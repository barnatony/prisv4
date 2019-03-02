<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Mosaddek">
<meta name="keyword"
	content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
<link rel="shortcut icon" href="../img/favicon.png">

<title>BassPRIS - A Payroll System</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/morris.css" rel="stylesheet" />
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="../stylesheet" href="css/owl.carousel.css" type="text/css">
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
<style>
#treeParent {
    position: fixed;
    top: 201px;
    bottom: 10px;
    border: 1px solid #ccc;
    overflow: auto;
    width: 50%;
}
</style>
</head>

<body>



	<section id="container">
		<!--header start-->
      <?php  include_once (__DIR__."/header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside><?php
		if (isset ( $_REQUEST ['consultant'] )) {
			echo "iam here";
		}
		$allowColumns = array ();
		$stmt = "SELECT (SELECT COUNT(*)  FROM payroll) AS payrollRuned ,themes_id,logo, pay_structure_id FROM payslip_design join company_pay_structure WHERE type = 'A' AND display_flag = 1 ORDER BY sort_order";
		$result = mysqli_query ( $conn, $stmt ) or die ( mysqli_error ( $conn ) );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$allowColumns [] = $row ['pay_structure_id'];
			$_SESSION ["design_id"] = $row ['themes_id'];
			$logo_s = explode ( ",", $row ['logo'] );
			$_SESSION ["logo_id_flag"] = $logo_s [2];
			$payroll_flag = $row ['payrollRuned'];
		}
		$_SESSION ['allowances_values'] = implode ( ',', $allowColumns );
		$_SESSION ['payroll_flag'] = ($payroll_flag == 0) ? 0 : 1;
		
		include_once (__DIR__ . "/sideMenu.php");
		?>
         </aside>
       <?php
							require_once (LIBRARY_PATH . "/dashboard.class.php");
							$dashboard = new Dashboard ();
							$dashboard->conn = $conn;
							$employee_widget = $dashboard->getEmployeeWidgetData ( $_SESSION ['current_payroll_month'] );
							$payout_widget = $dashboard->getPayoutWidgetData ();
							$event_widget = $dashboard->getUpcomingEventData (null,'hr');
							$employee_ItDeclarations = $dashboard->getPendingItDeclarationData ( $_SESSION ['financialYear'], 20 );
							$employee_lastLogins=null;
							if($employee_ItDeclarations==null){
								$employee_lastLogins = $dashboard->getEmployeeLastloginData ( 20 );
							}
							$employee_bdays = $dashboard->getEmployeeBirthdayData ( 7 );
							$employee_anniversaries = $dashboard->getEmployeeAniversaryData ( 7 );
							$getQuickLink = $dashboard->getQuickLinkForSalaryStmt ( $_SESSION ['current_payroll_month'] );
							$employeelastExits = $dashboard->getLastexits ($_SESSION ['current_payroll_month'] );
							
							$Biostmt = mysqli_query( $conn,"SELECT IFNULL(COUNT(employee_id),0) emp_count FROM employee_biometric;" );
							$result = mysqli_fetch_array ( $Biostmt, MYSQLI_ASSOC );
							$emp_count= $result['emp_count'];
							?>
           <section id="main-content">
           
			<section class="wrapper">
				<!--state overview start-->
				<?php
                      if($emp_count>0){
                      	echo '<p style="font-size:18px;">Your Current Payroll month is '.$_SESSION ['fywithMonth'].'</p><div class="col-lg-offset-11 pull-right" style="margin-top: -38px;">';
 						echo '<a href="today.php"><button class="btn btn-primary btn-xs " style="margin-bottom:10px;" type="button" Title="To go Today">Attendance</button></a></div>';
                      }
        		 ?>
				<div class="row state-overview">
 				
					<div class="col-lg-3 col-sm-6">
						<section class="panel">
							<div class="symbol blue">
								<i class="fa fa-user"></i>
							</div>
							<div class="value" style="padding-top: 5px;">

								<div id="employee-widget" class="carousel slide auto panel-body">
									<ol class="carousel-indicators out " style="bottom: -18px;">
										<li class="" data-slide-to="0" data-target="#employee-widget"></li>
										<li class="" data-slide-to="1" data-target="#employee-widget"></li>
										<li class="active" data-slide-to="2"
											data-target="#employee-widget"></li>
									</ol>


									<div class="carousel-inner">
										<div class="item text-center">
											<h2 class="count"><?php echo $employee_widget[0]['NewJoinees']; $pastdate=strtotime($_SESSION['current_payroll_month'].' -1 months');?></h2>
											<p>New Joinees (<?php echo date_format(date_create(date('Y-m-d', $pastdate)),'M'); ?>)</p>
										</div>
										<!--New join Employees-->
										<div class="item text-center">
											<h2><?php echo $employee_widget[0]['NewExits'];?></h2>
											<p style="color: #FF6C60">Resigned (<?php echo date_format(date_create(date('Y-m-d', $pastdate)),'M'); ?>)</p>
										</div>
										<!--No of Employees-->
										<div class="item text-center active">
											<h2><?php echo $employee_widget[0]['totalEmployees'];?></h2>
											<p>Employees</p>
										</div>
									</div>
			         			</div>
							</div>
						</section>
					</div>
					<div class="col-lg-3 col-sm-6">
						<section class="panel">
							<div class="symbol green">
								<i class="fa fa-inr"></i>

							</div>
							<div class="value" style="padding-top: 5px;">
								<div id="payout-widget" class="carousel slide auto panel-body">
									<ol class="carousel-indicators out" style="bottom: -18px;">
										<li class="" data-slide-to="0" data-target="#payout-widget"></li>
										<li class="active" data-slide-to="1"
											data-target="#payout-widget"></li>
									</ol>
									<div class="carousel-inner">

										<div class="item text-center">
											<h2><?php echo $payout_widget[0]['gross'];?></h2>

											<p>Gross Pay (<?php echo date('M',strtotime("".$_SESSION['current_payroll_month']." -1 months")); ?>)</p>
										</div>
										<!-- payout values-->
										<div class="item text-center active">
											<h2><?php echo $payout_widget[0]['net'];?></h2>
											<p>Net Pay (<?php echo date('M',strtotime("".$_SESSION['current_payroll_month']." -1 months")); ?>)</p>
										</div>
									</div>


								</div>
							</div>
						</section>
					</div>
					<div class="clearfix hidden-lg"></div>
					<div class="col-lg-6 col-sm-12">
						<section class="panel holiday">
							<div class="symbol content">
								<div class="list-group">
                          <?php  
                        if(count($event_widget)>0){
						foreach ( $event_widget as $row ) {
							 $workingButon=($row ['dateText']=='Today' && $row ['category']=='HOLIDAY' && $row ['empId']!='')?'<input class="btn btn-success  workingdate btn-xs" data-id="'.$row ['start'].'" value="Who \'s Working" type="button">':'';
							 echo '<a class="list-group-item" href="javascript:;"><h3>' . $row ['title'] . ' <span style="font-size:18px;color:#ccc">- ' . $row ['dateText'] . '</span>  '.$workingButon.'</h3></a>';
						}}else{
							echo '<div class="text-center" style="padding: 40px;">No Data Found</div>';
		
						}?>
                         </div>
							</div>
							<div class="arrow">
								<div class="in">
									<div class="col-lg-12">
										<a id="holiday-go-top" href="javascript:;"
											class="fa fa-chevron-up"></a>
									</div>
									<div class="col-lg-12">
										<a id="holiday-go-bottom" href="javascript:;"
											class="fa fa-chevron-down"></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</section>
					</div>


				</div>
				<!--state overview end-->

				<div class="row">
					<div class="col-lg-6">
						<!--new earning start-->
						<div class="panel terques-chart" style="background: 0px;">
							<div class="chart-tittle" style="border-radius: 4px 4px 0px 0px;">
								<span class="title">Pay (last 10 months)</span> <span
									class="value"> <a href="#" class="active chartType"
									data-id="net">Net</a> | <a href="#" class="chartType"
									data-id="gross">Gross</a> | <a href="#" class="chartType"
									data-id="deductions">Deduction</a>

								</span>
							</div>
							<div class="panel-body chart-texture"
								style="border-radius: 0px 0px 4px 4px; padding: 0;">

								<div class="chart">

									<div id="morris">

										<div id="pay-chart" class="graph" style="height: 325px"></div>
									</div>
								</div>
							</div>

						</div>
						<!--new earning end-->
					</div>
					<div class="col-lg-6">
						<!--new earning start-->
						<div class="panel terques-chart" style="background: 0px;">
							<div class="chart-tittle" style="border-radius: 4px 4px 0px 0px;background-color: #41cac0;">
								<span class="title">Notifications</span> <span class="value"> 
									<?php echo $text=isset($employee_lastLogins)?
	'<a href="#"  data-id="employeeLogin" class="commonNofityId active notifyClick">Employee Logins</a>':
    '<a href="#"  data-id="itNotify"  class=" commonNofityId active notifyClick">IT Declarations</a>';?> 
    <?php if($features['leaveManagement']['displayUrl']=='leave.php'){echo' |  <a href="#" class="notifyClick" id="lrNotifyId" data-id="lrNotify" >Leave Requests</a>';}?>
                                </span>
							</div>
							<div class="panel-body" 
								style="background-color: #FFF;border-radius: 0px 0px 4px 4px; padding: 0;color:rgb(121, 121, 121);">
							    <div style="display: block; height: 329px; display: block; overflow: auto; padding: 0px;"
								class="panel-body" id="contentNotify">	
								</div>
							</div>
        	</div>
						<!--new earning end-->
					</div>
					
				</div>
