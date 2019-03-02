<?php

include_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
?>
<div class="btn-group pull-right">
	<button id="branch_showhide" type="button" class="btn btn-sm btn-info"
		style="margin-top: -65%;">
		<i class="fa fa-plus"></i> Add
	</button>
</div>
<section class="panel">
	<div class="panel-body">
		<div class="col-lg-12" id="add-branch">
			<form class="form-horizontal" role="form" method="post"
				id="branchAddForm">
				<input type="hidden" name="act"
					value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
				<div class="col-lg-6">
					<div class="form-group">
						<label for="b_name" class="col-lg-3 col-sm-3 control-label">Branch
							Name</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="bname" maxlength="50"
								name="bName" placeholder="" required>
						</div>
					</div>
					<div class="form-group">
						<label for="b_addr" class="col-lg-3 col-sm-3 control-label">Location</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" name="bLoc" id="bloc"
								maxlength="30" placeholder="" required>
							<p class="help-block">Door No,Street Name,Local Area.</p>
						</div>
					</div>
					<div class="form-group">
						<label for="b_pin" class="col-lg-3 col-sm-3 control-label">PIN
							Code</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="bpin" name="bPin"
								maxlength="6" placeholder="" required>
						</div>
						<span class="help-block"><span class="pull-right empty-message"
							style="display: none">No Records Found</span></span>

					</div>
					<div class="form-group">
						<label for="b_city" class="col-lg-3 col-sm-3 control-label">City</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="bcity" name="bCity"
								placeholder="" required>
						</div>
					</div>
					<div class="form-group">
						<label for="b_state" class="col-lg-3 col-sm-3 control-label">State</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" name="bstate" id="bState"
								placeholder="" required>
						</div>
					</div>
				</div>
				<div class="col-lg-6" align="right">
					<div class="form-group">
						<label for="pt_slab" class="col-lg-3 col-sm-3 control-label">P.T
							Slab</label>
						<div class="col-lg-6">
							<select type="text" class="form-control" id="pt_slab"
								name="ptSlab" required>
								<option value="">Select PT Slab</option>
                                       <?php include("../common/display-ptslab.php"); ?>
                                    </select>
						</div>
					</div>
					<div class="form-group">
						<label for="b_pf" class="col-lg-3 col-sm-3 control-label">P.F No.</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="bpf" name="bPf"
								placeholder="" required>
						</div>
					</div>
					<div class="form-group">
						<label for="b_esi" class="col-lg-3 col-sm-3 control-label">E.S.I
							No.</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="besi" name="bEsi"
								placeholder="" required>
						</div>
					</div>
					<div class="form-group">
						<label for="p_pt" class="col-lg-3 col-sm-3 control-label">P.T No.</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="bpt" name="bPt"
								placeholder="" maxlength="10" required>
						</div>
					</div>
					<div class="form-group">
						<label for="p_pt" class="col-lg-3 col-sm-3 control-label">TAN No.</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="btan" name="bTan"
								placeholder="" required>
						</div>
					</div>
					<span id="error" class="help-block pull-left text-danger"
								style="display: none;"> Enter All Required Fields</span>
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-6">
							
							<button type="submit" class="btn btn-sm btn-success"
								id="branch_add">Add</button>
							<button type="button" class="btn btn-sm btn-danger" id="cancel">Cancel</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="space15"></div>
		<div class="adv-table editable-table">
			<section id="flip-scroll">
				<table class="table table-striped table-hover table-bordered cf"
					id="branch_editable-sample">
					<thead class="cf">
						<tr>
							<th>Branch Id</th>
							<th>Name</th>
							<th>Area</th>
							<th>Pin</th>
							<th>City</th>
							<th>State</th>
							<th>PT City</th>
							<th>PT No</th>
							<th>ESI No</th>
							<th>PF No</th>
							<th>TAN No</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr></tr>
					</tbody>
				</table>
			</section>
		</div>


		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
			tabindex="-1" id="branch_edit" class="modal fade">
			<div class="modal-dialog  modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close"
							type="button">×</button>
						<h4 class="modal-title">Edit Branch</h4>
					</div>
					<div class="modal-body">
						<form id="editBranchForm" method="POST" class="form-horizontal"
							role="form">
							<input type="hidden" name="act"
								value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
							<div class="row col-lg-12">
								<input type="hidden" name="bId" id="branchId">
								<div class="form-group col-lg-6">
									<label for="b_name" class="col-lg-5 col-sm-5 control-label">Branch
										Name</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="edit_bname"
											maxlength="15" name="bName" placeholder="" required>
									</div>
								</div>
								<div class="form-group col-lg-6">
									<label for="b_addr" class="col-lg-5 col-sm-5 control-label">Location</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" name="bLoc"
											id="edit_bloc" maxlength="30" placeholder="" required>
									</div>
								</div>
							</div>
							<div class="row col-lg-12">
								<div class="form-group col-lg-6">
									<label for="b_pin" class="col-lg-5 col-sm-5 control-label">PIN
										Code</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="edit_bpin"
											maxlength="6" name="bPin" placeholder="" required>
									</div>
								</div>
								<div class="form-group col-lg-6">
									<label for="b_city" class="col-lg-5 col-sm-5 control-label">City</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="edit_bcity"
											name="bCity" placeholder="" required>
									</div>
								</div>
							</div>
							<div class="row col-lg-12">
								<div class="form-group col-lg-6">
									<label for="b_state" class="col-lg-5 col-sm-5 control-label">State</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" name="bstate"
											id="edit_bstate" placeholder="" required>
									</div>
								</div>
								<div class="form-group col-lg-6">
									<label for="pt_slab" class="col-lg-5 col-sm-5 control-label">P.T
										Slab</label>
									<div class="col-lg-7">
										<select type="text" class="form-control" id="edit_pt_slab"
											name="ptSlab" required>
											<option value="">Select PT Slab</option>
                                       <?php include("../common/display-ptslab.php"); ?>
                                    </select>
									</div>
								</div>
							</div>
							<div class="row col-lg-12">
								<div class="form-group col-lg-6">
									<label for="b_pf" class="col-lg-5 col-sm-5 control-label">P.F
										No.</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="edit_bpf"
											name="bPf" placeholder="" required>
									</div>
								</div>
								<div class="form-group col-lg-6">
									<label for="b_esi" class="col-lg-5 col-sm-5 control-label">E.S.I
										No.</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="edit_besi"
											name="bEsi" placeholder="" required>
									</div>
								</div>
							</div>
							<div class="row col-lg-12">
								<div class="form-group col-lg-6">
									<label for="p_pt" class="col-lg-5 col-sm-5 control-label">P.T
										No.</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="edit_bpt"
											name="bPt" maxlength="20" placeholder="" required>
									</div>
								</div>
								<div class="form-group col-lg-6">
									<label for="p_pt" class="col-lg-5 col-sm-5 control-label">TAN
										No.</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" id="edit_btan"
											name="bTan" placeholder="" required>
									</div>
								</div>
							</div>

							<div class="modal-footer">
								<span id="edit-error" class="help-block pull-left"
									style="display: none;"> Enter All Required Fields</span>
								<button type="button" class="btn btn-sm btn-danger"
									data-dismiss="modal">Close</button>

								<button type="submit" class="btn btn-sm  btn-success"
									id="update">Update</button>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<!-- END JAVASCRIPTS -->
