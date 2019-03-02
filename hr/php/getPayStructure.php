<?php
include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
$a_json = array (
		"type1" => array (),
		"type2" => array () 
);
$a_json_row = array ();
$stmt = mysqli_prepare ( $conn, "SELECT ps.pay_structure_id,ps.display_name,ps.alias_name, ps.type, ps.sort_order, ps.display_flag 
                                    FROM company_pay_structure ps 
                                    WHERE ps.type IN (?,?)  
                                    ORDER BY ps.sort_order" );
mysqli_stmt_bind_param ( $stmt, 'ss', $_REQUEST ['type1'], $_REQUEST ['type2'] );
$result = mysqli_stmt_execute ( $stmt );
mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name, $alias_name, $type, $sort_order, $display_flag );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	$a_json_row ["pay_structure_id"] = $pay_structure_id;
	$a_json_row ["display_name"] = $display_name;
	$a_json_row ["alias_name"] = $alias_name;
	$a_json_row ["type"] = $type;
	$a_json_row ["sort_order"] = $sort_order;
	$a_json_row ["display_flag"] = $display_flag;
	if ($a_json_row ["type"] == $_REQUEST ['type1']) {
		$a_json ["type1"] [] = $a_json_row;
	} else if ($a_json_row ["type"] == $_REQUEST ['type2']) {
		$a_json ["type2"] [] = $a_json_row;
	}
}
$json = json_encode ( $a_json );
mysqli_close ( $conn );
print $json;
?>