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

<title>payUmoney Transaction</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
</head>

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
				<section class="panel">
					<!-- page start-->
					<header class="panel-heading"> PayUmoney Form </header>
					<div class="panel-body">
            <?php if($_REQUEST['pg']=='payumoney'){?>
             <form class="form-horizontal" role="form" method="post"
							action="#" id="payUmoneyForm" name="payuForm">
							<div class="alert alert-success fade in" id="msgBox">
								<strong>Invoice Amount of <em id="pending"> &#x20b9;  <?php echo $_REQUEST['PaymentAmount'];?> .. Pay Using PayUmoney</em>
								</strong>
							</div>
							<input type="hidden" name="key" id="key" /> <input type="hidden"
								name="hash" id="hash" /> <input type="hidden" name="txnid"
								id="txnid" /> <input name="amount" id="amount" type="hidden" />
							<input type="hidden" name="invoice_Id"
								value="<?php echo $_REQUEST['PaymentinvoiceId'];?>" /> <input
								type="hidden" name="act"
								value="<?php echo base64_encode($_SESSION['company_id']."!payumoneyTransfer");?>" />
							<div class="col-lg-12 row">
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-5 control-label">First Name</label>
										<div class="col-lg-7">
											<input type="text" class="form-control" name="firstname"
												id="firstname" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Phone</label>
										<div class="col-lg-7">
											<input type="text" class="form-control" name="phone"
												id="phone" />
										</div>
									</div>
								</div>
								<input name="surl"
									value="http://localhost:81/pris/hr/transaction.php"
									type="hidden" /> <input name="furl"
									value="http://localhost:81/pris/hr/transaction.php"
									type="hidden" /> <input type="hidden" name="service_provider"
									value="payu_paisa" size="64" />
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-5 control-label">Email</label>
										<div class="col-lg-7">
											<input type="text" class="form-control" name="email"
												id="email" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label">Product Info</label>
										<div class="col-lg-7">
											<textarea class="form-control" rows="1" name="productinfo"
												id="productinfo"></textarea>
											<label class="help-block text-danger pull-right"
												id="error-msg"></label>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<!--  <div class="col-lg-12 row">
                           <div class="col-lg-6">
						              <div class="form-group">
                                          <label class="col-lg-5 control-label">Last Name</label>
                                          <div class="col-lg-7">
                                              <input name="lastname"   class="form-control"  id="lastname" />
                                          </div>
                                      </div>
                                        <div class="form-group">
                                          <label class="col-lg-5 control-label">Address2</label>
                                          <div class="col-lg-7">
                                              <input type="text" class="form-control" name="address2" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">State</label>
                                          <div class="col-lg-7">
                                               <input type="text" class="form-control" name="state" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">Zipcode</label>
                                          <div class="col-lg-7">
                                             <input type="text" class="form-control" name="zipcode" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">UDF2</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="udf2" />
                                           </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">UDF4</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="udf4" />
                                           </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">PG</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="pg" />
                                           </div>
                                      </div>
                              </div>
                                 <input name="curl" type="hidden" value="http://localhost:81/pris/hr/transaction.php" />
                                          
                            <div class="col-lg-6">
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">Address1</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="address1" />
                                           </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">City </label>
                                          <div class="col-lg-7">
                                              <input type="text" class="form-control" name="city" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">Country</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="country" />
                                          </div>
                                      </div>
                                   <div class="form-group">
                                          <label class="col-lg-5 control-label">UDF1</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="udf1" />
                                           </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">UDF3</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="udf3" />
                                           </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="col-lg-5 control-label">UDF5</label>
                                          <div class="col-lg-7">
                                          <input type="text" class="form-control" name="udf5" />
                                           </div>
                                      </div>
                           </div>
                            </div> 
                            <hr>-->
							<button type="submit" class="btn btn-success btn-sm pull-right">
								Submit</button>
						</form>
             <?php
												
} else if (isset ( $_REQUEST ["status"] )) {
													$status = $_REQUEST ["status"];
													$firstname = $_REQUEST ["firstname"];
													$amount = $_REQUEST ["amount"];
													$txnid = $_REQUEST ["txnid"];
													$posted_hash = $_REQUEST ["hash"];
													$key = $_REQUEST ["key"];
													$productinfo = $_REQUEST ["productinfo"];
													$email = $_REQUEST ["email"];
													$salt = "CF51LjCw1n";
													
													if (isset ( $_REQUEST ["additionalCharges"] )) {
														$additionalCharges = $_REQUEST ["additionalCharges"];
														$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
													} else {
														$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
													}
													$hash = hash ( "sha512", $retHashSeq );
													if ($_REQUEST ['status'] == 'success') {
														if ($hash != $posted_hash) {
															echo "<div id='well' class='well'>Invalid Transaction. Please try again ..<a href='http://localhost:81/pris/hr/invoice.php'>Invoices</a></div>";
														} else {
															echo "<div id='well' class='well'><h3>Thank You. Your order status is " . $status . ".</h3>";
															echo "<h4>Your Transaction ID for this transaction is " . $txnid . ".</h4>";
															echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
														}
													} else {
														if ($hash != $posted_hash) {
															echo "<div id='well' class='well'>Invalid Transaction. Please try again..<a href='http://localhost:81/pris/hr/invoice.php'>Invoices</a></div> ";
														} else {
															echo "<div id='well' class='well'><h3>Your order status is " . $status . ".</h3>";
															echo "<h4>Your transaction id for this transaction is " . $txnid . ". You may try making the payment by clicking the link below.</h4></div>";
														}
													}
												} else {
													echo '<div id="well" class="well">Invalid Payment Selection</div>';
												}
												?>
            </div>
				</section>
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
	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script>
    $(document).ready(function () {
        var status='<?php echo isset($_REQUEST['status'])?$_REQUEST['status']:'';?>';
        var txnid='<?php echo isset($_REQUEST['txnid'])?$_REQUEST['txnid']:'';?>';
        var invoice_id='<?php  echo isset( $_SESSION['invoice_id'])? $_SESSION['invoice_id']:'';?>';
        if(status){
            if(invoice_id){
            $.ajax({
                dataType: 'html',
                type: "POST",
                url: "php/invoice.handle.php",
                cache: false,
                data:{act:'<?php echo base64_encode($_SESSION['company_id']."!transactionAdd");?>',status:status,txnid:txnid,invoice_Id:invoice_id},
                beforeSend:function(){
                	$('#loader').loading(true);
                 },
               success: function (data) {
             	  var json_obj = $.parseJSON(data); //parse JSON
             	  $('#loader').loading(false);
                }
            });
            }	
            }
    });
        
     $(document).on('submit', "#payUmoneyForm", function (e) {
        e.preventDefault();
        $('#error-msg').html('');
     $.ajax({
        dataType: 'html',
        type: "POST",
        url: "php/invoice.handle.php",
        cache: false,
        data:$('#payUmoneyForm').serialize(),
        beforeSend:function(){
        	$('#loader').loading(true);
         },
       success: function (data) {
     	  var json_obj = $.parseJSON(data); //parse JSON
     	  if(json_obj[02]!='error'){
     	  $('#txnid').val(json_obj[2].txnid);
     	 $('#txnid').val(json_obj[2].txnid);
     	 $.each(json_obj[2], function (k, v) {
         	 $('#'+k).val(v);
           });
        $('#payUmoneyForm').attr('action',json_obj[2].action);
        var payuForm = document.forms.payuForm;
        payuForm.submit();
     	  }else{
         	  $('#error-msg').html('Enter Required Fields');
          }
        $('#loader').loading(false);
        }
    });
    });    
   </script>
</body>
</html>
