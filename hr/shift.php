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
<title>Shift Timing</title>
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
<link rel="stylesheet" type="text/css" href="../css/timepicker.css" />
<style>
.back {
    margin-top: 10px;
    margin-left: 6px;
    margin-right: 12px;
    }

</style>
</head>
<body>
	<section id="container" class="">
		<!--header start-->
         <?php include("header.php"); 
         require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/shift.class.php");
								    $shift = new Shift ();
									$shift->conn = $conn;
									$generalshift = $shift->getGeneralShift ();
	     ?>
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
				<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
								<i class=" fa fa-arrow-left"></i> All Settings</a>
				   </div>
				<section class="panel" id="shiftsectionHide">
					<header class="panel-heading">
						Shift Timing
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn  btn-sm btn-info">
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</header>

					<div class="panel-body">
							<form class="form-horizontal displayHide" role="form" method="post" id="shiftForm">
									<input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
						    <div class="col-lg-12">
						     <div class="col-lg-6">
									<div class="form-group">

										<label class="col-lg-5 control-label"> Name</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" name="shiftName" id="shiftName"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> Start Time</label>
										<div class="col-lg-7 input-group bootstrap-timepicker">
											<span class="input-group-addon"> <i class="fa fa-clock-o"></i></span>
											<input type="text" class="form-control  timepicker-default" value="00:00"
												name="startTime" id="startPicker"/>

										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> End Time</label>
										<div class="col-lg-7 input-group bootstrap-timepicker">
											<span class="input-group-addon"> <i class="fa fa-clock-o"></i></span>
											<input type="text" class="form-control  timepicker-default" value="00:00"
												name="endTime" id="endPicker"  />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-lg-5 control-label"> Grace in Time</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control " data-mask="99:99" 
												value="00:00" name="graceInTime" id="graceInTime"/>
										</div>
									</div>
									<div class="form-group ">
										<label class="col-lg-5 control-label">Grace out time</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" name="graceOutTime" id="graceOutTime"
												value="00:00" data-mask="99:99"/>
										</div>
									</div>



								</div>
								<div class="col-lg-6">

									<div class="form-group">
										<label class="col-lg-5 control-label"> Early Start</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" name="earlyStart" id="earlyStart"
												value="00:00" data-mask="99:99"/> <span
												class="input-group-addon">HH:MM</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5  control-label"> Late End</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" data-mask="99:99" id="lateEnd"
												value="00:00" name="lateEnd" /> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-lg-5 ">
											<label class="control-label">Is Two days involved?</label>
										</div>
										<div class="col-lg-7 ">
										<input type="checkbox" class="is_night" id="is_night" name="dayType" title="Check if shift involves two days" />&emsp;
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Mini Hours for OT</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" data-mask="99:99" id="minHrsOt"
												value="00:00" name="minHrsOt"/> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Mini Hours for Half Day</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" data-mask="99:99" id="minHrsHalfDay"
												value="00:00" name="minHrsHalfDay"  /> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Mini Hours for Full Day</label>
										<div class="col-lg-7 input-group ">
											<input type="text" class="form-control" data-mask="99:99" id="minHrsFullDay"
												value="00:00"name="minHrsFullDay"/> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>
									<span id="error" class="help-block pull-left" style="color:#ec6459"></span>
									<br><br>
										</div>
								  
                                  	<?php 
									echo '<div  class="adv-table editable-table" ><section id="flip-scroll"><table class="table table-striped table-hover table-bordered cf dataTable generalTable" id="generalTable">
		                                   <thead class="cf"><th>Days</th>
		                                    <th>Monday&emsp;<input data-day="Monday" class="day1" type="checkbox"></th>
													<th>Tuesday&emsp;<input data-day="Tuesday"  class="day1" type="checkbox"></th>
													<th>Wednesday&emsp;<input data-day="Wednesday" class="day1" type="checkbox"></th>
													<th>Thursday&emsp;<input data-day="Thursday" class="day1" type="checkbox"></th>
													<th>Friday&emsp;<input data-day="Friday" class="day1"  type="checkbox"></th>
													<th>Saturday&emsp;<input data-day="Saturday" class="day1" type="checkbox"></th>
				                                    <th>Sunday&emsp;<input data-day="Sunday" class="day1" type="checkbox"></th>
											</thead><tbody>';
									$i=1;
									foreach ( $generalshift as $row ) {
										echo "<tr>";
										foreach($row as $key => $v){
											$text=($key=='weeks')?$v." Week":'';
											$value=($v=='FH')?
											'<label class="checkbox-inline" for="'.$i.'_'.$key.'_FH">FH 
		                                     <input id="'.$i.'_'.$key.'_FH" data-id="'.$i.'_'.$key.'"  data-day="'.$key.'" name="shiftWeekCheck" checked value="FH" type="checkbox">
		
											</label>
										    <label class="checkbox-inline" for="'.$i.'_'.$key.'_SH">SH 
		                                     <input id="'.$i.'_'.$key.'_SH" data-day="'.$key.'" value="SH" type="checkbox">
											</label>
		
		<input type="hidden" name="mapShift['.$i.']['.$key.']">
		':(($v=='SH')?	
											'<label class="checkbox-inline" for="'.$i.'_'.$key.'_FH">FH 
		                                     <input id="'.$i.'_'.$key.'_FH" data-id="'.$i.'_'.$key.'" data-day="'.$key.'" name="shiftWeekCheck" value="FH" type="checkbox">
											</label>
										    <label class="checkbox-inline" for="'.$i.'_'.$key.'_SH">SH 
		                                     <input id="'.$i.'_'.$key.'_SH" checked  data-day="'.$key.'" value="SH" type="checkbox">
											</label>
				<input type="hidden" name="mapShift['.$i.']['.$key.']">
				
				':(($v=='FD')?	
											'<label class="checkbox-inline" for="'.$i.'_'.$key.'_FH">FH 
		                                     <input id="'.$i.'_'.$key.'_FH" checked data-day="'.$key.'" data-id="'.$i.'_'.$key.'" name="shiftWeekCheck" value="FH" type="checkbox">
											</label>
										    <label class="checkbox-inline" for="'.$i.'_'.$key.'_SH">SH 
		                                     <input id="'.$i.'_'.$key.'_SH" checked  data-day="'.$key.'" value="SH" type="checkbox">
											</label>
						<input type="hidden" name="mapShift['.$i.']['.$key.']">
						':(($v=='')?	
											'<label class="checkbox-inline" for="'.$i.'_'.$key.'_FH">FH 
		                                     <input id="'.$i.'_'.$key.'_FH" data-id="'.$i.'_'.$key.'"  data-day="'.$key.'" name="shiftWeekCheck" value="FH" type="checkbox">
											</label>
										    <label class="checkbox-inline" for="'.$i.'_'.$key.'_SH">SH 
		                                     <input id="'.$i.'_'.$key.'_SH" value="SH" data-day="'.$key.'" type="checkbox">
											</label>
								
							<input type="hidden" name="mapShift['.$i.']['.$key.']">
								':'')));
											echo "	<td  style='padding: 6px;'>".$value." $text</td>";
										}
									echo "</tr>";
									 $i++;
									}
									echo '</tbody></table></section></div>'
									?>	
                           <button type="button" class="btn btn-sm btn-danger pull-right" id="cancelShift" style="margin-left:1%">Cancel</button>
                             <button type="submit" class="btn btn-sm btn-success pull-right" id="shiftAdd">Shift Add</button>
						 
							</div>

															</form>
					

						<div id="tableHide"  class="adv-table editable-table">
							
						</div>
						</div>
						</section>
						
