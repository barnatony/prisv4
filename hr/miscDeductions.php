<?php
include_once (dirname ( __DIR__ ) . "/include/config.php");
?>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
	tabindex="-1" id="deduction_edit" class="modal fade">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close"
					type="button">×</button>
				<h4 class="modal-title">Edit Deduction</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" method="post"
					id="d_payment_edit_form">
					<input type="hidden" name="dId" id="eDeducType"> <input
						type="hidden" name="act"
						value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />

					<div class="panel-body">
						<div class="col-lg-12 " id="inline_content">
							<label class="col-lg-2 control-label"> Deduction For </label>
							<div class="col-lg-12">
								<label for="d_e_Employee" class="col-lg-2 control-label"> <input
									name="d_e_payments_type" id="d_e_Employee" type="radio"
									value="E"> Employee
								</label> 
							</div>

						</div>

					</div>
					<div class="row col-lg-12"
						style="border-top: 1px solid rgb(229, 229, 229);">
						<br> <input type="hidden" name="deducId" id="d_e_payment_id">
							 <input	type="hidden" name="pId" id="ePaymentType"> <input type="hidden"
							 name="act"	value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />		
							 <div class="form-group col-lg-12">
							<label class="col-lg-2 control-label" for="pf_limit">Employee
								Name</label>

							<div class="col-lg-10">
								<select class="form-control" id="d_e_e_name" name="empIds[]"
									multiple>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, "SELECT employee_name, employee_id FROM employee_work_details  " );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $employee_name, $employee_id );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $employee_id . "'>" . $employee_name . " [ " . $employee_id . " ] <br>" . "</option>";
																																										}
																																										?>
                                             </select>
							</div>
						</div>
					
						<div class="form-group col-lg-6">
							<label class="col-lg-4 control-label">Deduction Name</label>
							<div class="col-lg-8">
								<select class="form-control" id="d_e_payment_name" name="dCategory">
                               <?php
																															$stmt = mysqli_prepare ( $conn, "SELECT display_name,pay_structure_id FROM  company_pay_structure WHERE  display_flag = 1 AND type='MD' ORDER BY sort_order" );
																															$result = mysqli_stmt_execute ( $stmt );
																															mysqli_stmt_bind_result ( $stmt, $display_name, $pay_structure_id );
																															while ( mysqli_stmt_fetch ( $stmt ) ) {
																																echo "<option value='" . $pay_structure_id . "'>" . $display_name . "" . "</option>";
																															}
																															?>
                           </select>
							</div>
						</div>
						<div class="form-group col-lg-6">
							<label class="col-lg-5 control-label">Effects From</label>
							<div class="col-lg-7 input-group">
								<span class="input-group-addon" style="cursor: pointer"><i
									class="fa fa-calendar"></i></span>
								<div class="iconic-input right">
									<input class="form-control" type="text" name="effectsFrom"
										id="d_e_applicable_form_db" required />
								</div>
							</div>
						</div>
					</div>

					<div class="row col-lg-12">

						<div class="form-group col-lg-6">
							<label class="col-lg-4 control-label">Type</label>
							<div class="col-lg-8">
								<label for="d_e_Lumsum" class="col-lg-6 control-label"> <input
									name="cal" id="d_e_Lumsum" type="radio" value="1" checked>lump
									sum
								</label> <label for="d_e_Percentage"
									class="col-lg-6 control-label"> <input name="cal"
									id="d_e_Percentage" type="radio" value="0"> Percentage
								</label>
							</div>
						</div>

						<div class="form-group col-lg-6">
							<label class="col-lg-5 control-label">No of Repetition</label>
							<div class="col-lg-7 ">
								<input type="text" class="form-control" name="count"
									id="d_e_count" value="" />
							</div>
						</div>

					</div>
					<div class="row col-lg-12">

						<div class="form-group col-lg-6">
							<label class="col-lg-4 control-label d_e_Percentage_i">Amount</label> 
							<label class="col-lg-4 control-label d_e_Lumsum_i">percentage</label>
							<div class="col-lg-8  input-group">
								<span class="input-group-addon d_e_Percentage_i"><i
									class="fa fa-rupee"></i></span> <span
									class="input-group-addon d_e_Lumsum_i">%</span>
                                <div class="iconic-input right ">
									<i class="left"
										style="font-size: 12px; margin: 9px 11px 6px 0px;"></i> <input
										class="form-control" name="Amount" id="d_e_payment_amount"
										type="text">
								</div>
							</div>
						</div>

						<div class="form-group col-lg-6">
							<label class="col-lg-5 control-label">Remarks</label>
							<div class="col-lg-7 ">
								<textarea class="form-control" id="eremark" rows="2" cols="15"
									name="remarks"></textarea>
							</div>
						</div>
					</div>
					<div class="row col-lg-12">
						<div class="form-group col-lg-6 d_e_Lumsum_i">
							<label class="col-lg-4 control-label">Salary Head</label>
							<div class="col-lg-8">
								<select class="form-control" id="d_e_payments_in" name="In[]" multiple>
								 <option value="GROSS">Gross</option>
                                          <?php
																																										$stmt = mysqli_prepare ( $conn, "SELECT pay_structure_id, display_name FROM company_pay_structure WHERE display_flag = 1 && type='A' " );
																																										$result = mysqli_stmt_execute ( $stmt );
																																										mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name );
																																										while ( mysqli_stmt_fetch ( $stmt ) ) {
																																											echo "<option value='" . $pay_structure_id . "'>" . $display_name . "</option>";
																																										}
																																										?>
                                             </select>
							</div>
						</div>
					</div>
			
			</div>



			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger"
					data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-sm btn-success d_save">Save
					changes</button>
			</div>
			</form>
		</div>
	</div>
