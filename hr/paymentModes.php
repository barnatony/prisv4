<?php

include_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
?>
<div class="btn-group pull-right">
	<button id="payment_showhide" type="button" class="btn btn-sm btn-info"
		style="margin-top: -65%;">
		<i class="fa fa-plus"></i> Add
	</button>
</div>
<section class="panel">
	<div class="panel-body">

		<div class="col-lg-12" id="add-paymentMode">
			<form class="form-horizontal" role="form" method="post"
				id="paymentModeAddForm">
				<input type="hidden" name="act"
					value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
				<div class="col-lg-12" id="add-paymentMode">
					<div class="panel-body">

						<div class="form-group">
							<label for="dname" class="col-lg-3 col-sm-3 control-label">Name</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" id="name" name="name"
									maxlength="20" placeholder="" required
									onkeypress="return onlyAlphabets(event,this);">
							</div>
						</div>
						<div class="form-group">
							<label for="dname" class="col-lg-3 col-sm-3 control-label">Bank
								Name</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" id="bName" name="bName"
									maxlength="20" placeholder="" required
									onkeypress="return onlyAlphabets(event,this);">
							</div>
						</div>
						<div class="form-group">
							<label for="dname" class="col-lg-3 col-sm-3 control-label">Account
								No</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" id="acNo" name="acNo"
									placeholder="" maxlength="20" required>
							</div>
						</div>
						<div class="form-group">
							<label for="dname" class="col-lg-3 col-sm-3 control-label">Branch
								Name</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" id="branch_name"
									maxlength="20" name="branch_name" placeholder="" required
									onkeypress="return onlyAlphabets(event,this);">
							</div>
						</div>
						<div class="form-group">
							<label for="dname" class="col-lg-3 col-sm-3 control-label">IFSC</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" id="ifsc" name="ifsc"
									maxlength="20" placeholder="" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 col-sm-3 control-label">Account Type</label>
							<div class="col-lg-5">
								<select class="form-control" id="account_type"
									name="account_type">
									<option value="">Select Account Type</option>
									<option value="Current">Current</option>
									<option value="Cash Credit">Cash Credit</option>
									<option value="Cash">Cash</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-3 col-lg-5" align="right">
								<span id="pay-error" class="help-block pull-left text-danger"
									style="display: none;"> Enter All Required Fields</span>
								<button type="submit" class="btn btn-sm btn-success"
									id="payment_mobe_add">Add</button>
								<button type="button" class="btn btn-sm btn-danger" id="cancel">Cancel</button>
							</div>
						</div>
					</div>
				</div>

			</form>
		</div>

		<div class="space15"></div>
		<div class="adv-table editable-table">
			<section id="flip-scroll">
				<table class="table table-striped table-hover table-bordered cf"
					id="payment_editable-sample">
					<thead class="cf">
						<tr>
							<th>Mode ID</th>
							<th>Mode Name</th>
							<th>Account Type</th>
							<th>Bank Name</th>
							<th>Account No</th>
							<th>Branch Name</th>
							<th>IFSC Code</th>
							<th>Actions</th>
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

<!-- END JAVASCRIPTS -->
.

