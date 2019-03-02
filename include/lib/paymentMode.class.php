<?php
/*
 * ----------------------------------------------------------
 * Filename : paymentMode.class.php
 * Author : Rufus Jackson
 * Database : company_payment_modes
 * Oper : Payment Mode Actions
 *
 * ----------------------------------------------------------
 */
class PaymentMode {
	/* Member variables */
	var $payment_mode_id;
	var $name;
	var $bank_name;
	var $bank_ac_no;
	var $bank_branch;
	var $bank_ifsc;
	var $account_type;
	var $enabled;
	var $updated_by;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New Payment Mode */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_payment_modes
										(payment_mode_id,payment_mode_name, bank_name, bank_ac_no, bank_branch, bank_ifsc, account_type,enabled, updated_by) 
										VALUES (?,?,?,?,?,?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $this->payment_mode_id, $this->name, $this->bank_name, $this->bank_ac_no, $this->bank_branch, $this->bank_ifsc, $this->account_type, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update Payment Mode Using Payment Mode ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_payment_modes SET 
				payment_mode_name = ?,bank_name = ?,bank_ac_no = ?,bank_branch = ?,bank_ifsc = ?,account_type = ?,updated_by = ? WHERE payment_mode_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $this->name, $this->bank_name, $this->bank_ac_no, $this->bank_branch, $this->bank_ifsc, $this->account_type, $this->updated_by, $this->payment_mode_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Enable/Disable Payment Mode */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_payment_modes SET enabled = ?,updated_by = ? WHERE payment_mode_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->payment_mode_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Payment Mode */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_payment_modes WHERE payment_mode_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->payment_mode_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
}
?>