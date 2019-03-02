<?php
/*
 * ----------------------------------------------------------
 * Filename : jobstatus.handle.php
 * Classname: jobstatus.class.php
 * Author : Rufus Jackson
 * Database : company_job_statuses
 * Oper : Job Status Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/jobStatus.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$jobstatus = new JobStatus ();
$jobstatus->status_id = isset ( $_REQUEST ['jStatusId'] ) ? $_REQUEST ['jStatusId'] : "";
$jobstatus->name = isset ( $_REQUEST ['sname'] ) ? $_REQUEST ['sname'] : "";
$jobstatus->comments = isset ( $_REQUEST ['scmts'] ) ? $_REQUEST ['scmts'] : "";
$jobstatus->updated_by = $_SESSION ['login_id'];
$jobstatus->conn = $conn;

switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$jobstatus->status_id = "JS" . $rand;
		$result = $jobstatus->insert ();
		break;
	case "update" :
		$result = $jobstatus->update ();
		break;
	case "delete" :
		$result = $jobstatus->delete ();
		break;
	case "enable" :
		$result = $jobstatus->setEnable ( 1 );
		break;
	case "disable" :
		$result = $jobstatus->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Job Status " . $action . " Successfully";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Job Status " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>