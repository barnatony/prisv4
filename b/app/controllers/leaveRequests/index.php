<?php
function _index(){
	$data['pagename']='Leave Requests ';
	$view = new View();
	$data['view'] = $view;
	
	
	$today_date= date("Y-m-d");
	$privilage=$_SESSION["authprivilage"];
	$leaveRequests=new LeaveRequest();
	if($privilage!='hr'){
	$employee_id = $_SESSION["employee_id"];
	
	
	
	
	
	
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
		$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
		$endDate = date('Y-m-t',strtotime($startDate));
	}
	$year = $_SESSION ['creditLeaveBased'] == 'finYear' ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
	
	
	
	
	
	$todayDay=date('D');
	$today= strtotime(date('Y-m-d'));
	
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
	
				$condn.=" WHEN '{$compLeave['leave_rule_id']}' THEN t.{$compLeave['leave_rule_id']}";
	
	}
	$query_condn=rtrim($query_condn,"UNION ALL");
	
	$queryText="SELECT *,IF((assumed_total-assumed_figure)<0,0,(assumed_total-assumed_figure)) assumed,
	IF(((leave_per_month-total_taken-(assumed_total-assumed_figure))<0 OR(this_year=0)),0,IF((leave_per_month-total_taken-(assumed_total-assumed_figure))<=max_combinable,(leave_per_month-total_taken-(assumed_total-assumed_figure)),max_combinable)) this_month
	,(this_year - previous_leave) available
	FROM (
	SELECT leave_rule_id,this_year,leave_taken,min_monthly_contrib,leave_per_month,total_taken,previous_leave,max_combinable,
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
	LEFT JOIN (SELECT leave_id,
	CASE c.leave_id
	$condn
	END previous_leave
	FROM payroll_preview_temp t
	CROSS JOIN
	(
	$query_condn
	) c
	WHERE t.employee_id =:employee_id) e
	ON z.leave_rule_id = e.leave_id
	)t GROUP BY leave_rule_id";
	
	
	
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
	
	
	
	}
	$data['custom_filters_leave']=$leaveRequests->select(' DISTINCT UPPER((leave_type)) leave_type');
	$data['custom_filters_status']=$leaveRequests->select(' DISTINCT UPPER((status)) status');
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/datatables/css/jquery.dataTables.min.css').'">';
	//$data['head'][]='<link rel="stylesheet" href="'.myUrl('https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css').'">';
	//$data['head'][]='<link rel="stylesheet" href="'.myUrl('https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	
	$data['foot'][]='<script src="'.myUrl('js/core/bootstrap.min.js').'"></script>';
	//$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.js').'"></script>';
	//$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js').'"></script>';
	//$data['foot'][]='<script src="'.myUrl('../js/DT_bootstrap.js').'"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js"></script>';
	//$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jQuery.dataTables.min.js').'"></script>';
    //$data['foot'][]='<script src="'.myUrl('js/plugins/DataTables/js/datatables.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>';
    //$data['foot'][]='<script src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>';	
	
	$data['foot'][]='<script src="'.myUrl('js/pages/leaveRequests-respond.js').'"></script>';
	if($_SESSION["authprivilage"]=="hr"){
		$data['foot'][]='<script src="'.myUrl('js/pages/leaveRequests-index.js').'"></script>';
		//$data['foot'][]='<script src="https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.15/datatables.min.js"></script>';
		$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>';
		//$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>';
		//$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>';
		$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.html5.min.js"></script>';
		$data['foot'][]='<script src="'.myUrl('js/core/bootstrap.min.js').'"></script>';
		$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>';
		$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css"></script>';
		$data['foot'][]='<script src="https://gyrocode.github.io/jquery-datatables-alphabetSearch/1.1.2/css/dataTables.alphabetSearch.css"></script>';
		//$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.min.js').'"></script>';
		$data['foot'][]='<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>';
		//$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>';
		
	}else{
		$data['foot'][]='<script src="'.myUrl('js/pages/leaveRequests-index.js').'"></script>';
	}
	
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['body'][]=View::do_fetch(VIEW_PATH.'leaveRequests/index.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}
