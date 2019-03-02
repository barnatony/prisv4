<?php
/*
 * ----------------------------------------------------------
 * Filename : ticket.handle.php
 * Classname: ticket.class.php
 * Author : Rufus Jackson
 * Database : tickets,ticket_replies
 * Oper : Raise Ticket, View Ticket
 *
 * ----------------------------------------------------------
 */
include_once ((dirname ( dirname ( __FILE__ ) )) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( (__FILE__) ) ) . "/include/lib/ticket.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
// $action = $_REQUEST['act'];
$resultObj = array ();

/* Operations to be performed */
/* Setting Variables */
$ticket = new Ticket ();
$ticket->company_id = $_SESSION ['company_id'];
$ticket->creator_id = $_SESSION ['login_id'];
$ticket->isMaster = FALSE;
switch ($action) {
	case "raiseTicket" :
		$category = $_REQUEST ['category'];
		$subject = $_REQUEST ['subject'];
		$desc = $_REQUEST ['description'];
		$priority = $_REQUEST ['priority'];
		$attachment = $_FILES ["attachment"];
		$result = $ticket->raiseTicket ( $category, $subject, $desc, $attachment, $priority );
		break;
	
	case "viewTickets" :
		$result = $ticket->viewTickets ( $_REQUEST ['startlimit'], $_REQUEST ['endlimit'] );
		if ($result) {
		} else {
			$result = FALSE;
		}
		break;
	
	case "viewTicket" :
		$result = $ticket->viewTicket ( $_REQUEST ['ticket_Id'] );
		break;
	
	case "replyTicket" :
		$ticket_id = $_REQUEST ['ticketId'];
		$receiver_id = $_REQUEST ['receiverId'];
		$description = $_REQUEST ['description'];
		$attachment = $_FILES ["attachment"];
		$status_change = $_REQUEST ['status'];
		$result = $ticket->replyTicket ( $ticket_id, $receiver_id, $description, $attachment, $status_change );
		break;
	
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Ticket " . $action . " Successfull";
	$resultObj [2] = $result;
} else if ($result [0] === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Ticket " . $action . " Successfull";
	$resultObj [2] = $result [0];
	$resultObj [3] = $result [1];
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Ticket " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>