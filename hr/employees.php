<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Employee List</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->

<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/jquery.Jcrop.min.css" rel="stylesheet" />
<link href="../css/slidebars.css" rel="stylesheet">
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<style>.viewImageResize {
	border-radius: 5px;
	width: 81%;
	margin-right: 10px;
}

.loader_1 {
	height: 86%;
	width: 94%;
}

#slab_opt_chosen, #employee_bank_name_chosen, #department_id_chosen,
	#employee_nationality1_chosen, #employee_id_proof_type1_chosen,
	#job_status_chosen, #dept_chosen, #designation_id_chosen,
	#branch_loc_chosen, #b_group_chosen, #employee_blood_group1_chosen,
	#emp_sslc_board1_chosen, #emp_hsc_board1_chosen, #branch_id_chosen,#shift_id1_chosen {
	width: 100% !important;
}


#desig1_chosen, #dept1_chosen, #branch_loc1_chosen, #team1_chosen, #status_name1_chosen,
	#payment_mode1_chosen {
	width: 100% !important;
}


#payment_mode1_chosen,#new_desig1_chosen,#new_dept1_chosen,#new_branch1_chosen,#new_team1_chosen,#new_status1_chosen,#new_shift1_chosen {
	width: 100% !important;
}

.panel-heading {
	min-height:50px;
}

.ajax_loader {
	margin: 0px auto;
	display: block;
}

.profile-nav .user-heading {
	background: #00BCD4;
}

.bio-graph-heading {
	background: #29B6F6;
}

.profile-nav ul>li>a:hover, .profile-nav ul>li>a:focus, .profile-nav ul li.active  a
	{
	border-left: 5px solid #00BCD4;
}
.media-body >h5>b{
   max-height: 14px; !important;
}

