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

/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/letter.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");


$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );

$action = $temp[1];

$resultObj = array ();

/* Setting Variables */
$letter = new Letter();

$letter->letterName = isset ( $_REQUEST ['letterName'] ) ? $_REQUEST ['letterName'] : "";
$letter->updated_by = isset ( $_SESSION ['login_id'] ) ? $_SESSION ['login_id'] : $_SESSION ['employee_id'];
$letter->conn = $conn;

switch ($action) {
	case "update" :
		$letter->letterContent = isset ( $_REQUEST ['letterContent'] ) ? $_REQUEST ['letterContent'] : "";
		$resultset = $letter->update ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	
	case "getHtml" :
		$resultset = $letter->getHtml();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "select" :
		$result = $letter->select ();
		break;
	case "download" :
		$id = isset($_REQUEST ['actionId'])?$_REQUEST ['actionId']:"";
		$result = $letter->download ( $id, $letter->letterName);
		
		break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Your Letter " . $action . " Successfully";
	$resultObj [2] = (isset($data)?$data:$result);
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = " Your Letter " . $action . " Failed";
	$resultObj [2] = (isset($data)?$data:$result);
}
echo json_encode ( $resultObj );
?>