<?php
function _view($for=null,$month=null){
	
	//for to check me or other employees
	if(!$for){
		$empId=isset($_REQUEST['empId'])?$_REQUEST['empId']:""; //others
	}elseif($_SESSION['authprivilage']=="employee"){
		$empId=$_SESSION['employee_id']; //employee 
	}else{
		$empId=$_SESSION['login_id']; //hr
	}
	
	
	
	$dbh = getdbh();
	//to get current payroll month startDate and endDate
		$stmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
  							FROM company_details WHERE info_flag="A" AND company_id=:company_id');
		$stmt->bindParam('company_id', $_SESSION['company_id']);
		$stmt->execute();
		$companyProp = $stmt->fetch();
		
	
	
	if(!$month){
		if($companyProp['attendance_dt'] !=1){
			$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".$companyProp['attendance_dt'];
			$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
			$endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
		}else{
			$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
			$endDate = date('Y-m-t',strtotime($startDate));
		}
	}else{
		$month=date('Y-m', strtotime($month));
		$arr = explode("-", $month);
		$year = $arr[0];
		$month = $arr[1];
		if($companyProp['attendance_dt'] !=1){
			$startDate = $year."-".($month)."-".$companyProp['attendance_dt'];
			$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
			$endDate = $year."-".($month)."-".($companyProp['attendance_dt']-1);
		}else{
			$startDate = $year."-".($month)."-01";
			$endDate = date('Y-m-t',strtotime($startDate));
		}
		
	}
	
/*
	$stmt=("SELECT ID,NAME,shift_name,work_day,team_name,DATE,SUBSTRING_INDEX(check_in,':',2) check_in,SUBSTRING_INDEX(check_out,':',2) check_out,all_punches,IF(SUBSTRING_INDEX(late,':',2)!='00:00',SUBSTRING_INDEX(late,':',2),'-') late,
      IF(SUBSTRING_INDEX(IF(earlyout>shift_hrs,shift_hrs,earlyout),':',2)!='00:00',SUBSTRING_INDEX(IF(earlyout>shift_hrs,shift_hrs,earlyout),':',2),'-') earlyout,
      IF(late_status!='-',late_status,IF(late_status='-' AND late!='-' AND SUBSTRING_INDEX(late,':',2)!='00:00','NA',late_status)) late_status,
      IF(early_status!='-',early_status,IF(early_status='-' AND earlyout!='-' AND SUBSTRING_INDEX(earlyout,':',2)!='00:00','NA',early_status)) early_status
      ,IFNULL(SUBSTRING_INDEX(IF(late!='-' AND earlyout!='-',ADDTIME((late),IF(earlyout>shift_hrs,shift_hrs,earlyout)),IF(late!='-',late,IF(earlyout>shift_hrs,shift_hrs,earlyout))),':',2),'-') total_time
FROM (
SELECT employee_id ID,employee_name NAME,shift_name,work_day,DATE_FORMAT(work_day,'%a %d %b,%Y') DATE,DATE_FORMAT(check_in,'%T') check_in,IF(DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(check_out,'%T'),'-') check_out,all_punches,
    check_in In_time,check_out OUT_time,is_day,late_end,
    IF(check_in>CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00'),SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00')))),'-') late,
    (CASE WHEN DATE_FORMAT(check_in,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') OR (is_day=1 AND check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00')) 
             OR(is_day =0 AND DATE_FORMAT(check_out,'%H:%i') BETWEEN TIME(shift_end_time) AND TIME(late_end)) OR (is_day=0 AND check_out >CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00')) THEN '-'-- AND TIME(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_out,check_in))))>=TIME(shift_hrs)
          WHEN check_in=check_out AND is_day=0 THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(check_out,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN is_day=1 AND check_out < CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) 
          WHEN is_day=0 AND shift_end_time NOT BETWEEN '00:00' AND '06:00' AND check_out<CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_END_time,':00') THEN TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out) -- SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN is_day=0 AND shift_end_time BETWEEN '00:00' AND '06:00' AND  check_out<CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          ELSE '-' END) earlyout,
    IFNULL(early_stat,'-') early_status,IFNULL(late_stat,'-') late_status,shift_hrs,team_name
FROM (
    SELECT employee_id,ref_id,shift_id,from_date,to_date,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,
    (CASE WHEN is_day=1 THEN MIN(date_time)
         WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END)  'check_in',
   (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',

      is_day,shift_name,start_time shift_st_time,end_time shift_end_time,early_start,late_end,shift_hrs,CONCAT(employee_name,' ',employee_lastname) employee_name,team_name
   FROM (
	SELECT * FROM (
     SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.shift_hrs,b.date_time,
        (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
                  ELSE date_time END) work_day,
            s.shift_name,s.start_time,s.end_time,z.employee_name,z.employee_lastname,team_name
     FROM (
       SELECT w.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,
             IF(r.shift_id IS NULL,:startDate,IF(r.to_date IS NULL AND r.from_date <:startDate OR r.from_date < :startDate,
             :startDate,DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IF(r.to_date!='0000-00-00',r.to_date,:endDate) to_date,
                 w.employee_doj,w.employee_name,w.employee_lastname,w.team_id
		FROM employee_work_details w
		LEFT JOIN device_users u
		ON w.employee_id = u.employee_id
		LEFT JOIN  shift_roaster r
		ON r.employee_id =:empId
		AND (DATE_FORMAT(to_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate OR DATE_FORMAT(from_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate
		OR (to_date ='0000-00-00' OR to_date IS NULL AND from_date BETWEEN :startDate AND :endDate) 
		OR (to_date ='0000-00-00' OR to_date IS NULL AND DATE_FORMAT(from_date,'%Y-%m-%d') < :startDate))
		WHERE w.enabled = 1 AND w.employee_id=:empId  ) z
   LEFT JOIN company_shifts s ON z.shift_id = s.shift_id
   LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id
   LEFT JOIN company_team t ON z.team_id = t.team_id
   AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND IF(is_day=0,DATE_ADD(to_date,INTERVAL 1 DAY),to_date)
   WHERE s.is_day IS NOT NULL ORDER BY z.employee_id,date_time)e
   WHERE date_time BETWEEN from_date AND to_date)q
   GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')) a
    INNER JOIN (SELECT employee_id EMPID,Name,dates,DATE_FORMAT(dates,'%d %b,%Y') Date_Formatted,GROUP_CONCAT(punch ORDER BY date_time) all_punches
FROM (
SELECT employee_id,Name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') dates,DATE_FORMAT(work_day,'%H:%i') punch
FROM (
     SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,
           (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY)
                  ELSE date_time END) work_day
     FROM (
     SELECT w.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,
             IF(r.shift_id IS NULL,:startDate,IF(r.to_date IS NULL AND r.from_date <:startDate OR r.from_date < :startDate,
             :startDate,DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IF(r.to_date!='0000-00-00',r.to_date,:endDate) to_date,
                 w.employee_doj,w.employee_name,w.employee_lastname,w.team_id
	FROM employee_work_details w
	LEFT JOIN device_users u
	ON w.employee_id = u.employee_id
	LEFT JOIN  shift_roaster r
	ON r.employee_id =:empId
	AND (DATE_FORMAT(to_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate OR DATE_FORMAT(from_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate
	OR (to_date ='0000-00-00' OR to_date IS NULL AND from_date BETWEEN :startDate AND :endDate) 
	OR (to_date ='0000-00-00' OR to_date IS NULL AND DATE_FORMAT(from_date,'%Y-%m-%d') < :startDate))
	WHERE w.enabled = 1 AND w.employee_id=:empId ) z
     LEFT JOIN company_shifts s
     ON z.shift_id = s.shift_id
     LEFT JOIN device_users du
     ON z.employee_id = du.employee_id
     LEFT JOIN employee_biometric b
     ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND IF(is_day=0,DATE_ADD(to_date,INTERVAL 1 DAY),to_date)
     WHERE s.is_day IS NOT NULL
     ORDER BY z.employee_id,work_day) q
GROUP BY employee_id,work_day
)t GROUP BY employee_id,dates) t
ON a.employee_id = t.EMPID AND a.work_day = dates
LEFT JOIN (SELECT employee_id EMPID,DATE_FORMAT(day,'%Y-%m-%d') day,regularize_type,
            IF(regularize_type='Late',status,'-') late_stat,
            IF(regularize_type='EarlyOut',status,'-') early_stat           
           FROM attendance_regularization at
           WHERE day BETWEEN :startDate AND :endDate AND regularize_type!='Incorrectpunches') e ON a.employee_id = e.EMPID AND a.work_day = day
GROUP BY employee_id,work_day,regularize_type ORDER BY work_day DESC
)z WHERE work_day BETWEEN :startDate AND :endDate;
			
");*/
	
 $query ="SELECT ID,NAME,shift_name,work_day,team_name,DATE,SUBSTRING_INDEX(check_in,':',2) check_in,SUBSTRING_INDEX(check_out,':',2) check_out,all_punches,
      IF(is_weekday IN('FD','FH') OR SUBSTRING_INDEX(late,':',2)!='00:00',SUBSTRING_INDEX(late,':',2),'-') late,
      IF(is_weekday IN('FD','SH'),'-',IF(SUBSTRING_INDEX(IF(earlyout>shift_hrs,shift_hrs,earlyout),':',2)!='00:00',SUBSTRING_INDEX(IF(earlyout>shift_hrs,shift_hrs,earlyout),':',2),'-')) earlyout,
      IF(late_status!='-',late_status,IF((day_type IN('FD','FH') OR is_weekday IN('FD','FH')),'-',IF(late_status='-' AND late!='-' AND SUBSTRING_INDEX(late,':',2)!='00:00' ,'NA',late_status))) late_status,
      IF(early_status!='-',early_status,IF((day_type IN('FD','SH') OR is_weekday IN('FD','SH')),'-',IF((early_status='-' AND earlyout!='-' AND SUBSTRING_INDEX(earlyout,':',2)!='00:00'),'NA',early_status))) early_status
      -- ,IFNULL(SUBSTRING_INDEX(IF(late!='-' AND earlyout!='-',IF((day_type='SH' OR is_weekday='SH'),late,IF(ADDTIME((late),IF(earlyout>TIME(shift_hrs),shift_hrs,earlyout))>shift_hrs,shift_hrs,ADDTIME((late),IF(earlyout>shift_hrs,shift_hrs,earlyout)))),IF(late!='-',IF((day_type IN ('FD','FH') OR is_weekday IN('FD','FH')),'-',late),IF((day_type IN ('FD','SH') OR is_weekday IN('FD','SH')),'-',IF(earlyout>shift_hrs,shift_hrs,earlyout)))),':',2),'-') total_time 
	  ,IFNULL(SUBSTRING_INDEX(IF(late!='-' AND earlyout!='-',IF((day_type='SH' OR is_weekday='SH'),late,IF(ADDTIME((IF(late='-','00:00:00',late)),SUBSTRING_INDEX(IF(earlyout>shift_hrs,shift_hrs,earlyout),'.',1))>TIME(shift_hrs),shift_hrs,ADDTIME((IF(late='-','00:00:00',late)),SUBSTRING_INDEX(IF(earlyout>shift_hrs,shift_hrs,IF(earlyout='-','00:00:00',earlyout)),'.',1)))),IF(late!='-',IF((day_type IN ('FD','FH') OR is_weekday IN('FD','FH')),'-',late),IF((day_type IN ('FD','SH') OR is_weekday IN('FD','SH')),'-',SUBSTRING_INDEX(IF(earlyout>shift_hrs,shift_hrs,IF(earlyout='-','00:00:00',earlyout)),'.',1)))),':',2),'-') total_time 
FROM (
SELECT employee_id ID,employee_name NAME,shift_name,work_day,DATE_FORMAT(work_day,'%a %d %b,%Y') DATE,DATE_FORMAT(check_in,'%T') check_in,IF(DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(check_out,'%T'),'-') check_out,all_punches,
    check_in In_time,check_out OUT_time,is_day,late_end,
    IF(day_type IN('FD','FH') OR is_weekday IN('FD','FH') OR check_in<=CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00') OR shift_id='SH63590','-',SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00'))))) late,
    (CASE WHEN DATE_FORMAT(check_in,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') OR (is_day=1 AND check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00')) 
             OR(is_day =0 AND DATE_FORMAT(check_out,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_out,'%H:%i') NOT BETWEEN TIME(shift_end_time) AND TIME(late_end)) OR (is_day=0 AND check_out >CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00')) OR day_type ='SH' OR day_type IN('FD','SH') OR is_weekday='FD' OR shift_id='SH63590' THEN '-'-- AND TIME(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_out,check_in))))>=TIME(shift_hrs)
          WHEN check_in=check_out AND is_day=0 THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(check_out,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN is_day=1 AND check_out < CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) 
          WHEN is_day=0 AND shift_end_time NOT BETWEEN '00:00' AND '10:00' AND check_out<CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_END_time,':00') THEN TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out) -- SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN is_day=0 AND shift_end_time BETWEEN '00:00' AND '10:00' AND  check_out<CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          ELSE '-' END) earlyout,
    IF((day_type='SH' OR is_weekday IN('FD','SH')) AND early_stat IS NOT NULL,'-',IFNULL(early_stat,'-')) early_status,IF((day_type='FH' OR is_weekday IN('FD','FH')) AND late_stat IS NOT NULL,'-',IFNULL(late_stat,'-')) late_status,shift_hrs,team_name,day_type,is_weekday
