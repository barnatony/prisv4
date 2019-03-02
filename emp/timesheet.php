<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="Claim Rules">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Timesheet</title>
      <!-- Bootstrap core CSS -->
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../css/bootstrap-reset.css" rel="stylesheet">
      <!--external css-->
      <link href="../css/font-awesome.min.css" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="../css/style.css" rel="stylesheet">
      <link href="../css/style-responsive.css" rel="stylesheet" />
      <link rel="stylesheet" href="../css/DT_bootstrap.css" />
      <link href="../css/TableTools.css" rel="stylesheet"/>
      <link href="../css/table-responsive.css" rel="stylesheet"/>
      <link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet"/>
      <!-- Custom styles for this template -->
       <link rel="stylesheet" type="text/css" href="../css/bootstrap-datetimepicker.min.css" />
      <link href="../css/chosen.css" rel="stylesheet">
      <link href="../css/chosen-bootstrap.css" rel="stylesheet">
 <style>
   .slider-info{
   padding-top:0px;
   }.tearsheet--big {
    display: inline-block;
    text-align: center;
    text-decoration: none;
    margin-bottom: 18px;
    font-weight: 700;
     width:170px;
}.tearsheet .month, .tearsheet--big .month, .tearsheet--small .month {
    text-transform: uppercase;
    background-color: #039BE5;
    color: #fff;
    display: block;
   padding:11px;
}.tearsheet .days, .tearsheet--big .days, .tearsheet--small .days {
    display: block;
    color: #555!important;
    background-color: #fff;
    border-top-width: 0;
     padding:11px;
     font-size:23px;
}.tearsheet .day_expand, .tearsheet--big .day_expand, .tearsheet--small .day_expand {
    display: block;
    color: #555!important;
    background-color: #fff;
    border-top-width: 0;
     padding:11px;
}
 tr td{
background:#fff;
color:#333;
height:0px!important;
line-height:0px!important;
width:0px!important;
	;
}.table-condensed tr td a{
padding:0px!important;

}.table-condensed tr td span{
line-height:20px!important;
height:30px!important;

}.top_time{
padding-top:54px;
padding-left:27px;
}.hour,.minute{
padding:20px!Important;
}.day{
padding:6px!Important;
    height: 20px!Important;
    line-height: 20px!Important;
    width: 20px!Important;
}.days1{
display: block;
    color: #555!important;
    background-color: #fff;
    border-top-width: 0;
     padding:11px;
     
     }.progress{
     overflow:initial!important;
     }.adv-table table tr td{
     padding:18px;
     }.progress-bar-blue{
background-color:#29B6F6;
}.progress-bar-red{
background-color:#FFD54F;
}.progress-bar-pink{
background-color:#B0BEC5;
}.progress-bar-gray{
background-color:#AED581;
}.progress-bar-cyon{
background-color:#FFAB91;
}.nav-pills>li.active>a,.nav-pills>li.active>a:hover,.nav-pills>li.active>a:focus{
color: #49bae4;
    background-color: #f5f5f5;
    border-bottom: 2px solid #49BAE4 !important;
    border-radius: 0;
    }
    .nav-pills>li>a{
     background-color: #f5f5f5;
    }
    .nav>li>a:hover, .nav>li>a:focus{
        background-color: #eee;
        }#panel_body_well{
        border:0px!important;
        border-radius:0px!important;
        }.ajax_loader,.ajax_loaders
{
display:block;
margin:0px auto;
}#panel_body_well{
margin-bottom:20px;
}
  </style>
  </head>

  <body>
