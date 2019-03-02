<?php
function _index() {
$data['pagename']='Reports ';
		$view = new View();
		$data['view'] = $view;
		
		
		$data['body'][]=View::do_fetch(VIEW_PATH.'reports/index.php',$data);
		View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
	
}