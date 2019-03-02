<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="BassTechs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Report Filter</title>
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
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<style>
.chosen-container .chosen-results li.active-result.disabled::after {
    content: "Inactive";
    background-color: transparent;
    color: #FCB322;
    border: 1px solid #FCB322;
    padding: 0px 2px 0px 2px;
    display: inline-block;
    font-size: 10px;
    position: absolute;
    right: 5px;
    margin-top: 2px;
}
#customfields_chosen {
	width: 150% !important;
}

#empMasterdetails_chosen ,#empPersonaldetails_chosen ,#team_select_chosen{
	width: 100% !important;
}

select.form-control+.chosen-container .chosen-results li.group-result {
  padding: 3px 8px;
  color: #8d8d8d;
}
</style>


<body>

	<section id="container" class="">
		<!--header start-->
     <?php
					
include_once (__DIR__ . "/header.php");
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/branch.class.php");
$branch = new Branch ();
$branch->conn = $conn;
$branchsOnly = $branch->select ();

Session::newInstance ()->_setGeneralPayParams ();
$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once (__DIR__."/sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading"> Report Filter </header>
					<?php
                    $stmt = mysqli_query ( $conn, "SELECT IF(leave_based_on = 'finYear','FY','CY') leave_based_on FROM company_details;" );
                    $result = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
                    $leave_based = $result['leave_based_on'];
                    
                    $Biostmt = mysqli_query( $conn,"SELECT IFNULL(COUNT(employee_id),0) emp_count FROM employee_biometric;" );
                    $result = mysqli_fetch_array ( $Biostmt, MYSQLI_ASSOC );
                    $emp_count= $result['emp_count'];
                    
                    $miscstmt = mysqli_query( $conn,"SELECT COUNT(pay_structure_id) misc_count FROM company_pay_structure WHERE type IN('MP','MD') AND display_flag=1;" );
                    $result = mysqli_fetch_array ( $miscstmt, MYSQLI_ASSOC );
                    $misc_count= $result['misc_count'];
                    
                    ?>
					<div class="panel-body">
						<form class="form-horizontal" id="reportFilter" method="POST" target="_blank" action="report.php" >
						<div id="loader" style="width:97%;height:50%"></div>
						<div class="col-lg-6">
						<div class="form-group col-lg-12">
											<label class="col-lg-5 control-label">Report Type</label>
											<div class="col-lg-7">
												<select  class="form-control" id="salaryStmt" name="salaryStmt">
													<optgroup  label="Pay Roll Module">
                                               		 	<option value="SS001">Salary Statement</option>
                                               		 	<?php if($misc_count>0)
                                               		 		echo'<option value="SS002">Misc Pay/Dedu Report</option>';
                                               		 	?>
                                               		 </optgroup>
													<optgroup  label="Attendance Module">
													 <?php
	                                                	if($emp_count>0){
	                                                		echo '<option value="BT005">Attendance Process Data</option>';
	                                                		echo '<option value="BT006">Attendance Summary Report</option>';
	                                                		echo '<option value="BT004">Deviation Report</option>';
	                                                		echo '<option value="BT003|early">Early Exit Report</option>';
	                                                		echo '<option value="BT003|late">Late Coming Report</option>';
	                                                		echo '<option value="BT002">Biometric Punches Report</option>';
			                                                echo '<option value="BT007">No Punch Report</option>';
			                                                
		                    							}
                                               		  ?>
                                               		    <option value="AT005">Leave Balance Report</option>
                                                		<option value="AT004">Leave Apply Report</option>
                                               		 </optgroup>
                                               		 <optgroup  label="Leave Module">
                                               		 	<option value="ER005">Approval Routing Detail Report</option>
                                               		 	<?php if($emp_count>0)
                                               		 		echo '<option value="AT006">Leave Summary Report</option>';
                                               		 	?>
                                               		 </optgroup>
                                               		 <optgroup  label="Employee Details Module">
                                               		 	<option value="ER001">Employee Birthday Report</option>
                                                		<option value="ER002">Employee Anniversary Report</option>
                                                		<option value="ER004">Employee Information Report</option>
                                                		<option value="MS001">Master Salary Report</option>
                                                		<option value="ER008">Previous Employment Report</option>
                                                		<option value="ER009">Employee Education Report</option>
                                                		<option value="ER010">Document Tracker Report</option>
                                                		<option value="ER011">Shift Monthly Report</option>
                                                		<option value="ER012">Appraisal Monthly Report</option>
                                                	</optgroup>
                                                	<optgroup  label="Employee Lifecycle Module">
                                                		<option value="EL001">Increment Report</option>
                                                		<option value="EL002">Promotion Report</option>
                                                		<option value="EL003">Transfer Report</option>
                                                		<option value="EL004">Separation Report</option>
                                                	</optgroup>
                                                	<optgroup  label="HR Module">
                                                		<option value="HR001">Employee Addition Report</option>
                                                		<option value="HR002">Attrition Analysis Report</option>
                                                		<option value="HR005">Attrition By Gender</option>
                                                		<option value="HR006">Attrition By Experience</option>
                                                		<option value="HR007">Attrition by Experience & Gender</option>
                                                		<option value="HR003|count">Team Wise Headcount Report</option>
            											<option value="HR003|members">Team Wise Members Report</option> 
            											<option value="HR004">Variance in Salary Report</option>
                                                		<option value="HR008">HR Analytics Report</option> 
            											
                                                	</optgroup>
                                                	<optgroup  label="Others">
                                                		<option value="FR001">Form-S</option>
                                                		<option value="FR002">Form-Q</option>
                                                		<option value="FR003">Form-P</option>
		                                                <option value="AT001">Leave Report</option>
		                                                <option value="AT002">Employee Wise Leave Report</option>
		                                                <option value="AT003">Day Wise Attendence Report</option>
	                                                	<?php if($emp_count>0){	
		                                                	echo '<option value="BT001|ot">Biometric OT Report</option>';
		                                                	echo '<option value="BT001|late_in">Biometric Latecomers Report</option>';
		                                                	echo '<option value="BT001|early_out">Biometric Less Working hrs Report</option>';
	                                                	}
		                                                ?>
                                                	</optgroup>
                                                
                                                </select>
                                          </div>
						</div>
						<div class="form-group col-lg-12" id="periodType">
											<label class="col-lg-5 control-label">For the</label>
											<div class="col-lg-7">
												<select  class="form-control" id="stmtFor" name="stmtFor">
                                                <option value="M">Months</option>
                                                <option value="Q">Quarter</option>
                                                <option value="HY">Half Year</option>
                                                <option value="Y">Year</option>
                                                </select>
                                          </div>
						</div>
						<div class="form-group col-lg-12" id="fromPeriod">
											<label class="col-lg-5 control-label">Period of</label>
											<div class="col-lg-7">
												<select  class="form-control" id="periodFrom" name="periodFrom">
                                               </select>
                                          </div>
						</div>
						<div class="form-group col-lg-12" id="fromdt">
											<label class="col-lg-5 control-label">From Date</label>
											<div class="col-lg-7">
												<input class="form-control" name="fromDate" id="fromDate" placeholder="dd-mm-yyyy" type="text" style="cursor:pointer;"></input>
                                          </div>
						</div>
						<div class="form-group col-lg-12" id="thismonth">
											<label class="col-lg-5 control-label">Month</label>
											<div class="col-lg-7">
												<select  class="form-control" id="monthFor" name="monthFor">
												<option value="01">Jan</option><option value="02">Feb</option><option value="03">Mar</option>
												<option value="04">Apl</option><option value="05">May</option><option value="06">Jun</option>
												<option value="07">Jul</option><option value="08">Aug</option><option value="09">Sep</option>
												<option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option>
												<option value="all">All</option>
												</select>
                                          </div>
                       </div>
                       <div class="form-group col-lg-12"id="showMonYear">
	                                          <label class="col-lg-5 control-label">Month Year</label>
	                                          <div class="col-lg-7 input-group">
												<span class="input-group-addon" style="cursor: pointer"><i class="fa fa-calendar"></i></span>
												<div class="iconic-input right">
													<input class="form-control" name="attendance_month" id="datepicker" type="text">
												</div>
											 </div>
						</div>
						<div class="form-group col-lg-12"id="showYear">
	                                          <label class="col-lg-5 control-label">Year</label>
	                                          <div class="col-lg-7 input-group">
												<span class="input-group-addon" style="cursor: pointer"><i class="fa fa-calendar"></i></span>
												<div class="iconic-input right">
													<input class="form-control" name="choose_year" id="choose_year" type="text">
												</div>
											 </div>
						</div>
						<div class="form-group col-lg-12"  id="group-cat">
											<label class="col-lg-5 control-label">For</label>
											<div class="col-lg-7">
												<select  class="form-control" id="reportCategory" name="reportCategory">
												<option value="B">Branch</option>
                                                <option value="F">Department</option>
                                                <option value="D">Designation</option>
                                                <option value="T">Team</option>
                                                <option value="E">Employee(s)</option>
                                                 <option value="">ALL</option>
                                               </select>
                                          </div>
						</div>
						<div class="form-group col-lg-12" id="showcustom">
							<div id="customChoose"><label class="col-lg-5 control-label">Choose Fields</label></div>
										<div class="col-lg-7" id="customselect">
											<select   class="form-control"  class="customfields" id="customfields" name="customfields[]" multiple>
													<option value="EMPLOYEE_DOB">EMPLOYEE DOB</option>
													<option value="EMPLOYEE_EMAIL">EMPLOYEE EMAIL</option>
													<option value="EMPLOYEE_MOBILE">EMPLOYEE MOBILE</option>
													<option value="EMPLOYEE_PERSONAL_MOBILE">EMPLOYEE PERSONAL MOBILE</option>
													<option value="EMPLOYEE_PERSONAL_EMAIL">EMPLOYEE PERSONAL EMAIL</option>
													<option value="DEPARTMENT_NAME">DEPARTMENT NAME</option>
													<option value="DESIGNATION_NAME">DESIGNATION NAME</option>
													<option value="BRANCH_NAME">BRANCH NAME</option>
													<option value="TEAM_NAME">TEAM NAME</option>
													<option value="IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER">REPORTING PERSON</option>
													<option value="SHIFT_NAME">SHIFT NAME</option>
											</select>
										</div>
                         </div>
                         
                         <div class="form-group col-lg-12" id="branchOnly">
											<label class="col-lg-5 control-label">Branch</label>
											<div class="col-lg-7">
												<select  class="form-control" id="branchCat" name="branchCat">
												    <?php
								$stmt = mysqli_prepare ( $conn, "SELECT branch_id,branch_name FROM company_branch;" );
								$result = mysqli_stmt_execute ( $stmt );
								mysqli_stmt_bind_result ( $stmt, $branch_id, $branch_name);
								while ( mysqli_stmt_fetch ( $stmt ) ) {
									
									echo "<option value='" . $branch_id . "'>" . $branch_name . "</option>";
								}
								?>
                                               </select>
                                          </div>
						</div>
						
						<div class="form-group col-lg-12" id="employee_master_details">
											<label class="col-lg-5 control-label">Employee Master Details</label>
											<div class="col-lg-7" id="empMaster_select">
												<select  class="form-control" id="empMasterdetails" name="empMasterDetail[]" multiple>
													<option value="DATE_FORMAT(employee_doj,'%d-%b-%Y') Employee_DOJ">Employee DOJ</option>
													<option value="Department_Name">Department</option>
													<option value="Designation_Name"> Designation</option>
													<option value="Branch_Name"> Branch</option>
													<option value="Team_Name"> Team</option>
													<option value="employee_mobile Official_Mobile">Official Mobile</option>
													<option value="employee_email Official_Email"> Official Email</option>
                                               </select>
                                     
                                          </div>
						</div>
						
						 <div class="form-group col-lg-12" id="emp_status">
											<label class="col-lg-5 control-label">Employee Status</label>
											<div class="col-lg-7">
												<select  class="form-control" id="employeeStatus" name="employeeStatus">
													<option value="1">Active</option>
													 <?php
												$stmt = mysqli_prepare ( $conn, "SELECT exit_id,reason_code FROM exit_reasons;" );
												$result = mysqli_stmt_execute ( $stmt );
												mysqli_stmt_bind_result ( $stmt, $exit_id, $reason_code);
												while ( mysqli_stmt_fetch ( $stmt ) ) {
													
													echo "<option value='" . $exit_id . "'>" . $reason_code . "</option>";
												}
											?>
                                               </select>
                                          </div>
						</div>
						
                         <div class = "row">
						<div class="checkboxes col-lg-12">
						<div class="col-lg-5">
								<label class="checkbox-inline leave_part1" for="leave_part1"> 
											<input id="leave_part1" name="consolidate" value="1" type="checkbox" data-toggle="popover" data-trigger="hover" data-placement="top" title="what is a consolidated Report?" data-content="For the selected periods only the gross salary of the employees will be displayed">Make it Consolidated
								</label>
						</div>								
					   </div>
					   <div class="checkboxes col-lg-12">
					   <div class="col-lg-5 " id="showfield">
								<label class="checkbox-inline fields" for="fields"> 
											<input id="fields" name="is_costom" value="1" type="checkbox"  data-toggle="popover" data-trigger="hover" data-placement="top"  data-content="">Choose Fields
								</label>
						</div>	
					    </div>
						</div>
						</div>
						<div class="col-lg-6">
						<div class="form-group col-lg-12">
						<label class="col-lg-2 control-label">.</label>
											<div class="col-lg-7" >
											</div>
                         </div>
                         <div class="form-group col-lg-12" id="teamOnly">
											<label class="col-lg-4 control-label">Team</label>
											<div class="col-lg-8" id="team_select">
												<select  class="form-control" id="teamCat" name="teamCat[]" multiple>
												    <?php
								$stmt = mysqli_prepare ( $conn, "SELECT team_id,team_name FROM company_team;" );
								$result = mysqli_stmt_execute ( $stmt );
								mysqli_stmt_bind_result ( $stmt, $team_id, $team_name);
								while ( mysqli_stmt_fetch ( $stmt ) ) {
									
									echo "<option value='" . $team_id . "'>" . $team_name . "</option>";
								}
								?>
                                               </select>
                                          </div>
						</div>
						
						 <div class="form-group col-lg-12" id="emp_ser_status">
											<label class="col-lg-4 control-label">Employee Job Status</label>
											<div class="col-lg-8">
												<select class="form-control job_status1" id="job_status1" name="job_status1">
														<option value="JS359045">Temporary</option>
														<option value="JS31188">Permanent</option>
														<option value="JS20069">Probation</option>
												</select>
                                          </div>
						</div>
						
						<div class="form-group col-lg-12" id="employee_per_details">
											<label class="col-lg-4 control-label">Employee Personal Details</label>
											<div class="col-lg-8" id="empPersonal_select">
												<select  class="form-control" id="empPersonaldetails" name="empPersonalDetails[]" multiple>
													<option value="DATE_FORMAT(employee_dob,'%d-%b-%Y') Employee_DOB">Employee DOB</option>
													<option value="Employee_Gender"> Employee Gender</option>
													<option value="permanent_emp_country Permanent_Country_Name">Permanent Country</option>
													<option value="Employee_Personal_Mobile">Permanent Contact Mobile</option>
													<option value="Employee_Personal_Email"> Permanent Email</option>
													<option value="Employee_Father_Name"> Employee Father's Name</option>
													<option value="Emp_Mother_Name"> Employee Mother's Name</option>
													<option value="Emp_Spouse_Name"> Employee Spouse Name</option>
													<option value="Emp_Spouse_Mobile"> Employee Spouse Mobile</option>
													<option value="Emp_UG_Degree"> Employee UG Degree</option>
													<option value="Emp_PG_Degree"> Employee PG Degree</option>
                                               </select>
                                     
                                          </div>
						</div>
						
                         <div class="form-group col-lg-12" id="chooseYr">
						<label class="col-lg-2 control-label">in</label>
											<div class="col-lg-7">
											<div class="input-group">
											<input type="hidden" id="changeYear" name="typeOfyear" value="FY">
              <span class="input-group-addon changeYear" title="Financial Year" id="leaveBased" style="cursor:pointer;">FY</span>
              								<select  class="form-control" id="inYear" name="inYear">
                                               <?php
								$stmt = mysqli_prepare ( $conn, "SELECT DISTINCT CONCAT(SUBSTRING(year,1,4),'-',SUBSTRING(year,5,2),'#',SUBSTRING(year,1,4),'-04-01#',(SUBSTRING(year,1,4)+1),'-03-01') FROM payroll ORDER BY year DESC;" );
								$result = mysqli_stmt_execute ( $stmt );
								mysqli_stmt_bind_result ( $stmt, $year);
								while ( mysqli_stmt_fetch ( $stmt ) ) {
									$yearData=explode('#',$year);
									echo "<option value='" . $yearData[1] . "'>" . $yearData[0] . "</option>";
								}
								?>
							 </select>
                                          </div>
                                          </div>
                                          
                                          
						</div>
						
						<div class="form-group col-lg-12" id="toPeriod">
											<label class="col-lg-2 control-label">to</label>
											<div class="col-lg-7">
												<select  class="form-control" id="periodTo" name="periodTo">
                                                </select>
                                          </div>
						</div>
						<div class="form-group col-lg-12" id="todt">
											<label class="col-lg-2 control-label">to</label>
											<div class="col-lg-7">
												<input class="form-control" name="toDate" id="toDate" placeholder="dd-mm-yyyy" type="text"></input>
                                          </div>
						</div>
						<div class="form-group col-lg-12" id="thismonth1">
							<label class="col-lg-2 control-label">.</label>
											
                         </div>
                         <div class="form-group col-lg-12" id="thisyear1">
							<label class="col-lg-2 control-label">.</label>
											
                         </div>
						<div class="form-group col-lg-12 hideForAllemp" >
											<label class="col-lg-2 control-label">in </label>
											<div class="col-lg-7">
												<select   class="form-control" id="categoryNames" name="categoryNames[]">
												   <?php
										foreach ( $branchsOnly as $row ) {
											echo "<option value='" . $row ['branch_id'] . "'>" . $row ['branch_name'] . " [ " . $row ['branch_id'] . " ] <br>" . "</option>";
										}
									?>
		                                </select>
                                          </div>
						</div>
						
						</div>
							<button type="submit" id="generate" class="btn btn-sm btn-default btn-block">Generate</button>
			
						</form>
						

					</div>
				</section>
			</section>

		</section>

		<!--main content end-->
		<!--footer start-->
		<?php include_once (__DIR__."/footer.php");?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript" src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script>
	//var date="?php echo ($_SESSION['partialRun']==1)?date('m Y', strtotime("+0 months",strtotime($_SESSION['payrollYear'].'-'.$_SESSION['monthNo'].'-01'))):date('m Y', strtotime("-1 months",strtotime($_SESSION['payrollYear'].'-'.$_SESSION['monthNo'].'-01')));?>";
     $(document).ready(function () {
    	 $('[data-toggle="popover"]').popover(); 
    	 $('#salaryStmt,#stmtFor,#inYear,#periodFrom,#periodTo,#reportCategory,#categoryNames').chosen();
    	 //$('#fromDate,#toDate').attr('disabled','disabled');
    	 $('#fromdt,#todt,#showcustom,#thismonth1,#thismonth,#showMonYear,#showYear,#thisyear1,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
    	 periodGet();
    	 $('#customselect').attr('multiple','multiple').show();
    	 $('#customfields').chosen();
    	// $("#datepicker").val(date);
    	 $('#showfield').show();
     });
     
     $('#fromDate,#toDate').datetimepicker({
         format: 'DD-MM-YYYY' 
     }); 
     $('#datepicker').datetimepicker({
 	    viewMode: 'years',
 	    format: 'MM YYYY'
     });
     $('#choose_year').datetimepicker({
  	    viewMode: 'years',
  	    format: 'YYYY'
      });
      $('#fields').change(function(){
          if($(this).prop("checked")) {
    	    $('#showcustom').show();
    	  }else{
    	    $('#showcustom').hide();
    	    $('#customfields').val('').trigger('chosen:updated');
    	  }
    	});
    /*  $('#leave_part1').change(function(){
          if($(this).prop("checked")) {
     	    $('#showfield').hide();
     	  } else {
     	    $('#showfield').show();
     	  }
     	});*/
     $("#fromDate").on("dp.change", function (e) {
         var date = $('#fromDate').datetimepicker().val();
         var someDate = new Date(date);
         someDate.setDate(someDate.getDate() + 30);
         if(date!=''){
		 	$('#toDate').data("DateTimePicker").minDate(e.date);
         	$('#toDate').data("DateTimePicker").maxDate(someDate.toISOString());
         }
     });
     
    $(document).on('change', "#salaryStmt", function (e) {
    		report=$('#salaryStmt :selected').val();
    		report = report.split("|");
    		var stmtReport = report[0];
    		var leaveBasedYear="<?php echo $leave_based;?>"; // FY,CY
    		//$('#stmtFor').removeAttr('disabled').trigger("chosen:updated");
    		if( stmtReport =='AT002' || stmtReport =='HR003' || stmtReport =='ER008' || stmtReport =='FR001' || stmtReport =='FR002' || stmtReport =='FR003' || stmtReport =='ER011')
    		   {
    			$('#showfield,#customChoose,#customselect,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
    			$('#group-cat,.hideForAllemp').show();
        		}else{
        			$('#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_per_details,#employee_master_details').hide();
        			$('#showfield,#customChoose,#customselect,#group-cat,.hideForAllemp').show();
            		}
    		
    		if( stmtReport =='SS001' || stmtReport =='AT001')
    			{
    				$('#group-cat,.hideForAllemp').show();
    				$('#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
    				$('.leave_part1').removeClass('hide');
        		}else{
        			$('.leave_part1').addClass('hide');
        			$('#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
        			$('#group-cat,.hideForAllemp').show();
            		}
    		if(stmtReport =='HR003'){
    			$('#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
    			$('#showYear,#thisyear1,#group-cat,.hideForAllemp').show();
        		}else{
        			$('#showYear,#thisyear1,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details').hide();
        			$('#group-cat,.hideForAllemp').show();
            		}
    		if(stmtReport =='AT005' || stmtReport =='AT006' || stmtReport =='FR002' || stmtReport =='HR005' || stmtReport =='HR006' || stmtReport =='HR007'){
    			$('#thismonth,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
    			$('#showMonYear,#group-cat,.hideForAllemp').show();
    		}else{
        		$('#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
    			$('#group-cat,.hideForAllemp').show();
    			//$('#thismonth,#thismonth1').hide();
    			$('#showMonYear').hide();
    		}
	    	
    		if(stmtReport =='MS001' || stmtReport =='ER004' || stmtReport =='ER005' || stmtReport =='ER008' || stmtReport =='ER009' || stmtReport =='ER010' || stmtReport =='ER012' || stmtReport =='HR002' || stmtReport =='HR003' || stmtReport =='HR004' || stmtReport =='FR001' || stmtReport =='FR003'){
	 	 		$('#stmtFor,#inYear,#periodFrom,#periodTo,#leave_part1').attr('disabled','disabled').trigger("chosen:updated");
	 	 	 	$('.changeYear,#fromPeriod,#toPeriod,#chooseYr,#periodType,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
	 	 	 	$('#fromdt,#todt,#thismonth1,#thismonth,#customfields').hide();
	 	 	 	$('#master_customfields,#group-cat,.hideForAllemp').show();
	 	 	}else if(stmtReport =='AT003' || stmtReport =='AT004' || stmtReport =='BT001' || stmtReport =='BT002' || stmtReport =='BT003' || stmtReport =='BT004' || stmtReport =='BT005' || stmtReport =='BT006' || stmtReport =='BT007' || stmtReport =='HR001' || stmtReport =='EL001' || stmtReport =='EL002' || stmtReport =='EL003' || stmtReport =='EL004' || stmtReport =='ER011' ){
	 	 		$('#stmtFor,#inYear,#periodFrom,#periodTo').attr('disabled','disabled').trigger("chosen:updated");
		 	 	$('#fromdt,#todt,#group-cat,.hideForAllemp').show();
		 	 	$('.changeYear,#fromPeriod,#toPeriod,#chooseYr,#periodType,#thismonth1,#thismonth,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
		 	 	
	    	}else if(stmtReport =='BT001'){
	    		$('#stmtFor,#inYear,#periodFrom,#periodTo').removeAttr('disabled').trigger("chosen:updated");
	    		$('#thismonth1,#thismonth,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
	    		$('.changeYear,#fromPeriod,#toPeriod,#chooseYr,#periodType,#group-cat,.hideForAllemp').show();
	    		$('#fromDate').data('DateTimePicker').date(null);
	    		$('#toDate').data('DateTimePicker').date(null);
	    		$('#fromdt,#todt').show().trigger("chosen:updated");
    		}else if(stmtReport == 'ER001' || stmtReport == 'ER002' || stmtReport =='AT005' || stmtReport =='AT006' || stmtReport =='FR002' || stmtReport =='HR005' || stmtReport =='HR006' || stmtReport =='HR007'){
				if(stmtReport =='ER001' || stmtReport =='ER002'){
	        		$('#thismonth,#thismonth1,#group-cat,.hideForAllemp').show();
	        		var payrollmonth = <?php echo $_SESSION ['monthNo'];?>;
	        		payrollmonth = (payrollmonth.length >1)?payrollmonth:'0'+payrollmonth;
	        		$("#monthFor").val(payrollmonth);
				}else
					$('#group-cat,.hideForAllemp').show();
					$('#thismonth1').show();
        		var payrollmonth = <?php echo $_SESSION ['monthNo'];?>;
        		payrollmonth = (payrollmonth.toString().length >1)?payrollmonth:'0'+payrollmonth;
                $("#monthFor").val(payrollmonth);
        		$('.changeYear,#fromPeriod,#toPeriod,#chooseYr,#periodType,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
        		$('#fromdt,#todt').hide();
    		}else  if(stmtReport == 'HR008'){
        		$('#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').show();
        		$('#branchCat,#empMasterdetails,#employeeStatus,#teamCat,#job_status1,#empPersonaldetails').chosen();
        		$("#periodType,#fromPeriod,#showcustom,#chooseYr,#toPeriod,#group-cat,.hideForAllemp,#fromdt,#todt,#showfield").hide();
        		$('#empMaster_select,#empPersonal_select,#team_select').attr('multiple','multiple').show();
        		$('#empMasterdetails,#empPersonaldetails,#teamCat').val('').trigger('chosen:updated');
        		}
    		else{
	    		$('#stmtFor,#inYear,#periodFrom,#periodTo,#leave_part1').removeAttr('disabled').trigger("chosen:updated");
	    		$('.changeYear,#fromPeriod,#toPeriod,#chooseYr,#periodType,#group-cat,.hideForAllemp').show();
	    		$('#fromdt,#todt,#thismonth1,#thismonth,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
	    		if(stmtReport =='AT001'){
	    			$('#changeYear').val(leaveBasedYear); //set hidden input content
	     	 		$('.changeYear').html(leaveBasedYear).click().hide();//set span content
	     	 		$('#thismonth1,#thismonth,#branchOnly,#teamOnly,#emp_status,#emp_ser_status,#employee_per_details,#employee_master_details').hide();
	    		}
	    		else if(stmtReport =='SS002'){
	        		//periodGet(currentAct,0);
	 		}
    		}
     });
  
     $(document).on('click', ".changeYear", function (e) {
        e.preventDefault();
        stmtReport=$('#salaryStmt :selected').val();
        if(stmtReport !='AT001'){
        	($(this).html()=='FY')?$(this).html('CY'):$(this).html('FY');     
            ($(this).html()=='FY')?$(this).attr("title","Financial Year"):$(this).attr("title","Calendar Year");
         }
        element =  $(this).html();
		$('.changeYear').val(element);
        $('#inYear').html();
        $.ajax({
        	 dataType: 'html',
             type: "POST",
             url: "php/report.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getPayrollYears");?>' },
             beforeSend:function(){
             	$('#loader').loading(true); 
              },
            success: function (data) {
                jsonData = JSON.parse(data);
                setOptionForChosen(jsonData[2],'inYear');
                periodGet();
            	$('#loader').loading(false); 
             }
        });
     });

     $(document).on('change', "#stmtFor,#inYear", function (e) {
         e.preventDefault();
         periodGet();
       });
     
     function periodGet(){
    	 stmtFor=$('#stmtFor :selected').val();
    	 if(stmtFor!='Y'){
    	 $('#inYear_chosen').show();
         }else{
         $('#inYear_chosen').hide();
         }
    	 inYear=$('#inYear :selected').val();
         typeYear=$('.changeYear').html();
         $.ajax({
        	 dataType: 'html',
             type: "POST",
             url: "php/report.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getPeriodOf");?>', reportFor:stmtFor ,
            	 reportYear:inYear,reportType:typeYear},
             beforeSend:function(){
            	 $('#loader').loading(true);  
              },
            success: function (data) {
                jsonData = JSON.parse(data);
                setOptionForChosen(jsonData[2],'periodTo');
                setOptionForChosen(jsonData[2],'periodFrom');
                $('#loader').loading(false); 
             }
        });
       }
     
      $(document).on('change', "#reportCategory", function (e) {
         e.preventDefault();
         categoryName=$('#reportCategory :selected').val();
         if(categoryName==''){
             $('.hideForAllemp').hide();
             }else{
         $.ajax({
             dataType: 'html',
             type: "POST",
             url: "php/report.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getcategoryNames");?>', categoryName:categoryName},
             beforeSend:function(){
            	 $('#loader').loading(true);  
              },
            success: function (data) {
                jsonData = JSON.parse(data);
                setOptionForChosen(jsonData[2],'categoryNames');
                $('.hideForAllemp').show();
                $('#categoryNames_chosen').css('width','100%');
                $('#loader').loading(false); 
             }
        });
        }
       });

     
     function setOptionForChosen(data,idName){
    	 html="";
    	 $('#'+idName).html('');
    	 if(data.length>0){
    	 for(i=0;i<data.length;i++){
    		 if(idName=='inYear' ||  idName=='periodFrom' ){
    			 dataVal=data[i].resultSet.split('#');
    			 html+='<option value="'+dataVal[1]+'*'+dataVal[0]+'">'+dataVal[0]+'</option>';
    	    }else if(idName=='periodTo'){
        	  dataVal=data[i].resultSet.split('#');
			 html+='<option value="'+dataVal[2]+'*'+dataVal[0]+'">'+dataVal[0]+'</option>';
	        }else if(idName=='categoryNames'){
    	    	$('#categoryNames').chosen('destroy').removeAttr('multiple').chosen();
    	    	if($('#reportCategory :selected').val()=='B'){
    	    		html+='<option value="'+data[i].branch_id+'">'+data[i].branch_name+'</option>';
        	    	}else if($('#reportCategory :selected').val()=='F'){
    	    		html+='<option value="'+data[i].department_id+'">'+data[i].department_name+'</option>';
        	    	}else if($('#reportCategory :selected').val()=='D'){
    	    		html+='<option value="'+data[i].designation_id+'">'+data[i].designation_name+'</option>';
        	    	}else if($('#reportCategory :selected').val()=='T'){
        	    		html+='<option value="'+data[i].team_id+'">'+data[i].team_name+'</option>';
        	    	}else if($('#reportCategory :selected').val()=='E'){
        	    	$('#categoryNames').chosen('destroy').attr('multiple', 'multiple').chosen();
    	    		if (data[i].enabled==1){
    	    			html+='<option value="'+data[i].employee_id+'">'+data[i].employee_name+' [ '+data[i].employee_id+' ] </option>';
        	    	}else{
        	    		html+='<option style="color:#FCB322;"  value="'+data[i].employee_id+'" class="disabled">'+data[i].employee_name+' [ '+data[i].employee_id+' ]</option>';
        	    	}
    	    	} 
	        }
         }
    	 }else{
    		 html+='<option>No data Found</option>';
    	 }
         
         $('#'+idName).append(html);
         $('#'+idName).trigger("chosen:updated");
         $('#'+idName).html(html);
    }

</script>
</body>
</html>

