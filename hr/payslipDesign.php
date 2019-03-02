<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Payslip Design</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link href="../css/jquery.fancybox.css" rel="stylesheet" />
<link href="../css/bootstrap-colorpicker.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/gallery.css" />
<style>
#passwordKey_chosen {
	width: 100% !important;
}

body.dragging, body.dragging *{
	cursor: move !important;
}

.colorpicker-hue {
    background-image: url(../img/hue.png);
}
.colorpicker-alpha {
    background-image: url(../img/alpha.png);
 
}
.dragged{
	position: absolute;
	top: 0;
	opacity: .5;
	z-index: 2000;
}

ol.vertical.sortable {
	min-height: 50px;
	margin: 0px 0px 9px;
	border: 1px solid rgb(153, 153, 153);
	padding: 0;
}

table img {
	background-size: 100% 100%;
	margin-left: 20px;
	width: 85px;
	height: 50px;
}

.sortable>li {
	display: block;
	margin: 5px;
	padding: 6px !important;
	border: 1px solid #BCB9B9;
	color: #fff;
	background: #40C4FF none repeat scroll 0% 0%;
}

.table tbody>tr>td, .table tfoot>tr>td {
	padding: 9px;
}

.sortable>li.placeholder {
	position: relative;
	margin: 0;
	padding: 0;
	border: none;
}

.sortable>li.placeholder:before {
	position: absolute;
	content: "";
	width: 0;
	height: 0;
	margin-top: -5px;
	left: -5px;
	top: -4px;
	border: 5px solid transparent;
	border-left-color: #999;
	border-right: none;
}

.line{
		margin-top:0 !important;
		margin-bottom:0 !important;
}

.thumbnail > img{
		cursor:pointer;
}

.payslipdesigns{
		width: 730px;
		height: 252px;
}

.payslip{
		margin-top: -9px;
}

.active {
		margin-right:10px;
}

.line-color{
		width: 13px;
		height: 15px;
		padding: 0px !important;
		
}
</style>
</head>

<body>

