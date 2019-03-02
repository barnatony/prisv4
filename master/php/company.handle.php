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
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/company.class.php");
$temp = isset ( $_REQUEST ['act_sub'] ) ? $_REQUEST ['act_sub'] : base64_decode ( $_REQUEST ['act'] );
$action = $temp;
// $action = $_REQUEST['act'];
$resultObj = array ();
$master_company = new Company ();
/* Operations To Be Performed */
$rand = mt_rand ( 10000, 9999999 );

$master_company->company_id = isset ( $_REQUEST ['company_id'] ) ? $_REQUEST ['company_id'] : "CP" . $rand;
$master_company->created_by = isset ( $_REQUEST ['mId'] ) ? $_REQUEST ['mId'] :$_SESSION['master_id'];
$master_company->starts_from = isset ( $_REQUEST ['sFrom'] ) ? $_REQUEST ['sFrom'] : "";
$master_company->company_name = isset ( $_REQUEST ['cName'] ) ? $_REQUEST ['cName'] : "";
$master_company->company_user_name = isset ( $_REQUEST ['cuserName'] ) ? strtolower ( $_REQUEST ['cuserName'] ) : "";
$master_company->company_type = isset ( $_REQUEST ['cType'] ) ? $_REQUEST ['cType'] : "";
$master_company->company_doi = isset ( $_REQUEST ['cDoi'] ) ? $_REQUEST ['cDoi'] : "";
$master_company->company_cin_no = isset ( $_REQUEST ['cCin'] ) ? $_REQUEST ['cCin'] : "";
$master_company->company_emp_id_prefix = (isset ( $_REQUEST ['empIdtype'] ) ? $_REQUEST ['empIdtype'] : "") == 'Prefix' ? (isset ( $_REQUEST ['empPre'] ) ? $_REQUEST ['empPre'] : "") : 0;
$master_company->company_emp_id_suffix = (isset ( $_REQUEST ['empIdtype'] ) ? $_REQUEST ['empIdtype'] : "") !== 'Prefix' ? (isset ( $_REQUEST ['empPre'] ) ? $_REQUEST ['empPre'] : "") : 0;
$master_company->company_build_name = isset ( $_REQUEST ['cBuild'] ) ? $_REQUEST ['cBuild'] : "";
$master_company->company_street = isset ( $_REQUEST ['cStreet'] ) ? $_REQUEST ['cStreet'] : "";
$master_company->company_area = isset ( $_REQUEST ['cArea'] ) ? $_REQUEST ['cArea'] : "";
$master_company->company_pin_code = isset ( $_REQUEST ['cPincode'] ) ? $_REQUEST ['cPincode'] : "";
$master_company->company_city = isset ( $_REQUEST ['cCity'] ) ? $_REQUEST ['cCity'] : "";
$master_company->company_state = isset ( $_REQUEST ['cState'] ) ? $_REQUEST ['cState'] : "";
$master_company->company_phone = isset ( $_REQUEST ['cPhone'] ) ? $_REQUEST ['cPhone'] : "";
$master_company->company_mobile = isset ( $_REQUEST ['cMobile'] ) ? $_REQUEST ['cMobile'] : "";
$master_company->company_email = isset ( $_REQUEST ['cEmail'] ) ? $_REQUEST ['cEmail'] : "";
$master_company->company_website = isset ( $_REQUEST ['cWebsite'] ) ? $_REQUEST ['cWebsite'] : "";
$master_company->company_resp1_name = isset ( $_REQUEST ['cResp1name'] ) ? $_REQUEST ['cResp1name'] : "";
$master_company->hr_1username = isset ( $_REQUEST ['Hr_1name'] ) ? $_REQUEST ['Hr_1name'] : "";
$master_company->company_resp1_desgn = isset ( $_REQUEST ['cResp1desgn'] ) ? $_REQUEST ['cResp1desgn'] : "";
$master_company->company_resp1_phone = isset ( $_REQUEST ['cResp1phone'] ) ? $_REQUEST ['cResp1phone'] : "";
$master_company->company_resp1_email = isset ( $_REQUEST ['cResp1email'] ) ? $_REQUEST ['cResp1email'] : "";
$master_company->company_resp2_name = isset ( $_REQUEST ['cResp2name'] ) ? $_REQUEST ['cResp2name'] : "";
$master_company->hr_2username = isset ( $_REQUEST ['Hr_2name'] ) ? $_REQUEST ['Hr_2name'] : "";
$master_company->company_resp2_desgn = isset ( $_REQUEST ['cResp2desgn'] ) ? $_REQUEST ['cResp2desgn'] : "";
$master_company->company_resp2_phone = isset ( $_REQUEST ['cResp2phone'] ) ? $_REQUEST ['cResp2phone'] : "";
$master_company->company_resp2_email = isset ( $_REQUEST ['cResp2email'] ) ? $_REQUEST ['cResp2email'] : "";
$master_company->leave_based_on = isset ( $_REQUEST ['leaveBasedOn'] ) ? $_REQUEST ['leaveBasedOn'] : "";
$master_company->logo_allotted = isset ( $_REQUEST ['logoSetted'] ) ? $_REQUEST ['logoSetted'] : "";
$master_company->date_of_signUp = date ( "d/m/Y" );
$master_company->enabled = 1;
$master_company->company_db_name = isset ( $_REQUEST ['cdb'] ) ? DB_PREFIX . $_REQUEST ['cdb'] : $master_company->company_user_name;

