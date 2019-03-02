<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Deductions</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/table-responsive.css" rel="stylesheet" />
<style>
#deduce_in_chosen, #deductions_type_chosen {
	width: 100% !important;
}

.well {
	border-radius: 0px;
}

.back{
margin-top:10px;
margin-right:10px;
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
				<!-- page start-->
				<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
								<i class=" fa fa-arrow-left"></i> All Settings</a>
				</div>
				<section class="panel">

					<div class="panel-body" style="padding: 2px;">
						<div class="col-lg-7" style="margin-top: 12px;">
							<label class="col-lg-3 control-label" style="margin-top: 4px;">
								Deduction Type </label>
							<div class="col-lg-9 form-group">
								<select class="form-control" id="deductions_type"
									name="deductions_type[]">
									<option value="">Select</option>
									<optgroup label="Enabled Deductions">                                          
<?php
$stmt = mysqli_prepare ( $conn, "SELECT pay_structure_id, display_name,display_flag FROM company_pay_structure WHERE  type='D' AND display_flag =1 " );
$result = mysqli_stmt_execute ( $stmt );
mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name, $display_flag );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	echo "<option   value='" . $pay_structure_id . "'>" . $display_name . "</option>";
}
?>
                                          </optgroup>
								</select> <span class='helpBlock'>To enable Deduction go to
									Masters > <a href='payStructure.php'>Pay Heads</a>
								</span>
							</div>
						</div>

					</div>

				</section>


				<div class="loader">
					<section class="panel" id="ptcontentTable" style="display: none">
						<header class="panel-heading"> Professional tax </header>
						<div class="panel-body">
							<div class="col-lg-12">
								<div class="ptcontent">
									<form class="form-horizontal" id="professiona_tax"
										class="professiona_tax">
										<input type="hidden" name="act" id="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!ptSlab")?>">
										<div class="col-lg-4">
											<label>City</label> <select class="form-control" required
												name="city" id="city" style="margin-bottom: 20px">
												<option value="NO">SELECT THE CITY</option>
                         <?php
																									$stmt = mysqli_prepare ( $conn, "SELECT pt_city FROM pt_slab GROUP BY pt_city" );
																									$result = mysqli_stmt_execute ( $stmt );
																									mysqli_stmt_bind_result ( $stmt, $pt_city );
																									while ( mysqli_stmt_fetch ( $stmt ) ) {
																										echo "<option value='" . $pt_city . "'>" . $pt_city . "</option>";
																									}
																									mysqli_stmt_free_result ( $stmt );
																									mysqli_stmt_close ( $stmt );
																									?>
                         </select>
										</div>
										<div class="col-lg-4">
											<label>FY</label> <select class="form-control" required
												name="financialYear" id="citycheck"
												style="margin-bottom: 20px">
												<option value="NO">SELECT THE Financial Year</option>
                         <?php
																									$stmt = mysqli_prepare ( $conn, "SELECT fin_year FROM pt_slab GROUP BY fin_year" );
																									$result = mysqli_stmt_execute ( $stmt );
																									mysqli_stmt_bind_result ( $stmt, $fin_year );
																									while ( mysqli_stmt_fetch ( $stmt ) ) {
																										echo "<option value='" . $fin_year . "'>" . $fin_year . "</option>";
																									}
																									mysqli_stmt_free_result ( $stmt );
																									mysqli_stmt_close ( $stmt );
																									?>
                         </select>

										</div>
										<div class="col-lg-4">
											<button type="submit" name="change_button" id="change_button"
												class="btn btn-sm btn-success" style="margin-top: 25px;">GO</button>
										</div>
									</form>
								</div>

							</div>
							<div class="clearfix"></div>
							<img src="../img/ajax-loader.gif" id="ajax_loader_getmessages"
								style="display: none;">

							<div class="ptcontents1" style="display: none"></div>
							<div class="ptcontents" style="display: none"></div>

						</div>
					</section>
					<section class="panel" id="itcontentTable" style="display: none;">
						<header class="panel-heading"> Income Tax </header>
						<div class="panel-body">
						<form class="form-horizontal" id="income_tax"
										class="income_tax">
						<input type="hidden" name="act" id="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!itSlab")?>">
						<div class ="col-lg-12">
									<div class="col-lg-4 col-lg-offset-2">
											<label>FY</label> <select class="form-control" required
												name="financialYear" id="citycheck"
												style="margin-bottom: 20px">
												<option value="NO">SELECT THE Financial Year</option>
                        		 				<?php
																									$stmt = mysqli_prepare ( $conn, "SELECT fin_year FROM it_slab GROUP BY fin_year" );
																									$result = mysqli_stmt_execute ( $stmt );
																									mysqli_stmt_bind_result ( $stmt, $fin_year );
																									while ( mysqli_stmt_fetch ( $stmt ) ) {
																										echo "<option value='" . $fin_year . "'>" . $fin_year . "</option>";
																									}
																									mysqli_stmt_free_result ( $stmt );
																									mysqli_stmt_close ( $stmt );
																									?>
                         						</select>

										</div>
										<div class="col-lg-4">
											<button type="submit" name="change_button" id="change_button"
												class="btn btn-sm btn-success" style="margin-top: 25px;">GO</button>
										</div>
										
						</div>
						</form>
						</div>
							<div class="col-lg-12">
								<div class="container itcontents" style="width: 75%;display: none;">
									<section class="error-wrapper"
										style="margin-top: 2%; text-align: left">
									</section>
								</div>
							</div>
						
					</section>
					<section class="panel" id="deductions_type_page"
						style="display: none;">
						<div class="panel-body">
							<div class="col-lg-12">
								<div class="col-lg-6 alert alert-block alert-success fade in Dd">
									<i class="fa fa-check-circle-o"></i> Enabled
								</div>
								<div class="col-lg-6 alert alert-block alert-danger fade in A">
									<i class="fa fa-ban"></i> Disabled
								</div>


								<div class="col-lg-5"></div>
								<div class="col-lg-1">
									<button type="submit" class="btn btn-sm btn-success D">Edit</button>
								</div>

							</div>

							<br> <br> <br>
							<form class="form-horizontal" role="form" method="post"
								id="deductionAddForm">
								<input type="hidden" name="act"
									value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
								<div class="col-lg-12">
									<br>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-5 control-label"> Name</label>
											<div class="col-lg-7 ">
												<input type="hidden" class="form-control" readonly
													name="dIds" id="deduction_id" value="" /> <input
													type="text" class="form-control" readonly
													name="deduction_name" id="deduction_name" value="" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Alias Name</label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control" readonly
													name="aName" id="alias_name" value="" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Is both contribution
												has to deducted?</label>
											<div class="col-lg-7 ">
												<label for="is_both_contribution1"
													class="col-lg-6 control-label"> <input name="isBothC"
													id="is_both_contribution1" value="1" disabled="disabled"
													checked type="radio"> Yes
												</label> <label for="is_both_contribution2"
													class="col-lg-6 control-label"> <input name="isBothC"
													id="is_both_contribution2" disabled="disabled" value="0"
													type="radio"> No
												</label>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Is admin charges has to
												deducted?</label>
											<div class="col-lg-7 ">
												<label for="is_admin_charges1"
													class="col-lg-6 control-label"> <input name="isAdminC"
													id="is_admin_charges1" value="1" disabled="disabled"
													checked type="radio"> Yes
												</label> <label for="is_admin_charges2"
													class="col-lg-6 control-label"> <input name="isAdminC"
													id="is_admin_charges2" disabled="disabled" value="0"
													type="radio"> No
												</label>
											</div>
										</div>



										<div class="form-group" id="max_cf">
											<label class="col-lg-5 control-label">Method of Calculation?</label>
											<div class="col-lg-7 ">
												<label for="Lumsum" class="col-lg-6 control-label"> <input
													name="calMethod" id="Lumsum" type="radio"
													disabled="disabled" value="1" checked>lump sum
												</label> <label for="Percentage"
													class="col-lg-6 control-label"> <input name="calMethod"
													id="Percentage" type="radio" disabled="disabled" value="0">
													Percentage
												</label>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label">What is <em
												class="value"></em> of employee share?
											</label>
											<div class="col-lg-7  input-group">

												<span class="input-group-addon Percentage_i"><i
													class="fa fa-rupee"></i></span> <span
													class="input-group-addon Lumsum_i">%</span>

												<div class="iconic-input right ">
													<i class="left"
														style="font-size: 12px; margin: 9px 11px 6px 0px;"></i> <input
														class="form-control" name="empShare" readonly
														id="employee_share" type="text">
												</div>
											</div>
										</div>
										<div class="form-group Lumsum_i">
											<label class="col-lg-5 control-label">Maximum Limit of
												employee share?</label>
											<div class="col-lg-7  input-group">

												<span class="input-group-addon "><i class="fa fa-rupee"></i></span>
												<div class="iconic-input right ">
													<i class="left"
														style="font-size: 12px; margin: 9px 11px 6px 0px;"></i> <input
														class="form-control" name="maxEmpShare" readonly
														id="max_employee_share" type="text">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">What is <em
												class="value"></em> of employor share?
											</label>
											<div class="col-lg-7  input-group">
												<span class="input-group-addon Percentage_i"><i
													class="fa fa-rupee"></i></span> <span
													class="input-group-addon Lumsum_i">%</span>
												<div class="iconic-input right">
													<input type="text" class="form-control" name="emplrShare"
														readonly id="employer_share" value="" />
												</div>
											</div>
										</div>
										<div class="form-group Lumsum_i">
											<label class="col-lg-5 control-label">Maximum Limit of
												employor share?</label>
											<div class="col-lg-7  input-group">
												<span class="input-group-addon "><i class="fa fa-rupee"></i></span>
												<div class="iconic-input right">
													<input type="text" class="form-control"
														name="maxEmplrShare" readonly id="max_employer_share"
														value="" />
												</div>
											</div>
										</div>

									</div>


									<div class="col-lg-6">

										<div class="form-group">
											<label class="col-lg-5 control-label">What is <em
												class="value"></em> of admincharges?
											</label>
											<div class="col-lg-7  input-group">
												<span class="input-group-addon Percentage_i"><i
													class="fa fa-rupee"></i></span> <span
													class="input-group-addon Lumsum_i">%</span>
												<div class="iconic-input right">
													<input type="text" class="form-control" name="adminC"
														readonly id="admin_charges" value="0.00" />
												</div>
											</div>
										</div>

										<div class="form-group salary_heads">
											<label class="col-lg-5 control-label">Deduce In (Salary Head)</label>
											<div class="col-lg-7">
												<select class="form-control" id="deduce_in"
													disabled="disabled" name="dedIn[]" multiple>
													<!-- option value="GROSS">Gross</option -->
                                          <?php
												$stmt = mysqli_prepare ( $conn, "SELECT pay_structure_id, display_name FROM company_pay_structure WHERE display_flag = 1 && type IN ('A','MP') ORDER BY type" );
												$result = mysqli_stmt_execute ( $stmt );
												mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name );
												while ( mysqli_stmt_fetch ( $stmt ) ) {
													echo "<option value='" . $pay_structure_id . "'>" . $display_name . "</option>";
												}
											?>
                                             </select>
											</div>

										</div>
										<div class="form-group">
											<label class="col-lg-5 control-label">Exemption Who draws
												more than</label>
											<div class="col-lg-7  input-group">
												<span class="input-group-addon "><i class="fa fa-rupee"></i></span>
												<div class="iconic-input right">
													<input type="text" class="form-control" name="calExemp"
														readonly id="cal_exemption" value="" />
												</div>
											</div>
										</div>


										<div class="form-group">
											<label class="col-lg-5 control-label">Payment made to</label>
											<div class="col-lg-7 ">
												<input type="text" class="form-control" readonly
													name="payTo" id="payment_to" value="" />
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label">Frequency</label>
											<div class="col-lg-7 ">
												<select class="form-control" disabled="disabled"
													id="frequency" name="freq">
													<option value="">Select Frequency</option>
													<option value="M">Month</option>
													<option value="Q">Quarter</option>
													<option value="HY">Half-yearly</option>
													<option value="Y">Yearly</option>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label">Due Date Peroid</label>
											<div class="col-lg-7 ">
												<label for="due_in_sub1" class="col-lg-6 control-label"> <input
													name="dueMonth" id="due_in_sub1" type="radio"
													disabled="disabled" value="|C" checked>Current Month
												</label> <label for="due_in_sub2"
													class="col-lg-6 control-label"> <input name="dueMonth"
													id="due_in_sub2" type="radio" disabled="disabled"
													value="|N"> Next Month
												</label>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-5 control-label">Due Date</label>
											<div class="col-lg-7  input-group">
												<input type="text" class="form-control" readonly
													name="dueDate" id="due_in" value="" />

											</div>
										</div>

										<div class="col-lg-6 col-lg-offset-5">
											<button type="button" class="btn btn-sm btn-success DD"
												data-toggle="modal" >Update</button>
											<button type="button" class="btn btn-sm btn-danger cancel">Cancel</button>
										</div>

									</div>
								</div>

								<div class="col-lg-6 col-lg-offset-5"></div>
							
							</form>
						</div>
					</section>
				</div>
				<!-- other Deduction Page End-->


				<!-- page end-->
			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!--script for this page only-->
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>


	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          jQuery(document).ready(function () {                
              $(".loader").loading(false);
              $('.D,.DD,.A,.cancel,.Dd,#ptcontentTable,#itcontentTable').hide();
              $('#add-deduction-toggle').toggle('hide');
              $('#showhide').on('click', function (event) {
                  $('#add-deduction-toggle').toggle('show');
              });
              $('#deduce_in,#deductions_type').chosen();
            
              $("#deductions_type_page").hide();
              $(".Lumsum_i").hide();

              $('#showhide').on('click', function (event) {
                  $('#add-loan').toggle('show');
              });

                $('.D').on('click', function (event) {
                $('.DD').show();
                $('.Dd').hide();
                $('.cancel').show();
                $('.D').hide();
                $('#alias_name').prop('readonly', false);
                $("input[type=cal]").attr('disabled', false);
                $("input[type=due_in_sub]").attr('disabled', false);
                $("input[type=radio]").attr('disabled', false);

                 $('#payment_to').prop('readonly', false);
                 $('#employee_share').prop('readonly', false);
                 $('#employer_share').prop('readonly', false);
                 $('#max_employee_share').prop('readonly', false);
                 $('#max_employer_share').prop('readonly', false);
                 $('#cal_exemption').prop('readonly', false);

                 $('#admin_charges').prop('readonly', false);
                 $("#frequency").prop("disabled", false); 
                 $('#due_in').prop('readonly', false);
                 $("#deduce_in").prop("disabled", false);  
                 $('#deduce_in').prop('disabled', false).trigger("chosen:updated");

                 });

             $('.cancel').on('click', function (event) {
            	 applydeductionData();
            	   $('.cancel').hide();
                   $('.DD').hide();
                   $('.D').show();
                   $('.Dd').show();
                   $('#alias_name').prop('readonly', true);
                   $("input[type=cal]").attr('disabled', true);
                   $("input[type=due_in_sub]").attr('disabled', true);
                   $("input[type=radio]").attr('disabled', true);
                   $('#payment_to').prop('readonly', true);
                   $('#employee_share').prop('readonly', true);
                   $('#max_employee_share').prop('readonly', true);
                   $('#max_employer_share').prop('readonly', true);
                   $('#cal_exemption').prop('readonly', true);
                   $('#employer_share').prop('readonly', true);
                   $('#admin_charges').prop('readonly', true);
                   $("#frequency").prop("disabled", true); 
                   $('#due_in').prop('readonly', true);
                   $('#deduce_in').prop('disabled', true).trigger("chosen:updated");
              });

              $('#Lumsum').on('click', function (event) {
                  $(".Lumsum_i").hide();
                  $(".Percentage_i").show();
                  $('.value').html('Amount');
                  $('#max_employee_share,#max_employer_share').val('0');
              });

              $('#Percentage').on('click', function (event) {
                  $(".Lumsum_i").show();
                  $(".Percentage_i").hide();
                  $('.value').html('Percentage');
                 
              });
              $('#salary').on('click', function (event) {
                 $(".salary_heads").show();
                });
              $('#gross').on('click', function (event) {
                  $(".salary_heads").hide();
               });
           

          });

