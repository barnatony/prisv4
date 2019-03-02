<?php
function _ajx_load(){
	require_login("Admin");
	$result = array(false,"rowCount"=>0,"info"=>'',"data"=>'');
	$user=new User();
	$wherewhat = $bindings = "";
	$n=!isset($_REQUEST['n'])?0:$_REQUEST['n'];
	$users= $user->select('name,email, DATE_FORMAT(last_login,"%b %d, %h:%i:%s %p ") date,p_reset_token,image,privilage',$wherewhat,$bindings," $n,12");
	$result['data']=$users;
	$result[0]=true;
	$result["rowCount"] = count($result['data']);
	echo json_encode($result);
	
}
?>






