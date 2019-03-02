<?php
function _changePassword($token=null) {
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	$user = new User();
	if(!$token){
		require_login('user');
		$user_id= $_SESSION['authuid'];
		$user->retrieve($user_id);
	}else
		$user->retrieve_one('p_reset_token=?',array($token));


		$password = password_hash($_POST['user_profile_confirm_password'], PASSWORD_BCRYPT);

		$user->merge(array("password"=>$password));
		if (!$user->exists())
			$result['info']='User not found!';
		else
			if ($user->update()->result[0]){
				if ($result[0]=true) {
			    	if(!$token){
			    		unset($_SESSION['authuid']);
			    		unset($_SESSION['authname']);
			    		unset($_SESSION['authprivilage']);
			    	}else{
			    		$user->merge(array("p_reset_token"=>"","p_reset_expiry"=>"0000-00-00 00:00:00"));
			    		$user->update();
			    	}
				}
				$result['info']='Password updated!';
			} else
				$result['info']='Password update failed!';


				echo json_encode($result);
}