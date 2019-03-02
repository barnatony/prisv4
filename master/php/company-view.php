<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
$columns = array (
		'company_id',
		'company_name',
		'company_city',
		'company_state',
		'company_mobile',
		'company_email',
		'date_of_signUp',
		'no_of_user',
		'info_flag',
		'enabled' 
);
// the table being queried
$table = "company_details";

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
$main_query = mysqli_query ( $conn, "SELECT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}" ) or die ( mysqli_error ( $conn ) );

// get the number of filtered rows
$filtered_rows_query = mysqli_query ( $conn, "SELECT FOUND_ROWS()" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $filtered_rows_query );
$response ['iTotalDisplayRecords'] = $row [0];

// get the number of rows in total
$total_query = mysqli_query ( $conn, "SELECT COUNT(company_id) FROM {$table}" ) or die ( mysqli_error ( $conn ) );
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
		if ($columns [$i] == 'info_flag') {
			/* Special output formatting for 'enable (Actions)' column */
			$row [] = ($aRow [$columns [$i]] == 'A') ? '<a class="view" >
            <button class="btn btn-success btn-xs"><i class="fa fa-eye"></i></button></a>' . '&nbsp;&nbsp;' . '<a class="edit" ><button class="btn btn-primary btn-xs"><i class="fa fa-pencil">
            </i></button></a>&nbsp;&nbsp;<a  title="Approved" href="#"><button class="btn btn-info btn-xs">
<i class="fa fa-check"></i> </button></a>' : ' <a class="view" >
            <button class="btn btn-success btn-xs"><i class="fa fa-eye"></i></button></a>' . '&nbsp;&nbsp;' . '<a class="edit" ><button class="btn btn-primary btn-xs"><i class="fa fa-pencil">
            </i></button></a>&nbsp;&nbsp;<a class="pending"   title="Pending" href="#"><button class="btn btn-danger btn-xs">
<i class="fa fa-spinner"></i></button></a>';
		} elseif ($columns [$i] != '') {
			/* other op */
			$row [] = $aRow [$columns [$i]];
		}
	}
	$response ['aaData'] [] = $row;
}

header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header ( 'Content-type: application/json' );
echo json_encode ( $response );

?>