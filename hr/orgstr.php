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
<title>ORGSTR</title>
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
<!-- Custom styles for this template -->
<style>
@media ( min-width : 992px) {
	.modal-lg {
		width: 1100px;
	}
}

#e_deduce_in_chosen {
	width: 100% !important;
}
.back{
	margin-top: 10px;
	margin-right:14px;
	margin-left:-6px;
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
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->

					<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
							<i class=" fa fa-arrow-left"></i> All Settings</a>
				</div>
				<header class="panel-heading tab-bg-dark-navy-blue">
					<ul class="nav nav-tabs" id="orgstr_tabs">
						<li><a href="#branch_sub1" data-loaded="false" data-title="branch"
							data-toggle="tab" id="branch_sub"> Branch </a></li>
						<li class=""><a href="#department1" data-loaded="false"
							data-title="department" data-toggle="tab" id="department">
								Department </a></li>
						<li class=""><a href="#designation1" data-loaded="false"
							data-title="designation" data-toggle="tab" id="designation">
								Designation </a></li>
						<li class=""><a href="#job1" data-loaded="false" data-title="job"
							data-toggle="tab" id="job"> Job Status </a></li>
						<li class=""><a href="#payment1" data-loaded="false"
							data-title="payment" data-toggle="tab" id="payment"> Payments
								Mode </a></li>
						<li class=""><a href="#team1" data-loaded="false"
							data-title="team" data-toggle="tab" id="team"> Team
								</a></li>
						<li class="pull-right"><a href="import.php?for=ORGSTR"
							target="foo()" title="Attendance Import">
								<button id="" type="button" class="btn btn-sm btn-default">
									<i class="fa fa-upload" aria-hidden="true"></i> ORGSTR Template
								</button>
						</a></li>
					</ul>
				</header>
				<div class="loader " style="width: 97.9%; height: 20%;"></div>
				<div class="tab-content tasi-tab">

					<div class="tab-pane active" id="branch_sub1">
						<div class="new_model_branch"></div>
					</div>
					<div class="tab-pane" id="designation1">
						<div class="new_model_desi"></div>
					</div>

					<div class="tab-pane" id="department1">
						<div class="new_model_depart"></div>
					</div>

					<div class="tab-pane" id="payment1">
						<div class="new_model_payment"></div>
					</div>

					<div class="tab-pane" id="job1">
						<div class="new_model_job"></div>
					</div>
					
					<div class="tab-pane" id="team1">
						<div class="new_model_team"></div>
					</div>

				</div>
			</section>






			<!-- page end-->
		</section>
		<?php include_once (__DIR__."/footer.php");?>
	</section>
	<!--main content end-->
	<!--footer start-->
			
      <!--footer end-->
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>

	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!--script for this page only-->
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
      $(function(){
      	// Javascript to enable link to tab
      	  var url = document.location.toString();
      	  var tab = "";
      	  if (url.match('#')) {
      		  if(url.split('#')[1].indexOf("?")> 0){
      	        	tab = url.split('#')[1].substring(0,url.split('#')[1].indexOf("?"));
      	        }else{
      	        	tab = url.split('#')[1];
      	        }
      	      $('#orgstr_tabs a[href="#' + tab + '"]').tab('show');
      	      
      	  	var sum = 0;
      		 
      	  }else{
      		 $('#orgstr_tabs a[href="#branch_sub1"]').tab('show');
      		//$('#orgstr_tabs a[href="#team1"]').tab('show');
      		  } 

      	  // Change hash for page-reload
      	  $('#orgstr_tabs a').on('shown.bs.tab', function (e) {
      	      window.location.hash = e.target.hash;
      	  })
      	});
      $('#orgstr_tabs').on('shown.bs.tab', function (e) {
   	   // newly activated tab
   	     window.scrollTo(0, 0);
   	  if($(e.target).data('loaded') === false){
			if($(e.target).data('title') == 'branch'){
				$(".loader").loading(true);
                $(".new_model_branch").load('branch.php');
                $(".loader").loading(false);
                
			}else if($(e.target).data('title') == 'department'){
				 $(".loader").loading(true);
                 $(".new_model_depart").load('departments.php');
                 $(".loader").loading(false);
                 
			}else if($(e.target).data('title') == 'designation'){
				 $(".loader").loading(true);
                 $(".new_model_desi").load('designations.php');
                 $(".loader").loading(false);
			}else if($(e.target).data('title') == 'job'){
			     $(".loader").loading(true);
                 $(".new_model_job").load('jobStatuses.php');
                  $(".loader").loading(false);
			}else if($(e.target).data('title') == 'payment'){
				  $(".loader").loading(true);
                  $(".new_model_payment").load('paymentModes.php');
                   $(".loader").loading(false);
			}else if($(e.target).data('title') == 'team'){
				  $(".loader").loading(true);
                  $(".new_model_team").load('team.php');
                   $(".loader").loading(false);
			}
			//make the tab loaded true to prevent re-loading while clicking.
     		$(e.target).data('loaded',true);
   		}
   	});
        
      </script>


</body>
</html>
