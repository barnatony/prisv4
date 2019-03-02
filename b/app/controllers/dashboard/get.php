<?php
function _get($action=null){
	
		$emp=new Employee();
		$empDetails=array(); //result array
		
		$dbh = getdbh();
		$wherewhat=" w.employee_id = ?";
		$bindings=$_SESSION['employee_id'];
		$empID=$_SESSION['employee_id'];
		$payroll_yr=$_SESSION['payrollYear'];
		$financial_yr=$_SESSION['financialYear'];
		$tempArr=array(); //common temp array
		$widget=isset($_REQUEST['widget'])?$_REQUEST['widget']:null; //to get which widget to load 
		
	switch($action){
		
		case "employeeDetails":
			
			
			$what = isset($_REQUEST['payval'])&&$_REQUEST['payval']!=null?$_REQUEST['payval']:'net_salary'; //gross,net
			
		//to get good morning message based on system time it changes accordingly
			$emp_name=$_SESSION['authname'];
			$com_name=$_SESSION['company_name'];
			$text=isset($_REQUEST['wishes'])?$_REQUEST['wishes']:null;
			$data = array("text"=>$emp_name.', '.$text.', '.' Welcome to '.' '.$com_name);
			if($text)
				$empDetails['notes']=$data;
			
		//to get wishes details
			
			$wishes="SELECT * FROM (
						SELECT (CASE WHEN DATE_FORMAT(employee_dob,'%d%m')=DATE_FORMAT(NOW(),'%d%m') THEN CONCAT('Happy Birthday ',employee_name,', ',company_name,' wishes you to have a great year ahead..!! ') END) wishes
						FROM (           
						SELECT employee_name,employee_dob,employee_doj,employee_marriagedate,c.company_name
						FROM employee_work_details w
						INNER JOIN employee_personal_details p
						ON w.employee_id = p.employee_id
						JOIN company_details c
						WHERE w.employee_id=:empId)z
						UNION
						SELECT (CASE WHEN DATE_FORMAT(employee_doj,'%d%m')=DATE_FORMAT(NOW(),'%d%m') THEN CONCAT('Happy ',FLOOR(DATEDIFF(NOW(),employee_doj)/365),IF(FLOOR(DATEDIFF(NOW(),employee_doj)/365)=1,'st',IF(FLOOR(DATEDIFF(NOW(),employee_doj)/365)=2,'nd',IF(FLOOR(DATEDIFF(NOW(),employee_doj)/365)=3,'rd','th'))),' work Anniversary ',employee_name,', Thank you for your valuable contribution to ',company_name,'. Keep up the Good work.') END) wishes
						FROM (           
						SELECT employee_name,employee_dob,employee_doj,employee_marriagedate,c.company_name
						FROM employee_work_details w
						INNER JOIN employee_personal_details p
						ON w.employee_id = p.employee_id
						JOIN company_details c
						WHERE w.employee_id=:empId)z
						UNION
						SELECT (CASE WHEN DATE_FORMAT(employee_marriagedate,'%d%m')=DATE_FORMAT(NOW(),'%d%m') THEN CONCAT('Happy ',FLOOR(DATEDIFF(NOW(),employee_marriagedate)/365),IF(FLOOR(DATEDIFF(NOW(),employee_marriagedate)/365)=1,'st',IF(FLOOR(DATEDIFF(NOW(),employee_marriagedate)/365)=2,'nd',IF(FLOOR(DATEDIFF(NOW(),employee_marriagedate)/365)=3,'rd','th'))),' wedding anniversary or marriage life ',employee_name,', ',company_name,' wishes you to Walk more years together.') END) wishes
						FROM (           
						SELECT employee_name,employee_dob,employee_doj,employee_marriagedate,c.company_name
						FROM employee_work_details w
						INNER JOIN employee_personal_details p
						ON w.employee_id = p.employee_id
						JOIN company_details c
						WHERE w.employee_id=:empId)z ) t
						WHERE wishes IS NOT NULL";
			
			
			$wishes = $dbh->prepare($wishes);
			$wishes->bindParam('empId', $empID);
			$wishes->execute();
			$wishes =$wishes->fetchAll(PDO::FETCH_ASSOC);
			$empDetails['greetings']=$wishes;
				
		//to get employee details
			$emp_detail=$emp->joined_select("w.employee_id,pd.employee_gender,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,DATE_FORMAT(pd.employee_dob,'%d-%m-%Y') employee_dob,IFNULL(s.shift_name,'-') shift_name,
								dept.department_name department,des.designation_name designation,
								IFNULL(IF(pd.employee_mobile='Nil','-',pd.employee_mobile),'-') employee_mobile,
								IFNULL(IF(pd.employee_email='Nil','-',pd.employee_email),'-') employee_email,
								IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),'<small> yrs</small>'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Yr'))) experience,b.branch_name branch,
								REPLACE(pd.employee_image,'..','') employee_image,
								DATE_FORMAT(w.employee_doj,'%d %b, %Y') doj
								,ct.team_id,ct.team_name","employee_work_details w LEFT JOIN company_shifts s ON s.shift_id=w.shift_id
									LEFT JOIN company_departments dept ON w.department_id = dept.department_id
									LEFT JOIN company_designations des ON des.designation_id=w.designation_id
									LEFT JOIN company_branch b ON w.branch_id = b.branch_id
									LEFT JOIN company_team ct ON ct.team_id=w.team_id
									LEFT JOIN employee_personal_details pd
									ON pd.employee_id=w.employee_id",$wherewhat,$bindings)[0];
		
		
		//to get mypay details
			$employee_id=$_SESSION['employee_id'];
			$payroll_month=$_SESSION['current_payroll_month'];
			
			$mypayQuery = "SELECT CONCAT (DATE_FORMAT(month_year,'%b %Y'))  particular,
							FORMAT(($what),2,'en_IN') netSal, CONCAT('paySlips.php?monthYear=',DATE_FORMAT(month_year,'%m%Y')) url
	            			FROM payroll
							WHERE employee_id = :empId ORDER BY month_year DESC LIMIT 0,3";
			$stmt = $dbh->prepare($mypayQuery);
				
			$stmt->bindParam('empId', $empID);
			$stmt->execute();
			$empMypay = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		
		
		//to get total salary of the year
			$salary=$emp->joined_select("FORMAT(FLOOR(SUM($what)),0,'en_IN') total_pay","employee_work_details e
	            					LEFT JOIN payroll p ON e.employee_id=p.employee_id 
									WHERE p.employee_id='{$empID}' AND year='$financial_yr'")[0];
			
			
			
			
			
		
		
		//to get policies details
		$arr1 = array("title"=>'Terms & Conditions of Employee',"link"=>'url');
		$arr2=array("title"=>'Treating employees equally',"link"=>'url');
		$arr3=array("title"=>'Employee orientation programs',"link"=>'url');
		$arr4=array("title"=>'Women Safety & Harassment Actions',"link"=>'url');
		$arr5=array("title"=>'Leave Rules & Policy',"link"=>'url');
		$links=array();
		$links[]=$arr1;
		$links[]=$arr2;
		$links[]=$arr3;
		$links[]=$arr4;
		$links[]=$arr5;
		
		$value =isset($_REQUEST['payval'])&&$_REQUEST['payval']!=null?'Gross':'Net';
		if($what=='gross_salary')
			$value='Gross';
		else 
			$value='Net';
		
			$tempArr['val']=$value;
			$tempArr['fyear']=substr($financial_yr, 0,4)."-".substr($financial_yr, 4,2);
		//to get the details of the specific widget if widget is selected
			if($widget=="mypay"){
				$tempArr['salary']=$salary;
				$tempArr['payslips']=$empMypay;
				$empDetails['mypay']=$tempArr;
			}elseif($widget=="policies"){
				$empDetails['policies']=$links;;
			}elseif(!$widget){
				$empDetails['myself']=$emp_detail;
				$tempArr['salary']=$salary;
				$tempArr['payslips']=$empMypay;
				$empDetails['mypay']=$tempArr;
				$empDetails['policies']=$links;
			}
		
		
		
		
		break;
		
		case "whats-happening":
			
		//if date not given to display the today and yesterday status
		if(isset($_REQUEST['date'])){
			$temp=str_replace(',', '-', $_REQUEST['date']);
			$today=$yesterday=date('Y-m-d',strtotime($temp));
			
		}else{
			$yesterday=date('Y-m-d',strtotime("-1 days"));
			$today=date('Y-m-d');

		}
		//to get shift 
		
		$shift="SELECT IFNULL(r_shift_id,w_shift_id) shift_id
				FROM (
				SELECT DATE_FORMAT(IFNULL(from_date,:tday),'%Y-%m-%d') from_date,DATE_FORMAT(IF(to_date='0000-00-00' OR to_date IS NULL,:tday,to_date),'%Y-%m-%d') to_date,r.shift_id r_shift_id,w.shift_id w_shift_id
									FROM employee_work_details w
									LEFT JOIN shift_roaster r
									ON w.employee_id = r.employee_id
									WHERE w.employee_id=:empId )q
				WHERE :tday BETWEEN from_date AND to_date";
		$shift = $dbh->prepare($shift);
		$shift->bindParam('empId',$empID);
		$shift->bindParam('tday',$today);
		$shift->execute();
		$shifts=$shift->fetch(PDO::FETCH_ASSOC);
		
		//to get today Status
		/*$todayQuery=("SELECT  start_time,end_time,shift_name,dates,DATE_FORMAT(dates,'%d') date,type,CONCAT(DATE_FORMAT(dates,'%W'),',',date_formatted) date_formatted,DATE_FORMAT(dates,'%b') month,notes,'' is_weekday,check_in,check_out
FROM (
SELECT * FROM (
SELECT employee_id,name,dates,DATE_FORMAT(dates,'%d %b,%Y') date_formatted,COUNT(punch) punch_count,'Incorrectpunches' type,'' check_in,'' check_out,CONCAT('Punches:',GROUP_CONCAT(punch)) notes,
	   shift_id,shift_name,start_time,end_time,'' punch_check
FROM (
SELECT employee_id,name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') dates,DATE_FORMAT(work_day,'%H:%i') punch,shift_id,shift_name,start_time,end_time
FROM (
    SELECT w.employee_id,CONCAT(employee_name,' ',employee_lastname) name,b.date_time,
           (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN s.end_time AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
				ELSE date_time END) work_day,
           s.shift_id,s.shift_name,s.start_time,s.end_time  
    FROM employee_work_details w
    LEFT JOIN company_shifts s
    ON s.shift_id = :shift
    LEFT JOIN device_users du
    ON w.employee_id = du.employee_id
    LEFT JOIN employee_biometric b
    ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN :yday AND DATE_ADD(:tday,INTERVAL 1 DAY)
    WHERE w.enabled = 1 AND w.employee_id=:empId ORDER BY date_time) q
WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN :yday AND :tday AND DATE_FORMAT(work_day,'%Y-%m-%d')!=DATE_FORMAT(NOW(),'%Y-%m-%d')
)t GROUP BY employee_id,dates) z WHERE punch_count%2 !=0
UNION
SELECT *,(CASE WHEN Type='Perfect' AND punch_count%2!=0 THEN NULL
          ELSE 1 END) punch_check 
FROM (
SELECT EmpID,Name,dates,DATE_FORMAT(Dates,'%d %b,%Y') Date_Formatted,punch_count,
      (CASE WHEN check_out < CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_in,'%H:%i')> start_time AND DATE_FORMAT(check_out,'%H:%i')< end_time THEN 'Late,EarlyOut'
            WHEN DATE_FORMAT(check_in,'%H:%i')> start_time THEN 'Late'
            WHEN DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_out,'%H:%i')< end_time THEN 'EarlyOut'
      		WHEN check_out >= IF(end_time NOT BETWEEN '00:00' AND '10:00',CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) THEN 'Perfect' END) Type,check_in,check_out,
      (CASE WHEN DATE_FORMAT(check_in,'%H:%i')<= start_time AND DATE_FORMAT(check_out,'%H:%i') >= end_time  THEN CONCAT(SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),':',2),'hrs') 
            WHEN check_out < CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_in,'%H:%i')> start_time AND DATE_FORMAT(check_out,'%H:%i')< end_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs,',SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(end_time,DATE_FORMAT(check_out,'%H:%i')),'.',1),':',2),'hrs')
            WHEN DATE_FORMAT(check_in,'%H:%i')> start_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs') 
			WHEN DATE_FORMAT(check_out,'%H:%i')< end_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(end_time,DATE_FORMAT(check_out,'%H:%i')),'.',1),':',2),'hrs') END) notes,
      shift_id,shift_name,start_time,end_time
FROM (
SELECT employee_id EmpID,Name,DATE_FORMAT(work_day,'%Y-%m-%d') dates,
        (CASE WHEN is_day=1 THEN MIN(date_time)
              WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END)  'check_in',
        (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',
   start_time,end_time,shift_hrs,COUNT(work_day) punch_count,shift_id,shift_name,is_day,late_end
FROM (
     SELECT * FROM (
     SELECT w.employee_id,CONCAT(employee_name,' ',employee_lastname) name,b.date_time,is_day,
       (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
             -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN s.end_time AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
		ELSE date_time END) work_day,
        s.start_time,s.end_time,s.shift_hrs,s.shift_id,s.shift_name,s.late_end
    FROM employee_work_details w
    LEFT JOIN company_shifts s
    ON s.shift_id = :shift
    LEFT JOIN device_users du
    ON w.employee_id = du.employee_id
    LEFT JOIN employee_biometric b
    ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN :yday AND DATE_ADD(:tday,INTERVAL 1 DAY)
    WHERE w.enabled = 1 AND w.employee_id=:empId ORDER BY date_time) q 
    WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN :yday AND :tday 
)q GROUP BY dates) t)w 
	) r WHERE type IS NOT NULL AND notes IS NOT NULL AND punch_check IS NOT NULL -- ORDER BY dates DESC
UNION 
SELECT '' start_time,'' end_time,'' shift_name,dates,DATE_FORMAT(dates,'%d') day,'WE' type,CONCAT(DATE_FORMAT(dates,'%W'),',',DATE_FORMAT(dates,'%d %b,%Y')) full_date,DATE_FORMAT(dates,'%b') month,
'' leave_rule_type,is_weekday,'' check_in,'' check_out FROM (      
					SELECT employee_id,z.shift_id,dates,from_date,to_date,
					      (CASE WHEN (weeks = IF((WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)>5,(WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) - 1),WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)) 
					                                  		THEN (CASE WHEN (DAYNAME(dates)='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),IF((sunday = 'FH' OR sunday = 'SH'),CONCAT('WE','-',sunday),'WE'),sunday) WHEN (DAYNAME(dates)='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),IF((monday = 'FH' OR monday = 'SH'),CONCAT('WE','-',monday),'WE'),monday) WHEN (DAYNAME(dates)='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),IF((Tuesday = 'FH' OR Tuesday = 'SH'),CONCAT('WE','-',Tuesday),'WE'),Tuesday) WHEN (DAYNAME(dates)='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),IF((wednesday = 'FH' OR wednesday = 'SH'),CONCAT('WE','-',wednesday),'WE'),wednesday) WHEN (DAYNAME(dates)='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),IF((thursday = 'FH' OR thursday = 'SH'),CONCAT('WE','-',thursday),'WE'),thursday) WHEN (DAYNAME(dates)='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),IF((friday = 'FH' OR friday = 'SH'),CONCAT('WE','-',friday),'WE'),friday) WHEN (DAYNAME(dates)='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),IF((saturday = 'FH' OR saturday = 'SH'),CONCAT('WE','-',saturday),'WE'),saturday) ELSE '' END)
					            END) is_weekday
					FROM (
					SELECT w.employee_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,
					            IF(r.shift_id IS NULL,:tday,IF(r.to_date IS NULL AND r.from_date <:tday OR r.from_date < :tday,
					            :tday,DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IFNULL(IF(r.to_date>:tday,:tday,r.to_date),:tday) to_date,
					            w.employee_name,w.employee_lastname,w.branch_id empBranch 
					      FROM employee_work_details w 
					      INNER JOIN device_users u
					      ON w.employee_id = u.employee_id
					      LEFT JOIN  shift_roaster r 
					      ON w.employee_id = r.employee_id AND (r.from_date BETWEEN :yday  AND :tday  OR r.from_date <= :tday )
					      AND (r.to_date BETWEEN :yday  AND :tday OR r.to_date IS NULL)
					      WHERE w.enabled = 1 AND w.employee_id=:empId)z
					  LEFT JOIN weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					  JOIN (SELECT selected_date dates FROM 
					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
					              WHERE selected_date BETWEEN :yday  AND :tday) t)q
					WHERE is_weekday IS NOT NULL AND is_weekday !='' 
					UNION
					SELECT DISTINCT '' start_time,'' end_time,'' shift_name,selected_date dates,DATE_FORMAT(selected_date,'%d') day,'H' type,CONCAT(DATE_FORMAT(selected_date,'%W'),',',DATE_FORMAT(selected_date,'%d %b,%Y')) full_date,DATE_FORMAT(selected_date,'%b') month,'',title,'' check_in,'' check_out
					FROM 
					    (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date,start_date,end_date,title,branch_id
					    FROM
					      (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					      (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					      (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					      (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					      (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4
					    JOIN holidays_event h
					    WHERE start_date BETWEEN :yday  AND :tday) v
					JOIN employee_work_details w
					ON w.employee_id = :empId AND w.branch_id = v.branch_id OR v.branch_id='NA'
					WHERE selected_date between start_date AND end_date
					UNION
					SELECT '' start_time,'' end_time,'' shift_name,DATE_FORMAT(absent_date,'%Y-%m-%d') absent_date,DATE_FORMAT(absent_date,'%d') day,'L' type,CONCAT(DATE_FORMAT(absent_date,'%W'),',',DATE_FORMAT(absent_date,'%d %b,%Y')) full_date,DATE_FORMAT(absent_date,'%b') month,
					       UPPER(leave_rule_type) leave_rule_type,CONCAT(leave_rule_type,'_',day_type,'_',reason) leave_details,'' check_in,'' check_out
					FROM emp_absences 
					WHERE employee_id=:empId AND absent_date BETWEEN :yday  AND :tday ORDER BY dates DESC;
		");*/
		
 $todayQuery=("SELECT  start_time,end_time,shift_name,dates,DATE_FORMAT(dates,'%d') date,type,CONCAT(DATE_FORMAT(dates,'%W'),',',date_formatted) date_formatted,DATE_FORMAT(dates,'%b') month,notes,'' is_weekday,check_in,check_out
FROM (
SELECT * FROM (
SELECT employee_id,name,dates,DATE_FORMAT(dates,'%d %b,%Y') date_formatted,COUNT(punch) punch_count,'Incorrectpunches' type,'' check_in,'' check_out,CONCAT('Punches:',GROUP_CONCAT(punch)) notes,
	   shift_id,shift_name,start_time,end_time,'' punch_check
FROM (
SELECT employee_id,name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') dates,DATE_FORMAT(work_day,'%H:%i') punch,shift_id,shift_name,start_time,end_time
FROM (
    SELECT w.employee_id,CONCAT(employee_name,' ',employee_lastname) name,b.date_time,
           (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
              -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN s.end_time AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
				ELSE date_time END) work_day,
           s.shift_id,s.shift_name,s.start_time,s.end_time  
    FROM employee_work_details w
    LEFT JOIN company_shifts s
    ON s.shift_id = :shift
    LEFT JOIN device_users du
    ON w.employee_id = du.employee_id
    LEFT JOIN employee_biometric b
    ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN :yday AND DATE_ADD(:tday,INTERVAL 1 DAY)
    WHERE w.enabled = 1 AND w.employee_id=:empId ORDER BY date_time) q
WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN :yday AND :tday AND DATE_FORMAT(work_day,'%Y-%m-%d')!=DATE_FORMAT(NOW(),'%Y-%m-%d')
)t GROUP BY employee_id,dates) z WHERE punch_count%2 !=0
UNION
SELECT EmpID,Name,dates,Date_Formatted,punch_count,
       (CASE WHEN Type='Late,EarlyOut' AND (day_type='SH' OR is_weekday='SH') THEN 'Late'
             WHEN Type='Late,EarlyOut' AND (day_type='FH' OR is_weekday='FH') THEN 'EarlyOut'
             WHEN (Type='Late' AND (day_type IN('FD','FH') OR is_weekday IN('FD','FH'))) OR (Type='EarlyOut' AND (day_type IN('FD','SH') OR is_weekday IN('FD','SH'))) OR shift_id='SH63590' THEN NULL ELSE Type END) Type,check_in,check_out,
       (CASE WHEN Type='Late,EarlyOut' AND (day_type='SH' OR is_weekday='SH') THEN SUBSTRING_INDEX(notes,',',1)
             WHEN Type='Late,EarlyOut' AND (day_type='FH' OR is_weekday='FH') THEN SUBSTRING_INDEX(notes,',',-1)
             WHEN (Type='Late' AND (day_type IN('FD','FH') OR is_weekday IN('FD','FH'))) OR (Type='EarlyOut' AND (day_type IN('FD','SH') OR is_weekday IN('FD','SH'))) OR shift_id='SH63590' THEN NULL ELSE notes END) notes,
       shift_id,shift_name,start_time,end_time,
      (CASE WHEN Type='Perfect' AND punch_count%2!=0 THEN NULL
                ELSE 1 END) punch_check 
FROM (
SELECT EmpID,Name,dates,DATE_FORMAT(Dates,'%d %b,%Y') Date_Formatted,punch_count,
      (CASE WHEN DATE_FORMAT(NOW(),'%H:%i') BETWEEN '00:00' AND '08:00' AND check_out < CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_in,'%H:%i')> start_time AND DATE_FORMAT(check_out,'%H:%i')< end_time THEN 'Late,EarlyOut'
            WHEN DATE_FORMAT(NOW(),'%H:%i') BETWEEN '09:00' AND '00:00' AND DATE_FORMAT(check_out,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND check_out < IF(end_time NOT BETWEEN '00:00' AND '10:00',CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) AND check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') THEN 'Late,EarlyOut'
            WHEN DATE_FORMAT(check_in,'%H:%i')> start_time THEN 'Late'
            -- WHEN DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_out,'%H:%i')< end_time THEN 'EarlyOut'
            WHEN DATE_FORMAT(check_out,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND check_out < IF(end_time NOT BETWEEN '00:00' AND '10:00',CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) THEN 'EarlyOut'
      		WHEN check_out >= IF(end_time NOT BETWEEN '00:00' AND '10:00',CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) THEN 'Perfect' END) Type,check_in,check_out,
      (CASE WHEN DATE_FORMAT(NOW(),'%H:%i') BETWEEN '00:00' AND '08:00' AND DATE_FORMAT(check_in,'%H:%i')<= start_time AND DATE_FORMAT(check_out,'%H:%i') >= end_time  THEN CONCAT(SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),':',2),'hrs') 
            -- WHEN check_out < CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00') AND DATE_FORMAT(check_in,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(check_in,'%H:%i')> start_time AND DATE_FORMAT(check_out,'%H:%i')< end_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs,',SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(end_time,DATE_FORMAT(check_out,'%H:%i')),'.',1),':',2),'hrs')
            WHEN DATE_FORMAT(NOW(),'%H:%i') BETWEEN '09:00' AND '00:00' AND DATE_FORMAT(check_out,'%Y-%m-%d') != DATE_FORMAT(NOW(),'%Y-%m-%d') AND check_out < IF(end_time NOT BETWEEN '00:00' AND '10:00',CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) AND check_in > CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00') THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(check_in,CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',start_time,':00')),'.',1),':',2),'hrs,',SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(IF(end_time NOT BETWEEN '00:00' AND '10:00',CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')),check_out),'.',1),':',2),'hrs')-- CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs,',SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(end_time,DATE_FORMAT(check_out,'%H:%i')),'.',1),':',2),'hrs')
            WHEN DATE_FORMAT(check_in,'%H:%i')> start_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(check_in,'%H:%i'),start_time),'.',1),':',2),'hrs') 
			WHEN DATE_FORMAT(check_out,'%H:%i')< end_time THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(end_time,DATE_FORMAT(check_out,'%H:%i')),'.',1),':',2),'hrs') END) notes,
      day_type,is_weekday,shift_id,shift_name,start_time,end_time