<section id="container" class="">
      <!--header start-->
     <?php include("header.php");
        ?>
      <!--header end-->
      <!--sidebar start-->
      <aside>
            <?php 
            include_once("sideMenu.php");
           
            ?>
         </aside>
      <!--sidebar end-->
      <!--main content start-->
     <section id="main-content">
          <section class="wrapper site-min-height">
     <section class="panel">
     	                            
                               <header class="panel-heading tab-bg-dark-navy-blue">
                                      <ul class="nav nav-tabs " id="time_tabs" role="tablist">
                                     
                                      <li id="header_req_active">
                                              <a href="#entry" id="entry_tab" data-toggle="tab" data-loaded=false data-title = "entry_tab">
                                                  Entry
                                              </a>
                                          </li>
                                      <li class="">
                                              <a href="#view"  id="view_tab" data-toggle="tab" data-loaded=false data-title = "view_tab">
                                                  View
                                              </a>
                                          </li>
                                         
                                          
                                         
                                      </ul>
                                  </header>
                                  
                                  <?php      
      require_once(LIBRARY_PATH . "/timesheet.class.php");      
      
      $timesheet = new Timesheet();
      $timesheet->conn = $conn;
      $timesheetOnly = $timesheet->getTask($_SESSION['employee_id']);
     
      ?>
                                   <div class="tab-content tasi-tab">
                                          <div class="tab-pane" id="view">
                                         
                                          <div class="panel-body">
                                           <div class="panel-body well">
                                          <form  role="form"  method="POST" class="form-horizontal" enctype="multipart/form-data" id="view_table_form">
                                          <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!getDataByDate");?>">
                                         
                                         <div class="col-md-8">
                                         <div class="form-group">
                                          <div class="col-md-2" style="padding-left:0px">
		                                      <label class="col-lg-5 control-label">Status</label>
		                                      </div>
		                                      <div class="col-lg-9" style="padding-left:0px">
		                                      <label  class="col-lg-3 control-label" >
                                        <input type="checkbox" name="status[]" id="status" class="status" checked value="saved" >Saved
                                        </label>
                                         <label  class="col-lg-3 control-label">
                                        <input type="checkbox" name="status[]" id="status" class="status" value="approved">Approved
                                         </label><label  class="col-lg-3 control-label">
                                        <input type="checkbox" name="status[]" id="status" class="status" value="submitted">Submitted
                                         </label><label  class="col-lg-3 control-label">
                                        <input type="checkbox" name="status[]" id="status" class="status" value="disputed">Disputed
                                        </label>
		                                      </div>
		                                      </div>
                                         </div>
                                 
                                        
                                         <div class="col-md-5">
                                          <div class="form-group">
                                       <label for="dname" class="col-lg-3 col-sm-1 control-label">From Date</label>
                                        <div class="col-lg-9">
                                       <input type="text" id="from_dates" name="from_date" class="form-control">
                                       
                                        </div>
                                        </div>
                                        </div>
                                         <div class="col-md-5">
                                          <div class="form-group">
                                       <label for="dname" class="col-lg-3 col-sm-3 control-label">To Date</label>
                                        <div class="col-lg-9">
                                        <input type="text" id="to_dates" name="to_date" class="form-control">
                                        </div>
                                        </div>
                                        </div>
                                       <div class="col-lg-2">
                                       <button type="submit" class="btn btn-success" id="submit">View</button>
                                       </div>
                                       
                                        
                                          </form>
                                          </div>
                                          
                                          <div class="clearfix"></div>
                                           <div class="panel-body">
                                             <img src="../img/ajax-loader.gif" class="ajax_loaders" style="display:none">
                                          <div class="text-center" id="error_table"></div>
                                           <div class="panel-body" id="timesheet_details_submit_form" style="display: none">
                     <div class="pull-right">
                     <button type="button" class="btn btn-success btn-xs" id="submit_now">Submit</button>
                     <button type="button" class="btn btn-danger btn-xs" id="delete_now">Delete</button>
                     </div>
                          <div class="adv-table editable-table">
                          	<section id="flip-scroll">
		 <table class="table table-striped table-hover  cf" id="timesheet_details_submit_table">
                           <thead >
                              <tr>
                                 <th style="padding:20px;"><input type="checkbox" class="mail-checkbox mail-group-checkbox"></th>
                                 <th>Employee Name</th>
                                 <th>Date</th>
                                 <th>From Time</th>
                                 <th>To Time</th>
                                 <th>Status</th>
                                 <th>Hours</th>
                                 <th>Comments</th>
                                  </tr>
                           </thead>
                           <tbody class="timesheet_details_show">
                           </tbody>
                        </table>
                        </section>
                       </div>
                          </div>
                                          
                                          </div>
                                          </div>
                                          </div>
                                          <div class="tab-pane" id="entry">
                                         
                                          <div class="panel-body">
                                          <form  role="form"  method="POST" class="form-horizontal" enctype="multipart/form-data" id="timesheet_ins">
                                           <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!insertTimesheet");?>">
                                          	<input type="hidden" name="hours" id="hours" value="">
                                          
                                          <div class="col-md-12 well panel-body">
                                          	<div id="loader"  style="width:96%;height:76%;"></div>
                                          <div class="col-md-2">
                                        
                                          <div class="form-group">
                                            
                                      <div class="col-lg-9" >
                                          <input type="hidden" name="from_datetime" id="from_datetime" class="form_datetime form-control">
                                          <input type="hidden" name="from_datetimes" id="from_datetimes" class="from_datetimes form-control">
                                      <div class="col-md-12" id="cenhj">
								<time class="tearsheet--big" >
								<span class="month"></span>
								<span class="days"></span>
								<span class="day_expand"></span>
								</time>
								</div>
								<div class="col-md-12" style="width:350%;">
								 <div class="progress_error"></div>
								</div>
                                      </div>
                                  </div>
                                          </div>
                                          
                                          <div class="col-md-8">
                                          <div class="col-md-6">
                                          <div class="form-group">
                                          <label class="col-lg-4 top_time" >From Time</label>
                                          <div class="col-lg-8">
                                          
                                            <input type="hidden" name="from_date" id="from_date" class=" form-control">
                                              </div>
                                              </div>
                                              </div>
                                              <div class="col-md-6">
                                               <div class="form-group">
                                          <label class="col-lg-4 top_time">To Time</label>
                                          <div class="col-lg-8">
                                          <input type="hidden"  name="to_date" id="to_date" class=" form-control">
                                          
                                              </div>
                                              </div>
                                              </div>
                                              <div class="clearfix"></div>
                                              
                                              </div>
                                              <div class="col-md-2" style="padding-top:33px;">
                                              <span class="days1"></span>
                                              </div>
                                             
                                              
                                              </div>
  
                                              <div class="progress" style="display:none;">
  
