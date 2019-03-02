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
class Project {
	
	var $conn;
	var $Title;
	var $category;
	var $description;
	var $priority;
	var $start_date;
	var $end_date;
	var $hours;
	var $creator;
	var $status;
	var $employeeId;
	var $reviewer;
	var $task_number;
	var $projectId;
	var $percentage;
	
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	function projectInsert($assignId){
		
		$rand = mt_rand ( 100, 9999999 );
		
		$clientId = "CLI" . $rand;		
		
		$query = "INSERT INTO project(project_id,project_title,project_catagory,description,creator,created_date,client,status,priority,start_date,end_date,hours,updated_by)
				  VALUES(?,?,?,?,?,NOW(),?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?,?)";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "ssssssssssss", $this->projectId,$this->Title,$this->category,$this->description,$this->creator,$clientId,$this->status,$this->priority,$this->start_date,$this->end_date,$this->hours,$this->creator);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		if($result){
			foreach ($assignId as $assignIds){
			$query = "INSERT INTO project_assignees(project_id,employee_id,updated_by)
				  VALUES(?,?,?)";
		$stmt = mysqli_prepare($this->conn, $query);
			mysqli_stmt_bind_param($stmt, "sss", $this->projectId,$assignIds,$this->creator);
			$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_stmt_error($stmt);
			}
			
			return $result;
		}
		
	}
	
	function taskDefault($projectId,$category,$startDate,$endDate,$hours,$creator,$reviewer,$priority,$team){

		$taskId = $projectId.'T'.'0';
		foreach ($reviewer as $reviewers){
			$reviewer = $reviewers;
		}
		$query = "INSERT INTO tasks(project_id,task_id,task_number,type,task_name,task_description,task_type,start_date,end_date,hours,status,creator,reviwer,created_date,priority,updated_by)
				  VALUES(?,?,'0',?,'Default','Default Task',?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?,'inisiated',?,?,NOW(),?,?)";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "sssssssssss",$projectId,$taskId,$category,$category,$startDate,$endDate,$hours,$creator,$reviewer,$priority,$creator );
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
	          $queryStmt = '';
			foreach($team as $teams){
				$queryStmt .= "('$taskId','$teams','$creator'),";
			}
			$queryStmt = substr($queryStmt,0,-1);
				$query = "INSERT INTO tasks_assignees(task_id,employee_id,updated_by)
				  VALUES$queryStmt";
				$stmt = mysqli_prepare($this->conn, $query);
				$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_stmt_error($this->conn);
			return $result;
		
	}
	
	function selectAllProject(){
		$json = array();
		$row = array();
		$query = "SELECT pro.project_id,pro.project_title,pro.status,(CASE WHEN DATE_FORMAT(pro.last_updated,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(pro.last_updated,'%h:%i  %p') 
WHEN DATE_FORMAT(pro.last_updated,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(pro.last_updated,'%e %b %Y  %h:%i %p') 
ELSE DATE_FORMAT(pro.last_updated,'%e/%c/%y  %h:%i %p') END) AS last_updated FROM project pro";
		$stmt = mysqli_prepare($this->conn, $query);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $projectId,$project_title,$status,$last_updated);
		while(mysqli_stmt_fetch($stmt)){
			$row['projectId'] = $projectId;
			$row['project_title'] = $project_title;
			$row['status'] = $status;
			$row['last_updated'] = $last_updated;
			array_push($json, $row);
		}
		return $json;
	}
	
	function taskInsert($assignId){
		
		$taskId = $this->projectId.'T'.$this->task_number;
		
		$query = "INSERT INTO tasks(project_id,task_id,task_number,type,task_name,task_description,task_type,start_date,end_date,hours,status,creator,reviwer,created_date,priority,updated_by)
				  VALUES(?,?,?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,NOW(),?,?)";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "sssssssssssssss",$this->projectId,$taskId,$this->task_number,$this->category,$this->Title,$this->description,$this->category,$this->start_date,$this->end_date,$this->hours,$this->status,$this->creator,$this->reviewer,$this->priority,$this->creator );
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		if($result){
			foreach ($assignId as $assignIds){
			
			$query = "INSERT INTO tasks_assignees(task_id,employee_id,updated_by)
				  VALUES(?,?,?)";
			$stmt = mysqli_prepare($this->conn, $query);
			
			mysqli_stmt_bind_param($stmt, "sss", $taskId,$assignIds,$this->creator);
			$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_stmt_error($this->conn);
			}
			return $result;
		}
		
	
	}
	
	function selectAllTask(){
		$json = array();
		$row = array();
		$query = "SELECT pro.task_number,pro.task_name,pro.status,(CASE WHEN DATE_FORMAT(pro.last_updated,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(pro.last_updated,'%h:%i  %p')
WHEN DATE_FORMAT(pro.last_updated,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(pro.last_updated,'%e %b %Y  %h:%i %p')
ELSE DATE_FORMAT(pro.last_updated,'%e/%c/%y  %h:%i %p') END) AS last_updated,pro.task_id FROM tasks pro";
		$stmt = mysqli_prepare($this->conn, $query);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $task_number,$task_name,$status,$last_updated);
		while(mysqli_stmt_fetch($stmt)){
			$row['task_number'] = $task_number;
			$row['task_name'] = $task_name;
			$row['status'] = $status;
			$row['last_updated'] = $last_updated;
			array_push($json, $row);
		}
		return $json;
	}
	function getProjectId($projectId){
		$query = "SELECT pro.project_id from project pro where pro.project_id=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "s", $projectId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $project_id);
		if(mysqli_stmt_fetch($stmt)){
			return false;
		}else{
			return true;
		}
	}
	function getTaskNumber($taskId){
		$query = "SELECT tas.task_number from tasks tas where tas.task_number=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "s", $taskId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $task_number);
		if(mysqli_stmt_fetch($stmt)){
			return false;
		}else{
			return true;
		}
	}
	function viewProjectById($projectId){
		$json = array();
		$query = mysqli_query($this->conn, "SELECT pro.project_id,pro.project_title,pro.project_catagory,pro.description,pro.creator,pro.status,pro.priority, DATE_FORMAT( pro.start_date , '%d/%m/%Y') start_date,
DATE_FORMAT( pro.end_date , '%d/%m/%Y') end_date,pro.hours,CONCAT(wrk.employee_name,' ', wrk.employee_lastname) employee_name,wrk.employee_id
FROM project pro
INNER JOIN project_assignees proass
ON pro.project_id = proass.project_id
INNER JOIN employee_work_details wrk
ON proass.employee_id = wrk.employee_id
WHERE pro.project_id='$projectId'");
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
			array_push($json, $row);
		}
		return $json;
		
	}
	function selectProjectByEmp($employee_id){
		$json = array();
		$row = array();
		$query = "SELECT proass.employee_id,pro.project_id,pro.project_title FROM project_assignees proass
LEFT JOIN project pro
ON proass.project_id = pro.project_id
WHERE proass.employee_id=?";
		$stmt = mysqli_prepare($this->conn,$query);
		mysqli_stmt_bind_param($stmt, "s", $employee_id);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $employee_id,$project_id,$project_title);
		while(mysqli_stmt_fetch($stmt)){
			$row['employee_id'] = $employee_id;
			$row['project_id'] = $project_id;
			$row['project_title'] = $project_title;
			array_push($json, $row);
			
		}
		return $json;
	}
	function viewProjectByIdandEmp($projectId,$employeeId){
		$json = array();
		$query = mysqli_query($this->conn, "SELECT pro.project_id,pro.project_title,pro.project_catagory,pro.description,pro.creator,pro.status,pro.priority, DATE_FORMAT( pro.start_date , '%d/%m/%Y') start_date,
                DATE_FORMAT( pro.end_date , '%d/%m/%Y') end_date,pro.hours,
                CONCAT(ewd.employee_name,' ',ewd.employee_lastname) employee_name,ewd.employee_id
                FROM project pro
                INNER JOIN project_assignees ps
        ON pro.project_id = ps.project_id 
        INNER JOIN employee_work_details ewd
                ON ps.employee_id = ewd.employee_id
                WHERE pro.project_id='$projectId'");
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
			array_push($json, $row);
		}
		return $json;
	
	}
	function viewTaskByIdandEmp($projectId){
		$json = array();
		$query = mysqli_query($this->conn, "SELECT  task.task_number,task.type,task.task_name,DATE_FORMAT( task.start_date , '%d/%m/%Y') task_start_date,
        DATE_FORMAT( task.end_date , '%d/%m/%Y') task_end_date,task.hours hours_task,CONCAT(wrk_det.employee_name,' ',wrk_det.employee_lastname) reviewer,
                CONCAT(ewd.employee_name,' ',ewd.employee_lastname) employee_name,ewd.employee_id,task.status task_status,task.percent_done,task.task_id,task.project_id
                FROM tasks task
                INNER JOIN employee_work_details wrk_det
                ON task.reviwer = wrk_det.employee_id
                INNER JOIN tasks_assignees tasa
                ON task.task_id = tasa.task_id  
                INNER JOIN employee_work_details ewd
                ON tasa.employee_id = ewd.employee_id
                WHERE task.project_id='$projectId' ");
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
			array_push($json, $row);
		}
		return $json;
	
	}
	function deleteTask($task_id,$employeeId){
		$query = "DELETE FROM tasks WHERE task_id=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt,"s",$task_id);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		
		return $result;
	}
	function viewReviewer($status,$employeeId){
		$json = array();
		$row = array();
		$status = str_replace(",", "\",\"", $status);
		$status = "\"".$status."\"";
		$query = "SELECT DATE_FORMAT(tim.`date`,'%Y/%m/%d') date,tim.from_time,tim.to_time,tim.hours,tim.comments,tim.status,CONCAT(pro.project_title ,' -> ',tas.task_name) task_name,tas.task_number,wrk.employee_name,tas.task_id,tim.employee_id,tas.status task_status,tas.percent_done FROM timesheet tim
INNER JOIN tasks tas
ON tas.task_id = tim.task_id
INNER JOIN project pro
ON tas.project_id = pro.project_id
INNER JOIN employee_work_details wrk
ON tim.employee_id = wrk.employee_id
WHERE tas.reviwer=? and tim.status IN ({$status})";
		$stmt = mysqli_prepare($this->conn,$query);
		mysqli_stmt_bind_param($stmt, "s", $employeeId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $date,$from_time,$to_time,$hours,$comments,$status,$task_name,$task_number,$employeeName,$task_id,$employee_id,$task_status,$percent_done);
		while(mysqli_stmt_fetch($stmt)){
			$row['date'] = $date;
			$row['from_time'] = $from_time;
			$row['to_time'] = $to_time;
			$row['hours'] = $hours;
			$row['comments'] = $comments;
			$row['status'] = $status;
			$row['task_name'] = $task_name;
			$row['task_number'] = $task_number;
			$row['employee_name'] = $employeeName;
			$row['task_id'] = $task_id;
			$row['employee_id'] = $employee_id;
			$row['task_status'] = $task_status;
			$row['percent_done'] = $percent_done;
			array_push($json, $row);
		}
		return $json;
	}
	function upateReviewerStatus(){
		$query = "UPDATE tasks tas SET tas.status=?,tas.percent_done=? WHERE tas.task_id=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt,"sss",$this->status,$this->percentage,$this->task_number);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		return $result;
	}
	function projectUpdate($assignId){
		$query = "UPDATE project SET project_title=?,project_catagory=?,description=?,start_date=STR_TO_DATE(?,'%d/%m/%Y'),end_date=STR_TO_DATE(?,'%d/%m/%Y'),hours=? WHERE project_id=? ";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "sssssss",$this->Title,$this->category,$this->description,$this->start_date,$this->end_date,$this->hours,$this->projectId);
		
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		if($result){
			foreach ($assignId as $assignIds){
				$query = "INSERT INTO project_assignees(project_id,employee_id)
				  VALUES(?,?) ON DUPLICATE KEY UPDATE project_id=?,employee_id=?";
				$stmt = mysqli_prepare($this->conn, $query);
				mysqli_stmt_bind_param($stmt, "ssss", $this->projectId,$assignIds,$this->projectId,$assignIds);
				$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_stmt_error($this->conn);					
			}
			return $result;
		}
	}
	function getAllTaskByEmp($employeeId){
		$json = array();
		$query = mysqli_query($this->conn, "SELECT  task.task_number,task.type,CONCAT(pro.project_title,' > ', task.task_name ) task_name,DATE_FORMAT( task.start_date , '%d/%m/%Y') start_date,
        DATE_FORMAT( task.end_date , '%d/%m/%Y') end_date,task.hours hours,CONCAT(wrk_det.employee_name,' ',wrk_det.employee_lastname) reviewer,
                CONCAT(ewd.employee_name,' ',ewd.employee_lastname) employee_name,ewd.employee_id,task.status status,task.percent_done,task.priority,task.task_id
                FROM tasks task
                INNER JOIN employee_work_details wrk_det
                ON task.reviwer = wrk_det.employee_id
                INNER JOIN tasks_assignees tasa
                ON task.task_id = tasa.task_id 
                INNER JOIN employee_work_details ewd
                ON tasa.employee_id = ewd.employee_id
                INNER JOIN project pro
                ON task.project_id = pro.project_id
                WHERE tasa.employee_id='$employeeId'");
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
			array_push($json, $row);
		}
		return $json;
	}
	function approveTimesheet($date,$fromTime,$employeeId){
	
		$date = str_replace(",", "\",\"", $date);
		$date = "\"".$date."\"";
		$fromTime = str_replace(",", "\",\"", $fromTime);
		$fromTime = "\"".$fromTime."\"";
		$employeeId = str_replace(",", "\",\"", $employeeId);
		$employeeId = "\"".$employeeId."\"";
		$query = "UPDATE timesheet SET status='approved' WHERE `date` IN ({$date}) and from_time IN ({$fromTime}) and employee_id IN ({$employeeId})";
		$stmt = mysqli_prepare($this->conn, $query);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		return $result;
	}
	function updateProjectStatus($projectId,$status){
		$query = "UPDATE project pro SET pro.status=? WHERE pro.project_id=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt,"ss",$status,$projectId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		return $result;
	
	}
	function AdminviewReviewer($status){
		$json = array();
		$row = array();
		$status = str_replace(",", "\",\"", $status);
		$status = "\"".$status."\"";
		$query = "SELECT w.employee_id,w.employee_name,DATE_FORMAT( t.date , '%Y/%m/%d') date,t.from_time,t.to_time,t.hours,t.comments,t.status
FROM timesheet t
INNER JOIN employee_work_details w
ON t.employee_id = w.employee_id
WHERE t.task_id IN ('permission','meeting','seminar','training') AND t.status IN({$status})";
		$stmt = mysqli_prepare($this->conn,$query);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $employee_id,$employee_name,$date,$from_time,$to_time,$hours,$comments,$status);
		while(mysqli_stmt_fetch($stmt)){
			$row['employee_id'] = $employee_id;
			$row['employee_name'] = $employee_name;
			$row['date'] = $date;
			$row['from_time'] = $from_time;
			$row['to_time'] = $to_time;
			$row['hours'] = $hours;
			$row['comments'] = $comments;
			$row['status'] = $status;
			array_push($json, $row);
		}
		return $json;
	}
	function rejectTimesheet($date,$fromTime,$employeeId){
	    $date = str_replace(",", "\",\"", $date);
		$date = "\"".$date."\"";
		$fromTime = str_replace(",", "\",\"", $fromTime);
		$fromTime = "\"".$fromTime."\"";
		$employeeId = str_replace(",", "\",\"", $employeeId);
		$employeeId = "\"".$employeeId."\"";
		$query = "UPDATE timesheet SET status='disputed' WHERE `date` IN ({$date}) and from_time IN ({$fromTime}) and employee_id IN ({$employeeId})";
		$stmt = mysqli_prepare($this->conn, $query);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		return $result;
	}
}