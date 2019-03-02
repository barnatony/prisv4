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

<title>Shift Allocation</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />

<link href="../css/table-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
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
				<!-- page start-->
				<section class="panel">

					<header class="panel-heading tab-bg-dark-navy-blue">
						Assign Shift For
						 <div class="btn-group pull-right">
							<button id="showhideButton" type="button" class="btn btn-sm btn-success shift-alloc">
								<i class="fa fa-plus"></i> Shift Allocation
							</button>
						  <a href="import.php?for=Misc_PayDedu" target="foo()" title="Miscellaneous Import">
							<button type="button" class="btn btn-sm btn-default " style="margin-left: 5px;">
								<i class="fa fa-upload" aria-hidden="true"></i> Import
							</button>
						  </a>
						</div>
					</header>

					<!--  <div class="loader " style="width: 97.9%; height: 20%;"></div>-->
				
				   <!--  <div class="panel-body">
				         <form class="form-horizontal shiftAlloc hide" role="form" method="post" id="filterHideShowDiv" >
						    ?php
								require_once (LIBRARY_PATH . "/filter.class.php");
								$filter = new Filter ();
								$filter->conn = $conn;
						       echo  $filterHtml = $filter->createFilterForScreen ('ShiftAllocation');
						    ?>-->
						    
			<div class="panel-body group_catagory" id="group_catagory ">
					 <div class="col-lg-12 panel-body">
					   <form class="form-horizontal  shiftAlloc hide" role="form" method="post" id="filterHideShowDiv" >
					 	<div class = "col-lg-6">
					 	<div class="form-group row">
					 			<div class="col-lg-4">
									<label for="Department" class="groupkey_cat control-label"><input name="groupkey" id="Department"  value="D" type="radio"> Department</label>
								</div>
								<div class="col-lg-4">
									<label for="Designation" class="groupkey_cat control-label"><input name="groupkey" id="Designation" value="F" type="radio"> Designation</label>
									</div>
								<div class="col-lg-4">
									<label for="Branch" class="groupkey_cat control-label"> <input name="groupkey" id="Branch" value="B" type="radio"> Branch</label>
									</div>
					 	 </div>
					 	 <div class="form-group row">
					 	 		<div class="col-lg-4">
									<label for="Shift" class="groupkey_cat control-label"> <input name="groupkey" id="Shift" value="S" type="radio"> Shift</label>
								</div>
								<div class="col-lg-4">
									<label for="Team" class="groupkey_cat control-label"> <input name="groupkey" id="Team" value="T" type="radio"> Team</label>
								</div>
								<div class="col-lg-4">
									<label for="Employee" class="groupkey_cat control-label"> <input name="groupkey" id="Employee" value="E" type="radio"> Employee</label>
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
									<select class="form-control" id="Shift_chosenId" data-placeholder="Select Shift" name="filter_Shift_selected[]">
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
					<div class="col-lg-12">
					<button type="button" class="btn btn-xs btn-success submit_group_val pull-right displayHide" style="margin-top:-5px;">
								 Submit
						</button>
					</div>
					<label id="groupText" style="margin-top:32px;"></label>
					
					
					<div class="col-lg-12 employee_list hide" style="background-color:rgb(252, 252, 252);padding-bottom: 12px;border: 1px solid #CCC;border-radius: 4px;">
		    		<div id="loaderForEmployees" style="margin-left: -4%"></div>
		    		 <div class="panel-heading row" style="/*border:1px solid #ccc;*/margin-left:0px;margin-right:0px;">
			    			<div  style="padding-right: 0px;padding-left: 0px;">
			    			Employees
			   			 	   	<button class="btn btn-xs btn-danger pull-right" style="margin-left:1%" type="button" id="filterDeselect">Deselect</button>
			    				<button type="button" id="filterSelect" class="btn btn-xs btn-success pull-right">Select All</button>
			    			</div>
		    			</div>
					    	<select class="form-control" id="employeeSelected" data-placeholder="Employees Listed Here" name="affectedIds[]" multiple="multiple">
							</select>
					</div>
				
					
				
						    <div class="form-group row" style="margin-top:40px;">
										<div class="col-lg-3">
											<label class="control-label">New Shift</label>&emsp;&emsp;
										</div>
										<div class="col-lg-9">
											<select class="form-control" id="new_Shift_chosenId" data-placeholder="Select Shift" name="new_shiftAlloc" >
											 <?php  require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/shift.class.php");
                                                                            $shiftid = new Shift ();
                                                                            $shiftid->conn = $conn;
                                                                            $shifts = $shiftid->select (0);
                                                                            foreach ( $shifts as $row ) {
                                                                            	echo "<option value='" . $row ['shift_id'] . "'>" . $row ['shift_name'] ."<br>" . "</option>";
                                                                            }
												?>
											</select>
									</div>
							</div>
							<div class="form-group row">
									<label class="col-lg-3 control-label">Effective From</label>
									   <div class="col-md-9 col-xs-11 input-group">
											<span class=" input-group-addon spanCal2"><i
												class="fa fa-calendar"></i></span> <input
												class="work_details form-control" type="text"
												name="effects_from"
												id="effects_from" required />
									</div>
							</div>
							<div class="form-group row">
                    				<label class="col-lg-3 control-label">Reason</label>
                    						<div class="col-lg-9">
                    							<textarea class="form-control shift_change_reason" name="desigChangeReason" placeholder="Text Shift Change" required ></textarea>
                    						</div>
                    		</div>
                    		 <div class="form-group pull-right">
					     	     <button type="button" class="btn btn-sm btn-success" id="shift_preview">Preview</button>
						        
				            </div>
                    										
						</div>
					</form>	
				 <div class="col-lg-6">
				 		<form id="shiftAllocForm" class="panel-body" >
						 <div id="allshiftContent"> 	</div>
						 <div class="shift_con displayHide" style="margin-top:70px;">
							 <div class="form-group  row">
							 	<div class="col-lg-6">	
									<label class="control-label">NEW SHIFT EFFECTS FROM&nbsp;&nbsp;&nbsp;:</label>
								</div>
								<div class="col-lg-6">					
									<input class="form-control new_shift_eff_from" type="text">
							 	</div>
							 </div>
							 <div class="form-group row">
							 	<div class="col-lg-6">	
							 		<label class=" control-label">REASON FOR SHIFT CHANGE&nbsp;:</label>
							 	</div>
								<div class="col-lg-6">	
							 		<input class="form-control reason_for_shift_change" type="text" >
							 	</div>
							 </div>
							 <div class="panel-body pull-right showedEmp"><button type="submit" class="btn btn-sm btn-success" id="shiftAllocSubmit">Assign</button></div>
							</div> 
						</form>
			</div>
			</div>
		</div>
   </section>
			<!-- page end -->

			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>

	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<!--script for this page only-->
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	
	<script src="../js/bootstrap-dialog.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
	$(document).ready(function () {
		  $('#filterHideShowDiv').toggleClass('show');
    });

	 $('#effects_from').datetimepicker({
   	      format: 'DD/MM/YYYY',
          maxDate:false,
          minDate: new Date()
       });
     
    $('.shift-alloc').on('click',function(){
        $('#filterHideShowDiv').toggleClass('show');
        //$('#shiftAllocForm').css('border-left','');	
     });

	$('#shift_preview').on('click',function(){
		$('#shiftAllocForm').css('border-left',' 1px solid #ccc');	
		var empId = $('#employeeSelected').val();
		var new_ShiftId = $('#new_Shift_chosenId').val();
		$('.shift_con').removeClass('displayHide');
		$('.new_shift_eff_from').val($('#effects_from').val());
		$('.reason_for_shift_change').val($('.shift_change_reason').val());
		$.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/employee.handle.php",
            cache: false,
            data: {shiftSearchIds:empId,new_ShiftId:new_ShiftId,
                act:'<?php echo base64_encode($_SESSION['company_id']."!getShiftAllocdetails");?>'},
            success: function (data) {
            	 jsonobject= JSON.parse(data);
                 $('#perq-data').hide();
                 $('#allshiftContent').empty();
                  var html = '<section id="flip-scroll"> <h4> Affected Employee(s)</h4><table class="table tab-content tasi-tab table-striped table-hover table-bordered cf dataTable " id="shift-sample" style="width:100%;overflow:hidden;"> <thead class="cf"><tr><th><input type="checkbox" class="head_shift_enable" data-id="shift_enable"  ></th><th>Employees Name</th><th>Old Shift</th><th>New Shift</th></tr></thead><tbody>';               	                	  
                     for (var i = 0, len = jsonobject[2].length; i < len; ++i) {
                    	 html += '<tr><input type="hidden" id="employee_id" value='+jsonobject[2][i].employee_id+'><input type="hidden" id="new_shift_id" value='+jsonobject[2][i].new_shift_id+'><td class="" id="select"><input type="checkbox" name="shift_enable" id="shift_enable" data-val='+jsonobject[2][i].employee_id+' data-id="shift_enable" class="shift_enable" value="1" ></td>';
                      		$.each(jsonobject[2][i], function (k, v) {
								if(k == 'employee_name' || k=='old_shift' || k=='new_shift')
            			  		html += '<td>'+v+'</td>';
              		 		}); 
                      	html += '</tr>';
                  }
                  
                  html += '</tbody> </table></section>';
                  //append table 
                  $('#allshiftContent').html(html);
                 
                    setTimeout(function(){  
                        var oTable = $('#shift-sample').dataTable( {
                       	 "aLengthMenu": [
                                            [10, 15, 20, -1],
                                            [10, 15, 20, "All"] // change per page values here
                                        ],
                            "iDisplayLength": 10,
                             "aoColumnDefs": [
                                              {"bSearchable": false, "bVisible": false ,"aTargets":0 }      
                                            ] ,
                              } );
                        },100);
                        $('#allshiftContent').removeClass('loader');
                  }
        });
	});

	

	$(document).on('change','input[type="radio"][name="groupkey"]',function(e){
		e.preventDefault();
		var groupKey = $(this).val();
		 $('.groupkey').val(groupKey);
	      if( groupKey=='D'){
			    $('.designation_select,.branch_select,.shift_select,.team_select').addClass('displayHide');
		  	  	$('.department_select,.submit_group_val').removeClass('displayHide');
		  	  	$('#Department_chosenId').attr('multiple','multiple');
		 	    $("#Department_chosenId").chosen();
		    }else if(groupKey =='F'){
			    $('.department_select,.branch_select,.shift_select,.team_select').addClass('displayHide');
			    $('.designation_select,.submit_group_val').removeClass('displayHide');
		  	  	$('#Designation_chosenId').attr('multiple','multiple');
		 	    $("#Designation_chosenId").chosen();
			   }else if(groupKey =='B'){
				    $('.department_select,.designation_select,.shift_select,.team_select').addClass('displayHide');
				    $('.branch_select,.submit_group_val').removeClass('displayHide');
			  	  	$('#Branch_chosenId').attr('multiple','multiple');
			 	    $("#Branch_chosenId").chosen();
			   }else if(groupKey =='S'){
				    $('.department_select,.designation_select,.branch_select,.team_select').addClass('displayHide');
				    $('.shift_select,.submit_group_val').removeClass('displayHide');
			  	  	$('#Shift_chosenId').attr('multiple','multiple');
			 	    $("#Shift_chosenId").chosen();
				   }else if(groupKey =='T'){
					   $('.department_select,.designation_select,.branch_select,.shift_select').addClass('displayHide');
					    $('.team_select,.submit_group_val').removeClass('displayHide');
				  	  	$('#Team_chosenId').attr('multiple','multiple');
				 	    $("#Team_chosenId").chosen();
					   }
				   else if(groupKey =='E'){
					   $('.department_select,.designation_select,.branch_select,.shift_select,.team_select,.submit_group_val ').addClass('displayHide');
						$('.employee_list').removeClass('hide');
						$("#groupText").html('');
						groupEmp(0,0,'','',"E"," ");
				   }
	  	  	});

	$(document).on('click',".submit_group_val",function(e){
		e.preventDefault();
		$("#shift_enable").attr('checked',"checked");
		$('.employee_list').removeClass('hide');
		//$("#employeeSelected").prop("selected", false);
		$('.submit_group_val,.team_select,.shift_select,.branch_select,.designation_select,.department_select').addClass('displayHide');
    	var groupKey = $('.groupkey').val();

    	 groupText = "Choose Employees ";
  	  
  	  if( groupKey == 'D'){
  				var groupValues= $("#Department_chosenId").chosen().val();
		  	  	var selMulti = $.map($("#Department_chosenId option:selected"), function (el, i) {
		 	         return $(el).text();
		 	     });
		 	   var group_dept= selMulti.join(",");
  	  		   if(group_dept!=''){
  	  		   groupText += "from Department(s): "+ group_dept ;
		 	   }
  	  	}else if(groupKey == 'F'){
  	  	        var groupValues= $("#Designation_chosenId").chosen().val();
  	  			var selMulti = $.map($("#Designation_chosenId option:selected"), function (el, i) {
	        			 return $(el).text();
	     		});
	  			 var group_desig= selMulti.join(",");
  	  			if(group_desig!=''){
  	  			groupText += "from Designation(s): "+ group_desig ;
	  			 }
  	  	  	}else if(groupKey == 'B'){
  	  	  		var groupValues= $("#Branch_chosenId").chosen().val();
  	  			var selMulti = $.map($("#Branch_chosenId option:selected"), function (el, i) {
			 			return $(el).text();
				});
				 var group_branch= selMulti.join(",");
					if(group_branch!=''){
					groupText += "from Branch(s): "+ group_branch;
				 }
  	  	  	 }else if(groupKey == 'S'){
  	  	  		 var groupValues= $("#Shift_chosenId").chosen().val();
  	  	  		 var selMulti = $.map($("#Shift_chosenId option:selected"), function (el, i) {
	 			   			return $(el).text();
					});
		 		var group_shift= selMulti.join(",");
  	  	  	  	if(group_shift!=''){
  	  	  	  			groupText += "from Shift(s): "+ group_shift ;
		 		}
			}else if(groupKey == 'T'){
		  	  	  var groupValues= $("#Team_chosenId").chosen().val();
		  	  	  var selMulti = $.map($("#Team_chosenId option:selected"), function (el, i) {
 			   				return $(el).text();
				    });
	 			var group_team= selMulti.join(",");
	 		   	if(group_team!=''){
	 		    groupText += "from Team(s): "+ group_team ;
	 		}
		 } else if(groupKey =='E'){
			 
			 $("#groupText").addClass('displayHide');
			 }

  
		
	  $("#groupText").html(groupText);
  	  $('.groupValues').val(groupValues);
  	 var groupValues = $(".groupValues").val();
  	  $("#empView").html('');
 	  $('#employeeSelected').attr('multiple','multiple');
 	 groupEmp(0,0,'','',groupKey,groupValues);
  	});


 	function groupEmp(startlimit,intervalLimit,searchKey,letterKey,groupKey,groupValues){
 	     var inActive = '';
 	     var $select = '';
 	     var is_surrogate_users = '-1';
 	     $('#employeeSelected').chosen().html('').trigger("chosen:updated");
 	      $.ajax({
 	          dataType: 'html',
 	          type: "POST",
 	          url: "php/employee.handle.php",
 	          data:{act:'<?php echo base64_encode($_SESSION['company_id']."!getEmployeeView");?>',start:startlimit,
 		             interval:intervalLimit,searchKey:searchKey,letterKey:letterKey,inActive:inActive,groupKey:groupKey,groupValues:groupValues,is_surrogate_users:is_surrogate_users},
 	          cache: false,
 	          beforeSend: function () {
 	              $("#searchLoader").loading(true);
 	              },
 	            
 	          success: function (data) {
 	              var json_obj = JSON.parse(data);
 	              for (var i = 0, len = json_obj[2].length; i < len;++i) {
 	            	   //console.log(json_obj[2][i].employee_id);
 	             	 var $select = $('#employeeSelected').append('<option value='+json_obj[2][i].employee_id+'>'+json_obj[2][i].employee_name +'['+json_obj[2][i].employee_id+']'+'</option>').trigger("chosen:updated");
 	              	 }
 	              $select.chosen();
 	          } 
 	      });
 	  	  	
		 }

 	//Filter Cutomise Select Function in js
	 $(document).on('click', "#filterSelect", function () {
		 $('#loaderForEmployees').loading(true);
	     $('#employeeSelected option').prop("selected", true);
	     $('#loaderForEmployees').loading(false);
	     $('#employeeSelected').trigger('chosen:updated');
    });
	 
	//Filter Cutomise Deselect Function in js
	$(document).on('click', "#filterDeselect", function () {
		$('#loaderForEmployees').loading(true);
   	  $('#employeeSelected option').removeAttr("selected");
   	  $('#loaderForEmployees').loading(false);
   	  $('#employeeSelected').trigger('chosen:updated');
     });

	
    $(document).on('click',".head_shift_enable",function(){
        var table = $('#shift-sample').dataTable();
        $(this).attr('checked',true);
        
      
      var rows = table.fnGetNodes();

       if($(this).prop("checked")==true){
           $(rows).find("[data-id='shift_enable']").prop( 'checked', "checked");
           $(rows).find("[data-id='shift_enable']").val('1');
               //table.find("[data-id='shift_enable']").prop( 'checked', "checked");
               //table.find("[data-id='shift_enable']").val('1');
           var Temp=$(this).parent().parent().find('#employee_id').val();
           //updateAlloc($(this),Temp);	
       }else{
           $(this).attr('checked',false);
           $(rows).find("[data-id='shift_enable']").removeAttr( 'checked');
           $(rows).find("[data-id='shift_enable']").val('0');
       }
       
           
       
   });
	 $(document).on('click', ".shift_enable", function (e) {
		   //get the parent row
		    //find input elemts and disable it other than check box, .not("input[type=""]")
		 	if($(this).is(":checked")){
		 		$(this).parent().parent().find('#select').attr('selected', true);
		 		$(this).parent().parent().find('input[type="checkbox"]').attr('checked', true);
		 		$(this).parent().parent().find('input[type="checkbox"]').val('1');
		 		//var Temp=$(this).parent().parent().find('#employee_id').val();
		 		//updateAlloc($(this)); 
		 	}
		 	else{
		 		$(this).parent().parent().find('#select').attr('selected', false);
				$(this).parent().parent().find('input[type="checkbox"]').attr('checked', false);
		 	    $(this).parent().parent().find('input[type="checkbox"]').val('0');
		 	}
		   });

