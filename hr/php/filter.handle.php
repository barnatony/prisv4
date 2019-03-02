<?php
/*
 * ----------------------------------------------------------
 * Filename : filter.handle.php
 * Classname: filter.class.php
 * Author : Rufus Jackson
 * Database : company_filter
 * Oper : filter Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/filter.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$filter = new Filter ();
$filter->resp_person_emp_id = isset ( $_REQUEST ['respPerson'] ) ? $_REQUEST ['respPerson'] : "NIL";
$filter->updated_by = $_SESSION ['login_id'];
$filter->conn = $conn;
switch ($action) {
	case "createFilterForScreen" :
		$result = $filter->createFilterForScreen($screen);
		break;
	case "getEmployeesbyFilter" :
		$filter->viewscreen = isset($_REQUEST['screen'])?$_REQUEST['screen']:"";
		$resultSet = $filter->getEmployeesbyFilter($_REQUEST['filterKey'],$_REQUEST['filterValue'],$_REQUEST['loadDisabled'],$_REQUEST['loadprocessedEmp']);
		$result=$resultSet['result'];
		$data=$resultSet['data'];
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "filter " . $action . " Successfully";
	$resultObj [2] = isset($data)?$data:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "filter " . $action . " Failed";
	$resultObj [2] = isset($data)?$data:$result;
}
echo json_encode ( $resultObj );
?>