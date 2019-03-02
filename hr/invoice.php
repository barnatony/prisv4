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

<title>Invoice</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" /><link href="../css/table-responsive.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />

<style>
.ibox-title {
	margin-bottom: 0;
	padding: 14px 15px 7px;
	height: 48px;
	border-bottom: 1px dashed rgba(0, 0, 0, .2);
}

.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td,
	.table>tbody>tr>td, .table>tfoot>tr>td {
	line-height: 1.42857;
	padding: 8px;
	vertical-align: top;
}

th {
	text-align: left;
}

table {
	
}

.table {
	width: 100%;
	max-width: 100%;
	margin-bottom: 20px;
	border-spacing: 0;
	border-collapse: collapse;
	background-color: transparent;
}
</style>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
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
				<section class="panel" id="invoiceDiv">
					<header class="panel-heading"> Invoices </header>
					<div class="panel-body">
						<div id="loader" style="width: 81%"></div>
						<div class="alert alert-danger fade in displayHide" id="msgBox">
							<button data-dismiss="alert" class="close close-sm css"
								type="button">
								<i class="fa fa-times"></i>
							</button>
							<strong>You have an <em id="pending"></em> Pending invoices
							</strong>
						</div>
						<div id="contentInvoices"></div>
					</div>
				</section>

				<section class="panel displayHide" id="invoiceFormDiv">
					<form action="invoicePdf.php" method="post">
						<input name="emp_id" value="('VM34235')" type="hidden">
						<header class="panel-heading">
							<em style="cursor: pointer;" id="back">Invoices &gt;</em> <em
								id="invoiceIdSet"></em> <input type="hidden"
								name="invoiceIdForpdf" class="invoiceIdValue">

							<button class="btn btn-sm btn-success  pull-right" type="submit"
								title="Invoice Download" style="margin-left: 1%;">
								<i class="fa fa-file-pdf-o"></i> PDF
							</button>
							<button class="btn btn-sm btn-default  pull-right" type="button"
								id="emailInvoice">
								<i class="fa fa-envelope-o"></i> Email
							</button>
						</header>
					</form>
					<div class="popover-markup hidden">
						<section class="panel">
							<div class="head hide">Information</div>
							<div class="content hide">

								<form class="form-horizontal" role="form" id="invoiceEmailForm"
									method="post">
									<div class="col-lg-12" id="hideDiv">
										<div class="input-group">
											<input type="hidden" name="invoice_Id" class="invoiceIdValue">
											<input type="hidden" name="act"
												value="<?php echo base64_encode("!sentEmail");?>" /> <input
												type="text" class="form-control" id="emailText"
												name="emailId"> <span class="input-group-btn">
												<button class="btn btn-default" id="submitEmailOn"
													type="button">SEND</button>
											</span>
										</div>
									</div>
									<label class="help-block text-danger pull-right" id="error-msg"
										style="font-size: 11px; margin-bottom: 3px; margin-top: -1px;"></label>
								</form>
							</div>
						</section>
					</div>
					<div class="col-lg-12 panel panel-primary">
						<div class="panel-body">
							<div class="row invoice-list" style="margin-bottom: -39px;">
								<div class="col-lg-12 corporate-id">
									<img src="../compDat/masterlogo.png" alt="basspris-logo"
										class="col-lg-3" style="width: 17%; margin-top: 20px;">
									<h2 class="col-lg-5"></h2>
									<h2 class="col-lg-5 pull-right displayHide generate"
										style="margin-top: -8px;">PERFORMA INVOICE</h2>
									<h2 class="col-lg-5 pull-right displayHide view"
										style="margin-top: -8px; text-align: right;">INVOICE</h2>

									<div class="col-lg-1 "></div>
									<div class="col-lg-6">
										<p>
											<i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;
											No:40,Second Floor, <br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Teachers Colony, Adyar, <br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chennai.PIN:600020<br> <i
												class="fa fa-mobile"></i>&nbsp;&nbsp;&nbsp; +91 9841616416
										</p>
										<h3 class="alert alert-danger view unpaidMsg"></h3>
										<h3 class="alert alert-success view status">Paid</h3>
									</div>

									<div class="col-lg-5">
										<ul class="unstyled pull-right">

											<li class="displayHide view" id="invoiceId"></li>
											<br>
											<li id="invoicePeriod" class="view"></li>
											<br>
											<li><h4>BILLING TO</h4></li>
											<li><i class="fa fa-briefcase"></i> &nbsp; <span
												id="company_name"></span>,</li>
											<li><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;<span
												id="company_build_name"></span>, <span id="company_area">PUDUKKOTTAI</span>,</li>
											<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
												id="company_street"></span>,
											</li>
											<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
												id="company_state"></span>,<span id="company_city"> </span>-<span
												id="company_pin_code"></span>,
											</li>
											<li><i class="fa fa-mobile"></i>&nbsp;&nbsp;&nbsp; +91 <span
												id="company_mobile"></span></li>
											<li><i class="fa fa-envelope"></i>&nbsp;&nbsp;<span
												id="company_email"></span></li>

										</ul>

									</div>

								</div>


							</div>
							<div class="container " style="width: 100%; margin-top: 5%;">
								<section class="error-wrapper"
									style="margin-top: 1%; text-align: left;">
									<div id="bodyContent"></div>

								</section>
							</div>
							<div class="row">
								<div class="col-lg-12 corporate-id" id="htmlprint">

									<div class="col-lg-7"></div>

									<div class="col-lg-5">
										<div id="taxContent"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel-body">
							<div class="row text-center invoice-btn displayHide view"
								id="payNowbutton">
								<form class="form-inline" method="post" action="transaction.php">
									<button type="submit" class="btn btn-info btn-sm pull-right"
										style="margin-left: 1%;">
										<i class="fa fa-credit-card"></i> Pay Now
									</button>
									<div class="form-group has-success pull-right">
										<input type="hidden" id="amountGet" name="PaymentAmount"> <input
											type="hidden" class="invoiceIdValue" name="PaymentinvoiceId">
										<select class="form-control" name="pg" id="pg">
											<option value="">Select Option</option>
											<option value="payumoney">PayUmoney</option>
											<option value="paypal">Paypal</option>
											<option value="stripe">Stripe</option>
											<option value="manualpayment">Bank / Cash</option>
											<option value="authorize_net">Authorize.net</option>
										</select>
									</div>
								</form>
							</div>
							<div class="row text-center invoice-btn displayHide view"
								id="dateGe"></div>
						</div>
					</div>
				</section>

			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php");?>
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
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script type="text/javascript">
       $(document).ready(function () {
    	  flag=0;
           var invoiceId='<?php echo isset($_REQUEST['invoiceId'])?$_REQUEST['invoiceId']:'';?>';
           if(invoiceId){
        	   invoiceView(invoiceId);
        	   }else{
               viewAllInvoice();
          }
      });

       $(document).on('click', ".invoice_view", function (e) {
         e.preventDefault();
         var invoiceId=$(this).data('id');
         invoiceView(invoiceId);
       });

       function viewAllInvoice(){
    	   $.ajax({
               dataType: 'html',
               type: "POST",
               url: "php/invoice.handle.php",
               cache: false,
               data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>'},
               beforeSend:function(){
               	$('#loader').loading(true);
                },
              success: function (data) {
            	  var json_obj = $.parseJSON(data); //parse JSON
            	  var html='';
            	  flag=1;
            	  var remaingInvoice=0;
             		
            	  $('#contentInvoices').empty();
            	  var html = '<section id="flip-scroll"><table class="table table-striped table-hover table-bordered cf dataTable" id="invoiceSample"> <thead class="cf"><tr><th>Invoice Id</th><th>Invoice Month</th><th>Period</th><th>NetAmount</th><th>Due On</th><th>Actions</th></tr></thead><tbody>';               	                	  
               	  for (var i = 0, len =json_obj[2].length; i < len; ++i) {
               		   html += '<tr>';
               		   $.each(json_obj[2][i], function (k, v) {
               		  if(k=='pending'){ remaingInvoice=v;}
               		  if(k!='pending'){
               		   if(k=='Status'){
                   		     html +=(v=='due')?'<td><span style="font-size: 10px;" title="'+invoiceId+'" class="label label-danger r-activity"><i class="fa fa-history" aria-hidden="true"></i> Due </span></td>':'<td><span style="font-size: 10px;" class="label label-success r-activity" ><i class="fa fa-check-circle-o" aria-hidden="true"></i> Paid</span></td>';
                       	   }else if(k=='due_on'){
                       		html+=(v.split('_')[1]>0 && json_obj[2][i].Status=='due')?'<td><a href="#" title="'+v.split('_')[1]+' Days Remaining">'+v.split('_')[0]+'</a></td>':'<td><a href="#" title="Paid On '+v.split('_')[0]+'">'+v.split('_')[0]+'</a></td>';
                           }else if(k=='invoice_id'){
                       		 invoiceId=v;
                       		 html +='<td><a class="invoice_view" href="#" title="View Invoice" data-id="'+invoiceId+'">'+invoiceId+'</a></td>';
                           }else{
                            html += '<td>'+v+'</td>';
                           }
               			}
                   	   });  
                      html += "</tr>";
                    	}
                	    html += '</tbody></table></section>';
                	    $('#contentInvoices').html(html);
                	    $('#invoiceSample').dataTable({"bInfo":false});
                	    console.log(remaingInvoice);
                	 $('#pending').html((remaingInvoice>0)?remaingInvoice:$('#msgBox').empty());
                	 (remaingInvoice>0)?$('#msgBox').show():$('#msgBox').hide();
                	 $('#loader').loading(false);
               }
           });
           }
       
       function invoiceView(invoiceId){
           $('.invoiceIdValue').val(invoiceId);
    	   $.ajax({
               dataType: 'html',
               type: "POST",
               url: "php/invoice.handle.php",
               cache: false,
               data: { act: '<?php echo base64_encode($_SESSION['company_id']."!viewInvoice");?>',invoice_Id:invoiceId},
               beforeSend:function(){
               	$('#loader').loading(true);
                },
              success: function (data) {
            	  var json_obj = $.parseJSON(data); //parse JSON
            	  setData(json_obj[2]);       	 
            	  $('#emailInvoice').popover({ 
       			   html: true,
       			   placement: "left",
       		       content: function () {
       			        return $(this).parent().parent().parent().find('.content').html();
       			    }
       			}); 
                   $('#loader').loading(false);
               }
           });
           }
       
       function setData(data){
          var  str=data.values[0];
          $('#invoiceDiv').hide();
		  $('#company_name').html(str.split('_')[0]);
    	  $('#company_build_name').html(str.split('_')[1]);
    	  $('#company_area').html(str.split('_')[2]);
    	  $('#company_street').html(str.split('_')[3]);
    	  $('#company_state').html(str.split('_')[4]);
    	  $('#company_pin_code').html(str.split('_')[5]);
    	  $('#company_city').html(str.split('_')[6]);
    	  $('#company_email').html(str.split('_')[7]);
    	  $('#company_mobile').html(str.split('_')[8]);
    	   var html="";var taxhtml="";var noteContent="";
           html+='<div class="table-responsive"><table class="table invoice-items"><thead><tr class="h4 text-dark"><th id="cell-id"   class="text-semibold">#</th><th id="cell-item" class="text-semibold">Particulars</th><th id="cell-item" class="text-semibold text-right">Amount</th></tr></thead><tbody>';
	    var i=1;
	    for (lc = 0; lc < data.services.length; lc+=4) {
	    	 if(data['services'][lc+2].split('|')[1]){
              var values=' ( '+data['services'][lc+2].split('|')[0]+' active Employees ) ';
		    }else{
			    var values='';
			} html +='<tr><td class="text-semibold text-dark text-left">'+i+'</td><td class="text-semibold text-dark text-left">PRIS -'+data['services'][lc]+values+'</td><td class="text-right">  '+data['services'][lc+1]+'</td></tr>';     	 
	    	 i++;
			} 
	   html+='<tbody></table></div>';
       $('#bodyContent').html(html);
       taxhtml='<table class="table h5 text-dark"><tbody> <tr class="b-top-none"><td colspan="2">Subtotal</td><td class="text-right"> '+data['values'][2]+'</td></tr>';
       taxhtml+=' <tr><td colspan="2">( - ) Discount @ <em id="DiscountPercent"></em> %</td><td class="text-right"> <em class="discountEnterEm">0.00</em></td></tr>';
          for (lc = 0; lc < data['taxes'].length; lc+=4) {
         taxhtml +=' <tr><td class="text-left" colspan="2">'+data['taxes'][lc]+' ('+data['taxes'][lc+2]+' %) </td><td class="text-right"> <em>'+data['taxes'][lc+1]+'</em></td></tr>';     	 
        }
     	taxhtml+='<tr class="h4"><td colspan="2">Grand Total</td><td class="text-right"> <em class="finalAmount">0.00</em></td></tr>';
     	taxhtml+='<tr><td  colspan="3"><strong class="amountinwords"></strong></td></tr></tbody></table>';
    	 
         $('#dateGe').html('Invoice Generated On '+data['values'][7]);
         $('#invoicePeriod').html('Invoice Period : '+data['values'][6]);
		 $('#invoiceId').html('Invoice Number	: <strong>'+data['values'][1]+'</strong>');
		 $('#invoiceIdSet').html('Invoice Id-'+data['values'][1]);
         $('#taxContent').html(taxhtml);
         $('.finalAmount').html(Math.round(data['values'][5]).toFixed(2));
         $('#amountGet').val(data['values'][5]);
         $('#DiscountPercent').html(data['values'][3]);
         $('.discountEnterEm').html(data['values'][4]);
         $('.amountinwords').html(firstToUpperCase(inWords(Math.round(data['values'][5]))));
         $('.view').show();
         $('.unpaidMsg').html('Unpaid');
         $('.unpaidMsg,.status').hide();
         if(data['values'][8]=='due'){
         $('.comId').val( $('#companyName :selected').val());
         $('#invoiceIdondue').val(data['values'][1]);
         $('#payNowbutton,.unpaidMsg').show();
         }else{
          	$('.status').show();$('#payNowbutton').hide();
         }
         $('#invoiceFormDiv,#msgBox').show();
          }
       
       $(document).on('click', "#back", function (e) {
           e.preventDefault();
           if(flag==0){
        	   viewAllInvoice();
           }
           $('#invoiceFormDiv').hide();
           $('#invoiceDiv').show();
       });
       

       $(document).on('click', "#submitEmailOn", function (e) {
	         e.preventDefault();
	         $('#error-msg').html('');
	         if($('#emailText').val()!=''){
	        	 $('#error-msg').html('');
	         $.ajax({
	               dataType: 'html',
	               type: "POST",
	               url: "php/invoice.handle.php",
	               cache: false,
	               data: $('#invoiceEmailForm').serialize(),
	               beforeSend:function(){
	               	$('#submitEmailOn').button('loading');
	                },
	              success: function (data) {
	            	  var json_obj = $.parseJSON(data); //parse JSON
	            	  if(json_obj[0]!='error'){
	            	  $('#error-msg').html('<div class="alert alert-danger fade in" id="msgBox">Invoice Sent Successfully   <button data-dismiss="alert" class="close close-sm css" type="button"><i class="fa fa-times"></i></button></div>');
                      $('#hideDiv').hide();
                      setTimeout(function(){ $('#emailInvoice').popover('destroy'); }, 2000);
 	            	  }
	            	  $('#submitEmailOn').button('reset');
	            	  }
	           });
	         }else{
		         $('#error-msg').html('Enter Valid EmailId');
		         }
	 });
    </script>
</body>
</html>
