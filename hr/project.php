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
      #teamMembers_chosen,#assignedTo_chosen,#projectId_chosen,#reviewer_chosen,#selectTask_chosen,#assignedTos_chosen,#assignedTo_up_chosen{
      width:100%!important;
      }.bio-graph-info {
    background: #fcfcfc;
}#ajax_loader_getmessages1{
display:block;
margin: 0 auto;
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
      $projectOnly = $project->selectAllProject();
     
      ?>
      
     <section id="main-content">
          <section class="wrapper site-min-height">
     <section class="panel">
     <header class="panel-heading tab-bg-dark-navy-blue">
                                      <ul class="nav nav-tabs " id="project_tabs" role="tablist">
                                          <li id="header_req_active">
                                              <a href="#Project" id="project_tab" data-toggle="tab" data-loaded=false data-title = "Project">
                                                  Project
                                              </a>
                                          </li>
                                         <li class="">
                                              <a href="#reviewers"  id="task_tab" data-toggle="tab" data-loaded=false data-title = "Reviewer">
                                                  Admin Tasks
                                              </a>
                                          </li>
                                      </ul>
                                  </header>
                                  <div class="panel-body">
                                   <div class="tab-content tasi-tab">
                                          <div class="tab-pane" id="Project">
                                          <header class="add_button_hide" >	
		   <div class="btn-group pull-right">
	      <button  type="button" class="btn btn-sm btn-info"  id="showhide"  style="margin-top: -32px;">
          <i class="fa fa-plus"></i> Add
          </button>
		</div>
	</header>
                                          <form class="form-horizontal" role="form" method="post" id="projectAddForm">
                                           <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!projectInsert");?>" />
                         				 
                                          
                                          <div class="panel-body" id="add-project-toggle" style="display:none;">
                                          <div class="col-lg-6">
                                               <div class="col-lg-12">
                                 <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Project Id</label>
                                        <div class="col-lg-9">
                                        <input class="form-control alias float_all" required type="text" id="projectIds" name="projectIds"  required />
                                       <img class="ajax_loader_inside" id="ajax_loader_inside" src="../img/input-spinner.gif" style="display: none;">
                                       <span class="nm_not_found" style="display: none;"></span>
                                       </div>
                                  </div></div>
                                  
                               <!-- <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Task Number</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" type="text" id="taskNumber" name="taskNumber"  required />
                                       </div>
                                   </div></div>  -->
                                  
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Title</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" type="text" required id="title" name="title"  required />
                                       </div>
                                   </div></div>
                                   
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Category</label>
                                        <div class="col-lg-9">
                                      <select name="type" id="type_proj" required class="form-control">
                                      <option value="no">Select Type</option>
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
                                        <textarea class="form-control"  id="description" required name="description" rows="3" required ></textarea>
                                       </div>
                                   </div></div>
                                
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Assigned To</label>
                                        <div class="col-lg-9">
                                        <select name="assignedTo[]" id="assignedTo" multiple required class="form-control validate[required]">
                                        <option value="" disabled="disabled">Select Employee</option>
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
                                      <label  class="col-lg-3 col-sm-3 control-label">Team Members</label>
                                        <div class="col-lg-9">
                                        <select name="teamMembers[]" id="teamMembers" multiple required class="form-control ">
                                        <option value="" disabled="disabled">Select Employee</option>
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
                                       <select name="priority" id="priority" required class="form-control">
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
                                        <input class="form-control" required type="text" id="start_date" name="start_date"  required />
                                       </div>
                                   </div></div>

 <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">End Date</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" required type="text" id="end_date" name="end_date"  required />
                                       </div>
                                   </div></div>

