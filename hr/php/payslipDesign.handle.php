	<?php
/*
 * ----------------------------------------------------------
 * Filename : payslipDesign.handle.php
 * Classname: payslipDesign.class.php
 * Author : Raja sundari
 * Database : preview_payroll_db
 * Oper : customise Payroll
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/payslipDesign.class.php");
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/session.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
/* Setting Variables */
$payslipdesign = new payslipDesign ();
Session::newInstance ()->_setLeaveRules ();
$lrArray = Session::newInstance ()->_get ( "leaveRules" );
Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
Session::newInstance ()->_setMiscPayParams ();
$miscallowDeducArray = $miscallowDeduc = Session::newInstance ()->_get ( "miscPayParams" );
$payslipdesign->masterString = $allowDeducArray ["allowString"];
$payslipdesign->allowDeduString = $allowDeducArray ["allowString"] . $allowDeducArray ["deduString"] . $miscallowDeducArray ["miscAllowString"] . $miscallowDeducArray ["miscDeduString"];
$payslipdesign->company_id = $_SESSION ['company_id'];
if ($action == 'update') {
	$logo_id_s = $_POST ['logo_id'];
	$logo_id_flag = $_POST ['logo_id_flag'];
	$logo_con = $logo_id_s . $logo_id_flag;
	$logo_id_f = str_ireplace ( "=&", "=", $logo_con );
	
	
	
	$logo_id_se = str_ireplace ( "&", "", $_POST ['logo_id_flag'] );
	$_SESSION ['logo_id_flag'] = $logo_id_se;
	$basic_val_s = $_POST ['basic_val'];
	$basic_val_f = str_ireplace ( "=&", ",", $basic_val_s );
	$basic_val_t = str_ireplace ( "=", ",", $basic_val_f );
	
	$leave_val_s = $_POST ['leave_val'];
	$leave_val_f = str_ireplace ( "=&", ",", $leave_val_s );
	$leave_val_t = str_ireplace ( "=", ",", $leave_val_f );
	
	$count_sub = count ( $_POST );
	$count_s = ($count_sub - 12) / 2;
	$value = is_int ( $count_s );
	if ($value == 0) {
		$count = $count_s + 0.5;
	} else {
		$count = $count_s;
	}
	$cloLeft = "";
	$cloRight = "";
	$i = 0;
	$j = 0;
	foreach ( $_POST as $key => $value ) {
		if ($key !== "pay_id_val" && $key !== "datatakeval" && $key !== "basic_val" && $key !== "leave_val" && $key !== "logo_id" && $key !== "logo_id_flag" && $key !== "act" && $key !=="colorpicker_val" && $key !='payslip_border' && $key !='is_header_color' && $key !='is_it_summary' && $key != 'is_master_sal') {
			if ($i < $count) {
				$cloLeft .= $key . ",";
			} else {
				$cloRight .= $key . ",";
			}
			$i ++;
			$j ++;
		}
	}
	$payslipdesign->headerFooter = str_ireplace ( "=", ",", $logo_id_f );
	$payslipdesign->totalColumName = str_ireplace ( "%23", "#", $basic_val_t );
	//$leave = str_replace ( "%23", "#", $leave_val_t );
	$payslipdesign->leaveInfo = str_ireplace ( "%23", "#", $leave_val_t );
	$payslipdesign->leftCustomise = substr ( $cloLeft, 0, - 1 );
	$payslipdesign->rightCustomise = substr ( $cloRight, 0, - 1 );
}
$payslipdesign->payslip_id = isset($_REQUEST ['pay_id_val'])? $_REQUEST ['pay_id_val']:"";
$payslipdesign->payslip_border = isset($_REQUEST ['payslip_border'])? $_REQUEST ['payslip_border']:"";
$payslipdesign-> payslip_ids = isset ( $_REQUEST ['pays_id'] ) ? $_REQUEST ['pays_id'] : "";
$payslipdesign->protect_password = isset ( $_REQUEST ['passKey'] ) ? $_REQUEST ['passKey'] : "";
$payslipdesign->inActive = isset ($_REQUEST['inActive'])?$_REQUEST['inActive']:0;
$payslipdesign->conn = $conn;
$payslipdesign->is_mastersalary = isset ($_REQUEST['is_mastersalary'])?$_REQUEST['is_mastersalary']:0;
$payslipdesign->setActive = isset ($_REQUEST['setActive'])?$_REQUEST['setActive']:"1";
$payslipdesign->colorCode = isset ($_REQUEST['colorpicker_val'])?$_REQUEST['colorpicker_val']:"";
$payslipdesign->headerColor = isset ($_REQUEST['is_header_color'])?$_REQUEST['is_header_color']:"";
$payslipdesign->ItSummary = isset ($_REQUEST['is_it_summary'])?$_REQUEST['is_it_summary']:"";
$payslipdesign->AddColumn = isset ($_REQUEST['is_master_sal'])?$_REQUEST['is_master_sal']:"";

//view payroll OPeration after Payroll Run Show their Runnned Data
$payslipdesign->extraCondition=((isset($_REQUEST ['affectedIds'])&& (!empty($_REQUEST ['affectedIds'])))? 
		" AND w.employee_id IN ('".str_replace(",","','",$_REQUEST ['affectedIds'])."')":"");
$payslipdesign->monthYear = isset ( $_REQUEST ['monthYear'] ) ? explode ( " ", $_REQUEST ['monthYear'] ) [1] . "-" . explode ( " ", $_REQUEST ['monthYear'] ) [0] . "-01" : '';

switch ($action) {
	case "update" :
		$result = $payslipdesign->update ( $payslipdesign->leftCustomise, $payslipdesign->rightCustomise, $payslipdesign->totalColumName, $payslipdesign->leaveInfo, $payslipdesign->headerFooter,  $payslipdesign->colorCode,$payslipdesign->payslip_border,$payslipdesign->headerColor,$payslipdesign->ItSummary,$payslipdesign->AddColumn ,$payslipdesign->payslip_id);
		break;
	case "passwordset" :
		$resultSet = $payslipdesign->passwordUpdate ( $payslipdesign->payslip_ids, $payslipdesign->protect_password);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "select" :
		$result = $payslipdesign->select ( $payslipdesign->payslip_id);
		break;
	case "selectThemes" :
		$resultSet = $payslipdesign->selectThemes ( $payslipdesign->payslip_id);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "view" :
		$resultSet = $payslipdesign->view ($payslipdesign->monthYear,$payslipdesign->extraCondition,$payslipdesign->inActive);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getEmployeePayrollData" :
		$resultSet = $payslipdesign->getEmployeePayrollData ($_REQUEST ['employee_id'],$payslipdesign->monthYear);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "viewPayslips":
		$resultSet = $payslipdesign->viewPayslips($payslipdesign->payslip_id);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "setActive":
		$result = $payslipdesign->setActive($payslipdesign->payslip_ids,$payslipdesign->setActive);
		break;

		
	default :
		$result = FALSE;
}
if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "PayslipCustomise  " . $action . " Successfull";
	$resultObj [2] = isset($dataSet)?$dataSet:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "PayslipCustomise " . $action . " Failed";
	$resultObj [2] = isset($dataSet)?$dataSet:$result;;
}
echo json_encode ( $resultObj );
?>