// reject code when difference of two profiles
$master_company->reject_reason = isset ( $_REQUEST ['rReason'] ) ? $_REQUEST ['rReason'] : "";
// Payroll Roll Back actions
$master_company->currentPayrollMonth = isset ( $_REQUEST ['currentMonth'] ) ? $_REQUEST ['currentMonth'] : "";
$master_company->payrollRollbackMonth = isset ( $_REQUEST ['RollbackMonth'] ) ? $_REQUEST ['RollbackMonth'] : "";

$master_company->ispartial = isset ( $_REQUEST ['ispartial'] ) ? $_REQUEST ['ispartial'] : "";


//Features Add parameter
$master_company->feature_id = isset ( $_REQUEST ['featuredId'] ) ? $_REQUEST ['featuredId'] : "";

// delete Opearation Value
$master_company->deletedEmpId = isset ( $_REQUEST ['deletedEmpId'] ) ? $_REQUEST ['deletedEmpId'] : "";
$master_company->deleteTableDetails = isset ( $_REQUEST ['choosenId'] ) ? $_REQUEST ['choosenId'] : "";
$master_company->loginrole = isset($_SESSION['loginIn'])?$_SESSION['loginIn']:"";

if (isset ( $_REQUEST ['leaveBasedOn'] )) {
	if ($_REQUEST ['leaveBasedOn'] == 'calYear') {
		$master_company->leaveBasedOn = date ( "Y", strtotime ( $master_company->currentPayrollMonth ) );
	} else if ($_REQUEST ['leaveBasedOn'] == 'finYear') {
		$monthNo = explode ( '-', $master_company->currentPayrollMonth ) [1];
		if ($monthNo == "01" || $monthNo == "02" || $monthNo == "03") {
			$master_company->leaveBasedOn = (date ( "Y", strtotime ( $master_company->currentPayrollMonth ) ) - 1) . date ( 'y', strtotime ( $master_company->currentPayrollMonth ) );
		} else {
			$master_company->leaveBasedOn = date ( "Y", strtotime ( $master_company->currentPayrollMonth ) ) . date ( 'y', strtotime ( $master_company->currentPayrollMonth ) ) + 1;
		}
	}
}

// select using company id 
$master_company->conn = $conn;
$master_company->masterConn=mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME);
switch ($action) {
	case "insert" :
		$resultSet = $master_company->create ();
		print_r($resultSet);
		$result=$resultSet['result'];
		$data=$resultSet['data'];
		break;
	case "filterCompany" :
		$resultSet = $master_company->filterCompany (0);
		$result=$resultSet['result'];
		$data=$resultSet['data'];
		break;
	case "update" :
		$result = $master_company->update ();
		break;
	case "reject" :
		$result = $master_company->reject ();
		break;
	case "approve" :
		$result= $master_company->approve ();
		break;
	case "select" :
		$result = $master_company->select ( $master_company->company_id );
		break;
	case "select_profile_differ" :
		$result = $master_company->select_profile_differ ( $master_company->company_id );
		break;
	case "enable" :
		$resultSet = $master_company->setEnable ( 1 );
		$result=$resultSet['result'];
		$data=$resultSet['data'];
		break;
	case "disable" :
		$resultSet = $master_company->setEnable ( 0 );
		$result=$resultSet['result'];
		$data=$resultSet['data'];
		break;
	case "getCompanyDetails" :
		$resultSet = $master_company->getCompanyDetails ( $master_company->currentPayrollMonth );
		$result=$resultSet['result'];
		$data=$resultSet['data'];
		break;
	case "payrollRollBack" :
		$result = $master_company->payrollRollBack ( $master_company->company_id, $master_company->currentPayrollMonth, $master_company->payrollRollbackMonth, $master_company->leaveBasedOn, $master_company->ispartial );
		break;
	case "getEmployeeDetails" :
		$resultSet = $master_company->getEmployeeDetails ( $master_company->company_id, $master_company->deletedEmpId );
		$result=$resultSet['result'];
		$data=$resultSet['data'];
		break;
	case "employeewipe" :
		$result = $master_company->wipeEmployee ( $master_company->company_id, $master_company->deletedEmpId, $master_company->deleteTableDetails );
		break;
	case "getcompanyFeatures" :
		$result = $master_company->getcompanyFeatures ( $master_company->company_id);
		break;
	case "enableFeatures" :
		$result = $master_company->addFeatures ( $master_company->company_id);
		break;
	case "disableFeatures" :
		$result = $master_company->deleteFeatures ( $master_company->company_id);
		break;
	case "view" :
		break;
	default :
		$result = FALSE;
		exit();
}
if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Company " . $action . " Successfull";
	$resultObj [2] =isset($data)?$data:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Company " . $action . " Failed";
	$resultObj [2] = isset($data)?$data:$result;
}
echo json_encode ( $resultObj );
?>