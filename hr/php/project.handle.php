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
	
	case "projectInsert":
		$project->Title= $_REQUEST['title'];
		$project->category = $_REQUEST['type'];
		$project->description = $_REQUEST['description'];
		$project->priority = $_REQUEST['priority'];
		$project->start_date = $_REQUEST['start_date'];
		$project->end_date = $_REQUEST['end_date'];
		$project->hours = $_REQUEST['hours'];
		$project->creator = $_SESSION['login_id'];
		$project->status = 'Inisiated';	
		$project->projectId = $_REQUEST['projectIds'];
		$project->projectInsert($_REQUEST['assignedTo']);
		$result = $project->taskDefault($project->projectId,$project->category,$project->start_date,$project->end_date,$project->hours,$project->creator,$_REQUEST['assignedTo'],$project->priority,$_REQUEST['teamMembers']);
	break;
	case "selectAllProject":
		$result = $project->selectAllProject();
	break;	
	case "viewTaskById":
		$result = $project->viewTaskByIdandEmp($_REQUEST['projectId']);
	break;
	case "taskInsert":
		$project->Title= $_REQUEST['title'];
		$project->category = $_REQUEST['type'];
		$project->description = $_REQUEST['description'];
		$project->priority = $_REQUEST['priority'];
		$project->start_date = $_REQUEST['start_date'];
		$project->end_date = $_REQUEST['end_date'];
		$project->hours = $_REQUEST['hours'];
		$project->creator = $_SESSION['login_id'];
		$project->status = 'Inisiated';
		$project->reviewer = $_REQUEST['reviewer'];
		$project->task_number = $_REQUEST['taskNumber'];
		$project->projectId = $_REQUEST['projectId'];
		
		$result = $project->taskInsert($_REQUEST['assignedTo']);
	break;
	case "selectAllTask":
		$result = $project->selectAllTask();
		break;
	case "getProjectId":
		$result  = $project->getProjectId($_REQUEST['projectId']);
		break;
	case "getTaskNumber":
		$result = $project->getTaskNumber($_REQUEST['taskId']);
		break;
	case "viewProjectById":
		$result = $project->viewProjectById($_REQUEST['projectId']);
		break;
	case "projectUpdate":
		$project->Title= $_REQUEST['title'];
		$project->category = $_REQUEST['type'];
		$project->description = $_REQUEST['description'];
		$project->start_date = $_REQUEST['start_date'];
		$project->end_date = $_REQUEST['end_date'];
		$project->hours = $_REQUEST['hours'];
		$project->projectId = $_REQUEST['projectIds'];
		$result = $project->projectUpdate($_REQUEST['assignedTo']);
	break;
	case "AdminviewReviewer":
		$result = $project->AdminviewReviewer($_REQUEST['status']);
	break;
	case "approveTimesheet":
		$result = $project->approveTimesheet($_REQUEST['date'],$_REQUEST['fromTime'],$_REQUEST['employee_id']);
	break;
	case "rejectTimesheet":
		$result = $project->rejectTimesheet($_REQUEST['date'],$_REQUEST['fromTime'],$_REQUEST['employee_id']);
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