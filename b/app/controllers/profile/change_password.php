<?php
function _change_password() {
	require_login('user');
	$data['pagename']='Change Password ';
	$view = new View();
	$data['view'] = $view;
	$data['body'][]=View::do_fetch(VIEW_PATH.'profile/change_password.php',$data);
	$data['foot'][]="<script src='".myUrl('js/pages/profile-change_password.js')."'></script>";
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
	
	
}
?>

