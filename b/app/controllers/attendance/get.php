<?php
function _get(){ //to get attendance regularization
	
	
	$dbh = getdbh();
	
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
		$endDate = date('Y-m-d',strtotime($startDate));
	}
	
	
	
	$query=$dbh->prepare("SELECT * FROM (SELECT * FROM (
SELECT employee_id EMPID,Name,dates,DATE_FORMAT(dates,'%d %b,%Y') Date_Formatted,COUNT(punch) punch_count,'Incorrectpunches' Type,'' check_in,'' check_out,is_day,CONCAT('Punches:',GROUP_CONCAT(punch ORDER BY work_day)) notes FROM (
SELECT employee_id,Name,DATE_FORMAT(work_day,'%Y-%m-%d') dates,DATE_FORMAT(work_day,'%H:%i') punch,work_day,is_day
FROM (
     SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,is_day,
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
	ON r.employee_id =:employee_id
	AND (DATE_FORMAT(to_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate OR DATE_FORMAT(from_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate
	OR (to_date ='0000-00-00' OR to_date IS NULL AND from_date BETWEEN :startDate AND :endDate) 
	OR (to_date ='0000-00-00' OR to_date IS NULL AND DATE_FORMAT(from_date,'%Y-%m-%d') < :startDate))
	WHERE w.enabled = 1 AND w.employee_id=:employee_id  ) z
     LEFT JOIN company_shifts s
     ON z.shift_id = s.shift_id
     LEFT JOIN device_users du
     ON z.employee_id = du.employee_id
     LEFT JOIN employee_biometric b
     ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
     WHERE s.is_day IS NOT NULL
     ORDER BY z.employee_id,work_day) q
GROUP BY employee_id,work_day
)t GROUP BY employee_id,dates)t
WHERE punch_count%2 !=0 AND dates!=DATE_FORMAT(NOW(),'%Y-%m-%d')
UNION
SELECT EmpID,Name,Dates,Date_Formatted,punch_count,
  (CASE WHEN type='Late,EarlyOut' AND SUBSTRING_INDEX(notes,',',-1)='00:00hrs early' THEN 'Late' ELSE type END) type,check_in,check_out,is_day,
  (CASE WHEN type='Late,EarlyOut' AND SUBSTRING_INDEX(notes,',',-1)='00:00hrs early' THEN SUBSTRING_INDEX(notes,',',1) ELSE notes END) notes
FROM (
SELECT EmpID,Name,Dates,DATE_FORMAT(Dates,'%d %b,%Y') Date_Formatted,'' punch_count,-- DATE_FORMAT(SEC_TO_TIME((ROUND(TIME_TO_SEC(check_out)/60)) * 60),'%H:%i'),
      -- IF(DATE_FORMAT(check_in,'%H:%i')> start_time AND DATE_FORMAT(check_in,'%Y-%m-%d')!=DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(SEC_TO_TIME((ROUND(TIME_TO_SEC(check_out)/60)) * 60),'%H:%i') < end_time 
         --  AND check_out< IF(is_day=1,CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')),'Late,Early Out',IF(DATE_FORMAT(check_in,'%H:%i')> start_time,'Late',IF(DATE_FORMAT(check_out,'%H:%i')< end_time,'EarlyOut',''))) Type,
         (CASE WHEN check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') AND (is_day=0 OR is_day=1) AND end_time NOT BETWEEN '00:00' AND '06:00' AND check_out<CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d')!=DATE_FORMAT(NOW(),'%Y-%m-%d') THEN 'Late,EarlyOut'
               WHEN check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') AND (is_day=0 OR is_day=1) AND end_time BETWEEN '00:00' AND '06:00' AND check_out<CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d')!=DATE_FORMAT(NOW(),'%Y-%m-%d') THEN 'Late,EarlyOut'
               WHEN check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') THEN 'Late'
               WHEN check_out< IF(is_day=1,CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) THEN 'EarlyOut' END) type,
      check_in,check_out,is_day,
      (CASE WHEN check_out < CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_in,'%H:%i')> start_time AND end_time NOT BETWEEN '00:00' AND '06:00' 
				         THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs late,',SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),check_out))),':',2),'hrs early')
            WHEN is_day=0 AND DATE_FORMAT(check_in,'%H:%i')> start_time  AND end_time BETWEEN '00:00' AND '06:00' AND check_out < CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00') 
                 THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs late,',SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00'),check_out))),':',2),'hrs early')
            WHEN DATE_FORMAT(check_in,'%H:%i')> start_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs late')
            WHEN DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_out,'%H:%i')< end_time THEN IF(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(end_time,DATE_FORMAT(check_out,'%H:%i')),'.',1),':',2)>shift_hrs,CONCAT(shift_hrs,'hrs early'),CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(end_time,DATE_FORMAT(check_out,'%H:%i')),'.',1),':',2),'hrs early')) END) notes
