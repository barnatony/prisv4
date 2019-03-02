<?php
/*
 * ----------------------------------------------------------
 * Filename : Itdeclaration.class.php
 * Classname: Itcalculation
 * Author : Rufus Jackson
 * Database : employee_income_tax
 * Oper : Find TDS
 *
 * -----------------------------------------------------------
 */

require_once (__DIR__ . "/database.class.php");

class ItCalculation {
	/* Member variables */
	var $fin_year;
	var $financial_year_start;
	var $financial_year_end;
	var $current_year;
	var $remaining_months;
	var $payroll_month_year;
	var $updated_by;
	var $_connection;
	
	/* Common for All Employees */
	var $current_month;
	private $it_slabs = array (
			"G" => array (),
			"A" => array (),
			"S" => array () 
	);
	private $epf_properties;
	private $mappedTaxExemptions;
	private $exemptionText;
	private $epf_deductable_amount = 0; // deductable amount from salaryy details
	private $epf_limit;
	private $hraMetroCities = array (
			"Chennai",
			"CHENNAI",
			"Delhi",
			"DELHI",
			"Mumbai",
			"MUMBAI",
			"Kolkatta",
			"KOLKATTA" 
	);
	private $limit80c = 150000;
	private $isMealCard = 0;
	private $isMappedExem = 0;
	private $employee_remaining_months;
	private $employee_id;
	private $employee_age;
	private $employee_father_age;
	private $employee_city;
	private $current_month_gross = 0;
	private $master_gross_salary = 0;
	private $yearly_epf = 0;
	private $yearly_gross = 0;
	private $yearly_pt = 0;
	private $yearly_basic = 0;
	private $yearly_hra = 0;
	private $prev_earnings;
	protected $tax_paid = 0;
	protected $gross_total_income = 0;
	protected $other_income = 0;
	protected $total_inc = 0;
	protected $house_prop_inc = 0;
	private $salaried_income = 0; // check this to prevent negative taxation <250000
	private $yearly_epfEmplr=0;
	
	/* 10 A Exemptions */
	protected $exe_medical = 0;
	protected $exe_travel_con = 0;
	protected $exe_lta = 0;
	protected $exe_other_10a = 0;
	protected $exe_80c = 0;
	protected $exe_80d = 0;
	protected $exe_80g = 0;
	protected $exe_80e = 0;
	protected $exe_deductions_other = 0; // other deductions - 80TTA+80U+80U - interst on saving acc + personal disability + personal severe disability
	protected $exe_hra = 0;
	protected $relief = 0;
	protected $rebate=0;
	protected $taxable_inc = 0;
	protected $tax = 0;
	protected $ec = 0;
	protected $shec = 0;
	protected $surcharge = 0;
	protected $tax_payable = 0;
	protected $tds = 0;
	
	/*IT Properties*/
	private $exe80ccd_nps_exemption_limit;
	private $total_80c_exemptions_limit;
	private $rgess_gross_condition; //rgress applicable for gross greater than
	private $medi_ins_senior_limit; //
	private $medi_ins_limit;
	private $medi_trmt_ordinary_limit;
	private $medi_trmt_severe_limit;
	private $medi_trmt_spcified_disease_limit;
	private $medi_trmt_spcified_disease_senior_limit;
	private $interest_savings_limit;
	private $personal_disability_limit;
	private $personal_severe_disability_limit;
	private $surcharge_percentage1; //percentage
	private $surcharge_percentage1_applicable_to; 
	private $surcharge_percentage2;
	private $surcharge_percentage2_applicable_to;
	private $medi_reimbursement_limit;
	private $self_occ_property_intrest_paid_limit;
	private $tax_applicable_income_gt; //greater than
	private $rebate_amount;
	private $rebate_limit;
	
