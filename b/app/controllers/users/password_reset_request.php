<?php
function _password_reset_request($email=null){
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	$user=new User();
	if(!$email){
		require_login('Admin');
		$user_id=$_POST["user-id"];
		$user->retrieve($user_id);
	}else
		$user->retrieve_one('email=?',$email);
	if(!$user->exists()){
			$result["errorInfo"]="No Users found.";
			echo json_encode($result);
			die();
	}
	$expiry=date('Y-m-d H:i:s', strtotime("+2 months", strtotime(date('Y-m-d h:i:sa'))));
	$user->merge(array("p_reset_token"=>getToken(),"p_reset_expiry"=>$expiry));
	if($user->update()->result[0])
		$result[0]= true;
	   
	else 
		$result["errorInfo"] = $user->get("result")["info"];

	//sending mail
	$emailData= $user->gen_email('Password Reset');
	if($emailData){
		extract($emailData);
	}else{
		$subject = $message =$to = $bcc = '';
	}
	NotifyEmail::_send($to, $subject, $message,null,$bcc);
	//sending mail end	
	echo json_encode($result);
}
?>

