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
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/team.class.php");


$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$team = new team();
$team->team_id = isset ( $_REQUEST ['teamId'] ) ? $_REQUEST ['teamId'] : "";
$team->name = isset ( $_REQUEST ['team_Name'] ) ? $_REQUEST ['team_Name'] : "";
$team->updated_by = $_SESSION ['login_id'];
$team->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$team->team_id = "TM" . $rand;
		$result = $team->insert ();
		break;
	case "update" :
		$result = $team->update ();
		break;
	case "delete" :
		$result = $team->delete ();
		break;
	case "enable" :
		$result = $team->setEnable ( 1 );
		break;
	case "disable" :
		$result = $team->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Team " . $action . " Successfully";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Team " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>
