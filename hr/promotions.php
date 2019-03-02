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
#slab_opt_chosen, .chosen-container, #_chosen,
	#incbranchChosen12_chosen, #incempChosen12_chosen, #t_incempChosen12,#incempChosen_chosen,
	#incdesignChosen_chosen, #incdepartChosen_chosen,
	#incbranchChosen_chosen, #incbranchChosens_chosen {
	width: 100% !important;
}
h4{
text-align: center;
}
.table{
max-width:100% !important;
width:100% !important;
}
</style>

</head>
<body>
	<section id="container">
		<!--header start-->
         <?php
        
include_once (__DIR__ . "/header.php");
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/designation.class.php");
									$employee = new Employee ();
									$employee->conn = $conn;
									$employees = $employee->selectEmpdesig ();
									$employeeOnly = $employee->select (0);
									
									$designation = new Designation ();
									$designation->conn = $conn;
									$designations = $designation->select ();
									
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/department.class.php");
									$department = new Department ();
									$department->conn = $conn;
									$departments = $department->select ();
									
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/team.class.php");
									$team = new Team ();
									$team->conn = $conn;
									$teams = $team->select ();
									$teamsOnly = $team->selectEmpTeam ();
									
									require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/branch.class.php");
									$branch = new Branch ();
									$branch->conn = $conn;
									$branchsOnly = $branch->select ();
									$branchs = $branch->selectEmpBranch ();
									Session::newInstance ()->_setGeneralPayParams ();
									$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
									Session::newInstance ()->_setMiscPayParams ();
									$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
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

					<header class="panel-heading tab-bg-dark-navy-blue"
						id="evaluate_tabs">
						<ul class="nav nav-tabs  ">
							<li><a href="#promotion" data-id="p" data-loaded="false"
								data-toggle="tab" id="promClick"> Promotion </a></li>
							<li><a href="#increment" data-id="i" data-loaded="false"
								data-toggle="tab" id="incClick"> Increment </a></li>
							<li><a href="#transfer" data-id="t" data-loaded="false"
								data-toggle="tab" id="transferClick"> Transfer </a></li>
							<li><a href="#team_transfer" data-id="tt" data-loaded="false"
								data-toggle="tab" id="teamTransferClick">Team Transfer </a></li>
							<li class="pull-right">
						
						</ul>
					</header>
					<div class="tab-content tasi-tab">
						<div class="tab-pane active" id="promotion">
						<form class="form-horizontal" role="form" method="post" id="promotionFormdata">
							<div class="panel-body">
								<div class="btn-group pull-right">
									<button type="button" class="btn btn-sm btn-info p_showhide"
										style="margin-top: -46%;">
										<i class="fa fa-plus"></i> Promotion
									</button>
								</div>
								<div class="promotionLoader" style="width: 96%; height: 150%"></div>
								<div class="col-lg-12" id="promotionForm" style="display: none">
										<input type="hidden" name="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!promoteByEmployeeId");?>" />
										<div class="col-lg-6">
											<div class="form-group">
												<label class="col-lg-5 col-sm-5 control-label">Effects From</label>
												<div class="col-lg-7 input-group">
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
											<div class="form-group employeeDiv">
												<label class="col-lg-5 col-sm-5 control-label">Employee Name</label>
												<div class="col-lg-7">
													<select class="form-control empChosen" id="empChosen" name="empIds">
														<option value="">Select Employee Name</option>
		                                        <?php
																																												foreach ( $branchs as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ] [ " . $row ['branch_name'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
													<div class="pull-right employeeNameerr"
														style="display: none;">
														<label id="employeeNameerr"></label>
													</div>
												</div>
											</div>
                             	<div class="form-group">
												<label class="col-lg-5 col-sm-5 control-label">New
													Designation Name</label>
												<div class="col-lg-7">
													<select class="form-control designChosen" name="promotedTo">
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
												<label class="col-lg-5 col-sm-5 control-label">Do You Want
													To Perform Inc ?</label>
												<div class="col-lg-7 ">
													<label for="incYes" class="col-lg-5 control-label"> <input
														id="incYes" value="1" name="incVal" type="radio"> Yes
													</label> <label for="incNo" class="col-lg-5 control-label">
														<input id="incNo" value="0" name="incVal" type="radio"
														checked> No
													</label>
												</div>
											</div>
											<div class="pbutton"></div>
											
										</div>
										<div class="col-lg-6" id="promote_salaryBaseText">
										 </div>
										
										</div>
								
								</div>
								<div class="panel-body displayHide" id="manuallyPrmotionwithinc">
								<div class="container" style="width: 75%;">
								<section class="error-wrapper" style="margin-top:0%;text-align:left">
								 	<div class="panel-body" id="promotionComeIncText"></div>
									<div class="form-group">
                                         <div class="col-lg-offset-8 col-lg-4">
												<div class="help-block text-danger" id="errorPro"></div>
												<button type="submit" class="btn btn-sm  btn-success"
													id="proSubmit">Promote</button>
												<button type="button"
													class="btn btn-sm  btn-danger cancel_promo">Cancel</button>
											</div>
										</div>
										</section>
								   </div>
									</div>
								</form>
							<div class="panel-body">
								<div class="space15"></div>
								<div class="adv-table editable-table">
									<section id="flip-scroll">
										<table  class="table table-striped table-hover table-bordered cf"
											id="empreview-sample">
											<thead class="cf">
												<tr>
													<th>ID</th>
													<th>Employee ID</th>
													<th>Affected Employee</th>
													<th>Effective From</th>
													<th>processed On</th>
													<th>Promoted Ids</th>
													<th>Increment Amount</th>
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
						<!-- End Of promotion -->
						<div class="tab-pane" id="increment">
						<form class="form-horizontal" role="form" method="post" id="incrementFormdata">
							<div class="panel-body">
								
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-info i_showhide"
										style="margin-left: 919px;margin-top: -56px;">
										<i class="fa fa-plus"></i> Increment
									</button>
								</div>
								<div class="btn-group pull-right">
								<a href="import.php?for=Increment" target="foo()" title="Increment Import">
									<button type="button" class="btn btn-sm btn-default"
										style="margin-top: -115%;">
										<i class="fa fa-plus"></i> Import
									</button></a>
								</div>
								<div class="incLoader" style="width: 96%; height: 150%"></div>
								<div class="col-lg-12" id="incrementForm" style="display: none">
										<input type="hidden" name="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!incrementByEmployeeId");?>" />
										<input type="hidden" name="incVal" value="1"> <input
											type="hidden" name="proYesNo" value="0"> <input type="hidden"
											name="promotedTo" value="NA">
										<div class="col-lg-6">
											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Effects From</label>
												<div class="col-lg-7 input-group">
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

											<div class="form-group employeeCon">
												<label class="col-lg-3 col-sm-3 control-label">Employee Name</label>
												<div class="col-lg-7">
													<select class="form-control incempChosen" id="incempChosen"
														name="empIds">
														<option value="">Select Employee Name</option>
		                                                 <?php
																																												foreach ( $branchs as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ] [ " . $row ['branch_name'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                             
		                                      </select>
													<div class="pull-right employeeerr1" style="display: none;">
														<label id="employeeerr1"></label>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6" id="incre_salaryBaseText">
										</div>
										</div>
										
							</div>
							<div class="panel-body displayHide" id="manuallyonlyinc">
							<div class="container" style="width: 75%;">
								<section class="error-wrapper" style="margin-top:0%;text-align:left">
								     <div class="panel-body" id="onlyIncText"></div>
											<div class="form-group">
											<div class="col-lg-offset-8 col-lg-4">
												<div class="help-block text-danger" id="errorInc"></div>
												<button type="submit" class="btn btn-sm  btn-success"
													id="incSubmit">Increment</button>
												<button type="button" class="btn btn-sm btn-danger cancel_inc">Cancel</button>
											</div>
										</div>
									</section>
											
										</div>
										</div>
									</form>
								
							<div class="panel-body">
								<div class="iloader" style="width: 81.4%; height: 50%;"></div>

								<div class="space15"></div>
								<div class="adv-table editable-table">
									<section id="flip-scroll">
										<table
											class="table table-striped table-hover table-bordered cf"
											id="increview-sample">
											<thead class="cf">
												<tr>
												<th>ID</th>
													<th>Employee ID</th>
													<th>Affected Employee</th>
													<th>Effective From</th>
													<th>processed On</th>
													<th>Increment Amount</th>
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
						<!-- End of Ince -->
						<div class="tab-pane" id="transfer">
							<div class="panel-body">
								<div class="btn-group pull-right">
									<button type="button" class="btn btn-sm btn-info t_showhide"
										style="margin-top: -46%;">
										<i class="fa fa-plus"></i> Transfer
									</button>
								</div>
								<div class="col-lg-12">
									<form class="form-horizontal" role="form" method="post"
										id="transferForm" style="display: none">
										<input type="hidden" name="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!transfer");?>" />
										<input type="hidden" name="incVal" value="0"> <input
											type="hidden" name="proYesNo" value="1">
											<input type="hidden" id="is_teamTrans" name="is_teamTrans" value="0">
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
													<div class="pull-right effectsFrom2err text-danger"
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
												<label class="col-lg-3 col-sm-3 control-label">Employee Name</label>
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
													<div class="pull-right employee2err text-danger" style="display: none;">
														<label id="employee2err"></label>
													</div>
												</div>
											</div>

											<div class="form-group branchCon">
												<label class="col-lg-3 col-sm-3 control-label">Branch Name</label>
												<div class="col-lg-4">
													<select class="form-control" id="incbranchChosen newBranch"
														name="branchIds">
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
													<div class="pull-right newbranch2err text-danger"
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
							</div>
							<div class="panel-body">
								<div class="tloader" style="width: 81.4%; height: 50%;"></div>
								<div class="space15"></div>
								<div class="adv-table editable-table">
									<section id="flip-scroll">
										<table
											class="table table-striped table-hover table-bordered cf"
											id="tranferreview-sample">
											<thead class="cf">
												<tr>
													<th>ID</th>
													<th>Transfer For</th>
													<th>Effective From</th>
													<th>EmployeeId</th>
													<th>Affected</th>
													<th>Destination(Branch)</th>
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
						<!--End of Transfer -->
						<!-- Team Transfer Starts here -->
						<div class="tab-pane" id="team_transfer">
							<div class="panel-body">
								<div class="btn-group pull-right">
									<button type="button" class="btn btn-sm btn-info tt_showhide"
										style="margin-top: -36%;">
										<i class="fa fa-plus"></i> Team Transfer
									</button>
								</div>
								<div class="col-lg-12">
									<form class="form-horizontal" role="form" method="post"
										id="teamTransferForm"  style="display: none">
										<input type="hidden" name="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!teamTransfer");?>" />
											<input type="hidden" id="is_teamTrans" name="is_teamTrans" value="1">
										<input type="hidden" name="incVal" value="0"> <input
											type="hidden" name="proYesNo" value="1">
										<div class="col-lg-12">

											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Effects From</label>
												<div class="col-lg-4 input-group">
													<span class="input-group-addon" style="cursor: pointer"><i
														class="fa fa-calendar"></i></span>
													<div class="iconic-input right">
														<input class="form-control t_effectsFrom" id="t_effectsFrom2"
															required name="teffectsFrom" type="text">
													</div>
													<div class="pull-right t_effectsFrom2err text-danger"
														style="display: none;">
														<label id="t_effectsFrom2err"></label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Transfer For</label>
												<div class="col-lg-7 ">
													<label for="etransfer" class="col-lg-3 control-label"> <input
														id="etransfer" value="E" name="transferedFor" type="radio"
														checked> Employee
													</label> <label for="ttransfer"
														class="col-lg-3 control-label"> <input id="ttransfer"
														value="T" name="transferedFor" type="radio"> Team
													</label>
												</div>
											</div>


											<div class="form-group employeeCon">
												<label class="col-lg-3 col-sm-3 control-label">Employee Name</label>
												<div class="col-lg-4">
													<select class="form-control" id="t_incempChosen12"
														name="employee_Ids">
														<option value="">Select Employee Name</option>
		                                                   <?php
																																												foreach ( $teamsOnly as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ] [ " . $row ['team_name'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                             
		                                      </select>
													<div class="pull-right t_employee2err text-danger" style="display: none;">
														<label id="t_employee2err"></label>
													</div>
												</div>
											</div>

											<div class="form-group teamCon">
												<label class="col-lg-3 col-sm-3 control-label">Team Name</label>
												<div class="col-lg-4">
													<select class="form-control" id="incteamChosen newTeam"
														name="teamIds">
														<option value="">Select Team Name</option>
		                                          <?php
																																												foreach ( $teams as $row ) {
																																													echo "<option value='" . $row ['team_id'] . "'>" . $row ['team_name'] . " [ " . $row ['team_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
													<div class="pull-right team2err" style="display: none;">
														<label id="team2err"></label>
													</div>
												</div>
											</div>

										   	<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">New Team Name</label>
												<div class="col-lg-4">
													<select class="form-control" id="incteamChosen12"
														name="transferedTo">
														<option value="">Select Team Name</option>
		                                          <?php
																																												foreach ( $teams as $row ) {
																																													echo "<option value='" . $row ['team_id'] . "'>" . $row ['team_name'] . " [ " . $row ['team_id'] . " ] <br>" . "</option>";
																																												}
																																												?>
		                                             
		                                       </select>
													<div class="pull-right newteam2err text-danger"
														style="display: none;">
														<label id="newteam2err"></label>
													</div>
												</div>
											</div>
											
										<div class="form-group">
											<label class="col-lg-3 col-sm-3 control-label">New Reporting Person</label>
											<div class="col-lg-4">
												<input type="hidden" id="rep_man-id" name="rep_man-id"> <input
													type="text" class="form-control" id="rep_man"
													name="rep_man" autocomplete="off" maxlength="50" required/> <span
													class="help-block"><span class="pull-right empty-message"
													style="display: none">No Records Found</span></span>
												<div class="pull-right reporting_man text-danger"
														style="display: none;">
														<label id="reporting_man"></label>
													</div>
											</div>
										</div>

											<br>
											<div class="form-group">
												<div class="col-lg-offset-4 col-lg-4">
													<button type="submit" class="btn btn-sm btn-success"
														id="teamTranferSubmit">Transfer</button>
													<button type="button"
														class="btn btn-sm btn-danger cancel_ttrans">Cancel</button>
												</div>
											</div>

										</div>
									</form>
								</div>
							</div>
							<div class="panel-body">
								<div class="tloader" style="width: 81.4%; height: 50%;"></div>
								<div class="space15"></div>
								<div class="adv-table editable-table">
									<section id="flip-scroll">
										<table
											class="table table-striped table-hover table-bordered cf"
											id="teamTrans-sample">
											<thead class="cf">
												<tr>
													<th>ID</th>
													<th>Transfer For</th>
													<th>Effective From</th>
													<th>EmployeeId</th>
													<th>Affected</th>
													<th>Destination(Team)</th>
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
						<!-- End of Team Transfer -->
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
            $('#incrementFormdata,#promotionFormdata,#teamTransferForm')[0].reset();
        	 $("#incempChosen option[value=''],.empChosen option[value=''],.designChosen option[value='']").prop("selected", true).trigger('chosen:updated');
              $(function(){
        	      	// Javascript to enable link to tab
        	     $('.oldsalaryprocomeInc,.oldsalaryonlyInc,#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
	   			  var url = document.location.toString();
        	      	  var tab = "";
        	      	  if (url.match('#')) {
        	      		  if(url.split('#')[1].indexOf("?")> 0){
        	      	        	tab = url.split('#')[1].substring(0,url.split('#')[1].indexOf("?"));
        	      	        }else{
        	      	        	tab = url.split('#')[1];
        	      	        }
        	      	      $('#evaluate_tabs a[href="#' + tab + '"]').tab('show');
        	      	      var sum = 0;
        	      	  }else{
        	      		  $('#evaluate_tabs a[href="#promotion"]').tab('show');
        	          } 

        	      	  // Change hash for page-reload
        	      	  $('#evaluate_tabs a').on('shown.bs.tab', function (e) {
        	      	      window.location.hash = e.target.hash;
        	      	  })
        	      	});
            incFlag=0;tfFlag=0;
        	$('#designChosen,.empChosen,.designChosen,.designselected,.incdesignDiv,#incempChosen,#incempChosen12,#incdesignChosen,#incdepartChosen,#incbranchChosen,#incbranchChosens,#incbranchChosen12,#incteamChosen12,#t_incempChosen12').chosen();
            $('.incDiv,.lSpan,.designDiv,.designCon,.departCon,.branchCon').hide();

            $('.effectsFrom,.t_effectsFrom').datetimepicker({
             	  format: 'DD/MM/YYYY',
              });

            $('.employeeCon').show();
            $('.teamCon').hide();
         
            $('.incemployeeDiv').html($('employeeDiv').html());
              });
        $('.p_showhide').on('click', function () {
            //var elem=$(this).attr('class').split(' ')[2];
           // $('.'+elem).hide();
           // $('.empChosen,.designChosen,.designselected').chosen();
           
            $('#promotionForm').toggle('show');
              $('#transferForm,#incrementForm,#teamTransferForm').hide();
             //$(element).css('display')=='none'?$('.i_showhide,.p_showhide,.t_showhide').show():$('.i_showhide,.p_showhide,.t_showhide').hide();
         });
       $('.i_showhide').on('click', function () {
    	   $('#incrementForm').toggle('show');
    	   $('#promotionForm,#teamTransferForm,#transferForm').hide();
       });
       $('.t_showhide').on('click', function () {
    	   $('#transferForm').toggle('show');
    	   $('#promotionForm,#incrementForm,#teamTransferForm').hide();
      });
       $('.tt_showhide').on('click', function () {
    	   $('#teamTransferForm').toggle('show');
    	   $('#promotionForm,#incrementForm,#transferForm').hide();
      });

       $('.cancel_promo').on('click', function () {
       $('#promotionForm').toggle('hide');
       $('.oldsalaryprocomeInc,.oldsalaryonlyInc,#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
	   $('#manuallyPrmotionwithinc,#well,.incDiv').hide();
       $('#promotionFormdata')[0].reset();
       $(".empChosen option[value=''],.designChosen option[value='']").prop("selected", true).trigger('chosen:updated');
       $('#errorPro').html('');
       });
        
        $('.cancel_inc').on('click', function () {
        	$('#incrementForm').toggle('hide');
        	$('.oldsalaryprocomeInc,.oldsalaryonlyInc,#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
	   		$('#manuallyonlyinc,#well,.incDiv').hide();
             $('#incrementFormdata')[0].reset();
             $("#incempChosen option[value=''],#incdesignChosen option[value=''],#incdepartChosen option[value=''],#incbranchChosen option[value='']").prop("selected", true).trigger('chosen:updated');
       });
        
        $('.cancel_trans').on('click', function () {
        	$('#transferForm').toggle('hide');
        });
        
        $('.cancel_ttrans').on('click', function () {
        	$('#teamTransferForm').toggle('hide');
        	 $('#teamTransferForm')[0].reset();
        	 $('#t_incempChosen12,#incteamChosen12').val('').trigger('chosen:updated');
       
        });
        $('#incYes').on('click', function () {
            $('.incDiv').show();
            $('.pbutton').hide();
         });
        $('#incNo').on('click', function () {
            $('.incDiv').hide();
           $('.pbutton').html( $('<div class="col-lg-offset-8 col-lg-4"><div class="help-block text-danger" id="errorPro"></div><button type="submit" class="btn btn-sm  btn-success" id="proSubmit">Promote</button>   <button type="button" class="btn btn-sm  btn-danger cancel_promo">Cancel</button></div>'));
           $('.pbutton').show();
            });
      
        $(document).on('change', "input[name='promotionFor']", function (e) {
      	  $(".employeeCon,.designCon,.departCon,.branchCon").hide();
      	  var selectedOpt = $(this).val()=='E'?'employeeCon':$(this).val()=='D'?'designCon':$(this).val()=='F'?'departCon':'branchCon';
      	  $('.'+selectedOpt).show();
        });
        
        $(document).on('change', "input[name='transferedFor']", function (e) {
        	  $(".employeeCon,.teamCon").hide();
        	 
        	  var selectedOpt = $(this).val()=='E'?'employeeCon':'teamCon';
        	  $('.'+selectedOpt).show();
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
                       
                        .append("<a>" + item.employee_name + " " + item.employee_lastname + " [" + item.employee_id + "] <br>" + [(item.employee_designation =='')?" ":item.employee_designation+", " ] + [(item.employee_department =='')?" ":item.employee_department+", "] + [(item.employee_branch =='')?" ":item.employee_branch] + "</a>")
                        .appendTo(ul);
                    };
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
                                 { "sName": "days_count" , "bSortable": false},
                                 { "sName": "days_count", "bSortable": false },
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
    	                    },{ "mRender": function 
	    	   	                        (data, type, row) {
    	                    	  var letterName='Evaluation:Transfer';
	    	   	                       var currentDate='<?php echo $_SESSION ['current_payroll_month'];?>';
	    	   	                       if(row[2]>=currentDate){
	    	   	                    	 return '<a href="#" title="Delete" class="transfer_delete" data-toggle="modal"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a> <form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+ '"><input type="hidden" name="actionId" id="actionId" value=' +row[0]+'><input type="hidden" name="letterName" id="letterName" value="' +letterName+ '"> <a  title="Download Transfer" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
	    	   	                       }else{
	    	   	                       return '<form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+ '"><input type="hidden" name="actionId" id="actionId" value=' +row[0]+'><input type="hidden" name="letterName" id="letterName" value="'+letterName+'"> <a  title="Download Transfer" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
		    	   	                  }
 	   	                         
     				    	      },
     	   	                             "aTargets": [6]

     	   	                     },{"bSearchable": false, "bVisible": false, "aTargets": [0]}  ], 


                         "oTableTools": {
                             "aButtons": [
                         {
                             "sExtends": "pdf",
                             "mColumns": [0, 1, 2, 3, 4, 5, 6, 7],
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
                              message: 'Are sure you want to delete this given Transfer ?',
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
                                  label: 'No',
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
								$('#effectsFrom2err').html('Effects From Field is Required');
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
      	                             $('#incempChosen,#incbranchChosen,#t_incempChosen12,#incteamChosen12').trigger('chosen:updated');
      	                          transferTable.fnDraw();
      	                        var elementLi=$("ul.nav-tabs li.active").find('a');
         	                 	 var buttonName=$(elementLi).data('id')+"_showhide";
    	                             BootstrapDialog.alert("Employee Transferred Successfully");
    	                             $('#transferForm').toggle('hide');
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
				   	        		($('.trbranchChosenval').html(branch_array[0][i].branch_name+" [ "+branch_array[0][i].branch_id +" ] "));
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
    
    	     	//for team transfer dataTable
var tmEditableTable = function () {
    	    return {
    	    	init: function () {
                //main function to initiate the module
    	      var teamTmTable = $('#teamTrans-sample').dataTable({
                         "aLengthMenu": [
                             [5, 15, 20, -1],
                             [5, 15, 20, "All"] // change per page values here
                         ],

                         // set the initial value
                         "iDisplayLength": 5,
                         "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                         "bProcessing": true,
                         "bServerSide": true,
                         "sAjaxSource": "php/teamTransfers-view.php",
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
                                 { "sName": "days_count" , "bSortable": false},
                                 { "sName": "days_count", "bSortable": false },
                                 { "sName": "enabled", "bSortable": false },
         				   ],"aoColumnDefs": [{ "mRender": function 
   	                        (data, type, row) {
      	                       
          					  if(data=='E'){
   	           					  return "Employee";
   	           					  }else{
   	           						 return "Team";	
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
    	                    },/* {"mRender": function 
	    	   	                        (data, type, row) {
    	                    	  var letterName='Team Transfer Letter';
	    	   	                       var currentDate='<?php echo $_SESSION ['current_payroll_month'];?>';
	    	   	                       if(row[2]>=currentDate){
	    	   	                    	 //return '<a href="#" title="Delete" class="t_transfer_delete" data-toggle="modal"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a> <form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+ '"><input type="hidden" name="actionId" id="actionId" value=' +row[0]+'><input type="hidden" name="letterName" id="letterName" value="' +letterName+ '"> <a  title="Download Transfer" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
	    	   	                    	// return '<a href="#" title="Delete" class="t_transfer_delete" data-toggle="modal"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a> <form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+ '"><input type="hidden" name="actionId" id="actionId" value=' +row[0]+'><input type="hidden" name="letterName" id="letterName" value="' +letterName+ '"> <a  title="Download Transfer" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
		    	   	                   }else{
	    	   	                      // return '<form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+ '"><input type="hidden" name="actionId" id="actionId" value=' +row[0]+'><input type="hidden" name="letterName" id="letterName" value="'+letterName+'"> <a  title="Download Transfer" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
		    	   	                  }
 	   	                         
     				    	      },
     	   	                             "aTargets": [5]

     	   	                     },*/{"bSearchable": false, "bVisible": false, "aTargets": [0]}  ], 


                         "oTableTools": {
                             "aButtons": [
                         {
                             "sExtends": "pdf",
                             "mColumns": [0, 1, 2, 3, 4, 5, 6],
                             "sPdfOrientation": "landscape",
                             "sPdfMessage": "Team Details"
                         },
                         
                         {
                             "sExtends": "xls",
                             "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
                         }
                      ],
                             "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                         }

                     });//dataTable end

    	            $('#teamTrans-sample_wrapper .dataTables_filter').html('<div class="input-group">\
    	                                              <input class="form-control medium" id="searchInput" type="text">\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="teamtranfersearchFilter" type="button">Search</button>\
    	                                              </span>\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="t_tranfersearchClear" type="button">Clear</button>\
    	                                              </span>\
    	                                          </div>');
    	            $('#teamTrans-sample_processing').css('text-align', 'center');
    	            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
    	            $('#teamTrans-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
    	            $('#searchInput').on('keyup', function (e) {
    	                if (e.keyCode == 13) {
    	                	teamTmTable.fnFilter($(this).val());
    	                } else if (e.keyCode == 27) {
    	                    $(this).parent().parent().find('input').val("");
    	                   teamTmTable.fnFilter("");
    	                }
    	            });

    	            $(document).on("click", "#teamtranfersearchFilter", function () {
    	            	teamTmTable.fnFilter($(this).parent().parent().find('input').val());
    	            });
    	            $(document).on("click", "#t_tranfersearchClear", function () {
    	                $(this).parent().parent().find('input').val("");
    	               teamTmTable.fnFilter("");
    	            });
    	            
					//teamTransfer delete
    	        /*    $(document).on('click',".t_transfer_delete",function(e) {
    	                e.preventDefault();
    	                var nRow = $(this).parents('tr')[0];
                        var data = teamTmTable.fnGetData(nRow);
                        console.log(data);
                        var pId = data[0];
                        var peffectsFrom=data[2];
                        BootstrapDialog.show({
          	                title:'Confirmation',
                              message: ' Are sure you want to delete this given Transfer?',
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
     	    	                        data: { act: '<?php echo base64_encode($_SESSION['company_id']."!teamTransferdelete");?>',promotionFor: data[1],promotionId:pId,peffectsFrom:peffectsFrom,empIds:data[3]},
               		                   complete:function(){
             		                    	 dialogRef.close();
             		                      },
               		                    success: function (data) {
               		                    	data = JSON.parse(data);
               		                        if (data[0] == "success") {
               		                        	teamTmTable.fnDraw();
               		                        	BootstrapDialog.alert('Employee Team Transfer Deleted Successfully');
               		                            
               		                        }else if (data[0] == "error") {
          		                                    alert(data[1]);
          		                                }
               		                    }
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
    	            });*/

    	          //team transfer submit 
    				$(document).on('click',"#teamTranferSubmit",function(e) {
        	              e.preventDefault();
        	          	var valid;
						if($('#t_effectsFrom2').val() == '' ){
							$('.t_effectsFrom2err').show();
							$('#t_effectsFrom2err').html('Effects From Field is Required');
							valid = false;
						}else{
							$('.t_effectsFrom2err').hide();
							$('#t_effectsFrom2err').html('');
							valid = true;
						}
						
						if($('#t_incempChosen12').val() == ''){
						
							$('.t_employee2err').show();
							$('#t_employee2err').html('Employee Name is Required');
							valid = false;
						}else{
							$('.t_employee2err').hide();
							$('#t_employee2err').html('');
							valid = true;
						}if($('#incteamChosen12').val() == ''){
							$('.newteam2err').show();
							$('#newteam2err').html('New Team Name is Required');
							valid = false;
						}else{
							$('.newteam2err').hide();
							$('#newteam2err').html('');
							valid = true;
						}
						if($('#ttransfer').is(':checked')){
							if($('#newTeam').val() == ''){

                            	 $('.team2err').show();
                             	$('#team2err').html('Team Name Field is Required');
                             	 valid = false;
                             }else{
                            	 $('.team2err').hide();
                            	 $('#team2err').html('');
								valid = true;
                             }
						}
						if($('#rep_man').val() == ''){
							$('.reporting_man').show();
							$('#reporting_man').html('Reporting person is Required');
							valid = false;
						}else{
							$('.reporting_man').hide();
							$('#reporting_man').html('');
							valid = true;
						}
						
			if($('#t_effectsFrom2').val() == ''  && $('#t_incempChosen12').val() == '' && $('#incteamChosen12').val() == ''){
							valid = false;
						}
				if(valid == true){
        	              
        	              $.ajax({
    	                         dataType: 'html',
    	                         type: "POST",
    	                         url: "php/employee.handle.php",
    	                         cache: false,
    	                         data: $('#teamTransferForm').serialize(),
    	                         success: function (data) {
    	                        	data1 = JSON.parse(data);
    	                             if(data1[0]=="success"){
     	                          $('#teamTransferForm')[0].reset();
     	                          $('#teamTransferForm').toggle('hide');
     	                          $('#addEmpreview').toggle('hide');
     	                          $('#t_incempChosen12,#incteamChosen12').val('').trigger('chosen:updated');
    	                         teamTmTable.fnDraw();
    	                          var elementLi=$("ul.nav-tabs li.active").find('a');
      	                 	       var buttonName=$(elementLi).data('id')+"_showhide";
    	                             BootstrapDialog.alert("Employee Transferred Successfully");
    	                             $('#transferForm').toggle('hide');
    	                             }else{
    	                              	 BootstrapDialog.alert("Can't Performed");
    	                               }
    	                               }

    	                     });
					}
    			});
    	            
    	      }//init function end
    	    }
    	  }();//tfeditable table

    	     	

    	 
        
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
                                 {"sName": "rule_name"},
                                 { "sName": "frequency" , "bSortable": false},
                                 { "sName": "days_count" , "bSortable": false},
                                 { "sName": "carry_forward", "bSortable": false },
                                 { "sName": "carry_forward" , "bSortable": false},
                                { "sName": "enabled", "bSortable": false },
         				   ],"aoColumnDefs": [{ "mRender": function 
 		     	                        (data, type, row) {
     	                           	 var dataStr=row[5].split("|");
 		         					  if(row[3]=="0|A"){
 			                           return "--";
 			                         }else if(dataStr[1]=='A'){
 			                        	 return " <i class='fa fa-rupee'></i> "+dataStr[0];
 			                         }else if(dataStr[1]=='P'){
 			                        	 return dataStr[0]+" %";
 			                         }} ,
 			                             "aTargets": [5]

 			                         },{ "mRender": function 
    	                        (data, type, row) {
 			    	         var parts =row[3].split('-');
 			   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
 			   	             return effectFrom;
   	                       },
    	                             "aTargets": [3]

    	                         },{ "mRender": function 
	    	   	                        (data, type, row) {
    	                        	  var letterName=(row[4]=='0|A')?'Evaluation:Increment':'';
	    	   	                       var currentDate='<?php echo $_SESSION ['current_payroll_month'];?>';
	    	   	                       if(row[3]>=currentDate){
	 	    	   	                     return '<a href="#" title="Delete" class="inc_delete" data-toggle="modal"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a><form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"><input type="hidden" name="actionId" id="actionId" value=' +row[0]+ '><input type="hidden" name="letterName" id="letterName" value="Evaluation:Increment"> <a  title="Download Increment Letter" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
	    	   	                      }else if (row[7]==1){
	    	   	                         return '<a href="#" title="Delete" class="inc_delete" data-toggle="modal"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a><form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"><input type="hidden" name="actionId" id="actionId" value=' +row[0]+ '><input type="hidden" name="letterName" id="letterName" value="Evaluation:Increment"> <a  title="Download Increment Letter" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
		    	   	                   }else{
	    	   	                    	  return '<form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"><input type="hidden" name="actionId" id="actionId" value='  +row[0]+ '><input type="hidden" name="letterName" id="letterName" value="Evaluation:Increment"> <a  title="Download Increment Letter" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
	  	    	   	                  }
   	   	                         
       				    	      },
       	   	                             "aTargets": [6]

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
    			    if($('#effectsFrom1').val() != ''  && $('#incempChosen').val() != ''){
    			     $('#errorInc').html('');
    			      var incPercentage=((Number($('#newCtc').val())-Number($('#oldCtc').val()))/ Number($('#oldCtc').val()))/100;
                     if(incPercentage>0 && incPercentage!='Infinity'){
                 	  	  $('.oldsalaryonlyInc').find("input").each(function () {
                            $(this).remove();
                        });
                        $('#incrementFormdata').find('input[type="text"]').each(function () {
                            if ($(this).attr('data-type') == 'rupee') {
                               $(this).val(deFormate($(this).val()));
                                }
                         }); 
                        $('#errorInc').html('');
                            $.ajax({
    	                        dataType: 'html',
    	                        type: "POST",
    	                        url: "php/employee.handle.php",
    	                        cache: false,
    	                        data: $('#incrementFormdata').serialize(),
    	                        beforeSend:function(){$(".incLoader").loading(true);},
    	  		                success: function (data) {
    	                            data1 = JSON.parse(data);
    	                           if(data1[0]=="success"){
    	                           incoTable.fnDraw();
    	                           $('#incrementForm').toggle('hide');
    	                           $('.oldsalaryprocomeInc,.oldsalaryonlyInc,#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
       		      	   		       $('#manuallyonlyinc,#well,.incDiv').hide();
    	                           $('#incrementFormdata')[0].reset();
    	                           $('#newCtc,#oldCtc').val(0);
    	                           $("#incempChosen option[value=''],#incdesignChosen option[value=''],#incdepartChosen option[value=''],#incbranchChosen option[value='']").prop("selected", true).trigger('chosen:updated');
    	                           BootstrapDialog.alert("Employee SalaryIncremented Successfully");
    	                              }else{
    	                             	 BootstrapDialog.alert("Can't Performed");
    	                              }
    	                            $(".incLoader").loading(false);
    	                            }
                            });
                   	 }else{
                   		$('#errorInc').html('No more Changes or Check Data Are Valid');
                       	 }
                   	 }else{
                   		//$('#errorInc').html('Enter Required Fields');
                       	 }
    	         });
    	           
    	           //delete Incremnet
	    	            $(document).on('click',".inc_delete",function(e) {
	    	                e.preventDefault();
	    	                var nRow = $(this).parents('tr')[0];
	                        var data = incoTable.fnGetData(nRow);
	                     
	                        var pId = data[0];
	                        var peffectsFrom=data[3];
	                         BootstrapDialog.show({
	          	                title:'Confirmation',
	                              message: 'Are Sure You Want To Delete This Given Increment',
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
	               		                        	BootstrapDialog.alert("Employee SalaryIncrement Deleted Successfully");
	               		                            
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
    	                                 {"sName": "rule_name"},
    	                                 { "sName": "frequency", "bSortable": false },
    	                                 { "sName": "days_count" , "bSortable": false},
    	                                 { "sName": "carry_forward", "bSortable": false },
    	                                 { "sName": "carry_forward", "bSortable": false },
    	                                 { "sName": "carry_forward" ,"bSortable": false },
    	                                { "sName": "enabled", "bSortable": false },
    	         				   ],"aoColumnDefs": [{ "mRender": function 
    		     	                        (data, type, row) {
    		         					  if(row[5]=="NA"){
    			                           return "--";
    			                         }else{
    			                        	 return data;
    			                         }} ,
    			                             "aTargets": [5]

    			                         },{ "mRender": function 
    			     	                        (data, type, row) {
 			     	                           var dataStr=row[6].split("|");
    			         					  if(row[4]=="0|A"){
    				                           return "--";
    				                         }else if(dataStr[1]=='A'){
    				                        	 return " <i class='fa fa-rupee'></i> "+dataStr[0];
    				                         }else if(dataStr[1]=='P'){
    				                        	 return dataStr[0]+" %";
    				                         }} ,
    				                             "aTargets": [6]
                                       },{ "mRender": function 
    	   	                        (data, type, row) {
    				    	         var parts =row[3].split('-');
    				   	             var effectFrom=parts[2]+"/"+parts[1]+"/"+parts[0];
    				   	             return effectFrom;
    	  	                       },
    	   	                             "aTargets": [3]

    	   	                         },{ "mRender": function 
    	    	   	                        (data, type, row) {
 	    	   	                      var letterName=(row[6]=='0|A')?'Evaluation:Promotion':'Evaluation:Promotion come Increment';
 	    	   	                      var currentDate='<?php echo $_SESSION ['current_payroll_month'];?>';
 	    	   	                      if(row[3]>=currentDate ){
 	 	    	   	                     return '<a href="#promotion_delete" title="Delete" class="promotion_delete" data-toggle="modal"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a> <form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"><input type="hidden" name="actionId" id="actionId" value='+row[0]+ '><input type="hidden" name="letterName" id="letterName" value="' +letterName+ '"> <a  title="Download Promotion letter" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
 	    	   	                      }else if (row[8]==1){
 	    	   	                    	 return '<a href="#promotion_delete" title="Delete" class="promotion_delete" data-toggle="modal"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a> <form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"><input type="hidden" name="actionId" id="actionId" value='+row[0]+ '><input type="hidden" name="letterName" id="letterName" value="' +letterName+ '"> <a  title="Download Promotion letter" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
 	 	    	   	                  }else{
 	    	   	                    	 return '<form method="post" action="php/letter.handle.php" id="transfer_download" style="display:inline"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"><input type="hidden" name="actionId" id="actionId" value='+row[0]+ '><input type="hidden" name="letterName" id="letterName" value="' +letterName+ '"> <a  title="Download Promotion letter" ><button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a></form>';
 	 	    	   	                  }
    	   	                          },
    	   	                          
        	   	                      "aTargets": [7]

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
    	    	            //add Promotion
    	    	            $(document).on('click',"#proSubmit",function(e) {
    	    	             e.preventDefault();
                             if($('#effectsFrom').val() != '' && $('.empChosen').val()!='' &&  $('.designChosen').val() != ''){
                            	 $('#errorPro').html('');
                            	 var incPercentage=((Number($('#newCtc').val())-Number($('#oldCtc').val()))/Number($('#oldCtc').val()))/100;
                               if((incPercentage>0 && incPercentage!='Infinity') || $('input[type=radio][name=incVal]:checked').val()==0){
                           		 $('.oldsalaryprocomeInc').find("input").each(function () {
                                     $(this).remove();
                                 });
                                 $('#promotionFormdata').find('input[type="text"]').each(function () {
                                     if ($(this).attr('data-type') == 'rupee') {
                                        $(this).val(deFormate($(this).val()));
                                         }
                                  }); 
                                   $('#errorPro').html('');
                                   $.ajax({
    	    	                        dataType: 'html',
    	    	                        type: "POST",
    	    	                        url: "php/employee.handle.php",
    	    	                        cache: false,
    	    	                        data: $('#promotionFormdata').serialize(),
    	    	                        beforeSend:function(){
    	    	                         	$('#proSubmit').button('loading'); 
    	    	                         	 $(".promotionLoader").loading(true);
    	    	                          },
    	    	                           success: function (data) {
    	    	                            data1 = JSON.parse(data);
    	    	                            if(data1[0]=="success"){
    	    	                            oTable.fnDraw();
               		                        $('#promotionForm').toggle('hide');
               		                        $('.oldsalaryprocomeInc,.oldsalaryonlyInc,#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
               		      	   		        $('#manuallyPrmotionwithinc,#manuallyonlyinc,#well,.incDiv').hide();
    	    	                            $('#promotionFormdata')[0].reset();
    	    	                            $('#newCtc,#oldCtc').val(0);
    	    	                            $(".empChosen option[value=''],.designChosen option[value='']").prop("selected", true).trigger('chosen:updated');
    	    	                            BootstrapDialog.alert("Employee Promotion Added Successfully");
    	    	                            }else{
    	    	                             	 BootstrapDialog.alert("Can't Performed");
    	    	                            }
    	    	                            $(".promotionLoader").loading(false);
      	    	                            $('#proSubmit').button('reset');
    	    	     						}
    	    	                     });
    	    	                    
                       	 }else{
                       		$('#errorPro').html('No more Changes or Check Data Are Valid');
                           	 }
                             }else{
                                 //$('#errorPro').html('Enter Required Fields');
                                 $("input[type=radio][name=incVal]").prop("checked", false);
                                 }
    	    	            });
//delete Promotion
    	    	            $(document).on('click',".promotion_delete",function(e) {
    	    	                e.preventDefault();
    	    	                var nRow = $(this).parents('tr')[0];
    	                        var data = oTable.fnGetData(nRow);
    	                        var pId = data[0];
    	                        var peffectsFrom=data[3];
    	                        BootstrapDialog.show({
    	          	                title:'Confirmation',
    	                              message: 'Are Sure You Want To Delete This Given Promotion',
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
    	               		                        	BootstrapDialog.alert('Employee Promotion Deleted Successfully');
    	               		                            
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
      	    	         
    	  	               
    	    	       }
    	    	        
    	           };

    	    	} ();

    	    	$(document).on('change', "input[type=radio][name=salary_type],input[type=radio][name=salary_based_on]", function () {
    	      	 	var salaryType=$("input[type=radio][name=salary_type]:checked").val();
    	      	     var slabType=$("input[type=radio][name=salary_based_on]:checked").val();
    	      	     $('#getCTCcontent').html('');
    	      	     $('#slabloader').loading(true);
    	       	     if (slabType== 'basic') {
    	               loadSlabOptions('basic', function (result) {
    	            	dataFill(salaryType,slabType);
    	              });
    	                $('#slab_select_box').show();
    	              }  else if (slabType == 'gross') {
    	            	  loadSlabOptions('gross', function (result) {
    	            		  dataFill(salaryType,slabType);
    	            	 });
    	            	
    	               $('#slab_select_box').show();
    	               } else if (slabType == 'noslab') {
    	            	   dataFill(salaryType,slabType);
    	                   $('#slab_select_box').hide();
    	               }
    	       	      $('#slabloader').loading(false);
    	      	  });

    	         function dataFill(salaryType,slabType){
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
    	      	   }
    	    	   
    	       $(document).on('click', "#noSlabCaulation", function (e) {
    	           e.preventDefault();
    	           var parameters = {}; //declare the object
    	           parameters["act"] = '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>'; //set some value
    	           parameters["pfLimit"] = ($("input[type=checkbox][name=pfLimit]:checked").val()==1)?1:0;
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
    	              	 $('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable table-bordered"><thead><tr role="row"><th colspan="3" style="text-align:center">New Salary</th></tr><tr role="row"><th style="text-align:center">Rate</th><th colspan="1" rowspan="1" style="text-align:center">Monthly</th><th style="text-align:center">Yearly</th></tr></thead>'+
	                          	  setData(json_obj[2]));
	    	              	 if($('#isSlab').val()!=1){
	    	                 $('#isSlab').val(1);
	    	      	         $('.oldsalaryprocomeInc,.oldsalaryonlyInc').html('<div class="table-responsive"><table class="table ctcDesigntable table-bordered"><thead><tr role="row"><th rowspan="2" style="padding: 22px;">Components</th><th colspan="3" style="text-align:center">Old Salary</th></tr><tr role="row"><th style="text-align:center">Rate</th><th colspan="1" rowspan="1" style="text-align:center">Monthly</th><th style="text-align:center">Yearly</th></tr></thead>'+
		                          	  setData(json_obj[2]));
	    	              	 }
	    	              	 if(Number($('#oldCtc').val())==0){
    	        	         $('#oldCtc').val(json_obj[2].gross);
  	              	         }
	    	              $('#newCtc').val(json_obj[2].gross);
    	              	  $('#loader').loading(false);
    	              	  $('#noSlabCaulation').button('reset');
    	              	  $('#incThenPromote').show();
    	              	$('#getTablecontent').find('td.emptyDiv,th.emptyDiv').each(function () {
    	                     $(this).remove();
    	                    }); 
    	               	}
    	          });
    	       	}
    	      }); 

    	       $(document).on('click', "#ctcSalaryCalc", function (e) {
    	           e.preventDefault();
    	            var ctc=deFormate($('#ctc').val());
    	           var gross=deFormate($('#gross').val());
    	           var basic=deFormate($('#basic').val());
    	           var slabId=$('#slab_opt :selected').val();
    	           var pfLimit=($("input[type=checkbox][name=pfLimit]:checked").val()==1)?1:0;
    	           var isCTC=($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
    	           if(slabId){
    	          	falg=(validateForSalary()==true)?1:0;
    	          	if(falg==1){
    	            $('#error-text').html('');
    	            $.ajax({
    	                dataType: 'html',
    	                type: "POST",	
    	                url: "php/employee.handle.php",
    	                cache: false,
    	                data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>',
    	                       ctc:ctc,gross:gross,basic:basic,slabId:slabId,pfLimit:pfLimit,isCTC:isCTC},
    	                beforeSend:function(){$('#loader').loading(true);$('#ctcSalaryCalc').button('loading');},
    	                success: function (data) {
    	               	 var json_obj = $.parseJSON(data); //parse JSON
    	               	$('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable table-bordered"><thead><tr role="row"><th colspan="3" style="text-align:center">New Salary</th></tr><tr role="row"><th style="text-align:center">Rate</th><th colspan="1" rowspan="1" style="text-align:center">Monthly</th><th style="text-align:center">Yearly</th></tr></thead>'+
	                          	  setData(json_obj[2]));
    	               	$('#newCtc').val(json_obj[2].gross);
    	               	$('#loader').loading(false);
    	               	$('#ctcSalaryCalc').button('reset');
    	               	$('#incThenPromote').show();
    	               	$('#getTablecontent').find('td.emptyDiv,th.emptyDiv').each(function () {
   	                     $(this).remove();
   	                    }); 
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
    	     
    	    	

    	      function salaryEditTable(data){
    	    	   $.each(data, function (k, v) {                
  	    	           $('#' + k).val(v);
  	    	         });
	    	    (data.slab_id == "Nil")?$("#noSlabCaulation").click():''; 
  	    	   $('#ctcSalaryCalc').data('id',data.slab_id);
  	    	 }

     	     $('#evaluate_tabs').on('shown.bs.tab', function (e) {
         	       // newly activated tab
    	      	    window.scrollTo(0, 0);
    	    	 	   $('.oldsalaryprocomeInc,.oldsalaryonlyInc,#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
    	        	   $('#manuallyPrmotionwithinc,#manuallyonlyinc,#well,.incDiv').hide();
    	               $("#promotionFormdata,#incrementFormdata,#incempChosen option[value=''],#incdesignChosen option[value=''],#incdepartChosen option[value=''],#incbranchChosen option[value=''],#teamTransferForm")[0].reset();
    	               $(".empChosen option[value=''],.designChosen option[value=''],#incempChosen option[value=''],#incdesignChosen option[value=''],#incdepartChosen option[value=''],#incbranchChosen option[value='']").prop("selected", true).trigger('chosen:updated');
    	            if($(e.target).data('loaded') === false){
    	   			if($(e.target).data('id') == 'p'){
    	   			 $(".loader").loading(true);
    	   			 promotionEditableTable.init();
    	   		     $('#well,#manuallyPrmotionwithinc').hide();
  	   			     $(".loader").loading(false);
                    }else if($(e.target).data('id') == 'i'){
    	            	 $(".loader").loading(true);
    	            	 incEditableTable.init();
    	            	 $('#well,#manuallyonlyinc').hide();
    	                 $(".loader").loading(false);
    	         	}else if($(e.target).data('id') == 't'){
        	    		 $(".loader").loading(true);
        	    		 tfEditableTable.init();
                         $(".loader").loading(false);
        	        }else if($(e.target).data('id') == 'tt'){
      	    		  $(".loader").loading(true);
    	    		 tmEditableTable.init();
                     $(".loader").loading(false);
        	       }
    	   		  $(e.target).data('loaded',true);
    	      		}
    	      	});

      	    $('#incempChosen').on('change', function (e) {
	            e.preventDefault();
	            $('#errorInc,#errorPro').html('');
	            $('#onlyIncText,#promotionComeIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
                $('#manuallyonlyinc').hide();
                $('#incre_salaryBaseText').html('<div class="form-group displayHide"><label class="col-lg-5 control-label" for="pfLimit">PF Limit</label><div class="col-lg-7 left"><label class="checkbox-inline"><input type="checkbox" id="pfLimit" name="pfLimit" value="1" checked>Apply PF Limit <br>(for greater than Rs.15,000)</label><input type="hidden" class="workvalidate" id="pfGross" value="0"/></div></div><div class="form-group"><label class="col-lg-5 control-label">Salary Type</label><div class="col-lg-7"> <label for="ctcval" class="col-lg-4 control-label" style="padding-right: 0"><input name="salary_type" id="ctcval"  value="ctc" type="radio"> CTC</label><label for="monthly" class="col-lg-4 control-label" style="padding-left: 0; padding-right: 0"><input name="salary_type" id="monthly" value="monthly" type="radio"> MONTHLY</label></div></div><div class="form-group"><label class="col-lg-5 control-label">Slab Type</label><div class="col-lg-7"><label for="salary_based_on1" class="col-lg-4 control-label" style="padding-right: 0"><input name="salary_based_on"  id="salary_based_on1" value="basic" type="radio"> Basic</label><label for="salary_based_on2" class="col-lg-4 control-label" style="padding-left: 0; padding-right: 0"><input name="salary_based_on" id="salary_based_on2" value="gross" type="radio"> Gross</label><label for="salary_based_on3" class="col-lg-4 control-label" style="padding-left: 0; padding-right: 0"><input name="salary_based_on" id="salary_based_on3" value="noslab" type="radio"> No Slab</label></div></div><div id="slabloader" style="width:100%;height:50%"></div><input type="hidden" id="checkIfexit"><div class="form-group" id="slab_select_box"><label class="col-lg-5 control-label">Slab Name</label><div class="col-lg-7"><select id="slab_opt" name="slab" class="form-control"><option value="">Select Slab</option></select><span class="help-block" id="minimum_salary_div" style="display:none">Applicable only for salary gt <span id="min_salary_amount"></span></span></div></div><div id="getCTCcontent"></div>');
	            $('#onlyIncText').html('<div class="row"><div class="col-lg-6"><div class="oldsalaryonlyInc displayHide" id="well"></div></div><div class="col-lg-6" style="margin-left:-5.8%" ><div class="form-group" ><div id="loader" style="width:93%;height:93%"></div><div id="getTablecontent" class="container" style="width:90%"></div></div></div></div><input id="newCtc" name="newCtc" value="0" type="hidden"><input id="oldCtc" name="oldCtc" value="0" type="hidden"><input id="isSlab" value="0" type="hidden">');
                $('#slab_opt').chosen();
                employeeId=$('#incempChosen :selected').val();
                inividualCtcEdit('onlyInc',employeeId);
             });
     	  
     	    $(document).on('change',"input[type=radio][name=incVal]", function (e) {
	            e.preventDefault();
	             $('#errorInc,#errorPro').html('');
	            if($('.empChosen').val()!='' &&  $('.designChosen').val() != '' && $('#effectsFrom').val() != '' ){
	           $('.oldsalaryonlyInc,.oldsalaryprocomeInc,#getTablecontent').html('');
	            $('#manuallyPrmotionwithinc,#manuallyonlyinc,#well').hide();
	             if($(this).val()==1){
	            	  $('#isSlab').val(0);
	        	      $('#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
                      $('#manuallyPrmotionwithinc').hide();
                      $('#promote_salaryBaseText').html('<div class="form-group displayHide"><label class="col-lg-5 control-label" for="pfLimit">PF Limit</label><div class="col-lg-7 left"><label class="checkbox-inline"><input type="checkbox" id="pfLimit" name="pfLimit" value="1" checked>Apply PF Limit <br>(for greater than Rs.15,000)</label><input type="hidden" class="workvalidate" id="pfGross" value="0"/></div></div><div class="form-group"><label class="col-lg-5 control-label">Salary Type</label><div class="col-lg-7"> <label for="ctcval" class="col-lg-4 control-label" style="padding-right: 0"><input name="salary_type" id="ctcval"  value="ctc" type="radio"> CTC</label><label for="monthly" class="col-lg-4 control-label" style="padding-left: 0; padding-right: 0"><input name="salary_type" id="monthly" value="monthly" type="radio"> MONTHLY</label></div></div><div class="form-group"><label class="col-lg-5 control-label">Slab Type</label><div class="col-lg-7"><label for="salary_based_on1" class="col-lg-4 control-label" style="padding-right: 0"><input name="salary_based_on"  id="salary_based_on1" value="basic" type="radio"> Basic</label><label for="salary_based_on2" class="col-lg-4 control-label" style="padding-left: 0; padding-right: 0"><input name="salary_based_on" id="salary_based_on2" value="gross" type="radio"> Gross</label><label for="salary_based_on3" class="col-lg-4 control-label" style="padding-left: 0; padding-right: 0"><input name="salary_based_on" id="salary_based_on3" value="noslab" type="radio"> No Slab</label></div></div><div id="slabloader" style="width:100%;height:50%"></div><input type="hidden" id="checkIfexit"><div class="form-group" id="slab_select_box"><label class="col-lg-5 control-label">Slab Name</label><div class="col-lg-7"><select id="slab_opt" name="slab" class="form-control"><option value="">Select Slab</option></select><span class="help-block" id="minimum_salary_div" style="display:none">Applicable only for salary gt <span id="min_salary_amount"></span></span></div></div><div id="getCTCcontent"></div>');
		              $('#promotionComeIncText').html('<div class="row"><div class="col-lg-6"><div class="oldsalaryprocomeInc displayHide" id="well"></div></div><div class="col-lg-6" style="margin-left:-5.8%" ><div class="form-group" ><div id="loader" style="width:93%;height:93%"></div><div id="getTablecontent" class="container" style="width:90%"></div></div></div></div><input id="newCtc" name="newCtc" value="0" type="hidden"><input id="oldCtc" name="oldCtc" value="0" type="hidden"><input id="isSlab" value="0" type="hidden">');
                      $('#slab_opt').chosen();
                      employeeId=$('.empChosen :selected').val();
                      inividualCtcEdit('procomeInc',employeeId);
	               }
	               else{
		        	   $('#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
		        	   $('.oldsalaryprocomeInc,.oldsalaryonlyInc').html('');
			           }
	            }else{
	            	 $('#errorPro').html('Enter Required Fields');
		            }
	        });

	        function inividualCtcEdit(content,employeeId){
	        	 $.ajax({
		                dataType: 'html',
		                type: "POST",	
		                url: "php/employee.handle.php",
		                cache: false,
		                data: {act: '<?php echo base64_encode($_SESSION['company_id']."!salary_details");?>',
		                       employeeId:employeeId},
		                beforeSend:function(){$(".promotionLoader,.incLoader").loading(true);},
		                success: function (data) {
		               	 var json_obj = $.parseJSON(data); //parse JSON
		               	 (json_obj[2][0].pf_limit==1)?$('#pfLimit').prop('checked', true):
	        	 		 $('#pfLimit').prop('checked', false);
	         	 	 (json_obj[2][0].salary_type=='ctc')? 
	            	     $("input[name=salary_type][value='ctc']").prop('checked', true): 
	                	 $("input[name=salary_type][value='monthly']").prop('checked', true);
	                  if (json_obj[2][0].slab_id == "Nil") {
	                	 $('#slabTypedata').val('No Slab');
	                	 $("input[name=salary_based_on][value='noslab']").click();
	                     $("input[name=salary_based_on][value='noslab']").prop('checked', true);
	                     salaryEditTable(json_obj[2][0]);
	                   } else {
	                	   $('#isSlab').val(1);
	                      // $('.oldsalary'+content).html('<h4>Old Salary</h4>'+setData(json_obj[2][1]));
	                       $('.oldsalary'+content).html('<div class="table-responsive"><table class="table ctcDesigntable table-bordered"><thead><tr role="row"><th rowspan="2" style="padding: 22px;">Components</th><th colspan="3" style="text-align:center">Old Salary</th></tr><tr role="row"><th style="text-align:center">Rate</th><th colspan="1" rowspan="1" style="text-align:center">Monthly</th><th style="text-align:center">Yearly</th></tr></thead>'+
	                          	  setData(json_obj[2][1]));
	                      $('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable table-bordered"><thead><tr role="row"><th colspan="3" style="text-align:center">New Salary</th></tr><tr role="row"><th style="text-align:center">Rate</th><th colspan="1" rowspan="1" style="text-align:center">Monthly</th><th style="text-align:center">Yearly</th></tr></thead>'+
	                          	  setData(json_obj[2][1]));
	                       $('#ctcSalaryCalc').data('id',json_obj[2][0].slab_id);
	                     if (json_obj[2][0].slab_type == 'basic') {
	                    	  $('#slabTypedata').val('Basic');
	                		 $("input[name=salary_based_on][value='basic']").click();
	                		 }else if( json_obj[2][0].slab_type == 'gross'){
	                		  $('#slabTypedata').val('Gross');
	                		  $("input[name=salary_based_on][value='gross']").click();
	                		 }
	                      salaryEditTable(json_obj[2][0]);
	                      $('#oldCtc,#newCtc').val(json_obj[2][1].gross);
	                       }
	                  $('#manuallyPrmotionwithinc,#manuallyonlyinc,#well').show();
	                  //$(".promotionLoader,.incLoader").loading(false);
	                  setTimeout(function(){
	    	    		//  $(".promotionLoader,.incLoader").loading(true);
	      	  	    	  $("#slab_opt option[value='" + json_obj[2][0].slab_id+ "']").prop("selected", true);
	    	              $("#slab_opt").trigger("chosen:updated");
	    	              $(".promotionLoader,.incLoader").loading(false);
	    	              $('#getTablecontent').find('td.emptyDiv,th.emptyDiv').each(function () {
	    	                     $(this).remove();
	    	                    }); 
	    	           },500);
	    	           }
		               
		            });
		       }

	        $('.empChosen').on('change', function (e) {
	     	   e.preventDefault();
	     	   $("input[type=radio][name=incVal]").prop("checked", false);
	     	  $('#promotionComeIncText,#onlyIncText,#promote_salaryBaseText,#incre_salaryBaseText').html('');
	     	 $('.oldsalaryprocomeInc,.oldsalaryonlyInc').html('');
            });
		     
</script>
</body>
</html>