</div><br><br>
                                              <div class="clearfix"></div>
                                              
                                             
                                              <ul class="nav nav-pills " >
                                          <li class="active" id="eng_li">
                                              <a id="engineering_tab" style="cursor: pointer" >
                                                  Tasks
                                              </a>
                                          </li>
                                          <li class="" id="corp_li">
                                              <a  id="corporate_tab" style="cursor: pointer">
                                                  Corporate
                                              </a>
                                          </li>
                                         <li class="" id="tim_li">
                                              <a  id="time_off_tab" style="cursor: pointer">
                                                  Time Off
                                              </a>
                                          </li>
                                      </ul>
                                       <div class="panel-body well" id="panel_body_well" >
                                       <div class="loadings" id="loader" style="width:77%;height:22%;"></div>
                                       <div class="col-lg-6">
                                          <div  id="Engineering">
                                          <div class="form-group">
                                          <label class="col-lg-2"> Task
                                          </label>
                                          <div class="col-lg-10">
                                          <select name="task" id="task" class="form-control">
                                          <option value="no">Select Task</option>
                                           <?php 
                                      if($timesheetOnly){
                                       foreach ($timesheetOnly as $row){
                                      echo "<option data-id='".$row['project_id']."' value='".$row['task_id']."'>".$row['task_name']." [ ".$row['task_id']." ]<br>"."</option>"; 
                                       }
                                      }else{
                                      	echo "<option value='training'>Training</option>";
                                      }
                                       ?>
                                          </select>
                                          </div>
                                          </div>
                                            <div class="form-group">
                                          <label class="col-lg-2"> Project
                                          </label>
                                          <div class="col-lg-10">
                                          <select name="project" id="project_id" class="form-control">
                                          <option value="no">Select Project</option>
                                         
                                          </select>
                                          
                                          
                                          </div>
                                          <div class="clearfix"></div>
                                          <div class="col-md-12" id="show_all_details" style="display: none;margin-top:10px;">
                                          <div class="form-group">
                                          <label class="col-lg-3"> Start Date :</label>
                                          <div class="col-lg-9">
                                          <span id="start_dates"></span>
                                          </div>
                                          </div>
                                          <div class="form-group">
                                          <label class="col-lg-3"> End Date :</label>
                                          <div class="col-lg-9">
                                          <span id="end_dates"></span>
                                          </div>
                                          </div>
                                          <div class="form-group">
                                          <label class="col-lg-3"> Hours :</label>
                                          <div class="col-lg-9">
                                          <span id="hours_en"></span>
                                          </div>
                                          </div>
                                          </div>
                                          </div>
                                          </div>
                                          <div id="corporate" style="display: none">
                                          <div class="form-group">
                                          <label class="col-lg-2"> Task
                                          </label>
                                          <div class="col-lg-10">
                                          <select name="corporate_task" id="corporate_task" class="form-control">
                                          <option value="no_cop">Select Corporate Task</option>
                                          <option value="meeting">Meeting</option>
                                          <option value="seminar">Seminar</option>
                                          
                                          </select>
                                          </div>
                                          </div>
                                          </div>
                                          <div id="time_off" style="display: none">
                                          <div class="form-group">
                                          <label class="col-lg-2"> Type
                                          </label>
                                          <div class="col-lg-10">
                                          <select name="Type" id="Type" class="form-control">
                                          <option value="no_per">Select Type</option>
                                          <option value="permission">Permission</option>
                                          </select>
                                          </div>
                                          </div>
                                          </div>
                                          </div>
                                       <div class="col-lg-6">
                                       <div class="form-group">
                                       <label class="col-lg-2 col-sm-2">Comments</label>
                                        <div class="col-lg-10">
                                        <textarea class="form-control" rows="4" id="comments" name="comments" ></textarea>
                                        </div>
                                       </div>
                                       
                                       </div>
                                       <div class="col-md-12">
                                       <button type="submit" id="save_task" class="btn btn-success pull-right" style="margin-left: 10px;">Save</button>
                                       <button type="button" class="btn btn-danger pull-right" >Cancel</button>
                                         </div>
                                       </div>
                                       
                                       
                                              </form>
                                          </div>
                                          </div>
                                          </div>
                                
                  </section>
                  </section>
                  </section>   
                  </section>
                             
