<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="bassTechs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Feature Upgrade</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
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
				<div class="container" style="width: 75%">
					<section class="error-wrapper" style="margin-top: 6%;">
						<h4>Planing to Upgrade ... ??? .... Upgrade On Single Click</h4>
						<button type="submit" class="btn btn-primary start" id="upgradeRequest">
                                              <i class="fa fa-upload"></i>
                                              <span>Upgrade</span>
                                          </button>
					</section>
				</div>

			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>

         <script type="text/javascript">
          $(document).ready(function () {
        	  $(document).on('click', "#upgradeRequest", function (e) {
                  e.preventDefault();
                  var rqtId='<?php echo $_REQUEST['feature'] ?>';
              	$.ajax({
	                   dataType: 'html',
	                   type: "POST",
	                   url: "php/featuresUpgrade.handle.php",
	                   cache: false,
	                   data: { act: '<?php echo base64_encode($_SESSION['company_id']."!upgradeSendEmail");?>',feature:rqtId},
	                   beforeSend:function(){
	                      $('#upgradeRequest').button('loading');
	                       },
	                   success: function (data) {
		                	 var json_obj  = JSON.parse(data);

		                	 if(json_obj[0]='success')
			                	 $('.error-wrapper').addClass('well').html('<h5>Request Sent. Our Team will Contact You shortly.</h5>');
		                	 $('#upgradeRequest').button('reset');
	                   }
	               });
               
        	  });
          });
          </script>
 </body>
</html>