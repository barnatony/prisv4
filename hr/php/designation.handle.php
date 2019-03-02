<?php
/*
 * ----------------------------------------------------------
 * Filename : designation.handle.php
 * Classname: designation.class.php
 * Author : Rufus Jackson
 * Database : company_designations
 * Oper : Department Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/designation.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$designation = new Designation ();
$designation->designation_id = isset ( $_REQUEST ['designId'] ) ? $_REQUEST ['designId'] : "";
$designation->designation_name = isset ( $_REQUEST ['design_name'] ) ? $_REQUEST ['design_name'] : "";
$designation->designation_hierarchy = isset ( $_REQUEST ['design_hier'] ) ? $_REQUEST ['design_hier'] : "";
$designation->updated_by = $_SESSION ['login_id'];
$designation->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$designation->designation_id = "DS" . $rand;
		$result = $designation->insert ();
		break;
	case "update" :
		$result = $designation->update ();
		break;
	case "delete" :
		$result = $designation->delete ();
		break;
	case "enable" :
		$result = $designation->setEnable ( 1 );
		break;
	case "disable" :
		$result = $designation->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Designation " . $action . " Successfull";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Designation " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>