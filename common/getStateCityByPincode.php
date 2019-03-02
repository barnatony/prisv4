<?php
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
$a_json = array ();
$a_json_row = array ();
$pincode = $_REQUEST ['pincode'];
$masterConn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
$stmt = mysqli_prepare ( $masterConn, "SELECT id, officename, pincode, officeType, deliverystatus, divisionname, regionname,
    		circlename, taluk, districtname, statename 
FROM pincodes WHERE pincode = ? LIMIT 0,1" );
if ($stmt) {
	mysqli_stmt_bind_param ( $stmt, 's', $pincode );
}
$result = mysqli_stmt_execute ( $stmt );
mysqli_stmt_bind_result ( $stmt, $id, $officename, $pincode, $officeType, $deliverystatus, $divisionname, $regionname, $circlename, $taluk, $districtname, $statename );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	$a_json_row ["pincode"] = $pincode;
	$a_json_row ["divisionname"] = $divisionname;
	$a_json_row ["regionname"] = $regionname;
	$a_json_row ["circlename"] = $circlename;
	$a_json_row ["taluk"] = $taluk;
	$a_json_row ["districtname"] = $districtname;
	$a_json_row ["statename"] = $statename;
	array_push ( $a_json, $a_json_row );
}

$json = json_encode ( $a_json );
mysqli_close ( $conn );
print $json;
?>
