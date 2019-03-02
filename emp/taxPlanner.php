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

<title>Tax Planner</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
<script src="../js/html5shiv.js"></script>
<script src="../js/respond.min.js"></script>
<![endif]-->
</head>

<body>

<section id= "container" >
		<!--header start-->
		<?php include("header.php"); ?>
		<!--header end-->
		<!--sidebar start-->
		<aside>
		<?php include_once("sideMenu.php");?>
		</aside>
		<?php
		$employee_id = $_SESSION ['employee_id'];
		?>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<!-- header for past years starts here -->
				<section class="panel year-panel" id="section_hide">
						<header class="panel-heading"> View IT Declaration </header>
									<div class="panel-body">
										 <div class="container col-lg-12 ">	
										           <label class="col-lg-3 control-label" style="font-weight: 300;font-size: 14px;text-align: left;">View IT Declaration Using Year</label>
													 	<div class="col-lg-4 input-group" >
													     <select class="form-control " required
																		name="year" id="years" style="margin-bottom: 20px">
																		
						                         <?php
																															$stmt = mysqli_prepare ( $conn, "SELECT DISTINCT year FROM employee_income_tax ORDER BY year DESC;   " );
																															$result = mysqli_stmt_execute ( $stmt );
																															mysqli_stmt_bind_result ( $stmt, $year );
																															while ( mysqli_stmt_fetch ( $stmt ) ) {
																																echo "<option value='" . $year . "'>" . $year . "</option>";
																															}
																															mysqli_stmt_free_result ( $stmt );
																															mysqli_stmt_close ( $stmt );
																															?>
						                             	</select>
						                            </div>
													<div class="col-lg-2" id="goButtonDiv">
														<button type="button" class="btn btn-sm btn-success" id="go">View</button>
													</div>
										</div>			
								</div>	
				</section>
				<!-- header for past years end -->
				<section class="panel taxplan">
					<header class="panel-heading">
						<div class="tax1">TAX PLANNER</div>
						<div class="summary displayHide">TAX SUMMARY</div>
						<div class="summayHeader displayHide">No Record found...</div>
						<div class="pull-right" style="margin-top:-2%;">
						<div class="displayHide summary">
								<form action="../common/itDeclaration.handle.php" method="post">
								<input name="taxEmpId" type="hidden" id="pdfEmpid">
								<div class="displayHide" id="hiddenpartBText"></div>
								<div class="displayHide" id="hiddenBeneifitsText"></div>
								<input name="partBContent" type="hidden" id="partBContentId">
								<input name="panNum" type="hidden" id="empPan"> 
								<input name="year" type="hidden" id="pdfYear"> 
								<input name="act" type="hidden" value="<?php echo base64_encode($_SESSION['company_id']."!taxSummaryPdf");?>" >
								<input name="empName" type="hidden" value="<?php echo $_SESSION['employee_name'];?>"> 
								<input name="benefitsPaid" type="hidden" id="benefitsPaidId">
								<button class="btn btn-sm btn-default" id="pddf" type="submit">
								<i class="fa fa-file-pdf-o"></i> PDF
								</button>
								<button class="btn btn-sm btn-danger" type="button" id="tax_plan">
								Tax Declares</button>
								</form>
								</div>
								<button class="btn btn-sm btn-danger" type="button" id="view_summery">
								Summary
								</button>
						</div>
					</header>


					<!-- page end-->

					
					<!-- Modal -->
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
						aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">

								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close"
										type="button">&times;</button>
									<h4 class="modal-title">Proof Image</h4>
								</div>
								<div class="modal-body">

									<div class="fileupload-new thumbnail">
										<img id="preview_image" style="width: 100%; height: 100%"
											src="http://www.placehold.it/672x920/EFEFEF/AAAAAA&amp;text=no+image"
											alt="Employee Image">
									</div>

								</div>
								<div class="modal-footer">
									<button data-dismiss="modal" class="btn btn-default"
										type="button">Close</button>

								</div>
							</div>
						</div>
					</div>
					<!-- modal -->
					<!--tab nav start-->

					<section class="panel displayHide" id="it_declare_summery">
									
												
                  <?php require_once  ( dirname ( __DIR__ ) . "/common/itSummaryText.php"); ?>

					</section>
		</section>
		<!--main content end-->
		<!--footer start-->
