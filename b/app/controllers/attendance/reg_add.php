<?php
function _reg_add(){
	$result = array(false,"rowCount"=>0,"info"=>'');
	
	$reg=new Regularization();
	
	try{
		if(!isset($_REQUEST['reg_type']) || $_REQUEST['reg_type']=="" || $_REQUEST['reg_type'] =="Select an Option")
			throw new Exception("Please select an option");
			if(!isset($_REQUEST['reason']) || $_REQUEST['reason']=="")
				throw new Exception("Enter a valid reason.");
	
	}catch (Exception $e) {
		$result[0]=false;
		$result["info"]=$e->getMessage();
		die(json_encode($result));
	}
	
	 $day=date("Y-m-d ", strtotime(str_replace("/","-",$_REQUEST["day"])));
	 $employee_id = $_SESSION["employee_id"];
	 $reason=$_REQUEST["reason"];
	 $reason_type=$_REQUEST["reg_type"];
	 $regularization_type=$_REQUEST["type"];
	 $reason=$_REQUEST["reason"];
	 
	 if($reason_type=='Others' || $reason_type=='Personal Reasons'){
			$checktype=false;	 	
	 		$reg->set("status","R");
	 }else{ 
	 	$checktype=true;
	 	$reg->set("status","RQ");
	 }
	 
	 $reg->merge(array(
	 		"day"=>$day,
	 		"employee_id"=>$employee_id,
	 		"reason"=>$reason,
	 		"reason_type"=>$reason_type,
	 		"regularize_type"=>$regularization_type,
	 		"reason"=>$reason,
	 ));
	 

	 switch($result = $reg->create()->result){
	 	case ($result[0]===true):
	 		$result['info'] = 'Regularization Added!';
	 		break;
	 	case ($result[0]===false):
	 		$result['info'] .= '- Failed!';
	 		break;
	 }
	
	 
	 
	 //to get employeeDetails
	 $employee=$reg->joined_select('r.id,r.employee_id,w.employee_name,w.employee_reporting_person,
										wd.employee_name rep_name,wd.employee_name reporting_person_name,p.employee_id rep_id,p.employee_email reporting_email,p.employee_email,r.day,
										r.regularize_type,r.reason_type,r.req_token,r.req_token_expiry,r.reason,
										r.status,r.admin_reason,r.raised_by,r.approved_by,r.approved_on',
	 		'attendance_regularization r
											LEFT JOIN employee_work_details w ON r.employee_id=w.employee_id
											LEFT JOIN employee_personal_details p ON w.employee_reporting_person=p.employee_id
											LEFT JOIN employee_work_details wd ON w.employee_reporting_person=wd.employee_id',
	 		'r.employee_id=? AND r.id= ?',array($_SESSION['employee_id'],$reg->get('id')) )[0];
	 	
	 
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
	 	
	 	//for displaying only incorrectpunches to the TL 
	 	if($reg->regularize_type=='Incorrectpunches' && $employee['reporting_email']!=null)
	 			$sendDetails[]=$repMail;
	 	$sendDetails[]=$hrMail;
	 		
	 	
	 	
	 	
	 			
	 	
	 	
	 //for notify email	
	 foreach($sendDetails as $sendDetail){
	 if($result[0]==true && $checktype){
	 		$emailData = $reg->gen_regRequest($employee['employee_name'],$employee['employee_id'],$sendDetail);
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