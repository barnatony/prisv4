<?php
/*
 * ----------------------------------------------------------
 * Filename : noticePeriod.handle.php
 * Classname: noticePeriod.class.php
 * Author : Raja Sundari
 * Database : emp_notice_period
 * Oper : NoticePeriod Actions
 *
 * ----------------------------------------------------------
 */
require_once ("../../include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/noticePeriod.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$noticePeriod = new noticePeriod ();
$noticePeriod->affected_ids = isset ( $_REQUEST ['affectIds'] ) ? $_REQUEST ['affectIds'] : "";
$noticePeriod->notice_date = isset ( $_REQUEST ['nDate'] ) ? $_REQUEST ['nDate'] : "";
$noticePeriod->last_date = isset ( $_REQUEST ['lDate'] ) ? $_REQUEST ['lDate'] : "";
$noticePeriod->reason = isset ( $_REQUEST ['reasonCode'] ) ? $_REQUEST ['reasonCode'] : "Nil";
$noticePeriod->letter_text = isset ( $_REQUEST ['letterCode'] ) ? $_REQUEST ['letterCode'] : "Nil";
$noticePeriod->remark = isset ( $_REQUEST ['remark'] ) ? $_REQUEST ['remark'] : "Nil";
$noticePeriod->process_type = isset ( $_REQUEST ['processType'] ) ? $_REQUEST ['processType'] : "SP";
$noticePeriod->status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "W";
$noticePeriod->updated_by = isset ( $_SESSION ['employee_id'] ) ? $_SESSION ['employee_id'] : $_SESSION ['employee_name'];
$noticePeriod->conn = $conn;
switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$noticePeriod->noticePeriodId = "NP" . $rand;
		$result = $noticePeriod->insert ();
		break;
	case "update" :
		$result = $noticePeriod->update ();
		break;
	case "delete" :
		$result = $noticePeriod->delete ();
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Notice Period " . $action . " Successfully";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Notice Period " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>