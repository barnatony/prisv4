<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">

<title> <?php echo $_GET['for'];?> Import</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<style type="text/css" title="currentStyle">
@import "../css/reportTable.css";

.loader {
	position: absolute;
	z-index: 1000;
	height: 5%;
	width: 96%;
}
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
            <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
           <?php if($_GET['for']!='ORGSTR' &&  $_GET['for']!='Attendance' && $_GET['for']!='Attendances' &&  $_GET['for']!='Employee' && $_GET['for']!='Misc_PayDedu' && $_GET['for']!='Increment') {?>
              <header class="panel-heading"> Import </header>
					<div class="panel-body">
						<div id="well" class="well">Page Not found...!!!</div>
					</div>
          <?php }else{ ?>
              <form action="php/import.handle.php" id="downloadTemplate"
						method="post">
						<input type="hidden" name="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."TemplateDownload");?>" />
						<header class="panel-heading">
							<span id="panel-heading">
                    <?php echo $_GET['for'];?>    Import <?php if($_GET['for']=='Attendance' || $_GET['for']=='Attendances' || $_GET['for']=='Misc_PayDedu' ) {
                    											$dateObj = DateTime::createFromFormat ( '!m', $_SESSION ['monthNo'] );
										                    	echo $month_name = $dateObj->format ( 'F' ) . " " . $_SESSION ['curYear'];}?>
                        </span>
							<button type="submit" id="exportTemplate" title="Export Template"
								class="btn btn-danger btn-sm pull-right">
								<i class="fa fa-download" aria-hidden="true"></i> Download</button>
						</header>

						<header class="panel-heading tab-bg-dark-navy-blue displayHide"
							id="headerDiv">
							<ul class="nav nav-tabs" id="orgstr_tabs">
								<li class="active"><a href="#branch_sub1" data-loaded="false"
									data-title="branch" data-toggle="tab" id="branch_sub"> Branch </a>
								</li>
								<li class=""><a href="#department1" data-loaded="false"
									data-title="department" data-toggle="tab" id="department">
										Department </a></li>
								<li class=""><a href="#designation1" data-loaded="false"
									data-title="designation" data-toggle="tab" id="designation">
										Designation </a></li>
								<li class=""><a href="#job1" data-loaded="false"
									data-title="job" data-toggle="tab" id="job"> Job Status </a></li>
								<li class=""><a href="#payment1" data-loaded="false"
									data-title="payment" data-toggle="tab" id="payment"> Payments
										Mode </a></li>
								<li class=""><a href="#team1" data-loaded="false"
									data-title="team" data-toggle="tab" id="team"> 
										Team </a></li>
							</ul>
						</header>
						<header class="panel-heading tab-bg-dark-navy-blue displayHide"
							id="headerDiv1">
							<ul class="nav nav-tabs" id="orgstr_tabs">
								<li class="active"><a href="#miscpay_sub1" data-loaded="false"
									data-title="miscPay" data-toggle="tab" id="miscPay_sub"> MiscPay </a>
								</li>
								<li class=""><a href="#miscdedu_sub1" data-loaded="false"
									data-title="miscPay_dedu" data-toggle="tab" id="miscPay_dedu">
										MiscDedu </a></li>
							</ul>
						</header>
						<div class="tab-content tasi-tab displayHide ">

							<div class="tab-pane active" id="branch_sub1">
								<section class="panel">
									<div class="panel-body" id="branchDiv"></div>
								</section>
							</div>

							<div class="tab-pane" id="designation1">
								<section class="panel">
									<div class="panel-body" id="designDiv"></div>
								</section>
							</div>

							<div class="tab-pane" id="department1">
								<section class="panel">
									<div class="panel-body" id="departDiv"></div>
								</section>
							</div>

							<div class="tab-pane" id="payment1">
								<section class="panel">
									<div class="panel-body" id="paymentDiv"></div>
								</section>
							</div>

							<div class="tab-pane" id="job1">
								<section class="panel">
									<div class="panel-body" id="jobDiv"></div>
								</section>
							</div>
							
							<div class="tab-pane" id="team1">
								<section class="panel">
									<div class="panel-body" id="teamDiv"></div>
								</section>
							</div>

						</div>
						<div class="tab-content tasi-tab1 displayHide ">

							<div class="tab-pane active" id="miscpay_sub1">
								<section class="panel">
									<div class="panel-body" id="miscpayDiv"></div>
								</section>
							</div>

							<div class="tab-pane " id="miscdedu_sub1">
								<section class="panel">
									<div class="panel-body" id="miscdeduDiv"></div>
								</section>
							</div>

						</div>
						

					</form>


					<form id="importForm" class="form-horizontal" method="post">
						<input type="hidden" name="act" id="act"
							value="<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."TemplateUpload");?>" />
						<div class="panel-body">


							<div class="form-group" id="FileIMport">
								<label class="col-lg-3  col-sm-3 control-label"> Choose File to
									Upload </label>

								<div class="col-lg-7">
									<div class="row fileupload-buttonbar">
										<div class="col-lg-6">
											<span class="btn btn-success btn-sm  fileinput-button attach">
												<i class="glyphicon glyphicon-plus"></i> <span>Import
													Template</span> <input type="file" name="file"
												id="excelUpload"
												accept=".xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
											</span>
											<button type="submit"
												class="btn btn-sm  btn-danger acceptedImportFile">submit</button>
										</div>

									</div>
									<span class="show_image_name"></span>

								</div>

							</div>
							<div id="loader"></div>
							<div class="panel-body displayHide acceptDiv">
								<div class="adv-table editable-table" id="showall"></div>
							</div>

							<div class="col-lg-12 row form-group displayHide acceptDiv">
								<div class="col-lg-7">
									<label class="checkbox-inline" style="" id="pf_limit2"> <input
										style="border: 1px solid rgb(226, 226, 228);" name="pf_limit"
										id="accpectKey" required checked type="checkbox"> I Accept to
										Import This Above Data

									</label>
								</div>

							</div>

							<div class="col-lg-7 displayHide acceptDiv">
								<button type="submit"
									class="btn btn-sm  btn-info acceptedImportFile">Import</button>
								<button type="button" class="btn btn-sm  btn-danger"
									id="cancelimport">Cancel</button>
							</div>
						</div>
					</form>
                  <?php } ?>
                  
        
													      
                </section>
			</section>
		</section>

		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php");?>
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
	<script src="../js/bootstrap-dialog.js"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
    <script type="text/javascript" charset="utf-8"
		src="../js/FixedColumns.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
	<script src="../js/jquery.table2excel.js"></script>
	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script type="text/javascript">
    $(document).ready(function () {
    	$('#FileIMport').show();
    	 var actVal='<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."TemplateUpload");?>';
         $('#act').val(actVal);
    	$( "#accpectKey" ).prop( "checked", true);
     var attdendaneKey='<?php echo $_GET['for'];?>';
        $('#container').addClass('sidebar-closed');
  	  $('#main-content').css('margin-left','0px');
  	  $('#sidebar').css('margin-left','-221px');
    });

 /*   $(document).on('submit', "#importForm",function (e) {
      	 e.preventDefault();
   	  $.ajax({
   		  processData: false,
             contentType: false,
             type: "POST",
             url: "php/import.handle.php",
             cache: false,
             data: new FormData(this),
             beforeSend:function(){
                	$('#submitImport').button('loading'); 
                 },
              success: function (data) {
           	   data1 = JSON.parse(data);
           	 if (data1[0] == "success") {
               	 console.log(attdendaneKey);
               	  BootstrapDialog.alert(data1[1]);      
                 	 if(attdendaneKey!='ORGSTR'){
               	  setTimeout(function(){ 
               		  window.location.assign("attendance.php"); 
                    }, 1000);     
                 	 }else{
                 		setTimeout(function(){ 
                 		  window.location.assign("orgstr.php"); 
                      }, 1000); 
                     	 }            
                }else{
               	 BootstrapDialog.alert(data1[2]);     
                    }
             }

         });
         });
    */
    


    	$(document).on('submit', "#importForm",function (e) {
    		e.preventDefault();
    		loadData(function() {               
    	 var oTable = $('#claim_requested').dataTable( {
                    	 "bInfo":false,
               			 "bPaginate": false,
                            "bSort": false,
               			 "bFilter": false,
	        				 "sScrollY": "300px",//height of the table
	        				 "sScrollX": "120%",//
	        				// "sScrollXInner":sScrollXInner,
	        				 "bScrollCollapse": true,
	        				 "bPaginate": false,
	        				 "bAutoWidth": true,
   					        });
   					  new FixedColumns( oTable, {
      					"iLeftColumns": 2
      					});
         		  $(window).resize( function () {
         			  oTable.fnAdjustColumnSizing();
         	      });
        	});
        	});
        	
        	function loadData(callback){
            	
        	$.ajax({
            	
       		  processData: false,
                 contentType: false,
                 type: "POST",
                 url: "php/import.handle.php",
                 cache: false,
                 data: new FormData($("#importForm")[0]),
                 beforeSend: function () {
                    	 $('.acceptedImportFile').button('loading'); 
                    	 $('#loader').addClass('loader'); 
                 },
                complete: function () {
                    	 $('.acceptedImportFile').button('reset');
                 },
                  success: function (data) {
                	  jasonData = JSON.parse(data);
                	  //console.log(jasonData[3]);
  if(jasonData[0]=='success'){     
	  $('.acceptDiv').show();
	if(jasonData[3]){
		 
		if(jasonData[4]=='ORG' || jasonData[4]=='MISC'){
			
			if(jasonData[3]=='error' || jasonData[3]=='File Cant Be Empty'){
				$('#cancelimport').click();
				 var error='<?php echo  "Invalid ".$_GET['for']." Template Selected .."; ?>';
		          BootstrapDialog.alert(error);  
				}else if(jasonData[4]=='ORG'){
		$('#headerDiv,.tasi-tab').show();
			console.log($('#headerDiv,.tasi-tab').show());
			$('#branchDiv,#departDiv,#designDiv,#jobDiv,#paymentDiv,#teamDiv').html('');
			$.each(jasonData[3], function (k, v) {
				if(k==0){
					$('#branchDiv').html(v);
				 }else if(k==1){
			    	$('#departDiv').html(v);
			    
				}else if(k==2){
					$('#designDiv').html(v);
				
				}else if(k==3){
					$('#jobDiv').html(v);
					
				}else if(k==4){
					$('#paymentDiv').html(v);
				
				}else if(k==5){
					$('#teamDiv').html(v);
				}
			});
			$('#FileIMport').hide();
			$( "#accpectKey" ).prop( "checked", false);
	 		$('#loader').removeClass('loader');
				}
		    else if(jasonData[4]=='MISC'){
		    	$('#headerDiv1,.tasi-tab1').show();
		    	console.log($('#headerDiv1,.tasi-tab1').show());
		    	$('#miscpayDiv,#miscdeduDiv').html('');
				$.each(jasonData[3], function (k, v) {
					if(k==0){
						$('#miscpayDiv').html(v);
					 }else if(k==1){
				    	$('#miscdeduDiv').html(v);
				    
					}
				});
		
			$('#FileIMport').hide();
			$( "#accpectKey" ).prop( "checked", false);
	 		$('#loader').removeClass('loader');
		    }
		}else{
	if(jasonData[3]=='error' || jasonData[3]=='File Cant Be Empty'){
		$('#cancelimport').click();
		 var error='<?php echo  "Invalid ".$_GET['for']." Template Selected .."; ?>';
          BootstrapDialog.alert(error);  
		}else{
			//console.log(jasonData[3]);
		         var  tableView=jasonData[3].replace("\/", "/");
		         
                 //var oTable = $('#claim_requested').dataTable();
                 	  //oTable.fnDestroy();
                	document.getElementById('showall').innerHTML = tableView;

                    	if(jasonData[4]=='Emp'){
                      	 sScrollXInner='500%';
                      	}else{
                      	 sScrollXInner='100%';
                        }
                        if(jasonData[4]!='Increment')
                    		callback();
                        	 
                       
                    

             	  
         		 $('#headerDiv,.tasi-tab').hide();
         		$('#FileIMport').hide();
        		$( "#accpectKey" ).prop( "checked", false);
         		$('#loader').removeClass('loader'); 	
		}
	}
		
		
 }else{
		    BootstrapDialog.alert(jasonData[1]);  
			$( "#accpectKey" ).prop( "checked", true);
     		$('#FileIMport').show();
     		$('.acceptDiv,#headerDiv,.tasi-tab,#headerDiv1,.tasi-tab1').hide(); 
     		$('#loader').removeClass('loader');
     		$('#showall').html('');
     		 $('.show_image_name').html('');
     		 var actVal='<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."TemplateUpload");?>';
             $('#act').val(actVal);
             window.scrollTo(0, 0);
            
		}
        }else{
        	 $('.acceptDiv').show(); 
	     	 $('#loader').removeClass('loader');
 		       BootstrapDialog.alert(jasonData[2]); 
   				$('#FileIMport').hide();
   			    $('.show_image_name').html('');
	     		$( "#accpectKey" ).prop( "checked", false);
               var actVal='<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."TemplateUpload");?>';
   	             $('#act').val(actVal);
   	            window.scrollTo(0, 0);
   	         }
        }
       	  	});
       	  	return;
    	}
  //  var oTable = $('#claim_requested1').dataTable.new FixedColumns( oTable, {"iLeftWidth": 2,});
    $('#cancelimport').on('click',function() {
    	$( "#accpectKey" ).prop( "checked", true);
 		$('#FileIMport').show();
 		$('.acceptDiv,#headerDiv,.tasi-tab,#headerDiv1,.tasi-tab1').hide(); 
 		$('#loader').removeClass('loader');
 		$('#showall').html('');
 		 $('.show_image_name').html('');
 		 var actVal='<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."TemplateUpload");?>';
         $('#act').val(actVal);
         window.scrollTo(0, 0);
     });
    
       $('input[type="checkbox"]').bind('click',function() {
             if($(this).is(':checked')) {
                var actVal='<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."Upload");?>';
                 $('#act').val(actVal);
             }else{
                 var actVal='<?php echo base64_encode($_SESSION['company_id']."!".$_GET['for']."TemplateUpload");?>';
                 $('#act').val(actVal);
                  }
        });
         
    	$('#excelUpload').on('change',function(e){
            $('.show_image_name').html('');
            $('.show_image_name').html( $(this).val().substring( $(this).val().lastIndexOf("\\") + 1,  $(this).val().length));
            });
          </script>
</body>
</html>
