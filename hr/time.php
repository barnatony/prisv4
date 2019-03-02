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
                                      <li class="">
                                              <a href="#view"  id="view_tab" data-toggle="tab" data-loaded=false data-title = "view_tab">
                                                  Timesheet 
                                              </a>
                                          </li>
                                         
                                          
                                         
                                      </ul>
                                  </header>
                                   <div class="tab-content tasi-tab">
                                           <div class="tab-pane" id="view">
                                           <div class="panel-body">
                                           <div class="panel-body" id="timesheet_submit_form">
         								   <div class="space15"></div>                     
                          <div class="adv-table editable-table">
                          <section id="flip-scroll">
		 <table class="table table-bordered table-hover  cf" id="timesheet_submit_table">
                           <thead class="cf">
                              <tr>
                                 
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
                                          <div class="tab-pane" id="entry">
                                          <div class="panel-body">
                                          <form  role="form"  method="POST" class="form-horizontal" enctype="multipart/form-data" id="emp_detail_form">
                                          <div class="col-md-12 well panel-body">
                                          <div class="col-md-2">
                                        
                                          <div class="form-group">
                                            
                                      <div class="col-lg-9" >
                                          <input type="hidden" name="from_datetime" id="from_datetime" class="form_datetime form-control">
                                      <div class="col-md-12" id="cenhj">
								<time class="tearsheet--big" >
								<span class="month"></span>
								<span class="days"></span>
								<span class="day_expand"></span>
								</time>
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
                                              </div></div>
                                              <div class="col-lg-2" style="padding-top:33px;">
                                              <span class="days1"></span>
                                              </div>
                                              
                                              
                                              </div>
                                              <div class="progress_bar">
  <div class="progress-bar progress-bar-success" role="progressbar" style="width:40%">
    Free Space
  </div>
  <div class="progress-bar progress-bar-warning" role="progressbar" style="width:10%">
    Warning
  </div>
  <div class="progress-bar progress-bar-danger" role="progressbar" style="width:20%">
    Danger
  </div>
   <div class="progress-bar progress-bar-warning" role="progressbar" style="width:20%">
    Danger
  </div>
   <div class="progress-bar progress-bar-success" role="progressbar" style="width:10%">
    Danger
  </div>
</div><br><br>
                                              <div class="clearfix"></div>
                                              <div class="col-md-12">
                                              <ul class="nav nav-pills " >
                                          <li class="active" id="eng_li">
                                              <a id="engineering_tab" style="cursor: pointer" >
                                                  Engineering
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
                                       <div class="panel-body well" style="border:1px solid #49BAE4">
                                       <div class="col-lg-7">
                                          <div  id="Engineering">
                                          <div class="form-group">
                                          <label class="col-lg-2"> Project
                                          </label>
                                          <div class="col-lg-10">
                                          <select name="project" id="project" class="form-control">
                                          <option>Iphone</option>
                                          </select>
                                          </div>
                                          </div>
                                          <div class="form-group">
                                          <label class="col-lg-2"> Task
                                          </label>
                                          <div class="col-lg-10">
                                          <select name="task" id="task" class="form-control">
                                          <option>Quick</option>
                                          </select>
                                          </div>
                                          </div>
                                          </div>
                                          <div id="corporate" style="display: none">
                                          <div class="form-group">
                                          <label class="col-lg-2"> Task
                                          </label>
                                          <div class="col-lg-10">
                                          <select name="corporate_task" id="corporate_task" class="form-control">
                                          <option>Corporate Event</option>
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
                                          <option>Permission</option>
                                          </select>
                                          </div>
                                          </div>
                                          </div>
                                          </div>
                                       <div class="col-lg-5">
                                       <div class="form-group">
                                       <label class="col-lg-2 col-sm-2">Comments</label>
                                        <div class="col-lg-10">
                                        <textarea class="form-control" rows="4" id="comments" name="comments" ></textarea>
                                        </div>
                                       </div>
                                       
                                       </div>
                                       <div class="col-md-12">
                                       <button type="button" class="btn btn-success pull-right" style="margin-left: 10px;">Save</button>
                                       <button type="button" class="btn btn-danger pull-right" >Cancel</button>
                                         </div>
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
      <script src="../js/moment.js"></script>
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
    		  $('#time_tabs a[href="#view"]').tab('show');
    		  
    		  } 

    	  // Change hash for page-reload
    	  $('#time_tabs a').on('shown.bs.tab', function (e) {
    	      window.location.hash = e.target.hash;
    	  })
    	});


      $('#time_tabs').on('shown.bs.tab', function (e) {
    	   // newly activated tab
    	    window.scrollTo(0, 0);
    	    $('.form_datetime').datetimepicker({
    	        format: 'dddd,DD/MM/YYYY'        
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
			
			//make the tab loaded true to prevent re-loading while clicking.
      		$(e.target).data('loaded',true);
    		}
    	});

    
 	 $('#from_date,#to_date').on('dp.change',function(e){
 	 	 
 		var fromHrs  = $('#from_date').val();
 		var toHrs = $('#to_date').val();
 		
 		var timeStart = new Date("01/01/2007 " + fromHrs);
 		var timeEnd = new Date("01/01/2007 " + toHrs);
		if(timeStart > timeEnd){
			$('.days1').html('Start Time Must Be Greater Than End Time');
		}else{
 		var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds
 		var minutes = diff % 60;
 		var hours = (diff - minutes) / 60;
 		$('.days1').html(hours+' Hrs '+ minutes+ ' Min');
		}
 	
 	 });
     

      $('.tearsheet--big').click(function(e){
    	  var $datepicker = $('.form_datetime').datetimepicker({
  	        format: 'DD/MM/YYYY'        
  	    });
    
    	
    	  $('.form_datetime').data("DateTimePicker").toggle();
    	  $('.form_datetime').on('dp.change',function(e){
    		  var fullDate = new Date($('.form_datetime').data("DateTimePicker").date());
      	    var twoDigitMonth = monthNames[fullDate.getMonth()];
      	    var twoDigitDate = fullDate.getDate();
      	 	    $('.month').html(twoDigitMonth+', '+fullDate.getFullYear());
      	 	    $('.days').html(twoDigitDate);
      	 	    $('.day_expand').html(weekday[fullDate.getDay()]);
      	 	    
    	  });
    	
    
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
      
      </script>


  </body>
</html>