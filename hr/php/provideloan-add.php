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

$resultObj = array ();
$employee_id = $_REQUEST ['employee_id'];
if ($employee_id) {
	foreach ( $employee_id as $value ) {
		$in_employee_id = $value;
		$loan_id = $_REQUEST ['loan_id'];
		$loan_amount = $_REQUEST ['loan_amount'];
		$no_installment = $_REQUEST ['no_installment'];
		$emi_amount = $_REQUEST ['emi_amount'];
		$issued_on = $_REQUEST ['issued_on'];
		$enabled = '1';
		$starts_from = $_REQUEST ['starts_from'];
		$closing_date = $_REQUEST ['closing_date'];
		$closing_balance = $_REQUEST ['loan_amount'];
		$remaining_installments = $_REQUEST ['no_installment'];
		$updated_by = $_SESSION ['login_id'];
		$stmt = mysqli_prepare ( $conn, "INSERT INTO company_loan_mapping
(employee_id,loan_id, loan_amount, no_installment, emi_amount, issued_on, enabled, starts_from, closing_balance,closing_date, remaining_installments,updated_by)VALUES (?,?,?,?,?,?,?,?,?,?,?,?);" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssss', $in_employee_id, $loan_id, $loan_amount, $no_installment, $emi_amount, $issued_on, $enabled, $starts_from, $closing_balance, $closing_date, $remaining_installments, $updated_by );
		$result = mysqli_stmt_execute ( $stmt );
	}
}
if ($result) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Mapped Successfully";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Error on code";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
}

?>
