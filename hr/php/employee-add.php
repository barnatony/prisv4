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
	function __construct($type,$title,$position,$params) {
		$masterconn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
	}
	function __destruct() {
	}
include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/include/config.php");
require_once ( LIBRARY_PATH. "/deviceApi.class.php"); // Include the File
$deviceApi = new deviceApi($conn); //pass the connection inside



$resultObj = array ();
if (isset ( $_REQUEST ['emp_id_prefix'] )) {
	$emp_id = $_REQUEST ['emp_id_prefix'] . $_REQUEST ['employee_id'];
} else if (isset ( $_REQUEST ['emp_id_suffix'] )) {
	$emp_id = $_REQUEST ['employee_id'] . $_REQUEST ['emp_id_suffix'];
}

$employee_id = "";
$first_name = "";
$dob = "";
$father_dob = "";
$mobile_no = "";
$email = "";
$house = "";
$street_name = "";
$area_name = "";
$job_status = "";
$doj = "";
$confirmation_date = "";
$designation = "";
$department = "";
$branch_location = "";
$salary_based_on = "";
//$team = "";

$employee_id = trim ( $_REQUEST ['employee_id'] );
$first_name = trim ( $_REQUEST ['employee_name'] );
$dob = trim ( $_REQUEST ['dob'] );
// $father_dob = trim($_REQUEST['father_dob']);
$mobile_no = trim ( $_REQUEST ['emp_personal_no'] );
$email = trim ( $_REQUEST ['email_id'] );
$personal_email = trim ( $_REQUEST ['personal_email_id'] );
$house = trim ( $_REQUEST ['building_name'] );
$street_name = trim ( $_REQUEST ['street'] );
$area_name = trim ( $_REQUEST ['area'] );
$pincode = trim ( $_REQUEST ['pin_code'] );
$job_status = trim ( $_REQUEST ['job_status'] );
$doj = trim ( $_REQUEST ['doj'] );
$confirmation_date = trim ( $_REQUEST ['confirmation_date'] );
$designation = trim ( $_REQUEST ['desig'] );
$department = trim ( $_REQUEST ['dept'] );
$branch_location = trim ( $_REQUEST ['branch_loc'] );
$salary_based_on = trim ( isset ( $_REQUEST ['salary_based_on'] )?$_REQUEST ['salary_based_on']:'nil' );
$errors = array ();
function dataValidate($test_date) {
	$date = DateTime::createFromFormat ( 'd/m/Y', $test_date );
	$date_errors = DateTime::getLastErrors ();
	if ($date_errors ['warning_count'] + $date_errors ['error_count'] > 0) {
		return true;
	} else {
		return false;
	}
}
// validate the form

if ($employee_id == "") {
	array_push ( $errors, "Please Enter Employee Id" );
} else if (strlen ( $employee_id ) > 20) {
	array_push ( $errors, "Employee Id Must Contain only 20 Character" );
}
if ($first_name == "") {
	array_push ( $errors, "Please Enter Your First Name" );
} else if (strlen ( $first_name ) > 20) {
	array_push ( $errors, "First Name Must Contain only 20 Character" );
}
if ($mobile_no == "") {
	array_push ( $errors, "Please Enter Your Mobile No" );
} else if (strlen ( $mobile_no ) < 10) {
	array_push ( $errors, "Mobile No Should be Equal to 10 Character" );
}
if ($email == "") {
	array_push ( $errors, "Please Enter Your Email" );
} else if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
	array_push ( $errors, "Please Enter Valid Email Address" );
}
if ($personal_email == "") {
	array_push ( $errors, "Please Enter Your Personal Email" );
} else if (! filter_var ( $personal_email, FILTER_VALIDATE_EMAIL )) {
	array_push ( $errors, "Please Enter Valid Personal Email Address" );
}
if ($house == "") {
	array_push ( $errors, "Please Enter Your House Name" );
} else if (strlen ( $house ) > 50) {
	array_push ( $errors, "House Name Must Contain only 50 Character" );
}
if ($street_name == "") {
	array_push ( $errors, "Please Enter Your Street Name" );
} else if (strlen ( $street_name ) > 50) {
	array_push ( $errors, "Street Name Must Contain only 50 Character" );
}
if ($area_name == "") {
	array_push ( $errors, "Please Enter Your Area Name" );
} else if (strlen ( $area_name ) > 50) {
	array_push ( $errors, "Area Name Must Contain only 50 Character" );
}
if ($pincode == "") {
	array_push ( $errors, "Please Enter Your Pin Code" );
} else if (strlen ( $pincode ) > 6) {
	array_push ( $errors, "Pin Code Must Contain only 6 Character" );
}
if ($job_status == "") {
	array_push ( $errors, "Please Enter Your Job Status" );
}
if ($department == "") {
	array_push ( $errors, "Please Enter Your Department" );
}
if ($designation == "") {
	array_push ( $errors, "Please Enter Your Designation" );
}
if ($branch_location == "") {
	array_push ( $errors, "Please Enter Your Branch Location" );
}
if ($salary_based_on == "") {
	array_push ( $errors, "Please Enter Your Slab Based On" );
} else if ($salary_based_on == 'noslab') {
}
if (dataValidate ( $doj )) {
	array_push ( $errors, "Date of Join Cannot be Empty" );
}
if (dataValidate ( $confirmation_date )) {
	array_push ( $errors, "Confirmation Date Cannot be Empty" );
}
if (dataValidate ( $dob )) {
	array_push ( $errors, "Date of Birth Cannot be Empty" );
}
$resultObj [0] = 'ERROR';
$output = '';

