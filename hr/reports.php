<?php
ini_set ( 'memory_limit', '512M' );
ini_set('max_execution_time', '90');
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
// style Purpose
$company_id = $_SESSION ['company_id'];
$datesub = explode ( " ", $_REQUEST ['dateOfreports'] );
$date = $datesub [1] . "-" . $datesub [0] . "-01";

require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/payslipDesign.class.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/esiReport.class.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/tdsReport.class.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/ptReport.class.php");
$payslipDesign = new payslipDesign ();
$payslipDesign->conn = $conn;
$designCustomised = $payslipDesign->select ( 'PS-1111',$company_id);
$ptReport = new PtReport ( $conn);
//$ptReport->companyId = isset ( $_REQUEST ['companyId'] ) ? $_REQUEST ['companyId'] : "";

$esiReport = new EsiReport ( $conn );
$esiReport->esiNo = isset ( $_REQUEST ['esiNo'] ) ? $_REQUEST ['esiNo'] : "";

$tdsReport = new TdsReport ( $conn );
$tdsReport->tdsNo = isset ( $_REQUEST ['tdsNo'] ) ? $_REQUEST ['tdsNo'] : "";


if ($_REQUEST ['report'] == 'tds' || $_REQUEST ['report'] == 'esi' || $_REQUEST ['report'] == 'pt' ){
	$header = '';
}else{

$header .= '<table  style="border-bottom:1pt solid black;border-collapse: collapse;width:100%"> <tr>
  		<td style="width:20%"><img src=' . $designCustomised [0] ['company_logo'] . ' style="width:15%"></td>
  		<td>' . strtoupper ( $designCustomised [0] ['company_name'] ) . '<br>' . strtoupper ( $designCustomised [0] ['company_build_name'] ) . ',' . strtoupper ( $designCustomised [0] ['company_street'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_area'] ) . ',<br>' . strtoupper ( $designCustomised [0] ['company_city'] ) . ',' . $designCustomised [0] ['company_pin_code'] . '</td></tr></table>';
}
if ($_REQUEST ['report'] == 'tds') {
	$html .= '<table style="width:100%;">
                          <tr><td  colspan="4" style="font-weight: bold;text-align:center;">';
	$html .= ucwords ( "TDS Reports for the month of " ) . date ( 'F Y', strtotime ( $date ) ) . '
                         <br> </td> </tr></table><br>';
	$html .= ' <div class="reportTable"><table style="width:110%;"><thead repeat_header="1" ><tr>';
	
	$html .= '<th>Employee Code</th><th>Name</th><th>PAN Number</th><th>Gross Salary</th><th>Tax</th><th>EduChess</th><th>Hr.EduChess</th><th>Total</th></tr></thead><tbody>';
	$stmt = "SELECT  p.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,per.employee_pan_no,p.gross_salary,ROUND((p.c_it - ROUND
(p.c_it*(3/103),2))) as income_tax,ROUND(ROUND(p.c_it*(2/103),2)) as edu_chess,
ROUND(ROUND(p.c_it*(1/103),2)) as hr_edu_chess
, p.c_it as total FROM payroll p INNER JOIN employee_personal_details per
					ON p.employee_id = per.employee_id
					INNER JOIN employee_work_details w
					ON per.employee_id = w.employee_id
					INNER JOIN company_branch cb
        			ON w.branch_id = cb.branch_id
					,(SELECT @a:= 0) AS a
					WHERE cb.tan_no='$tdsReport->tdsNo' AND p.month_year='$date'
					AND p.c_it >0";
	
	$stmt2 = "SELECT  p.employee_id,p.employee_name,per.employee_pan_no,sum(p.gross_salary),ROUND(sum((p.c_it - ROUND
