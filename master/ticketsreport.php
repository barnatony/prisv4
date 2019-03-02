
<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Basstechs">

<link rel="shortcut icon" href="img/favicon.png">
<title>Ticket Reports</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
<style>
.alert {
	padding-top: 2px !important;
	padding-bottom: 1px !important;
	margin-bottom: 0px;
}

.form-control {
	margin-bottom: 10px;
}

#ajax_loader_ticket, #ajax_loader {
	display: block;
	margin: 0 auto;
}

.bio-graph-info {
	background: #FAFAFA;
}

.bio-graph-heading {
	background: #42A5F5;
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
         </aside>
         <section id="main-content">
         <section class="wrapper site-min-height">
            <section class="panel" id="section_hide">
	 				<header class="panel-heading"> Report Tickets </header>
			

					<div class="panel-body">
					<form method="post" action="../master/php/ticket.handle.php" id="reportsId" class="report_ticket">
				 <div class="container col-lg-12">
				 
				 <input type="hidden" name="act" id="act"
									value="<?php echo "getTicketsbyDuration" ?>" />
									<label class="col-lg-2 control-label" style="font-weight: 300;font-size: 14px;text-align:right;margin-right:155px;">From </label>
									<div class="col-lg-2 input-group " style="margin-left: -147px">
										<span class="input-group-addon" style="cursor: pointer;"><i class="fa fa-calendar"></i></span>
										<div class="iconic-input right">
											<input class="form-control" name="from" id="datepicker"  type="text" style="width: 200px;">

										</div>
									</div>
									<label class="col-lg-2 control-label" style="font-weight: 300;font-size: 14px;text-align: left;margin-left:19px;">To</label>
									<div class="col-lg-2 input-group " style="margin-left:-154px;">
										<span class="input-group-addon" style="cursor: pointer;"><i class="fa fa-calendar"></i></span>
										<div class="iconic-input right">
											<input class="form-control" name="to" id="datepicker1"  type="text" style="width: 200px;">

										</div>
									</div>
									</div>
									
									
									<button type="submit" class="btn btn-default" id="generateButton" style="margin-left:735px;margin-top:10px;">Generate</button>
									<button class="btn btn-sm btn-default hide pull-right" id="export-to-excel" type="button" style="margin-top:12px;margin-right: 401px;"><i class="fa fa-file-excel-o"></i> Excel</button>
									<div id="tableContent" style="margin-top: 28px;"></div>
									</form>
								</div>
								
		</section>						
		</section>						
	  </section>					
	  <?php include 'footer.php'?>
      <!--footer end-->
	
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

	<!--common script for all pages-->
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/jquery.table2excel.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/common-scripts.js"></script>
	
	<!--script for this page only-->
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
	
	 $(document).ready(function () {
		
		 $('#datepicker').datetimepicker({
			    format: 'DD/MM/YYYY',
			    maxDate:false
		 });
		 $('#datepicker1').datetimepicker({
			   format: 'DD/MM/YYYY',
			   maxDate:false
			   
			 
		 });
		 $(document).on('click', "#export-to-excel", function (e) {
	    	  e.preventDefault();
	    	  ResultsToTable();
	     });
	 });
	    function ResultsToTable(){
		   
	        $("#tableContent").table2excel({
		       
	            exclude: ".excludeThisClass",
	            name: "Results",
	            fileext:".xls",
	            filename: "Ticket Raised"
	        });
	    }  
	 $(".report_ticket").on("submit",function(e){
		 e.preventDefault();
		 $.ajax({
			 processData: false,
		     contentType: false,
		     type: "POST",
		     url: "../master/php/ticket.handle.php",
		     cache: false,
		     data: new FormData(this),
             success: function (data) {
              data = JSON.parse(data);
              $('#export-to-excel').removeClass('hide');
              var reports= data[2];
              //var html =data[3];
             
             var html="<table style='width:100%'><th><td colspan='8' style='text-align:center;font-weight: bold;'> Tickets raised between "+$('#datepicker').val()+" and " +$('#datepicker1').val()+ "</td></th></table>";
              html +="<table style='width:100%' class='table table-bordered' id='report_table' ><tbody>";
              
              html +="<th>TicketId</th>";html+="<th>CompanyName</th>";html+="<th>Subject</th>";html+="<th>Category</th>";
              html +="<th>Priority</th>";html+="<th>Status</th>";html+="<th>Created On</th>";html+="<th>Updated_On</th>";
              html += "<th>Duration</th>";
              for(i=0;i<reports.length;i++){
                  html += "<tr>";         
              $.each(reports[i],function(k,v){
                  
                    html +="<td>" +v+ "</td>";
              });
                    html +="</tr>";
              }
              html+='<tbody></table>';
              if(data[0]=='error'){
            	$('#tableContent').empty().append("<table style='width:100%'><th><td style='text-align:center;font-weight:bold;'>No Available Data To Generate Ticket Reports</td></th></table>");
              }else{
              $('#tableContent').empty().append(html);
              
             }
             }
         });
	 });
	</script>
	
</body>
</html>
