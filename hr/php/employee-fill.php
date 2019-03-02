<?php
include_once ("../include/config.php");
$employee_id = $_REQUEST ['employee_id'];
$newStr = 's.';
$newStr .= str_replace ( ',', ',s.', $_REQUEST ['allowColumns'] );
$allowColumns1 = explode ( ",", $newStr );
$a_json = array ();
$a_json_row = array ();
$stmt = "SELECT w.employee_id ,  w.employee_name , 
                                    w.employee_doj,
                                    js.status_name,
                                    des.designation_name,
                                    dep.department_name,
                                    p.employee_bank_branch ,p.employee_gender,p.employee_id_proof_type,p.emp_ug_marks,p.emp_country,
                                    p.employee_dob ,p.employee_father_name ,p.emp_mother_name,p.employee_spouse_name,p.employee_id_proof_no,p.employee_nationality,
                                    p.employee_id_proof_expiry,p.employee_blood_group,p.emp_sslc_school,p.emp_sslc_board,p.emp_sslc_marks,p.emp_sslc_year,p.emp_sslc_proof,
                                    p.emp_hsc_school,p.emp_hsc_board,p.emp_hsc_marks,p.emp_hsc_year,p.emp_hsc_proof,
                                    p.emp_ug_institute_name,p.emp_ug_university,p.emp_ug_year_passing,p.emp_ug_proof,
                                    p.emp_pg_institute_name,p.emp_pg_university,p.emp_pg_year_passing,p.emp_pg_proof,p.emp_pg_marks,p.permanent_emp_bulidname,p.permanent_emp_area,p.permanent_emp_pincode,
                                    p.permanent_emp_city,p.permanent_emp_state,p.permanent_emp_country,
                                     w.employee_name , 
                                    w.employee_doj,
                                    js.status_name,
                                    des.designation_name,
                                    dep.department_name,
                                    p.employee_bank_branch ,
                                    p.employee_dob ,p.employee_father_name ,
                                    p.employee_gender , p.employee_marital_status ,p.employee_image ,
                                    p.employee_phone , p.employee_mobile ,p.employee_email ,p.employee_build_name ,
                                    p.employee_area , p.employee_street ,p.employee_state ,p.employee_city ,
                                     p.employee_pin_code , p.employee_pan_no ,p.employee_aadhaar_id , 
                                     p.employee_bank_name ,p.employee_acc_no ,p.employee_bank_ifsc , 
                                    js.status_name, w.employee_probation_period ,
                                    w.notice_period , w.employee_reporting_person , man.employee_name as reporting_manager,
                                     w.employee_doj,w.employee_confirmation_date,
                                    br.branch_name,w.employee_emp_pf_no ,w.employee_emp_uan_no ,
                                     w.employee_emp_esi_no ,slab.slab_type,w.enabled, s.slab_id ,
                                     w.resignation_date , 
                                     slab.slab_name,pm.payment_mode_name,
                                     w.branch_id,w.designation_id,w.shift_id,w.weekend_id,
                                     w.department_id,w.payment_mode_id ,w.status_id,s.employee_salary_amount,s.pf_limit," . $newStr . "

                                     FROM
                                     employee_work_details w
                                     INNER JOIN employee_personal_details p 
                                    ON w.employee_id = p.employee_id 
                                    INNER JOIN employee_salary_details s 
                                    ON w.employee_id = s.employee_id
                                    INNER JOIN company_designations des
                                    ON w.designation_id = des.designation_id
                                    INNER JOIN company_departments dep
                                    ON w.department_id = dep.department_id
                                    INNER JOIN company_branch br
                                    ON w.branch_id = br.branch_id
                                    LEFT JOIN company_allowance_slabs slab
                                    ON s.slab_id = slab.slab_id
                                    INNER JOIN company_payment_modes pm
                                    ON w.payment_mode_id = pm.payment_mode_id
                                    INNER JOIN company_job_statuses js
                                    ON w.status_id = js.status_id
                                     AND w.employee_id = '" . $_REQUEST ['employee_id'] . "'
                                    LEFT JOIN employee_work_details man ON w.employee_reporting_person = man.employee_id";

$result = mysqli_query ( $conn, $stmt );
while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	array_push ( $a_json, $row );
}
$json = json_encode ( $a_json );
mysqli_stmt_close ( $conn );
mysqli_close ( $conn );
print $json;
?>
