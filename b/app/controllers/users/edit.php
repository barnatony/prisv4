<?php
function _edit() {
	require_login('Admin');
	$data['pagename']='Users-Add ';
	$view = new View();
	$data['view'] = $view;
	
	$data['body'][]=View::do_fetch(VIEW_PATH.'users/edit.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_admin.php',$data);
}
 