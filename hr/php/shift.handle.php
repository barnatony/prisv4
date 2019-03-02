<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/shift.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

$shift = new Shift ();
$shift->shift_name = isset ( $_REQUEST ['shiftName'] ) ? $_REQUEST ['shiftName'] : "";
$shift->start_time = isset ( $_REQUEST ['startTime'] ) ? $_REQUEST ['startTime'] : "";
$shift->end_time = isset ( $_REQUEST ['endTime'] ) ? $_REQUEST ['endTime'] : "";
$shift->dayType = isset ( $_REQUEST ['dayType']) ?  0 : 1 ;
$shift->grace_inTime = isset ( $_REQUEST ['graceInTime'] ) ? $_REQUEST ['graceInTime'] : "";
$shift->grace_outTime = isset ( $_REQUEST ['graceOutTime'] ) ? $_REQUEST ['graceOutTime'] : "";
$shift->early_start = isset ( $_REQUEST ['earlyStart'] ) ? $_REQUEST ['earlyStart'] : "";
$shift->late_end = isset ( $_REQUEST ['lateEnd'] ) ? $_REQUEST ['lateEnd'] : "";
$shift->min_hrs_ot = isset ( $_REQUEST ['minHrsOt'] ) ? $_REQUEST ['minHrsOt'] : "";
$shift->min_hrs_half_day = isset ( $_REQUEST ['minHrsHalfDay'] ) ? $_REQUEST ['minHrsHalfDay'] : "";
$shift->min_hrs_full_day = isset ( $_REQUEST ['minHrsFullDay'] ) ? $_REQUEST ['minHrsFullDay'] : "";
$shift->shift_id = isset ( $_REQUEST ['shiftId'] ) ? $_REQUEST ['shiftId'] : "";
$shift->updated_by = $_SESSION ['login_id'];
$shift->conn = $conn;
switch ($action) {
	case "insert" :
	    $shift->shift_id='SH'.mt_rand ( 10000, 99999 );
		$result = $shift->insert ($_REQUEST['mapShift']);
		break;
	case "update" :
		$result = $shift->update ( $_REQUEST ['mapShift'] );
		break;
	case "enable" :
		$result = $shift->setEnable ( 1 );
		break;
	case "disable" :
		$result = $shift->setEnable ( 0 );
		break;
	case "select" :
		$result = $shift->select (0);
		break;
	case "getShiftMapped" :
		$result = $shift->getShiftMapped ( $shift->shift_id);
		break;
	default :
		$result = false;
		exit ();
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Shift " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Shift " . $action . " Failed";
	$resultObj [2] = $result;
}

echo json_encode ( $resultObj );