<section class="panel displayHide" id="editShiftsection">
					<header class="panel-heading">
						<button type="button" class="btn btn-primary btn-sm pull-left urltoLrpage">
							<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Shift Timing
						</button>
						&nbsp; Shift <em id="shiftNameView"></em>
						<button  type="button"  id="updateClick"class="btn btn-primary btn-sm pull-right css displayHide">
							<i class="fa fa-pencil"> </i> Edit
						</button>
			     		 <button type="button" class="btn btn-danger btn-sm pull-right css urltoLrpage" style="margin-left: 1%;" id="cancelEdit">
							<i class="fa fa-undo"></i> Cancel
						</button>
						<button type="button"  id="editClick" class="btn btn-primary btn-sm pull-right css">
							<i class="fa fa-check-square"></i> Save
						</button>
					</header>

					<div class="panel-body">
					<div id="editLoader" style="width:80%;height:100%"></div>
							<form class="form-horizontal" role="form" method="post" id="editShiftForm">
							<input type="hidden" name="shiftId" id="shiftIdval"/>
									<input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
						    <div class="col-lg-12">
						     <div class="col-lg-6">
									<div class="form-group">

										<label class="col-lg-5 control-label"> Name</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" name="shiftName"
												id="shift_name"  />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> Start Time</label>
										<div class="col-lg-7 input-group bootstrap-timepicker">
											<span class="input-group-addon"> <i class="fa fa-clock-o"></i></span>
											<input type="text" class="form-control  timepicker-default" value="00:00"
												name="startTime" id="start_time"  />

										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> End Time</label>
										<div class="col-lg-7 input-group bootstrap-timepicker">
											<span class="input-group-addon"> <i class="fa fa-clock-o"></i></span>
											<input type="text" class="form-control  timepicker-default" value="00:00"
												name="endTime" id="end_time"  />
										</div>
									</div>
									<div class="form-group">
										<div class="col-lg-5 ">
											<label class="control-label">Is Night Shift?</label>
										</div>
										<div class="col-lg-7 ">
										<input type="checkbox" class="is_night" id="is_night" name="dayType" title="Check if shift involves two days" />&emsp;
											
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 control-label"> Grace in Time</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" data-mask="99:99" value="00:00"
												name="graceInTime" id="grace_inTime"  />
										</div>
									</div>
									<div class="form-group ">
										<label class="col-lg-5 control-label">Grace out time</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" name="graceOutTime" value="00:00"
												data-mask="99:99" id="grace_outTime"  />
										</div>
									</div>



								</div>
								<div class="col-lg-6">

									<div class="form-group">
										<label class="col-lg-5 control-label"> Early Start</label>
										<div class="col-lg-7 input-group bootstrap-timepicker">
											<input type="text" class="form-control" name="earlyStart"
												data-mask="99:99" id="early_start"  /> <span
												class="input-group-addon">HH:MM</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5  control-label"> Late End</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" data-mask="99:99"
												name="lateEnd" id="late_end"  /> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Mini Hours for OT</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" data-mask="99:99"
												name="minHrsOt" id="min_hrs_ot"  /> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Mini Hours for Half Day</label>
										<div class="col-lg-7 input-group">
											<input type="text" class="form-control" data-mask="99:99"
												name="minHrsHalfDay" id="min_hrs_half_day"  /> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 control-label">Mini Hours for Full Day</label>
										<div class="col-lg-7 input-group ">
											<input type="text" class="form-control" data-mask="99:99"
												name="minHrsFullDay" id="min_hrs_full_day"  /> <span
												class="input-group-addon"> HH:MM </span>
										</div>
									</div>
									<span id="e_error" class="help-block pull-left" style="color:#ec6459"></span>
									<br><br>
										</div>
								  
                                  	<?php 
									echo '<section id="flip-scroll"><table class="table table-striped table-hover table-bordered cf dataTable generalTable" id="generalTable">
		                                   <thead class="cf"><th>Days</th>
				                                   <th>Monday&emsp;<input data-day="Monday"  class="day1" type="checkbox"></th>
													<th>Tuesday&emsp;<input data-day="Tuesday"  class="day1" type="checkbox"></th>
													<th>Wednesday&emsp;<input data-day="Wednesday"  class="day1" type="checkbox"></th>
													<th>Thursday&emsp;<input data-day="Thursday"  class="day1" type="checkbox"></th>
													<th>Friday&emsp;<input data-day="Friday"  class="day1"  type="checkbox"></th>
													<th>Saturday&emsp;<input data-day="Saturday" class="day1" type="checkbox"></th>
				                                    <th>Sunday&emsp;<input data-day="Sunday"  class="day1" type="checkbox"></th>
											</thead><tbody>';
									$i=1;
									foreach ( $generalshift as $row ) {
										echo "<tr>";
										foreach($row as $key => $v){
											$text=($key=='weeks')?$v." Week":'';
											$value=($v=='FH')?
											'<label class="checkbox-inline" for="e_'.$i.'_'.$key.'_FH">FH 
		                                     <input id="e_'.$i.'_'.$key.'_FH" data-id="'.$i.'_'.$key.'" data-day="'.$key.'" name="e_shiftWeekCheck" checked value="FH" type="checkbox">
		
											</label>
										    <label class="checkbox-inline" for="e_'.$i.'_'.$key.'_SH">SH 
		                                     <input id="e_'.$i.'_'.$key.'_SH" data-day="'.$key.'"  value="SH" type="checkbox">
											</label>
		
		<input type="hidden" name="mapShift['.$i.']['.$key.']">
		':(($v=='SH')?	
											'<label class="checkbox-inline" for="e_'.$i.'_'.$key.'_FH">FH 
		                                     <input id="e_'.$i.'_'.$key.'_FH" data-id="'.$i.'_'.$key.'"  data-day="'.$key.'" name="e_shiftWeekCheck" value="FH" type="checkbox">
											</label>
										    <label class="checkbox-inline" for="e_'.$i.'_'.$key.'_SH">SH 
		                                     <input id="e_'.$i.'_'.$key.'_SH" checked  data-day="'.$key.'" value="SH" type="checkbox">
											</label>
				<input type="hidden" name="mapShift['.$i.']['.$key.']">
				
				':(($v=='FD')?	
											'<label class="checkbox-inline" for="e_'.$i.'_'.$key.'_FH">FH 
		                                     <input id="e_'.$i.'_'.$key.'_FH" checked data-id="'.$i.'_'.$key.'" data-day="'.$key.'" name="e_shiftWeekCheck" value="FH" type="checkbox">
											</label>
										    <label class="checkbox-inline" for="e_'.$i.'_'.$key.'_SH">SH 
		                                     <input id="e_'.$i.'_'.$key.'_SH" checked  data-day="'.$key.'" value="SH" type="checkbox">
											</label>
						<input type="hidden" name="mapShift['.$i.']['.$key.']">
						':(($v=='')?	
											'<label class="checkbox-inline" for="e_'.$i.'_'.$key.'_FH">FH 
		                                     <input id="e_'.$i.'_'.$key.'_FH" data-id="'.$i.'_'.$key.'" data-day="'.$key.'" name="e_shiftWeekCheck" value="FH" type="checkbox">
											</label>
										    <label class="checkbox-inline" for="e_'.$i.'_'.$key.'_SH">SH 
		                                     <input id="e_'.$i.'_'.$key.'_SH" data-day="'.$key.'" value="SH" type="checkbox">
											</label>
								
							<input type="hidden" name="mapShift['.$i.']['.$key.']">
								':'')));
											echo "	<td  style='padding: 6px;'>".$value." $text</td>";
										}
									echo "</tr>";
									 $i++;
									}
									echo '</tbody></table></section>'
									?>	
															</div>

															</form>		</div>
						
				</section>
				<!-- page end-->
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
	<script type="text/javascript" src="../js/bootstrap-inputmask.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
    <script src="../js/common-scripts.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
     $(document).ready(function () { 
            $('#startPicker,#start_time,#endPicker,#end_time').datetimepicker({
                	  format: 'HH:mm'
                  });
                  getheckData('');
                  selectShifts();

              	if($('.is_night').prop("checked")==true)
					$('.is_night').val(0);
				else 
					$('.is_night').val(1);
     });

     $(document).on('click',".is_night",function(){
				if($(this).prop("checked")==true)
					$('.is_night').val(0);
				else 
					$('.is_night').val(1);
		});

