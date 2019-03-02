<?php
/*
 * ----------------------------------------------------------
 * Filename : employeeReport.handle.php
 * Classname: employeeReport.class.php
 * Author : Raja Sundari
 * Database :
 * Oper :
 *
 * ----------------------------------------------------------
 */
require_once ("../../include/config.php");
/* Include Class Library */
require_once (LIBRARY_PATH . "/employeeReport.class.php");
require_once (LIBRARY_PATH . "/session.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
$Employeereports = new Employeereports ();
//$Employeereports->startDate = isset ( $_REQUEST ['yearBasedon'] ) ? $_REQUEST ['yearBasedon'] == 'finYear' ? substr ( $_REQUEST ['year'], 0, - 2 ) . "-04-01" : $_REQUEST ['year'] . "-01-01" : 0;
$Employeereports->startDate = isset ( $_REQUEST ['year'] ) ? substr ( $_REQUEST ['year'], 0, - 2 ) . "-04-01" : "";
$Employeereports->endDate = date ( 'Y-m-d', strtotime ( "+11 months", strtotime ( $Employeereports->startDate ) ) );
$Employeereports->updated_by = $_SESSION ['employee_id'];
$Employeereports->conn = $conn;

switch ($action) {
	case "annualSalarySelect" :
		$result = $Employeereports->getEmployeesAnnualSalary ( $_SESSION ['employee_id'], $Employeereports->startDate, $Employeereports->endDate );
	    
        
		break;
	case "annualSalarypdf":
			$result = $Employeereports->getEmployeesAnnualSalary (  $_SESSION ['employee_id'] , $Employeereports->startDate, $Employeereports->endDate );
			$Employeereports->getannualSalarystmtpdf($result);
			break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "EmployeeReport " . $action . " Successfull";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "EmployeeReport " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>