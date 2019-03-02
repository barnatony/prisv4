<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<meta name="keyword" content="">
<link rel="shortcut icon" href="img/favicon.png">
<title>Pay Structure</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
body.dragging, body.dragging * {
	cursor: move !important;
}

.ui-sortable>li {
	padding: 5px 0 5px 5px !important;
}

ol.vertical.sortable {
	min-height: 100px;
	margin: 0px 0px 9px;
	border: 1px solid rgb(153, 153, 153);
	padding: 0;
}

.sortable>li {
	display: block;
	margin: 5px;
	padding: 5px;
	border: 1px solid #BCB9B9;
	color: #fff;
	background: #40C4FF none repeat scroll 0% 0%;
}

.sortable>li.placeholder {
	position: relative;
	margin: 0;
	padding: 0;
	border: none;
}

.has-switch span {
	font-size: 12.994px;
}

.sortable>li.placeholder:before {
	position: absolute;
	content: "";
	width: 0;
	height: 0;
	margin-top: -5px;
	left: -5px;
	top: -4px;
	border: 5px solid transparent;
	border-left-color: #999;
	border-right: none;
}

.fa-info-circle {
	cursor: pointer;
}

.alert {
	padding-top: 7px !important;
	padding-bottom: 9px !important;
	margin-bottom: 0px;
}

.times {
	position: relative;
	margin: 10px;
	margin-left: -1px;
	cursor: pointer;
}

.fa-check {
	position: relative;
	margin: 10px;
	cursor: pointer;
}

#sortable_edit {
	float: right;
	margin-right: 10px;
	cursor: pointer;
}

.edit {
	width: 87%;
	float: left;
}

.retirement_load {
	position: absolute;
	margin-left: -105px;
	margin-top: 6px;
	display: none;
}

#margin_right_pr, #margin_right_re {
	margin-top: 5px;
	margin-right: 14pt;
}

.retirement_load_td {
	padding: 20px;
}

.checkbox-inline {
	padding-top: 0px !Important;
}

#ex_salary_head_chosen, #salary_head_chosen, #ex_salary_head_s_chosen {
	width: 100% !important;
}

.ex_show_salary_days, .show_salary_days, .re_ex_show_salary_days,
	.re_show_salary_days {
	display: none;
}

