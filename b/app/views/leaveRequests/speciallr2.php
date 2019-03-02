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
<title>Compensation</title>
<!-- Bootstrap core CSS -->
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"	href="../css/bootstrap-datetimepicker.min.css" />

</head>
<body>
	<section id="container" class="">
		<!--header start-->
         <style>
.imgHeader {
	width: 18%;
	margin-top: -3px;
}

@media only screen and (min-width: 320px) and (max-width:480px ) {
	.imgHeader {
		width: 18%;
		margin-top: -89px;
		margin-left: 7%;
	}
}

a.logo {
	margin-bottom: -25px;
}

.btn-xs {
	font-size: 9px;
	line-height: 2.5;
	border-radius: 3px;
}

h3, .h3 {
	font-size: 17px;
}
}
</style>
<header class="header white-bg">
	<div class="sidebar-toggle-box">
		<div data-original-title="Toggle Navigation" data-placement="right"
			class="fa fa-bars tooltips"></div>
	</div>
	<!--logo start-->
	<a href="index.php" class="logo"> <img alt="VDart Group"
		src="../compDat/CP7210731/logo.jpg" class="imgHeader"></a>
	<!--logo end-->
	<div class="top-nav ">
		<ul class="nav pull-right top-menu">
			<li>
				<button class="btn btn-primary btn-xs" style="margin-top: 8px;"
					type="button">
              Feb-2018-19</button>
			</li>
			<li id="header_inbox_bar" class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">
                                           <i class="fa fa-envelope-o"></i> <span
					class="badge bg-important"></span>
			</a>
				<ul class="dropdown-menu extended inbox">
					<div class="notify-arrow notify-arrow-red"></div>
					<li>
						<p class="red"> No new messages </p>
					</li>
                                         <li><a href="messages.php">See all messages</a></li>
				</ul></li>
                                      <li id="header_notification_bar" class="dropdown"><a
				data-toggle="dropdown" class="dropdown-toggle" href="#"> <i
					class="fa fa-bell-o"></i> <span class="badge bg-warning"></span>

			</a>

				<ul class="dropdown-menu extended notification">
					<div class="notify-arrow notify-arrow-yellow"></div>
					<li>
						<p class="yellow"> No new Notification </p>
					</li>
                                                 <li><a href="notifications.php">See all
							Notifications</a></li>
				</ul></li>
			<!-- inbox dropdown end -->
			<!-- user login dropdown start-->
			<li class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">
                                            
                       <img alt="employee_image"
					src="../compDat/CP7210731/empDat/VDSS8197/VDSS8197.jpg" style="height: 25px;"> <span
					class="username">Naresh Kumar </span> <b
					class="caret"></b>
			</a>
				<ul class="dropdown-menu extended logout">
					<div class="log-arrow-up"></div>
					<li><a href="empProfileView.php"> <i class=" fa fa-user"></i>
							Profile
					</a></li>
					<li><a href="empProfileSetup.php"><i class="fa fa-cog"></i>
							Settings</a></li>
					
					<li><a href="logout.php"><i class="fa fa-key"></i> Log Out</a></li>
				</ul></li>
			<!-- user login dropdown end -->
		</ul>
	</div>
</header>
<script src="../js/jquery-2.1.4.min.js"></script>
<script>
$('.read-notification').click(function(e){
	e.preventDefault();
	
	var notification_id = $(this).data('value');
	var url = $(this).data('action');
	var check = $(this).data('url');
	console.log(check);
	if(check == true){
	$.ajax({
        dataType: 'html',
        type: "POST",
        url: "php/notification.handle.php",
        cache: false,
        data:{act: 'Q1A3MjEwNzMxIXJlYWROb3RpZmljYXRpb24=',notif_id:notification_id},
        success: function (data) {
        data = JSON.parse(data);
        if(data[0] == 'success'){
        	window.location.href = url;
        }else if(data[0] == 'error'){
            
        }	
        },
        error:function(jqXHR, textStatus, errorThrown) {
        	
      },
    });
	}else{
		window.location.href=url;
		
	}
});

/*FOr date Time picker javascript Session Storage*/
	        current_payroll_month='2019-02-01';
	     	localStorage.setItem("current_payroll_month",current_payroll_month );