.inActive{
	background-color: transparent; 
	color: #FCB322;
 	border: 1px solid #FCB322;
	padding: 0px 2px 0px 2px; 
	display: inline-block;
	font-size: 13px;
	position: absolute; 
	margin:5px;
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
include_once (dirname ( __DIR__ ) . "/include/config.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/session.class.php");
Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
$allowColums = "";
foreach ( $allowDeducArray ['A'] as $key ) {
	$allowColums .= $key ['pay_structure_id'] . ",";
}
Session::newInstance ()->_setMiscPayParams ();
$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
?>
          <?php
										
include_once (__DIR__ . "/header.php");
										$allowances_values = "empty";
										?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
         <?php include_once (__DIR__."/sideMenu.php");?>
         </aside>
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
              <?php
														require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
														require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
														
														$employee = new Employee ();
														$employee->conn = $conn;
														?>
		<section class="panel">											
			<header class="panel-heading row">
			<div class="pull-left col-md-9" id="groupText"></div>
     			<div class="btn-group pull-right col-md-2">
        				<a href="#">
							<button id="showhideFilter" type="button"
								class="btn btn-sm btn-success choose_group">
								<i class="fa fa-filter"></i> More Filter
							</button>
						</a>
					    <a href="../hr/createEmployee.php" target="foo()" title="Add Employee">
							<button type="button" class="btn btn-sm btn-info">
								<i class="fa fa-plus" aria-hidden="true"></i> Add
							</button>
						</a>
				</div>
			</header>
			<div class="panel-body group_catagory displayHide" id="group_catagory ">
					<form class="form-horizontal" role="form" method="post">
					 <div class="col-lg-12 panel-body">
					 	<div class = "col-lg-12">
					 		<div class="form-group row">
										<div class="col-lg-2 ">
											<label class="control-label">Show InActive Employees</label>&emsp;&emsp;
										</div>
										<div class="col-lg-2">
											<input class="in_active" id="in_active" name="inActive" value="0" title="inactive" type="checkbox">
										</div>
										
											
								</div>
								<div class="form-group row">
										<div class="col-lg-2 ">
											<label class="control-label">Show Surrogate Users</label>&emsp;&emsp;
										</div>
										<div class="col-lg-2">
											<input class="is_surrogate" id="is_surrogate" name="is_surrogate" value="1" title="SurrogateUsers" type="checkbox">
										</div>
								</div>
					 		<div class=form-group>
					 			<label class="col-lg-2 control-label">For
					 			</label>
					 			<div class="col-lg-10">
		 							<div class="btn-group gr-buttons" data-toggle="buttons">
										<label for="Department" class="btn btn-default active"><input name="groupkey" id="Department"  value="D" type="radio"> Department
										</label>
										<label for="Designation" class="btn btn-default groupkey_cat "><input name="groupkey" id="Designation" value="F" type="radio"> Designation</label>
										<label for="Branch" class="btn btn-default groupkey_cat "> <input name="groupkey" id="Branch" value="B" type="radio"> Branch</label>
										<label for="Shift" class="btn btn-default groupkey_cat"> <input name="groupkey" id="Shift" value="S" type="radio"> Shift</label>
										<label for="Team" class="btn btn-default groupkey_cat"> <input name="groupkey" id="Team" value="T" type="radio"> Team</label>
									</div>
							</div>
					 	</div>
					 <div class="form-group department_select displayHide">
					 <input type="hidden" class="groupkey">
					 	<label class="col-lg-2 control-label pull-left">
					 			Department
					 		</label>
					 	<div class="col-lg-10">
									<select class="form-control" id="Department_chosenId" data-placeholder="Select Department" name="filter_Department_selected[]" >
									
									<?php  
												$stmt = mysqli_prepare ( $conn, "SELECT distinct c.department_name,c.department_id
                      											   FROM company_departments c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.department_id= c.department_id
                            								       INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1
                      		                                       WHERE c.enabled=1");
								   				$result = mysqli_stmt_execute ( $stmt );
								   				mysqli_stmt_bind_result ( $stmt, $department_name, $department_id );
								 			
												 while ( mysqli_stmt_fetch ( $stmt ) ) {
													echo "<option value='" . $department_id . "'>" . $department_name .  "</option>";
												}?> 
												
									</select>
						</div>
						 <input type="hidden" class=groupValues>
					</div>
					 <div class="form-group designation_select displayHide">
					 <input type="hidden" class="groupkey">
					 	<label class="col-lg-2 control-label pull-left">
					 			Designation
					 		</label>
					 	<div class="col-lg-10">
									<select class="form-control" id="Designation_chosenId" data-placeholder="Select Designation" name="filter_Designation_selected[]" >
									 <?php  $stmt = mysqli_prepare ( $conn, "SELECT distinct c.designation_name,c.designation_id
                      											   FROM company_designations c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.designation_id= c.designation_id
                            									   INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1
                      		                                       WHERE c.enabled=1" );
										$result = mysqli_stmt_execute ( $stmt );
										mysqli_stmt_bind_result ( $stmt, $designation_name, $designation_id );
										while ( mysqli_stmt_fetch ( $stmt ) ) {
											echo  "<option value='" . $designation_id . "'>" . $designation_name . "</option>";
									}?>
									</select>
						</div>
						 <input type="hidden" class=groupValues>
					</div>
					 <div class="form-group branch_select displayHide">
					 <input type="hidden" class="groupkey">
					 	<label class="col-lg-2 control-label pull-left">
					 			Branch
					 		</label>
					 	<div class="col-lg-10">
									<select class="form-control" id="Branch_chosenId" data-placeholder="Select Branch" name="filter_Branch_selected[]" >
									 <?php  $stmt = mysqli_prepare ( $conn,"SELECT distinct c.branch_name,c.branch_id
											        FROM company_branch c
											        INNER JOIN employee_work_details w
												    ON w.branch_id= c.branch_id
											        INNER JOIN payroll_preview_temp pp
											        ON pp.employee_id = w.employee_id AND pp.is_processed!=1
											        WHERE c.enabled=1" );
									$result = mysqli_stmt_execute ( $stmt );
									mysqli_stmt_bind_result ( $stmt, $branch_name, $branch_id );
									while ( mysqli_stmt_fetch ( $stmt ) ) {
										echo "<option value='" . $branch_id . "'>" . $branch_name . " </option>";
									}?>
									</select>
						</div>
						 <input type="hidden" class=groupValues>
					</div>
					 <div class="form-group shift_select displayHide">
					 <input type="hidden" class="groupkey">
					 	<label class="col-lg-2 control-label pull-left">
					 			Shift
					 		</label>
					 	<div class="col-lg-10">
									<select class="form-control" id="Shift_chosenId" data-placeholder="Select Shift" name="filter_Shift_selected[]" >
									 <?php  $stmt = mysqli_prepare ( $conn,"SELECT distinct c.shift_id,c.shift_name
                      											   FROM company_shifts c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.shift_id= c.shift_id
                                                                   INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1
                      		                                       WHERE c.enabled=1");
										$$result = mysqli_stmt_execute ( $stmt );
												mysqli_stmt_bind_result ( $stmt,  $shift_id,$shift_name);
										while ( mysqli_stmt_fetch ( $stmt ) ) {
											 $shiftValue=($shift_id=='SH00001')?'SH00001,Nil,':$shift_id;
											echo "<option value='" . $shiftValue . "'>" . $shift_name . " </option>";
										}?>
									</select>
						</div>
						 <input type="hidden" class=groupValues>
					</div>
					 <div class="form-group team_select displayHide">
					 <input type="hidden" class="groupkey">
					 	<label class="col-lg-2 control-label pull-left">
					 			Team
					 		</label>
					 	<div class="col-lg-10">
									<select class="form-control" id="Team_chosenId" data-placeholder="Select Team" name="filter_Team_selected[]" >
									 <?php  $stmt = mysqli_prepare ( $conn,"SELECT distinct c.team_id,c.team_name
                      											   FROM company_team c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.team_id= c.team_id
                                                                   INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1
                      		                                       WHERE c.enabled=1");
										$$result = mysqli_stmt_execute ( $stmt );
												mysqli_stmt_bind_result ( $stmt,  $team_id,$team_name);
										while ( mysqli_stmt_fetch ( $stmt ) ) {
											
											echo "<option value='" . $team_id . "'>" . $team_name . "  <br>" . "</option>";
										}?>
									</select>
						</div>
						 <input type="hidden" class=groupValues>
					</div>
					<div class="col-lg-6">
					<button type="button" class="btn btn-sm btn-success submit_group_val pull-right" style="margin-top:10px;">
								 Submit
						</button>
					</div>
					
				</div>
				
					 	
			</div>
		    </form>
			</div>
       </section>
                <div class="modal fade" id="myModal" tabindex="-1"
					role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">

							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close"
									type="button">&times;</button>
								<h4 class="modal-title">Proof Image</h4>
							</div>
							<div class="modal-body">

								<div class="fileupload-new thumbnail">
									<img id="preview_imagemodel" style="width: 100%; height: 100%"
										src="http://www.placehold.it/672x920/EFEFEF/AAAAAA&amp;text=no+image"
										alt="Employee Image">
								</div>

							</div>
							<div class="modal-footer">
							<a id="proof_download" class="btn btn-success btn-sm" download> 
								<i class="fa fa-download"></i> Download</a>
								<button data-dismiss="modal" class="btn btn-default"
									type="button">Close</button>
	
							</div>
						</div>
					</div>
				</div>
				<!-- page end-->
				<!-- page start-->
				<div class="emp_list1" id="empList">
					<div class="alphabet">
						<ul class="directory-list">
							<li><a class="letter" href="#"><i class="fa fa-refresh"></i></a></li>
							<li><a class="letter" href="#">a</a></li>
							<li><a class="letter" href="#">b</a></li>
							<li><a class="letter" href="#">c</a></li>
							<li><a class="letter" href="#">d</a></li>
							<li><a class="letter" href="#">e</a></li>
							<li><a class="letter" href="#">f</a></li>
							<li><a class="letter" href="#">g</a></li>
							<li><a class="letter" href="#">h</a></li>
							<li><a class="letter" href="#">i</a></li>
							<li><a class="letter" href="#">j</a></li>
							<li><a class="letter" href="#">k</a></li>
							<li><a class="letter" href="#">l</a></li>
							<li><a class="letter" href="#">m</a></li>
							<li><a class="letter" href="#">n</a></li>
							<li><a class="letter" href="#">o</a></li>
							<li><a class="letter" href="#">p</a></li>
							<li><a class="letter" href="#">q</a></li>
							<li><a class="letter" href="#">r</a></li>
							<li><a class="letter" href="#">s</a></li>
							<li><a class="letter" href="#">t</a></li>
							<li><a class="letter" href="#">u</a></li>
							<li><a class="letter" href="#">v</a></li>
							<li><a class="letter" href="#">w</a></li>
							<li><a class="letter" href="#">x</a></li>
							<li><a class="letter" href="#">y</a></li>
							<li><a class="letter" href="#">z</a></li>
							<li><input type="hidden" id="startlimit" name="startlimit"
								value="12"> <input type="hidden" id="rep_man-id"
								name="rep_man-id"> <input type="text"
								class="form-control search letter" id="employee_id-id"
								name="employee_id" autocomplete="off"
								style="margin-top: -18px; background-color: white; margin-bottom: -11px"
								required /> <input type="hidden" id="it_employee-id"></li>
						</ul>
					</div>
					<div class="directory-info-row">
						<div class="row">
						<div id="searchLoader" style="height:100%;width:98%;"></div>
						<div id="well"class="col-md-12 col-sm-12 pull-right "></div>
							<div id="empView"></div>
							<div class="col-md-12 col-sm-3">

								<div class="text-center show_more">
									<a id="show_more" data-search='' data-letter=''  style="cursor: pointer; display: none;">Show
										More</a>
								</div>
								<img src="../img/input-spinner.gif" class="ajax_loader"
									style="display: none">
							</div>
                       <?php
																							$result = $employee->getAllEmployeeCount ();
																							foreach ( $result as $row ) {
																								echo "<input type='hidden' name='all_employee_count' id='all_employee_count' value=" . $row ['employeeId'] . ">";
																							}
																							?>
                       <input type="hidden" id="emp_id_now">
                       <input type='hidden' id='currentsearchCount'>
						</div>
					</div>
				</div>

				<!-- page end-->
				<!--page Starts-->


				<!---page Ends-->

				<div id="wrapper"></div>





			</section>
		</section>
		<!--main content end-->
		<!--footer start--
	   <!--footer end-->
	</section>
	<?php include_once (__DIR__."/footer.php");?>
   

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
	<script src="../js/ImageTools.js"></script>
	<!--script for this page only-->
	<script type="text/javascript" src="../js/jquery.Jcrop.min.js"></script>
	<script src="../js/summarydata.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script src="../js/jspdf.min.js"></script>
	<script src="../js/html2canvas.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">

	 	
     $(document).ready(function() {
         $('.choose_group').removeClass('displayHide');
    	 $('#startlimit').val(12);
    	  flag=0;
    	  removeHash();
         var employeeId='<?php echo isset($_REQUEST['employeeID'])?$_REQUEST['employeeID']:"";?>';
    		
    		if(employeeId){
        		  $.ajax({
                     dataType: 'html',
                     type: "POST",
                     url: "employee_sub.php",
                     beforeSend: function () {
                    	 $(".loader_1,.ctc_detail_loader_sub").loading(true);
                     },
                     success: function (data) {
                      $('#wrapper').html(data);
                      $( "div" ).removeClass( "loader_1" )
                			 $(".emp_list1").hide();
                             $(".emp_image_view,#mainBack").show();
                		     check_flag(employeeId);
                		     $(".loader_1,.ctc_detail_loader_sub").loading(false);
                            
                		  }
                     }); 
        		
        			  groupEmp(0,12,'','','','');
        		 
        		  if($('#currentsearchCount').val()> 12 || $('#all_employee_count').val() > 12){
						$("#show_more").show();			
  				 }
    			}else{
    				 groupEmp(0,12,'','','','');
    				 if($('#currentsearchCount').val()> 12 || $('#all_employee_count').val()> 12){
							$("#show_more").show();			
        				 }
        			}
	       
    	//design for list 

    	});

    
   	
     $(document).on('click',".in_active",function(){
			if($(this).prop("checked")==true)
				$('.in_active').val(1);
			else 
				$('.in_active').val(0);
	});


    $(document).on('click',".is_surrogate",function(){
			if($(this).prop("checked")==true)
				$('.is_surrogate').val('-1');
			else 
				$('.is_surrogate').val('1');
	});

  	$(document).on('click','.gr-buttons',function(){
  	   var groupKey = $('.active').find('input[name="groupkey"]').val();
	   $('.groupkey').val(groupKey);
	   if( groupKey =='D'){
		    $('.designation_select,.branch_select,.shift_select,.team_select').addClass('displayHide');
	  	  	 $('.department_select').removeClass('displayHide');
	  	  	$('#Department_chosenId').attr('multiple','multiple');
	 	    $("#Department_chosenId").chosen();
	    }else if(groupKey =='F'){
		    $('.department_select,.branch_select,.shift_select,.team_select').addClass('displayHide');
		    $('.designation_select').removeClass('displayHide');
	  	  	$('#Designation_chosenId').attr('multiple','multiple');
	 	    $("#Designation_chosenId").chosen();
		   }else if(groupKey =='B'){
			    $('.department_select,.designation_select,.shift_select,.team_select').addClass('displayHide');
			    $('.branch_select').removeClass('displayHide');
		  	  	$('#Branch_chosenId').attr('multiple','multiple');
		 	    $("#Branch_chosenId").chosen();
		   }else if(groupKey =='S'){
			    $('.department_select,.designation_select,.branch_select,.team_select').addClass('displayHide');
			    $('.shift_select').removeClass('displayHide');
		  	  	$('#Shift_chosenId').attr('multiple','multiple');
		 	    $("#Shift_chosenId").chosen();
			   }else if(groupKey =='T'){
				   $('.department_select,.designation_select,.branch_select,.shift_select').addClass('displayHide');
				    $('.team_select').removeClass('displayHide');
			  	  	$('#Team_chosenId').attr('multiple','multiple');
			 	    $("#Team_chosenId").chosen();
				   }
	   
  	  	});

  	$(document).on('click',".submit_group_val",function(){
  	  	$('.emp_catagory').show();
  	    $(".group_catagory,#showhideFilter").hide();
  	   var groupKey = $('.groupkey').val();
  	   groupText = "Showing Employees ";
  	  
  	  if( groupKey == 'D'){
  				var groupValues= $("#Department_chosenId").chosen().val();
		  	  	var selMulti = $.map($("#Department_chosenId option:selected"), function (el, i) {
		 	         return $(el).text();
		 	     });
		 	   var group_dept= selMulti.join(",");
  	  		   if(group_dept!=''){
  	  		   groupText += "from Department(s): "+ group_dept +' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
		 	   }else{
		 		  groupText +=' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
			 	   }
  	  	}else if(groupKey == 'F'){
  	  	        var groupValues= $("#Designation_chosenId").chosen().val();
  	  			var selMulti = $.map($("#Designation_chosenId option:selected"), function (el, i) {
	        			 return $(el).text();
	     		});
	  			 var group_desig= selMulti.join(",");
  	  			if(group_desig!=''){
  	  			groupText += "from Designation(s): "+ group_desig +' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
	  			 }else{
	  				groupText +=' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
		  			 }
  	  	  	}else if(groupKey == 'B'){
  	  	  		var groupValues= $("#Branch_chosenId").chosen().val();
  	  			var selMulti = $.map($("#Branch_chosenId option:selected"), function (el, i) {
			 			return $(el).text();
				});
				 var group_branch= selMulti.join(",");
					if(group_branch!=''){
					groupText += "from Branch(s): "+ group_branch +' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
				 }else{
					 groupText +=' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
					 }
  	  	  	 }else if(groupKey == 'S'){
  	  	  		 var groupValues= $("#Shift_chosenId").chosen().val();
  	  	  		 var selMulti = $.map($("#Shift_chosenId option:selected"), function (el, i) {
	 			   			return $(el).text();
					});
		 		var group_shift= selMulti.join(",");
  	  	  	  	if(group_shift!=''){
  	  	  	  			groupText += "from Shift(s): "+ group_shift +' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
		 		}else{
		 			groupText +=' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
			 		}
			}else if(groupKey == 'T'){
		  	  	  var groupValues= $("#Team_chosenId").chosen().val();
		  	  	  var selMulti = $.map($("#Team_chosenId option:selected"), function (el, i) {
 			   				return $(el).text();
				    });
	 		var group_team= selMulti.join(",");
	 		   if(group_team!=''){
	 		    groupText += "from Team(s): "+ group_team +' (<a class=change_field href="#" style="font-size:12px;"> Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All </a>)';
	 			}else{
	 				groupText += ' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
		 			}	
		 }else{
		  		 groupText += ' (<a class=change_field href="#" style="font-size:12px;">Change </a>/<a href="../hr/employees.php" style="font-size:12px;"> Show All</a>)';
			  	}

  	
		
		$("#groupText").html(groupText);
  	  $('.groupValues').val(groupValues);
  	  var groupKey = $(".groupkey").val();
 	  var groupValues = $(".groupValues").val();
  	  $("#empView").html('');
  	  groupEmp(0,12,'','',groupKey,groupValues);
  	  	
  	  	});

    
     $( "#showhideFilter" ).click(function() {
    	 $('#showhideFilter').removeClass('displayHide');
    	 $(".group_catagory").toggle();
    	});
 	
 	$(document).on('click','.change_field',function(e) {
 	 	$(".group_catagory").toggle();
 	 });
    	

		$(document).on('click','#show_more',function(e){
		e.preventDefault();
			var skey=$(this).data('search');
			var lkey=$(this).data('letter');
        			  var  startlimit= $('#startlimit').val();
        			  var intervalLimit = 12;
        			  $('#startlimit').val(parseInt(startlimit) + parseInt(12));
        				var groupKey = $(".groupkey").val();
        			 	var groupValues = $(".groupValues").val();
    				  groupEmp(startlimit,intervalLimit,skey,lkey,groupKey,groupValues);
         			 });
	    
        		 	function groupEmp(startlimit,intervalLimit,searchKey,letterKey,groupKey,groupValues){
            		 	//grouping
            		 	
        		 	    $('.ajax_loader').show();
        		        $('#well').html('');
        		        var inActive = $('.in_active').val();
        		        var surrogate = $('.is_surrogate').val();
        		        //var inActive = ?php echo isset($_REQUEST['inActive'])?$_REQUEST['inActive']:0;?>;
        		         $.ajax({
        		             dataType: 'html',
        		             type: "POST",
        		             url: "php/employee.handle.php",
        		             data:{act:'<?php echo base64_encode($_SESSION['company_id']."!getEmployeeView");?>',start:startlimit,
            		             interval:intervalLimit,searchKey:searchKey,letterKey:letterKey,inActive:inActive,groupKey:groupKey,groupValues:groupValues,is_surrogate_users:surrogate},
        		             cache: false,
        		             beforeSend: function () {
        	                     $("#searchLoader").loading(true);
        	                     },
        		             success: function (data) {
        		            	 $('.ajax_loader').hide();
        		                 var json_obj = JSON.parse(data);
        		                  if(json_obj[2].length>0){
            		           if(json_obj[2].length<12){
        		                	$('#show_more').hide();
        		                	}else{
        		                	$('#show_more').show();
            		                	}
        		                 $('#currentsearchCount').val(json_obj[2].length);
        		                 for (i = 0; i < json_obj[2].length; i++) {
        	                      if(json_obj[2][i].employee_image=='Nil')
        									{
        									if(json_obj[2][i].employee_gender=='Male'){
        											var image='../img/default-avatar_men.png'; 
        						                   	 }else{
        						                   		var image='../img/default-avatar_women.png'; 
        						                       	 } 
        									}else{
            									
        									var image=json_obj[2][i].employee_image;
        									}
									
									if(json_obj[2][i].team_id=='TM0001'){
										var team_name ='';
										}else{
											var team_name = json_obj[2][i].team_name +'<br>';
											}
	if(json_obj[2][i].dateofexit!=null){
									var dateOfExit1 = json_obj[2][i].dateofexit;
   									var d = dateOfExit1.split('-')[2];
   									var m = dateOfExit1.split('-')[1];
   									var y = dateOfExit1.split('-')[0];
   									var dateOfExit = d+'-'+m+'-'+y ;
								}else{
					var dateOfExit ='';
					}		
									if((json_obj[2][i].enabled==1)){ // || (json_obj[2][i].enabled== 1,-1)
										if(json_obj[2][i].status==1){
		   									var fingerPrint = '../img/finger-print.png';
										
										$("#empView").append('<div class="col-md-4 col-sm-4 view1" ><em></em><div class="panel" style="min-height:190px;"><div style="cursor: pointer;" class="get_emp_id panel-body " id="' + json_obj[2][i].employee_id + '"><div class="media"><div class="empImageDiv"><a class="pull-left"><img class="media-object viewImageResize" src=' + image + ' alt="employee_image" ></a></div><div class="media-body"><h4 style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;" title="'+ json_obj[2][i].employee_name + ' [ ' + json_obj[2][i].employee_id + ' ]">' + json_obj[2][i].employee_name + '<span class="text-muted small"> [ ' + json_obj[2][i].employee_id + ' ]</span></h4><address style="margin-bottom:0px;height:110px;"><p style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;max-heigth:114.7px;"><span title="'+ json_obj[2][i].designation_name + '"><strong>' + json_obj[2][i].designation_name + '</strong></span><br>'+team_name +''+ json_obj[2][i].department_name + '<br>' + json_obj[2][i].branch_name + '<br><span title='+ json_obj[2][i].employee_email +'><i class="fa fa-envelope-o"></i>&nbsp;' + json_obj[2][i].employee_email + '</span><br><span title='+ json_obj[2][i].employee_mobile + '><i class="fa fa-phone" style="font-size: 14px;"></i> ' + json_obj[2][i].employee_mobile + '.' +  '</p></span></address></div><div class=""><b class=" pull-right" style="max-height:17px;"><span class="finger_print"><img src='+fingerPrint+' title="enrolled" style="height:14px;width:14px;">&nbsp;|</span>&nbsp;<a title="view gross salary" class="view_gross"><i class="fa fa-eye"></i></a>&nbsp;|&nbsp;<a title= Joined&nbsp;on&nbsp;:'+ json_obj[2][i].employee_doj +'  > ' + json_obj[2][i].experience + '</a><span class="viewGross"  data-gross="'+json_obj[2][i].employee_salary_amount+'"></span></b></div></div></div></div></div>');
										}else{
											$("#empView").append('<div class="col-md-4 col-sm-4 view1" ><em></em><div class="panel " style="min-height:190px;"><div style="cursor: pointer;" class="get_emp_id panel-body " id="' + json_obj[2][i].employee_id + '"><div class="media"><div class="empImageDiv"><a class="pull-left"><img class="media-object viewImageResize" src=' + image + ' alt="employee_image" ></a></div><div class="media-body"><h4 style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;" title="'+ json_obj[2][i].employee_name + ' [ ' + json_obj[2][i].employee_id + ' ]">' + json_obj[2][i].employee_name + '<span class="text-muted small"> [ ' + json_obj[2][i].employee_id + ' ]</span></h4><address style="margin-bottom:0px;height:110px;"><p style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;max-heigth:114.7px;"><span title="'+ json_obj[2][i].designation_name + '"><strong>' + json_obj[2][i].designation_name + '</strong></span><br>' + team_name + ''  + json_obj[2][i].department_name + '<br>' + json_obj[2][i].branch_name + '<br><span title='+ json_obj[2][i].employee_email +'><i class="fa fa-envelope-o"></i>&nbsp;' + json_obj[2][i].employee_email + '</span><br><span title='+ json_obj[2][i].employee_mobile + '><i class="fa fa-phone" style="font-size: 14px;"></i> ' + json_obj[2][i].employee_mobile + '.' +  '</p></span></address></div></div><div class=""><b class=" pull-right" style="max-height:17px;"><a title="view gross salary" class="view_gross"><i class="fa fa-eye"></i></a>&nbsp;|&nbsp;<a title= Joined&nbsp;on&nbsp;:'+ json_obj[2][i].employee_doj +'  > ' + json_obj[2][i].experience + '</a><span class="viewGross"  data-gross="'+json_obj[2][i].employee_salary_amount+'"></span></b></div></div></div></div></div>');
											
											}						    
												$('.view_gross').on("click" ,function(){
													var n=new Number($(this).parent().find('.viewGross').data('gross'));
													var value=n.toLocaleString();
												    $(this).html('<span class="fa fa-inr"><span>&nbsp;'+value);
												});
												$('.view_gross').on("mouseleave", function () {
													 $(this).html('<i class="fa fa-eye"></i>');
												});
											
										}else if((json_obj[2][i].enabled==-1)){ // || (json_obj[2][i].enabled== 1,-1)
										if(json_obj[2][i].status==1){
		   									var fingerPrint = '../img/finger-print.png';
										
										$("#empView").append('<div class="col-md-4 col-sm-4 view1" ><em></em><div class="panel" style="min-height:190px;"><div style="cursor: pointer;" class="get_emp_id panel-body " id="' + json_obj[2][i].employee_id + '"><div class="media"><div class="empImageDiv"><a class="pull-left"><img class="media-object viewImageResize" src=' + image + ' alt="employee_image" ></a></div><div class="media-body"><h4 style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;" title="'+ json_obj[2][i].employee_name + ' [ ' + json_obj[2][i].employee_id + ' ]">' + json_obj[2][i].employee_name + '<span class="text-muted small"> [ ' + json_obj[2][i].employee_id + ' ]</span></h4><address style="margin-bottom:0px;height:110px;"><p style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;max-heigth:114.7px;"><span title="'+ json_obj[2][i].designation_name + '"><strong>' + json_obj[2][i].designation_name + '</strong></span><br>'+team_name +''+ json_obj[2][i].department_name + '<br>' + json_obj[2][i].branch_name + '<br><span title='+ json_obj[2][i].employee_email +'><i class="fa fa-envelope-o"></i>&nbsp;' + json_obj[2][i].employee_email + '</span><br><span title='+ json_obj[2][i].employee_mobile + '><i class="fa fa-phone" style="font-size: 14px;"></i> ' + json_obj[2][i].employee_mobile + '.' +  '</p></span></address></div><div class=""><b class=" pull-right" style="max-height:17px;"><span class="finger_print"><img src='+fingerPrint+' title="enrolled" style="height:14px;width:14px;">&nbsp;|</span>&nbsp;<a title="view gross salary" class="view_gross"><i class="fa fa-eye"></i></a>&nbsp;|&nbsp;<a title= Joined&nbsp;on&nbsp;:'+ json_obj[2][i].employee_doj +'  > ' + json_obj[2][i].experience + '</a><span class="viewGross"  data-gross="'+json_obj[2][i].employee_salary_amount+'"></span></b></div></div></div></div></div>');
										}else{
											$("#empView").append('<div class="col-md-4 col-sm-4 view1" ><em></em><div class="panel " style="min-height:190px;"><div style="cursor: pointer;" class="get_emp_id panel-body " id="' + json_obj[2][i].employee_id + '"><div class="media"><div class="empImageDiv"><a class="pull-left"><img class="media-object viewImageResize" src=' + image + ' alt="employee_image" ></a></div><div class="media-body"><h4 style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;" title="'+ json_obj[2][i].employee_name + ' [ ' + json_obj[2][i].employee_id + ' ]">' + json_obj[2][i].employee_name + '<span class="text-muted small"> [ ' + json_obj[2][i].employee_id + ' ]</span></h4><address style="margin-bottom:0px;height:110px;"><p style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;max-heigth:114.7px;"><span title="'+ json_obj[2][i].designation_name + '"><strong>' + json_obj[2][i].designation_name + '</strong></span><br>' + team_name + ''  + json_obj[2][i].department_name + '<br>' + json_obj[2][i].branch_name + '<br><span title='+ json_obj[2][i].employee_email +'><i class="fa fa-envelope-o"></i>&nbsp;' + json_obj[2][i].employee_email + '</span><br><span title='+ json_obj[2][i].employee_mobile + '><i class="fa fa-phone" style="font-size: 14px;"></i> ' + json_obj[2][i].employee_mobile + '.' +  '</p></span></address></div></div><div class=""><b class=" pull-right" style="max-height:17px;"><a title="view gross salary" class="view_gross"><i class="fa fa-eye"></i></a>&nbsp;|&nbsp;<a title= Joined&nbsp;on&nbsp;:'+ json_obj[2][i].employee_doj +'  > ' + json_obj[2][i].experience + '</a><span class="viewGross"  data-gross="'+json_obj[2][i].employee_salary_amount+'"></span></b></div></div></div></div></div>');
											
											}						    
												$('.view_gross').on("click" ,function(){
													var n=new Number($(this).parent().find('.viewGross').data('gross'));
													var value=n.toLocaleString();
												    $(this).html('<span class="fa fa-inr"><span>&nbsp;'+value);
												});
												$('.view_gross').on("mouseleave", function () {
													 $(this).html('<i class="fa fa-eye"></i>');
												});
											
										}else if(json_obj[2][i].enabled== 0,1){
											if(json_obj[2][i].status==1){
			   									var fingerPrint = '../img/finger-print.png';
			   									
										$("#empView").append('<div class="col-md-4 col-sm-4 view1" ><em></em><div class="panel" style="min-height:190px;"><div style="cursor: pointer;" class="get_emp_id panel-body " id="' + json_obj[2][i].employee_id + '" ><div class="media"><div class="empImageDiv"><a class="pull-left"><img class="media-object viewImageResize" src=' + image + ' alt="employee_image" ></a></div><div class="media-body"><h4 title="'+ json_obj[2][i].employee_name +' [ ' + json_obj[2][i].employee_id +' ]" style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;">' + json_obj[2][i].employee_name + '<span class="text-muted small" > [ ' + json_obj[2][i].employee_id + ' ]</span></h4><address style="margin-bottom:0px;height:110px;"><p style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;"> <span title="'+ json_obj[2][i].designation_name + '"><strong >' + json_obj[2][i].designation_name + '</strong></span><br>' + team_name + '' + json_obj[2][i].department_name + '<br>' + json_obj[2][i].branch_name +'<br><span title='+ json_obj[2][i].employee_email + '><i class="fa fa-envelope-o"></i> ' + json_obj[2][i].employee_email +  '</span><br><span title=' + json_obj[2][i].employee_mobile + '><i class="fa fa-phone" style="font-size: 14px;"></i> ' + json_obj[2][i].employee_mobile + '.' + '</span></p></address></div></div><div class=""><span class="pull-left"><label  class="inActive" title="Exited on : ' + dateOfExit + '">Inactive</label></span><b class="pull-right"  style="max-height:17px;"><span class="finger_print"><img src='+fingerPrint+' title="enrolled" style="height:14px;width:14px;">&nbsp;|</span>&nbsp;<a title="view gross salary" class="view_gross"><i class="fa fa-eye"></i></a>&nbsp;|&nbsp;<a title= Joined&nbsp;on&nbsp;:'+ json_obj[2][i].employee_doj +'  > ' + json_obj[2][i].experience + '</a><span class="viewGross" data-gross="'+json_obj[2][i].employee_salary_amount+'"></span></b></div></div></div></div></div>');  						
											}else{
												$("#empView").append('<div class="col-md-4 col-sm-4 view1" ><em></em><div class="panel" style="min-height:190px;"><div style="cursor: pointer;" class="get_emp_id panel-body " id="' + json_obj[2][i].employee_id + '"><div class="media"><div class="empImageDiv"><a class="pull-left"><img class="media-object viewImageResize" src=' + image + ' alt="employee_image" ></a></div><div class="media-body"><h4 title="'+ json_obj[2][i].employee_name +' [ ' + json_obj[2][i].employee_id +' ]" style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;">' + json_obj[2][i].employee_name + '<span class="text-muted small" > [ ' + json_obj[2][i].employee_id + ' ]</span></h4><address style="margin-bottom:0px;height:110px;"><p style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;"><span title="'+ json_obj[2][i].designation_name + '"><strong>' + json_obj[2][i].designation_name + '</strong></span><br>' + team_name + ''+ json_obj[2][i].department_name + '<br>' + json_obj[2][i].branch_name +'<br><span title='+ json_obj[2][i].employee_email + '><i class="fa fa-envelope-o"></i> ' + json_obj[2][i].employee_email +  '</span><br><span title=' + json_obj[2][i].employee_mobile + '><i class="fa fa-phone" style="font-size: 14px;"></i> ' + json_obj[2][i].employee_mobile + '.' +'</span></p></address></div></div><div class=""><label class="inActive" title="Exited on : ' + dateOfExit + '">Inactive</label><b class="pull-right"  style="max-height:17px;">&nbsp;<a title="view gross salary" class="view_gross"><i class="fa fa-eye"></i></a>&nbsp;|&nbsp;<a title= Joined&nbsp;on&nbsp;:'+ json_obj[2][i].employee_doj +'  > ' + json_obj[2][i].experience + '</a><span class="viewGross" data-gross="'+json_obj[2][i].employee_salary_amount+'"></span></b></div></div></div></div></div>'); 	
												}
											$('.view_gross').on("click" ,function(){
													var n=new Number($(this).parent().find('.viewGross').data('gross'));
													var value=n.toLocaleString();
												    $(this).html('<span class="fa fa-inr"><span>&nbsp;'+value);
												});
												$('.view_gross').on("mouseleave", function () {
													 $(this).html('<i class="fa fa-eye"></i>');
												});
										}
									
									
        		                 }
        		             }else{
        		            	 $("#empView").html('');
        		            	 $('#show_more').hide();
        		            	 $('#well').html('No data Found');
            		             }
        		               $("#searchLoader").loading(false);
        		             } 
        		         });
        	    		 }
        	
											
     $("a.letter").click(function () {
    	 var data=$(this).html();
    	 if(data=='<i class="fa fa-refresh"></i>'){
    		 $("#empView").html('');
    		 $('#startlimit').val(12);
    		 var groupKey = $(".groupkey").val();
    	 	 var groupValues = $(".groupValues").val();
    	 	 $('#show_more').data('letter','').data('search','');
    		groupEmp(0,12,'','',groupKey,groupValues);
        }else{
        	$('#startlimit').val(12);
        	$('#show_more').data('letter','').data('search','');
         $("#empView").html('');
         var groupKey = $(".groupkey").val();
 	 	var groupValues = $(".groupValues").val();
         $('#show_more').data('letter',data); //setter
         groupEmp(0,12,'',data,groupKey,groupValues);
     	
    	}
     });
     var timeoutId = 0;
     $('#employee_id-id').on('keyup', function (e) {
         e.preventDefault();
         	$("#empView").html('');
         	$('#show_more').data('letter','').data('search','');
         	clearTimeout(timeoutId); // doesn't matter if it's 0
         	timeoutId = setTimeout(getFilteredResultCount, 500);
       });

     function getFilteredResultCount() {
    	 $('#startlimit').val(12);
    	 $('#show_more').data('letter','').data('search','');
  	     var data=$('#employee_id-id').val();
  	     var groupKey = $(".groupkey").val();
	 	 var groupValues = $(".groupValues").val();
     	 groupEmp(0,12,data,'',groupKey,groupValues);
    	 $('#show_more').data('search',data); //setter
    	}
     
     /*EmpVIew Div Click Function Events Starts*/
      
        $('#empView').on('click', '.get_emp_id', function () {
            $('.panel-heading').hide();
        	 employee_id = $(this).attr("id");
        	 window.history.pushState( {} , '', '?employeeID='+employee_id+'' );
        	$('#emp_id_now').val(employee_id);
        	 var thisd = $(this);
        	$(this).closest('.view1').find("em").addClass('loader_1');
          	  if(flag==0){
            	 $.ajax({
                     dataType: 'html',
                     type: "POST",
                     url: "employee_sub.php",
                     beforeSend: function () {
                     $(".loader_1,.ctc_detail_loader_sub").loading(true);
                     },
                    success: function (data) {
                   $('#wrapper').html(data);
                       $(this).closest('.view1').find("em").removeClass('loader_1');
                			 $( "div" ).removeClass( "loader_1" )
                			 $(".emp_list1").hide();
                             $(".emp_image_view,#mainBack").show();
                            check_flag(employee_id);
                           $(".loader_1,.ctc_detail_loader_sub").loading(false);
                     }
                 }); 
    	        flag=1;
                   } else{
             $(".emp_list1").hide();
                         $(".emp_image_view,#mainBack").show();
                          var url =  window.location.hash.substr(1);
                		 
                		   if(url == 'work_history' || url == 'work_detail' || url == 'work_proof'){
                			   work_tabs();
                		   }else if(url == 'salary_detail'){
                    		   $('.ctc_detail_loader_sub').loading(true);
                			   ctc_tabs();
                		   }else{  
                			   check_flag(employee_id);
                			   $('#leave_account_tabs a[href="#personal"]').tab('show')
                    		   }
                		   }
          
            });

         

         function  check_flag(employee_id){
              $('input[name="employee_marital_status"]').click(function () {
             	  if ($(this).attr("value") == "Single") {
                         $(".spouse_name_hide ").hide('slow');
                     }else{
             		$(".spouse_name_hide ").show('slow');
             		}     });
	
					  employee_fill(employee_id);
      	    }

         function employee_fill(employee_id) {
        	 $('.personal_detail_loader,.ul_loader').loading(true);
        	 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "  php/employee.handle.php",
                 cache: false,
                 data: { 'act':'<?php echo base64_encode($_SESSION['company_id']."!getEmployeePersonelDetails");?>','employee_id': employee_id},
                 beforeSend: function () {
                	 $(".loader,.ctc_detail_loader_sub").loading(true);
                 },
                 success: function (data) {
                     $("#mainBack").show();
                   
                     var json_obj = $.parseJSON(data); //parse JSON
               
                     $("#employee_sub").val(json_obj[2][0].employee_id);
                     $(".emp_new,#emp_id_now").val(json_obj[2][0].employee_id);
                   //  $('#personal_tabs').click();
                     $('#emp_offer_letter_proof').prop('src', '../compDat/' + json_obj[2][0].emp_offer_letter_proof);
                     if(json_obj[2][0].employee_image=='Nil')
						{
                    	 if(json_obj[2][0].employee_gender=='Male'){
                        	  $('#round_preview_image').prop('src', '../img/default-avatar_men.png');
                         }else{
                        		 $('#round_preview_image').prop('src', '../img/default-avatar_women.png');
                         } 	 
						}else{
						 $('#preview_image_').prop('src',json_obj[2][0].employee_image);

					 var c=document.getElementById("preview_image");
						    var ctx=c.getContext("2d");
						    var img=document.getElementById("preview_image_");
						    ctx.drawImage(img,10,10);
						 $('#round_preview_image').prop('src',json_obj[2][0].employee_image);
					  }

			
                	 if(json_obj[2][0].employee_gender=='Male'){
                		        $("input[name=employee_gender][value=" + json_obj[2][0].employee_gender + "]").prop('checked', true);
                                $('#gender1').click(); 
								}else if(json_obj[2][0].employee_gender=='Female'){
							    $("input[name=employee_gender][value=" + json_obj[2][0].employee_gender + "]").prop('checked', true);
			                    $('#gender2').click(); 
		                   		} else{
		                   		$("input[name=employee_gender][value=" + json_obj[2][0].employee_gender + "]").prop('checked', true);
		                        $('#gender3').click(); 
			                    }

                	 $('h4#empIdShow').html(json_obj[2][0].employee_id );
                	 $('.empIdShow').html(json_obj[2][0].employee_id );
                     $('h4#emp_name').html(json_obj[2][0].employee_name+' '+json_obj[2][0].employee_lastname);
                     $('.employeeName').html(json_obj[2][0].employee_name+' '+json_obj[2][0].employee_lastname+'['+json_obj[2][0].employee_id+']');
                     $("#employee_blood_group1 option[value='"+json_obj[2][0].employee_blood_group+ "']").prop("selected", "selected").trigger('chosen:updated');
                     $("#employee_nationality1 option[value='"+ json_obj[2][0].employee_nationality+ "']").prop("selected", "selected").trigger('chosen:updated');
                     $("#employee_id_proof_type1 option[value='"+ json_obj[2][0].employee_id_proof_type + "']").prop("selected", "selected").trigger('chosen:updated');
                     $("#emp_sslc_board1 option[value='" + json_obj[2][0].emp_sslc_board + "']").prop("selected", "selected").trigger('chosen:updated');
                     $("#emp_hsc_board1 option[value='" + json_obj[2][0].emp_hsc_board + "']").prop("selected", "selected").trigger('chosen:updated');

                     if(json_obj[2][0].employee_bank_name!=null){
                         try{
                         $("#employee_bank_name option[value='" + json_obj[2][0].employee_bank_name.replace('_','') + "']").prop("selected", "selected").trigger('chosen:updated');
                         }catch (e) {
                        	   // statements to handle any exceptions
                        	  console.log('Bank Name is Not valid') // pass exception object to error handler
                        	}
                        }
                     
                     if(json_obj[2][0].employee_bank_name!=null)
                      $('#employee_bank_name1').val(json_obj[2][0].employee_bank_name.replace(/_/g, " "));
                     
                      if (json_obj[2][0].employee_marital_status == 'Single') {
                         $("input[name=employee_marital_status][value=" + json_obj[2][0].employee_marital_status + "]").prop('checked', true);
                         $('#employee_marital_status1').click();
                      }else {
                         $('#employee_spouse_name').val(json_obj[2][0].employee_spouse_name);
                         $("input[name=employee_marital_status][value=" + json_obj[2][0].employee_marital_status + "]").prop('checked', true);
                         $('#employee_marital_status2').click();
                     }
                     if (json_obj[2][0].employee_pt_adddress == '1') {
                         $("input[name=employee_pt_adddress][value=" + json_obj[2][0].employee_pt_adddress + "]").prop('checked', true);
                     } else {
                         $("input[name=employee_pt_adddress][value=" + json_obj[2][0].employee_pt_adddress + "]").prop('checked', true);
                         $(".per_add").prop("readonly", false);

                     }
                     if (json_obj[2][0].employee_international == '1') {
                         $("input[name=employee_international][value=" + json_obj[2][0].employee_international + "]").prop('checked', true);
                     } else {
                         $("input[name=employee_international][value=" + json_obj[2][0].employee_international + "]").prop('checked', true);

                     }
                    
                    
                    
                     $("input[name=employee_pc][value=" + json_obj[2][0].employee_pc + "]").prop('checked', true);
                     // $("#storeInformation").html(details.d);
              
                    if (json_obj[2][0].id !== null) {
                         //payroll Runned
                         $('#payrollCheck').val(1);
                         $('.emp_edit7,#deletebutton,#enrollbutton,.help-texts').hide();
                     } else {
                    	$('#payrollCheck').val(0);
                         $('.emp_edit7').show();
                         $('.emp_edit5,#deletebutton,#enrollbutton,.help-texts').show();
                         $('.help-texts').html('<div class="helpblock0">'+
                                 '<div class="alert" role="alert">'+
                                 '<div class="alert alert-info alert-dismissible fade in" role="alert">'+
                                 '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding-top: 10px;">'+
                                 '<span aria-hidden="true">&times;</span></button>'+
                                 '<p ><i class="fa fa-caret-right" ></i> &nbsp; Deleting a employee will delete all of his records. If you&apos;ve added employee wrongly perform this.</p>'+
                                 '<p ><i class="fa fa-caret-right" ></i> &nbsp; Editing DOJ requires a "Fore Preview" while running the payroll. If ignored it will consider the past doj.</p></div></div></div>');
                     }
                     if(json_obj[2][0].enabled == 0)
                         $('.emp_edit1,.emp_edit2,.emp_edit3,.emp_edit4,.emp_edit5').hide();

                     if (json_obj[2][0].dateofexit !== '0000-00-00') {
                         $('#dateofexithide').show();
                     }else{
                         $('#dateofexithide').hide();
                     }

                     if(json_obj[2][0].employee_personal_mobile==""){
                    	 $('#employee_personal_mobile').addClass('hide');
                         $('.pmobile-emp').removeClass('hide');
                         }else{
                       	    $('#employee_personal_mobile').html(json_obj[2][0].employee_personal_mobile);
                             }
                     
                     if(json_obj[2][0].employee_mobile==""){
                    	 $('#employee_mobile').addClass('hide');
                         $('.omobile-emp').removeClass('hide');
                         }else{
                       	    $('#employee_mobile').html(json_obj[2][0].employee_mobile);
                             }
                     

                     if(json_obj[2][0].father_mobile ==""){
                      // console.log($('#father_mobile').attr('-moz-appearance', 'textfield'));
                         $('#father_mobile').addClass('hide');
                         $('.fmobile-emp').removeClass('hide');
                         }else{
                        	 $('#father_mobile').html(json_obj[2][0].father_mobile);
                             }
                     

                     if(json_obj[2][0].mother_mobile==""){
                    	 $('#emp_mother_mobile').addClass('hide');
                         $('.mmobile-emp').removeClass('hide');
                         }else{
                       	    $('#emp_mother_mobile').html(json_obj[2][0].mother_mobile);
                             }
                     
                     $.each(json_obj[2][0], function (k, v) {
                         //display the key and value pair
                          if(k=='employee_pan_proof' || k=='employee_aadhaar_proof' || k=='employee_bank_proof'
                            	 || k=='employee_id_proof'|| k=='employee_sslc_proof' || k=='employee_ug_proof' 
                                	 || k=='employee_hsc_proof' || k=='employee_pg_proof'){
                            $('#' + k).val(v);
                          if($('#'+k).val()!=='Nil' && $('#'+k).val()!==''){
                        	   	  $('#'+k).parent().removeClass('btn-danger');
                        	   	 $('#'+k).parent().addClass('btn-primary');
                        	   	  }else{
                        	   		  $('#'+k).parent().removeClass('btn-danger btn-primary');
                             	   	 $('#'+k).parent().addClass('btn-danger');
                             	   	 }
                         }else{
                        	 $('#' + k).val(v);
                        	 }
                     });
                     if(json_obj[2][0].emp_country == 'Nil')
                         $("#emp_country").val("India");
                     


                     if (json_obj[2][0].employee_international == '1') {
                         $('#employee_international').val("Yes");
                     } else {
                         $('#employee_international').val("No");
                     }

                     if (json_obj[2][0].employee_pc == '1') {
                         $('#employee_pc').val("Yes");
                     } else {
                         $('#employee_pc').val("No");
                     }
 
                     $('.personal_detail_loader,.ul_loader').loading(false);
                     $(".loader,.ctc_detail_loader_sub").loading(false);
                 }

             });
         }

         
         
         function  work_tabs(){
             var employee_id =  $('#emp_id_now').val();
             $('.work_detail_loader,.ul_loader').loading(true);
        	 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: { 'employeeId':employee_id,'act':'<?php echo base64_encode($_SESSION['company_id']."!workDetails");?>'},
                 success: function (data) {
                	 var json_obj = $.parseJSON(data);
                	 $('.work_detail_loader,.ul_loader').loading(false);
                		 for(var i=0;i<json_obj[2].length;i++){
                    	 if(json_obj[2][i].payment_mode_id){
                             $("#payment_mode1 option[value='" + json_obj[2][i].payment_mode_id + "']").prop("selected", "selected").trigger('chosen:updated');
                           }
                    	 if(json_obj[2][i].shift_id){
                        	  $("#shift_id1 option[value='" + json_obj[2][i].shift_id + "']").prop("selected", "selected").trigger('chosen:updated');
                           }
                        $('#designation_id1').attr('title','Effects From '+json_obj[2][i].design_effects_from);
                        $('#department_name').attr('title','Effects From '+json_obj[2][i].depart_effects_from);
                        $('#branch_name').attr('title','Effects From '+json_obj[2][i].branch_effects_from);
                    	 $("#status_name option[value='" + json_obj[2][i].status_id + "']").prop("selected", "selected").trigger('chosen:updated');
                         $("#new_desig1 option[value='" + json_obj[2][i].designation_id + "']").prop("selected", "selected").trigger('chosen:updated');
                         $("#new_dept1 option[value='" + json_obj[2][i].department_id + "']").prop("selected", "selected").trigger('chosen:updated');
                         $("#new_branch1 option[value='" + json_obj[2][i].branch_id + "']").prop("selected", "selected").trigger('chosen:updated');
                         $("#branch_name option[value='" + json_obj[2][i].branch_id + "']").prop("selected", "selected").trigger('chosen:updated');
                         $("#new_team1 option[value='" + json_obj[2][i].team_id + "']").prop("selected", "selected").trigger('chosen:updated');
                         $("#new_shift1 option[value='" + json_obj[2][i].shift_id + "']").prop("selected", "selected").trigger('chosen:updated');
                         $("#new_status1 option[value='" + json_obj[2][i].status_id + "']").prop("selected", "selected").trigger('chosen:updated');
 						
                         $(".table1 tbody").empty();
                         s = 1;
                         if (json_obj[2][i].off_ltr_issue_dt != 'Nil') {
                             $(".table1 tbody").append("<tr style='text-align:center'><td>" + s + "." + "</td><td>" + json_obj[2][i].off_ltr_issue_dt + "</td><td>" + json_obj[2][i].employee_doj + "</td><td>Offer Letter</td><td><div class='col-lg-6'><a class='btn btn-success view1' data-toggle='modal' href='#myModal' style='background-color: #41CAC0;border-color: #41CAC0;'><input type='hidden' class='form-control' id='' /><i class='fa fa-eye'></i></a></div><div class='col-lg-6'><a style='background-color: red; border-color: red;' type='button' class='btn btn-info delete'><i class='fa fa-trash-o'></i></a></div></td></tr></tr>");
                             s++;
                         }
                         if (json_obj[2][i].confirm_ltr_issue_dt != 'Nil') {
                             $(".table1 tbody").append("<tr style='text-align:center'><td>" + s + "." + "</td><td>" + json_obj[2][i].confirm_ltr_issue_dt + "</td><td>" + json_obj[2][i].employee_confirmation_date + "</td><td>Confirmation Letter</td><td><div class='col-lg-6'><a class='btn btn-success view1' data-toggle='modal' href='#myModal' style='background-color: #41CAC0;border-color: #41CAC0;'><input type='hidden' class='form-control' id='' /><i class='fa fa-eye'></i></a></div><div class='col-lg-6'><a style='background-color: red; border-color: red;' type='button' class='btn btn-info delete'><i class='fa fa-trash-o'></i></a></div></td></tr></tr>");
                             s++;
                         }

                         if(json_obj[2][i].status == '-1' || json_obj[2][i].status == '-3'){
                          	  $('#enrollEmp,#enrollbutton').hide();
                         }else{
                          	  $('#enrollEmp,#enrollbutton').show(); 
                         } 
                         
                         if (json_obj[2][i].contract_ltr_issue_dt != 'Nil') {
                             $(".table1 tbody").append("<tr style='text-align:center'><td>" + s + "." + "</td><td>" + json_obj[2][i].contract_ltr_issue_dt + "</td><td>" + json_obj[2][i].employee_doj + "</td><td>Contract Letter</td><td><div class='col-lg-6'><a class='btn btn-success view1' data-toggle='modal' href='#myModal' style='background-color: #41CAC0;border-color: #41CAC0;'><input type='hidden' class='form-control' id='' /><i class='fa fa-eye'></i></a></div><div class='col-lg-6'><a style='background-color: red; border-color: red;' type='button' class='btn btn-info delete'><i class='fa fa-trash-o'></i></a></div></td></tr></tr>");
                             s++;
                         }
                         
						
                         var pf_limt = json_obj[2][i].pf_limit==1?$('#pf_limit1').val('YES'):$('#pf_limit1').val('NO');
                         var pf_limt1 = json_obj[2][i].pf_limit==1?$("#limit").prop('checked', true):$("#limit").prop('checked', false);
                             $("#designation_id1").val(json_obj[2][i].designation_name);
                             $('#rep_man-id-e').val(json_obj[2][i].employee_reporting_person);                
                             $('#rep_man').val(json_obj[2][i].reporting_manager);
                            
                      $.each(json_obj[2][i], function (k, v) {                
                       $('#' + k).val(v);
                     });
                      $('.remove').removeClass('hide show');
                     	$('.remove').addClass('hide');
                    if(json_obj[2][i].company_name!=null){
                          $('.delete').empty();
                         var cName=json_obj[2][i].company_name.split(","); 
                         var To=json_obj[2][i].to.split(","); 
                         var From=json_obj[2][i].from.split(",");
                         if(json_obj[2][i].designation!=null){ 
                         		var designation=json_obj[2][i].designation.split(","); 
                        	 }else{
                        		 var designation='-';
                             }
                         
                         var ctc=json_obj[2][i].ctc.split(",");
                         
                         if(json_obj[2][i].prev_reporting_manager!=null){
                        		var rep_manager = json_obj[2][i].prev_reporting_manager.split(","); 
                        	}else{
                     	  		 var rep_manager = '-';
                            }
                         
						if(json_obj[2][i].location!=null){
                         var location = json_obj[2][i].location.split(","); 
						}else{
							var location = '-';
							}
						
                         if(json_obj[2][i].contact_email!=null){
                        	 var con_email = json_obj[2][i].contact_email.split(","); 
                      		}else{
                   	   		var con_email = '-';
                         	 }
    					
    					 var  j=0;     
    					 $('#addcompanyno').val(json_obj[2][i].company_name.split(",").length+1);
    					 $('.remove').addClass('show');
    					for (var i=1; i<= cName.length;i++){
    						$('#'+i+'_addCompanyContent').append('<div class="row"><div class="col-lg-12"><h5><b>Company-'+i+'</b></h5><hr style="margin-top: 0px;"><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Company Name</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_cName" value="'+cName[j]+'" style="background-color:#FFF;border:0px"  disabled /></div></div><div class="form-group"><label class="col-lg-5 control-label">Reporting Manager</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_reporting_manager" value="'+rep_manager[j]+'" style="background-color:#FFF;border:0px"  disabled /></div></div>	</div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Designation</label><div class="col-lg-7 "><input type="text" class="form-control" name="'+i+'_desig"  value="'+designation[j]+'" style="background-color:#FFF;border:0px"  disabled/></div></div><div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7 "><input type="number" class="form-control" name="'+i+'_Ctc"  value="'+ctc[j]+'" style="background-color:#FFF;border:0px"  disabled /></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Contact Email</label><div class="col-lg-7 "><input type="email" class="form-control" name="'+i+'_contact_email"  value="'+con_email[j]+'" style="background-color:#FFF;border:0px"  disabled/></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-5 control-label">Location</label><div class="col-lg-7 "><input class="form-control" name="'+i+'_location" value="'+location[j]+'" style="background-color:#FFF;border:0px"  disabled ></div></div></div><div class="col-lg-10"> <div class="form-group"><label class="control-label col-lg-3">Duration</label><div class="col-md-7"><div class="input-group input-large" data-date="13/07/2013" data-date-format="mm/dd/yyyy"><input class="form-control dpd1 period" name="'+i+'_From"  value="'+From[j]+'" style="background-color: #fff; border: 0px" disabled type="text"><span class="input-group-addon" style="background-color: #fff; border: 0px" disabled>To</span><input class="form-control dpd2 period" value="'+To[j]+'" name="'+i+'_To" style="background-color: #fff; border: 0px" disabled type="text"></div></div></div></div></div></div>');
    					 	$('#'+i+'_addCompanyContent').parent().append('<div id='+(Number(i)+1)+'_addCompanyContent class="delete"></div>');
    					j++;
    					}}else{
    					$('.exp').html('<span>No work experience found.</span>');
    					$('.remove').addClass('hide');
    					$('#addcompanyno').val('1');
    					$('.delete').empty();
    					}
    				}
                	
                 }
        	 });
        	}

         function work_proof_tabs(){
        	
     		var employeeId='<?php echo isset($_REQUEST['employeeID'])?$_REQUEST['employeeID']:''?>';
     		if(employeeId != ''){
     		  employee_id = employeeId;
     		}else{
     		  employee_id = $('#emp_id_now').val();
     	   }
     		
      	   $('.timeline').empty();
     		 $.ajax({
                 dataType: 'html',
                 type: "POST",
                 url: "php/employee.handle.php",
                 cache: false,
                 data: { 'employeeId':employee_id,'act':'<?php echo base64_encode($_SESSION['company_id']."!letterGeneration");?>'},
                 beforeSend: function () {
                    	$('.work_proof').addClass('loader'); 
                    },
                success: function (data) {
                	 var jsonobject = $.parseJSON(data);
                	//console.log(jsonobject);
                	
                	 var emp_id = jsonobject[2][0].employee_id;
                	 $(".emp_id").append(emp_id);
                	 // var html ='<section id="flip-scroll"><table class="table table-striped table-hover table-bordered" id="letters_view"><thead class="eff"><tr><th style="text-align: center">S.No</th><th style="text-align: center">With Effect From</th><th style="text-align: center">Particulars</th><th style="text-align: center">Action</th></tr></thead><tbody>';
                	// $('<div class="col-lg-12" > <button class="btn btn-sm btn-default pull-right" style="margin-left: 1%;margin-top:10px;" type="button" id="lifeCyclePDF"><i class="fa fa-file-pdf-o"></i> PDF</button><button type="button" class="pull-right btn btn-sm  btn-danger back_emp_letter" style="margin-top:10px;">Back</button></div>'+
                       $('<section class="panel"><div class="panel-body"><div class="text-center mbot30"><h3 class="timeline-title" style="margin-left: 100px;"><span Class="emp_name">'+jsonobject[2][0].employee_name+'</span>&apos;s Life Cycle</h3>'+
      					'<p class="t-info">The entire stages of Employment of <span Class="emp_name">'+jsonobject[2][0].employee_name+'</span> - <span class="emp_id"></span></p></div><div class="timeline"></div></section>').appendTo('.work_proof');
                		 
                 	 for (var i = 0, len = jsonobject[2].length; i < len; ++i) {
                 		 var alt =' ';
                       	 var arrow =' ';
                 		 if ((i % 2) == 0){
                 				var alt =' ';
                 				var arrow ='arrow';
                     		}else{
                     			var alt ='alt';
                     			var arrow ='arrow-alt';
                         		}		
                		 var letterName= jsonobject[2][i].letter;
                	
                		 $('.timeline-desk span a').css('text-transform','none');
                		if(letterName == 'Offer:Letter'){
                			 $('.timeline-desk span a').css('text-transform','none');
                    		$('.emp_id').html(jsonobject[2][i].action_id);
                    		
                    		 $('<article class="timeline-item '+alt+'"><div class="timeline-desk"><div class="panel" style="margin-right: 20px;"><div class="panel-body letter-welcome">'+
							         '<span class="'+arrow+'"></span><span class="timeline-icon blue"></span><span class="timeline-date">'+jsonobject[2][i].day+'</span>'+
							         '<h1 class="blue">Welcome Onboard..!!</h1><p style="margin-bottom:5px;"><a class="emp_name" href="#">'+jsonobject[2][i].employee_name+'</a> joined in as a <span><a href="#" class="light-green" style="text-transform:none">'+jsonobject[2][i].new_desig+'</a></span></p>'+
							         '<div class="letters"><div class="row col-lg-12"><span class="letters-offer" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="'+'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download");?>'+'"/>'+
	                           			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
	                           			'<input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
	                           			'<button id="transfer_downloads" class="btn btn-white btn-xs"> <i class="fa fa-file-text-o"></i>&emsp;Offer</button></form></span>'+
							          '<span class = "letters-welcome" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="'+'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download");?>'+'"/>'+
	                           			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
	                           			'<input type="hidden" name="letterName" id="letterName" value="Welcome:Letter"/>'+
	                           			'<button id="transfer_downloads" class="btn btn-white btn-xs"> <i class="fa fa-file-text-o"></i>&emsp;Welcome</button></form></span>'+
	                           			'<span class="letters-joining" style="float:left;margin-left:7px;">'+
								         '<form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="'+'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download");?>'+'"/>'+
		                           			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
		                           			'<input type="hidden" name="letterName" id="letterName" value="Joining:Letter"/>'+
		                           			'<button id="transfer_downloads" class="btn btn-white btn-xs"> <i class="fa fa-file-text-o"></i>&emsp;Joining</button></form></span>'+
							         '<span class = "letters-bankAcc-open" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="'+'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download");?>'+'"/>'+
	                           			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
	                           			'<input type="hidden" name="letterName" id="letterName" value="Bank Account Opening Letter"/>'+
							         '<button id="transfer_downloads" class="btn btn-white btn-xs"> <i class="fa fa-file-text-o"></i>&emsp;A/C Opening</button></form></span>'+
							       	 '</div><br><div class="row col-lg-12"><span class = "letters-bonafide" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="'+'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download");?>'+'"/>'+
	                           			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
	                           			'<input type="hidden" name="letterName" id="letterName" value="Bonafide"/>'+
	                           			'<button id="transfer_downloads" class="btn btn-white btn-xs"> <i class="fa fa-file-text-o"></i>&emsp;Bonafide</button></form></span>'+
							       	 '<span class = "letters-employeeInfo" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="'+'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download");?>'+'"/>'+
	                           			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
	                           			'<input type="hidden" name="letterName" id="letterName" value="Employee Information Sheet"/><button id="transfer_downloads" class="btn btn-white btn-xs"> <i class="fa fa-file-text-o"></i>&emsp;Employee Info</button></form></span>'+
							       	'<span class = "letters-joining-checklist" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="'+'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download");?>'+'"/>'+
                           			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                           			'<input type="hidden" name="letterName" id="letterName" value="New Joinee Checklist"/><button id="transfer_downloads" class="btn btn-white btn-xs"> <i class="fa fa-file-text-o"></i>&emsp;Joining Checklist</button></form></span>'+
							       	'</div></div></div></div> </div> </article>').appendTo('.timeline');
						  
                		} else if(letterName=='Offer:Confirmation'){
                			 $('<article class="timeline-item '+alt+'"><div class="timeline-desk"><div class="panel" style="margin-right: 20px;"><div class="panel-body letter-confirm" >'+
					         '<span class="'+arrow+'"></span><span class="timeline-icon purple"></span> <span class="timeline-date">'+jsonobject[2][i].day+'</span>'+
					         '<h1 class="purple">Confirmation</h1><p style="margin-bottom:5px;"><a class="emp_name" href="#">'+jsonobject[2][i].employee_name+'</a>&apos;s employement is confirmed</p>'+
					         '<form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                   			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                  			'<input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
                  			'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp; '+
                             'Confirmation</button></form></div></div></div></article>').appendTo('.timeline');
                		 
                		 }else if(letterName== 'Evaluation:Promotion'){
                    		$('<article class="timeline-item '+alt+'"><div class="timeline-desk"><div class="panel" style="margin-right: 20px;"><div class="panel-body letter-promot">'+
						       '<span class="'+arrow+'"></span><span class="timeline-icon blue"></span><span class="timeline-date">'+jsonobject[2][i].day+'</span>'+
						       '<h1 class="blue">Promotion</h1><p style="margin-bottom:5px;"><a class="emp_name" href="#">'+jsonobject[2][i].employee_name+'</a> is promoted from <span><a href="#" class="blue"> '+jsonobject[2][i].old_desig+' </a>'+
						        'to <a href="#" class="light-green">'+jsonobject[2][i].new_desig+'</a></span></p><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                     			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                     			'<input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
                     			'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                'Promotion</button></form></div></div></div></article>').appendTo('.timeline');
                        	}else if(letterName== 'Evaluation:Increment'){
                        		$('<article class="timeline-item '+alt+'"><div class="timeline-desk"><div class="panel" style="margin-right: 20px;"><div class="panel-body letter-inc">'+
                        		'<span class="'+arrow+'"></span><span class="timeline-icon blue"></span><span class="timeline-date">'+jsonobject[2][i].day+'</span>'+
					            '<h1 class="blue">Increment</h1><p style="margin-bottom:5px;"><a class="emp_name" href="#">'+jsonobject[2][i].employee_name+'</a> is rewarded with an increment of <span><a href="#" class="blue">'+jsonobject[2][i].incremented_amount+'</a> from old remuneration  <a href="#" class="blue">Rs. '+jsonobject[2][i].old_salary+'</a> to <a href="#" class="light-green">Rs. '+jsonobject[2][i].new_salary+'</a></span></p>'+
					            '<form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                     			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                     			'<input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
                     			'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                'Increment</button></form></div></div></div> </article>').appendTo('.timeline');
                            	}else if(letterName== 'Evaluation:Promotion come Increment'){
                           		 $('<article class="timeline-item '+alt+'"><div class="timeline-desk"><div class="panel" style="margin-right: 20px;">'+
 							       '<div class="panel-body letter-proWithInc"><span class="'+arrow+'"></span> <span class="timeline-icon blue"></span>'+
 							       '<span class="timeline-date">'+jsonobject[2][i].day+'</span><h1 class="blue">Promotion with Increment</h1><p style="margin-bottom:5px;"><a class="emp_name" href="#">'+jsonobject[2][i].employee_name+'</a> is promoted from <span><a href="#" class="blue">'+jsonobject[2][i].old_desig+'</a>'+
 							       ' to <a href="#" class="light-green">'+jsonobject[2][i].new_desig+'</a></span> with an <b>Increment</b> of <span><a href="#" class="blue">'+jsonobject[2][i].incremented_amount+'</a> on old remuneration'+ 
 							      '<a href="#" class="blue">Rs. '+jsonobject[2][i].old_salary+'</a>. The revised remuneration is<a href="#" class="light-green">Rs. '+jsonobject[2][i].old_salary+'</a></span>'+
 							      '</p><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                      			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                    			'<input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
                    			'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                'Promotion with Increment</button></form></div></div></div></article>').appendTo('.timeline');
                            	}else if(letterName== 'Evaluation:Transfer'){
                            		$('<article class="timeline-item '+alt+'"><div class="timeline-desk"><div class="panel" style="margin-right: 20px;"><div class="panel-body letter-trans">'+
 							           '<span class="'+arrow+'"></span><span class="timeline-icon green"></span><span class="timeline-date">'+jsonobject[2][i].day+'</span><h1 class="green">Transfer</h1>'+
 							            '<p style="margin-bottom:5px;"><a class="emp_name" href="#">'+jsonobject[2][i].employee_name+'</a> is transferred from <span><a class="green">'+jsonobject[2][i].old_desig+'</a> to <a class="light-green">'+jsonobject[2][i].new_desig+'</a></span>'+
 							            '</p><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                            			'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/><input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
                            			'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;Transfer</button></form></div></div>'+
 							            '</div></article>').appendTo('.timeline');
                               }else if(letterName== 'Relieving Letter'){
                            	   $('.timeline-desk span a').css('text-transform','none');
                                       	$('<article class="timeline-item '+alt+'"><div class="timeline-desk"><div class="panel" style="margin-right: 20px;"><div class="panel-body letter-relive">'+
           							        '<span class="'+arrow+'"></span><span class="timeline-icon red"></span><span class="timeline-date">'+jsonobject[2][i].day+'</span>'+
        							        '<h1 class="red">Goodbye, <a class="emp_name" href="#">'+jsonobject[2][i].employee_name+'</a> !!</h1><p style="margin-bottom:5px;"><a class="emp_name" href="#"></a> left the service<span><a href="#" class="light-green"> '+jsonobject[2][i].new_desig+'</a></span></p>'+
        							        '<div class="letters row col-lg-12"><span class="letter-exitInterview"></span>'+
        							        '<span class="letter-reliving" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                                           	'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/> '+
                                           	'<input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
                                           	'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                            'Relieving</button></form></span><span class="letter-termination"></span><span class="letter-termination" style="float:left;margin-left:7px;">'+
                                            '<form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                                           	'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                                           	'<input type="hidden" name="letterName" id="letterName" value="Termination Letter"/>'+
                                           	'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                            'Termination</button></form></span><span class="letter-experience" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                                           	'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                                           	'<input type="hidden" name="letterName" id="letterName" value="Experience Letter"/>'+
                                           	'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                            'Experience</button></form></span>'+
        							        '</div><br><div class="row col-lg-12"><span class="exit-checklist" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                                           	'<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                                           	'<input type="hidden" name="letterName" id="letterName" value="Employee Exit Checklist"/>'+
                                           	'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                            'Exit Checklist</button></form></span><span class="exit-interview-letter" style="float:left;margin-left:7px;"><form class="form-holder" method="post" action="php/letter.handle.php"><input type="hidden" name="act" id="act" value="' +'<?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                                            '<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                                            '<input type="hidden" name="letterName" id="letterName" value="Exit Interview Letter"/>'+
                                             	'<button id="transfer_downloads" class="btn btn-white btn-xs"><i class="fa fa-file-text-o"></i>&emsp;'+
                                              'Exit Interview</button></form></span></div></div>'+
        							         '</div> </div></article>').appendTo('.timeline');
                               }
                
                			
                	 }
                 	$('.work_proof').removeClass('loader'); 
                	
                  }
     		 });
     	} 

       
        function ctc_tabs(){
        		<?php if(isset($_REQUEST['employeeID'])){?>	
        		var employee_id='<?php echo $_REQUEST['employeeID']?>';
        		<?php }else{?>
        		var employee_id =$('#emp_id_now').val();
        		<?php }?>
         	   $('.ctc_detail_loader,.ul_loader').loading(true);
         	   //$('.perqdetails').removeClass('hide');
            	 $.ajax({
                     dataType: 'html',
                     type: "POST",
                     url: "php/employee.handle.php",
                     cache: false,
                     data: { 'employeeId':employee_id,'act':'<?php echo base64_encode($_SESSION['company_id']."!salary_details");?>'},
                     success: function (data) {
                    	 var json_obj = $.parseJSON(data);
                    	 if(json_obj[2][0].gross==0){
                    		  $('.ctc_detail_loader_sub,.ul_loader').loading(false);
                    		  $('.ctc_detail_loader').loading(false);
                    		  $(".no_ctc_detail").html('<h4>Employee salary is not mapped.</h4><a href="../hr/ctc.php?employee_id='+json_obj[2][0].employee_id+'" >Click here to map...</a>');
                              $(".ctc_detail").hide();
                              $(".no_ctc_detail").show();
                              $(".perqdetails").hide();
                        	  $(".wef").hide();
         			} else {
         				    $('#effect_sal_from').html(' Wef- '+json_obj[2][0].effects_from+' ');
                    	 	   if (json_obj[2][0].salary_type=='1') {
                                   $("input[name=salary_type][value= ctc]").prop('checked', true);
                               } else {
                                   $("input[name=salary_type][value= monthly]").prop('checked', true);
                                   }
                              
                              if( json_obj[2][0].isAnnual=="1"){
                                  $("input[name=input_type][value= annual ]").prop('checked', true);
                              } else {
                                  $("input[name=input_type][value= monthly]").prop('checked', true);
                              }

                             
                        	 $('.emp_edit7').data('id',json_obj[2][0].slab_id);
                        	 $('#getCTCcontent').html('');
                        	if (json_obj[2][0].slab_id == "Nil") {
                        		 var salaryType=$("input[name='salary_type']:checked").val();
                        		 if(salaryType =='ctc'){
                        	   	        var  mischtml='';
                        	   	    	 var miscAllow = <?php echo json_encode($miscAlloDeduArray['MP']) ?>;
                        	   	    	 for (i = 0; i < miscAllow.length; i++) {
                        	   	    		 mischtml+='<div class="form-group"><label class="col-lg-5 control-label">'+miscAllow[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control miscAlowDeduCtc" id="'+miscAllow[i].pay_structure_id+'"  name="allowances['+miscAllow[i].pay_structure_id+']"  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div>';
                        	   	         }
                        		 }
                        		 var ar = <?php echo json_encode($allowDeducArray['A']);?>;
                          	     $('#getCTCcontent,#getTablecontent').html('');
                          	     var html='';var inputFormDynami='';
                          	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="Subctc"  name="ctc" data-type="rupee" oninput="reFormate(this)"  autocomplete="off" value="0" required/></div></div><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;border-bottom: 0px solid #e5e5e5;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'';
                          	     html+=(salaryType =='ctc')?varaibleComponents+'</div></div>':'';
                          	     html+='<div class="form-group"><label class="col-lg-5 control-label">Gross Salary</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="gross"  data-type="rupee" name="gross"  oninput="reFormate(this)" autocomplete="off" value="0" readonly required/></div></div></div></div>';
                          	     for (i = 0; i < ar.length; i++) {
                          	    	inputFormDynami+=ar[i].pay_structure_id+',';
                          	    	html+='<div class="form-group"><label class="col-lg-5 control-label">'+ar[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon hiddenSpan"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id='+ar[i].pay_structure_id+'  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div></div>';
                          	     }
                          	     html+='<br><button class="btn btn-default pull-right noSlabCaulation"  data-id="'+inputFormDynami+'" type="button" id="noSlabCaulation">Calculate</button>';
                          	     $('#getCTCcontent').html(html);
                          	     eventForNoSlab();
                          	     
                          	    $(".noSlabCaulation").on("click" , function (e) {
                                   e.preventDefault();
                                   var parameters = {}; //declare the object
                                   parameters["act"] = '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>'; //set some value
                                   parameters["pfLimit"]=$('#pf_limit :selected').val();
                                   parameters["isCTC"] =($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
                                   parameters["isAnnual"] =($("input[type=radio][name=input_type]:checked").val()=='annual')?1:0;
                                   parameters["ctc"] =($('#ctc').val())?deFormate($('#ctc').val()):0;
                                  // parameters["basic"] =0;
                                   $('#getCTCcontent').find('input[type="text"]').not(".miscAlowDeduCtc,#Subctc,#ctc").each(function(){
                                       if(this.id=='gross'){
                                      	 parameters['gross'] =deFormate(this.value);
                                       }else{
                                  	 parameters['allowances['+this.id+']'] =deFormate(this.value);
                                  	 }
                                   });
                                   $.ajax({
                                        dataType: 'html',
                                        type: "POST",	
                                        url: "php/employee.handle.php",
                                        cache: false,
                                        data: parameters,
                                        beforeSend:function(){$('#loader').loading(true);$('#noSlabCaulation').button('loading');},
                                        success: function (data) {
                                      	var json_obj = $.parseJSON(data); //parse JSON
                                      	$('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable"><thead><tr class="headerReload"><th class="emptyDiv">Components</th><th>Rate</th><th style="text-align:right">Monthly</th><th style="text-align:right">Yearly</th></tr></thead>'+
                                             	  setData(json_obj[2]));
                                      	  $('#loader').loading(false);
                                      	  $('#noSlabCaulation').button('reset');
                                       	}
                                  });
                              }); 
                          	
                              setTimeslabIntialize(json_obj[2][0]);
                           }else{ 
                                  $('#getTablecontent').html('<div class="table-responsive"><table class="table ctcDesigntable"><thead><tr class="headerReload"><th class="emptyDiv">Components</th><th>Rate</th><th style="text-align:right">Monthly</th><th style="text-align:right">Yearly</th></tr></thead>'+
                                		 setData(json_obj[2][1]));
                                  	setTimeslabIntialize(json_obj[2][0]);
                                 }
                                 
							//for perquisites table creation
							var perqusites =json_obj[2][2].data;
							$(".perqs").html();
							if(perqusites!= "No Data Found"){
							 var html ='<form id="perq_s" method="POST" class="form-horizontal" role="form"><input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!perquisiteMapping");?>"/><input type="hidden" class="form-control emp_new"	name="empID"	value="'+employee_id+'" /><table class="table-responsive table forperq" id="perqstable" name="perqstable"><thead><tr class="headerReload"><th class=""></th><th class="emptyDiv">Name</th><th class="deduc_amt">Dedu Amount</th><th class="deduc_type">Dedu type</th></tr></thead><tbody>';
                        	 for (var i = 0, len = perqusites.length; i < len;i++) {
                        			html += '<tr>';
	
        							if(perqusites[i].checked==0){
        								//disabled coding
        								html += '<td class="perqs"><input type="checkbox" id="perqs" name="preqs[]" value="'+perqusites[i].pid+'" class="check"></td>';
        								html += '<td>'+perqusites[i].name+'</td>';
        								html += '<td><input type="text" class="form-control" name="ded_amount[]" value="'+perqusites[i].value+'" disabled></td>';
        								html += '<td><select id="deduc_type" name="deduc_type[]" class="deduc_type form-control" disabled><option value="monthly">monthly</option><option value="annually">annually</option></select></td>';
        							}else{
        								//enabled coding
        								html += '<td class="perqs"><input type="checkbox" id="perqs" name="preqs[]" value="'+perqusites[i].pid+'" class="check" checked></td>';
        								html += '<td>'+perqusites[i].name+'</td>';
        								html += '<td><input type="text" class="form-control" name="ded_amount[]" value="'+perqusites[i].value+'"></td>';
        								if(perqusites[i].dedu_type=='monthly'){
        									html += '<td><select id="deduc_type" name="deduc_type[]" class="deduc_type form-control"><option value="monthly" selected>monthly</option><option value="annually">annually</option></select></td>';
        									}else{
        									html += '<td><select id="deduc_type" name="deduc_type[]" class="deduc_type form-control"><option value="monthly">monthly</option><option value="annually" selected>annually</option></select></td>';
        										}
									}
        							html += '</tr>';
								}	
        					  	html += '</tbody></table><button type="submit" class="btn btn-sm btn-success pull-right"  id="perq_map" name="perq_map">Map</button></form>';
        						$(".perqs").html(html);
        						$('#getPerqscontent').html(html);
							}else{
								$('.perqdetails').hide();
								$('.perqs').html('<p>No more perqs to map...</p>');
								}
							
        						if(perqusites[0].checked==1){
        		                       $('#perq_s').show();
        							   $('#perq').attr("disabled");
        						}else{
            		                $('#perq').removeAttr("checked");
        		                     $('#perq_s').hide();
        						}

        						$(".ctc_detail").show();
                                $(".no_ctc_detail").hide();
                     }
                     }
                        	                      
                  });
        	}
        /*	function work_proof_tabs(){
                var employeeId='?php echo isset($_REQUEST['employeeID'])?>';
                if(employeeId != ''){
                  employee_id = employeeId;
                }else{
                  employee_id = $('#emp_id_now').val();
               }
                 $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                    data: { 'employeeId':employee_id,'act':'?php echo base64_encode($_SESSION['company_id']."!letterGeneration");?>'},
                    beforeSend: function () {
                           $('#thisLetter').addClass('loader'); 
                       },
                   success: function (data) {
                        var jsonobject = $.parseJSON(data);
                         var html ='<section id="flip-scroll"><table class="table table-striped table-hover table-bordered" id="letters_view"><thead class="eff"><tr><th style="text-align: center">S.No</th><th style="text-align: center">With Effect From</th><th style="text-align: center">Particulars</th><th style="text-align: center">Action</th></tr></thead><tbody>';
                        for (var i = 0, len = jsonobject[2].length; i < len; ++i) {
                            var addSerialNumber = i+1;
                            var letterName= jsonobject[2][i].letter;
                            
                            html += '<tr>';
                                       html += '<td style="text-align: center">'+addSerialNumber+'</td><td>'+jsonobject[2][i].action_effects_from+'</td><td>'+jsonobject[2][i].letter+'</td>';
                                   html += '<td style="text-align: center">'+
                                       '<form  method="post" action="php/letter.handle.php">'+
                                   '<input type="hidden" name="act" id="act" value="' +'?php echo  base64_encode ( $_SESSION ['company_id'] . "!download" ) ;?>'+'"/>'+
                                   '<input type="hidden" name="actionId" id="actionId" value = "'+jsonobject[2][i].action_id+'"/>'+
                                   '<input type="hidden" name="letterName" id="letterName" value="'+letterName+'"/>'+
                                   '<button id="transfer_downloads"  class="btn btn-success btn-xs"><i class="fa fa-download"></i></button>'+
                                   '</form></td>';
                               
                              html += '</tr>';
                            }
                             html += '</tbody></table></section>';
                           
                           //append table 
                            $('#thisLetter').empty().append(html);
                            $('#thisLetter').removeClass('loader');
                            pendingFlag=1;     
                     }
                 });
            }  */
           	
           
      </script>


</body>
</html>

