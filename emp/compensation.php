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
				<section class="panel" id="shiftsectionHide">
					<header class="panel-heading">
						Compensation Request
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn  btn-sm btn-info">
								<i class="fa fa-plus"></i> Add Request
							</button>
						</div>
					</header>

					<div class="panel-body">
							<form class="form-horizontal displayHide" role="form" method="post" id="comoffForm">
									<input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
						    <div class="col-lg-6">
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
											<input type="text" class="form-control" name="workingFor"  maxlength="50" id="workingFor"/>
											<span id="reasonCode"></span>
										</div>
									</div>
							 <button type="button" class="btn btn-sm btn-danger pull-right" id="cancelShift" style="margin-left:1%">Cancel</button>
                             <button type="submit" class="btn btn-sm btn-success pull-right" id="comtAdd">Request</button>
                           
						 		</div>
                         
							
															</form>
															</div>
															<div class="panel-body">
															<div id="loader" style="width:97%;height:100%"></div>
															<footer class="weather-category displayHide">
                              <ul>
                               <li>
                                      <h5>Not Processed</h5>
                                     <p id="notProcessed">0</p>
                                  </li>
                                   <li>   <h5>Processed</h5>
                                    <p id="processed">0</p>
                                  </li>
                                  <li class="active">
                                      <h5>Elapsed</h5>
                                    <p id="elasped">0</p>
                                  </li>
                                
                              </ul>
                          </footer>
					<div id="tableHide"  class="adv-table editable-table">
							
						</div>

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
	<script src="../js/bootstrap-dialog.js"></script>
      <script src="../js/common-scripts.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
     $(document).ready(function () {
                  $('#dateOfcompoff').datetimepicker({
                	  format: 'DD/MM/YYYY',
                  });
                  var daySearch='<?php echo isset($_REQUEST['day'])?$_REQUEST['day']:null;?>';
                  if(daySearch!='All' && daySearch!='' && daySearch.indexOf("/")>0)
                  selectcompensationWithDate(daySearch);
                  else if(daySearch=='All' && daySearch!='')
                  selectcompensation();
                  else if( daySearch!='' && daySearch.indexOf("/")<0)
                  $('#tableHide').html('You are not working').css('text-align','center'); 
                  else if(daySearch==''){
                  selectcompensation();
                  } 
   });

   //update
     $(document).on('blur', "#dateOfcompoff", function (e) {
         e.preventDefault();
         if($('#dateOfcompoff').val()!=''){
         $.ajax({
             dataType: 'html',
             type: "POST",
             url: "php/compensation.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getHoliday");?>', date :$('#dateOfcompoff').val()},
             beforeSend:function(){
            	 $('#dateOfcompoff').addClass('spinner');
               },
             success: function (data) {
                 jsonData = JSON.parse(data);
                 $('#reasonCode').html('');
                 if (jsonData[0] == "success") {
                	  if(jsonData[2][0].holiday!=null && jsonData[2][0].isApplied==1){
                		  $('#comOffError').html('You have already applied for the day.');
                         	$('#comtAdd').prop('readonly', true);
                          $('#workingFor').val('').prop('readonly', false);
                    	  }else if(jsonData[2][0].holiday!=null && jsonData[2][0].isApplied==0){
                    		  $('#workingFor').val(jsonData[2][0].holiday).prop('readonly', true);
                              $('#comOffError').html('');
                               $('#comtAdd').prop('readonly', false);
                        	  }else{
                        		  $('#comOffError').html('');
                                  $('#reasonCode').html('Enter a Reason eg: Overtime (8 PM to 3AM)');
                                  $('#workingFor').val('').prop('readonly', false);
                                	 $('#editLoader').loading(true);
                            	  }
                 }
                 else
                     if (jsonData[0] == "error") {
                     $('#comOffError').html('');
                     $('#reasonCode').html('Enter a Reason eg: Overtime (8 PM to 3AM)');
                     $('#workingFor').val('').prop('readonly', false);
                   	 $('#editLoader').loading(true);
                     }
                 $('#dateOfcompoff').removeClass('spinner');
             }
    });
         }
     });

     

$(document).on('submit', "#comoffForm", function (e) {
    e.preventDefault();
    if($('#workingFor').val()!='' && $('#dateOfcompoff').val()!='') {        
    	//$('#error').html('');
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
                     $('#elasped').html(((d.Elapsed!=null)?d.Elapsed:0));
                     $('#processed').html(((d.Processed!=null)?d.Processed:0));
                     $('#notProcessed').html(((d.NotProcessed!=null)?d.NotProcessed:0));
                     compoffTablecreate(jsonData[2])
                     BootstrapDialog.alert(jsonData[1]);
                 }
                else
                    if (jsonData[0] == "error") {
                  	  BootstrapDialog.alert(jsonData[1]);
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
            if(jsonData[0]=='success'){
            
           if(jsonData[02].length>0){
        		var d=jsonData[02].shift();
        		 $('#elasped').html(((d.Elapsed!=null)?d.Elapsed:0));
                 $('#processed').html(((d.Processed!=null)?d.Processed:0));
                 $('#notProcessed').html(((d.NotProcessed!=null)?d.NotProcessed:0));
            	compoffTablecreate(jsonData[02]);
                
            }
            }
            $('#loader').loading(false);
         }
      });	
}

function compoffTablecreate(data){
	$('#tableHide').html('');
	var html = '<section id="flip-scroll"><table class="table table-striped  table-hover  cf dataTable" id="shift-sample"><thead><tr><th>Day</th><th>For Working </th><th>Status</th></tr></thead><tbody>';
	   for (var i = 0, len = data.length; i < len; ++i) {
			html += '<tr>';
			ftId='';
		  $.each(data[i], function (k, v) {
			  if(k=='admin_reason'){
				  admin_reason=v;
				  }
			  if(k=='is_processed'){
				  is_processed=v;
				  }
			      if(k!='employee_id'  && k!='compoff_id'  && k!= 'admin_reason'  && k!='day_count' && k!='is_processed' && k!='employee_name'){
                   if(k=='status'){
						if(v=='RQ')
					html+='<td><span title="Requested" class="label label-primary"><i class="fa fa-clock-o" aria-hidden="true"></i> Requested</span></td>';
					else if (v=='R')
					html += '<td><span  title="Reason" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="'+admin_reason+'"   class="label label-danger"><i class="fa fa-ban" aria-hidden="true"></i> Rejected</span> </td>';
					else 
				    html += '<td><span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> '+v+' </span> '+((is_processed!=null && is_processed=='-1')?'&nbsp;<span class="label label-danger">Elasped</span>':((is_processed!=null && is_processed=='1')?'&nbsp;<span class="label label-info">Processed</span>':''))+'</td>';
					}else{
						html+='<td>'+v+'</td>';
					}
      			 }  
		  }); 
  	  html += "</tr>";
  	}
  	html+='</tbody></table></section>';
  	$('.weather-category').show();
  	$('#tableHide').html(html);
  	$('[data-toggle="popover"]').popover();
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
          if(jsonData[02].length>0){
            var d=jsonData[02].shift();
            $('#elasped').html(((d.Elapsed!=null)?d.Elapsed:0));
            $('#processed').html(((d.Processed!=null)?d.Processed:0));
            $('#notProcessed').html(((d.NotProcessed!=null)?d.NotProcessed:0));
            compoffTablecreate(jsonData[02]);
          	}
          else{
              $('#tableHide').html('You are not working').css('text-align','center');} 

          $('#loader').loading(false);
       }
    });	
}


</script>
</body>
</html>
