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
<title>pt Report</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<style>
@import "../css/reportTable.css";

#control_label_month {
	width: 10% !important;
}
</style>


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
				<section class="panel">
					<header class="panel-heading"> PT Reports </header>
					<div class="panel-body">
						<form class="form-horizontal" id="ptReportForm">
							<div class="col-lg-12 row">
								<label class="col-sm-2 control-label" id="control_label_month"
									style="text-align: right">Month</label>
								<div class="col-sm-4 input-group">
									<span class="input-group-addon" style="cursor: pointer"><i
										class="fa fa-calendar"></i></span>
									<div class="iconic-input right">
										<input class="form-control" name="monthYear" id="monthDate"
											type="text"> <input type="hidden" id="monthDateINput"
											autocomplete="off" />
									</div>
								</div>
								<div class="col-sm-2">
									<input class="btn btn-sm btn-success" id="ptreport"
										value="Generate" type="button">
								</div>
							</div>
						</form>


						<div class="container container_ hide" style="width: 92%;">
							<section class="error-wrapper"
								style="margin-top: 3%; text-align: left;">

								<form class="form-horizontal" target="foo();"
									action="reports.php" method="post" id="ptForm">
									<div id="visibleIfnodata" class="hide">
										<h6 class="container" style="width: 50%; text-align: center;">
											<strong>No Available Data For pt Reports</strong>
										</h6>
									</div>
									<div class="exportButton container hide"
										style="width: 102%; text-align: right; display: block;">
										<h5 class="container" style="width: 100%; text-align: center;">
											<strong class="HeadingTable"></strong>
											<button class="btn btn-sm btn-defualt pull-right"
												style="margin-left: 1%;" id="export-to-excel">
												<i class="fa fa-file-excel-o"></i> EXCEL
											</button>
											<button class="btn btn-sm btn-defualt pull-right" id="ptPdf">
												<i class="fa fa-file-pdf-o"></i> PDF
											</button>
										</h5>
									</div>
									<input type="hidden" id="ptdate" name="dateOfreports"> <input
										type="hidden" value="pt" name="report">
								</form>

								<div id="tablecontent"></div>
								<div id="printableArea" class="hide">
									<h5 class="container" style="width: 92%; text-align: center;">
										<strong class="HeadingTable"></strong>
									</h5>
									<div id="tablecontent1"></div>
								</div>
							</section>
						</div>
					</div>
				</section>
			</section>

		</section>

		<!--main content end-->
		<!--footer start-->
		<?php include_once (__DIR__."/footer.php");?>
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
	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8"
		src="../js/FixedColumns.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
	<script src="../js/jquery.table2excel.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script>
     $(document).ready(function () {
    	 $('#ptReportForm')[0].reset();
         $('#monthDate').datetimepicker({
		 	    viewMode: 'years',
		 	    format: 'MM YYYY'
	   });
   });

     
     $(document).on('click', "#ptreport", function (e) {
        e.preventDefault();
        var date=$('#monthDate').val();
        if(date !=='' &&  $('#monthDateINput').val()!=date )
        {
        $('#monthDateINput').val(date);
        ptTableFormat(date);
        }
        else
        {
        }
    });
     
     $(document).on('click', "#ptPdf", function (e) {
         $('#ptdate').val($('#monthDate').val());
 	    document.getElementById("ptForm").submit();
 	});

     $(document).on('click', "#export-to-excel", function (e) {
   	  e.preventDefault();
      //export table
       $("#example1").table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: 'ptReportsOF'+$('#monthDate').val(),
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});         
     });

     function ptTableFormat(date){
      $.ajax({
         dataType: 'html',
         type: "POST",
         url: "php/reports.handle.php",
         cache: false,
         data: {act: '<?php echo base64_encode($_SESSION['company_id']."!ptReport");?>',date:date},
         success: function (data) {
        	 jsonobject = JSON.parse(data);
        	 $('#tablecontent,.HeadingTable').empty();
        	 if(jsonobject.tableData.length!==0){
        	  $('.exportButton,#visibleIfnodata,.container_').removeClass('hide show');
              $('#visibleIfnodata').addClass('hide');
              $('.exportButton,.container_').addClass('show');
              var dateNo = date.split(" "); 
        	 var n = ["","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
       	    $('.HeadingTable').html("PT Report For The Month Of "+n[Number(dateNo[0])]+" "+dateNo[1]);
       	  	var html1='';var html2='';var html3='';var html='';
     	  
       	   var html0 = '<div class="reportTable"><table  id="example" ><thead><tr>';
       	   var html1 = '<table id="example1"><thead><tr>';
       	    $.each(jsonobject.tableHeaders, function (k, v) {
     		  html += '<th style="text-align:center;background-color:#41cac0;color:#FFF">' + v + '</th>';
  		   }); 
     	   html += '</tr></thead>';
     	  for (var i = 0, len = jsonobject.tableData.length; i < len; ++i) {
     		 if(i % 2 == 0){
     	    	html += '<tr class="alt">';
     		    }else{
     		    	html += '<tr>';
     		    }
     		   $.each(jsonobject.tableData[i], function (k, v) {
         		   if(k=='gross_salary' || k=='pt' ){
             		   if(i % 2 == 0){
         				 html += '<td  style="text-align:right;background: #E0F9FF;color: #184A47;">' + v + '</td>';
               		    }else{
               		     html += '<td  style="text-align:right;color:#2A827C">' + v + '</td>';
               		    }
                		
             		   }else{
             			  if(i % 2 == 0){
             				 html += '<td style="text-align:left;background: #E0F9FF;color: #184A47;">' + v + '</td>';
                    		    }else{
                    		    	html += '<td style="text-align:left;color:#2A827C">' + v + '</td>';
                    		    }
         		
             		   }
         		 }); 
              	html += "</tr>";
         	}

         	
     	  html2 += '<tr>';
          for (var i = 0, len = jsonobject.tableHeaders.length; i < len; ++i) {
           	 html2 += '<td class="total" style="text-align:right;background-color:#41cac0;color:#FFF"></td>';
   	    }
          html2 += '</tr>';
          html2+='</table>';

          html3 += '<tfoot><tr>';
          for (var i = 0, len = jsonobject.tableHeaders.length; i < len; ++i) {
           	 html3 += '<td class="total" style="text-align:right;"></td>';
   	    }
          html3 += '</tr></tfoot>';
      	 html3 += '</table></div>';
     	 }else{
     	 $('.exportButton,#visibleIfnodata,.container_').removeClass('hide show');
       	 $('.exportButton').addClass('hide');
    	 $('#visibleIfnodata,.container_').addClass('show');
       	 
       	 }
         	

      	 $('#tablecontent').html(html0+html+html3);
      	 $('#tablecontent1').html(html1+html+html2);
      	  calculateColumn(jsonobject.tableHeaders.length,'example');
     	  calculateColumn(jsonobject.tableHeaders.length,'example1');
     	   $('#example td.total').eq(0).html('');
             $('#example td.total').eq(1).html('');
             $('#example td.total').eq(2).html('');
             $('#example td.total').eq(3).html('');
             $('#example td.total').eq(4).html('Total');
             
             $('#example1 td.total').eq(0).html('');
             $('#example1 td.total').eq(1).html('');
             $('#example1 td.total').eq(2).html('');
             $('#example1 td.total').eq(3).html('');
             $('#example1 td.total').eq(4).html('Total');

               var oTable = $('#example').dataTable({
            	  "bInfo":false,
            	  "bPaginate": false,
            	  "bSort": false,
            	 "bFilter": false,
    			 "sScrollY": "400px",
    			 "sScrollX": "100%",
    			 "sScrollXInner": "100%",
    			 "bScrollCollapse": true,
    			 "bPaginate": false,
    			 "bAutoWidth": false	
				 });
              new FixedColumns( oTable,{
              	"iLeftColumns": 2
              	});
               oTable.fnAdjustColumnSizing();
                $(window).bind('resize', function () {
              	    oTable.fnAdjustColumnSizing();
              	  });
             
     }
     
 });
     }
   </script>
</body>
</html>
