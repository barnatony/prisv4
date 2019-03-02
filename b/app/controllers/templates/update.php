<?php
function _update() {
	require_login('Admin');
	$template_id=isset($_REQUEST['tId'])?$_REQUEST['tId']:0;
	$template_id=max(0,intval($template_id));
	$result = array(false,"rowCount"=>0,"info"=>'');
	
	$template = new Etemplate($template_id);
	$send=isset($_REQUEST["send"])?$_REQUEST["send"]:"No";
	$core=isset($_REQUEST["core"])?$_REQUEST["core"]:"No";
		$template->merge(array(
				"tplname"=>$_REQUEST["template_name"],
				"subject"=>$_REQUEST["template_subject"],
				"notification_type"=>$_REQUEST["notification_type"],
				"message"=>$_REQUEST["message"],
				"send"=>$send,
				"core"=>$core
		));
		
	if ($template_id) {//Update COde
		if (!$template->exists()){
			$result['info']=' template not found!';
		}else{
			
			try{
				if(!$template->update()->result[0]) 
					throw new Exception("Error: "+$template->result['info']);
				}catch (Exception $e){
					
				}finally {
					$result=$template->result;
				}
			}
	}else {// Create Code
		$template->set("hidden", 0)->set("language_id", 1);
		try{
			if(!$template->create()->result[0])
				throw new Exception("Error: "+$template->result['info']);
		}catch (Exception $e){
			
		}finally {
			$result=$template->result;
		}
	}
	echo json_encode($result);
}