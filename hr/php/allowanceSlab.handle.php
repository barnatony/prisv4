<?php
/*
 * ----------------------------------------------------------
 * Filename : allowanceSlab.handle.php
 * Classname: allowanceSlab.class.php
 * Author : Rufus Jackson
 * Database : company_allowance_slabs
 * Oper : Allowance Slab Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/allowanceSlab.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Operation */
$values = array ();
if (isset ( $_REQUEST ['allowancesColumn'] )) {
	for($i = 0; $i < count ( $_REQUEST ['allowancesColumn'] ); $i ++) {
		$values [$i] = $_REQUEST [$_REQUEST ['allowancesColumn'] [$i]];
	}
}
/* Setting Variables */
$slab = new AllowanceSlab ();
$slab->slab_id = isset ( $_REQUEST ['sId'] ) ? $_REQUEST ['sId'] : "SL" . mt_rand ( 10000, 99999 );
$slab->slab_name = isset ( $_REQUEST ['sName'] ) ? $_REQUEST ['sName'] : null;
//$slab->slab_type = isset ( $_REQUEST ['sType'] ) ? $_REQUEST ['sType'] : null;
$slab->slab_type = "gross";
$slab->min_salary_amount = isset ( $_REQUEST ['minSalary'] ) ? $_REQUEST ['minSalary'] : null;
$slab->updated_by = $_SESSION ['login_id'];
$slab->slab_ed = isset ( $_REQUEST ['s_id'] ) ? $_REQUEST ['s_id'] : null;
$slab->conn = $conn;
switch ($action) {
	case "insert" :
		$allow_columns = array ();
		$result = $slab->insert ( $_REQUEST ['allowancesColumn'], $values );
		break;
	case "update" :
		$result = $slab->updateName ( $slab->slab_ed, $slab->slab_name );
		break;
	case "delete" :
		$result = $slab->delete ();
		break;
	case "enable" :
		$result = $slab->setEnable ( 1 );
		break;
	case "disable" :
		$result = $slab->setEnable ( 0 );
		break;
	case "selectByType" :
		$result = $slab->selectByType ( $_REQUEST ['sType'] );
		break;
	case "header_select" :
		$result = $slab->select_header ();
		break;
	default :
		$result = FALSE;
}

if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Slab " . $action . " Successfull";
	$resultObj [2] = $result;
} else if ($result [0] === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Slab " . $action . " Successfull";
	$resultObj [2] = $result [0];
	$resultObj [3] = $result [1];
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Slab " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>