(p.c_it*(3/103),2)))) as income_tax,ROUND(sum(ROUND(p.c_it*(2/103),2))) as edu_chess,
ROUND(sum(ROUND(p.c_it*(1/103),2))) as hr_edu_chess
, sum(p.c_it) as total FROM payroll p
					INNER JOIN employee_personal_details per
					ON p.employee_id = per.employee_id
					INNER JOIN company_branch cb
        			ON w.branch_id = cb.branch_id,(SELECT @a:= 0) AS a
					WHERE cb.tan_no='$tdsReport->tdsNo' AND p.month_year='$date'
					AND p.c_it >0";
	
} else if ($_REQUEST ['report'] == 'pt') {
	$html .= '<table style="width:100%;">
                          <tr><td  colspan="4" style="font-weight: bold;text-align:center;">';
	$html .= ucwords ( "PT Reports for the month of " ) . date ( 'F Y', strtotime ( $date ) ) . '
                         <br> </td> </tr></table><br>';
	$html .= ' <div class="reportTable"><table style="width:110%;"><thead repeat_header="1" ><tr>';
	$html .= '<th>Employee Code</th><th>Name</th><th>Branch Name</th><th>PT City</th><th>Gross Salary</th><th>Professional Tax</th></tr></thead><tbody>';
	$stmt = "SELECT  p.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,b.branch_name,b.pt_city_id,p.gross_salary, p.c_pt as pt FROM payroll p
					INNER JOIN employee_personal_details per
					ON p.employee_id = per.employee_id
					INNER JOIN employee_work_details w
					ON per.employee_id = w.employee_id
					INNER JOIN company_branch b
					ON b.branch_id = w.branch_id
					,(SELECT @a:= 0) AS a
					WHERE p.month_year = '$date'
					AND p.c_pt >0";
	$stmt2 = "SELECT  p.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,b.branch_name,NULL,SUM(p.gross_salary), SUM(p.c_pt) as pt FROM payroll p
					INNER JOIN employee_personal_details per
					ON p.employee_id = per.employee_id
					INNER JOIN employee_work_details w
					ON per.employee_id = w.employee_id
					INNER JOIN company_branch b
					ON b.branch_id = w.branch_id
					,(SELECT @a:= 0) AS a
					WHERE p.month_year = '$date'
					AND p.c_pt >0";
} else if ($_REQUEST ['report'] == 'bankPayout') {
	$html .= '<table style="width:100%;">
                          <tr><td  colspan="4" style="font-weight: bold;text-align:center;">';
	$html .= ucwords ( "Bank Payouts for the month of " ) . date ( 'F Y', strtotime ( $date ) ) . '
                         <br> </td> </tr></table><br>';
	$html .= ' <div class="reportTable"><table style="width:110%;"><thead repeat_header="1" ><tr>';
	$html .= '<th>Bank Name</th><th>Branch Name</th><th>Account NO</th><th>IFSC Code</th><th>TotEmp</th><th>Net Salary</th></tr></thead><tbody>';
	$stmt = "SELECT c.bank_name comBankName,
					c.bank_branch compBranch,c.bank_ac_no compAccNO, c.bank_ifsc comIfsc,count(w.employee_id) countEmp ,SUM(p.net_salary) net
					FROM employee_personal_details e INNER JOIN employee_work_details w ON e.employee_id = w.employee_id
					LEFT JOIN payroll p ON w.employee_id = p.employee_id 
					LEFT JOIN company_payment_modes c ON w.payment_mode_id = c.payment_mode_id 
					WHERE p.month_year = '$date' AND w.enabled=1  GROUP BY c.bank_name ORDER BY bank_name ASC;";
	$stmt2 = "SELECT c.bank_ac_no compAccNO,c.bank_name comBankName,
					c.bank_branch compBranch, NULL ,count(w.employee_id) countEmp ,SUM(p.net_salary) net
					FROM employee_personal_details e INNER JOIN employee_work_details w ON e.employee_id = w.employee_id
					LEFT JOIN payroll p ON w.employee_id = p.employee_id 
					LEFT JOIN company_payment_modes c ON w.payment_mode_id = c.payment_mode_id 
					WHERE p.month_year = '$date' AND w.enabled=1   ORDER BY bank_name ASC;";
} else if ($_REQUEST ['report'] == 'esi') {
	$html .= '<table style="width:100%;">
                          <tr><td  colspan="4" style="font-weight: bold;text-align:center;">';
	
	$html .= ucwords ( "Esi Report for the month of " ) . date ( 'F Y', strtotime ( $date ) ) . '
                         <br> </td> </tr></table><br>';
	
	$html .= '<div class="reportTable"> <table style="width:110%;"><thead repeat_header="1" ><tr>';
	
	$html .= '<th>IP Number</th><th>IP Name</th><th>No of Days</th>
        		<th>ToT Wages</th><th>Employee Share</th><th>Employer Share</th><th>Reason Code</th><th>Last Working Day</th></tr></thead><tbody>';
	$stmt = "SELECT  w.employee_emp_esi_no, p.employee_name ,ROUND(DAY(LAST_DAY(p.month_year)) - p.lop) AS no_of_days,ROUND(p.gross_salary) gross_salary,
					 CEIL(b.c_esi_emp_share) employee_share,CEIL(b.c_esi_elr_share) employer_share,
				     (CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1	MONTH), '%m%Y') THEN 2 ELSE '' END) as reason_code,
				     (CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1	MONTH), '%m%Y') THEN DATE_FORMAT(w.dateofexit ,'%d/%m/%Y') ELSE '' END) as last_working_day
			 FROM payroll p
			 INNER JOIN payroll_deduction_breakup b
             ON p.employee_id = b.employee_id
             INNER JOIN employee_work_details w
             ON p.employee_id = w.employee_id
        	 LEFT JOIN company_deductions cd
         	 ON deduction_id = 'c_esi'
			 WHERE p.gross_salary <= cd.cal_exemption AND  p.month_year='$date' AND b.month_year='$date'";
	
	$stmt2 = "SELECT  w.employee_emp_esi_no, p.employee_name ,SUM(ROUND(DAY(LAST_DAY(p.month_year)) - p.lop)) AS no_of_days,SUM(ROUND(p.gross_salary)) gross_salary,
					 SUM(CEIL(b.c_esi_emp_share)) employee_share,SUM(CEIL(b.c_esi_elr_share)) employer_share,
				     (CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1	MONTH), '%m%Y') THEN 2 ELSE '' END) as reason_code,
				     (CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1	MONTH), '%m%Y') THEN DATE_FORMAT(w.dateofexit ,'%d/%m/%Y') ELSE '' END) as last_working_day
			 FROM payroll p
			 INNER JOIN payroll_deduction_breakup b
             ON p.employee_id = b.employee_id
             INNER JOIN employee_work_details w
             ON p.employee_id = w.employee_id
        	 LEFT JOIN company_deductions cd
         	 ON deduction_id = 'c_esi'
			 WHERE p.gross_salary <= cd.cal_exemption  AND p.month_year='$date' AND b.month_year='$date'";
	
}else{
	$html.='Report Cannot be Loaded. Regenerate Report.';
}
// header end
// body
$i = 0;
$result = mysqli_query ( $conn, $stmt );
while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	
	if ($i % 2 == 0) {
		$html .= '<tr class="alt border_bottom">';
	} else {
		$html .= '<tr class="border_bottom">';
	}
	
	$j = 0;
	foreach ( $row as $_column ) {
		if ($_REQUEST ['report'] == 'tds') {
			if ($j == 0 || $j == 1 || $j == 2) {
				$html .= "<td  style='text-align:left;'>{$_column}</td>";
			} else {
				$html .= "<td style='text-align:right;'>{$_column}</td>";
			}
		} else {
			if ($j == 0 || $j == 1 || $j == 2 || $j == 3) {
				$html .= "<td  style='text-align:left;'>{$_column}</td>";
			} else {
				$html .= "<td style='text-align:right;'>{$_column}</td>";
			}
		}
		
		$j ++;
	}
	$html .= "</tr>";
	$i ++;
}

