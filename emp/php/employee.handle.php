<?php
/*
 * ----------------------------------------------------------
 * Filename : $employee.handle.php For Promotion Inc
 * Classname: $employee.class.php
 * Author : Rajasundari
 * Database : $employee wrkdetails,salary details
 * Oper : promotion Inc Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/employee.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/perquisite.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$employee = new Employee ();
$allowColumns = array ();

Session::newInstance ()->_setLeaveRules ();
$lrArray = Session::newInstance ()->_get ( "leaveRules" );

Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
Session::newInstance ()->_setMiscPayParams ();
$miscallowDeducArray= $miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );

$allowColumns = "";
$newStr = "";
$miscallowCol = "";
foreach ( $allowDeducArray ['A'] as $allow ) {
	$allowColumns .= 's.' . $allow ['pay_structure_id'] . ",";
}
foreach ( $miscAlloDeduArray ['MP'] as $miscAllo ) {
	$miscallowCol .= 's.' . $miscAllo ['pay_structure_id'] . ",";
}
$employee->allowColumns = $allowColumns;
$employee->miscallowCol = $miscallowCol;
/* Operations To Be Performed */
$employee->conn = $conn;
switch ($action) {
	case "getEmployeePayrollData" :
		//include this for payslib get for employee
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/payslipDesign.class.php");
		$payslipdesign = new payslipDesign ( $_SESSION ['monthNo'] . $_SESSION ['payrollYear'], cal_days_in_month ( CAL_GREGORIAN, $_SESSION ['monthNo'], $_SESSION ['payrollYear'] ), $_SESSION ['financialYear'], $_SESSION ['login_id']);
		$payslipdesign->monthYear = isset ( $_REQUEST ['monthYear'] ) ? explode ( " ", $_REQUEST ['monthYear'] ) [1] . "-" . explode ( " ", $_REQUEST ['monthYear'] ) [0] . "-01" : $monthYear;
        $payslipdesign->allowDeduString = $allowDeducArray ["allowString"] . $allowDeducArray ["deduString"] .(isset($miscallowDeducArray ["miscAllowString"])?$miscallowDeducArray ["miscAllowString"]:'').(isset( $miscallowDeducArray ["miscDeduString"])? $miscallowDeducArray ["miscDeduString"]:'');
		$payslipdesign->company_id = $_SESSION ['company_id'];
		$payslipdesign->masterString = $allowDeducArray ["allowString"];
		$payslipdesign->conn = $conn;
		$resultSet = $payslipdesign->getEmployeePayrollData ($_REQUEST ['employee_id'],$payslipdesign->monthYear);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getEmployeePersonelDetails" :
		$resultSet = $employee->getEmployeePersonelDetails ( $_SESSION ['employee_id'] );
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "workDetails" :
		$resultSet = $employee->workDetails ( $_REQUEST ['employeeId'] );
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "salary_details" :
		$resultSet = $employee->salaryDetails ( $_REQUEST ['employeeId'] );
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getCTCbreakUp" :
		$result = $employee->getCTCbreakUp ( $_REQUEST ['gross'], $_REQUEST ['basic'], $_REQUEST ['ctc'], $_REQUEST ['isCTC'], $_REQUEST ['pfLimit'], (isset ( $_REQUEST ['slabId'] ) ? $_REQUEST ['slabId'] : 0), (isset ( $_REQUEST ['allowances'] ) ? $_REQUEST ['allowances'] : "") );
		break;
	case "letterGeneration" :
		$resultSet = $employee->letters ( $_REQUEST ['employeeId'] );
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;

	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Employee " . $action . " Successfully";
	$resultObj [2] = isset($dataSet)?$dataSet:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Employee " . $action . " Failed";
	$resultObj [2] = isset($dataSet)?$dataSet:$result;
}
echo json_encode ( $resultObj );
?>