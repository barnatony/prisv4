<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");

$columns = array (
		'c.ticket_id',
		'cd.company_name',
		'c.category',
		'c.subject',
		'c.priority',
		'c.status',
		"(CASE WHEN DATE_FORMAT(c.created_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(c.created_on,'%h:%i  %p') WHEN DATE_FORMAT(c.created_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(c.created_on,'%e %b %Y  %h:%i %p') ELSE DATE_FORMAT(c.created_on,'%e/%c/%y  %h:%i %p') END) AS created_on",
		"(CASE WHEN DATE_FORMAT(c.updated_on,'%Y-%m-%d')= CURDATE() THEN DATE_FORMAT(c.updated_on,'%h:%i  %p') WHEN DATE_FORMAT(c.updated_on,'%y') = DATE_FORMAT(NOW(),'%y') THEN DATE_FORMAT(c.updated_on,'%e %b %Y %h:%i %p') ELSE DATE_FORMAT(c.updated_on,'%e/%c/%y  %h:%i %p') END) AS updated_on",
		'c.ticket_id' 
);

// the table being queried
$table = ""  . MASTER_DB_NAME . ".tickets c";

// any JOIN operations that you need to do
$joins = "INNER JOIN company_details cd ON c.company_id = cd.company_id  AND info_flag='A'";

// filtering
$sql_where = "";
if (isset ( $_REQUEST ['sSearch'] ) && $_REQUEST ['sSearch'] != "") {
	$count = 1;
	
	$sql_where = "WHERE ";
	foreach ( $columns as $column ) {
		
		if ($count <= 6) {
			$sql_where .= $column . " LIKE '%" . $_REQUEST ['sSearch'] . "%' OR ";
		} else if ($count == 7) {
			$sql_where = $sql_where . "cd.company_name LIKE '%" . $_REQUEST ['sSearch'] . "%'";
		} else {
			$sql_where = $sql_where;
		}
		$count ++;
	}
	$sql_where = $sql_where;
}

// ordering
$sql_order = "";
$sql_order = "ORDER BY c.updated_on DESC,c.created_on DESC";

// paging
$sql_limit = "";
if (isset ( $_REQUEST ['iDisplayStart'] ) && $_REQUEST ['iDisplayLength'] != '-1') {
	$sql_limit = "LIMIT " . $_REQUEST ['iDisplayStart'] . ", " . $_REQUEST ['iDisplayLength'];
}
$main_query = mysqli_query ( $conn, "SELECT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}" ) or die ( mysqli_error ( $conn ) );
//echo "SELECT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}";
// get the number of filtered rows
$filtered_rows_query = mysqli_query ( $conn, "SELECT FOUND_ROWS()" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $filtered_rows_query );
$response ['iTotalDisplayRecords'] = $row [0];

// get the number of rows in total
$total_query = mysqli_query ( $conn, "SELECT COUNT(c.id) FROM {$table}" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $total_query );
$response ['iTotalRecords'] = $row [0];

// send back the number requested
if (isset ( $_REQUEST ['sEcho'] )) {
	$response ['sEcho'] = intval ( $_REQUEST ['sEcho'] );
}
$response ['aaData'] = array ();

// finish getting rows from the main query
while ( $aRow = mysqli_fetch_array ( $main_query ) ) {
	$row = array ();
	for($i = 0; $i < count ( $columns ); $i ++) {
		
		$row [] = $aRow [$i];
	}
	$response ['aaData'] [] = $row;
}

header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header ( 'Content-type: application/json' );
echo json_encode ( $response );

?>