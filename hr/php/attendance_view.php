<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
$columns = explode ( ",", $_REQUEST ['sColumns'] );
$colmCreate = implode ( ", ", $columns );
$querystmt = str_replace ( "ma.compoff", "CASE  WHEN ma.compoff IS NULL THEN '-' ELSE ma.compoff END AS compoff", $colmCreate );
// the table being queried
$extraFilter=((isset($_REQUEST ['affectedIds'])&& $_REQUEST ['affectedIds']!='0')? " AND w.employee_id IN ('".str_replace(",","','",$_REQUEST ['affectedIds'])."')":"");
$table = "emp_montly_attendance ma";
// any JOIN operations that you need to do
$joins = "RIGHT JOIN payroll_preview_temp payTmp ON ma.employee_id = payTmp.employee_id
AND ma.month_year = '" . $_SESSION ['current_payroll_month'] . "' AND ma.year = '" . $_SESSION ['financialYear'] . "' INNER JOIN employee_work_details w
ON payTmp.employee_id = w.employee_id AND w.enabled = 1";

// filtering
$sql_where = "" . $extraFilter;
if (isset ( $_REQUEST ['sSearch'] ) && $_REQUEST ['sSearch'] != "") {
	$sql_where = "WHERE w.enabled=1 and payTmp.is_processed=0 " . $extraFilter . " AND ";
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
// echo "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}";
$main_query = mysqli_query ( $conn, "SELECT SQL_CALC_FOUND_ROWS " . $querystmt . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}" ) or die ( mysqli_error ( $conn ) );

// get the number of filtered rows
$filtered_rows_query = mysqli_query ( $conn, "SELECT FOUND_ROWS()" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $filtered_rows_query );
$response ['iTotalDisplayRecords'] = $row [0];

// get the number of rows in total
$total_query = mysqli_query ( $conn, "SELECT COUNT(w.id) FROM {$table} {$joins}" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $total_query );
$response ['iTotalRecords'] = $row [0];

// send back the number requested
if (isset ( $_REQUEST ['sEcho'] )) {
	$response ['sEcho'] = intval ( $_REQUEST ['sEcho'] );
}

$response ['aaData'] = array ();
//  /echo "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}";
// finish getting rows from the main query

while ( $aRow = mysqli_fetch_array ( $main_query ) ) {
	$row = array ();
	for($i = 0; $i < count ( $columns ); $i ++) {
		if ($columns [$i] == 'w.enabled') {
			/* Special output formatting for 'enable (Actions)' column */
			$row [] = ($aRow ['enabled'] == "1") ? '<a href="#shift_edit" class="a_edit" data-toggle="modal" title="Edit">
<button class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></button></a>' : '<a href="#shift_edit"  title="Edit" class="a_edit" data-toggle="modal">
<button class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></button></a>';
		} else if ($columns [$i] != '') {
			/* other op */
			$row [] = $aRow [$i];
		}
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