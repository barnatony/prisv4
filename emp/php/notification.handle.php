<?php
/*
 * ----------------------------------------------------------
 * Filename : notification.handle.php
 * Classname: notification.class.php
 * Author : Rufus Jackson
 * Database :
 * Oper : Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname(dirname ( dirname ( __FILE__ )) ) . "/include/lib/notification.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Operations to be performed */

/* Setting Variables */

$notification = new Notification ();

$notification->employee_id = $_SESSION ['employee_id'];

switch ($action) {
	case "readNotification" :
		$result = $notification->readNotification ( $_REQUEST ['notif_id'] );
		break;
	case "getEmployeeNotifications" :
		$result = $notification->getEmployeeNotifications ( $notification->employee_id, $_REQUEST ['start_limit'], $_REQUEST ['end_limit'] );
		break;
	case "deleteNotification" :
		$result = $notification->deleteNotification ( $_REQUEST ['notification_id'] );
		break;
	default :
		$result = false;
		exit ();
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Notification " . $action . " Successfull";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Notification " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
