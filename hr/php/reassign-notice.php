<?php
/*
 * ----------------------------------------------------------
 * Filename: Tansfer.php
 * add new entry to the company_branch database
 *
 *
 *
 * ----------------------------------------------------------
 */
include_once ("../../include/config.php");
$resultObj = array ();
$affected_ids = $_REQUEST ['affected_ids'];
$performed_by = $_SESSION ['login_id'];
$rand = mt_rand ( 10000, 99999 );
$promotion_id = "PM" . $rand;
$promotion_for = 'E';
$is_promotion = $_REQUEST ['is_promotion'];
$promoted_desig_id = $_REQUEST ['promoted_desig_id'];
$promotion_effects_from = $_REQUEST ['promotion_effects_from'];
$is_increment = $_REQUEST ['is_increment'];
$incremented_amount = $_REQUEST ['incremented_amount'] . "|P";
$increment_effects_from = $_REQUEST ['increment_effects_from'];
$pr_affected_ids = "\'" . $affected_ids . "\'";

$qurt = "CALL PROMOTE('$promotion_id','$promotion_for','$pr_affected_ids','$is_promotion',
     '$promoted_desig_id','$promotion_effects_from','$is_increment','$incremented_amount','$increment_effects_from','$performed_by',@affected,@clms)";
$result = mysqli_query ( $conn, $qurt );

if ($result) {
	$resultObj [0] = 'success';
	$resultObj [1] = "Reassigned Successfully";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
} else {
	$resultObj [0] = 'error';
	$resultObj [1] = "Cant be added";
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
}

?>
