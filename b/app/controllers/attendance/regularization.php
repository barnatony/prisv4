<?php
function _regularization(){
	$data['pagename']='Attendance Regularization ';
	$view = new View();
	$data['view'] = $view;
	
	
	
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/datatables/jquery.dataTables.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-core.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-full.min.js"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/dust/dust-helpers.js').'"></script>';
	$data['foot'][]="<script src='https://momentjs.com/downloads/moment.js'></script>";
	$data['foot'][]='<script src="'.myUrl('js/pages/attendance-respond.js').'"></script>';
	if($_SESSION["authprivilage"]=="hr"){
		$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>';
		$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>';
		$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>';
	}
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/attendance-regularization.js').'"></script>';
	$data['body'][]=View::do_fetch(VIEW_PATH.'attendance/regularization.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}

