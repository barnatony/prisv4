<?php
function _add(){
	$result = array(true,"rowCount"=>0,"info"=>'');
	
	$leave=new LeaveRequest();
	
	
	//check the current day, if it is wednesday,thurs,fri,sat provide the start of the week
	//if it is sun,mon,tues - provide the start of the previous week
	$currentWeekDays=array("Wed","Thu","Fri","Sat");
	
	
	
	$todayDay=date('D');
	//$todayDay="Wed";
	$today= strtotime(date('Y-m-d '));
	$def_time="05:00:00 AM";
	$curr_time=date('h:i:s A');
	//$curr_time="04:58:00 AM";
	
	if(in_array($todayDay,$currentWeekDays)){
		
		if($todayDay=="Wed"){
			if(DateTime::createFromFormat('H:i a', $curr_time)>DateTime::createFromFormat('H:i a', $def_time))
				$Rdate=date('Y-m-d',strtotime("-17 day", $today)); //displays current week monday as Restrict Date
			else 
				$Rdate=date('Y-m-d',strtotime("-17 day", $today)); //displays last week monday as Restrict date
		}else{
				$Rdate=date('Y-m-d',strtotime("-17 day", $today));
		}
	}else{
		
			$Rdate=date('Y-m-d',strtotime("-17 day", $today)); //displays last week monday as Restrict date
	}
	
	
	$from = $_REQUEST["duration_from"]=date("Y-m-d ", strtotime(str_replace("/","-",$_REQUEST["duration_from"])));
	$to = $_REQUEST["duration_to"]=date("Y-m-d ", strtotime(str_replace("/","-",$_REQUEST["duration_to"])));
	$employee_id = $_SESSION["employee_id"];
	

		
	//Validations
	try{
	 if(!isset($_REQUEST['duration_from']) || $_REQUEST['duration_from']=="")
	 		throw new Exception("Select From date");
	 	if(!isset($_REQUEST['duration_to']) || $_REQUEST['duration_to']=="")
	 		throw new Exception("Select To date");
	 	if(!isset($_REQUEST['leave_rule']) || $_REQUEST['leave_rule']=="")
	 		throw new Exception("Select Leave Type");
	 	if(!isset($_REQUEST['reason']) || $_REQUEST['reason']=="")
	 		throw new Exception("Enter a Reason for leave");
	 	if($_REQUEST['duration_from']==$_REQUEST['duration_to'] && $_REQUEST['from_half']=='SH' && $_REQUEST['to_half']=='FH')
	 		throw new Exception("You cant apply leave for Second Half and First Half of the same day.");
	 	if($from<=$Rdate)  //for past week restriction
	 		throw new Exception("You can't Apply Leaves for the past week after Tuesday of the current week");
	 	if( $_REQUEST['duration_to']<$_REQUEST['duration_from'])  //for duration to grater than duration from 
	 		throw new Exception("Select the Duration properly.");
	 	}catch (Exception $e) {
	 		$result[0]=false;
	 		$result["info"]=$e->getMessage();
	 		die(json_encode($result));
		}
	
	
	//check whether already an absence  or leave request found for the employee
	//$from,$to,$
	$stDate= date('Y-m-01', strtotime($from));
	$cond = ($from == $stDate)?"(from_date ='$from' AND":"(from_date BETWEEN '$from' AND '$to' OR";
	
	$query = "SELECT employee_id,request_id,from_date,to_date,reason,leave_type
			  FROM leave_requests
			  WHERE employee_id ='$employee_id' AND $cond to_date BETWEEN '$from' AND '$to') AND status NOT IN ('R','W','C');";
	
	$dbh = getdbh();
	$stmt=$dbh->query($query);
	
	$daysResult =$stmt->fetch(PDO::FETCH_ASSOC);
	if($daysResult){
		$result[0]=false;
		$result["info"]="Leave Can't Applied. Leave Requests already found for the same duration.";
		die(json_encode($result));
	}
	
	
	if($_REQUEST["considerLop"])
		$leave_rule = "lop";
	else
		$leave_rule=$_REQUEST['leave_rule'];
	$duration=$_REQUEST["duration"];
	$rand = mt_rand ( 10000, 99999 );
	$request_id = "LR" . $rand;
	$leave->merge(array(
			"request_id"=>$request_id,
			"employee_id"=>$employee_id,
			"from_date"=>$from,
			"from_half"=>$_REQUEST["from_half"],
			"to_date"=>$to,
			"to_half"=>$_REQUEST["to_half"],
			"leave_type"=>$leave_rule,
			"from_date"=>$_REQUEST["duration_from"],
			"to_date"=>$_REQUEST["duration_to"],
			"duration"=>$duration,
			"reason"=>$_REQUEST['reason'],
			"status"=>'RQ',
			"updated_by"=>$employee_id
	
	));
	
