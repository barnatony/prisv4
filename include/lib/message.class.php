<?php
require_once (dirname ( dirname ( __FILE__ ) ) . "/lib/database.class.php");
class Message extends Database {
	var $sender;
	var $receiver;
	var $subject;
	var $body;
	var $msg_id;
	var $label;
	var $recipentgroupid;
	var $parent_message_id;
	var $created_on;
	var $isRead;
	function __destruct() {
		mysqli_close ( $this->_connection );
	}
	public function sendMessage($recivers, $subject, $body, $parent_msg_id, $label) {
		$rand = mt_rand ( 10000, 9999999 );
		$this->msg_id = "MS" . $rand;
		$queryStmt = "insert into message(msg_id,subject,creator_id,msg_body,parent_msg_id,label) values(?,?,?,?,?,?)";
		$stmt = mysqli_prepare ( $this->_connection, $queryStmt );
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $this->msg_id, $subject, $this->sender, $body, $parent_msg_id, $label );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		if ($result) {
			foreach ( $recivers as $receiver ) {
				
				$stmt = mysqli_prepare ( $this->_connection, "insert into message_recipient(recipient_id,recipent_group_id,msg_id) values(?,?,?)" );
				mysqli_stmt_bind_param ( $stmt, 'sss', $receiver, $this->recipentgroupid, $this->msg_id );
				$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
			}
		}
		return $result;
	}
	public function replyMessage($recivers, $subject, $body, $parent_msg_id, $label) {
		$rand = mt_rand ( 10000, 9999999 );
		$msg_id = "RE" . $rand;
		$subject = "Re : " . $subject;
		$stmt = mysqli_prepare ( $this->_connection, "insert into message(msg_id,subject,creator_id,msg_body,parent_msg_id,label) values(?,?,?,?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $msg_id, $subject, $this->sender, $body, $parent_msg_id, $label );
		
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_errno ( $this->_connection );
		if ($result) {
			foreach ( $recivers as $receiver ) {
				
				$stmt = mysqli_prepare ( $this->_connection, "insert into message_recipient(recipient_id,recipent_group_id,msg_id) values(?,?,?)" );
				mysqli_stmt_bind_param ( $stmt, 'sss', $receiver, $this->recipentgroupid, $msg_id );
				$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_errno ( $this->_connection );
			}
		}
		return $result;
	}
	/*
	 * public function getUnreadMessages($rece_userid){
	 * $json = array();
	 * $row = array();
	 * $query = "SELECT t2.first_name,t1.subject,t1.msg_body,t2.recipient_id,t1.msg_id,t2.create_date,t1.creator_id,t1.parent_msg_id FROM message t1
	 * JOIN (
	 * SELECT c.first_name,m.creator_id,COUNT(*) as numberOfMsgs,r.recipient_id,MAX(m.create_date) create_date from message_recipient r
	 * INNER JOIN message m
	 * ON r.msg_id = m.msg_id
	 * INNER JOIN user c
	 * ON m.creator_id = c.user_id
	 * WHERE r.is_read='0' and r.recipient_id=?
	 * GROUP BY m.creator_id
	 * ) t2
	 * ON t1.creator_id = t2.creator_id AND t1.create_date = t2.create_date;";
	 * $stmt = mysqli_prepare($this->_connection, $query);
	 * mysqli_stmt_bind_param($stmt,'s',$rece_userid);
	 * $result = mysqli_stmt_execute($stmt)?TRUE:mysqli_error($this->_connection);
	 * mysqli_stmt_bind_result($stmt,$firstname,$subject,$body,$receipt,$msg,$create_date,$creator_id,$parent_msg_id);
	 * while(mysqli_stmt_fetch($stmt))
	 * {
	 *
	 *
	 * $row['first_name'] = $firstname;
	 * $row['subject'] = $subject;
	 * $row['msg_body'] = $body;
	 * $row['recipient_id'] = $receipt;
	 * $row['msg_id'] = $msg;
	 * $row['create_date'] = $create_date;
	 * $row['creator_id'] = $creator_id;
	 * $row['parent_msg_id'] = $parent_msg_id;
	 * array_push($json, $row);
	 * }
	 * return $json;
	 * }
	 */
	// to get the notification for the user
	public function getMessageNotification($user_id, $startLimit, $endLimit) {
		$json = array ();
		$row = array ();
		$query = "SELECT r.msg_id,m.parent_msg_id,(CASE WHEN c.employee_name IS NULL THEN CONCAT('Administrator','- ',m.creator_id)
          ELSE CONCAT(c.employee_name,' ',c.employee_lastname)
          END) as creator_name,
          m.creator_id ,
          m.subject,
          m.msg_body,
          m.create_date,
          r.is_read,
          r.recipient_id,
		  m.label
          from message_recipient r 
    INNER JOIN message m 
    ON r.msg_id = m.msg_id
    LEFT JOIN employee_work_details c
    ON m.creator_id = c.employee_id
    WHERE r.recipient_id=?
    ORDER BY m.create_date DESC LIMIT ?,?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'sii', $user_id, $startLimit, $endLimit );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $msg_id, $parent_id, $creator_name, $creator_id, $subject, $msg_body, $create_date, $is_read, $recipient_id, $label );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			
			$row ['messageId'] = $msg_id;
			$row ['parentMessageId'] = $parent_id;
			$row ['senderName'] = $creator_name;
			$row ['senderId'] = $creator_id;
			$row ['subject'] = $subject;
			$row ['body'] = $msg_body;
			$row ['sentOn'] = $create_date;
			$row ['isRead'] = $is_read;
			$row ['recieverId'] = $recipient_id;
			$row ['label'] = $label;
			array_push ( $json, $row );
		}
		return $json;
	}
	// get sent mails
	public function getSentMails($user_id, $startLimit, $endLimit) {
		$json = array ();
		$row = array ();
		$query = "SELECT r.msg_id,m.parent_msg_id,(CASE WHEN c.employee_name IS NULL THEN 'Administrator'
          ELSE CONCAT(c.employee_name,' ',c.employee_lastname)
          END) as creator_name,
          m.creator_id ,
          m.subject,
          m.msg_body,
          m.create_date,
          r.is_read,
		  r.read_on,
          r.recipient_id,
		  m.label
          from message_recipient r
    INNER JOIN message m
    ON r.msg_id = m.msg_id
    LEFT JOIN employee_work_details c
    ON m.creator_id = c.employee_id
    WHERE m.creator_id=?
    ORDER BY m.create_date DESC LIMIT ?,?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'sii', $user_id, $startLimit, $endLimit );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $msg_id, $parent_id, $creator_name, $creator_id, $subject, $msg_body, $create_date, $is_read, $read_on, $recipient_id, $label );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			
			$row ['messageId'] = $msg_id;
			$row ['parentMessageId'] = $parent_id;
			$row ['senderName'] = $creator_name;
			$row ['senderId'] = $creator_id;
			$row ['subject'] = $subject;
			$row ['body'] = $msg_body;
			$row ['sentOn'] = $create_date;
			$row ['isRead'] = $is_read;
			$row ['readOn'] = $read_on;
			$row ['recieverId'] = $recipient_id;
			$row ['label'] = $label;
			array_push ( $json, $row );
		}
		return $json;
	}
	// To get individual message conversation of a user
	public function getMessage($user_id, $recipient_id, $message_id) {
		$json = array ();
		$row = array ();
		$query = "SELECT r.msg_id,m.parent_msg_id, (CASE WHEN c.employee_name IS NULL THEN CONCAT('Administrator','- ',m.creator_id)
          ELSE CONCAT(c.employee_name,' ',c.employee_lastname)
          END) as creator_name,
          m.creator_id ,
          m.subject,
          m.msg_body as body,
          m.create_date,
          r.is_read,
          r.recipient_id,
		  m.label,
          (CASE WHEN rec.employee_name IS NULL THEN CONCAT('Administrator','- ',r.recipient_id)
          ELSE CONCAT(rec.employee_name,' ',rec.employee_lastname)
          END) as reciver_name
          FROM message_recipient r 
    INNER JOIN message m 
    ON r.msg_id = m.msg_id
    LEFT JOIN employee_work_details c
    ON m.creator_id = c.employee_id
    LEFT JOIN employee_work_details rec
    ON r.recipient_id= rec.employee_id
    WHERE ((m.creator_id = ? AND r.recipient_id= ?) OR (r.recipient_id= ? AND m.creator_id = ?)) AND (m.msg_id = ? OR m.parent_msg_id = ?) 
    ORDER BY m.create_date ASC";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $user_id, $recipient_id, $user_id, $recipient_id, $message_id, $message_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $msg_id, $parent_id, $creator_name, $creator_id, $subject, $msg_body, $create_date, $is_read, $recipient_id, $label, $reciver_name );
		$message_ids = "";
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			
			$row ['messageId'] = $msg_id;
			$row ['parentMessageId'] = $parent_id;
			$row ['senderName'] = $creator_name;
			$row ['senderId'] = $creator_id;
			$row ['subject'] = $subject;
			$row ['body'] = $msg_body;
			$row ['sentOn'] = $create_date;
			$row ['isRead'] = $is_read;
			$row ['recieverId'] = $recipient_id;
			$row ['label'] = $label;
			$row ['receiverName'] = $reciver_name;
			if ($is_read == 0) {
				$message_ids .= $msg_id . ",";
				$row ['isRead'] = 1;
			}
			array_push ( $json, $row );
		}
		$message_ids = rtrim ( $message_ids, "," );
		
		mysqli_stmt_free_result ( $stmt );
		if ($message_ids != "") {
			$result = $this->readMessage ( $message_ids, $user_id );
		}
		return $json;
	}
	public function toUsers($sender) {
		$json = array ();
		$row = array ();
		$query = "SELECT w.employee_id user_id,CONCAT (w.employee_name ,' ',w.employee_lastname ) user_name,d.designation_name,dep.department_name FROM employee_work_details w
			INNER JOIN company_designations d ON w.designation_id = d.designation_id 
			INNER JOIN company_departments dep ON w.department_id = dep.department_id
			WHERE w.enabled = 1 AND w.employee_id != ?
			UNION 
			SELECT a.user_name user_id,'Administrator' user_name, NULL,NULL FROM company_login_details a
			WHERE a.user_name != ?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'ss', $sender, $sender );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $user_id, $user_name, $designation_name, $department_name );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['user_id'] = $user_id;
			$row ['user_name'] = $user_name;
			$row ['designation'] = $designation_name;
			$row ['department'] = $department_name;
			array_push ( $json, $row );
		}
		mysqli_stmt_free_result ( $stmt );
		return $json;
	}
	/*
	 * public function getUnreadMessagesshowLatest($user_id){
	 * $json = array();
	 * $row = array();
	 * $query = "select c.recipient_id,d.msg_body,d.subject,d.msg_id,d.create_date,d.creator_id FROM message as d LEFT JOIN message_recipient as c on d.msg_id=c.msg_id WHERE d.parent_msg_id=?";
	 * $stmt = mysqli_prepare($this->_connection, $query);
	 * mysqli_stmt_bind_param($stmt,'s',$user_id);
	 * $result = mysqli_stmt_execute($stmt)?TRUE:mysqli_error($this->_connection);
	 * mysqli_stmt_bind_result($stmt,$creator_id,$body,$subject,$msg_id,$create_date,$creator_id);
	 * while(mysqli_stmt_fetch($stmt))
	 * {
	 * $row['recipient_id'] = $creator_id;
	 * $row['msg_body'] = $body;
	 * $row['subject'] = $subject;
	 * $row['msg_id'] = $msg_id;
	 * $row['create_date'] = $create_date;
	 * $row['creator_id'] = $creator_id;
	 * array_push($json, $row);
	 * }
	 * return $json;
	 * }
	 */
	public function readMessage($msgids, $receiver) {
		$msgids = str_replace ( ",", "\",\"", $msgids );
		$msgids = "\"" . $msgids . "\"";
		$query = "update message_recipient SET is_read= 1 where msg_id IN ({$msgids}) and recipient_id=?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $receiver );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
		return $result;
	}
	public function unreadMessage($msgids, $receiver) {
		$msgids = str_replace ( ",", "\",\"", $msgids );
		$msgids = "\"" . $msgids . "\"";
		$query = "update message_recipient SET is_read=0 where msg_id IN ({$msgids}) and recipient_id=?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $receiver );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
		return $result;
	}
	public function deleteMessage($msgids, $receiver) {
		$msgids = str_replace ( ",", "\",\"", $msgids );
		$msgids = "\"" . $msgids . "\"";
		$query = "DELETE r.*,c.* from message_recipient r INNER JOIN message c ON r.msg_id = c.msg_id where c.msg_id IN ({$msgids}) and r.recipient_id=?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $receiver );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
		return $result;
	}
	public function getTotalCount($receiver) {
		$json = array ();
		$row = array ();
		$query = "SELECT count(r.msg_id) as counts from message_recipient r INNER JOIN message m ON r.msg_id = m.msg_id WHERE r.recipient_id=?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $receiver );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $counts );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['msg_id'] = $counts;
			array_push ( $json, $row );
		}
		return $json;
	}
	public function deleteSendMessage($msgids, $sender) {
		$msgids = str_replace ( ",", "\",\"", $msgids );
		$msgids = "\"" . $msgids . "\"";
		$query = "DELETE r.*,c.* from message_recipient r INNER JOIN message c ON r.msg_id = c.msg_id where c.msg_id IN ({$msgids}) and c.creator_id=?";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $sender );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
		return $result;
	}
}
?>