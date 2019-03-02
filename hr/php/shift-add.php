<?php
/*
 * ----------------------------------------------------------
 * Filename: leave-add.php
 * add new entry to the company_branch database
 *
 *
 *
 * ----------------------------------------------------------
 */
include_once ("../../include/config.php");
$resultObj = array ();
$updated_by = $_SESSION ['login_id'];
$shift_name = $_REQUEST ['shift_name'];
$start_time = $_REQUEST ['start_time'];
$end_time = $_REQUEST ['end_time'];
$grace_inTime = $_REQUEST ['grace_inTime'];
$grace_outTime = $_REQUEST ['grace_outTime'];
$early_start = $_REQUEST ['early_start'];
$late_end = $_REQUEST ['late_end'];
$min_hrs_ot = $_REQUEST ['min_hrs_ot'];
$min_hrs_half_day = $_REQUEST ['min_hrs_half_day'];
$min_hrs_full_day = $_REQUEST ['min_hrs_full_day'];
$enabled = '1';
$rand = mt_rand ( 10000, 99999 );
$shift_id = "ST" . $rand;

$stmt = mysqli_prepare ( $conn, "INSERT INTO company_shifts (shift_id,shift_name,start_time,end_time,
grace_inTime,grace_outTime,early_start,late_end,min_hrs_ot,min_hrs_half_day,min_hrs_full_day,enabled,updated_by)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);" );

mysqli_stmt_bind_param ( $stmt, 'sssssssssssss', $shift_id, $shift_name, $start_time, $end_time, $grace_inTime, $grace_outTime, $early_start, $late_end, $min_hrs_ot, $min_hrs_half_day, $min_hrs_full_day, $enabled, $updated_by );
$result = mysqli_stmt_execute ( $stmt );

if ($result) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Shift Added Successfully";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Cant be added";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
}

?>
