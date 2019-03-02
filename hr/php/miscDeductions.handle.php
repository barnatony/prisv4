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
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/miscDeductions.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Operations to be performed */
/* Setting Variables */
$miscDedu = new MiscDeuctions ();

$miscDedu->deduction_for = 'E';
$miscDedu->cal = isset ( $_REQUEST ['cal'] ) ? $_REQUEST ['cal'] : null;
$miscDedu->dAmount = isset ( $_REQUEST ['Amount'] ) ? $_REQUEST ['Amount'] : null;
$miscDedu->dIn = isset ( $_REQUEST ['In'] ) ? $_REQUEST ['In'] : [ ]; // array Declaration
$miscDedu->dedu_affected_ids=(isset($_REQUEST['affectedIds'])?$_REQUEST ['affectedIds']:'');


/* Setting Variables */
$miscDedu->remarks = isset ( $_REQUEST ['remarks'] ) ? $_REQUEST ['remarks'] : null;
$miscDedu->dedu_category = isset ( $_REQUEST ['dCategory'] ) ? $_REQUEST ['dCategory'] : null;
$miscDedu->deduction_amount = ($miscDedu->cal == '1') ? $miscDedu->dAmount . "|A" : $miscDedu->dAmount . "|P";
$miscDedu->deductions_in = ($miscDedu->cal == '1') ? "NA" : implode ( ",", $miscDedu->dIn );
$miscDedu->repetition_count = isset ( $_REQUEST ['count'] ) ? $_REQUEST ['count'] : null;
$miscDedu->effects_from = isset ( $_REQUEST ['effectsFrom'] ) ? $_REQUEST ['effectsFrom'] : null;
$miscDedu->deduction_id = isset ( $_REQUEST ['deducId'] ) ? $_REQUEST ['deducId'] : null;

$miscDedu->miscdeduc = isset ( $_REQUEST ['mdId'] ) ? $_REQUEST ['change_alias'] . str_replace ( " ", "", strtolower ( $_REQUEST ['mdId'] ) ) : null;
$miscDedu->display_name = isset ( $_REQUEST ['aName'] ) ? ucwords ( strtolower ( $_REQUEST ['aName'] ) ) : null;
$miscDedu->alias_name = isset ( $_REQUEST ['mdId'] ) ? strtoupper ( $_REQUEST ['mdId'] ) : null;
$miscDedu->data = isset ( $_REQUEST ['data'] ) ? $_REQUEST ['data'] : null;
$miscDedu->updated_by = $_SESSION ['login_id'];
$miscDedu->conn = $conn;

switch ($action) {
	case "add" :
		$rand = mt_rand ( 10000, 99999 );
		$miscDedu->pay_structure_id = "MD" . $rand;
		$result = $miscDedu->add ();
		break;
	case "create" :
		$result = $miscDedu->createMiscdeduc ( $miscDedu->data );
		break;
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$miscDedu->deduction_id = "PAY" . $rand;
		$resultset = $miscDedu->insert ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "update" :
		$resultset = $miscDedu->update ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "enable" :
		$result = $miscDedu->setEnable ( 1 );
		break;
	case "disable" :
		$result = $miscDedu->setEnable ( 0 );
		break;
	case "getgroupEmployee" :
		$result = $miscDedu->getgroupIds();
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "MiscAllowance " . $action . " Successfull";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "MiscAllowance " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>