$("#shiftAllocForm").on('submit', function (e) {
			e.preventDefault();
			
		var new_shift_eff = $('.new_shift_eff_from').val();
		var change_reason = $('.reason_for_shift_change').val();
		var newShiftId =  $('#shift-sample tr').find('td:eq(0)').parent().find('#new_shift_id').val();


		var empIID = updateAlloc();
		console.log(updateAlloc())
		//tdata_empID.push(updateAlloc());
 	var EmployeeId =[];
 	var EmployeeId = empIID.filter(function(v){return v!==''});
	 $.ajax({
	          dataType: 'html',
	          type: "POST",
	          url: "php/employee.handle.php",
	          data:{act:'<?php echo base64_encode($_SESSION['company_id']."!updateShiftDetails");?>',date_of_start:new_shift_eff,
	        	  shiftChangeReason:change_reason,newShift:newShiftId,employeeId:EmployeeId},
	          cache: false,
	          beforeSend: function () {
	              $("#searchLoader").loading(true);
	              },
	          success: function (data) {
	              var json_obj = JSON.parse(data);
	              
	             if(json_obj[0]=="success"){
	            	  BootstrapDialog.alert(json_obj[1]);
	          } else
                    if (json_obj[0] == "error") {
                  	  BootstrapDialog.alert(json_obj[1]);
                  	 
                    }
	          }
	 	 });
	 	 
});



//for shift allocation check box select
function updateAlloc(){
		
		var table=$("#shift-sample").dataTable();
		var rows = table.fnGetNodes();
		var tempArr = [];
		
		var MyRows =$('input:checked',rows);
		   for(var i=0;i<MyRows.length;i++){
				var employee_id1 =$(MyRows[i]).data("val");
			 	tempArr.push(employee_id1);
			}
			
		return tempArr;
	    
}




	</script>
</body>
</html>
