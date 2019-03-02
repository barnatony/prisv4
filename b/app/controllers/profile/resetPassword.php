<?php
function _resetPassword($token) {
	$data['pagename']='Change Password ';
	$view = new View();
	$data['view'] = $view;
	$user = new User();
	$user->retrieve_one('p_reset_token=? AND p_reset_expiry>NOW()',array($token));
	$data['user']=$user;
	if(!$user->exists())
		$data['body'][]=View::do_fetch(VIEW_PATH.'errors/404.php',$data);
	else
		$data['body'][]=View::do_fetch(VIEW_PATH.'profile/change_password.php',$data);
	$data['foot'][]="<script src='".myUrl('js/pages/profile-change_password.js')."'></script>";
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
	
	
}
?>

	