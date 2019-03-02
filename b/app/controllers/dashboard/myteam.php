<?php 
function _myteam($action=null){
	
	$emp=new Employee();
	$empDetails=array(); //result array
	$privilage=$_SESSION["authprivilage"];
	$dbh = getdbh();
	
	$employees=new Employee();
	if($_SESSION['authprivilage']=="employee"){
		$login_id = $_SESSION["employee_id"];
		$wherewhat="WHERE l.employee_id='{$_SESSION["employee_id"]}'";
	}
	$today_date= date("d");
	$stmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
  							FROM company_details WHERE info_flag="A" AND company_id=:company_id');
	$stmt->bindParam('company_id', $_SESSION['company_id']);
	$stmt->execute();
	$companyProp = $stmt->fetch();
		
	if($companyProp['attendance_dt'] !=1){
		if($today_date >= $companyProp['attendance_dt']){
			$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']);
			$eDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
			$endDate = date("Y-m-d",strtotime("{$eDate} +1 months"));
		}else{
			$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']);
			$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
			$endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
		}
	}else{
		$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
		$endDate = date('Y-m-t',strtotime($startDate));
	}
	$tempArr=array();
	
	$HQuery=" SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id					FROM    (
        					        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
        					        FROM    (
        					                SELECT  @start_with := :login_id,
        					                        @id := @start_with,
        					                        @level := 0
        					                ) vars, employee_work_details
        					        WHERE   @id IS NOT NULL AND enabled !=0
        					        ) ho
        					JOIN employee_work_details hi
        					ON hi.employee_id = ho.employee_id AND enabled !=0";";
	$HQuery = $dbh->prepare($HQuery);
	$HQuery->bindParam('login_id',$login_id);
	$HQuery->execute();
	
	$mem='';
	while($members=$HQuery->fetch(PDO::FETCH_ASSOC)){
		$mem.=implode(" ",$members)."','";
	
	}
	$mem=rtrim($mem,",'");
	
	switch($action){
	
		case "leaveRequests":
			
			
			if($_SESSION["authprivilage"]=="employee")
				$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$_SESSION['employee_id']);
			
				if($teamleads){
					$privilage="team_lead";
					$wherewhat=" l.leave_type NOT IN ('lop','wfh','Otr','od') AND w.employee_id IN (
					'$mem') OR w.employee_reporting_person IS NULL";
				}
				
				
			
				$employees=$employees->joined_select("w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,s.shift_name shift,dept.department_name department,des.designation_name designation,pd.employee_mobile mobile,pd.employee_email email,IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Yrs'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Yr'))) experience,b.branch_name branch,
				l.id,l.request_id,DATE_FORMAT(l.from_date,'%d %b,%Y') start,l.from_half,DATE_FORMAT(l.to_date,'%d %b,%Y') end,l.to_half,l.duration,UPPER(l.leave_type) leave_type,l.reason,l.status,l.admin_reason,l.approved_on,l.approved_by,l.updated_by,(CASE 
WHEN l.from_half=l.to_half OR (l.from_half = 'SH' AND l.to_half = 'FH') THEN 1
ELSE 0
END) print_half,l.updated_on,'$privilage' privilage,'$startDate' attendance_st_date",
				" leave_requests l
				INNER JOIN employee_work_details w
				ON l.employee_id=w.employee_id
				LEFT JOIN company_shifts s
				ON s.shift_id=w.shift_id
				LEFT JOIN company_departments dept
				ON w.department_id = dept.department_id
				LEFT JOIN company_designations des
				ON des.designation_id=w.designation_id
				LEFT JOIN employee_personal_details pd
				ON pd.employee_id=w.employee_id
				LEFT JOIN company_branch b
				ON w.branch_id = b.branch_id","{$wherewhat}
				AND l.request_id IS NOT NULL ORDER BY l.status='RQ' DESC,l.raised_on DESC LIMIT 0,3");
					
				
				$tempArr['leaveRequests']=$employees;
				break;
				
				case "Regularization":
					if($_SESSION["authprivilage"]=="employee")
						$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$_SESSION['employee_id']);
							
						if($teamleads){
							$privilage="team_lead";
							$wherewhat="r.employee_id IN(
'$mem') AND r.regularize_type='Incorrectpunches'";
						}
					
					$employeesReg=$employees->joined_select("r.id,r.employee_id,p.employee_name,DATE_FORMAT(r.day,'%d %b,%Y') day,r.regularize_type,r.reason_type,r.reason,r.status,r.admin_reason,r.raised_on,
					r.raised_by,r.approved_on,r.approved_by,r.updated_on,r.updated_by,'$privilage' privilage"
					,"attendance_regularization r
					LEFT JOIN employee_work_details p ON p.employee_id=r.employee_id",
					"{$wherewhat}
					ORDER BY r.status='RQ' DESC,r.raised_on DESC LIMIT 0,4"
					);
					
					$tempArr['Regularization']=$employeesReg;
					break;
					
					
					
						break;
						
					case "LateComers":
						$date=date("Y-m-d");
						$query ="SELECT employee,branch,shift,DATE_FORMAT(TIMEDIFF(check_in,CONCAT(start_time,':00')),'%H:%i') late,lastCheckIn
						FROM (
						SELECT DISTINCT CONCAT(employee_name,' (',employee_id,')') employee,employee_id empId,branch_name branch,shift_name shift,DATE_FORMAT(MIN(date_time),'%H:%i') check_in,
						IF(is_day=0,DATE_FORMAT(DATE_SUB(MAX(date_time),INTERVAL 1 DAY),'%H:%i'),DATE_FORMAT(MAX(date_time),'%H:%i')) lastCheckIn,work_day,
						start_time,end_time,early_start,late_end
						FROM (
						SELECT w.employee_id,cb.branch_name,s.shift_name,w.employee_name,date_time,
						(CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
						ELSE date_time END) work_day,is_day,s.start_time,s.end_time,s.early_start,s.late_end
						FROM employee_biometric b
						INNER JOIN device_users du ON du.ref_id = b.employee_id
						INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
						INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
						WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)) AND 
					           w.employee_id IN('$mem'))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date
						GROUP BY employee_id
						)z WHERE  check_in > start_time 
						ORDER BY lastCheckIn DESC";
						$lateQuery = $dbh->prepare($query);
						$lateQuery->bindParam('date',$date);
						$lateQuery->bindParam('login_id',$login_id);
						$lateQuery->execute();
						$tempArr['lateComers'] =$lateQuery->fetchAll(PDO::FETCH_ASSOC);
						
							
						
						break;
						
					case "Present":
						$date=date("Y-m-d");
						$PQuery="SELECT DISTINCT CONCAT(employee_name,' (',employee_id,')') employee,branch,shift,DATE_FORMAT(MIN(date_time),'%H:%i') checkIn ,
				    IF(is_day=0,DATE_FORMAT(DATE_SUB(MAX(date_time),INTERVAL 1 DAY),'%H:%i'),DATE_FORMAT(MAX(date_time),'%H:%i')) lastCheckIn
				FROM (
				SELECT w.employee_id,cb.branch_name branch,s.shift_name shift,w.employee_name,date_time,
				      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
									       ELSE date_time END) work_day,is_day,s.start_time,s.end_time,s.early_start,s.late_end
				FROM employee_biometric b
				INNER JOIN device_users du ON du.ref_id = b.employee_id
				INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
				INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id 
				WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
				BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)) AND w.employee_id IN('$mem'))t   
				WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date
				GROUP BY employee_id ORDER BY lastCheckIn DESC;";
						
						$PQuery = $dbh->prepare($PQuery);
						$PQuery->bindParam('date',$date);
						$PQuery->bindParam('login_id',$login_id);
						$PQuery->execute();
						$tempArr['lateComers'] =$PQuery->fetchAll(PDO::FETCH_ASSOC);
						
					break;	
					case "active":
						$date=date("Y-m-d");
						$active="SELECT employee,branch,shift,checkIn,lastCheckIn
				FROM (
				SELECT DISTINCT CONCAT(employee_name,' (',employee_id,')') employee,branch_name branch,shift_name shift,DATE_FORMAT(MIN(date_time),'%H:%i') checkIn,
				    IF(is_day=0,DATE_FORMAT(DATE_SUB(MAX(date_time),INTERVAL 1 DAY),'%H:%i'),DATE_FORMAT(MAX(date_time),'%H:%i')) lastCheckIn,work_day,
				    COUNT(work_day) punch_count,(CASE WHEN COUNT(work_day)%2!=0 THEN 1 ELSE NULL END) active,start_time,end_time,early_start,late_end
				FROM (
				SELECT w.employee_id,cb.branch_name,s.shift_name,w.employee_name,date_time,
				      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
									       ELSE date_time END) work_day,is_day,s.start_time,s.end_time,s.early_start,s.late_end
				FROM employee_biometric b
				INNER JOIN device_users du ON du.ref_id = b.employee_id
				INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
				INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
				WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
				BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)) AND w.employee_id IN('$mem'))t 
				WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date
				GROUP BY employee_id)z WHERE active=1 ORDER BY lastCheckIn DESC";
						$active = $dbh->prepare($active);
						$active->bindParam('date',$date);
						$active->bindParam('login_id',$login_id);
						$active->execute();
						$tempArr['lateComers'] =$active->fetchAll(PDO::FETCH_ASSOC);
						
					break;	
					case "absent":
						$date=date("Y-m-d");
					$absent="SELECT employee,branch,shift,IF(COUNT(absent_date)!=0,COUNT(absent_date),'-') absentCount
				FROM (
				SELECT CONCAT(w.employee_name,' (',d.employee_id,')') employee,cb.branch_name branch,s.shift_name shift,absent_date,
					   s.start_time,s.end_time,s.early_start,s.late_end
				FROM device_users d
				INNER JOIN employee_work_details w ON d.employee_id = w.employee_id
				INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
				LEFT JOIN emp_absences a ON d.employee_id = a.employee_id
				AND a.absent_date BETWEEN ADDDATE(LAST_DAY(DATE_SUB(:date, INTERVAL 1 MONTH)),1) AND :date 
				WHERE w.enabled= 1 AND d.status=1 AND d.employee_id 
				NOT IN(
				      SELECT DISTINCT employee_id FROM (
				      SELECT w.employee_id,cb.branch_name,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,date_time,
				          (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
				          		       ELSE date_time END) work_day,is_day
				      FROM employee_biometric b
				      INNER JOIN device_users du ON du.ref_id = b.employee_id
				      INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
				      INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
				      LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
				      WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
				      BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)) AND w.employee_id IN('$mem'))t
				      WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date
				      GROUP BY employee_id) AND d.employee_id IN(
                  '$mem')
				)z GROUP BY employee;";	
					$absent = $dbh->prepare($absent);
					$absent->bindParam('date',$date);
					$absent->bindParam('login_id',$login_id);
					$absent->execute();
					$tempArr['lateComers'] =$absent->fetchAll(PDO::FETCH_ASSOC);
					
					break;
				/*	case "topWidgets":
					$date=date("Y-m-d");
					$Query="SELECT MAX(CASE WHEN Title='Present' THEN tot_count END) present,
MAX(CASE WHEN Title='Absent' THEN tot_count END) absent,MAX(CASE WHEN Title='Active' THEN tot_count END) active,MAX(CASE WHEN Title='Late' THEN tot_count END) late 
					   FROM(
             SELECT * FROM (
					   SELECT 'Present' Title,COUNT(employee_id) tot_count FROM (
					   SELECT DISTINCT employee_id
						FROM (
						SELECT w.employee_id,cb.branch_name,w.employee_name,date_time,
						      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
											       ELSE date_time END) work_day,is_day
						FROM employee_biometric b
						INNER JOIN device_users du ON du.ref_id = b.employee_id
						INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
						INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id 
						WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date AND employee_id IN(
                  SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id					FROM    (
        					        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
        					        FROM    (
        					                SELECT  @start_with := :login_id,
        					                        @id := @start_with,
        					                        @level := 0
        					                ) vars, employee_work_details
        					        WHERE   @id IS NOT NULL AND enabled !=0
        					        ) ho
        					JOIN employee_work_details hi
        					ON hi.employee_id = ho.employee_id)
            GROUP BY employee_id ) w )y
						UNION ALL
            SELECT * FROM (
            SELECT 'Absent',COUNT(employee_id) tot_count FROM (
            SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id					FROM    (
        					        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
        					        FROM    (
        					                SELECT  @start_with := :login_id,
        					                        @id := @start_with,
        					                        @level := 0
        					                ) vars, employee_work_details
        					        WHERE   @id IS NOT NULL AND enabled !=0
        					        ) ho
        					JOIN employee_work_details hi
        					ON hi.employee_id = ho.employee_id 
                  WHERE ho.employee_id NOT IN (SELECT (employee_id) tot_count FROM ( 
					   SELECT DISTINCT employee_id
						FROM (
						SELECT w.employee_id,cb.branch_name,w.employee_name,date_time,
						      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
											       ELSE date_time END) work_day,is_day
						FROM employee_biometric b
						INNER JOIN device_users du ON du.ref_id = b.employee_id
						INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
						INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id 
						WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date AND employee_id IN(
                  SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id					FROM    (
        					        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
        					        FROM    (
        					                SELECT  @start_with := :login_id,
        					                        @id := @start_with,
        					                        @level := 0
        					                ) vars, employee_work_details
        					        WHERE   @id IS NOT NULL AND enabled !=0
        					        ) ho
        					JOIN employee_work_details hi
        					ON hi.employee_id = ho.employee_id)
            GROUP BY employee_id ) w))j )l
            UNION ALL
            SELECT * FROM (
					    SELECT 'Active',COUNT(employee) tot_count FROM (
						SELECT employee FROM (
						SELECT DISTINCT CONCAT(employee_name,' (',employee_id,')') employee,branch_name,DATE_FORMAT(MIN(date_time),'%H:%i') check_in,
						    IF(is_day=0,DATE_FORMAT(DATE_SUB(MAX(date_time),INTERVAL 1 DAY),'%H:%i'),DATE_FORMAT(MAX(date_time),'%H:%i')) last_checkIn,work_day,
						    COUNT(work_day) punch_count,(CASE WHEN COUNT(work_day)%2!=0 THEN 1 ELSE NULL END) active
						FROM (
						SELECT w.employee_id,cb.branch_name,w.employee_name,date_time,
						      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
											       ELSE date_time END) work_day,is_day
						FROM employee_biometric b
						INNER JOIN device_users du ON du.ref_id = b.employee_id
						INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
						INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
						WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date AND employee_id IN(
                  SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id					FROM    (
        					        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
        					        FROM    (
        					                SELECT  @start_with := :login_id,
        					                        @id := @start_with,
        					                        @level := 0
        					                ) vars, employee_work_details
        					        WHERE   @id IS NOT NULL AND enabled !=0
        					        ) ho
        					JOIN employee_work_details hi
        					ON hi.employee_id = ho.employee_id)
            GROUP BY employee_id)z WHERE active=1) k )u
						UNION ALL
            SELECT * FROM (
					    SELECT 'Late',COUNT(employee_id) tot_count FROM (
						SELECT employee_id FROM (
						SELECT DISTINCT CONCAT(employee_name,' (',employee_id,')') employee_id,branch_name,DATE_FORMAT(MIN(date_time),'%H:%i') check_in,
						    IF(is_day=0,DATE_FORMAT(DATE_SUB(MAX(date_time),INTERVAL 1 DAY),'%H:%i'),DATE_FORMAT(MAX(date_time),'%H:%i')) last_checkIn,start_time,work_day
						FROM (
						SELECT w.employee_id,cb.branch_name,w.employee_name,date_time,
						      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
											       ELSE date_time END) work_day,is_day,s.start_time 
						FROM employee_biometric b
						INNER JOIN device_users du ON du.ref_id = b.employee_id
						INNER JOIN employee_work_details w ON du.employee_id = w.employee_id
						INNER JOIN company_branch cb ON w.branch_id = cb.branch_id
						LEFT JOIN company_shifts s ON IF(w.shift_id='Nil','SH00001',w.shift_id) = s.shift_id
						WHERE w.enabled= 1 AND IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')=:date,DATE_FORMAT(b.date_time,'%Y-%m-%d')
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date AND employee_id IN (
            SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id					FROM    (
        					        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
        					        FROM    (
        					                SELECT  @start_with := :login_id,
        					                        @id := @start_with,
        					                        @level := 0
        					                ) vars, employee_work_details
        					        WHERE   @id IS NOT NULL AND enabled !=0
        					        ) ho
        					JOIN employee_work_details hi
        					ON hi.employee_id = ho.employee_id)
            GROUP BY employee_id
						)z WHERE check_in > start_time 
            ORDER BY DATE_FORMAT(work_day,'%H:%i') DESC) i)o ) r";
					
					
					$topWidget = $dbh->prepare($Query);
					$topWidget->bindParam('date',$date);
					$topWidget->bindParam('login_id',$login_id);
					$topWidget->execute();
					$tempArr['topWidgets'] =$topWidget->fetchAll(PDO::FETCH_ASSOC);
					
					
					
					
					break;*/
	}
	
	
	echo json_encode($tempArr);
	
	
}












?>