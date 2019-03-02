<?php
/*
 * ----------------------------------------------------------
 * Filename : miscdeductionclass.php
 * Author : Rajasundari
 * Database : miscdeduction
 * Oper : General miscdeduction Actions
 *
 * ----------------------------------------------------------
 */
class MiscDeuctions {
	var $miscdeduc;
	var $name; // (normal Attribute)
	var $alias_name; // (normal Attribute)
	var $type; // (normal Attribute)
	var $sort_order = 99; // (normal Attribute)
	var $enabled = 0; // (normal Attribute)
	var $updated_by; // (normal Attribute)
	var $deduction_id;
	var $deduction_name;
	var $deduction_for;
	var $dedu_category;
	var $dedu_affected_ids;
	var $deduction_amount;
	var $deductions_in;
	var $effects_from;
	var $conn; // connection var
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	function add() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_pay_structure 
		( pay_structure_id,display_name,alias_name,type,sort_order,display_flag,updated_by)VALUES  (?,?,?,'MD',0,0,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->miscdeduc, $this->display_name, $this->alias_name, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function createMiscdeduc($data) {
		$sortOrder = 0;
		foreach ( $data as $allow ) {
			$stmt = mysqli_prepare ( $this->conn, "UPDATE company_pay_structure SET display_flag = 1 , sort_order = ? , updated_by = ? WHERE pay_structure_id = ?" );
			mysqli_stmt_bind_param ( $stmt, 'iss', $sortOrder, $this->updated_by, $allow ['id'] );
			$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
			if ($result) {
				mysqli_query ( $this->conn, "ALTER TABLE payroll_preview_temp ADD  " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER gross_salary" );
				mysqli_query ( $this->conn, "ALTER TABLE payroll ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER gross_salary" );
			}
			$sortOrder ++;
		}
		Session::newInstance ()->_drop ( "miscPayParams" );
		Session::newInstance ()->_setMiscPayParams ();
		Session::newInstance ()->_get ( "miscPayParams" );
		mysqli_query ( $this->conn, "UPDATE payroll_preview_temp SET status_flag='MD',updated_by='$this->updated_by'" );
		return $result;
	}
	function insert() {
		$emp_id = array();
		$emp_id = explode(',',$this->dedu_affected_ids);
		$date = '01/'.$this->effects_from;
		foreach( $emp_id as $val){
			$stmt = mysqli_prepare ( $this->conn, "INSERT INTO misc_deduction
					(deduction_id,remarks,deduction_for,dedu_category,dedu_affected_ids,deduction_amount,deductions_in,effects_from,repetition_count,
					effects_upto,updated_by)VALUES(?,?,?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,DATE_ADD(STR_TO_DATE(?,'%d/%m/%Y'),INTERVAL ?-1 MONTH),?)" );
			mysqli_stmt_bind_param ( $stmt, 'ssssssssssss', $this->deduction_id, $this->remarks, $this->deduction_for, $this->dedu_category, $val, $this->deduction_amount, $this->deductions_in, $date, $this->repetition_count, $date, $this->repetition_count, $this->updated_by );
			$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		}
		$extraCondition = "SET pt.status_flag = 'MD' WHERE w.enabled = 1 AND pt.employee_id IN ( '" . str_replace ( ",", "','", $this->dedu_affected_ids ) . "')";
		if($result===true){
		mysqli_query ( $this->conn, " UPDATE payroll_preview_temp pt
							   INNER JOIN employee_work_details w
							   ON pt.employee_id = w.employee_id
			                   $extraCondition" );
		return array('result'=>true,'data'=>'');
		}else
			return array('result'=>false,'data'=>$result);
	}
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE misc_deduction SET deduction_amount=?, deductions_in=?,remarks=?,updated_by=? WHERE deduction_id =?" );
		mysqli_stmt_bind_param ( $stmt, 'sssss', $this->deduction_amount, $this->deductions_in, $this->remarks, $this->updated_by, $this->deduction_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		if($result===true){
		mysqli_query ( $this->conn, "UPDATE payroll_preview_temp pt
									 INNER JOIN employee_work_details w
									 ON pt.employee_id = w.employee_id
									 INNER JOIN misc_deduction md 
									 ON pt.employee_id = md.dedu_affected_ids
									 SET status_flag = 'MD' WHERE w.enabled = 1 AND md.deduction_id = '$this->deduction_id';" );
		return array('result'=>true,'data'=>'');
		}else
			return array('result'=>false,'data'=>$result);
	}
	
	/* Enable/Disable LeaveRule */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE misc_deduction SET enabled =?,updated_by = ?  WHERE deduction_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->deduction_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function getgroupIds(){
		$a_json=array();
		$query = "SELECT CONCAT(w.employee_name,' [',md.dedu_affected_ids,']') employee
				 FROM misc_deduction md
				 INNER JOIN employee_work_details w
				 ON md.dedu_affected_ids = w.employee_id
				 WHERE md.deduction_id = '$this->deduction_id'";
		$result=mysqli_query($this->conn, $query) or die(mysqli_error($this->conn));
		while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			array_push($a_json,$row);
		}
		return $a_json;
	
	}
} // class : end

?>
