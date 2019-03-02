<?php
/*
 * ----------------------------------------------------------
 * Filename: employee-add.php
 * add new entry to the employee tables
 *
 *
 *
 * ----------------------------------------------------------
 */
include_once (dirname ( dirname ( __DIR__ ) ) . "/include/config.php");
include_once (dirname ( dirname ( __DIR__ ) ) . "/include/lib/session.class.php");
function insertImage($tmpFile, $rename_1) {
	$fileName = $tmpFile ["name"]; // The file name
	$fileTmpLoc = $tmpFile ["tmp_name"]; // File in the PHP tmp folder
	$fileType = $tmpFile ["type"]; // The type of file it is
	$fileSize = $tmpFile ["size"]; // File size in bytes
	$temp = explode ( ".", $tmpFile ["name"] );
	$fileExt = ".".end ( $temp );
	$fileErrorMsg = 0;
	
	// START PHP Image Upload Error Handling --------------------------------------------------
	if (! $fileTmpLoc) { // if file not chosen
		echo "ERROR: Please browse for a file before clicking the upload button.";
	} else if (! preg_match ( "/.(gif|jpg|png)$/i", $fileName )) {
		// This condition is only if you wish to allow uploading of specific file types
		echo "ERROR: Your image was not .gif, .jpg, or .png.";
		unlink ( $fileTmpLoc ); // Remove the uploaded file from the PHP temp folder
	} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
		echo "ERROR: An error occured while processing the file. Try again.";
	}
	$employee_id = $_REQUEST ['employee_id'];
	// END PHP Image Upload Error Handling ----------------------------------------------------
	// Place it into your "uploads" folder mow using the move_uploaded_file() function
	$rename = substr ( $rename_1, strpos ( $rename_1, "_" ) + 1 );
	
	$moveResult = move_uploaded_file ( $fileTmpLoc, "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employee_id . "/" . $rename . $fileExt );
	// Check to make sure the move result is true before continuing
	if ($moveResult != true) {
		echo "ERROR: File not uploaded. Try again.";
		unlink ( $fileTmpLoc ); // Remove the uploaded file from the PHP temp folder
		exit ();
	}
	// unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
	// ---------- Include Universal Image Resizing Function --------
	
	$target_file = "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employee_id . "/" . $rename . $fileExt;
	$resized_file = "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employee_id . "/" . $rename . $fileExt;
	$resized_file_name = "../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employee_id . "/" . $rename . $fileExt;
	$wmax = 672;
	$hmax = 1024;
	ak_img_resize ( $target_file, $resized_file, $wmax, $hmax, $fileExt );
	runquery ( $rename, $resized_file_name );
}
function ak_img_resize($target, $newcopy, $w, $h, $ext) {
	list ( $w_orig, $h_orig ) = getimagesize ( $target );
	$scale_ratio = $w_orig / $h_orig;
	if (($w / $h) > $scale_ratio) {
		$w = $h * $scale_ratio;
	} else {
		$h = $w / $scale_ratio;
	}
	$img = "";
	$ext = strtolower ( $ext );
	if ($ext == ".gif") {
		$img = imagecreatefromgif ( $target );
	} else if ($ext == ".png") {
		$img = imagecreatefrompng ( $target );
	} else {
		$img = imagecreatefromjpeg ( $target );
	}
	$tci = imagecreatetruecolor ( $w, $h );
	// imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
	imagecopyresampled ( $tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig );
	imagejpeg ( $tci, $newcopy, 80 );
}
function runquery($tablenae, $resized_file_name) // (databse,table,column_name,identifier)
{
	include ("../../include/config.php");
	$employee_id = $_REQUEST ['employee_id'];
	$colname = "employee_" . $tablenae;
	$stmt = mysqli_prepare ( $conn, "UPDATE employee_personal_details SET $colname=? WHERE employee_id = ?" );
	mysqli_stmt_bind_param ( $stmt, 'ss', $resized_file_name, $employee_id );
	$result = mysqli_stmt_execute ( $stmt );
}

