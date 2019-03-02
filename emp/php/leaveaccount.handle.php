<?php
/*
 * ----------------------------------------------------------
 * Filename : leaveaccount.class.php
 * Author : faheen
 * Database : leave account,payroll_preview_temp
 * Oper : Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/leaveaccount.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

$leave = new leave_account ($conn);

Session::newInstance ()->_setLeaveRules ();
$lrArray = Session::newInstance ()->_get ( "leaveRules" );
$leave->leaveRules = array_merge ( $lrArray ['M'], $lrArray ['Y'] );


// Leave Apply Parameters
$leave->from_date =isset( $_REQUEST ['from_date'])? $_REQUEST ['from_date']:'';
$leave->from_half =isset( $_REQUEST ['duration_from'])? $_REQUEST ['duration_from']:'';
$leave->to_date =isset( $_REQUEST ['to_date'])? $_REQUEST ['to_date']:'';
$leave->to_half =isset( $_REQUEST ['duration_to'])? $_REQUEST ['duration_to']:'';
$leave->duration = isset ( $_REQUEST ['durationCount'] ) ? $_REQUEST ['durationCount'] : "";
$leave->leave_type = isset ( $_REQUEST ['leave_rule_selected'] ) ? $_REQUEST ['leave_rule_selected'] : "";
$leave->reason = isset ( $_REQUEST ['reason'] ) ? $_REQUEST ['reason'] : "";
$leave->request_id = isset ( $_REQUEST ['request_id'] ) ? $_REQUEST ['request_id'] : "";
$leave->current_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];;
$leave->flag = isset ( $_REQUEST ['flag'] ) ? $_REQUEST ['flag'] :0;
$leave->employee_id = $_SESSION ['employee_id'];
$leave->updated_by = $_SESSION ['employee_id'];
switch ($action) {
	case "getLeaveAccount" :
		$leaveYear = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
		$result = $leave->getLeaveAccount ( $_SESSION ['employee_id'], $leaveYear );
		break;
	case "getSelectedLeaveDetails" :
		$result = $leave->getSelectedLeaveDetails ( $leave->leaveRules, $_SESSION ['employee_id'], $_REQUEST ['leave_account_year'] );
		break;
	case "leaveRequested" :
		$rand = mt_rand ( 10000, 99999 );
		$leave->request_id = "RQ" . $rand;
		$result = $leave->leaveRequested();
		break;
	case "getLeaveRequest" :
		$result = $leave->getLeaveRequest("$leave->employee_id");
		break;
	case "getWeekoff":
		$result = $leave->getWeekoff($leave->employee_id,$leave->request_id,$leave->current_year,
		$_REQUEST['leaveRuleId'],$leave->flag);
		break;
	default :
		$result = FALSE;
		exit ();
}

if(is_array($result)){
	if($result[0]== TRUE){
		$resultObj [0] = 'success';
		$resultObj [1] = $action . $result[1];
		$resultObj [2] = $result[2];
		
	}else{
		$resultObj [0] = 'error';
		$resultObj [1] = $result[1];
		$resultObj [2] = $result[2];
	}
		
}else{ 	
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "LeaveAccount " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "LeaveAccount " . $action . " Failed";
	$resultObj [2] = $result;
}
	}

echo json_encode ( $resultObj );