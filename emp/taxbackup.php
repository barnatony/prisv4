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

<title>Tax Planner</title>

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

$employee_id = $_SESSION ['employee_id'];
?>

<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading"> TAX PLANNER </header>
					<div class="panel-body">
						<form class="form-inline" id="itDeclarationForm" role="form"
							method="get">
							<input type="button" id="tax_plan" class="btn btn-success"
								value="Tax Declares"> <input type="button" id="view_summery"
								class="btn btn-success" value="Summary">
						</form>
					</div>
				</section>
				<!-- page end-->

				<!--  <section class="panel" id="head_ItDeclare1" >
        <header class="panel-heading"  style="height: 50px;">
            <div id="head_ItDeclare2"></div>
            <div id="head_ItDeclare3">
            <button type="button" class=" btn btn-primary btn-sm pull-right" id="edit" style="margin-top: -23px;"><i class="fa fa-pencil"></i>  Edit</button>
            <button type="button" class=" btn btn-danger btn-sm pull-right" id="cancel" style="margin-top: -23px;"><i class="fa fa-undo"></i>  Cancel</button>
                <input type="submit" class=" btn btn-success  btn-sm pull-right" id="save" style="margin-right: 5px;margin-top: -23px;" name="submit" id="submit" value="Update">
            </div>
        </header>                                    
    </section>
