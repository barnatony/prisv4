<?php
/*
 * ----------------------------------------------------------
 * Filename : EsiReport.class.php
 * Author : Raja Sundari
 * Database : payroll,..
 * Oper :
 *
 * ----------------------------------------------------------
 */
class EsiReport {
	var $monthYear;
	/* Member variables */
	protected $conn;
	function __construct($conn) {
		$this->conn = $conn;
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	function generateEsiReportData($monthYear,$esi_no) {
		$a_json = array ();
		$query = "SELECT CONCAT('p.',REPLACE(IF(deduce_in='GROSS','gross_salary',deduce_in),',','+p.')) esi_vars
				  FROM company_deductions WHERE deduction_id='c_esi';";
		$result = mysqli_query ( $this->conn, $query );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		$esi_dedVars = $row['esi_vars'];
		
		$extraCondition = (date ( "mY", strtotime ( $_SESSION ['current_payroll_month'] ) ) == $monthYear) ? 'AND
		 		(SELECT COUNT(*) FROM payroll_preview_temp where is_processed=0) = 0' : "";
		$stmt = "SELECT  w.employee_emp_esi_no, p.employee_name ,ROUND(DAY(LAST_DAY(p.month_year)) - p.lop) AS no_of_days
        				 ,ROUND($esi_dedVars) gross_salary, CEIL(b.c_esi_emp_share) employee_share,CEIL(b.c_esi_elr_share) employer_share,
						 (CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1 MONTH), '%m%Y') THEN 2 WHEN ROUND(DAY(LAST_DAY(p.month_year)) - p.lop)=0 THEN '1' ELSE 0 END) as reason_code,
						 (CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1 MONTH), '%m%Y') THEN DATE_FORMAT(w.dateofexit ,'%d/%m/%Y') ELSE '' END) as last_working_day
				 FROM payroll p
				 INNER JOIN payroll_deduction_breakup b
                 ON p.employee_id = b.employee_id
				 INNER JOIN employee_work_details w
				 ON p.employee_id = w.employee_id
				 INNER JOIN company_branch cb
         		 ON w.branch_id = cb.branch_id
				 INNER JOIN employee_salary_details s
         		 ON p.employee_id = s.employee_id
				 LEFT JOIN company_deductions cd
         		 ON deduction_id = 'c_esi'
         		 WHERE p.gross_salary <= cd.cal_exemption AND s.esi_limit !=-1 AND cb.esi_no='$esi_no' AND p.c_esi > 0 AND
		         DATE_FORMAT(p.month_year, '%m%Y') ='$monthYear' AND DATE_FORMAT(b.month_year, '%m%Y') ='$monthYear'  $extraCondition";
		//echo $stmt; die();
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	function downloadEsiexcel($monthYear,$esi_no) {
		$activeSheet = new PHPExcel ();
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "IP Number\n(10 Digits)"	 );
		$activeSheet->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
		$activeSheet->getActiveSheet ()->setCellValue ( 'B1', "IP Name\n( Only alphabets and space )" );
		$activeSheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		$activeSheet->getActiveSheet ()->setCellValue ( 'C1', "No of Days for which\n wages paid/payable during\n the month" );
		$activeSheet->getActiveSheet()->getStyle('C1')->getAlignment()->setWrapText(true);
		$activeSheet->getActiveSheet ()->setCellValue ( 'D1', 'Total Monthly Wages' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'E1', "Reason Code for Zero workings\n days(numeric only; provide 0 for\n all other reasons- Click on the\n link for reference)" );
		$activeSheet->getActiveSheet()->getStyle('E1')->getAlignment()->setWrapText(true);
		$activeSheet->getActiveSheet ()->setCellValue ( 'F1', "Last Working Day\n( Format DD/MM/YYYY  or\n DD-MM-YYYY)" );
		$activeSheet->getActiveSheet()->getStyle('F1')->getAlignment()->setWrapText(true);
		$activeSheet->getActiveSheet()->getStyle('A1:F1000')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$activeSheet->getActiveSheet ()->setTitle ( 'Sheet1' );
		$activeSheet->getActiveSheet ()->getStyle('A1:F1')->getFont()->setBold(true);
		$activeSheet->getActiveSheet()->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$activeSheet->getActiveSheet ()->getStyle ( "A1:F1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( 'b3ffff' );
		
		for($col = 'A'; $col !== 'Z'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$excelRowset = array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z') ;
		
		$extraCondition = (date ( "mY", strtotime ( $_SESSION ['current_payroll_month'] ) ) == $monthYear) ? 'AND
		 		(SELECT COUNT(*) FROM payroll_preview_temp where is_processed=0) = 0' : "";
		$stmt = "SELECT  TRIM(w.employee_emp_esi_no) esi_no, p.employee_name ,ROUND(DAY(LAST_DAY(p.month_year)) - p.lop) AS no_of_days
		,ROUND(p.gross_salary) gross_salary,
		(CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1 MONTH), '%m%Y') THEN 2 WHEN ROUND(DAY(LAST_DAY(p.month_year)) - p.lop)=0 THEN '1' ELSE '0' END) as reason_code,
		(CASE WHEN DATE_FORMAT(w.dateofexit, '%m%Y') = DATE_FORMAT(DATE_SUB(p.month_year,INTERVAL 1 MONTH), '%m%Y') THEN DATE_FORMAT(w.dateofexit ,'%d/%m/%Y') ELSE '' END) as last_working_day
		FROM payroll p
		INNER JOIN payroll_deduction_breakup b
		ON p.employee_id = b.employee_id
		INNER JOIN employee_work_details w
		ON p.employee_id = w.employee_id
		INNER JOIN company_branch cb
        ON w.branch_id = cb.branch_id
		INNER JOIN employee_salary_details s
        ON p.employee_id = s.employee_id
		LEFT JOIN company_deductions cd
		ON deduction_id = 'c_esi'
		WHERE p.gross_salary <= cd.cal_exemption AND s.esi_limit !=-1 AND cb.esi_no='$esi_no' AND
		DATE_FORMAT(p.month_year, '%m%Y') ='$monthYear' AND DATE_FORMAT(b.month_year, '%m%Y') ='$monthYear'  $extraCondition";
		//echo $stmt; die();
		$result = mysqli_query ( $this->conn,$stmt);
		
		$rowCount = 2;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$columCount = 0;
			foreach ( $row as $index ) {
				print_r($index);
				$activeSheet->getActiveSheet ()->setCellValue ( $excelRowset [$columCount] . $rowCount, $index );
				$columCount ++;
			}
			
			$rowCount ++;
		}
		
		$filname = 'Esi_report_'.$monthYear.'.xls';
		echo $filename; die();
		header ( "Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
		header ( "Content-Disposition: attachment; filename=$filname" );
		header ( "Cache-Control: max-age=0" );
		$objWriter = PHPExcel_IOFactory::createWriter ( $activeSheet, 'Excel5' );
		$objWriter->save ( 'php://output' );
		exit ();
	}
}
?>