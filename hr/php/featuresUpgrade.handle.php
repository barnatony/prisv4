<?php
/*
 * ----------------------------------------------------------
 * Filename : Payroll.handle.php
 * Classname: payroll.class.php
 * Author : Rufus Jackson
 * Database : preview_payroll_db
 * Oper : Payroll Run
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/config.php");
/* Include Class Library */
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/featuresUpgrade.class.php");
require_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/notifyEmail.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();
/* Setting Variables */
$features = new FeaturesUpgrade ( );
/* Operations to be performed For PreView */
$features->conn = $conn;
switch ($action) {
	case "upgradeSendEmail" :
		$result = $features->upgradeSendEmail ( $_REQUEST ['feature']);
		break;
	default :
		$result = FALSE;
}
if ($result === TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "FeaturesUpgrade " . $action . " Successfull";
	$resultObj [2] = isset($data)?$data:$result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "FeaturesUpgrade " . $action . " Failed";
	$resultObj [2] =  isset($data)?$data:$result;
}
echo json_encode ( $resultObj );
?>