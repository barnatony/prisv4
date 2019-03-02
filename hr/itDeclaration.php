	<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>IT Declaration</title>

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
<link href="../css/toastr.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style>
.numeric {
	text-align: center;
}
.get_emp_id {
		width:230px;
		height:200px;
}
</style>
</head>

<body>

<section id="container">
		<!--header start-->
<?php include_once (__DIR__."/header.php"); ?>
<!--header end-->
		<!--sidebar start-->
		<aside>
<?php include_once (__DIR__."/sideMenu.php");?>
</aside>

		<!--sidebar end-->
		
			<!-- header for past years starts here -->
			
<section id="main-content">
			<section class="wrapper site-min-height" >
				<section class="panel year-panel" id="section_hide">
					<header class="panel-heading"> View IT Declaration </header>
			<!-- page start-->

			<div class="panel-body">
				 <div class="container col-lg-12">	
				           <label class="col-lg-3 control-label" style="font-weight: 300;font-size: 14px;text-align: left;">View IT Declaration Using Year</label>
							 	<div class="col-lg-4 input-group">
							     <select class="form-control " required
												name="year" id="year" style="margin-bottom: 20px">
											
												
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
								<button type="submit" class="btn btn-sm btn-success" id="go">View</button>
						</div>
					</div>			
			</div>		
			
	       </section>
  	
	<!-- header for past years end -->
				
                	<div class="panel-body" style="margin-top: -12px;">
					<div class="emp_list1">
						<div class="alphabet">
							<ul class="directory-list">
								<li><a class="letter" href="#"><i class="fa fa-refresh"></i></a></li>
								<li><a class="letter" href="#">a</a></li>
								<li><a class="letter" href="#">b</a></li>
								<li><a class="letter" href="#">c</a></li>
								<li><a class="letter" href="#">d</a></li>
								<li><a class="letter" href="#">e</a></li>
								<li><a class="letter" href="#">f</a></li>
								<li><a class="letter" href="#">g</a></li>
								<li><a class="letter" href="#">h</a></li>
								<li><a class="letter" href="#">i</a></li>
								<li><a class="letter" href="#">j</a></li>
								<li><a class="letter" href="#">k</a></li>
								<li><a class="letter" href="#">l</a></li>
								<li><a class="letter" href="#">m</a></li>
								<li><a class="letter" href="#">n</a></li>
								<li><a class="letter" href="#">o</a></li>
								<li><a class="letter" href="#">p</a></li>
								<li><a class="letter" href="#">q</a></li>
								<li><a class="letter" href="#">r</a></li>
								<li><a class="letter" href="#">s</a></li>
								<li><a class="letter" href="#">t</a></li>
								<li><a class="letter" href="#">u</a></li>
								<li><a class="letter" href="#">v</a></li>
								<li><a class="letter" href="#">w</a></li>
								<li><a class="letter" href="#">x</a></li>
								<li><a class="letter" href="#">y</a></li>
								<li><a class="letter" href="#">z</a></li>
								<li><input type="hidden" id="rep_man-id" name="rep_man-id"> <input
									type="text" class="form-control search letter"
									id="employee_id-id" name="employee_id" autocomplete="off"
									style="margin-top: -18px; background-color: white; margin-bottom: -11px"
									required /> <input type="hidden" id="it_employee-id"></li>
							</ul>
						</div>
						<div class="directory-info-row">
							<div class="row">
								<div id="empView"></div>
							</div>
						</div>
					</div>
				</div>

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
				<section class="panel displayHide" id="it_declare_summery"
					style="margin-top: -10px;">
					<header class="panel-heading" id="hrHeader">
						<div class="name_emp"></div>
					<div class="pdf pull-right" style="margin-left:894px;margin-top:-23px;">	
						<button class="btn btn-sm btn-danger" type="button" id="back_p">
									Back</button>
									</div>
						<div class="pull-right">
						
							<form  action="../common/itDeclaration.handle.php" method="post">
								<input name="employee_id" type="hidden" id="pdfEmpid">
								<input name="year" type="hidden" id="pdfYear"> 
								<input name="act" type="hidden" value="<?php echo base64_encode($_SESSION['company_id']."!taxSummaryPdf");?>" >
								<div class="displayHide" id="hiddenpartBText"></div>
								<div class="displayHide" id="hiddenBeneifitsText"></div>
								<input name="partBContent" type="hidden" id="partBContentId">
								<input name="panNum" type="hidden" id="empPan"> 
								<input name="empName" type="hidden" id="empName"> 
								<input name="benefitsPaid" type="hidden" id="benefitsPaidId">
								<button class="btn btn-sm btn-default pull-right" type="submit" style="margin-left:894px;margin-top:11px;">
									<i class="fa fa-file-pdf-o"></i> PDF
								</button>
							</form>
						</div>
					</header>
				 <?php require_once  ( dirname ( __DIR__ ) . "/common/itSummaryText.php"); ?>
				<!--tab nav end-->
         	</section>
		</section>
		<!--main content end-->
		<!--footer start-->
