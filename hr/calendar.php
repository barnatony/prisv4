<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="BassTechs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Calendar</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/bootstrap-fullcalendar.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<style>
.back{
margin-top: 5px;
margin-right: 10px;
margin-left: 7px;
}
</style>
</head>

<body>

	<section id="container" class="">
		<!--header start-->
      <?php
						
include_once (__DIR__ . "/header.php");
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
				<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
								<i class=" fa fa-arrow-left"></i> All Settings</a>
				   </div>
				<section class="panel">
					<header class="panel-heading">
						Calender
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn btn-sm btn-info"
								style="margin-top: -5px;">
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</header>
					<div class="panel-body">

						<div class="col-lg-12">

							<aside class="col-lg-12">
								<section class="panel">
									<div class="panel-body">

										<div class="col-lg-12" id="add-event-toggle">
											<form class="form-horizontal" role="form" method="post"
												id="eventForm">
												<input type="hidden" name="act"
													value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
												<div class="col-lg-12">
													<div class="form-group">
														<label class="col-lg-3 col-sm-3 control-label">Category</label>
														<div class="col-lg-4">
															<select class="form-control" id="category"
																name="category">
																<option value="">Select Category</option>
																<option value="HOLIDAY">Holiday</option>
																<option value="DUE">Due Day</option>
																<option value="EVENT">Event</option>
															
															</select>
														</div>
													</div>

													<div class="form-group">
														<label  class="col-lg-3 col-sm-3 control-label">Title</label>
														<div class="col-lg-4">
															<input class="form-control" name="title" id="title" type="text">
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 col-sm-3 control-label">Start Date</label>
														<div class="col-lg-4 input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<div class="iconic-input right">
																<input class="form-control dateClass" name="sDate"
																	id="sDate" type="text">
															</div>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 col-sm-3 control-label">End Date</label>
														<div class="col-lg-4 input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<div class="iconic-input right">
																<input class="form-control endDate" name="eDate"
																	id="eDate" type="text">
															</div>
														</div>
													</div>

													<div class="form-group">
														<div class="col-lg-offset-5 col-lg-4">
															<button type="submit" class="btn btn-sm btn-success"
																id="holiSubmit">Create</button>
															<button type="button"
																class="btn btn-sm btn-danger cancel">Cancel</button>
														 	<label id="err"> </label>
														</div>
													</div>

												</div>

											</form>
										</div>
										<br>
										<div id="calendarSub" class="col-lg-12 has-toolbar"></div>
									</div>
								</section>
							</aside>

						</div>
					</div>
				</section>
				<!-- page end-->
			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
	   <?php include_once (__DIR__."/footer.php");?>
   
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript"
		src="../js/jquery-ui-1.9.2.custom.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!--script for this page only-->
	<script src="../js/fullcalendar.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
               $(document).ready(function() {
             calenderInt();
             $('#add-event-toggle').toggle('hide');
              $('#category').chosen();

              $('.dateClass,.endDate').datetimepicker({
              	  format: 'DD/MM/YYYY',
              	  maxDate:false
               });
               });

            

               $(document).on('blur',".dateClass",function(e) {
                   e.preventDefault();
                   setTimeout(function(){  $('#eDate').val($('#sDate').val());
                   }, 100);
               });
               
           $(document).on('click',"#holiSubmit",function(e) {
              e.preventDefault();
               if($('#category:selected').val()!='' && $('#title').val()!='' && $('#sDate').val()!='' && $('#eDate').val()!='') {
            	   $('#err').html('');
                  $.ajax({
                      dataType: 'html',
                      type: "POST",
                      url: "php/event.handle.php",
                      cache: false,
                      data: $('#eventForm').serialize(),
                      beforeSend:function(){
                       	$('#holiSubmit').button('loading'); 
                        },
                        complete:function(){
                       	 $('#holiSubmit').button('reset');
                        },	
   						success: function (data) {
                          if(data=="true"){
                          $('#eventForm')[0].reset();
                          $('#category').trigger('chosen:updated');
                          $('#add-event-toggle').toggle('hide');
                          $('#calendarSub').fullCalendar('destroy');
                          calenderInt();
                          }}
                  });    
               }
               else{
					$('#err').html('Please fill the required details');
				}
                          	  
          });

          $('#showhide').on('click', function (event) {
              $('#add-event-toggle').toggle('show');
          });
          $('.cancel').on('click', function (event) {
              $('#add-event-toggle').toggle('hide');
          });
         

          function calenderInt(){
        	  $.ajax({
                  datatype: "html",
                  type: "POST",
                  url: "php/event.handle.php",
                 cache: false,
                  data:{ act: '<?php echo base64_encode($_SESSION['company_id']."!view");?>' },
                 success: function (data) {
                  	dataSet = JSON.parse(data);
                $('#calendarSub').fullCalendar({
               //  year: '<?php echo $_SESSION['curYear'];?>',
       		  // month: '<?php echo $_SESSION['monthNo']-1;?>', 
       		  date: 01,
                 header: {
	            left: 'prev,next today',
	            center: 'title',
	            right: 'month,basicWeek,basicDay'
	        },
	        events:dataSet,
	        eventClick: function(calEvent, jsEvent, view) {
	             // delete event in backend
	                        	               BootstrapDialog.show({
	                              	                title:'Confirmation',
	                                                  message: 'Are Sure you want to Delete <strong>'+ calEvent.title +'<strong>',
	                                                  buttons: [{
	                                                      label: 'Delete',
	                                                      cssClass: 'btn-primary',
	                                                      autospin: true,
	                                                      action: function(dialogRef){
	                                                      	 $.ajax({
	                                   		                    dataType: 'html',
	                                   		                    type: "POST",
	                                   		                    url: "php/event.handle.php",
	                                   		                    cache: false,
	                                   		                    data: { act: "<?php echo base64_encode($_SESSION['company_id']."!delete");?>",title:calEvent.title },
	                                   		                      complete:function(){
	                                   		                    	 dialogRef.close();
	                                   		                      },
	                                   		                    success: function (data) {
	                                   		                    	  if (data == "true") {
	                                   		                        	BootstrapDialog.alert("Deleted Successfully");
	                                   		                         // delete in frontend
	                                   		        	                $('#calendarSub').fullCalendar('removeEvents', calEvent.id);
	                                   		        	                $('#calendarSub').fullCalendar('destroy');
	                                   		        	             calenderInt();
	                                   		                        }
	                                   		                    }
	                                   		                });
	                                                              		                            
	                                                      }
	                                                  }, {
	                                                      label: 'Close',
	                                                      action: function(dialogRef){
	                                                          dialogRef.close();
	                                                      }
	                                                  }]
	                                              });
	               
	            
	        }
			});
                 }  });}
      </script>


</body>
</html>