<!-- js placed at the end of the document so the pages load faster -->
      <script src="../js/jquery-2.1.4.min.js"></script>
      <script src="../js/jquery-ui.js"></script>
      <script src="../js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
      <script class="include" type="text/javascript" src="../js/jquery.dcjqaccordion.2.7.js"></script>
      <script src="../js/jquery.scrollTo.min.js"></script>
      <script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
      <script src="../js/respond.min.js" ></script>
       <script type="text/javascript" src="../js/jquery.dataTables.js"></script>
       <script type="text/javascript" src="../js/DT_bootstrap.js"></script>
       <script src="../js/ZeroClipboard.js"></script>
       <script src="../js/TableTools.js"></script>
       <script src="../js/bootstrap-datetimepicker.min.js"></script>
     <script src="../js/chosen.jquery.min.js"></script>
       <!--common script for all pages-->
     <script src="../js/common-scripts.js"></script>
     <script src="../js/bootstrap-dialog.js"></script>
     <script src="../js/jquery.knob.js"></script>
      <!-- END JAVASCRIPTS -->
      <script type="text/javascript">
      var monthNames = ["January", "February", "March", "April", "May", "June",
	                      "July", "August", "September", "October", "November", "December"
	                    ]; 	    
	    var weekday = new Array(7);
	    weekday[0] = "Sunday";
	    weekday[1] = "Monday";
	    weekday[2] = "Tuesday";
	    weekday[3] = "Wednesday";
	    weekday[4] = "Thursday";
	    weekday[5] = "Friday";
	    weekday[6] = "Saturday";
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
    	      $('#time_tabs a[href="#' + tab + '"]').tab('show');
    	      
    	  	
    	  }else{
    		  $('#time_tabs a[href="#entry"]').tab('show');
    		  
    		  } 

    	  // Change hash for page-reload
    	  $('#time_tabs a').on('shown.bs.tab', function (e) {
    	      window.location.hash = e.target.hash;
    	  })
    	});


      $('#time_tabs').on('shown.bs.tab', function (e) {
    	   // newly activated tab
    	    window.scrollTo(0, 0);
    	    $('.form_datetime,#from_dates,#to_dates').datetimepicker({
    	        format: 'DD/MM/YYYY'  ,
    	        maxDate: new Date,     
    	    });    
    	    $('#from_date,#to_date').datetimepicker({
    	    	 inline: true,
    	    	 sideBySide: true,
    	    	 format: 'LT',
    	    	
    	    });  
    	            
    	    var fullDate = new Date();
    	    var twoDigitMonth = monthNames[fullDate.getMonth()];
    	    var twoDigitDate = fullDate.getDate();
    	 	    $('.month').html(twoDigitMonth+', '+fullDate.getFullYear());
    	 	    $('.days').html(twoDigitDate);
    	 	    $('.day_expand').html(weekday[fullDate.getDay()]);
    	  if($(e.target).data('loaded') === false){
			if($(e.target).data('title') === 'entry_tab'){
				var d = new Date();
				var strDate =   d.getDate()+ "/" + (d.getMonth()+1) + "/"  + d.getFullYear() ;
				$('.form_datetime').data("DateTimePicker").date(strDate);
				$('#from_datetimes').val(strDate);
				//getTimesheetData(strDate);
				var fromHrs  = $('#from_date').val();
		 		var toHrs = $('#to_date').val();
		 		var timeStart = new Date("01/01/2007 " + fromHrs);
		 		var timeEnd = new Date("01/01/2007 " + toHrs);
		 		var hoursform = timeStart.getHours()+':'+timeStart.getMinutes();
		 		var minform = timeEnd.getHours()+':'+timeEnd.getMinutes();
		 	   $('#from_date').val(hoursform);
		 	   $('#to_date').val(minform);
			}
			//make the tab loaded true to prevent re-loading while clicking.
      		$(e.target).data('loaded',true);
    		}
    	});
      
    
 	 $('#from_date,#to_date').on('dp.change',function(e){
 	 	 
 		var fromHrs  = $('#from_date').val();
 		var toHrs = $('#to_date').val();
 		
 		var timeStart = new Date("01/01/2007 " + fromHrs);
 		var timeEnd = new Date("01/01/2007 " + toHrs);
 		var hoursform = timeStart.getHours()+':'+timeStart.getMinutes();
 		var minform = timeEnd.getHours()+':'+timeEnd.getMinutes();
 	   $('#from_date').val(hoursform);
 	   $('#to_date').val(minform);
		if(timeStart > timeEnd){
			var flag = 1;
			$('.days1').html('Start Time Must Be Greater Than End Time');
		}else{
			var flag = 0;
 		var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds
 		var minutes = diff % 60;
 		var hours = (diff - minutes) / 60;
 		$('.days1').html(hours+' Hrs '+ minutes+ ' Min');
 		var hou = '';
 		var min = '';
if(hours < 10){
hou = '0';
}if(minutes < 10){
min = '0';
}
$('#hours').val(hou+hours+':'+min+minutes+':'+'00');
		}
 	
 	 });
 	

      $('.tearsheet--big').click(function(e){
    	  $('.form_datetime').data("DateTimePicker").toggle();
      });
    	  $('.form_datetime').on('dp.change',function(e){
    		  var fullDate = new Date($('.form_datetime').data("DateTimePicker").date());
      	    var twoDigitMonth = monthNames[fullDate.getMonth()];
      	    var twoDigitDate = fullDate.getDate();
      	 	    $('.month').html(twoDigitMonth+', '+fullDate.getFullYear());
      	 	    $('.days').html(twoDigitDate);
      	 	    $('.day_expand').html(weekday[fullDate.getDay()]);

      	 	 var d = fullDate;
      	 	 var strDate =   d.getDate()+ "/" + (d.getMonth()+1) + "/"  + d.getFullYear() ;
      	 	$('#from_datetimes').val(strDate);
				getTimesheetData(strDate);
    	  });
    	
    
      
      $('#engineering_tab').click(function(e){
          $('#Engineering').show();
          $('#corporate').hide();
          $('#time_off').hide();
          $('#eng_li').addClass('active');
          $('#corp_li,#tim_li').removeClass('active');
          
      });
      $('#corporate_tab').click(function(e){
          $(this).closest('li').addClass('active');
          $('#Engineering').hide();
          $('#corporate').show();
          $('#time_off').hide();
          $('#corp_li').addClass('active');
          $('#eng_li,#tim_li').removeClass('active');
      });
      $('#time_off_tab').click(function(e){
    	  $(this).closest('li').addClass('active');
          $('#Engineering').hide();
          $('#corporate').hide();
          $('#time_off').show();
          $('#tim_li').addClass('active');
          $('#corp_li,#eng_li').removeClass('active');
      });
      $('#task').on('change',function(e){
		var data = $(this).val();
		if($('#task').val() == 'no'){

		}else{
			$.ajax({
				dataType: 'html',
				type: "POST",
			    url: "php/timesheet.handle.php",
			    cache: false,
			    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getProject");?>',projectId:$('#task').val()},	
			    success: function (data) {task
			        data = JSON.parse(data);
			        if(data[0] == "success"){
			var html = "";
			$('#show_all_details').show();
		    for(var i=0;i<data[2].length;i++){
		        html += '<option value="'+data[2][i].projectId+'">'+data[2][i].project_title+' ['+data[2][i].projectId+']</option>';
$('#start_dates').html(data[2][i].start_date);
$('#end_dates').html(data[2][i].end_date);
$('#hours_en').html(data[2][i].hours);
				    }
		   
		    $('#project_id').html(html);
			        }else if(data[0] == "error"){
			        	$('#show_all_details').hide();
			        	$('#project_id').html('<option value="Training">Training</option>');
			        }
			    }
			});
		}
      });
      function tConvert (time) {
    	  // Check correct time format and split into components
    	  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

    	  if (time.length > 1) { // If time format correct
    	    time = time.slice (1);  // Remove full string match value
    	    
    	    time[4] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
    	    time[0] = +time[0] % 12 || 12; // Adjust hours
    	  }
    	  
    	  
    	  return time.join (''); // return adjusted time or original string
    	}

      