$html .= '</tbody><tfoot><tr>';

$result1 = mysqli_query ( $conn, $stmt2 );
$row1 = mysqli_fetch_array ( $result1, MYSQLI_ASSOC );
$r = 0;
foreach ( $row1 as $_column ) {
	if ($r == 0 || $r == 1) {
		$html .= '<td style="text-align:right;"></td>';
	} else if ($r == 2) {
		$html .= '<td style="text-align:right;">Total</td>';
	} else {
		$html .= '<td style="text-align:right;">' . $_column . '</td>';
	}
	$r ++;
}
$html .= '</tr></tfoot></table></div>';

$footer = '<table style="width:100%;"><tr style="background-color: #1957A2" ><td colspan="5" style="text-align:center;color:#FFF"><p>&copy; Powered by  <a style="color:#FFF" href="http://basspris.com"> BASSPRIS </a>-Online Payroll System</td></tr><tr><td colspan="5" style="text-align:right">Page {PAGENO} | {nb}</td></tr></table>';

require_once (dirname ( dirname ( __FILE__ ) ) . "/js/mpdf/mpdf.php");
// $mpdf=new mPDF('en-GB-x','L','','',10,10,10,10,6,3);
$mpdf = new mPDF ( 'utf-8', 'A4-L', '', '', '15', '15', '40', '30' ); // if remove note isset into 20
$styleSheet = file_get_contents ( dirname ( __DIR__ ) . "/css/reportTable.css" );
$mpdf->WriteHTML ( $styleSheet, 1 );
$mpdf->setHTMLHeader ( $header, 1 );
$mpdf->setHTMLFooter ( $footer );
$mpdf->WriteHTML ( $html, 2 );
//'esi_report_' . date ( 'mY', strtotime ( $date ) ) .'.pdf', D
$mpdf->Output ();
exit ();

?>

