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
function insertImage($tmpFile, $rename_1) {
	$fileName = $tmpFile ["name"]; // The file name
	$fileTmpLoc = $tmpFile ["tmp_name"]; // File in the PHP tmp folder
	$fileType = $tmpFile ["type"]; // The type of file it is
	$fileSize = $tmpFile ["size"]; // File size in bytes
	$temp = explode(".",$tmpFile ["name"]);
	$fileExt = ".".end ( $temp );
	//$fileExt = ".jpg";
	$fileErrorMsg = 0;
	$employee_id = $_SESSION ['employee_id'];
	
	
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


function runquery($tablenae, $resized_file_name) // (databse,table,column_name,identifier)
{
	include ("../../include/config.php");
	$employee_id = $_SESSION['employee_id'];
	$colname = "employee_" . $tablenae;
	$stmt = mysqli_prepare ( $conn, "UPDATE employee_personal_details SET $colname=? WHERE employee_id = ?" );
	mysqli_stmt_bind_param ( $stmt, 'ss', $resized_file_name, $employee_id );
	$result = mysqli_stmt_execute ( $stmt ) or die(mysqli_error($stmt));
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
	
	$employee_personal_mobile = $_REQUEST ['employee_personal_mobile'];
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
	$employee_international = $_REQUEST ['employee_international'];
	$employee_blood_group = $_REQUEST ['employee_blood_group'];
	$employee_pc = $_REQUEST ['employee_pc'];
	$employee_pan_no = $_REQUEST ['employee_pan_no'];
	$employee_bank_name = $_REQUEST ['employee_bank_name'];
    $employee_bank_branch = $_REQUEST['employee_bank_branch'];
	$employee_acc_no = $_REQUEST ['employee_acc_no'];
	$employee_bank_ifsc = $_REQUEST ['employee_bank_ifsc'];
	$employee_id_proof_type = $_REQUEST ['employee_id_proof_type'];
	$employee_id_proof_expiry = $_REQUEST ['employee_id_proof_expiry'];
	$employee_id_proof_no = $_REQUEST ['employee_id_proof_no'];
	$employee_id = $_SESSION ['employee_id'];
	
    $query1="UPDATE  employee_personal_details SET 	employee_personal_mobile=?,employee_marital_status=?, emp_mother_name=?,
    		spouse_name=?,father_mobile=?,mother_mobile=?,spouse_mobile=?,mother_dob=STR_TO_DATE(?,'%d/%m/%Y'),spouse_dob=STR_TO_DATE(?,'%d/%m/%Y'),employee_marriagedate=STR_TO_DATE(?,'%d/%m/%Y'),
 			employee_nationality=?, employee_international=?,employee_blood_group=?, employee_pc=?, employee_pan_no=?,employee_bank_name=?,employee_bank_branch=?,
			employee_acc_no=?, employee_bank_ifsc=?, employee_id_proof_type=?, employee_id_proof_expiry=?, employee_id_proof_no=? where employee_id=? "; 
	//echo $query1;
	//echo $employee_personal_mobile, $employee_marital_status, $emp_mother_name, $employee_spouse_name, $emp_father_mobile, $emp_mother_mobile, $emp_spouse_mobile, $emp_mother_dob, $emp_spouse_dob, $employee_marriagedate, $employee_nationality, $employee_international, $employee_blood_group, $employee_pc, $employee_pan_no, $employee_bank_name,$employee_bank_branch,$employee_acc_no, $employee_bank_ifsc, $employee_id_proof_type, $employee_id_proof_expiry, $employee_id_proof_no, $employee_id;
    $stmt1 = mysqli_prepare ( $conn, $query1 );
	mysqli_stmt_bind_param ( $stmt1, 'sssssssssssssssssssssss', $employee_personal_mobile, $employee_marital_status, $emp_mother_name, $employee_spouse_name, $emp_father_mobile, $emp_mother_mobile, $emp_spouse_mobile, $emp_mother_dob, $emp_spouse_dob, $employee_marriagedate, $employee_nationality, $employee_international, $employee_blood_group, $employee_pc, $employee_pan_no, $employee_bank_name,$employee_bank_branch,$employee_acc_no, $employee_bank_ifsc, $employee_id_proof_type, $employee_id_proof_expiry, $employee_id_proof_no, $employee_id );
	$result1 = mysqli_stmt_execute ( $stmt1 ) or die (mysqli_stmt_error($stmt1));
	if ($result1) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'Personal Details Updated Successfully';
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}
}

