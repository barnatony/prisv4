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
<title>Company Features</title>
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
<style>
table {
    border: 1px solid #ddd;
}
.table tbody > tr > td, .table tfoot > tr > td {
    padding: 6px;
}
#companyName_chosen {
	width: 100% !important;
}</style>
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
					<header class="panel-heading">Features</header>
					<form class="form-horizontal">
							<div class="panel-body">
							<div class="container" style="width:75%">
								<div class="form-group">
								<label for="dname" class="col-lg-3 col-sm-3 control-label">
									Company Name</label>
								<div class="col-lg-5">
									<select class="form-control" id="companyName">
										<option value="">Select Company</option>
                     <?php
                     require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/company.class.php");
                     $company = new Company ();
                     $company->conn = $conn;
                     $companyVal = $company->filterCompany (1);
                     foreach ( $companyVal['data'][0] as $row ) {
                     	echo "<option data-id='" . $row['company_db_name'] . "' value='" . $row['company_id'] . "'>" . $row['company_name'] . "</option>";
                     }
							/*	$stmt = mysqli_prepare ( $conn, "SELECT company_id,company_name,company_db_name FROM company_details WHERE  enabled =1 " );
								$result = mysqli_stmt_execute ( $stmt );
								mysqli_stmt_bind_result ( $stmt,  $companyId, $companyName, $userName );
								while ( mysqli_stmt_fetch ( $stmt ) ) {
									echo "<option data-id='" . $userName . "' value='" . $companyId . "'>" . $companyName . "</option>";
								}*/
                      ?>
                                           </select>

								</div>

								
							</div>
							
							<div id="requetedLoader"  style="height: 58%; width: 70%"></div>
							
<div class="container" style="width:64%">
<br><br>
<div class="col-lg-12 displayHide wellDiv" id="well">
								
							</div>
</div></div>
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
<script src="../js/bootstrap-switch.js"></script>
	
	

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
           data: { act: '<?php echo base64_encode("getcompanyFeatures");?>', company_id: $('#companyName :selected').val()},
           beforeSend:function(){
        	   $("#requetedLoader").loading(true);
	             },
	       success: function (data1) {
	            	 data = JSON.parse(data1);
	            	 featuredTablecreate(data[02]);
	            	 $("#requetedLoader").loading(false);
                     }
	   		  });
   });

   $('#cancel').on('click', function (e) {
  	 e.preventDefault();
  	  $("#companyName option[value='']").prop("selected", true).trigger('chosen:updated');
  	  $('#well').html('');
     $('.wellDiv').hide();	
   });

   $(document).on('switch-change', ".switchedFeatures", function (e) {
   	 e.preventDefault();
		var element=$(this).find('input');
		var featuredId=element.data('id')
			enableVar='enable_'+featuredId;
		disableVar='disable_'+featuredId;
		  
		if(element.val()=='NA'){
			//enable ths features
		actVal='<?php echo base64_encode("enableFeatures");?>';
			}else{
		 actVal='<?php echo base64_encode("disableFeatures");?>';
	    	}
		$.ajax({
			   dataType: 'html',
	           type: "POST",
	           url: "php/company.handle.php",
	           cache: false,
	           data: { act:actVal, company_id: $('#companyName :selected').val(),featuredId:featuredId},
	           beforeSend:function(){
		          $('.'+enableVar+',.'+disableVar).loading(true);
	                },
		       success: function (data1) {
		            	 data = JSON.parse(data1);
		            	 $('.'+enableVar+',.'+disableVar).loading(false);
		            	 featuredTablecreate(data[02]);
  		       }
		   		  });
		 });
	   
function featuredTablecreate(data){
	var html = '<table class="table table-striped "><thead><tr><th>Features Name</th><th>Enable/Disable</th></tr><tbody>';
	   for (var i = 0, len = data.length; i < len; ++i) {
			html += '<tr>';
			ftId='';
		  $.each(data[i], function (k, v) {
			if(k=='ft_id'){
				ftId=v;
   		  }else if(k=='feature_id'){
       		  if(v!='NA'){
       			html +='<td><div class="enable_'+ftId+'"  style="width: 103px;height: 7%;"></div><div class="switch switch-square switchedFeatures" style="width: 100px;" data-on-label="Enabled" data-off-label="Disabled"><input type="checkbox" data-id="'+ftId+'" Checked   value="'+v+'" /></div></td>';
	          	 }else{
		            html +='<td><div class="disable_'+ftId+'"  style="width:103px;height: 7%;"></div><div class="switch switch-square switchedFeatures" style="width: 100px;" data-on-label="Enabled" data-off-label="Disabled"><input type="checkbox" data-id="'+ftId+'"  value="'+v+'"  /></div></td>';
		         }
   		}
   		  else{
   			html += '<td>'+v+'</td>';
       		  }
   	 }); 
  	  html += "</tr>";
  	}
  	html+='</tbody></table>';
  	$('#well').html(html);
  	$('.wellDiv').show();
  	 $(function () {
   		  $('.switch')['bootstrapSwitch']();
   		});
}
   </script>


</body>
</html>