</script>         <!--header end-->
		<!--sidebar start-->
		<aside>
            <div id="sidebar" class="nav-collapse ">
	<!-- sidebar menu start-->
	<ul class="sidebar-menu" id="nav-accordion">
		<li><a href="index.php"> <i class="fa fa-dashboard"></i> <span>Dashboard</span>
		</a></li>

		<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-gears"></i>
				<span>My Room</span>
		</a>
			<ul class="sub">
				<!-- <li id="employeeProfile"><a href="empProfileView.php">Profile</a></li> -->
				<!-- <li id="holidays"><a href="empHolidays.php">Calenders</a></li> -->
				<!-- <li id="roomPerqs"><a  href="roomPerqs.php">Perqs</a></li> -->
				<li id="attendance"><a href="../b/redirect/page/?url=attendance">Attendance</a></li>
				<li id="taxPlanner"><a href="taxPlanner.php">Tax Planner</a></li>
				<li id="attendanceRegularization"><a href="../b/redirect/page/?url=attendance/regularization">Regularization</a></li>
									<li id="leaveManagement"><a href="../b/redirect/page/?url=leaveRequests">Leave Requests</a></li>
								<li id="compensation">
				<a href="compensation.php">Compensation</a></li>				<li id="noticePeriod"><a href="noticePeriod.php">Raise a Notice Period</a></li>
				
			</ul></li>
			
		<!--
		echo '<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-sign-out"></i>
				<span>LMS</span>
		</a>

			<ul class="sub">
			 <!-- <li id="leaveManagement">
		<a href="'.$features['leaveManagement']['displayUrl'].'">Leave Accounts</a></li> 
		<li id="leaveManagement"><a href="../b/redirect/page/?url=leaveRequests">Leave Requests</a></li>

			</ul></li>';-->
                          
		
		<!-- <li class="sub-menu"><a href="javascript:;"> <i class="fa fa-calendar"></i>
				<span>Transactions</span>
		</a>
			<ul class="sub"> -->
			<!-- <li id="claim">
		<a href="">Claims</a></li>-->
		
		<!--<li id="assetRequest">
		<a href="">Asset Request</a></li>-->
				
			
				<!--  <li id="leaves"><a  href="leave.php">Leaves</a></li>
                          <li id="attendence"><a  href="attendence.php">Attendence</a></li> -->
				<!-- <li id="taxPlanner"><a href="taxPlanner.php">Tax planner</a></li>
			</ul></li> -->
		<li class="sub-menu"><a href="../b/redirect/page/?url=myteam//"> <i class="fa fa-users"></i>
				<span>My Team</span></a></li>
				
				<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-sitemap"></i>
				<span>Reports</span>
		</a>
			<ul class="sub">
				<li id="paySlip"><a href="paySlips.php">Pay Slips</a></li>
				<!--  <li id="pfBalance"><a href="pfBalance.php">PF-Balance</a></li>
                          <li id="loanSummary"><a href="loanSummary.php">Loan summary</a></li>
                          <li id="reportPerqs"><a href="perqsSummary.php">Perqs summary</a></li>
                          <li id="leaveSummary"><a href="leaveSummary.php">Leave Summary</a></li> -->
				<li id="annualSalary"><a href="annualSalaryStat.php">Annual Salary
						Statement</a></li>
				<!--  <li id="itPapers"><a href="itPapers.php">IT Papers</a></li> -->
			</ul></li>
		<!--
		<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-suitcase"></i>
				<span>Utilities</span>
		</a>
			<ul class="sub">

			
		<li id="message">
		<a href="">Messages</a></li>		<li id="notification"><a href="notifications.php">Notifications</a></li>
		
		<li id="project">
		<a href="">Project</a></li>		
		<li id="timesheet">
		<a href="">Timesheet</a></li>		
		</ul></li>
		-->
		 
		
		
	</ul>
	<!-- sidebar menu end-->
