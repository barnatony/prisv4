<?php

class team {
	/* Member variables */
	var $team_id;
	var $name;
	var $enabled;
	var $updated_by;
	var $conn;

	/* Member functions */
	/* Insert New Team */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_team
										(team_id,team_name,enabled,updated_by)
										VALUES (?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sss', $this->team_id, $this->name, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update Team Using Team ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_team SET
				team_name = ?,updated_by = ? WHERE team_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sss', $this->name, $this->updated_by, $this->team_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Enable/Disable Team */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_team SET enabled = ?,updated_by = ? WHERE team_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->team_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Team */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_team WHERE team_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->team_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Select Team */
	function select() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT team_name, team_id FROM company_team where enabled=1 " );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	/* select Team with employee */
	function selectEmpTeam() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT t.team_name,w.employee_name, w.employee_id FROM employee_work_details w INNER JOIN company_team t ON t.team_id=w.team_id AND w.enabled=1" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
}
?>