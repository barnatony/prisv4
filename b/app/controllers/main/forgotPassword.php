<?php
function _forgotPassword(){
	$data['pagename']='Forget Password';
	$view = new View();
	$data['view'] = $view;
	$data['body'][]=View::do_fetch(VIEW_PATH.'main/forgotPassword.php',$data);
	$data['foot'][]="<script src='".myUrl('js/pages/main-forgotPassword.js')."'></script>";
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}
