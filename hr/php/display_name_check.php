<?php
include_once ("../../include/config.php");
error_reporting ( 0 );
$a_json = array ();
$a_json_row = array ();
if (isset ( $_REQUEST ['display_name'] )) {
	$searchTerm = $_REQUEST ['display_name'];
	if ($searchTerm == "Incentive" || $searchTerm == "Bonus" || $searchTerm == "incentive" || $searchTerm == "bonus") {
		array_push ( $a_json, "check_display" );
	} else {
		$stmt = "SELECT display_name FROM company_pay_structure  WHERE display_name='$searchTerm' ";
		$result = mysqli_query ( $conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
	}
} else {
	$searchTerm = $_REQUEST ['alias_name'];
	$str = strtolower ( $searchTerm );
	$searchTerm_s = str_replace ( " ", "_", $str );
	$stmt = "SELECT pay_structure_id FROM company_pay_structure  WHERE pay_structure_id ='$searchTerm_s' ";
	$result = mysqli_query ( $conn, $stmt );
	while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
		array_push ( $a_json, $row );
	}
}
$json = json_encode ( $a_json );
mysqli_stmt_close ( $conn );
mysqli_close ( $conn );
print $json;
?>