<?php
function _edit() { 
	require_login("User");
	$data['pagename']='Profile Edit';
	$view = new View();
	$data['view'] = $view;
	
	if(isLogged('User')||isLogged('Admin'))
	$data["user"]=$user= new User($_SESSION['authuid']);
	

	//get the notifications here to display the options to turn of preferences
	$dbh = getdbh();
	$query = "SELECT n.notification_type ,n.name,n.description,
							(CASE
							  WHEN e.user_id IS NULL THEN 1
							  ELSE 0 END) status
							FROM notifications n
							LEFT JOIN
							notification_exclusions e
							ON n.notification_type = e.notification_type  AND e.user_id = :user_id
							WHERE n.notification_type != 'core'";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam("user_id", $_SESSION["authuid"]);
	$stmt->execute();
	$notifications = array();
	while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$notifications[]=$rs;
	};
	$data["notifications"] = $notifications;
    $data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
    $data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/img-crop/croppic.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/img-crop/croppic.js').'"></script>';
	$data['foot'][]="<script src='".myUrl('js/pages/profile-edit.js')."'></script>";
	$data['body'][]=View::do_fetch(VIEW_PATH.'profile/edit.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}
//if notifications are 0 turn the switch off when the switch is toggled make a call
?>