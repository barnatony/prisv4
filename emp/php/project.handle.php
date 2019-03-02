<?php
/*
 * ----------------------------------------------------------
 * Filename	: project.handle.php
 * Classname: project.class.php
 * Author	: sheik
 * Database	: project,project_assignees
 * Oper		: project Actions
 *
 * ----------------------------------------------------------
 */
require_once(dirname(dirname(dirname(__FILE__)))."/include/config.php");
/* Include Class Library */
require_once(dirname(dirname(dirname(__FILE__)))."/include/lib/project.class.php");
$temp = explode('!', base64_decode($_REQUEST['act']));
$action = $temp[1];
$resultObj = array();

/* Setting Variables */
$project = new Project();

$project->conn = $conn;


switch ($action) {
	
	case "taskInsert":
		$project->Title= $_REQUEST['title'];
		$project->category = $_REQUEST['type'];
		$project->description = $_REQUEST['description'];
		$project->priority = $_REQUEST['priority'];
		$project->start_date = $_REQUEST['start_date'];
		$project->end_date = $_REQUEST['end_date'];
		$project->hours = $_REQUEST['hours'];
		$project->creator = $_SESSION['employee_id'];
		$project->status = 'Inisiated';
		$project->reviewer = $_REQUEST['reviewer'];
		$project->task_number = $_REQUEST['taskNumber'];
		$project->projectId = $_REQUEST['projectId'];
		
		$result = $project->taskInsert($_REQUEST['employeeid']);
	break;
	case "viewProjectByIdandEmp":
		$result = $project->viewProjectByIdandEmp($_REQUEST['projectId'],$_SESSION['employee_id']);
		break;
	case "viewTaskByIdandEmp":
		$result = $project->viewTaskByIdandEmp($_REQUEST['projectId'],$_SESSION['employee_id']);
		break;
	case "deleteTask":
		$result = $project->deleteTask($_REQUEST['task_number'],$_REQUEST['employee_id']);
		break;
	case "viewReviewer":
		$result = $project->viewReviewer($_REQUEST['status'],$_SESSION['employee_id']);
		break;
	case "upateReviewerStatus":
		$project->task_number = $_REQUEST['task_number'];
		$project->status = $_REQUEST['status'];
		$project->percentage = $_REQUEST['percentage_com'];
		$result = $project->upateReviewerStatus();
		break;
	case "getTaskNumber":
		$result = $project->getTaskNumber($_REQUEST['taskId']);
		break;
	case "getTaskByEmp":
		$result = $project->getAllTaskByEmp($_SESSION['employee_id']);
		break;
	case "approveTimesheet":
		$result = $project->approveTimesheet($_REQUEST['date'],$_REQUEST['fromTime'],$_REQUEST['employee_id']);
		break;
	case "updateProjectStatus":
		$result = $project->updateProjectStatus($_REQUEST['projectId'],$_REQUEST['status']);
	break;
	default :
	$result = FALSE;
	exit;
}

if($result==TRUE){
	$resultObj[0]='success';
	$resultObj[1]="Project ".$action." Successfully";
	$resultObj[2] = $result;
}else{
	$resultObj[0]='error';
	$resultObj[1]="Project ".$action." Failed";
	$resultObj[2] = $result;
}
echo json_encode($resultObj);