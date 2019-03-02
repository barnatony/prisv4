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

<title>Leave Account</title>
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
.bio-row p span {
    width: 90px;
    }
.popover-content {
    padding: 10px 20px 5px 15px;
    width: 280px;
}
.table tbody > tr > td, .table tfoot > tr > td {
    padding: 6px;
}
#emploChosen_chosen,#lrSelected_chosen {
	width: 80% !important;
}

#leaveRequestId{
cursor:pointer;
}
#leave_account_year_chosen, #emploChosens_chosen {
	width: 100% !important;
}
table#Leave_acc td{
    padding:5px;
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
      <?php
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/leaveaccount.class.php");
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/session.class.php");
						
						Session::newInstance ()->_setLeaveRules ();
						$lrArray = Session::newInstance ()->_get ( "leaveRules" );
						$leaveRules = array_merge ( $lrArray ['M'], $lrArray ['Y'] );
						
						$leave = new leave_account ($conn);
						
						$employee = new Employee ();
						$employee->conn = $conn;
						$employeeOnly = $employee->select (1);
						?>
        <section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs " id="leave_account_tabs" role="tablist">
							<li id="header_req_active"><a href="#leave_details"
								id="leave_tabs" data-toggle="tab" data-loaded=false
								data-title="leave_tabs"> Leave Details </a></li>
							<li><a href="#leave_account" id="leave_tabs" data-toggle="tab"
								data-loaded=false data-title="leave_account"> Leave Account </a>
							</li>
							<!-- <li><a href="#leave_apply" id="leave_tabs" data-toggle="tab"
								data-loaded=false data-title="leave_apply"> Apply Leave </a></li> -->
