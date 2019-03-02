<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="BassTechs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Claim</title>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />

<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/datepicker.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
.bio-graph-info {
	background: #fcfcfc;
}

.bio-row {
	width: 100%;
}

.popover-title, .popover-content {
	color: black;
}

.ajax_loader {
	display: block;
	margin: 0px auto;
}

.validate_claim {
	display: none;
}

.bold {
	font-weight: 600;
}

#type_chosen, #classHotel_chosen, #classTravel_chosen, #typeHotel_chosen,
	#typeTravel_chosen, #claimRulechosen_chosen, #claims-sample_table {
	width: 100% !important;
}

.popover-title {
	padding: 3px 10px;
}

.popover-content {
	padding: 3px 10px;
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
     <?php
					
include ("header.php");
					require_once ("../include/lib/claim.class.php");
					$claim = new ClaimRule ();
					$claim->conn = $conn;
					$claimRule = $claim->selectBasedEmplouee ( $_SESSION ['employee_id'] );
					
					?>
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
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs " id="claim_tabs" role="tablist">
							<li id="header_req_active"><a href="#claimRequested"
								id="claim_tab" data-toggle="tab" data-loaded=false
								data-title="claimRequests"> <span id="claim_sd"></span>
							</a></li>

						</ul>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-sm btn-info" id="showhide">
								<i class="fa fa-plus"></i> Request Claim
							</button>
						</div>
					</header>
					<div class="tab-content tasi-tab">
						<div class="tab-pane" id="claimRequested">
							<div class="panel-body claimrules_show" style="display: none;">
            <?php
												
if (empty ( $claimRule )) {
													?>
                                       	<div id="well" class="well">Claims
									not Applicable</div>
                                       	<?php
												} else {
													?>
            <form class="form-horizontal claimsAddForm" encrole="form"
									method="post" id="claimsAddForm">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!Claiminsert");?>" />
									<div class="col-lg-12">

										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Claim
												Rule</label>
											<div class="col-lg-5">
												<select class="form-control" id="claimRulechosen"
													name="claimIds">
													<option>Select Claim Rule</option>
                                       <?php
													foreach ( $claimRule as $row ) {
														echo "<option value='" . $row ['claim_rule_id'] . "' data-id='" . $row ['ruleData'] . "'>" . $row ['claim_name'] . " [ " . $row ['claim_rule_id'] . " ]<br>" . "</option>";
													}
													?>
                                         </select> <label for="claimIds"
													id="claimIdsd"></label>
											</div>
										</div>
										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label"></label>
											<div class="col-lg-5">
												<div id="well" class="well hide"
													style="margin-bottom: 0px; padding: 0em 0em 0px 12px;"></div>
											</div>
										</div>


										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Title
												Of Claim</label>
											<div class="col-lg-5">
												<input class="form-control validate_claim_check" type="text"
													id="cName" name="cName" maxlength="50" />
											</div>
										</div>

										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Description</label>
											<div class="col-lg-5">
												<textarea class="form-control validate_claim_check" rows="2"
													name="description" id="description" maxlength="100"></textarea>
											</div>
										</div>


										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Date
												Range</label>
											<div class="col-lg-5">
												<div class="input-group input-large">
													<input class="form-control validate_claim_check"
														name="amtFrom" id="dateFrom" type="text"
														placeholder="dd/mm/yyyy"> <span class="input-group-addon">-</span>
													<input class="form-control dpd2 validate_claim_check"
														name="amtTo" id="dateTo" type="text"
														placeholder="dd/mm/yyyy">
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Amount</label>
											<div class="col-lg-5">
												<div class="input-group input-large">
													<span class="input-group-addon">&#x20B9;</span> <input
														class="form-control validate_claim_check" name="amt"
														type="text" placeholder="Amount" maxlength="20">
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Referance
												No</label>
											<div class="col-lg-5">
												<input class="form-control validate_claim_check"
													name="reference_no" id="reference_no" maxlength="30" />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-3  col-sm-3 control-label">Bill
												Attachment</label>
											<div class="col-lg-7">
												<div class="row fileupload-buttonbar">
													<div class="col-lg-6">
														<span
															class="btn btn-success btn-sm  fileinput-button attach">
															<i class="glyphicon glyphicon-plus"></i> <span>Add files</span>
															<input name="attachment" id="attachment"
															class="imagechange validate_claim_check" type="file">
														</span> <span class="show_image_name"></span>
													</div>

												</div>
											</div>

										</div>
									</div>

									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-5" align="right">
											<button type="submit" class="btn btn-sm btn-success"
												id="claimsSubmit">Submit</button>
											<button type="button"
												class="btn btn-sm  btn-danger closeModel">Cancel</button>
										</div>
									</div>

								</form>
                          <?php
												
}
												?>
                          
                          </div>

							<div class="panel-body" id="claim_form">
								<div class="space15"></div>
								<div class="adv-table editable-table">
									<section id="flip-scroll">
										<table class="table table-striped table-hover  cf"
											id="claims-sample_table">
											<thead class="cf">
												<tr>
													<th>Name</th>
													<th>Description</th>
													<th>Date Range</th>
													<th>Amount</th>
													<th>Attachment</th>
													<th>Status</th>
													<th>View</th>
												</tr>
											</thead>
											<tbody class="getClaimrule">
												<tr>
													<td colspan="6"><img src="../img/ajax-loader.gif"
														id="ajax_loader_getmessages"></td>
												</tr>
											</tbody>
										</table>
									</section>
								</div>
							</div>

							<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
								aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">

										<div class="modal-header">
											<button aria-hidden="true" data-dismiss="modal" class="close"
												type="button">&times;</button>
											<h4 class="modal-title">View Claim Attachment</h4>
										</div>
										<div class="modal-body">

											<div class="fileupload-new thumbnail">
												<img id="preview_image" style="width: 100%; height: 100%;"
													alt="Employee Image">
											</div>

										</div>
										<div class="modal-footer">
											<button data-dismiss="modal" class="btn btn-default"
												type="button">Close</button>

										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12" id="showurl"
								style="display: none; margin-top: 30pt;">
								<img src="../img/ajax-loader.gif" class="ajax_loader"
									style="display: none">
								<section class="panel" id="show_panel_p" style="display: none">
								</section>
								<section class="panel" id="show_panel_d" style="display: none">
									<div class="bio-graph-heading project-heading">
										<span class="employee_claim_name"></span><strong
											class="claim_name"></strong>
									</div>
									<div class="panel-body bio-graph-info">
										<!--<h1>New Dashboard BS3 </h1>-->
										<div class="row p-details">
											<div class="col-md-12">
												<div class="col-md-6">
													<div class="bio-row">
														<p>
															<span class="bold">Amount</span>: <span
																class="claim_amount"></span>
														</p>
													</div>

													<div class="bio-row">
														<p>
															<span class="bold">Status </span>: <span
																class="claim_status"></span>
														</p>
													</div>
													<div class="bio-row">
														<p>
															<span class="bold">Date</span>: <span class="claim_date"
																style="width: 40%"></span>
														</p>
													</div>

													<div class="bio-row">
														<p style="text-align: justify">
															<span class="bold">Description</span>:&nbsp;<span
																class="claim_desc" style="width: 76%"></span>
														</p>
													</div>
													<div class="bio-row">
														<p style="text-align: justify">
															<span class="bold">Reference No</span>:&nbsp;<span
																class="claim_reference"></span>
														</p>
													</div>
													<div class="bio-row">
														<p style="text-align: justify">
															<span class="bold">Bill</span>:&nbsp; <span
																class="attachment_claim" style="width: 75%"></span>
														</p>
													</div>
													<div class="bio-row">
														<p style="text-align: justify">
															<span class="bold">Admin Remarks</span>:&nbsp; <span
																class="remarks_claim" style="width: 75%"></span>
													
													</div>
													<div class="bio-row">
														<p style="text-align: justify">
															<span class="bold" id="approved_decline">Approved On</span>&nbsp;
															<span class="approvedon_claim" style="width: 75%"></span>
													
													</div>
												</div>


												<div class="col-md-6">

													<header class="panel-heading"
														style="padding-left: 0px; font-weight: 600;"> Claim Rule </header>
													<div id="welld" class="show"
														style="margin-bottom: 0px; padding: 0em 0em 0px 12px;"></div>
												</div>


											</div>


										</div>
									</div>

								</section>
							</div>
						</div>
					</div>

				</section>
			</section>
			<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
				tabindex="-1" id="billurl_request" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header">
							<button aria-hidden="true" data-dismiss="modal" class="close"
								type="button">&times;</button>
							<h4 class="modal-title">View Claim Attachment</h4>
						</div>
						<div class="modal-body">

							<div class="fileupload-new thumbnail">
								<img id="preview_imaged" style="width: 100%; height: 100%;"
									alt="Employee Image">
							</div>

						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default"
								type="button">Close</button>

						</div>
					</div>
				</div>
			</div>
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
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>

	<!--common script for all pages-->
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<script type="text/javascript">
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
      	      $('#claim_tabs a[href="#' + tab + '"]').tab('show');
      	      
      	  	var sum = 0;
      	  }else{
      		  $('#claim_tabs a[href="#claimRequested"]').tab('show');
      		  
      		  } 

      	  // Change hash for page-reload
      	  $('#claim_tabs a').on('shown.bs.tab', function (e) {
      	      window.location.hash = e.target.hash;
      	  })
      	});


        $('#claim_tabs').on('shown.bs.tab', function (e) {
      	   // newly activated tab
      	   $('#type,#classHotel,#classTravel,#typeHotel,#typeTravel,#claimRulechosen').chosen();
      	 $('#dateTo,#dateFrom').datetimepicker({
               format: 'DD/MM/YYYY'        
    	   }); 
      	 window.scrollTo(0, 0);
      	  if($(e.target).data('loaded') === false){
  			if($(e.target).data('title') == 'claimRequests'){
  				 var claimId = getParameterByName("cId");
  		      if(claimId){
  	  		      $('#claim_form').hide();
  		          $('#showurl').show();
  		          $('#claim_sd').html('Back');
  		        	  viewDetailClaim(claimId);    
  		        	bflag = 0;
  		      }else{
  		    		bflag = 1;
  		          $('#claim_sd').html('Claim');
  		        $('#claim_form').show();
  		        
  		      }
  		    getClaim();
  			}
  			//make the tab loaded true to prevent re-loading while clicking.
        		$(e.target).data('loaded',true);
      		}
      	});
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
          }
        $('#claim_tab').click(function(e){
      	  	if(bflag == 0){
      		 $('#showurl').hide();
    		  $('#claim_form').show();
    		  window.location.href = 'claims.php#claimRequested';
    		  $('#claim_sd').html("Claims");
      	  	}else{
      	  	  $('#showurl').hide();
    		  $('#claim_form').show();
    		
      	  	}bflag = 1;
      	});
        
           $('#claimRulechosen').on('change', function (e) {
            	 e.preventDefault();
            	 $('#claimIdsd').html('');
                   var selected_id = $('#claimRulechosen :selected').data('id');
                  
                   if(selected_id){
                	   $('#claimIdsd').html('');
                	   
               	  }else{
               		 $('#claimIdsd').html('');
              		  $('#claimIdsd').fadeIn(300);
              		  $('#claimIdsd').html('Select Claim Rule');
              		$('#well').removeClass('show');
             	   $('#well').addClass('hide');
               	  }
                   str=selected_id.split('_');
                    
                   if(str[0]=='travel'){
                        type="Travel";
                    	if(str[1]=='train'){
                    		subtype0= 'Train';
						}else if(str[1]=='bus'){
							subtype0= 'Bus';
						}else{
							subtype0= 'Flight';
							}
						
                  if(str[2]=='firstac'){
							subtype= 'First AC';
						}else if(str[2]=='secondac'){
							subtype= 'Second AC'
						}else if(str[2]=='thirdac'){
							subtype= 'Third AC'
						}else if(str[2]=='sl'){
							subtype= 'SL'
						}else if(str[2]=='acsl'){
							subtype= 'AC SL'
						}else if(str[2]=='acssl'){
							subtype= 'AC Semi SL'
						}else if(str[2]=='ssl'){
							subtype= 'Semi SL'
						}else if(str[2]=='ec'){
							subtype= 'EC'
						}else if(str[2]=='bc'){
							subtype= 'BC'
						}
						
				  }else if(str[0]=='hotel'){
					  type="Hotel";
					  
					  if(str[1]=='ac'){
                  		subtype0= 'AC';
						}else if(str[1]=='nac'){
							subtype0= 'Non-AC';
						}
						
            	    	 if(str[2]=='7star'){
            	    		 subtype= 'Sevan Star';
            	    		}else if(str[2]=='5star'){
            	    			subtype= 'Five Star';
            	            }else if(str[2]=='4star'){
            	            	subtype= 'Four Star';
            	            }else if(str[2]=='3star'){
            	            	subtype= 'Three Star';
                   	        }else if(str[2]=='NA'){
                   	        	subtype= 'Others';
                   	        }else{
                   	        	subtype= '-';
                       	    }}
            	     else if(str[0]=='others'){
            	    	 type="Others";
                         }
                   $('#well').removeClass('hide show');
                   $('#well').addClass('show');
                   
                    $('#well').empty();
                    $('#well').append('<h5>Type : <em>'+type+'</em></h5><h5>Sub Type : <em>'+subtype0+'</em></h5><h5>Class : <em>'+subtype+'</em></h5><h5>Amount : <em> <i class="fa fa-rupee"></i> '+str[3]+' - <i class="fa fa-rupee"></i> '+str[4]+'</em></h5>')
               	  
           });
           $('.imagechange').on('change',function(e){
        	   var text = $(this).val();
        	   var thefile = text.substring(text.lastIndexOf("\\") + 1, text.length);
        	   $('.show_image_name').html('');
        	   $('.show_image_name').html(thefile);
           });
