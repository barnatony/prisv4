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

<title>Asset Requests</title>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />

<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
#atCondition_chosen, #atType_chosen, #status_chosen,
	#e_atCondition_chosen, #e_atType_chosen, #e_status_chosen,
	#e_assetId_chosen, #employee_id_chosen, #assetId_chosen {
	width: 100% !important;
}

@media ( min-width : 992px) {
	.modal-lg {
		width: 900px;
	}
}

.asset_type, .employee_type, .type_asset {
	display: none;
}

.bio-row {
	width: 100%;
}

.ajax_loader {
	display: block;
	margin: 0px auto;
}

.bio-graph-info {
	background: #fcfcfc;
}
</style>

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
					<header class="panel-heading">
						<span id="panel-heading"> Asset Requests </span>
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn btn-sm btn-info"
								style="margin-top: -5px;">
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</header>
					<form class="form-horizontal" role="form" method="post"
						id="assetRequestForm">
						<input type="hidden" name="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
						<div class="col-lg-12">
                           <?php
																											/* Include Class Library */
																											require_once (LIBRARY_PATH . "/assetRequest.class.php");
																											require_once (LIBRARY_PATH . "/employee.class.php");
																											
																											$employee = new Employee ();
																											$employee->conn = $conn;
																											$employeeOnly = $employee->select (0);
																											
																											$asset = new AssetRequest ();
																											$asset->conn = $conn;
																											?>
            <div class="panel-body" id="add-assetTracker-toggle" style="display: none">
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Employees:</label>
										<div class="col-lg-7">
											<select class="form-control" id="employee_id"
												name="employee_id">
												<option value="">Select Employee</option> 
                                         <?php
																																									foreach ( $employeeOnly as $row ) {
																																										echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																									}
																																									?>
                                        </select> <label
												class="employee_type text-danger">Please Select any one Employee</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Type</label>
										<div class="col-lg-7">
											<select class="form-control" id="atType" name="atType">
												<option value="">Select Type</option>
												<option value="laptop">Laptop</option>
												<option value="mobile">Mobile</option>
												<option value="car">Car</option>
												<option value="bike">Bike</option>
												<option value="pendrive">Pen drive</option>
												<option value="harddisk">Hard disk</option>
											</select> <label class="type_asset text-danger">Please Select any one
												Type</label>
										</div>

									</div>



									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">From Date</label>
										<div class="col-md-7 col-xs-12 input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input class="form-control" name="fromDate" id="fromDate"
												type="text">
										</div>
									</div>


								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">To Date</label>
										<div class="col-md-7 col-xs-12 input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input class="form-control" name="toDate" id="toDate"
												type="text">
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Purpose</label>
										<div class="col-lg-7">
											<input class="form-control" type="text" name="purpose"
												id="purpose" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Description</label>
										<div class="col-lg-7">
											<textarea class="form-control" id="desc" name="desc" row="2"></textarea>
										</div>
									</div>

								</div>
								<div class="clearfix"></div>

								<div id="" class="well show">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 col-sm-5  control-label">Status</label>
											<div class="col-lg-7">
												<select class="form-control" id="status" name="status">
													<option value="P">Pending</option>
													<option value="I">Issue</option>
													<option value="R">Return</option>
													<option value="D">Decline</option>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Asset Id</label>
											<div class="col-lg-7">
												<select class="form-control" id="assetId" name="asset_id">
													<option value="No">Select the Available Assets</option>


												</select> <label class="asset_type text-danger">Please Select any one
													Asset</label>
											</div>
										</div>
									</div>
									<div class="col-lg-6">


										<div class="form-group">
											<label class="col-lg-5 col-sm-5  control-label">Issued On</label>
											<div class="col-lg-7">
												<input class="form-control" type="text" name="issued_on"
													id="issued_on" />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 col-sm-5  control-label">Issued Notes</label>
											<div class="col-lg-7">
												<textarea class="form-control" name="issued_notes"
													id="issued_notes"></textarea>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label"></label>
										<div class="col-lg-6 col-lg-offset-6">
											<button type="button"
												class="btn btn-sm btn-danger pull-right closeModel"
												style="margin-left: 1%">Cancel</button>
											<button type="submit"
												class="btn btn-sm btn-success pull-right" id="atSubmit">Add</button>
										</div>
									</div>

								</div>
							</div>
						</div>
					</form>
					<div class="panel-body" id="table_show" style="display: none">
						<div class="space15"></div>
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover  cf"
									id="assetRequest-sample">
									<thead class="cf">
										<tr>
											<th>Employee</th>
											<th>Type</th>
											<th>Date</th>
											<th>Purpose</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody id="getAssetRequest">
										<tr>
											<td colspan="6"><img src="../img/ajax-loader.gif"
												id="ajax_loader_getmessages"></td>
										</tr>
									</tbody>
								</table>
							</section>
						</div>
					</div>
					<div class="col-md-12" id="showurl" style="margin-top: 12pt;">
						<img src="../img/ajax-loader.gif" class="ajax_loader"
							style="display: none">
						<section class="panel" id="show_panel_ds" style="display: none"></section>
						<section class="panel" id="show_panel_d" style="display: none">
							<div class="bio-graph-heading project-heading">
								<span class="employee_asset_name"></span><strong
									class="asset_name"></strong>
							</div>
							<div class="panel-body bio-graph-info">
								<!--<h1>New Dashboard BS3 </h1>-->
								<div class="row p-details">
									<div class="col-md-12">
										<div class="col-md-6">

											<div class="bio-row">
												<p>
													<span class="bold">Type </span>: <span class="asset_type"></span>
												</p>
											</div>
											<div class="bio-row">
												<p>
													<span class="bold">Date</span>: <span class="asset_date"
														style="width: 40%"></span>
												</p>
											</div>
											<div class="bio-row">
												<p style="text-align: justify">
													<span class="bold">Purpose</span>:&nbsp;<span
														class="asset_purpose"></span>
												</p>
											</div>
											<div class="bio-row">
												<p style="text-align: justify">
													<span class="bold">Description</span>:&nbsp;<span
														class="asset_desc"></span>
												</p>
											</div>
											<div class="bio-row">
												<p style="text-align: justify">
													<span class="bold">Status</span>:&nbsp; <span
														class="asset_status"></span>
												</p>
											</div>

										</div>


										<div class="col-md-6">

											<header class="panel-heading"
												style="padding-left: 0px; font-weight: 600;" id="asset_det">

											</header>
											<div id="welld" class="show"
												style="margin-bottom: 0px; padding: 0em 0em 0px 12px;"></div>
										</div>


									</div>


								</div>
							</div>
							<div class="col-md-12" id="approvedornot" style="display: none;">
								<section class="panel">
									<header class="panel-heading"
										style="padding-left: 0px; font-weight: 600;"> Status of
										Requested Asset </header>
									<div class="panel-body bio-graph-info">
										<!--<h1>New Dashboard BS3 </h1>-->
										<div class="row p-details" class="">
											<div class="col-md-12">
												<form class="form-horizontal" id="assetEditForm"
													METHOD="post">
													<input type="hidden" name="act"
														value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
													<input type="hidden" name="requestId" id="requestId"
														value="">
													<div id="" class="well show">
														<div class="col-lg-6">
															<div class="form-group">
																<label class="col-lg-5 col-sm-5  control-label">Status</label>
																<div class="col-lg-7">
																	<select class="form-control" id="e_status"
																		name="status">
																		<option value="P">Pending</option>
																		<option value="I">Issue</option>
																		<option value="R">Return</option>
																		<option value="D">Decline</option>
																	</select>
																</div>
															</div>

															<div class="form-group">
																<label class="col-lg-5 col-sm-5 control-label">Asset</label>
																<div class="col-lg-7" id="e_assetId_sh">
																	<select class="form-control" id="e_assetId"
																		name="asset_id">

																	</select>
																</div>
															</div>
														</div>
														<div class="col-lg-6">

															<div class="issue_on" style="display:;">
																<div class="form-group">
																	<label class="col-lg-5 col-sm-5  control-label">Issued
																		On</label>
																	<div class="col-lg-7">
																		<input class="form-control" type="text"
																			name="issued_on" id="e_issued_on" />
																	</div>
																</div>

																<div class="form-group">
																	<label class="col-lg-5 col-sm-5  control-label">Issued
																		Notes</label>
																	<div class="col-lg-7">
																		<textarea class="form-control" name="issued_notes"
																			id="e_issued_notes"></textarea>
																	</div>
																</div>
															</div>
															<div class="return_on" style="display: none;">
																<div class="form-group">
																	<label class="col-lg-5 col-sm-5  control-label">Return
																		On</label>
																	<div class="col-lg-7">
																		<input class="form-control" type="text"
																			name="return_on" id="e_return_on" />
																	</div>
																</div>

																<div class="form-group">
																	<label class="col-lg-5 col-sm-5  control-label">Return
																		Notes</label>
																	<div class="col-lg-7">
																		<textarea class="form-control" name="return_notes"
																			id="e_return_notes"></textarea>
																	</div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<div class="clearfix"></div>
															<div class="col-lg-6">
																<div
																	class="alert alert-block alert-danger fade in error"
																	style="display: none">

																	<span class="error_status"></span>
																</div>
															</div>
															<div class="col-lg-6 col-lg-offset-6">
																<button type="button"
																	class="btn btn-sm btn-danger pull-right closeModel"
																	style="margin-left: 1%">Cancel</button>
																<button type="submit"
																	class="btn btn-sm btn-success pull-right"
																	id="atSubmit1">Update</button>


															</div>

														</div>

													</div>

												</form>
											</div>
										</div>
									</div>
								</section>
							</div>

						</section>
					</div>

				</section>
			</section>

			<!--main content end-->
			<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
		</section>
	</section>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/moment.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script src='../js/jquery.validate.min.js'></script>
	<script type="text/javascript">
           $(document).ready(function () {
        	   $('#atCondition,#atType,#status,#e_atCondition,#e_atType,#e_status,#employee_id').chosen();
               $('#issued_on,#fromDate,#wed,#toDate,#e_wed,#e_issued_on,#e_toDate,#e_fromDate,#e_return_on').datetimepicker({
            	   format: 'DD/MM/YYYY',
               });
               
        	   var requestId='<?php echo isset($_GET['rId'])?$_GET['rId']:0;?>';//jojo798
               if(requestId!=0){
            	$('#table_show').hide();                   
            	viewAsset(requestId);
            	$('#panel-heading').html("<a style='cursor:pointer;' href='assetRequest.php' >Back</a>");  
               }else{
            	   $('#table_show').show(); 
               }
        	  // $('#add-assetTracker-toggle').toggle('hide');
               getAssetRequest();
               
           });        