<div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Hours</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" required type="text" id="hours" name="hours"  required />
                                       </div>
                                   </div></div>
                                   
                                   </div>
                                    <div class="col-lg-12 ">
                                    <button type="button" id="cancel_project" class="btn btn-danger pull-right">Cancel</button>
                                    <button type="submit" id="submit_project" class="btn btn-success pull-right" style="margin-right:10px;">Submit</button>
                                    </div>
                                   </div>
                                   
                                          </form>
                                          <div class="panel-body" id="editable-project-table" >
                  <div class="space15"></div>                     
                          <div class="adv-table editable-table" >
                          	<section id="flip-scroll">
		 <table class="table table-striped table-hover  cf" id="claims-sample_table">
                           <thead class="cf">
                              <tr>
                              	<th style="padding:11px;"><input type="checkbox" class="mail-checkbox mail-group-checkbox"></th>
                                 <th>Project Num</th>
                                  <th>Project Title</th>
                                 <th>Status</th>
                                 <th>Last Updated</th>
                                 <th>Action</th>
                             </tr>
                           </thead>
                           <tbody class="project_fetch">
                             <tr><td colspan="5"><img src="../img/ajax-loader.gif" id="ajax_loader_getmessages"></td></tr>
                           </tbody>
                        </table>
                        </section>
                       </div>
                          </div>
                                   
                                          
                                          
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
                          	<div id="tableContent"></div>
		
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
                                         
                                         
                                           <img src="../img/ajax-loader.gif" id="ajax_loader_getmessages" style="display: none">
                                          <div class="project_system_details_err">
                                          </div>
                                         <div class="project_system_details" style="display:none;">
                  <div class="row">
                  <div class="col-md-12">
                      <section class="panel">
                          <div class="bio-graph-heading project-heading">
                          <div class="pull-left">
                         <a id="back_go" style="cursor:pointer"></a>
                          </div>
                              <strong class="project_subject"></strong>
                               
                          </div>
                          <div class="panel-body bio-graph-info">
                              <!--<h1>New Dashboard BS3 </h1>-->
                              <div class="row p-details">
                                  <div class="bio-row">
                                      <p><span class="bold">Project Category </span>: <span class="project_cate"></span> </p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span class="bold">Status </span>: <span class="project_status"></span></p>
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
                                     <p style="text-align: justify"><span class="bold"><b>Assigned Employee:</b></span>
                                     </p>
                                        <ul class="list-unstyled p-files assigned_emp">
                                  
                              </ul>
                                        
                                  </div>
                              </div>

                          </div>

                      </section>

                      <section class="panel">
                        <header class="panel-heading">
                          Tasks
                          <div class="pull-right" style="margin-bottom:10px;">
                          <div class="reply_fg">
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
                                          
                                          <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog" style="width:50%;">
                                  <div class="modal-content">
                                      
                                      <div class="modal-header"><button type="button" class="close close_task" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Edit Project</h4></div>
                                      <div class="modal-body">
                                      
                                      <form class="form-horizontal" role="form" method="post" id="projectUpdateForm">
                                           <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!projectUpdate");?>" />
                         				 
                                          
                                          <div class="panel-body" >
                                          <div class="col-lg-12">
                                               
                                        <input class="form-control alias float_all" required type="hidden" id="projectId_up" name="projectIds"   />
                                       
                                                                   
                              
                                  
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Title</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" type="text" required id="title_up" name="title"  required />
                                       </div>
                                   </div></div>
                                   
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Category</label>
                                        <div class="col-lg-9">
                                      <select name="type" id="type_proj_up" class="form-control">
                                      <option value="no">Select Type</option>
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
                                        <textarea class="form-control"  id="description_up" required name="description" rows="3" required ></textarea>
                                       </div>
                                   </div></div>
                                
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Assigned To</label>
                                        <div class="col-lg-9">
                                        <select name="assignedTo[]" id="assignedTo_up" multiple required class="form-control">
                                        <option value="no">Select Employee</option>
                                        <?php 
                                       foreach ($employeeOnly as $row){
                                      echo "<option value='".$row['employee_id']."'>".$row['employee_name']." [ ".$row['employee_id']." ]<br>"."</option>"; 
                                       }
                                       ?>
                                        </select>
                                       </div>
                                   </div></div>  
                                 
                                   </div>
                                   <div class="col-lg-12">
                                   <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Start Date</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" required type="text" id="start_date_up" name="start_date"  required />
                                       </div>
                                   </div></div>

 <div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">End Date</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" required type="text" id="end_date_up" name="end_date"  required />
                                       </div>
                                   </div></div>

