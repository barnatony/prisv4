<?php
/*
 * ----------------------------------------------------------
 * Filename :leaverules.handle.php
 * Classname:leaverules.class.php
 * Author :RufusJackson
 * Database :company_leaverules
 * Oper :leaverulesActions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* IncludeClassLibrary */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/leaveRule.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$leavepreceeding = array ();

/* OperationsToBePerformed */

if (isset ( $_REQUEST ['lcarry'] )) {
	$carry_forward = "1";
	$max_cf_days = $_REQUEST ['lmaxc'];
	$remain_cf = isset ( $_REQUEST ['lremain'] ) ? "E" : "L";
	$max_enc_days = $_REQUEST ['lmaxe'];
	$is_encashable = "0";
	$remain_enc = "L";
} elseif (isset ( $_REQUEST ['lenc'] )) {
	$is_encashable = "1";
	$max_cf_days = isset ( $_REQUEST ['lmaxcarry'] ) ? $_REQUEST ['lmaxcarry'] : "0";
	$remain_enc = isset ( $_REQUEST ['lremaine'] ) ? "cf" : "L";
	$max_enc_days = isset ( $_REQUEST ['lmaxenc'] ) ? $_REQUEST ['lmaxenc'] : "0";
	$carry_forward = "0";
	$remain_cf = "L";
} else {
	$is_encashable = "0";
	$max_cf_days = "0";
	$remain_enc = "L";
	$max_enc_days = "0";
	$carry_forward = "0";
	$remain_cf = "L";
}

$leavepreceeding = isset ( $_REQUEST ['lpreed'] ) ? $_REQUEST ['lpreed'] : "";
$leave_in_preceeding = isset ( $_REQUEST ['lpreed'] ) ? $leavepreceeding [0] == 'W' && $leavepreceeding [1] == 'H' ? 'B' : $leavepreceeding [0] : 'N';

$leavesucceeding = isset ( $_REQUEST ['lsuceed'] ) ? $_REQUEST ['lsuceed'] : "";
$leave_in_succeeding = isset ( $_REQUEST ['lsuceed'] ) ? $leavesucceeding [0] == 'W' && $leavesucceeding [1] == 'H' ? 'B' : $leavesucceeding [0] : 'N';

$leave_in_middle = isset ( $_REQUEST ['lpart'] ) ? $_REQUEST ['lpart'] : "";
$leave_in_middle = isset ( $_REQUEST ['lpart'] ) ? $leave_in_middle [0] == 'W' && $leave_in_middle [1] == 'H' ? 'B' : $leave_in_middle [0] : 'N';

if (isset ( $_REQUEST ['lclub'] )) {
	$club = $_REQUEST ['lclub'];
	$club_with = implode ( ",", $club );
} else {
	$club_with = "Male";
}

/*
 * echo$applicable_to=isset($_REQUEST['lappli'])?$applicable_to[0]=='G'&&!$applicable_to[1]=='F'&&!$applicable_to[2]=='M'?'Male'
 * :$applicable_to[0]=='F'&&!$applicable_to[1]=='G'&&!$applicable_to[2]=='T'?'Female':
 * $applicable_to[0]=='T'&&!$applicable_to[1]=='G'&&!$applicable_to[2]=='F'?'ALL'
 * :$applicable_to[0]=='G'&&$applicable_to[1]=='F'&&$applicable_to[2]=='T'?'ALL':
 * $applicable_to[0]=='F'&&$applicable_to[1]=='T'&&$applicable_to[2]=='G'?'ALL':'Nil':'Nil';
 * die();
 */
if (isset ( $_REQUEST ['lappli'] )) {
	$club = $_REQUEST ['lappli'];
	$applicable_to = implode ( ",", $club );
} else {
	$applicable_to = "N";
}

