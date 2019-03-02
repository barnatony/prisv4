<?php
function _ops_update(){
	
	$result = array(true,"rowCount"=>0,"info"=>'');
	try{
		if(!isset($_POST['user_profile_email']) || $_POST['user_profile_email']==""|| !validator::Email($_POST['user_profile_email']))
			throw new Exception("Enter a valid mail address.");
			if(!isset($_POST['profile_first_name']) || $_POST['profile_first_name']=="")
				throw new Exception("Enter  your first name.");
				
	}catch (Exception $e) {
		$result[0]=false;
		$result["info"]=$e->getMessage();
		die(json_encode($result));
	}
	
	

$user = new User($_SESSION["authuid"]);
$_POST["user_dob"]=date("Y-m-d H:i:s", strtotime(str_replace("/","-",$_POST["user_dob"])));  


$mergeArr=array(
		"lastname"=>$_POST["profile_last_name"],
		"name"=>$_POST["profile_first_name"],
		"email"=>$_POST["user_profile_email"],
		"dob"=>$_POST["user_dob"],
	    "gender"=>$_POST["user_signup_gender"],
);
if(isset($_POST["username"]))
	$mergeArr["username"]=$_POST["username"];
$user->merge($mergeArr);

switch($result = $user->update()->result){
	case ($result[0]===true):
		$result['info'] = 'Profile Updated';
		break;
	case ($result[1]===false):
		$result['info'] .= 'Updated failed!';
		break;
}

echo json_encode($result);

}
?>