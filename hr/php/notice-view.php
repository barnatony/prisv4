<?php
include_once ("../../include/config.php");
$columns = array (
		'n.notice_id',
		'w.employee_name',
		'DATE_FORMAT(n.notice_date,"%d/%m/%Y")',
		'DATE_FORMAT(n.last_working_date,"%d/%m/%Y")',
		'r.reason_code',
		'n.process_type',
		'n.notice_id',
		'w.employee_id',
		'n.reason',
		'n.status',
		'n.remark',
		'n.letter_text' 
);

// the table being queried
$table = "employee_work_details w
INNER JOIN   emp_notice_period n ON 
w.employee_id = n.affected_ids AND  '" . $_SESSION ['current_payroll_month'] . "' BETWEEN  n.notice_date AND n.last_working_date
LEFT JOIN   exit_reasons r ON r.exit_id = n.reason";

// any JOIN operations that you need to do
$joins = "";

// filtering
$sql_where = "";
if (isset ( $_REQUEST ['sSearch'] ) && $_REQUEST ['sSearch'] != "") {
	$sql_where = "WHERE ";
	foreach ( $columns as $column ) {
		$sql_where .= $column . " LIKE '%" . $_REQUEST ['sSearch'] . "%' OR ";
	}
	$sql_where = substr ( $sql_where, 0, - 3 );
}
// ordering
$sql_order = "";
if (isset ( $_REQUEST ['iSortCol_0'] )) {
	$sql_order = "ORDER BY  ";
	for($i = 0; $i < $_REQUEST ['iSortingCols']; $i ++) {
		$sql_order .= $columns [$_REQUEST ['iSortCol_' . $i]] . " " . $_REQUEST ['sSortDir_' . $i] . ", ";
	}
	$sql_order = substr_replace ( $sql_order, "", - 2 );
}

// paging
$sql_limit = "";
if (isset ( $_REQUEST ['iDisplayStart'] ) && $_REQUEST ['iDisplayLength'] != '-1') {
	$sql_limit = "LIMIT " . $_REQUEST ['iDisplayStart'] . ", " . $_REQUEST ['iDisplayLength'];
}

$main_query = mysqli_query ( $conn, "SELECT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$sql_where} {$sql_order} {$sql_limit}" ) or die ( mysqli_error ( $conn ) );

// get the number of filtered rows
$filtered_rows_query = mysqli_query ( $conn, "SELECT FOUND_ROWS()" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $filtered_rows_query );
$response ['iTotalDisplayRecords'] = $row [0];

// get the number of rows in total
$total_query = mysqli_query ( $conn, "SELECT COUNT(n.id) FROM {$table}" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $total_query );
$response ['iTotalRecords'] = $row [0];

// send back the number requested
if (isset ( $_REQUEST ['sEcho'] )) {
	$response ['sEcho'] = intval ( $_REQUEST ['sEcho'] );
}

$response ['aaData'] = array ();
// echo "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM {$table} {$sql_where} {$sql_order} {$sql_limit}";
// finish getting rows from the main query
while ( $aRow = mysqli_fetch_array ( $main_query ) ) {
	$row = array ();
	for($i = 0; $i < count ( $columns ); $i ++) {
		/* other op */
		$row [] = $aRow [$i];
	}
	$response ['aaData'] [] = $row;
}

header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header ( 'Content-type: application/json' );
mysqli_close ( $conn );
echo json_encode ( $response );

?>