FROM (
    SELECT employee_id,ref_id,shift_id,from_date,to_date,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,
    (CASE WHEN is_day=1 THEN MIN(date_time)
         WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),MIN(date_time)) END)  'check_in',
   (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',

      is_day,shift_name,start_time shift_st_time,end_time shift_end_time,early_start,late_end,shift_hrs,CONCAT(employee_name,' ',employee_lastname) employee_name,team_name
   FROM (
	SELECT * FROM (
     SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.shift_hrs,b.date_time,
        (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
                  ELSE date_time END) work_day,
            s.shift_name,s.start_time,s.end_time,z.employee_name,z.employee_lastname,team_name
     FROM (
       SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,
	      IF(from_date<:startDate,:startDate,from_date) from_date,
	      IF(to_date='0000-00-00' OR to_date IS NULL ,:endDate,to_date) to_date
	FROM shift_roaster r
	INNER JOIN employee_work_details w
	ON r.employee_id = w.employee_id
	LEFT JOIN device_users u
	ON w.employee_id = u.employee_id
	WHERE  r.employee_id=:empId
	AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
	 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate ))  ) z
   LEFT JOIN company_shifts s ON z.shift_id = s.shift_id
   LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id
   LEFT JOIN company_team t ON z.team_id = t.team_id
   AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND IF(is_day=0,DATE_ADD(to_date,INTERVAL 1 DAY),to_date)
   WHERE s.is_day IS NOT NULL ORDER BY z.employee_id,date_time)e
   WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN from_date AND to_date)q
   GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')) a
    INNER JOIN (SELECT employee_id EMPID,Name,dates,DATE_FORMAT(dates,'%d %b,%Y') Date_Formatted,GROUP_CONCAT(punch ORDER BY date_time) all_punches
FROM (
SELECT employee_id,Name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') dates,DATE_FORMAT(work_day,'%H:%i') punch
FROM (
     SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,
           (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY)
                  ELSE date_time END) work_day
     FROM (
	     SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,
	      IF(from_date<:startDate,:startDate,from_date) from_date,
	      IF(to_date='0000-00-00' OR to_date IS NULL ,:endDate,to_date) to_date
	FROM shift_roaster r
	INNER JOIN employee_work_details w
	ON r.employee_id = w.employee_id
	LEFT JOIN device_users u
	ON w.employee_id = u.employee_id
	WHERE  r.employee_id=:empId
	AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
	 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate )) ) z
     LEFT JOIN company_shifts s
     ON z.shift_id = s.shift_id
     LEFT JOIN device_users du
     ON z.employee_id = du.employee_id
     LEFT JOIN employee_biometric b
     ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND IF(is_day=0,DATE_ADD(to_date,INTERVAL 1 DAY),to_date)
     WHERE s.is_day IS NOT NULL
     ORDER BY z.employee_id,work_day) q
