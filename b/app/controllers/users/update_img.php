<?php
function _update_img(){
	require_login('User');
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	$user = new User($_SESSION['authuid']);
	$_POST["image_url"] = $_POST["image_url"]==""?"/img/avatar.jpg":$_POST["image_url"];
	$_POST['image_url'] = preg_replace('/'.preg_quote(WEB_FOLDER, '/').'/', '', $_POST['image_url'], 1);//remove project folder
	
	
	$user->set("image", $_POST['image_url']);
	if ($user->update()->result[0]){
		$result[0] = true;
		$result["rowCount"] = $user->result["rowCount"];
		$result['info']='User updated!';
	} else
		$result['info']="User update failed!";
	echo json_encode($result);
}