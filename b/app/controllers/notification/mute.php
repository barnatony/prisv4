<?php
function _mute(){
	//required parameters notification_type,enable =1,0
	require_login("user");
	$result = array(false,"rowCount"=>0,"info"=>"");
	$user_id = $_SESSION["authuid"];
	$notification_type=$_REQUEST["notification_type"];
	$enable=$_REQUEST["enable"];
	
	
	//check whether the notification type is present in the table or not
	$notification = new Notification();
	$notification = $notification->retrieve_one("notification_type = ? AND notification_type != ?",array($notification_type,'core'));
	$created_by = $_SESSION["authname"];
	
	if(!$notification){
		$result["Info"] = "No notification found.";
		echo json_encode($result);
		die();
	}
	$dbh = getdbh();
	if(!$enable){ //they wants to get get notified the value passed is 0 
		$stmt = $dbh->prepare("DELETE FROM notification_exclusions WHERE user_id = :user_id AND notification_type= :notification_type");
		$stmt->bindParam('user_id', $user_id);
		$stmt->bindParam('notification_type',$notification_type);
	}else{ 
		//the value passed is 1
		//delete from notification_exclusions where userID and notification_id
		//insert record to notification_exclusions
		
		$stmt = $dbh->prepare("INSERT INTO notification_exclusions (user_id,notification_type,created_by) VALUES (:user_id,:notification_type,:created_by)");
		$stmt->bindParam('user_id', $user_id);
		$stmt->bindParam('notification_type',$notification_type);
		$stmt->bindParam('created_by', $created_by);
	}
	if(!$stmt->execute())
		$result["info"] =$stmt->errorInfo()[2];
	else{
		$result[0] = true;
		$result["rowCount"] = $stmt->rowCount();
	}
	
	echo json_encode($result);
	die();
}