if (isset ( $_REQUEST ['personal'] )) {
	foreach ( $_FILES as $k => $v ) {
		
		if (! empty ( $_FILES [$k] ["name"] )) {
			if (is_array ( $v )) {
				$name = $k;
				insertImage ( $_FILES [$k], $name );
				// foreach ($v as $sk=>$sv){ /// $arr[$sk][$k]=$v; // }
			}
		}
	}
	
	$employee_gender = $_REQUEST ['employee_gender'];
	$employee_mobile = $_REQUEST ['employee_mobile'];
	$employee_email = $_REQUEST ['employee_email'];
	$emp_personal_mobile_number = $_REQUEST ['emp_personal_mobile_number'];
	$emp_personal_email_id = $_REQUEST ['emp_personal_email_id'];
	$employee_father_name = $_REQUEST ['employee_father_name'];
	$emp_mother_name = $_REQUEST ['emp_mother_name'];
	$employee_spouse_name = $_REQUEST ['emp_spouse_name'];
	$emp_father_mobile=$_REQUEST['emp_father_mobile'];
	$emp_mother_mobile=$_REQUEST['emp_mother_mobile'];
	$emp_spouse_mobile=$_REQUEST['emp_spouse_mobile'];
	$emp_mother_dob=$_REQUEST['emp_mother_dob'];
	$emp_spouse_dob=$_REQUEST['emp_spouse_dob'];
	$employee_marital_status = $_REQUEST ['employee_marital_status'];
	$employee_marriagedate = $_REQUEST ['employee_marriagedate'];
	$employee_nationality = $_REQUEST ['employee_nationality'];
	$employee_international = isset($_REQUEST ['employee_international'])?$_REQUEST ['employee_international']:'';
	$employee_blood_group = $_REQUEST ['employee_blood_group'];
	$employee_pc = isset($_REQUEST ['employee_pc'])?$_REQUEST ['employee_pc']:'';
	$employee_pan_no = $_REQUEST ['employee_pan_no'];
	$employee_aadhaar_id = $_REQUEST ['employee_aadhaar_id'];
	$employee_aadhaar_name = $_REQUEST ['employee_aadhaar_name'];
	$employee_bank_name =isset($_REQUEST ['employee_bank_name'])?$_REQUEST ['employee_bank_name']:'';
	$employee_acc_no = $_REQUEST ['employee_acc_no'];
	$employee_bank_ifsc = $_REQUEST ['employee_bank_ifsc'];
	$employee_id_proof_type = $_REQUEST ['employee_id_proof_type'];
	$employee_id_proof_expiry = $_REQUEST ['employee_id_proof_expiry'];
	$employee_id_proof_no = $_REQUEST ['employee_id_proof_no'];
	$employee_id = $_REQUEST ['employee_id'];
	$father_dob = $_REQUEST ['father_dob'];
	$employee_dob = $_REQUEST ['employee_dob'];
	
	$stmt1 = mysqli_prepare ( $conn, "UPDATE employee_personal_details p
		                       INNER JOIN employee_work_details w
							   ON p.employee_id = w.employee_id SET p.employee_dob=STR_TO_DATE(?,'%d/%m/%Y'),p.employee_gender=?,
p.father_dob=STR_TO_DATE(?,'%d/%m/%Y'),p.employee_mobile=?,p.employee_personal_mobile=?,p.employee_personal_email=?,p.employee_marital_status=?,p.employee_email=?,p.employee_father_name=?,
p.emp_mother_name=?, p.spouse_name=?, p.father_mobile=?, p.mother_mobile=?, p.spouse_mobile=?, p.mother_dob=STR_TO_DATE(?,'%d/%m/%Y'), p.spouse_dob=STR_TO_DATE(?,'%d/%m/%Y'), p.employee_marriagedate=STR_TO_DATE(?,'%d/%m/%Y'),
p.employee_nationality=?,p.employee_international=?,p.employee_blood_group=?,p.employee_pc=?,p.employee_pan_no=?,p.employee_aadhaar_id=?, p.employee_aadhaar_name=?,
p.employee_bank_name=?,p.employee_acc_no=?,p.employee_bank_ifsc=?,p.employee_id_proof_type=?,p.employee_id_proof_expiry=?,
p.employee_id_proof_no=?,w.employee_lastname='" . $_REQUEST ['employee_lastname'] . "',w.employee_name='" . $_REQUEST ['employee_name'] . "' where p.employee_id=? " );
	
mysqli_stmt_bind_param ( $stmt1, 'sssssssssssssssssssssssssssssss', $employee_dob, $employee_gender, $father_dob, $employee_mobile,$emp_personal_mobile_number, $emp_personal_email_id, $employee_marital_status, $employee_email, $employee_father_name, $emp_mother_name, $employee_spouse_name, $emp_father_mobile, $emp_mother_mobile, $emp_spouse_mobile, $emp_mother_dob, $emp_spouse_dob, $employee_marriagedate, $employee_nationality,$employee_international, $employee_blood_group, $employee_pc, $employee_pan_no, $employee_aadhaar_id, $employee_aadhaar_name,$employee_bank_name, $employee_acc_no, $employee_bank_ifsc, $employee_id_proof_type, $employee_id_proof_expiry, $employee_id_proof_no, $employee_id );

	$result1 = mysqli_stmt_execute ( $stmt1 );
	
	if ($result1) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'Personal Details Updated Successfully';
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}else{
		$resultObj [0] = 'error';
		$resultObj [1] = 'Personal Details Updated Failed';
		$resultObj [2] = mysqli_error( $conn );
		echo json_encode ( $resultObj );
	}
}

