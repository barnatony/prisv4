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

class Timesheet {
	var $conn;
	var $projectId;
	var $taskId;
	var $date;
	var $from_time;
	var $to_time;
	var $hours;
	var $comments;
	var $status;
	
	
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	function getProject($employeeId,$projectId){
		$row = array();
		$json = array();
		$query = "SELECT pro.project_id,pro.project_title,DATE_FORMAT(pro.start_date,'%d/%m/%Y') start_date,DATE_FORMAT(pro.end_date,'%d/%m/%Y') end_date,pro.hours FROM project pro
				  WHERE pro.project_id=? and pro.status!='Completed'";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "s", $projectId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $projectId,$project_title,$start_date,$end_date,$hours);
		while(mysqli_stmt_fetch($stmt)){
			$row['projectId'] = $projectId;
			$row['project_title'] = $project_title;
			$row['start_date'] = $start_date;
			$row['end_date'] = $end_date;
			$row['hours'] = $hours;
 			array_push($json, $row);
		}
		
		return $json;
	}
	
	function getTask($employeeId){
		$row = array();
		$json = array();
		$query = "SELECT  task.task_id,task.task_name,task.project_id
                FROM tasks task
                INNER JOIN employee_work_details wrk_det
                ON task.reviwer = wrk_det.employee_id
                INNER JOIN tasks_assignees tasa
                ON task.task_id = tasa.task_id 
                WHERE tasa.employee_id=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "s",$employeeId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $task_id,$task_name,$project_id);
		while(mysqli_stmt_fetch($stmt)){
			$row['task_id'] = $task_id;
			$row['task_name'] = $task_name;
			$row['project_id'] = $project_id;
			array_push($json, $row);
		}
		
		return $json;
	}
	function getTimesheet($date,$employeeId){
		$json = array();
		$query = "SELECT tim.employee_id,tim.project_id,tim.task_id,tim.date,tim.from_time,tim.to_time,tim.comments,tim.status,tim.hours FROM timesheet tim
				  WHERE tim.employee_id='$employeeId' and tim.date=STR_TO_DATE('$date','%d/%m/%Y') ORDER BY tim.from_time DESC";
		$result = mysqli_query($this->conn, $query);
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
			array_push($json, $row);
		}
		return $json;
	}
	function setTimesheet($date,$employeeId){
		$query = "SELECT tim.employee_id FROM timesheet tim where tim.employee_id='$employeeId' and tim.date=STR_TO_DATE('$date','%d/%m/%Y') and tim.from_time BETWEEN '$this->from_time' and '$this->to_time' OR tim.date=STR_TO_DATE('$date','%d/%m/%Y') and tim.to_time BETWEEN '$this->to_time' and '$this->from_time'";
		$stmt = mysqli_prepare($this->conn, $query);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		mysqli_stmt_bind_result($stmt, $employee_id);
		if(mysqli_stmt_fetch($stmt)){
			return false;
		}else{
			$query = "INSERT INTO timesheet(employee_id, project_id, task_id,date,from_time,to_time,hours,comments,status,submitted_date)
					VALUES(?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?,NOW())";
			$stmt = mysqli_prepare($this->conn, $query);
			mysqli_stmt_bind_param($stmt, "sssssssss",$employeeId,$this->projectId,$this->taskId,$this->date,$this->from_time,$this->to_time,$this->hours,$this->comments,$this->status);
			$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
			return $result;
		}
		
	}
	function getDataFromTime($fromDate,$toDate,$date,$employeeId){
		$json = array();
		$query = "SELECT tim.employee_id,tim.project_id,tim.task_id task_n_id,tim.date,tim.from_time,tim.to_time,tim.comments,tim.status,tim.hours,tas.task_id,tas.task_name FROM timesheet tim
				  LEFT JOIN tasks tas
				  ON tim.task_id = tas.task_id
				  WHERE tim.employee_id='$employeeId' and tim.date=STR_TO_DATE('$date','%d/%m/%Y') and tim.from_time='$fromDate' and tim.to_time='$toDate'";
		$result = mysqli_query($this->conn, $query);
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
			array_push($json, $row);
		}
		return $json;
	}
	function getDataByDate($fromDate,$toDate,$status,$employeeId){
		$json = array();
		foreach ($status as $statuss){
		
		$query = "SELECT tim.task_id, tim.project_id, tim.from_time, tim.to_time, tim.hours, tim.status, DATE_FORMAT(`date`, '%Y/%m/%d') Date,CONCAT(wrk.employee_name,'',wrk.employee_lastname) employee_name,tim.comments FROM timesheet tim
                  INNER JOIN employee_work_details wrk
                  ON tim.employee_id = wrk.employee_id
		WHERE tim.`date` BETWEEN STR_TO_DATE('$fromDate','%d/%m/%Y') and STR_TO_DATE('$toDate','%d/%m/%Y') and tim.status = '$statuss' and tim.employee_id='$employeeId'";
		$result = mysqli_query($this->conn, $query);
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
			array_push($json, $row);
		}
		}
		return $json;
	}
	function submitTimesheet($date,$fromTime,$employeeId){
		
		$date = str_replace(",", "\",\"", $date);
		$date = "\"".$date."\"";
		$fromTime = str_replace(",", "\",\"", $fromTime);
		$fromTime = "\"".$fromTime."\"";
		$query = "UPDATE timesheet SET status='submitted' WHERE `date` IN ({$date}) and from_time IN ({$fromTime}) and employee_id=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "s",$employeeId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		return $result;
	}
	function deleteTimesheet($date,$fromTime,$employeeId){
	
		$date = str_replace(",", "\",\"", $date);
		$date = "\"".$date."\"";
		$fromTime = str_replace(",", "\",\"", $fromTime);
		$fromTime = "\"".$fromTime."\"";
		$query = "DELETE FROM timesheet WHERE `date` IN ({$date}) and from_time IN ({$fromTime}) and employee_id=?";
		$stmt = mysqli_prepare($this->conn, $query);
		mysqli_stmt_bind_param($stmt, "s",$employeeId);
		$result = mysqli_stmt_execute($stmt) ? TRUE: mysqli_error($this->conn);
		return $result;
	}
}