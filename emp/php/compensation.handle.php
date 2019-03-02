<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/compensation.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

$compOff = new Compensation();
$compOff->working_for = isset ( $_REQUEST ['workingFor'] ) ? $_REQUEST ['workingFor'] : "";
$compOff->date=DateTime::createFromFormat('d/m/Y',(isset( $_REQUEST ['date'])?$_REQUEST ['date']:'00/00/0000'))->format('Y-m-d');
$compOff->updated_by = $_SESSION ['employee_id'];
$compOff->employee_id = $_SESSION ['employee_id'];
$compOff->status = 'RQ';
$compOff->conn = $conn;
switch ($action) {
	case "insert" :
	    $compOff->compoff_id='CO'.mt_rand ( 10000, 99999 );
		$resultData = $compOff->insert (0);
		$result=$resultData['result'];
		$dataError=$resultData['dataError'];
		break;
	case "getHoliday":
		$result=$compOff->getholiday ( $compOff->date,$compOff->date,$compOff->employee_id);
		break;
	case "select" :
		$result = $compOff->select ("'RQ','2X','CO','R'",1);
		break;
	case "search":
		$result = $compOff->getCompWithDate ("'RQ','2X','CO','R'",$compOff->date,$compOff->employee_id);
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
	$resultObj [2] = isset($dataError)?$dataError:$result;
}

echo json_encode ( $resultObj );