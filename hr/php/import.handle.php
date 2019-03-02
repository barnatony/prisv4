<?php
/*
 * ----------------------------------------------------------
 * Filename : import.class.php
 * Author : faheen
 * Database : emp_monthly_attendance,payroll_preview_temp
 * Oper : Actions
 *
 * ----------------------------------------------------------
 */
 

require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/import.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/leaveaccount.class.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/phpExcel/PHPExcel.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/phpExcel/PHPExcel/IOFactory.php");

set_time_limit (120);
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$import = new Import ($conn);
$import->filename = isset ( $_FILES ["file"] ["name"] ) ? $_FILES ["file"] ["tmp_name"] : "";
Session::newInstance ()->_setLeaveRules ();
$lrArray = Session::newInstance ()->_get ( "leaveRules" );
$import->leaveRules = array_merge ( $lrArray ['M'], $lrArray ['Y'] );
$import->updated_by = $_SESSION ['login_id'];

//$import->conn = $conn;
switch ($action) {
	case "AttendanceTemplateDownload" :// Download Template
		$result = $import->AttendanceTemplateDownload( $import->leaveRules );
		break;
	case "AttendanceTemplateUpload" : // Upload Template
		$dataVal = $import->AttendanceTemplateUpload( $import->filename, $import->leaveRules );
		$importHead = "Att";
		$result = true;
		break;
	case "AttendanceUpload" :       // Final upload of the downloaded file!!
		$result = $import->AttendanceProcessUploadedFile( $import->filename, $import->leaveRules );
		break;
	case "AttendancesTemplateDownload" :// Download Template
	
		$result = $import->AttendancesTemplateDownload( );
		break;
	case "AttendancesTemplateUpload" :// Upload Template
		$dataVal = $import->AttendancesTemplateUpload($import->filename);
		$importHead = "Att";
		$result = true;
		break;
	case "AttendancesUpload":     // Final upload of the downloaded file!!
		$result = $import->AttendancesProcessUploadedFile( $import->filename, $import->leaveRules );
		break;
	case "ORGSTRTemplateDownload" :    // Download Template
		$result = $import->ORGSTRTemplateDownload();
		break;
	case "ORGSTRTemplateUpload" :      // Upload Template
		$dataVal = $import->ORGSTRTemplateUpload ( $import->filename );
		$importHead = "ORG";
		$result = true;
		break;
	case "ORGSTRUpload" :              // Final upload of the downloaded file!!
		$result = $import->ORGSTRProcessUploadedFile ( $import->filename );
		break;
	case "EmployeeTemplateDownload" :   // Download Template
		$result = $import->EmployeeTemplateDownload ( $import->leaveRules );
		break;
	case "EmployeeTemplateUpload" :     // Upload Template
		$dataVal = $import->EmployeeTemplateUpload( $import->filename, $import->leaveRules );
		$result = true;
		$importHead = "Emp";
		break;
	case "EmployeeUpload" :             // Final upload of the downloaded file!!
		$result = $import->EmployeeProcessUploadedFile( $import->filename, $import->leaveRules );
		break;
	case "Misc_PayDeduTemplateDownload" :    // Download Template
	
		$result = $import->MiscPaydeduTemplateDownload();
		
		break;
	case "Misc_PayDeduTemplateUpload" :    // Upload Template
		$dataVal = $import->MiscPaydeduTemplateUpload( $import->filename );
		$result = true;
		$importHead = "MISC";
		break;
	case "Misc_PayDeduUpload" :             // Final upload of the downloaded file!!
		$result = $import->MiscPaydeduProcessUploadedFile ( $import->filename);
		break;
	case "IncrementTemplateDownload" :    // Download Template
		$result = $import->IncrementTemplateDownload();
		break;
	case "IncrementTemplateUpload" :    // Upload Template
		$dataVal = $import->IncrementTemplateUpload( $import->filename );
		$result = true;
		$importHead = "Increment";
		break;
	case "IncrementUpload" :             // Final upload of the downloaded file!!
		$result = $import->IncrementProcessUploadedFile ( $import->filename);
		break;
	default :
		$result = FALSE;
}
if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = $action . " Successfull";
	$resultObj [2] = $result;
	$resultObj [3] = isset ( $dataVal ) ? $dataVal : "";
	$resultObj [4] = isset ( $importHead ) ? $importHead : "";
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );