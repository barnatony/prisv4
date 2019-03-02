<?php
/*
 * ----------------------------------------------------------
 * Filename : deduction.class.php
 * Author : Rufus Jackson
 * Database : company_deductions
 * Oper : General Deduction Actions
 *
 * ----------------------------------------------------------
 */
class Deduction {
	var $company_id;
	var $deduction_id;
	var $alias_name;
	var $is_both_contribution;
	var $is_admin_charges;
	var $employee_share;
	var $employer_share;
	var $admin_charges;
	var $deduce_in;
	var $payment_to;
	var $frequency;
	var $max_employee_share;
	var $cal_exemption;
	var $max_employer_share;
	var $due_in;
	var $enabled;
	var $updated_by;
	var $conn; // connection var
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	function select() {
		$result = mysqli_query ( $this->conn, "SELECT d.deduction_id, p.alias_name, d.is_both_contribution, d.is_admin_charges,
	        d.employee_share, d.employer_share, d.admin_charges, d.deduce_in, d.payment_to, d.frequency,
	        d.max_employee_share, d.cal_exemption, d.max_employer_share, d.due_in, d.updated_by,
	        d.updated_on,p.display_flag 
			FROM  company_deductions  d
			INNER JOIN company_pay_structure p ON d.deduction_id = p.pay_structure_id
			WHERE d.deduction_id='" . $this->deduction_id_s . "'" );
		$row = mysqli_fetch_object ( $result );
		return $row;
	}
	function ptSlab($city, $financial) {
		$a_json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT  pt.pt_city ,pt.eligibility,pt.from_value,pt.to_value,pt.rate FROM pt_slab pt WHERE pt.pt_city='$city' and pt.fin_year='$financial'" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function itSlab($financial) {
		$result = array();
		$a_json = array ();
		$query =  "SELECT  it.from_value,it.to_value,it.rate,it.age_group FROM it_slab it WHERE it.fin_year='$financial'";
		$stmt = mysqli_query ( $this->conn, $query );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		array_push ( $result,$a_json);
		$a_json = array();
		$query = "SELECT it.name,it.value FROM it_properties it WHERE it.fin_year='$financial'";
		$stmt = mysqli_query ( $this->conn,$query);
		while ($row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC )){
			array_push ( $a_json,$row);
		}
		array_push ( $result,$a_json);
		return $result;
	}
	// **********************
	// INSERT
	// **********************
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_deductions ( deduction_id,alias_name,is_both_contribution,is_admin_charges,employee_share,
		employer_share,admin_charges,deduce_in,payment_to,frequency,max_employee_share,cal_exemption,max_employer_share,due_in,enabled,updated_by) 
		VALUES  (?,?,?,?,?,?,?,?,?,?,?,?,?,?,1,?) " );
		mysqli_stmt_bind_param ( $stmt, 'ssiisssssssssss', $this->deduction_id, $this->alias_name, $this->is_both_contribution, $this->is_admin_charges, $this->employee_share, $this->employer_share, $this->admin_charges, $this->deduce_in, $this->payment_to, $this->frequency, $this->max_employee_share, $this->cal_exemption, $this->max_employer_share, $this->due_in, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		mysqli_query ( $this->conn, "UPDATE payroll_preview_temp SET status_flag='D',updated_by='$this->updated_by'" );
		return $result;
	}
	function addDeduction() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_pay_structure ( pay_structure_id,display_name,alias_name,type,sort_order,display_flag,updated_by)
		VALUES  (?,?,?,'D',99,0,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->deduction_id, $this->display_name, $this->alias_name, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function createDeduction($data) {
		$sortOrder = 0;
		foreach ( $data as $allow ) {
			$stmt = mysqli_prepare ( $this->conn, "UPDATE company_pay_structure SET display_flag = 1 , sort_order = ? , updated_by = ? WHERE pay_structure_id = ?" );
			mysqli_stmt_bind_param ( $stmt, 'iss', $sortOrder, $this->updated_by, $allow ['id'] );
			$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
			if ($result) {
				if ($allow ['id'] != 'c_pt' && $allow ['id'] != 'c_it') {
					
					$alias_name = explode ( '_', $allow ['id'] );
					$stmt = mysqli_prepare ( $this->conn, "INSERT  INTO company_deductions(deduction_id,alias_name,updated_by) VALUES (?,?,?) ON DUPLICATE KEY UPDATE deduction_id =?" );
					mysqli_stmt_bind_param ( $stmt, 'ssss', $allow ['id'], $alias_name [1], $this->updated_by, $allow ['id'] );
					$result = mysqli_stmt_execute ( $stmt );
					mysqli_query ( $this->conn, "ALTER TABLE arrears ADD  " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER gross_salary" );
				} 
				
				mysqli_query ( $this->conn, "ALTER TABLE payroll_preview_temp ADD  " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER gross_salary" );
				mysqli_query ( $this->conn, "ALTER TABLE payroll ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER gross_salary" );
				$sortOrder ++;
			}
			mysqli_query ( $this->conn, "UPDATE payroll_preview_temp SET status_flag='D',updated_by='$this->updated_by'" );
			Session::newInstance ()->_drop ( "generalPayParams" );
			Session::newInstance ()->_setGeneralPayParams ();
			Session::newInstance ()->_get ( "generalPayParams" );
			
		}
		return $result;
	}
	
	// **********************
	// UPDATE
	// **********************
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_deductions d , company_pay_structure p SET  p.alias_name = ?,
		d.is_both_contribution = ?,d.is_admin_charges = ?,d.employee_share = ?,d.employer_share = ?,d.admin_charges =?,d.deduce_in = ?,d.due_in = ?,
		d.payment_to = ?,d.frequency = ?,d.cal_exemption = ?,d.max_employee_share = ?,d.max_employer_share = ?,d.updated_by = ? 
		WHERE d.deduction_id = p.pay_structure_id  AND d.deduction_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssssssssss', $this->ualias_name, $this->is_both_contribution, $this->is_admin_charges, $this->employee_share, $this->employer_share, $this->admin_charges, $this->deduce_in, $this->due_in, $this->payment_to, $this->frequency, $this->cal_exemption, $this->max_employee_share, $this->max_employer_share, $this->updated_by, $this->deduction_id_s );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		mysqli_query ( $this->conn, "UPDATE payroll_preview_temp SET status_flag='D',updated_by='$this->updated_by'" );
		
		return $result;
	}
} // class : end

?>