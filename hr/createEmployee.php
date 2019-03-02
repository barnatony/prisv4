<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Create Employee</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link href="../css/jquery.Jcrop.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />

<style>
.formGroup {
	margin-bottom: 8px;
}

#slab_opt_chosen, #bank_name_chosen, #job_status_chosen, #dept_chosen,
	#desig_chosen, #branch_loc_chosen, #team_loc_chosen, #b_group_chosen, #payment_mode_chosen {
	width: 100% !important;
}
.popover{
max-width:none;
width:100% !important;
}
</style>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php
					
include_once (__DIR__ . "/header.php");
					$payroll_flag = $_SESSION ['payroll_flag'];
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php
												
include_once (__DIR__ . "/sideMenu.php");
												require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
												
												$employee = new Employee ();
												$employee->conn = $conn;
												$strRestriction = $employee->createEmployeeRestriction ();
												// Allowances and Deduction
												Session::newInstance ()->_setGeneralPayParams ();
												$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
												Session::newInstance ()->_setMiscPayParams ();
												$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
												?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading">
						Create Employee<a href="import.php?for=Employee" target="foo()"
							title="Employee Import">
							<button id="" type="button"
								class="btn btn-sm btn-default pull-right">
								<i class="fa fa-upload" aria-hidden="true"></i> Employee Import
							</button>
						</a>
					</header>

					<div class="panel-body">
                  
                     <?php
																					
