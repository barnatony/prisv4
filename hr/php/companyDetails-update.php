<?php
/*
 * ----------------------------------------------------------
 * Filename: company-Details-update.php
 * add new entry to the employee tables
 *
 *
 *
 * ----------------------------------------------------------
 */

include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/lib/image.php");

$resultObj = array ();

	$company_id = $_SESSION ['company_id'];
	$updated_by = $_SESSION ['login_id'];
	if(!isset($_REQUEST['form']))
		die("Error");
	
	if($_REQUEST['form']=="#general-form")
	{ 
		$company_name = $_REQUEST ['company_name'];
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["company_logo"]["name"]);
		$file_extension = end($temporary);
		$file_name = '';
		if(($_FILES["company_logo"]["type"] == "image/png")){
			$file_name = 'logo-tmp.png';
		}
		elseif(($_FILES["company_logo"]["type"] == "image/jpg")){
			$file_name = 'logo-tmp.jpg';
		}
		elseif(($_FILES["company_logo"]["type"] == "image/jpeg")){
			$file_name ='logo-tmp.jpeg';
		}
		elseif(($_FILES["company_logo"]["type"] == "image/gif")){
			$file_name = 'logo-tmp.gif';
		}
		else{
			
		}
		
		if ((($_FILES["company_logo"]["type"] == "image/png")
				|| ($_FILES["company_logo"]["type"] == "image/jpg")
				|| ($_FILES["company_logo"]["type"] == "image/jpeg"))
				&& ($_FILES["company_logo"]["size"] < 1000000)//approx. 100kb files can be uploaded
				&& in_array($file_extension, $validextensions)){
					move_uploaded_file($_FILES["company_logo"]["tmp_name"],  "../../compDat/" . $_SESSION ['company_id']."/". $file_name);
					$image = new Image();
					$image->source_path = "../../compDat/" . $_SESSION ['company_id']."/". $file_name;
					$image->target_path = $newfilename_= "../../compDat/" . $_SESSION ['company_id']."/logo.jpg";
					$image->resize(500, 138, ZEBRA_IMAGE_CROP_MIDDLELEFT);
					//now delete the tmp image
					unlink("../../compDat/" . $_SESSION ['company_id']."/". $file_name);
						
		}
		
	$stmt = mysqli_prepare ($conn, "UPDATE company_details SET company_name =?,company_logo =? where company_id =? ")or die(mysqli_stmt_error($stmt));
	mysqli_stmt_bind_param ( $stmt,'sss',$company_name,$newfilename_,$company_id )or die(mysqli_stmt_error($stmt));
	$result = mysqli_stmt_execute ( $stmt );
	
	}