GROUP BY employee_id,work_day
)t GROUP BY employee_id,dates) t
ON a.employee_id = t.EMPID AND a.work_day = dates
LEFT JOIN (SELECT EMPID,day,MAX(late_stat) late_stat,MAX(early_stat) early_stat
          FROM (
          SELECT DISTINCT employee_id EMPID,DATE_FORMAT(day,'%Y-%m-%d') day,regularize_type,
            IF(regularize_type='Late',status,'-') late_stat,
            IF(regularize_type='EarlyOut',status,'-') early_stat           
           FROM attendance_regularization at
           WHERE day BETWEEN :startDate AND :endDate AND regularize_type!='Incorrectpunches'
           AND at.employee_id=:empId) t GROUP BY day) e ON a.work_day = day
LEFT JOIN (SELECT ab.employee_id absent_id,ab.absent_date,ab.day_type
           FROM emp_absences ab
           WHERE ab.absent_date BETWEEN :startDate AND :endDate) p
ON a.employee_id = p.absent_id AND a.work_day = p.absent_date 
LEFT JOIN (SELECT dates,DATE_FORMAT(dates,'%d') day,CONCAT(DATE_FORMAT(dates,'%W'),',',DATE_FORMAT(dates,'%d %b,%Y')) full_date,is_weekday
FROM (      
					SELECT employee_id,z.shift_id,dates,from_date,to_date,
					      (CASE WHEN (weeks = IF((WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)>5,(WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) - 1),WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)) 
					                  THEN (CASE WHEN (DAYNAME(dates)='sunday') THEN sunday WHEN (DAYNAME(dates)='monday') THEN monday WHEN (DAYNAME(dates)='tuesday') THEN tuesday WHEN (DAYNAME(dates)='wednesday') THEN wednesday WHEN (DAYNAME(thursday)='sunday') THEN thursday WHEN (DAYNAME(dates)='friday') THEN friday WHEN (DAYNAME(dates)='saturday') THEN saturday ELSE '' END)
					            END) is_weekday
					FROM (
					SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<:startDate,:startDate,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,:endDate,to_date) to_date
					FROM shift_roaster r
					INNER JOIN employee_work_details w
					ON r.employee_id = w.employee_id
					LEFT JOIN device_users u
					ON w.employee_id = u.employee_id
					WHERE  r.employee_id=:empId
					AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
					 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate )))z
					LEFT JOIN weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					  JOIN (SELECT selected_date dates FROM 
					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
					              WHERE selected_date BETWEEN :startDate  AND :endDate) t
                        WHERE dates BETWEEN from_date AND to_date)q
					  WHERE is_weekday IS NOT NULL
					)u ON a.work_day = u.dates 
