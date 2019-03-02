<?php
/*
 * ----------------------------------------------------------
 * Filename : report.handle.php
 * Classname: report.class.php
 * Author : Rufus Jackson
 * Database : 
 * Oper : Reports Handler
 *
 * ----------------------------------------------------------
 * 
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/report.class.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
$report = new Report ();
$report->conn = $conn;
$temp = explode ( '!', base64_decode ( $_REQUEST['act'] ) );
$action = $temp [1];
if($action=='reportHTML' || $action=='reportPdf' || $action=='reportExcel'){
Session::newInstance ()->_setGeneralPayParams ();
$report->allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
Session::newInstance ()->_setMiscPayParams ();
$report->miscallowDeducArray = $miscallowDeduc = Session::newInstance ()->_get ( "miscPayParams" );
}

switch ($action) {
	case "reportHTML" :
		$result=$report->merge($_REQUEST)->create($_REQUEST['type']);
		break;
	case "reportPdf" :
		$report->merge($_REQUEST)->create($_REQUEST['type']);
		$report->getReportPDF($report->tableData('O')['data']);
		break;
	case "reportExcel":
		 $report->merge($_REQUEST)->create($_REQUEST['type']);
		 $result=$report->tableData('O');
		 break;
	case "getReportPDF":
		$result = $report->getReportPDF ($_REQUEST['pdfData']);
		break;
	case "getPayrollYears" :
		$result = $report->getPayrollYears (isset($_REQUEST['typeOfYear'])?$_REQUEST['typeOfYear']:'');
		break;
	case "getPeriodOf" :
		$result = $report->getPeriodOf ($_REQUEST['reportFor'],(isset($_REQUEST['reportYear'])?$_REQUEST['reportYear']:''),$_REQUEST['reportType']);
		break;
	
	case "getcategoryNames" :
		if($_REQUEST['categoryName']=='B'){
			require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/branch.class.php");
			$branchData = new Branch ();
			$branchData->conn = $conn;
			$result=$branchData->select ();
		}else if($_REQUEST['categoryName']=='D'){
			require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/designation.class.php");
			$designation = new Designation ();
			$designation->conn = $conn;
			$result=$designation->select ();
		}else if($_REQUEST['categoryName']=='F'){
			require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/department.class.php");
			$department = new Department ();
			$department->conn = $conn;
			$result=$department->select ();
		}else if($_REQUEST['categoryName']=='E'){
			require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/employee.class.php");
			$employee = new Employee ();
			$employee->conn = $conn;
			$result=$employee->select (1);
		}else if($_REQUEST['categoryName']=='T'){
			require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/team.class.php");
			$team = new Team ();
			$team->conn = $conn;
			$result=$team->select ();
		}
		break;
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