<?php
include_once ("../include/config.php");
$rule_id = $_REQUEST ['rule_id'];
$a_json = array ();
$a_json = array (
		"payrol" => array () 
);

$stmt = "SELECT pay_structure_id,display_name,display_flag FROM  company_pay_structure where  display_flag='1' and type='D'";

$result = mysqli_query ( $conn, $stmt );

while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	if ($row ['pay_structure_id'] = "provident_fund" || $row ['pay_structure_id'] = "income_tax" || $row ['pay_structure_id'] = "professional_tax" || $row ['pay_structure_id'] = "esi") {
		$pay_structure_id = $row ['pay_structure_id'];
		$stmt2 = mysqli_query ( $conn, "ALTER TABLE company_allowance_slabs
 ADD $pay_structure_id DECIMAL(10,2) DEFAULT '0.00'  COMMENT 'leaverules' AFTER slab_type;" );
		
		$stmt2 = mysqli_query ( $conn, "ALTER TABLE employee_salary_details
 ADD $pay_structure_id DECIMAL(10,2) DEFAULT '0.00'  COMMENT 'leaverules' AFTER increment_date;" );
		
		$stmt2 = mysqli_query ( $conn, "ALTER TABLE payroll_preview_temp
 ADD $pay_structure_id DECIMAL(10,2) DEFAULT '0.00'  COMMENT 'leaverules' AFTER lop;" );
		
		$stmt2 = mysqli_query ( $conn, "ALTER TABLE payroll
 ADD $pay_structure_id DECIMAL(10,2) DEFAULT '0.00'  COMMENT 'leaverules' AFTER lop;" );
	}
	
	$a_json_row ["pay_structure_id"] = $row ['pay_structure_id'];
	$a_json_row ["display_name"] = $row ['display_name'];
	$a_json_row ["display_flag"] = $row ['display_flag'];
	if ($a_json_row ["display_flag"] == '1') {
		$a_json ["payrol"] [] = $a_json_row;
	}
}
$json = json_encode ( $a_json );
mysqli_stmt_close ( $conn );
mysqli_close ( $conn );
print $json;

?>