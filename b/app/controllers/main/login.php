<?php
function _login() {
	if(isLogged('Admin')){
		redirect('admin');
		die();
	}elseif(isLogged("User")){
		redirect('');
		die();
	}else{
		$data['pagename']='Login';
		$view = new View();
		$data['view'] = $view;
		$data['next']=isset($_REQUEST['next'])?$_REQUEST['next']:null;	
	    	$data['body'][]=View::do_fetch(VIEW_PATH.'main/login.php',$data);
		$data['foot'][]="<script src='".myUrl('js/pages/main-login.js')."'></script>";
		View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
	}
}
?>
