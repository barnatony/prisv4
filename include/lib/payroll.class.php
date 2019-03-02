<?php
/*
 * ----------------------------------------------------------
* Filename : Payroll.handle.php
* Classname: payroll.class.php
* Author : Rufus Jackson
* Database : preview_payroll_db
* Oper : Payroll Run
* ----------------------------------------------------------
*/
require_once (__DIR__ . "/database.class.php");
require_once (__DIR__ . "/itCalculation.class.php");
require_once (__DIR__ . "/noticePeriod.class.php");
require_once (__DIR__ . "/itDeclaration.class.php");
require_once (__DIR__ . "/leaveaccount.class.php");


class Payroll extends Database {
	/* Member variables */
	var $payroll_month_year; // 032016 , 122015
	var $financial_year; // 201516
	var $current_year; // take first four digit of financial year
	var $financial_year_start; // take first four digit of financial year and concatenate with -04-01
	var $financial_year_end; // take fin_year_start add a year to it and concatenate with -03-31
	var $attendance_start_date;
	var $remaining_months;
	var $startDate;
	var $endDate;
	var $salaryDays;
	var $prev_month_end; // take payroll month year and concatenate with 2016-03-31
	var $affected_ids;
	var $employee_ids; // VM0001,VM00002
	var $updated_by;
	var $isIt;
	var $conn;
	var $masterString;
	var $allowDeduString;
	/* Member functions */
	function __construct($month_year, $fin_year, $updated_by) {
		parent::__construct ();
		ini_set ( 'memory_limit', MEMORY_LIMIT );
		ini_set('max_execution_time', MAX_EXECUTION_TIME);
		date_default_timezone_set(DEFAULT_TIMEZONE);
		$this->daysInMonth();
		//echo "Start: {$this->startDate},End:{$this->endDate},SalDays:{$this->salaryDays}<br>";
		$month_start = substr ( $month_year, 2, 4 ) . "-" . substr ( $month_year, 0, 2 ) . "-01";
		$this->payroll_month_year = $month_year;
		$this->financial_year_start = substr ( $fin_year, 0, 4 ) . "-04-01";
		$this->financial_year_end = (substr ( $fin_year, 0, 4 ) + 1) . "-03-31";
		$this->remaining_months = ItCalculation::monthsFromDate ( $month_start, $this->financial_year_end, false );
		$this->prev_month_end = substr ( $month_year, 2, 4 ) . "-" . (substr ( $month_year, 0, 2 ) - 1) . "-31";
		$this->current_year = substr ( $month_year, 2, 4 );
		$this->financial_year = $fin_year;
		$this->updated_by = $updated_by;

		/*$getItsummary = new ItDeclaration($this->_connection);
		 $getItsummary->conn = $conn;
		 $itsummary->taxSummaryData();*/
	}

	function daysInMonth(){

		$result = mysqli_query ( $this->_connection, "SELECT salary_days,attendance_period_sdate attendance_dt
		FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'" );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->_connection));
		$this->attendance_start_date =$row['attendance_dt'];
		if($row['attendance_dt'] !=1){
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".$row['attendance_dt'];
			$this->startDate = date("Y-m-d",strtotime("{$this->startDate} -1 months"));
			$this->endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($row['attendance_dt']-1);
		}else{
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
			$this->endDate = date('Y-m-t',strtotime($this->startDate));
		}

		if(is_numeric($row['salary_days']))
			$this->salaryDays =  $row['salary_days'];
		else{
			if($row['salary_days']=='ad')
				$this->salaryDays = ((strtotime($this->endDate) - strtotime($this->startDate))/ (60 * 60 * 24) ) +1;
			else if($row['salary_days']=='wd')
				$this->salaryDays = self::getWorkingDays($this->startDate,$this->endDate,array());
			else
				$this->salaryDays = ((strtotime($this->endDate) - strtotime($this->startDate))/ (60 * 60 * 24))+1;
		}
		$this->isWeekday = $row['salary_days'];
		$stmt = "SELECT cp.pay_structure_id FROM company_pay_structure cp WHERE cp.display_flag = 1 AND cp.type = 'D';";
		$result = mysqli_query($this->_connection,$stmt);
		$this->isIt=0;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
		 	if($row['pay_structure_id'] =='c_it')
		 		$this->isIt= 1;
			}
	}

	public function previewPayroll($preview_for, $affected_ids, $isFnF, $againRunFlag) {
		$this->affected_ids = $affected_ids;
		$leave_account = new leave_account($this->_connection);
		$leave = $leave_account->creditlrPreviewPayroll(trim($affected_ids,"\'"),$this->leaves);
		
		$employees = $this->calculateAllowancesDeductions ( $preview_for, $affected_ids, $isFnF, $againRunFlag );
		new ItCalculation ( $employees, $this->financial_year, $this->payroll_month_year, $this->updated_by, $this->isIt, $this->_connection, $againRunFlag );
		return array (true,
				array($employees,
						count ( $employees ))
		);
	}
	protected function calculateAllowancesDeductions($cal_for, $affected_ids, $isFnF, $againRunFlag) {
		$json = [ ];
		$e_ids = explode(',',$affected_ids);
		//print_r($e_ids);
		
		foreach ( $e_ids as $e_ids ) {
			/*
			$emp_id = trim($e_ids,"\'"); 
			$updteRegularaization = "UPDATE attendance_summary ar
								INNER JOIN attendance_regularization re
								ON ar.employee_id = re.employee_id AND re.day = ar.days
								SET late_approved = (CASE WHEN re.regularize_type='Late' AND re.status = 'A' AND re.day = ar.days THEN 1 ELSE 0 END),
									early_approved = (CASE WHEN re.regularize_type='EarlyOut' AND re.status = 'A' AND re.day = ar.days  THEN 1 ELSE 0 END)
								WHERE ar.employee_id='$emp_id' AND re.day BETWEEN '$this->startDate' AND '$this->endDate'
								AND ar.days BETWEEN '$this->startDate' AND '$this->endDate';";
			$result= mysqli_query ( $this->_connection, $updteRegularaization);
			
			$UpdateLateLop ="UPDATE payroll_preview_temp p
							INNER JOIN employee_work_details w
							ON p.employee_id= w.employee_id
							SET  
							late_lop =IFNULL((SELECT IF(lop_value='',0,lop_value) llop FROM latelop_slab WHERE (SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(late_early_time))) FROM (SELECT days,IF((pay_day IN(0,0.5) AND day_type='W') OR (SUBSTRING_INDEX(day_type,'-',-1)=1),'',lateIn) late_early_time ,SEC_TO_TIME((TIME_TO_SEC(lateIn)))FROM attendance_summary s INNER JOIN employee_work_details w ON s.employee_id = w.employee_id WHERE s.employee_id='$emp_id' AND s.shift_id!='SH63590' AND w.exempt_attn !=1 AND lateIn!='-' AND (day_type ='W' OR SUBSTRING_INDEX(day_type,'-',1) NOT IN(1,'FH')) AND day_type!=1 AND late_approved ='0' AND days BETWEEN '$this->startDate' AND '$this->endDate' 
									  UNION
									  SELECT days,IF((pay_day IN(0,0.5) AND day_type='W') OR (SUBSTRING_INDEX(day_type,'-',-1)=1),'',earlyOut) late_early_time,SEC_TO_TIME((TIME_TO_SEC(earlyOut))) FROM attendance_summary s INNER JOIN employee_work_details w ON s.employee_id = w.employee_id WHERE s.employee_id='$emp_id'AND s.shift_id!='SH63590' AND w.exempt_attn !=1 AND earlyOut!='-' AND (day_type ='W' OR SUBSTRING_INDEX(day_type,'-',1) NOT IN(1,'SH')) AND day_type!=1 AND early_approved ='0' AND days BETWEEN '$this->startDate' AND '$this->endDate')t) BETWEEN from_time AND to_time),0),
							alop =IFNULL((SELECT IFNULL(SUM(IF(day_type='W' AND pay_day<1,1-pay_day,IF(day_type!='W' AND SUBSTRING_INDEX(day_type,'-',-1)>pay_day,SUBSTRING_INDEX(day_type,'-',-1)-pay_day,''))),0) alop
									FROM attendance_summary WHERE employee_id='$emp_id' AND SUBSTRING_INDEX(day_type,'-',-1) IN('W',0.5) AND days BETWEEN '$this->startDate' AND '$this->endDate'),0)
						 WHERE p.employee_id='$emp_id' AND w.exempt_attn!=1;";
			$result= mysqli_query ( $this->_connection, $UpdateLateLop);
			*/
			/*echo "CALL CALC_ALW_DED_PT('$cal_for','{$e_ids}','$this->payroll_month_year',
			'$this->startDate',$this->financial_year,$this->salaryDays,'$this->isWeekday',$isFnF,$againRunFlag,'$this->updated_by',@affected,@allowclms)";*/
		 
			$query = "CALL CALC_ALW_DED_PT('$cal_for','{$e_ids}','$this->payroll_month_year',
			'$this->startDate',$this->financial_year,$this->salaryDays,'$this->isWeekday',$isFnF,$againRunFlag,'$this->updated_by',@affected,@allowclms)";

			
			if (mysqli_multi_query ( $this->_connection, $query )) {
				if ($result1 = mysqli_use_result ( $this->_connection )) {
					while ( $row = mysqli_fetch_array ( $result1, MYSQLI_ASSOC ) ) {
						array_push ( $json, $row );
					}
					do {
						if ($result = mysqli_store_result ( $this->_connection )) {
							mysqli_free_result ( $result );
						}
					} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
				}
			}
		}
		
