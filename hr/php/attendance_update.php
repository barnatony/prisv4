<?php
/*
 * ----------------------------------------------------------
 * Filename: branch-add.php
 * add new entry to the company_branch database
 *
 *
 *
 * ----------------------------------------------------------
 */
error_reporting ( 0 );
include_once ("../../include/config.php");
$resultObj = array ();
$updated_by = $_SESSION ['login_id'];
$employee_id = $_REQUEST ['employee_id'];
$lop = $_REQUEST ['lop'];
$i = 0;
$temp_tble = array ();

foreach ( $_POST as $key => $value ) {
	
	if ($key [0] == "k") // leave for preivoe temp
{
		$temp_tble [$i] = substr ( $key, 5 ) . "='" . $value . "'";
		$i ++;
	}
}

$employee_temp = implode ( ',', $temp_tble );
$stmt = mysqli_prepare ( $conn, "UPDATE payroll_preview_temp 
SET $employee_temp,updated_by='" . $updated_by . "',lop='" . $lop . "',updated_on=CURRENT_TIMESTAMP WHERE employee_id = '$employee_id'" );
$result = mysqli_stmt_execute ( $stmt );

$i = 0;
$remaining = array ();
foreach ( $_POST as $key => $value ) {
	if ($key !== "employee_name" && $key !== "employee_id" && $key [0] !== "k" && $key !== "lop") // remaing for RMLL,RCLL
{
		$value;
		$remaining [$i] = substr_replace ( $key, "R_" . $key, 0 ) . "='" . $value . "'";
		$i ++;
	}
}

$employee_account = implode ( ',', $remaining );
$stmt = "SELECT * FROM  employee_leave_account WHERE employee_id ='$employee_id' ";
$result = mysqli_query ( $conn, $stmt );
$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
$lop_for_year = $row ['lop'] + $_REQUEST ['lop'];

$stmt1 = mysqli_prepare ( $conn, "UPDATE employee_leave_account 
SET $employee_account,updated_by='" . $updated_by . "',lop='" . $lop_for_year . "',updated_on=CURRENT_TIMESTAMP WHERE employee_id = '$employee_id'" );
$result1 = mysqli_stmt_execute ( $stmt1 );

if ($result && $result1) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Updated";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Cant be added";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
}

?>
