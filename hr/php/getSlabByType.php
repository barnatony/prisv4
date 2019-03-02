<?php
include_once ("../../include/config.php");
error_reporting ( 0 );
$a_json = array ();
$a_json_row = array ();
$queryStmt = "SELECT * FROM company_allowance_slabs WHERE slab_type = '" . $_REQUEST ['slab_type'] . "' AND enabled=1";
$result = mysqli_query ( $conn, $queryStmt );
while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	array_push ( $a_json, $row );
}
$json = json_encode ( $a_json );
mysqli_stmt_close ( $conn );
mysqli_close ( $conn );
print $json;
?>