function getheckData(type){
	  	 $("input[name='"+type+"shiftWeekCheck").each( function () {
		  	if($('#'+type+ $(this).data('id')+'_FH').is(":checked") && $('#'+type+  $(this).data('id')+'_SH').is(":checked")){
     			$(this).parent().parent().find('input[type="hidden"]').val('FD');
         		}else if($('#'+type+  $(this).data('id')+'_FH').is(":checked")){
         			$(this).parent().parent().find('input[type="hidden"]').val('FH');
             		}else if($('#'+type+  $(this).data('id')+'_SH').is(":checked")){
             			$(this).parent().parent().find('input[type="hidden"]').val('SH');
                 		}else{
                 			$(this).parent().parent().find('input[type="hidden"]').val('');
                     		}
     });
}

$(document).on('submit', "#shiftForm", function (e) {
    e.preventDefault();
    if($('#shiftName').val()!='' && $('#startPicker').val()!='' && $('#endPicker').val()!='' && $('#graceInTime').val()!='' &&
    $('#graceOutTime').val()!='' && $('#earlyStart').val()!='' && $('#lateEnd').val()!='' && $('#minHrsOt').val()!='' &&   
    $('#minHrsHalfDay').val()!='' && $('#minHrsFullDay').val()!='')     {        
    	getheckData('');
    	$('#error').html('');
        $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/shift.handle.php",
            cache: false,
            data: $('#shiftForm').serialize(),
            beforeSend:function(){
             	$('#shiftAdd').button('loading'); 
              },
           success: function (data) {
                jsonData = JSON.parse(data);
               
                if (jsonData[0] == "success") {
                	 jQuery('#shiftForm').toggle('hide');
                     $('#shiftForm')[0].reset(); $('#tableHide').show();
                     shiftTablecreate(jsonData[2]);
                     $('#shiftAdd').button('reset');
                     BootstrapDialog.alert(jsonData[1]);
                 }
                else
                    if (jsonData[0] == "error") {
                  	  BootstrapDialog.alert(jsonData[2]);
                    }
            }

   });
    }else{
    	$('#error').html('* Enter All Required Fields');
    	}
});

