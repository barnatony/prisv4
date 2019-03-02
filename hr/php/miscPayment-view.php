<?php
include_once (dirname ( dirname ( __DIR__ ) ) . "/include/config.php");
$columns = array (
		'ms.pay_category',
		"if(is_group=0,CONCAT(w.employee_name,' [',ms.pay_affected_ids,']'),'Group Perform')",
		'ms.payment_amount',
		'DATE_FORMAT(ms.effects_from,"%b %Y") as effects_from',
		'DATE_FORMAT(ms.effects_upto,"%b %Y") as effects_upto',
		'ms.remarks',
		'ms.enabled',
		'ms.payment_for',
		'ms.payments_in',
		'ms.payment_id',
		'ms.repetition_count',
		'ms.pay_affected_ids as emp_id',
		'ps.display_name',
		'DATE_FORMAT(ms.updated_on,"%d/%m/%Y") as updated_on'
);
// the table being queried
$table = "misc_payments ms";
$joins = "INNER JOIN company_pay_structure ps ON ms.pay_category= ps.pay_structure_id INNER JOIN employee_work_details w ON ms.pay_affected_ids = w.employee_id";
// filtering

$sql_where = "WHERE effects_upto >='" . $_SESSION ['current_payroll_month'] . "'";
if (isset ( $_REQUEST ['sSearch'] ) && $_REQUEST ['sSearch'] != "") {
	$sql_where = "WHERE ";
	foreach ( $columns as $column ) {
		if (strpos ( $column, ' as ' )) {
			$columnArr = explode ( ' as ', $column );
			$column = $columnArr [0];
		}
		$sql_where .= $column . " LIKE '%" . $_REQUEST ['sSearch'] . "%' OR ";
	}
	$sql_where = substr ( $sql_where, 0, - 3 );
}
// ordering
$sql_order = "";

if (isset ( $_REQUEST ['iSortCol_0'] )) {
	$sql_order = "ORDER BY  ";
	for($i = 0; $i < $_REQUEST ['iSortingCols']; $i ++) {
		$columns [$_REQUEST ['iSortCol_' . $i]];
		$sql_order .= $columns [$_REQUEST ['iSortCol_' . $i]] . " " . $_REQUEST ['sSortDir_' . $i] . ", ";
	}
	$sql_order;
	$sql_order = substr_replace ( $sql_order, "", - 2 );
}

// paging
$sql_limit = "";
if (isset ( $_REQUEST ['iDisplayStart'] ) && $_REQUEST ['iDisplayLength'] != '-1') {
	$sql_limit = "LIMIT " . $_REQUEST ['iDisplayStart'] . ", " . $_REQUEST ['iDisplayLength'];
}

 //echo "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM {$table} {$joins} {$sql_where} GROUP BY payment_id {$sql_order} {$sql_limit}";
$main_query = mysqli_query ( $conn, "SELECT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$joins} {$sql_where} GROUP BY payment_id {$sql_order} {$sql_limit}" ) or die ( mysqli_error ( $conn ) );

// get the number of filtered rows
$filtered_rows_query = mysqli_query ( $conn, "SELECT FOUND_ROWS()" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $filtered_rows_query );
$response ['iTotalDisplayRecords'] = $row [0];

// get the number of rows in total
$total_query = mysqli_query ( $conn, "SELECT COUNT(id) FROM {$table}" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $total_query );
$response ['iTotalRecords'] = $row [0];

// send back the number requested
if (isset ( $_REQUEST ['sEcho'] )) {
	$response ['sEcho'] = intval ( $_REQUEST ['sEcho'] );
}
$response ['aaData'] = array ();
// finish getting rows from the main query
while ( $aRow = mysqli_fetch_row ( $main_query ) ) {
	$row = array ();
	for($i = 0; $i < count ( $columns ); $i ++) {
		$row [] = $aRow [$i];
	}
	
	array_unshift ( $row, "<img class='openClose' src='../css/images/details_open.png'>" );
	
	$response ['aaData'] [] = $row;
}

header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header ( 'Content-type: application/json' );
mysqli_close ( $conn );
echo json_encode ( $response );

?>