if (isset ( $_REQUEST ['address'] )) {
	$employee_id = $_REQUEST ['employee_id'];
	$employee_build_name = $_REQUEST ['employee_build_name'];
	$employee_area = $_REQUEST ['employee_area'];
	$employee_pin_code = $_REQUEST ['employee_pin_code'];
	$employee_city = $_REQUEST ['employee_city'];
	$employee_district = $_REQUEST ['employee_district'];
	$employee_state = $_REQUEST ['employee_state'];
	$emp_country = $_REQUEST ['emp_country'];
	$permanent_emp_bulidname = $_REQUEST ['permanent_emp_bulidname'];
	$permanent_emp_area = $_REQUEST ['permanent_emp_area'];
	$permanent_emp_pincode = $_REQUEST ['permanent_emp_pincode'];
	$permanent_emp_city = $_REQUEST ['permanent_emp_city'];
	$permanent_emp_dist = $_REQUEST ['permanent_emp_dist'];
	$permanent_emp_state = $_REQUEST ['permanent_emp_state'];
	$permanent_emp_country = $_REQUEST ['permanent_emp_country'];
	$employee_pt_adddress = $_REQUEST ['employee_pt_adddress'];
	
	$stmt1 = mysqli_prepare ( $conn, "UPDATE  employee_personal_details SET employee_build_name=?,employee_area=?,employee_pin_code=?, employee_city=?,employee_district=?,
 employee_state=?, emp_country=?,
 permanent_emp_bulidname=?, permanent_emp_area=?,employee_pt_adddress=?,permanent_emp_pincode=?, permanent_emp_city=?, permanent_emp_dist=?, permanent_emp_state=?,permanent_emp_country=?  where employee_id=? " );
	mysqli_stmt_bind_param ( $stmt1, 'ssssssssssssssss', $employee_build_name, $employee_area, $employee_pin_code, $employee_city, $employee_district, $employee_state, $emp_country, $permanent_emp_bulidname, $permanent_emp_area, $employee_pt_adddress, $permanent_emp_pincode, $permanent_emp_city, $permanent_emp_dist, $permanent_emp_state, $permanent_emp_country, $employee_id );
	$result2 = mysqli_stmt_execute ( $stmt1 );
	if ($result2) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'Address  Updated Successfully';
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}else{
		$resultObj [0] = 'error';
		$resultObj [1] = 'Address Updated Failed';
		$resultObj [2] = mysqli_error( $conn );
		echo json_encode ( $resultObj );
	}
}

