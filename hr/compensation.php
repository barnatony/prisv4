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
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
a,a:hover ,a:focus{
    color: #fff;
}
.indicator,.getData{
cursor:pointer;
}
#emploChosen_chosen {
	width: 100% !important;
}
.table{
max-width:100% !important;
width:100% !important;
}
</style>
</head>
<body>
	<section id="container" class="">
		<!--header start-->
         <?php include("header.php"); ?>
         <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
              <?php
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
						require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
						$employee = new Employee ();
						$employee->conn = $conn;
						$employeeOnly = $employee->select (1);
						?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel" id="shiftsectionHide">
					<header class="panel-heading">
						Compensation
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn  btn-sm btn-info">
								<i class="fa fa-plus"></i> Give Compensation
							</button>
						</div>
					</header>

					<div class="panel-body">
							<form class="form-horizontal displayHide" role="form" method="post" id="comoffForm">
									<input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
									
									
		                                 
						    <div class="col-lg-6">
						    <div class="form-group">
										<label class="col-lg-5 control-label">Employee Name</label>
										<div class="col-lg-7">
										<select class="form-control" id="emploChosen"
												name="empId">
		                                          <?php
																																												foreach ( $employeeOnly as $row ) {
																																													echo "<option value='" . $row ['employee_id'] . "'>" . $row ['employee_name'] . " [ " . $row ['employee_id'] . " ]<br>" . "</option>";
																																												}
																																												?>
		                                 </select>
		                                 </div>
									</div>
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
										
											<input type="text" class="form-control" name="workingFor"   maxlength="50" id="workingFor"/>
											<span id="reasonCode"></span>
										</div>
									</div>
										<div class="form-group">
                                  <label class="col-lg-5 control-label">Day Count</label>
										<div class="col-lg-7 ">
											<input type="text" class="form-control" class="dayCount" name="dayCount"/>
											<input type="hidden" class="form-control" name="status" id="statusVal"/>
											<span id="reasonCode"></span>
										</div>
									</div>
									<span id="error"></span>
							 <button type="button" class="btn btn-sm btn-danger pull-right" id="cancelShift" style="margin-left:1%">Cancel</button>
                             <button type="button" class="btn btn-sm btn-success pull-right addComp" data-id="2x" style="margin-left:1%">Give 2X Salary</button>
                             <button type="button" class="btn btn-sm btn-success pull-right addComp" data-id="co">Give Comp-off</button>
                             </div>
                          </form>
						</div>
						<div class="panel-body">
							<div id="loader" style="width:97%;height:100%"></div>
					  <div id="tableHide"  class="adv-table editable-table">
							
						</div>

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
  										<p ><i class="fa fa-caret-right" ></i> &nbsp; Specify the Day Count you want to give as compensation</p>
  										<p ><i class="fa fa-caret-right" ></i> &nbsp; Providing &nbsp;<b>Comp-off</b>&nbsp; will add the specified day(s) to CO Account which can be availed later as a holiday with HR's Approval.</p>
  										<p ><i class="fa fa-caret-right" ></i> &nbsp; Providing &nbsp;<b>2x</b>&nbsp; will give the salary for the mentioned day(s) on the next payroll run. This amount will be found under "Other salary" head in Payslip. (If the amount isn't found enable Other salary in Payheads <a href="payStructure.php#payMisc" style="text-decoration: underline;color:blue ">here</a></p>
  								
  								</div>
								</div>
							</div>
									<!--help block end  -->
			</div>
					
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
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
      <script src="../js/common-scripts.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
     $(document).ready(function () {
    	 $('#emploChosen').chosen();
                  $('#dateOfcompoff').datetimepicker({
                	  format: 'DD/MM/YYYY',
                  });
                  var daySearch='<?php echo (isset($_REQUEST['day']))?$_REQUEST['day']:null; ?>';
                  if(daySearch!='All' && daySearch!='' && daySearch.indexOf("/")>0){
                  selectcompensationWithDate(daySearch);
                  dateFlag=1;
                  }
                  else if(daySearch=='All' && daySearch!=''){
                  selectcompensation();
                  dateFlag=0;
                  }
                  else if( daySearch!='' && daySearch.indexOf("/")<0){
                	  $('#tableHide').html('No one is Working').css('text-align','center'); 
                  }
                  else if(daySearch==''){
                  selectcompensation();
                  dateFlag=0;
                  } 
                  $(document).popover({
                		selector: '.getData',
                		html:true,
                		animation: true,
                		content: '<div class="col-lg-12 col-md-7 col-xs-11 input-group" style="margin-bottom:8px;"><input class="form-control dayCount" type="text" placeholder="Day Count"><span style="color:#fff" class="input-group-addon indicator">2X</span></div>', 
               		});
                  
     });

   //update
     $(document).on('blur', "#dateOfcompoff", function (e) {
         e.preventDefault();
         employee_id=$('#emploChosen :selected').val();
         if($('#dateOfcompoff').val()!='' && employee_id!=''){
         $.ajax({
             dataType: 'html',
             type: "POST",
             url: "php/compensation.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getHoliday");?>', date :$('#dateOfcompoff').val(),empId:employee_id},
             beforeSend:function(){
            	 $('#dateOfcompoff').addClass('spinner');
               },
             success: function (data) {
                 jsonData = JSON.parse(data);
                 $('#reasonCode').html('');
                 if (jsonData[0] == "success") {
                	  if(jsonData[2][0].holiday!=null && jsonData[2][0].isApplied==1){
                		  $('#comOffError').html('Already Exits');
                         	$('.addComp').prop('readonly', true);
                          $('#workingFor').val('').prop('readonly', false);
                    	  }else if(jsonData[2][0].holiday!=null && jsonData[2][0].isApplied==0){
                    		  $('#workingFor').val(jsonData[2][0].holiday).prop('readonly', true);
                              $('#comOffError').html('');
                               $('.addComp').prop('readonly', false);
                        	  }else{
                        		  $('#comOffError').html('');
                                  $('#reasonCode').html('Enter a Reason eg: Overtime (8 PM to 3AM)');
                                  $('#workingFor').val('').prop('readonly', false);
                                	 $('#editLoader').loading(true);
                            	  }
                 }
                 else
                     if (jsonData[0] == "error") {
                     $('#reasonCode').html('Enter a Reason eg: Overtime (8 PM to 3AM)');
                     $('#workingFor').val('').prop('readonly', false);
                   	 $('#editLoader').loading(true);
                   	$('#comOffError').html('');
                     }
                 $('#dateOfcompoff').removeClass('spinner');
             }
    });
         }
     });
     

