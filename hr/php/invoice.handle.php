<?php
/*
 * ----------------------------------------------------------
 * Filename : invoice.handle.php
 * Classname: invoice.class.php
 * Author : Rajasundari
 * Database : company_master_db
 * Oper : invoice Actions
 *
 * ----------------------------------------------------------
 */
error_reporting ( 0 );
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/invoice.class.php");
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/session.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$invoice = new invoice ();
/* Operations To Be Performed */
$invoice->invoice_id = isset ( $_REQUEST ['invoice_Id'] ) ? $_REQUEST ['invoice_Id'] : "";
$invoice->email_id = isset ( $_REQUEST ['emailId'] ) ? $_REQUEST ['emailId'] : "";
$invoice->txnid = isset ( $_REQUEST ['txnid'] ) ? $_REQUEST ['txnid'] : "";
$invoice->status = isset ( $_REQUEST ['status'] ) ? ($_REQUEST ['status'] == 'success') ? 'paid' : 'due' : "";
$invoice->txn_status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "";
$invoice->company_id = $_SESSION ['company_id'];
$invoice->conn = $conn;
switch ($action) {
	case "select" :
		$result = $invoice->select ( $invoice->company_id );
		break;
	case "payumoneyTransfer" :
		$result = $invoice->payumoneyTransfer ();
		$resultVal = $result;
		$result = TRUE;
		break;
	case "transactionAdd" :
		$result = $invoice->transactionAdd ( $this->company_id, $this->invoice_id, $this->txnid, $this->txn_status, $invoice->status );
		break;
	case "viewInvoice" :
		$result = $invoice->viewInvoice ( $invoice->company_id, $invoice->invoice_id );
		break;
	case "sentEmail" :
		$result = $invoice->sentEmail ( $invoice->company_id, $invoice->invoice_id, $invoice->email_id );
		break;
	default :
		$result = FALSE;
}
if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Invoice " . $action . " Successfull";
	$resultObj [2] = isset ( $resultVal ) ? $resultVal : 0;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Invoice " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>