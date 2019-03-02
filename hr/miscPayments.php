<?php
include_once (dirname ( __DIR__ ) . "/include/config.php");
?>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
	tabindex="-1" id="payment_edit" class="modal fade">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close"
					type="button">×</button>
				<h4 class="modal-title">Edit Payment</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" method="post"
					id="payment_edit_form">

					<div class="panel-body">
						<div class="col-lg-12 " id="inline_content">
							<label class="col-lg-2 control-label"> Payment For </label>
							<div class="col-lg-12">
								<label for="e_Employee" class="col-lg-2 control-label"> <input
									name="e_payments_type" id="e_Employee" type="radio" value="E">
									Employee
								</label>
							</div>

						</div>

					</div>
					<div class="row col-lg-12"
						style="border-top: 1px solid rgb(229, 229, 229);">
						<br> <input type="hidden" name="payId" id="e_payment_id"> <input
							type="hidden" name="pId" id="ePaymentType"> <input type="hidden"
							name="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
						<div class="form-group col-lg-12" id="e_e_hide">
							<label class="col-lg-2 control-label" for="pf_limit">Employee
								Name</label>

							<div class="col-lg-10">
								<select class="form-control" id="e_e_name" name="affectedIds[]"
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
						

					
					<!--  div class="row col-lg-12" -->
						<div class="form-group col-lg-6">
							<label class="col-lg-4 control-label">Payment Name</label>
							<div class="col-lg-7">
								<select class="form-control" id="epayment_name" name="pCategory">
                                 <?php
																																	$stmt = mysqli_prepare ( $conn, "SELECT pay_structure_id, display_name FROM company_pay_structure WHERE display_flag = 1 && type='MP' " );
																																	$result = mysqli_stmt_execute ( $stmt );
																																	mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name );
																																	while ( mysqli_stmt_fetch ( $stmt ) ) {
																																		echo "<option value='" . $pay_structure_id . "'>" . $display_name . "</option>";
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
										id="e_applicable_form_db" required />
								</div>
							</div>
						</div>


					</div>
					<div class="row col-lg-12">
						<div class="form-group col-lg-6">
							<label class="col-lg-4 control-label">Type</label>
							<div class="col-lg-7 ">
								<label for="e_Lumsum" class="col-lg-6 control-label"> <input
									name="cal" id="e_Lumsum" type="radio" value="1" checked>lump
									sum
								</label> <label for="e_Percentage"
									class="col-lg-6 control-label"> <input name="cal"
									id="e_Percentage" type="radio" value="0"> Percentage
								</label>
							</div>

						</div>

						<div class="form-group col-lg-6">
							<label class="col-lg-5 control-label">No of Repetition</label>
							<div class="col-lg-7 ">
								<input type="text" class="form-control" name="count"
									id="e_count" value="" />
							</div>
						</div>



					</div>
					<div class="row col-lg-12">

						<div class="form-group col-lg-6">
							<label class="col-lg-4 control-label e_Percentage_i">Amount</label>
							<label class="col-lg-4 control-label e_Lumsum_i">percentage</label>
							<div class="col-lg-7  input-group">

								<span class="input-group-addon e_Percentage_i"><i
									class="fa fa-rupee"></i></span> <span
									class="input-group-addon e_Lumsum_i">%</span>

								<div class="iconic-input right ">
									<i class="left"
										style="font-size: 12px; margin: 9px 11px 6px 0px;"></i> <input
										class="form-control" name="Amount" id="e_payment_amount"
										type="text">
								</div>
							</div>
						</div>


						


						<div class="form-group col-lg-6">
							<label class="col-lg-5 control-label">Remarks</label>
							<div class="col-lg-7 ">
								<textarea class="form-control" rows="3" cols="15" id="eremarks"
									name="remarks"></textarea>
							</div>
						</div>

					</div>
					
					<div class="row col-lg-12">
					<div class="form-group col-lg-6 e_Lumsum_i">
							<label class="col-lg-4 control-label">Salary Head</label>
							<div class="col-lg-8">
								<select class="form-control" id="e_payments_in" name="In[]"	multiple>
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


					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-danger"
							data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-sm btn-success save">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<section class="panel">

	<header class="panel-heading displayHide"
		style="border-color: #fff; margin-top: -3%;">
		<div class="btn-group pull-right">
			<button id="showhide_pay" type="button" class="btn btn-sm btn-info"
				style="margin-top: -5px;">
				<i class="fa fa-plus"></i> Payment Add
			</button>
		</div>
	</header>
	<div class="panel-body">
		<div class="adv-table editable-table">
			<section id="flip-scroll">
				<table class="table table-striped table-h over table-bordered cf"
					id="payment_table">
					<thead class="cf">
						<tr>
							<th>PayName</th>
							<th>Payment For</th>
							<th>Amount</th>
							<th>Effect From</th>
							<th>Effect Upto</th>
							<th style="width:200px">Remarks</th>
							<th style="width:100px">Action</th>
						</tr>
					</thead>
				</table>
			</section>
		</div>
	</div>
</section>

<!-- page end-->
<!-- END JAVASCRIPTS -->
<script type="text/javascript">
      var EditableTable = function () {
  	    return {

  	        //main function to initiate the module
  	        init: function (allowData) {

  	            var nCloneTh = document.createElement('th');
  	            var nCloneTd = document.createElement('td');
  	            nCloneTd.innerHTML = '<img class="openClose" src="../css/images/details_open.png">';
  	            nCloneTd.className = "center";
  	            $('#payment_table thead tr').each(function () {
  	                this.insertBefore(nCloneTh, this.childNodes[0]);
  	            });

  	            $('#payment_table tbody tr').each(function () {
  	                this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
  	            });

  	            /*
  	            * Initialse DataTables, with no sorting on the 'details' column
  	            */
  	            var oTable = $('#payment_table').dataTable({
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
  	                "sAjaxSource": "php/miscPayment-view.php",
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
  	                "aoColumns": [
  	                       {  "bSortable": false },
  	                       { "sName": "payment_name","bSortable": false  },
  	                        { "sName": "payment_amount","bSortable": false  },
  	                          { "sName": "effects_from","bSortable": false  },
  	                         { "sName": "effects_upto","bSortable": false  },
  	                          { "sName": "pay_category","bSortable": false  },
  	                           { "bSortable": false },
								  ], "aoColumnDefs": [{ "mRender": function 
  	                        (data, type, row) {
			  	                        return row[13];
			  	                        //console.log(row);
  	                         },
  	                             "aTargets": [1]

  	                         },{ "mRender": function 
       	                        (data, type, row) {
   	                        	var dataStr=data.split("|");
   	                        	 if (dataStr[1]=='A') {
   	                                 return '<i class="fa fa-rupee"></i> ' +dataStr[0];
   	                             } else {
       	                             return dataStr[0]+' %';}	                             
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
               	                        return '<a class="disable" href="" title="Disable"><button class="btn btn-success  btn-xs" style="padding: 1px 6px;"><i class="fa fa-unlock"></i></button></a>&nbsp<a href="#payment_edit"  title="Edit" class="edit" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a>';
           	                        }else{
           	                        	return '<a class="enable"   title="Enable" href=""><button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>&nbsp;<a   title="View" href="#payment_edit" class="view" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button></a>';
                   	                }
     	                        }else{//view payment and  edit approve
     	                        	  if(row[7]==1){
               	                        return '<a class="disable" href="" title="Disable"><button class="btn btn-success  btn-xs" style="padding: 1px 6px;"><i class="fa fa-unlock"></i></button></a>&nbsp<a href="#payment_edit"  title="Edit" class="edit" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a>';
           	                        }else{
           	                        	return '<a class="enable"   title="Enable" href=""><button class="btn btn-danger  btn-xs" style="padding: 1px 6px;"><i class="fa fa-lock"></i></button></a>&nbsp;<a   title="View" href="#payment_edit" class="view" data-toggle="modal"><button class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button></a>';
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
  	            $('#payment_table_wrapper .dataTables_filter').html('<div class="input-group">\
  	                                              <input class="form-control medium" id="searchInput" type="text">\
  	                                              <span class="input-group-btn">\
  	                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
  	                                              </span>\
  	                                              <span class="input-group-btn">\
  	                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
  	                                              </span>\
  	                                          </div>');
  	            $('#payment_table_processing').css('text-align', 'center');
  	            //$('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
  	            $('#payment_table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
  	            $('#searchInput').on('keyup', function (e) {
  	                if (e.keyCode == 13) {
  	                    oTable.fnFilter($(this).val());
  	                } else if (e.keyCode == 27) {
  	                    $(this).parent().parent().find('input').val("");
  	                    oTable.fnFilter("");
  	                }
  	            });

  	            $(document).on("click", "#searchFilter", function () {
  	                oTable.fnFilter($(this).parent().parent().find('input').val());
  	            });
  	            $(document).on("click", "#searchClear", function () {
  	                $(this).parent().parent().find('input').val("");
  	                oTable.fnFilter("");
  	            });

  	            /* Add event listener for opening and closing details
  	            * Note that the indicator for showing which row is open is not controlled by DataTables,
  	            * rather it is done here
  	            */
  	            $(document).on("click", "#payment_table tbody td img.openClose", function (e) {
  	                //$("#payment_table tbody td img.openClose").click(function (e) { 
  	                e.preventDefault();
  	                var nTr = $(this).parents('tr')[0];
  	                if (oTable.fnIsOpen(nTr)) {

  	                    /* This row is already open - close it */
  	                    this.src = "../css/images/details_open.png";
  	                    this.class = 'openClose';
  	                    oTable.fnClose(nTr);
  	                }
  	                else {
  	                    /* Open this row */
  	                     
  	                    this.src = "../css/images/details_close.png";
  	                    this.class = 'openClose';
  	                    
  	              var nRow = $(this).parents('tr')[0];
  	              var data = oTable.fnGetData(nRow);
	              var p_id = data[10];
	              var data1;
  	               $.ajax({
	                    dataType: 'html',
	                    type: "POST",
	                    url: "php/miscAllowances.handle.php",
	                    cache: false,
	                    data:{ act: '<?php echo base64_encode($_SESSION['company_id']."!getgroupEmployee");?>',payId:p_id },
	                    success: function (data) {
  	                        data1 = JSON.parse(data);
  	                      var tt = oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr,data1[2]), 'details');
	                    }
  		               });

  	            		 

  	                }
  	            });



  	            function fnFormatDetails(oTable, nTr,groupPayDetails) {
					var aData = oTable.fnGetData(nTr);
					var empstr = "";
  	              	var count = groupPayDetails.length;
	  	            for(i=0;i<count;i++){
  	                	empstr += ","+groupPayDetails[i]["employee"];
  	                }
  	              	empstr = empstr.substring(1);
  	                var sOut = '<div class="col-lg-12"><table width="100%" height="17%" border="0" cellspacing="0" cellpadding="5">';
  	                sOut += '<tr valign="top"><td width="19%" colspan:"3"><b>Payment In</b></td><td>' + aData[09] + '</td></tr>';
  	              	sOut += '<tr valign="top"><td width="19%" colspan:"3"><b>Affected Ids</b></td><td>' + empstr + '</td></tr>';
  	                sOut += '<tr valign="top"><td width="19%" colspan:"3"><b>Performed on</b></td><td>' + aData[14] + '</td></tr></div>';
  	                return sOut;
  	            }


  	          /*  $('#addendanceSubmit').on('click', function (e) {
  	  	          var data = $('#paymentAddForm').serializeArray();
  	  	          data.push({name: 'affectedIds', value: $("#employeeSelected").chosen().val()});
  	  	           e.preventDefault();
  	                $.ajax({
  	                    dataType: 'html',
  	                    type: "POST",
  	                    url: "php/miscAllowances.handle.php",
  	                    cache: false,
  	                    data:data,
  	                    beforeSend:function(){
  	                     	$('#addendanceSubmit').button('loading'); 
  	                      },
  	                     success: function (data) {
  	                        data1 = JSON.parse(data);
  	                        if (data1[0] == "success") {
  	                            $('#paymentAddForm')[0].reset();
  	                            $('#ee_payments_in').prop('selected', false).trigger('chosen:updated');
  	                            oTable.fnDraw();
  	                            $('#showhideButton').click();
  	                            BootstrapDialog.alert('Misc payment Added successfully');
  	                        }
  	                        else
  	                            if (data1[0] == "error") {
  	                            	BootstrapDialog.alert('Misc payment Doesnt Added');
  	                         }
  	                      $('#addendanceSubmit').button('reset');
   	                     
  	                    }

  	                });

  	            });
*/
  	            $(document).on('click', ".save", function (e) {
  	            	 var data = $('#payment_edit_form').serializeArray();
  	  	  	          data.push({name: 'affectedIds', value: $("#e_e_name").chosen().val()});
  	                e.preventDefault();
  	                $.ajax({
  	                    dataType: 'html',
  	                    type: "POST",
  	                    url: "php/miscAllowances.handle.php",
	                    cache: false,
  	                    data: data,
  	                    beforeSend:function(){
  	                     	$('.save').button('loading'); 
  	                      },
  	                      complete:function(){
  	                     	 $('.save').button('reset');
  	                      },
  	                    success: function (data) {
  	                        data1 = JSON.parse(data);
  	                        if (data1[0] == "success") {
  	                            $('#payment_edit_form')[0].reset();
  	                            $('#ee_payments_in').prop('selected', false).trigger('chosen:updated');
  	                            $('#e_name').prop('selected', false).trigger('chosen:updated');
  	                            $('#payment_add_form').hide();
  	                            oTable.fnDraw();
  	                            $('.close').click();
  	                            BootstrapDialog.alert('Misc payment Updated successfully');
  	                          }
  	                        else
  	                            if (data1[0] == "error") {
  	                            	 BootstrapDialog.alert('Misc payment Cant Updatted');
  	                            }
  	                    }

  	                });

  	            });
  	            //diable code
  	            $(document).on('click', "#payment_table a.disable", function (e) {
  	                e.preventDefault();
  	               var nRow = $(this).parents('tr')[0];
  	                var data = oTable.fnGetData(nRow);
  	                var e_id = data[10];
  	                var p_name = data[1];
  	                BootstrapDialog.show({
  	  	                title:'Confirmation',
  	                      message: 'Are Sure you want to Disable <strong>'+ p_name +'</strong>',
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
	  	                              url: "php/miscAllowances.handle.php",
	  	        	                  cache: false,
	  	        	                  data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', payId:e_id },
         		                      beforeSend:function(){
  	                                   	$('.pay_disable_form').button('loading'); 
  	                                    },
  	                                    complete:function(){
  	                                   	 $('.pay_disable_form').button('reset');
  	                                    },
  	                                  success: function (data) {
  	                                      data = JSON.parse(data);
             		                        if (data[0] == "success") {
  	  	                                      oTable.fnDraw();
  	                                    	  $('.close').click();
  	                                    	  BootstrapDialog.alert('Misc payment Disable Successfully');
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
  	            $(document).on('click', "#payment_table a.enable", function (e) {
  	                e.preventDefault();
  	                var nRow = $(this).parents('tr')[0];
  	                var data = oTable.fnGetData(nRow);
  	                var e_p_id = data[10];
  	                var e_p_name = data[1];
  	                BootstrapDialog.show({
  	  	                title:'Confirmation',
  	                      message: 'Are Sure you want to Enable <strong>'+ e_p_name +'</strong>',
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
  	                                  url: "php/miscAllowances.handle.php",
  	        	                      cache: false,
  	        	                      data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>',payId: e_p_id },
       		                          beforeSend:function(){
  	                                   	$('.pay_enable_form').button('loading'); 
  	                                    },
  	                                    complete:function(){
  	                                   	 $('.pay_enable_form').button('reset');
  	                                    },
  	                                  success: function (data) {
  	                                	data = JSON.parse(data);
  	                                      if (data[0] == "success") {
  	                                    	  oTable.fnDraw();
  	                                    	  $('.close').click();
  	                                          BootstrapDialog.alert('Misc payment Enabled Successfully');
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

  	          $(document).on('click', "#payment_table a.view", function (e) {
	                e.preventDefault();
	                /* Get the row as a parent of the link that was clicked on */
	                var nRow = $(this).parents('tr')[0];
	                var aData = oTable.fnGetData(nRow);
	                //$('#payment_edit_form')[0].reset();
	                 $('#payments_in').prop('selected', false).trigger('chosen:updated');
	                $('#e_name').prop('selected', false).trigger('chosen:updated');
	               $("input[name=e_payments_type][value=" + aData[8] + "]").click();
	                $("input[name=e_payments_type][value=" + aData[8] + "]").prop('checked', true);

	                var nRow = $(this).parents('tr')[0];
	                var aData = oTable.fnGetData(nRow);
	                $('#e_payment_id').val(aData[10]);
	                $('#eremarks').val(aData[6]);
	                $("#epayment_name option[value='" + aData[1] + "']").prop("selected", true);
	                $("#epayment_name").trigger("chosen:updated");
	                if (aData[8] == "E") {
	                    $.each(aData[12].split(","), function (i, e) {
	                        $("#e_e_name option[value='" + e + "']").prop("selected", true);
	                    });
	                    $("#e_e_name").trigger("chosen:updated"); //for drop down
	                } 
	                	                
	                var myString = aData[3].substr(aData[3].indexOf("|") + 1)
	                if (myString !== 'P') {
	                    $("#e_Lumsum").click();
	                    $("input[name=cal][value='1']").prop('checked', true);
	                    $('#e_payment_amount').val(aData[3]);
	                } else {
	                    $("#e_Percentage").click();
	                    $("input[name=cal][value='0']").prop('checked', true);
	                         $.each(aData[9].split(","), function (i, e) {
	                        $("#e_payments_in option[value='" + e + "']").prop("selected", true);
	                    });
	                    $("#e_payments_in").trigger("chosen:updated"); //for drop down

	                }
	                var e_payment_amount_s = aData[3].substr(0, aData[3].indexOf('|'));
	                $('#e_payment_amount').val(e_payment_amount_s);

	                $.each(aData[8].split(","), function (i, e) {
	                    $("#e_payments_in option[value='" + e + "']").prop("selected", true);
	                });
	                $("#e_payments_in").trigger("chosen:updated"); //for drop down

	                $('#e_applicable_form_db').val(aData[05]);
	                $('#e_count').val(aData[11]);
	                $("#payment_edit_form :input").attr("disabled", true);
	                $("#epayment_name, #e_payments_in,#e_e_payments_in, #e_e_name,#e_pay_category").prop("disabled", true);  
	                $('#epayment_name, #e_payments_in,#e_e_payments_in, #e_e_name,#e_pay_category').prop('disabled', true).trigger("chosen:updated");
	            });

  	            $(document).on('click', "#payment_table a.edit", function (e) {
  	                e.preventDefault();
  	                $("#payment_edit_form :input").attr("disabled", false);
	                $("#epayment_name, #e_name,#e_e_payments_in, #e_e_name, #e_pay_category,#e_applicable_form_db,#e_count,#e_Employee").prop("disabled", true);  
	                $('#epayment_name, #e_name,#e_e_payments_in, #e_e_name, #e_pay_category').prop('disabled', true).trigger("chosen:updated");
	              /* Get the row as a parent of the link that was clicked on */
  	                var nRow = $(this).parents('tr')[0];
  	                var aData = oTable.fnGetData(nRow);
  	                $('#payment_edit_form')[0].reset();
  	                $('#payments_in').prop('selected', false).trigger('chosen:updated');
  	                $('#e_name').prop('selected', false).trigger('chosen:updated');
  	                $("input[name=e_payments_type][value=" + aData[8] + "]").click();
  	                $("input[name=e_payments_type][value=" + aData[8] + "]").prop('checked', true);

  	                var nRow = $(this).parents('tr')[0];
  	                var aData = oTable.fnGetData(nRow);
  	                //console.log(aData[2].split(" "));
  	                $('#e_payment_id').val(aData[10]);
  	                $("#epayment_name option[value='" + aData[1] + "']").prop("selected", true);
  	                $("#epayment_name").trigger("chosen:updated");
  	                $("#e_e_name option[value='" + aData[2] + "']").prop("selected", true);
	                $("#e_e_name").trigger("chosen:updated");
  	                $('#eremarks').val(aData[6]);
  	                if (aData[8] == "E") {
  	                    $.each(aData[12].split(","), function (i, e) {
  	                        $("#e_e_name option[value='" + e + "']").prop("selected", true);
  	                    });
  	                    $("#e_e_name").trigger("chosen:updated"); //for drop down
  	                }   	                
  	                
  	                var myString = aData[3].substr(aData[3].indexOf("|") + 1)
  	                if (myString !== 'P') {
  	                    $("#e_Lumsum").click();
  	                    $("input[name=cal][value='1']").prop('checked', true);
  	                    $('#e_payment_amount').val(aData[2]);
  	                } else {
  	                    $("#e_Percentage").click();
  	                    $("input[name=cal][value='0']").prop('checked', true);
  	                         $.each(aData[9].split(","), function (i, e) {
  	                        $("#e_payments_in option[value='" + e + "']").prop("selected", true);
  	                    });
  	                    $("#e_payments_in").trigger("chosen:updated"); //for drop down

  	                }
  	                var e_payment_amount_s = aData[3].substr(0, aData[3].indexOf('|'));
  	                $('#e_payment_amount').val(e_payment_amount_s);

  	                $.each(aData[8].split(","), function (i, e) {
  	                    $("#e_payments_in option[value='" + e + "']").prop("selected", true);
  	                });
  	                $("#e_payments_in").trigger("chosen:updated"); //for drop down

  	                $('#e_applicable_form_db').val(aData[05]);
  	                $('#e_count').val(aData[11]);
  	               

  	            });

  	          $('#miscpayAdd').on('click', function (e) {
  	  	          if($("#employeeSelected").chosen().val()!='' &&  $('#count').val()!='' && 
  	  	          $('#payment_amount').val()!='' &&  $('#payment_amount').val()!='' && $('#applicable_form_db').val()!=''){
  	  	        	$('#errorMsg').html('');
                  var data = $('#paymentAddForm').serializeArray();
	  	          data.push({name: 'affectedIds', value: $("#employeeSelected").chosen().val()});
	  	           e.preventDefault();
	                $.ajax({
	                    dataType: 'html',
	                    type: "POST",
	                    url: "php/miscAllowances.handle.php",
	                    cache: false,
	                    data:data,
	                    beforeSend:function(){
	                     	$('#miscpayAdd').button('loading'); 
	                      },
	                     success: function (data) {
	                        data1 = JSON.parse(data);
	                        if (data1[0] == "success") {
	                            $('#paymentAddForm')[0].reset();
	                            $('#ee_payments_in').prop('selected', false).trigger('chosen:updated');
	                            oTable.fnDraw();
	                            $('#showhideButton').click();
	                            BootstrapDialog.alert('Misc payment Added successfully');
	                        }
	                        else
	                            if (data1[0] == "error") {
	                            	BootstrapDialog.alert('Misc payment Doesnt Added');
	                         }
	                      $('#miscpayAdd').button('reset');
	                     
	                    }

	                });
  	  	          }else{
$('#errorMsg').html('Enter All Fields');
  	  	  	          }
           
	            });

  	        }

  	    };

  	} ();
  	 $('#e_applicable_form_db').datetimepicker({
    	  format: 'DD/MM/YYYY',
    	  maxDate:false
     });
          jQuery(document).ready(function () {
        	   EditableTable.init();
        	   $(".Lumsum_i").hide();
        	      $(".e_Lumsum_i").hide();
        	  $('.Percentage_i').show();
        	      $('#e_Lumsum').on('click', function (event) {
        	          $(".e_Lumsum_i").hide();
        	          $(".e_Percentage_i").show();
        	      });
        	      $('#e_Percentage').on('click', function (event) {
        	          $(".e_Lumsum_i").show();
        	          $(".e_Percentage_i").hide();
        	      });
        	      $('#e_e_name,#epayment_name,#e_payments_in,#payment_name').chosen();	
          });

        
      </script>


</body>
</html>
