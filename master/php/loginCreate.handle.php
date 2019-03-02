<?php
/*
 * ----------------------------------------------------------
 * Filename : loginCreate.handle.php
 * Classname: loginCreate.class.php
 * Author : Raja sundari
 * Database : company_master_log
 * Oper : loginCreate Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/loginCreate.class.php");

require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$login_create = new loginCreate ();
/* Operations To Be Performed */

$rand = mt_rand ( 10000, 99999 );
$login_create->master_id =isset($_REQUEST ['masterId'])?(($_REQUEST ['masterId']=='Nil')?"BBM".$rand:$_REQUEST ['masterId']):"";
$imagereName =$login_create->master_id. '.jpg';
if(($action=='insert' || $action=='update') && !empty($_FILES["uLogo"]["name"]) && ($_REQUEST['isImage']==1))
{
	move_uploaded_file ( $_FILES ["uLogo"] ["tmp_name"],"../images/". $imagereName );
	$login_create->master_image = "images/".$imagereName;
}else{
	$login_create->master_image = "images/".$imagereName;
}	

$login_create->master_name = isset ( $_REQUEST ['name'] ) ? ucfirst($_REQUEST ['name'] ): "";
$login_create->isAllColumsSelect = isset ( $_REQUEST ['isAllColumsSelect'] ) ? $_REQUEST ['isAllColumsSelect'] : "";
$login_create->master_username = isset ( $_REQUEST ['userName'] ) ? $_REQUEST ['userName'] : "";
$login_create->master_email = isset ( $_REQUEST ['emailId'] ) ? $_REQUEST ['emailId'] : "";
$login_create->master_password = isset ( $_REQUEST ['password'] ) ? $_REQUEST ['password'] : "";
$login_create->master_mobile = isset ( $_REQUEST ['mobile'] ) ? $_REQUEST ['mobile'] : "";
$login_create->master_address = isset ( $_REQUEST ['address'] ) ? ucfirst($_REQUEST ['address']) : "";
$login_create->master_city = isset ( $_REQUEST ['city'] ) ? ucfirst($_REQUEST ['city'] ): "";
$login_create->master_state = isset ( $_REQUEST ['state'] ) ? ucfirst($_REQUEST ['state']) : "";
$login_create->master_gender = isset ( $_REQUEST ['gender'] ) ? $_REQUEST ['gender'] : "";
$login_create->master_role = isset ( $_REQUEST ['role'] ) ? $_REQUEST ['role'] : "";
$login_create->created_by =$_SESSION['master_id'];
$login_create->loginrole=$_SESSION['loginIn'];
$login_create->conn = $conn;
switch ($action) {
	case "insert" :
		$resultset = $login_create->insert ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "select" :
		$resultset = $login_create->select ($login_create->isAllColumsSelect);
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "enable" :
		$resultset = $login_create->setEnable ( 1 );
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "update" :
		$resultset = $login_create->update ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "disable" :
		$resultset = $login_create->setEnable ( 0 );
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	default :
		$result = FALSE;
}
if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "loginCreate " . $action . " Successfull";
	$resultObj [2] = isset($data)?$data:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "loginCreate " . $action . " Failed";
	$resultObj [2] = isset($data)?$data:$result;
}
echo json_encode ( $resultObj );
?>