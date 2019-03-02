<?php
/*
 * ----------------------------------------------------------
 * Filename : Claim.class.php
 * Author : Rajasundari
 * Database : Claim
 * Oper : Claim Actions
 *
 * ----------------------------------------------------------
 */
class ClaimRules {
	/* Member variables */
	var $claim_id;
	var $claim_name;
	var $alias_name;
	var $category_type;
	var $sub_type;
	var $class;
	var $amount_from;
	var $amount_to;
	var $updated_by;
	var $claimId_mapp;
	var $applicable_id;
	var $applicable_for;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New claimRule */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO claim_rules
                               (claim_id, claim_name, alias_name, category_type, sub_type, class, amount_from, amount_to, enabled, updated_by)  
							    VALUES (?,?,?,?,?,?,?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssss', $this->claim_id, $this->claim_name, $this->alias_name, $this->category_type, $this->sub_type, $this->class, $this->amount_from, $this->amount_to, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		return $this->select ();
	}
	
	/* Update claimRule Using claimRule ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE claim_rules SET 
				claim_name=?, alias_name=?, category_type=?, sub_type=?, class=?, amount_from=?, amount_to=?,updated_by=? WHERE claim_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssss', $this->claim_name, $this->alias_name, $this->category_type, $this->sub_type, $this->class, $this->amount_from, $this->amount_to, $this->updated_by, $this->claim_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $this->select ();
	}
	function select() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT CONCAT(cr.claim_id,'_',cr.claim_name)  cliamRules FROM claim_rules cr WHERE  cr.enabled=1" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	/* Clamis Select For based on theri employees */
	function selectBasedEmplouee($employee_id) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT cr.category_type,CONCAT(cr.category_type,'_',cr.sub_type,'_',IF (cr.class = '', '-',cr.class),'_',cr.amount_from,'_',cr.amount_to) ruleData,cr.claim_name,cm.claim_id
														FROM claim_mapping cm
														INNER JOIN employee_work_details w
														ON w.employee_id = cm.applicable_id
														OR  cm.applicable_id =w.designation_id
														INNER JOIN claim_rules cr
														ON cr.claim_id = cm.claim_id
														WHERE w.employee_id='$employee_id'" );
		
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	/* Enable/Disable LeaveRule */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE claim_rules SET enabled =?,updated_by = ?  WHERE claim_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->claim_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Delete claimRule */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM claim_mapping WHERE claim_id = ? AND applicable_id=?" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $this->claim_id, $this->applicable_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function MappedWith() {
		$queryStmt = "INSERT INTO claim_mapping  (claim_id, applicable_for, applicable_id,updated_by) 
				VALUES ";
		foreach ( $this->applicable_id as $mappedIds ) {
			$queryStmt .= "('" . $this->claimId_mapp . "','" . $this->applicable_for . "','" . $mappedIds . "','" . $this->updated_by . "'),";
		}
		$result = mysqli_query ( $this->conn, trim ( $queryStmt, "," ) ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function selectMappedClaim() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT c.claim_id,cr.claim_name, (CASE WHEN c.applicable_for = 'E' THEN 'Employee'
            WHEN c.applicable_for = 'F' THEN 'Designation' END) applicable_for,(CASE WHEN c.applicable_for = 'E' THEN CONCAT(w.employee_name,' ',w.employee_lastname)
            WHEN c.applicable_for = 'F' THEN d.designation_name END) employee_name,CONCAT(c.claim_id,'_',applicable_id) 'Actions' FROM claim_mapping c INNER JOIN claim_rules cr ON c.claim_id = cr.claim_id
            LEFT JOIN employee_work_details w ON c.applicable_id = (CASE WHEN c.applicable_for = 'E' THEN w.employee_id
            WHEN c.applicable_for = 'F' THEN '' END) LEFT JOIN company_designations d ON  c.applicable_id = (CASE WHEN c.applicable_for = 'F' THEN d.designation_id
            WHEN c.applicable_for = 'E' THEN  '' END) " );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
}
?>