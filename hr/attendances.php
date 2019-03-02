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
<title>Attendance</title>
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
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/timepicker.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style type="text/css" title="currentStyle">
.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
    padding: 10.4px;
}
@media ( min-width : 1200px)  {
.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
   
}}
.table {
 margin-bottom:0px;
}
.my_class
{
   width:47px !important;
   
}
.mispunch,.absent {
    position: relative;
}

.mispunch::before,.absent::before {
    content: "";
    z-index: 2;
    filter: alpha(opacity=0);
    opacity: 1;
    position: absolute;
    left: 50%;
    bottom: 3px;
    margin-left: -6px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 4px 4px;
    border-color: transparent;
}
.mispunch::after,.absent::after {
    content: "";
    z-index: 2;
    filter: alpha(opacity=0);
    opacity: 1;
    height: 0;
    width:100%;
    border-width: 0 0 3px;
    border-style: solid;
    position: absolute;
    left: 0;
    bottom: 0px;
}
.mispunch::after,.mispunch::before {
	 border-bottom-color: #2991d6;
}
.absent::after,.absent::before {
	 border-bottom-color: #ff6666;
}
.getData{
cursor:pointer;
}
.dataTables_scroll{
	overflow: auto !important;
	right: 0px !important;
	width:unset !important;
}
.dataTables_scrollHead
{
overflow:visible !important;
}

.dataTables_scrollHeadInner{
width:100%  !important;
}

#reportTable{
width:195% !important;
}

.dataTables_scrollBody{
	overflow:visible !important;
	
}

.br{
margin-top:10px;
}

.popover.top {
    margin-top: -22px;
    min-height:230px;
}

#lrType_chosen {
	width: 100% !important;
	margin-top: 1px !important;
}

#dayType_chosen {
    width: 100% !important;
}

.dayname{
font-size:10px;
}
 /* Tooltip */
.view1 + .tooltip > .tooltip-inner {
 padding:3px;
 font-size: 10px;
 margin-bottom:-15px;
 margin-left:15px;
 }
 
 
  /* Tooltip on top */
.view1 + .tooltip.top > .tooltip-arrow {
 margin-bottom:-15px;
 
}


 .attDetail + .tooltip > .tooltip-inner {
 padding:3px;
 font-size: 10px;
 margin-bottom:-15px;
 margin-left:15px;
 }

.attDetail + .tooltip.top > .tooltip-arrow {
 margin-top:15px;
 
}

#today{
 border-top: 2px solid rgba(52, 177, 173, 0.71);
 }
#today , #today1{
 border-left: 2px solid rgba(52, 177, 173, 0.71);
 border-right: 2px solid rgba(52, 177, 173, 0.71);
}

.modal-sm{

	width:300px !important;
 }


.m-bot15 {
    margin-bottom: 15px;
}

.lb-xs{
	font-size:9px;
}
.work-status{
	background-color: transparent;
 	border-radius: 9px;
 	padding: 0px 2px 0px 2px; 
 	display: inline-block;
 	font-size: 11px; 
 	margin:2px;
}


</style>
</head>

<body>
	<section id="container" class="">
	<!--header start-->
     <?php
					include_once (__DIR__ . "/header.php");
					require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
					require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/session.class.php");
					
					Session::newInstance ()->_setLeaveRules ();
					$lrArray = Session::newInstance ()->_get ( "leaveRules" );
					$leaveRules = array_merge ( $lrArray ['M'], $lrArray ['Y'], $lrArray ['Q'] );
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
				<section class="panel">
					<header class="panel-heading">
      Attendance For <span id="monthname"><?php
						