</div>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel" id="shiftsectionHide">
					<header class="panel-heading">
						Compensation Request
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn  btn-sm btn-info">
								<i class="fa fa-plus"></i> Add Request
							</button>
						</div>
					</header>

					<div class="panel-body">
							<form class="form-horizontal displayHide" role="form" method="post" id="comoffForm">
									<input type="hidden" name="act" value="Q1A3MjEwNzMxIWluc2VydA==" />
						    <div class="col-lg-6">
						    		<div class="form-group">
										<label class="col-lg-5 control-label">Date</label>
										<div class="col-lg-7 input-group bootstrap-timepicker">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control  timepicker-default"
												name="date" id="dateOfcompoff"/>
												<label  id="comOffError"></label>
                                        </div>
									</div>
									<div class="form-group">
                                  <label class="col-lg-5 control-label"> For working on</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" name="workingFor"  maxlength="50" id="workingFor"/>
											<span id="reasonCode"></span>
										</div>
									</div>
							 <button type="button" class="btn btn-sm btn-danger pull-right" id="cancelShift" style="margin-left:1%">Cancel</button>
                             <button type="submit" class="btn btn-sm btn-success pull-right" id="comtAdd">Request</button>
                           
						 		</div>
                         
							
															</form>
															</div>
															<div class="panel-body">
															<div id="loader" style="width:97%;height:100%"></div>
															<footer class="weather-category displayHide">
                              <ul>
                               <li>
                                      <h5>Not Processed</h5>
                                     <p id="notProcessed">0</p>
                                  </li>
                                   <li>   <h5>Processed</h5>
                                    <p id="processed">0</p>
                                  </li>
                                  <li class="active">
                                      <h5>Elapsed</h5>
                                    <p id="elasped">0</p>
                                  </li>
                                
                              </ul>
                          </footer>
					<div id="tableHide"  class="adv-table editable-table">
							
						</div>

						</div>
						</section>
			<!-- page end-->
			</section>
		</section>

	<!--main content end-->
		<!--footer start-->
         <footer class="site-footer">
	<div class="text-center">
		2016 &copy; PayRoll by <a href="http://hrlabz.in" style="color: #fff">HRLabz</a>
		<a href="#" class="go-top"> <i class="fa fa-angle-up"></i>
		</a>
	</div>
</footer>         <!--footer end-->
	</section>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
    <script class="include" type="text/javascript" src="../js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="../js/respond.min.js"></script>
    <!--Time Picker-->
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
      <script src="../js/common-scripts.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
     $(document).ready(function () {
                  $('#dateOfcompoff').datetimepicker({
                	  format: 'DD/MM/YYYY',
                  });
                  var daySearch='';
                  if(daySearch!='All' && daySearch!='' && daySearch.indexOf("/")>0)
                  selectcompensationWithDate(daySearch);
                  else if(daySearch=='All' && daySearch!='')
                  selectcompensation();
                  else if( daySearch!='' && daySearch.indexOf("/")<0)
                  $('#tableHide').html('You are not working').css('text-align','center'); 
                  else if(daySearch==''){
                  selectcompensation();
                  } 
   });

   //update
     $(document).on('blur', "#dateOfcompoff", function (e) {
         e.preventDefault();
         if($('#dateOfcompoff').val()!=''){
         $.ajax({
             dataType: 'html',
             type: "POST",
             url: "php/compensation.handle.php",
             cache: false,
             data: { act: 'Q1A3MjEwNzMxIWdldEhvbGlkYXk=', date :$('#dateOfcompoff').val()},
             beforeSend:function(){
            	 $('#dateOfcompoff').addClass('spinner');
               },
             success: function (data) {
                 jsonData = JSON.parse(data);
                 $('#reasonCode').html('');
                 if (jsonData[0] == "success") {
                	  if(jsonData[2][0].holiday!=null && jsonData[2][0].isApplied==1){
                		  $('#comOffError').html('You have already applied for the day.');
                         	$('#comtAdd').prop('readonly', true);
                          $('#workingFor').val('').prop('readonly', false);
                    	  }else if(jsonData[2][0].holiday!=null && jsonData[2][0].isApplied==0){
                    		  $('#workingFor').val(jsonData[2][0].holiday).prop('readonly', true);
                              $('#comOffError').html('');
                               $('#comtAdd').prop('readonly', false);
                        	  }else{
                        		  $('#comOffError').html('');
                                  $('#reasonCode').html('Enter a Reason eg: Overtime (8 PM to 3AM)');
                                  $('#workingFor').val('').prop('readonly', false);
                                	 $('#editLoader').loading(true);
                            	  }
                 }
                 else
                     if (jsonData[0] == "error") {
                     $('#comOffError').html('');
                     $('#reasonCode').html('Enter a Reason eg: Overtime (8 PM to 3AM)');
                     $('#workingFor').val('').prop('readonly', false);
                   	 $('#editLoader').loading(true);
                     }
                 $('#dateOfcompoff').removeClass('spinner');
             }
    });
         }
     });

     

