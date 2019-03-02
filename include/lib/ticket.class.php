<?php
require_once (__DIR__ ."/database.class.php");
class Ticket extends Database {
	var $ticket_id;
	var $company_id;
	var $creator_id;
	var $category;
	var $subject;
	var $attachment;
	var $description;
	var $priority;
	var $status;
	var $created_on;
	var $last_updated_on;
	var $isMaster;
	var $conn;
	var $formattachment;
	var $filePath ;
	var $updatedon;
	var $title;
	function __destruct() {
		mysqli_close ( $this->_connection );
	}
    function __construct(){
    	$date= date_default_timezone_set("Asia/Calcutta");
    	parent::__construct();
    }
	public function raiseTicket($category, $subject, $desc, $attachment, $priority) {
		$this->formattachment=$attachment;
		$rand = mt_rand ( 100, 9999999 );
		$ticketid = "TK" . $rand;
		if (! empty ( $attachment ["name"] )) {
			$target_dir = ((dirname ( dirname ( dirname ( __FILE__ ) ) )) . "/compDat/Tickets/$ticketid");
			
			if (! file_exists ( $target_dir )) {
				mkdir ( $target_dir );
			}
			
			$temp1 = md5 ( $attachment ["name"] ) . time ();
			$temp = explode ( ".", $attachment ["name"] );
			$extension = end ( $temp );
			$fullFilePath=  dirname ( (dirname ( dirname ( __FILE__ ) )) ) . "/compDat/Tickets/" . $ticketid . "/" . $temp1 .".". $extension;
			$this->filePath = $fullFilePath;
			$ext = move_uploaded_file ( $attachment ["tmp_name"], dirname ( (dirname ( dirname ( __FILE__ ) )) ) . "/compDat/Tickets/" . $ticketid . "/" . $temp1 .".". $extension );
			$newfilename_ = "../compDat/Tickets/" . $ticketid . "/" . $temp1 .".". $extension;
		} else {
			$newfilename_ = "Nil";
		}
		$attachment = $newfilename_;
		$this->category = $category;
		$this->subject=$subject;
		$this->description=$desc;
		$this->attachment=$attachment;
		$this->priority=$priority;
		$this->ticket_id=$ticketid;
		
		$stmt = mysqli_prepare ( $this->_connection, "INSERT INTO "  . MASTER_DB_NAME . ".tickets(ticket_id,company_id,creator_id,category,subject,description,attachment,priority,updated_on) 
													VALUES (?,?,?,?,?,?,?,?,NOW())" );
		
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $ticketid, $this->company_id, $this->creator_id, $category, $subject, $desc, $attachment, $priority );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_errno ( $this->_connection );
		self::emailTicket();
		return $result;
	}
	public function emailTicket(){
		require_once (__DIR__ . "/notifyEmail.class.php");
		$mail=new NotifyEmail();
		$mail->conn = $this->_connection;
		$subject = $this->ticket_id."-".$this->subject."-".$this->priority;
		$body='<table style="width:100% border-bottom:1pt solid black">
			   <tr><td>Category:</td> <td id="borderSet">' .$this->category. '</td></tr>
			    <tr><td>Description:</td><td id="borderSet">' .$this->description. '</td></tr></table>';
		
		$mailsentResult=$mail->_send('Gowtham', 'gowtham@basstechs.com' , $subject,$body,array($this->filePath),array($this->formattachment["name"]),null,array(array("email"=>"rufus@basstechs.com","name"=>"Rufus Jackson")),"form");
	
	}
	public function viewTickets($start, $end) {
		$json = array ();
		$row = array ();
		$query = "SELECT ticket_id,company_id,CAP_FIRST(creator_id) creator_id,category,subject,priority,status,created_on,updated_on,company_name,
  						IF(status='CLOSED',CONCAT(SUBSTRING_INDEX(TIMEDIFF(updated_dt,created_dt),':',2),' Hrs'),'-') time_taken
				  FROM (
					SELECT t.ticket_id,t.company_id,t.creator_id,t.category,t.subject,t.priority,t.status,created_on created_dt,t.updated_on updated_dt,
					(CASE WHEN DATE_FORMAT(t.created_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.created_on,'%h:%i  %p') 
						  WHEN DATE_FORMAT(t.created_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.created_on,'%e %b %Y  %h:%i %p') 
					    ELSE DATE_FORMAT(t.created_on,'%e/%c/%y  %h:%i %p') END) AS created_on,
					(CASE WHEN DATE_FORMAT(t.updated_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.updated_on,'%h:%i  %p')
						  WHEN DATE_FORMAT(t.updated_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.updated_on,'%e %b %Y %h:%i %p')
					    ELSE DATE_FORMAT(t.updated_on,'%e/%c/%y  %h:%i %p') END) AS updated_on,cd.company_name
					FROM "  . MASTER_DB_NAME . ".tickets t
					INNER JOIN company_details cd ON t.company_id = cd.company_id AND info_flag='A'
					WHERE t.company_id = ? -- AND t.creator_id = ?
					ORDER BY t.updated_on DESC,t.created_on DESC LIMIT 0,50) z";
		
		$stmt = mysqli_prepare ( $this->_connection, $query );
		
		//mysqli_stmt_bind_param ( $stmt, 'ss', $this->company_id, $this->creator_id );
		mysqli_stmt_bind_param ( $stmt, 's', $this->company_id);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		
		mysqli_stmt_bind_result ( $stmt, $ticket_id, $company_id, $creator_id, $category, $subject, $priority, $status, $created_on, $lastUpdated, $company_name,$time_taken);
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			
			$row ['ticketId'] = $ticket_id;
			$row ['companyId'] = $company_id;
			$row ['creatorId'] = $creator_id;
			$row ['category'] = $category;
			$row ['subject'] = $subject;
			$row ['priority'] = $priority;
			$row ['status'] = $status;
			$row ['createdOn'] = $created_on;
			$row ['lastUpdated'] = $lastUpdated;
			$row ['company_name'] = $company_name;
			$row ['time_taken'] = $time_taken;
			array_push ( $json, $row );
		}
		
