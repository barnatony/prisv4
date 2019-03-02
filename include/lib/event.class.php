<?php
/*
 * ----------------------------------------------------------
 * Filename : event.handle.php
 * Classname: event.class.php
 * Author : Rajasundari
 * Database : HoildayEvent
 * Oper : HoildayEvent Actions
 *
 * ----------------------------------------------------------
 */
class Event {
	/* Member variables */
	var $category;
	var $title;
	var $start_date;
	var $end_date;
	var $updated_by;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New event */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO holidays_event
        (holiday_id, category, branch_id, title, start_date, end_date,updated_by) 
										VALUES (?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?)" );
		mysqli_stmt_bind_param ( $stmt, 'sssssss', $this->event_id, $this->category, $this->branch_id, $this->title, $this->start_date, $this->end_date, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function view() {
		$a_json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT title,start_date  AS start,end_date AS end FROM `holidays_event`" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function delete() {
		$result = mysqli_query ( $this->conn, "DELETE FROM `holidays_event` where title='$this->title'" );
		return $result;
	}
}
?>