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
$asset->employee_id = $_SESSION ['employee_id'];
$asset->asset_type = isset ( $_REQUEST ['atType'] ) ? $_REQUEST ['atType'] : "";
$asset->from_date = isset ( $_REQUEST ['fromDate'] ) ? $_REQUEST ['fromDate'] : "";
$asset->to_date = isset ( $_REQUEST ['toDate'] ) ? $_REQUEST ['toDate'] : "";
$asset->purpose = isset ( $_REQUEST ['purpose'] ) ? $_REQUEST ['purpose'] : "";
$asset->description = isset ( $_REQUEST ['desc'] ) ? $_REQUEST ['desc'] : "";
$asset->updated_by = $_SESSION ['employee_id'];
$asset->conn = $conn;

switch ($action) {
	case "insertByEmp" :
		$result = $asset->insertByEmp ();
		break;
	case "selectAssetRequestByEmp" :
		$result = $asset->selectAssetRequestByEmp ( $_SESSION ['employee_id'] );
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