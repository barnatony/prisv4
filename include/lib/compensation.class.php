<?php
/*
 * ----------------------------------------------------------
 * Filename : Compensation.class.php
 * Author : Raja sundari
 * Database :
 * Oper : comoff Data Loading
 *
 * ----------------------------------------------------------
 */
require_once (__DIR__ . "/notification.class.php");
class Compensation {
	/* Member variables */
	var $compoff_id;
	var $employee_id;
	var $date;
	var $working_for;
	var $updated_by;
	var $day_count;
	var $status;
	var $approved_by;
	var $approved_on;
	var $conn;
	var $is_processed;
	
	/* Member functions */
	function insert($flag) {
		$approved_on=(($flag==1)?date("Y-m-d"):null);
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO compensation_requests
				(compoff_id,employee_id,date,working_for,updated_by,day_count,status,approved_by,approved_on)
                VALUES  (?,?,?,?,?,?,?,?,?);" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssss',$this->compoff_id, $this->employee_id, $this->date, 
		$this->working_for,$this->updated_by ,$this->day_count,$this->status,$this->approved_by,$approved_on);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		//echo  mysqli_error ( $this->conn );
		
		$notification = new Notification();
		$notification ->connection = $this->conn;
		$insertNotif = $notification->insertNotifications('compoffRequested', $this->employee_id,'Admin',$this->compoff_id,'is working for <b>'.$this->working_for.'</b>');
		
		if($result==1){
			$ajson=array (
					'result' => true,
					'dataError' =>(($flag==1)?self::select("'RQ','CO','2X'",0):self::select("'RQ','CO','2X'",1)));
			
		}else{
			
			$ajson=array (
					'result' => false,
					'dataError' =>mysqli_error ( $this->conn ));
		}
		return $ajson;
		
	}
   function select($status,$empFlag) {
        $condition=($empFlag==1)?"AND c.employee_id IN ('$this->employee_id')":'';
        $json = array ();
        if($empFlag==1){
        	$result = mysqli_query ( $this->conn, "SELECT SUM(CASE WHEN is_processed = '-1' THEN 1 ELSE 0 END)`Elapsed`,
        			SUM(CASE WHEN is_processed = '1' THEN 1 ELSE 0 END)`Processed`,
        			SUM(CASE WHEN is_processed = '0' OR is_processed IS NULL THEN 1 ELSE 0 END)`NotProcessed`
        			FROM compensation_requests WHERE status = 'CO' AND employee_id='$this->employee_id';" );
        	$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ;
        	array_push ( $json, $row );
        }
        $result = mysqli_query ( $this->conn, "SELECT c.admin_reason,c.compoff_id,c.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,
        IF(c.day_count IS NOT NULL,CONCAT(DATE_FORMAT(c.date,'%d/%m/%Y'),' - ',c.day_count),DATE_FORMAT(c.date,'%d/%m/%Y')) day_count,
        DATE_FORMAT(c.date,'%d/%m/%Y') date,c.working_for,c.is_processed ,c.status
											   FROM compensation_requests c
											   INNER JOIN employee_work_details w
											   ON c.employee_id = w.employee_id
											   WHERE c.status IN ($status) $condition  ORDER BY c.updated_on DESC" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		
		return $json;
		
	}
	
	function getCompWithDate($status,$date,$employeeId) {
		$json = array ();
		$condition=($employeeId!=null)?"AND c.employee_id='$employeeId'":'';
		if($employeeId!=null){
			$result = mysqli_query ( $this->conn, "SELECT SUM(CASE WHEN is_processed = '-1' THEN 1 ELSE 0 END)`Elapsed`,
					SUM(CASE WHEN is_processed = '1' THEN 1 ELSE 0 END)`Processed`,
					SUM(CASE WHEN is_processed = '0' OR is_processed IS NULL THEN 1 ELSE 0 END)`NotProcessed`
					FROM compensation_requests WHERE  status = 'CO' AND employee_id='$this->employee_id';" );
			$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ;
			array_push ( $json, $row );
		}
		$result = mysqli_query ( $this->conn, "SELECT c.admin_reason,c.compoff_id,c.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,
				IF(c.day_count IS NOT NULL,CONCAT(DATE_FORMAT(c.date,'%d/%m/%Y'),' - ',c.day_count),DATE_FORMAT(c.date,'%d/%m/%Y')) day_count,
				DATE_FORMAT(c.date,'%d/%m/%Y') date,c.working_for,c.is_processed,c.status
				FROM compensation_requests c
				INNER JOIN employee_work_details w
				ON c.employee_id = w.employee_id
				WHERE c.status IN ($status) $condition AND date='$date' ORDER BY c.updated_on DESC");
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
	
	function update() {
		$approved_on=date("Y-m-d");
		$stmt = mysqli_prepare ( $this->conn, "UPDATE compensation_requests SET day_count= ?, status = ?,admin_reason = ?, approved_by=? ,approved_on=? WHERE employee_id = ? AND compoff_id=?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssss', $this->day_count ,$this->status ,$this->admin_reason,$this->approved_by,$approved_on,$this->employee_id, $this->compoff_id);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return ($this->dateFlag==1)?self::getCompWithDate ("'RQ','2X','CO'",$this->date,null):self::select("'RQ','CO','2X'",0);
	}
	
	function getholiday($fromDate,$endDate,$employeeId){
		$json=array();
		$result = mysqli_query ( $this->conn, "SELECT MAX(CASE WHEN ref IS NULL THEN title ELSE null END) `holiday`,
		MAX(CASE WHEN ref IS NOT NULL THEN 1 ELSE 0 END) `isApplied`
		FROM (
				SELECT e.holiday_id,e.title,null ref FROM holidays_event e
				WHERE e.start_date = '$fromDate' AND e.category='HOLIDAY'
				UNION ALL
				SELECT c.compoff_id,c.working_for,c.status FROM compensation_requests c
				WHERE c.date = '$fromDate' AND c.employee_id ='$employeeId' AND  c.status!='R' )a;");
		
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
}
?>