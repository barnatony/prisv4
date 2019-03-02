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

<title>Asset Tracker</title>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />

<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/datepicker.css" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
#atCondition_chosen, #atType_chosen, #status_chosen,
	#e_atCondition_chosen, #e_atType_chosen, #e_status_chosen {
	width: 100% !important;
}

@media ( min-width : 992px) {
	.modal-lg {
		width: 900px;
	}
}
</style>

</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php include("header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
					<header class="panel-heading">
						Asset Tracker
						<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn btn-sm btn-info"
								style="margin-top: -5px;">
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</header>
					<form class="form-horizontal" role="form" method="post"
						id="assetTrackerForm">
						<input type="hidden" name="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
						<div class="col-lg-12">
							<div class="panel-body" id="add-assetTracker-toggle"
								style="display: none">
								<div class="col-lg-6">

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Name</label>
										<div class="col-lg-7">
											<input class="form-control" type="text" name="atName" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Type</label>
										<div class="col-lg-7">
											<select class="form-control" id="atType" name="atType">
												<option value="">Select Type</option>
												<option value="laptop">Laptop</option>
												<option value="mobile">Mobile</option>
												<option value="car">Car</option>
												<option value="bike">Bike</option>
												<option value="pendrive">Pen drive</option>
												<option value="harddisk">Hard disk</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Condition</label>
										<div class="col-lg-7">
											<select class="form-control" name="atCondition"
												id="atCondition">
												<option value="">Select condition</option>
												<option value="owned rented">Owned Rented</option>
												<option value="rented">Rented</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Purchase Date</label>
										<div class="col-md-7 col-xs-12 input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input class="form-control" name="purDate" id="purDate"
												type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Cost</label>
										<div class="col-md-7 col-xs-12 input-group">
											<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
											<input class="form-control" data-type="rupee" name="cost"
												type="text">

										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Warranty Exp
											Date</label>
										<div class="col-md-7 col-xs-12 input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input class="form-control" name="wed" id="wed" type="text">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Serial Number</label>
										<div class="col-lg-7">
											<input class="form-control" type="text" name="sNo" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Manufacturer</label>
										<div class="col-lg-7">
											<input class="form-control" type="text" name="mfr" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Model</label>
										<div class="col-lg-7">
											<input class="form-control" type="text" name="model" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label">Status</label>
										<div class="col-lg-7">
											<select class="form-control" name="status" id="status">
												<option value="">Select status</option>
												<option value="available">Available</option>
												<option value="maintenance">Maintenance</option>
												<option value="damaged">Damaged</option>
												<option value="locked">Locked</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-5 col-sm-5 control-label"></label>
										<div class="col-lg-6 col-lg-offset-6">
											<button type="button"
												class="btn btn-sm btn-danger pull-right closeModel"
												style="margin-left: 1%">Cancel</button>
											<button type="submit"
												class="btn btn-sm btn-success pull-right" id="atSubmit">Add</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>


					<div class="panel-body">
						<div class="space15"></div>
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover table-bordered cf"
									id="assetTracker-sample">
									<thead class="cf">
										<tr>
											<th>Name</th>
											<th>Type</th>
											<th>Condition</th>
											<th>PURDate</th>
											<th>Cost</th>
											<th>WEDate</th>
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
			</section>
			<!-- MOdel start For Assettracker edit  -->
			<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
				tabindex="-1" id="assetModel" class="modal fade">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button aria-hidden="true" data-dismiss="modal" class="close"
								type="button">×</button>
							<h4 class="modal-title">Edit AssetTracker</h4>
						</div>
						<div class="modal-body">
							<form class="form-horizontal" role="form" method="post"
								id="assetTrackerEditForm">
								<input type="hidden" name="act"
									value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
								<input type="hidden" name="assetId" id="assetId" />
								<div class="panel-body">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Name</label>
											<div class="col-lg-7">
												<input class="form-control" type="text" id="atName"
													name="atName" />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Type</label>
											<div class="col-lg-7">
												<select class="form-control" id="e_atType" name="atType">
													<option value="">Select Type</option>
													<option value="laptop">Laptop</option>
													<option value="mobile">Mobile</option>
													<option value="car">Car</option>
													<option value="bike">Bike</option>
													<option value="pendrive">Pen drive</option>
													<option value="harddisk">Hard disk</option>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Condition</label>
											<div class="col-lg-7">
												<select class="form-control" id="e_atCondition"
													name="atCondition">
													<option value="">Select condition</option>
													<option value="new">New</option>
													<option value="old">Old</option>
													<option value="rented">Rented</option>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Purchase Date</label>
											<div class="col-md-7 col-xs-12 input-group">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control" name="purDate" id="e_purDate"
													type="text">
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Cost</label>
											<div class="col-md-7 col-xs-12 input-group">
												<span class="input-group-addon"><i class="fa fa-rupee"></i></span>
												<input class="form-control" id="cost" data-type="rupee"
													name="cost" type="text">

											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Warranty Exp
												Date</label>
											<div class="col-md-7 col-xs-12 input-group">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control" name="wed" id="e_wed"
													type="text">
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Serial Number</label>
											<div class="col-lg-7">
												<input class="form-control" type="text" id="sNo" name="sNo" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Manufacturer</label>
											<div class="col-lg-7">
												<input class="form-control" type="text" id="mfr" name="mfr" />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Model</label>
											<div class="col-lg-7">
												<input class="form-control" type="text" id="model"
													name="model" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Quantity</label>
											<div class="col-lg-7">
												<input class="form-control" type="text" id="qty" name="qty" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 col-sm-5 control-label">Status</label>
											<div class="col-lg-7">
												<select class="form-control" id="e_status" name="status">
													<option value="">Select status</option>
													<option value="new">Normal</option>
													<option value="old">Under Maintenance</option>
													<option value="rented">Damaged</option>
												</select>
											</div>
										</div>

									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-sm btn-danger  closeModel">Cancel</button>
									<button type="submit" class="btn btn-sm btn-success"
										id="ateditSubmit">Update</button>
								</div>

							</form>

						</div>
					</div>
				</div>
			</div>

			<!--main content end-->
			<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
		</section>
	</section>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script type="text/javascript">
           $(document).ready(function () {
        	  // $('#add-assetTracker-toggle').toggle('hide');
               $('#atCondition,#atType,#status,#e_atCondition,#e_atType,#e_status').chosen();
               $('#purDate,#wed,#e_purDate,#e_wed').datepicker({
                   format: 'dd/mm/yyyy'                 	 
               }).on('changeDate', function(e){
                       if(e.viewMode == 'days')
                  		  $(this).datepicker('hide');
                    }); 
               var EditableTable = function () {

                   return {

                       //main function to initiate the module
                       init: function () {

                           var oTable = $('#assetTracker-sample').dataTable({
                               "aLengthMenu": [
                                   [5, 15, 20, -1],
                                   [5, 15, 20, "All"] // change per page values here
                               ],

                               // set the initial value
                               "iDisplayLength": 5,
                               "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                               "bProcessing": true,
                               "bServerSide": true,
                               "sAjaxSource": "php/assetTracker-view.php",
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
                                      { "sName": "claim_name" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
                                       {"sName": "alias_name" },
                                       { "sName": "category_type" },
                                       { "sName": "sub_type" },
                                       { "sName": "class" },
               						   { "sName": "amount_to" },
                                       { "sName": "enabled", "bSortable": false },
                                         ],"aoColumnDefs": [{ "mRender": function 
                   	                        (data, type, row) {
                                        	  if(row[3]=row[3]){
                                        		var parts =row[3].split('-');
                      	                        var purDate=parts[2]+"/"+parts[1]+"/"+parts[0]; 
                      	                        return purDate;
                    	                        }                	                        
                       	                                                        
               	                         },
               	                             "aTargets": [3]
               	                         },{ "mRender": function 
                    	                        (data, type, row) {
                                       	    if(row[5]=row[5]){
                   	                        var parts =row[5].split('-');
                         	                    var wedate=parts[2]+"/"+parts[1]+"/"+parts[0]; 
                         	                    return wedate; 
                   	                        }
               	                                                     
              	                         },
              	                             "aTargets": [5]

              	                         }],
                               "oTableTools": {
                                   "aButtons": [
                               {
                                   "sExtends": "pdf",
                                   "mColumns": [0, 1, 2, 3, 4, 5, 6],
                                   "sPdfOrientation": "landscape",
                                   "sPdfMessage": "Branch Details"
                               },
                               {
                                   "sExtends": "xls",
                                   "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
                               }
                            ],
                                   "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                               }

                           });

                           $('#assetTracker-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                                             <input class="form-control medium" id="searchInput" type="text">\
                                                             <span class="input-group-btn">\
                                                               <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                                             </span>\
                                                             <span class="input-group-btn">\
                                                               <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                                             </span>\
                                                         </div>');
                           $('#assetTracker-sample_processing').css('text-align', 'center');
                           //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
                           jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
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

                           //Enable claims
                            $(document).on('click', "#assetTracker-sample a.disable", function (e) {
                               e.preventDefault();
                               var nRow = $(this).parents('tr')[0];
                               var data = oTable.fnGetData(nRow);
                                BootstrapDialog.show({
               	                title:'Confirmation',
                                   message: 'Are Sure you want to Disable <strong>'+ data[0] +'<strong>',
                                   buttons: [{
                                       label: 'Disable',
                                       cssClass: 'btn-sm btn-success',
                                       autospin: true,
                                       action: function(dialogRef){
                                       	 $.ajax({
                    		                    dataType: 'html',
                    		                    type: "POST",
                    		                    url: "php/assetTracker.handle.php",
                    		                    cache: false,
                    		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', assetId:  data[07]  },
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


                           //Disable claims
                           $(document).on('click', "#assetTracker-sample a.enable", function (e) {
                               e.preventDefault();
                               var nRow = $(this).parents('tr')[0];
                               var data = oTable.fnGetData(nRow);
                               BootstrapDialog.show({
               	                title:'Confirmation',
                                   message: 'Are Sure you want to Enable <strong>'+ data[0] +'</strong>',
                                   buttons: [{
                                       label: 'Enable',
                                       cssClass: 'btn-sm btn-success',
                                       autospin: true,
                                       action: function(dialogRef){
                                       	 $.ajax({
                    		                    dataType: 'html',
                    		                    type: "POST",
                    		                    url: "php/assetTracker.handle.php",
                    		                    cache: false,
                    		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>',assetId:data[07]},
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




                            $(document).on('click', "#assetTracker-sample a.asset_edit", function (e) {
           	                e.preventDefault();
           	                 /* Get the row as a parent of the link that was clicked on */
           	                var nRow = $(this).parents('tr')[0];
           	                var data = oTable.fnGetData(nRow);
           	                $('#atName').val(data[0]);
           	                $("#e_atType option[value='" + data[1] + "']").prop("selected", true);
                            $("#e_atType").trigger("chosen:updated"); //for drop down
                            $("#e_atCondition option[value='" + data[2] + "']").prop("selected", true);
                            $("#e_atCondition").trigger("chosen:updated"); //for drop down
                            var parts =data[3].split('-');
  	                        var purDate=parts[2]+"/"+parts[1]+"/"+parts[0]; 
                            $('#e_purDate').val(purDate);
                            $('#cost').val(data[4]);
                            var parts =data[5].split('-');
  	                        var weDate=parts[2]+"/"+parts[1]+"/"+parts[0]; 
                            $('#e_wed').val(weDate);
                            $('#sNo').val(data[8]);
                            $('#mfr').val(data[9]);
                            $('#model').val(data[10]);
                            $('#qty').val(data[11]);
                            $("#e_status option[value='" + data[12] + "']").prop("selected", true);
                            $("#e_status").trigger("chosen:updated"); //for drop down
                            $('#assetId').val(data[07]);
              	            });

                            //Edit claims
                           $('#assetTrackerEditForm').on('submit', function (e) {
                               e.preventDefault();
                                   	 $.ajax({
                                       datatype: "html",
                                       type: "POST",
                                       url: "php/assetTracker.handle.php",
                                       cache: false,
                                       data: $('#assetTrackerEditForm').serialize(),
                                       beforeSend:function(){
                                        	$('#ateditSubmit').button('loading'); 
                                         },
                                         complete:function(){
                                           	$('#ateditSubmit').button('reset');
                                         },
                                       success: function (data) {
                                           data1 = JSON.parse(data);
                                           if (data1[0] == "success") {
                                               $('.close').click();
                                           	  BootstrapDialog.alert(data1[1]);
                                              oTable.fnDraw();
                                            
                                              }
                                        }

                                   });
                           
                           });

                           //AssetTracker Map Form
                           $('#assetTrackerForm').on('submit', function (e) {
                               e.preventDefault();
                               	 $.ajax({
                               		 dataType: 'html',
                	               		type: "POST",
                	               		data: $('#assetTrackerForm').serialize(),
                                        url: "php/assetTracker.handle.php",
                                        cache: false,
                                        beforeSend:function(){
                                        	$('#atSubmit').button('loading'); 
                                         },
                                        complete:function(){
                                        	$('#atSubmit').button('reset');
                                         },
                                        success: function (data) {
                                           data1 = JSON.parse(data);
                                           if (data1[0] == "success") {
                                               $('#assetTrackerForm')[0].reset();
                                               $('#add-assetTracker-toggle').toggle('hide');
                                               BootstrapDialog.alert(data1[1]);
                                               oTable.fnDraw();
                                               $('#atCondition,#atType,#status').prop('selected', false).trigger('chosen:updated');
                                               $('#atCondition,#atType,#status').trigger('chosen:updated');
                                           	   }
                                        }

                                   });
                                         
                           });


                       }

                   };

               } ();
               EditableTable.init();
               
           });

           $('.closeModel').on('click', function (e) {
        	   e.preventDefault();
               $('#add-assetTracker-toggle').hide();
               $('.close').click();
              // $('#showhide').show();
           });
           
           $('#showhide').on('click', function () {
               $('#add-assetTracker-toggle').toggle('show');
           });

      </script>

</body>
</html>
