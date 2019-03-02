<?php
include_once ("../../include/config.php");
$columns = array (
		'w.employee_id',
		'CONCAT(w.employee_name," ",w.employee_lastname)',
		'ROUND(IFNULL(cl.lop+cl.alop,0),1)',
		'ROUND(cl.late_lop,1)',
		's.employee_salary_amount',
		'cl.gross_salary',
		'cl.total_deduction',
		'cl.net_salary',
		'w.designation_id',
		'w.department_id',
		'w.branch_id' 
);
if(isset($_REQUEST ['affectedIds'])&& $_REQUEST ['affectedIds']!='0'){
	$value="'".str_replace(",","','",$_REQUEST ['affectedIds'])."'";
	$extraFilter=(" AND w.employee_id IN ($value)");
	$_SESSION ["emp_id"] =$value;
}else{
	$extraFilter="";
}

$table = "employee_work_details w";
$joins = " INNER JOIN employee_salary_details s
ON w.employee_id = s.employee_id   inner JOIN payroll_preview_temp cl on
s.employee_id=cl.employee_id  ";
// any JOIN operations that you need to do

// filtering
$sql_where = " WHERE w.enabled=1 AND is_processed=0 AND NOT EXISTS (SELECT  p.employee_id FROM   payroll p WHERE  p.employee_id= w.employee_id AND month_year='" . $_SESSION ['monthNo'] . $_SESSION ['payrollYear'] . "' )" . $extraFilter;
if (isset ( $_REQUEST ['sSearch'] ) && $_REQUEST ['sSearch'] != "") {
	$sql_where = "WHERE NOT EXISTS ( SELECT  p.employee_id FROM   payroll p WHERE  p.employee_id= w.employee_id  AND month_year='" . $_SESSION ['monthNo'] . $_SESSION ['payrollYear'] . "' )  AND ";
	foreach ( $columns as $column ) {
		if (strpos ( $column, ' as ' )) {
			$columnArr = explode ( ' ', $column );
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
	// echo "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}";
}
$main_query = mysqli_query ( $conn, "SELECT SQL_CALC_FOUND_ROWS " . implode ( ", ", $columns ) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}" ) or die ( mysqli_error ( $conn ) );
// get the number of filtered rows
$filtered_rows_query = mysqli_query ( $conn, "SELECT FOUND_ROWS()" ) or die ( mysqli_error ( $conn ) );
$row = mysqli_fetch_array ( $filtered_rows_query );
$response ['iTotalDisplayRecords'] = $row [0];
// get the number of rows in total
$total_query = mysqli_query ( $conn, "SELECT COUNT(w.id) FROM {$table}" ) or die ( mysqli_error ( $conn ) );
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
		if ($columns [$i] == 'cl.status_flag') {
			/* Special output formatting for 'enable (Actions)' column */
			$row [] = ($aRow [$columns [$i]] == "1") ? '<a href="#shift_edit" class="edit" data-toggle="modal">Edit</a>' : '<a href="#shift_edit" class="edit" data-toggle="modal">Edit</a>';
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