	/* Member functions */
	function __construct($employees, $fin_year, $payroll_month_year, $updated_by, $isNoUpdate, $conn, $againRunFlag) {
		$this->_connection = $conn;
		ini_set ( 'memory_limit', MEMORY_LIMIT );
		ini_set('max_execution_time', MAX_EXECUTION_TIME);
		date_default_timezone_set(DEFAULT_TIMEZONE);
		$this->financial_year_start = substr ( $fin_year, 0, 4 ) . "-04-01";
		$this->financial_year_end = (substr ( $fin_year, 0, 4 ) + 1) . "-03-31";
		$month_start = substr ( $payroll_month_year, 2, 4 ) . "-" . substr ( $payroll_month_year, 0, 2 ) . "-01";
		$this->remaining_months = self::monthsFromDate ( $month_start, $this->financial_year_end, false );
		$this->prev_month_end = substr ( $payroll_month_year, 2, 4 ) . "-" . (substr ( $payroll_month_year, 0, 2 )) . "-31";
		$this->prev_month_end = date("Y-m-d",strtotime("{$this->prev_month_end} -1 months"));
		$this->current_month = substr ( $payroll_month_year, 0, 2 );
		$this->fin_year = $fin_year;
		$this->current_year = substr ( $payroll_month_year, 2, 4 );
		$this->payroll_month_year = $payroll_month_year;
		$this->updated_by = $updated_by;
		$this->loadItSlabs ( $fin_year );
		$this->loadEpfProperties ();
		$this->loadMappedExemptions ();
		$this->checkIfMealCard ();
		$empCount = 0;
		
					
		//print_r ($itProperties);
		foreach ( $employees as $employee ) {
			
			if ($employee ['status_flag'] != 'P' || $againRunFlag == 1) {
				$empCount ++;
				$stmt = "SELECT DATE_FORMAT(np.last_working_date,'%d') last_date,DATE_FORMAT(np.last_working_date,'%m') last_working_month,np.last_working_date,p.father_dob,p.employee_dob,w.employee_doj,it.*,tax.old_tax_paid
					FROM employee_personal_details p
					INNER JOIN  employee_work_details w
					ON p.employee_id=w.employee_id
					INNER JOIN  employee_it_declaration it
					ON p.employee_id=it.employee_id AND it.year='" . $this->fin_year . "'
					INNER JOIN  employee_income_tax tax
					ON it.employee_id=tax.employee_id AND tax.year='" . $this->fin_year . "'
					LEFT JOIN emp_notice_period np
					ON w.employee_id=np.employee_id  AND np.status='A'
					WHERE w.employee_id ='" . $employee ['employee_id'] . "'";
				$result = mysqli_query ( $this->_connection, $stmt );
				if ($result) {
					$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
					$itRow = $row;
					mysqli_free_result ( $result );
					$tds = self::calculateEmployeeIt ( $employee ['employee_id'], $employee ['gross_salary'], $itRow ); // GET TDS VALUE
					if ($isNoUpdate == 1) {
						$this->updateITtable ();
						if ($tds != null) {
							$TotalDeductionValue = $tds + $employee ['total_deduction'];
							$netSalary = $employee ['gross_salary'] - ($tds + $employee ['total_deduction']);
							$stmt = mysqli_prepare ( $this->_connection, "UPDATE payroll_preview_temp pp SET pp.c_it=? ,pp.total_deduction=? , pp.net_salary=?, updated_by=?,status_flag='P' WHERE employee_id=? " );
							mysqli_stmt_bind_param ( $stmt, 'sssss', $tds, $TotalDeductionValue, $netSalary, $this->updated_by, $employee ['employee_id'] );
							$result = mysqli_stmt_execute ( $stmt );
						} else {
							$TotalDeductionValue = $employee ['total_deduction'];
							$netSalary = $employee ['gross_salary'] - ($employee ['total_deduction']);
							$stmt = mysqli_prepare ( $this->_connection, "UPDATE payroll_preview_temp pp SET pp.c_it=0,pp.total_deduction=? , pp.net_salary=?, updated_by=?,status_flag='P' WHERE employee_id=? " );
							mysqli_stmt_bind_param ( $stmt, 'ssss', $TotalDeductionValue, $netSalary, $this->updated_by, $employee ['employee_id'] );
							$result = mysqli_stmt_execute ( $stmt );
						}
					} else {
						return $tds;
					}
				} else {
					die ( mysqli_error ( $this->_connection ) );
				}
			} else {
				// load and select value from the table
			}
		}
	}
	public function calculateEmployeeIt($employee_id, $currentGross, $itRow) {
		$this->current_month_gross = $currentGross;
		$this->employee_id = $employee_id;
		$this->loadYearlyValues ( $employee_id, $this->financial_year_start, $this->prev_month_end, $itRow );
		$this->salariedIncome ( $itRow, $employee_id );
		if ($this->grossTotalIncome ( $itRow, $employee_id ) > 250000) {
			$this->taxPayable ( $itRow );
		} else {
			
			// no tax for people
			$this->other_income = 0;
			$this->exe_lta = 0;
			$this->yearly_epfEmplr = 0;
			$this->total_inc = 0;
			$this->house_prop_inc = 0;
			$this->exe_80e = 0;
			$this->exe_80c = 0;
			//$this->gross_total_income = 0;
			$this->exe_80d = 0;
			$this->exe_80g = 0;
			$this->exe_deductions_other = 0;
			$this->taxable_inc = 0;
			$this->relief = 0;
			$this->rebate = 0;
			$this->tax = 0;
			$this->ec = 0;
			$this->shec = 0;
			$this->salaried_income = 0;
			$this->surcharge = 0;
			$this->tax_payable = 0;
			$this->tds = 0;
			$this->tax_paid = 0;
			$this->tds = 0;
		}
		return $this->tds;
	}
	private function loadItSlabs($fin_year) {  
		$stmt = "SELECT  slab.from_value, slab.to_value, slab.rate,slab.age_group FROM it_slab slab WHERE slab.fin_year={$fin_year}";
		$result = mysqli_query ( $this->_connection, $stmt ); // Procedure Gives who ever employees affected
		$i = 0;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			if ($row ['age_group'] == "G") {
				$this->it_slabs ["G"] [] = $row;
			} elseif ($row ['age_group'] == "A") {
				$this->it_slabs ["A"] [] = $row;
			} elseif ($row ['age_group'] == "S") {
				$this->it_slabs ["S"] [] = $row;
			}
		}
		mysqli_free_result ( $result );
		
