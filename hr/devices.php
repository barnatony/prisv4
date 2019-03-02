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

<title>Devices</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />

<style>
#branch_loc_chosen{
	width: 100% !important;
}
 .switch {
  position: relative;
  display: inline-block;
  width: 32px;
  height: 21px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 13px;
  width: 11px;
  left: 4px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #8ce196;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
				<!-- Details of perquisite starts here -->
      					<div class="panel">
      							<header class="panel-heading"  >Devices View
      									<div class="btn-group pull-right">
											<button id="add_deviceData" type="button" class="btn btn-sm btn-info" style="margin-top: -5px;">

											<i class="fa fa-plus"></i> Add
											</button>
										</div>
						    	</header>
								<div class="panel-body" id="hide_form">
									<form class="form-horizontal " id="device-data" method="post">
													<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>"  />
									
												<div class="col-lg-12">
															<div class="form-group">
																<label class="col-lg-3 control-label">Device IP</label>
																	<div class=" col-lg-4">
																		<input type="text" class="form-control"  id="device_ip"
																			name="deviceIp" required/>
										 							</div>
															</div>
															<div class="form-group">
																<label class="col-lg-3 control-label">Device MAC</label>
																	<div class="col-lg-4">
																			<input type="text" class="form-control"  id="device_mac"
																			name="deviceMac" required/>
																	</div>
															</div>
															<div class="form-group">
																<label class="col-lg-3 control-label">Device Name</label>
																	<div class="col-lg-4">
																			<input type="text" class="form-control"  id="device_name"
																			name="deviceName" required />
																	</div>
															</div>
															<div class="form-group">
																<label class="col-lg-3 control-label">User Name</label>
																	<div class="col-lg-4">
																			<input type="text" class="form-control"  id="user_name"
																			name="userName" required />
																	</div>
															</div>
															<div class="form-group">
																<label class="col-lg-3 control-label">Password</label>
																	<div class="col-lg-4">
																			<input type="text" class="form-control"  id="password"
																			name="password" required />
																	</div>
															</div>
															<div class="form-group">
																<label class="col-lg-3 control-label">Device Location</label>
																	<div class="col-lg-4">
																			<select class="form-control" id="device_loc"
																				name="device_loc" required>
																				<option value="">Select Location</option>
                                                   <?php
																						$stmt = mysqli_prepare ( $conn, "SELECT branch_id,branch_name FROM company_branch" );
																						$result = mysqli_stmt_execute ( $stmt );
																						mysqli_stmt_bind_result ( $stmt, $branch_id,$branch_name );
																						while ( mysqli_stmt_fetch ( $stmt ) ) {
																							echo "<option value='" . $branch_id. "'>" . $branch_name . "</option>";
																						}
																						mysqli_stmt_close($stmt);
																						?> 
                                              								</select>
																	</div>
															</div>
																		
															<div class="form-group">
																<div class=" col-lg-offset-3 col-lg-4">
																			<input type="checkbox" aria-label="..." id="enable_enroll" class="enable_enroll" name="enableEnroll" />&nbsp;&nbsp;
																			<b>Enable Enrolment on this Device</b>
																			 <!--help text starts here-->
																		    <label class=" help-block text-danger text" style="margin-left: 20px;">
														  						 You can enrol user finger prints on this device for this branch , This will disable previous enrol device if any	
														  					</label>	
																				 <!--help text end-->
																</div>
															</div>
															
															<div class="form-group">
																	<div class="col-lg-offset-2 col-lg-5" align="right" style="margin-top:10px;">
																			<button type="submit" class="btn btn-sm btn-success submit"  id="dev_data_submit">Submit</button>
																			<button type="button" class="btn btn-sm  btn-danger" id="cancel">Cancel</button>															
																	</div>
															</div>
												</div>		
									</form>
								  </div>
									<div id="deviceTable" class="panel-body"></div>
									<div id="loader" style="width:97%;height:100%"></div>
									<!-- Modal tcp listener register starts here-->
								<div class="modal fade listener_info"  tabindex="-1"  id="listener_info" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
								  <div class="modal-dialog" role="document" style="width: 372px;">
								    <div class="modal-content">
								      <div class="modal-header">
								        <h5 class="modal-title">Register to a Service</h5>
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-21px;">
								          <span aria-hidden="true">&times;</span>
								        </button>
								      </div>
								      <div class="modal-body" style="padding-bottom:50px;">
								      	<form class="reg-service form-horizontal" id="reg-service">
								      	<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!insertListener");?>"  />
								      	<!--   <div class="form-group">
											<label class="col-sm-12 label label-default">Current Listener &amp; Port :<span class="ip"></span>&nbsp;&amp;&nbsp;<span class="port"></span></label>
										</div>-->
										<input type="hidden" class="device_ip" id="device_ip" name="deviceIp"/>
								      	<div class="form-group row">
													<label class="col-sm-4 control-label">Listener IP</label>
														<div class="col-sm-8">
															<input type="text" class="form-control"  id="listener_ip"
																			name="listenerIp"  required />
														</div>
										</div>
										<div class="form-group row">
													<label class="col-sm-4 control-label">Listener Port</label>
															<div class="col-sm-8">
																<input type="text" class="form-control"  id="listener_port"
																	name="listenerPort" required />
															</div>
										</div>
										<input type="submit" class="btn btn-primary reg pull-right" value="Register"/>
								      	</form>
								      </div>
								      
								    </div>
								  </div>
								</div>
								<!-- Modal tcp listener register end-->
					</div>
      						<!-- Details of perquisite end -->

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
	<!--script for this page only-->
	<script src="../js/chosen.jquery.min.js"></script>
	
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script type="text/javascript" src="../js/bootstrap-inputmask.min.js"></script>
	<script type="text/javascript">
	 $(document).ready(function () {
		  $('#device_loc').chosen();
		 
		  $.ajax({
   		   dataType: 'html',
               type: "POST",
               url: "php/devices.handle.php",
               cache: false,
               data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getDevicedata");?>'},
               success: function (data) {
                    jsonobject= JSON.parse(data);
                    $('#device-data').hide();
                    $('#deviceTable').empty();
                  
                     var html = '<section id="flip-scroll"> <table class="table tab-content tasi-tab table-striped table-hover table-bordered device dataTable " id="device-sample" style="width:100%;overflow:hidden;"> <thead class="device"><tr> <th>Branch</th><th>Name</th><th>IP</th><th>Enrolment</th><th>Action</th></tr></thead><tbody>';               	                	  
                     for (var i = 0, len = jsonobject[2].length; i < len; i++) {
                 		   html += '<tr class="device_data">';
                 		   $.each(jsonobject[2][i], function (k, v) {
	                  		   if(k == 'branch_name'){
	                  			 html +='<td>'+v+'</td>';
	                  		   }
	                  		   if(k == 'device_name'){
	                  			 html +='<td>'+v+'</td>';
	                  		   }
                 			 	if(k == 'IP'){
                 			 		html +='<td>'+v+'</td>';
                 			 	}
                 			 	if(k == 'enrollment'){
                 			 			if(v =='1'){
                 			 				 html += '<td><span class="label label-success">Yes</span></td>';
                 	                       		}else{
                 	                       		html += '<td><label class="switch" data-id="'+jsonobject[2][i].device_id+'" title="Default Enrolment Device"><input type="checkbox"><span class="slider round"></span></label></td>';
                     	                       		}
                 			 		} 		
                 		 }); 
                 		  
                   		 
                           
                           html +='<td><button class="btn btn-default btn-sm device_info" id="d_info" data-ip="'+jsonobject[2][i].IP+'" data-devid="'+jsonobject[2][i].device_id+'" data-toggle="popover"  style="margin-right:5px;"><i class="fa fa-info-circle"></i></button> <button  class="btn btn-default btn-sm tcp_info" data-devid="'+jsonobject[2][i].device_id+'" data-target="#listener_info" data-toggle="modal"><i class="fa fa-gear"></i></button> <button  class="btn btn-default btn-sm device_offline" title="Device Sync" data-ip="'+jsonobject[2][i].IP+'" data-deviceiid="'+jsonobject[2][i].device_id+'"><i class="fa fa-refresh"></i></button></td>';
                          html += '</tr>';
                    	 
                     }
                     html += '</tbody> </table></section>';
                      //append table 
                       $('#deviceTable').html(html);
                       setTimeout(function(){  
                           var oTable = $('#device-sample').dataTable( {
                          	 "aLengthMenu": [
                                               [5 ,10 , 15,  -1],
                                               [5 ,10 , 15, "All"] // change per page values here
                                           ],
                               "iDisplayLength": 5,
                               "aoColumnDefs": [
                                                 {"bSearchable": false, "bVisible": false, "aTargets": []}      
                                               ] ,
                                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'p>>",
                                
                                 } );

                           //for search bar        
          				 $('#device-sample_wrapper .dataTables_filter').html('<div class="input-group">\
          	                         <input class="form-control medium" id="branch_searchInput" type="text">\
          	                         <span class="input-group-btn">\
          	                           <button class="btn btn-white" id="branch_searchFilter" type="button">Search</button>\
          	                         </span>\
          	                         <span class="input-group-btn">\
          	                           <button class="btn btn-white" id="branch_searchClear" type="button">Clear</button>\
          	                         </span>\
          	                     </div>');
          	            $('#device-sample_processing').css('text-align', 'center');
          	           
          	            jQuery('#device-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
          	            $('#branch_searchInput').on('keyup', function (e) {
          	            if (e.keyCode == 13) {
          	            oTable.fnFilter($(this).val());
          	            } else if (e.keyCode == 27) {
          	            $(this).parent().parent().find('input').val("");
          	            oTable.fnFilter("");
          	            }
          	            });


          	          $(document).on('click', "#branch_searchFilter", function () {
          	            oTable.fnFilter($(this).parent().parent().find('input').val());
          	            });
          	            $(document).on('click', "#branch_searchClear", function () {
          	            $(this).parent().parent().find('input').val("");
          	            oTable.fnFilter("");
          	            });  //end search bar
                          
                           },100); 
                           $('#deviceTable').removeClass('loader');
                          
               }
   	    });
		 
	 });


    $(document).on('change','.switch',function(e){
         e.preventDefault();
         var deviceId = $(this).data('id');
         var target = $(this);
         $.ajax({
 		     dataType: 'html',
             type: "POST",
             url: "php/devices.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!changeEnrolment");?>','deviceId':deviceId},
             beforeSend: function () {
            	 target.button('loading'); 
             },
             complete: function () {
            	 target.button('reset'); 
              },
             success: function (data) {
                  jsonobject = JSON.parse(data);
                  $('#loader').loading(true);
                  location.reload();
                  $('#loader').loading(false);
             	}
     		});

	      });

	 $(document).on('click', ".device_info", function (e) {
         e.preventDefault();
         //for getting device statistics in popover
         var IP =$(this).parent().parent().find('.device_info').data('ip');
		 var target=$(this);
         tcpInfo(IP,target);
     });


     $(document).on('click', ".device_offline", function (e) {
         e.preventDefault();
        // console.log($(this).parent().parent().find('.device_offline').data('deviceiid'));
          var deviceIp = $(this).parent().parent().find('.device_offline').data('ip');
          var target = $(this);
          $.ajax({
               dataType: 'html',
              type: "POST",
              url: "php/devices.handle.php",
              cache: false,
              data: { act: '<?php echo base64_encode($_SESSION['company_id']."!deviceSync");?>','deviceIp':deviceIp},
              beforeSend: function () {
                  target.button('loading');
              },
              complete: function () {
                  target.button('reset');
               },
              success: function (data) {
                   jsonobject = JSON.parse(data);
				   console.log(jsonobject);
                   if(jsonobject[0]=='error' && jsonobject[2]=='Synced')
                	   BootstrapDialog.alert('Device is Already Synced Uptodate ')
                   else if(jsonobject[0]=='error')	   
                	  BootstrapDialog.alert(jsonobject[1])
                   else if(jsonobject[0]!='error')
					   BootstrapDialog.alert(jsonobject[1])
                   
                   $('#loader').loading(true);
                   //location.reload();
                   $('#loader').loading(false);
                  }
              });
 
           });
	 
	 $(document).on('click', "#add_deviceData", function (e) {
         e.preventDefault();
         jQuery('#device-data').toggle('show');
     });

	  
     $(document).on('click', "#cancel", function (e) {
         e.preventDefault();
         jQuery('#device-data').toggle('hide');
     });

	 

     $('#device-data').on('submit',function (e){
    	  e.preventDefault();
    	 // $('.listener_info')[0].reset();
    	  $.ajax({
    		   dataType: 'html',
                type: "POST",
                url: "php/devices.handle.php",
                cache: false,
                data: $('#device-data').serialize(),
                beforeSend: function () {
                	$('.submit').button('loading'); 
                },
                complete: function () {
                	$('.submit').button('reset'); 
                 },
                success: function (data) {
               	  data1 = JSON.parse(data);
              
               	  if (data1[0] == "success") {
               		 $('#device-data').toggle('hide');
               		  $('#device-data')[0].reset();
               	 		BootstrapDialog.alert(data1[1]);
               	   }else
               		   if (data1[0] == "error") {
                             		 alert(data1[1]);
                          }
                }
	       });
                  
         });
     
     $('#listener_info').on('show.bs.modal', function (e) {
    	  $(e.target).find("input[name=deviceId]").val($(e.relatedTarget).data('devid'));
    	  
    	})
    	
     $('#listener_info').on('hidden.bs.modal', function () {
    	 $('.reg-service')[0].reset();
    	});

     $('body').on('click', function (e) {
         $('[data-toggle=popover]').each(function () {
             // hide any open popovers when the anywhere else in the body is clicked
             if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                 $(this).popover('hide');
             }
         });
     });
 	
 	$(document).on('click','.tcp_info',function(e){
 		 var target=$(this);
 		 var IP =$(this).parent().parent().find('.device_info').data('ip');
 		 $('.device_ip').val(IP);
 		 tcpInfo(IP,target);
 	 	});
	 	
	//for getting tcplister informations
 	var tcpInfo= (function(IP,target) {
		 console.log(IP);
 		 $.ajax({
	   		   dataType: 'html',
	               type: "POST",
	               url: "php/devices.handle.php",
	               cache: false,
	               data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getListenerinfo");?>','deviceIp':IP},
	               beforeSend: function () {
	               		target.button('loading'); 
	               },
	               complete: function () {
	               		target.button('reset'); 
	                },
	               success: function (data) {
	              	 data1 = JSON.parse(data);
					   console.log(data1);
	              	var arr=data1[2][1].split(',');
	              	if(data1[2][0]!="No Listener Found"){
	              	html ='<p>Number of Users&nbsp;:&nbsp;'+arr[0]+'</br>Number of Punches Made&nbsp;:&nbsp;'+arr[1]+'</p><p>Current Listener & port&nbsp;:&nbsp;'+data1[2][0].listener_ip+'&nbsp;:&nbsp;'+data1[2][0].listener_port+'</p>';
	              	}else{
		              	html ='<p>Number of Users&nbsp;:&nbsp;'+arr[0]+'</br>Number of Punches Made&nbsp;:&nbsp;'+arr[1]+'</p><p>Current Listener & port&nbsp;:&nbsp;'+data1[2][0]+'&nbsp;</p>';
		              	}
	              	 target.popover({
						  html:true,
						  content:html,
						  placement: 'left',
						  trigger:"focus"
						});
					 $('.device_info').popover('show');
					 $('#listener_ip').val(data1[2][0].listener_ip);
					
	              	 $('#listener_port').val(data1[2][0].listener_port);
	               }
		 });
 	});
	 	
	//registering the tcp listener
     $('.reg-service').on('submit',function(e){
    	 e.preventDefault();
    	 
    	 $('.listener_info').modal('hide');
    	 $.ajax({
   		   dataType: 'html',
               type: "POST",
               url: "php/devices.handle.php",
               cache: false,
               data: $('.reg-service').serialize(),
               beforeSend: function () {
               	$('.reg').button('loading'); 
               	$('.reg').css('background-color','#00A8B3'); 
               },
               complete: function () {
               	$('.reg').button('reset'); 
                },
               success: function (data) {
              	  data1 = JSON.parse(data);
              	if (data1[0] == "success") {
		              	BootstrapDialog.show({
							type: BootstrapDialog.TYPE_SUCCESS,
							title: 'Information',
							message:'<p>Listener Registered Successfully.Device Will send the real time punches to the registered IP</p>',
							buttons: [{
								label: 'ok',
								cssClass: 'btn-primary' ,
								action: function(dialogRef){
									dialogRef.close();
								}
						
							}]
				      });
              	 		
              	   }else
              		   if (data1[0] == "error") {
                            alert('<p>Listener Not Successfully Registered.Try again Later Or contact the Support</p>');
                         }
               }
	       });
         
         });

	</script>
	
</body>
</html>