$('#professiona_tax').on('submit', function (event) {
	event.preventDefault();
	 if($('#citycheck').val() !='NO' && $('#city').val() !='NO'){
		  $('#ptcontentTable').show();
		  $('.ptcontents,.ptcontents1,#ajax_loader_getmessages').show();
	  $.ajax({
            type: "POST",
            url: "php/deduction.handle.php",
            cache: false,
            data: $('#professiona_tax').serialize(),
            beforeSend:function(){
            	$('#change_button').button('loading'); 
             },
            complete:function(){
            	$('#change_button').button('reset');
             },
            success: function (data) {
              $('#itcontentTable,#ajax_loader_getmessages').hide();
            	var json_obj = JSON.parse(data);
            	if(json_obj[0] == 'success'){
                var html = '<table class="table table-striped table-hover table-bordered" id="ptSlab"><thead><tr><th>From Value</th><th>To Value</th><th>Rate</th>';
        	    html += '</tr></thead><tbody>';
        	    $('.ptcontents,.ptcontents1').empty();
        	    for (var i = 0, len = json_obj[2].length; i < len; ++i) {
            	    if(json_obj[2][i].eligibility == 'M'){
            	    	var show_day = 'Monthly';
            	    }else if(json_obj[2][i].eligibility == 'HY'){
            	    	var show_day = 'Half-yearly';
            	    }
        	    	var well = '<div id="welld" class="well show" style="margin-bottom: 0px;padding: 0em 0em 0px 12px;background: #fcfcfc;"><h5>Eligibility: <em>'+show_day+'</em></h5></div>';	
                	            	    
        		   html += '<tr>';
            	 html += '<td>'+json_obj[2][i].from_value+'</td><td>'+json_obj[2][i].to_value+'</td><td>'+json_obj[2][i].rate+'</td>'; 
             	  html += "</tr>";
        		}
        		html += '</tbody></table>';
        		$('.ptcontents1').append(well);
        		$('.ptcontents').append(html);
        		
            	}else if(json_obj[0] == 'error'){
            		$('.ptcontents,.ptcontents1').html("");
            }
            	//var oTable = $('#ptSlab').dataTable().fnFakeRowspan(11);
                 }
  	  });
	 }
});