$(document).on('click', ".addComp", function (e) {
    e.preventDefault();
    $('#statusVal').val($(this).data('id'));
    if($('#workingFor').val()=='' || $('#dateOfcompoff').val()=='' || $('.dayCount').val()=='') {        
    	$('#error').html('<label class="text-danger">Enter Required Fields</label');
    }else{
    	$('#error').empty();
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
                     compoffTablecreate(jsonData[2])
                     BootstrapDialog.alert(jsonData[1]);
                 }
                else
                    if (jsonData[0] == "error") {
                     alert(jsonData[1]);
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
        data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>'},
        beforeSend: function () {
            $('#loader').loading(true);
          },
       success: function (data) {
            jsonData = JSON.parse(data);
            if(jsonData[02].length>0)
                compoffTablecreate(jsonData[02]);

            $('#loader').loading(false);
         }
      });	
}


function selectcompensationWithDate(date){
	 $.ajax({
       dataType: 'html',
       type: "POST",
       url: "php/compensation.handle.php",
       cache: false,
       data: { act: '<?php echo base64_encode($_SESSION['company_id']."!search");?>',date:date},
       beforeSend: function () {
           $('#loader').loading(true);
         },
      success: function (data) {
           jsonData = JSON.parse(data);
           if(jsonData[02].length>0)
               compoffTablecreate(jsonData[02]);
           else
               $('#tableHide').html('No one is Working').css('text-align','center'); 

           $('#loader').loading(false);
        }
     });	
}