$dateObj = DateTime::createFromFormat ( '!m', $_SESSION ['monthNo'] );
  					//echo $month_name = $dateObj->format ( 'F' ) . " " . $_SESSION ['curYear'];
						
						$stmt = "SELECT attendance_period_sdate attendance_dt FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'";
						$result = mysqli_query ( $conn, $stmt );
						$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->conn));
						$payroll_end_date=($row['attendance_dt']!=1)?$row['attendance_dt']-1:'30';
						$payrollmonth = $_SESSION ['monthNo'];
						$year = date('Y')>$_SESSION["payrollYear"]?date('Y'):$_SESSION["payrollYear"];
						$currentmonth = date('m');
						$currentmonthdt = $currentmonthdt = date('Y-m-d');
						$dateObj   = DateTime::createFromFormat('!m', $payrollmonth);
						$payrollmonthdt= $_SESSION["payrollYear"]."-".$dateObj->format('m')."-".$payroll_end_date;
						if($currentmonthdt > $payrollmonthdt && $row['attendance_dt'] !=1){
							if($currentmonth!=12){
							    if(date('d')>=$row['attendance_dt'])
								$currentmonth = date('m')+1;
							}else{
								$currentmonth = 01;
								$year = $year+1;
							}
							$dateObj   = DateTime::createFromFormat('!m', $currentmonth);
							echo $monthName = $dateObj->format('F')." ".$year;
						}elseif($currentmonthdt < $payrollmonthdt){
							$payrollmonth = (strlen($payrollmonth)==2)?$payrollmonth:"0".$payrollmonth;
							$dateObj   = DateTime::createFromFormat('!m', $payrollmonth);
							echo $monthName = $dateObj->format('F')." ".$year;
						}else 
							echo $month_name = date('F Y');
						?>
						</span>
						<span id="month-sel"></span>
						<select id="month_select" class="chooseMonth" name="month_select">
						<?php 
							$stmt = "SELECT attendance_period_sdate attendance_dt FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'";
							$result = mysqli_query ( $conn, $stmt );
							$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->conn));
							$payroll_end_date=($row['attendance_dt']!=1)?$row['attendance_dt']-1:'30';
    						//payroll month with end date
    						$payrollmonth = $_SESSION ['monthNo'];
    						$year = date('Y')>$_SESSION["payrollYear"]?date('Y'):$_SESSION["payrollYear"];
    						$currentmonth = date('m');
    						$currentmonthdt = $currentmonthdt = date('Y-m-d');
    						$dateObj   = DateTime::createFromFormat('!m', $payrollmonth);
    						$payrollmonthdt= $_SESSION["payrollYear"]."-".$dateObj->format('m')."-".$payroll_end_date;
    						if($currentmonthdt > $payrollmonthdt && $row['attendance_dt']!=1){
    							if($currentmonth!=12){
							   if(date('d')>=$row['attendance_dt'])
    								$currentmonth = date('m')+1;
    							}else{
    								$currentmonth = 01;
    								$year = $year+1;
    							}
    						}elseif($currentmonthdt < $payrollmonthdt){
    							$payrollmonth = (strlen($payrollmonth)==2)?$payrollmonth:"0".$payrollmonth;
    							$dateObj   = DateTime::createFromFormat('!m', $payrollmonth);
    							$currentmonth = $payrollmonth;
    						}
    						//current date and month
    						//if current date is >= end of payroll month
    						//nov 20 >= oct 19 (sep20-oct-19) 
    						//Nov, Dec
    						while($currentmonth != 01 && ($currentmonth >= $payrollmonth || $payrollmonth == 12)){
    							$currentmonth = (strlen($currentmonth)==2)?$currentmonth:"0".$currentmonth;
    						    $dateObj   = DateTime::createFromFormat('!m', $currentmonth);
    							$monthName = $dateObj->format('M'); 
    							echo '<option value='.$year.$currentmonth.'>'.$monthName.' '.$year.'</option>';
    							$currentmonth--;
    						}
    						if($currentmonth == 01){
    							$currentmonth = (strlen($currentmonth)==2)?$currentmonth:"0".$currentmonth;
    							$dateObj   = DateTime::createFromFormat('!m', $currentmonth);
    							$monthName = $dateObj->format('M');
    							echo '<option value='.$year.$currentmonth.'>'.$monthName.' '.$year.'</option>';
    							//$dateObj   = DateTime::createFromFormat('!m', $payrollmonth);
    							//$monthName = $dateObj->format('M');
    							//echo '<option value='.($year-1).$payrollmonth.'>'.$monthName.' '.($year-1).'</option>';
    							$year = $year-1; $currentmonth=12;
    							while($currentmonth != 01 && $currentmonth >= $payrollmonth && $payrollmonth != 01){
    								$currentmonth = (strlen($currentmonth)==2)?$currentmonth:"0".$currentmonth;
    								$dateObj   = DateTime::createFromFormat('!m', $currentmonth);
    								$monthName = $dateObj->format('M');
    								echo '<option value='.$year.$currentmonth.'>'.$monthName.' '.$year.'</option>';
    								$currentmonth--;
    							}
    						}
						?>
						</select>
						<?php 
							$stmt = "SELECT attendance_period_sdate attendance_dt FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'";
							$result = mysqli_query ( $conn, $stmt );
							$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->conn));
							$payroll_end_date=($row['attendance_dt']!=1)?$row['attendance_dt']-1:'30';
							//payroll month with end date
							$payrollmonth = $_SESSION ['monthNo'];
							$year = date('Y')>$_SESSION["payrollYear"]?date('Y'):$_SESSION["payrollYear"];
							$currentmonth = date('m');
							$currentmonthdt = $currentmonthdt = date('Y-m-d');
							$dateObj   = DateTime::createFromFormat('!m', $payrollmonth);
							$payrollmonthdt= $_SESSION["payrollYear"]."-".$dateObj->format('m')."-".$payroll_end_date;
							if($currentmonthdt > $payrollmonthdt)
								echo '<span id="monthEdit" style="font-size: 13px; cursor: pointer;"><a><i class="fa fa-pencil"></i></a></span>'; 
						?>
        
        <div class="btn-group pull-right">
        		<a href="#">
							<button id="showhideButton"  type="button"
								class="btn btn-sm btn-success">
								<i class="fa fa-filter"></i> More Filter
							</button>
							</a>
							<a href="lopupdate.php"><button type="button" class="btn btn-sm btn-danger">
								<i class="fa fa-cloud-upload"></i> LOP</button></a>
							<?php 
							$stmt = "SELECT IF(COUNT(employee_id)>0,1,0) is_bio FROM employee_biometric;";
							$result = mysqli_query ( $conn, $stmt );
							$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error(conn));
							if($row['is_bio'] != '0') 
								echo '<button type="button" class="btn btn-sm btn-default" title="Attendance Summary Import" style= "margin-right: 5px; border-radius: 3px;" id="attnSummary"><input type="hidden" id="attn-empId"> <i class="fa fa-rocket" aria-hidden="true"></i> Attn Summary ' .substr($_SESSION['fywithMonth'],0,3).substr($_SESSION["payrollYear"],2,2).'</button>';
							?>
							<?php 
							if($row['is_bio'] == '0')
								echo '<a href="import.php?for=Attendances" target="foo()" title="Attendance Report Import">
								<button type="button" class="btn btn-sm btn-default">
									<i class="fa fa-upload" aria-hidden="true"></i> Import
								</button>
								</a>';
							?>
						</div>
					</header>
                   <div class="panel-body displayHide" id="filterHideShowDiv">
					<form class="form-horizontal" role="form" method="post">
					   <?php
							require_once (LIBRARY_PATH . "/filter.class.php");
							$filter = new Filter ();
							$filter->conn = $conn;
					       echo  $filterHtml = $filter->createFilterForScreen ('attendances');
					    ?>
						<div class="panel-body pull-right showedEmp displayHide">
					     	<button type="submit" class="btn btn-sm btn-success" id="addendanceSubmit">submit</button>
						   <button type="button" class="btn btn-sm btn-danger" id="filterCancel">Cancel</button>
					   </div>
		            </form>
				</div>
				<div id="loader" style="width:97%;height:100%"></div>
				<div class="panel-body adv-table editable-table" id="tableCondent">
				</div>
					
				<!-- modal for  biometric data  starts here-->
			 	<div class="modal fade full-width-modal-right in" id="biomet_data"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height:100%;padding-right: 0px;">		
				      <div class="pull-right modal-sm" style="height:100%;">
				      <div class="modal-content-wrap" style="height:100%;position:fixed;overflow-y:scroll;">
						<div class="modal-content" style="height:100%;position:fixed;overflow-y:scroll;border-radius:0px;">
						   <div class="modal-header" style="border-radius:0px;">
						     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						         <h5 class="modal-title emp_name_bio"></h5></div>
							          <div class="modal-body" style="height:100%;padding: 9px 23px 10px 2px;">
							          	<form class="leave_attend_details">
							           <!-- Attendance & Leave details starts here -->
										<fieldset class="scheduler-border" style="padding: 0.35em 2.625em 0.75em 1.625em;"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 15px;border-bottom:0px;margin-bottom:-0.5px;">Attendance &amp; Leave Details<span class="lms pull-right"></span></legend>
											 <input type="hidden" name="employee_id" class="emp_id1" />
											 <div class="form-group ">
												<div class="emp_status_bio">
												</div>
											 </div>
											 <div class="form-group ">
												<div class="emp_date hide">
													<span class="dayType_tooltip" data-title="Day Type">
													 <select id="dayType" class="form-control dayType" data-title="Day Type" required >
														<option value="FD">FD</option>
														<option value="FH">FH</option>
														<option value="SH">SH</option>
													 </select>
													</span>
												</div>
											 </div>			
											 <textarea class=" form-control reasons hide"  placeholder="Reason" required></textarea>
											 <div class="dan"></div>
											  <button type="button" class="btn btn-xs btn-info edit pull-right"  value="Edit"  style="margin-top:10px;">Edit</button>
												<div class="col-sm-offset-7 row text-center">
													<input type="submit" class="btn btn-xs btn-success submit_data hide" id="submit_data" value="Save" style="margin-top:5px">
													<button type="button" class="btn btn-xs btn-danger bio_cancel hide" id="" value="Cancel"  style="margin-top:6px;margin-right:0.5px;">Cancel</button>
												</div>
										</fieldset>
									</form>
									   <!-- Attendance & Leave details end here -->
							           <!-- Time details starts here -->
							           <form class="time_det" id="time_det" style="margin-top:30px;" method="post"
									    action="">
									    
									    <input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!updateTimeDetails");?>" />
							           		<fieldset class="scheduler-border" style="padding: 0.35em 0.625em 2.5em ;"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 15px;border-bottom:0px;margin-bottom:-0.5px;">
											Time Details <span class="label label-default shift pull-right lb-xs"></span></legend>
											<input type="hidden" name="day_val" class="day_val" />
											<input type="hidden" name="employee_id" class="emp_id1" />
											<input type="hidden" name="employee_date" class="emp_date1" />
											<input type="hidden" name="current_date" class="current_date" />
												<div class="row" style="margin-left: 2px;" >
													<div class=" pull-left">
														<div class="bootstrap-timepicker">
															<div class="input-group input-group-sm m-bot15 ">
																<span class="input-group-addon"><i class="fa fa-sign-in"></i></span>
																	<input class="form-control input-sm check_in timepicker-default"  data-mask="99:99" data-toggle="tooltip" value=""
																		data-title="check In"  id="check_in" name="check_in"  style="width:30%; padding:3px;" required disabled />
																	<input type="hidden" class="old_checkin"    name="old_checkin" />
															</div>
														</div>
														<div class="input-group input-group-sm m-bot15 bootstrap-timepicker">
																<span class="input-group-addon"><i class="fa fa-sign-out"></i></span>
																	<input class="form-control input-sm timepicker-default check_out" id="check_out"  data-mask="99:99" data-toggle="tooltip" value=""
																		data-title="check Out" name="check_out"  style="width:30%; padding:3px;" required disabled />
																	<input type="hidden" class="old_checkout" name="old_checkout"  />
														</div>
												 </div>
												 	<div class=" col-xs-7 work_status pull-right" style="margin-left: -98px;text-align:right;">
															<span class="ot"></span>
															<span class="less_work_hrs"></span>
															<span class="late"></span>
														 	<p class="work_hours" id="work_hours" style="margin-top:10px;"></p>
													</div>
											   </div>
											   <span class="left_punch" >
														<textarea class=" form-control change_reason hide" name="changeReason" placeholder="Change reasons" required></textarea>
												</span>
																								
											    <input class="btn btn-xs btn-info edit_time pull-right" name="edit_time" type="button" value="Edit" style="margin-top:0px;" />
											    <input class="editTime" name="editTime" type="hidden" />
												<div class="col-sm-offset-7 row  text-center">
													<input type="submit" class="btn btn-xs btn-success  hide submit_timedet submit" id="submit_timedet" value="Save" style="margin-top:5px">
													<button type="button" class="btn btn-xs btn-danger time_cancel hide " id="time_cancel" style="margin-top:6px;margin-right:5px;">Cancel</button>
													
												</div>
												<div class="no_of_punch row" style="margin-left: 0px;"></div>
												<div class="punch_alert" style="color: red;"></div>
												
											</fieldset>
										</form>
							          
									   </div>
							           <!-- Time details end here -->
							          </div>
						</div>
						</div>
				   </div>
						<!-- modal for get biometric data  end-->
						
					<div class="popover-markup hidden">
						<section class="panel">
							<div class="head hide">Mail Confirmation</div>
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
						<!-- help block starts here -->
							<div class="helpblock">
                        		<div class="alert" role="alert">
                        		<div class="alert alert-info alert-dismissible fade in" role="alert">
                         			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp;<b>P</b>&nbsp;- Present, &nbsp;<b>FH</b>&nbsp;- First Half, &amp;nbsp<b>SH</b>&nbsp;- Second Half, &nbsp;<b>LOP</b>&nbsp;- loss of pay</p>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp;<b>CO</b>&nbsp;- Comp-off</p>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp;The table shows the dates in attendance period of your company.</p>
  										<p ><i class="fa fa-caret-right" ></i>&nbsp;	Dates which are marked with " - " mentions that the employee is unavailable for the day (maybe he/she is relieving ) which are not calculated for the salary.</p>
  								</div>
								</div>
							</div>
									<!--help block end  -->
				</section>
				
				<!-- page end-->
			</section>
			
		</section>
		
		
		<!--main content end-->
		<!--footer start-->
		<?php include_once (__DIR__."/footer.php");  ?>
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
	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8" src="../js/FixedColumns.min.js"></script>
	<script src="../js/jquery-migrate-1.0.0.js"></script> 
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script type="text/javascript" src="../js/bootstrap-inputmask.min.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          $(document).ready(function () {
          	var current_date="<?php $current_date = date('Y-m-d');echo $current_date;?>" ;
        	$('.current_date').val(current_date);
            $('#container').addClass('sidebar-closed');
        	$('#sidebar').getNiceScroll().hide();
        	$('#month_select,#month-sel').hide();

        	$('#check_in').tooltip({
	            animation:true,
				title:$(this).data('title'),
			});
				
        	$('#check_out').tooltip({
	            animation:true,
				title:$(this).data('title'),
			});
				
        	$('.check_in,.check_out').datetimepicker({
  	      	 format: 'HH:mm '
  	        });
  	        
			 firstDataTable();
			 
	      });
	      
     $("#monthEdit").on('click',function(){
		$('#monthname,#month-sel').hide();
		$('#month_select').show();
     });

     $('#month_select').on('change',function(e){
         e.preventDefault();
         $(this).hide();
         var month =$('.chooseMonth').val();
         var last2 = month.slice(-2);
         var year  = month.substring(0,4);
         var mon = moment(last2, 'M').format('MMMM');
         $('#month-sel').html( mon+' '+year);
         $('#month-sel').toggle();
         firstDataTable();
         });
	 
     $(document).on('click',".getData",function(){
     	 var currentDate = $('.current_date').val();
         var employee=$(this).attr('id');
		 var biodate = $(this).attr('id').split('#')[0];
		  if(currentDate < biodate){
			 $('#time_det').hide();
			 }else{
				 $('#time_det').show();
				 }
		 var empId_date=biodate+'_'+employee.split('#')[1];
		 $("[data-uniq="+empId_date+"]").parent().removeClass('mispunch');
		 var dayType=$(this).attr('data-type');
		 var lr=$(this).attr('data-lr');
		 var lvReason=$(this).attr('data-reason');
		 var device_status=$(this).data("status")
		 
		 getData(employee,biodate,dayType,lr,lvReason,device_status);
     });

    var getData= (function(employee,biodate,dayType,lr,lvReason,device_status) {
    	$('[data-toggle="tooltip"]').tooltip();
    	 $('.check_in,.check_out').attr('disabled',true);
    	 $('#biomet_data').addClass('modal');
    	 $('#biomet_data').modal('show');
    	 $(".edit_time").show();
    	 $(".edit_time").removeClass('hide');
 		 $(".submit_timedet,.time_cancel").addClass('hide');
 		 $('.punch_alert').empty();
 		 
    	 $(".dayType").chosen();
			//var biomonth ='<php echo $_SESSION['payrollYear']."-".($_SESSION['monthNo']);?>';
			//var empID=$(this).attr('id').split('#')[1];
			//var biodate = $(this).attr('id').split('#')[0];
			//var dateval = biomonth+"-"+biodate;
			var empID = employee.split('#')[1];
			var empName=employee.split('#')[2];
			var dateval = biodate;
			$('.emp_id1').val(empID);
			$('.emp_date1').val(biodate);
			var html="";
	   	    var leaveRulesselect = [<?php echo json_encode($leaveRules); ?>];
	   	   
	              html +='<span class="status_tooltip" data-title="Status"><select id="lrType" class="selectOption form-control m-bot15 lrType"  data-toggle="tooltip"  data-title="Status" style="width: 100%;" required disabled><option value="P">Present</option><option value="LOP">LOP</option><option value="WO">Week Off</option><option value="co">CO</option>';
					for(var i=0;i<leaveRulesselect[0].length;i++){
					   html+='<option value="'+leaveRulesselect[0][i].leave_rule_id.toUpperCase()+'"">'+leaveRulesselect[0][i].alias_name+'</option>';
	                }
	              html+='</select></span>';
	              
	        $('.emp_status_bio').html(html);

	        //var lr=$(this).attr('data-lr');
	    	$("#lrType option[value='"+lr+"']").attr("selected", true);
	    	$("#lrType").attr('disabled',true).trigger("chosen:updated");
	        $(".lrType").chosen();
	        $('.status_tooltip').tooltip({
	            animation:true,
				title:$(this).data('title'),
				});
	      
	        //var lvReason=$(this).attr('data-reason');
	        $('.reasons').val(lvReason);
	        var source=$(this).data('request');
	        if(source==1){ 
	        	$('.lms').html('<span class="label label-default lb-xs lms pull-right ">LMS</span>');
	        }else{
          		$('.lms').toggleClass('hide');
	        }
	       
	        if(lr!='P'){
		        $(".edit,.emp_date,.reasons").removeClass('hide');
	        	$(".submit_data,.bio_cancel").addClass('hide');
	        	$("#dayType").attr('disabled',true).trigger("chosen:updated");
	        	$(".reasons").attr('disabled',true);
		        }

			if(lr==''){
		        	$(".emp_date,.bio_cancel,.submit_data,.reasons").addClass('hide');
		        	 $(".edit").removeClass('hide');
			        }
  	    //var date=$(this).attr('id').split('#')[0];
  	    var date= biodate;
  	    var d1 = date.split('-')[2];
  	  	var m1 = date.split('-')[1];
  	  	var y1 = date.split('-')[0];
      	//var empName=$(this).attr('id').split('#')[2];
      	//var dayType=$(this).attr('data-type');
      	var dayss = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
      	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
     	   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
     	 ];
        var  formattedDate =new Date(dateval);
        var m =  monthNames [formattedDate.getUTCMonth()];
       
      	var dayy = new Date(dateval);
		var dayName = days[dayy.getUTCDay()];
		var y = dateval.substring(0, 4);
		var d = dayss[dayy.getUTCDay()];
      	$(this).parent().find('#employeeId').val(empID);
      	$(this).parent().find('#dateVal').val(date);
      	$("#dayType option[value='"+dayType+"']").attr("selected", true);
      	$('.emp_name_bio').html('<input type="hidden" id="dateVal" value="'+date+'"><input type="hidden" id="employeeId"  value="'+empID+'"/><span  class="emp_name_bio" data-title="'+dayName+' '+date+', '+m+' '+y+'"> '+empName+' ['+empID+' ]  <b>'+d1+'-'+m1+'-'+y1+','+d+'</b></span>');
		
          //modal submit functions
		$("#submit_data").unbind("click");
		var tableElement = $(this);
		
          $("#submit_data").on("click", function (e) {
              e.preventDefault();
             
              var employeeId =$('.emp_id1').val();
              var lrType = $('.lrType').val();
              var dayType = $('.dayType').val();
              var uniq_id = biodate+'_'+empID;
  			  var tableElement = $("[data-uniq="+uniq_id+"]");
  			  $("[data-uniq="+uniq_id+"]").children().find("a").html(lrType);
  			  if(lrType=='P')
  	  			  $('.bio_cancel').trigger('click');
			  
			  //var biomonth ='<php echo $_SESSION['payrollYear']."-".($_SESSION['monthNo']);?>';
  			  //var biodate = tableElement.attr('id').split('#')[0];
  			  //var dateVal = biomonth+"-"+biodate;
  			  var dateVal = biodate;
              var lvReason =$('.reasons').val();
              
              if(($('.reasons').val()=='') &&(lrType!='P')){
            		$('.dan').html('<label class="help-block text-danger text">Enter Required field</label>');
            	}else{
            		$('.dan').empty();  
           tableElement.parent().find("span").html(lrType);
           tableElement.parent().find("span").attr('data-type',dayType);
           tableElement.parent().find("span").attr('data-lr',lrType);
           $('#lrType').data('lr');
           tableElement.parent().find("span").attr('data-reason',lvReason);
	            FilteredVal=filterApplied;//only works When Filter is Applied
	            attSearchIds='';
	            if(FilteredVal!=null)
	            	attSearchIds=FilteredVal;
              $.ajax({
	                    dataType: 'html',
	                    type: "POST",
	                    url: "php/leaveaccount.handle.php",
			            cache: false,
	                    data: { act:'<?php echo base64_encode($_SESSION['company_id']."!updateAttendance");?>',employee_id:employeeId,
	                    	    dateVal:dateVal,dayType:dayType,leaveRuleId:lrType,attSearchIds:attSearchIds,reason:lvReason},
                  	 beforeSend:function(){
   		             	$('#submit_data').button('loading'); 
   		              },
   		             complete:function(){
                         	 $('#submit_data').button('reset');
                         //	$('.popover').hide();
                          },
	                   success: function (data) {
	                	   jsonData = JSON.parse(data);
	                	   if(jsonData[0]=='success'){
	                		   BootstrapDialog.alert(jsonData[1]);
      	 					 $('#biomet_data').modal('hide');
	                    	 if(jsonData[02].length>0){
		                    	$('#loader').loading(true);
	                    		//tableCreation(jsonData[02][02]);
     	     		           	$('#loader').loading(false); 
		 		             }else{
	    						$('#tableCondent').html('No data Found');
	    					}}else{
	    						BootstrapDialog.alert(jsonData[1]);
		    					}
	                     }
	                });
          		}
             });

         if(device_status==1){
        //For Time Details
        $('#time_det').removeClass('hide');
			$.ajax({
      		  dataType: 'html',
                type: "POST",
                url: "php/leaveaccount.handle.php",
		          data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getTimeDetails");?>',dateVal:dateval,employee_id:empID},	
		          cache: false,
                success: function (data) { //style="word-wrap: break-word;width:182px;
                    $(".no_of_punch").html('<p class="col-xs-10" style="font-size:15px; margin-left: 50px;">Punches</p><a class="punches col-xs-11 m-bot15" style="color:#3F3F3F"></a>');
						var jsonData = JSON.parse(data);
						var a = [];
						var b = [];
						//check in, check out
						$('#check_in').val(jsonData[2][0].check_in);
						$('.old_checkin').val(jsonData[2][0].check_in);
						$("#check_out").val(jsonData[2][0].check_out);
						$('.old_checkout').val(jsonData[2][0].check_out);
						$('.shift').html(jsonData[2][0].shift_name);
						$('.day_val').val(jsonData[2][0].is_day);

						var old_checkin=$('.old_checkin').val();
						$('.check_in').attr('data-oldcin',old_checkin);
						var old_checkout=$('.old_checkout').val();
						$('.check_out').attr('data-oldcout',old_checkout);
						
											
						if(jsonData[2][0].OT!=0){
								$('.ot').html('<label class="work-status" style="color: #8075c4;border: 1px solid #8075c4;" data-toggle="tooltip" title="Over Time :'+jsonData[2][0].OT+'&nbsp;Hours" data-toggle="tooltip"><b>OT</b></label>');
							}else{
								$('.ot').empty();
							}
						if(jsonData[2][0].less_work_hrs!=0 && jsonData[2][0].less_work_hrs!= null){
								$('.less_work_hrs').html('<label class="work-status" style="color: #FF6C60;border: 1px solid #FF6C60;" data-toggle="tooltip" title="'+jsonData[2][0].less_work_hrs+'&nbsp;&lt; work hours"><b>&lt;Wr</b></label>');
							}else{
								$('.less_work_hrs').empty();
							}
						if(jsonData[2][0].late!=0){
							$('.late').html('<label class="work-status" style="color: #FCB322;border: 1px solid #FCB322;" data-toggle="tooltip" title="'+jsonData[2][0].late+'&nbsp;hours late"><b>Late</b></label>');
	                		}else{
								$('.late').empty();
							}
						$('[data-toggle="tooltip"]').tooltip();   
						if(jsonData[2][0].work_hours!=null && jsonData[2][0].work_hours!=0){
							var work_hrs = jsonData[2][0].work_hours;
							var	work_hr = work_hrs.slice(0,5);
							$('.work_hours').html('<span class="badge bg-info" title="Total working hours">'+work_hr+"&nbsp;Hrs"+'</span>');
						}else{
							$('.work_hours').empty();
						 }
						 
						var html ='';
						for(var i=1;i<jsonData[2].length;i++){
							if(jsonData[2][i].payroll_status=='1'){
								$('.edit,.edit_time').addClass('hide');
							}else{
								$('.edit,.edit_time').removeClass('hide');
							}
							var punch= jsonData[2][i].punches ;
							var reason= jsonData[2][i].reason;
							var id= jsonData[2][i].id;

							if(reason!='')
								html +='<span class="punch_delete" id="punches" data-id='+id+' data-punch='+punch+' data-reason='+reason+' data-toggle="tooltip" title="'+reason+'" style="color: #2991d6; border-radius: 5px; background: #F3F3F3; float: left; font-size: 12px; padding: 2px; border-radius: -2px; border: 1px solid #DDD; margin: 1px 4px 5px 0px;">'+punch+'&nbsp;<i class="fa fa-times" aria-hidden="true" style="cursor: pointer; color: rgba(71, 62, 62, 0.54);"></i></span>';
							else
								html +='<span class="punch_delete" id="punches" data-id='+id+' data-punch='+punch+' data-reason="" style="background: #F3F3F3; border-radius: 5px; float: left; font-size: 12px; padding: 2px; border-radius: -2px; border: 1px solid #DDD; margin: 1px 4px 5px 0px;">'+punch+'&nbsp;<i class="fa fa-times" aria-hidden="true" style="cursor: pointer; color: rgba(71, 62, 62, 0.54);"></i></span>';
							
						}
						empid_date = jsonData[1].split(' ')[2];
						if((i-1)%2!=0)
							$("[data-uniq="+empid_date+"]").parent().addClass('mispunch');
						else
							$("[data-uniq="+empid_date+"]").removeClass('mispunch');
						$('.punches').html(html);
						$('[data-toggle="tooltip"]').tooltip();
						
      	         }	
				});
				// time details end
			}else{
				$('#time_det').addClass('hide');
				}
        	});

    $(document).on('click',".punch_delete",function(){
    	var bioId=$(this).attr('data-id');
    	var bioPunch=$(this).attr('data-punch');
    	var bioReason=$(this).attr('data-reason');
    	bioReason = bioReason.replace('-',' ');
    	if(bioReason!='')
  			alertmsg = "Punch Time: "+bioPunch+"&nbsp;&nbsp;&nbsp; Reason: "+bioReason;
    	else
    		alertmsg = "Punch Time: "+bioPunch;

    	BootstrapDialog.show({
			type: BootstrapDialog.TYPE_SUCCESS,
			title: 'Do you want to Delete this Punch?',
			message: alertmsg,
			buttons: [{
				label: 'OK',
				cssClass: 'btn-primary delete_punchTime',
				action: function(dialogRef){
							$.ajax({
						  		  dataType: 'html',
						            type: "POST",
						            url: "php/leaveaccount.handle.php",
							          data: {act: '<?php echo base64_encode($_SESSION['company_id']."!punchDelete");?>',bioId:bioId},	
							          cache: false,
							          beforeSend:function(){
							             	$('#loader').loading(true); 
							              },
						             success: function (data) {
							             $('.modal-dialog').hide();
							             $('#loader').loading(false); 
						            	data1 = JSON.parse(data);
						            	if (data1[0] == "success") {
		            	 					$("[data-uniq="+data1[2]+"]").trigger('click');
		            	 					$('.punch_alert').html('Punch deleted Successfully..!');
		            	 					dialogRef.close();
		            	 				 }else{
		            	 					$('.punch_alert').empty();
		            	 					$('.punch_alert').html('Punch is not deleted..!');
		                     			  }
						            	}
						    	});
							}
					}],
	 
			});
    });

   $('#biomet_data,#time_det').on('hidden.bs.modal', function () {
    	 $('#biomet_data').removeClass('modal');
    	 $('#time_det').removeClass('modal');
    	 $('.dan').empty();
    });
			
  		
     $(document).on('click',".edit",function(){
		 $(".bio_cancel,.submit_data").removeClass('hide');
		 $("#lrType,.dayType").removeAttr('disabled').trigger("chosen:updated");
		 $("#lrType").on('change',function(){
			 if($('#lrType :selected').val()=='P'){
				 $(".dayType,.reasons,.emp_date").addClass('hide');
				 $(".bio_cancel,.submit_data").removeClass('hide');
				 $("#lrType").removeAttr('disabled').trigger("chosen:updated");
				 }
			 else{
				 $(".dayType,.reasons,.emp_date").removeClass('hide');
				 $(".dayType").removeAttr('disabled').trigger("chosen:updated");
				 $("#lrType,.dayType").attr('disabled',false).trigger("chosen:updated");
				 }
		  });
		 $(".reasons").attr('disabled',false);
		 $(".edit").addClass('hide');
		 $('.dayType_tooltip').tooltip({
	         animation:true,
			 title:$(this).data('title'),
			});	
		});

		
	$(document).on('click',".bio_cancel",function(){
		$('.leave_attend_details')[0].reset();
		$(".edit").removeClass('hide');
		$('.dan').empty();
		$(".emp_date,.reasons,.bio_cancel,.submit_data").addClass('hide');
		$(".lrType,.dayType").attr('disabled',true).trigger("chosen:updated");
    	$(".reasons").attr('disabled',true);
	});

	//for time details	
	$(document).on('click',".edit_time",function(){
		$(".edit_time").addClass('hide');
		$(".editTime").val(1);
		$(".left_punch").show();
		$('.punch_alert').empty();
		$(".punches,.check_in,.check_out").removeAttr('disabled');
		$('#submit_timedet,#time_cancel,.change_reason').removeClass('hide');
	
	});
	
	
	$('#time_det').on('submit',function(e){
		e.preventDefault();
		var old_checkin=$('.old_checkin').val();
		var new_checkin=$('.check_in').val();
		var old_checkout=$('.old_checkout').val();
		var new_checkout=$('.check_out').val();
		
		if((old_checkin!=new_checkin)||(old_checkout!=new_checkout)){
			$.ajax({
				          dataType: 'html',
				          type: "POST",
				          url: "php/leaveaccount.handle.php",
				          cache: false,
				          data:  $(this).serialize(),
				          
				          beforeSend: function () {
			               	$('#submit_timedet').button('loading'); 
			               },
			               complete: function () {
			            	 $('#submit_timedet').button('reset');
			                },
					         success: function (data){
					        	 data1 = JSON.parse(data);
					        	 $('.time_det')[0].reset();
					        	 $('.edit_time').removeClass('hide');
					        	 $(".check_in,.check_out").attr('disabled',true);
					        	 $('.submit_timedet,.time_cancel,.change_reason').addClass('hide');
					        	 $('.punch_alert').empty();
					        	 if (data1[0] == "success"){
		                	 		 $("[data-uniq="+data1[2]+"]").trigger('click');
		                	 		 $('.punch_alert').html('Punch inserted Successfully');
		                	 		 $('#biomet_data').modal('show');
		                	 	 }else{
		                             $('.punch_alert').html('Punch inserted Failed');
		                         }
					          } 
				   });
		}else{
			alert("No changes detected");
			}	
	});
	
	
	$(document).on('click',"#time_cancel",function(){
		$('.time_det')[0].reset();
			var cin=$('.check_in').attr('data-oldcin');
			$('.check_in').val(cin);
			var cout=$('.check_out').attr('data-oldcout');
			$('.check_out').val(cout);
			$(".edit_time").removeClass('hide');
			$('.submit_timedet,.time_cancel,.change_reason').addClass('hide');
			$(".check_in,.check_out").attr('disabled',true);
	});

	//time details end
          function firstDataTable(SearchIds){
        	  var chooseMonth = $(".chooseMonth option:selected").val(); 
              if(SearchIds!=null)
                 filterApplied=SearchIds;
              else
            	  filterApplied=null;

              $.ajax({
        		  dataType: 'html',
                  type: "POST",
                  url: "php/leaveaccount.handle.php",
		          data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getAttendanceReport");?>',attSearchIds:SearchIds, month:chooseMonth},	
		          cache: false,
		          beforeSend:function(){
		             	$('#loader').loading(true); 
		              },
                  success: function (data) {
						jsonData = JSON.parse(data);
						for(var i=0;i<jsonData[2].length;i++){
							$('#attDetails').val(jsonData[2][i].NAME);
							
						}
			            if(jsonData[02].length>0){
					    	tableCreation(jsonData[02]);
			            }else{
							$('#tableCondent').html('No data Found');
						}
					  $('#loader').loading(false);
        	         }	
				});
		}

          function tableCreation(data){
              $('#tableCondent').html('');
        	  var html='';
				html+='<section id="flip-scroll"><table id="reportTable" class="table table-striped  table-bordered cf dataTable"><thead><tr>';
				var columnCount=0; 
				var columnArr=[]; 
				var rowborder = 0;
				var title = '';
				$.each(data[0], function (k, v) {
					if(k!="DEVICE"){ 	
						if(k=="EMPID" || k=="NAME")
							html+='<th class="view1">'+k+'</th>';
						else{
							var day = highlight ="";
							var days = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
							var dayss = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
							var dt = k.split('_')[1];
							var date = dt.split('-')[2];
							var dayy = new Date(dt);
							var dayName = days[dayy.getUTCDay()];
							var today = '<?php echo date("Y-m-d");?>';
							if(dt==today){
								highlight = "today";
							    day = "Today - "; 
							    rowborder = columnCount;
							}
							var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
			       	        var  formattedDate = new Date(dt);
			       	        var m =  monthNames [formattedDate.getUTCMonth()];
			       	        var y = dt.substring(0, 4);
			       	     	title = m+'-'+y;
			       	        var d = dayss[dayy.getUTCDay()];
			       	     if(dayName=='Su' || dayName=='Sa'){
							html+='<th  class="view1" id="'+highlight+'" data-toggle="tooltip" data-placement="top" title="'+day+' '+d+' '+date+', '+m+' '+y+'"><font color="#f7990d">'+((k.split('_')[1]!=null)?date+'<div class="dayname" >'+dayName+'</div>':k)+'</th>';
						 }else{
							html+='<th class="view1" id="'+highlight+'" data-toggle="tooltip" title="'+day+' '+d+' '+date+', '+m+' '+y+'">'+((k.split('_')[1]!=null)?date+'<div class="dayname" >'+dayName+'</div>':k)+'</th>';
							}
						columnArr.push(columnCount);
						}
						columnCount++;
					   }
					});
				
					html+='</tr></thead><tbody>';
					
				for(i=0;i<data.length;i++){ 
					html+='<tr>';
					punch_highlight="";
					var j= device_status =0; 
					columnDefArr=[];
					
					$.each(data[i], function (k, v) {
						var getdt = highlight ="";
						if(k=='NAME')
							name = v;
						if(k=='DEVICE')
							 device_status = v;
						if(j==rowborder && rowborder !=0)
							highlight = "today1";
						if(j!=0 && j!=1 && v!='-')
							columnDefArr.push(j);
						var weekend = ((v.split('-')[1]))?v:'';
						var classData=(j!=0 && j!=1 && v!='-')?'getData':'';
						var punch_highlight = (v=='Y'?'mispunch':(v=='*'?'absent':''));
						dateVal =(j!=0 && j!=1 && k!='DEVICE')?k.split('_')[1]:'';
						idSetVal = (dateVal!="")?dateVal.split('-')[2]:'';
						if(j==0)
							idSet ='#'+v;
						var uniqId = dateVal+idSet.replace('#','_');
						//html+='<td id="'+highlight+'"><span id="'+idSetVal+idSet+"_"+name+'" class="'+classData+'" data-name="" data-lr="'+((v.split('_')[1])?v.split('_')[1]:'')+'" data-reason="'+((v.split('_')[3])?v.split('_')[3]:'')+'" data-type="'+((v.split('_')[2])?v.split('_')[2]:'')+'" data-request="'+((v.split('_')[4])?v.split('_')[4]:'')+'" data-count="'+((v.split('_')[0]!==null)?v.split('_')[0]:'')+'" data-placement="top"> <a href="#" class="days_data" data-toggle="tooltip1" data-title="'+((v=='Z'|| v=='+')?'Present':((v.split('_')[1])?v.split('_')[1].toUpperCase():(v.split('-')[1])?"WeekEnd":v))+'" value ="'+((v=='Z'|| v=='+')?'P':((v.split('_')[1])?v.split('_')[1].toUpperCase():(v.split('-')[1])?(v.split('-')[0]):v))+'" >'+((v=='Z'|| v=='+')?'P':((v.split('_')[1])?v.split('_')[1].toUpperCase():(v.split('-')[1])?(v.split('-')[0]):v))+'</a></span></td>';
						if(k!='DEVICE')
							html+='<td class="'+punch_highlight+'" id="'+highlight+'"><span id="'+dateVal+idSet+"#"+name+'" class="'+classData+'" data-name="" data-lr="'+((v.split('_')[2])?v.split('_')[2]:'')+'" data-reason="'+((v.split('_')[4])?v.split('_')[4]:'')+'" data-type="'+((v.split('_')[3])?v.split('_')[3]:'')+'" data-request="'+((v.split('_')[5])?v.split('_')[5]:'')+'" data-count="'+((v.split('_')[1]!==null)?v.split('_')[1]:'')+'" data-status="'+device_status+'" data-uniq="'+uniqId+'" data-placement="top"><a href="#"class="days_data" data-toggle="tooltip" data-title="'+((v=='Z' || v=='Y' || v=='')?'Present':(v=='a' || v=='*')?'Absent':((v.split('_')[2])?(((v.split('_')[2])=='WO')?"Week Off":(((v.split('_')[2])=='co')?"Compensation Off":((v.split('_')[2])=='PL')?"Privilege Leave":((v.split('_')[2])=='cl')?"Casual Leave":"")):(v.split('-')[1])?((v == 'GH-FD') ? "General Holiday":"Weekend"):""))+'" value ="'+((v=='Z' || v=='Y' || v=='')?'P':(v=='a')?'A':((v.split('_')[2])?v.split('_')[2].toUpperCase():(v.split('-')[1])?(v.split('-')[0]):v))+'" >'+((v=='Z' || v=='Y' || v=='')?'P':(v=='*')?'A':((v.split('_')[2])?v.split('_')[2].toUpperCase():(v.split('-')[1])?(v.split('-')[0]):v))+'</a></span></td>';
							//html+='<td class="'+punch_highlight+'" id="'+highlight+'"><span id="'+dateVal+idSet+"#"+name+'" class="'+classData+'" data-name="" data-lr="'+((v.split('_')[2])?v.split('_')[2]:'')+'" data-reason="'+((v.split('_')[4])?v.split('_')[4]:'')+'" data-type="'+((v.split('_')[3])?v.split('_')[3]:'')+'" data-request="'+((v.split('_')[5])?v.split('_')[5]:'')+'" data-count="'+((v.split('_')[1]!==null)?v.split('_')[1]:'')+'" data-status="'+device_status+'" data-uniq="'+uniqId+'" data-placement="top"><a href="#"class="days_data" data-toggle="tooltip" data-title="'+((v=='Z' || v=='Y' || v=='')?'Present':(v=='a' || v=='*')?'Absent':((v.split('_')[2])?v.split('_')[2].toUpperCase():(v.split('-')[1])?((v == 'GH-FD') ? "General Holiday":"Weekend"):""))+'"                                                                                                                                               value ="'+((v=='Z' || v=='Y' || v=='')?'P':(v=='a')?'A':((v.split('_')[1])?v.split('_')[1].toUpperCase():(v.split('-')[1])?(v.split('-')[0]):v))+'" >'+((v=='Z' || v=='Y' || v=='')?'P':(v=='*')?'A':((v.split('_')[2])?v.split('_')[2].toUpperCase():(v.split('-')[1])?(v.split('-')[0]):v))+'</a></span></td>';
						if(k!='DEVICE')
							j++;
					});
					html+='</tr>';
			    }
			    html+='</tbody></table></section>';
			    $('#tableCondent').html(html);
			    var pdfArr = [0,1];
			    pdfArr = pdfArr.concat(columnArr);
			     var oTable= $('#reportTable').dataTable({
			    	 "aLengthMenu": [
	            	                    [10, 15, 20, -1],
	            	                    [10, 15, 20, "All"] // change per page values here
	            	                ],
	            	                "bSort": false,  // set the initial value
	            	                "iDisplayLength": 10,
		            	            "aoColumnDefs": [
				            	                { "sClass": "my_class","aTargets": columnArr }
				            	            
				            	             
				            	              ],
	            	                "sScrollX": "180%",
	            	         		//"sScrollXInner": "300%",
	            	         		"bScrollCollapse": true,
	            	         		"sScrollY": "100%",
	            	         	    "bPaginate": true,
	            	         	 
        							"bAutoWidth": false,
	            	                 "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'br'<'col-lg-6'i><'col-lg-6'T>><'col-lg-12'p>>",
	            	                "oTableTools": {
	            	                    "aButtons": [
	            	                /*{
	            	                    "sExtends": "pdf",
	            	                     "mColumns": pdfArr,
	            	                    "sPdfOrientation": "landscape",
	            	                    "sPdfMessage": "Branch Details"
	            	                },*/
	            	                
	            	                {
	            	                    "sExtends": "xls",
	            	                    "sTitle": "Attendance for "+title,
	            	                    "mColumns": pdfArr
	            	                }],
	            	             
	            	                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf",
	            	                    
	            	             }
	            	             
	            	            
			     });
			     $('#reportTable tbody').on('mouseover', 'tr', function () {
                     $('[data-toggle="tooltip"]').tooltip({
                        container:"body"
                     });
                 });
					
			      new FixedColumns( oTable, {
			                        	 "iLeftColumns": 2,
			                     		 "iLeftWidth": 350
                                    });
			            
				 $('#reportTable_wrapper .dataTables_filter').html('<div class="input-group">\
	                         <input class="form-control medium" id="branch_searchInput" type="text">\
	                         <span class="input-group-btn">\
	                           <button class="btn btn-white" id="branch_searchFilter" type="button">Search</button>\
	                         </span>\
	                         <span class="input-group-btn">\
	                           <button class="btn btn-white" id="branch_searchClear" type="button">Clear</button>\
	                         </span>\
	                     </div>');
	            $('#reportTable_processing').css('text-align', 'center');
	           
	            jQuery('#reportTable_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
	           // jQuery('#reportTable_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
	            $('#branch_searchInput').on('keyup', function (e) {
	            if (e.keyCode == 13) {
	            oTable.fnFilter($(this).val());
	            } else if (e.keyCode == 27) {
	            $(this).parent().parent().find('input').val("");
	            oTable.fnFilter("");
	            }
	            });
			  
	            $(document).on('click', "#branch_searchFilter", function () {
	            oTable.fnFilter($(this).parent().parent().find('input').val());
	            });
	            $(document).on('click', "#branch_searchClear", function () {
	            $(this).parent().parent().find('input').val("");
	            oTable.fnFilter("");
	            });  
	            $(document).on('click', ".sidebar-toggle-box", function (e) {	
	            	 oTable.fnDraw();
	            	 
	            });

	       }
        
	        $(document).on('click', "#addendanceSubmit", function (e) {
	             e.preventDefault();
	             firstDataTable($("#employeeSelected").chosen().val());
	             $('#attn-empId').val($("#employeeSelected").chosen().val());
	       	     $('#filterCancel').click();  
	       	});

	        $(document).on('click', "#addendanceSubmit", function (e) {
	             e.preventDefault();
	             firstDataTable($("#employeeSelected").chosen().val());
	             
	       	     $('#filterCancel').click();  
	       	});

	    $(document).on('click', "#attnSummary", function (e) {
               e.preventDefault();
               var empID= $('#attn-empId').val();
               if(empID ==''){
            	  alert("Please Choose an Employees Filter");
               }else{
	            	BootstrapDialog.show({
	   					type: BootstrapDialog.TYPE_SUCCESS,
	   					title: 'Information',
	   					message: "Do You want to Process Attendance ?",
	   					buttons: [{
	   						label: 'ok',
	   						cssClass: 'btn-primary attProcess' ,
	   						action: function(dialogRef){
	   						  dialogRef.close();
	   							$.ajax({
	   			                   dataType: 'html',
	   			                   type: "POST",
	   			                   url: "php/leaveaccount.handle.php",
	   			                   cache: false,
	   			                   data: { act: '<?php echo base64_encode($_SESSION['company_id']."!attendanceSummayInsert");?>',employeeId:empID },
	   			                   beforeSend:function(){
	   			                	  
	   			                      $('#attnSummary').button('loading'); 
	   			                      },
	   			                   complete:function(){
	   			                      $('#attnSummary').button('reset');
	   			                      },
	   			                   success:function (){
									  BootstrapDialog.alert("Attendance Processed Successfully");
	   			                   }
	   			         		}); 
	   						}
	   				
	   					}]
	   		      });
          	}
		});
	        /* $(document).on('shown.bs.popover','.getData',function(e){
          	e.preventDefault();
          	 $('.popover').hide();
         	var dateval ='?php echo _SESSION ['current_payroll_month'];?>'
      	    var date=$(this).attr('id').split('_')[0];
          	var empID=$(this).attr('id').split('_')[1];
          	var empName=$(this).attr('id').split('_')[2];
          	var dayType=$(this).attr('data-type');
          	var source=$(this).data('request');
          	var lr=$(this).attr('data-lr');
          	var dayss = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
          	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
	        	   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
	        	 ];
	        var  formattedDate =new Date(dateval);
	        var m =  monthNames [formattedDate.getMonth()];
	       
          	var dayy = new Date(dateval);
			var dayName = days[dayy.getDay()];
			var y = dateval.substring(0, 4);
			var d = dayss[dayy.getDay()];
          	$(this).parent().find('#employeeId').val(empID);
          	$(this).parent().find('#dateVal').val(date);
          	
          	$("#dayType option[value='"+dayType+"']").attr("selected", true);
          	$("#lrType option[value='"+lr+"']").attr("selected", true);
          	$(this).parent().find('.attDetails').html('<span  class="attDetail" data-title="'+dayName+' '+date+', '+m+' '+y+'"> '+empName+' ['+empID+' ]  <b>'+date+','+d+'</b></span>');

        	var reason=$(this).attr('data-reason');
        	if(lr=='P')
	        	$('.dayType,.reas1').hide();
        	if(source==1){ 
	        	$('.source').append("Source:LMS");
	        	$(this).parent().find("#reason2").val(''+reason+'');
          	}else{
          		$('.source').toggleClass('hide');
          		$(this).parent().find("#reason2").val(''+reason+'');
	        }
         	$(this).parent().find('.popover').show();
         	 $('.attDetail').tooltip({
	                animation:true,
				    title:$(this).data('title'),
				}); 
	     });*/
	    
     
</script>
</body>
</html>
