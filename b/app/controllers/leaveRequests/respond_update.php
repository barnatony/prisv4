<?php
function _respond_update(){ //status ->withdrawed W, cancelled C, Approved A -by Admin or TL,
	$result = array(false,"rowCount"=>0,"info"=>'',"from"=>'');
	
	if(!$_REQUEST["id"] || !$_REQUEST["value"] || !$_REQUEST["remarks"]){
		$result["info"] = "Provide all required Fields.";
		echo json_encode($result);
		die();
	}
	
	if(isset($_SESSION['authprivilage']) && $_SESSION['authprivilage']=="employee"){
		$login_id = $_SESSION["employee_id"];
	}elseif(isset($_SESSION["authprivilage"]) && $_SESSION['authprivilage']=="hr"){
		$login_id = $_SESSION["login_id"];
	}
	
	$req_id = $_REQUEST["req_id"];
	$id=$_REQUEST["id"];
	$leaveR=new LeaveRequest();
	if(!$_REQUEST['for']){
		$token = $_REQUEST["req_token"];
		$leaveR->retrieve_one('request_id=? AND req_token=? AND req_token_expiry >NOW()',array($req_id,$token));
		$result["from"]= $submitted_from="email";
	}else{
		$leaveR->retrieve_one('request_id=? AND id=?',array($req_id,$id));
		$result["from"]= $submitted_from="app";
	}
	
	if(!$leaveR->exists()){
		$result["info"] = "Request Not Found";
		echo json_encode($result);
		die();
	}
	
	$wherewhat = "lr.id = ? "; 
	$bindings = array($_REQUEST["id"]);
	
	
	
	$leaveRequest = $leaveR->joined_select("lr.request_id","leave_requests lr INNER JOIN employee_work_details w ON lr.employee_id=w.employee_id",$wherewhat,$bindings);
	if(!$leaveRequest){
		$result["info"] = "You Dont have Privilages.";
		echo json_encode($result);
		die();
	}
	
		$leaveR->set("status",$_REQUEST["value"]);
		
	if(isset($_REQUEST['leave_rule']))
		$leaveR->set("leave_type",$_REQUEST['leave_rule']);
	
	if($_REQUEST["value"] == "A" || $_REQUEST["value"] == "R"){
		$leaveR->merge(array("admin_reason"=>$_REQUEST["remarks"]));
	}
	
	if($_REQUEST["value"]=="A"){
			$leaveR->merge(array("approved_on"=> date('Y-m-d H:i:s'),"approved_by"=>isset($_SESSION['authuid'])?$login_id:$_REQUEST['rep_id']));
	}
	
	
	
	switch($result = $leaveR->update()->result){
		case ($result[0]===true):
			$result['info'] = 'Leave Request Updated!';
			break;
		case ($result[0]===false):
			$result['info'] .= '- Leave Request Update Failed!';
			break;
	}
	
	$result["from"] = $submitted_from;
	
	if($result[0]==true){
		$emailData = $leaveR->gen_LeaveResponse();
		if($emailData){
			extract($emailData);
		}else{
			$subject = $message = $to = $bcc = '';
		}
		NotifyEmail::_send($to, $subject, $message,null,null,null);
	}
	
	echo json_encode($result);																
}