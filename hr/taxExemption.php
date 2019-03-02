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

<title>TAX EXEMPTION</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">

<style>
.table td {
	max-width: 10px;
	word-break: break-all
}

.table tbody>tr>td, .table tfoot>tr>td {
	padding: 10px;
}

#taxExcemtion_chosen, #taxMapped_chosen {
	width: 100% !important;
}

hr {
	margin-top: 10px;
	margin-bottom: 9px;
}

select.form-control+.chosen-container .chosen-results li.group-result {
  padding: 3px 8px;
  color: #8d8d8d;
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
				<section class="panel">
					<header class="panel-heading">
						<a href="payStructure.php"> Pay Structure </a> > Tax Exemptions
					</header>
					<div class="panel-body">
						<div class="row">
							<form id="exemptionForm" method="POST">
								<input type="hidden" name="act"
									value="<?php echo base64_encode($_SESSION['company_id']."!taxExemptionMapped");?>" />
								<div class="col-lg-6">
									<header>Allowances</header>
									<div class="form-group">
										<select class="form-control" id="taxExcemtion" name="payId">
										<optgroup  label="Fixed Components" style="color:red">
                                     <?php
																																					$stmt = mysqli_prepare ( $conn, "SELECT pay_structure_id, display_name,display_flag FROM company_pay_structure WHERE type ='A' AND display_flag =1 AND (exemption_id IS NULL OR exemption_id ='')" );
																																					$result = mysqli_stmt_execute ( $stmt );
																																					mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name, $display_flag );
																																					while ( mysqli_stmt_fetch ( $stmt ) ) {
																																						echo "<option   value='" . $pay_structure_id . "'>" . $display_name . "</option>";
																																					}
																																																													?>
                                           </optgroup>	
                                           <optgroup  label="Variable Components" style="color:gray">
                                     <?php
																																					$stmt = mysqli_prepare ( $conn, "SELECT pay_structure_id, display_name,display_flag FROM company_pay_structure WHERE type ='MP' AND display_flag =1 AND (exemption_id IS NULL OR exemption_id ='')" );
																																					$result = mysqli_stmt_execute ( $stmt );
																																					mysqli_stmt_bind_result ( $stmt, $pay_structure_id, $display_name, $display_flag );
																																					while ( mysqli_stmt_fetch ( $stmt ) ) {
																																						echo "<option   value='" . $pay_structure_id . "'>" . $display_name . "</option>";
																																					}
																																																													?>
                                           </optgroup>	
                                           </select>
									</div>
								</div>
								<div class="col-lg-6">
									<header>Exemptions</header>
									<div class="form-group">
										<select class="form-control" id="taxMapped" name="taxMapped">
									  <?php
											$stmt = mysqli_prepare ( $conn, "SELECT txe.exemption_id,txe.title,CONCAT( txe.exemption_value, ' ' ,IF(txe.exempt_type='P','%','Rs')) value  FROM company_tax_exemptions txe" );
											$result = mysqli_stmt_execute ( $stmt );
											mysqli_stmt_bind_result ( $stmt, $exemption_id, $title, $exemption_value );
											while ( mysqli_stmt_fetch ( $stmt ) ) {
												echo "<option   value='" . $exemption_id . "'>" . $title . "</option>";
											}
											?>
                                  </select>
									</div>
									<!-- <div id="well" class="well content hide"></div>    -->
									<div class="col-lg-12">
										<button type="submit"
											class="btn btn-sm btn-success pull-right" id="map">Map</button>
									</div>
								</div>

							</form>
						</div>
						<header class="panel-heading"> Mapped Exemptions </header>
						<div class="container " style="width: 100%;">
							<section class="error-wrapper"
								style="margin-top: 2%; text-align: left">
								<div id="tableContent"></div>

							</section>
						</div>
					</div>

				</section>
				<!-- page end        -->
			</section>
		</section>
		<!-- Dialog Boxes-->
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
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
      jQuery(document).ready(function () {                          
          $('#taxExcemtion,#taxMapped').chosen();
             $.ajax({
               dataType: 'html',
               type: "POST",
               url: "php/allowance.handle.php",
               cache: false,
               data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getMappedExemptions");?>'},
               success: function (data) {
               jsonobject = JSON.parse(data);
$('#tableContent').empty();
var html = '<section id="flip-scroll"><table class="table table-striped table-hover  cf" id="taxExetable"><thead class="cf"><tr><th>Benefits</th><th>ExemName</th><th>Descr.</th><th>Actions</th></tr></thead><tbody>'
//html=;
//if(jsonobject[2].length!==0){
	 $.each(jsonobject[2], function (k, v) {
		  html += '<tr>';
		   $.each(v, function (k, v) {
			   if(k=='exemption_id'){
				   if(v=='conveyan10_14'){
					   html += '<td >Conveyance Allowance 10 (14)</td>'; 
				   }else if(v=='uniform10_14'){
					   html += '<td>Uniform Allowance 10 (14)</td>';
					   }else if(v=='transport10_14'){
						html += '<td >Transport Allowance 10 (14)</td>';
					   }
				   }else if(k=='exe'){
					   if(v=='conveyan10_14'){
						   html += '<td>Exempt to the extent of expenditure incurred for official purposes</td>'; 
					   }else if(v=='uniform10_14'){
						   html += '<td>Exempt to the extent of expenditure incurred for official purposes</td>';
						   }else if(v=='transport10_14'){
							html += '<td>Up to Rs. 1,600 per month (Rs. 3,200 per month for blind and handicapped employees) is exempt</td>';
						   }
					   }else if(k=='pay_structure_id'){
						  html += '<td style="width:12px"><a href="#"  title="Cancel" class="btn btn-danger btn-xs mapDisable" data-id='+v+'><i class="fa fa-times"></i> UnMap</a></td>';
					   }else{
			   html += '<td >' + v + '</td>';
			   }
		   }); 
        	html += "</tr>";
			 
	 });
html +='</tbody></table></section>';
$('#tableContent').append(html);

/*}else{
	$('#tableContent').append('<div id="well" class="well"> No Mapping Found</div>');
}*/
               	}
          });
      });

      $('#taxMapped').on('change', function (event) {
        /*  $('#well').removeClass('hide show');
          $('#well').addClass('show');*/
         switch($('#taxMapped :selected').val()) {
            case 'conveyan10_14': $(".content").html("<header>"+$('#taxMapped :selected').text()+"</header><hr>Exempt to the extent of expenditure incurred for official purposes"); break;
            case 'uniform10_14': $(".content").html("<header>"+$('#taxMapped :selected').text()+"</header><hr>Exempt to the extent of expenditure incurred for official purposes"); break;
            case 'transport10_14': $(".content").html("<header>"+$('#taxMapped :selected').text()+"</header><hr>Up to Rs. 1,600 per month (Rs. 3,200 per month for blind and handicapped employees) is exempt"); break;
          }
        });
      
      $('#exemptionForm').on('submit', function (e) {
         e.preventDefault();
         if($('#taxExcemtion').val()){
    	           $.ajax({
 		                     dataType: 'html',
 		                     type: "POST",
 		                     url: "php/allowance.handle.php",
 		                     cache: false,
     		                 data: $('#exemptionForm').serialize(),
     		                 beforeSend:function(){
     	                     	$('#map').button('loading'); 
     	                      },
     	                    complete:function(){
     	                     	 $('#map').button('reset');
     	                      },
 		                    success: function (data) {
 		                    	 data1 = JSON.parse(data);
 		                    	 window.location.assign("taxExemption.php");   
 		                     }
 		                });
         }
           });

      //Disable coding
      $(document).on('click', "#taxExetable a.mapDisable", function (e) {
          e.preventDefault();
          var pay_id = $(this).data('id');
          BootstrapDialog.show({
              title:'Confirmation',
              message: 'Are Sure you want UnMapped This TaxExemption</strong>',
              buttons: [{
                  label: 'Yes',
                  cssClass: 'btn-sm btn-success',
                  autospin: true,
                  action: function(dialogRef){
                  	 $.ajax({
		                    dataType: 'html',
		                    type: "POST",
		                    url: "php/allowance.handle.php",
		                    cache: false,
		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!delete");?>', payId: pay_id },
		                    complete:function(){
		                    	 dialogRef.close();
		                      },
		                    success: function (data) {
		                    	data = JSON.parse(data);
		                        if (data[0] == "success") {
		                        	BootstrapDialog.alert(data[1]);
		                        	window.location.assign("taxExemption.php");   
	 		                   }else if (data[0] == "error") {
	                                    alert(data[1]);
	                                }
		                    }
		                });
                          		                            
                  }
              }, {
                  label: 'No',
                  cssClass: 'btn-sm btn-danger',
                  action: function(dialogRef){
                      dialogRef.close();
                  }
              }]
          });
      });

      </script>


</body>
</html>
