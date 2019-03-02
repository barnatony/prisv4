<?php
/*
 * ----------------------------------------------------------
 * Filename : claimRules.handle.php
 * Classname: claimRules.class.php
 * Author : Rajsundari
 * Database : claimsRules
 * Oper : claimsRules Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/claim.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$claim = new ClaimRule ();

/* Claim MAppedFunction */
$claim->claimId_mapp = isset ( $_REQUEST ['claimIds'] ) ? $_REQUEST ['claimIds'] : "";
$claim->applicable_for = isset ( $_REQUEST ['applicableFor'] ) ? $_REQUEST ['applicableFor'] : "";
$claim->applicable_id = isset ( $_REQUEST ['affectedIds'] ) ? $_REQUEST ['affectedIds'] : "";

$rand = mt_rand ( 10000, 99999 );
$claim->claim_id = isset ( $_REQUEST ['cId'] ) ? $_REQUEST ['cId'] : "CR" . $rand;
$claim->claim_name = isset ( $_REQUEST ['cName'] ) ? $_REQUEST ['cName'] : "";
$claim->alias_name = isset ( $_REQUEST ['cAlias'] ) ? $_REQUEST ['cAlias'] : "";
$claim->category_type = isset ( $_REQUEST ['type'] ) ? $_REQUEST ['type'] : "";
$claim->sub_type = isset ( $_REQUEST ['typeClaims'] ) ? $_REQUEST ['typeClaims'] : "";
$claim->class = isset ( $_REQUEST ['classClaims'] ) ? $_REQUEST ['classClaims'] : "";
$claim->amount_from = isset ( $_REQUEST ['amtFrom'] ) ? $_REQUEST ['amtFrom'] : "";
$claim->amount_to = isset ( $_REQUEST ['amtTo'] ) ? $_REQUEST ['amtTo'] : "";

$claim->updated_by = $_SESSION ['login_id'];
$claim->conn = $conn;

switch ($action) {
	case "insert" :
		$result = $claim->insert ();
		break;
	case "update" :
		$result = $claim->update ();
		break;
	case "MappedWith" :
		$result = $claim->MappedWith ();
		break;
	case "select" :
		$result = $claim->select ();
		break;
	case "selectMappedClaim" :
		$result = $claim->selectMappedClaim ();
		break;
	case "enable" :
		$result = $claim->setEnable ( 1 );
		break;
	case "disable" :
		$result = $claim->setEnable ( 0 );
		break;
	case "delete" :
		$result = $claim->delete ();
		break;
	case "getAllClaim" :
		$result = $claim->getAllClaim ();
		break;
	case "getAllProcessedClaim" :
		$result= $claim->getProcessedClaim();
		break;
	case "getProcessedClaims":
		$result=$claim->getProcessedClaims($_REQUEST['processed_on']);
		break;
	case "viewClamisbyInd" :
		$result = $claim->viewClamisbyInd ( $_REQUEST ['claimIds'] );
		break;
	case "updateClaimbyemployee" :
		$claim->amount_approve = str_replace ( ',', '', $_REQUEST ['approved_amount'] );
		$claim->admin_remarks = $_REQUEST ['remarks'];
		$claim->status = $_REQUEST ['status'];
		$result = $claim->updateClaimbyemployee ( $_REQUEST ['claimId'] );
		break;
	case "showProcessClaimByEmployee" :
		$claim->employee_id = $_REQUEST ['process_claim_employee'];
		$result = $claim->showProcessClaimByEmployee ();
		break;
	case "showProcessClaimByDate" :
		$result = $claim->showProcessClaimByDate ( $_REQUEST ['from_date'], $_REQUEST ['to_date'] );
		break;
	case "processClaim" :
		$result = $claim->processClaim ( $_REQUEST ['claimId'] );
		break;
	case "downloadGeneratePdf" :
		$result = $claim->generateClaimPdf ( $_REQUEST ['claimId'], $_REQUEST ['name_pdf'] );
		break;
	case "employeeInClaims" :
		$result = $claim->employeeInClaims ();
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