<?php
function _getChartData($action=null) {
	
	$dbh = getdbh();
	$tempArr=array(); 
	$employees=new Employee();
	//to get attdance start date and end DAte
	//to get current payroll month startDate and endDate
	$stmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
  							FROM company_details WHERE info_flag="A" AND company_id=:company_id');
	$stmt->bindParam('company_id', $_SESSION['company_id']);
	$stmt->execute();
	$companyProp = $stmt->fetch();
	if($companyProp['attendance_dt'] !=1){
		$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".$companyProp['attendance_dt'];
		$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
		$endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
	}else{
		$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
		$endDate = date('Y-m-t',strtotime($startDate));
	}
	date_default_timezone_set('Asia/Kolkata');
	$now = new DateTime();
	$time=$now->format('H:i:s');
	$date = $now->format('Y-m-d');
	
	if($time > '00:00:00' && $time < '08:00:00')
		$date = date('Y-m-d', strtotime("-1 day"));
	
	$HQuery=" SELECT DISTINCT b.employee_id-- ,w.employee_name,MIN(date_time)
          FROM employee_biometric b
          WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d') IN (SELECT selected_date dates FROM 
          					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
          					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
          					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
          					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
          					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
          					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
          WHERE selected_date BETWEEN DATE_FORMAT(DATE_SUB(:date,INTERVAL 5 DAY),'%Y-%m-%d')  AND DATE_FORMAT(DATE_SUB(:date,INTERVAL 1 DAY),'%Y-%m-%d')
          AND DATE_FORMAT(selected_date,'%a') NOT IN ('Sat','Sun'))
          GROUP BY b.employee_id,DATE_FORMAT(b.date_time,'%Y-%m-%d')";
	$HQuery = $dbh->prepare($HQuery);
	$HQuery->bindParam('date',$date);
	$HQuery->execute();
	
	$mem='';
	while($members=$HQuery->fetch(PDO::FETCH_ASSOC)){
		$mem.=implode(" ",$members)."','";
		
	}
	$mem=rtrim($mem,",'");
	
	switch ($action){
		
		
		case "infos":   //for info(total,exits,joined,appraisal due)
			
		//to get total count of the employees
			$total=$dbh->prepare("SELECT count(employee_id) total FROM employee_work_details WHERE enabled = 1");
			$total->execute();
			$total = $total->fetchAll(PDO::FETCH_ASSOC);
		//to get join count
			$joined=$dbh->prepare("SELECT count(employee_id) joined FROM employee_work_details WHERE enabled = 1 AND employee_doj BETWEEN :startDate AND :endDate");
			$joined->bindParam('startDate', $startDate);
			$joined->bindParam('endDate', $endDate);
			$joined->execute();
			$joined = $joined->fetchAll(PDO::FETCH_ASSOC);
			
			
		//to get exits count
			$exits=$dbh->prepare("SELECT count(employee_id) exits FROM emp_notice_period WHERE last_working_date BETWEEN :startDate AND :endDate AND status='S'");
			$exits->bindParam('startDate', $startDate);
			$exits->bindParam('endDate', $endDate);
			$exits->execute();
			$exits = $exits->fetchAll(PDO::FETCH_ASSOC);
			
		
			
		
		//to ge male female percentage
		$Query=$dbh->prepare("SELECT p.employee_gender gender,COUNT(w.employee_id) total
				,ROUND((COUNT(w.employee_id)/(SELECT COUNT(employee_id) FROM employee_work_details WHERE enabled=1) )*100) percentage
				FROM employee_work_details w
				INNER JOIN employee_personal_details p
				ON w.employee_id = p.employee_id
				INNER JOIN company_branch br
				ON w.branch_id = br.branch_id
				WHERE w.enabled=1
				GROUP BY p.employee_gender");
		$Query->execute();
		$Query = $Query->fetchAll(PDO::FETCH_ASSOC);
			$abs_count=$dbh->prepare("SELECT COUNT(d.employee_id)-(SELECT COUNT(employee_id) FROM (          
          SELECT DISTINCT w.employee_id-- ,enabled,exempt_attn,w.employee_name-- ,(date_time)
          FROM employee_biometric b
          INNER JOIN device_users d
          ON b.employee_id = d.ref_id
          INNER JOIN employee_work_details w
          ON d.employee_id = w.employee_id AND w.enabled=1 AND w.exempt_attn !=1
          WHERE DATE_FORMAT(b.date_time,'%Y-%m-%d') >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 5 DAY),'%Y-%m-%d')
          AND DATE_FORMAT(b.date_time,'%Y-%m-%d') IN (SELECT selected_date dates FROM 
                    					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
                    					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
                    					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
                    					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
                    					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
                    					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
		                    WHERE selected_date BETWEEN DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 5 DAY),'%Y-%m-%d')  AND DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 1 DAY),'%Y-%m-%d') 
		                    AND DATE_FORMAT(selected_date,'%a') NOT IN ('Sat','Sun')) 
		          -- GROUP BY b.employee_id,DATE_FORMAT(b.date_time,'%Y-%m-%d')
		          ORDER BY b.employee_id,b.date_time) t) abs_count
		FROM device_users d
		INNER JOIN employee_work_details w
		ON d.employee_id = w.employee_id
		WHERE w.enabled=1 AND w.exempt_attn !=1");
		$abs_count->execute();
		$abs_count= $abs_count->fetchAll(PDO::FETCH_ASSOC);

			$Details['total']=$total;
			$Details['joined_count']=$joined;
			$Details["absent_employees"]=$abs_count;
			$Details['exits_count']=$exits;
			$Details["imageRatios"]=$Query;
	
		
		 
		break;
		
		
		case "branchCount":
				//to get branch Head Count of male and Female
			$brachCount="SELECT branch_name,gender,total,CONCAT(ROUND(total/total_count*100),'%') percentage