FROM (
SELECT employee_id EmpID,Name,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d') Dates,
        (CASE WHEN is_day=1 THEN MIN(date_time)
              WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END)  'check_in',
        (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',start_time,end_time,shift_hrs,is_day
FROM (
     SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,z.shift_id,b.date_time,is_day,
           (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY)
            ELSE date_time END) work_day,s.start_time,s.end_time,s.late_end,s.shift_hrs
     FROM (
     SELECT w.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,
             IF(r.shift_id IS NULL,:startDate,IF(r.to_date IS NULL AND r.from_date <:startDate OR r.from_date < :startDate,
             :startDate,DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IF(r.to_date!='0000-00-00',r.to_date,:endDate) to_date,
                 w.employee_doj,w.employee_name,w.employee_lastname,w.team_id
	FROM employee_work_details w
	LEFT JOIN device_users u
	ON w.employee_id = u.employee_id
	LEFT JOIN  shift_roaster r
	ON r.employee_id =:employee_id
	AND (DATE_FORMAT(to_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate OR DATE_FORMAT(from_date,'%Y-%m-%d') BETWEEN :startDate AND :endDate
	OR (to_date ='0000-00-00' OR to_date IS NULL AND from_date BETWEEN :startDate AND :endDate) 
	OR (to_date ='0000-00-00' OR to_date IS NULL AND DATE_FORMAT(from_date,'%Y-%m-%d') < :startDate))
	WHERE w.enabled = 1 AND w.employee_id=:employee_id ) z
     LEFT JOIN company_shifts s
     ON z.shift_id = s.shift_id
     INNER JOIN device_users du
     ON z.employee_id = du.employee_id
     LEFT JOIN employee_biometric b
     ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
     WHERE s.is_day IS NOT NULL 
     ORDER BY z.employee_id,date_time) q
GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')
)q ) t WHERE notes!='' ORDER BY dates DESC,EmpID ) a
LEFT JOIN attendance_regularization r
ON a.EMPID = r.employee_id AND a.dates = r.day AND a.type = r.regularize_type
WHERE r.employee_id IS NULL  AND dates BETWEEN :startDate AND :endDate;
	");*/
	
$query=$dbh->prepare("SELECT * FROM (SELECT * FROM (
SELECT employee_id EMPID,Name,dates,DATE_FORMAT(dates,'%d %b,%Y') Date_Formatted,COUNT(punch) punch_count,'Incorrectpunches' Type,'' check_in,'' check_out,is_day,CONCAT('Punches:',GROUP_CONCAT(punch ORDER BY work_day)) notes FROM (
SELECT employee_id,Name,DATE_FORMAT(work_day,'%Y-%m-%d') dates,DATE_FORMAT(work_day,'%H:%i') punch,work_day,is_day
FROM (
     SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,is_day,
           (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
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
	WHERE  r.employee_id=:employee_id
	AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
	 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate )) ) z
     LEFT JOIN company_shifts s
     ON z.shift_id = s.shift_id
     LEFT JOIN device_users du
     ON z.employee_id = du.employee_id
     LEFT JOIN employee_biometric b
     ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
     WHERE s.is_day IS NOT NULL
     ORDER BY z.employee_id,work_day) q
GROUP BY employee_id,work_day
)t GROUP BY employee_id,dates)t
WHERE punch_count%2 !=0 AND dates!=DATE_FORMAT(NOW(),'%Y-%m-%d')
UNION
SELECT EmpID,Name,Dates,Date_Formatted,punch_count,
  (CASE WHEN type='Late,EarlyOut' AND (day_type='SH' OR is_weekday='SH') OR (type='Late,EarlyOut' AND SUBSTRING_INDEX(notes,',',-1)='00:00hrs early') THEN 'Late'
        WHEN type='Late,EarlyOut' AND (day_type='FH' OR is_weekday='FH') OR (type='Late,EarlyOut' AND SUBSTRING_INDEX(notes,',',1)='00:00hrs late') THEN 'EarlyOut' 
        WHEN ((day_type='FH' OR is_weekday='FH') AND type='Late') OR ((day_type='SH' OR is_weekday='SH') AND type='EarlyOut') THEN '' 
        WHEN is_weekday='FD' OR day_type='FD' OR shift_id='SH63590' THEN '' ELSE type END) type,check_in,check_out,is_day,
  (CASE WHEN type='Late,EarlyOut' AND (day_type='SH' OR is_weekday='SH') OR (type='Late,EarlyOut' AND SUBSTRING_INDEX(notes,',',-1)='00:00hrs early') THEN SUBSTRING_INDEX(notes,',',1) 
        WHEN type='Late,EarlyOut' AND (day_type='FH' OR is_weekday='FH') OR (type='Late,EarlyOut' AND SUBSTRING_INDEX(notes,',',1)='00:00hrs late') THEN SUBSTRING_INDEX(notes,',',-1) 
        WHEN ((day_type='FH' OR is_weekday='FH') AND type='Late') OR ((day_type='SH' OR is_weekday='SH') AND type='EarlyOut') THEN '' 
        WHEN is_weekday='FD' OR day_type='FD' OR shift_id='SH63590' THEN '' ELSE notes END) notes