<!--  Last Month and Quick Links -->
			<div class="row">
					<div class="col-lg-6">
						<!--new earning start-->
						<div class="panel terques-chart" style="background: 0px;">
							<div class="chart-tittle" style="border-radius: 4px 4px 0px 0px;">
								<span class="title">Quick Links</span> 
							</div>
							<div class="panel-body "
								style="background-color: #FFF;border-radius: 0px 0px 4px 4px; padding: 0;color:rgb(121, 121, 121);">
<div style="height: 100px; display: block; overflow: auto; padding: 0px;" class="panel-body">
<?php 
	if(count($getQuickLink)>0){
		echo '<table class="table table-hover personal-task"><tbody>';
		foreach ($getQuickLink as $row){
		echo '<tr><td style="padding:6px 17px;">Salary Statment For Month of <b>'.$row['monthName'].'</b></td>
			<td style="padding:6px 17px;"><button class="btn btn-default btn-xs salryStmtQuicklink" data-id="'.$row['month_year'].'*'.$row['monthName'].'" type="button">
		   View</button></td></tr>';
		}
		echo '</tbody></table>';
	}else{
		echo '<div style="padding:37px;text-align: center;color:#797979">No Data Found</div>';
	}
?>
							</div>
							</div>

						</div>
						<!--new earning end-->
					</div>
					<div class="col-lg-6">
						<!--new earning start-->
						<div class="panel terques-chart" style="background: 0px;">
							<div class="chart-tittle" style="border-radius: 4px 4px 0px 0px;background-color: #41cac0;">
								<span class="title">Emloyees Leaving This Month</span>
							</div>
					<div class="panel-body" style="background-color: #FFF;border-radius: 0px 0px 4px 4px; padding: 0;color:rgb(121, 121, 121);">
						 <div style="height: 100px;display: block;overflow: auto; padding: 0px;" class="panel-body">
						 <?php 
						 	if(count($employeelastExits)>0){
						 		echo '<table class="table table-hover personal-task"><tbody>';
						 	foreach ($employeelastExits as $row){
								echo '<tr><td style="padding:6px 17px;"><b>'.$row['employee_name'].'</b> ( '.$row['employee_id'].' ) is on '.$row['last_working_date'].'</td><td style="padding:6px 17px;"><button class="btn btn-default btn-xs exitNoticePeriod" data-date="'.date_format(date_create($row['last_working_date']),'d/m/Y').'" data-id="'.$row['employee_id'].'" type="button">Run FAF</button></td></tr>';
							}
							echo '</tbody></table>';
							}else{
							echo '<div style="padding:37px;text-align: center;color:#797979">No one Leaving</div>';
							}
						 ?>
						 </div>
					</div>
        	</div>
						<!--new earning end-->
					</div>
					
				</div>
