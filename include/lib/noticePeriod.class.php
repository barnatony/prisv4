<?php
/*
 * ----------------------------------------------------------
 * Filename : noticePeriod.handle.php
 * Classname: noticePeriod.class.php
 * Author : Raja Sundari
 * Database : emp_notice_period
 * Oper : NoticePeriod Actions
 *
 * ----------------------------------------------------------
 */
require_once (__DIR__ . "/notification.class.php");
class noticePeriod {
	/* Member variables */
	var $noticePeriodId;
	var $employee_id;
	var $notice_date;
	var $last_date;
	var $reason;
	var $letter_text;
	var $process_type;
	var $status;
	var $updated_by;
	var $employee_doj;
	var $last_working_date;
	var $conn;
	function __construct($conn) {
		$this->conn = $conn;
		date_default_timezone_set(DEFAULT_TIMEZONE);
		$stmt = "SELECT salary_days,attendance_period_sdate attendance_dt FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'";
		$result = mysqli_query ( $this->conn, $stmt );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->conn));
		$this->attendance_start_date=$row['attendance_dt'];
		if($row['attendance_dt'] !=1){
		if($_SESSION['monthNo']!=01){
				$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo']-1)."-".$row['attendance_dt'];
				$this->endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($row['attendance_dt']-1);
			}else{
				$this->startDate = ($_SESSION['payrollYear']-1)."-12-".$row['attendance_dt'];
				$this->endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($row['attendance_dt']-1);
			}
		}else{
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
			$this->endDate = date('Y-m-t',strtotime($this->startDate));
		}
	}
	function __destruct() {
		//mysqli_close ( $this->conn );
	}
	/* Member functions */
	/* Insert New NoticePeriod */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO emp_notice_period(notice_id, employee_id, notice_date, last_working_date, reason, 
				letter_text,remark, process_type,status, updated_by)  VALUES (?,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?,?);" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssss', $this->noticePeriodId, $this->employee_id, $this->notice_date, $this->last_date, $this->reason, $this->letter_text, $this->remark, $this->process_type, $this->status, $this->updated_by );
		//When Status is Stop Pay Disable Employee 
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		$notification = new Notification();
		$notification ->connection = $this->conn;
		$insertNotif = $notification->insertNotifications('noticeRequested', $this->employee_id,'Admin',$this->noticePeriodId,' has requested for <b>Notice Period</b> ');
		
		$processed_on = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
		if($this->process_type=='SP' && $result===true)
			$exitResult=$this->exitEmployee($this->employee_id,$this->last_date,$processed_on);
		
			   
		if($result===true && (isset($exitResult['result'])?$exitResult['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else 
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	function exitEmployee($employee_id,$lastWorkingDate,$processed_on){
    $updateNotice  = "UPDATE emp_notice_period np SET status='S',processed_on='$processed_on' WHERE np.employee_id='$employee_id' AND np.status='A';";
    $result = mysqli_query($this->conn, $updateNotice) or die(mysqli_error($this->conn));
    
	$deletePreview = "DELETE FROM payroll_preview_temp WHERE employee_id='$employee_id';";
	$result = mysqli_query($this->conn, $deletePreview) or die(mysqli_error($this->conn));
	
	$updateWorkdetails= "UPDATE employee_work_details SET enabled=0,dateofexit=STR_TO_DATE('$lastWorkingDate','%d/%m/%Y') WHERE  employee_id='$employee_id';";
	
	$result = mysqli_query($this->conn, $updateWorkdetails) or die(mysqli_error($this->conn));
			 
			if (mysqli_error ( $this->conn )) {
				return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
			}else{
				return array("result"=>true);
			}
	}
	
	/* Update NoticePeriod Using NoticePeriod ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE emp_notice_period 
        SET notice_date = STR_TO_DATE(?,'%d/%m/%Y'),last_working_date = STR_TO_DATE(?,'%d/%m/%Y'),
		reason=?,letter_text=?,remark=?,process_type=?,status=?,updated_by=? WHERE notice_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssss', $this->notice_date, $this->last_date, $this->reason, $this->letter_text, $this->remark, $this->process_type, $this->status, $this->updated_by, $this->noticePeriodId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		if($this->process_type=='SP' && $result===true)
			   $exitResult=$this->exitEmployee($this->employee_id,$this->last_date);
		
			   
		if($result===true && (isset($exitResult['result'])?$exitResult['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else 
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	/* select currentMonthExit */
	function currentMonthExit() {
		$ajson = array ();
		$stmt = "SELECT  n.employee_id,n.notice_id,DATE_FORMAT(w.employee_doj,'%d/%m/%Y') employee_doj,CONCAT(w.employee_name,' ',w.employee_lastname), DATE_FORMAT(n.notice_date,'%d/%m/%Y') notice_date, DATE_FORMAT
											(n.last_working_date,'%d/%m/%Y') last_working_date, r.reason_code, n.process_type,n.status,n.id
											FROM  emp_notice_period n
											INNER JOIN   employee_work_details w ON  
											 n.employee_id = w.employee_id  AND  n.last_working_date >='".$this->startDate."'
											 AND  n.last_working_date <= '".$this->endDate. "'
											 AND n.status IN ('A','S') 
											LEFT JOIN   exit_reasons r ON n.reason =r.exit_id";
		
		if($result = mysqli_query ( $this->conn, $stmt )){
													while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
														array_push ( $ajson, $row );
													}
													return ((empty( $ajson) )?array("result"=>true,"data"=>'No Data Found'):array("result"=>true,"data"=>$ajson));
														
		}else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
		
	}
	
	/* select pendingExit */
	function pendingExit() {
		$ajson = array ();
		$stmt = "SELECT n.notice_id,n.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname), DATE_FORMAT(n.notice_date,'%d/%m/%Y') notice_date,
					DATE_FORMAT(n.last_working_date,'%d/%m/%Y') last_working_date, r.reason_code, n.process_type, n.status,n.id
				FROM  emp_notice_period n
				INNER JOIN   employee_work_details w ON
				n.employee_id = w.employee_id
				AND n.status IN ('P','H','A','S') 
				LEFT JOIN   exit_reasons r ON n.reason =r.exit_id ORDER BY id DESC" ;
		if($result = mysqli_query ( $this->conn, $stmt)){
											while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
												array_push ( $ajson, $row );
											}
		return ((empty( $ajson) )?array("result"=>true,"data"=>'No Data Found'):array("result"=>true,"data"=>$ajson));
											
		}else 
		return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	/* select noticeperiod */
	function select($noticeIds) {
		$a_json = array ();
		$stmt = "SELECT n.notice_id, CONCAT(w.employee_name,' ',w.employee_lastname) employee_name, DATE_FORMAT(n.notice_date,'%d/%m/%Y') notice_date,
					DATE_FORMAT(n.last_working_date,'%d/%m/%Y') last_working_date, n.reason,IF(n.process_type= '', null, n.process_type)  process_type , n.status,n.remark,n.letter_text,n.employee_id
				FROM  emp_notice_period n
				INNER JOIN   employee_work_details w ON  
				n.employee_id = w.employee_id  
				AND n.notice_id='$noticeIds'
				LEFT JOIN   exit_reasons r ON n.reason =r.exit_id";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	/* select based on employee_id From empNoticePeriod */
	function employeeNotice($employeeId) {
		$a_json = array ();
		$stmt = "SELECT DATE_FORMAT(n.notice_date,'%d/%m/%Y') notice_date, DATE_FORMAT(n.last_working_date,'%d/%m/%Y') last_working_date, n.letter_text,n.status,n.notice_id
				 FROM  emp_notice_period n
				 WHERE employee_id='$employeeId'";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	/* settlement For Employee salry Prview */
	function previewSettlmentPayslip($employee_id, $stmt, $stmtForpreview, $retirementArray) {
		$retirementval = "";
		$retirementNullval = "";
		$sumRetirment = "";
		$sumRetirmentData = "";
		foreach ( $retirementArray ['RA'] as $retireAllow ) {
			$retirementval .= $retireAllow ['pay_structure_id'] . ",";
			$sumRetirment .= $retireAllow ['pay_structure_id'] . "+";
			$retirementNullval .= "NULL,";
		}
		if ($sumRetirment != '') {
			$sumRetirmentData .= substr ( $sumRetirment, 0, - 1 ) . "+";
		}
		$retirmentSum = "SUM($sumRetirmentData py.net_salary) Netpayable";
		
		$queryStmt = $stmt . $retirementval . "SUM(t.gross_salary) gross_salary,SUM(t.net_salary) net_salary";
		$previewqueryStmt = str_replace ( ")", "", str_replace ( "SUM(t.", "", $stmtForpreview ) ) . "py.gross_salary,py.net_salary";
		$payrollqueryStmt = str_replace ( "t.", "p.", $stmt ) . "SUM(p.gross_salary) gross_salary,SUM(p.net_salary) net_salary," . $retirementNullval;
		
		$a_json = [ ];
	    $fnfstmt = "SELECT t.employee_id,t.employee_name,t.designation_name,t.department_name,
                                             t.employee_doj,REPLACE(t.employee_bank_name,'_',' ') employee_bank_name,t.employee_acc_no,t.employee_pan_no,t.employee_bank_ifsc,t.branch_name,t.employee_emp_pf_no,
				                             t.employee_emp_uan_no,t.employee_emp_esi_no,SUM(t.lop) lop,$queryStmt,SUM(t.total_deduction) total_deduction,t.last_working_date,t.process_type,ROUND(SUM(t.Netpayable)) Netpayable
											 FROM (
											 SELECT w.employee_id,w.employee_name,des.designation_name,dep.department_name,
											 DATE_FORMAT(w.employee_doj, '%d/%m/%Y') employee_doj,
											 p.employee_bank_name,p.employee_acc_no,p.employee_pan_no,p.employee_bank_ifsc,
				                             br.branch_name,w.employee_emp_pf_no ,w.employee_emp_uan_no,w.employee_emp_esi_no,py.lop,$previewqueryStmt,$retirementval py.total_deduction,
				 							 DATE_FORMAT(np.last_working_date, '%d/%m/%Y') last_working_date,np.process_type,$retirmentSum
											 FROM
								             employee_work_details w
		                                     INNER JOIN employee_personal_details p 
		                                     ON w.employee_id = p.employee_id 
		                                     INNER JOIN payroll_preview_temp py 
		                                     ON w.employee_id = py.employee_id
		                                     INNER JOIN company_designations des
		                                     ON w.designation_id = des.designation_id
		                                     INNER JOIN company_departments dep
		                                     ON w.department_id = dep.department_id
		                                     INNER JOIN company_branch br
		                                     ON w.branch_id = br.branch_id
		                                     INNER JOIN company_job_statuses js
		                                     ON w.status_id = js.status_id
				                             INNER JOIN emp_notice_period np
		                                     ON w.employee_id = np.employee_id AND
				                             w.enabled=1  
                                             AND np.status='A' 
                                             AND np.last_working_date BETWEEN '" . $this->startDate . "' AND '".$this->endDate . "'
				                             LEFT JOIN settlements s
		                                     ON w.employee_id = s.employee_id
				                             AND s.month_year='" . $_SESSION ['current_payroll_month'] . "'
		                                     WHERE  py.employee_id='$employee_id'
											 UNION ALL
											 SELECT NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
											 SUM(lop),$payrollqueryStmt total_deduction,NULL,NULL,SUM(net_salary) Netpayable
											 FROM payroll p
											 WHERE p.is_pay_pending=1  AND p.employee_id='$employee_id') t";
	   // print_r($fnfstmt);
	   // die();
		$result = mysqli_query ( $this->conn, $fnfstmt );
		
		While ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			if ($row ['employee_doj']) {
				$this->employee_doj = $row ['employee_doj'];
			}
			if ($row ['last_working_date']) {
				$this->last_working_date = $row ['last_working_date'];
			}
			array_push ( $a_json, $row );
		}
		
		$retireBenefits = new RetirementBenefit ();
		$retireBenefits->conn = $this->conn;
		
		$benefitsArray = array ();
		$retirmentSum = 0;
		foreach ( $retirementArray ['RA'] as $retireAllow ) {
			if ($retireAllow ['pay_structure_id'] == 'r_gratuity' || $retireAllow ['pay_structure_id'] == 'r_retrenchment' || $retireAllow ['pay_structure_id'] == 'r_leaveenc') {
				$paystructureVal = $a_json [0] [$retireAllow ['pay_structure_id']];
				if ($paystructureVal == '') {
					$benefitsAmount = $retireBenefits->calculateBenefit ( $retireAllow ['pay_structure_id'], $this->employee_id, $this->employee_doj, $this->last_working_date );
					$this->updatedGratuity ( 1, $retireAllow ['pay_structure_id'], $employee_id, $benefitsAmount ['amount'], $benefitsAmount ['amount_it_exempted'], $remarks = null );
					$benefitsArray [$retireAllow ['pay_structure_id']] = $benefitsAmount ['amount'];
					$retirmentSum += $benefitsAmount ['amount'];
				}
			}
		}
		
		if ($a_json [0] ['Netpayable'] == '') {
			$a_json [0] ['Netpayable'] = ROUND ( $a_json [0] ['net_salary'] + $retirmentSum );
		}
		
		$a_json = array_merge ( $a_json [0], $benefitsArray );
		return $a_json;
	}
	
	// $gratuity Store in Settlemnet Table
	function updatedGratuity($flag, $benefit, $employee_id, $amount, $amount_it_exempted, $remarkData) {
		// Retirment Benifits
		$itExempted = $benefit . '_exempted';
		$remarks = $benefit . '_remarks';
		if($remarkData !='-'){
			$remarkData = $remarkData;
		}else{
			$remarkData=' ';
			};
		
		if ($flag == 1) {
			$stmt = mysqli_prepare ( $this->conn, "INSERT INTO settlements (employee_id, month_year, $benefit, $itExempted, $remarks, updated_by)
                                            VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE $benefit =?,$itExempted=?,$remarks=?" );
			mysqli_stmt_bind_param ( $stmt, 'sssssssss', $employee_id, $_SESSION ['current_payroll_month'], $amount, $amount_it_exempted, $remarkData, $this->updated_by, $amount, $amount_it_exempted, $remarkData );
		} else if ($flag == 0) {
			$stmt = mysqli_prepare ( $this->conn, "UPDATE settlements SET  $benefit=?, $remarks=?, updated_by=? WHERE employee_id=? AND  month_year=?" );
			mysqli_stmt_bind_param ( $stmt, 'sssss', $amount, $remarkData, $this->updated_by, $employee_id, $_SESSION ['current_payroll_month'] );
		}
		
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
	}
	public function downloadGeneratePdf($empId, $processType, $querystmt, $retirementArray,$isPreview) {
		$querystmt1 = str_replace("SUM","",$querystmt);
		$previewQuery= str_replace("t.","p.",$querystmt1). "SUM(p.gross_salary) gross_salary,SUM(p.net_salary) net_salary";
				
		$retirementval = "";
		$sumRetirment = "";
		foreach ( $retirementArray ['RA'] as $retireAllow ) {
			$retirementval .= "SUM(" . $retireAllow ['pay_structure_id'] . ") " . $retireAllow ['pay_structure_id'] . ",";
			$sumRetirment .= "SUM(IFNULL(" . $retireAllow ['pay_structure_id'] . ",0))+";
		}
		
		if ($sumRetirment != '') {
			$sumRetirmentTOt = "(" . substr ( $sumRetirment, 0, - 1 ) . "+SUM(net_salary)) Netpayable";
		} else {
			$sumRetirmentTOt = "SUM(net_salary) Netpayable";
		}
		
		if($isPreview==0){
			if ($processType == 'P' || $processType == 'SP') {
				$extraCondition =""; 
				$settlementCond = "AND t.month_year = np.processed_on";
			} else if ($processType == 'S') {
				$extraCondition = "t.is_pay_pending=1 AND ";
				$settlementCond ="";
			}
		}else {
			$extraCondition ="";
			$settlementCond ="";
		}
		
		$a_json = array ();
		$htmlStyle = '<style>
.alignLeft {
   text-align: left;
}
.alignRight {
    text-align: right;
}
.alignCenter{
      text-align: center;
 }
    table {
    border-collapse: collapse;
	 		
}
td {
    padding: 4px;
  }
table#t02 {border:1px solid #000;
	 }
	 			table#t01 {border:1px solid #000;
	 }
table#t02 tr {border-top:1px solid #000;}
.fontSize{
   font-weight: bold;
}
.topBorder {
	 		border-top:1px solid #000;
	 	    }
.bottomBorder{
	 		border-bottom:1px solid #000;
	     }
	 			</style>';
		// style Purpose
		include (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
		
		if($isPreview==1){
			$retirementval = "";
			$retirementNullval = "";
			$sumRetirment = "";
			$sumRetirmentData = "";
			foreach ( $retirementArray ['RA'] as $retireAllow ) {
				$retirementval .= $retireAllow ['pay_structure_id'] . ",";
				$sumRetirment .= $retireAllow ['pay_structure_id'] . "+";
				$retirementNullval .= "NULL,";
			}
			if ($sumRetirment != '') {
				$sumRetirmentData .= substr ( $sumRetirment, 0, - 1 ) ;
			}
			if($sumRetirment != ''){
				$retirmentSum = "SUM(IFNULL($sumRetirmentData,0) + p.net_salary) Netpayable";
			}else{
				$retirmentSum = "p.net_salary Netpayable";
			}
			$payrollQuery = str_replace("t.","p.",$querystmt). "SUM(p.gross_salary) gross_salary,SUM(p.net_salary) net_salary," . $retirementNullval;
				
			
			$totStmt = $querystmt . $retirementval . "SUM(t.gross_salary) gross_salary,SUM(IFNULL(t.net_salary,0)) net_salary";
			
			$stmt="SELECT t.employee_id,t.employee_name,t.designation_name,t.department_name,
                           t.employee_doj,IF(t.employee_bank_name='','-',REPLACE(t.employee_bank_name,'_',' ')) employee_bank_name,IF(t.employee_acc_no='','-',t.employee_acc_no) employee_acc_no,IF(t.employee_pan_no='','-',t.employee_pan_no) employee_pan_no,IF(t.employee_bank_ifsc='','-',t.employee_bank_ifsc) employee_bank_ifsc,IF(t.branch_name='','-',t.branch_name) branch_name,t.employee_emp_pf_no,
				           t.employee_emp_uan_no,IF(t.employee_emp_esi_no='','-',t.employee_emp_esi_no) employee_emp_esi_no,SUM(t.lop) lop,$totStmt,SUM(t.total_deduction) total_deduction,t.last_working_date,t.process_type,ROUND(SUM(t.Netpayable)) Netpayable,
				           t.company_name,t.company_logo,t.company_build_name,t.company_street,t.company_area,t.company_city,t.company_pin_code
						   FROM (
								SELECT w.employee_id,w.employee_name,des.designation_name,dep.department_name,
								DATE_FORMAT(w.employee_doj, '%d/%m/%Y') employee_doj,
								pd.employee_bank_name,pd.employee_acc_no,pd.employee_pan_no,pd.employee_bank_ifsc,
				                br.branch_name,w.employee_emp_pf_no ,w.employee_emp_uan_no,w.employee_emp_esi_no,p.lop,$previewQuery,$retirementval p.total_deduction,
				 				DATE_FORMAT(np.last_working_date, '%d/%m/%Y') last_working_date,np.process_type,$retirmentSum,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,c.company_pin_code
								FROM
								employee_work_details w
		                        INNER JOIN employee_personal_details pd 
		                        ON w.employee_id = pd.employee_id 
		                        INNER JOIN payroll_preview_temp p 
		                        ON w.employee_id = p.employee_id
		                        INNER JOIN company_designations des
		                        ON w.designation_id = des.designation_id
		                        INNER JOIN company_departments dep
		                        ON w.department_id = dep.department_id
		                        INNER JOIN company_branch br
		                        ON w.branch_id = br.branch_id
		                        INNER JOIN company_job_statuses js
		                        ON w.status_id = js.status_id
				                INNER JOIN emp_notice_period np
		                        ON w.employee_id = np.employee_id AND
				                w.enabled=1  
                                AND np.status='A' 
                                AND np.last_working_date BETWEEN '" . $this->startDate . "' AND '".$this->endDate . "'
                                INNER JOIN company_details c ON c.company_id ='" . $_SESSION ['company_id'] . "' AND c.info_flag='A'
				                LEFT JOIN settlements s
		                        ON w.employee_id = s.employee_id
				                AND s.month_year='" . $_SESSION ['current_payroll_month'] . "'
		                        WHERE  p.employee_id='$empId'
								UNION ALL
								SELECT NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
								SUM(lop),$payrollQuery p.total_deduction,NULL,NULL,SUM(p.net_salary) Netpayable,NULL,NULL,NULL,NULL,NULL,NULL,NULL
								FROM payroll p
								WHERE p.is_pay_pending=1  AND p.employee_id='$empId') t";                  
			
		}else {
			$stmt = "SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,des.designation_name,dep.department_name,
			DATE_FORMAT(w.employee_doj, '%d/%m/%Y') employee_doj,t.month_year,
			IF(p.employee_bank_name='','-',REPLACE(p.employee_bank_name,'_',' ')) employee_bank_name,IF(p.employee_acc_no='','-',p.employee_acc_no) employee_acc_no,IF(p.employee_pan_no='','-',p.employee_pan_no) employee_pan_no,IF(p.employee_bank_ifsc='','-',p.employee_bank_ifsc) employee_bank_ifsc,
			br.branch_name,IF(w.employee_emp_pf_no='','-',w.employee_emp_pf_no) employee_emp_pf_no ,w.employee_emp_uan_no,IF(w.employee_emp_esi_no='','-',w.employee_emp_esi_no) employee_emp_esi_no,$querystmt $retirementval SUM(total_deduction) total_deduction,SUM(gross_salary) gross_salary,ROUND(SUM(net_salary)) net_salary,
			DATE_FORMAT(np.last_working_date, '%d/%m/%Y') last_working_date,t.lop,
			c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,c.company_pin_code,$sumRetirmentTOt
			FROM
			employee_work_details w
			INNER JOIN employee_personal_details p
			ON w.employee_id = p.employee_id
			INNER JOIN payroll t
			ON w.employee_id = t.employee_id
			INNER JOIN company_designations des
			ON w.designation_id = des.designation_id
			INNER JOIN company_departments dep
			ON w.department_id = dep.department_id
			INNER JOIN company_branch br
			ON w.branch_id = br.branch_id
			INNER JOIN company_job_statuses js
			ON w.status_id = js.status_id
			INNER JOIN emp_notice_period np
			ON w.employee_id = np.employee_id $settlementCond
			INNER JOIN company_details c ON c.company_id ='" . $_SESSION ['company_id'] . "' AND c.info_flag='A'
			AND np.status IN ('A','S')
			LEFT JOIN settlements s
			ON t.employee_id = s.employee_id AND t.month_year=s.month_year
			WHERE $extraCondition  t.employee_id='$empId'";
			
		}
	    
	    $result = mysqli_query ( $this->conn, $stmt ) or die(mysqli_error($this->conn));
		$count = 0;
		$payment_mode_id = "";
		$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		if(!$row["employee_id"])
			die("No Data Found. Cann't Generate Settlement.");
		$html = '';
		if($isPreview==1){
			$month = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
		}else{
			$month = $row ['month_year'];
		}
	    //$month = $row ['last_working_date'];
	    //$month = str_replace("/","-",$month);
		$month_name = date('F Y',strtotime ($month));
		$header = '<table style="width:100%;" class="bottomBorder"> <tr><td   style="width:33%"><img   style="width:28%;height:7%" src=../' . $row ['company_logo'] . '></td><td colspan="3">' . strtoupper ( $row ['company_name'] ) . '<br>' . strtoupper ( $row ['company_build_name'] ) . ',' . strtoupper ( $row ['company_street'] ) . ',' . strtoupper ( $row ['company_area'] ) . ',<br>' . strtoupper ( $row ['company_city'] ) . ',' . $row ['company_pin_code'] . '</td></tr></table>';
		
		$html .= '<br><br><br><br><table style="width:100%;">
                          <tr><td class="" colspan="4" style="font-weight: bold;text-align:center;">
                                         ';
		//setting the flag. when the $isPreview flag is 1 it will print Provisional or else it will remove Provisional word
		if($isPreview==1){
		$html .= strtoupper ( "Provisional Full and Final Settlement For The  Month  of " ) . " " . strtoupper ( $month_name ) . '
                          </td> </tr></table><br><table style="width:100%;" id="t01">
                             ';
		}
		else {
			$html .= strtoupper ( "Full and Final Settlement For The  Month of " ) . " " . strtoupper ( $month_name ) . '
                          </td> </tr></table><br><table style="width:100%;" id="t01">
                             ';
		}
		$html .= '<tr><td>Employee Name </td>
                     <td> :&nbsp; ' . $row ['employee_name'] . '</td><td>ESI<td > :&nbsp;' . $row ['employee_emp_esi_no'] .'</td></tr>
      		         <tr><td> Employee ID </td>
                     <td> :&nbsp; ' . $row ['employee_id'] . '</td><td>EPF<td > :&nbsp;' . $row ['employee_emp_pf_no'] . '</td></tr>
      	           	 <tr><td> DOJ</td>
                     <td> :&nbsp; ' . $row ['employee_doj'] . '</td><td>Bank Name<td > :&nbsp;' . $row ['employee_bank_name'] .'</td></tr>
    		         <tr><td> Department</td>
                     <td> :&nbsp; ' . $row ['department_name'] . '</td><td>Account No<td > :&nbsp;' . $row ['employee_acc_no'] .'</td></tr>
      		         <tr><td> Designation</td>
                     <td> :&nbsp; ' . $row ['designation_name'] . '</td><td>Pan No<td > :&nbsp;' . $row ['employee_pan_no'] . '</td></tr>
       		         <tr><td> Branch</td>
                     <td> :&nbsp; ' . $row ['branch_name'] . '</td><td>IFSC<td > :&nbsp;' . $row ['employee_bank_ifsc'] . '</td></tr>
                     <tr><td>Last Working Date </td > :
                     <td> :&nbsp;' . $row ['last_working_date'] . '</td><td>LOP<td > :&nbsp;' . $row ['lop'] . '</td></tr></table>';
					
		
		$html .= '<br><table style="width:100%" id="t02">';
		
		$html .= '<tr>
                        <td class="fontSize alignCenter bottomBorder">Payheads</td><td class="fontSize alignCenter bottomBorder">Monthly</td>
                        <td class="fontSize alignCenter bottomBorder">Deductions</td><td class="fontSize alignCenter bottomBorder">Monthly</td></tr>';
		
		$allAllowNameId = array ();
		$allDeducNameId = array ();
		// Allowances and Deduction
		Session::newInstance ()->_setGeneralPayParams ();
		$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
		
		foreach ( $allowDeducArray ['A'] as $allow ) {
			if ($row [$allow ['pay_structure_id']] > 0) {
				$allAllowNameId [] = $allow ['display_name'] . "," . $allow ['pay_structure_id'];
			}
		}
		
		foreach ( $allowDeducArray ['D'] as $dedu ) {
			if ($row [$dedu ['pay_structure_id']] > 0) {
				$allDeducNameId [] = $dedu ['display_name'] . "," . $dedu ['pay_structure_id'];
			}
		}
		
		// miscAllowances and miscDeduction
		Session::newInstance ()->_setGeneralPayParams ();
		$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
		
		foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
			if ($row [$miscAllow ['pay_structure_id']] > 0) {
				$row [$miscAllow ['pay_structure_id']];
				$allAllowNameId [] = $miscAllow ['display_name'] . "," . $miscAllow ['pay_structure_id'];
			}
		}
		
		foreach ( $miscallowDeducArray ['MD'] as $miscDedu ) {
			if ($row [$miscDedu ['pay_structure_id']] > 0) {
				$allDeducNameId [] = $miscDedu ['display_name'] . "," . $miscDedu ['pay_structure_id'];
			}
		}
		
		// retirement Allowances And Deduction
		Session::newInstance ()->_setRetirementParams ();
		$retirementArray = Session::newInstance ()->_get ( "retirementParams" );
		
		foreach ( $retirementArray ['RA'] as $retirementAllow ) {
			if ($row [$retirementAllow ['pay_structure_id']] > 0) {
				$allRetireNameId [] = $retirementAllow ['display_name'] . "," . $retirementAllow ['pay_structure_id'];
			}
		}
		
		foreach ( $retirementArray ['RD'] as $retirementDedu ) {
			if ($row [$retirementDedu ['pay_structure_id']] > 0) {
				$decuRetireNameId [] = $retirementDedu ['display_name'] . "," . $retirementDedu ['pay_structure_id'];
			}
		}
		
		if (count ( $allAllowNameId ) != count ( $allDeducNameId )) {
			foreach ( $allAllowNameId as $key => $value ) :
				if (! isset ( $allDeducNameId [$key] ))
					$allDeducNameId [$key] = NULL;
			endforeach
			;
			$combinedAllowDeduc = array_combine ( $allAllowNameId, $allDeducNameId );
		}
		
		if (! $combinedAllowDeduc) {
			if (count ( $allDeducNameId ) != count ( $allAllowNameId )) {
				foreach ( $allDeducNameId as $key => $value ) :
					if (! isset ( $allAllowNameId [$key] ))
						$allAllowNameId [$key] = NULL;
				endforeach
				;
			}
			$combinedAllowDeduc = array_combine ( $allAllowNameId, $allDeducNameId );
		}
		
		foreach ( $combinedAllowDeduc as $k => $v ) {
			list ( $allowName, $allowId ) = explode ( ",", $k );
			list ( $deducName, $deducId ) = explode ( ",", $v );
			if($allowName !='' && $deducName != ''){   
				$html .= '<tr><td style="width:25%">' . $allowName . '</td><td class="alignRight" style="width:25%">' . inr_format( $row [$allowId]) . '</td>
				          <td style="width:25%">' . $deducName . '</td><td class="alignRight" style="width:25%" >' .  inr_format( $row [$deducId]) . '</td></tr>';
			}else{ 
				$html .= '<tr><td style="width:25%">' . $allowName . '</td><td class="alignRight" style="width:25%">' . inr_format( $row [$allowId]) . '</td>
							<td style="width:25%">' . $deducName . '</td><td class="alignRight" style="width:25%" >' .   $row [$deducId] . '</td></tr>';
			}	
		}
		// Retirment
		$html .= '<tr><td class="fontSize alignRight topBorder bottomBorder">
                                 Gross</td><td  class="fontSize  alignRight topBorder bottomBorder">' . inr_format( $row ['gross_salary']) . '</td >
                                 <td class="fontSize alignRight topBorder bottomBorder">Total Deductions</td><td  class="fontSize  alignRight topBorder bottomBorder">' . inr_format( $row ['total_deduction']) . '</td></tr>
                          		<tr><td class="fontSize alignRight bottomBorder" colspan="3">
                                Net Amount</td><td  class="fontSize alignRight bottomBorder">' . inr_format( $row ['net_salary']) . '</td></tr>';
		
		$retirementAllow = "";
		$retirementDedu = "";
		
		if (is_array ( $allRetireNameId )) {
			$html .= '<tr><td class="fontSize alignLeft bottomBorder" colspan="5">
                               		<i class="fa fa-minus-square pull-left" aria-hidden="true"></i> Retirement Benefits</td></tr>';
			
			foreach ( $allRetireNameId as $retieKey => $retirevalue ) {
				list ( $retireName, $retireId ) = explode ( ",", $retirevalue );
				$html .= '<tr class="retirementAllow"><td>' . $retireName . '</td><td class="alignRight" colspan="5">' . inr_format( $row [$retireId]) . '</td></tr>';
			}
		}
		
		if (is_array ( $decuRetireNameId )) {
			$html .= '<tr><td class="fontSize alignLeft bottomBorder topBorder" colspan="5">
                                  			<i class="fa fa-minus-square pull-left" aria-hidden="true"></i> Retirement Deductions</td></tr>';
			
			foreach ( $decuRetireNameId as $retiedKey => $retiredvalue ) {
				list ( $deduRetiredName, $deduretiredId ) = explode ( ",", $retiredvalue );
				$html .= '<tr><td>' . $deduRetiredName . '</td><td class="alignRight" colspan="5">' . inr_format( $row [$deduretiredId]) . '</td></tr>';
			}
		}
		
		$html .= '<tr><td class="fontSize alignRight bottomBorder topBorder" colspan="3">
                                Net Amount Payable</td><td  class="fontSize alignRight topBorder bottomBorder">' . inr_format(ROUND ( $row ['Netpayable'] )) . '</td></tr>
                                <tr><td class="topBorder">
                                Amount in words</td><td colspan="4" class="topBorder">' . ucfirst ( Session::newInstance ()->convert_number_to_words ( ROUND ( $row ['Netpayable'] ) ) ) . ' only</td></tr></table>
                                </table><br>';
		$html .= $htmlStyle;
		$footer = '<table style="width:100%;"> <tr style="background-color: #1957A2" ><td colspan="5" style="text-align:center;color:#FFF"><p>&copy; Powered by  <a style="color:#FFF" href="http://basspris.com"> BASSBRIS </a> - Pay your wages in a mintue <p></td></tr></table>';
		
		$mpdf->setHTMLHeader ( $header );
		//$mpdf->setHTMLFooter ( $footer );
		$mpdf->WriteHTML ( $html );
		$mpdf->Output ( $row ['employee_name'] . str_replace ( " ", "", $month_name ) . '.pdf', D );
		exit ();
	}
}

?>