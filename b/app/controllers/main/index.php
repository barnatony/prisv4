<?php
function _index($emp_id) {
	$data['pagename']='Home ';
	$view = new View();
	$data['view'] = $view;
	$dbh = getdbh();
	$stmt = $dbh->prepare('SELECT * from employee_work_details WHERE employee_id = :employee_id');
	$stmt->bindParam('employee_id', $emp_id);
	if(!$stmt->execute())
		print_r($stmt->errorInfo());
	else
		$res= $stmt->fetch();
	
	
	$event=new Event();
	$data['body'][]=View::do_fetch(VIEW_PATH.'main/index.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}
?>