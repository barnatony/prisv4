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
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<style>
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



#companyName_chosen {
	width: 100% !important;
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
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading">
						Invoice
						<button
							class="btn btn-sm btn-default  pull-right displayHide view"
							type="button" id="emailInvoice">
							<i class="fa fa-envelope-o"></i> Email
						</button>
					</header>
					<div class="popover-markup hidden">
						<section class="panel">
							<div class="content hide">
								<form class="form-horizontal" role="form" id="invoiceEmailForm"
									method="post">
									<div class="col-lg-12">
										<div class="input-group">
											<input type="hidden" class="form-control comId" name="comId">
											<input type="hidden" name="invoiceId" class="invoiceIdValue">
											<input type="hidden" name="act"
												value="<?php echo base64_encode("!sentEmail");?>" /> <input
												type="text" class="form-control" name="emailId"> <span
												class="input-group-btn">
												<button class="btn btn-default" id="submitEmailOn"
													type="button">SEND</button>
											</span>
										</div>
									</div>
								</form>
							</div>
						</section>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" method="post"
							id="invoiceFristForm">
							<div class="col-lg-12">

								<div class="col-lg-4">
									<header>Company Name</header>
									<select class="form-control" id="companyName">
										<option value="">Select Company</option>
                                        <?php
                                        require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/company.class.php");
                                        $company = new Company ();
                                        $company->conn = $conn;
                                        $companyVal = $company->filterCompany (1);
                                        foreach ( $companyVal['data'][0] as $row ) {
                                        	echo "<option data-id='" . $row['company_db_name'] . "' value='" . $row['company_id'] . "'>" . $row['company_name'] . "</option>";
                                        }
     /*                                   
		$stmt = mysqli_prepare ( $conn, "SELECT company_id,company_name,company_db_name FROM company_details WHERE  enabled =1 " );
		$result = mysqli_stmt_execute ( $stmt );
		mysqli_stmt_bind_result ( $stmt, $companyId, $companyName, $userName );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			echo "<option data-id='" . $userName . "' value='" . $companyId . "'>" . $companyName . "</option>";
}*/
																																								?>
                                           </select>

								</div>

								<div class="col-lg-4">
									<header>Invoice Date</header>
									<input type="text" id="monthDate" class="form-control"> <label
										class="help-block text-danger" id="error-msg"
										style="margin-bottom: 0px;"></label>
								</div>
							</div>
							<div class="col-lg-2">
								<header>`</header>
								<button type="submit" class="btn btn-sm btn-success"
									id="submitForm">GO</button>
								<button type="submit" class="btn btn-sm btn-danger" id="cancel">Cancel</button>
							</div>
						</form>

					</div>
				</section>
				<div id="loader" style="width: 81%;"></div>
				<section class="panel displayHide" id="invoiceFormDiv">
					<div class="col-lg-12 panel panel-primary">
						<!--<div class="panel-heading navyblue"> INVOICE</div>-->
						<div class="panel-body" id="printableArea">
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
										<h3 class="alert alert-danger view status1">
											Unpaid<em id="content"></em>
										</h3>
										<div class="popover-markup hidden">
											<section class="panel">
												<div class="head hide">Information</div>
												<div class="content hide">
													<form class="form-horizontal" role="form" id="dueEditFom"
														method="post">
														<div class="col-lg-12">
															<div class="form-group">
																<label for="dname"
																	class="col-lg-5 col-sm-5 control-label">Due Date</label>
																<div class="col-lg-7">
																	<input type="hidden" class="form-control"
																		name="invoiceId" id="invoiceIdondue"> <input
																		type="hidden" class="form-control comId" name="comId">
																	<input type="hidden" name="act"
																		value="<?php echo base64_encode("!dueDateupdate");?>" />
																	<input type="text" class="form-control"
																		name="dueOnEdit" id="dueOnEdit">
																</div>
															</div>
														</div>
														<button type="submit" class="btn btn-default btn-block"
															id="submitDueOn">SET</button>
													</form>
												</div>
											</section>
										</div>
										<h3 class="alert alert-success view status">Paid</h3>
									</div>

									<div class="col-lg-5">
										<ul class="unstyled pull-right">

											<li class="displayHide view" id="invoiceId"></li>
											<br>
											<!--  <li id="dateFix"></li>
                                      <br> -->
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

									<div class="col-lg-7">
										<!--  <ul class="unstyled amounts pull-right" style="width:100%">
                               <li> <p id="detailsContent"></p></li>
                                 </ul> -->
									</div>

									<div class="col-lg-5">
										<div id="taxContent"></div>
									</div>


								</div>
							</div>
						</div>

						<div class="panel-body">
							<form class="form-horizontal" role="form" method="post"
								id="invoiceForm">
								<input type="hidden" id="invoiceAmount" name="invoiceAmount"> <input
									type="hidden" name="act" id="act" /> <input type="hidden"
									id="discountAmount" value="0" name="discountAmount"> <input
									type="hidden" id="discountedAmount" value="0"
									name="discountedAmount"> <input type="hidden" id="taxAmount"
									value="0" name="taxAmount"> <input type="hidden" id="netAmount"
									value="0" name="netAmount"> <input type="hidden" id="taxVal"> <input
									type="hidden" id="taxArray" name="taxArray"> <input
									type="hidden" id="servArray" name="servArray"> <input
									type="hidden" id="curentMOnth" name="curentMOnth"> <input
									type="hidden" name="invoiceId" class="invoiceIdValue"> <input
									type="hidden" class="comId" name="comId">
								<div class="row text-center invoice-btn displayHide view"
									id="dateGe"></div>
								<div class="row text-center invoice-btn displayHide generate">
									<button type="submit" id="generateInvoice"
										class="btn btn-success btn-sm pull-right">
										<i class="fa fa-check"></i> Generate
									</button>
								</div>
								<input type="hidden" id="monthDateSub">
							</form>
						</div>
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
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		$('#invoiceFristForm')[0].reset();
		$('#monthDateSub').val('');
		$('#companyName,#serviceChosen,#taxChosen').chosen();  
		 $('#monthDate').datetimepicker({
       	  format: 'DD/MM/YYYY',
       	 });
	});
    
	 $('#invoiceFristForm').on('submit', function (e) {
		  e.preventDefault();
		  var newDate=$('#monthDate').val().split('/')[1]+$('#monthDate').val().split('/')[2];
		  if(newDate!= $('#monthDateSub').val()){
		 $('#monthDateSub').val($('#monthDate').val().split('/')[1]+$('#monthDate').val().split('/')[2]);
		  if($('#monthDate').val()==''||  $('#companyName :selected').val() ==''){
	        	 $('#error-msg').html('* Enter All Required Fields');
	        	 $('#monthDateSub').val('');
		         }else{
		   invoiceShow();
		         }
		  }
	   });

	 function invoiceShow(){
		 $('#error-msg').html('');
	       $('.comId').val($('#companyName :selected').val());
	       $('#discountEnter').val(0);
		  var userName=$('#companyName :selected').data('id');
		   $.ajax({
			   dataType: 'html',
	           type: "POST",
	           url: "php/invoice.handle.php",
	           cache: false,
	           data: { act: '<?php echo base64_encode("!invoiceSelect");?>', comId: $('#companyName :selected').val(),userName:userName,curentMOnth:$('#monthDate').val()},
	           beforeSend:function(){
	        	  $('#submitForm').button('loading');
	        	  $('#loader').loading(true);
	           },
		       success: function (data) {
		          data = JSON.parse(data);
		         setData(data[2]);
		          $('#invoiceFormDiv').show();
          	  $('#submitForm').button('reset');
          	  $('#loader').loading(false);
          	 }
		   		  });
	   		  }
		  
	   function finalAmunt(amount,percent){
	   finalAmount=(amount*percent)/100;
	   return (finalAmount>0)?finalAmount:0;
      }
	   
	    $(document).on('blur', "#discountEnter", function (e) {
			  e.preventDefault();
			  $('#discountAmount').val(($(this).val())?$(this).val():0);
			  discountedAmount=finalAmunt(($('#invoiceAmount').val())?$('#invoiceAmount').val():Number($('#invoiceAmountEdit').html()),$('#discountAmount').val());
			  $('#discountedAmount').val(discountedAmount);
			  $('.discountEnterEm').html(discountedAmount.toFixed(2));
			  discountNet=(($('#invoiceAmount').val())?$('#invoiceAmount').val():Number($('#invoiceAmountEdit').html()))-discountedAmount;
			  var taxValue=  $('#taxVal').val().split(',');
			  taxValue.pop();
			  sum=0; deduction=0;
			  var taxArray=[[]];
			  for (lc = 0; lc < taxValue.length; lc++) {
            	 var deductionVal=finalAmunt(discountNet,Number($('#'+taxValue[lc]).data('id')));
				 deduction +=deductionVal;
				 taxArray[lc]=taxValue[lc].split('_')[1]+'='+deductionVal+'='+$('#'+taxValue[lc]).data('id')+'=tax';
                 $('#'+taxValue[lc]).html(deductionVal.toFixed(2));
              }
              $('#taxArray').val(taxArray);
              $('#taxAmount').val(deduction.toFixed(2));
               var finalAmount=Number(discountNet)+deduction;
			  $('.finalAmount').html(Math.round((finalAmount>0)?finalAmount:0).toFixed(2));
			  $('#netAmount').val((finalAmount>0)?finalAmount:0);
			  if(parseInt(finalAmount)>0)
			  	$('.amountinwords').html((firstToUpperCase(inWords(Math.round((finalAmount>0)?finalAmount:0)))));
			  else $('.amountinwords').html("-");
	  });
		  
	   $(document).on('click', "#cancel", function (e) {
	         e.preventDefault();
	        $("#companyName option[value='']").prop("selected", true).trigger('chosen:updated');
         	$('#monthDate,#monthDateSub').val('');
         	$('#invoiceFormDiv,.view').hide();
	   });
	   
	   $(document).on('click', "#generateInvoice", function (e) {
	         e.preventDefault();
	         var actVal='<?php echo base64_encode("!generateInvoice");?>';
	         $('#act').val(actVal);
	         generateInvoice();
	   });
	   
	   $(document).on('click', "#discountUpdate", function (e) {
	         e.preventDefault();
	         var actVal='<?php echo base64_encode("!updateDiscount");?>';
	         $('#act').val(actVal);
	         generateInvoice();
	   });

	 function generateInvoice(){
		 $.ajax({
             dataType: 'html',
             type: "POST",
             url: "php/invoice.handle.php",
             cache: false,
             data: $('#invoiceForm').serialize(),
             beforeSend:function(){
            	$('#generateInvoice').button('loading'); 
            	$('#loader').loading(true);
  	         },
            success: function (data) {
            	 jsonobject = JSON.parse(data);
            	 if (jsonobject[0] == "success") {
            	 setData(jsonobject[2]);
           }else{
              	BootstrapDialog.alert(jsonobject[1]);
             }
          $('#generateInvoice').button('reset');
          $('#loader').loading(false);
	       }
            
  });
  }
	   $(document).on('submit', "#dueEditFom", function (e) {
	         e.preventDefault();
	        $.ajax({
	                 dataType: 'html',
	                 type: "POST",
	                 url: "php/invoice.handle.php",
	                 cache: false,
	                 data: $('#dueEditFom').serialize(),
	                 beforeSend:function(){
	                	$('#submitDueOn').button('loading'); 
	                	$('#loader').loading(true);
	      	         },
	                success: function (data) {
	                	 jsonobject = JSON.parse(data);
	                	 BootstrapDialog.alert(jsonobject[1]);
	                	 setData(jsonobject[2]);
	              $('#submitDueOn').button('reset');
	              $('#loader').loading(false);
	  	               
	           }
	                
	      });
	 });

		 function setData(data){
			 $('#emailInvoice').popover({ 
     			   html: true,
     			   placement: "left",
     		       content: function () {
     			        return $(this).parent().parent().parent().find('.content').html();
     			    }
     			}); 
			  $('#curentMOnth').val($('#monthDate').val());
			  $('#detailsContent').html('');
	          if(data.address){
	        	  $('#dateFix').html('Invoice Date :'+$('#monthDate').val());
			       var  str=data.address[0];
			   }else{
				   var  str=data.values[0];
			  }
	          $('#company_name').html(str.split('_')[0]);
       	  $('#company_build_name').html(str.split('_')[1]);
       	  $('#company_area').html(str.split('_')[2]);
       	  $('#company_street').html(str.split('_')[3]);
       	  $('#company_state').html(str.split('_')[4]);
       	  $('#company_pin_code').html(str.split('_')[5]);
       	  $('#company_city').html(str.split('_')[6]);
       	  $('#company_email').html(str.split('_')[7]);
       	  $('#company_mobile').html(str.split('_')[8]);
       	  if(data.address){
       	  var html="";sum=0;var noteContent='';
       	  var servArray =[];

       html+='<div class="table-responsive"><table class="table invoice-items"><thead><tr class="h4 text-dark"><th id="cell-id"   class="text-semibold">#</th><th id="cell-item" class="text-semibold">Particulars</th><th id="cell-item" class="text-semibold text-right">Amount</th></tr></thead><tbody>';
var i=1;
         //noteContent+='<div class="table-responsive"><table class="table invoice-items"><thead><tr class="h5 text-dark"><th id="cell-id"   class="text-semibold">Particulars</th><th id="cell-item" class="text-semibold">Employee Count</th></tr></thead><tbody>';

     	 for (lc = 0; lc < data.services.length; lc++) {
           	  if(data['services'][lc]["is_variable"]==1){
           		// noteContent+='<tr><td>'+ data['services'][lc]["serv_name"]+'</td><td>'+data['services'][lc]["empCount"]+' </td></tr>';
        		 var extraconditon=data['services'][lc]["qty"]+'|'+data['services'][lc]["exempt_to"];
        		 var values=' ( '+data['services'][lc]["qty"]+' * '+Math.round(data['services'][lc]["default_price"])+' )';
                 }else{
                 var extraconditon=data['services'][lc]["empCount"];
             	 var values='';
                }
             servArray[lc]=data['services'][lc]["serv_id"]+'='+data['services'][lc]["serv_amount"]+'='+extraconditon+'=service';
             sum +=Number(data['services'][lc]["serv_amount"]);
       	   html +='<tr><td class="text-semibold text-dark text-left">'+i+'</td><td class="text-semibold text-dark text-left">PRIS -'+data['services'][lc]["serv_name"]+values+'</td><td class="text-right">  '+data['services'][lc]["serv_amount"]+'</td></tr>';     	 
            i++;
         	  }
    	  
     	    //noteContent+='<tbody></table></div>';
    	    //$('#detailsContent').html(noteContent);
    	    
      	  	 html+='<tbody></table></div>';
           	   $('#bodyContent').html(html);
             var taxArray=[[]];
             var taxhtml="";deduction=0;var taxFoun="";
             taxhtml='<table class="table h5 text-dark"><tbody> <tr class="b-top-none"><td colspan="2">Subtotal</td><td class="text-right"> '+sum.toFixed(2)+'</td></tr>';
             taxhtml+=' <tr><td colspan="2">( - ) Discount @ <input type="text"  id="discountEnter"/> %</td><td class="text-right"> <em class="discountEnterEm">0.00</em></td></tr>';
             
          	  for (lc = 0; lc < data['taxes'][0].length; lc++) {
           	  var deductionAmount=finalAmunt(sum,data['taxes'][0][lc]["tax_percentage"]);
            	  deduction +=deductionAmount;
            	 taxArray[lc]=data['taxes'][0][lc]["tax_id"]+'='+deductionAmount+'='+data['taxes'][0][lc]["tax_percentage"]+'=tax';
                taxFoun+='tax_'+data['taxes'][0][lc]["tax_id"]+'_val,';
           	  taxhtml +=' <tr><td class="text-left" colspan="2">'+data['taxes'][0][lc]["title"]+' ('+data['taxes'][0][lc]["tax_percentage"]+' %)</td><td class="text-right"> <em id="tax_'+data['taxes'][0][lc]["tax_id"]+'_val" data-id="'+data['taxes'][0][lc]["tax_percentage"]+'">'+deductionAmount.toFixed(2)+'</em></td></tr>';     	 
             }
          	taxhtml+='<tr class="h4"><td colspan="2">Grand Total</td><td class="text-right"> <em class="finalAmount">0.00</em></td></tr>';
        	taxhtml+='<tr><td  colspan="3"><strong class="amountinwords"></strong></td></tr></tbody></table>';
       	      $('#taxArray').val(taxArray);
              $('#servArray').val(servArray);
         	  $('#taxVal').val(taxFoun);
         	  $('#invoiceAmount').val(sum);
         	  $('#taxAmount').val(deduction.toFixed(2));
         	  finalTotal=sum+deduction;
         	  $('#netAmount').val((finalTotal>0)?finalTotal:0);
       	  $('#taxContent').html(taxhtml);
       	  $('.finalAmount').html(Math.round((finalTotal>0)?finalTotal:0).toFixed(2));
		  if(parseInt(finalTotal)>0)
     	  	$('.amountinwords').html(firstToUpperCase(inWords(Math.round((finalTotal>0)?finalTotal:0))));
       	  else $('.amountinwords').html("-");
       	  $('.view').hide();$('.generate').show();
       	  }else{
           	  var html="";var taxhtml="";var noteContent="";
           	  ///design already invoice geneaate persond
           	   html+='<div class="table-responsive"><table class="table invoice-items"><thead><tr class="h4 text-dark"><th id="cell-id"   class="text-semibold">#</th><th id="cell-item" class="text-semibold">Particulars</th><th id="cell-item" class="text-semibold text-right">Amount</th></tr></thead><tbody>';
	    var i=1;
	    
	  //  noteContent+='<div class="table-responsive"><table class="table invoice-items"><thead><tr class="h5 text-dark"><th id="cell-id"   class="text-semibold">Particulars</th><th id="cell-item" class="text-semibold">Employee Count</th></tr></thead><tbody>';
	    	for (lc = 0; lc < data.services.length; lc+=4) {
	    	 if(data['services'][lc+2].split('|')[1]){
                 var values=' ( '+data['services'][lc+2].split('|')[0]+' active Employees ) ';
		    }else{
			    var values='';
			} html +='<tr><td class="text-semibold text-dark text-left">'+i+'</td><td class="text-semibold text-dark text-left">PRIS -'+data['services'][lc]+values+'</td><td class="text-right">  '+data['services'][lc+1]+'</td></tr>';     	 
	    	 i++;
 			} 
	      //noteContent+='<tbody></table></div>';
     	  html+='<tbody></table></div>';
           //  $('#detailsContent').html(noteContent);
           $('#bodyContent').html(html);
           taxFoun="";
          taxhtml='<table class="table h5 text-dark"><tbody> <tr class="b-top-none"><td colspan="2">Subtotal</td><td class="text-right" id="invoiceAmountEdit"> '+data['values'][2]+'</td></tr>';
          taxhtml+=(data['values'][8]=='due')?'<tr id="tdClear"><td colspan="2"><i title="Edit Discount" data-original-title="" id="discountedit" style="cursor:pointer;" class="fa fa-pencil pull-right" aria-hidden="true"></i>( - ) Discount @ <em id="DiscountPercent"></em> %</td><td class="text-right"> <em class="discountEnterEm">0.00</em></td></tr>':
        	  '<tr id="tdClear"><td colspan="2"> ( - ) Discount @ <em id="DiscountPercent"></em> %</td><td class="text-right"> <em class="discountEnterEm">0.00</em></td></tr>';
          
             for (lc = 0; lc < data['taxes'].length; lc+=4) {
            	 taxFoun+='tax_'+data['taxes'][lc+3]+'_val,';
            taxhtml +=' <tr><td class="text-left" colspan="2">'+data['taxes'][lc]+' ('+data['taxes'][lc+2]+' %) </td><td class="text-right"> <em id="tax_'+data['taxes'][lc+3]+'_val" data-id="'+data['taxes'][lc+2]+'">'+data['taxes'][lc+1]+'</em></td></tr>';     	 
           }
        	taxhtml+='<tr class="h4"><td colspan="2">Grand Total</td><td class="text-right"> <em class="finalAmount">0.00</em></td></tr>';
        	taxhtml+='<tr><td  colspan="3"><strong class="amountinwords"></strong></td></tr></tbody></table>';
        	$('#taxVal').val(taxFoun);
            //$('#dateFix').html('Invoice Date : '+data['values'][7]);
            $('#dateGe').html('Invoice Generated On '+data['values'][7]);
            $('#invoicePeriod').html('Invoice Period : '+data['values'][6]);
		     $('#invoiceId').html('Invoice Number	: <strong>'+data['values'][1]+'</strong>');
            $('#taxContent').html(taxhtml);
            $('.finalAmount').html(Math.round(data['values'][5]).toFixed(2));
            $('#DiscountPercent').html(data['values'][3]);
            $('.discountEnterEm').html(data['values'][4]);
            $('.amountinwords').html(firstToUpperCase(inWords(Math.round(data['values'][5]))));
            $('.view').show();$('.generate').hide();
            $('.status1,.status').hide();
            $('#invoiceIdondue,.invoiceIdValue').val(data['values'][1]);
            if(data['values'][8]=='due'){
            $('.comId').val( $('#companyName :selected').val());
            $('.status1').html('Unpaid <em  title="Edit Dueon" >( Due On -'+data['values'][9]+') <i id="dueEditContent" style="cursor:pointer;" class="fa fa-pencil pull-right" aria-hidden="true"></i> </em>');
            $('#invoiceIdondue,.invoiceIdValue').val(data['values'][1]);
            $('#dueEditContent').popover({ 
   				   html: true,
   				   placement: "left",
   			       title: function () {
   				        return $(this).parent().parent().parent().find('.head').html();
   				    },
   				    content: function () {
   				        return $(this).parent().parent().parent().find('.content').html();
   				    }
   				}).on('shown.bs.popover', function () {
   				$('#dueOnEdit').datetimepicker({format: 'DD/MM/YYYY'});
   				$('#dueOnEdit').val(data['values'][9]);
   			    });
			    $('.status1').show();
              }else{
             	$('.status').show();
              }
             
         }
        }
		 $(document).on('click', "#submitEmailOn", function (e) {
	         e.preventDefault();
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
	            	  BootstrapDialog.alert(json_obj[1]);
	            	  $('#submitEmailOn').button('reset');
	            	  if(json_obj[0]!='error'){
		            	  $('#emailInvoice').popover('destroy');}
		              }
	           });
	 });
		 
		 $(document).on('click', "#discountedit", function (e) {
	         e.preventDefault();
	         var oldDiscount=$('#DiscountPercent').html();
	         $('#tdClear').empty();
		        $('#tdClear').html('<td colspan="2">( - ) Discount @ <input type="text" value="'+oldDiscount+'"  id="discountEnter"/> %</td><td class="text-right"> <em class="discountEnterEm">0.00</em>  <i title="Cancel Discount" data-original-title="" id="discountCancel" style="font-size: 21px;cursor:pointer;" class="fa fa-times" aria-hidden="true"></i>  <i title="Update Discount" data-original-title="" id="discountUpdate" style="cursor:pointer;font-size: 21px;" class="fa fa-floppy-o" aria-hidden="true"></i></td>');
		 });
		 
		 $(document).on('click', "#discountCancel", function (e) {
	         e.preventDefault();
	         invoiceShow();
	          });
	 </script>
</body>
</html>