GROUP BY employee_id,work_day ORDER BY work_day DESC
)z WHERE work_day BETWEEN :startDate AND :endDate;";
 
 //print_r($query);
	$stmt=$dbh->prepare($query);
	$stmt->bindParam('empId', $empId);
	$stmt->bindParam('startDate', $startDate);
	$stmt->bindParam('endDate', $endDate);
	$stmt->execute();
	
	//if not executed throws an error
	if(!$stmt)
		print_r($dbh->errorInfo());
	
	$attendances=array();
	
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		

		//split by comma & store in array
		$coloredString = array();
		$punchStrings=explode(",",$row['all_punches']);
		for($i=0;$i<count($punchStrings);$i++){
			$num= $i+1;
			if($num%2==0){ //even
				$coloredString[] ='<span class="text-danger">'.$punchStrings[$i].'</span>';
			}else{
				$coloredString[]='<span class="text-success">'.$punchStrings[$i].'</span>';
			}
		}
		
		$row["all_punches"] = implode(", ", $coloredString);
		$attendances[] = $row; 
	}
	
	/*
	$sum=$dbh->prepare("SELECT SUBSTRING_INDEX(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(upapproved_late!='-' AND upapproved_earlyOut!='-',ADDTIME(upapproved_late,upapproved_earlyOut),IF(upapproved_late!='-',upapproved_late,upapproved_earlyOut))))),':',2) total_time 
			FROM (
		SELECT *,IF(work_day=day AND regularize_type='Late' AND SUBSTRING_INDEX(status,'-',-1)='Approved' OR SUBSTRING_INDEX(late,':',2)='00:00','-',IF(work_day=day AND (regularize_type='Late,EarlyOut' OR regularize_type='EarlyOut,Late'),'',IF(late>shift_hrs,shift_hrs,late))) upapproved_late,
        IF(work_day=day AND regularize_type='EarlyOut' AND SUBSTRING_INDEX(status,'-',-1)='Approved' OR SUBSTRING_INDEX(earlyout,':',2)='00:00','-',IF(work_day=day AND (regularize_type='EarlyOut,Late' OR regularize_type='Late,EarlyOut'),'',IF(earlyout>shift_hrs,shift_hrs,earlyout)))upapproved_earlyOut
	FROM (
SELECT employee_id ID,employee_name NAME,shift_name,work_day,DATE_FORMAT(work_day,'%a %d %b,%Y') DATE,DATE_FORMAT(check_in,'%T') check_in,DATE_FORMAT(check_out,'%T') check_out,
		day,regularize_type,status,shift_hrs,
    IF(check_in>CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00'),SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00')))),'-') late,
    -- (CASE WHEN DATE_FORMAT(check_in,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') OR check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN '-'-- AND TIME(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_out,check_in))))>=TIME(shift_hrs)
          -- WHEN is_day=1 AND check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) 
          -- WHEN check_in=check_out AND is_day=0 THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(check_out,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          -- WHEN check_out<CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          -- ELSE '-' END) earlyout
    (CASE WHEN DATE_FORMAT(check_in,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') OR (is_day=1 AND check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00')) 
             OR(is_day =0 AND DATE_FORMAT(check_out,'%H:%i') BETWEEN TIME(shift_end_time) AND TIME(late_end)) OR (is_day=0 AND check_out >CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00')) THEN '-'-- AND TIME(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_out,check_in))))>=TIME(shift_hrs)
          WHEN check_in=check_out AND is_day=0 THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(check_out,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN is_day=1 AND check_out < CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) 
          WHEN is_day=0 AND shift_end_time NOT BETWEEN '00:00' AND '06:00' AND check_out<CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_END_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) -- SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN DATE_FORMAT(check_out,'%Y-%m-%d') AND is_day=0 AND shift_end_time BETWEEN '00:00' AND '06:00' AND  check_out<CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          ELSE '-' END) earlyout
FROM (
	SELECT employee_id,ref_id,shift_id,from_date,to_date,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,
    (CASE WHEN is_day=1 THEN MIN(date_time)
         WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END)  'check_in',
   (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',
      is_day,shift_name,start_time shift_st_time,end_time shift_end_time,early_start,late_end,shift_hrs,CONCAT(employee_name,' ',employee_lastname) employee_name
   FROM (
     SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,b.date_time,
       (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
                  -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
                  ELSE date_time END) work_day,
            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                   THEN DATE_FORMAT(DATE_SUB(date_time, INTERVAL 1 DAY),'%H:%i') ELSE DATE_FORMAT(date_time,'%H:%i') END) punches,
            s.shift_name,s.start_time,s.end_time,s.shift_hrs,z.employee_name,z.employee_lastname
     FROM (
       SELECT w.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,
             IF(r.shift_id IS NULL,:startDate,IF(r.to_date IS NULL AND r.from_date <:startDate OR r.from_date < :startDate,
             :startDate,DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IF(r.to_date!='0000-00-00',r.to_date,:endDate) to_date,
                 w.employee_doj,w.employee_name,w.employee_lastname,w.team_id
		FROM employee_work_details w
		LEFT JOIN device_users u
		ON w.employee_id = u.employee_id
		LEFT JOIN  shift_roaster r
		ON r.employee_id =:empId
		AND (DATE_FORMAT(to_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate OR DATE_FORMAT(from_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate
		OR (to_date ='0000-00-00' OR to_date IS NULL AND from_date BETWEEN :startDate AND :endDate) 
		OR (to_date ='0000-00-00' OR to_date IS NULL AND DATE_FORMAT(from_date,'%Y-%m-%d') < :startDate))
		WHERE w.enabled = 1 AND w.employee_id=:empId  ) z
   LEFT JOIN company_shifts s ON z.shift_id = s.shift_id
   LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id
      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND IF(is_day=0,DATE_ADD(to_date,INTERVAL 1 DAY),to_date)
      WHERE s.is_day IS NOT NULL ORDER BY z.employee_id,date_time)q
   GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')) a
	LEFT JOIN (SELECT EMPID,day,regularize_type,CONCAT(regularize_type,'-',sts) status FROM (
            SELECT employee_id EMPID,DATE_FORMAT(day,'%Y-%m-%d') day,regularize_type,
                (CASE WHEN status='A' THEN 'Approved' WHEN status='RQ' THEN 'Requested' WHEN status='W' THEN 'Withdrawn'
                     WHEN status='R' THEN 'Rejected' END) sts
               FROM attendance_regularization at
               WHERE day BETWEEN :startDate AND :endDate AND regularize_type!='Incorrectpunches')r) t
ON a.employee_id = t.EMPID AND a.work_day = day
-- WHERE work_day != DATE_FORMAT(NOW(),'%Y-%m-%d')			
GROUP BY employee_id,work_day,regularize_type
)z )q WHERE work_day BETWEEN :startDate AND :endDate;");*/
	
	$sum=$dbh->prepare("SELECT SUBSTRING_INDEX(SEC_TO_TIME(SUM(TIME_TO_SEC(total_time))),':',2) total_time FROM (
		SELECT DISTINCT IFNULL(day,work_day),
    SUBSTRING_INDEX(SEC_TO_TIME((TIME_TO_SEC(IF(upapproved_late!='-' AND upapproved_earlyOut!='-',ADDTIME(upapproved_late,upapproved_earlyOut),IF(upapproved_late!='-',upapproved_late,upapproved_earlyOut))))),':',2) total_time 
		FROM (
		SELECT *,IF(work_day=day AND regularize_type='Late' AND SUBSTRING_INDEX(status,'-',-1)='Approved' OR SUBSTRING_INDEX(late,':',2)='00:00' OR ((work_day = absent_date AND day_type IN('FD','FH')) OR is_weekday IN('FD','FH')),'-',
                  IF(work_day=day AND (regularize_type='Late,EarlyOut' OR regularize_type='EarlyOut,Late'),'',IF(late>shift_hrs,shift_hrs,late))) upapproved_late,
        IF(work_day=day AND regularize_type='EarlyOut' AND SUBSTRING_INDEX(status,'-',-1)='Approved' OR SUBSTRING_INDEX(earlyout,':',2)='00:00' OR ((work_day = absent_date AND day_type IN('FD','SH')) OR is_weekday IN('FD','SH')),'-',
                  IF(work_day=day AND (regularize_type='EarlyOut,Late' OR regularize_type='Late,EarlyOut'),'',IF(earlyout>shift_hrs,shift_hrs,earlyout)))upapproved_earlyOut
	FROM (
SELECT employee_id ID,employee_name NAME,shift_name,work_day,DATE_FORMAT(work_day,'%a %d %b,%Y') DATE,DATE_FORMAT(check_in,'%T') check_in,DATE_FORMAT(check_out,'%T') check_out,
		day,regularize_type,status,shift_hrs,absent_date,day_type,is_weekday,
    IF(shift_id!='SH63590' AND check_in>CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00'),SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00')))),'-') late,
    /*(CASE WHEN DATE_FORMAT(check_in,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') OR check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN '-'-- AND TIME(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_out,check_in))))>=TIME(shift_hrs)
          WHEN is_day=1 AND check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) 
          WHEN check_in=check_out AND is_day=0 THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(check_out,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN check_out<CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          ELSE '-' END) earlyout*/
    (CASE WHEN DATE_FORMAT(check_in,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') OR (is_day=1 AND check_out >CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00')) 
             OR(is_day =0 AND DATE_FORMAT(check_out,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_out,'%H:%i') NOT BETWEEN TIME(shift_end_time) AND TIME(late_end)) OR (is_day=0 AND check_out >CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00')) OR shift_id='SH63590' THEN '-'-- AND TIME(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_out,check_in))))>=TIME(shift_hrs)
          WHEN check_in=check_out AND is_day=0 THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(check_out,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN is_day=1 AND check_out < CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) 
          WHEN is_day=0 AND shift_end_time NOT BETWEEN '00:00' AND '10:00' AND check_out<CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_END_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))) -- SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          WHEN DATE_FORMAT(check_out,'%Y-%m-%d') AND is_day=0 AND shift_end_time BETWEEN '00:00' AND '10:00' AND  check_out<CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00') THEN SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00'),check_out)))
          ELSE '-' END) earlyout
