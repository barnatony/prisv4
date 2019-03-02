<?php
function _respond($for=null){
	
	$view = new View();
	$data["view"]=$view;
	$lr = new LeaveRequest();
	$data['for']=$for;
	if($for=="p"){
		$lr=$lr->retrieve_one('request_id=? AND id=?',array($_REQUEST['req'],$_REQUEST['id']));
		$emp=$lr->get("employee_id");
		$data["request"] = $lr;
	}else{
		if(!isset($_REQUEST["token"])&& !isset($_REQUEST["req"]) && !isset($_REQUEST['cid']) && !isset($_REQUEST['rep_id']))
			die("Error Occurred.Link Invalid");
		
			$token =$_REQUEST["token"];
			$req_id = base64_decode($_REQUEST["req"]);
			$id=base64_decode($_REQUEST['id']);
			$company_id = base64_decode($_REQUEST["cid"]);
			$reporting_person_id=base64_decode($_REQUEST['rep_id']);
		
			$_SESSION["company_id"] =$company_id ;
		
			$lr=$lr->retrieve_one('id=? AND request_id=? AND req_token=? AND req_token_expiry >NOW()',array($id,$req_id,$token));
			if(!$lr)
				custom_error("Sorry..!!","The Link you're trying to open is not found or Expired");
				$data["request"] = $lr;
				$emp=$lr->get("employee_id");
		
	}
	
	
	
	//check the leave_status if lop
	$dbh = getdbh();
	//prisxyz_master.company_details mc,local-FROM company_master_db.company_details mc
	$stmt=$dbh->prepare("SELECT DATE_FORMAT(mc.current_payroll_month,'%Y') payrollYear,DATE_FORMAT(mc.current_payroll_month,'%m') monthNo,
									mc.leave_based_on creditLeaveBased,cd.attendance_period_sdate attendance_dt,IF(mc.leave_based_on='finYear',IF((DATE_FORMAT(mc.current_payroll_month,'%m')IN ('01','02','03')),CONCAT((DATE_FORMAT(mc.current_payroll_month,'%Y')-1),SUBSTRING(DATE_FORMAT(mc.current_payroll_month,'%Y'),3,4)),CONCAT(DATE_FORMAT(mc.current_payroll_month,'%Y'),(SUBSTRING(DATE_FORMAT(mc.current_payroll_month,'%Y'),3,4)+1))),DATE_FORMAT(mc.current_payroll_month,'%Y')) financialYear
									FROM prisxyz_master.company_details mc
									INNER JOIN company_details cd
									ON mc.company_id = cd.company_id
									WHERE mc.company_id=:company_id AND mc.info_flag='A';");
		
	$company_id=$_SESSION['company_id'];
	$stmt->bindParam('company_id',$company_id);
	$stmt->execute();
	$companyProp = $stmt->fetch();
	
	if($companyProp['attendance_dt'] !=1){
		$startDate = $companyProp['payrollYear']."-".($companyProp['monthNo'])."-".$companyProp['attendance_dt'];
		$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
		$endDate = $companyProp['payrollYear']."-".($companyProp['monthNo'])."-".($companyProp['attendance_dt']-1);
	}else{
		$startDate = $companyProp['payrollYear']."-".($companyProp['monthNo'])."-01";
		$endDate = date('Y-m-t',strtotime($startDate));
	}
	$year = $companyProp ['creditLeaveBased'] == 'finYear' ? $companyProp ['financialYear'] : $companyProp ['payrollYear'];
	
	
	//to get list of working days, weekoff and holidays in given period
	$query=$dbh->prepare("SELECT *,IF((assumed_total-assumed_figure)<0,0,(assumed_total-assumed_figure)) assumed,
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
       (SELECT IFNULL(SUM(lq.duration),0) total FROM leave_requests lq WHERE lq.employee_id=:employee_id AND lq.status='A' AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date) total_taken
FROM company_leave_rules lr
LEFT JOIN emp_leave_account la
ON lr.leave_rule_id = la.leave_rule_id
LEFT JOIN leave_requests lq
ON la.leave_rule_id = lq.leave_type AND lq.employee_id=:employee_id AND lq.from_date BETWEEN :start_date AND :end_date AND lq.to_date BETWEEN :start_date AND :end_date AND lq.status='A'
WHERE la.employee_id=:employee_id  AND la.year=:year AND lr.enabled=1
GROUP BY lr.leave_rule_id) z
)t GROUP BY leave_rule_id");
	
	
	$query->bindParam('employee_id', $emp);
	$query->bindParam('start_date', $startDate);
	$query->bindParam('end_date', $endDate);
	$query->bindParam('year', $year);
	$query->execute();
	
	$data["leaveInfos"]=$leaveInfos=$query->fetchAll();
	
	$leaverules=array();
	$leaverules[]="LOP";
	foreach($leaveInfos as $leaveInfo){
			if($lr->get("duration")<=$leaveInfo['this_year'])
				$leaverules[]=$leaveInfo['leave_rule_id'];
				
		}


	$data['leaverules']=$leaverules;
	
	
	
		
			
		
	
	if(!$query){ //if query not executed throws an error
		$result["info"]=$dbh->errorInfo()[2];
	}
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/leaveRequests-respond.js').'"></script>';
	
	if($for=="p"){
		echo View::do_fetch(VIEW_PATH.'leaveRequests/respond.php',$data);
		
	}else{
		$data['body'][]=View::do_fetch(VIEW_PATH.'leaveRequests/respond.php',$data);
		View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
	}
}