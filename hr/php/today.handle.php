<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/today.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

$attendance = new today_attendance ($conn);

$attendance->branchIds = isset ( $_REQUEST['branchIds']) ? $_REQUEST ['branchIds'] : '';
$attendance->tablekey = isset ( $_REQUEST['tablekey']) ? $_REQUEST ['tablekey'] : '';

date_default_timezone_set('Asia/Kolkata');
$now = new DateTime();
$time=$now->format('H:i:s');
$date = $now->format('Y-m-d');

if($time > '00:00:00' && $time < '08:00:00')
	$date = date('Y-m-d', strtotime("-1 day"));

$attendance->date = isset ($date) ? $date : '';
$attendance->conn = $conn;
switch ($action) {
    case "todayDashboardData" :
        //top widgets
        $resultSet = $attendance->topwidgets ($attendance->date,$attendance->branchIds);
        $result=$resultSet['result'];
        $dataSet["topwidgets"]=$resultSet['data'];
        
        //activity chart
        $resultSet = $attendance->chartData ($attendance->date,$attendance->branchIds);
        $result=$resultSet['result'];
        $dataSet["chartData"]=$resultSet['data'];
        
        //latecomers Table Data
        $resultSet = $attendance->lateComersData ($attendance->date,$attendance->branchIds);
        $result=$resultSet['result'];
        $dataSet["lateComers"]=$resultSet['data'];
        
        //persent Employees Table Data
        $resultSet = $attendance->PresentData ($attendance->date,$attendance->branchIds);
        $result=$resultSet['result'];
        $dataSet["present"]=$resultSet['data'];
       
        //active employees table Data
        $resultSet = $attendance->ActiveEmployees ($attendance->date,$attendance->branchIds);
        $result=$resultSet['result'];
        $dataSet["active"]=$resultSet['data'];
        
        //absent employees table data
        $resultSet = $attendance->AbsentEmployeesData ($attendance->date,$attendance->branchIds);
        $result=$resultSet['result'];
        $dataSet["absent"]=$resultSet['data'];
        
        break;
	    
	default :
		$result = FALSE;
		exit ();
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Employee " . $action . " Successfully";
	$resultObj [2] = isset($dataSet)?$dataSet:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Employee " . $action . " Failed";
	$resultObj [2] = isset($dataSet)?$dataSet:$result;
}
echo json_encode ( $resultObj );