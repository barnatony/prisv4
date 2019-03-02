<?php require_once ("../include/config.php");?>
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
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
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
$employer_id = $_REQUEST ['it_employee_id'];
$employer_name = $_REQUEST ['it_employee_name'];

?>
<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading"> Tax Planner </header>

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
										<header class="panel-heading"> Income Summery </header>
										<table class="table table-hover">
											<thead>

												<tr>

													<th class="numeric">Details Of all deducted</th>
													<th class="numeric">INR</th>
													<th class="numeric">INR</th>
													<th class="numeric">INR</th>

												</tr>

											</thead>
											<tbody>



												<tr>
													<td colspan="4">( i ) Gross Salary</td>
												</tr>
												<tr>
													<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(a)
														Salary as per provisions contained in sec 17 (1)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(b)
														Value of perquisites u/s 17(2) ( as per Form No.12BA,
														wherever applicable )</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(c)
														Profits in lieu of salary under section 17(3) 9 as per
														Form No.12BA,wherever applicable )</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td class="numeric">Total</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>



												<tr>
													<td colspan="4">( ii ) Less Allowance to the extent exempt
														u/s 10</td>
												</tr>
												<tr>
													<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(a)
														Salary as per provisions contained in sec 17 (1)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>


												<tr>
													<td>( iii ) Balance (1-2)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td colspan="4">( iv ) Deduction</td>
												</tr>
												<tr>
													<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(a)
														Entertainment allowance</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(b)
														Tax on Employment</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td>( iv ) Aggregate of 4(a) and (b)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>


												<tr>
													<td>( vi ) Income chargable under the head 'salaries' (3-5)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>


												<tr>
													<td>( vii ) Add any other income reported by the employee</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>


												<tr>
													<td>( viii ) Gross total income (6+7)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td colspan="4">( ix ) Deduction under Chapter VI-A
													
													</th>
													</td>
												
												
												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														A) section 80C,80CCC and 80CCD</td>
													<td class="numeric"></td>
													<td class="numeric">Gross Amount</td>
													<td class="numeric">Deductible Amount</td>

												</tr>

												<tr>
													<td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)
														section 80C</td>
												</tr>

												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;i) Employee Provident Fund</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;ii) Repayment of Housing loan</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;iii) Tution fee-child 1</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;iv) Tution fee-child 2</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) section 80CCC</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c) section 80CCD</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td colspan="4">Note : 1. Aggragate amount deductible under
														sections 80C, 80CCC and 80 CCD(1) shall not exceed one
														lakh rupees</td>
												</tr>
												<tr>
													<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														B) Other sections (e.g. 80E, 80G, 80TTA, etc )under
														chapter VI-A80C,80CCC and 80CCD</td>
													<td class="numeric">Gross amount</td>
													<td class="numeric">Qualifying amount</td>
													<td class="numeric">Deductible amount</td>
												</tr>

												<tr>
													<td>( x ) Aggregate of deductible amount under Chapter VI-A</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>

												<tr>
													<td>( xi ) Total Income (8-10)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td>( xii ) Tax on total income</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td>( xiii ) Education cess @ 35(on tax computed at
														S.No.12)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td>( xiv ) Tax payable (12+13)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td>( xv ) Less:Relief under section 89 (attach details)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
												</tr>
												<tr>
													<td>( xvi ) Tax payable (14-15)</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
													<td class="numeric">0.00</td>
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
												<header class="panel-heading"> Income Summery </header>
												<table class="table table-hover">
													<thead>

														<tr>

															<th class="numeric">Perquisite</th>
															<th class="numeric">Value of perquisite</th>
															<th class="numeric">Recovered</th>
															<th class="numeric">Tax Chargable</th>

														</tr>
													</thead>
													<tbody>


														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Accommodation</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cars/Other
																automotive</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sweeper,
																gardener, watchman or personal attendent</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gas,
																electricity, water</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;interest,free
																or concessional loans</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Holiday
																expenses</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Free
																or concessional travel</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Free
																meals</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Free
																Education</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gifts,
																vouchers etc.</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Credit
																card expenses</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Club
																expenses</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Use
																of movable assets by employees</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transfer
																of assets to employees</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>

														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Value
																of any other benefit/amenity/service/privilege</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stock
																options ( non-qualified options )</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Other
																benefits or amenties</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
																Value of perquisites</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
															<td class="numeric">0.00</td>
														</tr>
														<tr>

															<td class="numeric">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
																value of penifits in lieu of salary as per section 17
																(3)</td>
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
													<header class="panel-heading"> Income Summery </header>
													<table class="table table-hover ">
														<thead>
															<tr>
																<th>Particulars</th>
																<th>Amount(INR)</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<th>( i ) Emoluments Paid</th>
															</tr>
                                <?php
																																$stmt = "SELECT * FROM  company_pay_structure WHERE type ='A' && display_flag ='1' ";
																																$result = mysqli_query ( $conn, $stmt );
																																
																																while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
																																	echo "<tr><td  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            " . $row ['display_name'] . "</td><td>0.00</td></tr>";
																																}
																																?>                      
                              
                          <tr>
																<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	Incentive Bonus</td>
																<td>0.00</td>
															</tr>
															<tr>
																<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	Others</td>
																<td>0.00</td>
															</tr>
															<tr>
																<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	Previous Employer Salary</td>
																<td>0.00</td>
															</tr>
															<tr>
																<th>( ii ) Gross emoluments</th>
																<td>0.00</td>
															</tr>
															<tr>
																<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	House Property Income</td>
																<td>0.00</td>
															</tr>
															<tr>
																<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	Income form other Sources</td>
																<td>0.00</td>
															</tr>
															<tr>
																<th>( iii ) Total Income</th>
																<td>0.00</td>
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
	<script src="../js/jquery-2.1.4.min.js"></script>
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
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<!--For auto Complete-->


	<!-- END JAVASCRIPTS -->
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




    





   // $('#head_ItDeclare1,#it_declare_tab,#head_ItDeclare2,#head_ItDeclare3').hide();

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


   

    

</script>




</body>
</html>
