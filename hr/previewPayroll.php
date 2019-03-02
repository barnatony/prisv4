<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Payroll Preview</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/chosen-bootstrap.css" rel="stylesheet">

<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />

<style>
.giverscore_tooltip {
	cursor: pointer;
}
.input-group-addon {
	background-color: #fff;
	border-radius: 12px;
}

.adv-tables table tr td {
	padding: 9px;
}

#adv_ih tr td {
	border-top: 0px;
	padding: 1px;
}

.popover {
	width: 500px;
}

@media ( min-width : 992px) {
	.modal-lg {
		width: 800px;
	}
}
#ajax_loaders {
	display: block;
	margin: 0 auto;
}
</style>
</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php
					include_once (__DIR__ . "/header.php");
					$month_year = $_SESSION ['payrollYear'] . "-" . $_SESSION ['monthNo'] . "-01";
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once (__DIR__."/sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->

		<section id="main-content">
			<section class="wrapper site-min-height">
				<section class="panel">
					<header class="panel-heading">
                  Preview Payroll For <?php echo date('F Y', strtotime($_SESSION['current_payroll_month']));?>
				   <div class="btn-group pull-right">
                           &nbsp;
							<button id="showhideButton" type="button"
								class="btn btn-sm btn-info pull-right">
								<i class="fa fa-eye"></i> Preview
							</button>
						</div>
					</header>
					<input name="empFormat" id="empFormat" type="hidden" /> <input
						name="empCount" id="empCount" type="hidden" />
					
					<div class="panel-body" id="filterHideShowDiv">
					<form class="form-horizontal" role="form" method="post">
					   <?php
							require_once (LIBRARY_PATH . "/filter.class.php");
							$filter = new Filter ();
							$filter->conn = $conn;
					       echo  $filterHtml = $filter->createFilterForScreen ('previewPayroll');
					    ?>
						<div class="col-sm-3 panel-body showedEmp displayHide pull-right">
						    <div class="input-group">
						        <span class="input-group-addon"> 
						          <label 	class="checkbox-inline" style="margin-top: -8%;" id="forcerunMsg" data-toggle="popover" data-trigger="hover" data-container="body" 	data-placement="left" data-content="Include Already Previewed Employee(s). This May Take Minutes..">
						               <input type="checkbox" name="ForceRun" id="ForceRun" value="1"> Force Preview
						           </label>
								</span>
						        <input type="button" class="form-control btn btn-success  btn-sm inputBox" id="addendanceSubmit" value="Preview" />
						
						    </div>
						</div>
			
		             </form>
				</div>
				
				<img src="../img/ajax-loader.gif" style="display: none"
						id="ajax_loaders">
					<div style="display: none" id="previewTableHide">
						<div class="panel-body">
							<div class="adv-table editable-table">
								<section id="flip-scroll">
								<div id="tableContent" class="hide"></div>
									<div class="pull-right">
										<form method="post" action="php/payroll.handle.php"
											id="downloadPayroll">
											<input type="hidden" name="act" id="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!downloadPayrollPreview");?>">
											<input type="hidden" name="employee_id" id="employee_ids"
												value=""> 
											<button id="run_pay" type="button" class="btn btn-sm primary"
												title="Run Payroll For The Previewd Employees">
												<i class="fa fa-rocket"></i> Run Payroll
											</button>
											<button type="submit" id="downloadPayrolls"
												name="downloadPayrolls" class="btn btn-sm btn-success"
												title="Download Provisional Salary Statement">
												<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
												&nbsp;Statement
											</button>
											<button type="button" title="Download Provisional Salary Statement" class="btn btn-sm btn-warning" id="provisionalExcel" name="provisionalExcel">
												<input type="hidden" id="employees_ids">
												<i class="fa fa-download" aria-hidden="true"></i>
												&nbsp;Excel
											</button>
										</form>
									</div>
									<table
										class="table table-striped table-hover table-bordered cf"
										id="payroll-sample" width="100%">
										<thead class="cf">
											<tr>
												<th>EmpId</th>
												<th>EmpName</th>
												<th>LOP</th>
												<th>LLOP</th>
												<th>MasterGross</th>
												<th>Gross</th>
												<th>Deductions</th>
												<th>Net</th>
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
					</div>
					<div class="helpblock0" ><div class="alert" role="alert"><div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p ><i class="fa fa-caret-right" ></i> &nbsp;After Preview, You can get the breakup of salary by clicking on the <b>"Net Salary Amount"</b> of each employee.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;Clicking <b>"Statement"</b> will Download a provisional salary statement in PDF format for the previewed employees.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;Clicking <b>"Run Payroll"</b> will run the payroll (only for the previewed employees) with the previewed values. This action can't be rollbacked.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp; After Successful Payroll Run , on clicking <b>"Send Email"</b> will send the payslip to all the employee registered emails.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;You can See the top right month changed to next month after the successful run of last employee.</p><p ><i class="fa fa-caret-right" ></i>  &nbsp;Payroll of employees who's serving their last month can't be run from this screen. You can run it from <a href="noticePeriod.php#thismonth" style="text-decoration: underline;color:blue ">here</a></p></div></div></div>
				</section>
				<!-- page end-->
			</section>
		</section>

		<!--main content end-->
		<!--footer start-->
		<?php include_once (__DIR__."/footer.php");?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>

	<!--common script for all pages-->
	<script src="../js/jquery.validate.min.js"></script>

	<!--script for this page only-->
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
    <script src="../js/common-scripts.js"></script>
	<script src="../js/jquery.table2excel.js"></script>
	


	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
          $(document).ready(function () {
	      //side menu
              $('#sidebar').getNiceScroll().hide();
              $('.dcjq-parent').removeClass('active');
              $('.sub').css('display','none');
        	  flag=0;
        	  $('#forcerunMsg').popover();
        	  $( "#ForceRun" ).prop( "checked", false);
        	  var month="<?php echo $_SESSION['monthNo']." ".$_SESSION['payrollYear'];?>";
		     // $('#period_hide').toggle('hide');
              $('#run_pay').hide();
                            
			   $(document).on('click', "#run_pay", function () {
				   BootstrapDialog.show({
     	                title:'Confirmation',
                         message: 'Are Sure you want To Run Payroll For Previewed Employees',
                         closable: true,
                         closeByBackdrop: false,
                         closeByKeyboard: false,
                         buttons: [{
                             label: 'OK',
                             cssClass: 'btn-sm btn-success',
                             autospin: true,
                             action: function(dialogRef){
							 $.ajax({
                      dataType: 'html',
                      type: "POST",
                      url: "php/payroll.handle.php",
                      cache: false,
                      data: { act: '<?php echo base64_encode($_SESSION['company_id']."!run");?>',empFormat:$('#empFormat').val(),count: $('#empCount').val() },
                      beforeSend:function(){
                        	$('#run_pay').button('loading'); 
                          },
                          complete:function(){
                         	 $('#run_pay').button('reset');
                          },
                      success: function (data) {
                    	  $('.close').click();
                    	  $('#run_pay').hide();
                          data=JSON.parse(data);
                          if(data[0]=="error"){
                        	  BootstrapDialog.show({
                                  title: 'Information',
                                  message:"<h5 style='color:#ff6c60'> <i class='fa fa-times' aria-hidden='true'></i>  Can't Run Payroll. </h5>" +data[2],
                                  closable: true,
                                  closeByBackdrop: false,
                                  closeByKeyboard: true
                                  
                              });
                              return false;
                          }
                          BootstrapDialog.show({
           	               title:'Confirmation',
                              message: ' <h5  style="color: Green;"> <i class="fa fa-check" aria-hidden="true"></i> Payroll Runned Successfully </h5> <p> Do you Want to Send Email For Runned Employees </p>',
                              closable: true,
                              closeByBackdrop: false,
                              closeByKeyboard: true,
                              buttons: [{
                                  label: 'Send Email',
                                  cssClass: 'btn-sm btn-success',
                                  autospin: true,
                                  action: function(dialogRef){
                                     $.ajax({
                                         dataType: 'html',
                                         type: "POST",
                                         url: "  php/payroll.handle.php",
                                         cache: false,
                                         data: { act: '<?php echo base64_encode($_SESSION['company_id']."!sendEmail");?>', empFormat:$('#empFormat').val() },
                  		                  success: function (data) {
                  		                	 var json_obj  = JSON.parse(data);
                  		                	 console.log(json_obj);
                                            if(json_obj[2]){
                                                $('.close').click();
                                                BootstrapDialog.show({
                                                    title: 'Information',
                                                    message:"<h5 style='color:#ff6c60'> <i class='fa fa-times' aria-hidden='true'></i>  Can't sent Email For Following employees </h5>" +json_obj[2],
                                                    closable: true,
                                                    closeByBackdrop: false,
                                                    closeByKeyboard: false,
                                                    buttons: [{
                                                        label: 'OK',
                                                        action: function(dialog) {
                                                        	window.location.assign("viewPayroll.php"); 
                                                        }
                                                    }]
                                                });
                                                
                                             }else{
                                            	$('.close').click();
                                            	 BootstrapDialog.show({
                                                     title: 'Information',
                                                     message:"<h5  style='color: Green;'> <i class='fa fa-check' aria-hidden='true'></i>  Email Sent Successfully For Runned Employees </h5>",
                                                     closable: true,
                                                     closeByBackdrop: false,
                                                     closeByKeyboard: false,
                                                     buttons: [{
                                                         label: 'OK',
                                                         action: function(dialog) {
                                                         	window.location.assign("viewPayroll.php"); 
                                                         }
                                                     }]
                                                 });
                                               }
                                          }
                                     });
                                	 
                            }
                              }, {
                                  label: 'No',
                                  cssClass: 'btn-sm btn-danger',
                                  action: function(dialogRef){
                                      dialogRef.close(); 
                                      window.location.assign("viewPayroll.php");  
                                  }
                              }]
                          });
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

				 $(document).on('click', "#addendanceSubmit", function (e) {
                  e.preventDefault();
                  var id=$("#employeeSelected").chosen().val();
            	  if(id){
            		  $('#ajax_loaders').show();
            		  $('#showhideButton').click();
            	$.ajax({
                      dataType: 'html',
                      type: "POST",
                      url: "php/payroll.handle.php",
                      cache: false,
                      data: { act: '<?php echo base64_encode($_SESSION['company_id']."!preview");?>', affectedIds:id,month_year: month ,forceRun:$('input[name="ForceRun"]:checked').val() },
                      beforeSend:function(){
                        $('#submit_value').button('loading'); 
                        },
                      complete:function(){
                         	 $('#submit_value').button('reset');
                          },
                      success: function (data) {
                    	  getEmployeeIds = JSON.parse(data);
                       $('#ajax_loaders').hide();
                       $( "#ForceRun" ).prop( "checked", false);
                       $('#showhide').show();
					  $('#previewTableHide').removeClass('hide show');
					   $('#previewTableHide').addClass('show');
					 
					     $('.deselect').click();   
						  if(flag==0){
					    	 EditableTable.init(id,month);
					    	 flag=1;
					    }else{
					    	  var oTable = $('#payroll-sample').dataTable();
	                          oTable.fnDestroy();
	                          EditableTable.init(id,month);
	                         
	                    }
                    	   
                          $('#payroll-sample,#run_pay').show(); 
						  var leaveCreditEmp='';
						  var moveEmprunned='';
						  for (var i = 0, len = getEmployeeIds[2][0].length; i < len; ++i) {
			                  $.each(getEmployeeIds[2][0][i], function (k, v) {
							  if(k=='employee_id')
							  {
						      leaveCreditEmp +=v+",";
							  }
					          });
								}
								  $('#empFormat,#empCount').val('');
								  $('#empFormat').val(leaveCreditEmp.slice(0, -1));
								  $('#employee_ids').val(leaveCreditEmp.slice(0, -1));
								  $('#employees_ids').val(leaveCreditEmp.slice(0, -1));
								  $('#empCount').val(getEmployeeIds[2][1]);
								  bf_flag_download = 0;
								  
				      }
                  });
            	  }else{
            	  }
                 });
            	            
          });
          
          var EditableTable = function () {

        	    return {

        	        //main function to initiate the module
        	        init: function ( id,month) {
        	        	  var oTable = $('#payroll-sample').dataTable({
        	                  "aLengthMenu": [
        	                    [5, 15, 20, -1],
        	                    [5, 15, 20, "All"] // change per page values here
        	                ],

        	                // set the initial value
        	                "iDisplayLength": 5,
        	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
        	                "bProcessing": true,
        	                "bServerSide": true,
	        				"sAjaxSource": "php/payroll_view.php",
        	                 "fnServerParams": function (aoData) {
								 console.log(aoData);
        	                    aoData.push({ "name": "affectedIds", "value": id },
        	                    { "name": "month", "value": month });
        	                },
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
        	                       { "sName": "w.employee_id" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
        	                        {"sName": "w.employee_name" },
									{"sName": "cl.late_lop" },
        	                        { "sName": "s.employee_salary_amount" },
        	                        { "sName": "cl.gross" },
        	                        { "sName": "cl.tot_deduction" },
        							{ "sName": "cl.net" },
        	                        { "sName": "cl.status_flag", "bSortable": false },
        	                     	

        	                          ], 
									
									  "aoColumnDefs": [{
        	                     	      // `data` refers to the data for the cell (defined by `mData`, which
        	                     	      // defaults to the column being worked with, in this case is the first
        	                     	      // Using `row[0]` is equivalent.
        	                     	    
        	                     	      "mRender": function (data, type, row) {
        	                         		if(data){
        	                         		
											return '<span class="giverscore_tooltip" data-loaded="false" title="" data-content="" data-placement="left" id='+row[0]+' data-input='+row[0]+' tabindex="0"><a href="javascript:void(0)">'+row[7]+'</a></span>';
											
											}	      
        	                         
        	                     	      },
        	                         	   "aTargets": [7]
        	                         	      
        	                     	      
        	                     	  }], 
        	                    	                          
        	               "oTableTools": {
        	                    "aButtons": [
        	                {
        	                    "sExtends": "pdf",
        	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7,8,9,10,11,12],
        	                    "sPdfOrientation": "landscape",
        	                    "sPdfMessage": "Branch Details"
        	                },
        	                {
        	                    "sExtends": "xls",
        	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7,8,9,10,11,12]
        	                }
        	             ],
        	                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

        	                }

        	            });
        	
        	        
        	            $('#payroll-sample_wrapper .dataTables_filter').html('<div class="input-group">\
        	                    <input class="form-control medium" id="searchInputs" type="text">\
        	                    <span class="input-group-btn">\
        	                      <button class="btn btn-white" id="searchFilters" type="button">Search</button>\
        	                    </span>\
        	                    <span class="input-group-btn">\
        	                      <button class="btn btn-white" id="searchClears" type="button">Clear</button>\
        	                    </span>\
        	                </div>');
        	   		 $('#payroll-sample_processing').css('text-align', 'center');
        	         jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
        	   		 $('#searchInputs').on('keyup', function (e) {
        	   			 if (e.keyCode == 13) {
        	                    oTable.fnFilter($(this).val());
        	                } else if (e.keyCode == 27) {
        	                    $(this).parent().parent().find('input').val("");
        	                    oTable.fnFilter("");
        	                }
        	   			 });
        	   		 $('#searchFilters').on('click', function () {
        	                oTable.fnFilter($(this).parent().parent().find('input').val());
        	            });
        	            $('#searchClears').on('click', function () {
        	                $(this).parent().parent().find('input').val("");
        	                oTable.fnFilter("");
        	            });
        	          //sal details popover
                	    $(document).popover({
                    		selector: '.giverscore_tooltip',
                    		trigger:'focus',
                    		html:true,
                    		animation: true,
                    		
                    		content: '<img src="../img/ajax-loader.gif" id="ajax_loader_getmessages">', 
                   		});

                   		//popover content load
                	    $(document).on('shown.bs.popover','.giverscore_tooltip',function(e){
                    	    e.preventDefault();
            				var thisd = this;
            				if($(this).data('loaded') == false){
            				var value = $(this).data('input');
            			
            				$.ajax({
                                dataType: 'html',
                                type: "POST",
                                url: "php/payroll.handle.php",
                                cache: false,
                                data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getSalaryDetails");?>',emp_id:value,current_month:'1'},
                                success: function (data) {
                                 data = JSON.parse(data);
                                
                              var html="<table class='table ' id='adv_ih'><tbody>"; var allowances ='<tr><td colspan="3"><b>Allowance</b></td></tr>'; var deduction ='<tr><td colspan="3"><b>Deduction</b></td></tr>';
                                for(i=0;i<data[2].length;i++){
                                	 $.each(data[2][i],function(k,v){
                                    	 
                                    	 if(v!=0.00 || v!=0){
                                    		 var str=k.split('_');
                                        	if(str[0]=='A' || str[0]=='MP'){
                                        		allowances += '<tr ><td>'+str[1]+'</td><td>:</td><td style="text-align:right">'+v+'</td></tr>';
                                        		}else if(str[0]=='D' || str[0]=='MD'){
                                        deduction += '<tr ><td>'+str[1]+'</td><td>:</td><td style="text-align:right">'+v+'</td></tr>';
                                        		}else{
                                        			gross= '<tr ><td>'+str[0]+'</td><td>:</td><td style="text-align:right">'+v+'</td></tr>';
                                            		}
                                       }
                                    	 });
                                 }
                                html +=allowances+gross;
                                html +=deduction;
                                 html += '</tbody></table>';
                                 var popovers = $(thisd).attr('data-content',html).popover('show');
                                
                                }
                            });
            				}else{
            					 
            					 
            				}
            				 $(thisd).data('loaded',true);
                    	});
        	         
        	        }

    	        

        	    };

        	} ();
            
        	$(document).on('click','#downloadPayroll',function(e){
            	if(bf_flag_download == 0){
            	//document.getElementById("downloadPayrolls").submit();
            	}
            	bf_flag_download = 1;
            });	
			
			//Export To excel Base
       $(document).on('click', "#provisionalExcel", function (e) {
		     e.preventDefault();
           	 var empID= $('#employees_ids').val();
       	   $.ajax({
               dataType: 'html',
               type: "POST",
               url: "php/payroll.handle.php",
               cache: false,
               data:{act: '<?php echo base64_encode($_SESSION['company_id']."!downloadPreviewExcel");?>',employeesId:empID},
               beforeSend:function(){
               	$('#provisionalExcel').button('loading'); 
                 },
                 complete:function(){
                $('#provisionalExcel').button('reset');
                 },
               success: function (data) {
              	 jsonobject = JSON.parse(data);
              	 html ='';
              	html +='<table id="example1"><thead>';
              	html+='</thead><tbody><tr>';
				for (var i=0;i<jsonobject['2'].length;i++){
              		html +='<tr>';
              		$.each(jsonobject['2'][i], function( k, v ) {
						if(i!= 0){
							html+='<td>'+v+'</td>';
						}else{
							html+='<td><b>'+v+'<b></td>';
						}
                  	});
              		html +='</tr>';
              	}
            	html+='</tr></tbody></table>';
				//console.log(html);
              	$('#tableContent').html(html);
               }

               });
			   
       	setTimeout(function(){  $("#example1").table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: 'Provisional_Salary_Statement',
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});  }, 100);
    	      //export table
    	     
    	     });
        	
        	
           	                                       	
      </script>
</body>
</html>
