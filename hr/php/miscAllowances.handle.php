<?php
/*
 * ----------------------------------------------------------
 * Filename : miscPayments.handle.php
 * Classname: miscPayments.class.php
 * Author : Raja Sundari
 * Database : company_pay_structure
 * Oper : General miscPayments Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/miscAllowances.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$miscPay = new MiscAllowances ();
/* Operations to be performed */
$miscPay->payment_for ='E';
$miscPay->cal = isset ( $_REQUEST ['cal'] ) ? $_REQUEST ['cal'] : null;
$miscPay->pAmount = isset ( $_REQUEST ['Amount'] ) ? $_REQUEST ['Amount'] : null;
$miscPay->pIn = isset ( $_REQUEST ['In'] ) ? $_REQUEST ['In'] : [ ]; // array Declaration
$miscPay->pay_affected_ids=(isset($_REQUEST['affectedIds'])?$_REQUEST ['affectedIds']:'');

/* Setting Variables */
$miscPay->remarks = isset ( $_REQUEST ['remarks'] ) ? $_REQUEST ['remarks'] : null;
$miscPay->pay_category = isset ( $_REQUEST ['pCategory'] ) ? $_REQUEST ['pCategory'] : null;
$miscPay->payment_amount = ($miscPay->cal == '1') ? $miscPay->pAmount . "|A" : $miscPay->pAmount . "|P";
$miscPay->payments_in = ($miscPay->cal == '1') ? "NA" : implode ( ",", $miscPay->pIn );
$miscPay->repetition_count = isset ( $_REQUEST ['count'] ) ? $_REQUEST ['count'] : null;
$miscPay->effects_from = isset ( $_REQUEST ['effectsFrom'] ) ? $_REQUEST ['effectsFrom'] : null;
$miscPay->payment_id = isset ( $_REQUEST ['payId'] ) ? $_REQUEST ['payId'] : null;
$miscPay->pay_id = isset ( $_REQUEST['pId'] ) ? $_REQUEST['pId'] : null;
$miscPay->miscPay = isset ( $_REQUEST ['mpId'] ) ? $_REQUEST ['change_alias'] . str_replace ( " ", "", strtolower ( $_REQUEST ['mpId'] ) ) : null;
$miscPay->display_name = isset ( $_REQUEST ['aName'] ) ? ucwords ( strtolower ( $_REQUEST ['aName'] ) ) : null;
$miscPay->alias_name = isset ( $_REQUEST ['mpId'] ) ? strtoupper ( $_REQUEST ['mpId'] ) : null;
$miscPay->data = isset ( $_REQUEST ['data'] ) ? $_REQUEST ['data'] : null;
$miscPay->updated_by = $_SESSION ['login_id'];
$miscPay->conn = $conn;

switch ($action) {
	case "add" :
		$rand = mt_rand ( 10000, 99999 );
		$miscPay->pay_structure_id = "MP" . $rand;
		$result = $miscPay->add ();
		break;
	case "create" :
		$result = $miscPay->createMiscpay ( $miscPay->data );
		break;
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$miscPay->payment_id = "PAY" . $rand;
		$resultset = $miscPay->insert ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "update" :
		$resultset = $miscPay->update ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "enable" :
		$result = $miscPay->setEnable ( 1 );
		break;
	case "disable" :
		$result = $miscPay->setEnable ( 0 );
		break;
	case "getgroupEmployee" :
		$result = $miscPay->getgroupIds();
		break;
	
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "MiscAllowance " . $action . " Successfull";
	$resultObj [2] = isset($data)?$data:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "MiscAllowance " . $action . " Failed";
	$resultObj [2] = isset($data)?$data:$result;
}
echo json_encode ( $resultObj );
?>