<section id="container" class="">
		<!--header start-->
     <?php include("header.php");
    				 error_reporting ( 0 );
   	  ?>
      <!--header end-->
		<!--sidebar start-->
		<aside class="<?php echo isset($_REQUEST["payslip_id"])?'':'';?>">
            <?php include_once("sideMenu.php");
            		require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/payslipDesign.class.php");
										$payslipDesign = new payslipDesign ();
										$payslipDesign->conn = $conn;
										if(isset($_REQUEST["payslip_id"]))
											$designCustomised = $payslipDesign->select ( $_REQUEST["payslip_id"] );
								  
										$payslips = $payslipDesign->viewPayslips()["data"];
									
										?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
	<section id="main-content">
		<section class="wrapper site-min-height">
				<!-- page start-->
							<!--maincontent   -->
 			<section id="main-content " class="<?php echo isset($_REQUEST["payslip_id"])?'displayHide':'';?>" >
		 					<div class="pull-right allset" style="margin-top: 0%;">
									<a href="masterSetup.php" class="btn btn-sm btn-danger" type="button" id="back-botton">
											<i class=" fa fa-arrow-left"></i> All Settings
										</a>
								</div>
				<section class="wrapper site-min-height " style="margin-top: 10px;">
							<!-- designs start -->
					<?php foreach($payslips as $payslip){?>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel col-sm-6 col-sm-offset-2 payslipdesigns " >
								<div class="panel-body">
									<div class="scroll-bar" >
										<div  class="col-sm-12 col-md-12 col-xs-12 design1 payslipdesign" id="design1">
												<div class="col-sm-4 col-md-4 col-xs-4" style="padding-top:15px;">
											       	<a class="fileupload-new thumbnail"  href="../hr/payslipDesign.php?payslip_id=<?php echo $payslip["payslip_id"]?>" >
											       			<img src="../img/payslip2.jpg" />
											       </a>
											    </div> 
											    <div class="col-sm-8 col-md-8 col-xs-8 payslip" id="payslip" >
															<h4><?php echo $payslip["payslip_name"]?>
																	<a  class="btn btn-xs btn-danger pull-right design"  href="../hr/payslipDesign.php?payslip_id=<?php echo $payslip["payslip_id"]?>" id="payslip_id">
																		<i class=" fa  fa-pencil"></i>Edit
																	</a>
														
																	<?php if($payslip["set_active"]==1){?>
																	<span class="badge label label-success pull-right" style="margin-right:5px">Active</span>
																			
																		<?php }else{?>
																	<button type="button" id="active" class="btn btn-xs btn-info pull-right hide" style="margin-right:5px"><i class = "fa fa-x fa-check" style="margin-right:10px;"></i></button>
																	<?php }?>
															
															</h4>
														
														<hr class="line"></hr>
														<p class="protection"  style="margin-top:11px;">Protection &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:&nbsp;&nbsp;<?php if($payslip["protect_password"] !==""){?><?php echo $payslip["protect_password"] ?><?php }else{?><?php echo NO ?><?php }?></p>
														<p class="mastsal">Additional Columns &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php if($payslip["is_mastersalary"]==0){?>
														 			<?php echo "None" ?>
														 			<?php }else if($payslip["is_mastersalary"]==1){?>
																	 <?php echo "Master Salary" ?>
														 			 <?php }else{?>
														 			 <?php echo "YTD" ?>
															 <?php }?></p>
														 <p class="itsum">IT Summary &emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;:&nbsp;<?php if($payslip["is_ItSummary"]==0){?>
																	 <?php echo NO ?>
																	 <?php }else{ ?>
														 			 <?php echo YES ?>
														 			 <?php } ?>
														 		</p>
														 	 
														 <p class="empdet">Employee Details &emsp;&emsp;&nbsp;&nbsp;&nbsp;:&nbsp; &nbsp;<?php 
														 		$name_value=$name_value1="";
														 		$html_s = explode ( ',', $payslip ['clo_left']);
																		 foreach ( $html_s as $k ) {
														 								// dynamickcoloums of value and label set
														 						$words = explode ( "#", $k );
														 						$k_label = substr ( $words [1], 0 );
														 						$k_value = substr ( $words [0], 0 );
														 						$name_value = $name_value.$k_label . "," ;
																		 }
																		 echo $name_value;
																		 ?>
																		 <?php 
																		 $html_s1= explode ( ',', $payslip ['clo_right']);
																	
																		 foreach ( $html_s1 as $k ) {
																		 	// dynamickcoloums of value and label set
																		 	$words = explode ( "#", $k );
																		 	$k_label = substr ( $words [1], 0 );
																		 	$k_value = substr ( $words [0], 0 );
																		 	$name_value1 = $name_value1.$k_label . "," ;
																		 }
																		 echo rtrim($name_value1,',');
														?>
														
														 </p>
														 <p>Line color &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;:&nbsp;&nbsp;
													     		<a  class="colorpicker-component colors"> 
													     		<input type="hidden"  value="<?php echo $payslip["color_code"]?>" name="color_cod" class="color_cod"/> 
													     		<span class="input-group-addon line-color"><i class="line-col"  style="height:19px;width:35px;"></i></span>
							     						 		</a>
							     						 </p>
												
												</div>
										</div>
								</div>
							</div>
						</div>	
					</div>
				</div>
				
							<?php }?>
			</section>
		</section>
							<!-- designs end -->
						<!-- payslip Design starts here -->
	<?php if(isset($_REQUEST["payslip_id"])){?>
					<!--main content start-->
				<!-- page start-->
				<form class="row" >
					<div class="col-md-12">
						<div class="row">
							<div class="col-lg-8">
								<section class="panel" id="dvContents">
									<header class="panel-heading">
										<a type="text" class="design"  id="back" href="../hr/payslipDesign.php">
											<i  aria-hidden="true"></i>Payslip Design 
										</a> <i class="fa fa-angle-double-left" aria-hidden="true"></i> <?php echo $designCustomised[0]["payslip_name"]?>
										<div class="pull-right">
											
											<?php if( $designCustomised[0]["set_active"]==0) {?>
											<button type="button"
												class="btn btn-sm btn-success label-success  setActive" id="set_active" data-form="#disp1" name="setActive" >
												Set Active</button>
											<?php }else{?>
											<input type="button"
												class="btn btn-sm btn-info label-success acti " id="acti" value="Active" />
												<?php }?>
											
											<?php if( $designCustomised[0]["protect_password"]=="") {?>
												<em id="productButton">
												<button type="button" class="btn btn-sm btn-danger" id="protectid">
												<i class="fa fa-unlock-alt" aria-hidden="true"></i> Protect
												</button>
												</em>
											<?php }else{?>
												<em id="productButton">
												<button type="button" class="btn btn-sm btn-primary" id="protectid">
												<i class="fa fa-lock" aria-hidden="true"></i> Protect
												</button>
												</em>
											<?php }?>
											<button type="button"
												class="btn btn-sm  mini  btn-success apply ">
												<i class="fa fa-check-circle-o" aria-hidden="true"></i>
												Apply
											</button>
											<button type="button"
												class="btn btn-sm  mini btn-danger cancel ">
												<i class="fa fa-times-circle-o" aria-hidden="true"></i>
												Cancel
											</button>
										</div>
									</header>
									
									<div class="panel-body displayHide" id="protectDiv">
										<form class="form-horizontal" role="form" id="passwordForm">
											<div id="editDiv" class="displayHide">
												<div class="pull-right">
													<i class="fa fa-pencil" title="Edit Password"
														style="cursor: pointer" aria-hidden="true"
														id="editProduct"></i>
												</div>
												<br>
												<div class="col-lg-12 row">
													<div class="form-group">
														<label class="col-lg-5 col-sm-5 control-label">Payslip
															Generate With Protected Key AS</label>
														<div class="col-lg-5">
															<input type="text" class="form-control" id="originalPass"
																readonly />
														</div>
													</div>
												</div>
											</div>
											<div class="col-lg-12 row" id="buttonDiv">
												<div class="form-group">
													<label for="slab_name"
														class="col-lg-5 col-sm-5 control-label">Payslip Generate
														With Protected Key</label>
													<div class="col-lg-7">
														<label for="key1" class="col-lg-6 control-label"><input
															id="key1" name="passprotect" type="radio" value="1"> YES</label>
														<label for="key2" class="col-lg-6 control-label"><input
															id="key2" name="passprotect" type="radio" value="0"> NO</label>
													</div>
												</div>

												<div class="form-group displayHide" id="passDiv">
													<label class="col-lg-5 control-label">Password As</label>
													<div class="col-lg-7">
														<select class="form-control" id="passwordKey"
															name="protect_as">
															<option value="employee_id">Employee ID</option>
															<option value="employee_dob">Employee DOB</option>
															<option value="employee_pan_no">Employee PAN NO</option>
															<option value="others">Others</option>
														</select>
													</div>
												</div>

												<div class="form-group displayHide" id="EnterPassDiv">
													<label class="col-lg-5 control-label">Enter Password</label>
													<div class="col-lg-7">
														<input type="text" class="form-control" id="enterPass"
															value="<?php echo $designCustomised[0]['protect_password']?>"
															maxlength="20" />
													</div>
												</div>

												<div class="col-lg-6 col-lg-offset-5" style="margin-top:10px;">
													<button type="submit" class="btn btn-sm btn-success"
														id="passwordChange">protect</button>
													<button type="button" class="btn btn-sm btn-danger"
														id="passCancel">Cancel</button>
												</div>
											</div>
										</form>
									</div>
									<div class="panel-body" id="customiseDiv">
									<form class=" payslip-custom"><div class="col-lg-12 panel-heading" style="border-color:#fff">Theming</div>
									<div class="col-lg-offset-1">
										<div class="col-lg-12 form-group row line-colors" style="margin-bottom:20px;">
											<div class="col-lg-4">Theme Color &emsp;&emsp;&emsp;&emsp;&emsp;:</div>
											<div class="col-lg-6">
												 <a id="cp2" class="colorpicker-component" >  
										     		 <input type="hidden" value="<?php echo $designCustomised[0]["color_code"]?>" name="color_code" class="color_code"/> 
			     							 		 <span class="input-group-addon line-color"><i class="line-col"  id="line-col" style="height:19px;width:35px;"></i></span>
			     							  	</a>
			     							</div>
			     						</div>
			     						<div class="col-lg-12 form-group row">
			     							<div class="col-lg-4">
			     								Border &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:
			     							</div>
			     							<div class="col-lg-8" style="margin-left:-14px;">
			     								<label for="all_border" class="col-lg-4 control-label" ><input type="radio" id="all_border" value="all_border" name="payslip-border"> All Border</label>
			     								<label for="outside_border" class="col-lg-5" >
			     								<input type="radio" id="outside_border" value="outside_border" name="payslip-border"> Outside Border </label>
			     							  	<input type = "hidden" id="ps_border">
			     							  </div>
										</div>
										<div class="col-lg-12 form-group row">
			     							<div class="col-lg-4">
			     								Color Header &emsp;&emsp;&emsp;&emsp;&emsp;:
			     							</div>
			     							<div class="col-lg-6">
												<input type="checkbox" id="color_header" name="colorHeader">
											</div>
										</div>
										<div class="col-lg-12 form-group row">
			     							<div class="col-lg-4">
			     								Additional Columns &emsp;&emsp; :
			     							</div>
			     							<div class="col-lg-6" >
			     								<select class="form-control" id="Add_column"
															name="AdditionalCol">
															<option value="0">None</option>
															<option value="1">Master Salary</option>
															<option value="2">YTD</option>
														</select>
			     							  </div>
										</div>
										<div class="col-lg-12 form-group row">
			     							<div class="col-lg-4">
			     								Include IT Summary &emsp;&emsp;:
			     							</div>
			     							<div class="col-lg-6">
												<input type="checkbox" id="inc_it_summary" name="IncItSummary">
											</div>
										</div>
										<div class="col-lg-offset-4 help-text itSummary text-info hide">
										
										This will make three pages for payslip which slow down the process of sending emails & downloading payslips.</div><br><br>
									</div>
										</form>
										<form id="logo">
											<table
												class="table table-bordered table-striped table-condensed ">
                                      <?php
																				
											$company_id = $_SESSION ['company_id'];
																				$title = "";
																				$title1 = "";
																				$title1 .= '<tr><td colspan="2"> 
                            <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            <li class="draggable">
                               <input type="hidden" name="Address">
                               Company Name And Address
                           </li>
                           </ol> 
                            </td>
                            <td colspan="2">
                            <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            <li class="draggable">
                             <input type="hidden" name="logo">
                              Company Logo
                            </li>
                              </ol> 
                            </td>
                                    </tr>';
																				$title .= '<tr>
                                    <td colspan="2">
                                         <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            <li class="draggable">
                             <input type="hidden" name="logo">
                              Company Logo
                            </li>
                              </ol> 
                            </td>
                            <td colspan="2"> 
                                     <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            <li class="draggable">
                                <input type="hidden" name="Address">
                               Company Name And Address
                                   
                            </li>
                           </ol> 
                                    </td>
                                    </tr>';
																				$title0 .= '<tr>
                                    <td colspan="2">
                                         <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            
                              </ol> 
                            </td>
                            <td colspan="2"> 
                                     <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                          
                           </ol> 
                                    </td>
                                    </tr>';
																				
																				$title2 .= '<tr>
                                    <td colspan="2">
                                         <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            <li class="draggable">
                             <input type="hidden" name="logo">
                              Company Logo
                            </li>
                              </ol> 
                            </td>
                            <td colspan="2"> 
                                     <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                           
                           </ol> 
                                    </td>
                                    </tr>';
																				
																				$title3 .= '<tr>
                                    <td colspan="2">
                                         <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            
                              </ol> 
                            </td>
                            <td colspan="2"> 
                                     <ol id="allowances_avail_list_1" class="allowances_pay_structure_top vertical sortable">
                            <li class="draggable">
                                <input type="hidden" name="Address">
                               Company Name And Address
                                   
                            </li>
                           </ol> 
                                    </td>
                                    </tr>';
																				
																				$logo_f = explode ( ",", $designCustomised [0] ['logo'] );
																				if ($logo_f [2] == "0" && $logo_f [2] !== "01") {
																					
																					if ($logo_f [0] == "Address") {
																						echo $title1;
																					} else {
																						echo $title;
																					}
																				} else {
																					
																					if ($logo_f [2] == "01" && $logo_f [2] !== "0" && $logo_f [2] !== "1") {
																						if ($logo_f [0] == "Address") {
																							echo $title3;
																						} else {
																							echo $title2;
																						}
																					} else {
																						echo $title0;
																					}
																				}
																				?> 
                                  </table>
							</form>

								<form id="datatake_form">
											<input type="hidden" name="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!update");?>" />
												
											<input type="hidden" name="datatakeval" id="datatakeval"
												value="2"> <input type="hidden" name="basic_val"
												id="basic_info_val"> <input type="hidden" name="leave_val"
												id="leave_info_val"> <input type="hidden" name="logo_id"
												id="logo_id"> <input type="hidden" name="logo_id_flag"
												id="logo_id_flag"><input type="hidden" name="pay_id_val" 
												id="pay_id"><input type="hidden" name="colorpicker_val" 
												id="color_picker_val"><input type = "hidden" id="border" name="payslip_border">	
												<input type = "hidden" id="is_header_color" name="is_header_color">
												<input type = "hidden" id="is_it_summary" name="is_it_summary">
												<input type = "hidden" id="is_master_sal" name="is_master_sal">
												
											<div class="forTable">	
											<table
												class="table table-bordered table-striped table-condensed dynamic_table  ">
												<tbody>
												<tr>
													<td colspan="3" style="border: 1px solid #DDD;">
														<ol id='allowances_avail_list'
															class='allowances_pay_structure vertical sortable'>
                            <?php
																												$row_firt = "";
																												$second = "";
																												$basic = "";
																												$leave = "";
																												$html_s = explode ( ',', $designCustomised [0] ['clo_left'] );
																											//print_r($html_s);
																												foreach ( $html_s as $k ) {
																													// dynamickcoloums of value and label set
																													$words = explode ( "#", $k );
																													$k_label = substr ( $words [1], 0 );
																													$k_value = substr ( $words [0], 0 );
																													$name_value = $k_value . "#" . $k_label;
																													$row_firt .= "<li class='draggable'><span class='col-lg-6 '>" . str_replace ( "-", " ", $k_label ) . "</span>
                              <input type='hidden' name='$name_value'>
                            <span id='$k_value'>:&nbsp;&nbsp;&nbsp;</span></li>";
																													
																												}
																												
																												$html_r = explode ( ',', $designCustomised [0] ['clo_right'] );
																												
																												foreach ( $html_r as $v ) {
																													$words_v = explode ( "#", $v );
																													$v_label = substr ( $words_v [1], 0 );
																													$v_value = substr ( $words_v [0], 0 );
																													$name_value = $v_value . "#" . $v_label;
																													
																													$second .= "<li class='draggable'>
                              <span class='col-lg-6 control-span'> " . str_replace ( "-", " ", $v_label ) . " </span>
                                   <span id='$v_value'>:&nbsp;&nbsp;&nbsp;</span>
                                         <input type ='hidden' name='$name_value'>
                            </li> ";
																											
																												}
																													
																											
																												$html_b = explode ( ',', $designCustomised [0] ['basic_info'] );
																										//	echo $html_b;
																												foreach ( $html_b as $k ) {
																													if ($k !== '') {
																														// dynamcicloums of value and label set
																														$words = explode ( "#", $k );
																														$k_label = substr ( $words [1], 0 );
																														$k_value = substr ( $words [0], 0 );
																														$name_value = $k_value . "#" . $k_label;
																														$basic .= "<li class='draggable'><span class='col-lg-6 '>" . str_replace ( "-", " ", $k_label ) . "</span>
                              <input type='hidden' name='$name_value'>
                            <span id='$k_value'>:&nbsp;&nbsp;&nbsp;</span></li>";
																													}
																												}
																												$html_l = explode ( ',', $designCustomised [0] ['leave_info'] );
																												$count = 0;
																												foreach ( $html_l as $k ) {
							
																													
																													
																													
																													if ($k !== '' && $count == 0) {
																														// dynamcicloums of value and label set
																														// $label_v=str_ireplace("#"," ",$v);
																														$words_v = explode ( "#", $k );
																														
																														$v_label = substr ( $words_v [1], 0 );
																														$v_value = substr ( $words_v [0], 0 );
																														$name_value = $v_value . "#" . $v_label;
																														$leave_rr .= "<li class='draggable'><span class='col-lg-6 '>" . str_replace ( "-", " ", $v_label ) . "</span>
                              <input type='hidden' name='$name_value'>
                            <span id='$v_value'>:&nbsp;&nbsp;&nbsp;</span></li>";
																														$count ++;
																													}
																												}
																												
																												$html_logo = explode ( ',', $designCustomised [0] ['logo'] );
																												foreach ( $html_logo as $k ) {
																													if ($k !== '') {
																														// dynamcicloums of value and label set
																														$label = str_ireplace ( "#", " ", $k );
																														$words = explode ( " ", $label );
																														$k_label = substr ( $words [1], 0 );
																														$k_value = substr ( $words [0], 0 );
																														$name_value = $k_value . "#" . $k_label;
																														
																														$leave .= "<li class='draggable'><span class='col-lg-6 '>" . str_replace ( "-", " ", $k_label ) . "</span>
                              <input type='hidden' name='$name_value'>
                            <span id='$k_value'>:&nbsp;&nbsp;&nbsp;</span></li>";
																													}
																												}
																												echo $row_firt;
																												?>    
                        
                              </ol>
													</td>
													<td colspan='3' style='border: 1px solid #DDD;'>
														<ol id='allowances_avail_list'
															class='allowances_pay_structure vertical sortable'>
                                        						<?php echo $second;?>
                           
                              							</ol>
													</td>
							</tr>


						</tbody>
				</table>
				</div>
								</form>
								<form id="logo_form">
											<table
												class="table table-bordered table-striped table-condensed">
                                   
                                  <?php
																																		if ($logo_f [2] == "1" && $logo_f [2] !== "01") {
																																			if ($logo_f [0] == "Address") {
																																				echo $title1;
																																			} else {
																																				echo $title;
																																			}
																																		} else {
																																			if ($logo_f [2] == "01" && $logo_f [2] !== "0" && $logo_f [2] !== "1") {
																																				if ($logo_f [0] == "Address") {
																																					echo $title2;
																																				} else {
																																					echo $title3;
																																				}
																																			} else {
																																				echo $title0;
																																			}
																																		}
																																		?>
                                
                                    
                                   	</table>
								</form>
										<footer class="panel-footer" style="background-color: #FFF;">
											<div class="pull-right">
												<button type="button"
													class="btn btn-sm  mini  btn-success apply displayHide">
													<i class="fa fa-check-circle-o" aria-hidden="true"></i>
													Apply
												</button>
												<button type="button"
													class="btn btn-sm  mini btn-danger cancel displayHide" href="../hr/payslipDesign.php">
													<i class="fa fa-times-circle-o" aria-hidden="true"></i>
													Cancel
												</button>
											</div>
										</footer>
									</div>
								</section>
							</div>

							<div class="col-lg-4 drag_button">
								<section class="panel">
									<header class="panel-heading"> Drag Information To Left </header>
										<div class="panel-body">
											Basic Information
											<form id="basic_info">
														<ol id="allowances_avail_list"
															class='allowances_pay_structure vertical sortable'>
			                                    			 <?php
																			echo $basic;
																?>  
			                              			    </ol>
			                              	</form>
                              		</div>
                              		</section>
                              	</div>
                              
                       		 </div>
                    	 </div>	
					</form>						
				<?php }?>
			
			<!-- payslip design end -->
		</section>
	</section>
