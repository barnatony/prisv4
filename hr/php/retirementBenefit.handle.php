<?php
/*
 * ----------------------------------------------------------
 * Filename : retirementBenefit.handle.php
 * Classname: retirementBenefit.class.php
 * Author : Rufus Jackson
 * Database : company_pay_structure
 * Oper : Actions
 *
 * ----------------------------------------------------------
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/session.class.php");
/* Include Class Library */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/retirementBenefit.class.php");
$temp = explode ( '!', base64_decode ( $_REQUEST ['act'] ) );
$action = $temp [1];
$resultObj = array ();

/* Operations to be performed */

/* Setting Variables */
$retirementBenefit = new RetirementBenefit ();
// pay structure add arguments
$retirementBenefit->id = isset ( $_REQUEST ['rId'] ) ? $_REQUEST ['rId'] : null;
$retirementBenefit->enabled = isset ( $_REQUEST ['rEnable'] ) ? $_REQUEST ['rEnable'] : 0;

$retirementBenefit->conn = $conn;
$retirementBenefit->updated_by = $_SESSION ['login_id'];

switch ($action) {
	case "benefitEnable" :
		$result = $retirementBenefit->setEnable ( $_REQUEST ['rEnable'] );
		break;
	case "getBenefits" :
		$result = $retirementBenefit->getBenefits ();
		break;
	case "updateRetirement" :
		$retirementBenefit->salary_heads = implode ( ',', $_REQUEST ['salary_head'] );
		$retirementBenefit->salary_heads_it_exempted = implode ( ',', $_REQUEST ['ex_salary_head'] );
		$retirementBenefit->salary_days = $_REQUEST ['salary_days'];
		$retirementBenefit->salary_average_months = $_REQUEST ['average_salary'];
		$retirementBenefit->maximum_amount = $_REQUEST ['maximum_amount'];
		$retirementBenefit->round_service_years = $_REQUEST ['round_year'];
		$result = $retirementBenefit->updateRetirement ( $_REQUEST ['r_benefit'] );
		break;
	case "selectRetirement" :
		$result = $retirementBenefit->selectRetirement ( $_REQUEST ['r_benefit'] );
		break;
	default :
		$result = FALSE;
}

if ($result == TRUE) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Benefit " . $action . " Successfull";
	$resultObj [2] = $result;
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Benefit " . $action . " Failed";
	$resultObj [2] = $result;
}
echo json_encode ( $resultObj );
?>