// 	if($_SESSION["authprivilage"]=="hr"){
// 		$leave->merge(array(
// 				"admin_reason"=>$_REQUEST['admin_reason'],
// 				"approved_on"=>$_REQUEST['approved_on'],
// 				"approved_by"=>$_REQUEST['approved_by']
// 		));
// 	}
	
	
	switch($result = $leave->create()->result){
		case ($result[0]===true):
			$result['info'] = 'Leave Requested!';
			break;
		case ($result[0]===false):
			$result['info'] .= '- Leave Request Failed!';
			break;
	}
	
	//to get employeeDetails
	$employee=$leave->joined_select('l.request_id,l.employee_id,w.employee_name,l.from_date sdate,
												l.to_date edate,l.duration,l.leave_type,l.reason,l.status,wd.employee_id rep_id,
												l.raised_on,l.admin_reason,wd.employee_name reporting_person_name,pd.employee_email,p.employee_email reporting_email,l.approved_by,l.approved_on,l.updated_by,l.updated_on,
												l.req_token,l.req_token_expiry',
			'leave_requests l
												LEFT JOIN employee_work_details w ON l.employee_id = w.employee_id
												LEFT JOIN employee_work_details wd ON w.employee_reporting_person =wd.employee_id
												LEFT JOIN employee_personal_details pd ON l.employee_id = pd.employee_id
												LEFT JOIN employee_personal_details p ON wd.employee_id=p.employee_id',
			'l.employee_id=? AND l.request_id = ?',array($_SESSION['employee_id'],$leave->get('request_id')))[0];
		
	
	//to get company email
	$query="SELECT user_name,password,emp_id,login_privilage,email,last_login,send_email FROM company_login_details WHERE send_email=1";
	$dbh = getdbh();
	$stmt=$dbh->query($query);
	if(!$stmt)
		die($stmt->errorInfo()[1]);
		$company_logins =$stmt->fetchAll(PDO::FETCH_ASSOC);
	
		
	$sendDetails=array();
		$repMail=array("email"=>$employee['reporting_email'],"name"=>$employee['reporting_person_name'],"id"=>$employee['rep_id']);
		
	foreach($company_logins as $company){
		$hrMail=array("name"=>$company['user_name'],"email"=> $company['email'],"id"=>$company['user_name']);
		
	}	
	
	$hRleave=array("lop","wfh","Otr","od");
	//to check if employee reporting person is not null if null send mail only to hr 
	//and to leave type applicable only for HR
	//if(!in_array($leave->leave_type,$hRleave) && $employee['reporting_email']!=null)
	if($employee['reporting_email']!=null)
		$sendDetails[]=$repMail;
	
	$sendDetails[]=$hrMail;
	
	
		
		
		
	
	//for NotifyEmail	
	foreach ($sendDetails as $sendDetail){
		if($result[0]==true){
		$emailData = $leave->gen_leaveRequest($employee['employee_name'],$employee['employee_id'],$sendDetail);
		if($emailData){
			extract($emailData);
		}else{
			$subject = $message = $to = $bcc = '';
		}
		NotifyEmail::_send($to, $subject, $message,null,null,null);
	}
	}
	
	echo json_encode($result);																
}