FROM (
	SELECT employee_id,ref_id,shift_id,from_date,to_date,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,
    (CASE WHEN is_day=1 THEN MIN(date_time)
          WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),MIN(date_time)) END)  'check_in',
   (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',

      is_day,shift_name,start_time shift_st_time,end_time shift_end_time,early_start,late_end,shift_hrs,CONCAT(employee_name,' ',employee_lastname) employee_name,team_name
   FROM (
	SELECT * FROM (
     SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.shift_hrs,b.date_time,
        (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
                  ELSE date_time END) work_day,
            s.shift_name,s.start_time,s.end_time,z.employee_name,z.employee_lastname,team_name
     FROM (
       SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,
	      IF(from_date<:startDate,:startDate,from_date) from_date,
	      IF(to_date='0000-00-00' OR to_date IS NULL ,:endDate,to_date) to_date
	FROM shift_roaster r
	INNER JOIN employee_work_details w
	ON r.employee_id = w.employee_id
	LEFT JOIN device_users u
	ON w.employee_id = u.employee_id
	WHERE  r.employee_id=:empId
	AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
	 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate ))  ) z
   LEFT JOIN company_shifts s ON z.shift_id = s.shift_id
   LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id
   LEFT JOIN company_team t ON z.team_id = t.team_id
   AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND IF(is_day=0,DATE_ADD(to_date,INTERVAL 1 DAY),to_date)
   WHERE s.is_day IS NOT NULL ORDER BY z.employee_id,date_time)e
   WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN from_date AND to_date)q
   GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')) a
	LEFT JOIN (SELECT EMPID,day,regularize_type,CONCAT(regularize_type,'-',sts) status FROM (
            SELECT DISTINCT employee_id EMPID,DATE_FORMAT(day,'%Y-%m-%d') day,regularize_type,
                (CASE WHEN status='A' THEN 'Approved' WHEN status='RQ' THEN 'Requested' WHEN status='W' THEN 'Withdrawn'
                     WHEN status='R' THEN 'Rejected' END) sts
               FROM attendance_regularization at
               WHERE at.employee_id=:empId AND day BETWEEN :startDate AND :endDate AND regularize_type!='Incorrectpunches')r) t
