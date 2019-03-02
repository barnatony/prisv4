<?php
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
$stmt = mysqli_prepare ( $conn, "SELECT DISTINCT pt_city FROM pt_slab WHERE fin_year='" . $_SESSION ['financialYear'] . "'" );
$result = mysqli_stmt_execute ( $stmt );
$result = mysqli_stmt_bind_result ( $stmt, $pt_city );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	$pt_city_s = str_replace ( " ", "_", $pt_city );
	echo "<option value=" . $pt_city_s . ">" . $pt_city . "</option>";
}
?>