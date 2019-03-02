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

		<title>Ctcscreen</title>

		<!-- Bootstrap core CSS -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/bootstrap-reset.css" rel="stylesheet">
		<link href="../css/chosen.css" rel="stylesheet">
		<link href="../css/chosen-bootstrap.css" rel="stylesheet">
		<!--external css-->
		<link href="../css/font-awesome.min.css" rel="stylesheet" />
		<!-- Custom styles for this template -->
		<link href="../css/style.css" rel="stylesheet">
		<link href="../css/style-responsive.css" rel="stylesheet" />
		
		<style>
			
		</style>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
		<!--[if lt IE 9]>
		<script src="../js/html5shiv.js"></script>
		<script src="../js/respond.min.js"></script>
		<![endif]-->
		</head>

		<body>

		<section id="container" class="">
		<!--header start-->
		<?php include("header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");
            			require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/employee.class.php");
												
												$employee = new Employee ();
												$employee->conn = $conn;
												$strRestriction = $employee->createEmployeeRestriction ();
												// Allowances and Deduction
												Session::newInstance ()->_setGeneralPayParams ();
												$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
												Session::newInstance ()->_setMiscPayParams ();
												$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
											?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<!-- row for two pannels -->
				
					<?php if(isset($_REQUEST['employee_id'])){?>
			<header class=" panel panel-heading panel-body"><label class="col-lg-12 control-label">Structure Salary for :</label><div class ="col-lg-7 col-lg-offset-2 input-group emp_Id"  style="color: #797979;">
					<form class="form-horizontal " id="ctc-data"  name="ctcData" method="post">
									<input type="hidden"  id="employee_id" name="employee_id" value="<?php echo $_REQUEST['employee_id'];?>" />
											<?php 	
											$stmt = mysqli_prepare ( $conn, "SELECT CONCAT(w.employee_name,' [',w.employee_id,']')employee,des.designation_name,dept.department_name
																												FROM employee_work_details w
																												INNER JOIN company_designations des
																												ON w.designation_id = des.designation_id
																												INNER JOIN company_departments dept
																												ON w.department_id = dept.department_id
																												WHERE w.employee_id = '$_REQUEST[employee_id]';   " );
																				$result = mysqli_stmt_execute ( $stmt );
																				mysqli_stmt_bind_result ( $stmt,$employee,$designation_name,$department_name);
																				while ( mysqli_stmt_fetch ( $stmt ) ) {
																						echo'<div class="col-lg-offset-1 emp">'.$employee. '</div>';
																						echo'<div class="col-lg-offset-1 des">' .$designation_name.'</div>';
																						echo'<div class="col-lg-offset-1 dep">' . $department_name.'</div>';
																				}
																				mysqli_stmt_free_result ( $stmt );
																				mysqli_stmt_close ( $stmt );
											?>									
			  </form>
				</div>
				</header>
									<?php }else{?>
				<header class=" panel panel-heading panel-body struct_header"><label class="col-lg-12 control-label">Structure Salary for :</label><div class ="col-lg-7 col-lg-offset-2 input-group emp_Id"  style="color: #797979;">
					<form class="form-horizontal " id="ctc-data"  name="ctcData" method="post">
									 				
												<?php 	echo ' <select class="form-control empSelectBox " required
														name="employee_id" id="employee_id" value="" style="margin-bottom: 20px;">';
														
                        
																									$stmt = mysqli_prepare ( $conn, "SELECT w.employee_id,w.employee_name
																																	FROM employee_work_details w 
																																	INNER JOIN employee_salary_details s
																																	ON w.employee_id = s.employee_id
																																	WHERE s.employee_salary_amount=0;  " );
																								
																									$result = mysqli_stmt_execute ( $stmt );
																									mysqli_stmt_bind_result ( $stmt ,$employee_id,$employee_name);
																									while ( mysqli_stmt_fetch ( $stmt ) ) {
																										echo "<option value='" . $employee_id . "'>$employee_name  [$employee_id]</option>";
																									}
																									mysqli_stmt_free_result ( $stmt );
																									mysqli_stmt_close ( $stmt );
																									
                         								echo  ' </select>';  
                         		
			
                       						 ?>
                       </form>
				</div>
				</header>		
									
									<?php }?>
						
                         
			<div class="row">
				<div class="col-lg-6">
					<section class="panel">
							<div class="panel-body">
							<form class="form-horizontal " id="ctc-data"  name="ctcData" method="post">
								
										<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-4 control-label">Salary Type</label>
													<div class="col-lg-8">
															<label for="ctcval" class="col-lg-5 control-label" title="CTC">
															<input name="salary_type" id="ctcval" value="ctc" type="radio">
															 CTC</label> 
															<label for="salary_type" class="col-lg-5 control-label" title="Gross">
															<input name="salary_type"id="salary_type" value="monthly" type="radio">
															GROSS</label>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-4 control-label">Input Type</label>
													<div class="col-lg-8">
															<label for="monthly" class="col-lg-5 control-label" title="Monthly">
															<input name="input_type" class="ip" id="monthly" value="monthly" type="radio"> Monthly</label> 
															<label for="annual" class="col-lg-5 control-label" title="Annual"><input name="input_type" class="ip" id="annual" value="annual" type="radio">
															Annual</label>
													</div>
												</div>
												
												<div class="form-group" id="slab_select_box">
														<label class="col-lg-4 control-label">Slab Name</label>
														<div class="col-lg-8">
															<select id="slab_opt" name="slab" class="form-control" required>			
															</select> <span class="help-block" id="minimum_salary_div"
																style="display: none">Applicable only for salary gt <span
																id="min_salary_amount"></span></span>
														</div>
											</div>
											
												<?php 
												$stmt = mysqli_prepare ( $conn, "SELECT struct.display_flag
																				FROM company_pay_structure struct
																				WHERE struct.pay_structure_id='c_epf' AND struct.display_flag ='0';" );
																				$result = mysqli_stmt_execute ( $stmt );
																				mysqli_stmt_bind_result ( $stmt,$display_flag);
																				echo "$display_flag";
																		?>
																								
											<?php  if($dispay_flag="1"){?>													
												<div class="form-group">
													<label class="col-lg-4 control-label" for="pf_limit" style="padding-right: 0">PF</label>
													<div class="col-lg-8">
															<select class="form-control" id="pf_limit" name="pf_limit">
			                                               	    <option value="1" selected>Apply PF Limit</option>
																<option value="0">Don't Apply PF Limit</option>
																<option value="-1">Exclude from PF</option>
															</select>
													</div>
												</div>
											<?php }?>	
											
										<br>
										<div class="dan"></div>
										<button class="btn btn-default pull-right pro" type="button" id="provideIp">Provide Inputs</button>
								</div>
						</form>	
					 </div>
					</section>								
				</div>
						 <div class="col-lg-6" id="getCTCcontent"></div>
											
			</div><!-- row end -->
			<!-- table creation  starts here-->
									<div id="getTablecontent" ></div>
			<!-- table creation end-->	
			<!-- perquisites starts here -->
		
								  <div class="col-lg-12 panel panel-body perqdetails hidden">
								   
											        <input type="hidden" name="act"
														value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />
											        <input type="checkbox" aria-label="..." id="perq" name="perq_check" checked>&nbsp;&nbsp; <span class="perq-text"> Perquisites </span>
											        <div class="perqs" id="perqsdata">
													      
											        </div>
											        <button class="btn btn-default pull-right" type="button" id="map_salary" style="margin-right:455px;">Map Salary</button>
									</div>
					<!-- perquisites end -->
				
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
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/html2canvas.js"></script>
	<script src="../js/jspdf.debug.js"></script>
	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script type="text/javascript"></script>
	
	<script>
	$(document).ready(function(){
		$("#slab_opt,#pf_limit").chosen();
		if($('.empSelectBox').val()==null){
			$('.struct_header').hide();
			}
		<?php if(isset($_REQUEST['employee_id'])){?>	
		var employee_id='<?php echo $_REQUEST['employee_id']?>';
		<?php }else{?>
		var employee_id =$('#employee_id').val();
		<?php }?>
		$.ajax({
             dataType: 'html',
             type: "POST",
             url: "php/employee.handle.php",
             cache: false,
             data: {act:'<?php echo base64_encode($_SESSION['company_id']."!salary_details");?>', employeeId:employee_id},
             success: function (data) {
            	 var json_obj = $.parseJSON(data);
            		//for perquisites table creation
					var perqusites =json_obj[2][2].data;
					if(perqusites!= "No Data Found"){
					$(".perqs").html();
					 var html ='<form id="perq_s" method="POST" class="form-horizontal" role="form"><input type="hidden" name="act" value="<?php echo base64_encode($_SESSION['company_id']."!perquisiteMapping");?>"/><input type="hidden" class="form-control emp_new"	name="empID"	value="'+employee_id+'" /><table class="table-responsive table forperq" id="perqstable" name="perqstable"><thead><tr class="headerReload"><th class=""></th><th class="emptyDiv">Name</th><th class="deduc_amt">Dedu Amount</th><th class="deduc_type">Dedu type</th></tr></thead><tbody>';
             	 for (var i = 0, len = perqusites.length; i < len;i++) {
             			html += '<tr>';

							if(perqusites[i].checked==0){
								//disabled coding
								html += '<td class="perqs"><input type="checkbox" id="perqs" name="preqs[]" value="'+perqusites[i].pid+'" class="check"></td>';
								html += '<td>'+perqusites[i].name+'</td>';
								html += '<td><input type="text" class="form-control" name="ded_amount[]" value="'+perqusites[i].value+'" disabled></td>';
								html += '<td><select id="deduc_type" name="deduc_type[]" class="deduc_type form-control" disabled><option value="monthly">monthly</option><option value="annually">annually</option></select></td>';
							}else{
								//enabled coding
								html += '<td class="perqs"><input type="checkbox" id="perqs" name="preqs[]" value="'+perqusites[i].pid+'" class="check" checked></td>';
								html += '<td>'+perqusites[i].name+'</td>';
								html += '<td><input type="text" class="form-control" name="ded_amount[]" value="'+perqusites[i].value+'"></td>';
								if(perqusites[i].dedu_type=='monthly'){
									html += '<td><select id="deduc_type" name="deduc_type[]" class="deduc_type form-control"><option value="monthly" selected>monthly</option><option value="annually">annually</option></select></td>';
									}else{
									html += '<td><select id="deduc_type" name="deduc_type[]" class="deduc_type form-control"><option value="monthly">monthly</option><option value="annually" selected>annually</option></select></td>';
										}
							}
							html += '</tr>';
						}	
					  	html += '</tbody></table></form>';//<button type="submit" class="btn btn-sm btn-success pull-right"  id="perq_map" name="perq_map">Map</button></form>';
						$(".perqs").html(html);
						$('#getPerqscontent').html(html);
						
					}else{
						$('.perq-text,.perqs,#perq ').hide();
						$('#perq').attr("disabled","disabled");
						//$('.perqs').html('<p>No more perqs to map...</p>');
						
						}
					
						if(perqusites[0].checked==1){
		                       $('#perq_s').show();
							   $('#perq').attr("disabled");
						}else{
 		                $('#perq').removeAttr("checked");
		                     $('#perq_s').hide();
						}
             }
		 });
		});

	

$(".pro").on('click',function(){
	
	if($('#slab_opt').val()==''||$("input[type=radio][name=salary_type]").val()==''||$('.ip').val()==''){
		$('.dan').html('<label class="help-block text-danger text">Enter Required field</label>');
	}else
	{	 
		$('.dan').remove(html);
		var salaryType=$("input[name='salary_type']:checked").val();
		  var slabType = $("#slab_opt option:selected").val();
		  var inputType=$("input[name='input_type']:checked").val();
	    	  $('#getCTCcontent').html('');
	    	 if(salaryType =='ctc'){
	        var  mischtml='';
	        var miscAllow = <?php echo json_encode($miscAlloDeduArray['MP']) ?>;
	      	for (i = 0; i < miscAllow.length; i++) {
	    	 mischtml+='<div class="form-group"><label class="col-lg-5 control-label">'+miscAllow[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control miscAlowDeduCtc"  name="allowances['+miscAllow[i].pay_structure_id+']"  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div>';
			 }
	         } 
	 
	if(slabType!='Nil')
	{
			
   	if(salaryType =='ctc' && inputType =='monthly'){
	     var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text"  id="Subctc" name="ctc" oninput="reFormate(this)" data-type="rupee" class="salaryValidate form-control" value="0"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" data-type="rupee" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text" name="ctc_fixed_component"  data-type="rupee" oninput="reFormate(this)" id="ctc" class="form-control salaryValidate" value="0"/></div></div>';
	     $('#getCTCcontent').html('<div class=" grossSal panel panel-body" >'+
			'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post" >'+
					'<div class="col-lg-12"/>'+
	    	     varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label></div></form></div>');
	     }
	     else if(salaryType =='ctc' && inputType =='annual'){
	    	var varaibleComponents=(salaryType =='ctc')?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text"  id="Subctc" name="ctc" oninput="reFormate(this)" data-type="rupee" class="salaryValidate form-control" value="0"/></div></div></div><div class="form-group"><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" data-type="rupee" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="gross" id="gross" value="0"/><input type="hidden" name="basic"  id="basic" value="0"/><input type="text" name="ctc_fixed_component"  data-type="rupee" oninput="reFormate(this)" id="ctc" class="form-control salaryValidate" value="0"/></div></div>';
	     $('#getCTCcontent').html('<div class=" grossSal panel panel-body" >'+
				'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post">'+
						'<div class="col-lg-12" />'
	    	     +varaibleComponents+'<br><button class="btn btn-default pull-right" style="margin-right: 2%;" type="button" id="ctcSalaryCalc">Calculate</button><label style="margin-right: 2%;" class="help-block text-danger pull-right" id="error-text"></label></div></div></form></div>');
	     }
	     else if(salaryType =='monthly' && inputType =='monthly'){
	     $('#getCTCcontent').html('<div class=" grossSal panel panel-body" >'+
					'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post">'+
							'<div class="col-lg-12" >'
							+'<div class="form-group"><label class="col-lg-5 control-label">Gross</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="ctc_fixed_component" data-type="rupee" id="ctc" value="0"/><input type="hidden" name="basic" id="basic" value="0"/><input type="text" name="gross" id="gross" class="form-control salaryValidate" value="0"  data-type="rupee" oninput="reFormate(this)" /></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc"style="margin-top:124px;margin-right:16px;">view salary</button><label class="help-block text-danger text" id="error-text"></label></div></div></div></form></div>');
	     }
	     else if(salaryType =='monthly' && inputType =='annual'){
	    	 $('#getCTCcontent').html('<div class=" grossSal panel panel-body" >'+
				'<form class="form-horizontal " id="gross_sal"  name="grosSal" method="post">'+
						'<div class="col-lg-12" />'+
						'<div class="form-group"><label class="col-lg-5 control-label">Gross</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="hidden" name="ctc_fixed_component" data-type="rupee" id="ctc" value="0"/><input type="hidden" name="basic" id="basic" value="0"/><input type="text" name="gross" id="gross" class="form-control salaryValidate" value="0"  data-type="rupee" oninput="reFormate(this)" /></div><br><button class="btn btn-default pull-right" type="button" id="ctcSalaryCalc"style="margin-top:124px;margin-right:16px;">view salary</button><label class="help-block text-danger text" id="error-text"></label></div></div></div></form></div>');
 	
	     }else{
	    	 $('#getCTCcontent,#getTablecontent').html('');
	     }
	}else{
	     var ar = <?php echo json_encode($allowDeducArray['A']);?>;
	  
	     $('#getCTCcontent,#getTablecontent').html('');
	     var html='';var inputFormDynami='';
	  	 var varaibleComponents=(salaryType =='ctc' )?'<div class="form-group"><label class="col-lg-5 control-label">CTC</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="Subctc"  name="ctc" data-type="rupee" oninput="reFormate(this)"  autocomplete="off" value="0" required/></div></div><fieldset class="scheduler-border"><legend class="scheduler-border" style="color:rgb(121, 121, 121);font-size: 16px;">Variable Component (s)</legend>'+mischtml+'</fieldset></div><div class="form-group"><label class="col-lg-5 control-label">Fixed Component</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input class="form-control" id="ctc" name="ctc_fixed_component" oninput="reFormate(this)" data-type="rupee" autocomplete="off" value="0" readonly type="text"></div>':'';
		 html+=(salaryType =='ctc' )?'<div class=" grossSal panel panel-body" >'+
				'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post">'+
						'<div class="col-lg-12" />'+varaibleComponents+'</div></div>':'<div class=" grossSal panel panel-body" >'+
				'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post">'+
						'<div class="col-lg-12" />';
	     html+='<div class="form-group"><label class="col-lg-5 control-label">Gross Salary</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id="gross"  data-type="rupee" name="gross"  oninput="reFormate(this)" autocomplete="off" value="0" readonly required/></div></div></div>';

	     for (i = 0; i < ar.length; i++) {
	    	inputFormDynami+=ar[i].pay_structure_id+',';
	    	
			html+='<div class=" grossSal panel" >'+
			'<form class="form-horizontal " id="gross_sal" name="grosSal" method="post">'+
			'<div class="col-lg-12" />'+
			'<div class="form-group"><label class="col-lg-5 control-label">'+ar[i].display_name+'</label><div class="col-lg-7"><div class="input-group"><span class="input-group-addon"><i class="fa fa-rupee"></i></span><input type="text" class="form-control salaryValidate" id='+ar[i].pay_structure_id+'  data-type="rupee"  oninput="reFormate(this)" autocomplete="off" value="0" required/></div></div></div></form></div>';
			 }
			
		 html+='<br><button class="btn btn-default pull-right"  data-id="'+inputFormDynami+'" type="button" id="noSlabCaulation">Calculate</button>';
		 $('#getCTCcontent').html(html);
	     eventForNoSlab();
	     }
	}
	  });


		
		
	   $(document).on('click', "#noSlabCaulation", function (e) {
	          e.preventDefault();
	          $('.perqdetails').removeClass("hidden");
	          var parameters = {}; //declare the object
	          parameters["act"] = '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>'; //set some value
	          parameters["pfLimit"] = $('#pf_limit :selected').val();
	          parameters["isCTC"] =($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
	          parameters["isAnnual"] =($("input[type=radio][name=input_type]:checked").val()=='annual')?1:0;
	          parameters["ctc"] =($('#ctc').val())?deFormate($('#ctc').val()):0;
	          parameters["basic"] =0;
	          $('#getCTCcontent').find('input[type="text"]').not(".miscAlowDeduCtc,#Subctc,#ctc").each(function(){
	              if(this.id=='gross'){
	             	 parameters['gross'] =deFormate(this.value);
	              }else{
	         	 parameters['allowances['+this.id+']'] =deFormate(this.value);
	         	 }
	          });
	          falg=(validateForSalary()==true)?1:0;
	      	if(falg==1){
	          $.ajax({
	               dataType: 'html',
	               type: "POST",	
	               url: "php/employee.handle.php",
	               cache: false,
	               data: parameters,
	               beforeSend:function(){$('#loader').loading(true);$('#noSlabCaulation').button('loading');},
	               success: function (data) {
	             	  var json_obj = $.parseJSON(data); //parse JSON
	             	  var ctcCal_d =  setData(json_obj[2]);
	             	 	$('#getTablecontent').html('<form id="map_sal"><input type="hidden"  name="mapSal"><div class="row" ><div class="col-lg-12"><section class="panel"><div class="panel-body"><button class="btn btn-default pull-right" type="button" id="export_pdf" style="margin-right:200px;">Export to PDF</button><div class="container" id="canvas" style="width: 50%;margin-top:50px;" ><div class="table-responsive"><table class="table ctcDesigntable"><thead><tr class="headerReload"><th class="emptyDiv">Components</th><th>Rate</th><th style="text-align:right">Monthly</th><th style="text-align:right">Yearly</th></tr></thead>'+
	  	                   	  setData(json_obj[2])+'</div></div></section></div></div></form>');
	        
	             	  $('#loader').loading(false);
	             	  $('#noSlabCaulation').button('reset');
	              	}
	         });
	      	}
	     }); 

	  $(document).on('click',"#export_pdf" ,function(e) {
		  e.preventDefault();   
	        html2canvas($("#canvas"), {
	            onrendered: function(canvas) {         
	                var imgData = canvas.toDataURL('image/png');
	                var doc = new jsPDF('p', 'mm');
	                doc.addImage(imgData, 'PNG', 10, 10);
	                doc.save('export_pdf.pdf');
	            }
	        });
	    });	
		   
	   
	   $(document).on('click', "#ctcSalaryCalc", function (e) {
	          e.preventDefault();
	          $('.perqdetails').removeClass("hidden");
	          var ctc=deFormate($('#ctc').val());
	          var gross=deFormate($('#gross').val());
	          var slabId=$('#slab_opt :selected').val();
		      var pfLimit = $('#pf_limit :selected').val();
	          var isCTC=($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
	          var isAnnual=($("input[type=radio][name=input_type]:checked").val()=='annual')?1:0;
	          var checkIfexit=ctc+gross+slabId+pfLimit+isCTC+isAnnual;
	          if(slabId){
	          if(checkIfexit!=$('#checkIfexit').val()){
	         	falg=(validateForSalary()==true)?1:0;
	         	if(falg==1){
	          $('#checkIfexit').val(checkIfexit);
	          $('#error-text').html('');
	          $.ajax({
	               dataType: 'html',
	               type: "POST",	
	               url: "php/employee.handle.php",
	               cache: false,
	               data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getCTCbreakUp");?>',
	                       ctc:ctc,gross:gross,slabId:slabId,pfLimit:pfLimit,isCTC:isCTC,isAnnual:isAnnual},
	               beforeSend:function(){$('#loader').loading(true);$('#ctcSalaryCalc').button('loading');},
	               success: function (data) {
	              	 var json_obj = $.parseJSON(data); //parse JSON
	              	$('#getTablecontent').html('<form id="map_sal"><input type="hidden"  name="mapSal"><div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-body"><button class="btn btn-default pull-right" type="button" id="export_pdf" style="margin-right:200px;">Export to PDF</button><div class="container" id="canvas" style="width: 50%;margin-top:50px;" ><div class="table-responsive"><table class="table ctcDesigntable"><thead><tr class="headerReload"><th class="emptyDiv">Components</th><th>Rate</th><th style="text-align:right">Monthly</th><th style="text-align:right">Yearly</th></tr></thead>'+
	                   	  setData(json_obj[2])+'</div></div></section></div></div></form>');
	              	 $('#loader').loading(false);
	              	$('#ctcSalaryCalc').button('reset');
	              	}
	           }); 
	         }else{
	         	 $('#getTablecontent,#checkIfexit').html('');
	         	 }
	          }
	          }else{ 
	         	 $('#getTablecontent,#checkIfexit').html('');
	             $('#error-text').html('Enter Required Fields');
	         }
	     });

	   $(document).on('change', "input[type=radio][name=salary_type]", function (e){
		   e.preventDefault();
		   $('#slab_opt').empty();
		   var isctc = ($("input[type=radio][name=salary_type]:checked").val()=='ctc')?1:0;
		   $.ajax({
               dataType: 'html',
               type: "POST",	
               url: "php/employee.handle.php",
               cache: false,
               data: {  act:  '<?php echo base64_encode($_SESSION['company_id']."!chooseSlabtype");?>', ctc:isctc },
               success: function (data) {
              	 var json_obj = $.parseJSON(data);
              	$('#slab_opt').append('<option value="">Select</option>');
              	 for (i = 0; i < json_obj[2].length; i++)
              	 {
              	 	$('#slab_opt').append('<option id="' + json_obj[2][i]['slab_id'] + '" value="' + json_obj[2][i]['slab_id'] + '">' + json_obj[2][i]['slab_name'] + '</option>');
              	 } $('#slab_opt').append('<option value="Nil">No Slab</option>');
              	 	 $('#slab_opt').trigger('chosen:updated');
                }
	   		});
	   });
	   
	   $(document).on('click',"#map_salary", function (e){
			e.preventDefault();
			if($(perq).prop("checked") == true){
				fun1();
			 }
			  $.ajax({
	               dataType: 'html',
	               type: "POST",	
	               url: "php/employee_update.php",
	               cache: false,
	               data: $('#gross_sal,#map_sal,#ctc-data').serialize(),
	               beforeSend:function(){
	                   	$('#map_salary').button('loading'); 
	                    },
	               success: function (data) {
	            		 var data1 = $.parseJSON(data);
	            		 if (data1[0] == "success") {
		            		 $("#gross_sal")[0].reset();
	            			 $('#map_salary').button('reset');
	            			 BootstrapDialog.show({
	            					type: BootstrapDialog.TYPE_SUCCESS,
	            					title: 'Information',
	            					message: 'Ctc Updated Successfully',
	            					buttons: [{
	            						label: 'ok',
	            						cssClass: 'btn-primary',
	            						action: function(dialogRef){
	            							window.location.href = "../hr/employees.php?employeeID="+data1[2];
	            						}
	            				
	            					}]
	            		      });
	            		 }else if(data1 [0] == "error"){
		            		 alert(data1[1]);
	            		 }
	               	}
		        
	               });
		   });
	 //for perqs mapping
			var fun1= (function() {
			  $.ajax({
	               dataType: 'html',
	               type: "POST",	
	               url: "php/employee.handle.php",
	               cache: false,
	               data: $('#perq_s').serialize(),empId:$(".emp_new").serialize(),
	               beforeSend:function(){
	                   	$('#map_salary').button('loading'); 
	                    },
	               success: function (data) {
		               console.log(data);
	            		 var data1 = $.parseJSON(data);
	            		 if (data1[0] == "success") {
	            			 
	            			 $('#map_salary').button('reset');
	            			// BootstrapDialog.alert(data1[1]);
	            		  }else if(data1 [0] == "error"){
		            		 alert(data1[1]);
	            		 }
	               	}
	               });
		   });

	   $(document).on('click',"#perq",function(){
		   //e.preventDefault();
				if($(this).is(":checked"))
					$('#perq_s').show();
				else 
					$('#perq_s').hide();
		});

	    $(document).on('click', ".check", function (e) {
		   //get the parent row
		    //find input elemts and disable it other than check box, .not("input[type=""]")
		 	if($(this).is(":checked"))
		 		$(this).parent().parent().find(":input").not('input[type="checkbox"]').attr('disabled', false);
			else
				$(this).parent().parent().find(":input").not('input[type="checkbox"]').attr('disabled', true);
		   });
	   
		$('#perq_cancel').on('click',function(e){
			e.preventDefault();
			$("#perqsdata").toggleClass('hide');
			});
	
	</script>
</body>
</html>
