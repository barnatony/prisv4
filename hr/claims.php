<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="BassTechs">
<meta name="keyword" content="Claim Rules">
<link rel="shortcut icon" href="img/favicon.png">
<title>Claim Rules</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
.table tbody>tr>td, .table tfoot>tr>td {
	padding: 10px;
}

#no_claim {
	margin-bottom: 15px;
	margin-top: 15px;
	font-size: 15px;
}

#claimreq {
	cursor: pointer;
}

.bio-row {
	width: 100%;
}

.ajax_loader, .ajax_loader1 {
	display: block;
	margin: 0px auto;
}

.bio-graph-info {
	background: #fcfcfc;
}

.arrow {
	border-top-width: 0;
	border-bottom-color: #999;
	border-bottom-color: rgba(0, 0, 0, .25);
	border-width: 8px;
	position: relative;
	display: block;
	width: 0;
	height: 0;
	border-color: transparent;
	border-style: solid;
	content: " ";
	border-top-width: 0;
	border-bottom-color: #ccc;
}

.over {
	position: relative;
	display: block;
	width: 100%;
	box-shadow: inset 0 3px 6px rgba(0, 0, 0, .05);
}

.primary_span {
	position: relative !Important;
}

@media ( min-width : 992px) {
	.modal-lg {
		width: 600px;
	}
}
.popover{
max-width:none !important;
width:auto !important;
}
#type_chosen, #classHotel_chosen, #classTravel_chosen, #typeHotel_chosen,
	#typeTravel_chosen, #etype_chosen, #eclassHotel_chosen,
	#eclassTravel_chosen, #etypeHotel_chosen, #etypeTravel_chosen,
	#emploChosen_chosen, #designChosen_chosen, #claimRulechosen_chosen,
	#process_claim_employee_chosen {
	width: 100% !important;
}
.back{
    margin-top: 7px;
    margin-right: 5px;
}
</style>
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
            <?php
												error_reporting ( 0 );
												include_once (__DIR__ . "/sideMenu.php");
												
												require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
												require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/designation.class.php");
												require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/claim.class.php");
												
												$employee = new Employee ();
												$employee->conn = $conn;
												$employeeOnly = $employee->select (1);
												
												$claim = new ClaimRule ();
												$claim->conn = $conn;
												$claimRule = $claim->select ();
												$employeeInClaim = $claim->employeeInClaims ();
												$designation = new Designation ();
												$designation->conn = $conn;
												$designations = $designation->select ();
												?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
				<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
								<i class=" fa fa-arrow-left"></i> All Settings</a>
				           </div>
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs " id="claim_tabs" role="tablist">
							<li id="header_req_active"><a href="#claimRules" id="claim_tab"
								data-toggle="tab" data-loaded=false data-title="claimRules">
									Claim Rules </a></li>
							<li class=""><a href="#claimMapping" id="claimTab"
								data-toggle="tab" data-loaded=false data-title="claimMapping">
									Claim Mapping </a></li>
							<li class="" id="header_req"><a href="#claimRequested"
								id="claimreq" data-toggle="tab" data-loaded=false
								data-title="claimRequests"> Claims Requested </a> <span
								id="claim_sd"></span></li>
							<li class="" id="header_req"><a href="#processClaim"
								id="process_claim_request" data-toggle="tab" data-loaded=false
								data-title="processClaim"> Process Claims </a></li>
							
							<li class="" id="header_req1"><a href="#processedClaims"
							    id="processed_claims" data-toggle="tab" data-loaded=false
							    data-title="processedClaims">Processed Claims</a></li>
							
							
						</ul>
					</header>


					<div class="panel-body">
						<img src="../img/ajax-loader.gif" class="ajax_loader"
							style="display: none">
						<div class="tab-content tasi-tab">
							<div class="tab-pane" id="claimRules">
								<header class="add_button_hide">
									<div class="btn-group pull-right">
										<button type="button" class="btn btn-sm btn-info"
											id="showhide" style="margin-top: -47px;margin-right:94px;">
											<i class="fa fa-plus"></i> Add
										</button>
									</div>
								</header>

								<form class="form-horizontal" role="form" method="post"
									id="claimsAddForm">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
									<div class="col-lg-12">
										<div class="panel-body" id="add-claim-toggle"
											style="display: none">
											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Name</label>
												<div class="col-lg-5">
													<input class="form-control" type="text" id="cName"
														name="cName" required />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Alias Name</label>
												<div class="col-lg-5">
													<input class="form-control" type="text" id="cAlias"
														name="cAlias" required />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Type</label>
												<div class="col-lg-5">
													<select class="form-control" id="type" name="type" required>
														<option value="">Select Type</option>
														<option value="travel">Travel</option>
														<option value="hotel">Hotel Accommodation</option>
														<option value="others">Others-Official Spend</option>
													</select>
												</div>
											</div>
											<div id="travelHide" class="hide">
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Type Of
														Travel</label>
													<div class="col-lg-5">
														<select class="form-control" id="typeTravel"
															name="typeClaims">
															<option value="train">Train</option>
															<option value="bus">Bus</option>
															<option value="flight">Flight</option>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Class Of
														Travel</label>
													<div class="col-lg-5">
														<select class="form-control" id="classTravel"
															name="classClaims">
															<option value="firstac">1st AC</option>
															<option value="secondac">2nd AC</option>
															<option value="thirdac">3rd AC</option>
															<option value="sl">SL</option>
															<option value="acsl">AC SL</option>
															<option value="acssl">AC-Semi SL</option>
															<option value="ssl">Semi SL</option>
															<option value="ec">EC</option>
															<option value="bc">BC</option>
														</select>
													</div>
												</div>
											</div>

											<div id="hotelHide" class="hide">
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Type Of
														Hotel</label>
													<div class="col-lg-5">
														<select class="form-control" id="typeHotel"
															name="typeClaims">
															<option value="ac">AC Room</option>
															<option value="nac">NON AC</option>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Class of
														Hotel</label>
													<div class="col-lg-5">
														<select class="form-control" id="classHotel"
															name="classClaims">
															<option value="7star">7 Star</option>
															<option value="5star">5 Star</option>
															<option value="4star">4 Star</option>
															<option value="3star">3 Star</option>
															<option value="NA">Others</option>
														</select>
													</div>
												</div>
											</div>

											<div id="othersHide" class="hide">
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Others-Official
														Spend</label>
													<div class="col-lg-5">
														<input class="form-control" type="text" name="typeClaims"
															id="otherSpendhide" />
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Amount Range</label>
												<div class="col-lg-5">
													<div class="input-group input-large">
														<input class="form-control" name="amtFrom" id="amtFrom"
															type="text" placeholder="From" required> <span
															class="input-group-addon">-</span> <input
															class="form-control dpd2" name="amtTo" id="amtTo"
															type="text" placeholder="To" required>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-lg-offset-3 col-lg-5" align="right">
													<button type="submit" class="btn btn-sm btn-success"
														id="claimsSubmit">Submit</button>
													<button type="button"
														class="btn btn-sm  btn-danger closeModel">Cancel</button>
												</div>
											</div>
										</div>
									</div>

								</form>
								<div class="panel-body" id="editable-table">
									<div class="space15"></div>
									<div class="adv-table editable-table">
										<section id="flip-scroll">
											<table class="table table-striped table-hover  cf"
												id="claims-sample">
												<thead class="cf">
													<tr>
														<th>Name</th>
														<th>Alias</th>
														<th>Type</th>
														<th>SubType</th>
														<th>Class</th>
														<th>Amt</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<tr>
													</tr>
												</tbody>
											</table>
										</section>
									</div>
								</div>

							</div>

							<div class="tab-pane" id="claimMapping">

								<form id="claimMapForm" method="POST" class="form-horizontal">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!MappedWith");?>" />
									<div class="col-lg-6">
										<header style="margin-bottom: 9px;">Claim Rules Name</header>
										<div class="form-group">
											<div class="col-lg-10">
												<select class="form-control" id="claimRulechosen"
													name="claimIds">
                                       <?php
																																							foreach ( $claimRule as $index ) {
																																								foreach ( $index as $row ) {
																																									$str = explode ( "_", $row );
																																									echo "<option value='" . $str [0] . "'>" . $str [1] . " [ " . $str [0] . " ]<br>" . "</option>";
																																								}
																																							}
																																							?>
                                         </select>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<div class="col-lg-5">
												<label class="control-label">Maped To</label>
											</div>
											<div class="col-lg-7">
												<label for="employee" class="col-lg-6 control-label"><input
													name="applicableFor" checked id="employee" value="E"
													type="radio"> Employee</label> <label for="designation"
													class="col-lg-6 control-label"><input name="applicableFor"
													id="designation" value="F" type="radio"> Designation</label>
											</div>
										</div>


										<div class="form-group col-lg-12" id="empHide">
											<select class="form-control" id="emploChosen" multiple
												name="affectedIds[]">
		                                          <?php
																																												foreach ( $employeeOnly as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                 </select>
										</div>
										<div class="form-group col-lg-12 hide" id="designHide">
											<select class="form-control" id="designChosen" multiple
												name="affectedIds[]">
		                                           <?php
																																													foreach ( $designations as $row ) {
																																														echo "<option value='" . $row ['designation_id'] . "'>" . $row ['designation_name'] . " [ " . $row ['designation_id'] . " ] <br>" . "</option>";
																																													}
																																													?>
		                                             
		                                       </select>
										</div>

										<div class="col-lg-12">
											<button type="submit"
												class="btn btn-sm btn-success pull-right">ClaimMap</button>
										</div>
									</div>

								</form>

								<header class="panel-heading"> Maped Claims </header>
								<div class="container " style="width: 100%;">
									<section class="error-wrapper"
										style="margin-top: 2%; text-align: left">
										<div id="tableContent"></div>

									</section>
								</div>
							</div>
							<div class="tab-pane" id="claimRequested">
								<div class="panel-body" id="claimRequesteds">
									<div class="space15"></div>
									<div class="adv-table editable-table">
										<section id="flip-scroll">

											<table class="table table-striped table-hover  cf dataTable"
												id="claim_requested">
												<thead class="cf">
													<tr>
														<th>Employee Name</th>
														<th>Purpose</th>
														<th>Date</th>
														<th>Amount</th>
														<th>Status</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody id="claim_request_body">

												</tbody>
											</table>
										</section>
									</div>
								</div>
								<section class="panel" id="show_panel_p" style="display: none">
								</section>
								<div class="col-md-12" id="showurl" style="display: none;">

									<header class="panel-heading"> </header>

									<section class="panel">
										<div class="bio-graph-heading project-heading">
											<span class="employee_claim_name"></span><strong
												class="claim_name"></strong>
										</div>
										<div class="panel-body bio-graph-info">
											<!--<h1>New Dashboard BS3 </h1>-->
											<div class="row p-details">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="bio-row">
															<p>
																<span class="bold">Amount</span>: <span
																	class="claim_amount"></span>
															</p>
														</div>

														<div class="bio-row">
															<p>
																<span class="bold">Status </span>: <span
																	class="claim_status"></span>
															</p>
														</div>
														<div class="bio-row">
															<p>
																<span class="bold">Date</span>: <span class="claim_date"
																	style="width: 40%"></span>
															</p>
														</div>

														<div class="bio-row">
															<p style="text-align: justify">
																<span class="bold">Description</span>:&nbsp;<span
																	class="claim_desc" style="width: 76%"></span>
															</p>
														</div>
														<div class="bio-row">
															<p style="text-align: justify">
																<span class="bold">Reference No</span>:&nbsp;<span
																	class="claim_reference"></span>
															</p>
														</div>
														<div class="bio-row">
															<p style="text-align: justify">
																<span class="bold">Bill</span>:&nbsp; <span
																	class="attachment_claim" style="width: 75%"></span>
															</p>
														</div>

													</div>


													<div class="col-md-6">

														<header class="panel-heading"
															style="padding-left: 0px; font-weight: 600;"> Claim Rule
														</header>
														<div id="welld" class="show"
															style="margin-bottom: 0px; padding: 0em 0em 0px 12px;"></div>
													</div>

												</div>


											</div>
										</div>
										<div class="col-md-12" id="approvedornot"
											style="display: none">
											<section class="panel">
												<header class="panel-heading"
													style="padding-left: 0px; font-weight: 600;">
													Approve/Decline Claim </header>
												<div class="panel-body bio-graph-info">
													<!--<h1>New Dashboard BS3 </h1>-->
													<div class="row p-details" class="">
														<div class="col-md-12">
															<form class="form-horizontal" id="claim_approve_submit"
																METHOD="post">
																<input type="hidden" name="act"
																	value="<?php echo base64_encode($_SESSION['company_id']."!updateClaimbyemployee");?>" />
																<div class="form-group">
																	<div class="col-md-5">
																		<label class="control-label">Approved Amount</label>
																	</div>
																	<div class="col-md-5">
																		<input class="form-control" type="text"
																			name="approved_amount" id="approved_amount" value="0"
																			oninput="reFormate(this)">
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-md-5">
																		<label class="control-label">Remarks</label>
																	</div>
																	<div class="col-md-5">
																		<textarea class="form-control" rows="2" name="remarks"
																			id="remarks"></textarea>
																		<label id="remarks_id" style="display: none">Please
																			Enter your Remarks</label>
																	</div>
																	<input type="hidden" name="claimId" id="claimId"
																		value=""> <input type="hidden" name="status"
																		id="status_change" value="">
																</div>
																<div class="form-group">
																	<div class="col-lg-offset-2 col-lg-5" align="right"
																		style="margin-top: 10px; margin-left: 231px;">
																		<button type="button" class="btn btn-sm btn-success"
																			id="claim_approve">Approve</button>
																		<button type="button" class="btn btn-sm btn-warning"
																			id="claim_decline">Decline</button>
																		<button type="button" class="btn btn-sm  btn-danger "
																			id="claim_close_id">Cancel</button>
																	</div>
																</div>

															</form>
														</div>
													</div>
												</div>

											</section>
										</div>
								
								</div>
							</div>


							<div class="tab-pane" id="processClaim">
								<div class="show_pr">
									<div class="col-md-6">
										<header style="margin-bottom: 9px; cursor: pointer;"
											id="by_emp">Process By Employee</header>
										<div class="arrow" id="employee_arrow"></div>
									</div>
									<div class="col-md-6">
										<header style="margin-bottom: 9px; cursor: pointer;"
											id="by_date">Process By Date</header>
										<div class="arrow" id="date_arrow" style="display: none"></div>
									</div>
								</div>
								<div class="employee_process over">
									<div class="col-md-6 over"style="margin-left:150px">
										<form id="by_employee_name" method="POST">
											<input type="hidden" name="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!showProcessClaimByEmployee");?>" />
											<div class="form-group">
												<div class="col-lg-8">
													<label class="col-lg-2">Employee Name</label>
													<div class="col-lg-9">
													<select class="form-control"
														id="process_claim_employee"
														name="process_claim_employee[]">

													</select>
                                                   </div>
												</div>
											</div>
											<div class="col-lg-4" style="margin-top:1px">
												<button type="submit"
													class="btn btn-sm btn-info process_claim_cr">View Claims</button>
											</div>
										</form>

									</div>
								</div>
								<div class="date_process" style="display: none">
									<div class="col-md-6 over">
										<form id="by_date_emp" method="POST">
											<input type="hidden" name="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!showProcessClaimByDate");?>" />
											<div class="form-group">
												<label for="dname" class="col-lg-3 col-sm-3 control-label text-right">Date
													Range</label>
												<div class="col-lg-5">
													<div class="input-group input-large">
														<input class="form-control validate_claim_check valid"
															name="from_date" id="dateFrom" type="text"
															placeholder="dd/mm/yyyy"> <span class="input-group-addon">-</span>
														<input class="form-control dpd2 validate_claim_check"
															name="to_date" id="dateTo" type="text"
															placeholder="dd/mm/yyyy">
													</div>
												</div>
											</div>
											<div class="col-lg-4">
												<button type="submit"
													class="btn btn-sm btn-info process_claim_cr">View Claim</button>
											</div>
										</form>
									</div>


								</div>
								<div class="" id="show_employee_table">
									<div class="panel-body">
										<div class="space15"></div>
										<div class="adv-table editable-table">
											<img src="../img/ajax-loader.gif" class="ajax_loader1"
												style="display: none">
											<div class="tableProcess" id="tableProcess"></div>
										</div>
									</div>
								</div>
							</div>

                          <div class="tab-pane" id="processedClaims">
								<div class="panel-body" id="processedClaims">
									<div class="space15"></div>
									
									<div class="adv-table editable-table">
										<section id="flip-scroll">
                                     
											<table class="table table-striped table-hover  cf dataTable"
												id="claim_processed">
												<thead class="cf">
													<tr>
													    <th>Processed On</th>
														<th>Amount</th>
														<th>Count</th>
														<th>Status</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody id="claim_processed_body">

												</tbody>
											</table>

										</section>
								       </div>
								       </div>
								       </div>
								<section class="panel" id="show_panel_p" style="display: none">
								</section>
                               <!-- help block starts here -->
							<div class="helpblock">
                        		<div class="alert" role="alert">
                        		<div class="alert alert-info alert-dismissible fade in" role="alert">
                         			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp; 	By clicking Add will <b>add</b> a rule for raising claim and Map it to designations/Employee.</p>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp; 	Employees can only raise claims under the rule they're <b>mapped</b> with.</p>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp; 	You can <b>Process</b> the approved claims either by employee or between the dates the claims are raised. (You can select the claims you want to process and click process)</p>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp; 	<b>Processed</b> Claims displays the claims you've processed so far by date of process. Clicking on the Amount will provide the details about claims.</p>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp;  	Download will download the payroll statement for the process.</p>
  								</div>
								</div>
							</div>
									<!--help block end  --> 
                              
				</section>

		</section>

	</section>

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
		tabindex="-1" id="claim_edit" class="modal fade">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close"
						type="button">×</button>
					<h4 class="modal-title">Edit Claim Rules</h4>
				</div>
				<div class="modal-body">
					<form id="claimeditForm" method="POST" class="form-horizontal"
						role="form">
						<input type="hidden" name="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
						<div class="form-group col-lg-12">
							<label class="col-lg-5 col-sm-5 control-label">Name</label>
							<div class="col-lg-7">
								<input class="form-control" type="hidden" id="cId" name="cId"
									required /> <input class="form-control" type="text" id="ecName"
									name="cName" required />
							</div>
						</div>

						<div class="form-group col-lg-12">
							<label class="col-lg-5 col-sm-5 control-label">Alias Name</label>
							<div class="col-lg-7">
								<input class="form-control" type="text" id="ecAlias"
									name="cAlias" required />
							</div>
						</div>



						<div class="form-group col-lg-12">
							<label class="col-lg-5 col-sm-5 control-label">Type</label>
							<div class="col-lg-7">
								<select class="form-control" id="etype" name="type" required>
									<option value="">Select Type</option>
									<option value="travel">Travel</option>
									<option value="hotel">Hotel Accommodation</option>
									<option value="others">Others-Official Spend</option>
								</select>
							</div>
						</div>
						<div id="etravelHide" class="hide">
							<div class="form-group col-lg-12">
								<label class="col-lg-5 col-sm-5 control-label">Type Of Travel</label>
								<div class="col-lg-7">
									<select class="form-control" id="etypeTravel" name="typeClaims">
										<option value="train">Train</option>
										<option value="bus">Bus</option>
										<option value="flight">Flight</option>
									</select>
								</div>
							</div>

							<div class="form-group col-lg-12">
								<label class="col-lg-5 col-sm-5 control-label">Class Of Travel</label>
								<div class="col-lg-7">
									<select class="form-control" id="eclassTravel"
										name="classClaims">
										<option value="firstac">1st AC</option>
										<option value="secondac">2nd AC</option>
										<option value="thirdac">3rd AC</option>
										<option value="sl">SL</option>
										<option value="acsl">AC SL</option>
										<option value="acssl">AC-Semi SL</option>
										<option value="ssl">Semi SL</option>
										<option value="ec">EC</option>
										<option value="bc">BC</option>
									</select>
								</div>
							</div>
						</div>

						<div id="ehotelHide" class="hide">
							<div class="form-group col-lg-12">
								<label class="col-lg-5 col-sm-5 control-label">Type Of Hotel</label>
								<div class="col-lg-7">
									<select class="form-control" id="etypeHotel" name="typeClaims">
										<option value="ac">AC Room</option>
										<option value="nac">NON AC</option>
									</select>
								</div>
							</div>

							<div class="form-group col-lg-12">
								<label class="col-lg-5 col-sm-5 control-label">Class of Hotel</label>
								<div class="col-lg-7">
									<select class="form-control" id="eclassHotel"
										name="classClaims">
										<option value="7star">7 Star</option>
										<option value="5star">5 Star</option>
										<option value="4star">4 Star</option>
										<option value="3star">3 Star</option>
										<option value="NA">Others</option>
									</select>
								</div>
							</div>
						</div>

						<div id="eothersHide" class="hide">
							<div class="form-group col-lg-12">
								<label class="col-lg-5 col-sm-5 control-label">Other Spend</label>
								<div class="col-lg-7">
									<input class="form-control" type="text" name="typeClaims"
										id="otherSpend" />
								</div>
							</div>
						</div>

						<div class="form-group col-lg-12">
							<label class="col-lg-5 col-sm-5 control-label">Amount Range</label>
							<div class="col-lg-7">
								<div class="input-group input-large">
									<input class="form-control" name="amtFrom" id="eamtFrom"
										type="text" placeholder="From" required> <span
										class="input-group-addon">-</span> <input
										class="form-control dpd2" name="amtTo" id="eamtTo" type="text"
										placeholder="To" required>
								</div>
							</div>
						</div>


						<div class="modal-footer">
							<button type="submit" class="btn btn-sm btn-success"
								id="claimseditSubmit">Update</button>
							<button type="button" class="btn btn-sm  btn-danger closeModel">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
		tabindex="-1" id="billurl_request" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close"
						type="button">&times;</button>
					<h4 class="modal-title">View Claim Attachment</h4>
				</div>
				<div class="modal-body">

					<div class="fileupload-new thumbnail">
						<img id="preview_imaged" style="width: 100%; height: 100%;"
							alt="Employee Image">
					</div>

				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>

				</div>
			</div>
		</div>
	</div>
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
		tabindex="-1" id="billurl_request_table" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close"
						type="button">&times;</button>
					<h4 class="modal-title">View Claim Attachment</h4>
				</div>
				<div class="modal-body">

					<div class="fileupload-new thumbnail">
						<img id="preview_image" style="width: 100%; height: 100%;"
							alt="Employee Image">
					</div>

				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>

				</div>
			</div>
		</div>
	</div>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
      
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
    	      $('#claim_tabs a[href="#' + tab + '"]').tab('show');
    	      
    	  	var sum = 0;
    	  }else{
    		  $('#claim_tabs a[href="#claimRules"]').tab('show');
    		  
    		  } 

    	  // Change hash for page-reload
    	  $('#claim_tabs a').on('shown.bs.tab', function (e) {
    	      window.location.hash = e.target.hash;
    	  })
    	});


      $('#claim_tabs').on('shown.bs.tab', function (e) {
    	   // newly activated tab
    	   $('#claim_sd').html("");
    	   bflag = 0;
    	   window.scrollTo(0, 0);
    	  if($(e.target).data('loaded') === false){
			if($(e.target).data('title') == 'claimRules'){
				claimRuleTable.init();
				$('#claim_sd').html('');
			}else if($(e.target).data('title') == 'claimMapping'){
				$('#claim_sd').html("");
				mappedClaimTableDraw();
			}else if($(e.target).data('title') == 'claimRequests'){
				  var claimId = getParameterByName("cId");
				  if(claimId){
					  $('#claimRequesteds').hide();
					  $('#claim_sd').html('<span id="claim_back" style="display:block;text-align:center">View</span>');
					  viewDetailClaim(claimId); 
						
				  }else{
					  bflag = 1;
					  $('#claimRequesteds').show();
				  }
				  claimRequestTable(); 
			}else if($(e.target).data('title') == 'processClaim'){
				selectChange();
			}
			else if($(e.target).data('title') == 'processedClaims'){
				claimProcessedTable(); 
			}
			//make the tab loaded true to prevent re-loading while clicking.
      		$(e.target).data('loaded',true);
    		}
    	});
  	$('#claimreq,#claim_close_id').click(function(e){
  	  	if(bflag == 0){
  		 $('#showurl').hide();
		  $('#claimRequesteds').show();
		  $('#claim_sd').html("");
		  window.location.href = 'claims.php#claimRequested';
  	  	}else{
  	  	  $('#showurl').hide();
		  $('#claimRequesteds').show();
		  $('#claim_sd').html("");
  	  	}bflag = 1;
  	});
 function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }
 $('#dateTo,#dateFrom').datetimepicker({
     format: 'DD/MM/YYYY'        
 }); 
      //claim Rule Table Functions
      var claimRuleTable = function () {

                   return {

                       //main function to initiate the module
                       init: function () {

                           var oTable = $('#claims-sample').dataTable({
                               "aLengthMenu": [
                                   [5, 15, 20, -1],
                                   [5, 15, 20, "All"] // change per page values here
                               ],

                               // set the initial value
                               "iDisplayLength": 5,
                               "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                               "bProcessing": true,
                               "bServerSide": true,
                               "sAjaxSource": "php/claim-view.php",
                               "sServerMethod": "POST",

                               "sPaginationType": "bootstrap",
                               "oLanguage": {
                                   "sLengthMenu": "_MENU_ records per page",
                                   "oPaginate": {
                                       "sPrevious": "Prev",
                                       "sNext": "Next"
                                   }
                               },
                               "oColReorder": {
                                   "iFixedColumns": 1
                               },
                              
                               "aoColumns": [
                                      { "sName": "claim_name" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
                                       {"sName": "alias_name" },
                                       { "sName": "category_type" },
                                       { "sName": "sub_type" },
                                       { "sName": "class" },
               						   { "sName": "amount_to" },
                                       { "sName": "enabled", "bSortable": false },
                                         ],"aoColumnDefs": [{ "mRender": function 
                   	                        (data, type, row) {
                                        	 if(row[2]=='travel'){
               	                                 return 'Travel';
               	                             } else if(row[2]=='hotel'){
               	                            	 return 'Hotel';
                   	                             }else{
                       	                             return 'Others';
                       	                             }	                             
               	                         },
               	                             "aTargets": [2]

               	                         },{
                                    	     "mRender": function (data, type, row) {
                                    	    	 if(row[2]=='travel'){
													if(row[3]=='train'){
														return 'Train';
													}else if(row[3]=='bus'){
														return 'Bus'
													}else if(row[3]=='flight'){
														return 'Flight'
													}
                                            	     
                                        	     }else if(row[2]=='hotel'){
                                        	    	 if(row[3]=='ac'){
                                        	    			return 'AC';
                                        	    		}else if(row[3]=='nac'){
                                        	    			return 'Non-Ac';
                                        	     }}
                                        	     else if(row[2]=='others'){
                                        	    	 return row[3];
                                                     }
                                    	      },                                    	      
                                    	      "aTargets": [3]
                                    	  },{
                                    	     "mRender": function (data, type, row) {
                                    	    	 if(row[2]=='travel'){
													if(row[4]=='firstac'){
														return 'First AC';
													}else if(row[4]=='secondac'){
														return 'Second AC'
													}else if(row[4]=='thirdac'){
														return 'Third AC'
													}else if(row[4]=='sl'){
														return 'SL'
													}else if(row[4]=='acsl'){
														return 'AC SL'
													}else if(row[4]=='acssl'){
														return 'AC Semi SL'
													}else if(row[4]=='ssl'){
														return 'Semi SL'
													}else if(row[4]=='ec'){
														return 'EC'
													}else if(row[4]=='bc'){
														return 'BC'
													}
											  }else if(row[2]=='hotel'){
                                        	    	 if(row[4]=='7star'){
                                        	    			return 'Sevan Star';
                                        	    		}else if(row[4]=='5star'){
                                        	    			return 'Five Star';
                                        	            }else if(row[4]=='4star'){
                                        	    			return 'Four Star';
                                        	            }else if(row[4]=='3star'){
                                        	    			return 'Three Star';
                                               	        }else if(row[4]=='NA'){
                                        	    			return 'Others';
                                               	        }else{
                                               	        	return '-';
                                                   	    }}
                                        	     else if(row[2]=='others'){
                                        	    	 return '-';
                                        	    	 }
                                    	      },                                    	      
                                    	      "aTargets": [4]
                                    	  },{ "mRender": function 
                     	                        (data, type, row) {
                   	                       
                                         	  return '<i class="fa fa-rupee"></i> '+row[5]+'-'+row[08];
                	                                                       
                	                         },
                	                             "aTargets": [5]

                	                         },],
                               "oTableTools": {
                                   "aButtons": [
                               {
                                   "sExtends": "pdf",
                                   "mColumns": [0, 1, 2, 3, 4, 5],
                                   "sPdfOrientation": "landscape",
                                   "sPdfMessage": "Claim Rules"
                               },
                               {
                                   "sExtends": "xls",
                                   "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
                               }
                            ],
                                   "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                               }

                           });

                           $('#claims-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                                             <input class="form-control medium" id="searchInput" type="text">\
                                                             <span class="input-group-btn">\
                                                               <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                                             </span>\
                                                             <span class="input-group-btn">\
                                                               <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                                             </span>\
                                                         </div>');
                           $('#claims-sample_processing').css('text-align', 'center');
                           //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
                           jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
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
                           var nEditing = null;
                       
                           //Enable claims
                            $(document).on('click', "#claims-sample a.disable", function (e) {
                               e.preventDefault();
                               var nRow = $(this).parents('tr')[0];
                               var data = oTable.fnGetData(nRow);
                                BootstrapDialog.show({
               	                title:'Confirmation',
                                   message: 'Are Sure you want to Disable <strong>'+ data[0] +'<strong>',
                                   buttons: [{
                                       label: 'Disable',
                                       cssClass: 'btn-sm btn-success',
                                       autospin: true,
                                       action: function(dialogRef){
                                       	 $.ajax({
                    		                    dataType: 'html',
                    		                    type: "POST",
                    		                    url: "php/claim.handle.php",
                    		                    cache: false,
                    		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', cId:  data[07]  },
                    		                      complete:function(){
                    		                    	 dialogRef.close();
                    		                      },
                    		                    success: function (data) {
                    		                    	data = JSON.parse(data);
                    		                        if (data[0] == "success") {
                    		                        	oTable.fnDraw();
                    		                        	BootstrapDialog.alert(data[1]);
                    		                        }else if (data[0] == "error") {
               		                                    alert(data[1]);
               		                                }
                    		                    },
                   		                      error:function(jqXHR, textStatus, errorThrown) {
                  		                    	 BootstrapDialog.alert('Error Disabling Claim Rule : '+errorThrown+'');
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


                           //Disable claims
                           $(document).on('click', "#claims-sample a.enable", function (e) {
                               e.preventDefault();
                               var nRow = $(this).parents('tr')[0];
                               var data = oTable.fnGetData(nRow);
                               BootstrapDialog.show({
               	                title:'Confirmation',
                                   message: 'Are Sure you want to Enable <strong>'+ data[0] +'</strong>',
                                   buttons: [{
                                       label: 'Enable',
                                       cssClass: 'btn-sm btn-success',
                                       autospin: true,
                                       action: function(dialogRef){
                                       	 $.ajax({
                    		                    dataType: 'html',
                    		                    type: "POST",
                    		                    url: "php/claim.handle.php",
                    		                    cache: false,
                    		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>',cId:data[07]},
                    		                    complete:function(){
                  		                    	 dialogRef.close();
                  		                      },
                    		                    success: function (data) {
                    		                    	data = JSON.parse(data);
                    		                        if (data[0] == "success") {
                    		                        	oTable.fnDraw();
                    		                        	BootstrapDialog.alert(data[1]);
                    		                            
                    		                        }else if (data[0] == "error") {
               		                                    alert(data[1]);
               		                                }
                    		                    },
                     		                      error:function(jqXHR, textStatus, errorThrown) {
                     		                    	 BootstrapDialog.alert('Error Enabling the Claim Rule : '+errorThrown+'');
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




                           $(document).on('click', "#claims-sample a.claim_edit", function (e) {
           	                  e.preventDefault();
                           /* Get the row as a parent of the link that was clicked on */
           	                var nRow = $(this).parents('tr')[0];
           	                var data = oTable.fnGetData(nRow);
           	                $('#ecName').val(data[0]);
           	                $('#ecAlias').val(data[1]); 
           	                $("#etype option[value='" + data[2] + "']").prop("selected", true);
                            $("#etype").trigger("chosen:updated"); //for drop down
                            $('#ehotelHide,#etravelHide,#eothersHide').addClass('hide');
                            $("#etypeHotel,#eclassHotel,#eclassTravel,#etypeTravel,#otherSpend").prop("disabled", true);  
                            $('#etypeHotel,#eclassHotel,#eclassTravel,#etypeTravel,#otherSpend').prop('disabled', true).trigger("chosen:updated"); 
                           
                           if(data[2]=='travel'){
	                        	  $("#eclassTravel,#etypeTravel").prop("disabled", false);  
	                              $('#eclassTravel,#etypeTravel').prop('disabled', false).trigger("chosen:updated");
	                              $('#etravelHide').removeClass('hide Show');
                        	      $('#etravelHide').addClass('Show');
								  $("#etypeTravel option[value='" + data[3] + "']").prop("selected", true);
							      $("#etypeTravel").trigger("chosen:updated");
							      $("#eclassTravel option[value='" + data[4] + "']").prop("selected", true);
							      $("#eclassTravel").trigger("chosen:updated");
						}else if(data[2]=='hotel'){
								$("#etypeHotel,#eclassHotel").prop("disabled", false);  
				                $('#etypeHotel,#eclassHotel').prop('disabled', false).trigger("chosen:updated");  
				                $('#ehotelHide').removeClass('hide Show');
                      	        $('#ehotelHide').addClass('Show');
								$("#etypeHotel option[value='" + data[3] + "']").prop("selected", true);
							    $("#etypeHotel").trigger("chosen:updated");
							    $("#eclassHotel option[value='" + data[4] + "']").prop("selected", true);
							    $("#eclassHotel").trigger("chosen:updated");
						} else {
							$("#otherSpend").prop("disabled", false);  
							$('#eothersHide').removeClass('hide Show');
                  	        $('#eothersHide').addClass('Show');
                  	        $('#otherSpend').val(data[3]);
                  	        }
   						     $('#eamtFrom').val(data[5]);
						     $('#eamtTo').val(data[08]);
						     $('#cId').val(data[07]);
              	            });



                           //Edit claims
                           $('#claimeditForm').on('submit', function (e) {
                               e.preventDefault();
                                   	 $.ajax({
                                       datatype: "html",
                                       type: "POST",
                                       url: "php/claim.handle.php",
                                       cache: false,
                                       data: $('#claimeditForm').serialize(),
                                       beforeSend:function(){
                                        	$('#claimseditSubmit').button('loading'); 
                                         },
                                         complete:function(){
                                       	$('#claimseditSubmit').button('reset');
                                         },
                                       success: function (data) {
                                           data1 = JSON.parse(data);
                                           if (data1[0] == "success") {
                                               $('.close').click();
                                           	  BootstrapDialog.alert(data1[1]);
                                              oTable.fnDraw();
                                              $('#claimRulechosen').empty();
                                              var html='';
                                              for (var i = 0, len = data1[2].length; i < len; ++i) {
                                                 $.each(data1[2][i], function (k, v) {
                                                  var str=v.split('_');
                                                  html +='<option value='+str[0]+'>'+str[1]+' [ '+str[0]+' ]<br></option>';
                                                           });
                                              }
                                           $('#claimRulechosen').append(html);
                                           $('#claimRulechosen').chosen().trigger("chosen:updated");
                                           
                                              }
                                        },
             		                      error:function(jqXHR, textStatus, errorThrown) {
                 		                    	$('#claimRulechosen').html("");
                 		                    	$('#claimRulechosen').html('Error in Submiting the Form : '+errorThrown+''); 
               		                      },

                                   });
                           
                           });

                           
                           //Add claims
                           $('#claimsAddForm').on('submit', function (e) {
                               e.preventDefault();
                                   	 $.ajax({
                                       datatype: "html",
                                       type: "POST",
                                       url: "php/claim.handle.php",
                                       cache: false,
                                       data: $('#claimsAddForm').serialize(),
                                       beforeSend:function(){
                                        	$('#claimsSubmit').button('loading'); 
                                         },
                                         complete:function(){
                                       	$('#claimsSubmit').button('reset');
                                         },
                                       success: function (data) {
                                           data1 = JSON.parse(data);
                                           if (data1[0] == "success") {
                                        	  // $('#showhide').show();
                                           	$('#add-claim-toggle').toggle('hide');
                                              $('#claimsAddForm')[0].reset();
                                              BootstrapDialog.alert(data1[1]);
                                              oTable.fnDraw();
                                              $('#claimRulechosen').empty();
                                               var html='';
                                              for (var i = 0, len = data1[2].length; i < len; ++i) {
                                                 $.each(data1[2][i], function (k, v) {
                                                  var str=v.split('_');
                                                  html +='<option value='+str[0]+'>'+str[1]+' [ '+str[0]+' ]<br></option>';
                                                           });
                                              }
                                           $('#claimRulechosen').append(html);
                                           $('#claimRulechosen').chosen().trigger("chosen:updated");
                                             }
                                        },
           		                      error:function(jqXHR, textStatus, errorThrown) {
           		                    	$('#claimRulechosen').html("");
           		                    	$('#claimRulechosen').html('Error in Submiting the form : '+errorThrown+''); 
         		                      },

                                   });
                           
                           });


                       }

                   };

               } ();

               $('#showhide').on('click', function (e) {
            	   e.preventDefault();
                  // $(this).hide();
                  $('#add-claim-toggle').toggle('show');
                });
               
               $('.closeModel').on('click', function (e) {
            	   e.preventDefault();
                   $('#add-claim-toggle').hide();
                   $('.close').click();
                  // $('#showhide').show();
               });
			   //$('#add-claim-toggle').toggle('hide');
        	 $('#type,#classHotel,#classTravel,#typeHotel,#typeTravel,#etype,#eclassHotel,#eclassTravel,#etypeHotel,#etypeTravel,#emploChosen,#designChosen,#process_claim_employee').chosen();
        	 flag=0;
        	 $('#claimRulechosen').chosen().trigger("chosen:updated");
        	 $("#emploChosen option").prop("selected", false);
             $("#emploChosen").trigger("chosen:updated");
             $("#designChosen option").prop("selected", false);
             $("#designChosen").trigger("chosen:updated");  
             $('#employee').click();
           

      
          
           
        $('#type').on('change', function (e) {
        	 e.preventDefault();
               var selected_id = $('#type :selected').val();
               $('#travelHide,#hotelHide,#othersHide').addClass('hide');
               $('#'+selected_id+'Hide').removeClass('hide Show');
               $('#'+selected_id+'Hide').addClass('Show');
               $('#otherSpendhide').prop("disabled", true);
               $("#typeHotel,#classHotel,#classTravel,#typeTravel").prop("disabled", true);  
               $('#typeHotel,#classHotel,#classTravel,#typeTravel').prop('disabled', true).trigger("chosen:updated"); 
             
               if(selected_id=='travel'){
               $("#classTravel,#typeTravel").prop("disabled", false);  
               $('#classTravel,#typeTravel').prop('disabled', false).trigger("chosen:updated");
               }else if(selected_id=='hotel'){
            	   $("#typeHotel,#classHotel").prop("disabled", false);  
                   $('#typeHotel,#classHotel').prop('disabled', false).trigger("chosen:updated");  
               }else{
            	   $('#otherSpendhide').prop("disabled", false);
            	   }      
         });


       // For Eidt Action in Claims riule page
        $('#etype').on('change', function (e) {
        	 e.preventDefault();
               var selected_id = $('#etype :selected').val();
               $('#etravelHide,#ehotelHide,#eothersHide').addClass('hide');
               $('#e'+selected_id+'Hide').removeClass('hide Show');
               $('#e'+selected_id+'Hide').addClass('Show');
               $('#otherSpend').prop("disabled", true);
               $("#etypeHotel,#eclassHotel,#eclassTravel,#etypeTravel").prop("disabled", true);  
               $('#etypeHotel,#eclassHotel,#eclassTravel,#etypeTravel').prop('disabled', true).trigger("chosen:updated"); 
               if(selected_id=='travel'){
               $("#eclassTravel,#etypeTravel").prop("disabled", false);  
               $('#eclassTravel,#etypeTravel').prop('disabled', false).trigger("chosen:updated");
               }else if(selected_id=='hotel'){
            	   $("#etypeHotel,#eclassHotel").prop("disabled", false);  
                   $('#etypeHotel,#eclassHotel').prop('disabled', false).trigger("chosen:updated");  
               }else{
                   
            	   $('#otherSpend').prop("disabled", false);
            	   }               
         }); 


        //cliam Mappinf Function
         $(document).on('change', "input[name='applicableFor']", function (e) {
        	 e.preventDefault();
            	  $("#empHide,#designHide").removeClass('hide show');
            	  $("#empHide,#designHide").addClass('hide');
            	  var selectedOpt = $(this).val()=='E'?'empHide':'designHide';
            	  $('#'+selectedOpt).addClass('show');
            	  var selectAllDataId = $(this).val()=='F'?'emploChosen':'designChosen';
            	  $("#"+selectAllDataId+" option").prop("selected", false);
                  $("#"+selectAllDataId).trigger("chosen:updated"); 
       });

     


       //Disable coding
       $(document).on('click', "#example a.mapDisable", function (e) {
           e.preventDefault();
           var claimRule = $(this).parent().parent().children('td:eq(0)').text();
           var assignedTo = $(this).parent().parent().children('td:eq(2)').text();
           var cId = $(this).attr('id');
           var affectedId = $(this).data('id');
           BootstrapDialog.show({
               title:'Confirmation',
               message: 'Are Sure you want Unmap this ClaimRule <strong>'+claimRule+'</strong> assigned to <strong>'+assignedTo+'</strong>',
               buttons: [{
                   label: 'Yes',
                   cssClass: 'btn-sm btn-success',
                   autospin: true,
                   action: function(dialogRef){
                   	 $.ajax({
 		                    dataType: 'html',
 		                    type: "POST",
 		                    url: "php/claim.handle.php",
 		                    cache: false,
 		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!delete");?>', cId: cId ,affectedIds:affectedId},
 		                    complete:function(){
 		                    	 dialogRef.close();
 		                      },
 		                    success: function (data) {
 		                    	data = JSON.parse(data);
 		                        if (data[0] == "success") {
 		                        	BootstrapDialog.alert(data[1]);
 		                        	mappedClaimTableDraw();  
 	 		                   }else if (data[0] == "error") {
 	                                    alert(data[1]);
 	                                }
 		                    },
 		                      error:function(jqXHR, textStatus, errorThrown) {
 		                    	  BootstrapDialog.alert('Error in Delete Your Map Claim : '+errorThrown+'' ); 
 		                      },
 		                });
                           		                            
                   }
               }, {
                   label: 'No',
                   cssClass: 'btn-sm btn-danger',
                   action: function(dialogRef){
                       dialogRef.close();
                   }
               }]
           });
       });
       
       //Claim Map Form
         $('#claimMapForm').on('submit', function (e) {
             e.preventDefault();
             	 $.ajax({
                     datatype: "html",
                     type: "POST",
                     url: "php/claim.handle.php",
                     cache: false,
                     data: $('#claimMapForm').serialize(),
                     beforeSend:function(){
                      	$('#claimseditSubmit').button('loading'); 
                       },
                       complete:function(){
                     	$('#claimseditSubmit').button('reset');
                       },
                     success: function (data) {
                         data1 = JSON.parse(data);
                         if (data1[0] == "success") {
                             $('.close').click();
                         	  BootstrapDialog.alert(data1[1]);
                         	 mappedClaimTableDraw();
                         	 $('#claimRulechosen').chosen().trigger("chosen:updated");
                        	 $("#emploChosen option").prop("selected", false);
                             $("#emploChosen").trigger("chosen:updated");
                             $("#designChosen option").prop("selected", false);
                             $("#designChosen").trigger("chosen:updated");  
                         	 }
                      },
                      error:function(jqXHR, textStatus, errorThrown) {
                    	  BootstrapDialog.alert('Error Map Claim : '+errorThrown+''); 
                      },

                 });
                       
         });

         function mappedClaimTableDraw(){
        	 $.ajax({
                 datatype: "html",
                 type: "POST",
                 url: "php/claim.handle.php",
                 cache: false,
                 data: { act: '<?php echo base64_encode($_SESSION['company_id']."!selectMappedClaim");?>'},
                  /* beforeSend:function(){
                  	$('#claimseditSubmit').button('loading'); 
                   },
                   complete:function(){
                 	$('#claimseditSubmit').button('reset');
                   },*/
                 success: function (data) {
                     data1 = JSON.parse(data);
                     $('#tableContent').empty();
                     
                     var html ='<section id="flip-scroll"><table id="example" class="table table-striped table-hover  cf dataTable"><thead class="cf"><tr><th>ClaimId</th><th>ClaimName</th><th>AffectedFor</th><th>AffectedTo</th><th>Actions</th></tr></thead><tbody>';
                     for (var i = 0, len = data1[2].length; i < len; ++i) {
                         html +='<tr>';
                          $.each(data1[2][i], function (k, v) {
                             if(k=='Actions'){
                                 str=v.split('_');
                            	  html +='<td><a href="#"  title="Cancel" class="btn btn-danger btn-xs mapDisable" id='+str[0]+' data-id='+str[1]+'><i class="fa fa-times"></i> UnMap</a></td>';
                            	  }else{
                          html +='<td>'+v+'</td>';
                            	  }
                          });
                          html +='</tr>';
                     }
                      html +='</tbody></table></section>';
                    // console.log(html);
                 $('#tableContent').append(html);
             
                 var mappedClaimsTable = $('#example').dataTable( {
                	 "aLengthMenu": [
                                     [5, 15, 20, -1],
                                     [5, 15, 20, "All"] // change per page values here
                                 ],
                                 "oColVis": {
                            			"aiExclude": [ 0 ]
                            		},
                     "iDisplayLength": 5,
                     
        
                       } );
                 mappedClaimsTable.fnSetColumnVis( 0, false );
                  },
                  error:function(jqXHR, textStatus, errorThrown) {
                	  $('#tableContent').empty();
                  	$('#tableContent').append("Error in Loading Claim Map Table: "+errorThrown+"");  
                  },
 
             });
             

             }
       
      
        function claimRequestTable(){
          
       	 	$.ajax({
                datatype: "html",
                type: "POST",
                url: "php/claim.handle.php",
                cache: false,
                data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getAllClaim");?>'},
                success: function (data) {
                    data = JSON.parse(data);
                    if(data[0] == 'success'){
					$('#claim_request_body').html("");
					for(j=0;j<data[2].length;j++){
						var html = "";
						if(data[2][j].status == 'P'){
	        				var status = '<span class="badge view btn-sm btn-warning">Pending</span>';
	        				
	                  }else if(data[2][j].status == 'A'){
	                 	 var status = '<span class="badge  view btn-sm btn-success">Approved</span>';
	                 	 
	                  }else if(data[2][j].status == 'D'){
	                 	 var status = '<span class="badge  view btn-sm btn-danger">Declined</span>';
	                 	 
	                  } else if (data[2][j].status == 'R'){
	                  	 var status = '<span class="badge  view btn-primary">Processed</span>';
	                 } 
						html +='<tr><td>'+data[2][j].employee_name+'</td><td>'+data[2][j].purpose+'</td><td>'+data[2][j].date+'</td><td>'+data[2][j].amount+'</td><td>'+status+'</td><td><a id="view_data" data-view='+data[2][j].claim_id+' class="btn view btn-sm btn-primary"><i class="fa fa-eye"> </i> View</a></td></tr>';
						$('#claim_request_body').append(html);
						
					
					}
					var oTable = $('#claim_requested').dataTable( {
	                	 "aLengthMenu": [
	                                     [5, 15, 20, -1],
	                                     [5, 15, 20, "All"] // change per page values here
	                                 ],
	                     "iDisplayLength": 5,
	                     "aaSorting": [],
	                     "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                         "oTableTools": {
                             "aButtons": [
                         {
                             "sExtends": "pdf",
                             "mColumns": [0, 1, 2, 3, 4],
                             "sPdfOrientation": "landscape",
                             "sPdfMessage": "Claim"
                         },
                         {
                             "sExtends": "xls",
                             "mColumns": [0, 1, 2, 3, 4]
                         }
                      ],
                             "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                         }
                   
	                       } );
					 $('#claim_requested_wrapper .dataTables_filter').html('<div class="input-group">\
			                   <input class="form-control medium" id="searchInputs" type="text">\
			                   <span class="input-group-btn">\
			                     <button class="btn btn-white" id="searchFilters" type="button">Search</button>\
			                   </span>\
			                   <span class="input-group-btn">\
			                     <button class="btn btn-white" id="searchClears" type="button">Clear</button>\
			                   </span>\
			               </div>');
					 $('#claim_requested_processing').css('text-align', 'center');
					 jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
					 $('#searchInputs').on('keyup', function (e) {
						 if (e.keyCode == 13) {
						 oTable.fnFilter($(this).val());
						 } else if (e.keyCode == 27) {
						 $(this).parent().parent().find('input').val("");
						 oTable.fnFilter("");
						 }
						 });
					 $('#searchFilters').on('click', function () {
							
						 oTable.fnFilter($(this).parent().parent().find('input').val());
						 });
						 $('#searchClears').on('click', function () {
						 $(this).parent().parent().find('input').val("");
						 oTable.fnFilter("");
						 });
		               
                    }else if(data[0] == 'error'){
                    	$('#claim_request_body').html("");
                    	$('#claim_request_body').html("<tr><td colspan='6'><input type='hidden' name='asd' id='asd' value='NO'/>No Data Found</td>");
                    }
                },
                error:function(jqXHR, textStatus, errorThrown) {
                	$('#claim_request_body').html("");
                	$('#claim_request_body').html("<tr><td colspan='6'>Error Loading Requested Claims : "+errorThrown+"</td>");  
                },

                });
      
            } 
        function claimProcessedTable(){
            
       	 	$.ajax({
                datatype: "html",
                type: "POST",
                url: "php/claim.handle.php",
                cache: false,
                data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getAllProcessedClaim");?>'},
                success: function (data) {
                    data = JSON.parse(data);
                    if(data[0] == 'success'){
					$('#claim_processed_body').html("");
					for(j=0;j<data[2].length;j++){
						var html = "";
						if(data[2][j].status == 'R'){
	        				var status = '<span class="badge  view btn-primary">Processed</span>';
	        				
	                  } 
						html +='<tr><td>'+data[2][j].processed_on+'</td><td>'+data[2][j].amount+'</td><td>'+data[2][j].count+'</t6d><td>'+status+'</td><td><form method="post" action="php/claim.handle.php" id="payout_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!downloadGeneratePdf" ) ;?>'+'"><input type="hidden" name="claimId" id="processedDate" value="' +data[2][j].processed_on+ '"><input type="hidden" name="name_pdf" id="name_pdf" value='+data[2][j].processed_on+'> <a  title="Download Payout" ><button id="payoutDownload"  class="btn btn-success btn-sm"><i class="fa fa-download"></i><span>Download</span></button></a></form></td></tr>';
						$('#claim_processed_body').append(html);
						
					
					}
					var oTable = $('#claim_processed').dataTable( {
	                	 "aLengthMenu": [
	                                     [5, 15, 20, -1],
	                                     [5, 15, 20, "All"] // change per page values here
	                                 ],
	                     "iDisplayLength": 5,
	                     "aaSorting": [],
	                     "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
	                     
	                     "aoColumnDefs": [{
                    	      // `data` refers to the data for the cell (defined by `mData`, which
                    	      // defaults to the column being worked with, in this case is the first
                    	      // Using `row[0]` is equivalent.
                    	    
                    	      "mRender": function (data, type, row) {
								if(data){
                        		return '<span class="give_tooltip" data-loaded="false" title="" data-content="" data-placement="right" id="'+row[0]+'" data-input="'+row[0]+'" tabindex="0"><a href="javascript:void(0)">'+row[1]+'</a></span>';
								
								}	      
                        
                    	      },
                        	   "aTargets": [1]
                        	      
                    	      
                    	  }],
                        "oTableTools": {
                            "aButtons": [
                        {
                            "sExtends": "pdf",
                            "mColumns": [0, 1, 2, 3, 4],
                            "sPdfOrientation": "landscape",
                            "sPdfMessage": "Claim"
                        },
                        {
                            "sExtends": "xls",
                            "mColumns": [0, 1, 2, 3, 4]
                        }
                     ],
                            "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                        },
                        
                  
	                       } );
					 $('#claim_processed_wrapper .dataTables_filter').html('<div class="input-group">\
			                   <input class="form-control medium" id="searchInputs" type="text">\
			                   <span class="input-group-btn">\
			                     <button class="btn btn-white" id="searchFilters" type="button">Search</button>\
			                   </span>\
			                   <span class="input-group-btn">\
			                     <button class="btn btn-white" id="searchClears" type="button">Clear</button>\
			                   </span>\
			               </div>');
					 $('#claim_requested_processed').css('text-align', 'center');
					 jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
					 $('#searchInputs').on('keyup', function (e) {
						 if (e.keyCode == 13) {
						 oTable.fnFilter($(this).val());
						 } else if (e.keyCode == 27) {
						 $(this).parent().parent().find('input').val("");
						 oTable.fnFilter("");
						 }
						 });
					 $('#searchFilters').on('click', function () {
							
						 oTable.fnFilter($(this).parent().parent().find('input').val());
						 });
						 $('#searchClears').on('click', function () {
						 $(this).parent().parent().find('input').val("");
						 oTable.fnFilter("");
						 });
		               
                   }else if(data[0] == 'error'){
                   	$('#claim_processed_body').html("");
                   	$('#claim_processed_body').html("<tr><td colspan='6'><input type='hidden' name='asd' id='asd' value='NO'/>No Data Found</td>");
                   }
               },
               error:function(jqXHR, textStatus, errorThrown) {
               	$('#claim_processed_body').html("");
               	$('#claim_processed_body').html("<tr><td colspan='6'>Error Loading Requested Claims : "+errorThrown+"</td>");  
               },

               });
     
           } 
                   

        function viewDetailClaim(claimId)
        {
        	$('.ajax_loader').show();
            $.ajax({
           	  type: "POST",
                url: "php/claim.handle.php",
                cache: false,
                data:{act: '<?php echo base64_encode($_SESSION['company_id']."!viewClamisbyInd");?>',claimIds:claimId},
            success: function (data) {
           data = JSON.parse(data);
           $('.ajax_loader').hide();
           $('#claimrequesteds').hide();
          if(data[0] == 'success'){
        	  $('#showurl').show();
          	$('#show_panel_name').show();
          	$('.attachment_claim').html("");
        	$('#welld').html("");
           	 for(var j=0;j<1;j++){
               	 
           		if(data[2][j].amount_approved =='0.00' && data[2][j].status == 'D'){
                      var amount = '<i class="fa fa-rupee"></i> '+data[2][j].amount_approved;
                  }else{
                      var amount = '<i class="fa fa-rupee"></i> '+data[2][j].amount;
                  }if(data[2][j].status == 'P'){
        				var status = '<a class="btn  view btn-sm btn-warning">Pending</a>';
        				$('#approvedornot').show();
                  }else if(data[2][j].status == 'A'){
                 	 var status = '<a class="btn  view btn-sm btn-success">Approved</a>';
                 	 $('#approvedornot').hide();
                  }else if(data[2][j].status == 'D'){
                 	 var status = '<a class="btn  view btn-sm btn-danger">Declined</a>';
                 	 $('#approvedornot').hide();
                  } else if (data[2][j].status == 'R'){
                 	 var status = '<a class="btn  view btn-sm btn-primary">Processed</button>';
                	 var color = '#fff';
                	 $('#approvedornot').hide();
                 } 
                  $('#claimId').attr('value',data[2][j].claim_id);
                  	$('.employee_claim_name').html(data[2][j].employee_name+" ["+data[2][j].employee_id+"]"+"'s Claim For ");
        			 $('.claim_name').html(data[2][j].purpose);
        			 $('.claim_desc').html(data[2][j].description);
        			 $('.claim_amount').html(amount);
        			 $('.claim_reference').html(data[2][j].reference_no);
        			 $('.claim_status').html(status);
        			 $('.claim_date').html(data[2][j].date);
        			 var phtml = "";
        			 if(data[2][j].bill_url){
        	            phtml +='<a class="btn view btn-sm btn-primary" href="#billurl_request"  id=com'+data[2][j].claim_id+' data-url='+data[2][j].bill_url+' data-toggle="modal" target="_blank">View Bill</a>';
        	       	} 
        		    $('.attachment_claim').append(phtml);
        		    $('#preview_imaged').attr('src',' ');
        			if(data[2][j].claim_id!=''){
        				$(document).on('click','#com'+data[2][j].claim_id,function(e){
        					e.preventDefault();
        				var dataurl = $(this).data('url');
        				$('#preview_imaged').attr('src',dataurl);
        				});
        			}
        	       	$('#welld').append('<h5>Type : <em>'+data[2][j].category_type+'</em></h5><h5>Sub Type : <em>'+data[2][j].sub_type+'</em></h5><h5>Class : <em>'+data[2][j].class+'</em></h5><h5>Amount : <em> <i class="fa fa-rupee"></i> '+data[2][j].amount_from+' - <i class="fa fa-rupee"></i> '+data[2][j].amount_to+'</em></h5>');
        		 }
        				 
        		}else if(data[0] == 'error'){
        			$('#showurl').hide();
        			$('#show_panel_p').show();
        			$('#show_panel_p').html('<div class="text-center"><h5>No Claims Found</h5></div>');
        		}

              },
              error:function(jqXHR, textStatus, errorThrown) {
            	  $('.ajax_loader').hide();
                  $('#claimrequesteds').hide();
            	$('#show_panel_p').show();
      			$('#show_panel_p').html('<div class="text-center"><h5>Error Loading Claims: '+errorThrown+'</h5></div>');
            },

        	});
        }
        $(document).on('click','#view_data',function (e){
        	e.preventDefault();
        	var data = $(this).data('view');
        	$('#claim_sd').html('<span id="claim_back" style="display:block;text-align:center">View</span>');
        	$('#claimRequesteds').hide();
			$('#showurl').show();
        	viewDetailClaim(data);
        });
        
        $('#claim_approve').click(function(e){
            e.preventDefault();
            $('#status_change').attr('value','A');
            if($('#approved_amount').val() == '' || $('#remarks').val()==''){
            	$('#remarks_id').show();
            }else{
            	$('#remarks_id').hide();
           BootstrapDialog.show({
                title:'Confirmation',
               message: 'Are Sure you want to Approve this Claim?',
               closable: true,
               closeByBackdrop: false,
               closeByKeyboard: false,
               buttons: [{
                   label: 'Approve',
                   cssClass: 'btn-sm btn-success',
                   autospin: true,
                   action: function(dialogRef){
                   	 $.ajax({
		                    dataType: 'html',
		                    type: "POST",
		                    url: "php/claim.handle.php",
		                    cache: false,
		                    data:$('#claim_approve_submit').serialize(),
		                    complete:function(){
		                    	 dialogRef.close();
		                      },
		                    success: function (data) {
		                    	data = JSON.parse(data);
		                        if (data[0] == "success") {
		                        	$('.claim_status,.claim_amount').html("");
		                        	$('.claim_status').html('<a class="btn  view btn-sm btn-success">Approved</a>');
		                        	$('.claim_amount').html('<i class="fa fa-inr"></i> '+$('#approved_amount').val());
		                        	$('#claim_approve_submit')[0].reset();
		                        	$('#approvedornot').hide();
		                        	selectChange();
		                        	
		                        	var said = "";
                                    var said = $('#asd').val() ;
                                    if(said == 'NO'){
                                 	   $('#asd').val('YES')
                                    }else{
                                 	   $('#claim_requested').dataTable().fnDestroy();
                                    }
		                        	claimRequestTable(); 
		                        	}
	                        	},
	                        	error:function(jqXHR, textStatus, errorThrown) {
		                    	BootstrapDialog.alert('Error in Approving Your Claim : '+errorThrown+'');
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
            }
        });
        $('#claim_decline').click(function(e){
            e.preventDefault();
            $('#status_change').attr('value','D');
 if($('#approved_amount').val() == '' || $('#remarks').val()==''){
                $('#remarks_id').show();
            }else{
            	 $('#remarks_id').hide();
           BootstrapDialog.show({
                title:'Confirmation',
               message: 'Are Sure you want to Decline this Claim?',
               closable: true,
               closeByBackdrop: false,
               closeByKeyboard: false,
               buttons: [{
                   label: 'Decline',
                   cssClass: 'btn-sm btn-warning',
                   autospin: true,
                   action: function(dialogRef){
                   	 $.ajax({
		                    dataType: 'html',
		                    type: "POST",
		                    url: "php/claim.handle.php",
		                    cache: false,
		                    data:$('#claim_approve_submit').serialize(),
		                    complete:function(){
		                    	 dialogRef.close();
		                      },
		                    success: function (data) {
		                    	data = JSON.parse(data);
		                        if (data[0] == "success") {
		                        	$('.claim_status,.claim_amount').html("");
		                        	$('.claim_status').html('<a class="btn view btn-sm btn-danger">Declined</a>');
		                        	$('.claim_amount').html('<i class="fa fa-inr"></i> '+$('#approved_amount').val());
		                        	$('#claim_approve_submit')[0].reset();
		                        	$('#approvedornot').hide();
		                        	var said = "";
                                    var said = $('#asd').val() ;
                                    if(said == 'NO'){
                                 	   $('#asd').val('YES')
                                    }else{
                                 	   $('#claim_requested').dataTable().fnDestroy();
                                    }
		                        	claimRequestTable(); 	
			                   }
		                    },
		                    error:function(jqXHR, textStatus, errorThrown) {
		                    	BootstrapDialog.alert('Error in Decline Your Claim : '+errorThrown+'');
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
            }
        });
		
		$('#by_emp').click(function(e){
			 e.preventDefault();
			$('#employee_arrow,.employee_process').show();
			$('.date_process,#date_arrow').hide();
			$('#tableProcess').empty();
			$('#employee_names').html("");
			$('#by_date_emp')[0].reset();
		});
		$('#by_employee_name').on('submit',function(e){
			 e.preventDefault();
			 $('.ajax_loader1').show();
			 processClaim($('#by_employee_name'));
		});
		$('#by_date_emp').on('submit',function(e){
			 e.preventDefault();
			 if($('#dateFrom').val() == '' && $('#dateTo').val() == ''){
			 }else{
			 $('.ajax_loader1').show();
			 processClaim($('#by_date_emp'));
			 }
		});
		function processClaim(data){
			
			 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/claim.handle.php",
                 cache: false,
                 data:data.serialize(),
                 beforeSend:function(){
                   	$('.process_claim_cr').button('loading'); 
                    },
                    complete:function(){
                  	$('.process_claim_cr').button('reset');
                    },
                 success: function (data) {
                 	data = JSON.parse(data);
                 	$('.ajax_loader1').hide();
                 	
                 	if(data[0] == 'success'){
                 		$('#tableProcess').empty();
                 		if($('#dateFrom').val() == '' && $('#dateTo').val() == ''){
                 	 	 for(var j=0;j<1;j++){
							nameId = "<div style='display:inline-block;margin-bottom: 15px;margin-top:15px;font-size:15px;text-align:center;' class='col-md-12' id='employee_names'>"+data[2][j].employee_name+"'s Claims<div class='generate_pdf pull-right' id='generate_pdf' style='display:none'><form action='php/claim.handle.php' method='post' id='generate_pdf_form'><input name='claimId' class='processedDate' value='"+data[2][j].claim_id+"' type='hidden'><input type='hidden' name='name_pdf' id='name_pdf' value="+data[2][j].employee_name.replace(/ /g,'')+"><input type='hidden' name='act' id='act' value='<?php echo base64_encode($_SESSION['company_id'].'!downloadGeneratePdf');?>'><button type='button' class='btn btn-primary' data-toggle='tooltip' title='Download Payout Structure for Processed Claims'><i class='fa fa-file-pdf-o' style='margin-right:10px;'></i>Payout Statement</button></form></div></div>";
                     	 	 }
                 		}else{
                 			nameId = '<div style="display:inline-block;margin-bottom: 15px;margin-top:15px;font-size:15px;text-align:center;" class="col-md-12" id="employee_names">Claims From '+$('#dateFrom').val()+' To '+$('#dateTo').val()+'<div class="generate_pdf pull-right" id="generate_pdf" style="display:none"><form action="php/claim.handle.php" method="post" id="generate_pdf_form"><input name="claimId" class="claimId_generate" value="" type="hidden"><input type="hidden" name="name_pdf" id="name_pdf" value='+$('#dateFrom').val() +'-To-'+$('#dateTo').val()+'><input type="hidden" name="act" id="act" value="<?php echo base64_encode($_SESSION['company_id']."!downloadGeneratePdf");?>"><button type="button" class="btn btn-primary" data-toggle="tooltip" title="Download Payout Structure for Processed Claims"><i class="fa fa-file-pdf-o" style="margin-right:10px;" ></i>Payout Statement</button></form></div></div>';
                 		}
                     	 	var html ='<section id="flip-scroll">'+nameId+'<table id="example" class="table  table-striped table-condensed"><thead><tr><th class="	" style="padding:11px;"><input type="checkbox" class="mail-checkbox mail-group-checkbox"></div></div></th><th>Name</th><th class="numeric">Date</th><th class="pr_cliams">Processed Claims</th><th class="numeric">Amount</th></tr></thead><tbody>';
                            for (var i = 0, len = data[2].length; i < len; ++i) {
                               
                                if(data[2][i].status == 'A'){
									status = '<span class="badge bg-primary primary_span">Not Processed</span>';
                                }else{
                                	status = '<span class="badge bg-success primary_span" style="position:relative!important">Processed</span>';
                                }
                                html +='<tr id="'+data[2][i].claim_id+'">';
                                 html +='<td class="inbox-small-cells"><input type="checkbox" name="mailcheckbox[]" value='+data[2][i].claim_id+' class="mail-checkbox mail_read" id='+data[2][i].amount+'></td><td>'+data[2][i].employee_name+'</td><td class="numeric">'+data[2][i].date+'</td><td id="replace_process">'+status+'</td><td class="numeric">'+data[2][i].amount+'</td>';
                                 
                                 html +='</tr>';
                            }
                            html +='<tr id="last_col"><td colspan="4" style="text-align:center;" >Total Amount</td><td id="total_amounts" style="font-weight:700"></td></tr>'
                             html +='</tbody></table><div class="col-md-8"></div><div class="col-md-4"> <button type="submit" class="btn btn-sm btn-primary" id="Process" style="margin-right:5px;">Process</button><button type="button" class="btn btn-sm  btn-danger cancel_process" >Cancel</button></div></section>';
                           // console.log(html);
                        $('#tableProcess').append(html);
                     	}else if(data[0] == 'error'){
                     		$('#tableProcess').empty();
                     		var htmls ='<div class="text-center" id="no_claim" style="padding-top: 100px !important;" >No Claims Found</div>';
                     		$('#tableProcess').html(htmls);
                     	}
                 },
                 error:function(jqXHR, textStatus, errorThrown) {
                 	
               },
             });
		}
		$(document).on('change',".mail-group-checkbox",function (e) {
			e.preventDefault();
			
			$(".mail-checkbox").not(':disabled').prop('checked', $(this).prop("checked"));
				
		});
		 $(document).on('change',".mail-checkbox",function(e){
			e.preventDefault();
			var sum = 0;
				data = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked:not(:disabled)'), function (n, i) {
	
					return sum +=Number(n.id);
				});
				
				$('#total_amounts').html(sum);
			
		}); 
		$(document).on('click','.cancel_process',function(e){
			e.preventDefault();
			$('#tableProcess').empty();
			$('#process_claim_employee option').prop('selected', false).trigger('chosen:updated');
			$('#by_date_emp')[0].reset();
			$('#employee_arrow,.employee_process,.date_process,#date_arrow').hide();
		});
		$('#by_date').click(function(e){
			e.preventDefault();
$('.date_process,#date_arrow').toggle();
$('#employee_arrow,.employee_process').hide();
$('#tableProcess').empty();
$('#employee_names').html("");
		});
		  
		$(document).on('click','#Process',function(e){
            e.preventDefault();
            data_claim = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked:not(:disabled)'), function (n, i) {
            	
				return n.value;
				
			}).join(',');
		if(data_claim == ''){
		}else{
           BootstrapDialog.show({
                title:'Confirmation',
               message: 'Are Sure you want to Process this Claim?',
               closable: true,
               closeByBackdrop: false,
               closeByKeyboard: false,
               buttons: [{
                   label: 'Process',
                   cssClass: 'btn-sm btn-success',
                   autospin: true,
                   action: function(dialogRef){
                   	 $.ajax({
		                    dataType: 'html',
		                    type: "POST",
		                    url: "php/claim.handle.php",
		                    cache: false,
		                    data:{claimId:data_claim,act: '<?php echo base64_encode($_SESSION['company_id']."!processClaim");?>'},
		                    complete:function(){
		                    	 dialogRef.close();
		                      },
		                    success: function (data) {
		                    	data = JSON.parse(data);
		                        if (data[0] == "success") {
			                    	var array = data_claim.toString().split(",") ;
			                    	console.log(array);
			                    	$('.pr_cliams').show();
			                    	$.each(array,function(i){
			                    		$('#'+array[i]+' #replace_process').html("<span class='badge bg-success'>Processed</span>");
										$('#'+array[i]+" .mail-checkbox").attr('checked', false);
										$('#'+array[i]+" .mail-checkbox").attr('disabled',true);
			                    		 });
			                    	$('.generate_pdf').show();
			                    	$('.claimId_generate').val(data_claim);
			                   }
		                    },
		                    error:function(jqXHR, textStatus, errorThrown) {
		                    	BootstrapDialog.alert('Error in Proccessing Your Claim : '+errorThrown+'');
		                    	
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
		}
        });
		 $(document).popover({
     		selector: '.give_tooltip',
     		trigger:'focus',
     		html:true,
     		animation: true,
     		
     		content: '<img src="../img/ajax-loader.gif" id="ajax_loader_getmessages">', 
    		});
 		
		 $(document).on('shown.bs.popover','.give_tooltip',function(e){
     	    e.preventDefault();
				var thisd = this;
				if($(this).data('loaded') == false){
				var value = $(this).data('input');
			
				$.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/claim.handle.php",
                 cache: false,
                 data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getProcessedClaims");?>',processed_on:value},
                 success: function (data) {
                  data = JSON.parse(data);
                 	var claims= data[2];
                  	var html="<table style='width:100%' class='table table-bordered' id='adv_ih' ><tbody>";
                    html +='<tr><td colspan="1"><b>EMP Name</b></td>'; html +='<td colspan="1"><b>Amount</b></td>';
                    html +='<td colspan="1"><b>Purpose</b></td></tr>';
				  for(i=0;i<claims.length;i++){
					  html +="<tr>";
					  
				    $.each(claims[i],function(k,v){
				    	html +="<td>"+v+"</td>";
					});
					html +="</tr>";
				  }
				  html+='<tbody></table>';
                  var popovers = $(thisd).attr('data-content',html).popover('show');
                  
                 
                 }
             });
				}else{
					 
					 
				}
				 $(thisd).data('loaded',true);
     	});
      
		
        function selectChange(){
        	 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/claim.handle.php",
                 cache: false,
                 data:{act: '<?php echo base64_encode($_SESSION['company_id']."!employeeInClaims");?>'},
                 success: function (data) {
                 	data = JSON.parse(data);
                 	$('#process_claim_employee').html("");
                 	if(data[0] == 'success'){
                 		var html ="";
                 		for(var i=0;i<data[2].length;i++){
							html +="<option value="+data[2][i].employee_id+">"+data[2][i].employee_name +"["+data[2][i].employee_id+"]</option>";
							}
                 		$('#process_claim_employee').html(html);
                 		 $("#process_claim_employee").trigger("chosen:updated");
                     	}else if(data[0] == 'error'){
                     		$('#process_claim_employee').html('<option value="No">No Employee Found</option>');
                     		 $("#process_claim_employee").trigger("chosen:updated");
                     	}
                 },
                 error:function(jqXHR, textStatus, errorThrown) {
                 	
               },
             });
        }
        $(document).on('click','.generate_pdf',function(e){
        	document.getElementById("generate_pdf_form").submit();
        });
      </script>


</body>
</html>