$('#income_tax').on('submit',function(event){
       event.preventDefault();
      $('.itcontents').show();
        $.ajax({
           type: "POST",
           url: "php/deduction.handle.php",
           cache: false,
           data: $('#income_tax').serialize(),
           beforeSend:function(){
           	$('#change_button').button('loading'); 
            },
           complete:function(){
           	$('#change_button').button('reset');
            },
           success: function (data) {
        	   $('#ptcontentTable').hide();
        	   var json_obj = JSON.parse(data);
        	   if(json_obj[0] == 'success'){
            	   
        		  var html='<table class="table table-striped table-hover table-bordered" id="itSlab"><thead><tr id="slab-table-head"><th>Annual Income ( &#8377 )</th><th>Tax Rate</th><th>Rate</th>';
	              html += '</tr></thead><tbody>';
	              $('.itcontents').empty();
	              for (var i = 0, len = json_obj[2][0].length; i < len; ++i) {
	            	  html += '<tr>';
	             	 html += '<td>'+json_obj[2][0][i].from_value+'</td><td>'+json_obj[2][0][i].to_value+'</td><td>'+json_obj[2][0][i].rate+'</td>'; 
	              	  html += "</tr>";
	              }
	              html += '</tbody></table>';
	          	 $('.itcontents').append(html);
	          	 
        	   var html='<table class="table table-striped table-hover table-bordered"><thead><tr id="slab-table-head"><th>Name</th><th>Value</th>';
	              html += '</tr></thead><tbody>';
	              for (var i = 0, len = json_obj[2][1].length; i < len; ++i) {
		            	 
	            	  html += '<tr>';
	             	 html += '<td>'+json_obj[2][1][i].name+'</td><td>'+json_obj[2][1][i].value+'</td>'; 
	              	  html += "</tr>";
	              	  
	              }
	              html += '</tbody></table>';
		          	$('.itcontents').append(html);
	  
           } else if(json_obj[0] == 'error'){
       		$('.itcontents').html("");
               }
         }      
     });
});

          
                       $('#deductions_type').on('change', function (event) {
                    	   $("#deductions_type_page").hide();
                    	    applydeductionData();
        	                });

        	            
        	            $(document).on('click', ".DD", function (e) {
                            e.preventDefault();
                                BootstrapDialog.show({
            	                title:'Confirmation',
                                message: 'Are Sure you want to update',
                                closable: true,
                                closeByBackdrop: false,
                                closeByKeyboard: false,
                               buttons: [{
                                    label: 'Update',
                                    cssClass: 'btn-sm btn-success',
                                    autospin: true,
                                    action: function(dialogRef){
                                    	 $.ajax({
                 		                    dataType: 'html',
                 		                    type: "POST",
                 		                    url: "php/deduction.handle.php",
                 		                    cache: false,
                 		                   data: $('#deductionAddForm').serialize(),
                 		                    complete:function(){
               		                    	 dialogRef.close();
               		                      },
               		                   success: function (data) {
               	                        data1 = JSON.parse(data);
               	                        if (data1[0] == "success") {
               	                           $('.rr').click();
               	                            $('.DD').hide();
               	                            $('.D').show();
               	                            $('.cancel').hide();
               	                            $('.Dd').show();
               	                            $('#alias_name').prop('readonly', true);
               	                            $("input[type=cal]").attr('disabled', true);
               	                            $("input[type=due_in_sub]").attr('disabled', true);
               	                            $("input[type=radio]").attr('disabled', true);
               	                            $('#payment_to').prop('readonly', true);
               	                            $('#employee_share').prop('readonly', true);
               	                            $('#max_employee_share').prop('readonly', true);
               	                            $('#max_employer_share').prop('readonly', true);
               	                            $('#cal_exemption').prop('readonly', true);
               	                            $('#employer_share').prop('readonly', true);
               	                            $('#admin_charges').prop('readonly', true);
               	                            $("#frequency").prop("disabled", true);
               	                            $('#due_in').prop('readonly', true);
               	                            $('#deduce_in').prop('disabled', true).trigger("chosen:updated");
               	                         BootstrapDialog.alert("Deductions updated successfull");
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

function applydeductionData(){
	 var selected_id = $('#deductions_type :selected').val();
    var selected_id_i = $("#deductions_type option:selected").text();
    if(selected_id=="c_pt"){
    	$('#ptcontentTable').show(); 
    	  $('#itcontentTable').hide();
    }else if(selected_id=="c_it"){
        $('#ptcontentTable').hide();
        $('#itcontentTable').show();
			        
	    }else{
    $.ajax({
        type: "POST",
        url: "php/deduction.handle.php",
        cache: false,
        data: { act: '<?php echo base64_encode($_SESSION['company_id']."!select");?>', dIds:selected_id },
          beforeSend:function(){
        	$(".loader").loading(true);
          },
          complete:function(){
            $(".loader").loading(false);
          },
        success: function (data) {
            
            var json_obj = $.parseJSON(data); //parse JSON
            if (json_obj[2].display_flag == '1' && json_obj[2].pay_structure_id !== 'labour_welfare_fund'
            && json_obj[2].pay_structure_id !== 'c_pt' && json_obj[2].pay_structure_id !== 'c_it') {
            	 $("#deductions_type_page").show();
            	$('#ptcontentTable,#itcontentTable').hide();
                $('.D').show();
                $('.cancel').hide();
                $('.A,.DD').hide();
                $('.Dd').show();
                $('#deduction_id').val(json_obj[2].deduction_id);
                        $('#deduction_name').val(selected_id_i);
                        $('#alias_name').val(json_obj[2].alias_name);
                        console.log( json_obj[2].is_both_contribution);
var is_both= json_obj[2].is_both_contribution==1?$("input[name=isBothC][value=" + json_obj[2].is_both_contribution + "]").prop('checked', true):$("input[name=isBothC][value=" + json_obj[2].is_both_contribution + "]").prop('checked', true);
var is_admin_charges= json_obj[2].is_admin_charges==1?$("input[name=isAdminC][value=" + json_obj[2].is_admin_charges + "]").prop('checked', true):$("input[name=isAdminC][value=" + json_obj[2].is_admin_charges + "]").prop('checked', true);
                       
                        var str = json_obj[2].employee_share;
                        var myString = str.substr(str.indexOf("|") + 1)
                        if (myString !== 'P') {
                            $("#Lumsum").click();
                            $("input[name=calMethod][value='1']").prop('checked', true);

                        } else {
                            $("#Percentage").click();
                            $("input[name=calMethod][value='0']").prop('checked', true);
					   }
						   
                        var ee_employee_share = json_obj[2].employee_share.substr(0, json_obj[2].employee_share.indexOf('|'));
                        $('#employee_share').val(ee_employee_share);

                        $('#max_employee_share').val(json_obj[2].max_employee_share);
                        $('#max_employer_share').val(json_obj[2].max_employer_share);
                        $('#cal_exemption').val(json_obj[2].cal_exemption);


                        var ee_employer_share = json_obj[2].employer_share.substr(0, json_obj[2].employer_share.indexOf('|'));
                        $('#employer_share').val(ee_employer_share);

                        var e_admin_charges = json_obj[2].admin_charges.substr(0, json_obj[2].admin_charges.indexOf('|'));
                        $('#admin_charges').val(e_admin_charges);
                        $('#deduce_in option:selected').removeAttr('selected');
                        $('#deduce_in').trigger('chosen:updated');


                         $.each(json_obj[2].deduce_in.split(","), function (i, e) {

                            $("#deduce_in option[value='" + e + "']").prop("selected", true);
                        });
                        $("#deduce_in").trigger("chosen:updated"); //for drop down
                        //$('#salary').click();
                        

                        var str = json_obj[2].due_in;
                        var due_in = str.substr(str.indexOf("|") + 1)
                        if (due_in == 'C') {
                            $("input[name=due_in_sub]").prop('checked', false);
                            $("input[name=due_in_sub][value='Current Month']").prop('checked', true);
                        } else {
                            $("input[name=due_in_sub]").prop('checked', false);
                            $("input[name=due_in_sub][value='Next Month']").prop('checked', true);
                        }

                        $('#payment_to').val(json_obj[2].payment_to);
                        $('#frequency option[value='+json_obj[2].frequency +']').prop('selected', true);
                        
                        var e_due_in = json_obj[2].due_in.substr(0, json_obj[2].due_in.indexOf('|'));
                        $('#due_in').val(e_due_in);

            }
        }

    });  
    }


}
        	       
      </script>


</body>
</html>