if (isset ( $_REQUEST ['workExp'] )) {
	$queryStmt = "";
	mysqli_query ( $conn, "DELETE  FROM emp_work_history WHERE employee_id='" . $_REQUEST ['employee_id'] . "'" );
	$queryStmt .= "REPLACE INTO emp_work_history  (employee_id,company_name, contact_email,  prev_reporting_manager, location,`from`,`to`,designation,ctc,updated_by) Values ";
	for($i = 1; $i < $_REQUEST ['totExper']; $i ++) {
		$queryStmt .= "('" . $_REQUEST ['employee_id'] . "','" . $_REQUEST [$i . '_cName'] . "','" . $_REQUEST [$i . '_contact_email'] . "','" . $_REQUEST [$i . '_reporting_manager'] . "','" . $_REQUEST [$i . '_location'] . "','" . $_REQUEST [$i . '_From'] . "','" . $_REQUEST [$i . '_To'] . "','" . $_REQUEST [$i . '_desig'] . "','" . $_REQUEST [$i . '_Ctc'] . "','" . $_SESSION ['login_id'] . "'),";
	}
	mysqli_query ( $conn, substr ( $queryStmt, 0, - 1 ) );
	$resultObj [0] = 'success';
	$resultObj [1] = 'Work History Updated Successfully';
	echo json_encode ( $resultObj );
	mysqli_close ( $conn );
}

if (isset ( $_REQUEST ['address'] )) {
	
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
	$employee_id = $_SESSION ['employee_id'];
    $query2 = "UPDATE  employee_personal_details SET employee_build_name=?,employee_area=?,employee_pin_code=?, employee_city=?, employee_district=?, employee_state=?, emp_country=?,
               permanent_emp_bulidname=?, permanent_emp_area=?,employee_pt_adddress=?,permanent_emp_pincode=?, permanent_emp_city=?, permanent_emp_dist=?, permanent_emp_state=?,permanent_emp_country=?  where employee_id=? " ;
	$stmt2 = mysqli_prepare ( $conn, $query2);
	mysqli_stmt_bind_param ( $stmt2, 'ssssssssssssssss', $employee_build_name, $employee_area, $employee_pin_code, $employee_city, $employee_district, $employee_state, $emp_country, $permanent_emp_bulidname, $permanent_emp_area, $employee_pt_adddress, $permanent_emp_pincode, $permanent_emp_city, $permanent_emp_dist, $permanent_emp_state, $permanent_emp_country, $employee_id );
    $result2 = mysqli_stmt_execute ( $stmt2 )or die (mysqli_stmt_error($stmt2));
	if ($result2) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'Address  Updated Successfully';
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
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
	$employee_id = $_SESSION ['employee_id'];
	$emp_sslc_school = $_REQUEST ['emp_sslc_school'];
	$emp_sslc_board = $_REQUEST ['emp_sslc_board'];
	$emp_sslc_marks = $_REQUEST ['emp_sslc_marks'];
	$emp_sslc_year = $_REQUEST ['emp_sslc_year'];
	$emp_ug_institute_name = $_REQUEST ['emp_ug_institute_name'];
	$emp_ug_university = $_REQUEST ['emp_ug_university'];
	$emp_ug_degree = $_REQUEST ['emp_ug_degree'];
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
	$query4 = "UPDATE  employee_personal_details SET emp_sslc_school=?,emp_sslc_board=?,emp_sslc_marks=?, emp_sslc_year=?,
    emp_ug_institute_name=?, emp_ug_university=?,  emp_ug_degree=?, emp_ug_major_subject=?, emp_ug_marks=?, emp_ug_year_passing=?,emp_hsc_school=?,emp_hsc_board=?, 
    emp_hsc_marks=?, emp_hsc_year=?,emp_pg_institute_name=?,emp_pg_university=?, emp_pg_degree=?, emp_pg_major_subject=?, emp_pg_marks=?,emp_pg_year_passing=?  where employee_id=?";
	$stmt4 = mysqli_prepare ( $conn, "$query4" );
	mysqli_stmt_bind_param ( $stmt4, 'sssssssssssssssssssss', $emp_sslc_school, $emp_sslc_board, $emp_sslc_marks, $emp_sslc_year, $emp_ug_institute_name, $emp_ug_university, $emp_ug_degree, $emp_ug_major_subject,  $emp_ug_marks, $emp_ug_year_passing, $emp_hsc_school, $emp_hsc_board, $emp_hsc_marks, $emp_hsc_year, $emp_pg_institute_name, $emp_pg_university, $emp_pg_degree, $emp_pg_major_subject, $emp_pg_marks, $emp_pg_year_passing, $employee_id );
	//echo "$emp_sslc_school, $emp_sslc_board, $emp_sslc_marks, $emp_sslc_year, $emp_ug_institute_name, $emp_ug_university, $emp_ug_marks, $emp_ug_year_passing, $emp_hsc_school, $emp_hsc_board, $emp_hsc_marks, $emp_hsc_year, $emp_pg_institute_name, $emp_pg_university, $emp_pg_marks, $emp_pg_year_passing, $employee_id";
	$result3 = mysqli_stmt_execute ( $stmt4 ) or die(mysqli_stmt_error($stmt4));
	if ($result3) {
		$resultObj [0] = 'success';
		$resultObj [1] = 'Education Details  Updated Successfully';
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}
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

if (isset ( $_REQUEST ['x1'] )) {
	
	$employee_id = $_SESSION ['employee_id'];
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
		  // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
		// define a result image filename
		$sResultFileName = $sTempFileName . $sExt;
		
		// output image to file
		imagejpeg ( $vDstImg, $sResultFileName, $iJpgQuality );
		@unlink ( $sTempFileName );
	}
}

?>
