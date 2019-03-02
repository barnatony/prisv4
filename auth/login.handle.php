<?php
/*
 * ----------------------------------------------------------
 * Filename : Login Handle
 * Classname: Login Handle
 * Author : Antony B Adaikalam
 * Database :
 * Oper :
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/user.class.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/notification.class.php");

//allow basspris.com to get data


header('Access-Control-Allow-Origin: *');


// $temp = explode('!', base64_decode($_REQUEST['act']));
// $action = $temp[1];
$action = $_REQUEST ['act'];
$resultObj = array ();

/* Operations to be performed */

/* Setting Variables */
$user = new User ();

switch ($action) {
	case "company" :
		if ($user->validateCompany ( $_REQUEST ['companyUserName'] )) {
			$result = true;
		} else {
			$result = false;
		}
		break;
	case "user" :
		$priv = $user->validateUser ( $_REQUEST ['type'], $_REQUEST ['username'], $_REQUEST ['password'],$_REQUEST['companyId'] );
		if ($priv != false) {
			if ($priv == "EMPLOYEE") {
				$result = array (
						true,
						"emp" 
				);
			} elseif ($priv == "HR") {
				$result = array (
						true,
						"hr" 
				);
			}
		} else {
			$result = false;
		}
		break;
	case "resetPassword" :
		$priv = $user->resetPassword($_REQUEST ['type'],$_REQUEST["companyId"],$_REQUEST["email"]);
		if ($priv != false) 
			$result =  true;
		else 
			$result = false;
		break;
	case "master" :
		$priv = $user->validateMaster ( $_REQUEST ['username'], $_REQUEST ['password'] );
		if ($priv != false) {
			$result = array (
					true,
					"master" 
			);
		} else {
			$result = false;
		}
		break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Login " . $action . " Successfull";
	$resultObj [2] = $result [1];
} else if ($result [0] === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Login " . $action . " Successfull";
	$resultObj [2] = $result [0];
	$resultObj [3] = $result [1];
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Incorrect $action Credentials";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>