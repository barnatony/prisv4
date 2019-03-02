<?php
function _leaveRequests(){
	
	$data['pagename']='Leave Requests ';
	$view = new View();
	$data['view'] = $view;
	
	
		//check if login person has team members
			$teamlead=new Employee();
			if($_SESSION["authprivilage"]=="employee")
				$teamlead=$teamlead->retrieve_one('employee_reporting_person=?',$_SESSION["employee_id"]);
			else if($_SESSION["authprivilage"]=="hr") 
				$teamlead=$teamlead->retrieve_one('employee_reporting_person=?',$_SESSION["login_id"]);
	
		//if not redirects to myteam page
			if(!$teamlead){
				redirect('myteam');
			}
			$leaveRequests=new LeaveRequest();
			// DISTINCT UPPER((leave_type)) leave_type
			$data['custom_filters_leave']=$leaveRequests->select(' DISTINCT UPPER((leave_type)) leave_type');
			$data['custom_filters_status']=$leaveRequests->select(' DISTINCT UPPER((status)) status');
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/datatables/jquery.dataTables.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.min.js').'"></script>';
	
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>';
	$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/leaveRequests-respond.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/myteam-leaveRequests.js').'"></script>';
	$data['body'][]=View::do_fetch(VIEW_PATH.'myteam/leaveRequests.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}