		return $json;
	}
	public function viewTicket($ticket_id) {
		$json = array ();
		$row = array ();
		if ($this->isMaster) {
			$query = "SELECT t.ticket_id conver_id,t.ticket_id,t.company_id,CAP_FIRST(t.creator_id) creator_id,'Administrator' as receiver,t.category,t.subject,
t.description,t.attachment, t.priority,t.status,
(CASE WHEN DATE_FORMAT(t.created_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.created_on,'%h:%i  %p') 
WHEN DATE_FORMAT(t.created_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.created_on,'%e %b %Y  %h:%i %p') 
ELSE DATE_FORMAT(t.created_on,'%e/%c/%y  %h:%i %p') END) AS created_on,

(CASE WHEN DATE_FORMAT(t.updated_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.updated_on,'%h:%i  %p')
WHEN DATE_FORMAT(t.updated_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.updated_on,'%e %b %Y %h:%i %p')
ELSE DATE_FORMAT(t.updated_on,'%e/%c/%y  %h:%i %p') END) AS updated_on,cd.company_name
FROM "  . MASTER_DB_NAME . ".tickets t
           INNER JOIN company_details cd ON t.company_id = cd.company_id AND info_flag='A'
WHERE t.ticket_id = ?
UNION 
SELECT r.reply_id,t.ticket_id,t.company_id,CAP_FIRST(r.sender_id) sender_id,r.receiver_id as receiver,t.category,t.subject,r.description,
r.attachment,null,r.status_change,null,
(CASE WHEN DATE_FORMAT(t.updated_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.updated_on,'%h:%i  %p') 
WHEN DATE_FORMAT(t.updated_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.updated_on,'%e %b %Y  %h:%i %p')
ELSE DATE_FORMAT(t.updated_on,'%e/%c/%y  %h:%i %p') END) AS updated_on,cd.company_name
FROM ". MASTER_DB_NAME . ".ticket_replies r
INNER JOIN "  . MASTER_DB_NAME . ".tickets t
           ON r.ticket_id = t.ticket_id
           INNER JOIN company_details cd ON t.company_id = cd.company_id AND info_flag='A'
WHERE r.ticket_id = ?";
			$stmt = mysqli_prepare ( $this->_connection, $query );
			mysqli_stmt_bind_param ( $stmt, 'ss', $ticket_id, $ticket_id );
		} else {
			$query = " SELECT t.ticket_id conver_id,t.ticket_id,t.company_id,CAP_FIRST(t.creator_id) creator_id,'Administrator' as receiver,t.category,
       t.subject,t.description,t.attachment,t.priority,t.status,(CASE WHEN DATE_FORMAT(t.created_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.created_on,'%h:%i  %p') 
WHEN DATE_FORMAT(t.created_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.created_on,'%e %b %Y  %h:%i %p') 
ELSE DATE_FORMAT(t.created_on,'%e/%c/%y  %h:%i %p') END) AS created_on,

(CASE WHEN DATE_FORMAT(t.updated_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.updated_on,'%h:%i  %p')
WHEN DATE_FORMAT(t.updated_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.updated_on,'%e %b %Y %h:%i %p')
ELSE DATE_FORMAT(t.updated_on,'%e/%c/%y  %h:%i %p') END) AS updated_on,cd.company_name
                        FROM "  . MASTER_DB_NAME . ".tickets t
					INNER JOIN company_details cd ON t.company_id = cd.company_id AND info_flag='A'
                        WHERE t.ticket_id = ? AND t.company_id = ?
                        UNION
                        SELECT r.reply_id,t.ticket_id,t.company_id,CAP_FIRST(r.sender_id) sender_id,r.receiver_id as receiver,t.category,t.subject,r.description,r.attachment,null,r.status_change,null,(CASE WHEN DATE_FORMAT(t.updated_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(t.updated_on,'%h:%i  %p')
WHEN DATE_FORMAT(t.updated_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(t.updated_on,'%e %b %Y %h:%i %p')
ELSE DATE_FORMAT(t.updated_on,'%e/%c/%y  %h:%i %p') END) AS updated_on,cd.company_name
                        FROM ". MASTER_DB_NAME . ".ticket_replies r
                        INNER JOIN "  . MASTER_DB_NAME . ".tickets t
                        ON r.ticket_id = t.ticket_id
						INNER JOIN company_details cd ON t.company_id = cd.company_id AND info_flag='A'
                        WHERE t.company_id = ? AND r.ticket_id = ?";
			$stmt = mysqli_prepare ( $this->_connection, $query );
			mysqli_stmt_bind_param ( $stmt, 'ssss', $ticket_id, $this->company_id, $this->company_id, $ticket_id );
		}
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $conver_id, $ticket_id, $company_id, $creator_id, $receiver, $category, $subject, $description, $attachment, $priority, $status, $created_on, $lastUpdated, $companyName );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['converId'] = $conver_id;
			$row ['ticketId'] = $ticket_id;
			$row ['companyId'] = $company_id;
			$row ['creatorId'] = $creator_id;
			$row ['receiver'] = $receiver;
			$row ['category'] = $category;
			$row ['subject'] = $subject;
			$row ['desc'] = $description;
			$row ['attach'] = $attachment;
			$row ['priority'] = $priority;
			$row ['status'] = $status;
			$row ['createdOn'] = $created_on;
			$row ['lastUpdated'] = $lastUpdated;
			$row ['companyName'] = $companyName;
			array_push ( $json, $row );
		}
		
		$reOpenquery = "SELECT created_on,updated_on,IF(TIMEDIFF(NOW(),created_on)>'24:00:00','0',TIMEDIFF(updated_on,created_on)) reopen_time
				   		FROM ". MASTER_DB_NAME . ".tickets WHERE ticket_id='$ticket_id' AND company_id='$this->company_id' AND status='CLOSED'";
		$reOpenstmt= mysqli_query( $this->_connection,$reOpenquery);
		$result = mysqli_fetch_array ( $reOpenstmt, MYSQLI_ASSOC );
		$row ['reopen_time'] = $result['reopen_time'];
		//if($time!='')
		array_push ( $json, (isset($row ['reopen_time'])?$row ['reopen_time']:0));
			
		return $json;
	}
	public function replyTicket($ticket_id, $receiver_id, $description, $attachment, $status_change) {
		$rand = mt_rand ( 100, 9999999 );
		$this->formattachment=$attachment;
		$ticketid = "RE" . $rand;
		if (! empty ( $attachment ["name"] )) {
				
			$target_dir = ((dirname ( dirname ( dirname ( __FILE__ ) ) )) . "/compDat/Tickets/$ticket_id");
				
			if (! file_exists ( $target_dir )) {
				mkdir ( $target_dir );
			}
			$temp1 = md5 ( $attachment ["name"] ) . time ();
			$temp = explode ( ".", $attachment ["name"] );
			$extension = end ( $temp );
			$fullFilePath=  dirname ( (dirname ( dirname ( __FILE__ ) )) ) . "/compDat/Tickets/" . $ticket_id . "/" . $temp1 .".". $extension;
			$this->filePath = $fullFilePath;
			$ext = move_uploaded_file ( $attachment ["tmp_name"], $fullFilePath);
			$newfilename_ = "../compDat/Tickets/" . $ticket_id . "/" . $temp1 .".". $extension;
		} else {
			$newfilename_ = "Nil";
		}
		$attachment = $newfilename_;
		
		
		if ($this->isMaster) {
			
			$query = "INSERT INTO ". MASTER_DB_NAME . ".ticket_replies(reply_id,ticket_id,sender_id,receiver_id,description,attachment,status_change) 
					VALUES (?,?,?,?,?,?,?)";
			$stmt = mysqli_prepare ( $this->_connection, $query );
			mysqli_stmt_bind_param ( $stmt, 'sssssss', $ticketid, $ticket_id, $this->creator_id, $receiver_id, $description, $attachmentName, $status_change );
		
		} else {
			$query = "INSERT INTO ". MASTER_DB_NAME . ".ticket_replies(reply_id,ticket_id,sender_id,receiver_id,description,attachment) VALUES (?,?,?,?,?,?)";
			$stmt = mysqli_prepare ( $this->_connection, $query );
			mysqli_stmt_bind_param ( $stmt, 'ssssss', $ticketid, $ticket_id, $this->creator_id, $receiver_id, $description, $attachmentName );
		}
		
		$result[0] = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		
		$this->ticket_id=$ticket_id;
		$this->priority= $this->category="";
		$this->subject="";
		$this->description=$description;
		$this->attachment=$attachment;
		$result[1]=date("h:i a ",time());
		//self::emailTicket();
		return $result;
	}
	function getTicketsbyDuration($from,$to){
		$this->from=$from;
		$this->to=$to;
		$a_json=array();
		
		if($this->isMaster){
		$query="SELECT t.ticket_id, c.company_name , t.subject,t.category,t.priority,t.status ,t.created_on,t.updated_on, time_to_sec(timediff(t.updated_on , t.created_on )) / 3600 duration
                    FROM "  . MASTER_DB_NAME . ".tickets t
                    LEFT JOIN company_details c
                    ON t.company_id = c.company_id
                    WHERE t.created_on
                    BETWEEN STR_TO_DATE('$from','%d/%m/%Y') AND STR_TO_DATE('$to','%d/%m/%Y')";
		$result=mysqli_query($this->_connection,$query) or  die(mysqli_error($this->_connection));
		while ( $row = mysqli_fetch_array ($result, MYSQLI_ASSOC ) ) {
			    
		    	array_push ( $a_json, $row );
		    	
		    }
		    
		    return $a_json;
		}	
		    }
	
	
}