FROM (
SELECT br.branch_name,p.employee_gender gender,COUNT(w.employee_id) total,total_count
FROM employee_work_details w
INNER JOIN employee_personal_details p 
ON w.employee_id = p.employee_id
INNER JOIN company_branch br
ON w.branch_id = br.branch_id AND w.enabled=1
LEFT JOIN (
    SELECT br.branch_name,COUNT(w.employee_id) total_count
    FROM employee_work_details w
    INNER JOIN company_branch br
    ON w.branch_id = br.branch_id AND w.enabled=1
    GROUP BY w.branch_id) a
ON br.branch_name = a.branch_name
GROUP BY w.branch_id,p.employee_gender)z
ORDER BY branch_name; ";
			$brachCount = $dbh->prepare($brachCount);
			$brachCount->execute();
			$branch_name="";
			$results=array();
			$index =-1;
			while ($row = $brachCount->fetch(PDO::FETCH_ASSOC)){
				
				if($branch_name!=$row["branch_name"])
					$index++;
					$results[$index]["branch_name"]=$row["branch_name"];
					if($row["gender"]=="Male"){
						$results[$index]["malePercentage"]=$row["percentage"];
						$results[$index]["maleTotal"]=$row["total"];
					}else{
						$results[$index]["female"]=$row["gender"];
						$results[$index]["femPercentage"]=$row["percentage"];
						$results[$index]["femTotal"]=$row["total"];
					}
					$branch_name=$row['branch_name'];
			
			}
				
			
			$Details['brachCount']=$results;
			break;
	
		case "teamCount":
			/*
			$teamCount="SELECT ct.team_name,p.employee_gender gender,COUNT(w.employee_id) emp_count
						,ROUND((COUNT(w.employee_id)/(SELECT COUNT(employee_id) FROM employee_work_details WHERE enabled=1) )*100) percentage
						FROM employee_work_details w
						INNER JOIN employee_personal_details p 
						ON w.employee_id = p.employee_id
						INNER JOIN company_team ct
						ON w.team_id = ct.team_id
						WHERE w.enabled=1
					GROUP BY w.team_id,p.employee_gender";
			*/					
			$teamCount="SELECT team_name,gender,emp_count,CONCAT(ROUND(emp_count/total_count*100),'%') percentage
						FROM (
						SELECT ct.team_name,p.employee_gender gender,COUNT(w.employee_id) emp_count,total_count
						FROM employee_work_details w
						INNER JOIN employee_personal_details p 
						ON w.employee_id = p.employee_id
						INNER JOIN company_team ct
						ON w.team_id = ct.team_id AND w.enabled = 1
						LEFT JOIN (
						  SELECT ct.team_name,COUNT(w.employee_id) total_count
						  FROM employee_work_details w
						  INNER JOIN company_team ct
						  ON w.team_id = ct.team_id AND w.enabled = 1
						  GROUP BY ct.team_id ) a
						ON ct.team_name = a.team_name
						GROUP BY w.team_id,p.employee_gender) z
						ORDER BY team_name";
			$teamCount = $dbh->prepare($teamCount);
			$teamCount->execute();
			$team="";
			$results=array();
			$index =-1;
			while ($row = $teamCount->fetch(PDO::FETCH_ASSOC)){
			
				if($team!=$row["team_name"])
					$index++;
					$results[$index]["team_name"]=$row["team_name"];
					if($row["gender"]=="Male"){
						$results[$index]["malePercentage"]=$row["percentage"];
						$results[$index]["maleTotal"]=$row["emp_count"];
					}else{
						$results[$index]["femPercentage"]=$row["percentage"];
						$results[$index]["femTotal"]=$row["emp_count"];
					}
					$team=$row['team_name'];
						
			}
			$Details['teamCount']=$results;
			
				
		break;
		
		case "AgeCount":
			$ageHeadcount="SELECT gender,t.age_range,COUNT(IFNULL(t.age_range,0)) range_count,CONCAT(ROUND((COUNT(IFNULL(t.age_range,0))/total_count)*100),'%') percentage