<script type="text/javascript">
var branch_EditableTable = function () {

	    return {

	        //main function to initiate the module
	        init: function () {

	            var oTable = $('#branch_editable-sample').dataTable({
	                "aLengthMenu": [
	                    [5, 15, 20, -1],
	                    [5, 15, 20, "All"] // change per page values here
	                ],

	                // set the initial value
	                "iDisplayLength": 5,
	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
	                "bProcessing": true,
	                "bServerSide": true,
	                "sAjaxSource": "php/branch-view.php",
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
	                "aoColumns": [                           //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
	                        {"sName": "b.branch_id" },
	                        { "sName": "b.branch_name" },
	                        { "sName": "b.branch_addr" },
							{ "sName": "b.city_pin" },
							{ "sName": "p.districtname" },
							{ "sName": "p.statename" },
							{ "sName": "b.pt_city_id" },
							{ "bSortable": false },
							{ "bSortable": false },
							{ "bSortable": false },
	                        { "bSortable": false },
	                        { "sName": "b.enabled" },


	                    ],"aoColumnDefs": [
                                            {"bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }   
                                           
                                           ],
	                "oTableTools": {
	                    "aButtons": [
	                {
	                    "sExtends": "pdf",
	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
	                    "sPdfOrientation": "landscape",
	                    "sPdfMessage": "Branch Details"
	                },
	                {
	                    "sExtends": "xls",
	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
	                }
	             ],
	                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

	                }

	            });

	            $('#branch_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
	                                              <input class="form-control medium" id="branch_searchInput" type="text">\
	                                              <span class="input-group-btn">\
	                                                <button class="btn btn-white" id="branch_searchFilter" type="button">Search</button>\
	                                              </span>\
	                                              <span class="input-group-btn">\
	                                                <button class="btn btn-white" id="branch_searchClear" type="button">Clear</button>\
	                                              </span>\
	                                          </div>');
	            $('#branch_editable-sample_processing').css('text-align', 'center');
	            //jQuery('#branch_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
	            jQuery('#branch_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
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
	            var nEditing = null;

	            // for adding new branch
	            $(document).on('click', "#branch_add", function (e) {
	                e.preventDefault();
	                if ($("#bname").val() == '' || $("#bloc").val() == '' || $("#bcity").val() == '' || $("#bstate").val() == '' || $("#bpin").val() == '' || $("#bpf").val() == '' || $("#besi").val() == '' || $("#bpt").val() == '' || $("#btan").val() == '') {
	                	$("#error").show();

	                }
	                else {
	                    $.ajax({
	                        dataType: 'html',
	                        type: "POST",
	                        url: "php/branch.handle.php",
	                        cache: false,
	                        data: $('#branchAddForm').serialize(),
	                        beforeSend:function(){
	                         	$('#branch_add').button('loading'); 
	                          },
	                          complete:function(){
	                         	 $('#branch_add').button('reset');
	                          },
	                        success: function (data) {
	                            data1 = JSON.parse(data);
	                            if (data1[0] == "success") {
	                                jQuery('#add-branch').toggle('hide');
	                                $("#bname").val('') ;
	                                $("#bloc").val('') ; 
	                                $("#bcity").val('') ;
	                                $("#bstate").val('') ;
	                                $("#bpin").val('') ;
	                                $("#bpt").val('') ;
	                                oTable.fnDraw();
	                                BootstrapDialog.alert('Branch Added Successfully');
	                                setTimeout(function(){
  	                            	$('.close').click();
  	                            	}, 5000);
	                                $("#error").hide();
	                            }
	                            else if (data1[0] == "error") {
	                                    alert(data1[1]);
	                                }
	                        }

	                    });
	                }
	            });



	            $(document).on('click', "#branch_editable-sample a.branch_disable", function (e) {
	                e.preventDefault();
	                var nRow = $(this).parents('tr')[0];
	                var data = oTable.fnGetData(nRow);	
	                var branch_id = data[0];
	                var branch_name = data[1];
	                BootstrapDialog.show({
		                title:'Confirmation',
	                    message: 'Are Sure you want to Disable <strong>'+ branch_name +'<strong>',
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
       		                    url: "php/branch.handle.php",
       		                    cache: false,
       		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', bId: branch_id  },
       		                      complete:function(){
       		                    	 dialogRef.close();
       		                      },
       		                    success: function (data) {
       		                    	data = JSON.parse(data);
       		                        if (data[0] == "success") {
       		                        	oTable.fnDraw();
       		                        	BootstrapDialog.alert(data[1]);
       		                        	 setTimeout(function(){
           	                            	$('.close').click();
           	                            	}, 5000);
       		                        }else if (data[0] == "error") {
		                                    alert(data[1]);
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

	            $(document).on('click', "#branch_editable-sample a.branch_enable", function (e) {
	                e.preventDefault();
	                var nRow = $(this).parents('tr')[0];
	                var data = oTable.fnGetData(nRow);
	                var e_branch_id = data[0];
	                var branch_name = data[1];
	                BootstrapDialog.show({
		                title:'Confirmation',
	                    message: 'Are Sure you want to Enable <strong>'+ branch_name +'</strong>',
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
       		                    url: "php/branch.handle.php",
       		                    cache: false,
       		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>', bId: e_branch_id },
       		                   complete:function(){
     		                    	 dialogRef.close();
     		                      },
       		                    success: function (data) {
       		                    	data = JSON.parse(data);
       		                        if (data[0] == "success") {
       		                        	oTable.fnDraw();
       		                        	BootstrapDialog.alert(data[1]);       		                            
       		                        }else if (data[0] == "error") {
		                                    alert(data[1]);
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
		   
					$(document).on('click', "#branch_editable-sample a.branch_edit", function (e) {
	                e.preventDefault();
	                /* Get the row as a parent of the link that was clicked on */
	                var nRow = $(this).parents('tr')[0];
	                var data = oTable.fnGetData(nRow);
	                $('#branchId').val(data[0]);
	                $('#edit_bname').val(data[1]);
	                $('#edit_bloc').val(data[2]);
	                $('#edit_bpin').val(data[3]);
	                $('#edit_bcity').val(data[4]);
	                $('#edit_bstate').val(data[5]);
	                var str=data[6].split(" ");

                 if(str[1]){$('#edit_pt_slab').val(str[0]+"_"+str[1])}else{$('#edit_pt_slab').val(str[0])};
	                $('#edit_bpf').val(data[9]);
	                $('#edit_besi').val(data[8]);
	                $('#edit_bpt').val(data[7]);
	                $('#edit_btan').val(data[10]);

	            });

	            $('#editBranchForm').on('submit', function (e) {
	                e.preventDefault();
	                if ($("#edit_bname").val() == '' || $("#edit_bloc").val() == '' || $("#edit_bcity").val() == '' || $("#edit_bstate").val() == '' || $("#edit_bpin").val() == '' || $("#edit_bpf").val() == '' || $("#edit_besi").val() == '' || $("#edit_bpt").val() == '' || $("#edit_btan").val() == '') {

	                	$("#edit-error").show();

	                }
	                else {
	                    $.ajax({
	                        dataType: 'html',
	                        type: "POST",
	                        url: "php/branch.handle.php",
	                        cache: false,
	                        data: $(this).serialize(),
	                        beforeSend:function(){
	                         	$('#update').button('loading'); 
	                          },
	                          complete:function(){
	                         	 $('#update').button('reset');
	                          },
	                        success: function (data) {

	                            data1 = JSON.parse(data);
	                            if (data1[0] == "success") {
	                                $('.close').click();
	                                $('#edit_branch').modal('hide');
	                                $('#editBranchForm')[0].reset();
	                                oTable.fnDraw();
	                                BootstrapDialog.alert('Branch Updated Successfully');
	                                setTimeout(function(){
  	                            	$('.close').click();
  	                            	}, 5000);
	                                $("#edit-error").hide();
	                            }
	                            else
	                                if (data1[0] == "error") {
	                                    alert(data1[1]);
	                                }
	                        }

	                    });
	                }
	            });

	        }

	    };

	} ();
          $(document).ready(function () {
		  $("#add-branch").hide();
		   commonFunctions.getCompanyDetails(function (result) {
                  if (result[0].company_epf_pattern == 'C') {
                      $('#bpf').val(result[0].company_epf_no);
                      $('#bpf').attr('readonly', true);
                      $('#edit_bpf').val(result[0].company_epf_no);
                      $('#edit_bpf').attr('readonly', true);
                  }
                  if (result[0].company_esi_pattern == 'C') {
                      $('#besi').val(result[0].company_esi_no);
                      $('#besi').attr('readonly', true);
                      $('#edit_besi').val(result[0].company_esi_no);
                      $('#edit_besi').attr('readonly', true);
                  }
                  if (result[0].company_tan_pattern == 'C') {
                      $('#btan').val(result[0].company_tan_no);
                      $('#btan').attr('readonly', true);
                      $('#edit_btan').val(result[0].company_tan_no);
                      $('#edit_btan').attr('readonly', true);
                  }
              });
          });
setTimeout(function(){ 
 branch_EditableTable.init();
        	 
}, 100);
          $(document).on('click', "#branch_showhide", function (e) {
              e.preventDefault();
              jQuery('#add-branch').toggle('show');
           });
          

          $(document).on('click', "#cancel", function (e) {
              e.preventDefault();
              jQuery('#add-branch').toggle('hide');
           });
          
          $('#bpin').on('blur', function () {
		     commonFunctions.getCityByPinCode($('#bpin'),$(this).val(), function (result) {
			      $('#bcity').val(result[0].districtname);
				  $('#bState').val(result[0].statename);
       });
          });
          $('#edit_bpin').on('blur', function () {
              commonFunctions.getCityByPinCode($('#edit_bpin'),$(this).val(), function (result) {
                  $('#edit_bcity').val(result[0].districtname);
                  $('#edit_bstate').val(result[0].statename);
              });
          });
         
  		        
      </script>