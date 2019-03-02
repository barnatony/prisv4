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
<title>Companies</title>

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
.css {
	background-color: #FFF;
	border: 0px;
}
</style>
</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php include("header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">


				<section class="panel" id="header_hide">
					<div class="panel-body" style="padding: 2px;" id="body">
						<header class="panel-heading" style="border-color: #FFF;">
							Company View
							<div class="pull-right" style="margin-top: -5px;">
								<button type="button" class="btn btn-success" id="add_company_d">
									<i class="fa fa-plus"></i> Add Company
								</button>
							</div>
						</header>

						<div id="com_add" class="displayHide">
							<form class="form-horizontal" id="masterCompanyAdd" method="post"
								action="" enctype="multipart/form-data">
								<!--collapse start-->
								<input type="hidden" name="act"
									value="<?php echo base64_encode("insert");?>" />
								<div class="panel-group m-bot20" id="accordion">

									<div class="panel panel-default">

										<div class="panel-heading">
											<br>
											<h4 class="panel-title">
												<a class="accordion-toggle" data-toggle="collapse"
													data-parent="#accordion" href="#collapseOne"> ADD COMPANY
													DETAILS <i class="fa fa-minus pull-right"></i>
												</a>
											</h4>
										</div>
										<div id="collapseOne" class="panel-collapse collapse in">
											<div class="panel-body">
												<div class="col-md-6">

													<div class="form-group">
														<label class="col-lg-5 control-label"> company Name</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cName"
																maxlength="56" required readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> User Name</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cuserName"
																id="user_name" maxlength="10" required readonly="true" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-5 control-label">Company Logo</label>
														<div class="col-lg-7">
															<div class="fileupload-new thumbnail"
																style="width: 500px; height: 100px; margin-bottom: 5px;">
																<img id="preview_image" src="" alt="Company Image">
															</div>
															<span class="btn btn-success btn-sm fileinput-button"
																id="span_image" style="width: 128px;"> <i
																class="fa fa-upload"></i> <span>Upload Image</span> <input
																name="cLogo" id="image" type="file"
																accept="image/jpeg,image/png">
															</span> <em class="help-block well errorC" id="well">Provide
																500 x 130 For Company Logo</em>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Date Of
															In-Corporation</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cDoi"
																id="doi" readonly="true" />
														</div>
													</div>


												</div>
												<input type="hidden" id="logoSetted" name="logoSetted">
												<div class="col-md-6">

													<div class="form-group">
														<label class="col-lg-5 control-label"> Type </label>
														<div class="col-lg-7 ">
															<select class="form-control" id="cmp_type" name="cType">
																<option value="select">-----SELECT-----</option>
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
														<label class="col-lg-5 control-label"> CIN/LLPIN </label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cCin"
																id="cin_no" maxlength="20" required readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label">Employee Id Type</label>
														<div class="col-lg-7">

															<label for="prefix" class="col-lg-6 control-label"><input
																name="empIdtype" id="prefix" class="presuf"
																value="Prefix" type="radio" checked value="1" disabled>
																Prefix</label> <label for="suffix"
																class="col-lg-6 control-label"><input name="empIdtype"
																id="suffix" class="presuf" value="Suffix" value="0"
																type="radio" disabled> Suffix</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label" id="pre_suf">Prefix</label>
														<div class="col-lg-7 ">
															<input type="text" class="type_radio form-control"
																name="empPre" maxlength="3" required readonly="true">
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> With starts_from</label>
														<div class="col-lg-7 ">
															<input type="text"
																class="form-control starts_from current_payroll_month"
																name="sFrom" />
														</div>
													</div>


													<div class="form-group">
														<label class="col-lg-5 control-label"> Leave Based On </label>
														<div class="col-lg-7 ">
															<select class="form-control leave_based_on"
																name="leaveBasedOn">
																<option value="finYear">Financial Year [2015-16]</option>
																<option value="calYear">Calendar Year [2015]</option>
															</select>
														</div>
													</div>


												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle" data-toggle="collapse"
													data-parent="#accordion" href="#collapseTwo"> COMPANY
													CONTACT DETAILS <i class="fa fa-plus pull-right"></i>
												</a>
											</h4>
										</div>
										<div id="collapseTwo" class="panel-collapse collapse">
											<div class="panel-body">
												<div class="col-lg-6 ">
													<div class="form-group">
														<label class="col-lg-5 control-label"> Building Name</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cBuild"
																id="cm_build_name" maxlength="44" readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Street </label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cStreet"
																id="cm_street_name" maxlength="44" readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Area </label>
														<div class=" col-lg-7 ">
															<input type="text" class="form-control" name="cArea"
																id="cm_area_name" readonly="true" maxlength="25" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Pincode</label>
														<div class=" col-lg-7 ">
															<input type="text" class="form-control" name="cPincode"
																id="pincode" readonly="true" maxlength="6" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> City </label>
														<div class=" col-lg-7 ">
															<input type="text" class="form-control" name="cCity"
																id="pin_city" maxlength="25" readonly="true" />
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label class="col-lg-5 control-label">State</label>
														<div class=" col-lg-7 ">
															<input type="text" class="form-control" name="cState"
																id="pin_state" maxlength="25" readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Phone </label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cPhone"
																id="cm_phone_no" maxlength="12" readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Mobile </label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cMobile"
																id="cm_mobile_no" maxlength="12" readonly="true" />
														</div>
													</div>
													<div class="form-group">


														<label class="col-lg-5 control-label"> Email Id</label>
														<div class=" col-lg-7 ">
															<input type="text" class="form-control" name="cEmail"
																id="cm_email_id" maxlength="40" readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Website </label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cWebsite"
																id="cm_website" readonly="true" maxlength="25" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading"
											style="height: 36px; margin-left: -15px;">
											<h4 class="panel-title">
												<a style="margin-left: 15px;" class="accordion-toggle "
													data-toggle="collapse" data-parent="#accordion"
													href="#collapseThree"> RESPOSIBLE's FOR COMPANY<i
													class="fa fa-plus pull-right" style="margin-left: -2.7em;"></i>
												</a>

											</h4>
										</div>
										<div id="collapseThree" class="panel-collapse collapse">
											<div class="panel-body">
												<div class="col-lg-6 ">
													<h5>Owner / Director / CEO Details</h5>
													<hr style="margin-top: 0px;">
													<div class="form-group">
														<label class="col-lg-5 control-label">Name </label>
														<div class=" col-lg-7 ">
															<input type="text" class="form-control" name="cResp1name"
																id="Res_person1_name" maxlength="20" required
																readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label">User Name</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="Hr_1name"
																id="hr1_check" maxlength="15" required readonly="true" />
															<span id="err_msg" style="color: red"></span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Designation</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control"
																name="cResp1desgn" id="Res_person1_design"
																maxlength="20" required readonly="true" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-5 control-label"> Mobile </label>
														<div class=" col-lg-7 ">
															<input type="text" class="form-control"
																name="cResp1phone" id="Res_person1_phone" maxlength="12"
																required readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Email</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control"
																name="cResp1email" id="Res_person1_Email_id"
																maxlength="40" required readonly="true" />
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<h5>HR Contact Details</h5>
													<hr style="margin-top: 0px;">
													<div class="form-group">
														<label class="col-lg-5 control-label">Name</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="cResp2name"
																id="Res_person2_name" maxlength="20" required
																readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label">User Name</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control" name="Hr_2name"
																id="hr2_check" maxlength="15" required readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label"> Designation </label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control"
																name="cResp2desgn" id="Res_person2_design"
																maxlength="20" required readonly="true" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-5 control-label">Mobile </label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control"
																name="cResp2phone" id="Res_person2_phone" maxlength="12"
																required readonly="true" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-5 control-label">Email</label>
														<div class="col-lg-7 ">
															<input type="text" class="form-control"
																name="cResp2email" id="Res_person2_Email_id"
																maxlength="40" required readonly="true" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>


								<div class="modal-footer">
									<button type="submit" class="btn btn-primary" id="add">Add</button>
									<button type="button" class="btn btn-danger" id="cancel">
										cancel</button>

								</div>

								<!--collapse end-->
							</form>
						</div>
					</div>
				</section>



				<section class="panel" id="edit_view">
					<div class="panel-body" style="padding: 2px;">
						<header class="panel-heading" style="border-color: #FFF;">
							Company View
							<div class="btn-group pull-right" id="back_view">
								<button id="showhide" type="button" class="btn btn-danger"
									style="margin-top: -5px;">Back</button>
							</div>
						</header>


						<form class="form-horizontal" id="masterCompanyEdit" method="post"
							enctype="multipart/form-data">
							<!--collapse start-->
							<div class="panel-group m-bot20" id="accordion1">

								<div class="panel panel-default">

									<div class="panel-heading">
										<br>
										<h4 class="panel-title">
											<a class="accordion1-toggle" data-toggle="collapse"
												data-parent="#accordion1" href="#collapseOne1"> ADD COMPANY
												DETAILS <i class="fa fa-minus pull-right"></i>
											</a>
										</h4>
									</div>
									<div id="collapseOne1" class="panel-collapse collapse in">
										<div class="panel-body">
											<div class="col-md-6 ">
												<input type="hidden" name="act"
													value="<?php echo base64_encode("update");?>" /> <input
													type="hidden" class="form-control" name="cdb"
													id="company_db_name" readonly="true" /> <input
													type="hidden" class="form-control" name="company_id"
													id="company_id" readonly="true" />
												<div class="form-group">
													<label class="col-lg-5 control-label"> Name</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cName"
															id="company_name" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> User Name</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cuserName"
															id="company_user_name" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-5 control-label">Company Logo</label>
													<div class="col-lg-7">
														<div class="fileupload-new thumbnail"
															style="width: 500px; height: 100px; margin-bottom: 5px;">
															<img class="e_preview_image" id="e_preview_image" src=""
																alt="Company Image">
														</div>
														<span
															class="btn btn-success btn-sm fileinput-button view_span"
															id="span_image" style="width: 128px;"> <i
															class="fa fa-upload"></i> <span>Upload Image</span> <input
															type="text" name="cLogo_" class="e_preview_image"> <input
															name="cLogo" id="e_image" type="file"
															accept="image/jpeg,image/png">
														</span> <em class="help-block well errorC" id="well">Provide
															500 x 130 For Company Logo</em>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-5 control-label"> Date Of
														In-Corporation</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cDoi"
															id="company_doi" />
													</div>
												</div>

											</div>
											<input type="hidden" id="e_logoSetted" name="logoSetted">
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-lg-5 control-label"> Type </label>
													<div class="col-lg-7  show_">
														<input type="text" class="form-control company_type" />
													</div>
													<div class="col-lg-7  hide_">
														<select class="form-control company_type" name="cType">
															<option value="select">-----SELECT-----</option>
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
													<label class="col-lg-5 control-label"> CIN/LLPIN </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cCin"
															id="company_cin_no" />
													</div>
												</div>
												<div class="show_">
													<div class="form-group">
														<label class="col-lg-5 control-label lName"></label>
														<div class="col-lg-7  show_">
															<input type="text" class="form-control id_type" />
														</div>
													</div>
												</div>
												<div class="form-group hide_">
													<label class="col-lg-5 control-label">Employee Id Type</label>
													<div class="col-lg-7 ">
														<label for="e_prefix" class="col-lg-6 control-label"> <input
															name="empIdtype" id="e_prefix" class="presuf"
															value="Prefix" type="radio" checked value="1" disabled>
															Prefix
														</label> <label for="e_suffix"
															class="col-lg-6 control-label"> <input name="empIdtype"
															id="e_suffix" class="presuf" value="Suffix" value="0"
															type="radio" disabled> Suffix
														</label>

													</div>
												</div>
												<div class="form-group hide_">
													<label class="col-lg-5 control-label e_lName"></label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control value_type"
															name="empPre" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-5 control-label"> With starts_from</label>
													<div class="col-lg-7 ">
														<input type="text"
															class="form-control starts_from current_payroll_month"
															name="sFrom" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Leave Based On </label>
													<div class="col-lg-7 ">
														<select class="form-control leave_based_on"
															name="leaveBasedOn">
															<option value="finYear">Financial Year [2015-16]</option>
															<option value="calYear">Calendar Year [2015]</option>
														</select>
													</div>
												</div>


											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a class="accordion1-toggle" data-toggle="collapse"
												data-parent="#accordion1" href="#collapseTwo2"> COMPANY
												CONTACT DETAILS <i class="fa fa-plus pull-right"></i>
											</a>
										</h4>
									</div>
									<div id="collapseTwo2" class="panel-collapse collapse">
										<div class="panel-body">
											<div class="col-lg-6 ">
												<div class="form-group">
													<label class="col-lg-5 control-label"> Building Name</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cBuild"
															id="company_build_name"
															value="<?php echo $company_build_name;?>" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Street </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cStreet"
															id="company_street" value="<?php echo $company_street;?>" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Area </label>
													<div class=" col-lg-7 ">
														<input type="text" class="form-control" name="cArea"
															id="company_area" value="<?php echo $company_area;?>" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Pincode</label>
													<div class=" col-lg-7 ">
														<input type="text" class="form-control" name="cPincode"
															id="company_pin_code"
															value="<?php echo $company_pin_code;?>" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> City </label>
													<div class=" col-lg-7 ">
														<input type="text" class="form-control" name="cCity"
															id="company_city" value="<?php echo $company_city;?>" />
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="col-lg-5 control-label">State</label>
													<div class=" col-lg-7 ">
														<input type="text" class="form-control" name="cState"
															id="company_state" value="<?php echo $company_state;?>" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Phone </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cPhone"
															id="company_phone" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Mobile </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cMobile"
															id="company_mobile" />
													</div>
												</div>
												<div class="form-group">


													<label class="col-lg-5 control-label"> Email Id</label>
													<div class=" col-lg-7 ">
														<input type="text" class="form-control" name="cEmail"
															id="company_email" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Website </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cWebsite"
															id="company_website" />
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading"
										style="height: 36px; margin-left: -15px;">
										<h4 class="panel-title">
											<a style="margin-left: 15px;" class="accordion1-toggle "
												data-toggle="collapse" data-parent="#accordion1"
												href="#collapseThree3"> RESPOSIBLE's FOR COMPANY<i
												class="fa fa-plus pull-right" style="margin-left: -2.7em;"></i>
											</a>
										</h4>
									</div>
									<div id="collapseThree3" class="panel-collapse collapse">
										<div class="panel-body">
											<div class="col-lg-6 ">
												<h5>Owner / Director / CEO Details</h5>
												<hr style="margin-top: 0px;">
												<div class="form-group">
													<label class="col-lg-5 control-label">Name</label>
													<div class=" col-lg-7 ">
														<input type="text" class="form-control" name="cResp1name"
															id="company_resp1_name" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> User Name</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="Hr_1name"
															id="hr_1username" /> <span id="err1_msg"
															style="color: red"></span>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Designation </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cResp1desgn"
															id="company_resp1_desgn" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-5 control-label"> Mobile</label>
													<div class=" col-lg-7 ">
														<input type="text" class="form-control" name="cResp1phone"
															id="company_resp1_phone" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Email</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cResp1email"
															id="company_resp1_email" />
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<h5>HR Contact Details</h5>
												<hr style="margin-top: 0px;">
												<div class="form-group">
													<label class="col-lg-5 control-label">Name</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cResp2name"
															id="company_resp2_name" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> User Name</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="Hr_2name"
															id="hr_2username" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label"> Designation </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cResp2desgn"
															id="company_resp2_desgn" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-5 control-label">Phone </label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cResp2phone"
															id="company_resp2_phone" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label">Email</label>
													<div class="col-lg-7 ">
														<input type="text" class="form-control" name="cResp2email"
															id="company_resp2_email" />
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="modal-footer" id="view_footer">
								<button type="submit" id="update_bu" class="btn btn-primary">
									<i class="fa fa-check-square"></i> Update
								</button>
								<button type="button" class="btn btn-default"
									data-dismiss="modal" data-toggle="modal" href="#myModal2"
									id="cancel_edit">
									<i class="fa fa-undo"></i> Cancel
								</button>

							</div>
							<!--collapse end-->
						</form>
					</div>
				</section>


				<section class="panel">
				<div id="loader"></div>
					<div class="panel-body" id="tableContent">
						
					</div>
				</section>
				
				<!-- page end-->


			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>


	<!-- END JAVASCRIPTS -->
	<script>

          $(document).ready(function () {
        	  filterCompanies();
                 
              $('#doi,#company_doi,.starts_from').datetimepicker({  
            	  format: 'DD/MM/YYYY',
            	  inline:true,
            	  maxDate:false
              });
              $("#add_company_d").click(function () {
                  $('#com_add').toggle('show');
              });
              
              $("#back_view").click(function () {
                  $('#com_add,#header_hide').show();
                  $('#edit_view').hide();
                  $('#cancel').click();
			 });
              $('#prefix,#suffix').attr('disabled', false);
              $("#masterCompanyAdd ").find(":input").each(function () {
                  $(this).attr('readonly', false);
                  $('option:not(:selected)').attr('disabled', false);
               });
              $('#edit_view').hide();
              $("#company_list").click(function () {
                  $("#com_lists").show();
                  $("#com_add").hide();
                  $("#com_lists").hide();
              });
              $("#prefix,#suffix").change(function () {
                  $('#pre_suf').html($(this).val());
              });
              $("#e_prefix,#e_suffix").change(function () {
                  $('.e_lName').html($(this).val());
              });
              $("#cancel").click(function () {
                  $('#com_add').toggle('hide');
              });

              $("#cancel_edit").click(function () {
                  $('#com_add,#header_hide').show();
                  $('#edit_view').hide();
                  $('#cancel').click();
              });
              

              $("#com_lists").hide();
                //pincode set 
               $('#pincode').on('blur', function () {
			     commonFunctions.getCityByPinCode($('#pincode'),$(this).val(), function (result) {
				      $('#pin_city').val(result[0].districtname);
					  $('#pin_state').val(result[0].statename);
                  });
              });

               $('#company_pin_code').on('blur', function () {
  			     commonFunctions.getCityByPinCode($('#pincode'),$(this).val(), function (result) {
  				      $('#company_city').val(result[0].districtname);
  					  $('#company_state').val(result[0].statename);
                    });
                });
               function pincode(id,city,state){
                   }
             
           
   });


          $(document).on('click', '#loginUserTable a.pending', function (e) {
              e.preventDefault();
              var nRow = $(this).parents('tr').find('td:first').html();
              window.location.assign("masterApproval.php?id="+nRow)
             
           });

        
          function toggleChevron(e) {
              $(e.target).prev('.panel-heading').find('i.fa').toggleClass('fa-minus fa-plus');
              $('#accordion', '.panel-heading').css('background-color', 'green');
          }
          $('#accordion').on('hidden.bs.collapse', toggleChevron);
          $('#accordion').on('shown.bs.collapse', toggleChevron);

          //for edit
          function toggleChevrons(e) {
                $(e.target).prev('.panel-heading').find('i.fa').toggleClass('fa-minus fa-plus');
              $('#accordion1', '.panel-heading').css('background-color', 'green');
          }
          $('#accordion1').on('hidden.bs.collapse', toggleChevrons);
          $('#accordion1').on('shown.bs.collapse', toggleChevrons);

//user anem chaeck Avlialability

          $('#user_name').on('blur', function () {
              if ($(this).val() != "") {
            	  var str = $(this).val();
              	var res = str.toLowerCase(); 
                    var xhr = $.ajax({
                      type: "POST",
                      url: "../common/user_name_check.php",
                      data: { "user_name":res},
                      cache: false,
                      beforeSend: function () {
                          
                      },
                      complete: function () {
                      },
                      success: function (data) {
                          data = JSON.parse(data);
                          if (data.length > 0) {
                        	  $('#user_name').val('');
                        	  $('#user_name').removeClass('correct');
                              $('#user_name').addClass('wrong');
                          } else {
                              $('#user_name').removeClass('wrong');
                              $('#user_name').addClass('correct');
                          }
                      }
                  });
              }

          });
          
          //hr user name check
          $('#hr1_check').on('blur', function () {
             if( $('#hr1_check').val()==$('#hr2_check').val()){
					$('#err_msg').html('Try Different Username');
					}else{
						$('#err_msg').html('');
						}
          });
          $('#hr2_check').on('blur', function () {
             if( $('#hr1_check').val()==$('#hr2_check').val()){
					$('#err_msg').html('Try Different Username');
					}else{
						$('#err_msg').html('');
					}
          });

          $('#hr_1username').on('blur', function () {
              if( $('#hr_1username').val()==$('#hr_2username').val()){
 					$('#err1_msg').html('Try Different Username');
 					}else{
 						$('#err1_msg').html('');
 						}
           });
           $('#hr_2username').on('blur', function () {
              if( $('#hr_2username').val()==$('#hr_1username').val()){
 					$('#err1_msg').html('Try Different Username');
 					}else{
 						$('#err1_msg').html('');
 					}
           });

           
               
          $("#image").change(function (e) {
              if (this.disabled) return alert('File upload not supported!');
              var F = this.files;
              if (F && F[0]) for (var i = 0; i < F.length; i++) readImage(F[i]);
          });

          function readImage(file) {

              var reader = new FileReader();
              var image = new Image();

              reader.readAsDataURL(file);
              reader.onload = function (_file) {
                  image.src = _file.target.result;              // url.createObjectURL(file);
                  image.onload = function () {
                      var w = this.width,
                                h = this.height,
                                s = ~ ~(file.size / 1024);
                      if (w <= 500 && h <= 130 && s < 10000) {
                          $('#preview_image').attr('src', this.src);
                          $('#e_preview_image').attr('src', this.src);
                          $('.errorC').html("Provide 500 x 130 Image");
                    	  $(".errorC").css("color", "black"); 	
                    	  $('#logoSetted').val(1);
                    	  $('#e_logoSetted').val(1);
                      } else {
                    	  $('.errorC').html("Provide 500 x 130 Image");
                    	  $(".errorC").css("color", "red"); 
                    	  $('#logoSetted').val(0);
                    	  $('#e_logoSetted').val(0);
                      }

                  };
                  image.onerror = function () {
                      alert('Invalid file type: ' + file.type);
                  };
              };

    
          }; 
          
          $("#e_image").change(function (e) {
              if (this.disabled) return alert('File upload not supported!');
              var F = this.files;
              if (F && F[0]) for (var i = 0; i < F.length; i++) readImage(F[i]);
          });
          function filterCompanies(){
              $.ajax({
                  dataType: 'html',
                  type: "POST",
                  url: "php/company.handle.php",
                  cache: false,
                  data: { act: '<?php echo base64_encode("filterCompany");?>'},
	             beforeSend:function(){
                   	$('#loader').loading(true); 
                    },
                 success: function (data) {
                      jsondata = JSON.parse(data);
                      if (jsondata[0] == "success") {
                          tableViewForMaster(jsondata[2]);
                     }

                  	$('#loader').loading(false); 
                  }

              });
          }
        	//Master Table View Common
        					 function tableViewForMaster(data){
            					 if(data=='No Data Found'){
html="<table class='table table-striped table-hover table-bordered dataTable' id='loginUserTable'><thead><tr><th>ComID</th><th>ComName</th><th>City</th><th>State</th><th>Mobile</th><th>Email</th><th>Signup</th><th>CreatedBy</th><th>Staus</th></tr></table>"
                					 }else{
            					  htmlHeader="<table class='table table-striped table-hover table-bordered dataTable' id='loginUserTable'><thead><tr>";
        		                  data[1].forEach(function(columName) {
        		                	 htmlHeader+="<th>"+columName+"</th>";
        		                	});
        		                  htmlHeader+="</tr></thead>";
        		                  htmlBody="<tbody>";
        		                  data[0].forEach(function(trrows) {
        		                	  htmlBody+="<tr>";
        		                	  len=trrows.length;
        		                      trrows.forEach(function(td,index) {
        		                        if(index == len - 1){
        		                          htmlBody+="<td><a class='view'><button class='btn btn-success btn-xs'><i class='fa fa-eye'></i></button></a>&nbsp;<a class='edit'><button class='btn btn-danger btn-xs'><i class='fa fa-pencil'></i></button></a>&nbsp;";
              	                		  htmlBody+=(td.split('#')[1]==1)?"<a class='enable' title='disable' ><button class='btn btn-success btn-xs'><i class='fa fa-unlock'></i></button></a>&nbsp;":"<a class='disable' title='disable'><button class='btn btn-danger btn-xs'><i class='fa fa-lock'></i></button></a>&nbsp;"; 
              	                		  htmlBody+=(td.split('#')[0]=='W')?"<a title='Pending' class='pending' href='#'><button class='btn btn-danger btn-xs'><i class='fa fa-spinner'></i></button></a></td>":"<a title='Approved' href='#'><button class='btn btn-info btn-xs'><i class='fa fa-check'></i> </button></a></td>"; 
              	                		}else
        		                		  htmlBody+="<td>"+td+"</td>"; 
        		                 	  });
        		                	  htmlBody+="</tr>";
        		                 	});
        		                  htmlBody+="</tbody></table>";
        		                  html=htmlHeader+htmlBody;
            					 }
            					 $('#tableContent').html('').html(html);
        		                 var oTable=$('#loginUserTable').dataTable({
        		                	"iDisplayLength": 5,
        		  	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
        		  	            });
        		                $('#loginUserTable_wrapper .dataTables_filter').html('<div class="input-group">\
        		                          <input class="form-control medium" id="searchInput" type="text">\
        		                          <span class="input-group-btn">\
        		                            <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
        		                          </span>\
        		                          <span class="input-group-btn">\
        		                            <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
        		                          </span>\
        		                      </div>');
        							$('#loginUserTable_processing').css('text-align', 'center');
        							jQuery('#loginUserTable_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
        							$('#searchInput').on('keyup', function (e) {
        							if (e.keyCode == 13) {
        							oTable.fnFilter($(this).val());
        							} else if (e.keyCode == 27) {
        							$(this).parent().parent().find('input').val("");
        							oTable.fnFilter("");
        							}
        							});
        							$('#searchFilter').on('click', function () {
        							oTable.fnFilter($(this).parent().parent().find('input').val());
        							});
        							$('#searchClear').on('click', function () {
        							$(this).parent().parent().find('input').val("");
        							oTable.fnFilter("");
        							});
        		  	           }

        					 $(document).on('click', "#loginUserTable a.edit", function (e) {
     			                e.preventDefault();
     			                var masterName = $(this).closest('tr').children(':eq(1)').text();
     			               var companyId = $(this).closest('tr').children(':eq(0)').text(); $('#com_add,#header_hide').hide();
        	                $('#edit_view').show();
        	                $('.view_span,#view_footer').show();
        	                $('#back_view,.show_').hide();
        	                $('.hide_').show();
        	                $.ajax({
        	                    dataType: 'html',
        	                    type: "POST",
        	                    url: "php/company.handle.php",
        	                    cache: false,
        	                    data: { act: '<?php echo base64_encode("select");?>', company_id: companyId },
   		                        success: function (data) {
        	                        var json_obj = $.parseJSON(data); //parse JSON
        	                        $.each(json_obj[2][0], function (k, v) {
        	                            //display the key and value pair
        	                            $('#' + k).val(v);
        	                        });
        	                       $.each(json_obj[2][0], function (k, v) {
        	                            //display the key and value pair
        	                            $('.' + k).val(v);
        	                        });
								  $("#masterCompanyEdit ").find(":input").each(function () {
        	                              $(this).attr('readonly', false);
        	                              $(this).removeAttr('disabled');
        	                            $(this).removeAttr("style", "background-color: #FFF; border:0px");
        	                            $('option:not(:selected)').attr('disabled', false);
        	                        });
        	                        
        	                        $('#company_name').attr('readonly', true);
        	                        $('#company_user_name').attr('readonly', true);
        	                        $('#hr_2username').attr('readonly', true);
        	                        $('#hr_1username').attr('readonly', true);
        	                        $('.e_preview_image').prop('src', json_obj[2][0].company_logo);
        	                        $('.e_preview_image').val(json_obj[2][0].company_logo);

									if (json_obj[2][0].company_emp_id_prefix !== '0') {
        	                            $("#e_prefix").click();
        	                            $('.value_type').val(json_obj[2][0].company_emp_id_prefix);
        	                            $('.e_lName').html('Prefix');

        	                        } else {
        	                            $("#e_suffix").click();
        	                            $('.value_type').val(json_obj[2][0].company_emp_id_suffix);
        	                            $('.e_lName').html('Suffix');
        	                        }
									$('.current_payroll_month').val(json_obj[2][0].payroll_month);
									$('#company_doi').val(json_obj[2][0].doi);
									
        	                    }
        	                });
     			               
     			            });
      			            
        					 $(document).on('click', "#loginUserTable a.view", function (e) {
        			                e.preventDefault();
        			                var masterName = $(this).closest('tr').children(':eq(1)').text();
        			               var companyId = $(this).closest('tr').children(':eq(0)').text();
        			               $('#com_add,#header_hide').hide();
               	                $('#edit_view').show();
               	                $.ajax({
               	                    dataType: 'html',
               	                    type: "POST",
               	                    url: "php/company.handle.php",
               	                    cache: false,
               	                    data: { act: '<?php echo base64_encode("select");?>', company_id: companyId  },
          		                        success: function (data) {
          		                        	window.scrollTo(0, 0);
              		                       var json_obj = $.parseJSON(data); //parse JSON
               	                        $.each(json_obj[2][0], function (k, v) {
               	                            //display the key and value pair
               	                            $('#' + k).val(v);
               	                        });
               	                        $('.view_span,#view_footer,.hide_').hide();
               	                        $('#back_view,.show_').show();
               	                        $("#masterCompanyEdit ").find(":input").each(function () {
               	                            $(this).attr('readonly', true);
               	                            $(this).attr('disabled', true);
               	                            $(this).attr("style", "background-color: #FFF; border:0px");
               	                            $('option:not(:selected)').attr('disabled', true);
               	                        });
               	                        $('.company_type').val(json_obj[2][0].company_type);
               	                        $('.leave_based_on').val(json_obj[2][0].leave_based_on);
               	                        $('.e_preview_image').prop('src', json_obj[2][0].company_logo);
               	                        $('.e_preview_image').val( json_obj[2][0].company_logo);
               	                        if (json_obj[2][0].company_emp_id_prefix !== '0') {
                   	                       $('.id_type').val(json_obj[2][0].company_emp_id_prefix);
       										 $('.lName').html('Prefix');
       									 } else {
       									    $('.lName').html('Suffix');
               	                            $('.id_type').val(json_obj[2][0].company_emp_id_suffix);
               	                           }

             	                           $('.current_payroll_month').val(json_obj[2][0].payroll_month);
             	                         $('#company_doi').val(json_obj[2][0].doi);
               	                    }
               	                });
        			               
        			            });
        					  $('#masterCompanyEdit').on('submit', function (e) {
                	              e.preventDefault();
                  	              $.ajax({
                  	            	  processData: false,
              	                    contentType: false,
              	                    type: "POST",
              	                    url: "php/company.handle.php",
              	                    cache: false,
              	                    data: new FormData(this),
              	                    beforeSend:function(){
        		                         	$('#update_bu').button('loading'); 
        		                          },
        		                          complete:function(){
        		                         	 $('#update_bu').button('reset');
        		                          },
              	                     success: function (data) {
            		         	                        data1 = JSON.parse(data);
            		         	                        if (data1[0] == "success") {
                		         	                        $('.close').click();
                		         	                       tableViewForMaster(data[2]);
            		         	                            $('#masterCompanyEdit')[0].reset();
            		         	                            $('#e_preview_image').attr('src', 'http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image');
            		         	                            $('#cancel_edit').click();
            		         	                            BootstrapDialog.alert(data1[1]);
            		         	                        }

            		         	                    }

            		         	                });
            		               });
        					  $(document).on('click', "#loginUserTable a.disable", function (e) {
        			                e.preventDefault();
        			                var masterName = $(this).closest('tr').children(':eq(1)').text();
        			               var masterId = $(this).closest('tr').children(':eq(0)').text();
        			               BootstrapDialog.show({
               		                title:'Confirmation',
           		                    message: 'Are Sure you want to Enable <strong>'+ masterName +'<strong>',
           		                    buttons: [{
           		                        label: 'Enable',
           		                        cssClass: 'btn-sm btn-primary',
           		                        autospin: true,
           		                        action: function(dialogRef){
           		                        	 $.ajax({
            	        		                    dataType: 'html',
            	        		                    type: "POST",
            	        		                   url: "php/company.handle.php",
            	        		                    cache: false,
            	        		                    data: { act: '<?php echo base64_encode("enable");?>', company_id: masterId  },
            	        		                      complete:function(){
            	        		                    	 dialogRef.close();
            	        		                      },
            	        		                    success: function (data) {
            	        		                    	data = JSON.parse(data);
            	        		                        if (data[0] == "success") {
            	        		                        	tableViewForMaster(data[2]);
            	        		                        	BootstrapDialog.alert(data[1]);
            	        		                        	 setTimeout(function(){
            	            	                            	$('.close').click();
            	            	                            	}, 5000);
            	        		                        }else if (data[0] == "error") {
            			                                    alert(data[1]);
            			                                }
            	        		                    }
            	        		                });
           		                                		                            
           		                        }
           		                    }, {
           		                        label: 'Close',
           		                        cssClass: 'btn-sm btn-default',
          		                        action: function(dialogRef){
           		                            dialogRef.close();
           		                        }
           		                    }]
           		                });
        			               
        			            });

        		               $(document).on('click', "#loginUserTable a.enable", function (e) {
        		               e.preventDefault();
        		               var masterName = $(this).closest('tr').children(':eq(1)').text();
        		               var masterId = $(this).closest('tr').children(':eq(0)').text();
        		               BootstrapDialog.show({
           		                title:'Confirmation',
       		                    message: 'Are Sure you want to Disable <strong>'+masterName+'<strong>',
       		                    buttons: [{
       		                        label: 'Disable',
       		                        cssClass: 'btn-sm btn-primary',
       		                        autospin: true,
       		                        action: function(dialogRef){
       		                        	 $.ajax({
        	        		                    dataType: 'html',
        	        		                    type: "POST",
        	        		                   url: "php/company.handle.php",
        	        		                    cache: false,
        	        		                    data: { act: '<?php echo base64_encode("disable");?>', company_id: masterId   },
        	        		                      complete:function(){
        	        		                    	 dialogRef.close();
        	        		                      },
        	        		                    success: function (data) {
        	        		                    	data = JSON.parse(data);
        	        		                        if (data[0] == "success") {
        	        		                        	tableViewForMaster(data[2]);
        	        		                        	BootstrapDialog.alert(data[1]);
        	        		                        	 setTimeout(function(){
        	            	                            	$('.close').click();
        	            	                            	}, 5000);
        	        		                        }else if (data[0] == "error") {
        			                                    alert(data[1]);
        			                                }
        	        		                    }
        	        		                });
       		                                		                            
       		                        }
       		                    }, {
       		                        label: 'Close',
                                    cssClass: 'btn-sm btn-default',
       		                        action: function(dialogRef){
       		                            dialogRef.close();
       		                        }
       		                    }]
       		                });
        		               
        		            });

        		               $('#masterCompanyAdd').on('submit', function (e) {
               	                e.preventDefault();
               	                $.ajax({
               	                	   processData: false,
                   	                    contentType: false,
                   	                    type: "POST",
                   	                    url: "php/company.handle.php",
                   	                    cache: false,
                   	                    data: new FormData(this),
                   	                    beforeSend:function(){
       		                         	$('#add').button('loading'); 
       		                          },
       		                          complete:function(){
       		                         	 $('#add').button('reset');
       		                          },
               	                    success: function (data) {
                   	                     data1 = JSON.parse(data);
               	                        if (data1[0] == "success") {
               	                            $('#com_add').toggle('hide');
               	                           filterCompanies();
               	                          $('#masterCompanyAdd')[0].reset();
               	                            $('#preview_image').attr('src', 'http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image');
               	                            $('#user_name').removeClass('wrong correct');
               	                            $("#save,#cancel").hide();
               	                            BootstrapDialog.alert(data1[1]);
               	                            $('#prefix').click();
               	                        }   }

               	                });
               	                 });
      </script>


</body>
</html>