/* Settingiables */
$leaverule = new LeaveRule ();
$leaverule->leave_rule_id = isset ( $_REQUEST ['lrule'] ) ? $_REQUEST ['lrule'] : "";
$leaverule->leave_id = isset ( $_REQUEST ['leave_rule_id'] ) ? $_REQUEST ['leave_rule_id'] : "";
$leaverule->rule_name = isset ( $_REQUEST ['lname'] ) ? $_REQUEST ['lname'] : "";
$leaverule->club = isset ( $_REQUEST ['lappli'] ) ? $_REQUEST ['lappli'] : "";
$leaverule->alias_name = isset ( $_REQUEST ['Lalias'] ) ? strtoupper ( $_REQUEST ['Lalias'] ) : "";
$leaverule->effects_from = isset ( $_REQUEST ['leffect'] ) ? $_REQUEST ['leffect'] : "";
$leaverule->allot_from = isset ( $_REQUEST ['lfrom'] ) ? (isset ( $_REQUEST ['ldays'] ) ? $_REQUEST ['ldays'] . "|" . $_REQUEST ['lfrom'] : "") : "JD|" . (isset ( $_REQUEST ['ldays'] ) ? $_REQUEST ['ldays'] : "");
$leaverule->type = isset ( $_REQUEST ['ltype'] )?$_REQUEST ['ltype']:"";
$leaverule->days_count = isset ( $_REQUEST ['lcount'] ) ? $_REQUEST ['lcount'] : "";
$leaverule->max_combinable = isset ( $_REQUEST ['lmax'] ) ? $_REQUEST ['lmax'] : "";
$leaverule->pro_rata_basis = isset ( $_REQUEST ['lbasic'] ) ? "1" : "0";
$leaverule->allot_on = isset ( $_REQUEST ['lallot'] ) ? $_REQUEST ['lallot'] : "";
$leaverule->calculation_on = isset ( $_REQUEST ['lcalc'] ) ? "CM" : "PM";
$leaverule->round_off = isset ( $_REQUEST ['lround'] ) ? $_REQUEST ['lround'] : "";
// commonforbothcarryandenc
$leaverule->enc_salary = isset ( $_REQUEST ['lencsal'] ) ? $_REQUEST ['lencsal'] : "";
$leaverule->encashable_on = isset ( $_REQUEST ['lencon'] ) ? "Y" : "R";
$leaverule->carry_forward = $carry_forward;
$leaverule->max_cf_days = $max_cf_days;
$leaverule->is_encashable = $is_encashable;
$leaverule->remain_enc = $remain_enc;
$leaverule->max_enc_days = $max_enc_days;
$leaverule->remain_cf = $remain_cf;
$leaverule->leave_in_middle = $leave_in_middle;
$leaverule->leave_in_preceeding = $leave_in_preceeding;
$leaverule->leave_in_succeeding = $leave_in_succeeding;
$leaverule->club_with = $club_with;
$leaverule->applicable_to = $applicable_to;
$leaverule->updated_by = $_SESSION ['login_id'];
$leaverule->next_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['nextyear_date'] : "01/01/" . ($_SESSION ['payrollYear'] + 1);
$leaverule->current_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear']; // For calender year base leave crdit
$leaverule->conn = $conn;
switch ($action) {
	case "insert" :
		$leaverule->leave_rule_id = strtolower ( $_REQUEST ['Lalias'] );
		$result = $leaverule->insert ();
		break;
	case "update" :
		$result = $leaverule->update ();
		break;
	case "view" :
		$result = $leaverule->view ( $leaverule->leave_rule_id );
		break;
	case "delete" :
		$result = $leaverule->delete ();
		break;
	case "select" :
		$result = $leaverule->select ( $leaverule->leave_id );
		break;
	case "header_select" :
		$result = $leaverule->header_select ();
		break;
	case "enable" :
		$result = $leaverule->setEnable ( 1 );
		break;
	case "disable" :
		$result = $leaverule->setEnable ( 0 );
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "LeaveRule " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "LeaveRule " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>