<div class="col-lg-12">
                                   <div class="form-group">
                                      <label  class="col-lg-3 col-sm-3 control-label">Hours</label>
                                        <div class="col-lg-9">
                                        <input class="form-control" required type="text" id="hours_up" name="hours"  required />
                                       </div>
                                   </div></div>
                                   
                                   </div>
                                    <div class="col-lg-12 ">
                                    <button type="button" id="cancel_project_up" class="btn btn-danger pull-right">Cancel</button>
                                    <button type="submit" id="submit_project_up" class="btn btn-success pull-right" style="margin-right:10px;">Submit</button>
                                    </div>
                                   </div>
                                   
                                          </form>
                                      
                                      </div>
                                      </div>
                                      </div>
                                      </div>
                                      
                                          
                                          
                                          </div>
                                          
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
		  $('#project_tabs a[href="#Project"]').tab('show');
		  
		  } 

	  // Change hash for page-reload
	  $('#project_tabs a').on('shown.bs.tab', function (e) {
	      window.location.hash = e.target.hash;
	  })
	});


  $('#project_tabs').on('shown.bs.tab', function (e) {
	   // newly activated tab
	    window.scrollTo(0, 0);
	    $('#start_date,#end_date,#start_date_up,#end_date_up').datetimepicker({
	        format: 'DD/MM/YYYY'        
	    }); 	   
	    
	  if($(e.target).data('loaded') === false){

		  if($(e.target).data('title') == 'Project'){
			  var projectId = getParameterByName("projectId"); 
			  if(projectId){
				  $('#editable-project-table').hide();
				  $('#back_go').html('Back');
				  viewProject(projectId);
			  }
			  getProject();
			  
		  }
		  if($(e.target).data('title') === 'Reviewer'){  
			  getReviewer('submitted');
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
  $('#assignedTo,#projectId,#reviewer,#assignedTo_up,#teamMembers').chosen();
$('#showhide').click(function(e){

$('#add-project-toggle').toggle('show');
	
});
$('#cancel_project').click(function(e){

	$('#add-project-toggle').toggle('show');
		
	});

	

$.validator.addMethod("valueNotEquals", function(value, element, arg){
	  return arg != value;
	 }, "Value must not equal arg.");
$.validator.setDefaults({ ignore: ":hidden:not(select)" });

$('#projectAddForm').validate({
	
	  rules: {
		   type: { valueNotEquals: "no" },
		   assignedTo:"required",
		   teamMembers:"required"
		  },
		  messages: {
			type: { valueNotEquals: "Catgegory Field is Required" },
			
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
         	$('#submit_project').button('loading'); 
          },
          complete:function(){
        	$('#submit_project').button('reset');
          },	
success: function (data) {
   data = JSON.parse(data);
	if(data[0] == 'success'){
		$('#projectAddForm')[0].reset();
		$('#assignedTo').trigger("chosen:updated");
		$('#projectId').trigger("chosen:updated");
		$('#add-project-toggle').hide();
		BootstrapDialog.alert('Project Added Successfully');
		var said = "";
        var said = $('#asd').val() ;
		if(said == 'NO'){
     	   $('#asd').val('YES')
        }else{
        	$('#claims-sample_table').dataTable().fnDestroy();
        }
		getProject();
	}else{
		BootstrapDialog.alert('Project Added Failed');
	}

   }

});
		  }

	
});




function getProject(){

	 $.ajax({
    		dataType: 'html',
    		type: "POST",
            url: "php/project.handle.php",
            cache: false,
            data: {act: '<?php echo base64_encode($_SESSION['company_id']."!selectAllProject");?>'},	
            success: function (data) {
                data = JSON.parse(data);
               
               $('.project_fetch').html("");
                if (data[0] == "success") {
                	var html = "";
                    for(var i=0;i<data[2].length;i++){
                    	html +='<tr><td><input type="checkbox" class="mail-checkbox mail-group-checkbox"></td><td><a class="view_project" data-id='+data[2][i].projectId+' style="cursor:pointer;">'+data[2][i].projectId+'</a></td><td><a class="view_project" data-id='+data[2][i].projectId+' style="cursor:pointer;">'+data[2][i].project_title+'</a></td><td>'+data[2][i].status+'</td><td>'+data[2][i].last_updated+'</td><td><a style="cursor:pointer;" data-toggle="modal" href="#Modal" title="Edit" class="status_edit" data-id="'+data[2][i].projectId+'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a></td></tr>';
                    }
                    $('#claims-sample_table').dataTable().fnDestroy();
                    $('.project_fetch').html(html);
                	EditableTable.init();
                	}else if (data[0] == "error") {
                	$('.project_fetch').html('<tr><td colspan="7"><input type="hidden" name="asd" id="asd" value="NO"/>No Project Found</td></tr>');
                		
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

$('#projectIds').on('blur',function(e){
e.preventDefault();
if($('#projectIds').val() == ''){
}else{
$('#ajax_loader_inside').show();
$.ajax({
	dataType: 'html',
	type: "POST",
    url: "php/project.handle.php",
    cache: false,
    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getProjectId");?>',projectId:$('#projectIds').val()},	
    success: function (data) {
        data = JSON.parse(data);
        $('#ajax_loader_inside').hide();
        if (data[0] == "success") {
        	 $('.nm_not_found').hide();
 		    $('.alias').removeClass('wrong');
 		    $('.alias').addClass('correct');
        	}else if (data[0] == "error") {
        		
        		    $('.alias').val('');
                	$('.alias').removeClass('correct');
                    $('.alias').addClass('wrong');
                    $('.nm_not_found').fadeIn("Fast");
                    $('.nm_not_found').html('Project Id Already Found Try Other Id');
                    $('.nm_not_found').fadeOut(3000);
            	}
    }
});
}
});


$(document).on('click','.view_project',function(e){
e.preventDefault();
var projectId = $(this).data('id');
$('#editable-project-table').hide();
viewProject(projectId);
$('#back_go').html('Back');
});
$('#back_go').click(function(e){
	e.preventDefault();
	$('.project_system_details').hide();
	$('#editable-project-table').show();
});
$('#taskForm').on('submit',function(e){
	e.preventDefault();
	var projectId = $('#selectTask').val();
	viewProject(projectId);
	$('#back_go').html('');
});

function viewProject(projectId){
	$('#ajax_loader_getmessages').show();
	$.ajax({
		dataType: 'html',
		type: "POST",
	    url: "php/project.handle.php",
	    cache: false,
	    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!viewProjectById");?>',projectId:projectId},	
	    success: function (data) {
	        data = JSON.parse(data);
	        $('#ajax_loader_getmessages').hide();
	        $('.task_view_all,.project_system_details_err').html('');
	        $('.project_system_details').show();
	        if(data[0] == "success"){
	            $('.project_subject').html(data[2][0].project_title);
				$('.project_cate').html(data[2][0].project_catagory);
				$('.project_status').html(data[2][0].status);
				$('.project_created').html(data[2][0].creator);
				$('.project_start_date').html(data[2][0].start_date);
				$('.project_end_date').html(data[2][0].end_date);
				$('.project_priority').html(data[2][0].priority);
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
function getTasks(projectId){
	$.ajax({
		dataType: 'html',
		type: "POST",
	    url: "php/project.handle.php",
	    cache: false,
	    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!viewTaskById");?>',projectId:projectId},	
	    success: function (data) {
	        data = JSON.parse(data);
	        $('.task_view_all').html("");
	        if(data[0] == "success"){
	var html = "";
    for(var i=0;i<data[2].length;i++){
        	html += '<tr>';
        	html += '<td>'+data[2][i].task_number+'</td><td>'+data[2][i].employee_name+'</td><td id="'+data[2][i].task_number+'replace_process">'+data[2][i].task_status+'</td><td>'+data[2][i].task_name+'</td><td>'+data[2][i].task_start_date+'</td><td>'+data[2][i].task_end_date+'</td><td>'+data[2][i].hours_task+'</td><td>'+data[2][i].reviewer+'</td>'; 
        	html += '</tr>';
    }
    	$('#project-sample_table').dataTable().fnDestroy();
    	$('.task_view_all').html(html);
    projectTable.init();
	        }else if(data[0] == "error"){
	        	$('.task_view_all').html('<tr><td colspan="8"><input type="hidden" name="asd" id="asd" value="NO"/>No Task Created</td></tr>');
	        }
	    }
	});
}
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
$(document).on('click','.status_edit',function(e){
var projectId = $(this).data('id');
$.ajax({
	dataType: 'html',
	type: "POST",
    url: "php/project.handle.php",
    cache: false,
    data: {act: '<?php echo base64_encode($_SESSION['company_id']."!viewProjectById");?>',projectId:projectId},	
    success: function (data) {
        data = JSON.parse(data);
        $('#projectId_up').val(data[2][0].project_id);
        $('#title_up').val(data[2][0].project_title);
        $("#type_proj_up option[value='" + data[2][0].project_catagory + "']").prop("selected", true);
		$('#start_date_up').val(data[2][0].start_date);
		$('#end_date_up').val(data[2][0].end_date);
		$('#description_up').val(data[2][0].description);
		$('#hours_up').val(data[2][0].hours);
		for(var i=0;i<data[2].length;i++){
			
			$("#assignedTo_up option[value='" + data[2][i].employee_id + "']").prop("selected", true);
		}
		$("#assignedTo_up").trigger('chosen:updated');
    }
});
});
$('#cancel_project_up').click(function(e){
$('.close').trigger('click');
});
$('#projectUpdateForm').on('submit',function(e){
	e.preventDefault();
	$.ajax({
   	 	processData: false,
        contentType: false,
        type: "POST",
        url: "php/project.handle.php",
        cache: false,
        data:new FormData(this),
        beforeSend:function(){
         	$('#submit_project_up').button('loading'); 
          },
          complete:function(){
        	$('#submit_project_up').button('reset');
          },	
success: function (data) {
   data = JSON.parse(data);
	if(data[0] == 'success'){
		$('.close').trigger('click');
		BootstrapDialog.alert('Project Update Successfully');
		getProject();
	}else{
		BootstrapDialog.alert('Project Update Failed');
	}

   }

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
function getReviewer(status){
	$('#ajax_loader_getmessages1').show();
	$.ajax({
		dataType: 'html',
		type: "POST",
	    url: "php/project.handle.php",
	    cache: false,
	    data: {status:status,act: '<?php echo base64_encode($_SESSION['company_id']."!AdminviewReviewer");?>'},	
	    success: function (data) {
	        data = JSON.parse(data);
	        if(data[0] == "success"){
	        	$('#editable-table_review').show();
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
					}
		        	html += '<tr>';
		        	html += '<td style="padding:14px"><input type="hidden" name="employee_ids1[]"  value="'+data[2][i].employee_id+'"><input type="checkbox" name="mailcheckbox[]" class="mail-checkbox '+check+'" id="'+data[2][i].from_time+'" value="'+data[2][i].date+'"></td><td>'+data[2][i].employee_name+'</td><td>'+data[2][i].from_time+'</td><td>'+data[2][i].to_time+'</td><td>'+data[2][i].hours+'</td><td>'+status_ch+'</td><td>'+data[2][i].comments+'</td>';
		        	html += '</tr>';
		        }
		     // $('#review-sample_tables').dataTable().fnDestroy();  
		     $('#tableContent').html('').html('<img src="../img/ajax-loader.gif" id="ajax_loader_getmessages1"><table class="table table-striped table-hover  cf" id="fs"><thead><tr><th><input type="checkbox" class="mail-checkbox mail-group-checkbox"></th><th>Employee Name</th><th>From Time</th><th>To Time</th><th>Hours</th><th>Status</th><th>Comments</th></tr></thead><tbody class="view_reviewer_table">'+html+'</tbody></table>');
			  //  nonEditableTable.init(); 
			    $('#fs').dataTable( {
			   	 "aLengthMenu": [
			                      [5, 15, 20, -1],
			                      [5, 15, 20, "All"] // change per page values here
			                  ],
			      "iDisplayLength": 5,
			      "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
			      "bProcessing": true,
			    });
		     $(".status_add").attr('disabled',true);
	        }else if(data[0] == "error"){
	        	$('#editable-table_review').hide();
		        $('#view_task_table').html('<div class="text-center">No Tasks to Review</div>');
	        }
	        $('#ajax_loader_getmessages1').hide();
		 }
	});

	
}
var nonEditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
     
var oTable =  $('#review-sample_tables').dataTable( {
	 "aLengthMenu": [
                   [5, 15, 20, -1],
                   [5, 15, 20, "All"] // change per page values here
               ],
   "iDisplayLength": 5,
   "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
   "bProcessing": true,
   
     });
$('#review-sample_tables_wrapper .dataTables_filter').html('<div class="input-group">\
       <input class="form-control medium" id="searchInput" type="text">\
       <span class="input-group-btn">\
         <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
       </span>\
       <span class="input-group-btn">\
         <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
       </span>\
   </div>');
$('#review-sample_tables_processing').css('text-align', 'center');
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
		                     	getReviewer('approved');
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
		            	BootstrapDialog.alert('Status Approved Successfully');
		                     	getReviewer('disputed');
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
$(".mail-group-checkbox").change(function (e) {
	e.preventDefault();
	$(".status_sub").not(':disabled').prop('checked', $(this).prop("checked"));
	
});
</script>
</body>
</html>