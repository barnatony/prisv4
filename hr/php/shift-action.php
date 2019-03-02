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
include_once ("../../include/config.php");
$shift_id = $_REQUEST ['shift_id'];
$action = $_REQUEST ['action'] == 'disable' ? 0 : 1;

$stmt = mysqli_prepare ( $conn, "UPDATE company_shifts SET enabled =? WHERE shift_id = ?" );
mysqli_stmt_bind_param ( $stmt, 'ss', $action, $shift_id );
$result = mysqli_stmt_execute ( $stmt );

if ($result) {
	mysqli_close ( $conn );
	echo 'success';
} else {
	echo 'error';
}

?>
