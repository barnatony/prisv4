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
<title>ESI Report</title>
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
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
</head>
<style>
@import "../css/reportTable.css";

#control_label_month {
	width: 10%;
}
#branch_name1_chosen {
		width:100% !important;
}
</style>
<body>

	<section id="container" class="">
		<!--header start-->
    <?php  include_once (__DIR__."/header.php"); ?>
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
					<header class="panel-heading"> Esi Report</header>
						<div class="panel-body">
						<form action="php/reports.handle.php" class="form-horizontal"
							role="form" method="post" id="epfReport">
							<div class="col-lg-12 panel-body">
					<!-- <div class = "col-lg-12 row">
					 		<div class="form-group company_select ">
					 	<label class="col-lg-2 control-label" style="text-align: right">
					 			Company
					 		</label>
					 	<div class=" col-lg-10">
					 	<div class="btn-row">
							   		 	  <div class="btn-group" data-toggle="buttons">
							    	<php  
												$stmt = mysqli_prepare ( $conn, "SELECT company_name, company_id , company_prefix ,company_suffix
                      											   FROM companies");
								   				$result = mysqli_stmt_execute ( $stmt );
								   				mysqli_stmt_bind_result ( $stmt, $company_name, $company_id , $company_prefix ,$company_suffix);
												 while ( mysqli_stmt_fetch ( $stmt ) ) {
												 	echo "<label class='btn btn-default'><input name='companyId'  data-prefix='". $company_prefix ."' data-suffix='". $company_suffix ."' value='". $company_id ."' type='radio'>". $company_name ."</label>";
													
												}?>
											</div> 
   									</div>
   							</div>
						</div>
					</div> -->	
					
					<div class="form-group row">
                    			<label class="col-lg-2 control-label" style="text-align: right">Branch</label>
									<div class="col-sm-4 ">
										<input type="text" class=" form-control"
										 id="branch_name" name="branch"  readonly="readonly" required /><select
										 class="form-control branch_name1" id="branch_name1" name="branchName" >
												 <?php
                    																$stmt = mysqli_prepare ( $conn, "SELECT esi_no, GROUP_CONCAT(branch_name) FROM company_branch GROUP BY esi_no;" );
                    																$result = mysqli_stmt_execute ( $stmt );
                    																mysqli_stmt_bind_result ( $stmt, $esi_no, $branch_name );
                    																while ( mysqli_stmt_fetch ( $stmt ) ) {
                    																echo "<option value='" . $esi_no . "'>" . $branch_name . "</option>";
                    															}
													                    ?>   		   
                                              			 		 
                                              						</select>
									</div>
                    														
                   </div>
					
				 	<div class="col-lg-12 row">
						<div class="form-group ">
								<label class="col-lg-2 control-label" style="text-align: right">Month</label>
								<div class="col-sm-4 input-group">
									<span class="input-group-addon" style="cursor: pointer"><i
										class="fa fa-calendar"></i></span>
									<div class="iconic-input right">
										<input class="form-control esiDate" name="monthYear"
											id="monthYear" type="text"><input type="hidden"
											id="monthYearInput" autocomplete="off"> 
									</div>
								</div>
							</div>
					</div>
				    <div class="col-lg-6">
				    	<div class="pull-right">	
							<button type="button" class="btn btn-sm btn-success esiId" value="Generate" id="esiId" style="margin-top:10px 0px;">
								 Generate
						</button></div>
					</div>
				</div>
				<br>
			</form>
			<div class="container container_ hide" style="width: 89%;">

				<section class="error-wrapper"
									style="margin-top:3%; text-align: left;">
					<input type="hidden" name="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!epfReportTxt");?>">
						<input type="hidden" name="date" id="monthDatesub">
                         <form action="php/reports.handle.php" method="post"	id="esireport">
						 	<input type="hidden" value="<?php echo base64_encode($_SESSION['company_id']." !downloadEsiexcel");?>" name="act">
                         	<input type="hidden" class="comp_id" name="companyId">
                         	<input type="hidden" id="EsiExcel"name="monthYear">
                         	<input type="hidden" class="esiNo" name="esiNo">
                         <div>
                             <a href="#" title="Download ESI Excel sheet">
                             <button class="btn btn-sm btn-defualt pull-right" id="export-to-excel" type="submit" style="margin-top: -4px;">
                                <i class="fa fa-file-excel-o"></i> Excel<img src="../img/ajax-loader.gif" style="display: none; position: absolute;margin: 2% 2%;" class="employeemailflag">
                                 </button>
                                 </a>
                          </div>
						</form>
						<form class="form-horizontal" target="foo();"   action="reports.php"
							method="post" id="esiForm">
							<div id="visibleIfnodata" class="hide">
										<h6 class="container" style="width: 50%; text-align: center;">
											<strong>No Data Found</strong>
										</h6>
									</div>
									<input type="hidden" class="esiNo" name="esiNo">
									<input type="hidden" id="esidate" name="dateOfreports">
									<input type="hidden" value="esi" name="report">
									<div class="exportButton">
									
									<div class="container" style="text-align: center;margin-bottom:20px;">
									<strong class="HeadingTable"  ></strong>
									<button class="btn btn-sm btn-defualt pull-right esiPdf" style="margin-top:-3px;margin-right:7px;"  id="esiPdf"
												type="button">
												<i class="fa fa-file-pdf-o"></i> PDF
											</button>
									</div>
									</div>
							</form>
					</section>
								<div id="tablecontent"></div>
			</div>
				<div id="tablecontent1" class="hide"></div>
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
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/jquery.table2excel.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script>
     $(document).ready(function () {
         //$('#branch_name1').chosen();
         $('#branch_name').hide();
    		$('#epfReport')[0].reset();
    		$('#esireport').hide();
    	 $('.esiDate').datetimepicker({
     	    viewMode: 'years',
     	    format: 'MM YYYY',
        });

    	  $(document).on('blur',".esiDate",function(e) {
              e.preventDefault();
              setTimeout(function(){ $('#monthDatesub').val($('#monthDate').val());
              }, 100);
          });

    	 });
	 
	 
     $(document).on('click', ".esiId", function (e) {
         e.preventDefault();
    	 $('#esireport').show();
         var date=$('#monthYear').val();
         var esiNo = $("#branch_name1 option:selected").val();
         $('.esiNo').val(esiNo);
         if((date!='' && $('#monthYearInput').val()!=date)||esiNo !='')
         {
        	 $('#monthYearInput').val(date);
        	 $('#EsiExcel').val(date);
     		
        esiTableFormat(date,esiNo);
         }
         else
         {
         }
     });


     $(document).on('click', ".esiPdf", function (e) {
    	  e.preventDefault()
         $('#esidate').val($('#monthYear').val());
 	    document.getElementById("esiForm").submit();
 	});
               
     
     $(document).on('click', "#export-to-excel", function (e) {
      	  e.preventDefault();
      	  //export table
          $("#example1").table2excel({
   			exclude: ".noExl",
   			name: "Excel Document Name",
   			filename: 'esi_report_'+$('#monthYear').val(),
   			exclude_img: true,
   			exclude_links: true,
   			exclude_inputs: true
   		});         
        });
     
