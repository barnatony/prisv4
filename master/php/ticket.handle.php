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
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/ticket.class.php");
$action = $_REQUEST ['act'];

$resultObj = array ();
/* Operations to be performed */
/* Setting Variables */
$ticket = new Ticket ();
$ticket->isMaster = TRUE;
switch ($action) {
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
		$ticket->creator_id = $_REQUEST ['creator_ticket'];
		$result = $ticket->replyTicket ( $ticket_id, $receiver_id, $description, $attachment, $status_change );
		break;
	case "getTicketsbyDuration":
	   $from =$_REQUEST['from'];
	   $to=$_REQUEST['to'];
	   $result=$ticket->getTicketsbyDuration($from,$to);
	   break;
	default :
		$result = FALSE;
		exit ();
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Ticket " . $action . " Successfull";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Ticket " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>