FROM (
SELECT employee_id EmpID,Name,DATE_FORMAT(work_day,'%Y-%m-%d') dates,
        (CASE WHEN is_day=1 THEN MIN(date_time)
              WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),MIN(date_time)) END)  'check_in',
        (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',
   start_time,end_time,shift_hrs,COUNT(work_day) punch_count,shift_id,shift_name,is_day,late_end
FROM (
     SELECT * FROM (
     SELECT w.employee_id,CONCAT(employee_name,' ',employee_lastname) name,b.date_time,is_day,
       (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
             -- WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN s.end_time AND s.late_end)
                  -- THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
		ELSE date_time END) work_day,
        s.start_time,s.end_time,s.shift_hrs,s.shift_id,s.shift_name,s.late_end
    FROM employee_work_details w
    LEFT JOIN company_shifts s
    ON s.shift_id = :shift
    LEFT JOIN device_users du
    ON w.employee_id = du.employee_id
    LEFT JOIN employee_biometric b
    ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN :yday AND DATE_ADD(:tday,INTERVAL 1 DAY)
    WHERE w.enabled = 1 AND w.employee_id=:empId ORDER BY date_time) q 
    WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN :yday AND :tday 
)q GROUP BY dates) t
LEFT JOIN (SELECT ab.employee_id absent_id,ab.absent_date,ab.day_type
           FROM emp_absences ab
           WHERE ab.absent_date BETWEEN :yday AND :tday) p
