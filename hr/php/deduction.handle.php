<?php
/*
 * ----------------------------------------------------------
 * Filename : generalDeductions.handle.php
 * Classname: generalDeductions.class.php
 * Author : Rufus Jackson
 * Database : company_deductions
 * Oper : General Deduction Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/deduction.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Operations to be performed */

$suffix = isset ( $_REQUEST ['calMethod'] ) ? $_REQUEST ['calMethod'] == 1 ? "|A" : "|P" : null;
$empShare = isset ( $_REQUEST ['empShare'] ) ? $_REQUEST ['empShare'] . $suffix : "0" . $suffix;
$emplrShare = isset ( $_REQUEST ['emplrShare'] ) ? isset ( $_REQUEST ['isBothC'] ) ? $_REQUEST ['isBothC'] == 0 ? "0" . $suffix : $_REQUEST ['emplrShare'] . $suffix : 0 : 0;
$adminC = isset ( $_REQUEST ['adminC'] ) ? isset ( $_REQUEST ['isAdminC'] ) ? $_REQUEST ['isAdminC'] == 0 ? "0" . $suffix : $_REQUEST ['adminC'] . $suffix : 0 : 0;
$deduce_in = isset ( $_REQUEST ['dedIn'] ) ? implode ( ",", $_REQUEST ['dedIn'] ) : "GROSS";
$due_in = isset ( $_REQUEST ['dueDate'] ) ? $_REQUEST ['dueDate'] . $_REQUEST ['dueMonth'] : null;

/* Setting Variables */
$deduction = new Deduction ();
// pay structure add arguments
$deduction->company_id = $temp [0];
$deduction->deduction_id = isset ( $_REQUEST ['dId'] ) ? $_REQUEST ['change_alias'] . str_replace ( " ", "", strtolower ( $_REQUEST ['dId'] ) ) : null;
$deduction->deduction_id_s = isset ( $_REQUEST ['dIds'] ) ? $_REQUEST ['dIds'] : null;
$deduction->display_name = isset ( $_REQUEST ['aName'] ) ? ucwords ( strtolower ( $_REQUEST ['aName'] ) ) : null;
$deduction->alias_name = isset ( $_REQUEST ['dId'] ) ? strtoupper ( $_REQUEST ['dId'] ) : null;

// create pay structure arguments
$deduction->data = isset ( $_REQUEST ['data'] ) ? $_REQUEST ['data'] : null;
// paystructure update class
$deduction->ualias_name = isset ( $_REQUEST ['aName'] ) ? strtoupper ( $_REQUEST ['aName'] ) : null;
$deduction->is_both_contribution = isset ( $_REQUEST ['isBothC'] ) ? $_REQUEST ['isBothC'] : null;
$deduction->is_admin_charges = isset ( $_REQUEST ['isAdminC'] ) ? $_REQUEST ['isAdminC'] : null;
$deduction->employee_share = $empShare;
$deduction->employer_share = $emplrShare;
$deduction->admin_charges = $adminC;
$deduction->deduce_in = $deduce_in;
$deduction->payment_to = isset ( $_REQUEST ['payTo'] ) ? $_REQUEST ['payTo'] : null;
$deduction->frequency = isset ( $_REQUEST ['freq'] ) ? $_REQUEST ['freq'] : null;
$deduction->max_employee_share = isset ( $_REQUEST ['maxEmpShare'] ) ? $_REQUEST ['maxEmpShare'] : null;
$deduction->cal_exemption = isset ( $_REQUEST ['calExemp'] ) ? $_REQUEST ['calExemp'] : null;
$deduction->max_employer_share = isset ( $_REQUEST ['maxEmplrShare'] ) ? $_REQUEST ['maxEmplrShare'] : null;
$deduction->due_in = $due_in;
$deduction->updated_by = $_SESSION ['login_id'];
$deduction->conn = $conn;

switch ($action) {
	case "select" :
		$result = $deduction->select ();
		break;
	case "ptSlab" :
		$result = $deduction->ptSlab ( $_REQUEST ['city'], $_REQUEST ['financialYear'] );
		break;
	case "itSlab" :
		$result = $deduction->itSlab($_REQUEST ['financialYear']);
		break;
	case "update" :
		$result = $deduction->update ();
		break;
	case "add" :
		$result = $deduction->addDeduction ();
		break;
	case "create" :
		$result = $deduction->createDeduction ( $deduction->data );
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Deduction " . $action . " Successfull";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Deduction " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>