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

<title>Annual Salary Statement</title>
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
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
<style>
@import "../css/reportTable.css";

#finYearchosen_chosen, #calYearchosen_chosen {
	width: 100% !important;
}
</style>
</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php include("header.php");  ?>
      <!-- header end -->
		<!-- sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">

					<header class="panel-heading">
						<input type="hidden" id="annualCheck" value="0"> Annual Salary
						statement
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn btn-sm btn-info">
								<i class="fa fa-plus"></i> Generate
							</button>
						</div>
					</header>
					<form action="php/employeeReport.handle.php" method="post"
											id=annualSalaryStmPdf>
					<div class="container pull-right" style="margin-top:10px;">
					<div class="exportButton">
					                     
                                            <button class="btn btn-sm btn-defualt pull-right" id="export-to-excel" type="submit" style="margin-left: 1%;">
                                                <i class="fa fa-file-excel-o"></i> EXCEL
                                            
                                            </button>   
                     
                                            &nbsp;&nbsp;
                                            <input type="hidden"
												value="<?php echo base64_encode($_SESSION['employee_id']."!annualSalarypdf");?>" name="act">
										   
											<input type="hidden" id="yearPdf" name="year">
                                            <button class="btn btn-sm btn-defualt pull-right" id="esiPdf" type="submit">
                                                <i class="fa fa-file-pdf-o"></i> PDF
                                            </button>
                                           
                   </div>
                   </div>
				   </form>
					<form role="form" method="POST" class="form-horizontal"
						id="annaulSalaryForm">
						<div class="panel-body" id="showhideDiv">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">Based On </label>
									<div class="col-lg-5"><label class="control-label">Financial Year</label>
									</div>
								</div>
								<div class="form-group" id="forfinyearDiv">
									<label for="dname" class="col-lg-3 col-sm-3 control-label">Select
										Year</label>
									<div class="col-lg-5">
										<select class="form-control" id="finYearchosen">
                                              <?php
																																														$stmt = mysqli_prepare ( $conn, "SELECT year FROM employee_income_tax WHERE employee_id='" . $_SESSION ['employee_id'] . "'  ORDER BY year DESC" );
																																														$result = mysqli_stmt_execute ( $stmt );
																																														mysqli_stmt_bind_result ( $stmt, $year );
																																														while ( mysqli_stmt_fetch ( $stmt ) ) {
																																															echo "<option value='" . $year . "'>" . $year . "</option>";
																																														}
																																														?>
                                   </select>
									</div>
								</div>
							 <input type="hidden" id="yearFromemploye">
								<div class="col-lg-offset-4 col-lg-5 pull-right">
									<button type="button" class="btn btn-sm btn-success"
										id="generateSalarystmt">Generate</button>
								</div>
							</div>

						</div>
					</form>
					<div class="panel-body" id="contenttable">
						<div class="container" id="table_show" style="width: 92%;">
							<section class="error-wrapper"
								style="margin-top: 3%; text-align: center;">
								<h5 id="HeadingTable"></h5>
							</section>
						</div>
						<div id="tableContent"></div>
					    <div id="excelContent" class="hide"></div>
					
					</div>
					
				</section>

			</section>
		</section>


		<!-- page end-->

		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
	</section>
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8"
		src="../js/FixedColumns.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
	<script src="../js/jquery.table2excel.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script type="text/javascript">
			$(document).ready(function () {
             $('#annaulSalaryForm')[0].reset();
				$('#container').addClass('sidebar-closed');
		    	  $('#main-content').css('margin-left','0px');
		    	  $('#sidebar').css('margin-left','-221px');
		    	  
				$('#finYearchosen').chosen();				
				  var chosenSetval=$('#finYearchosen').val();
				  $('#yearFromemploye').val($("#finYearchosen option:selected").val());
				  $(document).on('click', "#export-to-excel", function (e) {
			    	  e.preventDefault();
			    	  ResultsToTable();
			     });
				   
				  function ResultsToTable(){
					   
				        $("#excelTable").table2excel({
					       
				            exclude: ".excludeThisClass",
				            name: "Results",
				            fileext:".xls",
				            filename: "Annual Salary Statement"
				        });
				    }  
			});
			 
			 $(document).on('click','#showhide', function (e) {
				   e.preventDefault();
				   $('#showhideDiv').toggle('hide');
			 });
			 
			  $(document).on('click','#generateSalarystmt', function (e) {
				   e.preventDefault();
				  $('#yearFromemploye').val($("#finYearchosen option:selected").text());
				  if($('#annualCheck').val()!=$('#yearFromemploye').val()){
				 	annualSalryStmt();
				   }else{
					   $('#showhideDiv').toggle('hide');
					  
					   }
			  });

			 function annualSalryStmt(){
			  $.ajax({
                  dataType: 'html',
                  type: "POST",
                  url: "php/employeeReport.handle.php",
                  cache: false,
                  data:{act: '<?php echo base64_encode($_SESSION['company_id']."!annualSalarySelect");?>',year:$('#yearFromemploye').val()},
               
                  beforeSend:function(){
	                     	$('#generateSalarystmt').button('loading'); 
	                     	$('#contenttable').addClass('loader');
	                        },
	                  success: function (data) {
	                	jsonobject= JSON.parse(data);
	                	 $('#generateSalarystmt').button('reset');
	                	if(jsonobject[2].length>0){
	                	$('#annualCheck').val($('#yearFromemploye').val());
	                	$('#yearBasedPdf').val($("input[name='year']:checked").val());
	                	$('#yearPdf').val($('#yearFromemploye').val());
	                	$('#contenttable').removeClass('loader');
					    $('#tableContent').empty();
					    $('#HeadingTable').empty();
           	    $('#HeadingTable').html("Annual Salary Statement for the Financial Year of "+$('#yearFromemploye').val());
					    $('#showhideDiv').toggle('hide');
				  
               	   var html = '<thead><tr>';

					   $.each(jsonobject[2][1], function (k, v) {
						 if(v!='-'){
                         	 html += '<th style="text-align:center;">' + k + '</th>';
                          }
            		   });
                       var netTotal=0;
	             	   html += '<th style="text-align:center;">Total</th></tr></thead><tbody>';

	             		    for (var i = 0, len = jsonobject[2].length; i < len; ++i) {
		             		    var header="";
		             	if(i % 2 == 0){
	             		    	html += '<tr class="alt">';
		             		    }else{
		             		    	html += '<tr>';
		             		    }
               		   
               		   var total=0;
               		   $.each(jsonobject[2][i], function (k, v) {
                   		  
	                		if(k=='SALARYHEADS'){
	                			if(v.split('_')[1]=='A'){
	                				html += '<td style="text-align:center;font-weight: bold;">ALLOWANCES</td>';
	                				
		                			}else if(v.split('_')[1]=='D'){
	                				html += '<td style="text-align:center;font-weight: bold;">DEDUCTIONS</td>';
		                			}
	                			else if(v.split('_')[1]=='Gross'){
	                			 html += '<td style="font-weight: bold;text-align: right;">'+v.split('_')[1]+'</td>';
	                			// header= '<tr><td colspan="13">Deduction</td></tr>';
	                			}else if(v.split('_')[1]=='Net Salary'){
		                			 html += '<td style="font-weight: bold;text-align: right;">'+v.split('_')[1]+'</td>';
		                    	}else if(v.split('_')[1]=='TotalDedu'){
		                			 html += '<td style="font-weight: bold;text-align: right;">'+v.split('_')[1]+'</td>';
		                    	}else{			                			
		                	 html += '<td style="text-align:left;">'+v.split('_')[1]+'</td>';
		                		}
               			  }
               			  else if(v!='-'){
               			  html += '<td style="text-align:right;">' + reFormateByNumber(v) + '</td>';
               			   total+=Number(v);
               		      }
                          
               	       }); 
                    	  html += "<td style='text-align:right;'>" + reFormateByNumber(total.toFixed(2)) + "</td></tr>"+header;
                    }
	            		
               	html += '</tbody></table>';
               	var excelHtml= '<table id="excelTable" style="width:100%" border=1>'+html;
               	html = '<div class="reportTable"><table id="annualTable1" style="width:100%" >'+html+'</div>';
				$('#tableContent').empty().html(html);
				$('#excelContent').html(excelHtml);
               		var oTable= $('#annualTable1').dataTable({
               			 "bInfo":false,
               			 "bPaginate": false,
                            "bSort": false,
               			 "bFilter": false,
	        				 "sScrollY": true,//height of the table
	        				 "sScrollX": true,//
	        				 "sScrollXInner": "100%",
	        				 "sScrollY": '600px',
	        				 "sScrollX": '120%',
	        				 "bScrollCollapse": true,
	        				 "bPaginate": false,
	        				 "bAutoWidth": true,
	        				 });
               		  new FixedColumns( oTable, {
	        					"iLeftColumns": 1
	        					});
  					
               		  $(window).resize( function () {
               			  oTable.fnAdjustColumnSizing();
               			  });
                     $('#generateSalarystmt').button('reset');

	            }else{
           		 $('#generateSalarystmt').button('reset');
           		 $('#contenttable').removeClass('loader');
           		 $('#showhideDiv').toggle('hide');
           		 $('#HeadingTable').html("No Available Data To Generate Annual Paysilp");
 				 }
		                	
                }
	              });
			  }
	     </script>