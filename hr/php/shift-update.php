<?php
/*
 * ----------------------------------------------------------
 * Filename:shift-Update.php
 * add new entry to the company_branch database
 *
 *
 *
 * ----------------------------------------------------------
 */
include_once ("../../include/config.php");
$resultObj = array ();
$shift_id = $_REQUEST ['shift_id'];
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
$updated_by = $_SESSION ['login_id'];

$stmt = mysqli_prepare ( $conn, "UPDATE company_shifts 
SET shift_name = ?, start_time = ?, end_time = ?, grace_inTime = ?, grace_outTime = ?, early_start = ?, late_end = ?, min_hrs_ot = ? ,min_hrs_half_day = ?, min_hrs_full_day = ?,updated_by=? WHERE shift_id = ?" );
mysqli_stmt_bind_param ( $stmt, 'ssssssssssss', $shift_name, $start_time, $end_time, $grace_inTime, $grace_outTime, $early_start, $late_end, $min_hrs_ot, $min_hrs_half_day, $min_hrs_full_day, $updated_by, $shift_id );
$result = mysqli_stmt_execute ( $stmt );
if ($result) {
	$resultObj [0] = 'success';
	$resultObj [1] = 'Shift Updated';
	echo json_encode ( $resultObj );
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Error ON Code";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
}

?>
