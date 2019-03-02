<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
$columns = array (
		'action_id',
		'w.employee_id',
		'CONCAT(w.employee_name," ",w.employee_lastname) as affected_ids',
		'action_effects_from',
		'DATE_FORMAT(IFNULL(a.processed_on,pro.action_effects_from),"%d/%m/%Y") as processed_on',
		'promoted_desig_id',
		'incremented_amount',
		'performed_by',
		'(CASE WHEN pro.action_effects_from = "'.$_SESSION ['current_payroll_month'].'" OR 
         (IFNULL(a.processed_on,pro.action_effects_from) = "'.$_SESSION ['current_payroll_month'].'" )  THEN 1
          ELSE 0
         END) as deleteFlag');
// the table being queried
$table = "comp_promotions_increments pro";
$joins = "INNER JOIN employee_work_details w
ON designation_id = affected_ids  OR employee_id = affected_ids
OR branch_id = affected_ids  OR department_id = affected_ids
LEFT JOIN arrears a
ON pro.affected_ids = a.employee_id AND a.processed_on='".$_SESSION ['current_payroll_month']."' ";

// filtering
$sql_where = "WHERE promoted_desig_id !='NA' AND employees_affected !=0  AND enabled=1";
if (isset ( $_REQUEST ['sSearch'] ) && $_REQUEST ['sSearch'] != "") {
	$sql_where = "WHERE ";
	$col = array('action_id','w.employee_id','CONCAT(w.employee_name," ",w.employee_lastname)','promoted_desig_id','incremented_amount','DATE_FORMAT(IFNULL(a.processed_on,pro.action_effects_from),"%d/%m/%Y")');
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
		$sql_order .= $columns [$_REQUEST ['iSortCol_' . $i]] . " " . $_REQUEST ['sSortDir_' . $i] . ", ";
	}
	$sql_order = substr_replace ( $sql_order, "", - 2 );
}
// paging
$sql_limit = "";
if (isset ( $_REQUEST ['iDisplayStart'] ) && $_REQUEST ['iDisplayLength'] != '-1') {
	$sql_limit = "LIMIT " . $_REQUEST ['iDisplayStart'] . ", " . $_REQUEST ['iDisplayLength'];
}
//echo "SELECT DISTINCT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}";
$main_query = mysqli_query ( $conn, "SELECT DISTINCT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}" ) or die ( mysqli_error ( $conn ) );

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
		 if ($columns [$i] != '') {
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