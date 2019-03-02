<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/compensation.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

$compOff = new Compensation();
$compOff->working_for = isset ( $_REQUEST ['workingFor'] ) ? $_REQUEST ['workingFor'] : "";
$compOff->compoff_id = isset ( $_REQUEST ['compOff'] ) ? $_REQUEST ['compOff'] : "";
$compOff->day_count = isset ( $_REQUEST ['dayCount'] ) ? $_REQUEST ['dayCount'] : null;
$compOff->employee_id = isset ( $_REQUEST ['empId'] ) ? $_REQUEST ['empId'] : "";
$compOff->status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "";
$compOff->dateFlag = isset ( $_REQUEST ['dateFlag'] ) ? $_REQUEST ['dateFlag'] : "";
$compOff->admin_reason = isset ( $_REQUEST ['admin_reason'] ) ? $_REQUEST ['admin_reason'] : "";
$compOff->date=DateTime::createFromFormat('d/m/Y',(isset( $_REQUEST ['date'])?$_REQUEST ['date']:'00/00/0000'))->format('Y-m-d');
$compOff->approved_by = $_SESSION ['login_id'];
$compOff->conn = $conn;
switch ($action) {
	case "insert" :
	    $compOff->compoff_id='CO'.mt_rand ( 10000, 99999 );
		$resultData = $compOff->insert (1);
		$result=$resultData['result'];
		$dataError=$resultData['dataError'];
		break;
	case "getHoliday":
		$result=$compOff->getholiday ( $compOff->date,$compOff->date,$compOff->employee_id);
		break;
	case "select":
		 $result = $compOff->select ("'RQ','CO','2X'",0);
		break;
	case "search":
		$result = $compOff->getCompWithDate ("'RQ','2X','CO'",$compOff->date,null);
		break;
	case "update":
		$result = $compOff->update ();
		break;
	default :
		$result = false;
		exit ();
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "compensation " . $action . " Successfully";
	$resultObj [2] = isset($dataError)?$dataError:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "compensation " . $action . " Failed";
	$resultObj [2] =  isset($dataError)?$dataError:$result;
}

echo json_encode ( $resultObj );