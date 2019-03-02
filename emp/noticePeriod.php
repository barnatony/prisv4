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
<title>Notice Period</title>
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
<link rel="stylesheet" type="text/css" 	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">


<!-- Custom styles for this template -->


<style>
@media ( min-width : 992px) {
	.modal-lg {
		width: 600px;
	}
}
</style>
</head>

<body>
	<section id="container" class="">
		<!--header start-->
     <?php
					
include ("header.php");
					require_once ("../include/lib/employee.class.php");
					$employee = new Employee ();
					$employee->conn = $conn;
					$employeeNotice = $employee->selectColmEmp ( "notice_period,employee_id,employee_name", " FROM employee_work_details where enabled=1 AND employee_id='" . $_SESSION ['employee_id'] . "' " );
					$noticeReason = $employee->selectColmEmp ( "exit_id,reason_code FROM exit_reasons", "" );
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php
												
include_once ("sideMenu.php");
												?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
					<header class="panel-heading">
						Notice Period
						<button type="button"
							class="btn btn-primary btn-sm pull-right css" id="edit">
							<i class="fa fa-pencil"></i> Edit
						</button>
					</header>

					<div class="panel-body">
						<div class="col-lg-12" id="hide_from">
							<form class="form-horizontal" role="form" method="post"
								id="notice_add">
								<input type="hidden" name="act" id="act"
									value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
								<div class="col-lg-12" id="add-department">
									<input type="hidden" name="nId" id="notice_id">
									<div class="panel-body">
										<div class="form-group">
											<div class="col-lg-5">
                                     <?php
																																					foreach ( $employeeNotice as $row ) {
																																						echo "<input type='hidden'  data-id='" . $row ['notice_period'] . "' name='employee_id' id='employee_id' value='" . $row ['employee_id'] . "' />";
																																					}
																																					?>                                    
                                        </div>
										</div>
										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Notice
												Date</label>
											<div class="col-lg-5 input-group">
												<span class="input-group-addon" style="cursor: pointer"><i
													class="fa fa-calendar"></i></span>
												<div class="iconic-input right">
													<input class="form-control datepickerCls" type="text"
														id="datepickerCls" name="nDate" disabled required />
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Last
												Working Date </label>
											<div class="col-lg-5 input-group">
												<span class="input-group-addon" style="cursor: pointer"><i
													class="fa fa-calendar"></i></span>
												<div class="iconic-input right">
													<input class="form-control datepickerCls" type="text"
														id="resignation_date" name="lDate" disabled readonly required />
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Letter
											</label>
											<div class="col-lg-8">
												<textarea class="form-control ckeditor" name="editor1"
													disabled rows="6"></textarea>
											</div>
											<input type="hidden" name="letterCode" id="letter">

										</div>
										<div class="form-group" id="statusFlag">
											<label for="dname" class="col-lg-3 col-sm-3 control-label">Process
												Status </label>
											<div class="col-lg-7">
												<span class="ticket_status"><span
													class="label label-primary" id="estatus"></span></span>
											</div>

										</div>


										<div class="form-group" id="buttons" style="display: none;">
											<div class="col-lg-offset-2 col-lg-5" align="right">
												<button type="submit" class="btn btn-sm btn-success"
													id="noticeAdd">Submit</button>
												<button type="button" class="btn btn-sm  btn-danger"
													id="cancel">Cancel</button>
											</div>
										</div>
									</div>
								</div>

							</form>
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
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>


	<!--script for this page only-->
	<script type="text/javascript" src="../js/ckeditor.js"></script>
