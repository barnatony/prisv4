<?php
function _raise(){
	
	$data['pagename']="LeaveRequests View";
	$leaveR=new LeaveRequest();
	$data['view']=new View();
	$employee_id = $_SESSION["employee_id"];
	$leaveRequests=$leaveR->retrieve_many('employee_id = ? AND status = ?',array($employee_id,'RQ'));
	$data["restrictions"] = array("pendingRequests"=>true);
	$data["leaveRequests"]=$leaveRequests;
	
	//get attedance start date from company details
	$today_date= date("Y-m-d");
	
	
	$dbh = getdbh();
	$stmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
  							FROM company_details WHERE info_flag="A" AND company_id=:company_id');
	$stmt->bindParam('company_id', $_SESSION['company_id']);
	$stmt->execute();
	$companyProp = $stmt->fetch();
	
	if($companyProp['attendance_dt'] !=1){
		if($today_date >= $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1)){
		$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']);
		$eDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
		$endDate = date("Y-m-d",strtotime("{$eDate} +1 months"));
		}else{
			$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']);
			$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
			$endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
		}
	}else{
		$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])
		
		."-01";
		$endDate = date('Y-m-t',strtotime($startDate));
	}
	$year = $_SESSION ['creditLeaveBased'] == 'finYear' ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
	
	$data["attn_start_dt"] = date('Y-m-d',strtotime("{$startDate} -1 week"));
	//$data["attn_start_dt"] = date('Y-m-d',strtotime("{$startDate}"));
	 $data["attn_end_dt"] = $endDate;
	
	
	//check the current day, if it is wednesday,thurs,fri,sat provide the start of the week
	//if it is sun,mon,tues - provide the start of the previous week
	 $currentWeekDays=array("Wed","Thu","Fri","Sat");
		

	
	$todayDay=date('D');
	$today= strtotime(date('Y-m-d')); 
	
	/*if(in_array($todayDay,$currentWeekDays)){
		$data['Rdate']=date('Y-m-d',strtotime("last Sunday", $today)); //displays current week monday as Restrict Date
	}else{
		$data['Rdate']=date('Y-m-d',strtotime("last Week -1 day", $today)); //displays last week monday as Restrict date
	}*/
	
	$def_time="05:00:00 AM";
	$curr_time=date('h:i:s A');
	
	if(in_array($todayDay,$currentWeekDays)){
	
		if($todayDay=="Wed"){
			if(DateTime::createFromFormat('H:i a', $curr_time)>DateTime::createFromFormat('H:i a', $def_time))
					$data['Rdate']=date('Y-m-d',strtotime("-25 day", $today)); //displays current week monday as Restrict Date
				else
					$data['Rdate']=date('Y-m-d',strtotime("-25 day", $today)); //displays last week monday as Restrict date
		}else{
			$data['Rdate']=date('Y-m-d',strtotime("-25 day", $today));
		}
	}else{
	
		$data['Rdate']=date('Y-m-d',strtotime("-25 day", $today)); //displays last week monday as Restrict date
	}
		
	
	/*$queryText = "SELECT *,IF((assumed_total-assumed_figure)<0,0,(assumed_total-assumed_figure)) assumed,
        IF(((leave_per_month-total_taken-(assumed_total-assumed_figure))<0 OR(this_year=0)),0,IF((leave_per_month-total_taken-(assumed_total-assumed_figure))<=max_combinable,(leave_per_month-total_taken-(assumed_total-assumed_figure)),max_combinable)) this_month
FROM (
SELECT leave_rule_id,this_year,leave_taken,min_monthly_contrib,leave_per_month,total_taken,max_combinable,
      IF((min_monthly_contrib-leave_taken)<0,0,min_monthly_contrib-leave_taken) assumed_figure,
      (SELECT SUM(assumed_figure) FROM (
            SELECT leave_rule_id,this_year,leave_taken,min_monthly_contrib,leave_per_month,
                    IF(min_monthly_contrib=0,0,IF((min_monthly_contrib-leave_taken)<0,0,min_monthly_contrib-leave_taken)) assumed_figure
                   
            FROM (     
            SELECT la.leave_rule_id,(la.opening_bal+la.allotted)-la.availed this_year,IFNULL(SUM(IF(lq.duration < lr.min_monthly_contrib,lr.min_monthly_contrib,(lq.duration))),0) leave_taken,
                   lr.type,lr.min_monthly_contrib,lr.max_combinable,lr.leave_per_month,
                   (SELECT IFNULL(SUM(lq.duration),0) total FROM leave_requests lq WHERE lq.employee_id=:employee_id AND lq.status='A' AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date) total_taken
            FROM company_leave_rules lr
            LEFT JOIN emp_leave_account la
            ON lr.leave_rule_id = la.leave_rule_id
            LEFT JOIN leave_requests lq
            ON la.leave_rule_id = lq.leave_type AND lq.employee_id=:employee_id AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date AND lq.status='A'
            WHERE la.employee_id=:employee_id  AND la.year=:year AND lr.enabled=1
            GROUP BY lr.leave_rule_id )w )q )assumed_total
FROM (     
SELECT la.leave_rule_id,(la.opening_bal+la.allotted)-la.availed this_year,IFNULL(SUM(IF(lq.duration < lr.min_monthly_contrib,lr.min_monthly_contrib,(lq.duration))),0) leave_taken,
       lr.type,lr.min_monthly_contrib,lr.max_combinable,lr.leave_per_month,
       (SELECT IFNULL(SUM(lq.duration),0) total FROM leave_requests lq WHERE lq.employee_id=:employee_id AND lq.status='A' AND lq.leave_type NOT IN ( 'LOP','od','otr', 'wfh' ) AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date) total_taken
FROM company_leave_rules lr
LEFT JOIN emp_leave_account la
ON lr.leave_rule_id = la.leave_rule_id
LEFT JOIN leave_requests lq
ON la.leave_rule_id = lq.leave_type AND lq.employee_id=:employee_id AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date AND lq.status='A'
WHERE la.employee_id=:employee_id  AND la.year=:year AND lr.enabled=1
GROUP BY lr.leave_rule_id) z
)t GROUP BY leave_rule_id";*/
	
	//to get active leave rules for company
	
	$compLeave="SELECT leave_rule_id FROM company_leave_rules WHERE enabled=1 ";
	$compLeave = $dbh->prepare($compLeave);
	$compLeave->execute();
	$compLeaves = $compLeave->fetchAll(PDO::FETCH_ASSOC);
	$condn="";
	
	foreach($compLeaves as $key=>$compLeave){
		if($key==0)
			$query_condn="SELECT '{$compLeave['leave_rule_id']}' leave_id UNION ALL";
		else
			$query_condn.=" SELECT '{$compLeave['leave_rule_id']}' UNION ALL";
		
		$condn.=" WHEN '{$compLeave['leave_rule_id']}' THEN SUM(t.day_count)";
		
    }
    $query_condn=rtrim($query_condn,"UNION ALL");
    
	$queryText="SELECT *,IF(available_leave<=0,0,available_leave) available,IF(available_leave<0 or available_leave=0,0,IF(available_leave<eligible,available_leave,eligible)) this_month
	FROM (
	SELECT *,IF((assumed_total-assumed_figure)<0,0,(assumed_total-assumed_figure)) assumed,
        IF(((leave_per_month-total_taken-(assumed_total-assumed_figure))<0 OR(this_year=0)),0,IF((leave_per_month-total_taken-(assumed_total-assumed_figure))<=max_combinable,(leave_per_month-total_taken-(assumed_total-assumed_figure)),max_combinable)) eligible
        ,(this_year - previous_leave) available_leave
FROM (
SELECT leave_rule_id,this_year,leave_taken,min_monthly_contrib,leave_per_month,total_taken,IFNULL(previous_leave,0) previous_leave,max_combinable,
      IF((min_monthly_contrib-leave_taken)<0,0,min_monthly_contrib-leave_taken) assumed_figure,
      (SELECT SUM(assumed_figure) FROM (
            SELECT leave_rule_id,this_year,leave_taken,min_monthly_contrib,leave_per_month,
                    IF(min_monthly_contrib=0,0,IF((min_monthly_contrib-leave_taken)<0,0,min_monthly_contrib-leave_taken)) assumed_figure
                   
            FROM (     
            SELECT la.leave_rule_id,(la.opening_bal+la.allotted)-la.availed this_year,IFNULL(SUM(IF(lq.duration < lr.min_monthly_contrib,lr.min_monthly_contrib,(lq.duration))),0) leave_taken,
                   lr.type,lr.min_monthly_contrib,lr.max_combinable,lr.leave_per_month,
                   (SELECT IFNULL(SUM(lq.duration),0) total FROM leave_requests lq WHERE lq.employee_id=:employee_id AND lq.status='A' AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date) total_taken
            FROM company_leave_rules lr
            LEFT JOIN emp_leave_account la
            ON lr.leave_rule_id = la.leave_rule_id
            LEFT JOIN leave_requests lq
            ON la.leave_rule_id = lq.leave_type AND lq.employee_id=:employee_id AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date AND lq.status='A'
            WHERE la.employee_id=:employee_id  AND la.year=:year AND lr.enabled=1
            GROUP BY lr.leave_rule_id )w )q )assumed_total
FROM (     
SELECT la.leave_rule_id,(la.opening_bal+la.allotted)-la.availed this_year,IFNULL(SUM(IF(lq.duration < lr.min_monthly_contrib,lr.min_monthly_contrib,(lq.duration))),0) leave_taken,
       lr.type,lr.min_monthly_contrib,lr.max_combinable,lr.leave_per_month,
       (SELECT IFNULL(SUM(lq.duration),0) total FROM leave_requests lq WHERE lq.employee_id=:employee_id  
       AND lq.leave_type NOT IN ('od','otr','wfh')
AND lq.leave_type NOT IN ('od','otr','wfh','lop') AND 
       lq.status='A' AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date) total_taken
FROM company_leave_rules lr
LEFT JOIN emp_leave_account la
ON lr.leave_rule_id = la.leave_rule_id
LEFT JOIN leave_requests lq
ON la.leave_rule_id = lq.leave_type AND lq.employee_id=:employee_id AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date AND lq.status='A'
WHERE la.employee_id=:employee_id  AND la.year=:year AND lr.enabled=1
GROUP BY lr.leave_rule_id) z
LEFT JOIN (SELECT leave_rule_type,
         CASE leave_rule_type
          $condn
         END previous_leave
   FROM emp_absences t
   WHERE t.employee_id =:employee_id AND t.absent_date BETWEEN :start_date AND :end_date GROUP BY t.leave_rule_type ) e
   ON z.leave_rule_id = e.leave_rule_type
)t GROUP BY leave_rule_id )g";
	
	//print_r($queryText); 
	
	$stmt = $dbh->prepare($queryText);
	$stmt->bindParam('employee_id', $employee_id);
	$stmt->bindParam('start_date', $startDate);
	$stmt->bindParam('end_date', $endDate);
	$stmt->bindParam('year', $year);
	$stmt->execute();
	$leaveAccounts = $stmt->fetchAll();
	
	
	$data["leaveAccounts"] = $leaveAccounts;
	
	
	
	
	
	if($leaveAccounts)
		$data["leaveBalance"] = true;
	else
		$data["leaveBalance"] = false;
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/leaveRequests-raise.js').'"></script>';
	$data['body'][]= View::do_fetch(VIEW_PATH.'leaveRequests/splraise.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}