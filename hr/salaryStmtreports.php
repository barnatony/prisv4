<?php
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
ini_set ( 'memory_limit', '512M' );
$html = '';
$header = '';
$company_id = $_SESSION ['company_id'];
$date = $_SESSION ['payrollDatere'];

require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/payslipDesign.class.php");
$payslipDesign = new payslipDesign ();
$payslipDesign->conn = $conn;
$designCustomised = $payslipDesign->select ( 'PS-1111' );

$header .= '<table  style="border-bottom:1pt solid black;border-collapse: collapse;width:100%"> <tr>
  		<td style="width:20%"><img src=' . $designCustomised [0] ['company_logo'] . ' style="width:15%"></td>
  		<td>' . strtoupper ( $designCustomised [0] ['company_name'] ) . '<br>' . strtoupper ( $designCustomised [0] ['company_build_name'] ) . ',' . strtoupper ( $designCustomised [0] ['company_street'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_area'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_city'] ) . ',' . $designCustomised [0] ['company_pin_code'] . '</td></tr></table>';

$header .= '<br><table style="width:100%"><tr><td  style="font-weight: bold;text-align:center;">';
$header .= strtoupper ( "Salary statement for the month of " ) . strtoupper ( date ( 'F Y', strtotime ( $date ) ) ) . '
                     </td> </tr></table>';

$html .= '<div class="reportTable"> <table style="width:130%"><thead repeat_header="1" ><tr> 
    <th>ID</th><th>Name</th><th>LOP</th>';
foreach ( explode ( ",", substr ( $_SESSION ['allowanceslabel'], 0, - 1 ) ) as $key => $val ) {
	$html .= '<th>' . strtoupper ( $val ) . '</th>';
}
$html .= '<th>GROSS</th>';

foreach ( explode ( ",", substr ( $_SESSION ['dedulabel'], 0, - 1 ) ) as $key1 => $val1 ) {
	$html .= '<th>' . strtoupper ( $val1 ) . '</th>';
}
$html .= '<th>TOTDEDU</th><th>NET</th></tr></thead><tbody>';

$stmt = "SELECT lm.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,ROUND(lop,1) lop," . base64_decode ( $_SESSION ['allowancesIds'] ) . "gross_salary," . base64_decode ( $_SESSION ['deducIds'] ) . "total_deduction,net_salary From " . $_SESSION ['queryStmt'] . " ORDER BY lm.employee_id ";
$result = mysqli_query ( $conn, $stmt );
$num_rows = mysqli_num_rows ( $result );
$i = 0;
while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	$j = 0;
	if ($i % 2 == 0) {
		$html .= '<tr class="alt border_bottom">';
	} else {
		$html .= '<tr class="border_bottom">';
	}
	
	foreach ( $row as $_column ) {
		if ($j == 0) {
			$html .= "<td style='text-align:left;width:5%'>" . ($_column) . "</td>";
		} else if ($j == 1) {
			$html .= "<td style='text-align:left;width:12%;font-size:12px;'>" . ucfirst ( strtolower ( $_column ) ) . "</td>";
		} else {
			$rr = $_column;
			$html .= "<td style='text-align:right'>".inr_format($rr)."</td>";
		}
		$j ++;
	}
	
	$html .= "</tr>";
	$i ++;
}

$html .= '<tr><td colspan="2" style="text-align:right;width:17%">Total</td><td>-</td>';

$queryFindTotal = "SELECT sum(" . str_replace ( ",", "),sum(", base64_decode ( $_SESSION ['allowancesIds'] ) . "
        		gross_salary," . base64_decode ( $_SESSION ['deducIds'] ) . "total_deduction,net_salary" ) . ") From " . str_replace ( 'GROUP BY employee_id', '', $_SESSION ['queryStmt'] ) . "";
$result1 = mysqli_query ( $conn, $queryFindTotal );
$row1 = mysqli_fetch_array ( $result1, MYSQLI_ASSOC );
foreach ( $row1 as $_column ) {
	$html .= '<td style="text-align:right">' . inr_format ( ROUND ( $_column ), 2 ) . '</td>';
}
$html .= '</tbody></tr></table></div>';

$footer = '<table style="width:100%;"><tr style="background-color: #1957A2" ><td colspan="5" style="text-align:center;color:#FFF">&copy; Powered by <a style="color:#FFF" href="http://basspris.com"> BASSPRIS </a>-Online Payroll System</td></tr><tr><td colspan="5" style="text-align:right">Page {PAGENO} | {nb}</td></tr></table>';

require_once (dirname ( dirname ( __FILE__ ) ) . "/js/mpdf/mpdf.php");
$mpdf = new mPDF ( 'utf-8', 'A4-L', '', '', '15', '15', '40', '30' ); // if remove note isset into 20
$styleSheet = file_get_contents ( dirname ( __DIR__ ) . "/css/reportTable.css" );
$mpdf->WriteHTML ( $styleSheet, 1 ); // Writing style to pdf
$mpdf->setHTMLHeader ( $header, 1 );
$mpdf->setHTMLFooter ( $footer );
$mpdf->WriteHTML ( $html, 2 );
$mpdf->Output ();
exit ();
?>

