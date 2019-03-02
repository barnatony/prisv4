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
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/dashboard.class.php");

// $temp = explode('!', base64_decode($_REQUEST['act']));
// $action = $temp[1];
$action = $_REQUEST ['act'];
$resultObj = array ();

/* Operations to be performed */

/* Setting Variables */
$dashboard = new Dashboard ();
$dashboard->updated_by = $_SESSION ['login_id'];
$dashboard->conn = $conn;

switch ($action) {
	case "getChartData" :
		$result = $dashboard->getPayoutChartData ( $_REQUEST ['type'], $_SESSION ['current_payroll_month'], 10 );
		break;
	case "getEmployeeWidgetData" :
		$result = $dashboard->getEmployeeWidgetData ( $_SESSION ['current_payroll_month'] );
		break;
	case "getEmployeeLastlogins":
		$result = $dashboard->getEmployeeLastloginData (20);
		break;
	case "getEmployeePendingItDeclarations" :
		$result = $dashboard->getPendingItDeclarationData ( $_SESSION ['financialYear'] , 20 );
		break;
	case "getEmployeeBirthdays" :
		$result = $dashboard->getEmployeeBirthdayData ( $days );
		break;
	case "getEmployeeAniversaries" :
		$result = $dashboard->getEmployeeAniversaryData ( $days );
		break;
	case "getEmployeelrRq" :
		$result = $dashboard->getEmployeeleaveRequest ();
		break;
	case "getUpcomingEvents" :
		$result = $dashboard->getUpcomingEventData ( $count );
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