$('#claimsSubmit').click(function(){
	var selected_id = $('#claimRulechosen :selected').data('id');
    
    if(!selected_id){
	 $('#claimIdsd').html('');
		  $('#claimIdsd').fadeIn(300);
		  $('#claimIdsd').html('Select Claim Rule');
		$('#well').removeClass('show');
   $('#well').addClass('hide');
}if($('#attachment').val()==''){
$('.show_image_name').html('');
$('.show_image_name').html('Please Add Attachment');
}
});
          $('.claimsAddForm').validate({
        	  
        	   rules: {
        		           claimIds: "required",
        				   cName:"required",
        				   description:"required",
        				   amtFrom:"required",
        				   amtTo:"required",
        				   amt:{
            				   required:true,
            				   number: true
        				   },
        				   reference_no:"required",
        				   
        				},
        				messages: {
        					claimIds: "Please Select your Claim Rule",
        					cName: "Please Enter your Claim Name",
        					description:"Please Enter your Claim Description",
        					amtFrom:"Please Enter From Date",
        					amtTo:"Please Enter To Date",
        					amt:{
            					required:"Please Enter your Amount",
            					number:"Enter only No"
        					},
        					reference_no:"Please Enter your Reference No",
        					
        				},
        		        
        	submitHandler: function(form) { 
        		
        	           $.ajax({
                    	 processData: false,
                         contentType: false,
	                     type: "POST",
	                     url: "php/claim.handle.php",
	                     cache: false,
	                     data:new FormData(form),
	                     beforeSend:function(){
	                      	$('#claimsSubmit').button('loading'); 
	                       },
	                       complete:function(){
	                     	$('#claimsSubmit').button('reset');
	                       },	
	            success: function (data) {
	                data = JSON.parse(data);
					if(data[0] == 'success'){
						$('.claimsAddForm')[0].reset();
						$('.close').click();
                   	  BootstrapDialog.alert(data[1]);
                   	$('.claimrules_show').fadeToggle("fast");
                    var said = "";
                    var said = $('#asd').val() ;
                    if(said == 'NO'){
                 	   $('#asd').val('YES')
                    }else{
                    	$('#claims-sample_table').dataTable().fnDestroy();
                    }          		
                   
                   	   getClaim();
					}else if(data[0] == 'error'){
						$('.close').click();
	                   	  BootstrapDialog.alert(data[1]);
					}

			        }

				});
               } 
       	
           });
     

       function getClaim()
       {
           $('#ajax_loader_getmessages').show();
    	   $.ajax({
       		dataType: 'html',
       		type: "POST",
               url: "php/claim.handle.php",
               cache: false,
               data: {act: '<?php echo base64_encode($_SESSION['company_id']."!viewClaimsByEmployee");?>'},	
               success: function (data) {
                   data = JSON.parse(data);
                   $('#ajax_loader_getmessages').hide();
                  
                   if (data[0] == "success") {
                	   $('.show_image_name').html('');
                       $('.getClaimrule').empty();
                       for(lc=0;lc<data[2].length;lc++){
                      var html = "";
                     if(data[2][lc].amount_approved!='0.00'){
                         var amount = data[2][lc].amount_approved;
                     }else{
                         var amount = data[2][lc].amount;
                     }if(data[2][lc].status == 'P'){
						var status = '<a class="btn  view btn-sm btn-warning">Pending</a>';
						var color = '#fff';
                     }else if(data[2][lc].status == 'A'){
                    	 var status = '<a class="btn  view btn-sm btn-success" data-toggle="popover" data-original-title="Admin Remarks" data-content="'+data[2][lc].admin_remarks+'" data-placement="left" data-trigger="hover">Approved</a>';
                    	 var color = '#fff';
                     }else if(data[2][lc].status == 'D'){
                    	 var status = '<a class="btn  view btn-sm btn-danger" data-toggle="popover" data-original-title="Admin Remarks" data-content="'+data[2][lc].admin_remarks+'" data-placement="left" data-trigger="hover">Declined</button>';
                    	 var color = '#fff';
                     }else if (data[2][lc].status == 'R'){
                    	 var status = '<a class="btn  view btn-sm btn-primary" data-toggle="popover" data-original-title="Admin Remarks" data-content="'+data[2][lc].admin_remarks+'" data-placement="left" data-trigger="hover">Processed</button>';
                    	 var color = '#fff';
                     }  
                     html +='<tr><td>'+data[2][lc].purpose+'</td><td>'+data[2][lc].description+'</td><td>'+data[2][lc].date+'</td><td>'+amount+'</td><td class="text-center"><a class="btn  view btn-sm btn-primary" id='+data[2][lc].claim_id+' data-url='+data[2][lc].bill_url+' data-toggle="modal" href="#myModal"><i class="fa fa-paperclip"></i></a></td><td style="color:'+color+'" class="text-center">'+status+'</td><td class="text-center"><a class="btn  view btn-sm btn-primary" id="view_data" data-view='+data[2][lc].claim_id+'><i class="fa fa-eye"></i> View</a></td></tr>';
					 $('.getClaimrule').append(html); 
					
						 $('[data-toggle="popover"]').popover();
				    	
					var htmlsd = "";
					$('#preview_image').attr('src',' ');
					$(document).on('click','#'+data[2][lc].claim_id,function(e){
						e.preventDefault();
					var dataurl = $(this).data('url');
					$('#preview_image').attr('src',dataurl);
					});
					
                    }
                       
                       EditableTable.init();
                       
                   	}else if (data[0] == "error") {
                   		$('.getClaimrule').html('<tr><td colspan="7"><input type="hidden" name="asd" id="asd" value="NO"/>No Claims Found</td></tr>');
                   		
                       	}
               }
           });
       } 
      
    	   var EditableTable = function () {

    		    return {

    		        //main function to initiate the module
    		        init: function () {
    		        
    	   var oTable =  $('#claims-sample_table').dataTable( {
            	 "aLengthMenu": [
                               [5, 15, 20, -1],
                               [5, 15, 20, "All"] // change per page values here
                           ],
               "iDisplayLength": 5,
               "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
               "bProcessing": true,
               
                 });
    		        	
    	   $('#claims-sample_table_wrapper .dataTables_filter').html('<div class="input-group">\
                   <input class="form-control medium" id="searchInput" type="text">\
                   <span class="input-group-btn">\
                     <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                   </span>\
                   <span class="input-group-btn">\
                     <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                   </span>\
               </div>');
$('#claims-sample_table_processing').css('text-align', 'center');
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

$('#showhide,.closeModel').click(function(e){
	e.preventDefault();
	$('.claimrules_show').toggle("show");
	
});
function viewDetailClaim(claimId)
{
	$('.ajax_loader').show();
    $.ajax({
   	  type: "POST",
        url: "php/claim.handle.php",
        cache: false,
        data:{act: '<?php echo base64_encode($_SESSION['company_id']."!viewClamisbyId");?>',claimIds:claimId},
    success: function (data) {
   data = JSON.parse(data);
   $('.ajax_loader').hide();
  if(data[0] == 'success'){
	  
  	$('#show_panel_d').show();
	$('.attachment_claim').html("");
	$('#welld').html("");
	for(var j=0;j<1;j++){
   		if(data[2][j].amount_approved =='0.00' && data[2][j].status == 'D'){
              var amount = '<i class="fa fa-rupee"></i> '+data[2][j].amount_approved;
          }else{
              var amount = '<i class="fa fa-rupee"></i> '+data[2][j].amount;
          }if(data[2][j].status == 'P'){
				var status = '<a class="btn  view btn-sm btn-warning">Pending</a>';
				$('#approvedornot').show();
				$('#approved_decline').html('');
				 $('.approvedon_claim').html('');
          }else if(data[2][j].status == 'A'){
         	 var status = '<a class="btn  view btn-sm btn-success" >Approved</a>';
         	$('.approvedon_claim').html(data[2][j].approved_on);
			 $('#approved_decline').html('Approved On');
         	 $('#approvedornot').hide();
          }else if(data[2][j].status == 'D'){
         	 var status = '<a class="btn  view btn-sm btn-danger" >Declined</a>';
         	 $('#approvedornot').hide();
         	$('.approvedon_claim').html(data[2][j].approved_on);
			 $('#approved_decline').html('Declined On');
          } 
          $('#claimId').attr('value',data[2][j].claim_id);
          	 $('.claim_name').html(data[2][j].purpose);
			 $('.claim_desc').html(data[2][j].description);
			 $('.claim_amount').html(amount);
			 $('.claim_reference').html(data[2][j].reference_no);
			 $('.claim_status').html(status);
			 $('.claim_date').html(data[2][j].date);
			 $('.remarks_claim').html(data[2][j].admin_remarks);
			 
			 var phtml = "";
			 if(data[2][j].bill_url){
	            phtml +='<a class="btn view btn-sm btn-primary" href="#billurl_request"  id='+data[2][j].claim_id+' data-url='+data[2][j].bill_url+' data-toggle="modal" target="_blank">View Bill</a>';
	       	} 
		    $('.attachment_claim').append(phtml);
		    $('#preview_imaged').attr('src',' ');
			if(data[2][j].claim_id!=''){
				$(document).on('click','#'+data[2][j].claim_id,function(e){
					e.preventDefault();
				var dataurl = $(this).data('url');
				$('#preview_imaged').attr('src',dataurl);
				});
			}
	       	$('#welld').append('<h5>Type : <em>'+data[2][j].category_type+'</em></h5><h5>Sub Type : <em>'+data[2][j].sub_type+'</em></h5><h5>Class : <em>'+data[2][j].class+'</em></h5><h5>Amount : <em> <i class="fa fa-rupee"></i> '+data[2][j].amount_from+' - <i class="fa fa-rupee"></i> '+data[2][j].amount_to+'</em></h5>');
		 }
				 
		}else if(data[0] == 'error'){
			$('#show_panel_p').show();
			$('#show_panel_p').html('<div class="text-center"><h5>No Claims Found</h5></div>');
		}

      }

	});
}
$(document).on('click','#view_data',function (e){
	e.preventDefault();
	var data = $(this).data('view');
	$('#claim_form').hide();
	$('#showurl').show();
    $('#claim_sd').html('<span id="claim_back" style="cursor:pointer">Back</span>');
	viewDetailClaim(data);
});
$(document).on('click','#claim_back',function (e){
	$('#showurl').hide();
	$('#claim_form').show();
	$('#claim_sd').html('Claims');
});


</script>

</body>
</html>