function getTimesheetData(dates){

	$('#loader').loading(true);
	$.ajax({
		dataType: 'html',
		type: "POST",
	    url: "php/timesheet.handle.php",
	    cache: false,
	    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getTimesheet");?>',date:dates},	
	    success: function (data) {
	        data = JSON.parse(data);
	        $('.progress').show();
	        
	        $('#loader').loading(false);
	        if(data[0] == "success"){
		        var html = "";
		var myArray = ['success','danger','info','warning','blue','red','pink','gray','cyon'];        
	for(var i=0;i<data[2].length;i++){
		var rand = myArray[Math.floor(Math.random() * myArray.length)];
		 var d = new Date("1970-1-1 " +data[2][i].hours);
	     var n = d.getHours();
	     var s = d.getMinutes();
			var hours = n;
			if(hours == 0){
			hours = 0.5;
			}
			var width = (hours/24)*100;
			var fromtimeAm = tConvert(data[2][i].from_time); 
			var totimeAm = tConvert(data[2][i].to_time);

			
			html += '<div class="progress-bar progress-bar-'+rand+' get_data" title="'+hours+' Hr :'+s+' Min'+' " data-from="'+data[2][i].from_time+'" data-to="'+data[2][i].to_time+'" role="progressbar" style="width:'+width+'%;cursor:pointer;">'+fromtimeAm+' To '+totimeAm+'</div>'; 
 	}
	       $('.progress').html(html);
	        }else if(data[0] == "error"){
	        	
	        	$('.progress').html('');
	        }
	    }
	});
}