if (isset ( $_REQUEST ['education'] )) {
	
	foreach ( $_FILES as $k => $v ) {
		
		if (! empty ( $_FILES [$k] ["name"] )) {
			if (is_array ( $v )) {
				$name = $k;
				insertImage ( $_FILES [$k], $name );
				// foreach ($v as $sk=>$sv){ /// $arr[$sk][$k]=$v; // }
			}
		}
	}
	$employee_id = $_REQUEST ['employee_id'];
	$emp_sslc_school = $_REQUEST ['emp_sslc_school'];
	$emp_sslc_board = $_REQUEST ['emp_sslc_board'];
	$emp_sslc_marks = $_REQUEST ['emp_sslc_marks'];
	$emp_sslc_year = $_REQUEST ['emp_sslc_year'];
	$emp_ug_institute_name = $_REQUEST ['emp_ug_institute_name'];
	$emp_ug_university = $_REQUEST ['emp_ug_university'];
	$emp_ug_degree = $_REQUEST['emp_ug_degree'];
	$emp_ug_major_subject = $_REQUEST['emp_ug_major_subject'];
	$emp_ug_marks = $_REQUEST ['emp_ug_marks'];
	$emp_ug_year_passing = $_REQUEST ['emp_ug_year_passing'];
	$emp_hsc_school = $_REQUEST ['emp_hsc_school'];
	$emp_hsc_board = $_REQUEST ['emp_hsc_board'];
	$emp_hsc_marks = $_REQUEST ['emp_hsc_marks'];
	$emp_hsc_year = $_REQUEST ['emp_hsc_year'];
	$emp_pg_institute_name = $_REQUEST ['emp_pg_institute_name'];
	$emp_pg_university = $_REQUEST ['emp_pg_university'];
	$emp_pg_degree = $_REQUEST['emp_pg_degree'];
	$emp_pg_major_subject = $_REQUEST['emp_pg_major_subject'];
	$emp_pg_marks = $_REQUEST ['emp_pg_marks'];
	$emp_pg_year_passing = $_REQUEST ['emp_pg_year_passing'];
	$stmt1 = mysqli_prepare ( $conn, "UPDATE  employee_personal_details SET emp_sslc_school=?,emp_sslc_board=?,emp_sslc_marks=?, emp_sslc_year=?,
 emp_ug_institute_name=?, emp_ug_university=?, emp_ug_degree=?, emp_ug_major_subject=?, emp_ug_marks=?, emp_ug_year_passing=?,emp_hsc_school=?,emp_hsc_board=?, 
 emp_hsc_marks=?, emp_hsc_year=?,emp_pg_institute_name=?,emp_pg_university=?, emp_pg_degree=?, emp_pg_major_subject=?, emp_pg_marks=?,emp_pg_year_passing=?  where employee_id=? " );
	mysqli_stmt_bind_param ( $stmt1, 'sssssssssssssssssssss', $emp_sslc_school, $emp_sslc_board, $emp_sslc_marks, $emp_sslc_year, $emp_ug_institute_name, $emp_ug_university, $emp_ug_degree, $emp_ug_major_subject,  $emp_ug_marks, $emp_ug_year_passing, $emp_hsc_school, $emp_hsc_board, $emp_hsc_marks, $emp_hsc_year, $emp_pg_institute_name, $emp_pg_university, $emp_pg_degree, $emp_pg_major_subject,$emp_pg_marks, $emp_pg_year_passing, $employee_id );
	$result3 = mysqli_stmt_execute ( $stmt1 );
	
	if ($result3) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'Education Details  Updated Successfully';
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}else{
		$resultObj [0] = 'error';
		$resultObj [1] = 'Education Updated Failed';
		$resultObj [2] = mysqli_error( $conn );
		echo json_encode ( $resultObj );
	}
}