$('#e_status').on('change',function(e){
	e.preventDefault();
	 var selected_id = $('#e_status :selected').val();
	 if(selected_id == 'I'){
		 $('.issue_on').show();
		 $('.return_on').hide();
	 }else if(selected_id == 'R'){
		 $('.issue_on').hide();
		 $('.return_on').show();
	 }else{
		 $('.issue_on').hide();
		 $('.return_on').hide();
		
	 }	
});
              	           $(document).on('click','#panel_heading,.closeModel',function (e) {
               	                e.preventDefault();
               	             $('#show_panel_d').hide();
               	          	 $('#table_show').show();
               	          $('#panel-heading').html("Asset Requests");   
              	            });
                            //Edit claims 
             $(document).on('click', "#assetRequest-sample a.asset_view", function (e) {
           	                e.preventDefault();
           	              $('.ajax_loader').show();
           	              $('#table_show').hide();
           	              var value = $(this).data('value');
           	           		viewAsset(value);
           	           	$('#panel-heading').html("<a style='cursor:pointer;' id='panel_heading'>Back</a>");  
              	            });

	            function viewAsset(value){
$('.ajax_loader').show();
	            	$.ajax({
       	           		dataType: 'html',
       	           		type: "POST",
       	                   url: "php/assetRequest.handle.php",
       	                   cache: false,
       	                   data: {act: '<?php echo base64_encode($_SESSION['company_id']."!selectAssetRequestById");?>',request_Id:value},	
       	                   success: function (data) {
       	                    data = JSON.parse(data);
       	                    $('.ajax_loader').hide();
       	                 $('#welld').removeClass('show');
       	                 $('#welld').addClass('well hide');
       	                    if(data[0] == "success"){
       	                 	$('#show_panel_d').show(); 
       	                    $('#welld').html(""); 
       	                     
       	                    for(var i=0;i<data[2].length;i++){
       	                    	change(data[2][i].asset_type,$('#e_assetId'));
       	                    	$('#requestId').val(data[2][i].request_id);
       	                    	$('.employee_asset_name').html(data[2][i].employee_name+' ['+data[2][i].employee_id+'] Requested an Asset For ');
								$('.asset_name').html(data[2][i].purpose);
								$('.asset_employee').html(data[2][i].employee_name);
								$('.asset_date').html(data[2][i].date);
								$('.asset_desc').html(data[2][i].description);
								$('.asset_purpose').html(data[2][i].purpose);
								
								$('#welld').append('<h5>Asset Name: <em>'+data[2][0].asset_name+'</em></h5><h5>Issued On : <em id="asset_issue_on">'+data[2][i].issue_on+'</em></h5><h5>Issued Notes : <em id="asset_issue">'+data[2][i].issue_notes+'</em></h5><h5>Return On : <em id="asset_return_on">'+data[2][i].return_on+'</em></h5><h5>Return Notes : <em id="asset_return">'+data[2][i].return_notes+'</em></h5>');
								if(data[2][i].status == 'P'){
									$("#e_status option[value='R']").remove();   
									var status = '<a class="btn  view btn-sm btn-warning">Pending</a>';
									$('#approvedornot').show();
									 $('#asset_det').html('');
		                            }else if(data[2][i].status == 'I'){
		                            var status = '<a class="btn  view btn-sm btn-success">Issued</a>';
		                            $("#e_status option[value='R']").remove();
		                            $('.issue_on').show();
		                            $('#approvedornot').show();
		                       	 $('#welld').removeClass('well hide');
            	                 $('#welld').addClass('show');
            	                 $('#asset_det').html('Asset Details');
		                            }else if(data[2][i].status == 'R'){
		                            $("#e_status option[value='I']").remove();   
		                            var status = '<a class="btn  view btn-sm btn-primary">Returned</a>';
		                            $('#approvedornot').hide();
		                            $('#welld').removeClass('well hide');
	            	                 $('#welld').addClass('show');
	            	                 $('#asset_det').html('Asset Details');
		                            }else if(data[2][i].status == 'D'){
		                            var status = '<a class="btn  view btn-sm btn-danger">Declined</a>';
		                            $('#approvedornot').hide();
		                            $('#welld').removeClass('well hide');
	            	                 $('#welld').addClass('show');
	            	                 $('#asset_det').html('Asset Details');
		                            }   
								$('.asset_status').html(status);
								$('.asset_type').html(data[2][i].asset_type);
								$("#e_status option[value='" + data[2][0].status + "']").remove();
           	                	$('#e_status').trigger("chosen:updated");
           	                	if(data[2][0].asset_ids == null){
           	                	}else{			
           	                		$("#e_assetId_sh").html('<select  class="form-control" name="asset_id" id="asset_id" style="margin-top:8px;"><option  value='+data[2][0].asset_id+' selected>'+data[2][0].asset_name+'</option></select>');
           	                		
           	                	}
           	                	if(data[2][0].issue_on != ''){
           	                		$('#e_issued_on').val(data[2][0].issue_on);
           	                	  
           	                	}else{
           	                	 
               	               
           	                	}
           	                
								if(data[2][0].return_notes != ''){
									$('#e_return_notes').val(data[2][0].return_notes);
           	                	}else{
								
           	                	}
								if(data[2][0].return_on != ''){
           	                		$('#e_return_on').val(data[2][0].return_on);
           	                		
           	                	}else{
           	                		
           	                	}
           	                
								if(data[2][0].issue_notes != ''){
									$('#e_issued_notes').val(data[2][0].issue_notes);
           	                	}else{
								
           	                	}
           	                	
								
               	           }
       	                       	}else if(data[0] == 'error'){
       	                       	$('#show_panel_ds').show(); 
       	                     $('#show_panel_ds').html("<div class='text-center'>No Assets Found</div>");
       	                       	}
       	                   }
       	                  
       	               });
	            }
               
            $('#atSubmit').click(function(e){
            	validate();


          });
          
