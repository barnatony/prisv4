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
$invoice->serv_id = isset ( $_REQUEST ['serv_id'] ) ? $_REQUEST ['serv_id'] : "";
$invoice->company_username = isset ( $_REQUEST ['userName'] ) ? DB_PREFIX . $_REQUEST ['userName'] : "";
$invoice->company_id = isset ( $_REQUEST ['comId'] ) ? $_REQUEST ['comId'] : "";
$invoice->email_id = isset ( $_REQUEST ['emailId'] ) ? $_REQUEST ['emailId'] : "";
$invoice->exempt_to = isset ( $_REQUEST ['examptTo'] ) ? $_REQUEST ['examptTo'] : 0;
$invoice->serv_name = isset ( $_REQUEST ['serviceName'] ) ? $_REQUEST ['serviceName'] : "";
$invoice->serv_desc = isset ( $_REQUEST ['serviceDec'] ) ? $_REQUEST ['serviceDec'] : "";
$invoice->is_variable = isset ( $_REQUEST ['isVariable'] ) ? $_REQUEST ['isVariable'] : "";
$invoice->default_price = isset ( $_REQUEST ['price'] ) ? $_REQUEST ['price'] : 0;
// Tax OPrations
$invoice->tax_id = isset ( $_REQUEST ['taxId'] ) ? $_REQUEST ['taxId'] : "";
$invoice->title = isset ( $_REQUEST ['taxTitle'] ) ? $_REQUEST ['taxTitle'] : "";
$invoice->description = isset ( $_REQUEST ['taxDec'] ) ? $_REQUEST ['taxDec'] : "";
$invoice->tax_percentage = isset ( $_REQUEST ['taxPercent'] ) ? $_REQUEST ['taxPercent'] : 0;
$invoice->data = isset ( $_REQUEST ['data'] ) ? $_REQUEST ['data'] : null;

// invoice generate pameter
$invoice->invoice_id = isset ( $_REQUEST ['invoiceId'] ) ? $_REQUEST ['invoiceId'] : "";
$invoice->amount = isset ( $_REQUEST ['invoiceAmount'] ) ? $_REQUEST ['invoiceAmount'] : "";
$invoice->discount = isset ( $_REQUEST ['discountAmount'] ) ? $_REQUEST ['discountAmount'] : "";
$invoice->discounted_amount = isset ( $_REQUEST ['discountedAmount'] ) ? $_REQUEST ['discountedAmount'] : "";
$invoice->taxes = isset ( $_REQUEST ['taxAmount'] ) ? $_REQUEST ['taxAmount'] : "";
$invoice->net_amount = isset ( $_REQUEST ['netAmount'] ) ? $_REQUEST ['netAmount'] : "";
$date = DateTime::createFromFormat ( 'd/m/Y', isset ( $_REQUEST ['curentMOnth'] ) ? $_REQUEST ['curentMOnth'] : (isset ( $_REQUEST ['dueOnEdit'] ) ? $_REQUEST ['dueOnEdit'] : "00/00/000") );
$invoice->invoice_month = isset ( $_REQUEST ['curentMOnth'] ) ? $date->format ( 'Y-m-d' ) : "";
$invoice->INID = isset ( $_REQUEST ['curentMOnth'] ) ? $date->format ( 'mY' ) : "";
$invoice->invoice_period = isset ( $_REQUEST ['curentMOnth'] ) ? $date->format ( '28/m/Y' ) . " To " . date ( '28/m/Y', strtotime ( "+31 day", strtotime ( $date->format ( 'Y-m-d' ) ) ) ) : "";
$invoice->due_on = isset ( $_REQUEST ['curentMOnth'] ) ? date ( 'Y-m-28', strtotime ( "+31 day", strtotime ( $date->format ( 'Y-m-d' ) ) ) ) : $date->format ( 'Y-m-d' );
$invoice->servArray = isset ( $_REQUEST ['servArray'] ) ? explode ( ',', $_REQUEST ['servArray'] ) : "";
$invoice->taxArray = isset ( $_REQUEST ['taxArray'] ) ? explode ( ',', $_REQUEST ['taxArray'] ) : "";
$invoice->conn = $conn;
switch ($action) {
	case "generateInvoice" :
		$rand = mt_rand ( 10000, 99999 );
		$invoice->invoice_id = $rand . $invoice->INID;
		$result = $invoice->generateInvoice ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "updateDiscount" :
		$result = $invoice->updateDiscount ( $invoice->company_id, $invoice->invoice_id );
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "serviceInsert" :
		$rand = mt_rand ( 10000, 99999 );
		$invoice->serv_id = "SV" . $rand;
		$result = $invoice->serviceInsert ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "serviceSelect" :
		$result = $invoice->serviceSelect ();
		break;
	case "mapServices" :
		$result = $invoice->mapServices ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "serviceUpdate" :
		$result = $invoice->serviceUpdate ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "serviceDisable" :
		$result = $invoice->serviceEnable ( 0 );
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "serviceEnable" :
		$result = $invoice->serviceEnable ( 1 );
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "taxDisable" :
		$result = $invoice->taxEnable ( 0 );
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "taxEnable" :
		$result = $invoice->taxEnable ( 1 );
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "mapTaxes" :
		$result = $invoice->mapTaxes ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "taxInsert" :
		$rand = mt_rand ( 10000, 99999 );
		$invoice->tax_id = "TX" . $rand;
		$result = $invoice->taxInsert ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "taxUpdate" :
		$result = $invoice->taxUpdate ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "taxSelect" :
		$result = $invoice->taxSelect ();
		break;
	case "serviceEdit" :
		$result = $invoice->serviceEdit ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "invoiceSelect" :
		$result = $invoice->invoiceSelect ();
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "companyServiceMap" :
		$result = $invoice->companyServiceMap ();
		break;
	case "sortoutData" :
		$result = $invoice->sortoutData ( $invoice->data );
		$resultVal = $result ['select'];
		$result = $result ['result'];
		break;
	case "dueDateupdate" :
		$result = $invoice->dueDateupdate ( $invoice->company_id, $invoice->invoice_id );
		$resultVal = $result ['select'];
		$result = $result ['result'];
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