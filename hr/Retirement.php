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

<title>Retirement</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />

<style>
.table-striped>tbody>tr:nth-child(2n+1)>td, .table-striped>tbody>tr:nth-child(2n+1)>th
	{
	background-color: #fff;
}

.table-hover>tbody>tr:hover>td, .table-hover>tbody>tr:hover>th {
	background-color: #fff;
}

hr {
	margin-top: 10px;
	margin-bottom: 9px;
}
</style>
</head>
<body>
	<section id="container" class="">
		<!--header start-->
     <?php  include("header.php");?>
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
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs  ">
							<li class="active"><a href="#RetireAll" data-id="p"
								data-toggle="tab" id="promClick"> Retirement Allowances </a></li>
							<li class=""><a href="#RetireDedu" data-id="i" data-toggle="tab"
								id="incClick"> Retirement Deductions </a></li>
						</ul>
					</header>
					<div class="panel-body">
						<div class="tab-content tasi-tab">
							<div class="tab-pane active" id="RetireAll">
								<div class="panel-body">
									<div class="col-lg-12">
										<div class="container " style="width: 100%;">
											<section class="error-wrapper"
												style="margin-top: 2%; text-align: left">
												<table
													class="table table-striped table-hover table-bordered">
													<thead>
														<tr id="slab-table-head">
															<th>Benefits</th>
															<th>Enable/Disable</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><h5>(i) Gratuity</h5>
																<hr>
																<span> An individual who has worked in an organization
																	for a minimum period of 5 years is eligible for
																	gratuity. </span></td>
															<td>
																<div class="switch switch-square" data-on-label="Enable"
																	data-off-label="Disable" style="margin-top: 16%;">
																	<input type="checkbox" id="c_gratuity" />
																</div>
															</td>
														</tr>

														<tr>
															<td><h5>(ii) Superannuation Fund</h5>
																<hr>
																<span> a) Retirement benefit given to employees by the
																	Company. <br> b) IF Company has a link with agencies
																	like LIC Superannuation Fund, where their contributions
																	are paid.<br> c) The Company pays 15% of basic wages as
																	superannuation contribution.No contribution from the
																	employee.<br>
															</span></td>
															<td>
																<div class="switch switch-square" data-on-label="Enable"
																	data-off-label="Disable" style="margin-top: 16%;">
																	<input type="checkbox" id="c_saf" />
																</div>
															</td>
														</tr>

														<tr>
															<td><h5>(iii) Leave Encashment</h5>
																<hr>
																<span> You have not taken leaves and have opted for
																	encashing these leaves, your employer would be paying
																	you some amount as leave encashment.<br> The amount so
																	received on account of encashing the leaves not availed
																	would be liable to tax under head "Income from Salary".
															</span></td>
															<td>
																<div class="switch switch-square" data-on-label="Enable"
																	data-off-label="Disable" style="margin-top: 16%;">
																	<input type="checkbox" id="c_leaveenc" />
																</div>
															</td>
														</tr>


														<tr>
															<td><h5>(iii) Retrenchment Fund</h5>
																<hr>
																<span> Leave pay and pro-rata bonuses that are paid at
																	the time of the termination of employment do not form
																	part of a severance benefit and<br> are subject to tax
																	at normal rates applicable to individuals.
															</span>
															
															<td>
																<div class="switch switch-square" data-on-label="Enable"
																	data-off-label="Disable" style="margin-top: 16%;">
																	<input type="checkbox" id="c_retrenchment" />
																</div>
															</td>
														</tr>

													</tbody>
												</table>
											</section>
										</div>
									</div>
								</div>
							</div>


							<div class="tab-pane" id="RetireDedu">Retirment Deduction</div>

						</div>



					</div>

				</section>
				<!-- page end        -->
			</section>
		</section>
		<!-- Dialog Boxes-->
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
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
	<script src="../js/respond.min.js"></script>

	<!--custom checkbox & radio-->
	<script src="../js/bootstrap-switch.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
      jQuery(document).ready(function () {                
       $('.switch').on('click',function(){
           var element=$(this).find('input').attr('id');
           if ($("#"+element).prop('checked')==true){ 
        	   $.ajax({
        		   dataType: 'html',
                   type: "POST",
                   url: "php/leaveRule.handle.php",
                   cache: false,
                   data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>',data:'1' },
		           success: function (data) {
                       data1 = JSON.parse(data);
                       if (data1[0] == "success") {
                            $('.close').click();                            
                            $('.model_msg0').click();
                            $('#leave_msg').html('Leave Rule Updated Successfully ');
                            oTable.fnDraw();
                             }
                       else
                           if (data1[0] == "error") {
                               alert(data1[1]);
                           }
                   }

               });
        	    }else{
        	    	console.log('no');
            	    }
          });
      });

      </script>
</body>
</html>
