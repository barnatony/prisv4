<?php
/*
 * ----------------------------------------------------------
 * Filename : import.class.php
 * Author : faheen
 * Database : emp_monthly_attendance,payroll_preview_temp
 * Oper : Actions
 * * ----------------------------------------------------------
 */
 
class Import {
	
	
	
	protected $excelRowset = array ('0','0','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z') ;
	protected $ro = array ('C2','D2','E2','F2','G2','H2','I2','J2','M2','N2','O2','P2','Q2','R2','S2','T2','U2','V2','W2','X2','Y2','Z2');
	protected $excelRowset1 = array('0','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG');
	protected $ro1 = array ( 'C2','D2','E2','F2','G2','H2','I2','J2','K2','L2','M2','N2','O2','P2','Q2','R2','S2','T2','U2','V2','W2','X2','Y2','Z2','AA2','AB2','AC2','AD2','AE2','AF2','AG2');
	function __construct($conn){
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
		ini_set ( 'memory_limit', MEMORY_LIMIT );
		ini_set('max_execution_time', MAX_EXECUTION_TIME);
		date_default_timezone_set(DEFAULT_TIMEZONE);
		$this->conn = $conn;
		$result = mysqli_query ( $this->conn, "SELECT salary_days,attendance_period_sdate attendance_dt
		FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'" );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->conn));
		$this->attendance_start_date=$row['attendance_dt'];
		
	if($row['attendance_dt'] !=1){
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".$row['attendance_dt'];
			$this->startDate = date("Y-m-d",strtotime("{$this->startDate} -1 months"));
			$this->endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($row['attendance_dt']-1);
		}else{
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
			$this->endDate = date('Y-m-t',strtotime($this->startDate));
		}
}
//final upload of downloaded file... 
	function AttendanceProcessUploadedFile($xsxlFile, $leaveRules) {
		if ($xsxlFile) {
			if (($getfile = fopen ( $xsxlFile, "r" )) !== FALSE) {
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
				foreach ( $objPHPExcel->getWorksheetIterator () as $worksheet ) {
					$worksheetTitle = $worksheet->getTitle ();
					if ($worksheetTitle != 'Information') {
						$highestRow = $worksheet->getHighestRow (); // e.g. 10
						$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
						$nrColumns = ord ( $highestColumn ) - 64;
						$counProtect = 1;
						
						foreach ( $leaveRules as $comd ) {
							$leaveRuleArry [$counProtect] = $comd ['leave_rule_id'];
							$counProtect ++;
						}
						
						$leaveRulesUddate = "";
						$leaveRulesUddate1 = "";
						$comoff = "";
						$lop = "";
						$leaveYear = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
						for($row = 3; $row <= $highestRow; ++ $row) {
							$k = 1;
							$j = 0;
							$employeId = "";
							$duplicateStr = "";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								if ($col == 0) {
									$leaveRulesUddate1 .= " INSERT INTO emp_montly_attendance SET employee_id='" . $val . "',month_year='" . $_SESSION ['current_payroll_month'] . "',year='" . $leaveYear . "',updated_by='" . $_SESSION ['login_id'] . "',";
									$leaveRulesUddate .= " UPDATE payroll_preview_temp SET updated_by='" . $_SESSION ['login_id'] . "',";
									$employeId = $val;
								}
								if ($counProtect < $col) {
									if (! empty ( $leaveRuleArry [$k] )) {
										$leaveRulesUddate .= $leaveRuleArry [$k] . "=" . $val . ",";
										$duplicateStr .= $leaveRuleArry [$k] . "=" . $val . ",";
										$leaveRulesUddate1 .= $leaveRuleArry [$k] . "=" . $val . ",";
									} else {
										if ($j == 0) {
											$leaveRulesUddate1 .= "compoff=" . $val . "  ON DUPLICATE KEY UPDATE " . $duplicateStr . "compoff=" . $val . " ; ";
											$j ++;
										} else if ($j == 1) {
											$leaveRulesUddate .= "lop=" . $val . " WHERE employee_id='$employeId';";
											$j = 0;
										}
									}
									$k ++;
								}
							}
						}
					}
				}
				
				if ($result = mysqli_multi_query ( $this->conn, $leaveRulesUddate1 . $leaveRulesUddate )) {
					$result1 = true;
					if ($result = mysqli_use_result ( $this->conn )) {
						do {
							if ($result = mysqli_store_result ( $this->conn )) {
								mysqli_free_result ( $result );
							}
						} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
					}
				}
				return isset ( $result1 ) ? $result1 : mysqli_error ( $this->conn );
			}
		} else {
			return "File Cant Be Empty";
		}
	}
	//upload
	function AttendanceTemplateUpload($xsxlFile, $leaveRules) {

		if ($xsxlFile) {
			if (($getfile = fopen ( $xsxlFile, "r" )) !== FALSE) {
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
				foreach ( $objPHPExcel->getWorksheetIterator () as $worksheet ) {
					
					$worksheetTitle = $worksheet->getTitle ();
					if ($worksheetTitle != 'Information') {
						$highestRow = $worksheet->getHighestRow (); // e.g. 10
						$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
						$nrColumns = ord ( $highestColumn ) - 64;
						$html = "";
						$html .= '<div class="reportTable">
		 <table id="claim_requested">
                           <thead>
                              <tr>
                              <th>EmployeeId</th>
                              <th>EmployeeName</th>';
						$orginalHeader = array (
								'Employee Id',
								'Employee Name' 
						);
						foreach ( $leaveRules as $comd ) {
							$html .= "<th>Allotted " . $comd ['leave_rule_id'] . "</th>";
							array_push ( $orginalHeader, "Alloted " . $comd ['leave_rule_id'] );
						}
						foreach ( $leaveRules as $comd ) {
							$html .= "<th>" . $comd ['leave_rule_id'] . "</th>";
							array_push ( $orginalHeader, $comd ['rule_name'] );
						}
						
						$html .= '<th>Comoff</th><th>LOP</th></tr></thead><tbody>';
						array_push ( $orginalHeader, 'COMPOFF', 'LOP' );
						for($row = 2; $row <= 2; ++ $row) {
							$headerAray = array ();
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								isset ( $val ) ? array_push ( $headerAray, $val ) : "";
							}
						}
						if ($orginalHeader === array_intersect ( $orginalHeader, $headerAray ) && $headerAray === array_intersect ( $headerAray, $orginalHeader )) {
							for($row = 3; $row <= $highestRow; ++ $row) {
								
								if ($row % 2 == 0) {
									$html .= "<tr  class='alt' style='width:15%'>";
								} else {
									$html .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$html .= isset ( $val ) ? "<td>" . $val . "</td>" : "";
								}
							}
							$html .= "</tbody></table></div>";
						} else {
							$html = "";
							$html = "error";
						}
					}
				}
				
				return $html;
			}
		} else {
			return "File Cant Be Empty";
		}
	}
	//Download 
	function AttendanceTemplateDownload($leaveRules) {
		$activeSheet = new PHPExcel ();
		$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ( 'A1:B1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'A1:B1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "Attendance for ".$_SESSION ['fywithMonth'] );
		$activeSheet->getActiveSheet ()->getStyle ( "A1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->setTitle ( $_SESSION ['fywithMonth'] );
		$ro=$this->ro;
		$queryStmt = "";
		$comid = "";
		$queryStmt2 = "";
		
		$activeSheet->getActiveSheet ()->setCellValue ( 'A2', 'Employee Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B2', 'Employee Name' );
		for($col = 'A'; $col !== 'Z'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$k = 0;
		$activeSheet->getActiveSheet ()->getProtection ()->setPassword ( 'Bass2015&' );
		$activeSheet->getActiveSheet ()->getProtection ()->setSheet ( true );
		
		$counProtect = 3;
		foreach ( $leaveRules as $comd ) {
			$activeSheet->getActiveSheet ()->setCellValue ( $ro [$k], "Alloted " . $comd ['leave_rule_id'] );
			$k ++;
			$queryStmt .= "  MAX(CASE WHEN ac.leave_rule_id = '" . $comd ['leave_rule_id'] . "' THEN ac.allotted+ac.opening_bal ELSE 0 END) av_" . $comd ['leave_rule_id'] . ",";
			$counProtect ++;
		}
		
		foreach ( $leaveRules as $comd ) {
			$activeSheet->getActiveSheet ()->setCellValue ( $ro [$k], $comd ['rule_name'] );
			$k ++;
			$queryStmt2 .= "0 " . $comd ['leave_rule_id'] . ",";
		}
		
		$activeSheet->getActiveSheet ()->setCellValue ( $ro [$k], "COMPOFF" );
		$k ++;
		$activeSheet->getActiveSheet ()->setCellValue ( $ro [$k], "LOP" );
		$leave_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
		$result = mysqli_query ( $this->conn, "SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,$queryStmt $queryStmt2
												0 compoff, 
												0 lop
												FROM employee_work_details w
												LEFT JOIN emp_leave_account ac ON w.employee_id = ac.employee_id
												AND ac.`year` = '$leave_year'
												WHERE  w.enabled = 1
												GROUP BY w.employee_id" );
		$rowCount = 3;
		
		$excelRowset=$this->excelRowset;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$columCount = 2;
			foreach ( $row as $index ) {
				$activeSheet->getActiveSheet ()->setCellValue ( $excelRowset [$columCount] . $rowCount, $index );
				if ($columCount > $counProtect) {
					$activeSheet->getActiveSheet ()->getStyle ( $excelRowset [$columCount] . $rowCount )->getProtection ()->setLocked ( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );
					if ($columCount % 2 == 0) {
						$activeSheet->getActiveSheet ()->getStyle ( $excelRowset [$columCount] . $rowCount )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
					} else {
						$activeSheet->getActiveSheet ()->getStyle ( $excelRowset [$columCount] . $rowCount )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
					}
				}
				$columCount ++;
			}
			
			$rowCount ++;
		}
		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 1 );
		$activeSheet->getActiveSheet ()->setTitle ( 'Information' );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'C1:D1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'C1:D1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C1', "Leave Rule Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C2', "LeaveRuleID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D2', "LeaveRule Name" );
		
		$result = mysqli_query ( $this->conn, "SELECT leave_rule_id,rule_name FROM company_leave_rules WHERE  enabled=1 " );
		
		$i = 3;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'D' . $i, $row ['rule_name'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'C' . $i, $row ['leave_rule_id'] );
			$i ++;
		}
		$activeSheet->getActiveSheet ()->getStyle ( "C1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->getStyle ( "C2:D2" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		
		for($col1 = 'A'; $col1 !== 'E'; $col1 ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col1 )->setAutoSize ( true );
		}
		
		$filname = 'Attendance_'.($_SESSION ['fywithMonth']).'.xls';
		header ( "Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
		header ( "Content-Disposition: attachment; filename=$filname" );
		header ( "Cache-Control: max-age=0" );
		$objWriter = PHPExcel_IOFactory::createWriter ( $activeSheet, 'Excel5' );
		$objWriter->save ( 'php://output' );
		exit ();
	}
	// Download Excel
	function ORGSTRTemplateDownload() {
		$activeSheet = new PHPExcel ();
		$activeSheet->setActiveSheetIndex ( 0 );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', 'Branch Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B1', 'Branch Location' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C1', 'City Pincode' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D1', 'State' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'E1', 'City' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'F1', 'PT SlabName ' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'G1', 'PF NO' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'H1', 'ESI NO' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'I1', 'PT NO' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'J1', 'TAN NO' );
		
		$activeSheet->getActiveSheet ()->setTitle ( 'Branch' );
		for($col = 'A'; $col !== 'Q'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 1 );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', 'Department Name' );
		$activeSheet->getActiveSheet ()->setTitle ( 'Department' );
		for($col = 'A'; $col !== 'B'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 2 );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', 'Designation Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B1', 'Designation Hierarchy' );
		$activeSheet->getActiveSheet ()->setTitle ( 'Designation' );
		for($col = 'A'; $col !== 'C'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 3 );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', 'Status Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B1', 'Status Comments' );
		$activeSheet->getActiveSheet ()->setTitle ( 'Job Status' );
		for($col = 'A'; $col !== 'C'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 4 );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', 'Payment Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B1', 'Bank Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C1', 'Account No' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D1', 'Branch Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'E1', 'IFSC Code' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'F1', 'Account Type' );
		$activeSheet->getActiveSheet ()->setTitle ( 'Payments Mode' );
		for($col = 'A'; $col !== 'G'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 5 );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', 'Team Name' );
		$activeSheet->getActiveSheet ()->setTitle ( 'Team' );
		for($col = 'A'; $col !== 'B'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 6 );
		$activeSheet->getActiveSheet ()->setTitle ( 'Information' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "Pt SlabName Must be Based on Below Content In Branch:" );
		
		$result = mysqli_query ( $this->conn, "SELECT DISTINCT pt_city FROM pt_slab WHERE fin_year='" . $_SESSION ['financialYear'] . "' " );
		
		$i = 3;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'A' . $i, $row ['pt_city'] );
			$i ++;
		}
		
		$activeSheet->getActiveSheet ()->setCellValue ( 'C1', "Payment Account Type:" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C3', "Current" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C4', "Cash Credit" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C5', "Cash" );
		
		$activeSheet->getActiveSheet ()->getStyle ( 'A1:C1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		
		for($col = 'A'; $col !== 'D'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		$filname = 'ORGSTR.xls';
		header ( "Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
		header ( "Content-Disposition: attachment; filename=$filname" );
		header ( "Cache-Control: max-age=0" );
		$objWriter = new PHPExcel_Writer_Excel5($activeSheet);
		$objWriter->save ( 'php://output' );
		exit ();
	}
	//Upload 
	function ORGSTRTemplateUpload($xsxlFile) {
		if ($xsxlFile) {
			if (($getfile = fopen ( $xsxlFile, "r" ))) {
				$branchRow = "";
				$departRow = "";
				$desigRow = "";
				$jobRow = "";
				$paymentRow = "";
				$teamRow = "";
				$queryStmt = "";
				
				//$fileType = PHPExcel_IOFactory::identify ( $xsxlFile );
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
				
				
				$orgstrArray = array ();
				$desigRow = "";
				$jobRow = "";
				$paymentRow = "";
				$branchRow = "";
				$departRow = "";
				$orginalHeader = array ();
				foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
					array_push ( $orginalHeader, $worksheet->getTitle () );
				}
				$headerAray = array (
						'Branch',
						'Department',
						'Designation',
						'Job Status',
						'Payments Mode',
						'Team',
						'Information' 
				);
				if ($orginalHeader === array_intersect ( $orginalHeader, $headerAray ) && $headerAray === array_intersect ( $headerAray, $orginalHeader )) {
					
					foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
						$worksheetTitle = $worksheet->getTitle ();
						$highestRow = $worksheet->getHighestDataRow (); // e.g. 10
						$highestColumn = $worksheet->getHighestDataColumn (); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
						$nrColumns = ord ( $highestColumn ) - 64;
						
						if ($worksheetTitle == 'Branch') {
							$branchRow .= '<div class="reportTable"><table><thead><tr><th>Branch Name</th><th>Branch Location</th><th>City Pincode</th><th>State</th><th>City</th><th>PT SlabName</th><th>PF NO</th><th>ESI NO</th><th>PT NO</th><th>TAN NO</th></tr></thead><tbody>';
							for($row = 2; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$branchRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$branchRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$branchRow .= "<td>" . $val . "</td>";
								}
							}
							$branchRow .= "</tbody></table></div>";
						} else if ($worksheetTitle == 'Department') {
							$departRow .= '<div class="reportTable"><table><thead><tr><th>Department Name</th></tr></thead><tbody>';
							for($row = 2; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$departRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$departRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$departRow .= "<td>" . $val . "</td>";
								}
							}
							$departRow .= "</tbody></table></div>";
						} else if ($worksheetTitle == 'Designation') {
							$desigRow .= '<div class="reportTable"><table><thead><tr><th>Designation Name</th><th>Designation Name</th></tr></thead><tbody>';
							for($row = 2; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$desigRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$desigRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$desigRow .= "<td>" . $val . "</td>";
								}
							}
							$desigRow .= "</tbody></table></div>";
						} else if ($worksheetTitle == 'Job Status') {
							$jobRow .= '<div class="reportTable"><table><thead><tr><th>Status Name</th><th>Status Comments</th></tr></thead><tbody>';
							for($row = 2; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$jobRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$jobRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$jobRow .= "<td>" . $val . "</td>";
								}
							}
							$jobRow .= "</tbody></table></div>";
						} else if ($worksheetTitle == 'Payments Mode') {
							$paymentRow .= '<div class="reportTable"><table><thead><tr><th>Payment Name</th><th>Bank Name</th><th>Account No</th><th>Branch Name</th><th>IFSC Code</th><th>Account Type</th></tr></thead><tbody>';
							for($row = 2; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$paymentRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$paymentRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$paymentRow .= "<td>" . $val . "</td>";
								}
							}
							$paymentRow .= "</tbody></table></div>";
						}
						else if ($worksheetTitle == 'Team') {
							$teamRow .= '<div class="reportTable"><table><thead><tr><th>Team Name</th></tr></thead><tbody>';
							for($row = 2; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$teamRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$teamRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$teamRow .= "<td>" . $val . "</td>";
								}
							}
							$teamRow .= "</tbody></table></div>";
						}
					}
					
					array_push ( $orgstrArray, $branchRow, $departRow, $desigRow, $jobRow, $paymentRow , $teamRow );
				} else {
					$orgstrArray = "error";
				}
				return $orgstrArray;
			}
		} else {
			return "File Cant Be Empty";
		}
	
	}
	//final upload of downloaded file
	function ORGSTRProcessUploadedFile($xsxlFile) {
		if ($xsxlFile) {
			if (($getfile = fopen ( $xsxlFile, "r" ))) {
				$queryStmt = "";
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
				
				$branchArray = array (
						'branch_name',
						'branch_addr',
						'city_pin',
						'state',
						'city',
						'pt_city_id',
						'pf_no',
						'esi_no',
						'pt_no',
						'tan_no' 
				);
				$paymentArray = array (
						'payment_mode_name',
						'bank_name',
						'bank_ac_no',
						'bank_branch',
						'bank_ifsc',
						'account_type' 
				);
				$missedinsert = array ();
				foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
					$worksheetTitle = $worksheet->getTitle ();
					$highestRow = $worksheet->getHighestRow (); // e.g. 10
					$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
					$nrColumns = ord ( $highestColumn ) - 64;
					if ($worksheetTitle == 'Branch') {
						
						for($row = 2; $row <= $highestRow; ++ $row) {
							$branchRow = "";
							$rand = mt_rand ( 10000, 99999 );
							$branch_id = "BR" . $rand;
							$branchRow .= "INSERT INTO company_branch SET branch_id='" . $branch_id . "',enabled=1,updated_by='" . $_SESSION ['login_id'] . "',resp_person_emp_id='NIL',";
							$duplicateStr = "";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								if ($col == 9) {
									$branchRow .= $branchArray [$col] . "='" . $val . "' ON DUPLICATE KEY UPDATE " . $duplicateStr . $branchArray [$col] . "='" . $val . "' ;";
								} else {
									$branchRow .= $branchArray [$col] . "='" . $val . "',";
									$duplicateStr .= $branchArray [$col] . "='" . $val . "',";
								}
							}
							if (! mysqli_query ( $this->conn, $branchRow )) {
								array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
							}
						}
					} else if ($worksheetTitle == 'Department') {
						for($row = 2; $row <= $highestRow; ++ $row) {
							$departRow = "";
							$rand = mt_rand ( 10000, 99999 );
							$department_id = "DP" . $rand;
							$departRow .= "INSERT INTO company_departments SET department_id='" . $department_id . "',enabled=1,updated_by='" . $_SESSION ['login_id'] . "',";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								$departRow .= "department_name='" . $val . "'";
								$departRow .= " ON DUPLICATE KEY UPDATE department_name='" . $val . "';";
							}
							if (! mysqli_query ( $this->conn, $departRow )) {
								array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
							}
						}
					} else if ($worksheetTitle == 'Designation') {
						for($row = 2; $row <= $highestRow; ++ $row) {
							$desigRow = "";
							$desigRow1 = "";
							$rand = mt_rand ( 10000, 99999 );
							$designation_id = "DS" . $rand;
							$desigRow .= "INSERT INTO company_designations SET designation_id='" . $designation_id . "',enabled=1,updated_by='" . $_SESSION ['login_id'] . "',";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								if ($col == 1) {
									$desigRow .= "designation_hierarchy='" . $val . "' ON DUPLICATE KEY UPDATE " . $desigRow1 . "designation_hierarchy='" . $val . "' ;";
								} else {
									$desigRow .= "designation_name='" . $val . "',";
									$desigRow1 .= "designation_name='" . $val . "',";
								}
							}
							if (! mysqli_query ( $this->conn, $desigRow )) {
								array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
							}
						}
					} else if ($worksheetTitle == 'Job Status') {
						for($row = 2; $row <= $highestRow; ++ $row) {
							$jobRow = "";
							$jobRow1 = "";
							$rand = mt_rand ( 10000, 99999 );
							$status_id = "JS" . $rand;
							$jobRow .= "INSERT INTO company_job_statuses SET status_id='" . $status_id . "',enabled=1,updated_by='" . $_SESSION ['login_id'] . "',";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								if ($col == 1) {
									$jobRow .= "status_comments='" . $val . "' ON DUPLICATE KEY UPDATE " . $jobRow1 . "status_comments='" . $val . "';";
								} else {
									$jobRow .= "status_name='" . $val . "',";
									$jobRow1 .= "status_name='" . $val . "',";
								}
							}
							if (! mysqli_query ( $this->conn, $jobRow )) {
								array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
							}
						}
					} else if ($worksheetTitle == 'Payments Mode') {
						for($row = 2; $row <= $highestRow; ++ $row) {
							$paymentRow = "";
							$paymentRow1 = "";
							$rand = mt_rand ( 10000, 99999 );
							$payment_mode_id = "PM" . $rand;
							$paymentRow .= "INSERT INTO company_payment_modes SET payment_mode_id='" . $payment_mode_id . "',enabled=1,updated_by='" . $_SESSION ['login_id'] . "',";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								if ($col == 5) {
									$paymentRow .= $paymentArray [$col] . "='" . $val . "' ON DUPLICATE KEY UPDATE " . $paymentRow1 . $paymentArray [$col] . "='" . $val . "' ;";
								} else {
									$paymentRow .= $paymentArray [$col] . "='" . $val . "',";
									$paymentRow1 .= $paymentArray [$col] . "='" . $val . "',";
								}
							}
							if (! mysqli_query ( $this->conn, $paymentRow )) {
								array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
							}
						}
					} else if ($worksheetTitle == 'Team') {
						for($row = 2; $row <= $highestRow; ++ $row) {
							$teamRow = "";
							$rand = mt_rand ( 10000, 99999 );
							$team_id= "TM" . $rand;
							$teamRow .= "INSERT INTO company_team SET team_id='" . $team_id . "',enabled=1,updated_by='" . $_SESSION ['login_id'] . "',";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								$teamRow .= "team_name='" . $val . "'";
								$teamRow .= " ON DUPLICATE KEY UPDATE team_name='" . $val . "';";
							}
							if (! mysqli_query ( $this->conn, $teamRow)) {
								array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
							}
						}
					}
				}
				if (empty ( $missedinsert )) {
					$result = true;
				} else {
					$result = $missedinsert;
				}
				return $result;
			}
		} else {
			return "File Cant Be Empty";
		}
	}
	//Download file
	function EmployeeTemplateDownload($leaveRules) {
		$activeSheet = new PHPExcel ();
		$activeSheet->setActiveSheetIndex ( 0 );
		$activeSheet->getActiveSheet ()->setTitle ( 'Employee Add' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A2', 'Employee Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B2', 'First Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C2', 'Second Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D2', 'Date of Birth' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'E2', 'Fathers Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'F2', 'Gender' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'G2', 'Martial Status' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'H2', 'Phone No' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'I2', 'Mobile No' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'J2', 'Email Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'K2', 'Personal Mobile' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'L2', 'Personal Email' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'M2', 'House Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'N2', 'Street Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'O2', 'Area Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'P2', 'Pin Code' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'Q2', 'City' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'R2', 'State' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'S2', 'Country' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'T2', 'PAN Number' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'U2', 'Aadhaar No' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'V2', 'Bank Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'W2', 'Bank A/c No' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'X2', 'IFSC Code' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'Y2', 'Bank Branch' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'Z2', 'Job StatusID' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AA2', 'Date of Join' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AB2', 'Probation Period' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AC2', 'Confirmation Date' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AD2', 'Notice Period' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AE2', 'Designation Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AF2', 'Department Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AG2', 'Branch Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AH2', 'Team Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AI2', 'Reporting Manager' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AJ2', 'PF Number' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AK2', 'UAN Number' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AL2', 'ESI Number' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'AM2', 'PF Limit');
		$activeSheet->getActiveSheet ()->setCellValue ( 'AN2', 'Salary Type');
		$activeSheet->getActiveSheet ()->setCellValue ( 'AO2', 'CTC');
		$rows = array (
				'AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT');
		$rowcount = 0;
		$count = 2;
		
		Session::newInstance ()->_setMiscPayParams ();
		$miscAllArray = Session::newInstance ()->_get ( "miscPayParams" );
		foreach ( $miscAllArray ['MP'] as $allow ) {
			$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount] . $count, $allow ['display_name'] );
			$rowcount ++;
		}
		$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount] . $count, 'CTC FIXED Components');
		$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount+1] . $count, 'Slab ID' );
		$rowcount ++;
		$variableEnd= $rows [$rowcount] . "1";
		Session::newInstance ()->_setGeneralPayParams ();
		$salryHeader=$rows [$rowcount+1] . "1";
		$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
		foreach ( $allowDeducArray ['A'] as $allow ) {
			$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount+1] . $count, $allow ['display_name'] );
			$rowcount ++;
		}
		$rowcount ++;
		$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount] . $count, 'Gross Salary' );
		$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount + 1] . $count, 'Payment ID' );
		$salryHeadingEnd = $rows [$rowcount + 1] . "1"; // For Heading Merge Cells
		if (count($leaveRules) > 0) {
			$leaveStart = $rows [$rowcount + 2] . "1";
			foreach ( $leaveRules as $comd ) {
				$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount + 2] . $count, $comd ['rule_name'] );
				$rowcount ++;
			}
			$leaveEnd = $rows [$rowcount + 1] . "1";
		}
		if ($_SESSION ['payroll_flag'] != 1) {
			$activeSheet->getActiveSheet ()->setCellValue ( $rows [$rowcount + 2] . $count, "TDS Amount" );
			$rowcount ++;
		}
		
		for($col1 = 'A'; $col1 !== $rows [$rowcount + 2]; $col1 ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col1 )->setAutoSize ( true );
		}
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', 'Personal Details' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'X1', 'Work Details');
		$activeSheet->getActiveSheet ()->setCellValue ( 'AK1', 'Variable Components');
		$activeSheet->getActiveSheet ()->setCellValue ( $salryHeader, 'Salary Details');
		
	
		
		$activeSheet->getActiveSheet ()->freezePane ( 'D2' );
		$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ( 'A1:W1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'A1:W1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ( 'X1:AJ1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'X1:AJ1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ('AK1:' . $variableEnd );
		$activeSheet->getActiveSheet ()->getStyle ('AK1:'. $variableEnd )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		
	    $activeSheet->setActiveSheetIndex ( 0 )->mergeCells ($salryHeader.':' . $salryHeadingEnd );
		$activeSheet->getActiveSheet ()->getStyle ($salryHeader.':'. $salryHeadingEnd )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		
		$activeSheet->getActiveSheet ()->getStyle ( 'A1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		$activeSheet->getActiveSheet ()->getStyle ( 'X1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->getStyle ( 'AK1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		$activeSheet->getActiveSheet ()->getStyle ( $salryHeader )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		
		$activeSheet->getDefaultStyle ()->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		
		if (count($leaveRules) > 0) {
			$activeSheet->getActiveSheet ()->setCellValue ( $leaveStart, 'Opening Balance' );
			$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ( $leaveStart . ":" . $leaveEnd );
			$activeSheet->getActiveSheet ()->getStyle ( $leaveStart . ":" . $leaveEnd )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$activeSheet->getActiveSheet ()->getStyle ( $leaveStart )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		}
		$activeSheet->setActiveSheetIndex ( 0 );		
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 1 );
		$activeSheet->getActiveSheet ()->setTitle ( 'Information' );
		//Instruction
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A1:M1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'A1:M1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "General Instructions" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A2:M2');
		$activeSheet->getActiveSheet ()->setCellValue ( 'A2', "1. Make sure you have created Job Statuses,Designations,Departments,Branches,Salary Slabs,Payment Modes before Importing" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A3:M3' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A3', "2. Date of Birth,Date of Join,Confirmation Date  must be in dd-mm-yyyy Format" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A4:M4' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A4', "3. Phone,Mobile Number must be in 10 Digits,Pincode allowed Only 6 Digits" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A5:M5' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A5', "4. Gender must be From (Male,Female,Trans),Marital Status must be From (Single,Married),Salary Type must be From (ctc,monthly)" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A6:M6' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A6', "5. Refer the below tables for the columns Bank Name,Job StatusID,Designation Id,Department Id,Branch Id,Slab ID,Payment ID" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A7:M7');
		$activeSheet->getActiveSheet ()->setCellValue ( 'A7', "6. Probation Period must be given in Days i.e 90 for 3 months" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A8:M8' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A8', "7. PF Limit values are 0,1 i.e 1 for limit 0 for no limit" );
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A9:M9' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A9', "8. Reporting Manager must be given as the Employee ID of the manager , Leave as blank if there is no reporting manager" );
		
		$activeSheet->getActiveSheet ()->setCellValue ( 'A13', "Bank Name" );
		$bankNae = array (
				'Axis Bank',
				'City Union Bank Ltd',
				'HDFC Bank',
				'ICICI Bank',
				'IndusInd Bank',
				'Indian Overseas Bank',
				'Karur Vysya Bank',
				'Kotak Mahindra Bank',
				'Indian Bank',
				'State Bank Of India',
				'Standard Chartered',
				'State Bank Of Travancore',
				'TMB Bank Ltd',
				'Union Bank Of India',
				'Yes Bank' 
		);
		$activeSheet->getActiveSheet ()->getStyle ( "A13" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$i = 14;
		foreach ( $bankNae as $key ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'A' . $i, $key );
			$activeSheet->getActiveSheet ()->getStyle ( "A" . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			$i ++;
		}
		// Job Satatus Details
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'C13:D13' );
		$activeSheet->getActiveSheet ()->getStyle ( 'C13:D13' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C13', "Job Status" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C14', "Job Status ID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D14', "Job Status Name" );
		$result = mysqli_query ( $this->conn, "SELECT status_id,status_name FROM company_job_statuses WHERE  enabled=1 " );
		$activeSheet->getActiveSheet ()->getStyle ( "C14:D14" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		$i = 14;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'C' . $i, $row ['status_id'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'D' . $i, $row ['status_name'] );
			$activeSheet->getActiveSheet ()->getStyle ( 'C' . $i . ":" . 'D' . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
			$i ++;
		}
		
		// Designmation
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'F13:G13' );
		$activeSheet->getActiveSheet ()->getStyle ( 'F13:G13' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'F13', "Designation Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'F14', "Designation ID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'G14', "Designation Name" );
		$activeSheet->getActiveSheet ()->getStyle ( "F14:G14" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		
		$result = mysqli_query ( $this->conn, "SELECT designation_id,designation_name FROM company_designations WHERE  enabled=1 " );
		
		$i = 14;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'F' . $i, $row ['designation_id'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'G' . $i, $row ['designation_name'] );
			$activeSheet->getActiveSheet ()->getStyle ( "F" . $i . ":" . "G" . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			$i ++;
		}
		// Departmnet Deatails
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'I13:J13' );
		$activeSheet->getActiveSheet ()->getStyle ( 'I13:J13' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'I13', "Department Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'I14', "Department ID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'J14', "Department Name" );
		$activeSheet->getActiveSheet ()->getStyle ( "I14:J14" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		
		$result = mysqli_query ( $this->conn, "SELECT department_id,department_name FROM company_departments WHERE  enabled=1 " );
		
		$i = 14;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'I' . $i, $row ['department_id'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'J' . $i, $row ['department_name'] );
			$activeSheet->getActiveSheet ()->getStyle ( "I" . $i . ":" . "J" . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
			$i ++;
		}
		
		// Branch Deatails
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'L13:M13' );
		$activeSheet->getActiveSheet ()->getStyle ( 'L13:M13' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'L13', "Branch Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'L14', "Branch ID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'M14', "Branch Name" );
		$activeSheet->getActiveSheet ()->getStyle ( "L14:M14" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		
		$result = mysqli_query ( $this->conn, "SELECT branch_id,branch_name FROM company_branch WHERE  enabled=1 " );
		$misc=array();
		
		$i = 14;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'L' . $i, $row ['branch_id'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'M' . $i, $row ['branch_name'] );
			$activeSheet->getActiveSheet ()->getStyle ( "L" . $i . ":" . "M" . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			$i ++;
		}
		
		// Payment Details
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'O13:P13' );
		$activeSheet->getActiveSheet ()->getStyle ( 'O13:P13' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'O13', "Payment Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'O14', "Payment ID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'P14', "Payment Name" );
		$activeSheet->getActiveSheet ()->getStyle ( "O14:P14" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		
		$result = mysqli_query ( $this->conn, "SELECT payment_mode_id,payment_mode_name FROM company_payment_modes WHERE  enabled=1 " );
		
		$i = 14;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'O' . $i, $row ['payment_mode_id'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'P' . $i, $row ['payment_mode_name'] );
			$activeSheet->getActiveSheet ()->getStyle ( "O" . $i . ":" . "P" . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			$i ++;
		}
		
		// Team Details
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'R13:S13' );
		$activeSheet->getActiveSheet ()->getStyle ( 'R13:S13' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'R13', "Team Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'R14', "Team ID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'S14', "Team Name" );
		$activeSheet->getActiveSheet ()->getStyle ( "R14:S14" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		
		$result = mysqli_query ( $this->conn, "SELECT team_id,team_name FROM company_team WHERE  enabled=1 " );
		
		$i = 14;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'R' . $i, $row ['team_id'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'S' . $i, $row ['team_name'] );
			$activeSheet->getActiveSheet ()->getStyle ( "R" . $i . ":" . "S" . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			$i ++;
		}
		
		// slab Deatails
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'U13:V13' );
		$activeSheet->getActiveSheet ()->getStyle ( 'U13:V13' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'U13', "Slab Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'U14', "Slab ID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'V14', "Slab Type" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'W14', "Slab Name" );
		
		$columSlab = '';
		$comArray_s = array (
				'X',
				'Y',
				'Z',
				'AA',
				'AB',
				'AC',
				'AD',
				'AE',
				'AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS'
		);
		
		$j = 0;
		foreach ( $allowDeducArray ['A'] as $allow ) {
			$activeSheet->getActiveSheet ()->setCellValue ( $comArray_s [$j] . "14", $allow ['display_name'] );
			$columSlab .= $allow ['pay_structure_id'] . ",";
			$j ++;
		}
		$activeSheet->getActiveSheet ()->setCellValue ( $comArray_s [$j] . "14", "Min salary Amount" );
		
		$comArray = array (
				'U',
				'V',
				'W',
				'X',
				'Y',
				'Z',
				'AA',
				'AB',
				'AC',
				'AD',
				'AE',
				'AF','AG','AH','AI','AJ','AK','AL','AM' 
		);
		
		$result = mysqli_query ( $this->conn, "SELECT slab_id,slab_type,slab_name," . $columSlab . " min_salary_amount FROM company_allowance_slabs WHERE  enabled=1 " );
		$i = 15;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$j = 0;
			foreach ( $row as $_column ) {
				if ($j % 2 == 0) {
					$activeSheet->getActiveSheet ()->getStyle ( $comArray [$j] . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
				} else {
					$activeSheet->getActiveSheet ()->getStyle ( $comArray [$j] . $i )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
				}
				$activeSheet->getActiveSheet ()->setCellValue ( $comArray [$j] . $i, str_replace ( "R", "Remainig Amount", str_replace ( "Nil", "0", str_replace ( "|A", "", str_replace ( "|P", "%", $_column ) ) ) ) );
				$j ++;
			}
			$i ++;
		}
		
		$activeSheet->getActiveSheet ()->getStyle ( "U14:" . $comArray [$j] . "14" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		
		// $activeSheet->getActiveSheet()->getStyle("A1:O1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('83d8d2');
		// $activeSheet->getActiveSheet()->getStyle("A2:P2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('39b2a9');
		
		for($col1 = 'A'; $col1 !== 'AA'; $col1 ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col1 )->setAutoSize ( true );
		}
		
		$filname = 'EmployeeAdd.xls';
		header ( "Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
		header ( "Content-Disposition: attachment; filename=$filname" );
		header ( "Cache-Control: max-age=0" );
		//bjWriter = PHPExcel_IOFactory::createWriter ( $activeSheet, 'Excel5' );
		//$objWriter->save ( 'php://output' );
		$objWriter = new PHPExcel_Writer_Excel5($activeSheet);
		$objWriter->save ( 'php://output' );
		exit ();
	}
	//upload file
	function EmployeeTemplateUpload($xsxlFile, $leaveRules) {
		$json = array ();
		$personelDetailsArray = array ();
		$workDetailArray = array ();
		if (($getfile = fopen ( $xsxlFile, "r" )) !== FALSE) {
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($xsxlFile);
			foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
				$worksheetTitle = $worksheet->getTitle ();
				$highestRow = $worksheet->getHighestRow (); // e.g. 10
				$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
				$nrColumns = ord ( $highestColumn ) - 64;
				$val = "";
				$val .= '<div class="reportTable">
		 <table id="claim_requested">
                           <thead>
                              <tr>
                              <th>EmpId</th>
                              <th>Name</th>
                              <th>LastName</th>
                              <th>DOB</th>
                              <th>FathersName</th>
                              <th>Gender</th>
                              <th>MartialStatus</th>
                              <th>Phone</th>
                              <th>Mobile</th>
                              <th>EmailId</th>
							  <th>Personal Mobile</th>
                              <th>Personal Email</th>
							  <th>HouseName</th>
                              <th>Street</th>
                              <th>Area</th>
                              <th>PIN</th>
                              <th>City</th>
                              <th>State</th>
                              <th>Country</th>
                              <th>PAN</th>
                              <th>Aadhaar</th>
                              <th>BankName</th>
                              <th>AccNo</th>
                              <th>IFSC</th>
                              <th>BankBranch</th>
                              <th>JobStatus</th>
                              <th>DOJ</th>
                              <th>Probation</th>
                              <th>ConfirmationDate</th>
                              <th>NoticePeriod</th>
                              <th>DesigID</th>
                              <th>DepartID</th>
                              <th>BranchID</th>
							  <th>TeamID</th>
                              <th>Report-Manager</th>
                              <th>PF</th>
                              <th>UAN</th>
                              <th>ESI</th>
                              <th>PFLimit</th>
						     <th>Salary Type</th>
						     <th>CTC</th>';
				$columCountcheck = 41;
				Session::newInstance ()->_setMiscPayParams ();
				$miscAllArray = Session::newInstance ()->_get ( "miscPayParams" );
				foreach ( $miscAllArray ['MP'] as $allow ) {
					$val .= "<th>" . $allow ['alias_name'] . "</th>";
					$columCountcheck ++;
				}
				$columCountcheck = $columCountcheck + 2;
			    $val .= '<th>CTC FIXED Components</th><th>Slab Id</th>';
			    Session::newInstance ()->_setGeneralPayParams ();
				$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
				
				foreach ( $allowDeducArray ['A'] as $allow ) {
					$val .= "<th>" . $allow ['alias_name'] . "</th>";
					$columCountcheck ++;
				}
				$columCountcheck = $columCountcheck + 2;
				$val .= '<th>Gross</th><th>PaymentId</th>';
				if (is_array ( $leaveRules )) {
					foreach ( $leaveRules as $comd ) {
						$val .= "<th>" . $comd ['rule_name'] . "</th>";
						$columCountcheck ++;
					}
				}
				if ($_SESSION ['payroll_flag'] != 1) {
					$val .= "<th>TDS Amount</th>";
					$columCountcheck ++;
				}
				$val .= '</tr></thead><tbody>';
				if ($worksheetTitle) {
					if ($columCountcheck == $highestColumnIndex) {						
						for($row = 3; $row <= $highestRow; ++ $row) {
							if ($row % 2 == 0) {
								$val .= "<tr  class='alt' style='width:15%'>";
							} else {
								$val .= '<tr style="width:15%">';
							}
							
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$value = $cell->getValue ();
								$data = $worksheet->getCellByColumnAndRow ( $col, $row )->getValue ();
								if (PHPExcel_Shared_Date::isDateTime ( $worksheet->getCellByColumnAndRow ( $col, $row ) )) {
									$value = date ( $format = "d/m/Y", PHPExcel_Shared_Date::ExcelToPHP ( $value ) );
								}
								$val .= "<td>" . $value . "</td>";
							}
							$val .= "</tr>";
						}
						$val .= "</tbody></table></div>";
				   } else {
						$val = "";
						$val = "error";
					}
					
					return $val;
				}
			}
		} else {
			return "File Cant Be Empty";
		}
	}
	//final upload of the downloaded file!!!
	function EmployeeProcessUploadedFile($xsxlFile, $leaveRules) {
		$missedEmployee = array ();
		$employeeArray = array (
				'D_employee_id',
				'A_employee_name',
				'A_employee_lastname',
				'B_employee_dob',
				'B_employee_father_name',
				'B_employee_gender',
				'B_employee_marital_status',
				'B_employee_phone',
				'B_employee_mobile',
				'B_employee_email',
				'B_employee_personal_mobile',
				'B_employee_personal_email',
				'B_employee_build_name',
				'B_employee_street',
				'B_employee_area',
				'B_employee_pin_code',
				'B_employee_city',
				'B_employee_state',
				'B_emp_country',
				'B_employee_pan_no',
				'B_employee_aadhaar_id',
				'B_employee_bank_name',
				'B_employee_acc_no',
				'B_employee_bank_ifsc',
				'B_employee_bank_branch',
				'A_status_id',
				'A_employee_doj',
				'A_employee_probation_period',
				'A_employee_confirmation_date',
				'A_notice_period',
				'A_designation_id',
				'A_department_id',
				'A_branch_id',
				'A_team_id',
				'A_employee_reporting_person',
				'A_employee_emp_pf_no',
				'A_employee_emp_uan_no',
				'A_employee_emp_esi_no',
				'C_pf_limit',
				'C_salary_type','C_ctc'
		);
		Session::newInstance ()->_setMiscPayParams ();
		$miscAllArray = Session::newInstance ()->_get ( "miscPayParams" );
		Session::newInstance ()->_setGeneralPayParams ();
		$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
		foreach ($miscAllArray['MP'] as $allow ) {
			array_push ( $employeeArray, "C_" . $allow ['pay_structure_id'] );
		}
		array_push ( $employeeArray, "ctc_fixed_component" ,"C_slab_id");
		foreach ($allowDeducArray['A'] as $allow ) {
			array_push ( $employeeArray, "C_" . $allow ['pay_structure_id'] );
		}
		array_push ( $employeeArray, "C_employee_salary_amount","A_payment_mode_id" );
		
		if (is_array ( $leaveRules )) {
			foreach ( $leaveRules as $comd ) {
				array_push ( $employeeArray, "E_" . $comd ['leave_rule_id'] );
			}
		}
		
		if ($_SESSION ['payroll_flag'] !== 1) {
			array_push ( $employeeArray, "F_old_tax_paid" );
		}
		
		if (($getfile = fopen ( $xsxlFile, "r" )) !== FALSE) {
		
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($xsxlFile);
			
			foreach ( $objPHPExcel->getWorksheetIterator () as $worksheet ) {
				
				$worksheetTitle = $worksheet->getTitle ();
				$highestRow = $worksheet->getHighestRow (); // e.g. 10
				$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
				$nrColumns = ord ( $highestColumn ) - 64;
				
				for($row = 3; $row <= $highestRow; ++ $row) {
					$employeeGender = "";
					$employeeDoj = "";
					$employeeCondate = "";
					$employeeDob = "";
					$Mixed_array = array ();
					for($col = 0; $col < $highestColumnIndex; ++ $col) {
						$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
						$value = $cell->getValue ();
						$data = $worksheet->getCellByColumnAndRow ( $col, $row )->getValue ();
						if (PHPExcel_Shared_Date::isDateTime ( $worksheet->getCellByColumnAndRow ( $col, $row ) )) {
							$value = date ( $format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP ( $value ) );
						}
						if ($employeeArray [$col] == 'B_employee_gender') {
							if ($value == "Male" || $value == "male" || $value == "MALE") {
								$Gender = '%Male%';
							} elseif ($value == "Female" || $value == "female" || $value == "FEMALE") {
								$Gender = '%Female%';
							} elseif ($value == "Trans" || $value == "TRANS" || $value == "trans") {
								$Gender = '%Trans';
							}
							$employeeGender = "\\'" . $Gender . "\\'";
						}
						if ($employeeArray [$col] == 'A_employee_doj') {
							$employeeDoj = date ( "d/m/Y", strtotime ( $value ) );
						}
						if ($employeeArray [$col] == 'A_employee_name') {
							$employeeName = $value;
						}
						
						if ($employeeArray [$col] == 'D_employee_id') {
							$empid = $value;
						}
						if ($employeeArray [$col] == 'A_employee_confirmation_date') {
							$employeeCondate = date ( "d/m/Y", strtotime ( $value ) );
						}
						if ($employeeArray [$col] == 'B_employee_dob') {
							$employeeDob = str_replace ( "/", "", date ( "d/m/Y", strtotime ( $value ) ) );
							$employeeDob = password_hash($employeeDob, PASSWORD_BCRYPT);
						}
					$Mixed_array [] = $employeeArray [$col] . "='" .( ($employeeArray [$col] =='B_employee_bank_name')?str_replace(' ','_',$value):$value ). "',";
					}
					$leave_year_end_date = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['nextyear_date'] : "01/01/" . ($_SESSION ['payrollYear'] + 1);
					$leave_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
					$workddetails = "";
					$salrydetails = "";
					$personeldetails = "";
					$employee_id = "";
					$leaveDetils = "";
					$tdsupdate = "";
					$Grouped_array = array ();
					$personeldetails .= "INSERT INTO employee_personal_details SET  employee_password='$employeeDob',";
					$workddetails .= "INSERT INTO employee_work_details SET ";
					$salrydetails .= "INSERT INTO employee_salary_details SET ";
					
					foreach ( $Mixed_array as $one_word ) {
						$firstone = substr ( $one_word, 0, 1 );
						if ($firstone == 'A') {
							$workddetails .= substr ( $one_word, 2 );
						} else if ($firstone == 'B') {
							$personeldetails .= substr ( $one_word, 2 );
						} else if ($firstone == 'C') {
							$salrydetails .= substr ( $one_word, 2 );
						} else if ($firstone == 'D') {
							$employee_id .= substr ( $one_word, 2 );
						} else if ($firstone == 'E') {
							$str = explode ( "=", substr ( $one_word, 2 ) );
							$leaveDetils .= "INSERT INTO `emp_leave_account` SET employee_id='" . $empid . "',`year`='" . $leave_year . "',leave_rule_id='" . $str [0] . "',opening_bal=" . $str [1] . " is_leavecredited=1,updated_by='" . $_SESSION ['login_id'] . "';";
						} else if ($firstone == 'F') {
							$str = explode ( "=", substr ( $one_word, 2 ) );
							$tdsupdate .= "UPDATE employee_income_tax SET old_tax_paid=" . $str [1] . "updated_by='" . $_SESSION ['login_id'] . "'  WHERE employee_id='$empid' AND year='" . $_SESSION ['financialYear'] . "'";
						}
					}
					
					mysqli_autocommit ( $this->conn, FALSE );
					try {
						mysqli_commit ( $this->conn );
						$workdStmt = mysqli_query ( $this->conn, $workddetails . $employee_id . "enabled=1;" );
						if(!$workdStmt)
							throw new Exception ( ":For " . $employee_id . mysqli_error ( $this->conn ) );
						$salryStmt = mysqli_query ( $this->conn, $salrydetails . $employee_id . "updated_by='" . $_SESSION ['login_id'] . "',effects_from=STR_TO_DATE('$employeeDoj','%d/%m/%Y');" );
						if(!$salryStmt)
							throw new Exception ( ":For " . $employee_id . mysqli_error ( $this->conn ) );
						$personStmt = mysqli_query ( $this->conn, $personeldetails . $employee_id . "employee_image='Nil',updated_by='" . $_SESSION ['login_id'] . "';" );
						if(!$personStmt)
							throw new Exception ( ":For " . $employee_id . mysqli_error ( $this->conn ) );
						if ($workdStmt && $salryStmt && $personStmt) {
							// leave Rule crdit Section
							if (is_array ( $leaveRules )) {
								mysqli_multi_query ( $this->conn, $leaveDetils );
								do {
									if ($result = mysqli_store_result ( $this->conn )) {
										mysqli_free_result ( $result );
									}
								} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
								
								$qurt = "CALL CREDITLEAVE_ONEMPLOYEEADD('" . $_SESSION ['current_payroll_month'] . "','" . $leave_year . "',STR_TO_DATE('$leave_year_end_date','%d/%m/%Y'),'$empid',STR_TO_DATE('$employeeDoj','%d/%m/%Y'),STR_TO_DATE('$employeeCondate','%d/%m/%Y'),'$employeeGender','" . $_SESSION ['login_id'] . "')";
								if (! mysqli_query ( $this->conn, $qurt ) ? TRUE : mysqli_error ( $this->conn )) {
									throw new Exception ( ":For " . $employee_id . mysqli_error ( $this->conn ) );
								}
							}
							
							$otherInsertionRow = "INSERT INTO `employee_it_declaration` SET employee_id='" . $empid . "',
					 				           `year`='" . $_SESSION ['financialYear'] . "',updated_by='" . $_SESSION ['login_id'] . "';
					 				           	INSERT INTO employee_income_tax SET employee_id='" . $empid . "',
					 				           `year`='" . $_SESSION ['financialYear'] . "',updated_by='" . $_SESSION ['login_id'] . "';
					 				           	INSERT INTO payroll_preview_temp SET employee_id='" . $empid . "',
					 				            updated_by='" . $_SESSION ['login_id'] . "'";
							if (! mysqli_multi_query ( $this->conn, $otherInsertionRow ) ? TRUE : mysqli_error ( $this->conn )) {
								throw new Exception ( ":For " . $employee_id . mysqli_error ( $this->conn ) );
							}
							
							do {
								if ($result = mysqli_store_result ( $this->conn )) {
									mysqli_free_result ( $result );
								}
							} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
							
							if ($_SESSION ['payroll_flag'] !== 1) {
								if (! mysqli_query ( $this->conn, $tdsupdate ) ? TRUE : mysqli_error ( $this->conn )) {
									throw new Exception ( ":For " . $employee_id . mysqli_error ( $this->conn ) );
								}
							}
							$target_dir = "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $empid;
							if (! file_exists ( $target_dir )) {
								if(!file_exists( "../../compDat/" . $_SESSION ['company_id'] ))
									mkdir ( "../../compDat/" . $_SESSION ['company_id'] );
									if(!file_exists("../../compDat/" . $_SESSION ['company_id'] . "/empDat" ))
										mkdir ( "../../compDat/" . $_SESSION ['company_id'] . "/empDat" );
										if(!file_exists( "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $empid ))
											mkdir ( "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $empid );
							}
							$result1 = true;
						} else {
							throw new Exception ( mysqli_error ( $this->conn ) );
						}
					} catch ( Exception $e ) {
						mysqli_rollback ( $this->conn );
						$errMsg = NULL;
						$errMsg = explode ( ':', $e->getMessage () );
						array_push ( $missedEmployee, "For " . $employeeName . " [ " . $empid . " ] " . $errMsg [1] );
						$result1 = implode ( "<br>", $missedEmployee );
					} finally {
						mysqli_commit ( $this->conn );
					}
				}
				return $result1;
			}
		}
	}
	
	//Download
	function AttendancesTemplateDownload() {
		$daycount = ((strtotime($this->endDate) - strtotime($this->startDate))/ (60 * 60 * 24) ) +1;
		
		$activeSheet = new PHPExcel ();
		$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ( 'A1:C1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'A1:C1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "Attendance for ".$_SESSION ['fywithMonth'] );
		$activeSheet->getActiveSheet ()->getStyle ( "A1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->setTitle ( $_SESSION ['fywithMonth'] );
		if($daycount>30){
			$activeSheet->getActiveSheet ()->getStyle ( "A2:AG2" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			$loopcond = "AH";
		}else{
			$activeSheet->getActiveSheet ()->getStyle ( "A2:AF2" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			$loopcond = "AG";
		}
		
		
		$ro1=$this->ro1;
		$queryStmt = "";
		$comid = "";
		$queryStmt2 = "";
	
		
		$result = mysqli_query ( $this->conn, "SELECT leave_rule_id,rule_name FROM company_leave_rules WHERE  enabled=1 " );
		
		$i = 3;
		$leaveRulesStr ="";
		$leaveRules = array();
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$leaveRulesStr .= strtoupper($row ['leave_rule_id'])."," .strtoupper($row ['leave_rule_id']."-FH").",".strtoupper($row ['leave_rule_id']."-SH").",";
			array_push($leaveRules,$row);
		}	
		array_push($leaveRules, array("leave_rule_id"=>"P","rule_name"=>"Present"));
		array_push($leaveRules, array("leave_rule_id"=>"LOP","rule_name"=>"Loss of Pay"));
		array_push($leaveRules, array("leave_rule_id"=>"CO","rule_name"=>"Comp - Off"));
		$leaveRulesStr .= "P,LOP,LOP-FH,LOP-SH,CO,CO-FH,CO-SH,GH-FD,WE-FD,WE-FH,WE-SH"; 
		$leaveRulesStr = "\"".$leaveRulesStr."\"";
		
		$activeSheet->getActiveSheet ()->setCellValue ( 'A2', 'Employee Id' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B2', 'Employee Name' );
		for($col = 'A'; $col != $loopcond; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
	
		$k = 0;
		$counProtect = 3;
		$start_date=$this->startDate;
		//$endDate=date('Y-m-t',strtotime($startDate));
		$queryStmt="";
		$query="SELECT employee_id,CONCAT(employee_name,' ',employee_lastname) employee_name,";
		while (strtotime($this->startDate) <= strtotime($this->endDate)) {
			$currentDate=$this->startDate;
			$queryStmt.="MAX(CASE WHEN start_date = '$this->startDate' AND holBranch = 'ALL' THEN 'GH-FD'
               WHEN (start_date >= '$this->startDate' AND  end_date <= '$this->startDate' AND holBranch=empBranch ) THEN 'RH-FD'
               WHEN (weeks = WEEK('$this->startDate') - WEEK(DATE_FORMAT('$this->startDate' , '%Y-%m-01')) + 1)
               THEN
                 (CASE WHEN (DAYNAME('$this->startDate')='sunday') THEN  IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),CONCAT('WE','-',sunday),sunday)
                       WHEN (DAYNAME('$this->startDate')='Monday') THEN  IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),CONCAT('WE','-',monday),monday)
                       WHEN (DAYNAME('$this->startDate')='Tuesday') THEN  IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),CONCAT('WE','-',tuesday),tuesday)
                       WHEN (DAYNAME('$this->startDate')='Wednesday') THEN  IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),CONCAT('WE','-',wednesday),wednesday)
                       WHEN (DAYNAME('$this->startDate')='Thursday') THEN  IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),CONCAT('WE','-',thursday),thursday)
                       WHEN (DAYNAME('$this->startDate')='Friday') THEN  IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),CONCAT('WE','-',friday),friday)
                       WHEN (DAYNAME('$this->startDate')='Saturday') THEN  IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),CONCAT('WE','-',saturday),saturday)
                  END) END) `".(explode('-',$this->startDate)[02])."`,";
			$activeSheet->getActiveSheet ()->setCellValue ( $ro1 [$k],date ("d/m/Y",strtotime($currentDate)));
			$this->startDate = date ("Y-m-d", strtotime("+1 day", strtotime($this->startDate)));
			$counProtect ++;
			$k ++;
		}
	    $query.=substr($queryStmt,0,-1)."  FROM (
		SELECT
	    IF(w.shift_id = 'Nil' OR w.shift_id = '','SH00001',w.shift_id) shift_id,we.weeks,we.sunday,we.monday,we.tuesday,we.wednesday,we.thursday,
	    we.friday,we.saturday,w.employee_id, w.employee_name,w.employee_lastname,w.branch_id empBranch,
	    h.branch_id holBranch ,h.start_date,h.end_date FROM employee_work_details w
	    LEFT join weekend we ON IF(w.shift_id = 'Nil' OR w.shift_id = '','SH00001',w.shift_id) = we.shift_id
	    JOIN company_branch b, holidays_event h
	    WHERE w.enabled = 1 AND h.start_date>= '$start_date' AND h.end_date<='$this->endDate' AND (h.category = 'HOLIDAY' OR h.category = 'DUE DAY' OR h.category = 'EVENT' ) )a GROUP BY employee_id;";
	    
	    $result = mysqli_query ( $this->conn,$query) or die(mysqli_error($this->conn));
	    $rowCount = 3;
		$excelRowset1=$this->excelRowset1;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$columCount = 1;
			foreach ( $row as $index ) {
				if($columCount>=3){
				
					$objValidation = $activeSheet->getActiveSheet ()->getCell($excelRowset1 [$columCount] . $rowCount)->getDataValidation();
			        $objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
					$objValidation->setAllowBlank(true);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle('Input error');
					$objValidation->setError('Value is not in list.');
					$objValidation->setPromptTitle('Pick from list');
					$objValidation->setPrompt('Please pick a value from the drop-down list.');
					$objValidation->setFormula1($leaveRulesStr);
					
					$activeSheet->getActiveSheet ()->setCellValue ( $excelRowset1 [$columCount] . $rowCount, $index );
				}else{
					$activeSheet->getActiveSheet ()->setCellValue ( $excelRowset1 [$columCount] . $rowCount, $index );
				}
				
				$columCount ++;
			}
				
			$rowCount ++;
		}
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 1 );
		$activeSheet->getActiveSheet ()->setTitle ( 'Information' );
		$activeSheet->setActiveSheetIndex( 1 )->mergeCells ( 'C1:D1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'C1:D1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C1', "Leave Rule Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C2', "LeaveRuleID" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D2', "LeaveRule Name" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C3', "GH" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C4', "WE" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D3', "General Holiday" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D4', "Week End" );
		$activeSheet->getActiveSheet ()->setCellValue('F3','FD stands for Full Day, FH stands for First Half and SH stands for Second Half');
	
		
	
		$i = 5;
		foreach ( $leaveRules as $leaveRule) {
			$activeSheet->getActiveSheet ()->setCellValue ( 'D' . $i, $leaveRule ['rule_name'] );
			$activeSheet->getActiveSheet ()->setCellValue ( 'C' . $i,strtoupper( $leaveRule ['leave_rule_id'] ));
			$i ++;
		}
		$activeSheet->getActiveSheet ()->getStyle ( "C1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->getStyle ( "C2:D2" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
	
		for($col1 = 'A'; $col1 !== 'E'; $col1 ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col1 )->setAutoSize ( true );
		}
	
		$filname = 'Attendance_'.($_SESSION ['fywithMonth']).'.xls';
		header ( "Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
		header ( "Content-Disposition: attachment; filename=$filname" );
		header ( "Cache-Control: max-age=0" );
		//$objWriter = new PHPExcel_Writer_Excel5($activeSheet);
		$objWriter = PHPExcel_IOFactory::createWriter ( $activeSheet, 'Excel5' );
		$objWriter->save ( 'php://output' );
		exit ();
	}
	//upload
	function AttendancesTemplateUpload($xsxlFile) {
		if ($xsxlFile) {
			
			if (($getfile = fopen ( $xsxlFile, "r" )) !== FALSE) {
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
				//$activeSheet = PHPExcel_IOFactory::load ( $xsxlFile );
				foreach ( $objPHPExcel->getWorksheetIterator () as $worksheet ) {
						
					$worksheetTitle = $worksheet->getTitle ();
					
					if ($worksheetTitle != 'Information') {
						$highestRow = $worksheet->getHighestRow (); // e.g. 10
						$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
						$nrColumns = ord ( $highestColumn ) - 64;
						$html = "";
						$html .= '<div class="reportTable">
		                 <table id="claim_requested">
                           <thead>
                              <tr>
                              <th>EmployeeId</th>
                              <th>EmployeeName</th>';      
						$orginalHeader = array (
								'Employee Id',
								'Employee Name'
		             );
						
						while (strtotime($this->startDate) <= strtotime($this->endDate)) {
							$currentDate=$this->startDate;
							$html .= "<th>" . date("d/m", strtotime($this->startDate)) . "</th>";
							array_push ( $orginalHeader,date ("d/m/Y",strtotime($currentDate)) );
							$this->startDate = date ("Y-m-d", strtotime("+1 day", strtotime($this->startDate)));
						}
						$html .= '</tr></thead>';
						
						for($row = 2; $row <= 2; ++ $row) {
							$headerAray = array ();
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								isset ( $val ) ? array_push ( $headerAray, $val ) : "";
							}
						}
						if ($orginalHeader === array_intersect ( $orginalHeader, $headerAray ) && $headerAray === array_intersect ( $headerAray, $orginalHeader )) {
						    $html .="<tbody>";
							for($row = 3; $row <= $highestRow; ++ $row) {
	                           if ($row % 2 == 0) {
									$html .= "<tr  class='alt' style='width:15%'>";
								} else {
									$html .= '<tr style="width:15%">';
								}
						    
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$html .= isset ( $val ) ? "<td>" . explode('-',$val)[0]. "</td>" : "<td>P</td>" ;	
									
								}
							}
							
							$html .= "</tbody></table></div>";
							
						} else {
							$html = "";
							$html = "error";
						}
					}
				}
	
				return $html;
			}
		} else {
			return "File Cant Be Empty";
		}
	}
	//final upload of downloaded file!!!
	function AttendancesProcessUploadedFile($xsxlFile,$leavesRule) {
		if ($xsxlFile) {
			if (($getfile = fopen ( $xsxlFile, "r" )) !== FALSE) {
				//$activeSheet = PHPExcel_IOFactory::load ( $xsxlFile );
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
				foreach ( $objPHPExcel->getWorksheetIterator () as $worksheet ) {
					$worksheetTitle = $worksheet->getTitle ();
					if ($worksheetTitle != 'Information') {
						$highestRow = $worksheet->getHighestRow (); // e.g. 10
						$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
						$nrColumns = ord ( $highestColumn ) - 64;
						
						$employeeQueries="";
						
						for($row = 3; $row <= $highestRow; ++ $row) {
							$employeeQuery = "INSERT INTO emp_absences
	       	           		(emp_absences.employee_id,emp_absences.absent_date,emp_absences.leave_rule_type,emp_absences.day_type,emp_absences.day_count,emp_absences.updated_by) VALUES ";
							$valuesQuery="";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								//each employee column will come here
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row ); //get the cell
								$val = $cell->getValue ();
								if($val!=""){
									if($col==0)
										$employeeid = $val;
										elseif($col>=2){
											//explode value by - to get rule and meridian - FD or HD
											$str=explode('-',$val);
											$val=$str[0];
											$day = isset($str[1])?$str[1]:"";
											if($val=="" || in_array($val,array("P","WE","GH","RH")))
												continue;
												$daycount=(($day=='FH'||$day=='SH')?0.5:1);
												$cell = $worksheet->getCellByColumnAndRow ( $col, 2 ); //get the cell
												$date = $cell->getValue ();
												$date = explode("/",$date);
												$date = array_reverse($date);
												$date = implode('-',$date);
												$valuesQuery .="('".$employeeid."','".$date."','".$val."','".$day."','".$daycount."','$this->updated_by' ),";
										}else{
											$dummy="";
										}
								}
									
							}
							if($valuesQuery!="")
								$employeeQueries .= $employeeQuery.rtrim($valuesQuery,",")." ON DUPLICATE KEY UPDATE leave_rule_type = VALUES(emp_absences.leave_rule_type),emp_absences.day_type =VALUES(emp_absences.day_type),emp_absences.day_count =VALUES(emp_absences.day_count),emp_absences.updated_by=VALUES(emp_absences.updated_by);";
						}
						if ($result = mysqli_multi_query ( $this->conn, substr($employeeQueries,0,-1) )) {
							$result1 = true;
							if ($result = mysqli_use_result ( $this->conn )) {
								do {
									if ($result = mysqli_store_result ( $this->conn )) {
										mysqli_free_result ( $result );
									}
								} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
							}
						}
						$leave_account = new leave_account($this->conn);
						$leave_account->creditlrPreviewPayroll(null,$leavesRule);
						return isset ( $result1 ) ? $result1 : mysqli_error ( $this->conn );
					}
			    }
			}
			  else {
							return "File Cant Be Empty";
			}
	}
	}
	//Download 
	function MiscPaydeduTemplateDownload() {
		$activeSheet = new PHPExcel ();
		$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ( 'A1:C1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'A1:C1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setTitle ( 'MiscPayments');
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "Misc Payments for ".$_SESSION ['fywithMonth'] );
		$activeSheet->getActiveSheet ()->getStyle ( "A1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->getStyle ( "A2:E2")->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );

		
		$activeSheet->getActiveSheet ()->setCellValue ( 'A2', 'Employee ID' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B2', 'Employee Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C2', 'Pay Category' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D2', 'Remarks' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'E2', 'Pay Amount' );
		
		for($col = 'A'; $col !== 'Z'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		//Misc Pay,Deduc List Prepartaion
		$result = mysqli_query ( $this->conn, "SELECT ps.pay_structure_id MiscCategory,ps.display_name MiscCategoryName,ps.type type  FROM company_pay_structure ps WHERE ps.type IN ('MP','MD') AND ps.display_flag = 1 " )or die(mysqli_error($this->conn));
		$mp = $md= "";
		$misc=array();
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			if($row['type']=="MP")
				$mp .= $row['MiscCategory'].",";
			elseif($row['type']=="MD")
				$md .= $row['MiscCategory'].",";
			array_push($misc,$row);
		}
		$mp = "\"".rtrim($mp,",")."\""; //c_incentive,c_overtime
		$md = "\"".rtrim($md,",")."\"";//c_advances,c_loan
		
		
		//Employee ID ,Name List Preparation
		$result = mysqli_query ( $this->conn, "SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name, 'pay' pay_category 
											   FROM employee_work_details w 
											   WHERE w.enabled = 1
											   GROUP BY w.employee_id;");
		$employees=array();
		
		$rowCount = 3;
		$excelRowset=$this->excelRowset;

	    while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$columCount = 2;
			foreach ( $row as $key=>$value ) {
				if($key=="pay_category"){
					$objValidation = $activeSheet->getActiveSheet ()->getCell($excelRowset [$columCount] . $rowCount)->getDataValidation();
					$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
					$objValidation->setAllowBlank(false);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle('Input error');
					$objValidation->setError('Value is not in list.');
					$objValidation->setPromptTitle('Pick from list');
					$objValidation->setPrompt('Please pick a value from the drop-down list.');
					$objValidation->setFormula1($mp);
					
						
				}else
					$activeSheet->getActiveSheet ()->setCellValue ( $excelRowset [$columCount] . $rowCount, $value );
				$columCount ++;
			}
			array_push($employees,$row); //for second sheet 
			$rowCount ++;
		}
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'A1:C1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'A1:C1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setTitle ( 'MiscDeductions');
		$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "Misc Payments for ".$_SESSION ['fywithMonth'] );
		$activeSheet->getActiveSheet ()->getStyle ( "A1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->getStyle ( "A2:E2")->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			
		$activeSheet->getActiveSheet ()->setCellValue ( 'A2', 'Employee ID' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'B2', 'Employee Name' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C2', 'Deduction Category' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D2', 'Remarks' );
		$activeSheet->getActiveSheet ()->setCellValue ( 'E2', 'Deduction Amount' );
		
		for($col = 'A'; $col !== 'Z'; $col ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
		}
		
		
		$rowCount = 3;
	    foreach ( $employees as $key=>$employee) {
			$columCount = 2;
			foreach ( $employee as $key=>$value ) {
				if($key=="pay_category"){
					$objValidation = $activeSheet->getActiveSheet ()->getCell($excelRowset [$columCount] . $rowCount)->getDataValidation();
					$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
					$objValidation->setAllowBlank(false);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle('Input error');
					$objValidation->setError('Value is not in list.');
					$objValidation->setPromptTitle('Pick from list');
					$objValidation->setPrompt('Please pick a value from the drop-down list.');
					$objValidation->setFormula1($md);
						
				}else
					$activeSheet->getActiveSheet ()->setCellValue ( $excelRowset [$columCount] . $rowCount, $value );
				$columCount ++;
			}
				
			$rowCount ++;
		}
		$activeSheet->createSheet ();
		$activeSheet->setActiveSheetIndex ( 2 );
		$activeSheet->getActiveSheet ()->setTitle ( 'Information' );
		$activeSheet->setActiveSheetIndex ( 2 )->mergeCells ( 'C1:D1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'C1:D1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C1', "Misc Payments Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'C2', "Pay Category" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'D2', "Pay Category Name" );
		$activeSheet->setActiveSheetIndex ( 2 )->mergeCells ( 'G1:H1' );
		$activeSheet->getActiveSheet ()->getStyle ( 'G1:H1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$activeSheet->getActiveSheet ()->setCellValue ( 'G1', "Misc Deductions Details" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'G2', "Dedu Category" );
		$activeSheet->getActiveSheet ()->setCellValue ( 'H2', "Dedu Category Name" );
		
		//$result = mysqli_query ( $this->conn, "SELECT ps.pay_structure_id PayCategory,ps.display_name PayCategoryName FROM company_pay_structure ps WHERE ps.type = 'MP' AND ps.display_flag = 1 " );
		
		$pi = 3; //row count start from 3
		$di =3;
		foreach ( $misc as $key=>$miscParm ) {
			if($miscParm['type']=="MP"){
				$activeSheet->getActiveSheet ()->setCellValue ( 'D' . $pi, $miscParm ['MiscCategoryName'] );
				$activeSheet->getActiveSheet ()->setCellValue ( 'C' . $pi, $miscParm ['MiscCategory'] );
				$pi ++;
			}elseif($miscParm['type']=="MD"){
				$activeSheet->getActiveSheet ()->setCellValue ( 'H' . $di, $miscParm ['MiscCategoryName'] );
				$activeSheet->getActiveSheet ()->setCellValue ( 'G' . $di, $miscParm ['MiscCategory'] );
				$di ++;
			}
			
		}
		
		$activeSheet->getActiveSheet ()->getStyle ( "C1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->getStyle ( "G1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
		$activeSheet->getActiveSheet ()->getStyle ( "C2:D2")->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		$activeSheet->getActiveSheet ()->getStyle ( "G2:H2" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
		
		for($col1 = 'A'; $col1 !== 'I'; $col1 ++) {
			$activeSheet->getActiveSheet ()->getColumnDimension ( $col1 )->setAutoSize ( true );
		}
		
		$filname = "misc_".($_SESSION ['fywithMonth']).".xls";
		header ( "Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
		
		header ( "Content-Disposition: attachment; filename=$filname" );
		header ( "Cache-Control: max-age=0" );
		$objWriter = new PHPExcel_Writer_Excel5($activeSheet);
		ob_end_clean();
		$objWriter->save ( 'php://output' );
		//exit ();
	}
	//Upload
	function MiscPaydeduTemplateUpload($xsxlFile) {
		if ($xsxlFile) {
			if (($getfile = fopen ( $xsxlFile, "r" ))) {
				$miscPayRow = "";
				$miscDeduRow = "";				
				//ileType = PHPExcel_IOFactory::identify ( $xsxlFile );
				//bjReader = PHPExcel_IOFactory::createReader ( $fileType );
				//ctiveSheet = $objReader->load ( $xsxlFile );
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
				$miscArray = array ();
				$miscPayRow = "";
				$miscDeduRow = "";
				$orginalHeader = array ();
				foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
					array_push ( $orginalHeader, $worksheet->getTitle () );
					
				}
				$headerAray = array (
						'MiscPayments',
						'MiscDeductions',
						'Information'
				);
				
				if ($orginalHeader === array_intersect ( $orginalHeader, $headerAray ) && $headerAray === array_intersect ( $headerAray, $orginalHeader )) {
					
					foreach ( $objPHPExcel->getAllSheets () as $worksheet)  {
						$worksheetTitle = $worksheet->getTitle ();
						$highestRow = $worksheet->getHighestDataRow (); // e.g. 10
						$highestColumn = $worksheet->getHighestDataColumn (); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
						$nrColumns = ord ( $highestColumn ) - 64;
				
						if ($worksheetTitle == 'MiscPayments') {
							$miscPayRow .= '<div class="reportTable"><table><thead><tr><th>Employee Id</th><th>Employee Name</th><th>Pay category</th><th>Remarks</th><th>Amount</th></tr></thead><tbody>';
							for($row = 3; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$miscPayRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$miscPayRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$miscPayRow .= "<td>" . $val . "</td>";
								}
							}
							 $miscPayRow .= "</tbody></table></div>";
						}else if ($worksheetTitle == 'MiscDeductions') {
							$miscDeduRow .= '<div class="reportTable"><table><thead><tr><th>Employee Id</th><th>Employee Name</th><th>Dedu category</th><th>Remarks</th><th>Amount</th></tr></thead><tbody>';
							for($row = 3; $row <= $highestRow; ++ $row) {
								if ($row % 2 == 0) {
									$miscDeduRow .= "<tr  class='alt' style='width:15%'>";
								} else {
									$miscDeduRow .= '<tr style="width:15%">';
								}
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
									$miscDeduRow .= "<td>" . $val . "</td>";
								}
							}
							$miscDeduRow .= "</tbody></table></div>";
						}
					}
					   array_push ( $miscArray, $miscPayRow, $miscDeduRow );
				
				} else {
							$miscArray = "error";
				}
				  return $miscArray;
				}
			} else {
		      return "File Cant Be Empty";
		      
		    }
	}
	//final upload of the downloaded file!!
	function MiscPaydeduProcessUploadedFile ($xsxlFile) {
		if ($xsxlFile) {
			if (($getfile = fopen ( $xsxlFile, "r" ))) {
				$queryStmt = "";
				//ileType = PHPExcel_IOFactory::identify ( $xsxlFile );
				$objReader = new PHPExcel_Reader_Excel5();
				$objPHPExcel = $objReader->load($xsxlFile);
	
				$miscPayArray = array (
						'pay_affected_ids',
						'employee_name',
						'pay_category',
						'remarks',
						'payment_amount'
				);
				$miscDeduArray = array (
						'dedu_affected_ids',
						'employee_name',
						'dedu_category',
						'remarks',
						'deduction_amount'
				);
				$missedinsert = array ();
				foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
					$worksheetTitle = $worksheet->getTitle ();
					$highestRow = $worksheet->getHighestRow (); // e.g. 10
					$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
					$nrColumns = ord ( $highestColumn ) - 64;
					if ($worksheetTitle == 'MiscPayments') {
					
						for($row = 3; $row <= $highestRow; ++ $row) {
							$miscPayRow = "";
							$rand = mt_rand ( 10000, 99999 );
							$payment_id = "IMPAY" . $rand;
							$payment_for = "E";
							$payments_in = "NA";
							$effects_from = $_SESSION ['current_payroll_month'];
							$miscPayRow .= "INSERT INTO misc_payments SET payment_id='" . $payment_id . "',payment_for='" . $payment_for . "',payments_in='" . $payments_in . "',effects_from='" . $effects_from . "',effects_upto='" . $effects_from . "',repetition_count=1,enabled=1,updated_by='" . $_SESSION ['login_id'] . "',";
							$duplicateStr = "";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								if ($col == 4) {
									if(!is_numeric($val) && $val==0)
										continue 2;
									$miscPayRow .= $miscPayArray [$col] . "='" . $val."|A" . "' ON DUPLICATE KEY UPDATE " . $duplicateStr1 ." ;";									
								} else {
									if($col !=1){
									$miscPayRow .= $miscPayArray [$col] . "='" . $val . "',";
									$duplicateStr .= $miscPayArray [$col] . "='" . $val . "',";
									$duplicateStr1 = rtrim($duplicateStr,",");
								   }
								}
								}
								//print_r($miscPayRow); die();
								if (! mysqli_query ( $this->conn, $miscPayRow )) {
									array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
								}
							}
					}else if ($worksheetTitle == 'MiscDeductions') {
					
						for($row = 3; $row <= $highestRow; ++ $row) {
							$miscDeduRow = "";
							$rand = mt_rand ( 10000, 99999 );
							$deduction_id = "IMPAY" . $rand;
							$deduction_for = "E";
							$deduction_in = "NA";
							$effects_from = $_SESSION ['current_payroll_month'];
							$miscDeduRow .= "INSERT INTO misc_deduction SET deduction_id='" . $deduction_id . "',deduction_for='" . $deduction_for . "',deductions_in='" . $deduction_in . "',effects_from='" . $effects_from . "',effects_upto='" . $effects_from . "',repetition_count=1,enabled=1,updated_by='" . $_SESSION ['login_id'] . "',";
							$duplicateStr = "";
							for($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
								$val = $cell->getValue ();
								if ($col == 4) {
									if(!is_numeric($val) && $val==0)
										continue 2;
										$miscDeduRow .= $miscDeduArray [$col] . "='" . $val."|A" . "' ON DUPLICATE KEY UPDATE " . $duplicateStr1 ." ;";									
								}else {
									if($col !=1){
									$miscDeduRow .= $miscDeduArray [$col] . "='" . $val . "',";
									$duplicateStr .= $miscDeduArray [$col] . "='" . $val . "',";
									$duplicateStr1 = rtrim($duplicateStr,",");
								       }
								  }
								}
							if (! mysqli_query ( $this->conn, $miscDeduRow )) {
							array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
							}
						}
					}
				}

				if (empty ( $missedinsert )) {
					$result = true;
				} else {
					$result = $missedinsert;
				}
				return $result;
				}
				} else {
					return "File Cant Be Empty";
		 	}
		}
		//Increment Template Download
		function IncrementTemplateDownload() {
			$activeSheet = new PHPExcel ();
			$activeSheet->setActiveSheetIndex ( 0 )->mergeCells ( 'A1:C1' );
			$activeSheet->getActiveSheet ()->getStyle ( 'A1:C1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$activeSheet->getActiveSheet ()->setTitle ( 'Increment');
			$activeSheet->getActiveSheet ()->setCellValue ( 'A1', "Increments for ".$_SESSION ['fywithMonth'] );
			$activeSheet->getActiveSheet ()->getStyle ( "A1" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
			$activeSheet->getActiveSheet ()->getStyle ( "A2:G2")->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			
			
			$activeSheet->getActiveSheet ()->setCellValue ( 'A2', 'Employee ID' );
			$activeSheet->getActiveSheet ()->setCellValue ( 'B2', 'Employee Name' );
			$activeSheet->getActiveSheet ()->setCellValue ( 'C2', 'Slab ID' );
			$activeSheet->getActiveSheet ()->setCellValue ( 'D2', 'Slab Type' );
			$activeSheet->getActiveSheet ()->setCellValue ( 'E2', 'Old Gross' );
			$activeSheet->getActiveSheet ()->setCellValue ( 'F2', 'New Gross' );
			$activeSheet->getActiveSheet ()->setCellValue ( 'G2', 'Increment Effects From' );
			
			for($col = 'A'; $col !== 'Z'; $col ++) {
				$activeSheet->getActiveSheet ()->getColumnDimension ( $col )->setAutoSize ( true );
			}
			
			$result = mysqli_query ( $this->conn,"SELECT GROUP_CONCAT(CONCAT(pay_structure_id,' ',REPLACE(display_name,' ','_'))) alw,GROUP_CONCAT(pay_structure_id) allowances,GROUP_CONCAT(display_name) NAME FROM company_pay_structure WHERE display_flag=1 AND type='A';");
			$payheads = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
			$allowances = $payheads['allowances'];
			$pay_heads = explode(',',('slab_id,slab_name,'.$payheads['allowances']));
			$alw_heads = explode(',',('Slab ID,Slab Name,'.$payheads['NAME']));
			//Slab List Prepartaion
			$result = mysqli_query ( $this->conn, "SELECT s.slab_id,s.slab_name,$allowances FROM company_allowance_slabs s WHERE s.enabled = 1 " )or die(mysqli_error($this->conn));
			$slab= "";
			$slabs=array();
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
					$slab.= $row['slab_id'].",";
					array_push($slabs,$row);
			}
			$slab= "\"".rtrim($slab,",")."\""; //company slabs
			
			
			//Employee ID ,Name List Preparation
			$result = mysqli_query ( $this->conn, "SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name, 'pay' pay_category,'' formula,'GROSS' slab_type,s.employee_salary_amount
											   FROM employee_work_details w
											   INNER JOIN employee_salary_details s
											   ON w.employee_id = s.employee_id
											   WHERE w.enabled = 1
											   GROUP BY w.employee_id;");
			$employees=array();
			
			$rowCount = 3;
			$excelRowset=$this->excelRowset;
			
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				$columCount = 2;
				//$activeSheet->getActiveSheet()->setCellValue("G". $rowCount,'=IF(F'.$rowCount.'<>"",ROUND((F'.$rowCount.'-E'.$rowCount.')*100/E'.$rowCount.',2),"")');
				//$activeSheet->getActiveSheet()->setCellValue("H". $rowCount,'=IF(F'.$rowCount.'<>"",'.$_SESSION ['current_payroll_month'].',"")');
				
				foreach ( $row as $key=>$value ) {
					if($key=="pay_category"){
						$objValidation = $activeSheet->getActiveSheet ()->getCell($excelRowset [$columCount] . $rowCount)->getDataValidation();
						$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
						$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
						$objValidation->setAllowBlank(false);
						$objValidation->setShowInputMessage(true);
						$objValidation->setShowErrorMessage(true);
						$objValidation->setShowDropDown(true);
						$objValidation->setErrorTitle('Input error');
						$objValidation->setError('Value is not in list.');
						$objValidation->setPromptTitle('Pick from list');
						$objValidation->setPrompt('Please pick a value from the drop-down list.');
						$objValidation->setFormula1($slab);
					}else{
						//$activeSheet->getActiveSheet ()->setCellValue ( $excelRowset [$columCount] . $rowCount, $value );
						$columCount ++;
					
					}
					
				}
				array_push($employees,$row); //for second sheet
				$rowCount ++;
			}
			
			$activeSheet->createSheet ();
			$activeSheet->setActiveSheetIndex ( 1 );
			$activeSheet->getActiveSheet ()->setTitle ( 'Information' );
			$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'D1:E1' );
			$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'C2:G2' );
			$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'C3:E3' ); //()->setCellValue ( 'C1', "Instructions")
			$activeSheet->getActiveSheet ()->getStyle ( 'C1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$activeSheet->getActiveSheet ()->setCellValue ( 'C1', "Instructions");
			$activeSheet->getActiveSheet ()->setCellValue ( 'C2', "1.Increment Effects from must be in dd-mm-YYYY format." );
			$activeSheet->getActiveSheet ()->setCellValue ( 'C3', "2.Every Columns should be filled.");
			$activeSheet->getActiveSheet ()->setCellValue ( 'C4', "3.'P' - Refers to Percentage.");
			$activeSheet->getActiveSheet ()->setCellValue ( 'C5', "4.'A' - Refers to Amount.");
			$activeSheet->getActiveSheet ()->setCellValue ( 'C6', "5.'R' - Refers to Remaining Amount.");
			$activeSheet->setActiveSheetIndex ( 1 )->mergeCells ( 'C5:D5' );
			$activeSheet->getActiveSheet ()->getStyle ( 'C8:D8' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$activeSheet->getActiveSheet ()->setCellValue ( 'C8', "Slab Details" );
			
			$ro1 = array ( 'C','D','E','F','G','H','I','J','K','L');
			for($i=0; sizeof($alw_heads)>$i; $i++) {
				$activeSheet->getActiveSheet ()->setCellValue ( $ro1[$i].'9',$alw_heads[$i]);
				$f_row = $ro1[$i].'9';
			}
			$pi = 10; //row count start from 3
			foreach ( $slabs as $key=>$slab ) {
				for($i=0; sizeof($alw_heads)>$i; $i++) {
					$activeSheet->getActiveSheet ()->setCellValue ( $ro1[$i].$pi,$slab[$pay_heads[$i]]);
				}
				$pi ++;
			}
			$activeSheet->getActiveSheet ()->getStyle ( "C8" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '39b2a9' );
			$activeSheet->getActiveSheet ()->getStyle ( "C9:".$f_row)->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setRGB ( '83d8d2' );
			
			for($col1 = 'A'; $col1 !== 'P'; $col1 ++) {
				$activeSheet->getActiveSheet ()->getColumnDimension ( $col1 )->setAutoSize ( true );
			}
					
			
			$filname = "Increments_".($_SESSION ['fywithMonth']).".xls";
			header ( "Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
			header ( "Content-Disposition: attachment; filename=$filname" );
			header ( "Cache-Control: max-age=0" );
			$objWriter = new PHPExcel_Writer_Excel5($activeSheet);
			//ob_end_clean();
			//$h2d_file_uri = $_SERVER['DOCUMENT_ROOT'].'/docs/some_file_name.docx';
			//$objWriter->save($h2d_file_uri );
			$objWriter->save ( 'php://output' );
			
			exit ();
		}
		//Increment Template Upload for Verification Screen
		function IncrementTemplateUpload($xsxlFile) {
			if ($xsxlFile) {
				if (($getfile = fopen ( $xsxlFile, "r" ))) {
					//ileType = PHPExcel_IOFactory::identify ( $xsxlFile );
					//bjReader = PHPExcel_IOFactory::createReader ( $fileType );
					//ctiveSheet = $objReader->load ( $xsxlFile );
					$objReader = new PHPExcel_Reader_Excel5();
					$objPHPExcel = $objReader->load($xsxlFile);
					$incRow = "";
					$orginalHeader = array ();
					foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
						array_push ( $orginalHeader, $worksheet->getTitle () );
						
					}
					$headerAray = array (
							'Increment',
							'Information'
					);
					
					if ($orginalHeader === array_intersect ( $orginalHeader, $headerAray ) && $headerAray === array_intersect ( $headerAray, $orginalHeader )) {
						
						foreach ( $objPHPExcel->getAllSheets () as $worksheet)  {
							$worksheetTitle = $worksheet->getTitle ();
							$highestRow = $worksheet->getHighestDataRow (); // e.g. 10
							$highestColumn = $worksheet->getHighestDataColumn (); // e.g 'F'
							$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
							$nrColumns = ord ( $highestColumn ) - 64;
							
							if ($worksheetTitle == 'Increment') {
								$incRow.= '<div class="reportTable"><table><thead><tr><th>Employee Id</th><th>Employee Name</th><th>Slab ID</th><th>Slab Type</th><th>Old Gross</th><th>New Gross</th><th>Increment Percentage</th></tr></thead><tbody>';
								for($row = 3; $row <= $highestRow; ++ $row) {
									if ($row % 2 == 0) {
										$incRow.= "<tr  class='alt' style='width:15%'>";
									} else {
										$incRow.= '<tr style="width:15%">';
									}
									for($col = 0; $col < $highestColumnIndex; ++ $col) {
										$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
										$val = $cell->getValue ();
										if (PHPExcel_Shared_Date::isDateTime ( $worksheet->getCellByColumnAndRow ( $col, $row ) )) {
											$val = date ( $format = "d/m/Y", PHPExcel_Shared_Date::ExcelToPHP ( $val ) );
										}
										$incRow.= "<td>" . $val . "</td>";
									}
								}
								$incRow.= "</tbody></table></div>";
							}
						}
						
					} else {
						$incRow= "error";
					}
					return $incRow;
				}
			} else {
				return "File Cant Be Empty";
				
			}
		}
		//final upload of the downloaded file!!
		function IncrementProcessUploadedFile($xsxlFile) {
			$result = mysqli_query ( $this->conn,"SELECT GROUP_CONCAT(pay_structure_id) allowances FROM company_pay_structure WHERE display_flag=1 AND type='A';");
			$payheads = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
			$allowances = $payheads['allowances'];
			
			if ($xsxlFile) {
				if (($getfile = fopen ( $xsxlFile, "r" ))) {
					$queryStmt = "";
					//ileType = PHPExcel_IOFactory::identify ( $xsxlFile );
					$objReader = new PHPExcel_Reader_Excel5();
					$objPHPExcel = $objReader->load($xsxlFile);
					
					$miscPayArray = array (
							'affected_ids',
							'employee_name',
							'slab_id',
							'slab_type',
							'old_gross',
							'incremented_amount',
							'action_effects_from'
					);
					
					
					$missedinsert = array ();
					foreach ( $objPHPExcel->getAllSheets () as $worksheet ) {
						$worksheetTitle = $worksheet->getTitle ();
						$highestRow = $worksheet->getHighestRow (); // e.g. 10
						$highestColumn = $worksheet->getHighestColumn (); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
						$nrColumns = ord ( $highestColumn ) - 64;
						$sal_row = array();
						if ($worksheetTitle == 'Increment') {
							
							for($row = 3; $row <= $highestRow; ++ $row) {
								$rand = mt_rand ( 10000, 99999 );
								$increment_id = "IPM" . $rand;
								$increment_for = "E";
								$effects_from = $_SESSION ['current_payroll_month'];
								$incRow = $updateRow=  "";
								$incRow.= "INSERT INTO comp_promotions_increments SET action_id='" . $increment_id. "',action_for='" . $increment_for. "',employees_affected=1,performed_by='" . $_SESSION ['login_id'] . "',";
								$updateRow .="UPDATE employee_salary_details SET ";
								$duplicateStr = $duplicateStr1 = $slab_id = $new_gross = $emp_id ="";
								$amount = $percentage_amount = $remining_amount= 0;
								for($col = 0; $col < $highestColumnIndex; ++ $col) {
									$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
									$val = $cell->getValue ();
										if (PHPExcel_Shared_Date::isDateTime ( $worksheet->getCellByColumnAndRow ( $col, $row ) )) {
											$val = date ( $format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP ( $val ) );
										}
										if ($col == 0){
											$emp_id = $val;
											$result = mysqli_query ( $this->conn, "SELECT employee_salary_amount old_gross FROM employee_salary_details s WHERE employee_id='$val' ;");
											$sal_row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
											$old_gross = $sal_row['old_gross'];
											$incRow.= $miscPayArray [$col] . "='" . $val . "',";
										}else if($col ==2) {
											$slab_id = $val;
										}else if ($col == 5) {
											if(!is_numeric($val) && $val==0)
												continue 2;
												$new_gross = $val;
												$inc_per = (($val-$old_gross)/$old_gross)*100;
												$incRow.= $miscPayArray [$col] . "='" . $inc_per."|P',";
												
										}else if($col == 6){
												$effects_from = date ( "d/m/Y", strtotime ( $val ) );
												$incRow.= $miscPayArray [$col] . "= STR_TO_DATE('$effects_from','%d/%m/%Y');";	
												$duplicateStr .= $miscPayArray [$col] . "='" . $val . "',";
												$duplicateStr1 = rtrim($duplicateStr,",");
										}
											$result = mysqli_query ( $this->conn, "SELECT $allowances FROM company_allowance_slabs s WHERE s.slab_id = '$slab_id' " )or die(mysqli_error($this->conn));
											$alw_rows= mysqli_fetch_assoc ($result);
									}
									// calculate total amount available in slab
									foreach($alw_rows as $key=>$alw_row){
										if(substr($alw_row,-1)=='A' && substr($alw_row,0,-2)!='R')
											$amount= $amount+substr($alw_row,0,-2);
									}
									// calculate the total amont in percentage format 
									foreach($alw_rows as $key=>$alw_row){
										if(substr($alw_row,-1)=='P')
											$percentage_amount = $percentage_amount+(($new_gross*substr($alw_row,0,-2))/100);
									}
									$remining_amount = $new_gross -($amount+$percentage_amount);
									
									foreach($alw_rows as $key=>$alw_row){
										if(substr($alw_row,-1)=='P')
											$updateRow .= $key ."=".(($new_gross*substr($alw_row,0,-2))/100).",";
										else if(substr($alw_row,-1)=='A' && substr($alw_row,0,-2)!='R')
											$updateRow .= $key ."=".substr($alw_row,0,-2).",";
										else 
											$updateRow .= $key ."=".$remining_amount.",";
									}
									$updateRow .="slab_id='$slab_id',increment_id='$increment_id',employee_salary_amount='$new_gross',effects_from=STR_TO_DATE('$effects_from','%d/%m/%Y'),updated_by='" . $_SESSION ['login_id'] . "'  WHERE employee_id='$emp_id';";
									
									if (! mysqli_query ( $this->conn, $incRow)) {
										array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
									}
									if (! mysqli_query ( $this->conn, $updateRow)) {
										array_push ( $missedinsert, mysqli_error ( $this->conn ) . "<br>" );
									}
							  }
							}
						}
					
					if (empty ( $missedinsert )) {
						$result = true;
					} else {
						$result = $missedinsert;
					}
					return $result;
				}
			} else {
				return "File Cant Be Empty";
			}
		}
	
}