ON a.employee_id = t.EMPID AND a.work_day = day
LEFT JOIN (SELECT ab.employee_id absent_id,ab.absent_date,ab.day_type
           FROM emp_absences ab
           WHERE ab.absent_date BETWEEN :startDate AND :endDate) e
ON a.employee_id = e.absent_id AND a.work_day = e.absent_date        
LEFT JOIN (SELECT dates,DATE_FORMAT(dates,'%d') days,CONCAT(DATE_FORMAT(dates,'%W'),',',DATE_FORMAT(dates,'%d %b,%Y')) full_date,is_weekday
FROM (      
					SELECT employee_id,z.shift_id,dates,from_date,to_date,
					      (CASE WHEN (weeks = IF((WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)>5,(WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) - 1),WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)) 
					                  THEN (CASE WHEN (DAYNAME(dates)='sunday') THEN sunday WHEN (DAYNAME(dates)='monday') THEN monday WHEN (DAYNAME(dates)='tuesday') THEN tuesday WHEN (DAYNAME(dates)='wednesday') THEN wednesday WHEN (DAYNAME(thursday)='sunday') THEN thursday WHEN (DAYNAME(dates)='friday') THEN friday WHEN (DAYNAME(dates)='saturday') THEN saturday ELSE '' END)
					            END) is_weekday
					FROM (
					SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<:startDate,:startDate,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,:endDate,to_date) to_date
					FROM shift_roaster r
					INNER JOIN employee_work_details w
					ON r.employee_id = w.employee_id
					LEFT JOIN device_users u
					ON w.employee_id = u.employee_id
					WHERE  r.employee_id=:empId
					AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
					 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate )))z
					LEFT JOIN weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					  JOIN (SELECT selected_date dates FROM 
					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
					              WHERE selected_date BETWEEN :startDate  AND :endDate) t
                        WHERE dates BETWEEN from_date AND to_date)q
					  WHERE is_weekday IS NOT NULL
					)u ON a.work_day = u.dates 
