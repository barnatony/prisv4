<?php
/*
 * ----------------------------------------------------------
 * Filename : branch.class.php
 * Author : Rufus Jackson
 * Database : company_branch
 * Oper : Branch Actions
 *
 * ----------------------------------------------------------
 */
class Branch {
	/* Member variables */
	var $branch_id;
	var $name;
	var $address;
	var $pin;
	var $pt_slab;
	var $pt;
	var $esi;
	var $pf;
	var $tan;
	var $resp_person_emp_id;
	var $enabled;
	var $updated_by;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New Branch */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_branch
										(branch_id,branch_name, branch_addr, city_pin, pt_city_id, pt_no, esi_no, pf_no, tan_no, resp_person_emp_id,state,city,enabled,updated_by) 
										VALUES (?,?,?,?,?,?,?,?,?,?,?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sssisssssssss', $this->branch_id, $this->name, $this->address, $this->pin, $this->pt_slab, $this->pt, $this->esi, $this->pf, $this->tan, $this->resp_person_emp_id, $this->state, $this->city, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update Branch Using Branch ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_branch SET 
				branch_name = ?,branch_addr = ?,city_pin = ?,pt_city_id = ?,pt_no = ?,esi_no = ?,pf_no = ?,
      			tan_no = ?,resp_person_emp_id = ?,updated_by = ?,state=?,city=? WHERE branch_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssissssssssss', $this->name, $this->address, $this->pin, $this->pt_slab, $this->pt, $this->esi, $this->pf, $this->tan, $this->resp_person_emp_id, $this->updated_by, $this->state, $this->city, $this->branch_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Enable/Disable Branch */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_branch SET enabled = ?,updated_by = ? WHERE branch_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->branch_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Branch */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_branch WHERE branch_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->branch_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* select Branch */
	function select() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT branch_name, branch_id FROM company_branch  where enabled=1" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	/* select Branch with employee */
	function selectEmpBranch() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT b.branch_name,w.employee_name, w.employee_id FROM employee_work_details w INNER JOIN company_branch b ON b.branch_id=w.branch_id AND w.enabled=1" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
}
?>