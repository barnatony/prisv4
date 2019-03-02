<?php
/*
 * ----------------------------------------------------------
 * Filename : leaverules.handle.php
 * Classname: leaverules.class.php
 * Author : Rufus Jackson
 * Database : company_leaverules
 * Oper : leaverules Actions
 *
 * ----------------------------------------------------------
 */
require_once ("../../include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/leaveaccount.class.php");
error_reporting ( 0 );

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$leavepreceeding = array ();
$leave_account = new leave_account ();
/* Operations To Be Performed */

$leave_account->employee_id = isset ( $_REQUEST ['employee_id'] ) ? $_REQUEST ['employee_id'] : "";
$leave_account->lop = isset ( $_REQUEST ['lop'] ) ? $_REQUEST ['lop'] : "";
$leave_account->lop_sub = isset ( $_REQUEST ['lop_sub'] ) ? $_REQUEST ['lop_sub'] : "";
$leave_account->leave_rules = isset ( $_REQUEST ['leave_rules'] ) ? $_REQUEST ['leave_rules'] : "";
$leave_account->leave_account = isset ( $_REQUEST ['id'] ) ? $_REQUEST ['id'] : "";
$leave_account->updated_by = $_SESSION ['login_id'];
$leave_account->next_year = $_SESSION ['nextyear_date'];
$leave_account->current_year = $_SESSION ['financialYear'];
$leave_account->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$leave_account->rule_id = "LR" . $rand;
		$result = $leave_account->insert ();
		break;
	case "update" :
		$result = $leave_account->update ();
		break;
	case "delete" :
		$result = $leave_account->delete ();
		break;
	case "select" :
		$result = $leave_account->select ( $leave_account->leave_account, $leave_account->current_year, $leave_account->leave_rules );
		break;
	case "enable" :
		$result = $leave_account->setEnable ( 1 );
		break;
	case "disable" :
		$result = $leave_account->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "LeaveAccount " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "LeaveAccount " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>