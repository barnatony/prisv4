<?php
function _reg_update(){
	//status ->withdrawed W, cancelled C, Approved A -by Admin or TL,
		$result = array(false,"rowCount"=>0,"info"=>'',"from"=>'');
	
		if(!$_REQUEST["id"] || !$_REQUEST["value"] || !$_REQUEST["remarks"]){
			$result["info"] = "Provide all required Fields.";
			echo json_encode($result);
			die();
		}
		
		$id = $_REQUEST["id"];
		$reg=new Regularization();
		if(!$_REQUEST['for']){ //if respond from mail
			$token = $_REQUEST["req_token"];
			$reg->retrieve_one('id=? AND req_token=? AND req_token_expiry >NOW()',array($id,$token));
			$result["from"]= $submitted_from="email";
		}else{//for popup
			$reg->retrieve_one('id=?',$id);
			$result["from"]= $submitted_from="app";
			
		}
		if(!$reg->exists()){
			$result["info"] = "Request Not Found";
			echo json_encode($result);
			die();
		}
		
		$wherewhat = "r.id = ? ";
		$bindings = array($_REQUEST["id"]);
	
	
	
		$regRequest = $reg->joined_select("r.id","attendance_regularization r INNER JOIN employee_work_details w ON r.employee_id=w.employee_id",$wherewhat,$bindings);
		if(!$regRequest){
			$result["info"] = "You Dont have Privilages.";
			echo json_encode($result);
			die();
		}
	
		$reg->set("status",$_REQUEST["value"]);
	
		if($_REQUEST["value"] == "A" || $_REQUEST["value"] == "R"){
			$reg->merge(array("admin_reason"=>$_REQUEST["remarks"]));
		}
		if($_REQUEST["value"]=="A"){
			$reg->merge(array("approved_on"=> date('Y-m-d H:i:s'),"approved_by"=>isset($_SESSION['authuid'])?$_SESSION['authuid']:$_REQUEST['rep_id']));
		}
	
	
	
	
		switch($result = $reg->update()->result){
			case ($result[0]===true):
				$result['info'] = 'Regularization Request Updated!';
				break;
			case ($result[0]===false):
				$result['info'] .= '- Regularization Request Update Failed!';
				break;
		}
		$result["from"] = $submitted_from;
		if($result[0]==true){
			$emailData = $reg->gen_regResponse();
			if($emailData){
				extract($emailData);
			}else{
				$subject = $message = $to = $bcc = '';
			}
			NotifyEmail::_send($to, $subject, $message,null,null,null);
		}
	
		echo json_encode($result);
	}	
