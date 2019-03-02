<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="BassTechs">
<link rel="shortcut icon" href="../img/favicon.png">

<title>BassPRIS - A Payroll System</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/morris.css" rel="stylesheet" />
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
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

#finYearchosen_chosen {
	color: black;
}

.table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td,
	.table tbody>tr>td, .table tfoot>tr>td {
	padding: 5px;
}
</style>
</head>

<body>



	<section id="container">
		<!--header start-->
     <?php include ("header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside><?php
		
		$allowColumns = array ();
		$stmt = "SELECT (SELECT COUNT(*)  FROM payroll) AS payrollRuned ,themes_id,logo, pay_structure_id FROM payslip_design join company_pay_structure WHERE type = 'A' AND display_flag = 1 ORDER BY sort_order";
		require_once (LIBRARY_PATH . "/database.class.php");
		$database = new Database ();
		$result = mysqli_query ( $database->getConnection (), $stmt ) or die ( mysqli_error ( $database->getConnection () ) );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$allowColumns [] = $row ['pay_structure_id'];
			$_SESSION ["design_id"] = $row ['themes_id'];
			$logo_s = explode ( ",", $row ['logo'] );
			$_SESSION ["logo_id_flag"] = $logo_s [2];
			$payroll_flag = $row ['payrollRuned'];
		}
		$_SESSION ['allowances_values'] = implode ( ',', $allowColumns );
		$_SESSION ['payroll_flag'] = ($payroll_flag == 0) ? 0 : 1;
		
		include_once ("sideMenu.php");
		?>
         </aside>
       <?php
							require_once (LIBRARY_PATH . "/dashboard.class.php");
							$dashboard = new Dashboard ();
							$dashboard->conn = $conn= $database->getConnection ();
							
							$payout_widget = $dashboard->getEmployeePayoutWidgetData ( $_SESSION ['employee_id'], $_SESSION ['current_payroll_month'] );
							$employee_widget = 0;
							$event_widget = $dashboard->getUpcomingEventData ( $_SESSION ['employee_id'],'emp');
							
							// $employee_lastLogins = $dashboard->getEmployeeLastloginData(20);
							$employee_ItDeclarations = $dashboard->getPendingItDeclarationData ( $_SESSION ['financialYear'], 20 );
							if ($employee_ItDeclarations == [ ]) {
								$employee_lastLogins = $dashboard->getEmployeeLastloginData ( 20 );
							}
							$employee_bdays = $dashboard->getEmployeeBirthdayData ( 7 );
							$employee_anniversaries = $dashboard->getEmployeeAniversaryData ( 7 );
							function humanTiming($time) {
								$time = time () - $time; // to get the time since that moment
								$time = ($time < 1) ? 1 : $time;
								$tokens = array (
										31536000 => 'year',
										2592000 => 'month',
										604800 => 'week',
										86400 => 'day',
										3600 => 'hour',
										60 => 'minute',
										1 => 'second' 
								);
								
								foreach ( $tokens as $unit => $text ) {
									if ($time < $unit)
										continue;
									$numberOfUnits = floor ( $time / $unit );
									return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
								}
							}
							
							// Attendance New Dashboard
							 $Biostmt = mysqli_query( $conn,"SELECT IFNULL(COUNT(b.employee_id),0) emp_count FROM employee_biometric b INNER JOIN device_users u ON b.employee_id = u.ref_id WHERE u.employee_id ='{$_SESSION ['employee_id']}';" );
							 $result = mysqli_fetch_array ( $Biostmt, MYSQLI_ASSOC );
							 $emp_count= $result['emp_count'];
							 
							 //for getting if the emp is team lead or not
							 $query=mysqli_query($conn, "SELECT COUNT(employee_id) team_mem FROM employee_work_details WHERE employee_reporting_person='{$_SESSION['employee_id']}';" );
							 $result = mysqli_fetch_array ( $query, MYSQLI_ASSOC );
							 $team_lead= $result['team_mem'];
							?>
     


        <section id="main-content">
			<section class="wrapper">
				<!--state overview start-->
				<?php
				$html='<span style="margin-top: -38px;" class="pull-right">';
				echo '<p style="font-size:18px;">'.$_SESSION ['employee_name'].', <span id="greetingsMessage"></span>, Welcome to '.$_SESSION["company_name"].'</p>';
				 //Attendance New Dashboard
				 
					//echo '<div class="col-lg-offset-11 pull-right" style="margin-top: -38px;"><a href="../b/redirect/page/?url=dashboard"><button class="btn btn-primary btn-xs " style="margin-bottom:10px;" type="button" Title="View my attendance & pay Dashboard">Dashboard v2</button></a></div>';
					if($team_lead>0)
				 		$html.='<a href="../b/redirect/page/?url=myteam/dashboard" style="margin-right: 10px;"><button class="btn btn-primary btn-xs " style="margin-bottom:10px;" type="button" title="View Team Dashboard">Team Dashboard</button></a>';
					if($emp_count>0)
						$html.='<a href="../b/redirect/page/?url=dashboard"><button class="btn btn-primary btn-xs " style="margin-bottom:10px;" type="button" title="View my attendance &amp; pay Dashboard">Dashboard v2</button></a>';
				 	
				 	$html.='</span>';
				 	
				 	echo $html;				 
				 
				/* if($emp_count>0){
					echo '<div class="col-lg-offset-11 pull-right" style="margin-top: -38px;"><a href="../b/redirect/page/?url=attendance"><button class="btn btn-primary btn-xs " style="margin-bottom:10px;" type="button" Title="View my attendance">My Attendance</button></a></div>';
				}
				*/
				?>
				<div class="row state-overview">

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

					<!-- Quick Links Start -->
					<div class="col-lg-3 col-sm-6">
						<section class="panel quickLinks">
							<!--  <ul class="list-group">
                              <li class="list-group-item">Pay Slip - Mar 2016 <button style="margin-right: 10px;" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-eye"></i> View</button></li>
                              <li class="list-group-item">Anual Salary Statement <button style="margin-right: 10px;" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-eye"></i> View</button></li>
                      </ul>  -->
							
                      <?php
																						$payslipLastThreeMonth = $dashboard->getEmployeeQuickLinks ( 'pay', $_SESSION ['current_payroll_month'], 2, $_SESSION ['employee_id'] );
																						if ($payslipLastThreeMonth != null) {
																							echo '<table class="table table-bordered" style="height: 100%"><tbody>';
																							foreach ( $payslipLastThreeMonth as $row ) {
																								echo ' <tr><td>' . $row ['particular'] . '</td> <td>' . $row ['netSal'] . '</td><td><a href=' . $row ['url'] . '><button style="margin-right: 10px;" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-download"></i> View</button></a></td></tr>';
																							}
																							echo '</tbody></table>';
																						} else {
																							echo '<div class="text-center" style="padding: 40px;">No Data Found</div>';
																						}
																						?>
                                                   
                              
						</section>
					</div>
					<!-- Quick Links End -->
					<div class="clearfix hidden-lg"></div>
					<div class="col-lg-6 col-sm-12">
						<section class="panel holiday">
							<div class="symbol content">
								<div class="list-group">
                          <?php 
                        if(count($event_widget)>0){
						foreach ( $event_widget as $row ) {
							$empid=explode('-',$row ['empId']);
							 $workingButon=($row ['dateText']=='Today' && $row ['category']=='HOLIDAY' && $_SESSION['employee_id']==$empid[0] )?'<input class="btn btn-success btn-xs"  value="You \'re Working" type="button">':
							 (($row ['dateText']=='Today' && $row ['category']=='HOLIDAY')?'<input class="btn btn-warning btn-xs compensationClick"  id="'.$row ['hId'].'" data-date="'.$row ['start'].'"  data-title="'.$row ['title'].'"  value="I\'m Working" type="button"><img  id="loader_'.$row ['hId'].'"  class="displayHide" style="margin-left: -9%;" src="../img/ajax-loader.gif">':'');
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
					<div class="clearfix hidden-lg"></div>



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
							<div class="panel-body chart-texture chartPay"
								style="border-radius: 0px 0px 4px 4px; padding: 0;  height: 325px">

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
						<!--user info table start-->

						<div class="panel terques-chart" style="background: 0px;">
							<div class="chart-tittle" style="border-radius: 4px 4px 0px 0px;">
								<span class="title">Income Tax</span> <select class="title"
									id="finYearchosen">
                                              <?php
																		$stmt = mysqli_prepare ( $conn, "SELECT year FROM employee_income_tax WHERE employee_id='" . $_SESSION ['employee_id'] . "'  ORDER BY year DESC" );
																		$result = mysqli_stmt_execute ( $stmt );
																		mysqli_stmt_bind_result ( $stmt, $year );
																		while ( mysqli_stmt_fetch ( $stmt ) ) {
																			echo "<option value='" . $year . "'>" . $year . "</option>";
																		}
																											?>
                                   </select>
							</div>

							<div class="panel-body chart-texture tax"
								style="border-radius: 0px 0px 4px 4px; padding: 0;">

								<div class="chart">

									<div id="morris" class="col-lg-12 " style="height:325px">
									<div class="taxText displayHide"   style="height:325px"></div>
										<div id="taxOne" class="graph graphTax col-lg-6 displayHide"  style="height:325px"></div>
										<div id="taxTwo" class="graph graphTax col-lg-6 displayHide"  style="height:325px"></div>
									</div>
								</div>
							</div>

						</div>

						<!--user info table end-->
					</div>
				</div>

				
				
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
                                              <p class="birthday-desc "><span class="pull-left">' . $row ['bDay'] . ' </span><span class="pull-left hide"> -' . $row ['age'] . ' Yrs</span></p>
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
 <?php include("footer.php"); ?>
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
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/raphael-min.js" type="text/javascript"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>




	<script>

      //owl carousel

      $(document).ready(function() {
    	 $('#finYearchosen').chosen();
    	 $('#finYearchosen_chosen').addClass('pull-right');
    	 $(".carousel").carousel({
        	  interval: 5000
         });
         
     	$('#container').addClass('sidebar-closed');
    	  $('#main-content').css('margin-left','0px');
    	  $('#sidebar').css('margin-left','-221px');

    	  function getGreeting(){
	    	  var now = new Date();
	    	  var hrs = now.getHours();
	    	  var msg = "";
	
	    	  if (hrs >  0) msg = "Mornin' Sunshine!"; // REALLY early
	    	  if (hrs >  6) msg = "Good morning";      // After 6am
	    	  if (hrs > 12) msg = "Good afternoon";    // After 12pm
	    	  if (hrs > 17) msg = "Good evening";      // After 5pm
	    	  if (hrs > 22) msg = "Good Night!";        // After 10pm
			return msg;
    	  }

    	  $("#greetingsMessage").text(getGreeting);

    	  
      });

   
  	
      var barChart= Morris.Bar({
		  element: 'taxOne',
		  barColors:['#78909C','#4CAF50'],
		  xkey: 'ded',
		  ykeys: ['granted', 'availed'],
		  labels: ['Granted', 'Availed'],
		  hideHover: true,
	  		grid:true,
	  		xLabelAngle:45,
	  		ymax:'auto[150000]'
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

      var currentFinyear='<?php echo $_SESSION['financialYear'];?>';
      $("#finYearchosen option[value='" + currentFinyear + "']").prop("selected", true);
      $("#finYearchosen").trigger("chosen:updated"); //for drop down
        updateChart('tax','','getTaxChartData',currentFinyear,function(data){
        	$('.graphTax').hide();
            if(data.length>0){
            var finalObj = $.concat(data[0],data[1],data[2]);
            var taxPayable = $.concat(data[3]);
            donut_chart_data=[];
            donut_chart_data[0]={label:"Tax Payable",
                    value:taxPayable[0].granted};
            donut_chart_data[1]={label:"Tax Paid",value:taxPayable[0].availed};
        $('.taxText').html('').hide();
          $('.graphTax').show();
            barChart.setData(finalObj);
            Morris.Donut({
          	  element: 'taxTwo',
          	  colors:['#78909C','#4CAF50'],
          	  data:donut_chart_data,
              resize:true
          	});
           }else{
            	$('.taxText').html('<div style="padding: 122px;color:#797979">No Data Found</div>').show();
                }

        });
		
        updateChart('chartPay','net','getChartData','',function(data){
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
			updateChart('chartPay',$(this).data('id'),'getChartData','',function(data){
				if(data.length>0){
					graph.setData(data);
	             }else{
	                 $('#pay-chart').html('<div style="padding: 122px;color:#797979">No Data Found</div>');
	             }
				});
		});


	$('#finYearchosen').on('change', function (event) {
        var selected_id = $('#finYearchosen :selected').val();
       updateChart('tax','','getTaxChartData',selected_id,function(data){
    	   $('.graphTax').hide();
    	  if(data.length>0){
    		var finalObj = $.concat(data[0],data[1],data[2]);
            var taxPayable = $.concat(data[3]);
            donut_chart_data=[];
            donut_chart_data[0]={label:"Tax Payable",
                    value:taxPayable[0].granted};
            donut_chart_data[1]={label:"Tax Paid",value:taxPayable[0].availed};
            	 $('.taxText').html('').hide();
            	 $('.graphTax').show();
            barChart.setData(finalObj);
              Morris.Donut({
          	  element: 'taxTwo',
          	  colors:['#78909C','#4CAF50'],
          	  data:donut_chart_data,
              resize:true
          	});
         }else{
           	$('.taxText').html('<div style="padding: 122px;color:#797979">No Data Found</div>').show();
               }
        });
    });
    
	function updateChart(loderShow,chartType,act,SelectedFinYear,handleData) {
		  $.ajax({
    	      type: "POST",
    	      url: "php/dashboard.handle.php", 
    	      data: { act:act,type:chartType,finYear:SelectedFinYear},
    	      beforeSend:function(){
        	      	$('.'+loderShow).addClass('loading');
        	      },
        	  error:function(jqXHR, textStatus, errorThrown) {
            		  $('.chart-texture').removeClass('loading');
                	  $('.chart').empty();
            		  $('.chart').html("<div class='text-danger' style='height:325px'>Chart Could Not Be Loaded</div>");
            	  },
    	      success:function(data){
        	      data = JSON.parse(data);
        	      graph.options.yLabelFormat = function(y){
  				    return reFormateByNumber(y);
  				};
  				 handleData(data[2]);
  				
        	      $('.'+loderShow).removeClass('loading');
        	      
        	      } 
    	    });
      }
    
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
    
    $('.itDeclaration').on('click',function(e){
    	e.preventDefault();
    	window.location = "itDeclaration.php?employeeID="+$(this).data('id');	
    });

    
    $('.compensationClick').on('click',function(e){
    	e.preventDefault();
    	if($(this).data('date')){
    	element=$(this);
    	var dateVal=$(this).data('date');
    	var idVal=$(this).attr('id');
    	 $.ajax({
             dataType: 'html',
             type: "POST",
             url: "php/compensation.handle.php",
             cache: false,
             data: { act: '<?php echo base64_encode($_SESSION['company_id']."!insert");?>', date:dateVal, workingFor:$(this).data('title')},
             beforeSend:function(){
            	 $('#loader_'+idVal).show();
             },
             success: function (data) {
                 jsonData = JSON.parse(data);
                 if (jsonData[0] == "success") {
                     $('#'+idVal).removeClass('compensationClick btn-warning').addClass('btn-success').val('You \'re Working').data('date','');
                 	 }
                 else
                     if (jsonData[0] == "error") {
                     $('#workingFor').val('').prop('readonly', false);
                   	 $('#editLoader').loading(true);
                     }
               $('#loader_'+idVal).hide();
           }
    });
    }
    });

      //custom select box

      $(function(){ 
        $('select.styled').customSelect();
      });

  </script>

</body>
</html>


