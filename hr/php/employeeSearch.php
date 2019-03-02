<?php
include_once ("../../include/config.php");
error_reporting ( 0 );
$a_json = array ();
$a_json_row = array ();
if (isset ( $_REQUEST ['term'] )) {
	$searchTerm = "%{$_REQUEST['term']}%";
	$stmt = mysqli_prepare ( $conn, "SELECT e.employee_id, e.employee_name, e.employee_lastname, IF(e.enabled=-1,'',b.branch_name) branch_name,IF(e.enabled=-1,'',d.department_name) department_name,IF(e.enabled=-1,'',ds.designation_name) designation_name  
                                        FROM employee_work_details e, company_branch b, company_departments d, company_designations ds 
                                        WHERE e.branch_id = b.branch_id AND e.department_id = d.department_id AND e.designation_id = ds.designation_id
                                        AND (e.employee_name like ? OR e.employee_id like ?) AND e.enabled != 0;" );
	if ($stmt) {
		mysqli_stmt_bind_param ( $stmt, 'ss', $searchTerm, $searchTerm );
	}
} else if (isset ( $_REQUEST ['employee_id'] )) {
	/* For employee Id availability */
	if (isset ( $_REQUEST ['emp_id_prefix'] )) {
		$employee_id = $_REQUEST ['emp_id_prefix'] . $_REQUEST ['employee_id'];
	} else if (isset ( $_REQUEST ['emp_id_suffix'] )) {
		$employee_id = $_REQUEST ['employee_id'] . $_REQUEST ['emp_id_suffix'];
	}
	$stmt = mysqli_prepare ( $conn, "SELECT e.employee_id, e.employee_name, e.employee_lastname, b.branch_name, d.department_name, ds.designation_name 
                                        FROM employee_work_details e, company_branch b, company_departments d, company_designations ds 
                                        WHERE e.branch_id = b.branch_id AND e.department_id = d.department_id AND e.designation_id = ds.designation_id
                                        AND e.employee_id = ?" );
	if ($stmt) {
		mysqli_stmt_bind_param ( $stmt, 's', $employee_id );
	}
} else if (isset ( $_REQUEST ['employee_user_name'] )) {
	/* For employee userName availability */
	$stmt = mysqli_prepare ( $conn, "SELECT e.employee_id, e.employee_name, e.employee_lastname, b.branch_name, d.department_name, ds.designation_name 
                                        FROM employee_work_details e, company_branch b, company_departments d, company_designations ds 
                                        WHERE e.branch_id = b.branch_id AND e.department_id = d.department_id AND e.designation_id = ds.designation_id
                                        AND e.employee_user_name = ?" );
	if ($stmt) {
		mysqli_stmt_bind_param ( $stmt, 's', $_REQUEST ['employee_user_name'] );
	}
}
$result = mysqli_stmt_execute ( $stmt );
mysqli_stmt_bind_result ( $stmt, $employee_id, $employee_name, $employee_lastname,
    /* $employee_user_name, $employee_father_name, $employee_gender, $employee_dob, $employee_image, 
     * $employee_marital_status, $employee_blood_group, $employee_build_name, $employee_street, $employee_area,
     *  $employee_city, $employee_pin_code, $employee_state, $employee_phone, $employee_mobile, $employee_email,
     *   $employee_pan_no, $employee_license_no, $employee_passport_no, $employee_bank_name, $employee_acc_no,
     *    $employee_bank_ifsc, $employee_bank_branch, $employee_doj, $employee_job_status,*/
    		$employee_branch, $employee_department, $employee_designation/*, $employee_reporting_person, $employee_emp_pf_no, $employee_emp_esi_no, $employee_slab_id, $employee_salary_amount, $enabled, $updated_on*/);
while ( mysqli_stmt_fetch ( $stmt ) ) {
	$a_json_row ["employee_id"] = $employee_id;
	$a_json_row ["employee_name"] = $employee_name;
	$a_json_row ["employee_lastname"] = $employee_lastname;
	/*
	 * $a_json_row["employee_user_name"] = $employee_user_name;
	 * $a_json_row["employee_father_name"] = $employee_father_name;
	 * $a_json_row["employee_gender"] = $employee_gender;
	 * $a_json_row["employee_dob"] = $employee_dob;
	 * $a_json_row["employee_image"] = $employee_image;
	 * $a_json_row["employee_marital_status"] = $employee_marital_status;
	 * $a_json_row["employee_blood_group"] = $employee_blood_group;
	 * $a_json_row["employee_build_name"] = $employee_build_name;
	 * $a_json_row["employee_street"] = $employee_street;
	 * $a_json_row["employee_area"] = $employee_area;
	 * $a_json_row["employee_city"] = $employee_city;
	 * $a_json_row["employee_pin_code"] = $employee_pin_code;
	 * $a_json_row["employee_state"] = $employee_state;
	 * $a_json_row["employee_phone"] = $employee_phone;
	 * $a_json_row["employee_mobile"] = $employee_mobile;
	 * $a_json_row["employee_email"] = $employee_email;
	 * $a_json_row["employee_pan_no"] = $employee_pan_no;
	 * $a_json_row["employee_license_no"] = $employee_license_no;
	 * $a_json_row["employee_passport_no"] = $employee_passport_no;
	 * $a_json_row["employee_bank_name"] = $employee_bank_name;
	 * $a_json_row["employee_acc_no"] = $employee_acc_no;
	 * $a_json_row["employee_bank_ifsc"] = $employee_bank_ifsc;
	 * $a_json_row["employee_bank_branch"] = $employee_bank_branch;
	 * $a_json_row["employee_doj"] = $employee_doj;
	 * $a_json_row["employee_job_status"] = $employee_job_status;
	 */
	$a_json_row ["employee_branch"] = $employee_branch;
	$a_json_row ["employee_department"] = $employee_department;
	$a_json_row ["employee_designation"] = $employee_designation;
	/*
	 * $a_json_row["employee_reporting_person"] = $employee_reporting_person;
	 * $a_json_row["employee_emp_pf_no"] = $employee_emp_pf_no;
	 * $a_json_row["employee_emp_esi_no"] = $employee_emp_esi_no;
	 * $a_json_row["employee_slab_id"] = $employee_slab_id;
	 * $a_json_row["employee_salary_amount"] = $employee_salary_amount;
	 * $a_json_row["enabled"] = $enabled;
	 * $a_json_row["updated_on"] = $updated_on;
	 */
	array_push ( $a_json, $a_json_row );
}
$json = json_encode ( $a_json );
mysqli_stmt_close ( $conn );
mysqli_close ( $conn );
print $json;
?>