<?php include_once (__DIR__."/footer.php"); ?>
<!--footer end-->
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
	<script src="../js/toastr.min.js"></script>
	<!--script for this page only-->
	<script src="../js/ZeroClipboard.js"></script>
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">

$(document).ready(function() {
	itDeclarationActData='<?php echo base64_encode($_SESSION['company_id']."!getItDeclarationData");?>';
	itSummeryActData='<?php echo base64_encode($_SESSION['company_id']."!taxSummaryData");?>';
	current_payroll_month='<?php echo $_SESSION['current_payroll_month'];?>';
    localStorage.setItem("itSummeryActData",itSummeryActData);
	localStorage.setItem("itDeclarationActData",itDeclarationActData );
	var employeeId='<?php echo isset($_REQUEST['employeeID'])?$_REQUEST['employeeID']:"";?>';
	var year = <?php echo  $_SESSION['financialYear'];?>;
	$("#pdfYear").val(year);
	if(employeeId){
		  it_view(employeeId);
		  $('#pdfEmpid').val(employeeId);
	        $(".emp_list1").hide();
	 
	        setTimeout(function(){ 
	    		GroupEmp();
	    //design for list 
	    }, 3000);
		}else{
			GroupEmp();
			
			}
});


$(document).on('click','#go',function(e){
e.preventDefault();
var inActive = <?php echo isset($_REQUEST['inActive'])?$_REQUEST['inActive']:0;?>;
var year=$('#year').val();
$("#pdfYear").val(year);
$.ajax({
   dataType: 'html',
    type: "POST",
    url: "../common/itDeclaration.handle.php",
    cache: false,
    data: {act:'<?php echo base64_encode($_SESSION['company_id']."!getItempView");?>',
    	     inActive:inActive,year:year},
    beforeSend:function(){
    	$('#go').button('loading'); 
    },
	complete:function(){
		$('#go').loading(false);
		},
    success: function (data){
       var json_obj = JSON.parse(data);
    	  var numberOfElements = json_obj[2].length;
    	    $("#empView").empty();
    	  for (i = 0; i < numberOfElements; i++) {
    		  $('#pdfYear').append(year);
        	  if((json_obj[2][i].years)==0){
		        if(json_obj[2][i].enabled==1){
	          $('#empView').append('<div class="col-md-3 col-sm-3 view1" ><div class="panel"><div class="get_emp_id panel-body "><div class="media"><div class="empImageDiv"><input type="hidden" value=' + json_obj[2][i].employee_id + '> </div><div class="media-body" style="font-size: 12px;"><h4 style="font-size: 12px;margin-bottom:0px;">' + json_obj[2][i].employee_name + ' [' + json_obj[2][i].employee_id + ' ]</h4><em>' + json_obj[2][i].designation_name + '</em> , <em>' + json_obj[2][i].department_name + '</em><br><br><strong>Tax</strong> : <em>' + json_obj[2][i].tax+'</em> <br><strong>Tax Paid</strong> : <em>' + json_obj[2][i].tax_paid + '</em><br>      <strong>Tax Payable</strong> : <em>' + json_obj[2][i].tax_payable + '</em><br><br>   <input type="hidden" id="emp_id_s" value=' + json_obj[2][i].employee_id + '><div class="col-md-12 empImageDiv"><input type="button" class="btn btn-sm btn-info view_it"    value="View" >&nbsp;<input type="button" class="btn btn-sm btn-info" value="summary" data-name="'+json_obj[2][i].employee_name+'" data-pan="'+json_obj[2][i].employee_pan_no+'"  id="summary_s"></div></div></div></div></div></div>');
				$('.previousEmpEdit').hide();
				$('.declarationEdit').hide();
		        }else
		        {
		        	$('#empView').append('<div class="col-md-3 col-sm-3 view1" ><div class="panel"><div class="get_emp_id panel-body "><div class="media"><label style=" background-color: transparent; color: #FCB322;border: 1px solid #FCB322;padding: 0px 2px 0px 2px; display: inline-block;font-size: 13px;position: absolute; right: 30px; margin-top: 2px;">Inactive</label><div class="empImageDiv"><input type="hidden" value=' + json_obj[2][i].employee_id + '> </div><div class="media-body" style="font-size: 12px;"><h4 style="font-size: 12px;margin-bottom:0px;">' + json_obj[2][i].employee_name + ' [' + json_obj[2][i].employee_id + ' ]</h4><em>' + json_obj[2][i].designation_name + '</em> , <em>' + json_obj[2][i].department_name + '</em><br><br><strong>Tax</strong> : <em>' + json_obj[2][i].tax+'</em> <br><strong>Tax Paid</strong> : <em>' + json_obj[2][i].tax_paid + '</em><br>      <strong>Tax Payable</strong> : <em>' + json_obj[2][i].tax_payable + '</em><br><br>   <input type="hidden" id="emp_id_s" value=' + json_obj[2][i].employee_id + '> &nbsp;<input type="button" class="btn btn-sm btn-info" style="margin-left:45px;" value="summary" data-name="'+json_obj[2][i].employee_name+'" data-pan="'+json_obj[2][i].employee_pan_no+'"  id="summary_s"></div></div></div></div></div></div>');
		        	$('.previousEmpEdit').hide();
		        	$('.declarationEdit').hide();
			        }
		        $('#go').button('reset');  
         }else
        	 if(json_obj[2][i].enabled==1){
   	          $('#empView').append('<div class="col-md-3 col-sm-3 view1" ><div class="panel"><div class="get_emp_id panel-body "><div class="media"><div class="empImageDiv"><input type="hidden" value=' + json_obj[2][i].employee_id + '> </div><div class="media-body" style="font-size: 12px;"><h4 style="font-size: 12px;margin-bottom:0px;">' + json_obj[2][i].employee_name + ' [' + json_obj[2][i].employee_id + ' ]</h4><em>' + json_obj[2][i].designation_name + '</em> , <em>' + json_obj[2][i].department_name + '</em><br><br><strong>Tax</strong> : <em>' + json_obj[2][i].tax+'</em> <br><strong>Tax Paid</strong> : <em>' + json_obj[2][i].tax_paid + '</em><br>      <strong>Tax Payable</strong> : <em>' + json_obj[2][i].tax_payable + '</em><br><br>   <input type="hidden" id="emp_id_s" value=' + json_obj[2][i].employee_id + '>  <div class="col-md-12 empImageDiv"><input type="button" class="btn btn-sm btn-info view_it"    value="View" >&nbsp;<input type="button" class="btn btn-sm btn-info" value="summary" data-name="'+json_obj[2][i].employee_name+'" data-pan="'+json_obj[2][i].employee_pan_no+'"  id="summary_s"></div></div></div></div></div></div>');
   	       			$('.previousEmpEdit').show();
   					$('.declarationEdit').show();
  		        }else
   		        {
   		        	$('#empView').append('<div class="col-md-3 col-sm-3 view1" ><div class="panel"><div class="get_emp_id panel-body "><div class="media"><label style=" background-color: transparent; color: #FCB322;border: 1px solid #FCB322;padding: 0px 2px 0px 2px; display: inline-block;font-size: 13px;position: absolute; right: 30px; margin-top: 2px;">Inactive</label><div class="empImageDiv"><input type="hidden" value=' + json_obj[2][i].employee_id + '> </div><div class="media-body" style="font-size: 12px;"><h4 style="font-size: 12px;margin-bottom:0px;">' + json_obj[2][i].employee_name + ' [' + json_obj[2][i].employee_id + ' ]</h4><em>' + json_obj[2][i].designation_name + '</em> , <em>' + json_obj[2][i].department_name + '</em><br><br><strong>Tax</strong> : <em>' + json_obj[2][i].tax+'</em> <br><strong>Tax Paid</strong> : <em>' + json_obj[2][i].tax_paid + '</em><br>      <strong>Tax Payable</strong> : <em>' + json_obj[2][i].tax_payable + '</em><br><br>   <input type="hidden" id="emp_id_s" value=' + json_obj[2][i].employee_id + '>  <div class="col-md-12 empImageDiv"><input type="button" class="btn btn-sm btn-info view_it"    value="View" >&nbsp;<input type="button" class="btn btn-sm btn-info" style="margin-left:45px;" value="summary" data-name="'+json_obj[2][i].employee_name+'" data-pan="'+json_obj[2][i].employee_pan_no+'"  id="summary_s"></div></div></div></div></div></div>');
   		        	$('.previousEmpEdit').show();
		        	$('.declarationEdit').show();
   	   		        }
   		       $('#go').button('reset');  
          }
         
    }
});
});	