FROM (
SELECT (w.employee_id) emp_count,p.employee_gender gender,employee_dob,FLOOR(DATEDIFF(NOW(),employee_dob)/365) age
      ,(CASE
        WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 20 and 24 THEN '20 - 24'
        WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 25 and 29 THEN '25 - 29'
        WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 30 and 34 THEN '30 - 34'
        WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 35 and 44 THEN '35 - 44'
        WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 45 and 54 THEN '45 - 54'
        WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 55 and 64 THEN '55 - 64'
        END) as age_range
FROM employee_work_details w
INNER JOIN employee_personal_details p 
ON w.employee_id = p.employee_id AND w.enabled = 1)t
LEFT JOIN (
      SELECT q.age_range,COUNT(employee_id) total_count FROM (
      SELECT (w.employee_id),FLOOR(DATEDIFF(NOW(),employee_dob)/365) age
            ,(CASE
              WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 20 and 24 THEN '20 - 24'
              WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 25 and 29 THEN '25 - 29'
              WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 30 and 34 THEN '30 - 34'
              WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 35 and 44 THEN '35 - 44'
              WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 45 and 54 THEN '45 - 54'
              WHEN FLOOR(DATEDIFF(NOW(),employee_dob)/365) BETWEEN 55 and 64 THEN '55 - 64'
          END) as age_range
      FROM employee_work_details w
      INNER JOIN employee_personal_details p 
      ON w.employee_id = p.employee_id AND w.enabled = 1) q
      GROUP BY age_range) a
ON t.age_range = a.age_range
GROUP BY gender,t.age_range ORDER BY t.age_range;
		";
			$ageHeadcount = $dbh->prepare($ageHeadcount);
			$ageHeadcount->execute();
			$ageHeadcount =$ageHeadcount->fetchAll(PDO::FETCH_ASSOC);
			$Details['AgeCount']=$ageHeadcount;
			
			break;
		
		case "HiredLeft":
			
			
			$startHL=$_SESSION['payrollYear'].'-01-01';
			$endHL= $_SESSION['payrollYear'].'-12-31';
			$hiredLeft="SELECT *,IF(category='left',CONCAT('',emp_count),emp_count) actual_count FROM (
							SELECT 'join' category,doj_month,first_month,COUNT(employee_id) emp_count
								FROM (
									SELECT w.employee_id,employee_doj,DATE_FORMAT(employee_doj,'%m') first_month,DATE_FORMAT(employee_doj,'%b') doj_month
									FROM employee_work_details w
									INNER JOIN employee_personal_details p
									ON w.employee_id = p.employee_id
									WHERE w.enabled = 1 AND employee_doj BETWEEN :startDate AND :endDate
									ORDER BY employee_doj)t
							GROUP BY doj_month UNION
							SELECT 'left',left_month,last_month,COUNT(employee_id) emp_count
							FROM (
									SELECT w.employee_id,last_working_date,DATE_FORMAT(last_working_date,'%m') last_month,DATE_FORMAT(last_working_date,'%b') left_month
									FROM emp_notice_period np
									INNER JOIN employee_work_details w
									ON np.employee_id = w.employee_id
									WHERE last_working_date BETWEEN :startDate AND :endDate AND status='S'
									ORDER BY employee_doj)t
							GROUP BY left_month) q ORDER BY first_month";
		
			$hiredLeft = $dbh->prepare($hiredLeft);
			$hiredLeft->bindParam('startDate', $startHL);
			$hiredLeft->bindParam('endDate', $endHL);
			
			$hiredLeft->execute();
			$results=array();
			$month="";
			$category="";
			$index =-1;
			while ($row = $hiredLeft->fetch(PDO::FETCH_ASSOC)){
				
				if($month!=$row["doj_month"])
					$index++;
				$results[$index]["doj_month"]=$row["doj_month"];
				if($row["category"]=="join"){
					$results[$index]["join_count"]=$row["emp_count"];
					$results[$index]["join_actual_count"]=$row["actual_count"];
				}else{ 
					$results[$index]["left_count"]=$row["emp_count"];
					$results[$index]["left_actual_count"]=$row["actual_count"];
				}
				$month=$row['doj_month'];
				
			}
			
			$Details['HiredLeft']=$results;
			
			
			
		break;
		
		case "currentEmployees":
			$employees="SELECT DATE_FORMAT(p.month_year,'%b') months,w.employee_gender gender,COUNT(w.employee_id) emp_count
							FROM payroll p
							INNER JOIN employee_personal_details w
							ON p.employee_id = w.employee_id
							GROUP BY w.employee_gender,p.month_year
							ORDER BY month_year";
			$employees = $dbh->prepare($employees);
			$employees->execute();
			$results=array();
			$month="";
			$category="";
			$index =-1;
			while ($row = $employees->fetch(PDO::FETCH_ASSOC)){
			
				if($month!=$row["months"])
					$index++;
					
					$results[$index]["month"]=$row["months"];
					if($row["gender"]=="Male"){
						$results[$index]["join_count"]=$row["gender"];
						$results[$index]["maleCount"]=$row["emp_count"];
					}else{
						$results[$index]["left_count"]=$row["gender"];
						$results[$index]["femaleCount"]=$row["emp_count"];
					}
					$month=$row['months'];
			
			}
			$Details['currentEmployees']=$results;
			
		break;	
		case "employeeSpan":
			$Query="SELECT gender,t.exp_range,COUNT(IFNULL(t.exp_range,0)) range_count,CONCAT(ROUND((COUNT(IFNULL(t.exp_range,0))/total_count)*100),'%') percentage
