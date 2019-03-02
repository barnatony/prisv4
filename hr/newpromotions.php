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
<title>Promotion</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">

<style>
.chosen-container, #designChosen_chosen, #incbranchChosen12_chosen,
	#incempChosen12_chosen, #incempChosen_chosen, #incdesignChosen_chosen,
	#incdepartChosen_chosen, #incbranchChosen_chosen,
	#incbranchChosens_chosen {
	width: 100% !important;
}

@media ( min-width : 992px) {
	.modal-lg {
		width: 800px;
	}
}
</style>

</head>


<body>
	<section id="container" class="">
		<!--header start-->
         <?php
									
include_once (__DIR__ . "/header.php");
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/designation.class.php");
									$employee = new Employee ();
									$employee->conn = $conn;
									$employees = $employee->selectEmpdesig ();
									$employeeOnly = $employee->select ();
									
									$designation = new Designation ();
									$designation->conn = $conn;
									$designations = $designation->select ();
									
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/department.class.php");
									$department = new Department ();
									$department->conn = $conn;
									$departments = $department->select ();
									
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/branch.class.php");
									$branch = new Branch ();
									$branch->conn = $conn;
									$branchsOnly = $branch->select ();
									$branchs = $branch->selectEmpBranch ();
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
						<ul class="nav nav-tabs  ">
							<li class="active"><a href="#promotion" data-id="p"
								data-toggle="tab" id="promClick"> Promotion </a></li>
							<li class=""><a href="#increment" data-id="i" data-toggle="tab"
								id="incClick"> Increment </a></li>
							<li class=""><a href="#transfer" data-id="t" data-toggle="tab"
								id="transferClick"> Transfer </a></li>
							<li class="pull-right">
								<div class="btn-group " id="promo_t">
									<button type="button" class="btn btn-sm btn-info p_showhide"
										style="margin-right: 10px; margin-top: 10px;">
										<i class="fa fa-plus"></i> Promotion
									</button>
								</div>
								<div class="btn-group" id="incer_t" style="display: none;">
									<button type="button" class="btn btn-sm btn-info i_showhide"
										style="margin-right: 10px; margin-top: 10px;">
										<i class="fa fa-plus"></i> Increment
									</button>
								</div>
								<div class="btn-group" id="trans_t" style="display: none;">
									<button type="button" class="btn btn-sm btn-info t_showhide"
										style="margin-right: 10px; margin-top: 10px;">
										<i class="fa fa-plus"></i> Transfer
									</button>
								</div>

							</li>
							<!--      <li class="">
                                              <a href="#slabchange" data-toggle="tab" id="slabClick">
                                                  Change Slab
                                              </a>
                                          </li>  -->
						</ul>
					</header>
					<div class="col-md-12">
						<div id="addEmpreview">
							<div class="panel-body">
								<div class="tab-content tasi-tab">
									<div class="tab-pane active" id="promotion">
										<form class="form-horizontal" role="form" method="post"
											id="promotionForm" style="display: none">
											<input type="hidden" name="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!promote");?>" />
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Effects From</label>
													<div class="col-lg-4 input-group">
														<span class="input-group-addon" style="cursor: pointer"><i
															class="fa fa-calendar"></i></span>
														<div class="iconic-input right">
															<input class="form-control effectsFrom" id="effectsFrom"
																name="peffectsFrom" required type="text">
														</div>
														<div class="pull-right effectsFromerr"
															style="display: none;">
															<label id="effectsFromerr"></label>
														</div>
													</div>
												</div>
												<input type="hidden" value="1" name="proYesNo">
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Promotion
														For</label>
													<div class="col-lg-7 ">
														<label for="epromotion" class="col-lg-3 control-label"> <input
															id="epromotion" value="E" name="promotionFor"
															type="radio" checked> Employee
														</label> <label for="dpromotion"
															class="col-lg-3 control-label"> <input id="dpromotion"
															value="D" name="promotionFor" type="radio"> Designation
														</label>
													</div>
												</div>


												<div class="form-group employeeDiv">
													<label class="col-lg-3 col-sm-3 control-label">Employee
														Name</label>
													<div class="col-lg-4">
														<select class="form-control empChosen" name="empIds">
															<option value="">Select Employee Name</option>
		                                          <?php
																																												foreach ( $employees as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ] [" . $row ['designation_name'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right employeeNameerr"
															style="display: none;">
															<label id="employeeNameerr"></label>
														</div>
													</div>
												</div>


												<div class="form-group designDiv">
													<label class="col-lg-3 col-sm-3 control-label">Designation
														Name</label>
													<div class="col-lg-4">
														<select class="form-control designselected" name="desiIds">
															<option value="">Select Designation Name</option>
			                                          <?php
																																													foreach ( $designations as $row ) {
																																														echo "<option value='" . $row ['designation_id'] . "'>" . $row ['designation_name'] . " [ " . $row ['designation_id'] . " ] <br>" . "</option>";
																																													}
																																													?>
			                                         </select>
														<div class="pull-right designationerr"
															style="display: none;">
															<label id="designationerr"></label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">New
														Designation Name</label>
													<div class="col-lg-4">
														<select class="form-control designChosen"
															name="promotedTo">
															<option value="">Select Designation Name</option>
			                                          <?php
																																													foreach ( $designations as $row ) {
																																														echo "<option value='" . $row ['designation_id'] . "'>" . $row ['designation_name'] . " [ " . $row ['designation_id'] . " ] <br>" . "</option>";
																																													}
																																													?>
			                                         </select>
														<div class="pull-right designationNameerr"
															style="display: none;">
															<label id="designationNameerr"></label>
														</div>
													</div>
												</div>


												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Do You Want
														To Perform Inc ?</label>
													<div class="col-lg-7 ">
														<label for="incYes" class="col-lg-2 control-label"> <input
															id="incYes" value="1" name="incVal" type="radio"> Yes
														</label> <label for="incNo" class="col-lg-2 control-label">
															<input id="incNo" value="0" name="incVal" type="radio"
															checked> No
														</label>
													</div>
												</div>

												<div class="incDiv">
													<div class="form-group">
														<label for="dname" class="col-lg-3 col-sm-3 control-label">Increment
															As</label>
														<div class="col-lg-7">
															<label for="lumpsum" class="col-lg-3 control-label"> <input
																id="lumpsum" type="radio" value="1" name="calc"> Lump
																sum
															</label> <label for="percentage"
																class="col-lg-3 control-label"> <input id="percentage"
																type="radio" value="0" name="calc" checked> Percentage
															</label>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 col-sm-3 control-label">What Is <em
															class="incAs">Percentage ?</em></label>
														<div class="col-lg-4 input-group">
															<span class="input-group-addon pSpan">%</span> <span
																class="input-group-addon lSpan"><i class="fa fa-inr"></i>
															</span>
															<div class="iconic-input right">
																<input type="text" class="form-control" name="incAmount">
															</div>
														</div>
													</div>

												</div>
												<br>
												<div class="form-group">
													<div class="col-lg-offset-4 col-lg-4">
														<button type="submit" class="btn btn-sm  btn-success"
															id="proSubmit">Promote</button>
														<button type="button"
															class="btn btn-sm  btn-danger cancel_promo">Cancel</button>
													</div>
												</div>

											</div>

										</form>
									</div>

									<div class="tab-pane" id="increment">
										<form class="form-horizontal" role="form" method="post"
											id="incrementForm" style="display: none">
											<input type="hidden" name="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!increment");?>" />
											<input type="hidden" name="incVal" value="1"> <input
												type="hidden" name="proYesNo" value="0"> <input
												type="hidden" name="promotedTo" value="NA">
											<div class="col-lg-12">

												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Effects From</label>
													<div class="col-lg-4 input-group">
														<span class="input-group-addon" style="cursor: pointer"><i
															class="fa fa-calendar"></i></span>
														<div class="iconic-input right">
															<input class="form-control effectsFrom" id="effectsFrom1"
																required name="peffectsFrom" type="text">
														</div>
														<div class="pull-right effectsFromerr1"
															style="display: none;">
															<label id="effectsFromerr1"></label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Increment
														For</label>
													<div class="col-lg-7 ">
														<label for="einc" class="col-lg-3 control-label"> <input
															id="einc" value="E" name="promotionFor" type="radio"
															checked> Employee
														</label> <label for="dinc" class="col-lg-3 control-label">
															<input id="dinc" value="D" name="promotionFor"
															type="radio"> Designation
														</label> <label for="finc" class="col-lg-3 control-label">
															<input id="finc" value="F" name="promotionFor"
															type="radio"> Department
														</label> <label for="binc" class="col-lg-3 control-label">
															<input id="binc" value="B" name="promotionFor"
															type="radio"> Branch
														</label>
													</div>
												</div>


												<div class="form-group employeeCon">
													<label class="col-lg-3 col-sm-3 control-label">Employee
														Name</label>
													<div class="col-lg-4">
														<select class="form-control" id="incempChosen"
															name="empIds">
															<option value="">Select Employee Name</option>
		                                          <?php
																																												foreach ( $employeeOnly as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right employeeerr1"
															style="display: none;">
															<label id="employeeerr1"></label>
														</div>
													</div>
												</div>

												<div class="form-group designCon">
													<label class="col-lg-3 col-sm-3 control-label">Designation
														Name</label>
													<div class="col-lg-4">
														<select class="form-control" id="incdesignChosen"
															name="desiIds">
															<option value="">Select Designation Name</option>
		                                          <?php
																																												foreach ( $designations as $row ) {
																																													echo "<option value='" . $row ['designation_id'] . "'>" . $row ['designation_name'] . " [ " . $row ['designation_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right designationerr1"
															style="display: none;">
															<label id="designationerr1"></label>
														</div>
													</div>
												</div>

												<div class="form-group departCon">
													<label class="col-lg-3 col-sm-3 control-label">Department
														Name</label>
													<div class="col-lg-4">
														<select class="form-control" id="incdepartChosen"
															name="deparIds">
															<option value="">Select Department Name</option>
		                                          <?php
																																												foreach ( $departments as $row ) {
																																													echo "<option value='" . $row ['department_id'] . "'>" . $row ['department_name'] . " [ " . $row ['department_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right departmenterr1"
															style="display: none;">
															<label id="departmenterr1"></label>
														</div>
													</div>
												</div>

												<div class="form-group branchCon">
													<label class="col-lg-3 col-sm-3 control-label">Branch Name</label>
													<div class="col-lg-4">
														<select class="form-control" id="incbranchChosen"
															name="bracnIds">
															<option value="">Select Branch Name</option>
		                                          <?php
																																												foreach ( $branchsOnly as $row ) {
																																													echo "<option value='" . $row ['branch_id'] . "'>" . $row ['branch_name'] . " [ " . $row ['branch_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right brancherr1" style="display: none;">
															<label id="brancherr1"></label>
														</div>
													</div>
												</div>


												<div class="form-group">
													<label for="dname" class="col-lg-3 col-sm-3 control-label">Increment
														As</label>
													<div class="col-lg-7">
														<label for="ilumpsum" class="col-lg-3 control-label"> <input
															id="ilumpsum" type="radio" value="1" name="calc"> Lump
															sum
														</label> <label for="ipercentage"
															class="col-lg-3 control-label"> <input id="ipercentage"
															type="radio" value="0" name="calc" checked> Percentage
														</label>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">What Is <em
														class="incAs">Percentage ?</em></label>
													<div class="col-lg-4 input-group">
														<span class="input-group-addon pSpan">%</span> <span
															class="input-group-addon lSpan"><i class="fa fa-inr"></i>
														</span>
														<div class="iconic-input right">
															<input type="text" class="form-control" id="incAmount"
																name="incAmount">
															<div class="pull-right incAmounterr"
																style="display: none;">
																<label id="incAmounterr"></label>
															</div>
														</div>
													</div>
												</div>

												<br>
												<div class="form-group">
													<div class="col-lg-offset-4 col-lg-4">
														<button type="submit" class="btn btn-sm  btn-success"
															id="incSubmit">Inc</button>
														<button type="button"
															class="btn btn-sm btn-danger cancel_inc">Cancel</button>
													</div>
												</div>

											</div>


										</form>

									</div>
									<div class="tab-pane" id="transfer">
										<form class="form-horizontal" role="form" method="post"
											id="transferForm" style="display: none">
											<input type="hidden" name="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!transfer");?>" />
											<input type="hidden" name="incVal" value="0"> <input
												type="hidden" name="proYesNo" value="1">
											<div class="col-lg-12">

												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Effects From</label>
													<div class="col-lg-4 input-group">
														<span class="input-group-addon" style="cursor: pointer"><i
															class="fa fa-calendar"></i></span>
														<div class="iconic-input right">
															<input class="form-control effectsFrom" id="effectsFrom2"
																required name="peffectsFrom" type="text">
														</div>
														<div class="pull-right effectsFrom2err"
															style="display: none;">
															<label id="effectsFrom2err"></label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">Transfer For</label>
													<div class="col-lg-7 ">
														<label for="etransfer" class="col-lg-3 control-label"> <input
															id="etransfer" value="E" name="promotionFor" type="radio"
															checked> Employee
														</label> <label for="btransfer"
															class="col-lg-3 control-label"> <input id="btransfer"
															value="B" name="promotionFor" type="radio"> Branch
														</label>
													</div>
												</div>


												<div class="form-group employeeCon">
													<label class="col-lg-3 col-sm-3 control-label">Employee
														Name</label>
													<div class="col-lg-4">
														<select class="form-control" id="incempChosen12"
															name="empIds">
															<option value="">Select Employee Name</option>
		                                          <?php
																																												foreach ( $branchs as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ] [ " . $row ['branch_name'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right employee2err"
															style="display: none;">
															<label id="employee2err"></label>
														</div>
													</div>
												</div>

												<div class="form-group branchCon">
													<label class="col-lg-3 col-sm-3 control-label">Branch Name</label>
													<div class="col-lg-4">
														<select class="form-control"
															id="incbranchChosen newBranch" name="bracnIds">
															<option value="">Select Branch Name</option>
		                                          <?php
																																												foreach ( $branchsOnly as $row ) {
																																													echo "<option value='" . $row ['branch_id'] . "'>" . $row ['branch_name'] . " [ " . $row ['branch_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right branch2err" style="display: none;">
															<label id="branch2err"></label>
														</div>
													</div>
												</div>


												<div class="form-group">
													<label class="col-lg-3 col-sm-3 control-label">New Branch
														Name</label>
													<div class="col-lg-4">
														<select class="form-control" id="incbranchChosen12"
															name="promotedTo">
															<option value="">Select Branch Name</option>
		                                          <?php
																																												foreach ( $branchsOnly as $row ) {
																																													echo "<option value='" . $row ['branch_id'] . "'>" . $row ['branch_name'] . " [ " . $row ['branch_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
														<div class="pull-right newbranch2err"
															style="display: none;">
															<label id="newbranch2err"></label>
														</div>
													</div>
												</div>

												<br>
												<div class="form-group">
													<div class="col-lg-offset-4 col-lg-4">
														<button type="submit" class="btn btn-sm btn-success"
															id="tranferSubmit">Transfer</button>
														<button type="button"
															class="btn btn-sm btn-danger cancel_trans">Cancel</button>
													</div>
												</div>

											</div>


										</form>

									</div>
									<div class="tab-pane" id="slabchange">salb change goes here</div>
								</div>
							</div>

						</div>
					</div>

					<div class="panel-body" id="pTable">

						<div class="space15"></div>
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover table-bordered cf"
									id="empreview-sample">
									<thead class="cf">
										<tr>
											<th>ID</th>
											<th>Promotion For</th>
											<th>Effective From</th>
											<th>Promoted Ids</th>
											<th>Increment Amount</th>
											<th>Affected Id</th>
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

					<div class="panel-body hide" id="iTable">
						<div class="iloader" style="width: 81.4%; height: 50%;"></div>

						<div class="space15"></div>
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover table-bordered cf"
									id="increview-sample">
									<thead class="cf">
										<tr>
											<th>ID</th>
											<th>Increment For</th>
											<th>Effective From</th>
											<th>Increment Amount</th>
											<th>AffectedId</th>
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

					<div class="panel-body hide" id="tTable">
						<div class="tloader" style="width: 81.4%; height: 50%;"></div>

						<div class="space15"></div>
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover table-bordered cf"
									id="tranferreview-sample">
									<thead class="cf">
										<tr>
											<th>ID</th>
											<th>Transfered For</th>
											<th>Effective From</th>
											<th>Affected Id</th>
											<th>Tranfered To</th>
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

					<!--    <div class="panel-body hide"  id="sTable">
                     <div class="space15"></div>                     
                          <div class="adv-table editable-table"> 
                         Content Here;
                       </div>
                      </div>
                        -->
				</section>
				<!-- page end-->
			</section>
		</section>
		<!-- Promotion Edit -->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
			tabindex="-1" id="promotion_edit" class="modal fade">
			<div class="modal-dialog  modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close"
							type="button">×</button>
						<h4 class="modal-title">Promotion Edit</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" role="form" method="post"
							id="promotionEditFrom">
							<input type="hidden" name="promotionId" id="promotionId" /> <input
								type="hidden" name="act"
								value="<?php echo base64_encode($_SESSION['company_id']."!promotionEdited");?>" />
							<div class="col-lg-12">
								<div class="form-group">
									<label class="col-lg-4 col-sm-4 control-label">Effects From</label>
									<div class="col-lg-7 input-group">
										<span class="input-group-addon" style="cursor: pointer"><i
											class="fa fa-calendar"></i></span>
										<div class="iconic-input right">
											<input class="form-control" id="effectsFromval"
												name="oldpeffectsFrom" type="hidden"> <input
												class="form-control effectsFrom" id="effectsFrom"
												name="peffectsFrom" type="text">
										</div>
									</div>
								</div>

								<input type="hidden" value="1" name="proYesNo">
								<div class="form-group">
									<label class="col-lg-4 col-sm-4 control-label">Promotion For</label>
									<label class="col-lg-4 control-label editepromotion"></label> <input
										class="editepromotion" name="promotionFor" type="hidden">
								</div>


								<div class="form-group employeeDiv">
									<label class="col-lg-4 col-sm-4 control-label">Employee Name</label>
									<input type="hidden" name="empIds" class="editempChosenval"> <label
										class="col-lg-7 control-label editempChosenval"></label>
								</div>


								<div class="form-group designDiv">
									<label class="col-lg-4 col-sm-4 control-label">Designation Name</label>
									<input type="hidden" name="desiIds" class="designselectedval">
									<label class="col-lg-7  control-label designselectedval"></label>
								</div>

								<div class="form-group">
									<label class="col-lg-4 col-sm-4 control-label">New Designation
										Name</label>
									<div class="col-lg-7">
										<input type="hidden" name="promotedTo0" id="oldpromotedTo"> <select
											class="form-control" id="designChosen" name="promotedTo">
											<option value="">Select Designation Name</option>
			                                          <?php
																																													foreach ( $designations as $row ) {
																																														echo "<option value='" . $row ['designation_id'] . "'>" . $row ['designation_name'] . " [ " . $row ['designation_id'] . " ] <br>" . "</option>";
																																													}
																																													?>
			                                         </select>
									</div>
								</div>


								<div class="form-group">
									<label class="col-lg-4 col-sm-4 control-label">Do You Want To
										Perform Inc ?</label>
									<div class="col-lg-12">
										<label for="editincYes" class="col-lg-4 control-label"> <input
											type="hidden" name="incVal0" id="oldincVal"> <input
											id="editincYes" value="1" name="incVal" type="radio"> Yes
										</label> <label for="editincNo" class="col-lg-4 control-label">
											<input id="editincNo" value="0" name="incVal" type="radio"
											checked> No
										</label>
									</div>
								</div>

								<div class="incDiv">
									<div class="form-group">
										<label for="dname" class="col-lg-4 col-sm-4 control-label">Increment
											As</label>
										<div class="col-lg-12">
											<label for="elumpsum" class="col-lg-3 control-label"> <input
												type="hidden" name="calc0" id="oldcalc"> <input
												id="elumpsum" type="radio" value="1" name="calc"> Lump sum
											</label> <label for="epercentage"
												class="col-lg-3 control-label"> <input id="epercentage"
												type="radio" value="0" name="calc" checked> Percentage
											</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-4 col-sm-4 control-label">What Is <em
											class="incAs">Percentage ?</em></label>
										<div class="col-lg-7 input-group">
											<span class="input-group-addon pSpan">%</span> <span
												class="input-group-addon lSpan"><i class="fa fa-inr"></i> </span>
											<div class="iconic-input right">
												<input type="hidden" name="incAmount0" id="oldincAmount"> <input
													type="text" class="form-control" name="incAmount"
													id="incAmount">
											</div>
										</div>
									</div>

								</div>
								<br>
								<div class="form-group">
									<div class="col-lg-12 col-lg-offset-4">
										<button type="submit" class="btn btn-sm  btn-success"
											id="promotioneditclick">UpdatePro</button>
										<button type="button"
											class="btn btn-sm  btn-danger editcancel">Cancel</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- inc_edit Edit -->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
			tabindex="-1" id="inc_edit" class="modal fade">
			<div class="modal-dialog  modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close"
							type="button">×</button>
						<h4 class="modal-title">Increment Edit</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" role="form" method="post"
							id="incEditForm">
							<input type="hidden" name="act"
								value="<?php echo base64_encode($_SESSION['company_id']."!incrementEdit");?>" />
							<input type="hidden" name="cal0" id="ioldcalc"> <input
								type="hidden" name="incVal" value="1">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">Effects From</label>
									<div class="col-lg-7 input-group">
										<span class="input-group-addon" style="cursor: pointer"><i
											class="fa fa-calendar"></i></span>
										<div class="iconic-input right">
											<input name="promotionId" id="ipromotionId" type="hidden"> <input
												class="form-control ieffectsFromval" name="oldpeffectsFrom"
												type="hidden"> <input
												class="form-control effectsFrom ieffectsFromval"
												name="peffectsFrom" type="text">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">Incremented For</label>
									<input name="promotionFor" class="incrementForval"
										type="hidden"> <label
										class="col-lg-7 control-label incchoenval"></label>
								</div>


								<div class="form-group employeeCon">
									<label class="col-lg-3 col-sm-3 control-label incchoenlabel"></label>
									<input name="empIds" class="incempChosenval" type="hidden"> <label
										class="col-lg-7 control-label incempChosenval"></label>
								</div>

								<div class="form-group designCon">
									<label class="col-lg-3 col-sm-3 control-label incchoenlabel"></label>
									<input name="desiIds" class="incdesignChosenval" type="hidden">
									<label class="col-lg-7 control-label incdesignChosenval"></label>
								</div>

								<div class="form-group departCon">
									<label class="col-lg-3 col-sm-3 control-label incchoenlabel"></label>
									<input name="deparIds" class="incdepartChosenval" type="hidden">
									<label class="col-lg-7 control-label incdepartChosenval"></label>
								</div>

								<div class="form-group branchCon">
									<label class="col-lg-3 col-sm-3 control-label incchoenlabel"></label>
									<input name="bracnIds" class="incbranchChosenval" type="hidden">
									<label class="col-lg-7 control-label incbranchChosenval"></label>
								</div>


								<div class="form-group">
									<label for="dname" class="col-lg-3 col-sm-3 control-label">Increment
										As</label>
									<div class="col-lg-9">
										<label for="iilumpsum" class="col-lg-3 control-label"> <input
											id="iilumpsum" type="radio" value="1" name="calc"> Lump sum
										</label> <label for="iipercentage"
											class="col-lg-3 control-label"> <input id="iipercentage"
											type="radio" value="0" name="calc" checked> Percentage
										</label>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">What Is <em
										class="incAs">Percentage ?</em></label>
									<div class="col-lg-7 input-group">
										<span class="input-group-addon pSpan">%</span> <span
											class="input-group-addon lSpan"><i class="fa fa-inr"></i> </span>
										<div class="iconic-input right">
											<input type="hidden" class="form-control ioldincAmount"
												name="incAmount0"> <input type="text"
												class="form-control ioldincAmount" name="incAmount">
										</div>
									</div>
								</div>

								<br>
								<div class="form-group">
									<div class="col-lg-offset-4 col-lg-4">
										<button type="submit" class="btn btn-sm btn-success"
											id="inceditSubmit">Inc</button>
										<button type="button"
											class="btn btn-sm  btn-danger editcancel">Cancel</button>
									</div>
								</div>

							</div>


						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Transfer Edit -->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
			tabindex="-1" id="transfer_edit" class="modal fade">
			<div class="modal-dialog  modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close"
							type="button">×</button>
						<h4 class="modal-title">Transfer Edit</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" role="form" method="post"
							id="transfereditForm">
							<input type="hidden" name="act"
								value="<?php echo base64_encode($_SESSION['company_id']."!transferEdit");?>" />
							<input type="hidden" id="tpro" name="promotionId">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">Effects From</label>
									<div class="col-lg-6 input-group">
										<span class="input-group-addon" style="cursor: pointer"><i
											class="fa fa-calendar"></i></span>
										<div class="iconic-input right">
											<input type="hidden" class="tpeffectsFrom"
												name="oldpeffectsFrom"> <input
												class="form-control effectsFrom tpeffectsFrom"
												name="peffectsFrom" type="text">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">Transfer For</label>
									<input name="promotionFor" class="trsferForval" type="hidden">
									<label class="col-lg-7 control-label transferIdsval"></label>
								</div>


								<div class="form-group employeeCon">
									<label class="col-lg-3 col-sm-3 control-label transferIdslabel"></label>
									<input name="empIds" class="tcempChosenval" type="hidden"> <label
										class="col-lg-7 control-label tcempChosenval"></label>
								</div>

								<div class="form-group branchCon">
									<label class="col-lg-3 col-sm-3 control-label transferIdslabel"></label>
									<input name="bracnIds" class="trbranchChosenval" type="hidden">
									<label class="col-lg-7 control-label trbranchChosenval"></label>
								</div>


								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">New Branch Name</label>
									<div class="col-lg-4">
										<input name="promotedTo0" class="promotedtr" type="hidden"> <select
											class="form-control" id="incbranchChosens" name="promotedTo">
											<option value="">Select Branch Name</option>
		                                          <?php
																																												foreach ( $branchsOnly as $row ) {
																																													echo "<option value='" . $row ['branch_id'] . "'>" . $row ['branch_name'] . " [ " . $row ['branch_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
									</div>
								</div>

								<br>
								<div class="form-group">
									<div class="col-lg-offset-4 col-lg-4">
										<button type="submit" class="btn btn-sm btn-success"
											id="tranfereditSubmit">Transfer</button>
										<button type="button" class="btn btn-sm btn-danger editcancel">Cancel</button>
									</div>
								</div>

							</div>


						</form>

					</div>
				</div>
			</div>
		</div>

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
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>

	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>

	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>

	<script src="../js/bootstrap-dialog.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          
        $(document).ready(function () {
           incFlag=0;tfFlag=0;
        	promotionEditableTable.init();
            
        	$('#designChosen,.empChosen,.designChosen,.designselected,.incdesignDiv,#incempChosen,#incempChosen12,#incdesignChosen,#incdepartChosen,#incbranchChosen,#incbranchChosens,#incbranchChosen12').chosen();
            $('.incDiv,.lSpan,.designDiv,.designCon,.departCon,.branchCon').hide();

            $('.effectsFrom').datetimepicker({
             	  format: 'DD/MM/YYYY',
              });
         
            $('.incemployeeDiv').html($('employeeDiv').html());
              });
        $('.p_showhide').on('click', function (event) {
            //var elem=$(this).attr('class').split(' ')[2];
           // $('.'+elem).hide();
           // $('.empChosen,.designChosen,.designselected').chosen();
           
            $('#promotionForm').toggle('show');
            $('#transferForm,#incrementForm').hide();
             //$(element).css('display')=='none'?$('.i_showhide,.p_showhide,.t_showhide').show():$('.i_showhide,.p_showhide,.t_showhide').hide();
         });
       $('.i_showhide').on('click', function (event) {
    	   $('#incrementForm').toggle('show');
    	   $('#promotionForm,#transferForm').hide();
       });
       $('.t_showhide').on('click', function (event) {
    	   $('#transferForm').toggle('show');
    	   $('#promotionForm,#incrementForm').hide();
       });
        $('.cancel_promo').on('click', function (event) {
        	
      	// var elementLi=$("ul.nav-tabs li.active").find('a');
      	// var buttonName=$(elementLi).data('id')+"_showhide";
      	$('#promotionForm').toggle('hide');
      	// var element =  $("#addEmpreview").toggle('hide');
      	
      	 //$('.i_showhide,.p_showhide,.t_showhide').show();
      	// $('.'+buttonName).show();
      //   $(element).css('display')=='none'?$('.'+buttonName).show():$('.'+buttonName).hide();
         });
        $('.cancel_inc').on('click', function (event) {
        	$('#incrementForm').toggle('hide');
        });
        $('.cancel_trans').on('click', function (event) {
        	$('#transferForm').toggle('hide');
        });
        $('.editcancel').on('click', function (event) {
            $('.close').click();
        });
        $('#promClick').on('click', function (event) {
        	 $('#pTable,#iTable,#tTable,#sTable').removeClass('hide show');
    		 $('#iTable,#tTable,#sTable').addClass('hide');
        	 $('#pTable').addClass('show');
        	 $('#incer_t,#trans_t').hide();
        	 $('#promo_t').show();
        			 
        });
        
        $('#incClick').on('click', function (event) {
        	$('.iloader').addClass('loader');
        	$('#pTable,#iTable,#tTable,#sTable').removeClass('hide show');
        	$('#pTable,#tTable,#sTable').addClass('hide');
        	 if (incFlag == 0) {
        		 incEditableTable.init();
        		 incFlag = 1;
            } 
         $('#iTable').addClass('show');
         $('.iloader').removeClass('loader');
         $('#promo_t,#trans_t').hide();
    	 $('#incer_t').show();
        });
        $('#transferClick').on('click', function (event) {
        	$('.tloader').addClass('loader');
        	$('#pTable,#iTable,#tTable,#sTable').removeClass('hide show');
        	$('#pTable,#iTable,#sTable').addClass('hide');
        	 if (tfFlag == 0) {
        		 tfEditableTable.init();
        		 tfFlag = 1;
            } 
          $('#tTable').addClass('show');
          $('.tloader').removeClass('loader');
          $('#promo_t,#incer_t').hide();
     	 $('#trans_t').show();
          });
        
       $('#slabClick').on('click', function (event) {
        	$('#pTable,#iTable,#tTable,#sTable').removeClass('hide show');
        	$('#pTable,#iTable,#tTable').addClass('hide');
        	$('#sTable').addClass('show');
          });
        $('#incYes,#editincYes').on('click', function (event) {
            $('.incDiv').show();
         });
        $('#incNo,#editincNo').on('click', function (event) {
            $('.incDiv').hide();
         });
        $('#lumpsum,#ilumpsum,#elumpsum,#iilumpsum').on('click', function (event) {
            $('.incAs').html('Lumpsum Amount ?');
            $('.lSpan').show();
            $('.pSpan').hide();
         });
        $('#percentage,#ipercentage,#iipercentage,#epercentage').on('click', function (event) {
            $('.incAs').html('Percentage ?');
            $('.lSpan').show();
            $('.pSpan').hide();
            $('.lSpan').hide();
            $('.pSpan').show();
         });

        $('#dpromotion').on('click', function (event) {
            $('.employeeDiv').hide();
            $('.designDiv').show();            
         });
   
        $('#epromotion').on('click', function (event) {
            $('.employeeDiv').show();
            $('.designDiv').hide();
         });
        
     
        $(document).on('change', "input[name='promotionFor']", function (e) {
      	  $(".employeeCon,.designCon,.departCon,.branchCon").hide();
      	  var selectedOpt = $(this).val()=='E'?'employeeCon':$(this).val()=='D'?'designCon':$(this).val()=='F'?'departCon':'branchCon';
      	  $('.'+selectedOpt).show();
        });
     // For tansfer
    	 var tfEditableTable = function () {
    	    return {
    	    	init: function () {
                //main function to initiate the module
    	      var transferTable = $('#tranferreview-sample').dataTable({
                         "aLengthMenu": [
                             [5, 15, 20, -1],
                             [5, 15, 20, "All"] // change per page values here
                         ],

                         // set the initial value
                         "iDisplayLength": 5,
                         "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                         "bProcessing": true,
                         "bServerSide": true,
                         "sAjaxSource": "php/transfers-view.php",
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
                                { "sName": "leave_rule_id" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
                                 {"sName": "rule_name" },
                                 { "sName": "frequency" },
                                 { "sName": "days_count" },
                                 { "sName": "days_count" },
                                 { "sName": "enabled", "bSortable": false },
         				   ],"aoColumnDefs": [{ "mRender": function 
   	                        (data, type, row) {
          					  if(data=='E'){
   	           					  return "Employee";
   	           					  }else{
   	           						 return "Branch";	
   	           						   }
 	                        	 return "Transformation";
 	                        } ,
 	                             "aTargets": [1]

 	                         },{ "mRender": function 
    	                        (data, type, row) {
     	                     var parts =row[2].split('-');
 			   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
 			   	             return effectFrom;
   	                       },
    	                             "aTargets": [2]
    	                         },{"bSearchable": false, "bVisible": false, "aTargets": [0]}  ], 


                         "oTableTools": {
                             "aButtons": [
                         {
                             "sExtends": "pdf",
                             "mColumns": [0, 1, 2, 3, 4, 5, 6],
                             "sPdfOrientation": "landscape",
                             "sPdfMessage": "Branch Details"
                         },
                         
                         {
                             "sExtends": "xls",
                             "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
                         }
                      ],
                             "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                         }

                     });

    	            $('#tranferreview-sample_wrapper .dataTables_filter').html('<div class="input-group">\
    	                                              <input class="form-control medium" id="searchInput" type="text">\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="tranfersearchFilter" type="button">Search</button>\
    	                                              </span>\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="tranfersearchClear" type="button">Clear</button>\
    	                                              </span>\
    	                                          </div>');
    	            $('#tranferreview-sample_processing').css('text-align', 'center');
    	            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
    	            $('#tranferreview-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
    	            $('#searchInput').on('keyup', function (e) {
    	                if (e.keyCode == 13) {
    	                	transferTable.fnFilter($(this).val());
    	                } else if (e.keyCode == 27) {
    	                    $(this).parent().parent().find('input').val("");
    	                   transferTable.fnFilter("");
    	                }
    	            });
    	            $(document).on("click", "#tranfersearchFilter", function () {
    	            	transferTable.fnFilter($(this).parent().parent().find('input').val());
    	            });
    	            $(document).on("click", "#tranfersearchClear", function () {
    	                $(this).parent().parent().find('input').val("");
    	               transferTable.fnFilter("");
    	            });

    	            var nEditing = null;

    	            //delete trasfer
    	            $(document).on('click',".transfer_delete",function(e) {
    	                e.preventDefault();
    	                var nRow = $(this).parents('tr')[0];
                        var data = transferTable.fnGetData(nRow);
                        var pId = data[0];
                        var peffectsFrom=data[2];
                        BootstrapDialog.show({
          	                title:'Confirmation',
                              message: 'Are Sure You Want To Delete this Given Transfer',
                              closable: true,
                              closeByBackdrop: false,
                              closeByKeyboard: false,                            
                              buttons: [{
                                  label: 'Yes',
                                  cssClass: 'btn-sm btn-success',
                                  autospin: true,
                                  action: function(dialogRef){
                                  	 $.ajax({
                                  		dataType: 'html',
     	    	                        type: "POST",
     	    	                        url: "php/employee.handle.php",
     	    	                        cache: false,
     	    	                        data: { act: '<?php echo base64_encode($_SESSION['company_id']."!transferdelete");?>',promotionFor: data[1],promotionId:pId,peffectsFrom:peffectsFrom,empIds:data[3]},
               		                   complete:function(){
             		                    	 dialogRef.close();
             		                      },
               		                    success: function (data) {
               		                    	data = JSON.parse(data);
               		                        if (data[0] == "success") {
               		                        	transferTable.fnDraw();
               		                        	BootstrapDialog.alert('Employee Transfer Deleted Successfully');
               		                            
               		                        }else if (data[0] == "error") {
          		                                    alert(data[1]);
          		                                }
               		                    }
               		                });
                                          		                            
                                  }
                              }, {
                                  label: 'NO',
                                  cssClass: 'btn-sm btn-danger',
        	                       action: function(dialogRef){
                                      dialogRef.close();
                                  }
                              }]
                          });
    	            });
    	           //tranfer
    	             $(document).on('click',"#tranferSubmit",function(e) {
    	                 e.preventDefault();
							var valid;
							if($('#effectsFrom2').val() == '' ){
								$('.effectsFrom2err').show();
								$('#effectsFrom2err').html('Effect From Field is Required');
								valid = false;
							}else{
								$('.effectsFrom2err').hide();
								$('#effectsFrom2err').html('');
								valid = true;
							}
							
							if($('#incempChosen12').val() == ''){
							
								$('.employee2err').show();
								$('#employee2err').html('Employee Name is Required');
								valid = false;
							}else{
								$('.employee2err').hide();
								$('#employee2err').html('');
								valid = true;
							}if($('#incbranchChosen12').val() == ''){
								$('.newbranch2err').show();
								$('#newbranch2err').html('New Branch Name is Required');
								valid = false;
							}else{
								$('.newbranch2err').hide();
								$('#newbranch2err').html('');
								valid = true;
							}if($('#btransfer').is(':checked')){
								if($('#newBranch').val() == ''){

	                            	 $('.branch2err').show();
	                             	$('#branch2err').html('Branch Name Field is Required');
	                             	 valid = false;
	                             }else{
	                            	 $('.branch2err').hide();
	                            	 $('#branch2err').html('');
									valid = true;
	                             }

							}

							
if($('#effectsFrom2').val() == ''  && $('#incempChosen12').val() == '' && $('#incbranchChosen12').val() == ''){
	valid = false;
}
    	                 if(valid == true){
    	                	$.ajax({
    	                         dataType: 'html',
    	                         type: "POST",
    	                         url: "php/employee.handle.php",
    	                         cache: false,
    	                         data: $('#transferForm').serialize(),
    	                         success: function (data) {
    	                             data1 = JSON.parse(data);
    	                             if(data1[0]=="success"){
        	                         $('#transferForm')[0].reset();
        	                         $('#addEmpreview').toggle('hide');
      	                             $('#incempChosen,#incbranchChosen,#incdepartChosen,#incbranchChosen').val('').trigger('chosen:updated');
      	                          transferTable.fnDraw();
      	                        var elementLi=$("ul.nav-tabs li.active").find('a');
         	                 	 var buttonName=$(elementLi).data('id')+"_showhide";
    	                             BootstrapDialog.alert("Employee Transfered Successfully");
    	                             }else{
    	                              	 BootstrapDialog.alert("Can't Performed");
    	                               }
    	                               }

    	                     });
    	                 }

    	               });
//tansfer edit

    	             $(document).on('click',"#tranfereditSubmit",function(e) {
    	            	 e.preventDefault();
	                     $.ajax({
	                         dataType: 'html',
	                         type: "POST",
	                         url: "php/employee.handle.php",
	                         cache: false,
	                         data: $('#transfereditForm').serialize(),
	                         success: function (data) {
	                             data1 = JSON.parse(data);
	                             if(data1[0]=="success"){
	        	                        $('.close').click();
	        	                        transferTable.fnDraw();
	    	                            BootstrapDialog.alert('Employee Transfer Edited Successfully');
	    	                            }else{
	    	                             	 BootstrapDialog.alert("Can't Performed");
	    	                                  }
	                             }

	                     });
    	             });
        	             
      	           //transfer Fuill
    	        var emp_array = [<?php echo json_encode($employeeOnly); ?>];
   	           var branch_array = [<?php echo json_encode($branchsOnly); ?>];
   	           $(document).on('click',".transfer_edit",function(e) {
   	                e.preventDefault();
   	                 var nRow = $(this).parents('tr')[0];
   	                 var data = transferTable.fnGetData(nRow);
   	                 $('#tpro').val(data[0]);
   	                 var parts =data[2].split('-');
		   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
		   	             $('.tpeffectsFrom').val(effectFrom);
		   	               var val=data[1];
		   	     $('.employeeCon,.branchCon').hide();
		   	          var selectClasss = val =='E'?'employeeCon':'branchCon';
		   	          var selectAllDataId = val =='E'?'Employee':'Branch';
		   	          $('.'+selectClasss).show();
		   	          $('.transferIdsval').html(selectAllDataId);
		   	          $('.trsferForval').val(val);
		   	        $('.transferIdslabel').html(selectAllDataId+" Name");
		   	          	
		   	         		
		   	       $('.tcempChosenval,.trbranchChosenval').val('');   
		   	      if(data[1]=='E'){
			   	        console.log(data[4]);
		   	         $('.tcempChosenval').val(data[3]);
		   	        var result = emp_array[0].filter(function(v,i) {
		   	        	if(emp_array[0][i].employee_id===data[3]){
		   	        		$('.tcempChosenval').html(emp_array[0][i].employee_name+" [ "+emp_array[0][i].employee_id +" ] ");
		   	        			}		   	         
		   	        });}else if(data[1]=='B'){
			   	           $('.trbranchChosenval').val(data[3]);
				   	        var result = branch_array[0].filter(function(v,i) {
				   	        	if(branch_array[0][i].branch_id===data[3]){
				   	        		$('.trbranchChosenval').html(branch_array[0][i].branch_name+" [ "+branch_array[0][i].branch_id +" ] ");
				   	            }		   	         
				   	        });
		   	        }
		   	        $('.promotedtr').val(data[4]);
		   	     $("#incbranchChosens option[value='" + data[4] + "']").prop("selected", true);
                 $("#incbranchChosens").trigger("chosen:updated");
		   	  
	               });
    	   	 }
	        
    	    };

    	     	} ();
        
//from INcremneteal
        var incEditableTable = function () {
    	    return {
    	    	init: function () {
                   //main function to initiate the module
    	      var incoTable = $('#increview-sample').dataTable({
                         "aLengthMenu": [
                             [5, 15, 20, -1],
                             [5, 15, 20, "All"] // change per page values here
                         ],

                         // set the initial value
                         "iDisplayLength": 5,
                         "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                         "bProcessing": true,
                         "bServerSide": true,
                         "sAjaxSource": "php/increment-view.php",
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
                                { "sName": "leave_rule_id" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
                                 {"sName": "rule_name" },
                                 { "sName": "frequency" },
                                 { "sName": "days_count" },
                                 { "sName": "carry_forward" },
                                { "sName": "enabled", "bSortable": false },
         				   ],"aoColumnDefs": [{ "mRender": function 
	     	                        (data, type, row) {
	     	                        if(row[1]=="E"){
			                           return "Employee";
			                         }else if(row[1]=="D"){
			                        	 return "Designation";
			                         }else if(row[1]=="F"){
			                        	 return "Department";
			                         }else if(row[1]=="B"){
			                        	 return "Branch";
			                         }} ,
			                             "aTargets": [1]

			                         },{ "mRender": function 
 		     	                        (data, type, row) {
 		                        	 var dataStr=row[3].split("|");
 		         					  if(row[3]=="0|A"){
 			                           return "--";
 			                         }else if(dataStr[1]=='A'){
 			                        	 return " <i class='fa fa-rupee'></i> "+dataStr[0];
 			                         }else if(dataStr[1]=='P'){
 			                        	 return dataStr[0]+" %";
 			                         }} ,
 			                             "aTargets": [3]

 			                         },{ "mRender": function 
    	                        (data, type, row) {
 			    	         var parts =row[2].split('-');
 			   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
 			   	             return effectFrom;
   	                       },
    	                             "aTargets": [2]

    	                         },{"bSearchable": false, "bVisible": false, "aTargets": [0]}  ],

                         "oTableTools": {
                             "aButtons": [
                         {
                             "sExtends": "pdf",
                             "mColumns": [0, 1, 2, 3, 4, 5, 6],
                             "sPdfOrientation": "landscape",
                             "sPdfMessage": "Branch Details"
                         },
                         {
                             "sExtends": "xls",
                             "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
                         }
                      ],
                             "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                         }

                     });

    	            $('#increview-sample_wrapper .dataTables_filter').html('<div class="input-group">\
    	                                              <input class="form-control medium" id="searchInput" type="text">\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="incsearchFilter" type="button">Search</button>\
    	                                              </span>\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="incsearchClear" type="button">Clear</button>\
    	                                              </span>\
    	                                          </div>');
    	            $('#increview-sample_processing').css('text-align', 'center');
    	            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
    	            $('#increview-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
    	            $('#searchInput').on('keyup', function (e) {
    	                if (e.keyCode == 13) {
    	                	incoTable.fnFilter($(this).val());
    	                } else if (e.keyCode == 27) {
    	                    $(this).parent().parent().find('input').val("");
    	                   incoTable.fnFilter("");
    	                }
    	            });
    	            $(document).on("click", "#incsearchFilter", function () {
    	            	incoTable.fnFilter($(this).parent().parent().find('input').val());
    	            });
    	            $(document).on("click", "#incsearchClear", function () {
    	                $(this).parent().parent().find('input').val("");
    	               incoTable.fnFilter("");
    	            });

    	            var nEditing = null;

    	           // increment form
    	             $(document).on('click',"#incSubmit",function(e) {
    	                 e.preventDefault();
						var valid;
						if($('#effectsFrom1').val() == ''){
							$('.effectsFromerr1').show();
							$('#effectsFromerr1').html('Effects From Field is Required');
							valid = false;
						}else{
							$('.effectsFromerr1').hide();
							$('#effectsFromerr1').html('');
							valid = true;
						}if($('#incempChosen').val() == ''){
							$('.employeeerr1').show();
							$('#employeeerr1').html('Employee Name Field is Required');
							valid = false;
						}else{
							$('.employeeerr1').hide();
							$('#employeeerr1').html('');
							valid = true;
						}if($('#incAmount').val() == ''){
							$('.incAmounterr').show();
							$('#incAmounterr').html('Increment Amount Field is Required');
							valid = false;
						}else{
							$('.incAmounterr').hide();
							$('#incAmounterr').html('');
							valid = true;
						}if($('#dinc').is(':checked')){
							if($('#incdesignChosen').val() == ''){

                           	 $('.designationerr1').show();
                            	$('#designationerr1').html('Designation Name Field is Required');
                            	 valid = false;
                            }else{
                           	 $('.designationerr1').hide();
                           	 $('#designationerr1').html('');
								valid = true;
                            }

						}	if($('#finc').is(':checked')){
							if($('#incdepartChosen').val() == ''){

	                           	 $('.departmenterr1').show();
	                            	$('#departmenterr1').html('Department Name Field is Required');
	                            	 valid = false;
	                            }else{
	                           	 $('.departmenterr1').hide();
	                           	 $('#departmenterr1').html('');
									valid = true;
	                            }

							}if($('#binc').is(':checked')){
								if($('#incbranchChosen').val() == ''){

		                           	 $('.brancherr1').show();
		                            	$('#brancherr1').html('Branch Name Field is Required');
		                            	 valid = false;
		                            }else{
		                           	 $('.brancherr1').hide();
		                           	 $('#brancherr1').html('');
										valid = true;
		                            }

								}
						
						if($('#effectsFrom1').val() == '' && $('#incempChosen').val() == '' && $('#incAmount').val() == ''){
							valid = false;
						}
						if(valid == true){
    	                 
    	                     $.ajax({
    	                         dataType: 'html',
    	                         type: "POST",
    	                         url: "php/employee.handle.php",
    	                         cache: false,
    	                         data: $('#incrementForm').serialize(),
    	                         success: function (data) {
    	                             data1 = JSON.parse(data);
    	                             if(data1[0]=="success"){
    	                            	 var element =  $("#addEmpreview").toggle();
  	    	                           $(element).css('display')=='none'?$('.i_showhide,.p_showhide,.t_showhide').show():$('.i_showhide,.p_showhide,.t_showhide').hide();
  	    	                          //  $('#addEmpreview').toggle('hide');
  	    	                          $('#incrementForm')[0].reset();
    	                             $('#incempChosen,#incdesignChosen,#incdepartChosen,#incbranchChosen').val('').trigger('chosen:updated');
    	                            incoTable.fnDraw();
    	                            var elementLi=$("ul.nav-tabs li.active").find('a');
    	                       	 var buttonName=$(elementLi).data('id')+"_showhide";
    	                            // $('#addEmpreview').toggle('hide');
    	                             BootstrapDialog.alert("Employee SalaryIncremented Sucessfully");
    	                               }else{
    	                              	 BootstrapDialog.alert("Can't Performed");
    	                               }
    	                             }

    	                     });
						}
    	          });

    	           //delete Incremnet
	    	            $(document).on('click',".inc_delete",function(e) {
	    	                e.preventDefault();
	    	                var nRow = $(this).parents('tr')[0];
	                        var data = incoTable.fnGetData(nRow);
	                        var pId = data[0];
	                        var peffectsFrom=data[2];
	                        console.log(peffectsFrom);
	                        BootstrapDialog.show({
	          	                title:'Confirmation',
	                              message: 'Are Sure You Want To Delete this Given Increment',
	                              closable: true,
	                                closeByBackdrop: false,
	                                closeByKeyboard: false,
	                              
	                              buttons: [{
	                                  label: 'Yes',
	                                  cssClass: 'btn-sm btn-success',
	                                    autospin: true,
	                                  action: function(dialogRef){
	                                  	 $.ajax({
	                                  		dataType: 'html',
	     	    	                        type: "POST",
	     	    	                        url: "php/employee.handle.php",
	     	    	                        cache: false,
	     	    	                        data: { act: '<?php echo base64_encode($_SESSION['company_id']."!delete");?>', promotionId:pId,peffectsFrom:peffectsFrom},
	               		                   complete:function(){
	             		                    	 dialogRef.close();
	             		                      },
	               		                    success: function (data) {
	               		                    	data = JSON.parse(data);
	               		                        if (data[0] == "success") {
	               		                        	incoTable.fnDraw();
	               		                        	BootstrapDialog.alert("Employee SalaryIncrement Deleted Sucessfully");
	               		                            
	               		                        }else if (data[0] == "error") {
	          		                                    alert(data[1]);
	          		                                }
	               		                    }
	               		                });
	                                          		                            
	                                  }
	                              }, {
	                                  label: 'NO',
	                                  cssClass: 'btn-sm btn-danger',
	          	                      action: function(dialogRef){
	                                      dialogRef.close();
	                                  }
	                              }]
	                          });
	    	            });
    	             //Edit increment form
    	             $(document).on('click',"#inceditSubmit",function(e) {
    	                 e.preventDefault();
    	                     $.ajax({
    	                         dataType: 'html',
    	                         type: "POST",
    	                         url: "php/employee.handle.php",
    	                         cache: false,
    	                         data: $('#incEditForm').serialize(),
    	                         success: function (data) {
    	                             data1 = JSON.parse(data);
    	                             if(data1[0]=="success"){
 	        	                        $('.close').click();
 	        	                       incoTable.fnDraw();
 	    	                            BootstrapDialog.alert("Employee SalaryIncrement Edited Sucessfully");
 	    	                            }else{
 	    	                             	 BootstrapDialog.alert("Can't Performed");
 	    	                                  }
    	                             }

    	                     });
    	          });
    	                var design_array = [<?php echo json_encode($designations); ?>];
	    	            var emp_array = [<?php echo json_encode($employeeOnly); ?>];
	    	            var depart_array = [<?php echo json_encode($departments); ?>];
	    	            var branch_array = [<?php echo json_encode($branchsOnly); ?>];
	    	           $(document).on('click',".inc_edit",function(e) {
	    	                e.preventDefault();
	    	                 var nRow = $(this).parents('tr')[0];
	    	                 var data = incoTable.fnGetData(nRow);
	    	                 $('#ipromotionId').val(data[0]);
	    	                 var parts =data[2].split('-');
			   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
			   	             $('.ieffectsFromval').val(effectFrom);
			   	               var val=data[1];
			   	          $('.employeeCon,.designCon,.departCon,.branchCon').hide();
			   	          var selectClasss = val =='E'?'employeeCon':val =='D'?'designCon':val =='F'?'departCon':'branchCon';
			   	          var selectAllDataId = val =='E'?'Employee':val =='D'?'Designation':val =='F'?'Department':'Branch';
			   	          $('.'+selectClasss).show();
			   	          $('.incchoenval').html(selectAllDataId);
			   	          $('.incrementForval').val(val);	
			   	          $('.incchoenlabel').html(selectAllDataId+" Name");		
			   	       $('.incdesignChosenval,incempChosenval,incbranchChosenval').val('');      
			   	        if(data[1]=='E'){
			   	         $('.incempChosenval').val(data[4]);
			   	        var result = emp_array[0].filter(function(v,i) {
			   	        	if(emp_array[0][i].employee_id===data[4]){
			   	        		$('.incempChosenval').html(emp_array[0][i].employee_name+" [ "+emp_array[0][i].employee_id +" ] ");
			   	        			}		   	         
			   	        });}else if(data[1]=='D'){
			   	           $('.incdesignChosenval').val(data[4]);
			   	        var result = design_array[0].filter(function(v,i) {
			   	        	if(design_array[0][i].designation_id===data[4]){
			   	        		$('.incdesignChosenval').html(design_array[0][i].designation_name+" [ "+design_array[0][i].designation_id +" ] ");
			   	            }		   	         
			   	        });
			   	        }else if(data[1]=='B'){
				   	           $('.incbranchChosenval').val(data[4]);
					   	        var result = branch_array[0].filter(function(v,i) {
					   	        	if(branch_array[0][i].branch_id===data[4]){
					   	        		$('.incbranchChosenval').html(branch_array[0][i].branch_name+" [ "+branch_array[0][i].branch_id +" ] ");
					   	            }		   	         
					   	        });
			   	        }else if(data[1]=='F'){
						   	           $('.incdepartChosenval').val(data[4]);
							   	        var result = depart_array[0].filter(function(v,i) {
							   	        	if(depart_array[0][i].department_id===data[4]){
							   	        		$('.incdepartChosenval').html(depart_array[0][i].department_name+" [ "+depart_array[0][i].department_id +" ] ");
							   	            }		   	         
							   	        });
							   	        }	  

			   	     var dataStr=data[3].split("|");
	                    
	                    (dataStr[1]=="A")?$('#ioldcalc').val(1):$('#ioldcalc').val(0);
					    (dataStr[1]=="A")?$('#iilumpsum').click():$('#iipercentage').click();
					     $('.ioldincAmount').val(dataStr[0]);
					      	                
		               });
    	   	 }
	        
    	    };

    	     	} ();


    	     	//Add Promotion
    	        
    	        var promotionEditableTable = function () {
    	    	    return {
    	    	    	init: function () {
    						 //main function to initiate the module
    	    	      var oTable = $('#empreview-sample').dataTable({
    	                         "aLengthMenu": [
    	                             [5, 15, 20, -1],
    	                             [5, 15, 20, "All"] // change per page values here
    	                         ],

    	                         // set the initial value
    	                         "iDisplayLength": 5,
    	                         "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
    	                         "bProcessing": true,
    	                         "bServerSide": true,
    	                         "sAjaxSource": "php/promotion-view.php",
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
    	                                { "sName": "leave_rule_id" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
    	                                 {"sName": "rule_name" },
    	                                 { "sName": "frequency" },
    	                                 { "sName": "days_count" },
    	                                 { "sName": "carry_forward" },
    	                                 { "sName": "carry_forward" },
    	                                { "sName": "enabled", "bSortable": false },
    	         				   ],"aoColumnDefs": [{ "mRender": function 
   	     	                        (data, type, row) {
   	     	                        if(row[1]=="E"){
   			                           return "Employee";
   			                         }else if(row[1]=="D"){
   			                        	 return "Designation";
   			                         }} ,
   			                             "aTargets": [1]

   			                         },{ "mRender": function 
    		     	                        (data, type, row) {
    		         					  if(row[4]=="NA"){
    			                           return "--";
    			                         }else{
    			                        	 return data;
    			                         }} ,
    			                             "aTargets": [3]

    			                         },{ "mRender": function 
    			     	                        (data, type, row) {
    			                        	 var dataStr=row[4].split("|");
    			         					  if(row[4]=="0|A"){
    				                           return "--";
    				                         }else if(dataStr[1]=='A'){
    				                        	 return " <i class='fa fa-rupee'></i> "+dataStr[0];
    				                         }else if(dataStr[1]=='P'){
    				                        	 return dataStr[0]+" %";
    				                         }} ,
    				                             "aTargets": [4]

    				                         },{ "mRender": function 
    	   	                        (data, type, row) {
    				    	         var parts =row[2].split('-');
    				   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
    				   	             return effectFrom;
    	  	                       },
    	   	                             "aTargets": [2]

    	   	                         },{"bSearchable": false, "bVisible": false, "aTargets": [0]}  ],


    	                         "oTableTools": {
    	                             "aButtons": [
    	                         {
    	                             "sExtends": "pdf",
    	                             "mColumns": [0, 1, 2, 3, 4, 5, 6],
    	                             "sPdfOrientation": "landscape",
    	                             "sPdfMessage": "Branch Details"
    	                         },
    	                         {
    	                             "sExtends": "xls",
    	                             "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
    	                         }
    	                      ],
    	                             "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

    	                         }

    	                     });

    	    	            $('#empreview-sample_wrapper .dataTables_filter').html('<div class="input-group">\
    	    	                                              <input class="form-control medium" id="searchInput" type="text">\
    	    	                                              <span class="input-group-btn">\
    	    	                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
    	    	                                              </span>\
    	    	                                              <span class="input-group-btn">\
    	    	                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
    	    	                                              </span>\
    	    	                                          </div>');
    	    	            $('#empreview-sample_processing').css('text-align', 'center');
    	    	            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
    	    	            $('#empreview-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
    	    	            $('#searchInput').on('keyup', function (e) {
    	    	                if (e.keyCode == 13) {
    	    	                    oTable.fnFilter($(this).val());
    	    	                } else if (e.keyCode == 27) {
    	    	                    $(this).parent().parent().find('input').val("");
    	    	                    oTable.fnFilter("");
    	    	                }
    	    	            });
    	    	            $(document).on("click", "#searchFilter", function () {
    	    	                oTable.fnFilter($(this).parent().parent().find('input').val());
    	    	            });
    	    	            $(document).on("click", "#searchClear", function () {
    	    	                $(this).parent().parent().find('input').val("");
    	    	                oTable.fnFilter("");
    	    	            });

    	    	            var nEditing = null;
    	    	            
    	    	            $(document).on('click',"#promotioneditclick",function(e) {
    	    	                e.preventDefault();
    	    	                    $.ajax({
    	    	                        dataType: 'html',
    	    	                        type: "POST",
    	    	                        url: "php/employee.handle.php",
    	    	                        cache: false,
    	    	                        data: $('#promotionEditFrom').serialize(),
    	    	                        /*beforeSend:function(){
    	    	                         	$('#promotioneditclick').button('loading'); 
    	    	                          },
    	    	                          complete:function(){
    	    	                         	 $('#promotioneditclick').button('reset');
    	    	                          },*/	
    	    	     						success: function (data) {
    	    	                            data1 = JSON.parse(data);
    	    	                            if(data1[0]=="success"){
    	        	                        $('.close').click();
    	    	                            oTable.fnDraw();
    	    	                            BootstrapDialog.alert("Employee Promotion Edited Sucessfully");
    	    	                            }else{
    	    	                             	 BootstrapDialog.alert("Can't Performed");
    	    	                                  }
    	    	     						}

    	    	                    });
    	    	            });
    	    	    	    //add Promotion
    	    	            $(document).on('click',"#proSubmit",function(e) {
    	    	                e.preventDefault();
                             var valid;
                             if($('#effectsFrom').val() == ''){
                                 $('.effectsFromerr').show();
                            	$('#effectsFromerr').html('Effects From Field is Required');
                            	 valid = false;
                             }else{
                            	 $('.effectsFromerr').hide();
                            	 $('#effectsFromerr').html('');
								valid = true;
                             }if($('.empChosen').val() == ''){

                            	 $('.employeeNameerr').show();
                             	$('#employeeNameerr').html('Employee Name Field is Required');
                             	 valid = false;
                             }else{
                            	 $('.employeeNameerr').hide();
                            	 $('#employeeNameerr').html('');
								valid = true;
                             }if($('.designChosen').val() == ''){

                            	 $('.designationNameerr').show();
                             	$('#designationNameerr').html('New Designation Name Field is Required');
                             	 valid = false;
                             }else{
                            	 $('.designationNameerr').hide();
                            	 $('#designationNameerr').html('');
								valid = true;
                             }
							if($('#dpromotion').is(':checked')){
								if($('.designselected').val() == ''){

	                            	 $('.designationerr').show();
	                             	$('#designationerr').html('Designation Name Field is Required');
	                             	 valid = false;
	                             }else{
	                            	 $('.designationerr').hide();
	                            	 $('#designationerr').html('');
									valid = true;
	                             }

							}	
                             
                             if($('#effectsFrom').val() == '' && $('.empChosen').val() == '' && $('.designChosen').val() == ''){
                            	 valid = false;
                             }
                             if(valid == true){
    	    	                
    	    	                    $.ajax({
    	    	                        dataType: 'html',
    	    	                        type: "POST",
    	    	                        url: "php/employee.handle.php",
    	    	                        cache: false,
    	    	                        data: $('#promotionForm').serialize(),
    	    	                        /*beforeSend:function(){
    	    	                         	$('#proSubmit').button('loading'); 
    	    	                          },
    	    	                          complete:function(){
    	    	                         	 $('#proSubmit').button('reset');
    	    	                          },*/	
    	    	     						success: function (data) {
    	    	                            data1 = JSON.parse(data);
    	    	                            if(data1[0]=="success"){
    	    	                           var element =  $("#addEmpreview").toggle();
    	    	                           $(element).css('display')=='none'?$('.i_showhide,.p_showhide,.t_showhide').show():$('.i_showhide,.p_showhide,.t_showhide').hide();
    	    	                          //  $('#addEmpreview').toggle('hide');
    	    	                            $('.empChosen,.designChosen,.designselected').val('').trigger('chosen:updated');
    	    	                            $('#promotionForm')[0].reset();
    	    	                            oTable.fnDraw();
    	    	                            var elementLi=$("ul.nav-tabs li.active").find('a');
    	    	                       	 var buttonName=$(elementLi).data('id')+"_showhide";
    	    	                             BootstrapDialog.alert("Employee Promotion Added Sucessfully");
    	    	                            }else{
    	    	                             	 BootstrapDialog.alert("Can't Performed");
    	    	                                  }
    	    	     						}

    	    	                    });
                             }
    	    	            });
//delete Promotion
    	    	            $(document).on('click',".promotion_delete",function(e) {
    	    	                e.preventDefault();
    	    	                var nRow = $(this).parents('tr')[0];
    	                        var data = oTable.fnGetData(nRow);
    	                        var pId = data[0];
    	                        var peffectsFrom=data[2];
    	                        BootstrapDialog.show({
    	          	                title:'Confirmation',
    	                              message: 'Are Sure You Want To Delete this Given Promotion',
    	                              closable: true,
    	                                closeByBackdrop: false,
    	                                closeByKeyboard: false,
    	                             
    	                              buttons: [{
    	                                  label: 'Yes',
    	                                  cssClass: 'btn-sm btn-success',
    	                                   autospin: true,
    	                                  action: function(dialogRef){
    	                                  	 $.ajax({
    	                                  		dataType: 'html',
    	     	    	                        type: "POST",
    	     	    	                        url: "php/employee.handle.php",
    	     	    	                        cache: false,
    	     	    	                        data: { act: '<?php echo base64_encode($_SESSION['company_id']."!delete");?>', promotionId:pId,peffectsFrom:peffectsFrom},
    	               		                   complete:function(){
    	             		                    	 dialogRef.close();
    	             		                      },
    	               		                    success: function (data) {
    	               		                    	data = JSON.parse(data);
    	               		                        if (data[0] == "success") {
    	               		                        	oTable.fnDraw();
    	               		                        	BootstrapDialog.alert('Employee Promotion Deleted Sucessfully');
    	               		                            
    	               		                        }else if (data[0] == "error") {
    	          		                                    alert(data[1]);
    	          		                                }
    	               		                    }
    	               		                });
    	                                          		                            
    	                                  }
    	                              }, {
    	                                  label: 'NO',
    	                                  cssClass: 'btn-sm btn-danger',
    	          	                      action: function(dialogRef){
    	                                      dialogRef.close();
    	                                  }
    	                              }]
    	                          });
    	    	            });
      	    	           //For promotion
    	    	            var design_array = [<?php echo json_encode($designations); ?>];
    	    	            var emp_array = [<?php echo json_encode($employeeOnly); ?>];
    	    	           $(document).on('click',".promotion_edit",function(e) {
    	    	                e.preventDefault();
    	    	                $('.employeeDiv,.designDiv').hide();
    	    	                 var nRow = $(this).parents('tr')[0];
    	    	                 var data = oTable.fnGetData(nRow);
    	    	                 $('#promotionId').val(data[0]);
    	    	                 var parts =data[2].split('-');
    			   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
    			   	             $('#effectsFromval').val(effectFrom);
    			   	             $('#effectsFrom').val(effectFrom);
    			   	          (data[1]=='E')?$('.editepromotion').html('Employee'):$('.editepromotion').html('Designation');
    			   	           (data[1]=='E')?$('.editepromotion').val('E'):$('.editepromotion').val('D');
    			   	           (data[1]=='E')?$('.employeeDiv').show():$('.designDiv').show();
    						if(data[1]=='E'){
    			   	         $('.editempChosenval').val(data[5]);
    			   	         $('.editempChosenval').html(data[5]);
    			   	        var result = emp_array[0].filter(function(v,i) {
    			   	        	if(emp_array[0][i].employee_id===data[5]){
    			   	        		$('.editempChosenval').html(emp_array[0][i].employee_name+" [ "+emp_array[0][i].employee_id +" ] ");
    			   	        	}		   	         
    			   	        });}else{
    			   	           $('.designselectedval').val(data[5]);
    			   	           $('.designselectedval').html(data[5]);
    			   	        var result = design_array[0].filter(function(v,i) {
    			   	        	if(design_array[0][i].designation_id===data[5]){
    			   	        		$('.designselectedval').html(design_array[0][i].designation_name+" [ "+design_array[0][i].designation_id +" ] ");
    			   	        	}		   	         
    			   	        });
    			   	        }
    						    $('#oldpromotedTo,#oldincVal,#oldcalc,#oldincAmount').val('');
    	                        $('#oldpromotedTo').val(data[3]);
    			   	            $("#designChosen option[value='" + data[3] + "']").prop("selected", true);
    		                    $("#designChosen").trigger("chosen:updated");
    		                    var dataStr=data[4].split("|");
    		                    (dataStr[0]=="0")?$('#oldincVal').val(0):$('#oldincVal').val(1);
    		                    (dataStr[0]=="0")?$('#editincNo').click():$('#editincYes').click();
    		                    (dataStr[1]=="A")?$('#oldcalc').val(1):$('#oldcalc').val(0);
    	   					    (dataStr[1]=="A")?$('#elumpsum').click():$('#epercentage').click();
    	   					    $('#oldincAmount').val(dataStr[0]);
    	   					    $('#incAmount').val(dataStr[0]);
    		               });
    	  	               
    	    	       }
    	    	        
    	           };

    	    	} ();
	         
      </script>

</body>
</html>
