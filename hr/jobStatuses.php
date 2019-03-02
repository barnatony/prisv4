<?php

include_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
?>
<div class="btn-group pull-right">
	<button id="job_showhide" type="button" class="btn btn-sm btn-info"
		style="margin-top: -65%;">
		<i class="fa fa-plus"></i> Add
	</button>
</div>
<section class="panel">
	<div class="panel-body">
		<div class="col-lg-12" id="add-status">
			<form class="form-horizontal" role="form" method="post"
				id="statusAddForm">
				<input type="hidden" name="act"
					value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
				<div class="col-lg-12" id="add-status">
					<div class="panel-body">

						<div class="form-group">
							<label for="sname" class="col-lg-3 col-sm-3 control-label">Status
								Name</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" id="sname" name="sname"
									maxlength="25" placeholder="" required>
							</div>
						</div>
						<div class="form-group">
							<label for="dname" class="col-lg-3 col-sm-3 control-label">Status
								Comments</label>
							<div class="col-lg-5">
								<textarea class="form-control" id="scmts" name="scmts"
									maxlength="100" placeholder="" required rows="2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-3 col-lg-5" align="right">
								<span id="job-error" class="help-block pull-left text-danger"
									style="display: none;"> Enter All Required Fields</span>
								<button type="submit" class="btn btn-sm btn-success"
									id="job_status_add">Add</button>
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
					id="job_status_editable-sample">
					<thead class="cf">
						<tr>
							<th>Job ID</th>
							<th>Job Name</th>
							<th>job Comments</th>
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