FROM (
SELECT (w.employee_id) emp_count,p.employee_gender gender,employee_doj,TIMESTAMPDIFF(MONTH,employee_doj,NOW()) experience
       ,(CASE
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 0 and 6 THEN '0-6 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 7 and 12 THEN '7-12 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 13 and 18 THEN '13-18 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 19 and 24 THEN '19-24 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 25 and 36 THEN '25-36 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 37 and 48 THEN '37-48 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 49 and 60 THEN '49-60 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 19 and 27 THEN '19-24 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) >60  THEN 'Above 60 months' END) as exp_range
FROM employee_work_details w
INNER JOIN employee_personal_details p 
ON w.employee_id = p.employee_id AND w.enabled = 1)t
LEFT JOIN ( 
     SELECT q.exp_range,COUNT(employee_id) total_count FROM (
     SELECT (w.employee_id),TIMESTAMPDIFF(MONTH,employee_doj,NOW()) experience
       ,(CASE
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 0 and 6 THEN '0-6 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 7 and 12 THEN '7-12 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 13 and 18 THEN '13-18 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 19 and 24 THEN '19-24 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 25 and 36 THEN '25-36 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 37 and 48 THEN '37-48 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 49 and 60 THEN '49-60 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) BETWEEN 19 and 27 THEN '19-24 months'
        WHEN TIMESTAMPDIFF(MONTH,employee_doj,NOW()) >60  THEN 'Above 60 months' END) as exp_range
    FROM employee_work_details w
    INNER JOIN employee_personal_details p 
    ON w.employee_id = p.employee_id AND w.enabled = 1)q
    GROUP BY q.exp_range) a