<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>

	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>


	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          jQuery(document).ready(function () {
        	  $('#reason').chosen();
              $('.datepickerCls').datetimepicker({
 	        	 format: 'DD/MM/YYYY',   
             });
        	   $("#datepickerCls").blur(function() {
	               exitDateSet();
			  });
 			  
			   var id='<?php echo  $_SESSION['employee_id'] ;?>'
	           $.ajax({
	       		   dataType: 'html',
	                   type: "POST",
	                   url: "../hr/php/noticePeriod.handle.php",
	                   cache: false,
	                   data: { act: '<?php echo base64_encode($_SESSION['company_id']."!employeeNotice");?>',nId:id},
	                 success: function (data) {
	                     jsonobject= JSON.parse(data);
	                     if(jsonobject[2].length!==0){
		                 
$('#datepickerCls').val(jsonobject[2][0].notice_date);
$('#resignation_date').val(jsonobject[2][0].last_working_date);  
CKEDITOR.instances.editor1.setData(jsonobject[2][0].letter_text);

                          if(jsonobject[2][0].status=='P'){
                          $('#act').val('<?php echo base64_encode($_SESSION['company_id']."!update");?>');
	                	  $('#estatus').html('Pending');
	                	  $('#notice_id').val(jsonobject[2][0].notice_id);
	                	  $('#edit').show();
	                      }else  if(jsonobject[2][0].status=='H'){
	                      $('#estatus').html('Hold');
	                      $('#edit').hide();
	                      }else if(jsonobject[2][0].status=='A'){
	                	  $('#estatus').html('Approved');
	                	  $('#edit').hide();
	                      }else if(jsonobject[2][0].status=='D'){
	                    	     $('#estatus').html('Declined');
	                    	     $("#notice_add :input").attr("disabled", false);
		                    	 $('#buttons,#statusFlag').show();
		                    	 $('#edit').hide();
	                      }

	                     }else{
	                    	 $("#notice_add :input").attr("disabled", false);
	                    	 $('#buttons').show();
	                    	 $('#statusFlag,#edit').hide();
	                    	 CKEDITOR.instances.editor1.setReadOnly(false);
	                          }
	                 }	   	         
			   	        });

          });
	                  

         $('#edit').on('click', function (e) { 
              e.preventDefault();
              $(this).hide();
              $("#datepickerCls,#resignation_date").attr("disabled", false);
               CKEDITOR.instances.editor1.setReadOnly(false);
               $('#buttons').show();
               $('#statusFlag').hide();
          });

          $('#cancel').on('click', function (e) { 
              e.preventDefault();
              $("#datepickerCls,#resignation_date").attr("disabled", true);
               CKEDITOR.instances.editor1.setReadOnly(true);
               $('#buttons').hide();
               $('#statusFlag,#edit').show();
         });
          
           function exitDateSet(){
	         	  setTimeout(function () {
	           		 noticeDateset=$('#datepickerCls').val().split('/');
	           		  var noticeDate=new Date(noticeDateset[1]+"/"+noticeDateset[0]+"/"+noticeDateset[2]);  
	           		  var  resignDate=GetFormattedDate(noticeDate.setDate(noticeDate.getDate() +Number($('#employee_id').data('id'))));
	 				   	  $('#resignation_date').val(resignDate);
	 			   	      }, 300);//Get Date Val
	 	   	      }
          

          $('#notice_add').on('submit', function (e) {
              e.preventDefault();
              if ($("#notice_date").val() == '' || $("#resignation_date").val() == '') {
                  $('.model_msg0').click();
                  $('#notice_msg').html('Enter All Requirement');
              }
              else {
            	  var data = CKEDITOR.instances.editor1.getData();
            	  $('#letter').val(data);
                  $.ajax({
                      datatype: "html",
                      type: "POST",
                      url: "../hr/php/noticePeriod.handle.php",
                      cache: false,
                      data: $('#notice_add').serialize(),
                      beforeSend:function(){
                       	$('#noticeAdd').button('loading'); 
                        },
                        complete:function(){
                       	 $('#noticeAdd').button('reset');
                        },
                      success: function (data) {
                          data1 = JSON.parse(data);
                          if (data1[0] == "success") {
                            window.location.href="noticePeriod.php";
                             }
                          else
                              if (data1[0] == "error") {
                                  alert(data1[1]);
                              }

                      }

                  });
              }
          });
          
      </script>


</body>
</html>
