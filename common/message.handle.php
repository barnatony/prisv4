<?php
/*
 * ----------------------------------------------------------
 * Filename : message.handle.php
 * Classname: message.class.php
 * Author : Rufus Jackson
 * Database : message,message_recipient
 * Oper : Message Send,Notifications
 *
 * ----------------------------------------------------------
 */
/* Include Class Library */
include_once ((dirname ( dirname ( __FILE__ ) )) . "/include/config.php");
require_once (dirname ( dirname ( (__FILE__) ) ) . "/include/lib/message.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
// $action = $_REQUEST['act'];
$resultObj = array ();

/* Operations to be performed */
/* Setting Variables */
$message = new Message ();
if ($_SESSION ['role'] == 'HR') {
	$login_id = $_SESSION ['login_id'];
} else if ($_SESSION ['role'] == 'EMPLOYEE') {
	$login_id = $_SESSION ['employee_id'];
}
$message->sender = $login_id;

switch ($action) {
	case "sendMessage" :
		$recivers = $_REQUEST ['recivers'];
		$subject = $_REQUEST ['subject'];
		$body = $_REQUEST ['body'];
		$parent_msg_id = "";
		$label = $_REQUEST ['label'];
		$result = $message->sendMessage ( $recivers, $subject, $body, $parent_msg_id, $label );
		break;
	case "replyMessage" :
		$recivers = $_REQUEST ['recivers'];
		$subject = $_REQUEST ['subject'];
		$body = $_REQUEST ['body'];
		$parent_msg_id = isset ( $_REQUEST ['parentMsgId'] ) ? $_REQUEST ['parentMsgId'] : "";
		$label = $_REQUEST ['replylabel'];
		$result = $message->replyMessage ( $recivers, $subject, $body, $parent_msg_id, $label );
		break;
	case "getMessageNotification" :
		$result = $message->getMessageNotification ( $login_id, $_REQUEST ['startlimit'], $_REQUEST ['endlimit'] );
		if ($result) {
		} else {
			$result = FALSE;
		}
		break;
	case "getMessage" :
		$result = $message->getMessage ( $login_id, $_REQUEST ['recipient_id'], $_REQUEST ['message_id'] );
		break;
	case "readMessage" :
		
		$result = $message->readMessage ( $_REQUEST ['message_id'], $login_id );
		break;
	case "unreadMessage" :
		
		$result = $message->unreadMessage ( $_REQUEST ['message_id'], $login_id );
		break;
	case "getSentMails" :
		$result = $message->getSentMails ( $login_id, $_REQUEST ['startlimit'], $_REQUEST ['endlimit'] );
		if ($result) {
		} else {
			$result = FALSE;
		}
		break;
	case "deleteMessage" :
		$result = $message->deleteMessage ( $_REQUEST ['message_id'], $login_id );
		break;
	case "deleteSendMessage" :
		$result = $message->deleteSendMessage ( $_REQUEST ['message_id'], $login_id );
		break;
	/*
	 * case "toUsers":
	 *
	 * $result = $message->toUsers();
	 * break;
	 */
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Message " . $action . " Successfull";
	$resultObj [2] = $result;
} else if ($result [0] === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Message " . $action . " Successfull";
	$resultObj [2] = $result [0];
	$resultObj [3] = $result [1];
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Message " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>