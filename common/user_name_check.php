<?php
include_once ("../include/config.php");
$a_json = array ();
$a_json_row = array ();
if (isset ( $_REQUEST ['user_name'] )) // used for master company name avaiabliity check
{
	$searchTerm = $_REQUEST ['user_name'];
	$stmt = "SELECT company_user_name FROM company_master_db.company_details  WHERE company_user_name='$searchTerm' ";
	$result = mysqli_query ( $conn, $stmt );
	while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
		array_push ( $a_json, $row );
	}
} elseif (isset ( $_REQUEST ['leave_alias'] )) { // used for leave alias name avialablility
	$searchTerm = $_REQUEST ['leave_alias'];
	$stmt = "SELECT alias_name FROM company_leave_rules  WHERE alias_name='$searchTerm' ";
	$result = mysqli_query ( $conn, $stmt );
	while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
		array_push ( $a_json, $row );
	}
} else if (isset ( $_REQUEST ['consult_user_name'] )) {
	$stmt = "SELECT company_user_name FROM company_master_db.consultant_company_details  WHERE company_user_name='" . $_REQUEST ['consult_user_name'] . "' ";
	$result = mysqli_query ( $conn, $stmt );
	while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
		array_push ( $a_json, $row );
	}
}
$json = json_encode ( $a_json );
mysqli_close ( $conn );
print $json;
?>