ON t.exp_range = a.exp_range
GROUP BY gender,t.exp_range ORDER BY t.experience;";
			$Query = $dbh->prepare($Query);
			$Query->execute();
			$results=array();
			$range="";
			$index =-1;
			while ($row = $Query->fetch(PDO::FETCH_ASSOC)){
			
				if($range!=$row["exp_range"])
					$index++;
					$results[$index]["exp_range"]=$row["exp_range"];
					if($row["gender"]=="Male"){
						$results[$index]["range_male"]=$row["range_count"];
						$results[$index]["per_male"]=$row["percentage"];
						
					}else{
						$results[$index]["range_female"]=$row["range_count"];
						$results[$index]["per_female"]=$row["percentage"];
						
					}
					
					$range=$row['exp_range'];
			
			}
				
			
			$Details['employeeSpan']=$results;
			
		break;
		
		case "imagePercentage":
			$Query="SELECT p.employee_gender gender,COUNT(w.employee_id) total
				,ROUND((COUNT(w.employee_id)/(SELECT COUNT(employee_id) FROM employee_work_details WHERE enabled=1) )*100) percentage
				FROM employee_work_details w
				INNER JOIN employee_personal_details p
				ON w.employee_id = p.employee_id
				INNER JOIN company_branch br
				ON w.branch_id = br.branch_id
				WHERE w.enabled=1
				GROUP BY p.employee_gender";
			$Query = $dbh->prepare($Query);
			$Query->execute();
			$results=array();
			$range="";
			$results = $Query->fetchAll(PDO::FETCH_ASSOC);
			//$results[0]['percentage']='97';
			//print_r($results);
		//	print_r($results);
			$Details['Percentage']=$results;
			
			break;
		case "HoverData":
			//to get hover data
			$JoinedEmp=$dbh->prepare("SELECT w.employee_id,w.employee_name,des.designation_name,
b.branch_name,ct.team_name,p.employee_gender FROM employee_work_details w
			LEFT JOIN employee_personal_details  p ON w.employee_id = p.employee_id
				LEFT JOIN company_departments dept ON w.department_id = dept.department_id
				LEFT JOIN company_designations des ON des.designation_id=w.designation_id
				LEFT JOIN company_branch b ON w.branch_id = b.branch_id
				LEFT JOIN company_team ct ON ct.team_id=w.team_id
				LEFT JOIN employee_personal_details pd
				ON pd.employee_id=w.employee_id
			WHERE w.employee_doj BETWEEN :startDate AND :endDate AND w.enabled=1
			
				");
			$JoinedEmp->bindParam('startDate', $startDate);
			$JoinedEmp->bindParam('endDate', $endDate);
			$JoinedEmp->execute();
			$JoinedEmp = $JoinedEmp->fetchAll(PDO::FETCH_ASSOC);
			$Details["JoinedEmp"]=$JoinedEmp;
			
			
			$ExitEmp=$dbh->prepare("SELECT n.employee_id,DATE_FORMAT(n.last_working_date,'%d-%m-%Y') lastWorkingDay,w.employee_name,n.notice_date,pd.employee_gender,
					IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Yr')) experience
FROM emp_notice_period n 
LEFT JOIN employee_work_details w
				ON w.employee_id=n.employee_id
			-- 	LEFT JOIN company_departments dept ON n.department_id = dept.department_id
			-- 	LEFT JOIN company_designations des ON des.designation_id=w.designation_id
			-- 	LEFT JOIN company_branch b ON w.branch_id = b.branch_id
			-- 	LEFT JOIN company_team ct ON ct.team_id=w.team_id 
				LEFT JOIN employee_personal_details pd
				ON pd.employee_id=n.employee_id
WHERE n.last_working_date BETWEEN :startDate AND :endDate AND status='S'");
			
			$ExitEmp->bindParam('startDate', $startDate);
			$ExitEmp->bindParam('endDate', $endDate);
			$ExitEmp->execute();
			$ExitEmp = $ExitEmp->fetchAll(PDO::FETCH_ASSOC);
			
			$Details["ExitEmp"]=$ExitEmp;
			
			$imageMaleCount=$dbh->prepare("SELECT p.employee_gender gender,COUNT(w.employee_id) total
					,ROUND((COUNT(w.employee_id)/(SELECT COUNT(employee_id) FROM employee_work_details WHERE enabled=1) )*100) percentage
				FROM employee_work_details w
				INNER JOIN employee_personal_details p
				ON w.employee_id = p.employee_id
				INNER JOIN company_branch br
				ON w.branch_id = br.branch_id
			    WHERE w.enabled=1
				GROUP BY p.employee_gender

					");
			$imageMaleCount->execute();
			$imageMaleCount = $imageMaleCount->fetchAll(PDO::FETCH_ASSOC);
			
			$Details["Empcount"]=$imageMaleCount;
				$absCount=$dbh->prepare("SELECT d.employee_id,w.employee_name,p.employee_gender
					FROM device_users d
					INNER JOIN employee_work_details w
					ON d.employee_id = w.employee_id
					INNER JOIN employee_personal_details p
          			ON d.employee_id = p.employee_id
					WHERE w.enabled=1 AND w.exempt_attn !=1 AND d.ref_id NOT IN ('$mem')");
			$absCount->execute();
			$absCount= $absCount->fetchAll(PDO::FETCH_ASSOC);
			
			$Details["absEmp"]=$absCount;
			
		break;	
	}
	
	
	
	echo json_encode($Details);
}