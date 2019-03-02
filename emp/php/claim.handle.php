<?php
/*
 * ----------------------------------------------------------
 * Filename : claim.handle.php
 * Classname: claim.class.php
 * Author : Rufus
 * Database : claims
 * Oper : claims Actions
 *
 * ----------------------------------------------------------
 */
require_once ("../../include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/claim.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

$claim = new Claim ();
$claim->updated_by = $_SESSION ['employee_id'];
$claim->conn = $conn;
function dates($dated) {
	$date = $dated;
	$date = str_replace ( '/', '-', $date );
}
switch ($action) {
	case "Claiminsert" :
		$claim->claim_rule_id = $_REQUEST ['claimIds'];
		$claim->claim_name = $_REQUEST ['cName'];
		$claim->description = $_REQUEST ['description'];
		$claim->date_from = $_REQUEST ['amtFrom'];
		$claim->date_to = $_REQUEST ['amtTo'];
		$claim->amount = $_REQUEST ['amt'];
		$claim->reference_no = $_REQUEST ['reference_no'];
		$claim->bill_attachment = $_FILES ['attachment'];
		$result = $claim->employeeClaiminsert ( $claim->bill_attachment );
		break;
	case "viewClaimsByEmployee" :
		$result = $claim->viewClaimbyemployee ( $_SESSION ['employee_id'] );
		break;
	case "viewClamisbyId" :
		$result = $claim->viewClamisbyId ( $_REQUEST ['claimIds'], $_SESSION ['employee_id'] );
		break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "ClaimsRule " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "ClaimsRule " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>