$(document).on('submit', "#comoffForm", function (e) {
    e.preventDefault();
    if($('#workingFor').val()!='' && $('#dateOfcompoff').val()!='') {        
    	//$('#error').html('');
        $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/compensation.handle.php",
            cache: false,
            data: $('#comoffForm').serialize(),
            beforeSend:function(){
             	$('#comtAdd').button('loading'); 
              },
           success: function (data) {
                jsonData = JSON.parse(data);
                if (jsonData[0] == "success") {
                	 jQuery('#comoffForm').toggle('hide');
                	 $('#workingFor').val('').prop('readonly', false);
                     $('#comoffForm')[0].reset();
                     var d=jsonData[02].shift();
                     $('#elasped').html(((d.Elapsed!=null)?d.Elapsed:0));
                     $('#processed').html(((d.Processed!=null)?d.Processed:0));
                     $('#notProcessed').html(((d.NotProcessed!=null)?d.NotProcessed:0));
                     compoffTablecreate(jsonData[2])
                     BootstrapDialog.alert(jsonData[1]);
                 }
                else
                    if (jsonData[0] == "error") {
                  	  BootstrapDialog.alert(jsonData[1]);
                    }
            	$('#comtAdd').button('reset'); 
            }

   });
    }
});

function selectcompensation(){
	 $.ajax({
        dataType: 'html',
        type: "POST",
        url: "php/compensation.handle.php",
        cache: false,
        data: { act: 'Q1A3MjEwNzMxIXNlbGVjdA=='},
        beforeSend: function () {
            $('#loader').loading(true);
        },
       success: function (data) {
            jsonData = JSON.parse(data);
            if(jsonData[0]=='success'){
            
           if(jsonData[02].length>0){
        		var d=jsonData[02].shift();
        		 $('#elasped').html(((d.Elapsed!=null)?d.Elapsed:0));
                 $('#processed').html(((d.Processed!=null)?d.Processed:0));
                 $('#notProcessed').html(((d.NotProcessed!=null)?d.NotProcessed:0));
            	compoffTablecreate(jsonData[02]);
                
            }
            }
            $('#loader').loading(false);
         }
      });	
}

function compoffTablecreate(data){
	$('#tableHide').html('');
	var html = '<section id="flip-scroll"><table class="table table-striped  table-hover  cf dataTable" id="shift-sample"><thead><tr><th>Day</th><th>For Working </th><th>Status</th></tr></thead><tbody>';
	   for (var i = 0, len = data.length; i < len; ++i) {
			html += '<tr>';
			ftId='';
		  $.each(data[i], function (k, v) {
			  if(k=='admin_reason'){
				  admin_reason=v;
				  }
			  if(k=='is_processed'){
				  is_processed=v;
				  }
			      if(k!='employee_id'  && k!='compoff_id'  && k!= 'admin_reason'  && k!='day_count' && k!='is_processed' && k!='employee_name'){
                   if(k=='status'){
						if(v=='RQ')
					html+='<td><span title="Requested" class="label label-primary"><i class="fa fa-clock-o" aria-hidden="true"></i> Requested</span></td>';
					else if (v=='R')
					html += '<td><span  title="Reason" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="'+admin_reason+'"   class="label label-danger"><i class="fa fa-ban" aria-hidden="true"></i> Rejected</span> </td>';
					else 
				    html += '<td><span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> '+v+' </span> '+((is_processed!=null && is_processed=='-1')?'&nbsp;<span class="label label-danger">Elasped</span>':((is_processed!=null && is_processed=='1')?'&nbsp;<span class="label label-info">Processed</span>':''))+'</td>';
					}else{
						html+='<td>'+v+'</td>';
					}
      			 }  
		  }); 
  	  html += "</tr>";
  	}
  	html+='</tbody></table></section>';
  	$('.weather-category').show();
  	$('#tableHide').html(html);
  	$('[data-toggle="popover"]').popover();
  	}
$(document).on('click', "#showhide", function (e) {
    e.preventDefault();
   $("#comoffForm").toggle('show');
});

$(document).on('click', "#cancelShift", function (e) {
    e.preventDefault();
    $('#comoffForm')[0].reset();
    jQuery('#comoffForm').toggle('hide');
});

function selectcompensationWithDate(date){
	 $.ajax({
      dataType: 'html',
      type: "POST",
      url: "php/compensation.handle.php",
      cache: false,
      data: { act: 'Q1A3MjEwNzMxIXNlYXJjaA==',date:date},
      beforeSend: function () {
          $('#loader').loading(true);
        },
     success: function (data) {
          jsonData = JSON.parse(data);
          if(jsonData[02].length>0){
            var d=jsonData[02].shift();
            $('#elasped').html(((d.Elapsed!=null)?d.Elapsed:0));
            $('#processed').html(((d.Processed!=null)?d.Processed:0));
            $('#notProcessed').html(((d.NotProcessed!=null)?d.NotProcessed:0));
            compoffTablecreate(jsonData[02]);
          	}
          else{
              $('#tableHide').html('You are not working').css('text-align','center');} 

          $('#loader').loading(false);
       }
    });	
}


</script>
</body>
</html>