$(document).on('click', "#showhide", function (e) {
    e.preventDefault();
  ( $("#shiftForm").css('display')=='none')?$('#tableHide').hide():$('#tableHide').show();
   $("#shiftForm").toggle('show');
});

$(document).on('click', "#cancelShift", function (e) {
    e.preventDefault();
    $('#shiftForm')[0].reset();
    jQuery('#shiftForm').toggle('hide');
    $('#tableHide').show();
});

            $(document).on('click', "#shift-sample a.enable", function (e) {
                e.preventDefault();
                var shiftId = $(this).data('id');
                var shiftName = $(this).data('name');
                BootstrapDialog.show({
	                title:'Confirmation',
                    message: 'Are Sure you want to Enable <strong>'+ shiftName +'</strong>',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    buttons: [{
                        label: 'Enable',
                        cssClass: 'btn-sm btn-success',
                        autospin: true,
                        action: function(dialogRef){
                        	 $.ajax({
   		                    dataType: 'html',
   		                    type: "POST",
   		                    url: "php/shift.handle.php",
   		                    cache: false,
   		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>', shiftId: shiftId },
   		                   complete:function(){
 		                    	 dialogRef.close();
 		                      },
   		                    success: function (data) {
   		                    	data = JSON.parse(data);
   		                        if (data[0] == "success") {
   		                        	shiftTablecreate(data[2]);
   		                        	BootstrapDialog.alert(data[1]);       		                            
   		                        }else if (data[0] == "error") {
	                                    alert(data[1]);
	                                }
   		                    }
   		                });
                                		                            
                        }
                    }, {
                        label: 'Close',
                        cssClass: 'btn-sm btn-danger',
	                    action: function(dialogRef){
                            dialogRef.close();
                        }
                    }]
                });
            });

            $(document).on('click', "#shift-sample a.disable", function (e) {
                e.preventDefault();
                var shiftId = $(this).data('id');
                var shiftName = $(this).data('name');
                BootstrapDialog.show({
	                title:'Confirmation',
                    message: 'Are Sure you want to Disable <strong>'+ shiftName +'</strong>',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    buttons: [{
                        label: 'Disable',
                        cssClass: 'btn-sm btn-success',
                        autospin: true,
                        action: function(dialogRef){
                        	 $.ajax({
   		                    dataType: 'html',
   		                    type: "POST",
   		                    url: "php/shift.handle.php",
   		                    cache: false,
   		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', shiftId: shiftId },
   		                   complete:function(){
 		                    	 dialogRef.close();
 		                      },
   		                    success: function (data) {
   		                    	data = JSON.parse(data);
   		                        if (data[0] == "success") {
   		                        	shiftTablecreate(data[2]);
   		                        	BootstrapDialog.alert(data[1]);       		                            
   		                        }else if (data[0] == "error") {
	                                    alert(data[1]);
	                                }
   		                    }
   		                });
                                		                            
                        }
                    }, {
                        label: 'Close',
                        cssClass: 'btn-sm btn-danger',
	                    action: function(dialogRef){
                            dialogRef.close();
                        }
                    }]
                });
            });

