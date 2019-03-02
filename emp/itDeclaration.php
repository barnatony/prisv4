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

<title>IT Declaration</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../assets/font-awesome/css/font-awesome.css"
	rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="css/table-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<style>
.fileinput-button input {
	position: absolute;
	top: 0px;
	right: 0px;
	margin: 0px;
	opacity: 0;
	font-size: 200px;
	direction: ltr;
	cursor: pointer;
}

.fileinput-button {
	border-color: #78CD51;
	background: #78CD51 none repeat scroll 0% 0%;
	overflow: hidden;
	position: relative;
}
</style>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

	<section id="container">
		<!--header start-->
     <?php include("header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>
      <?php
						echo $employer_id = $_REQUEST ['it_employee_id'];
						echo $employer_name = $_REQUEST ['it_employee_name'];
						
						?>
      <!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading"> IT DECLARATION </header>
					<div class="panel-body">
						<form class="form-inline" name="itDeclaration"
							id="itDeclarationForm" action="itDeclaration.php" role="form"
							method="post">
							<div class="form-group">
								<label class="sr-only" for="it_employee_id">Employee ID</label>
								<input type="text" class="form-control " id="it_employee_id"
									placeholder="Employee ID" required>
							</div>
							<div class="form-group">
								<label class="sr-only" for="it_employee_name">Employee Name</label>
								<input type="text" class="form-control " name="it_employee_name"
									id="it_employee_name" placeholder="Employee Name" required>

							</div>
							<button type="submit" id="go" class="btn btn-success">GO</button>
						</form>
					</div>
				</section>
				<!-- page end-->

				<section class="panel" id="head_ItDeclare1">
					<header class="panel-heading" style="height: 50px;">
						<div id="head_ItDeclare2"></div>
						<div id="head_ItDeclare3">
							<button type="button" class=" btn btn-primary btn-sm pull-right"
								id="edit" style="margin-top: -23px;">
								<i class="fa fa-pencil"></i> Edit
							</button>
							<button type="button" class=" btn btn-danger btn-sm pull-right"
								id="cancel" style="margin-top: -23px;">
								<i class="fa fa-undo"></i> Cancel
							</button>
							<button type="button" class=" btn btn-success  btn-sm pull-right"
								id="save" style="margin-right: 5px; margin-top: -23px;">
								<i class="fa fa-check-square"></i> Update
							</button>
						</div>
					</header>
				</section>

				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
					aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content" style="width: 800px;">
							<div class="modal-header" style="height: 48px;">
								<button type="button" class="close" data-dismiss="modal"
									aria-hidden="true">&times;</button>
								<h4 class="modal-title pull-left">View Your Details</h4>
								</h4>
							</div>
							<div class="modal-body">
								<form enctype="multipart/form-data" class="form-horizontal"
									id="" method="post" action="../php/employee-add.php">
									<fieldset title="Personal" class="step" id="default-step-0">
										<legend></legend>
										<div class="col-lg-12">
											<div class="form-group">
												<div class="col-lg-12">
													<div class="fileupload-new thumbnail"
														style="width: 600px; height: 600px; margin-bottom: 5px; margin-left: 67px;">
														<img id="preview_image"
															src="http://www.placehold.it/600x600/EFEFEF/AAAAAA&amp;text=no+image"
															alt="Employee Image">
													</div>
												</div>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
							<div class="modal-footer">
								<button data-dismiss="modal" class="btn btn-default"
									type="button">Close</button>

							</div>
						</div>
					</div>
				</div>
				<!-- modal -->
				<!--tab nav start-->

				<section class="panel" id="it_declare_tab">
					<div id="myTabs">
						<header class="panel-heading tab-bg-dark-navy-blue ">

							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#previous"
									id="click_previous">Previous Employment Details</a></li>
								<li class=""><a data-toggle="tab" href="#exemption"
									id="click_exemption">Exemptions </a></li>
								<li class=""><a data-toggle="tab" href="#deduction"
									id="click_deduction">Deductions </a></li>
								<li class=""><a data-toggle="tab" href="#housepropertyincome"
									id="click_housepropertyincome">House Property Income</a></li>
								<li class=""><a data-toggle="tab" href="#otherincome"
									id="click_otherincome">Other Income</a></li>
							</ul>
						</header>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="post"
								id="itdeclare_previous_emp">
								<div class="tab-content">
									<div id="previous" class="tab-pane active">
										<div class="col-lg-12" id="itDeclare_previous_employee">

											<div class="col-lg-6" align="left">
												<div class="form-group">

													<label class="col-lg-5 control-label">Employee Code</label>
													<div class="col-lg-7">

														<input type="text" class="form-control "
															name="it_employer_code" id="it_employer_code"
															readonly="true" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label">Employer Name</label>
													<div class="col-lg-7">
														<input type="text" class="form-control "
															name="it_employer_name" id="it_employer_name"
															readonly="true" />
													</div>
												</div>
											</div>
											<div class="col-lg-6" align="right">
												<div class="form-group">
													<label class="col-lg-5 control-label">Employer TAN</label>
													<div class="col-lg-7">
														<input type="text" class="form-control "
															name="it_employer_tan" id="it_employer_tan" required
															readonly="true" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label">Proof</label>
													<div class="col-lg-12">
														<form id="fileupload"
															action="//jquery-file-upload.appspot.com/" method="POST"
															enctype="multipart/form-data">
															<noscript>
																<input type="hidden" name="redirect"
																	value="http://blueimp.github.io/jQuery-File-Upload/">
															</noscript>
															<div class="row fileupload-buttonbar ">
																<div class="col-lg-12">
																	<span
																		class="btn btn-success fileinput-button col-lg-3 attach"
																		style="margin-right: 24px; margin-left: 18px;"> <i
																		class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																		<input type="file" name="files[]" multiple>
																	</span> <a class="btn btn-success view1"
																		data-toggle="modal" href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0; width: 128px;">
																		<i class="fa fa-eye"></i>View
																	</a> <a class="btn btn-success view2"
																		data-toggle="modal" href="#myModal"> <i
																		class="fa fa-eye"></i>View
																	</a>



																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
											<!--form-->
										</div>


										<table
											class="table table-bordered table-striped table-condensed">
											<thead>
												<tr>
													<th>Description</th>
													<th class="numeric" style="width: 140px;">Actual</th>
													<th class="numeric" style="width: 140px;">Approved</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Earnings</td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" name="it_employee_earn"
																id="it_employee_earn" readonly="true">
														</div></td>
													<td class="numeric"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" name="it_employee_code"
																id="it_employee_code" readonly="true" />
														</div></td>
												</tr>
												<tr>
													<td>Tax</td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" value="dxfd"
																name="it_employee_code" id="it_employee_code"
																readonly="true" />
														</div></td>
													<td class="numeric"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span><input
																type="text" class="form-control" name="it_employee_code"
																id="it_employee_code" readonly="true" />
														</div></td>
												</tr>
												<tr>
													<td>PT Amount</td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span><input
																type="text" class="form-control" value="dxfd"
																name="it_employee_code" id="it_employee_code"
																readonly="true" />
														</div></td>
													<td class="numeric"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span><input
																type="text" class="form-control" name="it_employee_code"
																id="it_employee_code" readonly="true" />
														</div></td>
												</tr>
												<tr>
													<td>PF Amount</td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span><input
																type="text" class="form-control" value="dxfd"
																name="it_employee_code" id="it_employee_code"
																readonly="true" />
														</div></td>
													<td class="numeric"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span><input
																type="text" class="form-control" name="it_employee_code"
																id="it_employee_code" readonly="true" />
														</div></td>
												</tr>
											</tbody>
										</table>
										<div class="col-lg-12" id="itDeclare_previous_employee">
											<form class="form-horizontal" role="form" method="post"
												id="itdeclare_previous_emp">
												<div class="col-lg-12" align="left">
													<div class="form-group">
														<label class="col-lg-5 control-label">Remarks</label>
														<div class="col-lg-10">
															<textarea class="form-control " rows="1"
																name="it_exemption_remarks" id="it_exemption_remarks"
																style="width: 860px; height: 82px;" required
																readonly="true"></textarea>
														</div>
													</div>
												</div>
											</form>


											<a href="#exemption"
												class=" btn btn-success pull-right tabNav" data-toggle="tab">
												Next</a>

										</div>
									</div>
									<div id="exemption" class="tab-pane">
										<h5>1.House Rent</h5>
										<div class="col-lg-12" id="itDeclare_exemption_owner">
											<table
												class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th>S.No</th>
														<th class="numeric">Owner Name</th>
														<th class="numeric">Address</th>
														<th class="numeric" style="width: 130px;">PAN</th>
														<th class="numeric " style="width: 110px;">Proof</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1.</td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view4" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>2.</td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view5" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="col-lg-12" id="itDeclare_exemption_house">
											<table
												class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th>Month</th>
														<th class="numeric" style="width: 140px;">Actual</th>
														<th class="numeric" style="width: 140px;">Approved</th>
														<th class="numeric" style="width: 110px;">Proof</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>January</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view6" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																			</button>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>February</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view7" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>March</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view8" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>

																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>April</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view9" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>

																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>May</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view10" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>June</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view11" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>July</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view12" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>August</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view13" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>September</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view14" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>October</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view15" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>November</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view16" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>December</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view17" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class=" row col-md-12" id="itDeclare_previous_employee">
											<form class="form-horizontal" role="form" method="post"
												id="itdeclare_previous_emp">
												<div class="col-lg-6">
													<div class="form-group">
														<label class="col-lg-5 control-label">Remarks</label>
														<div class="col-lg-12">
															<textarea class="form-control " rows="1"
																name="building_name" id="building_name"
																style="width: 839px; height: 82px;" required
																readonly="true"></textarea>
														</div>
													</div>
												</div>
											</form>
										</div>

										<h5>2.Others</h5>
										<div class="col-lg-12" id="itDeclare_exemption_house">
											<table
												class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th class="numeric">Description</th>
														<th class="numeric" style="width: 140px;">Actual</th>
														<th class="numeric" style="width: 140px;">Approved</th>
														<th class="numeric">Remarks</th>
														<th class="numeric" style="width: 110px;">Proof</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Medical Bills</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view18" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>LTA</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view19" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Other Examptions</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view20" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
												</tbody>
											</table>
											<a class="btn btn-primary pull-left tabNav" href="#previous"
												data-toggle="tab">Previous</a><a href="#deduction"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
										</div>
									</div>
									<div id="deduction" class="tab-pane">
										<section id="unseen">
											<table
												class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th>SECTION</th>
														<th>Description</th>
														<th class="numeric" style="width: 140px;">Actual</th>
														<th class="numeric" style="width: 140px;">Approved</th>
														<th class="numeric">Remarks</th>
														<th class="numeric" style="width: 110px;">Proof</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>80C</td>
														<td>FD</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view21" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Tuition Fees</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view22" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>NSC</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view23" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Sukanya Sumriddhi Scheme</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view24" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Infratstructure Bond</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view25" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>VPF/PPF/PF</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view26" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Repayment on Housing Loan</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view27" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>LIC</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view28" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>SIP</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view29" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Mutual Funds</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view30" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>NPS</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view31" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80C</td>
														<td>ELSS</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view32" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80CCD-1B</td>
														<td>NPS</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view33" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80CCG</td>
														<td>RGESS (50% of the investment)</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach ">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view34" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80D</td>
														<td>Medical Insurance Self</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view35" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80D</td>
														<td>Medical Insurance Parents</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view36" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80D</td>
														<td>Preventive Medical Checkup</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view37" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80DD</td>
														<td>Medical treatment for severe disability</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view38" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80DD</td>
														<td>Maint. & Medi treatment for Physical disability</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view39" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80DDB</td>
														<td>Med Treatment Exp. Spec disease Very Sr.Citizen</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view40" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80DDB</td>
														<td>Med Treatment Exp. for Spec.disease Sr Citizen</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view41" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80E</td>
														<td>Interest on Education Loan</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view42" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80G</td>
														<td>Donation</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view43" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80TTA</td>
														<td>Interest on Saving Account</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view44" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>80U</td>
														<td>Medical Expenses for Special Desease</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view45" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
												</tbody>
											</table>
											<a class="btn btn-primary pull-left tabNav" href="#exemption"
												data-toggle="tab">Previous</a><a href="#housepropertyincome"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
										</section>
									</div>
									<div id="housepropertyincome" class="tab-pane">
										<h5>1.Self-Occupied Property</h5>
										<div class="col-lg-12" id="itDeclare_exemption_owner">
											<table
												class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th>Destription</th>
														<th class="numeric" style="width: 140px;">Actual</th>
														<th class="numeric" style="width: 140px;">Approval</th>
														<th class="numeric">Remarks</th>
														<th class="numeric " style="width: 110px;">Proof</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Interest Income</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view46" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
												</tbody>
											</table>
										</div>
										<h5>2.Let-out Property</h5>
										<div class="col-lg-12" id="itDeclare_exemption_owner">
											<table
												class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th>Destription</th>
														<th class="numeric " style="width: 140px;">Actual</th>
														<th class="numeric" style="width: 140px;">Approval</th>
														<th class="numeric">Remarks</th>
														<th class="numeric " style="width: 110px;">Proof</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Annual Rent Received/Receivable</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view47" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Municiple tax Paid</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view48" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Unrealized Rent</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view49" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Interest on Housing Loan</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view50" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
												</tbody>
											</table>
											<a class="btn btn-primary pull-left tabNav" href="#deduction"
												data-toggle="tab">Previous</a><a href="#otherincome"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
										</div>
									</div>
									<div id="otherincome" class="tab-pane">
										<div class="col-lg-12" id="itDeclare_exemption_owner">
											<table
												class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th>Destription</th>
														<th class="numeric" style="width: 140px">Actual</th>
														<th class="numeric" style="width: 140px">Approval</th>
														<th class="numeric">Remarks</th>
														<th class="numeric " style="width: 110px;">Proof</th>

													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Interest Income</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="abi"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view51" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Lottery/Race income</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="abi"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view52" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Sub-Letting Income</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view53" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Land Rental income</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view54" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
													<tr>
														<td>Other Income</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" value="dxfd"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="it_employee_code" id="it_employee_code"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<form id="fileupload"
																	action="//jquery-file-upload.appspot.com/"
																	method="POST" enctype="multipart/form-data">
																	<noscript>
																		<input type="hidden" name="redirect"
																			value="http://blueimp.github.io/jQuery-File-Upload/">
																	</noscript>
																	<div class="row fileupload-buttonbar">
																		<div class="col-lg-5 attach">
																			<span class="btn btn-success fileinput-button"> <i
																				class="fa fa-paperclip"></i> <input type="file"
																				name="files[]" multiple>
																			</span>
																		</div>
																		<div class="col-lg-5">
																			<a class="btn btn-success view55" data-toggle="modal"
																				href="#myModal"> <i class="fa fa-eye"></i></a>
																		</div>
																	</div>
																</form>
															</div></td>
													</tr>
												</tbody>
											</table>
											<a class="btn btn-primary pull-left tabNav"
												href="#housepropertyincome" data-toggle="tab">Previous</a><input
												name="submitForm" id="submitForm"
												class="finish btn btn-danger" value="Finish" type="button">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>


				</section>
				<!--tab nav end-->









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
	<script src="../js/respond.min.js"></script>

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>

	<!--script for this page only-->
	<script src="../assets/data-tables/ZeroClipboard.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          $(document).ready(function () {
              $('#head_ItDeclare1,#it_declare_tab,#head_ItDeclare2,#head_ItDeclare3').hide();
              
              $(".table").find(":input").each(function () {
                  $(this).attr('readonly', true);
              });

              $(".attach,#save,#cancel,.view1").hide();
              $("#edit").click(function () {
                  $("#edit,.view2").hide();
                  $(".attach,#save,#cancel,.view1").show();
                  $("#itdeclare_previous_emp,#it_exemption_remarks").find(":input").each(function () {
                      $(this).removeAttr('readonly');
                  });
                  $(".table,#it_employee_code,#it_employer_name,#it_employer_tan").find(":input").each(function () {
                      $(this).attr('readonly', false);
                  });
                  $(".actual").find(":input").each(function () {
                      $(this).attr('readonly', true);
                  });
              });
              $("#cancel").click(function () {
                  $("#save,#cancel,.attach,.view1").hide();
                  $("#edit,.view2").show();
                  $(".table,#itdeclare_previous_emp,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                      $(this).attr('readonly', true);
                  });
              });
              $("#save").click(function () {
                  $("#save,#cancel,.attach,.view1").hide();
                  $(".view2,#edit").show();
                  $(".table,#itdeclare_previous_emp,#it_exemption_remarks").find(":input").each(function () {
                      $(this).attr('readonly', true);
                  });
              });
              $('#itDeclarationForm').submit(function (e) {
                  $('#it_declare_tab,#head_ItDeclare1,#head_ItDeclare2,#head_ItDeclare3').show();
                  $('#head_ItDeclare2').html($("#it_employee_name").val() + " 's It Declaration  ");
                  e.preventDefault();
              });


          });
          $('.tabNav').on('click', function () {
              var menu = $(this).attr('href');
              menu = menu.split('#');
              $('#click_' + menu[1]).parent().siblings().each(function () {
                  $(this).removeClass('active');
              });
              $('#click_' + menu[1]).parent().addClass('active');
          });
      </script>




</body>
</html>
