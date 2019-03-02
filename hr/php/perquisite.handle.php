<?php

require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/perquisite.class.php");

$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Setting Variables */
$perquisites = new perquisites ($conn);
$perquisites->employee_id = isset ( $_REQUEST ['employee_id'] ) ? $_REQUEST ['employee_id'] : "RCI001";
$perquisites->perq_name = isset ( $_REQUEST ['perq_name'] ) ? $_REQUEST ['perq_name'] : "";
$perquisites->usageTrans = isset ( $_REQUEST ['usageTrans'] ) ? $_REQUEST ['usageTrans'] : "";
$perquisites->typesPerq = isset ( $_REQUEST ['typesPerq'] ) ? $_REQUEST ['typesPerq'] : "";
$perquisites->condition = isset ( $_REQUEST ['condition'] ) ? $_REQUEST ['condition'] : "";
$perquisites->ownRent = isset ( $_REQUEST ['ownRent'] ) ? $_REQUEST ['ownRent'] : "";
$perquisites->value = isset ( $_REQUEST ['value'] ) ? $_REQUEST ['value'] : "";
$perquisites->capacity = isset ( $_REQUEST ['capacity'] ) ? $_REQUEST ['capacity'] : "";
$perquisites->updated_by = isset ( $_SESSION ['login_id'] ) ? $_SESSION ['login_id'] : $_SESSION ['employee_id'];
$perquisites->mapped_from = isset ( $_REQUEST ['mapped_from'] ) ? $_REQUEST ['mapped_from'] : "";
switch ($action) {
	case "insert" :
		$resultset = $perquisites->insert ($perquisites->perq_name, $perquisites->usageTrans, $perquisites->typesPerq, $perquisites->condition, $perquisites->ownRent, $perquisites->value, $perquisites->capacity, $perquisites->updated_by);
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	case "getAllperquisites" :
		$resultset = $perquisites->getperquisites ();
		$result=$resultset['result'];
		$data=$resultset['data'];
		break;
	default :
		$result = FALSE;
}
if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Perquisites " . $action . " Successfully";
	$resultObj [2] = (isset($data)?$data:$result);
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Perquisites " . $action . " Failed";
	$resultObj [2] = (isset($data)?$data:$result);
}
echo json_encode ( $resultObj );
?>
