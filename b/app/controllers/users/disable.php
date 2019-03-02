<?php
function _disable(){
	$result = array(false,"rowCount"=>0,"info"=>'',"data"=>'');
	$user_id=$_POST["user_id"];
	$status=$_POST["status_id"];
	$user=new User($user_id);
	$user->set("isActive",$status);
	if (!$user->exists()){
		$result['info']='User not found!';
	}else{
		
		switch($result = $user->update()->result){
			case ($result[0]===true):
				$result['info'] .= 'Set User Active';
				break;
			case ($result[0]===false):
				$result['info'] .= 'set active failed!';
				break;
		}			
		
	}

	echo json_encode($result);
}