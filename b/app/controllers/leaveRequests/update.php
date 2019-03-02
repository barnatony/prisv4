<?php
function _update(){ //status ->withdrawed W, cancelled C, Approved A -by Admin or TL,  
	$result = array(false,"rowCount"=>0,"info"=>'');
	
	if(!$_REQUEST["id"] || !$_REQUEST["value"] || !$_REQUEST["remarks"]){
		$result["info"] = "Provide all required Fields.";
		echo json_encode($result);
		die();
	}
		
	$leaveR=new LeaveRequest($_REQUEST["id"]);
	if(!$leaveR->exists()){
		$result["info"] = "Request Not Found";
		echo json_encode($result);
		die();
	}
	
	$wherewhat = "lr.id = ? "; 
	$bindings = array($_REQUEST["id"]);
	
	if($_SESSION["authprivilage"]=="employee"){
		$wherewhat .= " AND lr.employee_id=? OR w.employee_reporting_person=?";
		$bindings[]= $_SESSION["employee_id"];
		$bindings[]= $_SESSION["employee_id"];
	}
	
	$leaveRequest = $leaveR->joined_select("lr.id","leave_requests lr INNER JOIN employee_work_details w ON lr.employee_id=w.employee_id",$wherewhat,$bindings);
	if(!$leaveRequest){
		$result["info"] = "You Dont have Privilages.";
		echo json_encode($result);
		die();
	}
	
	$leaveR->set("status",trim($_REQUEST["value"]));
	
	if($_SESSION['authprivilage']=="employee"){
		$login_id = $_SESSION["employee_id"];
	}elseif($_SESSION["authprivilage"]=="hr"){
		$login_id = $_SESSION["login_id"];
	}
	
	if($_REQUEST["value"] == "A" || $_REQUEST["value"] == "R"){
		$leaveR->merge(array("admin_reason"=>$_REQUEST["remarks"],"updated_by"=>$login_id));
		if($_REQUEST["value"]=="A")
			$leaveR->merge(array("approved_on"=> date('Y-m-d H:i:s'),"approved_by"=>$login_id));
	}else{
		$leaveR->set("updated_by",$_SESSION['authuid']);
	}
	switch($result = $leaveR->update()->result){
		case ($result[0]===true):
			
			$result['info'] = 'Leave Request Updated!';
			break;
		case ($result[0]===false):
			$result['info'] .= '- Leave Request Update Failed!';
			break;
	}
	
	echo json_encode($result);																
}