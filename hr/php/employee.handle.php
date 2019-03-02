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
require_once ( LIBRARY_PATH. "/deviceApi.class.php"); // Include the File
$deviceApi = new deviceApi($conn); //pass the connection inside
$allowColumns = array ();
$resultObj = array ();
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$empId = $temp [0];
$employee = new Employee ();
Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
Session::newInstance ()->_setMiscPayParams ();
$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );

$allowColumns = "";
$newStr = "";
$miscallowCol = "";
$miscValue="";
foreach ( $allowDeducArray ['A'] as $allow ) {
	$allowColumns .= 's.' . $allow ['pay_structure_id'] . ",";
}
$isdeduCount=0;
foreach ( $allowDeducArray ['D'] as $dedu ) {
	if($dedu ['pay_structure_id']!='c_pt' && $dedu ['pay_structure_id']!='c_it'){
		$isdeduCount++;
	}
}
foreach ( $miscAlloDeduArray ['MP'] as $miscAllo ) {
	$miscallowCol .= 's.' . $miscAllo ['pay_structure_id'] . ",";
	$miscValue.=$miscAllo ['pay_structure_id'].'=0,';
}
$employee->allowColumns = $allowColumns;
$employee->miscallowCol = $miscallowCol;
$employee->miscAllowValue=$miscValue;

$newStr .= substr ( $allowColumns, 0, - 1 );
$employee->pfLimit = isset ( $_REQUEST ['pfLimit'] ) ? $_REQUEST ['pfLimit'] : -1;
$employee->slabId = isset ( $_REQUEST ['slabId'] ) ? $_REQUEST ['slabId'] : "";
$employee->isDeduction=(($isdeduCount)>0)?1:0;//For Query redirect while dedution now applocable
/* Operations To Be Performed */
$employee->promotionId = isset ( $_REQUEST ['promotionId'] ) ? $_REQUEST ['promotionId'] : "";
$employee->promotionFor = isset ( $_REQUEST ['promotionFor'] ) ? $_REQUEST ['promotionFor'] : "";
/*
 * $employee->employeeIds = (
 * ($employee->promotionFor=='E')?$_REQUEST['empIds'] :
 * (($employee->promotionFor=='D')?$_REQUEST['desiIds'] :
 * (($employee->promotionFor=='F')?$_REQUEST['deparIds']:
 * (($employee->promotionFor=='B')?$_REQUEST['bracnIds']:""))));
 */
$employee->employeeId = isset($_REQUEST ['employeeId'])?$_REQUEST['employeeId']:"";
$employee->employeeIds = isset ( $_REQUEST ['empIds'] ) ? $_REQUEST ['empIds'] : '';

$employee->branchIds = isset ( $_REQUEST['branchIds']) ? $_REQUEST ['branchIds'] : '';
$employee->peffectsFrom = isset ( $_REQUEST ['peffectsFrom'] ) ? $_REQUEST ['peffectsFrom'] : "";
$employee->oldpeffectsFrom = isset ( $_REQUEST ['oldpeffectsFrom'] ) ? $_REQUEST ['oldpeffectsFrom'] : "";
$employee->promotedTo = isset ( $_REQUEST ['promotedTo'] ) ? $_REQUEST ['promotedTo'] : "";
$employee->incYesNo = isset ( $_REQUEST ['incVal'] ) ? ($_REQUEST ['incVal'] == 1) ? 1 : 0 : "";
$employee->proYesNo = isset ( $_REQUEST ['proYesNo'] ) ? ($_REQUEST ['proYesNo'] == 1) ? 1 : 0 : "";
// $employee->incAmount=((isset($_REQUEST['incVal'])?$_REQUEST['incVal']:"")==1)?(($_REQUEST['calc']==1)?$_REQUEST ['incAmount']."|A":(($_REQUEST['calc']=='0')?$_REQUEST ['incAmount']."|P":ROUND(((($_REQUEST['newCtc']-$_REQUEST['oldCtc'])/$_REQUEST['oldCtc'])*100),2)."|P")):"0|A";
$employee->incAmount = ((isset ( $_REQUEST ['incVal'] ) ? $_REQUEST ['incVal'] : "") == 1) ? (ROUND ( ((($_REQUEST ['newCtc'] - $_REQUEST ['oldCtc']) / $_REQUEST ['oldCtc']) * 100), 2 ) . "|P") : "0|A";
$employee->leave_account = isset ( $_REQUEST ['id'] ) ? $_REQUEST ['id'] : "";
$employee->updated_by = $_SESSION ['login_id'];
$employee->wipeempId = isset ( $_REQUEST ['wipeempId'] ) ? $_REQUEST ['wipeempId'] : "";
$employee->enrollempId = isset ( $_REQUEST ['enrollempId'] ) ? $_REQUEST ['enrollempId'] : "";
$employee->fp_number ="2";
$employee->save = isset ( $_REQUEST ['save'] ) ? $_REQUEST ['save'] : "";

