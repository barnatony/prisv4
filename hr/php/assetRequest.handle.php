<?php
/*
 * ----------------------------------------------------------
 * Filename : assetRequest.handle.php
 * Classname: assetRequest.class.php
 * Author : Rufus
 * Database : asset_request
 * Oper : assetRequest Actions
 *
 * ----------------------------------------------------------
 */
require_once ("../../include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/assetRequest.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

$asset = new AssetRequest ();

$rand = mt_rand ( 10000, 99999 );
$asset->request_id = isset ( $_REQUEST ['request_Id'] ) ? $_REQUEST ['request_Id'] : "REQ" . $rand;
$asset->employee_id = isset ( $_REQUEST ['employee_id'] ) ? $_REQUEST ['employee_id'] : "";
$asset->asset_type = isset ( $_REQUEST ['atType'] ) ? $_REQUEST ['atType'] : "";
$asset->from_date = isset ( $_REQUEST ['fromDate'] ) ? $_REQUEST ['fromDate'] : "";
$asset->to_date = isset ( $_REQUEST ['toDate'] ) ? $_REQUEST ['toDate'] : "";
$asset->purpose = isset ( $_REQUEST ['purpose'] ) ? $_REQUEST ['purpose'] : "";
$asset->description = isset ( $_REQUEST ['desc'] ) ? $_REQUEST ['desc'] : "";
$asset->status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "";
$asset->asset_id = isset ( $_REQUEST ['asset_id'] ) ? $_REQUEST ['asset_id'] : "";
$asset->issue_on = isset ( $_REQUEST ['issued_on'] ) ? $_REQUEST ['issued_on'] : "";
$asset->issue_notes = isset ( $_REQUEST ['issued_notes'] ) ? $_REQUEST ['issued_notes'] : "";
$asset->return_on = isset ( $_REQUEST ['return_on'] ) ? $_REQUEST ['return_on'] : "";
$asset->return_notes = isset ( $_REQUEST ['return_notes'] ) ? $_REQUEST ['return_notes'] : "";
$asset->updated_by = $_SESSION ['login_id'];
$asset->conn = $conn;

switch ($action) {
	case "insert" :
		$result = $asset->insert ();
		break;
	case "update" :
		$result = $asset->update ( $_REQUEST ['requestId'] );
		break;
	case "selectAsset" :
		$result = $asset->selectAsset ( $_REQUEST ['type'] );
		break;
	case "selectAssetRequest" :
		$result = $asset->selectAssetRequest ();
		break;
	case "selectAssetRequestById" :
		$result = $asset->selectAssetRequestById ( $_REQUEST ['request_Id'] );
		break;
	case "downloadGeneratePdf" :
		$result = $asset->downloadGeneratePdf ( $_REQUEST ['request_Id'], $_REQUEST ['employee_id'] );
		break;
	default :
		$result = false;
		exit ();
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "AssetRequest " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "AssetRequest " . $action . " Failed";
	$resultObj [2] = $result;
}

echo json_encode ( $resultObj );