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
<title>Employee LOP</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style>
@media ( min-width : 992px) {
	.modal-lg {
		width: 800px;
	}
}
</style>
</head>
<body>
<section id="container" class="">
		<!--header start-->
     <?php
					include_once (__DIR__ . "/header.php");
					$stmt = "SELECT * FROM  company_leave_rules where enabled=1";
					$payerollMonthyear = $_SESSION ['monthNo'] . $_SESSION ['payrollYear'];
					$result = mysqli_query ( $conn, $stmt );
					while ( $row = $obj = mysqli_fetch_object ( $result ) ) {
						$row->alias_name;
					}
					mysqli_free_result ( $result );
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
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
					tabindex="-1" id="shift_edit" class="modal fade"
					data-keyboard="false" data-backdrop="static">
					<div class="modal-dialog  modal-lg">

						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close"
									type="button">&times;</button>
								<h4 class="modal-title">Employees LOP</h4>
							</div>
							<form id="lop_calc" method="POST" class="form-horizontal"
								role="form">
								<input id="checkitsChange" type="hidden">
								<div class="modal-body">
									<div class=" row  col-lg-12">
										<div class="col-lg-6">
											<input type="hidden" name="employee_id" id="employee_id">
											<div class="form-group col-lg-12">
												<label class="col-lg-4 control-label">Name</label>
												<div class="col-lg-8">
													<input type="hidden" class="form-control"
														name="employee_name" readonly id="e_employee_name"
														value="" /> <input type="text" class="form-control"
														readonly id="e_employee_name_id" value="" />
												</div>
											</div>


                                             <?php
																																														$leave_rule_id = array ();
																																														$stmt = "SELECT * FROM  company_leave_rules where enabled=1";
																																														$result = mysqli_query ( $conn, $stmt );
																																														while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
																																															$leave_rule_id [] = "la." . $row ['leave_rule_id'];
																																															echo ' <div class="form-group col-lg-12 col_hide"  data-id=' . $row ["alias_name"] . '>
	                                       <label class="col-lg-4 control-label">' . $row ["alias_name"] . '</label>
                                        <div class="col-lg-8">
                                              <input type="text" class="keyup' . $row ["alias_name"] . ' form-control"  value="0" name="keyup' . $row ["alias_name"] . '" />
                                   </div>
                                     </div>';
																																													}
																																													$leave_rules = implode ( ',', $leave_rule_id ) . ",";
																																													?>
    
     <div class="form-group col-lg-12" id="lop_div">
			<label class="col-lg-4 control-label">lop</label>
			<div class="col-lg-8">
				 <input type="hidden" class="form-control lop" name="lop_sub" value="0.00" />
				 <input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
				 <input type="text" class="form-control lop" name="lop"	value="0.00" />
				 <input type="hidden" class="form-control" name="leave_value" id="leave_value" />
			</div>
	</div>
											<div class="form-group col-lg-12">
												<label class="col-lg-4 control-label">Comp Off</label>
												<div class="col-lg-8">
													<input type="hidden" class="form-control" id="lopVal"
														value="0" /> <input type="text" class="form-control"
														name="comOff" id="comOff" value="0" />
												</div>
											</div>

										</div>
										<div class="col-lg-6" id="msg" style="display: none;">
											<h5>No leave Rules Are Found</h5>
										</div>
										<div class="col-lg-6">
											<table class="table table-striped table-hover table-bordered"
												style="background-color: rgb(249, 249, 249);" id="table_val">
												<tbody>
													<tr>
														<td colspan="4" style="text-align: center; padding: 9px;">Leave
															Information</td>
													</tr>
													<tr>
														<td style="width: 94px; padding: 9px;">Leave Type</td>
														<td style="width: 81px; padding: 9px;">Eligible</td>
														<td style="padding: 9px;">Remaining</td>
														<td></td>
														<td style="padding: 9px;">Availed</td>
													</tr>
                                    <?php
																																				
