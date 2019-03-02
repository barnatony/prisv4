<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">
<title>EPF Report</title>
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
<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
</head>
<style>
.table tbody>tr>td {
	padding: 4px;
}

h5 {
	font-size: 12px;
	margin-top: 4px;
	margin-bottom: 0px;
}

ul, ol {
	margin-top: 0;
	margin-bottom: 0px;
}

.imag {
	width: 30%;
	margin-top: 12px;
	margin-left: 16%;
}

@media only screen and (min-width: 320px) and (max-width:640px ) {
	.imag {
		width: 9%;
		margin-top: 12px;
		margin-left: 42%;
	}
}

@media only screen and (min-width: 640px) and (max-width:1280px ) {
	.imag {
		width: 51%;
		margin-top: 41px;
		margin-left: 23%;
	}
}

.spanCenter {
	text-align: center;
}

.alignRight {
	text-align: right;
}

.table-striped>tbody>tr:nth-child(2n+1)>td, .table-striped>tbody>tr:nth-child(2n+1)>th
	{
	background-color: #FFF;
}

.table-hover>tbody>tr:hover>td, .table-hover>tbody>tr:hover>th {
	background-color: #FFF;
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
					<header class="panel-heading"> Epf Reports 
					</header>
					<div class="panel-body">
						<div id="tableContent" class="hide"></div>
						<form action="php/reportsV2.handle.php" class="form-horizontal"
							role="form" method="post" id="epfReport">
							<div class="col-lg-12 panel-body">
					<div class="form-group row">
                    			<label class="col-lg-2 control-label" style="text-align: right">Branch</label>
									<div class="col-sm-4 ">
										<input type="text" class=" form-control"
										 id="branch_name" name="branch"  readonly="readonly" required /><select
										 class="form-control branch_name1" id="branch_name1" name="branchName" >
												 <?php
                    																$stmt = mysqli_prepare ( $conn, "SELECT pf_no, GROUP_CONCAT(branch_name) FROM company_branch GROUP BY pf_no;" );
                    																$result = mysqli_stmt_execute ( $stmt );
                    																mysqli_stmt_bind_result ( $stmt, $pf_no, $branch_name );
                    															while ( mysqli_stmt_fetch ( $stmt ) ) {
                    																echo "<option value='" . $pf_no . "'>" . $branch_name . "</option>";
                    															}
													                    ?>   
													 		    
                                              						</select>
									</div>
                    														
                   </div>
				 <div class="col-lg-12 row">
						<div class="form-group company_select ">
								<label class="col-lg-2 control-label" style="text-align: right">Month</label>
								<div class="col-lg-4 input-group">
									<span class="input-group-addon" style="cursor: pointer"><i
										class="fa fa-calendar"></i></span>
									<div class="iconic-input right">
										<input class="form-control epfDate" name="monthYear"
											id="monthDate" type="text"> <input type="hidden"
											id="monthDateInput" autocomplete="off">
									</div>
								</div>
							</div>
					</div>
				    <div class="col-lg-6">	
				    <div class="pull-right">
							<button type="button" class="btn btn-sm btn-success" value="Generate" id="EPFChellan" style="margin-top:10px;">
								 Generate
						</button>
						</div>
					</div>
							
				</div>
							<input type="hidden" name="act"
								value="<?php echo base64_encode($_SESSION['company_id']."!epfReportTxt");?>">
							<input type="hidden" name="epfNo" class="epfNo">
							<input type="hidden" name="date" id="monthDatesub">
							<div class="container container_ hide" style="width: 92%;">
								<section class="error-wrapper"
									style="margin-top: 3%; text-align: left;">
									<div id="visibleIfnodata" class="hide">
										<h6 class="container" style="width: 50%; text-align: center;">
											<strong>No Available Challan For EPF Reports</strong>
										</h6>
									</div>
									<div class="exportButton">
										<h5 class="container" style="width: 92%; text-align: center;">
											<strong class="HeadingTable"></strong>
											<button class="btn btn-sm btn-default pull-right"
												style="margin-left: 1%" onclick="epfReport()" type="button">
												<i class="fa fa-file-text-o"></i> TEXT
											</button>
											<button class="btn btn-sm btn-default pull-right"
												id="export-to-excel" type="button">
												<i class="fa fa-file-text-o"></i> EXCEL
											</button>
										</h5>
										<div id="UAN_alert" style="color: #E44826;word-wrap:break-word;"> </div>
										
										<div style="box-shadow: 1px 0px 2px 2px rgb(136, 136, 136);padding: 10px;margin-top: 10px;">
											<div id="tablecontent"></div>
										</div>
									</div>
								</section>
							</div>
						</form>
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
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>

	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/jquery.table2excel.js"></script>

	<script>
     $(document).ready(function () {
    	//$('#branch_name1').chosen();
         $('#branch_name').hide();
    	 $('#epfReport')[0].reset();
    	 $('.epfDate').datetimepicker({
      	    viewMode: 'years',
      	    format: 'MM YYYY'
          });
    	   });
 	   
      $(document).on('blur',".epfDate",function(e) {
          e.preventDefault();
          setTimeout(function(){  $('#monthDatesub').val($('#monthDate').val());
          }, 100);
      });

      

     $(document).on('click', "#EPFChellan", function (e) {
         var date=$('#monthDate').val();
         var epfNo = $('#branch_name1 option:selected').val();
         $('.epfNo').val(epfNo);
         if((date!='' && $('#monthDateInput').val()!=date)||epfNo!=''){
        	 $('#monthDateInput').val(date);
        	 epfChellam(epfNo);
         }
         
        });

       function epfReport() {
    	    document.getElementById("epfReport").submit();
    	}

  

       //Export To excel Base
       $(document).on('click', "#export-to-excel", function (e) {
           	  e.preventDefault();
           	 
       	   $.ajax({
               dataType: 'html',
               type: "POST",
               url: "php/reportsV2.handle.php",
               cache: false,
               data:{act: '<?php echo base64_encode($_SESSION['company_id']."!epfReportExcel");?>', date:$('#monthDate').val() , epfNo:$('.epfNo').val()},
               beforeSend:function(){
               	$('#export-to-excel').button('loading'); 
                 },
                 complete:function(){
                $('#export-to-excel').button('reset');
                 },
               success: function (data) {
              	 jsonobject = JSON.parse(data);
              	
              	 var html = NoUAN = '';
             	//$('#tableContent').empty();
              	html +='<table id="example1"><thead>';
              	$.each(jsonobject.tableHeaders, function( k, v ) {
                  	html +='<th>'+v+'</th>';
                  	});
              	html+='</thead><tbody>';
              	for (var i=0;i<jsonobject.tableData.length;i++){
              		html +='<tr>';
              		$.each(jsonobject.tableData[i], function( k, v ) {
              		if(k=='UAN'){
						if(v.split('_')[0]=="")
							NoUAN += v.split('_')[1]+',';
						
						html +='<td>'+v.split('_')[0]+'</td>';
                  	}else
              			html +='<td>'+v+'</td>';
                  	});
              		html +='</tr>';
              	}
            	html+='</tr></tbody></table>';
				
            	NoUAN = NoUAN.slice(0,-1);
            	if(NoUAN.length>0){
            		$('#UAN_alert').empty();
            		$('#UAN_alert').append("The following Employees("+NoUAN+")doesn't have UAN No.");
            	}
              	$('#tableContent').html(html);
               }

               });
       	setTimeout(function(){  $("#example1").table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: 'EPFReportsOF'+$('#monthDate').val(),
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});  }, 100);
    	      //export table
    	     
    	     });

     function epfChellam(epfNo){
       $.ajax({
           dataType: 'html',
           type: "POST",
           url: "php/reportsV2.handle.php",
           cache: false,
           data: { act: '<?php echo base64_encode($_SESSION['company_id']."!epfReportChellan");?>', monthYear:$('#monthDate').val(),epfNo:epfNo},
           beforeSend:function(){
           	$('#EPFChellan').button('loading'); 
             },
             complete:function(){
            	 $('#EPFChellan').button('reset');
             },
           success: function (data) {
           	 jsonobject = JSON.parse(data);
             	var dateFormed=$('#monthDate').val().split(' ');
                var formatDate=GetFormattedDate("01/"+dateFormed[0]+"/"+dateFormed[1]);
                var dateFormed=formatDate.split('/');
                            
            if(jsonobject[0].EPF){      
                var html='';
               	 $('.exportButton,.container_,#visibleIfnodata').removeClass('hide show');
               	 $('#visibleIfnodata').addClass('hide');
               	 $('.exportButton,.container_').addClass('show');
           	 var n = ["","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
           	
           	    $('.HeadingTable').html("EPF Challan The Month Of "+n[Number(dateFormed[0])]+" "+dateFormed[2]);
          	    var adminTotal=Number(Math.round(Math.max(jsonobject[0].EPF*0.005,500))+Math.round(Math.max(jsonobject[0].EPF*0.00)));
          	    var empS=Number(Math.round(jsonobject[0].ER_Remitted)+Math.round(jsonobject[0].EPS_Remitted)+Math.round(jsonobject[0].EDLI*0.005));
          	    var str =inWords(Number(empS+adminTotal+Math.round(jsonobject[0].EE_Remitted)));
          	    html +="<div class='row invoice-list'>  <div class='col-lg-3 col-sm-3'></div><div class='col-lg-5 col-sm-5'><ul class='unstyled'></ul></div></div><div class='row invoice-list'><div class='col-lg-12 col-sm-12'></ul></div></div><div class='row invoice-list'> <div class='col-lg-12 col-sm-12'></ul></div></div><div class='row invoice-list'>  <div class='col-lg-12 col-sm-12'><ul class='unstyled'></div></div><div class='row invoice-list'><div class='col-lg-3 col-sm-3'><ul class='unstyled'><!--li ---><!--/li--></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'><li><strong> EPF</strong></li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'><li><strong> EPS</strong></li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'><li><strong>EDLI</strong></li></ul></div></div><div class='row invoice-list'>  <div class='col-lg-3 col-sm-3'><ul class='unstyled'><li>TOTAL SUBSCRIBERS :</li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'> <li><strong> "+jsonobject[0].emp_count+"</strong></li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'> <li><strong> "+jsonobject[0].emp_count+"</strong></li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'> <li><strong> "+jsonobject[0].emp_count+"</strong></li></ul></div></div> <div class='row invoice-list'>  <div class='col-lg-3 col-sm-3'><ul class='unstyled'> <li>TOTAL WAGES :</li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'> <li><strong> "+Math.round(jsonobject[0].EPF)+"</strong></li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'> <li><strong> "+Math.round(jsonobject[0].EPS)+"</strong></li></ul></div><div class='col-lg-2 col-sm-2'><ul class='unstyled'> <li><strong> "+Math.round(jsonobject[0].EDLI)+"</strong></li></ul></div></div><table class='table table-striped table-hover' CELLPADDING='0' cellspacing='0'> <thead><tr><th>SL</th><th>PARTICULARS</th><th class='hidden-phone'>A/C.01</th><th>A/C.02</th><th>A/C.10</th><th>A/C.21</th><th>A/C.22</th><th>TOTAL</th></tr></thead><tbody><tr><td>1</td><td>ADMIN CHARGES</td><td></td><td>"+Math.round(Math.max(jsonobject[0].EPF*0.005,500))+"</td><td></td><td></td><td>"+Math.round(Math.max(jsonobject[0].EPF*0.000))+"</td><td>"+adminTotal+"</td></tr><tr><td >2</td><td>EMPLOYER'S SHARE OF CONT.</td><td>"+Math.round(jsonobject[0].ER_Remitted)+"</td><td></td><td>"+Math.round(jsonobject[0].EPS_Remitted)+"</td><td>"+Math.round(jsonobject[0].EDLI*0.005)+"</td><td></td><td>"+empS+"</td></tr><tr><td>3</td><td>EMPLOYEE'S SHARE OF CONT.</td><td>"+Math.round(jsonobject[0].EE_Remitted)+"</td><td></td><td></td><td></td><td></td><td>"+Math.round(jsonobject[0].EE_Remitted)+"</td></tr><tr><td colspan='7'>GRAND TOTAL (IN WORDS) : "+str[0].toUpperCase() + str.slice(1)+" </td><td>"+Number(empS+adminTotal+Math.round(jsonobject[0].EE_Remitted))+"</td></tr><tr></div></div>";
               $('#tablecontent').html(html);
              }else{
        	 $('.exportButton,#visibleIfnodata,.container').removeClass('hide show');
          	 $('.exportButton').addClass('hide');
       	 $('#visibleIfnodata,.container_').addClass('show');
            }
           }
       }); 
       }
</script>
</body>
</html>