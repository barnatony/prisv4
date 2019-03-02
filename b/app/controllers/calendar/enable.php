<?php
function _enable(){
	$result = array(false,"rowCount"=>0,"info"=>'',"data"=>'');
	
	$id=isset($_REQUEST["id"])?$_REQUEST["id"]:null;
	$value=isset($_REQUEST["value"])?$_REQUEST["value"]:null;
	
	if(!$id){
		$result['info']="Id is needed";
		echo json_encode($result);
		die();
	}
	$event=new Event($id);
	$event->set("enable",$value);
	
	if (!$event->exists()){
		$result['info']='Event not found!';
	}else{
	
		switch($result = $event->update()->result){
			case ($result[0]===true):
				$result['info'] = 'Set Event Active';
				break;
			case ($result[0]===false):
				$result['info'] = 'Set active failed!';
				break;
		}
	}
	
	echo json_encode($result);
}
?>