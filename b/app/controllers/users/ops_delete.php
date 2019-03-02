<?php
function _ops_delete() {
  require_login('Admin');
  $result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
  $user_id=max(0,intval($_POST['user_id']));
  $user=new User();
  if ($user_id) {
  	$user->retrieve($user_id);
  	if($user->exists() && $user->get('user_id') != $_SESSION['authuid']){
  		$user = $user->delete();
  		$result[0] = true;
  		$result['info'] = 'User Deleted';
  	}else{
  		$result['info'] = 'Failed to delete User';
  	}
  }
  
  echo json_encode($result);
}