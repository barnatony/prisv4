<?php
/*
 * ----------------------------------------------------------
 * Filename : noticePeriod.handle.php
 * Classname: noticePeriod.class.php
 * Author : Raja Sundari
 * Database : emp_notice_period
 * Oper : NoticePeriod Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/noticePeriod.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/payroll.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/retirementBenefit.class.php");
require_once ( LIBRARY_PATH. "/deviceApi.class.php"); // Include the File


$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$noticePeriod = new noticePeriod ($conn);
$noticePeriod->employee_id = isset ( $_REQUEST ['employee_id'] ) ? $_REQUEST ['employee_id'] : "";
$noticePeriod->notice_date = isset ( $_REQUEST ['nDate'] ) ? $_REQUEST ['nDate'] : "";
$noticePeriod->last_date = isset ( $_REQUEST ['lDate'] ) ? $_REQUEST ['lDate'] : "";
$noticePeriod->reason = isset ( $_REQUEST ['reasonCode'] ) ? $_REQUEST ['reasonCode'] : "";
$noticePeriod->letter_text = isset ( $_REQUEST ['letterCode'] ) ? $_REQUEST ['letterCode'] : "";
$noticePeriod->remark = isset ( $_REQUEST ['remark'] ) ? $_REQUEST ['remark'] : "";
$noticePeriod->process_type = isset ( $_REQUEST ['processType'] ) ? $_REQUEST ['processType'] : "";
$noticePeriod->status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "P";
$noticePeriod->updated_by = isset ( $_SESSION ['login_id'] ) ? $_SESSION ['login_id'] : $_SESSION ['employee_id'];
$noticePeriod->noticePeriodId = isset ( $_REQUEST ['nId'] ) ? $_REQUEST ['nId'] : "";

if ($action == 'settlementPreview' || $action == 'downloadGeneratePdf' || $action == 'settlementPreviewPdf') {
	$allAllowNameId = "";
	$allDeduNameId = "";
	$allMiscAllowNameId = "";
	$allMiscDeduNameId = "";
	Session::newInstance ()->_setGeneralPayParams ();
	$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
	
	foreach ( $allowDeducArray ['A'] as $allow ) {
		$allAllowNameId .= "SUM(t." . $allow ['pay_structure_id'] . ") " . $allow ['pay_structure_id'] . ",";
	}
	
	foreach ( $allowDeducArray ['D'] as $dedu ) {
		$allDeduNameId .= "SUM(t." . $dedu ['pay_structure_id'] . ") " . $dedu ['pay_structure_id'] . ",";
	}
	
	// miscAllowances and miscDeduction
	Session::newInstance ()->_setMiscPayParams ();
	$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
	
	foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
		$allMiscAllowNameId .= "SUM(t." . $miscAllow ['pay_structure_id'] . ") " . $miscAllow ['pay_structure_id'] . ",";
	}
	
	foreach ( $miscallowDeducArray ['MD'] as $miscDedu ) {
		$allMiscDeduNameId .= "SUM(t." . $miscDedu ['pay_structure_id'] . ") " . $miscDedu ['pay_structure_id'] . ",";
	}
	
	Session::newInstance ()->_setRetirementParams ();
	$retirementArray = Session::newInstance ()->_get ( "retirementParams" );
	
	$noticePeriod->queryStmt = $allAllowNameId . $allDeduNameId . $allMiscAllowNameId . $allMiscDeduNameId;
	$noticePeriod->previewQueryStmt = $allowDeducArray ["allowString"] . $allowDeducArray ["deduString"] . $miscallowDeducArray ["miscAllowString"] . $miscallowDeducArray ["miscDeduString"];
}

// For Settlement Select previous data From payroll and previewpayroll_temp
/*
 * Session::newInstance()->_setLeaveRules();
 * $lrArray=Session::newInstance()->_get("leaveRules");
 */
//$noticePeriod->conn = $conn;
switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$noticePeriod->noticePeriodId = "NP" . $rand;
		$resultset = $noticePeriod->insert ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "update" :
		$resultset = $noticePeriod->update ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "currentMonthExit" :
		$resultset = $noticePeriod->currentMonthExit ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "select" :
		$result = $noticePeriod->select ( $noticePeriod->noticePeriodId );
		break;
	case "pendingExit" :
		$resultset = $noticePeriod->pendingExit ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	// For notice createdby employee
	case "employeeNotice" :
		$result = $noticePeriod->employeeNotice ( $noticePeriod->noticePeriodId );
		break;
	case "txtGenerate" :
		$my_file = dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/compDat/" . $_SESSION ['company_id'] . "/LTR/" . str_replace ( " ", "_", $_REQUEST ['letterName'] ) . ".txt";
		$handle = fopen ( $my_file, 'w' ) or die ( 'Cannot open file:  ' . $my_file );
		$data = isset ( $_REQUEST ['letterCode'] ) ? $_REQUEST ['letterCode'] : "";
		fwrite ( $handle, $data );
		$result = true;
		break;
	case "settlementPreview" :
		$payroll = new Payroll ( $_SESSION ['monthNo'] . $_SESSION ['payrollYear'],$_SESSION ['financialYear'], $_SESSION ['login_id'] );
		$empId = isset ( $_REQUEST ['employee_id'] ) ? $_REQUEST ['employee_id'] : "";
		$result = $payroll->previewPayroll ( 'E', "\'" . $empId . "\'", 1, 1 );
		$result = $noticePeriod->previewSettlmentPayslip ( $empId, $noticePeriod->queryStmt, $noticePeriod->previewQueryStmt, $retirementArray );
		break;
	// For notice createdby employee
	case "updatedGratuity" :
		$result = $noticePeriod->updatedGratuity ( 0, $_REQUEST ['benefits'], $noticePeriod->employee_id, $_REQUEST ['gratuityAmount'], 0, $_REQUEST ['remarks'] );
		break;
	case "calculateGratuity" :
		$retireBenefits = new RetirementBenefit ($conn);
		//$retireBenefits->conn = $conn;
		$result = $retireBenefits->calculateBenefit ( $_REQUEST ['benefits'], $_REQUEST ['employee_id'], $_REQUEST ['employee_doj'], $_REQUEST ['employee_doe'] );
		break;
	case "downloadGeneratePdf" :
		$result = $noticePeriod->downloadGeneratePdf ( $_REQUEST ['employee_id'], $_REQUEST ['processType'], $noticePeriod->queryStmt, $retirementArray,0);
		break;
    case "settlementPreviewPdf" :
		$result = $noticePeriod->downloadGeneratePdf ( $_REQUEST ['employee_id'], $_REQUEST ['processType'], $noticePeriod->queryStmt, $retirementArray ,1);
		break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Notice Period " . $action . " Successfully";
	$resultObj [2] = (isset($data)?$data:$result);
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Notice Period " . $action . " Failed";
	$resultObj [2] = (isset($data)?$data:$result);
}
echo json_encode ( $resultObj );
?>