<?php
function _login($username=null) {
  $result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
  $in_app_login=false;
  if($username){
  	$in_app_login =true;
  	$_POST['username']=trim(base64_decode($username));
  	$_POST['password']="";
  }
  	
  
  $uid = isset($_SESSION['authuid'])?$_SESSION['authuid']:null;
  $user =new User($uid);
  if(!isset($_SESSION["authuid"])){
  	$username=trim($_POST['username']);
  	$user->retrieve_one('(username=? OR email=?) AND isActive=1',array($username,$username));
  	
  }
  $password=$_POST['password'];
  $next = isset($_REQUEST['next'])?$_REQUEST["next"]:"";
  if (!$user->exists()) {
    unset($_SESSION['authuid']);
    unset($_SESSION['authname']);
    unset($_SESSION['authprivilage']);
    $result['info']="User Doesn't Exist";
    die(json_encode($result));
  }
  if (!password_verify($password, $user->get('password')) && !$in_app_login) {
    if(!isset($_SESSION['authuid'])){
  		unset($_SESSION['authuid']);
    	unset($_SESSION['authname']);
    	unset($_SESSION['authprivilage']);
  	}
    $result['info']="Wrong Password!";
    die(json_encode($result));
  }
  
  
  if($user->get("p_reset_token")){
  	$url = myUrl('profile/resetPassword/'.$user->get("p_reset_token")."/");
  }else{//Login Succeeded
  $_SESSION['authuid'] = $user->get('user_id');
  $_SESSION['authname'] = $user->get('name');
  $_SESSION['authprivilage'] = $user->get('privilage');
  $user->merge(array("last_login"=>date('Y-m-d H:i:s')));
  $user->update();
  }
  
  if($user->get('privilage')=='Admin'){
  	//for Admin
  	$result[0]=true;
  	$url = isset($url)?$url:myUrl('admin/');
  	$result['data']= ($next!="")?$next:$url;
  	redirect($result['data']);
  	//die(json_encode($result));
  }else{
  	//User 
  	$url = isset($url)?$url:myUrl('');
  	$result[0]=true;
  	$result['data']= ($next!="")?$next:$url;
  	redirect($result['data']);
  	//die(json_encode($result));
  }
}