<?php
include_once (dirname ( __DIR__ ) . "/include/config.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/session.class.php");
Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
$allowColums = "";
foreach ( $allowDeducArray ['A'] as $key ) {
	$allowColums .= $key ['pay_structure_id'] . ",";
}
Session::newInstance ()->_setMiscPayParams ();
$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
?>
<style>
		.btn-primary.disabled, .btn-primary[disabled]{
				background-color: #00A8B3 !important;
				border-color: #00A8B3 !important;
			}
			@media (min-width: 768px) {
	     	.modal-sm{
		 	 width: 400px;
		     }
           }
			
		.close{
			margin-top: -22px !important;
			}
		.fp{
		padding: 5px !important;
		border: 1px solid;
		margin-right: 5px;
		text-align:center
	}
	
	
</style>
<section class="emp_image_view wrapper"
	style="margin-top: -6px; padding: 0px;">
	<!-- page start-->
	<div class="row">
		<aside class="profile-nav col-lg-3">
			<section class="panel">
				<form id="upload_form" enctype="multipart/form-data" method="post"
					onsubmit="return checkForm()">

					<input type="hidden" class="form-control emp_new" 
						name="employee_id"
						value="<?php if(isset($_GET['employeeID'])){echo $_GET['employeeID'];}?>" />
					<input type="hidden" class="form-control" name="emp_image"
						value="image" />
					<div class="user-heading round">
						<a href="#employeeCrop" style="color: #fff;" class="profile_id"
							data-toggle="modal">
							<canvas id="preview_image" style="display: none"></canvas> <img
							id="preview_image_"
							src='http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image'>
						</a> <a class="profileAl"> <span class="fileinput-button "
							style="width: inherit; height: inherit; border: medium none; background: #00BCD4 none repeat scroll 0% 0%;">
								<img id="round_preview_image" class="employee_image_preview "
								src=""
								alt="Employee Image"><input disabled type="file"
								name="employee_image" id="image" accept="image/jpeg,image/png"
								style="width: 116px; height: 97px; margin-top: -41px;" />
						</span>
						</a>
						<h4 id="emp_name"></h4>
						<h4 id="empIdShow" class="empIdShow" style="margin-bottom: 0px;"></h4>
					</div>
					<div aria-hidden="true" aria-labelledby="myModalLabel"
						role="dialog" tabindex="-1" id="employeeCrop" class="modal fade">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close"
										type="button">&times;</button>
									<h4 class="modal-title">Employee Image Crop</h4>
								</div>
								<div class="modal-body">
									<div class="body">
										<!-- upload form -->
										<!-- hidden crop params -->
										<input type="hidden" id="x1" name="x1" /> <input type="hidden"
											id="y1" name="y1" /> <input type="hidden" id="x2" name="x2" />
										<input type="hidden" id="y2" name="y2" />
										<div>
											<span class="btn btn-success btn-sm fileinput-button"
												style="width: 134px;"> <i class="fa fa-upload"></i>Upload
												Image <input type="file" name="image_file" id="image_file"
												onchange="fileSelectHandler()" accept="image/jpeg,image/png" />

											</span>
										</div>

										<div class="step2">
											<br> <img id="preview" />

											<div class="info">
												<input type="hidden" id="w" name="w" /> <input type="hidden"
													id="h" name="h" /> <input type="hidden" id="imageexit"
													name="imageexit" />
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<div class="error pull-left" style="color: red"></div>
										<a id="emp_image" href="" class="btn btn-default btn-sm" download> 
											<i class="fa fa-download"></i> Download</a>
										<button type="button" class="btn btn-sm btn-danger"
											data-dismiss="modal" id="cancelCrop">Cancel</button>
										<button type="submit" class="btn btn-sm btn-success"
											id="cropok">Crop</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
				<div id="loader" class="ul_loader" style="height: 58%; width: 85%"></div>
				<ul class="nav nav-pills nav-stacked" id="leave_account_tabs"
					role="tablist">
					<li class="profile_view "><a class="pro_list" id="personal_tabs"
						data-toggle="tab" data-loaded=false data-title="personal_tabs"
						href="#personal"> <i class="fa fa-user"></i>Personal Details
					</a></li>
					<li class="work_view "><a class="pro_list" id="work_detail_tabs"
						data-toggle="tab" data-loaded=false data-title="work_tabs"
						href="#work_detail"><i class="fa fa-briefcase"></i> Work Details
					</a></li>
					<li class="ctc_view "><a class="pro_list" id="salary_tabs"
						data-toggle="tab" data-loaded=false data-title="salary_tabs"
						href="#salary_detail"><i class="fa fa-money"></i> Salary 
					</a></li>	
					<li class="address_view"><a class="pro_list" id="address_tabs"
						data-toggle="tab" data-loaded=false data-title="personal_tabs"
						href="#for_address"><i class="fa fa-map-marker"></i>Address</a></li>
					<li class="career_view"><a class="pro_list" id="career_tabs"
						data-toggle="tab" data-loaded=false data-title="personal_tabs"
						href="#career"> <i class="fa fa-book"></i> Edu- Career
					</a></li>
					<li class="work_history_view"><a class="pro_list" id="work_tabs"
						data-toggle="tab" data-loaded=false data-title="work_tabs"
						href="#work_history"><i class="fa fa-building-o"></i> Work History
					</a></li>
					
					<li class="work_proof_view"><a class="pro_list" 
						id="work_proof_tabs" data-toggle="tab" data-loaded=false
						data-title="work_proof_tabs" href="#work_proof"><i
							class="fa fa-recycle"></i> Life Cycle </a>
					</li>
				</ul>

			</section>
		</aside>

		<aside class="profile-info col-lg-9">

			<section class="panel">
				<div class="loader">
					<div class="tab-content tasi-tab">
						<div class="tab-pane" id="personal">
							<div id="loader" class="personal_detail_loader"
								style="width: 96%"></div>
							<form role="form" method="POST" class="form-horizontal"
								enctype="multipart/form-data" id="emp_detail_form">
	
								<div class="personal bio-graph-heading">
									<p>
										<em>Employee Profile View </em>
										<button type="button"
											class="pull-right btn btn-sm  btn-danger back_emp ">Back</button>
										<input type="button"
											class="pull-right emp_edit1 btn-sm btn btn-success"
											value="Edit"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
										<input type="button"
											class="pull-right btn-sm emp_cancel1 btn btn-danger"
											value="Cancel"
											style="background-color: #f00; border-color: #f00"></input> <input
											type="submit"
											class="pull-right btn-sm emp_up1 btn btn-success"
											value="Update"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
									</p>
								</div>
								<!--Personal Strats-->
								<div class="personal panel-body">
								
								
								
								<div class="row">

										<div class="col-lg-6">

											<div class="form-group hidden">
												<label class="col-lg-5 control-label">Employee ID</label>
												<div class=" col-lg-7 ">
													<input type="hidden" class="form-control" name="personal"
														value="personal" /> <input type="hidden"
														class="form-control emp_new" id="emp_id_personal"
														name="employee_id" /> <input type="text"
														class="form-control" name="employee_id" id="employee_id"
														readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">First Name</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_name" id="employee_name"  readonly="readonly" required/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Last Name</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_lastname" id="employee_lastname"
														readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Date of Birth </label>
												<div class="col-md-7 col-xs-11 input-group">
													<span class="input-group-addon" id="empIdPrefix0"><i
														class="fa fa-calendar"></i></span> <input type="text"
														class="form-control" name="employee_dob" id="employee_dob"
														value="" readonly="readonly" required/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Gender</label>
												<div class="col-lg-7">
													<input type="text" class="form-control"
														id="employee_gender" readonly="readonly" /> <label
														for="gender1" class="col-lg-3 control-label male"><input
														checked name="employee_gender" id="gender1" value="Male"
														type="radio"> Male</label> <label for="gender2"
														class="col-lg-3 control-label female"><input
														name="employee_gender" id="gender2" value="Female"
														type="radio"> Female</label> <label for="gender3"
														class="col-lg-3 control-label other"><input
														name="employee_gender" id="gender3" value="Trans"
														type="radio"> Trans</label>

												</div>
											</div>


											<div class="form-group">
												<label class="col-lg-5 control-label">Official Mobile </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_mobile" id="employee_mobile" maxlength="10"
														readonly="readonly" />
														<!--  <span class="message"></span>-->
														<span style="margin-top: 10px;" class="col-lg-5 text-center omobile-emp hide"> -</span>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Official Email </label>
												<div class="col-lg-7 ">
													<input type="email" class="form-control"
														name="employee_email" id="employee_email" maxlength="70"
														readonly="readonly" required/>
														
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Personal Mobile </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_personal_mobile_number" id="employee_personal_mobile" maxlength="10"
														readonly="readonly" required/>
														<span class="message"></span>
														<span style="margin-top: 10px;" class="col-lg-5 text-center pmobile-emp hide"> -</span>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Personal Email </label>
												<div class="col-lg-7 ">
													<input type="email" class="form-control"
														name="emp_personal_email_id" id="employee_personal_email" maxlength="70"
														readonly="readonly" required/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label"> Father's Name</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_father_name" id="employee_father_name"
														maxlength="30" readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Father's DOB</label>
												<div class="col-lg-7">
													<input type="text" class="form-control" name="father_dob"
														id="father_dob" maxlength="10" />
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
														name="emp_mother_name" id="emp_mother_name" maxlength="30"
														readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Mother's DOB</label>
												<div class="col-lg-7">
													<input type="text" class="form-control"
														name="emp_mother_dob" id="mother_dob" maxlength="10" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Mother's Mobile Number</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_mother_mobile" id="emp_mother_mobile"
														maxlength="10"  readonly />
														<span class="message"></span>
														<span style="margin-top: 10px;" class="col-lg-5 text-center mmobile-emp hide"> -</span>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Marital Status </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														id="employee_marital_status" readonly="readonly" /> <label
														for="employee_marital_status1"
														class="col-lg-6 m_l control-label"> <input
														name="employee_marital_status" class="matrial1"
														id="employee_marital_status1" value="Single" type="radio"
														disabled> Single
													</label> <label for="employee_marital_status2"
														class="col-lg-6 m_l control-label"
														style="padding-left: 0px;"> <input
														name="employee_marital_status" class="matrial2"
														id="employee_marital_status2" value="Married" type="radio"
														disabled> Married
													</label>
												</div>
											</div>

											<div class="form-group spouse_name_hide" id="spouse_name_hide">
												<label class="col-lg-5 control-label">Marriage Date</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_marriagedate" maxlength="10"
														id="employee_marriagedate" readonly="readonly" />
												</div>
											</div>
											<div class="form-group  spouse_name_hide ">
													<label class="col-lg-5 control-label">Spouse Name</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control"
															name="emp_spouse_name" id="spouse_name"
															maxlength="30" readonly />
													</div>
											</div>
														
											<div class="form-group spouse_name_hide ">
												<label class="col-lg-5 control-label">Spouse Mobile Number</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_spouse_mobile" id="spouse_mobile" 
														maxlength="10"  readonly />
														<span class="message"></span>
												</div>
											</div>
														
											<div class="form-group spouse_name_hide ">
												<label class="col-lg-5 control-label">Spouse DOB</label>
												<div class="col-lg-7">
													<input type="text" class="form-control"
														name="emp_spouse_dob" id="spouse_dob" maxlength="10" readonly />
												</div>
											</div>

										</div>

										<div class="col-md-6">

											<div class="form-group">
												<label class="col-lg-5 control-label">Nationality</label>
												<div class=" col-lg-7">
													<input type="text" class="form-control"
														id="employee_nationality" readonly="readonly" /> <select
														class="form-control" id="employee_nationality1"
														name="employee_nationality">
														<option value="">Select Nationality</option>
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
												<label class="col-lg-5 control-label">International Employee</label>
												<div class="col-lg-7">
													<input type="text" class="form-control"
														id="employee_international" readonly="readonly" /> <label
														for="foreign" class="col-lg-6 m_l control-label"><input
														name="employee_international" class="foreign" id="foreign"
														value="1" type="radio" disabled> Yes</label> <label
														for="domestic" class="col-lg-6 m_l control-label"
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
														id="employee_blood_group" readonly="readonly" />
														<select class="form-control" id="employee_blood_group1" name="employee_blood_group">
														<option value="">Select Blood Group</option>
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
													<input type="text" class="form-control" id="employee_pc"
														readonly="readonly" /> <label for="special"
														class="col-lg-6 m_l control-label"><input
														name="employee_pc" class="special" id="special" value="1"
														type="radio" > Yes</label> <label for="able"
														class="col-lg-6 m_l control-label"
														style="padding-left: 0px;"><input name="employee_pc"
														id="able" class="able" value="0" type="radio"> No</label>

												</div>
											</div>


											<div class="form-group">
												<label class="col-lg-5 control-label">Pancard Number </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_pan_no" id="employee_pan_no" maxlength="10"
														readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">PAN Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success btn-xs fileinput-button attach">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>

																<input class="image_change" name="employee_pan_proof"
																id="image" accept="image/jpeg,image/png"
																style="display: block;" type="file">
															</span>
														</div>
														<div class="col-lg-8">
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
														maxlength="20" readonly="readonly" required/>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-5 control-label">Aadhaar Name</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_aadhaar_name" id="employee_aadhaar_name"
														maxlength="20" readonly="readonly" required/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Aadhaar Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success fileinput-button attach btn-xs">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																<input type="file" name="employee_aadhaar_proof"
																id="image" class="image_change" accept="image/jpeg,image/png">
															</span>
														</div>
														<div class="col-lg-8">
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
													<input type="text" id="employee_bank_name1"
														class="form-control" readonly="readonly"> <select class="form-control"
														name="employee_bank_name" id="employee_bank_name">
												<option value="">Select Bank Name</option>
											    <option value="State Bank of India"> State Bank of India</option>
											    <option value="State Bank of Bikaner and Jaipur"> State Bank of Bikaner and Jaipur</option>
											     <option value="State Bank of Hyderabad">State Bank of Hyderabad</option>
											     <option value="State Bank of Mysore">State Bank of Mysore</option>
											     <option value="State Bank of Patiala">State Bank of Patiala</option>
											     <option value="State Bank of Travancore">State Bank of Travancore</option>
												 <option value="Allahabad Bank">Allahabad Bank</option>
											     <option value="Andhra Bank">Andhra Bank</option>
											     <option value="Bank of India">Bank of India</option>
											     <option value="Bank of Baroda">Bank of Baroda</option>
											     <option value="Bank of Maharashtra">Bank of Maharashtra</option>
											     <option value="Canara Bank">Canara Bank</option>
											     <option value="Central Bank of India">Central Bank of India</option>
											     <option value="Corporation Bank">Corporation Bank</option>
											     <option value="Dena Bank">Dena Bank</option>
											     <option value="Indian Bank">Indian Bank</option>
											     <option value="Indian Overseas Bank">Indian Overseas Bank</option>
											     <option value="Oriental Bank of Commerce">Oriental Bank of Commerce</option>
											     <option value="Punjab & Sindh Bank">Punjab &amp; Sindh Bank</option>
											     <option value="Punjab National Bank">Punjab National Bank</option>
											     <option value="Syndicate Bank">Syndicate Bank</option>
											     <option value="UCO Bank">UCO Bank</option>
											     <option value="Union Bank of India">Union Bank of India</option>
											     <option value="United Bank of India">United Bank of India</option>
											     <option value="Vijaya Bank">Vijaya Bank</option>
												 <option value="IDBI Bank">IDBI Bank</option>
											     <option value="Bharatiya Mahila Bank">Bharatiya Mahila Bank</option>
											     <option value="State Bank of Sikkim">State Bank of Sikkim</option>
											     <option value="ICICI Bank">ICICI Bank</option>
											     <option value="Bandhan Bank">Bandhan Bank</option>
											     <option value="Catholic Syrian Bank">Catholic Syrian Bank</option>
											     <option value="City Union Bank">City Union Bank</option>
											     <option value="Dhanlaxmi Bank">Dhanlaxmi Bank</option>
											     <option value="DCB Bank">DCB Bank</option>
											     <option value="Federal Bank">Federal Bank</option>
											     <option value="HDFC Bank">HDFC Bank</option>
											     <option value="Tamilnad Mercantile Bank Limited">Tamilnad Mercantile Bank Limited</option>
											     <option value="IDFC Bank">IDFC Bank</option>
											     <option value="Karnataka Bank">Karnataka Bank</option>
											     <option value="IndusInd Bank">IndusInd Bank</option>
											     <option value="Jammu and Kashmir Bank">Jammu and Kashmir Bank</option>
											     <option value="Karur Vysya Bank">Karur Vysya Bank</option>
											     <option value="Kotak Mahindra Bank">Kotak Mahindra Bank</option>
												 <option value="Lakshmi Vilas Bank">Lakshmi Vilas Bank</option>
											     <option value="Nainital Bank">Nainital Bank</option>
											     <option value="RBL Bank">RBL Bank</option>
											     <option value="South Indian Bank">South Indian Bank</option>
											     <option value="Yes Bank">Yes Bank</option>
											     <option value="Axis Bank">Axis Bank</option>
													</select>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Bank A/c Number </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_acc_no" id="employee_acc_no" maxlength="30"
														readonly="readonly" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Bank Branch</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_bank_branch" maxlength="30"
														id="employee_bank_branch" readonly="readonly" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Bank IFSC </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_bank_ifsc" id="employee_bank_ifsc"
														maxlength="12" readonly="readonly" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Bank Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success fileinput-button attach btn-xs">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																<input type="file" name="employee_bank_proof" id="image"
																class="image_change" accept="image/jpeg,image/png">
															</span>
														</div>
														<div class="col-lg-8">
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
														id="employee_id_proof_type" readonly="readonly" /> <select
														class="form-control" id="employee_id_proof_type1"
														name="employee_id_proof_type">
														<option value="">Select Id Proof Type</option>
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
														readonly="readonly" />
												</div>
											</div>


											<div class="form-group ">
												<label class="col-lg-5 control-label"> ID Expiry Date </label>
												<div class="col-md-7 col-xs-11 input-group">
													<span class="input-group-addon" id="empIdPrefix1"><i
														class="fa fa-calendar"></i></span> <input type="text"
														class="form-control" name="employee_id_proof_expiry"
														maxlength="10" id="employee_id_proof_expiry"
														readonly="readonly" />
												</div>
											</div>


											<div class="form-group">
												<label class="col-lg-5 control-label">ID Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success fileinput-button attach btn-xs">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																<input type="file" name="employee_id_proof" id="image"
																class="image_change" accept="image/jpeg,image/png">
															</span>
														</div>
														<div class="col-lg-8">
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
										<button type="button"
											class="pull-right btn-sm btn btn-danger back_emp ">Back</button>
										<input type="button"
											class="pull-right btn-sm emp_edit2 btn btn-success"
											value="Edit"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
										<input type="button"
											class="pull-right btn-sm emp_cancel2 btn btn-danger"
											value="Cancel"
											style="background-color: #f00; border-color: #f00"></input> <input
											type="submit"
											class="pull-right btn-sm emp_up2 btn btn-success"
											value="Update"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
									</p>
								</div>

								<div class="for_address panel-body">
									<input type="hidden" class="form-control emp_new"
										id="emp_id_address" name="employee_id" /> <input type="hidden"
										class="form-control" name="address" value="address" />
									
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
														id="employee_build_name" readonly="readonly" required/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Area</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_area" id="employee_area" maxlength="50"
														readonly="readonly" required/>
												</div>
											</div>



											<div class="form-group">
												<label class="col-lg-5 control-label">Pincode</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_pin_code" id="employee_pin_code"
														maxlength="6" readonly="readonly" required/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">City </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_city" id="employee_city" maxlength="50"
														readonly="readonly" required/>
												</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-5 control-label">District / Taluk</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control"
															name="employee_district" id="employee_district" maxlength="50"
															readonly required/>
													</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">State </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="employee_state" id="employee_state" maxlength="50"
														readonly="readonly" required/>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Country </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control" name="emp_country"
														id="emp_country" maxlength="50" readonly="readonly" required/>
												</div>
											</div>
											<div class="form-group" id="hide_label_address">
												<label class="col-lg-5 control-label" id="hide_que">If
													Permanent address same as Present address</label>
												<div class="col-lg-7">
													<label for="emp_ad" class=" col-lg-6 m_l1 control-label"> <input
														name="employee_pt_adddress" id="emp_ad" value="1"
														type="radio" disabled> Yes
													</label> <label for="emp_ad1"
														class=" col-lg-6 m_l1 control-label"
														style="padding-left: 0px;"> <input
														name="employee_pt_adddress" id="emp_ad1" value="0"
														type="radio" disabled> No
													</label> <input type="text"
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
										<button type="button"
											class="pull-right btn btn-sm btn-danger back_emp">Back</button>
										<input type="button"
											class="pull-right btn-sm emp_edit3 btn btn-success"
											value="Edit"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
										<input type="button"
											class="pull-right btn-sm emp_cancel3 btn btn-danger"
											value="Cancel"
											style="background-color: #f00; border-color: #f00"></input> <input
											type="submit"
											class="pull-right btn-sm emp_up3 btn btn-success"
											value="Update"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
									</p>
								</div>

								<div class="career panel-body">
									
									<div class="row">
										<div class="col-lg-6">
											<h5>
												<b>SSLC </b>
											</h5>
											<hr style="margin-top: 0px;">
											<div class="form-group">
												<label class="col-lg-5 control-label"> School Name </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_sslc_school" id="emp_sslc_school" maxlength="50"
														readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Board Of Education</label>
												<div class=" col-lg-7">
													<input type="text" class="form-control"
														name="emp_sslc_board" id="emp_sslc_board" readonly="readonly" />
													<select class="form-control" id="emp_sslc_board1"
														name="emp_sslc_board1">
														<option value="">Select Board Of Education</option>
														<option value="StateBoard">StateBoard</option>
														<option value="Matriculation">Matriculation</option>
														<option value="CBSE">CBSE</option>
														<option value="ICSE">ICSE</option>
														<option value="IB">IB</option>
													</select>

												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Marks (in percentage)</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_sslc_marks" id="emp_sslc_marks" maxlength="5"
														readonly="readonly" />
												</div>
											</div>
											<div class="form-group ">
												<label class="col-lg-5 control-label"> Year of Passing </label>
												<div class="col-md-7 col-xs-11 input-group">
													<span class="input-group-addon empIdPrefix1_edu"><i
														class="fa fa-calendar"></i></span> <input type="text"
														class="period1 form-control" name="emp_sslc_year"
														id="emp_sslc_year" maxlength="4" readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success fileinput-button attach_edu btn-xs">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																<input type="file" name="employee_sslc_proof"
																class="image_change_edu" accept="image/jpeg,image/png">
															</span>
														</div>
														<div class="col-lg-8">
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
														maxlength="20" readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> University </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_ug_university" id="emp_ug_university"
														maxlength="20" readonly="readonly" />
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
														maxlength="35" readonly/>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-5 control-label">Marks (in percentage)</label>
												<div class="col-lg-7">
													<input type="text" class="form-control" name="emp_ug_marks"
														id="emp_ug_marks" maxlength="5" readonly="readonly" />
												</div>
											</div>
											<div class="form-group ">
												<label class="col-lg-5 control-label"> Year of Passing </label>
												<div class="col-md-7 col-xs-11 input-group">
													<span class="input-group-addon empIdPrefix1_edu"><i
														class="fa fa-calendar"></i></span> <input type="text"
														class="period3 form-control" name="emp_ug_year_passing"
														id="emp_ug_year_passing" maxlength="4" readonly="readonly" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label"> Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success fileinput-button attach_edu btn-xs">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																<input type="file" name="employee_ug_proof" id="image"
																class="image_change_edu" accept="image/jpeg,image/png">
															</span>
														</div>
														<div class="col-lg-8">
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
													<input type="text" class="form-control"
														name="emp_hsc_school" id="emp_hsc_school" maxlength="50"
														readonly="readonly" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label">Board Of Education</label>
												<div class=" col-lg-7">
													<input type="text" class="form-control"
														name="emp_hsc_board" id="emp_hsc_board" readonly="readonly" />
													<select class="form-control" id="emp_hsc_board1"
														name="emp_hsc_board1">
														<option value="">Select Board Of Education</option>
														<option value="StateBoard">StateBoard</option>
														<option value="Matriculation">Matriculation</option>
														<option value="CBSE">CBSE</option>
														<option value="ICSE">ICSE</option>
														<option value="IB">IB</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label">Marks (in percentage)</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_hsc_marks" id="emp_hsc_marks" maxlength="5"
														readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Year of Passing </label>
												<div class="col-md-7 col-xs-11 input-group">
													<span class="input-group-addon empIdPrefix1_edu"><i
														class="fa fa-calendar"></i></span> <input type="text"
														class="period2 form-control" name="emp_hsc_year"
														id="emp_hsc_year" maxlength="4" readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success fileinput-button attach_edu btn-xs">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																<input type="file" name="employee_hsc_proof"
																class="image_change_edu" accept="image/jpeg,image/png">
															</span>
														</div>
														<div class="col-lg-8">
															<a class="btn btn-danger  view btn-xs"
																data-toggle="modal" href="#myModal"> <input
																type="hidden" class="form-control"
																id="employee_hsc_proof" value="" /> <i class="fa fa-eye"></i>
																View
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
														maxlength="20" readonly="readonly" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Universty </label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control"
														name="emp_pg_university" id="emp_pg_university"
														maxlength="20" readonly="readonly" />
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
															maxlength="35" readonly/>
													</div>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Marks (in percentage)</label>
												<div class="col-lg-7 ">
													<input type="text" class="form-control" name="emp_pg_marks"
														id="emp_pg_marks" maxlength="5" readonly="readonly" />
												</div>
											</div>
											<div class="form-group ">
												<label class="col-lg-5 control-label"> Year of Passing </label>
												<div class="col-md-7 col-xs-11 input-group">
													<span class="input-group-addon empIdPrefix1_edu"><i
														class="fa fa-calendar"></i></span> <input type="text"
														class="period4 form-control" name="emp_pg_year_passing"
														id="emp_pg_year_passing" maxlength="4" readonly="readonly" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-5 control-label"> Proof</label>
												<div class="col-lg-7">
													<div class="row fileupload-buttonbar ">
														<div class="col-lg-4">
															<span
																class="btn btn-success fileinput-button attach_edu btn-xs">
																<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																<input type="file" name="employee_pg_proof"
																class="image_change_edu" accept="image/jpeg,image/png">
															</span>
														</div>
														<div class="col-lg-8">
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
							<div class="ctc_detail_loader_sub"
								style="width: 96%; height: 100%"></div>
							<div id="loader" class="ctc_detail_loader" style="width: 96%"></div>
							<form enctype="multipart/form-data" class="form-horizontal"
								id="emp_ctc_form" method="post" action="">
								<input type="hidden" value="ctc" name="fromSalary"> <input
									type="hidden" class="form-control emp_new" id="emp_ctc_id"
									name="employee_id" />
								<div class="ctc_detail_head bio-graph-heading">
									<p>
										<em>Employee Salary <a class="wef" style="color:#fff">[<span id="effect_sal_from"></span>]</a>
										</em>
										<button type="button"
											class="pull-right btn btn-sm  btn-danger back_emp ">Back</button>
										<input type="button"
											class="pull-right emp_edit7 btn-sm btn btn-success"
											value="Edit"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
										<input type="button"
											class="pull-right btn-sm emp_cancel7 btn btn-danger"
											value="Cancel"
											style="background-color: #f00; border-color: #f00"></input> <input
											type="submit"
											class="pull-right btn-sm emp_up7 btn btn-success"
											value="Update"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
									</p>
								</div>
								<div class="no_ctc_detail panel-body" style="display: none">
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
													<label for="ctcval" class="col-lg-4 control-label" title="CTC"
														style="padding-right: 0"><input name="salary_type"
														id="ctcval" value="ctc" type="radio">CTC</label>
														<label for="salary_type" title="Gross" class="col-lg-4 control-label" style="padding-left: 0; padding-right: 0">
															<input name="salary_type"id="salary_type" value="monthly" type="radio">
															GROSS</label>
												</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-5 control-label">Input Type</label>
													<div class="col-lg-7">
															<label for="monthly" class="col-lg-4 control-label" title="Monthly">
															<input name="input_type" class="ip" id="monthly" value="monthly" type="radio"> Monthly</label> 
															<label for="annual" class="col-lg-4 control-label" title="Annual"><input name="input_type" class="ip" id="annual" value="annual" type="radio">
															Annual</label>
													</div>
												</div>
												
											<div id="slabloader" style="width: 100%; height: 50%"></div>
											<input type="hidden" id="checkIfexit">
											<div class="form-group" id="slab_select_box" >
												<label class="col-lg-5 control-label">Slab Name</label>
												<div class="col-lg-7 showSpan">
													<input type="text" class="form-control"
														id="slabdata" required/>

												</div>
												<div class="col-lg-7 hiddenSpan">
													<select id="slab_opt" name="slab" class="form-control" required>
														<option value="">Select Slab</option>
													</select> <span class="help-block" id="minimum_salary_div"
														style="display: none">Applicable only for salary gt <span
														id="min_salary_amount"></span></span>
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
								<div class="col-lg-12 panel-body perqdetails hide">
								
											        <input type="hidden" name="act"
														value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
											        <input type="checkbox" aria-label="..." id="perq" name="perq_check" checked>&nbsp;&nbsp;  Perquisites
											        <div class="perqs" id="perqsdata">
													      
											        </div>
									</div>
						</div>
						<!--CTC Details Ends-->

						<!--Work history Strats-->
						<div class="tab-pane" id="work_history">
							<div id="loader" class="work_detail_loader" style="width: 96%"></div>
							<form enctype="multipart/form-data" class="form-horizontal"
								id="emp_work_histroy_form" method="post" action="" >
								<div class="work_history bio-graph-heading">
									<p>
										<em>Employee Work History </em>
										<button type="button"
											class="pull-right btn btn-sm  btn-danger back_emp ">Back</button>
										<input type="button"
											class="pull-right emp_edit4 btn-sm btn btn-success"
											value="Edit"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
										<input type="button"
											class="pull-right btn-sm emp_cancel4 btn btn-danger"
											value="Cancel"
											style="background-color: #f00; border-color: #f00"></input> <input
											type="submit"
											class="pull-right btn-sm emp_up4 btn btn-success"
											value="Update"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
									</p>
								</div>
								<div class="work_history panel-body">
									<div class="row">
										<div class="col-lg-12">
										<h4 class="exp" style="text-align: center" >
													</h4>
										<button type="button"
											class="pull-right btn btn-sm  btn-success addCompany" id="addCompany">Add
											Company</button>
										<button
											type="button"
											class="hide pull-right btn btn-sm remove btn-danger">
											<i class="fa fa-times"></i> Remove
										</button>
										<input type="hidden" id="addcompanyno" value='1'
											name="totExper">
								
									<input type="hidden" value="workExp" name="workExp"> <input
										type="hidden" class="form-control emp_new" id="empExpId"
										name="employee_id" /></div>
										</div>

									</div>
									<!--  Row End-->
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
										<em> Employee Work Details</em>

										<button type="button"
											class="pull-right btn-sm btn btn-danger back_emp ">Back</button>
										<input type="button"
											class="pull-right btn-sm emp_edit5 btn btn-success"
											value="Edit"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;" />
										<input type="button"
											class="pull-right emp_cancel5  btn-danger  btn btn-sm"
											value="Cancel"
											style="background-color: #f00; border-color: #f00" /> <input
											type="submit"
											class="pull-right  emp_up5  btn-success btn btn-sm "
											value="Update"
											style="margin-right: 0.5%; background-color: #459626; border-color: #449021;"></input>
								
								</div>
								<div class="work_detail panel-body">

									
									<input type="hidden" id="payrollCheck">
									<div class="row">

										<div class="col-lg-6 ">
											<input type="hidden" class="employeeName">
											<input type="hidden" class="empIdShow">
											<div class="form-group">
												<label class="col-lg-5 control-label"> Designation</label>
												<div class="col-lg-7 ">
													<input type="text" class="work_details form-control" id="designation_id1" 
														 name="designation_id" readonly="readonly"/>
												</div>
												<label class="col-lg-offset-10 change_des"><a href="#designation_info">Change</a></label>
    										</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Department</label>
												<div class="col-lg-7 ">
													<input type="text" class="work_details form-control"
														id="department_name" name="department_id" readonly="readonly" />
												</div>
												<label class="col-lg-offset-10 change_dept"> <a href="#department_info">Change</a></label>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Branch</label>
												<div class="col-lg-7 ">
													<input type="text" class="work_details form-control"
														id="branch_name" name="branch_id" readonly="readonly" />
												</div>
												<label class="col-lg-offset-10 change_branch"> <a href="#branch_info">Change</a></label>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Team</label>
												<div class="col-lg-7 ">
													<input type="text" class="work_details form-control"
														id="team_name" name="team_id" readonly="readonly" />
												</div>
												<label class="col-lg-offset-10 change_team"> <a href="#team_info">Change</a></label>
											</div>
											<div class="form-group">
												<label class="col-lg-5 control-label"> Job Status</label>
												<div class="col-lg-7 ">
													<input type="text" class="work_details form-control work"
														id="status_name" name="status_id" readonly="readonly" /> 
												</div>
												<label class="col-lg-offset-10 change_job_status"> <a href="#job_status_info">Change</a></label>
											</div>
											
            								<div class="form-group">
            									<label class="col-lg-5 control-label"> Shifts</label>
            									<div class=" col-lg-7 ">
            									 <input type="text" class="work_details form-control" name="shift_id" 
            									 		id="shift_name" readonly="readonly"/>
            									</div>
            									<label class="col-lg-offset-10 change_shift"><a href="#shift_info">Change</a></label>
            								</div>
            								
            								<div class="form-group">
												<label class="col-lg-5 control-label">Reporting Manager</label>
												<div class="col-lg-7">
													<input type="hidden" id="rep_man-id-e" name="rep_man-id"> <input
														type="text" class="form-control" id="rep_man"
														name="rep_man" autocomplete="off" /> <span
														class="help-block"><span class="pull-right empty-message"
														style="display: none">No Records Found</span></span>
												</div>
											</div>


											<div class="form-group hidden">
												<label class="col-lg-5 control-label"> WeekEnds </label>
												<div class=" col-lg-7 ">
													<input type="text" class="work_details form-control"
														name="weekend_id" id="weekend_id" readonly="readonly" />
												</div>
											</div>
											
												<div class="col-lg-6">
	                                            <div class="col-lg-3" style="margin-right:20px;">
	                                            <div id="deletebutton" class="displayHide" >
	                                            <input type="hidden" class="empIdShow" name="employeeId" value="" />
	                                                <button type="button" class="btn-xs btn btn-danger wipeEmp"
	                                                    id="wipeEmp" >
	                                                    <i class="fa fa-trash-o" aria-hidden="true"></i> DELETE
	                                                </button>
	                                            </div>
	                                  
	                                          </div>
	                                            
	                                            <div class="col-lg-3">
	                                              <div id="enrollbutton" class="enrollbutton"> 
		                                                <button type="button" class="btn-xs btn btn-info"
		                                                    id="enrollEmp" data-toggle="modal" data-target="#modalBox">
		                                                    <i class="fa fa-user-plus" aria-hidden="true"></i> ENROLL
		                                                </button>
		                                            </div> 
	                                           </div>
											</div>
										</div>
										
										

										<div class="col-lg-6">
											<div class="form-group">
												<label class="col-lg-5 control-label"> Payment Mode </label>
												<div class=" col-lg-7 ">
					<input type="text" class="work_details form-control" id="payment_mode_name" readonly="readonly" />
	                <select class="form-control" id="payment_mode1"	name="payment_mode_id">
							
                                                   <?php
											$stmt = mysqli_prepare ( $conn, "SELECT payment_mode_id, payment_mode_name FROM company_payment_modes" );
											$result = mysqli_stmt_execute ( $stmt );
											mysqli_stmt_bind_result ( $stmt, $payment_mode_id, $payment_mode_name );
											while ( mysqli_stmt_fetch ( $stmt ) ) {
												echo "<option value='" . $payment_mode_id . "'>" . $payment_mode_name . "</option>";
											}
												?>    
                                              </select>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-5 control-label"> UAN Number</label>
												<div class=" col-lg-7 ">
													<input type="text" class="work_details form-control"
														name="employee_emp_uan_no" id="employee_emp_uan_no"
														readonly="readonly" />
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-5 control-label"> EPF Number </label>
												<div class=" col-lg-7 ">
													<input type="text" class="work_details form-control" maxlength="26"
														name="employee_emp_pf_no" id="employee_emp_pf_no"
														readonly="readonly" />
												</div>
											</div>
											
											
											<div class="form-group">
												<label class="col-lg-5 control-label"> ESI ID </label>
												<div class="col-lg-7 ">
													<input type="text" class="work_details form-control"
														name="employee_emp_esi_no" id="employee_emp_esi_no"
														readonly="readonly" />
												</div>
											</div>
											
											
											<div class="form-group">
												<label class="col-lg-5 control-label">Date of Join</label>
												<div class=" col-md-7 col-xs-11 input-group">
													<span class=" input-group-addon spanCal2"><i
														class="fa fa-calendar"></i></span> <input
														class="work_details form-control" type="text"
														name="employee_doj" id="employee_doj" required
														readonly="readonly" />
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
														id="employee_confirmation_date" required readonly="readonly" />
												</div>
											</div>

										</div>
									</div>
									<div class="help-texts displayHide"></div>
								</div>
								
							</form>
							<!-- Modal for Designation Change starts here -->
            									<div class="modal fade designation_info"  tabindex="-1"  id="designation_info" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    								  <div class="modal-dialog" role="document" style="width: 800px;">
                    								    <div class="modal-content">
                    								      <div class="modal-header">
                    								        <h5 class="modal-title">Change Designation for <span class="emp_name_id"></span></h5>
                    								        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-21px;">
                    								          <span aria-hidden="true">&times;</span>
                    								        </button>
                    								      </div>
                    								      <div class="modal-body" style="padding-bottom:50px;">
                    								      	<form class="desig_form form-horizontal row" id="desig_form">
                    								      		<input type="hidden" class="empIdShow" name="employeeId">
                    								      <div class="col-lg-12">	
                    								      	<div class ="col-lg-6">
                    								      	<div class="form-group row">
                    											<label class="col-sm-5 control-label">New Designation</label>
                    												<div class="col-sm-7" >
																		<input type="text" class="form-control"
                    														id="new_designation"   name="newDesignation" readonly="readonly" required /> <select
                    														class="form-control new_desig1" id="new_desig1">
														
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
                    										<div class="form-group row">
                    													<label class="col-sm-5 control-label">Reason for change</label>
                    															<div class="col-sm-7">
                    																<textarea class="form-control desig_change_reason" name="desigChangeReason" placeholder="Change reasons" required ></textarea>
                    															</div>
                    										</div>
                    										
                    									<div class="form-group">
															<label class="col-sm-5 control-label">Designation Effects From </label>
															<div class=" col-sm-7 input-group">
															<span class=" input-group-addon spanCal2"><i class="fa fa-calendar"></i></span>
															 <input class="form-control" type="text"  name="design_effects_from" 
															 id="design_effects_from" required placeholder="dd/mm/yyyy" />
														 </div>
													 </div>
                    										<input type="submit" class="btn btn-sm btn-primary changeDes pull-right" value="Change"/>
                    										</div>
                    										<div class="col-lg-6" >
                    											<div class="panel panel-body" style="background-color:lightgrey">
                    												<p>Current Designation&emsp;:&nbsp; <span class="current_desig"></span></p>
                    												<p>Effects From  &emsp;&emsp;&emsp;&emsp;&nbsp;:&nbsp; <span class="changed_on"></span></p>
                    												<p>Purpose &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:&nbsp; <span class ="purpose_desig"></span></p>
                    												<p>Reason &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;:&nbsp; <span class ="desig_Changereason"></span></p>
                    											</div>
                    										</div>
                    									</div>
                    								  </form>
                    								  <hr>
                									 <div class="col-lg-12">
                										<div id="change_History_design"></div>
                									 </div>
                        									
                    								  </div>
                    								</div>
                    							 </div>
            								</div>
								            <!-- Modal for Designation Change end -->
								            <!-- Modal for Department Change starts here -->
								            <div class="modal fade department_info"  tabindex="-1"  id="department_info" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    							<div class="modal-dialog" role="document" style="width: 800px;">
                    								  <div class="modal-content">
                    								    <div class="modal-header">
                    								      <h5 class="modal-title">Change Department for <span class="emp_name_id"></span></h5>
                    								        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-21px;">
                    								          <span aria-hidden="true">&times;</span>
                    								        </button>
                    								    </div>
                    								    <div class="modal-body" style="padding-bottom:50px;">
                    								      <form class="dept_form form-horizontal row" id="dept_form">
                    								      	<input type="hidden" class="empIdShow" name="employeeId" value="" />
                    								     	 <div class="col-lg-12">
                    								      		<div class ="col-lg-6">
                    								      			<div class="form-group row">
                    													<label class="col-sm-5 control-label">New Department</label>
																	<div class="col-sm-7 ">
																		<input type="text" class=" form-control"
																	id="new_dept" name="newDepartment"  readonly="readonly" required /> <select
																	class="form-control new_dept1" id="new_dept1" >
													   
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
                    											<div class="form-group row">
                    													<label class="col-sm-5 control-label">Reason for change</label>
                    															<div class="col-sm-7">
                    																<textarea class="form-control dept_change_reason" name="deptChangeReason" placeholder="Change reasons" required ></textarea>
                    															</div>
                    											</div>
                    										
                        										<div class="form-group">
    																<label class="col-sm-5 control-label">Department Effects From </label>
    																<div class=" col-sm-7 input-group" >
    																	<span class=" input-group-addon spanCal2"><i class="fa fa-calendar"></i></span>
    															 		<input class="form-control" type="text" name="dept_effects_from" 
    															 			id="dept_effects_from" required placeholder="dd/mm/yyyy" />
    														 		</div>
    													 		</div>
                    											<input type="submit" class="btn btn-sm btn-primary changeDept pull-right" value="Change"/>
                    									 </div>
                    										
                    										<div class="col-lg-6" >
                    											<div class="panel panel-body" style="background-color:lightgrey">
                    												<p>Current Department&emsp;:&nbsp; <span class="current_dept"></span></p>
                    												<p>Effects From  &emsp;&emsp;&emsp;&emsp;&nbsp;:&nbsp; <span class="changed_on_dept"></span></p>
                    												<p>Reason &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;:&nbsp; <span class ="dept_Changereason"></span></p>
                    											</div>
                    										</div>
                    									</div>
                        							</form>	
                        							<hr>
                        							<div class="col-lg-12">
                        								<div id="change_History_deptmnt"></div>
                        							</div>
                    							</div>
                    				      </div>
            					    </div>
            					</div>  
								             <!-- Modal for Department Change end -->
								             <!-- Modal for Branch Change starts here -->
								  <div class="modal fade branch_info"  tabindex="-1"  id="branch_info" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    							<div class="modal-dialog" role="document" style="width: 800px;">
                    								  <div class="modal-content">
                    								    <div class="modal-header">
                    								      <h5 class="modal-title">Change Branch for <span class="emp_name_id"></span></h5>
                    								        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-21px;">
                    								          <span aria-hidden="true">&times;</span>
                    								        </button>
                    								    </div>
                    								    <div class="modal-body" style="padding-bottom:50px;">
                    								      <form class="branch_form form-horizontal row" id="branch_form">
                    								      	<input type="hidden" class="empIdShow" name="employeeId" value="" />
                    								     	 <div class="col-lg-12">
                    								      		<div class ="col-lg-6">
                    								      			<div class="form-group row">
                    													<label class="col-sm-5 control-label">New Branch</label>
																	<div class="col-sm-7 ">
																		<input type="text" class=" form-control"
																	id="new_branch" name="newBranch"  readonly="readonly" required /> <select
																	class="form-control new_branch1" id="new_branch1" >
													   
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
                    											<div class="form-group row">
                    													<label class="col-sm-5 control-label">Reason for change</label>
                    															<div class="col-sm-7">
                    																<textarea class="form-control branch_change_reason" name="deptChangeReason" placeholder="Change reasons" required ></textarea>
                    															</div>
                    											</div>
                    										
                        										<div class="form-group">
    																<label class="col-sm-5 control-label">Branch Effects From </label>
    																<div class=" col-sm-7 input-group" >
    																	<span class=" input-group-addon spanCal2"><i class="fa fa-calendar"></i></span>
    															 		<input class="form-control" type="text" name="branch_effects_from" 
    															 			id="branch_effects_from" required placeholder="dd/mm/yyyy" />
    														 		</div>
    													 		</div>
                    											<input type="submit" class="btn btn-sm btn-primary changeBranch pull-right" value="Change"/>
                    									 </div>
                    										
                    										<div class="col-lg-6" >
                    											<div class="panel panel-body" style="background-color:lightgrey">
                    												<p>Current Branch&emsp;:&nbsp; <span class="current_branch"></span></p>
                    												<p>Effects From  &emsp;&emsp;:&nbsp; <span class="changed_on_branch"></span></p>
                    												<p>Purpose &emsp;&emsp;&emsp;&emsp;:&nbsp; <span class ="purpose_branch"></span></p>
                    												<p>Reason &emsp;&emsp;&emsp;&emsp;&nbsp;:&nbsp; <span class ="branch_Changereason"></span></p>
                    											</div>
                    										</div>
                    									</div>
                        							</form>
                        							<hr>
                        							<div class="col-lg-12">
                        								<div id="change_History_branch"></div>
                        							</div>	
                    							</div>
                    				      </div>
            					    </div>
            					</div>             
								             <!------------ Modal for Branch end ---------->
								               <!-- Modal for Team Change starts here -->
								  <div class="modal fade team_info"  tabindex="-1"  id="team_info" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    							<div class="modal-dialog" role="document" style="width: 800px;">
                    								  <div class="modal-content">
                    								    <div class="modal-header">
                    								      <h5 class="modal-title">Change Team for <span class="emp_name_id"></span></h5>
                    								        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-21px;">
                    								          <span aria-hidden="true">&times;</span>
                    								        </button>
                    								    </div>
                    								    <div class="modal-body" style="padding-bottom:50px;">
                    								      <form class="team_form form-horizontal row" id="team_form">
                    								      	<input type="hidden" class="empIdShow" name="employeeId" value="" />
                    								     	 <div class="col-lg-12">
                    								      		<div class ="col-lg-6">
                    								      			<div class="form-group row">
                    													<label class="col-sm-5 control-label">New Team</label>
																	<div class="col-sm-7 ">
																		<input type="text" class=" form-control"
																	id="new_team" name="newTeam"  readonly="readonly" required /> <select
																	class="form-control new_team1" id="new_team1" >
													   
                                              			 			 <?php
                    																$stmt = mysqli_prepare ( $conn, "SELECT team_id, team_name FROM company_team" );
                    																$result = mysqli_stmt_execute ( $stmt );
                    																mysqli_stmt_bind_result ( $stmt, $team_id, $team_name );
                    																while ( mysqli_stmt_fetch ( $stmt ) ) {
                    																echo "<option value='" . $team_id . "'>" . $team_name . "</option>";
                    															}
													                    ?>    
                                              						</select>
																</div>
                    														
                    											</div>
                    											<div class="form-group row">
                    													<label class="col-sm-5 control-label">Reason for change</label>
                    															<div class="col-sm-7">
                    																<textarea class="form-control team_change_reason" name="teamChangeReason" placeholder="Change reasons" required ></textarea>
                    															</div>
                    											</div>
                    										
                        										<div class="form-group">
    																<label class="col-sm-5 control-label">Team Effects From </label>
    																<div class=" col-sm-7 input-group" >
    																	<span class=" input-group-addon spanCal2"><i class="fa fa-calendar"></i></span>
    															 		<input class="form-control" type="text" name="team_effects_from" 
    															 			id="team_effects_from" required placeholder="dd/mm/yyyy" />
    														 		</div>
    													 		</div>
                    											<input type="submit" class="btn btn-sm btn-primary changeTeam pull-right" value="Change"/>
                    									 </div>
                    										
                    										<div class="col-lg-6" >
                    											<div class="panel panel-body" style="background-color:lightgrey">
                    												<p>Current Team&emsp;:&nbsp; <span class="current_team"></span></p>
                    												<p>Effects From  &emsp;:&nbsp; <span class="changed_on_team"></span></p>
                    												<p>Reason &emsp;&emsp;&emsp;&nbsp;:&nbsp; <span class ="team_Changereason"></span></p>
                    											</div>
                    										</div>
                    									</div>
                        							</form>
                        							<hr>
                        							<div class="col-lg-12">
                        								<div id="change_History_team"></div>
                        							</div>	
                    							</div>
                    				      </div>
            					    </div>
            					</div>             
								             <!------------ Modal for Team end ---------->  
								           
								              <!-- Modal for job status Change starts here -->
								 <div class="modal fade job_status_info"  tabindex="-1"  id="job_status_info" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    							<div class="modal-dialog" role="document" style="width: 800px;">
                    								  <div class="modal-content">
                    								    <div class="modal-header">
                    								      <h5 class="modal-title">Change Job Status for <span class="emp_name_id"></span></h5>
                    								        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-21px;">
                    								          <span aria-hidden="true">&times;</span>
                    								        </button>
                    								    </div>
                    								    <div class="modal-body" style="padding-bottom:50px;">
                    								      <form class="job_status_form form-horizontal row" id="job_status_form">
                    								      	<input type="hidden" class="empIdShow" name="employeeId" value="" />
                    								     	 <div class="col-lg-12">
                    								      		<div class ="col-lg-6">
                    								      			<div class="form-group row">
                    													<label class="col-sm-5 control-label">New Status</label>
																	<div class="col-sm-7 ">
																		<input type="text" class=" form-control"
																	id="new_status" name="newStatus"  readonly="readonly" required /> <select
																	class="form-control new_status1" id="new_status1" >
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
                    											<div class="form-group row">
                    													<label class="col-sm-5 control-label">Reason for change</label>
                    															<div class="col-sm-7">
                    																<textarea class="form-control status_change_reason" name="statusChangeReason" placeholder="Change reasons" required ></textarea>
                    															</div>
                    											</div>
                    										
                        										<div class="form-group">
    																<label class="col-sm-5 control-label">Job status Effects From </label>
    																<div class=" col-sm-7 input-group" >
    																	<span class=" input-group-addon spanCal2"><i class="fa fa-calendar"></i></span>
    															 		<input class="form-control" type="text" name="status_effects_from" 
    															 			id="status_effects_from" required placeholder="dd/mm/yyyy" />
    														 		</div>
    													 		</div>
                    											<input type="submit" class="btn btn-sm btn-primary changejobStatus pull-right" value="Change"/>
                    									 </div>
                    										
                    										<div class="col-lg-6" >
                    											<div class="panel panel-body" style="background-color:lightgrey">
                    												<p>Current Status&emsp;:&nbsp; <span class="current_jobstatus"></span></p>
                    												<p>Effects From  &emsp;:&nbsp; <span class="changed_on_jobstatus"></span></p>
                    												<p>Reason &emsp;&emsp;&emsp;&nbsp;:&nbsp; <span class ="job_status_Changereason"></span></p>
                    											</div>
                    										</div>
                    									</div>
                        							</form>
                        							<hr>
													<div class="col-lg-12">
                        									<div id="change_History_status"></div>
                        							</div>
                    							</div>
                    				      </div>
            					    </div>
            					</div>             
								             <!------------ Modal for change job status end ---------->
							                 <!-- Modal for shift Change starts here -->
								  <div class="modal fade shift_info"  tabindex="-1"  id="shift_info" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    							<div class="modal-dialog" role="document" style="width: 800px;">
                    								  <div class="modal-content">
                    								    <div class="modal-header">
                    								      <h5 class="modal-title">Change Shift for <span class="emp_name_id"></span></h5>
                    								        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-21px;">
                    								          <span aria-hidden="true">&times;</span>
                    								        </button>
                    								    </div>
                    								    <div class="modal-body" style="padding-bottom:50px;">
                    								      <form class="shift_form form-horizontal row" id="shift_form">
                    								      	<input type="hidden" class="empIdShow" name="employeeId" value="" />
                    								     	 <div class="col-lg-12">
                    								      		<div class ="col-lg-6">
                    								      			<div class="form-group row">
                    													<label class="col-sm-5 control-label">New Shift</label>
																	<div class="col-sm-7 ">
																		<input type="text" class=" form-control"
																	id="new_shift" name="newShift"  readonly="readonly" required /> <select
																	class="form-control new_shift1" id="new_shift1" >
                                              			 				 <?php
                                                                            require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/shift.class.php");
                                                                            $shiftid = new Shift ();
                                                                            $shiftid->conn = $conn;
                                                                            $shifts = $shiftid->select (0);
                                                                            foreach ( $shifts as $row ) {
                                                                            	echo "<option value='" . $row ['shift_id'] . "'>" . $row ['shift_name'] ."<br>" . "</option>";
                                                                            }
                                                                    ?>     
                                              						</select>
																</div>
                    														
                    											</div>
                    											<div class="form-group">
    																<label class="col-sm-5 control-label">Date Of Start</label>
    																<div class=" col-sm-7 input-group" >
    																	<span class=" input-group-addon spanCal2"><i class="fa fa-calendar"></i></span>
    															 		<input class="form-control" type="text" name="DateofStart" 
    															 			id="date_of_start" required placeholder="dd/mm/yyyy" />
    														 		</div>
    													 		</div>
                    											<div class="form-group row">
                    													<label class="col-sm-5 control-label">Reason for change</label>
                    															<div class="col-sm-7">
                    																<textarea class="form-control shift_change_reason" name="shiftChangeReason" placeholder="Change reasons" required ></textarea>
                    															</div>
                    											</div>
                    											<input type="submit" class="btn btn-sm btn-primary changeShift pull-right" value="Change"/>
                    									 </div>
                    										
                    										<div class="col-lg-6" >
                    											<div class="panel panel-body" style="background-color:lightgrey">
                    												<p>Current Shift&emsp;:&nbsp; <span class="current_shift"></span></p>
                    												<p>Effects From &emsp;:&nbsp; <span class="changed_on_shift"></span></p>
                    												<p>Reason &emsp;&emsp;&emsp;&nbsp;:&nbsp; <span class ="shift_Changereason"></span></p>
                    											</div>
                    										</div>
                    									</div>
                        							</form>
                        							<hr>
                        							<div class="col-lg-12">
                        								<div id="change_History_shift"></div>
                        							</div>	
                    							</div>
                    				      </div>
            					    </div>
            					</div>             
								             <!------------ Modal for shift changes end ---------->	             
							
							<!-- Enroll modal starts here 
						<div class="modal"  id="modalBox" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                           <div class="modal-content">
                                               <div class="block block-themed block-transparent remove-margin-b">
		                                               <div class="modal-header">
													       			<h5 class="modal-title">Enrolment</h5>
															        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
															         <span aria-hidden="true">&times;</span> 
															        </button>
														       </div>
                                                    <div class="modal-body">
                                                                <div class="text-center">
													                 <img class="sign" src="../img/sign.png" style="width:120px;height:120px;" />
													                 <div class="time_show hide" style="margin:-35px 0px 0px 60px;"><span class="time_sign"></span></div> 
																</div>
                                                                 <div class="text-center">
                                                                     <button class="btn btn-sm btn-primary enrol_popup" type="button" id="enrol_popup" style="margin-top: 20px;">Capture</button>
                                                                     <button class="btn btn-sm btn-primary retry hide" type="button" style="margin-top: 20px;">Retry</button>
                                                                 </div>
                                                     </div>
                                                 </div>
                                 
                                            </div>
                                       </div>
                               </div>
                               <!-- Enroll modal end here -->
                               <!-- Enroll modal starts here -->
							<div class="modal"  id="modalBox" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm" style="width:750px">
                                           <div class="modal-content">
                                               <div class="block block-themed block-transparent remove-margin-b">
		                                               <div class="modal-header">
													       			<h5 class="modal-title">Enrol&nbsp;<span class="enrol_emp"></span></h5>
															        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
															         <span aria-hidden="true">&times;</span> 
															        </button>
														       </div>
                                                    <div class="modal-body" >
                                                    <form id="enrol_fp">
                                                    	 
                                                    	 <div class="row col-sm-12">
                                                    	 <input type="hidden" class="empIdShow" name="employeeId" />
                                                    	 <div class="col-sm-2">
                                                    	 
                                                    	 	<button type="button" id="enrol-fp"  class="btn btn-info btn-sm enrol-fp hide">
																      Enroll</button>
																      <button type="button" id="fp-1"  class="btn btn-info btn-sm enrol-again-fp hide" >
																      Enroll Again </button>
														</div>
														 <div class="col-sm-2">
															 <button type="button" id="save_enrol" data-fp="4" class="btn btn-warning btn-sm save_enrol hide">
																      <i class="fa fa-floppy-o" aria-hidden="true" style="color:#fff"></i>
																      Save from Device</button>
														</div>
                                                    	 <!--  	<p>Finger Prints
                                                    	 		<button type="button" id="enrol_again" class="btn btn-success btn-sm">
                                                    	 			<img class="enrol-loading" src="../img/undo-arrow(1).png" style="color:#fff;heigth: 12px;width: 12px;"/>
																			Enrol Again</button>
                                                              </p> -->
                                                       </div>
                                                          <br>
                                                          <br>
                                                        
															<div class="row col-sm-12">
															
																   <div class="col-sm-2 fp">
																   	  <p>FP-1</p>
																      
																      <b class="fp1 ">-</b>
																      <label  id="enrolled_fp"  class="label label-success label-sm enrolled_fp1 hide">
																      <i class="fa fa-check" aria-hidden="true" style="color:#fff"></i>
																      Enroled</label>
																   </div>
																   <div class="col-sm-2 fp">
																   	  <p>FP-2</p>
																   	  <b class="fp2 ">-</b>
																     
																       <label  id="enrolled_fp"  class="label label-success label-sm enrolled_fp2 hide">
																      <i class="fa fa-check" aria-hidden="true" style="color:#fff"></i>
																      Enroled</label>
																   </div>
																   <div class="col-sm-2 fp  ">
																   	   <p>FP-3</p>
																   	   <b class="fp3 ">-</b>
																     
																      <label  id="enrolled_fp3"  class="label label-success label-sm enrolled_fp3 hide">
																      <i class="fa fa-check" aria-hidden="true" style="color:#fff"></i>
																      Enroled</label>
																   </div>
																   <div class="col-sm-2 fp ">
																   		<p>FP-4</p>
																   		<b class="fp4 ">-</b>
																      
																      <label  id="enrolled_fp"  class="label label-success label-sm enrolled_fp4 hide">
																      <i class="fa fa-check" aria-hidden="true" style="color:#fff"></i>
																      Enroled</label>
																   </div>
																   <div class="col-sm-2 fp ">
																   		<p>FP-5</p>
																   		<b class="fp5 ">-</b>
																      
																      <label  id="enrolled_fp"  class="label label-success label-sm enrolled_fp5 hide">
																      <i class="fa fa-check" aria-hidden="true" style="color:#fff"></i>
																      Enroled</label>
																   </div>
																   
															</div>
														<br>
														<br>
														<div class="enrol-success"></div>	
														<div class =" ">
															<p class="pin" id="pin"><strong>User PIN : <span class="pin-val"></span></strong></p>
														</div>
								<div class="enrol-help-text">
										<div role="alert" class="" style="padding-top: 15px;"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding-top: 10px;"><span aria-hidden="true">x</span></button>
										<p><i class="fa fa-caret-right"></i> &nbsp;Clicking the Enroll/Enroll Again will initiate the Enrol process on the device. </p>
										<p><i class="fa fa-caret-right"></i> &nbsp;Make sure the employee stands near the device to record his/her finger print before inititiating this process.</p>
										<p><i class="fa fa-caret-right"></i> &nbsp;Once the process is completed in the device Click on the Save From Device button to save the recorded finger prints captured..</p>
										<p><i class="fa fa-caret-right"></i> &nbsp;Do not save when the enroll process is still undergoing in the device.</p>
										<p><i class="fa fa-caret-right"></i> &nbsp;Enroll Again will delete all the credentials of the employee from the device and re record them.</p>
										<p><i class="fa fa-caret-right"></i> &nbsp;Placing mehandhi, band-aid or other covering materials in the finger will lead the device to not recognize the clear fingerprint.
										If so do not initiate the process.</p>
										</div></div>
							   </div>
														
														
                                                      </form>
                                                   </div>
                                                  
                                                 </div>
                                 
                                            </div>
                                       </div>
                               </div>
                               <!-- Enroll modal end here -->
						</div>
						<!--Working Details Ends-->


						<!--Work proof Strats-->
						<div class="tab-pane" id="work_proof">
					<div id="loader" class="work_detail_loader" style="width: 96%"></div>
					<form enctype="multipart/form-data" class="form-horizontal"
					   id="emp_workProof_form" method="post" action="">
					  <div class="col-lg-12" >
					  	<button type="button" class="pull-right btn btn-sm  btn-danger back_emp_letter" style="margin-left: 1%;margin-top:10px;">Back</button>
					   	<button class="btn btn-sm btn-default pull-right" style="margin-top:10px;" type="button" id="lifeCyclePDF"><i class="fa fa-file-pdf-o"></i> PDF</button>
					  </div>
					<div class="work_proof panel-body" id="work_proof_1">
					<!-- letter panel starts here -->
							      <div class="clearfix">&nbsp;</div>
							  
								<!-- letter panel end here -->
								
								</div>
							</form>
						</div>
					<!--Work Proof Ends-->
				</div>

			</section>
		</aside>
	</div>
	<!-- page end-->
