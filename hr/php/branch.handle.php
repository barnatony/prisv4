<?php
/*
 * ----------------------------------------------------------
 * Filename : branch.handle.php
 * Classname: branch.class.php
 * Author : Rufus Jackson
 * Database : company_branch
 * Oper : Branch Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/branch.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$branch = new Branch ();
$branch->branch_id = isset ( $_REQUEST ['bId'] ) ? $_REQUEST ['bId'] : "";
$branch->name = isset ( $_REQUEST ['bName'] ) ? $_REQUEST ['bName'] : "";
$branch->address = isset ( $_REQUEST ['bLoc'] ) ? $_REQUEST ['bLoc'] : "";
$branch->pin = isset ( $_REQUEST ['bPin'] ) ? $_REQUEST ['bPin'] : "";
$branch->state = isset ( $_REQUEST ['bstate'] ) ? $_REQUEST ['bstate'] : "";
$branch->city = isset ( $_REQUEST ['bCity'] ) ? $_REQUEST ['bCity'] : "";
$branch->pt_slab = isset ( $_REQUEST ['ptSlab'] ) ? str_replace ( "_", " ", $_REQUEST ['ptSlab'] ) : "";
$branch->pt = isset ( $_REQUEST ['bPt'] ) ? $_REQUEST ['bPt'] : "";
$branch->esi = isset ( $_REQUEST ['bEsi'] ) ? $_REQUEST ['bEsi'] : "";
$branch->pf = isset ( $_REQUEST ['bPf'] ) ? $_REQUEST ['bPf'] : "";
$branch->tan = isset ( $_REQUEST ['bTan'] ) ? $_REQUEST ['bTan'] : "";
$branch->resp_person_emp_id = isset ( $_REQUEST ['respPerson'] ) ? $_REQUEST ['respPerson'] : "NIL";
$branch->updated_by = $_SESSION ['login_id'];
$branch->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$branch->branch_id = "BR" . $rand;
		$branch->resp_person_emp_id = 'NIL';
		$result = $branch->insert ();
		break;
	case "update" :
		$result = $branch->update ();
		break;
	case "delete" :
		$result = $branch->delete ();
		break;
	case "enable" :
		$result = $branch->setEnable ( 1 );
		break;
	case "disable" :
		$result = $branch->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Branch " . $action . " Successfully";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Branch " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>