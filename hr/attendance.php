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
<title>Attendance</title>
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
								<h4 class="modal-title">Attendance</h4>
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
      Attendance For <?php
						
$dateObj = DateTime::createFromFormat ( '!m', $_SESSION ['monthNo'] );
						echo $month_name = $dateObj->format ( 'F' ) . " " . $_SESSION ['curYear'];
						?>
        
        <div class="btn-group pull-right">
							<button id="showhideButton" type="button"
								class="btn btn-sm btn-success">
								<i class="fa fa-filter"></i> More Filter
							</button>
							&nbsp; <a href="import.php?for=Attendance" target="foo()"
								title="Attendance Import">
								<button id="" type="button"
									class="btn btn-sm btn-default pull-right">
									<i class="fa fa-upload" aria-hidden="true"></i> Attendance
								</button>
							</a>
						</div>
					</header>

				<div class="panel-body displayHide" id="filterHideShowDiv">
					<form class="form-horizontal" role="form" method="post">
					   <?php
							require_once (LIBRARY_PATH . "/filter.class.php");
							$filter = new Filter ();
							$filter->conn = $conn;
					       echo  $filterHtml = $filter->createFilterForScreen ('attendance');
					    ?>
				<div class="panel-body pull-right showedEmp displayHide">
			     	<button type="submit" class="btn btn-sm btn-success" id="addendanceSubmit">submit</button>
				   <button type="button" class="btn btn-sm btn-danger" id="filterCancel">Cancel</button>
			   </div>
		             </form>
				</div>

					<div class="col-lg-12">
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover table-bordered cf"
									id="slab-table" style="display: none;" width='100%'>
									<thead class="cf">
										<tr id="slab-table-head">
											<th>EmpId</th>
											<th>EmpName</th>
										</tr>
									</thead>
									<tbody>
										<tr>
										</tr>
									</tbody>
								</table>
							</section>
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
              leave_account(function (output) {
                  var affectid =0;
                  EditableTable.init(output, affectid);
                  $('#slab-table').show();
              });

              

              function leave_account(callBack) {
                  $.ajax({
                	  dataType: 'html',
                      type: "POST",
                      url: "php/leaveRule.handle.php",
                      cache: false,
                      data: { act: '<?php echo base64_encode($_SESSION['company_id']."!header_select");?>'},
                      success: function (data) {
	                      data = JSON.parse(data);
	                      var totalAllowances = 0;
                          dataTableColumns = {};
                          var aoColumns = [{ 'sName': 'w.employee_id' }, { 'sName': 'w.employee_name'}];
                          var allowancesArr = ['w.employee_id', 'w.employee_name'];
                          $('#allowances_list').empty();
                          for (lc = 0; lc < data[2].length; lc++) {
                              if (data[2][lc]['enabled'] == 1) {
                                  totalAllowances = totalAllowances + 2;
                                  aoColumns.push({ 'sName': 'payTmp.'+data['2'][lc]["alias_name"] });
                                  allowancesArr.push(data['2'][lc]["alias_name"]);
                                  $('#slab-table-head').append('<th>' + data['2'][lc]["alias_name"] + '</th>');
                              }
                          }
                          $('#slab-table-head').append('<th>compOff</th>');
                          $('#slab-table-head').append('<th>Remarks</th>');
                          $('#slab-table-head').append('<th>Lop</th>');
                          $('#slab-table-head').append('<th>Actions</th>');
                          aoColumns.push({ 'sName': 'ma.compoff', 'bSortable': false });
                          aoColumns.push({ 'sName': 'ma.Remarks', 'bSortable': false });
                          aoColumns.push({ 'sName': 'payTmp.lop', 'bSortable': false });
                          aoColumns.push({ 'sName': 'w.enabled', 'bSortable': false });
                          dataTableColumns['columns'] = allowancesArr;
                          dataTableColumns['aoColumns'] = aoColumns;
                          callBack(dataTableColumns);
                      }
                  });
              }
              $("tr td:nth-child(4)").hide();
           
                $(document).on('click', "#addendanceSubmit", function (e) {
                  e.preventDefault();
                  var oTable = $('#slab-table').dataTable();
                  oTable.fnDestroy();
                  EditableTable.init(dataTableColumns,$("#employeeSelected").chosen().val());
            	  $('#filterCancel').click();  
            	});

            

              $('input[class^="keyup"]').blur(function (e) {
                    e.preventDefault();
                  var class_name = $(this).attr("class").split(' ')[0];
                  var res = class_name.substring(5);
                  var values = $("." + class_name).val();             
                  var tds = $(this).parent().parent().parent().parent().find("table tr td");
                  for (var i = 0; i < tds.length; i++) {
                      if (tds.eq(i).text() === res) {
                          var account = tds.eq(i).parent().find("td:nth-child(2) span").html();
                          var max_ = tds.eq(i).parent().find("td:nth-child(4) span").html();
                          max = Math.min(max_, values);
                          var remaining = Math.max(account - max,0);
                          $("#" + res).val(remaining);
                          var availed=Number(account)-Number(remaining);
                          $("#values_"+res).val(availed);
                          tds.eq(i).parent().find("td:nth-child(5) span").html(availed);
                          var account_s = tds.eq(i).parent().find("td:nth-child(3) span").html(remaining);
                          var sum = 0;
                          var remain_value_set = class_name.split("p").pop();
                          var max = tds.eq(i).parent().find("td:nth-child(4) span").html();
                          var val = $(this).val();
                          if (val == null) {
                              val = "0";
                          } 
                            max=remaining==0?account:max;                        	  
                        	lop_calc(val,max,res);
						  }
                  }
                  
    });


              function lop_calc(val,max,res,lop_val){  
                  var sumTotal=0;
                  var cueentMOnthCount='<?php echo $_SESSION['noOfDaysInMonth']; ?>';
                  $('input[class^="keyup"]').each(function () {
                	  sumTotal += Number($(this).val());
                  });
             if(sumTotal <= cueentMOnthCount)    
                       {
                           $('#attedence_update').removeClass("disabled");
                    	   $('#error_msg').html('');
                    	   lop=Math.max(val-max,0);
                    	   var i=$("#e_"+res).val(lop);
                           var sum = 0;
                           $('[data-id="lop_value"]').each(function () {
                          	  sum += Number($(this).val());
                            });
                           $('#lopVal').val();
                           $('.lop').val(sum);
                           $('.lop').html(sum);
                           $('#lopVal').val(sum);
                           var lopVal=$('#lopVal').val()-$('#comOff').val();
                         	 $('.lop').val(lopVal);
                              $('.lop').html(lopVal);
                              $('#attedence_update').removeClass("disabled");
                              $('#error_msg').html('');
                              
                              
                     }else{
                       $('#attedence_update').addClass("disabled");
                       $('#error_msg').html('Enter valid days');
                     }	
             
             }    

              $('#comOff').blur(function (e) {
                 if(Number($('#comOff').val())<=Number($('#lopVal').val())){
                 var lopVal=$('#lopVal').val()-$('#comOff').val();
            	 $('.lop').val(lopVal);
                 $('.lop').html(lopVal);
                 $('#attedence_update').removeClass("disabled");
                 $('#error_msg').html('');
                  }else{
                	  $('#attedence_update').addClass("disabled");
                      $('#error_msg').html('Enter valid CompOff');
                      $('.lop').val($('#lopVal').val());
                      $('.lop').html($('#lopVal').val());
                      }
              });
              
              $('.lop').blur(function (e) {
            	  $('#lopVal').val($(this).val());
              });
              
             
              var EditableTable = function () {
            	    return {
            	        //main function to initiate the module
            	        init: function (allowData, id) {
            	            var aoColumns = allowData;
           	                var oTable = $('#slab-table').dataTable({
            	                "aLengthMenu": [
            	                    [5, 15, 20, -1],
            	                    [5, 15, 20, "All"] // change per page values here
            	                ],
            	               // set the initial value
            	                "iDisplayLength": 5,
            	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
            	                "bProcessing": true,
            	                "bServerSide": true,
            	                "sAjaxSource": "php/attendance_view.php",
            	                "fnServerParams": function (aoData) {
            	                    aoData.push({ "name": "columns", "value": allowData['columns'] }, { "name": "affectedIds", "value": id });
            	                },
            	                "sServerMethod": "POST",

            	                "sPaginationType": "bootstrap",
            	                "oLanguage": {
            	                    "sLengthMenu": "_MENU_ records per page",
            	                    "oPaginate": {
            	                        "sPrevious": "Prev",
            	                        "sNext": "Next"
            	                    }
            	                },
            	                "oColReorder": {
            	                    "iFixedColumns": 1
            	                },
                  	                
            	                "aoColumns": allowData['aoColumns'],
            	               
            	                "oTableTools": {
            	                    "aButtons": [
            	                {
            	                    "sExtends": "pdf",
            	                    "sPdfOrientation": "landscape",
            	                    "sPdfMessage": "Allowance Slab Details"
            	                },
            	                {
            	                    "sExtends": "xls"
            	                }
            	             ],
            	                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

            	                },
            	                "aoColumnDefs": [
            	             	                 {"bSearchable": false, "bVisible": false, "aTargets": [ (allowData['aoColumns'].length-3)] }      
                                                ] ,

            	            });
            	          
            	            $('#slab-table_wrapper .dataTables_filter').html('<div class="input-group">\
            	                                              <input class="form-control medium" id="searchInput" type="text">\
            	                                              <span class="input-group-btn">\
            	                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
            	                                              </span>\
            	                                              <span class="input-group-btn">\
            	                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
            	                                              </span>\
            	                                          </div>');
            	            $('#slab-table_processing').css('text-align', 'center');
            	            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            	            $('#slab-table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            	            $('#searchInput').on('keyup', function (e) {
            	                if (e.keyCode == 13) {
            	                    oTable.fnFilter($(this).val());
            	                } else if (e.keyCode == 27) {
            	                    $(this).parent().parent().find('input').val("");
            	                    oTable.fnFilter("");
            	                }
            	            });
            	            $('#searchFilter').on('click', function () {
            	                oTable.fnFilter($(this).parent().parent().find('input').val());
            	            });
            	            $('#searchClear').on('click', function () {
            	                $(this).parent().parent().find('input').val("");
            	                oTable.fnFilter("");
            	            });

            	            var nEditing = null;


            	            $(document).off('click', ".a_edit");
            	            $(document).off('click', "#attedence_update");

            	            $(document).on('click', ".a_edit",function (e) {
            	                e.preventDefault();
            	                /* Get the row as a parent of the link that was clicked on */
            	                var nRow = $(this).parents('tr')[0];
            	                var data = oTable.fnGetData(nRow);
            	                var jqTds = $('>td', nRow);
                     var totcolum= jqTds.length-4;
                     var lopV= jqTds.length-2;
                     var remarks= jqTds.length-3;
            	                var emName=data[1];
            	                var emId=data[0];
            	                $.ajax({
            	                	 dataType: 'html',
            	                     type: "POST",
            	                     url: "php/leaveaccount.handle.php",
            	                     cache: false,
            	                      data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>',id:data[0],leave_rules:'<?php echo $leave_rules;?>'},
            	                      // data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>',id:data[0],leave_rules:'pp.cl,'},
            	                     
									 success: function (data1) {
                	                    var json_obj = $.parseJSON(data1); //parse JSON
                	                    $('[data-id="lop_value"],input[class^="keyup"],input[id^="values_"]').each(function () { 
                    	               $(this).val(0);           	                      
                    	               });
                    	               $('#attedence_update').removeClass("disabled");
            	                    	$('#error_msg').html('');
            	                    	 if (json_obj[0]=='error') {
                	                    	 $('#employee_id').val(emId);
                	                    	 $('#e_employee_name').val(emName);
                	                    	 $('#e_employee_name_id').val(emName +"[ "+ emId +" ] ");
                	                    	 $('.col_hide,.trhideclass1').hide();
                	                    	 $('#table_val').hide();
                	                    	 $('#msg').show();
                	                    	 $('#lop_div').show();
                	                    	 $('.lop').val(data[lopV]);
                	                    	 (data[totcolum]=='-')?$('#comOff').val(0):$('#comOff').val(data[totcolum]);
                	                    	 $('#lopVal').val(data[lopV]);
                	                    	 $('#remarks').val(data[remarks]);
                	                   }else{  
                    	                $('#lop_div').hide();
                    	                $('#table_val').show();
                    	                     
            	                        $('#e_employee_name').val(json_obj[2][0].employee_name);
            	                        $('#employee_id').val(json_obj[2][0].employee_id);
            	                        $('#e_employee_name_id').val(json_obj[2][0].employee_name + " [ " + json_obj[2][0].employee_id + " ]");
            	                        $('.col_hide,.trhideclass1,#msg').hide();

                	                      var fruits =[];            	                      
            	                      for(var i=0;i<json_obj[2].length;i++)
            	                      {
            	                    	    $.each(json_obj[2][i], function (k, v) {
            	                    		  fruits.push(json_obj[2][i].alias_name+"_"+json_obj[2][i].leave_rule_id);
            	                    		 $('#max' +json_obj[2][i].alias_name).html(json_obj[2][i].max_combinable);
                 	                    	 $('#R_' +json_obj[2][i].alias_name).html(json_obj[2][i].allotted_total);
                	                    	  $('[data-id='+json_obj[2][i].alias_name+']').show();
            	                    		  $('[data-id=table_'+json_obj[2][i].alias_name+']').show();
            	                    		 });
         	                    		   }                	                  
                	                  $('#leave_value').html(fruits.join("<br />"));
                	                  fruits = $.unique(fruits);
                	                  $('#leave_value').val(fruits.sort().join());
                	                   }

            	                    $.each(json_obj[2][0], function (k, v) {
            	                             //display the key and value pair
            	                           $('.keyup'+k.toUpperCase()).val((v)? parseInt(v):0); 
            	                          });
								
            	                    $('#comOff').val((json_obj[2][0].compoff)? parseInt(json_obj[2][0].compoff):0);
            	                   var j=3;
                 	                    $('input[class^="keyup"]').each(function () {
                 	                    	 var class_name = $(this).attr("class").split(' ')[0];
                 	                         var res = class_name.substring(5);
                 	                         var values = $("." + class_name).val();  
                 	                         var tds = $(this).parent().parent().parent().parent().find("table tr td");
                 	                         for (var i = 0; i < tds.length; i++) {
                 	                             if (tds.eq(i).text() === res) {
                 	                                 var account = tds.eq(i).parent().find("td:nth-child(2) span").html();
                 	                                 var max_ = tds.eq(i).parent().find("td:nth-child(4) span").html();
                 	                                 max = Math.min(max_, values);
                 	                                var remaining = Math.max(account - max,0);
                 	                                 $("#" + res).val(remaining);       
                 	                                 var availed=Number(account)-Number(remaining);
                 	                                 $("#values_"+res).val(availed);
                 	                                 tds.eq(i).parent().find("td:nth-child(5) span").html(availed);
                 	                                 var account_s = tds.eq(i).parent().find("td:nth-child(3) span").html(remaining);
                 	                                 var sum = 0;
                 	                                 var remain_value_set = class_name.split("p").pop();
                 	                                 var max = tds.eq(i).parent().find("td:nth-child(4) span").html();
                 	                                 var val = $(this).val();
                 	                                 if (val == null) {
                 	                                     val = "0";
                 	                                 } 
                 	                                max=remaining==0?account:max; 
                 	                                lop_calc(val,max,res);
                 	       						  }
                 	                         }
                 	                         j=1+j;
                 	                     });
                 	                   finalLop=Number(json_obj[2][0].lop);
                 	                  $('.lop').val(finalLop);
                 	                 $('#remarks').val(json_obj[2][0].remarks);
                   	              }

            	                });
            	                setTimeout(function(){  $('#checkitsChange').val($("#lop_calc").serialize()); }, 100);
                            });




            	            $(document).on('click', "#attedence_update",function (e) {
            	            	 e.preventDefault();
 								if ($("#lop_calc").serialize() == $('#checkitsChange').val()) {
 									BootstrapDialog.alert('No More Changes Deducted');
    	                        }else{
        	                    if($("#remarks").val().length<=100){
        	                    	$('#error_msg').html('');
        	                   $.ajax({
            	                	dataType: 'html',
     		                        type: "POST",
     		                        url: "php/leaveaccount.handle.php",
           	                        cache: false,
     		                        data: $('#lop_calc').serialize(),
     		                         beforeSend:function(){
            	                       	$('#attedence_update').button('loading'); 
            	                        },
            	                        complete:function(){
            	                       	 $('#attedence_update').button('reset');
            	                        },
            	                    success: function (data) {
            	                        data1 = JSON.parse(data);
            	                        if (data1[0] == "success") {
            	                            $('.close').click();
            	                            $('#lop_calc')[0].reset();
            	                            oTable.fnDraw();
            	                            BootstrapDialog.alert('Attendance Updated Successfully');
            	                            setTimeout(function(){
            	                            	$('.close').click();
            	                            	}, 5000);

            	                        }
            	                        else
            	                            if (data1[0] == "error") {
            	                                alert(data1[1]);
            	                            }
            	                    }

            	                });
            	                }else{
                	                $('#error_msg').html('Summarise leave in 100 Characters')
                	                }
    	                        }
            	            });
            	           
            	        }

            	    };

            	} ();
          });

          $(document).on('blur', "#lopManual",function (e) {
         	 e.preventDefault();
         	 $('.lop').val($(this).val());
          });
      </script>
</body>
</html>
