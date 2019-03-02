<?php
/*
 * ----------------------------------------------------------
 * Filename : AssetTracker.handle.php
 * Classname: AssetTracker.class.php
 * Author : Rajsundari
 * Database : AssetTracker
 * Oper : AssetTracker Actions
 *
 * ----------------------------------------------------------
 */
require_once ("../../include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/assetTracker.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$assetTrc = new AssetTracker ();

$rand = mt_rand ( 10000, 99999 );
$assetTrc->asset_id = isset ( $_REQUEST ['assetId'] ) ? $_REQUEST ['assetId'] : "AT" . $rand;
$assetTrc->asset_name = isset ( $_REQUEST ['atName'] ) ? $_REQUEST ['atName'] : "";
$assetTrc->asset_type = isset ( $_REQUEST ['atType'] ) ? $_REQUEST ['atType'] : "";
$assetTrc->asset_condition = isset ( $_REQUEST ['atCondition'] ) ? $_REQUEST ['atCondition'] : "";
$assetTrc->purchase_date = isset ( $_REQUEST ['purDate'] ) ? $_REQUEST ['purDate'] : "";
$assetTrc->cost = isset ( $_REQUEST ['cost'] ) ? $_REQUEST ['cost'] : "";
$assetTrc->warranty_date = isset ( $_REQUEST ['wed'] ) ? $_REQUEST ['wed'] : "";
$assetTrc->serial_number = isset ( $_REQUEST ['sNo'] ) ? $_REQUEST ['sNo'] : "";
$assetTrc->manufacturer = isset ( $_REQUEST ['mfr'] ) ? $_REQUEST ['mfr'] : "";
$assetTrc->model = isset ( $_REQUEST ['model'] ) ? $_REQUEST ['model'] : "";
$assetTrc->status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "";
$assetTrc->updated_by = $_SESSION ['login_id'];
$assetTrc->conn = $conn;

switch ($action) {
	case "insert" :
		$result = $assetTrc->insert ();
		break;
	case "update" :
		$result = $assetTrc->update ();
		break;
	case "select" :
		$result = $assetTrc->select ();
		break;
	case "enable" :
		$result = $assetTrc->setEnable ( 1 );
		break;
	case "disable" :
		$result = $assetTrc->setEnable ( 0 );
		break;
	case "delete" :
		$result = $assetTrc->delete ();
		break;
	case "checkAssetId" :
		$result = $assetTrc->checkAssetId ( $_REQUEST ['assetId'] );
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "AssetTracker " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "AssetTracker " . $action . " Failed";
	$resultObj [2] = $result;
}

echo json_encode ( $resultObj );
?>