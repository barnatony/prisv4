<?php
function _check(){
	$result = array(true,"rowCount"=>0,"info"=>'',"data"=>'');
	
	$leave=new LeaveRequest();
	
	
	$_REQUEST["from"]=date("Y-m-d ", strtotime(str_replace("/","-",$_REQUEST["from"])));
	$_REQUEST["to"]=date("Y-m-d ", strtotime(str_replace("/","-",$_REQUEST["to"])));
	$leave_rule=$_REQUEST['leave_rule'];
		
	$duration=$_REQUEST["duration"];
	
	if(isset($_SESSION["authprivilage"])=="hr")
		$employee_id = $_REQUEST["employee_id"];
	else 
		$employee_id = $_SESSION["employee_id"];

	//employee Gender //leave requests pending & Approved(RQ,A) for this month
	
		$dbh = getdbh();
		
		//to get list of working days, weekoff and holidays in given period
	$query=$dbh->prepare("SELECT * FROM (    
SELECT employee_id,z.shift_id,dates,FROM_date,to_date,
      (CASE WHEN (weeks = IF((WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1)>5,(WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) - 1),WEEK(dates) - WEEK(DATE_FORMAT(dates , '%Y-%m-01')) + 1))
                                    THEN (CASE WHEN (DAYNAME(dates)='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),IF((sunday = 'FH' OR sunday = 'SH'),CONCAT('WE','-',sunday),'WE'),sunday) WHEN (DAYNAME(dates)='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),IF((monday = 'FH' OR monday = 'SH'),CONCAT('WE','-',monday),'WE'),monday) WHEN (DAYNAME(dates)='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),IF((Tuesday = 'FH' OR Tuesday = 'SH'),CONCAT('WE','-',Tuesday),'WE'),Tuesday) WHEN (DAYNAME(dates)='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),IF((wednesday = 'FH' OR wednesday = 'SH'),CONCAT('WE','-',wednesday),'WE'),wednesday) WHEN (DAYNAME(dates)='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),IF((thursday = 'FH' OR thursday = 'SH'),CONCAT('WE','-',thursday),'WE'),thursday) WHEN (DAYNAME(dates)='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),IF((friday = 'FH' OR friday = 'SH'),CONCAT('WE','-',friday),'WE'),friday) WHEN (DAYNAME(dates)='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),IF((saturday = 'FH' OR saturday = 'SH'),CONCAT('WE','-',saturday),'WE'),saturday) ELSE '' END)
            END) is_weekday
FROM (
SELECT w.employee_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,
            IF(r.shift_id IS NULL,:startDate,IF(r.to_date IS NULL AND r.FROM_date < :startDate OR r.FROM_date <  :startDate,
             :startDate,DATE_FORMAT(r.FROM_date,'%Y-%m-%d'))) FROM_date,IFNULL(IF(r.to_date> :endDate, :endDate,r.to_date),:endDate) to_date,
			
            w.employee_name,w.employee_lastname,w.branch_id empBranch
      FROM employee_work_details w
      LEFT JOIN device_users u
      ON w.employee_id = u.employee_id
      LEFT JOIN  shift_roaster r
      ON w.employee_id = r.employee_id AND (r.FROM_date BETWEEN :startDate AND :endDate  OR r.FROM_date <= :startDate )
      AND (r.to_date BETWEEN :startDate AND :endDate OR r.to_date IS NULL)
      WHERE w.enabled = 1 AND w.employee_id=:empId)z
  LEFT JOIN weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
  JOIN (SELECT selected_date dates FROM
              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date FROM
               (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
               (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
               (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
               (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
               (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4) v
              WHERE selected_date between :startDate AND :endDate) t)q
WHERE is_weekday IS NOT NULL AND is_weekday!='' 
UNION
SELECT DISTINCT :empId employee_id,'' shift_id,selected_date dates,start_date FROM_date,end_date to_date,title
FROM
    (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) selected_date,start_date,end_date,title,branch_id
    FROM
      (SELECT 0 t0 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t0,
      (SELECT 0 t1 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t1,
      (SELECT 0 t2 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t2,
      (SELECT 0 t3 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t3,
      (SELECT 0 t4 union SELECT 1 union SELECT 2 union SELECT 3 union SELECT 4 union SELECT 5 union SELECT 6 union SELECT 7 union SELECT 8 union SELECT 9) t4
    JOIN holidays_event h
    WHERE start_date BETWEEN :startDate AND :endDate) v
JOIN employee_work_details w
ON w.employee_id = :empId AND w.branch_id = v.branch_id OR v.branch_id='NA'
WHERE selected_date between start_date AND end_date; ");	
		
	$query->bindParam('startDate', $_REQUEST["from"]);
	$query->bindParam('endDate', $_REQUEST["to"]);
	$query->bindParam('empId', $employee_id);
	
	$query->execute();
	$result['rowCount']=$query->rowCount();
	$result['data'] = $query->fetchAll();
	
	
	if(!$query){ //if query not executed throws an error
		$result["info"]=$dbh->errorInfo()[2];
	}
	
	
	
	
	
	echo json_encode($result);																
}