</section>
<script type="text/javascript">
     $(document).ready(function() {
    	 value='<?php echo substr($allowColums,0,-1);?>';
    	 $('#dateofexithide,.profile_id').hide();
    	 $('#slab_opt,#new_desig1,#new_dept1,#new_branch1,#new_team1,#new_status1,#new_shift1').chosen();
     	 $('#employee_nationality1,#job_status,#dept,#designation_id,#branch_loc,#payment_mode,#employee_blood_group1,#emp_sslc_board1,#emp_hsc_board1,#employee_id_proof_type1,#payment_mode1,#employee_bank_name').chosen();
     	 $(".change_des,.change_dept,.change_branch,.change_team,.change_job_status,.change_shift,#new_designation,#new_dept,#new_branch,#new_team,#new_status,#new_shift").hide();
         $(".male,.female,.other,.matrial1,.matrial2,.m_l,.m_l1,.special,.able,.foreign,.domestic,#emp_ad,#emp_ad1,.image_change,#empIdPrefix0,.SpanRupee,#hide_label_address").hide();
         $('span#spanPeriod,span.spanCal,span.spanCal2,span#spanDay,span#spanDay1,span#spanDay2,span#empIdPrefix1,.empIdPrefix1_edu,.work_detail,.work_detail_head,.career,.attach,.attach_edu,.emp_up1,.emp_cancel1,.emp_up2,.emp_cancel2,.emp_up3,.emp_cancel3,.emp_up4,.emp_cancel4,.emp_up5,.emp_cancel5,.emp_up6,.emp_cancel6,.emp_up7,.emp_cancel7,.for_address,.work_history,.ctc_detail,.ctc_detail_head,.work_proof,#matrial_status1,#emp_able,#inter_emp,#emp_address,#employee_blood_group1_chosen,#employee_id_proof_type1_chosen,#employee_nationality1_chosen,#employee_bank_name_chosen,#emp_designation_id,#emp_branch_id,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen,#payment_mode1_chosen,#mainBack').hide();
         $('.emp_edit1,.emp_edit2,.emp_edit3,.emp_edit4,.emp_edit5,.emp_edit6,.emp_edit7,.section.emp_list1').show();
		 $('.for_address,.career,.ctc_detail_head,.ctc_detail,.work_history,.work_detail_head,.work_detail,.work_proof').show();
		 
         $('#slab_change,#slap_name').hide();
         $("#emp_detail_form,#emp_address_form,#emp_edu_form,#emp_work_histroy_form,#emp_work_items ").find(":input").not("#wipeEmp,#enrollEmp,.back_emp,.emp_edit3,.emp_edit1,.emp_edit2,.emp_edit4,.emp_edit5,.emp_edit7").each(function () {
                   $(this).css({ 'background-color': '#FFF', 'border': '0px' });
             $(this).attr('disabled', true);
             $('.emp_edit5').css({ 'background-color': '#459626' });
             $('.emp_edit7').css({ 'background-color': '#459626' });
         });
      
         $("#mainBack").click(function () {
             $(".emp_list1").show();
             $(".emp_image_view").hide();
             $("#mainBack").show();
         });

        

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
     	 		  $('#leave_account_tabs a[href="#personal"]').tab('show');
     	 		  window.scrollTo(0, 0);
     	 		  } 

     	 	  // Change hash for page-reload
     	 	  $('#leave_account_tabs a').on('shown.bs.tab', function (e) {
     	 	      window.location.hash = e.target.hash;
     	 	      window.scrollTo(0, 0);
     	 	  })
     	 	});
 	 	
         /* tab Clicks Starts*/
         

         $('#leave_account_tabs').on('shown.bs.tab', function (e) {
       	   // newly activated tab 
       	 window.scrollTo(0, 0);
       	
       	  //if($(e.target).data('loaded') === false){
      		    if($(e.target).data('title') === 'personal_tabs'){
      			    
      		    }else if($(e.target).data('title') === 'work_tabs'){
      		    	 work_tabs();
      		    	$('#work_detail_tabs,#work_proof_tabs').data('loaded',true);
      		    	
      		    }else if($(e.target).data('title') === 'work_proof_tabs'){
      		    //work_tabs();
      		    	$('.work_proof').empty();
      		    	work_proof_tabs();
      		    	$('#work_tabs,#work_detail_tabs').data('loaded',true);
      		    }if($(e.target).data('title') === 'salary_tabs'){
      		    	$('.ctc_detail_loader_sub').loading(true);
   				   ctc_tabs();
      		    }
      			//}
      			//make the tab loaded true to prevent re-loading while clicking.
         		
       	$(e.target).data('loaded',true);
       	});
          
          /*tab clicks Ends*/   
         $('.emp_edit1').click(function () {
        	// $('#preview_image_').prop('src','');
        	 $('#preview_image_').empty();
             $("#employee_nationality1,#employee_bank_name,#employee_blood_group1,#job_status,#employee_id_proof_type1,.profile_id").prop('disabled',false).trigger('chosen:updated');
             $('span#empIdPrefix1,span.spanCal,.profile_id').show();
             $(".male,.female,.other,.matrial1,.matrial2,.m_l,.special,.able,.foreign,.domestic,#employee_id_proof_type1_chosen,#employee_blood_group1_chosen,#employee_nationality1_chosen,#designation_id_chosen,#department_id_chosen,#branch_id_chosen,.image_change,.emp_up1,.emp_cancel1,.attach,span#spanPeriod,#employee_bank_name_chosen").show();
             $('.emp_edit1,#matrial_status1,#emp_able,#inter_emp,#emp_designation_id,#emp_branch_id,#employee_nationality,#employee_blood_group,#employee_id_proof_type,#employee_marital_status,#employee_international,#employee_pc,.back_emp,.profileAl,#employee_gender,#employee_bank_name1,#employee_bank_name').hide();
             $('#emp_mother_mobile,#father_mobile,#employee_personal_mobile,#employee_mobile').removeClass('hide');
             $('.mmobile-emp,.fmobile-emp,.omobile-emp,.pmobile-emp').addClass('hide');
              radioButtons();
             $("#emp_detail_form").find(":input").not("#employee_id").each(function () {
                 $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                 $(this).attr('readonly', false);
                 $(this).removeAttr('disabled');

             });

         });
        // document.getElementById('yourimage').src = "url/of/image.jpg?random="+new Date().getTime();
            
   //Name validation only allow alphabets & space 
    $('#employee_name,#employee_lastname,#employee_father_name,#emp_mother_name,#spouse_name,#employee_aadhaar_name,#employee_bank_branch,#permanent_emp_city,#permanent_emp_dist,#permanent_emp_state,#permanent_emp_country,#employee_city,#employee_district,#employee_state,#emp_country').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z\b t]*$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)|| (e.keyCode) == 9) {
            return true;
        }
        else
        {
        e.preventDefault();
        return false;
        }
    });

   //Number validation
    $('#employee_mobile,#employee_personal_mobile,#emp_mother_mobile,#father_mobile,#spouse_mobile,#employee_acc_no,#employee_pin_code,#employee_aadhaar_id,#permanent_emp_pincode').keypress(function (event) {
        var keycode = event.which;
        if (!(keycode == 0 || event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
            event.preventDefault();
            return false;
        }
    });

    $('#employee_mobile,#employee_personal_mobile,#emp_mother_mobile,#father_mobile,#spouse_mobile').blur(function() {
    var mobNum = $(this).val();
    if(mobNum.length!=10){
        $(this).parent().find('.message').html('<label class="text-danger">Enter valid mobile number</label>');
    }else{    
    	$(this).parent().find('.message').empty();
    }
    }); 

    //do not allow special characters
    $('#employee_pan_no,#employee_bank_ifsc').keypress(function (event) {
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

  
         $('#emp_detail_form').on('submit', function (e) {
              e.preventDefault();
            
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
                  try{
					  data1 = JSON.parse(data);
                     if (data1[0] == "success") {
                    	 $("#employee_nationality1,#employee_bank_name,#employee_blood_group1,#job_status,#employee_id_proof_type1,.profile_id").prop('disabled', true).trigger('chosen:updated');
                         $('span#empIdPrefix1,span.spanCal,.emp_up1,.emp_cancel1,.attach,.profile_id').hide();
                         $(".male,.female,.other,.matrial1,.matrial2,.m_l,.special,.able,.foreign,.domestic,#employee_blood_group1_chosen,#employee_id_proof_type1_chosen,#employee_nationality1_chosen,.image_change,#employee_bank_name_chosen,#employee_bank_name").hide();
                         $('.emp_edit1,#matrial_status1,#emp_able,#inter_emp,#employee_nationality,#employee_blood_group,#employee_id_proof_type,#employee_marital_status,#employee_international,#employee_pc,.back_emp,.profileAl,#employee_gender,#employee_bank_name1').show();
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
                      }catch(e){
                    	 alert('Error Occurred. Try again Later');
                         }
                 }

             });


         });
         $('.emp_cancel1').click(function () {
        	  $('.message').empty();
             $("#employee_nationality1,#employee_bank_name,#employee_blood_group1,#job_status,#employee_id_proof_type1,.profile_id").prop('disabled', true).trigger('chosen:updated');
             $('span#empIdPrefix1,span.spanCal,.emp_up1,.emp_cancel1,.attach,.profile_id').hide();
             $(".male,.female,.other,.matrial1,.matrial2,.m_l,.special,.able,.foreign,.domestic,#employee_blood_group1_chosen,#employee_id_proof_type1_chosen,#employee_nationality1_chosen,.image_change,#employee_bank_name_chosen,#employee_bank_name").hide();
             $('.emp_edit1,#matrial_status1,#emp_able,#inter_emp,#employee_nationality,#employee_blood_group,#employee_id_proof_type,#employee_marital_status,#employee_international,#employee_pc,.back_emp,.profileAl,#employee_bank_name1,#employee_gender').show();
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
             $('.emp_edit2,#emp_address,.back_emp').hide();
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
                  try {
                     data1 = JSON.parse(data);
                     if (data1[0] == "success") {
                    	$(".emp_up2,.emp_cancel2,#emp_ad,#emp_ad1,.m_l1,#hide_label_address").hide();
                         $('.emp_edit2,#emp_address,.back_emp').show();
                         $("#emp_address_form").find(":input").not('.emp_edit2,.back_emp').each(function () {
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
                        
                       } catch (e) {
                	    	 alert('Error Occurred. Try again Later');
                	    }
                 }

             });


         });



         $('.emp_cancel2').click(function () {
             $(".emp_up2,.emp_cancel2,#emp_ad,#emp_ad1,.m_l1,#hide_label_address").hide();
             $('.emp_edit2,#emp_address,.back_emp').show();
             $("#emp_address_form").find(":input").not('.emp_edit2,.back_emp').each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });

             });
             radioButtons1();
             var employee_id = $('#emp_id_address').val();
             employee_fill(employee_id);
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
                  try {  
				    data1 = JSON.parse(data);
                     if (data1[0] == "success") {
                        $("#emp_sslc_board1,#emp_hsc_board1").prop('disabled', true).trigger('chosen:updated');
                         $(".emp_up3,.emp_cancel3,.empIdPrefix1_edu,.attach_edu,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen").hide();
                         $('.emp_edit3,#emp_sslc_board,#emp_hsc_board,.back_emp').show();
                         $("#emp_edu_form").find(":input").not('.emp_edit3,.back_emp').each(function () {
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
                    } catch (e) {
       	    	 alert('Error Occurred. Try again Later');
       	    		}
                 }

             });


         });


         $('.emp_edit3').click(function () {
             $("#emp_sslc_board1,#emp_hsc_board1").prop('disabled', false).trigger('chosen:updated');
             $(".emp_up3,.emp_cancel3,.empIdPrefix1_edu,.attach_edu,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen").show();
             $('.emp_edit3,#emp_sslc_board,#emp_hsc_board,.back_emp').hide();
             $("#emp_edu_form").find(":input").not("#employee_id,.emp_edit3").each(function () {
                 $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                 $(this).attr('readonly', false);
                 $(this).removeAttr('disabled');

             });
         });

         $('.emp_cancel3').click(function () {
             $("#emp_sslc_board1,#emp_hsc_board1").prop('disabled', true).trigger('chosen:updated');
             $(".emp_up3,.emp_cancel3,.empIdPrefix1_edu,.attach_edu,#emp_sslc_board1_chosen,#emp_hsc_board1_chosen").hide();
             $('.emp_edit3,#emp_sslc_board,#emp_hsc_board,.back_emp').show();
             $("#emp_edu_form").find(":input").not(' .emp_edit3,.back_emp').each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });

             });
             var employee_id = $('#emp_id_edu').val();
             employee_fill(employee_id);
         });

