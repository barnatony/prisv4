<?php
/*
 * ----------------------------------------------------------
 * Filename : allowanceSlab.class.php
 * Author : Rufus Jackson
 * Database : company_allowance_slabs
 * Oper : Allowance Slab Actions
 *
 * ----------------------------------------------------------
 */
class AllowanceSlab {
	/* Member variables */
	var $slab_id;
	var $slab_name;
	var $slab_type;
	var $min_salary_amount = 0.00;
	var $enabled = 1;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	
	/* Insert Slab */
	function insert($allow_columns, $values) {
		$params = array (
				&$this->slab_id,
				&$this->slab_name,
				&$this->slab_type 
		);
		$type = "sss";
		$qnMark = "";
		$extraCoum = "";
		if ($this->slab_type == 'basic') {
			$qnMark .= "'NA',";
			$extraCoum = 'basic,';
		}
		for($i = 0; $i < count ( $allow_columns ); $i ++) {
			$qnMark .= '?,';
			$type .= "s";
			$$allow_columns [$i] = $values [$i];
			$params [] = &$$allow_columns [$i];
		}
		$type .= "is";
		$params [] = &$this->min_salary_amount;
		$params [] = &$this->updated_by;
		$queryStmt = "INSERT INTO company_allowance_slabs (slab_id,slab_name,slab_type," . $extraCoum . implode ( ',', $allow_columns ) . ",min_salary_amount,enabled,
					  	updated_by) VALUES (?,?,?," . $qnMark . "?,1,?)";
		$stmt = mysqli_prepare ( $this->conn, $queryStmt );
		call_user_func_array ( 'mysqli_stmt_bind_param', array_merge ( array (
				$stmt,
				$type 
		), $params ) );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Select Slab By Type */
	function selectByType($type) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT * FROM company_allowance_slabs WHERE slab_type = '" . $type . "' AND enabled=1" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	/* Update Slab Name */
	function updateName($id, $value) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_allowance_slabs s SET  s.slab_name = ? WHERE s.slab_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $value, $id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Enable/Disable Slab */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_allowance_slabs SET enabled = ?,updated_by = ? WHERE slab_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->slab_ed );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Slab */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE s FROM company_allowance_slabs s 
												LEFT JOIN employee_salary_details sal 
												ON s.slab_id = sal.slab_id 
												WHERE sal.slab_id IS NULL AND s.slab_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->slab_ed );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		$result = array (
				$result,
				mysqli_stmt_affected_rows ( $stmt ) 
		);
		return $result;
	}
	/* Update Slab Data By ID if its not mapped to any of the employees */
	function updateSlabData($allow_columns, $values) {
		$params = array (
				&$this->slab_name,
				&$this->slab_type 
		);
		$type = "ss";
		for($i = 0; $i < count ( $allow_columns ); $i ++) {
			$type .= "s";
			$$allow_columns [$i] = $values [$i];
			$params [] = &$$allow_columns [$i];
		}
		$type .= "iss";
		$params [] = &$this->min_salary_amount;
		$params [] = &$this->updated_by;
		$params [] = &$this->slab_id;
		$queryStmt = "UPDATE company_allowance_slabs s LEFT JOIN employee_work_details w ON s.slab_id = w.slab_id 
													SET s.slab_name = ? ,s.slab_type = ? ,s." . implode ( '= ?,s.', $allow_columns ) . "= ? 
												   ,s.min_salary_amount = ?
												  ,s.updated_by = ?
													WHERE w.slab_id IS NULL AND s.slab_id = ?";
		$stmt = mysqli_prepare ( $this->conn, $queryStmt );
		call_user_func_array ( 'mysqli_stmt_bind_param', array_merge ( array (
				$stmt,
				$type 
		), $params ) );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		$result = array (
				$result,
				mysqli_stmt_affected_rows ( $stmt ) 
		);
		return $result;
	}
}
?>