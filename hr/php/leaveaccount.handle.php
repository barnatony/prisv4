<?php
/*
 * ----------------------------------------------------------
 * Filename : leaverules.handle.php
 * Classname: leaverules.class.php
 * Author : Rufus Jackson
 * Database : company_leaverules
 * Oper : leaverules Actions
 *
 * ----------------------------------------------------------
 */

/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/leaveaccount.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$leavepreceeding = array ();

$leave_account = new leave_account ($conn);
/* Operations To Be Performed */
$leave_account->attSearchIds=(isset($_REQUEST ['attSearchIds']) && (!empty($_REQUEST ['attSearchIds']))? " AND w.employee_id IN ('".implode("','",(array)$_REQUEST ['attSearchIds'])."')":"");
$leave_account->compoff_id = isset ( $_REQUEST ['compOff'] ) ? $_REQUEST ['compOff'] : "";
$leave_account->request_id = isset ( $_REQUEST ['request_id'] ) ? $_REQUEST ['request_id'] : "";
$leave_account->lop = isset ( $_REQUEST ['lop'] ) ? $_REQUEST ['lop'] : "";
$leave_account->lop_sub = isset ( $_REQUEST ['lop_sub'] ) ? $_REQUEST ['lop_sub'] : "";
$leave_account->leave_rules = isset ( $_REQUEST ['leave_rules'] ) ? $_REQUEST ['leave_rules'] : "";
$leave_account->leave_account = isset ( $_REQUEST ['id'] ) ? $_REQUEST ['id'] : "";
$leave_account->lr_select = isset ( $_REQUEST ['value'] ) ? $_REQUEST ['value'] : "";
$leave_account->compoff = isset ( $_REQUEST ['comOff'] ) ? $_REQUEST ['comOff'] : "";
$leave_account->admin_reason = isset ( $_REQUEST ['adminReason'] ) ? $_REQUEST ['adminReason'] : "";
$leave_account->lopData = isset ( $_REQUEST ['lopData'] ) ? $_REQUEST ['lopData'] : "";
$leave_account->status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "";
$leave_account->updated_by = $_SESSION ['login_id'];
$leave_account->is_processed = isset ( $_REQUEST ['isprocessed'] ) ? $_REQUEST ['isprocessed'] : "";
$leave_account->next_year = $_SESSION ['nextyear_date'];
$leave_account->current_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
$leave_account->leaveRuleId = isset ( $_REQUEST ['leaveRuleId'] ) ? $_REQUEST ['leaveRuleId'] : "";
$leave_account->employee_id = isset ( $_REQUEST ['employee_id'] ) ? $_REQUEST ['employee_id'] : "";
$leave_account->remarks = isset ( $_REQUEST ['remarks'] ) ? $_REQUEST ['remarks'] : "";

$leave_account->dayType = isset ($_REQUEST ['dayType']  ) ?$_REQUEST ['dayType'] : "";
$leave_account->dayCount = (isset ( $leave_account->dayType) &&  ($leave_account->dayType=='FH' || $leave_account->dayType=='SH') ) ?
                           0.5 :(($leave_account->dayType=='FD')?1:'');
$leave_account->date = isset ( $_REQUEST ['dateVal'] ) ? $_REQUEST ['dateVal'] : "";
$leave_account->dayType = isset ( $leave_account->dayType ) ? $leave_account->dayType : "";
$leave_account->month = isset ( $_REQUEST ['month'] ) ? $_REQUEST ['month'] : "";
$leave_account->flag = isset ( $_REQUEST ['flag'] ) ? $_REQUEST ['flag'] :0;
$leave_account->reason = isset ( $_REQUEST ['reason'] ) ? $_REQUEST ['reason'] :0;
$leave_account->check_in = isset ( $_REQUEST ['check_in'] ) ? $_REQUEST ['check_in'] :0;
$leave_account->check_out = isset ( $_REQUEST ['check_out'] ) ? $_REQUEST ['check_out'] :0;
$leave_account->old_checkin = isset ( $_REQUEST ['old_checkin'] ) ? $_REQUEST ['old_checkin'] : "";
$leave_account->old_checkout  = isset ( $_REQUEST ['old_checkout'] ) ? $_REQUEST ['old_checkout'] : "";
$leave_account->changeReason = isset ( $_REQUEST ['changeReason'] ) ? $_REQUEST ['changeReason'] :0;
$leave_account->editTime = isset ( $_REQUEST ['editTime'] ) ? $_REQUEST ['editTime'] :0;
$leave_account->date1 = isset ( $_REQUEST ['employee_date'] ) ? $_REQUEST ['employee_date'] : "";
$leave_account->employeeId = isset ( $_REQUEST ['employeeId'] ) ? $_REQUEST ['employeeId'] : "";
$leave_account->day_val = isset ( $_REQUEST ['day_val'] ) ? $_REQUEST ['day_val'] : "";