function GroupEmp(){
	var inActive = <?php echo isset($_REQUEST['inActive'])?$_REQUEST['inActive']:0;?>;
	var year=$('#year').val();
	$.ajax({
	    dataType: 'html',
	    type: "POST",
	    url: "../common/itDeclaration.handle.php",
	    cache: false,
	    data: {act:'<?php echo base64_encode($_SESSION['company_id']."!getItempView");?>',inActive:inActive,year:year},
	    beforeSend:function(){
	    
	    	$("#empView").loading(true);
	      },
	      complete:function(){
	    $("#empView").loading(false);
	 
	      },
	    success: function (data) {
	    	var json_obj = JSON.parse(data);
	        var numberOfElements = json_obj[2].length;
	        $("#empView").empty();
	        for (i = 0; i < numberOfElements; i++) {
		        if(json_obj[2][i].enabled==1){
	          $('#empView').append('<div class="col-md-3 col-sm-3 view1" ><div class="panel"><div class="get_emp_id panel-body "><div class="media"><div class="empImageDiv"><input type="hidden" value=' + json_obj[2][i].employee_id + '> </div><div class="media-body" style="font-size: 12px;"><h4 style="font-size: 12px;margin-bottom:0px;">' + json_obj[2][i].employee_name + ' [' + json_obj[2][i].employee_id + ' ]</h4><em>' + json_obj[2][i].designation_name + '</em> , <em>' + json_obj[2][i].department_name + '</em><br><br><strong>Tax</strong> : <em>' + json_obj[2][i].tax+'</em> <br><strong>Tax Paid</strong> : <em>' + json_obj[2][i].tax_paid + '</em><br>      <strong>Tax Payable</strong> : <em>' + json_obj[2][i].tax_payable + '</em><br><br>   <input type="hidden" id="emp_id_s" value=' + json_obj[2][i].employee_id + '>  <div class="col-md-12 empImageDiv"><input type="button" class="btn btn-sm btn-info view_it"    value="View" >&nbsp;<input type="button" class="btn btn-sm btn-info" value="summary" data-name="'+json_obj[2][i].employee_name+'" data-pan="'+json_obj[2][i].employee_pan_no+'"  id="summary_s"></div></div></div></div></div></div>');
		        }else
		        {
		        	$('#empView').append('<div class="col-md-3 col-sm-3 view1" ><div class="panel"><div class="get_emp_id panel-body "><div class="media"><label style=" background-color: transparent; color: #FCB322;border: 1px solid #FCB322;padding: 0px 2px 0px 2px; display: inline-block;font-size: 13px;position: absolute; right: 30px; margin-top: 2px;">Inactive</label><div class="empImageDiv"><input type="hidden" value=' + json_obj[2][i].employee_id + '> </div><div class="media-body" style="font-size: 12px;"><h4 style="font-size: 12px;margin-bottom:0px;">' + json_obj[2][i].employee_name + ' [' + json_obj[2][i].employee_id + ' ]</h4><em>' + json_obj[2][i].designation_name + '</em> , <em>' + json_obj[2][i].department_name + '</em><br><br><strong>Tax</strong> : <em>' + json_obj[2][i].tax+'</em> <br><strong>Tax Paid</strong> : <em>' + json_obj[2][i].tax_paid + '</em><br>      <strong>Tax Payable</strong> : <em>' + json_obj[2][i].tax_payable + '</em><br><br>   <input type="hidden" id="emp_id_s" value=' + json_obj[2][i].employee_id + '>  <div class="col-md-12 empImageDiv">&nbsp;<input type="button" class="btn btn-sm btn-info" style="margin-left:45px;" value="summary" data-name="'+json_obj[2][i].employee_name+'" data-pan="'+json_obj[2][i].employee_pan_no+'"  id="summary_s"></div></div></div></div></div></div>');
		        }

		         if (json_obj[2][i].status_id == "P") { 
		      
	          $('.view_it').css("background-color", "rgb(79, 161, 192)");
	         }
	 
	      else if (json_obj[2][i].status_id == "A") {
	    	 
	          $('.view_it').css("background-color", "#58C9F3");
	      }
	        }
	      
	    }

	});
	
}


    $(document).on('click', '#back_p', function () {
    	 $(".year-panel").show();
    	
        $('#it_declare_tab,#it_declare_summery').hide();
        $(".emp_list1").show();
       
		 $('#employee_id-id').val('');
				    $('#rep_man-id').val('');
				
			//$("#empView").find('div').css("display", "block");
    });


    $("a.letter").click(function () {
        var letter = $(this).html();
        $("a.letter").each(function () {
           $(this).css({ 'background-color': '', 'color': '' });
        });
        $(this).css({ 'background-color': '#FF6C60', 'color': 'white' });

        if (letter == '<i class="fa fa-refresh"></i>') {
            $(".view1").each(function () {
                $(this).show();
            });
        }
        else {
            $(".view1").each(function () {
                var liText = $(this).find("h4:first").html();
                if (liText.indexOf(letter.toUpperCase()) == 0 || liText.indexOf(letter.toLowerCase()) == 0) {
                    $(this).show();
                }
                else {
                    $(this).hide();
                }
            });
        }
    });

    $('#empView').on('click', '.view_it', function () {
        view_yr = $('#year').val();
        employee_id = $(this).parent().parent().find("input").val();
         $('#employeeIdForIt,#pdfEmpid').val(employee_id);
        it_view(employee_id,view_yr);
        $(".emp_list1").hide();
       $(".year-panel").hide();
    });

    $('#empView').on('click', '#summary_s', function () {
        employee_id = $(this).parent().parent().find("input").val();
        $('#pdfEmpid').val(employee_id);
        $('#empPan').val($(this).data('pan') );
        $('#empName').val($(this).data('name')  );
        it_summary(employee_id);
        $(".emp_list1").hide();
        $(".year-panel").hide();
     });
    
       