FROM (
SELECT EmpID,Name,Dates,DATE_FORMAT(Dates,'%d %b,%Y') Date_Formatted,'' punch_count,day_type,is_weekday,-- DATE_FORMAT(SEC_TO_TIME((ROUND(TIME_TO_SEC(check_out)/60)) * 60),'%H:%i'),
      (CASE WHEN DATE_FORMAT(check_in,'%H:%i')> start_time AND /*check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') AND*/  (is_day=0 OR is_day=1) AND end_time NOT BETWEEN '00:00' AND '10:00' AND check_out<CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d')!=DATE_FORMAT(NOW(),'%Y-%m-%d') THEN 'Late,EarlyOut'
               WHEN DATE_FORMAT(check_in,'%H:%i')> start_time AND /*check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') AND*/ (is_day=0 OR is_day=1) AND end_time BETWEEN '00:00' AND '10:00' AND check_out<CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d')!=DATE_FORMAT(NOW(),'%Y-%m-%d') THEN 'Late,EarlyOut'
               WHEN DATE_FORMAT(check_in,'%H:%i')> start_time /* AND check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') AND*/ THEN 'Late'
               WHEN check_out< IF(is_day=1,CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) THEN 'EarlyOut' END) type,
      check_in,check_out,is_day,shift_id,
      (CASE WHEN check_out < CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_in,'%H:%i')> start_time AND end_time NOT BETWEEN '00:00' AND '10:00' 
				         THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs late,',SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),check_out))),':',2),'hrs early')
            WHEN is_day=0 AND DATE_FORMAT(check_in,'%H:%i')> start_time  AND end_time BETWEEN '00:00' AND '10:00' AND check_out < CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00') 
                 THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs late,',SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00'),check_out))),':',2),'hrs early')
            WHEN DATE_FORMAT(check_in,'%H:%i')> start_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs late')
            WHEN DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND end_time NOT BETWEEN '00:00' AND '10:00' AND check_out < CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00')  THEN CONCAT(SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),check_out))),':',2),'hrs early')
            WHEN DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND end_time BETWEEN '00:00' AND '10:00' AND check_out < CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00') THEN CONCAT(SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00'),check_out))),':',2),'hrs early')
            END) notes
FROM (
SELECT employee_id EmpID,Name,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d') Dates,
        (CASE WHEN is_day=1 THEN MIN(date_time)
              WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END)  'check_in',
        (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',start_time,end_time,shift_hrs,is_day-- ,day_type
FROM (
     SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,z.shift_id,b.date_time,is_day,
           (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY)
            ELSE date_time END) work_day,s.start_time,s.end_time,s.late_end,s.shift_hrs
     FROM (
     SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,
      IF(from_date<:startDate,:startDate,from_date) from_date,
      IF(to_date='0000-00-00' OR to_date IS NULL ,:endDate,to_date) to_date
	FROM shift_roaster r
	INNER JOIN employee_work_details w
	ON r.employee_id = w.employee_id
	LEFT JOIN device_users u
	ON w.employee_id = u.employee_id
	WHERE  r.employee_id=:employee_id
	AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
	 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate )) ) z
     LEFT JOIN company_shifts s
     ON z.shift_id = s.shift_id
     INNER JOIN device_users du
     ON z.employee_id = du.employee_id
     LEFT JOIN employee_biometric b
     ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
     WHERE s.is_day IS NOT NULL 
     ORDER BY z.employee_id,date_time) q
GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')
)a
LEFT JOIN (SELECT ab.employee_id absent_id,ab.absent_date,ab.day_type
           FROM emp_absences ab
           WHERE ab.absent_date BETWEEN :startDate AND :endDate) p
ON a.EmpID = p.absent_id AND Dates = p.absent_date 
LEFT JOIN (SELECT w_dates,DATE_FORMAT(w_dates,'%d') days,CONCAT(DATE_FORMAT(w_dates,'%W'),',',DATE_FORMAT(w_dates,'%d %b,%Y')) full_date,is_weekday
FROM (      
					SELECT employee_id,z.shift_id,w_dates,from_date,to_date,
					      (CASE WHEN (weeks = IF((WEEK(w_dates) - WEEK(DATE_FORMAT(w_dates , '%Y-%m-01')) + 1)>5,(WEEK(w_dates) - WEEK(DATE_FORMAT(w_dates , '%Y-%m-01')) - 1),WEEK(w_dates) - WEEK(DATE_FORMAT(w_dates , '%Y-%m-01')) + 1)) 
					                  THEN (CASE WHEN (DAYNAME(w_dates)='sunday') THEN sunday WHEN (DAYNAME(w_dates)='monday') THEN monday WHEN (DAYNAME(w_dates)='tuesday') THEN tuesday WHEN (DAYNAME(w_dates)='wednesday') THEN wednesday WHEN (DAYNAME(w_dates)='thursday') THEN thursday WHEN (DAYNAME(w_dates)='friday') THEN friday WHEN (DAYNAME(w_dates)='saturday') THEN saturday ELSE '' END)
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
					WHERE  r.employee_id=:employee_id
					AND ((NOT (from_date > :endDate OR to_date < :startDate )) OR
					 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :endDate )))z
					LEFT JOIN weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					  JOIN (SELECT selected_date w_dates FROM 
					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
					              WHERE selected_date BETWEEN :startDate  AND :endDate) t
                        WHERE w_dates BETWEEN from_date AND to_date)q
					  WHERE is_weekday IS NOT NULL
					)u ON dates = u.w_dates)t 
WHERE notes!='' ORDER BY dates DESC,EmpID) e 
LEFT JOIN attendance_regularization r
ON e.EMPID = r.employee_id AND e.dates = r.day AND e.type = r.regularize_type
WHERE r.employee_id IS NULL AND notes!='' AND dates BETWEEN :startDate AND :endDate;
		");

	
	$endDate=date ( 'Y-m-d');
	$query->bindParam('employee_id', $_SESSION['employee_id']);
	$query->bindParam('startDate', $startDate);
	$query->bindParam('endDate', $endDate);
	$query->execute();
	
	
	//check the current day,if it is wednesday,thurs,fri,sat provide the start of the week
	//if it is sun,mon,tues - provide the start of the previous week
	$currentWeekDays=array("Wed","Thu","Fri","Sat");
	
	
	
	$todayDay=date('D');
	$today= strtotime(date('Y-m-d'));
	$def_time="10:00:00 AM";
	$curr_time=date('h:i:s A');
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$restricted_date=date('Y-m-d',strtotime("-31 day", $today)); //displays last week monday as Restrict date

	
	
	
	$regularization=array();
	while($row=$query->fetch(PDO::FETCH_ASSOC)){
			
		
		if($row['dates']>=$restricted_date)
			$row['res'] ="Applicable";
		else 
			$row['res'] ="Expired";
		
		//seperate the notes and type
		$type=explode(",",$row['Type']);
		if(isset($type[1])){
			$notes=explode(",",$row['notes']);
			for($i=0;$i<count($type);$i++){
				$row['Type']=$type[$i];
				$row['notes']=$notes[$i];
				$regularization[]=$row;
			}	
		}else
			$regularization[]=$row;
	}
	
	
	//if not executed throws an error
	if(!$query)
		print_r($dbh->errorInfo());
	

	$regularization=array(
			"regularization"=>$regularization
	);
	
	echo json_encode($regularization);
}