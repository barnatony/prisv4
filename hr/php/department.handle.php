<?php
/*
 * ----------------------------------------------------------
 * Filename : department.handle.php
 * Classname: department.class.php
 * Author : Rufus Jackson
 * Database : company_departments
 * Oper : Department Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/department.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$department = new Department ();
$department->department_id = isset ( $_REQUEST ['dId'] ) ? $_REQUEST ['dId'] : "";
$department->name = isset ( $_REQUEST ['dName'] ) ? $_REQUEST ['dName'] : "";
$department->updated_by = $_SESSION ['login_id'];
$department->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$department->department_id = "DP" . $rand;
		$result = $department->insert ();
		break;
	case "update" :
		$result = $department->update ();
		break;
	case "delete" :
		$result = $department->delete ();
		break;
	case "enable" :
		$result = $department->setEnable ( 1 );
		break;
	case "disable" :
		$result = $department->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Department " . $action . " Successfull";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Department " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>