</section>
	
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.fancybox.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/modernizr.custom.js"></script>
	<script src="../js/bootstrap-colorpicker.js"></script>

		<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript">
	
	 $(document).ready(function () {
		//$('.line-col').css('background-color','#01579b');
		  proteckeySet(0);
		
		 //  $('#acti').hide();
		   //$('#protectid,#protectDiv').hide();
		   
		   $('#protectDiv').hide();
	        sortableSet("allowances_pay_structure");
		  $('#passwordKey').chosen();
	  $('table tr.dynamic').remove();
	  var temp = "<?php echo($designCustomised[0]['is_mastersalary']) ?>";
	if(temp==0){
	  var row = '<table class="table table-bordered table-striped table-condensed "><tbody><tr class="dynamic"><th colspan="1" style="text-align:center;">Payheads</th><th colspan="2" style="text-align:center;">Current ( &#8377; )</th><th colspan="1" style="text-align:center;">Deductions</th><th colspan="2" style="text-align:center;">Current ( &#8377; )</th></tr><tr class="dynamic"><td colspan="1" style="text-align: center">Basic</td><td colspan="2" style="text-align: center"> </td><td colspan="1" style="text-align: center">ESI</td><td colspan="2" style="text-align: center"> </td></tr><tr class="dynamic"><td colspan="1" style="text-align: center">HRA</td><td colspan="2" style="text-align: center">  </td><td colspan="1" style="text-align: center">EPF</td><td colspan="2" style="text-align: center"> </td></tr><tr class="dynamic"><td colspan="1" style="font-weight: 900;text-align:right;">Gross</td><td colspan="2" style="text-align:center;"> </td><td colspan="1" style="font-weight: 900;text-align:right;">Total Deduction</td><td colspan="2" style="text-align:center;"> </td></tr><tr class="dynamic"><td colspan="1" style="font-weight: 900;text-align:right;">Net</td><td colspan="5" style="text-align:center;"> </td></tr><tr class="dynamic"><td>Amount in Words</td></tr></tbody></table>';
		 $(".forTable").after(row);
	}else if(temp==2){
		  var row = '<table class="table table-bordered table-striped table-condensed "><tbody><tr class="dynamic"><th colspan="1" style="text-align:center;">Earnings</th><th colspan="2" style="text-align:center;">Amount ( &#8377; )</th><th colspan="2" style="text-align:center;">YTD ( &#8377; )</th><th colspan="1" style="text-align:center;">Deductions</th><th colspan="2" style="text-align:center;">Amount ( &#8377; )</th><th colspan="2" style="text-align:center;">YTD ( &#8377; )</th></tr><tr class="dynamic"><td colspan="1" style="text-align: center">Basic</td><td colspan="2" style="text-align: center"> </td><td colspan="2" style="text-align: center">  </td><td colspan="1" style="text-align: center">ESI</td><td colspan="2" style="text-align: center"> </td><td colspan="2" style="text-align: center">  </td></tr><tr class="dynamic"><td colspan="1" style="text-align: center">HRA</td><td colspan="2" style="text-align: center">  </td><td colspan="2" style="text-align: center">  </td><td colspan="1" style="text-align: center">EPF</td><td colspan="2" style="text-align: center"> </td><td colspan="2" style="text-align: center">  </td></tr><tr class="dynamic"><td colspan="1" style="font-weight: 900;text-align:right;">Gross Earnings</td><td colspan="2" style="text-align:center;"> </td><td colspan="2" style="text-align: center">  </td><td colspan="1" style="font-weight: 900;text-align:right;">Gross Deductions</td><td colspan="2" style="text-align:center;"> </td><td colspan="2" style="text-align: center">  </td></tr><tr class="dynamic"><td colspan="1" style="font-weight: 900;text-align:right;">Net</td><td colspan="12" style="text-align:center;"> </td></tr><tr class="dynamic"><td>Amount in Words</td></tr></tbody></table>';
			 $(".forTable").after(row);
		}else{
			var row  = '<table class="table table-bordered table-striped table-condensed "><tbody><tr class="dynamic"><th colspan="1" style="text-align:center;">Payheads</th><th colspan="1" style="text-align:center;">Monthly ( &#8377; )</th><th colspan="1" style="text-align:center;">Current ( &#8377; )</th><th colspan="1" style="text-align:center;">Deductions</th><th colspan="2" style="text-align:center;">Current ( &#8377; )</th></tr>			<tr class="dynamic"><td colspan="1" style="text-align: center">Basic</td><td colspan="1" style="text-align: center"> </td><td colspan="1" style="text-align: center"> </td><td colspan="1" style="text-align: center">ESI</td><td colspan="2" style="text-align: center"> </td></tr><tr class="dynamic"><td colspan="1" style="text-align: center">HRA</td><td colspan="1" style="text-align: center"> </td><td colspan="1" style="text-align: center">  </td><td colspan="1" style="text-align: center">EPF</td><td colspan="2" style="text-align: center"> </td></tr><tr class="dynamic"><td colspan="1" style="font-weight: 900;text-align:right;">Gross</td><td colspan="2" style="text-align:center;"> </td><td colspan="1" style="font-weight: 900;text-align:right;">Total Deduction</td><td colspan="2" style="text-align:center;"> </td></tr><tr class="dynamic"><td colspan="1" style="font-weight: 900;text-align:right;">Net</td><td colspan="5" style="text-align:center;"> </td></tr><tr class="dynamic"><td>Amount in Words</td></tr></tbody></table>';
			  $(".forTable").after(row);
		}
	});


	  //theming functions
	   	$('input[type=radio][name=payslip-border]').click(function() {
	   		if($(this).is(':checked')) {
	   		var border = $(this).val();
	   		$('#ps_border').val(border);
	   		}
	       });
	       	   
		$('input[type=checkbox][name=colorHeader]').on('click change',function() {
	   		if($(this).is(':checked')) {
		   		$("#color_header").val('yes');
	   		}else{
	   			$("#color_header").val('no');
		   		}
	       });

		$('input[type=checkbox][name=IncItSummary]').on('click change',function() {
	   		if($(this).is(':checked')) {
		   		$("#inc_it_summary").val('1');
		   		$('.itSummary').removeClass('hide');
	   		}else{
	   			$("#inc_it_summary").val('0');
	   			$('.itSummary').addClass('hide');
		   		}
	       });

		$('input[type=select][name=AdditionalCol]').change(function() {
			$( "#Add_column option:selected" ).val();
	       });
		
	  
	      $('.apply').on('click', function (e) {
	    	  e.preventDefault();
	    	 var payslip_border=$("input[name=payslip-border]:checked").val();
	    	 var headercolor = $("#color_header").val();
	    	 var Itsummary = $("#inc_it_summary").val();
	    	 var AddColumn = $("#Add_column").val();
	    	 var colorpicker = $("#cp2").data("color");
	    	   
			   var id_get=$(this).data("id");
	       if(id_get){
	    	   $('#datatakeval').val(id_get);  
	    	   }
	   e.preventDefault();
	   		
	    	  var basic_info = $("#basic_info").serialize();
	          var leave_info = $("#leave_info").serialize();
	          var logo_form = $("#logo_form").serialize();
	          var logo = $("#logo").serialize();
	         
	          var payslip_info = "<?php echo $_REQUEST['payslip_id']?>";
	       
	        
	          if (logo_form !== '' && logo !== '') {
	              document.getElementById("logo_id").value = logo + "&" + logo_form;
	              document.getElementById("logo_id_flag").value = "&01";
	          } else {
	              if (logo_form !== '') {
	                  document.getElementById("logo_id").value = logo_form;
	                  document.getElementById("logo_id_flag").value = "&1";
	                 

	              } else {
	                  document.getElementById("logo_id").value = logo;
	                  document.getElementById("logo_id_flag").value = "&0";
	                 
	              }
	          }
	          document.getElementById("basic_info_val").value = basic_info;
	          document.getElementById("leave_info_val").value = leave_info;
	          document.getElementById("pay_id").value = payslip_info;
	          document.getElementById("color_picker_val").value = colorpicker;
	          document.getElementById("border").value = payslip_border;
	          document.getElementById("is_header_color").value = headercolor;
	          document.getElementById("is_it_summary").value = Itsummary;
	          document.getElementById("is_master_sal").value = AddColumn;
	        
	          $.ajax({
	              dataType: 'html',
	              type: "POST",
	              url: "php/payslipDesign.handle.php",
	              cache: false,
	              data:{colorpicker:colorpicker},
		              data: $('#datatake_form').serialize(),
	              		
	              beforeSend:function(){
	               	$('.apply').button('loading'); 
	                },
	                complete:function(){
	               	 $('.apply').button('reset');
	                },

	              success: function (data) {
		              	  data1 = JSON.parse(data);
	                  if (data1[0] == "success") {
	                     window.location.assign("paySlip-download.php?payslip_id=<?php echo isset($_REQUEST["payslip_id"])?$_REQUEST["payslip_id"]:"";?>");
	                  }
	                 
	              }

	          });

	 });
	      
