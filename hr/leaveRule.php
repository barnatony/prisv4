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
<title>Leave Rule</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<style>
@media ( min-width : 992px) {
	.modal-lg {
		width: 1100px;
	}
}

#e_club_with_chosen, #allot_form_chosen, #allot_on_chosen,
	#round_off_chosen, #club_with_chosen, #c_allot_from_chosen,
	#e_allot_on_chosen, #e_round_off_chosen {
	width: 100% !important;
}
.back{
    margin-top: 5px;
    margin-right: 13px;
    margin-left: 7px;
}
</style>



<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <![endif]-->
</head>
<!--custom checkbox & radio-->

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
				<section class="panel displayHide" id="viewlrDiv">
					<header class="panel-heading">
						<button type="button" class="btn btn-primary btn-sm pull-left"
							id="urltoLrpage">
							<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Leave
							Rules
						</button>
						&nbsp; Leave Rule For <em id="lrName"></em> <input type="hidden"
							id="curentLeave">
                 <?php   if($_SESSION['payroll_flag']==0){?>
                 <button type="button"
							class="btn btn-primary btn-sm pull-right css" data-id="tcl"
							id="leaveEdit">
							<i class="fa fa-pencil"></i> Edit
						</button>
			     <?php }?>
			       <button type="button"
							class="btn btn-danger btn-sm pull-right css displayHide"
							id="cancelEditLeave" style="margin-left: 1%;">
							<i class="fa fa-undo"></i> Cancel
						</button>
						<button type="submit"
							class="btn btn-primary btn-sm pull-right css displayHide"
							id="leave_update">
							<i class="fa fa-check-square"></i> Save
						</button>

					</header>
					<div class="panel-body">
						<input type="hidden" id="editleaveserlize">
						<form id="editleaveform" method="POST"
							class="form-horizontal displayHide " role="form">
							<input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
							<div class="row col-lg-12">
								<div class="col-lg-6">
									<input type="hidden" name="lrule" id="e_leave_rule_id">
									<div class="form-group ">
										<label class="col-lg-5 control-label">Leave Name</label>
										<div class="col-lg-7">
											<input type="hidden" id="currentLeaveRule" /> <input
												type="text" class="form-control" name="lname"
												id="e_rule_name" value="" required />
										</div>
									</div>
									<input type="hidden" class="form-control" id="e_e_alias_name" />

									<div class="form-group ">
										<label class="col-lg-5 control-label"> Effect From</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<span class="input-group-addon" id="empIdPrefix"><i
												class="fa fa-calendar"></i></span> <input type="text"
												class="form-control" name="leffect" id="e_effects_from"
												value="" />
										</div>
									</div>
									<div class="form-group ">
										<label class="col-lg-5 control-label">Leave Allotment From</label>
										<div class="col-lg-7">
											<select class="form-control" id="c_allot_from" name="lfrom">
												<option value="JD">Joining Date</option>
												<option value="CD">Confirmation Date</option>
												<option value="AD">After Days</option>
											</select>
										</div>
									</div>
									<div class="form-group e_hide_after">
										<label class="col-lg-5 control-label"> After</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="ldays"
												id="e_after_days" value="0" /> <span
												class="input-group-addon">days</span>
										</div>

									</div>

									<div class="form-group">
                                        <label class="col-lg-5 control-label">Allotment Type</label>
                                        <div class="col-lg-7">    
                                              <span class="checkbox-inline"><input type="radio" value="M" name="ltype">&nbsp;Monthly</span>
                                              <span class="checkbox-inline"><input type="radio" value="Q" name="ltype">&nbsp;Quarterly</span>
                                              <span class="checkbox-inline"><input type="radio" value="Y" name="ltype">&nbsp;Yearly</span>
                                    	</div>
                                   </div>

									<div class="form-group ">
										<label class="col-lg-5 control-label"> Count</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lcount"
												id="e_days_count" value="12" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>

									<div class="form-group ">
										<label class="col-lg-5 control-label"> Maximum Combinable</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lmax"
												id="e_max_combinable" value="1.0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label"> Pro-Rata basic </label>
										<div class="col-lg-7">
											<div class="switch switch-square" data-on-label="Yes"
												data-off-label="No">
												<input type="checkbox" id="e_pro_rate" name="lbasic"
													value="1" />
											</div>
										</div>
									</div>


									<div class="form-group">
										<label class="col-lg-5 control-label">Allotment On </label>
										<div class="col-lg-7">
											<select class="form-control" id="e_allot_on" name="lallot">
												<option value="AD">Acutal Days</option>
												<option value="PD">Present Days</option>
												<option value="N">None</option>
											</select>
										</div>
									</div>

									<div class="form-group  e_calc_leave">
										<label class="col-lg-5 control-label">Leave Calculation As Per
										</label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 100px;"
												data-on-label="Current" data-off-label="Previous">
												<input type="checkbox" id="e_calculation_on" name="lcalc"
													value="PM" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Round Off</label>
										<div class="col-lg-7">
											<select class="form-control" id="e_round_off" name="lround">
												<option value="LH">Lower Halfday</option>
												<option value="HH">Higher Halfday</option>
												<option value="LF">Lower Fullday</option>
												<option value="HF">Higher Fullday</option>

											</select>
										</div>

									</div>
								</div>
								<div class="col-lg-6">

									<div class="form-group " id="e_carry_hide">
										<label class="col-lg-5 control-label">Can Carry Forward</label>
										<div class="col-lg-7">
											<div class="switch switch-square" data-on-label="Yes"
												data-off-label="No">
												<input type="checkbox" id="e_carry_forward" name="lcarry"
													value="1" />
											</div>
										</div>
									</div>

									<div class="form-group e_max_cf ">
										<label class="col-lg-5 control-label"> Maximum Carry Forward</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lmaxc"
												id="e_max_cf_days" value="0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>

									<div class="form-group " id="e_enc_hide">
										<label class="col-lg-5 control-label">Is Encashable</label>
										<div class="col-lg-7">
											<div class="switch switch-square" data-on-label="Yes"
												data-off-label="No">
												<input type="checkbox" id="e_encashable" name="lenc"
													value="1" />
											</div>
										</div>

									</div>

									<div class="form-group e_max_cf  ">
										<label class="col-lg-5 control-label">Remaining </label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 120px;"
												data-on-label="Encashable" data-off-label="Lapse">
												<input type="checkbox" id="e_encash_remain" name="lremain"
													value="E" />
											</div>
										</div>
									</div>

									<div class="form-group " id="e_max_encash">
										<label class="col-lg-5 control-label"> Maximam Encashable</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input class="form-control" name="lmaxenc"
												id="e_max_enc_days" value="0" type="text"> <span
												class="input-group-addon">days</span>
										</div>
									</div>
									<div class="form-group e_encas_days">
										<label class="col-lg-5 control-label">Maximum Encashable</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control e_max_enc_days"
												name="lmaxe" value="0" /> <span class="input-group-addon">days</span>
										</div>
									</div>
									<div class="form-group e_encas_days e_enc_salry ">
										<label class="col-lg-5 control-label"> Encashable Salary</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lencsal"
												id="e_enc_salary" value="0" /> <span
												class="input-group-addon">Times</span>
										</div>
									</div>

									<div class="form-group e_encas_days  e_enc_salry">
										<label class="col-lg-5 control-label"> Encashable on</label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 130px;"
												data-on-label="Yearly" data-off-label="Retirement">
												<input type="checkbox" id="e_encashable_on" name="lencon"
													value="Y" />
											</div>
										</div>
									</div>


									<div class="form-group" id="e_max_encash">
										<label class="col-lg-5 control-label">Remaining</label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 80px;"
												data-on-label="C/F" data-off-label="Lapse">
												<input type="checkbox" id="e_carry_remain" name="lremaine"
													value="cf" />
											</div>
										</div>
									</div>
									<div class="form-group e_carry_days">
										<label class="col-lg-5 control-label"> Maximam Carry Forward</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control e_max_enc_days"
												id="e_e_max_cf_days" name="lmaxcarry" value="0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>

									<div class="form-group ">
										<label class="col-lg-6 control-label">Can Leave Part of </label>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="weekoff"> <input
												type="checkbox" id="weekoff" name="lpart[]" value="W">Weekoff
											</label>
										</div>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="holiday"> <input
												type="checkbox" id="holiday" name="lpart[]" value="H">Holidays
											</label>
										</div>

									</div>

									<div class="form-group ">
										<label class="col-lg-6 control-label">Can Leave Part of
											preceeding</label>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="p_weekoff"> <input
												type="checkbox" id="p_weekoff" name="lpreed[]" value="W">Weekoff
											</label>
										</div>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="p_holidays"> <input
												type="checkbox" id="p_holidays" name="lpreed[]" value="H">Holidays
											</label>
										</div>
									</div>


									<div class="form-group ">
										<label class="col-lg-6 control-label">Can Leave Part of
											Suceeding</label>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="s_weekoff"> <input
												type="checkbox" id="s_weekoff" name="lsuceed[]" value="W">Weekoff
											</label>
										</div>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="s_holidays"> <input
												type="checkbox" id="s_holidays" name="lsuceed[]" value="H">Holidays
											</label>
										</div>

									</div>

									<div class="form-group ">
										<label class="col-lg-6 control-label">Applicable To</label>
										<div class="checkboxes col-lg-2">
											<label class="checkbox-inline" for="male"> <input
												type="checkbox" id="male" name="lappli[]" value="Male">Male

											</label>
										</div>
										<div class="checkboxes col-lg-2">
											<label class="checkbox-inline" for="female"> <input
												type="checkbox" id="female" name="lappli[]" value="Female">Female
											</label>
										</div>

										<div class="checkboxes col-lg-2">
											<label class="checkbox-inline" for="trans"> <input
												type="checkbox" id="trans" name="lappli[]" value="Trans">Trans
											</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label" for="pf_limit">Can Club
											With</label>
										<div class="col-lg-7" style="text-align: left;">
											<select class="form-control" id="e_club_with" name="lclub[]"
												multiple>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, "SELECT leave_rule_id, rule_name,alias_name FROM company_leave_rules WHERE enabled = 1" );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $leave_rule_id, $rule_name, $alias_name );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $leave_rule_id . "'>" . $alias_name . "</option>";
																																										}
																																										?>
                                             </select>
										</div>
									</div>

								</div>




							</div>
						</form>
						<form class="form-horizontal" role="form" method="post"
							id="leaveruleview">

							<div class="col-lg-6">
								<div class="form-group">
									<label class="col-lg-5 control-label">Leave Name</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_rule_name" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-5 control-label">Effect From</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_effects_from" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-5 control-label">Leave Allotment From</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="lr_allot_from" />
									</div>
								</div>

								<div class="form-group daysDiv displayHide">
									<label class="col-lg-5 control-label"> After Days</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_after_days" />
									</div>
								</div>


								<div class="form-group">
									<label class="col-lg-5 control-label">Allotment Type</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_type" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label"> Count</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_days_count" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-5 control-label">Maximum Combinable</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_max_combinable" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label"> Pro-Rata basic </label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_pro_rate" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-5 control-label">Allotment On </label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_allot_on" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-5 control-label">Leave Calculation As Per
									</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_leave_calc" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label">Round Off</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_round_off" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label">Applicable To</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_applicable_to" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label">Can Club With</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control" id="lr_club_with" />
									</div>
								</div>
							</div>
							<div class="col-lg-6">

								<div class="form-group">
									<label class="col-lg-5 control-label">Is Encashable</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="lr_is_encashable" />
									</div>
								</div>
								<div id="isencaDiv" style="display: none;">
									<div class="form-group">
										<label class="col-lg-5 control-label">Maximam Encashable </label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lr_max_enc_days" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Encashable Salary </label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lr_enc_salary" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Encashable On </label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lr_encashable_on" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Remaining</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lr_remain_enc" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Maximam Carry Forward</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lr_max_cf_days" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-5 control-label">Can Carry Forward</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="lr_carry_forward" />
									</div>
								</div>
								<div id="iscarryWholeDiv">
									<div class="form-group">
										<label class="col-lg-5 control-label">Maximum Carry Forward</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lr_cmax_cf_days" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Remaining </label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lr_remain_cf" />
										</div>
									</div>
									<div id="iscarryDiv">

										<div class="form-group">
											<label class="col-lg-5 control-label">Maximum Encashable </label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control"
													id="lr_cmax_enc_days" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Encashable Salary</label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control" id="lr_cenc_salary" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Encashable On</label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control"
													id="lr_cencashable_on" />
											</div>
										</div>
									</div>

								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label">Can Leave Part of </label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control"
											id="lr_leave_in_middle" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label">Can Leave Part of
										preceeding</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control"
											id="lr_leave_in_preceeding" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label">Can Leave Part of
										Suceeding</label>
									<div class="col-lg-7 ">
										<input type="text" class="form-control"
											id="lr_leave_in_succeeding" />
									</div>
								</div>
							</div>
						</form>
					</div>
				</section>



				<section class="panel" id="createlrDiv">
					<header class="panel-heading">
						Leave Rules
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn btn-sm btn-info"
								style="margin-top: -5px;">
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</header>

					<div class="panel-body">

						<div class="col-lg-12 displayHide" id="add-leave-toggle">
							<form class="form-horizontal" role="form" method="post"
								id="leaveAddForm">
								<input type="hidden" name="act"
									value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
								<br>
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"> Name</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" id="lname" name="lname" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> Alias</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control"  id="Lalias" name="Lalias"
												id="alias_name" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> Effect From</label>
										<div class="col-md-7 col-xs-11 input-group">
											<span class="input-group-addon" id="empIdPrefix"><i
												class="fa fa-calendar"></i></span> <input type="text"
												class="form-control" name="leffect" id="effect_from" 
												value="" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Leave Allotment From</label>
										<div class="col-lg-7">
											<select class="form-control" id="allot_form" name="lfrom">
												<option value="JD">Joining Date</option>
												<option value="CD">Confirmation Date</option>
												<option value="AD">After Days</option>
											</select>
										</div>
									</div>
									<div class="form-group hide_after">
										<label class="col-lg-5 control-label"> After</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="ldays"
												id="after_days" value="0" /> <span class="input-group-addon">days</span>
										</div>
									</div>
									<div class="form-group">
                                        <label class="col-lg-5 control-label">Allotment Type</label>
                                        <div class="col-lg-7">    
                                              <span class="checkbox-inline"><input type="radio" value="M" name="ltype">&nbsp;Monthly</span>
                                              <span class="checkbox-inline"><input type="radio" value="Q" name="ltype">&nbsp;Quarterly</span>
                                              <span class="checkbox-inline"><input type="radio" value="Y" name="ltype">&nbsp;Yearly</span>
                                    	</div>
                                	</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> Count</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lcount" id="lcount"
												value="1.00" /> <span class="input-group-addon">days</span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Maximum Combinable</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lmax"
												id="max_combinable" value="1.0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> Pro-Rata basic </label>
										<div class="col-lg-7">
											<div class="switch switch-square" data-on-label="Yes"
												data-off-label="No">
												<input type="checkbox" id="pro_rate" name="lbasic" value="1" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Allotment On </label>
										<div class="col-lg-7">
											<select class="form-control" id="allot_on" name="lallot">
												<option value="AD">Acutal Days</option>
												<option value="PD">Present Days</option>
												<option value="N">None</option>
											</select>
										</div>
									</div>


								</div>

								<div class="col-lg-6">
									<div class="form-group calc_leave">
										<label class="col-lg-5 control-label">Leave Calculation As Per
										</label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 100px;"
												data-on-label="Current" data-off-label="Previous">
												<input type="checkbox" id="leave_calc" name="lcalc"
													value="PM" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Round Off</label>
										<div class="col-lg-7">
											<select class="form-control" id="round_off" name="lround">
												<option value="LH">Lower Halfday</option>
												<option value="HH">Higher Halfday</option>
												<option value="LF">Lower Fullday</option>
												<option value="HF">Higher Fullday</option>

											</select>
										</div>
									</div>

									<div class="form-group" id="carry_hide">
										<label class="col-lg-5 control-label">Can Carry Forward</label>
										<div class="col-lg-7">
											<div class="switch switch-square" data-on-label="Yes"
												data-off-label="No">
												<input type="checkbox" id="carry_forward" name="lcarry"
													value="1" />
											</div>
										</div>

									</div>

									<div class="form-group max_cf">
										<label class="col-lg-5 control-label"> Maximum Carry Forward</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lmaxc"
												id="m_carry_forward" value="0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>
									<div class="form-group max_cf">
										<label class="col-lg-5 control-label">Remaining </label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 120px;"
												data-on-label="Encashable" data-off-label="Lapse">
												<input type="checkbox" id="encash_remain" name="lremain"
													value="E" />
											</div>
										</div>
									</div>
									<div class="form-group encas_days">
										<label class="col-lg-5 control-label">Maximum Encashable</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lmaxe"
												id="m_carry_forward" value="0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>

									<div class="form-group" id="enc_hide">
										<label class="col-lg-5 control-label">Is Encashable</label>
										<div class="col-lg-7">
											<div class="switch switch-square" data-on-label="Yes"
												data-off-label="No">
												<input type="checkbox" id="encashable" name="lenc" value="1" />
											</div>
										</div>

									</div>
									<div class="form-group" id="max_encash">
										<label class="col-lg-5 control-label"> Maximam Encashable</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lmaxenc"
												id="max_encash1" value="0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>
									<div class="form-group encas_days enc_salry">
										<label class="col-lg-5 control-label"> Encashable Salary</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lencsal"
												value="0" /> <span class="input-group-addon">Times</span>
										</div>
									</div>
									<div class="form-group encas_days enc_salry">
										<label class="col-lg-5 control-label"> Encashable On</label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 120px;"
												data-on-label="Yearly" data-off-label="Retirement">
												<input type="checkbox" id="encashable_on" name="lencon"
													value="Y" />
											</div>
										</div>
									</div>
									<div class="form-group" id="max_encash">
										<label class="col-lg-5 control-label">Remaining</label>
										<div class="col-lg-7">
											<div class="switch switch-square" style="width: 80px;"
												data-on-label="C/F" data-off-label="Lapse">
												<input type="checkbox" id="carry_remain" name="lremaine"
													value="cf" />
											</div>
										</div>
									</div>

									<div class="form-group carry_days">
										<label class="col-lg-5 control-label"> Maximam Carry Forward</label>
										<div class="col-lg-7 col-xs-11 input-group">
											<input type="text" class="form-control" name="lmaxcarry"
												id="max_encash1" value="0" /> <span
												class="input-group-addon">days</span>
										</div>
									</div>


									<div class="form-group">
										<label class="col-lg-5  control-label">Can Leave Part of </label>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="leave_part1"> <input
												type="checkbox" id="leave_part1" name="lpart[]" value="W">Weekoff
											</label>
										</div>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="leave_part2"> <input
												type="checkbox" id="leave_part2" name="lpart[]" value="H">Holidays
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Can Leave Part of
											preceeding</label>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="leave_pre1"> <input
												type="checkbox" id="leave_pre1" name="lpreed[]" value="W">Weekoff
											</label>
										</div>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="leave_pre2"> <input
												type="checkbox" id="leave_pre2" name="lpreed[]" value="H">Holidays
											</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Can Leave Part of
											Suceeding</label>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="leave_suc1"> <input
												type="checkbox" id="leave_suc1" name="lsuceed[]" value="W">Weekoff
											</label>
										</div>
										<div class="checkboxes col-lg-3">
											<label class="checkbox-inline" for="leave_suc2"> <input
												type="checkbox" id="leave_suc2" name="lsuceed[]" value="H">Holidays
											</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label" for="pf_limit">Can Club
											With</label>
										<div class="col-lg-7">
											<select class="form-control" id="club_with" name="lclub[]"
												multiple>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, "SELECT leave_rule_id, rule_name,alias_name FROM company_leave_rules WHERE enabled = 1" );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $leave_rule_id, $rule_name, $alias_name );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $leave_rule_id . "'>" . $alias_name . "</option>";
																																										}
																																										?>
                                             </select>
										</div>

									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Applicable To</label>
										<div class="checkboxes col-lg-2">
											<label class="checkbox-inline" for="leave_applicable1"> <input
												type="checkbox" id="leave_applicable1" name="lappli[]"
												checked value="Male">Male

											</label>
										</div>
										<div class="checkboxes col-lg-2">
											<label class="checkbox-inline" for="leave_applicable2"> <input
												type="checkbox" id="leave_applicable2" name="lappli[]"
												value="Female">Female
											</label>
										</div>

										<div class="checkboxes col-lg-2">
											<label class="checkbox-inline" for="leave_applicable4"> <input
												type="checkbox" id="leave_applicable4" name="lappli[]"
												value="Trans">Trans
											</label>
										</div>
									</div>


									<label id="error-msg" class="text-danger"></label>
									<div class="form-group">
										<div class="col-lg-6 col-lg-offset-5 ">
											<button type="submit" class="btn btn-sm btn-success"
												id="leave-add">Add Rule</button>
											<button type="button" class="btn btn-sm btn-danger"
												id="cancel">Cancel</button>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="space15"></div>
						<br>
						<br>
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover table-bordered cf"
									id="leave-sample">
									<thead class="cf">
										<tr>
											<th>RulesID</th>
											<th>Name</th>
											<th>Type</th>
											<th>Count</th>
											<th>Max combine</th>
											<th>Applicable To</th>
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
				</section>


				<!-- page end-->
			</section>
		</section>

		<!--footer start-->
      <?php include_once (__DIR__."/footer.php");?>
         <!--footer end-->
	</section>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>


	<!--custom checkbox & radio-->
	<script type="text/javascript" src="../js/ga.js"></script>
	<script src="../js/bootstrap-switch.js"></script>
	<script src="../js/jquery.tagsinput.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>


	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>

	<!--script for this page-->
	<script src="../js/form-component.js"></script>

	<!--script for this page only-->
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>



	<!-- END JAVASCRIPTS -->

	<script type="text/javascript">
    $(document).ready(function () {
    	  var EditableTable = function () {

              return {

                  //main function to initiate the module
                  init: function () {

                      var oTable = $('#leave-sample').dataTable({
                          "aLengthMenu": [
                              [5, 15, 20, -1],
                              [5, 15, 20, "All"] // change per page values here
                          ],

                          // set the initial value
                          "iDisplayLength": 5,
                          "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                          "bProcessing": true,
                          "bServerSide": true,
                          "sAjaxSource": "php/leave-view.php",
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
          						{ "sName": "applicable_to" },
                                  { "sName": "enabled", "bSortable": false },
                                   ],

                                    "aoColumnDefs": [{ "mRender": function 
             	                        (data, type, row) {
             	                        if(row[6]==10){
                 	                        return '<a class="disable" href="" title="Disable"><button class="btn btn-primary btn-xs" style="padding: 1px 4px;"><i class="fa fa-unlock"></i></button></a>&nbsp;<a href="#" data-id="'+row[0]+'"  title="View" class="view_lr"><button class="btn btn-info btn-xs" ><i class="fa fa-eye"></i></button></a>';          
                 	                     }else if (row[6]==11){
                 	                    	   return '<a class="disable" href="" title="Disable"><button class="btn btn-primary btn-xs" style="padding: 1px 4px;"><i class="fa fa-unlock"></i></button></a>&nbsp;<a href="#"  title="View" data-id="'+row[0]+'" class="view_lr" data-toggle="modal"><button class="btn btn-info btn-xs" ><i class="fa fa-eye"></i></button></a>';          
                       	                 }else if (row[6]==01){
                 	                    	   return '<a class="enable"title="Enable" href="#"><button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>&nbsp;<a href="#" data-id="'+row[0]+'"  title="View" class="view_lr" data-toggle="modal"><button class="btn btn-info btn-xs" ><i class="fa fa-eye"></i></button></a>';          
                       	                 }else if (row[6]==00){
                 	                    	   return '<a class="enable"title="Enable" href="#"><button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>&nbsp;<a href="#" data-id="'+row[0]+'"  title="View" class="view_lr" data-toggle="modal"><button class="btn btn-info btn-xs" ><i class="fa fa-eye"></i></button></a>';          
                       	                 }
			  	                        
 	                         },
 	                             "aTargets": [6]

 	                         },
                      ],
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

                      $('#leave-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                                        <input class="form-control medium" id="searchInput" type="text">\
                                                        <span class="input-group-btn">\
                                                          <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                                        </span>\
                                                        <span class="input-group-btn">\
                                                          <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                                        </span>\
                                                    </div>');
                      $('#leave-sample_processing').css('text-align', 'center');
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

                      //Enable coding
                       $(document).on('click', "#leave-sample a.disable", function (e) {
                          e.preventDefault();
                          var nRow = $(this).parents('tr')[0];
                          var data = oTable.fnGetData(nRow);
                          var l_id = data[0];
                          var l_name = data[1];
                          BootstrapDialog.show({
          	                title:'Confirmation',
                              message: 'Are Sure you want to Disable <strong>'+ l_name +'<strong>',
                              buttons: [{
                                  label: 'Disable',
                                  cssClass: 'btn-sm btn-success',
                                  autospin: true,
                                  action: function(dialogRef){
                                  	 $.ajax({
               		                    dataType: 'html',
               		                    type: "POST",
               		                    url: "php/leaveRule.handle.php",
               		                    cache: false,
               		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', lrule: l_id  },
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
                      });

                    
                      //Disable coding
                      $(document).on('click', "#leave-sample a.enable", function (e) {
                          e.preventDefault();
                          var nRow = $(this).parents('tr')[0];
                          var data = oTable.fnGetData(nRow);
                          var e_l_id = data[0];
                          var e_l_name = data[1];
                          BootstrapDialog.show({
          	                title:'Confirmation',
                              message: 'Are Sure you want to Enable <strong>'+ e_l_name +'</strong>',
                              buttons: [{
                                  label: 'Enable',
                                  cssClass: 'btn-sm btn-success',
                                  autospin: true,
                                  action: function(dialogRef){
                                  	 $.ajax({
               		                    dataType: 'html',
               		                    type: "POST",
               		                    url: "php/leaveRule.handle.php",
               		                    cache: false,
               		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>', lrule: e_l_id },
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
                      });

                       //Add LEave Rules
                       $(document).on('click',"#leave-add",function(e) {
                          e.preventDefault();
                       if ($("#lname").val() == '' && $("#Lalias").val() == '' &&  $("#effect_from").val() == '' 
                           || $("#lcount").val() == '' || $("#max_combinable").val() == '') 
                             {
                            $('#error-msg').html('Enter All Required Fields');
                          }
                          else {
                        	    $('#error-msg').html('');
                                $.ajax({
                                  dataType: 'html',
                                  type: "POST",
                                  url: "php/leaveRule.handle.php",
                                  cache: false,
                                  data: $('#leaveAddForm').serialize(),
                                  beforeSend:function(){
                                   	$('#leave-add').button('loading'); 
                                    },
                                    complete:function(){
                                   	 $('#leave-add').button('reset');
                                    },
									success: function (data) {
                                      data1 = JSON.parse(data);
                                      if (data1[0] == "success") {
                                      	$('#pro_rate,#leave_calc,#carry_forward,#encash_remain,#encashable_on,#encashable,#carry_remain').prop('checked', false);
                                      	$('#pro_rate,#leave_calc,#carry_forward,#encash_remain,#encashable_on').parents().removeClass("switch-on switch-animate");
                                          $('#pro_rate,#leave_calc,#carry_forward,#encash_remain,#encashable_on').parents().addClass("switch-off switch-animate");
                                          jQuery('#add-leave-toggle').toggle('hide');
                                          $('#carry_hide,#enc_hide').show();
                                          $('#max_encash,#encash_on,#encash,.carry_days,.max_cf,.encas_days').hide();
                                          $('#alias_name').removeClass('correct wrong');
                                          $('#leaveAddForm')[0].reset();
                                          oTable.fnDraw();
BootstrapDialog.alert("Leave added suscessfully");
                                      }
                                      else
                                          if (data1[0] == "error") {
                                              alert(data1[1]);
                                          }
                                  }

                              });
                          }
                      });


                       $('#urltoLrpage').on('click', function (event) {
                     	  $('#createlrDiv').show();
                           $('#viewlrDiv').hide();
                           oTable.fnDraw();
                     });


                      //Edit Submit Form
                      $('#leave_update').on('click', function (e) {
                    	 e.preventDefault();
                    	 if ($("#editleaveserlize").val() == $('#editleaveform').serialize()) {
								BootstrapDialog.alert('No More Changes Deducted');
	                        }else{
                          $.ajax({
                              dataType: 'html',
                              type: "POST",
                              url: "php/leaveRule.handle.php",
                              cache: false,
                              data: $('#editleaveform').serialize(),
                             /* beforeSend:function(){
                               	$('#leave_update').button('loading'); 
                                },
                                complete:function(){
                               	 $('#leave_update').button('reset');
                                },*/
                              success: function (data) {
                                  data1 = JSON.parse(data);
                                  if (data1[0] == "success") {
                                	  $('#editleaveform,#leave_update,#cancelEditLeave').hide();
                                      $('#leaveruleview,#leaveEdit').show();
                                      console.log($("#e_e_alias_name").val());
                                      lrDataFill($("#e_e_alias_name").val());
                                  }
                                  else
                                      if (data1[0] == "error") {
                                          alert(data1[1]);
                                      }
                              }

                          });
	                        }
                    	
                      });
                  }

              };

          } ();
          
          EditableTable.init();
        $('#carry_forward').prop('checked', false);
         $('#encashable').prop('checked', false);
        $('#club_with').chosen();
         $('#e_club_with,#allot_form,#allot_on,#round_off,#c_allot_from,#e_allot_on,#e_round_off').chosen();
         var dateToday = new Date();    

         
   	      $('#effect_from,#e_effects_from').datetimepicker({
          	  format: 'DD/MM/YYYY',
           });
         
            	  $('#allot_form').on("change", function (e) {
                     if($(this).val()=='AD')
                     {
                         $('.hide_after').show();
                         $('#after_days').val('0');
                     }else
                         {
                    	 $('.hide_after').hide();
                    	 $('#after_days').val('0');
                         }

                   });
                   
            	   $('#allot_on').on("change", function (e) {
                       if($(this).val()=='N')
                       {
                           $('.calc_leave').hide();
                           $('#leave_calc').prop('checked', false);
                       }else{
                      	 $('.calc_leave').show();
                           }

                     });
                $("#max_encash,#encash_on,#encash,.max_cf,.encas_days,.hide_after,.carry_days").hide();
                $("#add-leave-toggle").hide();
                $("#encashable1").change(function () {
                    $("#max_encash,#encash_on,#encash").show();
                });

               
                $('#encashable').change(function () {
                    if ($(this).prop('checked')) {
                    	$('#carry_hide').hide();
                        $("#max_encash,#encash_on,#encash,.enc_salry").show();
                    }
                    else {
                    	$('#carry_hide').show();
                       $("#max_encash,#encash_on,#encash,.carry_days").hide();
                    }
                });

                
                $('#encash_remain').change(function () {
                    if ($(this).prop('checked')) {
                        $(".encas_days").show(); // checked
                    }
                    else {
                        $(".encas_days").hide();
                    }
                });
                $('#carry_forward').change(function () {
                    if ($(this).prop('checked')) {
                    	$('#enc_hide').hide();
                        $(".max_cf").show(); // checked
                        
                    }
                    else {
                        $('#encash_remain').prop('checked', false);
                    	$('#enc_hide').show();
                        $(".max_cf,.encas_days").hide();
                    }
                });
                $('#carry_remain').change(function () {
                    if ($(this).prop('checked')) {
                        $(".carry_days").show(); // checked
                    }
                    else {
                        $(".carry_days").hide();
                    }
                });


                //edit show and hide detail
                  $('#e_allot_on').on("change", function (e) {
                       if($(this).val()=='N')
                       {
                           $('.e_calc_leave').hide(); $('#e_leave_calc').prop('checked', false);
                       }else
                           {
                      	 $('.e_calc_leave').show();
                           }

                     });
                $('#c_allot_from').on("change", function (e) {
                     if($(this).val()=='AD')
                     {
                    	 $('#e_after_days').val('0');
                         $('.e_hide_after').show();
                           }else
                         {
                        $('#e_after_days').val('0');
                    	 $('.e_hide_after').hide();
                         }

                   });
                 $('#e_encashable').change(function () {
                    if ($(this).prop('checked')) {
                    	$('#e_carry_hide,.e_encas_days').hide();
                        $("#e_max_encash,#e_encash_on,#e_encash,.e_enc_salry").show();
                    }
                    else {
                    	$('#e_carry_hide').show();
                    	$('#e_e_max_cf_days,#e_max_enc_days,#e_enc_salary').val('0');
                    	$('#e_encashable_on,#e_carry_remain').prop('checked', false);
                        $("#e_max_encash,#e_encash_on,#e_encash,.e_carry_days,.e_enc_salry").hide();
                    }
                });
                 
                $('#e_encash_remain').change(function () {
                    if ($(this).prop('checked')) {
                        $(".e_encas_days").show(); // checked
                    }
                    else {
                    	$('#e_encashable_on,#e_carry_remain').prop('checked', false);
                    	
                        $(".e_encas_days").hide();
                    }
                });
                $('#e_carry_forward').change(function () {
                    if ($(this).prop('checked')) {
                    	$('#e_enc_hide').hide();
                        $(".e_max_cf").show(); // checked
                    }
                    else {
                    	$('#e_encashable_on,#e_encash_remain').prop('checked', false);
                    	$('#e_max_cf_days,.e_max_enc_days,#e_enc_salary').val('0');
                    	 
                    	$('#e_enc_hide').show();
                        $(".e_max_cf,.e_encas_days").hide();
                    }
                });
                $('#e_carry_remain').change(function () {
                    if ($(this).prop('checked')) {
                        $(".e_carry_days").show(); // checked
                    }
                    else {
                    	$('#e_e_max_cf_days').val('0');
                    	$(".e_carry_days").hide();
                    }
                });
                
                $('#carry_forward,#encashable').on('click', function () {

                    $('#m_carry_forward,#max_encash1,#encash1').val("0");

                });
				});


            
            $('#alias_name').on('blur', function () {
                if ($(this).val() != "") {
                	var str = $(this).val();
                	var res = str.toLowerCase(); 
                      var xhr = $.ajax({
                        type: "POST",
                        url: "../common/user_name_check.php",
                        data: { "leave_alias": res},
                        cache: false,
                        beforeSend: function () {
                            
                        },
                        complete: function () {
                        },
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data.length > 0) {
                          	  $('#alias_name').val('');
                          	  $('#alias_name').removeClass('correct');
                                $('#alias_name').addClass('wrong');
                            } else {
                                $('#alias_name').removeClass('wrong');
                                $('#alias_name').addClass('correct');
                            }
                        }
                    });
                }

            });
            $('#showhide').on('click', function (event) {
                $('#add-leave-toggle').toggle('show');
            });
            $('#cancel').on('click', function (event) {
                $('#add-leave-toggle').toggle('hide');
            });
           
            $('#cancelEditLeave').on('click', function (event) {
            	 $('#editleaveform,#leave_update,#cancelEditLeave').hide();
                 $('#leaveruleview,#leaveEdit').show();
                 lrDataFill($("#curentLeave").val());
            });

            function lrDataFill(aliasname){
                $('#curentLeave').val(aliasname);
                $.ajax({
                      dataType: 'html',
                      type: "POST",
                      url: "php/leaveRule.handle.php",
                      cache: false,
                      data: { act: '<?php echo base64_encode($_SESSION['company_id']."!view");?>', lrule: aliasname},
		                  success: function (data) {
		                	json_obj = JSON.parse(data);
                           if (json_obj[0] == "success") {
                        	  $('#createlrDiv,#editleaveform,#leave_update,#cancelEditLeave').hide();
                              $('#viewlrDiv,#leaveruleview,#leaveEdit').show();
                              $("#leaveruleview").find(":input").each(function () {
                             	  $(this).attr('disabled', true); 
                             	  $(this).css({ 'background-color': '#FFF', 'border': '0px solid #E2E2E4' });
                              });

                              $('#lrName').html(json_obj[2][0].rule_name);
                              $('#lr_rule_name').val(json_obj[2][0].rule_name);

                                      $('.daysDiv').hide();
                                       if(json_obj[2][0].allot_from.split('|')[1]=='JD'){
                                    	  $('#lr_allot_from').val('Joining Date');
                                          }else if(json_obj[2][0].allot_from.split('|')[1]=='CD'){
                                        	  $('#lr_allot_from').val('Confirmation Date');
                                          }else if(json_obj[2][0].allot_from.split('|')[1]=='AD'){
                                        	  $('#lr_allot_from').val('After Days');
                                        	  $('.daysDiv').show();
                                        	  $('#lr_after_days').val(json_obj[2][0].allot_from.split('|')[0]);
                                           }
                                       
                                 	   if(json_obj[2][0].type=='M'){
                                		   $('#lr_type').val('Month');
                                       }else if(json_obj[2][0].type=='Y'){
                                    	   $('#lr_type').val('Year');
                                       }else if(json_obj[2][0].type=='Q'){
                                    	   $('#lr_type').val('Quarter');
                                       }
                                       
                                  	  if(json_obj[2][0].pro_rata_basis==1){
                               		   $('#lr_pro_rate').val('Yes');
                                      }else if(json_obj[2][0].pro_rata_basis==0){
                                   	   $('#lr_pro_rate').val('No');
                                      }

                                        if(json_obj[2][0].allot_on=='AD'){
                                   		   $('#lr_allot_on').val('Acutal Days');
                                          }else if(json_obj[2][0].allot_on=='PD'){
                                       	   $('#lr_allot_on').val('Present Days');
                                          }else if(json_obj[2][0].allot_on=='N'){
                                       	   $('#lr_allot_on').val('None');
                                          }
                                        
                                    		  if(json_obj[2][0].calculation_on=='CM'){
                                          		   $('#lr_leave_calc').val('Current Month');
                                              }else if(json_obj[2][0].calculation_on=='PM'){
                                              	   $('#lr_leave_calc').val('Previous Month');
                                              }
                                              
                                          if(json_obj[2][0].round_off=='LH'){
                                     		   $('#lr_round_off').val('Lower Halfday');
                                            }else if(json_obj[2][0].round_off=='HH'){
                                         	   $('#lr_round_off').val('Higher Halfday');
                                            }else if(json_obj[2][0].round_off=='LF'){
                                         	   $('#lr_round_off').val('Lower Fullday');
                                            }else if(json_obj[2][0].round_off=='HF'){
                                         	   $('#lr_round_off').val('Higher Fullday');
                                            }
                                          $('#lr_days_count').val(json_obj[2][0].days_count+'  Days');
                                          $('#lr_max_combinable').val(json_obj[2][0].max_combinable+'  Days');
                                          $('#lr_effects_from').val(json_obj[2][0].effects_from);

                                          if(json_obj[2][0].is_encashable==1){
                                              $('#isencaDiv').show();
                                        	  $('#lr_is_encashable').val('Yes');
                                        	  $('#lr_max_enc_days').val(json_obj[2][0].max_enc_days+'  Days');
                                              $('#lr_enc_salary').val(json_obj[2][0].enc_salary+'  Times');
                                              if(json_obj[2][0].encashable_on=='Y'){
                                            	  $('#lr_encashable_on').val('Yearly');
                                              }else{
                                            	  $('#lr_encashable_on').val('Retirement');
                                              }
                                              if(json_obj[2][0].remain_enc=='L'){
                                            	  $('#lr_remain_enc').val('Lapse');
                                            	  $('#lr_max_cf_days').val(json_obj[2][0].max_cf_days).hide();
                                              }else{
                                            	  $('#lr_remain_enc').val('Carry Forward');
                                            	  $('#lr_max_cf_days').val(json_obj[2][0].max_cf_days).show();
                                            	 $('#lr_max_cf_days').val(json_obj[2][0].max_cf_days).show();
                                              }
                                              }else{
                                             $('#isencaDiv').hide();
                                             $('#lr_is_encashable').val('No');
                                             }


                                          if(json_obj[2][0].carry_forward==1){
                                        	  $('#iscarryWholeDiv').show();
                                              $('#lr_carry_forward').val('Yes');
                                        	  $('#lr_cmax_cf_days').val(json_obj[2][0].max_cf_days+' Days');
                                         	  if(json_obj[2][0].remain_cf=='E'){
                                         		  $('#iscarryDiv').show();
                                           	      $('#lr_remain_cf').val('Encashable');
                                                  $('#lr_cmax_enc_days').val(json_obj[2][0].max_enc_days+' Days');
                                            	  $('#lr_cenc_salary').val(json_obj[2][0].enc_salary+' Times');
                                            	  if(json_obj[2][0].encashable_on=='Y'){
                                            	  $('#lr_cencashable_on').val('Yearly');
                                            	  }else{
                                            	  $('#lr_cencashable_on').val('Retirement');
                                                  }
                                              }else{
                                            	  $('#iscarryDiv').hide();
                                           	      $('#lr_remain_cf').val('Lapse');
                                             }}else{
                                             $('#iscarryWholeDiv').hide();
                                             $('#lr_carry_forward').val('No');
                                             }
                                          
                                          if(json_obj[2][0].leave_in_middle=='B'){
                                        	  $('#lr_leave_in_middle').val('Weekoff,Holidays');
                                          }else if(json_obj[2][0].leave_in_middle=='W'){
                                        	  $('#lr_leave_in_middle').val('Weekoff');
                                          }else if(json_obj[2][0].leave_in_middle=='H'){
                                        	  $('#lr_leave_in_middle').val('Holidays');
                                          }else if(json_obj[2][0].leave_in_middle=='N'){
                                        	  $('#lr_leave_in_middle').val('-');
                                          }
                                          if(json_obj[2][0].leave_in_preceeding=='B'){
                                        	  $('#lr_leave_in_preceeding').val('Weekoff,Holidays');
                                          }else if(json_obj[2][0].leave_in_middle=='W'){
                                        	  $('#lr_leave_in_preceeding').val('Weekoff');
                                          }else if(json_obj[2][0].leave_in_middle=='H'){
                                        	  $('#lr_leave_in_preceeding').val('Holidays');
                                          }else if(json_obj[2][0].leave_in_middle=='N'){
                                        	  $('#lr_leave_in_preceeding').val('-');
                                          }
                                          if(json_obj[2][0].leave_in_succeeding=='B'){
                                        	  $('#lr_leave_in_succeeding').val('Weekoff,Holidays');
                                          }else if(json_obj[2][0].leave_in_middle=='W'){
                                        	  $('#lr_leave_in_succeeding').val('Weekoff');
                                          }else if(json_obj[2][0].leave_in_middle=='H'){
                                        	  $('#lr_leave_in_succeeding').val('Holidays');
                                          }else if(json_obj[2][0].leave_in_middle=='N'){
                                        	  $('#lr_leave_in_succeeding').val('-');
                                          }

                                          $('#lr_applicable_to').val(json_obj[2][0].applicable_to);
                                          if(json_obj[2][0].club_with){
                                              if(json_obj[2][0].club_with!='N'){
                                        	  $('#lr_club_with').val(json_obj[2][0].club_with.toUpperCase());
                                          }else{
                                        	  $('#lr_club_with').val('-');
                                              }
                                          }
                                          var checkeditapplicable='<?php echo $_SESSION['payroll_flag'];?>';
                                          if(checkeditapplicable==0){
                                          $.each(json_obj[2][0], function (k, v) {
                                              $('#e_' + k).val(v);
                                          });
                                          $('.e_hide_after').hide();
                                          $('#e_e_alias_name').val(json_obj[2][0].alias_name );
                                          $('.e_max_enc_days').val(json_obj[2][0].max_enc_days );
                                          $('#e_e_max_cf_days').val(json_obj[2][0].max_cf_days );
                                          $('#e_enc_hide,#e_carry_hide').show();
                                          
                                          if (json_obj[2][0].calculation_on !== 'PM') {
                                          	 $('#e_calculation_on').prop('checked', true);
                                              $('#e_calculation_on').parents().removeClass("switch-off switch-animate");
                                              $('#e_calculation_on').parents().addClass("switch-on switch-animate");
                                          } else {
                                          	$('#e_calculation_on').prop('checked', false);
                                              $('#e_calculation_on').parents().removeClass("switch-on switch-animate");
                                              $('#e_calculation_on').parents().addClass("switch-off switch-animate");
                                          }
                                          
                                          if (json_obj[2][0].carry_forward !== '0') {
                                            $('#e_enc_hide,.e_carry_days').hide();
                                              $('#e_carry_forward').prop('checked', true);
                                              $('#e_carry_forward').parents().removeClass("switch-off switch-animate");
                                              $('#e_carry_forward').parents().addClass("switch-on switch-animate");
                                              $('.e_max_cf ').show();
                                               if( json_obj[2][0].remain_cf !=='L')
                                          	   {
                                          	   
                                          	   $('#e_encash_remain').prop('checked', true);
                                          	   $('#e_encash_remain').parents().removeClass("switch-off switch-animate");
                                                 $('#e_encash_remain').parents().addClass("switch-on switch-animate");
                                          	   $(".e_encas_days").show();
                                          	    }else{
                                          	       $('#e_encash_remain').prop('checked', false);
                                          		   $('#e_encash_remain').parents().removeClass("switch-on switch-animate");
                                                     $('#e_encash_remain').parents().addClass("switch-off switch-animate");
                                                     $(".e_encas_days").hide();
                                          	   }
                                          } else {
                                              $('#e_carry_forward').prop('checked', false);
                                              $('#e_enc_hide').show();
                                              $('#e_carry_forward').parents().removeClass("switch-on switch-animate");
                                              $('#e_carry_forward').parents().addClass("switch-off switch-animate");
                                              $(".e_max_cf,.e_encas_days ").hide();
                                          }
                                          
                                          
                                           if (json_obj[2][0].is_encashable !== '0') {
                                          	$('#e_carry_hide,.e_carry_days').hide();	
                                              $('#e_encashable').prop('checked', true);
                                              $('#e_encashable').parents().removeClass("switch-off switch-animate");
                                              $('#e_encashable').parents().addClass("switch-on switch-animate");
                                              $("#e_max_encash,#e_encash_on,#e_encash,.e_enc_salry").show();
                                              $('#max_encash_e').val(json_obj[2][0].max_enc_days);
                                              $('#e_encahable_timesOf_pay').val(json_obj[2][0].encahable_timesOf_pay);
                                              if (json_obj[2][0].remain_enc !== 'L') {
                                              	$('.e_carry_days').show();	
                                                  $('#e_carry_remain').prop('checked', true);
                                                  $('#e_carry_remain').parents().removeClass("switch-off switch-animate");
                                                  $('#e_carry_remain').parents().addClass("switch-on switch-animate");
                                              } else {
                                              	$('.e_carry_days').hide();
                                                  $('#e_carry_remain').prop('checked', false);
                                                  $('#e_carry_remain').parents().removeClass("switch-on switch-animate");
                                                  $('#e_carry_remain').parents().addClass("switch-off switch-animate");
                                              }

                                          } else {
                                              $('#e_encashable').prop('checked', false);
                                              $('#e_encashable').parents().removeClass("switch-on switch-animate");
                                              $('#e_encashable').parents().addClass("switch-off switch-animate");
                                              $("#e_max_encash,#e_encash_on,#e_encash").hide();
                                          }
                                         
                                           $('#e_allot_on option[value='+json_obj[2][0].allot_on+']').attr('selected', true);
                                           $("#e_allot_on").trigger("chosen:updated");
                                           $('.e_calc_leave').show();
                                              
                                          	if(json_obj[2][0].allot_on=='N'){
                                          		 $('.e_calc_leave').hide();
                                          	}

                                          	var res = json_obj[2][0].allot_from.split("|");
                                            $('#c_allot_from option[value='+res[1]+']').attr('selected', true);
                                          	$('#c_allot_from').trigger("chosen:updated");
                                            $('.e_hide_after').hide();
                                                   
                                               	if(res[1]=='AD'){
                                               		$('#e_after_days').val(res[0]);
                                               		 $('.e_hide_after').show();
                                               	}
                                                   
                                          	
                                          $('#e_round_off option[value='+json_obj[2][0].round_off+']').attr('selected', true);
                                          $("#e_round_off").trigger("chosen:updated");
                                         
                                          
                                          if (json_obj[2][0].pro_rata_basis !== '0') {
                                          	 $('#e_pro_rate').prop('checked', true);
                                              $('#e_pro_rate').parents().removeClass("switch-off switch-animate");
                                              $('#e_pro_rate').parents().addClass("switch-on switch-animate");

                                          } else {
                                          	$('#e_pro_rate').prop('checked', false);
                                              $('#e_pro_rate').parents().removeClass("switch-on switch-animate");
                                              $('#e_pro_rate').parents().addClass("switch-off switch-animate");

                                          }

                                          

                                          if (json_obj[2][0].encashable_on !== 'R') {
                                            $('#e_encashable_on').prop('checked', true);
                                              $('#e_encashable_on').parents().removeClass("switch-off switch-animate");
                                              $('#e_encashable_on').parents().addClass("switch-on switch-animate");
                                          } else {
                                              $('#e_encashable_on').prop('checked', false);
                                              $('#e_encashable_on').parents().removeClass("switch-on switch-animate");
                                              $('#e_encashable_on').parents().addClass("switch-off switch-animate");
                                          }

                                          if (json_obj[2][0].type !== 'Y') {
                                              $('#e_type').prop('checked', true);
                                              $('#e_type').parents().removeClass("switch-off switch-animate");
                                              $('#e_type').parents().addClass("switch-on switch-animate");
                                          } else {
                                              $('#e_type').prop('checked', false);
                                              $('#e_type').parents().removeClass("switch-on switch-animate");
                                              $('#e_type').parents().addClass("switch-off switch-animate");
                                          }

                                          //leave midle
                                          if (json_obj[2][0].leave_in_middle == 'W') {
                                             
                                              $('#weekoff').prop('checked', true);

                                          } else {
                                              if (json_obj[2][0].leave_in_middle == 'H') {
                                                    
                                                  $('#holiday').prop('checked', true);

                                              } else {

                                                  if (json_obj[2][0].leave_in_middle == 'B') {
                                                       
                                                      $('#holiday').prop('checked', true);
                                                      $('#weekoff').prop('checked', true);


                                                  } else {
                                                       
                                                      $('#holiday').prop('checked', false);
                                                      $('#weekoff').prop('checked', false);


                                                  }

                                              }
                                          }

                                          //leave Preceeding
                                          if (json_obj[2][0].leave_in_preceeding == 'W') {

                                              $('#p_weekoff').prop('checked', true);

                                          } else {
                                              if (json_obj[2][0].leave_in_preceeding == 'H') {
                                                  $('#p_holidays').prop('checked', true);
                     } else {

                                                  if (json_obj[2][0].leave_in_preceeding == 'B') {
                                                      $('#p_weekoff').prop('checked', true);
                                                      $('#p_holidays').prop('checked', true);


                                                  } else {
                                                      $('#p_weekoff').prop('checked', false);
                                                      $('#p_holidays').prop('checked', false);


                                                  }

                                              }
                                          }


                                          //leave Succeding
                                          if (json_obj[2][0].leave_in_succeeding == 'W') {
                                              $('#s_weekoff').prop('checked', true);

                                          } else {
                                              if (json_obj[2][0].leave_in_succeeding == 'H') {
                                                  $('#s_holidays').prop('checked', true);

                                              } else {

                                                  if (json_obj[2][0].leave_in_succeeding == 'B') {
                                                      $('#s_holidays').prop('checked', true);
                                                      $('#s_weekoff').prop('checked', true);


                                                  } else {
                                                      $('#s_holidays').prop('checked', false);
                                                      $('#s_weekoff').prop('checked', false);


                                                  }

                                              }
                                          }


                                          //leave Succeding
                                         if (json_obj[2][0].applicable_to == 'Male') {

                                              $('#male').prop('checked', true);
                                               $('#trans').prop('checked', false);
                                              $('#female').prop('checked', false);

                                          } else if (json_obj[2][0].applicable_to == 'Female') {
                                                  $('#female').prop('checked', true);
                                                    $('#male').prop('checked', false);
                                               $('#trans').prop('checked', false);

                                              } else if (json_obj[2][0].applicable_to == 'Trans') {
                                                  	    $('#trans').prop('checked', true);
                                                          $('#male').prop('checked', false);
                                                          $('#female').prop('checked', false);
                                            }else if (json_obj[2][0].applicable_to == 'Male,Female,Trans') {
                                          	    $('#trans').prop('checked', true);
                                                $('#male').prop('checked', true);
                                                $('#female').prop('checked', true);
                                  }else if (json_obj[2][0].applicable_to == 'Female,Trans') {
                                	    $('#trans').prop('checked', true);
                                        $('#male').prop('checked', false);
                                        $('#female').prop('checked', true);
                          }else if (json_obj[2][0].applicable_to == 'Male,Female') {
                      	    $('#trans').prop('checked', false);
                            $('#male').prop('checked', true);
                            $('#female').prop('checked', true);
              }else if (json_obj[2][0].applicable_to == 'Male,Trans') {
            	    $('#trans').prop('checked', true);
                    $('#male').prop('checked', true);
                    $('#female').prop('checked', false);
             }
                                  
                        $.each(json_obj[2][0].club_with.split(","), function (i, e) {
                                              $("#e_club_with option[value='" + e + "']").prop("selected", true);
                                          });

                                          $("#e_club_with").trigger("chosen:updated"); //for drop down   

                      setTimeout(function(){  $('#editleaveserlize').val($("#editleaveform").serialize()); }, 100);  
                                          }
                            }
                      }

                  });
                  }

            $(document).on('click',".view_lr",function(e) {
                e.preventDefault();
                var aliasname=$(this).data('id');
                lrDataFill(aliasname);
           });

            $(document).on('click',"#leaveEdit",function(e) {
             e.preventDefault();
             $(this).hide();
             $('#editleaveform,#leave_update,#cancelEditLeave').show();
             $('#leaveruleview').hide();
             });
        </script>