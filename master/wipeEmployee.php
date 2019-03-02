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
<title>Employee Wipe</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
</head>

<body>
	<section id="container" class="">
		<!--header start-->
     <?php   include_once (__DIR__."/header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
     <?php include_once(__DIR__."/sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
					<header class="panel-heading"> Employee Wipe </header>
					<form class="form-horizontal" id="payrollRollbackForm">
						<input type="hidden" id="payrollMonth" />

						<div class="panel-body">
							<div class="col-lg-6">

								<div class="form-group">
									<label for="dname" class="col-lg-3 col-sm-3 control-label">
										Company Name</label>
									<div class="col-lg-7">
										<select class="form-control" id="companyName" name="companyid">
											<option value="">Select Company</option>
                                        <?php
                                        require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/company.class.php");
                                        $company = new Company ();
                                        $company->conn = $conn;
                                        $companyVal = $company->filterCompany (1);
                                        foreach ( $companyVal['data'][0] as $row ) {
                                        	echo "<option data-leave='" . $row['leave_based_on'] . "' data-val='" . $row['current_payroll_month'] . "'
			data-id='" . $row['company_db_name'] . "' value='" . $row['company_id'] . "'>" . $row['company_name'] . "</option>";
                                        }
                                        /*
								 $stmt = mysqli_prepare ( $conn, "SELECT leave_based_on,current_payroll_month,company_id,company_name,company_db_name FROM company_details WHERE  enabled =1 " );
								$result = mysqli_stmt_execute ( $stmt );
								mysqli_stmt_bind_result ( $stmt, $leaveBased, $payrollMOnth, $companyId, $companyName, $userName );
								while ( mysqli_stmt_fetch ( $stmt ) ) {
									echo "<option data-id='" . $userName . "' value='" . $companyId . "'>" . $companyName . "</option>";
								}*/
								?>
                                           </select>

									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 col-sm-3 control-label">Employee ID</label>
									<div class="col-lg-7">
										<input type="hidden" class="form-control" name="act"
											value="<?php echo base64_encode("employeewipe");?>" required />
										<input type="text" class="form-control" name="deletedEmpId"
											id="employeeid" />
									</div>
								</div>
								<div class="form-group displayHide wellDiv wellDiv1">
									<input type="hidden" id="toDelete" name="choosenId" /> <input
										type="hidden" id="username" name="cdb" /> <label
										class="col-lg-3 col-sm-3 control-label">Select To Wipe </label>
									<div class="col-lg-9">

										<label class="col-lg-5 checkbox-inline"> <input id="limit"
											value="att" name="wipeEmp" type="checkbox"> Attendance
										</label> <label class="col-lg-4 checkbox-inline"> <input
											id="limit" value="tax" name="wipeEmp" type="checkbox"> Income
											Tax
										</label> <br> <label class="col-lg-5 checkbox-inline"> <input
											id="limit" value="msg" name="wipeEmp" type="checkbox">
											Messages
										</label> <label class="col-lg-4 checkbox-inline"> <input
											id="limit" value="notifi" name="wipeEmp" type="checkbox">
											Notifications
										</label>

									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group displayHide wellDiv">
									<div class="col-lg-12">
										<div id="well" class="well" style="background-color: #fff;"></div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-4 col-lg-offset-4 ">
									<div id="error"></div>
									<button type="submit" class="btn btn-sm btn-success"
										id="submitWipe">Submit</button>
								</div>
							</div>
						</div>
					</form>
				</section>
			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
	  <?php include_once(__DIR__."/footer.php");?>
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
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>


	<!-- END JAVASCRIPTS -->
	<script>
   $(document).ready(function () {
		$('#companyName').chosen();              
   });

   $('#employeeid').on('blur', function (e) {
	   e.preventDefault();
	   var userName=$('#companyName :selected').data('id');
	   $.ajax({
   		   dataType: 'html',
              type: "POST",
              url: "php/company.handle.php",
              cache: false,
              data: { act: '<?php echo base64_encode("getEmployeeDetails");?>',company_id: $('#companyName :selected').val(),cdb:userName,deletedEmpId:$('#employeeid').val()},
              beforeSend:function(){
   	             $('#submit').button('loading'); 
   	             },
   	               complete:function(){
   	              	 $('#submit').button('reset');
   	               },
   	             success: function (data) {
   	            	jsonval = JSON.parse(data);
   	             if(jsonval[0]=='success' && jsonval[2]!='No Data Found'){	
   	            	if(jsonval[2][0]){
   	            	if(jsonval[2][0].status=='Active'){
                  var  classVal='success ';
                  var extraStr='<h5>Employee Exit On: <em>'+jsonval[2][0].last_working_date+'</em></h5>';
   	   	            	}else{
   	   	         var  classVal='danger';
   	   	   	            	}
   	                $('#well').html('<span  class="label label-'+classVal+' pull-right r-activity">'+jsonval[2][0].status+'</span><h5>Employee Name: <em><strong>'+jsonval[2][0].employee_name+'</strong></em></h5><h5>Department Name : <em><strong>'+jsonval[2][0].department_name +'</strong></em></h5><h5>Designation Name : <em><strong>'+jsonval[2][0].designation_name +'</strong></em></h5><h5>Branch Name : <em><strong>'+jsonval[2][0].branch_name +'</strong></em></h5><h5>employee Doj : <em><strong>'+jsonval[2][0].employee_doj +'</strong></em></h5>');
                    $('.wellDiv').show();	
                   }else{
                	   $('#well').html('Invalid Employee ID');
                	   $('.wellDiv').show();
                       $('.wellDiv1').hide();
                       $('#error').html('');
                       
   	             }
   	             }else{  
   	   	        $('#well').html('data Base Error');
          	   $('.wellDiv').show();
               $('.wellDiv1').hide();
               $('#error').html('');
   	   	             }
   	             }
   	   		  });
	  });
   $('#companyName').on('change', function (e) {
	   e.preventDefault();
	   $('#employeeid').val('');
	   $('#well').html('Enter Employee Id');
  	   $('.wellDiv').show();
       $('.wellDiv1').hide();
       $('#error').html('');
   });
   
   $('#payrollRollbackForm').on('submit', function (e) {
   		e.preventDefault();
   		var userName=$('#companyName :selected').data('id');
        var favorite = [];
        $.each($("input[name='wipeEmp']:checked"), function(){            
        favorite.push($(this).val());
        });
       $('#toDelete').val(favorite.join(","));
       $('#username').val(userName);
       if($('#employeeid').val()!='' &&  $('#toDelete').val()!=''){
    	   $('#error').html('');
   		   $.ajax({
   			 dataType: 'html',
             type: "POST",
             url: "php/company.handle.php",
             cache: false,
             data: $('#payrollRollbackForm').serialize(),
             beforeSend:function(){
             	$('#submitWipe').button('loading'); 
               },
               complete:function(){
              	 $('#submitWipe').button('reset');
               },
             success: function (data) {
                 data1 = JSON.parse(data);
                 $("#companyName option[value='']").prop("selected", true).trigger('chosen:updated');
		   	     $('#well').html('');
		   	     $('.wellDiv').hide();
		   	     $('#employeeid').val('');
	             BootstrapDialog.alert(data1[1]);
	             
   	        }

         });
   	 }
   });

    </script>


</body>
</html>