$('#timesheet_ins').on('submit',function(e){
e.preventDefault();

var Dates = $('#from_datetime').val();
var timeStart = new Date("01/01/2007 " + $('#from_date').val());
var timeEnd = new Date("01/01/2007 " + $('#to_date').val());
if(timeStart > timeEnd){
	$('.progress_error').html('<b>Start Time Must Be Greater Than End Time</b>');	
}else if($('#task').val() == 'no'){
	$('.progress_error').html('<b>Task Field is Required</b>');
}else if($('#comments').val() == ''){
	$('.progress_error').html('<b>Comment Field is Required</b>');
}else{
$.ajax({
	 	processData: false,
  contentType: false,
  type: "POST",
  url: "php/timesheet.handle.php",
  cache: false,
  data:new FormData(this),
  beforeSend:function(){
   	$('#save_task').button('loading'); 
    },
    complete:function(){
  	$('#save_task').button('reset');
    },	
success: function (data) {
data = JSON.parse(data);
if(data[0] == "success"){
	$('.progress_error').html('');
	var fromHrs  = $('#from_date').val();
		var toHrs = $('#to_date').val();
		var timeStart = new Date("01/01/2007 " + fromHrs);
		var timeEnd = new Date("01/01/2007 " + toHrs);
		var hoursform = timeStart.getHours()+':'+timeStart.getMinutes();
		var minform = timeEnd.getHours()+':'+timeEnd.getMinutes();
	   $('#from_date').val(hoursform);
	   $('#to_date').val(minform);
	getTimesheetData(Dates);
	
}else if(data[0] == "error"){

$('.progress_error').html('<b>Duplicate Entry For The Time Found</b>');
	
}

}

});
}
});

