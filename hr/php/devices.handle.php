<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/devices.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/deviceApi.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
//$action=$_REQUEST['act'];
//$resultObj = array ();


/* Setting Variables */
$devices = new devices ($conn);
$device =new deviceApi ($conn);
		
$devices->deviceIp = isset ( $_REQUEST ['deviceIp'] ) ? $_REQUEST ['deviceIp'] : "";
$devices->deviceMac = isset ( $_REQUEST ['deviceMac'] ) ? $_REQUEST ['deviceMac'] : "";
$devices->deviceName = isset ( $_REQUEST ['deviceName'] ) ? $_REQUEST ['deviceName'] : "";
$devices->device_loc = isset ( $_REQUEST ['device_loc'] ) ? $_REQUEST ['device_loc'] : "";
$devices->userName = isset ( $_REQUEST ['userName'] ) ? $_REQUEST ['userName'] : "";
$devices->password = isset ( $_REQUEST ['password'] ) ? $_REQUEST ['password'] : "";
$devices->enableEnroll =  isset ( $_REQUEST ['enableEnroll'] ) ? ($_REQUEST ['enableEnroll'] == 'on') ? 1 : 0 : "";
$devices->deviceId = isset ( $_REQUEST ['deviceId'] ) ? $_REQUEST ['deviceId'] : "";
$devices->listenerIp = isset ( $_REQUEST ['listenerIp'] ) ? $_REQUEST ['listenerIp'] : "";
$devices->listenerPort = isset ( $_REQUEST ['listenerPort'] ) ? $_REQUEST ['listenerPort'] : "";
$devices->registered_by = $_SESSION ['login_id'];
$now = new DateTime();
$date=$now->format('Y-m-d'); // MySQL datetime format
$devices->registered_on = isset ($date) ? $date : '';
switch ($action) {
	case "insert" :
		$resultset = $devices->insert ($devices->deviceName,$devices->deviceIp, $devices->deviceMac ,  $devices->device_loc,$devices->userName,$devices->password, $devices->enableEnroll);
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "getDevicedata" :
		$resultset = $devices->getDevicedata ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "insertListener" :
		$resultset = $devices->insertListener ($devices->deviceIp,$devices->listenerIp,$devices->listenerPort,$devices->registered_on,$devices->registered_by);
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "getListenerinfo" :
		
		$result=$device->getdeviceinfo($devices->deviceIp);
		$resultset = $devices->getListenerinfo ($devices->deviceIp);
		array_push($resultset['data'], $result);
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "changeEnrolment" :
		$resultset = $devices->changeEnrolment ($_REQUEST ['deviceId']);
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
		
		case "setinactive" :
			$resultset = $device->setinactive ($_REQUEST ['empId']);
			$result=$resultset['result'];
			$data=$resultset['data'];
			break;
	case "importintoDevice" :
		$start=isset($_REQUEST['start'])?$_REQUEST['start']:null;
		$end=isset($_REQUEST['end'])?$_REQUEST['end']:null;
		$finger_count=$_REQUEST['count'];
		$devices=$_REQUEST['devices'];
		if(!is_array($devices))
			$devices=explode(",", $devices);
		$resultset = $device->importIntoDevices($devices,$start,$end,$finger_count);
		$result=$resultset[0];
		$data=$resultset[1];
	break;
	case "importFromDevices" :
		$ref_id_start=$_REQUEST['ref_start'];
		$ref_id_end=$_REQUEST['ref_end'];
		$finger_index=$_REQUEST['finger_index'];
		$devices=$_REQUEST['devices'];
		$source_device=$_REQUEST['source_device'];
		if(!is_array($devices))
			$devices=explode(",", $devices);
		$resultset = $device->importFromDevices($devices,$ref_id_start,$ref_id_end,$finger_index,$source_device);
		$result=$resultset[0];
		$data=$resultset[1];
	
	break;
	case "deviceSync" :
		
		$device_ip= $devices->deviceIp;
		$device_capacity=50000;
		//$device_ip=81;
		$resultset = $device->device_sync($device_capacity,$device_ip);
		$result=$resultset[0];
		$data=$resultset[1];
	break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Devices " . $action . " Successfully";
	$resultObj [2] = (isset($data)?$data:$result);
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Devices " . $action . " Failed";
	$resultObj [2] = (isset($data)?$data:$result);
}
echo json_encode ( $resultObj );