<!-- Quick Links and Last Month -->
			<div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading tab-bg-dark-navy-blue ">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a aria-expanded="true" data-toggle="tab"
                                        href="#aniversary"><header class="panel-heading"
                                                style="font-weight: normal; border: none; padding: 0px;">Anniversaries</header></a>

                                    </li>
                                    <li class=""><a aria-expanded="false" data-toggle="tab"
                                        href="#birthday"><header class="panel-heading"
                                                style="font-weight: normal; border: none; padding: 0px;">Birthdays</header>
                                    </a></li>
                                </ul>
                            </header>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="aniversary" class="tab-pane active">
                                        <div class="panel-body">
                              <?php
                                                                                                                        foreach ( $employee_anniversaries as $row ) {
                                                                                                                            if (file_exists ( $row ['emp_img'] )) {
                                                                                                                                $imageFile = $row ['emp_img'];
                                                                                                                            } else {
                                                                                                                                $imageFile = ($row ['gender'] == "Male") ? "../img/default-avatar_men.png" : "../img/default-avatar_women.png";
                                                                                                                            }
                                                                                                                            echo ' <div class="col-lg-4">
                              <div class="birthday row terques">
                                  <!-- Comment -->
                                  <div class="birthday-ava">
                                         <img class="avatar" src="' . $imageFile . '" alt="">
                                     </div>
                                       <div class="birthday-content col-lg-9">
                                                   <p class="birthday-title"><a href="#">' . $row ['emp_name'] . '</a></p>
                                              <p class="birthday-desc "><span class="pull-left">' . $row ['jDay'] . ' </span><span class="hidden-xs pull-left"> - ' . $row ['age'] . ' Yr(s)</span></p>
                                      </div>
                               </div>
                             </div>';
                                                                                                                        }
                                                                                                                       
                                                                                                                        if ($employee_anniversaries == NUll) {
                                                                                                                            echo '<div class="col-lg-12 well text-center" style="margin:0px"> No Anniversaries found this week.</div>';
                                                                                                                        }
                                                                                                                       
                                                                                                                        ?>
                        </div>
                                    </div>
                                    <div id="birthday" class="tab-pane">
                                        <div class="panel-body">
                                 
                              <?php
                                                                                                                        foreach ( $employee_bdays as $row ) {
                                                                                                                            if (file_exists ( $row ['emp_img'] )) {
                                                                                                                                $imageFile = $row ['emp_img'];
                                                                                                                            } else {
                                                                                                                                $imageFile = ($row ['gender'] == "Male") ? "../img/default-avatar_men.png" : "../img/default-avatar_women.png";
                                                                                                                            }
                                                                                                                            echo ' <div class="col-lg-4">
                              <div class="birthday terques row">
                                  <!-- Comment -->
                                  <div class="birthday-ava">
                                         <img class="avatar" src="' . $imageFile . '" alt="">
                                     </div>
                                       <div class="birthday-content col-lg-9">
                                                   <p class="birthday-title"><a href="#">' . $row ['emp_name']. '</a></p>
                                              <p class="birthday-desc "><span class="pull-left">' . $row ['bDay'] . ' </span><span class="hidden-xs pull-left"> -' . $row ['age'] . ' Yrs</span></p>
                                      </div>
                               </div>
                             </div>';
                                                                                                                        }
                                                                                                                        if ($employee_bdays == NUll) {
                                                                                                                            echo '<div class="col-lg-12 well text-center" style="margin:0px"> No Birthdays found this week.</div>';
                                                                                                                        }
                                                                                                                       
                                                                                                                        ?>

                             
                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>

                    </div>

                </div>


			</section>
		</section>

		<!--main content end-->
		<!--footer start-->
  <?php include_once (__DIR__."/footer.php");?>
  
		<!--footer end-->
	</section>
      <!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/owl.carousel.js"></script>
	<script src="../js/jquery.customSelect.min.js"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/morris.min.js" type="text/javascript"></script>
	<script src="../js/raphael-min.js" type="text/javascript"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>




	<script>

      //owl carousel

      $(document).ready(function() {
    	//sidemenu 
    		$('#sidebar').getNiceScroll().hide();
    		
    		//side Menu HIde
    		 $('#container').addClass('sidebar-closed');
    		$('#main-content').css('margin-left','0px');
    		$('#sidebar').css('margin-left','-221px');
    		
    	  var itDeclarations = <?php echo json_encode($employee_ItDeclarations) ?>;
		    if(itDeclarations.length>0){
		    	 tableCreation(itDeclarations,'itNotify');
		    	 $('#commonNofity').data('id','itNotify');
			}else{
				 var employeelastLogins = <?php echo json_encode($employee_lastLogins) ?>;
					tableCreation(employeelastLogins,'employeeLogin');
					$('#commonNofity').data('id','employeeLogin');
			}
   	     $(".carousel").carousel({
        	  interval: 5000
         });
     });

      
      var graph = Morris.Line({
          element: 'pay-chart',
          data: [0,0],
          xkey: 'month_year',
          xLabelAngle:45,
          ykeys: ['yVal'],
          labels: ['Value'],
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
		
      updateChart('net',function(data){
    	  if(data.length>0){
          	graph.setData(data);
           }else{
               $('#pay-chart').html('<div style="padding: 122px;color:#797979">No Data Found</div>');
           }
		});
		
	$('.chartType').on('click',function(e){
			e.preventDefault();
			$(this).siblings('a').removeClass('active');
			$(this).addClass('active');
			updateChart($(this).data('id'),function(data){
				if(data.length>0){
					graph.setData(data);
	             }else{
	                 $('#pay-chart').html('<div style="padding: 122px;color:#797979">No Data Found</div>');
	             }
			});
		});
	function updateChart(chartType,handleData) {
    	  $.ajax({
    	      type: "POST",
    	      url: "php/dashboard.handle.php", 
    	      data: { "act": "getChartData", "type":chartType},
    	      beforeSend:function(){
        	      	$('.chart-texture').addClass('loading');
        	      },
        	  complete:function(){
        		  //$('.chart').loading(false);
            	  },
    	      success:function(data){
        	      data = JSON.parse(data);
        	      graph.options.yLabelFormat = function(y){
  				    return reFormateByNumber(y);
  				};
  				
        	      handleData(data[2]);
        	      $('.chart-texture').removeClass('loading');
        	      
        	      } 
    	    });
      }

	    
//start of dashboad holiday down/up
	 $('#holiday-go-top').on('click',function(e){
	e.preventDefault();
	$('#holiday-go-top').addClass('disabled');  
	if(($('.holiday > .content > .list-group').position().top +100) <= 0 ){
		$('.holiday > .content > .list-group').animate({ "top": "+=100px", "bottom": "-=100px"}, "slow" );
	}	
	setTimeout(function(){$('#holiday-go-top').removeClass('disabled');   }, 1000);
});

$('#holiday-go-bottom').on('click',function(e){
e.preventDefault();
$('#holiday-go-bottom').addClass('disabled');  

height=$('.holiday > .content > .list-group').height();
if(height!=99){
tophight=$('.holiday > .content > .list-group').position().top;
var data=height+tophight;
if(data>100){
	$('.holiday > .content > .list-group').animate({ "top": "-=100px", "bottom": "+=100px" }, "slow" );
  }else if(data>50 && data!=100){
	$('.holiday > .content > .list-group').animate({ "top": "-=100px", "bottom": "+=100px" }, "slow" );
  }else{
	  height=(data==50)?height-50:height-100;
	  $('.holiday > .content > .list-group').animate({ "top": "+="+height+"px", "bottom": "+=0px" }, "slow" );
     }
}
setTimeout(function(){$('#holiday-go-bottom').removeClass('disabled');   }, 1000);
});

    
    	$(document).on('click', ".itDeclaration", function (e) {
    	e.preventDefault();
    	window.location = "itDeclaration.php?employeeID="+$(this).data('id');	
       });

    	$(document).on('click', ".workingdate", function (e) {
        	e.preventDefault();
        	window.location = "compensation.php?day="+$(this).data('id');	
        });

    	$(document).on('click', ".leaveRequest", function (e) {
        	e.preventDefault();
        	window.location = "leave.php?lrID="+$(this).data('id')+'#leaveRequest';
       });
        
    	$(document).on('click', ".exitNoticePeriod", function (e) {
        	e.preventDefault();
        	window.location = "noticePeriod.php?fnfId="+$(this).data('id')+'&date='+$(this).data('date')+'#thismonth';	
        });

    	$(document).on('click', ".salryStmtQuicklink", function (e) {
        	e.preventDefault();
        date=$(this).data('id');
        window.open("report.php?act="+'<?php echo base64_encode($_SESSION['company_id']."!reportHTML"); ?>'+"&salaryStmt=SS001&stmtFor=M&typeOfyear=FY&periodFrom="+date+"&periodTo="+date+"&consolidate=0");	
       });
        
    	$(document).on('click', ".notifyClick", function (e) {
        	e.preventDefault();
        	var element=$(this).data('id');
        	$('.notifyClick').removeClass('active');
        	var actVal= (element=='itNotify'?$('.commonNofityId').addClass('active'):
            	((element=='employeeLogin')?$('.commonNofityId').addClass('active'):(element=='lrNotify')?
                    	$('#lrNotifyId').addClass('active'):''));
        	
        	var actVal= (element=='itNotify'?'getEmployeePendingItDeclarations':
        	((element=='employeeLogin')?'getEmployeeLastlogins':(element=='lrNotify')?'getEmployeelrRq':''));
        $.ajax({
  	      type: "POST",
  	      url: "php/dashboard.handle.php", 
  	      data: {act: actVal},
		     beforeSend:function(){
      	      	$('#contentNotify').addClass('loading');
      	      },
      	  complete:function(){
      		  //$('.chart').loading(false);
          	  },
  	      success:function(data){
      	      jsondata = JSON.parse(data);
      	      tableCreation(jsondata[2],element);
      	      } 
  	    });
        });
        //custom select box

      $(function(){ 
         $('select.styled').customSelect();
      });

      function tableCreation(dataArray,id){
    	  $('#contentNotify').addClass('loading');
    	  if(dataArray.length>0){
         var html='<table class="table table-hover personal-task"><tbody>';
			for(var i=0;i<dataArray.length;i++){
				if(id=='employeeLogin'){
					 var trData='<tr><td style="padding:8px 17px;"><b>'+dataArray[i].employee_name+'</b> - '+dataArray[i].employee_id+'</td><td style="padding:8px 17px;"> <i class="fa fa-clock-o" style="font-size: 15px;"></i> '+humanTiming(dataArray[i].last_login)+' </td>';
			    }else if(id=='itNotify'){
					 var trData='<tr><td style="padding:8px 17px;"><b>'+dataArray[i].employee_name+'</b> ('+dataArray[i].employee_id+' ) Modified IT Declaration on '+humanTiming(dataArray[i].updated_on)+' </td><td style="padding:8px 17px;"><button class="btn btn-primary btn-xs itDeclaration"  data-id=' +dataArray[i].employee_id+'  type="button">Approve</button> </td></tr>';
			   }else if(id=='lrNotify'){
				  var trData='<tr><td style="padding:8px 17px;"><b>'+dataArray[i].employee_name+'</b> - '+dataArray[i].employee_id+'  has requested for  <b>'+dataArray[i].lr_type+'</b> on <b>'+dataArray[i].fromDate+' </b> ( '+humanTiming(dataArray[i].updated_on)+' ) </td><td style="padding:8px 17px;"><button class="btn btn-primary btn-xs leaveRequest" data-id=' +dataArray[i].request_id+'  type="button">Approve</button> </td></tr>';
			   }
		     html +=trData
			}
			html+='</tbody></table>';
			$("#contentNotify").html(html);
    	   }else{
    		   $("#contentNotify").html('<div style="padding: 122px;text-align: center;color:#797979">No Data Found</div>');
           }
			 $('#contentNotify').removeClass('loading');
      	    }

      $(window).scroll(function (event) {
    	    var scroll = $(window).scrollTop();
    	     // Do something
    	});
  	
  </script>

</body>
</html>


