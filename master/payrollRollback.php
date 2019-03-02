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
<title>Payroll Rollback</title>
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
					<header class="panel-heading"> Payroll Rollback </header>
					<form class="form-horizontal" id="payrollRollbackForm">
						<input type="hidden" id="payrollMonth" />

						<div class="panel-body">
							<div class="form-group">
								<label for="dname" class="col-lg-3 col-sm-3 control-label">
									Company Name</label>
								<div class="col-lg-5">
									<select class="form-control" id="companyName"
										name="deductions_type[]">
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
	echo "<option data-leave='" . $leaveBased . "' data-val='" . $payrollMOnth . "' data-id='" . $userName . "' value='" . $companyId . "'>" . $companyName . "</option>";
}*/
?>
                                           </select>

								</div>
							</div>

							<div class="form-group displayHide wellDiv">
								<label for="dname" class="col-lg-3 col-sm-3 control-label"></label>
								<div class="col-lg-5">
									<div id="well" class="well"
										style="margin-bottom: 0px; padding: 0em 0em 0px 12px;"></div>
								</div>
							</div>

							<div class="form-group displayHide wellDiv">
								<label for="dname" class="col-lg-3 col-sm-3 control-label">
									Rollback Month For</label>
								<div class="col-lg-5">
									<input class="form-control" id="monthcount" disabled
										type="text"> <input class="form-control" id="ispartial"
										type="hidden">
								</div>
							</div>
							<div class="col-lg-offset-3 col-lg-5" align="right">
							<span id="errorMsg"></span>
								<button type="submit" class="btn btn-sm btn-success" id="submit">Submit</button>
								<button type="button" class="btn btn-sm btn-danger" id="cancel">Cancel</button>
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

   $('#companyName').on('change', function (e) {
	   e.preventDefault();
	  var currPayrollMonth=$('#companyName :selected').data('val');
	   var userName=$('#companyName :selected').data('id');
	   $.ajax({
		   dataType: 'html',
           type: "POST",
           url: "php/company.handle.php",
           cache: false,
           data: { act: '<?php echo base64_encode("getCompanyDetails");?>', company_id: $('#companyName :selected').val(),cdb:userName,currentMonth:currPayrollMonth},
           beforeSend:function(){
	             $('#submit').button('loading'); 
	             },
	               complete:function(){
	              	 $('#submit').button('reset');
	               },
	             success: function (data) {
	            	 jsonval = JSON.parse(data);
	            	 if(jsonval[0]=='success' && jsonval[2]!='No Data Found'){	
		             jsonval[2][0].runnedEmp.split('#');
	            	 var ispartial=(jsonval[2][0].runnedEmp.split('#')[0]==1)?1:0;
	            	 var getdate=(jsonval[2][0].runnedEmp.split('#')[0]==1)?jsonval[2][0].currentMonth:jsonval[2][0].month_year;
	            	 var payrollMonth=(jsonval[2][0].runnedEmp.split('#')[0]==1)?currPayrollMonth:jsonval[2][0].lastMonth;
	            	 var dateName=(jsonval[2][0].runnedEmp.split('#')[0]==1)?jsonval[2][0].runnedEmp.split('#')[1]+' For '+jsonval[2][0].currentMonth:jsonval[2][0].runnedEmp+' For '+jsonval[2][0].month_year;
	            	 $('#well').html('<h5>Last Payroll Month : <em>'+jsonval[2][0].month_year+'</em></h5><h5>Current Payroll Month : <em>'+jsonval[2][0].currentMonth +'</em></h5><h5>Payroll Runned Details : <em>'+dateName +'</em></h5>');
                      $('.wellDiv').show();	
                      $('#monthcount').val(getdate);
                      $('#payrollMonth').val(payrollMonth);
                      $('#ispartial').val(ispartial);
	    	             }else{
		    	             $('#errorMsg').html('Database Error');
		    	             $('.wellDiv').hide();	
		    	             $('#well').html('');
		    	             }
    	             }
	   		  });
   });

   
   $('#payrollRollbackForm').on('submit', function (e) {
   	    e.preventDefault();
   		var currPayrollMonth=$('#companyName :selected').data('val');
   		var userName=$('#companyName :selected').data('id');
   		var leaveBased=$('#companyName :selected').data('leave');
 	     $.ajax({
   		   dataType: 'html',
              type: "POST",
              url: "php/company.handle.php",
              cache: false,
              data: { act: '<?php echo base64_encode("payrollRollBack");?>',company_id: $('#companyName :selected').val(),cdb:userName,RollbackMonth:$('#payrollMonth').val(),currentMonth:currPayrollMonth,leaveBasedOn:leaveBased,ispartial:$('#ispartial').val()},
              beforeSend:function(){
   	             $('#submit').button('loading'); 
   	             },
   	               complete:function(){
   	              	 $('#submit').button('reset');
   	               },
   	             success: function (data) {
   	            	jsonval = JSON.parse(data);
		   	        $("#companyName option[value='']").prop("selected", true).trigger('chosen:updated');
		   	      	$('#well').html('');
		   	        $('.wellDiv').hide();
   	            	BootstrapDialog.alert(jsonval[1]);
     	    	    }
   	   		  });
     });

   $('#cancel').on('click', function (e) {
  	 e.preventDefault();
  	  $("#companyName option[value='']").prop("selected", true).trigger('chosen:updated');
  	  $('#well').html('');
     $('.wellDiv').hide();	
   });

   </script>


</body>
</html>