-- WHERE work_day != DATE_FORMAT(NOW(),'%Y-%m-%d')			
GROUP BY employee_id,work_day,regularize_type
)z )q WHERE work_day BETWEEN :startDate AND :endDate)r;");
	
	
	$sum->bindParam('empId',$empId);
	$sum->bindParam('startDate', $startDate);
	$sum->bindParam('endDate', $endDate);
	$sum->execute();
	$cndn='';
	
	$sDate=$startDate;
	while ($sDate <= $endDate) {
		//echo 'okk'."\n";
	$cndn.="MAX(CASE WHEN (weeks = IF((WEEK('".$sDate."') - WEEK(DATE_FORMAT('".$sDate."' , '%Y-%m-01')) + 1)>5,(WEEK('".$sDate."') - WEEK(DATE_FORMAT('".$sDate."' , '%Y-%m-01'))),(WEEK('".$sDate."') - WEEK(DATE_FORMAT('".$sDate."' , '%Y-%m-01')) + 1)))
                                          THEN (CASE WHEN (DAYNAME('".$sDate."')='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),CONCAT('WE','-',sunday),sunday) WHEN (DAYNAME('".$sDate."')='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),CONCAT('WE','-',monday),monday) WHEN (DAYNAME('".$sDate."')='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),CONCAT('WE','-',tuesday),tuesday) WHEN (DAYNAME('".$sDate."')='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),CONCAT('WE','-',wednesday),wednesday) WHEN (DAYNAME('".$sDate."')='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),CONCAT('WE','-',thursday),thursday) WHEN (DAYNAME('".$sDate."')='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),CONCAT('WE','-',friday),friday) WHEN (DAYNAME('".$sDate."')='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),CONCAT('WE','-',saturday),saturday) END)
							WHEN (DATE_FORMAT(check_in,'%Y-%m-%d') ='".$sDate."' AND DATE_FORMAT(check_out,'%Y-%m-%d') ='".$sDate."' AND DATE_FORMAT(absent_date,'%Y-%m-%d') = '".$sDate."') OR DATE_FORMAT(absent_date,'%Y-%m-%d') = '".$sDate."' THEN leave_details
							WHEN DATE_FORMAT(check_in,'%Y-%m-%d') ='".$sDate."' THEN IF(worked_hrs>=min_workhrs,'Z','Y')                             
							WHEN ((start_date BETWEEN '".$sDate."' AND '".$sDate."') OR (end_date BETWEEN '".$sDate."' AND '".$sDate."')) THEN (CASE WHEN (category = 'OPTIONAL' AND holBranch = empBranch) THEN 'RH-FD' WHEN (category = 'HOLIDAY' AND holBranch = 'NA') THEN 'GH-FD' END)
                            WHEN DATE_FORMAT(last_working_date,'%Y-%m-%d') < '".$sDate."' OR employee_doj > '".$sDate."' THEN '-'
                            WHEN device_status = 0 THEN '' ELSE '*' END)'".$sDate."',";
	$sDate = date ("Y-m-d", strtotime("+1 day", strtotime($sDate)));
	}
	
	
	//to get present absent details of a day
	$PQuery=$dbh->prepare("SELECT employee_id EMPID,"
			.substr($cndn,0,-1)."
              FROM (
					SELECT *,SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1) worked_hrs FROM 
					(SELECT employee_id,(CASE WHEN is_day=1 THEN MIN(date_time)
					                          WHEN is_day=0 THEN work_day END) 'check_in',
					                    (CASE WHEN is_day IN(1,0) THEN MAX(date_time)
					                          -- WHEN is_day=0 THEN DATE_SUB(MAX(date_time),INTERVAL 1 DAY) 
                                    ELSE '' END) 'check_out',is_day,
					        employee_doj,last_working_date,absent_date,leave_details,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,
					        employee_name,employee_lastname,start_date,end_date,holBranch,empBranch,category,device_status,min_workhrs
					FROM (
					SELECT z.employee_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
					      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
					       ELSE date_time END) work_day,
					       z.employee_doj,n.last_working_date,a.absent_date,CONCAT(UPPER(a.leave_rule_type),'_',a.day_type) leave_details,
					       weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,employee_name,employee_lastname,start_date,end_date,h.branch_id holBranch,empBranch,h.category,device_status,CONCAT(min_hrs_full_day,':00') min_workhrs
					FROM (
						SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
								IF(from_date<:startDate,:startDate,from_date) from_date,
								IF(to_date='0000-00-00' OR to_date IS NULL ,:endDate,to_date) to_date,IFNULL(status,0) device_status
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  w.enabled = 1 AND w.employee_id=:empId
						AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate )) ORDER BY w.employee_id ) z
					LEFT JOIN company_shifts s
					ON z.shift_id = s.shift_id 
					LEFT JOIN emp_notice_period n 
					ON z.employee_id = n.employee_id AND n.status='A'
					LEFT JOIN device_users du
					ON z.employee_id = du.employee_id
					LEFT JOIN employee_biometric b
					ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
					LEFT JOIN leave_requests l
					ON z.employee_id = l.employee_id AND l.status='A' AND (l.from_date BETWEEN :startDate AND :endDate) AND (l.to_date BETWEEN :startDate AND :endDate) 
					LEFT JOIN emp_absences a
					ON z.employee_id = a.employee_id AND a.absent_date BETWEEN :startDate AND :endDate
					LEFT join weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					LEFT JOIN holidays_event h ON (h.start_date BETWEEN :startDate AND :endDate) 
					WHERE s.is_day IS NOT NULL 
					ORDER BY z.employee_id,date_time )q
					GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d'),absent_date,weeks,start_date ORDER BY employee_id) t1
					GROUP BY t1.employee_id,t1.check_in,t1.absent_date,weeks,start_date) r
					GROUP BY employee_id;");
	
			
	
	$PQuery->bindParam('empId',$empId);
	$PQuery->bindParam('startDate', $startDate);
	$PQuery->bindParam('endDate', $endDate);
	$PQuery->execute();
	$PQuery=$PQuery->fetch(PDO::FETCH_ASSOC);
	
	//print_r($PQuery);
	$html=' <thead id="attendance-Status" style="padding:0px !important"><tr>';
	$val=' <tbody><tr>';
	$status=array();
	
	
	foreach($PQuery as $key=>$PQuer){
		if(date('Y-m-d')==$key)
			$tday='todayAtt';
		else 
			$tday='';
		$d=date('D', strtotime($key)).'&nbsp'.date('d', strtotime($key)).'&nbsp'.date('M,Y', strtotime($key));
		// d M,Y
		if($key=='EMPID')
		$html.='<th class="text-left " style="">'.$key.'</th>';
	elseif($key=='NAME')
		$html.='<th class="text-left" style="">'.$key.'</th>';
	else
		if(date('D', strtotime($key))=='Sat' || date('D', strtotime($key))=='Sun')
		$html.='<th class="text-left display_date '.$tday.'" id=""  data-content='.$d.' data-toggle="popover" data-trigger="focus" data-toggle="tooltip" data-container="body" role="button" ; 
;"><span style="color:#f7990d">'.date('d', strtotime($key)).'</br>'.date('D', strtotime($key)).'</span></th>';
	else
		$html.='<th class="text-left display_date  '.$tday.'" id="" data-content='.$d.' data-toggle="popover" data-trigger="focus" role="button" data-toggle="tooltip" data-container="body" ;
;">'.date('d', strtotime($key)).'</br>'.date('D', strtotime($key)).'</th>';
	$halfDay=array('CL_SH','CL_FH','SL_SH','SL_FH','EL_FH','EL_SH');
	$fullDay=array('CL_FD','SL_FD','EL_FD');
	$hoverData='';
	
	if($PQuer=='WE-FD'){
		$PQuer='WE';
	}elseif($PQuer=='WE-FH'){
		$PQuer='WEEK END';
	}elseif($PQuer=='WE-SH'){
		$PQuer='WE-SH';
	}elseif($PQuer=='*'&&$key>=date('Y-m-d')){
		$PQuer='-';
	}elseif($PQuer=='*'&& $key<=date('Y-m-d')){
		$PQuer='<span style="color:#dc3636;">A</span>';
	}elseif($PQuer=='Y'){
		$PQuer='<span style="color:#d26a5c;text-decoration: underline;" >P</span>';
	}elseif($PQuer=='Z'){
		$PQuer='<span class="text-success">P</span>';//(WORKED FULL WORK HOURS)';
	}elseif(in_array($PQuer,$halfDay)){
		$hoverData=substr($PQuer, -2);
		$PQuer=substr($PQuer, 0, -3);
	}elseif(in_array($PQuer,$fullDay)){
		$hoverData=substr($PQuer, -2);
		$PQuer=substr($PQuer, 0, -3);
	}else{
		if($key!='EMPID'){
	    $hoverData=substr($PQuer, -2);
		$PQuer=substr($PQuer, 0, -3);
		}else{
			$PQuer=$PQuer;
		}
	}
	//echo $hoverData;
	if(isset($hoverData))
		$val.='<td class="text-left dates '.$tday.' hoverD " id="" data-content="'.$hoverData.'" data-toggle="popover" data-trigger="focus" role="button">'.$PQuer.'</td>';
	else 
		$val.='<td class="text-left dates '.$tday.' hoverD " id="" data-content="" data-toggle="popover" data-trigger="focus" role="button">'.$PQuer.'</td>';
	
	}
	
    $html.='</tr></thead>';
    $val.='</tr></tbody>';
    $status=$html.$val;
 
	//to get puches seperately
	$attendance=array("attendances"=>$attendances,"Allpunches"=>array(),"status"=>$status);
	
	

		
	
	if($attendances)
		$attendance['total']=$sum->fetch(PDO::FETCH_ASSOC);
	
	echo json_encode($attendance);

}