if (! empty ( $strRestriction )) {
																						?>
                                       	<div id="well"
							class="well  col-lg-12">
							<h5>Following details are requires to create employee</h5>
							<ul class="chat-available-user row" style="margin-bottom: -19px;">
                                       	<?php
																						foreach ( $strRestriction as $key ) {
																							if ($key == 'BRANCH') {
																								$link = 'orgstr.php#branch_sub1';
																							} else if ($key == 'DEPARTMENT') {
																								$link = 'orgstr.php#department1';
																							} else if ($key == 'DESIGNATION') {
																								$link = 'orgstr.php#designation1';
																							} else if ($key == 'JOBSTATUS') {
																								$link = 'orgstr.php#job1';
																							} else if ($key == 'PAYMENTMODE') {
																								$link = 'orgstr.php#payment1';
																							}
																							echo ' <li class="col-lg-6">
                              <a href=' . $link . '>
                                  <i class="fa fa-circle text-success"></i>
                                  ' . $key . '
                               </a>
                          </li>';
																						}
																						?>
                                       	
                         
                                        </ul>
						</div>
                                       	<?php
																					} else {
																						?>
                   <div class="stepy-tab">
							<ul id="default-titles" class="stepy-titles clearfix">
								<li id="default-title-0" class="current-step">
									<div>Personal</div>
								</li>
								<li id="default-title-1" class="">
									<div>Work</div>
								</li>
								<li id="default-title-2" class="">
									<div>Salary</div>
								</li>
								<li id="default-title-3" class="">
									<div>Summary</div>
								</li>
							</ul>
						</div>
						<form enctype="multipart/form-data" class="form-horizontal"
							id="employeeAddForm" method="post">
							<fieldset title="Personal" class="step" id="default-step-0">

								<legend></legend>
								<div class="col-lg-12">
									<div class="col-lg-6">
											
									<!-- company id -->
									
                                             <?php
                                              //$result = array();
													$stmt ="SELECT company_id,company_name FROM companies";
													$active = ' ';
													if ($result=mysqli_query($conn,$stmt))
														{
															$row=mysqli_fetch_row($result);
															if($row==0){
															}else{
																echo '<div class="form-group "><label class="col-lg-5 control-label">Company Name </label><div class="input-group col-lg-7 m-bot15" style="margin-left:-17px;"><div class="col-lg-12"><div class="btn-group" data-toggle="buttons">';
																$i = 0;
																echo '<label class=" btn btn-default active"> <input type="radio"  value='.$row[0].'  name="companyName" checked/>'.$row[1].'</label>';
																	
																while ($row=mysqli_fetch_row($result))
																{
																  echo '<label class=" btn btn-default "> <input type="radio"  value='.$row[0].'  name="companyName" />'.$row[1].'</label>';
																  $i++;
																}
																echo '</div></div></div></div>';
															 }
														}
													mysqli_free_result($result);
											?>
                                       
										
										
										<div class="form-group">
											<label class="col-lg-5 control-label">Employee ID *</label>
											<div class="input-group col-lg-7 m-bot15">
                                          
                                             <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT company_id, company_name, company_type, company_logo, company_build_name, company_street, company_area, company_city, company_pin_code, company_state, company_phone, company_mobile, company_email, company_pin, company_resp1_name, company_resp1_desgn, company_resp1_phone, company_resp1_email, company_resp2_name, company_resp2_desgn, company_resp2_phone, company_resp2_email, company_pan_no, company_cin_no, company_tan_pattern, company_tan_no, company_doi, company_epf_pattern, company_epf_no, company_esi_pattern, company_esi_no, company_emp_id_prefix, company_emp_id_suffix, info_flag FROM company_details WHERE info_flag='A'" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $company_id, $company_name, $company_type, $company_logo, $company_build_name, $company_street, $company_area, $company_city, $company_pin_code, $company_state, $company_phone, $company_mobile, $company_email, $company_pin, $company_resp1_name, $company_resp1_desgn, $company_resp1_phone, $company_resp1_email, $company_resp2_name, $company_resp2_desgn, $company_resp2_phone, $company_resp2_email, $company_pan_no, $company_cin_no, $company_tan_pattern, $company_tan_no, $company_doi, $company_epf_pattern, $company_epf_no, $company_esi_pattern, $company_esi_no, $company_emp_id_prefix, $company_emp_id_suffix, $info_flag );
																						$emplyeeIdCode="";
																						$count=0;
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							
																							if ($company_emp_id_prefix != '0') {
																								$emplyeeIdCode = $company_emp_id_prefix;
																								echo '<div class="value_column" style="display:none"><input type="text"  maxlength="5" name="edit" class="form-control edit" style="height:100%;width:20%;position: absolute;margin-top: -6.5%;"/><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;">
    		                                      </div><span class="input-group-addon" id="empIdPrefix"  style="cursor:pointer;">' . $company_emp_id_prefix . '</span>
                                                  <input type="hidden"  value="' . $company_emp_id_prefix . '" name="emp_id_prefix" id="emp_id_prefix"/>
                                              <input type="text" class="form-control personalValidate" name="employee_id" id="employee_id"  maxlength="20" autocomplete="off" required/>';
																							} else  {
																								$emplyeeIdCode = $company_emp_id_suffix;
																								echo '<input type="text" class="form-control"  name="employee_id" id="employee_id" autocomplete="off" required/>
                                                        <input type="hidden"   class="personalValidate" value="' . $company_emp_id_suffix . '" maxlength="20"  name="emp_id_suffix" id="emp_id_suffix"/>
                                                        <span class="input-group-addon" id="empIdSuffix">' . $company_emp_id_suffix . '</span><div class="value_column" style="display:none"><input type="text"  maxlength="5" name="edit" class="form-control edit" style="margin-left: -12%;height:100%;width:20%;position: absolute;margin-top: -6.5%;"/><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;">
    		                                        </div>';
																							}
																						}
																						mysqli_stmt_close($stmt);
																						?>
                                         </div>
										</div>
										<div aria-hidden="true" aria-labelledby="myModalLabel"
											role="dialog" tabindex="-1" id="employeeCrop"
											class="modal fade">
											<div class="modal-dialog  modal-lg">

												<div class="modal-content">
													<div class="modal-header">
														<button aria-hidden="true" data-dismiss="modal"
															class="close" type="button">&times;</button>
														<h4 class="modal-title">Employee Image Crop</h4>
													</div>
													<div class="modal-body">
														<div class="body">

															<!-- upload form -->
															<!-- hidden crop params -->
															<input type="hidden" id="x1" name="x1" /> <input
																type="hidden" id="y1" name="y1" /> <input type="hidden"
																id="x2" name="x2" /> <input type="hidden" id="y2"
																name="y2" />
															<div>
																<span class="btn btn-success btn-sm fileinput-button"
																	style="width: 134px;"> <i class="fa fa-upload"></i>Upload
																	Image <input type="file" name="image_file"
																	id="image_file" onchange="fileSelectHandler()" />

																</span>
															</div>

															<div class="step2">
																<br> <img id="preview" />

																<div class="info">
																	<input type="hidden" id="w" name="w" /> <input
																		type="hidden" id="h" name="h" /> <input type="hidden"
																		id="imageexit" name="imageexit" />
																</div>
															</div>

														</div>
														<div class="modal-footer">
															<div class="error pull-left" style="color: red"></div>
															<button type="button" class="btn btn-sm btn-danger"
																data-dismiss="modal" id="cancelCrop">Cancel</button>
															<button type="submit" class="btn btn-sm btn-success"
																id="cropok">Crop</button>
														</div>

													</div>


												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">First Name *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control personalValidate"
													maxlength="20" name="employee_name" id="employee_name"
													autocomplete="off" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Last Name</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="lastName"
													maxlength="20" id="employee_lastname" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Date Of Birth *</label>
											<div class="col-lg-7 input-group">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control personalValidate" maxlength="10"
													type="text" name="dob" id="dob" required
													placeholder="dd/mm/yyyy" />
												<div></div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Father's Name</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="fname"
													maxlength="20" id="fname" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Mother's Name</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="mname"
													maxlength="20" id="mname" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Gender</label>
											<div class="col-lg-7">
													<label for="gender1" class="col-lg-4 control-label">
													<input checked name="gender" id="gender1" value="Male"
													type="radio"> Male</label> 
													<label for="gender2"
													class="col-lg-4 control-label">
													<input name="gender"
													id="gender2" value="Female" type="radio"> Female</label> 
													<label
													for="gender3" class="col-lg-4 control-label">
													<input
													name="gender" id="gender3" value="Trans" type="radio">
													Trans</label>
											</div>
										</div>

									<!-- <div class="form-group">
											<label class="col-lg-5 control-label">Image</label>
											<div class="col-lg-7">
												<div class="fileupload-new thumbnail"
													style="width: 133px; height: 170px; margin-bottom: 5px">
													<canvas id="preview_image"
														style="width: 123px; height: 160px; overflow: hidden;"></canvas>
												</div>
												<span class="btn btn-success btn-sm fileinput-button"
													style="width: 134px;"> <i class="fa fa-upload"></i> <a
													href="#employeeCrop" style="color: #fff;"
													data-toggle="modal"><span>Employee Image</span></a> 
													<!--      <input name="image" id="image" type="file" accept="image/jpeg,image/png"> -->
												<!-- </span>
											</div>
										</div>  -->
										

										<div class="form-group">
											<label class="col-lg-5 control-label">Marital Status</label>
											<div class="col-lg-7">
												<input name="imageName" id="imageName" type="hidden"
													value="Nil"> <label for="marital_status1"
													class="col-lg-6 control-label"><input name="marital_status"
													id="marital_status1" value="Single" type="radio" checked>
													Single</label> <label for="marital_status2"
													class="col-lg-6 control-label"><input name="marital_status"
													id="marital_status2" value="Married" type="radio"> Married</label>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-lg-5 control-label">Official Phone Number </label>
											<div class="col-lg-7">
												<input type="text" class="form-control"
													maxlength="10" name="mobile_no" id="mobile_no" required />
													<span class="message"></span>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-lg-5 control-label">Official Email *</label>
											<div class="col-lg-7">
												<input type="email" class="form-control personalValidate"
													name="email_id" id="email_id" maxlength="70"
													autocomplete="off" required />
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-lg-5 control-label">Personal Phone Number *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control personalValidate"
													maxlength="10" name="emp_personal_no" id="personal_no"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Personal Email *</label>
											<div class="col-lg-7">
												<input type="Email" class="form-control  personalValidate"
													name="personal_email_id" id="personal_email_id" maxlength="70"
													autocomplete="off" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">House Name *</label>
											<div class="col-lg-7">
												<textarea class="form-control personalValidate" rows="1"
													name="building_name" id="building_name" maxlength="50"
													required></textarea>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										
										
										<div class="form-group">
											<label class="col-lg-5 control-label">Street Name *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control personalValidate"
													name="street" id="street" maxlength="50" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Area Name *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control personalValidate"
													name="area" id="area" maxlength="50" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">City *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control  personalValidate" name="city"
													id="city" maxlength="50" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">PIN Code *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control personalValidate"
													name="pin_code" id="pin_code" min="6" maxlength="6"
													 />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">District / Taluk *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control  personalValidate" name="emp_dist_taluk"
													id="emp_dist_taluk" maxlength="50" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">State *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control  personalValidate" name="state"
													id="state" maxlength="50" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Country *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control  personalValidate" name="empCountry"
													maxlength="50" id="emp_country" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">PAN Number</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="pan_no"
													id="pan_no" maxlength="10" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Aadhaar ID *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control personalValidate" name="aadhaar_id"
													id="aadhaar_id" maxlength="20" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Aadhaar Name *</label>
											<div class="col-lg-7">
												<input type="text" class="form-control personalValidate" name="aadhaar_name"
													id="aadhaar_name" maxlength="20" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Bank Name</label>
											<div class="col-lg-7">
												<select class="form-control" id="bank_name" name="bank_name">
												<option value="">Select Bank Name</option>
												 <option value="Allahabad Bank">Allahabad Bank</option>
											     <option value="Andhra Bank">Andhra Bank</option>
											      <option value="Axis Bank">Axis Bank</option>
											     <option value="Bank of India">Bank of India</option>
											     <option value="Bank of Baroda">Bank of Baroda</option>
											     <option value="Bank of Maharashtra">Bank of Maharashtra</option>
											      <option value="Bandhan Bank">Bandhan Bank</option>
											       <option value="Bharatiya Mahila Bank">Bharatiya Mahila Bank</option>
											     <option value="Canara Bank">Canara Bank</option>
											     <option value="Central Bank of India">Central Bank of India</option>
											     <option value="Citibank">Citibank</option>
											     <option value="Corporation Bank">Corporation Bank</option>
											      <option value="Catholic Syrian Bank">Catholic Syrian Bank</option>
											     <option value="City Union Bank">City Union Bank</option>
											     <option value="Dena Bank">Dena Bank</option>
											     <option value="Dhanlaxmi Bank">Dhanlaxmi Bank</option>
											     <option value="DCB Bank">DCB Bank</option>
											      <option value="Federal Bank">Federal Bank</option>
											       <option value="HDFC Bank">HDFC Bank</option>
											     <option value="Indian Bank">Indian Bank</option>
											     <option value="Indian Overseas Bank">Indian Overseas Bank</option>
											     <option value="IDBI Bank">IDBI Bank</option>
											     <option value="ICICI Bank">ICICI Bank</option>
											      <option value="IDFC Bank">IDFC Bank</option>
											      <option value="IndusInd Bank">IndusInd Bank</option>
											     <option value="Jammu and Kashmir Bank">Jammu and Kashmir Bank</option>
											     <option value="Karnataka Bank">Karnataka Bank</option>
											      <option value="Karur Vysya Bank">Karur Vysya Bank</option>
											     <option value="Kotak Mahindra Bank">Kotak Mahindra Bank</option>
											      <option value="Lakshmi Vilas Bank">Lakshmi Vilas Bank</option>
											      <option value="Nainital Bank">Nainital Bank</option>
											     <option value="Oriental Bank of Commerce">Oriental Bank of Commerce</option>
											     <option value="Punjab & Sindh Bank">Punjab &amp; Sindh Bank</option>
											     <option value="Punjab National Bank">Punjab National Bank</option>
											     <option value="RBL Bank">RBL Bank</option>
											    <option value="State Bank of India"> State Bank of India</option>
											    <option value="State Bank of Bikaner and Jaipur"> State Bank of Bikaner and Jaipur</option>
											     <option value="State Bank of Hyderabad">State Bank of Hyderabad</option>
											     <option value="State Bank of Mysore">State Bank of Mysore</option>
											     <option value="State Bank of Patiala">State Bank of Patiala</option>
											     <option value="State Bank of Travancore">State Bank of Travancore</option>
											    <option value="State Bank of Sikkim">State Bank of Sikkim</option>
											   	<option value="South Indian Bank">South Indian Bank</option>
											   	 <option value="Syndicate Bank">Syndicate Bank</option>
											     <option value="Tamilnad Mercantile Bank Limited">Tamilnad Mercantile Bank Limited</option>
											      <option value="UCO Bank">UCO Bank</option>
											     <option value="Union Bank of India">Union Bank of India</option>
											     <option value="United Bank of India">United Bank of India</option>
											     <option value="Vijaya Bank">Vijaya Bank</option>
											     <option value="Yes Bank">Yes Bank</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Bank A/C No.</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="bank_ac"
													id="bank_ac" maxlength="30" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">IFSC Code</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="bank_ifsc"
													id="bank_ifsc" maxlength="12" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Bank Branch</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="bank_branch"
													id="bank_branch" maxlength="30" />
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>

							</fieldset>
							<fieldset title="Work" class="step" id="default-step-1">
								<legend></legend>
								<div class="col-lg-12 row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 control-label">Job Status *</label>
											<div class="col-lg-7">
												<select class="form-control" id="job_status"
													name="job_status">
                                                <?php
																						echo "<option>Select Job status</option>";
																						$stmt = mysqli_prepare ( $conn, "SELECT status_id, status_name, status_comments FROM company_job_statuses" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $status_id, $status_name, $status_comments );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $status_id . "'>" . $status_name . "</option>";
																						}
																						mysqli_stmt_close($stmt);
																						?>
                                              </select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Date Of Join *</label>
											<div class="col-md-7 col-xs-11 input-group">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control workvalidate" type="text"
													name="doj" id="doj" maxlength="10" required
													placeholder="dd/mm/yyyy" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Probation Period</label>
											<div class="col-md-7 col-xs-11 input-group">
												<input class="form-control " type="text"
													name="probation_period" id="probation_period"
													maxlength="20" value="0" required /> <span
													class="input-group-addon">days</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Confirmation Date *</label>
											<div class="col-md-7 col-xs-11 input-group">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control workvalidate" type="text"
													name="confirmation_date" maxlength="10"
													placeholder="dd/mm/yyyy" id="confirmation_date" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Notice Period</label>
											<div class="col-md-7 col-xs-11 input-group">
												<input class="form-control" type="text" name="notice_period"
													id="notice_period" value="0" maxlength="20" required /> <span
													class="input-group-addon">days</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Designation *</label>
											<div class="col-lg-7">
												<select class="form-control" id="desig" name="desig">
													<option value="">Select Designation</option>
                                                   <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT designation_id, designation_name, designation_hierarchy FROM company_designations WHERE enabled=1" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $designation_id, $designation_name, $designation_hierarchy );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $designation_id . "'>" . $designation_name . "</option>";
																						}
																						mysqli_stmt_close($stmt);
																						?>
                                                  </select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Department *</label>
											<div class="col-lg-7">
												<select class="form-control" id="dept" name="dept">
													<option value="">Select Department</option>    
                                               <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT department_id, department_name FROM company_departments" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $department_id, $department_name );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $department_id . "'>" . $department_name . "</option>";
																						}
																						mysqli_stmt_close($stmt);
																						?>
                                              </select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Branch Location *</label>
											<div class="col-lg-7">
												<select class="form-control" id="branch_loc"
													name="branch_loc">
												  <option value="">Select Branch</option>
                                                   <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT branch_id, branch_name FROM company_branch" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $branch_id, $branch_name );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $branch_id . "'>" . $branch_name . "</option>";
																						}
																						mysqli_stmt_close($stmt);
																						?> 
                                              </select>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										
										<div class="form-group">
											<label class="col-lg-5 control-label">Team *</label>
											<div class="col-lg-7">
												<select class="form-control" id="team"
													name="team">
												  <option value="">Select Team</option>
                                                   <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT team_id, team_name FROM company_team" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $team_id, $team_name );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $team_id . "'>" . $team_name . "</option>";
																						}
																						mysqli_stmt_close($stmt);
																						?> 
                                              </select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Shift *</label>
											<div class="col-lg-7">
												<select class="form-control" id="shift"
													name="shift">
												  <option value="">Select Shift</option>
                                                   <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT shift_id, shift_name FROM company_shifts WHERE enabled=1" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $shift_id, $shift_name );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $shift_id . "'>" . $shift_name . "</option>";
																						}
																						mysqli_stmt_close($stmt);
																						?> 
                                              </select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Reporting Manager</label>
											<div class="col-lg-7">
												<input type="hidden" id="rep_man-id" name="rep_man-id"> <input
													type="text" class="form-control" id="rep_man"
													name="rep_man" autocomplete="off" maxlength="50" /> <span
													class="help-block"><span class="pull-right empty-message"
													style="display: none">No Records Found</span></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">PF Number</label>
											<div class="col-lg-7">
												<input type="text" name="pf_no" id="pf_number"
													class="form-control" maxlength="26" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">UAN Number</label>
											<div class="col-lg-7">
												<input type="text" name="uan_no" id="uan_number"
													class="form-control" maxlength="20" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">ESI Number</label>
											<div class="col-lg-7">
												<input type="text" name="esi_no" id="ef_number"
													class="form-control" maxlength="20" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Payment Mode *</label>
											<div class="col-lg-7">
												<select class="form-control" id="payment_mode"
													name="payment_mode">
													<option value="">Select Payment Mode</option>
                                                   <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT payment_mode_id, payment_mode_name FROM company_payment_modes" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $payment_mode_id, $payment_mode_name );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $payment_mode_id . "'>" . $payment_mode_name . "</option>";
																						}
																						mysqli_stmt_close ( $stmt );
																						?>    
                                              </select>
											</div>
										</div>
									</div>
								
								<div class="clearfix"></div>
							</fieldset>
							<fieldset title="Salary" class="step" id="default-step-2">
								<legend></legend>
								
								<div class="col-lg-6">
								<div class="form-group">
										<div>
											<input type="checkbox" aria-label="..." id="mapLater" class="mapLater" name="mapLater" >&nbsp;&nbsp;<b>Map Later</b>
										</div>
								</div>
								<div class="forctc">
								<div class="form-group ">
										<label class="col-lg-4 control-label">Salary Type</label>
											<div class="col-lg-8">
												<label for="ctcval" class="col-lg-5 control-label" title="CTC">
													<input name="salary_type" id="ctcval"value="ctc" type="radio"> CTC</label> 
												<label for="salary_type" class="col-lg-5 control-label" title="Gross">
														<input name="salary_type"id="salary_type" value="monthly" type="radio">GROSS</label>
											</div>
								</div>
								<div class="form-group">
											<label class="col-lg-4 control-label">Input Type</label>
												<div class="col-lg-8">
													<label for="monthly" class="col-lg-5 control-label" title="Monthly">
														<input name="input_type" class="ip" id="monthly" value="monthly" type="radio"> Monthly</label> 
													<label for="annual" class="col-lg-5 control-label" title="Annual">
														<input name="input_type" class="ip" id="annual" value="annual" type="radio">Annual</label>
												</div>
								</div>
								
								<div id="slabloader" style="width: 100%; height: 50%"></div>
									<input type="hidden" id="checkIfexit">
								<div class="form-group" id="slab_select_box">
										<label class="col-lg-4 control-label">Slab Name</label>
											<div class="col-lg-8">
												<select id="slab_opt" name="slab" class="form-control">			
													</select> <span class="help-block" id="minimum_salary_div"
																style="display: none">Applicable only for salary gt <span
																id="min_salary_amount"></span></span>
											</div>
								</div>
									<?php 
												$stmt = mysqli_prepare ( $conn, "SELECT struct.display_flag
																				FROM company_pay_structure struct
																				WHERE struct.pay_structure_id='c_epf' AND struct.display_flag ='0';" );
																				$result = mysqli_stmt_execute ( $stmt );
																				mysqli_stmt_bind_result ( $stmt,$display_flag);
																				mysqli_stmt_close($stmt);
																				echo "$display_flag";
																		?>
																								
																						
												<div class="form-group ">
													<label class="col-lg-4 control-label" for="pf_limit" style="padding-right: 0">PF</label>
													<div class="col-lg-8">
															<select class="form-control" id="pf_limit" name="pf_limit">
			                                               	    <option value="1" selected>Apply PF Limit</option>
																<option value="0">Don't Apply PF Limit</option>
																<option value="-1">Exclude from PF</option>
															</select>
													</div>
												</div>
											
	
									<div  id="getCTCcontent"></div>
								</div>
                                 </div>
                                 
								<div class="col-lg-6">
									
									<div class="form-group " id="lineSet">
										<div id="loader" style="width: 93%; height: 93%"></div>
										<div id="getTablecontent" class="container" style="width: 90%"></div>
									</div>
								</div>

								<br>
								<br>
								<div class="clearfix"></div>
							</fieldset>
							<fieldset title="Summary" class="step" id="default-step-3">
								<legend></legend>
								<div class="col-lg-12">
									<div class="col-lg-6" align="left">
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Employee Id</label>
											<div class="col-lg-7">
												<p class="form-control-static" id="e_id"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Employee Name</label>
											<div class="col-lg-7">
												<p class="form-control-static" id="ename"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Gender</label>
											<div class="col-lg-7">
												<p class="form-control-static" id="d_gender"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Date Of Birth</label>
											<div class="col-lg-7">
												<p class="form-control-static" id="d_dob"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Email ID</label>
											<div class="col-lg-7">
												<p class="form-control-static" id="d_mail_id"></p>
											</div>
										</div>

										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Mobile</label>
											<div class="col-lg-7">
												<p class="form-control-static" id="d_mob_no"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Address</label>
											<div class="col-lg-7">
												<p class="form-control-static" align="left" id="d_addr"></p>
												<p class="form-control-static" align="left" id="d_addr1"></p>
												<p class="form-control-static" align="left" id="d_addr2"></p>
												<p class="form-control-static" align="left" id="d_addr3"></p>
												<p class="form-control-static" align="left" id="d_addr4"></p>
												<p class="form-control-static" align="left" id="d_addr5"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">PF Number</label>
											<div class="col-lg-7">
												<p class="form-control-static" id="d_pf_number"></p>
											</div>
										</div>
									</div>
									<div class="col-lg-6" align="right">
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">UAN Number</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_uan_no"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">ESI Number</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_esi_no"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Date of Join</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_doj"></p>
											</div>
										</div>

										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Designation</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_desig"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Department</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_dept"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Bank Account</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_bank_ac"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">IFCS Code</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_ifcs"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Gross Salary</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_sal"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">CTC</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_ctc"></p>
											</div>
										</div>
										<div class="form-group formGroup">
											<label class="col-lg-5 control-label">Payment Mode</label>
											<div class="col-lg-7" align="left">
												<p class="form-control-static" id="d_payment_mode"></p>
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</fieldset>
							<fieldset title="Open Bal" class="step" id="default-step-4"></fieldset>
							<input type="button" name="submitForm" id="submitForm"
								class="finish btn btn-danger" value="Finish" />
						</form>
							<div class="help-texts">
						
						</div>
                          <?php
																					
}
																					?>
                 </div>
				</section>
				<!-- page end-->
			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
			<?php include_once (__DIR__."/footer.php");?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!--script for this page only-->
	<script src="../js/jquery.stepy.js"></script>
	<script src="../js/summarydata.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="../js/jquery.Jcrop.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          $(document).ready(function () {
          		$('.help-texts').empty().html(' <div class="helpblock0" ><div class="alert" role="alert"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p ><i class="fa fa-caret-right" ></i> &nbsp; Have You created the Department/ Designation/Branch which you are going to give this employee?  if not create <a href="orgstr.php" style="text-decoration: underline;color:blue">here</a></p><p ><i class="fa fa-caret-right" ></i>  &nbsp; Double Clicking on the Prefix/Suffix will allow you to change your custom prefix/suffix</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	On Clicking the Employee ID Input, you can view last inserted employees on each prefix/suffix and their respective IDs.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	Fields that are marked with * are mandatory. The information which is left over can be filled on Employee Profile.</p></div></div></div>');
          	
          $('.sidebar-menu').show();
          
        	  var payroll_flag='<?php echo $payroll_flag;?>';
        	  flagAjaxcall=0;
        	 
        	  if(payroll_flag==0)
                    {
            	     $('#default-step-4').empty();
					 $('#default-step-4').append('<legend></legend><div class="col-lg-12"><div class="col-lg-6" id="remove_hide"><?php  $a_name= array(); $stmt = mysqli_prepare($conn,"SELECT  rule_name, leave_rule_id FROM company_leave_rules where enabled='1' "); $result = mysqli_stmt_execute($stmt);mysqli_stmt_bind_result($stmt, $rule_name,$alias_name);while (mysqli_stmt_fetch($stmt)) { $a_name[]=$alias_name; echo '<div class="form-group leave_hide '."$alias_name".'" ><label class="col-lg-5 control-label">'."$rule_name".'</label><div class="col-lg-7"><input type="text" class="form-control" maxlength="15" value="0" name="'."$alias_name".'" id="'."$alias_name".'" /></div></div>';}?> <input type="hidden" name="openbal" value="<?php echo implode($a_name,',');?>"></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">TDS</label><div class="col-lg-7"><input type="text" class="form-control" maxlength="15" name="tds" id="tds"/></div></div></div></div><div class="clearfix"></div>');
                         $('input[name=gender]').change(function(){
              		 
             		  var value_s = $( 'input[name=gender]:checked' ).val();
             		  var gender='';
             		  if(value_s=="Male")
     	     			 {
     	     			 	gender='Male%';
     	     			 }else if(value_s=="Female")
     	     			 {
     	     			 	gender='%Female%';
     	     			 	
     	     			 }else if(value_s=="Trans")
     	     			 {
     	     			 	gender='%Trans';
     	     			  } 
          			  $('.leave_hide').hide();	         		 
             		  $.ajax({
     	                     dataType: 'html',
     	                     type: "POST",
     	                     url: "php/Leaveaccount.handle.php",
     		                 cache: false,
     	                     data: { act: '<?php echo base64_encode($_SESSION['company_id']."!lr_select");?>',value:gender},
     	                     success: function (data) {
     	                    	  var json_obj = $.parseJSON(data); //parse JSON
     	                    	  for(var i=0;i<json_obj[2].length;i++){
     		                    	$('.'+json_obj[2][i].leave_rule_id).show();
                 	                }
          	               }
     	                 }); 
           		  });
	                     }else {
	                      
            	   		$('fieldset').remove('#default-step-4');
                   }
        	 
                
              $('#job_status,#dept,#desig,#branch_loc,#team,#shift,#payment_mode,#bank_name,#slab_opt').chosen();
              $(function () {
                  $('#employeeAddForm').stepy({
                      backLabel: 'Previous',
                      block: true,
                      backLabel: 'Previous',
                      nextLabel: 'Next',
                      titleClick: true,
                      errorImage: true,
                      validate: false,
                      titleTarget: '.stepy-tab',
                      Enter: true,
                      next: function(index) {
                    
                     if(index==2)
                           
                    		$('.help-texts').empty().html(' <div class="helpblock0" ><div class="alert" role="alert"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p ><i class="fa fa-caret-right" ></i> &nbsp; If Probation / Notice Period doesnt applicable for the employee leave the field as "0"</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	Reporting Manger is the one the employee going to work under with. You can leave it as blank and fill it later on Employee Profile.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	Payment Mode: The Mode of payment which is being dispatched to the employee.</p></div></div></div>');
                         if(index==3)
                        	
                        	 $('.help-texts').empty().html(' <div class="helpblock0" ><div class="alert" role="alert"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p ><i class="fa fa-caret-right" ></i> &nbsp; Applying PF Limit Will limit the PF share from your salary. (as per the Govt. Limitations)</p><p ><i class="fa fa-caret-right" ></i>  &nbsp; You can Choose between Monthly & CTC (a yearly cumulative pay which includes projected variable pays & benefits)</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;  Slabs: predefined salary structures that makes you easy to provide salary on a single value (gross or basic)</p></div></div></div>');
                         if(index==4)
                         	
                        	 $('.help-texts').empty();
                 	 
                    	 },
                 back: function(index) {
                		if(index==1)
                			$('.help-texts').empty().html(' <div class="helpblock0" ><div class="alert" role="alert"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p ><i class="fa fa-caret-right" ></i> &nbsp; Have You created the Department/ Designation/Branch which you are going to give this employee?  if not create <a href="orgstr.php" style="text-decoration: underline;color:blue">here</a>here</p><p ><i class="fa fa-caret-right" ></i>  &nbsp; Double Clicking on the Prefix/Suffix will allow you to change your custom prefix/suffix</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	On Clicking the Employee ID Input, you can view last inserted employees on each prefix/suffix and their respective IDs.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	Fields that are marked with * are mandatory. The information which is left over can be filled on Employee Profile.</p></div></div></div>');
                   		if(index==2)
                   			$('.help-texts').empty().html(' <div class="helpblock0" ><div class="alert" role="alert"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p ><i class="fa fa-caret-right" ></i> &nbsp; If Probation / Notice Period doesnt applicable for the employee leave the field as "0"</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	Reporting Manger is the one the employee going to work under with. You can leave it as blank and fill it later on Employee Profile.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;	Payment Mode: The Mode of payment which is being dispatched to the employee.</p></div></div></div>');
                		 if(index==3)
                         	
                			 $('.help-texts').empty().html(' <div class="helpblock0" ><div class="alert" role="alert"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p ><i class="fa fa-caret-right" ></i> &nbsp; Applying PF Limit Will limit the PF share from your salary. (as per the Govt. Limitations)</p><p ><i class="fa fa-caret-right" ></i>  &nbsp; You can Choose between Monthly & CTC (a yearly cumulative pay which includes projected variable pays & benefits)</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;  Slabs: predefined salary structures that makes you easy to provide salary on a single value (gross or basic)</p></div></div></div>');
                   
                        }
                  });
              });

              
              $('#dob,#father_dob,#doj,#confirmation_date').datetimepicker({
            	  format: 'DD/MM/YYYY',
            	  maxDate:false
              }).on('dp.change', function(e){
                 $('#dob,#father_dob,#doj,#confirmation_date').next().remove();
                     if(e.viewMode == 'days')
            		  $(this).datetimepicker('hide');
                     setTimeout(function(){  $('#d_dob').html($('#dob').val());
                     }, 100);
                     setTimeout(function(){ $('#d_doj').html($('#doj').val());
                     }, 100);
              }); 


              $(function () {
                  $("#rep_man").autocomplete({
                      source: "php/employeeSearch.php",
                      response: function (event, ui) {
                          // ui.content is the array that's about to be sent to the response callback.
                          if (ui.content.length === 0) {
                              $(this).removeClass('spinner');
                              $(this).parent().find('.empty-message').fadeIn(300);
                              $(this).parent().find('.empty-message').fadeOut(1000);
                          } else {
                              $(this).parent().find('.empty-message').hide();
                          }
                      },
                      minLength: 2,
                      focus: function (event, ui) {
                          $("#rep_man").val(ui.item.employee_name+" "+ui.item.employee_lastname);
                          return false;
                      },
                      select: function (event, ui) {
                          $("#rep_man").val(ui.item.employee_name+" "+ui.item.employee_lastname);
                          $("#rep_man-id").val(ui.item.employee_id);
                          return false;
                      },
                      search: function () { $(this).addClass('spinner'); },
                      open: function () { $(this).removeClass('spinner'); },
                      change: function (event, ui) { if (ui.item == null) { $('#rep_man').val(""); $('#rep_man').val(""); } }
                  })
                          .autocomplete("instance")._renderItem = function (ul, item) {
                              return $("<li>")
                             
                               .append("<a>" + item.employee_name + " " + item.employee_lastname + " [" + item.employee_id + "] <br>" + [(item.employee_designation!='')?item.employee_designation+', ':' ' ] + [(item.employee_department!='')?item.employee_department + ", ":" " ]+ [(item.employee_branch!='')?item.employee_branch:" " ] + "</a>")
                              
                              //.append("<a>" + item.employee_name + " " + item.employee_lastname + " [" + item.employee_id + "] <br>" + item.employee_designation + "," + item.employee_department + ", " + item.employee_branch + "</a>")
                              .appendTo(ul);
                          };
              });

               });
          /* ready fn end */
          
          
       
    //Name validation only allow alphabets & space 
   $('#employee_name,#employee_lastname,#fname,#mname,#city,#emp_dist_taluk,#state,#emp_country,#aadhaar_name,#bank_branch').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z\b t]*$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str) || (e.keyCode) == 9){
            return true;
        }
        else
        {
        e.preventDefault();
        return false;
        }
    });

   //Number validation
    $('#employee_id,#mobile_no,#personal_no,#pin_code,#bank_ac,#aadhaar_id').keypress(function (event) {
        var keycode = event.which;
        if (!(event.shiftKey == false && ( keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57) ||  keycode == 0))) {
            event.preventDefault();
            return false;
        }
    });


    //do not allow special characters
    $('#pan_no,#bank_ifsc').keypress(function (event) {
    	 var regex = new RegExp("^[a-zA-Z0-9\b]+$");
         var keycode = event.which;
         var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
         if (regex.test(key)) {
            return true;
         }else if(keycode==0){
         	 return true;
             }else{
         	event.preventDefault();
             return false;
             }
    });
          
           $(document).on('change', "input[type=radio][name=salary_type]", "input[type=radio][name=input_type]",function (e){
		   e.preventDefault();
		   $('#slab_opt').empty();
		   var isctc = ($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
		   $.ajax({
               dataType: 'html',
               type: "POST",	
               url: "php/employee.handle.php",
               cache: false,
               data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!chooseSlabtype");?>', ctc:isctc },
               success: function (data) {
              	 var json_obj = $.parseJSON(data);
              	$('#slab_opt').append('<option value="select">Select</option>');
              	 for (i = 0; i < json_obj[2].length; i++)
              	 {
              	 	$('#slab_opt').append('<option id="' + json_obj[2][i]['slab_id'] + '" value="' + json_obj[2][i]['slab_id'] + '">' + json_obj[2][i]['slab_name'] + '</option>');
              	 } $('#slab_opt').append('<option value="Nil">No Slab</option>');
              	 	 $('#slab_opt').trigger('chosen:updated');
                }
	   		});
	   });
    	   
          $('#doj').datetimepicker({ format: 'DD/MM/YYYY', }).on("changeDate", function (e) {
              e.preventDefault();
              var date = new Date(e.date);
              date.setDate(date.getDate() + parseInt($('#probation_period').val()));
              $("#doj").datetimepicker('update', date);
              $("#confirmation_date").datetimepicker('update', date);
             
          });

          $('#doj').on('blur', function () {
              var date = $(this).val();
              $("#confirmation_date").val(date);
          });
          
          
          $('#probation_period').on('blur', function () {
              var date = $('#doj').val().split('/');
              date = new Date(date[2], date[1] - 1, date[0]);
              date.setDate(date.getDate() + parseInt($('#probation_period').val()));
              var dd = date.getDate();
              var mm = date.getMonth() + 1;
              var y = date.getFullYear();
              var someFormattedDate = dd + '/' + mm + '/' + y;	
              $("#confirmation_date").val(someFormattedDate);
          });
          
          /* Availability Checking for emp Id and emp username */
          $('#employee_id').on('blur', function () {
              if ($(this).val() != "") {
                  var xhr = $.ajax({
                      type: "POST",
                      url: "php/employeeSearch.php",
                      data: { "employee_id": $(this).val(), "emp_id_prefix": $('#emp_id_prefix').val(), "emp_id_suffix": $('#emp_id_suffix').val() },
                      cache: false,
                      beforeSend: function () {
                    	  $('#employee_id').removeClass('correct wrong');
                          $('#employee_id').addClass('spinner');
                      },
                      complete: function () {
                    	  $('#employee_id').removeClass('spinner');
                      },
                      success: function (data) {
                          data = JSON.parse(data);
                          if (data.length > 0) {
                              $('#employee_id').removeClass('correct wrong');
                              $('#employee_id').addClass('wrong');
                              $('#employee_id').val('');
                          } else {
                              $('#employee_id').removeClass('wrong correct');
                              $('#employee_id').addClass('correct');
                          }
                      }
                  });
              }

          });
       
          /* Get the last inserted employees */
          $('#employee_id').on('focus',function() {
              var xhr=$.ajax({
                  type:"POST",
                  url:"php/employee.handle.php",
                  data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getLastInsertedEmployees");?>'},
                  success: function (data) {
                  data = JSON.parse(data);
                  var emplast=data[2];
                  var html="";
                  for(i=0;i<emplast.length;i++){
					  
					  $.each(emplast[i],function(k,v){
					    	html += v + "," ;
					    	
					    });
					        
					  
                  }
                  html = html.slice(0,-1); 

               /*   $('#employee_id').popover({title:"<h5>Last Inserted Employees</h5>",content:html,html:true});
                  $('#employee_id').blur(function () {
                      $(this).popover('hide');
                  });*/
                  $("#employee_id")
                  .popover({title:"<h5>Last Inserted Employees</h5>",content:html,html:true})
                 
                  }
              });
         });
          /* Load City State by Pincode */
          $('#pin_code').on('blur', function () {
              if ($(this).val().length == 6) {
                  commonFunctions.getCityByPinCode($(this), $(this).val(), function (result) {
                      if (result.length > 0) {
                          $('#emp_dist_taluk').val(result[0].districtname);
                          $('#state').val(result[0].statename);
                          $('#emp_country').val('INDIA');
                          $('#d_addr3').text($("#city").val());
                          $('#d_addr4').text($("#state").val());
                          $('#d_addr5').text($("#pin_code").val());
                      }
                  });
              }
          });	
          

           
            $(document).on('blur', ".personalValidate", function (e) {
          	  e.preventDefault();
          	 validateForPersonal();
          	});
            $(document).on('blur', ".workvalidate", function (e) {
          	  e.preventDefault();
          	validateForWork();
          	});
                      
            $('#job_status,#dept,#desig,#branch_loc,#team,#shift,#payment_mode,#doj').on('change', function(evt, params) {
        	  validateForWork();
        	});

            $(document).on('blur', ".salaryValidate", function (e) {
          	  e.preventDefault();
          	  validateForSalary();
          	});
          	
          function validateForWork(){
               k=0;
        	  $('.text').remove();
        	  var inputs = $(".workvalidate");
        	
        	   for(var i = 0; i < inputs.length; i++){
        		   if($(inputs[i]).val()!=''){
        			   k++;
              		 $(inputs[i]).next().remove();
                  	 }else{
                         value= $(inputs[i]).attr('id');
                		 labelVal=$(inputs[i]).parent().parent().find('label').html();
                		 $(inputs[i]).next().remove();
                		 $(inputs[i]).after('<label class="help-block text-danger text">Enter '+labelVal+'</label>');
                     }
               }
             
             jobElment=$('#job_status').parent().parent().find('label').text();
        	 designElment=$('#desig').parent().parent().find('label').text();
        	 departElment=$('#dept').parent().parent().find('label').text();
        	 branchElment=$('#branch_loc').parent().parent().find('label').text();
        	 teamElment=$('#team').parent().parent().find('label').text();
        	 shiftElment=$('#shift').parent().parent().find('label').text();
        	 paymentElment=$('#payment_mode').parent().parent().find('label').text();
        	 ($('#job_status :selected').val()=='Select Job status')?0:k++;
        	 ($('#desig :selected').val()=='')?0:k++;
        	 ($('#dept :selected').val()=='')?0:k++;
        	 ($('#branch_loc :selected').val()=='')?0:k++;
        	 ($('#team :selected').val()=='')?0:k++;
        	 ($('#shift :selected').val()=='')?0:k++;
        	 ($('#payment_mode :selected').val()=='')?0:k++;
        	 ($('#job_status :selected').val()=='Select Job status')?
        			$('#job_status_chosen').after('<label class="help-block text-danger text">Enter '+jobElment+'</label>')
        			:$('#job_status_chosen').next().remove();

        	  ($('#desig :selected').val()=='')?
                	$('#desig_chosen').after('<label class="help-block text-danger text">Enter '+designElment+'</label>')
                	:$('#desig_chosen').next().remove();
              ($('#dept :selected').val()=='')?
                     $('#dept_chosen').after('<label class="help-block text-danger text">Enter '+departElment+'</label>')
                     :$('#dept_chosen').next().remove();
              ($('#branch_loc :selected').val()=='')?
                     $('#branch_loc_chosen').after('<label class="help-block text-danger text">Enter '+branchElment+'</label>')
                     :$('#branch_loc_chosen').next().remove();
              ($('#team :selected').val()=='')?
                             $('#team_chosen').after('<label class="help-block text-danger text">Enter '+teamElment+'</label>')
                             :$('#team_chosen').next().remove();
              ($('#shift :selected').val()=='')?
                      $('#shift_chosen').after('<label class="help-block text-danger text">Enter '+shiftElment+'</label>')
                     :$('#shift_chosen').next().remove();
              ($('#payment_mode :selected').val()=='')?
                             $('#payment_mode_chosen').after('<label class="help-block text-danger text">Enter '+paymentElment+'</label>')
                             :$('#payment_mode_chosen').next().remove();
              
              // $('#pfGross').val()+k;
                  var checkedValiodate=k;
                if(checkedValiodate==9){
                      $('.text').remove();
                        return true;
                     }else{
                    	 $('#employeeAddForm-title-1').click();
                    	}
                   } 
          
             function validateForPersonal(){
	              var inputs = $(".personalValidate");
	        	  var j=0;
	        	 for(var i = 0; i < inputs.length; i++){
	            	 if($(inputs[i]).val()!=''){
	            	     j++;
	           		 $(inputs[i]).next().remove();
	               	 }else{
	                      value= $(inputs[i]).attr('id');
	                     labelVal=$(inputs[i]).parent().parent().find('label').html();
	            		 $(inputs[i]).parent().find("label").remove();
	            		 $(inputs[i]).after('<label class="help-block text-danger text">Enter '+labelVal+'</label>');
	            		 }
	            }
	             if(j==16){
	            	$('.text').remove();
	               return true;
	        	  }else{
	             	 $('#employeeAddForm-title-0').click();
	             	 }
         }

             function salaryAmountvalidate(){
              $('.text').remove();
           	  var inputs = $(".salaryAmount");
           	  var j=0;
           	 for(var i = 0; i < inputs.length; i++){
               	 if($(inputs[i]).val()!=''){
               	     j++;
              		 }
               }
              if(inputs.length==0){
            	 $('#ctcSalaryCalc,#noSlabCaulation').after('<label class="help-block text-danger text">Continue To Click Calculate</label>');
                 $('#employeeAddForm-title-2').click();
              }else if(inputs.length==j){
                  $('.text').remove();
                  return true;
                }else{
           		$('#ctcSalaryCalc,#noSlabCaulation').after('<label class="help-block text-danger text">Continue To Click Calculate</label>');
                $('#employeeAddForm-title-2').click();
                }
               }
             
         
        $('#employeeAddForm').on('submit', function (e) {
        e.preventDefault();
    	//console.log($('#image_file').val();
       
        	flagAjaxcall=(validateForPersonal()==true)?((validateForWork()==true)?(validateForSalary()==true)?1:validateForSalary():validateForWork()):validateForPersonal();
           if(flagAjaxcall==1){
        	  
           			if($('input[name=mapLater]').is(':checked') || (!$('input[name=mapLater]').is(':checked') && salaryAmountvalidate()==true)){
           				if(!$('input[name=mapLater]').is(':checked')){
		     		     $('#employeeAddForm').find('input[type="text"]').each(function () {
			                        if ($(this).attr('data-type') == 'rupee') {
			                           $(this).val(deFormate($(this).val()));
			                            }
			                     }); 
           				}

           		
           				
			     	  $.ajax({
			     		  dataType: 'html',
			               type: "POST",
			               url: "php/employee-add.php",
			               cache: false,
			               data: $('#employeeAddForm').serialize(),
			               beforeSend:function(){
			               	$('#submitForm').button('loading'); 
			                 },
			                 complete:function(){
			                	 $('#submitForm').button('reset');
			                 },
			               success: function (data) {
			                   data1 = JSON.parse(data);
			                   if (data1[0] == "success") {
			                 	//var canvas = $("#preview_image")[0];
			             	   // var context = canvas.getContext("2d");
			             	    $('.jcrop-holder img').attr('src','http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image');
			             	  //	context.clearRect(0, 0, 1200, 1000); 
			             	    $('#employeeAddForm')[0].reset();
			                     $('#job_status,#desig,#dept,#branch_loc,#team,#payment_mode,#slab_opt').prop('selected', false).trigger('chosen:updated');
			                     $('#job_status,#desig,#dept,#branch_loc,#team,#payment_mode,#slab_opt').trigger('chosen:updated');
			                     $('#employeeAddForm-title-0').click();
			                     $('#getCTCcontent,#getTablecontent').html('');
			                     $("input[type=radio][name=input_type],input[type=radio][name=salary_type]").prop("checked", false);
			                     $('#employee_id').removeClass('correct wrong');
			                     var letterName = 'Offer:Confirmation';
			                     BootstrapDialog.show({
		            					type: BootstrapDialog.TYPE_SUCCESS,
		            					title: 'Information',
		            					message: data1[1]+'<a href="../hr/employees.php"></a><form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value= "' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+ '"><input type="hidden" name="actionId" id="employee_id" value = '+data1[2]+'><input type="hidden" name="letterName" id="letterName" value="'+letterName+'"> <a  title="Download Offer Letter" ><button class="btn btn-default pull-right" style="margin-right: 2%;" type="submit" id="Offer_letter">Download Offer Letter</button></a></form>',
		            					buttons: [{
		            						label: 'ok',
		            						cssClass: 'btn-primary' ,
		            						action: function(dialogRef){
		            							window.location.href = "../hr/employees.php?employeeID="+data1[2];
		            						}
		            				
		            					}]
		            		      });
			                   		
								//var letterName = 'Offer:Confirmation';
								//BootstrapDialog.alert(data1[1]+'<a href="../hr/employees.php"></a><form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value= "' +'?php echo  base64_encode ( _SESSION ['company_id'] . "!download" ) ;?>'+ '"><input type="hidden" name="actionId" id="employee_id" value = '+data1[2]+'><input type="hidden" name="letterName" id="letterName" value="'+letterName+'"> <a  title="Download Offer Letter" ><button class="btn btn-default pull-right" style="margin-right: 2%;" type="submit" id="Offer_letter">Download Offer Letter</button></a></form>');
			                      }else{
			                     BootstrapDialog.alert(data1[1]);
			                     }
			                   flagAjaxcall=0;
			                 }
			           });
			     	}else{
			     		   salaryAmountvalidate();
			     	 		}
        }
            
        });               

         $('#cancelCrop').on('click', function (e) {
        	 e.preventDefault();
        	 $('.close').click();  
        	 $('#imageexit').val(0);
        	//var canvas = $("#preview_image")[0];
  	       // var context = canvas.getContext("2d");
  	        $('.jcrop-holder img').attr('src','http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image');
  	        context.clearRect(0, 0, 1200, 1000);    
  	        document.getElementById("image_file").value = "";
       	   });
         
         $('#cropok').on('click', function (e) {
        	 e.preventDefault();
        	  $('.close').click();          	 
         });
         $('#empIdPrefix,#empIdSuffix').dblclick(function(e) {
            $('.value_column').show();
         });
         
         $(document).keyup(function(e) {
        	  if (e.keyCode === 13)  $('.value_column').hide();     // enter
        	  if (e.keyCode === 27)  $('.value_column').hide();   // esc
        	});
         $('.edit').on('blur',function(e){
             var oringEmpCode='<?php echo $emplyeeIdCode;?>';
             oringEmpCode=($('.edit').val())?$('.edit').val():oringEmpCode;
        	 $('#emp_id_prefix,#emp_id_suffix').val(oringEmpCode);
             $('#empIdPrefix,#empIdSuffix').html(oringEmpCode);
             $('.value_column').hide();
         });

         $(document).on('change', "input[type=radio][name=salary_type],input[type=radio][name=input_type],#slab_opt", function () {
          var salaryType=$("input[name='salary_type']:checked").val();
   		  var slabType = $("#slab_opt option:selected").val();
   		  var inputType=$("input[name='input_type']:checked").val();
   	    	  $('#getCTCcontent').html('');
   	    	 if(salaryType =='ctc'){
   	        var  mischtml='';
   	        var miscAllow = <?php echo json_encode($miscAlloDeduArray['MP']) ?>;
   	   
   	      	for (i = 0; i < miscAllow.length; i++) {
   	    	 mischtml+='<div class="form-group"><label class="col-lg-5 control-label">'+miscAllow[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control miscAlowDeduCtc"  name="allowances['+miscAllow[i].pay_structure_id+']"  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div>';
   			 }
   	         } 
   	 
   	if(slabType!='Nil')
   	{
      	if(salaryType =='ctc' && inputType =='monthly'){
          
   	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-4 control-label">CTC</label><div class="col-lg-8"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text"  id="Subctc" name="ctc" oninput="reFormate(this)" data-type="rupee" class="salaryValidate form-control" value="0"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" data-type="rupee" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text" name="ctc_fixed_component"  data-type="rupee" oninput="reFormate(this)" id="ctc" class="form-control salaryValidate" value="0"/></div></div>';
   	     $('#getCTCcontent').html('<form class="form-horizontal " id="gross_sal" name="grosSal" method="post" />'+
   					'<div class="col-lg-12"/>'+
   	    	     varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label></div></form>');
   	     }
   	     else if(salaryType =='ctc' && inputType =='annual'){
   	  	
   	    	var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-4 control-label">CTC</label><div class="col-lg-8"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text"  id="Subctc" name="ctc" oninput="reFormate(this)" data-type="rupee" class="salaryValidate form-control" value="0"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" data-type="rupee" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text" name="ctc_fixed_component"  data-type="rupee" oninput="reFormate(this)" id="ctc" class="form-control salaryValidate" value="0"/></div></div>';
   	     $('#getCTCcontent').html('<form class="form-horizontal " id="gross_sal" name="grosSal" method="post">'+
   						'<div class="col-lg-12" >'
   	    	     +varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label></div></form>');
   	     }
   	     else if(salaryType =='monthly' && inputType =='monthly'){
   	    	
   	     $('#getCTCcontent').html('<form class="form-horizontal " id="gross_sal" name="grosSal" method="post" />'+
   							'<div class="col-lg-12" />'
   							+'<div class="form-group"><label class="col-lg-4 control-label">Gross</label><div class="col-lg-8"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="ctc_fixed_component" data-type="rupee" id="ctc" value="0"/><input type="hidden" name="basic" id="basic" value="0"/><input type="text" name="gross" id="gross" class="form-control salaryValidate" value="0"  data-type="rupee" oninput="reFormate(this)" /></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc"style="margin-top:124px;margin-right:16px;">view salary</button><label class="help-block text-danger text" id="error-text"></label></div></div></div></form>');
   	     }
   	     else if(salaryType =='monthly' && inputType =='annual'){
   	    	
   	    	 $('#getCTCcontent').html('<form class="form-horizontal " id="gross_sal"  name="grosSal" method="post" />'+
   						'<div class="col-lg-12" />'+
   						'<div class="form-group"><label class="col-lg-4 control-label">Gross</label><div class="col-lg-8"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="ctc_fixed_component" data-type="rupee" id="ctc" value="0"/><input type="text" name="gross" id="gross" class="form-control salaryValidate" value="0"  data-type="rupee" oninput="reFormate(this)" /></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc"style="margin-top:124px;margin-right:16px;">view salary</button><label class="help-block text-danger text" id="error-text"></label></div></div></div></form>');
    	
   	     }else{
   	    	 $('#getCTCcontent,#getTablecontent').html('');
   	     }
   	}else{
   	     var ar = <?php echo json_encode($allowDeducArray['A']);?>;
   	  
   	     $('#getCTCcontent,#getTablecontent').html('');
   	     var html='';var inputFormDynami='';
   	  	 var varaibleComponents=(salaryType =='ctc' )?'<div class="form-group"><label class="col-lg-4 control-label">CTC</label><div class="col-lg-8"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="Subctc"  name="ctc" data-type="rupee" oninput="reFormate(this)"  autocomplete="off" value="0" required/></div></div><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div></div>':'';
   		 html+=(salaryType =='ctc' )?'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post">'+
   						'<div class="col-lg-12" >'+varaibleComponents+'</div>':'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post"/>'+
   						'<div class="col-lg-12" />';
   	     html+='<div class="form-group"><label class="col-lg-5 control-label">Gross Salary</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="gross"  data-type="rupee" name="gross"  oninput="reFormate(this)" autocomplete="off" value="0" readonly required/></div></div></div></form>';

   	     for (i = 0; i < ar.length; i++) {
   	    	inputFormDynami+=ar[i].pay_structure_id+',';
   			html+='<form class="form-horizontal " id="gross_sal" name="grosSal" method="post" />'+
   			'<div class="col-lg-12" />'+
   			'<div class="form-group"><label class="col-lg-5 control-label">'+ar[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id='+ar[i].pay_structure_id+'  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div></div></form>';
   			}
   			
   		 html+='<br><button class="btn btn-default pull-right"  data-id="'+inputFormDynami+'" type="button" id="noSlabCaulation">Calculate</button>';
		 $('#getCTCcontent').html(html);
   	     eventForNoSlab();
   	 
   	    }
     	  });

      $(document).on('click', "#noSlabCaulation", function (e) {
          e.preventDefault();
          $('.mapLater').hide();
          var parameters = {}; //declare the object
          parameters["act"] = '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>'; //set some value
          parameters["pfLimit"] = $('#pf_limit :selected').val();
          parameters["isCTC"] =($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
          parameters["isAnnual"] =($("input[type=radio][name=input_type]:checked").val()=='annual')?1:0;
          parameters["ctc"] =($('#ctc').val())?deFormate($('#ctc').val()):0;
          parameters["basic"] =0;
          $('#getCTCcontent').find('input[type="text"]').not(".miscAlowDeduCtc,#Subctc,#ctc").each(function(){
              if(this.id=='gross'){
             	 parameters['gross'] =deFormate(this.value);
              }else{
         	 parameters['allowances['+this.id+']'] =deFormate(this.value);
         	 }
          });
          falg=(validateForSalary()==true)?1:0;
      	if(falg==1){
          $.ajax({
               dataType: 'html',
               type: "POST",	
               url: "php/employee.handle.php",
               cache: false,
               data: parameters,
               beforeSend:function(){$('#loader').loading(true);$('#noSlabCaulation').button('loading');},
               success: function (data) {
             	  var json_obj = $.parseJSON(data); //parse JSON
             	  $('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable"><thead><tr class="headerReload"><th class="emptyDiv">Components</th><th>Rate</th><th style="text-align:right">Monthly</th><th style="text-align:right">Yearly</th></tr></thead>'+
                     	  setData(json_obj[2]));
             	  $('#loader').loading(false);
             	  $('#noSlabCaulation').button('reset');
              	}
         });
      	}
     }); 

      $(document).on('click', "#ctcSalaryCalc", function (e) {
          e.preventDefault();
         // $('.mapLater').hide();
          $('#getTablecontent').show();
          var ctc=deFormate($('#ctc').val());
          var gross=deFormate($('#gross').val());
          var slabId=$('#slab_opt :selected').val();
          var pfLimit = $('#pf_limit :selected').val();
          var isCTC=($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
          var isAnnual=($("input[type=radio][name=input_type]:checked").val()=='annual')?1:0;
          var checkIfexit=ctc+gross+slabId+pfLimit+isCTC+isAnnual;
          if(slabId){
          if(checkIfexit!=$('#checkIfexit').val()){
         	falg=(validateForSalary()==true)?1:0;
         	if(falg==1){
          $('#checkIfexit').val(checkIfexit);
          $('#error-text').html('');
          $.ajax({
               dataType: 'html',
               type: "POST",	
               url: "php/employee.handle.php",
               cache: false,
               data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>',
                       ctc:ctc,gross:gross,slabId:slabId,pfLimit:pfLimit,isCTC:isCTC,isAnnual:isAnnual},
               beforeSend:function(){$('#loader').loading(true);$('#ctcSalaryCalc').button('loading');},
               success: function (data) {
              	 var json_obj = $.parseJSON(data); //parse JSON
             
              	$('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable"><thead><tr class="headerReload"><th class="emptyDiv">Components</th><th>Rate</th><th style="text-align:right">Monthly</th><th style="text-align:right">Yearly</th></tr></thead>'+
                   	  setData(json_obj[2]));
            	
              	 $('#loader').loading(false);
              	$('#ctcSalaryCalc').button('reset');
              	}
           }); 
         }else{
         	 $('#getTablecontent,#checkIfexit').html('');
         	 }
          }
          }else{
         	 $('#getTablecontent,#checkIfexit').html('');
              $('#error-text').html('Enter Required Fields');
         }
     });
      $('input[name="mapLater"]').click(function() {
    	  if ($(this).is(':checked')) {
    	    // Do stuff
    		  $('.forctc').hide();
    		 $('#getTablecontent').hide();
    	  }
    	  else{
    		  $('.forctc').show();
    	    	  }
    	});

    
      
  </script>
</body>
</html>