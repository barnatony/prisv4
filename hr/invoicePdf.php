<?php
error_reporting ( 0 );
require_once (dirname ( __DIR__ ) . "/include/config.php");
$html = "";
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/invoice.class.php");
$invoice = new Invoice ();
$invoice->conn = $conn;
$row = $invoice->viewInvoice ( $_SESSION ['company_id'], $_REQUEST ['invoiceIdForpdf'] );
require_once (dirname ( __DIR__ ) . "/include/lib/session.class.php");
$styleSheet = '<style>
  body { font-family: Open Sans, sans-serif; font-size: 10pt; }
 .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    line-height: 1.42857;
    padding: 8px;
    vertical-align: top;
}
   
.table td{
border-collapse: collapse;
padding:6px;
} 
th{
    text-align: left;
}
.textRightAlign{
text-align:right;
}
table.content td{
border-top:1px solid black;
border-collapse: collapse;
padding:6px;
} 
.bootomBorder{    		
border-top:1px solid black;
border-collapse: collapse;
}    	
.success{
color:#1AB394;
}
.danger{
color:#e46f61;
}
</style>';
$address = explode ( '_', $row ['values'] [0] );
$html .= $styleSheet;
$status = ($row ['values'] [8] == 'due') ? '<h2 class="danger">Unpaid</h2>' : '<h2 class="success">Paid</h2>';
$html .= '<table style="width:100%">
    		<tr><td> <img src="../compDat/masterlogo.png" alt="basspris-logo" class="col-lg-3" style="width: 17%;margin-top: 20px;"></td>
    		    <td class="textRightAlign"><h2>INVOICE</h2></td>
    		</tr>
    		<tr><td>&nbsp;&nbsp;&nbsp;No:40,Second Floor,</td><td class="textRightAlign"></td></tr>
    		<tr><td >&nbsp;&nbsp;&nbsp;Teachers Colony, Adyar,</td><td style="width:40%">Invoice Number	: <strong>' . $row ['values'] [1] . '</strong></td></tr>
    		<tr><td>&nbsp;&nbsp;&nbsp;Chennai.PIN:600020,</td><td class="textRightAlign"></td></tr>
    		<tr><td style="width:60%">&nbsp;&nbsp;&nbsp;+91 9841616416 </td><td style="width:40%">Invoice Period : ' . $row ['values'] [6] . '</td></tr>
    		</table>
    		<br>
    		<table style="width:100%">
    		<tr><td style="width:60%">' . $status .'(Due on '.$row ['values'] [9]. ')</td><td><h4>BILLING TO</h4></td></tr>
    		<tr><td ></td><td>' . $address [0] . '</td></tr>
    		<tr><td ></td><td>' . $address [1] . ',</td></tr>
    		<tr><td ></td><td>' . $address [2] . ',</td></tr>
    		<tr><td ></td><td>' . $address [3] . ',</td></tr>
    		<tr><td ></td><td>' . $address [4] . ',' . $address [5] . '-' . $address [6] . ',</td></tr>
    		<tr><td ></td><td>' . $address [7] . ',</td></tr>
    		<tr><td ></td><td> + 91 ' . $address [8] . '.</td></tr>
    		</table>
    		 
	        <table class="table content" style="border-bottom:0pt solid black;border-collapse: collapse;width:100%"><thead><tr>
    		<th>#</th><th>Particulars</th>
    		<th class="textRightAlign">Amount</th></tr></thead>
    		<tbody>';
$i = 1;
for($lc = 0; $lc < count ( $row ['services'] ); $lc += 4) {
	$values = (explode ( '|', $row ['services'] [$lc + 2] ) [1] != '') ? '( ' . explode ( '|', $row ['services'] [$lc + 2] ) [0] . ' active Employees ) ' : '';
	$html .= '<tr><td>' . $i . '</td><td>' . $row ['services'] [$lc] . $values . '</td><td class="textRightAlign">' . $row ['services'] [$lc + 1] . '</td></tr>';
	$i ++;
}

$html .= '</tbody></table><table class="table" style="border-bottom:0pt solid black;border-collapse: collapse;width:100%"><tbody>
    		<tr><td style="width:50%"></td><td class="bootomBorder">Subtotal</td><td class="textRightAlign bootomBorder"> ' . inr_format ( round ( $row ['values'] [2] ) ) . '</td></tr>
            <tr><td ></td><td class="bootomBorder">( - ) Discount @ ' . $row ['values'] [3] . ' %</td><td class="textRightAlign bootomBorder"> ' . inr_format ( round ( $row ['values'] [4] ) ) . '</td></tr>';

for($lc = 0; $lc < count ( $row ['taxes'] ); $lc += 4) {
	$html .= '<tr><td ></td><td class="bootomBorder"> ' . $row ['taxes'] [$lc] . ' ( ' . $row ['taxes'] [$lc + 2] . ' %) </td><td class="textRightAlign bootomBorder">' . inr_format ( round ( $row ['taxes'] [$lc + 1] ) ) . '</td></tr>';
}

$html .= '<tr class="h4"><td ></td><td class="bootomBorder">Grand Total</td><td class="textRightAlign bootomBorder">' . inr_format ( round ( $row ['values'] [5] ) ) . '</td></tr>
    		<tr><td ></td><td colspan="2" ><strong>' . ucfirst ( Session::newInstance ()->convert_number_to_words ( ROUND ( $row ['values'] [5] ) ) ) . 'only </strong></td></tr>
    		</tbody></table>';

$companyFooter = '<table style="width:100%" id="t03">
                                  <tr><td colspan="1" ></td><td colspan="8" style="text-align: left">
                                   <b> Note : </b>This is confidential information and you are advised not to share it with others. This is a computer generated
    <p> invoice and does not require any signature.</p></table>';

require_once (dirname ( dirname ( __FILE__ ) ) . "/js/mpdf/mpdf.php");
$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
$mpdf->WriteHTML ( $html );
$mpdf->setHTMLFooter ( $companyFooter );
$content = $mpdf->Output ( 'Invoice.pdf', 'D' ); // S
exit ();
?>
