<?php
/*
 * ----------------------------------------------------------
 * Filename : dashboard.handle.php
 * Classname: dashboard.class.php
 * Author : Rufus Jackson
 * Database :
 * Oper :
 *
 * ----------------------------------------------------------
 */
require_once ("../../include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/dashboard.class.php");

// $temp = explode('!', base64_decode($_REQUEST['act']));
// $action = $temp[1];
$action = $_REQUEST ['act'];
$resultObj = array ();

/* Operations to be performed */

/* Setting Variables */
$dashboard = new Dashboard ();
$dashboard->updated_by = $_SESSION ['employee_id'];
$dashboard->conn = $conn;

switch ($action) {
	case "getChartData" :
		$result = $dashboard->getEmployeePayoutChartData ( $_REQUEST ['type'], $_SESSION ['current_payroll_month'], 10, $_SESSION ['employee_id'] );
		break;
	case "getEmployeeBirthdays" :
		$result = $dashboard->getEmployeeBirthdayData ( $days );
		break;
	case "getEmployeeAniversaries" :
		$result = $dashboard->getEmployeeAniversaryData ( $days );
		break;
	case "getUpcomingEvents" :
		$result = $dashboard->getUpcomingEventData ( $count );
		break;
	case "getTaxChartData" :
		$result = $dashboard->getTaxChartData ( $_SESSION ['employee_id'], $_REQUEST ['finYear'] );
		break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Slab " . $action . " Successfull";
	$resultObj [2] = $result;
} else if (isset($result [0]) === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Slab " . $action . " Successfull";
	$resultObj [2] = $result [0];
	$resultObj [3] = $result [1];
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Slab " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>