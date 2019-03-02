<?php
class today_attendance {
	/* Member variables */
	var $conn;
	function __construct($conn) {
		$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $_SESSION ['cmpDtSrc'] );
	}
	
	function topwidgets($date,$branchId){
	    $a_json = array ();
	    $branchCond = ($branchId!='')?"AND w.branch_id='$branchId'":"";
	    /*
	    $statsQuery = "SELECT MAX(CASE WHEN Title='Present' THEN tot_count END) present,MAX(CASE WHEN Title='Absent' THEN tot_count END) absent,MAX(CASE WHEN Title='Active' THEN tot_count END) active,MAX(CASE WHEN Title='Late' THEN tot_count END) late 
					   FROM(
					   SELECT 'Present' Title,COUNT(employee_id) tot_count FROM (
							SELECT w.employee_id FROM employee_biometric b 
						    INNER JOIN device_users du ON du.ref_id = b.employee_id 
							INNER JOIN employee_work_details w ON du.employee_id = w.employee_id 
							WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date' $branchCond GROUP BY b.employee_id)w
					   UNION ALL
					   SELECT 'Absent',(SELECT COUNT(d.employee_id) FROM device_users d INNER JOIN employee_work_details w ON d.employee_id = w.employee_id WHERE d.status=1 $branchCond) - (SELECT COUNT(employee_id) FROM (SELECT w.employee_id FROM employee_biometric b INNER JOIN device_users du ON du.ref_id = b.employee_id INNER JOIN employee_work_details w ON du.employee_id = w.employee_id WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date' $branchCond GROUP BY b.employee_id)w) tot_count
					   UNION ALL
					   SELECT 'Active',COUNT(active) tot_count FROM ( 
							SELECT w.employee_id,(CASE WHEN COUNT(b.date_time)%2!=0 THEN 1 ELSE NULL END) active 
							FROM employee_work_details w LEFT JOIN device_users du ON w.employee_id = du.employee_id 
							LEFT JOIN employee_biometric b ON du.ref_id = b.employee_id 
							LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
							WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date' $branchCond GROUP BY employee_id ORDER BY b.employee_id,b.date_time)q
					   UNION ALL
					   SELECT 'Late',(SELECT COUNT(employee_id) FROM ( 
							SELECT w.employee_id,MIN(b.date_time) date_time,DATE_FORMAT(b.date_time,'%T') check_in,s.start_time 
							FROM employee_work_details w LEFT JOIN device_users du ON w.employee_id = du.employee_id 
							LEFT JOIN employee_biometric b ON du.ref_id = b.employee_id 
							INNER JOIN device_users u ON w.employee_id = u.employee_id 
							LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id 
							WHERE w.enabled=1 AND DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date' $branchCond 
							GROUP BY b.employee_id ORDER BY b.employee_id,b.date_time) z WHERE DATE_FORMAT(date_time,'%H:%i') > start_time) late) t;";
	    */
	    $statsQuery = "SELECT MAX(CASE WHEN Title='Present' THEN tot_count END) present,MAX(CASE WHEN Title='Absent' THEN tot_count END) absent,MAX(CASE WHEN Title='Active' THEN tot_count END) active,MAX(CASE WHEN Title='Late' THEN tot_count END) late 
						FROM(
						  SELECT 'Present' Title,COUNT(CONCAT(w.employee_name,' (',a.employee_id,')')) tot_count
						  FROM attendance_summary a
						  INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
						  INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						  LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
						  WHERE w.enabled= 1 AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date' $branchCond
						  UNION ALL
						  SELECT 'Late',COUNT(CONCAT(w.employee_name,' (',a.employee_id,')')) tot_count
						  FROM attendance_summary a
						  INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
						  INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						  LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
						  WHERE w.enabled= 1 AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date' $branchCond AND lateIn !='-'
						  UNION ALL
						  SELECT 'Active',COUNT(employee) tot_count
						  FROM (
						  SELECT CONCAT(w.employee_name,' (',a.employee_id,')') employee,(LENGTH(a.punches) - LENGTH(REPLACE(a.punches, ',', '')))+1 cnt
						  FROM attendance_summary a
						  INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
						  INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						  LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
						  WHERE w.enabled= 1 AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date' $branchCond
						  ) t WHERE cnt%2 !=0
						  UNION ALL
						  SELECT 'Absent',COUNT(employee) tot_count FROM (
							SELECT employee 
										FROM (
										SELECT CONCAT(w.employee_name,' (',d.employee_id,')') employee
										FROM device_users d
										INNER JOIN employee_work_details w ON d.employee_id = w.employee_id
										INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
										LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
										LEFT JOIN emp_absences a ON d.employee_id = a.employee_id
										AND a.absent_date BETWEEN ADDDATE(LAST_DAY(DATE_SUB('$date', INTERVAL 1 MONTH)),1) AND '$date' 
										WHERE w.enabled= 1 AND d.status=1 $branchCond AND d.employee_id 
										NOT IN(
											  SELECT a.employee_id
											  FROM attendance_summary a
											  INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
											  INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
											  LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
											  WHERE w.enabled= 1 AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date') $branchCond
										)z GROUP BY employee)t )r;";
						 
	    //echo $statsQuery; die();
	    $result = mysqli_query ( $this->conn, $statsQuery);
	    while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	        array_push ( $a_json, $row );
	    }
	    return array (
	        'result' => (($result)?TRUE:FALSE),
	        'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
	    );
	}
	
	function chartData($date,$branchId){
		$a_json = array ();
		$branchCond = ($branchId!='')?"AND w.branch_id='$branchId'":"";
		$query ="SELECT DATE_FORMAT(FROM_UNIXTIME(FLOOR((UNIX_TIMESTAMP(date_time))/1800)*1800),'%H:%i') AS emp_time,count(1) as emp_count
				 FROM (
					SELECT b.employee_id,date_time,DATE_FORMAT(FROM_UNIXTIME(FLOOR((UNIX_TIMESTAMP(date_time))/1800)*1800),'%H:%i') AS punch_time
					FROM employee_biometric b
					INNER JOIN device_users du
					ON b.employee_id = du.ref_id
					LEFT JOIN employee_work_details w
					ON du.employee_id = w.employee_id
					WHERE w.enabled= 1 AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN '$date' AND DATE_ADD('$date',INTERVAL 1 DAY) 
				    AND DATE_FORMAT(date_time,'%Y-%m-%d %T')> '$date 08:30:00' AND DATE_FORMAT(date_time,'%Y-%m-%d %T')<= CONCAT(DATE_ADD('$date',INTERVAL 1 DAY),' 08:30:00') $branchCond
				    GROUP BY b.employee_id,punch_time ORDER BY date_time
				  ) z GROUP BY emp_time ORDER BY date_time;";
		
		$result = mysqli_query ( $this->conn, $query );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
		);
		
	}
	
	function lateComersData($date,$branchId) {
	    $a_json = array ();
	    $branchCond = ($branchId!='')?"AND w.branch_id='$branchId'":"";
	   /*$query ="SELECT CONCAT (employee_name,' (',employee_id,')') employee_id ,branch_name,check_in,DATE_FORMAT(TIMEDIFF(check_in,CONCAT(start_time,':00')),'%H:%i') time_diff,last_checkIn
				   FROM (
					SELECT CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,w.employee_id,MIN(b.date_time) date_time,DATE_FORMAT(b.date_time,'%H:%i') check_in,s.start_time,cb.branch_name,DATE_FORMAT(MAX(b.date_time),'%H:%i') last_checkIn
					FROM employee_work_details w
					LEFT JOIN company_branch cb
          			ON w.branch_id = cb.branch_id
					LEFT JOIN device_users du
					ON w.employee_id = du.employee_id
					INNER JOIN employee_biometric b
					ON du.ref_id = b.employee_id
          			LEFT JOIN company_shifts s
					ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
					WHERE w.enabled=1 AND DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date' $branchCond
					GROUP BY b.employee_id ORDER BY b.employee_id,b.date_time
					) z WHERE DATE_FORMAT(date_time,'%H:%i') > start_time ORDER BY DATE_FORMAT(date_time,'%H:%i') DESC;";
	    */
	    $query ="SELECT CONCAT(w.employee_name,' (',a.employee_id,')') employee_id,cb.branch_name,s.shift_name,DATE_FORMAT(a.checkIn,'%H:%i') check_in,
					   SUBSTRING_INDEX(a.lateIn,':',2) time_diff,SUBSTRING_INDEX(a.punches,',',-1) last_checkIn,start_time,end_time,early_start,late_end
				FROM attendance_summary a
				INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
				INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
				WHERE w.enabled= 1 AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date' AND lateIn !='-' $branchCond
				ORDER BY SUBSTRING_INDEX(a.punches,',',-1) DESC;";
	    //echo $query; die();
	    $result = mysqli_query ( $this->conn, $query );
	    while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	        array_push ( $a_json, $row );
	    }
	    return array (
	        'result' => (($result)?TRUE:FALSE),
	        'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
	    );
	    
	}
	
	function PresentData($date,$branchId){
	    $a_json = array ();
	    $branchCond = ($branchId!='')?"AND w.branch_id='$branchId'":"";
	    /*$query ="SELECT DISTINCT CONCAT(w.employee_name,' (',w.employee_id,')') employee,cb.branch_name,DATE_FORMAT(MIN(b.date_time),'%H:%i') check_in,DATE_FORMAT(MAX(b.date_time),'%H:%i') last_checkIn
                 FROM employee_biometric b
                 INNER JOIN device_users du ON du.ref_id = b.employee_id
                 INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
                 INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
                 WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date'$branchCond
                 GROUP BY w.employee_id;";*/
	    
	    $query ="SELECT CONCAT(w.employee_name,' (',a.employee_id,')') employee,cb.branch_name,s.shift_name,DATE_FORMAT(a.checkIn,'%H:%i') check_in,
					   SUBSTRING_INDEX(a.punches,',',-1) last_checkIn,start_time,end_time,early_start,late_end
				FROM attendance_summary a
				INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
				INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
				WHERE w.enabled= 1 AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date' $branchCond
				ORDER BY SUBSTRING_INDEX(a.punches,',',-1) DESC;";
	    //echo $query; die();
	    $result = mysqli_query ( $this->conn, $query );
	    while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	        array_push ( $a_json, $row );
	    }
	    return array (
	        'result' => (($result)?TRUE:FALSE),
	        'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
	    );
	}
	
	function AbsentEmployeesData($date,$branchId){
	    $a_json = array ();
	    $branchCond = ($branchId!='')?"AND w.branch_id='$branchId'":"";
	    /*$query ="SELECT employee,branch_name,IF(COUNT(absent_date)!=0,COUNT(absent_date),'-') absent_count 
				 FROM (
                 SELECT CONCAT(w.employee_name,' (',d.employee_id,')') employee,cb.branch_name,absent_date
                 FROM device_users d
                 INNER JOIN employee_work_details w ON d.employee_id = w.employee_id
                 INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
                 LEFT JOIN emp_absences a ON d.employee_id = a.employee_id
                 AND a.absent_date BETWEEN ADDDATE(LAST_DAY(DATE_SUB('$date', INTERVAL 1 MONTH)),1) AND '$date' $branchCond
                 WHERE d.status=1 $branchCond AND d.employee_id NOT IN(SELECT DISTINCT w.employee_id FROM employee_biometric b
                 INNER JOIN device_users du ON du.ref_id = b.employee_id
                 INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
                 WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date'))z GROUP BY employee;";
	    */
	    $query ="SELECT employee,branch_name,shift_name,IF(COUNT(absent_date)!=0,COUNT(absent_date),'-') absent_count,start_time,end_time,early_start,late_end 
				FROM (
				SELECT CONCAT(w.employee_name,' (',d.employee_id,')') employee,cb.branch_name,s.shift_name,absent_date,
					   s.start_time,s.end_time,s.early_start,s.late_end
				FROM device_users d
				INNER JOIN employee_work_details w ON d.employee_id = w.employee_id
				INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
				LEFT JOIN emp_absences a ON d.employee_id = a.employee_id
				AND a.absent_date BETWEEN ADDDATE(LAST_DAY(DATE_SUB('$date', INTERVAL 1 MONTH)),1) AND '$date'
				WHERE w.enabled= 1 $branchCond AND d.status=1 AND d.employee_id 
				NOT IN(
				      SELECT a.employee_id
					  FROM attendance_summary a
					  INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
					  INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
					  LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
					  WHERE w.enabled= 1 $branchCond AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date')
				)z GROUP BY employee;";
	    //echo $query; die();
	    $result= mysqli_query ( $this->conn, $query );
	    while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	        array_push ( $a_json, $row );
	    }
	    return array (
	        'result' => (($result)?TRUE:FALSE),
	        'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
	    );
	}
	
	function ActiveEmployees($date,$branchId){
	    $a_json = array ();
	    $branchCond = ($branchId!='')?"AND w.branch_id='$branchId'":"";
	    /*$query ="SELECT CONCAT(employee_name,' (',employee_id,')') employee,employee_name,branch_name,DATE_FORMAT(check_in,'%H:%i') check_in,DATE_FORMAT(last_checkIn,'%H:%i')last_checkIn FROM (
                 SELECT w.employee_id,w.employee_name,cb.branch_name,MIN(b.date_time) check_in,MAX(b.date_time) last_checkIn,(CASE WHEN COUNT(b.date_time)%2!=0 THEN 1 ELSE NULL END) active
                 FROM employee_work_details w
                 LEFT JOIN device_users du ON w.employee_id = du.employee_id
                 LEFT JOIN employee_biometric b ON du.ref_id = b.employee_id
                 INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
                 LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
                 WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date'$branchCond
                 GROUP BY employee_id ORDER BY b.employee_id,b.date_time )z
                 WHERE active=1;";
	    */
	    $query ="SELECT employee,branch_name,shift_name,check_in,last_checkIn,start_time,end_time,early_start,late_end
				FROM (
				SELECT CONCAT(w.employee_name,' (',a.employee_id,')') employee,cb.branch_name,s.shift_name,DATE_FORMAT(a.checkIn,'%H:%i') check_in,
					   SUBSTRING_INDEX(a.punches,',',-1) last_checkIn,start_time,end_time,early_start,late_end,
					   a.punches,(LENGTH(a.punches) - LENGTH(REPLACE(a.punches, ',', '')))+1 cnt
				FROM attendance_summary a
				INNER JOIN employee_work_details w ON a.employee_id = w.employee_id
				INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
				WHERE w.enabled= 1 AND DATE_FORMAT(a.checkIn,'%Y-%m-%d')='$date' $branchCond
				) t WHERE cnt%2 !=0
				ORDER BY SUBSTRING_INDEX(punches,',',-1) DESC;";
	    //echo $query; die();
	    $result = mysqli_query ( $this->conn, $query );
	    while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	        array_push ( $a_json, $row );
	    }
	    return array (
	        'result' => (($result)?TRUE:FALSE),
	        'data' =>(($result)?$a_json:mysqli_error ( $this->conn ))
	    );
	}
}

