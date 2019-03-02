<?php
/*
 * ----------------------------------------------------------
 * Filename : designation.class.php
 * Author : Rufus Jackson
 * Database : company_designations
 * Oper : Designation Actions
 *
 * ----------------------------------------------------------
 */
class Designation {
	/* Member variables */
	var $designation_id;
	var $designation_name;
	var $designation_hierarchy;
	var $enabled;
	var $updated_by;
	var $conn;
	
	/* Member functions */
	/* Insert New Designation */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_designations
										(designation_id,designation_name,designation_hierarchy,enabled,updated_by) 
										VALUES (?,?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssis', $this->designation_id, $this->designation_name, $this->designation_hierarchy, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update Branch Using Designation ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_designations SET 
				designation_name = ?,designation_hierarchy = ?,updated_by = ? WHERE designation_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'siss', $this->designation_name, $this->designation_hierarchy, $this->updated_by, $this->designation_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Enable/Disable Designation */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_designations SET enabled = ?,updated_by = ? WHERE designation_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->designation_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Designation */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_designations WHERE designation_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->designation_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* select Designation */
	function select() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT designation_name,designation_id FROM company_designations  where enabled=1 " );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
}
?>