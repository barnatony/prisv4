<?php
function _availability($type){
	$val=$_REQUEST['term'];
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	if(!$type || !$val )
		die(json_encode($result));
	if($type == 'un')
		$key="username";
	elseif ($type=='email'){
		$key="email";
		if(!validator::Email($val))
			$result["data"]="Invalid Email Format";
			die(json_encode($result));
	}
	
	$user = new User();
	$users=$user->retrieve_many("{$key} = ?",$val);
	if($users){
		$result["rowCount"] = count($users);
		$result["data"] = "Already Exists.";
	}else{
		$result["data"]="Available";	
	}
	$result[0]=true;
	echo json_encode($result);
}