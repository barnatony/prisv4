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
error_reporting ( 0 );
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* IncludeClassLibrary */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/event.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$event = new Event ();
$event->category = isset ( $_REQUEST ['category'] ) ? $_REQUEST ['category'] : null;
$event->branch_id = isset ( $_REQUEST ['category'] ) ? ($_REQUEST ['category'] == 'NORMAL') ? "ALL" : "NA" : null;
$event->title = isset ( $_REQUEST ['title'] ) ? $_REQUEST ['title'] : "";
$event->start_date = isset ( $_REQUEST ['sDate'] ) ? $_REQUEST ['sDate'] : "";
$event->end_date = isset ( $_REQUEST ['eDate'] ) ? $_REQUEST ['eDate'] : "";
$event->updated_by = isset ($_SESSION ['login_id'] ) ?$_SESSION ['login_id']:"";
$event->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$event->event_id = "HD" . $rand;
		$event->resp_person_emp_id = 'NIL';
		$result = $event->insert ();
		break;
	case "delete" :
		$result = $event->delete ();
		break;
	case "view" :
		$result = $event->view ();
		break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "event " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>