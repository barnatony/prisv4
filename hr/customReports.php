
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

<title>Reports</title>
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
<link rel="stylesheet" type="text/css" href="../css/datepicker.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style type="text/css" title="currentStyle">
@import "../css/demo_table.css";

#e_attendance_chosen, #f_attendance_chosen, #b_attendance_chosen,
	#d_attendance_chosen {
	width: 100% !important;
}

.dataTables_scrollBody::-webkit-scrollbar {
	width: 5px;
}

.dataTables_scrollBody::-webkit-scrollbar-track {
	-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
	border-radius: 10px;
}

.dataTables_scrollBody::-webkit-scrollbar-thumb {
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
}
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
     <?php
					
include ("header.php");
					$allowance = array ();
					$allowanceId = array ();
					$deduc = array ();
					$deducId = array ();
					$Miscdeduc = array ();
					$miscdeducId = array ();
					$Miscallow = array ();
					$MiscallowIds = array ();
					$result = mysqli_query ( $conn, "SELECT type,alias_name,pay_structure_id FROM  company_pay_structure WHERE  display_flag = 1 ORDER BY sort_order" );
					while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
						if ($row ['type'] == 'A') {
							$allowance [] = $row ['alias_name'];
							$allowanceId [] = $row ['pay_structure_id'];
						} elseif ($row ['type'] == 'D') {
							$deduc [] = $row ['alias_name'];
							$deducId [] = $row ['pay_structure_id'];
						} elseif ($row ['type'] == 'MD') {
							$Miscdeduc [] = $row ['alias_name'];
							$miscdeducId [] = $row ['pay_structure_id'];
						} elseif ($row ['type'] == 'MP') {
							$Miscallow [] = $row ['alias_name'];
							$MiscallowIds [] = $row ['pay_structure_id'];
						}
					}
					
					// and pdf data set
					$_SESSION ['allowancesIds'] = base64_encode ( implode ( ',', array_merge ( $allowanceId, $MiscallowIds ) ) );
					$_SESSION ['deducIds'] = base64_encode ( implode ( ',', array_merge ( $deducId, $miscdeducId ) ) );
					$_SESSION ['allowanceslabel'] = array_merge ( $allowance, $Miscallow );
					$_SESSION ['dedulabel'] = array_merge ( $deduc, $Miscdeduc );
					
					// excel Arguments for table create
					$headerNameExcel = implode ( ',', array_merge ( $allowance, $Miscallow ) ) . ",Gross," . implode ( ',', array_merge ( $deduc, $Miscdeduc ) );
					$columrNameExcel = implode ( ',', array_merge ( $allowanceId, $MiscallowIds ) ) . ",gross_salary," . implode ( ',', array_merge ( $deducId, $miscdeducId ) );
					
					// for datatable reportes
					$miscAllowVal = "SUM(" . implode ( '+', $MiscallowIds ) . "),gross_salary";
					array_push ( $allowanceId, $miscAllowVal );
					$miscDeduVal = "SUM(" . implode ( '+', $miscdeducId ) . ")";
					array_push ( $deducId, $miscDeduVal );
					array_push ( $allowance, "OtherAllow", "Gross" );
					array_push ( $deduc, "OtherDedu" );
					
					$header = implode ( ',', array_merge ( $allowance, $deduc ) );
					$allDeducids = implode ( ',', array_merge ( $allowanceId, $deducId ) );
					?>
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
				<section class="panel" id="section_hide">
					<header class="panel-heading"> Salary statement </header>
					<div class="panel-body">
						<form class="form-horizontal" role="form" method="post">
							<div class="col-lg-7">
								<label class="col-lg-4 control-label">Month</label>
								<div class="col-lg-5 input-group">
									<span class="input-group-addon" style="cursor: pointer"><i
										class="fa fa-calendar"></i></span>
									<div class="iconic-input right">
										<input class="form-control" name="attendance_month"
											id="datepicker" required="" readonly="true" type="text">
									</div>
								</div>
								<i class="fa fa-search-plus letter" id="advance_search"
									style="cursor: pointer; margin-top: 8px; font-size: 1.3em;"></i>
								</li>
								<div class="col-lg-9" style="text-align: right;" id="go_hide">
									<br> <input class="btn btn-success" id="go" value="Generate"
										type="button">
								</div>
							</div>
							<div class="pull-right exportButton">
								<button class="btn btn-danger" id="export-to-excel">
									<i class="fa fa-file-excel-o"></i> Excel
								</button>
								&nbsp;&nbsp; <a class="button-next  btn btn-success"
									target=foo(); href="salaryStmtreports.php"> <i
									class="fa fa-file-pdf-o"></i> PDF
								</a>
							</div>

						</form>
					</div>
					<div class="hide" id="excelTablecontent"></div>
					<div class="panel-body" id="period_hide">
						<form class="form-horizontal" role="form" method="post" id="">
							<div class=" row col-lg-12" style="margin-left: -5px;">
								<label class="col-lg-2 control-label"> Attendance View</label>
								<div class="col-lg-12">
									<label for="Department" class="col-lg-2 control-label"> <input
										name="reports_for" id="Department" type="radio" value="F"
										checked> Department
									</label> <label for="Designation"
										class="col-lg-2 control-label"> <input name="reports_for"
										id="Designation" type="radio" value="D"> Designation
									</label> <label for="Branch" class="col-lg-2 control-label"> <input
										name="reports_for" id="Branch" type="radio" value="B"> Branch
									</label>
								</div>
							</div>
							<!--Designation-->
							<div class="col-lg-12" id="designation_details"
								style="margin-left: 12px; display: none">
								<br>
								<div class="form-group">
									<label class="col-lg-2 control-label" for="pf_limit">Designation
										Name</label>
									<div class="col-lg-7">
										<select class="form-control" id="d_attendance"
											name="d_attendance[]" multiple>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, " SELECT designation_name, designation_id FROM company_designations" );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $designation_name, $designation_id );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $designation_id . "'>" . $designation_name . " [ " . $designation_id . " ] <br>" . "</option>";
																																										}
																																										?>
                                             </select>
									</div>
								</div>
							</div>

							<!--Branch-->
							<div class="col-lg-12" id="branch_details"
								style="margin-left: 12px; display: none">
								<br>
								<div class="form-group">
									<label class="col-lg-2 control-label" for="pf_limit">Branch
										Name</label>
									<div class="col-lg-7">
										<select class="form-control" id="b_attendance"
											name="b_attendance[]" multiple>
                                             <?php
																																													$stmt = mysqli_prepare ( $conn, "SELECT branch_name, branch_id FROM company_branch " );
																																													$result = mysqli_stmt_execute ( $stmt );
																																													mysqli_stmt_bind_result ( $stmt, $branch_name, $branch_id );
																																													while ( mysqli_stmt_fetch ( $stmt ) ) {
																																														echo "<option value='" . $branch_id . "'>" . $branch_name . " [ " . $branch_id . " ] <br>" . "</option>";
																																													}
																																													?>
                                             </select>
									</div>

								</div>
							</div>


							<!--depart-->
							<div class="col-lg-12" id="department_details"
								style="margin-left: 12px; display: none">
								<br>
								<div class="form-group">
									<label class="col-lg-2 control-label" for="pf_limit">Department
										Name</label>
									<div class="col-lg-7">
										<select class="form-control" id="f_attendance"
											name="f_attendance[]" multiple>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, " SELECT department_name, department_id FROM company_departments " );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $department_name, $department_id );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $department_id . "'>" . $department_name . " [ " . $department_id . " ] <br>" . "</option>";
																																										}
																																										?>
                                             </select>
									</div>

								</div>

							</div>

							<div class="col-lg-offset-2">
								<div class="form-group">
									<input type="button" class="btn btn-success btn-sm select"
										value="Select All" style="margin-left: 44px;"> <input
										type="button" class="btn btn-danger btn-sm deselect"
										value="Deselect all"> <input type="button"
										class="btn btn-success  btn-sm" id="submit_value"
										value="Submit"> <input type="submit"
										class="btn btn-danger  btn-sm" id="cancel" value="Cancel">
								</div>
							</div>
						</form>

					</div>

					<!-- page start-->

					<div class="container" style="width: 92%; margin-top: 3%;">
						<section class="error-wrapper"
							style="margin-top: 2%; text-align: left;">
							<div class="loader" style="width: 88%; height: 50%;"></div>
							<div class="data_feed"></div>

						</section>
						<br>
						<br>
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
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/bootstrap-datepicker.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8"
		src="../js/FixedColumns.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
	<script src="../js/jquery.table2excel.js"></script>

	<script type="text/javascript">
			$(document).ready(function () {        	  
		        	   $("#period_hide").toggle('hide');
		        	   $(".loader,#designation_details,#branch_details,.container,.exportButton").hide();
		        	   $('#department_details').show();
		     });
		      
	          //datepicker Inistalization
		           $("#datepicker").datepicker({
		               format: " mm yyyy",
		               viewMode: "months",
		               minViewMode: "months"
		           }).on('changeDate', function(e){
		       	    $(this).datepicker('hide');
		       	       });


			      $('#go').on('click', function (e) {
				       e.preventDefault();
		        	   var headerName='<?php echo $header;?>';
		               var columName='<?php echo $allDeducids;?>';
		               var date = $('#datepicker').val();
	                   reportGenerate(headerName,columName,date,""); // Function Call For reportGenetare BasedOn Date selection
	                });

	                
		           $('#e_attendance,#d_attendance,#f_attendance,#b_attendance').chosen();// for Chocen Inisialization
		           $(document).on('click', "#advance_search", function () {
		        	   var element =  $("#period_hide").toggle();
		        	    $(element).css('display')=='none'?$('#go_hide').show():$('#go_hide').hide();
		           });//Advance search Click

		           $(document).on('change', "input[name='reports_for']", function (e) {
		            	  $("#designation_details,#branch_details,#department_details").hide();
		            	  var selectedOpt = $(this).val()=='D'?'designation_details':$(this).val()=='F'?'department_details':'branch_details';
		            	  $('.deselect').click();
		            	  $('#'+selectedOpt).show();
		              });// Get Value For search based on (D,F,B)

		           $(document).on('click', ".select", function () {
		            	  var val = $("input:radio[name='reports_for']:checked").val();
		            	  var selectAllDataId = val =='E'?'e_attendance option':val =='D'?'d_attendance option':val =='F'?'f_attendance option':'b_attendance option';
		            	  $('#'+selectAllDataId.split(" ")[0]).trigger('chosen:updated');
		                  $('#'+selectAllDataId).prop("selected", true);
		                  $('#'+selectAllDataId.split(" ")[0]).trigger('chosen:updated');
		             });// Select All chosen Value based On click of (D,F,B)

		              $(document).on('click', ".deselect", function () {
						  var val = $("input:radio[name='reports_for']:checked").val();
		            	  var selectAllDataId = val =='E'?'e_attendance option':val =='D'?'d_attendance option':val =='F'?'f_attendance option':'b_attendance option';
		            	  $('#'+selectAllDataId.split(" ")[0]).trigger('chosen:updated');
		                  $('#'+selectAllDataId).removeAttr("selected");
		                  $('#'+selectAllDataId.split(" ")[0]).trigger('chosen:updated');
		              });// DeSelect All chosen Value based On click of (D,F,B)


		              
		              $(document).on('click', "#submit_value", function (e) {
		                  e.preventDefault();
		                  var val = $("input:radio[name='reports_for']:checked").val();
		            	  var selectedId = val =='E'?'e_attendance':val =='D'?'d_attendance':val =='F'?'f_attendance':'b_attendance';
		            	  var date = $('#datepicker').val();
		            	   var headerName='<?php echo $header;?>';
			               var columName='<?php echo $allDeducids;?>';
			               
		            	  reportGenerate(headerName,columName,date,val,$("#"+selectedId).val(),"advanceReportSearch"); // Function Call For reportGenetare BasedOn Date selection
			              $("#advance_search").click();   
		            	  $('.deselect').click();         	  
		              	});// Function Call For reportGenetare BasedOn (D,F,B)

					//cancel Event 
					 $(document).on('click', "#cancel", function (e) {
					                  e.preventDefault();
					                  $("#period_hide").hide();
					                //  $("#advance_search").click();  
									 });
					  
	                  //Function For Report Generate  This Create A table
		              function reportGenerate(headerName,columName,date,val,affectedIds,key){
			              var flag=0;
		            	  $.ajax({
			                   dataType: 'html',
			                   type: "POST",
			                   url: "php/reports.handle.php",
			                   cache: false,
			                   data:{search:'reports_filter',headerName:headerName,columName:columName,date:date,reportFor:val,affectedIds:affectedIds,key:key},
			                   success: function (data) {
			                	  jsonobject= JSON.parse(data);
			                	  $('.data_feed').empty();
			                	  $('.container').show();
			                	  $('#container').addClass('sidebar-closed');
			                	  $('#main-content').css('margin-left','0px');
			                	  $('#sidebar').css('margin-left','-210px');
			                       var html = '<table cellpadding="0" cellspacing="0"  id="example" style="width:100%;border: 1px solid #DDD;"  ><thead><tr>';
                                   var copunt_tr=0;
			                	   $.each(jsonobject[2].tableHeader, function (k, v) {
			                		   copunt_tr++;
			             			  html += '<th style="text-align:center;padding: 6px;">' + v + '</th>';
			             		   }); 
			                	   html += '</tr></thead><tfoot><tr>';
			                	   $.each(jsonobject[2].tableHeader, function (k, v) {
				             			  html += '<th style="text-align:right;"></th>';
				             		   });
			             		   html += '</th></tr><tbody>';
			                	   if(jsonobject[2].tableValues){
			                	   for (var i = 0, len = jsonobject[2].tableValues.length; i < len; ++i) {
			                		   html += '<tr>';
			                		   $.each(jsonobject[2].tableValues[i], function (k, v) {
				                		if(k=='employee_id' || k=='employee_name'){
			                			  html += '<td style="padding-left: 2%;">' + v + '</td>';}else{
			                			  html += '<td style="text-align:right;line-height: 1.42857;padding:4px;line-height: 1.42857; vertical-align: top;">' + v + '</td>';
			                      			  }
			                	       }); 
			                     	  html += "</tr>";
			                     	 flag=1;
			                		}
			                		 $('.exportButton').show();
			                		}else{
			                			$('.exportButton').hide();
			                			 html += '<tr><td colspan='+jsonobject[2].tableHeader.length+' style="text-align: left;">No Data Are Found</td></tr>';
			                       }
			                		html += '</tbody></table>';
			                		$(html).appendTo('.data_feed');
                             		if(flag==1)
                             		{  
			                		var oTable = $('#example').dataTable({
			                			 "bInfo":false,
			                			 "bPaginate": false,
			                			 "bFilter": false,
				        				 "sScrollY": "400px",
				        				 "sScrollX": "100%",
				        				 "sScrollXInner": "250%",
				        				 "bScrollCollapse": true,
				        				 "bPaginate": false,
				        				 "bAutoWidth": false,
				        				 "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
									      for(var k=2;k<=(copunt_tr-1);){
				        			        	   var TotalMarks = 0;
				        			            for ( var i=iStart ; i<iEnd ; i++ ) {
					        			             TotalMarks += parseFloat(aaData[aiDisplay[i]][k]);
				        			            }
				        			           var nCells = nRow.getElementsByTagName('th');
				        			           // '<i class="fa fa-rupee"></i> '+
				        			           nCells[k-2].innerHTML=TotalMarks.toFixed(2);

					        				  k++;
				        			         }
}
								      
				        	      } );
                             		}
			                		  new FixedColumns( oTable, {
			        					"iLeftColumns": 2
			        					} );
			        				 oTable.fnAdjustColumnSizing();
			        				  $(window).bind('resize', function () {
			        					    oTable.fnAdjustColumnSizing();
			        					  } );
			                		
			        				}	                     
			               });
			              }
		              
		              $(document).on('click', "#export-to-excel", function (e) {
		            	  e.preventDefault();
			             var headerName='<?php echo $headerNameExcel;?>';
			              var columName='<?php echo $columrNameExcel;?>';
			              var date = $('#datepicker').val();
		            	 $.ajax({
			                   dataType: 'html',
			                   type: "POST",
			                   url: "php/reports.handle.php",
			                   cache: false,
			                   data:{search:'reports_filter',headerName:headerName,columName:columName,date:date},
			                   success: function (data) {
			                   jsonobject= JSON.parse(data);
			                	  var html = '<table  id="excelTable"><thead><tr><th style="text-align:center;background-color: rgb(120, 210, 252);" colspan='+jsonobject[2].tableHeader.length+'>Salary Statment For '+date+'</th><tr class="totalColumn">';
			                	   $.each(jsonobject[2].tableHeader, function (k, v) {
			                		  html += '<th style="text-align:center;background-color: rgb(120, 210, 252);">' + v + '</th>';
			             		   }); 
			                	   html += '</tr><tbody>';
			                	   
			                	  
			                	   if(jsonobject[2].tableValues){
			                	   for (var i = 0, len = jsonobject[2].tableValues.length; i < len; ++i) {
			                		   html += '<tr>';
			                		   $.each(jsonobject[2].tableValues[i], function (k, v) {
				                		if(k=='employee_id' || k=='employee_name'){
			                			  html += '<td>' + v + '</td>';
			                			  }else{
			                			  html += '<td  style="text-align:right;">' + v + '</td>';
			                      			  }
			                	       }); 
			                     	  html += "</tr>";
			                     	}
			                 	   
			                     } 
				                   html += '<tr>';
					               for (var i = 0, len = jsonobject[2].tableHeader.length; i < len; ++i) {
					                	 html += '<td class="total" style="text-align:right"></td>';
			                	    }
					               html += '</tr><tr rowspan="4" class="company_name" ><th  colspan="2" style="text-align:right"><img src="../compDat/1331/1331.jpg" alt=""></th><th  colspan='+(jsonobject[2].tableHeader.length-2)+' style="text-align:right">BASSBIZ,<br>NO:40,Teachers Colony,<br>Ayar,Chennai-600091</th></tr></tbody></table>';
			                        //append table 
					                $('#excelTablecontent').html(html);
//Total assign
					                calculateColumn(jsonobject[2].tableHeader.length);
					                $('#excelTable td.total').eq(0).html('-');
					                $('#excelTable td.total').eq(1).html('Total');
					              
			                        
			           //export table
		                $("#excelTable").table2excel({
							exclude: ".noExl",
							name: "Excel Document Name",
							filename: date,
							exclude_img: true,
							exclude_links: true,
							exclude_inputs: true
						});
			                   }
		                   });
		              });

		              function calculateColumn(index)
		              {
			               
			               for(var i=0;i<index;i++){
			            	   var total = 0;
			               $('#excelTable tr').each(function()
		                  { 
				              var value = parseInt($('td', this).eq(i).html());
				               if (!isNaN(value))
		                      {
		                          total += value;
		                      }
		                  });
		                $('#excelTable td.total').eq(i).html(total.toFixed(2));
		               }
		              }
		     </script>