.back{
margin-top:10px;
margin-right:10px;
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
				<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
								<i class=" fa fa-arrow-left"></i> All Settings</a>
				</div>
				<section class="panel">
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs" id="paystructure_tabs">
							<li class=""><a href="#payGeneral" data-id="p"
								data-title="general" data-toggle="tab" data-loaded="false"
								id="general"> General </a></li>
							<li class=""><a href="#payMisc" data-id="i" data-toggle="tab"
								id="miscAllowid" data-title="misc" data-loaded="false"> Misc </a></li>
							<li class=""><a href="#payRetirment" data-id="t"
								data-toggle="tab" id="retirementClick" data-title="retire"
								data-loaded="false"> Retirement </a></li>
							<li class="">
								<div class="btn-group">
									<div id="error_show" class="alert alert-info fade in"
										style="display: none;">
										<button data-dismiss="" class="close close-sm" type="button">
											<i class="fa fa-times"></i>
										</button>
										<span class="composed_reply"></span>
									</div>
								</div>
							</li>

						</ul>

					</header>
					<div class="panel-body">
						<div class="tab-content tasi-tab">
							<div class="tab-pane active" id="payGeneral">
								<div class="col-lg-12" id="generalbody">
									<header class="panel-heading">
										Allowances <a href="taxExemption.php"><button type="button"
												class="btn btn-sm btn-info pull-right">TAX EXEMPTION</button>
										</a>

									</header>
									<div class="panel-body">

										<div class="col-lg-6">
											<header>Structure</header>
											<ol id="allowances_list"
												class='allowances_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>
										</div>
										<div class="col-lg-6">
											<header>Available Allowances</header>
											<ol id="allowances_avail_list"
												class='allowances_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>

											<button type="button" href="#allowances_add"
												data-toggle="modal"
												class="btn btn-sm btn-info pull-right all_edit remove">
												<i class="fa fa-plus"></i> Add
											</button>

											<br></br>
											<div class="btn-group">
												<div id="allowance_show" class="alert alert-info fade in"
													style="display: none;">
													<button data-dismiss="" class="close close-sm"
														type="button">
														<i class="fa fa-times"></i>
													</button>
													<span class="composed_reply"></span>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="">
											<button type="button" id="create_allowances_btn"
												class="btn btn-success btn-block">Create Allowances</button>
										</div>
									</div>
								</div>
								<!-- Deductions -->
								<div class="col-lg-12">
									<header class="panel-heading"> Deductions </header>
									<div class="panel-body">
										<div class="col-lg-6">
											<header>Structure</header>
											<ol id="deductions_list"
												class='deductions_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>
										</div>
										<div class="col-lg-6">
											<header>Available Deductions</header>
											<ol id="deductions_avail_list"
												class='deductions_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>
											<button type="button" href="#deductionAdd"
												data-toggle="modal"
												class="btn btn-sm btn-info pull-right branch_edit remove">
												<i class="fa fa-plus"></i> Add
											</button>


											<br></br>
											<div class="btn-group">
												<div id="deductions_show" class="alert alert-info fade in"
													style="display: none;">
													<button data-dismiss="" class="close close-sm"
														type="button">
														<i class="fa fa-times"></i>
													</button>
													<span class="composed_reply"></span>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-12">
											<button type="button" id="create_deductions_btn"
												class="btn btn-success btn-block">Create Deductions</button>
										</div>
									</div>

								</div>
							</div>

							<div class="tab-pane" id="payMisc">
								<!-- Mispayments -->
								<div class="col-lg-12" id="miscAllowbody">
									<header class="panel-heading"> Miscellaneous Payments </header>
									<div class="panel-body">
										<div class="col-lg-6">
											<header>Structure</header>
											<ol id="miscpay_list"
												class='miscpay_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>
										</div>
										<div class="col-lg-6">
											<header>Available Miscellaneous Payments</header>
											<ol id="miscpay_avail_list"
												class='miscpay_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>
											<button type="button" href="#miscPayAdd" data-toggle="modal"
												class="btn btn-sm btn-info pull-right branch_edit remove">
												<i class="fa fa-plus"></i> Add
											</button>


											<br></br>
											<div class="btn-group">
												<div id="miscpay_show" class="alert alert-info fade in"
													style="display: none;">
													<button data-dismiss="" class="close close-sm"
														type="button">
														<i class="fa fa-times"></i>
													</button>
													<span class="composed_reply"></span>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-12">
											<button type="button" id="create_miscpay_btn"
												class="btn btn-success btn-block">Create Miscellaneous
												Allowance</button>
										</div>
									</div>

								</div>
								<!-- Misdeduction -->
								<div class="col-lg-12">
									<header class="panel-heading"> Miscellaneous Deductions </header>
									<div class="panel-body">
										<div class="col-lg-6">
											<header>Structure</header>
											<ol id="miscdeduc_list"
												class='miscdeduc_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>
										</div>
										<div class="col-lg-6">
											<header>Available Miscellaneous Deductions</header>
											<ol id="miscdeduc_avail_list"
												class='miscdeduc_pay_structure vertical sortable'>
												<img src="../img/ajax-loader.gif" class="box_loader">
											</ol>
											<button type="button" href="#miscdeduc" data-toggle="modal"
												class="btn btn-sm btn-info pull-right branch_edit remove">
												<i class="fa fa-plus"></i> Add
											</button>
											<br></br>
											<div class="btn-group">
												<div id="miscdeduc_show" class="alert alert-info fade in"
													style="display: none;">
													<button data-dismiss="" class="close close-sm"
														type="button">
														<i class="fa fa-times"></i>
													</button>
													<span class="composed_reply"></span>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-12">
											<button type="button" id="create_miscdeduc_btn"
												class="btn btn-success btn-block">Create Miscellaneous
												Deduction</button>
										</div>
									</div>

								</div>
							</div>
							<div class="tab-pane" id="payRetirment">
								<div class="col-lg-12">
									<div class="container " style="width: 100%;">
										<section class="error-wrapper"
											style="margin-top: 2%; text-align: left">
											<table class="table table-striped table-hover table-bordered">
												<thead>
													<tr id="slab-table-head">
														<th>Benefits</th>
														<th>Enable/Disable</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><h5>(i) Gratuity</h5> <span> An individual who has
																worked in an organization for a minimum period of 5
																years is eligible for gratuity. </span>
															<div class="alert alert-success fade in success"
																style="display: none">
																<button data-dismiss="alert" class="close close-sm"
																	type="button">
																	<i class="fa fa-times"></i>
																</button>
																<span class="status_success"></span>
															</div>
															<div class="alert alert-block alert-danger fade in error"
																style="display: none">
																<button data-dismiss="alert" class="close close-sm"
																	type="button">
																	<i class="fa fa-times"></i>
																</button>
																<span class="status_error"></span>
															</div></td>
														<td id="r_gratuity_s">
															<div class="switch switch-square gratuity"
																id="r_gratuity" data-name="r_gratuity"
																data-on-label="Enabled" data-off-label="Disabled"
																style="margin-top: 16%;">
																<input type="checkbox" class="c_gratuity" /> <img
																	src="../img/ajax-loader.gif" class="retirement_load">
															</div>
															<div class="text-center">
																<button class="btn btn-xs btn-primary" type="button"
																	id="margin_right_pr">
																	<i class="fa fa-pencil"></i> Edit
																</button>
															</div>
														</td>
													</tr>
													<tr class="show_gra" style="display: none">
														<td>
															<form class="form-horizontal" class="gratuity_edit_form"
																id="gratuity_edit_form">



																<input type="hidden" name="act" id="act"
																	value="<?php echo base64_encode($_SESSION['company_id']."!updateRetirement");?>" />
																<input type="hidden" name="r_benefit" id="r_benefit"
																	value="r_gratuity" />
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Salary Head</label>
																	</div>
																	<div class="col-lg-7">
																		<select class="form-control salary_head"
																			id="salary_head" required multiple
																			name="salary_head[]">
		                                          <?php
																																												Session::newInstance ()->_setGeneralPayParams ();
																																												$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
																																												foreach ( $allowDeducArray ['A'] as $allow ) {
																																													
																																													echo "<option value=" . $allow ['pay_structure_id'] . " data-id=" . $allow ['pay_structure_id'] . ">" . $allow ['display_name'] . "</option>";
																																												}
																																												?>
		                                 </select> <label for=""
																			class="show_salary_days">Salary Head is Required</label>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Average Salary Of Last</label>
																	</div>
																	<div class="col-lg-7">
																		<input type="text" class="form-control"
																			name="average_salary" id="average_salary">
																	</div>
																	<div class="col-lg-1" style="padding: 0px;">(Months)</div>
																</div>

																<div class="form-group">
																	<div class="col-lg-3">
																		<label>No of Salary Days</label>
																	</div>
																	<div class="col-lg-7">
																		<input type="text" class="form-control"
																			name="salary_days" id="salary_days">
																	</div>
																</div>

																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Maximum Amount</label>
																	</div>
																	<div class="col-lg-7">
																		<div class="input-group m-bot15">
																			<span class="input-group-addon"><i
																				class="fa fa-rupee"></i></span> <input type="text"
																				class="form-control" name="maximum_amount"
																				id="maximum_amount">
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Round Year Complete</label>
																	</div>
																	<div class="col-lg-7">
																		<label class="checkbox-inline"> <input type="radio"
																			class="" name="round_year" id="round_year" checked
																			value="UP"> Round Up
																		</label> <label class="checkbox-inline"> <input
																			type="radio" class="" name="round_year"
																			id="round_year1" value="DOWN"> Round Down
																		</label>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Excemted Salary Head</label>
																	</div>
																	<div class="col-lg-7">
																		<select class="form-control ex_salary_head"
																			id="ex_salary_head" multiple required
																			name="ex_salary_head[]">
		                                           <?php
																																													Session::newInstance ()->_setGeneralPayParams ();
																																													$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
																																													foreach ( $allowDeducArray ['A'] as $allow ) {
																																														if ($allow ['pay_structure_id'] == 'basic') {
																																															echo "<option value=" . $allow ['pay_structure_id'] . " data-id=" . $allow ['pay_structure_id'] . " selected >" . $allow ['display_name'] . "</option>";
																																														} else {
																																															echo "<option value=" . $allow ['pay_structure_id'] . " data-id=" . $allow ['pay_structure_id'] . ">" . $allow ['display_name'] . "</option>";
																																														}
																																													}
																																													?>
		                                 </select> <label for=""
																			class="ex_show_salary_days">Excemted Salary Head is
																			Required</label> <span>Basic and DA are Excempted, If
																			your are not Provided the DA Select Here</span>
																	</div>
																</div>
														
														</td>
														<td class="retirement_load_td"><br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
															<button class="btn btn-xs btn-success"
																id="gratuity_loader">Submit</button>
															<button class="btn btn-xs btn-danger" id="cancel_gra"
																type="button">Cancel</button>
															</form></td>
													</tr>
													<!-- <tr>
														<td><h5>(ii) Superannuation Fund</h5>
															<span> a) Retirement benefit given to employees by the
																Company. <br> b) IF Company has a link with agencies
																like LIC Superannuation Fund, where their contributions
																are paid.<br> c) The Company pays 15% of basic wages as
																superannuation contribution.No contribution from the
																employee.<br>
														</span></td>
														<td>
															<div class="switch switch-square superannuation" id="r_saf" data-name="r_saf" data-on-label="Enabled"
																data-off-label="Disabled" style="margin-top: 16%;">
																<input type="checkbox" id="c_saf" />
																<img src="../img/ajax-loader.gif" class="retirement_load">
															</div>
														</td>
													</tr> -->

													<tr>
														<td><h5>(iii) Leave Encashment</h5> <span> You have not
																taken leaves and have opted for encashing these leaves,
																your employer would be paying you some amount as leave
																encashment.<br> The amount so received on account of
																encashing the leaves not availed would be liable to tax
																under head "Income from Salary".
														</span></td>
														<td>
															<div class="switch switch-square leave_encash"
																id="r_leaveenc" data-name="r_leaveenc"
																data-on-label="Enabled" data-off-label="Disabled"
																style="margin-top: 16%;">
																<input type="checkbox" id="c_leaveenc" /> <img
																	src="../img/ajax-loader.gif" class="retirement_load">
															</div>
														</td>
													</tr>


													<tr>
														<td><h5>(iii) Retrenchment Fund</h5> <span> Leave pay and
																pro-rata bonuses that are paid at the time of the
																termination of employment do not form part of a
																severance benefit and<br> are subject to tax at normal
																rates applicable to individuals.
														</span>
															<div class="alert alert-success fade in success1"
																style="display: none">
																<button data-dismiss="alert" class="close close-sm"
																	type="button">
																	<i class="fa fa-times"></i>
																</button>
																<span class="status_success1"></span>
															</div>
															<div
																class="alert alert-block alert-danger fade in error1"
																style="display: none">
																<button data-dismiss="alert" class="close close-sm"
																	type="button">
																	<i class="fa fa-times"></i>
																</button>
																<span class="status_error1"></span>
															</div></td>
														<td id="r_retrenchment_s">
															<div class="switch switch-square retrenchment"
																id="r_retrenchment" data-name="r_retrenchment"
																data-on-label="Enabled" data-off-label="Disabled"
																style="margin-top: 16%;">
																<input type="checkbox" id="c_retrenchment" /> <img
																	src="../img/ajax-loader.gif" class="retirement_load">
															</div>
															<div class="text-center">
																<button class="btn btn-xs btn-primary " type="button"
																	id="margin_right_re">
																	<i class="fa fa-pencil"></i> Edit
																</button>
															</div>
														</td>
													</tr>
													<tr class="show_ret" style="display: none">
														<td>
															<form class="form-horizontal" class="retre_edit_form"
																id="retre_edit_form">


																<input type="hidden" name="act" id="act"
																	value="<?php echo base64_encode($_SESSION['company_id']."!updateRetirement");?>" />
																<input type="hidden" name="r_benefit" id="r_benefit"
																	value="r_retrenchment" />
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Salary Head</label>
																	</div>
																	<div class="col-lg-7">
																		<select class="form-control re_salary_head"
																			id="salary_head" required multiple
																			name="salary_head[]">
		                                          <?php
																																												Session::newInstance ()->_setGeneralPayParams ();
																																												$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
																																												foreach ( $allowDeducArray ['A'] as $allow ) {
																																													
																																													echo "<option value=" . $allow ['pay_structure_id'] . " data-id=" . $allow ['pay_structure_id'] . ">" . $allow ['display_name'] . "</option>";
																																												}
																																												?>
		                                 </select> <label for=""
																			class="re_show_salary_days">Salary Head is Required</label>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Average Salary Of Last</label>
																	</div>
																	<div class="col-lg-7">
																		<input type="text" class="form-control"
																			name="average_salary" id="average_salary">
																	</div>
																	<div class="col-lg-1" style="padding: 0px;">(Months)</div>
																</div>

																<div class="form-group">
																	<div class="col-lg-3">
																		<label>No of Salary Days</label>
																	</div>
																	<div class="col-lg-7">
																		<input type="text" class="form-control"
																			name="salary_days" id="salary_days">
																	</div>
																</div>

																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Maximum Amount</label>
																	</div>
																	<div class="col-lg-7">
																		<div class="input-group m-bot15">
																			<span class="input-group-addon"><i
																				class="fa fa-rupee"></i></span> <input type="text"
																				class="form-control" name="maximum_amount"
																				id="maximum_amount">
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Round Year Complete</label>
																	</div>
																	<div class="col-lg-7">
																		<label class="checkbox-inline"> <input type="radio"
																			class="" name="round_year" id="round_year" checked
																			value="UP"> Round Up
																		</label> <label class="checkbox-inline"> <input
																			type="radio" class="" name="round_year"
																			id="round_year1" value="DOWN"> Round Down
																		</label>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-lg-3">
																		<label>Excemted Salary Head</label>
																	</div>
																	<div class="col-lg-7">
																		<select class="form-control re_ex_salary_head"
																			id="ex_salary_head s" multiple required
																			name="ex_salary_head[]">
		                                           <?php
																																													Session::newInstance ()->_setGeneralPayParams ();
																																													$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
																																													foreach ( $allowDeducArray ['A'] as $allow ) {
																																														if ($allow ['pay_structure_id'] == 'basic') {
																																															echo "<option value=" . $allow ['pay_structure_id'] . " data-id=" . $allow ['pay_structure_id'] . " selected>" . $allow ['display_name'] . "</option>";
																																														} else {
																																															echo "<option value=" . $allow ['pay_structure_id'] . " data-id=" . $allow ['pay_structure_id'] . ">" . $allow ['display_name'] . "</option>";
																																														}
																																													}
																																													?>
		                                 </select> <label for=""
																			class="re_ex_show_salary_days">Excemted Salary Head
																			is Required</label> <span>Basic and DA are Excempted,
																			If your are not Provided the DA Select Here</span>
																	</div>
																</div>
														
														</td>
														<td class="retirement_load_td"><br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
															<button class="btn btn-xs btn-success"
																id="gratuity_loader1">Submit</button>
															<button class="btn btn-xs btn-danger" id="cancel_gra1"
																type="button">Cancel</button>
															</form></td>
													</tr>
												</tbody>
											</table>
										</section>
									</div>
								</div>
							</div>

						</div>


						<!--  Allowanes Add Using Allowanes MOdeal-->
						<div aria-hidden="true" aria-labelledby="myModalLabel"
							role="dialog" tabindex="-1" id="allowances_add"
							class="modal fade" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog  modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button aria-hidden="true" data-dismiss="modal" class="close"
											type="button">×</button>
										<h4 class="modal-title">Allowances Add</h4>
									</div>
									<form id="allowances_form" method="POST"
										class="form-horizontal" role="form">
										<input type="hidden" name="change_alias" id="change_alias"
											value="c_"> <input type="hidden" name="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!add");?>" />

										<div class="modal-body">
											<div class="row col-lg-12">
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label">Alias Name <i
														id="alias_nm" class="fa fa-info-circle"></i>
													</label>
													<div class="col-lg-7">
														<input type="text"
															class="form-control alias float_all text_only alpha_no_space"
															id="key" name="aId" minlength="2" maxlength="5"
															placeholder="2-5 characters" value="" data-id='c_' /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found"></span>
													</div>
												</div>
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label">Allowances Name <i
														id="allowance_nm" class="fa fa-info-circle"></i>
													</label>
													<div class="col-lg-7">
														<input type="text"
															class="form-control all_an float_all text_only "
															name="aName" maxlength="20" value=""
															placeholder="Full name of the Allowance" /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found1"></span>
													</div>
												</div>
												<label class="col-lg-12 control-label"
													style="color: #EF7065"> <em id="alloawances_error"></em>
													<div class="btn-group">
														<div
															class="alert alert-warning fade in modal_alerts_allowance"
															style="display: none">
															<button data-dismiss="" class="close close-sm"
																type="button">
																<i class="fa fa-times"></i>
															</button>
															<span class="composed_replys"></span>
														</div>
													</div>

												</label>
											</div>

											<div class="modal-footer">
												<button type="submit" id="allawances"
													class="btn  btn-sm btn-success">Add</button>
												<button type="button" class="btn btn-sm btn-danger"
													data-dismiss="modal">Close</button>

											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!--  Deduction Add Using Deduction MOdeal-->
						<div aria-hidden="true" aria-labelledby="myModalLabel"
							role="dialog" tabindex="-1" id="deductionAdd" class="modal fade"
							data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog  modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button aria-hidden="true" data-dismiss="modal" class="close"
											type="button">×</button>
										<h4 class="modal-title">Deduction Add</h4>
									</div>
									<div class="modal-body">
										<form id="deduction_add" method="POST" class="form-horizontal"
											role="form">
											<input type="hidden" name="change_alias" id="change_alias"
												value="c_">
											<div class="row col-lg-12">
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label"> Alias Name <i
														id="alias_ded_nm" class="fa fa-info-circle"></i></label>
													<div class="col-lg-7">
														<input type="hidden" name="act"
															value="<?php echo base64_encode($_SESSION['company_id']."!add");?>" />
														<input type="text"
															class="form-control alias float_all text_only alpha_no_space"
															id="key1" name="dId" minlength="2" maxlength="5"
															placeholder="2-5 characters" value="" data-id='c_' /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found"></span>
													</div>

												</div>
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label">Name of Deduction <i
														id="deduction_nm" class="fa fa-info-circle"></i></label>
													<div class="col-lg-7">
														<input type="text"
															class="form-control all_an float_all text_only "
															id="dedu" placeholder="Full Name of Deduction"
															name="aName" value=""
															Placeholder="Full Name of Deduction" /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found1"></span>
													</div>
												</div>

												<label class="col-lg-12 control-label"
													style="color: #EF7065"> <em id="deduction_error"></em>
													<div class="btn-group">
														<div
															class="alert alert-warning fade in modal_alerts_deduction"
															style="display: none">
															<button data-dismiss="" class="close close-sm"
																type="button">
																<i class="fa fa-times"></i>
															</button>
															<span class="composed_replys"></span>
														</div>
													</div>
												</label>

											</div>

											<div class="modal-footer">
												<button type="submit" id="deduction"
													class="btn btn-sm btn-success">Add</button>
												<button type="button" class="btn btn-sm btn-danger"
													data-dismiss="modal">Close</button>

											</div>
										</form>
									</div>

								</div>
							</div>
						</div>
						<!--  MiscPayemnt Add Using Misc MOdeal-->
						<div aria-hidden="true" aria-labelledby="myModalLabel"
							role="dialog" tabindex="-1" id="miscPayAdd" class="modal fade"
							data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog  modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button aria-hidden="true" data-dismiss="modal" class="close"
											type="button">×</button>
										<h4 class="modal-title">MisPayment Add</h4>
									</div>
									<div class="modal-body">
										<form id="mispamentForm" method="POST" class="form-horizontal"
											role="form">
											<input type="hidden" name="change_alias" id="change_alias"
												value="m_">
											<div class="row col-lg-12">
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label"> Alias Name <i
														id="alias_mispay_nm" class="fa fa-info-circle"></i></label>
													<div class="col-lg-7">

														<input type="hidden" name="act"
															value="<?php echo base64_encode($_SESSION['company_id']."!add");?>" />
														<input type="text"
															class="form-control alias float_all text_only alpha_no_space"
															id="key3" name="mpId" minlength="2" maxlength="5"
															placeholder="2-5 characters" value="" data-id='m_' /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found"></span>
													</div>
												</div>
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label">Name of MisPayment <i
														id="mispayment_nm" class="fa fa-info-circle"></i></label>
													<div class="col-lg-7">
														<input type="text"
															class="form-control all_an float_all text_only "
															id="mispayid" placeholder="Full Name of Mispayment"
															name="aName" value="" /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found1"></span>
													</div>
												</div>

												<label class="col-lg-12 control-label"
													style="color: #EF7065"> <em id="miscpay_error"></em>
													<div class="btn-group">
														<div
															class="alert alert-warning fade in modal_alerts_mispayment"
															style="display: none">
															<button data-dismiss="" class="close close-sm"
																type="button">
																<i class="fa fa-times"></i>
															</button>
															<span class="composed_replys"></span>
														</div>
													</div>

												</label>

											</div>

											<div class="modal-footer">
												<button type="submit" id="mispayAdd"
													class="btn btn-sm btn-success">Add</button>
												<button type="button" class="btn btn-sm btn-danger"
													data-dismiss="modal">Close</button>

											</div>
										</form>
									</div>

								</div>
							</div>
						</div>
						<!--  MiscDeduction Add Using Misc MOdeal-->
						<div aria-hidden="true" aria-labelledby="myModalLabel"
							role="dialog" tabindex="-1" id="miscdeduc" class="modal fade"
							data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog  modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button aria-hidden="true" data-dismiss="modal" class="close"
											type="button">×</button>
										<h4 class="modal-title">MisDeductions Add</h4>
									</div>
									<div class="modal-body">
										<form id="misdeducForm" method="POST" class="form-horizontal"
											role="form">
											<input type="hidden" name="change_alias" id="change_alias"
												value="m_">
											<div class="row col-lg-12">
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label"> Alias Name <i
														id="alias_misded_nm" class="fa fa-info-circle"></i></label>
													<div class="col-lg-7">
														<input type="hidden" name="act"
															value="<?php echo base64_encode($_SESSION['company_id']."!add");?>" />
														<input type="text"
															class="form-control alias float_all text_only alpha_no_space"
															id="key4" name="mdId" minlength="2" maxlength="5"
															placeholder="2-5 characters" value="" data-id='m_' /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found"></span>
													</div>
												</div>
												<div class="form-group col-lg-12">
													<label class="col-lg-5 control-label">Name of MisDeductions
														<i id="misded_nm" class="fa fa-info-circle"></i>
													</label>
													<div class="col-lg-7">
														<input type="text"
															class="form-control all_an float_all text_only "
															id="misdeducid" placeholder="Full Name of MisDeduction"
															name="aName" value="" /> <img
															src="../img/input-spinner.gif" class="ajax_loader_inside">
														<span class="nm_not_found1"></span>
													</div>
												</div>

												<label class="col-lg-12 control-label"
													style="color: #EF7065"> <em id="miscdeduc_error"></em>
													<div class="btn-group">
														<div
															class="alert alert-warning fade in modal_alerts_misded"
															style="display: none">
															<button data-dismiss="" class="close close-sm"
																type="button">
																<i class="fa fa-times"></i>
															</button>
															<span class="composed_replys"></span>
														</div>
													</div>


												</label>

											</div>

											<div class="modal-footer">
												<button type="submit" id="misdeducAdd"
													class="btn btn-sm btn-success">Add</button>
												<button type="button" class="btn btn-sm btn-danger"
													data-dismiss="modal">Close</button>

											</div>
										</form>
									</div>

								</div>
							</div>
						</div>


					</div>


				</section>
				<!-- page end-->
			</section>
		</section>
		<!-- Dialog Boxes-->
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
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/bootstrap-switch.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/jquery.ui.touch-punch.min.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
	var gra_flag = 0;
	var re_flag = 0;
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
    	      $('#paystructure_tabs a[href="#' + tab + '"]').tab('show');
    	  	
    	  }else{
    		  $('#paystructure_tabs a[href="#payGeneral"]').tab('show');
    		  
    		  } 

    	  // Change hash for page-reload
    	  $('#paystructure_tabs a').on('shown.bs.tab', function (e) {
    	      window.location.hash = e.target.hash;
    	  })
    	});
	$('#paystructure_tabs').on('shown.bs.tab', function (e) {
 	   // newly activated tab
 	   window.scrollTo(0, 0);
 	  if($(e.target).data('loaded') === false){
			if($(e.target).data('title') == 'general'){				
				loadPayStructure("A","D");
			}else if($(e.target).data('title') == 'misc'){				
				loadPayStructure("MP","MD");
			}else if($(e.target).data('title') == 'retire'){				
				retirementBenefits();
			}
		//make the tab loaded true to prevent re-loading while clicking.
   		$(e.target).data('loaded',true);
 		}
 	});
	$('.ex_salary_head,.salary_head,.re_ex_salary_head,.re_salary_head').chosen();
	$('.text_only').bind('keydown blur',function(e){ 
		if (e.ctrlKey || e.altKey) {
			e.preventDefault();
			} else {
			var key = e.keyCode;
			if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
			e.preventDefault();
			
			}
			}
	 });
	 $('.alpha_no_space').keyup(function(e){
		 var node = $(this);
		 node.val(node.val().replace(/ /g,'') ); }
	 );
	
      var dataArray = '';
      $(".allowances_pay_structure").on( "sortupdate", function( event, ui ) {
    	  dataArray = $.map($('#allowances_list').children('li'), function(el){
  	        return {'name':$(el).data('name'),'id':$(el).data('id')}; 
  	    });
    	  });
          $('#create_allowances_btn').on('click', function () {
             if(dataArray!='')
             {
            	 var data = dataArray;
             
              BootstrapDialog.show({
	                title:'Confirmation',
                 message: 'Are Sure you want to Create Allowances Use This Order,This Action Cantbe Undone ?',
                 closable: true,
                 closeByBackdrop: false,
                 closeByKeyboard: false,
                 buttons: [{
                     label: 'Create',
                     cssClass: 'btn-sm btn-success',
                     autospin: true,
                     action: function(dialogRef){
                    	 $.ajax({
                             type: "POST",
                             url: "php/allowance.handle.php",
                             cache: false,
                             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!create");?>', data: data },
                             complete:function(){
		                    	 dialogRef.close();
		                      },
 		                   	 success: function (data) {
                            	 data = JSON.parse(data);
 		                        if (data[0] == "success") {
 		                        	refersh();
 		                           loadPayStructure("A","D");
                                	 BootstrapDialog.alert(data[1]);
                                 }
                                
                             }
                         });
                             		                            
                     }
                 }, {
                     label: 'Close',
                     cssClass: 'btn-sm btn-danger',
                     action: function(dialogRef){
                         dialogRef.close();
                     }
                 }]
             }); 
          }
          else
          {
             	 $('#allowance_show').show();
             	 $('.composed_reply').html("You have Not Made Any Changes");
             	 $('#allowance_show').fadeOut(5000);
             	$(".close-sm").click(function(e){
             		$('#allowance_show').hide();
             	});
          } 
          });
         //cretaDeduction Button  
         var dataArrayded = '';
         $(".deductions_pay_structure").on( "sortupdate", function( event, ui ) {
    	  dataArrayded = $.map($('#deductions_list').children('li'), function(el){
  	        return {'name':$(el).data('name'),'id':$(el).data('id')}; 
  	    });
    	  });
          $('#create_deductions_btn').on('click', function () {
        	  if(dataArrayded!='')
              {
             	 var data = dataArrayded;
        	 BootstrapDialog.show({
	                title:'Confirmation',
                   message: 'Are Sure you want to Create Deduction to Use This Order,This Action Cantbe Undone ?',
                   closable: true,
                   closeByBackdrop: false,
                   closeByKeyboard: false,
                  
                   buttons: [{
                       label: 'Create',
                       cssClass: 'btn-sm btn-success',
                        autospin: true,
                       action: function(dialogRef){
                       	 $.ajax({
    		                    dataType: 'html',
    		                    type: "POST",
    		                    url: "php/deduction.handle.php",
    		                    cache: false,
    		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!create");?>', data: data },
    		                    complete:function(){
    		                    	 dialogRef.close();
    		                      },
    		                    success: function (data) {
    		                    	data = JSON.parse(data);
    		                        if (data[0] == "success") {
    		                        	refersh();
    		                            loadPayStructure("A","D");
    		                        	BootstrapDialog.alert(data[1]);
    		                        }
    		                    }
    		                });
                               		                            
                       }
                   }, {
                       label: 'Close',
                       cssClass: 'btn-sm btn-danger',
                       action: function(dialogRef){
                           dialogRef.close();
                       }
                   }]
               }); 
              }
        	  else
              {
                 	 $('#deductions_show').show();
                 	 $('.composed_reply').html("You have Not Made Any Changes");
                 	$('#deductions_show').fadeOut(5000);
                 	$(".close-sm").click(function(e){
                 		$('#deductions_show').hide();
                 	});
              } 
       });      
