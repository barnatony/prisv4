
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Company Details</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<style>
.well {
	padding: 6px;
}

.fileinput-button input {
	position: absolute;
	top: 0px;
	right: 0px;
	margin: 0px;
	opacity: 0;
	font-size: 200px;
	direction: ltr;
	cursor: pointer;
}

.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control
	{
	background-color: #FFF;
}

.css {
	border: 0px;
}

.fileinput-button {
	border-color: #78CD51;
	background: #78CD51 none repeat scroll 0% 0%;
	overflow: hidden;
	position: relative;
}
.lab{
	color:#797979;
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
					$company_id = $_SESSION ['company_id'];
					
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once (__DIR__."/sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
	
			<section class="wrapper site-min-height">
				<!-- page start-->
			
					<!-- company profile with no edit -->
				<div class="panel">
					<div class="panel-body">
					<form class="form-horizontal company-data" id="general-form" method="post"
					enctype="multipart/form-data">
					
					  <div class="col-md-6">
					  	
						<div class="form-group">
										<label class="col-lg-5 control-label"> Company Name</label>
										<div class=" col-lg-6">
											<input type="text" class="form-control"  id="company_name"
												name="company_name" disabled/>
										 </div>
						</div>
									<!--  	<p class="col-lg-4 form-control-static">(ID : <span class="company_user_name" id="company_user_name">hrlabz</span>)</p>-->
						<div class="form-group ">
						<div class=" col-lg-offset-5 col-lg-6">
						(ID :<span class="company_user_name" id="company_user_name"></span>)
						</div>
						</div>
						<div class="form-group">
										<label class="col-lg-5 control-label">Current Payroll Month</label>
										<div class="col-lg-7 ">
											<p class="current_payroll_month form-control-static" id="current_payroll_month"></p>
										</div>
						</div>
						<div class="form-group">
										<label class="col-lg-5 control-label">Leaves based On </label>
										<div class="col-lg-7 ">
											<p class="form-control-static" name="leave_based_on" id="leave_based_on"></p>
										</div>
						</div>
					</div>
						
						  <div class="col-md-6">
						  <div class="pull-right" style="margin-top: -2%;">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
								<i class=" fa fa-arrow-left"></i> All Settings</a>
								
						</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"
											style="text-align: center;margin-top:25px;">Company Logo</label>
										<div class="col-lg-7">
											<div class="fileupload-new thumbnail"
												style="width: 500px; height: 100px; margin-bottom: 20px;margin-top:7px;">
												<img class="company_logo" id="company_logo" name="company_logo" src="<?php echo $company_logo!=""? $company_logo:"http://www.placehold.it/500x138/EFEFEF/AAAAAA&text=no+image"; ?>"
													alt="Company Logo">
													
													
											</div>
											<span class="btn btn-success btn-sm fileinput-button"
												id="span_image" style="display:none;" >
											 <input type="file" class="inputfile"
												id="company_logo" name="company_logo" accept="image/jpeg,image/png" data-multiple-caption="{count} files selected" multiple />
												<label><i
												class="fa fa-upload type="file"></i> <span>Upload Image</span> </label>
											</span>
											 <em class="help-block well" id="well">Provide 500 x
												138 For Better Resolution</em>
										</div>

									</div>
								</div>
							<div class="col-md-12 buttons-bar">
					<button class="btn btn-default btn-xs pull-right edit" data-form="#general-form"><i class="fa fa-pencil"></i> Edit</button>
					</div>
					</form>
				</div>
		</div>
	</div>
					<!-- company profile with no edit -->
				

						<!-- company details1 starts here -->
	<div class="panel-group m-bot20" id="company-accordion">	  
				<div class="panel panel-default">
						<header class="panel-heading">
							<h4 class="panel-title">
								 <a class="accordion-toggle collapsed" data-toggle="collapse"
										data-parent="#company-accordion" href="#collapseOne">
								 			COMPANY DETAILS<i class="fa fa-plus pull-right"></i>
								  </a>
							</h4>
						</header>
					<div id="collapseOne" class="panel-collapse collapse">
					 <div class="panel-body">
						<form class="form-horizontal company-data1" id="company-data1" method="post"
					enctype="multipart/form-data">
							<div class="col-lg-12">
								<img id="preview">
			 					<div class="col-md-6" align="left">
									<div class="form-group">
											<label class="col-lg-5 control-label">Company Type </label>
											<div class="col-lg-7" >
											
												<select class="form-control company_type" name="company_type" id="company_type" disabled>
													<option value="">-----SELECT-----</option>
													<option value="privateltd">Private Ltd</option>
													<option value="trust">Trust</option>
													<option value="association">Association</option>
													<option value="institution">Institution</option>
													<option value="ltd">Ltd</option>
													<option value="llp">LLP</option>
													<option value="opc">OPC</option>
													<option value="partnership">Partnership</option>
													<option value="proprietary">Proprietary</option>
													<option value="liaison">Liaison</option>
													<option value="project_branch">Project/Branch Office</option>
												</select>
											</div>
									</div>
									
									<div class="form-group">
											<label class="col-lg-5 control-label"> Website </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_website" name="company_website" id="company_website"
													maxlength="40" disabled />
											</div>
									</div>
									</div>
									<div class="col-md-6">
									<div class="form-group">
											<label class="col-lg-5 control-label">Employee Id Type </label>
										
											<div class="col-lg-7 ">

												<label for="company_emp_id_prefix" class="col-lg-6 control-label "> <input
													name="emp_id_type" id="company_emp_id_prefix" maxlength="3"
													value="Prefix" type="radio" value="1" disabled> Prefix
												</label> <label for="company_emp_id_suffix"
													class="col-lg-6 control-label"> <input name="emp_id_type"
													id="company_emp_id_suffix" value="Suffix" value="0" type="radio" maxlength="3"
													disabled> Suffix
												</label>
											</div>
										</div>
										 <div class="form-group">
											<label class="col-lg-5 control-label label_name pre_suf_label"></label>
											<div class="col-lg-7 ">
												<input type="text" class="type_radio form-control e_pre_suf"
													required name="pre_suf" id="pre_suf" disabled>
											</div>
										</div>
								
								</div>
							
						</div>
						<div class="col-md-12 buttons-bar">
								<button class="btn btn-default btn-xs pull-right edit" data-form="#company-data1"><i class="fa fa-pencil"></i> Edit</button>
						   		
						</div>
						</form>			
				</div>
			</div>
				
		</div>
</div>
					<!-- company details1 end-->

					<!--company details2 starts here-->
	<div class="panel-group m-bot20" id="company-accordion1">			
					<div class="panel panel-default">
						<header class="panel-heading">
							<h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse"
										data-parent="#company-accordion1" href="#collapseTwo">
								 		COMPANY DETAILS<i class="fa fa-plus pull-right"></i>
									</a>
									
							</h4>
						</header>
						<div id="collapseTwo" class="panel-collapse collapse">
						<div class="panel-body">
							<form class="form-horizontal" id="company-data2" method="post"
					enctype="multipart/form-data">
							<div class="col-lg-12">
								<div class="alert alert-warning fade in" id="msgBox" style="display: none;">
									<button data-dismiss="alert" class="close close-sm"
										type="button">
										<i class="fa fa-times"></i>
									</button>
									<strong>Changes Detected!</strong> Awaiting for Administrator
									Appoval.
								</div>
								<div class="alert-warning fade in rejected_div" id="rejected_div"
									style="display: none;">
									<button data-dismiss="alert" class="close close-sm"
										type="button">
										<i class="fa fa-times"></i>
									</button>
									<strong>Changes Rejected by Admin!</strong>
									<h5>
										Reason :<em id="rejected_msg"></em>
									</h5>
								</div>
								<img id="preview">
								<div class="col-md-6" alingn="left">
										
										<div class="form-group">
											<label class="col-lg-5 control-label"> Date Of In-Corporation</label>
												<div class="col-md-7 input-group">
													<span class="input-group-addon" id="empIdPrefix"><i class="fa fa-calendar"></i></span> 
														<input type="text" class="form-control company_doi" name="company_doi" id="company_doi" disabled/>
												</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> CIN/LLPIN </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_cin_no" name="company_cin_no" id="company_cin_no"
													maxlength="25" disabled />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label"> PAN </label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control company_pan_no"
													name="company_pan_no" maxlength="25"
													id="company_pan_no" disabled />
											</div>
										</div>
										
									<div class="form-group">
											<label class="col-lg-5 control-label" id="company_tan_pattern">TAN Pattern</label>
											<div class="col-lg-7">
												 <label for="e_tan_pattern1"
													class="non_cen col-lg-6 control-label"> <input
													name="company_tan_pattern" id="e_tan_pattern1" value="C"
													type="radio" disabled> Centralised
												</label> <label for="e_tan_pattern2"
													class="non_cen col-lg-6 control-label"
													style="padding-left: 0px;"><input
													name="company_tan_pattern" id="e_tan_pattern2" value="NC"
													type="radio" disabled> Non-Centralised</label>
											</div>
									</div>
									<div class="form-group">
											<label class="col-lg-5 control-label">TAN </label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control" maxlength="20"
													name="company_tan_no" id="company_tan_no" disabled />
											</div>
									</div>	

							</div>


							<div class="col-md-6">
										<div class="form-group">
											<label class="col-lg-5 control-label" id="company_epf_pattern">EPF Pattern</label>
											<div class="col-lg-7">
												<label for="e_epf_pattern1"
													class="non_cen1 col-lg-6 control-label"> <input
													name="company_epf_pattern" id="e_epf_pattern1" value="C"
													type="radio" disabled> Centralised
												</label> <label for="e_epf_pattern2"
													class="non_cen1 col-lg-6 control-label"
													style="padding-left: 0px;"> <input
													name="company_epf_pattern" id="e_epf_pattern2" value="NC"
													type="radio" disabled> Non-Centralised
												</label>
										 	</div>
							          	</div>
									 <div class="form-group">
											<label class="col-lg-5 control-label">EPF Number</label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control"
													name="company_epf_no" maxlength="20" disabled
													id="company_epf_no" />
											</div>
									 </div>
									 <div class="form-group">
											<label class="col-lg-5 control-label" id="company_esi_pattern">ESI Pattern</label>
											<div class="col-lg-7">
												<label for="e_esi_pattern1"
													class="non_cen2 col-lg-6 control-label"> <input
													name="company_esi_pattern" id="e_esi_pattern1" value="C"
													type="radio" disabled> Centralised
												</label> <label for="e_esi_pattern2"
													class="non_cen2 col-lg-6 control-label"
													style="padding-left: 0px;"> <input
													name="company_esi_pattern" id="e_esi_pattern2" value="NC"
													type="radio" disabled> Non-Centralised
												</label>
											</div>
									</div>
									<div class="form-group">
											<label class="col-lg-5 control-label">ESI Number</label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control"
													name="company_esi_no" maxlength="20" disabled
													id="company_esi_no" />
											</div>
									</div>
									
								</div>
					   		 </div>
					  			
					  		<div class="col-md-12 buttons-bar">
								<button class="btn btn-default btn-xs pull-right edit" data-form="#company-data2"><i class="fa fa-pencil "></i> Edit</button>
						   		
							</div>
							</form>
				</div>
			</div>
		</div>
	</div>
									<!--company details2 -->
										<!-- contact details starts here -->
			<div class="panel-group m-bot20" id="contact-accordion">		
					<div class="panel panel-default">
						<header class="panel-heading">
							<h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse"
										data-parent="#contact-accordion" href="#collapseThree"> COMPANY CONTACT
										DETAILS <i class="fa fa-plus pull-right"></i>
									</a>
									<span class="lab"> ( Needs approval for editing )</span>
							</h4>		
								
						</header>

						<div id="collapseThree" class="panel-collapse collapse">
							<div class="panel-body">
							<form class="form-horizontal" id="contact-info" method="post"
					enctype="multipart/form-data">
								 <div class="col-lg-12">
								  <div class="alert alert-warning fade in" id="" style="display:none">
									<button data-dismiss="alert" class="close close-sm"
										type="button">
										<i class="fa fa-times"></i>
									</button>
									<strong>Changes Detected!</strong> Awaiting for Administrator
									Appoval.
								</div>
								<div class="alert-warning fade in rejected_div" id="rejected_div" 
									style="display: none;">
									<button data-dismiss="alert" class="close close-sm"
										type="button">
										<i class="fa fa-times"></i>
									</button>
									<strong>Changes Rejected by Admin!</strong>
									<h5>
										Reason :<em id="rejected_msg"></em>
									</h5>
								</div>
								<img id="preview">
									<div class="col-lg-6 ">
										<div class="form-group">
											<label class="col-lg-5 control-label"> Building Name</label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_build_name" id="company_build_name"
													name="company_build_name" required maxlength="20" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Street </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_street" name="company_street" id="company_street"
													required maxlength="24" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Area </label>
											<div class=" col-lg-7 ">
												<input type="text"
													class="form-control company_area" name="company_area" id="company_area"
													required maxlength="24" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Pincode</label>
											<div class=" col-lg-7 ">
												 <input type="text"
													class="form-control company_pin_code" id="company_pin_code"
													name="company_pin_code" required maxlength="6" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> City </label>
											<div class=" col-lg-7 ">
												<input type="text"
													class="form-control company_city" name="company_city" id="company_city"
													required maxlength="20" disabled />
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 control-label">State</label>
											<div class=" col-lg-7 ">
												 <input type="text"
													class="form-control company_state" name="company_state" id="company_state"
													required maxlength="20" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Phone </label>
											<div class="col-lg-7 ">
												<input type="text"
													class="form-control company_phone" name="company_phone" id="company_phone"
													required maxlength="11" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Mobile </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_mobile" name="company_mobile" id="company_mobile"
													required maxlength="10" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Email Id</label>
											<div class=" col-lg-7 ">
												 <input type="text"
													class="form-control company_email" name="company_email" id="company_email"
													required maxlength="45" disabled />
											</div>
										</div>
									 </div>
								</div>
								<div class="col-md-12 buttons-bar">
								<button class="btn btn-default btn-xs pull-right edit" data-form="#contact-info"><i class="fa fa-pencil"></i> Edit</button>
						   		
								</div>	
							</form>
								
						</div>
					</div>
				</div>
		</div>
								<!-- contact details starts here -->					
								<!-- Responsible's for company starts here -->
				<div class="panel-group m-bot20" id="responsible-accordion">				
						<div class="panel panel-default">
							<header class="panel-heading">
							   <h4 class="panel-title">
									<a class="accordion-toggle "
										data-toggle="collapse" data-parent="#responsible-accordion"
										href="#collapseFour"> RESPONSIBLE's FOR COMPANY<i
										class="fa fa-plus pull-right"></i>
									</a>
									<span class="lab"> ( Needs approval for editing )</span>
							  </h4>
							</header>
							<div id="collapseFour" class="panel-collapse collapse">
								<div class="panel-body">
								<form class="form-horizontal" id="responsible" method="post"
					enctype="multipart/form-data">
								<div class="col-lg-12">
								  <div class="alert alert-warning fade in" id="msgBox" style="display: none;">
									<button data-dismiss="alert" class="close close-sm"
										type="button">
										<i class="fa fa-times"></i>
									</button>
									<strong>Changes Detected!</strong> Awaiting for Administrator
									Appoval.
								</div>
								<div class="alert-warning fade in rejected_div" id="rejected_div"
									style="display: none;">
									<button data-dismiss="alert" class="close close-sm"
										type="button">
										<i class="fa fa-times"></i>
									</button>
									<strong>Changes Rejected by Admin!</strong>
									<h5>
										Reason :<em id="rejected_msg"></em>
									</h5>
								</div>
								<img id="preview">
									<div class="col-lg-6 ">
										<h5>Owner / Director / CEO Details</h5>
										<hr style="margin-top: 0px;">
							<div class="form-group">
											<label class="col-lg-5 control-label">Name</label>
											<div class=" col-lg-7 ">
												<input type="text"
													class="form-control company_resp1_name" id="company_resp1_name"
													name="company_resp1_name" required maxlength="20" disabled />
											</div>
										</div>
									  <div class="form-group">
											<label class="col-lg-5 control-label">User Name</label>
											<div class=" col-lg-7 ">
												<input type="text"
													class="form-control hr_1username" name="hr_1username" id="hr_1username"
													maxlength="20" required maxlength="10" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Designation </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_resp1_desgn" id="company_resp1_desgn"
													name="company_resp1_desgn" required maxlength="20" disabled />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label"> Mobile </label>
											<div class=" col-lg-7 ">
											<input type="text"
													class="form-control company_resp1_phone" id="company_resp1_phone"
													name="company_resp1_phone" required maxlength="10" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Email </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_resp1_email" id="company_resp1_email"
													name="company_resp1_email" required maxlength="40" disabled />
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<h5>HR Contact Details-2:</h5>
										<hr style="margin-top: 0px;">
										<div class="form-group">
											<label class="col-lg-5 control-label">Name </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_resp2_name" id="company_resp2_name"
													name="company_resp2_name" required maxlength="20" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">User Name </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control hr_2username" maxlength="20"  id="hr_2username" required
													name="hr_2username" maxlength="10" disabled />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> Designation </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_resp2_desgn" id="company_resp2_desgn"
													name="company_resp2_desgn" required maxlength="20" disabled />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label">Mobile </label>
											<div class="col-lg-7 ">
												 <input type="text"
													class="form-control company_resp2_phone" id="company_resp2_phone"
													name="company_resp2_phone" required disabled maxlength="10" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Email </label>
											<div class="col-lg-7 ">
												<input type="text"
													class="form-control company_resp2_email" id="company_resp2_email"
													name="company_resp2_email" required maxlength="40" disabled />
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 buttons-bar">
								<button class="btn btn-default btn-xs pull-right edit" data-form="#responsible"><i class="fa fa-pencil"></i> Edit</button>
						   		</div>
						   		</form>
							</div>
						</div>
					</div>
			</div>
				<!-- responsible's for company end here -->
				<!-- Salary / Attendance days starts here -->
			<div class="panel-group m-bot20" id="salary-attendance-accordion">	
						<div class="panel panel-default">
							<header class="panel-heading">
								<h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse"
										data-parent="#salary-attendance-accordion" href="#collapseFive">SALARY /
										ATTENDANCE DAYS <i class="fa fa-plus pull-right"></i>
									</a>
								</h4>
							</header>
							<div id="collapseFive" class="panel-collapse collapse">
								<div class="panel-body">
								<form class="form-horizontal" id="salary-attendance-days" method="post"
					enctype="multipart/form-data">
								<div class="col-lg-12">
								<img id="preview">
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-lg-5 control-label">Attendance period
												starts from</label>				
											<div class="col-lg-7">
												<select class="form-control" id="attendance_period_sdate" name="attendance_period_sdate" disabled>
													<option value="01">1st of Current Month</option>
													<option value="15">15th of Past Month</option>
													<option value="16">16th of Past Month</option>
													<option value="17">17th of Past Month</option>
													<option value="18">18th of Past Month</option>
													<option value="19">19th of Past Month</option>
													<option value="20">20th of Past Month</option>
													<option value="21">21st of Past Month</option>
													<option value="22">22nd of Past Month</option>
													<option value="23">23rd of Past Month</option>
													<option value="24">24th of Past Month</option>
													<option value="25">25th of Past Month</option>
													<option value="26">26th of Past Month</option>
													<option value="27">27th of Past Month</option>
													<option value="28">28th of Past Month</option>
													<option value="29">29th of Past Month</option>
													<option value="30">30th of Past Month</option>
													<option value="31">31st of Past Month</option>

												</select>
											</div>

										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 control-label">Salary Period</label>
											
											<div class="col-lg-7">
												<select class="form-control " id="salary_days"
													name="salary_days" disabled>
													<option value="select">-----SELECT-----</option>
													<option value="ad">Actual Days</option>
													<option value="30">Fixed-30</option>
													<option value="31">Fixed-31</option>
													<option value="wd">Business Days</option>
												</select>
											</div>
										</div>
									</div>
									</div>
									<div class="col-md-12 buttons-bar">
										<button class="btn btn-default btn-xs pull-right edit" data-form="#salary-attendance-days"><i class="fa fa-pencil"></i> Edit</button>
						   			</div>
									</form>
								</div>
								
							</div>
						</div>
				</div>
				
								
			
			
		
		</section>
		<!--main content end-->
		<!--footer start-->
			<?php include_once (__DIR__."/footer.php");?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<!-- END JAVASCRIPTS -->

	</body>
	<script type="text/javascript">
	$(document).ready(function () {
		var com_id="<?php echo $company_id;?>";
   	 	company_details_fill(com_id);
   	 $(document).on('click','.cancel',function(e){
   	   	 location.reload();
   		});
	});

	  $(document).on('click', '#back-button', function () {
	        $('.wrapper').hide();
	        $(".emp_list1").show();
	      
			 $('#employee_id-id').val('');
					    $('#rep_man-id').val('');
						$("#empView").find('div').css("display", "block");
	    });
	
	function isJson(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}
	function company_details_fill(com_id){ 
  	  $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/getCompanyDetails.php",
            cache: false,
            data: { company_id: com_id},
            success: function (data) {
            	//console.log(data);
                var json_obj= $.parseJSON(data)[0]; //parse JSON
                //console.log(json_obj);
                $.each(json_obj, function (k, v) {
                	                    //display the key and value pair
                    if(k=='current_payroll_month'||k=='company_user_name')
                        $('#'+k).text(v);
                    if(k=='leave_based_on'){
                        if(v=="calYear")
                        	$('#'+k).text("Calander Year");
                        else
                        	$('#'+k).text("Financial Year");
                    }
                    else if(k=="company_emp_id_prefix" || k=="company_emp_id_suffix"){
                        if(v!=0){
                            $("#"+k).prop('checked',true);
                        	$('#pre_suf').val(v);
                        	}
                        else
                        	$("#"+k).removeAttr('checked');
					}
                    else if(k=="company_doi"){
                        
                        var valueDate=GetFormattedDate(v);
               	 			$('#' + k).val(valueDate);
               	 	}
                    else if(k=='company_tan_pattern')
                    {
                       if(v!='C')
                    	   $("#e_tan_pattern2").prop('checked',true);
                       else
                           $("#e_tan_pattern1").prop('checked',true);
                     }else if(k=="company_tan_no"){
                         $("#"+k).val(v);
                         }
                     else if(k=='company_epf_pattern')
                     {
                        if(v!='C')
                            $("#e_epf_pattern2").prop('checked',true);
                        else
                            $('#e_epf_pattern1').prop('checked',true);
                        }
                     else if(k=='company_epf_no'){
                         $("#"+k).val(v);
                     }
                    
                    else if(k=='company_esi_pattern')
                        {
                        if(v!='C')
                            $("#e_esi_pattern2").attr('checked',true);
                        else
                            $("#e_esi_pattern1").attr('checked',true);
                        }
                    else if(k=="company_esi_no"){
                            $('#'+k).val(v);
                        }
                    else if(k=='info_flag')
    				{
        				if(v=='P'){
							//$("#company-data2").find(".alert").show();
							$("#contact-info").find(".alert").show();
							$("#responsible").find(".alert").show();
							//$("#company-data2").find(".edit").hide();
							$("#contact-info").find(".edit").hide();
							$("#responsible").find(".edit").hide();
        				}else if(v=='R'){
        					//$("#company-data2").find(".rejected_div").show();
        					$("#contact-info").find(".rejected_div").show();
        					$("#responsible").find(".rejected_div").show();
        					//$("#company-data2").find(".edit").hide();
							$("#contact-info").find(".edit").hide();
							$("#responsible").find(".edit").hide();
            			}
        				
    				}      
                    
                    else
                    	$('#'+k).val(v);
				});
              
                				
			}
        });  
       }
	
	 function toggleChevron(e) {
         $(e.target).prev('.panel-heading').find('i.fa').toggleClass('fa-minus fa-plus');
         $('#company-accordion', '.panel-heading').css('background-color', 'green');
         $(e.target).prev('.panel-heading1').find('i.fa').toggleClass('fa-minus fa-plus');
         $('#company-accordion1', '.panel-heading1').css('background-color', 'green');
         $(e.target).prev('.panel-heading2').find('i.fa').toggleClass('fa-minus fa-plus');
         $('#contact-accordion', '.panel-heading2').css('background-color', 'green');
         $(e.target).prev('.panel-heading3').find('i.fa').toggleClass('fa-minus fa-plus');
         $('#responsible-accordion', '.panel-heading3').css('background-color', 'green');
         $(e.target).prev('.panel-heading4').find('i.fa').toggleClass('fa-minus fa-plus');
         $('#salary-attendance-accordion', '.panel-heading4').css('background-color', 'green');
         }
     
     $('#company-accordion').on('hidden.bs.collapse', toggleChevron);
     $('#company-accordion').on('shown.bs.collapse', toggleChevron);
     
     $('#company-accordion1').on('hidden.bs.collapse', toggleChevron);
     $('#company-accordion1').on('shown.bs.collapse', toggleChevron);

     $('#contact-accordion').on('hidden.bs.collapse', toggleChevron);
     $('#contact-accordion').on('shown.bs.collapse', toggleChevron);

     $('#responsible-accordion').on('hidden.bs.collapse', toggleChevron);
     $('#responsible-accordion').on('shown.bs.collapse', toggleChevron);

     $('#salary-attendance-accordion').on('hidden.bs.collapse', toggleChevron);
     $('#salary-attendance-accordion').on('shown.bs.collapse', toggleChevron);

  $('.company_doi').datetimepicker({
         format: 'DD/MM/YYYY',
        });
 

   $('.edit').on('click',function(e){
        e.preventDefault();
        var form = $(this).data('form');
        var el = $(this);
		if(form =='#general-form')
        	$('#span_image').show();
        
        $(form).find('input[type=text],select').each(function(){
           $(this).removeAttr('readonly').removeClass('readonly').removeAttr('disabled')});

        $(form).find('input[type=radio]').each(function(){
            $(this).removeAttr('readonly').removeClass('readonly').removeAttr('disabled')});
       
         el.parent('.buttons-bar').html('<input type="hidden" name="form" value="'+form+'" ><button type="button" class="btn btn-danger btn-sm pull-right cancel" id="cancel"><i class="fa fa-undo"></i> Cancel</button><button type="submit" class="btn btn-success  btn-sm pull-right save" id="save" value="'+form+'" style="margin-right: 5px;"><i class="fa fa-check-square"></i> Save</button>');
        $('input[name=emp_id_type]').on('change', function() {
         	$('.pre_suf_label').text($(this).val());
         	
          }); 
	
          
   });

 +function ( document, window, index )
   {
   	var inputs = document.querySelectorAll( '.inputfile' );
   	Array.prototype.forEach.call( inputs, function( input )
   	{
   		var label	 = input.nextElementSibling,
   			labelVal = label.innerHTML;

   		input.addEventListener( 'change', function( e )
   		{
   			var fileName = '';
   			if( this.files && this.files.length > 1 )
   				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
   			else
   				fileName = e.target.value.split( '\\' ).pop();

   			if( fileName )
   				label.querySelector( 'span' ).innerHTML = fileName;
   			else
   				label.innerHTML = labelVal;
   		});

   		// Firefox bug fix
   		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
   		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
   	});
   }( document, window, 0 );
   
$(document).on('click','.save',function(e){
	e.preventDefault();
	var form = $($(this).val());
	var el=$(this);

	$.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        url: "php/companyDetails-update.php",
        cache: false,
        data: new FormData(form[0]),
        beforeSend:function(){
         	el.button('loading'); 
          },
          complete:function(){
         	 el.button('reset');
          },
        success: function (data) {
        	if(isJson(data) == false) {
        		alert("Error Occured : "+data);
             }else
            	data = JSON.parse(data);
            if(data[0]==true){
                alert("Updated Successfully");
                location.reload();
            }else{
				alert("Error Occured : "+data[1]);
                }
        },
        error:function(jqxhr,a,b){
            alert("Error Occurred");
        }

    });
});
	</script>
</html>
