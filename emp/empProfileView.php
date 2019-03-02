<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Mosaddek">
<meta name="keyword"
	content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
<link rel="shortcut icon" href="img/favicon.png">

<title>Employee List</title>

<!-- Bootstrap core CSS -->
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/jquery.Jcrop.min.css" rel="stylesheet" />

<link href="../css/slidebars.css" rel="stylesheet">
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style>
#department_id_chosen, #employee_nationality1_chosen,
	#employee_id_proof_type1_chosen, #job_status_chosen, #dept_chosen,
	#desig1_chosen_chosen, #branch_loc_chosen, #b_group_chosen,
	#employee_blood_group1_chosen,#employee_bank_name1_chosen, #emp_sslc_board1_chosen,
	#emp_hsc_board1_chosen, #branch_id_chosen {
	width: 100% !important;
}

.panel-heading1 {
	padding: 26px 15px;
}

.profile-nav .user-heading {
	background: #00BCD4;
}

.bio-graph-heading {
	background: #29B6F6;
}

.profile-nav ul>li>a:hover, .profile-nav ul>li>a:focus, .profile-nav ul li.active  a
	{
	border-left: 5px solid #00BCD4;
}
.attach{
	padding:2px 2px !important;
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
				
include ("header.php");
					$employee_id = $_SESSION ['employee_id'];
					$company_db = $_SESSION ['company_db'];
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
<?php

include_once ("sideMenu.php");
Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
Session::newInstance ()->_setMiscPayParams ();
$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
?>
         </aside>
		<!--sidebar end-->


		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
			tabindex="-1" id="employeeCrop" class="modal fade">
			<div class="modal-dialog  modal-lg">

				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close"
							type="button">&times;</button>
						<h4 class="modal-title">Employee Image Crop</h4>
					</div>
					<canvas id="preview_image" style="display: none"></canvas>
					<form id="upload_form" enctype="multipart/form-data" method="post"
						onsubmit="return checkForm()">

						<div class="modal-body">
							<div class="bbody">

								<!-- upload form -->
								<!-- hidden crop params -->
								<input type="hidden" id="x1" name="x1" /> <input type="hidden"
									id="y1" name="y1" /> <input type="hidden" id="x2" name="x2" />
								<input type="hidden" id="y2" name="y2" />
								<div>
									<span class="btn btn-success btn-sm fileinput-button"
										style="width: 134px;"> <i class="fa fa-upload"></i>Upload
										Image <input type="file" name="image_file" id="image_file"
										onchange="fileSelectHandler()" accept="image/jpeg,image/png"/>

									</span>
								</div>



								<div class="step2">
									<img id="preview" />

									<div class="info">
										<input type="hidden" id="w" name="w" /> <input type="hidden"
											id="h" name="h" /> <input type="hidden"
											class="form-control emp_new" name="employee_id" />
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="error pull-left" style="color: red"></div>
								<button type="button" class="btn btn-sm btn-danger"
									data-dismiss="modal">Cancel</button>
								<button type="submit" class="btn btn-sm btn-success"
									id="btnCrop">Crop</button>
							</div>

						</div>

					</form>
				</div>
			</div>
		</div>

		<!--main content start-->
		<section id="main-content">
			<section class="site-min-height wrapper">
				<!--page Starts-->
				<section class="emp_image_view " style="margin-top: -6px;">
					<!-- page start-->

					<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
						aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">

								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close"
										type="button">&times;</button>
									<h4 class="modal-title">Proof Image</h4>
								</div>
								<div class="modal-body">

									
			<div class="fileupload-new thumbnail">
			<img id="preview_image_model" style="width: 100%; height: 100%"/>
           </div>
								</div>
								<div class="modal-footer">
									<button data-dismiss="modal" class="btn btn-default"
										type="button">Close</button>

								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<aside class="profile-nav col-lg-3">
							<section class="panel">
								<form role="form" method="POST" class="form-horizontal"
         									enctype="multipart/form-data" id="emp_image">
         								<input type="hidden" class="form-control emp_new" name="employee_id" /> 
         								<input type="hidden" class="form-control" name="emp_image" value="image" />
         								<div class="user-heading round">
         								 <a href="#employeeCrop" style="color: #fff;"class="profile_id" data-toggle="modal" > 
         								 <img id="preview_image_" src='http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image'></a>
                                        <a class="profileAl">
                                       <span class="fileinput-button" style="width: inherit; height: inherit; border: medium none; background: #00BCD4 none repeat scroll 0% 0%;">
            						  <img id="round_preview_image" class="employee_image_preview "
            						    src="http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image" alt="Employee Image"> <input disabled type="file"
            						   name="employee_image" id="image" accept="image/jpeg,image/png" style="width: 116px; height: 97px; margin-top: -41px;" />
          						 </span>
          							</a>
          							<h1 id="emp_name"></h1>
         </div>
        </form>


								<input type="hidden" id="emp_id_now">
								<div id="loader" class="ul_loader"
									style="height: 58%; width: 85%"></div>
								<ul class="nav nav-pills nav-stacked" id="leave_account_tabs"
									role="tablist">
									<li class="profile_view "><a class="pro_list"
										id="personal_tabs" data-toggle="tab" data-loaded=false
										data-title="personal_tabs" href="#personals"> <i
											class="fa fa-user"></i>Personal Details
									</a></li>
									<li class="work_view "><a class="pro_list"
										id="work_detail_tabs" data-toggle="tab" data-loaded=false
										data-title="work_tabs" href="#work_detail"><i
											class="fa fa-briefcase"></i> Work Details</a></li>
									<li class="ctc_view "><a class="pro_list" id="salary_tabs"
										data-toggle="tab" data-loaded=false data-title="salary_tabs"
										href="#salary_detail"><i class="fa fa-money"></i> Salary </a></li>
									<li class="address_view"><a class="pro_list" id="address_tabs"
										data-toggle="tab" data-loaded=false data-title="personal_tabs"
										href="#for_address"><i class="fa fa-map-marker"></i>Address</a></li>
									<li class="career_view"><a class="pro_list" id="career_tabs"
										data-toggle="tab" data-loaded=false data-title="personal_tabs"
										href="#career"> <i class="fa fa-book"></i> Edu- Career
									</a></li>
									<li class="work_history_view"><a class="pro_list"
										id="work_tabs" data-toggle="tab" data-loaded=false
										data-title="work_tabs" href="#work_history"><i
											class="fa fa-building-o"></i> Work History </a></li>
								</ul>

							</section>
						</aside>
						<aside class="profile-info col-lg-9">

							<section class="panel">
								<div class="tab-content tasi-tab">
									<div class="tab-pane" id="personals">
										<div id="loader" class="personal_detail_loader"
											style="width: 96%"></div>
										<form role="form" method="POST" class="form-horizontal"
											enctype="multipart/form-data" id="emp_detail_form">

											<div class="personal bio-graph-heading">
												<p>
													<em>Employee Profile View </em> <input type="button"
														class="pull-right emp_edit1 btn-sm btn btn-success"
														value="Edit"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
													<input type="button"
														class="pull-right btn-sm emp_cancel1 btn btn-danger"
														value="Cancel"
														style="background-color: #f00; border-color: #f00"></input>
													<input type="submit"
														class="pull-right btn-sm emp_up1 btn btn-success"
														value="Update"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
												</p>
											</div>
											<!--Personal Strats-->
											<div class="personal panel-body">
												<h4 style="text-align: center">Bio Graph</h4>
												<div class="row">

													<div class="col-lg-6">

														<div class="form-group">
															<label class="col-lg-5 control-label">Employee ID</label>
															<div class=" col-lg-7 ">
																<input type="hidden" class="form-control"
																	name="personal" value="personal" />	
																	<input type="text" class="form-control emp_id" id="employee_id" name="employee_id" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">First Name</label>
															<div class="col-lg-7 ">
															<input type="text" class="form-control emp_name" name="employee_name" id="employee_name" 
															readonly />
																
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Last Name</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control emp_lastname"
																	name="employee_lastname" id="employee_lastname"
																	readonly/>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Date of Birth </label>
															<div class="col-md-7 col-xs-11 input-group">
																<span class="input-group-addon" id="empIdPrefix0"><i
																	class="fa fa-calendar"></i></span> <input type="text"
																	class="form-control emp_dob" name="employee_dob"
																	id="employee_dob" value="" readonly />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Gender</label>
															<div class="col-lg-7">
																<input type="text" class="form-control emp_gender"
																	id="employee_gender" readonly /> <label
																	for="gender1" class="col-lg-3 control-label male"><input
																	checked name="employee_gender" id="gender1"
																	value="Male" type="radio"> Male</label> <label
																	for="gender2" class="col-lg-3 control-label female"><input
																	name="employee_gender" id="gender2" value="Female"
																	type="radio"> Female</label> <label for="gender3"
																	class="col-lg-3 control-label other"><input
																	name="employee_gender" id="gender3" value="Trans"
																	type="radio"> Trans</label>

															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-5 control-label">Personal Mobile </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_personal_mobile" id="employee_personal_mobile" 
																	maxlength="10"  readonly required/>
																<span class="message"></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-5 control-label">Personal Email </label>
															<div class="col-lg-7 ">
																<input type="email" class="form-control emp_email"
																	name="employee_personal_email" id="employee_personal_email"
																	maxlength="30" readonly autocomplete="off" required />
																	<span style="margin-top: 10px;" class="col-lg-5 text-center pmobile-emp hide"> -</span>
															</div>
														</div>


														<div class="form-group">
															<label class="col-lg-5 control-label">Official Mobile </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_mobile" id="employee_mobile" 
																	maxlength="10"  readonly required/>
																	<span class="message"></span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-5 control-label">Official Email </label>
															<div class="col-lg-7 ">
																<input type="email" class="form-control emp_email"
																	name="employee_email" id="employee_email"
																	maxlength="70" readonly autocomplete="off" required />
																	<span style="margin-top: 10px;" class="col-lg-5 text-center omobile-emp hide"> -</span>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label"> Father's Name</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control "
																	name="employee_father_name" id="employee_father_name"
																	maxlength="30" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Father's DOB</label>
															<div class="col-lg-7">
																<input type="text" class="form-control"
																	name="father_dob" id="father_dob" maxlength="10" placeholder="dd/mm/yyyy" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Father's Mobile Number</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_father_mobile" id="father_mobile" 
																	maxlength="10"  readonly />
																	<span class="message"></span>
																	<span style="margin-top: 10px;" class="col-lg-5 text-center fmobile-emp hide"> -</span>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Mother's Name</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_mother_name" id="emp_mother_name"
																	maxlength="30" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Mother's DOB</label>
															<div class="col-lg-7">
																<input type="text" class="form-control"
																	name="emp_mother_dob" id="mother_dob"  maxlength="10" placeholder="dd/mm/yyyy" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Mother's Mobile Number</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_mother_mobile" id="mother_mobile" 
																	maxlength="10"  readonly />
																	<span class="message"></span>
																	<span style="margin-top: 10px;" class="col-lg-5 text-center mmobile-emp hide"> -</span>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Marital Status </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control emp_maritalstatus"
																	id="employee_marital_status" readonly name="employee_marital_status" /> <label
																	for="employee_marital_status1"
																	class="col-lg-6 m_l control-label"> <input
																	name="employee_marital_status" class="matrial1"
																	id="employee_marital_status1" value="Single"
																	type="radio" disabled> Single
																</label> <label for="employee_marital_status2"
																	class="col-lg-6 m_l control-label"
																	style="padding-left: 0px;"> <input
																	name="employee_marital_status" class="matrial2"
																	id="employee_marital_status2" value="Married"
																	type="radio" disabled> Married
																</label>
															</div>
														</div>

														<div class="form-group spouse_name_hide" id="spouse_name_hide">
															<label class="col-lg-5 control-label">Marriage Date</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_marriagedate" maxlength="10"
																	id="employee_marriagedate" readonly />
															</div>
														</div>

														<div class="form-group spouse_name_hide">
															<label class="col-lg-5 control-label">Spouse Name</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_spouse_name" id="spouse_name"
																	maxlength="30" readonly />
															</div>
														</div>
														
														<div class="form-group spouse_name_hide">
															<label class="col-lg-5 control-label">Spouse Mobile Number</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_spouse_mobile" id="spouse_mobile" 
																	maxlength="10"  readonly />
																	<span class="message"></span>
															</div>
														</div>
														
														<div class="form-group spouse_name_hide">
															<label class="col-lg-5 control-label">Spouse DOB</label>
															<div class="col-lg-7">
																<input type="text" class="form-control"
																	name="emp_spouse_dob" id="spouse_dob" maxlength="10" readonly/>
															</div>
														</div>
													</div>
											
												<div class="col-md-6">
														<div class="form-group">
															<label class="col-lg-5 control-label">Nationality</label>
															<div class=" col-lg-7">
																<input type="text" class="form-control"
																	id="employee_nationality" readonly /> <select
																	class="form-control" id="employee_nationality1"
																	name="employee_nationality">
																	<option>-----Select-----</option>
																	<option value="Afghan">Afghan</option>
																	<option value="Algerian">Algerian</option>
																	<option value="Angolan">Angolan</option>
																	<option value="Argentine">Argentine</option>
																	<option value="Austrian">Austrian</option>
																	<option value="Bangladeshi">Bangladeshi</option>
																	<option value="Belarusian">Belarusian</option>
																	<option value="Belgian">Belgian</option>
																	<option value="Bolivian">Bolivian</option>
																	<option value="Bosnian">Bosnian</option>
																	<option value="Brazilian">Brazilian</option>
																	<option value="British">British</option>
																	<option value="Bulgarian">Bulgarian</option>
																	<option value="Cambodian">Cambodian</option>
																	<option value="Cameroonian">Cameroonian</option>
																	<option value="Canadian">Canadian</option>
																	<option value="Central African">Central African</option>
																	<option value="Chadian">Chadian</option>
																	<option value="Chinese">Chinese</option>
																	<option value="Colombian">Colombian</option>
																	<option value="Costa Rican">Costa Rican</option>
																	<option value="Croatian">Croatian</option>
																	<option value="Czech">Czech</option>
																	<option value="Congolese">Congolese</option>
																	<option value="Danish">Danish</option>
																	<option value="Ecuadorian">Ecuadorian</option>
																	<option value="Egyptian">Egyptian</option>
																	<option value="English">English</option>
																	<option value="Estonian">Estonian</option>
																	<option value="Ethiopian">Ethiopian</option>
																	<option value="Finnish">Finnish</option>
																	<option value="French">French</option>
																	<option value="German">German</option>
																	<option value="Ghanaian">Ghanaian</option>
																	<option value="Greek">Greek</option>
																	<option value="Guatemalan">Guatemalan</option>
																	<option value="Dutch">Dutch</option>
																	<option value="Honduran">Honduran</option>
																	<option value="Hungarian">Hungarian</option>
																	<option value="Icelandic">Icelandic</option>
																	<option value="Indian">Indian</option>
																	<option value="Indonesian">Indonesian</option>
																	<option value="Iranian">Iranian</option>
																	<option value="Iraqi">Iraqi</option>
																	<option value="Irish">Irish</option>
																	<option value="Israeli">Israeli</option>
																	<option value="Italian">Italian</option>
																	<option value="Ivorian">Ivorian</option>
																	<option value="Jamaican">Jamaican</option>
																	<option value="Japanese">Japanese</option>
																	<option value="Jordanian">Jordanian</option>
																	<option value="Kazakh">Kazakh</option>
																	<option value="Kenyan">Kenyan</option>
																	<option value="Lao">Lao</option>
																	<option value="Latvian">Latvian</option>
																	<option value="Libyan">Libyan</option>
																	<option value="Lithuanian">Lithuanian</option>
																	<option value="Malagasy">Malagasy</option>
																	<option value="Malaysian">Malaysian</option>
																	<option value="Malian">Malian</option>
																	<option value="Mauritanian">Mauritanian</option>
																	<option value="Mexican">Mexican</option>
																	<option value="Moroccan">Moroccan</option>
																	<option value="Namibian">Namibian</option>
																	<option value="New Zealand">New Zealand</option>
																	<option value="Nicaraguan">Nicaraguan</option>
																	<option value="Nigerien">Nigerien</option>
																	<option value="Nigerian">Nigerian</option>
																	<option value="Norwegian">Norwegian</option>
																	<option value="Omani">Omani</option>
																	<option value="Pakistani">Pakistani</option>
																	<option value="Panamanian">Panamanian</option>
																	<option value="Paraguayan">Paraguayan</option>
																	<option value="Peruvian">Peruvian</option>
																	<option value="Philippine">Philippine</option>
																	<option value="Polish">Polish</option>
																	<option value="Portuguese">Portuguese</option>
																	<option value="Romanian">Romanian</option>
																	<option value="Russian">Russian</option>
																	<option value="Saudi Arabian">Saudi Arabian</option>
																	<option value="Scottish">Scottish</option>
																	<option value="Senegalese">Senegalese</option>
																	<option value="Serbian">Serbian</option>
																	<option value="Singaporean">Singaporean</option>
																	<option value="Slovak">Slovak</option>
																	<option value="Somalian">Somalian</option>
																	<option value="South African">South African</option>
																	<option value="Spanish">Spanish</option>
																	<option value="Sudanese">Sudanese</option>
																	<option value="Swedish">Swedish</option>
																	<option value="Swiss">Swiss</option>
																	<option value="Syrian">Syrian</option>
																	<option value="Thai">Thai</option>
																	<option value="Tunisian">Tunisian</option>
																	<option value="Turkish">Turkish</option>
																	<option value="Turkmen">Turkmen</option>
																	<option value="Ukranian">Ukranian</option>
																	<option value="Emirati">Emirati</option>
																	<option value="American">American</option>
																	<option value="Uruguayan">Uruguayan</option>
																	<option value="Vietnamese">Vietnamese</option>
																	<option value="Welsh">Welsh</option>
																	<option value="Zambian">Zambian</option>
																	<option value="Zimbabwean">Zimbabwean</option>


																</select>

															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">International
																Employee</label>
															<div class="col-lg-7">
																<input type="text" class="form-control"
																	id="employee_international" readonly="true" name="employee_international"/> <label
																	for="foreign" class="col-lg-6 m_l control-label"><input
																	name="employee_international" class="foreign"
																	id="foreign" value="1" type="radio" disabled> Yes</label>
																<label for="domestic" class="col-lg-6 m_l control-label"
																	style="padding-left: 0px;"><input
																	name="employee_international" id="domestic"
																	class="domestic" value="0" type="radio" disabled> No</label>
																<p class="col-lg-6 form-control-static" id="inter_emp"></p>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Blood Group</label>
															<div class="col-lg-7">
																<input type="text" class="form-control"
																	id="employee_blood_group" readonly="true" /> <select
																	class="form-control" id="employee_blood_group1"
																	name="employee_blood_group">
																	<option>-----Select-----</option>
																	<option value="APositive">A(Positive)</option>
																	<option value="ANegative">A(Negative)</option>
																	<option value="Bpositive">B(positive)</option>
																	<option value="BNegative">B(Negative)</option>
																	<option value="ABPositive">AB(Positive)</option>
																	<option value="ABNegative">AB(Negative)</option>
																	<option value="OPositive">O(Positive)</option>
																	<option value="ONegative">O(Negative)</option>
																</select>

															</div>
														</div>

													<div class="form-group">
															<label class="col-lg-5 control-label">Specially Disabled</label>
															<div class="col-lg-7">
																<input type="text" class="form-control" id="employee_pc" name="employee_pc" 
																	readonly="true" /> <label for="special"
																	class="col-lg-6 m_l control-label"><input
																	name="employee_pc" class="special" id="special"
																	value="1" type="radio" disabled> Yes</label> <label
																	for="able" class="col-lg-6 m_l control-label"
																	style="padding-left: 0px;"><input name="employee_pc"
																	id="able" class="able" value="0" type="radio" disabled>
																	No</label>

															</div>
														</div>


														<div class="form-group">
															<label class="col-lg-5 control-label">Pancard Number </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_pan_no" id="employee_pan_no"
																	maxlength="10" readonly/>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">PAN Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success btn-sm fileinput-button attach">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input class="image_change"
																			name="employee_pan_proof" id="image"
																			accept="image/jpeg,image/png" style="display: block;"
																			type="file">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger  view btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_pan_proof" /> <i class="fa fa-eye"></i>
																			View
																		</a>
																	</div>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Aadhaar card Number</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_aadhaar_id" id="employee_aadhaar_id"
																	maxlength="20" readonly />
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-5 control-label">Aadhaar Name</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_aadhaar_name" id="employee_aadhaar_name"
																	maxlength="30" readonly />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Aadhaar Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success fileinput-button attach btn-sm">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input type="file"
																			name="employee_aadhaar_proof" id="image"
																			class="image_change" accept="image/jpeg,image/png">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger view view_wid btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_aadhaar_proof"
																			value="../compDat/resized_income-tax-letter.jpg" /> <i
																			class="fa fa-eye"></i> View
																		</a>
																	</div>
																</div>

															</div>
														</div>



														<div class="form-group">
															<label class="col-lg-5 control-label">Bank Name </label>
															<div class="col-lg-7">
																<input type="text" id="employee_bank_name" name="employee_bank_name" 
																	class="form-control" readonly="true" />
																	
																	<select class="form-control" name="employee_bank_name1" id="employee_bank_name1">
																		
														 <option value>---Select---</option>
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
													     <option value="Punjab & Sindh Bank">Punjab & Sindh Bank</option>
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
															<label class="col-lg-5 control-label">Bank A/c Number </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_acc_no" id="employee_acc_no"
																	maxlength="30" readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Bank Branch</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control emp_bankbranch"
																	name="employee_bank_branch" maxlength="30"
																	id="employee_bank_branch" readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Bank IFSC </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_bank_ifsc" id="employee_bank_ifsc"
																	maxlength="12" readonly />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Bank Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success fileinput-button attach btn-sm">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input type="file"
																			name="employee_bank_proof" id="image" accept="image/jpeg,image/png"
																			class="image_change">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger view view_wid btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_bank_proof"
																			value="../compDat/resized_income-tax-letter.jpg" /> <i
																			class="fa fa-eye"></i> View
																		</a>
																	</div>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">ID Name</label>
															<div class=" col-lg-7">
																<input type="text" class="form-control"
																	id="employee_id_proof_type" readonly /> <select
																	class="form-control" id="employee_id_proof_type1"
																	name="employee_id_proof_type">
																	<option>-----Select-----</option>
																	<option value="passport">Passport</option>
																	<option value="driving">Driving License</option>
																	<option value="voterId">Voter Id</option>
																</select>

															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">ID Number </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_id_proof_no" id="employee_id_proof_no"
																	readonly />
															</div>
														</div>


														<div class="form-group ">
															<label class="col-lg-5 control-label"> ID Expiry Date </label>
															<div class="col-md-7 col-xs-11 input-group">
																<span class="input-group-addon" id="empIdPrefix1"><i
																	class="fa fa-calendar"></i></span> <input type="text"
																	class="form-control" name="employee_id_proof_expiry"
																	maxlength="10" id="employee_id_proof_expiry"
																	readonly />
															</div>
														</div>


														<div class="form-group">
															<label class="col-lg-5 control-label">ID Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success fileinput-button attach btn-sm">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input type="file"
																			name="employee_id_proof" id="image"
																			class="image_change" accept="image/jpeg,image/png">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger view view_wid btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_id_proof"
																			value="../compDat/resized_income-tax-letter.jpg" /> <i
																			class="fa fa-eye"></i> View
																		</a>
																	</div>
																</div>

															</div>
														</div>

													</div>
												</div>

											</div>
											<!--Personal Ends-->
										</form>

									</div>

									<!--Address Strats-->
									<div class="tab-pane" id="for_address">
										<form enctype="multipart/form-data" class="form-horizontal"
											id="emp_address_form" method="post">
											<div class="for_address bio-graph-heading">
												<p>
													<em>Employee Address </em>
													<!--  <input type="button"
														class="pull-right btn-sm emp_edit2 btn btn-success"
														value="Edit"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
													--><input type="button"
														class="pull-right btn-sm emp_cancel2 btn btn-danger"
														value="Cancel"
														style="background-color: #f00; border-color: #f00"></input>
													<input type="submit"
														class="pull-right btn-sm emp_up2 btn btn-success"
														value="Update"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
												</p>
											</div>

											<div class="for_address panel-body">
												<input type="hidden" class="form-control emp_new"
													id="emp_id_address" name="employee_id" /> <input
													type="hidden" class="form-control" name="address"
													value="address" />
												<h4 style="text-align: center">Communication Address</h4>
												<div class="row">
													<div class="col-lg-6">
														<h5>
															<b>TEMPORARY ADDRESS </b>
														</h5>
														<hr style="margin-top: 0px;">
														<div class="form-group">
															<label class="col-lg-5 control-label">Building Name </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_build_name" maxlength="70"
																	id="employee_build_name" readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Area</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_area" id="employee_area" maxlength="50"
																	readonly="true" />
															</div>
														</div>



														<div class="form-group">
															<label class="col-lg-5 control-label">Pincode</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_pin_code" id="employee_pin_code"
																	maxlength="6" readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">City </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_city" id="employee_city" maxlength="50"
																	readonly="true" />
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-5 control-label">District / Taluk </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_district" id="employee_district" maxlength="50"
																	readonly />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">State </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="employee_state" id="employee_state"
																	maxlength="50" readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Country </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_country" id="emp_country" maxlength="50"
																	readonly="true" />
															</div>
														</div>
														<div class="form-group" id="hide_label_address">
															<label class="col-lg-5 control-label" id="hide_que">If
																Choose Above the Address as Same or not</label>
															<div class="col-lg-7">
																<label for="emp_ad" class=" col-lg-6 m_l1 control-label">
																	<input name="employee_pt_adddress" id="emp_ad"
																	value="1" type="radio" disabled> Yes
																</label> 
																<label for="emp_ad1"
																	class=" col-lg-6 m_l1 control-label"
																	style="padding-left: 0px;"> <input
																	name="employee_pt_adddress" id="emp_ad1" value="0"
																	type="radio" disabled> No
																</label> 
																<input type="text"
																	class="col-lg-6 form-control-static" id="emp_address">
															</div>
														</div>

													</div>

													<div class="col-md-6">


														<h5>
															<b>PERMANENT ADDRESS </b>
														</h5>
														<hr style="margin-top: 0px;">
														<div class="match_address">
															<div class="form-group">
																<label class="col-lg-5 control-label"> Building Name </label>
																<div class="col-lg-7 ">
																	<input type="text" class="per_add  form-control"
																		name="permanent_emp_bulidname" maxlength="70" required
																		id="permanent_emp_bulidname" />
																</div>
															</div>
															<div class="form-group">
																<label class="col-lg-5 control-label"> Area</label>
																<div class="col-lg-7 ">
																	<input type="text" class="per_add  form-control"
																		name="permanent_emp_area" maxlength="50" required
																		id="permanent_emp_area" />
																</div>
															</div>

															<div class="form-group">
																<label class="col-lg-5 control-label"> Pincode</label>
																<div class="col-lg-7 ">
																	<input type="text" class="per_add form-control"
																		name="permanent_emp_pincode" maxlength="6" required
																		id="permanent_emp_pincode" />
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-lg-5 control-label">City </label>
																<div class="col-lg-7 ">
																	<input type="text" class="per_add form-control"
																		name="permanent_emp_city" maxlength="50" required
																		id="permanent_emp_city" />
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-lg-5 control-label">District / Taluk </label>
																<div class="col-lg-7 ">
																	<input type="text" class="per_add form-control"
																		name="permanent_emp_dist" maxlength="50" required
																		id="permanent_emp_dist" />
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-lg-5 control-label"> State </label>
																<div class="col-lg-7 ">
																	<input type="text" class="per_add form-control"
																		name="permanent_emp_state" maxlength="50" required
																		id="permanent_emp_state" />
																</div>
															</div>
															<div class="form-group">
																<label class="col-lg-5 control-label"> Country </label>
																<div class="col-lg-7 ">
																	<input type="text" class="per_add form-control"
																		name="permanent_emp_country" maxlength="50" required
																		id="permanent_emp_country" />
																</div>
															</div>
														</div>
													</div>

												</div>

											</div>
										</form>
									</div>
									<!--Address Ends-->

									<!--Educational Career Strats-->
									<div class="tab-pane" id="career">
										<form enctype="multipart/form-data" class="form-horizontal"
											id="emp_edu_form" method="post" action="">
											<input type="hidden" value="education" name="education"> <input
												type="hidden" class="form-control emp_new" id="emp_id_edu"
												name="employee_id" />

											<div class="career bio-graph-heading">
												<p>
													<em>Employee Education </em>
												
													<input type="button"
														class="pull-right btn-sm emp_edit3 btn btn-success"
														value="Edit"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
													<input type="button"
														class="pull-right btn-sm emp_cancel3 btn btn-danger"
														value="Cancel"
														style="background-color: #f00; border-color: #f00"></input>
													<input type="submit"
														class="pull-right btn-sm emp_up3 btn btn-success"
														value="Update"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
												</p>
											</div>

											<div class="career panel-body">
												<h4 style="text-align: center">Career</h4>
												<div class="row">
													<div class="col-lg-6">
														<h5>
															<b>SSLC </b>
														</h5>
														<hr style="margin-top: 0px;">
														<div class="form-group">
															<label class="col-lg-5 control-label"> School Name </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control emp_sslc_school"
																	name="emp_sslc_school" id="emp_sslc_school"
																	maxlength="50" readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Board Of Education</label>
															<div class=" col-lg-7">
																<input type="text" class="form-control"
																	name="emp_sslc_board" id="emp_sslc_board"
																	readonly="true" /> <select class="form-control"
																	id="emp_sslc_board1" name="emp_sslc_board1">
																	<option>-----Select-----</option>
																	<option value="StateBoard">StateBoard</option>
																	<option value="Matriculation">Matriculation</option>
																	<option value="CBSE">CBSE</option>
																	<option value="ICSE">ICSE</option>
																	<option value="IB">IB</option>
																</select>

															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Marks (in
																percentage)</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control emp_sslc_marks"
																	name="emp_sslc_marks" id="emp_sslc_marks" maxlength="5"
																	readonly="true" />
															</div>
														</div>
														<div class="form-group ">
															<label class="col-lg-5 control-label"> Year of Passing </label>
															<div class="col-md-7 col-xs-11 input-group">
																<span class="input-group-addon empIdPrefix1_edu"><i
																	class="fa fa-calendar"></i></span> <input type="text"
																	class="period1 form-control emp_sslc_year" name="emp_sslc_year"
																	id="emp_sslc_year" maxlength="4" readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success fileinput-button attach_edu btn-sm attach">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input type="file"
																			name="employee_sslc_proof" class="image_change_edu" accept="image/jpeg,image/png">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger  view btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_sslc_proof"
																			value="../compDat/resized_income-tax-letter.jpg" /> <i
																			class="fa fa-eye"></i> View
																		</a>
																	</div>
																</div>
															</div>
														</div>
															<br>
														<h5>
															<b>GRADUATION </b>
														</h5>
														<hr style="margin-top: 0px;">
														<div class="form-group">
															<label class="col-lg-5 control-label"> Institution Name</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_ug_institute_name" id="emp_ug_institute_name"
																	maxlength="35" readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> University </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_ug_university" id="emp_ug_university"
																	maxlength="35" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Name of the Degree </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_ug_degree" id="emp_ug_degree"
																	maxlength="35" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Major Subject </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_ug_major_subject" id="emp_ug_major_subject"
																	maxlength="35" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Marks (in
																percentage)</label>
															<div class="col-lg-7">
																<input type="text" class="form-control"
																	name="emp_ug_marks" id="emp_ug_marks" maxlength="5"
																	readonly="true" />
															</div>
														</div>
														<div class="form-group ">
															<label class="col-lg-5 control-label"> Year of Passing </label>
															<div class="col-md-7 col-xs-11 input-group">
																<span class="input-group-addon empIdPrefix1_edu"><i
																	class="fa fa-calendar"></i></span> <input type="text"
																	class="period3 form-control" name="emp_ug_year_passing"
																	id="emp_ug_year_passing" maxlength="4" readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label"> Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success fileinput-button attach_edu btn-sm attach">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input type="file"
																			name="employee_ug_proof" id="image"
																			class="image_change_edu" accept="image/jpeg,image/png">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger  view btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_ug_proof"
																			value="../compDat/resized_income-tax-letter.jpg" /> <i
																			class="fa fa-eye"></i> View
																		</a>
																	</div>
																</div>

															</div>
														</div>

														
													
													</div>

													<div class="col-md-6">
														<h5>
															<b>HSC </b>
														</h5>
														<hr style="margin-top: 0px;">
														<div class="form-group">
															<label class="col-lg-5 control-label"> School Name </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control emp_hsc_school"
																	name="emp_hsc_school" id="emp_hsc_school"
																	maxlength="30" readonly="true" />
															</div>
												 			</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Board Of Education</label>
															<div class=" col-lg-7">
																<input type="text" class="form-control"
																	name="emp_hsc_board" id="emp_hsc_board" readonly="true" />
																<select class="form-control" id="emp_hsc_board1"
																	name="emp_hsc_board1">
																	<option>-----Select-----</option>
																	<option value="StateBoard">StateBoard</option>
																	<option value="Matriculation">Matriculation</option>
																	<option value="CBSE">CBSE</option>
																	<option value="ICSE">ICSE</option>
																	<option value="IB">IB</option>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Marks (in
																percentage)</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_hsc_marks" id="emp_hsc_marks" maxlength="5"
																	readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Year of Passing </label>
															<div class="col-md-7 col-xs-11 input-group">
																<span class="input-group-addon empIdPrefix1_edu"><i
																	class="fa fa-calendar"></i></span> <input type="text"
																	class="period2 form-control" name="emp_hsc_year"
																	id="emp_hsc_year" maxlength="4" readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success fileinput-button attach_edu btn-xs attach">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input type="file"
																			name="employee_hsc_proof" class="image_change_edu" accept="image/jpeg,image/png">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger  view btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_hsc_proof" value="" /> <i
																			class="fa fa-eye"></i> View
																		</a>
																	</div>
																</div>
															</div>
														</div>
														<br>
														<h5>
															<b>POST-GRADUATION </b>
														</h5>
														<hr style="margin-top: 0px;">
														<div class="form-group">
															<label class="col-lg-5 control-label"> Institution Name</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_pg_institute_name" id="emp_pg_institute_name"
																	maxlength="35" readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Universty </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_pg_university" id="emp_pg_university"
																	maxlength="35" readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Name of the Degree </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_pg_degree" id="emp_pg_degree"
																	maxlength="35" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Major Subject </label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_pg_major_subject" id="emp_pg_major_subject"
																	maxlength="35" readonly />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Marks (in
																percentage)</label>
															<div class="col-lg-7 ">
																<input type="text" class="form-control"
																	name="emp_pg_marks" id="emp_pg_marks" maxlength="5"
																	readonly="true" />
															</div>
														</div>
														<div class="form-group ">
															<label class="col-lg-5 control-label"> Year of Passing </label>
															<div class="col-md-7 col-xs-11 input-group">
																<span class="input-group-addon empIdPrefix1_edu"><i
																	class="fa fa-calendar"></i></span> <input type="text"
																	class="period4 form-control" name="emp_pg_year_passing"
																	id="emp_pg_year_passing" maxlength="4" readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label"> Proof</label>
															<div class="col-lg-7">
																<div class="row fileupload-buttonbar ">
																	<div class="col-lg-6">
																		<span
																			class="btn btn-success fileinput-button attach_edu btn-sm attach">
																			<i class="glyphicon glyphicon-plus"></i> <span>Add
																				files</span> <input type="file"
																			name="employee_pg_proof" class="image_change_edu" accept="image/jpeg,image/png">
																		</span>
																	</div>
																	<div class="col-lg-6">
																		<a class="btn btn-danger  view btn-xs"
																			data-toggle="modal" href="#myModal"> <input
																			type="hidden" class="form-control"
																			id="employee_pg_proof"
																			value="../compDat/resized_income-tax-letter.jpg" /> <i
																			class="fa fa-eye"></i> View
																		</a>
																	</div>
																</div>

															</div>
														</div>

													</div>

												</div>

											</div>
										</form>
									</div>
									<!--Educational Career Ends-->

									<!--CTC Details Strats-->
									<div class="tab-pane" id="salary_detail">
										<div id="loader" class="ctc_detail_loader" style="width: 96%"></div>
										<form enctype="multipart/form-data" class="form-horizontal"
											id="emp_ctc_form" method="post" action="">
											<input type="hidden" value="ctc" name="fromSalary"> <input
												type="hidden" class="form-control emp_new" id="emp_ctc_id"
												name="employee_id" />
											<div class="ctc_detail_head bio-graph-heading">
												<p>
													<em>Employee Salary [<span id="effect_sal_from"></span>]
													</em>
													<input type="button"
														class="pull-right emp_edit7 btn-sm btn btn-success"
														value="Edit"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
													<input type="button"
														class="pull-right btn-sm emp_cancel7 btn btn-danger"
														value="Cancel"
														style="background-color: #f00; border-color: #f00"></input>
													<input type="submit"
														class="pull-right btn-sm emp_up7 btn btn-success"
														value="Update"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
												</p>
											</div>
											<div class="ctc_detail panel-body">
												<div class="row">
													<div class="col-lg-6">
														<div class="form-group">
														<label class="col-lg-5 control-label" for="pf_limit">PF</label>
															
														<div class="col-lg-7">
															<select class="form-control" id="pf_limit" name="pf_limit">
			                                               	    <option value="1" selected>Apply PF Limit</option>
																<option value="0">Don't Apply PF Limit</option>
																<option value="-1">Exclude from PF</option>
															</select>
															
														</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Salary Type</label>
															<div class="col-lg-7">
																<label for="ctcval" class="col-lg-4 control-label"
																	style="padding-right: 0"><input name="salary_type"
																	id="ctcval" value="ctc" type="radio"> CTC</label> <label
																	for="monthly" class="col-lg-4 control-label"
																	style="padding-left: 0; padding-right: 0"><input
																	name="salary_type" id="monthly" value="monthly"
																	type="radio"> MONTHLY</label>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Slab Type</label>
															<div class="col-lg-7 hiddenSpan">
																<label for="salary_based_on1"
																	class="col-lg-4 control-label" style="padding-right: 0"><input
																	name="salary_based_on" id="salary_based_on1"
																	value="basic" type="radio"> Basic</label> <label
																	for="salary_based_on2" class="col-lg-4 control-label"
																	style="padding-left: 0; padding-right: 0"><input
																	name="salary_based_on" id="salary_based_on2"
																	value="gross" type="radio"> Gross</label> <label
																	for="salary_based_on3" class="col-lg-4 control-label"
																	style="padding-left: 0; padding-right: 0"><input
																	name="salary_based_on" id="salary_based_on3"
																	value="noslab" type="radio"> No Slab</label>
															</div>
															<div class="col-lg-7 showSpan">
																<input type="text" class="form-control"
																	id="slabTypedata" value="No Slab" />
															</div>
														</div>
														<div id="slabloader" style="width: 100%; height: 50%"></div>
														<input type="hidden" id="checkIfexit">
														<div class="form-group" id="slab_select_box">
															<label class="col-lg-5 control-label">Slab Name</label>
															<div class="col-lg-7 showSpan">
																<input type="text" class="form-control"
																	id="slabdata" />

															</div>
															<div class="col-lg-7 hiddenSpan">
																<select id="slab_opt" name="slab" class="form-control">
																	<option value="">Select Slab</option>
																</select> <span class="help-block"
																	id="minimum_salary_div" style="display: none">Applicable
																	only for salary gt <span id="min_salary_amount"></span>
																</span>
															</div>
														</div>
														<div id="getCTCcontent"></div>
													</div>

													<div class="col-lg-6">
														<div class="form-group" id="lineSet">
															<div id="loader" style="width: 93%; height: 93%"></div>
															<div id="getTablecontent" class="container"
																style="width: 90%"></div>
														</div>
													</div>
												</div>
											</div>
										</form>
									</div>
									<!--CTC Details Ends-->

									<!--Work history Strats-->
									<div class="tab-pane" id="work_history">
										<div id="loader" class="work_detail_loader" style="width: 96%"></div>
										<form enctype="multipart/form-data" class="form-horizontal"
											id="emp_work_histroy_form" method="post" action="">
											<div class="work_history bio-graph-heading">
												<p>
													<em>Employee Work History </em> <input type="button"
														class="pull-right emp_edit4 btn-sm btn btn-success"
														value="Edit"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
													<input type="button"
														class="pull-right btn-sm emp_cancel4 btn btn-danger"
														value="Cancel"
														style="background-color: #f00; border-color: #f00"></input>
													<input type="submit"
														class="pull-right btn-sm emp_up4 btn btn-success"
														value="Update"
														style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
												</p>
											</div>
											<div class="work_history panel-body">
												<h4 class="exp" style="text-align: center">
												</h4>
													<button type="button"
														class="pull-right btn btn-sm  btn-success" id="addCompany">Add
														Company</button>
													<button style="border: 1px solid rgb(226, 226, 228);"
														type="button"
														class="hide pull-right btn btn-sm remove btn-danger">
														<i class="fa fa-times"></i> Remove
													</button>
													<input type="hidden" id="addcompanyno" value='1'
														name="totExper">
												
												<input type="hidden" value="workExp" name="workExp"> <input
													type="hidden" class="form-control emp_new" id="empExpId"
													name="employee_id" />
												
												<!--  Row End-->
											</div>
												<div class="col-lg-12">
													<div id="1_addCompanyContent" class="delete"></div>
												</div>
										</form>
									</div>
									<!--Work history Ends-->

									<!--Working Details Strats-->
									<div class="tab-pane" id="work_detail">
										<div id="loader" class="work_detail_loader" style="width: 96%"></div>
										<form enctype="multipart/form-data" class="form-horizontal"
											id="emp_work_items" method="post" action="">
											<input type="hidden" value="work_items" name="work_items"> <input
												type="hidden" class="form-control emp_new" id="emp_work_id"
												name="employee_id" />

											<div class="work_detail_head bio-graph-heading">

												<p>
													<em> Employee work Detail</em>
											
											</div>
											<div class="work_detail panel-body">

												<h4 style="text-align: center">Work Particulars</h4>
												<input type="hidden" id="payrollCheck">
												<div class="row">

													<div class="col-lg-6 ">

														<div class="form-group">
															<label class="col-lg-5 control-label"> Designation</label>
															<div class="col-lg-7 ">
																<input type="text" class="work_details form-control"
																	id="designation_id1" readonly="true" /> <select
																	class="form-control" id="desig1" name="designation_id">
																	<option value="">Select Designation</option>
                                                   <?php
																																																			$stmt = mysqli_prepare ( $conn, "SELECT designation_id, designation_name, designation_hierarchy FROM company_designations" );
																																																			$result = mysqli_stmt_execute ( $stmt );
																																																			mysqli_stmt_bind_result ( $stmt, $designation_id, $designation_name, $designation_hierarchy );
																																																			while ( mysqli_stmt_fetch ( $stmt ) ) {
																																																				echo "<option value='" . $designation_id . "'>" . $designation_name . "</option>";
																																																			}
																																																			?>
                                                  </select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label"> Department</label>
															<div class="col-lg-7 ">
																<input type="text" class="work_details form-control"
																	id="department_name" readonly="true" /> <select
																	class="form-control" id="dept1" name="department_id">
																	<option value="">Select Department</option>    
                                               <?php
																																															$stmt = mysqli_prepare ( $conn, "SELECT department_id, department_name FROM company_departments" );
																																															$result = mysqli_stmt_execute ( $stmt );
																																															mysqli_stmt_bind_result ( $stmt, $department_id, $department_name );
																																															while ( mysqli_stmt_fetch ( $stmt ) ) {
																																																echo "<option value='" . $department_id . "'>" . $department_name . "</option>";
																																															}
																																															?>
                                              </select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Branch</label>
															<div class="col-lg-7 ">
																<input type="text" class="work_details form-control"
																	id="branch_name" readonly="true" /> <select
																	class="form-control" id="branch_loc1" name="branch_id">
																	<option value="">Select Branch</option>
                                                   <?php
																																																			$stmt = mysqli_prepare ( $conn, "SELECT branch_id, branch_name FROM company_branch" );
																																																			$result = mysqli_stmt_execute ( $stmt );
																																																			mysqli_stmt_bind_result ( $stmt, $branch_id, $branch_name );
																																																			while ( mysqli_stmt_fetch ( $stmt ) ) {
																																																				echo "<option value='" . $branch_id . "'>" . $branch_name . "</option>";
																																																			}
																																																			?>    
                                              </select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Job Status</label>
															<div class="col-lg-7 ">
																<input type="text" class="work_details form-control"
																	id="status_name" readonly="true" /> <select
																	class="form-control" id="status_name1" name="status_id">
                                                <?php
																																																$stmt = mysqli_prepare ( $conn, "SELECT status_id, status_name, status_comments FROM company_job_statuses" );
																																																$result = mysqli_stmt_execute ( $stmt );
																																																mysqli_stmt_bind_result ( $stmt, $status_id, $status_name, $status_comments );
																																																while ( mysqli_stmt_fetch ( $stmt ) ) {
																																																	echo "<option value='" . $status_id . "'>" . $status_name . "</option>";
																																																}
																																																?>
                                              </select>
															</div>
														</div>


														<div class="form-group">
															<label class="col-lg-5 control-label"> Payment Mode </label>
															<div class=" col-lg-7 ">
																<input type="text" class="work_details form-control"
																	id="payment_mode_name" readonly="true" /> <select
																	class="form-control" id="payment_mode1"
																	name="payment_mode_id">
																	<option value="">Select Payment Mode</option>
                                                   <?php
																																																			$stmt = mysqli_prepare ( $conn, "SELECT payment_mode_id, payment_mode_name FROM company_payment_modes" );
																																																			$result = mysqli_stmt_execute ( $stmt );
																																																			mysqli_stmt_bind_result ( $stmt, $payment_mode_id, $payment_mode_name );
																																																			while ( mysqli_stmt_fetch ( $stmt ) ) {
																																																				echo "<option value='" . $payment_mode_id . "'>" . $payment_mode_name . "</option>";
																																																			}
																																																			mysqli_stmt_close ( $conn );
																																																			mysqli_close ( $conn );
																																																			?>    
                                              </select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Shifts</label>
															<div class=" col-lg-7 ">
																<input type="text" class="work_details form-control"
																	name="shift_id" id="shift_name" readonly="true" />
															</div>
														</div>


														<div class="form-group">
															<label class="col-lg-5 control-label"> WeekEnds </label>
															<div class=" col-lg-7 ">
																<input type="text" class="work_details form-control"
																	name="weekend_id" id="weekend_id" readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> EPF Number </label>
															<div class=" col-lg-7 ">
																<input type="text" class="work_details form-control"
																	name="employee_emp_pf_no" id="employee_emp_pf_no"
																	readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> UAN Number</label>
															<div class=" col-lg-7 ">
																<input type="text" class="work_details form-control"
																	name="employee_emp_uan_no" id="employee_emp_uan_no"
																	readonly="true" />
															</div>
														</div>

													</div>

													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-5 control-label"> ESI ID </label>
															<div class="col-lg-7 ">
																<input type="text" class="work_details form-control"
																	name="employee_emp_esi_no" id="employee_emp_esi_no"
																	readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> PF Limit </label>
															<div class="col-lg-7 ">
																<input type="text" id="pf_limit1" readonly="true" /> <label
																	class="checkbox-inline" style="display: none;"
																	id="pf_limit2"> <input name="pf_limit" id="limit"
																	value="1" checked="" type="checkbox">Apply PF Limit <br>(for
																	greater than Rs.15,000)
																</label>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Reporting Manager</label>
															<div class="col-lg-7">
																<input type="hidden" id="rep_man-id-e" name="rep_man-id">
																<input type="text" class="form-control" id="rep_man"
																	name="rep_man" autocomplete="off" /> <span
																	class="help-block"><span
																	class="pull-right empty-message" style="display: none">No
																		Records Found</span></span>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Offer Letter Issue</label>
															<div class=" col-md-7 col-xs-11 input-group">
																<span class=" input-group-addon spanCal2"><i
																	class="fa fa-calendar"></i></span> <input
																	class="work_details form-control" type="text"
																	name="off_ltr_issue_dt" id="off_ltr_issue_dt" required
																	readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Confirmation
																letter Issue</label>
															<div class=" col-md-7 col-xs-11 input-group">
																<span class=" input-group-addon spanCal2"><i
																	class="fa fa-calendar"></i></span> <input
																	class="work_details form-control" type="text"
																	name="confirm_ltr_issue_dt" id="confirm_ltr_issue_dt"
																	required readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label"> Contract letter
																Issue</label>
															<div class=" col-md-7 col-xs-11 input-group">
																<span class=" input-group-addon spanCal2"><i
																	class="fa fa-calendar"></i></span> <input
																	class="work_details form-control" type="text"
																	name="contract_ltr_issue_dt" id="contract_ltr_issue_dt"
																	required readonly="true" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Date of Join</label>
															<div class=" col-md-7 col-xs-11 input-group">
																<span class=" input-group-addon spanCal2"><i
																	class="fa fa-calendar"></i></span> <input
																	class="work_details form-control" type="text"
																	name="employee_doj" id="employee_doj" required
																	readonly="true" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-5 control-label">Probation Period</label>
															<div class="col-md-7 col-xs-11 input-group">
																<input class="work_details form-control" type="text"
																	name="employee_probation_period"
																	id="employee_probation_period" value="0" required /> <span
																	class=" input-group-addon" id="spanDay">days</span>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-5 control-label">Confirmation Date</label>
															<div class="col-md-7 col-xs-11 input-group">
																<span class=" input-group-addon spanCal2"><i
																	class="fa fa-calendar"></i></span> <input
																	class="work_details form-control" type="text"
																	name="employee_confirmation_date"
																	id="employee_confirmation_date" required
																	readonly="true" />
															</div>
														</div>

													</div>

												</div>
											</div>
										</form>
									</div>
									<!--Working Details Ends-->
					</div>
				</section>
			</aside>
		</div>
					<!-- page end-->
	</section>

				<!---page Ends-->
	</section>
	</section>
		<!--main content end-->
		<!--footer start--
		<?php include("footer.php"); ?>
      <!--footer end-->
</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>

	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/ImageTools.js"></script>
	<!--script for this page only-->
	<script src="../js/jquery.stepy.js"></script>
	<script type="text/javascript" src="../js/jquery.Jcrop.min.js"></script>
	<script src="../js/summarydata.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>

	<script src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
           $(document).ready(function () {
        	   removeHash();
         $('#dateofexithide,.profile_id').hide();
    	 $('#employee_nationality1,#job_status,#dept,#designation_id,#branch_loc,#payment_mode,#employee_blood_group1,#emp_sslc_board1,#emp_hsc_board1,#employee_id_proof_type1,#status_name1,#desig1,#dept1,#branch_loc1,#payment_mode1,#employee_bank_name1').chosen();
         $(".male,.female,.other,.matrial1,.matrial2,.m_l,.m_l1,.special,.able,.foreign,.domestic,#emp_ad,#emp_ad1,.image_change,#empIdPrefix0,.SpanRupee,#slabinEmp,#hide_label_address").hide();
         $('span#spanPeriod,span.spanCal,span.spanCal2,span#spanDay,span#spanDay1,span#spanDay2,span#empIdPrefix1,.empIdPrefix1_edu,.work_detail,.work_detail_head,.career,.attach,.attach_edu,.emp_up1,.emp_cancel1,.emp_up2,.emp_cancel2,.emp_up3,.emp_cancel3,.emp_up4,.emp_cancel4,.emp_up5,.emp_cancel5,.emp_up7,.emp_cancel7,.for_address,.work_history,.ctc_detail,.ctc_detail_head,.work_proof,#matrial_status1,#emp_able,#inter_emp,#emp_address,#employee_blood_group1_chosen,#employee_id_proof_type1_chosen,#employee_nationality1_chosen,#employee_bank_name1_chosen,#emp_designation_id,#emp_branch_id,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen,#status_name1_chosen,#desig1_chosen,#dept1_chosen,#branch_loc1_chosen,#payment_mode1_chosen,#mainBack').hide();
         $('.emp_edit1,.emp_edit2,.emp_edit3,.emp_edit4,.emp_edit5,.emp_edit7,.section.emp_list1').show();
		 $('.for_address,.career,.ctc_detail_head,.ctc_detail,.work_history,.work_detail_head,.work_detail,.work_proof').show();
		 
         
         $('#slab_change,#slap_name').hide();

        
         
         $("#emp_detail_form,#emp_address_form,#emp_edu_form,#emp_work_histroy_form,#emp_work_items").find(":input").not(".back_emp,.emp_edit3,.emp_edit1,.emp_edit2,.emp_edit4,.emp_edit5,.emp_edit7").each(function () {
             $(this).css({ 'background-color': '#FFF', 'border': '0px' });
             $(this).attr('disabled', true);
             $('.emp_edit5').css({ 'background-color': '#459626' });
             $('.emp_edit7').css({ 'background-color': '#459626' });
         });

       
         /* tab Clicks Starts*/
        $(function(){
     	 	// Javascript to enable link to tab
     	 	  var url = document.location.toString();
     	 	  var tab = "";
     	 	  if (url.match('#')) {
     	 		  if(url.split('#')[1].indexOf("?")> 0){
     	 	        	tab = url.split('#')[1].substring(0,url.split('#')[1].indexOf("?"));
     	 	        }else{
     	 	        	tab = url.split('#')[1];
     	 	        }
   	 	        
     	 	      $('#leave_account_tabs a[href="#' + tab + '"]').tab('show');
     	 	      window.scrollTo(0, 0);
     	 	  	
     	 	  }else{
     	 		  $('#leave_account_tabs a[href="#personals"]').tab('show');
     	 		  window.scrollTo(0, 0);
     	 		  } 

     	 	  // Change hash for page-reload
     	 	  $('#leave_account_tabs a').on('shown.bs.tab', function (e) {
     	 	      window.location.hash = e.target.hash;
     	 	      window.scrollTo(0, 0);
     	 	  })
     	 	});
 	 	
         /* tab Clicks Starts*/
         
         
     //Name validation only allow alphabets & space 
    $('#emp_mother_name,#spouse_name,#employee_bank_branch').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z\b t]*$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str) || (e.keyCode) == 9) {
            return true;
        }
        else
        {
        e.preventDefault();
        return false;
        }
    });

    //Number validation
    $('#employee_personal_mobile,#father_mobile,#mother_mobile,#spouse_mobile,#employee_acc_no').keypress(function (event) {
        var keycode = event.which;
        if (!(keycode == 0 || event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
            event.preventDefault();
            return false;
        }
    });

    $('#employee_personal_mobile,#mother_mobile,#father_mobile,#spouse_mobile').blur(function() {
        var mobNum = $(this).val();
        if(mobNum.length!=10){
            $(this).parent().find('.message').html('<label class="text-danger">Enter valid mobile number</label>');
        }else{    
        	$(this).parent().find('.message').empty();
        }
        }); 

    //do not allow special characters
    $('#employee_pan_no,#employee_bank_ifsc').keypress( function (event) {
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

         $('#leave_account_tabs').on('shown.bs.tab', function (e) {
       	   // newly activated tab 
       	 window.scrollTo(0, 0);
       	
       	  if($(e.target).data('loaded') === false){
       		employee_id="<?php echo $employee_id;?>";
       		$('#emp_id_now').val(employee_id);
      		    if($(e.target).data('title') === 'personal_tabs'){
      		    	 employee_fill(employee_id);
      		    	
      		    	$('#personal_tabs,#address_tabs,#career_tabs').data('loaded',true);
      		    }if($(e.target).data('title') === 'work_tabs'){
      		    	 work_tabs();
      		    	$('#work_detail_tabs').data('loaded',true);
      		    }
      		    if($(e.target).data('title') === 'salary_tabs'){
      		    	ctc_tabs();
      		    	
      		    }
      			}
      			//make the tab loaded true to prevent re-loading while clicking.
         		
       	$(e.target).data('loaded',true);
       	});
         /*tab clicks Ends*/
         $('.emp_edit1').click(function () {
        	 $('#preview_image_').empty();
             $("#employee_nationality1,#employee_blood_group1,#employee_bank_name1,#job_status,#employee_id_proof_type1,.profile_id").prop('disabled',false).trigger('chosen:updated');
             $('span#empIdPrefix1,span.spanCal,.profile_id').show();
             $(".matrial1,.matrial2,.m_l,.special,.able,.foreign,.domestic,#employee_id_proof_type1_chosen,#employee_blood_group1_chosen,#employee_bank_name1_chosen,#employee_nationality1_chosen,#designation_id_chosen,#department_id_chosen,#branch_id_chosen,.image_change,.emp_up1,.emp_cancel1,.attach,span#spanPeriod").show();
             $('.emp_edit1,#matrial_status1,#emp_able,#inter_emp,#emp_designation_id,#emp_branch_id,#employee_nationality,#employee_blood_group,#employee_id_proof_type,#employee_bank_name,#employee_marital_status,#employee_international,#employee_pc,.back_emp,.profileAl').hide();
             radioButtons();
             $('#emp_mother_mobile,#father_mobile').removeClass('hide');
             $('.mmobile-emp,.fmobile-emp').addClass('hide');
             
            $("#emp_detail_form").find(":input").not("#employee_id, #employee_name,#employee_dob,#employee_gender,#employee_lastname,#father_dob,#employee_mobile,#emp_personal_email,#employee_email,#employee_personal_email,#employee_father_name,#employee_aadhaar_name,#employee_aadhaar_id").each(function () {
                $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                $(this).attr('readonly', false);
                $(this).removeAttr('disabled');

            });

         });

         $('input[name=employee_marital_status]').click(function () { 
        	 if($("input[name=employee_marital_status]:checked").val()=="Single") {
            	 $('.spouse_name_hide').hide('slow');
        	 }else{
        		 $('.spouse_name_hide').show('slow');
            	 }
        	 });

         $('#emp_detail_form').on('submit', function (e) {
             e.preventDefault();
             //mobile number validation
             $.ajax({
                processData: false,
                contentType: false,
                type: "POST",
                url: "php/employee_update.php",
                cache: false,
                data: new FormData(this),
                beforeSend:function(){
                 	$('.emp_up1').button('loading'); 
                 	
                  },
                  complete:function(){
                 	 $('.emp_up1').button('reset');
                  },
                success: function (data) {
					  data1 = JSON.parse(data);
					if (data1[0] == "success") {
                        
                 	$("#employee_nationality1,#employee_blood_group1,#employee_bank_name1,#job_status,#employee_id_proof_type1,.profile_id").prop('disabled', true).trigger('chosen:updated');
                        $('span#empIdPrefix1,span.spanCal,.emp_up1,.emp_cancel1,.attach,.profile_id').hide();
                        $(".matrial1,.matrial2,.m_l,.special,.able,.foreign,.domestic,#employee_blood_group1_chosen,#employee_bank_name1_chosen,#employee_id_proof_type1_chosen,#employee_nationality1_chosen,.image_change").hide();
                        $('.emp_edit1,#matrial_status1,#emp_able,#inter_emp,#employee_nationality,#employee_blood_group,#employee_id_proof_type,#employee_bank_name,#employee_marital_status,#employee_international,#employee_pc,.back_emp,.profileAl').show();
                        /*radio button value starts*/
                        if ($("input[name=employee_international]:checked").val() == 1) {
                            $('#employee_international').val("Yes");
                        } else {
                            $('#employee_international').val("No");
                        }

                        if ($("input[name=employee_pc]:checked").val() == 1) {
                            $('#employee_pc').val("Yes");
                        } else {
                            $('#employee_pc').val("No");
                        }

                       
                       
                            if($("input[name=employee_marital_status]:checked").val()=="Single") {
                                $('#employee_marital_status').val('single');
                            } else {
                                $('#employee_marital_status').val('Married');
                                $('#spouse_name_hide').show();
                            }

                       
                        
                        $("#emp_detail_form").find(":input").not('.emp_edit1,.image_change,.back_emp').each(function () {
                            $(this).attr('readonly', true);
                            $(this).attr('disabled', true);
                            $(this).css({ 'background-color': '#FFF', 'border': '1px ' });
                        });
                        radioButtons1();
                        BootstrapDialog.alert(data1[1]);
	           } else
                        if (data1[0] == "error") {
                            alert(data1[1]);
                        }
                }

            });
        });
         
         $('.emp_cancel1').click(function () {
        	  $('.message').empty();
             $("#employee_nationality1,#employee_blood_group1,#employee_bank_name1,#job_status,#employee_id_proof_type1,.profile_id").prop('disabled', true).trigger('chosen:updated');
             $('span#empIdPrefix1,span.spanCal,.emp_up1,.emp_cancel1,.attach,.profile_id').hide();
             $(".matrial1,.matrial2,.m_l,.special,.able,.foreign,.domestic,#employee_blood_group1_chosen,#employee_bank_name1_chosen,#employee_id_proof_type1_chosen,#employee_nationality1_chosen,.image_change").hide();
             $('.emp_edit1,#matrial_status1,#emp_able,#inter_emp,#employee_nationality,#employee_blood_group,#employee_id_proof_type,#employee_marital_status,#employee_international,#employee_pc,.back_emp,.profileAl').show();
             $("#emp_detail_form").find(":input").not('.emp_edit1,.back_emp').each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });

             });
             radioButtons1();
             var employee_id = $('#emp_id_personal').val();
             employee_fill(employee_id);
         });
         
         function radioButtons() {
             $("input[name='employee_pt_adddress'],input[name='normal'],input[name='type_emp'],input[name='matrial_status'],input[name='emp_id_type'],input[name='esi_pattern']").each(function (i) {
                 $(this).removeAttr('disabled');
             });
         }
         function radioButtons1() {
             $("input[name='employee_pt_adddress'],input[name='normal'],input[name='type_emp'],input[name='matrial_status'],input[name='emp_id_type'],input[name='esi_pattern']").each(function (i) {
                 $(this).attr('disabled', 'disabled');
             });
         }
         $('.emp_edit2').click(function () {
             $(".emp_up2,.emp_cancel2,#emp_ad,#emp_ad1,.m_l1,#hide_label_address").show();
             $('.emp_edit2,#emp_address').hide();
             $("#emp_address_form").find(":input").not('.emp_edit2').each(function () {
                 $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                 $(this).attr('readonly', false);

                 $(this).removeAttr('disabled');
             });
             radioButtons();
             if ($('input[name=employee_pt_adddress]:checked').val() == 1) {
                 $('.per_add').prop('readonly', true);
             } else {
                 $('.per_add').prop('readonly', false);
             }


         });

         $('#emp_address_form').on('submit', function (e) {
             e.preventDefault();
             $.ajax({
                 processData: false,
                 contentType: false,
                 type: "POST",
                 url: "php/employee_update.php",
                 cache: false,
                 data: new FormData(this),
                 beforeSend:function(){
                   	$('.emp_up2').button('loading'); 
                    },
                    complete:function(){
                   	 $('.emp_up2').button('reset');
                    },
                 success: function (data) {
                     data1 = JSON.parse(data);
                     if (data1[0] == "success") {
                         $(".emp_up2,.emp_cancel2,#emp_ad,#emp_ad1,.m_l1,#hide_label_address").hide();
                         $('.emp_edit2,#emp_address').show();
                         $("#emp_address_form").find(":input").not('.emp_edit2').each(function () {
                             $(this).attr('readonly', true);
                             $(this).attr('disabled', true);
                             $(this).css({ 'background-color': '#FFF', 'border': '1px ' });
                         });
                         radioButtons1();
                         BootstrapDialog.alert(data1[1]);
                     }
                     else
                         if (data1[0] == "error") {
                             alert(data1[1]);
                         }
                 }

             });


         });



         $('.emp_cancel2').click(function () {

             $(".emp_up2,.emp_cancel2,#emp_ad,#emp_ad1,.m_l1,#hide_label_address").hide();
             $('.emp_edit2,#emp_address').show();
             $("#emp_address_form").find(":input").not('.emp_edit2').each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });

             });
             radioButtons1();
             var employee_id = $('#emp_id_address').val();
             employee_fill(employee_id);
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
                    $("#rep_man").val(ui.item.employee_name);
                     return false;
                 },
                 select: function (event, ui) {
                     $("#rep_man").val(ui.item.employee_name);
                     $("#rep_man-id").val(ui.item.employee_id);
                     $("#rep_man-id-e").val(ui.item.employee_id)
                     return false;
                 },
                 search: function () { $(this).addClass('spinner'); },
                 open: function () { $(this).removeClass('spinner'); },
                 change: function (event, ui) { if (ui.item == null) { $('#rep_man').val(""); $('#rep_man').val(""); } }
             })
                     .autocomplete("instance")._renderItem = function (ul, item) {
                         return $("<li>")
                          .append("<a>" + item.employee_name + " [" + item.employee_id + "] <br>" + item.employee_designation + "," + item.employee_department + ", " + item.employee_branch + "</a>")
                         .appendTo(ul);
                     };
         });
         $('#emp_edu_form').on('submit', function (e) {
             e.preventDefault();
             $.ajax({
                 processData: false,
                 contentType: false,
                 type: "POST",
                 url: "php/employee_update.php",
                 cache: false,
                 data: new FormData(this),
                 beforeSend:function(){
                    	$('.emp_up3').button('loading'); 
                     },
                     complete:function(){
                    	 $('.emp_up3').button('reset');
                     },
                 success: function (data) {
                    
				    data1 = JSON.parse(data);
                     if (data1[0] == "success") {
                         $("#emp_sslc_board1,#emp_hsc_board1").prop('disabled', true).trigger('chosen:updated');
                         $(".emp_up3,.emp_cancel3,.empIdPrefix1_edu,.attach_edu,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen").hide();
                         $('.emp_edit3,#emp_sslc_board,#emp_hsc_board').show();
                         $("#emp_edu_form").find(":input").not('.emp_edit3').each(function () {
                             $(this).attr('readonly', true);
                             $(this).attr('disabled', true);
                             $(this).css({ 'background-color': '#FFF', 'border': '1px ' });
                         });
                         BootstrapDialog.alert(data1[1]);
                     }
                     else
                         if (data1[0] == "error") {
                             alert(data1[1]);
                         }
                 }

             });


         });


         $('.emp_edit3').click(function () {
             $("#emp_sslc_board1,#emp_hsc_board1").prop('disabled', false).trigger('chosen:updated');
             $(".emp_up3,.emp_cancel3,.empIdPrefix1_edu,.attach_edu,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen").show();
             $('.emp_edit3,#emp_sslc_board,#emp_hsc_board').hide();
             $("#emp_edu_form").find(":input").not("#employee_id, .emp_edit3,#employee_name,#employee_dob,#employee_gender").each(function () {
                 $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                 $(this).attr('readonly', false);
                 $(this).removeAttr('disabled');

             });
         });

         $('.emp_cancel3').click(function () {
             $("#emp_sslc_board1,#emp_hsc_board1").prop('disabled', true).trigger('chosen:updated');
             $(".emp_up3,.emp_cancel3,.empIdPrefix1_edu,.attach_edu,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen").hide();
             $('.emp_edit3,#emp_sslc_board,#emp_hsc_board').show();
             $("#emp_edu_form").find(":input").not(' .emp_edit3').each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });

             });
             var employee_id = $('#emp_id_edu').val();
             employee_fill(employee_id);
         });
         
   //For work Histry
                $('#addCompany').click(function () {
                	var i=$('#addcompanyno').val();
                	$('.remove').removeClass('hide show');
                	$('.remove').removeClass('hide');
                	$('#'+i+'_addCompanyContent').append('<div class="row"><div class="col-lg-12"><h5><b>Company-'+i+'</b></h5><hr style="margin-top: 0px;"><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Company Name</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_cName" /></div></div><div class="form-group"><label class="col-lg-5 control-label">Reporting Manager</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_reporting_manager" id="1_reporting_manager" /></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Designation</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_desig"/></div></div><div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_Ctc"/></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Contact Email</label><div class="col-lg-7 "><input type="email" class="form-control" name="'+i+'_contact_email" /></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Location</label><div class="col-lg-7 "><input class="form-control" name="'+i+'_location"  type="text"></div></div></div><div class="col-lg-10"> <div class="form-group"><label class="control-label col-lg-3">Duration</label><div class="col-md-7"><div class="input-group input-large" data-date="13/07/2013" data-date-format="mm/dd/yyyy"><input class="form-control dpd1 period" name="'+i+'_From" type="text"><span class="input-group-addon" style="background-color: #fff; border: 0px">To</span><input class="form-control dpd2 period" name="'+i+'_To" type="text"></div></div></div></div></div></div>');
				    $('#'+i+'_addCompanyContent').parent().append('<div id='+(Number(i)+1)+'_addCompanyContent></div>');
                	$('#addcompanyno').val(Number(i)+1);
                });

                $('.remove').click(function () {
                	var i=$('#addcompanyno').val()-1;
                	$('#'+i+'_addCompanyContent').empty();
                	$('#'+i+i+'_addCompanyContent').empty();
                	$('.remove').removeClass('hide show');
                	$('#addcompanyno').val(i);
                	});

                $('body').on('focus',".period", function(){
               
               	 $(this).datetimepicker({
                 	    viewMode: 'years',
                 	    format: 'DD/MM/YYYY'
                     });
                });

                $('#emp_work_histroy_form').on('submit', function (e) {
                    e.preventDefault();
                     $.ajax({
                        processData: false,
                        contentType: false,
                        type: "POST",
                        url: "php/employee_update.php",
                        cache: false,
                        data: new FormData(this),
                        beforeSend:function(){
                           	$('.emp_up4').button('loading'); 
                            },
                            complete:function(){
                           	 $('.emp_up4').button('reset');
                            },
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                $(".emp_edit4,.emp_cancel4,.back_emp").show();
                                $('.emp_up4,.emp_cancel4').hide();
                                $("#emp_work_histroy_form").find(":input").not(".back_emp,.emp_edit4").each(function () {
                               	 $(this).css({ 'background-color': '#FFF', 'border': '1px ' });
                               	  $(this).attr('readonly', false);
                                    $(this).removeAttr('disabled');
                                  
                                });
                                BootstrapDialog.alert(data1[1]);
                            }
                            else
                                if (data1[0] == "error") {
                                    alert(data1[1]);
                                }
                        }

                    });


                });
                $('.emp_edit4').click(function () {
                    $('.exp').empty();
                   // $('.exp').removeClass('show');
                    $(".emp_up4,.emp_cancel4").show();
                    $('.remove').addClass('hide');
                    $('.emp_edit4,.back_emp').hide();
                    $("#emp_work_histroy_form").find(":input").each(function () {
                        $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                        $(this).attr('readonly', false);
                        $(this).removeAttr('disabled');
                    });
                });

                
                $('.emp_cancel4').click(function () {
               	 $(".emp_edit4,.emp_cancel4,.back_emp").show();
                    $('.emp_up4,.emp_cancel4').hide();
                    $("#emp_work_histroy_form").find(":input").not(".emp_edit4,.back_emp").each(function () {
                        $(this).attr('readonly', true);
                        $(this).attr('disabled', true);
                        $(this).css({ 'background-color': '#FFF', 'border': '0px' });

                    });
                    var employee_id = $('#emp_id_personal').val();
                    employee_fill(employee_id);
                    work_tabs();
                });
                //End Of Work histroy
        


         $("a.view").click(function (e) {
         var imag = $(this).find("input").val();
         if(imag!=='Nil' && imag!=='' ){
         		$(this).prop( "disabled", false);
         		$('#preview_image_model').attr('src', imag);
         }else{
        	 $(this).prop( "disabled", true);
             }
         });

         
         $(".image_change").change(function () {
	         var element = this;
	         var name = $(this).attr("name");
	         ImageTools.resize(this.files[0], {
	         width: 672, // maximum width
	         height: 1024 // maximum height
	         },
	         function (blob, didItResize) {
	         $(element).parent().parent().parent().find("a").find("input").val(window.URL.createObjectURL(blob));
	         });


         });

         $(".image_change_edu").change(function () {
         var element = this;
         var name = $(this).attr("name");
         ImageTools.resize(this.files[0], {
         width: 672, // maximum width
         height: 1024 // maximum height
         },
         function (blob, didItResize) {
         $(element).parent().parent().parent().find("a").find("input").val(window.URL.createObjectURL(blob));
         });


         });

        
         $(".special,.able").change(function () {
             $('#employee_pc').val($(this).val());
         });
         $(".foreign,.domestic").change(function () {
             $('#employee_international').val($(this).val());
         });
         $("#emp_ad,#emp_ad1").change(function () {
             $('#emp_address').val($(this).val());
         });


         /*radio button value Ends*/
         /*Chosen Box Functionalities starts*/
         $("#employee_blood_group1").change(function () {
             $('#employee_blood_group').val($("#employee_blood_group1 option:selected").text());
         });
         $("#employee_bank_name1").change(function () {
             $('#employee_bank_name').val($("#employee_bank_name1 option:selected").text());
         });
         $("#employee_id_proof_type1").change(function () {
             $('#employee_id_proof_type').val($("#employee_id_proof_type1 option:selected").text());
         });
         $("#emp_sslc_board1").change(function () {
             $('#emp_sslc_board').val($("#emp_sslc_board1 option:selected").text());
         });
         $("#emp_hsc_board1").on('blur change', function () {
             $('#emp_hsc_board').val($("#emp_hsc_board1 option:selected").text());
         });
         $("#emp_sslc_board1").on('blur change', function () {
             $('#emp_hsc_board').val($("#emp_sslc_board1 option:selected").text());
         });
         $("#emp_sslc_board1").on("blur change", function () {
             $("#emp_hsc_board1").val($(this).find("option:selected").attr("value")).trigger('chosen:updated');
         });
         $("#employee_nationality1").change(function () {
             $('#employee_nationality').val($("#employee_nationality1 option:selected").text());
         });
   		 $("#status_name1").on("blur change", function () {
        	$('#status_name').val($("#status_name1 option:selected").text()).trigger('chosen:updated');
       	});
         $("#desig1").on("blur change", function () {
             $('#designation_id1').val($("#desig1 option:selected").text()).trigger('chosen:updated');
         });
         $("#dept1").on("blur change", function () {
             $('#department_name').val($("#dept1 option:selected").text()).trigger('chosen:updated');
         });
         $("#branch_loc1").on("blur change", function () {
             $('#branch_name').val($("#branch_loc1 option:selected").text()).trigger('chosen:updated');
         });
         $("#payment_mode1").on("blur change", function () {
             $('#payment_mode_name').val($("#payment_mode1 option:selected").text()).trigger('chosen:updated');
         });
         /*Chosen Box Functionalities Ends*/
         /*DatePicker Functions starts*/


          $('#employee_dob,#dob,#emp_id_date,#employee_marriagedate,#father_dob,#mother_dob,#spouse_dob').datetimepicker({  format: 'DD/MM/YYYY' });
         
         $('#employee_dob').datetimepicker().on("blur changeDate", function (e) {
             date = new Date(e.date);
             date.setMonth(date.getMonth() + 180);
             $(".period1").datetimepicker('setDate', date);
         });
         $('#employee_dob').datetimepicker().on("blur changeDate", function (e) {
             date = new Date(e.date);
             date.setMonth(date.getMonth() + 204);
             $(".period2").datetimepicker('setDate', date);

         });
         $('#employee_dob').datetimepicker().on("blur changeDate", function (e) {
             date = new Date(e.date);
             date.setMonth(date.getMonth() + 246);
             $(".period3").datetimepicker('setDate', date);
         });
         $('#employee_dob').datetimepicker().on("blur changeDate", function (e) {
             date = new Date(e.date);
             date.setMonth(date.getMonth() + 270);
             $(".period4").datetimepicker('setDate', date);
         });
       
         $(".period1,.period2,.period3,.period4").datetimepicker({
        	  format: 'YYYY',
              viewMode: "years",
              minDate: moment("2000","YYYY"),
        });

         $('#employee_id_proof_expiry').datetimepicker({
             format : 'DD/MM/YYYY',
             maxDate: false
             });

         $(".period").datetimepicker({   viewMode: 'years',format: 'MM YYYY',
               });
         
         $('#doj').datetimepicker({ format: 'DD/MM/YYYY'}).on("changeDate", function (e) {
             e.preventDefault();
             var date = new Date(e.date);
             date.setDate(date.getDate() + parseInt($('#probation_period').val()));
             $("#confirmation_date").datetimepicker('update', date);
         });
         $('#probation_period').on('blur', function () {
             var date = $('#doj').val().split('/');
             date = new Date(date[2], date[1] - 1, date[0]);
             date.setDate(date.getDate() + parseInt($('#probation_period').val()) + 1);
             $("#confirmation_date").datetimepicker('update', date);
             
         });


        
         $('#employee_probation_period').on('blur', function () {
             var date = $('#employee_doj').val().split('/');
             date = new Date(date[2], date[1] - 1, date[0]);
             date.setDate(date.getDate() + parseInt($('#employee_probation_period').val()) + 1);
          });
        
     $('input[name="matrial_status"]').on('click', function () {
         if ($(this).val() == 'Single') {
             $("#employee_spouse_name").attr("disabled", true);

         }
         else {
             $('#employee_spouse_name').css('opacity', 1.0).val('');
             $("#employee_spouse_name").attr("disabled", false);
         }
     });
     /**********************************ADDRESS FUNCTION STARTS *****************************************/
     /*Address radio Button Function Starts*/
     $('input[name="employee_pt_adddress"]').on('click', function () {
         if ($(this).val() == '1') {
             $("#permanent_emp_bulidname").val($("#employee_build_name").val());
             $("#permanent_emp_area").val($("#employee_area").val());
             $("#permanent_emp_city").val($("#employee_city").val());
             $("#permanent_emp_pincode").val($("#employee_pin_code").val());
             $("#permanent_emp_state").val($("#employee_state").val());
             $("#permanent_emp_country").val($("#emp_country").val());
             $(".per_add").attr("readonly", true);
         }
         else {
             $("#permanent_emp_bulidname,#permanent_emp_area,#permanent_emp_city,#permanent_emp_pincode,#permanent_emp_state,#permanent_emp_country").val('');
             $(".per_add").attr("readonly", false);
         }
     });
     /*Address radio Button Function Ends*/
     /* Load City State by Pincode for Temporary address*/
     $('#employee_pin_code').on('blur', function () {
         if ($(this).val().length == 6) {
             commonFunctions.getCityByPinCode($(this), $(this).val(), function (result) {
                 if (result.length > 0) {
                     $('#employee_city').val(result[0].districtname);
                     $('#employee_state').val(result[0].statename);

                 }
             });
         }
     });
     /* Load City State by Pincode for Permanent address*/
     $('#permanent_emp_pincode').on('blur', function () {
         if ($(this).val().length == 6) {
             commonFunctions.getCityByPinCode($(this), $(this).val(), function (result) {
                 if (result.length > 0) {
                     $('#permanent_emp_city').val(result[0].districtname);
                     $('#permanent_emp_state').val(result[0].statename);

                 }
             });
         }
     });
    
     /*************************************image process Ends**************************************/

     $('#emp_profile_view').submit(function (e) {
         $('section.emp_image_view').show();
         e.preventDefault();
     });

  
           function employee_fill(employee_id) {
               $('.personal_detail_loader,.ul_loader').loading(true);
        	   $.ajax({
                   dataType: 'html',
                   type: "POST",
                   url: "php/employee.handle.php",
                   cache: false,
                   data: { 'act':'<?php echo base64_encode($_SESSION['company_id']."!getEmployeePersonelDetails");?>','employee_id': employee_id},
                   beforeSend: function () {
                  	 $(".loader").loading(true);
                   },
                   complete: function () {
                  	$(".loader").loading(false);
                   },
                   success: function (data) {
                     $('.personal_detail_loader,.ul_loader').loading(false);
                  	 $("#mainBack").show();
                       var json_obj = $.parseJSON(data); //parse JSON
                      
                     $("#employee_id").val(json_obj[2][0].employee_id);
                      // $("#employee_sub").val(json_obj[2][0].employee_id);

                      /*for fill the employee personal details*/
                     
                     // if(json_obj[2][0].employee_bank_name)
                     if(json_obj[2][0].employee_bank_name)
                       $("#employee_bank_name1 option[value='" + json_obj[2][0].employee_bank_name+ "']").prop("selected","selected").trigger('chosen:updated');
                       $("#employee_blood_group1 option[value='" + json_obj[2][0].employee_blood_group + "']").prop("selected", "selected").trigger('chosen:updated');
                       $("#employee_nationality1 option[value='" + json_obj[2][0].employee_nationality + "']").prop("selected", "selected").trigger('chosen:updated');
                       $("#employee_id_proof_type1 option[value='" + json_obj[2][0].employee_id_proof_type + "']").prop("selected", "selected").trigger('chosen:updated');

                       /*for fill the Edu- career*/
					
                       $("#emp_sslc_board1 option[value='" + json_obj[2][0].emp_sslc_board + "']").prop("selected", "selected").trigger('chosen:updated');
                       $("#emp_hsc_board1 option[value='" + json_obj[2][0].emp_hsc_board + "']").prop("selected", "selected").trigger('chosen:updated');
                      
                       $('#emp_offer_letter_proof').prop('src', '../compDat/' + json_obj[2][0].emp_offer_letter_proof);
                       if(json_obj[2][0].employee_image=='Nil')
  						{
                      	 if(json_obj[2][0].employee_gender=='Male'){
                          	  $('#round_preview_image').prop('src', '../img/default-avatar_men.png');
                           }else{
                          		 $('#round_preview_image').prop('src', '../img/default-avatar_women.png');
                              	 } 	 
  						}else{
  						 $('#preview_image_').prop('src',json_obj[2][0].employee_image);
  					 var c=document.getElementById("preview_image");
  						    //var ctx=c.getContext("2d");
  						    var img=document.getElementById("preview_image_");
  						   // ctx.drawImage(img,10,10);
  						 $('#round_preview_image').prop('src',json_obj[2][0].employee_image);
  					  }

                      
                       if(json_obj[2][0].father_mobile ==""){
                        // console.log($('#father_mobile').attr('-moz-appearance', 'textfield'));
                           $('#father_mobile').addClass('hide');
                           $('.fmobile-emp').removeClass('hide');
                           }else{
                          	 $('#father_mobile').html(json_obj[2][0].father_mobile);
                               }
                       

                       if(json_obj[2][0].mother_mobile==""){
                      	 $('#emp_mother_mobile').addClass('hide');
                           $('.mmobile-emp').removeClass('hide');
                           }else{
                         	    $('#emp_mother_mobile').html(json_obj[2][0].mother_mobile);
                               }
                       

                  	 if(json_obj[2][0].employee_gender=='Male'){
                  		        $("input[name=employee_gender][value=" + json_obj[2][0].employee_gender + "]").prop('checked', true);
                                  $('#gender1').click(); 
  								}else if(json_obj[2][0].employee_gender=='Female'){
  							    $("input[name=employee_gender][value=" + json_obj[2][0].employee_gender + "]").prop('checked', true);
  			                    $('#gender2').click(); 
  		                   		} else{
  		                   		$("input[name=employee_gender][value=" + json_obj[2][0].employee_gender + "]").prop('checked', true);
  		                        $('#gender3').click(); 
  			                    }
                     
                 
                       if (json_obj[2][0].employee_marital_status == 'single') {
                           $("input[name=employee_marital_status][value=" + json_obj[2][0].employee_marital_status + "]").prop('checked', true);
                          // $('#employee_marital_status1').click();
                        } else {
                           $('#employee_spouse_name').val(json_obj[2][0].employee_spouse_name);
                           $("input[name=employee_marital_status][value=" + json_obj[2][0].employee_marital_status + "]").prop('checked', true);
                          // $('#employee_marital_status2').click();
                       }
                       if (json_obj[2][0].employee_pt_adddress == '1') {
                           $("input[name=employee_pt_adddress][value=" + json_obj[2][0].employee_pt_adddress + "]").prop('checked', true);
                       } else {
                           $("input[name=employee_pt_adddress][value=" + json_obj[2][0].employee_pt_adddress + "]").prop('checked', true);
                           $(".per_add").prop("readonly", false);

                       }
                       if (json_obj[2][0].employee_international == '1') {
                           $("input[name=employee_international][value=" + json_obj[2][0].employee_international + "]").prop('checked', true);
                       } else {
                           $("input[name=employee_international][value=" + json_obj[2][0].employee_international + "]").prop('checked', true);

                       }
                      
                      
                      
                       $("input[name=employee_pc][value=" + json_obj[2][0].employee_pc + "]").prop('checked', true);
                       // $("#storeInformation").html(details.d);
                       

                       
                       if (json_obj[2][0].id !== null) {
                           //payroll Runned
                           $('#payrollCheck').val(1);
                           $('.emp_edit7').hide();
                      } else {
                      	$('#payrollCheck').val(0);
                           $('.emp_edit7').show();
                           $('.emp_edit5').show();
                       }
                       

                       if (json_obj[2][0].dateofexit !== '0000-00-00') {
                           $('#dateofexithide').show();
                       }else{
                           $('#dateofexithide').hide();
                       }
 
                       $.each(json_obj[2][0], function (k, v) {
                           //display the key and value pair
                            if(k=='employee_pan_proof' || k=='employee_aadhaar_proof' || k=='employee_bank_proof'
                              	 || k=='employee_id_proof'|| k=='employee_sslc_proof' || k=='employee_ug_proof' 
                                  	 || k=='employee_hsc_proof' || k=='employee_pg_proof'){
                              $('#' + k).val(v);
                            if($('#'+k).val()!=='Nil' && $('#'+k).val()!==''){
                          	   	  $('#'+k).parent().removeClass('btn-danger');
                          	   	 $('#'+k).parent().addClass('btn-primary');
                          	   	  }else{
                          	   		  $('#'+k).parent().removeClass('btn-danger btn-primary');
                               	   	 $('#'+k).parent().addClass('btn-danger');
                               	   	 }
                           }
                            $('#'+k).val(v);
                       });
                       


                       if (json_obj[2][0].employee_international == 1) {
                           $('#employee_international').val("Yes");
                       } else {
                           $('#employee_international').val("No");
                       }

                       if (json_obj[2][0].employee_pc == 1) {
                           $('#employee_pc').val("Yes");
                       } else {
                           $('#employee_pc').val("No");
                       }
                     

                   }

               });
           }

         //form employee Crop
           $('#upload_form').on('submit', function (e) {
          	   e.preventDefault();
          	 if (parseInt($('#w').val())){
                    $.ajax({
                         processData: false,
                         contentType: false,
                         type: "POST",
                         url: "php/employee_update.php",
                         cache: false,
                         data: new FormData(this),
                          beforeSend:function(){
                         	$('#btnCrop').button('loading'); 
                           },
                           complete:function(){
                          	 $('#btnCrop').button('reset');
                           },
                         success: function (data) {
                        	 data1 = JSON.parse(data);
                             if(data1!=='error'){
                             	$('#preview_image_').attr('src','');
                             	$('#preview_image_').attr('src',data1[0]+"?"+(new Date()).getTime());
                             	$('.emp_up1').click();
                             	$('#imageName').val(data1);
                             	$('.close').click();
                             	$("#round_preview_image").attr("src",data1+"?"+(new Date()).getTime());
                             }else{
                            	
                                 }
                         	}
                     });
          	 }else{
          		$('.error').html('Please select a crop region and then press Upload').show();
                return false;
              	 }      	     
           });
           function  work_tabs(){
               var employee_id =  $('#emp_id_now').val();
               $('.emp_new').val(employee_id);
               $('.work_detail_loader,.ul_loader').loading(true);
          	 $.ajax({
                   dataType: 'html',
                   type: "POST",
                   url: "php/employee.handle.php",
                   cache: false,
                   data: { 'employeeId':employee_id,'act':'<?php echo base64_encode($_SESSION['company_id']."!workDetails");?>'},
                   success: function (data) {
                  	 var json_obj = $.parseJSON(data);
                  	 $('.work_detail_loader,.ul_loader').loading(false);
                       for(var i=0;i<json_obj[2].length;i++){
                      	 if(json_obj[2][i].payment_mode_id){
                               $("#payment_mode1 option[value=" + json_obj[2][i].payment_mode_id + "]").prop("selected", "selected").trigger('chosen:updated');
                             }
                          $('#designation_id1').attr('title','Effects From '+json_obj[2][i].design_effects_from);
                          $('#department_name').attr('title','Effects From '+json_obj[2][i].depart_effects_from);
                          $('#branch_name').attr('title','Effects From '+json_obj[2][i].branch_effects_from);
                      	  $("#status_name1 option[value=" + json_obj[2][i].status_id + "]").prop("selected", "selected").trigger('chosen:updated');
                           $("#desig1 option[value=" + json_obj[2][i].designation_id + "]").prop("selected", "selected").trigger('chosen:updated');
                           $("#dept1 option[value=" + json_obj[2][i].department_id + "]").prop("selected", "selected").trigger('chosen:updated');
                           $("#branch_loc1 option[value=" + json_obj[2][i].branch_id + "]").prop("selected", "selected").trigger('chosen:updated');
                           $(".table1 tbody").empty();
                           s = 1;
                           if (json_obj[2][i].off_ltr_issue_dt != 'Nil') {
                               $(".table1 tbody").append("<tr style='text-align:center'><td>" + s + "." + "</td><td>" + json_obj[2][i].off_ltr_issue_dt + "</td><td>" + json_obj[2][i].employee_doj + "</td><td>Offer Letter</td><td><div class='col-lg-6'><a class='btn btn-success view1' data-toggle='modal' href='#myModal' style='background-color: #41CAC0;border-color: #41CAC0;'><input type='hidden' class='form-control' id='' /><i class='fa fa-eye'></i></a></div><div class='col-lg-6'><a style='background-color: red; border-color: red;' type='button' class='btn btn-info delete'><i class='fa fa-trash-o'></i></a></div></td></tr></tr>");
                               s++;
                           }
                           if (json_obj[2][i].confirm_ltr_issue_dt != 'Nil') {
                               $(".table1 tbody").append("<tr style='text-align:center'><td>" + s + "." + "</td><td>" + json_obj[2][i].confirm_ltr_issue_dt + "</td><td>" + json_obj[2][i].employee_confirmation_date + "</td><td>Confirmation Letter</td><td><div class='col-lg-6'><a class='btn btn-success view1' data-toggle='modal' href='#myModal' style='background-color: #41CAC0;border-color: #41CAC0;'><input type='hidden' class='form-control' id='' /><i class='fa fa-eye'></i></a></div><div class='col-lg-6'><a style='background-color: red; border-color: red;' type='button' class='btn btn-info delete'><i class='fa fa-trash-o'></i></a></div></td></tr></tr>");
                               s++;
                           }
                           if (json_obj[2][i].contract_ltr_issue_dt != 'Nil') {
                               $(".table1 tbody").append("<tr style='text-align:center'><td>" + s + "." + "</td><td>" + json_obj[2][i].contract_ltr_issue_dt + "</td><td>" + json_obj[2][i].employee_doj + "</td><td>Contract Letter</td><td><div class='col-lg-6'><a class='btn btn-success view1' data-toggle='modal' href='#myModal' style='background-color: #41CAC0;border-color: #41CAC0;'><input type='hidden' class='form-control' id='' /><i class='fa fa-eye'></i></a></div><div class='col-lg-6'><a style='background-color: red; border-color: red;' type='button' class='btn btn-info delete'><i class='fa fa-trash-o'></i></a></div></td></tr></tr>");
                               s++;
                           }
                           
                           var pf_limt = json_obj[2][i].pf_limit==1?$('#pf_limit1').val('YES'):$('#pf_limit1').val('NO');
                           var pf_limt1 = json_obj[2][i].pf_limit==1?$("#limit").prop('checked', true):$("#limit").prop('checked', false);
                               $("#designation_id1").val(json_obj[2][i].designation_name);
                               $('#rep_man-id-e').val(json_obj[2][i].employee_reporting_person);                
                               $('#rep_man').val(json_obj[2][i].reporting_manager);
                        $.each(json_obj[2][i], function (k, v) {                
                        	 $('#' + k).val(v);
                       });
                        $('.remove').removeClass('hide show');
                       	$('.remove').addClass('hide');
                           if(json_obj[2][i].company_name!==null){
                            $('.delete').empty();
                            var cName=json_obj[2][i].company_name.split(","); 
                            var To=json_obj[2][i].to.split(","); 
                            var From=json_obj[2][i].from.split(",");
                            if(json_obj[2][i].designation!=null){ 
                            		var designation=json_obj[2][i].designation.split(","); 
                           	  }else{
                           		 var designation='-';
                                }
                            
                            var ctc=json_obj[2][i].ctc.split(",");
                            
                            if(json_obj[2][i].prev_reporting_manager!=null){
                           		var rep_manager = json_obj[2][i].prev_reporting_manager.split(","); 
                           	  }else{
                        	  		 var rep_manager = '-';
                               }
                            
   						if(json_obj[2][i].location!=null){
                            var location = json_obj[2][i].location.split(","); 
   						   }else{
   							var location = '-';
   							}
   						
                         if(json_obj[2][i].contact_email!=null){
                           	 var con_email = json_obj[2][i].contact_email.split(","); 
                         	}else{
                      	   	   	var con_email = '-';
                             }

         				 var  j=0;     
      					 $('#addcompanyno').val(json_obj[2][i].company_name.split(",").length+1);
      					 $('.remove').addClass('show');
      					for (var i=1; i<= cName.length;i++){
      						$('#'+i+'_addCompanyContent').append('<div class="row"><div class="col-lg-12"><h5><b>Company-'+i+'</b></h5><hr style="margin-top: 0px;"><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Company Name</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_cName" value="'+cName[j]+'" style="background-color:#FFF;border:0px"  disabled /></div></div><div class="form-group"><label class="col-lg-5 control-label">Reporting Manager</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_reporting_manager" value="'+rep_manager[j]+'" style="background-color:#FFF;border:0px"  disabled /></div></div>	</div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Designation</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_desig"  value="'+designation[j]+'" style="background-color:#FFF;border:0px"  disabled/></div></div><div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_Ctc"  value="'+ctc[j]+'" style="background-color:#FFF;border:0px"  disabled /></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Contact Email</label><div class="col-lg-7 "><input type="email" class="form-control" name="'+i+'_contact_email"  value="'+con_email[j]+'" style="background-color:#FFF;border:0px"  disabled/></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Location</label><div class="col-lg-7 "><input class="form-control" name="'+i+'_location" value="'+location[j]+'" style="background-color:#FFF;border:0px"  disabled ></div></div></div><div class="col-lg-10"> <div class="form-group"><label class="control-label col-lg-3">Duration</label><div class="col-md-7"><div class="input-group input-large" data-date="13/07/2013" data-date-format="mm/dd/yyyy"><input class="form-control dpd1 period" name="'+i+'_From"  value="'+From[j]+'" style="background-color: #fff; border: 0px" disabled type="text"><span class="input-group-addon" style="background-color: #fff; border: 0px" disabled>To</span><input class="form-control dpd2 period" value="'+To[j]+'" name="'+i+'_To" style="background-color: #fff; border: 0px" disabled type="text"></div></div></div></div></div></div>');
      					 	$('#'+i+'_addCompanyContent').parent().append('<div id='+(Number(i)+1)+'_addCompanyContent class="delete"></div>');
      					j++;
      					}}else{
      						$('.exp').html('<span> No work experience found.</span>');
      					$('.remove').addClass('hide');
      					$('#addcompanyno').val('1');$('.delete').empty();
      					}
      				}
                   }
          	 });
          	}


           
       	
           function ctc_tabs(){
       		var employeeId='<?php echo isset($_REQUEST['employeeID'])?>';
       		if(employeeId != ''){
       		  employee_id = employeeId;
       		}else{
       		  employee_id = $('#emp_id_now').val();
       		  
       	   }
        	   $('.ctc_detail_loader,.ul_loader').loading(true);
           	 $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data: { 'employeeId':employee_id,'act':'<?php echo base64_encode($_SESSION['company_id']."!salary_details");?>'},
                    success: function (data) {
                   	 var json_obj = $.parseJSON(data);
                   	 //console.log(data);
                   	 	 $('#effect_sal_from').html(' Wef- '+json_obj[2][0].effects_from+' ');
                   	 	(json_obj[2][0].pf_limit==1)?$('#pf_limit').prop('checked', true):
                   	 		$('#pf_limit').prop('checked', false);
                       	 (json_obj[2][0].salary_type=='ctc')? 
                       	 $("input[name=salary_type][value='ctc']").prop('checked', true): 
                           	 $("input[name=salary_type][value='monthly']").prop('checked', true);
                       	 $('#slabTypedata').val('No Slab');
                        	
                       	 if (json_obj[2][0].slab_id == "Nil") {
                           	 $("input[name=salary_based_on][value='noslab']").click();
                                $("input[name=salary_based_on][value='noslab']").prop('checked', true);
                                setTimeslabIntialize(json_obj[2][0]);
                                } else {
                                  $('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable"><thead><tr class="headerReload"><th class="emptyDiv">Components</th><th>Rate</th><th style="text-align:right">Monthly</th><th style="text-align:right">Yearly</th></tr></thead>'+
                              		   setData(json_obj[2][1]));
                                 if (json_obj[2][0].slab_type == 'basic') {
                               	  $('#slabTypedata').val('Basic');
                           		 $("input[name=salary_based_on][value='basic']").click();
                           		 }else if( json_obj[2][0].slab_type == 'gross'){
                           		  $('#slabTypedata').val('Gross');
                           		  $("input[name=salary_based_on][value='gross']").click();
                           		 }
                                 setTimeslabIntialize(json_obj[2][0]);
                                    }
                         $('.emp_edit7').hide();   
                       	  }
                       
                 });
       	}
          	$(document).on('change', "input[type=radio][name=salary_type],input[type=radio][name=salary_based_on]", function () {
          	 	var salaryType=$("input[type=radio][name=salary_type]:checked").val();
          	     var slabType=$("input[type=radio][name=salary_based_on]:checked").val();
          	     $('#getCTCcontent').html('');
          	     if(salaryType =='ctc'){
          	        var  mischtml='';
          	    	 var miscAllow = <?php echo json_encode($miscAlloDeduArray['MP']) ?>;
          	    	 for (i = 0; i < miscAllow.length; i++) {
          	    		 mischtml+='<div class="form-group"><label class="col-lg-5 control-label">'+miscAllow[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control miscAlowDeduCtc" id="'+miscAllow[i].pay_structure_id+'"  name="allowances['+miscAllow[i].pay_structure_id+']"  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div>';
          	         }
          	         }
          	      if(salaryType =='ctc' && slabType =='gross'){
          	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text"  id="Subctc" name="ctc" oninput="reFormate(this)" data-type="rupee" class="salaryValidate form-control" value="0"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;border-bottom: 0px solid #e5e5e5;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" data-type="rupee" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text" name="ctc_fixed_component"  data-type="rupee" oninput="reFormate(this)" id="ctc" class="form-control salaryValidate" value="0"/></div></div>';
          	     $('#getCTCcontent').html(varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label>');
          	     }else if(salaryType =='ctc' && slabType =='basic'){
          	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="text"  id="Subctc" name="ctc" data-type="rupee" class="form-control salaryValidate" value="0" oninput="reFormate(this)"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;border-bottom: 0px solid #e5e5e5;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" name="ctc_fixed_component" data-type="rupee" oninput="reFormate(this)" autocomplete="off" value="0" readonly type="text"></div></div></div><div class="form-group"><label class="col-lg-5 control-label">Basic (Monthly)</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" name="basic" id="basic" data-type="rupee" oninput="reFormate(this)"  class="form-control salaryValidate" value="0"/></div></div></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="text" name="ctc_fixed_component" id="ctc" class="form-control salaryValidate" data-type="rupee" oninput="reFormate(this)"  value="0"/></div></div></div><div class="form-group"><label class="col-lg-5 control-label">Basic</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" name="basic" id="basic" class="form-control salaryValidate"   data-type="rupee" oninput="reFormate(this)" value="0"/></div></div>';
          	     $('#getCTCcontent').html(varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label></div>');
          	     }else if(salaryType =='monthly' && slabType =='gross'){
          	     $('#getCTCcontent').html('<div class="form-group"><label class="col-lg-5 control-label">Gross</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="ctc_fixed_component" data-type="rupee" id="ctc" value="0"/><input type="hidden" name="basic" id="basic" value="0"/><input type="text" name="gross" id="gross" class="form-control salaryValidate" value="0"  data-type="rupee" oninput="reFormate(this)" /></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc">Calculate</button><label class="help-block text-danger text" id="error-text"></label></div></div>');
          	     }else if(salaryType =='monthly' && slabType =='basic'){
          	     $('#getCTCcontent').html('<div class="form-group"><label class="col-lg-5 control-label">Basic</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross"  value="0"/><input type="hidden" name="ctc_fixed_component"  data-type="rupee" id="ctc" value="0"/><input type="text" name="basic"  data-type="rupee" oninput="reFormate(this)"  id="basic" class="form-control salaryValidate" value="0"/></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc">Calculate</button><label class="help-block text-danger text" id="error-text"></label></div></div>');
          	     }else if(slabType =='noslab'){
          	     var ar = <?php echo json_encode($allowDeducArray['A']);?>;
          	     $('#getCTCcontent,#getTablecontent').html('');
          	     var html='';var inputFormDynami='';
          	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="Subctc"  name="ctc" data-type="rupee" oninput="reFormate(this)"  autocomplete="off" value="0" required/></div></div><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;border-bottom: 0px solid #e5e5e5;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'';
          	     html+=(salaryType =='ctc')?varaibleComponents+'</div></div>':'';
          	     html+='<div class="form-group"><label class="col-lg-5 control-label">Gross Salary</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="gross"  data-type="rupee" name="gross"  oninput="reFormate(this)" autocomplete="off" value="0" readonly required/></div></div></div></div>';
          	     for (i = 0; i < ar.length; i++) {
          	    	inputFormDynami+=ar[i].pay_structure_id+',';
          	    	html+='<div class="form-group"><label class="col-lg-5 control-label">'+ar[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id='+ar[i].pay_structure_id+'  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div></div>';
          	     }
          	     html+='<br><button class="btn btn-default pull-right"  data-id="'+inputFormDynami+'" type="button" id="noSlabCaulation">Calculate</button>';
          	     $('#getCTCcontent').html(html);
          	     eventForNoSlab();
          	     }else{
          	    	 $('#getCTCcontent,#getTablecontent').html('');
          	     }
          	  });

           $(document).on('click', "#noSlabCaulation", function (e) {
               e.preventDefault();
               var parameters = {}; //declare the object
               parameters["act"] = '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>'; //set some value
               parameters["pfLimit"] = ($("input[type=checkbox][name=pf_limit]:checked").val()==1)?1:0;
               parameters["isCTC"] =($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
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
               var ctc=deFormate($('#ctc').val());
               var gross=deFormate($('#gross').val());
               var basic=deFormate($('#basic').val());
               var slabId=$('#slabinEmp').val();
               var pfLimit=($("input[type=checkbox][name=pf_limit]:checked").val()==1)?1:0;
               var isCTC=($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
               var checkIfexit=ctc+gross+basic+slabId+pfLimit+isCTC;
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
                            ctc:ctc,gross:gross,basic:basic,slabId:slabId,pfLimit:pfLimit,isCTC:isCTC},
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
           });
         
      </script>
</body>
</html>