-->
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
								<fieldset title="Personal" class="step" id="default-step-0">
									<legend></legend>
									<div class="col-lg-12">
										<div class="form-group">
											<div class="col-lg-12">
												<div class="fileupload-new thumbnail"
													style="width: 672px; height: 920px; margin-bottom: 5px; margin-left: 10px;">
													<img id="preview_image"
														src="http://www.placehold.it/133x170/EFEFEF/AAAAAA&amp;text=no+image"
														alt="Employee Image">
												</div>
											</div>
										</div>
									</div>
								</fieldset>

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
				<section class="panel" id="it_declare_summery">
					<div id="myTabs">
						<header class="panel-heading tab-bg-dark-navy-blue ">

							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#summery"
									id="click_previous">Summary</a></li>
								<li class=""><a data-toggle="tab" href="#form12ba"
									id="click_exemption">Form 12BA</a></li>
								<li class=""><a data-toggle="tab" href="#partB"
									id="click_exemption">Part B</a></li>


							</ul>
						</header>
						<div class="panel-body">
							<div class="tab-content">
								<!--part B-->
								<div id="partB" class="tab-pane">
									<div class="col-sm-12">
										<header class="panel-heading"> PART B ( Annexure to
											Form.No.16) </header>
										<table
											class="table table-bordered table-striped table-condensed">
											<thead>

												<tr>

													<th class="numeric">Details Of all deducted</th>
													<th class="numeric"><i class="fa fa-rupee"></i></th>
													<th class="numeric"><i class="fa fa-rupee"></i></th>
													<th class="numeric"><i class="fa fa-rupee"></i></th>

												</tr>

											</thead>
											<tbody>



												<tr>
													<td colspan="4">( i ) Gross Salary</td>
												</tr>
												<tr>
													<td class="numeric" style="padding-left: 31px;">(a) Salary
														as per provisions contained in sec 17 (1)</td>
													<td class="numeric"><span class="salary_month"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>

												<tr>
													<td class="numeric" style="padding-left: 31px;">(b) Value
														of perquisites u/s 17(2) ( as per Form No.12BA, wherever
														applicable )</td>
													<td class="numeric"><span class="val_perq"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>
												<tr>
													<td class="numeric" style="padding-left: 31px;">(c) Profits
														in lieu of salary under section 17(3) 9 as per Form
														No.12BA,wherever applicable )</td>
													<td class="numeric"><span class="lieu"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>

												<tr>
													<td class="numeric" style="padding-left: 35px;">Total</td>
													<td class="numeric"></td>
													<td class="numeric"><span class="tot_perq"></span></td>
													<td class="numeric"></td>
												</tr>



												<tr>
													<td colspan="4">( ii ) Less Allowance to the extent exempt
														u/s 10</td>
												</tr>
												<tr>
													<td style="padding-left: 31px;">HRA</td>
													<td class="numeric"><span class="exe_hra"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>
												<tr>
													<td style="padding-left: 31px;">LTA</td>
													<td class="numeric"><span class="exe_ita"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>
												<tr>
													<td style="padding-left: 31px;">Others</td>
													<td class="numeric"><span class="exe_oth"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>
												<tr>
													<td style="padding-left: 31px;"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="exemption"></span></td>
													<td class="numeric"></td>
												</tr>

												<tr>
													<td>( iii ) Balance (1-2)</td>
													<td class="numeric"></td>
													<td class="numeric"><span class="balance"></span></td>
													<td class="numeric"></td>
												</tr>
												<tr>
													<td colspan="4">( iv ) Deduction</td>
												</tr>
												<tr>
													<td class="numeric" style="padding-left: 31px;">(a)
														Entertainment allowance</td>
													<td class="numeric"><span class="entertain"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>
												<tr>
													<td class="numeric" style="padding-left: 31px;">(b) Tax on
														Employment</td>
													<td class="numeric"><span class="pro_tax_paid"></span></td>
													<td class="numeric"></td>
													<td class="numeric"></td>
												</tr>

												<tr>
													<td></td>

													<td class="numeric"></td>
													<td class="numeric"><span class="aggregate"></span></td>
													<td class="numeric"></td>
												</tr>


												<tr>
													<td>( v ) Income chargable under the head 'salaries' (3-4)</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="income_charable"></span></td>
												</tr>


												<tr>
													<td>( vi ) Add any other income reported by the employee</td>

													<td class="numeric"></td>
													<td class="numeric"><span class="any_income"></span></td>
													<td class="numeric"></td>
												</tr>


												<tr>
													<td>( vii ) Gross total income (5+6)</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="gross_s"></span></td>
												</tr>

												<tr>
													<td>( vii ) Deduction under Chapter VI-A
													
													</th>
													</td>
													<td class="numeric"></td>
													<td class="numeric">Gross Amount</td>
													<td class="numeric">Deductible Amount</td>

												</tr>
												<tr>
													<td style="padding-left: 35px;">a) section 80C</td>
													<td class="numeric"></td>
													<td class="numeric"><span class="_80c"></span></td>
													<td class="numeric"><span class="ded_80c"></span></td>
												</tr>

												<tr>
													<td style="padding-left: 35px;">b) section 80D</td>
													<td class="numeric"></td>
													<td class="numeric"><span class="_80d"></span></td>
													<td class="numeric"><span class="ded_80d"></span></td>

												</tr>
												<tr>
													<td style="padding-left: 35px;">c) section 80E</td>
													<td class="numeric"></td>
													<td class="numeric"><span class="_80e"></span></td>
													<td class="numeric"><span class="ded_80e"></span></td>
												</tr>
												<tr>
													<td style="padding-left: 35px;">d) section 80G</td>
													<td class="numeric"></td>
													<td class="numeric"><span class="_80g"></span></td>
													<td class="numeric"><span class="ded_80g"></span></td>
												</tr>
												<tr>
													<td style="padding-left: 35px;">e)Other Deduction</td>
													<td class="numeric"></td>
													<td class="numeric"><span class="other"></span></td>
													<td class="numeric"><span class="ded_other"></span></td>
												</tr>



												<tr>
													<td>( ix ) Aggregate of deductible amount under Chapter
														VI-A</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="aggregate_dec "></span></td>
												</tr>

												<tr>
													<td>( x ) Total Income (7-9)</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="tot_dec"></span></td>
												</tr>
												<tr>
													<td>( xi ) Tax on total income</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="tax"></span></td>
												</tr>
												<tr>
													<td>( xii ) surcharge @ 10 % (on tax computed at S.No.11)</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="surcharge"></span></td>
												</tr>
												<tr>
													<td>( xii ) EC @ 2% (on tax computed at S.No.11)</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="ec"></span></td>
												</tr>
												<tr>
													<td>( xiv ) SHEC @ 1% (on tax computed at S.No.11)</td>
													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="shec"></span></td>

												</tr>



												<tr>
													<td>( xv ) Tax payable (11+12+13+14)</td>
													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="tax_payable"></span></td>

												</tr>
												<tr>
													<td>( xvi ) Less:Relief under section 89</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="relief"></span></td>
												</tr>
												<tr>
													<td>( xvii ) Tax payable (15-16)</td>

													<td class="numeric"></td>
													<td class="numeric"></td>
													<td class="numeric"><span class="tax_payable_tot"></span></td>
												</tr>
											</tbody>
										</table>

										</form>
									</div>
								</div>

								<!--Form 12B-->
								<div id="form12ba" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="">


										<section id="unseen">
											<div class="col-sm-12">
												<header class="panel-heading"> Statment of perqs </header>
												<table class="table table-hover">
													<thead>

														<tr>

															<th class="numeric" style="padding-left: 31px;">Perquisites</th>
															<th class="numeric">Value</th>
															<th class="numeric">Recovered</th>
															<th class="numeric">Taxable</th>

														</tr>
													</thead>
													<tbody>


														<tr>

															<td class="numeric" style="padding-left: 31px;">Accommodation</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Cars/Other
																automotive</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Sweeper,
																gardener, watchman or personal attendent</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Gas,
																electricity, water</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">interest,free
																or concessional loans</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Holiday
																expenses</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Free or
																concessional travel</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Free
																meals</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Free
																Education</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Gifts,
																vouchers etc.</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric" style="padding-left: 31px;">Credit
																card expenses</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric" style="padding-left: 31px;">Club
																expenses</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric" style="padding-left: 31px;">Use of
																movable assets by employees</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric" style="padding-left: 31px;">Transfer
																of assets to employees</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric" style="padding-left: 31px;">Value of
																any other benefit/amenity/service/privilege</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Stock
																options ( non-qualified options )</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 31px;">Other
																benefits or amenties</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric" style="padding-left: 45px;">Total
																Value of perquisites</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

													</tbody>
												</table>
											</div>
										</section>
									</form>
								</div>

								<div id="summery" class="tab-pane active">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="">


										<section id="unseen">
											<div class="col-sm-12">
												<section class="panel">
													<header class="panel-heading"> Income Summary </header>
													<table class="table table-hover ">
														<thead>
															<tr>
																<th>Particulars</th>
																<th>Amount(INR)</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>( i ) Benefits Paid</td>
															</tr>
                                <?php
																																$stmt = "SELECT * FROM  company_pay_structure WHERE type ='A' && display_flag ='1' ";
																																$result = mysqli_query ( $conn, $stmt );
																																
																																while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
																																	echo "<tr><td style='padding-left: 31px;' >
            " . $row ['display_name'] . "</td><td><span class=" . $row ['pay_structure_id'] . "></span></td></tr>";
																																}
																																?>                      
                              
                          <tr>
																<td style="padding-left: 31px;">Incentive</td>
																<td><span class="incentive"></span></td>
															</tr>

															<tr>
																<td style="padding-left: 31px;">Bonus</td>
																<td><span class="bonus"></span></td>
															</tr>
															<tr>
																<td style="padding-left: 31px;">Others</td>
																<td><span class="others"></span></td>
															</tr>

															<tr>
																<td style="padding-left: 31px;">Perquisites</td>
																<td><span class="perq"></span></td>
															</tr>
															<tr>
																<td style="padding-left: 31px;">Previous Employer Salary</td>
																<td><span class="prev_earnings_app"></span></td>
															</tr>
															<tr>
																<td style="padding-left: 31px;">Gross</td>
																<td><span class="gross"></span></td>
															</tr>
															<tr>
																<td>( ii ) House Property Income</td>
																<td><span class="house_prop_inc"></span></td>
															</tr>
															<tr>
																<td>( iii ) Income form other Sources</td>
																<td><span class="other_income"></span></td>
															</tr>
															<tr>
																<td style="padding-left: 35px;">Total Income</td>
																<td><span class="Tot_income"></span></td>
															</tr>

														</tbody>
													</table>
												</section>
											</div>

										</section>

									</form>
								</div>


							</div>
						</div>



					</div>
				</section>

				<section class="panel" id="it_declare_tab">
					<div id="myTabs">
						<header class="panel-heading tab-bg-dark-navy-blue ">

							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#previous"
									id="click_previous">Previous Employment</a></li>
								<li class=""><a data-toggle="tab" href="#housepropertyincome"
									id="click_housepropertyincome">House Property Income</a></li>
								<li class=""><a data-toggle="tab" href="#otherincome"
									id="click_otherincome">Other Income</a></li>
								<li class=""><a data-toggle="tab" href="#exemption"
									id="click_exemption">HRA </a></li>
								<li class=""><a data-toggle="tab" href="#other_exemption"
									id="click_other_exemption">Other Exemptions </a></li>

								<li class=""><a data-toggle="tab" href="#deduction"
									id="click_deduction">80C </a></li>
								<li class=""><a data-toggle="tab" href="#80C" id="click_80C">80D
								</a></li>
								<li class=""><a data-toggle="tab" href="#other_deduction"
									id="click_other_deduction">Other Deductions </a></li>


							</ul>
						</header>
						<div class="panel-body">
							<div class="tab-content">
								<div id="previous" class="tab-pane active">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="itdeclare_previous_emp">

										<div class="col-lg-12" id="itDeclare_previous_employee">
											<br>
											<div class="col-lg-12">

												<button type="button"
													class=" btn btn-primary btn-sm pull-right edit"
													style="margin-top: -23px;">
													<i class="fa fa-pencil"></i> Edit
												</button>

											</div>
											<br>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="col-lg-5 control-label">Employee Code</label>
													<div class="col-lg-7">
														<input type="hidden" class="form-control "
															id="privious_id" name="employee_id"
															placeholder="Employee ID" required> <input type="text"
															class="form-control " name="prev_emp_code"
															id="prev_emp_code" readonly="true" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label">Employer Name</label>
													<div class="col-lg-7">
														<input type="text" class="form-control "
															name="prev_employer_name" id="prev_employer_name"
															readonly="true" />
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="col-lg-5 control-label">Employer TAN</label>
													<div class="col-lg-7">
														<input type="text" class="form-control "
															name="prev_employer_tan" id="prev_employer_tan" required
															readonly="true" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-5 control-label">Proof</label>
													<div class="col-lg-7">
														<div class="row fileupload-buttonbar ">
															<div class="col-lg-6">
																<span class="btn btn-success fileinput-button attach"> <i
																	class="glyphicon glyphicon-plus"></i> <span>Add files</span>
																	<input type="file" name="prev_employer_proof"
																	class="imagechange">
																</span>
															</div>
															<div class="col-lg-6">
																<a class="btn btn-success view" data-toggle="modal"
																	href="#myModal"
																	style="background-color: #41CAC0; border-color: #41CAC0; width: 128px;">
																	<input type="hidden" class="form-control"
																	id="prev_employer_proof"
																	value="../compDat/resized_income-tax-letter.jpg" /> <i
																	class="fa fa-eye"></i>View
																</a>
															</div>
														</div>

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
													<td class="numeric "><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control"
																name="prev_earnings_act" id="prev_earnings_act"
																readonly="true">
														</div></td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control"
																name="prev_earnings_app" id="prev_earnings_app"
																readonly="true" />
														</div></td>
												</tr>
												<tr>
													<td>Tax</td>
													<td class="numeric "><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" name="prev_tax_act"
																id="prev_tax_act" readonly="true" />
														</div></td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" name="prev_tax_app"
																id="prev_tax_app" readonly="true" />
														</div></td>
												</tr>
												<tr>
													<td>PT Amount</td>
													<td class="numeric "><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" name="prev_pt_act"
																id="prev_pt_act" readonly="true" />
														</div></td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" name="prev_pt_app"
																id="prev_pt_app" readonly="true" />
														</div></td>
												</tr>
												<tr>
													<td>PF Amount</td>
													<td class="numeric "><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" value="dxfd"
																name="prev_pf_act" id="prev_pf_act" readonly="true" />
														</div></td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon">₹</span> <input
																type="text" class="form-control" name="prev_pf_app"
																id="prev_pf_app" readonly="true" />
														</div></td>
												</tr>
											</tbody>
										</table>
										<div class="col-lg-12" id="itDeclare_previous_employee">

											<div class="col-lg-12" align="left">
												<div class="form-group">
													<label class="col-lg-5 control-label">Remarks</label>
													<div class="col-lg-10">
														<textarea class="form-control " rows="1"
															name="prev_emp_remarks" id="prev_emp_remarks"
															style="width: 860px; height: 82px;" required
															readonly="true"></textarea>
													</div>
												</div>
											</div>


											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel"
												style="margin-top: -23px;">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px; margin-top: -23px;" name="submit"
												id="submit" value="Update"> <a href="#exemption"
												class=" btn btn-success pull-right tabNav" data-toggle="tab">
												Next</a>

										</div>

									</form>
								</div>



								<!--For HRA-->

								<div id="exemption" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="exemption_form_update">
										<input type="hidden" class="form-control " id="hra_id"
											name="employee_id" placeholder="Employee ID" required> <br>
										<div class="col-lg-12">

											<button type="button"
												class=" btn btn-primary btn-sm pull-right edit"
												style="margin-top: -23px;">
												<i class="fa fa-pencil"></i> Edit
											</button>
										</div>

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
																<input type="text" class="form-control"
																	name="owner1_name" id="owner1_name" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="owner1_addr" id="owner1_addr" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="owner1_pan" id="owner1_pan" readonly="true" />
															</div></td>
														<td class="numeric"><div>

																<div class="row fileupload-buttonbar">
																	<div class="col-lg-5 attach">
																		<span class="btn btn-success fileinput-button"> <i
																			class="fa fa-paperclip"></i> <input type="file"
																			name="owner1_proof" class="imagechange">
																		<!--owner1_proof-->
																		</span>
																	</div>
																	<div class="col-lg-5">
																		<a class="btn btn-success view" data-toggle="modal"
																			href="#myModal"
																			style="background-color: #41CAC0; border-color: #41CAC0;">
																			<input type="hidden" class="form-control "
																			id="owner1_proof" /><i class="fa fa-eye"></i>
																		</a>
																	</div>
																</div>


															</div></td>
													</tr>
													<tr>
														<td>2.</td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="owner2_name" id="owner2_name" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="owner2_addr" id="owner2_addr" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="owner2_pan" id="owner2_pan" readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="owner2_proof" class="imagechange">
																	<!--owner2_proof-->
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="owner2_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
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

													<td>April</td>
													<td class="numeric "><div class="input-group m-bot15">
															<span class="input-group-addon"><i class="fa fa-rupee"> </i></span><input
																type="text" class="form-control" value="dxfd"
																name="apr_act" id="apr_act" readonly="true" />
														</div></td>
													<td class="numeric actual"><div class="input-group m-bot15">
															<span class="input-group-addon"><i class="fa fa-rupee"> </i></span><input
																type="text" class="form-control" name="apr_app"
																id="apr_app" readonly="true" />
														</div></td>
													<td class="numeric"><div>
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="apr_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="apr_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</div></td>
													</tr>
													<tr>
														<td>May</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"> <i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" name="may_act"
																	id="may_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="may_app" id="may_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<div class="row fileupload-buttonbar">
																	<div class="col-lg-5 attach">
																		<span class="btn btn-success fileinput-button"> <i
																			class="fa fa-paperclip"></i> <input type="file"
																			name="may_proof" class="imagechange">
																		</span>
																	</div>
																	<div class="col-lg-5">
																		<a class="btn btn-success view" data-toggle="modal"
																			href="#myModal"
																			style="background-color: #41CAC0; border-color: #41CAC0;">
																			<input type="hidden" class="form-control "
																			id="may_proof" /> <i class="fa fa-eye"></i>
																		</a>
																	</div>
																</div>

															</div></td>
													</tr>
													<tr>
														<td>June</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="jun_act" id="jun_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="jun_app" id="jun_app" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="jun_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="jun_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>July</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="jul_act" id="jul_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="jul_app" id="jul_app" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="jul_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="jul_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>August</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	value="dxfd" name="aug_act" id="aug_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="aug_app" id="aug_app" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="aug_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="aug_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>September</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	value="dxfd" name="sep_act" id="sep_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="sep_app" id="sep_app" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="sep_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="sep_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>October</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="oct_act" id="oct_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="oct_app" id="oct_app" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="oct_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="oct_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>November</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="nov_act" id="nov_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="nov_app" id="nov_app" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="nov_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="nov_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>December</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="dec_act" id="dec_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="dec_app" id="dec_app" readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="dec_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="dec_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>


														</td>
													</tr>


													<tr>
														<td>January</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	value="dxfd" name="jan_act" id="jan_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="jan_app" id="jan_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<div class="row fileupload-buttonbar">
																	<div class="col-lg-5 attach">
																		<span class="btn btn-success fileinput-button"> <i
																			class="fa fa-paperclip"></i> <input type="file"
																			name="jan_proof" class="imagechange">
																		</span>
																	</div>
																	<div class="col-lg-5">
																		<a class="btn btn-success view" data-toggle="modal"
																			href="#myModal"
																			style="background-color: #41CAC0; border-color: #41CAC0;">
																			<input type="hidden" class="form-control "
																			id="jan_proof" /> <i class="fa fa-eye"></i>
																		</a>
																	</div>
																</div>

															</div></td>
													</tr>
													<tr>
														<td>February</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"> <i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" name="feb_act"
																	id="feb_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"> <i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" name="feb_app"
																	id="feb_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<div class="row fileupload-buttonbar">
																	<div class="col-lg-5 attach">
																		<span class="btn btn-success fileinput-button"> <i
																			class="fa fa-paperclip"></i> <input type="file"
																			name="feb_proof" class="imagechange">
																		</span>
																	</div>
																	<div class="col-lg-5">
																		<a class="btn btn-success view" data-toggle="modal"
																			href="#myModal"
																			style="background-color: #41CAC0; border-color: #41CAC0;">
																			<input type="hidden" class="form-control "
																			id="feb_proof" /> <i class="fa fa-eye"></i>
																		</a>
																	</div>
																</div>

															</div></td>
													</tr>
													<tr>
														<td>March</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="mar_act" id="mar_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="mar_app" id="mar_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<div class="row fileupload-buttonbar">
																	<div class="col-lg-5 attach">
																		<span class="btn btn-success fileinput-button"> <i
																			class="fa fa-paperclip"></i> <input type="file"
																			name="mar_proof" class="imagechange">
																		</span>
																	</div>
																	<div class="col-lg-5">
																		<a class="btn btn-success view" data-toggle="modal"
																			href="#myModal"
																			style="background-color: #41CAC0; border-color: #41CAC0;">
																			<input type="hidden" class="form-control "
																			id="mar_proof" /> <i class="fa fa-eye"></i>
																		</a>
																	</div>
																</div>

															</div></td>
													</tr>
													<tr>
												
												</tbody>
											</table>
										</div>
										<div class=" row col-md-12" id="itDeclare_previous_employee">

											<div class="col-lg-6">
												<div class="form-group">
													<label class="col-lg-5 control-label">Remarks</label>
													<div class="col-lg-12">
														<textarea class="form-control " rows="1"
															name="rent_remarks" id="rent_remarks"
															style="width: 839px; height: 82px;" required
															readonly="true"></textarea>
													</div>
												</div>
											</div>

										</div>

										<div class="col-lg-12" id="itDeclare_exemption_house">
											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px;" name="submit" id="submit"
												value="Update"> <a class="btn btn-primary pull-left tabNav"
												href="#previous" data-toggle="tab">Previous</a> <a
												href="#other_exemption"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
										</div>
									</form>
								</div>



								<!--For other_exemption-->
								<div id="other_exemption" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="other_exemption_update">
										<input type="hidden" class="form-control " id="exemption_id"
											name="employee_id" placeholder="Employee ID" required>


										<section id="unseen">
											<br>
											<div class="col-lg-12">
												<button type="button"
													class=" btn btn-primary btn-sm pull-right edit"
													style="margin-top: -23px;">
													<i class="fa fa-pencil"></i> Edit
												</button>
											</div>
											<h5>2.Others</h5>

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
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="medical_act" id="medical_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="medical_app" id="medical_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="medical_remarks" id="medical_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">

															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="medical_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="medical_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>


														</td>
													</tr>
													<tr>
														<td>LTA</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="lta_act" id="lta_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="lta_app" id="lta_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="lta_remarks" id="lta_remarks" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="lta_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="lta_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>Travel conveyance</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name=trc_act " id="trc_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="trc_app" id="trc_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="trc_remarks" id="trc_remarks" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="trc_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="trc_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>Other Examptions</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="others_act" id="others_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="others_app" id="others_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="others_remarks" id="others_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="others_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="others_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>

												</tbody>
											</table>
											<a class="btn btn-primary pull-left tabNav" href="#exemption"
												data-toggle="tab">Previous</a> <a href="#deduction"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px;" name="submit" id="submit"
												value="Update">
									
									</form>
								</div>



								<!--For Deduction-->
								<div id="deduction" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="deduction_form_update">
										<input type="hidden" class="form-control " id="80d_id"
											name="employee_id" placeholder="Employee ID" required>

										<section id="unseen">
											<br>
											<div class="col-lg-12">
												<button type="button"
													class=" btn btn-primary btn-sm pull-right edit"
													style="margin-top: -23px;">
													<i class="fa fa-pencil"></i> Edit
												</button>
											</div>
											<br>
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
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="80c_fd_act" id="80c_fd_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="80c_fd_app" id="80c_fd_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_fd_remarks" id="80c_fd_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_fd_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_fd_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Tuition Fees</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	value="dxfd" name="80c_tution_act" id="80c_tution_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80c_tution_app" id="80c_tution_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_tution_remarks" id="80c_tution_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">




															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_tution_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_tution_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>NSC</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	value="dxfd" name="80c_nsc_act" id="80c_nsc_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80c_nsc_app" id="80c_nsc_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_nsc_remarks" id="80c_nsc_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">



															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_nsc_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_nsc_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Sukanya Sumriddhi Scheme</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"> <i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" name="80c_sukanya_act"
																	id="80c_sukanya_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	value="dxfd" name="80c_sukanya_app"
																	id="80c_sukanya_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_sukanya_remarks" id="80c_sukanya_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">



															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_sukanya_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_sukanya_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Infratstructure Bond</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"> <i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" name="80c_inf_act"
																	id="80c_inf_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	value="dxfd" name="80c_inf_app" id="80c_inf_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_inf_remarks" id="80c_inf_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">

															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_inf_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_inf_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>VPF/PPF/PF</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"> <i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" name="80c_vpf_act"
																	id="80c_vpf_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80c_vpf_app" id="80c_vpf_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_vpf_remarks" id="80c_vpf_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_vpf_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_vpf_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Repayment on Housing Loan</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80c_repa_act" id="80c_repa_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80c_repa_app" id="80c_repa_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_repa_remarks" id="80c_repa_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">

															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_repa_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_repa_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>LIC</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80c_lic_act" id="80c_lic_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80c_lic_app" id="80c_lic_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_lic_remarks" id="80c_lic_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_lic_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_lic_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Post Office Time Deposit</td>
														</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80c_sip_act" id="80c_sip_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80c_sip_app" id="80c_sip_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_sip_remarks" id="80c_sip_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">




															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_sip_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_sip_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Mutual Funds</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_mut_act" id="80c_mut_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_mut_app" id="80c_mut_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_mut_remarks" id="80c_mut_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">




															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_mut_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_mut_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Pension Fund</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_nps_act" id="80c_nps_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_nps_app" id="80c_nps_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_nps_remarks" id="80c_nps_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_nps_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_nps_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>ELSS</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_elss_act" id="80c_elss_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_elss_app" id="80c_elss_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_elss_remarks" id="80c_elss_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">



															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_elss_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_elss_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80C</td>
														<td>Approved shars/Bonds</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_bonds_act" id="80c_bonds_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80c_bonds_app" id="80c_bonds_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80c_bonds_remarks" id="80c_bonds_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80c_bonds_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80c_bonds_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80CCD-1B</td>
														<td>NPS</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80ccd1b_nps_act" id="80ccd1b_nps_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80ccd1b_nps_app" id="80ccd1b_nps_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80ccd1b_nps_remarks" id="80ccd1b_nps_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80ccd1b_nps_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80ccd1b_nps_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>

													<tr>
														<td>80CCG</td>
														<td>RGESS</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80ccg_rgess_act" id="80ccg_rgess_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80ccg_rgess_app" id="80ccg_rgess_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80ccg_rgess_remarks" id="80ccg_rgess_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80ccg_rgess_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80ccg_rgess_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>

												</tbody>
											</table>

											<a class="btn btn-primary pull-left tabNav"
												href="#other_exemption" data-toggle="tab">Previous</a> <a
												href="#80C" class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px;" name="submit" id="submit"
												value="Update">

										</section>
									</form>
								</div>

								<!--For 80C-->

								<div id="80C" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="80C_form_update">
										<input type="hidden" class="form-control " id="80c_id"
											name="employee_id" placeholder="Employee ID" required>

										<section id="unseen">
											<br>
											<div class="col-lg-12">
												<button type="button"
													class=" btn btn-primary btn-sm pull-right edit"
													style="margin-top: -23px;">
													<i class="fa fa-pencil"></i> Edit
												</button>
											</div>
											<br>
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
														<td>80D</td>
														<td>Medical Insurance Self</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80d_medself_act" id="80d_medself_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80d_medself_app" id="80d_medself_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80d_medself_remarks" id="80d_medself_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80d_medself_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80d_medself_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80D</td>
														<td>Medical Insurance Parents</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80d_medpar_act" id="80d_medpar_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80d_medpar_app" id="80d_medpar_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80d_medpar_remarks" id="80d_medpar_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">

															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80d_medpar_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80d_medpar_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80D</td>
														<td>Preventive Medical Checkup</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80d_prev_act" id="80d_prev_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80d_prev_app" id="80d_prev_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80d_prev_remarks" id="80d_prev_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">

															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80d_prev_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80d_prev_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80DD</td>
														<td>Medical treatment for ordinary disability</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80dd_med_act" id="80dd_med_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80dd_med_app" id="80dd_med_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80dd_med_remarks" id="80dd_med_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">





															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80dd_med_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80dd_med_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>


														</td>
													</tr>
													<tr>
														<td>80DD</td>
														<td>Medical treatment for severe disability</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80dd_medsev_act" id="80dd_medsev_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80dd_medsev_app" id="80dd_medsev_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80dd_medsev_remarks" id="80dd_medsev_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">

															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80dd_medsev_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80dd_medsev_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80DDB</td>
														<td>Medical Treatment for Specified disease</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80ddb_med1_act" id="80ddb_med1_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80ddb_med1_app" id="80ddb_med1_app"
																	readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80ddb_med1_remarks" id="80ddb_med1_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80ddb_med1_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80ddb_med1_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>

												</tbody>
											</table>

											<a class="btn btn-primary pull-left tabNav" href="#deduction"
												data-toggle="tab">Previous</a> <a href="#other_deduction"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px;" name="submit" id="submit"
												value="Update">
									
									</form>
								</div>


								<!--For other_deduction-->
								<div id="other_deduction" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="other_deduction_form_update">
										<input type="hidden" class="form-control " id="other_deduc_id"
											name="employee_id" placeholder="Employee ID" required>

										<section id="unseen">
											<br>
											<div class="col-lg-12">
												<button type="button"
													class=" btn btn-primary btn-sm pull-right edit"
													style="margin-top: -23px;">
													<i class="fa fa-pencil"></i> Edit
												</button>
											</div>
											<br>
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
														<td>80E</td>
														<td>Interest on Education Loan</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="80e_edu_act" id="80e_edu_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="80e_edu_app" id="80e_edu_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80e_edu_remarks" id="80e_edu_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80e_edu_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80e_edu_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80G</td>
														<td>Donation To National Funds</td>
														<td class="numeric">
															<div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80g_don1_act" id="80g_don1_act"
																	readonly="true" />
															</div>
														</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don1_app" id="80g_don1_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don1_remarks" id="80g_don1_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80g_don1_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80g_don1_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>

													<tr>
														<td>80G</td>
														<td>Donation To National Trust</td>
														<td class="numeric">
															<div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80g_don2_act" id="80g_don2_act"
																	readonly="true" />
															</div>
														</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don2_app" id="80g_don2_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don2_remarks" id="80g_don2_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80g_don2_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80g_don2_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>

													<tr>
														<td>80G</td>
														<td>Donation To Govt For Family Planning/IOA</td>
														<td class="numeric">
															<div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80g_don3_act" id="80g_don3_act"
																	readonly="true" />
															</div>
														</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don3_app" id="80g_don3_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don3_remarks" id="80g_don3_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80g_don3_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80g_don3_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>

													<tr>
														<td>80G</td>
														<td>Donation To Charitiable Trust/NGOS</td>
														<td class="numeric">
															<div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80g_don4_act" id="80g_don4_act"
																	readonly="true" />
															</div>
														</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don4_app" id="80g_don4_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don4_remarks" id="80g_don4_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80g_don4_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80g_don4_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>

													<tr>
														<td>80GGA</td>
														<td>Donation To Scientific Research</td>
														<td class="numeric">
															<div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80g_don5_act" id="80g_don5_act"
																	readonly="true" />
															</div>
														</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don5_app" id="80g_don5_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don5_remarks" id="80g_don5_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80g_don5_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80g_don5_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>80GGC</td>
														<td>Donation To Political Parties</td>
														<td class="numeric">
															<div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="80g_don6_act" id="80g_don6_act"
																	readonly="true" />
															</div>
														</td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don6_app" id="80g_don6_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control" value="dxfd"
																	name="80g_don6_remarks" id="80g_don6_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80g_don6_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80g_don6_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>

													<tr>
														<td>80TTA</td>
														<td>Interest on Saving Account</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="80tta_sav_act" id="80tta_sav_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80tta_sav_app" id="80tta_sav_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80tta_sav_remarks" id="80tta_sav_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80tta_sav_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80tta_sav_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80U</td>
														<td>Personal Disability</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="80u_dis1_act" id="80u_dis1_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80u_dis1_app" id="80u_dis1_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80u_dis1_remarks" id="80u_dis1_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80u_dis1_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80u_dis1_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>80U</td>
														<td>Personal Severe Disability</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	name="80u_dis2_act" id="80u_dis2_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="80u_dis2_app" id="80u_dis2_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="80u_dis2_remarks" id="80u_dis2_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="80u_dis2_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="80u_dis2_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
												</tbody>
											</table>
											<a class="btn btn-primary pull-left tabNav" href="#80C"
												data-toggle="tab">Previous</a> <a
												href="#housepropertyincome"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px;" name="submit" id="submit"
												value="Update">
									
									</form>
								</div>


								<!--For housepropertyincome-->

								<div id="housepropertyincome" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="housepropertyincome_form">

										<input type="hidden" class="form-control " id="house_id"
											name="employee_id" placeholder="Employee ID" required> <br>
										<div class="col-lg-12">
											<button type="button"
												class=" btn btn-primary btn-sm pull-right edit"
												style="margin-top: -23px;">
												<i class="fa fa-pencil"></i> Edit
											</button>
										</div>
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
														<td>Interest Paid</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="inter_act" id="inter_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" name="inter_app"
																	id="inter_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="inter_remarks" id="inter_remarks" readonly="true" />
															</div></td>
														<td class="numeric">


															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="inter_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="inter_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
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
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="annRent_act" id="annRent_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="annRent_app" id="annRent_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="annRent_remarks" id="annRent_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="annRent_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="annRent_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>Municiple tax Paid</td>
														<td class="numeric "><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="munic_act" id="munic_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" name="munic_app"
																	id="munic_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="munic_remarks" id="munic_remarks" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="munic_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="munic_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>Unrealized Rent</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="unreal_act" id="unreal_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="unreal_app" id="unreal_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="unreal_remarks" id="unreal_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="unreal_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="unreal_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>Housing Loan Interest Paid</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="housing_act" id="housing_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="housing_app" id="housing_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="housing_remarks" id="housing_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">

															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="housing_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="housing_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>


														</td>
													</tr>
												</tbody>
											</table>
											<a class="btn btn-primary pull-left tabNav"
												href="#other_deduction" data-toggle="tab">Previous</a> <a
												href="#otherincome"
												class=" btn btn-success  pull-right tabNav"
												data-toggle="tab"> Next</a>
											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px;" name="submit" id="submit"
												value="Update">

										</div>

									</form>
								</div>
								<!--For otherincome-->

								<div id="otherincome" class="tab-pane">
									<form role="form" method="POST" class="form-horizontal"
										enctype="multipart/form-data" id="other_form">

										<input type="hidden" class="form-control " id="income_id"
											name="employee_id" placeholder="Employee ID" required> <br>
										<div class="col-lg-12">
											<button type="button"
												class=" btn btn-primary btn-sm pull-right edit"
												style="margin-top: -23px;">
												<i class="fa fa-pencil"></i> Edit
											</button>
										</div>
										<br>
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
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"> <i class="fa fa-rupee"></i></span><input
																	type="text" class="form-control" name="int_inc_act"
																	id="int_inc_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee">
																</i></span><input type="text" class="form-control"
																	name="int_inc_app" id="int_inc_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="int_inc_remarks" id="int_inc_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="int_inc_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="int_inc_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>Lottery/Race income</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="abi"
																	name="lottery_act" id="lottery_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="lottery_app" id="lottery_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="lottery_remarks" id="lottery_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="lottery_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="lottery_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>Sub-Letting Income</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="letting_act" id="letting_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control"
																	name="letting_app" id="letting_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="letting_remarks" id="letting_remarks"
																	readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="letting_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="letting_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>

														</td>
													</tr>
													<tr>
														<td>Land Rental income</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" value="dxfd"
																	name="land_act" id="land_act" readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" name="land_app"
																	id="land_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="land_remarks" id="land_remarks" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="land_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="land_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>Other Income</td>
														<td class="numeric"><div class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i>
																</span><input type="text" class="form-control"
																	value="dxfd" name="other_act" id="other_act"
																	readonly="true" />
															</div></td>
														<td class="numeric actual"><div
																class="input-group m-bot15">
																<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
																<input type="text" class="form-control" name="other_app"
																	id="other_app" readonly="true" />
															</div></td>
														<td class="numeric"><div>
																<input type="text" class="form-control"
																	name="other_remarks" id="other_remarks" readonly="true" />
															</div></td>
														<td class="numeric">
															<div class="row fileupload-buttonbar">
																<div class="col-lg-5 attach">
																	<span class="btn btn-success fileinput-button"> <i
																		class="fa fa-paperclip"></i> <input type="file"
																		name="other_proof" class="imagechange">
																	</span>
																</div>
																<div class="col-lg-5">
																	<a class="btn btn-success view" data-toggle="modal"
																		href="#myModal"
																		style="background-color: #41CAC0; border-color: #41CAC0;">
																		<input type="hidden" class="form-control "
																		id="other_proof" /> <i class="fa fa-eye"></i>
																	</a>
																</div>
															</div>


														</td>
													</tr>
												</tbody>
											</table>

											<a class="btn btn-primary pull-left tabNav"
												href="#housepropertyincome" data-toggle="tab">Previous</a>
											<!--  <input name="submitForm" id="submitForm" class="finish btn btn-danger" value="Finish" type="button">-->
											<button type="button"
												class=" btn btn-danger btn-sm pull-right cancel">
												<i class="fa fa-undo"></i> Cancel
											</button>
											<input type="submit"
												class=" btn btn-success  btn-sm pull-right save"
												style="margin-right: 5px;" name="submit" id="submit"
												value="Update">

										</div>

									</form>
								</div>
							</div>
						</div>
					</div>


				</section>
				<!--tab nav end-->


				</form>






			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
