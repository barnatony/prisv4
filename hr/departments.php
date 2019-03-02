<?php

include_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
?>
<div class="btn-group pull-right">
	<button id="showhide_de" type="button" class="btn btn-sm btn-info"
		style="margin-top: -65%;">
		<i class="fa fa-plus"></i> Add
	</button>
</div>
<section class="panel">
	<div class="panel-body">


		<div class="col-lg-12" id="add-department">
			<form class="form-horizontal" role="form" method="post"
				id="departmentAddForm">
				<input type="hidden" name="act"
					value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
				<div class="col-lg-12" id="add-department">
					<div class="panel-body">
						<div class="form-group">
							<label for="dname" class="col-lg-3 col-sm-3 control-label">Department
								Name</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" id="dname_sub"
									name="dName" maxlength="50" placeholder="" required>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-3 col-lg-5" align="right">
								<span id="dep-error" class="help-block pull-left text-danger"
									style="display: none;"> Enter the Department Name</span>
								<button type="submit" class="btn btn-sm btn-success"
									id="department_add">Add</button>
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
					id="department_editable-sample">
					<thead class="cf">
						<tr>
							<th>Department ID</th>
							<th>Department Name</th>
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
          $(document).ready(function () {
            var depart_EditableTable = function () {

        	      return {

        	          //main function to initiate the module
        	          init: function () {
        	              function restoreRow(depart_oTable, nRow) {
        	                  var aData = depart_oTable.fnGetData(nRow);
        	                  var jqTds = $('>td', nRow);
        	                  for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
        	                      depart_oTable.fnUpdate(aData[i], nRow, i, false);
        	                  }
        	                  depart_oTable.fnDraw();
        	              }

        	              function editRow(depart_oTable, nRow) {
        	                  var aData = depart_oTable.fnGetData(nRow);
        	                  var jqTds = $('>td', nRow);
        	                  jqTds[0].innerHTML = '<input type="text" class="form-control small" maxlength="20" required value="' + aData[1] + '">';
        	                  jqTds[1].innerHTML = '<a class="department_edit" href="" title="Save" data-actions="Save" ><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>&nbsp;<a class="department_cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a>';

        	              }

        	              function saveRow(depart_oTable, nRow) {
        	                  var jqInputs = $('input', nRow);
        	                  depart_oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
        	                  depart_oTable.fnUpdate('<a class="department_edit" title="Save" href=""><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>', nRow, 2, false);
        	                  //depart_oTable.fnUpdate('<a class="department_delete" href="">Delete</a>', nRow, 3, false);
        	              }
        	              
        	              //$('#department_editable-sample').remove();
        	              var depart_oTable = $('#department_editable-sample').dataTable({
        	                  "aLengthMenu": [
        	                      [5, 15, 20, -1],
        	                      [5, 15, 20, "All"] // change per page values here
        	                  ],

        	                  // set the initial value
        	                  "iDisplayLength": 5,
        	                  "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

        	                  "bProcessing": true,
        	                  "bServerSide": true,
        	                  "sAjaxSource": "php/department-view.php",
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
										{ "sName": "department_id" },
        	                          { "sName": "department_name" },
        	                          { "sName": "enabled" },

        	                      ],"aoColumnDefs": [
       	                                          {"bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }
       	            	                       ],
        	                  "oTableTools": {
        	                      "aButtons": [
        	                  {
        	                      "sExtends": "pdf",
        	                      "mColumns": [0, 1, 2],
        	                      "sPdfOrientation": "landscape",
        	                      "sPdfMessage": "Designation Details"
        	                  },
        	                  {
        	                      "sExtends": "xls",
        	                      "mColumns": [0, 1, 2]
        	                  }
        	               ],
        	                      "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

        	                  }

        	              });

        	              $('#department_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
        	                                                <input class="form-control medium" id="depart_searchInput" type="text">\
        	                                                <span class="input-group-btn">\
        	                                                  <button class="btn btn-white" id="depart_searchFilter" type="button">Search</button>\
        	                                                </span>\
        	                                                <span class="input-group-btn">\
        	                                                  <button class="btn btn-white" id="depart_searchClear" type="button">Clear</button>\
        	                                                </span>\
        	                                            </div>');
        	              $('#department_editable-sample_processing').css('text-align', 'center');
        	              //jQuery('#department_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
        	              $('#department_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
        	              $('#depart_searchInput').on('keyup', function (e) {
        	                  if (e.keyCode == 13) {
        	                      depart_oTable.fnFilter($(this).val());
        	                  } else if (e.keyCode == 27) {
        	                      $(this).parent().parent().find('input').val("");
        	                      depart_oTable.fnFilter("");
        	                  }
        	              });

        	              $(document).on('click', "#depart_searchFilter", function () {
        	                  depart_oTable.fnFilter($(this).parent().parent().find('input').val());
        	              });

        	              $(document).on('click', "#depart_searchClear", function () {
        	                  $(this).parent().parent().find('input').val("");
        	                  depart_oTable.fnFilter("");
        	              });

        	              var nEditing = null;

        	              // for adding new department

        	              $(document).on('click', "#department_add", function (e) {
        	                  e.preventDefault();
        	                  if ($("#dname_sub").val() == '')
        	                  {  
        	                     $("#dep-error").show();
        	                  }
        	                  else {
        	                      $.ajax({
        	                          dataType: 'html',
        	                          type: "POST",
        	                          url: "php/department.handle.php",
        	                          cache: false,
        	                          data: $('#departmentAddForm').serialize(),
        	                          beforeSend:function(){
        	                           	$('#department_add').button('loading'); 
        	                          },
        	                          complete:function(){
        	                         	 $('#department_add').button('reset');
        	                          },
        	                          success: function (data) {
        	                              data= JSON.parse(data);
        	                              if (data[0] == "success") {
        	                                  jQuery('#add-department').toggle('hide');
        	                                  $('#departmentAddForm')[0].reset();
        	                                  BootstrapDialog.alert(data[1]);
        	                                  depart_oTable.fnDraw();
        	                                  $("#dep-error").hide();
        	                              }
        	                              else {
        	                                  if (data[0] == "error") {
        	                                	  BootstrapDialog.alert(data[1]);
        	                                  }


        	                              }
        	                          }

        	                      });
        	                  }
        	              });

        	              $(document).on('click', "#department_editable-sample  .department_cancel", function (e) {
          	                  e.preventDefault();
          	                  if ($(this).attr("data-mode") == "new") {
          	                      var nRow = $(this).parents('tr')[0];
          	                    depart_oTable.fnDeleteRow(nRow);
          	                  } else {
          	                      restoreRow(depart_oTable, nEditing);
          	                      nEditing = null;
          	                  }
          	              });
            	              

        	              $(document).on('click', "#department_editable-sample a.department_disable", function (e) {
        	                  e.preventDefault();
        	                  var nRow = $(this).parents('tr')[0];
        	                  var data = depart_oTable.fnGetData(nRow);
        	                  var depart_id = data[0];
        	                  var depart_name = data[1];
        	                  BootstrapDialog.show({
          		                title:'Confirmation',
      		                    message: 'Are Sure you want to Disable <strong>'+ depart_name +'<strong>',
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
      	        	                          url: "php/department.handle.php",
      	        	                          cache: false,
      	        	                          data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', dId: depart_id },
      	        	                          complete:function(){
         	        		                    	 dialogRef.close();
         	        		                      },
      	        	                          success: function (data) {
      	        	                        	data = JSON.parse(data);
      	        	                              if (data[0] == "success") {
          	        	                            BootstrapDialog.alert(data[1]);
      	        	                                  depart_oTable.fnDraw();
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


        	              

        	                $(document).on('click', "#department_editable-sample a.department_enable", function (e) {
        	                  e.preventDefault();
        	                  var nRow = $(this).parents('tr')[0];
        	                  var data = depart_oTable.fnGetData(nRow);
        	                  var e_depart_id = data[0];
        	                  var e_depart_name = data[1];
        	                  BootstrapDialog.show({
          		                title:'Confirmation',
      		                    message: 'Are Sure you want to Enable <strong>'+ e_depart_name +'<strong>',
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
      	        	                          url: "php/department.handle.php",
      	        	                          cache: false,
      	        	                          data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>', dId: e_depart_id  },
      	        	                         
      	        	                        complete:function(){
    	        		                    	 dialogRef.close();
    	        		                      },
      	        	                          success: function (data) {
      	        	                        	data = JSON.parse(data);
      	        	                              if (data[0] == "success") {
      	        	                            	BootstrapDialog.alert(data[1]);
      	        	                                  depart_oTable.fnDraw();
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


        	           

        	              

        	              $(document).on('click', "#department_editable-sample a.department_edit", function (e) {
        	                  e.preventDefault();

        	                  /* Get the row as a parent of the link that was clicked on */
        	                  var nRow = $(this).parents('tr')[0];

        	                  if (nEditing !== null && nEditing != nRow) {
        	                      /* Currently editing - but not this row - restore the old before continuing to edit mode */
        	                      restoreRow(depart_oTable, nEditing);
        	                      editRow(depart_oTable, nRow);
        	                      nEditing = nRow;
        	                  } else if (nEditing == nRow && $(this).data('actions') == "Save") {
        	                    
        	                      /* Editing this row and want to save it */
        	                      saveRow(depart_oTable, nEditing);
        	                      nEditing = null;
        	                      var data = depart_oTable.fnGetData(nRow);
        	                        if (data[1] == '') {
        	                          alert("Enter Department Name");
        	                          editRow(depart_oTable, nRow);
        	                          nEditing = nRow;
        	                      }
        	                      else {
        	                              $.ajax({
        	                              dataType: 'html',
        	                              type: "POST",
        	                              url: "php/department.handle.php",
        	                              cache: false,
        	                              data: { act: '<?php echo base64_encode($_SESSION['company_id']."!update");?>', dId: data[0],dName: data[1]},
        	                              success: function (data) {
        	                            	  data1 = JSON.parse(data);
        	                                  if (data1[0] == "success") {
        	                                     $('.d_model_msg2').click();
        	                                      depart_oTable.fnDraw();
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
        	                      editRow(depart_oTable, nRow);
        	                      nEditing = nRow;
        	                  }
        	              });
        	          }

        	      };

        	  } ();


        	  $(document).on('click', "#cancel", function (e) {
                  e.preventDefault();
                  jQuery('#add-department').toggle('hide');
               });
               $("#add-department").hide();
                 depart_EditableTable.init();
                $(document).on('click', "#showhide_de", function (e) {
                  e.preventDefault();
                  jQuery('#add-department').toggle('show');
               });
                    
          });
      </script>