function esiTableFormat(date,esiNo){
     $.ajax({
         dataType: 'html',
         type: "POST",
         url: "php/reports.handle.php",
         cache: false,
         data:{act: '<?php echo base64_encode($_SESSION['company_id']."!esiReport");?>',monthYear:date,esiNo:esiNo},
         beforeSend:function(){
         	$('#esiId').button('loading'); 
           },
           complete:function(){
          	 $('#esiId').button('reset');
           },
         success: function (data) {
        	 jsonobject = JSON.parse(data);
        	 
        	if(jsonobject.length!==0){
        		$('#tablecontent').removeClass('hide');
             var dateFormed=$('#monthYear').val().split(' ');
             var formatDate=GetFormattedDate("01/"+dateFormed[0]+"/"+dateFormed[1]);
             var dateFormed=formatDate.split('/');
             $('#tablecontent').empty();
        	 var excelHeader='';var uiPdfHeader='';var excelBody='';var uiPdfBody='';
        	 $('.exportButton,.container_,#visibleIfnodata').removeClass('hide show');
        	 $('#visibleIfnodata').addClass('hide');
        	 $('.exportButton,.container_').addClass('show');
    	 var n = ["","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
   	    $('.HeadingTable').html("Esi Report For The Month Of "+n[Number(dateFormed[0])]+" "+dateFormed[2])
  excelHeader +='<table id="example1"> <thead><th style="text-align:center;background-color:#41cac0;color:#FFF;">IP Number (10 Digits) </th> <th  style="text-align:center;background-color:#41cac0;color:#FFF;">IP Name( Only alphabets and space ) </th><th  style="text-align:center;background-color:#41cac0;color:#FFF;">No of Days for which wages paid/payable during the month </th>        <th  style="text-align:center;background-color:#41cac0;color:#FFF;">Total Monthly Wages	  </th>      <th style="text-align:center;background-color:#41cac0;color:#FFF;"> Reason Code for Zero workings days </th>              <th  style="text-align:center;background-color:#41cac0;color:#FFF;">Last Working Day( Format DD/MM/YYYY  or DD-MM-YYYY) </th>             </thead>   <tbody>';             

  uiPdfHeader +='<div class="reportTable"><table id="example"> <thead><tr><th >IP Number</th > <th >IP Name</th> <th >Days</th><th >Wages</th><th >Employee Share</th><th >Employer Share</th><th >Reason</th> <th >Exitdate</th></tr></thead> <tbody>';             
for (var i=0;i<jsonobject.length;i++){
	if(i % 2 == 0){
		excelBody += '<tr style="background-color:#E0F9FF;color:#184A47">';
		uiPdfBody += '<tr style="background-color:#E0F9FF;color:#184A47">';
	    	
		    }else{
		    	excelBody += '<tr style="color:#2A827C">';
		    	uiPdfBody += '<tr style="color:#2A827C">';
		    	
		    }
     $.each(jsonobject[i], function( k, v ) {
		 if(k=='employee_emp_esi_no' || k=='employee_name'){
			 excelBody += '<td style="text-align:left;">' + v + '</td>';
				 uiPdfBody += '<td style="text-align:left;">' + v + '</td>';
		  }else{
			  if(k!='employee_share' && k!='employer_share'){ 
				  if(k=='no_of_days' && v==0)
					  excelBody  += '<td style="text-align:right;">'+Math.round(v)+'</td>';
				 else if(k=='last_working_day' || k=='reason_code' && v==0)
					  excelBody  += '<td style="text-align:right;">'+ v +'</td>';
				 else
					 excelBody  += '<td style="text-align:right;">'+Math.round(v)+'</td>';
			  }
			  uiPdfBody += '<td style="text-align:right;">'+Math.round(v)+'</td>';
			}
		});
		
	     excelBody += "</tr>";
		 uiPdfBody += "</tr>";
}

	excelBody+='</tbody></table>';
	
	uiPdfBody+='</tbody></table></div>';
	//console.log(excelHeader+excelBody);
$('#tablecontent').html(uiPdfHeader+uiPdfBody);
$('#tablecontent1').html(excelHeader+excelBody);


var oTable = $('#example').dataTable({
	"bInfo":false,
	 "bPaginate": false,
    "bSort": false,
	 "bFilter": false,
	 "sScrollY": "400px",
	 "sScrollX": "105%",
	 "sScrollXInner": "100%",
	 "bScrollCollapse": true,
	 "bPaginate": false,
	 "bAutoWidth": false		 
	 });
	 
new FixedColumns( oTable, {
	"iLeftColumns": 2
	} );
 oTable.fnAdjustColumnSizing();
  $(window).bind('resize', function () {
	    oTable.fnAdjustColumnSizing();
	  } );
        	}else{
        		$('.exportButton,#visibleIfnodata,.container').removeClass('hide show');
              	 $('.exportButton').addClass('hide');
           	 $('#visibleIfnodata,.container_').addClass('show');
           	$('#tablecontent').addClass('hide');
           	 
           	 }
         	
             }
     });
     }
 	
   </script>
</body>
</html>