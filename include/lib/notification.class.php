<?php
require_once (dirname ( dirname ( __FILE__ ) ) . "/lib/database.class.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/lib/notification.class.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/lib/notifyEmail.class.php");
class Notification extends Database {
	var $notif_id;
	var $sender;
	var $receiver;
	var $action_id;
	var $notif_text;
	var $msg_id;
	var $created_on;
	var $isRead;
	var $user_id;
	var $conn;
	function __destruct() {
		mysqli_close ( $this->_connection );
	}
	
	public function insertNotifications($notif_type,$sender,$receiver,$action_id,$notif_text){
		date_default_timezone_set("Asia/Calcutta");
		$rand = mt_rand ( 10000, 9999999 );
		$notif_id= "NT" . $rand;
		$date = date("Y-m-d H:i:s");
		$stmt = mysqli_prepare ( $this->_connection,"INSERT INTO notifications (notification_id,notification_type,sender_id,receiver_id,
											action_id,notif_text,is_read,create_date) VALUES (?,?,?,?,?,?,0,?)" )or die(mysqli_error($this->_connection));
		mysqli_stmt_bind_param ( $stmt, 'sssssss',$notif_id,$notif_type,$sender,$receiver,$action_id,$notif_text,$date);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection);
		
		$stmt1 = "SELECT CONCAT(employee_name,' ',employee_lastname) employee_name FROM employee_work_details WHERE employee_id = '$sender';";
		$result1 = mysqli_query ( $this->_connection, $stmt1);
		$row = mysqli_fetch_array ( $result1, MYSQLI_ASSOC );
		$emp_name = $row['employee_name'];
		
		$query = "SELECT company_resp1_email,company_user_name,email_notify FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'";
		$result = mysqli_query ( $this->_connection, $query);
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		$to = $row['company_resp1_email'];
		$company_name = $row['company_user_name'];
		
		if($notif_type=='claimRequested'){
			$sub='CLAIM RQ';
		}elseif($notif_type=='AssetRequested'){
			$sub='ASSET RQ';
		}elseif($notif_type=='noticeRequested'){
			$sub='NOTICE RQ';
		}elseif($notif_type=='compoffRequested'){
			$sub='COMPOFF RQ';
		}
		if($row['email_notify']=='yes'){
			$mail=new NotifyEmail();
			$mail->conn = $this->_connection;
			$subject = "PRIS-NEW ".$sub." - ".$emp_name."[".$sender."]";
			$body='<p> Employee <b>'.$emp_name.'['.$sender.']</b> '.$notif_text.' on '.date('d-m-Y',strtotime($date)).'.</p><br><p><a href="pris.xyz/'.$company_name.'">Click here </a> to login to the application.</p>';
			
			$mailsentResult=$mail->_send('Hr',$to,$subject,$body,null,"form");
		}
	}
	// to get the notifications for the employee
	public function getEmployeeNotifications($employee_id, $startLimit, $endLimit) {
		$json = array ();
		$row = array ();
		$query = "SELECT CONCAT(n.notif_text,' ',CONCAT(' by Admin - ',n.sender_id)) notif_text ,
					create_date,
					CONCAT(ty.action_url,n.action_id,ty.screen_action) notif_url ,n.is_read,n.notification_id
					FROM notifications n
					INNER JOIN notification_types ty
					ON n.notification_type = ty.notification_type
					WHERE n.receiver_id = ?
					ORDER BY n.create_date DESC LIMIT ?,?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'sii', $employee_id, $startLimit, $endLimit );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $notif_text, $created_date, $action_url, $isRead, $notification_id );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['nText'] = $notif_text;
			$row ['createDate'] = $created_date;
			$row ['action'] = $action_url;
			$row ['isRead'] = $isRead;
			$row ['notification_id'] = $notification_id;
			array_push ( $json, $row );
		}
		return $json;
	}
	public function getAdminNotifications($user_id, $startLimit, $endLimit) {
		$json = array ();
		$row = array ();
		$query = "SELECT CONCAT(CONCAT( '<b>',e.employee_name,' ',e.employee_lastname,' (',e.employee_id,') </b>' ),' ',n.notif_text) notif_text ,
		       (CASE WHEN DATE_FORMAT(n.create_date,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(n.create_date,'%h:%i  %p')
		                WHEN DATE_FORMAT(n.create_date,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(n.create_date,'%b %e')
		              ELSE DATE_FORMAT(n.create_date,'%e/%c/%y')
		     END) as create_date,
		CONCAT(ty.action_url,n.action_id,ty.screen_action) notif_url ,n.is_read,n.notification_id
		FROM notifications n
		INNER JOIN notification_types ty
		ON n.notification_type = ty.notification_type
		INNER JOIN employee_work_details e
		ON n.sender_id = e.employee_id
		WHERE n.receiver_id IN ('Admin',?) ORDER BY n.create_date DESC LIMIT ?,?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'sii', $user_id, $startLimit, $endLimit );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $notif_text, $created_date, $action_url, $isRead, $notification_id );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['nText'] = $notif_text;
			$row ['createDate'] = $created_date;
			$row ['action'] = $action_url;
			$row ['isRead'] = $isRead;
			$row ['notification_id'] = $notification_id;
			array_push ( $json, $row );
		}
		return $json;
	}
	public function readNotification($notif_id) {
		$query = "update notifications SET is_read= 1 where notification_id=?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $notif_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
		return $result;
	}
	public function countNotification($user_id) {
		$json = array ();
		$row = array ();
		$query = "SELECT COUNT(n.notification_id) as count
		FROM notifications n
		INNER JOIN notification_types ty
		ON n.notification_type = ty.notification_type
		INNER JOIN employee_work_details e
		ON n.sender_id = e.employee_id
		WHERE n.receiver_id IN ('Admin',?) ORDER BY n.create_date";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $user_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $countAdmin );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['countAdmin'] = $countAdmin;
			array_push ( $json, $row );
		}
		return $json;
	}
	public function countNotificationEmployee($employee_id) {
		$json = array ();
		$row = array ();
		$query = "SELECT COUNT(n.notification_id) as count
		FROM notifications n
		INNER JOIN notification_types ty
		ON n.notification_type = ty.notification_type
		INNER JOIN employee_work_details e
		ON n.sender_id = e.employee_id
		WHERE n.receiver_id = ? ORDER BY n.create_date";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $employee_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $countEmp );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['countEmp'] = $countEmp;
			array_push ( $json, $row );
		}
		return $json;
	}
	public function deleteNotification($notification_id) {
		$notification_id = str_replace ( ",", "\",\"", $notification_id );
		$notification_id = "\"" . $notification_id . "\"";
		$query = "DELETE r.* from notifications r where r.notification_id IN ({$notification_id})";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
		return $result;
	}
}
?>