foreach ( $errors as $val ) {
	$output .= "<div class='col-lg-6'><p class='output' style='color:#b71c1c;'>* $val</p></div>";
}
$clearfix = '<div class="clearfix"></div>';
$resultObj [1] = $output . $clearfix;
if ($output != '') {
	echo json_encode ( $resultObj );
} else {
	// new section
	
	$companyDir = "../../compDat/" . $_SESSION ['company_id'];
	$empDir = $companyDir. "/empDat/" ;
	$empDirId = $empDir. "/$emp_id";
	if(! file_exists ( $companyDir))
		mkdir($companyDir);
		if(! file_exists ( $empDir))
			mkdir($empDir);
		if(! file_exists( $empDirId))
			mkdir($empDirId);
			$target_dir = $empDirId;
			if (! file_exists ( $target_dir ))
				mkdir ( $target_dir);
			
		function insertPersonalDetails($connc, $employee_id) {
					// inside empDat New directory creation
				if (! empty ( $_FILES ["image_file"] ["name"] )) {
						$allowedExts = array (
								"jpeg",
								"jpg",
								"png"
						);
						$temp = explode ( ".", $_FILES ["image_file"] ["name"] );
						$extension = end ( $temp );
						$newfilename = "../../compDat/" . $_SESSION ['company_id'] . "/empDat/$employee_id/";
						$newfilename .=$employee_id;
						move_uploaded_file ( $_FILES ["image_file"] ["tmp_name"],$newfilename);
						croppedImageUpload ($newfilename);
						 
						$employee_image = "../compDat/" . $_SESSION ['company_id'] . "/empDat/$employee_id/" . $employee_id . ".$extension";
						 
						// employee_insert($employee_image);
					} else {
						$employee_image = "Nil";
						// employee_insert($employee_image);
					}
					$employee_father_name = $_REQUEST ['fname'];
					$employee_mother_name = $_REQUEST ['mname'];
					$employee_gender = $_REQUEST ['gender'];
					$employee_dob = $_REQUEST ['dob'];
					$employee_image = $employee_image;
					$employee_marital_status = $_REQUEST ['marital_status'];
					$employee_build_name = $_REQUEST ['building_name'];
					$employee_street = $_REQUEST ['street'];
					$employee_area = $_REQUEST ['area'];
					$employee_pin_code = $_REQUEST ['pin_code'];
					$employee_city = $_REQUEST ['city'];
					$employee_dist_taluk = $_REQUEST ['emp_dist_taluk'];
					$employee_state = $_REQUEST ['state'];
					$employee_mobile = $_REQUEST ['mobile_no'];
					$employee_email = $_REQUEST ['email_id'];
					$employee_pan_no = $_REQUEST ['pan_no'];
					$employee_aadhaar_id = $_REQUEST ['aadhaar_id'];
					$employee_aadhaar_name = $_REQUEST ['aadhaar_name'];
					$employee_bank_name = $_REQUEST ['bank_name'];
					$employee_acc_no = $_REQUEST ['bank_ac'];
					$employee_bank_ifsc = $_REQUEST ['bank_ifsc'];
					$employee_bank_branch = $_REQUEST ['bank_branch'];
					$emp_personal_mobile_number =  $_REQUEST ['emp_personal_no'];
					$emp_personal_email_id =  $_REQUEST ['personal_email_id'];
					//$team = trim ( $_REQUEST ['team'] );
					$employee_password = str_replace ( "/", '', $_REQUEST ['dob'] );
					 
					$employee_password = password_hash($employee_password, PASSWORD_BCRYPT);
					 
					$stmt = mysqli_prepare ( $connc, "INSERT INTO employee_personal_details
                                 (employee_id,employee_father_name,emp_mother_name,employee_gender,
                                 employee_dob,
                                 employee_image,
                                 employee_marital_status,
                                 employee_build_name,
                                 employee_street,
                                 employee_area,
                                 employee_pin_code,
                                 employee_city,
								 employee_district,
                                 employee_state,
                                 employee_mobile,
                                 employee_email,
                                 employee_pan_no,
                                 employee_aadhaar_id,
								 employee_aadhaar_name,
                                 employee_bank_name,
                                 employee_acc_no,
                                 employee_bank_ifsc,
                                 employee_bank_branch,
                                employee_personal_mobile,
                                employee_personal_email,
                                 updated_by,
                                 employee_password)
                                VALUES (?, CAP_FIRST(?),CAP_FIRST(?), ?,
                                 STR_TO_DATE(?,'%d/%m/%Y'),
                                 ?, ?, CAP_FIRST(?),CAP_FIRST(?),CAP_FIRST(?),?,CAP_FIRST(?),CAP_FIRST(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)" );
					mysqli_stmt_bind_param( $stmt, 'sssssssssssssssssssssssssss', $employee_id, $employee_father_name, $employee_mother_name, $employee_gender, $employee_dob,$employee_image, $employee_marital_status, $employee_build_name, $employee_street, $employee_area, $employee_pin_code, $employee_city, $employee_dist_taluk, $employee_state,  $employee_mobile, $employee_email, $employee_pan_no, $employee_aadhaar_id, $employee_aadhaar_name, $employee_bank_name, $employee_acc_no, $employee_bank_ifsc, $employee_bank_branch, $emp_personal_mobile_number, $emp_personal_email_id, $_SESSION ['login_id'], $employee_password);
					
					$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $connc );
					return $result;
				}			
				
	
	function croppedImageUpload($newfilename) {
		$iWidth = 133;
		$iHeight = 170; // desired image result dimensions
		$iJpgQuality = 90;
		@chmod ( $newfilename, 0644 );
		if (file_exists ( $newfilename) && filesize ( $newfilename ) > 0) {
			$aSize = getimagesize ( $newfilename ); // try to obtain image info
			if (! $aSize) {
				@unlink ( $newfilename );
				return;
			}
			// check for image type
			switch ($aSize [2]) {
				case IMAGETYPE_JPEG :
					$sExt = '.jpg';
					// create a new image from file
					$vImg = @imagecreatefromjpeg ( $newfilename);
					break;
				case IMAGETYPE_PNG :
					$sExt = '.png';
	
					// create a new image from file
					$vImg = @imagecreatefrompng ( $newfilename );
					break;
				default :
					@unlink ( $newfilename);
					return;
			}
	
			// create a new true color image
			$vDstImg = @imagecreatetruecolor ( $iWidth, $iHeight );
	
			// copy and resize part of an image with resampling
			imagecopyresampled ( $vDstImg, $vImg, 0, 0, ( int ) $_POST ['x1'], ( int ) $_POST ['y1'], $iWidth, $iHeight, ( int ) $_POST ['w'], ( int ) $_POST ['h'] );
	
			// define a result image filename
			$sResultFileName = $newfilename . $sExt;
	
			// output image to file
			imagejpeg ( $vDstImg, $sResultFileName, $iJpgQuality );
			@unlink ( $newfilename);
		}
	}
	function insertWorkDetails($connc, $employee_id) {
		//work details
		$company_id = isset($_REQUEST['companyName'])?$_REQUEST['companyName']:"";
		$employee_name = $_REQUEST ['employee_name'];
		$employee_last_name = $_REQUEST ['lastName'];
		$employee_doj = $_REQUEST ['doj'];
		$employee_probation_period = $_REQUEST ['probation_period'];
		$employee_confirmation_date = $_REQUEST ['confirmation_date'];
		$status_id = $_REQUEST ['job_status'];
		$branch_id = $_REQUEST ['branch_loc'];
		$team_id = $_REQUEST ['team'];
		$shift_id = $_REQUEST ['shift'];
		$department_id = $_REQUEST ['dept'];
		$designation_id = $_REQUEST ['desig'];
		$employee_reporting_person = $_REQUEST ['rep_man-id'];
		$employee_emp_pf_no = $_REQUEST ['pf_no'];
		$employee_emp_uan_no = $_REQUEST ['uan_no'];
		$employee_emp_esi_no = $_REQUEST ['esi_no'];
		$job_status = $_REQUEST ['job_status'];
		$payment_mode_id = $_REQUEST ['payment_mode'];
		$notice_period = $_REQUEST ['notice_period'];
		
		$stmt = mysqli_prepare ( $connc, "INSERT INTO employee_work_details
                                (company_id, employee_id, employee_name,employee_lastname, 
			     				employee_doj, 
			     				employee_probation_period, 
			     				employee_confirmation_date, status_id, branch_id, department_id, designation_id, employee_reporting_person, employee_emp_pf_no, 
			     				employee_emp_uan_no, employee_emp_esi_no, payment_mode_id, notice_period, 
			     				enabled,updated_by,design_effects_from,
						branch_effects_from,team_id,shift_id)
VALUES (?,?, CAP_FIRST(?), CAP_FIRST(?),STR_TO_DATE(?,'%d/%m/%Y'), ?, STR_TO_DATE(?,'%d/%m/%Y'), ?, ?, ?, 
			     				?, ?, ?, ?, ?, ?, ?,1,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'), ?, ?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssssssssssssss',$company_id, $employee_id, $employee_name, $employee_last_name, $employee_doj, $employee_probation_period, $employee_confirmation_date, $status_id, $branch_id, $department_id, $designation_id, $employee_reporting_person, $employee_emp_pf_no, $employee_emp_uan_no, $employee_emp_esi_no, $payment_mode_id, $notice_period, $_SESSION ['login_id'], $employee_doj, $employee_doj,$team_id,$shift_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $connc );
		
		
		return $result;
	}
	
	function insertSalaryDetails($connc, $employee_id) {
		
		$pfLimit = $_REQUEST ['pf_limit'] ;
		$allowanceColumnsValues = "";
		$allowanceColumns = "";
		if(isset( $_REQUEST ['allowances'])){
			foreach ( $_REQUEST ['allowances'] as $key => $val ) {
				$allowanceColumns .= $key . ",";
				$allowanceColumnsValues .= str_replace(',','',$val ). ",";
		}
		}
		$ctc = isset ( $_REQUEST ['ctc'] ) ? $_REQUEST ['ctc'] : 0;
		$ctc = str_replace(',','',$ctc);
		$salaryType = isset ( $_REQUEST ['ctc'] ) ?'ctc' :'monthly';
		$slab = isset ( $_REQUEST ['slab'] ) ? $_REQUEST ['slab']: 0 ;
		$ctc_fixed_component = isset ( $_REQUEST ['ctc_fixed_component'] ) ? $_REQUEST ['ctc_fixed_component'] : 0;
		$ctc_fixed_component = str_replace(',','',$ctc_fixed_component);
		$employee_salary_amount = isset ( $_REQUEST ['grossSalary'] ) ? $_REQUEST ['grossSalary'] :0 ;
		$employee_doj = $_REQUEST ['doj'];
	//	$salary_based_on= isset ( $_REQUEST ['salary_based_on'] )?$_REQUEST ['salary_based_on']: 'nil';
	//	if ($salary_based_on != 'noslab') {
	//		$slab_id = $_REQUEST ['slab'];
	//	} else {
	//		$slab_id = "Nil";
	//	}
		$stmt = 'INSERT INTO employee_salary_details (slab_id,ctc,salary_type,ctc_fixed_component,employee_id,pf_limit,employee_salary_amount,effects_from,' . $allowanceColumns . 'updated_by)
                       VALUES (\'' . $slab . '\',' . $ctc . ',\'' . $salaryType . '\',' . $ctc_fixed_component . ',"' . $employee_id . '",' . $pfLimit . ',' . $employee_salary_amount . ',STR_TO_DATE(\'' . $employee_doj . '\',\'%d/%m/%Y\'),' . $allowanceColumnsValues . '"' . $_SESSION ['login_id'] . '")';

		$result = mysqli_query ( $connc, $stmt ) ? TRUE : mysqli_error ( $connc );
		return $result;
	}
	function insertItDecl($connc, $employee_id) {
		$stmt = mysqli_prepare ( $connc, "INSERT INTO `employee_it_declaration`(`employee_id`,`year`,`updated_by`)VALUES (?, ?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sss', $employee_id, $_SESSION ['financialYear'], $_SESSION ['login_id'] );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $connc );
		return $result;
	}
	function insertShiftdetails($connc, $employee_id){
		$shift_id = $_REQUEST ['shift'];
		$employee_doj = $_REQUEST ['doj'];
		$stmt = mysqli_prepare ( $connc, "INSERT INTO `shift_roaster`(`employee_id`,`shift_id`,`from_date`,`updated_by`)VALUES (?, ?, STR_TO_DATE(?,'%d/%m/%Y'), ?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $employee_id, $shift_id, $employee_doj, $_SESSION ['login_id'] );
		
		//echo $employee_id, $shift_id, $_SESSION ['login_id'];
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $connc );
		return $result;
		
	}
	function insertIncomeTax($connc, $employee_id) {
		$employee_income = "INSERT INTO employee_income_tax (employee_id, `year`,updated_by) VALUES ('$employee_id','" . $_SESSION ['financialYear'] . "','" . $_SESSION ['login_id'] . "')";
		$result = mysqli_query ( $connc, $employee_income ) ? TRUE : mysqli_error ( $connc );
		if ($result != true) {
			return $result;
		} else {
			if (isset ( $_REQUEST ['tds'] )) {
				$tds = $_REQUEST ['tds'] != "" ? $_REQUEST ['tds'] : 0;
				if ($tds != 0) {
					$employee_update = "update employee_income_tax set old_tax_paid='" . $tds . "' where employee_id='$employee_id'";
					$result = mysqli_query ( $connc, $employee_update ) ? TRUE : mysqli_error ( $connc );
				}
			}
		}
		return $result;
	}
	function insertPayrollTemp($connc, $employee_id) {
		
		$stmt = mysqli_prepare ( $connc, "INSERT INTO payroll_preview_temp (employee_id,updated_by)VALUES (?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $employee_id, $_SESSION ['login_id'] );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $connc );
		return $result;
	}
	function insertLeaveAccount($connc, $employee_id) {
		
		$leave_year_end_date = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['nextyear_date'] : "01/01/" . ($_SESSION ['payrollYear'] + 1);
		$leave_year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
		
		if (isset ( $_REQUEST ['openbal'] )) {
			$openbal = $_REQUEST ['openbal'];
			$o_bal = array ();
			$tags = explode ( ',', $openbal );
			$leaveAcc = 'insert into `emp_leave_account` (employee_id, `year`, leave_rule_id, opening_bal,is_leavecredited,updated_by)values';
			foreach ( $tags as $i => $key ) {
				if (! empty ( $_REQUEST [$key] )) {
					$o_bal [$i] = '(\'' . $employee_id . '\',\'' . $leave_year . '\',\'' . $key . '\',\'' . $_REQUEST [$key] . '\',1,\'' . $_SESSION ['login_id'] . '\')';
				}
			}
			$implode_openbal = implode ( $o_bal, ',' );
			$open_ba = $leaveAcc . $implode_openbal;
			$result = mysqli_query ( $connc, $open_ba ) ? TRUE : mysqli_error ( $connc );
		}
		
		$employee_doj = $_REQUEST ['doj'];
		$employee_confirmation_date = $_REQUEST ['confirmation_date'];
		$employee_gender = $_REQUEST ['gender'];
		if ($employee_gender == "Male") {
			$gender = '%Male%';
		} elseif ($employee_gender == "Female") {
			$gender = '%Female%';
		} elseif ($employee_gender == "Trans") {
			$gender = '%Trans';
		}
		
		$gender_s = "\\'" . $gender . "\\'";
		
		$qurt = "CALL CREDITLEAVE_ONEMPLOYEEADD('" . $_SESSION ['current_payroll_month'] . "','" . $leave_year . "',STR_TO_DATE('$leave_year_end_date','%d/%m/%Y'),
			     			'$employee_id',STR_TO_DATE('$employee_doj','%d/%m/%Y'),STR_TO_DATE('$employee_confirmation_date','%d/%m/%Y'),'$gender_s','" . $_SESSION ['login_id'] . "')";
		$result = mysqli_query ( $connc, $qurt ) ? TRUE : mysqli_error ( $connc );
		return $result;
	}
	
	
	//to check if company has lms enabled or not
	$masterconn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
	$sql=mysqli_query($masterconn,"SELECT * FROM company_features WHERE feature_id='FT96145' AND  company_id='{$_SESSION['company_id']}'");
	$lms=mysqli_fetch_assoc($sql);
	
	

	
	//$deviceInsertinfo = array();
	mysqli_autocommit ( $conn, FALSE );
	
	try {
		// mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
		mysqli_commit ( $conn );
		if (! is_bool ( $info = insertWorkDetails ( $conn, $emp_id ) ))
			throw new Exception ( "Work Details not inserted : " . $info . "" );
		if (! is_bool ( $info = insertPersonalDetails ( $conn, $emp_id ) ))
			throw new Exception ( "Personal Details not inserted : " . $info . "" );
		if (! is_bool ( $info = insertSalaryDetails ( $conn, $emp_id ) ))
			throw new Exception ( "salary Details not inserted : " . $info . "" );
		if (! is_bool ( $info = insertItDecl ( $conn, $emp_id ) ))
			throw new Exception ( "IT Decl not inserted : " . $info . "" );
		if (! is_bool ( $info = insertIncomeTax ( $conn, $emp_id ) ))
			throw new Exception ( "IT Tables not inserted : " . $info . "" );
		if (! is_bool ( $info = insertPayrollTemp ( $conn, $emp_id ) ))
			throw new Exception ( "Payroll Temp not inserted : " . $info . "" );
		if (! is_bool ( $info = insertLeaveAccount ( $conn, $emp_id ) ))
			throw new Exception ( "Leave Account not inserted : " . $info . "" );
		if(! is_bool ($info = insertShiftdetails ( $conn, $emp_id ) ))
			throw new Exception ( "Shift roaster not inserted : " . $info . "" );
		if($lms['company_id']==$_SESSION['company_id']){
		$deviceInsertinfo = $deviceApi->insertIntoDevice($conn, $emp_id,$branch_location,$first_name) ; //array(true,""), (false, "Not Inserted")
			
		}
		
	} catch ( Exception $e ) {
		mysqli_rollback ( $conn );
		$resultObj [0] = 'error';
		$errMsg = NULL;
		$errMsg = explode ( ':', $e->getMessage () );
		$resultObj [1] = $errMsg [0];
		$resultObj [2] = $errMsg [1];
		// inside empDat New directory creation
		if(file_exists($target_dir))
			rmdir ( "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $emp_id . "" );
		
			echo json_encode ( $resultObj );
			mysqli_close ( $conn );
			die();
		
	} finally {
		mysqli_commit ( $conn );
		$resultObj [0] = 'success';
		$resultObj [1] = "Employee Added Successfully";
		$resultObj [2] = $emp_id;
		if(!$deviceInsertinfo[0])
			$resultObj [3] = $deviceInsertinfo[1]; //for device errors
		echo json_encode ( $resultObj );
		mysqli_close ( $conn );
	}
	
}
?>