<!-- END JAVASCRIPTS -->
<script type="text/javascript">
          jQuery(document).ready(function () {

        	  var job_EditableTable = function () {

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
        	                  jqTds[0].innerHTML = '<input type="text" class="form-control small" maxlength="15" required value="' + aData[1] + '">';
        	                  jqTds[1].innerHTML = '<textarea type="text" class="form-control small" maxlength="50" required>' + aData[2] + '</textarea>';
        	                  jqTds[2].innerHTML = '<a class="job_edit" href=""data-actions="Save"  title="Save"><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>&nbsp;<a class="job_cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs" ><i class="fa fa-times"></i></button></a>';

        	              }

        	              function saveRow(oTable, nRow) {
        	                  var jqInputs = $('input,textarea', nRow);
        	                  oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
        	                  oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
        	                  oTable.fnUpdate('<a class="job_edit"  href="" data-actions="Save" title="Save" ><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>', nRow, 3, false);
        	              }



        	              var oTable = $('#job_status_editable-sample').dataTable({
        	                  "aLengthMenu": [
        	                      [5, 15, 20, -1],
        	                      [5, 15, 20, "All"] // change per page values here
        	                  ],

        	                  // set the initial value
        	                  "iDisplayLength": 5,
        	                  "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

        	                  "bProcessing": true,
        	                  "bServerSide": true,
        	                  "sAjaxSource": "php/jobStatus-view.php",
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
										{ "sName": "status_id" },
        	                          { "sName": "status_name" },
        	  						{ "sName": "status_comments" },
        	                          { "bSortable": false }
        	                      ],"aoColumnDefs": [
       	                                          {"bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }
       	            	                       ],
        	                  "oTableTools": {
        	                      "aButtons": [
        	                  {
        	                      "sExtends": "pdf",
        	                      "mColumns": [0, 1, 2],
        	                      "sPdfOrientation": "landscape",
        	                      "sPdfMessage": "Job Status Details"
        	                  },
        	                  {
        	                      "sExtends": "xls",
        	                      "mColumns": [0, 1, 2]
        	                  }
        	               ],
        	                      "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

        	                  }

        	              });


        	              $('#job_status_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
        	                                                <input class="form-control medium" id="job_searchInput" type="text">\
        	                                                <span class="input-group-btn">\
        	                                                  <button class="btn btn-white" id="job_searchFilter" type="button">Search</button>\
        	                                                </span>\
        	                                                <span class="input-group-btn">\
        	                                                  <button class="btn btn-white" id="job_searchClear" type="button">Clear</button>\
        	                                                </span>\
        	                                            </div>');
        	              $('#job_status_editable-sample_processing').css('text-align', 'center');
        	              //jQuery('#job_status_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
        	              $('#job_status_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
        	              $('#job_searchInput').on('keyup', function (e) {
        	                  if (e.keyCode == 13) {
        	                      oTable.fnFilter($(this).val());
        	                  } else if (e.keyCode == 27) {
        	                      $(this).parent().parent().find('input').val("");
        	                      oTable.fnFilter("");
        	                  }
        	              });

        	              $(document).on('click', "#job_searchFilter", function () {
        	                  oTable.fnFilter($(this).parent().parent().find('input').val());
        	              });
        	              $(document).on('click', "#job_searchClear", function () {
        	                  $(this).parent().parent().find('input').val("");
        	                  oTable.fnFilter("");
        	              });

        	              var nEditing = null;

        	              // for adding new designation
        	              $(document).on('click', "#job_status_add", function (e) {
        	                  e.preventDefault();
        	                  if ($("#sname").val() == '' && $("#scmts").val() == '')
        	                  { $('#job-error').show(); }
        	                  else {
        	                      $.ajax({
        	                          dataType: 'html',
        	                          type: "POST",
        	                          url: "php/jobStatus.handle.php",
        	                          cache: false,
        	                          data: $('#statusAddForm').serialize(), 
        	                          beforeSend:function(){
        	                           	$('#job_status_add').button('loading'); 
        	                          },
        	                          complete:function(){
        	                         	 $('#job_status_add').button('reset');
        	                          },
        	                          success: function (data) {
        	                              data1 = JSON.parse(data);
        	                              if (data1[0] == "success") {
        	                                  jQuery('#add-status').toggle('hide');
        	                                  $('#statusAddForm')[0].reset();
        	                                  $('.js_model_msg1').click();
        	                                  BootstrapDialog.alert("JobStatus Added sucessfully");
        	                                  oTable.fnDraw();
                                               $("#job-error").hide();
        	                              }
        	                              else {
        	                                  if (data1[0] == "error") {
        	                                	  BootstrapDialog.alert("JobStatus Added Failed");
        	                                  }


        	                              }

        	                          }

        	                      });
        	                  }
        	              });


        	              $(document).on('click', "#job_status_editable-sample  .job_cancel", function (e) {
          	                  e.preventDefault();
          	                  if ($(this).attr("data-mode") == "new") {
          	                      var nRow = $(this).parents('tr')[0];
          	                    oTable.fnDeleteRow(nRow);
          	                  } else {
          	                      restoreRow(oTable, nEditing);
          	                      nEditing = null;
          	                  }
          	              });
            	              

        	              $(document).on('click', "#job_status_editable-sample a.job_disable", function (e) {
        	                  e.preventDefault();
        	                  var nRow = $(this).parents('tr')[0];
        	                  var data = oTable.fnGetData(nRow);
        	                  var js_id = data[0];
        	                  var js_name = data[1];
        	                  BootstrapDialog.show({
          		                title:'Confirmation',
      		                    message: 'Are Sure you want to Disable <strong>'+ js_name +'<strong>',
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
      	        	                          url: "php/jobStatus.handle.php",
      	        	                          cache: false,
      	        	                          data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', jStatusId:js_id },
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
      	        	                            	BootstrapDialog.alert("Error on query");
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

        	             

        	             $(document).on('click', "#job_status_editable-sample a.job_enable", function (e) {
        	                  e.preventDefault();
        	                  var nRow = $(this).parents('tr')[0];
        	                  var data = oTable.fnGetData(nRow);
        	                  var e_js_id = data[0];
        	                  var e_js_name = data[1];
        	                  BootstrapDialog.show({
            		                title:'Confirmation',
        		                    message: 'Are Sure you want to Enable <strong>'+ e_js_name +'<strong>',
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
        	        	                          url: "php/jobStatus.handle.php",
        	        	                          cache: false,
        	        	                          data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>', jStatusId:e_js_id },
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
        	        	                            	BootstrapDialog.alert("Error on query");
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


        	             $(document).on('click', "#job_status_editable-sample a.job_edit", function (e) {
        	                  e.preventDefault();

        	                  /* Get the row as a parent of the link that was clicked on */
        	                  var nRow = $(this).parents('tr')[0];

        	                  if (nEditing !== null && nEditing != nRow) {
        	                      /* Currently editing - but not this row - restore the old before continuing to edit mode */
        	                      restoreRow(oTable, nEditing);
        	                      editRow(oTable, nRow);
        	                      nEditing = nRow;
        	                  } else if (nEditing == nRow && $(this).data('actions') == "Save") {

        	                      /* Editing this row and want to save it */
        	                      saveRow(oTable, nEditing);
        	                      nEditing = null;
        	                      var data = oTable.fnGetData(nRow);
        	                      if (data[1] == '' || data[2] == '') {
        	                          alert("Enter Required Fields");
        	                          editRow(oTable, nRow);
        	                          nEditing = nRow;
        	                      }
        	                      else {
        	                          $.ajax({
        	                              dataType: 'html',
        	                              type: "POST",
        	                              url: "php/jobStatus.handle.php",
        	                              cache: false,
        	                              data: { act: '<?php echo base64_encode($_SESSION['company_id']."!update");?>', jStatusId: data[0], sname: data[1], scmts: data[2] },
        	                              success: function (data) {
        	                                  data1 = JSON.parse(data);
        	                                  if (data1[0] == "success") {
        	                                      $('.js_model_msg2').click();
        	                                      oTable.fnDraw();
                                                  
        	                                  }
        	                                  else {
        	                                      if (data1[0] == "error") {
        	                                          alert(data1[1]);
        	                                      }


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
        	  $(document).on('click', "#cancel", function (e) {
                  e.preventDefault();
                 jQuery('#add-status').toggle('hide');
              });
  	                
              job_EditableTable.init();
              $("#add-status").hide();
              $(document).on('click', "#job_showhide", function (e) {
                  e.preventDefault();
                 jQuery('#add-status').toggle('show');
              });
          });

      </script>

