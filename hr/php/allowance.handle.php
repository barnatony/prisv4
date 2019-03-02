<?php
/*
 * ----------------------------------------------------------
 * Filename : allowance.handle.php
 * Classname: allowance.class.php
 * Author : Rufus Jackson
 * Database : company_pay_structure
 * Oper : General Deduction Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/allowance.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Operations to be performed */

/* Setting Variables */
$allowance = new Allowance ();
$allowance->allowance_id = isset ( $_REQUEST ['aId'] ) ? $_REQUEST ['change_alias'] . str_replace ( " ", "", strtolower ( $_REQUEST ['aId'] ) ) : null;
$allowance->display_name = isset ( $_REQUEST ['aName'] ) ? ucwords ( strtolower ( $_REQUEST ['aName'] ) ) : null;
$allowance->alias_name = isset ( $_REQUEST ['aId'] ) ? strtoupper ( $_REQUEST ['aId'] ) : null;
$allowance->data = isset ( $_REQUEST ['data'] ) ? $_REQUEST ['data'] : null;
$allowance->updated_by = $_SESSION ['login_id'];
// Tax exemptionoeration
$allowance->mappedwithPid = isset ( $_REQUEST ['payId'] ) ? $_REQUEST ['payId'] : null;
$allowance->exemption_id = isset ( $_REQUEST ['taxMapped'] ) ? $_REQUEST ['taxMapped'] : null;
// query pass as argument for select Function
$allowance->conn = $conn;
switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$branch->branch_id = "BR" . $rand;
		$branch->resp_person_emp_id = 'NIL';
		$result = $branch->insert ();
		break;
	case "getMappedExemptions" :
		$result = $allowance->getMappedExemptions ();
		break;
	case "update" :
		$result = $allowance->update ();
		break;
	case "delete" :
		$result = $allowance->delete ();
		break;
	case "add" :
		$result = $allowance->addAllowance ();
		break;
	case "create" :
		$result = $allowance->createAllowance ( $allowance->data );
		break;
	case "taxExemptionMapped" :
		$result = $allowance->mapAllowanceToExcemption ();
		break;
	case "nameChange" :
		$result = $allowance->renameAllowance ( $_REQUEST ['paystructureId'], $_REQUEST ['newName'], $_REQUEST ['aliasorfull'] );
		break;
	default :
		$result = FALSE;
}
if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Allowance " . $action . " Successfull";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Allowance " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>