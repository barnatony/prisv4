<?php
/*
 * ----------------------------------------------------------
 * Filename	: timesheet.handle.php
 * Classname: timesheet.class.php
 * Author	: sheik
 * Database	: timesheet
 * Oper		: timesheet Actions
 *
 * ----------------------------------------------------------
 */
require_once(dirname(dirname(dirname(__FILE__)))."/include/config.php");
/* Include Class Library */
require_once(dirname(dirname(dirname(__FILE__)))."/include/lib/timesheet.class.php");
$temp = explode('!', base64_decode($_REQUEST['act']));
$action = $temp[1];
$resultObj = array();

/* Setting Variables */
$timesheet = new Timesheet();

$timesheet->conn = $conn;


switch ($action) {
	
	case "getProject":
		$projectId = explode('T',$_REQUEST['projectId']);
		$result = $timesheet->getProject($_SESSION['employee_id'],$projectId[0]);
	break;
	
	case "getTimesheet":
		$result = $timesheet->getTimesheet($_REQUEST['date'], $_SESSION['employee_id']);
	break;
	
	case "insertTimesheet":
		
		$timesheet->projectId = $_REQUEST['project'];
		if($_REQUEST['corporate_task'] == 'no_cop' && $_REQUEST['Type'] == 'no_per'){
		$timesheet->taskId = $_REQUEST['task'];
		}else if($_REQUEST['Type'] != 'no_per'){
		 $timesheet->taskId = $_REQUEST['Type'];
		}else{
		$timesheet->taskId = $_REQUEST['corporate_task'];
		}
		$timesheet->date = $_REQUEST['from_datetime'];
		$timesheet->from_time = $_REQUEST['from_date'];
		$timesheet->to_time = $_REQUEST['to_date'];
		$timesheet->hours = $_REQUEST['hours'];
		$timesheet->comments = $_REQUEST['comments'];
		$timesheet->status = 'Saved';
		$result = $timesheet->setTimesheet($timesheet->date, $_SESSION['employee_id']);
	break;
	case "getDataFromTime":
	$result = $timesheet->getDataFromTime($_REQUEST['fromTime'],$_REQUEST['toTime'],$_REQUEST['date'],$_SESSION['employee_id']);
	break;
	case "getDataByDate":
	$result = $timesheet->getDataByDate($_REQUEST['from_date'],$_REQUEST['to_date'],$_REQUEST['status'],$_SESSION['employee_id']);
	break;
	case "submitTimesheet":
	$result = $timesheet->submitTimesheet($_REQUEST['date'],$_REQUEST['fromTime'],$_SESSION['employee_id']);
	break;
	case "deleteTimesheet":
	$result = $timesheet->deleteTimesheet($_REQUEST['date'],$_REQUEST['fromTime'],$_SESSION['employee_id']);	
	break;
	default :
	$result = FALSE;
	exit;
}

if($result==TRUE){
	$resultObj[0]='success';
	$resultObj[1]="Timesheet ".$action." Successfully";
	$resultObj[2] = $result;
}else{
	$resultObj[0]='error';
	$resultObj[1]="Timesheet ".$action." Failed";
	$resultObj[2] = $result;
}
echo json_encode($resultObj);