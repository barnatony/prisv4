<?php
function _regularization(){
	$data['pagename']='Myteam Regularization ';
	$view = new View();
	$data['view'] = $view;
	
	
	//check if login person has team members
			$data["privilage"]="team_lead";	
			$teamlead=new Employee();
			if($_SESSION["authprivilage"]=="employee")
				$teamlead=$teamlead->retrieve_one('employee_reporting_person=?',$_SESSION["employee_id"]);
			else if($_SESSION["authprivilage"]=="hr") 
				$teamlead=$teamlead->retrieve_one('employee_reporting_person=?',$_SESSION["login_id"]);
	
		//if not redirects to myteam page
			if(!$teamlead){
				redirect('myteam');
			}
	
	
	
			$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/datatables/jquery.dataTables.min.css').'">';
			$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
			$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
			$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
			$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.min.js').'"></script>';
			$data['foot'][]='<script src="'.myUrl('js/pages/attendance-respond.js').'"></script>';
			$data['foot'][]='<script src="'.myUrl('js/pages/myteam-regularization.js').'"></script>';
			$data['body'][]=View::do_fetch(VIEW_PATH.'myteam/regularization.php',$data);
			View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}

