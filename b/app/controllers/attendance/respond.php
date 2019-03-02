<?php
function _respond($for=null){
	
	$view = new View();
	$data["view"]=$view;
	$reg = new Regularization();
	$data['for']=$for;
	$dbh = getdbh();
	
	if($for=="p"){
		$reg->retrieve_one('id=?',$_REQUEST['id']);
		$emp=$reg->get("employee_id");
		$data["request"] = $reg;
	}else{
		if(!isset($_REQUEST["token"])&& !isset($_REQUEST["id"]) && !isset($_REQUEST['cid']) && !isset($_REQUEST['rep_id']))
			die("Error Occurred.Link Invalid");
			$token =$_REQUEST["token"];
			$id = base64_decode($_REQUEST["id"]);
			$company_id = base64_decode($_REQUEST["cid"]);
			$reporting_person_id=base64_decode($_REQUEST['rep_id']);
			$_SESSION["company_id"] =$company_id ;
		
			$dbh = getdbh();
			$reg->retrieve_one('id=? AND req_token=? AND req_token_expiry >NOW()',array($id,$token));
			if(!$reg->exists())
				custom_error("Sorry..!!","The Link you're trying to open is not found or Expired");
				$data["request"] = $reg;
				$emp=$reg->get("employee_id");
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
	$today=$reg->get("day");
	$shift->bindParam('empId',$emp);
	$shift->bindParam('tday',$today);
	$shift->execute();
	$shifts=$shift->fetch(PDO::FETCH_ASSOC);
	
	
	$query=$dbh->prepare("
SELECT  start_time,end_time,shift_name,dates,DATE_FORMAT(dates,'%d') date,type,CONCAT(DATE_FORMAT(dates,'%W'),',',date_formatted) date_formatted,DATE_FORMAT(dates,'%b') month,notes,'' is_weekday,check_in,check_out
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
      		WHEN check_out >= IF(is_day=1,CONCAT(DATE_FORMAT(dates,'%Y-%m-%d'),' ',end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(dates,INTERVAL 1 DAY),'%Y-%m-%d'),' ',end_time,':00')) THEN 'Perfect' END) Type,check_in,check_out,
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
   start_time,end_time,shift_hrs,COUNT(work_day) punch_count,shift_id,shift_name,is_day
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
	) r WHERE type IS NOT NULL AND notes IS NOT NULL AND punch_check IS NOT NULL AND type =:reg_type
 	");
	
	
	
	
	
	$reg_type=$reg->get("regularize_type");
	$tday=$yday=$reg->get("day");
	$query->bindParam('empId', $emp);
	$query->bindParam('tday', $tday);
	$query->bindParam('yday', $yday);
	$query->bindParam('reg_type',$reg_type);
	$query->bindParam('shift',$shifts['shift_id']);
	$query->execute();
	$regularization=$query->fetch(PDO::FETCH_ASSOC);
		
	
		
	$data['notes']=$regularization['notes'];
			
				
		$data["request"] = $reg;
		$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
		$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
		$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
		$data['foot'][]='<script src="'.myUrl('js/pages/attendance-respond.js').'"></script>';
		
		
		if($for=="p"){
			echo View::do_fetch(VIEW_PATH.'attendance/respond.php',$data);
		}else{
			$data['body'][]=View::do_fetch(VIEW_PATH.'attendance/respond.php',$data);
			View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
		}
}