if (isset ( $_REQUEST ['work_items'] )) {
	$employee_id = $_REQUEST ['employee_id'];
	$employee_reporting_person = $_REQUEST ['rep_man-id'];
	$payment_mode_id = $_REQUEST ['payment_mode_id'];
	$employee_emp_pf_no = $_REQUEST ['employee_emp_pf_no'];
	$employee_emp_uan_no = $_REQUEST ['employee_emp_uan_no'];
	$employee_emp_esi_no = $_REQUEST ['employee_emp_esi_no'];
	$employee_doj = $_REQUEST ['employee_doj'];
	$employee_probation_period = $_REQUEST ['employee_probation_period'];
	$employee_confirmation_date = $_REQUEST ['employee_confirmation_date'];
	$updated_by = $_SESSION ['login_id'];
	$stmt1 = mysqli_prepare ( $conn, "UPDATE  employee_work_details SET payment_mode_id=?, employee_emp_pf_no=?,employee_emp_uan_no=?,employee_emp_esi_no=?,
 employee_doj=STR_TO_DATE(?,'%d/%m/%Y'),employee_probation_period=?, employee_confirmation_date=STR_TO_DATE(?,'%d/%m/%Y'),updated_by=?,employee_reporting_person=? where employee_id=? " );
	mysqli_stmt_bind_param ( $stmt1, 'ssssssssss', $payment_mode_id, $employee_emp_pf_no, $employee_emp_uan_no, $employee_emp_esi_no,  $employee_doj, $employee_probation_period, $employee_confirmation_date, $updated_by, $employee_reporting_person, $employee_id );
	$result3 = mysqli_stmt_execute ( $stmt1 );
	
	if ($result3) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'Work Details Updated Successfully';
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}else{
		$resultObj [0] = 'error';
		$resultObj [1] = 'Work Details Updated Failed';
		$resultObj [2] = mysqli_error( $conn );
		echo json_encode ( $resultObj );
	}
}

if (isset ( $_REQUEST ['workExp'] )) {
	$queryStmt = "";
	mysqli_query ( $conn, "DELETE  FROM emp_work_history WHERE employee_id='" . $_REQUEST ['employee_id'] . "'" );
	$queryStmt .= "REPLACE INTO emp_work_history  (employee_id,company_name, contact_email, prev_reporting_manager, location,`from`,`to`,designation,ctc,updated_by) Values ";
	for($i = 1; $i < $_REQUEST ['totExper']; $i ++) {
		$queryStmt .= "('" . $_REQUEST ['employee_id'] . "','" . $_REQUEST [$i . '_cName'] . "','" . $_REQUEST [$i . '_contact_email'] . "','" . $_REQUEST [$i . '_reporting_manager'] . "','" . $_REQUEST [$i . '_location'] . "','" . $_REQUEST [$i . '_From'] . "','" . $_REQUEST [$i . '_To'] . "','" . $_REQUEST [$i . '_desig'] . "','" . $_REQUEST [$i . '_Ctc'] . "','" . $_SESSION ['login_id'] . "'),";
	}
	mysqli_query ( $conn, substr ( $queryStmt, 0, - 1 ) );
	$resultObj [0] = 'success';
	$resultObj [1] = 'Work History Updated Successfully';
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
}

if (isset ( $_REQUEST ['fromSalary']) || isset ( $_REQUEST ['mapSal'] )) {
	$employee_id =$_REQUEST['employee_id'];
	$pfLimit = isset($_REQUEST ['pf_limit'])?$_REQUEST ['pf_limit']:-1 ;
	$allowanceColumnsValues = "";
	foreach ( $_REQUEST ['allowances'] as $key => $val ) {
		$val= str_replace(',','',$val);
		$allowanceColumnsValues .= "," . $key . "=" . $val;
	}
	
	$ctc = isset ( $_REQUEST ['ctc'] ) ? $_REQUEST ['ctc'] : 0;
	$ctc = str_replace(',','',$ctc);
	$salaryType = isset ( $_REQUEST ['ctc'] ) ?'ctc' :'monthly';
	$slab = isset ( $_REQUEST ['slab'] ) ? $_REQUEST ['slab']: 0 ;
	$ctc_fixed_component =  isset( $_REQUEST ['ctc_fixed_component'])? $_REQUEST ['ctc_fixed_component'] : 0;
	$ctc_fixed_component = str_replace(',','',$ctc_fixed_component);
	$input_type = $_REQUEST ['input_type'] ;
	$isAnnual = 0;
	if($input_type=='annual' && $ctc >0 ){
		$isAnnual = 1;
	}
	$employee_salary_amount = $_REQUEST ['grossSalary'];
	Session::newInstance ()->_setMiscPayParams ();
	$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
	$miscValue="";
	foreach ( $miscAlloDeduArray ['MP'] as $miscAllo ) {
		$miscValue.=$miscAllo ['pay_structure_id'].'=0,';
	}
	
	$stmt = mysqli_query (  $conn, 'UPDATE employee_salary_details SET  '.$miscValue.'slab_id=\'' . $slab . '\',ctc=' . $ctc . ',salary_type=\'' . $salaryType . '\',isAnnual='.$isAnnual.',ctc_fixed_component=' . $ctc_fixed_component . ',
			pf_limit=' . $pfLimit . ',employee_salary_amount=' . $employee_salary_amount . ' 
			' . $allowanceColumnsValues . ',updated_by="' . $_SESSION ['login_id'] . '" WHERE employee_id="' . $_REQUEST ['employee_id'] . '"' );

	if ($stmt) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'CTC Updated Successfully';
		$resultObj [2] =  $_REQUEST ['employee_id'];
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}else{
		$resultObj [0] = 'error';
		$resultObj [1] = 'CTC Updated Failed';
		$resultObj [2] = mysqli_error( $conn );
		echo json_encode ( $resultObj );
	}
}



