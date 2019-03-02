<?php
function _update(){ //status ->withdrawed W by emp, Approved A -by Admin or TL,  
	$result = array(false,"rowCount"=>0,"info"=>'');
	
	if(!$_REQUEST["id"] || !$_REQUEST["value"] || !$_REQUEST["remarks"]){
		$result["info"] = "Provide all required Fields.";
		echo json_encode($result);
		die();
	}
		
	$reg=new Regularization($_REQUEST["id"]);
	if(!$reg->exists()){
		$result["info"] = "Regularization Not Found";
		echo json_encode($result);
		die();
	}
	
	$wherewhat = "r.id = ? "; 
	$bindings = array($_REQUEST["id"]);
	
	if($_SESSION["authprivilage"]=="employee"){
		$wherewhat .= " AND r.employee_id=? OR w.employee_reporting_person=?";
		$bindings[]= $_SESSION["employee_id"];
		$bindings[]= $_SESSION["employee_id"];
	}
	
	$regReq = $reg->joined_select("r.id","attendance_regularization r INNER JOIN employee_work_details w ON r.employee_id=w.employee_id",$wherewhat,$bindings);
	if(!$regReq){
		$result["info"] = "You Dont have Privilages.";
		echo json_encode($result);
		die();
	}
	
	$reg->set("status",trim($_REQUEST["value"]));
	
	if($_SESSION['authprivilage']=="employee"){
		$login_id = $_SESSION["employee_id"];
	}elseif($_SESSION["authprivilage"]=="hr"){
		$login_id = $_SESSION["login_id"];
	}
	
	if($_REQUEST["value"] == "A" || $_REQUEST["value"] == "R"){
		$reg->merge(array("admin_reason"=>$_REQUEST["remarks"],"updated_by"=>$login_id));
		if($_REQUEST["value"]=="A")
			$reg->merge(array("approved_on"=> date('Y-m-d H:i:s'),"approved_by"=>$login_id));
	}else{
		$reg->set("updated_by",$_SESSION['authuid']);
	}
	switch($result = $reg->update()->result){
		case ($result[0]===true):
			
			$result['info'] = 'Regularization Updated!';
			break;
		case ($result[0]===false):
			$result['info'] .= '- Regularization Update Failed!';
			break;
	}
	
	
	
	echo json_encode($result);																
}