function selectShifts(){
	 $.ajax({
         dataType: 'html',
         type: "POST",
         url: "php/shift.handle.php",
         cache: false,
         data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>'},
        success: function (data) {
             jsonData = JSON.parse(data);
                 shiftTablecreate(jsonData[02]);
            
         }

     });	
}

function shiftTablecreate(data){
	$('#tableHide').html('');
	var html = '<section id="flip-scroll"><table class="table table-striped table-hover table-bordered" id="shift-sample"><thead><tr>			<th>Shift Name</th>			<th>Day Type</th>         <th>Start Time</th>			<th>End Time</th>   	<th>Grace In</th>			<th>Grace Out</th>			<th>Early</th>			<th>Late</th>			<th>OT</th>			<th>Halfday</th>			<th>Fullday</th>				<th>Actions</th>		</tr>	</thead>	<tbody>';
	   for (var i = 0, len = data.length; i < len; ++i) {
			html += '<tr>';
			ftId='';
		  $.each(data[i], function (k, v) {
			 if(k=='shift_name'){shift_name=v}
			 if(k=='shift_id'){shift_id=v}
			 if(k=='is_day'){
				 if(v == 0){
					 	 v ='<span class="label label-warning">Night</span>';
					 } else{
						 v ='<span class="label label-success">Day</span>';
						 }
				 }
			 if(k=='enabled'){
				  if(v==1){
					  html+='<td style="padding: 6px;"><a class="disable" data-id="'+shift_id+'" data-name="'+shift_name+'" href="" title="Diable"><button class="btn btn-primary btn-xs" style="padding: 1px 4px;"><i class="fa fa-unlock"></i></button></a>  <a href="#"  title="Edit" data-id="'+shift_id+'" class="edit"><button class="btn btn-success btn-xs" ><i class="fa fa-pencil"></i></button></a>   <a href="#" data-click="view" data-id="'+shift_id+'" title="view" class="view"><button class="btn btn-info btn-xs"><i class="fa fa-eye"></i></button></a></td>';
					  }else{
					  html+='<td  style="padding: 6px;"><a class="enable" data-id="'+shift_id+'" data-name="'+shift_name+'" href="" title="Enable"><button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>   <a href="#"  title="Edit" data-id="'+shift_id+'" class="edit"><button class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></button></a>  <a href="#" data-click="view" data-id="'+shift_id+'" title="view" class="view"><button class="btn btn-info btn-xs"><i class="fa fa-eye"></i></button></a></td>';
					  }
              	  }
				  else{
					  if(k!='shift_id')
				   html += '<td>'+v+'</td>';
				  }  
				}); 
  	  html += "</tr>";
  	}
  	html+='</tbody></table></section>';
  	$('#tableHide').html(html);
  	var oTable=$('#shift-sample').dataTable({
  		 "aLengthMenu": [
 	                    [5, 15, 20, -1],
 	                    [5, 15, 20, "All"] // change per page values here
 	                ],

 	                // set the initial value
 	                "iDisplayLength": 5,
 	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
 	               "oTableTools": {
	                    "aButtons": [
	                {
	                    "sExtends": "pdf",
	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
	                    "sPdfOrientation": "landscape",
	                    "sPdfMessage": "Branch Details"
	                },
	                {
	                    "sExtends": "xls",
	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
	                }
	             ],
	                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

	                }
 	               });
  	 $('#shift-sample_wrapper .dataTables_filter').html('<div class="input-group">\
             <input class="form-control medium" id="branch_searchInput" type="text">\
             <span class="input-group-btn">\
               <button class="btn btn-white" id="branch_searchFilter" type="button">Search</button>\
             </span>\
             <span class="input-group-btn">\
               <button class="btn btn-white" id="branch_searchClear" type="button">Clear</button>\
             </span>\
         </div>');
$('#shift-sample_processing').css('text-align', 'center');
//jQuery('#shift-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
jQuery('#shift-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
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
}

//update Shitfs
$(document).on('click', "#shift-sample a.edit,#shift-sample a.view", function (e) {
                e.preventDefault();
              var valueData=$(this).data('click');
               $('#shiftIdval').val($(this).data('id'));
                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/shift.handle.php",
                    cache: false,
                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getShiftMapped");?>', shiftId: $(this).data('id') },
		            beforeSend:function(){
                      $('#editClick').button('loading'); 
                      $('#editLoader').loading(true);
                      },
                   success: function (data) {
                        jsonData = JSON.parse(data);
                        if (jsonData[0] == "success") {
                           $.each(jsonData[02].select[0], function (k, v) {
                            	 $('#'+k).val(v);
                        	 });
                        	$('#shiftNameView').html(jsonData[02].select[0].shift_name);
                        	$('input[type="checkbox"]').prop('checked' , true);
                        	if(jsonData[02].select[0].is_day !="0")
                        		$('input[type="checkbox"]').removeAttr('checked');
                        	var j=1;
                        	for(var i=0;i<jsonData[02].weeksShift.length;i++){
                             $.each(jsonData[02].weeksShift[i], function (k, v) {
                                if(v=='FD'){                       
$('#e_'+j+'_'+k+'_FH,#e_'+j+'_'+k+'_SH').prop('checked', true);
                                 }else if(v=='FH'){
$('#e_'+j+'_'+k+'_FH').prop('checked', true);$('#e_'+j+'_'+k+'_SH').prop('checked', false);
                                     }else if(v=='SH'){
$('#e_'+j+'_'+k+'_FH').prop('checked', false);$('#e_'+j+'_'+k+'_SH').prop('checked', true);
                                     }else {
                                    	 $('#e_'+j+'_'+k+'_FH,#e_'+j+'_'+k+'_SH').prop('checked', false);
                                         }
                            });
                             j++;
                           }

                        	  if(valueData=='view'){
                             	 $("#editShiftForm").find(":input").each(function () {
                             		$(this).attr('readonly', true).attr('disabled', true);
                             		$('#editClick,#cancelEdit').hide();
                             		$('#updateClick').show();
                               });
                                 }else{
                                	 $("#editShiftForm").find(":input").each(function () {
                                  		$(this).attr('readonly', false).attr('disabled', false);
                                  		$('#editClick,#cancelEdit').show();
                                  		$('#updateClick').hide();
                               });
                               }
                              
                        	$('#shiftsectionHide').hide();
                            $('#editShiftsection').show();
                        }
                        $('#editClick').button('reset'); 
                        $('#editLoader').loading(false);
                    }

                });
         });

