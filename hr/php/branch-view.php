<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
$columns = array (
		'b.branch_id',
		'b.branch_name',
		'b.branch_addr',
		'b.city_pin',
		'b.city',
		'b.state',
		'b.pt_city_id',
		'b.pt_no',
		'b.esi_no',
		'b.pf_no',
		'b.tan_no',
		'b.enabled' 
);
$aColumns = array (
		'branch_id',
		'branch_name',
		'branch_addr',
		'city_pin',
		'city',
		'state',
		'pt_city_id',
		'pt_no',
		'esi_no',
		'pf_no',
		'tan_no',
		'enabled' 
);

// the table being queried
$table = "company_branch b";

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
$total_query = mysqli_query ( $conn, "SELECT COUNT(id) FROM {$table}" ) or die ( mysqli_error ( $conn ) );
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
	for($i = 0; $i < count ( $aColumns ); $i ++) {
		if ($aColumns [$i] == 'enabled') {
			/* Special output formatting for 'enable (Actions)' column */
			$row [] = ($aRow [$aColumns [$i]] == "1") ? '<a class="branch_disable" title="Disable" href="">
<button class="btn btn-success btn-xs" style="padding: 1px 4px;"><i class="fa fa-unlock"></i></button>      
</a><a href="#branch_edit" title="Edit"  class="branch_edit" data-toggle="modal">
<button class="btn btn-primary btn-xs" ><i class="fa fa-pencil"></i></button></a>' : '<a class="branch_enable" title="Enable" href="">
 <button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>
<a href="#branch_edit" title="Edit" class="branch_edit" data-toggle="modal">
<button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a>';
		} else if ($aColumns [$i] != '') {
			/* other op */
			$row [] = $aRow [$aColumns [$i]];
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