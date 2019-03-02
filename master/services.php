<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<meta name="keyword" content="">
<link rel="shortcut icon" href="img/favicon.png">
<title>Manage Services</title>
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
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />

<style>
#loader {
	height: 45%;
	width: 79%;
}

#sortable_edit {
	float: right;
	margin-right: 10px;
	cursor: pointer;
}

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

#companyName_chosen, #serviceChosen_chosen, #taxChosen_chosen {
	width: 100% !important;
}

#serviceSample_filter, #taxSample_filter {
	margin-top: -5%;
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
				<section class="panel">
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs" id="managingServicesTab">
							<li class=""><a href="#servicesTab" data-id="p"
								data-title="servicetitle" data-toggle="tab" data-loaded="false"
								id="servicetitle"> Services </a></li>
							<li class=""><a href="#taxTab" data-id="i" data-toggle="tab"
								id="taxtitleAllowid" data-title="taxtitle" data-loaded="false">Tax</a></li>
							<li class=""><a href="#mappingTab" data-id="t" data-toggle="tab"
								id="maptitlementClick" data-title="maptitle" data-loaded="false">Company
									Services</a></li>
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
							<div class="tab-pane active" id="servicesTab">
								<div class="col-lg-12">
									<div class="btn-group pull-right">
										<button id="service_HideSeek" type="button"
											class="btn btn-sm btn-info" style="margin-top: -65%;">
											<i class="fa fa-plus"></i> Add
										</button>
									</div>
									<form class="form-horizontal displayHide" role="form"
										method="post" id="service_AddForm">
										<input type="hidden" name="act"
											value="<?php echo base64_encode("!serviceInsert");?>" />
										<div class="col-lg-12">
											<div class="panel-body">
												<div class="form-group">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Service
														Name</label>
													<div class="col-lg-5">
														<input type="text" class="form-control" name="serviceName"
															id="serviceName" maxlength="30" placeholder="">
													</div>
												</div>
												<div class="form-group">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Service
														Description</label>
													<div class="col-lg-5">
														<textarea class="form-control" name="serviceDec"
															id="serviceDec" maxlength="100" rows="2"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Is Variable</label>
													<div class="col-lg-7">
														<label for="yesIs" class="col-lg-4 control-label"> <input
															name="isVariable" id="yesIs" value="1" type="radio"> Yes
														</label> <label for="noIs" class="col-lg-4 control-label">
															<input name="isVariable" checked id="noIs" value="0"
															type="radio"> No
														</label>
													</div>
												</div>
												<div class="form-group displayHide price">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Exempt
														Count</label>
													<div class="col-lg-5">
														<input class="form-control" type="text" name="examptTo"
															id="priceAmount" value="0">
													</div>
												</div>

												<div class="form-group">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Amount</label>
													<div class="col-lg-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
															<div class="iconic-input right">
																<input class="form-control" type="text" name="price"
																	value="0">
															</div>
														</div>
													</div>
												</div>

												<div class="form-group">
													<div class="col-lg-offset-3 col-lg-5" align="right">
														<span id="service-error" class="help-block pull-left"> </span>
														<button type="submit" class="btn btn-sm btn-success"
															id="addbuttonService">Add</button>
														<button type="button" class="btn btn-sm btn-danger"
															id="service_cancel">Cancel</button>
													</div>
												</div>
											</div>
										</div>

									</form>
									<div id="serviceTable"></div>
								</div>
							</div>

							<div class="tab-pane" id="taxTab">
								<div class="col-lg-12">
									<div class="btn-group pull-right">
										<button id="tax_HideSeek" type="button"
											class="btn btn-sm btn-info" style="margin-top: -65%;">
											<i class="fa fa-plus"></i> Add
										</button>
									</div>
									<form class="form-horizontal displayHide" role="form"
										method="post" id="tax_AddForm">
										<input type="hidden" name="act"
											value="<?php echo base64_encode("!taxInsert");?>" />
										<div class="col-lg-12">
											<div class="panel-body">
												<div class="form-group">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Tax
														Title</label>
													<div class="col-lg-5">
														<input type="text" class="form-control" name="taxTitle"
															id="taxTitle" maxlength="30">
													</div>
												</div>
												<div class="form-group">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Tax
														Description</label>
													<div class="col-lg-5">
														<textarea class="form-control" name="taxDec"
															maxlength="100" id="taxDec" rows="2"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Tax
														Percentage</label>
													<div class="col-lg-5">
														<div class="input-group">
															<div class="iconic-input right">
																<input class="form-control" type="text" id="taxPercent"
																	name="taxPercent" value="0">
															</div>
															<span class="input-group-addon">%</span>
														</div>
													</div>
												</div>

												<div class="form-group">
													<div class="col-lg-offset-3 col-lg-5" align="right">
														<span id="tax-error" class="help-block pull-left"
															style="display: none;"> Enter All Required Fields</span>
														<button type="submit" class="btn btn-sm btn-success"
															id="taxAddButton">Add</button>
														<button type="button" class="btn btn-sm btn-danger"
															id="tax_cancel">Cancel</button>
													</div>
												</div>
											</div>
										</div>
									</form>

									<div id="taxTable"></div>
								</div>

							</div>
							<div class="tab-pane" id="mappingTab">
								<form class="form-horizontal">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">
												Company Name</label>
											<div class="col-lg-5">
												<select class="form-control" id="companyName"
													name="deductions_type[]">
													<option value="">Select Company</option>
                                        <?php
                                        require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/company.class.php");
                                        $company = new Company ();
                                        $company->conn = $conn;
                                        $companyVal = $company->filterCompany (1);
                                        foreach ( $companyVal['data'][0] as $row ) {
                                        	echo "<option data-leave='" . $row['leave_based_on'] . "' data-val='" . $row['current_payroll_month'] . "'
			data-id='" . $row['company_db_name'] . "' value='" . $row['company_id'] . "'>" . $row['company_name'] . "</option>";
                                        }
                                        /*
$stmt = mysqli_prepare ( $conn, "SELECT leave_based_on,current_payroll_month,company_id,company_name,company_db_name FROM company_details WHERE  enabled =1 " );
$result = mysqli_stmt_execute ( $stmt );
mysqli_stmt_bind_result ( $stmt, $leaveBased, $payrollMOnth, $companyId, $companyName, $userName );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	echo "<option data-leave='" . $leaveBased . "' data-val='" . $payrollMOnth . "' data-id='" . $userName . "' value='" . $companyId . "'>" . $companyName . "</option>";
}
*/
																																								?>
                                           </select>

											</div>
										</div>
									</div>
									<div id="loader"></div>
									<div class="col-lg-12 displayHide" id="mappingdiv">
										<div class="panel-body">
											<div class="col-lg-6">
												<header class="panel-heading">
													Mapped Services
													<button type="button" href="#mapServices"
														data-toggle="modal" class="btn btn-xs btn-info pull-right">
														<i class="fa fa-plus"></i> Add
													</button>
												</header>
												<ol id="services_list"
													class='services_structure vertical sortable'>
												</ol>
											</div>
											<div class="col-lg-6">
												<header class="panel-heading">
													Mapped Taxes
													<button type="button" href="#mapTaxes" data-toggle="modal"
														class="btn btn-xs btn-info pull-right">
														<i class="fa fa-plus"></i> Add
													</button>
												</header>
												<ol id="tax_list" class='tax_structure vertical sortable'>
													<img src="../img/ajax-loader.gif" class="box_loader">
												</ol>
												<label class="help-block text-danger" id="error-msg"
													style="margin-bottom: 0px;"></label> <br>
											</div>
											<div class="">
												<button type="button" id="companyServiceMapp"
													class="btn btn-success btn-block">Add</button>
											</div>
										</div>
									</div>

								</form>
							</div>
						</div>
					</div>
				</section>
				<!-- page end-->

				<!-- MOdel Section -->


				<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
					tabindex="-1" id="serviceEdit" class="modal fade"
					data-keyboard="false" data-backdrop="static">
					<div class="modal-dialog  modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close"
									type="button">&times;</button>
								<h4 class="modal-title">Service Edit</h4>
							</div>
							<form id="serviceEditForm" method="POST" class="form-horizontal"
								role="form">
								<div class="modal-body">
									<div class="row  col-lg-12">
										<input type="hidden" name="serv_id" id="serv_id" /> <input
											type="hidden" name="act"
											value="<?php echo base64_encode("!serviceUpdate");?>" />
										<div class="col-lg-12">
											<div class="panel-body">
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Service
														Name</label>
													<div class="col-lg-7">
														<input type="text" class="form-control" name="serviceName"
															id="e_serviceName" maxlength="30" placeholder="">
													</div>
												</div>
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Service
														Description</label>
													<div class="col-lg-7">
														<textarea class="form-control" name="serviceDec"
															id="e_serviceDec" maxlength="100" rows="2"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 col-sm-5 control-label">Is Variable</label>
													<div class="col-lg-7">
														<label for="e_yesIs" class="col-lg-4 control-label"> <input
															name="isVariable" id="e_yesIs" value="1" type="radio">
															Yes
														</label> <label for="e_noIs"
															class="col-lg-4 control-label"> <input name="isVariable"
															id="e_noIs" value="0" type="radio"> No
														</label>
													</div>
												</div>

												<div class="form-group displayHide e_price">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Exempt
														Count</label>
													<div class="col-lg-7">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
															<div class="iconic-input right">
																<input class="form-control" type="text" name="examptTo"
																	id="e_priceAmount" value="0">
															</div>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Maximum
														Amount</label>
													<div class="col-lg-7">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
															<div class="iconic-input right">
																<input class="form-control" type="text" name="price"
																	id="maxAmount" value="0">
															</div>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
									<div class="modal-footer">
										<span id="e_service-error" class="help-block pull-left"> </span>
										<button type="button" class="btn btn-sm btn-danger"
											data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-sm btn-success"
											id="editServiceButton">Update</button>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
				<!-- Tax Form Model -->
				<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
					tabindex="-1" id="taxEdit" class="modal fade" data-keyboard="false"
					data-backdrop="static">
					<div class="modal-dialog  modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close"
									type="button">&times;</button>
								<h4 class="modal-title">Tax Edit</h4>
							</div>
							<form id="taxEditForm" method="POST" class="form-horizontal"
								role="form">
								<div class="modal-body">
									<div class="row  col-lg-12">
										<input type="hidden" name="taxId" id="taxId" /> <input
											type="hidden" name="act"
											value="<?php echo base64_encode("!taxUpdate");?>" />
										<div class="col-lg-12">
											<div class="panel-body">
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Tax
														Title</label>
													<div class="col-lg-7">
														<input type="text" class="form-control" name="taxTitle"
															id="e_taxTitle" maxlength="30">
													</div>
												</div>
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Tax
														Description</label>
													<div class="col-lg-7">
														<textarea class="form-control" name="taxDec"
															maxlength="100" id="e_taxDec" rows="2"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Tax
														Percentage</label>
													<div class="col-lg-7">
														<div class="input-group">
															<div class="iconic-input right">
																<input class="form-control" type="text"
																	id="e_taxPercent" name="taxPercent" value="0">
															</div>
															<span class="input-group-addon">%</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<span id="e_tax-error" class="help-block pull-left"> </span>
										<button type="button" class="btn btn-sm btn-danger"
											data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-sm btn-success"
											id="editTaxButton">Update</button>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
				<!-- Add services For company -->
				<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
					tabindex="-1" id="mapServices" class="modal fade"
					data-keyboard="false" data-backdrop="static">
					<div class="modal-dialog  modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close"
									type="button">&times;</button>
								<h4 class="modal-title">Add Service</h4>
							</div>
							<form id="mapServicesForm" method="POST" class="form-horizontal"
								role="form">
								<div class="modal-body">
									<div class="row  col-lg-12">
										<input type="hidden" name="act"
											value="<?php echo base64_encode("!mapServices");?>" /> <input
											type="hidden" name="comId" class="comId" />
										<div class="col-lg-12">
											<div class="panel-body">
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Service</label>
													<div class="col-lg-7">
														<select class="form-control" id="serviceChosen"
															name="serv_id">
															<option value="">Select Service</option>
														</select> <label
															class="help-block text-danger displayHide isvar"
															style="margin-bottom: 0px;">Fixed price</label> <label
															class="help-block text-danger displayHide notvar"
															style="margin-bottom: 0px;">Variable Price</label>
													</div>
												</div>
												<div id="contentData"></div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<span id="mapservice-error" class="help-block pull-left"> </span>
										<button type="button" class="btn btn-sm btn-danger"
											data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-sm btn-success">Add</button>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>

				<!-- Mappedd tax -->
				<!-- Add services For company -->
				<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
					tabindex="-1" id="mapTaxes" class="modal fade"
					data-keyboard="false" data-backdrop="static">
					<div class="modal-dialog  modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close"
									type="button">&times;</button>
								<h4 class="modal-title">Add Taxes</h4>
							</div>
							<form id="mapTaxForm" method="POST" class="form-horizontal"
								role="form">
								<div class="modal-body">
									<div class="row  col-lg-12">
										<input type="hidden" name="act"
											value="<?php echo base64_encode("!mapTaxes");?>" /> <input
											type="hidden" name="comId" class="comId" />
										<div class="col-lg-12">
											<div class="panel-body">
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Taxes</label>
													<div class="col-lg-7">
														<input type="hidden" name="comId" id="taxcomId" /> <select
															class="form-control" id="taxChosen" name="taxId">
															<option value="">Select Services</option>
                                        <?php
																																								$stmt = mysqli_prepare ( $conn, "SELECT tax_id, title, tax_percentage FROM taxes WHERE  enabled =1 " );
																																								$result = mysqli_stmt_execute ( $stmt );
																																								mysqli_stmt_bind_result ( $stmt, $tax_id, $title, $tax_percentage );
																																								while ( mysqli_stmt_fetch ( $stmt ) ) {
																																									echo "<option data-percent='" . $tax_percentage . "' value='" . $tax_id . "'>" . $title . "</option>";
																																								}
																																								?>
                                           </select>
													</div>
												</div>
												<div class="form-group displayHide taxContent">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Tax
														Percent</label>
													<div class="col-lg-7">
														<label for="dname" class="col-lg-5 col-sm-5 control-label"
															id="taxVal"></label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<span id="mapservice-error" class="help-block pull-left"> </span>
										<button type="button" class="btn btn-sm btn-danger"
											data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-sm btn-success">Add</button>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>

				<!-- Mappedd tax -->
				<!-- Add services For company -->
				<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
					tabindex="-1" id="editServices" class="modal fade"
					data-keyboard="false" data-backdrop="static">
					<div class="modal-dialog  modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close"
									type="button">&times;</button>
								<h4 class="modal-title">Edit Services</h4>
							</div>
							<form id="editServicesForm" method="POST" class="form-horizontal"
								role="form">
								<div class="modal-body">
									<div class="row  col-lg-12">
										<input type="hidden" name="act"
											value="<?php echo base64_encode("!serviceEdit");?>" /> <input
											type="hidden" name="comId" class="comId" />
										<div class="col-lg-12">
											<div class="panel-body">
												<div class="form-group displayHide" id="isVariabl">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Exempt
														Count</label>
													<div class="col-lg-7">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
															<div class="iconic-input right">
																<input class="form-control" name="examptTo" value="0"
																	id="editexamptTo" type="text"> <input
																	class="form-control" name="serv_id" id="editserv_id"
																	type="hidden">
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="dname" class="col-lg-5 col-sm-5 control-label">Maximum
														Amount</label>
													<div class="col-lg-7">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
															<div class="iconic-input right">
																<input class="form-control" name="price" id="editPrice"
																	required type="text">
															</div>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
									<div class="modal-footer">
										<span id="editservice-error" class="help-block pull-left"> </span>
										<button type="button" class="btn btn-sm btn-danger"
											data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-sm btn-success"
											id="submitEditeServ">Update</button>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>

				<button type="button" href="#editServices" id="modelClick"
					data-toggle="modal" class="displayHide"></button>

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
	<script src="../js/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
	$(document).ready(function () {
		$('#companyName,#serviceChosen,#taxChosen').chosen();  
	});

	///Other event handling
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
	    	      $('#managingServicesTab a[href="#' + tab + '"]').tab('show');
	    	  	
	    	  }else{
	    		  $('#managingServicesTab a[href="#servicesTab"]').tab('show');
	    		  
	    		  } 

	    	  // Change hash for page-reload
	    	  $('#managingServicesTab a').on('shown.bs.tab', function (e) {
	    	      window.location.hash = e.target.hash;
	    	  })
	    	});
		$('#managingServicesTab').on('shown.bs.tab', function (e) {
	 	   // newly activated tab
	 	   window.scrollTo(0, 0);
	 	  if($(e.target).data('loaded') === false){
				if($(e.target).data('title') == 'servicetitle'){
					 selectionOfservices();				
			   }else if($(e.target).data('title') == 'taxtitle'){				
					selectionOftaxes();
				}else if($(e.target).data('title') == 'maptitle'){				
				//	maptitlementBenefits();
				}
			//make the tab loaded true to prevent re-loading while clicking.
	   		$(e.target).data('loaded',true);
	 		}
	 	});    
	 	
		 $("#yesIs,#noIs,#e_yesIs,#e_noIs").click(function () {
			  if($("#yesIs").is(':checked')){
				  $('.price').show();
				 }else{
				 $('.price').hide();
				 $('#priceAmount,#e_priceAmount').val(0);
                 }
		      if($("#e_yesIs").is(':checked')){
				  $('.e_price').show();
			  }else{
				  $('#priceAmount,#e_priceAmount').val(0);
				  $('.e_price').hide();
				 }
		});
		
		$(document).on('click', "#service_HideSeek,#tax_HideSeek", function (e) {
	           e.preventDefault();
	           jQuery('#'+$(this).attr('id').split('_')[0]+'_AddForm').toggle('show');
	   });
		$(document).on('click', "#service_cancel,#tax_cancel", function (e) {
	           e.preventDefault();
	           jQuery('#'+$(this).attr('id').split('_')[0]+'_AddForm').toggle('hide');
	   });

		   
		 $('#serviceChosen').on('change', function (e) {
			   e.preventDefault();
			 $('#contentData').html('');
			 $('.notvar,.isvar').hide();
			 if($('#serviceChosen :selected').data('is')==1){
				 $('.notvar').show();
				 $('#contentData').html('<div class="form-group"><label for="dname" class="col-lg-5 col-sm-5 control-label">Customized Exemption </label><div class="col-lg-7"><input class="form-control" type="text" name="examptTo" value="0" id="mapppedExamptTo"></div></div><div class="form-group"><label for="dname" class="col-lg-5 col-sm-5 control-label">Customized Amount</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><div class="iconic-input right"><input class="form-control" type="text" name="price"  id="mappedMaxAmount" value="0"></div></div></div></div>');
			 }else{
				 $('.isvar').show();
				 $('#contentData').html('<div class="form-group"><label for="dname" class="col-lg-5 col-sm-5 control-label">Customized Amount</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><div class="iconic-input right"><input class="form-control" type="text" name="price"  id="mappedMaxAmount" value="0"></div></div></div></div>');
			 }
			 $('#mapppedExamptTo').val($('#serviceChosen :selected').data('to'));
			 $('#mappedMaxAmount').val($('#serviceChosen :selected').data('price'));
	   });

		 $('#taxChosen').on('change', function (e) {
			 e.preventDefault();
			 $('.taxContent').show();
			 $('#taxVal').html($('#taxChosen :selected').data('amount'));
	     });
			      
		 $('#companyName').on('change', function (e) {
			  e.preventDefault();
			  $('.comId,#taxcomId').val( $('#companyName :selected').val());
			  var currPayrollMonth=$('#companyName :selected').data('val');
			   var userName=$('#companyName :selected').data('id');
			   $.ajax({
				   dataType: 'html',
		           type: "POST",
		           url: "php/invoice.handle.php",
		           cache: false,
		           data: { act: '<?php echo base64_encode("!companyServiceMap");?>', comId: $('#companyName :selected').val()},
		           beforeSend:function(){
		        	   $('#loader').loading(true);
		           },
			       success: function (data) {
			            	  data = JSON.parse(data);
			            	  $('#mappingdiv').show();
			            	  setdata(data[2]);
			            	 $('#loader').loading(false);
			    	             }
			   		  });
		   });

			function setdata(data){
				$('#services_list,#tax_list,#taxChosen,#serviceChosen').empty();
				$('#taxChosen,#serviceChosen').append("<option value=''>Select Option</option>");
				for (lc = 0; lc < data['selectSev'].length; lc++) {
			        $('#serviceChosen').append("<option data-is="+data['selectSev'][lc]["is_variable"]+" data-to="+data['selectSev'][lc]["exempt_to"]+" data-price="+data['selectSev'][lc]["default_price"]+" value="+data['selectSev'][lc]["serv_id"]+">"+data['selectSev'][lc]["serv_name"]+"</option>");
			     }
			     $('#serviceChosen').trigger("chosen:updated");
			       	  
			     for (lc = 0; lc < data['selectTax'].length; lc++) {
			         $('#taxChosen').append("<option data-amount="+data['selectTax'][lc]["tax_percentage"]+" value="+data['selectTax'][lc]["tax_id"]+">"+data['selectTax'][lc]["title"]+"</option>");
			     }
			     $('#taxChosen').trigger("chosen:updated");
			       	   for (lc = 0; lc < data['services'].length; lc++) {
			            	         $('#services_list').append('<li class="nondraggable" data-var="'+ data['services'][lc]["is_variable"] +'"  data-to="'+data['services'][lc]["exempt_to"]+'"  data-price="'+ data['services'][lc]["default_price"] +'"  data-id="'+ data['services'][lc]["serv_id"] +'" id="'+ data['services'][lc]["company_id"] +'"><span>' +
				                     data['services'][lc]["serv_name"] + '</span><i id="sortable_edit" class="fa fa-pencil pull-right"></i></li>');
			           }
			            for (lc = 0; lc < data['taxes'][0].length; lc++) {
		        	          $('#tax_list').append('<li class="nondraggable"  data-id="'+ data['taxes'][0][lc]["tax_id"] +'" id="'+ data['taxes'][0][lc]["company_id"] +'" ><span class="'+ data['taxes'][0][lc]["tax_id"] +'">' +
		                               data['taxes'][0][lc]["title"] + '</span></li>');
		               }
			    initializeServices();
			    initializetax();
			 }
		 
	   function initializeServices() {
      	  allowances = $(".services_structure").sortable({
      		  connectWith: '.services_structure',
      		  out: function(ev, ui) {
          		   if(ui.item.hasClass("nondraggable"))
      	              ui.sender.sortable("false");
      	        }});
	     }
	         
       function initializetax() {
      	  deductions = $(".tax_structure").sortable({
      		  connectWith: '.tax_structure',
      		  out: function(ev, ui) {
      	            if(ui.item.hasClass("nondraggable"))
      	              ui.sender.sortable("false");
      	        }
	        });
  	   }
		
	/*Ajax calls*/
	 // Add Services 
	 $(document).on('submit', "#service_AddForm", function (e) {
         e.preventDefault();
         if ($("#serviceName").val() == '' || $("#serviceDec").val() == '')
         {
              $("#service-error").html(' Enter All Required Fields');
         }
         else {
        	 $("#service-error").html('');
             $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: $('#service_AddForm').serialize(),
                 beforeSend:function(){
                  	$('#addbuttonService').button('loading'); 
                 },
                 success: function (data) {
                	 jsonobject = JSON.parse(data);
                	  if (jsonobject[0] == "success") {
                     jQuery('#service_AddForm').toggle('hide');
                	 $('#service_AddForm')[0].reset();
                	 BootstrapDialog.alert(jsonobject[1]);
                	 sevicedataConvertion(jsonobject[2]);
                	 $('#addbuttonService').button('reset');
                	  }else{
                	BootstrapDialog.alert(jsonobject[2].split(':')[0]);
                   	  }
                     }
                
             });
         }
     });
     
     //mapp service to company
     $(document).on('submit', "#mapServicesForm", function (e) {
         e.preventDefault();
         if ($("#mappedMaxAmount").val() == '' || $("#mapppedExamptTo").val() == '')
         {
              $("#mapservice-error").html(' Enter All Required Fields');
         }
         else {
        	 $("#mapservice-error").html('');
             $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: $('#mapServicesForm').serialize(),
                 beforeSend:function(){
                  	$('#loader').loading(true);
                 },
                 success: function (data) {
                	 jsonobject = JSON.parse(data);
                	  if (jsonobject[0] == "success") {
                		  BootstrapDialog.alert(jsonobject[1]);
                     $('#mapServicesForm')[0].reset();
                	 $('.close').click();
                	 $('#mapServicesForm')[0].reset();
                	 setdata(jsonobject[2]);
                	 $('#loader').loading(false);
                  }else{
                 	BootstrapDialog.alert(jsonobject[1]);
                    	  }
                  	 }                
             });
         }
     });

     //mapp Tax to company
     $(document).on('submit', "#mapTaxForm", function (e) {
         e.preventDefault();
             $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: $('#mapTaxForm').serialize(),
                 beforeSend:function(){
                	 $('#loader').loading(true);
                 },
                 success: function (data) {
                	 jsonobject = JSON.parse(data);
                	 if (jsonobject[0] == "success") {
                      $('.close').click();
                      BootstrapDialog.alert(jsonobject[1]);
                     $('#mapTaxForm')[0].reset();
	            	  setdata(jsonobject[2]);
	            	  $('#loader').loading(false);
                	 }else{
                      	BootstrapDialog.alert(jsonobject[1]);
                         	  }
                  	 }
                
             });
          });
     //Add Tax 
	 $(document).on('submit', "#tax_AddForm", function (e) {
         e.preventDefault();
         if ($("#taxTitle").val() == '' || $("#taxDec").val() == '' || $("#taxPercent").val() == '')
         {
              $("#tax-error").html(' Enter All Required Fields');
         }
         else {
        	 $("#tax-error").html('');
             $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: $('#tax_AddForm').serialize(),
                 beforeSend:function(){
                  	$('#taxAddButton').button('loading'); 
                 },
                success: function (data) {
                	 jsonobject = JSON.parse(data);
                	 if (jsonobject[0] == "success") {
                	BootstrapDialog.alert(jsonobject[1]); 
                	 jQuery('#tax_AddForm').toggle('hide');
                	 $('#tax_AddForm')[0].reset();
                 	 taxdataConvertion(jsonobject[2]);
                 	 $('#taxAddButton').button('reset');
                 }else{
                  	BootstrapDialog.alert(jsonobject[1]);
                     	  }
                     }
                
             });
         }
     });

		//select Service table
		function selectionOfservices(){
			 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: { act: '<?php echo base64_encode("!serviceSelect");?>'},
                 success: function (data) {
                	 jsonobject = JSON.parse(data);
                	 sevicedataConvertion(jsonobject[2]);
                     }
              });
		}

		//selecttion of tax
		function selectionOftaxes(){
			 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: { act: '<?php echo base64_encode("!taxSelect");?>'},
                 success: function (data) {
                	 jsonobject = JSON.parse(data);
                	 taxdataConvertion(jsonobject[2]);
                     }
              });
		}
		
        //Disable service
		 $(document).on('click', "#serviceSample a.disable", function (e) {
             e.preventDefault();
             var serv_id = $(this).data('id');
             serv_name=$(this).data('val');
              BootstrapDialog.show({
	                title:'Confirmation',
                 message: 'Are Sure you want to Disable <strong>'+ serv_name +'<strong>',
                 buttons: [{
                     label: 'Disable',
                     cssClass: 'btn-sm btn-success',
                     autospin: true,
                     action: function(dialogRef){
                     	 $.ajax({
  		                  dataType: 'html',
  		                  type: "POST",
  		                  url: "php/invoice.handle.php",
  		                  cache: false,
  		                  data: { act: '<?php echo base64_encode("!serviceDisable");?>', serv_id: serv_id  },
  		                      complete:function(){
  		                    	 dialogRef.close();
  		                      },
  		                    success: function (data) {
  		                    	data = JSON.parse(data);
  		                        if (data[0] == "success") {
  		                        	BootstrapDialog.alert(data[1]);
  	                                sevicedataConvertion(data[02]);
  		                        }else if (data[0] == "error") {
		                                    alert(data[1]);
		                                }
  		                    },
 		                      error:function(jqXHR, textStatus, errorThrown) {
		                    	 BootstrapDialog.alert('Error Disabling  : '+errorThrown+'');
  		                      },
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
         });

		 //Disable Tax
		 $(document).on('click', "#taxSample a.disable", function (e) {
             e.preventDefault();
             var taxId = $(this).data('id');
             taxName=$(this).data('val');
              BootstrapDialog.show({
	                title:'Confirmation',
                 message: 'Are Sure you want to Disable <strong>'+ taxName +'<strong>',
                 buttons: [{
                     label: 'Disable',
                     cssClass: 'btn-sm btn-success',
                     autospin: true,
                     action: function(dialogRef){
                     	 $.ajax({
  		                  dataType: 'html',
  		                  type: "POST",
  		                  url: "php/invoice.handle.php",
  		                  cache: false,
  		                  data: { act: '<?php echo base64_encode("!taxDisable");?>', taxId: taxId  },
  		                      complete:function(){
  		                    	 dialogRef.close();
  		                      },
  		                    success: function (data) {
  		                    	data = JSON.parse(data);
  		                        if (data[0] == "success") {
  		                        	BootstrapDialog.alert(data[1]);
  	                               taxdataConvertion(data[02]);
  		                        }else if (data[0] == "error") {
		                                    alert(data[1]);
		                                }
  		                    },
 		                      error:function(jqXHR, textStatus, errorThrown) {
		                    	 BootstrapDialog.alert('Error Disabling  : '+errorThrown+'');
  		                      },
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
         });
//fill data for service 
		 $(document).on('click', "#serviceSample a.serviceEdit", function (e) {
             e.preventDefault();
             var MyRows = $(this).parents('tr');
            $('#serv_id').val($(this).data('id'));
            $('#e_serviceName').val($(MyRows[0]).find('td:eq(0)').html());
		    $('#e_serviceDec').val($(MyRows[0]).find('td:eq(1)').html());
		      if($(MyRows[0]).find('td:eq(2)').html()=='Yes'){
			    $("#e_yesIs" ).prop( "checked",true );
		    	$('.e_price').show();
			    }else{
		    	$("#e_noIs").prop( "checked", true);
		    	$('.e_price').hide();
			    }
		    $('#e_priceAmount').val($(MyRows[0]).find('td:eq(3)').html());
		    $('#maxAmount').val($(MyRows[0]).find('td:eq(4)').html());
         });
         
		//fill data for Tax 
		 $(document).on('click', "#taxSample a.taxEdit", function (e) {
             e.preventDefault();
             var MyRows = $(this).parents('tr');
            $('#taxId').val($(this).data('id'));
            $('#e_taxTitle').val($(MyRows[0]).find('td:eq(0)').html());
		    $('#e_taxDec').val($(MyRows[0]).find('td:eq(1)').html());
		    $('#e_taxPercent').val($(MyRows[0]).find('td:eq(2)').html());
         });

		 //Edit Service 
		  $(document).on('submit', "#serviceEditForm", function (e) {
         e.preventDefault();
         if ($("#e_serviceName").val() == '' || $("#e_serviceDec").val() == '')
         {
              $("#e_service-error").html(' Enter All Required Fields');
         }
         else {
        	 $("#e_service-error").html('');
             $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: $('#serviceEditForm').serialize(),
                 beforeSend:function(){
                  	$('#editServiceButton').button('loading'); 
                 },
                 success: function (data) {
                	 jsonobject = JSON.parse(data);
                	  if (jsonobject[0] == "success") {
                	 $('.close').click();
                	 $('#serviceEditForm')[0].reset();
                  	 BootstrapDialog.alert(jsonobject[1]);
                     sevicedataConvertion(jsonobject[2]);
                     $('#editServiceButton').button('reset');
                	  }else{
                	 BootstrapDialog.alert(jsonobject[2]);
                    	  }
                     }
                
             });
         }
     });
		  //Edit TAx Data 
		  $(document).on('submit', "#taxEditForm", function (e) {
         e.preventDefault();
         if ($("#e_taxTitle").val() == '' || $("#e_taxPercent").val() == '' || $("#e_taxDec").val() == '')
         {
              $("#e_tax-error").html('Enter All Required Fields');
         }
         else {
        	 $("#e_tax-error").html('');
             $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/invoice.handle.php",
                 cache: false,
                 data: $('#taxEditForm').serialize(),
                 beforeSend:function(){
                  	$('#editTaxButton').button('loading'); 
                 },
                 success: function (data) {
                	 jsonobject = JSON.parse(data);
                	 if (jsonobject[0] == "success") {
                      $('.close').click();
                	 BootstrapDialog.alert(jsonobject[1]);
                	 $('#taxEditForm')[0].reset();
                     taxdataConvertion(jsonobject[2]);
                     $('#editTaxButton').button('reset');
                	 }else{
                		 BootstrapDialog.alert(jsonobject[1]);
                     }
                     }
             });
         }
     });

		  //Enable data   
		 $(document).on('click', "#serviceSample a.enable", function (e) {
             e.preventDefault();
             var serv_id = $(this).data('id');
             serv_name=$(this).data('val');
              BootstrapDialog.show({
	                title:'Confirmation',
                 message: 'Are Sure you want to enable <strong>'+ serv_name +'<strong>',
                 buttons: [{
                     label: 'Enable',
                     cssClass: 'btn-sm btn-success',
                     autospin: true,
                     action: function(dialogRef){
                     	 $.ajax({
  		                  dataType: 'html',
  		                  type: "POST",
  		                  url: "php/invoice.handle.php",
  		                  cache: false,
  		                  data: { act: '<?php echo base64_encode("!serviceEnable");?>', serv_id: serv_id  },
  		                      complete:function(){
  		                    	 dialogRef.close();
  		                      },
  		                    success: function (data) {
  		                    	data = JSON.parse(data);
  		                        if (data[0] == "success") {
  		                           BootstrapDialog.alert(data[01]);
                                   sevicedataConvertion(data[02]);
  		                        }else if (data[0] == "error") {
		                                    alert(data[1]);
		                                }
  		                    },
 		                      error:function(jqXHR, textStatus, errorThrown) {
		                    	 BootstrapDialog.alert('Error Disabling  : '+errorThrown+'');
  		                      },
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
         });
         

		 //Enable data Tax    
		 $(document).on('click', "#taxSample a.enable", function (e) {
             e.preventDefault();
             var tax_id = $(this).data('id');
             taxName=$(this).data('val');
              BootstrapDialog.show({
	                title:'Confirmation',
                 message: 'Are Sure you want to enable <strong>'+ taxName +'<strong>',
                 buttons: [{
                     label: 'Enable',
                     cssClass: 'btn-sm btn-success',
                     autospin: true,
                     action: function(dialogRef){
                     	 $.ajax({
  		                  dataType: 'html',
  		                  type: "POST",
  		                  url: "php/invoice.handle.php",
  		                  cache: false,
  		                  data: {act: '<?php echo base64_encode("!taxEnable");?>', taxId: tax_id  },
  		                      complete:function(){
  		                    	 dialogRef.close();
  		                      },
  		                    success: function (data) {
  		                    	data = JSON.parse(data);
  		                        if (data[0] == "success") {
  		                           BootstrapDialog.alert(data[1]);
                                   taxdataConvertion(data[02]);
  		                        }else if (data[0] == "error") {
		                                    alert(data[1]);
		                        }
  		                    },
 		                      error:function(jqXHR, textStatus, errorThrown) {
		                    	 BootstrapDialog.alert('Error Disabling  : '+errorThrown+'');
  		                      },
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
         });
         
		 function sevicedataConvertion(jsonobjectArray){
				 $('#serviceTable').html('');
	 			 var html = '<section id="flip-scroll"> <table class="table table-striped table-hover table-bordered cf dataTable" id="serviceSample"> <thead class="cf"><tr><th>Service ID</th><th>Service Name</th><th>Desc</th><th>isVaraible</th><th>Exempt To</th><th>Amount</th><th>Actions</th></tr></thead><tbody>';               	                	  
	          	  for (var i = 0, len =jsonobjectArray.length; i < len; ++i) {
	          		   html += '<tr>';
	          		   $.each(jsonobjectArray[i], function (k, v) {
	              		  if(k=='serv_id'){
	                  		  serviceId=v;
	                  		}
	              		  if(k=='serv_name'){
	              			serv_name=v;
	                  		}
	              		 	if(k=='enabled'){
	              			  if(v==1){
	                  		      html += '<td><a class="disable" data-val='+serv_name+' data-id='+serviceId+' href="#" title="Disable"><button  class="btn btn-primary btn-xs" style="padding: 1px 4px;"><i class="fa fa-unlock"></i></button></a>&nbsp;<a data-id='+serviceId+'  data-val='+serv_name+' href="#serviceEdit" title="Edit" class="serviceEdit" data-toggle="modal" ><button class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></button></a></td>';  
	                  		      }else{
	                      		  html += '<td><a class="enable" data-val='+serv_name+' data-id='+serviceId+'  href="#" title="Enable"><button  class="btn btn-danger btn-xs" style="padding: 1px 4px;"><i class="fa fa-lock"></i></button></a></td>';
	                              }                         		   
	              		   }else if(k=='is_variable'){
	                  		   if(v==1){
	              		  html += '<td>Yes</td>';}else{
	              			  html += '<td>no</td>';  }
	                  		   }else{
	              		  html += '<td>'+v+'</td>';
	              		  }
	          	       }); 
	               	  html += "</tr>";
	               	}
	           	    html += '</tbody></table></section>';
	           	  $('#serviceTable').html(html);
	           	  var oTable=$('#serviceSample').dataTable({
	             	 "aLengthMenu": [
	                                  [5, 15, 20, -1],
	                                  [5, 15, 20, "All"] // change per page values here
	                              ],
	                  "iDisplayLength": 5,
	                  "aoColumnDefs": [
	                                   {"bSearchable": false, "bVisible": false, "aTargets": [0]}      
	                                  ],
	           });
           }
         
		 function taxdataConvertion(jsonobjectArray){
			 $('#taxTable').html('');
 			 var html = '<section id="flip-scroll"> <table class="table table-striped table-hover table-bordered cf dataTable" id="taxSample"> <thead class="cf"><tr><th>Tax Id</th><th>Tax Title</th><th>TaxDesc.</th><th>Percent</th><th>Actions</th></tr></thead><tbody>';               	                	  
          	  for (var i = 0, len =jsonobjectArray.length; i < len; ++i) {
          		   html += '<tr>';
          		   $.each(jsonobjectArray[i], function (k, v) {
              		  if(k=='tax_id'){
                  		  taxId=v;
                  		}
              		  if(k=='title'){
              			taxTitle=v;
                  		}
              		 	if(k=='enabled'){
              			  if(v==1){
                  		      html += '<td><a class="disable" data-val='+taxTitle+' data-id='+taxId+' href="#" title="Disable"><button  class="btn btn-primary btn-xs" style="padding: 1px 4px;"><i class="fa fa-unlock"></i></button></a>&nbsp;<a data-id='+taxId+'  data-val='+taxTitle+' href="#taxEdit" title="Edit" class="taxEdit" data-toggle="modal" ><button class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></button></a></td>';  
                  		      }else{
                      		  html += '<td><a class="enable" data-val='+taxTitle+' data-id='+taxId+'  href="#" title="Enable"><button  class="btn btn-danger btn-xs" style="padding: 1px 4px;"><i class="fa fa-lock"></i></button></a></td>';
                              }                         		   
              		 	}else{
              		  html += '<td>'+v+'</td>';
              		  }
          	       }); 
               	  html += "</tr>";
               	}
           	    html += '</tbody></table></section>';
           	  $('#taxTable').html(html);
           	  var oTable=$('#taxSample').dataTable({
             	 "aLengthMenu": [
                                  [5, 15, 20, -1],
                                  [5, 15, 20, "All"] // change per page values here
                              ],
                  "iDisplayLength": 5,
                  "aoColumnDefs": [
                                   {"bSearchable": false, "bVisible": false, "aTargets": [0]}      
                                  ],
           });

         }

		 //Sort  Button  Changes
         var servicesSortorder = '';
         $(".services_structure").on( "sortupdate", function( event, ui ) {
        	 servicesSortorder = $.map($('#services_list').children('li'), function(el){            	 
  	        return {'serv_id':$(el).data('id'),'comId':$(el).attr('id')}; 
  	    });
      	});
   	  
   	    var taxSortorder = '';
         $(".tax_structure").on( "sortupdate", function( event, ui ) {
        	 taxSortorder = $.map($('#tax_list').children('li'), function(el){            	 
  	        return {'tax_id':$(el).data('id'),'comId':$(el).attr('id')}; 
  	    });
        });
         
   	  
          $('#companyServiceMapp').on('click', function (e) {
        	  e.preventDefault();
              if(servicesSortorder !='' || taxSortorder !='')
             {
            $('#error-msg').html('');
            var data = $.concat(taxSortorder,servicesSortorder);
            BootstrapDialog.show({
	                title:'Confirmation',
                   message: 'Are Sure you want to This Changes',
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
    		                    url: "php/invoice.handle.php",
    		  		            cache: false,
    		                    data: { act: '<?php echo base64_encode("!sortoutData");?>', data: data,comId: $('#companyName :selected').val() },
    		                    beforeSend:function(){
     		   		        	   $('#loader').loading(true);
     		   		           },
    		                    complete:function(){
    		                    	 dialogRef.close();
    		                      },
    		                    success: function (data) {
    		                    	data = JSON.parse(data);
    		                    	setdata(data[2]);
    		                    	BootstrapDialog.alert(data[1]);
    		                    	 $('#loader').loading(false);
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
              }else{
                   $('#error-msg').html("* You have Not Made Any Changes");
              	}
      });   

      	  $(document).on('click', "#sortable_edit", function (e) {
        	  e.preventDefault();
        	   if($(this).parent().data('var')==1){
               $('#isVariabl').show();
               $('#editserv_id').val($(this).parent().data('id'));
               console.log($(this).parent().data('id'));
               $('#editexamptTo').val($(this).parent().data('to'));
               $('#editPrice').val($(this).parent().data('price'));
              }else{
            	  $('#isVariabl').hide();
            	  $('#editPrice').val($(this).parent().data('price'));
              }
              $('#modelClick').click();
          });

      	  $(document).on('submit', "#editServicesForm", function (e) {
        	  e.preventDefault();
        	  if($('#editPrice').val()!=''){
        		  $('#editservice-error').html('');
        	  $.ajax({
                  dataType: 'html',
                  type: "POST",
                  url: "php/invoice.handle.php",
	              cache: false,
	              data: $('#editServicesForm').serialize(),
                 beforeSend:function(){
  		        	   $('#submitEditeServ').button('loading');
  		           },
                 success: function (data) {
                  	data = JSON.parse(data);
                  	setdata(data[2]);
                  	BootstrapDialog.alert(data[1]);
                  	$('.close').click();
                  	$('#submitEditeServ').button('reset');
                  }
              });}else{
               $('#editservice-error').html('Enter All Requirment');
                  }
      	  });
              
</script>
</body>
</html>