if($_REQUEST['form']=="#company-data1"){
	$company_type = $_REQUEST ['company_type'];
	$company_emp_id_prefix = ($_REQUEST ['emp_id_type'] == "Prefix") ? $_REQUEST ['pre_suf'] : 0;
	$company_emp_id_suffix = ($_REQUEST ['emp_id_type'] !== "Prefix") ? $_REQUEST ['pre_suf'] : 0;
	$company_website = $_REQUEST ['company_website'];
	$stmt = mysqli_prepare ( $conn, "UPDATE company_details SET company_type = ?,company_emp_id_prefix =?,company_emp_id_suffix =?, company_website =?,updated_by=? WHERE company_id =?" );
	mysqli_stmt_bind_param ( $stmt, 'ssssss',$company_type,$company_emp_id_prefix,$company_emp_id_suffix,$company_website,$updated_by,$company_id);
	$result = mysqli_stmt_execute ( $stmt ) or die(mysqli_stmt_error($stmt));
}
else if($_REQUEST['form']=="#company-data2")
{
	$company_doi = $_REQUEST ['company_doi'];
	$company_cin_no = $_REQUEST ['company_cin_no'];
	$company_pan_no = $_REQUEST ['company_pan_no'];
	$company_tan_pattern = $_REQUEST ['company_tan_pattern'];
	$company_tan_no = $company_tan_pattern == "NC" ? "NA" : $_REQUEST ['company_tan_no'];
	$company_epf_pattern = $_REQUEST ['company_epf_pattern'];
	$company_epf_no = $company_epf_pattern == "NC" ? "NA" : $_REQUEST ['company_epf_no'];
	$company_esi_pattern = $_REQUEST ['company_esi_pattern'];
	$company_esi_no = $company_esi_pattern == "NC" ? "NA" : $_REQUEST ['company_esi_no'];

	$stmt = mysqli_prepare ($conn,"UPDATE company_details SET company_doi =STR_TO_DATE(?,'%d/%m/%Y'), company_cin_no =?, company_pan_no =?, company_tan_pattern =?, company_tan_no =?,
      company_epf_pattern =?, company_epf_no =?,company_esi_pattern =?, company_esi_no =?  WHERE company_id =? ");
	mysqli_stmt_bind_param ( $stmt, 'ssssssssss',$company_doi,$company_cin_no,$company_pan_no,$company_tan_pattern,$company_tan_no,$company_epf_pattern,$company_epf_no,$company_esi_pattern,$company_esi_no,$company_id);
	$result = mysqli_stmt_execute ( $stmt ) or die(mysqli_stmt_error($stmt));
	}
	else if($_REQUEST['form']=="#contact-info")
	{
		$company_build_name = $_REQUEST ['company_build_name'];
		$company_street = $_REQUEST ['company_street'];
		$company_area = $_REQUEST ['company_area'];
		$company_pin_code = $_REQUEST ['company_pin_code'];
		$company_city = $_REQUEST ['company_city'];
		$company_state = $_REQUEST ['company_state'];
		$company_phone = $_REQUEST ['company_phone'];
		$company_mobile = $_REQUEST ['company_mobile'];
		$company_email = $_REQUEST ['company_email'];
		
		$stmt = mysqli_prepare ($conn,"UPDATE company_details SET company_build_name =?, company_street =?, company_area =?, company_pin_code =?, company_city =?,
       company_state =?, company_phone = ?, company_mobile =?, company_email =?, info_flag ='P' WHERE company_id =? ");
		mysqli_stmt_bind_param ( $stmt, 'ssssssssss',$company_build_name,$company_street,$company_area,$company_pin_code,$company_city,$company_state,$company_phone,$company_mobile,$company_email,$company_id);
		$result = mysqli_stmt_execute ( $stmt ) or die(mysqli_stmt_error($stmt));
	}
	else if($_REQUEST['form']=="#responsible")
	{
		$company_resp1_name = $_REQUEST ['company_resp1_name'];
		$hr_1username = $_REQUEST ['hr_1username'];
		$company_resp1_desgn = $_REQUEST ['company_resp1_desgn'];
		$company_resp1_phone = $_REQUEST ['company_resp1_phone'];
		$company_resp1_email = $_REQUEST ['company_resp1_email'];
		$company_resp2_name = $_REQUEST ['company_resp2_name'];
		$hr_2username = $_REQUEST ['hr_2username'];
		$company_resp2_desgn = $_REQUEST ['company_resp2_desgn'];
		$company_resp2_phone = $_REQUEST ['company_resp2_phone'];
		$company_resp2_email = $_REQUEST ['company_resp2_email'];
		
		$stmt = mysqli_prepare ($conn,"UPDATE company_details SET company_resp1_name = ?, hr_1username =?, company_resp1_desgn =?, company_resp1_phone =?,
       company_resp1_email =?, company_resp2_name =?, hr_2username =?, company_resp2_desgn =?, company_resp2_phone =?,
       company_resp2_email =?, info_flag ='P' WHERE company_id =? ") or die(mysqli_error($conn));
		mysqli_stmt_bind_param ( $stmt, 'sssssssssss',$company_resp1_name,$hr_1username,$company_resp1_desgn,$company_resp1_phone,$company_resp1_email,$company_resp2_name,$hr_2username,$company_resp2_desgn,$company_resp2_phone,$company_resp2_email,$company_id) or die(mysqli_stmt_error($stmt));
		$result = mysqli_stmt_execute ( $stmt ) ;
	}
	
 else if($_REQUEST['form']=="#salary-attendance-days" ) 
 {
 	$attendance_period_sdate = $_REQUEST ['attendance_period_sdate'];
 	$salary_days = $_REQUEST ['salary_days'];
 	$stmt = mysqli_prepare ($conn,"UPDATE company_details SET attendance_period_sdate = ?, salary_days = ? WHERE company_id = ? ") or die(mysqli_error($conn));
 	mysqli_stmt_bind_param ( $stmt, 'sss',$attendance_period_sdate,$salary_days,$company_id);
 	$result = mysqli_stmt_execute ($stmt) or die(mysqli_stmt_error($stmt));
 }

if ($result) {
	$resultObj [0] = true;
	$resultObj [1] = 'Updated Sucessfully';
	if(  $_REQUEST['form']=="#contact-info" || $_REQUEST['form']=="#responsible" ){
		$stmt = mysqli_prepare ( $conn, "UPDATE " . MASTER_DB_NAME . ".company_details  SET info_flag='P'  WHERE company_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $company_id );
		$result = mysqli_stmt_execute ( $stmt );
		$query1=" INSERT INTO company_details (company_id,company_name,company_user_name,current_payroll_month,company_logo,
		    company_build_name,company_type,company_street,company_area,
		     company_city,company_pin_code,company_website,company_state,company_phone,company_mobile,company_email,
		     company_pin,company_pan_no,company_tan_pattern,company_tan_no,company_doi,company_cin_no,company_epf_pattern,
		     company_epf_no,company_esi_pattern,company_esi_no,company_emp_id_suffix,company_emp_id_prefix,company_resp1_name,
		     hr_1username,company_resp1_desgn,company_resp1_phone,company_resp1_email,company_resp2_name,company_resp2_desgn,
		     hr_2username,company_resp2_phone,company_resp2_email,approval_remarks,info_flag,leave_based_on,
		     email_method,salary_days,attendance_period_sdate,updated_by)
		     (SELECT cs.company_id,cs.company_name,cs.company_user_name,cs.current_payroll_month,cs.company_logo,
		     cs.company_build_name,cs.company_type,cs.company_street,cs.company_area,
		     cs.company_city,cs.company_pin_code,cs.company_website,cs.company_state,cs.company_phone,cs.company_mobile,cs.company_email,
		     cs.company_pin,cs.company_pan_no,cs.company_tan_pattern,cs.company_tan_no,cs.company_doi,cs.company_cin_no,cs.company_epf_pattern,
		     cs.company_epf_no,cs.company_esi_pattern,cs.company_esi_no,cs.company_emp_id_suffix,cs.company_emp_id_prefix,cs.company_resp1_name,
		     cs.hr_1username,cs.company_resp1_desgn,cs.company_resp1_phone,cs.company_resp1_email,cs.company_resp2_name,cs.company_resp2_desgn,
		     cs.hr_2username,cs.company_resp2_phone,cs.company_resp2_email,cs.approval_remarks,cs.info_flag,cs.leave_based_on,
		     cs.email_method,cs.salary_days,cs.attendance_period_sdate,cs.updated_by
		     FROM company_details_shadow cs
		     WHERE cs.info_flag='A' AND cs.updated_on = (SELECT MAX(updated_on) FROM company_details_shadow WHERE info_flag='A'));";
		$stmt1 = mysqli_query ($conn,  $query1) OR die(mysqli_error($conn));
		//$result1 = mysqli_stmt_execute ( $stmt1) ? TRUE : mysqli_error ( $conn );
		$resultObj [1] = 'Submitted for Approval';
	}
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	} else {
		$resultObj [0] = 'error';
		$resultObj [1] = 'Cant be Updated';
		echo json_encode ( $resultObj );
	}
	
	
	

/* image box works ends */

?>
