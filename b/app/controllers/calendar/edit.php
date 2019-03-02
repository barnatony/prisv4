<?php
function _edit(){
	$data['pagename']='Edit';
	$view = new View();
	$data['view'] = $view;
    $event=new Event();
    
    //$data['events']=$event=$event->joined_select('e.id,e.category,e.branch_id,cb.branch_name,e.title,e.start_date,e.end_date,e.enabled,e.updated_on,e.updated_by ','holidays_event e INNER JOIN company_branch cb ON cb.branch_id = e.branch_id');
    $query="SELECT * FROM company_branch";
    $dbh = getdbh();
    $stmt=$dbh->query($query);
    if(!$stmt){
    	print_r($dbh->errorInfo());
    }
    $data['branches']=$branches =$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    
    
    
	$data['head'][]='<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/fullcalendar/fullcalendar.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/chosen/chosen-bootstrap.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/chosen/chosen.css').'">';
	
	$data['foot'][]="<script src='https://code.jquery.com/ui/1.11.4/jquery-ui.min.js'></script>";
	$data['foot'][]="<script src='".myUrl('js/plugins/fullcalendar/moment.min.js')."'></script>";
	$data['foot'][]='<script src="'.myUrl('js/plugins/fullcalendar/fullcalendar.min.js').'"></script>';
	$data['foot'][]="<script src='".myUrl('js/plugins/fullcalendar/gcal.min.js')."'></script>";	
	$data['foot'][]="<script src='".myUrl('js/plugins/chosen/chosen.jquery.min.js')."'></script>";

	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]="<script src='".myUrl('js/pages/calendar-index.js')."'></script>";
	$data['body'][]=View::do_fetch(VIEW_PATH.'calendar/edit.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}	
?>