ON t.EmpID = p.absent_id AND dates = p.absent_date  
LEFT JOIN (SELECT w_dates,DATE_FORMAT(w_dates,'%d') days,CONCAT(DATE_FORMAT(w_dates,'%W'),',',DATE_FORMAT(w_dates,'%d %b,%Y')) full_date,is_weekday
FROM (      
					SELECT employee_id,z.shift_id,w_dates,from_date,to_date,
					      (CASE WHEN (weeks = IF((WEEK(w_dates) - WEEK(DATE_FORMAT(w_dates , '%Y-%m-01')) + 1)>5,(WEEK(w_dates) - WEEK(DATE_FORMAT(w_dates , '%Y-%m-01')) - 1),WEEK(w_dates) - WEEK(DATE_FORMAT(w_dates , '%Y-%m-01')) + 1)) 
					                  THEN (CASE WHEN (DAYNAME(w_dates)='sunday') THEN sunday WHEN (DAYNAME(w_dates)='monday') THEN monday WHEN (DAYNAME(w_dates)='tuesday') THEN tuesday WHEN (DAYNAME(w_dates)='wednesday') THEN wednesday WHEN (DAYNAME(w_dates)='thursday') THEN thursday WHEN (DAYNAME(w_dates)='friday') THEN friday WHEN (DAYNAME(w_dates)='saturday') THEN saturday ELSE '' END)
					            END) is_weekday
					FROM (
					SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<:yday,:yday,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,:tday,to_date) to_date
					FROM shift_roaster r
					INNER JOIN employee_work_details w
					ON r.employee_id = w.employee_id
					LEFT JOIN device_users u
					ON w.employee_id = u.employee_id
					WHERE  r.employee_id=:empId
					AND ((NOT (from_date > :yday OR to_date < :tday )) OR
					 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :yday )))z
					LEFT JOIN weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					  JOIN (SELECT selected_date w_dates FROM 
					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
					              WHERE selected_date BETWEEN :yday  AND :tday) t
                        WHERE w_dates BETWEEN from_date AND to_date)q
					  WHERE is_weekday IS NOT NULL
					)u ON dates = u.w_dates )w
	) r WHERE type IS NOT NULL AND notes IS NOT NULL AND punch_check IS NOT NULL -- ORDER BY dates DESC