function compoffTablecreate(data){
	$('#tableHide').html('');
	var html = '<section id="flip-scroll"><table class="table table-striped  table-hover  cf dataTable" id="comoffTable"><thead><tr><th>EmpId</th><th>EmpName</th><th>Day</th><th>Working On</th><th>IsProcess</th><th>Action</th></tr></thead><tbody>';
	   for (var i = 0, len = data.length; i < len; ++i) {
			html += '<tr>';
			ftId='';
			//console.log(data[i].is_processed);
		  $.each(data[i], function (k, v) {
			  if(k=='employee_id'){
				  employee_id=v;
				  }
			  if(k=='compoff_id'){
				  compoff_id=v;
				  }
			  if(k=='date'){
				  date=v;
				  }

			    if(k!= 'admin_reason' && k!='compoff_id' && k!='date' ){
			    	  if(k=='status'){
	                   	if(v=='RQ')
						html+='<td  data-id="'+employee_id+'" data-date="'+date+'" data-co="'+compoff_id+'"><span class="label label-success getData" data-status="'+v+'" id="2X" data-placement="top"> <a href="#"><i class="fa fa-inr" aria-hidden="true"></i> 2X </a></span>&nbsp;<span data-status="'+v+'" class="label label-default getData" id="CO" data-placement="top"> <a href="#"><i class="fa fa-inr" aria-hidden="true"></i> CO </a></span>&nbsp;<span data-status="'+v+'" class="label label-danger getData" id="Reject" data-placement="top"> <a href="#"> X</a></span></td>';
						else if (v=='2X'){
							if(data[i].is_processed != 0)
								html += '<td><span title="DoubleSalary"  class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> 2X </span></td>';
							else
								html += '<td data-id="'+employee_id+'" data-date="'+date+'" data-co="'+compoff_id+'"><span title="DoubleSalary"  class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> 2X </span>&nbsp;<span  class="label label-danger reload" id="Refresh" title="Undo Compensation" data-placement="top"> <a href="#"> <i class="fa fa-undo indicator" aria-hidden="true"></i></a></span></td>';
						}else if (v=='CO')
							if(data[i].is_processed != 0)
								html += '<td><span title="CompOff"  class="label label-warning"><i class="fa fa-check" aria-hidden="true"></i> CO </span></td>';
							else
								html += '<td data-id="'+employee_id+'" data-date="'+date+'" data-co="'+compoff_id+'"><span title="CompOff"  class="label label-warning"><i class="fa fa-check" aria-hidden="true"></i> CO </span>&nbsp;<span  class="label label-danger reload" id="Refresh" title="Undo Compensation" data-placement="top"> <a href="#"> <i class="fa fa-undo indicator" aria-hidden="true"></i></a></span> </td>';
						}else{
						html+='<td>'+v+'</td>';
					}
      			 }  
		  }); 
  	  html += "</tr>";
  	}
  	html+='</tbody></table></section>';
  	$('#tableHide').html(html);
	var oTable=$('#comoffTable').dataTable({
 		 "aLengthMenu": [
	                    [5, 15, 20, -1],
	                    [5, 15, 20, "All"] // change per page values here
	                ],
	                "aaSorting": [[ 2, "desc" ]],
	                // set the initial value
	                "iDisplayLength": 5,
	                "aoColumnDefs": [
                                     {"bSearchable": false, "bVisible": false, "aTargets": [ 4 ] }      
                                   ] ,
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
 	 $('#comoffTable_wrapper .dataTables_filter').html('<div class="input-group">\
            <input class="form-control medium" id="branch_searchInput" type="text">\
            <span class="input-group-btn">\
              <button class="btn btn-white" id="branch_searchFilter" type="button">Search</button>\
            </span>\
            <span class="input-group-btn">\
              <button class="btn btn-white" id="branch_searchClear" type="button">Clear</button>\
            </span>\
        </div>');
$('#comoffTable_processing').css('text-align', 'center');
//jQuery('#comoffTable_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
jQuery('#comoffTable_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
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
$(document).on('click', "#showhide", function (e) {
    e.preventDefault();
   $("#comoffForm").toggle('show');
});

$(document).on('click', "#cancelShift", function (e) {
    e.preventDefault();
    $('#comoffForm')[0].reset();
    jQuery('#comoffForm').toggle('hide');
});

$(document).on('shown.bs.popover','.getData',function(e){
  	e.preventDefault();
  	$('.popover').hide();
  	$('.indicator').html($(this).attr('id'));
  	($(this).attr('id')=='Reject')?$('.indicator').addClass('label-danger'):(($(this).attr('id')=='CO')?$('.indicator').addClass('label-default'):$('.indicator').addClass('label-success'));
  	($(this).attr('id')=='Reject')?$('.dayCount').attr({
  		placeholder:"Enter Reason", 
  	    maxlength:"100"
  	}):'';
  	($(this).attr('id')!='Reject')?$('.dayCount').val(1):'';
  	$(this).parent().find('span#'+$(this).attr('id')).next().show();
 });

 $(document).on('click','.indicator',function(e){
  	e.preventDefault();
  	element=$(this);
  	if($(this).parent().find('input').val()!=null && $(this).parent().find('input').val()){
  	employee_id=$(this).closest('td').data('id');
  	comoff=$(this).closest('td').data('co');
  	date=$(this).closest('td').data('date');
  	var dayCount;
  if($(this).html()=='Reject'){
	status='R';
	adminReason=$(this).parent().find('input').val();
  }else{
	adminReason='';
	status=$(this).html();
	dayCount=$(this).parent().find('input').val(); 
  }
  	 $.ajax({
         dataType: 'html',
         type: "POST",
         url: "php/compensation.handle.php",
         cache: false,
         data: { act: '<?php echo base64_encode($_SESSION['company_id']."!update");?>',empId:employee_id,
                compOff:comoff,dayCount:dayCount,date:date,admin_reason:adminReason,status:status,
                dateFlag:dateFlag},
         beforeSend: function () {
        	 element.button('loading'); 
         },
        success: function (data) {
             jsonData = JSON.parse(data);
             $('.popover').hide();
             $('#loader').loading(true);
             if(jsonData[02].length>0)
                 compoffTablecreate(jsonData[02]);
             else
             $('#tableHide').html('No one is Working').css('text-align','center'); 
             $('#loader').loading(false);
             element.button('reset'); 
          }
       });	
  	}
 });
 $(document).on('click','.reload',function(e){
	 var sts = 'RQ';
	 comoff=$(this).closest('td').data('co');
	 date=$(this).closest('td').data('date');
	 $.ajax({
         dataType: 'html',
         type: "POST",
         url: "php/compensation.handle.php",
         cache: false,
         data: { act: '<?php echo base64_encode($_SESSION['company_id']."!update");?>',empId:employee_id,
        	   compOff:comoff,date:date,status:sts,dateFlag:dateFlag},
         success: function (data) {
             jsonData = JSON.parse(data);
             compoffTablecreate(jsonData[02]);
         }
	 });
 });
 
</script>
</body>
</html>
