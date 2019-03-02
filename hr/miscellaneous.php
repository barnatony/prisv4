<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="basstechs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Miscellaneous Payment/Deduction</title>
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

<link href="../css/table-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
@media ( min-width : 992px) {
	.modal-lg {
		width: 1100px;
	}
}
#e_payments_in_chosen,#ee_payments_in_chosen,#payment_name_chosen,#e_e_name_chosen,#epayment_name_chosen,#d_payment_name_chosen,
#e_d_payments_in_chosen,#d_e_e_name_chosen,#d_e_payment_name_chosen,#d_e_payments_in_chosen {
	width: 100% !important;
}
</style>
</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php include_once (__DIR__."/header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once (__DIR__."/sideMenu.php"); ?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">

					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs" id="miscTab">
						
								<li><a href="#miscPay" data-loaded="false" data-title="misPay"
							data-toggle="tab" id="paymentClick"> Miscellaneous Payments </a></li>
							<li><a href="#miscDedu" data-loaded="false" data-title="misDed"
							data-toggle="tab" id="deduClick"> Miscellaneous Deductions </a></li>

						</ul>
						 <div class="btn-group pull-right" style="margin-top: -1.5%;">
							<button id="showhideButton" type="button" class="btn btn-sm btn-success">
								<i class="fa fa-plus"></i> Payment Add
							</button>
						 <a href="import.php?for=Misc_PayDedu" target="foo()" title="Miscellaneous Import">
							<button type="button" class="btn btn-sm btn-default" style="margin-left: 5px;">
								<i class="fa fa-upload" aria-hidden="true"></i> Import
							</button>
						 </a>
						</div>
					</header>

					<div class="loader " style="width: 97.9%; height: 20%;"></div>
					<form class="form-horizontal displayHide" role="form" method="post" id="filterHideShowDiv">
					   <?php
							require_once (LIBRARY_PATH . "/filter.class.php");
							$filter = new Filter ();
							$filter->conn = $conn;
					       echo  $filterHtml = $filter->createFilterForScreen ('miscellaneous');
					    ?>
				   </form>
				   <div class="col-lg-12 panel-body">
			<form class="form-horizontal" role="form" method="post"	id="paymentAddForm">
                 <div class="col-lg-6">
                  <input type="hidden" name="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
					<div class="form-group" id="payinName">
                        
						<label class="col-lg-2 control-label"> Pay as</label>
						<div class="col-lg-7">
						<select class="form-control"
								id="payment_name" name="pCategory">
                               <?php
																															$stmt = mysqli_prepare ( $conn, "SELECT display_name,pay_structure_id FROM  company_pay_structure WHERE  display_flag = 1 AND type='MP' ORDER BY sort_order" );
																															$result = mysqli_stmt_execute ( $stmt );
																															mysqli_stmt_bind_result ( $stmt, $display_name, $pay_structure_id );
																															while ( mysqli_stmt_fetch ( $stmt ) ) {
																																echo "<option value='" . $pay_structure_id . "'>" . $display_name . "" . "</option>";
																															}
																															?>
                           </select> <span class="helpBlock">To Enable
								MiscPayments &gt; <a href="payStructure.php">Pay Heads</a>
							</span>
						</div>

					</div>

<div class="form-group displayHide" id="deductinName">
						<label class="col-lg-2 control-label"> Name</label>
						<div class="col-lg-7">
							<input type="hidden" class="form-control" name="dId"
								id="d_payment_ids" value="" /> <select class="form-control"
								id="d_payment_name" name="dCategory">
                               <?php
																															$stmt = mysqli_prepare ( $conn, "SELECT display_name,pay_structure_id FROM  company_pay_structure WHERE  display_flag = 1 AND type='MD' ORDER BY sort_order" );
																															$result = mysqli_stmt_execute ( $stmt );
																															mysqli_stmt_bind_result ( $stmt, $display_name, $pay_structure_id );
																															while ( mysqli_stmt_fetch ( $stmt ) ) {
																																echo "<option value='" . $pay_structure_id . "'>" . $display_name . "" . "</option>";
																															}
																															?>
                           </select> <span class="helpBlock">To Enable
								MiscDeduction &gt; <a href="payStructure.php">Pay Heads</a>
							</span>

						</div>
					</div>

					<div class="form-group" id="max_cf">
						<label class="col-lg-2 control-label">Type</label>
						<div class="col-lg-7">
							<label for="Lumsum" class="col-lg-6 control-label"> <input
								name="cal" id="Lumsum" type="radio" value="1" checked>lump sum
							</label> <label for="Percentage" class="col-lg-6 control-label">
								<input name="cal" id="Percentage" type="radio" value="0">
								Percentage
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-2 control-label Percentage_i">Amount</label>
						<label class="col-lg-2 control-label Lumsum_i">Percentage</label>
						<div class="col-lg-7 input-group">

							<span class="input-group-addon Percentage_i"><i
								class="fa fa-rupee"></i></span> <span
								class="input-group-addon Lumsum_i">%</span>

							<div class="iconic-input right ">
								<i class="left"
									style="font-size: 12px; margin: 9px 11px 6px 0px;"></i> <input
									class="form-control" name="Amount" id="payment_amount"
									type="text" required>
							</div>
						</div>
					</div>
					<div class="form-group Lumsum_i">
						<label class="col-lg-2 control-label">Salary Head</label>
						<div class="col-lg-7">
							<select class="form-control" id="ee_payments_in" name="In[]"
								multiple>
								<option value="GROSS">Gross</option>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, "SELECT pay_structure_id, display_name FROM company_pay_structure WHERE display_flag = 1 && type='A' " );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $pay_structure_id . "'>" . $display_name . "</option>";
																																										}
																																										?>
								                                             </select>
						</div>

					</div>

				</div>


				<div class="col-lg-6">


					<div class="form-group">
						<label class="col-lg-2 control-label">No of Repetition</label>
						<div class="col-lg-7">
							<input type="text" class="form-control" name="count" id="count"
								value="1" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-2 control-label">Effects From</label>
						<div class="col-lg-7 input-group">
							<span class="input-group-addon" style="cursor: pointer"><i
								class="fa fa-calendar"></i></span>
							<div class="iconic-input right">
								<input class="form-control" type="text" name="effectsFrom"
									id="applicable_form_db" required />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Remarks</label>
						<div class="col-lg-7">
							<textarea class="form-control" rows="3" cols="15" name="remarks"></textarea>
						</div>
					</div>
					<div class="panel-body pull-right">
					<label class="control-label text-danger" id="errorMsg"></label>
			     	<button type="button" class="btn btn-sm btn-success displayHide" id="deductionAdd">Add Deduction</button>
			     	<button type="button" class="btn btn-sm btn-success" id="miscpayAdd">Add Payment</button>
				   <button type="button" class="btn btn-sm btn-danger" id="filterCancel">Cancel</button>
			      </div>

				</div>
			</form>
			</div>
				<div class="tab-content tasi-tab">
						<div class="tab-pane active" id="miscPay"> 
								<div class="payment"></div>
							</div>
							<div class="tab-pane" id="miscDedu">
								<div class="deduction"></div>
							</div>
							</div>
					<!--help block starts here-->
					<div class="helpblock" >
                        		<div class="alert" role="alert">
                        		<div class="alert alert-info alert-dismissible fade in" role="alert">
                         			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  										<p ><i class="fa fa-caret-right" ></i> &nbsp;<b>"Pay as"</b> - &gt; under which salary head you want to perform your payment/deduction.</p>
  										<p ><i class="fa fa-caret-right" ></i>  &nbsp;Payment as Percentage needs the salary heads where the percentage to be applied. (Gross includes all the heads)</p>
  										<p ><i class="fa fa-caret-right" ></i>  &nbsp;Effects From - from which month the payment/deduction has to be processed. You can schedule it for future months</p>
  										<p ><i class="fa fa-caret-right" ></i>  &nbsp;Repeat - the number of month(s) the payment/deduction has to be performed.</p>
  								</div>
								</div>
						</div>
						<!--help block end-->
			</section>


				<!-- page end-->
			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include_once (__DIR__."/footer.php"); ?>
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
	<!--script for this page only-->
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	
	<script src="../js/bootstrap-dialog.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
	$(document).ready(function () {midsFlag=0;midDeFlag=0;
	

     
      $('#applicable_form_db').datetimepicker({
     	  format: 'MM/YYYY',
     	  maxDate:false
      });

	});
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
     	      $('#miscTab a[href="#' + tab + '"]').tab('show');
     	      
     	     //  (tab='')
     	  	var sum = 0;
     	  }else{
     		 $('#miscTab a[href="#miscPay"]').tab('show');
         	  }
     	   if(tab=='miscDedu'){
         	 $('#showhideButton').html('<i class="fa fa-plus"></i> Deduction Add');
         	$('#showhideButton').attr('data-id','miscdedu');
         	 $('#deductinName,#deductionAdd').show();
             $('#payinName,#miscpayAdd').hide();
         	  }else if(tab=='miscPay'){
             $('#showhideButton').html('<i class="fa fa-plus"></i> Payment Add');
             $('#showhideButton').attr('data-id','miscpay');
             $('#deductinName,#deductionAdd').hide();
             $('#payinName,#miscpayAdd').show();
             	  }
     	 
     	  // Change hash for page-reload
     	  $('#miscTab a').on('shown.bs.tab', function (e) {
     	      window.location.hash = e.target.hash;
		})
     	});
       
      $('#miscTab').on('shown.bs.tab', function (e) {
	   // newly activated tab
   	     window.scrollTo(0, 0);
   	  $('#errorMsg').html('');
   	   if($(e.target).data('title') == 'misPay'){
				$(".loader").loading(true);
				if(midsFlag==0){
				 $(".payment").load('miscPayments.php');
				 midsFlag=1;
				}
                $(".loader").loading(false);
                
			}else if($(e.target).data('title') == 'misDed'){
				 $(".loader").loading(true);
				  if(midDeFlag==0){
				 $(".deduction").load('miscDeductions.php');
				 midDeFlag=1;
				 }
                 $(".loader").loading(false);
                 
			}
			//make the tab loaded true to prevent re-loading while clicking.
     		$(e.target).data('loaded',true);
   		});

      	$(".Percentage_i").hide();
      	 $(document).on('click', "#Lumsum", function (e) {
      	      $(".Lumsum_i").hide();
              $(".Percentage_i").show();
          });
      	$(document).on('click', "#Percentage", function (e) {
              $(".Lumsum_i").show();
              $(".Percentage_i").hide();
          });

      	$(document).on('click', "#paymentClick", function (e) {
      		 $('#showhideButton').html('<i class="fa fa-plus"></i> Payment Add');
              $('#deductinName,#filterHideShowDiv,#paymentAddForm,#deductionAdd').hide();
             $('#payinName,#miscpayAdd').show();
       });

    	$(document).on('click', "#deduClick", function (e) {
    		$('#showhideButton').html('<i class="fa fa-plus"></i> Deduction Add');
      		$('#deductinName,#deductionAdd').show();
            $('#payinName,#filterHideShowDiv,#paymentAddForm,#miscpayAdd').hide();
       });
     	
          
      $('#ee_payments_in,#d_payment_name').chosen();
      </script>


</body>
</html>