UNION 
SELECT '' start_time,'' end_time,'' shift_name,dates,DATE_FORMAT(dates,'%d') day,'WE' type,CONCAT(DATE_FORMAT(dates,'%W'),',',DATE_FORMAT(dates,'%d %b,%Y')) full_date,DATE_FORMAT(dates,'%b') month,
'' leave_rule_type,is_weekday,'' check_in,'' check_out FROM (      
					SELECT employee_id,z.shift_id,dates,from_date,to_date,
					      (CASE WHEN (weeks = IF((WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)>5,(WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) - 1),WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)) 
					                                  		THEN (CASE WHEN (DAYNAME(dates)='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),IF((sunday = 'FH' OR sunday = 'SH'),CONCAT('WE','-',sunday),'WE'),sunday) WHEN (DAYNAME(dates)='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),IF((monday = 'FH' OR monday = 'SH'),CONCAT('WE','-',monday),'WE'),monday) WHEN (DAYNAME(dates)='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),IF((Tuesday = 'FH' OR Tuesday = 'SH'),CONCAT('WE','-',Tuesday),'WE'),Tuesday) WHEN (DAYNAME(dates)='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),IF((wednesday = 'FH' OR wednesday = 'SH'),CONCAT('WE','-',wednesday),'WE'),wednesday) WHEN (DAYNAME(dates)='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),IF((thursday = 'FH' OR thursday = 'SH'),CONCAT('WE','-',thursday),'WE'),thursday) WHEN (DAYNAME(dates)='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),IF((friday = 'FH' OR friday = 'SH'),CONCAT('WE','-',friday),'WE'),friday) WHEN (DAYNAME(dates)='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),IF((saturday = 'FH' OR saturday = 'SH'),CONCAT('WE','-',saturday),'WE'),saturday) ELSE '' END)
					            END) is_weekday
					FROM (
					SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<:yday,:yday,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,:tday,to_date) to_date
					FROM shift_roaster r
					INNER JOIN employee_work_details w
					ON r.employee_id = w.employee_id
					LEFT JOIN device_users u
					ON w.employee_id = u.employee_id
					WHERE  r.employee_id=:empId
					AND ((NOT (from_date > :yday OR to_date < :tday )) OR
					 ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > :yday )))z
					  LEFT JOIN weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					  JOIN (SELECT selected_date dates FROM 
					              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
					               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
					              WHERE selected_date BETWEEN :yday  AND :tday) t)q
					WHERE is_weekday IS NOT NULL AND is_weekday !='' 
					UNION
					SELECT DISTINCT '' start_time,'' end_time,'' shift_name,selected_date dates,DATE_FORMAT(selected_date,'%d') day,'H' type,CONCAT(DATE_FORMAT(selected_date,'%W'),',',DATE_FORMAT(selected_date,'%d %b,%Y')) full_date,DATE_FORMAT(selected_date,'%b') month,'',title,'' check_in,'' check_out
					FROM 
					    (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date,start_date,end_date,title,branch_id
					    FROM
					      (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
					      (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
					      (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
					      (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
					      (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4
					    JOIN holidays_event h
					    WHERE start_date BETWEEN :yday  AND :tday ) v
					JOIN employee_work_details w
					ON w.employee_id = :empId AND w.branch_id = v.branch_id OR v.branch_id='NA'
					WHERE selected_date between start_date AND end_date
					UNION
					SELECT '' start_time,'' end_time,'' shift_name,DATE_FORMAT(absent_date,'%Y-%m-%d') absent_date,DATE_FORMAT(absent_date,'%d') day,'L' type,CONCAT(DATE_FORMAT(absent_date,'%W'),',',DATE_FORMAT(absent_date,'%d %b,%Y')) full_date,DATE_FORMAT(absent_date,'%b') month,
					       UPPER(leave_rule_type) leave_rule_type,CONCAT(leave_rule_type,'_',day_type,'_',reason) leave_details,'' check_in,'' check_out
					FROM emp_absences 
					WHERE employee_id=:empId AND absent_date BETWEEN :yday  AND :tday ORDER BY dates DESC;");	
		
	
		
		$todayQuery = $dbh->prepare($todayQuery);
		$todayQuery->bindParam('empId',$empID);
		$todayQuery->bindParam('yday',$yesterday);
		$todayQuery->bindParam('tday',$today);
		$todayQuery->bindParam('shift',$shifts['shift_id']);
		$todayQuery->execute();
		
		
		$date="";
		$index =-1;
		$results=array();
		
		
		
		while ($row = $todayQuery->fetch(PDO::FETCH_ASSOC)){
			
			if($date!=$row["dates"])
				$index++;
				
			if($row['type']=="Late"){
				$notes ='<li><i class="si  si-clock  list-timeline-icon bg-info"></i><div class="list-timeline-content">
						<p class="font-s13">You are <span class="text-danger">Late </span>
						by <i class="text-muted">'.$row['notes'].'</i></p></div></li>';
			}elseif($row['type']=='Incorrectpunches'){
			
				$notes='<li class="push-5"><i class="fa fa-exclamation list-timeline-icon bg-primary-dark"></i><div class="list-timeline-content"><p class="font-s13">You have incorrect punches</p></div></li>';
			}elseif($row['type']=='EarlyOut'){
				$notes='<li><i class="si  si-clock   list-timeline-icon bg-city"></i><div class="list-timeline-content"><p class="font-s13">You went <span class="text-danger">Early
							</span>by <i class="text-muted">'.$row['notes'].'</i></p></div></li>';
			}elseif($row['type']=="Perfect"){
			
				$notes='<li><i class="fa fa-check list-timeline-icon bg-success"></i><div class="list-timeline-content"><p class="font-s13">
					You are Perfect, worked <i class="text-muted">'.$row['notes'].'</i></p></div></li>';
			}elseif($row['type']=="Late,EarlyOut"){
				$both= explode(",", $row['notes']);
				$notes='<li><i class="si  si-clock   list-timeline-icon bg-city"></i><div class="list-timeline-content"><p class="font-s13">You went <span class="text-danger">Early
							</span>by <i class="text-muted">'.$both[1].'</i></p></div></li>';
				$notes.='<li><i class="si  si-clock  list-timeline-icon bg-info"></i><div class="list-timeline-content">
						<p class="font-s13">You are <span class="text-danger">Late</span>
						<i class="text-muted">'.$both[0].'</i></p></div></li>';
					
			}elseif($row['type']=="WE"){
				$notes='<li><i class="fa fa-calendar  list-timeline-icon bg-amethyst"></i><div class="list-timeline-content">
							<p class="font-s13">Its a <span class="text-danger">weekend
							</span></p></div></li>';
			}elseif($row['type']=="H"){
				$notes='<li><i class="fa fa-calendar  list-timeline-icon bg-amethyst"></i><div class="list-timeline-content">
							<p class="font-s13">Its a <span class="text-danger">Holiday
							</span></p><span class="label label-warning">'.$row['is_weekday'].'</span></div></li>';
			}elseif($row['type']=="L"){
				$notes='<li><i class="fa  fa-calendar-times-o  list-timeline-icon bg-amethyst"></i><div class="list-timeline-content">
							<p class="font-s13">You are on  <span class="text-danger">Leave
							</span></p><span class="label label-primary">'.$row['notes'].'</span></div></li>';
			}
			
			$row["notes"] = $notes;
			
			$results[$index]["day_num"]=$row["date"];
			$results[$index]["month"]=$row["month"];
			$results[$index]["full_date"]=$row["date_formatted"];
			$results[$index]["start_time"]=$row["start_time"];
			$results[$index]["end_time"]=$row["end_time"];
			$results[$index]["shift_name"]=$row["shift_name"];
			
				
				
			$results[$index]["transactions"][] =$row;
			$date = $row["dates"];
		}
		
		
		
		
		
		
		$empDetails['today']=$results;
		
		break;
	
		case "leaveStatus":
		
		
		//to get leaveBalance
			
			$preview_leave = $balancestmt=$payStmt = $caseStmt = "";
			$lstmt=("SELECT l.leave_rule_id,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
			$lquery = $dbh->prepare($lstmt);
			$lquery->execute();
			$leaveRule =$lquery->fetchAll(PDO::FETCH_ASSOC);
			
			//to get 
			$company_id=$_SESSION['company_id'];
			$stmt=("SELECT leave_based_on,attendance_period_sdate FROM company_details WHERE company_id=:companyId AND info_flag='A'");
			$stmt=$dbh->prepare($stmt);
			$stmt->bindParam('companyId',$company_id);
			$stmt->execute();
			$companyProp = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($companyProp ['leave_based_on'] != 'finYear'){
				if($companyProp['attendance_period_sdate']!=1){
					$sdate = ($_SESSION['payrollYear']-1)."-".(12)."-".$companyProp['attendance_period_sdate'];
					$tdate=$_SESSION['payrollYear']."-".(12)."-".($companyProp['attendance_period_sdate']-1);
				}else{
					$sdate=$_SESSION['payrollYear']."-".(01)."-".($companyProp['attendance_period_sdate']);
					$tdate=$_SESSION['payrollYear']."-12-31";   //.(12)."-".($_SESSION['noOfDaysInMonth']);
				}
			}else{
				if($companyProp['attendance_period_sdate']!=1){
					$sdate = ($_SESSION['payrollYear']-1)."-".(03)."-".$companyProp['attendance_period_sdate'];
					$tdate=$_SESSION['payrollYear']."-".(04)."-".($companyProp['attendance_period_sdate']-1);
				}else{
					$sdate=$_SESSION['payrollYear']."-".(04)."-".($companyProp['attendance_period_sdate']);
					$tdate=$_SESSION['payrollYear']."-04-30";   //.(04)."-".($_SESSION['noOfDaysInMonth']);
				}
			}
			$year = $companyProp ['leave_based_on'] == 'finYear' ? $financial_yr : $payroll_yr;
			foreach ( $leaveRule as $val ){
				
				 $payStmt .="SUM(p.". $val ['leave_rule_id'] ."),";
				 $preview_leave .= "p.". $val ['leave_rule_id'] .",";
				 $caseStmt .= "WHEN '". $val ['leave_rule_id'] ."' THEN availed+". $val ['leave_rule_id'] ." ";
				 $balancestmt .= "WHEN '". $val ['leave_rule_id'] ."' THEN allotted-availed+". $val ['leave_rule_id'] ." ";
			}
			
			$payStmt = rtrim($payStmt,",");
			$preview_leave= rtrim($preview_leave,",");
			/*$query = "SELECT rule_name,UPPER(leave_rule_id) leave_rule_id,(CASE leave_rule_id $caseStmt END) availed,(CASE leave_rule_id $balancestmt  END) balance 
					FROM (
						SELECT CONCAT(w.employee_name,' ',w.employee_lastname) AS employee, lr.rule_name ,l.leave_rule_id,l.allotted+l.opening_bal allotted,l.availed,l.lapsed,$preview_leave
						FROM emp_leave_account l
						INNER JOIN payroll_preview_temp p ON l.employee_id = p.employee_id
						INNER JOIN company_leave_rules lr	ON l.leave_rule_id = lr.leave_rule_id
						INNER JOIN employee_work_details w ON l.employee_id = w.employee_id
						WHERE l.employee_id=:empId AND l.year =:year) z
					;";*/
			
			$query="SELECT rule_name,leave_rule_id,availed,IF((opening_bal<availed),0,(opening_bal-availed) )balance
			FROM (
					SELECT la.employee_id,rule_name,UPPER(la.leave_rule_id) leave_rule_id,(opening_bal+allotted) opening_bal,IFNULL(SUM(day_count),0) availed
					FROM emp_leave_account la
					LEFT JOIN company_leave_rules l
					ON la.leave_rule_id = l.leave_rule_id
					LEFT JOIN emp_absences a
					ON la.leave_rule_id = a.leave_rule_type AND la.employee_id = a.employee_id
					AND la.year=:year AND a.absent_date BETWEEN :sdate AND :tdate
					WHERE la.employee_id=:empId AND l.enabled=1
					GROUP BY la.leave_rule_id )t;";
		
//echo $query;
//echo $empID,$sdate,$tdate,$year;
			$query = $dbh->prepare($query);
			$query->bindParam('empId',$empID);
			$query->bindParam('sdate',$sdate);
			$query->bindParam('tdate',$tdate);
			$query->bindParam('year',$year);
			
			
			$query->execute();
			$leaveBalance =$query->fetchAll(PDO::FETCH_ASSOC);

			
		//to get leaveRequest 
			$leave=new LeaveRequest();
			$leaveStaus=$leave->joined_select("DATE_FORMAT(from_date,'%d %b,%Y') start,IF(SUBSTRING_INDEX(duration,'.',-1)!=0,duration,SUBSTRING_INDEX(duration,'.',1)) duration,DATE_FORMAT(to_date,'%d %b,%Y') end,status,reason,approved_by,updated_on,updated_by,UPPER(leave_type) leave_type,UPPER(from_half) from_half,UPPER(to_half) to_half,(CASE 
WHEN from_half=to_half OR (from_half = 'SH' AND to_half = 'FH') THEN 1
ELSE 0
END) print_half","leave_requests WHERE employee_id='{$empID}'
											ORDER BY updated_on DESC LIMIT 0,3");
			
			
		
			if($widget=="status"){
				$empDetails['leaveStatus']=$leaveStaus;
			}elseif($widget=="balance"){
				$empDetails["leaveBalance"]=$leaveBalance;
			}elseif(!$widget){
				$empDetails["leaveBalance"]=$leaveBalance;
				$empDetails['leaveStatus']=$leaveStaus;
			}
			
		break;
		
		case "infos":
			//to get birthday				
				$birthdays=$emp->joined_select("p.employee_id ,p.employee_email,(CASE WHEN DATE_FORMAT(p.employee_dob,'%d') = DATE_FORMAT(NOW(),'%d')THEN 'Today'
				      WHEN DATE_FORMAT(p.employee_dob,'%d') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d') THEN 'Tomorrow'
				      ELSE DATE_FORMAT(p.employee_dob,'%b %d')
				      END
				 ) AS employee_dob,DATE_FORMAT(p.employee_dob,'%d') as day
				,REPLACE(p.employee_image ,'..','') employee_image,w.employee_name ,p.employee_gender, EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM  p.employee_dob) age","employee_personal_details p
				INNER JOIN  employee_work_details w ON p.employee_id=w.employee_id  AND w.enabled=1
				WHERE p.employee_dob + INTERVAL EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM p.employee_dob) YEAR
				BETWEEN CURRENT_DATE() AND CURRENT_DATE() + INTERVAL 10 DAY ORDER BY day ASC");
				$empDetails["birthdays"]=$birthdays;
				
				
				
			//to get anniversary
				$anniversary=$emp->joined_select("p.employee_id ,p.employee_email,(CASE WHEN DATE_FORMAT(w.employee_doj,'%d') = DATE_FORMAT(NOW(),'%d')THEN 'Today'
					      WHEN DATE_FORMAT(w.employee_doj,'%d') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d') THEN 'Tomorrow'
					      ELSE DATE_FORMAT(w.employee_doj,'%b %d')
					      END
					 ) AS doj,DATE_FORMAT(p.employee_dob,'%d') as day
					,REPLACE(p.employee_image ,'..','') employee_image ,w.employee_name ,p.employee_gender, EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM  w.employee_doj) years","employee_personal_details p
					INNER JOIN  employee_work_details w ON p.employee_id=w.employee_id  AND w.enabled=1
					WHERE  w.employee_doj + INTERVAL EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM w.employee_doj) YEAR
					BETWEEN CURRENT_DATE() AND CURRENT_DATE() + INTERVAL 10 DAY ORDER BY DATE_FORMAT(employee_doj,'%m%d') ASC");
				$empDetails["anniversary"]=$anniversary;
				
				
			//to get holidays
				$generalholidays=$emp->joined_select("DATE_FORMAT(h.start_date,'%W %d %b,%Y') sdate,h.holiday_id,h.category, h.title ,(CASE WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(NOW(),'%d%m')THEN 'Today'
					WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d%m') THEN 'Tomorrow'
					ELSE DATE_FORMAT(h.start_date,'%b %d')
					END
					) AS event_date","holidays_event h WHERE 
					start_date >= CURRENT_DATE() ORDER BY start_date ASC");
				
				
				$empDetails["holidays"]=$generalholidays;
				
				break;
		
		case "tax"	:
			
				
			$taxYear=isset($_REQUEST['tax-year'])?$_REQUEST['tax-year']:null;
			/*$taxQuery="SELECT ded,FORMAT(value1,2,'en_IN') value1,FORMAT(value2,2,'en_IN') value2,(CASE WHEN ded='totalVsTaxable' THEN ROUND(100-(value2/value1)*100,2)
               WHEN ded='payableVsPaid' THEN FORMAT(value2+value1,2,'en_IN') ELSE ROUND((value2/value1)*100,2) END) percentage,
		        (CASE WHEN ded='ded_80c' THEN '80c granted' WHEN ded='ded_80d' THEN '80d granted'
		               WHEN ded='totalVsTaxable' THEN 'total income' WHEN ded='payableVsPaid' THEN 'tax_paid' END) value1_names,
		        (CASE WHEN ded='ded_80c' THEN '80c availed' WHEN ded='ded_80d' THEN '80d availed'
		               WHEN ded='totalVsTaxable' THEN 'taxable income' WHEN ded='payableVsPaid' THEN 'tax_payable' END) value2_names
		FROM (
		SELECT ded,
		            CASE c.ded
		               WHEN 'ded_80c' THEN (SELECT value FROM it_properties WHERE fin_year=:financial_yr AND it_calculation_variable='total_80c_exemptions_limit')
		               WHEN 'ded_80d' THEN (SELECT value FROM it_properties WHERE fin_year=:financial_yr AND it_calculation_variable='total_80d_exemptions_limit')
		               WHEN 'totalVsTaxable' THEN t.total_inc
		               WHEN 'payableVsPaid' THEN t.tax_payable
		            END value1,
		            CASE c.ded
		               WHEN 'ded_80c' THEN ded_80c
		               WHEN 'ded_80d' THEN ded_80d
		               WHEN 'totalVsTaxable' THEN t.taxable_inc
		               WHEN 'payableVsPaid' THEN (SELECT IFNULL(SUM(c_it),0)  FROM payroll p WHERE p.employee_id=:empId AND p.year = :financial_yr)
		            END value2
		       FROM employee_income_tax t
		       CROSS JOIN
		     (
		       SELECT 'ded_80c' ded UNION ALL
		       SELECT 'ded_80d' UNION ALL
		       SELECT 'totalVsTaxable' UNION ALL
		       SELECT 'payableVsPaid'
		     ) c
		    WHERE t.employee_id = :empId AND t.year = :financial_yr) t";*/
			
			$taxQuery="SELECT ded,FORMAT(value1,0,'en_IN') value1,FORMAT(value2,0,'en_IN') value2,
        IFNULL((CASE WHEN ded='Tax Savings' THEN ROUND(100-(value2/value1)*100,2) 
                     WHEN ded='Tax Payable vs Tax Paid' THEN FORMAT(value2+value1,0,'en_IN') ELSE ROUND((value2/value1)*100,2) END),0.00) value3,
        (CASE WHEN ded='Tax Savings' THEN 'Total Income' WHEN ded='Tax Payable vs Tax Paid' THEN 'Tax Payable' ELSE 'Availed' END) value1_legend,
        (CASE WHEN ded='Tax Savings' THEN 'Taxable Tncome' WHEN ded='Tax Payable vs Tax Paid' THEN 'Tax Paid' ELSE 'Granted' END) value2_legend,
        (CASE WHEN ded='Tax Payable vs Tax Paid' THEN 'Total Tax' ELSE 'Percentage' END) value3_legend
FROM (
SELECT ded,
			         CASE c.ded 
			            WHEN '80C' THEN (SELECT value FROM it_properties WHERE fin_year=:financial_yr AND it_calculation_variable='total_80c_exemptions_limit')
			            WHEN '80D' THEN (SELECT value FROM it_properties WHERE fin_year=:financial_yr AND it_calculation_variable='total_80d_exemptions_limit')
			            WHEN 'Tax Savings' THEN t.total_inc
			            WHEN 'Tax Payable vs Tax Paid' THEN t.tax_payable
			         END value1,
			         CASE c.ded 
			            WHEN '80C' THEN ded_80c
			            WHEN '80D' THEN ded_80d
			            WHEN 'Tax Savings' THEN t.taxable_inc
			            WHEN 'Tax Payable vs Tax Paid' THEN (SELECT IFNULL(SUM(c_it),0)  FROM payroll p WHERE p.employee_id=:empId AND p.year = :financial_yr)
			         END value2
			    FROM employee_income_tax t 
			    CROSS JOIN
			  (
			    SELECT '80C' ded UNION ALL
			    SELECT '80D' UNION ALL
			    SELECT 'Tax Savings' UNION ALL
			    SELECT 'Tax Payable vs Tax Paid'
			  ) c
				WHERE t.employee_id = :empId AND t.year = :financial_yr) t";
			
			
			
			$taxQuery = $dbh->prepare($taxQuery);
			$taxQuery->bindParam('empId',$empID);
			$taxQuery->bindParam('financial_yr',$taxYear);
			$taxQuery->execute();
			$tax =$taxQuery->fetchAll(PDO::FETCH_ASSOC);
			
			
			$empDetails['tax']=$tax;
			
			
		break;	
		
	}
	

echo json_encode($empDetails);		

}