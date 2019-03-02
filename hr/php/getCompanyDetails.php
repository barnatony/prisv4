<?php
include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
$a_json = array ();
$a_json_row = array ();
$stmt = mysqli_prepare ( $conn, "SELECT   company_id, company_name, company_type, company_logo, company_build_name,
    		company_street, company_area, company_city, company_pin_code, company_state, company_phone, company_mobile,
    		company_email, company_pin, company_resp1_name, company_resp1_desgn, company_resp1_phone, company_resp1_email,
    		company_resp2_name, company_resp2_desgn, company_resp2_phone, company_resp2_email, company_pan_no, company_cin_no,
    		company_tan_pattern, company_tan_no, company_doi, company_epf_pattern, company_epf_no, company_esi_pattern, company_esi_no,
    		company_emp_id_prefix, company_emp_id_suffix,company_website,salary_days,attendance_period_sdate,hr_2username,hr_1username ,company_user_name,info_flag ,approval_remarks,current_payroll_month,leave_based_on  FROM  company_details ORDER BY info_flag  DESC " );
$result = mysqli_stmt_execute ( $stmt );
mysqli_stmt_bind_result ( $stmt, $company_id, $company_name, $company_type, $company_logo, $company_build_name, $company_street, $company_area, $company_city, $company_pin_code, $company_state, $company_phone, $company_mobile, $company_email, $company_pin, $company_resp1_name, $company_resp1_desgn, $company_resp1_phone, $company_resp1_email, $company_resp2_name, $company_resp2_desgn, $company_resp2_phone, $company_resp2_email, $company_pan_no, $company_cin_no, $company_tan_pattern, $company_tan_no, $company_doi, $company_epf_pattern, $company_epf_no, $company_esi_pattern, $company_esi_no, $company_emp_id_prefix, $company_emp_id_suffix, $company_website, $salary_days, $attendance_period_sdate, $hr_2username, $hr_1username, $company_user_name, $info_flag, $approval_remarks, $current_payroll_month, $leave_based_on );

while ( mysqli_stmt_fetch ( $stmt ) ) {
	$a_json_row ["company_id"] = $company_id;
	$a_json_row ["company_name"] = $company_name;
	$a_json_row ["company_type"] = $company_type;
	$a_json_row ["company_logo"] = $company_logo;
	$a_json_row ["company_build_name"] = $company_build_name;
	$a_json_row ["company_street"] = $company_street;
	$a_json_row ["company_area"] = $company_area;
	$a_json_row ["company_city"] = $company_city;
	$a_json_row ["company_pin_code"] = $company_pin_code;
	$a_json_row ["company_state"] = $company_state;
	$a_json_row ["company_phone"] = $company_phone;
	$a_json_row ["company_mobile"] = $company_mobile;
	$a_json_row ["company_email"] = $company_email;
	$a_json_row ["company_pin"] = $company_pin;
	$a_json_row ["company_resp1_name"] = $company_resp1_name;
	$a_json_row ["company_resp1_desgn"] = $company_resp1_desgn;
	$a_json_row ["company_resp1_phone"] = $company_resp1_phone;
	$a_json_row ["company_resp1_email"] = $company_resp1_email;
	$a_json_row ["company_resp2_name"] = $company_resp2_name;
	$a_json_row ["company_resp2_desgn"] = $company_resp2_desgn;
	$a_json_row ["company_resp2_phone"] = $company_resp2_phone;
	$a_json_row ["company_resp2_email"] = $company_resp2_email;
	$a_json_row ["company_pan_no"] = $company_pan_no;
	$a_json_row ["company_cin_no"] = $company_cin_no;
	$a_json_row ["company_tan_pattern"] = $company_tan_pattern;
	$a_json_row ["company_tan_no"] = $company_tan_no;
	$a_json_row ["company_doi"] = $company_doi;
	$a_json_row ["company_epf_pattern"] = $company_epf_pattern;
	$a_json_row ["company_epf_no"] = $company_epf_no;
	$a_json_row ["company_esi_pattern"] = $company_esi_pattern;
	$a_json_row ["company_esi_no"] = $company_esi_no;
	$a_json_row ["company_emp_id_prefix"] = $company_emp_id_prefix;
	$a_json_row ["company_emp_id_suffix"] = $company_emp_id_suffix;
	$a_json_row ["company_website"] = $company_website;
	$a_json_row ["hr_2username"] = $hr_2username;
	$a_json_row ["hr_1username"] = $hr_1username;
	$a_json_row ["salary_days"] = $salary_days;
	$a_json_row ["attendance_period_sdate"] = $attendance_period_sdate;
	$a_json_row ["company_user_name"] = $company_user_name;
	$a_json_row ["info_flag"] = $info_flag;
	$a_json_row ["approval_remarks"] = $approval_remarks;
	$a_json_row ["current_payroll_month"] = $current_payroll_month;
	$a_json_row ["leave_based_on"] = $leave_based_on;
	array_push ( $a_json, $a_json_row );
}
$json = json_encode ( $a_json );
//print_r($json);
// mysqli_stmt_close($conn);
mysqli_close ( $conn );
print $json;
?>