<?php include("footer.php"); ?>
<!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-1.8.3.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/ImageTools.js"></script>
	<!--Resize image script-->

	<!--script for this page only-->
	<script src="../assets/data-tables/ZeroClipboard.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<!--For auto Complete-->


	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
           $(document).ready(function () {
          value="<?php echo implode(',',$allowColumns);?>";
           employee_id="<?php echo $employee_id;?>";});
           </script>
	<script type="text/javascript">

    $(".imagechange").change(function (e) {
        var element = this;
        var name = $(this).attr("name");
        ImageTools.resize(this.files[0], {
            width: 672, // maximum width
            height: 1024 // maximum height
        },
     function (blob, didItResize) {
         console.log(element);
         $(element).parent().parent().parent().find("a").find("input").val(window.URL.createObjectURL(blob));
     });

    });


    $("a.view").click(function (e) {
        var imag = $(this).find("input").val();
        $('#preview_image').attr('src', imag);
    });
    $('#it_declare_summery').hide();
    $("#view_summery").click(function (e) {

        $.ajax({
            dataType: 'html',
            type: "POST",
            url: "  ../php/it-summary.php",
            cache: false,
            data: $('#itDeclarationForm').serialize(),
            success: function (data) {
                var json_obj = $.parseJSON(data); //parse JSON
                $('#it_declare_tab').hide();
                $('#it_declare_summery').show();
                var s = 0;
                var month_differ = json_obj.employee_income_tax[0].month_differ;
                $.each(json_obj.employee_salary_details[0], function (k, v) {
                    if (k !== "id" && k !== "employee_id" && k !== "employee_salary_amount" && k !== "pf_limit" && k !== "increment_date" && k !== "updated_on" && k !== "updated_by") {
                        $('.' + k).html((v * month_differ).toFixed(2));
                        s += parseFloat(v * month_differ, 10);
                    }
                });
                incentive = '0.00';
                $('.incentive').html(incentive);
                bonus = '0.00';
                $('.bonus').html(bonus);
                others = '0.00';
                $('.others').html(others);
                perq = '0.00';
                $('.perq').html(perq);

                $.each(json_obj.employee_income_tax[0], function (k, v) {
                    //display the key and value pair
                    $('.' + k).html(Math.max(0, v).toFixed(2));

                });


                //form 1  calucaluation
                salary = parseFloat(json_obj.employee_income_tax[0].prev_earnings_app, 10);
                $('.prev_earnings_app').html(salary.toFixed(2));
                gross = s + parseFloat(incentive, 10) + parseFloat(bonus, 10) + parseFloat(others, 10) + parseFloat(perq, 10) + (salary);
                $('.gross').html(gross);
                house_prop_inc = $('.house_prop_inc').html();
                other_income = $('.other_income').html();
                income = parseFloat(house_prop_inc, 10) + parseFloat(other_income, 10);
                $('.Tot_income').html(gross + income);

                //IT DECALRATION CAL
                var _80g = 0;
                var _80c = 0;
                var _80d = 0;
                var _80e = 0;
                var other = 0;
                $.each(json_obj.employee_it_declaration[0], function (k, v) {
                    //display the key and value pair
                    if (k[2] == "g") {
                        _80g += parseFloat(v, 10);
                    } else {
                        if (k[2] == "c") {
                            _80c += parseFloat(v, 10);
                        } else {
                            if (k[2] == "d") {
                                _80d += parseFloat(v, 10);
                            } else {
                                if (k[2] == "e") {
                                    _80e += parseFloat(v, 10);
                                } else {
                                    other += parseFloat(v, 10);
                                }
                            }
                        }
                    }

                });
                $('._80g').html(_80g.toFixed(2)); //3
                $('._80c').html(_80c.toFixed(2)); //3
                $('._80d').html(_80d.toFixed(2)); //3
                $('._80e').html(_80e.toFixed(2)); //3
                $('.other').html(other.toFixed(2)); //3



                $('.salary_month').html(gross.toFixed(2));
                val_perq = "0.00";
                $('.val_perq').html(val_perq);
                lieu = "0.00";
                $('.lieu').html(lieu);
                tot_perq = parseFloat(gross, 10) + parseFloat(val_perq, 10) + parseFloat(lieu, 10);
                $('.tot_perq').html(tot_perq.toFixed(2));

                exemption = parseFloat(json_obj.employee_income_tax[0].exe_hra, 10) + parseFloat(json_obj.employee_income_tax[0].exe_ita, 10) +
                 parseFloat(json_obj.employee_income_tax[0].exe_oth, 10);
                $('.exemption').html(exemption.toFixed(2));
                $('.balance').html((tot_perq - exemption).toFixed(2)); //3

                pro_tax_paid = "0.00";
                $('.pro_tax_paid').html(pro_tax_paid);
                entertain = "0.00";
                $('.entertain').html(entertain); //5
                aggregate = parseFloat(entertain, 10) + parseFloat(pro_tax_paid, 10);
                $('.aggregate').html(aggregate.toFixed(2)); //4()a+4(b)
                income_charable = (tot_perq - exemption) - aggregate;
                $('.income_charable').html(income_charable.toFixed(2));
                $('.any_income').html(income.toFixed(2));
                $('.gross_s').html((income + income_charable).toFixed(2));

                aggregate_dec = parseFloat(json_obj.employee_income_tax[0].ded_80c, 10) + parseFloat(json_obj.employee_income_tax[0].ded_80d, 10) +
                 parseFloat(json_obj.employee_income_tax[0].ded_80e, 10) + parseFloat(json_obj.employee_income_tax[0].ded_80g, 10) +
                 parseFloat(json_obj.employee_income_tax[0].ded_other, 10);

                $('.aggregate_dec').html(aggregate_dec.toFixed(2));
                tot_dec = ((income + income_charable) - aggregate_dec);
                $('.tot_dec').html(tot_dec.toFixed(2));
                tax_tot = parseFloat(json_obj.employee_income_tax[0].tax, 10) +
                 parseFloat(json_obj.employee_income_tax[0].ec, 10) +
                 parseFloat(json_obj.employee_income_tax[0].shec, 10);
                $('.tax_tot').html(tax_tot.toFixed(2));
                tax_payable_tot = parseFloat(json_obj.employee_income_tax[0].tax_payable, 10) - parseFloat(json_obj.employee_income_tax[0].relief, 10);
                $('.tax_payable_tot').html(tax_payable_tot.toFixed(2));



            }

        });


    });


    $(function () {
        $("#employee_id-id").autocomplete({
            source: "../php/employeeSearch.php",
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
                $("#employee_id-id").val(ui.item.employee_name);
                return false;
            },
            select: function (event, ui) {
                $("#employee_id-id").val(ui.item.employee_name);
                $("#rep_man-id").val(ui.item.employee_id);
                return false;
            },
            search: function () { $(this).addClass('spinner'); },
            open: function () { $(this).removeClass('spinner'); },
            change: function (event, ui) { if (ui.item == null) { $('#employee_id-id').val(""); $('#employee_id-id').val(""); } }
        })
                           .autocomplete("instance")._renderItem = function (ul, item) {
                               return $("<li>")
                               .append("<a>" + item.employee_name + " [" + item.employee_id + "] <br>" + item.employee_designation + "," + item.employee_department + ", " + item.employee_branch + "</a>")
                               .appendTo(ul);
                           };
    });




    $('#itDeclarationForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            dataType: 'html',
            type: "POST",
            url: "  ../php/it-employee-search.php",
            cache: false,
            data: $('#itDeclarationForm').serialize(),
            success: function (data) {
                var json_obj = $.parseJSON(data); //parse JSON
                $('#privious_id').val(json_obj[0].employee_id);
                $('#hra_id').val(json_obj[0].employee_id);
                $('#exemption_id').val(json_obj[0].employee_id);
                $('#80c_id').val(json_obj[0].employee_id);
                $('#80d_id').val(json_obj[0].employee_id);
                $('#other_deduc_id').val(json_obj[0].employee_id);
                $('#house_id').val(json_obj[0].employee_id);
                $('#income_id').val(json_obj[0].employee_id);
                $('#it_declare_summery').hide();
                $.each(json_obj[0], function (k, v) {
                    //display the key and value pair
                    $('#' + k).val(v);
                });
            }

        });

    });




    $("#it_declare_tab").hide();
    $("#tax_plan").click(function () { 
    $("#it_declare_tab").show();
    $('#it_declare_summery').hide();
    });

    $('#head_ItDeclare1,#head_ItDeclare2,#head_ItDeclare3').hide();

    $(".table").find(":input").each(function () {
        $(this).attr('readonly', true);
    });

    $(".attach,.save,.cancel").hide();
    $(".edit").click(function () {
        var ref_this = $('.nav-tabs li.active');
        $("a[data-toggle='tab'").prop('disabled', true);
        $(".tabNav").hide();
        $(".edit").hide();
        $(".attach,.save,.cancel").show();
        $("#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").each(function () {
            $(this).removeAttr('readonly');
        });
        $(".table,#it_employee_code,#it_employer_name,#it_employer_tan").find(":input").each(function () {
            $(this).attr('readonly', false);
        });
        $(".actual").find(":input").each(function () {
            $(this).attr('readonly', true);
        });
    });

    $(".cancel").click(function () {
        $(".tabNav").show();
        $("a[data-toggle='tab'").prop('disabled', false);
        $(".save,.cancel,.attach").hide();
        $(".edit").show();
        $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
            $(this).attr('readonly', true);
        });
    });
    $('#deduction_form_update').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });

    $('#exemption_form_update').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });

    $('#itdeclare_previous_emp').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });


    $('#other_exemption_update').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });


    $('#80C_form_update').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });


    $('#other_deduction_form_update').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });

    $('#housepropertyincome_form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });
    $('#other_form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: "../php/it-declaration-update.php",
            cache: false,
            data: new FormData(this),
            success: function (data) {
                data1 = JSON.parse(data);
                if (data1[0] == "success") {
                    $(".tabNav").show();
                    alert(data1[1]);
                    $("a[data-toggle='tab'").prop('disabled', false);
                    $(".save,.cancel,.attach").hide();
                    $(".edit").show();
                    $(".table,#itdeclare_previous_emp,#exemption_form_update,#deduction_form_update,#it_exemption_remarks").find(":input").not(".actual").each(function () {
                        $(this).attr('readonly', true);
                    });

                }
                else
                    if (data1[0] == "error") {
                        alert(data1[1]);
                    }

            }

        });

    });


    //need to afetr sucess msg 
    // $("#save,#cancel,.attach").hide();
    // $("#edit").show();
    //  $(".table,#itdeclare_previous_emp,#it_exemption_remarks").find(":input").each(function () {
    //      $(this).attr('readonly', true);
    // });


    $('#itDeclarationForm').submit(function (e) {
        $('#it_declare_tab,#head_ItDeclare1,#head_ItDeclare2,#head_ItDeclare3').show();
        $('#head_ItDeclare2').html($("#employee_id-id").val() + " 's It Declaration  ");
        e.preventDefault();
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