		return $json;
	}
	public function runPayroll($isexit) {

		$leave_account = new leave_account($this->_connection);
		$leave_requests = $leave_account->getLeaveRequest($this->payrollempMoveFormat,null,$this->payroll_month_year);

		//Check whether the month is future dated than the calender date
		//echo ( ($_SESSION ['payrollYear']) . '-' . $_SESSION ['monthNo']); echo date('Y-m');
		if( date('Y-m',strtotime( ($_SESSION ['payrollYear']) . '-' . $_SESSION ['monthNo']))>date('Y-m'))
			return array(false,"You Cannot Run Payroll for the Future Date.");
			//Check whether they are running a day after the attendance close day
			//if( date('Y-m',strtotime ( ($_SESSION ['payrollYear']) . '-' . $_SESSION ['monthNo']))==date('Y-m') && $this->attendance_start_date+2 != date('d'))
			//return array(false,"You cannot run before the Attendance Close Date.");
			//check whether they have no pending leave requests for the running month
			//if($leave_requests[2]!=[])
				//return array(false,"You have pending Leave Requests to be Approved for this month. <a class=\"btn btn-xs btn-default\" href=\"leave.php#leaveRequest\">Approve Leaves here</a>");



				$leaveRulesTot = $this->leaveRulesmonthly . $this->leaveRulesquarterly .$this->leaveRulesyearly;
				$dynamicColums = $leaveRulesTot . $this->allowDeduString;
				// make colums set into zero when payroll was previewed
				$queryStmt = str_replace ( ",", "=0,", $dynamicColums );
				mysqli_query ( $this->_connection, "INSERT INTO payroll (inc_arrear,employee_id,updated_by,employee_name,worked_days,$dynamicColums lop,
						alop,late_lop,other_leave,gross_salary,is_pay_pending,total_deduction,net_salary)
						SELECT  p.inc_arrear,p.employee_id,p.updated_by,w.employee_name,worked_days,$dynamicColums lop,alop,late_lop,other_leave,gross_salary,is_pay_pending,
						total_deduction,net_salary from  employee_work_details w INNER JOIN  payroll_preview_temp p
						WHERE  w.enabled=1  AND w.employee_id =p.employee_id AND p.employee_id  IN ($this->payrollempMoveFormat) AND not  exists (
						select p.employee_id,p.updated_by,w.employee_name FROM  employee_work_details w INNER JOIN  payroll p
						WHERE w.employee_id =p.employee_id AND p.employee_id  IN ($this->payrollempMoveFormat)  AND p.month_year='$this->currentMonthDate');" );
				$checkInserted_occur = mysqli_insert_id ( $this->_connection );

				if ($checkInserted_occur !== 0) {
					$leaveRuleFields = str_replace ( ",", "=0,", $leaveRulesTot );
					// credit Leave For monthly bases
					if (($_SESSION ['creditLeaveBased'] == 'calYear' && $_SESSION ['monthNo'] != '12') || ($_SESSION ['creditLeaveBased'] == 'finYear' && $_SESSION ['monthNo'] != '03')) {
						$this->leaveAccountCredit ( $this->empFormat, $leaveRulesTot, $this->leaveRulesmonthly );
					}  
					// credit Leave For quarterly bases
					if (($_SESSION ['creditLeaveBased'] == 'calYear' && ($_SESSION ['monthNo']== '03' || $_SESSION ['monthNo']== '06' || $_SESSION ['monthNo']== '09')) || ($_SESSION ['creditLeaveBased'] == 'finYear' && ($_SESSION ['monthNo']== '06' || $_SESSION ['monthNo']== '09' || $_SESSION ['monthNo']== '12'))) {
						$this->leaveAccountCredit ( $this->empFormat, $leaveRulesTot, $this->leaveRulesquarterly);
					}
					
					$emp_Ids = str_ireplace(",","','",$this->empFormat);
					if ($_SESSION ['creditLeaveBased'] == 'calYear' && ($_SESSION ['monthNo'] != '01' || $_SESSION ['monthNo'] != '12')){
                        $leaveaccUpdateQuery = "UPDATE emp_leave_account l
                                                INNER JOIN employee_work_details w
                                                ON l.employee_id = w.employee_id
                                                INNER JOIN company_leave_rules lr
                                                ON l.leave_rule_id = lr.leave_rule_id
                                                SET allotted = 12-".$_SESSION ['monthNo'].
                                                " WHERE l.employee_id IN('$emp_Ids') AND l.allotted+l.opening_bal =0 AND l.leave_rule_id IN('".str_ireplace(",","','",$leaveRulesTot)."')
                                                AND DATEDIFF(NOW(),w.employee_doj) >=SUBSTRING_INDEX(lr.allot_from,'|',1) AND year=".$_SESSION ['payrollYear'].";";
												
                        mysqli_query ( $this->_connection,$leaveaccUpdateQuery) or die(mysqli_error($this->_connection));
                    }
					$sql = "";
					if ($isexit == 1) {
						$noticePeriod = new noticePeriod ($this->_connection);
						//$noticePeriod->conn = $this->_connection;
						$result=$noticePeriod->exitEmployee(str_replace('\'','',$this->payrollempMoveFormat),$this->lastWorkingDate,$this->currentMonthDate);
					}
					$previewUpdate= "UPDATE payroll_preview_temp p INNER JOIN  employee_work_details w
					SET $leaveRuleFields $queryStmt lop=0,late_lop=0,alop=0,other_leave=0,worked_days=0,inc_arrear=0,net_salary=0,total_deduction=0,gross_salary=0,attn_lock=0,is_processed=1,is_pay_pending=0 WHERE w.enabled=1 AND p.employee_id IN ($this->payrollempMoveFormat);";
					mysqli_query ( $this->_connection,$previewUpdate) or die(mysqli_error($this->_connection));

					$payrollUpdate= "UPDATE payroll p
									INNER JOIN employee_work_details w
									ON p.employee_id = w.employee_id SET  month_year='$this->currentMonthDate',year='$this->financial_year',
									p.designation_id = w.designation_id, p.department_id = w.department_id,p.branch_id = w.branch_id, p.team_id = w.team_id
									WHERE  month_year='0000-00-00' AND  year IS NULL;";
					mysqli_query ( $this->_connection,$payrollUpdate) or die(mysqli_error($this->_connection));

					$sql = "SELECT count(*) count FROM  payroll_preview_temp pt INNER JOIN employee_work_details w ON w.employee_id = pt.employee_id WHERE employee_doj < '$this->endDate' AND is_processed=0 AND w.enabled=1";
					$result = mysqli_query ( $this->_connection,$sql) or die(mysqli_error($this->_connection));

					while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
						$sql_sub = "";
						if ($row ['count'] == 0) {

							$tempUpdate= "UPDATE payroll_preview_temp SET is_processed=0 ,status_flag='A' ,total_deduction=0,net_salary=0;";
							mysqli_query( $this->_connection,$tempUpdate) or die(mysqli_error($this->_connection));
							$sql_sub = "INSERT INTO $this->master_db_name.company_payroll_details(is_lastpayroll,company_id,emp_count,active_emp_count,month_year,year,updated_by)
							VALUES(1,'$this->company_id','$this->empCount','$this->empCount','$this->currentMonthDate','$this->financial_year','$this->company_id');";
							mysqli_query( $this->_connection,$sql_sub) or die(mysqli_error($this->_connection));

							$this->payroll_flag = 1;
							list ( $monthNo, $payrollYear, $finiancialYear, $newcurYear, $nextyear_date, $fywithMonth, $noOfDaysInMonth, $calYearDate ) = $this->update_payroll_month ( $this->nextMonthDate );

							// Function Work nextFinicial Year Start with credited Yearly leaves Based oncalender year
							if ($_SESSION ['creditLeaveBased'] == 'calYear' && $monthNo == '01') {
								$result = mysqli_query ( $this->_connection, "SELECT employee_id FROM employee_work_details WHERE enabled=1" );
								$employee_id = "";
								while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
									$employee_id .= $row ['employee_id'] . ",";
								}
								$this->LeaveYearlyCedit ( $this->masterDbDate, (($payrollYear + 1) . "-01-01"), $payrollYear, $employee_id, $leaveRulesTot, $_SESSION ['curYear'] );
							}

							if ($_SESSION ['creditLeaveBased'] == 'calYear' && $monthNo == '04') {
								$result = mysqli_query ( $this->_connection, "SELECT employee_id FROM employee_work_details WHERE enabled=1" );
								$valueData = "";
								while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
									$valueData .= "('" . $row ['employee_id'] . "','$finiancialYear','$this->updated_by'),";
								}

								mysqli_query ( $this->_connection, "INSERT INTO employee_income_tax (employee_id, `year`,updated_by) VALUES " . substr ( $valueData, 0, - 1 ) );
								mysqli_query ( $this->_connection, "INSERT INTO employee_it_declaration (`employee_id`,`year`,`updated_by`) VALUES" . substr ( $valueData, 0, - 1 ) );
							}

							// Function Work nextFinicial Year Start with credited Yearly leaves
							if ($monthNo == 04 && $_SESSION ['creditLeaveBased'] == 'finYear') {
								$result = mysqli_query ( $this->_connection, "SELECT employee_id FROM employee_work_details WHERE enabled=1" );
								$employee_id = "";
								$valueData = "";
								while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
									$employee_id .= $row ['employee_id'] . ",";
									$valueData .= "('" . $row ['employee_id'] . "','$finiancialYear','$this->updated_by'),";
								}

								mysqli_query ( $this->_connection, "INSERT INTO employee_income_tax (employee_id, `year`,updated_by)  VALUES " . substr ( $valueData, 0, - 1 ) );
								mysqli_query ( $this->_connection, "INSERT INTO employee_it_declaration (`employee_id`,`year`,`updated_by`) VALUES" . substr ( $valueData, 0, - 1 ) );

								$this->LeaveYearlyCedit ( $this->masterDbDate, (($payrollYear + 1) . "-04-01"), $finiancialYear, $employee_id, $leaveRulesTot, $this->financial_year );

								// $this->LeaveYearlyCedit($nextyearDate,$finiancialYear,$employee_id,$leaveRulesTot,$this->financial_year,$newcurYear);
							}

							$_SESSION ['partialRun'] = 0;
							$_SESSION ['financialYear'] = $finiancialYear;
							$_SESSION ['curYear'] = $newcurYear; // current Year it means 201516 when itsget jan year was 2015
							$_SESSION ['nextyear_date'] = $nextyear_date;
							$_SESSION ['fywithMonth'] = $fywithMonth;
							$_SESSION ['noOfDaysInMonth'] = $noOfDaysInMonth;
							$_SESSION ['payrollYear'] = $payrollYear; // payroll Year it means 201516 when itsget jan year was 2016 not curent year 2015
							$_SESSION ['monthNo'] = $monthNo;
							$_SESSION ['current_payroll_month'] = $this->masterDbDate;
							$_SESSION ['calYear'] = $calYearDate; // 01/01/2016
							mysqli_query ( $this->_connection, "UPDATE $this->master_db_name.company_details SET current_payroll_month='$this->masterDbDate' where company_id='$this->company_id'" );
						} else {
							// To view Payslip Based on Run
							$_SESSION ['partialRun'] = 1;
							mysqli_query ( $this->_connection, "INSERT INTO $this->master_db_name.company_payroll_details(is_lastpayroll,company_id,emp_count,active_emp_count,month_year,year,updated_by)
									VALUES(0,'$this->company_id','$this->empCount','$this->empCount','$this->currentMonthDate','$this->financial_year','$this->company_id')" );
						}
					}

				}
				return array(true,"Payroll Ran Successfully");
	}
	public function leaveAccountCredit($empIds, $leaveRules, $monthlyLeave) {
		// echo "CALL LEAVEACCOUNT_CREDIT('$this->financial_year','$empIds','$leaveRules','$monthlyLeave','$this->updated_by');";
		$current_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
		$stmt = mysqli_prepare ( $this->_connection, "CALL LEAVEACCOUNT_CREDIT(?,?,?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sssss', $current_year, $empIds, $leaveRules, $monthlyLeave, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
	}
	public function LeaveYearlyCedit($lastPayrollDate, $next_yeardate, $current_year, $employee_id, $leaveRulesTot, $past_year) {
		// echo "CALL LEAVEYEARLY_CREDIT('$lastPayrollDate','$next_yeardate','$current_year','$employee_id','$leaveRulesTot','$past_year','$this->updated_by')";
		$stmt = mysqli_prepare ( $this->_connection, "CALL LEAVEYEARLY_CREDIT(?,?,?,?,?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sssssss', $lastPayrollDate, $next_yeardate, $current_year, $employee_id, $leaveRulesTot, $past_year, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
	}
	protected function update_payroll_month($date) {
		$split_date = explode ( '-', $date );
		$yrdata = strtotime ( $date );
		$monthNo = $split_date [1];

		// Fincancial Year 201516
		if ($split_date [1] == "04") {
			// 2016-04-01
			$curYear = date ( 'Y', strtotime ( $date ) ); // 2016
			$payrollYear = $curYear; // 2016
			$finiancialYear = ($split_date [0]) . substr ( ($curYear + 1), - 2 ); // 201617
			$nextyear_date = '01/04/' . ($curYear + 1); // 01/04/2017
			$fywithMonth = date ( 'M', $yrdata ) . "-" . ($split_date [0]) . "-" . substr ( ($curYear + 1), - 2 ); // Apr-2016-17
			$noOfDaysInMonth = cal_days_in_month ( CAL_GREGORIAN, $split_date [1], $curYear ); // 04,2016
		} elseif ($split_date [1] == "01" || $split_date [1] == "03" || $split_date [1] == "02") {
			// 2016-01-01 To 2016-03-01
			$curYear = date ( 'Y', strtotime ( $date ) ) - 1; // 2015
			$payrollYear = date ( 'Y', strtotime ( $date ) ); // 2016
			$finiancialYear = ($split_date [0] - 1) . substr ( ($curYear + 1), - 2 ); // 201516
			$nextyear_date = '01/04/' . ($payrollYear); // 01/04/2016
			$fywithMonth = date ( 'M', $yrdata ) . "-" . ($split_date [0] - 1) . "-" . substr ( ($payrollYear), - 2 ); // MAR-2015-16
			$noOfDaysInMonth = cal_days_in_month ( CAL_GREGORIAN, $split_date [1], $payrollYear ); // 03/2016
		} else {
			// 2015-05-01 To 2015-12-01
			$curYear = date ( 'Y', strtotime ( $date ) ); // 2015
			$payrollYear = $curYear; // 2015
			$nextyear_date = '1/04/' . ($payrollYear + 1); // 01/04/2016
			$finiancialYear = $payrollYear . substr ( ($payrollYear + 1), - 2 ); // 201516
			$fywithMonth = date ( 'M', $yrdata ) . "-" . $payrollYear . "-" . substr ( ($payrollYear + 1), - 2 ); // OCT-2015-16
			$noOfDaysInMonth = cal_days_in_month ( CAL_GREGORIAN, $split_date [1], $payrollYear ); // 062015
		}
		$calYearDate = '01/01/' . (date ( 'Y', strtotime ( $date ) ) + 1);
		return array (
				$monthNo,
				$payrollYear,
				$finiancialYear,
				$curYear,
				$nextyear_date,
				$fywithMonth,
				$noOfDaysInMonth,
				$calYearDate
		);
	}
	public function getSalaryDetails($employee_id, $isCurrentMonth = NULL) {
		$json = array ();
		Session::newInstance ()->_setGeneralPayParams ();
		$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
		$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
		$allAllowNameId = "";
		$allowDeduction = "";
		$miscAllowance = "";
		$miscDeduction = "";
		foreach ( $allowDeducArray ['A'] as $allow ) {
			$allAllowNameId .= "p." . $allow ['pay_structure_id'] . " " . "A_" . str_replace ( " ", "_", $allow ['display_name'] ) . ",";
		}
		foreach ( $allowDeducArray ['D'] as $allow ) {
			$allowDeduction .= "p." . $allow ['pay_structure_id'] . " " . "D_" . str_replace ( " ", "_", $allow ['display_name'] ) . ",";
		}
		foreach ( $miscallowDeducArray ['MD'] as $allow ) {
			$miscAllowance .= "p." . $allow ['pay_structure_id'] . " " . "MD_" . str_replace ( " ", "_", $allow ['display_name'] ) . ",";
		}
		foreach ( $miscallowDeducArray ['MP'] as $allow ) {
			$miscDeduction .= "p." . $allow ['pay_structure_id'] . " " . "MP_" . str_replace ( " ", "_", $allow ['display_name'] ) . ",";
		}
		$result = mysqli_query ( $this->_connection, "SELECT " . $allAllowNameId . $miscAllowance . "IFNULL(p.inc_arrear,0) MP_IncArrears,CONCAT('<b>',p.gross_salary,'</b>') Gross_Salary," . $allowDeduction . $miscDeduction . "CONCAT('<b>',p.total_deduction,'</b>') MD_Deduction FROM payroll_preview_temp p  WHERE p.employee_id='$employee_id' " );
		if (! $result) {
			return array(false,mysqli_error ( $this->_connection ) );
		}
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//print_r($json);
		return array(true,$json);
	}
	function generateHtmlForPdf($employeeId,$monthYear,$allowDeduStr,$masterStr,$isEmail,$ignoreAlreadySent,$emailResendId){
		$month_name = date ( 'F Y', strtotime ( $monthYear ) );
		$attachmentNames=array();$attachments=array();
		require_once (__DIR__ . "/payslipDesign.class.php");
		$extraCondition = ($ignoreAlreadySent == 1 && $isEmail=="ForEmailSend") ? "AND is_mailSent!=1" : '';
		$payslipDesign = new payslipDesign ();
		$payslipDesign->conn = $this->_connection;
		$companyAddressQuery='';
		$designCustomised = $payslipDesign->select();
		$logo_s = explode ( ",", $designCustomised[0]["logo"] );
		$logo_flag = $logo_s [2];
		$emp_id= trim($employeeId,"(,),'");
		
		$companyAddressQuery = "SELECT c.company_email,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,c.company_mobile,
				                               c.company_pin_code FROM companies c INNER JOIN employee_work_details w ON w.company_id = c.company_id  WHERE w.employee_id = '$emp_id'";
		$result = mysqli_query ( $this->_connection, $companyAddressQuery);
		$companyAddress = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		if($companyAddress!=''){
		
			$designCustomised [0] ['company_name'] = $companyAddress["company_name"];
			$designCustomised [0] ['company_logo'] = $companyAddress["company_logo"];
			$designCustomised [0] ['company_build_name'] = $companyAddress["company_build_name"];
			$designCustomised [0] ['company_street'] = $companyAddress["company_street"];
			$designCustomised [0] ['company_area'] = $companyAddress["company_area"];
			$designCustomised [0] ['company_mobile'] = $companyAddress["company_mobile"];
			$designCustomised [0] ['company_city'] = $companyAddress["company_city"];
			$designCustomised [0] ['company_pin_code'] = $companyAddress["company_pin_code"];
		}
		$year = date ( 'Y', strtotime ( $monthYear ) );
		$year =$year.(substr($year,2)+1);
		$itDeclaration = new ItDeclaration ();
		$itDeclaration->conn = $this->_connection;
		$itsummary = $itDeclaration->taxSummaryData($emp_id,$year);

		$a_json = array ();
		// require 'phpMailer/PHPMailerAutoload.php';
		if($isEmail==1){
			$htmlStyle = '<style>
body,pre { font-family: Open Sans, sans-serif; font-size: 10pt; }td {padding: 4px;border:0;}.font_bold{
font-weight: bold;}.line_2 {border-right:1px solid #000;}th {text-align: left;}.alignLeft {text-align: left;}.alignRight{text-align:right;}
.header{text-align: center;}img{width:10%;height:10%;align:"middle";} table#t02 {border:1px solid #000;border-collapse: collapse;}table#t02 tr {border-top:1px solid #000;border-collapse: collapse;
}table#t02 td {border-left:1px solid #000;border-collapse: collapse;}table#t01 {border:1px solid #000;border-collapse: collapse;}
table#t01 tr {border-left:1px solid #000;border-collapse: collapse;}table#t03 tr {border-top:1px solid #000;}
.line {border-top:1px solid #000;}.line_1 {border-bottom:1px solid #000;}.coloredBorderTop{ border-top:1px solid ' . strtoupper ( $designCustomised [0] ['color_code'] ) .';}.coloredBorderBottom{ border-bottom:1px solid ' . strtoupper ( $designCustomised [0] ['color_code'] ) .';}
</style>';
		}else if($isEmail==0){
			$htmlStyle = '<style>
table, th, td { border-collapse: collapse;} td {padding: 4px;border:0;}.font_bold{font-weight: bold;} .line_2 {border-right:1px solid #000;}
th { text-align: left;}.alignLeft { text-align: left;}.align_text{text-align:right;}.header{ text-align: center;}
img{  width:10%;  height:10%;  align:"middle";}
table#t02 {border:1px solid #000;font-size: 10pt;}table#t02 tr {border-top:1px solid #000;}  table#t02 td {border-left:1px solid #000;}  table#t01 {border:1px solid #000;}  table#t01 tr {border-left:1px solid #000;}table#t03 tr {border-top:1px solid #000;}
.line {border-top:1px solid #000;} .line_1 {border-bottom:1px solid #000;}
.coloredBorderTop{ border-top:1px solid ' . strtoupper ( $designCustomised [0] ['color_code'] ) .';}.coloredHeader{ background-color: ' . strtoupper ( $designCustomised [0] ['color_code'] ) .';color:#fff;}.coloredBorderBottom{ border-bottom:1px solid ' . strtoupper ( $designCustomised [0] ['color_code'] ) .';}</style>';
			
		}
		// For Header Make

		$headerLogoAddress = "";
		$headerAddressLogo = "";
		$addressOnly = "";
		$logoOnly = "";
		$footer = "";
		$header = "";
		$footerLogoAddress = "";
		$footerAddressLogo = "";
		$headerLogoAddress .= '<table style="width:100%;" > <tr><td  style="width:33%" class="coloredBorderBottom"><img  style="width:28%;height:7%" src=' . $designCustomised [0] ['company_logo'] . '></td>
 <td colspan="3" class="font_bold" class="coloredBorderBottom">' . strtoupper ( $designCustomised [0] ['company_name'] ) . '<br>' . strtoupper ( $designCustomised [0] ['company_build_name'] ) . ',' . strtoupper ( $designCustomised [0] ['company_street'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_area'] ) . ', Ph:'. strtoupper ( $designCustomised [0] ['company_mobile'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_city'] ) . ',' . $designCustomised [0] ['company_pin_code'] . '.</td></tr></table>';
	
		$footerLogoAddress .= '<table style="width:100%;"> <tr><td   style="width:33%" class="coloredBorderTop"><img style="width:28%;height:7%" src=../' . $designCustomised [0] ['company_logo'] . '></td>
         <td colspan="3" class="coloredBorderTop">' . strtoupper ( $designCustomised [0] ['company_name'] ) . '<br>' . strtoupper ( $designCustomised [0] ['company_build_name'] ) . ',' . strtoupper ( $designCustomised [0] ['company_street'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_area'] ) . ', Ph:'. strtoupper ( $designCustomised [0] ['company_mobile'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_city'] ) . ',' . $designCustomised [0] ['company_pin_code'] . '.</td></tr></table>';

		$headerAddressLogo .= '<table style="width:100%;"> <tr > <td  colspan="3" class="coloredBorderBottom" >' . strtoupper ( $designCustomised [0] ['company_name'] ) . '<br>' . strtoupper ( $designCustomised [0] ['company_build_name'] ) . ',' . strtoupper ( $designCustomised [0] ['company_street'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_area'] ) . ', Ph:'. strtoupper ( $designCustomised [0] ['company_mobile'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_city'] ) . ',' . $designCustomised [0] ['company_pin_code'] . '.</td><td style="width:25%" class="coloredBorderBottom"><img   style="width:25%;height:7%" src=' . $designCustomised [0] ['company_logo'] . '></td></tr></table>';

		$footerAddressLogo .= '<table style="width:100%;"> <tr> <td  colspan="3"  class="coloredBorderTop">' . strtoupper ( $designCustomised [0] ['company_name'] ) . '<br>' . strtoupper ( $designCustomised [0] ['company_build_name'] ) . ',' . strtoupper ( $designCustomised [0] ['company_street'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_area'] ) . ', Ph:'. strtoupper ( $designCustomised [0] ['company_mobile'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_city'] ) . ',' . $designCustomised [0] ['company_pin_code'] . '.</td><td style="width:33%" class="coloredBorderTop"><img style="width:28%;height:7%" src=' . $designCustomised [0] ['company_logo'] . '></td></tr></table>';

		$addressOnly .= '<table style="width:100%;"><tr><td  colspan="10"   class="coloredBorderTop" >' . strtoupper ( $designCustomised [0] ['company_name'] ) . '<br>' . strtoupper ( $designCustomised [0] ['company_build_name'] ) . ',' . strtoupper ( $designCustomised [0] ['company_street'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_area'] ) . ', Ph:'. strtoupper ( $designCustomised [0] ['company_mobile'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_city'] ) . ',' . $designCustomised [0] ['company_pin_code'] . '.</td></tr></table>';

		$logoOnly .= '<table style="width:100%;"><tr class="line"><th  colspan="5" style="text-align:right;" ><img    style="width:28%;height:7%" src=' . $designCustomised [0] ['company_logo'] . '></th></tr></table>';
		
	if ($logo_flag == "0") {

			$logo_f = explode ( ",", $designCustomised [0] ['logo'] );
		
			if ($logo_f [0] == "Address") {
				$header .= $headerAddressLogo;
			} else {
				$header .= $headerLogoAddress;
			}
		} else {

			if ($logo_flag == "01" && $logo_flag !== "0" && $logo_flag !== "1") {

				$logo_f = explode ( ",", $designCustomised [0] ['logo'] );
				if ($logo_f [0] == "Address") {
					$header .= $addressOnly;
				} else {
					$header .= $logoOnly;
				}
			}
		}

		// For Footer MAke
		if ($logo_flag == "1" && $logo_flag !== "01") {

			$logo_f = explode ( ",", $designCustomised [0] ['logo'] );
			if ($logo_f [0] == "Address") {
				$footer .= $footerAddressLogo;
			} else {
				$footer .= $footerLogoAddress;
			}
		} else {

			if ($logo_flag == "01" && $logo_flag !== "0" && $logo_flag !== "1") {

				$logo_f = explode ( ",", $designCustomised [0] ['logo'] );
				if ($logo_f [1] == "Address") {
					$footer .= $addressOnly;
				} else {
					$footer .= $logoOnly;
				}
			}
		}
		
		$html_s = explode ( ',', $designCustomised [0] ['clo_left'] );
		$html_r = explode ( ',', $designCustomised [0] ['clo_right'] );
		$employeeDetails = array_combine ( $html_s, $html_r );

		$masterstring = $master_misc = "";
		
		$masterMiscArray = explode('!',$masterStr);
		$master_allow = $masterMiscArray[0];
		$master_misc = $masterMiscArray[1];
		if($master_misc != '')
			$master_misc =  "'0' ".str_replace(",",", '0' ",str_replace(',','1, ',substr($master_misc,0,-1)))."1,";
		
		$masterArray = explode(',',"sal.".str_replace(',',',sal.', substr($master_allow,0,-1)));
		$aliasArray = explode(',',(str_replace(',','1,',substr($master_allow,0,-1))).'1');
		$misc_alias = explode(',',(str_replace(',','1,',substr($masterMiscArray[1],0,-1))).'1');
		$temp = array_combine($masterArray,$aliasArray);
		
		foreach($temp as $key=>$val) // Loop though one array
			$masterstring.= $key.' ' .$val.','; // combine 'em
		
		$salaryHistoryQuery = "SELECT employee_id FROM employee_salary_details_history
							   WHERE employee_id IN $employeeId AND '$monthYear' BETWEEN effects_from AND effects_upto;";
		//echo $salaryHistoryQuery; die();
		$result = mysqli_query ( $this->_connection, $salaryHistoryQuery);
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		
		$histCond = $whereCond = "";
		if($row){
			$histCond ="_history";
			$whereCond = "WHERE '$monthYear' BETWEEN effects_from AND effects_upto";
		}else{
			$salEffectsCheck = "SELECT w.employee_doj,sal.effects_from,his.effects_from hist_effectsFrom
								FROM employee_work_details w
								INNER JOIN employee_salary_details sal
								ON w.employee_id = sal.employee_id
								LEFT JOIN employee_salary_details_history his
								ON w.employee_id = his.employee_id
								WHERE w.employee_id IN $employeeId ORDER BY his.effects_from LIMIT 0,1;";
			//echo $salEffectsCheck; die();
			$result = mysqli_query ( $this->_connection, $salEffectsCheck);
			$row1 = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		}
		//print($row1['effects_from']); die();
		$masterstring1 =''; $cond = 'sal.employee_salary_amount';
		if(!$row){
			if($row1['employee_doj'] != $row1['effects_from'] && $row1['employee_doj'] != $row1['hist_effectsFrom']){
				
				foreach($aliasArray as $val) // Loop though one array
					$masterstring1.= "'-' " .$val.",";
					
			$masterstring = $masterstring1;
			$master_misc = str_replace('0','-',$master_misc);
			$cond = "'-' ";
			}
		}
		$array = $allowDeduStr."gross_salary,total_deduction,net_salary";
		$alw_array = explode(',',$array);
		$YTDstring = $currentmonth = "";
		if(date('m', strtotime($monthYear))<'04'){
			$st_limit = date('Y-04-d', strtotime($monthYear));
			$st_limit = date("Y-m-d", strtotime("-1 year", strtotime($st_limit)));
		}else 
			$st_limit = date('Y-04-d', strtotime($monthYear));
		
		foreach($alw_array as $key){ // Loop though one array
			$YTDstring.= "SUM(CASE WHEN month_year BETWEEN '".$st_limit."' AND '".$monthYear."' THEN ".$key. " END) " .$key."2,";
			$currentmonth.="SUM(CASE WHEN month_year ='".$monthYear."' THEN ".$key. " END) " .$key.",";
		}
		$currentmonth.= "'".$monthYear."' month_year,SUM(CASE WHEN month_year ='".$monthYear."' THEN lop+alop END) lop,SUM(CASE WHEN month_year ='".$monthYear."' THEN late_lop END) late_lop,SUM(CASE WHEN month_year ='".$monthYear."' THEN worked_days END) worked_days,SUM(CASE WHEN month_year ='".$monthYear."' THEN inc_arrear END) inc_arrear,";
		//echo $mas_str; die();
				
		$payrollstring = "s.".str_replace(',',',s.',substr($allowDeduStr,0,-1)).",";
		
		$stmt = "SELECT s.inc_arrear,s.month_year, w.employee_id ,  CONCAT(w.employee_name,' ',w.employee_lastname) employee_name
                                 ,s.gross_salary,s.total_deduction,s.net_salary,dep.department_name,
								 p.employee_bank_branch,DATE_FORMAT(w.employee_doj, '%d/%m/%Y') as employee_doj,js.status_name,des.designation_name,
							     DATE_FORMAT(p.employee_dob, '%d/%m/%Y') as employee_dob,p.employee_phone , p.employee_mobile ,p.employee_email,
     		                     p.employee_pan_no ,p.employee_aadhaar_id,p.employee_bank_name ,p.employee_acc_no ,p.employee_bank_ifsc ,br.branch_name,
     		                     w.employee_emp_pf_no,w.employee_emp_uan_no,w.employee_emp_esi_no," . $payrollstring . $masterstring .$master_misc.$cond." gross_salary1,
								 lop,late_lop,DATE_FORMAT(np.last_working_date,'%d/%m/%Y') last_working_date,s.worked_days
     		                     FROM
     		                     employee_work_details w
     		                     INNER JOIN employee_personal_details p
     		                     ON w.employee_id = p.employee_id
     		                     INNER JOIN employee_salary_details$histCond sal
     		                     ON w.employee_id = sal.employee_id
     		                     INNER JOIN payroll s
     		                     ON w.employee_id = s.employee_id $extraCondition
     		                     LEFT JOIN emp_notice_period np
     		                     ON w.employee_id = np.employee_id
     		                     INNER JOIN company_designations des
     		                     ON w.designation_id = des.designation_id
     		                     INNER JOIN company_departments dep
     		                     ON w.department_id = dep.department_id
     		                     INNER JOIN company_branch br
     		                     ON w.branch_id = br.branch_id
     		                     LEFT JOIN company_payment_modes pm
     		                     ON w.payment_mode_id = pm.payment_mode_id
     		                     INNER JOIN company_job_statuses js
     		                     ON w.status_id = js.status_id
     		                     AND w.employee_id =s.employee_id
     		                     AND w.employee_id IN $employeeId AND s.month_year='$monthYear' $whereCond;";
			
			$stmt2 = "SELECT employee_id,employee_name,department_name,employee_bank_branch,employee_doj,status_name,designation_name,
							 employee_dob,employee_phone,employee_mobile,employee_email,employee_pan_no,employee_aadhaar_id,employee_bank_name,
							 employee_acc_no,employee_bank_ifsc,branch_name,employee_emp_pf_no,employee_emp_uan_no,employee_emp_esi_no,"
							 .$currentmonth.str_replace(',','1, ',str_replace('!','',$masterStr)).$YTDstring." gross_salary1,
						     inc_arrear inc_arrear2,last_working_date
					  FROM (
						  SELECT s.inc_arrear,s.month_year, w.employee_id ,  CONCAT(w.employee_name,' ',w.employee_lastname) employee_name
	                                 ,s.gross_salary,s.total_deduction,s.net_salary,dep.department_name,
									 p.employee_bank_branch,DATE_FORMAT(w.employee_doj, '%d/%m/%Y') as employee_doj,js.status_name,des.designation_name,
								     DATE_FORMAT(p.employee_dob, '%d/%m/%Y') as employee_dob,p.employee_phone , p.employee_mobile ,p.employee_email,
	     		                     p.employee_pan_no ,p.employee_aadhaar_id,p.employee_bank_name ,p.employee_acc_no ,p.employee_bank_ifsc ,br.branch_name,
	     		                     w.employee_emp_pf_no,w.employee_emp_uan_no,w.employee_emp_esi_no," . $payrollstring . $masterstring .$master_misc.$cond." gross_salary1,
									 lop,alop,late_lop,DATE_FORMAT(np.last_working_date,'%d/%m/%Y') last_working_date,s.worked_days
	     		          FROM employee_work_details w
	     		          INNER JOIN employee_personal_details p
	     		          ON w.employee_id = p.employee_id
	     		          INNER JOIN employee_salary_details$histCond sal
	     		          ON w.employee_id = sal.employee_id
	     		          INNER JOIN payroll s
	     		          ON w.employee_id = s.employee_id $extraCondition
	     		          LEFT JOIN emp_notice_period np
	     		          ON w.employee_id = np.employee_id
	     		          INNER JOIN company_designations des
	     		          ON w.designation_id = des.designation_id
	     		          INNER JOIN company_departments dep
	     		          ON w.department_id = dep.department_id
	     		          INNER JOIN company_branch br
	     		          ON w.branch_id = br.branch_id
	     		          LEFT JOIN company_payment_modes pm
	     		          ON w.payment_mode_id = pm.payment_mode_id
	     		          INNER JOIN company_job_statuses js
	     		          ON w.status_id = js.status_id
	     		          AND w.employee_id =s.employee_id
	     		          AND w.employee_id IN $employeeId AND s.month_year BETWEEN '$st_limit' AND '$monthYear' 
						  $whereCond ORDER BY s.month_year DESC)t GROUP BY employee_id;";
			//echo $stmt2; die();
			//INNER JOIN companies c ON c.company_id ='" . $_SESSION['company_id'] . "' and s.month_year='$monthYear'
			//echo $stmt; die();
			
			$result = mysqli_query ( $this->_connection, $stmt2);
			$num_rows = mysqli_num_rows ( $result );
			
			$r = 1;
			
			include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
			$mailsentDetails = [ ];
			/*Getting Company Pay Heads i.e basic,HRA,PT,ESI ...*/

			$allowances = array (); //all allowances
			$deductions = array (); //all deductions
			$miscPayments = array();
			$miscDeductions = array();
			// Allowances and Deduction
			Session::newInstance ()->_setGeneralPayParams ();
			$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );

			foreach ( $allowDeducArray ['A'] as $allow )
				$allowances [] = $allow ['display_name'] . "!" . $allow ['pay_structure_id'];
				foreach ( $allowDeducArray ['D'] as $dedu )
					$deductions [] = $dedu ['display_name'] . "!" . $dedu ['pay_structure_id'];

					array_push ( $allowances, "Arrear!inc_arrear" );
						

					// miscAllowances and miscDeduction
					Session::newInstance ()->_setGeneralPayParams ();
					$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );

					foreach ( $miscallowDeducArray ['MP'] as $miscAllow )
						$allowances[] = $miscAllow ['display_name'] . "!" . $miscAllow ['pay_structure_id'];
							
					foreach ( $miscallowDeducArray ['MD'] as $miscDedu)
						$deductions[] = $miscDedu ['display_name'] . "!" . $miscDedu ['pay_structure_id'];
					
					if(count($allowances)>count($deductions))
						$rowIterateCount = count($allowances);
					else
						$rowIterateCount = count($deductions);

					
					/* Looping each Employees */
					while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
						$html = '';
						$employee_email = $row ['employee_email']; //email of employee
						$monthrunned = $row ['month_year'];
						$noticedate = $row ['last_working_date'];
						$notice = date ( 'Y-d-m', strtotime ( $noticedate));
						$line_1 = ' ';
						$is_header= ' ';
						if($designCustomised [0] ['payslip_border']=='all_border'){
							$line_1 = 'line_1';
						}else {
							$line_1 = ' ';
						}
						
						if($designCustomised [0] ['color_header']=='yes'){
								
							$is_header= 'coloredHeader';
						}else {
							$is_header = ' ';
						}
						//General Information Table
						$content = '';
						$html .= '<br><br><br><br><br><table style="width:100%;font-size: 10pt;" ><tr><td class="font_bold" colspan="4" style="text-align:center;">';

						$html .= strtoupper ( "Payslip For The  Month  of " ) . " " . strtoupper ( $month_name ) . '
                          </td> </tr></table><br><table id="t01" style="width:100%;font-size: 10pt;table-layout:fixed;">
                             ';
						$panNum = isset ( $row ['employee_pan_no'] ) ? $row ['employee_pan_no'] : "password"; //get pan num
						$empId = isset ( $row ['employee_id'] ) ? $row ['employee_id'] : "Payslip"; //get employee ID

						foreach ( $employeeDetails as $k => $v ) { // $k - left columns $v - right columns
							// dynamcicloums of value and label set
							//left column
							$words = explode ( "#", $k ); //employee_id#ID ->[0] employee_id, [1] ID
							$words[1] = strtoupper($words[1]);
							if((substr($notice,0,7)==substr($monthYear,0,7)) && $words[1] == 'DOJ'){ //check for last working date
								$k_label = 'Last working';
								$k_value = 'last_working_date'; //employee_id
							}else{
								$k_label = substr ( $words[1], 0 ); //ID
								$k_value = trim(substr ( $words [0], 0 )); //employee_id
							}
										
							//right column
							$words_v = explode ( "#", $v );
							$words_v[1] = strtoupper($words_v[1]);
							if((substr($noticedate,0,7)==substr($monthYear,0,7)) && $words_v [1] == 'DOJ'){ //check for last working date
								$v_label= 'Last working';
								$v_value= 'last_working_date'; //employee_id
							}else{
								$v_label= substr ( $words_v [1], 0 ); //ID
								$v_value= trim(substr ( $words_v [0], 0 )); //employee_id
							}
								$v_label = substr ( $words_v [1], 0 );
								$v_value = trim(substr ( $words_v [0], 0 ));
								$rightVal = "-";
								$lefttVal = "-";
							if((!is_numeric($row [$k_value]) && $row [$k_value] !="") || (is_numeric($row [$k_value]) && $row [$k_value] !=0))
								$rightVal = $row [$k_value];
							if((!is_numeric($row [$v_value]) && $row [$v_value] !="") || (is_numeric($row [$v_value]) && $row [$v_value] !=0))
								$lefttVal = $row [$v_value];

							$html .= '<br><tr class="'.$line_1.'" style="width:100%;"><td class="font_bold "  style="width:21%">' . str_replace ( "_", " ", $k_label ) . ' </td>
                 <td class="line_2 "  style="width:29%;"> :&nbsp; ' . $rightVal. '</td><td  class="font_bold "  style="width:21%">' . str_replace ( "-", " ", $v_label ) . '</td><td class="line_2" style="width:29%;">:&nbsp; ' . $lefttVal. '</td></tr>';
							}
							$html .= ' </table><br><br> ';
							
							if($designCustomised [0] ['is_mastersalary']== 0){
								$html .= '<table class="'.$line_1.'" style="width:100%;font-size:10pt;" id="t02" border=1><tr> <th  class="header '.$is_header.'">EARNINGS</th>
        					<th  class="header '.$is_header.'">AMOUNT</th><th  class="header '.$is_header.'">DEDUCTIONS</th><th  class="header '.$is_header.'">AMOUNT</th></tr>';
							}else if($designCustomised [0] ['is_mastersalary']== 2){
								$html .= '<table class="'.$line_1.'" style="width:100%;table-layout:fixed;" id="t02" border=1><tr> <th  class="header '.$is_header.'" >EARNINGS</th><th  class="header '.$is_header.'" >AMOUNT</th>
        					<th  class="header '.$is_header.'" >YTD</th><th  class="header '.$is_header.'" >DEDUCTIONS</th><th  class="header '.$is_header.'" >AMOUNT</th><th  class="header '.$is_header.'" >YTD</th></tr>';
							}else{
								$html .= '<table class="'.$line_1.'" style="width:100%;font-size:12pt;" id="t02" border=1><tr> <th  class="header '.$is_header.'">EARNINGS</th><th  class="header '.$is_header.'">ACTUALS</th>
        					<th  class="header '.$is_header.'">AMOUNT</th><th  class="header '.$is_header.'">DEDUCTIONS</th><th  class="header '.$is_header.'">AMOUNT</th></tr>';
							}
						
							//Pay Parameters Left & Right
							$leftParams = $rightParams =array();
							for($i=0;$i<$rowIterateCount;$i++){//loop
								//dumping allowance values

							if(isset($allowances[$i])){
								$a_label = explode("!",$allowances[$i])[0]; //allowance label i.e Basic
								$a_id = explode("!",$allowances[$i])[1]; //allowance ID i.e c_basic
								
								if($designCustomised [0] ['is_mastersalary']== 2){//allowances for YTD
									$m_id = explode("!",$allowances[$i])[1].'2';
								}else{
									$m_id = explode("!",$allowances[$i])[1].'1';
								}
								
								
							if($row[$a_id] >0 && $row[$a_id]!='')
								if($designCustomised [0] ['is_mastersalary']== 0){ //check whether Master salary be included
									$leftParams[] = '<td class="'.$line_1.'" style="width:25%" >' . $a_label . '</td>
	      	 	 	 	 				<td class="align_text '.$line_1.'" style="width:25%" >' . inr_format ( $row [$a_id] ) . '</td>';
								}else if($designCustomised [0] ['is_mastersalary']== 2){
									if($row [$m_id] !='-'){
										 $leftParams[] = '<td class="'.$line_1.'" style="width:22%" >' . $a_label . '</td><td class="align_text '.$line_1.'" style="width:15%" >' . inr_format ( $row [$a_id] ) . '</td>
	      	 	 	 	 					<td class="align_text '.$line_1.'" style="width:15%" >' .  inr_format ( $row [$m_id] ) . '</td>';
									}else{
									$leftParams[] = '<td class="'.$line_1.'" style="width:22%" >' . $a_label . '</td><td class="align_text '.$line_1.'" style="width:15%" >' . $row [$a_id]. '</td>
	      	 	 	 	 					<td class="align_text  '.$line_1.'" style="width:15%" >' .  inr_format ( $row [$m_id] ) . '</td>';
									}
									
								}else{
									if($row [$m_id] !='-'){
										$leftParams[] = '<td class="'.$line_1.'" style="width:25%" >' . $a_label . '</td><td class="align_text '.$line_1.'" style="width:25%" >' . inr_format ( $row [$m_id] ) . '</td>
	      	 	 	 	 					<td class="align_text  '.$line_1.'" style="width:25%" >' .  inr_format ( $row [$a_id] ) . '</td>';
									}else{
										$leftParams[] = '<td class="'.$line_1.'" style="width:25%" >' . $a_label . '</td><td class="align_text '.$line_1.'" style="width:25%" >' . $row [$m_id]. '</td>
	      	 	 	 	 					<td class="align_text  '.$line_1.'" style="width:25%" >' .  inr_format ( $row [$a_id] ) . '</td>';
									}
								}
							}
							//dumping deduction values
							if(isset($deductions[$i])){
								
								$d_label = explode("!",$deductions[$i])[0];
								$d_id = explode("!",$deductions[$i])[1];
								if($designCustomised [0] ['is_mastersalary']== 2){
									$d_id1 = explode("!",$deductions[$i])[1].'2';
								}
								
							if($row[$d_id] >0 && $row[$d_id]!='' || $d_id == 'c_it' || $d_id == 'c_it1' ) //IT must be printed always
								if($designCustomised [0] ['is_mastersalary']== 2){
								$rightParams[] = '<td class="'.$line_1.'" style="width:20%" >' . $d_label . '</td>
      	 	 	 	 				<td class="align_text '.$line_1.' " style="width:10%" >' . inr_format ( $row [$d_id] ) . '</td>
      	 	 	 	 				<td class="align_text '.$line_1.'" style="width:18%" >' . inr_format ( $row [$d_id1] ) . '</td>';
							}else{
								$rightParams[] = '<td class="'.$line_1.'" style="width:25%" >' . $d_label . '</td>
      	 	 	 	 				<td class="align_text  '.$line_1.'" style="width:25%" >' . inr_format ( $row [$d_id] ) . '</td>';
      	 	 	 	 				
								
							}
							}
							
						}
						
						if(count($leftParams)>count($rightParams))
							$rowCount =count($leftParams);
						else
							$rowCount=count($rightParams);

						$payParams = "";

						for($i=0;$i<$rowCount;$i++){
							if($designCustomised [0] ['is_mastersalary']== 0){
								if(!isset($leftParams[$i]))
									$leftParams[$i] = '<td  style="width:25%" ></td><td class="align_text" style="width:25%" ></td>';
								if(!isset($rightParams[$i]))
									$rightParams[$i] = '<td  style="width:25%" ></td><td class="align_text" style="width:25%" ></td>';
							}else if($designCustomised [0] ['is_mastersalary']== 2){
								if(!isset($leftParams[$i]))
									$leftParams[$i] = '<td  style="width:23%" ></td><td  style="width:15%" ></td><td class="align_text" style="width:15%" ></td>';
									if(!isset($rightParams[$i]))
										$rightParams[$i] = '<td  style="width:20%" ></td><td class="align_text" style="width:10%" ></td><td class="align_text" style="width:18%" ></td>';
								
							}else{
								if(!isset($leftParams[$i]))
									$leftParams[$i] = '<td  style="width:25%" ></td><td  style="width:25%" ></td><td class="align_text" style="width:25%" ></td>';
								if(!isset($rightParams[$i]))
									$rightParams[$i] = '<td  style="width:25%" ></td><td class="align_text" style="width:25%" ></td>';
							}

							$payParams.="<tr>".$leftParams[$i].$rightParams[$i]."</tr>";
						}
						//print_r($payParams); die();
						$netAmount = "";

						if($designCustomised [0] ['is_mastersalary']== 0){
							$gross = '<tr><td  class="line_1 line font_bold alignLeft">
                                 Gross Earnings</td><td class="align_text line_1 line font_bold ">' . inr_format ( $row ['gross_salary'] ) . '
                                 <td class="line font_bold alignLeft">
                                 Gross Deductions</td><td class="align_text line font_bold">' . inr_format ( $row ['total_deduction'] ) . '</td></tr>';
						}else if($designCustomised [0] ['is_mastersalary']== 2){
							$gross = '<tr><td  class="line_1 line font_bold alignLeft">
                                 Gross Earnings</td><td class="align_text line_1 line font_bold ">' .  ( $row ['gross_salary'] ) . '<td class="align_text line_1 line font_bold ">' . inr_format ( $row ['gross_salary2'] ) . '
                                 <td class="line font_bold alignLeft">
                                 Gross Deductions</td><td class="align_text line font_bold">' . inr_format ( $row ['total_deduction'] ) . '</td><td class="align_text line font_bold">' . inr_format ( $row ['total_deduction2'] ) . '</td></tr>';
							
						}else{
							$gross = '<tr><td  class="line_1 line font_bold alignLeft">
                                 Gross Earnings</td><td class="align_text line_1 line font_bold ">' .  ( $row ['gross_salary1'] ) . '<td class="align_text line_1 line font_bold ">' . inr_format ( $row ['gross_salary'] ) . '
                                 <td class="line font_bold alignLeft">
                                 Gross Deductions</td><td class="align_text line font_bold">' . inr_format ( $row ['total_deduction'] ) . '</td></tr>';
						}

						$netAmount = '<tr><td class="line font_bold alignLeft" >Net Amount</td><td  colspan="6"  class="line font_bold">' . inr_format ( $row ['net_salary'] ) . '</td></tr>';

						$html .= $payParams . $gross . $netAmount;
						//echo $html; die();
						$html .= '<tr><td class="line">Amount in words</td><td colspan="6" class="line">' . ucfirst ( Session::newInstance ()->convert_number_to_words ( $row ['net_salary'] ) ) . ' only</td></tr></table>
                                   ';
						$html .= "</tbody> </table>";
					
						$html .= '<br><table style="width:100%;font-size:10.5pt;" id="t03" >
                                  <tr><td colspan="1" ></td><td colspan="8">
                                   <b> Note : </b>'.$designCustomised [0]['note'].'</td><td colspan="1" ></td></tr></table><br><br>';
						
						if($designCustomised [0] ['is_ItSummary']== 1){
							$htmldata = $itDeclaration->downloadTaxSummary($itsummary,$year,true);
							$html .= '<pagebreak /><br><br><br><br><br>'.$htmldata;
						}
						//$companyFooter = '<br><table style="width:100%;"> <tr style="background-color: #1957A2" ><td colspan="5" style="text-align:center;color:#FFF"><p>&copy; Powered by  <a style="color:#FFF" href="http://basspris.com"> BASSPRIS </a> - Pay your wages in a mintue <p></td></tr></table>';
											 
						if($isEmail==1){
							$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
							$mpdf->WriteHTML ( $htmlStyle, 1 ); // Writing style to pdf
							$mpdf->setHTMLHeader ( $header, 1 );
							//$mpdf->setHTMLFooter ( $footer . $companyFooter );
							$mpdf->WriteHTML ( $html, 2 );
							if($designCustomised[0]['protect_password']!=''){
								$protect=isset($row[$designCustomised[0]['protect_password']])?$row[$designCustomised[0]['protect_password']]:$row[0]['protect_password'];
								$mpdf->SetProtection(array('copy','print'),$protect,$protect);
							}
							$content = $mpdf->Output ( '', 'S' );
							$emailID =  ( ($emailResendId!=null) ? $emailResendId : $employee_email);
							if (! filter_var ( $emailID, FILTER_VALIDATE_EMAIL ) === false) {
								$mail=new NotifyEmail();
								$mail->conn = $this->_connection;
								$mailsentResult=$mail->_send($row['employee_name'], $emailID , $month_name.' Payslip', 'Please Find Attachment',array($content),array($month_name.'_'.$empId.'.pdf'));
								$mailsentResult=$row['employee_id'] . "|" . $mailsentResult;
								unset ( $mail );
							} else {
								$mailsentResult = $row ['employee_name'] . "|$empId";
								}
								unset ( $mpdf );
								array_push ( $mailsentDetails, $mailsentResult );
								}else if($isEmail==0){
									$html = $htmlStyle.$html;
									//echo $html; die();
									$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
									$mpdf->setHTMLHeader ( $header );
									//$mpdf->setHTMLFooter ( $footer . $companyFooter );
									$mpdf->WriteHTML ( $html );

									if($designCustomised[0]['protect_password']!=''){
										$protect=isset($row[$designCustomised[0]['protect_password']])?$row[$designCustomised[0]['protect_password']]:$designCustomised[0]['protect_password'];
										$mpdf->SetProtection(array('copy','print'),$protect,$protect);
									}
										$content = $mpdf->Output ( $row ['employee_id'] . "'s ".$month_name." Payslip.pdf", "D" ); // S
										exit ();
									}
								}

							return ($mailsentDetails)?$mailsentDetails:'';
	}

	public function monthlyPayslip($employeeId, $monthYear) {

		$this->generateHtmlForPdf($employeeId,$monthYear,$this->allowDeduString,$this->masterString,0,0,null);
	}

	public function sendEmail($employeeId, $monthYear,$emailResendId, $flagignoreEmpresend) {

		$employeeId = "('" . str_replace ( ",", "','", $employeeId ) . "')";
		$allowDeduStr = "s." . str_replace ( ",", ",s.", $this->allowDeduString);

		$mailsentDetails=$this->generateHtmlForPdf($employeeId,$monthYear,$this->allowDeduString,$this->masterString,1,$flagignoreEmpresend,$emailResendId);

		$emailFlagsuccess = '';
		foreach ( $mailsentDetails as $key ) {
			if (explode ( '|', $key ) [1] == 1) {
				$emailFlagsuccess .= "'" . explode ( '|', $key ) [0] . "',";
			} else {$emailFlagfails='';
			$emailFlagfails .= "<h5> <i class='fa fa-hand-o-right' aria-hidden='true'></i> " . explode ( '|', $key ) [0] . " [ " . explode ( '|', $key ) [1] . " ] </h5>";
			}
		}
		if ($emailFlagsuccess != null)
			mysqli_query ( $this->_connection, "UPDATE payroll SET is_mailSent=1 WHERE employee_id IN ( " . substr ( $emailFlagsuccess, 0, - 1 ) . " ) AND month_year='$monthYear'" );

			if(!$emailFlagfails)
				return array(true,null);
			else
				return array(false,$emailFlagfails);

			$_SESSION ['empFormat'] = '';
			return $result;
	}
	function emailpdf($compnayEmaiId,$content, $employee_email, $empId, $month_name) {
		$content = chunk_split ( base64_encode ( $content ) );
		$mailto = $employee_email;
		$subject = $month_name . " Payslip";
		$from_name = $compnayEmaiId;
		$from_mail = $compnayEmaiId;
		$message = 'Please Find Attachment';
		$filename = $empId . '.pdf';
		$boundary = "xyz";
		$emailHeader = "--$boundary\r\n";
		$emailHeader .= "content-Transfer-Encoding: 8bits\r\n";
		$emailHeader .= "content-Type: text/html; charset=ISO-8859-1\r\n\r\n";
		$emailHeader .= "$message\r\n";
		$emailHeader .= "--$boundary\r\n";
		$emailHeader .= "Content-Type:application/pdf; name=\"" . $filename . "\"\r\n";
		$emailHeader .= "Content-Dispostion: attachment; filename=\"" . $filename . "\"\r\n";
		$emailHeader .= "Content-Transfer-Encoding: base64\r\n\r\n";
		$emailHeader .= "$content\r\n";
		$emailHeader .= "--$boundary--\r\n";
		$emailHeader2 = "MIME-Version: 1.0\r\n";
		$emailHeader2 .= "From: " . $from_name . "\r\n";
		$emailHeader2 .= "Return-Path:$from_mail\r\n";
		$emailHeader2 .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
		$emailHeader2 .= "$boundary\r\n";
		/*$mail = new PHPMailer;
		 $mail->isSMTP();
		 $mail->Host = 'smtp.gmail.com';
		 $mail->SMTPAuth = true;
		 $mail->Username ="sundari@basstechs.com";
		 $mail->Password =  "Bass2015&";
		 $mail->SMTPSecure = 'tls';
		 $mail->From = "sundari@basstechs.com";
		 $mail->FromName =$company_name;
		 $mail->Subject   = $month_name." Payslip";
		 $mail->Body      ="Please Find Attachment";
		 $mail->addAddress("sundari@basstechs.com");
		 $mail->addAddress("lavanyarajasundari@gmail.com");
		 $mail->AddStringAttachment($content, $empId.'.pdf', 'base64', 'application/octet-stream');
		 $mail->Send();*/
		if (@mail ( $mailto, $subject, $emailHeader, $emailHeader2, "-r" . $from_mail )) {
			$result = 1;
		} else {
			$result = 0;
		}
		return $empId . "|" . $result;


	}
	public function downloadPayrollPreview($attr_for, $employee_id) {
		$a_json = array ();
		$employee_id = str_replace ( ",", "\",\"", $employee_id );
		$employee_id = "\"" . $employee_id . "\"";
		$html = '';
		$header = '';
		$company_id = $_SESSION ['company_id'];
		$date = $_SESSION ['current_payroll_month'];
		$monthYear = date ( 'm/Y', strtotime ( $date ));
		$compdetails = array ();
		$stmt ="SELECT c.company_email,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,
                c.company_pin_code FROM company_details c WHERE c.company_id = '" . $_SESSION ['company_id'] . "' AND c.info_flag='A'";
		$result = mysqli_query ( $this->_connection,$stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $compdetails, $row );
		}

		Session::newInstance ()->_setGeneralPayParams ();
		$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
		$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );

		$allAllowNameId = "";
		$allowDeduction = "";
		$allowancesIds = "";
		$deduIds = "";
		$headerArr=array("EMPID","EMPNAME","DOJ","LOP","LLOP");
		foreach ( $allowDeducArray ['A'] as $allow ) {
			$allAllowNameId .= $allow ['alias_name'] . ",";
			$allowancesIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( $allowDeducArray ['D'] as $allow ) {
			$allowDeduction .= $allow ['alias_name'] . ",";
			$deduIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( $miscallowDeducArray ['MD'] as $allow ) {
			$allowDeduction .= $allow ['alias_name'] . ",";
			$deduIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( $miscallowDeducArray ['MP'] as $allow ) {
			$allAllowNameId .= $allow ['alias_name'] . ",";
			$allowancesIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( explode ( ",", substr ( $allAllowNameId, 0, - 1 ) ) as $key => $val ) {
			array_push($headerArr,strtoupper($val));
		}
		array_push($headerArr, "INC-ARREAR");
		array_push($headerArr, "GROSS");
		foreach ( explode ( ",", substr ( $allowDeduction, 0, - 1 ) ) as $key1 => $val1 ) {
			array_push($headerArr,strtoupper($val1));
		}
		array_push($headerArr,"TOTDED");
		array_push($headerArr,"NET");

		$stmt = "SELECT lm.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,DATE_FORMAT(w.employee_doj,'%d/%m/%Y') doj,ROUND(lop+alop,1) lop,ROUND(late_lop,1) llop," . $allowancesIds . "lm.inc_arrear,lm.gross_salary," . $deduIds . "total_deduction,net_salary FROM	 employee_work_details w
		INNER JOIN payroll_preview_temp lm ON w.employee_id=lm.employee_id
		WHERE lm.employee_id IN ({$employee_id})  GROUP by lm.employee_id ORDER BY lm.employee_id";
		$result = mysqli_query ( $this->_connection, $stmt );

		$i = 0;
		$data =array();
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_NUM) ) {
			array_push($data, $row);
		}

		require_once (__DIR__ . "/table.class.php");
		$table= new Table($headerArr,$data);
		$table->print_table($headerArr,$data,$isRemoveIndices=true,$isTotal=true);



		$tableHtml = '<div class="reportTable"> <table style="width:120%">';
			
		foreach(($table->header) as $key=>$head){
			$tableHtml .="<thead repeat_header=1> <tr >";

			foreach($head as $key=>$value){
				$tableHtml .= '<th>' . $value . '</th>';
			}
			$tableHtml .="</tr> </thead>";
		}

		// data rows
		$tableHtml .="<tbody> ";
		foreach(($table->data) as $key=>$value){
			if($monthYear != substr($value[2],3)){
				if ($key % 2 == 0) {
					$tableHtml .= '<tr class="alt border_bottom">';
				}else {
					$tableHtml .= '<tr class="border_bottom">';
				}
			}else {
				$tableHtml .='<tr class="payoutnew">';
			}
				
			foreach($value as $key2=>$value2){
				//if (false === strtotime($value2)) {

				if(is_numeric($value2) && $key2 !=0)
					$tableHtml .= '<td style="text-align:right;">' . inr_format($value2) . '</td>';
					else
						$tableHtml .= '<td>' . $value2. '</td>';

			}
			$tableHtml .= "</tr>";
		}

		foreach(($table->footer) as $key=>$foot){
			$tableHtml .= '<tr class="alt border_bottom style="font-weight:bold"">';
			foreach($foot as $key=>$value){
				if(is_numeric($value))
					$tableHtml .= '<td style="text-align:right;">' . inr_format($value) . '</td>';
					else
						$tableHtml .= '<td>' . $value. '</td>';
			}
			$tableHtml .= '</tr>';
		}
		$tableHtml .="</tbody>";
		// finish table and return it

		$tableHtml .= '</table></div>';
		$html .= '<br><table style="width:100%"><tr><td  style="font-weight: bold;text-align:center;">';
		$html .= strtoupper ( "Provisional Salary Statement for the month of " ) . strtoupper ( date ( 'F Y', strtotime ( $date ) ) ) . '
                     </td> </tr></table>';
		$html .= $tableHtml;

		 

		$footer = '<table style="width:100%;"><tr style="background-color: #1957A2" ><td colspan="5" style="text-align:center;color:#FFF">&copy; Powered by  <a style="color:#FFF" href="http://basspris.com"> BASSPRIS </a> -Online Payroll System</td></tr><tr><td colspan="5" style="text-align:right">Page {PAGENO} | {nb}</td></tr></table>';
		$name = str_replace ( " ", "_", strtoupper ( date ( 'F Y', strtotime ( $date ) ) ) );


		include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
		$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 ); // if remove note isset into 20
		$styleSheet = file_get_contents ( dirname ( dirname ( __DIR__ ) ) . "/css/reportTable.css" );
		$header .= '<table> <tr>
  		<th style="width:20% "><img src=../' . $compdetails [0] ['company_logo'] . ' style="width:15%"></th>
  		<td style="font-weight: bold;font-size:15px;text-align:left; ">' . ucwords ( $compdetails [0] ['company_name'] ) . '<br>' . ucwords ( $compdetails [0] ['company_build_name'] ) . ',' . ucwords ( $compdetails [0] ['company_street'] ) . ',' . ucwords ( $compdetails [0] ['company_area'] ) . ',<br>' . ucwords ( $compdetails [0] ['company_city'] ) . ',' . $compdetails [0] ['company_pin_code'] . '</td></tr></table>';
		$mpdf->WriteHTML ( $styleSheet, 1 );
		$mpdf->setAutoTopMargin='stretch';// Writing style to pdf
		$mpdf->setHeader ( $header );
		//$mpdf->setHTMLFooter ( $footer );
		$mpdf->WriteHTML ( $html, 2 );
		$mpdf->Output ( 'provisional_Statement_' . $name . '.pdf', D );
		exit ();
	}
	public function payoutStatement($monthYear,$employeeId) {
		$employeeId = "('" . str_replace ( ",", "','", $employeeId ) . "')";
		$a_json = array ();
		$retireAllow = "";

		Session::newInstance ()->_setRetirementParams ();
		$retirementArray = Session::newInstance ()->_get ( "retirementParams" );
		if(count($retirementArray["retirementAllowString"])>0)
			$retireAllow .= str_replace ( ',', '+', substr ( $retirementArray["retirementAllowString"], 0, - 1 ) ) ;
		if(count($retirementArray["RA"])>0)
			$retireAllow = "+IFNULL(".$retireAllow.",0)";

			$compdetails = array ();
			$stmt ="SELECT c.company_email,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,
            	  c.company_pin_code FROM company_details c WHERE c.company_id = '" . $_SESSION ['company_id'] . "' AND c.info_flag='A'";
			$result = mysqli_query ( $this->_connection,$stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $compdetails, $row );
		}
		$htmlStyle = '<style>
#borderSet {
   border-collapse: collapse;
		border: 1px solid #CCC;
				padding: 5px;
	}

th{
color:#fff;
			width:2px;
}
.center_div{
	margin-top: 0 auto;

}

body { font-family: Open Sans, sans-serif; font-size: 10pt; }
		</style>';
		// style Purpose
		include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
		$stmt = "SELECT compAccNO,comBankName,compBranch,comIfsc,employee_id,employee_name,EmpBankName,EmpAccNO,EmpIfsc,net,account_type
				FROM(
					SELECT c.bank_ac_no compAccNO,c.bank_name comBankName,c.bank_branch compBranch,
					c.bank_ifsc comIfsc,e.employee_id,w.employee_name,
					e.employee_bank_name EmpBankName,e.employee_acc_no EmpAccNO,e.employee_bank_ifsc EmpIfsc ,p.net_salary$retireAllow net,c.account_type
					FROM employee_personal_details e
					INNER JOIN employee_work_details w ON e.employee_id = w.employee_id
					LEFT JOIN payroll p	ON w.employee_id = p.employee_id
					LEFT JOIN company_payment_modes c ON w.payment_mode_id = c.payment_mode_id
					LEFT JOIN settlements s ON p.employee_id = s.employee_id AND p.month_year = s.month_year
					WHERE  p.month_year = '$monthYear' AND p.employee_id IN $employeeId AND p.is_pay_pending != 1
					UNION ALL
					SELECT c.bank_ac_no compAccNO,c.bank_name comBankName,c.bank_branch compBranch, c.bank_ifsc comIfsc,e.employee_id,
					w.employee_name, e.employee_bank_name EmpBankName,e.employee_acc_no EmpAccNO,e.employee_bank_ifsc EmpIfsc ,SUM(p.net_salary)$retireAllow net,c.account_type
					FROM employee_personal_details e
					INNER JOIN employee_work_details w ON e.employee_id = w.employee_id
					INNER JOIN payroll p ON w.employee_id = p.employee_id
					LEFT JOIN company_payment_modes c ON w.payment_mode_id = c.payment_mode_id
					INNER JOIN emp_notice_period n ON p.employee_id = n.employee_id
					LEFT JOIN settlements s ON p.employee_id = s.employee_id
					WHERE p.is_pay_pending =1 AND  DATE_FORMAT(n.last_working_date,'%m%Y') = DATE_FORMAT('$monthYear','%m%Y') AND p.employee_id IN $employeeId
					ORDER BY comBankName,employee_id ASC)z WHERE employee_id IS NOT NULL;";
				 
		$result = mysqli_query ( $this->_connection, $stmt ) or die(mysqli_error($this->_connection));
		$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
		$flag = 0;
		$count = 0;
		$payment_mode_id = "";
		$html = "";
		$total = 0;
		$i = 1;
		$row_cnt = mysqli_num_rows ( $result );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			if ($payment_mode_id == "") {
				$total = 0;
				$html.='<br><h3 style="font-size:15px;font-weight:bold;text-align:center">Bank Payout Statement for the ' . date ( 'F Y', strtotime ( $monthYear ) ) . ' </h3>';
				if ($row ['account_type'] == 'Cash') {
					$html.='Mode Of Payment: '.$row['account_type'];
				} else {
					$html .= '<br><table  style="border-collapse: collapse;width:100%">
						<tr><td>Mode Of Payment:' . $row ['account_type'] . ' <br> Bank Name : ' . $row ['comBankName'] . '<br>
						Account No:  ' . $row['compAccNO']. '<br> Branch Name: ' .$row['compBranch'].' <br>IFSC Code: '.$row['comIfsc'].'<br></td></tr>
						</table>';
						}
					$html .= '<br><br><table class="center_div" style="border-collapse: collapse;width:100%;">
					<thead repeat_header="1" ><tr style="padding: 5px;text-align:left;background-color: #39b2a9;">
					<th>SLNo</th><th>EmpID</th><th>Emp Name</th><th>Bank Name</th><th>AccountNo</th><th>IFSC</th><th>Net Salary</th></tr></thead>';

			} elseif ($payment_mode_id != $row ['comBankName'] && $payment_mode_id != ""){
				$count = 1;
				$html .= '<tr><td id="borderSet" style="padding: 5px" colspan="6">Total</td><td id="borderSet" style="padding: 5px">' . inr_format ( ( float ) $total ) . '</td></tr>';
				$html .= '</table>';
				$html .= '<pagebreak />';
				$html.='<br><h3 style="font-size:15px;font-weight:bold;text-align:center">Bank Payout Statement for the ' . date ( 'F Y', strtotime ( $monthYear ) ) . ' </h3>';
				if ($row ['account_type'] == 'Cash') {
					$html.='Mode Of Payment: '.$row['account_type'];
				} else {
					$html .= '<br><table  style="border-collapse: collapse;width:100%">
						<tr><td>Mode Of Payment:' . $row ['account_type'] . ' <br> Bank Name : ' . $row ['comBankName'] . '<br>
						Account No:  ' . $row['compAccNO']. '<br> Branch Name: ' .$row['compBranch'].' <br>IFSC Code: '.$row['comIfsc'].'<br></td></tr>
						</table>';
				}
					$html .= '<br><table class="center_div" style="border-collapse: collapse;width:100%">
					<thead repeat_header="1" ><tr style="text-align:left;background-color: #39b2a9;">
					<th>SL No</th><th>EmpID</th><th>Emp Name</th><th>Bank Name</th><th>AccountNo</th><th>IFSC</th><th>Net Salary</th></tr></thead>';
						$count=0;
						$total=0;
					}
					$count++;
					$html .= '<tr><td id="borderSet">' . $count . '</td>
				<td id="borderSet">' .$row ['employee_id'] . '</td>
				<td id="borderSet">' .$row ['employee_name'] . '</td>
				<td id="borderSet">' . str_replace("_"," ",$row ['EmpBankName']) . '</td>
				<td id="borderSet">' . $row ['EmpAccNO'] . '</td>
				<td id="borderSet">' . $row ['EmpIfsc'] . '</td>
				<td id="borderSet" style="padding: 5px;text-align:left">' . inr_format($row ['net']) . '</td></tr>';
				$total = $total + $row ['net'];
				if ($row_cnt == $i) {
					$html .= '<tr><td id="borderSet" style="padding: 10px;text-align:left;" colspan="6">Total</td><td id="borderSet" style="padding: 10px">' . inr_format ( ( float ) $total) . '</td></tr>';
				}
				$payment_mode_id = $row ['comBankName'];
				$i ++;
		}
		$html .= '</table>';
		$html .= $htmlStyle;
				 
				//include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
		$header .= '<table  style="border-collapse: collapse;"> <tr>
  		<td style="width:20% "><img src=../' . $compdetails [0] ['company_logo'] . ' style="width:15%"></td>
  		<td style="font-weight: bold;font-size:15px;text-align:left; ">' . ucwords ( $compdetails [0] ['company_name'] ) . '<br>' . ucwords ( $compdetails [0] ['company_build_name'] ) . ',' . ucwords ( $compdetails [0] ['company_street'] ) . ',' . ucwords ( $compdetails [0] ['company_area'] ) . ',<br>' . ucwords ( $compdetails [0] ['company_city'] ) . ',' . $compdetails [0] ['company_pin_code'] . '</td></tr></table>';
		$mpdf->setAutoTopMargin='stretch';
		$mpdf->SetHeader($header);
		$mpdf->WriteHTML ( $html);
		$mpdf->Output ( 'Bank Payout Stmt For ' . date ( 'F Y', strtotime ( $monthYear ) ) . '.pdf', D );
		exit ();
	}

	function getWorkingDays($startDate,$endDate,$holidays){
		// do strtotime calculations just once
		$endDate = strtotime($endDate);
		$startDate = strtotime($startDate);


		//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
		//We add one to inlude both dates in the interval.
		$days = ($endDate - $startDate) / 86400 + 1;

		$no_full_weeks = floor($days / 7);
		$no_remaining_days = fmod($days, 7);

		//It will return 1 if it's Monday,.. ,7 for Sunday
		$the_first_day_of_week = date("N", $startDate);
		$the_last_day_of_week = date("N", $endDate);

		//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
		//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($the_first_day_of_week <= $the_last_day_of_week) {
			if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
			if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
		}
		else {
			// (edit by Tokes to fix an edge case where the start day was a Sunday
			// and the end day was NOT a Saturday)

			// the day of the week for start is later than the day of the week for end
			if ($the_first_day_of_week == 7) {
				// if the start date is a Sunday, then we definitely subtract 1 day
				$no_remaining_days--;

				if ($the_last_day_of_week == 6) {
					// if the end date is a Saturday, then we subtract another day
					$no_remaining_days--;
				}
			}
			else {
				// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
				// so we skip an entire weekend and subtract 2 days
				$no_remaining_days -= 2;
			}
		}

		//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
		//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
		$workingDays = $no_full_weeks * 5;
		if ($no_remaining_days > 0 )
		{
			$workingDays += $no_remaining_days;
		}

		//We subtract the holidays
		foreach($holidays as $holiday){
			$time_stamp=strtotime($holiday);
			//If the holiday doesn't fall in weekend
			if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
				$workingDays--;
		}

		return $workingDays;
	}
	public function downloadPreviewExcel($attr_for, $employee_id) {
		$a_json = array ();
		$employee_id = str_replace ( ",", "\",\"", $employee_id );
		$employee_id = "\"" . $employee_id . "\"";
		$html = '';
		$header = '';
		$company_id = $_SESSION ['company_id'];
		$date = $_SESSION ['current_payroll_month'];
		$monthYear = date ( 'm/Y', strtotime ( $date ));
		$compdetails = array ();
		$stmt ="SELECT c.company_email,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,
                c.company_pin_code FROM company_details c WHERE c.company_id = '" . $_SESSION ['company_id'] . "' AND c.info_flag='A'";
		$result = mysqli_query ( $this->_connection,$stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $compdetails, $row );
		}
	
		Session::newInstance ()->_setGeneralPayParams ();
		$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
		$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );

		$allAllowNameId = "";
		$allowDeduction = "";
		$allowancesIds = "";
		$deduIds = "";
		$tableHeaders = $tableData = array();
		$headerArr=array("EMPID","EMPNAME","DOJ","CALENDAR DAYS","WORKED DAYS","LOP","LLOP","ACTUAL GROSS");
		foreach ( $allowDeducArray ['A'] as $allow ) {
			$allAllowNameId .= $allow ['display_name'] . ",";
			$allowancesIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( $allowDeducArray ['D'] as $allow ) {
			$allowDeduction .= $allow ['display_name'] . ",";
			$deduIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( $miscallowDeducArray ['MD'] as $allow ) {
			$allowDeduction .= $allow ['display_name'] . ",";
			$deduIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( $miscallowDeducArray ['MP'] as $allow ) {
			$allAllowNameId .= $allow ['display_names'] . ",";
			$allowancesIds .= "lm." . $allow ['pay_structure_id'] . ",";
		}

		foreach ( explode ( ",", substr ( $allAllowNameId, 0, - 1 ) ) as $key => $val ) {
			array_push($headerArr,strtoupper($val));
		}
		array_push($headerArr, "INC-ARREAR");
		array_push($headerArr, "GROSS");
		foreach ( explode ( ",", substr ( $allowDeduction, 0, - 1 ) ) as $key1 => $val1 ) {
			array_push($headerArr,strtoupper($val1));
		}
		array_push($headerArr,"TOTDED");
		array_push($headerArr,"NET");
		$tableHeaders = $headerArr;
		
		$stmt = "SELECT lm.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,DATE_FORMAT(w.employee_doj,'%d/%m/%Y') doj,IFNULL(cal_days,''),worked_days,ROUND(lop+alop,1) lop,ROUND(late_lop,1) llop,s.employee_salary_amount actual_grosss," . $allowancesIds . "lm.inc_arrear,lm.gross_salary," . $deduIds . "total_deduction,net_salary 
				FROM employee_work_details w
				INNER JOIN payroll_preview_temp lm ON w.employee_id=lm.employee_id
				INNER JOIN employee_salary_details s ON w.employee_id=s.employee_id
				WHERE lm.employee_id IN ({$employee_id})  GROUP by lm.employee_id ORDER BY lm.employee_id";
		$result = mysqli_query ( $this->_connection, $stmt );

		$i = 0;
		$data =array();
		array_push($data, $tableHeaders);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_NUM) ) {
			array_push($data, $row);
		}
		$tableData = $data;
		array_push($tableHeaders,$tableData); 
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$tableData:mysqli_error ( $this->_connection ))
		);
	}
}
?>