//update
$(document).on('click', "#updateClick", function (e) {
    e.preventDefault();
    $("#editShiftForm").find(":input").each(function () {
    	$(this).attr('readonly', false).attr('disabled', false);
  		$('#editClick,#cancelEdit').show();
  		$('#updateClick').hide();
   });
});
//update
$(document).on('click', "#editClick", function (e) {
    e.preventDefault();
    if ($("#shift_name").val() == '' || $("#start_time").val() == '' || $("#end_time").val() == '' || $("#grace_inTime").val() == '' || $("#attendance_dependant").val() == '' || $("#carry_forward").val() == '' || $("#max_cf_days").val() == '' || $("#is_encashable").val() == '' || $("#max_enc_days").val() == '' || $("#encashable_on").val() == '' || $("#encahable_timesOf_pay").val() == '' || $("#pro_rata_basis").val() == '' || $("#is_applicable_for_prob").val() == '' || $("#leave_in_middle").val() == '' || $("#leave_in_preceeding").val() == ''
    || $("#grace_outTime").val() == '' ||
    $("#early_start").val() == '' || $("#late_end").val() == '' || $("#min_hrs_ot").val() == ''|| $("#min_hrs_half_day").val() == ''
    	|| $("#min_hrs_full_day").val() == ''|| $("#min_hrs_full_day").val() == '') {
$('#e_error').html('* Enter All Required Fields');
        }
    else {
    	$('#e_error').html('');
    	getheckData('e_');
        $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/shift.handle.php",
            cache: false,
            data: $('#editShiftForm').serialize(),
            beforeSend:function(){
            	$('#editLoader').loading(true); 
              },
            success: function (data) {
                jsonData = JSON.parse(data);
                if (jsonData[0] == "success") {
                	 jQuery('#shiftForm').toggle('hide');
                     $('#shiftForm')[0].reset(); $('#tableHide').show();
                     shiftTablecreate(jsonData[2]);
                     $('#shiftsectionHide').show();
                     $('#editShiftsection,#shiftForm').hide();
                     $('#editLoader').loading(false);
                     BootstrapDialog.alert(jsonData[1]);
                 }
                else
                    if (jsonData[0] == "error") {
                  	  BootstrapDialog.alert(jsonData[2]);
                  	 $('#editLoader').loading(true);
                    }
            }
   });
    }
});

