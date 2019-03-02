<?php
/*
 * ----------------------------------------------------------
 * Filename : Payroll.handle.php
 * Classname: payroll.class.php
 * Author : Rufus Jackson
 * Database : preview_payroll_db
 * Oper : Payroll Run
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/payroll.class.php");
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/session.class.php");
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/notifyEmail.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
/* Setting Variables */
$payroll = new Payroll ( $_SESSION ['monthNo'] . $_SESSION ['payrollYear'], $_SESSION ['financialYear'], $_SESSION ['login_id'] );
/* Operations to be performed For PreView */

$payroll->payroll_for ='E';
$affected_ids = isset ( $_REQUEST ['affectedIds'] ) ? $_REQUEST ['affectedIds'] : ""; // THe ID for the run payroll
$affectedArray = array ();
if (is_array ( $affected_ids )) {
	foreach ( $affected_ids as $id ) {
		$affectedArray [] = "\'" . $id . "\'";
	}
	$affectedEmpId = implode ( ',', $affectedArray );
}

// Session::newInstance()->_drop("leaveRules");
// Session::newInstance()->_drop("generalPayParams");
// Session::newInstance()->_drop("miscPayParams");

Session::newInstance ()->_setLeaveRules ();
$lrArray = Session::newInstance ()->_get ( "leaveRules" );
Session::newInstance ()->_setGeneralPayParams ();
$payroll->allowDedArray = $allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
Session::newInstance ()->_setMiscPayParams ();
$payroll->miscallowDeducArray= $miscallowDeducArray = $miscallowDeduc = Session::newInstance ()->_get ( "miscPayParams" );

/* Operations to be performed For Run */
$payroll->company_id = $_SESSION ['company_id'];
$payroll->payroll_flag = $_SESSION ['payroll_flag'];
$payroll->master_db_name = MASTER_DB_NAME;
$payroll->leaveRulesmonthly = $lrArray ['monthlyString']; // CL,
$payroll->leaveRulesquarterly = $lrArray ['quarterlyString'];
$payroll->leaveRulesyearly = $lrArray ['yearlyString']; // SL,
$payroll->leaves = array_merge ( $lrArray ['M'], $lrArray ['Y'], $lrArray ['Q'] );
$payroll->masterString = $allowDeducArray ["allowString"] ."!". $miscallowDeducArray ["miscAllowString"];
$payroll->allowDeduString = $allowDeducArray ["allowString"] . $allowDeducArray ["deduString"] . $miscallowDeducArray ["miscAllowString"] . $miscallowDeducArray ["miscDeduString"];
$payroll->empCount = isset ( $_REQUEST ['count'] ) ? $_REQUEST ['count'] : "";
$payroll->empFormat = isset ( $_REQUEST ['empFormat'] ) ? $_REQUEST ['empFormat'] : "";
$payroll->payrollempMoveFormat = isset ( $_REQUEST ['empFormat'] ) ? "'" . str_replace ( ",", "','", $_REQUEST ['empFormat'] ) . "'" : "";
$payroll->leavemakezero = isset ( $_REQUEST ['leavemakezero'] ) ? $_REQUEST ['leavemakezero'] : "";
$payroll->currentMonthDate = $_SESSION ['payrollYear'] . '-' . $_SESSION ['monthNo'] . '-01';
$payroll->masterDbDate = date ( 'Y-m-d', strtotime ( "+1 months", strtotime ( $_SESSION ['payrollYear'] . '-' . $_SESSION ['monthNo'] . '-01' ) ) );
// option have to deleete
$payroll->nextMonthDate = date ( 'Y-m-d', strtotime ( "+1 months", strtotime ( ($_SESSION ['payrollYear']) . '-' . $_SESSION ['monthNo'] . '-01' ) ) );
$payroll->isexit = isset ( $_REQUEST ['isexit'] ) ? $_REQUEST ['isexit'] : 0; // For Make Work Details employe Disable
$monthYear = isset ( $_SESSION ['partialRun'] ) ? ($_SESSION ['partialRun'] == 1) ? date ( 'Y-m-d', strtotime ( "+0 months", strtotime ( $_SESSION ['payrollYear'] . '-' . $_SESSION ['monthNo'] . '-01' ) ) ) : date ( 'Y-m-d', strtotime ( "-1 months", strtotime ( $_SESSION ['payrollYear'] . '-' . $_SESSION ['monthNo'] . '-01' ) ) ) : 0;
$payroll->monthYear = isset ( $_REQUEST ['monthYear'] ) ? explode ( " ", $_REQUEST ['monthYear'] ) [1] . "-" . explode ( " ", $_REQUEST ['monthYear'] ) [0] . "-01" : $monthYear;
$payroll->ignoreemploee = isset ( $_REQUEST ['IgnoreEmpresend'] ) ? $_REQUEST ['IgnoreEmpresend'] : 0;
$payroll->forceRunFlag = isset ( $_REQUEST ['forceRun'] ) ? $_REQUEST ['forceRun'] : 0;
$payroll->lastWorkingDate = isset ( $_REQUEST ['lastWorkingDate'] ) ? $_REQUEST ['lastWorkingDate'] : 0;
switch ($action) {
	case "run" :
		$result= $payroll->runPayroll ( $payroll->isexit );
		break;
	case "preview" :
		$result = $payroll->previewPayroll ( $payroll->payroll_for, $affectedEmpId, 0, $payroll->forceRunFlag );
		break;
	case "sendEmail" :
		if (isset ( $_REQUEST ['mailId'] ) && ! empty ( $_REQUEST ['mailId'] )) {
			if (! filter_var ( $_REQUEST ['mailId'], FILTER_VALIDATE_EMAIL ) === false) {
				$result = $payroll->sendEmail ( $payroll->empFormat, $payroll->monthYear,$_REQUEST ['mailId'], 0 );
			} else {
				$result = 0;
			}
		} else {
			$result = $payroll->sendEmail ( $payroll->empFormat, $payroll->monthYear, null, $payroll->ignoreemploee );
		}
		break;
	case "getSalaryDetails" :
		$result = $payroll->getSalaryDetails ( $_REQUEST ['emp_id'], $_REQUEST ['current_month'] );
		break;
	case "payoutStatement" :
		$result = $payroll->payoutStatement ( $payroll->monthYear, $_REQUEST['empIds']);
		break;
	case "downloadPayrollPreview" :
		$result = $payroll->downloadPayrollPreview ( $payroll->payroll_for, $_REQUEST ['employee_id'], 0 );
		break;
	case "monthlyPayslipPdf" :
		$result = $payroll->monthlyPayslip($_REQUEST ['employee_id'],$_SESSION ['payrollDate'],$payroll->allowDeduString,$payroll->masterString);
		break;
	case "downloadPreviewExcel" :
		$resultdata = $payroll->downloadPreviewExcel ( $payroll->payroll_for, $_REQUEST ['employeesId'], 0 );
		$result = $resultdata['result'];
		$data = $resultdata['data'];
		break;
	case "getLopDetails":
		$leave_acc = new leave_account($conn);
		$resultdata= $leave_acc->getLopDetails($payroll->monthYear,$_REQUEST ['employee_id']);
		$result = $resultdata['result'];
		$data = $resultdata['data'];
	default :
		$result = FALSE;
}
if ($result[0] || $result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Payroll  " . $action . " Successfull";
	$resultObj [2] = isset($data)?$data:$result[1];
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Payroll " . $action . " Failed";
	$resultObj [2] =  isset($data)?$data:$result[1];
}
echo json_encode ( $resultObj );
?>