<?php
function _index(){
	
	$data['pagename']='Attendance View';
	$view = new View();
	$data['view'] = $view;
	
	$data['for']="me";
	$data['teamleads']="";
	$dbh = getdbh();
	$stmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
  							FROM company_details WHERE info_flag="A" AND company_id=:company_id');
	$stmt->bindParam('company_id', $_SESSION['company_id']);
	$stmt->execute();
	$companyProp = $stmt->fetch();
	
	//to get currentDate
	$today= date("Y-m-d");
	
	$monthend=$_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']);
	if($companyProp['attendance_dt'] !=1 && $today >= $monthend){
		$data["selectedMonth"]=$_SESSION['payrollYear']."-".($_SESSION['monthNo']+1);
	}else{	
		$data["selectedMonth"]=$_SESSION['payrollYear']."-".($_SESSION['monthNo']);
	}
	
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/datatables/jquery.dataTables.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/jquery-auto-complete/jquery.auto-complete.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/fullcalendar/fullcalendar.min.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/core/bootstrap.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js")></script>';
	$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js")></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js")></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/jquery-auto-complete/jquery.auto-complete.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/slimScroll/jquery.scroll_js.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/slimScroll/jquery.nicescroll.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/fullcalendar/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/fullcalendar/fullcalendar.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/fullcalendar/gcal.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-core.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-full.min.js"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/dust/dust-helpers.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/attendance-index.js').'"></script>';
	$data['body'][]=View::do_fetch(VIEW_PATH.'attendance/index.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
	
}