if (isset ( $_REQUEST ['x1'] )) {
	$employee_id = $_REQUEST ['employee_id'];
	$iWidth = 133;
	$iHeight = 170; // desired image result dimensions
	$iJpgQuality = 90;
	if (is_uploaded_file ( $_FILES ['image_file'] ['tmp_name'] )) {
		// new unique filename
		$sTempFileName = "../../compDat/" . $_SESSION ['company_id'] . "/empDat/$employee_id/";
		
		if (! file_exists ( $sTempFileName ))
			mkdir ( $sTempFileName);
		$sTempFileName .=$employee_id;
		// move uploaded file into cache folder
		move_uploaded_file ( $_FILES ['image_file'] ['tmp_name'], $sTempFileName );
		
		$temp = explode ( ".", $_FILES ["image_file"] ["name"] );
		$extension = end ( $temp );
		// change file permission to 644
		croppedImageUpload ( $sTempFileName );
		$employee_image = "../compDat/" . $_SESSION ['company_id'] . "/empDat/$employee_id/" . $employee_id . ".".$extension;
		$resultObj [0] = $employee_image;
		$stmt1 = mysqli_query ( $conn, "UPDATE  employee_personal_details SET employee_image='$employee_image' WHERE employee_id='$employee_id'" );
		echo json_encode ( $resultObj );
	}
}


// image cropped
function croppedImageUpload($sTempFileName) {
	$iWidth = 133;
	$iHeight = 170; // desired image result dimensions
	$iJpgQuality = 90;
	@chmod ( $sTempFileName, 0644 );
	if (file_exists ( $sTempFileName ) && filesize ( $sTempFileName ) > 0) {
		$aSize = getimagesize ( $sTempFileName ); // try to obtain image info
		if (! $aSize) {
			@unlink ( $sTempFileName );
			return;
		}
		// check for image type
		switch ($aSize [2]) {
			case IMAGETYPE_JPEG :
				$sExt = '.jpg';
				// create a new image from file
				$vImg = @imagecreatefromjpeg ( $sTempFileName );
				break;
			case IMAGETYPE_PNG :
				$sExt = '.png';
				
				// create a new image from file
				$vImg = @imagecreatefrompng ( $sTempFileName );
				break;
			default :
				@unlink ( $sTempFileName );
				return;
		}
		
		// create a new true color image
		$vDstImg = @imagecreatetruecolor ( $iWidth, $iHeight );
		
		// copy and resize part of an image with resampling
		imagecopyresampled ( $vDstImg, $vImg, 0, 0, ( int ) $_POST ['x1'], ( int ) $_POST ['y1'], $iWidth, $iHeight, ( int ) $_POST ['w'], ( int ) $_POST ['h'] );
		
		// define a result image filename
		$sResultFileName = $sTempFileName . $sExt;
		
		// output image to file
		imagejpeg ( $vDstImg, $sResultFileName, $iJpgQuality );
		@unlink ( $sTempFileName );
	}
}

?>
