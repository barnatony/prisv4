<?php
function _dashboard(){
	$data['pagename']='Team-Dashboard';
	$view = new View();
	$data['view'] = $view;
	
	$employees=new Employee();
	if($_SESSION['authprivilage']=='employee'){
		$login_id = $_SESSION["employee_id"];
		//to check team exist for the login employee
		$data['teamleads']=$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$login_id);
	}else{
		redirect();
	}
	
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
	/*$Query="SELECT MAX(CASE WHEN Title='Present' THEN tot_count END) present,
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
	$data['topWidgets'] =$topWidget->fetchAll(PDO::FETCH_ASSOC);*/
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
        					ON hi.employee_id = ho.employee_id";
	$HQuery = $dbh->prepare($HQuery);
	$HQuery->bindParam('login_id',$login_id);
	$HQuery->execute();
	
	$mem='';
	while($members=$HQuery->fetch(PDO::FETCH_ASSOC)){
		$mem.=implode(" ",$members)."','";
		
	}
	$mem=rtrim($mem,",'");
	
	$Query="SELECT * FROM (
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
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)) AND w.employee_id IN('$mem'))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date
            GROUP BY employee_id ) w )y;";

	
	$topWidget = $dbh->prepare($Query);
	$topWidget->bindParam('date',$date);
	$topWidget->bindParam('login_id',$login_id);
	$topWidget->execute();
	$data['Present']=$topWidget->fetch(PDO::FETCH_ASSOC);
	
	
	$QueryActive=" SELECT * FROM (
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
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY))  AND w.employee_id IN('$mem'))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date
            GROUP BY employee_id)z WHERE active=1) k )u;";
	
	$QueryActive = $dbh->prepare($QueryActive);
	$QueryActive->bindParam('date',$date);
	$QueryActive->bindParam('login_id',$login_id);
	$QueryActive->execute();
	$data['Active'] =$QueryActive->fetch(PDO::FETCH_ASSOC);
	
	
	$QueryAbsent="SELECT * FROM (
            SELECT 'Absent',COUNT(employee_id) tot_count FROM (
            SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id FROM (
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
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)) AND w.employee_id IN('$mem'))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date 
            GROUP BY employee_id ) w))j )l;";
	$QueryAbsent = $dbh->prepare($QueryAbsent);
	$QueryAbsent->bindParam('date',$date);
	$QueryAbsent->bindParam('login_id',$login_id);
	$QueryAbsent->execute();
	$data['Absent']=$QueryAbsent->fetch(PDO::FETCH_ASSOC);
	
	$QueryLate="SELECT * FROM (
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
						BETWEEN :date AND DATE_ADD(:date,INTERVAL 1 DAY)) AND w.employee_id IN ('$mem'))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')=:date
            GROUP BY employee_id
						)z WHERE check_in > start_time
            ORDER BY DATE_FORMAT(work_day,'%H:%i') DESC) i)o";
	
	$QueryLate = $dbh->prepare($QueryLate);
	$QueryLate->bindParam('date',$date);
	$QueryLate->bindParam('login_id',$login_id);
	$QueryLate->execute();
	$data['Late'] =$QueryLate->fetch(PDO::FETCH_ASSOC);
	
	
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-core.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-full.min.js"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/dust/dust-helpers.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/holiday-anniversaries.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/jquery.table2excel.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/myteam-dashboard.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/myteam-index.js').'"></script>';
	//$data['foot'][]='<script src="'.myUrl('js/pages/myteam-widget1.js').'"></script>';
	$data['body'][]=View::do_fetch(VIEW_PATH.'myteam/dashboard.php',$data);
	
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}