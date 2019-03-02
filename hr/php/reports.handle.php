<?php
/*
 * ----------------------------------------------------------
* Filename : reports.handle.php
* Classname: reports.class.php
* Author : Raja sundari
* Database : From Payroll
* Oper : Reports Filter
*
* ----------------------------------------------------------
*/
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/statReports.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/phpExcel/PHPExcel.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/phpExcel/PHPExcel/IOFactory.php");
$reports = new Reports ();
$reports->conn = $conn;
if (isset ( $_REQUEST ['act'] )) {
	$act = $_REQUEST ['act'];
	$temp = explode ( '!', base64_decode ( $act ) );
	$action = $temp [1];
} else {
	$action = $_REQUEST ['search'];
}
$resultObj = array ();
// $leavepreceeding=array();
$action = trim($action);
switch ($action) {
	case "reports_filter" :
		//Operations To Be Performed
		$elements = array ();
		if ((isset($_REQUEST ['key'])?$_REQUEST ['key']:'empty') == 'Excel') {
			$affectedIds = "'" . str_replace ( ",", "','", $_REQUEST ['affectedIds'] . "'" );
		} else {
			if (is_array ( (isset($_REQUEST ['affectedIds'])?$_REQUEST ['affectedIds']:'') )) {
				foreach ($_REQUEST ['affectedIds'] as $name ) {
					// employees converted into string using foreach and implode
					$elements [] = "'$name'";
				}
				$affectedIds = implode ( ',', $elements );
			}else{
				$affectedIds='';
			}
		}
		$date = isset ( $_REQUEST ['date'] ) ? explode ( " ", $_REQUEST ['date'] ) : "";
		$reports->date = $date [1] . "-" . $date [0] . "-01";
		$_SESSION ['payrollDatere'] = $reports->date;
		$reports->headerName = "EmployeeId,Name,LOP," . $_SESSION ['headerName'] . "TOTDEDU,NET";
		$reports->columName = $_SESSION ['colName'];
		$reports->dateOrder = $_REQUEST ['date'];
		// report Advance search using D,F,B
		$tableNameReport = "payroll lm";
		$joins = " INNER JOIN employee_work_details w ON  lm.employee_id = w.employee_id ";
		$extraFilter = $affectedIds . ") WHERE month_year='$reports->date'";
		$condition = $_REQUEST ['reportFor'] == 'D' ? ' AND w.designation_id in (' . $extraFilter : ($_REQUEST ['reportFor'] == 'F' ? ' AND w.department_id in  (' . $extraFilter : ' AND w.branch_id in   (' . $extraFilter);
		// report search using date only
		// echo date("mY", strtotime($_SESSION['current_payroll_month']));
		$extraCondition = ($_SESSION ['current_payroll_month'] == $reports->date) ? ' AND
		 		(SELECT COUNT(*) FROM payroll_preview_temp where is_processed=0) = 0 GROUP BY employee_id' : " GROUP BY employee_id";
		$ReportQuery = "employee_work_details w INNER JOIN  payroll lm ON w.employee_id=lm.employee_id  WHERE month_year='$reports->date' $extraCondition";
		// if action Equal to advanceReportSearch Table assign as payroll with joins of work details
		$reports->queryStmt = ((isset($_REQUEST ['key'])?$_REQUEST ['key']:'empty') == 'advanceReportSearch' ||(isset($_REQUEST ['key'])?$_REQUEST ['key']:'empty') == 'Excel') ? $tableNameReport . $joins . $condition . $extraCondition : $ReportQuery;
		$_SESSION ['queryStmt'] = $reports->queryStmt;
		$reports->tblName = isset ( $_REQUEST ['tblName'] ) ? $_REQUEST ['tblName'] : "";
		$reports->colSelect = isset ( $_REQUEST ['colSelect'] ) ? $_REQUEST ['colSelect'] : "";
		$result = $reports->reports_filter ( $reports->columName, $reports->queryStmt );
		break;
	case "data_feed" :
		$action = $_REQUEST ['search'];
		// Operations To Be Performed
		$elements = array ();
		if ($_REQUEST ['key'] == 'Excel') {
			$affectedIds = "'" . str_replace ( ",", "','", $_REQUEST ['affectedIds'] . "'" );
		}

		$date = isset ( $_REQUEST ['date'] ) ? $_REQUEST ['date'] : "";
		$dateSet = explode ( " ", $date );
		$reports->date = $dateSet [2] . "-" . $dateSet [1] . "-01";
		$_SESSION ['payrollDatere'] = $reports->date;
		$reports->headerName = "EmployeeId,Name,LOP," . $_SESSION ['headerName'] . ",TotDedu,Net";
		$reports->columName = $_SESSION ['colName'];
		// $reports->date= isset($_REQUEST ['date'])?explode(" ",$_REQUEST ['date'])str_replace(" ","",$_REQUEST ['date']):"";
		$reports->dateOrder = $_REQUEST ['date'];
		// report Advance search using D,F,B
		$tableNameReport = "payroll lm";
		$joins = " INNER JOIN employee_work_details cl ON  lm.employee_id = cl.employee_id ";
		$extraFilter = $affectedIds . ") WHERE month_year='$reports->date'";
		$condition = $_REQUEST ['reportFor'] == 'D' ? ' AND cl.designation_id in (' . $extraFilter : ($_REQUEST ['reportFor'] == 'F' ? ' AND cl.department_id in  (' . $extraFilter : ' AND cl.branch_id in   (' . $extraFilter);
		// report search using date only
		$ReportQuery = "payroll lm WHERE month_year='$reports->date'";
		// if action Equal to advanceReportSearch Table assign as payroll with joins of work details
		$reports->queryStmt = ($_REQUEST ['key'] == 'advanceReportSearch' || $_REQUEST ['key'] == 'Excel') ? $tableNameReport . $joins . $condition : $ReportQuery;
		$_SESSION ['queryStmt'] = $reports->queryStmt;
		$reports->tblName = isset ( $_REQUEST ['tblName'] ) ? $_REQUEST ['tblName'] : "";
		$reports->colSelect = isset ( $_REQUEST ['colSelect'] ) ? $_REQUEST ['colSelect'] : "";
		$result = $reports->tableContent ( $reports->tblName, $reports->colSelect );
		break;
	case "epfReport" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/epfReport.class.php");
		$epfReport = new EpfReport ( $conn );
		// $epfReport->conn = $conn;
		$result = $epfReport->generateEpfReportData ( "basic,c_conveyan,c_medicala,c_special", 1, 0, 15000, 0.08333, 0.03665, "032018" );
		echo json_encode ( $epfReport );
		exit ();
	case "esiReport" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/esiReport.class.php");
		$esiReport = new EsiReport ( $conn );
		$esiReport->monthYear = isset ( $_REQUEST ['monthYear'] ) ? str_replace ( " ", "", $_REQUEST ['monthYear'] ) : "";
		$esiReport->esiNo = isset ( $_REQUEST ['esiNo'] ) ? $_REQUEST ['esiNo'] : "";
		$result = $esiReport->generateEsiReportData ( $esiReport->monthYear, $esiReport->esiNo );
		echo json_encode ( $result );
		exit ();
	case "downloadEsiexcel" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/esiReport.class.php");
		$esiReport = new EsiReport ( $conn );
		$esiReport->monthYear = isset ( $_REQUEST ['monthYear'] ) ? str_replace ( " ", "", $_REQUEST ['monthYear'] ) : "";
		$esiReport->esiNo = isset ( $_REQUEST ['esiNo'] ) ? $_REQUEST ['esiNo'] : "";
		$result = $esiReport->downloadEsiexcel ( $esiReport->monthYear ,$esiReport->esiNo );
		echo json_encode ( $result );
		exit ();
	case "epfReportChellan" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/epfReport.class.php");
		$epfReport = new EpfReport ( $conn );
		$epfReport->monthYear = isset ( $_REQUEST ['monthYear'] ) ? str_replace ( " ", "", $_REQUEST ['monthYear'] ) : "";
		$epfReport->epfNo = isset ( $_REQUEST ['epfNo'] ) ? $_REQUEST ['epfNo'] : "";
		$result = $epfReport->generateEpfReportChallan ( $epfReport->monthYear ,$epfReport->epfNo  );
		echo json_encode ( $result );
		exit ();
	case "epfReportExcel":
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/epfReport.class.php");
		$epfReport = new EpfReport ( $conn );
		$epfReport->monthYear = isset ( $_REQUEST ['date'] ) ? str_replace ( " ", "", $_REQUEST ['date'] ) : "";
		$epfReport->epfNo = isset ( $_REQUEST ['epfNo'] ) ? $_REQUEST ['epfNo'] : "";
		$epfReport->generateEpfReportData ($epfReport->monthYear ,$epfReport->epfNo ,1);
		echo json_encode ( $epfReport );
		exit ();
	case "epfReportTxt" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/epfReport.class.php");
		$epfReport = new EpfReport ( $conn );
		$epfReport->monthYear = isset ( $_REQUEST ['date'] ) ? str_replace ( " ", "", $_REQUEST ['date'] ) : "";
		$epfReport->epfNo = isset ( $_REQUEST ['epfNo'] ) ? $_REQUEST ['epfNo'] : "";
		$epfReport->generateEpfReportData ( $epfReport->monthYear,$epfReport->epfNo );
		$epfReport->duration = $epfReport->monthYear;
		$handle = fopen ( "epf{$epfReport->duration}.txt", "w" );
		foreach ( $epfReport->tableData as $rowData ) {
			for($i = 0; $i < count ( $epfReport->tableHeaders ); $i ++) {
				fwrite ( $handle, "{$rowData[$i]}#~#" );
			}
			fwrite ( $handle, "\r\n" );
		}
		fclose ( $handle );
		header ( 'Content-Type: application/octet-stream' );
		header ( 'Content-Disposition: attachment; filename=' . basename ( "epf{$epfReport->duration}.txt" ) );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate' );
		header ( 'Pragma: public' );
		header ( 'Content-Length: ' . filesize ( "epf{$epfReport->duration}.txt" ) );
		readfile ( "epf{$epfReport->duration}.txt" );
		unlink ( "epf{$epfReport->duration}.txt" );
		exit ();
	case "tdsReport" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/tdsReport.class.php");
		$tdsReport = new TdsReport ( $conn );
		$date = isset ( $_REQUEST ['date'] ) ? explode ( " ", $_REQUEST ['date'] ) : "";
		$tdsReport->monthYear = $date [1] . "-" . $date [0] . "-01";
		$tdsReport->tdsNo = isset ( $_REQUEST ['tdsNo'] ) ? $_REQUEST ['tdsNo'] : "";
		$tdsReport->generateTdsData ( 'c_it', $tdsReport->monthYear ,$tdsReport->tdsNo);
		echo json_encode ( $tdsReport );
		exit ();
	case "ptReport" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/ptReport.class.php");
		$ptReport = new PtReport ( $conn );
		$date = isset ( $_REQUEST ['date'] ) ? explode ( " ", $_REQUEST ['date'] ) : "";
		$ptReport->monthYear = $date [1] . "-" . $date [0] . "-01";
		$ptReport->generatePtData ( 'c_pt', $ptReport->monthYear );
		echo json_encode ( $ptReport );
		exit ();
	case "bankPayoutReport" :
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/payoutReport.class.php");
		$payoutreport = new payoutReport ( $conn );
		$date = isset ( $_REQUEST ['date'] ) ? explode ( " ", $_REQUEST ['date'] ) : "";
		$payoutreport->monthYear = $date [1] . "-" . $date [0] . "-01";
		$payoutreport->generatePayoutData ( $payoutreport->monthYear );
		echo json_encode ( $payoutreport );
		exit ();
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Reports " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Reports " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>