<?php
/*
 * ----------------------------------------------------------
 * Filename : department.class.php
 * Author : Rufus Jackson
 * Database : company_department
 * Oper : Department Actions
 *
 * ----------------------------------------------------------
 */
class Department {
	/* Member variables */
	var $department_id;
	var $name;
	var $enabled;
	var $updated_by;
	var $conn;
	
	/* Member functions */
	/* Insert New Department */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_departments
										(department_id,department_name,enabled,updated_by) 
										VALUES (?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sss', $this->department_id, $this->name, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update Branch Using Department ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_departments SET 
				department_name = ?,updated_by = ? WHERE department_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sss', $this->name, $this->updated_by, $this->department_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Enable/Disable Department */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_departments SET enabled = ?,updated_by = ? WHERE department_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->department_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Department */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_departments WHERE department_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->department_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Select Department */
	function select() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT department_name, department_id FROM company_departments where enabled=1 " );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
}
?>