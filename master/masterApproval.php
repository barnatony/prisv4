<?php
require_once ( dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
$company_id = $_REQUEST ['id'];
?>
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

<title>master Approval</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/datepicker.css" />

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

.adjustlabel {
	height: 34px;
}

.head, .center_adjust {
	text-align: center;
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
				<!-- Modal -->

				<div class="modal fade" id="myModal2" tabindex="-1" role="dialog"
					aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"
									aria-hidden="true">&times;</button>
								<h4 class="modal-title">Confirm Reject</h4>
							</div>
							<form id="reject_form" method="POST" class="form-horizontal"
								role="form">
								<div class="modal-body">
									<div class="col-lg-12">
										<div class="col-lg-6">
											<label class="col-lg-12 control-label"
												style="text-align: center"> Specify The Reason</label>
										</div>
										<div class=" col-lg-6 ">
											<input type="hidden" name="act"
												value="<?php echo base64_encode("reject");?>" /> <input
												type="hidden" class="form-control" name="company_id"
												value="<?php echo  $company_id; ?>"> <input type="hidden"
												class="form-control" name="cdb" id="company_db_name"
												required> <input type="text" class="form-control"
												name="rReason" id="reject_reason" required>
										</div>
									</div>
								</div>
								<div class="modal-footer" style="margin-top: 36px;">
									<button data-dismiss="modal" class="btn btn-default"
										type="button">Cancel</button>
									<input class="btn btn-danger" type="submit" id="modalConfirm"
										value="Reject">
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Modal -->

				<!-- page start-->
				<form id="masterNewApproval" method="post" action=""
					enctype="multipart/form-data">
					<input type="hidden" name="act_sub" id="act" />
					<section class="panel">
						<header class="panel-heading" style="height: 60px">
							Master Approval <a href="listView.php"><button type="button"
									class="col-lg-1 btn btn-primary btn-sm pull-right" id="back"
									style="margin-right: 7px;">
									<i class="fa fa-arrow-circle-left"></i> Back
								</button></a>
							<button type="button"
								class="col-lg-1 btn btn-danger  btn-sm pull-right"
								data-toggle="modal" href="#myModal2" id="reject"
								style="margin-right: 7px">
								<i class="fa fa-times"></i> Reject
							</button>
							<button type="button"
								class="col-lg-1 btn btn-success btn-sm pull-right" id="edit"
								style="margin-right: 7px;">
								<i class="fa fa-pencil"></i> Edit
							</button>
							<button type="button"
								class="col-lg-1 btn btn-warning btn-sm pull-right " id="approve"
								style="margin-right: 7px;">
								<i class="fa fa-check-square"></i> Approve
							</button>
							<button type="button"
								class="col-lg-1 btn btn-info btn-sm pull-right" id="update"
								style="margin-right: 7px;">
								<i class="fa fa-refresh"></i> Update
							</button>
							<button type="button"
								class="col-lg-1 btn btn-default btn-sm pull-right " id="cancel"
								style="margin-right: 7px;">
								<i class="fa fa-ban"></i> Cancel
							</button>
						</header>
					</section>
					<div class="col-sm-4">
						<section class="panel">
							<header class="panel-heading head"> Labels </header>
							<div class="panel-body ">
								<div class="row">
									<h5 style="text-align: center"> COMPANY CONTACT DETAILS </h5>
									<br>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Building Name </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class="col-lg-12 ">
											<label class="control-label"> Street </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Area </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Pincode </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> City </label>
										</div>
									</div>

									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> State </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Phone </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Mobile </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Email Id </label>
										</div>
									</div>
									
									<h5 style="text-align: center">Owner / Director / CEO Details</h5>
									<br>
									<div class="form-group adjustlabel">
										<div class="col-lg-12 ">
											<label class="control-label"> Name </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class="col-lg-12 ">
											<label class="control-label"> User Name </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Designation </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Mobile </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Email-Id </label>
										</div>
									</div>
									<h5 style="text-align: center; margin-bottom: 16px;"> HR Contact
										Details</h5>

									<div class="form-group adjustlabel">
										<div class="col-lg-12 ">
											<label class="control-label"> Name </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class="col-lg-12 ">
											<label class="control-label"> User Name </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Designation </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Mobile </label>
										</div>
									</div>
									<div class="form-group adjustlabel">
										<div class=" col-lg-12 ">
											<label class="control-label"> Email-Id </label>
										</div>
									</div>





								</div>

							</div>
						</section>
					</div>

					<div class="col-sm-4">
						<section class="panel">
							<header class="panel-heading head">
								Old Data 
							</header>
							<div class="panel-body old-data">
								<div class="row">
									<br>
									 <br>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_build_name" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_street" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_area" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_pin_code" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_city" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_state" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_phone" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_mobile" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_email" readonly="true" />
										</div>
									</div>
									<br> 
									<br>
									<br>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp1_name" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="hr_1username" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp1_desgn" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp1_phone" readonly="true" />
										</div>
									</div>

									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp1_email" readonly="true" />
										</div>
									</div>

									<br>
									<br>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp2_name" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="hr_2username" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp2_desgn" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp2_phone" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text"
												class="form-control center_adjust disabled_input"
												id="company_resp2_email" readonly="true" />
										</div>
									</div>


								</div>

							</div>
						</section>
					</div>





					<div class="col-sm-4">
						<section class="panel">
							<header class="panel-heading head">
								New Data
							</header>
							<div class="panel-body" id="newDataPanel">
								<div class="row">
									<br>
									<br>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust "
												name="cBuild" id="1company_build_name" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust "
												name="cStreet" id="1company_street" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust "
												name="cArea" id="1company_area" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cPincode" id="1company_pin_code" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cCity" id="1company_city" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cState" id="1company_state" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cPhone" id="1company_phone" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cMobile" id="1company_mobile" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cEmail" id="1company_email" readonly="true" />
										</div>
									</div>
									<br>
									<br>
									<br>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp1name" id="1company_resp1_name" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="hr_1username" id="1hr_1username" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp1desgn" id="1company_resp1_desgn" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp1phone" id="1company_resp1_phone" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp1email" id="1company_resp1_email" readonly="true" />
										</div>
									</div>
									<br>
									<br>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp2name" id="1company_resp2_name" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="hr_2username" id="1hr_2username" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp2desgn" id="1company_resp2_desgn" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp2phone" id="1company_resp2_phone" readonly="true" />
										</div>
									</div>
									<div class="form-group">
										<div class=" col-lg-12 ">
											<input type="text" class="form-control center_adjust"
												name="cResp2email" id="1company_resp2_email" readonly="true" />
										</div>
									</div>
								</div>
							</div>

						</section>
					</div>
<input
												type="hidden" class="form-control" name="company_id"
												value="<?php echo  $company_id; ?>">
				</form>
				<!-- page end-->
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
		<script src="../js/bootstrap-dialog.js"></script>

		<!--script for this page only-->
		<!-- END JAVASCRIPTS -->
		<script type="text/javascript">
           $(document).ready(function () {
        company_id="<?php echo $company_id;?>";
        
          });
           </script>
		<script type="text/javascript">
          $("#cancel,#update").hide();
          $(document).ready(function () {
              $("#masterApproval,#masterNewApproval").find(":input").not('#approve,#back,#update,#reject,#cancel,#edit').each(function () {
                  $(this).css({ 'background-color': '#FFF', 'border': '0px' });
                  $(this).attr('disabled', true);
                  $(this).attr('readonly', true);

              });
              $.ajax({
            	  dataType: 'html',
                  type: "POST",
                  url: "php/company.handle.php",
                  cache: false,
                  data: { act: '<?php echo base64_encode("select_profile_differ");?>' , 'company_id': company_id },
	              success: function (data) {
                      var json_obj = $.parseJSON(data); //parse JSON
                      if(json_obj[2].p_company_details[0])
                    $.each(json_obj[2].M_company_details[0], function (k, v) {
                          //display the key and value pair
                    	  if( k=='company_doi'){ 
                        	var valueDate=GetFormattedDate(v);
                          	$('#' + k).val(valueDate);
                            }else{
                            	$('#' + k).val(v);
                            }
                    }); 	
                      else
                      {
                    	  $(".old-data").html("<p class='text-center'> NO Changes Detected</p>");
                      }
                          
                      $('.db_name').val(json_obj[2].M_company_details[0].company_db_name);
                   	 if(json_obj[2].p_company_details[0])
                      $.each(json_obj[2].p_company_details[0], function (k, v) {
                          console.log(json_obj[2].p_company_details[0]);
                       if( k=='company_doi'){ 
                    	  	var valueDate=GetFormattedDate(v);
                         	 $('#1' + k).val(valueDate);
                           }else{
                        	   $('#1' + k).val(v);
                           }
                     });
                   	 else{
                       	 $("#newDataPanel").html("<p class='text-center'> NO Changes Detected</p>");
                       	 $("#reject,#edit,#approve").remove();
                   	 }
                       	 
                  }

              });

          });
          $("#edit").click(function () {
              $("#cancel,#update").show();
              $("#edit,#reject,#approve").hide();
              $("#masterNewApproval").find(":input").not('.disabled_input').each(function () {
                  $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                  $(this).attr('readonly', false);
                  $(this).removeAttr('disabled');

              });
          });
        
          $("#cancel").click(function () {
             $("#cancel,#update").hide();
              $("#edit,#reject,#approve").show();
              $("#masterNewApproval").find(":input").not('.pull-right').each(function () {
                  $(this).css({ 'background-color': '#FFF', 'border': '0px' });
                  $(this).attr('disabled', true);
                  $(this).attr('readonly', true);

              });

          });
        
         
          $("#modalConfirm").click(function () {
             // $("#newDataPanel").hide();
           //  $("#newDataPanel").html("<p class='text-center'> NO Changes Detected</p>");
              $("#masterNewApproval").find(":input").each(function () {
                  $(this).css({ 'background-color': '#FFF', 'border': '0px' });
                  $(this).attr('disabled', true);
                  $(this).attr('readonly', true);

              });
          });
          $("#update").click(function (e) {
              $('#act').val('approve');
              BootstrapDialog.show({
	                title:'Confirmation',
                  message: 'Are Sure you want to Approve these changes',
                  buttons: [{
                      label: 'YES',
                      cssClass: 'btn-primary',
                      autospin: true,
                      action: function(dialogRef){
                    	  $("#masterNewApproval").find(":input").not('.disabled_input').each(function () {
                              $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                              $(this).attr('readonly', false);
                              $(this).removeAttr('disabled');

                          });
                      	 $.ajax({
   		                    dataType: 'html',
   		                    type: "POST",
   		                    url: "php/company.handle.php",
   	                        cache: false,
   	                        data: $('#masterNewApproval').serialize(),
   	               
   		                    complete:function(){
 		                    	 dialogRef.close();
 		                     },
   		                    success: function (data) {
   		                    	data = JSON.parse(data);
   		                        if (data[0] == "success") {
   		                         $("#masterNewApproval").find(":input").not('#back').each(function () {
   	   		                        
   	                              $(this).css({ 'background-color': '#FFF', 'border': '0px' });
   	                              $(this).attr('disabled', true);
   	                              $(this).attr('readonly', true);
   	                              });
   							 $('#back').show();
   	                         $("#edit,#reject,#approve,#cancel,#update").hide();
   	                          BootstrapDialog.alert(data[1]);
   	                       
   		                        }else if (data[0] == "error") {
	                                    alert(data[1]);
	                                }
   		                    }
   		                });
                              		                            
                      }
                  }, {
                      label: 'Close',
                      action: function(dialogRef){
                          dialogRef.close();
                      }
                  }]
              });
          });

          $("#approve").click(function (e) {
              $('#act').val('approve');
              BootstrapDialog.show({
	                title:'Confirmation',
                  message: 'Are Sure you want to Approve these changes',
                  buttons: [{
                      label: 'YES',
                      cssClass: 'btn-primary',
                      autospin: true,
                      action: function(dialogRef){
                    	  $("#masterNewApproval").find(":input").not('.disabled_input').each(function () {
                              $(this).css({ 'background-color': '', 'border': '1px solid #E2E2E4' });
                              $(this).attr('readonly', false);
                              $(this).removeAttr('disabled');

                          });
                      	 $.ajax({
   		                    dataType: 'html',
   		                    type: "POST",
   		                    url: "php/company.handle.php",
   	                        cache: false,
   	                        data: $('#masterNewApproval').serialize(),
   		                    complete:function(){
 		                    	 dialogRef.close();
 		                     },
   		                    success: function (data) {
   		                    	data = JSON.parse(data);
   		                        if (data[0] == "success") {
   		                         $("#masterNewApproval").find(":input").not('#back').each(function () {
   	                              $(this).css({ 'background-color': '#FFF', 'border': '0px' });
   	                              $(this).attr('disabled', true);
   	                              $(this).attr('readonly', true);
   	                          });
   							 $('#back').show();
   	                         $("#edit,#reject,#approve,#cancel,#update").hide();
   	                          BootstrapDialog.alert(data[1]);
   		                            
   		                        }else if (data[0] == "error") { 
	                                    alert(data[1]);
	                                }
   		                    }
   		                });
                              		                            
                      }
                  }, {
                      label: 'Close',
                      action: function(dialogRef){
                          dialogRef.close();
                      }
                  }]
              });
          });
          
          $('#reject_form').on('submit', function (e) {
              e.preventDefault();
              $.ajax({
            	  dataType: 'html',
                  type: "POST",
                  url: "php/company.handle.php",
                  cache: false,
                  data: $('#reject_form').serialize(), 
            	   success: function (data) {
                      data1 = JSON.parse(data);
                      if (data1[0] == "success") {
                    	  $('#back').removeAttr('disabled');
                    	  $('#back').removeAttr('readonly');
                    	  $('#back').css({ 'background-color': '#41CAC0', 'border': '1px solid #E2E2E4' });
                    	  $('#back').show();
                    	  $('.close').click();
                          $('#approve, #reject ,#edit ').hide();
                          BootstrapDialog.alert(data1[1]);
                      
                      $("#newDataPanel").html("<p class='text-center'> NO Changes Detected</p>");

                  }
                      }

              });

          });
          
          
        /*          
          $('#masterNewApproval').on('submit', function (e) {
              e.preventDefault();
              $.ajax({
            	  dataType: 'html',
                  type: "POST",
                  url: "../master/php/rejectCompanyedit.php",
                  cache: false,
                  data: $('#masterNewApproval').serialize(),
                   success: function (data) {
                      data1 = JSON.parse(data);
                      if (data1[0] == "success")
                           {
                    	  $("#masterNewApproval").find(":input").not('#back').each(function () {
                              $(this).css({ 'background-color': '#FFF', 'border': '0px' });
                              $(this).attr('disabled', true);
                              $(this).attr('readonly', true);

                          });
						 $('.close').click();
                          $('.model_msg').click();
                          $('#succes_msg').html(data1[1]);
                          $('#back').show();
                          $("#edit,#reject,#approve,#cancel,#update").hide();
                        
                      }

                  }

              });


          });
          */
        
      </script>

</body>
</html>
