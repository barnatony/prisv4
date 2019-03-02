<?php
/*
 * ----------------------------------------------------------
 * Filename : paymentMode.handle.php
 * Classname: paymentMode.class.php
 * Author : Rufus Jackson
 * Database : company_payment_modes
 * Oper : Payment Mode Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/paymentMode.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$payment_mode = new PaymentMode ();
$payment_mode->payment_mode_id = isset ( $_REQUEST ['pId'] ) ? $_REQUEST ['pId'] : "";
$payment_mode->name = isset ( $_REQUEST ['name'] ) ? $_REQUEST ['name'] : "";
$payment_mode->bank_name = isset ( $_REQUEST ['bName'] ) ? $_REQUEST ['bName'] : "";
$payment_mode->bank_ac_no = isset ( $_REQUEST ['acNo'] ) ? $_REQUEST ['acNo'] : "";
$payment_mode->bank_branch = isset ( $_REQUEST ['branch_name'] ) ? $_REQUEST ['branch_name'] : "";
$payment_mode->bank_ifsc = isset ( $_REQUEST ['ifsc'] ) ? $_REQUEST ['ifsc'] : "";
$payment_mode->account_type = isset ( $_REQUEST ['account_type'] ) ? $_REQUEST ['account_type'] : "";
$payment_mode->updated_by = $_SESSION ['login_id'];
$payment_mode->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$payment_mode->payment_mode_id = "PM" . $rand;
		$result = $payment_mode->insert ();
		break;
	case "update" :
		$result = $payment_mode->update ();
		break;
	case "delete" :
		$result = $payment_mode->delete ();
		break;
	case "enable" :
		$result = $payment_mode->setEnable ( 1 );
		break;
	case "disable" :
		$result = $payment_mode->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Payment Mode " . $action . " Successfull";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Payment Mode " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>