<script type="text/javascript">
          jQuery(document).ready(function() {

        	  var payment_EditableTable = function () {

        	      return {

        	          //main function to initiate the module
        	          init: function () {
        	              function restoreRow(oTable, nRow) {
        	                  var aData = oTable.fnGetData(nRow);
        	                  var jqTds = $('>td', nRow);
        	                  for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
        	                      oTable.fnUpdate(aData[i], nRow, i, false);
        	                  }
        	                  oTable.fnDraw();
        	              }

        	              function editRow(oTable, nRow) {
        	                  var aData = oTable.fnGetData(nRow);
        	                  var jqTds = $('>td', nRow);
        	                  jqTds[0].innerHTML = '<input type="text" class="form-control small"  maxlength="20"  required value="' + aData[1] + '">';
        	                  jqTds[1].innerHTML = '<select class="form-control  small" ><option value="' + aData[2] + '">' + aData[2] + '</option><option value="Current">Current</option><option value="Cash Credit">Cash Credit</option><option value="Cash">Cash</option></select>';
        	                  jqTds[2].innerHTML = '<input type="text" class="form-control small" maxlength="20"  required value="' + aData[3] + '">';
        	                  jqTds[3].innerHTML = '<input type="text" class="form-control small" maxlength="20"  required value="' + aData[4] + '">';
        	                  jqTds[4].innerHTML = '<input type="text" class="form-control small" maxlength="20`"   required value="' + aData[5] + '">';
        	                  jqTds[5].innerHTML = '<input type="text" class="form-control small"  maxlength="20"  required value="' + aData[6] + '">';
        	                  jqTds[6].innerHTML = '<a class="pay_edit" href="" data-actions="Save"  title="Save" ><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>&nbsp;<a class="pay_cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs" ><i class="fa fa-times"></i></button></a>';
        	                 }

        	              function saveRow(oTable, nRow) {
        	                  var jqInputs = $('input,select', nRow);
        	                  oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
        	                  oTable.fnUpdate(jqInputs[5].value, nRow, 6, false);
        	                  oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
        	                  oTable.fnUpdate(jqInputs[2].value, nRow, 3, false);
        	                  oTable.fnUpdate(jqInputs[3].value, nRow, 4, false);
        	                  oTable.fnUpdate(jqInputs[4].value, nRow, 5, false);
        	                  //  oTable.fnUpdate(jqInputs[6].value, nRow, 7, false);

        	                  oTable.fnUpdate('<a class="pay_edit" href="" data-actions="Save"  title="Save"><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>', nRow, 7, false);
        	              }




        	              var oTable = $('#payment_editable-sample').dataTable({
        	                  "aLengthMenu": [
        	                      [5, 15, 20, -1],
        	                      [5, 15, 20, "All"] // change per page values here
        	                  ],

        	                  // set the initial value
        	                  "iDisplayLength": 5,
        	                  "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

        	                  "bProcessing": true,
        	                  "bServerSide": true,
        	                  "sAjaxSource": "php/paymentModes-view.php",
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
{ "sName": 'payment_mode_id' },
        	                          { "sName": 'payment_mode_name' },
        	                           { "sName": 'account_type' },
        	  						{ "sName": 'bank_name' },
        	                          { "sName": 'bank_ac_no' },
        	                          { "sName": 'bank_branch' },
        	                          { "sName": 'bank_ifsc' },
        	                          { "bSortable": false }
        	                      ],"aoColumnDefs": [
        	                                            {"bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }   
        	                                            
        	                                            ],
        	                  "oTableTools": {
        	                      "aButtons": [
        	                  {
        	                      "sExtends": "pdf",
        	                      "mColumns": [0, 1, 2, 3, 4, 5, 6, 7],
        	                      "sPdfOrientation": "landscape",
        	                      "sPdfMessage": "Payment Mode Details"
        	                  },
        	                  {
        	                      "sExtends": "xls",
        	                      "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
        	                  }
        	               ],
        	                      "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

        	                  }

        	              });


        	              $('#payment_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
        	                                                <input class="form-control medium" id="payment_searchInput" type="text">\
        	                                                <span class="input-group-btn">\
        	                                                  <button class="btn btn-white" id="payment_searchFilter" type="button">Search</button>\
        	                                                </span>\
        	                                                <span class="input-group-btn">\
        	                                                  <button class="btn btn-white" id="payment_searchClear" type="button">Clear</button>\
        	                                                </span>\
        	                                            </div>');
        	              $('#payment_editable-sample_processing').css('text-align', 'center');
        	              //jQuery('#payment_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
        	              $('#payment_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
        	              $('#payment_searchInput').on('keyup', function (e) {
        	                  if (e.keyCode == 13) {
        	                      oTable.fnFilter($(this).val());
        	                  } else if (e.keyCode == 27) {
        	                      $(this).parent().parent().find('input').val("");
        	                      oTable.fnFilter("");
        	                  }
        	              });

        	              $(document).on('click', "#payment_searchFilter", function () {
        	                  oTable.fnFilter($(this).parent().parent().find('input').val());
        	              });
        	              $(document).on('click', "#payment_searchClear", function () {
        	                  $(this).parent().parent().find('input').val("");
        	                  oTable.fnFilter("");
        	              });

        	              var nEditing = null;
                           // for adding new designation
        	              $(document).on('click', "#payment_mobe_add", function (e) {
        	                  e.preventDefault();
        	                  if (($('#account_type').val() !='Cash' || $("#name").val() == '') && ($("#bName").val() == '' || $("#acNo").val() == '' || $("#branch_name").val() == '' || $("#ifsc").val() == '' ))
        	                  { 
        	                  $('#pay-error').show();
        	                  }
        	                  else {
        	                      $.ajax({
        	                          dataType: 'html',
        	                          type: "POST",
        	                          url: "php/paymentMode.handle.php",
        	                          cache: false,
        	                          data: $('#paymentModeAddForm').serialize(),
        	                          beforeSend:function(){
        	                           	$('#payment_mobe_add').button('loading'); 
        	                            },
        	                            complete:function(){
        	                           	 $('#payment_mobe_add').button('reset');
        	                            },
        	                          success: function (data) {
        	                              data1 = JSON.parse(data);
        	                              if (data1[0] == "success") {
        	                                  $('.close').click();
        	                                  jQuery('#add-paymentMode').toggle('hide');
        	                                  $('#paymentModeAddForm')[0].reset();
        	                                   $('.p_model_msg1').click();
        	                                  // console.log(data1);
        	                                   BootstrapDialog.alert("Payment-Mode Added sucessfully");
        	                                  oTable.fnDraw();
        	                                  $("#pay-error").hide();
        	                              }
        	                              else
        	                                  if (data1[0] == "error") {
        	                                	  BootstrapDialog.alert("Payment-Mode Added Failed");
        	                                  }
        	                          }

        	                      });
        	                  }
        	              });


        	              
        	              $(document).on('click', "#payment_editable-sample  .pay_cancel", function (e) {
          	                  e.preventDefault();
          	                  if ($(this).attr("data-mode") == "new") {
          	                      var nRow = $(this).parents('tr')[0];
          	                    oTable.fnDeleteRow(nRow);
          	                  } else {
          	                      restoreRow(oTable, nEditing);
          	                      nEditing = null;
          	                  }
          	              });
        	              


        	              $(document).on('click', "#payment_editable-sample a.pay_enable", function (e) {
        	                  e.preventDefault();
        	                  var nRow = $(this).parents('tr')[0];
        	                  var data = oTable.fnGetData(nRow);
        	                  var e_pay_id = data[0];
        	                  var e_pay_name = data[1];
        	                  BootstrapDialog.show({
          		                title:'Confirmation',
      		                    message: 'Are Sure you want to Enable <strong>'+ e_pay_name +'<strong>',
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
      	        	                          url: "php/paymentMode.handle.php",
      	        	                          cache: false,
      	        	                          data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>', pId:e_pay_id },
      	        	                          complete:function(){
       	        		                    	 dialogRef.close();
       	        		                      },
      	        	                          success: function (data) {
      	        	                        	  data = JSON.parse(data);
      	        	                              if (data[0] == "success") {
      	        	                            	  BootstrapDialog.alert(data[1]);
      	        	                                  oTable.fnDraw();
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


        	              


        	            $(document).on('click', "#payment_editable-sample a.pay_disable", function (e) {
        	                  e.preventDefault();
        	                  var nRow = $(this).parents('tr')[0];
        	                  var data = oTable.fnGetData(nRow);
        	                  var pay_id = data[0];
        	                  var pay_name = data[1];
        	                  BootstrapDialog.show({
          		                title:'Confirmation',
      		                    message: 'Are Sure you want to Disable <strong>'+ pay_name +'<strong>',
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
      	        	                          url: "php/paymentMode.handle.php",
      	        	                          cache: false,
      	        	                          data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', pId:pay_id},
      	        	                          complete:function(){
        	        		                    	 dialogRef.close();
        	        		                      },
      	        	                          success: function (data) {
      	        	                        	  data = JSON.parse(data);
      	        	                              if (data[0] == "success") {
      	        	                            	  BootstrapDialog.alert(data[1]);
      	        	                                  oTable.fnDraw();
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

        	              

        	              $(document).on('click', "#payment_editable-sample a.pay_edit", function (e) {
        	                  e.preventDefault();
        	                  /* Get the row as a parent of the link that was clicked on */
        	                  var nRow = $(this).parents('tr')[0];

        	                  if (nEditing !== null && nEditing != nRow) {
        	                      /* Currently editing - but not this row - restore the old before continuing to edit mode */
        	                      restoreRow(oTable, nEditing);
        	                      editRow(oTable, nRow);
        	                      nEditing = nRow;
        	                  } else if (nEditing == nRow && $(this).data('actions')== "Save") {

        	                      /* Editing this row and want to save it */
        	                      saveRow(oTable, nEditing);
        	                      nEditing = null;
        	                      var data = oTable.fnGetData(nRow);
        	                      console.log(data[2]);
        	                      
        	                      if ((data[2] !== 'Cash') && ( data[5] ==''|| data[4] ==''|| data[3] ==''|| data[6] ==''|| data[1] =='') ) {
        	                          alert("Enter Required Fields");
        	                          editRow(oTable, nRow);
        	                          nEditing = nRow;
        	                      }
        	                      else {
        	                          $.ajax({
        	                              dataType: 'html',
        	                              type: "POST",
        	                              url: "php/paymentMode.handle.php",
        	                              cache: false,
        	                              data: { act: '<?php echo base64_encode($_SESSION['company_id']."!update");?>',pId: data[0], name: data[1], bName: data[3], acNo: data[4],
        	                            	  branch_name: data[5], ifsc: data[6], account_type: data[2]
        	                              },
        	                              success: function (data) {
        	                                  data1 = JSON.parse(data);
        	                                  if (data1[0] == "success") {
        	                                      $('.p_model_msg2').click();
        	                                      oTable.fnDraw();

        	                                  }
        	                                  else
        	                                      if (data1[0] == "error") {
        	                                          alert(data1[1]);
        	                                      }


        	                              }

        	                          });
        	                      }

        	                  } else {
        	                      /* No edit in progress - let's start one */
        	                      editRow(oTable, nRow);
        	                      nEditing = nRow;
        	                  }
        	              });
        	          }

        	      };

        	  } ();
             payment_EditableTable.init();
			  $("#add-paymentMode").hide();
            $(document).on('click', "#payment_showhide", function (e) {
                  e.preventDefault();
                  jQuery('#add-paymentMode').toggle('show');
               });

            $(document).on('click', "#cancel", function (e) {
                e.preventDefault();
                jQuery('#add-paymentMode').toggle('hide');
             });
            
			$("#account_type_sub").click(function () {
                console.log("sdfs");
            });  
          });
      </script>