$(document).on('click',"#set_active",function(e){
	e.preventDefault();
	var p_key = "<?php echo $_REQUEST['payslip_id']?>";
	 $.ajax({
         dataType: 'html',
         type: "POST",
         url: "php/payslipDesign.handle.php",
         cache: false,
         data: { act: '<?php echo base64_encode($_SESSION['company_id']."!setActive");?>',pays_id:p_key},
         beforeSend:function(){
          	$('#set_active').button('loading'); 
           },
          complete:function(){
        	    location.reload();
              },
        success: function (data) {
		        	 var jsonobject = JSON.parse(data);
		       
        }
});
});


        $('#cp2').colorpicker();
        $('.colors').colorpicker();
  		$(document).on('click', ".colors", function (e) {
	    	  e.preventDefault();
	    	  $('.dropdown-menu').hide();
	      });
	      
	     
	   $(document).on('click', ".cancel", function (e) {
    	  e.preventDefault();
          // $(".apply,.cancel").hide();
    	  $(".customise,#protectid").show();
    	  location.reload();
    	  sortableSet("false");
      });   
	      
	      
	      function sortableSet($var) {
	    	  $("ol."+$var).sortable({
	    		connectWith: '.allowances_pay_structure'
	    		 });
	    	 $("ol.allowances_pay_structure_top").sortable({
	    		  connectWith: '.allowances_pay_structure_top'
	    	  });
	    	     }
		     

	      $(document).on('click', "#protectid", function (e) {
	    	  e.preventDefault();
	   		 //$('#protectid').button('loading');
	   		
	          $('#protectDiv').toggle('show');
	      });      

	      $(document).on('click', "#passCancel", function (e) {
	    	  e.preventDefault();
	          $('#protectDiv').toggle('hide');
	          location.reload();
	      });

	      $(document).on('change', "input[name='passprotect']", function (e) {
			  e.preventDefault();
		     if($(this).val()==1){
			     $('#passDiv').show();
	    	  }else{
	    		  $('#passDiv,#EnterPassDiv').hide();
	    		  $('#enterPass').val('');
	        	  }
	      });
	      $('#passwordKey').on('change', function (e) {
	    	  e.preventDefault();
	          if($("#passwordKey option:selected").text()=='Others'){
	        	  $('#enterPass').val('');
	              $('#EnterPassDiv').show();
	         }else{
	        	 $('#EnterPassDiv').hide();
	        	 $('#enterPass').val($("#passwordKey option:selected").val());
	             }
	      });
	      
	      $(document).on('click', "#passwordChange", function (e) {
	    	  e.preventDefault();
	          proteckeySet(0);
	      });

	      $(document).on('click', "#editProduct", function (e) {
	    	  e.preventDefault();
	    	  proteckeySet(1);
	    	 });

	 
      function proteckeySet(flag){
          var password_key= $('#enterPass').val(); 
          var p_key = "<?php echo $_REQUEST['payslip_id']?>";
          $.ajax({
              dataType: 'html',
              type: "POST",
              url: "php/payslipDesign.handle.php",
              cache: false,
              data: { act: '<?php echo base64_encode($_SESSION['company_id']."!selectThemes");?>',pay_id_val:p_key},
              beforeSend:function(){
            		$('#protectid').button('loading'); 
                },
               complete:function(){
             	  //  location.reload();
                   },
             success: function (data) {
     		       var jsonobject = JSON.parse(data);
     		      if(jsonobject[2][0].payslip_border=='all_border'){
         		      $('#all_border').attr('checked',true);
         		      }else{
             		      $('#outside_border').attr('checked',true);
             		      }

     		      if(jsonobject[2][0].color_header=='yes'){
         		      $('#color_header').val('yes').prop('checked',true);
         		      }else{
         		    	  $('#color_header').val('no').prop('checked',false);
             		      }
     		     $("#Add_column option[value='"+jsonobject[2][0].is_mastersalary+ "']").prop("selected", "selected");
     		   
     		      if(jsonobject[2][0].is_ItSummary=='1'){
       		         $('#inc_it_summary').val('1').prop('checked',true);
       		         }else{
       		    	      $('#inc_it_summary').val('0').prop('checked',false);
           		          }	
             	}
          });
          
    	  $.ajax({
              dataType: 'html',
              type: "POST",
              url: "php/payslipDesign.handle.php",
              cache: false,
              data: { act: '<?php echo base64_encode($_SESSION['company_id']."!passwordset");?>',passKey:password_key,pays_id:p_key },
              beforeSend:function(){
               	$('#protectid').button('loading'); 
                },
                beforeSend:function(){
                   	$('#protectid').button('reset'); 
                    },
             success: function (data) {
            	  var jsonobject = JSON.parse(data);
            	   if(jsonobject[2]){
                      $("input[name=passprotect][value='1']").prop('checked', true);
                      if(flag==0){
                           $('#buttonDiv,#passDiv,#EnterPassDiv').hide();
                           $('#editDiv').show();
                      }else{
                    	  $('#buttonDiv,#passDiv').show();
                          $('#editDiv').hide();
                      }
                      $('#originalPass').val(jsonobject[2]);
                      if(jsonobject[2]=='employee_id' || jsonobject[2]=='employee_dob' || jsonobject[2]=='employee_pan_no' ){
                    	  $('#originalPass').val(jsonobject[2].replace("_", " ").toUpperCase());
                    	   $("#passwordKey option[value='" + jsonobject[2] + "']").prop("selected", true).trigger("chosen:updated");
                      }else{
                    	  $('#originalPass').val(jsonobject[2]);
                    	  $("#passwordKey option[value='others']").prop("selected", true).trigger("chosen:updated");
                    	  $('#EnterPassDiv').val(jsonobject[2]).show();
                      }
                      $("#productButton").html('<button type="button" class="btn btn-primary btn-sm css" id="protectid"><i class="fa fa-lock" aria-hidden="true"></i> Protect</button>').show();
                      }else{
                    	  $("input[name=passprotect][value='0']").prop('checked', true);
                    	  $('#buttonDiv').show();
                          $('#editDiv').hide();
                          $('#protectDiv').hide();
                          $("#productButton").html('<button type="button" class="btn btn-sm btn-danger" id="protectid"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Protect</button>').show();
                     }
             }

          });
          }
    
  </script>


</body>
</html>
