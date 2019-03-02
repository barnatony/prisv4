<?php
function _getWidget() {
	
	$emp=new Employee();
	$empDetails=array(); //result array
	$privilage=$_SESSION["authprivilage"];
	$dbh = getdbh();
	
	$employees=new Employee();
	if($_SESSION['authprivilage']=="employee"){
		$login_id = $_SESSION["employee_id"];
		$wherewhat="WHERE l.employee_id='{$_SESSION["employee_id"]}'";
	}
	$date=date("Y-m-d");
	$tempArr=array();
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
        					ON hi.employee_id = ho.employee_id AND hi.enabled=1
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
		
	echo json_encode($tempArr);
		
}