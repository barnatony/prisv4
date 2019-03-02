<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="Claim Rules">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Project</title>
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
      #reviewer_cho_chosen,#employeeid_chosen{
      width:100%!important;
      }.bio-graph-info {
    background: #fcfcfc;
    }.ajax_loaders{
    display:block;
    margin:0 auto;
    }                            .fa-check {
    position: relative;
    margin: 10px;
    cursor: pointer;
}
.times {
    position: relative;
    margin: 10px;
    margin-left: -1px;
    cursor: pointer;
}
}
      
      </style>
      
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
      
      <?php 
      require_once(LIBRARY_PATH . "/employee.class.php");
      require_once(LIBRARY_PATH . "/project.class.php");
      
      $employee = new Employee();
      $employee->conn = $conn;
      $employeeOnly=$employee->select(0);
      
      $project = new Project();
      $project->conn = $conn;
      $projectOnly = $project->selectProjectByEmp($_SESSION['employee_id']);
     
      ?>
      
     <section id="main-content">
          <section class="wrapper site-min-height">
     <section class="panel">
     <header class="panel-heading tab-bg-dark-navy-blue">
                                      <ul class="nav nav-tabs " id="project_tabs" role="tablist">
                                      <li class="">
                                              <a href="#task"  id="task_tab" data-toggle="tab" data-loaded=false data-title = "Task">
                                                  Tasks
                                              </a>
                                          </li>
                                          <li id="header_req_active">
                                              <a href="#manageProject" style="cursor:pointer;" id="project_tab" data-toggle="tab" data-loaded=false data-title = "Manage Project">
                                                  Manage Projects
                                              </a>
                                               <span>
                         <a id="back_go" style="display:block;text-align:center"></a>
                          </span>
                                          </li>
                                          <li class="">
                                              <a href="#reviewers"  id="task_tab" data-toggle="tab" data-loaded=false data-title = "Reviewer">
                                                  Review Tasks
                                              </a>
                                          </li>
                                         
                                      </ul>
                                  </header>
                                  <div class="panel-body">
                                   <div class="tab-content tasi-tab">
                                   <div class="tab-pane" id="task">
                                    <div class="panel-body" id="editable-table">
              <div class="adv-table editable-table" >
                          	<section id="flip-scroll">
		 <table class="table table-striped table-hover  cf" id="task-sample_table" >
                
                      <thead>
                      <tr>
                          <th>Task No</th>
                          <th>Task Name</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                          <th>Hours</th>
                          <th>Status</th>
                          <th>Priority</th>
                      </tr>
                      </thead>
                     <tbody class="view_task_table">
                  <tr><td colspan="7"><img src="../img/ajax-loader.gif" id="ajax_loader_getmessages"></td></tr>
                 </tbody>
                  
                  </table>
                  </section>
                  </div>
                  </div>
                                   </div>
                                   <div class="tab-pane" id="manageProject">
                                          <?php 
                                         if($projectOnly){
                                         	?>
                                         	 <div class="col-md-12">
                                             <form class="form-horizontal" role="form" method="post" id="taskForm">
                                           <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!taskInsert");?>" />
                         				 <div class="form-group">
                                      <label  class="col-lg-1  control-label">Project</label>
                                        <div class="col-lg-5">
                                       <select name="selectTask" id="selectTask"  class="form-control">
                                       <option>Select Project</option>
                                       <?php 
                                      
                                       foreach ($projectOnly as $row){
                                      echo "<option value='".$row['project_id']."'>".$row['project_title']." [ ".$row['project_id']." ]<br>"."</option>"; 
                                       }
                                       ?>
                                        </select>
                                       </div>
                                       <div class="col-lg-4">
                                       <button type="submit" id="view_task" class="btn btn-success">View</button>
                                       </div>
                                   </div>
                         				 </form>
                                             <div class="project_system_details_err">
                                          </div>
                                          <img src="../img/ajax-loader.gif" id="ajax_loader_getmessages" style="display: none">
                                         <div class="project_system_details" style="display:none;">
                  <div class="row">
                  <div class="col-md-12">
                      <section class="panel">
                          <div class="bio-graph-heading project-heading">
                         
                              <strong class="project_subject"></strong>
                          </div>
                          <div class="panel-body bio-graph-info">
                              <!--<h1>New Dashboard BS3 </h1>-->
                              <div class="row p-details">
                                  <div class="bio-row">
                                      <p><span class="bold">Project Category </span>: <span class="project_cate"></span></p>
                                  </div>
                                  <div class="bio-row">
                                      <p style="height:34px;"><span class="bold">Status </span>: <span class="project_status"></span>
                                 <span style="margin-left: 60px" id="edit_now">
                                 </span>
                                 </p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span class="bold">Created </span>: <span class="project_created"></span></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span class="bold">Start Date</span>: <span class="project_start_date" ></span></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span class="bold">End Date</span>: <span class="project_end_date" ></span></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span class="bold">Priority </span>: <span class="project_priority"></span></p>
                                  </div>
                                   <div class="bio-row">
                                      <p><span class="bold">Hours </span>: <span class="project_hours"></span></p>
                                  </div>
                                                        <div class="clearfix"></div>
                                                                 <div class="col-lg-12">
                                                                 
                                     <p style="text-align: justify"><span class="bold"><b>Description:</b></span>&nbsp;<span class="project_desc"></span>
                                     </p>
                                     <div class="col-lg-6">
                                     <p style="text-align: justify"><span class="bold"><b>Assigned Employee:</b></span>
                                     </p>
                                        <ul class="list-unstyled p-files assigned_emp">
                                  
                              </ul></div> <div class="col-lg-6">
                                    <p style="text-align: justify"><span class="bold"><b>Team Members:</b></span>
                                     </p>
                                        <ul class="list-unstyled p-files team_emp">
                                  
                              </ul>    </div>
                                  </div>
                              </div>

                          </div>

                      </section>

                      <section class="panel">
                        <header class="panel-heading">
                          Tasks
                          <div class="pull-right" style="margin-bottom:10px;">
                          <div class="reply_fg">
                         <a  data-toggle="modal" href="#myModal" class=" btn btn-success btn-xs"><i class="fa fa-reply-all"></i> &nbsp;Create Task</a> 
                          </div>
                          </div>
                        </header>
                        
                                          
                                          <div class="panel-body" id="editable-table">
              <div class="adv-table editable-table" >
                          	<section id="flip-scroll">
		 <table class="table table-striped table-hover  cf" id="project-sample_table" >
                          <thead>
                          <tr>
                              <th>Task No</th>
                          	  <th>Employee Name</th>
                          	  <th>Status</th>
                              <th>Task Name</th>
                              <th>Start Date</th>
                              <th>End Date</th>
                              <th>Hours</th>
                              <th>Reviewer</th>
                              <th>Action</th>
                          </tr>
                          </thead>
                          <tbody class="task_view_all">
                         
                          </tbody>
                          </table>
                          </section>
                          </div>
                          </div>
                          
                        
                      
                      </section>

                  </div></div>
                  </div>        
                                             </div>
                                         	<?php 
                                         }else{
                                         	echo "<div class='text-center'>No Project Found</div>";
                                         }
                                          ?>
                                          </div>
                                          
                                           <div class="tab-pane" id="reviewers">
                                           <div class="panel-body well">
                                          <form  role="form"  method="POST" class="form-horizontal" enctype="multipart/form-data" id="view_table_form">
                                          <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!getDataByDate");?>">
                                         
                                         <div class="col-md-8">
                                         <div class="form-group">
                                          <div class="col-md-2" style="padding-left:0px">
		                                      <label class="col-lg-5 control-label">Status</label>
		                                      </div>
		                                      <div class="col-lg-8" style="padding-left:0px">
		                                      
                                         <label  class="col-lg-3 control-label">
                                        <input type="checkbox" name="status[]" id="status" class="status" value="approved">Approved
                                         </label><label  class="col-lg-3 control-label">
                                        <input type="checkbox" name="status[]" id="status" class="status" value="submitted" checked>Submitted
                                         </label><label  class="col-lg-3 control-label">
                                        <input type="checkbox" name="status[]" id="status" class="status" value="disputed">Disputed
                                        </label>
                                         <div class="col-lg-2">
                                       <button type="submit" class="btn btn-success" id="submit">View</button>
                                       </div>
		                                      </div>
		                                       
		                                      </div>
		                                    
                                         </div>
                                          </form>
                                          </div>
                                          <img src="../img/ajax-loader.gif" class="ajax_loaders" style="display:none">
                                          <div id="view_task_table"></div>
                                          <div class="panel-body" id="editable-table_review" style="display:none">
                                           <div class="pull-right">
                     <button type="button" class="btn btn-success btn-xs" id="approve_now">Approve</button>
                     <button type="button" class="btn btn-danger btn-xs" id="reject_now">Reject</button>
                     </div>
              <div class="adv-table editable-table" >
                          	<section id="flip-scroll">
		 <table class="table table-striped table-hover  cf" id="claims-sample_table" >
                
                      <thead>
                      <tr>
                       <th><input type="checkbox" class="mail-checkbox mail-group-checkbox"></th>
                          <th>Task No</th>
                          <th>Employee Name</th>
                          <th>Task Name</th>
                          <th>From Time</th>
                          <th>To Time</th>
                          <th>Hours</th>
                          <th>Task Status</th>
                          <th>Status</th>
                          <th>Comments</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                     <tbody class="view_reviewer_table">
                  <tr><td colspan="6"><img src="../img/ajax-loader.gif" id="ajax_loader_getmessages"></td></tr>
                 </tbody>
                  
                  </table>
                  </section>
                  </div>
                  </div>
                                          
                                          
                                          </div>
                                       <div class="modal fade" id="status_ReviewModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog" style="width:50%;">
                                  <div class="modal-content">
                                      
                                      <div class="modal-header"><button type="button" class="close close_status" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Change Status</h4></div>
                                      <div class="modal-body">
                                      <div class="panel-body">
                                      <form class="form-horizontal" role="form" method="post" id="changeStatusReview">
                                      <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!upateReviewerStatus");?>" />
                                      <input type="hidden" name="task_number" id="task_number_rev"  />
                                      <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Status</label>
                                        <div class="col-lg-9">
                                     <select name="status" id="status_rev" class="form-control">
                                     <option value="To Do">To Do</option>
                                     <option value="In Progress">In Progress</option>
                                     <option value="To Verify">To Verify</option>
                                     <option value="To Test">To Test</option>
                                     <option value="Awaiting Approval">Awaiting Approval</option>
                                     <option value="Completed">Completed</option>
                                     </select>
                                       </div>
                                   </div></div>
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Percentage Completed</label>
                                        <div class="col-lg-9">
                                     <input type="text" name="percentage_com" id="percentage_com_rev" class="form-control" />
                                       </div>
                                   </div></div>
                                    <div class="col-lg-12 ">
                                    <button type="button" id="cancel_status_rev" class="btn btn-danger pull-right">Cancel</button>
                                    <button type="submit" id="submit_rev_status" class="btn btn-success pull-right" style="margin-right:10px;">Submit</button>
                                    </div>
                                      </form>
                                      </div>
                                      </div>
                                      </div>
                                      </div>
                                      </div>
                                         
                                          
                                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog" style="width:80%;">
                                  <div class="modal-content">
                                      
                                      <div class="modal-header"><button type="button" class="close close_task" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Create Task</h4></div>
                                      <div class="modal-body">
                                      <form class="form-horizontal" role="form" method="post" id="taskAddForm">
                                           <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!taskInsert");?>" />
                         				 
                                          
                                          <div class="panel-body">
                                          <div class="col-lg-6">
                                          <div class="col-lg-12">
                                          <input type="hidden" name="projectId" id="projectId"  >
                                       
                                       </div>
                                  
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Task Number</label>
                                        <div class="col-lg-9">
                                        <input class="form-control alias1 float_all" type="text" id="taskNumber" name="taskNumber"  required />
                                      <img class="ajax_loader_inside" id="ajax_call" src="../img/input-spinner.gif" style="display: none;">
                                       <span class="nm_not_found1" style="display: none;"></span>
                                       </div>
                                   </div></div>
                                  
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Title</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" type="text" id="title" name="title"  required />
                                       </div>
                                   </div></div>
                                   
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Type</label>
                                        <div class="col-lg-9">
                                      <select name="type" id="type_proj" class="form-control">
                                      <option>Select Type</option>
                                       <option value="Mobile">Mobile</option>
                                       <option value="Laptop">Laptop</option>
                                       <option value="Tv">TV</option>
                                      </select>
                                       </div>
                                   </div></div>
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Description</label>
                                        <div class="col-lg-9">
                                        <textarea class="form-control"  id="description" name="description" rows="3" required ></textarea>
                                       </div>
                                   </div></div>
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Reviewer</label>
                                        <div class="col-lg-9">
                                        <select name="reviewer" id="reviewer_cho" required class="form-control">
                                        <option>Select Reviewer</option>
                                         <?php 
                                       foreach ($employeeOnly as $row){
                                      echo "<option value='".$row['employee_id']."'>".$row['employee_name']." [ ".$row['employee_id']." ]<br>"."</option>"; 
                                       }
                                       ?>
                                        </select>
                                       </div>
                                   </div></div>
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Assigned To</label>
                                        <div class="col-lg-9">
                                        <select name="employeeid[]" id="employeeid" multiple required class="form-control">
                                        <option value="no">Select Employee</option>
                                        <?php 
                                       foreach ($employeeOnly as $row){
                                      echo "<option value='".$row['employee_id']."'>".$row['employee_name']." [ ".$row['employee_id']." ]<br>"."</option>"; 
                                       }
                                       ?>
                                        </select>
                                       </div>
                                   </div></div>
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Priority</label>
                                        <div class="col-lg-9">
                                       <select name="priority" id="priority"  class="form-control">
                                        <option value="Normal">Normal</option>
                                        <option value="High">High</option>
                                        <option value="Low">Low</option>
                                        </select>
                                       </div>
                                   </div></div>
                                   </div>
                                   <div class="col-lg-6">
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Start Date</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" type="text" id="start_dates" name="start_date"  required />
                                       </div>
                                   </div></div>

 <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">End Date</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" type="text" id="end_dates" name="end_date"  required />
                                       </div>
                                   </div></div>

