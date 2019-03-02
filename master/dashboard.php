<?php
include_once ("../include/config.php");
if (! empty ( $_SESSION ['master_name'] ) && ! empty ( $_SESSION ['master_id'] )) {
	?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Mosaddek">
<meta name="keyword"
	content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
<link rel="shortcut icon" href="../img/favicon.png">

<title>BassPRIS - A Payroll System</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/morris.css" rel="stylesheet" />
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="../stylesheet" href="css/owl.carousel.css" type="text/css">
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
<style>
h1 {
	font-size: 17px;
}

.morris-hover.morris-default-style {
	background: rgba(23, 21, 21, 0.8) none repeat scroll 0% 0%;
	border: 0px solid rgba(230, 230, 230, 0.8);
}
</style>
</head>

<body>



	<section id="container">
		<!--header start-->
     <?php include("header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>


       <?php include("footer.php"); ?>
  
		<!--main content end-->
		<!--footer start-->

		<!--footer end-->
	</section>
	

    <!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/owl.carousel.js"></script>
	<script src="../js/jquery.customSelect.min.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/morris.min.js" type="text/javascript"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>




	<script>

      //owl carousel

      $(document).ready(function() {
         $("#owl-demo").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true,
			  autoPlay:true

          });
            $('.tooltips').click();
      });

      //custom select box

      $(function(){ 

          $('select.styled').customSelect();
      });

      
  	Morris.Line({
        element: 'liveAuction-graph',
        data: data,
        xkey: 'Rate',
		ykeys:['bidAmount','bid_amount'],
        labels: ['Rate','Rate'],
        lineColors:['#428bca','#5C7399'],
		hideHover: true,
		axes:true,
		parseTime:false,
		smooth:false,
		yLabelFormat:function (y) { return  y.toString() ;},
		});
     
       var tax_data = [
           {"period": "2011 Q3", "licensed": 3407, "sorned": 660},
           {"period": "2011 Q2", "licensed": 3351, "sorned": 629},
           {"period": "2011 Q1", "licensed": 3269, "sorned": 618},
           {"period": "2010 Q4", "licensed": 3246, "sorned": 661},
           {"period": "2009 Q4", "licensed": 3171, "sorned": 676},
           {"period": "2008 Q4", "licensed": 3155, "sorned": 681},
           {"period": "2007 Q4", "licensed": 3226, "sorned": 620},
           {"period": "2006 Q4", "licensed": 3245, "sorned": null},
           {"period": "2005 Q4", "licensed": 3289, "sorned": null}
      ];
      Morris.Line({
        element: 'hero-graph',
        data: tax_data,
        xkey: 'period',
        ykeys: ['licensed', 'sorned'],
        labels: ['Licensed', 'Off the road'],
        lineColors:['#fff','#fff']
      });   
  </script>

</body>
</html>
<?php

} else {
	die ( header ( 'Location:../' ) );
}
?>