</div>
</div>
<section class="panel">
	<div class="panel-body">
		<div class="adv-table editable-table">
			<section id="flip-scroll">
				<table class="table table-striped table-hover table-bordered cf"
					id="deduction_table">
					<thead class="cf">
						<tr>
							<th>Name</th>
							<th>Deduction For</th>
							<th>Amount</th>
							<th>Effects From</th>
							<th>Effects Upto</th>
							<th style="width:200px">Remarks</th>
							<th style = "width:100px">Action</th>

						</tr>
					</thead>
				</table>
			</section>
		</div>
	</div>
</section>

<!-- END JAVASCRIPTS -->
<script type="text/javascript">
      var dec_EditableTable = function () {

    	    return {

    	        //main function to initiate the module
    	        init: function (allowData) {

    	            var nCloneTh = document.createElement('th');
    	            var nCloneTd = document.createElement('td');
    	            nCloneTd.innerHTML = '<img class="openClose" src="../css/images/details_open.png">';
    	            nCloneTd.className = "center";
    	            $('#deduction_table thead tr').each(function () {
    	                this.insertBefore(nCloneTh, this.childNodes[0]);
    	            });

    	            $('#deduction_table tbody tr').each(function () {
    	                this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
    	            });

    	            /*
    	            * Initialse DataTables, with no sorting on the 'details' column
    	            */
    	            var deduoTable = $('#deduction_table').dataTable({
    	                "aoColumnDefs": [
    	                  { "bSortable": false, "aTargets": [0] }
    	              ],
    	                "aaSorting": [[1, 'asc']],
    	                "aLengthMenu": [
    	                    [5, 15, 20, -1],
    	                    [5, 15, 20, "All"] // change per page values here
    	                ],

    	                // set the initial value
    	                "iDisplayLength": 5,
    	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

    	                "bProcessing": true,
    	                "bServerSide": true,
    	                "sAjaxSource": "php/miscDedu-view.php",
    	                "fnServerParams": function (aoData) {
    	                    aoData.push({ "name": "columns", "value": allowData });
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
    	                "aoColumns": [  {  "bSortable": false },
	                       { "sName": "deduc_name","bSortable": false  },
 	                        { "sName": "deduc_amount","bSortable": false  },
 	                          { "sName": "effects_from","bSortable": false  },
 	                         { "sName": "effects_upto","bSortable": false  },
 	                          { "sName": "deduc_category","bSortable": false  },
 	                         { "sName": "deduc_category","bSortable": false  },
    	              ],"aoColumnDefs": [{ "mRender": function 
    	                        (data, type, row) {
  			  	                        return row[13];
    	                         },
    	                             "aTargets": [1]

    	                         } ,{ "mRender": function 
    	                      	                        (data, type, row) {
    	                      	                        	var dataStr=data.split("|");
    	                      	                             if (dataStr[1]=='A') {
    	                      	                                 return '<i class="fa fa-rupee"></i> ' +dataStr[0];
    	                      	                             } else {
        	                      	                             return dataStr[0]+' %';
        	                      	                             }	                             
    	                      	                         },
    	                      	                             "aTargets": [3]

    	                      	                         },{ "mRender": function 
    	                         	                        (data, type, row) {
    	                        	                        	var parts =row[4].split(' ');
    	                        	                       		var effectFrom=parts[2]+"-"+parts[1]+"-"+parts[0];   
    	                        	                          	var parts =row[5].split(' ');
    	                        	                            var effectUpto=parts[2]+"-"+parts[1]+"-"+parts[0]; 
    	                     	                       		var currentMonthdate='<?php echo $_SESSION['current_payroll_month'];?>';
    	                          	                        if(currentMonthdate <=effectUpto && currentMonthdate >=effectFrom ){//view payment only nOt edit approve
    	                          	                        	 if(row[7]==1){
    	                                    	                        return '<a class="deduction_disable" href="" title="Disable"><button class="btn btn-success  btn-xs" style="padding: 1px 6px;"><i class="fa fa-unlock"></i></button></a>&nbsp<a href="#deduction_edit"  title="Edit" class="deduction_edit" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a>';
    	                                	                        }else{
    	                                	                        	return '<a class="deduction_enable"   title="Enable" href=""><button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>&nbsp;<a   title="view" href="#deduction_edit" class="deduction_view" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button></a>';
    	                                        	                }
    	                          	                        }else{//view payment and  edit approve
    	                          	                        	  if(row[7]==1){
    	                                    	                        return '<a class="deduction_disable" href="" title="Disable"><button class="btn btn-success  btn-xs" style="padding: 1px 6px;"><i class="fa fa-unlock"></i></button></a>&nbsp<a href="#deduction_edit"  title="Edit" class="deduction_edit" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a>';
    	                                	                        }else{
    	                                	                        	return '<a class="deduction_enable"   title="Enable" href=""><button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>&nbsp;<a   title="View" href="#deduction_edit" class="deduction_view" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a>';
    	                                        	                }
    	                          	                        }                   
    	                         	                         },
    	                         	                             "aTargets": [7]

    	                         	                         }],

                        "oColVis": {
    	                    "aiExclude": [0]
    	                },
    	                "oTableTools": {
    	                    "aButtons": [
    	                {
    	                    "sExtends": "pdf",
    	                    "mColumns": [1, 2, 3, 4, 5, 6],
    	                    "sPdfOrientation": "landscape",
    	                    "sPdfMessage": "Employee Details"
    	                },
    	                {
    	                    "sExtends": "xls",
    	                    "mColumns": [1, 2, 3, 4, 5, 6]
    	                }
    	             ],
    	                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

    	                }
    	            });
    	            $('#deduction_table_wrapper .dataTables_filter').html('<div class="input-group">\
    	                                              <input class="form-control medium" id="de_searchInput" type="text">\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="de_searchFilter" type="button">Search</button>\
    	                                              </span>\
    	                                              <span class="input-group-btn">\
    	                                                <button class="btn btn-white" id="de_searchClear" type="button">Clear</button>\
    	                                              </span>\
    	                                          </div>');
    	            $('#deduction_table_processing').css('text-align', 'center');
    	            //$('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
    	            $('#deduction_table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
    	            $('#de_searchInput').on('keyup', function (e) {
    	                if (e.keyCode == 13) {
    	                	deduoTable.fnFilter($(this).val());
    	                } else if (e.keyCode == 27) {
    	                    $(this).parent().parent().find('input').val("");
    	                    deduoTable.fnFilter("");
    	                }
    	            });

    	            $(document).on("click", "#de_searchFilter", function () {
    	            	deduoTable.fnFilter($(this).parent().parent().find('input').val());
    	            });
    	            $(document).on("click", "#de_searchClear", function () {
    	                $(this).parent().parent().find('input').val("");
    	                deduoTable.fnFilter("");
    	            });

    	            /* Add event listener for opening and closing details
    	            * Note that the indicator for showing which row is open is not controlled by DataTables,
    	            * rather it is done here
    	            */
    	            $(document).on("click", "#deduction_table tbody td img.openClose", function (e) {
    	                //$("#deduction_table tbody td img.openClose").click(function (e) { 
    	                e.preventDefault();
    	                var nTr = $(this).parents('tr')[0];
    	                if (deduoTable.fnIsOpen(nTr)) {

    	                    /* This row is already open - close it */
    	                    this.src = "../css/images/details_open.png";
    	                    this.class = 'openClose';
    	                    deduoTable.fnClose(nTr);
    	                }
    	                else {
    	                    /* Open this row */
    	                    this.src = "../css/images/details_close.png";
    	                    this.class = 'openClose';
    	                    

    	                var nRow = $(this).parents('tr')[0];
    	                var data = deduoTable.fnGetData(nRow);
    		            var p_id = data[11];
    	                $.ajax({
    	                    dataType: 'html',
    	                    type: "POST",
    	                    url: "php/miscDeductions.handle.php",
    	                    cache: false,
    	                    data:{ act: '<?php echo base64_encode($_SESSION['company_id']."!getgroupEmployee");?>',deducId:p_id },
    	                    success: function (data) {
      	                        data1 = JSON.parse(data);
      	                      var tt = deduoTable.fnOpen(nTr, fnFormatDetails(deduoTable, nTr,data1[2]), 'details');
    	                    }
      		             });
    	                }
    	            });



    	            function fnFormatDetails(deduoTable, nTr,groupDeduDetails) {
						//console.log(groupDeduDetails);
    	                var aData = deduoTable.fnGetData(nTr);
    	                var empstr = "";
      	              	var count = groupDeduDetails.length;
    	  	            for(i=0;i<count;i++){
      	                	empstr += ","+groupDeduDetails[i]["employee"];
      	                }
      	              	empstr = empstr.substring(1);
    	                var sOut = '<div class="col-lg-12"><table width="100%" height="17%" border="0" cellspacing="0" cellpadding="5">';
    	                sOut += '<tr valign="top"><td width="19%" colspan:"3"><b>Deduction In</b></td><td>' + aData[09] + '</td></tr>';
    	                sOut += '<tr valign="top"><td width="19%" colspan:"3"><b>Affected Ids</b></td><td>' + empstr + '</td></tr>';
    	                sOut += '<tr valign="top"><td width="19%" colspan:"3"><b>Processed on</b></td><td>' + aData[14] + '</td></tr></div>';
    	                return sOut;
    	            }

    	            $(document).on('click', ".d_save", function (e) {
    	                e.preventDefault();
    	                var data = $('#d_payment_edit_form').serializeArray();
  		  	          data.push({name: 'affectedIds', value: $("#d_e_e_name").chosen().val()});
    	                $.ajax({
    	                    dataType: 'html',
    	                    type: "POST",
    	                    url: "php/miscDeductions.handle.php",
    	                    cache: false,
    	                    data:data,
    	                    beforeSend:function(){
    	                     	$('.d_save').button('loading'); 
    	                      },
    	                      complete:function(){
    	                     	 $('.d_save').button('reset');
    	                      },
    	                     success: function (data) {
    	                        data1 = JSON.parse(data);
    	                        if (data1[0] == "success") {
    	                            $('#d_payment_edit_form')[0].reset();
    	                            $('#d_payments_in').prop('selected', false).trigger('chosen:updated');
    	                            $('#d_e_name').prop('selected', false).trigger('chosen:updated');
    	                            $('#d_payment_add_form').hide();
    	                            deduoTable.fnDraw();
    	                            $('.close').click();
    	                            BootstrapDialog.alert('Misc Deduction Updated Successfully');
    	                        }else{
    	                        	  BootstrapDialog.alert('Misc Deduction Updated Failed');
        	                        }
    	                        
    	                    }

    	                });

    	            });

    	            //diable code
    	            $(document).on('click', "#deduction_table a.deduction_disable", function (e) {
    	                e.preventDefault();
    	                var nRow = $(this).parents('tr')[0];
    	                var data = deduoTable.fnGetData(nRow);
    	                var e_id = data[11];
    	                var d_name = data[1];
    	                BootstrapDialog.show({
      	  	                title:'Confirmation',
      	                      message: 'Are Sure you want to Disable <strong>'+ d_name +'</strong>',
      	                    closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
      	                      buttons: [{
      	                          label: 'Disable',
      	                          cssClass: 'btn-sm btn-success',
      	                          autospin: true,
      	                          action: function(dialogRef){
      	                        	 $.ajax({
      	    	                        dataType: 'html',
      	    	                        type: "POST",
      	    	                        url: "php/miscDeductions.handle.php",
      	    	                        cache: false,
	      	    	                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>',deducId:e_id },
	       		                        beforeSend:function(){
      	    	                         	$('.dedu_disable_form').button('loading'); 
      	    	                          },
      	    	                          complete:function(){
      	    	                         	 $('.dedu_disable_form').button('reset');
      	    	                          },
      	    	                        success: function (data) {
      	    	                        	data = JSON.parse(data);
       	    	                             if (data[0] == "success") {
      	    	                               $('.model_msg0').click();
      	    	                             deduoTable.fnDraw();
      	    	                              $('.close').click();
  	                                    	  BootstrapDialog.alert('Misc Deduction Enabled Successfully');
      	    	                            }

      	    	                        }
      	    	                    });
      	                                  		                            
      	                          }
      	                      }, {
      	                          label: 'Close',
      	                        cssClass: 'btn-sm btn-danger',
      	                        
      	                          action: function(dialogRef){
      	                              dialogRef.close();
      	                          }
      	                      }]
      	                  });
    	            });

    	           

    	            //Disable coding

    	              $(document).on('click', "#deduction_table a.deduction_enable", function (e) {
    	                e.preventDefault();
    	                var nRow = $(this).parents('tr')[0];
    	                var data = deduoTable.fnGetData(nRow);
    	                var e_d_id = data[11];
    	                var e_d_name = data[1];
    	                BootstrapDialog.show({
      	  	                title:'Confirmation',
      	                      message: 'Are Sure you want to Enable <strong>'+ e_d_name +'</strong>',
      	                    closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
      	                     
      	                      buttons: [{
      	                          label: 'Enable',
      	                        cssClass: 'btn-sm btn-success',
      	                        
      	                          autospin: true,
      	                          action: function(dialogRef){
      	                        	 $.ajax({
      	    	                        dataType: 'html',
      	    	                        type: "POST",
      	    	                        url: "php/miscDeductions.handle.php",
      	    	                        cache: false,
      	    	                        data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>',deducId:e_d_id },
	       		                         beforeSend:function(){
      	    	                         	$('.dedu_enable_form').button('loading'); 
      	    	                          },
      	    	                          complete:function(){
      	    	                         	 $('.dedu_enable_form').button('reset');
      	    	                          },
      	    	                        success: function (data) {
      	    	                        	data = JSON.parse(data);
      	    	                            if (data[0] == "success") {
      	    	                            	deduoTable.fnDraw();
      	    	                              $('.close').click();
  	                                    	  BootstrapDialog.alert('Misc Deduction Disabled Successfully');
      	    	                          
      	    	                            }
      	    	                            else
      	    	                                alert("Error on query");
      	    	                        }
      	    	                    });
      	                                  		                            
      	                          }
      	                      }, {
      	                          label: 'Close',
      	                        cssClass: 'btn-sm btn-danger',
      	                        
      	                          action: function(dialogRef){
      	                              dialogRef.close();
      	                          }
      	                      }]
      	                  });
    	            });

    	              $(document).on('click', "#deduction_table a.deduction_view", function (e) {
      	                e.preventDefault();
      	                $("#d_payment_edit_form :input").attr("disabled", true);
    	                $("#d_e_payment_name_chosen,#d_e_payments_in_chosen,#d_e_e_payments_in_chosen,#d_e_e_name_chosen,#d_e_pay_category_chosen").prop("disabled", true);  
    	                $('#d_e_payment_name_chosen,#d_e_payments_in_chosen,#d_e_e_payments_in_chosen,#d_e_e_name_chosen,#d_e_pay_category_chosen').prop('disabled', true).trigger("chosen:updated");
    	                
      	                /* Get the row as a parent of the link that was clicked on */
      	                var nRow = $(this).parents('tr')[0];
      	                var aData = deduoTable.fnGetData(nRow);
      	                $('#d_payment_edit_form')[0].reset();
      	                $('#eremark').val(aData[6]);
      	                $('#e_d_payments_in').prop('selected', false).trigger('chosen:updated');
      	                $('#d_e_name').prop('selected', false).trigger('chosen:updated');
      	                $("input[name=d_e_payments_type][value=" + aData[8] + "]").click();
      	                $("input[name=d_e_payments_type][value=" + aData[8] + "]").prop('checked', true);
      	                var nRow = $(this).parents('tr')[0];
      	                var aData = deduoTable.fnGetData(nRow);
      	                $('#d_e_payment_id').val(aData[11]);
      	               if (aData[8] == "E") {
      	                    $.each(aData[12].split(","), function (i, e) {
      	                        $("#d_e_e_name option[value='" + e + "']").prop("selected", true);
      	                    });
      	                    $("#d_e_e_name").trigger("chosen:updated"); //for drop down
      	                } 
      	                $('#d_e_payment_name option[value="' + aData[1] + '"]').prop('selected', true);
      	                $("#d_e_payment_name").trigger("chosen:updated"); 


      	                var myString = aData[3].substr(aData[3].indexOf("|") + 1)
      	                if (myString !== 'P') {
      	                    $("#d_e_Lumsum").click();
      	                    $("input[name=cal][value='1']").prop('checked', true);
      	                    $('#d_e_payment_amount').val(aData[3]);
      	                 
      	                } else {
      	                    $("#d_e_Percentage").click();
      	                    $("input[name=cal][value='0']").prop('checked', true);
      	                    $.each(aData[9].split(","), function (i, e) {
      	                        $("#d_e_payments_in option[value='" + e + "']").prop("selected", true);
      	                    });
      	                    $("#d_e_payments_in").trigger("chosen:updated"); //for drop down

      	                }
      	                var e_payment_amount_s = aData[3].substr(0, aData[3].indexOf('|'));
      	                $('#d_e_payment_amount').val(e_payment_amount_s);

  						$('#d_e_applicable_form_db').val(aData[05]);
      	                $('#d_e_count').val(aData[11]);
      	           });

    	              $('#deductionAdd').on('click', function (e) {
    	            	   if($("#employeeSelected").chosen().val()!='' &&  $('#count').val()!='' && 
    	           	  	          $('#payment_amount').val()!='' &&  $('#payment_amount').val()!='' && $('#applicable_form_db').val()!=''){
    	           	  	  var data = $('#paymentAddForm').serializeArray();
    		  	          data.push({name: 'affectedIds', value: $("#employeeSelected").chosen().val()});
    		  	        $.ajax({
    		                  dataType: 'html',
    		                  type: "POST",
    		                  url: "php/miscDeductions.handle.php",
    		                  cache: false,
    		                  data: data,
    		                  beforeSend:function(){
    		                   	$('#deductionAdd').button('loading'); 
    		                    },
    		                   success: function (data) {
    		                      data1 = JSON.parse(data);
    		                      if (data1[0] == "success") {
    		                          $('#paymentAddForm')[0].reset();
    		                          $('#d_payments_in').prop('selected', false).trigger('chosen:updated');
    		                          deduoTable.fnDraw();
    		                          $('#showhideButton').click();
    		                          BootstrapDialog.alert('Misc Deduction Added successfully');
    		                      }
    		                      else
    		                          if (data1[0] == "error") {
    		                          	 BootstrapDialog.alert('Misc Deduction cant be Added');

    		                          }
    		                      $('#deductionAdd').button('reset');
    		                  }

    		              });
    	              }
    		  	      else{
    		  	    	$('#errorMsg').html('Enter All Fields');
    		  	    	  	  	  	          }
    	               });
    	          

    	            $(document).on('click', "#deduction_table a.deduction_edit", function (e) {
    	                e.preventDefault();
    	                $("#d_payment_edit_form :input").attr("disabled", false);
    	                //$("#d_e_e_name,#d_e_payment_name,#d_e_applicable_form_db,#d_e_count,#eremark,").prop("disabled", true);
    	                $('#d_e_payment_name_chosen,#d_e_payments_in_chosen,#d_e_e_payments_in_chosen,#d_e_e_name_chosen,#d_e_pay_category_chosen,#d_e_applicable_form_db,#d_e_count,#d_e_e_name,#d_e_payment_name,#d_e_Employee').prop('disabled', true);
      	                $('#d_e_payment_name_chosen,#d_e_payments_in_chosen,#d_e_e_payments_in_chosen,#d_e_e_name_chosen,#d_e_pay_category_chosen').prop('disabled', true).trigger("chosen:updated");
      	              
    	                /* Get the row as a parent of the link that was clicked on */
    	                var nRow = $(this).parents('tr')[0];
    	                var aData = deduoTable.fnGetData(nRow);
    	                $('#d_payment_edit_form')[0].reset();
    	                $('#e_d_payments_in').prop('selected', false).trigger('chosen:updated');
    	                $('#d_e_name').prop('selected', false).trigger('chosen:updated');
    	                $("input[name=d_e_payments_type][value=" + aData[8] + "]").click();
    	                $("input[name=d_e_payments_type][value=" + aData[8] + "]").prop('checked', true);
    	                var nRow = $(this).parents('tr')[0];
    	                var aData = deduoTable.fnGetData(nRow);
    	                $('#d_e_payment_id').val(aData[11]);
    	                $('#eremark').val(aData[6]);
    	                if (aData[8] == "E") {
    	                    $.each(aData[10].split(","), function (i, e) {
    	                        $("#d_e_e_name option[value='" + e + "']").prop("selected", true);
    	                    });
    	                    $("#d_e_e_name").trigger("chosen:updated"); //for drop down
    	                }
    	                $('#d_e_payment_name option[value="' + aData[1] + '"]').prop('selected', true);
    	                $("#d_e_payment_name").trigger("chosen:updated"); 
    	               
    	                var myString = aData[3].substr(aData[3].indexOf("|") + 1)
    	                if (myString !== 'P') {
    	                    $("#d_e_Lumsum").click();
    	                    $("input[name=cal][value='1']").prop('checked', true);
    	                    $('#d_e_payment_amount').val(aData[2]);
    	                 
    	                } else {
    	                    $("#d_e_Percentage").click();
    	                    $("input[name=cal][value='0']").prop('checked', true);
    	                    $.each(aData[09].split(","), function (i, e) {
    	                        $("#d_e_payments_in option[value='" + e + "']").prop("selected", true);
    	                    });
    	                    $("#d_e_payments_in").trigger("chosen:updated"); //for drop down

    	                }
    	                var e_payment_amount_s = aData[3].substr(0, aData[3].indexOf('|'));
    	                $('#d_e_payment_amount').val(e_payment_amount_s);

						$('#d_e_applicable_form_db').val(aData[05]);
    	                $('#d_e_count').val(aData[12]);
    	                
    	            });

    	        }

    	    };

    	} ();
          jQuery(document).ready(function () {
        	  dec_EditableTable.init();
                   $(".d_Lumsum_i").hide();
              $(".d_e_Lumsum_i").hide();

              $(".Lumsum_i").hide();
    	      $(".e_Lumsum_i").hide();
    	  $('.Percentage_i').show();
    	  
              $('#d_e_Lumsum').on('click', function (event) {
                  $(".d_e_Lumsum_i").hide();
                  $(".d_e_Percentage_i").show();
              });
              $('#d_e_Percentage').on('click', function (event) {
                  $(".d_e_Lumsum_i").show();
                  $(".d_e_Percentage_i").hide();
              });
              $('#d_e_e_name,#d_e_payment_name,#d_e_payments_in').chosen();	
          });

          $('#d_e_applicable_form_db').datetimepicker({
        	  format: 'DD/MM/YYYY',
        	  maxDate:false
         });
      </script>


</body>
</html>