//createMicPaymnet 
 var dataArraymiscpay = '';
 $(".miscpay_pay_structure").on( "sortupdate", function( event, ui ) {
	    dataArraymiscpay = $.map($('#miscpay_list').children('li'), function(el){
	 	        return {'name':$(el).data('name'),'id':$(el).data('id')}; 
	 	    });
	    });
 $('#create_miscpay_btn').on('click', function () {
	 if(dataArraymiscpay!='')
     {
    	 var data = dataArraymiscpay;
console.log(dataArraymiscpay);
        	 BootstrapDialog.show({
	                title:'Confirmation',
                   message: 'Are Sure you want to Create MiscPayments to Use This Order,This Action Cantbe Undone ?',
                   closable: true,
                   closeByBackdrop: false,
                   closeByKeyboard: false,
                  
                   buttons: [{
                       label: 'Create',
                       cssClass: 'btn-sm btn-success',
                       autospin: true,
                       action: function(dialogRef){
                       	 $.ajax({
    		                    dataType: 'html',
    		                    type: "POST",
    		                    url: "php/miscAllowances.handle.php",
    		                    cache: false,
    		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!create");?>', data: data },
    		                    complete:function(){
    		                    	 dialogRef.close();
    		                      },
    		                    success: function (data) {
    		                    	data = JSON.parse(data);
    		                        if (data[0] == "success") {
    		                        	mprefresh();
     		                        	loadPayStructure("MP","MD");
    		                        	BootstrapDialog.alert(data[1]);
    		                        }
    		                    }
    		                });
                               		                            
                       }
                   }, {
                       label: 'Close',
                       cssClass: 'btn-sm btn-danger',
                       action: function(dialogRef){
                           dialogRef.close();
                       }
                   }]
               });
     }
	  else
     {
        	 $('#miscpay_show').show();
        	 $('.composed_reply').html("You have Not Made Any Changes");
        	 $('#miscpay_show').fadeOut(5000);
        	$(".close-sm").click(function(e){
        		$('#miscpay_show').hide();
        	});
     } 
            });
