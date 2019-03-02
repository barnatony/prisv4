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
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style type="text/css" title="currentStyle">
@import "../css/reportTable.css";

#e_attendance_chosen, #f_attendance_chosen, #b_attendance_chosen,
	#d_attendance_chosen {
	width: 100% !important;
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
					
include_once (__DIR__ . "/header.php");
					$allowance = "";
					$allowanceId = "";
					$deduc = "";
					$deducId = "";
					$Miscdeduc = "";
					$miscdeducId = "";
					$Miscallow = "";
					$MiscallowIds = "";
					
					Session::newInstance ()->_setGeneralPayParams ();
					$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
					
					foreach ( $allowDeducArray ['A'] as $allow ) {
						$allowance .= $allow ['alias_name'] . ",";
						$allowanceId .= $allow ['pay_structure_id'] . ",";
					}
					
					foreach ( $allowDeducArray ['D'] as $dedu ) {
						$deduc .= $dedu ['alias_name'] . ",";
						$deducId .= $dedu ['pay_structure_id'] . ",";
					}
					
					// miscAllowances and miscDeduction
					Session::newInstance ()->_setMiscPayParams ();
					$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
					
					foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
						$Miscallow .= $miscAllow ['alias_name'] . ",";
						$MiscallowIds .= $miscAllow ['pay_structure_id'] . ",";
					}
					
					foreach ( $miscallowDeducArray ['MD'] as $miscDedu ) {
						$Miscdeduc .= $miscDedu ['alias_name'] . ",";
						$miscdeducId .= $miscDedu ['pay_structure_id'] . ",";
					}
					
					// and pdf data set
					$_SESSION ['allowancesIds'] = base64_encode ( $allowanceId . $MiscallowIds );
					$_SESSION ['deducIds'] = base64_encode ( $deducId . $miscdeducId );
					$_SESSION ['allowanceslabel'] = $allowance . $Miscallow;
					$_SESSION ['dedulabel'] = $deduc . $Miscdeduc;
					
					// excel Arguments for table create
					$headerNameExcel = $allowance . $Miscallow . "GROSS," . $deduc . $Miscdeduc;
					$columrNameExcel = $allowanceId . $MiscallowIds . "gross_salary," . $deducId . $miscdeducId;
					
					// for datatable reportes
					if ($MiscallowIds !== '') {
						$allowanceId .= "SUM(" . substr ( str_replace ( ",", "+", $MiscallowIds ), 0, - 1 ) . "),gross_salary,";
						$allowance .= "OTHERA,";
					}
					
					if ($miscdeducId !== '') {
						$deducId .= "SUM(" . substr ( str_replace ( ",", "+", $miscdeducId ), 0, - 1 ) . "),";
						$deduc .= "OTHERD,";
					}
					
					$_SESSION ['headerName'] = $allowance . "GROSS," . $deduc;
					$_SESSION ['colName'] = $allowanceId . "gross_salary," . $deducId;
					?>
      <!-- header end -->
		<!-- sidebar start-->
		<aside>
            <?php include_once (__DIR__."/sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel" id="section_hide">
					<header class="panel-heading"> Salary statement </header>
					<form class="form-horizontal" id="go_validate" role="form"
						method="post">
						<div class="panel-body">
							<input type="hidden" id="salryStmtinput" autocomplete="off">
							<div class="col-lg-12 row">
								<label class="col-sm-2 control-label" id="control_label_month"
									style="text-align: right">Month</label>
								<div class="col-sm-4 input-group">
									<span class="input-group-addon" style="cursor: pointer"><i
										class="fa fa-calendar"></i></span>
									<div class="iconic-input right">
										<input class="form-control" name="attendance_month"
											id="datepicker" type="text">
									</div>
								</div>
								<i class="col-sm-2 fa fa-search-plus letter" id="advance_search"
									style="width: 3.667%; cursor: pointer; margin-top: 8px; font-size: 1.3em;"></i>
								<div class="col-sm-2" id="go_hide">
									<input class="btn btn-sm btn-success" id="go" value="Generate"
										type="button">
								</div>
								<div class="loader" style="width: 88%;"></div>
							</div>

							<div class="hide" id="excelTablecontent"></div>

							<div class=" row col-lg-12" id="period_hide"
								style="display: none">
								<div class="panel-body">
									<label class="col-lg-2 control-label" id="control_label_year">
										Salary View</label>
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
									style="display: none">
									<div class="form-group">
										<label class="col-lg-2 control-label" for="pf_limit">Designation
											Name</label>
										<div class="col-lg-7" style="margin-left: -2%;">
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
								<div class="col-lg-12" id="branch_details" style="display: none">

									<div class="form-group">
										<label class="col-lg-2 control-label" for="pf_limit">Branch
											Name</label>
										<div class="col-lg-7" style="margin-left: -2%;">
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

								<div class="col-lg-12" id="department_details"
									style="display: none">

									<div class="form-group">
										<label class="col-lg-2 control-label" for="pf_limit">Department
											Name</label>
										<div class="col-lg-7" style="margin-left: -2%;">
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

								<!--depart-->


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
							</div>
						</div>
					</form>



					<!-- page start-->

					<div class="container" id="table_show"
						style="width: 92%; display: none">
						<section class="error-wrapper"
							style="margin-top: 3%; text-align: left;">

							<h5 class="container exportButton"
								style="width: 92%; text-align: center;">
								<strong id="HeadingTable"></strong>
								<button class="btn btn-sm btn-default pull-right"
									style="margin-left: 1%" id="export-to-excel">
									<i class="fa fa-file-excel-o"></i> Excel
								</button>
								<a class="button-next  btn btn-sm btn-default" target=foo();
									href="salaryStmtreports.php"> <i class="fa fa-file-pdf-o"></i>
									PDF
								</a>
							</h5>
							<input type="hidden" id="flagForexcel"> <input type="hidden"
								id="flagForexcelData"> <input type="hidden" id="flagForexcelVal">

							<div id="visibleIfnodata" class="hide">
								<h6 class="container" style="width: 50%; text-align: center;">
									<strong>No Available Data For Salary Reports</strong>
								</h6>
							</div>
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
			<?php include_once (__DIR__."/footer.php");?>
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
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8"
		src="../js/FixedColumns.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
	<script src="../js/jquery.table2excel.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script type="text/javascript">
			$(document).ready(function () {       
				$('#go_validate')[0].reset();
	            $(document).on('click', ".Popup", function () {
						var NWin =   myWindow = window.open($(this).prop('href'), "myWindow", "width=900, height=500,scrollbars=no,toolbar=no,screenx=0,screeny=0,location=no,titlebar=no,directories=no,status=no,menubar=no");
						    if (window.focus)					
						     {
					
						       NWin.focus();
					
						     }
					
						     return false;
					
						    }); 	  
		        	   $("#period_hide").hide();
		        	   $(".loader,#designation_details,#branch_details,.container,.exportButton").hide();
		        	   $('#department_details').show();
		     });
		      
	          //datepicker Inistalization
	           $('#datepicker').datetimepicker({
        	    viewMode: 'years',
        	    format: 'MM YYYY'
            });
	            

			       $('#go').on('click', function (e) {
				      $('#flagForexcel').val(0);
				       e.preventDefault();
		        	   var date = $('#datepicker').val();
		               if(date){
		               reportGenerate(date,"");// Function Call For reportGenetare BasedOn Date selection
		               }
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
		              
		                  $('#flagForexcel').val(1);
		                  var val = $("input:radio[name='reports_for']:checked").val();
		            	  var selectedId = val =='E'?'e_attendance':val =='D'?'d_attendance':val =='F'?'f_attendance':'b_attendance';
		            	  var date = $('#datepicker').val();
		            	  $('#flagForexcelVal').val(val);
		            	  $('#flagForexcelData').val($("#"+selectedId).val());
		            	  reportGenerate(date,val,$("#"+selectedId).val(),"advanceReportSearch"); // Function Call For reportGenetare BasedOn Date selection
			               $("#advance_search").click();   
			            	  $('.deselect').click();   
				              
			                    	  
		              	});// Function Call For reportGenetare BasedOn (D,F,B)

					//cancel Event 
					 $(document).on('click', "#cancel", function (e) {
					                  e.preventDefault();
					                  $("#period_hide").hide();
					                  $("#go_hide").show();
					                //  $("#advance_search").click();  
									 });
					  
	                  //Function For Report Generate  This Create A table
		              function reportGenerate(date,val,affectedIds,key){
			              if($('#salryStmtinput').val()!=date){
			            	  $('#salryStmtinput').val(date);    
			              var flag=0;
		            	  $.ajax({
			                   dataType: 'html',
			                   type: "POST",
			                   url: "php/reports.handle.php",
			                   cache: false,
			                   data:{search:'reports_filter',date:date,reportFor:val,affectedIds:affectedIds,key:key},
			                   beforeSend:function(){
	       	                       	$('#go').button('loading'); 
	       	                        },
	       	                        complete:function(){
	       	                       	 $('#go').button('reset');
	       	                        },
			                   success: function (data) {
			                	  jsonobject= JSON.parse(data);
			                	  $('#table_show').show();
			                	  var n = ["","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
			                	  var dateNo = date.split(" "); 
			                	  $('.data_feed,#HeadingTable').empty();
			                	  $('#HeadingTable').html("Salary Statment For The Month Of "+n[Number(dateNo[0])]+" "+dateNo[1])
			                	  $('.container').show();
			                	   var html = '<div class="reportTable"><table  id="example"><thead><tr>';
                                   var copunt_tr=0;
			                	   $.each(jsonobject[2].tableHeader, function (k, v) {
			                		   copunt_tr++;
			             			  html += '<th>' + v.substring(0,6) + '</th>';
			             		   }); 
			                	   html += '</tr></thead><tfoot><tr>';
			                	   $.each(jsonobject[2].tableHeader, function (k, v) {
				             			  html += '<td  style="text-align:right"></td>';
				             	  });
			             		   html += '</tr><tbody>';
			                	   if(jsonobject[2].tableValues){
			                	   for (var i = 0, len = jsonobject[2].tableValues.length; i < len; ++i) {
			                		   if(i % 2 == 0){
				             		    	html += '<tr class="alt">';
					             		    }else{
					             		    	html += '<tr>';
					             		    }
			                		   $.each(jsonobject[2].tableValues[i], function (k, v) {
				                		if(k=='employee_id'){
				                			var date = $('#datepicker').val();
			                			  html += '<td><a href="viewPayroll.php?date='+date+'&&employeeId='+v+'" class="Popup" >'+v+'</a></td>';
			                			  }else if(k=='employee_name'){
			                				  html += '<td>' + v + '</td>';
					                    }else{
			                			  html += '<td style="text-align:right">' + reFormateByNumber(v) + '</td>';
			                      			  }
			                	       }); 
			                     	  html += "</tr>";
			                     	 flag=1;
			                		}
			                	   $('#visibleIfnodata').removeClass('hide show');
		                			$('#visibleIfnodata').addClass('hide');
			                		 $('.exportButton').show();
			                		}else{
			                			$('#go').button('reset');
			                			html='';
			                			$('.exportButton').hide();
			                			$('#visibleIfnodata').removeClass('hide show');
			                			$('#visibleIfnodata').addClass('show');
			                	   }
			                		html += '</tbody></table></div>';
			                		$(html).appendTo('.data_feed');
                             		if(flag==1)
                             		{  
			                		var oTable = $('#example').dataTable({
			                			 "bInfo":false,
			                			 "bPaginate": false,
			                			 "bFilter": false,
				        				 "sScrollY": "400px",
				        				 "sScrollX": "100%",
				        				 "sScrollXInner": "125%",
				        				 "bScrollCollapse": true,
				        				 "bPaginate": false,
				        				 "	": true,
				        				 "bAutoWidth": false,
				        				 "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
									      for(var k=2;k<=(copunt_tr-1);){
				        			        	var TotalMarks = 0;
				        			            for ( var i=iStart ; i<iEnd ; i++ ) {
					        			             TotalMarks += parseFloat(deFormate(aaData[aiDisplay[i]][k]));
				        			            }
				        			           var nCells = nRow.getElementsByTagName('td');
				        			          
				        			           // '<i class="fa fa-rupee"></i> '+
				        			           console.log(TotalMarks.toFixed(2))
				        			           nCells[k-2].innerHTML=reFormateByNumber(TotalMarks.toFixed(2));
				        			          k++;
				        			         }
}

			                		});
                             		}
			                		  new FixedColumns( oTable, {
			        					"iLeftColumns": 2
			        					} );
			        		
			        		         $(window).resize( function () {
			                			  oTable.fnAdjustColumnSizing();
			                			  } );
			                		
			        				}	                     
			               });
			              }
			              }
		              
		              $(document).on('click', "#export-to-excel", function (e) {
		            	  e.preventDefault();
			             var headerName='<?php echo $headerNameExcel;?>';
			              var columName='<?php echo $columrNameExcel;?>';
			              var date = $('#datepicker').val();
			              var val = $("input:radio[name='reports_for']:checked").val();
			              var key=$('#flagForexcel').val()==1?'Excel':"";
			              var val=$('#flagForexcelVal').val();
						   $.ajax({
			                   dataType: 'html',
			                   type: "POST",
			                   url: "php/reports.handle.php",
			                   cache: false,
			                   data:{search:'reports_filter',headerName:headerName,columName:columName,date:date,reportFor:val,affectedIds:$('#flagForexcelData').val(),key:key},
		                       beforeSend:function(){
       	                       	$('#export-to-excel').button('loading'); 
       	                        },
       	                        complete:function(){
       	                       	 $('#export-to-excel').button('reset');
       	                        },
			                   success: function (data) {
			                   jsonobject= JSON.parse(data);
			                	  var html = '<table  id="excelTable"><thead><tr><th style="text-align:center;color:#184A47;" colspan='+jsonobject[2].tableHeader.length+'>'+$('#HeadingTable').html()+'</th><tr class="totalColumn">';
			                	   $.each(jsonobject[2].tableHeader, function (k, v) {
			                		  html += '<th style="text-align:center;background-color:#41cac0;color:#FFF">' + v + '</th>';
			             		   }); 
			                	   html += '</tr><tbody>';
			                	 if(jsonobject[2].tableValues){
			                	   for (var i = 0, len = jsonobject[2].tableValues.length; i < len; ++i) {
			                			html += '<tr>';
			                		   $.each(jsonobject[2].tableValues[i], function (k, v) {
				                		if(k=='employee_id' || k=='employee_name'){
			                			  if(i % 2 == 0){
		                					    html += '<td  style="text-align:left;background-color:#E0F9FF;color:#184A47;">'+ v +  '</td>';
					             		    	 }else{
					             		    		  html += '<td  style="text-align:left;color:#184A47;">'+ v +  '</td>';
								              }
			                			  }else{
			                				   if(i % 2 == 0){
			                					    html += '<td  style="text-align:right;background-color:#E0F9FF;color:#184A47;">' +Math.round(v)+ '</td>';
						             		    	 }else{
							             		    html += '<td  style="text-align:right;color:#2A827C">' +Math.round(v)+ '</td>';
							             		    }
						             		   }
			                	       }); 
			                     	  html += "</tr>";
			                     	}
			                 	   
			                     } 
				                   html += '<tr>';
					               for (var i = 0, len = jsonobject[2].tableHeader.length; i < len; ++i) {
					                	 html += '<td class="total" style="text-align:right;background-color:#41cac0;color:#FFF"></td>';
			                	    }
					               html += '</tr></tbody></table>';
			                        //append table 
					                $('#excelTablecontent').html(html);
         //   Total assign
					                calculateColumn(jsonobject[2].tableHeader.length,'excelTablecontent');
					                $('#excelTable td.total').eq(0).html('-');
					                $('#excelTable td.total').eq(1).html('Total');
					              
			                        
			           //export table
		                $("#excelTable").table2excel({
							exclude: ".noExl",
							name: "Excel Document Name",
							filename: "SalarystmtOf"+date,
							exclude_img: true,
							exclude_links: true,
							exclude_inputs: true
						});
			                   }
		                   });
		              });

		             
		     </script>