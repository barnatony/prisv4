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
<link rel="stylesheet" type="text/css" href="../css/datepicker.css" />

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
				<section class="panel">
					<header class="panel-heading"> Calender </header>
					<div class="panel-body">

						<div class="col-lg-12">

							<aside class="col-lg-12">
								<section class="panel">
									<div class="panel-body">
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
		<?php include("footer.php"); ?>
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
	<script src="../js/bootstrap-datepicker.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
               $(document).ready(function() {
             calenderInt();
             $('#add-event-toggle').toggle('hide');
              $('#category').chosen();
        	  $('.dateClass').datepicker({
                  format: 'dd/mm/yyyy',
       
              	 
              }).on('changeDate', function(e){
                  if(e.viewMode == 'days')
            		  $(this).datepicker('hide');
                  $('#eDate').val($('#sDate').val());
              }); 
        	  $('.endDate').datepicker({
                  format: 'dd/mm/yyyy',
              	 
              }).on('changeDate', function(e){
                  if(e.viewMode == 'days')
            		  $(this).datepicker('hide');
                  }); 
          });

             


          

          function calenderInt(){
        	  $.ajax({
                  datatype: "html",
                  type: "POST",
                  url: "../hr/php/event.handle.php",
                 cache: false,
                  data:{ act: '<?php echo base64_encode($_SESSION['company_id']."!view");?>' },
                 success: function (data) {
                  	dataSet = JSON.parse(data);
                $('#calendarSub').fullCalendar({
       		  date: 01,
                 header: {
	            left: 'prev,next today',
	            center: 'title',
	            right: 'month,basicWeek,basicDay'
	        },
	        events:dataSet,
                });
                 }
        	  });
                 
        	  }
      </script>


</body>
</html>