<div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Hours</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" type="text" id="hours" name="hours"  required />
                                       </div>
                                   </div></div>
                                   </div>
                                   <div class="col-lg-12 ">
                                    <button type="button" id="cancel_task" class="btn btn-danger pull-right">Cancel</button>
                                    <button type="submit" id="submit_task" class="btn btn-success pull-right" style="margin-right:10px;">Submit</button>
                                    </div>
                                   </div>
                                          </form>
                                      </div>
                                      </div>
                                      </div>
                                      </div>
                                               
                                          </div></div>
                                          </section>
                                          </section>
                                          </section>
                                          </section>
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
<script src="../js/jquery.validate.min.js"></script>
       <!--common script for all pages-->
<script src="../js/common-scripts.js"></script>
<script src="../js/bootstrap-dialog.js"></script>
<script src="../js/jquery.knob.js"></script>
<script>
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
	      $('#project_tabs a[href="#' + tab + '"]').tab('show');
	    
	  	
	  }else{
		  $('#project_tabs a[href="#task"]').tab('show');
		  
		  } 

	  // Change hash for page-reload
	  $('#project_tabs a').on('shown.bs.tab', function (e) {
	      window.location.hash = e.target.hash;
	      
	  })
	});


  $('#project_tabs').on('shown.bs.tab', function (e) {
	   // newly activated tab
	    
	    $('#start_dates,#end_dates').datetimepicker({
	        format: 'DD/MM/YYYY'        
	    }); 

	    $('#from_dates,#to_dates').datetimepicker({
	        format: 'DD/MM/YYYY' 
	        
	    });  
	    window.setTimeout(function() {
	        $(window).scrollTop(0); 
	    }, 0);
	    $('#reviewer_cho,#employeeid').chosen();
	  if($(e.target).data('loaded') === false){
		  
		  if($(e.target).data('title') === 'Manage Project'){  
			  var projectId = getParameterByName("projectId"); 
			  if(projectId){
				 var flag = 0;
				  $('#taskForm').hide();
				  $('#back_go').html('View');
				  viewProject(projectId);
				  if(flag == 0){
					  $('#project_tab').click(function(e){
							window.location.href = 'project#manageProject';
						  });
					  flag = 1;
				  }
			  }
			 
			  }
		  if($(e.target).data('title') === 'Reviewer'){  
		  getReviewer('submitted');
		  }
		  if($(e.target).data('title') === 'Task'){  
		  getAllTask();
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
  $('#taskForm').on('submit',function(e){
		e.preventDefault();
		var projectId = $('#selectTask').val();
		viewProject(projectId);
		
	});
  $.validator.setDefaults({ ignore: ":hidden:not(select)" });
  $('#taskAddForm').validate({
	  rules: {
		  type:"required",
		  reviewer:"required",
		  employeeid:"required"
		  },
		  submitHandler: function (form) {
	  	$.ajax({
	     	 	processData: false,
	          contentType: false,
	          type: "POST",
	          url: "php/project.handle.php",
	          cache: false,
	          data:new FormData(form),
	          beforeSend:function(){
	           	$('#submit_task').button('loading'); 
	            },
	            complete:function(){
	          	$('#submit_task').button('reset');
	            },	
	  success: function (data) {
	  	 data = JSON.parse(data);
	  	if(data[0] == 'success'){
	  		$('#taskAddForm')[0].reset();
	  		
	  		$('#reviewer_cho').trigger("chosen:updated");
	  		$('.close_task').trigger('click');
	  		BootstrapDialog.alert('Task Added Successfully');		
	  		getTasks($('#projectId').val());
	  	}else{
	  		BootstrapDialog.alert('Task Added Failed');
	  	}
	  	

	     }

	  });
		  }
	  	
	  });

	
  function viewProject(projectId){
	  $('#ajax_loader_getmessages').show();
		$.ajax({
			dataType: 'html',
			type: "POST",
		    url: "php/project.handle.php",
		    cache: false,
		    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!viewProjectByIdandEmp");?>',projectId:projectId},	
		    success: function (data) {
		        data = JSON.parse(data);
		        $('#ajax_loader_getmessages').hide();
		        $('.task_view_all,.project_system_details_err').html('');
		        $('.project_system_details').show();
		        if(data[0] == "success"){
		        	if(data[2][0].status == 'Inisiated' || data[2][0].status == 'To Do' || data[2][0].status == 'In Progress'){
						var status_tsk = '<span id="fa_show" style="position:initial;width:150px;" class="badge bg-primary">'+data[2][0].status+'</span>';
						}else if(data[2][0].status == 'To Verify' || data[2][0].status == 'To Test' || data[2][0].status == 'Awaiting Approval'){
						var status_tsk = '<span id="fa_show" style="position:initial;width:150px;" class="badge bg-warning">'+data[2][0].status+'</span>';
						}else if(data[2][0].status == 'Completed'){
						var status_tsk = '<span id="fa_show" style="position:initial;width:150px;" class="badge bg-success">'+data[2][0].status+'</span>';
						}
					if(data[2][0].priority == 'Normal'){
						var priority = '<i class=" fa fa-circle text-success"></i> ';
					}else if(data[2][0].priority == 'High'){
						var priority = '<i class=" fa fa-circle text-danger"></i> ';
					}else if(data[2][0].priority == 'Low'){
						var priority = '<i class=" fa fa-circle text-warning"></i> ';
					}

					var htmls = '<div class="value_column" style="display:none;width:400px;"><div class="col-md-6"><select  name="status" id="'+data[2][0].project_id+'" class="form-control input-sm"><option value="To Do">To Do</option><option value="In Progress">In Progress</option>option value="To Verify">To Verify</option><option value="To Test">To Test</option><option value="Awaiting Approval">Awaiting Approval</option><option value="Completed">Completed</option></select></div><div class="col-md-6"><i id="valued" data-id="'+data[2][0].project_id+'" class="fa fa-check valued_tick" style="cursor:pointer;"></i><img src="../img/input-spinner.gif" class="loader_away" style="display:none;margin-right:8px;"><i id="valued" style="cursor:pointer;" class="fa fa fa-times valued times"></i></div></div>';
					var html1 = '<a title="Edit Status" data-status="'+data[2][0].status+'" data-id="'+data[2][0].project_id+'"id="edit_show" style="cursor: pointer"><i class="fa fa-pencil"></i></a>';

					$('#edit_now').html(html1);			
			        $('#projectId').val(data[2][0].project_id);
		            $('.project_subject').html(data[2][0].project_title);
					$('.project_cate').html(data[2][0].project_catagory);
					$('.project_status').html(status_tsk +htmls);
					$('.project_created').html(data[2][0].creator);
					$('.project_start_date').html(data[2][0].start_date);
					$('.project_end_date').html(data[2][0].end_date);
					$('.project_priority').html(priority + data[2][0].priority);
					$('.project_desc').html(data[2][0].description);
					$('.project_hours').html(data[2][0].hours);
					var html = "";
					for(var i=0;i<data[2].length;i++){
						html += '<li>';
						html += '<a><i class="fa fa-user"></i> '+data[2][i].employee_name+'</a>';
						html += '</li>';
					}
					$('.assigned_emp').html(html);
				
					getTasks(projectId);
					
		        }else if(data[0] == "error"){
		        	$('#back_go').html('');
		        	$('.project_system_details').hide();
		        	$('.project_system_details_err').html('<div class="text-center">No Project Found</div>');
		        }
		    }
		});
	}
	$(document).on('click','.status_edit',function(e){
         e.preventDefault();
		var data = $(this).data('id');
		var status = $(this).data('status');
		var per = $(this).data('per');
	$("#status_rev option[value='" +status + "']").prop("selected", true);
	$('#percentage_com_rev').val(per);
	$('#task_number_rev').val(data);
	});
	$('#cancel_status').click(function(e){
	e.preventDefault();
	$('.close').trigger('click');
	});
	$('#cancel_status_rev').click(function(e){
		e.preventDefault();
		$('.close').trigger('click');
		});
	
	$('#changeStatusReview').on('submit',function(e){
		e.preventDefault();
			$.ajax({
		   	 	processData: false,
		        contentType: false,
		        type: "POST",
		        url: "php/project.handle.php",
		        cache: false,
		        data:new FormData(this),
		        beforeSend:function(){
		         	$('#submit_rev_status').button('loading'); 
		          },
		          complete:function(){
		        	$('#submit_rev_status').button('reset');
		          },	
		success: function (data) {
			 data = JSON.parse(data);
			if(data[0] == 'success'){
				$('.close_status').click();
				BootstrapDialog.alert('Status Change Successfully');	
				var status = $('#task_number_rev').val();
				if($('#status_rev').val() == 'To Do' || $('#status_rev').val() == 'In Progress'){
					var status_tsk = '<span style="position:initial" class="badge bg-primary">'+$('#status_rev').val()+'</span>';
					}else if($('#status_rev').val() == 'To Verify' || $('#status_rev').val() == 'To Test' || $('#status_rev').val() == 'Awaiting Approval'){
					var status_tsk = '<span style="position:initial" class="badge bg-warning">'+$('#status_rev').val()+'</span>';
					}else if($('#status_rev').val() == 'Completed'){
					var status_tsk = '<span style="position:initial" class="badge bg-success">'+$('#status_rev').val()+'</span>';
					}
				$('.'+status+'replace_process_rev1').html(status_tsk);
				$('#changeStatusReview')[0].reset();
			}else{
				BootstrapDialog.alert('Status Change Failed');
			}
			

		   }

		});
	});
	$(".mail-group-checkbox").change(function (e) {
		e.preventDefault();
		$(".status_sub").not(':disabled').prop('checked', $(this).prop("checked"));
		
	});
	$('#approve_now').click(function(e){
		var Date = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
		    return n.value;
		}).join(',');
		var fromTime = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
		    return n.id;
		}).join(',');
		var employee_id = jQuery.map($('input[type="hidden"][name="employee_ids1[]"]'), function (n, i) {
		    return n.value;
		}).join(',');
		var status = jQuery.map($('input[type="hidden"][name="employee_ids1[]"]'), function (n, i) {
		    return n.id;
		}).join(',');
		BootstrapDialog.show({
			 title:'Confirmation',
				        message: 'Are Sure you want Approve?</strong>',
				        buttons: [{
				            label: 'Yes',
				            cssClass: 'btn-sm btn-success',
				            autospin: true,
				            action: function(dialogRef){
				$.ajax({
					dataType: 'html',
					type: "POST",
			        url: "php/project.handle.php",
			        cache: false,
			        data: {date:Date,fromTime:fromTime,employee_id:employee_id,act: '<?php echo base64_encode($_SESSION['company_id']."!approveTimesheet");?>'},	
			        complete:function(){
			            dialogRef.close();
			         },
			        success: function (data) {
			            data = JSON.parse(data);
			            if(data[0] == "success"){
			            	BootstrapDialog.alert('Status Approved Successfully');
			            	var array = status.toString().split(",");
			            	$.each(array,function(i){
			            		$('.'+array[i]+'replace_process_rev').html('<span class="badge bg-success" style="position:initial">Approved</span>');
			            	});
			            	getReviewer('submitted');
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
function getReviewer(status){
	$('.ajax_loaders').show();
	$.ajax({
		dataType: 'html',
		type: "POST",
	    url: "php/project.handle.php",
	    cache: false,
	    data: {status:status,act: '<?php echo base64_encode($_SESSION['company_id']."!viewReviewer");?>'},	
	    success: function (data) {
	        data = JSON.parse(data);
	        $('.ajax_loaders').hide();
	        if(data[0] == "success"){
	        	$('#editable-table_review').show();
		        $('.view_reviewer_table').html("");
		        $('#view_task_table').html('');
		        var html = "";
		        for(var i=0;i<data[2].length;i++){
		        	var check = "status_add";
					if(data[2][i].status == 'submitted'){
					var status_ch = '<span style="position:initial" class="badge bg-primary">Submitted</span>';
					check = 'status_sub';
					}else if(data[2][i].status == 'approved'){
					var status_ch = '<span style="position:initial" class="badge bg-success">Approved</span>';
					}else if(data[2][i].status == 'disputed'){
					var status_ch = '<span style="position:initial" class="badge bg-important">Disputed</span>';
					}if(data[2][i].task_status == 'To Do' || data[2][i].task_status == 'In Progress'){
					var status_tsk = '<span style="position:initial" class="badge bg-primary">'+data[2][i].task_status+'</span>';
					}else if(data[2][i].task_status == 'To Verify' || data[2][i].task_status == 'To Test' || data[2][i].task_status == 'Awaiting Approval'){
					var status_tsk = '<span style="position:initial" class="badge bg-warning">'+data[2][i].task_status+'</span>';
					}else if(data[2][i].task_status == 'Completed'){
					var status_tsk = '<span style="position:initial" class="badge bg-success">'+data[2][i].task_status+'</span>';
					}
		        	html += '<tr>';
		        	html += '<td style="padding:14px"><input type="hidden" name="employee_ids1[]" id='+data[2][i].task_id+' value="'+data[2][i].employee_id+'"><input type="checkbox" name="mailcheckbox[]" class="mail-checkbox '+check+'" id="'+data[2][i].from_time+'" value="'+data[2][i].date+'"></td><td>'+data[2][i].task_number+'</td><td>'+data[2][i].employee_name+'</td><td>'+data[2][i].task_name+'</td><td>'+data[2][i].from_time+'</td><td>'+data[2][i].to_time+'</td><td>'+data[2][i].hours+'</td><td class="'+data[2][i].task_id+'replace_process_rev1">'+status_tsk+'</td><td class="'+data[2][i].task_id+'replace_process_rev">'+status_ch+'</td><td>'+data[2][i].comments+'</td><td><a style="cursor:pointer;float:left;" title="Edit" data-toggle="modal" href="#status_ReviewModel" class="status_edit" data-status="'+data[2][i].task_status+'" data-per="'+data[2][i].percent_done+'" data-id="'+data[2][i].task_id+'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a></td>';
		        	html += '</tr>';
		        	
		        }
		     $('#claims-sample_table').dataTable().fnDestroy();  
		     $('.view_reviewer_table').html(html);
		     EditableTable.init();
		     $(".status_add").attr('disabled',true);
	        }else if(data[0] == "error"){
	        	$('#editable-table_review').hide();
		        $('#view_task_table').html('<div class="text-center">No Tasks to Review</div>');
	        }
	    }
	});

	
}

function getTasks(projectId){
	$.ajax({
		dataType: 'html',
		type: "POST",
	    url: "php/project.handle.php",
	    cache: false,
	    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!viewTaskByIdandEmp");?>',projectId:projectId},	
	    success: function (data) {
	        data = JSON.parse(data);
	        $('.task_view_all').html("");
	        if(data[0] == "success"){
	var html = "";
	var phtml = "";
    for(var i=0;i<data[2].length;i++){
        
    	if(data[2][i].task_status == 'inisiated' || data[2][i].task_status == 'To Do' || data[2][i].task_status == 'In Progress'){
			var status_tsk = '<span style="position:initial"  class="badge bg-primary">'+data[2][i].task_status+'</span>';
			}else if(data[2][i].task_status == 'To Verify' || data[2][i].task_status == 'To Test' || data[2][i].task_status == 'Awaiting Approval'){
			var status_tsk = '<span style="position:initial"  class="badge bg-warning">'+data[2][i].task_status+'</span>';
			}else if(data[2][i].task_status == 'Completed'){
			var status_tsk = '<span style="position:initial"  class="badge bg-success">'+data[2][i].task_status+'</span>';
			}
    		phtml += '<li>';
    		phtml += '<a><i class="fa fa-group"></i> '+data[2][i].employee_name+'</a>';
    		phtml += '</li>';
        	html += '<tr>';
        	html += '<td>'+data[2][i].task_number+'</td><td>'+data[2][i].employee_name+'</td><td id="'+data[2][i].task_number+'replace_process">'+status_tsk+'</td><td>'+data[2][i].task_name+'</td><td>'+data[2][i].task_start_date+'</td><td>'+data[2][i].task_end_date+'</td><td>'+data[2][i].hours_task+'</td><td>'+data[2][i].reviewer+'</td><td><a style="cursor:pointer;float:left;" title="Delete" class="status_delete" data-pro='+data[2][i].project_id+' data-emp='+data[2][i].employee_id+' data-id='+data[2][i].task_id+' ><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a></td>'; 
        	html += '</tr>';
    }
   
    	$('#project-sample_table').dataTable().fnDestroy();
    	$('.task_view_all').html(html);
    	$('.team_emp').html(phtml);
    projectTable.init();
    
	        }else if(data[0] == "error"){
	        	$('.task_view_all').html('<tr><td colspan="8"><input type="hidden" name="asd" id="asd" value="NO"/>No Task Created</td></tr>');
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
	   "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
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
	var projectTable = function () {

	    return {

	        //main function to initiate the module
	        init: function () {
	        
	var oTable =  $('#project-sample_table ').dataTable( {
		 "aLengthMenu": [
	                   [5, 15, 20, -1],
	                   [5, 15, 20, "All"] // change per page values here
	               ],
	   "iDisplayLength": 5,
	   "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
	   "bProcessing": true,
	   
	     });
	        	
	$('#project-sample_table_wrapper .dataTables_filter').html('<div class="input-group">\
	       <input class="form-control medium" id="searchInputs" type="text">\
	       <span class="input-group-btn">\
	         <button class="btn btn-white" id="searchFilters" type="button">Search</button>\
	       </span>\
	       <span class="input-group-btn">\
	         <button class="btn btn-white" id="searchClears" type="button">Clear</button>\
	       </span>\
	   </div>');
	$('#project-sample_table_processing').css('text-align', 'center');
	//jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
	jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
	$('#searchInputs').on('keyup', function (e) {
	if (e.keyCode == 13) {
	oTable.fnFilter($(this).val());
	} else if (e.keyCode == 27) {
	$(this).parent().parent().find('input').val("");
	oTable.fnFilter("");
	}
	});
	$('#searchFilters').on('click', function () {

	oTable.fnFilter($(this).parent().parent().find('input').val());
	});
	$('#searchClears').on('click', function () {
	$(this).parent().parent().find('input').val("");
	oTable.fnFilter("");
	});
	    
	        }
	};

	} ();
	var taskTable = function () {

	    return {

	        //main function to initiate the module
	        init: function () {
	        
	var oTable =  $('#task-sample_table ').dataTable( {
		 "aLengthMenu": [
	                   [5, 15, 20, -1],
	                   [5, 15, 20, "All"] // change per page values here
	               ],
	   "iDisplayLength": 5,
	   "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
	   "bProcessing": true,
	   
	     });
	        	
	$('#task-sample_table_wrapper .dataTables_filter').html('<div class="input-group">\
	       <input class="form-control medium" id="searchInputs1" type="text">\
	       <span class="input-group-btn">\
	         <button class="btn btn-white" id="searchFilters1" type="button">Search</button>\
	       </span>\
	       <span class="input-group-btn">\
	         <button class="btn btn-white" id="searchClears1" type="button">Clear</button>\
	       </span>\
	   </div>');
	$('#task-sample_table_processing').css('text-align', 'center');
	//jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
	jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
	$('#searchInputs1').on('keyup', function (e) {
	if (e.keyCode == 13) {
	oTable.fnFilter($(this).val());
	} else if (e.keyCode == 27) {
	$(this).parent().parent().find('input').val("");
	oTable.fnFilter("");
	}
	});
	$('#searchFilters1').on('click', function () {

	oTable.fnFilter($(this).parent().parent().find('input').val());
	});
	$('#searchClears1').on('click', function () {
	$(this).parent().parent().find('input').val("");
	oTable.fnFilter("");
	});
	    
	        }
	};

	} ();
	$('#taskNumber').on('blur',function(e){
		if($(this).val() == ''){
		}else{
		e.preventDefault();
		$('#ajax_call').show();
		$.ajax({
			dataType: 'html',
			type: "POST",
		    url: "php/project.handle.php",
		    cache: false,
		    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getTaskNumber");?>',taskId:$('#taskNumber').val()},	
		    success: function (data) {
		        data = JSON.parse(data);
		        $('#ajax_call').hide();
		        if (data[0] == "success") {
		        	 $('.nm_not_found1').hide();
		 		    $('.alias1').removeClass('wrong');
		 		    $('.alias1').addClass('correct');
		        	}else if (data[0] == "error") {
		        		
		        		    $('.alias1').val('');
		                	$('.alias1').removeClass('correct');
		                    $('.alias1').addClass('wrong');
		                    $('.nm_not_found1').fadeIn("Fast");
		                    $('.nm_not_found1').html('Task Number Already Found Try Other Number');
		                    $('.nm_not_found1').fadeOut(3000);
		            	}
		    }
		});
		}
		});
	$('#cancel_task').click(function(e){
	e.preventDefault();
	$('.close_task').trigger('click');
	});
	function getAllTask(){

		$.ajax({
			dataType: 'html',
			type: "POST",
		    url: "php/project.handle.php",
		    cache: false,
		    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getTaskByEmp");?>'},	
		    success: function (data) {
		        data = JSON.parse(data);
		        $('.view_task_table').html("");
		        if(data[0] == "success"){
			        var html = "";
			        for(var i=0;i<data[2].length;i++){
			        	html += '<tr>';
			        	html += '<td>'+data[2][i].task_number+'</td><td>'+data[2][i].task_name+'</td><td>'+data[2][i].start_date+'</td><td>'+data[2][i].end_date+'</td><td>'+data[2][i].hours+'</td><td id="'+data[2][i].task_id+'replace_process_rev">'+data[2][i].status+'</td><td>'+data[2][i].priority+'</td>';
			        	html += '</tr>';
			        }
			     $('.view_task_table').append(html);
			   
			     taskTable.init();
			    
		        }else if(data[0] == "error"){
		        	
		        $('.view_task_table').html('<tr><td colspan="7">No Tasks Assigned</td></tr>');
		        }
		    }
		});
	}
	$(document).on('click','.status_delete',function(e){
		var taskId = $(this).data('id');
		var employeeId = $(this).data('emp');
		var projectId = $(this).data('pro');
		 BootstrapDialog.show({
            title:'Confirmation',
            message: 'Are Sure you want Delete This Task</strong>',
            buttons: [{
                label: 'Yes',
                cssClass: 'btn-sm btn-success',
                autospin: true,
                action: function(dialogRef){
                	 $.ajax({
		                    dataType: 'html',
		                    type: "POST",
		                    url: "php/project.handle.php",
		                    cache: false,
		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!deleteTask");?>',task_number:taskId,employee_id:employeeId},
		                    complete:function(){
		                    	 dialogRef.close();
		                      },
		                    success: function (data) {
		                    	data = JSON.parse(data);
		                        if (data[0] == "success") {
		                        	BootstrapDialog.alert('Successfully Deleted the Task');
		                        	getTasks(projectId);
	 		                   }else if (data[0] == "error") {
	                                    alert('Error in Deleting the Task');
	                                }
		                    },
		                      error:function(jqXHR, textStatus, errorThrown) {
		                    	  BootstrapDialog.alert('Error in Delete Your Map Claim : '+errorThrown+'' ); 
		                      },
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
	$('#view_table_form').on('submit',function(e){
		e.preventDefault();
	if($('input[name="status[]"]:checked').length != 0){
		var status = jQuery.map($(':checkbox[name=status\\[\\]]:checked'), function (n, i) {
		    return n.value;
		}).join(',');
		getReviewer(status);
	}else{
	}
	});
	$(document).on('click','#edit_show',function(e){
		$(this).hide();
   	  var data = $(this).attr('data-id');
   	  var status = $(this).attr('data-status');
   	  $('#fa_show').html('');
   	  $(this).closest('.bio-row').find('.value_column').show();
   	 $(document).on("click",".valued",function(e){
         $('#edit_show').show();
     	$(this).closest('.bio-row').find('.value_column').hide();
     	$('#fa_show').html(status);;
   });
   	  
	});
	$(document).on("click",".valued_tick",function(e){
  	  var alias = $(this).data('id');
        var daya = $("#"+alias).val();
        var thisd = this; 
        $(this).hide();
        $('.loader_away').show(); 
        $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/project.handle.php",
            cache: false,
            data: {projectId:alias,status:daya,act: '<?php echo base64_encode($_SESSION['company_id']."!updateProjectStatus");?>'},
            success: function (data) {
                data1 = JSON.parse(data);
                $('.loader_away').hide();
                $('.valued_tick').show();
                if (data1[0] == "success") {
                  $('#fa_show').html(daya);
              	  $(thisd).closest('.bio-row').find('.value_column').hide();
              	  $(thisd).closest('.bio-row').find('#edit_show').attr('data-status',daya);
              	  $('#edit_show').show();
                }
                else if(data1[0] == "error"){
              	  
                }
            }});
 	    });
	$('#reject_now').click(function(e){
		var Date = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
		    return n.value;
		}).join(',');
		var fromTime = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
		    return n.id;
		}).join(',');
		var employee_id = jQuery.map($('input[type="hidden"][name="employee_ids1[]"]'), function (n, i) {
		    return n.value;
		}).join(',');
		var status = jQuery.map($('input[type="hidden"][name="employee_ids1[]"]'), function (n, i) {
		    return n.id;
		}).join(',');
		BootstrapDialog.show({
			 title:'Confirmation',
				        message: 'Are Sure you want Reject?</strong>',
				        buttons: [{
				            label: 'Yes',
				            cssClass: 'btn-sm btn-success',
				            autospin: true,
				            action: function(dialogRef){
				$.ajax({
					dataType: 'html',
					type: "POST",
			        url: "php/project.handle.php",
			        cache: false,
			        data: {date:Date,fromTime:fromTime,employee_id:employee_id,act: '<?php echo base64_encode($_SESSION['company_id']."!rejectTimesheet");?>'},	
			        complete:function(){
			            dialogRef.close();
			         },
			        success: function (data) {
			            data = JSON.parse(data);
			            if(data[0] == "success"){
			            	BootstrapDialog.alert('Timesheet Reject Successfully');

			            	var array = status.toString().split(",");
			            				            	$.each(array,function(i){
			            				            		$('.'+array[i]+'replace_process_rev').html('<span class="badge bg-success" style="position:initial">Approved</span>');
			            				            	});
			            				            	getReviewer('disputed');
			            }else if(data[0] == "error"){

			            	BootstrapDialog.alert('Timesheet Failed To Reject');
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