<?php include("footer.php"); ?>
<!--footer end-->
	</section>
 </section>
</section>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<!--For auto Complete-->
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/ImageTools.js"></script>
	<!--Resize image script-->
	<!--script for this page only-->
	<script src="../js/ZeroClipboard.js"></script>
	<!-- END JAVASCRIPTS -->
<script type="text/javascript">
        $(document).ready(function () {
        	itDeclarationActData='<?php echo base64_encode($_SESSION['company_id']."!getItDeclarationData");?>';
        	itSummeryActData='<?php echo base64_encode($_SESSION['company_id']."!taxSummaryData");?>';
            localStorage.setItem("itSummeryActData",itSummeryActData);
        	localStorage.setItem("itDeclarationActData",itDeclarationActData );
            employee_id="<?php echo $employee_id;?>";
            var year = <?php echo  $_SESSION['financialYear'];?>;
            $("#pdfYear").val(year);
            $('#hrHeader').hide();
             $('#employeeIdForIt').val(employee_id);
        	it_view(employee_id);
        	flag=0;
        });


         
          $("#tax_plan").click(function (e) {
        	  $("#it_declare_tab,#view_summery,.tax1").show();
        	  $("#it_declare_summery,.summary").hide();
            });
                 
        $(document).on('click', '.declarationEdit', function (e) {
        	e.preventDefault();
        	$('.declarationCancel,.previousEmpcancel').click();
        	$(".table").find(":input").each(function () {
        		 $(this).prop("disabled",true);
        	 });
        	 $('.showDiv').show();
        	 $('.emptyHtml').html('');
        	$('#'+$(this).data('id')+'_viewdiv').hide();
            $('#'+$(this).data('id')+'_editdiv').append('<span class="btn btn-success btn-xs marginRight fileinput-button"><i class="fa fa-paperclip"></i> <input type="file" name="'+$(this).data('id')+'" disabled class="itImagechange"></span><a class="marginRight btn btn-xs btn-danger itImgView" title="view" data-toggle="modal" href="#myModal"> <input type="hidden" class="form-control"  id="'+$(this).data('id')+'_currentImage" /> <i class="fa fa-eye"></i></a><a class="marginRight btn btn-xs btn-success declarationSave" title="Save" data-id="'+$(this).data('id')+'"><i class="fa fa-check" aria-hidden="true"></i></a><a class="marginRight btn btn-xs btn-danger declarationCancel" title="Save" data-id="'+$(this).data('id')+'"><i class="fa fa-times" aria-hidden="true"></i></a><br><label id="text"></label>');
            $('#'+$(this).data('id')+'_editdiv').show();
            $(this).parent().parent().parent().find('td').not(".hrApproved").each(function () {
                $(this).find('input').prop("disabled",false);
           });
        });

        $(document).on('click', '.declarationCancel', function (e) {
        	e.preventDefault();
            $(this).parent().parent().parent().find('td').each(function () {
        		 $('#'+$(this).find('input').attr('id')).val($(this).find('input').data('id'));
        		 $(this).find('input').prop("disabled",true);
        	});
        	$('#text,#text1').html('');
            $('#'+$(this).data('id')+'_editdiv').html('');
            $('#'+$(this).data('id')+'_viewdiv').show();
        });

        $(document).on('click', '.declarationSave', function (e) {
        	e.preventDefault();
        	    var element=$(this).closest('tr');
        	    element.find('input').not(".hrApproved,input[type=file]").each(function () {
        		$(this).val(deFormate($(this).val()));
              });
        	 
        	  var currentEle=$(this).data('id');
              var formData = new FormData($('#'+$(this).closest('form').attr('id'))[0]);
              $.ajax({
                  type: "POST",
                  url: "../common/itDeclaration.handle.php",
                  data: formData,
                  contentType: false,
                  processData: false,
                  beforeSend:function(){$('#'+$(this).data('id')).button('loading');},
                  success: function (data) {
                      data1 = JSON.parse(data);
                      if(data1[2]==1){
                      if (data1[0] == "success") {
                          BootstrapDialog.alert($("ul#declarationNabs li.active").find('a').html()+' Updated Successfull');
                          element.find('td').not(":nth-child(1)").each(function () {
                            $(this).find('input').prop("disabled",true);
                          });
                          $('#'+currentEle+'_editdiv,#text,#text1').html('');
                          $('#'+currentEle+'_viewdiv').show();
                          console.log($('#'+currentEle).val());
                          if($('#'+currentEle).val()!='' &&  $('#'+currentEle).val()!='Nil'){
                        	  $('#'+currentEle).parent().find('.fa').removeClass("fa-paperclip").addClass("fa-eye");
                              $('#'+currentEle).parent().removeClass("btn-danger").addClass("btn-primary").prop( "disabled", false);
                              }else{
                            	  $('#'+currentEle).attr('disabled', true);
                              }
                         
                         }
                  }
                      else
                    		BootstrapDialog.alert('Cant be upload '+data1[2]);
                      $('#'+$(this).data('id')).button('reset'); 
                  }

              });
          });

        $(document).on('click', '.previousEmpEdit', function (e) {
        	e.preventDefault();
        	$(this).hide();
        	$('#itdeclare_previous_emp').find('input,textarea').not('.empActual').each(function () {
        	    $(this).attr('disabled', false);
            });
        	 $('#prevDisable').attr('disabled', false);
            $('.previousEmpUpdate,.previousEmpcancel,.attach').show();
        });

        $(document).on('click', '.previousEmpcancel', function (e) {
        	e.preventDefault();
        	$('.previousEmpEdit').show();
        	$('#text,#text1').html('');
        	$('#itdeclare_previous_emp').find('input,textarea').each(function () {
        		 $(this).attr('disabled', true);
        		 $('#'+$(this).attr('id')).val($(this).data('id'));
            });
            ($('#prev_employer_proof').val()!='' || $('#prev_employer_proof').val()!='Nil')?$('#prevDisable').attr('disabled', false):
            	$('#prevDisable').attr('disabled', true);
            $('.previousEmpUpdate,.previousEmpcancel,.attach').hide();
        });

        $('#itdeclare_previous_emp').on('submit', function (e) {
        	 e.preventDefault();
        	  $('#itdeclare_previous_emp').find('input,textarea').not('.empActual,.itImagechange').each(function () {
        		$(this).val(deFormate($(this).val()));
              });
              //if(){
        	  $.ajax({
                  type: "POST",	
                  url: "../common/itDeclaration.handle.php",
                  data: new FormData(this),
                  contentType: false,
                  processData: false,
                  beforeSend:function(){$('.previousEmpUpdate').button('loading');},
                  success: function (data) {
                      data1 = JSON.parse(data);
                      if(data1[2]==1){
                      if (data1[0] == "success") {
                          BootstrapDialog.alert($("ul#declarationNabs li.active").find('a').html()+' Updated Successfull');
                          $('#itdeclare_previous_emp').find('input,textarea').each(function () {
                     		 $(this).attr('disabled', true);
                         });
                          $('.previousEmpUpdate,.previousEmpcancel,.attach').hide();
                          $('.previousEmpEdit').show();
                          $('#text,#text1').html('');
                          if($('#prev_employer_proof').val()!='' &&  $('#prev_employer_proof').val()!='Nil'){
                          $('#prevDisable').attr('disabled', false);
                          $('#prevDisable').parent().find('.fa').removeClass("fa-paperclip").addClass("fa-eye");
                          $('#prevDisable').removeClass("btn-danger").addClass("btn-primary");
                          }else{
                           $('#prevDisable').attr('disabled', true);
                          }
                      }
                      }
                      else
                          	BootstrapDialog.alert('Cant be upload '+data1[2]);
                      $('.previousEmpUpdate').button('reset'); 
                  }

              });
             // }
          });
        /*  $("#view_summery").click(function (e) {
      	  
            $("#it_declare_summery,#view_summery,.tax1").hide();
            $("#it_declare_tab,.summary,#tax_plan,#pddf").show();
            if(flag==0){
                it_summary(employee_id);
            }else {
                 $('#it_declare_tab').hide();	 
                 $('#it_declare_summery').show();
                 }
           });*/
      
      $("#view_summery").click(function (e) {
    	  if(flag==0){
    	  it_summary(employee_id);
    	  }
            $("#it_declare_tab,#view_summery,.tax1").hide();
            $("#it_declare_summery,.summary,#tax_plan,#pddf").show();
            
           });
           
     $(document).on('click','#go',function(e){
             e.preventDefault();
             var year=$("#years").val();
             $("#pdfYear").val(year);
             $("#it_declare_tab,.summary,#view_summery").show();
             $("#it_declare_summery,.tax1,#tax_plan,#pddf").hide();
            	 $.ajax({
  	    	       dataType: 'html',
  	    	       type: "POST",
  	    	       url: "../common/itDeclaration.handle.php",
  	    	       cache: false,
  	    	        data: {act:localStorage.getItem("itSummeryActData"),current_payroll_month:current_payroll_month,employee_id: employee_id,year:year},
  	    	      	beforeSend:function(){
  	    	    	$('#go').button('loading'); 
  	    	   		 },
  	    			complete:function(){
  	    			$('#go').loading(false);
  	    			},
  	    			
  	    	       success: function (data) {
  	    	    	 var json_obj = $.parseJSON(data); //parse JSON
  	    	         var s = 0;
  	    	         if(json_obj[2].employee_salary_details[0].gross > 0 || json_obj[2].employee_salary_details[0].gross != null){
  	  	    	       prev_earnings_app=json_obj[2].employee_it_declaration[0].prev_earnings_app;
  	       	       	   $('.name_emp').html(json_obj[2].employee_salary_details[0].employee_name + "  's IT Summary ");
  	    	           
  	    	           //table header setting
  	       	         var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
  	    	        	   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
  	    	        	 ];
  	       	          var  formattedDate =new Date(current_payroll_month);
  	       	          var m =  monthNames [formattedDate.getMonth()];
  	       	          var pre= monthNames [formattedDate.getMonth()-1];
  	       	        
  	       	          if((json_obj[2].employee_it_declaration[0].years)==0){
  	    	        	 $('#previousMonthsHead').html('<p>Apr - Mar(<i class="fa fa-rupee"></i>)</p>');
  	    	        	 $('#previousMonthsHead1').html('<p>Projected(<i class="fa fa-rupee"></i>)</p>');
  	    	        	 $('.previousEmpEdit').hide();
  	    	          }else{
  	    	        	  $("#previousMonthsHead").html('<p>Apr - '+pre+'(<i class="fa fa-rupee"></i>)</p>');
  	    	        	  $('#previousMonthsHead1').html('<p>'+m+' - Mar(<i class="fa fa-rupee"></i>)</p>');
  	    	        	 $('.previousEmpEdit').show();
  	    	        	  }
  	    	          
  	    	           $.each(json_obj[2].employee_salary_details[0], function (k, v) {
  	    	        	   if(k=='gross')
  	    	        		   v=parseFloat(v)+parseFloat(prev_earnings_app);
  	    	        	   if(v!='-'){
  	    	        		   $('.'+k).html(reFormateByNumber(Number(v).toFixed(2)));
  	    	        	   }else{
  	    	        		   $('.'+k).html(v);
  	    	        	   }
  	    	         });
  	    	           $.each(json_obj[2].employee_income_tax[0], function (k, v) {
  	    	               //display the key and value pair
  	    	               $('.' + k).html(reFormateByNumber(Number(v).toFixed(2)));
  	    	          });
  	    	           $('.c_incentive').html(json_obj[2].employee_salary_details[0].c_incentive);
  	    	           
  	    	           $('.c_bonus').html(json_obj[2].employee_salary_details[0].c_bonus);
  	    	           perq = '0.00';
  	    	           $('.perq').html(perq);
  	    	           $('.prev_earnings_app').html(prev_earnings_app);
  	    	           $('.othersGross').html((Number(deFormate($('.prev_earnings_app').html()))+Number(deFormate($('.perq').html()))+Number(deFormate($('.c_other').html()))+Number(deFormate($('.c_incentive').html()))+Number(deFormate($('.c_bonus').html()))).toFixed(2));
  	    	           grosVal=Number(deFormate($('.gross').html()))+Number(deFormate($('.house_prop_inc').html()))+Number(deFormate($('.other_income').html()));
  	    	           $('.Tot_income').html(reFormateByNumber(grosVal.toFixed(2)));     
  	    	           $('.salary_month').html($('.gross').html());
  	    	           income = Number(deFormate($('.house_prop_inc').html()) )+Number(deFormate($('.other_income').html()));
  	    	   flag=1;
  	    	           //IT DECALRATION CAL
  	    	           var _80g = 0;
  	    	           var _80c = 0;
  	    	           var _80d = 0;
  	    	           var _80e = 0;
  	    	           var other = 0;
  	    	           $.each(json_obj[2].employee_it_declaration[0], function (k, v) {
  	    	               //display the key and value pair
  	    	               if (k[2] == "g") {
  	    	                   _80g += parseFloat(v, 10);
  	    	               } else {
  	    	                   if (k[2] == "c") {
  	    	                       _80c += parseFloat(v, 10);
  	    	                   } else {
  	    	                       if (k[2] == "d") {
  	    	                           _80d += parseFloat(v, 10);
  	    	                       } else {
  	    	                           if (k[2] == "e" & k !='prev_earnings_app') {
  	    	                               _80e += parseFloat(v, 10);
  	    	                           } else {
  	    	                        	   if(k !='prev_earnings_app')
  	    	                        		   other += parseFloat(v, 10);
  	    	                           }
  	    	                       }
  	    	                   }
  	    	               }
  	    	               $('.'+k).html(reFormateByNumber(Number(v).toFixed(2)));

  	    	           });
  	    	           $('._80g').html(reFormateByNumber(_80g.toFixed(2))); //3
  	    	           $('._80c').html(reFormateByNumber((_80c+Number(json_obj[2].employee_income_tax[0].epf_employee)).toFixed(2))); //3
  	    	           $('._80d').html(reFormateByNumber(_80d.toFixed(2))); //3
  	    	           $('._80e').html(reFormateByNumber(_80e.toFixed(2))); //3
  	    	            $('.other').html(reFormateByNumber(other.toFixed(2))); //3


  	    	            val_perq = "0.00";
  	    	           $('.val_perq').html(val_perq);
  	    	           lieu = "0.00";
  	    	           $('.lieu').html(lieu);
  	    	          tot_perq = parseFloat(Number(deFormate($('.gross').html())), 10) + parseFloat(val_perq, 10) + parseFloat(lieu, 10);
  	    	           $('.tot_perq').html(reFormateByNumber(tot_perq.toFixed(2)));

  	    	            exemption = parseFloat(json_obj[2].employee_income_tax[0].exe_hra, 10) + parseFloat(json_obj[2].employee_income_tax[0].exe_lta, 10) +
  	    	                parseFloat(json_obj[2].employee_income_tax[0].exe_oth, 10);
  	    	           $('.exemption').html(reFormateByNumber(exemption.toFixed(2)));
  	    	           $('.balance').html(reFormateByNumber((tot_perq - exemption).toFixed(2))); //3

  	    	            $('.taxon_employment').html(parseFloat(json_obj[2].employee_income_tax[0].taxon_employment, 10).toFixed(2));
  	    	           entertain = "0.00";
  	    	           $('.entertain').html(reFormateByNumber(entertain)); //5
  	    	           aggregate = parseFloat(entertain, 10) + parseFloat(json_obj[2].employee_income_tax[0].taxon_employment, 10);
  	    	           $('.aggregate').html(reFormateByNumber(aggregate.toFixed(2))); //4()a+4(b)
  	    	           income_charable = (tot_perq - exemption) - aggregate;
  	    	           $('.income_charable').html(reFormateByNumber(income_charable.toFixed(2)));
  	    	           $('.any_income').html(reFormateByNumber(income.toFixed(2)));
  	    	           $('.gross_s').html(reFormateByNumber((income + income_charable).toFixed(2)));

  	    	           aggregate_dec = parseFloat(json_obj[2].employee_income_tax[0].ded_80c, 10) + parseFloat(json_obj[2].employee_income_tax[0].ded_80d, 10) +
  	    	                parseFloat(json_obj[2].employee_income_tax[0].ded_80e, 10) + parseFloat(json_obj[2].employee_income_tax[0].ded_80g, 10) +
  	    	                parseFloat(json_obj[2].employee_income_tax[0].ded_other, 10);

  	    	           $('.aggregate_dec').html(reFormateByNumber(aggregate_dec.toFixed(2)));
  	    	           tot_dec = ((income + income_charable) - aggregate_dec);
  	    	           $('.tot_dec').html(reFormateByNumber(tot_dec.toFixed(2)));
  	    	           tax_tot = parseFloat(json_obj[2].employee_income_tax[0].tax, 10) +
  	    	                parseFloat(json_obj[2].employee_income_tax[0].ec, 10) +
  	    	                parseFloat(json_obj[2].employee_income_tax[0].shec, 10);
  	    	           $('.tax_payable').html(reFormateByNumber(tax_tot.toFixed(2)));
  	    	           $('.net_tax_payable').html(reFormateByNumber(parseFloat(json_obj[2].employee_income_tax[0].tax_payable, 10)));
  	    	           tax_payable_tot =Math.max(0,(Number(deFormate($('.tax_payable').html()))- parseFloat(json_obj[2].employee_income_tax[0].relief, 10)));
  	    	           $('.tax_payable_tot').html(reFormateByNumber(tax_payable_tot.toFixed(2)));
  	    	           
  	    	           var element=$('#hiddenpartBText').html($('#partBContent').clone());
  	    	           element.find(".hiddentrPdf").each(function () {
  	    	              $(this).remove();
  	    	           });
  	    	           var beneEelement=$('#hiddenBeneifitsText').html($('#benefitsPaidContent').clone());
  	    	           beneEelement.find(".hiddentrPdf").each(function () {
  	    	              $(this).remove();
  	    	           });
  	    	         $(".summayHeader").hide();
  	    	          $("#partBContentId").val($('#partBContent').html());
  	    	          $('#benefitsPaidId').val($('#benefitsPaidContent').html());
  	    	        $('#go').button('reset'); 
  	    	       }else{
  	    	    	 $("#it_declare_tab,#view_summery,.tax1").hide();
  	    	    	 $('.tax1,.summary').hide();
  	    	    	 $(".summayHeader").show();
  	    	    	 $('#go').button('reset');
  	    	        
  	    	       }
  	    	     }
  	    	   });
                 
         });
         
             
</script>
</body>
</html>