(function ($) {
                   jQuery.expr[':'].Contains = function (a, i, m) {
                       return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
                   };

                   //live search function
                   function live_search(list) {
                       $("#employee_id-id").change(function () {
                           //getting search value
                           var searchtext = $(this).val();
                           if (searchtext) {
                               //finding If content matches with searck keyword
                               $matches = $(list).find('div:Contains(' + searchtext + ')');
							  //hiding non matching lists
                               $('div', list).not($matches).not('.empImageDiv').slideUp();
                               //showing matching lists
                               $matches.slideDown();

                           } else {
                               //if search keyword is empty then display all the lists
                               $(list).find("div").slideDown(200);
                           }
                           return false;
                       })
               .keyup(function () {
                   $(this).change();
               });
                   }

                   $(function () {
                       live_search($("#empView"));
                   });
               } (jQuery));



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
    $(this).parent().parent().parent().find('td').not(".empActual").each(function () {
        $(this).find('input').prop("disabled",false);

        
   });
    Command: toastr["info"]("Changes made in the declaration will effects on the IT Summary by the next successful payroll preview/run.")

	toastr.options = {
			  "closeButton": true,
			  "debug": false,
			  "progressBar": false,
			  "positionClass": "toast-top-right",
			 // "onclick": null,
			  "showDuration": "200",
			  "hideDuration": "1000",
			  "timeOut": "3000",
			  "extendedTimeOut": "3000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			}
	

	
    
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
	    element.find('input').not(".empActual,input[type=file]").each(function () {
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
                 
                 }}
              else
               BootstrapDialog.alert('Cant be upload '+data1[2]);
              $('#'+$(this).data('id')).button('reset'); 
          }

      });
  });

$(document).on('click', '.previousEmpEdit', function (e) {
	e.preventDefault();
	$(this).hide();
	$('#itdeclare_previous_emp').find('input,textarea').not('.hrApproved').each(function () {
	    $(this).attr('disabled', false);
    });
	 $('#prevDisable').attr('disabled', false);
    $('.previousEmpUpdate,.previousEmpcancel,.attach').show();

    Command: toastr["info"]("Changes made in the declaration will effects on the IT Summary by the next successful payroll preview/run.")

	toastr.options = {
			  "closeButton": true,
			  "debug": false,
			  "progressBar": false,
			  "positionClass": "toast-top-right",
			  "onclick": null,
			  "showDuration": "200",
			  "hideDuration": "1000",
			  "timeOut": "3000",
			  "extendedTimeOut": "3000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			}
	
    
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
	  $('#itdeclare_previous_emp').find('input,textarea').not('.hrApproved,.itImagechange').each(function () {
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
              }}
              else
              BootstrapDialog.alert('Cant be upload '+data1[2]);
             $('.previousEmpUpdate').button('reset'); 
          }

      });
     // }
  });


</script>
</body>
</html>