$(document).on('click','.get_data',function(e){
var fromTime = $(this).data('from');
var toTime = $(this).data('to');	
var Dates = $('#from_datetime').val();
$('.loadingg,#loader').loading(true);
$.ajax({
	dataType: 'html',
	type: "POST",
    url: "php/timesheet.handle.php",
    cache: false,
    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getDataFromTime");?>',fromTime:fromTime,toTime:toTime,date:Dates},	
    success: function (data) {
        data = JSON.parse(data);
       $('.loadingg,#loader').loading(false);
        if(data[0] == "success"){
        	$("#project_id option[value='" + data[2][0].project_id + "']").prop("selected", true);
        	if(data[2][0].task_name != null){
        	 var html = '<option value="'+data[2][0].task_id+'">'+data[2][0].task_name+'['+data[2][0].task_id+']</option>';
        	 $('#task').html(html);
         	}else{
         		$('#task').html('<option value="">NO Task Found</option>');
         		if($("#corporate_task option[value='" + data[2][0].task_n_id + "']").length != 0){
         		$('#eng_li,#tim_li').removeClass('active');
         		$('#corp_li').addClass('active');
         		$('#Engineering').hide();
                $('#corporate').show();
                $('#time_off').hide();
         		$("#corporate_task option[value='" + data[2][0].task_n_id + "']").prop("selected", true);
         		}else{
         			$('#eng_li,#corp_li').removeClass('active');
             		$('#tim_li').addClass('active');
             		$('#Engineering').hide();
                    $('#corporate').hide();
                    $('#time_off').show();
         		$("#Type option[value='" + data[2][0].task_n_id + "']").prop("selected", true);	
         		}
         	}        	
			$('#comments').val(data[2][0].comments);
			$('.days1').html(data[2][0].hours);
				var dates1 = new Date("01/01/2007 " + data[2][0].to_time);
				var gethour = dates1.getHours();
				var getMin = dates1.getMinutes()+1;
			$('#from_date').data("DateTimePicker").date(gethour+':'+getMin);
			$('#to_date').data("DateTimePicker").date(data[2][0].to_time);
			
             }else if(data[0] == "error"){
        	
        }
    }
});
	
});

$('#view_table_form').on('submit',function(e){
	e.preventDefault();
if($('input[name="status[]"]:checked').length != 0 && $('#from_dates').val() != '' && $('#to_dates').val() != ''){


	$('.ajax_loaders').show();
	

	$.ajax({
	 	processData: false,
  contentType: false,
  type: "POST",
  url: "php/timesheet.handle.php",
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
$('.ajax_loaders').hide();
if(data[0] == "success"){
	$('#error_table').html('');
$('#timesheet_details_submit_form').show();
$('.timesheet_details_show').html('');

var html = '';
var check = '';
for(var i=0;i<data[2].length;i++){
if(data[2][i].status == 'saved'){
check = 'saved_check';
}else{
check = 'no_check';
}
	
html +='<tr id="'+data[2][i].Date.replace(/[/\\*]/g, "")+data[2][i].from_time.replace(/ /g,'').replace(/:/g, "")+'remove">';
html +='<td><input type="checkbox" name="mailcheckbox[]" class="mail-checkbox '+check+'" id="'+data[2][i].from_time+'" value="'+data[2][i].Date+'"></td><td>'+data[2][i].employee_name+'</td><td>'+data[2][i].Date+'</td><td>'+data[2][i].from_time+'</td><td>'+data[2][i].to_time+'</td><td id="'+data[2][i].Date.replace(/[/\\*]/g, "")+data[2][i].from_time.replace(/ /g,'').replace(/:/g, "")+'status">'+data[2][i].status+'</td><td>'+data[2][i].hours+'</td><td>'+data[2][i].comments+'</td>';
html +='</tr>';
}
$('#timesheet_details_submit_table').dataTable().fnDestroy();

$('.timesheet_details_show').html(html);
EditableTable.init();
}else if(data[0] == "error"){
$('#timesheet_details_submit_form').hide();
$('.timesheet_details_show').html('');
$('#error_table').html('No Data Found');
	
}

}

});
}else{
}	
});
var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
        
