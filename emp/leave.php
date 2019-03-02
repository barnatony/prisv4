<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Leave Account</title>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style>
#leaveRequestId{
cursor:pointer;
}
.bio-row p span {
    width: 111px;
    }
    
#leaveDetailsTable  td {
    padding: 6px;
}
.label{
border-radius: 9px;
}
#leave_account_year_chosen, #leave_rule_selected_chosen {
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
     <?php include_once (__DIR__."/header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once (__DIR__."/sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
      <?php
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/session.class.php");
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/leaveaccount.class.php");
						
						$leave = new leave_account ($conn);
						Session::newInstance ()->_setLeaveRules ();
						$lrArray = Session::newInstance ()->_get ( "leaveRules" );
						$leaveRules = array_merge ( $lrArray ['M'], $lrArray ['Y'] );
						?>
        <section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs " id="leave_account_tabs" role="tablist">
							<li id="header_req_active"><a href="#leave_tab" id="leave_tabs"
								data-toggle="tab" data-loaded=false data-title="leave_tab">
									Leave Details </a></li>
							<li><a href="#leave_account_tab" id="leave_tabs"
								data-toggle="tab" data-loaded=false data-title="leave_account_tab">
									Leave Account </a></li>
							<li><a href="#leaveRequest" id="leaveRequestId" data-toggle="tab"
								data-loaded=false data-title="leaveRequest"> Leave Apply </a>
								<div style="text-align: center;">
								<span id="lrrqSow" class="displayHide"></span></div>
                            </li>

						</ul>

					</header>
					<div class="tab-content tasi-tab">
						<div class="tab-pane" id="leave_tab">
							<div class="panel-body leave_tab_show">
								<form class="form-horizontal leave_tab_Form" method="post"
									id="leave_tab_Form">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!getSelectedLeaveDetails");?>" />
									<div class="form-group">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Year</label>
										<div class="col-lg-5">
											<select name="leave_account_year" id="leave_account_year"
												class="form-control">
                                       <?php
										$result = $leave->getYear ();
										if ($result) {
										foreach ( $result as $row ) {
										echo '<option value=' . $row ['years'] . '>' . $row ['years'] . '</option>';
										}
										}
										?>
                                        </select>
										</div>
										<div class="col-lg-3">
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
											<table class="table table-bordered table-hover  cf"
												id="leave_details_submit_table">
												<thead class="cf">
													<tr>
														<th>Month</th>
                                 <?php
																																	foreach ( $leaveRules as $comd ) {
																																		echo '<th>' . $comd ['alias_name'] . '</th>';
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


								<div class="panel-body" id="leave_details_form">
									<div class="space15"></div>
									<div class="adv-table editable-table">
										<section id="flip-scroll">
											<div class="text-center">
												<h5>Leave Availed For the Current Year <?php echo date('Y', strtotime($_SESSION['fywithMonth']))?></h5>
											</div>
											<table class="table table-bordered table-hover  cf"
												id="leave_details_table">
												<thead class="cf">
													<tr>
														<th>Month</th>
                                 <?php
																																	
																																	foreach ( $leaveRules as $comd ) {
																																		echo '<th>' . $comd ['alias_name'] . '</th>';
																																	}
																																	?>
                                 <th>Lop</th>
													</tr>
												</thead>
												<tbody class="getClaimrule">
													<tr>
                           
                           <?php
																											$result = $leave->getLeaveDetails ( $leaveRules, $_SESSION ['employee_id'] );
																											if ($result) {
																												foreach ( $result as $row ) {
																													if (isset ( $row ['monthYear'] ) == '') {
																														echo '<td>' . date ( 'M', strtotime ( $_SESSION ['fywithMonth'] ) ) . '</td>';
																													} else {
																														echo '<td>' . $row ['monthYear'] . '</td>';
																													}
																													
																													foreach ( $leaveRules as $comd ) {
																														echo '<td>' . $row [$comd ['leave_rule_id']] . '</td>';
																													}
																													echo '<td>' . $row ['lop'] . '</td>';
																												}
																											}
																											?>
                           </tr>
												</tbody>
											</table>
										</section>
									</div>
								</div>



							</div>





						</div>
						<div class="tab-pane" id="leave_account_tab">
							<div class="panel-body" id="claim_form">
								<div class="space15"></div>
								<div class="adv-table editable-table">
									<section id="flip-scroll">
										<div class="text-center">
                          	<?php
																											$leaveYear = ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
																											?>
                          	<h5>Leave Account For the Year <?php echo $leaveYear;?></h5>
										</div>
										<table class="table table-striped  table-hover  cf"
											id="leave_account_table">
											<thead class="cf">
												<tr>
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
						<div class="tab-pane" id="leaveRequest">
						<div class="panel-body">
							<div class="form-horizontal">
								<div id="empLeaveDetilas" class="displayHide">
								<div class="col-md-12" id="showurl" style="">

									<header class="panel-heading"> </header>
                                    <div id="loader" style="width:99%;height:100%"></div>
									<section class="panel">
										<div class="bio-graph-heading project-heading">
											<span class="employee_claim_name">Leave Request View </span>
										</div>
										<div class="panel-body bio-graph-info">
											<!--<h1>New Dashboard BS3 </h1>-->
											<div class="row p-details">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="bio-row">
															<p>
																<span class="bold">From date</span>: <span id="fDate">01-05-2016</span> <em id="fhalf">FH</em>
															</p>
														</div>

														<div class="bio-row">
															<p>
																<span class="bold">To date </span>: <span id="tDate">15-05-2016</span> <em id="thalf">SH</em>
															</p>
														</div>
														<div class="bio-row" style="width:100%;">
															<p>
																<span class="bold">Reason</span>: <span id="reasonText" style="width:79%;vertical-align: middle;">01-05-2016</span>
															</p>
														</div>
														<div id="tableContent"></div>	
															<div class="bio-row">
															<p>
																<span class="bold">Leave Preceeding</span>: <span id="LeavePre"></span>
															</p>
														</div>
														<div class="bio-row">
															<p>
																<span class="bold">Leave Middle</span>: <span id="LeaveMid"></span>
															</p>
														</div>
														<div class="bio-row">
															<p>
																<span class="bold">Leave Succeeding</span>: <span id="LeaveSuc"></span>
															</p>
														</div>
														<div class="bio-row">
															<p>
																<span class="bold">Max Combinable</span>: <span id="maxCom">5</span>
															</p>
														</div>
															<div class="bio-row">
															<p>
																<span class="bold"><b>Lop</b></span> : <span id="lop"></span>
															</p>
														</div>
														<div class="bio-row">
															<p>
																<span class="bold"><b>Leave</b></span> : <span id="leave"></span>
															</p>
														</div>
														<div class="col-md-6 alert alert-success fade in">
    															<strong>Approved</strong> </div>
														</div>
																
													<div class="col-md-6">

														<header class="panel-heading" style="padding-left: 0px; font-weight: 600;">Request Leave Details
														</header>
														<div id="datesShow"></div>
													</div>

												</div>


											</div>
										</div>
								</section></div>
								
								</div>
								</div>
							</div>
							<div class="panel-body" id="raiseLrRq">
							<div class="btn-group pull-right">
	<button id="showhide" type="button" class="btn btn-sm btn-info"
		style="margin-top: -26%;">
		<i class="fa fa-plus"></i> Leave Request 
	</button>
</div>
								<form class="form-horizontal displayHide" method="post"
									id="leaveApplyForm">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!leaveRequested");?>" />
									<div class="form-group row col-lg-12">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Duration</label>
										<div class="col-lg-4">
											<input type="text" name="from_date" id="from_date"
												class="form-control" placeholder="From Date">
										</div>

										<div class="col-lg-6">
											<label for="fh" class="col-lg-3 control-label"> <input
												type="radio" id="fh" value="FH" checked
												name="duration_from"> First Half
											</label> <label for="sh" class="col-lg-3 control-label"> <input
												id="sh" value="SH" type="radio" name="duration_from">
												Second Half
											</label>
										</div>
									</div>

									<div class="form-group row col-lg-12">
										<div class="col-lg-2"></div>
										<div class="col-lg-4">
											<input type="text" name="to_date" id="to_date"
												class="form-control" placeholder="To Date">
											<div class="pull-right">
												<label id="date_show"></label>
											</div>
										</div>
										<div class="col-lg-6">
											<label for="tfh" class="col-lg-3 control-label"> <input
												type="radio" id="tfh" value="FH" name="duration_to"> First
												Half
											</label> <label for="tsh" class="col-lg-3 control-label"> <input
												id="tsh" value="SH" type="radio" checked name="duration_to">
												Second Half
											</label>
										</div>
									</div>
									<input type="hidden" name="durationCount" id="durationCount">
									<div class="form-group">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Leave
											Rule</label>
										<div class="col-lg-5">
											<select name="leave_rule_selected" id="leave_rule_selected"
												class="form-control"> 
                                          <?php
											$result = $leave->select ( $_SESSION ['employee_id'], $leaveYear, '' );
											echo '<option value=""></option><option value="co">CO</option>';
											foreach ( $result[2] as $comd ) {
												echo '<option data-all=' . $comd ['allotted_total'] . ' data-id=' . $comd ['max_combinable'] . ' value=' . $comd ['leave_rule_id'] . '>' . $comd ['alias_name'] . ' [ '.$comd ['allotted_total'].' ] </option>';
											}
											?> 
			                             </select>
										</div>
									</div>
									<div class="form-group">
										<label for="dname" class="col-lg-2 col-sm-3 control-label">Reason</label>
										<div class="col-lg-5">
											<textarea rows="3" name="reason" id="reason" maxlength="100"
												class="form-control"></textarea>
											<br>
											<div id="error" class="displayHide"
												style="padding: 11px; color: #ff6c60; background-color: #fff;"></div>
										</div>
									</div>

									<div class="col-lg-7 pull-right" id="leaveSubmit">
										<button class="btn btn-sm  btn-success" id="submitButton"  type="submit">Submit</button>
										<button class="btn btn-sm  btn-danger" type="button" id="applyCancel">Cancel</button>
									</div>
								</form>
							<div class="panel-body">
							<div id="requetedLoader"  style="height: 58%; width: 96%"></div>
									<div class="space15"></div>
									<div class="adv-table editable-table" id="leaveApplyContent">
									</div>
								</div>
								</div>
						</div>
					</div>
				</section>
			</section>
			<!--main content end-->
			<!--footer start-->
		<?php include_once (__DIR__."/footer.php");?>
      <!--footer end-->
		</section>
	</section>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>

	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/respond.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<!--script for this page only-->
	<script src="../js/chosen.jquery.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>

	<script type="text/javascript">
      $(document).ready(function () {
    	  $('#leave_account_year,#leave_rule_selected').chosen();
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
    	      $('#leave_account_tabs a[href="#' + tab + '"]').tab('show');
      	   var sum = 0;
      	  }else{
      		  $('#leave_account_tabs a[href="#leave_tab"]').tab('show');
      		  
      		  } 

      	  // Change hash for page-reload
      	  $('#leave_account_tabs a').on('shown.bs.tab', function (e) {
      	      window.location.hash = e.target.hash;
      	  })
      	});

      var dateval ='<?php echo $_SESSION ['current_payroll_month'];?>';
        $('#leave_account_tabs').on('shown.bs.tab', function (e) {
      	   // newly activated tab    
      	$('#from_date,#to_date').datetimepicker({
        	format: 'DD/MM/YYYY',
        	maxDate:false,
        	minDate: dateval
 	     }); 
	   
      	 window.scrollTo(0, 0);
      	  if($(e.target).data('loaded') === false){
         if($(e.target).data('title') == 'leave_account_tab'){  				
			 leave_account();  		  		        
  		  }
		 if($(e.target).data('title') == 'leaveRequest'){  		
			 var lrID='<?php echo  isset($_REQUEST['lrID'])?$_REQUEST['lrID']:null ?>';
		     if(lrID){
	    	 getRequestedLeave(lrID);  	
	    	 }else{
	    	 getRequestedLeave('');  	
		    }	        
  		} 
  			}
  			//make the tab loaded true to prevent re-loading while clicking.
        		$(e.target).data('loaded',true);
      		
      	});
        
					function leave_account(){
						 $.ajax({
					    		dataType: 'html',
					    		type: "POST",
					            url: "php/leaveaccount.handle.php",
					            cache: false,
					            data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getLeaveAccount");?>'},	
					            success: function (data) {
					            data = JSON.parse(data);
					             if(data[0] == "success"){
									var html = "";
					     			for(var i=0;i<data[2].length;i++){
										var remaining = parseFloat(data[2][i].allotted) - parseFloat(data[2][i].availed);
										html +='<tr>';
										html +='<td>'+data[2][i].rule_name+'</td><td>'+data[2][i].allotted+'</td><td>'+data[2][i].availed+'</td><td>'+data[2][i].lapsed+'</td><td>'+remaining+'</td>';
										html +='</tr>'
										$('.getleave_account').html(html);  
					     			}    
					     		$('#leave_account_table').dataTable({
					     			 "bInfo":false,
		                			 "bPaginate": false,
		                			 "bFilter": false,
				               });       
					     		  }else if(data[0] == "error"){
									$('.getleave_account').html('<tr><td colspan="5">No Data Found</td></tr>');
					             }  
					                 
					            }
					        });
						
					}
						
					
							$('#leave_tab_Form').on('submit',function(e){
								$('#known_year').html($('#leave_account_year :selected').val());
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

							
							$('#leaveApplyForm').on('submit',function(e){
								 e.preventDefault();
								 if($('#from_date').val()!='' && $('#to_date').val()!='' && $('#leave_rule_selected').val()!='' && $('#reason').val()!='' ){
									 $('#error').html('').hide();
								 	$.ajax({
								 		 dataType: 'html',
		                                 type: "POST",
		                                 url: "php/leaveaccount.handle.php",
										 cache: false,
		                                 data: $('#leaveApplyForm').serialize(),
		                                 beforeSend:function(){
									     	$('#submitButton').button('loading'); 
									      },
									    success: function (data) {
											data = JSON.parse(data);
											if(data[0]=='success'){
												$('#date_show').html('');
												$("#leave_rule_selected option[value='']").prop("selected", true).trigger('chosen:updated');
												$('#leaveApplyForm')[0].reset();
												leaveApplyTableCreation(data[2]);
												$('[data-toggle="popover"]').popover();   
												$('#leaveApplyForm').toggle('hide');
												BootstrapDialog.alert(data[1]);
											    $('#submitButton').button('reset');
												}else{
													$('#submitButton').button('reset');
													$('#leaveApplyForm').toggle('hide');
													BootstrapDialog.alert(data[1] + data[2]);
													
											   }
										 }	
									});	}else{
										$('#error').html('* Enter All Required Fields').show();
										}
							});
							
							$('#from_date,#to_date,input[type=radio][name=duration_from],input[type=radio][name=duration_to]').on('blur change',function(e){
								if(!$('#to_date').val()){
									$('#to_date').val($('#from_date').val());
									}
								checkDuration();
							 });
							 
							
							 $('#leave_rule_selected,#from_date,#to_date').on('change blur', function (e) {
								 e.preventDefault();
								 $('#error').html('').hide();
								 $('#leaveSubmit').show();
								 checkDuration();
									/* if(Number($('#durationCount').val())<=Number($('#leave_rule_selected :selected').data('all'))){
									 $('#error').html('').hide();
									 $('#leaveSubmit').show();
									 checkDuration();
								 }else{
									$('#error').html('Leave Not applicable').show();
									$('#leaveSubmit').hide();
							     }*/
				     		});
					     		function checkDuration(){
					     			var mdy = daydiff(parseDate($('#from_date').val()), parseDate($('#to_date').val()));
									  if( $("input[name='duration_to']:checked").val() !='undefined'){
										  mdy = (parseFloat(mdy)-1)+
										  parseFloat(($("input[name='duration_from']:checked").val()=='FH')?1:0.5)+
										  parseFloat(($("input[name='duration_to']:checked").val()=='SH')?1:0.5);
	                               }
								  if(!isNaN(mdy) && mdy>0 ){
									  $('#date_show').html(mdy + ' Days Leave');	
									  $('#durationCount').val(mdy);
									  $('#error').html('').hide();
									  $('#leaveSubmit').show();
								  }else{
									  $('#error').html('Date Format Wrong').show();
									  $('#durationCount').val('');
									  $('#date_show').html('');
									  $('#leaveSubmit').hide();
							    }}

								 function getRequestedLeave(lrID){
									 $.ajax({
								 		 dataType: 'html',
		                                 type: "POST",
		                                 url: "php/leaveaccount.handle.php",
		 					             data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getLeaveRequest");?>',request_id:lrID},	
		   					             cache: false,
		                                 beforeSend:function(){
		                                	 $("#requetedLoader").loading(true);
									      },
									     success: function (data) {
											data = JSON.parse(data);
											if(data[0]=='success'){
												leaveApplyTableCreation(data[2]);
												$('[data-toggle="popover"]').popover();   
												$("#requetedLoader").loading(false);
										   }else{
												$("#requetedLoader").loading(false);
											   }
										 }	
									});
								}
								 $('#showhide').on('click',function(e){
									 e.preventDefault();
									  $('#leaveApplyForm').toggle('show');
							     });
									
								 $('#applyCancel').on('click',function(e){
									 e.preventDefault();
									    $('#date_show,#error').html('');
										$("#leave_rule_selected option[value='']").prop("selected", true).trigger('chosen:updated');
										$('#leaveApplyForm')[0].reset();
										$('#leaveApplyForm').toggle('hide');
								});
									
function leaveApplyTableCreation(data){
	 var html = '<section id="flip-scroll"><table id="leaveApplyTable" class="table table-striped  table-hover  cf"><thead><tr><th>From Date</th><th>To Date</th><th>Duration</th><th>leave Type</th><th>Reason</th><th>Status</th></tr></thead><tbody>';
	   for (var i = 0, len = data.length; i < len; ++i) {
			html += '<tr>';
			 adminReason='';
		   $.each(data[i], function (k, v) {	
			   if(k=='employee_id'){
				 employee_id=v;
			 }
				 if(k=='request_id'){
				 request_id=v;
				 }
				 if(k=='leave_type'){
					 leave_type=v;
					 }
				 if(k=='from_half'){
					 from_half=v;
					 }
				 if(k=='from_date'){
					 from_date=v;
					 }
				 
				 if(k=='to_date'){
					 to_date=v;
					 }
				 if(k=='to_half'){
					 to_half=v;
					 }
				 if(k=='reason'){
					 reason=v;
					 }
				 
				 
			   if(k!='leave_in_middle' && k!='max_combinable' && k!='employee_id' && k!='employee_name' &&  k!='request_id' && k!='from_half' && k!='to_half' && k!='leave_in_preceeding' && k!='leave_in_succeeding'){
			    if(k=='admin_reason'){
				      adminReason=v;
			      }else if(k=='status'){
				   if(v=='RQ'){
					       html += '<td><span title="Requested"  class="label label-primary"><i class="fa fa-clock-o" aria-hidden="true"></i> Requested</span></td>';   
					   }else if(v=='R'){
						   html += '<td><span title="Reason" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="'+adminReason+'"  class="label label-danger"><i class="fa fa-ban" aria-hidden="true"></i> Rejected</span> </td>';   
                             }
						   else if(v=='A'){
							  // html += '<td><span  title="Approved"  class="label label-success">Approved</span> <a href="#" id="'+request_id+'" data-emp="'+employee_id+'" data-lrType="'+leave_type+'" data-from ="'+from_date+'" data-to ="'+to_date+'" data-fromHalf ="'+from_half+'" data-toHalf ="'+to_half+'" data-reason ="'+reason+'" class="view" ><span  title="View"  class="label label-danger"><i class="fa fa-eye" aria-hidden="true"></i></span> </a></td>';   
							   html += '<td><span  title="Approved"  class="label label-success">Approved</span></td>';
						   }else{
							   html += '<td>'+v+'</td>'; 
							   }
				  }else{
			   html += '<td>'+v+'</td>';
			      }
			   }
		  }); 
   	  html += "</tr>";
   	}
   	html+='</tbody></table></section>';
   	
 $('#leaveApplyContent').html(html);
 $('#leaveApplyTable').dataTable({
		 "bInfo":false,
		 "bPaginate": false,
		 "bFilter": false,
 	     "bSort": false         
 });  
 var lrID='<?php echo  isset($_REQUEST['lrID'])?$_REQUEST['lrID']:null ?>';
 if(lrID){
  	  $('#'+lrID).click();
	 	 }
}

/*$(document).on('click', "#leaveApplyTable a.view", function (e) {
    e.preventDefault();
  var lrType=$(this).data('lrtype');
  var empId=$(this).data('emp');
  var rqId=$(this).attr('id');
  $('#leaveType').html(lrType);
  $('#fDate').html($(this).data('from'));
  $('#fhalf').html($(this).data('fromHalf'));
  $('#tDate').html($(this).data('to'));
  $('#thalf').html($(this).data('toHalf'));
  $('#reasonText').html($(this).data('reason'));
  $.ajax({
		 dataType: 'html',
     type: "POST",
     url: "php/leaveaccount.handle.php",
      data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getWeekoff");?>',employee_id:empId,
	         request_id:rqId,leaveRuleId:lrType,flag:0},	
      cache: false,
     beforeSend:function(){
     	 $('#loader').loading(true);
	      },
	    success: function (data) {
				jsonData = JSON.parse(data);
				loadData(jsonData[02][0]);
              $('#empLeaveDetilas').show();
             $('#loader').loading(false);
             $('#raiseLrRq').hide();
             console.log($('#raiseLrRq').hide());
             $('#lrrqSow').html('view').show();
             $('.leaveRuleIdCurrent').val($('#lrSelected :selected').val());
     }	
	});	
    
});
*/
function loadData(dataArray){
	var data="<table id='leaveDetailsTable' class='table table-striped table-hover table-bordered cf dataTable'><thead><tr><th>Dates</th><th>DESC.</th><th>Day type</th><th>Day Count</th></tr></thead><tbody>";
	var numeric="";
	 $.each(dataArray, function (k, v) {
		 if(v!=null){
     if(k!='leave' && k!='lop' && k!='lrPre' && k!='lrSuc' && k!='lrMid' && k!='duration' && k!='maxCom'  ){
	   data+='<tr><td>'+k.split('-')[2]+'/'+k.split('-')[1]+'/'+k.split('-')[0]+'</td><td>'+((v.isWorkingDay!=null)?'Working Day':((v.isWeekend!=null)?'Week End':'Holiday'))+'<td>'+((v.dataType!=null)?v.dataType:'FD')+'</td><td>'+v.dayCount+'</td></tr>';
	 }
     var leaveRuleId=$('#lrSelected :selected').val();
     if(v.lop!=null)
     numeric+= "#"+k+'_'+((v.lop==0)?leaveRuleId:'lop')+'_'+((v.dataType!=null)?v.dataType:'FD')+'_'+((v.dayCount!=null)?v.dayCount:'');
		 }
    });
    $('#lopData').val(numeric);
    $('.leaveRuleIdCurrent').val($('#lrSelected :selected').val());
    data+='</tbody></table>';
    $('#datesShow').html(data);
    $('#LeavePre').html(((dataArray.lrPre=='B')?'Hoilday,Weekend':((dataArray.lrPre=='W')?'Week End':((dataArray.lrPre=='H')?'Hoilday':'None'))));
    $('#LeaveSuc').html(((dataArray.lrSuc=='B')?'Hoilday,Weekend':((dataArray.lrSuc=='W')?'Week End':((dataArray.lrSuc=='H')?'Hoilday':'None'))));
    $('#LeaveMid').html(((dataArray.lrMid=='B')?'Hoilday,Weekend':((dataArray.lrMid=='W')?'Week End':((dataArray.lrMid=='H')?'Hoilday':'None'))));
    $('#lop').html(dataArray.lop);
    $('#leave').html(dataArray.leave);
    }
    
$('#leaveRequestId').click(function(e){
	  $('#lrrqSow').html('').hide();
	   $('#raiseLrRq').show();
	   $('#empLeaveDetilas').hide();
});
     
</script>
</body>
</html>