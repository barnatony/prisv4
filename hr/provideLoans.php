<?php include_once("../include/config.php");?>
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
<title>Provided Loans</title>
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
<link href="../css/TableTools.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/datepicker.css" />

<style>
@media ( min-width : 992px) {
	.modal-lg {
		width: 1100px;
	}
}

#e_club_with_chosen {
	width: 100% !important;
}
</style>



<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <![endif]-->
</head>
<!--custom checkbox & radio-->
<script type="text/javascript" src="../js/ga.js"></script>

<body>
	<section id="container" class="">
		<!--header start-->
         <?php include("header.php"); ?>
         <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading">
						Provide Loans History
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn btn-info"
								style="margin-top: -5px;">
								<i class="fa fa-plus"></i> To Provide Loan
							</button>
						</div>
					</header>

					<div class="panel-body">

						<div class="col-lg-12" id="add-provideloan-toggle">
							<form class="form-horizontal" role="form" method="post"
								id="provideformAddForm">
								<div class="col-lg-12">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 control-label">Employee Name</label>
											<div class="col-lg-7">
												<select class="form-control" id="employee_id"
													name="employee_id[]" multiple>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, "SELECT employee_id,employee_name FROM employee_work_details WHERE enabled = 1" );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $employee_id, $employee_name );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $employee_id . "'>" . $employee_name . " [" . $employee_id . "] " . "</option>";
																																										}
																																										?>
                                             </select>
											</div>

										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Loan</label>
											<div class="col-lg-7">
												<select class="form-control" id="loan_id" name="loan_id">
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, "SELECT loan_id,loan_name FROM company_loans WHERE enabled = 1" );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $loan_id, $loan_name );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $loan_id . "'>" . $loan_name . "</option>";
																																										}
																																										?>
                                             </select>
											</div>

										</div>

										<div class="form-group">

											<label class="col-lg-5 control-label"> Amount </label>
											<div class="col-lg-7 input-group">
												<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
												<div class="iconic-input right">
													<i class="left"
														style="font-size: 12px; margin: 9px 11px 6px 0px;"></i> <input
														type="text" class="form-control" name="loan_amount"
														id="loan_amount" value="0" />
												</div>
											</div>
										</div>


										<div class="form-group">
											<label class="col-lg-5 control-label"> Issue Date</label>
											<div class="col-lg-7 input-group">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control " name="issued_on"
													id="issued_on" value="" />
											</div>
										</div>

									</div>


									<div class="col-lg-6">

										<div class="form-group">
											<label class="col-lg-5 control-label">Repayment Start Date</label>
											<div class="col-lg-7 input-group bootstrap-timepicker">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control timepicker-default"
													name="starts_from" id="starts_from" value="" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"> No of Installment </label>
											<div class="col-lg-7">
												<input type="text" class="form-control"
													name="no_installment" id="no_installment" value="0" />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label">EMI</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="emi_amount"
													id="emi_amount" value="0" />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label">Close Date</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" name="closing_date"
													id="closing_date" value="0" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label"></label>
											<div class="col-lg-7"></div>
										</div>
										<div class="col-lg-7 col-lg-offset-5 ">
											<button type="submit" class="btn btn-danger"
												id="providedloan-add">Add</button>

										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="space15"></div>
						<div class="adv-table editable-table">
							<table class="table table-striped table-hover table-bordered"
								id="editable-sample">
								<thead>
									<tr>
										<th>EmpID</th>
										<th>Loan Name</th>
										<th>Amount</th>
										<th>Install.</th>
										<th>EMI</th>
										<th>Issued</th>
										<th>Repayment</th>
										<th>Balance</th>
										<th>Lastdue</th>
										<th>Bal. Install</th>
										<th>Action</th>
										<th>Edit</th>
									</tr>
								</thead>
								<tbody>
									<tr>
									</tr>
								</tbody>
							</table>
						</div>






					</div>
				</section>
				<!-- page end-->
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
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>


	<!--custom checkbox & radio-->
	<script type="text/javascript" src="../js/ga.js"></script>
	<script src="../js/bootstrap-switch.js"></script>
	<script src="../js/jquery.tagsinput.js"></script>
	<!--Date Picker-->
	<script src="../js/bootstrap-datepicker.js"></script>

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>

	<!--script for this page-->
	<script src="../js/form-component.js"></script>
	<!--Data Mask-->
	<script type="text/javascript" src="../js/bootstrap-inputmask.min.js"></script>

	<!--script for this page only-->
	<script src="../js/provided-loan.js"> </script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>



	<!-- END JAVASCRIPTS -->

	<script type="text/javascript">
    $(document).ready(function () {
          $('#employee_id').chosen();
          
          EditableTable.init();
      $('#add-provideloan-toggle').toggle('hide');
            $('#showhide').on('click', function (event) {
                $('#add-provideloan-toggle').toggle('show');
            });

            $('#issued_on,#starts_from').datepicker({
                  format: 'dd/mm/yyyy'
              });
               $(function () {
                  $("#rep_man").autocomplete({
                      source: "../php/employee-loan.php",
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
                          $("#rep_man").val(ui.item.employee_name);
                          return false;
                      },
                      select: function (event, ui) {
                          $("#rep_man").val(ui.item.employee_name);
                          $("#rep_man-id").val(ui.item.employee_id);
                          return false;
                      },
                      search: function () { $(this).addClass('spinner'); },
                      open: function () { $(this).removeClass('spinner'); },
                      change: function (event, ui) { if (ui.item == null) { $('#rep_man').val(""); $('#rep_man').val(""); } }
                  })
                          .autocomplete("instance")._renderItem = function (ul, item) {
                              return $("<li>")
                              .append("<a>" + item.employee_name + " [" + item.employee_id + "] <br>" + item.employee_designation + "," + item.employee_department + ", " + item.employee_branch + "</a>")
                              .appendTo(ul);
                          };
              });


    });
</script>