var oTable =  $('#timesheet_details_submit_table').dataTable( {
	 "aLengthMenu": [
                   [5, 15, 20, -1],
                   [5, 15, 20, "All"] // change per page values here
               ],
   "iDisplayLength": 5,
   "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
   "bProcessing": true,
   
     });
        	
$('#timesheet_details_submit_table_wrapper .dataTables_filter').html('<div class="input-group">\
       <input class="form-control medium" id="searchInput" type="text">\
       <span class="input-group-btn">\
         <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
       </span>\
       <span class="input-group-btn">\
         <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
       </span>\
   </div>');
$('#timesheet_details_submit_table_processing').css('text-align', 'center');
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
$(".mail-group-checkbox").change(function (e) {
e.preventDefault();
$(".saved_check").not(':disabled').prop('checked', $(this).prop("checked"));
});
$(document).on('change','.no_check',function (e) {
e.preventDefault();
$('.no_check').prop('checked', false);
});
$('#submit_now').click(function(e){
	var Date = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value;
	}).join(',');
	var fromTime = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.id;
	}).join(',');
	var joined = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value.replace(/[/\\*]/g, '')+n.id.replace(/ /g,'').replace(/:/g, "");
	}).join(',');
	BootstrapDialog.show({
		 title:'Confirmation',
			        message: 'Are Sure you want Submit?</strong>',
			        buttons: [{
			            label: 'Yes',
			            cssClass: 'btn-sm btn-success',
			            autospin: true,
			            action: function(dialogRef){
			$.ajax({
				dataType: 'html',
				type: "POST",
		        url: "php/timesheet.handle.php",
		        cache: false,
		        data: {date:Date,fromTime:fromTime,act: '<?php echo base64_encode($_SESSION['company_id']."!submitTimesheet");?>'},	
		        complete:function(){
		            dialogRef.close();
		         },
		        success: function (data) {
		            data = JSON.parse(data);
		            if(data[0] == "success"){
		            	var array = joined.toString().split(",");
		            	$.each(array,function(i){
		            		$('#'+array[i]+'status').html('Submitted');
		            		$('#'+array[i]+'remove').find('.saved_check').prop('checked', false);
		            		$('#'+array[i]+'remove').find('.saved_check').attr('disabled',true);
		            		 });
			            
		            }else if(data[0] == "error"){

		            	BootstrapDialog.alert('Failed To Submit');
		            }
			            
		            
		        }
		    });
			            }
		            }, {
		                label: 'No',
		                cssClass: 'btn-sm btn-danger',
		                action: function(dialogRef){
		                    dialogRef.close();
		                  
		                }
		            }]
			});
	
});
$('#delete_now').click(function(e){
	var Date = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value;
	}).join(',');
	var fromTime = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.id;
	}).join(',');
	var joined = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value.replace(/[/\\*]/g, '')+n.id.replace(/ /g,'').replace(/:/g, "");
	}).join(',');
	BootstrapDialog.show({
		 title:'Confirmation',
			        message: 'Are Sure you want Delete?</strong>',
			        buttons: [{
			            label: 'Yes',
			            cssClass: 'btn-sm btn-success',
			            autospin: true,
			            action: function(dialogRef){
			$.ajax({
				dataType: 'html',
				type: "POST",
		        url: "php/timesheet.handle.php",
		        cache: false,
		        data: {date:Date,fromTime:fromTime,act: '<?php echo base64_encode($_SESSION['company_id']."!deleteTimesheet");?>'},	
		        complete:function(){
		            dialogRef.close();
		         },
		        success: function (data) {
		            data = JSON.parse(data);
		            if(data[0] == "success"){
		            	var array = joined.toString().split(",");
		            	$.each(array,function(i){
		            		$('#'+array[i]+'remove').remove();
		            		 });
		               
		            }else if(data[0] == "error"){

		            	BootstrapDialog.alert('Failed To Delete');
		            }
			            
		            
		        }
		    });
			            }
		            }, {
		                label: 'No',
		                cssClass: 'btn-sm btn-danger',
		                action: function(dialogRef){
		                    dialogRef.close();
		                  
		                }
		            }]
			});
	
});
      </script>


  </body>
</html>