<?php
function _index() {
	require_login('Admin');
	$data['pagename']='SC- Admin';
	$view = new View();
	$data['view'] = $view;
	
	View::do_dump(VIEW_PATH.'layouts/layout_admin.php',$data);
}
?>