//createMicDedection
var dataArraymiscdeduc = '';
 $(".miscdeduc_pay_structure ").on( "sortupdate", function( event, ui ) {
    	  dataArraymiscdeduc = $.map($('#miscdeduc_list').children('li'), function(el){
  	        return {'name':$(el).data('name'),'id':$(el).data('id')}; 
  	    });
    	  });
 $('#create_miscdeduc_btn').on('click', function () {
	 if(dataArraymiscdeduc!='')
     {
    	 var data = dataArraymiscdeduc;
        	 BootstrapDialog.show({
	               title:'Confirmation',
                   message: 'Are Sure you want to Create MiscDeduction to Use This Order,This Action Cantbe Undone ?',
                   closable: true,
                   closeByBackdrop: false,
                   closeByKeyboard: false,
                   buttons: [{
                       label: 'Create',
                       cssClass: 'btn-sm btn-success',
                        autospin: true,
                       action: function(dialogRef){
                       	 $.ajax({
    		                    dataType: 'html',
    		                    type: "POST",
    		                    url: "php/miscDeductions.handle.php",
    		                    cache: false,
    		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!create");?>', data: data },
    		                    complete:function(){
    		                    	 dialogRef.close();
    		                      },
    		                    success: function (data) {
    		                    	data = JSON.parse(data);
    		                        if (data[0] == "success") {
    		                        	mprefresh();
     		                        	loadPayStructure("MP","MD");
    		                        	BootstrapDialog.alert(data[1]);
    		                        }
    		                    }
    		                });
                               		                            
                       }
                   }, {
                       label: 'Close',
                       cssClass: 'btn-sm btn-danger',
                       action: function(dialogRef){
                           dialogRef.close();
                       }
                   }]
               });
 }
 else
 {
    	 $('#miscdeduc_show').show();
    	 $('.composed_reply').html("You have Not Made Any Changes");
    	 $('#miscdeduc_show').fadeOut(5000);
    	$(".close-sm").click(function(e){
    		$('#miscdeduc_show').hide();
    	});}});
          $('.remove').on('click', function () {
              $("#key1").prop("readonly", false);
              $("#key1,.all_an,.alias").val('');
              $('.all_an,.alias').removeClass('wrong correct');
              $("#key").prop("readonly", false);
              $("#key,.all_an,.alias").val('');
          });
          $('.all_an').on('blur', function () {
        	  if ($(this).val() != "") {
        		  var thisd = $(this);
        	  $(this).closest('.col-lg-7').find('.ajax_loader_inside').show();	  
        	  var regex = /^[a-zA-Z ]*$/;
              if (regex.test($(this).val())) {
            	  $('#alloawances_error').html('');
            	  $('#deduction_error').html('');
            	  $('#miscpay_error').html('');
            	  var xhr = $.ajax({
                      type: "POST",
                      url: "php/display_name_check.php",
                      data: { "display_name": $(this).val()},
                      cache: false,
                      beforeSend: function () {   
                      },
                      complete: function () {
                      },
                      success: function (data) {
                          data = JSON.parse(data);
                         $(thisd).closest('.col-lg-7').find('.ajax_loader_inside').hide();
                          if (data.length > 0) {
                        	  $('.all_an').val('');
                              $('.all_an').removeClass('correct');
                              $('.all_an').addClass('wrong');
                              $('.nm_not_found1').fadeIn("Fast");;
                              $('.nm_not_found1').html('Name Already Found Try Other Name');
                              $('.nm_not_found1').fadeOut(3000);
                          } else {
                        	  $('.nm_not_found1').hide();
                              $('.all_an').removeClass('wrong');
                              $('.all_an').addClass('correct');
                          }
                      }
                  });
              } else{
            	  $(thisd).closest('.col-lg-7').find('.ajax_loader_inside').hide();
            	  $('#alloawances_error').html('Enter Valid Allowances Name');
            	  $('#deduction_error').html('Enter Valid Deduction Name');
            	  $('#miscpay_error').html('Enter Valid MiscPayment Name');
                 $('.all_an').val('');
                 $('.all_an').removeClass('wrong correct');
                 $('.all_an').addClass('wrong');
              }
        	  }
          });
          $('.alias').on('blur', function () {
              if ($(this).val() != "") {
                  var thisd = $(this);
                  var aliasname = $(this).data('id');
                  $(this).closest('.col-lg-7').find('.ajax_loader_inside').show();
                    var xhr = $.ajax({
                      type: "POST",
                      url: "php/display_name_check.php",
                      data: { "alias_name": aliasname+$(this).val()},
                      cache: false,
                      beforeSend: function () {                          
                      },
                      complete: function () {
                      },
                      success: function (data) {
                          data = JSON.parse(data);
                        
                          $(thisd).closest('.col-lg-7').find('.ajax_loader_inside').hide();
                          if (data.length > 0) {
                        	  $('.alias').val('');
                              $('.alias').removeClass('correct');
                              $('.alias').addClass('wrong');
                              $('.nm_not_found').fadeIn("Fast");
                              $('.nm_not_found').html('Name Already Found Try Other Name');
                              $('.nm_not_found').fadeOut(3000);
                          } else {
                        	  $('.nm_not_found').hide();
                              $('.alias').removeClass('wrong');
                              $('.alias').addClass('correct');
                          }
                      }
                  });
              }

          });
 $(document).on('click', "#deduction", function (e) {
        	  if($('#key1').val()=='' ||$('#dedu').val()==''){
                  $('#deduction_error').html('* Enter All Values');
                  return false;
               }else{            
              e.preventDefault();
                             $.ajax({
     		                     dataType: 'html',
     		                     type: "POST",
     		                     url: "php/deduction.handle.php",
     		                     cache: false,
	     		                 data: $('#deduction_add').serialize(),
	     		                beforeSend:function(){
	     	                     	$('#deduction').button('loading'); 
	     	                      },
	     	                      complete:function(){
	     	                     	 $('#deduction').button('reset');
	     	                      },
     		                    success: function (data) {
     		                    	data = JSON.parse(data);
     		                        if (data[0] == "success") {
     		                        	 $('.all_an').val('');
     		                        	 $('.close').click();
     		                            $('#deduction_add')[0].reset();
     		                        	BootstrapDialog.alert(data[1]);
     		                        	refersh();
     		                        	loadPayStructure("A","D");
       		                          }else if (data[0] == "error") {
		                                    alert(data[1]);
		                       }
     		                    }
     		                });
                }
                     
          });
          $(document).on('click', "#mispayAdd", function (e) {
        	  if($('#key3').val()=='' ||$('#mispayid').val()==''){
                  $('#miscpay_error').html('* Enter All Values');
                  return false;
               }else{            
              e.preventDefault();
                             $.ajax({
     		                     dataType: 'html',
     		                     type: "POST",
     		                     url: "php/miscAllowances.handle.php",
     		                     cache: false,
	     		                 data: $('#mispamentForm').serialize(),
	     		                beforeSend:function(){
	     	                     	$('#mispayAdd').button('loading'); 
	     	                      },
	     	                      complete:function(){
	     	                     	 $('#mispayAdd').button('reset');
	     	                      },
     		                    success: function (data) {
     		                    	data1 = JSON.parse(data);
     		                    	 if (data1[0] == "success") {
     		                    		 $('.all_an').val('');
     		                            $('.close').click();
     		                            $('#mispamentForm')[0].reset();
     		                        	BootstrapDialog.alert(data1[1]);
     		                        	mprefresh();
     		                        	loadPayStructure("MP","MD");
     		                      }
     		                    }
     		                });
                }
                     
          });
          //msdeduction Add
          $(document).on('click', "#misdeducAdd", function (e) {
        	  if($('#key4').val()=='' ||$('#misdeducid').val()==''){
                  $('#miscdeduc_error').html('* Enter All Values');
                  return false;
               }else{            
              e.preventDefault();
                             $.ajax({
     		                     dataType: 'html',
     		                     type: "POST",
     		                     url: "php/miscDeductions.handle.php",
     		                     cache: false,
	     		                 data: $('#misdeducForm').serialize(),
	     		                beforeSend:function(){
	     	                     	$('#misdeducAdd').button('loading'); 
	     	                      },
	     	                      complete:function(){
	     	                     	 $('#misdeducAdd').button('reset');
	     	                      },
     		                    success: function (data) {
     		                    	data1 = JSON.parse(data);
     		                    	 if (data1[0] == "success") {
     		                    		 $('.all_an').val('');
     		                            $('.close').click();
     		                            $('#misdeducForm')[0].reset();
     		                        	BootstrapDialog.alert(data1[1]);
     		                        	mprefresh();
     		                        	loadPayStructure("MP","MD");
     		                        	}
     		                    }
     		                });
                }
                     
          });
          $(document).on('click', "#allawances", function (e) {
        	  if($('#key').val()=='' ||$('.all_an').val()=='')
              {
                  $('#alloawances_error').html('* Enter All Values');
                  return false;
               }else{
              e.preventDefault();
              $.ajax({
                  dataType: 'html',
                  type: "POST",
                  url: "php/allowance.handle.php",
                  cache: false,
                  data: $('#allowances_form').serialize(),
                  beforeSend:function(){
                   	$('#allawances').button('loading'); 
                    },
                    complete:function(){
                   	 $('#allawances').button('reset');
                    },
                  success: function (data) {
                      data1 = JSON.parse(data);
                      
                      if (data1[0] == "success") {
                           $('.close').click();
                          $('.all_an').val('');
                          $('#allowances_form')[0].reset();
                          BootstrapDialog.alert(data1[1]);
                          refersh();
                          loadPayStructure("A","D");
                          
                      }
                      

                      }});
             }});
        
         $(document).ready(function () {
               flagMisc=0;
               flagRetirement = 0;
              
          });
          
 	    function refersh()
 	    {
 	    	$('#allowances_avail_list,#allowances_list,#deductions_avail_list,#deductions_list').html('');
 	    }
 	    function mprefresh()
 	    {
 	    	 $('#miscpay_avail_list,#miscdeduc_avail_list,#miscdeduc_list,#miscpay_list').html('');
 	    }
          
          function loadPayStructure(dataFirst,dataSecond) {
              $('.box_loader').show();
              $.ajax({
                  type: "POST",
                  url: "php/getPayStructure.php",
                  cache: false,
                  data:{type1:dataFirst,type2:dataSecond,},
                  beforeSend: function () {
                 	 $(".loader_1").loading(true);
                  },
                  complete: function () {
                 $(".loader_1").loading(false);
                  },
                  success: function (data) {
                	  $('.box_loader').hide();
                      data = JSON.parse(data);
                      
                     for (lc = 0; lc < data['type1'].length; lc++) {
                 	 
                          if (data['type1'][lc]['display_flag'] == 0 && data['type1'][lc]['type']== 'A') {
                              $('#allowances_avail_list').append('<li class="draggable" data-name="' + data['type1'][lc]["display_name"] + '" data-id="' + data['type1'][lc]["pay_structure_id"] + '"><span class="'+ data['type1'][lc]["alias_name"] +'">' + data['type1'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type1'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type1'][lc]["alias_name"] +'" data-payId="'+ data['type1'][lc]["pay_structure_id"] +'" data-name="'+ data['type1'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued" id="times" class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type1'][lc]["display_name"] + '" data-alias="'+ data['type1'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');
                          }else if (data['type1'][lc]['display_flag'] == 1 && data['type1'][lc]['type']== 'A') {
                        	  $('#allowances_list').append('<li class="nondraggable" data-name="' + data['type1'][lc]["display_name"] + '" data-id="' + data['type1'][lc]["pay_structure_id"] + '"><span class="'+ data['type1'][lc]["alias_name"] +'">' + data['type1'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type1'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type1'][lc]["alias_name"] +'" data-payId="'+ data['type1'][lc]["pay_structure_id"] +'" data-name="'+ data['type1'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued" id="times" class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type1'][lc]["display_name"] + '" data-alias="'+ data['type1'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');
                              }else if (data['type1'][lc]['display_flag'] == 0 && data['type1'][lc]['type']== 'MP') {
                            	 
                              $('#miscpay_avail_list').append('<li class="draggable" data-name="' + data['type1'][lc]["display_name"] + '" data-id="' + data['type1'][lc]["pay_structure_id"] + '"><span class="'+ data['type1'][lc]["alias_name"] +'">' + data['type1'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type1'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type1'][lc]["alias_name"] +'" data-payId="'+ data['type1'][lc]["pay_structure_id"] +'" data-name="'+ data['type1'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued"  class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type1'][lc]["display_name"] + '" data-alias="'+ data['type1'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i></li>');
                              }else if (data['type1'][lc]['display_flag'] == 1 && data['type1'][lc]['type']== 'MP') {
                             
                              $('#miscpay_list').append('<li class="nondraggable" data-name="' + data['type1'][lc]["display_name"] + '" data-id="' + data['type1'][lc]["pay_structure_id"] + '"><span class="'+ data['type1'][lc]["alias_name"] +'">' + data['type1'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type1'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type1'][lc]["alias_name"] +'" data-payid="'+ data['type1'][lc]["pay_structure_id"] +'" data-name="'+ data['type1'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued"  class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type1'][lc]["display_name"] + '" data-alias="'+ data['type1'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');
                              }if(data['type1'][lc]['display_flag'] == 1 && data['type1'][lc]['type']== 'A' && data['type1'][lc]["display_name"] == 'Basic') {

                                  $('#allowances_list').html('<li class="basic" id="Basic" data-name="' + data['type1'][lc]["display_name"] + '" data-id="' + data['type1'][lc]["pay_structure_id"] + '"><span class="'+ data['type1'][lc]["alias_name"] +'">' + data['type1'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type1'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type1'][lc]["alias_name"] +'" data-payId="'+ data['type1'][lc]["pay_structure_id"] +'" data-name="'+ data['type1'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued" id="times" class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type1'][lc]["display_name"] + '" data-alias="'+ data['type1'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');
                              }
                          }
                     for (lc = 0; lc < data['type2'].length; lc++ ) {
                       
                    	  if (data['type2'][lc]['display_flag'] == 0 && data['type2'][lc]['type']== 'D') {
                             
                              $('#deductions_avail_list').append('<li class="draggable" data-name="' + data['type2'][lc]["display_name"] + '" data-id="' + data['type2'][lc]["pay_structure_id"] + '"><span class="'+ data['type2'][lc]["alias_name"] +'">' + data['type2'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type2'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type2'][lc]["alias_name"] +'" data-payId="'+ data['type2'][lc]["pay_structure_id"] +'" data-name="'+ data['type2'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued" id="times" class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type2'][lc]["display_name"] + '" data-alias="'+ data['type2'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');
                              } else if (data['type2'][lc]['display_flag'] == 1  && data['type2'][lc]['type']== 'D') {
                            
                              $('#deductions_list').append('<li class="nondraggable" data-name="' + data['type2'][lc]["display_name"] + '" data-id="' + data['type2'][lc]["pay_structure_id"] + '"><span class="'+ data['type2'][lc]["alias_name"] +'">' + data['type2'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type2'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type2'][lc]["alias_name"] +'" data-payId="'+ data['type2'][lc]["pay_structure_id"] +'" data-name="'+ data['type2'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued" class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type2'][lc]["display_name"] + '" data-alias="'+ data['type2'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');
                              }else if (data['type2'][lc]['display_flag'] == 0 && data['type2'][lc]['type']== 'MD') {
                             
                              $('#miscdeduc_avail_list').append('<li class="draggable" data-name="' + data['type2'][lc]["display_name"] + '" data-id="' + data['type2'][lc]["pay_structure_id"] + '"><span class="'+ data['type2'][lc]["alias_name"] +'">' + data['type2'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type2'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type2'][lc]["alias_name"] +'" data-payId="'+ data['type2'][lc]["pay_structure_id"] +'" data-name="'+ data['type2'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued" id="times" class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type2'][lc]["display_name"] + '" data-alias="'+ data['type2'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');
                              } else if (data['type2'][lc]['display_flag'] == 1  && data['type2'][lc]['type']== 'MD') {
                              
                              $('#miscdeduc_list').append('<li class="nondraggable" data-name="' + data['type2'][lc]["display_name"] + '" data-id="' + data['type2'][lc]["pay_structure_id"] + '"><span class="'+ data['type2'][lc]["alias_name"] +'">' + data['type2'][lc]["display_name"] + '</span><div class="value_column" style="display:none"><input type="text" name="edit" id="'+ data['type2'][lc]["alias_name"] +'" class="form-control edit"/><i id="valued" data-alias="'+ data['type2'][lc]["alias_name"] +'" data-payid="'+ data['type2'][lc]["pay_structure_id"] +'" data-name="'+ data['type2'][lc]["display_name"] +'" class="fa fa-check valued_tick"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued"  class="fa fa fa-times valued times"></i></div><i id="sortable_edit" data-name="' + data['type2'][lc]["display_name"] + '" data-alias="'+ data['type2'][lc]["alias_name"] +'" class="fa fa-pencil edit_show"></i><i id="fa_remove" class="fa fa-remove" style="display:none;"></i></li>');	
                              }
                      }
                 
                      initializeAllowance();
                      initializeDeduction();
                      initializeMiscpay();
                      initializeMiscdeduc();
                     }});}
          function initializeAllowance() {
        	  allowances = $(".allowances_pay_structure").sortable({
        		  connectWith: '.allowances_pay_structure',
        		  items: "li:not(.basic)",
        		  out: function(ev, ui) {
            		 
        	            if(ui.item.hasClass("nondraggable"))
        	              ui.sender.sortable("false");
        	        }});
  	     
        	  }
          function initializeDeduction() {
        	  deductions = $(".deductions_pay_structure").sortable({
        		  connectWith: '.deductions_pay_structure',
        		  out: function(ev, ui) {
        	            if(ui.item.hasClass("nondraggable"))
        	              ui.sender.sortable("false");
        	        }});}
          function initializeMiscpay() {
        	  miscpay = $(".miscpay_pay_structure").sortable({
        		  connectWith: '.miscpay_pay_structure',
        		   out: function(ev, ui) {
        	            if(ui.item.hasClass("nondraggable"))
        	              ui.sender.sortable("false");
        	        }});}
          function initializeMiscdeduc() {
        	  miscdeduc = $(".miscdeduc_pay_structure").sortable({
        		  connectWith: '.miscdeduc_pay_structure',
        		  out: function(ev, ui) {
        	            if(ui.item.hasClass("nondraggable"))
        	              ui.sender.sortable("false");
        	        }});
        	     }
          $(document).on("click",".edit_show",function(e){
        	  e.preventDefault();
        	  $(this).hide();
        	  var data = $(this).attr('data-name');
              var datas = $(this).data('alias');
              $("#"+datas).val(data);
              $(this).closest('.nondraggable,.draggable').find('.value_column').show();
              $('.'+datas).hide();
              $(this).closest('.nondraggable,.draggable').find(".edit").focus().select();
                $(document).on("click",".valued",function(e){
                    $('.edit_show').show();
                	$(this).closest('.nondraggable,.draggable').find('.value_column').hide();
                	$('.'+datas).show().text(data);
              });
                var esc = $.Event("keydown", { keyCode: 27 });
                $('body').keydown(function(e) {
              	    if (e.keyCode == 27) {
              	    	$('.edit_show').show();
                    	$('.nondraggable,.draggable').find('.value_column').hide();
                    	$('.'+datas).show().text(data);
                }
              	    });});
          $(document).on("click",".valued_tick",function(e){
        	  var alias = $(this).data('alias');
        	  var name = $(this).data('name');
              var daya = $("#"+alias).val();
              var thisd = $(this);
              if(daya != name)
              {
              if(daya!='')
              {
            	$('.edit_show').show();
              var pay_structure_id = $(this).data('payid');
               var aliasorfull = 'F';  
              $(this).closest('.nondraggable,.draggable').attr('data-name',daya);
              $(this).hide();
              $('.loader_away').show(); 
              $.ajax({
                  dataType: 'html',
                  type: "POST",
                  url: "php/allowance.handle.php",
                  cache: false,
                  data: {paystructureId:pay_structure_id,newName:daya,aliasorfull:aliasorfull,act: '<?php echo base64_encode($_SESSION['company_id']."!nameChange");?>'},
                  success: function (data) {
                      data1 = JSON.parse(data);
                      $('.loader_away').hide();
                      $('.valued_tick').show();
                      if (data1[0] == "success") {
                    	  $('.'+alias).show().text(daya);
                    	  $(thisd).closest('.nondraggable,.draggable').find('.value_column').hide();
                    	  $(thisd).closest('.nondraggable,.draggable').find('.edit_show').attr('data-name',daya);
                      }
                      else if(data1[0] == "error"){
                    	  $('#error_show').show();
                      	 $('.composed_reply').html("Name Changes Doesn't Made");
                      	 $('#error_show').fadeOut(5000);
                      	$(".close-sm").click(function(e){
                      		$('#error_show').hide();
                      	});
                      }
                  }});
              }
              }
              else
              {
            	  $('.'+alias).show().text(name);
            	  $(thisd).closest('.nondraggable,.draggable').find('.value_column').hide();
            	  $(thisd).closest('.nondraggable,.draggable').find('.edit_show').show();
              }
       	    });
     	    
     	    $('#alias_nm').click(function (e){
         	    
     	    	$('.composed_replys').html('');
				$('.modal_alerts_allowance').show();
				$('.modal_alerts_allowance').find('.composed_replys').html('Alias Name Should Be 2-5 characters');
				$('.close').click(function(e){
					$(this).closest('.modal_alerts_allowance').hide();
				});
     	    });
     	   $('#allowance_nm').click(function (e){
     		    $('.composed_replys').html('');
				$('.modal_alerts_allowance').show();
				$('.modal_alerts_allowance').find('.composed_replys').html('Allowance Name: Full name of the Allowance Which Will be Displayed on the Payslip');
				$('.close').click(function(e){
					$(this).closest('.modal_alerts_allowance').hide();
				});
    	    });
   	    $('#alias_ded_nm').click(function (e){
    	    	$('.composed_replys').html('');
				$('.modal_alerts_deduction').show();
				$('.modal_alerts_deduction').find('.composed_replys').html('Alias Name Should Be 2-5 characters');
				$('.close').click(function(e){
					$(this).closest('.modal_alerts_deduction').hide();
				});
    	    });
     	  $('#deduction_nm').click(function (e){
   		    $('.composed_replys').html('');
				$('.modal_alerts_deduction').show();
				$('.modal_alerts_deduction').find('.composed_replys').html('Deduction Name: Full name of the Deduction Which Will be Displayed on the Payslip');
				$('.close').click(function(e){
					$(this).closest('.modal_alerts_deduction').hide();
				});
  	    });
     	  $('#alias_mispay_nm').click(function (e){
  	    	$('.composed_replys').html('');
				$('.modal_alerts_mispayment').show();
				$('.modal_alerts_mispayment').find('.composed_replys').html('Alias Name Should Be 2-5 characters');
				$('.close').click(function(e){
					$(this).closest('.modal_alerts_mispayment').hide();
				});
  	    });
   	  $('#mispayment_nm').click(function (e){
 		    $('.composed_replys').html('');
				$('.modal_alerts_mispayment').show();
				$('.modal_alerts_mispayment').find('.composed_replys').html('Mispayment Name: Full name of the Mispayment Which Will be Displayed on the Payslip');
				$('.close').click(function(e){
					$(this).closest('.modal_alerts_mispayment').hide();
				});
	    });
   	  $('#alias_misded_nm').click(function (e){
	    	$('.composed_replys').html('');
			$('.modal_alerts_misded').show();
			$('.modal_alerts_misded').find('.composed_replys').html('Alias Name Should Be 2-5 characters');
			$('.close').click(function(e){
				$(this).closest('.modal_alerts_misded').hide();
			});
	    });
	  $('#misded_nm').click(function (e){
		    $('.composed_replys').html('');
			$('.modal_alerts_misded').show();
			$('.modal_alerts_misded').find('.composed_replys').html('Deduction Name: Full name of the Deduction Which Will be Displayed on the Payslip');
			$('.close').click(function(e){
				$(this).closest('.modal_alerts_misded').hide();
			});
    });
	  function showGraduty(){
			var selected_id = $('.salary_head :selected').data('id');
			var selected_id1 = $('.re_salary_head :selected').data('id');
			var ex_selected_id = $('.ex_salary_head :selected').data('id');
			var ex_selected_id1 = $('.re_ex_salary_head :selected').data('id');
		    if(!selected_id){
		  $('.show_salary_days').show();
		}else{
			$('.show_salary_days').hide();
			
		}if(!ex_selected_id){
			$('.ex_show_salary_days').show();
		}else{
			$('.ex_show_salary_days').hide();
		}
			if(!ex_selected_id1){
				$('.re_ex_show_salary_days').show();
			}else{
				$('.re_ex_show_salary_days').hide();
			}if(!selected_id1){
				  $('.re_show_salary_days').show();
			}else{
				$('.re_show_salary_days').hide();
				
			}
				}
		 $('#gratuity_loader').click(function(){
			 showGraduty();
			});
		 $('#gratuity_loader1').click(function(){
			 showGraduty();
			});
		$('.salary_head,.ex_salary_head').change(function(e){
			showGraduty();
		});	
		$('.re_salary_head,.re_ex_salary_head').change(function(e){
			showGraduty();
		});	
	 $('.gratuity').on('switch-change', function (e, data) {
		
		 if(data.value == true){
		 var enable  = '1';
		 }else{
		 enable = '0';
		 }
	 	 var id = $(this).data('name');
	 	 $(this).find('.switch-animate').css('opacity','0.1');
	 	 var thisd =  $(this);
	 	 retirementBenefit(id,enable,thisd);
	});  
	 $('.superannuation').on('switch-change', function (e, data) {
		 if(data.value == true){
		 var enable  = '1';
		 }else{
		 enable = '0';
		 }
	 	 var id = $(this).data('name');
	 	$(this).find('.switch-animate').css('opacity','0.1');
	 	 var thisd =  $(this);
	 	 retirementBenefit(id,enable,thisd);
	}); 
	 $('.leave_encash').on('switch-change', function (e, data) {
		 if(data.value == true){
		 var enable  = '1';
		 }else{
		 enable = '0';
		 }
	 	 var id = $(this).data('name');
	 	$(this).find('.switch-animate').css('opacity','0.1');
	 	 var thisd =  $(this);
	 	 retirementBenefit(id,enable,thisd);
	}); 
	 $('.retrenchment').on('switch-change', function (e, data) {
		 if(data.value == true){
		 var enable  = '1';
		 }else{
		 enable = '0';
		 }
	 	 var id = $(this).data('name');
	 	$(this).find('.switch-animate').css('opacity','0.1');
	 	 var thisd =  $(this);
	 	 retirementBenefit(id,enable,thisd);
	}); 	 
	  function retirementBenefit(id,enable,thisd) {
		  $(thisd).find('.retirement_load').show();
          $.ajax({
              type: "POST",
              url: "php/retirementBenefit.handle.php",
              cache: false,
              data:{rId:id,rEnable:enable,act:'<?php echo base64_encode($_SESSION['company_id']."!benefitEnable");?>'},
              beforeSend: function () {
             	 $(".loader_1").loading(true);
              },
              complete: function () {
             $(".loader_1").loading(false);
              },
              success: function (data) {
            	  $(thisd).find('.retirement_load').hide();
            	  $(thisd).find('.switch-animate').css('opacity','1');
            	  if(id == 'r_gratuity' && enable == '0'){
            		  $('.show_gra').hide();
            	  }else{
            		
            	  }
            	  if(id == 'r_retrenchment' && enable == '0'){
            		  $('.show_ret').hide();
            	  }else{
            		  
            	  }
            	  
            	 if(enable == '0'){
            		
                	 $(thisd).closest('#r_gratuity_s').find('#margin_right_pr').hide();
                	 $(thisd).closest('#r_retrenchment_s').find('#margin_right_re').hide();
            	 }else{
            		 $(thisd).closest('#r_gratuity_s').find('#margin_right_pr').show();
            		 $(thisd).closest('#r_retrenchment_s').find('#margin_right_re').show();
            	 }
                  }
                  
                 });}
      
	 function retirementBenefits(){
		
		   $.ajax({
	              type: "POST",
	              url: "php/retirementBenefit.handle.php",
	              cache: false,
	              data:{act:'<?php echo base64_encode($_SESSION['company_id']."!getBenefits");?>'},
	              beforeSend: function () {
	             	 $(".loader_1").loading(true);
	              },
	              complete: function () {
	             $(".loader_1").loading(false);
	              },
	              success: function (data) {
	            	data = JSON.parse(data);
	            	if (data[0] == "success") {
	            	for (lc = 0; lc < data[2].length; lc++ ) {
		              if(data[2][lc]['display_flag'] == '0' && data[2][lc]['pay_structure_id'] == 'r_gratuity'){
		            	  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,false);
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-off");
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-on");
		            	  $('#margin_right_pr,.show_gra').hide();
                      }else if(data[2][lc]['display_flag'] == '1' && data[2][lc]['pay_structure_id'] == 'r_gratuity'){
                    	  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,true);
                    	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-on");
                          $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-off");
                          $('#margin_right_pr').show();
                      }
		             /* if(data[2][lc]['display_flag'] == '0' && data[2][lc]['pay_structure_id'] == 'r_saf'){
		            	  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,false);
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-off");
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-on");
		            	  }
	            	  else if(data[2][lc]['display_flag'] == '1' && data[2][lc]['pay_structure_id'] == 'r_saf'){
	            		  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,true);
	            		  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-on");
                          $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-off");
                          
                      }*/ if(data[2][lc]['display_flag'] == '0' && data[2][lc]['pay_structure_id'] == 'r_leaveenc'){
                    	  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,false);
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-off");
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-on");
                          
			              
                      }else if(data[2][lc]['display_flag'] == '1' && data[2][lc]['pay_structure_id'] == 'r_leaveenc'){
                    	  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,true);
                    	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-on");
                          $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-off");
                          
                      } else if(data[2][lc]['display_flag'] == '0' && data[2][lc]['pay_structure_id'] == 'r_retrenchment'){
                    	  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,false);
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-off");
		            	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-on");
		            	  $('#margin_right_re,.show_ret').hide();
                      }else if(data[2][lc]['display_flag'] == '1' && data[2][lc]['pay_structure_id'] == 'r_retrenchment'){
                    	  $('#'+data[2][lc]['pay_structure_id']).bootstrapSwitch('setState',data.value,true);
                    	  $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').addClass("switch-on");
                          $('#'+data[2][lc]['pay_structure_id']).find('.switch-animate').removeClass("switch-off");
                          $('#margin_right_re').show();
                      }
						}
							
	            	  }
	                  }
	                  
	                 });
		 }
		
	
	 $('#margin_right_pr').click(function(e){
		 if(gra_flag == 0){
		 $.ajax({
	            type: "POST",
	            url: "php/retirementBenefit.handle.php",
	            cache: false,
	            data:{act:'<?php echo base64_encode($_SESSION['company_id']."!selectRetirement");?>',r_benefit:'r_gratuity'},
	            success: function (data) {
	                data = JSON.parse(data);
	               
	                if(data[0] == 'success'){
	                	 $.each(data[2][0].salary_heads.split(","), function (i, e) {
	                		 $('#gratuity_edit_form').find("#salary_head option[value='" + e + "']").prop("selected", true);
                         });
                   
	            	 $('#gratuity_edit_form').find('#salary_head').trigger('chosen:updated');	
				     $.each(data[2][0].salary_heads_it_exempted.split(","), function (i, e) {
                		 $('#gratuity_edit_form').find("#ex_salary_head option[value='" + e + "']").prop("selected", true);
                     });
               
	            	 $('#gratuity_edit_form').find('#ex_salary_head').trigger('chosen:updated');
				        if(data[2][0].round_service_years == 'UP'){
				        	$('#gratuity_edit_form').find( "#round_year" ).prop( "checked", true );
				        }else{
				        	$('#gratuity_edit_form').find( "#round_year1" ).prop( "checked", true );
				        }
						$('#gratuity_edit_form').find('#average_salary').attr('value',data[2][0].salary_average_months);
						$('#gratuity_edit_form').find('#salary_days').attr('value',data[2][0].salary_days);	
						$('#gratuity_edit_form').find('#maximum_amount').attr('value',data[2][0].maximum_amount);	
						 $('#ex_salary_head_chosen').find('a[data-option-array-index="0"]').hide();	
				       
	                }
	            }
		 });
		 }
		 gra_flag = 1;
	 });
	 $('#margin_right_re').click(function(e){
		 if(re_flag == 0){
		 $.ajax({
	            type: "POST",
	            url: "php/retirementBenefit.handle.php",
	            cache: false,
	            data:{act:'<?php echo base64_encode($_SESSION['company_id']."!selectRetirement");?>',r_benefit:'r_retrenchment'},
	            success: function (data) {
	                data = JSON.parse(data);
	                if(data[0] == 'success'){
	                	$.each(data[2][0].salary_heads.split(","), function (i, e) {
	                		 $('#retre_edit_form').find("#salary_head option[value='" + e + "']").prop("selected", true);
                        });
                  
	            	 $('#retre_edit_form').find('#salary_head').trigger('chosen:updated');	
				     	
	            	 $.each(data[2][0].salary_heads_it_exempted.split(","), function (i, e) {
               		 $('#retre_edit_form').find(".re_ex_salary_head option[value='" + e + "']").prop("selected", true);
               		 
                    });
              
	            	 $('#retre_edit_form').find('#ex_salary_head').trigger('chosen:updated');
				    	   
				    	 
					        if(data[2][0].round_service_years == 'UP'){
					        	$('#retre_edit_form').find( "#round_year" ).prop( "checked", true );
					        }else{
					        	$('#retre_edit_form').find( "#round_year1" ).prop( "checked", true );
					        }
				    	 $('.re_salary_head,.re_ex_salary_head').trigger("chosen:updated");	
						$('#retre_edit_form').find('#average_salary').attr('value',data[2][0].salary_average_months);
						$('#retre_edit_form').find('#salary_days').attr('value',data[2][0].salary_days);	
						$('#retre_edit_form').find('#maximum_amount').attr('value',data[2][0].maximum_amount);	
						$('#retre_edit_form').find('#ex_salary_head_s_chosen a[data-option-array-index="0"]').hide();
				       }    
	                }
	            
		 });
		 }
		 re_flag = 1;
	 });
	 $('#margin_right_pr,#cancel_gra').click(function(e){
		$('.show_gra').slideToggle('fast');	 
		$('.salary_head,.ex_salary_head').trigger("chosen:updated");	
		});
	$('#margin_right_re,#cancel_gra1').click(function(e){
		$('.show_ret').slideToggle('fast');	 
		$('.salary_head,.ex_salary_head').trigger("chosen:updated");	
		});	
    function gradutity(data_seriliaze,loader,gradutity){
    	$.ajax({
            type: "POST",
            url: "php/retirementBenefit.handle.php",
            cache: false,
            data:data_seriliaze.serialize(),
            beforeSend: function () {
           		loader.button('loading'); 
             },
             complete:function(){
            	 loader.button('reset');
             },
            success: function (data) {
                data = JSON.parse(data);
                if(data[0] == 'success'){
               	 
               	 if(gradutity == 'Gradutity'){
					$('.show_gra').hide();
					$('.success').show();
	               	 $('.status_success').html(gradutity+' Updated Successfully');
	               	 $('.success').fadeOut(5000);
               	 }else if(gradutity == 'Retrenchment'){
					$('.show_ret').hide();
					$('.success1').show();
	               	 $('.status_success1').html(gradutity+' Updated Successfully');
	               	 $('.success1').fadeOut(5000);
               	 }
                }else if(data[0] == 'error'){
                	if(gradutity == 'Gradutity'){
    					$('.show_gra').hide();
    					$('.error').show();
    	               	 $('.error_success').html(gradutity+' Updated Successfully');
    	               	 $('.error').fadeOut(5000);
                   	 }else if(gradutity == 'Retrenchment'){
    					$('.show_ret').hide();
    					$('.error1').show();
    	               	 $('.error_success1').html(gradutity+' Updated Successfully');
    	               	 $('.error1').fadeOut(5000);
                }
            }
            }
            
        });

    }
	
	$('#gratuity_edit_form').validate({
		rules:{
			salary_head:'required',
			average_salary:'required',
			salary_days:'required',
			maximum_amount:'required',
		},
		messages:{
			salary_head:'Salary head is Required',
			average_salary:'Average Salary Of Last Month is Required',
			salary_days:'No of Salary Days is Required',
			maximum_amount:'Maximum Amount is Required',
		},
		submitHandler: function(form) { 
			gradutity($('#gratuity_edit_form'),$('#gratuity_loader'),'Gradutity');	
		 
		}

	});
	$('#retre_edit_form').validate({
		rules:{
			salary_head:'required',
			average_salary:'required',
			salary_days:'required',
			maximum_amount:'required',
		},
		messages:{
			salary_head:'Salary head is Required',
			average_salary:'Average Salary Of Last Month is Required',
			salary_days:'No of Salary Days is Required',
			maximum_amount:'Maximum Amount is Required',
		},
		submitHandler: function(form) { 
			gradutity($('#retre_edit_form'),$('#gratuity_loader1'),'Retrenchment');	
		 	
		}

	});
      </script>
</body>
</html>