<li><a id="leaveRequestId" href="#leaveRequest" data-toggle="tab"
								data-loaded=false data-title="leaveRequest"> Leave Requests </a>
								<div style="text-align: center;">
								<span id="lrrqSow" class="displayHide"></span></div></li>
								
						</ul>
                    </header>
					<div class="tab-content tasi-tab">
						<div class="tab-pane" id="leave_details">
							<div class="panel-body leave_tab_show">

								<form class="form-horizontal leave_tab_Form" method="post"
									id="leave_tab_Form">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!getSelectedLeaveDetails");?>" />
									<div class="form-group">
										<label for="dname" class="col-lg-1 control-label">Year</label>
										<div class="col-lg-4">
											<select name="leave_account_year" id="leave_account_year"
												class="form-control">
                                       <?php
																																							$result = $leave->getYear ();
																																							if ($result) {
																																								foreach ( $result as $row ) {
																																									?>
                                       	<option
													value="<?php echo $row['years']?>"><?php echo $row['years']?></option>
                                       <?php
																																								}
																																							}
																																							?>
                                        </select>
										</div>
										<div class="col-lg-6">

											<select class="form-control" id="emploChosen"
												name="affectedIds">
		                                          <?php
																																												foreach ( $employeeOnly as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                 </select>

											<button type="submit" class="btn btn-success" id="submit">View</button>
										</div>
									</div>
								</form>
								<div class="panel-body" id="leave_details_submit_form"
									style="display: none;">
									<div class="space15"></div>
									<div class="adv-table editable-table">
										<section id="flip-scroll">
											<div class="text-center">
												<h5>
													Leave Availed For the Year <span id="known_year"><?php echo date('Y', strtotime($_SESSION['fywithMonth']))?></span>
												</h5>
											</div>
											<table class="table table-striped table-hover  cf"
												id="leave_details_submit_table">
												<thead class="cf">
													<tr>
														<th>Employee Name</th>
														<th>Month</th>
                                 <?php
																																	
																																	foreach ( $leaveRules as $comd ) {
																																		
																																		?>
                                 <th><?php echo $comd['alias_name']?></th>
                                 <?php
																																	}
																																	?>
                                 <th>Lop</th>
													</tr>
												</thead>
												<tbody class="leave_details_show">
												</tbody>
											</table>
										</section>
									</div>
								</div>
							</div>





						</div>
						<div class="tab-pane" id="leave_account">
							<div class="panel-body" id="claim_form">
								<form class="form-horizontal leave_account_Form" method="post"
									id="leave_account_Form">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!getLeaveAccount");?>" />

									<div class="form-group">
										<label for="dname" class="col-lg-1 control-label">Year</label>
										<div class="col-lg-4">
											<select name="leave_account_year" id="leave_account_year"
												class="form-control">
                                       <?php
																																							$result = $leave->getLeaveAccountYear ();
																																							if ($result) {
																																								foreach ( $result as $row ) {
																																									?>
                                       	<option
													value="<?php echo $row['years']?>"><?php echo $row['years']?></option>
                                       <?php
																																								}
																																							}
																																							?>
                                        </select>
										</div>
										<div class="col-lg-6">

											<select class="form-control" id="emploChosen"
												name="affectedIds">
		                                          <?php
																																												foreach ( $employeeOnly as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                 </select>

											<button type="submit" class="btn btn-success"
												id="submit_account">View</button>
										</div>
									</div>
								</form>
								<div class="space15"></div>
								<div class="adv-table leave_account_editable-table"
									style="display: none">
									<section id="flip-scroll">
										<div class="text-center">
											<h5>
												Leave Account For the Year <span class="leave_year"></span>
											</h5>
										</div>
										<table class="table table-striped  table-hover  cf"
											id="leave_account_table">
											<thead class="cf">
												<tr>
													<th>Employee</th>
													<th>Leave Type</th>
													<th>Alloted</th>
													<th>Availed</th>
													<th>Lapsed</th>
													<th>Remaining</th>
												</tr>
											</thead>
											<tbody class="getleave_account">
												<tr>
													<td colspan="4"><img src="../img/ajax-loader.gif"
														id="ajax_loader_getmessages"></td>
												</tr>
											</tbody>
										</table>
									</section>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="leave_apply">
							<div class="panel-body">
								<form class="form-horizontal leave_tab_Form" method="post"
									id="leave_tab_Form">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!Claiminsert");?>" />
									<div class="form-group">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Employee</label>
										<div class="col-lg-5">
											<select class="form-control" id="emploChosens"
												name="affectedIds">
		                                          <?php
																																												foreach ( $employeeOnly as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                 </select>
										</div>
									</div>
									<div class="form-group">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Duration</label>
										<div class="col-lg-5">
											<input type="text" name="from_date" id="from_date"
												class="form-control" placeholder="From Date">
										</div>
										<div class="col-lg-5">
											<input type="radio" name="duration" id="duration" value="1">First
											Half <input type="radio" name="duration" id="duration"
												value="0.5">Second Half
										</div>

									</div>
									<div class="form-group">
										<div class="col-lg-2"></div>
										<div class="col-lg-5">
											<input type="text" name="to_date" id="to_date"
												class="form-control" placeholder="To Date">
											<div class="pull-right">
												<label id="date_show"></label>
											</div>
										</div>
										<div class="col-lg-5">
											<input type="radio" name="duration_to" id="duration_to"
												value="0">First Half <input type="radio" name="duration_to"
												id="duration_to" value="0.5">Second Half
										</div>
									</div>
									<div class="form-group">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Leave
											Rule</label>
										<div class="col-lg-5">
											<select name="leave_rule_selected" id="leave_rule_selected"
												class="form-control">
												<option>CL</option>
												<option>SL</option>
												<option>ML</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Reason</label>
										<div class="col-lg-5">
											<textarea rows="3" name="reason" id="reason"  maxlength="100"
												class="form-control"></textarea>
										</div>
									</div>
									<div class="col-lg-2 col-sm-3"></div>
									<div class="col-lg-7 pull-right">
										<button class="btn btn-danger" id="cancel" type="button">Cancel</button>
										<button class="btn btn-success" id="leave_reason"
											type="button">Submit</button>
									</div>
								</form>
							</div>
						</div>
<div class="tab-pane" id="leaveRequest">
							<div class="panel-body">
							<div class="form-horizontal">
								<div id="tableData"></div>
								 <div id="loader" style="width:99%;height:100%"></div>
								<div id="empLeaveDetilas" class="displayHide">
				<!--mail inbox start-->
              <div class="mail-box">
                  <aside class="sm-side">
                      <div class="bio-graph-heading project-heading" style="background: #41CAC0">
                                  <h3><em id="empName"> </em> Requested a <em id="leaveName"></em></h3>             
                          
                      </div>
                      
                      <ul class="inbox-nav inbox-divider" style="background-color:#eff5ed;">
                      <li></li>
                         	<div class="bio-row">
							<p><span class="bold">From date</span>: <span id="fDate">01-05-2016</span> <em id="fhalf">FH</em>
							</p></div>
							<input type="hidden" id="fhalfVal">
							<input type="hidden" id="thalfVal">
							<div class="bio-row">
															<p>
																<span class="bold">To date </span>: <span id="tDate">15-05-2016</span> <em id="thalf">SH</em>
															</p>
														</div>
														
														<div class="bio-row">
															<p>
																<span class="bold">Duration</span>: <span id="durationVal">01-05-2016</span>
															</p>
														</div>
														<div class="bio-row" style="width:100%;">
															<p>
																<span class="bold">Reason</span>: <span id="reasonText" style="width:79%;vertical-align: middle;">01-05-2016</span>
															</p>
														</div>
														<div id="tableContent"></div>
														<div class="bio-row hideRoW">
															<p>
																<span class="bold">Leave Preceeding</span>: <span id="LeavePre"></span>
															</p>
														</div>
														<div class="bio-row hideRoW">
															<p>
																<span class="bold">Leave Middle</span>: <span id="LeaveMid"></span>
															</p>
														</div>
														<div class="bio-row hideRoW">
															<p>
																<span class="bold">Leave Succeeding</span>: <span id="LeaveSuc"></span>
															</p>
														</div>
														<div class="bio-row hideRoW">
															<p>
																<span class="bold">Max Combinable</span>: <span id="maxCom">5</span>
															</p>
														</div>
													
                      </ul>
                      <div class="inbox-body  pull-left">
  <span class="label label-default  text-center hideRoW" >Leave : <em id="leaveResult">d</em></span>  <span class="label label-default text-center hideRoW">Lop : <em id="lopResult">d</em></span>
                         </div>
                      <div class="inbox-body pull-right">
                      
                        <div class="btn-group">
                              <a href="javascript:;" class="btn  btn-sm mini btn-success" id="acceptRequest">
                                  <i class="fa fa-plus"></i> Approve
                              </a>
                          </div>
                          	<input type="hidden" id="lopData">
                            <a href="javascript:;" class="btn btn-sm mini btn-danger"  id="rejectRequest">
                                  <i class="fa fa-ban"></i> Reject
                              </a>
                            <div class="btn-group">
                              <a href="javascript:;" class="btn btn-sm mini btn-default"  id="cancelRequest">
                                  <i class="fa fa-times"></i> Cancel
                              </a>
                          </div>
                         </div>
<div class="popover-markup hidden">
						<section class="panel">
							<div class="content hide">
								<form class="form-horizontal" role="form" id="rejectForm" method="post">
										<div class="input-group col-lg-12">
											<input type="hidden" name="act" value="<?php echo base64_encode("!updatelrRequestStatus");?>" />
											<input type="hidden" name="employee_id" id="empIdLrStatus"/>
											<input type="hidden" name="request_id" id="rqIdLrStatus"/>
											<input type="hidden" class="leaveRuleIdCurrent" name="leaveRuleId">
											<input type="hidden" name="status" value="R"/>
											<textarea class="form-control" name="adminReason" id="adminReason" placeholder="Enter Reason" rows="2" style="resize:none"></textarea>     
    <span id="rejectRq" class="input-group-addon btn btn-default">Reject</span>
										</div>
										<label id="errorReason"></label>
								</form>
							</div>
						</section>
					</div>
                  </aside>
                  <aside class="sm-side" style="background-color:#FFF;">
                      <div class="bio-graph-heading project-heading" style="background: #41CAC0;margin-left:1px;">
                          <h3>Days Description</h3>
                       </div>
                      <div class="inbox-body" id="datesShow">
                       
                      </div>
                  </aside>
              </div>								
								</div>
								</div>
							</div>
						</div>

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
      $('#emploChosen,#leave_account_year,#emploChosens').chosen();
      var lrID='<?php echo  isset($_REQUEST['lrID'])?$_REQUEST['lrID']:null ?>';
       var flag=0;
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
      	      $('#leave_account_tabs a[href="#' + tab + '"]').tab('show');
      	      
      	  	var sum = 0;
      	  }else{
      		  $('#leave_account_tabs a[href="#leave_details"]').tab('show');
      		  
      		  } 

      	  // Change hash for page-reload
      	  $('#leave_account_tabs a').on('shown.bs.tab', function (e) {
      	      window.location.hash = e.target.hash;
      	  })
      	});


        $('#leave_account_tabs').on('shown.bs.tab', function (e) {
      	   $('#from_date,#to_date').datetimepicker({
        format: 'DD/MM/YYYY'        
 	   }); 
      	 window.scrollTo(0, 0);
      	 if($(e.target).data('loaded') === false){
             if($(e.target).data('title') == 'leaveRequest'){  	
                 if(flag==0){			
            	 getRequestedLeave();  		  
                 }		        
      		  }
      	 }
      	  if($(e.target).data('loaded') === false){
  			if($(e.target).data('title') == 'leave_tabs'){  				
  						  		        
  		      }
      	  }
			});

  		
        


				$('#leave_tab_Form').on('submit',function(e){
				e.preventDefault();
						$.ajax({
							processData: false,
						    contentType: false,
						    type: "POST",
						    url: "php/leaveaccount.handle.php",
						    cache: false,
						    data:new FormData(this),
						    beforeSend:function(){
						     	$('#submit').button('loading'); 
						      },
						      complete:function(){
						    	$('#submit').button('reset');
						      },	
							success: function (data) {
									data = JSON.parse(data);
									$('#leave_details_form').hide();
									$('#leave_details_submit_form').show();
									if(data[0] == "success"){
										var html = "";
									for(var i=0;i<data[2].length;i++){
									html +='<tr>';
									$.each(data[2][i], function (k, v) {
									html +='<td>'+v+'</td>';
									});
									html +='</tr>';	
									}
									$('.leave_details_show').html(html);
									}else if(data[0] == "error"){
										
									}
								
							}	
						});	
				 });


				$('#leave_account_Form').on('submit',function(e){
					e.preventDefault();
					$.ajax({
						processData: false,
					    contentType: false,
					    type: "POST",
					    url: "php/leaveaccount.handle.php",
					    cache: false,
					    data:new FormData(this),
					    beforeSend:function(){
					     	$('#submit_account').button('loading'); 
					      },
					      complete:function(){
					    	$('#submit_account').button('reset');
					      },	
						success: function (data) {
								data = JSON.parse(data);
								$('.leave_account_editable-table').show();
								if(data[0] == "success"){
									var html = "";
										for(var i=0;i<data[2].length;i++){
										var remaining = parseFloat(data[2][i].allotted) - parseFloat(data[2][i].availed);
										html +='<tr>';
										html +='<td>'+data[2][i].employee+'</td><td>'+data[2][i].rule_name+'</td><td>'+data[2][i].allotted+'</td><td>'+data[2][i].availed+'</td><td>'+data[2][i].lapsed+'</td><td>'+remaining+'</td>';
										html +='</tr>'
										$('.getleave_account').html(html);  
										}       
							     }else if(data[0] == "error"){
									$('.getleave_account').html('<tr><td colspan="5">No Data Found</td></tr>');
							     }  
							
						}	
					});	
					});
	
					$('#to_date').on('blur',function(e){
						var mdy = daydiff(parseDate($('#from_date').val()), parseDate($('#to_date').val()));
						if($("input[name='duration']:checked").val() !='undefined'){
							// mdy = mdy - $("input[name='duration']:checked").val();
						  }
						  if( $("input[name='duration_to']:checked").val() !='undefined'){
							  mdy = parseFloat(mdy) + parseFloat($("input[name='duration_to']:checked").val());
						  }
						  if(isNaN(mdy)){
						  }else{
							  $('#date_show').html(mdy + ' Days Leave');
						  }
					});


					function getRequestedLeave(){
						$.ajax({
							 dataType: 'html',
                             type: "POST",
                             url: "php/leaveaccount.handle.php",
					         data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getLeaveRequest");?>'},	
					         cache: false,
                             beforeSend:function(){
						    	//$('#submit').button('loading'); 
						      },
						    success: function (data) {
									data = JSON.parse(data);
									requestTablecreate(data[2]);
									flag=1;
							}	
						});	
						}

					function requestTablecreate(data){
						$('#tableData').html('');
						var html = '<section id="flip-scroll"><table class="table table-striped table-hover table-bordered" id="lrRequest-sample"><thead><tr><th>EmployeeID</th><th>EmployeeName</th><th>From Date</th><th>To Date</th><th>Duration</th><th>leave Type</th><th>Reason</th><th>Status</th></tr></thead><tbody>';
						   for (var i = 0, len = data.length; i < len; ++i) {
								html += '<tr>';
							 $.each(data[i], function (k, v) {
								 if(k=='leave_type'){leave_type=v;}
								 if(k=='duration'){duration=v;}
								 if(k=='employee_id'){employee_id=v;}
								 if(k=='employee_name'){employee_name=v;}
								 if(k=='from_date'){from_date=v;}
								 if(k=='reason'){reason=v}
								 if(k=='to_date'){to_date=v}
								 if(k=='from_half'){from_half=v;}else
								 if(k=='to_half'){to_half=v
									 }else if(k=='request_id'){
									 request_id=v;
								 }else if(k=='admin_reason'){}
								  else if(k=='status'){
									  if(v=='RQ'){
									html+='<td style="padding: 6px;"> <a href="#"  data-name="'+employee_name+'" data-duration="'+duration+'" data-lr="'+leave_type+'" data-reason="'+reason+'" data-fhalf="'+from_half+'" data-thalf="'+to_half+'"  data-click="view" data-tdate="'+to_date+'" data-fdate="'+from_date+'" data-requestid="'+request_id+'"  id="'+request_id+'" data-id="'+employee_id+'" title="view" class="view"><button class="btn btn-info btn-xs"><i class="fa fa-eye"></i></button></a></td>';
									  }else if(v=='A'){
									html+='<td style="padding: 6px;"> <a href="#" title="Approved"><button class="btn btn-success btn-xs">Approved</button></a></td>';
										  }
							        	  }
									  else{
									   html += '<td>'+v+'</td>';
									  } 
									}); 
					  	  html += "</tr>";
					  	}
					  	html+='</tbody></table></section>';
					  	$('#tableData').html(html);
					  	$('#lrRequest-sample').dataTable({
					  		 "aaSorting": [[ 7, "asc" ]],
		                });  
					  	if(lrID){
						  	$('#'+lrID).click();
					  	}	
					}

					$(document).on('click', "#lrRequest-sample a.view", function (e) {
		                e.preventDefault();
		                var employeeId=$(this).data('id');
		                var requestId=$(this).data('requestid');
		                $('#rqIdLrStatus').val(requestId);
		                $('#empIdLrStatus').val(employeeId);
		                $('#fDate').html($(this).data('fdate'));
		                $('#durationVal').html($(this).data('duration'));
		                $('#tDate').html($(this).data('tdate'));
		                $('#fhalf').html('[ '+$(this).data('fhalf')+' ] ');
		                $('#fhalfVal').val($(this).data('fhalf'));
		                $('#thalfVal').val($(this).data('thalf'));
		                $('#thalf').html('[ '+$(this).data('thalf')+' ] ');
		                $('#reasonText').html($(this).data('reason'));
		                $('#empName').html($(this).data('name')+' [ '+employeeId+' ]');
		                $('#leaveName').html($(this).data('lr'));
		                var leaveRuleId=$(this).data('lr').toLowerCase();
		                if(leaveRuleId!='co'){
		                $.ajax({
							 dataType: 'html',
                            type: "POST",
                            url: "php/leaveaccount.handle.php",
					         data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getWeekoff");?>',employee_id:employeeId,
						         request_id:requestId,leaveRuleId:leaveRuleId,flag:1},	
					         cache: false,
                            beforeSend:function(){
                            	 $('#loader').loading(true);
						      },
						    success: function (data) {
									jsonData = JSON.parse(data);
									if(jsonData[2][0]!='Map Employee Shift'){
									$('#rejectRequest').popover({ 
										   html: true,
										   placement: "left",
										   title: function () {
										        return $(this).parent().parent().parent().find('.head').html();
										    },
										    content: function () {
										        return $(this).parent().parent().parent().find('.content').html();
										    }
										});	
									$('#tableContent').html('');
									var html='<div class="form-group col-md-12"><label for="dname" class="col-sm-3 control-label">LeaveRule &nbsp;&nbsp;&nbsp;&nbsp;:</label><div class="col-lg-7">';
									html +='<select  id="lrSelected" class="form-control"><option value=""></option><option value="lop">LOP</option><option value="co" data-id="'+employeeId+'_'+requestId+'" >CO</option>';
									//console.log(jsonData[02][01][02][01]);
									for(var i=0;i<jsonData[02][01][02].length;i++){
			                           html+='<option  data-id="'+employeeId+'_'+requestId+'" value="'+jsonData[02][01][02][i].leave_rule_id+'"">'+jsonData[02][01][02][i].alias_name+ ' [ '+jsonData[02][01][02][i].allotted_total+' ] </option>';
			                           }
			                           
									html+='</select><a role="button" tabindex="0" id="popover1" class="give_tooltip" style="margin-left:100px;">View Leave History</a></div></div>';
									$('#tableData').hide();
									$('#tableContent').html(html);
									$('#lrSelected').chosen();
									$("#lrSelected option[value='"+leaveRuleId+"']").prop("selected", true).trigger('chosen:updated');
									loadData(jsonData[02][0]);
                                     $('#empLeaveDetilas').show();
			                        $('#loader').loading(false);
			                        $('#lrrqSow').html('view').show();
			                        $('.leaveRuleIdCurrent').val($('#lrSelected :selected').val());
									}else{
										BootstrapDialog.alert(jsonData[2][0]);
										}
			               }	
						});	
		                }else{
			                  
		                	  $('.hideRoW').hide();
							  var RequestId=requestId;
							 var empid=employeeId;
							 comOffAjax(RequestId,leaveRuleId,empid);
							 $('#tableData').hide();
							$('#empLeaveDetilas').show();
			                }
		                
					});

					$(document).on('change', "#lrSelected", function (e) {
						   e.preventDefault();
						  var ids=$('#lrSelected :selected').data('id');
						  var leaveRuleId=$('#lrSelected :selected').val();
						 if(leaveRuleId!='co' && leaveRuleId!='lop'){
						   $.ajax({
								 dataType: 'html',
	                            type: "POST",
	                            url: "php/leaveaccount.handle.php",
						         data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getWeekoff");?>',employee_id: ids.split('_')[0],
							         request_id: ids.split('_')[1],leaveRuleId:leaveRuleId,flag:0},
						        cache: false,
	                            beforeSend:function(){
	                            	$('#loader').loading(true);
							      },
							    success: function (data) {
										jsonData = JSON.parse(data);
										loadData(jsonData[02][0]);
										$('#loader').loading(false);
				               }	
							});
							}else if(leaveRuleId=='co' && leaveRuleId!='lop'){
								$('.hideRoW').hide();
								  var RequestId=ids.split('_')[1];
								 var empid=ids.split('_')[0];
								 comOffAjax(RequestId,leaveRuleId,empid);
						}else if(leaveRuleId!='co' && leaveRuleId=='lop'){
							$('#rejectRequest').popover({ 
								   html: true,
								   placement: "left",
								   title: function () {
								        return $(this).parent().parent().parent().find('.head').html();
								    },
								    content: function () {
								        return $(this).parent().parent().parent().find('.content').html();
								    }
								});	
							$('#datesShow').html('');
							$('.hideRoW').hide();
					}
					});

					function comOffAjax(RequestId,leaveRuleId,empid){
						$('#datesShow').html('');
						 $.ajax({
							 dataType: 'html',
                            type: "POST",
                            url: "php/leaveaccount.handle.php",
					        data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getComoffLeave");?>',
					        employee_id: empid,leaveRuleId:leaveRuleId,flag:0},
					        cache: false,
                            beforeSend:function(){
                            	$('#loader').loading(true);
						      },
						    success: function (data) {
									jsonData = JSON.parse(data);
									if(jsonData[02][0].length>0){
									comOffTableCreate(jsonData[02][0],RequestId);
									leaveRulegenerate(jsonData[02][1][2],empid,RequestId,leaveRuleId);
									}else if(jsonData[02][1].length==0 ){
										$('#tableContent').html('');
										$('#tableContent').html('<div class="form-group col-md-12"><label for="dname" class="col-lg-3 col-sm-3 control-label">LeaveRule &nbsp;&nbsp;&nbsp;&nbsp; :</label><div class="col-lg-7"><select  id="lrSelected" class="form-control"><option value=""></option><option value="lop">LOP</option><option value="co" selected data-id="'+empid+'_'+RequestId+'" >CO</option></select><a role="button" tabindex="0" id="popover1" class="give_tooltip" style="margin-left:100px;">View Leave History</a></div>');
										$('#lrSelected').chosen();
										$('#datesShow,#leavecheckText,#LeavePre,#LeaveSuc,#LeaveMid,#maxCom').html('');
										$('#datesShow').html('<div class="well">No CompOff Found</div>');
										$('#rejectRequest').popover({ 
											   html: true,
											   placement: "left",
											   title: function () {
											        return $(this).parent().parent().parent().find('.head').html();
											    },
											    content: function () {
											        return $(this).parent().parent().parent().find('.content').html();
											    }
											});	
										   $('#loader').loading(false);
					                        $('#lrrqSow').html('view').show();
									}else if(jsonData[02][0].length==0 ){
										 leaveRulegenerate(jsonData[02][1][2],empid,RequestId,leaveRuleId);
										 $('#datesShow').html('<div class="well">No CompOff Found</div>');
									}else{
										$('#datesShow,#leavecheckText,#LeavePre,#LeaveSuc,#LeaveMid,#maxCom').html('');
										$('#datesShow').html('<div class="well">No CompOff Found</div>');
										}
									$('#loader').loading(false);
			               }	
						});	}

					function leaveRulegenerate(data,empid,RequestId,leaveRuleId){
						$('#rejectRequest').popover({ 
							   html: true,
							   placement: "left",
							   title: function () {
							        return $(this).parent().parent().parent().find('.head').html();
							    },
							    content: function () {
							        return $(this).parent().parent().parent().find('.content').html();
							    }
							});	
						$('#tableContent').html('');
						var html='<div class="form-group col-md-12"><label for="dname" class="col-lg-3 col-sm-3 control-label">LeaveRule &nbsp;&nbsp;&nbsp;&nbsp; :</label><div class="col-lg-7">';
						html +='<select  id="lrSelected" class="form-control"><option value=""></option><option value="lop">LOP</option><option value="co" data-id="'+empid+'_'+RequestId+'" >CO</option>';
						for(var i=0;i<data.length;i++){
                        html+='<option  data-id="'+empid+'_'+RequestId+'" value="'+data[i].leave_rule_id+'"">'+data[i].alias_name+ ' [ '+data[i].allotted_total+' ] </option>';
                        }
                        html+='</select><a role="button" tabindex="0" id="popover1" class="give_tooltip" style="margin-left:100px;">View Leave History</a></div>';
						$('#tableData').hide();
						$('#tableContent').html(html);
						$('#lrSelected').chosen();
						$("#lrSelected option[value='"+leaveRuleId+"']").prop("selected", true).trigger('chosen:updated');
						$('#empLeaveDetilas').show();
                        $('#loader').loading(false);
                        $('#lrrqSow').html('view').show();
                        //$('.leaveRuleIdCurrent').val($('#lrSelected :selected').val());
						}
					function comOffTableCreate(data,requestId){
						$('#datesShow,#leavecheckText,#LeavePre,#LeaveSuc,#LeaveMid,#maxCom').html('');
						var html="<table class='table table-striped table-hover table-bordered cf dataTable'><thead><tr><th></th><th>Day</th><th>Worked On</th><th>Action</th></tr></thead><tbody>";
						$.each(data, function (k, v) {
							html+='<tr><td><input type="checkbox" class="comOffAccepted" data-cid="'+v.compoff_id+'" data-id="'+v.employee_id+'"  data-rid="'+requestId+'"  data-val="'+'co__'+v.day_count+'#"  '+((v.is_processed=='-1')?'disabled':'')+'></td><td>'+v.date + '  <span class="label label-danger">'+v.day_count+'</span> </td><td>'+v.working_for+'</td><td><button class="btn btn-warning btn-xs elapsedIt" data-id="'+v.employee_id+'"  data-Cid="'+v.compoff_id+'" '+((v.is_processed=='-1')?'disabled':'')+' title="This Will Elaped the Comp-off">Elapse It</button></td></tr>';
						});
						html+='</tbody></table><p id="comoffError">Select equal number of days  as the requested dates </p>';
						$('#datesShow').html(html);
						$('.hideRoW').hide();
						}
					
					function loadData(dataArray){
						$('.hideRoW').show();
						var data="<table class='table table-inbox table-hover'><thead><tr><th>Dates</th><th>DESC.</th><th>Day type</th><th>Day Count</th></tr></thead><tbody>";
						var numeric="";
						 $.each(dataArray, function (k, v) {
							 if(v!=null){
                         if(k!='leave' && k!='lop' && k!='lrPre' && k!='lrSuc' && k!='lrMid' && k!='duration' && k!='maxCom'){
						   data+='<tr><td>'+k.split('-')[2]+'/'+k.split('-')[1]+'/'+k.split('-')[0]+'</td><td>'+((v.isWorkingDay!=null)?'Working Day':((v.isWeekend!=null)?'Week End':'Holiday'))+'<td>'+((v.dataType!=null)?v.dataType:'FD')+'</td><td>'+v.dayCount+'</td></tr>';
						 }
                         var leaveRuleId=$('#lrSelected :selected').val();
                         if(v.lop!=null)
                         numeric+= "#"+k+'_'+((v.lop==0)?leaveRuleId:'lop')+'_'+((v.dataType!=null)?v.dataType:'FD')+'_'+((v.dayCount!=null)?v.dayCount:'');
							 }
	                    });
                        $('#lopData').val(numeric);
                        //$('.leaveRuleIdCurrent').val($('#lrSelected :selected').val());
                        data+='</tbody></table>';
                        $('#datesShow').html(data);
                        $('#LeavePre').html(((dataArray.lrPre=='B')?'Hoilday,Weekend':((dataArray.lrPre=='W')?'Week End':((dataArray.lrPre=='H')?'Hoilday':'None'))));
                        $('#LeaveSuc').html(((dataArray.lrSuc=='B')?'Hoilday,Weekend':((dataArray.lrSuc=='W')?'Week End':((dataArray.lrSuc=='H')?'Hoilday':'None'))));
                        $('#LeaveMid').html(((dataArray.lrMid=='B')?'Hoilday,Weekend':((dataArray.lrMid=='W')?'Week End':((dataArray.lrMid=='H')?'Hoilday':'None'))));
                        $('#maxCom').html(dataArray.maxCom);
                        //$('#durationVal').html(((dataArray.duration!=null)?dataArray.duration:0));
                        $('#leaveResult').html(((dataArray.leave!=null)?dataArray.leave:0));
                        $('#lopResult').html(((dataArray.lop!=null)?dataArray.lop:0));
	                    //$("#leavecheckText").html('<table class="table table-bordered" style="width:25%;"><tr><th style="padding:6px;">Duration</th><td >'+((dataArray.duration!=null)?dataArray.duration:0)+'</td></tr><tr><th style="padding:6px;">Leave</th><td>'+((dataArray.leave!=null)?dataArray.leave:0)+'</td></tr><tr><th style="padding:6px;">Lop</th><td>'+((dataArray.lop!=null)?dataArray.lop:0)+'</td></tr></tbody></table>');
                      //  $("#leavecheckText").html('<ul class="inbox-nav"><li><a href="#">Duration <span class="label label-warning pull-right">'+((dataArray.duration!=null)?dataArray.duration:0)+'</span></a></li><li><a href="#">Leave <span class="label label-success pull-right">'+((dataArray.leave!=null)?dataArray.leave:0)+'</span></a></li><li><a href="#">Lop <span class="label label-danger pull-right">'+((dataArray.lop!=null)?dataArray.lop:0)+'</span></a></li></ul>');
                     //   $("#leavecheckText").html('<ul class="inbox-nav"><li><span class="label label-default pull-right"> Duration '+((dataArray.duration!=null)?dataArray.duration:0)+'</span><span class="label label-success pull-right"> Leave '+((dataArray.leave!=null)?dataArray.leave:0)+'</span><span class="label label-danger pull-right"> Lop '+((dataArray.lop!=null)?dataArray.lop:0)+'</span></li></ul>');
                        }
					 $(document).on('click', "#rejectRq", function (e) {
                         e.preventDefault();
                         if($('#adminReason').val()!=''){
                         $.ajax({
                         dataType: 'html',
                         type: "POST",
                         url: "php/leaveaccount.handle.php",
                         cache: false,
                         data: $('#rejectForm').serialize(),
                         success: function (data) {
                        	 data = JSON.parse(data);
                             requestTablecreate(data[2]);
	                        	$('#tableData').show();$('#empLeaveDetilas').hide();
	                        	$('#rejectRequest').popover('destroy');
	    						BootstrapDialog.alert(data[1]);
                         }
                         });
                         }else{
                             $('#errorReason').html('Reject Reasons');
                         }
                            
					 });
					 
					  $(document).on('click', "#acceptRequest", function (e) {
						  e.preventDefault();
						  $('#rejectRequest').popover({ 
							   html: true,
							   placement: "left",
							   title: function () {
							        return $(this).parent().parent().parent().find('.head').html();
							    },
							    content: function () {
							        return $(this).parent().parent().parent().find('.content').html();
							    }
							});	
                       var leaveRuleId=$('#lrSelected :selected').val();
                       var rqId=$('#rqIdLrStatus').val();
  		               var empId=$('#empIdLrStatus').val();
  		             if(leaveRuleId!='CO' &&  leaveRuleId !='co' && leaveRuleId !='lop' ){
  		            	  var lopData=$('#lopData').val();
                         BootstrapDialog.show({
          	                title:'Confirmation',
                              message: 'Are Sure you want to Approve ?',
                              buttons: [{
                                  label: 'YES',
                                  cssClass: 'btn-sm btn-success',
                                  autospin: true,
                                  action: function(dialogRef){
                                	  updateEmpabsences(empId,rqId,lopData,leaveRuleId,''); 
                                   }
                              }, {
                                  label: 'Close',
                                  cssClass: 'btn-sm btn-danger',
                                  action: function(dialogRef){
                                      dialogRef.close();
                                  }
                                     }]
                          });
  		              }else if(leaveRuleId=='co' && leaveRuleId!='lop'){
  	  		           var rqId=$('input.comOffAccepted:checked').data('rid');
  		            	var empId=$('input.comOffAccepted:checked').data('id');
  		            	var lopData='';var cidData='';
  		            	var startVar=$('#fDate').html().split('/');
  		            	var endVar=$('#tDate').html().split('/');
  		            		var start = new Date(startVar[01]+'/'+startVar[0]+'/'+startVar[02]);
  		            		var end = new Date(endVar[01]+'/'+endVar[0]+'/'+endVar[02]);
  		            		var date = []; 
  		            	  while(start<= end){
  		            		date.push(start.getFullYear()+'-'+(start.getMonth() + 1) + '-' + start.getDate()   );
  						        var newDate = start.setDate(start.getDate() + 1);
  						       start = new Date(newDate);
  						    }
						var i=0;
  		            	$('input.comOffAccepted:checked').each(function () {
  		            		lopData+=date[i]+'_'+$(this).data('val');
  		            		cidData+="'"+$(this).data('cid')+"'_";
  		            		i++;
  		            	});
  		            	if(i==date.length){
  		              	updateEmpabsences(empId,rqId,lopData,'co',cidData);
  		              	$('#comoffError').css('color','');
  		              	}
  		            	else{
  		            		$('#comoffError').css('color','red');
  		            	}
  	  		             
  		              }else if(leaveRuleId=='lop'){
  	  		            var lopData='';
   		            	var startVar=$('#fDate').html().split('/');
   		            	var endVar=$('#tDate').html().split('/');
   		            		var start = new Date(startVar[01]+'/'+startVar[0]+'/'+startVar[02]);
   		            		var startDate = new Date(startVar[01]+'/'+startVar[0]+'/'+startVar[02]);//defalut date store
   		            		var end = new Date(endVar[01]+'/'+endVar[0]+'/'+endVar[02]);
   		            	 while(start<= end){
   		            		 dayType='FD';
   	   	   		             dayCount=1;
   	   	   		    /*if((startDate-end)  == 0){
   	   	   	   		         dayCount=(($('#fhalfVal').val()=='SH' && $('#thalfVal').val()=='SH') || ($('#fhalfVal').val()=='FH' && $('#thalfVal').val()=='FH' ))?0.5:0;
   	   	                     dayType=(($('#fhalfVal').val()=='SH' && $('#thalfVal').val()=='SH') || ($('#fhalfVal').val()=='FH' && $('#thalfVal').val()=='FH' ))?$('#fhalfVal').val():0;
 	   		             }else{*/   		             
   	   	   		             if((startDate-start)  == 0){
   	   	   		               dayCount=($('#fhalfVal').val()=='SH' )?0.5:1;
   	   	   	   		           dayType=($('#fhalfVal').val()=='SH')?'SH':'FD';
   	   	   		             }
   	   	   		             if((end-start) == 0){ 
   	   	   		               dayCount=($('#thalfVal').val()=='FH')?0.5:1;
   	   	   		               dayType=($('#thalfVal').val()=='FH')?'FH':'FD';
		            		 }
 	   		             //}
   	   		            lopData+=start.getFullYear()+'-'+(start.getMonth() + 1) + '-' + start.getDate()+'_lop_'+dayType+'_'+dayCount+'#';
   						var newDate = start.setDate(start.getDate() + 1);
   						start = new Date(newDate);
   						    }
						   updateEmpabsences(empId,rqId,lopData,'lop',''); 
  	  		          }
               });

                      function updateEmpabsences(empId,rqId,lopData,leaveRuleId,cidIds){
                    	  $.ajax({
     		                    dataType: 'html',
     		                    type: "POST",
     		                    url: "php/leaveaccount.handle.php",
					            cache: false,
     		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!updatelrRequestStatus");?>',employee_id:empId,
         		                    request_id:rqId,status:'A',lopData:lopData,leaveRuleId:leaveRuleId,comOff:cidIds},
         		                   complete:function(){
         		                    	 $('.close').click();
         		                      },
     		                    success: function (data) {
     		                    	data = JSON.parse(data);
     		                        if (data[0] == "success") {
     		                        	requestTablecreate(data[2]);
     		                        	$('#tableData').show();$('#empLeaveDetilas').hide();
     		                        	$('#rejectRequest').popover('destroy');
     		 						    BootstrapDialog.alert(data[1]);
     		                        }else if (data[0] == "error") {
		                                    alert(data[1]);
		                                }
     		                    }
     		                });
   		                }

					  $('#cancelRequest').click(function(e){
						  $('#tableData').show();$('#empLeaveDetilas').hide();
						  $('#rejectRequest').popover('destroy');
						  $('#lrrqSow').html('');
						  var s =window.location.href;
							var val=removeURLParameter(s,'lrID');
							  $('#lrrqSow').html('').hide();
							  window.history.pushState("", document.title,val); 
					 });
						 
					  $('#leaveRequestId').click(function(e){
						  var s =window.location.href;
						var val=removeURLParameter(s,'lrID');
						  $('#lrrqSow').html('').hide();
						  window.history.pushState("", document.title,val); 
						  var data='<?php $_REQUEST['lrID']='';?>';
						  $('#rejectRequest').popover('destroy');
						  $('#tableData').show();$('#empLeaveDetilas').hide();
				     });

					  $(document).keyup(function (event) {
						   if (event.which === 27) {
							        $('#rejectRequest').popover('hide');
							    }
					  });
					  
					  function removeURLParameter(url, parameter) {
						    //prefer to use l.search if you have a location/link object
						    var urlparts= url.split('?');   
						    if (urlparts.length>=2) {

						        var prefix= encodeURIComponent(parameter)+'=';
						        var pars= urlparts[1].split(/[&;]/g);

						        //reverse iteration as may be destructive
						        for (var i= pars.length; i-- > 0;) {    
						            //idiom for string.startsWith
						            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
						                pars.splice(i, 1);
						            }
						        }

						        url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
						        return url;
						    } else {
						        return url;
						    }
						}

					  $(document).on('click', ".elapsedIt", function (e) {
                          e.preventDefault();
                         $.ajax({
								 dataType: 'html',
	                            type: "POST",
	                            url: "php/leaveaccount.handle.php",
						        data: {act: '<?php echo base64_encode($_SESSION['company_id']."!setelapsedIt");?>',
						        employee_id:$(this).data('id'),compOff:$(this).data('cid'),isprocessed:'-1'},
						        cache: false,
	                            beforeSend:function(){
	                            	$('#loader').loading(true);
							      },
							    success: function (data) {
										jsonData = JSON.parse(data);
										if(jsonData[02].length>0)
										comOffTableCreate(jsonData[02],'');
										else
										$('#datesShow').html('<div class="well">No CompOff Found</div>');
										
										$('#loader').loading(false);
				               }	
							});	
					  });

					 $(document).on('click',"#popover1",function(e){
						  e.preventDefault();
						  var empID = $('#empIdLrStatus').val();
						  $.ajax({
				                 dataType: 'html',
				                 type: "POST",
				                 url: "php/leaveaccount.handle.php",
				                 cache: false,
				                 data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getPreviousLeave");?>','employee_id': empID},
				                 success: function (data) {
					                 data = JSON.parse(data);
					                 var leavedetails = data[2];
					                 var html='';
					                 if(data[2] !=""){
						                html +='<table class="table table-bordered" id=Leave_acc><tbody>';
						                html +='<tr><td><b>Date</b></td><td><b>Leave Type</b></td><td><b>Reason</b></td></tr>';
										for(i=0;i<leavedetails.length;i++){
						  				   html +='<tr>';
						  				   $.each(leavedetails[i],function(k,v){
						  				    	html +='<td>'+v+'</td>';
						  					});
						  					html +='</tr>';
						  				  }
						                html+='</tbody></table>';
						                
					                 }else{
										html += 'No History available for this employee.';
						             }
					                 $("#popover1").popover(
					     					   {html:true,content:html,trigger:"focus"});
					                $("#popover1").popover("show");
				                 }
						  });
					  });

					 
					</script>

</body>
</html>