function validate(){

	  var selected_id = $('#employee_id :selected').val();
	  var type_id = $('#atType :selected').val();
	  var asset_id = $('#assetId').val();
	  if(selected_id == ''){
    	  $('.employee_type').show();
    	  }else{
    		  $('.employee_type').hide();
    	  }if(type_id == ''){
    	  $('.type_asset').show();
    	  }else{
    		  $('.type_asset').hide();
    	  }if(asset_id == 'No'){
    	  $('.asset_type').show();
    	
    	  }else{
    	  $('.asset_type').hide();
    	  
    	  }
	
}
                           //AssetTracker Map Form
                           $('#assetRequestForm').validate({
                        	   rules: {
                        		   fromDate: "required",
                        		   toDate:"required",
                        		   purpose:"required",
                        		   desc:"required",
                        		   issued_on:"required",
                        		   issued_notes:"required",
                        		   assetId:"required",
                				},
                				messages: {
                					fromDate: "<label class='text-danger'>Please Enter your From Date</label>",
                					toDate: "<label class='text-danger'>Please Enter your To Date</label>",
                					purpose:"<label class='text-danger'>Please Enter your Purpose</label>",
                					desc:"<label class='text-danger'>Please Enter From Description</label>",
                					issued_on:"<label class='text-danger'>Please Enter To Issued On</label>",
                					issued_notes:"<label class='text-danger'>Please Enter your Issued Notes</label>",
                					assetId:"<label class='text-danger'>Please Enter your Asset Id</label>",
                					
                				},
                		        
                	submitHandler: function(form) { 
                		 var asset_id = $('#assetId').val();
                		 if(asset_id == 'No'){
                	    	  $('.asset_type').show();
                	    	  }else{
                	    	  $('.asset_type').hide();
                               	 $.ajax({
                	               		type: "POST",
                	               		data: $('#assetRequestForm').serialize(),
                                        url: "php/assetRequest.handle.php",
                                        cache: false,
                                        beforeSend:function(){
                                        	$('#atSubmit').button('loading'); 
                                         },
                                        complete:function(){
                                        	$('#atSubmit').button('reset');
                                         },
                                        success: function (data) {
                                           data1 = JSON.parse(data);
                                           if (data1[0] == "success") {
                                               
                                               $('#assetRequestForm')[0].reset();
                                               $('#add-assetTracker-toggle').toggle('hide');
                                               BootstrapDialog.alert(data1[1]);
                                               var said = "";
                                               var said = $('#asd').val() ;
                                               if(said == 'NO'){
                                            	   $('#asd').val('YES')
                                               }else{
                                            	   $('#assetRequest-sample').dataTable().fnDestroy();
                                               }
                                               getAssetRequest();
                                           	   }
                                        }

                                   });
                	     }
                	  }
                           });


           $('.closeModel').on('click', function (e) {
        	   e.preventDefault();
               $('#add-assetTracker-toggle').hide();
               $('.close').click();
           });
           
           $('#showhide').on('click', function () {
               $('#add-assetTracker-toggle').toggle('show');
           });

           function getAssetRequest()
           {
        	   $.ajax({
           		dataType: 'html',
           		type: "POST",
                   url: "php/assetRequest.handle.php",
                   cache: false,
                   data: {act: '<?php echo base64_encode($_SESSION['company_id']."!selectAssetRequest");?>'},	
                   success: function (data) {
                       data = JSON.parse(data);
                      $('#getAssetRequest').html("");
                       if (data[0] == "success") {
                           var html = "";
                           for(lc=0;lc<data[2].length;lc++){                        	
                        	var download = '';
                            if(data[2][lc].status == 'P'){
							var status = '<a class="btn  view btn-sm btn-warning">Pending</a>';
                            }else if(data[2][lc].status == 'I'){
                            var status = '<a class="btn  view btn-sm btn-success">Issued</a>';
                            var download = '<form action="php/assetRequest.handle.php" method="post" id="generate_pdf_form"><input type="hidden" name="employee_id" id="employee_id" value='+data[2][lc].employee_id+'><input name="request_Id" class="requestId_generate" value='+data[2][lc].request_id+' type="hidden"><input type="hidden" name="act" id="act" value="<?php echo base64_encode($_SESSION["company_id"]."!downloadGeneratePdf");?>"><button type="button" class="btn btn-xs btn-primary generate_pdf" data-toggle="tooltip" title="Download Issue Bill" id="generate_pdf" style="margin-left:10px;"><i class="fa fa-file-pdf-o" ></i></button></form>';
                            }else if(data[2][lc].status == 'R'){
                            var status = '<a class="btn  view btn-sm btn-primary">Returned</a>';
                            }else if(data[2][lc].status == 'D'){
                            var status = '<a class="btn  view btn-sm btn-danger">Declined</a>';
                            }   
                          	html +='<tr><td>'+data[2][lc].employee_name+'</td><td>'+data[2][lc].asset_type+'</td><td>'+data[2][lc].date+'</td><td>'+data[2][lc].purpose+'</td><td class="text-center">'+status+'</td><td ><a style="cursor:pointer;float:left;"title="View" class="asset_view"  data-value='+data[2][lc].request_id+'><button class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button></a>'+download+'</td></tr>'
                         }
                           $('#getAssetRequest').html(html); 
                           EditableTable.init();
                           
                       	}else if (data[0] == "error") {
                       	$('#getAssetRequest').html('<tr><td colspan="7"><input type="hidden" name="asd" id="asd" value="NO"/>No Asset Request Found</td></tr>');
                       		
                           	}
                   }
               });
           } 



           
           var EditableTable = function () {

   		    return {

   		        //main function to initiate the module
   		        init: function () {
   		        
   	   var oTable =  $('#assetRequest-sample').dataTable( {
           	 "aLengthMenu": [
                              [5, 15, 20, -1],
                              [5, 15, 20, "All"] // change per page values here
                          ],
              "iDisplayLength": 5,
              "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
              "bProcessing": true,
              "bSort":false,
                });
   		        	
   	   $('#assetRequest-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                  <input class="form-control medium" id="searchInput" type="text">\
                  <span class="input-group-btn">\
                    <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                  </span>\
                  <span class="input-group-btn">\
                    <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                  </span>\
              </div>');
$('#assetRequest-sample_processing').css('text-align', 'center');
//jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
$('#searchInput').on('keyup', function (e) {
if (e.keyCode == 13) {
oTable.fnFilter($(this).val());
} else if (e.keyCode == 27) {
$(this).parent().parent().find('input').val("");
oTable.fnFilter("");
}
});
$('#searchFilter').on('click', function () {
	
oTable.fnFilter($(this).parent().parent().find('input').val());
});
$('#searchClear').on('click', function () {
$(this).parent().parent().find('input').val("");
oTable.fnFilter("");
});
   		    
   		        }
   	    };

   	} ();

   	$('#assetEditForm').on('submit', function (e) {
        e.preventDefault();
       var status =  $('#e_status :selected').val();
       var check = $('#e_assetId :selected').val();
       
       if(status == 'I'){
        if($('#e_issued_on').val() == '' && $('#e_issued_notes').val() == ''){
        	$('.error').show();
       	 $('.error_status').html('Issue And Issue Notes in Required');
       	 $('.error').fadeOut(5000);
        }else {
            if(check != 'NO'){
            var send = true;
            }else{
                $('.error').show();
            	 $('.error_status').html('No Asset Found');
            	 $('.error').fadeOut(5000);
            }
        }
       }else if(status == 'R') {      
        if($('#e_return_on').val() == '' && $('#e_return_notes').val() == ''){
        	$('.error').show();
        	$('.error_status').html('Return On And Return Notes in Required');
        	 $('.error').fadeOut(5000);
        }else{
        	if(check != 'NO'){
    			var send = true;
                }else{
                	$('.error').show();
               	 $('.error_status').html('No Asset Found');
               	 $('.error').fadeOut(5000);
                }
        }
       }else if(status == 'D'){      
         
       			var send = true;
          
       }
       if(send == true){
           
    
        	 $.ajax({
        		 dataType: 'html',
            		type: "POST",
            		data: $('#assetEditForm').serialize(),
                 url: "php/assetRequest.handle.php",
                 cache: false,
                 beforeSend:function(){
                 	$('#atSubmit1').button('loading'); 
                  },
                 complete:function(){
                 	$('#atSubmit1').button('reset');
                  },
                 success: function (data) {
                    data1 = JSON.parse(data);
                  
                    if (data1[0] == "success") {
                    	$('.close').click();
                        BootstrapDialog.alert(data1[1]);
                        if($('#e_status').val() == 'P'){
							var status = '<a class="btn  view btn-sm btn-warning">Pending</a>';
                            }else if($('#e_status').val() == 'I'){
                            var status = '<a class="btn  view btn-sm btn-success">Issued</a>';
                            }else if($('#e_status').val() == 'R'){
                            var status = '<a class="btn  view btn-sm btn-primary">Returned</a>';
                            }else if($('#e_status').val() == 'D'){
                            var status = '<a class="btn  view btn-sm btn-danger">Declined</a>';
                            $('#approvedornot').hide();
                            }   
                        $('.asset_status').html(status);
                        if($('#e_status').val() == 'I'){
                        $("#assetId option[value='" + $('#e_assetId').val() + "']").prop("disabled", true);
                        }else{
                        $("#assetId option[value='" + $('#e_assetId').val() + "']").prop("disabled", false);
                        }
                        var said = "";
                        var said = $('#asd').val() ;
                        if(said == 'NO'){
                     	   $('#asd').val('YES')
                        }else{
                     	   $('#assetRequest-sample').dataTable().fnDestroy();
                        }
                        getAssetRequest();
                    	   }
                    	
                 }

            });
        }    
    });

    $(document).on('click','.generate_pdf',function(e){
    	document.getElementById("generate_pdf_form").submit();
    });
    $('#atType').on('change',function(e){
var selected_id = $('#atType :selected').val();
change(selected_id,$('#assetId'));

    });

    function change(status,ids){
    	$.ajax({
    		dataType: 'html',
    		type: "POST",
           url: "php/assetRequest.handle.php",
           cache: false,
           data: {act: '<?php echo base64_encode($_SESSION['company_id']."!selectAsset");?>',type:status},	
           success: function (data) {
               data = JSON.parse(data);
               ids.empty(); 
               if (data[0] == "success") {
                 for(var i=0; i<data[2].length; i++){
    				var html = "";
    				html +='<option value='+data[2][i].asset_id+'>'+data[2][i].asset_name+'</option>';

                 } 
                 ids.append(html);
               	}else if (data[0] == "error") {
               		ids.append("<option value='NO'>No Assets Found</option>");
               		
                   	}
           }
       });
    }

                            
      </script>

</body>
</html>
