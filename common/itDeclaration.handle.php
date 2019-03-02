<?php
/*
 * ----------------------------------------------------------
 * Filename : $Itdeclaration.handle.php For Promotion Inc
 * Classname: $Itdeclaration.class.php
 * Author : Rajasundari
 * Database : $Itdeclaration wrkdetails,salary details
 * Oper : promotion Inc Actions
 *
 * ----------------------------------------------------------
 */
include_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
include_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/itDeclaration.class.php");
include_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/session.class.php");
$resultObj = array ();
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$Itdeclaration = new ItDeclaration();
Session::newInstance ()->_setMiscPayParams ();
$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
$Itdeclaration->conn = $conn;
$Itdeclaration->uploadFiles = (! empty ( $_FILES ))?$_FILES:"";
$Itdeclaration->postValues = ($_POST)?$_POST:"";
$Itdeclaration->flag=($_SESSION['role']=='HR')?1:0;
$Itdeclaration->inActive = isset ($_REQUEST['inActive'])?$_REQUEST['inActive']:0;
$Itdeclaration->summaryYear = isset ($_REQUEST['year'])?$_REQUEST['year']:$_SESSION ['financialYear'] ;
$Itdeclaration->viewYr = isset ($_REQUEST['year'])?$_REQUEST['year']:$_SESSION ['financialYear'] ;
switch ($action) {
	case "getItempView" :
		$result = $Itdeclaration->getItempView ($_REQUEST['inActive'],$Itdeclaration->summaryYear);
		break;
	case "getItDeclarationData" :
		$result = $Itdeclaration->getItDeclarationData ($_REQUEST['employee_id'],$Itdeclaration->viewYr);
		break;
	case "updateItdeclaration":
		$result = $Itdeclaration->updateItdeclaration ($_REQUEST['employee_id'],$Itdeclaration->uploadFiles,$Itdeclaration->postValues,$Itdeclaration->flag);
		break;
	case "taxSummaryData" :
		$result = $Itdeclaration->taxSummaryData($_REQUEST['employee_id'],$Itdeclaration->summaryYear);
		break;
	case "taxSummaryPdf" :
		$result = $Itdeclaration->taxSummaryPdf($_REQUEST['employee_id'], $_REQUEST ['benefitsPaid'], $_REQUEST ['partBContent'],$_REQUEST['empName'],(isset($_REQUEST['panNum'])?$_REQUEST['panNum']:'Nil'),(isset ($_REQUEST['year'])?$_REQUEST['year']:$_SESSION ['financialYear'] ) );
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "IT " . $action . " Successfully";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "IT " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>