Session::newInstance ()->_setLeaveRules ();
$lrArray = Session::newInstance ()->_get ( "leaveRules" );
$leave_account->leaves = array_merge ( $lrArray ['M'], $lrArray ['Y'], $lrArray ['Q'] );
switch ($action) {
	case "insert" :
		$rand = mt_rand ( 10000, 99999 );
		$leave_account->rule_id = "LR" . $rand;
		$result = $leave_account->insert ();
		break;
	case "update" :
		$result = $leave_account->update ();
		break;
	case "delete" :
		$result = $leave_account->delete ();
		break;
	case "lr_select" :
		$result = $leave_account->lr_select ( $leave_account->lr_select );
		break;
	case "select" :
		$result = $leave_account->select ( $leave_account->leave_account, $leave_account->current_year, $leave_account->leave_rules );
		break;
	case "enable" :
		$result = $leave_account->setEnable ( 1 );
		break;
	case "disable" :
		$result = $leave_account->setEnable ( 0 );
		break;
	case "setelapsedIt":
		$result = $leave_account->setelapsedIt();
		break;
	case "view" :
		break;
	case "getSelectedLeaveDetails" :
		$result = $leave_account->getAdminLeaveDetails ( $leave_account->leaves, $_REQUEST ['affectedIds'], $_REQUEST ['leave_account_year'] );
		break;
	case "getLeaveAccount":
		$result = $leave_account->getLeaveAccount ( $_REQUEST ['affectedIds'], $_REQUEST ['leave_account_year'] );
		break;
	case "getLeaveRequest" :
		$result = $leave_account->getLeaveRequest();
		break;
	case "getComoffLeave":
		$result = $leave_account->getComoffLeave($leave_account->employee_id ,$leave_account->flag);
		break;
	case "getWeekoff":
		$result = $leave_account->getWeekoff($leave_account->employee_id,$leave_account->request_id,$leave_account->current_year,
		                                     $_REQUEST['leaveRuleId'],$leave_account->flag);
		break;
	case "updatelrRequestStatus":
		$result = $leave_account->updatelrRequestStatus($leave_account->employee_id,$leave_account->request_id,$leave_account->compoff);
		break;
	case "getAttendanceReport":
	    $result = $leave_account->getAttendanceReport($leave_account->attSearchIds,$leave_account->month);
		break;
	case "updateAttendance":
		$resultData = $leave_account->updateAttendance();
		$result=$resultData[0];
		$resultdata=$resultData[1];
		break;
	case "getPreviousLeave":
		$result = $leave_account->getPreviousLeave($leave_account->employee_id );
		break;
	case "getTimeDetails":
		$result = $leave_account->getTimeDetails($leave_account->employee_id,$leave_account->date);
		break;
	case "updateTimeDetails":
	    $result = $leave_account->updateTimeDetails($leave_account->employee_id,$leave_account->date1,$leave_account->day_val,$leave_account->check_in,$leave_account->check_out,$leave_account->changeReason,$leave_account->editTime,$leave_account->old_checkin,$leave_account->old_checkout,$leave_account->updated_by);
		break;
	case "punchDelete":
		$result = $leave_account->punchesDelete($_REQUEST ['bioId']);
		break;
	case "attendanceSummayInsert":
		$result = $leave_account->attnSummaryInsert($leave_account->employeeId);
		break;
	case "getLateLOP":
		$result = $leave_account->getlateLOP($_REQUEST['empID']);
		break;
	case "UpdateLateLOP":
		$result = $leave_account->UpdateLateLOP($_REQUEST['emplopdata']);
		break;
	default :
		$result = FALSE;
		exit ();
}
if(is_array($result)){
	if($result[0]== TRUE){
		$resultObj [0] = 'success';
		$resultObj [1] = $action . $result[1];
		$resultObj [2]= $result[2];
		
	}else{
		$resultObj [0] = 'error';
		$resultObj [1] = $action . $result[1];
		$resultObj [2] = $result[2];
	}
	
}else{ 
	if ($result == TRUE) {
		$resultObj [0] = 'success';
		$resultObj [1] = "LeaveAccount " . $action . " Successfully";
		$resultObj [2] = isset($resultdata)?$resultdata:$result;
	} else {
		$resultObj [0] = 'error';
		$resultObj [1] = "LeaveAccount " . $action . " Failed";
		$resultObj [2] = isset($resultdata)?$resultdata:$result;
	}
}
echo json_encode ( $resultObj );
?>