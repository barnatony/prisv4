<?php
/*
 * ----------------------------------------------------------
 * Filename : employee.class.php
 * Author : Rajasundari
 * Database : Employee wrkdetails,salary details
 * Oper : promotion Inc Actions
 * ----------------------------------------------------------
 */
require_once ( LIBRARY_PATH. "/deviceApi.class.php"); // Include the File
class Employee {
	/* Member variables */
	var $updated_by;
	var $allowColumns;
	var $miscallowCol;
	var $isDeduction;
	var $startDate;
	var $conn;
	
	function __construct() {
		//$this->conn = $conn;
		$conns = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $_SESSION ['cmpDtSrc']);
		date_default_timezone_set(DEFAULT_TIMEZONE);
		$stmt = "SELECT salary_days,attendance_period_sdate attendance_dt FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'";
		$result = mysqli_query ( $conns, $stmt );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($conns));
		$this->attendance_start_date=$row['attendance_dt'];
		if($row['attendance_dt'] !=1){
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo']-1)."-".$row['attendance_dt'];
			$this->startDate = date("Y-m-d", strtotime($this->startDate));
			$this->endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($row['attendance_dt']-1);
		}else{
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
			$this->endDate = date('Y-m-t',strtotime($this->startDate));
		}
	}
	function __destruct() {
		//mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* For Promotion */
	function promote($actionId, $promotionFor, $employeeIds, $peffectsFrom, $proYesNo, $promotedTo, $incYesNo, $incAmount) {
		$stmt = mysqli_query ( $this->conn, "CALL PROMOTE_INC('" . $_SESSION ['current_payroll_month'] . "','$actionId','$promotionFor','$employeeIds',STR_TO_DATE('$peffectsFrom','%d/%m/%Y'),$proYesNo,'$promotedTo',$incYesNo,'$incAmount','$this->updated_by','C',0,'" . $_SESSION ['financialYear'] . "',$this->startDate,@affected,@allColumns)" );
		$result = ($stmt) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Promotion Edit */
	function promotionEdit($promotionFor, $employeeIds, $peffectsFrom, $proYesNo, $promotedTo, $incAmount, $promotionId, $oldpeffectsFrom) {
		$stmt = mysqli_query ( $this->conn, "CALL PROMOTE_INC('" . $_SESSION ['current_payroll_month'] . "','$promotionId','$promotionFor','$employeeIds',STR_TO_DATE('$peffectsFrom','%d/%m/%Y'),$proYesNo,'$promotedTo',1,'$incAmount','$this->updated_by','U',DATE_SUB(STR_TO_DATE('$oldpeffectsFrom','%d/%m/%Y'),INTERVAL 1 DAY),'" . $_SESSION ['financialYear'] . "',$this->startDate,@affected,@allColumns)" );
		$result = ($stmt) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* For Increment */
	function increment($actionId, $promotionFor, $employeeIds, $peffectsFrom, $proYesNo, $promotedTo, $incYesNo, $incAmount) {
		$stmt = mysqli_query ( $this->conn, "CALL PROMOTE_INC('" . $_SESSION ['current_payroll_month'] . "','$actionId','$promotionFor','$employeeIds',STR_TO_DATE('$peffectsFrom','%d/%m/%Y'),$proYesNo,'$promotedTo',$incYesNo,'$incAmount','$this->updated_by','C',0,'" . $_SESSION ['financialYear'] . "',$this->startDate,@affected,@allColumns)" );
		$result = ($stmt) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Increment Edit */
	function incrementEdit($promotionFor, $employeeIds, $peffectsFrom, $incAmount, $promotionId, $oldpeffectsFrom) {
		$stmt = mysqli_query ( $this->conn, "CALL PROMOTE_INC('" . $_SESSION ['current_payroll_month'] . "','$promotionId','$promotionFor','$employeeIds',STR_TO_DATE('$peffectsFrom','%d/%m/%Y'),
		0,'NA',1,'$incAmount','$this->updated_by','U',DATE_SUB(STR_TO_DATE('$oldpeffectsFrom','%d/%m/%Y'),INTERVAL 1 DAY),'" . $_SESSION ['financialYear'] . "',$this->startDate,@affected,@allColumns)" );
		$result = ($stmt) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Promotion inc Delete */
	function delete($promotionId, $peffectsFrom) {
		$stmt = mysqli_query ( $this->conn, "CALL PROMOTE_INC('" . $_SESSION ['current_payroll_month'] . "','$promotionId',
				0,0,'0000-00-00',1,0,1,0,'$this->updated_by','D',DATE_SUB('$peffectsFrom',INTERVAL 1 DAY),'" . $_SESSION ['financialYear'] . "',$this->startDate,@affected,@allColumns)" );
		$result = ($stmt) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	// for transfer
	function transfer() {
		if ($this->promotionFor == 'E') {
			//echo "UPDATE employee_work_details SET transfer_id=?, branch_id = ?,branch_effects_from=STR_TO_DATE(?,'%d/%m/%Y'),updated_by = ? WHERE employee_id = ? AND MONTH(branch_effects_from) != MONTH('" . $_SESSION ['current_payroll_month'] . "')";
			$stmt = mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET transfer_id=?, branch_id = ?,branch_effects_from=STR_TO_DATE(?,'%d/%m/%Y'),updated_by = ? WHERE employee_id = ? " );
			mysqli_stmt_bind_param ( $stmt, 'sssss', $this->actionId, $this->promotedTo, $this->peffectsFrom, $this->updated_by, $this->employeeIds );
			$result = mysqli_stmt_execute ( $stmt );
		} else {
			$employeIds = array ();
			$result = mysqli_query ( $this->conn, "SELECT employee_id FROM employee_work_details where branch_id ='$this->branchIds'" );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				$employeIds [] = "'" . $row ['employee_id'] . "'";
			}
			//echo "UPDATE employee_work_details SET transfer_id='$this->actionId',branch_id = '$this->promotedTo',branch_effects_from=STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y') WHERE  employee_id IN (" . implode ( ',', $employeIds ) . ")  AND MONTH(branch_effects_from) != MONTH('" . $_SESSION ['current_payroll_month'] . "')";
			
			$stmt = mysqli_query ( $this->conn, "UPDATE employee_work_details SET transfer_id='$this->actionId',branch_id = '$this->promotedTo',branch_effects_from=STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y') WHERE  employee_id IN (" . implode ( ',', $employeIds ) . ") " );
		}
		$employeeCount = mysqli_affected_rows ( $this->conn );
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO comp_transfers 
(action_id, action_for, affected_ids, action_effects_from, transferred_branch_id, is_teamTrans,employees_affected,performed_by)
VALUES ('$this->actionId', '$this->promotionFor', '$this->employeeIds', STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y'), '$this->promotedTo','$this->is_teamTrans','$employeeCount','$this->updated_by');
" );
		
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	//for Team transfer
	function teamTransfer(){
		if($this->transferedFor == 'E'){
			$team_history = "INSERT INTO emp_team_history (employee_id,team_id,effects_from,effects_upto,team_change_reason)(SELECT employee_id,team_id,team_effects_from,DATE_SUB(STR_TO_DATE('$this->teffectsFrom','%d/%m/%Y'),INTERVAL 1 DAY),team_change_reason FROM employee_work_details WHERE employee_id='$this->employee_Ids')";
			mysqli_query ( $this->conn,$team_history);
			
			$stmt = mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET transfer_id=?, team_id = ?,team_effects_from=STR_TO_DATE(?,'%d/%m/%Y'),employee_reporting_person = ?,updated_by = ? WHERE employee_id = ? " );
			mysqli_stmt_bind_param ( $stmt, 'ssssss', $this->actionId, $this->transferedTo, $this->teffectsFrom, $this->rep_man, $this->updated_by, $this->employee_Ids );
			$result = mysqli_stmt_execute ( $stmt );
		}else {
			$employeIds = array ();
			$result = mysqli_query ( $this->conn, "SELECT employee_id FROM employee_work_details where team_id ='$this->teamIds'" );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				$employeIds [] = "'" . $row ['employee_id'] . "'";
			}
			$team_history = "INSERT INTO emp_team_history (employee_id,team_id,effects_from,effects_upto,team_change_reason)(SELECT employee_id,team_id,team_effects_from,DATE_SUB(STR_TO_DATE('$this->teffectsFrom','%d/%m/%Y'),INTERVAL 1 DAY),team_change_reason FROM employee_work_details WHERE employee_id IN (" . implode ( ',', $employeIds ) . ") )";
			mysqli_query ( $this->conn,$team_history);
		$stmt = mysqli_query ( $this->conn, "UPDATE employee_work_details SET transfer_id='$this->actionId',team_id = '$this->transferedTo',team_effects_from=STR_TO_DATE('$this->teffectsFrom','%d/%m/%Y'), employee_reporting_person='$this->rep_man' WHERE  employee_id IN (" . implode ( ',', $employeIds ) . ") " );
		}
		
		$employeeCount = mysqli_affected_rows ( $this->conn );
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO comp_transfers
				(action_id, action_for, affected_ids,action_effects_from, transferred_branch_id, is_teamTrans, employees_affected,performed_by)
				VALUES ('$this->actionId', '$this->transferedFor', '$this->employee_Ids', STR_TO_DATE('$this->teffectsFrom','%d/%m/%Y'), '$this->transferedTo','$this->is_teamTrans','$employeeCount','$this->updated_by');
				" );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
		}
		

	
	/* TransferEdit */
	function transferEdit($promotionFor, $employeeIds, $peffectsFrom, $promotionId, $oldpeffectsFrom, $promotedTo) {
		if ($promotionFor == 'E') {
			$stmt = mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET  branch_id = ?,branch_effects_from=STR_TO_DATE(?,'%d/%m/%Y'),updated_by = ? WHERE employee_id = ? AND transfer_id=?" );
			mysqli_stmt_bind_param ( $stmt, 'sssss', $promotedTo, $peffectsFrom, $this->updated_by, $this->employeeIds, $promotionId );
			$result = mysqli_stmt_execute ( $stmt );
		} else {
			$stmt = mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET  branch_id = ?,branch_effects_from=STR_TO_DATE(?,'%d/%m/%Y'),updated_by = ? WHERE transfer_id=?" );
			mysqli_stmt_bind_param ( $stmt, 'ssss', $promotedTo, $peffectsFrom, $this->updated_by, $promotionId );
			$result = mysqli_stmt_execute ( $stmt );
		}
		
		$result = mysqli_query ( $this->conn, "UPDATE comp_transfers  
				SET action_effects_from=STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y'),transferred_branch_id = '$promotedTo',
				performed_by='$this->updated_by' WHERE action_id='$promotionId'" );
		
		return $result;
	}
	
	/* Transfer Delete */
	function transferdelete($promotionFor, $promotionId, $peffectsFrom, $empId) {
		if ($promotionFor == 'E') {
			$result = mysqli_query ( $this->conn, "SELECT transfer_id,branch_id,effects_from FROM emp_branch_history 
	    WHERE employee_id='$empId' AND effects_upto=DATE_SUB('$peffectsFrom',INTERVAL 1 DAY)" );
			$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
			$result = mysqli_query ( $this->conn, "UPDATE employee_work_details SET branch_id='" . $row ['branch_id'] . "',updated_by='$this->updated_by'
	 	,transfer_id='" . $row ['transfer_id'] . "',branch_effects_from='" . $row ['effects_from'] . "' WHERE employee_id='$empId' " );
			$result = mysqli_query ( $this->conn, "DELETE FROM emp_branch_history  WHERE employee_id='$empId' AND effects_upto=DATE_SUB('$peffectsFrom',INTERVAL 1 DAY)" );
			$result = mysqli_query ( $this->conn, "DELETE FROM emp_branch_history  WHERE employee_id='$empId' AND effects_from='$peffectsFrom'" );
		} else {
			$result = mysqli_query ( $this->conn, "SELECT employee_id,transfer_id,branch_id,effects_from FROM emp_branch_history
			WHERE effects_upto=DATE_SUB('$peffectsFrom',INTERVAL 1 DAY)" );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				$stmt = mysqli_query ( $this->conn, "UPDATE employee_work_details SET branch_id='" . $row ['branch_id'] . "',updated_by='$this->updated_by'
		    ,transfer_id='" . $row ['transfer_id'] . "',branch_effects_from='" . $row ['effects_from'] . "' WHERE employee_id='" . $row ['employee_id'] . "' " );
				$stmt1 = mysqli_query ( $this->conn, "DELETE FROM emp_branch_history  WHERE employee_id='" . $row ['employee_id'] . "' AND effects_upto=DATE_SUB('$peffectsFrom',INTERVAL 1 DAY)" );
			}
		}
		$result = mysqli_query ( $this->conn, "DELETE FROM comp_transfers  WHERE action_id='$promotionId' AND is_teamTrans=0" );
		return $result;
	}
	
	/*function teamTransferdelete($promotionFor, $promotionId, $peffectsFrom, $empId) {
		if ($promotionFor == 'E') {
			$result = mysqli_query ( $this->conn, "SELECT transfer_id,branch_id,effects_from FROM emp_branch_history
					WHERE employee_id='$empId' AND is_teamTrans=1 ORDER BY id DESC LIMIT 1" );
			$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
			$result = mysqli_query ( $this->conn, "UPDATE employee_work_details SET branch_id='" . $row ['branch_id'] . "',updated_by='$this->updated_by'
					,transfer_id='" . $row ['transfer_id'] . "',branch_effects_from='" . $row ['effects_from'] . "' WHERE employee_id='$empId' " );
			$result = mysqli_query ( $this->conn, "DELETE FROM emp_branch_history  WHERE employee_id='$empId' AND is_teamTrans=1 AND effects_upto=DATE_SUB('$peffectsFrom',INTERVAL 1 DAY)" );
			$result = mysqli_query ( $this->conn, "DELETE FROM emp_branch_history  WHERE employee_id='$empId' AND is_teamTrans=1 AND  effects_from='$peffectsFrom'" );
		} else {
			$result = mysqli_query ( $this->conn, "SELECT transfer_id,branch_id,effects_from FROM emp_branch_history
					WHERE employee_id='$empId' AND is_teamTrans=1 ORDER BY id DESC LIMIT 1" );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				$stmt = mysqli_query ( $this->conn, "UPDATE employee_work_details SET branch_id='" . $row ['branch_id'] . "',updated_by='$this->updated_by'
						,transfer_id='" . $row ['transfer_id'] . "',branch_effects_from='" . $row ['effects_from'] . "' WHERE employee_id='" . $row ['employee_id'] . "' " );
				$stmt1 = mysqli_query ( $this->conn, "DELETE FROM emp_branch_history  WHERE employee_id='" . $row ['employee_id'] . "' AND is_teamTrans=1 AND effects_upto=DATE_SUB('$peffectsFrom',INTERVAL 1 DAY)" );
			}
		}
		$result = mysqli_query ( $this->conn, "DELETE FROM comp_transfers  WHERE action_id='$promotionId' AND is_teamTrans=1" );
		return $result;
	}*/
	
	// call from Promotion Page FOr Employee Name With their designation NAme
	function selectEmpdesig() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT  CONCAT(employee_name,' ',employee_lastname) employee_name, w.employee_id,d.designation_name,w.design_effects_from
												FROM employee_work_details w
												INNER JOIN company_designations d
												ON d.designation_id=w.designation_id
				                                INNER JOIN employee_personal_details p 
			                                    ON w.employee_id = p.employee_id 
			                                    INNER JOIN employee_salary_details s 
			                                    ON w.employee_id = s.employee_id
			                                    INNER JOIN company_departments dep
			                                    ON w.department_id = dep.department_id
			                                    INNER JOIN company_branch br
			                                    ON w.branch_id = br.branch_id
												WHERE w.enabled=1 AND  DATE_FORMAT(w.design_effects_from,'%m%Y') != DATE_FORMAT('".$_SESSION['current_payroll_month']."','%m%Y');" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	/* Select Employee */
	// call from Promotion Page FOr Employee Name
	function select($flag) {
		$a_json = array ();
		$condition=($flag!=1)?"WHERE w.enabled=1 AND DATE_FORMAT(s.effects_from,'%m%Y') != DATE_FORMAT('".$_SESSION['current_payroll_month']."','%m%Y');":'WHERE w.enabled=1';
		$cloumSelect=($flag!=1)?",d.designation_name,s.effects_from ":'';
		$query = "SELECT  CONCAT(employee_name,' ',employee_lastname) employee_name, w.employee_id,w.enabled $cloumSelect
												FROM employee_work_details w
												INNER JOIN company_designations d
												ON d.designation_id=w.designation_id 
												INNER JOIN employee_salary_details s
												ON s.employee_id = w.employee_id
				                                INNER JOIN employee_personal_details p 
			                                    ON w.employee_id = p.employee_id 
			                                    INNER JOIN company_departments dep
			                                    ON w.department_id = dep.department_id
			                                    INNER JOIN company_branch br
			                                    ON w.branch_id = br.branch_id
				                                $condition ";
		$result = mysqli_query ( $this->conn,$query);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getLastInsertedEmployees(){
		$a_json=array();
		$query="SELECT CONCAT(employee_name,' [',employee_id,']') emp_id
				FROM employee_work_details 
				WHERE employee_id IN (
				SELECT CONCAT(SUBSTRING(employee_id,1,(mypos-1)),MAX(SUBSTRING_INDEX(employee_id,SUBSTRING(employee_id,1,(mypos-1)),-1))) ids
				FROM (
					SELECT employee_id,employee_name,CAST(employee_id AS UNSIGNED) cast_val,
					LEAST ( IF(LOCATE('0',employee_id)>0,LOCATE('0',employee_id),999),
					IF(LOCATE('1',employee_id)>0,LOCATE('1',employee_id),999),
					IF(LOCATE('2',employee_id)>0,LOCATE('2',employee_id),999),
					IF(LOCATE('3',employee_id)>0,LOCATE('3',employee_id),999),
					IF(LOCATE('4',employee_id)>0,LOCATE('4',employee_id),999),
					IF(LOCATE('5',employee_id)>0,LOCATE('5',employee_id),999),
					IF(LOCATE('6',employee_id)>0,LOCATE('6',employee_id),999),
					IF(LOCATE('7',employee_id)>0,LOCATE('7',employee_id),999),
					IF(LOCATE('8',employee_id)>0,LOCATE('8',employee_id),999),
					IF(LOCATE('9',employee_id)>0,LOCATE('9',employee_id),999)
					) as mypos FROM employee_work_details
					 ORDER BY employee_id DESC
                ) a GROUP BY SUBSTRING(employee_id,1,(mypos-1)));";
		
		
		$result=mysqli_query($this->conn, $query) or die(mysqli_error($this->conn));
		while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			array_push($a_json,$row);
		}
		return $a_json;
		
	}
	/* Select Employee with their notice period */
	function selectColmEmp($columName, $join) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT $columName $join" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getEmployeeTreeData() {
		$result = mysqli_query ( $this->conn, "SELECT e.employee_id,CONCAT(e.employee_name,' ',e.employee_lastname) AS employee_name ,r.employee_id as reporting_to,r.employee_name as reporting_person,d.designation_name,de.department_name,d.designation_hierarchy
                                    FROM employee_work_details e
                                    INNER JOIN company_designations d
                                    ON e.designation_id = d.designation_id
			                        INNER JOIN company_departments de
                                    ON e.department_id = de.department_id
                                    LEFT JOIN employee_work_details r
                                    ON e.employee_reporting_person = r.employee_id
                                    ORDER BY d.designation_hierarchy,e.employee_reporting_person" );
		$a_json = array ();
		
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$a_json [] = $row;
		}
		
		// $json_obj = json_encode($a_json);
		$a_json = json_encode ( $a_json );
		$a_json = json_decode ( $a_json );
		$tot = array ();
		function constructTree($parent, $employees) {
			$ch = array (
					"title" => $parent->employee_name,
					"key" => $parent->employee_id,
					"folder" => FALSE,
					"expanded" => FALSE,
					"data" => array (
							'icon' => "employee.png",
							'reportingPerson' => $parent->reporting_person,
							'designation' => $parent->designation_name,
							'department' => $parent->department_name,
							'employee_id' => $parent->employee_id,
							'employee_name' => $parent->employee_name,
							'reportId' => $parent->reporting_to 
					),
					"children" => array () 
			);
			foreach ( $employees as $key => $employee ) {
				if ($employee->employee_id == $parent->employee_id) {
					unset ( $employees [$key] ); // unset($employees[$key]);
				} else {
					if ($employee->reporting_to == $parent->employee_id) {
						
						$ch ['data'] ['icon'] = "manager.png";
						$ch ['children'] [] = constructTree ( $employee, $employees );
						$ch ['folder'] = TRUE;
					}
				}
			}
			if ($parent->reporting_to == "") {
				$ch ['expanded'] = TRUE;
			}
			return $ch;
		}
		
		$output = array ();
		$output [] = constructTree ( $a_json [0], $a_json );
		
		return json_encode ( $output );
	}
	function createEmployeeRestriction() {
		$result = mysqli_query ( $this->conn, "SELECT  IF(COUNT(*)='0','BRANCH','') empRestriction FROM company_branch WHERE enabled=1
											UNION  ALL
											SELECT IF(COUNT(*)='0','DEPARTMENT','')FROM company_departments WHERE enabled=1
											UNION  ALL
											SELECT IF(COUNT(*)='0','DESIGNATION','') FROM company_designations WHERE enabled=1
											UNION  ALL
											SELECT IF(COUNT(*)='0','JOBSTATUS','') FROM company_job_statuses WHERE enabled=1
											UNION  ALL
											SELECT IF(COUNT(*)='0','PAYMENTMODE','') FROM company_payment_modes WHERE enabled=1
											" );
		$a_json = array ();
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			if ($row ['empRestriction'] != NULL) {
				
				array_push ( $a_json, $row ['empRestriction'] );
			}
		}
		return $a_json;
	}
	function getAllEmployeeCount() {
		$json = array ();
		$row = array ();
		$query = "SELECT count(*)
		FROM employee_work_details  w
		INNER JOIN employee_personal_details p
		ON w.employee_id = p.employee_id
		LEFT JOIN company_designations des
		ON w.designation_id = des.designation_id
		LEFT JOIN company_departments d
		ON w.department_id=d.department_id
		LEFT JOIN company_branch b
		ON w.branch_id=b.branch_id
		WHERE  w.enabled =1";
		$stmt = mysqli_prepare ( $this->conn, $query );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_stmt_error ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $employeeId );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['employeeId'] = $employeeId;
			array_push ( $json, $row );
		}
		return $json;
	}
	function wipeEmployee($employeeId) {

		$deviceApi = new deviceApi($this->conn); //pass the connection inside
		$results = $deviceApi->deleteonDevice ($employeeId);
		
		$query = "DELETE FROM emp_leave_account WHERE employee_id='{$employeeId}';DELETE FROM emp_montly_attendance WHERE employee_id='$employeeId';
		DELETE FROM  employee_it_declaration WHERE employee_id='{$employeeId}';DELETE FROM  employee_income_tax WHERE employee_id='{$employeeId}';
		DELETE FROM  asset_requests WHERE employee_id='{$employeeId}';
		DELETE FROM  claims WHERE employee_id='{$employeeId}';DELETE FROM  claim_mapping WHERE applicable_id='{$employeeId}';
		DELETE FROM  notifications WHERE sender_id='{$employeeId}';
		DELETE FROM  message WHERE creator_id='{$employeeId}';DELETE FROM message_recipient WHERE recipient_id='{$employeeId}';
		DELETE FROM  emp_notice_period WHERE employee_id='{$employeeId}';
		DELETE FROM  comp_promotions_increments WHERE affected_ids='{$employeeId}';
		DELETE FROM  comp_transfers WHERE affected_ids='{$employeeId}';
		DELETE FROM  emp_work_history WHERE employee_id='{$employeeId}';
		DELETE FROM  employee_salary_details_history WHERE employee_id='{$employeeId}';
		DELETE FROM  employee_salary_details_shadow WHERE employee_id='{$employeeId}';
		DELETE FROM  emp_branch_history WHERE employee_id='{$employeeId}';
		DELETE FROM  emp_designation_history WHERE employee_id='{$employeeId}';
		DELETE FROM  arrears WHERE employee_id='{$employeeId}';
		DELETE FROM  payroll_preview_temp WHERE employee_id='{$employeeId}';
		DELETE FROM  employee_personal_details WHERE employee_id='{$employeeId}';
		DELETE FROM  employee_salary_details WHERE employee_id='{$employeeId}';
		DELETE FROM  employee_work_details WHERE employee_id='{$employeeId}';
		DELETE FROM  device_users WHERE employee_id='{$employeeId}';";
				
		if ($result = mysqli_multi_query ( $this->conn, $query )) {
			do {
				if ($result = mysqli_store_result ( $this->conn )) {
					mysqli_free_result ( $result );
				}
			} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
		}
		$filename = dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employeeId;
		self::recursiveRemoveDirectory ( $filename );
		return true;
	}
	function recursiveRemoveDirectory($directory) {
		foreach ( glob ( "{$directory}/*" ) as $file ) {
			if (is_dir ( $file )) {
				self::recursiveRemoveDirectory ( $file );
			} else {
				unlink ( $file );
			}
		}
		rmdir ( $directory );
	}
	function salaryDetails($employee_id) {
		$a_json = array ();
		$query = "SELECT s.pf_limit,if(salary_type ='ctc',1,0)salary_type,s.ctc_fixed_component ctc,s.ctc Subctc,s.employee_id,
		w.employee_name,w.employee_lastname,s.slab_id,slab.slab_type,slab.slab_name,
		s.employee_salary_amount gross,$this->allowColumns $this->miscallowCol DATE_FORMAT(s.effects_from,'%d/%m/%Y') effects_from,isAnnual 
		FROM employee_salary_details s
		INNER JOIN employee_work_details w
		ON s.employee_id = w.employee_id
		LEFT JOIN company_allowance_slabs slab
		ON s.slab_id = slab.slab_id
	    WHERE s.employee_id='$employee_id'";
	
		$result = mysqli_query ( $this->conn, $query );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		array_push ( $a_json, $row );
		array_push ( $a_json, self::getCTCbreakUp ( $row ['gross'], $row ['ctc'], $row ['salary_type'],$row ['isAnnual'], $row ['pf_limit'], (($row ['slab_id'] == 'Nil') ? 'Nil' : $row ['slab_id']), '' ) );
		
		$perqs_array = array();
		$perquisites = new perquisites ($this->conn);
		$perqs_array = $perquisites->getAvailablePerqs($employee_id);
		array_push ( $a_json, $perqs_array );
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		
		
	}
	function workDetails($employee_id) {
		$a_json = array ();
		$query = "SELECT cs.shift_name,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id) shift_id,GROUP_CONCAT(wh.company_name SEPARATOR ',') company_name,
        IF(du.status IS NULL,IF((de.branch_id=w.branch_id)AND du.status IS NULL,-2,-3),du.status) status,
        GROUP_CONCAT(wh.`from` SEPARATOR ',') `from`,
        GROUP_CONCAT(wh.`to` SEPARATOR ',') `to`,
        GROUP_CONCAT(wh.`designation` SEPARATOR ',') `designation`,
		GROUP_CONCAT(wh.`prev_reporting_manager` SEPARATOR ',') `prev_reporting_manager`,
		GROUP_CONCAT(wh.`location` SEPARATOR ',') `location`,
		GROUP_CONCAT(wh.`contact_email` SEPARATOR ',') `contact_email`,
        GROUP_CONCAT(wh.`ctc` SEPARATOR ',') `ctc`,(select id from payroll where employee_id='" . $employee_id . "' limit 0
        ,1
        ) as id,w.employee_id,w.employee_name,w.employee_lastname,w.employee_reporting_person,CONCAT (man.employee_name,' ',man.employee_lastname) as reporting_manager,
        DATE_FORMAT(w.employee_confirmation_date,'%d/%m/%Y') employee_confirmation_date,w.employee_probation_period,w.notice_period, br.branch_name,w.employee_emp_pf_no ,w.employee_emp_uan_no ,
        w.employee_emp_esi_no ,slab.slab_type,w.enabled, s.slab_id ,w.off_ltr_proof, slab.slab_name,pm.payment_mode_name,ct.team_name,ct.team_id,
        CONCAT(DATE_FORMAT(w.employee_doj,'%d/%m/%Y'),' (',IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Years'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Year'))),')') employee_doj,
        w.branch_id,w.designation_id,w.shift_id,w.weekend_id,w.off_ltr_issue_dt,w.confirm_ltr_issue_dt,w.contract_ltr_issue_dt,js.status_name, w.department_id,des.designation_name,dep.department_name,w.payment_mode_id ,w.status_id,s.pf_limit,
        DATE_FORMAT(man.design_effects_from,'%d/%m/%Y') design_effects_from,DATE_FORMAT( man.branch_effects_from,'%d/%m/%Y') branch_effects_from,DATE_FORMAT( man.depart_effects_from ,'%d/%m/%Y') depart_effects_from
        FROM employee_work_details w
        INNER JOIN employee_salary_details s
        ON s.employee_id = w.employee_id
        LEFT JOIN company_designations des
        ON w.designation_id = des.designation_id
        LEFT JOIN company_departments dep
        ON w.department_id = dep.department_id
        LEFT JOIN company_branch br
        ON w.branch_id = br.branch_id
        LEFT JOIN company_allowance_slabs slab
        ON s.slab_id = slab.slab_id
        LEFT JOIN employee_work_details man ON
        w.employee_reporting_person = man.employee_id
        LEFT JOIN company_payment_modes pm
        ON w.payment_mode_id = pm.payment_mode_id
        LEFT JOIN company_job_statuses js
        ON w.status_id = js.status_id
        LEFT JOIN company_shifts cs
        ON IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id) = cs.shift_id
        LEFT JOIN device_users du
        ON w.employee_id =du.employee_id
        LEFT JOIN devices de
        ON de.branch_id=w.branch_id
        LEFT JOIN company_team ct
        ON w.team_id = ct.team_id
        LEFT JOIN emp_work_history wh on w.employee_id=wh.employee_id
        WHERE w.employee_id = '$employee_id'";
	
		$result = mysqli_query ( $this->conn, $query );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function getCTCbreakup($gross, $ctc, $isCTC, $isAnnual, $pfLimit, $slabId, $noSlabData) {
		// struct.display_name
	
		if($isAnnual == 1){
			if($isCTC=='1')
				$ctc = $ctc/12;
		}
		if($isCTC != '1'){
			$gross = ($isAnnual == 1) ? $gross / 12 : $gross;
		}
		if($slabId !="Nil" && $this->isDeduction==0){
			$stmt = "SELECT slabs.*
			FROM company_allowance_slabs slabs
			WHERE slabs.slab_id = '$slabId' AND slabs.enabled =1";
		}else{
			$stmt ="SELECT struct.display_name,ded.deduction_id,(CASE
			WHEN SUBSTRING(ded.employee_share ,-1) = 'P'
			THEN 'P'
			ELSE 'A'
			END) deduct_type,
			((CASE
			WHEN ded.is_both_contribution = 1 OR ded.is_both_contribution = 0
			THEN SUBSTRING_INDEX(ded.employer_share , '|', 1)
			ELSE 0
			END
			)+(CASE
			WHEN ded.is_admin_charges = 1
			THEN SUBSTRING_INDEX(ded.admin_charges , '|', 1)
			ELSE 0
			END
			)
			) value,SUBSTRING_INDEX(ded.employer_share , '|', 1) employer_charges,
			SUBSTRING_INDEX(ded.admin_charges , '|', 1) admin_charges,
			ded.max_employer_share,
			ded.deduce_in deduce_in,
			ded.cal_exemption exemption_to,
			slabs.*
			FROM company_deductions ded
			LEFT JOIN company_pay_structure struct
			ON ded.deduction_id = struct.pay_structure_id
			LEFT JOIN company_allowance_slabs slabs
			ON slabs.slab_id = '$slabId' AND slabs.enabled =1
			WHERE struct.display_flag = 1;";
		}
	
		$result = mysqli_query ( $this->conn, $stmt)or die(mysqli_error($this->conn));;
		$salaryData = array (
				"allowances" => array (),
				"deductions" => array (),
				"totalDeductions" => 0,
				"gross" => $gross,
				"ctc" => $ctc,
				"difference" => 0
		);
	
		$slabData = array ();
		$ctcGrossPercentage = 100; // 100+ % 100 for goss
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$slabData [] = $row;
		}
		// Finding Allowances
		$result = mysqli_query ( $this->conn, "SELECT pay.pay_structure_id,pay.display_name
                                            FROM company_pay_structure pay
                                            WHERE pay.display_flag = 1
                                            AND pay.type = 'A'" );
			
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$salaryData ["allowances"] [$row ['pay_structure_id']] ["name"] = $row ['pay_structure_id'];
			$salaryData ["allowances"] [$row ['pay_structure_id']] ["label"] = $row ['display_name'];
		}
		
		if (is_array ( $noSlabData ) && $slabId == 'Nil') {
			
			foreach ( $noSlabData as $key => $val ) {
				if($isAnnual == 1){
					
					$salaryData ['allowances'] [$key] ['amount'] = $val/12;
					$salaryData ['allowances'] [$key] ['rate'] = '-|A';
	
				}else{
					$salaryData ['allowances'] [$key] ['amount'] = $val;
					$salaryData ['allowances'] [$key] ['rate'] = '-|A';
				}
			}
	
		} else {
			if ($isCTC == 1 && $slabData [0] ['slab_type'] == 'gross') {
				if($this->isDeduction==1)
				{
					$salaryData = self::findDeduction ( $salaryData, $slabData, $isCTC, $pfLimit );
				}else{
					$salaryData ["gross"] = $salaryData ["ctc"];
				}
			}
			$salaryData = self::findAllowance ( $salaryData, $slabData );
		}
	
		if($this->isDeduction==1)
		{
			$salaryData = self::findDeduction ( $salaryData, $slabData, $isCTC, $pfLimit );
		}else{
			$salaryData ["ctc"] = $salaryData ["gross"] ;
		}
	
		$salaryData ["gross"] = round ( $salaryData ["gross"], 2 );
		$salaryData ["ctc"] = round ( $salaryData ["ctc"], 2 );
		$salaryData ["isCtc"] = $isCTC;
	
		if ($salaryData ["ctc"] != $ctc) {
			$salaryData ["difference"] = $ctc - $salaryData ["ctc"];
		}
		return $salaryData;
	}
	
	private static function findAllowance($salaryData, $slabData) {
		$tally_gross = 0;
		$allow_array = array ();
		
		if ($slabData [0] ['slab_type'] == 'basic') {
			// based on basic
			$main_val = $salaryData ["basic"];
			$tally_gross = $main_val;
		} else if ($slabData [0] ['slab_type'] == 'gross') {
			// based on gross
			$main_val = $salaryData ["gross"];
		} else if ($slabData [0] ["slab_type"] == "") {
			// no slab
		}
		foreach ( $salaryData ["allowances"] as $allowance ) {
			if ((substr ( $slabData [0] [$allowance ['name']], - 1 ) == 'P')) {
				// percentage_part
				$allow = $main_val * (substr ( $slabData [0] [$allowance ['name']], 0, - 2 ) / 100);
				$tally_gross += $allow;
			} elseif ((substr ( $slabData [0] [$allowance ['name']], - 1 ) == 'A') && (substr ( $slabData [0] [$allowance ['name']], 0, 1 ) != 'R') && ($slabData [0] [$allowance ['name']] != 'NA')) {
				// amount part
				$allow = substr ( $slabData [0] [$allowance ['name']], 0, - 2 );
				$tally_gross += $allow;
			} elseif ($slabData [0] [$allowance ['name']] == 'NA') {
				$allow = $main_val;
			} else {
				$allow = 0;
			}
			$values = array (
					"label" => $allowance ['label'],
					"name" => $allowance ['name'],
					"rate" => $slabData [0] [$allowance ['name']],
					"amount" => round ( $allow, 2 ) 
			);
			$allow_array [$allowance ['name']] = $values;
		}
		$salaryData ["allowances"] = $allow_array;
		foreach ( $salaryData ["allowances"] as $allowance ) {
			if ((substr ( $slabData [0] [$allowance ["name"]], - 1 ) == 'A') && (substr ( $slabData [0] [$allowance ["name"]], 0, 1 ) == 'R') && ($slabData [0] [$allowance ['name']] != 'NA')) {
				// remaining amount part
				$allow = $salaryData ["gross"] - $tally_gross;
				$tally_gross += $allow;
				$salaryData ['allowances'] [$allowance ["name"]] ['amount'] = round ( $allow, 2 );
			}
		}
		if ($slabData [0] ['slab_type'] == 'basic') {
			$salaryData ["gross"] = $tally_gross;
		}
		return $salaryData;
	}
	private static function findDeduction($salaryData, $dedSlabData, $isCtc, $pfLimit) {
		// salaryData - allowances,deductions,gross || dedSlabData deduction,slab properties
		$salaryData ["totalDeductions"] = 0;
		$ctcGrossPercentage = 100;
		$total_dedc_amt = 0;
		$ded_amount = 0;
		$ctc_percent_dedc = 0;
		foreach ( $dedSlabData as $dedSlab ) {
			if ($isCtc == 1 && $dedSlab ["slab_type"] == 'gross') {
				$dedc_percnt = 0;
				$employer_contrib = 0;
				$admin_charges = 0;
				$total_dedc_amt = 0;
				$remaining_amt_head = '';
				$esiCheck = 0;
				// get each component values from the deduce in column
				$deduce_ins = explode ( ",", $dedSlab ['deduce_in'] );
				if ($dedSlab ['deduct_type'] == 'P') {
					foreach ( $deduce_ins as $deduce_in ) {
						// every deduce_in
						if ($deduce_in != 'GROSS') {
							if ((substr ( $dedSlab [$deduce_in], - 1 ) == 'P')) {
								// percentage_part
								$dedc_percnt += substr ( $dedSlab [$deduce_in], 0, - 2 );
							} elseif ((substr ( $dedSlab [$deduce_in], - 1 ) == 'A') && (substr ( $dedSlab [$deduce_in], 0, 1 ) != 'R')) {
								// amount part
								$total_dedc_amt += substr ( $dedSlab [$deduce_in], 0, - 2 );
							} elseif ((substr ( $dedSlab [$deduce_in], - 1 ) == 'A') && (substr ( $dedSlab [$deduce_in], 0, 1 ) == 'R')) {
								// remaining amount part
								$remaining_amt_head = substr ( $dedSlab [$deduce_in], 0, - 2 );
							}
						} else {
							$esiCheck = $dedc_percnt = 100;
						}
					}
					$employer_contrib = $dedc_percnt * ($dedSlab ['employer_charges'] / 100);
					$admin_charges = $dedc_percnt * ($dedSlab ['admin_charges'] / 100);
					$ctc_percent_dedc = $dedc_percnt * ($dedSlab ['value'] / 100);
					$ctcGrossPercentage += $ctc_percent_dedc;
				} else {
					$ded_amount = $dedSlab ['value'];
				}
			} else {
				$deduce_ins = explode ( ",", $dedSlab ['deduce_in'] );
				$total_deduce_in_amount = 0;
				$esiCheck = 0;
				$employer_contrib = 0;
				$admin_charges = 0;
				$ded_amount = 0;
				foreach ( $deduce_ins as $deduce_in ) {
					// every deduce_in
					if ($deduce_in != 'GROSS') {
						$total_deduce_in_amount += $salaryData ['allowances'] [$deduce_in] ["amount"];
					} else {
						$total_deduce_in_amount = $esiCheck = $salaryData ["gross"];
					}
				}
				if ($total_deduce_in_amount < $dedSlab ["exemption_to"] || $dedSlab ["exemption_to"] == 0) {
					if ($dedSlab ["deduct_type"] == 'P') {
						// employer charges
						$employer_contrib = $total_deduce_in_amount * ($dedSlab ["employer_charges"] / 100);
						// admin charges
						$admin_charges = $total_deduce_in_amount * ($dedSlab ["admin_charges"] / 100);
						if ($pfLimit == 0 && $dedSlab ['deduction_id'] == 'c_epf') {
							// if pf and pf limit is "0" then do not compare max share
						}elseif($pfLimit == -1 && $dedSlab ['deduction_id'] == 'c_epf'){
							$employer_contrib = $admin_charges = 0;
						}else {
							$max_share = $dedSlab ["max_employer_share"] == 0 ? 99999999 : $dedSlab ["max_employer_share"];
							if ($employer_contrib > $max_share) {
								$employer_contrib = $max_share;
								$admin_charges = ($employer_contrib * (($dedSlab ["employer_charges"] == 0) ? 0 : (100 / $dedSlab ["employer_charges"]))) * ($dedSlab ['admin_charges'] / 100);
							}
						}
						// total charges
						$ded_amount = $employer_contrib + $admin_charges;
					} else {
						$ded_amount = $dedSlab ["value"];
					}
				}
			}
			$total_dedc_amt += $ded_amount;
			if($esiCheck < $dedSlab ["exemption_to"] || $dedSlab ["exemption_to"] == 0){
				$salaryData ["deductions"] [$dedSlab ['deduction_id']] = array (
						"label" => $dedSlab ['display_name'],
						"name" => $dedSlab ['deduction_id'],
						"deduce_in" => $dedSlab ["deduce_in"],
						"rate" => $dedSlab ["value"],
						"CTCrate" => $ctc_percent_dedc,
						"amount" => round ( $ded_amount, 2 ),
						"employer_c" => $employer_contrib,
						"admin_c" => $admin_charges 
				);
			}
		}
		
		if ($isCtc == 1 && $dedSlabData [0] ['slab_type'] == 'gross') {
			$salaryData ["totalDeductions"] = 0;
			foreach ( $dedSlabData as $deduction ) {
					$employer_contrib = (($salaryData ['deductions'] [$deduction ['deduction_id']] ['employer_c'] / $ctcGrossPercentage) * $salaryData ['ctc']);
				$total_deduce_in_amount = $employer_contrib * (($deduction ["employer_charges"] == 0) ? 0 : (100 / $deduction ["employer_charges"]));
				
				if ($total_deduce_in_amount < $deduction ["exemption_to"] || $deduction ["exemption_to"] == 0) {
					$admin_charges = ($salaryData ['deductions'] [$deduction ['deduction_id']] ['admin_c'] / $ctcGrossPercentage) * $salaryData ['ctc'];
					if ($pfLimit == 0 && $deduction ['deduction_id'] == 'c_epf') {
						// if pf and pf limit is "0" then do not compare max share
					} elseif($pfLimit == -1 && $deduction ['deduction_id'] == 'c_epf'){
						$employer_contrib = $admin_charges = 0;
					}else {
					  $max_share = $deduction ["max_employer_share"] == 0 ? 99999999 : $deduction ["max_employer_share"];
						if ($employer_contrib > $max_share) {
							$employer_contrib = $max_share;
							$admin_charges = ($deduction ["max_employer_share"] / ($deduction ["employer_charges"]) * 100) * ($deduction ['admin_charges'] / 100);
						}
					}
				} else {
					$employer_contrib = 0;
					$admin_charges = 0;
				}
				$ded_amount = $employer_contrib + $admin_charges;
				$salaryData ['deductions'] [$deduction ['deduction_id']] ['amount'] = round ( $ded_amount, 2 );
				
				$salaryData ["totalDeductions"] += $salaryData ['deductions'] [$deduction ['deduction_id']] ['amount'];
			}
			$salaryData ["gross"] = $salaryData ["ctc"] - $salaryData ["totalDeductions"];
		} else {
			$salaryData ["totalDeductions"] += $total_dedc_amt;
			$salaryData ["ctc"] = $salaryData ["gross"] + $salaryData ['totalDeductions'];
		}
		return $salaryData;
	}
	
	
	function incrementByEmployeeId($employee_id, $increment_id, $salaryParams, $isPromotion) {
		// write in salary details
			$allowanceColumnsValues = "";
			foreach ( $salaryParams as $key => $val ) {
				$allowanceColumnsValues .= "," . $key . "=" . $val;
			}
		   $date = DateTime::createFromFormat ( 'd/m/Y', $this->peffectsFrom );
		   $this->ctc_fixed_component = $this->ctc_fixed_component==''?0:$this->ctc_fixed_component;
		   $this->ctc = $this->ctc==''?0:$this->ctc;
		   $inc_query = "UPDATE employee_salary_details SET ".$this->miscAllowValue ."slab_id='" . $this->salaryName . "',increment_id='$increment_id',ctc=$this->ctc,
					salary_type='$this->salary_type',ctc_fixed_component=$this->ctc_fixed_component,
					pf_limit='$this->pfLimit',employee_salary_amount='$this->employee_salary_amount'
					$allowanceColumnsValues ,updated_by='" . $_SESSION ['login_id'] . "',
					effects_from=STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y') WHERE employee_id='$employee_id';";
		   $stmt = mysqli_query ( $this->conn,  $inc_query);
		    
		   if ($date->format ( 'Y-m-d' ) < $this->startDate) {
		   			$stmt = mysqli_query ( $this->conn, "CALL CALC_ARREARS('$employee_id','" . $_SESSION ['current_payroll_month'] . "',
			'" . $date->format ( 'Y-m-d' ) . "','" . $_SESSION ['financialYear'] . "')" );
						
					}

	if ($isPromotion=='NA') {
			$stmt = mysqli_multi_query ( $this->conn, "INSERT INTO comp_promotions_increments
				(action_id, action_for, affected_ids, action_effects_from, promoted_desig_id,
				incremented_amount, employees_affected,  performed_by)
				VALUES ('$increment_id', 'E','$employee_id', STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y'),
				'NA','$this->incAmount', 1,'" . $_SESSION ['login_id'] . "')" );
		}
	return true;
	}
	function promoteByEmployeeId($employee_id, $promotion_id, $newDesignationId, $isIncrement, $salaryParams) {
		// write in work details
		if ($isIncrement == 1) {
			self::incrementByEmployeeId ( $employee_id, $promotion_id, $salaryParams, 1);
		} else if ($isIncrement == 0) {
			$stmt = mysqli_query ( $this->conn, "UPDATE employee_salary_details SET increment_id='$promotion_id',
				effects_from=STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y') WHERE employee_id='$employee_id' " );
		}
		$stmt = mysqli_multi_query ( $this->conn, "UPDATE  employee_work_details SET promotion_id='$promotion_id',
			design_effects_from=STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y'),designation_id='$newDesignationId'
			WHERE employee_id='$employee_id';INSERT INTO comp_promotions_increments
			(action_id, action_for, affected_ids, action_effects_from, promoted_desig_id,
			incremented_amount, employees_affected,  performed_by)
			VALUES ('$promotion_id', 'E','$employee_id', STR_TO_DATE('$this->peffectsFrom','%d/%m/%Y'),
			'$newDesignationId','$this->incAmount', 1,'" . $_SESSION ['login_id'] . "')" );
		return true;
	}
	
	function getEmployeeView($startLimit,$intervalLimit,$searchKey,$letterKey,$inActive,$groupKey=null,$groupvalues1=null,$is_surrogateUsers) {
		// write in work details
		$a_json=array();
		$enabled_cond = ($inActive == 0)? "w.enabled IN (1,$is_surrogateUsers)":"w.enabled IN (0,1)";
	    $groupvalues_1 = str_replace(',', '","', $groupvalues1);
	    $groupvalues ='"'.$groupvalues_1.'"';
	    if($startLimit==0 && $intervalLimit==0){//for shift allocation screen
	    	$limtCondition = '';
	    }else{
	    	$limtCondition ="ASC LIMIT ". $startLimit. ",".$intervalLimit;
	    }
		$groupcondition = "";
		if(isset($groupKey) && isset($groupvalues) && $groupvalues != ""  ){
			//$limtCondition="";
			if($groupKey=='D')
				$groupcondition = "AND w.department_id IN (".$groupvalues.")";
			else if($groupKey=='F')
				$groupcondition = "AND w.designation_id IN (".$groupvalues.")";
			else if ($groupKey=='B')
				$groupcondition = "AND w.branch_id IN (".$groupvalues.")";
			else if ($groupKey=='S')
				$groupcondition = "AND w.shift_id IN (".$groupvalues.")";
			else if ($groupKey=='T')
				$groupcondition = "AND w.team_id IN (".$groupvalues.")";
			else if ($groupKey=='E')
				$groupcondition = "";
		}
		
		$stmt = "SELECT w.employee_id,p.employee_image,p.employee_email,p.employee_mobile,s.employee_salary_amount,IFNULL(du.status,0) status,IFNULL(w.dateofexit,'') dateofexit,ct.team_name,ct.team_id,
						CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,p.employee_gender,DATE_FORMAT(w.employee_doj,'%b-%d,%Y') employee_doj,
						des.designation_name,b.branch_name,d.department_name,w.enabled,IF(w.enabled=1,IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Yrs'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Yr'))),
				      	IF(ROUND((DATEDIFF(np.last_working_date,w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(np.last_working_date,w.employee_doj) / 365),1),' Yrs'),IF(ROUND((DATEDIFF(np.last_working_date,w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(np.last_working_date,w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Yr'))))experience
		FROM employee_work_details  w
		INNER JOIN employee_personal_details p
		ON w.employee_id = p.employee_id
		INNER JOIN employee_salary_details s
		ON w.employee_id = s.employee_id
		LEFT JOIN company_designations des
		ON w.designation_id = des.designation_id
		LEFT JOIN company_departments d
		ON w.department_id=d.department_id
		LEFT JOIN company_branch b
		ON w.branch_id=b.branch_id
		LEFT JOIN company_team ct
		ON w.team_id=ct.team_id
		LEFT JOIN device_users du
        ON w.employee_id = du.employee_id
        LEFT JOIN emp_notice_period np
    	ON w.employee_id = np.employee_id
		WHERE  $enabled_cond $groupcondition AND  (w.employee_id LIKE '%".$searchKey."%' OR w.employee_name LIKE '".$searchKey."%' OR w.employee_lastname LIKE '".$searchKey."%')
		AND   (w.employee_name LIKE '".$letterKey."%') 
		ORDER BY w.employee_id   $limtCondition";
		//echo $stmt; die();
		$result = mysqli_query (  $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function getEmployeePersonelDetails($employee_id) {
		$a_json = array ();
		
		$query = "SELECT w.employee_id , w.employee_name,w.employee_lastname,p.employee_gender,p.employee_mobile,
				    p.employee_personal_mobile,p.employee_personal_email,
					DATE_FORMAT(p.employee_dob,'%d/%m/%Y') employee_dob,
					CASE WHEN (    DATE_FORMAT(p.father_dob,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE 
					DATE_FORMAT(p.father_dob,'%d/%m/%Y') END  AS father_dob,
         		    CASE WHEN ( DATE_FORMAT(p.mother_dob,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE 
					DATE_FORMAT(p.mother_dob,'%d/%m/%Y') END  AS mother_dob,
                    CASE WHEN( DATE_FORMAT(p.spouse_dob,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE
                    DATE_FORMAT(p.spouse_dob,'%d/%m/%Y') END AS spouse_dob,
					p.employee_email,p.employee_father_name,p.emp_mother_name,p.spouse_name,
          			p.father_mobile,p.mother_mobile,p.spouse_mobile,
					p.employee_image,p.employee_marital_status,
					(select id from payroll where employee_id='$employee_id' limit 0,1)  id,
					CASE WHEN (    DATE_FORMAT(p.employee_marriagedate,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE 
					DATE_FORMAT(p.employee_marriagedate,'%d/%m/%Y') END   employee_marriagedate,
					CASE WHEN (    DATE_FORMAT(w.notice_date,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE 
					DATE_FORMAT(w.notice_date,'%d/%m/%Y') END  AS notice_date,
					CASE WHEN (    DATE_FORMAT(w.dateofexit,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE 
					DATE_FORMAT(w.dateofexit,'%d/%m/%Y') END  AS dateofexit,
					p.employee_nationality,p.employee_international,p.employee_blood_group,p.employee_pc,p.employee_pan_proof,p.
					employee_pan_no,p.employee_id_proof_type,p.employee_aadhaar_name,p.employee_aadhaar_proof,p.
					employee_aadhaar_id,p.employee_id_proof_no,p.employee_id_proof_expiry,p.
					employee_bank_name,p.employee_acc_no,p.employee_bank_ifsc,p.employee_bank_proof,p.
					employee_bank_branch,p.employee_id_proof,p.employee_build_name,p.employee_area,p.
					employee_pin_code,p.employee_city,p.employee_district,p.employee_street,p.employee_state,p.
					employee_password,p.permanent_emp_bulidname,p.permanent_emp_area,p.permanent_emp_city,p.permanent_emp_dist,p.
					permanent_emp_pincode,p.permanent_emp_state,p.permanent_emp_country,p.emp_country,p.
					emp_sslc_school,p.emp_sslc_board,p.emp_ug_institute_name,p.emp_ug_university,p.emp_ug_degree,p.emp_ug_major_subject,p.employee_ug_proof,p.
					employee_sslc_proof,p.emp_pg_institute_name,p.emp_pg_university,p.emp_pg_degree,p.emp_pg_major_subject, p.employee_pg_proof,p.
					emp_sslc_marks,p.emp_hsc_school,p.emp_hsc_board,p.emp_hsc_marks,p.emp_hsc_year,p.emp_pg_marks,p.
					emp_pg_year_passing,p.emp_ug_year_passing,p.emp_sslc_year,p.employee_hsc_proof,p.employee_pt_adddress,p.
					emp_ug_marks,w.enabled
					FROM employee_work_details w
					INNER JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					WHERE w.employee_id ='$employee_id'  ";
	
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($stmt));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	function letters($employee_id){
		$a_json = array ();
		$query ="SELECT pro.action_id,w.employee_name,IF(pro.incremented_amount!='0|A',CONCAT(ROUND(SUBSTRING_INDEX(pro.incremented_amount,'|',1),2),'%'),NULL) incremented_amount,DATE_FORMAT(pro.action_effects_from,'%d/%m/%Y') action_effects_from,
				action_effects_from effects_from,DATE_FORMAT(action_effects_from,'%W %b %d, %Y') day,
				FORMAT(ROUND(his.employee_salary_amount),0,'e_IN') old_salary,FORMAT(CEIL(his.employee_salary_amount+(his.employee_salary_amount*SUBSTRING_INDEX(pro.incremented_amount,'|',1))/100),0,'e_IN') new_salary,
				OLDdesig.designation_name old_desig,desig.designation_name new_desig,
				IF(pro.promoted_desig_id!='NA' AND pro.incremented_amount!='0|A','Evaluation:Promotion come Increment',IF(pro.incremented_amount='0|A','Evaluation:Promotion','Evaluation:Increment')) letter
				FROM comp_promotions_increments pro
				INNER JOIN employee_work_details w
				ON pro.affected_ids = w.employee_id
				LEFT JOIN company_designations desig
				ON pro.promoted_desig_id = desig.designation_id
				LEFT JOIN employee_salary_details s
				ON w.employee_id = s.employee_id
				LEFT JOIN employee_salary_details_history his
				ON w.employee_id = his.employee_id AND DATE_SUB(pro.action_effects_from,INTERVAL 1 DAY) = his.effects_upto
				LEFT JOIN emp_designation_history desHis
				ON w.employee_id = desHis.employee_id AND DATE_SUB(pro.action_effects_from,INTERVAL 1 DAY) = desHis.effects_upto
				LEFT JOIN company_designations OLDdesig
				ON desHis.designation_id = OLDdesig.designation_id
				WHERE w.employee_id = '$employee_id' AND action_id IS NOT NULL -- AND pro.promoted_desig_id != 'NA';
				UNION 
				SELECT w.employee_id action_id,w.employee_name,' ',DATE_FORMAT(w.employee_confirmation_date,'%d/%m/%Y') confirmation_date,DATE_FORMAT(IF(w.employee_doj=w.employee_confirmation_date,DATE_ADD(w.employee_confirmation_date,INTERVAL 1 DAY),w.employee_confirmation_date),'%Y-%m-%d')
				effects_from,DATE_FORMAT(w.employee_confirmation_date,'%W %b %d, %Y') day,' ',' ',desig.designation_name,'','Offer:Confirmation' letter
				FROM employee_work_details w
				INNER JOIN company_designations desig
				ON w.designation_id = desig.designation_id
				WHERE w.employee_id = '$employee_id' AND w.employee_confirmation_date IS NOT NULL
				UNION
				SELECT action_id,employee_name,NULL,employee_doj,effects_from,DATE_FORMAT(effects_from,'%W %b %d, %Y') day,NULL,
				     FORMAT( IF((gross!=his_gross && his_gross !=0),his_gross,gross),0,'e_IN') employee_salary_amount,NULL,IF((desig!=his_desig && his_desig!=''),his_desig,desig) designation_name,letter
				FROM (
					SELECT w.employee_id action_id,w.employee_name,s.employee_salary_amount gross,IFNULL(sh.employee_salary_amount,0) his_gross,desig.designation_name desig,IFNULL(cdh.designation_name,'') his_desig,DATE_FORMAT(w.employee_doj,'%d/%m/%Y') employee_doj,
						   employee_doj effects_from,'Offer:Letter'letter
					FROM employee_work_details w
					INNER JOIN company_designations desig
					ON w.designation_id = desig.designation_id
					INNER JOIN employee_salary_details s
					ON w.employee_id = s.employee_id
					LEFT JOIN employee_salary_details_history sh
					ON w.employee_id = sh.employee_id
					LEFT JOIN emp_designation_history dh
					ON w.employee_id = dh.employee_id AND dh.promotion_id = 'CREATION'
					LEFT JOIN company_designations cdh
					ON dh.designation_id = cdh.designation_id
					WHERE w.employee_id = '$employee_id' ORDER BY sh.effects_from ASC LIMIT 0,1) z
				  UNION
				  SELECT trans.action_id action_id,w.employee_name,NULL,DATE_FORMAT(trans.action_effects_from,'%d/%m/%Y') action_effects_from,action_effects_from effects_from,DATE_FORMAT(trans.action_effects_from,'%W %b %d, %Y') day,
				         NULL,NULL,IF(trans.is_teamTrans!=1,oldbr.branch_name,oldt.team_name) old_branch,IF(trans.is_teamTrans!=1,newbr.branch_name,te.team_name) new_branch,'Evaluation:Transfer' letter
				  FROM employee_work_details w
				  LEFT JOIN comp_transfers trans
				  ON w.employee_id = trans.affected_ids OR trans.action_id = w.transfer_id AND (w.branch_effects_from = trans.action_effects_from)
				  LEFT JOIN company_branch newbr
				  ON trans.transferred_branch_id = newbr.branch_id
				  LEFT JOIN emp_branch_history brHis
				  ON w.employee_id = brHis.employee_id AND DATE_SUB(trans.action_effects_from,INTERVAL 1 DAY) = brHis.effects_upto  
				  LEFT JOIN company_branch oldbr
				  ON brHis.branch_id = oldbr.branch_id
				  LEFT JOIN company_team te
		          ON w.team_id = te.team_id
		          LEFT JOIN emp_team_history tHis
		          ON w.employee_id = tHis.employee_id AND DATE_SUB(trans.action_effects_from,INTERVAL 1 DAY) = tHis.effects_upto
		          LEFT JOIN company_team oldt
		          ON oldt.team_id = tHis.team_id
				  WHERE w.employee_id = '$employee_id' AND action_id IS NOT NULL
				UNION
	            SELECT w.employee_id,w.employee_name,'',DATE_FORMAT(n.last_working_date,'%d/%m/%Y') action_effects_from,last_working_date effects_from,DATE_FORMAT(n.last_working_date,'%W %b %d, %Y') day,'','','',desig.designation_name new_desig,'Relieving Letter'
          		FROM emp_notice_period n
          		LEFT JOIN employee_work_details w
          		ON n.employee_id = w.employee_id
                LEFT JOIN company_designations desig
			    ON w.designation_id = desig.designation_id
          		WHERE n.employee_id = '$employee_id' AND n.status!='P'
				ORDER BY DATE(effects_from) DESC;";
		
		//echo $query; die();
		$result = mysqli_query ( $this->conn, $query );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	function chooseSlab($ctc){
		$a_json = array ();
		$cond = ($ctc==1)?'AND min_salary_amount=0.00':'';
		$query ="SELECT slab_id,slab_name FROM company_allowance_slabs WHERE slab_type = 'gross' AND enabled=1 $cond ";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		
	}
	function getCurrentDesig_Info($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id,promotion_id,designation_name,DATE_FORMAT(wd.design_effects_from,'%d/%m/%Y') design_effects_from,IFNULL(designation_change_reason,'Nil') designation_change_reason
		FROM employee_work_details wd
		INNER JOIN company_designations cd ON
		wd.designation_id =cd.designation_id
		WHERE employee_id = '$employeeId' ";
		//echo $query; die();
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		 
	}
	
	function  UpdateDesignation($employeeId, $newDesignation , $desigChangeReason, $designationEffectsFrom, $updated_by){
		/* 
		$stmt= mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET employee_id=?, designation_id = ?,design_effects_from = STR_TO_DATE(?,'%d/%m/%Y'),designation_change_reason = ?,updated_by = ? WHERE employee_id = ?");
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $employeeId, $newDesignation,$designationEffectsFrom, $desigChangeReason,$updated_by, $employeeId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));*/
		
		$query = "INSERT INTO emp_designation_history (employee_id,designation_id,effects_from,effects_upto) (
		SELECT '$employeeId',designation_id,design_effects_from,DATE_SUB(STR_TO_DATE('$designationEffectsFrom','%d/%m/%Y'),INTERVAL 1 DAY)
		FROM employee_work_details
		WHERE employee_id='$employeeId')";
		//echo $query; die();
		$stmt = mysqli_query ( $this->conn,$query);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		$stmt= mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET employee_id=?, designation_id = ?,design_effects_from = STR_TO_DATE(?,'%d/%m/%Y'),designation_change_reason = ?,updated_by = ? WHERE employee_id = ?");
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $employeeId, $newDesignation,$designationEffectsFrom, $desigChangeReason,$updated_by, $employeeId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	function DesigChangeHistory($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id, designation_name, effects_from, effects_upto,IFNULL(designation_change_reason,'Employee Creation') designation_change_reason
		FROM emp_designation_history dh
		INNER JOIN company_designations cd ON
		dh.designation_id =cd.designation_id
		WHERE employee_id = '$employeeId' ";
		//echo $query; die();
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function  UpdateDepartment($employeeId, $newDept , $deptChangeReason, $deptEffectsFrom, $updated_by){
		$stmt= mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET employee_id=?, department_id = ?,depart_effects_from = STR_TO_DATE(?,'%d/%m/%Y'),department_change_reason = ?,updated_by = ? WHERE employee_id = ?");
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $employeeId, $newDept, $deptEffectsFrom, $deptChangeReason, $updated_by, $employeeId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
			else
				return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
				 
	}
	
	function getCurrentDepartment_Info($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id,department_name,DATE_FORMAT(wd.depart_effects_from,'%d/%m/%Y') depart_effects_from,IFNULL(department_change_reason,'Nil') department_change_reason
				FROM employee_work_details wd
				INNER JOIN company_departments cd ON
				wd.department_id =cd.department_id
				WHERE employee_id = '$employeeId';";
		//echo $query; die();
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		 
	}
	
	function DeptChangeHistory($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id, department_name, effects_from, effects_upto,IFNULL(department_change_reason,'Employee Creation') department_change_reason
		FROM emp_department_history dh
		INNER JOIN company_departments cd ON
		dh.department_id =cd.department_id
		WHERE employee_id = '$employeeId'";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function  UpdateBranch($employeeId, $newbranch , $branchChangeReason, $branchEffectsFrom, $updated_by){
		$stmt= mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET employee_id=?, branch_id = ?,branch_effects_from = STR_TO_DATE(?,'%d/%m/%Y'),branch_change_reason = ?,updated_by = ? WHERE employee_id = ?");
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $employeeId, $newbranch, $branchEffectsFrom, $branchChangeReason, $updated_by, $employeeId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
				 
	}
	
	function getCurrentBranch_Info($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id,promotion_id,transfer_id,branch_name,DATE_FORMAT(wd.branch_effects_from,'%d/%m/%Y') branch_effects_from,IFNULL(branch_change_reason,'Nil') branch_change_reason
		FROM employee_work_details wd
		INNER JOIN company_branch cb ON
		wd.branch_id =cb.branch_id
		WHERE employee_id = '$employeeId'  ";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		 
	}
	
	function BranchChangeHistory($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id, branch_name, effects_from, effects_upto,IFNULL(branch_change_reason,'Employee Creation') branch_change_reason
		FROM emp_branch_history bh
		INNER JOIN company_branch cb ON
		bh.branch_id =cb.branch_id
		WHERE employee_id = '$employeeId'";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function  UpdateTeam($actionId, $employeeId, $newTeam , $teamChangeReason, $teamEffectsFrom, $updated_by){
		$stmt= mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET transfer_id= ?,
				team_id = ?,team_effects_from = STR_TO_DATE(?,'%d/%m/%Y'),team_change_reason = ?,updated_by = ? WHERE employee_id = ?");
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $actionId, $newTeam, $teamEffectsFrom, $teamChangeReason, $updated_by, $employeeId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
				 
	}
	
	function getCurrentTeam_Info($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id,wd.team_id,team_name,DATE_FORMAT(wd.team_effects_from,'%d/%m/%Y') team_effects_from,IFNULL(team_change_reason,'Nil') team_change_reason 
		FROM employee_work_details wd
		INNER JOIN company_team ct ON
		wd.team_id =ct.team_id
		WHERE employee_id = '$employeeId'  ";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		 
	}
	
	function TeamChangeHistory($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id, team_name, effects_from, effects_upto,IFNULL(team_change_reason,'Employee Creation') team_change_reason
		FROM emp_team_history th
		INNER JOIN company_team ct ON
		th.team_id =ct.team_id
		WHERE employee_id = '$employeeId'";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function  UpdatejobStatus($employeeId, $newstatus , $statusChangeReason, $statusEffectsFrom, $updated_by){
		$stmt= mysqli_prepare ( $this->conn, "UPDATE employee_work_details SET status_id = ?,job_status_effects_from = STR_TO_DATE(?,'%d/%m/%Y'),job_status_change_reason = ?,updated_by = ? WHERE employee_id = ?");
		mysqli_stmt_bind_param ( $stmt, 'sssss', $newstatus, $statusEffectsFrom, $statusChangeReason, $updated_by, $employeeId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
			else
				return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
				 
	}
	
	function getCurrentjob_status($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id,status_name,DATE_FORMAT(wd.job_status_effects_from,'%d/%m/%Y') job_status_effects_from,IFNULL(job_status_change_reason,'Nil') job_status_change_reason
		FROM employee_work_details wd
		INNER JOIN company_job_statuses cs ON
		wd.status_id =cs.status_id
		WHERE employee_id = '$employeeId'  ";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		 
	}
	
	
	function JobstatusChangeHistory($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id, status_name, IFNULL(effects_from,'') effects_from, effects_upto,IFNULL(job_status_change_reason,'Employee Creation') job_status_change_reason
		FROM emp_job_status_history sh
		INNER JOIN company_job_statuses cs ON
		sh.status_id =cs.status_id
		WHERE employee_id = '$employeeId'";
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function getCurrentShift($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id,w.shift_id,shift_name,DATE_FORMAT(w.shift_effects_from,'%d/%m/%Y') from_date,IFNULL(shift_change_reason,'Nil') shift_change_reason
		FROM employee_work_details w
		INNER JOIN company_shifts cs 
		ON IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id) =cs.shift_id
		WHERE employee_id = '$employeeId'";
		//echo $query;
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		 
	}
	
	
	function  updateShiftDetails($empId, $shiftId ,$changeReason, $fromDate, $toDate,$updated_by){
		$json = array();
		if(is_array($empId)){
			$employeeIds = implode (' ', $empId);
			$employeeId = "'".str_replace(" ","','",$employeeIds)."'";			
		}else{
			$employeeId = $empId;
		}
	
		/*
		$shiftcheck = "SELECT DISTINCT IFNULL(s.employee_id,CONCAT(w.employee_doj,',',IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),',',IFNULL(w.shift_change_reason,'Nil'))) emp_doj
					   FROM shift_roaster s
					   RIGHT JOIN employee_work_details w
					   ON s.employee_id = w.employee_id AND s.employee_id IN ($employeeId)
					   WHERE w.employee_id IN ($employeeId);";
		print_r($shiftcheck);
		die();	
		$result = mysqli_query ( $this->conn, $shiftcheck) OR die(mysqli_stmt_error($shiftcheck));
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ); 
		$emp_doj = explode(',',$row['emp_doj']);
		
		if($employeeId != $emp_doj[0]){
			$stmt1 = mysqli_prepare ( $this->conn, "INSERT into shift_roaster (employee_id, shift_id , from_date , to_date, shift_change_reason ,updated_by )VALUES(?,?,?,DATE_SUB(STR_TO_DATE('$fromDate','%d/%m/%Y'),INTERVAL 1 DAY),?,?);");
			mysqli_stmt_bind_param ( $stmt1, 'sssss', $employeeId, $emp_doj[1] ,$emp_doj[0], $emp_doj[2],$updated_by );
			$result = mysqli_stmt_execute ( $stmt1 ) ? TRUE : mysqli_error ( $this->conn );
			/*
			$stmt2 = mysqli_prepare ( $this->conn, "INSERT into shift_roaster (employee_id, shift_id , from_date , to_date ,updated_by )VALUES(?,?,?,NULL,?);");
			mysqli_stmt_bind_param ( $stmt2, 'ssss', $employeeId, $shiftId, $fromDate, $updated_by );
			$result = mysqli_stmt_execute ( $stmt2 ) ? TRUE : mysqli_error ( $this->conn );
		}*/
		
		$query_1= "UPDATE employee_work_details SET shift_id = '$shiftId',shift_effects_from = STR_TO_DATE('$fromDate','%d/%m/%Y'),shift_change_reason = '$changeReason',updated_by = '$updated_by' WHERE employee_id IN ($employeeId)";
		$result = mysqli_query ( $this->conn, $query_1 );
		//mysqli_stmt_bind_param ( $stmt, 'sssss', $shiftId, $fromDate, $changeReason, $updated_by, $employeeId );
		//$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		if($employeeId!=null && $toDate==null){
			$query ="UPDATE shift_roaster SET to_date = DATE_SUB(DATE_FORMAT(STR_TO_DATE('$fromDate','%d/%m/%Y'),'%Y-%m-%d'),INTERVAL 1 DAY) WHERE employee_id IN($employeeId) AND (to_date='0000-00-00' OR to_date IS NULL )";
			$result = mysqli_query ( $this->conn, $query );
		}
		
		foreach( $empId as $empIds){
			 $stmt = mysqli_prepare ( $this->conn, "INSERT into shift_roaster (employee_id, shift_id , from_date , to_date , shift_change_reason ,updated_by )VALUES(?,?, STR_TO_DATE('$fromDate','%d/%m/%Y'),STR_TO_DATE('$toDate','%d/%m/%Y'),?,?);");
			 mysqli_stmt_bind_param ( $stmt, 'ssss', $empIds, $shiftId, $changeReason, $updated_by );
			 $result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		} 
	if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	function ShiftChangeHistory($employeeId){
		$a_json = array ();
		$query ="SELECT employee_id, shift_name, from_date, to_date,IFNULL(shift_change_reason,'-') shift_change_reason
		FROM shift_roaster sr
		INNER JOIN company_shifts cs ON
		sr.shift_id = cs.shift_id
		WHERE employee_id = '$employeeId' AND (to_date !='0000-00-00' AND to_date IS NOT NULL )";
		//echo $query; 
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	}
	
	function getEnrollStatus($empId){
		$a_json = array ();
		$result=mysqli_query($this->conn,"SELECT w.employee_id,IFNULL(d.status,'0') status,IFNULL(d.pin,'0') pin,d.ref_id,(CASE WHEN d.fp1 IS NOT NULL THEN 1 ELSE 0 END) fp1,
				(CASE WHEN d.fp2 IS NOT NULL THEN 1 ELSE 0 END) fp2,
				(CASE WHEN d.fp3 IS NOT NULL THEN 1 ELSE 0 END) fp3,
				(CASE WHEN d.fp4 IS NOT NULL THEN 1 ELSE 0 END) fp4,
				(CASE WHEN d.fp5 IS NOT NULL THEN 1 ELSE 0 END) fp5 FROM employee_work_details w
				LEFT JOIN device_users d ON w.employee_id=d.employee_id
				WHERE w.employee_id ='$empId' ");
		
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
	
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
	
	}
	
	function getShiftAllocdetails($employeeId,$newShiftId){
		
		$a_json = array ();
		$query ="SELECT w.employee_name,w.employee_id,oldsh.shift_name old_shift,newsh.shift_name new_shift, newsh.shift_id new_shift_id
					FROM employee_work_details w 
					LEFT JOIN company_shifts oldsh 
					ON w.shift_id = oldsh.shift_id 
					LEFT JOIN company_shifts newsh 
					ON newsh.shift_id = '$newShiftId' 
					WHERE $employeeId";
		//echo $query;
		$result = mysqli_query ( $this->conn, $query ) OR die(mysqli_stmt_error($query));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		
	}
	function getlateLOP(){
		$json=array();
		$stmt = "SELECT w.employee_id, w.employee_name,p.alop,p.late_lop
				FROM payroll_preview_temp p
				INNER JOIN employee_work_details w
				ON p.employee_id = w.employee_id
				WHERE w.enabled =1 AND (p.alop >0 OR p.late_lop >0);";
		//echo $stmt; die();
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//return $json;
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$json:mysqli_error ( $this->conn ))
		);
	}
	
	function UpdateLateLOP($lopdata){
		$json=array();
		$data = explode(',',$lopdata);
		//print_r($data); 
		foreach ($data as $empdata){
			$query ="UPDATE payroll_preview_temp SET alop=SUBSTRING_INDEX(SUBSTRING_INDEX('$empdata','|',2),'|',-1), late_lop=SUBSTRING_INDEX('$empdata','|',-1) WHERE employee_id=SUBSTRING_INDEX('$empdata','|',1);";
			$result = mysqli_query ( $this->conn, $query );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$result:mysqli_error ( $this->conn ))
		);
	}
	
}

	
	?>