		$stmt = "SELECT it_calculation_variable,value FROM it_properties WHERE fin_year = $fin_year;";
		$result = mysqli_query($this->_connection, $stmt) or die(mysqli_error($this->_connection));
		while ($row = mysqli_fetch_array ( $result, MYSQLI_ASSOC )){
				$variable = $row["it_calculation_variable"];
				$value = $row["value"];
				$this->$variable = $value;
			}
		mysqli_free_result ( $result );
		
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
	}
	private function loadEpfProperties() {
		$stmt = "SELECT cd.max_employee_share,cd.employer_share,cd.max_employer_share,cd.is_both_contribution,  
				 cd.employee_share,cd.is_admin_charges,cd.admin_charges ,cd.deduce_in
				 FROM company_deductions cd INNER JOIN  company_pay_structure ps
				 ON cd.deduction_id=ps.pay_structure_id
				 AND ps.display_flag=1 AND cd.deduction_id='c_epf'";
		$result = mysqli_query ( $this->_connection, $stmt ); // Procedure Gives who ever employees affected
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		$this->epf_properties = $row;
		mysqli_free_result ( $result );
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
	}
	private function loadMappedExemptions() {
		$id = "";
		$presentText = "";
		$arrersText = "";
		$prjecteText = "";
		$pastText ="";
		$stmt = "SELECT  pay_structure_id FROM company_pay_structure WHERE exemption_id!=''";
		$result = mysqli_query ( $this->_connection, $stmt ); // Procedure Gives who ever employees affected
		$row_count = mysqli_num_rows ( $result );
		$this->isMappedExem = ($row_count > 0) ? 1 : 0;
		if ($this->isMappedExem == 1) {
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				$id .= "e_" . $row ["pay_structure_id"] . "+";
				$pastText .= "SUM(CASE WHEN ps.pay_structure_id = '" . $row ["pay_structure_id"] . "'  
		THEN IF( pe.exempt_type ='A',LEAST(pe.exemption_value,py." . $row ["pay_structure_id"] . " ),
		(pe.exemption_value*py." . $row ["pay_structure_id"] . ")/100) END) e_" . $row ["pay_structure_id"] . ",";
				$presentText .= "SUM(CASE WHEN ps.pay_structure_id = '" . $row ["pay_structure_id"] . "' 
		THEN IF( pe.exempt_type ='A',LEAST(pe.exemption_value,pt." . $row ["pay_structure_id"] . "),
		(pe.exemption_value*pt." . $row ["pay_structure_id"] . ")/100) END) e_" . $row ["pay_structure_id"] . ",";
				$prjecteText .= "SUM(CASE WHEN ps.pay_structure_id = '" . $row ["pay_structure_id"] . "'
		THEN IF( pe.exempt_type ='A',LEAST(pe.exemption_value,sd." . $row ["pay_structure_id"] . ")* " . '$remainingMonths ' . " ,
		((pe.exemption_value*sd." . $row ["pay_structure_id"] . ")/100)* " . '$remainingMonths ' . " ) END) e_" . $row ["pay_structure_id"] . ",";
				$arrersText .= "SUM(CASE WHEN ps.pay_structure_id = '" . $row ["pay_structure_id"] . "'  
		THEN IF( pe.exempt_type ='A',LEAST(pe.exemption_value,a." . $row ["pay_structure_id"] . "),
		(pe.exemption_value*a." . $row ["pay_structure_id"] . ")/100) END) e_" . $row ["pay_structure_id"] . ",";
			}
			$query = "SELECT LEAST(SUM(" . substr ( $id, 0, - 1 ) . "),max_exempt_limit) mappedTaxExemptions FROM(
				SELECT 
				$pastText py.employee_id,pe.max_exempt_limit
				FROM company_pay_structure ps
				INNER JOIN company_tax_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN payroll py
				ON py.month_year BETWEEN ? AND ?
				WHERE py.employee_id = ?
				UNION ALL
				SELECT 
				$presentText pt.employee_id,pe.max_exempt_limit
				FROM company_pay_structure ps
				INNER JOIN company_tax_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN payroll_preview_temp pt
				ON pt.employee_id =?
				UNION ALL
				SELECT 
				$prjecteText sd.employee_id,pe.max_exempt_limit
				FROM company_pay_structure ps
				INNER JOIN company_tax_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN employee_salary_details sd
				ON sd.employee_id =?
				UNION ALL
				SELECT 
				$arrersText a.employee_id,pe.max_exempt_limit
				FROM company_pay_structure ps
				INNER JOIN company_tax_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN arrears a
				ON a.month_year BETWEEN ? AND ?
				WHERE a.employee_id= ? )q;";
			$this->exemptionText = $query;
		}
	}
	private function checkIfMealCard() {
		// check meal card deduction enabled or not if enabled set isMealCard = 1
		$query = "SELECT ps.display_name FROM company_pay_structure ps 
				WHERE ps.pay_structure_id='m_mc' AND ps.display_flag=1";
		$result = mysqli_query ( $this->_connection, $query );
		$row_count = mysqli_num_rows ( $result );
		$this->isMealCard = ($row_count == 1) ? 1 : 0;
		return $this->isMealCard;
	}
	public function loadYearlyValues($employee_id, $fin_year_start, $prev_month_end, $itRow) {
		$result = mysqli_query ( $this->_connection, "SELECT salary_days,attendance_period_sdate attendance_dt
		FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'" );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->_connection));
		$last_working_month = $itRow ['last_working_month'];
		
		if($itRow ['last_date'] >= $row['attendance_dt'] && $row['attendance_dt'] !=1 && $itRow ['last_working_month']!='') {
			$last_working_month = $last_working_month+1;
		}                
		
		if ($last_working_month == $this->current_month) {
			$remaining_months = 0;
		} else {
			$remaining_months = $this->remaining_months;
		}
                $query = "SELECT t.employee_id,t.employee_city,SUM(t.basic) basic ,SUM(t.hra) hra,SUM(t.gross) gross ,it FROM (
		SELECT p.employee_id,p.employee_city,IFNULL(SUM(py.basic),0) basic,
		IFNULL(SUM(py.c_hra),0) hra,
		IFNULL(SUM(py.gross_salary),0) gross,
		IFNULL(SUM(py.c_it),0) it,
		'Past' duration FROM employee_personal_details p
    LEFT JOIN  payroll py 
		ON p.employee_id=py.employee_id 
    AND py.month_year BETWEEN ? AND ?
		WHERE p.employee_id =?
		UNION ALL
		SELECT pp.employee_id,NULL,SUM(pp.basic),SUM(pp.c_hra),SUM(pp.gross_salary) gross ,0,'Current' FROM
        payroll_preview_temp pp
		WHERE pp.employee_id =?
		UNION ALL
	    SELECT a.employee_id,NULL,IFNULL(SUM(a.basic),0),IFNULL(SUM(a.c_hra),0),0,0,'arrears' FROM arrears a
	    WHERE  a.month_year BETWEEN ? AND ? AND a.employee_id =?
        UNION ALL
		SELECT s.employee_id,NULL,s.basic*{$remaining_months},s.c_hra*{$remaining_months},SUM(s.employee_salary_amount)*{$remaining_months} ,0,'Projected'
		FROM employee_salary_details s WHERE s.employee_id = ?) t";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $fin_year_start, $prev_month_end, $employee_id, $employee_id, $fin_year_start, $prev_month_end, $employee_id, $employee_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $employee_id, $employee_city, $basic, $hra, $gross, $it );
		mysqli_stmt_fetch ( $stmt );
		$this->employee_city = $employee_city;
		$this->yearly_basic = $basic;
		$this->yearly_hra = $hra;
		$this->yearly_gross = $gross;
		$this->tax_paid = $it;
		mysqli_stmt_free_result ( $stmt );
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		
		if (is_array ( $this->epf_properties )) {
			$summ = str_replace ( ",", "+", $this->epf_properties ['deduce_in'] ); // sum all the deduce in columns in salary details
			$query = "SELECT ($summ) as deductableAmt,pf_limit,employee_salary_amount FROM employee_salary_details WHERE employee_id = '{$employee_id}'";
			if ($result = mysqli_query ( $this->_connection, $query )) {
				$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
				$this->epf_deductable_amount = $row ['deductableAmt'];
				$this->epf_limit = $row ['pf_limit'];
				$this->master_gross_salary = $row ['employee_salary_amount'];
				mysqli_free_result ( $result );
			} else {
				die ( mysqli_error ( $this->_connection ) );
			}
			$this->yearly_epf = $this->loadYearlyEpf ();
		} else {
			$query = "SELECT employee_salary_amount FROM employee_salary_details WHERE employee_id = '{$employee_id}'";
			if ($result = mysqli_query ( $this->_connection, $query )) {
				$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
				$this->master_gross_salary = $row ['employee_salary_amount'];
				mysqli_free_result ( $result );
			} else {
				die ( mysqli_error ( $this->_connection ) );
			}
		}
		
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		if ($remaining_months == 0) {
			$this->epf_deductable_amount = 0;
		}
		
		$query = "CALL CALC_PT('$employee_id','$this->payroll_month_year','$this->fin_year','$this->current_month_gross','$this->master_gross_salary','IT','$remaining_months',@ptValue)";
		if (mysqli_multi_query ( $this->_connection, $query )) {
			if ($result1 = mysqli_use_result ( $this->_connection )) {
				while ( $row = mysqli_fetch_array ( $result1, MYSQLI_ASSOC ) ) {
					$this->yearly_pt = (isset ( $row ['pt_rate'] ) ? $row ['pt_rate'] : 0)+$itRow['prev_pt_app'];
					// array_push($json, $row['']);
				}
				
				do {
					if ($result = mysqli_store_result ( $this->_connection )) {
						mysqli_free_result ( $result );
					}
				} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
			}
		}
		
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		return array (
				"city" => $employee_city,
				"yearlyBasic" => $basic,
				"yearlyHra" => $hra,
				"yearlyGross" => $gross,
				"taxPaid" => $it 
		);
	}
	private function loadYearlyEpf() {
		mysqli_stmt_init ( $this->_connection );
		$max_employee_share = $this->epf_properties ['max_employee_share'];
		$max_employer_share = $this->epf_properties ['max_employer_share'];
		$is_both_contribution = $this->epf_properties ['is_both_contribution'];
		$employee_share = $this->epf_properties ['employee_share'];
		$employer_share = $this->epf_properties ['employer_share'];
		$epf_limit = $this->epf_limit;
		$is_admin_charges = $this->epf_properties ['is_admin_charges'];
		$admin_charges = $this->epf_properties ['admin_charges'];
		$query = "CALL CALC_EPF('$this->epf_deductable_amount','$employee_share','$max_employee_share',
		                     '$is_both_contribution','$employer_share','$max_employer_share','$is_admin_charges','$admin_charges',
		                     '$epf_limit','$this->employee_id','$this->payroll_month_year',
		                     '$this->fin_year','$this->remaining_months','IT',@epfValue);";
		if (mysqli_multi_query ( $this->_connection, $query )) {
			if ($resultRow = mysqli_use_result ( $this->_connection )) {
				$row = mysqli_fetch_array ( $resultRow, MYSQLI_ASSOC );
				$this->yearly_epf = $row ['employee_value'];
				$this->yearly_epfEmplr = $row ['employer_value'];
				// $this->yearly_epf =$row['epfValue']; corection
				do {
					if ($result = mysqli_store_result ( $this->_connection )) {
						mysqli_free_result ( $result );
					}
				} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
			}
		}
		return $this->yearly_epf;
	}
	private function otherIncome($itRow) {
		$other_income = $itRow ['int_inc_app'] + $itRow ['lottery_app'] + $itRow ['letting_app'] + $itRow ['land_app'] + $itRow ['other_app'];
		$this->other_income = $other_income;
		return $other_income;
	}
	private function housePropertyIncome($itRow) {
		$inter_app = min ( $itRow ['inter_app'], 200000 ); // self occupied
		$annRent_app = $itRow ['annRent_app'] - ($itRow ['munic_app'] + $itRow ['unreal_app']); // let out prop
		$annRent = ($annRent_app * (70 / 100)) - $itRow ['housing_app'];
		$house_loan = $annRent + (- $inter_app);
		$this->house_prop_inc = $house_loan;
		return $house_loan;
	}
	private function salariedIncome($row, $employee_id) {
		$this->salaried_income = $this->yearly_gross - $this->exe_hra ( $row ) - $this->exe_10a ( $row, $employee_id ) - $this->yearly_pt;
		return $this->salaried_income;
	}
	private function other_mapped_exe_10a($itRow, $employee_id) {
		if ($this->isMappedExem == 1) {
			if ($itRow ['last_working_month'] == $this->current_month) {
				$remainingMonths = 0;
			} else {
				$remainingMonths = $this->remaining_months;
			}
			$query = $this->exemptionText;
			eval ( "\$query = \"$query\";" );
			//echo "$this->financial_year_start, $this->prev_month_end,$employee_id";
			//echo $query;
			$stmt = mysqli_prepare ( $this->_connection, $query );
			
			mysqli_stmt_bind_param ( $stmt, 'ssssssss', $this->financial_year_start, $this->prev_month_end, $employee_id, $employee_id, $employee_id, $this->financial_year_start, $this->prev_month_end, $employee_id );
			$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
			mysqli_stmt_bind_result ( $stmt, $mappedTaxExemptions );
			mysqli_stmt_fetch ( $stmt );
			$this->mappedTaxExemptions = $mappedTaxExemptions;
		} else {
			$this->mappedTaxExemptions = 0;
		}
		return $this->mappedTaxExemptions;
	}
	private function meal_card_exe_10a($employee_id) {
		$this->mealCardExemptions = 0;
		if ($this->isMealCard == 1) {
			$stmt = mysqli_prepare ( $this->_connection, "SELECT SUM(m_mc) FROM(
														SELECT sum(p.m_mc) m_mc FROM payroll p WHERE p.employee_id =?
														UNION 
														SELECT pt.m_mc  m_mc FROM payroll_preview_temp pt WHERE pt.employee_id=?
														) t" );
			mysqli_stmt_bind_param ( $stmt, 'ss', $employee_id, $employee_id );
			$result = mysqli_stmt_execute ( $stmt );
			mysqli_stmt_bind_result ( $stmt, $this->mealCardExemptions );
			mysqli_stmt_fetch ( $stmt );
		}
		return $this->mealCardExemptions;
	}
	private function grossTotalIncome($itRow) {
		// other income + total income +house loan income etc..
		$this->prev_earnings = $itRow['prev_earnings_app'];
		$this->gross_total_income = $this->salaried_income + $this->otherIncome ( $itRow ) + $this->housePropertyIncome ( $itRow )+$this->prev_earnings;
		return $this->gross_total_income;
	}
	private function exe_10a($row, $employee_id) {
		$this->exe_medical = min ( $row ['medical_app'], 15000 ); //medical limit
		$this->exe_lta = $row ['lta_app'];
		//$months_worked = $this->monthsFromDate ( $row ['employee_doj'], $this->financial_year_end, true );
		//$trc_app_month = min ( ($months_worked), 12 ) * 1600;
		$this->exe_travel_con = 0;//min ( $row ['trc_app'], $trc_app_month );
		$this->exe_other_10a = $row ['others_app'];
		//$this->exeOther10a = ($this->exe_medical + $this->exe_lta + $this->exe_travel_con + $this->exe_other_10a + $this->other_mapped_exe_10a ( $row, $employee_id ) + $this->meal_card_exe_10a ( $employee_id ) + $this->yearly_epfEmplr);
		$this->exeOther10a = 40000;
		return $this->exeOther10a;
	}
	private function exe_hra($row) {
		// hra past present future value calc - > hra, basic yearly
		$hra_factor = in_array ( $this->employee_city, $this->hraMetroCities ) ? 0.5 : 0.4;
		$basicWitFactor = $this->yearly_basic * $hra_factor;
		$r_paid = ($row ['jan_app'] + $row ['feb_app'] + $row ['mar_app'] + $row ['apr_app'] + $row ['may_app'] + $row ['jun_app'] + $row ['jul_app'] + $row ['aug_app'] + $row ['sep_app'] + $row ['oct_app'] + $row ['nov_app'] + $row ['dec_app']);
		$tenPercentBasic = (10 / 100) * ($this->yearly_basic);
		// rent_paid
		$rent_paid = MAX ( 0, ($r_paid - $tenPercentBasic) );
		return $this->exe_hra = min ( $this->yearly_hra, $rent_paid, $basicWitFactor );
	}
	private function exe_80c($row) {
		$total_80c = $row ['80c_fd_app'] + $row ['80c_tution_app'] + $row ['80c_nsc_app'] + $row ['80c_sukanya_app'] + $row ['80c_inf_app'] + $row ['80c_vpf_app'] + $row ['80c_repa_app'] + $row ['80c_lic_app'] + $row ['80c_sip_app'] + $row ['80c_mut_app'] + $row ['80c_nps_app'] + $row ['80c_elss_app'] + $row ['80c_bonds_app'] + $row ['prev_pf_app']+ $this->yearly_epf;
		$ded_80c_sub = min ( $total_80c, $this->total_80c_exemptions_limit ); //
		$ccd1b_nps_app = min ( $row ['80ccd1b_nps_app'], $this->exe80ccd_nps_exemption_limit );
		if ($this->yearly_gross > $this->rgess_gross_condition) {
			$rgess_app = min ( ($row ['80ccg_rgess_app'] / 2), 25000 );
		} else {
			$rgess_app = "0";
		}
		$ded_80c = $ded_80c_sub + $ccd1b_nps_app + $rgess_app;
		$this->exe_80c = $ded_80c;
		return $ded_80c;
	}
	private function exe_80e($row) {
		$edu_app = $row ['80e_edu_app'];
		$this->exe_80e = $edu_app;
		return $edu_app;
	}
	private function exe_80d($row) {
		$employee_age = $this->current_year - substr($row ['employee_dob'],0,4);
		$father_age = $this->current_year - substr($row ['father_dob'],0,4);
		if ($employee_age >= 60) {//senior citizen
			$medself = $row ['80d_medself_app'] + $row ['80d_prev_app'];
			$medself_app = min ( $medself, $this->medi_ins_senior_limit );
		} else {
			$medself = $row ['80d_medself_app'] + $row ['80d_prev_app'];
			$medself_app = min ( $medself, $this->medi_ins_limit );
		}
		if ($father_age >= 60) {
			$min_parent = min ( $row ['80d_medpar_app'], $this->medi_ins_senior_limit );
		} else {
			$min_parent = min ( $row ['80d_medpar_app'], $this->medi_ins_limit );
		}
		if ($row ['80dd_med_app'] > 0) {
			$med_app = min ( $row ['80dd_med_app'], $this->medi_trmt_ordinary_limit );
		} else {
			$med_app = "0";
		}
		
		if ($row ['80dd_medsev_app'] > 0) {
			$medsev_app = min ( $row ['80dd_medsev_app'], $this->medi_trmt_severe_limit );
		} else {
			$medsev_app = "0";
		}
		
		if ($employee_age >= 60) {
			$med1_app = min ( $row ['80ddb_med1_app'], $this->medi_trmt_spcified_disease_senior_limit );
		} else {
			$med1_app = min ( $row ['80ddb_med1_app'], $this->medi_trmt_spcified_disease_limit );
		}
		$ded_80d = $medself_app + $min_parent + $med_app + $medsev_app + $med1_app;
		$this->exe_80d = $ded_80d;
		return $ded_80d;
	}
	private function exe_80g($row) {
		$national_app = $row ['80g_don1_app'];
		$trust_app = ($row ['80g_don2_app']) * (50 / 100);
		$this->exe_80e = $row ['80e_edu_app'];
		$income = $this->gross_total_income - ($this->exe_80c - $this->exe_80d - $row ['80e_edu_app']);
		$family_sub = ($income) * (10 / 100);
		$family_sub = max(0,$family_sub);
		$donation = $row ['80g_don3_app'] + $row ['80g_don4_app'] / 2;
		$donation_cal = min ( $donation, $family_sub );
		$political = $row ['80g_don6_app'];
		$ded_80g = $national_app + $trust_app + $donation_cal + $political + $row ['80g_don5_app'];
		$sav_app = min ( $row ['80tta_sav_app'], $this->interest_savings_limit );
		$dis1_app = min ( $row ['80u_dis1_app'], $this->personal_disability_limit );
		$dis2_app = min ( $row ['80u_dis2_app'], $this->personal_severe_disability_limit );
		$this->exe_deductions_other = $sav_app + $dis1_app + $dis2_app;
		$this->exe_80g = $ded_80g;
		return $ded_80g;
	}
	private function yearlyTax($row) {
		$exemptions = $this->exe_80c ( $row ) + $this->exe_80d ( $row ) + $this->exe_80e ( $row ) + $this->exe_80g ( $row ) + $this->exe_deductions_other;
		$taxable_income = $this->gross_total_income - $exemptions; // 303600
		$this->taxable_inc = $taxable_income;
		$this->gross_total_income;
		$tax = 0;
		$slabs = null;
		if ($this->employee_age < 60) {
			// G
			$slabs = $this->it_slabs ["G"];
		} elseif ($this->employee_age >= 60 && $this->employee_age < 80) {
			// A
			$slabs = $this->it_slabs ["A"];
		} elseif ($this->employee_age >= 80) {
			// S
			$slabs = $this->it_slabs ["S"];
		}
		foreach ( $slabs as $slab ) {
			// array("from_value":,"to_value":,"rate")
			/*
			 * 0 - 2,50,000 0%
			 * 2,50,001 - 5,00,000 10%
			 * 5,00,001 - 10,00,000 20%
			 * 10,00,001 - 30%
			 *
			 */
			
			$diff_from_to = max ( $slab ['to_value'] - $slab ['from_value'], 0 );
			if ($taxable_income > $diff_from_to && $diff_from_to != 0) {
				$tax += $diff_from_to * ($slab ['rate'] / 100);
				$taxable_income = $taxable_income - $diff_from_to;
			} else {
				$tax += $taxable_income * ($slab ['rate'] / 100);
				$taxable_income = 0;
				continue;
			}
		}
		$this->rebate = $this->taxable_inc < $this->rebate_limit ? $this->rebate_amount : 0;
		$tax = $tax - $this->rebate;
		 if($this->yearly_gross > $this->surcharge_percentage1_applicable_to && $this->yearly_gross < $this->surcharge_percentage2_applicable_to)
			$sur_charge = ROUND ($tax * ($this->surcharge_percentage1/100));
		elseif($this->yearly_gross > $this->surcharge_percentage2_applicable_to)
			$sur_charge = ROUND ( $tax * ($this->surcharge_percentage2/100));
		else 
			$sur_charge = 0;
		// $tax += $sur_charge;
		$edu_chess = ($tax+$sur_charge) * 0.03;
		$higher_edu_chess = ($tax+$sur_charge) * 0.01;
		$this->tax = MAX ( 0, $tax );
		$tax = $tax + $sur_charge + $edu_chess + $higher_edu_chess;
		$this->ec = MAX ( 0, $edu_chess );
		$this->shec = MAX ( 0, $higher_edu_chess );
		$this->surcharge = MAX ( 0, $sur_charge );
		return $tax;
	}
	private function taxPayable($itRow) {
		$this->tax_payable = max ( 0, $this->yearlyTax ( $itRow ) - $itRow ['prev_tax_app'] - $itRow ['old_tax_paid'] - $this->tax_paid );
		$tds = $this->tax_payable / ($this->remaining_months + 1);
		$this->tds = ROUND ( max ( $tds, 0 ) );
		return $this->tds;
	}
	private function updateITtable() {
		$payable_tax = $paid_tax =0;
		if(substr($this->payroll_month_year,0,2)=='03'){
			$payable_tax = 0;
			$paid_tax = $this->tds+$this->tax_paid;
		}else{
			$payable_tax = $this->tax_payable;
			$paid_tax = $this->tax_paid;
		}
		
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		$stmt = mysqli_prepare ( $this->_connection, "UPDATE employee_income_tax SET epf_employer=?,epf_employee=?,other_income=ROUND(?),exe_hra=ROUND(?),exe_lta=?,exe_oth=ROUND(?),total_inc=ROUND(?),
		house_prop_inc=?,ded_80e=?,ded_80c=?,gti=?,ded_80d=?,ded_80g=?,ded_other=?,taxable_inc=ROUND(?),rebate=?,tax=CEIL(?),ec=CEIL(?),shec=CEIL(?),surcharge=?,
		tax_payable=CEIL(?),tds=CEIL(?),tax_paid=CEIL(?),updated_by=?,taxon_employment=?,prev_earnings_app=ROUND(?) WHERE employee_id =? AND year=? " ) or die(mysqli_error($this->_connection));
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssssssssssssssssssss', $this->yearly_epfEmplr, $this->yearly_epf, $this->other_income, $this->exe_hra, $this->exe_lta, $this->exeOther10a, $this->salaried_income, $this->house_prop_inc, $this->exe_80e, $this->exe_80c, $this->gross_total_income, $this->exe_80d, $this->exe_80g, $this->exe_deductions_other, $this->taxable_inc, $this->rebate, $this->tax, $this->ec, $this->shec, $this->surcharge, $payable_tax, $this->tds, $paid_tax, $this->updated_by, $this->yearly_pt,$this->prev_earnings, $this->employee_id, $this->fin_year );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
	}
	public static function monthsFromDate($start_date, $end_date, $includeCurrentMonth) {
		// calculate how many months between dates
		
		$startDate=isset(explode ( "-", $start_date ) [1])?explode ( "-", $start_date ) [1]:'start';
		$endDate=isset(explode ( "-", $end_date ) [1])?explode ( "-", $end_date ) [1]:'end';
		if ($startDate== $endDate) {
		 	$temp = 0;
		} else {
			$curr_month = $includeCurrentMonth == true ? 1 : 0;
			$datetime1 = date_create ( $start_date );
			$datetime2 = date_create ( $end_date );
			$interval = date_diff ( $datetime1, $datetime2 );
			$temp = (($interval->format ( '%y' ) * 12) + $interval->format ( '%m' )) + $curr_month;
		}
		return $temp;
	}
}
?>