<?php 
require_once(dirname(dirname(dirname(__FILE__)))."/include/config.php");
$columns = array( 'action_id','action_for','action_effects_from',
'incremented_amount','affected_ids','performed_by');

// the table being queried
$table = "tasks t";

// filtering
$sql_where = "WHERE promoted_desig_id ='NA' AND incremented_amount !='0|A' AND employees_affected!=0";
if (isset($_REQUEST['sSearch']) && $_REQUEST['sSearch'] != "")
	{
		$sql_where = "WHERE ";
		foreach ($columns as $column)
		{
            if(strpos($column,' as ')){
                $columnArr = explode(' ',$column);
                $column = $columnArr[0];
            }
			$sql_where .= $column . " LIKE '%" .  $_REQUEST['sSearch'] . "%' OR ";
		}
		$sql_where = substr($sql_where, 0, -3);
	}
// ordering
$sql_order = "";
if ( isset( $_REQUEST['iSortCol_0'] ) )
{
    $sql_order = "ORDER BY  ";
    for ( $i = 0; $i <  $_REQUEST['iSortingCols']; $i++ )
    {
        $sql_order .= $columns[$_REQUEST['iSortCol_' . $i]] . " " .  $_REQUEST['sSortDir_' . $i] . ", ";
    }
    $sql_order = substr_replace( $sql_order, "", -2 );
}
$joins =""; 
// paging
$sql_limit = "";
if ( isset( $_REQUEST['iDisplayStart'] ) && $_REQUEST['iDisplayLength'] != '-1' )
{
    $sql_limit = "LIMIT " .  $_REQUEST['iDisplayStart'] . ", " .  $_REQUEST['iDisplayLength'];
}
//echo "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}";
$main_query = mysqli_query($conn,"SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit}")
    or die(mysqli_error($conn));
 
// get the number of filtered rows
$filtered_rows_query = mysqli_query($conn,"SELECT FOUND_ROWS()")
    or die(mysqli_error($conn));
$row = mysqli_fetch_array($filtered_rows_query);
$response['iTotalDisplayRecords'] = $row[0];
 
// get the number of rows in total
$total_query = mysqli_query($conn,"SELECT COUNT(id) FROM {$table}")
    or die(mysqli_error($conn));
$row = mysqli_fetch_array($total_query);
$response['iTotalRecords'] = $row[0];
 
// send back the number requested
if(isset($_REQUEST['sEcho'])){
	$response['sEcho'] = intval($_REQUEST['sEcho']);
}

$response['aaData'] = array();
// finish getting rows from the main query
while ($aRow = mysqli_fetch_row($main_query))
{
  $row = array();
	for ( $i=0 ; $i<count($columns) ; $i++ )
    {
    	
         if  ( $columns[$i] == 'performed_by' ){
            /* Special output formatting for 'enable (Actions)' column */
            $row[] = '
            		<a href="#" title="Delete" class="inc_delete" data-toggle="modal">
            <button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a> <form method="post" action="php/employee.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="'.base64_encode($_SESSION['company_id']."!downloadLetter").'"><input type="hidden" name="transferId" id="transferId" value='.$id.'>
                      <input type="hidden" name="letterName" id="letterName" value="Increment_LTR"> <a  title="Download Transfer" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
    }else if ( $columns[$i] != '' ){
                /* other op */
               $row[] = $aRow[$i]; 
			}
    }
   
    $response['aaData'][] = $row;
}
 
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
mysqli_close($conn);
echo json_encode($response);
 
?>