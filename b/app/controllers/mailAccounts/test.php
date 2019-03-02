<?php
function _test(){
	$result = array(true,"rowCount"=>0,"info"=>'');
	$email=$_REQUEST['email'];
	$config_id=$_REQUEST['id'];
	$mail=new NotifyEmail();
	$res= $mail->_test($config_id, $email);
	if(!is_bool($res)){
		$result[0]=false;
		$result["info"]=$res;
	}
	echo json_encode($result);
}