<?php
function _ops_update() {
	//require_login('Admin');
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	$user_id=isset($_POST['uid'])?$_POST['uid']:0;
	$user = new User();
	if ($user_id) {
		$user->retrieve($user_id);
		$user->merge($_POST);
		if (!$user->exists())
			$result['info']='User not found!';
			else {
				$_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
				if ($user->update()->result[0]){
					$result[0] = true;
					$result['info']='User updated!';
				} else
					$result['info']='User update failed!';
			}
	   }else {
		$next = isset($_REQUEST['next'])?$_REQUEST["next"]:"";
		$_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$user->merge(array(
				"name"=>$_POST["name"],
				"username"=>$_POST["username"],
				"email"=>$_POST["email"],
				"password"=>$_POST["password"],

		));
		if(isset($_POST["admin"]))
			$user->set("privilage", "Admin");
			switch($result = $user->create()->result){
				case ($result[0]===true):
					$result['info'] = 'User Created!';
					$result['data']= ($next!="")?myUrl("main/login/?next={$next}"):myUrl("main/login");
					break;
				case ($result[0]===false):
					$result['info'] .= '- User Not Created!';
					break;
			}
    }
   //sending mail
	$emailData= $user->gen_email('Sign Up');
	if($emailData){
		extract($emailData);
	}else{
		$subject = $message =$to = $bcc = '';
	}
	NotifyEmail::_send($to, $subject, $message,null);
	//sending mail end
	echo json_encode($result);
}
?>