$stmt = "SELECT * FROM  company_leave_rules  where enabled=1";
																																				$result = mysqli_query ( $conn, $stmt );
																																				while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
																																					echo '<input type="hidden" id="values_' . $row ["alias_name"] . '"  value="0.00" name="values_' . $row ["alias_name"] . '" /><input type="hidden" id="e_' . $row ["alias_name"] . '" data-id="lop_value" value="0.00">
    		<input type="hidden" id="' . $row ["alias_name"] . '" value="0.00" name="' . $row ["alias_name"] . '"><tr class="keyup' . $row ["alias_name"] . ' trhideclass1"  data-id="table_' . $row ["alias_name"] . '">
    		<td style="width: 62px;padding: 9px;" >' . $row ["alias_name"] . '</td><td  style="width: 10px;padding: 9px;"><span id="R_' . $row ["alias_name"] . '">0.00</span></td> <td  style="width: 60px;padding: 9px;"><span id="'. $row ["alias_name"] . '"></span></td>
    		<td  style="width: 60px;padding: 9px;" clas="max"><span id="max' . $row ["alias_name"] . '"></span></td><td  style="width: 60px;padding: 9px;" clas="max"><span id="availed_' . $row ["alias_name"] . '"></span></td></tr>';
																																				}
																																				?>
                                        
                                        <tr>
														<td>Lop</td>
														<td colspan="3"><input type="text" class="form-control lop" id="lopManual" value="0.00" /></td>
													</tr>
												</tbody>
											</table>
											<div class="form-group">
												<label for="dname" class="col-lg-3 col-sm-3 control-label">Remarks
												</label>
												<div class="col-lg-9">
													<textarea class="form-control" rows="2" name="remarks" id="remarks"placeholder="Summarize leave in 100 Characters" maxlength="100" value="" required name="remark"></textarea>
												</div>
											</div>
											<br> <em id="error_msg" style="color: rgb(227, 65, 65)"></em>
										</div>


									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-sm btn-danger"
											data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-sm btn-success"
											id="attedence_update">Update</button>
									</div>
								</div>

							</form>

						</div>
					</div>
				</div>



				<section class="panel">
					<header class="panel-heading">
     					LOP Employees 
					</header> 
				<div class="panel-body" id="filterHideShowDiv">
					<form class="form-horizontal" role="form" method="post">
					   <?php
							require_once (LIBRARY_PATH . "/filter.class.php");
							$filter = new Filter ();
							$filter->conn = $conn;
					       echo  $filterHtml = $filter->createFilterForScreen ('attendances');
					    ?>
						<div class="panel-body pull-right showedEmp displayHide">
					     	<button type="submit" class="btn btn-sm btn-success" id="lop_filter_Submit">submit</button>
						   <button type="button" class="btn btn-sm btn-danger" id="lop_filter_cancel">Cancel</button>
					   </div>
		            </form>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
				 		<form id="shiftAllocForm" class="panel-body" >
						 <div id="lopContent"> 	</div>
						</form>
					</div>
				</div>
				</section>
				<!-- page end-->
			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include_once (__DIR__."/footer.php");  ?>
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
	<!--script for this page only-->
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/jquery.validate.min.js"></script>


	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          $(document).ready(function () {
        	 // loadData();
        	 // $('.newval').attr('hidden');
          });
          $(document).on(function () {
        	  $('#LOPcancel').addClass('hide');
          }); 
          
       var loadData= (function(empID) {
        	  $.ajax({
                  dataType: 'html',
                  type: "POST",
                  url: "php/leaveaccount.handle.php",
                  cache: false,
                  data: {
                      act:'<?php echo base64_encode($_SESSION['company_id']."!getLateLOP");?>',empID:empID},
                  success: function (data) {
                     jsonobject= JSON.parse(data);
                       $('#perq-data').hide();
                       $('#lopContent').empty();
                        var html = '<section id="flip-scroll"> <table class="table tab-content tasi-tab table-striped table-hover table-bordered cf dataTable " id="shift-sample" style="width:100%;overflow:hidden;"> <thead class="cf"><tr><th>EMPID</th><th>Name</th><th>Attendance LOP</th><th>Late LOP</th></tr></thead><tbody>';               	                	  
                           for (var i = 0, len = jsonobject[2].length; i < len; ++i) {
                          	 //html += '<tr><td><input type="hidden" id="employee_id" value='+jsonobject[2][i].employee_id+'></td>';
								
								html +='<tr>';
							 $.each(jsonobject[2][i], function (k, v) {
								
      								if(k=='alop'){
      								    html += '<td class="alop_value"><input id="a_lop" class="a_lop" type="hidden" data-id='+jsonobject[2][i].employee_id+' value='+v+'><span class="a_lop_1">'+v+'</span></td>';
      								}else if(k=='late_lop'){
      									html += '<td class="latelop_value"><input id="late_lop" class="late_lop" type="hidden" value='+v+'><span class="late_lop_1">'+v+'</span></td>';
      								}else if(k=='employee_id' || k=='employee_name' )
                  			  			html += '<td>'+v+'</td>';
                    		 		}); 
                            	html += '</tr>';
                        }
                        
                        html += '</tbody> </table></section><div class="panel-body pull-right"style="margin-bottom:40px;" ><button class="btn btn-sm btn-info" style="margin-right: 5px;" id="LOPedit">Edit</button><button class="btn btn-sm btn-danger hide" style="margin-right: 5px;" id="LOPcancel">Cancel</button><button type="submit" class="btn btn-sm btn-success" id="lopSubmit">Lock & Submit</button></div>';
                        //append table 
                        $('#lopContent').html(html);
                       
                          setTimeout(function(){  
                              var oTable = $('#shift-sample').dataTable( {
                             	 "aLengthMenu": [
                                                  [10, 15, 20, -1],
                                                  [10, 15, 20, "All"] // change per page values here
                                              ],
                                  "iDisplayLength": 10,
                                   "aoColumnDefs": [
                                                    //{"bSearchable": false, "bVisible": false ,"aTargets":0 }      
                                                  ] ,
                                    } );
                              },100);
                              $('#lopContent').removeClass('loader');
                              $('#shift-sample_wrapper .dataTables_filter').html('<div class="input-group">\
         	                         <input class="form-control medium" id="branch_searchInput" type="text">\
         	                         <span class="input-group-btn">\
         	                           <button class="btn btn-white" id="branch_searchFilter" type="button">Search</button>\
         	                         </span>\
         	                         <span class="input-group-btn">\
         	                           <button class="btn btn-white" id="branch_searchClear" type="button">Clear</button>\
         	                         </span>\
         	                     </div>');
         	            $('#shift-sample_processing').css('text-align', 'center');
         	           
         	            jQuery('#shift-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
         	           // jQuery('#reportTable_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
         	            $('#branch_searchInput').on('keyup', function (e) {
         	            if (e.keyCode == 13) {
         	            oTable.fnFilter($(this).val());
         	            } else if (e.keyCode == 27) {
         	            $(this).parent().parent().find('input').val("");
         	            oTable.fnFilter("");
         	            }
         	            });
         	            
         	            $(document).on('click', "#branch_searchFilter", function () {
         	            oTable.fnFilter($(this).parent().parent().find('input').val());
         	            });
         	            $(document).on('click', "#branch_searchClear", function () {
         	            $(this).parent().parent().find('input').val("");
         	            oTable.fnFilter("");
         	            });  
         	           

                        }
              });
		   });

       $(document).on('click', "#lop_filter_Submit", function (e) {
           e.preventDefault();
           $('#filterHideShowDiv').addClass('hide');
           var employee_id = $("#employeeSelected").chosen().val();
           loadData(employee_id);
           //$('#attn-empId').val($("#employeeSelected").chosen().val());
     	     $('#lop_filter_cancel').click();  
     	});
 
       $(document).on('click',"#LOPedit",function (e) {
     	  e.preventDefault();
     	 var tble = $(this).parent().parent().find('#shift-sample').html();
     	//$('tble tr').each(function() {
        // console.log(tr);
	     	 $('.late_lop').attr('type', 'text');
	     	 $('.late_lop_1').addClass('hide');
     	//});
     	 $('#LOPedit').hide();
     	 $('#LOPcancel').removeClass('hide');
     	  
     	 
     	
       });

       $(document).on('click',"#lopSubmit",function (e) {
      	  e.preventDefault();
      	 var tble=$(this).parent().parent().find('#shift-sample');
      	 var lopdata='';
      	 $('#shift-sample tr').each(function() {
      		  alop=$(this).find(".alop_value input").val();
      		  data_val=$(this).find(".alop_value input").attr("data-id");
      	 	  latelop=$(this).find(".latelop_value input").val();
			  if(alop != undefined)
      	 	  	  lopdata+= data_val+'|'+alop+'|'+latelop+',';
  	 	  	  console.log(lopdata);
      	   });
      		$.ajax({
                dataType: 'html',
                type: "POST",
                url: "php/leaveaccount.handle.php",
                cache: false,
                data: {
                    act:'<?php echo base64_encode($_SESSION['company_id']."!UpdateLateLOP");?>',emplopdata:lopdata},
                success: function (data) {
                	jsonData= JSON.parse(data);
                   if (jsonData[0] == "success") {
                  	  BootstrapDialog.show({
										  type: BootstrapDialog.TYPE_SUCCESS,
						   				  title: 'Information',
										  message: 'Attendance Processed Successfully',
												buttons: [{
							   						label: 'ok',
							   						cssClass: 'btn-primary' ,
							   						action: function(dialogRef){
				            							window.location.href = "../hr/lopupdate.php";
							   						}
					            				}]
										  });
                   }
                  else
                      if (jsonData[0] == "error") {
                    	  BootstrapDialog.alert(jsonData[2]);
                    	 //$('#editLoader').loading(true);
                      }
                }
      		});
        });
              
       
      </script>
</body>
</html>
