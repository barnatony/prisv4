<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Notice Period</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />



<style>
.loader {
	position: absolute;
	z-index: 1000;
	height: 65%;
	width: 81%;
	background: rgba(255, 255, 255, .8) url('../img/ajax-loader.gif') 50%
		50% no-repeat;
}

.popover-title {
	padding: 3px 10px;
}

.popover-content {
	padding: 3px 10px;
}

.retirementAllow {
	cursor: pointer;
}

.fa.pull-left {
	margin-right: .3em;
	margin-top: 3px;
}

.bold_ {
	font-weight: bold;
	font-size: 12px;
}

span.previous {
	float: left;
}

.date {
	text-align: center;
}

span.next {
	float: right;
}

table#header {
	width: 100%;
	color: rgb(54, 50, 50);
}

table#id td {
	padding: 6px;
}

.align_text {
	text-align: right;
}

th {
	text-align: center;
}

.font_bold {
	font-weight: bold;
}

#employee_id_chosen, #e_affected_ids_chosen, #reason_chosen,
	#processType_chosen, #e_reason_chosen, #e_processType_chosen ,.table{
	width: 100% !important;
}
</style>
</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php
					
include_once (__DIR__ . "/header.php");
					require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
					error_reporting ( 0 );
					$employee = new Employee ();
					$employee->conn = $conn;
					$employeeNotice = $employee->selectColmEmp ( "notice_period,employee_id,employee_name", " FROM employee_work_details WHERE enabled=1" );
					$noticeReason = $employee->selectColmEmp ( "exit_id,reason_code FROM exit_reasons", "" );
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php
												include_once (__DIR__ . "/sideMenu.php");
												?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->

		<form action="php/noticePeriod.handle.php" method="post"
			id="generate_pdf_form">
			<input type="hidden" name="employee_id" id="employeeId"> <input
				type="hidden" name="processType" id="employeeType"> <input
				type="hidden" name="act"
				value="<?php echo base64_encode($_SESSION['company_id']."!downloadGeneratePdf");?>">
			<button type="submit" style="display: none;"></button>
		</form>

		<section id="main-content">
			<section class="wrapper site-min-height">

				<div class="panel" id="settlementPreview" style="display: none;">
					<header class="panel-heading">
						<button type="button" class="btn btn-primary btn-sm pull-left css"
							id="back">
							<i class="fa fa-fast-backward" aria-hidden="true"></i> Back
						</button>
						&nbsp; &nbsp; Settlement Preview
						<button type="button"
							class="btn btn-default btn-sm pull-right css" id="runPayroll">
							<i class="fa fa-fast-forward" aria-hidden="true"></i> Run Payroll
						</button>
					</header>
					<div class="panel-body">
					<form action="php/noticePeriod.handle.php" method="post"
											id=settlementPreviewPdf>
											<input type="hidden"
												value="<?php echo base64_encode($_SESSION['company_id']."!settlementPreviewPdf");?>"
												name="act"><input type="hidden" name="employee_id" id="employeeId1">
												<input type="hidden" name="processType" id="processId1">
												<button class="btn btn-success btn-sm pull-right css" style="margin-right:162px;" type="submit">Download 
					                          </button><br></form>
						<div class="container "
							style="width: 75%; box-shadow: 1px 0px 10px 4px rgb(136, 136, 136);margin-top:30px">
							<section style="text-align: left">
								<!-- Gratutity default value SET From query Excecuted -->
								<input type="hidden" id="inputofr_gratuity"> <input
									type="hidden" id="editedValuer_gratuity" value="0"> <input
									type="hidden" id="grautityValuer_gratuity"> <input
									type="hidden" id="inputofr_retrenchment"> <input type="hidden"
									id="editedValuer_retrenchment"> <input type="hidden"
									id="grautityValuer_retrenchment">
       <?php
							
							$html = "";
							$Body = "";
							
							$html = '<br><div style="background-color:#FAFAFA;padding:10px;">
            <table id="header"><th colspan="4">' . "Provisional full and final Settlement For The  Month  of " . date ( 'F', mktime ( 0, 0, 0, $_SESSION ['monthNo'], 10 ) ) . " " . $_SESSION ['payrollYear'] . '</th>';
							
							$html .= ' <tr><td><div class="col-lg-12">
	                  <div class="col-lg-6 col-sm-6">                      
			          <tr> <td width="25%"> <span class="bold_ previous"> Employee Name </span> </td><td><span class="date"> : 
			          <span  class="employee_name"></span></span></td><td>  <span class="bold_ previous">Bank Name </span>  
			          </td><td><span class="date"> :  <span  class="employee_bank_name"></span></span></td></tr></div>
			          </td></tr>
    		          <tr><td><div class="col-lg-12">
			          <div class="col-lg-6 col-sm-6">                      
			          <tr> <td width="25%"> <span class="bold_ previous"> Employee ID </span> </td><td><span class="date"> : 
			          <span  class="employee_id"></span></span></td><td> <span class="bold_ previous">Account No </span>  
			          </td><td><span class="date"> : <span  class="employee_acc_no"></span></span></td></tr></div>
			          </td></tr>  
    		          <tr><td><div class="col-lg-12">
			          <div class="col-lg-6 col-sm-6">                      
			          <tr> <td width="25%"> <span class="bold_ previous">DOJ</span> </td><td><span class="date"> : 
			          <span  class="employee_doj"></span></span></td><td>  <span class="bold_ previous">Pan No </span>  
			          </td><td><span class="date"> : <span  class="employee_pan_no"></span></span></td></tr></div>
			          </td></tr>
    		          <tr><td><div class="col-lg-12">
			          <div class="col-lg-6 col-sm-6">                      
			          <tr> <td width="25%"> <span class="bold_ previous">Department</span> </td><td><span class="date"> : 
			          <span  class="department_name"></span></span></td><td>  <span class="bold_ previous">EPF </span>  
			          </td><td><span class="date"> : <span  class="employee_emp_pf_no"></span></span></td></tr></div>
			          </td></tr>
    		         <tr><td><div class="col-lg-12">
			          <div class="col-lg-6 col-sm-6">                      
			          <tr> <td width="25%"> <span class="bold_ previous">Designation</span> </td><td><span class="date"> : 
			          <span  class="designation_name"></span></span></td><td>  <span class="bold_ previous">IFSC </span>  
			          </td><td><span class="date"> : <span  class="employee_bank_ifsc"></span></span></td></tr></div>
			          </td></tr>
    	             <tr><td><div class="col-lg-12">
			          <div class="col-lg-6 col-sm-6">                      
			          <tr> <td width="25%"> <span class="bold_ previous">Branch</span> </td><td><span class="date"> : 
			          <span  class="branch_name"></span></span></td><td>  <span class="bold_ previous">ESI </span>  
			          </td><td><span class="date"> : <span  class="employee_emp_esi_no"></span></span></td></tr></div>
			          </td></tr>
					  <tr><td><div class="col-lg-12">
			          <div class="col-lg-6 col-sm-6">                      
			          <tr> <td width="25%">  <span class="bold_ previous">Last Working Date </span>  
			          </td><td><span class="date"> : <span  class="last_working_date"></span></span></td><td>  <span class="bold_ previous">LOP </span>  
			          </td><td><span class="date"> : <span  class="lop"></span></span></td></tr></div>
			          </td></tr>';
							
							$Body = '</table></div><table class="table table-striped table-hover" id="id"  style="color: rgb(54, 50, 50);">';
							
							$allowances = "";
							$gross = "";
							$deduction = "";
							$other_salary = "";
							$other_deductions = "";
							
							$allowances = '<tr class="line_1 font_bold sortable" style="display:none;">
                        <td  style="text-align:center;">Payheads</td><td  style="text-align:right;width: 198px;">Monthly ( &#8377; )</td>
                        <td  style="text-align:center;" colspan="2" >Deductions</td><td  style="text-align:right;">Monthly (  &#8377; )</td></tr>';
							
							$allAllowNameId = array ();
							$allDeducNameId = array ();
							// Allowances and Deduction
							Session::newInstance ()->_setGeneralPayParams ();
							$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
							
							foreach ( $allowDeducArray ['A'] as $allow ) {
								$allAllowNameId [] = $allow ['display_name'] . "," . $allow ['pay_structure_id'];
							}
							
							foreach ( $allowDeducArray ['D'] as $dedu ) {
								$allDeducNameId [] = $dedu ['display_name'] . "," . $dedu ['pay_structure_id'];
							}
							
							// miscAllowances and miscDeduction
							Session::newInstance ()->_setMiscPayParams ();
							$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
							
							foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
								$allAllowNameId [] = $miscAllow ['display_name'] . "," . $miscAllow ['pay_structure_id'];
							}
							
							foreach ( $miscallowDeducArray ['MD'] as $miscDedu ) {
								$allDeducNameId [] = $miscDedu ['display_name'] . "," . $miscDedu ['pay_structure_id'];
							}
							
							// retirement Allowances And Deduction
							Session::newInstance ()->_setRetirementParams ();
							$retirementArray = Session::newInstance ()->_get ( "retirementParams" );
							
							foreach ( $retirementArray ['RA'] as $retirementAllow ) {
								$allRetireNameId [] = $retirementAllow ['display_name'] . "," . $retirementAllow ['pay_structure_id'];
							}
							
							foreach ( $retirementArray ['RD'] as $retirementDedu ) {
								$decuRetireNameId [] = $retirementDedu ['display_name'] . "," . $retirementDedu ['pay_structure_id'];
							}
							
							if (count ( $allAllowNameId ) != count ( $allDeducNameId )) {
								foreach ( $allAllowNameId as $key => $value ) :
									if (! isset ( $allDeducNameId [$key] ))
										$allDeducNameId [$key] = NULL;
								endforeach
								;
								$combinedAllowDeduc = array_combine ( $allAllowNameId, $allDeducNameId );
							}
							
							if (! $combinedAllowDeduc) {
								if (count ( $allDeducNameId ) != count ( $allAllowNameId )) {
									foreach ( $allDeducNameId as $key => $value ) :
										if (! isset ( $allAllowNameId [$key] ))
											$allAllowNameId [$key] = NULL;
									endforeach
									;
								}
								$combinedAllowDeduc = array_combine ( $allAllowNameId, $allDeducNameId );
							}
							
							foreach ( $combinedAllowDeduc as $k => $v ) {
								list ( $allowName, $allowId ) = explode ( ",", $k );
								list ( $deducName, $deducId ) = explode ( ",", $v );
								
								$allowances .= '<tr class="sortable" style="display:none"><td>' . $allowName . '</td><td style="width: 198px;" class="align_text"><span class=' . $allowId . '></span></td>
                                <td colspan="2" >' . $deducName . '</td><td colspan="2" class="align_text"><span class=' . $deducId . '></span></td></tr>';
							}
							
							echo $html;
							echo $Body;
							echo $name = '<tr style="cursor: pointer;" id="grossDeduc"><td class="font_bold previous" id="imgChange" colspan="5"><i class="fa fa-plus-square pull-left" aria-hidden="true"></i>                  		General Allowances</td></tr>';
							echo $allowances;
							
							echo $gross = '<tr><td class="font_bold align_text">
                                 Gross</td><td colspan="2"  class="font_bold  align_text"><span class="gross_salary"></span></td >
                                 <td class="font_bold align_text ">Total Deductions</td><td  class="font_bold  align_text"><span class="total_deduction"></span></td></tr>
                          		<tr><td class="font_bold align_text" colspan="4">
                                Net Amount</td><td  class="font_bold  align_text"><span class="net_salary"></span></td></tr>';
							
							$retirementAllow = "";
							$retirementDedu = "";
							
							$retirementAllow .= '<tr><td class="font_bold previous" colspan="5"><i class="fa fa-minus-square pull-left" aria-hidden="true"></i> Retirement Benefits</td></tr>';
							
							foreach ( $allRetireNameId as $retieKey => $retirevalue ) {
								list ( $retireName, $retireId ) = explode ( ",", $retirevalue );
								if ($retireId == 'r_leaveenc') {
									$retirementAllow .= '<tr class="retirementAllow"><td colspan="2">' . $retireName . '</td><td class="align_text" colspan="4"><span class="spanHIde ' . $retireId . '"></span>
                           		<div class="input-group divHide" id="' . $retireId . '"  style="display:none;"><input class="form-control medium ' . $retireId . ' leaveEnc"  type="text">
                           		<span class="input-group-btn"  id="save' . $retireId . '" >
                           		<button style="height: 34px;" class="btn btn-white" type="button"  data-id=' . $retireId . '  >
                           		<i class="fa fa-check" aria-hidden="true"></i></button></span><span class="input-group-btn"><button style="height: 34px;" class="btn btn-white closeInput" type="button">
                           		<i class="fa fa-times-circle" aria-hidden="true"></i></button></span></div></div></div></td></tr>';
								} else {
									$retirementAllow .= '<tr class="retirementAllow"><td colspan="2">' . $retireName . '</td><td class="align_text" colspan="4"><span class="spanHIde ' . $retireId . '">1500.15</span><div class="input-group divHide" id="' . $retireId . '"  style="display:none;"><input class="form-control medium ' . $retireId . '"  type="text"><span class="input-group-btn"><button style="height: 34px;" class="btn btn-white" type="button" id="calculate" ><i class="fa fa-refresh" aria-hidden="true"></i> </button></span><span class="input-group-btn saveHide" style="display:none;"  id="save' . $retireId . '" ><button style="height: 34px;" class="btn btn-white saveSubmit" type="button"  data-id=' . $retireId . '  ><i class="fa fa-check" aria-hidden="true"></i></button></span><span class="input-group-btn"><button style="height: 34px;" class="btn btn-white closeInput" type="button"><i class="fa fa-times-circle" aria-hidden="true"></i></button></span><div class="popover-markup" id="remark' . $retireId . '"><div class="head hide"> Why this change?</div><div class="content hide"><div class="form-group"><textarea class="form-control" id="remark' . $retireId . '"  placeholder="Enter Remarks (  50 Characters ) " rows="1" maxlength="50" ></textarea><button type="button" style="margin-top: 2%;" class="btn btn-xs btn-block col-lg-12 remarksubmit" data-id=' . $retireId . ' >Submit</button></div></div></div></div></td></tr>';
								}
							}
							$retirementDedu .= '<tr><td class="font_bold previous" colspan="5"><i class="fa fa-minus-square pull-left" aria-hidden="true"></i> Retirement Deductions</td></tr>';
							
							foreach ( $decuRetireNameId as $retiedKey => $retiredvalue ) {
								list ( $retiredName, $retiredId ) = explode ( ",", $retiredvalue );
								$retirementDedu .= '<tr><td colspan="4">' . $retiredName . '</td><td class="align_text"><span class=' . $retiredId . '></span></td></tr>';
							}
							
							echo $retirementAllow;
							echo $retirementDedu;
							echo $amount = '<tr><td class="font_bold align_text" colspan="4">
                                Net Amount Payable</td><td  class="font_bold  align_text"><span class="Netpayable"></span></td></tr><tr><td >
                                  Amount in words</td><td colspan="4"><span class="words"></span></td></tr></table>
                                 </table><br>';
							
							?>
           </section>
           </div>

						</div>

					</div>





				<section class="panel" id="viewContent" style="display: none">
					<header class="panel-heading">
						<button type="button"
							class="btn btn-primary btn-sm pull-left css ticket_list">
							<i class="fa fa-fast-backward" aria-hidden="true"></i> Notice
							Period
						</button>
						&nbsp;&nbsp;<em id="employeeName"></em>
						<button type="button"
							class="btn btn-primary btn-sm pull-right css" id="edit" style="">
							<i class="fa fa-pencil"></i> Edit
						</button>
					</header>
					<div class="row">
						<div class="col-md-12">
							<section class="panel">
								<div class="panel-body bio-graph-info">
									<!--<h1>New Dashboard BS3 </h1>-->
									<form id="editnotice" method="POST" class="form-horizontal"
										role="form">
										<input type="hidden" name="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
										<div class="row col-lg-12">
											<input type="hidden" name="nId" id="notice_id">
											<div class="col-lg-7" style="display: none;">
												<select class="form-control" name="employee_id"
													id="e_affected_ids">
                                         <?php
																																									foreach ( $employeeNotice as $row ) {
																																										echo "<option  data-id='" . $row ['notice_period'] . "' value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																									}
																																									?>  
                                        </select>
											</div>
											<div class="form-group  col-lg-6">
												<label for="dname" class="col-lg-4 col-sm-4 control-label">
													Notice Date</label>
												<div class="col-lg-7 input-group">
													<span class="input-group-addon" style="cursor: pointer"><i
														class="fa fa-calendar"></i></span>
													<div class="iconic-input right">
														<input class="form-control datepickerCls" type="text"
															name="nDate" id="e_notice_date" disabled required />
													</div>

												</div>
											</div>

											<div class="form-group  col-lg-6">
												<label for="dname" class="col-lg-4 col-sm-4 control-label">
													Last Working Date</label>
												<div class="col-lg-7 input-group">
													<span class="input-group-addon" style="cursor: pointer"><i
														class="fa fa-calendar"></i></span>
													<div class="iconic-input right">
														<input class="form-control datepickerCls" type="text"
															name="lDate" id="e_resignation_date" disabled required />
													</div>
													<span class='helpBlock  input-group' style="color: red"
														id="invaliddate"></span>
												</div>
											</div>

										</div>
										<div class="row col-lg-12">
											<div class="form-group  col-lg-12">
												<label for="dname" class="col-lg-2 col-sm-2 control-label">Letter
												</label>
												<div class="col-lg-8">
													<textarea class="form-control ckeditor" name="editor1"
														disabled rows="4"></textarea>
												</div>
												<input type="hidden" name="letterCode" id="letter">

											</div>
										</div>
										<div class="row col-lg-12">
											<div class="form-group col-lg-6">
												<label class="col-lg-4 col-sm-4 control-label">Reason</label>
												<div class="col-lg-7">
													<select class="form-control" id="e_reason"
														name="reasonCode">
														<option value="">Select Reason</option>
                                        <?php
																																								foreach ( $noticeReason as $row ) {
																																									echo "<option  value='" . $row ['exit_id'] . "'>" . $row ['reason_code'] . " <br>" . "</option>";
																																								}
																																								?>                                    
                                        </select>
												</div>
											</div>
											<input type="hidden" name="status" id="e_status">

											<div class="form-group col-lg-6">
												<label class="col-lg-4 col-sm-4 control-label">Process Type</label>
												<div class="col-lg-7">
													<select class="form-control" id="e_processType"
														name="processType">
														<option value="">Select Process Type</option>
														<option value="P">Pay</option>
														<option value="SP">StopPay</option>
														<option value="S">Settlement</option>
													</select>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<label for="dname" class="col-lg-4 col-sm-4 control-label">Remarks
												</label>
												<div class="col-lg-7">
													<textarea class="form-control" rows="2" id="e_remark"
														disabled name="remark"></textarea>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<label for="dname" class="col-lg-4 col-sm-4 control-label">Process
													Status </label>
												<div class="col-lg-7" style="margin-top: 2%;">
													<span class="ticket_status"><span
														class="label label-primary" id="estatus"></span></span>
												</div>
											</div>


										</div>


										<div class="modal-footer" id="buttons" style="display: none;">
											Done Edit ?
											<button type="submit" class="btn btn-sm btn-success"
												data-id="A" id="approve">Approve</button>
											<button type="submit" class="btn btn-sm btn-danger"
												data-id="D" id="decline">Decline</button>
											<button type="submit" class="btn btn-sm btn-info" data-id="H"
												id="hold">Hold</button>
											<button type="button" class="btn btn-sm btn-danger"
												id="cancel">Cancel</button>
										</div>

									</form>
								</div>

							</section>
						</div>

					</div>
				</section>
				<div id="ifnoticeempty" class="well displayHide"></div>
				<div id="content" class="displayHide">
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs" id="noticeperiodTabs">
							<li class=""><a href="#thismonth" data-title="thismonth"
								data-loaded="false" data-toggle="tab"> This Month Exits </a></li>
							<li class=""><a href="#allexits" data-title="allexits"
								data-loaded="false" data-toggle="tab"> All Exits </a></li>
							
						</ul>
					</header>
					<section class="panel">
						<header class="panel-heading"
							style="border-color: #fff; margin-top: -2%;">

							<div class="btn-group pull-right">
								<button id="add_notice" type="button"
									class="btn btn-sm btn-info" style="margin-top: -5px;">

									<i class="fa fa-plus"></i> Add
								</button>
							</div>
						</header>

						<div class="panel-body">
							<div class="col-lg-12" id="hide_from">
								<form class="form-horizontal" role="form" method="post"
									id="notice_add">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
									<div class="col-lg-12" id="add-department">
										<div class="panel-body">

											<div class="form-group">
												<label for="dname" class="col-lg-3 col-sm-3 control-label">Employee
													Name</label>
												<div class="col-lg-5">
													<select class="form-control" id="employee_id"
														name="employee_id">
                                        <?php
																																								foreach ( $employeeNotice as $row ) {
																																									echo "<option  data-id='" . $row ['notice_period'] . "' value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ] " . " <br>" . "</option>";
																																								}
																																								?>                                    
                                        </select>
												</div>
											</div>
											<div class="form-group">
												<label for="dname" class="col-lg-3 col-sm-3 control-label">Notice
													Date</label>
												<div class="col-lg-5 input-group">
													<span class="input-group-addon" style="cursor: pointer"><i
														class="fa fa-calendar"></i></span>
													<div class="iconic-input right">
														<input class="form-control datepickerCls" type="text"
															id="datepickerCls" name="nDate" required />
													</div>

												</div>
											</div>
											<div class="form-group">
												<label for="dname" class="col-lg-3 col-sm-3 control-label">Last
													Working day </label>
												<div class="col-lg-5 input-group">
													<span class="input-group-addon" style="cursor: pointer"><i
														class="fa fa-calendar"></i></span>
													<div class="iconic-input right">
														<input class="form-control datepickerCls" type="text"
															id="resignation_date" name="lDate" required />
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Reason</label>
												<div class="col-lg-5">
													<select class="form-control" id="reason" name="reasonCode">
                                        <?php
																																								foreach ( $noticeReason as $row ) {
																																									echo "<option  value='" . $row ['exit_id'] . "'>" . $row ['reason_code'] . " <br>" . "</option>";
																																								}
																																								?>                                    
                                        </select>
												</div>
											</div>

											<div class="form-group">
												<label for="dname" class="col-lg-3 col-sm-3 control-label">Remarks
												</label>
												<div class="col-lg-5">
													<textarea class="form-control" rows="4" name="remark"></textarea>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-3 col-sm-3 control-label">Process Type</label>
												<div class="col-lg-5">
													<select class="form-control" id="processType"
														name="processType" required>
														<option value="P">Pay</option>
														<option value="SP">StopPay</option>
														<option value="S">Settlement</option>
													</select>
												</div>
											</div>
											<input type="hidden" name="status" id="status">
											<div class="form-group">
												<div class="col-lg-offset-3 col-lg-5" align="right">
													<button type="submit" class="btn btn-sm btn-success"
														data-id="A" id="noticeApprove">Approve</button>
													<button type="submit" class="btn btn-sm btn-success displayHide"
														data-id="H" id="noticeHold">Hold</button>
													<button type="button" class="btn btn-sm  btn-danger"
														id="cancel">Cancel</button>
												</div>
											</div>
										</div>
									</div>

								</form>
							</div>

							<div class="tab-content tasi-tab">
								<div class="tab-pane active" id="thismonth">
									<div id="thisMonthContent"></div>
								</div>
								<div class="tab-pane " id="allexits">
									<div id="allMonthContent"></div>
								</div>
							</div>
						</div>
						  <!--help text starts here-->
				   <div class="helpblock" style="margin-top:50px;">
                         <div class="alert" role="alert">
                         <div class="alert alert-info alert-dismissible fade in" role="alert">
                         	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  						<p ><i class="fa fa-caret-right" ></i><b>	Settlement </b> &nbsp; - Do not Pay during Notice Period, Settle on the last working month (inclusive of pending pays).</p>
  						<p ><i class="fa fa-caret-right" ></i><b>	Pay </b> &nbsp; - Pay During Notice Period, Settle on the last working month salary.</p>
  						<p ><i class="fa fa-caret-right" ></i><b>	Stop Pay  </b> &nbsp; - Terminates the employee with no salary.</p>
  						<p ><i class="fa fa-caret-right" ></i> &nbsp;This Month exits shows only the employees who are leaving on this month. You can preview the FNF (Full & Final Settlement ) by clicking FNF.</p>
  						<p ><i class="fa fa-caret-right" ></i> &nbsp;It Run Payroll on Preview of the Settlement will run the settlement for the employee which you can't roll back.</p>
  						<p ><i class="fa fa-caret-right" ></i> &nbsp;Clicking FNF for Settlement Ran Employee will download the Settlement PDF which you can distribute to the employee.</p>	
  							</div>
						</div>
						</div>	
						 <!--help text end-->
						  	
					</div>
						  	
						
						
					</section>
			
			</section> 
                <?php include_once (__DIR__."/footer.php");?>
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
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/respond.min.js"></script>


	<!--script for this page only-->
	<script type="text/javascript" src="../js/ckeditor.js"></script>

	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>


	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          jQuery(document).ready(function () {
        	  
  	    	
        	  $('[data-toggle="popover"]').popover();   
        	 	  pendingFlag=0;
            	  pendingExitFlag=0;
            	  $(function(){
            	    	// Javascript to enable link to tab
            	    	 var url = document.location.toString();
            	    	 var tab = "";
            	    	
            	    	      if (url.match('#')) {
                	    	    if(url.split('#')[0].indexOf("?")> 0){
                	    	    tab = url.split('#')[0].substring(0,url.split('#')[0].indexOf("?"));
            	    			noticeIdGet=url.split('#')[0].substr(url.split('#')[0].indexOf("=") + 1);
            	    			  pendingFlag=0;
            	    			  urlData=url.split('#')[0].substr(url.split('#')[0].indexOf("?"));
               if(urlData.slice(1,-1).split('=')[0]!='fnfId'){
            	   pendingExitFlag=0;
            	   viewnoticeStatus(noticeIdGet);
               }else{
            	    $('#runPayroll').data('lastDate','<?php echo $_REQUEST['date'];?>');
            	    SettlementSelect('<?php echo $_REQUEST['fnfId'];?>');
            	  }
                      }else{	tab = url.split('#')[1];
                      
            	    	        }
          	    	          $('#noticeperiodTabs a[href="#'+tab+'"]').tab('show');
          	    	         
          	    	          }else{
            	    	      $('#noticeperiodTabs a[href="#thismonth"]').tab('show');
            	    	    }
            	    	 // Change hash for page-reload
            	    	 $('#noticeperiodTabs a').on('shown.bs.tab', function (e) {
            	    	    window.location.hash = e.target.hash;
            	    	 });
            	      });

            	  $(document).on('shown.bs.tab', "#noticeperiodTabs", function (e) {
            		  // newly activated tab
            		     window.scrollTo(0, 0);
   	                 if($(e.target).data('loaded') === false){
                    	if($(e.target).data('title') == 'thismonth'){
            				currentMonthExit();
            			}else if($(e.target).data('title') == 'allexits'){
                		    pendingExit();
            		    }
            			//make the tab loaded true to prevent re-loading while clicking.
                  		$(e.target).data('loaded',true);
                		}
                	});
      	
          	
              
               var date = new Date();	
          	  // $('#datepickerCls').val(date.getDate() + '/' + (date.getMonth() + 1) + '/' +  date.getFullYear());
           	   $("#hide_from").hide();
           	current_payroll_month='<?php echo $_SESSION['current_payroll_month']?>';

            
                $('.datepickerCls').each(function(){
                    $(this).datetimepicker({
                    	format: 'DD/MM/YYYY',
                    	 maxDate :false,
                    	// minDate: current_payroll_month
                    });
                });
                
                $('#employee_id,#e_affected_ids,#reason,#processType,#e_reason,#e_processType').chosen();
    
          
          });

          $(document).on('click', "#add_notice", function (e) {
              e.preventDefault();
              jQuery('#hide_from').toggle('show');
          });
          
          $(document).on('click', "#cancel", function (e) {
              e.preventDefault();
              jQuery('#hide_from').toggle('hide');
          });

          $(document).on('click', ".viewNotice", function (e) {
              e.preventDefault();
             window.history.pushState("", "", "noticePeriod?nId="+$(this).data('id')+$(this).data('url'));
             viewnoticeStatus($(this).data('id'));
          });
          
          
          $("#datepickerCls").blur(function() {
              setTimeout(function () {
       		 noticeDateset=$('#datepickerCls').val().split('/');
       		  var noticeDate=new Date(noticeDateset[1]+"/"+noticeDateset[0]+"/"+noticeDateset[2]);  
       		  var  resignDate=GetFormattedDate(noticeDate.setDate(noticeDate.getDate() +Number($('#employee_id').find(':selected').attr('data-id'))));
			   	  $('#resignation_date').val(resignDate);
		   	      }, 300);//Get Date Val
		  });
		  
        //For Edit Notice
          $("#e_notice_date").blur(function(){
        	  setTimeout(function () {
        		 noticeDateset=$('#e_notice_date').val().split('/');
        		  var noticeDate=new Date(noticeDateset[1]+"/"+noticeDateset[0]+"/"+noticeDateset[2]);  
        		  var empNoticearray = [<?php echo json_encode($employeeNotice); ?>];
	              var  resignDate=GetFormattedDate(noticeDate.setDate(noticeDate.getDate() +Number($('#e_affected_ids').find(':selected').attr('data-id'))));
	              $('#e_resignation_date').val(resignDate);		   	         
	   	      }, 200);//Get Date Val
          });
          

          $('#edit').on('click', function (e) { 
              e.preventDefault();
              $(this).hide();
              $("#editnotice :input").attr("disabled", false);
              $("#e_reason,#e_processType").prop("disabled", false);  
               $('#e_reason,#e_processType').prop('disabled', false).trigger("chosen:updated");
               CKEDITOR.instances.editor1.setReadOnly(false);
               $('#buttons').show();
               $('#estatus').text();
               if($('#estatus').text()=='Hold'){
                    $('#hold').hide();
                    $('#approve,#decline').show();
                   }else  if($('#estatus').text()=='Pending'){
                    $('#decline,#hold,#approve').show();
                   }
            });
          
          $('#cancel').on('click', function (e) { 
              e.preventDefault();
              $('#buttons').hide();
              $('#edit').show();
              $("#editnotice :input").attr("disabled", true);
              $("#e_reason,#e_processType").prop("disabled", true);  
               $('#e_reason,#e_processType').prop('disabled', true).trigger("chosen:updated");
               CKEDITOR.instances.editor1.setReadOnly(true);
            });

         $('.ticket_list').on('click', function (e) { 
             e.preventDefault();
             var url = document.location.toString();
       	     if(url.split('#')[0]){
  	       	 tab =url.split('#')[0].substring(0,url.split('#')[0].indexOf("?"));
  	       	  $('#noticeperiodTabs a[href="#'+tab+'"]').tab('show');
  	       	  $('#viewContent').hide();
              $('#content').show();
              pendingExit();
       	     }else{	
 	         $('#viewContent').hide();
             $('#content').show();
       	     }
       	   var s =window.location.href;
			var val=removeURLParameter(s,'nId');
			  $('#lrrqSow').html('').hide();
		 window.history.pushState("", document.title,val); 
          });
          
          function viewnoticeStatus(id){
        	  $.ajax({
       		   dataType: 'html',
                   type: "POST",
                   url: "php/noticePeriod.handle.php",
                   cache: false,
                   data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>',nId:id},
                 success: function (data) {
                     jsonobject= JSON.parse(data);
                     if(jsonobject[0]=='success'){
                     $('#viewContent').show();
                     $('#content').hide();
                     $("#editnotice :input").attr("disabled", true);
                     $("#e_reason,#e_processType").prop("disabled", true);  
 	                 $('#e_reason,#e_processType').prop('disabled', true).trigger("chosen:updated");
 	                 $("#e_affected_ids option[value=" +jsonobject[2][0].employee_id + "]").prop("selected", "selected");
       	             $("#e_affected_ids").trigger("chosen:updated");
       	                
                   $('#employeeName').html(" "+jsonobject[2][0].employee_name+'\'s Notice View');
                   $('#notice_id').val(jsonobject[2][0].notice_id);
        	       $('#e_notice_date').val(" "+jsonobject[2][0].notice_date);
                   $('#e_resignation_date').val(" "+jsonobject[2][0].last_working_date);
                   if(jsonobject[2][0].reason){
                   $("#e_reason option[value=" + jsonobject[2][0].reason + "]").prop("selected", "selected");
                   $("#e_reason").trigger("chosen:updated");
                   }
                   if(jsonobject[2][0].process_type!=null){
                   $("#e_processType option[value=" +jsonobject[2][0].process_type + "]").prop("selected", "selected");
                   $("#e_processType").trigger("chosen:updated");
                   }
                   $('#e_remark').val(jsonobject[2][0].remark);
                   
                  if(jsonobject[2][0].status=='P'){
                	  $('#edit').show();
                	  $('#estatus').html('Pending');
                      }else  if(jsonobject[2][0].status=='H'){
                      $('#edit').show();
                	  $('#estatus').html('Hold');
                      }else if(jsonobject[2][0].status=='A'){
                	  $('#estatus').html('Approved');
                	  $('#edit').hide();
                      }else if(jsonobject[2][0].status=='D'){
                    	  $('#estatus').html('Declined');
                    	  $('#edit').hide();
                      }
               
	            	 var noticePeriod = [<?php echo json_encode($employeeNotice); ?>];
  	                 var result = noticePeriod[0].filter(function(v,i) {
		   	        	if(noticePeriod[0][i].employee_id===data[7]){
			   	        	var idVal=noticePeriod[0][i].notice_period;
		   	        		var from = data[2].split("/");
		   	        		var to = data[3].split("/");
		   	        		var newDate = addDays(new Date(from[2], from[1] - 1, from[0]),idVal);
		   	        		var toval=new Date(to[2], to[1] - 1, to[0]);
		   	        	    if(newDate - toval==0){
			   	        		$('#invaliddate').html('Mismatched Date');
  			   	        		}else{
			   	        			$('#invaliddate').html('');
			   	        	}
		   	        	}		   	         
		   	        });
	   	        
  	               $('#ifnoticeempty').hide().html('');
                  CKEDITOR.instances.editor1.setData(jsonobject[2][0].letter_text);
                     }else{
                         $('#content').hide();
                         $('#ifnoticeempty').show().html('Notice Period doesn\'t Exit');
                       }       
              }

                   
             });
        	
           }

          //check Notice Date applied Correctly or not
          function addDays(theDate, days) {
        	    return new Date(theDate.getTime() + days*24*60*60*1000);
        	}
      	
          function currentMonthExit(){
        	  $.ajax({
        		   dataType: 'html',
                    type: "POST",
                    url: "php/noticePeriod.handle.php",
                    cache: false,
                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!currentMonthExit");?>'},
                    beforeSend: function () {
                    	$('#thisMonthContent').addClass('loader'); 
                    },
                 success: function (data) {
                      jsonobject= JSON.parse(data);
                      if(jsonobject[2]=='No Data Found')
                      jsonobject[2]=[];
                      if(jsonobject[0]=='success'){
                      
                          $('#ifnoticeempty').hide();
                          $('#content').show();
                      $('#thisMonthContent').empty();
                      var html = '<section id="flip-scroll"> <table class="table table-striped table-hover table-bordered cf dataTable" id="noticePeriod-sample" style="width:100%;overflow:hidden;"> <thead class="cf"><tr><th>Employee ID</th><th>Notice ID</th><th>Employee Doj</th><th>Name</th><th>Notice Date</th><th>Last Working</th><th>Reason</th><th>Process Type</th><th>Status</th><th>Actions</th></tr></thead><tbody>';               	                	  
               	  for (var i = 0, len = jsonobject[2].length; i < len; ++i) {
               		   html += '<tr>';
               		   $.each(jsonobject[2][i], function (k, v) {
                   		   if(k=='employee_id'){
                   			employeeId=v;
                       		   }
                           if(k=='notice_id'){
                    			noticeId=v;
                        		   }
                		   
              		      if(k=='status'){
                       		statusId=v;
                       		if(statusId=='A'){
                   		 html += '<td><button class="btn btn-success btn-xs">Approved </button></td>';
                       		}else{
                   			 html += '<td><button class="btn btn-success btn-xs">Settlement Runned</button></td>';
                       		 }
                   		}else if(k=='process_type'){
                       		 if(v=='SP'){
                       			 html += '<td>Stop Pay</td>';	
                       			  }else if(v=='P'){
                           			  processType=v;
                       				 html += '<td>Pay</td>';	
                       				 }else if(v=='S'){
                       				  processType=v;
                       				 html += '<td>Settlement</td>';	
                       				 }else{
                       					html += '<td>-</td>';	
                           				 }
                       		 }else if(k=='id'){
                           		 if(statusId=='A'){
                           		 html += '<td><a href="#" title="View"><button class="btn btn-default btn-xs viewNotice" data-url="#thismonth" data-id="'+noticeId+'"><i class="fa fa-eye"></i> View</button></a> &nbsp; <a href="#" data-id='+employeeId+'  class="settlment"  title="Full and Final" ><button class="btn btn-success btn-xs"><i class="fa fa-cloud-upload"></i> FAF</button></a></td>';
                           		 }else{
                           			 html += '<td><button class="btn btn-default btn-xs settlementPdf" data-url="#thismonth"  data-id="'+  processType +'" id="'+employeeId+'" ><i class="fa fa-file-pdf-o"></i>FNF</button></a> &nbsp; </button></td>';
                                	 }}else{
               			  html += '<td>'+v+'</td>';
               			  }
               	       }); 
                    	  html += "</tr>";
                    	}
                	   
                      html += '</tbody> </table></section>';
                       //append table 
                        $('#thisMonthContent').html(html);
                        setTimeout(function(){  
                        $('#noticePeriod-sample').dataTable( {
                       	 "aLengthMenu": [
                                            [10, 15, 20, -1],
                                            [10, 15, 20, "All"] // change per page values here
                                        ],
                            "iDisplayLength": 10,
                            "aoColumnDefs": [
                                             {"bSearchable": false, "bVisible": false, "aTargets": [ 0 ,1,2] }      
                                            ] ,
                            
                              } );
                        },100);
                        $('#thisMonthContent').removeClass('loader');
                        pendingFlag=1;
               }

                 }
              });
              }	
          

        
          function pendingExit(){
        	  $.ajax({
          		   dataType: 'html',
                      type: "POST",
                      url: "php/noticePeriod.handle.php",
                      cache: false,
                      data: { act: '<?php echo base64_encode($_SESSION['company_id']."!pendingExit");?>'},
                      beforeSend: function () {
                      	$('#allMonthContent').addClass('loader'); 
                      },
                    success: function (data) {
                        jsonobject= JSON.parse(data);
                        if(jsonobject[2]=='No Data Found')
                        jsonobject[2]=[];
                        if(jsonobject[0]=='success'){
                            $('#ifnoticeempty').hide();
                            $('#content').show();
                        $('#allMonthContent').empty();
                        var html = '<section id="flip-scroll"> <table class="table table-striped table-hover table-bordered cf dataTable" id="pending-sample"> <thead class="cf"><tr><th>Employee Id</th><th>Notice ID</th><th>Name</th><th>Notice Date</th><th>Last Working</th><th>Reason</th><th>Process Type</th><th>Status</th><th>Actions</th></tr></thead><tbody>';               	                	  
                 	  for (var i = 0, len = jsonobject[2].length; i < len; ++i) {
                 		   html += '<tr>';
                 		   $.each(jsonobject[2][i], function (k, v) {
                 		
                 			  if(k=='employee_id'){
                         			employeeId=v;
                             	}
                     		  if(k=='notice_id'){
                         			noticeidval=v;
                             	}
              				 if(k=='process_type'){
                  				     processType=v;
                            		 if(v=='SP'){
                            			 html += '<td>Stop Pay</td>';	
                            			  }else if(v=='P'){
                            				 html += '<td>Pay</td>';	
                            				 }else if(v=='S'){
                            				 html += '<td>Settlement</td>';	
                            				 }else{
                            					 html += '<td>-</td>';	
                                				 }
                 			  }else if(k=='status'){
                     			  if(v=='P'){
                     				 html += '<td><button class="btn btn-danger btn-xs">Pending</button></td>';
											statusval=v;
                          				 }else if(v=='H'){
                          					statusval=v;
                     					html += '<td><button class="btn btn-primary btn-xs"> Hold</button></td>';	 
                         				 }else if(v=='A'){
                           					statusval=v;
                         					html += '<td><button class="btn btn-success btn-xs"> Approved</button></td>';	 
                             			 }else if(v=='S'){
                            					statusval=v;
                             					html += '<td><button class="btn btn-success btn-xs"> Setteled</button></td>';	 
                                 				 }
                     			  }else if(k=='id'){
                         			 if(statusval=='P'){
                          				 html += '<td><a href="#" title="View"><button class="btn btn-default btn-xs viewNotice" data-url="#allexits"  data-id="'+noticeidval+'"><i class="fa fa-eye"></i> View</button></a></td>';
                          				 }else if(statusval=='H'){
                          					html += '<td><a href="#" title="View"><button class="btn btn-default btn-xs viewNotice" data-url="#allexits"  data-id="'+noticeidval+'"><i class="fa fa-eye"></i> View</button></a></td>';	 
                              				 }
                          				else if(statusval=='A'){
                          					html += '<td><a href="#" title="View"><button class="btn btn-default btn-xs viewNotice" data-url="#allexits"  data-id="'+noticeidval+'"><i class="fa fa-eye"></i> View</button></a></td>';	 
                              				 }
                          				else if(statusval=='S'){
                              				if(jsonobject[2][i].process_type!=='SP'){
                                  				
                          						html += '<td><a href="#" title="View"><button class="btn btn-default btn-xs viewNotice" data-url="#allexits"  data-id="'+noticeidval+'"><i class="fa fa-eye"></i> View</button></a><a href="#" title="Download FNF PDF"><button class="btn btn-default btn-xs settlementPdf"  STYLE="margin-left: 5px" data-url="#allexits"  data-id="'+processType+'" id="'+employeeId+'"><i class="fa fa-file-pdf-o"></i> FNF</button></a></td>';	 
                              				 }else
                                  				html += '<td><a href="#" title="View"><button class="btn btn-default btn-xs viewNotice" data-url="#allexits"  data-id="'+noticeidval+'"><i class="fa fa-eye"></i> View</button></a></td>';
                          				     }
                          			  }else{                 				 
                     			  html += '<td>'+v+'</td>';
                     			  }
                 	       }); 
                      	  html += "</tr>";
                      	}
                  	   
                        html += '</tbody> </table></section>';
                         //append table 
                          $('#allMonthContent').html(html);
                         setTimeout(function(){  
                          var oTable = $('#pending-sample').dataTable( {
                         	 "aLengthMenu": [
                                              [10, 15, 20, -1],
                                              [10, 15, 20, "All"] // change per page values here
                                          ],
                              "iDisplayLength": 10,
                               "aoColumnDefs": [
                                                {"bSearchable": false, "bVisible": false, "aTargets": [ 0 ,1] }      
                                              ] ,
                                } );
                          },100);
                          $('#allMonthContent').removeClass('loader');
                          pendingExitFlag=1;
                         
                    }
                    }

                });
              }

          
            $('#approve,#decline,#hold').on('click', function (e) {
                e.preventDefault();
            	$('.text').remove();
                reasonElment=$('#e_reason').parent().parent().find('label').text();
           	    typeElment=$('#e_processType').parent().parent().find('label').text();
           	    if($('#e_reason :selected').val() && $('#e_processType :selected').val()){
                 var data = CKEDITOR.instances.editor1.getData();
                    $('#letter').val(data);
                	var element=$(this).data('id');
                    $('#e_status').val(element);
                       var idVal=$(this).attr('id');
    	                 $.ajax({
    	                    datatype: "html",
    	                    type: "POST",
    	                    url: "php/noticePeriod.handle.php",
                            cache: false,
    	                    data: $('#editnotice').serialize(),
    	                    beforeSend:function(){
    	                     	$('#'+idVal).button('loading'); 
    	                      },
    	                   complete:function(){
    	                     	 $('#'+idVal).button('reset');
    	                      },
    	                    success: function (data) {
    	                        data1 = JSON.parse(data);
    	                        if (data1[0] == "success") {
    	                           $("#hide_from").hide();
    	                           $('.close').click();
    	                           BootstrapDialog.alert(data1[1]);
    	                           $(this).data('id');
                                           if(element=='H'){
    	                                	  $('#edit').show();
    	 	                             	  $('#buttons').hide();
    	 	                             	$('#estatus').html('Hold');
    	                                   }else if(element=='A' || element=='D'){
    	                                	   $('#buttons,#edit').hide();
    	                                	   var statusVal=(element=='A')?'Approved':'Declined';
    	                                	   $('#estatus').html(statusVal);
    	                                   }
                                           currentMonthExit();
                                           pendingExit(); 
                                           $("#editnotice :input").attr("disabled", true);
                                           $("#e_reason,#e_processType").prop("disabled", true);  
                                           $('#e_reason,#e_processType').prop('disabled', true).trigger("chosen:updated");
                                            CKEDITOR.instances.editor1.setReadOnly(true);
                                   
    	                            }
    	                        else
    	                            if (data1[0] == "error") {
    	                                alert(data1[1]);
    	                            }

    	                    }

    	                });
                            
                }else{
                        ($('#e_reason :selected').val()=='')?
                 			$('#e_reason_chosen').after('<label class="help-block text-danger text">Enter '+reasonElment+'</label>')
                 			:$('#e_reason_chosen').next().remove();
                 	    ($('#e_processType :selected').val()=='')?
                          	 $('#e_processType_chosen').after('<label class="help-block text-danger text">Enter '+typeElment+'</label>')
                          	 :$('#e_processType_chosen').next().remove();
                    
                }
            });

            $('#noticeApprove,#noticeHold').on('click', function (e) {
                e.preventDefault();
                if ($("#notice_date").val() == '' || $("#resignation_date").val() == '') {
                    $('.model_msg0').click();
                    $('#notice_msg').html('Enter All Required Fields');
                }
                else {
                	$('#status').val($(this).data('id'));
	                   var idVal=$(this).attr('id');
	                $.ajax({
                        datatype: "html",
                        type: "POST",
                        url: "php/noticePeriod.handle.php",
                        cache: false,
                        data: $('#notice_add').serialize(),
                        beforeSend:function(){
                         	$('#'+idVal).button('loading'); 
                          },
                          complete:function(){
                         	 $('#'+idVal).button('reset');
                          },
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                $('#hide_from').hide();
                                $('#notice_add')[0].reset();
                                $('#employee_id').trigger('chosen:updated');
                                $('#reason').trigger('chosen:updated');
                                $('#processType').trigger('chosen:updated');
                                currentMonthExit();
                                BootstrapDialog.alert(data1[1]);
                                pendingExit();  
                                                         
                               }
                            else
                                if (data1[0] == "error") {
                                    alert(data1[1]);
                                }

                        }

                    });
                }
            });

            $(document).on('click', "#back", function (e) {
            	 e.preventDefault();
            	 $('#content').show();
           	     $('#settlementPreview').hide();
           	    pendingExit();      
           	var s =window.location.href;
			var val=removeURLParameter(s,'date');
		    window.history.pushState("", document.title,val);  

		    var s =window.location.href;
			var val=removeURLParameter(s,'fnfId');
			window.history.pushState("", document.title,val);                        
              });

            $(document).on('click', ".settlment", function (e) {
            	 e.preventDefault();
                var empID=$(this).data('id');
                   BootstrapDialog.show({
  	                title:'Confirmation',
                      message: 'Are you sure you want to run FnF for the Employee ... ?',// <em> <b>'+$(this).closest('tr').children('td:first').text()+' [ '+ empID +' ]</em> </b>',
                      closable: true,
                      closeByBackdrop: false,
                      closeByKeyboard: false,
                      buttons: [{
                          label: 'OK',
                          cssClass: 'btn-sm btn-success',
                          autospin: true,
                          action: function(dialogRef){
                                    SettlementSelect(empID);
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

            $(document).on('click', "#runPayroll", function (e) {
              	 e.preventDefault();
              	 
              	empIds=$(e.target).data('id');
              	lastDate=$(e.target).data('lastDate');
              	BootstrapDialog.show({
   	               title:'Confirmation',
                      message: 'Are Sure you want To Run Payroll And Take It As Pdf Final </b>',
                      closable: true,
                      closeByBackdrop: false,
                      closeByKeyboard: false,
                      buttons: [{
                          label: 'OK',
                          cssClass: 'btn-sm btn-success',
                          autospin: true,
                          action: function(dialogRef){
                               $.ajax({
                                  dataType: 'html',
                                  type: "POST",
                                  url: "php/payroll.handle.php",
                                  cache: false,
                                  data: {act: '<?php echo base64_encode($_SESSION['company_id']."!run");?>', empFormat: empIds,isexit:1,lastWorkingDate:lastDate},
                                  success: function (data) {
                                	  //document.getElementById("generate_pdf_form").submit();
                                	  pendingExitFlag=0;
                                	  $('.close').click();
                                 		currentMonthExit();
                                	  $('#content').show();
                                 	  $('#settlementPreview').hide();
                                 	 $('#back').click();
                                 
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

               

            //hide/show AllowanceAndDeuctions 
            $(document).on('click', "#grossDeduc", function (e) {
              	 e.preventDefault();
                var element =  $(".sortable").toggle();
           	    $(element).css('display')=='none'?$('#imgChange').html('<i class="fa fa-plus-square  pull-left"></i> General Allowances')
                   	    :$('#imgChange').html('<i class="fa fa-minus-square  pull-left"></i> General Allowances');
           	  });

            $(document).on('dblclick', ".retirementAllow", function (e) {
            	 e.preventDefault();
             	$('.divHide,.saveHide').hide();
            	$('.spanHIde').show();
             	className=$(this).find('span').attr('class');
             	str=className.split(" ");
               	$('#'+str[1]).show();
               	$('#save'+str[1]).popover('destroy');
               	$(this).find('span:first').hide();

               	//optional code
               	var value=$(this).find('span:first').text();
               	$('.'+str[1]).val(value);
              });

            //hide/show AllowanceAndDeuctions 
            $(document).on('click', ".closeInput", function (e) {
              	 e.preventDefault();
              	$('.divHide').hide();
              	$('.spanHIde').show();
            });

            
            $(document).on('blur keyup', ".medium", function (e) {
              	 e.preventDefault();

              	 className=$(this).parent().parent().parent().find('span:first').attr('class');
              	 exixtingVal=Number($(this).parent().parent().parent().find('span:first').text());
              	 updatedVal=Number($(this).parent().parent().find('input').val());
              	 str=className.split(" ");
              	
               	 ajaxval=$('#inputof'+str[01]).val();
               	
              	 var empID=$('#inputofr_gratuity').data('id');
              	 $('.saveHide').hide();
              	 
              	if(exixtingVal!=updatedVal){
                 $('#save'+str[1]).show();
                 if(ajaxval!=updatedVal){
                     $('#editedValue'+str[01]).val(updatedVal.toFixed(2));//user given Gratuity value It has been store into datatbase with remark
             	 $('#save'+str[1]).popover({
                     html: true,
                     title: function () {
                         return $(this).parent().find('.head').html();
                     },
                     content: function () {
                         return $(this).parent().find('.content').html();
                     },placement:'left'
                 });
              	 }else{
                  	 $('#save'+str[1]).popover('destroy');
              	 }
                  }else{
                	  $('#save'+str[1]).popover('destroy');
                	  $('#save'+str[1]).hide();
                      	}
              	
            });

            $(document).on('click', "#calculate", function (e) {
              	 e.preventDefault();
              	ajaxval=$('#inputof'+str[1]).val();
              	var empId=$('#inputofr_gratuity').data('id');
              	var doj=$('#inputofr_gratuity').data('doj');
              	var doe=$('#inputofr_gratuity').data('doe');
              	element=$(this).parent().parent().find('input').val();
              	existingVal=$(this).parent().parent().find('input').val();
              	//$('#editedValue').val(ajaxval);
             		if($('#grautityValue'+str[1]).val()!=element){
             		$.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/noticePeriod.handle.php",
                        cache: false,
                        data: {act: '<?php echo base64_encode($_SESSION['company_id']."!calculateGratuity");?>',employee_id:empId,employee_doj:doj,employee_doe:doe,benefits:str[1]},
                        success: function (data) {
                        	var json_obj = $.parseJSON(data); //parse JSON
                       	$('.'+str[1]).val(Number(json_obj[2].amount));
                       	$('#grautityValue'+str[1]).val(Number(json_obj[2].amount).toFixed(2));
                       	$('#editedValue'+str[1]).val(Number(json_obj[2].amount).toFixed(2));
                       	$('#save'+str[1]).show();
                 		$('#save'+str[1]).popover('destroy');
                 		 $('#inputof'+str[1]).val('');
                        }
                    });
             		}else{
             			$('#save'+str[1]).popover('destroy');
                 		}
             });

            $(document).on('click', ".saveSubmit,.remarksubmit", function (e) {
              	e.preventDefault();
              	str=$(this).data('id');
               var grautityVal= $('#inputof'+str).val();
             	var userGraVal= $('#editedValue'+str).val();
             	var orginalGraVal= $('#grautityValue'+str).val();
             	remarkVal=$('textarea#remark'+str).val();
             	var empId=$('#inputofr_gratuity').data('id');
            	if(grautityVal!==userGraVal){
                 	if(remarkVal!='' && userGraVal!=''){
                 	 $.ajax({
                        dataType: 'html',
                        type: "POST",	
                        url: "php/noticePeriod.handle.php",
                        cache: false,
                        data: {act: '<?php echo base64_encode($_SESSION['company_id']."!updatedGratuity");?>',employee_id:empId,gratuityAmount:userGraVal,remarks:remarkVal,benefits:str},
                        success: function (data) {
                       	$('.divHide').hide();
                          	$('.spanHIde').show();
                          	$('#saver_gratuity').popover('destroy');
                          	$('textarea#remark'+str).val('');
                          	$('.'+str).html(userGraVal);
                          	SettlementSelect(empId);
                            }
                    });
                 	}else if(userGraVal==orginalGraVal){
                      $.ajax({
                            dataType: 'html',
                            type: "POST",
                            url: "php/noticePeriod.handle.php",
                            cache: false,
                            data: {act: '<?php echo base64_encode($_SESSION['company_id']."!updatedGratuity");?>',employee_id:empId,gratuityAmount:userGraVal,remarks:remarkVal,benefits:str},
                            success: function (data) {
                           	$('.divHide').hide();
                              	$('.spanHIde').show();
                              	$('#saver_gratuity').popover('destroy');
                              	$('textarea#remark'+str).val('');
                              	$('.'+str).html(userGraVal);
                              	SettlementSelect(empId);
                               //	$('#inputofGratuity').val(userGraVal);
                            }
                        });
                     	}
                 	}else{
                 		$('.divHide').hide();
                      	$('.spanHIde').show();
                      	$('.r_gratuity').html(userGraVal);
                     	}
               });

            $(document).on('click', ".settlementPdf", function (e) {
             	 e.preventDefault();
             	 $('#employeeId').val($(this).attr('id'));
             	 $('#employeeType').val($(this).attr('data-id'));
                document.getElementById("generate_pdf_form").submit();
           	 });

            $(document).on('click', "#saver_leaveenc", function (e) {
            	 e.preventDefault();
            	 editVal=$('.leaveEnc').val();
            	 oldleavenc=$(this).parent().parent().parent().find('span:first').text();
            	if(oldleavenc!==editVal){
            		     $.ajax({
                               dataType: 'html',
                               type: "POST",
                               url: "php/noticePeriod.handle.php",
                               cache: false,
                               data: {act: '<?php echo base64_encode($_SESSION['company_id']."!updatedGratuity");?>',employee_id:$('#employeeId').val(),gratuityAmount:editVal,remarks:"-",benefits:'r_leaveenc'},
                               success: function (data) {
                              	$('.divHide').hide();
                                 	$('.spanHIde').show();
                                 	$('#saver_gratuity').popover('destroy');
                                 	$('textarea#remark'+str).val('');
                                 	$('.r_leaveenc').html(Number(editVal).toFixed(2));
                                 	SettlementSelect($('#employeeId').val());
                                }
                           });
                	 }else{
                		$('.divHide').hide();
                      	$('.spanHIde').show();
                    	 }
            	 
            	 
            });

            function SettlementSelect(empID){
            	 $.ajax({
                     dataType: 'html',
                     type: "POST",
                     url: "php/noticePeriod.handle.php",
                     cache: false,
                     data: {act: '<?php echo base64_encode($_SESSION['company_id']."!settlementPreview");?>', employee_id: empID },
                     success: function (data) {
                  	   var json_obj = $.parseJSON(data); //parse JSON
                         $('.close').click();
                  	      $('#content').hide();
                   	  $('#settlementPreview').show();
                   	   $.each(json_obj[2], function (k, v) {
                            if(k=='employee_id'){
                               $('#runPayroll').data('id',v);
                               $('#employeeId').val(v);
                               $('#employeeId1').val(v);
                               }
                            if(k=='process_type'){
                          	  $('#employeeType').val(v);
                          	  $('#processId1').val(v);
                                }

                            if(k=='employee_doj'){
                          	  $('#inputofr_gratuity').attr('data-doj',v);
                                }

                            if(k=='last_working_date'){
                          	  $('#inputofr_gratuity').attr('data-doe',v);
                          	$('#runPayroll').data('lastDate',v);
                                }

                            
  				            if(k=='r_gratuity' || k=='r_retrenchment')
  				            {
  				            	 $('#inputof'+k).val(v);
  					            $('#inputofr_gratuity').attr('data-id',empID);
  					        }
  					        
  				          $('.' +k).html((v)?v:"-");
   		                	if(k=='Netpayable'){
   			                	$('.words').html(firstToUpperCase(inWords(Math.round(Number(v)))));
   			                	$('.Netpayable').html(Number(v).toFixed(2));
   		                	}
   		                	
   		                });
                     }
                 });
                 }
            function removeURLParameter(url, parameter) {
			    //prefer to use l.search if you have a location/link object
			    var urlparts= url.split('?');   
			    if (urlparts.length>=2) {

			        var prefix= encodeURIComponent(parameter)+'=';
			        var pars= urlparts[1].split(/[&;]/g);

			        //reverse iteration as may be destructive
			        for (var i= pars.length; i-- > 0;) {    
			            //idiom for string.startsWith
			            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
			                pars.splice(i, 1);
			            }
			        }

			        url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
			        return url;
			    } else {
			        return url;
			    }
			}
   </script>
</body>
</html>

