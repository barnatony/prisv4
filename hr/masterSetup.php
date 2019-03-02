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

		<title>Master Setup</title>

		<!-- Bootstrap core CSS -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/bootstrap-reset.css" rel="stylesheet">
		<!--external css-->
		<link href="../css/font-awesome.min.css" rel="stylesheet" />
		<!-- Custom styles for this template -->
		<link href="../css/style.css" rel="stylesheet">
		<link href="../css/style-responsive.css" rel="stylesheet" />
		<style>
		div.ref:hover {
		  background-color:#41cac0;
		} 
		.ref{
		   text-align:center;
		   color:#ffffff; 
		   padding:35px;
		   height:200px;
		   background-color:#189FF2;
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
            <?php include_once (__DIR__."/sideMenu.php");?>
        </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
					<!-- page start-->
					<div class="panel-body">
						<div class="form-group row col-lg-12">
						 <a href="companyProfile.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" >
						        <i class="ifa fa fa-building-o fa-3x" ></i>
						        <h4>Company Profile</h4>
						        <p>Manage Your Company Details,Logo,Resposible persons.</p>
						    </div></a>
						 <a href="orgstr.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" >
						         <i class="fa fa-sitemap fa-3x" ></i>
						         <h4>ORGSTR </h4>
						         <p>Manage Branches, Departments, Designations and payment mode.</p>
						     </div></a>
						
						 <a href="payStructure.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" >
						         <i class="fa fa-plus fa-3x" ></i>
						         <h4>Pay Heads</h4>
						         <p>Manage Monthly,One Time Pay Components for your Company.</p>
						     </div></a>
						 <a href="deductions.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" >
						        <i class="fa fa-minus fa-3x" ></i>
						        <h4>Deductions</h4>
						        <p>Manage your Deductions and their Properties i.e PF,ESI.</p>
						     </div></a>
					     </div>
					     <div class="form-group row col-lg-12">
						 <a href="slabs.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" >
						         <i class="fa fa-bars fa-3x" ></i>
						         <h4>Pay Slabs</h4>
						         <p>Create Predefined Salary Structures for your organisation.</p>
						     </div></a>
						 <a href="payslipDesign.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" > 
						         <i class="fa fa-file-text-o fa-3x" ></i>
						         <h4>Pay Slip</h4>
						         <p>Design the Pay slip, enable protection,customize fields.</p>
						     </div></a>
						 <a href="letterDesign.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" >
						         <i class="fa fa-envelope fa-3x" ></i>
						         <h4>Letters</h4>
						         <p>Manage all your letter formats,Customized letter formats.</p>
						 </div></a>
						 <a href="<?php echo $features['claim']['displayUrl']; ?>" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" > 
						         <i class="fa fa-get-pocket fa-3x" ></i>
						         <h4>Claims</h4>
						         <p>Create Claim Rules, Define who can claim and Process.</p>
						     </div></a>
					     </div>
					     
					     <div class="form-group row col-lg-12">
						 <a href="leaveRule.php" class="clr  col-md-3 col-sm-6" >
						     <div class="ref panel" > 
						         <i class="fa fa-calendar-times-o fa-3x" ></i>
						         <h4>Leave Rules</h4>
						         <p>Predefine leave rules and eligibilities.</p>
						     </div></a>
						 <a href="shift.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" > 
						        <i class="fa fa-clock-o fa-3x" ></i>
						        <h4>Shifts </h4>
						        <p>Manage Shifts, Timings and Weekends.</p>
						     </div></a>
						 <a href="calendar.php" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" >
						         <i class="fa fa-calendar fa-3x" ></i>
						         <h4>Calendar</h4>
						         <p>View your Calendar,Create Holidays and Events.</p>
						     </div></a>
						 <a href="<?php echo $features['asset']['displayUrl']; ?>" class="clr  col-md-3 col-sm-6">
						     <div class="ref panel" > 
						         <i class="fa fa-empire fa-3x" ></i>
						         <h4>Assets</h4>
						         <p>Manage your Company Assets and Track them.</p>
						     </div></a>
						 </div>
						 
						 <div class="form-group row col-lg-12">
						 <a href="devices.php" class="clr  col-md-3 col-sm-6" >
						     <div class="ref panel" > 
						         <i class="fa fa-tablet fa-3x" ></i>
						         <h4>Devices</h4>
						         <p>Manage your Company Biometric Devices.</p>
						     </div></a>
						 </div>
					 </div>
					 
					 
					 
				</section>
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
	
	
</body>
</html>					