// indidual Promotion
$employee->ctc = isset ( $_REQUEST ['ctc'] ) ? $_REQUEST ['ctc'] : 0;
$employee->salary_type = isset ( $_REQUEST ['ctc'] ) ? 'ctc' : 'monthly';
$employee->groupValues =isset ($_REQUEST['groupValues'])?$_REQUEST['groupValues']:0;
$employee->groupKey =isset ($_REQUEST['groupKey'])?$_REQUEST['groupKey']:0;
$employee->salaryName = ((isset ( $_REQUEST ['salary_based_on'] ) ? $_REQUEST ['salary_based_on'] : '') != 'noslab') ? isset ( $_REQUEST ['slab'] ) ? $_REQUEST ['slab'] : '' : 'Nil';
$employee->ctc_fixed_component = isset ( $_REQUEST ['ctc_fixed_component'] ) ? $_REQUEST ['ctc_fixed_component'] : 0;
$employee->employee_salary_amount = isset ( $_REQUEST ['grossSalary'] ) ? $_REQUEST ['grossSalary'] : 0;
$employee->inActive =isset ($_REQUEST['inActive'])?$_REQUEST['inActive']:0;
$employee->newDesignation = isset  ($_REQUEST['newDesignation'])?$_REQUEST['newDesignation']:0;
$employee->newDepartment = isset  ($_REQUEST['newDepartment'])?$_REQUEST['newDepartment']:0;
$employee->newBranch = isset  ($_REQUEST['newBranch'])?$_REQUEST['newBranch']:0;
$employee->newTeam = isset  ($_REQUEST['newTeam'])?$_REQUEST['newTeam']:0;
$employee->newStatus = isset  ($_REQUEST['newStatus'])?$_REQUEST['newStatus']:0;
$employee->newShift = isset  ($_REQUEST['newShift'])?$_REQUEST['newShift']:0;
$employee->desigChangeReason = isset  ($_REQUEST['desigChangeReason'])?$_REQUEST['desigChangeReason']:"";
$employee->deptChangeReason = isset  ($_REQUEST['deptChangeReason'])?$_REQUEST['deptChangeReason']: "";
$employee->branchChangeReason = isset  ($_REQUEST['branchChangeReason'])?$_REQUEST['branchChangeReason']: "";
$employee->teamChangeReason = isset  ($_REQUEST['teamChangeReason'])?$_REQUEST['teamChangeReason']: "";
$employee->statusChangeReason = isset  ($_REQUEST['statusChangeReason'])?$_REQUEST['statusChangeReason']: "";
$employee->shiftChangeReason = isset  ($_REQUEST['shiftChangeReason'])?$_REQUEST['shiftChangeReason']:"";
$employee->designationEffectsFrom = isset  ($_REQUEST['designation_effects_from'])?$_REQUEST['designation_effects_from']: "";
$employee->departmentEffectsFrom = isset  ($_REQUEST['dept_effects_from'])?$_REQUEST['dept_effects_from']: "";
$employee->branchEffectsFrom = isset  ($_REQUEST['branch_effects_from'])?$_REQUEST['branch_effects_from']: "";
$employee->teamEffectsFrom = isset  ($_REQUEST['team_effects_from'])?$_REQUEST['team_effects_from']: "";
$employee->statusEffectsFrom = isset  ($_REQUEST['status_effects_from'])?$_REQUEST['status_effects_from']:"";
$employee->dateOfstart = isset  ($_REQUEST['date_of_start'])?$_REQUEST['date_of_start']: "";
$employee->shift_toDate = isset  ($_REQUEST['toDate'])?$_REQUEST['toDate']: "";
$employee->transferedFor = isset  ($_REQUEST['transferedFor'])?$_REQUEST['transferedFor']: "";
$employee->employee_Ids = isset  ($_REQUEST['employee_Ids'])?$_REQUEST['employee_Ids']: "";
$employee->teffectsFrom = isset  ($_REQUEST['teffectsFrom'])?$_REQUEST['teffectsFrom']: "";
$employee->transferedTo = isset  ($_REQUEST['transferedTo'])?$_REQUEST['transferedTo']: "";
$employee->teamIds = isset  ($_REQUEST['teamIds'])?$_REQUEST['teamIds']: "";
$employee->is_teamTrans = isset  ($_REQUEST['is_teamTrans'])?$_REQUEST['is_teamTrans']: "0";
$employee->rep_man = isset  ($_REQUEST['rep_man-id'])?$_REQUEST['rep_man-id']: " ";
$employee->shiftSearchIds=(isset($_REQUEST ['shiftSearchIds']) && (!empty($_REQUEST ['shiftSearchIds']))? " w.employee_id IN ('".implode("','",(array)$_REQUEST ['shiftSearchIds'])."')":"");
$employee->new_ShiftId= isset($_REQUEST ['new_ShiftId'])?$_REQUEST ['new_ShiftId']:"";
$employee->is_surrogate_users= isset($_REQUEST ['is_surrogate_users'])?$_REQUEST ['is_surrogate_users']:"";
//$employee->new_shift_name = isset  ($_REQUEST['new_shift_name'])?$_REQUEST['new_shift_name']: " ";
$rand = mt_rand ( 10000, 99999 );
$employee->actionId = "PM" . $rand;
$employee->conn = $conn;
switch ($action) {
	case "promote" :
		$result = $employee->promote ( $employee->actionId, $employee->promotionFor, $employee->employeeIds, $employee->peffectsFrom, $employee->proYesNo, $employee->promotedTo, $employee->incYesNo, $employee->incAmount );
		break;
	case "increment" :
		$result = $employee->increment ( $employee->actionId, $employee->promotionFor, $employee->employeeIds, $employee->peffectsFrom, $employee->proYesNo, $employee->promotedTo, $employee->incYesNo, $employee->incAmount );
		break;
	case "promotionEdited" :
		if ($_REQUEST ['oldpeffectsFrom'] !== $_REQUEST ['peffectsFrom'] || $_REQUEST ['promotedTo0'] !== $_REQUEST ['promotedTo'] || $_REQUEST ['incVal0'] !== $_REQUEST ['incVal'] || $_REQUEST ['calc0'] !== $_REQUEST ['calc'] || $_REQUEST ['incAmount0'] !== $_REQUEST ['incAmount']) {
			$dataSet = $employee->promotionEdit ( $employee->promotionFor, $employee->employeeIds, $employee->peffectsFrom, $employee->proYesNo, $employee->promotedTo, $employee->incAmount, $employee->promotionId, $employee->oldpeffectsFrom );
		} else {
			$dataSet = TRUE;
		}
		break;
	case "incrementEdit" :
		if ($_REQUEST ['oldpeffectsFrom'] !== $_REQUEST ['peffectsFrom'] || $_REQUEST ['cal0'] !== $_REQUEST ['calc'] || $_REQUEST ['incAmount0'] !== $_REQUEST ['incAmount']) {
			$dataSet = $employee->incrementEdit ( $employee->promotionFor, $employee->employeeIds, $employee->peffectsFrom, $employee->incAmount, $employee->promotionId, $employee->oldpeffectsFrom );
		} else {
			$dataSet = TRUE;
		}
		break;
	case "transferEdit" :
		if ($_REQUEST ['oldpeffectsFrom'] !== $_REQUEST ['peffectsFrom'] || $_REQUEST ['promotedTo0'] !== $_REQUEST ['promotedTo']) {
			$dataSet = $employee->transferEdit ( $employee->promotionFor, $employee->employeeIds, $employee->peffectsFrom, $employee->promotionId, $employee->oldpeffectsFrom, $employee->promotedTo );
		} else {
			$dataSet = TRUE;
		}
		break;
	case "select" :
		$result= $employee->select ();
		break;
	case "wipeEmployee" :
		$result = $employee->wipeEmployee ( $employee->wipeempId ); 
		break;
	case "getEnrollStatus" :
		$resultSet = $employee->getEnrollStatus ($employee->employeeId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
		
	case "enrollEmployee" :
		$results = $deviceApi->enrollOnDevice (  $employee->employeeId,$employee->fp_number,$employee->save);
		$result=(is_array($results))?$results[0]:$results;
		$dataSet = (is_array($results))?$results[1]:'';
		break;
	case "saveEmployee" :
		$results = $deviceApi->enrollOnDevice ( $employee->employeeId,$employee->fp_number,$employee->save );
		$result=(is_array($results))?$results[0]:$results;
		$dataSet = (is_array($results))?$results[1]:'';
		break;
	case "delete" :
		$result = $employee->delete ( $employee->promotionId, $employee->peffectsFrom );
		break;
	case "transferdelete" :
		$result = $employee->transferdelete ( $employee->promotionFor, $employee->promotionId, $employee->peffectsFrom, $employee->employeeIds );
		break;
	case "teamTransferdelete" :
		$result = $employee->teamTransferdelete ( $employee->promotionFor, $employee->promotionId, $employee->peffectsFrom, $employee->employeeIds );
		break;
	case "transfer" :
		$rand = mt_rand ( 10000, 99999 );
		$employee->actionId = "TF" . $rand;
		$result = $employee->transfer();
		break;
	case "teamTransfer" :
		$rand = mt_rand ( 10000, 99999 );
		$employee->actionId = "TM" . $rand;
		$result = $employee->teamTransfer();
		break;
	case "getEmployeeTree" :
		$dataSet = $employee->getEmployeeTreeData();
		die($dataSet);
		$result=TRUE;
		break;
	case "getEmployeePersonelDetails" :
		$resultSet = $employee->getEmployeePersonelDetails ( $_REQUEST ['employee_id'] );
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
	case "letterGeneration" :
		$resultSet = $employee->letters ( $_REQUEST ['employeeId'] );
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "downloadLetter" :
		$result = $employee->downloadLetter ( $_REQUEST ['transferId'], $_REQUEST ['letterName'] );
		break;
	case "getCTCbreakUp" :
		$dataSet = $employee->getCTCbreakUp ( $_REQUEST ['gross'],$_REQUEST ['ctc'], $_REQUEST ['isCTC'],(isset($_REQUEST ['isAnnual'])?$_REQUEST ['isAnnual']:0), (isset($_REQUEST ['pfLimit'])?$_REQUEST ['pfLimit']:"-1"), (isset ( $_REQUEST ['slabId'] ) ? $_REQUEST ['slabId'] : 0), (isset ( $_REQUEST ['allowances'] ) ? $_REQUEST ['allowances'] : "") );
		$result=TRUE;
		break;
	case "incrementByEmployeeId" :
		$result = $employee->incrementByEmployeeId ( $employee->employeeIds, $employee->actionId, (isset ( $_REQUEST ['allowances'] ) ? $_REQUEST ['allowances'] : ""),'NA');
		break;
	case "promoteByEmployeeId" :
		$result = $employee->promoteByEmployeeId ( $employee->employeeIds, $employee->actionId, $employee->promotedTo, $employee->incYesNo, (isset ( $_REQUEST ['allowances'] ) ? $_REQUEST ['allowances'] : "") );
		break;
	case "getEmployeeView" :
		$resultSet = $employee->getEmployeeView ($_REQUEST ['start'],$_REQUEST ['interval'],$_REQUEST ['searchKey'],$_REQUEST ['letterKey'],$employee->inActive,$employee->groupKey,$employee->groupValues,$employee->is_surrogate_users);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getLastInsertedEmployees" :
		$result=$employee->getLastInsertedEmployees();
		break;
	case "downloadCtc" :
		$resultSet = $employee->downloadCtc ( $_REQUEST ['gross'],$_REQUEST ['ctc'], $_REQUEST ['isCTC'],(isset ($_REQUEST ['isAnnual'])? ($_REQUEST ['isAnnual']):0 ), $_REQUEST ['pfLimit'], (isset ( $_REQUEST ['slabId'] ) ? $_REQUEST ['slabId'] : 0), (isset ( $_REQUEST ['allowances'] ) ? $_REQUEST ['allowances'] : "") );
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "perquisites_details" :
		$perqs = new Perquisites ($conn);
		$result=$perqs->getAvailablePerqs($_REQUEST ['employeeId'] );
		break;
	case "perquisiteMapping" :
		$perqs = new Perquisites ($conn);
		$result=$perqs->perquisiteMapping($_REQUEST ['empID'],(isset($_REQUEST['preqs'])?$_REQUEST['preqs']:''),(isset($_REQUEST['ded_amount'])?$_REQUEST['ded_amount']:''),(isset($_REQUEST['deduc_type'])?$_REQUEST['deduc_type']:''), $_SESSION ['login_id'] );
		break;
	case "chooseSlabtype":
		$resultSet = $employee->chooseSlab ($_REQUEST['ctc'] );
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getCurrentDesig_Info":
		$resultSet = $employee->getCurrentDesig_Info($_REQUEST ['employeeId']);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "UpdateDesignation":
		$resultSet = $employee->UpdateDesignation($employee->employeeId, $employee->newDesignation, $employee->desigChangeReason, $employee->designationEffectsFrom, $employee->updated_by);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "DesigChangeHistory":
		$resultSet = $employee->DesigChangeHistory($employee->employeeId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "UpdateDepartment":
		$resultSet = $employee->UpdateDepartment($employee->employeeId, $employee->newDepartment, $employee->deptChangeReason, $employee->departmentEffectsFrom, $employee->updated_by);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getCurrentDepartment_Info":
		$resultSet = $employee->getCurrentDepartment_Info($_REQUEST ['employeeId']);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "DeptChangeHistory":
		$resultSet = $employee->DeptChangeHistory($employee->employeeId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "UpdateBranch":
		$resultSet = $employee->UpdateBranch($employee->employeeId, $employee->newBranch, $employee->branchChangeReason, $employee->branchEffectsFrom, $employee->updated_by);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getCurrentBranch_Info":
		$resultSet = $employee->getCurrentBranch_Info($_REQUEST ['employeeId']);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "BranchChangeHistory":
		$resultSet = $employee->BranchChangeHistory($employee->employeeId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "UpdateTeam":
		$rand = mt_rand ( 10000, 99999 );
		$employee->actionId = "TM" . $rand;
		$resultSet = $employee->UpdateTeam($employee->actionId,$employee->employeeId, $employee->newTeam, $employee->teamChangeReason, $employee->teamEffectsFrom, $employee->updated_by);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getCurrentTeam_Info":
		$resultSet = $employee->getCurrentTeam_Info($_REQUEST ['employeeId']);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "TeamChangeHistory":
		$resultSet = $employee->TeamChangeHistory($employee->employeeId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "UpdatejobStatus":
		$resultSet = $employee->UpdatejobStatus($employee->employeeId, $employee->newStatus , $employee->statusChangeReason, $employee->statusEffectsFrom, $employee->updated_by);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getCurrentjob_status":
		$resultSet = $employee->getCurrentjob_status($_REQUEST ['employeeId']);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "JobstatusChangeHistory":
		$resultSet = $employee->JobstatusChangeHistory($employee->employeeId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "updateShiftDetails":
		$resultSet = $employee->updateShiftDetails($employee->employeeId, $employee->newShift , $employee->shiftChangeReason, $employee->dateOfstart, $employee->shift_toDate, $employee->updated_by);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getCurrentShift":
		$resultSet = $employee->getCurrentShift($_REQUEST ['employeeId']);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "ShiftChangeHistory":
		$resultSet = $employee->ShiftChangeHistory($employee->employeeId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getShiftAllocdetails":
		$resultSet = $employee->getShiftAllocdetails($employee->shiftSearchIds,$employee->new_ShiftId);
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "getLateLOP":
		$resultSet = $employee->getlateLOP();
		$result=$resultSet['result'];
		$dataSet=$resultSet['data'];
		break;
	case "UpdateLateLOP":
		$resultSet = $employee->UpdateLateLOP($_REQUEST['emplopdata']);
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