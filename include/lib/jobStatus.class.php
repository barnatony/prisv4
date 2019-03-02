<?php
/*
 * ----------------------------------------------------------
 * Filename : jobstatus.class.php
 * Author : Rufus Jackson
 * Database : company_job_statuses
 * Oper : Branch Actions
 *
 * ----------------------------------------------------------
 */
class JobStatus {
	/* Member variables */
	var $status_id;
	var $name;
	var $comments;
	var $enabled;
	var $updated_by;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New Job Status */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_job_statuses
										(status_id,status_name, status_comments,enabled,updated_by) 
										VALUES (?,?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->status_id, $this->name, $this->comments, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update Job Status Using Job Status ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_job_statuses SET 
				status_name = ?,status_comments = ?,updated_by = ? WHERE status_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->name, $this->comments, $this->updated_by, $this->status_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Enable/Disable Job Status */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_job_statuses SET enabled = ?,updated_by = ? WHERE status_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->status_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Job Status */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_job_statuses WHERE status_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->status_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
}
?>