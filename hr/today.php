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

<title>Today</title>

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
#branch_loc_chosen {
	width: 100% !important;
	margin-top: 1px !important;
}
.dash-today.table tbody > tr > td, .table tfoot > tr > td {
    padding: 6px;
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
			<section class="wrapper">
				<!--state overview start-->
				<div class="row">
					<div class="col-lg-6">
						<p style="font-size:18px;">Showing Attendance Statistics for <?php $now = new DateTime();
											$date = $now->format('d,M Y');
											//$date = date('d,M Y',strtotime("-1 days"));
											echo $date;
											?>
						</p>
						
					</div>
					<div class="col-lg-2 pull-right" style="margin-bottom:12px;">
						<select class="form-control" id="branch_loc"
									name="branch_loc">
							<option value="">Select Branch</option>
	                                        <?php
																$stmt = mysqli_prepare ( $conn, "SELECT branch_id, branch_name FROM company_branch" );
																$result = mysqli_stmt_execute ( $stmt );
																mysqli_stmt_bind_result ( $stmt, $branch_id, $branch_name );
																while ( mysqli_stmt_fetch ( $stmt ) ) {
																	echo "<option value='" . $branch_id . "'>" . $branch_name . "</option>";
																}
																mysqli_stmt_close($stmt);
													?> 
	                      </select>
					</div>				
				</div>
			
				
			
				<div class="row state-overview">
					<div class="col-lg-3 col-sm-6">
						<section class="panel">
							<div class="symbol blue">
								<i class="fa fa-user"></i>
							</div>
							<div class="value" style="padding-top: 5px;">
								<div id="presentEmp-widget" class="present_Emp panel-body">
									<p class="presentEmp" style="font-size:23px;cursor:pointer;"></p>
									<p>Present Today</p>
			         			</div>
							</div>
						</section>
					</div>
					
					<div class="col-lg-3 col-sm-6">
						<section class="panel">
							<div class="symbol green">
								<i class="fa fa-user-times"></i>
							</div>
							<div class="value" style="padding-top: 5px;">
								<div id="absentEmp-widget" class="absent_Emp  panel-body">
									<p class="absentEmp" style="font-size:23px;cursor:pointer;"></p>
									<p>Absent Today</p>
								</div>
							</div>
						</section>
					</div>
					
					<div class="col-lg-3 col-sm-6">
						<section class="panel">
							<div class="symbol blue">
								<i class="fa fa-user-plus"></i>
							</div>
							<div class="value" style="padding-top: 5px;">
								<div id="active-widget" class="active_Users panel-body">
									<p class="activeUsers" style="font-size:23px;cursor:pointer;"></p>
									<p>Active Users</p>
								</div>
							</div>
						</section>
					</div>
					
					<div class="col-lg-3 col-sm-6">
						<section class="panel">
							<div class="symbol green">
								<i class="fa fa-clock-o"></i>
							</div>
							<div class="value" style="padding-top: 5px;">
								<div id="late-widget" class="late_Comers panel-body">
									<p class="lateComers" style="font-size:23px;cursor:pointer;"></p>
									<p>Late Comers</p>
								</div>
							</div>
						</section>
					</div>
				</div>
					<!--state overview end-->
 
				<div class="row">
					<div class="col-lg-6">
						<!--Employee Logins start-->
						<div class="panel terques-chart" style="background: 0px;">
							<div class="chart-tittle" style="border-radius: 4px 4px 0px 0px;">
								<span class="title">Attendance Activity</span>
								
							</div>
							<div class="panel-body chart-texture"
								style="border-radius: 0px 0px 4px 4px; padding: 0;">

								<div class="chart">

									<div id="morris">
										<div id="loader1" style="width:95%;height:90%"></div>
										<div id="pay-chart" class="graph" style="height: 325px">
										</div>
										
										<span class="no-data"></span>
									</div>
								</div>
							</div>

						</div>
						<!--Employee Logins end-->
					</div>
					
					<div class="col-lg-6">
						<!--Late Comers table start-->
						<div class="panel terques-chart" style="background: 0px;">
							<div class="chart-tittle" style="border-radius: 4px 4px 0px 0px;background-color: #41cac0;">
								<span class="tble_title" ></span>
								<input type="hidden" class="table_key" id="table_key">
								<button class="btn btn-sm btn-default pull-right hidden" type="button" id="export-late-excel" ><i class="fa fa-file-excel-o"></i> Excel</button>
								<button class="btn btn-sm btn-default pull-right hidden" type="button" id="export-activeUsers-excel" ><i class="fa fa-file-excel-o"></i> Excel</button>
								<button class="btn btn-sm btn-default pull-right hidden" type="button" id="export-absenties-excel" ><i class="fa fa-file-excel-o"></i> Excel</button>
								<button class="btn btn-sm btn-default pull-right hidden" type="button" id="export-presentEmployees-excel" ><i class="fa fa-file-excel-o"></i> Excel</button>
							</div>
									
							<div class="panel-body" 
								style="background-color: #FFF;border-radius: 0px 0px 4px 4px; padding: 0;color:rgb(121, 121, 121);">
							    <div style="display: block; height: 329px; display: block; overflow: auto; padding: 0px;"
								class="panel-body" id="contentNotify">
									<div id="late_Comers" class="table_late">
									</div>	
								</div>
							</div>
        				</div>
						<!--Late Comers table  end-->
					</div>
					</div>
			
				
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
	<script src="../js/jquery.table2excel.js"></script>
	<script src="../js/respond.min.js"></script>
	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<!-- script for this pages-->
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/morris.min.js" type="text/javascript"></script>
	<script src="../js/raphael-min.js" type="text/javascript"></script>
	<script>
	$(document).ready(function() {
		$('#export-late-excel').removeClass('hidden');
		loadData();
        setInterval(function(){loadData();}, 5000);
    });

    $(document).on('change', "#branch_loc", function (e){
       loadData();
    });
  
  //set table key values
    $(document).on('click',".present_Emp",function(e){
        e.preventDefault();
  		$('.table_key').val('present');
  		$('#export-presentEmployees-excel').removeClass('hidden');
  		$('#export-late-excel,#export-activeUsers-excel,#export-absenties-excel').addClass('hidden');
  		loadData();
    });

    $(document).on('click',".absent_Emp ",function(e){
        e.preventDefault();
        $('.table_key').val('absent');
        $('#export-absenties-excel').removeClass('hidden');
        $('#export-late-excel,#export-activeUsers-excel,#export-presentEmployees-excel').addClass('hidden');
  		loadData();
    });

    $(document).on('click',".active_Users",function(e){
        e.preventDefault();
        $('.table_key').val('active');
        $('#export-activeUsers-excel').removeClass('hidden');
        $('#export-late-excel,#export-absenties-excel,#export-presentEmployees-excel').addClass('hidden');
  		loadData();
    });

    $(document).on('click', ".late_Comers ", function (e){
        e.preventDefault();
        $('.table_key').val('lateComers');
        $('#export-late-excel').removeClass('hidden');
        $('#export-activeUsers-excel,#export-absenties-excel,#export-presentEmployees-excel').addClass('hidden');
   	 	loadData();
    });

    //for chart data
            var graph = Morris.Area({
                element: 'pay-chart',
                data: [0,0],
                xkey: 'emp_time',
                xLabelAngle:45,
                ykeys: ['emp_count'],
                labels: ['Employees'],
                lineColors:['#49BAE4'],
                pointStrokeColors:['#49b'],
                gridTextColor:'#49BAE4',
                eventLineColors:['#2e2e2e'],
                resize:true,
                hideHover: true,
                grid:true,
                parseTime:false,
                smooth:false,
             });

		//for getting whole dashboard data
            var loadData= (function() {
                var branch_loc = $("#branch_loc option:selected").val();  
                var table_key = $(".table_key").val();
                   $.ajax({
                           dataType: 'html',
                           type: "POST",
                           url: "php/today.handle.php",
                           //timeout: 3000,
                           data: {act: '<?php echo base64_encode($_SESSION['company_id']."!todayDashboardData");?>',branchIds:branch_loc,tablekey:table_key},
                           cache: false,
                           beforeSend:function(){
                               //table Loader
                               },
                             success: function (data) {
                            	 jsonData = JSON.parse(data);
                            	 //put the data in smaller widgets
								$('.presentEmp').html(jsonData[2].topwidgets[0].present);
                         		$('.absentEmp').html(jsonData[2].topwidgets[0].absent);
                         		$('.activeUsers').html(jsonData[2].topwidgets[0].active);
                         		$('.lateComers').html(jsonData[2].topwidgets[0].late);
                                 
                                 //put the data in chart widget
                         		 if(jsonData[2].chartData.length>0){
                                     $('#pay-chart').show();
                                     $('.no-data').addClass('hide');
                                     //initiate the chart 
                                     graph.setData(jsonData[2].chartData);
                                 }else{
                                     $('#pay-chart').hide();
                                     $('.no-data').removeClass('hide');
                                     $('.no-data').html('<div style="padding: 122px 122px 189px;color:#797979">No Data Found</div>');
                                 }

                           
                                 //check whats the tablekey is
                             if(table_key=='lateComers'||table_key=='') {
                            	 $('.tble_title').html('Late Comers');
                            	 if(jsonData[2].lateComers.length > 0){
                                     $('.table_late').empty();
                                            var html = '<table class="table table-hover table-bordered dash-today late-comers " id="late-comers" style="width:100%;overflow:hidden;"><thead class="lateComers"></thead><tbody><div id="loader5" style="width:95%;height:90%"></div>';
                                            html += '<thead><th>Employee</th><th>Branch</th><th>Shift</th><th>CheckIn</th><th>Late</th><th>Last CheckIn</th></thead>'                                                                   
                                        for (var i = 0, len = jsonData[2].lateComers.length; i < len; i++) {
                                             html += '<tr>';
                                              $.each(jsonData[2].lateComers[i], function (k, v) {
                                            	var ID = jsonData[2].lateComers[i].employee_id;
                                               	var empID = ID.split('(')[1];
                                               	var e_Id = empID.replace(")","");
                                                 if(k == 'employee_id')
                                                    html +='<td><a href="../hr/employees.php?employeeID='+e_Id+'"><b>'+v.split('(')[0]+'</b>('+v.split('(')[1]+'</a></td>';
                                                else if(k!='start_time' && k!="end_time" && k!="early_start" && k!="late_end" )
                                                    html +='<td>'+v+'</td>';
                                                });          
                                               html +='</tr>';
                                         }
                                         html += '</tbody></table>';
                                         
                                         $('.table_late').html(html);
                                //append table
                                 }else{
                                     $('.table_late').html('<div id="loader5" style="width:95%;height:90%"></div><div style="padding: 122px;color:#797979;text-align:center">No Data Found</div>');
                                     }    
                               }         
                                 
                             //for the present employees table   
                               if(table_key=='present'){
                                	 $('.tble_title').html('Present Employees');
                                       if(jsonData[2].present.length > 0){
                                            $('.table_late').empty();
                                                   var html = '<table class="table table-hover table-bordered dash-today present-employee " id="present-employee" style="width:100%;overflow:hidden;"><thead class="present"></thead><tbody>';
                                                   html += '<thead><th>Employee</th><th>Branch</th><th>Shift</th><th>CheckIn</th><th>Last CheckIn</th></thead>'                                                                   
                                                    for (var i = 0, len = jsonData[2].present.length; i < len; i++) {
                                                        html += '<tr>';
                                                           $.each(jsonData[2].present[i], function (k, v) {
                                                        	   var ID = jsonData[2].present[i].employee;
                                                               var empID = ID.split('(')[1];
                                                               var e_Id = empID.replace(")","");
                                                               
                                                             if(k == 'employee')
                                                                 html +='<td><a href="../hr/employees.php?employeeID='+e_Id+'"><b>'+v.split('(')[0]+'</b>('+v.split('(')[1]+'</a></td>';
                                                              else if(k!='start_time' && k!="end_time" && k!="early_start" && k!="late_end" )
                                                                   html +='<td>'+v+'</td>';
                                                            });          
                                                              html += '</tr>';
                                                        }
                                                        html += '</tbody></table>';
                                                        $('.table_late').html(html);
                                               //append table
                                         }else{
                                           $('.table_late').html('<div style="padding: 122px;color:#797979;text-align:center">No Data Found</div>');
                                         }
                               }
                               
                             //for the absent employees table         
                               if(table_key=='absent'){      
                            	   $('.tble_title').html('Absent Employees');
                                      if(jsonData[2].absent.length > 0){
                                           $('.table_late').empty();
                                                  var html = '<table class="table table-hover table-bordered dash-today absent-employee " id="absent-employee" style="width:100%;overflow:hidden;"><thead class="absent_head"></thead><tbody>';
                                                  html += '<thead><th>Employee</th><th>Branch</th><th>Shift</th><th>Absent count (This month)</th></thead>'                                                                   
                                              for (var i = 0, len = jsonData[2].absent.length; i < len; i++) {
                                                   html += '<tr>';
                                                    $.each(jsonData[2].absent[i], function (k, v) {
                                                    	   var ID = jsonData[2].absent[i].employee;
                                                           var empID = ID.split('(')[1];
                                                           var e_Id = empID.replace(")","");
                                                       if(k == 'employee')
                                                          html +='<td><a href="../hr/employees.php?employeeID='+e_Id+'"><b>'+v.split('(')[0]+'</b>('+v.split('(')[1]+'</a></td>';
                                                       else if(k == 'absent_count')
                                                          html +='<td style="text-align:center">'+v+'</td>'
                                                       else if(k!='start_time' && k!="end_time" && k!="early_start" && k!="late_end" )
                                                          html +='<td>'+v+'</td>';
                                                      
                                                      });          
                                                     html += '</tr>';
                                               }
                                               html += '</tbody></table>';
                                               $('.table_late').html(html);
                                      //append table
                                       }else{
                                           $('.table_late').html('<div style="padding: 122px;color:#797979;text-align:center">No Data Found</div>');
                                           }  
                               } 

                            //for the active users table         
                            if(table_key=='active'){ 
                            	$('.tble_title').html('Active Users');
                          	if(jsonData[2].active.length > 0){
                               $('.table_late').empty();
                                      var html = '<table class="table table-hover table-bordered dash-today active-users " id=" active-users" style="width:100%;overflow:hidden;"><thead class="active_head"></thead><tbody>';
                                      html += '<thead><th>Employee</th><th>Branch</th><th>Shift</th><th>CheckIn</th><th>Last CheckIn</th></thead>'                                                                   
                                  for (var i = 0, len = jsonData[2].active.length; i < len; i++) {
                                       html += '<tr>';
                                        $.each(jsonData[2].active[i], function (k, v) {
                                        	var ID = jsonData[2].active[i].employee;
                                           	var empID = ID.split('(')[1];
                                           	var e_Id = empID.replace(")","");
                                          if(k!=='employee_name')
                                           if(k == 'employee')
                                              html +='<td><a href="../hr/employees.php?employeeID='+e_Id+'"><b>'+v.split('(')[0]+'</b>('+v.split('(')[1]+'</a></td>';
                                           else if(k!='start_time' && k!="end_time" && k!="early_start" && k!="late_end" )
                                              html +='<td>'+v+'</td>';
                                          
                                          });          
                                         html += '</tr>';
                                   }
                                   html += '</tbody></table>';
                                   $('.table_late').html(html);
                          //append table
                           }else{
                               $('.table_late').html('<div style="padding: 122px;color:#797979;text-align:center">No Data Found</div>');
                               }

                            }     
                                
                           }
                   });   

            });
            $(document).on('click',"#export-late-excel" ,function(e) {
      		 e.preventDefault();   
      		$("#late-comers").table2excel({
				//exclude: ".noExl",
				//name: "Excel Document Name",
				filename: "Late Comers On <?php echo date('d M,Y',strtotime("now"))?>",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
			});
      	    });

            $(document).on('click',"#export-activeUsers-excel" ,function(e) {
         		 e.preventDefault();   
         		$("#active-users").table2excel({
   				//exclude: ".noExl",
   				//name: "Excel Document Name",
   				filename: "Active Users On <?php echo date('d M,Y',strtotime("now"))?>",
   				exclude_img: true,
   				exclude_links: true,
   				exclude_inputs: true
   			});
         	    });

            $(document).on('click',"#export-absenties-excel" ,function(e) {
        		 e.preventDefault();   
        		$("#absent-employee").table2excel({
  				//exclude: ".noExl",
  				//name: "Excel Document Name",
  				filename: "Absent Employees On <?php echo date('d M,Y',strtotime("now"))?>",
  				exclude_img: true,
  				exclude_links: true,
  				exclude_inputs: true
  			});
        	    });
          
            $(document).on('click',"#export-presentEmployees-excel" ,function(e) {
       		 e.preventDefault();   
       		$("#present-employee").table2excel({
 				//exclude: ".noExl",
 				//name: "Excel Document Name",
 				filename: "Present Employees On <?php echo date('d M,Y',strtotime("now"))?>",
 				exclude_img: true,
 				exclude_links: true,
 				exclude_inputs: true
 			});
       	    });
       
	</script>
</body>
</html>
