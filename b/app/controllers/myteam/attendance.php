<?php
function _attendance() {
	$data['pagename']='Myteam Attendance';
	$view = new View();
	$data['view'] = $view;
	
	$employees=new Employee();
	if($_SESSION['authprivilage']=='employee'){
		$login_id = $_SESSION["employee_id"];
		//to check team exist for the login employee
			$data['teamleads']=$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$login_id);
	}elseif($_SESSION['authprivilage']=='hr'){
		$data['teamleads']=$employees->retrieve_one(); //if hr login 
	}else{
		redirect();
	}
	
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/datatables/jquery.dataTables.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/jquery-auto-complete/jquery.auto-complete.min.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/jquery-auto-complete/jquery.auto-complete.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js")"></script>';
	$data['foot'][]='<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js")"></script>';
	$data['foot'][]='<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js")"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-core.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-full.min.js"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/dust/dust-helpers.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/attendance-index.js').'"></script>';
	$data['body'][]=View::do_fetch(VIEW_PATH.'attendance/index.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}