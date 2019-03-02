
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Mosaddek">
<meta name="keyword"
	content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
<link rel="shortcut icon" href="img/favicon.png">

<title>Payslip Design</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<style>
.table tbody>tr>td, .table tfoot>tr>td {
	padding: 9px;
}

.dragged {
	position: absolute;
	top: 0;
	opacity: .5;
	z-index: 2000;
}

ol.vertical.sortable {
	min-height: 50px;
	margin: 0px 0px 9px;
	padding: 0;
}

.align {
	text-align: right
}

.sortable>li {
	display: block;
	margin: 5px;
	padding: 1px;
}
</style>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body style="color: #201C1C;">

	<section id="container" class="">
		<!--header start-->
      <?php
						
include_once (__DIR__ . "/header.php");
						?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
        <?php
								
include_once (__DIR__ . "/sideMenu.php");
								require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/payslipDesign.class.php");
								$payslipDesign = new payslipDesign ();
								$payslipDesign->conn = $conn;
							   if(isset($_REQUEST["payslip_id"]))
							   $designCustomised = $payslipDesign->select ($_REQUEST["payslip_id"]);
								$design_id = $_SESSION ['design_id'];
								$logo_flag = $_SESSION ['logo_id_flag'];
								$payslips = $payslipDesign->viewPayslips()["data"];
								
					?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<!--sidebar end-->
		<!--main content start-->
		
		
	<section id="main-content">
			<section class="wrapper" id="gallery_hide">
				<!-- page start-->
				<!--serlise-->
				<section class="panel">
					<header class="panel-heading">
						<a class="btn btn-sm btn-primary" href="payslipDesign.php"> Back</a>
						Customized Payslip Design
					</header>
					<div class="panel-body">
						<div class="container " style="width: 75%;">
							<a class="btn btn-sm btn-default pull-right"
								style="margin-bottom: 1%;" href="../hr/payslipDesign.php?payslip_id=<?php echo $_REQUEST['payslip_id']?> ">Edit</a>
						</div>

						<div class="container "
							style="width: 75%; box-shadow: 1px 0px 10px 4px rgb(136, 136, 136)">
							<section class="error-wrapper"
								style="margin-top: 2%; text-align: left">

								<table class="table" >
                         <?php
																									$title = "";
																									$title1 = "";
																									$title1 .= '<tr><td><img  alt="Company Logo"></td><td colspan="5">' . $designCustomised [0] ['company_name'] . '<br>' . $designCustomised [0] ['company_build_name'] . ',' . $designCustomised [0] ['company_street'] . ',<br>' . $designCustomised [0] ['company_area'] . ',<br>' . $designCustomised [0] ['company_city'] . ',' . $designCustomised [0] ['company_pin_code'] . '</td></tr>';
																									
																									$title .= '<tr><td colspan="5">' . $designCustomised [0] ['company_name'] . '<br>' . $designCustomised [0] ['company_build_name'] . ',' . $designCustomised [0] ['company_street'] . ',<br>' . $designCustomised [0] ['company_area'] . ',<br>' . $designCustomised [0] ['company_city'] . ',' . $designCustomised [0] ['company_pin_code'] . '</td><td colspan="1"><img alt="Company Logo" src="company_logo"></td></tr>';
																									if ($logo_flag == "0" && $logo_flag !== "01") {
																										$logo_f = explode ( ",", $designCustomised [0] ['logo'] );
																										if ($logo_f [0] == "Address") {
																											echo $title;
																										} else {
																											echo $title1;
																										}
																									}
																									
																									if ($logo_flag == "01" && $logo_flag !== "0" && $logo_flag !== "1") {
																										
																										$logo_set = explode ( ",", $designCustomised [0] ['logo'] );
																										if ($logo_set [0] == "Address") {
																											echo $title4 .= '<tr><td colspan="10">' . $designCustomised [0] ['company_name'] . '<br>' . $designCustomised [0] ['company_build_name'] . ',' . $designCustomised [0] ['company_street'] . ',<br>' . $designCustomised [0] ['company_area'] . ',<br>' . $designCustomised [0] ['company_city'] . ',' . $designCustomised [0] ['company_pin_code'] . '</td></tr>';
																										} else {
																											echo $title4 .= '<tr><td colspan="10"><img alt="Company Logo" src="company_logo" ></td></tr>';
																										}
																									}
																									?>
                            
                               
                                                            
                                    <tr>
										<td colspan="10"
											style="font-weight: bold; text-align: center;">Payslip For
											The Month of XXXX</td>

									</tr>
									<tr>
										<td colspan="4" style="border: 1px solid #DDD;">
											<ol id='allowances_avail_list'
												class='allowances_pay_structure vertical sortable'>
                  <?php
																		$row_firt = "";
																		$second = "";
																		$basic = "";
																		$leave = "";
																		$html_s = explode ( ',', $designCustomised [0] ['clo_left'] );
																		foreach ( $html_s as $k ) {
																			// dynamcicloums of value and label set
																			$label = str_ireplace ( "#", " ", $k );
																			$words = explode ( " ", $label );
																			$k_label = substr ( $words [1], 0 );
																			$k_value = substr ( $words [0], 0 );
																			$name_value = $k_value . "#" . $k_label;
																			
																			$row_firt .= "<li class='draggable'><span class='col-lg-6 '>$k_label</span>
                              <input type='hidden' name='$name_value'>
                            <span class='$k_value'>xxx</span></li>

                             ";
																		}
																		$html_r = explode ( ',', $designCustomised [0] ['clo_right'] );
																		foreach ( $html_r as $v ) {
																			$words_v = explode ( "#", $v );
																			$v_label = substr ( $words_v [1], 0 );
																			$v_value = substr ( $words_v [0], 0 );
																			$name_value = $v_value . "#" . $v_label;
																			
																			$second .= "<li class='draggable'>
                              <span class='col-lg-6 control-span'>" . str_replace ( "-", " ", $v_label ) . "</span>
                                   <span class='$v_value'>xxx</span>
                                         <input type='hidden' name='$name_value'></li> ";
																		}
																		
																		$html_b = explode ( ',', $designCustomised [0] ['basic_info'] );
																		foreach ( $html_b as $k ) {
																			if ($k !== '') {
																				$words = explode ( "#", $k );
																				$k_label = substr ( $words [1], 0 );
																				$k_value = substr ( $words [0], 0 );
																				
																				$name_value = $k_value . "#" . $k_label;
																				
																				$basic .= "<li class='draggable'><span class='col-lg-6 '>" . str_replace ( "-", " ", $k_value ) . "</span>
                              <input type='hidden' name='$name_value'>
                            <span class='$k_value'>:&nbsp;&nbsp;&nbsp;</span></li>
                                                                     ";
																			}
																		}
																		$html_l = explode ( ',', $designCustomised [0] ['leave_info'] );
																		foreach ( $html_l as $k ) {
																			if ($k !== '') {
																				// dynamcicloums of value and label set
																				$label = str_ireplace ( "#", " ", $k );
																				$words = explode ( " ", $label );
																				$k_label = substr ( $words [1], 0 );
																				$k_value = substr ( $words [0], 0 );
																				$name_value = $k_value . "#" . $k_label;
																				
																				$leave_rr .= "<li class='draggable'><span class='col-lg-6 '>$k_label</span>
                              <input type='hidden' name='$name_value'>
                            <span class='$k_value'>:&nbsp;&nbsp;&nbsp;</span></li>
                                                                     ";
																			}
																		}
																		
																		$html_logo = explode ( ',', $designCustomised [0] ['logo'] );
																		foreach ( $html_logo as $k ) {
																			if ($k !== '') {
																				// dynamcicloums of value and label set
																				$label = str_ireplace ( "#", " ", $k );
																				$words = explode ( " ", $label );
																				$k_label = substr ( (isset($words [1])? $words [1]:''), 0 );
																				$k_value = substr ( (isset($words [0])? $words [0]:''), 0 );
																				$name_value = $k_value . "#" . $k_label;
																				
																				$leave .= "<li class='draggable'><span class='col-lg-6 '>$k_label</span>
                              <input type='hidden' name='$name_value'>
                            <span class='$k_value'>:&nbsp;&nbsp;&nbsp;</span></li>
                                                                     ";
																			}
																		}
																		
																		echo $row_firt;
																		?>    
                        
                              </ol>
										</td>
										<td colspan='5' style='border: 1px solid #DDD;'>
											<ol id='allowances_avail_list'
												class='allowances_pay_structure vertical sortable'>
                                        <?php echo $second;?>
                           
                              </ol>
										</td>
									</tr>
									<?php if($designCustomised[0]['is_mastersalary']==0){?>
									<tr>
									
										<th colspan="2" style="text-align: center;">Payheads</th>
										<th colspan="2" style="text-align: center;">Current ( &#8377;)</th>
										<th colspan="2" style="text-align: center;">Deductions</th>
										<th colspan="2" style="text-align: center;">Current ( &#8377;)</th>
									</tr>
									<tr>
										<td colspan="2" style="text-align: center">Basic</td>
										<td colspan="2" class="align">1000.00</td>
										<td colspan="2" style="text-align: center">EPF</td>
										<td colspan="2" class="align">500.00</td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: center">HRA</td>
										<td colspan="2" class="align">1000.00</td>
										<td colspan="2" style="text-align: center">IT</td>
										<td colspan="2" class="align">500.00</td>
									</tr>
									<tr>
										<td colspan="2" style="font-weight: 900; text-align: right;">Gross</td>
										<td colspan="2" class="align">2000.00</td>
										<td colspan="2" style="font-weight: 900; text-align: right;">Total Deduction</td>
										<td colspan="2" class="align">1000.00</td>
									</tr>
									<tr>
										<td colspan="3	" style="font-weight: 900; text-align: right;">Net</td>
										<td colspan="6" class="align">1000.00</td>
									</tr>
									<tr>
										<td colspan="3">Amount in words</td>
										<td colspan="6">Thousand rupees only</td>
									</tr>
									<?php } else if($designCustomised[0]['is_mastersalary']==2){?>
										<tr>
											
										<th colspan="2" style="text-align: center;">Earnings</th>
										<th colspan="1" style="text-align: center;">Amount ( &#8377;)</th>
										<th colspan="1" style="text-align: center;">YTD ( &#8377;)</th>
										<th colspan="2" style="text-align: center;">Deductions</th>
										<th colspan="1" style="text-align: center;">Amount ( &#8377;)</th>
										<th colspan="1" style="text-align: center;">YTD ( &#8377;)</th>
														</tr>
														<tr>
														<td colspan="2" style="text-align: center">Basic</td>
														<td colspan="1" class="align">1000.00</td>
														<td colspan="1" class="align">1000.00</td>
														<td colspan="2" style="text-align: center">EPF</td>
														<td colspan="1" class="align">500.00</td>
														<td colspan="1" class="align">500.00</td>
														</tr>
														<tr>
														<td colspan="2" style="text-align: center">HRA</td>
														<td colspan="1" class="align">1000.00</td>
														<td colspan="1" class="align">1000.00</td>
														<td colspan="2" style="text-align: center">IT</td>
														<td colspan="1" class="align">500.00</td>
														<td colspan="1" class="align">500.00</td>
														</tr>
														<tr>
														<td colspan="2" style="font-weight: 900; text-align: right;">Gross Earnings</td>
														<td colspan="2" class="align">2000.00</td>
														<td colspan="2" style="font-weight: 900; text-align: right;">Gross Deductions</td>
														<td colspan="2" class="align">1000.00</td>
														</tr>
														<tr>
														<td colspan="3	" style="font-weight: 900; text-align: right;">Net</td>
														<td colspan="6" class="align">1000.00</td>
														</tr>
														<tr>
														<td colspan="3">Amount in words</td>
														<td colspan="6">Thousand rupees only</td>
														</tr>
										
									<?php }else{?>
									<tr>
									
										<th colspan="2" style="text-align: center;">Payheads</th>
										<th colspan="1" style="text-align: center;">Actuals ( &#8377;)</th>
										<th colspan="1" style="text-align: center;">Current ( &#8377;)</th>
										<th colspan="2" style="text-align: center;">Deductions</th>
										<th colspan="2" style="text-align: center;">Current ( &#8377;)</th>
									</tr>
									<tr>
										<td colspan="2" style="text-align: center">Basic</td>
										<td colspan="1" class="align">1000.00</td>
										<td colspan="1" class="align">1000.00</td>
										<td colspan="2" style="text-align: center">EPF</td>
										<td colspan="2" class="align">500.00</td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: center">HRA</td>
										<td colspan="1" class="align">1000.00</td>
										<td colspan="1" class="align">1000.00</td>
										<td colspan="2" style="text-align: center">IT</td>
										<td colspan="2" class="align">500.00</td>
									</tr>
									<tr>
										<td colspan="1" style="font-weight: 900; text-align: right;">Gross</td>
										<td colspan="2" class="align">2000.00</td>
										<td colspan="1" class="align">2000.00</td>
										<td colspan="2" style="font-weight: 900; text-align: right;">Total Deduction</td>
										<td colspan="2" class="align">1000.00</td>
									</tr>
									<tr>
										<td colspan="3"  style="font-weight: 900; text-align: right;">Net</td>
										<td colspan="6"  class="align">1000.00</td>
									</tr>
									<tr>
										<td colspan="3">Amount in words</td>
										<td colspan="6">Thousand rupees only</td>
									</tr>
									<?php }?>
									<tr>
										<td colspan="10" style="text-align: center">This is system
											does not require signature</td>
									</tr>
                      <?php
																						if ($logo_flag == "1" && $logo_flag !== "01") {
																							$logo_f = explode ( ",", $designCustomised [0] ['logo'] );
																							if ($logo_f [0] == "Address") {
																								echo $title;
																							} else {
																								echo $title1;
																							}
																						}
																						
																						if ($logo_flag == "01" && $logo_flag !== "0" && $logo_flag !== "1") {
																							
																							$logo_set = explode ( ",", $designCustomised [0] ['logo'] );
																							
																							if ($logo_set [1] == "Address") {
																								echo $title5 .= '<tr><td colspan="10">' . $designCustomised [0] ['company_name'] . '<br>' . $designCustomised [0] ['company_build_name'] . ',' . $designCustomised [0] ['company_street'] . ',<br>' . $designCustomised [0] ['company_area'] . ',<br>' . $designCustomised [0] ['company_city'] . ',' . $designCustomised [0] ['company_pin_code'] . '</td></tr>';
																							} else {
																								echo $title5 .= '<tr><td colspan="10"><img alt="Company Logo" src="company_logo"></td></tr>';
																							}
																						}
																						
																						?>
                              
                     
</table>
							</section>
						</div>
					</div>
				</section>
		</section>
</section> 
 
    <?php include_once (__DIR__."/footer.php");?>
      <!--main content end-->
		<!--footer start-->

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

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/jquery-ui.js"></script>
	

	<!--For auto Complete-->

</body>
</html>