//For work Histry
         $('.addCompany').click(function () {
        	
         	var i=$('#addcompanyno').val();
         	$('.remove').removeClass('hide show');
         	$('.remove').removeClass('hide');  
         	$('#'+i+'_addCompanyContent').append('<div class="row"><div class="col-lg-12"><h5><b>Company-'+i+'</b></h5><hr style="margin-top: 0px;"><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Company Name</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_cName" /></div></div><div class="form-group"><label class="col-lg-5 control-label">Reporting Manager</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_reporting_manager" id="1_reporting_manager" /></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Designation</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_desig"/></div></div><div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7 "><input type="number" class="form-control" name="'+i+'_Ctc"/></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Contact Email</label><div class="col-lg-7 "><input type="email" class="form-control" name="'+i+'_contact_email" /></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Location</label><div class="col-lg-7 "><input class="form-control" name="'+i+'_location"  type="text"></div></div></div><div class="col-lg-10"> <div class="form-group"><label class="control-label col-lg-3">Duration</label><div class="col-md-7"><div class="input-group input-large" data-date="13/07/2013" data-date-format="mm/dd/yyyy"><input class="form-control dpd1 period" name="'+i+'_From" type="text"><span class="input-group-addon" style="background-color: #fff; border: 0px">To</span><input class="form-control dpd2 period" name="'+i+'_To" type="text"></div></div></div></div></div></div>');
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
              
          /*   $(this).datepicker({
                 format: 'mm-yyyy',
                 viewMode: "months",
                 minViewMode: "months"
          }).on('changeDate', function(e){
             	 if(e.viewMode == 'months')
               		  $(this).datepicker('hide');
          		  });*/
     		  
         });

         $('.back_emp_letter').on('click',function (){
        	 window.location.href = "../hr/employees.php";
           
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
                  try {
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
                         
                    
                 } catch (e) {
        	    	 alert('Error Occurred. Try again Later');
        	    }
                 }

             });


         });
         $('.emp_edit4').click(function () {
        	 $('.exp').empty();
             $(".emp_up4,.emp_cancel4").show();
             $('.emp_edit4,.back_emp').hide();
           $("#emp_work_histroy_form").find(":input").each(function () {
                 $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                 $(this).attr('readonly', false);
                 $(this).removeAttr('disabled');
             });
         });

         
         $('.emp_cancel4').click(function () {
        	 work_tabs();
        	 $(".emp_edit4,.emp_cancel4,.back_emp").show();
             $('.emp_up4,.emp_cancel4').hide();
             $("#emp_work_histroy_form").find(":input").not(".emp_edit4,.back_emp").each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });

             });
             var employee_id = $('#emp_id_personal').val();
             employee_fill(employee_id);
            
         });
         //End Of Work histroy
         
         $('.emp_edit5').click(function () {
             var cur_payroll_month='<?php echo  $_SESSION['current_payroll_month']?>';
             var y = cur_payroll_month.substring(0,4);
             var m = cur_payroll_month.substring(5,7);
             var payroll_month = m+"/"+y;
             var date_of_join = $('#employee_doj').val();
             var d_o_j = date_of_join.substring(3,10);
			 $('.help-texts').css('display','none');
             $("#employee_doj,#employee_probation_period,#employee_confirmation_date").prop('disabled',true).trigger('chosen:updated');	
             $("#payment_mode1").prop('disabled', false).trigger('chosen:updated');	
             $(".emp_up5,.emp_cancel5,span#spanDay1,span#spanDay2,#payment_mode1_chosen,#pf_limit2").show();
             $('.emp_edit5,#payment_mode_name,#pf_limit1,.back_emp,#wipeEmp,#enrollEmp').hide();
             $(".change_des,.change_dept,.change_branch,.change_team,.change_job_status,.change_shift").show();
			
			 $("#emp_work_items").find(":input").not('.spanCal2,.emp_edit5,#designation_id1,#department_name,#branch_name,#team_name,#status_name,#shift_name').each(function () {
					$(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
	                $(this).removeAttr('readonly');
	                $(this).removeAttr('disabled');
				 });
				
			 if(payroll_month == d_o_j){
				    $('.spanCal2,span#spanDay').show();
					$("#employee_doj,#employee_probation_period,#employee_confirmation_date").prop('disabled', false).trigger('chosen:updated');
					$("#employee_doj,#employee_probation_period,#employee_confirmation_date").removeAttr('readonly');
					$("#employee_doj,#employee_probation_period,#employee_confirmation_date").removeAttr('disabled');
				}else{
						$('.spanCal2,span#spanDay').hide();
						$("#employee_doj,#employee_probation_period,#employee_confirmation_date").prop('disabled', true).trigger('chosen:updated');
						$("#employee_doj,#employee_probation_period,#employee_confirmation_date").attr('readonly');
						$("#employee_doj,#employee_probation_period,#employee_confirmation_date").attr('disabled');
						}
      });


      /*  $(window).click(function() {
        	//Hide the menus if visible
        	 $('.change_des,.change_dept').button('reset');
        	});*/
        	 
        			                	 
        //date format for changing fields
         $('#design_effects_from,#dept_effects_from,#branch_effects_from,#status_effects_from,#team_effects_from').datetimepicker({
        	format:'DD/MM/YYYY',
        	minDate:new Date(),
        	viewDate:new Date(),
        	useCurrent: false,
         	maxDate:false
    	 });

		/*********************for changing designation******************************/
         $('.change_des').on('click',function(e){
        	 e.preventDefault();
        	 $('#design_effects_from').val('');
        	 $('.desig_change_reason').val('');
        	 $('.designation_info').modal('show');
        	 var empName =$(this).parent().parent().find('.employeeName').html();
        	 $('.emp_name_id').html(empName);
        	 var empID =$(this).parent().parent().find('.empIdShow').html();
        	
            $.ajax({
            	 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!getCurrentDesig_Info");?>', employeeId:empID },
                 beforeSend:function(){
                 	$('.change_des').button('loading'); 
                  },
                  complete:function(){
                 	 $('.change_des').button('reset');
                  },
                 success: function (data) {
                     data1 = JSON.parse(data);
                         $('.current_desig').html(data1[2][0].designation_name);
                         $('.changed_on').html(data1[2][0].design_effects_from);
                        // $('#design_effects_from').val(data1[2][0].design_effects_from);
                        
                         if(data1[2][0].promotion_id =='NA'){
                              $('.purpose_desig').html('Employee Details');
                             }else{
                              $('.purpose_desig').html(data1[2][0].promotion_id);
                               }
                         $('.desig_Changereason').html(data1[2][0].designation_change_reason);
                        }
                 });

                $.ajax({
               	 dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!DesigChangeHistory");?>', employeeId:empID },
                    beforeSend:function(){
                    	$('.change_des').button('loading'); 
                     },
                     complete:function(){
                    	 $('.change_des').button('reset');
                     },
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if(data1[2].length!=0){
                        var html='';
                        $('#change_History_design').empty();
                        var html = '<section id="flip-scroll"><h4 style="text-align:center">Change History</h4><table class="table tab-content tasi-tab table-striped table-hover table-bordered desig_his1 dataTable " id="Change_history_design1" style="width:100%;overflow:hidden;"> <thead class="desig_his"><tr> <th>Designation</th><th>From</th><th>TO</th><th>Change Reason</th></tr></thead><tbody>';               	                	  
                        for (var i = 0, len = data1[2].length; i < len; i++) {
                    		   html += '<tr class="desig_his_data">';
                    		   $.each(data1[2][i], function (k, v) {
   	                  		   if(k == 'designation_name'){
   	                  			 html +='<td>'+v+'</td>';
   	                  		   }
   	                  		   if(k == 'effects_from'){
   	   	                  		   var dayy =v;
   	   	                  		   var day = new Date(dayy);
           	   	               	   var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
           	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
           	   	          	 						];
   	                  			  var y = dayy.substring(0, 4);
   	                  		 	  var date=dayy.substring(8,10);
   	                  	 		  var m =  monthNames [day.getMonth()];
   	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
   	                  		   }
                    			 	if(k == 'effects_upto'){
                    			 		 var dayy =v;
                    			 		if(dayy != '0000-00-00'){
         	   	                  		 var day = new Date(dayy);
                 	   	               	 var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                 	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                 	   	          	 						];
         	                  			 var y = dayy.substring(0, 4);
         	                  		 	 var date=dayy.substring(8,10);
         	                  	 		 var m =  monthNames [day.getMonth()];
         	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
                    			 		}else{
                    			 			html +='<td>-</td>';
                        			 		}
                    			 	}
                    			 	if(k == 'designation_change_reason'){
                    			 			html +='<td>'+v+'</td>';
                    			 		} 		
                    		 }); 
                       	  html += '</tr>';
                       	 
                        }
                        html += '</tbody> </table></section></form>';
                         //append table 
                          $('#change_History_design').html(html);
                           }else{
                        	   $('#change_History_design').empty();
                               }
                    }
                    });
            
             
             });

        
         $('#desig_form').on('submit',function(e){
        	 e.preventDefault();
        	 var empID =$(this).find('.empIdShow').html();
        	 var designationID = $("#new_desig1 option:selected").val();
        	 $('.des_id').val(designationID);
        	 var designationID_1 = $("#new_desig1 option:selected").html();
        	 $('#designation_id1').val(designationID_1);
        	 var changeReason = $('.desig_change_reason').val();
        	 var designEffectsFrom =$('#design_effects_from').val(); 
        	  $.ajax({
             	 dataType: 'html',
                  type: "POST",
                  url: "php/employee.handle.php",
                  cache: false,
                  data:{  act:  ' <?php echo base64_encode($_SESSION['company_id']."!UpdateDesignation");?>', employeeId:empID,newDesignation:designationID,desigChangeReason:changeReason,designation_effects_from:designEffectsFrom },
                  beforeSend:function(){
                  	$('.changeDes').button('loading'); 
                  	$('.changeDes').css('background-color','#00A8B3'); 
                   },
                   complete:function(){
                	  
                  	 $('.changeDes').button('reset');
                   },
                  success: function (data) {
                	  data1 = JSON.parse(data);
                	  $('.designation_info').modal('hide');
                   // window.location.href ="../hr/employees.php?employeeID="+empID;
                  }
                  });
             });
     /****************** Designation changes end here***************/
    /******************* Department changes starts here*************/
           $('.change_dept').on('click',function(e){
               $('#dept_effects_from').val('');
               $('.dept_change_reason').val('');
        	   $('.department_info').modal('show');
        	   var empName =$(this).parent().parent().find('.employeeName').html();
          	   $('.emp_name_id').html(empName);
          	   var empID =$(this).parent().parent().find('.empIdShow').html();
          	  
        	   $.ajax({
              	 dataType: 'html',
                   type: "POST",
                   url: "php/employee.handle.php",
                   cache: false,
                   data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!getCurrentDepartment_Info");?>', employeeId:empID },
                   beforeSend:function(){
                   	$('.change_dept').button('loading');
                    },
                    complete:function(){
                   	 $('.change_dept').button('reset');
                    },
                   success: function (data) {
                       data1 = JSON.parse(data);
                           $('.current_dept').html(data1[2][0].department_name);
                           $('.changed_on_dept').html(data1[2][0].depart_effects_from);
                           //$('#dept_effects_from').val(data1[2][0].depart_effects_from);
                           $('.dept_Changereason').html(data1[2][0].department_change_reason);
                          }
                   });

        	   $.ajax({
                 	 dataType: 'html',
                      type: "POST",
                      url: "php/employee.handle.php",
                      cache: false,
                      data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!DeptChangeHistory");?>', employeeId:empID },
                      beforeSend:function(){
                      	$('.change_dept').button('loading'); 
                       },
                       complete:function(){
                      	 $('.change_dept').button('reset');
                       },
                      success: function (data) {
                          data1 = JSON.parse(data);
                          if(data1[2].length!=0){
                          var html='';
                          $('#change_History_deptmnt').empty();
                          var html = '<section id="flip-scroll"><h4 style="text-align:center">Change History</h4><table class="table tab-content tasi-tab table-striped table-hover table-bordered depart dataTable " id="Change_history_depart1" style="width:100%;overflow:hidden;"> <thead class="depart_his"><tr> <th>Department</th><th>From</th><th>TO</th><th>Change Reason</th></tr></thead><tbody>';               	                	  
                          for (var i = 0, len = data1[2].length; i < len; i++) {
                      		   html += '<tr class="dept_his_data">';
                      		   $.each(data1[2][i], function (k, v) {
     	                  		   if(k == 'department_name'){
     	                  			 html +='<td>'+v+'</td>';
     	                  		   }
     	                  		   if(k == 'effects_from'){
     	   	                  		   var dayy =v;
     	   	                  		   var day = new Date(dayy);
             	   	               	   var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
             	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
             	   	          	 						];
     	                  			  var y = dayy.substring(0, 4);
     	                  		 	  var date=dayy.substring(8,10);
     	                  	 		  var m =  monthNames [day.getMonth()];
     	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
     	                  		   }
                      			 	if(k == 'effects_upto'){
                      			 		 var dayy =v;
                      			 		 
                      			 		if(dayy != '0000-00-00'){
           	   	                  		 var day = new Date(dayy);
                   	   	               	 var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                   	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                   	   	          	 						];
           	                  			 var y = dayy.substring(0, 4);
           	                  		 	 var date=dayy.substring(8,10);
           	                  	 		 var m =  monthNames [day.getMonth()];
           	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
                      			 		}else{
                      			 			html +='<td>-</td>';
                          			 		}
                      			 	}
                      			 	if(k == 'department_change_reason'){
                      			 			html +='<td>'+v+'</td>';
                      			 		} 		
                      		 }); 
                         	  html += '</tr>';
                         	 
                          }
                          html += '</tbody> </table></section></form>';
                           //append table 
                            $('#change_History_deptmnt').html(html);
                             }else{
                          	   $('#change_History_deptmnt').empty();
                                 }
                      }
                      });
          	   
               });

           $('#dept_form').on('submit',function(e){
          	 e.preventDefault();
          	 var empID =$(this).find('.empIdShow').html();
          	 var departmentID = $("#new_dept1 option:selected").val();
          	 var departmentID_1 = $("#new_dept1 option:selected").html();
          	 $('#department_name').val(departmentID_1);
          	 var deptChangeReason = $('.dept_change_reason').val();
          	 var deptEffectsFrom =$('#dept_effects_from').val(); 
          	  $.ajax({
               	 dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data:{act: "<?php echo base64_encode($_SESSION['company_id']."!UpdateDepartment");?>" ,employeeId:empID,newDepartment:departmentID,deptChangeReason:deptChangeReason,dept_effects_from:deptEffectsFrom},
                    beforeSend:function(){
                    	$('.changeDept').button('loading');
                    	$('.changeDept').css('background-color','#00A8B3');  
                     },
                     complete:function(){
                    	 $('.changeDept').button('reset');
                     },
                    success: function (data) {
                  	  data1 = JSON.parse(data);
                  	 $('.department_info').modal('hide');
                        
                    }
                    });
               });
          
  /******************** Department changes starts here *********************/
   /********************* for changing Branch ******************************/
  
         $('.change_branch').on('click',function(e){
        	 e.preventDefault();
        	 $('#branch_effects_from').val('');
        	 $('.branch_change_reason').val('');
        	 $('.branch_info').modal('show');
        	 var empName =$(this).parent().parent().find('.employeeName').html();
        	 $('.emp_name_id').html(empName);
        	 var empID =$(this).parent().parent().find('.empIdShow').html();
        	 
        	 $.ajax({
            	 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!getCurrentBranch_Info");?>', employeeId:empID },
                 beforeSend:function(){
                 	$('.change_branch').button('loading'); 
                  },
                  complete:function(){
                 	 $('.change_branch').button('reset');
                  },
                 success: function (data) {
                     data1 = JSON.parse(data);
                         $('.current_branch').html(data1[2][0].branch_name);
                         $('.changed_on_branch').html(data1[2][0].branch_effects_from);
                        // $('#branch_effects_from').val(data1[2][0].branch_effects_from);
                         if(data1[2][0].tranfer_id){
                              $('.purpose_branch').html(data1[2][0].tranfer_id);
                             }
                          $('.branch_Changereason').html(data1[2][0].branch_change_reason);
                        }
                 });

        	 $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data: {act:'<?php echo base64_encode($_SESSION['company_id']."!BranchChangeHistory");?>',employeeId:empID },
                    beforeSend:function(){
                    	$('.change_branch').button('loading'); 
                     },
                     complete:function(){
                    	 $('.change_branch').button('reset');
                     },
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if(data1[2].length!=0){
                        var html='';
                        $('#change_History_branch').empty();
                        var html = '<section id="flip-scroll"><h4 style="text-align:center">Change History</h4><table class="table tab-content tasi-tab table-striped table-hover table-bordered dataTable branch_his1" id="Change_history_branch1" style="width:100%;overflow:hidden;"> <thead class="branch_his"><tr> <th>Branch</th><th>From</th><th>TO</th><th>Change Reason</th></tr></thead><tbody>';               	                	  
                        for (var i = 0, len = data1[2].length; i < len; i++) {
                    		   html += '<tr class="branch_his_data">';
                    		   $.each(data1[2][i], function (k, v) {
   	                  		   if(k == 'branch_name'){
   	                  			 html +='<td>'+v+'</td>';
   	                  		   }
   	                  		   if(k == 'effects_from'){
   	   	                  		   var dayy =v;
   	   	                  		   var day = new Date(dayy);
           	   	               	   var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
           	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
           	   	          	 						];
   	                  			  var y = dayy.substring(0, 4);
   	                  		 	  var date=dayy.substring(8,10);
   	                  	 		  var m =  monthNames [day.getMonth()];
   	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
   	                  		   }
                    			 	if(k == 'effects_upto'){
                    			 		 var dayy =v;
                    			 		if(dayy != '0000-00-00'){
         	   	                  		 var day = new Date(dayy);
                 	   	               	 var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                 	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                 	   	          	 						];
         	                  			 var y = dayy.substring(0, 4);
         	                  		 	 var date=dayy.substring(8,10);
         	                  	 		 var m =  monthNames [day.getMonth()];
         	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
                    			 		}else{
                    			 			html +='<td>-</td>';
                        			 		}
                    			 	}
                    			 	if(k == 'branch_change_reason'){
                    			 			html +='<td>'+v+'</td>';
                    			 		} 		
                    		 }); 
                       	  html += '</tr>';
                       	 
                        }
                        html += '</tbody> </table></section></form>';
                         //append table 
                          $('#change_History_branch').html(html);
                           }else{
                        	   $('#change_History_branch').empty();
                               }
                    }
                    });
             
         });
         $('#branch_form').on('submit',function(e){
          	 e.preventDefault();
          	 var empID =$(this).find('.empIdShow').html();
          	 var branchID = $("#new_branch1 option:selected").val();
          	 var branchname_1 = $("#new_branch1 option:selected").html();
          	 $('#branch_name').val(branchname_1);
          	 var branchChangeReason = $('.branch_change_reason').val();
          	 var branchEffectsFrom =$('#branch_effects_from').val(); 
          	  $.ajax({
               	 dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data:{act: '<?php echo base64_encode($_SESSION['company_id']."!UpdateBranch");?>' ,employeeId:empID,
                        newBranch:branchID,branchChangeReason:branchChangeReason,branch_effects_from:branchEffectsFrom},
                    beforeSend:function(){
                    	$('.changeBranch').button('loading'); 
                    	$('.changeBranch').css('background-color','#00A8B3'); 
                     },
                     complete:function(){
                    	 $('.changeBranch').button('reset');
                     },
                    success: function (data) {
                  	  data1 = JSON.parse(data);
                  	 $('.branch_info').modal('hide');
                    }
                    });
               });
         
      /******************** Branch changes end *********************/
      /********************* for changing Team ******************************/
  
         $('.change_team').on('click',function(e){
        	 e.preventDefault();
        	 $('#team_effects_from').val('');
        	 $('.team_change_reason').val('');
        	 $('.team_info').modal('show');
        	 var empName =$(this).parent().parent().find('.employeeName').html();
        	 $('.emp_name_id').html(empName);
        	 var empID =$(this).parent().parent().find('.empIdShow').html();
        	 
        	 $.ajax({
            	 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!getCurrentTeam_Info");?>', employeeId:empID },
                 beforeSend:function(){
                 	$('.change_team').button('loading'); 
                  },
                  complete:function(){
                 	 $('.change_team').button('reset');
                  },
                 success: function (data) {
                     data1 = JSON.parse(data);
                         $('.current_team').html(data1[2][0].team_name);
                         $('.changed_on_team').html(data1[2][0].team_effects_from);
                         //$('#team_effects_from').val(data1[2][0].team_effects_from);
                         $('.team_Changereason').html(data1[2][0].team_change_reason);
                        }
                 });

        	 $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data: {act:'<?php echo base64_encode($_SESSION['company_id']."!TeamChangeHistory");?>',employeeId:empID },
                    beforeSend:function(){
                    	$('.change_team').button('loading'); 
                     },
                     complete:function(){
                    	 $('.change_team').button('reset');
                     },
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if(data1[2].length!=0){
                        var html='';
                        $('#change_History_team').empty();
                        var html = '<section id="flip-scroll"><h4 style="text-align:center">Change History</h4><table class="table tab-content tasi-tab table-striped table-hover table-bordered dataTable team_his1" id="Change_history_team1" style="width:100%;overflow:hidden;"> <thead class="team_his"><tr> <th>Team</th><th>From</th><th>TO</th><th>Change Reason</th></tr></thead><tbody>';               	                	  
                        for (var i = 0, len = data1[2].length; i < len; i++) {
                    		   html += '<tr class="team_his_data">';
                    		   $.each(data1[2][i], function (k, v) {
   	                  		   if(k == 'team_name'){
   	                  			 html +='<td>'+v+'</td>';
   	                  		   }
   	                  		   if(k == 'effects_from'){
   	   	                  		   var dayy =v;
   	   	                  		   var day = new Date(dayy);
           	   	               	   var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
           	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
           	   	          	 						];
   	                  			  var y = dayy.substring(0, 4);
   	                  		 	  var date=dayy.substring(8,10);
   	                  	 		  var m =  monthNames [day.getMonth()];
   	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
   	                  		   }
                    			 	if(k == 'effects_upto'){
                    			 		 var dayy =v;
                    			 		if(dayy != '0000-00-00'){
         	   	                  		 var day = new Date(dayy);
                 	   	               	 var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                 	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                 	   	          	 						];
         	                  			 var y = dayy.substring(0, 4);
         	                  		 	 var date=dayy.substring(8,10);
         	                  	 		 var m =  monthNames [day.getMonth()];
         	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
                    			 		}else{
                    			 			html +='<td>-</td>';
                        			 		}
                    			 	}
                    			 	if(k == 'team_change_reason'){
                    			 			html +='<td>'+v+'</td>';
                    			 		} 		
                    		 }); 
                       	  html += '</tr>';
                       	 
                        }
                        html += '</tbody> </table></section></form>';
                         //append table 
                          $('#change_History_team').html(html);
                           }else{
                        	   $('#change_History_team').empty();
                               }
                    }
                    });
             
         });
         $('#team_form').on('submit',function(e){
          	 e.preventDefault();
          	 var empID =$(this).find('.empIdShow').html();
          	 var teamID = $("#new_team1 option:selected").val();
          	 var teamname_1 = $("#new_team1 option:selected").html();
          	 $('#team_name').val(teamname_1);
          	 var teamChangeReason = $('.team_change_reason').val();
          	 var teamEffectsFrom =$('#team_effects_from').val(); 
          	  $.ajax({
               	 dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data:{act: '<?php echo base64_encode($_SESSION['company_id']."!UpdateTeam");?>' ,employeeId:empID,
                        newTeam:teamID,teamChangeReason:teamChangeReason,team_effects_from:teamEffectsFrom},
                    beforeSend:function(){
                    	$('.changeTeam').button('loading'); 
                    	$('.changeTeam').css('background-color','#00A8B3'); 
                     },
                     complete:function(){
                    	 $('.changeTeam').button('reset');
                     },
                    success: function (data) {
                  	  data1 = JSON.parse(data);
                  	 $('.team_info').modal('hide');
                    }
                    });
               });
         
      /******************** team changes end *********************/
       /********************* job status changes starts here ******************************/
  
         $('.change_job_status').on('click',function(e){
        	 e.preventDefault();
        	 $('#status_effects_from').val('');
        	 $('.status_change_reason').val('');
        	 $('.job_status_info').modal('show');
        	 var empName =$(this).parent().parent().find('.employeeName').html();
        	 $('.emp_name_id').html(empName);
        	 var empID =$(this).parent().parent().find('.empIdShow').html();

        	 $.ajax({
            	 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!getCurrentjob_status");?>', employeeId:empID },
                 beforeSend:function(){
                 	$('.change_job_status').button('loading'); 
                  },
                  complete:function(){
                 	 $('.change_job_status').button('reset');
                  },
                 success: function (data) {
                     data1 = JSON.parse(data);
                         $('.current_jobstatus').html(data1[2][0].status_name);
                         $('.changed_on_jobstatus').html(data1[2][0].job_status_effects_from);
                        // $('#status_effects_from').val(data1[2][0].job_status_effects_from);
                         $('.job_status_Changereason').html(data1[2][0].job_status_change_reason);
                        }
                 });

        	 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: {act:'<?php echo base64_encode($_SESSION['company_id']."!JobstatusChangeHistory");?>',employeeId:empID },
                 beforeSend:function(){
                 	$('.change_job_status').button('loading'); 
                  },
                  complete:function(){
                 	 $('.change_job_status').button('reset');
                  },
                 success: function (data) {
                     data1 = JSON.parse(data);
                     if(data1[2].length!=0){
                     var html='';
                     $('#change_History_status').empty();
                     var html = '<section id="flip-scroll"><h4 style="text-align:center">Change History</h4><table class="table tab-content tasi-tab table-striped table-hover table-bordered dataTable job_status_his1" id="Change_history_job_status1" style="width:100%;overflow:hidden;"> <thead class="job_status_his"><tr> <th>Status</th><th>From</th><th>TO</th><th>Change Reason</th></tr></thead><tbody>';               	                	  
                     for (var i = 0, len = data1[2].length; i < len; i++) {
                 		   html += '<tr class="job_status_his_data">';
                 		   $.each(data1[2][i], function (k, v) {
	                  		   if(k == 'status_name'){
	                  			 html +='<td>'+v+'</td>';
	                  		   }
	                  		   if(k == 'effects_from'){
	   	                  		   var dayy =v;
	   	                  		   var day = new Date(dayy);
        	   	               	   var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        	   	          	 						];
	                  			  var y = dayy.substring(0, 4);
	                  		 	  var date=dayy.substring(8,10);
	                  	 		  var m =  monthNames [day.getMonth()];
	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
	                  		   }
                 			 	if(k == 'effects_upto'){
                 			 		 var dayy =v;
                 			 		if(dayy != '0000-00-00'){
      	   	                  		 var day = new Date(dayy);
              	   	               	 var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
              	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
              	   	          	 						];
      	                  			 var y = dayy.substring(0, 4);
      	                  		 	 var date=dayy.substring(8,10);
      	                  	 		 var m =  monthNames [day.getMonth()];
      	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
                 			 		}else{
                 			 			html +='<td>-</td>';
                     			 		}
                 			 	}
                 			 	if(k == 'job_status_change_reason'){
                 			 			html +='<td>'+v+'</td>';
                 			 		} 		
                 		 }); 
                    	  html += '</tr>';
                    	 
                     }
                     html += '</tbody> </table></section></form>';
                      //append table 
                       $('#change_History_status').html(html);
                        }else{
                     	   $('#change_History_status').empty();
                            }
                 }
                 });
         });
     	
         $('#job_status_form').on('submit',function(e){
          	 e.preventDefault();
          	 var empID =$(this).find('.empIdShow').html();
          	 var statusID = $("#new_status1 option:selected").val();
          	 var statusID_1 = $("#new_status1 option:selected").html();
          	 $('#status_name').val(statusID_1);
          	 var statusChangeReason = $('.status_change_reason').val();
          	 var statusEffectsFrom =$('#status_effects_from').val(); 
          	  $.ajax({
               	 dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data:{act: '<?php echo base64_encode($_SESSION['company_id']."!UpdatejobStatus");?>' ,employeeId:empID,
                        newStatus:statusID,statusChangeReason:statusChangeReason,status_effects_from:statusEffectsFrom},
                    beforeSend:function(){
                    	$('.changejobStatus').button('loading'); 
                    	$('.changejobStatus').css('background-color','#00A8B3'); 
                     },
                     complete:function(){
                    	 $('.changejobStatus').button('reset');
                     },
                    success: function (data) {
                  	  data1 = JSON.parse(data);
                  	$('.job_status_info').modal('hide');
                        
                    }
                    });
               });
        
        
         
      /********************Job status changes end *********************/
     /********************* shift changes starts here ******************************/
       
      $('.change_shift').on('click',function(e){
        	 e.preventDefault();
        	 $('#date_of_start').val('');
        	 $('.shift_change_reason').val('');
        	 $('.shift_info').modal('show');
        	 var empName =$(this).parent().parent().find('.employeeName').html();
        	 $('.emp_name_id').html(empName);
        	 var empID =$(this).parent().parent().find('.empIdShow').html();
        	
            $.ajax({
            	 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!getCurrentShift");?>', employeeId:empID },
                 beforeSend:function(){
                 	$('.change_shift').button('loading'); 
                  },
                  complete:function(){
                 	 $('.change_shift').button('reset');
                  },
                 success: function (data) {
                     data1 = JSON.parse(data);
                     if(data1[2] !=''){
                         $('.current_shift').html(data1[2][0].shift_name);
                         $('.new_shift').html(data1[2][0].shift_id);
                         $('.changed_on_shift').html(data1[2][0].from_date);
                        // $('#date_of_start').val(data1[2][0].from_date);
                         $('.shift_Changereason').html(data1[2][0].shift_change_reason);
                        }
                 	}
                 });


                $.ajax({
               	 dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!ShiftChangeHistory");?>', employeeId:empID },
                    beforeSend:function(){
                    	$('.change_shift').button('loading'); 
                     },
                     complete:function(){
                    	 $('.change_shift').button('reset');
                     },
                    success: function (data) {
                        data1 = JSON.parse(data);
                        //console.log(data1[2][0]);
                        if(data1[2].length!=0){
                        var html='';
                        $('#change_History_shift').empty();
                        var html = '<section id="flip-scroll"><h4 style="text-align:center">Change History</h4><table class="table tab-content tasi-tab table-striped table-hover table-bordered desig_his1 dataTable " id="Change_history_design1" style="width:100%;overflow:hidden;"> <thead class="desig_his"><tr> <th>Shift</th><th>From</th><th>TO</th><th>Change Reason</th></tr></thead><tbody>';               	                	  
                        for (var i = 0, len = data1[2].length; i < len; i++) {
                    		   html += '<tr class="desig_his_data">';
                    		
                    		   $.each(data1[2][i], function (k, v) {
   	                  		   if(k == 'shift_name'){
   	                  			 html +='<td>'+v+'</td>';
   	                  		   }
   	                  		   if(k == 'from_date'){
   	   	                  		   var dayy =v;
   	   	                  		   var day = new Date(dayy);
           	   	               	   var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
           	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
           	   	          	 						];
   	                  			  var y = dayy.substring(0, 4);
   	                  		 	  var date=dayy.substring(8,10);
   	                  	 		  var m =  monthNames [day.getMonth()];
   	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
   	                  		   }
                    			 	if(k == 'to_date'){
                    			 		 var dayy =v;
                    			 		if(dayy != '0000-00-00'){
         	   	                  		 var day = new Date(dayy);
                 	   	               	 var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                 	   	          	   						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                 	   	          	 						];
         	                  			 var y = dayy.substring(0, 4);
         	                  		 	 var date=dayy.substring(8,10);
         	                  	 		 var m =  monthNames [day.getMonth()];
         	                  			 html +='<td>'+date+' '+m+','+y+'</td>';
                    			 		}else{
                    			 			html +='<td>-</td>';
                        			 		}
                    			 	}
                    			 	if(k == 'shift_change_reason'){
                    			 			html +='<td>'+v+'</td>';
                    			 		} 
                    		 }); 
                    		
                       	  html += '</tr>';
                    		
                        }
                        html += '</tbody> </table></section></form>';
                         //append table 
                          $('#change_History_shift').html(html);
                       }else{
                        	   $('#change_History_shift').empty();
                               }
                    }
                    });
            
             
             });

      $( "#date_of_start" ).datetimepicker({
      	 format: 'DD/MM/YYYY',
      	 maxDate:false
      	// minDate: new Date()
       });
        
         $('#shift_form').on('submit',function(e){
        	 e.preventDefault();
        	 var empID =$(this).find('.empIdShow').html();
        	 var shiftID = $("#new_shift1 option:selected").val();
        	 var shiftID_1 = $("#new_shift1 option:selected").html();
        	 $('#shift_name').val(shiftID_1);
        	 var changeReason = $('.shift_change_reason').val();
        	 var dateOfstart =$('#date_of_start').val(); 
        	  $.ajax({
             	 dataType: 'html',
                  type: "POST",
                  url: "php/employee.handle.php",
                  cache: false,
                  data:{  act:  ' <?php echo base64_encode($_SESSION['company_id']."!updateShiftDetails");?>', employeeId:empID,newShift:shiftID,shiftChangeReason:changeReason,date_of_start:dateOfstart },
                  beforeSend:function(){
                  	$('.changeShift').button('loading'); 
                  	$('.changeShift').css('background-color','#00A8B3'); 
                   },
                   complete:function(){
                  	 $('.changeShift').button('reset');
                   },
                  success: function (data) {
                	  data1 = JSON.parse(data);
                	  $('.shift_info').modal('hide');
                  }
                  });
             });  	      
	 /********************shift changes end *********************/
         $('#emp_work_items').on('submit', function (e) {
        	 e.preventDefault();
             $("#emp_work_items input").prop("disabled", false);
             $.ajax({
                 processData: false,
                 contentType: false,
                 type: "POST",
                 url: "php/employee_update.php",
                 cache: false,
                 data: new FormData(this),
                 beforeSend:function(){
                    	$('.emp_up5').button('loading'); 
                     },
                     complete:function(){
                    	 $('.emp_up5').button('reset');
                     },
                 success: function (data) {
                   try {
                       data1 = JSON.parse(data);
                     if (data1[0] == "success") {
                        /*Update Confirmation box on work Details Ends*/
                         $("#payment_mode1").prop('disabled', true).trigger('chosen:updated');
                         $(".emp_up5,.emp_cancel5,.spanCal2,span#spanDay,span#spanDay1,span#spanDay2,#payment_mode1_chosen,#pf_limit2").hide();
                         $('.emp_edit5,#status_name,#department_name,#branch_name,#payment_mode_name,#pf_limit1,.back_emp,#shift_name').show();
                         $('#emp_edit5').attr('disabled', false);
                         $("#emp_work_items").find(":input").not(".back_emp,#wipeEmp,#enrollEmp").each(function () {
                             $(this).attr('readonly', true);
                             $('.emp_edit5').css({ 'background-color': '#459626' });
                             $(this).css({ 'background-color': '#FFF', 'border': '1px ' });

                         });
                         BootstrapDialog.alert(data1[1]);
					     
                     }
                     else
                         if (data1[0] == "error") {
                             alert(data1[1]);
                         }
                     } catch (e) {
        	    	 alert('Error Occurred. Try again Later');
        	    }
                   
                 }

             });
         });

        

         $('.emp_cancel5').click(function () {
        	 $('#enrollEmp').show();
             $("#payment_mode1").prop('disabled', true).trigger('chosen:updated');
             $(".change_des,.change_dept,.change_branch,.change_team,.change_job_status,.change_shift").hide();
             $(".emp_up5,.emp_cancel5,.spanCal2,span#spanDay,span#spanDay1,span#spanDay2,#payment_mode1_chosen,#pf_limit2").hide();
             $('.emp_edit5,#status_name,#department_name,#branch_name,#payment_mode_name,#pf_limit1,.back_emp,#wipeEmp,#shift_name').show();
             $("#emp_work_items").find(":input").not('.emp_edit5,.back_emp,#wipeEmp,#enrollEmp').each(function () {
                 $(this).attr('readonly', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });
                 $(this).attr('disabled', true);
             });
             var employee_id = $('#emp_work_id').val();
             work_tabs(employee_id);
         });

         $('input[type="checkbox"]').bind('click',function() {
             if($(this).is(':checked')) {
            	 $('#pf_limit1').val('YES');
              }else{
            	$('#pf_limit1').val('NO');
                  }
        });

         $('.emp_edit6').click(function () {
             $(".emp_up6,.emp_cancel6,.delete").show();
             $('.emp_edit6').hide();
             $("#emp_workProof_form").find(":input").each(function () {
                 $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                 $(this).attr('readonly', false);
                 $(this).removeAttr('disabled');

             });
         });
         $('.emp_up6').click(function () {
             $(".emp_up6,.emp_cancel6,.delete").hide();
             $('.emp_edit6').show();
             $("#emp_workProof_form").find(":input").each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '1px ' });
             });
         });
         $('.emp_cancel6').click(function () {
             $(".emp_up6,.emp_cancel6,.delete").hide();
             $('.emp_edit6').show();
             $("#emp_workProof_form").find(":input").each(function () {
                 $(this).attr('readonly', true);
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });

             });
             

         });




         $('#emp_ctc_form').on('submit', function (e) {
             e.preventDefault();
             $('#emp_ctc_form').find('input[type="text"]').each(function () {
                 if ($(this).attr('data-type') == 'rupee') {
                    $(this).val(deFormate($(this).val()));
                     }
              }); 
             $.ajax({
            	 dataType: 'html',
                 type: "POST",
                 url: "php/employee_update.php",
                 cache: false,
                 data: $('#emp_ctc_form').serialize(),
                 beforeSend:function(){
                 	$('.emp_up7').button('loading'); 
                  },
                  complete:function(){
                 	 $('.emp_up7').button('reset');
                  },
                 success: function (data) {
                  try {  
                     data1 = JSON.parse(data);
                     if (data1[0] == "success") {
                         $(".emp_up7,.emp_cancel7,.SpanRupee").hide();
                         $('.emp_edit7,#slap_name_val,#slap_type_div,#slap_name_div,.back_emp').show();
                         $('#slab_change,#slap_name').hide();
                         $("#emp_ctc_form").find(":input").not('.emp_edit7,.back_emp,input[type=radio][name=salary_based_on]').each(function () {
                             $(this).attr('disabled', true);
                             $(this).css({ 'background-color': '#FFF', 'border': '0px' });
                           });
                         var employee_id = $('#emp_ctc_id').val();
                         $('.ctc_detail_loader_sub').loading(true);
                         ctc_tabs();
                         BootstrapDialog.alert(data1[1]);
					     
                     }
                     
				 } catch (e) {
        	    	 alert('Error Occurred. Try again Later');
        	    }
                 }

             });


         });

       

       $('.emp_edit7').click(function (e) {
             e.preventDefault();
            $(".emp_up7,.emp_cancel7,.SpanRupee,#slab_change,#slap_name").show();
            $('.emp_edit7,#slap_name_val,#slap_type_div,#slap_name_div,.back_emp').hide();
             $("#emp_ctc_form").find(":input").each(function () {
                 $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                // $(this).attr('readonly', false);
                 $(this).removeAttr('disabled');
               });
             $('#slab_opt').prop('disabled', false);
             $('#ctcSalaryCalc,#noSlabCaulation').show();
             $("#slab_opt option[value='" + $(this).data('id')+ "']").prop("selected", true);
         	  $("#slab_opt").trigger("chosen:updated");
			//for showing slab type
         		slab_type();
          });

       $(document).on('change', "input[type=radio][name=salary_type],input[type=radio][name=input_type]", function (e){
		   e.preventDefault();
		   $('.hiddenSpan').show();
		   $('#slab_name,.showSpan').hide();
		   slab_type();
       });
       
		  function slab_type(){
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
              	$('#slab_opt').append('<option value="">Select</option>');
              	 for (i = 0; i < json_obj[2].length; i++)
              	 {
              	 	$('#slab_opt').append('<option id="' + json_obj[2][i]['slab_id'] + '" value="' + json_obj[2][i]['slab_id'] + '">' + json_obj[2][i]['slab_name'] + '</option>');
              	 } $('#slab_opt').append('<option value="Nil">No Slab</option>');
              	 	 $('#slab_opt').trigger('chosen:updated');
                }
	   		});
	   }

         $('.emp_cancel7').click(function () {
             $(".emp_up7,.emp_cancel7,.SpanRupee").hide();
             $('.emp_edit7,#slap_name_val,#slap_type_div,#slap_name_div,.back_emp').show();
             $('#slab_change,#slap_name').hide();
             $("#emp_ctc_form").find(":input").not('.emp_edit7,.back_emp,input[type=radio][name=salary_based_on]').each(function () {
                 $(this).attr('disabled', true);
                 $(this).css({ 'background-color': '#FFF', 'border': '0px' });
            });
             $('.ctc_detail_loader_sub').loading(true);
              ctc_tabs();
              var employee_id = $('#emp_ctc_id').val();
              $('.text').html('');
          });
       
         $("a.view").click(function (e) {
         var imag = $(this).find("input").val();
         if(imag!=='Nil' && imag!=='' ){
         $(this).prop( "disabled", false);
         $('#preview_imagemodel').attr('src', imag);
         $('#proof_download').prop('href', imag);
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

         /*radio button value starts*/
         $(".matrial1,.matrial2").change(function () {
             if ($(this).val() == '1') {
                 $('#employee_marital_status').val('Single');
             }else {
                 $('#employee_marital_status').val('Married');
                 $('#spouse_name_hide').show();
             }

         });

         /*radio button value starts*/
         $("#gender1,#gender2,#gender3").change(function () {
             if ($(this).val() == 'Male') {
                 $('#employee_gender').val('Male');
             } else if($(this).val() == 'Female'){
                 $('#employee_gender').val('Female');
               }else{
            	 $('#employee_gender').val('Trans');
                }

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
         $("#employee_bank_name").change(function () {
             $('#employee_bank_name1').val($("#employee_bank_name option:selected").text());
         });
         $("#employee_nationality1").change(function () {
             $('#employee_nationality').val($("#employee_nationality1 option:selected").text());
         });
        
         $("#payment_mode1").on("blur change", function () {
             $('#payment_mode_name').val($("#payment_mode1 option:selected").text()).trigger('chosen:updated');
         });

          $('#employee_dob,#employee_marriagedate,#father_dob,#mother_dob,#spouse_dob,#dob,#doj,#confirmation_date,#notice_date,#emp_id_date,#employee_doj,#employee_confirmation_date,#resignation_date,#off_ltr_issue_dt,#confirm_ltr_issue_dt,#contract_ltr_issue_dt').datetimepicker({
       	    format: 'DD/MM/YYYY'
           });
          $('#employee_id_proof_expiry').datetimepicker({
              maxDate:false,
          });

          
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
        	   minDate: moment("1970","YYYY"),
         });
        
        $('#doj').datetimepicker({
              format: 'DD/MM/YYYY',
              maxDate:false,
           	   minDate: new Date()
              
         });
         
         $('#probation_period').on('blur', function () {
             var date = $('#doj').val().split('/');
             date = new Date(date[2], date[1] - 1, date[0]);
             date.setDate(date.getDate() + parseInt($('#probation_period').val()) + 1);
             $("#confirmation_date").datetimepicker('update', date);
         });

         $('#employee_doj').datetimepicker({
              format: 'DD/MM/YYYY' }).on("changeDate", function (e) {
             e.preventDefault();
             var date = new Date(e.date);
             date.setDate(date.getDate() + parseInt($('#employee_probation_period').val()));
             $("#employee_confirmation_date").datepicker('update', date);
         });

         $('#employee_probation_period').on('blur', function () {
             var date = $('#employee_doj').val().split('/');
             date = new Date(date[2], date[1] - 1, date[0]);
             date.setDate(date.getDate() + parseInt($('#employee_probation_period').val()) + 1);
             $("#employee_confirmation_date").datepicker('update', date);
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
             $("#permanent_emp_dist").val($("#employee_district").val());
             $("#permanent_emp_country").val($("#emp_country").val());
             $(".per_add").attr("readonly", true);
         }
         else {
             $("#permanent_emp_bulidname,#permanent_emp_area,#permanent_emp_city,#permanent_emp_dist,#permanent_emp_pincode,#permanent_emp_state,#permanent_emp_country").val('');
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
                 $("#rep_man-id-e").val(ui.item.employee_id);
                 return false;
             },
             search: function () { $(this).addClass('spinner'); },
             open: function () { $(this).removeClass('spinner'); },
             change: function (event, ui) { if (ui.item == null) { $('#rep_man').val(""); $('#rep_man').val(""); } }
         })
                 .autocomplete("instance")._renderItem = function (ul, item) {
                     return $("<li>")
                      .append("<a>" + item.employee_name + " " + item.employee_lastname + " [" + item.employee_id + "] <br>" + [(item.employee_designation!='')?item.employee_designation+', ':' ' ] + [(item.employee_department!='')?item.employee_department + ", ":" " ]+ [(item.employee_branch!='')?item.employee_branch:" " ] + "</a>")
                     .appendTo(ul);
                 };
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
     /**********************************ADDRESS FUNCTION ENDS *****************************************/
     /**************************************Image Process*******************************************/
     function readImage(file,element,name) {

         var reader = new FileReader();
         var image = new Image();

         reader.readAsDataURL(file);
         reader.onload = function (_file) {
             image.src = _file.target.result; // url.createObjectURL(file);
             image.onload = function () {
                 var w = this.width,
                                                h = this.height,
                                                s = ~ ~(file.size / 1024);
                 if (w <= 133 && h <= 170 && s < 10000) {
                      $('employee_image_preview').attr('src', this.src);
                    
                 } else {
                
                     alert("Provide 133 x 170 Image");
                 }

             };
             image.onerror = function () {
                 alert('Invalid file type: ' + file.type);
             };
         };

     }

     
     $("#image").change(function (e) {
         if (this.disabled) return alert('File upload not supported!');
         var F = this.files;
         if (F && F[0]) for (var i = 0; i < F.length; i++) readImage(F[i]);
     });

     /*************************************image process Ends**************************************/

     $('#emp_profile_view').submit(function (e) {
         $('section.emp_image_view').show();
         e.preventDefault();
     });
     $('.back_emp').on('click', function () {
      window.history.pushState( {} , '', 'employees.php' );
         $('.panel-heading').show();
         $(".emp_image_view,#mainBack").hide();
         $(".emp_list1").show();
		   $('#employee_id-id').val('');
		    $('#rep_man-id,emp_name,image').val('');
			$("#empView").find('div').css("display", "block");
			$('#emp_detail_form')[0].reset();
			removeHash();
      }); 

     });

     $('#cancelCrop').on('click', function (e) {
    	 e.preventDefault();
    	 $('.close').click();  
    	 $('#imageexit').val(0);
    	var canvas = $("#preview_image")[0];
	        var context = canvas.getContext("2d");
	        $('.jcrop-holder img').attr('src','http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image');
	        context.clearRect(0, 0, 1200, 1000);    
	        document.getElementById("image_file").value = "";
   	   });
     
     
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
                    	   $('#round_preview_image').attr('src',"");
                           $('#round_preview_image').attr('src',data1[0]+"?"+(new Date()).getTime());
                           var id=$('#employee_id').val();
                           $("#"+id+" img.viewImageResize").attr("src",data1[0]+"?"+(new Date()).getTime());
                           $('#preview_image_').attr('src','');
                      		$('#preview_image_').attr('src',data1[0]+"?"+(new Date()).getTime());
                      
                      	 $('.emp_up1').click();
                       $('#imageName').val(data1[0]);
                       $('.close').click();
                       
                       }
                     }
               }); 
    	   }else{
         		$('.error').html('Please select a crop region and then press Upload').show();
               return false;
             	 }    
               });   

     $('#wipeEmp').on('click', function (e) {
    	   e.preventDefault();
    	  var empId = $(this).parent().find('.empIdShow').html();
    	   BootstrapDialog.show({
				type: BootstrapDialog.TYPE_SUCCESS,
				title: 'confirmation',
				message:'Are you sure you want to delete the employee ?' ,
					buttons: [{
					label: 'Delete',
					cssClass: 'btn-primary' ,
					action: function(dialogRef){
						$.ajax({
				    		  dataType: 'html',
				              type: "POST",
				              url: "php/employee.handle.php",
				              cache: false,
				              data: { act: '<?php echo base64_encode($_SESSION['company_id']."!wipeEmployee");?>', wipeempId: $('#emp_id_personal').val() },
				              success: function (data) {
				              data1 = JSON.parse(data);
				                window.location.assign("employees.php"); 
				               
				              }
				    	  });
					}
			
				},
    	   		{
				label: 'Cancel',
				cssClass: 'btn-default' ,
				action: function(dialogRef){
					window.location.href = "../hr/employees.php?employeeID="+empId;
				}
		
			}]
	      });
    	 
       });
     $(document).on('click', "#slabdata" ,function(){
    	 $('.hiddenSpan').show();
    	 $('.showSpan').hide();
    	});

 //Loading Popup for Enroll//
     $(document).on('click',"#enrollbutton", function (e) {
  	   e.preventDefault();
  	  // $('#modalBox').modal('show');
  	  $(".enrol-success").empty();
  	  $('.save_enrol,.enrol-fp,.enrol-again-fp').addClass('hide');
  	 $('.fp1,.fp2,.fp3,.fp4,.fp5').removeClass('hide');
 	 $('.enrolled_fp1,.enrolled_fp2,.enrolled_fp3,.enrolled_fp4,.enrolled_fp5').addClass('hide');
  	 var empName =$(this).parent().parent().parent().find('.employeeName').html();
	 var empID =$(this).parent().parent().parent().find('.empIdShow').html();
     $('.enrol_emp').html(empName);
     $.ajax({
 		   dataType: 'html',
           type: "POST",
           url: "php/employee.handle.php",
           cache: false,
           data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getEnrollStatus");?>', employeeId: empID },
           beforeSend:function(){
              	$('#enrollbutton').button('loading'); 
                },
           complete:function(){
                  $('#enrollbutton').button('reset');
                  },
           success: function (data) {
               
          	 var json_obj = $.parseJSON(data);
          	 if(json_obj[2][0].status=='1'){
              	 
          		$('.enrol-fp').addClass('hide');
             	$('.enrol-again-fp').removeClass('hide');
             	 if(json_obj[2][0].pin!=0){
             		 $('.pin-val').html(json_obj[2][0].pin);
             		
             	 }else{
             		 $('.pin').addClass('hide'); 
             	 }
             	
          	 if(json_obj[2][0].fp1 !='0'){
              	 $('.fp1').addClass('hide');
              	 $('.enrolled_fp1').removeClass('hide');
              	 $(".fp1").addClass('hide');
              }else{
         		 $('.fp-1').addClass('hide');
              	 $('.enrolled_fp1').addClass('hide');
              	 $(".fp1").removeClass('hide');
              }
              if(json_obj[2][0].fp2 !='0'){
              		 $('.fp2').addClass('hide');
                  	 $('.enrolled_fp2').removeClass('hide');
                  	 $(".fp2").addClass('hide');
               }else{
          		 $('.enrolled_fp2').addClass('hide');
              	 $(".fp2").removeClass('hide');
              	
              }
              if(json_obj[2][0].fp3 !='0'){
                  		 $('.fp3').addClass('hide');
                      	 $('.enrolled_fp3').removeClass('hide');
                      	 $(".fp3").addClass('hide');
               }else{
             		 $('.enrolled_fp3').addClass('hide');
                  	 $(".fp3").removeClass('hide');
                  	 
                  }
              if(json_obj[2][0].fp4 !='0'){
             		 $('.fp4').addClass('hide');
                  	 $('.enrolled_fp4').removeClass('hide');
                  	 $(".fp4").addClass('hide');
             	 }else{
             		 $('.enrolled_fp4').addClass('hide');
                  	 $(".fp4").removeClass('hide');
                  }
              if(json_obj[2][0].fp5 !='0'){
             		 $('.fp5').addClass('hide');
                  	 $('.enrolled_fp5').removeClass('hide');
                  	$(".fp5").addClass('hide');
             	 }else{
             		$('.enrolled_fp5').addClass('hide');
                  	$(".fp5").removeClass('hide');
                  }
         	 
              	 
          	 
           }else {
         	 $('.enrol-fp').removeClass('hide');
         	$('.enrol-again-fp').addClass('hide');
         	 if(json_obj[2][0].pin!=0){
         		 $('.pin-val').html(json_obj[2][0].pin);
         		
         	 }else{
         		 $('.pin').addClass('hide'); 
         	 }
               }
              }
       
       });
     });

   //Enrolling Fingerprint
     
     $(document).on('click',".enrol-fp",function(e){
         e.preventDefault();
         $('.enrol-again-fp').removeClass('hide');
         var target = $(this);
         var fp_count = $(this).data("fp");
         var empId = $(this).parent().parent().find('.empIdShow').html();
         enrollFunction(empId);
     });

     function enrollFunction(empId){
         $.ajax({
      		   dataType: 'html',
                type: "POST",
                url: "php/employee.handle.php",
                cache: false,
                data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enrollEmployee");?>',employeeId:empId },
                beforeSend:function(){
               	$('.enrol-fp,.enrol-again-fp').loading(true);
                     },
                complete:function(){
               	 $('.enrol-fp,.enrol-again-fp').loading(false);
                       },
                success: function (data) {
               	 var json_obj = $.parseJSON(data);
               	 if(json_obj[0]=="success"){
               	$('.save_enrol').removeClass('hide');
               	$('.enrol-fp').addClass('hide');
            	$('.enrol-success').empty().html("<div class='text-success'>Enroll Process is Initiated on the device, Ask the Employee to place the finger when prompted on device.Once All the fingerprints are successfully captured on device click save to Device.</div><br>");
               	 }else{
               		$('.enrol-success').empty().html("<div class='text-danger'>Error Occurred."+json_obj[2]+"</div><br>");
               	 }
                }
            
            });
        }

     function enrolStatuslFunction(empId){
    	 $.ajax({
  		   dataType: 'html',
            type: "POST",
            url: "php/employee.handle.php",
            cache: false,
            data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getEnrollStatus");?>', employeeId: empId },
            beforeSend:function(){
               	$('#enrollbutton').button('loading'); 
                 },
            complete:function(){
                   $('#enrollbutton').button('reset');
                   },
            success: function (data) {
               
                
           	 var json_obj = $.parseJSON(data);
           	 if(json_obj[2][0].status=='1'){
               	 
           		$('.enrol-fp').addClass('hide');
              	$('.enrol-again-fp').removeClass('hide');
              	$('#pin').val(json_obj[2][0].pin);
              	
           	 if(json_obj[2][0].fp1 !='0'){
               	 $('.fp1').addClass('hide');
               	 $('.enrolled_fp1').removeClass('hide');
               	 $(".fp1").addClass('hide');
               }else{
          		 $('.fp-1').addClass('hide');
               	 $('.enrolled_fp1').addClass('hide');
               	 $(".fp1").removeClass('hide');
               }
               if(json_obj[2][0].fp2 !='0'){
               		 $('.fp2').addClass('hide');
                   	 $('.enrolled_fp2').removeClass('hide');
                   	 $(".fp2").addClass('hide');
                }else{
           		 $('.enrolled_fp2').addClass('hide');
               	 $(".fp2").removeClass('hide');
               	
               }
               if(json_obj[2][0].fp3 !='0'){
                   		 $('.fp3').addClass('hide');
                       	 $('.enrolled_fp3').removeClass('hide');
                       	 $(".fp3").addClass('hide');
                }else{
              		 $('.enrolled_fp3').addClass('hide');
                   	 $(".fp3").removeClass('hide');
                   	 
                   }
               if(json_obj[2][0].fp4 !='0'){
              		 $('.fp4').addClass('hide');
                   	 $('.enrolled_fp4').removeClass('hide');
                   	 $(".fp4").addClass('hide');
              	 }else{
              		 $('.enrolled_fp4').addClass('hide');
                   	 $(".fp4").removeClass('hide');
                   }
               if(json_obj[2][0].fp5 !='0'){
              		 $('.fp5').addClass('hide');
                   	 $('.enrolled_fp5').removeClass('hide');
                   	$(".fp5").addClass('hide');
              	 }else{
              		$('.enrolled_fp5').addClass('hide');
                   	$(".fp5").removeClass('hide');
                   }
          	 
               	 
           	 
            }else {
          	 $('.enrol-fp').addClass('hide');
          	 $('#pin').val(json_obj[2][0].pin);
            	
                }
               }
        
        });
     }

     $(document).on('click',".enrol-again-fp",function(e){
         e.preventDefault();
         var target = $(this);
         var empId = $(this).parent().parent().find('.empIdShow').html();
         var fp_count = $(this).data("fp");
         enrollFunction(empId);
         
     });

   //Enrol save from device
     $(document).on('click',".save_enrol",function(e){
         e.preventDefault();
         var target = $(this);
         var fp_count = $(this).data("fp");
         var empId = $(this).parent().parent().find('.empIdShow').html();
         var save = 'true';
         $.ajax({
   		   dataType: 'html',
             type: "POST",
             url: "php/employee.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!saveEmployee");?>',employeeId:empId ,save:save },
             beforeSend:function(){
                	$('.save_enrol').button('loading'); 
                  },
             complete:function(){
                    $('.save_enrol').button('reset');
                    },
             success: function (data) {
            	 var json_obj = $.parseJSON(data);
            	 enrolStatuslFunction(empId)
            	 $('.enrol-fp').addClass('hide');
            	 if(json_obj[0]=="success"){
                	 
                	
                	$('.enrol-success').html("<div class='text-danger'>FingerPrint Saved Successfully</div><br>");
            	 }else{
                	 //enroll failed
            		 $('.enrol-success').html("<div class='text-danger'>FingerPrint Save Failed</div><br>");
                	 }
             }
         
         });
     });

     /* Enroll Access Ajax Call*/
    /* $(document).on('click',"#enrol_popup", function(e) {
    	 e.preventDefault();
    	 $.ajax({
 		   dataType: 'html',
           type: "POST",
           url: "php/employee.handle.php",
           cache: false,
           data: { act: '?php echo base64_encode($_SESSION['company_id']."!enrollEmployee");?>', enrollempId: $('#emp_id_personal').val() },
           beforeSend:function(){
              	$('#enrol_popup').button('loading'); 
              	
                },
          complete:function(){
               	 $('#enrol_popup').button('reset');
                },
           success: function (data) {
        	   var json_obj = $.parseJSON(data);
               $(".time_show").removeClass('hide'); 
          if(json_obj[0]=="success"){
              // Enroll success //
        	  var timer =5;
        	  var interval = setInterval(function() {
            	 timer--;
            	 $('.time_sign').text(timer+"s");
            	 if (timer === 0) {
                   $('.time_sign').html('<span class="fa fa-check fa-2x text-success" style="margin-left:-20px;"></span>');
        	       $("#enrol_popup").button('reset');
        	       $("#enrol_popup").text('Capture Again');
        	       clearInterval(interval);
               }
               i--;
             }, 1000)
            }else{
                // Enroll failure //
            	var timer =5;
            	var interval = setInterval(function() {
            	    timer--;
            	    $('.time_sign').text(timer+"s");
            	    if (timer === 0) {
            	    	$('.time_sign').empty();
            	    	$('.time_sign').append('<span class="fa fa-times fa-2x text-danger" style="margin-left:-20px;"></span>');
               	       	$("#enrol_popup").button('reset');
               	       	$('.retry').removeClass('hide');
               	     	$('.enrol_popup').addClass('hide');
                    	  clearInterval(interval);
            	    }
            	}, 1000);
                }
           }
     });  
    }); */
     
	/* Enroll retry */
     $(document).on('click',".retry,.capture_again ", function(e) {
         $('#enrol_popup').trigger('click');
     }); 

     $(document).on('change', "input[type=radio][name=salary_type],input[type=radio][name=input_type],#slab_opt", function () {
  	 	var salaryType=$("input[type=radio][name=salary_type]:checked").val();
  	    var inputType=$("input[type=radio][name=input_type]:checked").val();
  	    var slabType = $("#slab_opt option:selected").val();
  	     $('#getCTCcontent').html('');
  	     $('#slabloader').loading(true);
        	dataFill(salaryType,inputType);
   	      $('#slabloader').loading(false);
  	  });

     function dataFill(){
    	 var salaryType=$("input[name='salary_type']:checked").val();
		 var slabType = $("#slab_opt option:selected").val();
		 var inputType=$("input[name='input_type']:checked").val();
    	 if(salaryType =='ctc'){
   	        var  mischtml='';
   	    	 var miscAllow = <?php echo json_encode($miscAlloDeduArray['MP']) ?>;
   	    	 for (i = 0; i < miscAllow.length; i++) {
   	    		 mischtml+='<div class="form-group"><label class="col-lg-5 control-label">'+miscAllow[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control miscAlowDeduCtc" id="'+miscAllow[i].pay_structure_id+'"  name="allowances['+miscAllow[i].pay_structure_id+']"  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div>';
   	         }
   	         }
 if(slabType!='Nil')
        {
    	 	if(salaryType =='ctc' && inputType =='monthly'){
      	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="text"  id="Subctc" name="ctc" oninput="reFormate(this)" data-type="rupee" class="salaryValidate form-control" value="0"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;border-bottom: 0px solid #e5e5e5;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" data-type="rupee" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text" name="ctc_fixed_component"  data-type="rupee" oninput="reFormate(this)" id="ctc" class="form-control salaryValidate" value="0"/></div></div>';
      	     $('#getCTCcontent').html(varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label>');
      	     }else if(salaryType =='ctc' && inputType =='annual'){
      	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="text"  id="Subctc" name="ctc" data-type="rupee" class="form-control salaryValidate" value="0" oninput="reFormate(this)"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;border-bottom: 0px solid #e5e5e5;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" name="ctc_fixed_component" data-type="rupee" oninput="reFormate(this)" autocomplete="off" value="0" readonly type="text"></div></div></div></div></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="text" name="ctc_fixed_component" id="ctc" class="form-control salaryValidate" data-type="rupee" oninput="reFormate(this)"  value="0"/></div></div></div></div>';
      	     $('#getCTCcontent').html(varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label></div>');
      	     }else if(salaryType =='monthly' && inputType =='monthly'){
      	     $('#getCTCcontent').html('<div class="form-group"><label class="col-lg-5 control-label">Gross</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="ctc_fixed_component" data-type="rupee" id="ctc" value="0"/><input type="text" name="gross" id="gross" class="form-control salaryValidate" value="0"  data-type="rupee" oninput="reFormate(this)" /></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc">Calculate</button><label class="help-block text-danger text" id="error-text"></label></div></div>');
      	     }else if(salaryType =='monthly' && inputType =='annual'){
      	     $('#getCTCcontent').html('<div class="form-group"><label class="col-lg-5 control-label">Gross</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="hidden" name="ctc_fixed_component" data-type="rupee" id="ctc" value="0"/><input type="text" name="gross" id="gross" class="form-control salaryValidate" value="0"  data-type="rupee" oninput="reFormate(this)" /></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc">calculate</button><label class="help-block text-danger text" id="error-text"></label></div></div>');
      	    }else{
      	    	 $('#getCTCcontent,#getTablecontent').html('');
      	     }
        }else
      	    {
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
      	     }
  	   }
	   
   $(document).on('click', "#noSlabCaulation", function (e) {
       e.preventDefault();
       var parameters = {}; //declare the object
       parameters["act"] = '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>'; //set some value
       parameters["pfLimit"]=$('#pf_limit :selected').val();
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
       var ctc=deFormate($('#ctc').val());
       var gross=deFormate($('#gross').val());
       var slabId=$('#slab_opt :selected').val();
       var pfLimit=$('#pf_limit :selected').val();
       var isCTC=($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
       var isAnnual=($("input[type=radio][name=input_type]:checked").val()=='annual')?1:0;
       var checkIfexit=ctc+gross+slabId+pfLimit+isCTC+isAnnual;
       if(slabId){
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
       }else{
      	 $('#getTablecontent,#checkIfexit').html('');
           $('#error-text').html('Enter Required Fields');
      }
  });

   
      $(document).on('click',"#perq",function(){
	   //e.preventDefault();
			if($(this).is(":checked"))
				$('#perq_s').show();
			else 
				$('#perq_s').hide();

	});

   $(document).on('click', ".check", function (e) {
	   //get the parent row
	    //find input elemts and disable it other than check box, .not("input[type=""]")
	 	if($(this).is(":checked"))
	 		$(this).parent().parent().find(":input").not('input[type="checkbox"]').attr('disabled', false);
		else
			$(this).parent().parent().find(":input").not('input[type="checkbox"]').attr('disabled', true);
	   });
 
	$('#perq_cancel').on('click',function(e){
		e.preventDefault();
		$("#perqsdata").toggleClass('hide');

		});


	$(document).on('submit','#perq_s',function(e){
		e.preventDefault();
			$.ajax({
			          dataType: 'html',
			          type: "POST",
			          url: "php/employee.handle.php",
			          cache: false,
			          data:  $(this).serialize(),empId:$(".emp_new").serialize(),
			          
			          beforeSend: function () {
		               	$('#perq_map').button('loading'); 
		               },
		               complete: function () {
		            	 	$('#perq_map').button('reset');
		                },
				         success: function (data){
				        	 data1 = JSON.parse(data);
				        	 
				        	  if (data1[0] == "success") {
	                	 					BootstrapDialog.alert(data1[1]);
	                	 				 }else
	                		   				if (data1[0] == "error") {
	                              		 		alert(data1[1]);
	                         			  }
				          } 
			   });
	      });



	
$(document).on('click',"#lifeCyclePDF" ,function(e) {
		 e.preventDefault(); 
		 $('#sidebar').getNiceScroll().show();
  		//side Menu show
  		$('#container').removeClass('sidebar-closed');
  		$('#main-content').css('margin-left','210px');
  		$('#sidebar').css('margin-left','0px');
  		$('.sidebar-menu').css('display','block');
  		/* for v4.0.1
		$('#work_proof_1').css('display','block');
		$('#work_proof_1').css('margin','0 4px 8px 0');
		$('#work_proof_1').css('background-color','#efefef');
		$('#work_proof_1').css('padding',' .4em');*/
		 
		  html2canvas($("#work_proof_1"), {
			    onrendered: function(canvas) {  
			       var imgData = canvas.toDataURL(
			             'image/jpeg');              
			        var doc = new jsPDF('p', 'mm','a4');
			        doc.addImage(imgData, 'JPEG', 0, 0);
			        doc.save('Employee Life cycle.pdf');
			    }
			});
	  });
	
 </script>