/**  For Checkbox value is checked on whole column **/  
$(document).on('click',".day1",function(){
	 var table = $('.generalTable');
    $(this).attr('checked',true);
   
    if($(this).prop("checked")==true){
        var day1 = $(this).attr('data-day');
   	 ownerIndex = $('.generalTable').find($('th:contains("'+day1+'")')).index();
   	 if(ownerIndex ==1){
          	table.find("[data-day='monday']").prop( 'checked', "checked");
               }else if(ownerIndex ==2){
               	 table.find("[data-day='tuesday']").prop( 'checked', "checked");
                   }else if(ownerIndex ==3){
               	 	table.find("[data-day='wednesday']").prop( 'checked', "checked");
                   	}else if(ownerIndex ==4){
                   	 	table.find("[data-day='thursday']").prop( 'checked', "checked");
                   		}else if(ownerIndex ==5){
                       	 	table.find("[data-day='friday']").prop( 'checked', "checked");
                   			}else if(ownerIndex ==6){
                   				table.find("[data-day='saturday']").prop( 'checked', "checked");
                       			}else if(ownerIndex ==7){
                       				table.find("[data-day='sunday']").prop( 'checked', "checked");
                           			}else{
                               			}
   }else{
   	$(this).attr('checked',false);
   	 var day1 = $(this).attr('data-day');
   	 ownerIndex = $('.generalTable').find($('th:contains("'+day1+'")')).index();
   	if(ownerIndex ==1){
           table.find("[data-day='monday']").removeAttr( 'checked');
              }else if(ownerIndex ==2){
              	 table.find("[data-day='tuesday']").removeAttr( 'checked');
                  }else if(ownerIndex ==3){
              	 	table.find("[data-day='wednesday']").removeAttr( 'checked');
                  	}else if(ownerIndex ==4){
                  	 	table.find("[data-day='thursday']").removeAttr( 'checked');
                  		}else if(ownerIndex ==5){
                      	 	table.find("[data-day='friday']").removeAttr( 'checked');
                  			}else if(ownerIndex ==6){
                  				table.find("[data-day='saturday']").removeAttr( 'checked');
                      			}else if(ownerIndex ==7){
                      				table.find("[data-day='sunday']").removeAttr( 'checked');
                          			}else{
                              			}
       }
      
    });
         
$(document).on('click', ".urltoLrpage", function (e) {
    e.preventDefault();
    $('#shiftsectionHide').show();
    $('#editShiftsection').hide();
});
</script>
</body>
</html>
