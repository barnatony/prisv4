<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>View Payroll</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />



<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
</head>
<style>
.align_text {
	text-align: right;
}

table#t002 td {
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
}
table#Leave_acc td{
    padding:5px;
}

th {
	text-align: center;
}

.font_bold {
	font-weight: bold;
}


.spanBold {
	font-weight: bold;
	font-size: 12px;
}

span.previous {
	float: left;
}

.spanCenter {
	text-align: center;
}

span.next {
	float: right;
}

table {
	width: 100%;
	color: rgb(54, 50, 50);
}

.img {
	background-size: 100% 100%;
	margin-left: 20px;
	width: 85px;
	height: 50px;
}

.width {
	width: 314px;
}

table#id td {
	padding: 6px;
}
.popover{
max-width:none !important;
width:auto !important;
}

</style>
<body>

	<!-- Modal-->
	<div id="branch_edit" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="modal-body">
					<form id="emailsendForm" method="POST" class="form-horizontal"
						role="form">
						<div class="row col-lg-12">
							<div class="form-group">
								<label class="col-lg-4 col-sm-4 control-label">Mail To </label>
								<div class="col-lg-8">
									<label for="exitingid" class="col-lg-6 m_l control-label"> <input
										value="0" name="emailId" type="radio" id="exitingid" checked>
										Registered
									</label> <label for="alterId"
										class="col-lg-6 m_l control-label"> <input value="1"
										name="emailId" type="radio" id="alterId"> Alternate Mail
									</label>
								</div>
							</div>
							<div class="form-group" style="display: none" id="alternateDiv">
								<label class="col-lg-4 col-sm-4 control-label"> Provide
									Alternate Mail </label>
								<div class="col-lg-8">
									<input id="mailIdText" class="form-control" type="text"
										placeholder="Enter EmailID" /> <input id="rmailIdText"
										class="form-control" type="hidden" />
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-sm  btn-success"
						id="resentEmail">Send</button>
					<button type="button" class="btn btn-sm btn-danger"
						data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>


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
												$designCustomised = $payslipDesign->select ();
											
												?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content" style="margin-top: 0px;">
			<section class="wrapper site-min-height">
				<!-- page start-->

				<section class="panel" id="section_hide">
					<header class="panel-heading"> View Payroll </header>
			

					<div class="panel-body">
				 <div class="container col-lg-12">
									<label class="col-lg-2 control-label" style="font-weight: 300;font-size: 14px;text-align: left;">View Payroll Using Month</label>
									<div class="col-lg-2 input-group">
										<span class="input-group-addon" style="cursor: pointer"><i class="fa fa-calendar"></i></span>
										<div class="iconic-input right">
											<input class="form-control" name="attendance_month" id="datepicker" required type="text">

										</div>
									</div>
									<div class="col-lg-2 displayHide" id="goButtonDiv">
								<button type="submit" class="btn btn-sm btn-success" id="go">View</button>
								<button type="button" class="btn btn-sm btn-default" title="More Filter" id="showhideButton"><i class="fa fa-search-plus"></i></button>
								</div>
								<div class="pull-right displayHide" id="wholemailSent">
										<form action="php/payroll.handle.php" method="post"
											id="payoutStatement">
											<input type="hidden"
												value="<?php echo base64_encode($_SESSION['company_id']."!payoutStatement");?>"
												name="act"> <input type="hidden" id="payoutDate"
												name="monthYear"><input type="hidden" id="empIDS"
												name="empIds"> <a href="#" title="Download Payout Statement for the viewed Employee"><button
													class="btn btn-sm btn-info" type="submit">
													<i class="fa fa-file-pdf-o"></i> Payout Statement<img src="../img/ajax-loader.gif"
												style="display: none; position: absolute;margin: 2% 2%;"
												class="employeemailflag">
												</button></a>
												<a href="#" title="Send email to the viewed Employees"><button
												class="btn btn-sm btn-info" type="button" id="EmailsentAllemp">
												<i class="fa fa-envelope-o"></i> Email</button></a>
										</form>
										
										<div class="popover-markup hidden">
											<section class="panel">
												<div class="head hide">Mail Confirmation</div>
												<div class="content hide">
													<form class="form-horizontal" role="form">
														<div class="col-lg-12 form-group checkboxes"
															style="margin-bottom: 0px;">
															<label class="checkbox-inline"
																style="font-size: 12px; color: #797979"> <input
																id="igoreChecked" type="checkbox">Ignore Already Sent
																Empolyee
															</label> <input type="hidden" id="empIDS">
														</div>
														<button type="submit" class="btn btn-default btn-block"
															id="igoremailSubmit">SEND</button>
													</form>
												</div>
											</section>
										</div>
									</div>
									
								</div>
								
										
			   </div>
			   <div class="panel-body displayHide" id="filterHideShowDiv">
			   <form class="form-horizontal" role="form" method="post">
					   <?php
							require_once (LIBRARY_PATH . "/filter.class.php");
							$filter = new Filter ();
							$filter->conn = $conn;
					       echo  $filterHtml = $filter->createFilterForScreen ('viewPayroll');
					    ?>
					  <div class="panel-body pull-right showedEmp displayHide">
			     	<button type="button" class="btn btn-sm btn-success" id="addendanceSubmit">View</button>
				   <button type="button" class="btn btn-sm btn-danger" id="filterCancel">Cancel</button>
			   </div>
			 </form> 
			   </div>

	</section>
     <section>
     <div id="run_pay_empLoader"></div>
    <div class="row" id="run_pay_emp"></div>
         	<div class="panel displayHide" id="pdf_div">
						<div style="text-align: left; margin-left: 60px;"
							class="panel-heading">
							<em id="payslip_name" class="clg-lg-6"></em>'s Payslip
						</div>
						<div style="display: block;" class="btn-group pull-left" id="back">
							<button class="btn btn-sm btn-info" type="button"
								style="margin-top: -39px; border-radius: 6px; margin-left: 8px;"
								title="view All">Back</button>
						</div>

						<div class="panel-body">
							<div class="container " style="width: 75%;">
								<div class="pull-right" id="id_set"
									style="margin-left: 1%; margin-bottom: 1%;"></div>
								<div class="pull-right" id="emailResent"></div>
							</div>
							<div class="container "
								style="width: 75%; box-shadow: 1px 0px 10px 4px rgb(136, 136, 136)">
								<section class="error-wrapper"
									style="margin-top: 2%; text-align: left">
       <?php
							$html = "";
							$Body = "";
							$html = '<br>
            <table><th colspan="4">' . "Payslip For The  Month  of " . " " . '<span id="monthPayrollval"></span></th>';
							$html_s = explode ( ',', $designCustomised [0] ['clo_left'] );
							$html_r = explode ( ',', $designCustomised [0] ['clo_right'] );
							$c = array_combine ( $html_s, $html_r );
							foreach ( $c as $k => $v ) {
								// dynamcicloums of value and label set
								// $label=str_ireplace("#"," ",$k);
								$lopcheck = (array_search("lop#Lop",$html_s)>0)?'left':'right';
								
								$words = explode ( "#", $k );
								$words[1] = strtoupper($words[1]); 
								$k_label = substr ( $words [1], 0 );
								$k_value = trim(substr ( $words [0], 0 ));
								
								// $label_v=str_ireplace("#"," ",$v);
								$words_v = explode ( "#", $v );
								$words_v[1] = strtoupper($words_v[1]);
								$v_label = substr ( $words_v [1], 0 );
								$v_value = trim(substr ( $words_v [0], 0 ));
								
								if ($lopcheck == 'left'){
								   if($k_label !='Lop'){
									$html .= '<tr><td><div class="col-lg-12"><div class="col-lg-6 col-sm-6"> <tr> <td width="25%"> <span class="spanBold previous"> ' . str_replace ( "-", " ", $k_label ) . '</span> </td><td><span class="spanCenter"> : 
				                          <span  class="_'.$k_value . '"></span></td><td>  <span class="spanBold previous">' . str_replace ( "-", " ", $v_label ) . ' </span> </td><td><span class="spanCenter"> :  <span  class="_' .$v_value.'"></span></span></td></tr></div></td></tr>';
								   }else{
								   	$html .= '<tr><td><div class="col-lg-12"><div class="col-lg-6 col-sm-6"> <tr> <td width="25%"> <span class="spanBold previous"> ' . str_replace ( "-", " ", $k_label ) . '</span> </td><td><span class="spanCenter"> :</span>
				                          <a role="button" tabindex="0" id="popover1" class="_'.$k_value . ' give_tooltip"></a></td><td>  <span class="spanBold previous">' . str_replace ( "-", " ", $v_label ) . ' </span> </td><td><span class="spanCenter"> :  <span  class="_' .$v_value.'"></span></span></td></tr></div></td></tr>';
								   }
								 }else{
				                 	if($v_label !='Lop'){
				                  		$html .= '<tr><td><div class="col-lg-12"><div class="col-lg-6 col-sm-6"><tr> <td width="25%"> <span class="spanBold previous"> ' . str_replace ( "-", " ", $k_label ) . '</span> </td><td><span class="spanCenter"> :
				        					      <span  class="_'.$k_value . '"></span></span></td><td>  <span class="spanBold previous">' . str_replace ( "-", " ", $v_label ) . ' </span> </td><td><span class="spanCenter"> :  <span  class="_' .$v_value.'"></span></span></td></tr></div></td></tr>';  
				                  	}else{
				                  		$html .= ' <tr><td><div class="col-lg-12"><div class="col-lg-6 col-sm-6"><tr> <td width="25%"> <span class="spanBold previous"> ' . str_replace ( "-", " ", $k_label ) . '</span> </td><td><span class="spanCenter"> :</span>
				                                   <span  class="_'.$k_value . '"></span></td><td> <span class="spanBold previous">' . str_replace ( "-", " ", $v_label ) . ' </span> </td><td><span class="spanCenter"> :  <a role="button" tabindex="0" id="popover1" class="_' .$v_value.' give_tooltip"></a></span></td></tr></div></td></tr>';
				                  	}
				                  }
							}	
					if($designCustomised [0] ['is_mastersalary']==0){
						$Body = '</table><br><table class="table table-striped table-hover" id="id"><tr style="border-bottom: 1px solid #000;" class="line_1 font_bold">
			                        <td  style="text-align:center;">Earnings</td><td colspan="1" style="text-align:right;width: 198px;">Monthly ( &#8377; )</td>
			                        <td  style="text-align:center;" colspan="2" >Deductions</td><td  style="text-align:right;">Monthly (  &#8377; )</td></tr>';
					}else if($designCustomised [0] ['is_mastersalary']==2){
						$Body = '</table><br><table class="table table-striped table-hover" id="id"><tr style="border-bottom: 1px solid #000;" class="line_1 font_bold">
			                        <td style="text-align:center;">Earnings</td><td style="text-align:right;">Amount ( ₹ )</td><td style="text-align:right;margin-left: 67px;">YTD ( ₹ )</td>
			                        <td colspan="1" style="text-align:center;">Deductions</td><td style="text-align:center;width: 152px;">Amount ( ₹ )</td><td style="text-align:center;width: 172px;">YTD ( ₹ )</td></tr>';
					}else{
						$Body = '</table><br><table class="table table-striped table-hover" id="id"><tr style="border-bottom: 1px solid #000;" class="line_1 font_bold">
			                        <td  style="text-align:center;">Earnings</td><td  style="text-align:right;width: 198px;">Actuals ( &#8377; )</td><td  style="text-align:right;width: 198px;">Current ( &#8377; )</td>
			                        <td  style="text-align:center;" colspan="1" >Deductions</td><td  style="text-align:right;">Current (  &#8377; )</td></tr>';
					}
										
							$allowances = "";
							$gross = "";
							$deduction = "";
							$other_salary = "";
							$allAllowNameId = array ();
							$allDeducNameId = array ();
							$masterAllowNameId = array ();
							// Allowances and Deduction
							Session::newInstance ()->_setGeneralPayParams ();
							$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
							
							foreach ( $allowDeducArray ['A'] as $allow ) {
								$masterAllowNameId [] = $allow ['pay_structure_id']."1";
								$allAllowNameId [] = $allow ['display_name'] . "!" . $allow ['pay_structure_id'];
							}
							//print_r($masterAllowNameId);
							foreach ( $allowDeducArray ['D'] as $dedu ) {
								
								$allDeducNameId [] = $dedu ['display_name'] . "!" . $dedu ['pay_structure_id'];
							}
							
							// miscAllowances and miscDeduction
							Session::newInstance ()->_setMiscPayParams ();
							$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
							
							foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
								$allAllowNameId [] = $miscAllow ['display_name'] . "!" . $miscAllow ['pay_structure_id'];
							}
							array_push ( $allAllowNameId, "Arrear!inc_arrear" );
							
							foreach ( $miscallowDeducArray ['MD'] as $miscDedu ) {
								$allDeducNameId [] = $miscDedu ['display_name'] . "!" . $miscDedu ['pay_structure_id'];
							}
							
							if(count($allAllowNameId)>count($allDeducNameId))
								$rowIterateCount = count($allAllowNameId);
							else
								$rowIterateCount = count($allDeducNameId);
							
							//Pay Parameters Left & Right
							$leftParams = $rightParams =array();
							for($i=0;$i<$rowIterateCount;$i++){//loop
								//dumping allowance values 
								if(isset($allAllowNameId[$i])){
									$a_label = explode("!",$allAllowNameId[$i])[0]; //allowance label i.e Basic
									$a_id = explode("!",$allAllowNameId[$i])[1]; //allowance ID i.e c_basic
									
									if($designCustomised [0] ['is_mastersalary']== 2){//allowances for YTD
										$m_id = explode("!",$allAllowNameId[$i])[1].'2';
										
									}else{
										$m_id = explode("!",$allAllowNameId[$i])[1].'1';
									}
									
									//if($allAllowNameId[$a_id] >0 && $allAllowNameId[$a_id]!='')
									if($designCustomised [0] ['is_mastersalary']==0){
										$leftParams[] = '<td>'. (isset($a_label)?$a_label:'') . '</td>
	      	 	 	 	 				<td style="width: 198px;" class="align_text" colspan="2"><span class=2_' . (isset($a_id)?$a_id:''). '></span></td>';
									}else if($designCustomised [0] ['is_mastersalary']==2){
										$leftParams[] = '<td>'. (isset($a_label)?$a_label:'') . '</td>
	      	 	 	 	 				<td style="width: 198px;" class="align_text"><span class=2_' . (isset($a_id)?$a_id:''). '></span></td><td style="width: 198px;" class="align_text"><span class=2_' . (isset($m_id)?$m_id:''). '></span></td>';	
									}else{
										$leftParams[] = '<td>' . (isset($a_label)?$a_label:'') . '</td>
	      	 	 	 	 				<td style="width: 198px;" class="align_text"><span class=2_' . (isset($a_id)?$a_id:''). '1></span></td><td style="width: 198px;" class="align_text"><span class=2_' . (isset($a_id)?$a_id:''). '></span></td>';
									}
								}	
								//dumping deduction values
								if(isset($allDeducNameId[$i])){
									$d_label = explode("!",$allDeducNameId[$i])[0];
									$d_id = explode("!",$allDeducNameId[$i])[1];
									if($designCustomised [0] ['is_mastersalary']== 2){
										$d_id1 = explode("!",$allDeducNameId[$i])[1].'2';
									}
								//if($allDeducNameId[$d_id] >0 && $allDeducNameId[$d_id]!='' || $d_id == 'c_it') //IT must be printed always
									if($designCustomised [0] ['is_mastersalary']==0){
										$rightParams[] = '<td style="width: 198px;">' . (isset($d_label)?$d_label:''). '</td>
      	 	 	 	 			    	 <td style="width: 198px;" colspan="2" class="align_text" ><span style="width: 198px;" class=2_' . (isset($d_id)?$d_id:''). '></span></td>';
										}else if($designCustomised [0] ['is_mastersalary']==2){
										$rightParams[] = '<td style="width: 198px;padding-left:61px;">' . (isset($d_label)?$d_label:''). '</td>
      	 	 	 	 			    	 <td style="width: 198px;" colspan="1" class="align_text" ><span style="width: 198px;" class=2_' . (isset($d_id)?$d_id:''). '></span><td style="width: 198px;" colspan="1" class="align_text" ><span style="width: 198px;" class=2_' . (isset($d_id1)?$d_id1:''). '></span></td>';
										}else{
										$rightParams[] = '<td>' . (isset($d_label)?$d_label:''). '</td>
      	 	 	 	 			    	 <td  class="align_text"colspan="2" ><span class=2_' . (isset($d_id)?$d_id:''). '></span></td>';
										}
								}
									
							}
							
							if(count($leftParams)>count($rightParams))
								$rowCount =count($leftParams);
							else
								$rowCount=count($rightParams);
							
							$payParams = "";
							
							for($i=0;$i<$rowCount;$i++){
									
								if(!isset($leftParams[$i]))
									$leftParams[$i] = '<td  style="width:25%" ></td><td class="align_text" style="width:25%" colspan="2"></td>';
								if(!isset($rightParams[$i]))
									$rightParams[$i] = '<td  style="width:25%" ></td><td class="align_text" style="width:25%" colspan="2"></td>';
											
								$payParams.="<tr>".$leftParams[$i].$rightParams[$i]."</tr>";
							}
							
							if (count ( $allAllowNameId ) != count ( $allDeducNameId )) {
								foreach ( $allAllowNameId as $key => $value ) :
									if (! isset ( $allDeducNameId [$key] ))
										$allDeducNameId [$key] = NULL;
								endforeach
								;
							}
							$combinedAllowDeduc = array_combine ( $allAllowNameId, $allDeducNameId );
							
							if (! $combinedAllowDeduc) {
								if (count ( $allDeducNameId ) != count ( $allAllowNameId )) {
									foreach ( $allDeducNameId as $key => $value ) :
										if (! isset ( $allAllowNameId [$key] ))
											$allAllowNameId [$key] = NULL;
									endforeach
									;
								}
							}
							$combinedAllowDeduc = array_combine ( $allAllowNameId, $allDeducNameId );
							// key contain allowances values
							// values contain deduction values
							
							foreach ( $combinedAllowDeduc as $key => $value ) {
								$allowanc = explode ( "!", $key );
								$deduction = explode ( "!", $value );
								$allowances .= '<tr><td>' .(isset($allowanc [0])?$allowanc [0]:'') . '</td><td style="width: 198px;" class="align_text"><span class=2_' . (isset($allowanc [1])?$allowanc [1]:'')  . '></span></td>
                                <td colspan="2" >' . (isset($deduction [0])?$deduction [0]:'') . '</td><td colspan="2" class="align_text"><span class=2_' . (isset($deduction [1])?$deduction [1]:'')   . '></span></td></tr>';
							}
							//echo $designCustomised [0] ['is_mastersalary']; echo $designCustomised [0] ['is_ItSummary'];
							echo $html;
							echo $Body;
							//echo $allowances;
							echo $payParams;
							
							if($designCustomised [0] ['is_mastersalary']==0){
								 $gross = '<tr><td class="font_bold" >
                                		   Gross Earnings</td><td colspan="2"  class="font_bold  align_text"><span class="2_gross_salary"></span></td >
                                 		 <td class="font_bold">Gross Deductions</td><td  class="font_bold  align_text"><span class="2_total_deduction"></span></td></tr>';
							}else if($designCustomised [0] ['is_mastersalary']==2){
								$gross = '<tr><td class="font_bold" >
                                		  Gross Earnings</td><td colspan="1"  class="font_bold  align_text"><span class="2_gross_salary"></span></td ><td colspan="1"  class="font_bold  align_text"><span class="2_gross_salary2"></span></td >
                                 		 <td class="font_bold" style="padding-left:61px;">Gross Deductions</td><td  colspan="1" class="font_bold  align_text"><span class="2_total_deduction"></span></td><td  colspan="1" class="font_bold  align_text"><span class="2_total_deduction2"></span></td></tr>';
							}else{
								$gross = '<tr><td class="font_bold" >
                                		  Gross Earnings</td><td colspan="1"  class="font_bold  align_text"><span class="2_gross_salary1"></span></td ><td colspan="1"  class="font_bold  align_text"><span class="2_gross_salary"></span></td >
                                 		 <td class="font_bold">Gross Deductions</td><td colspan="2" class="font_bold  align_text"><span class="2_total_deduction"></span></td></tr>';
							}
							echo $gross;
							echo $net = '<tr><td class="font_bold">
                                Net Amount</td><td colspan="6" class="font_bold"><span class="net "></span></td></tr>';
							echo $amount = '<tr><td >
                                Amount in words</td><td colspan="6"><span class="words"></span></td></tr></table>
                                <br><br>
				                 <table style="width:100%" id="t03" >
                                  <tr><td colspan="1" ></td><td colspan="8" style="text-align: justify">
                                  <em class="bold_">Note:</em><em class="note">'.$designCustomised [0] ['note'].'</em>
								</td><td colspan="2" ></td></tr></table>';
							
							?>
           </section>


							</div>
						</div>



					</div>
				</section>
				<!-- page end-->
			</section>
		</section>
	</section>
	</form>	
	<!-- page end-->

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
	<script src="../js/chosen.jquery.min.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/common-scripts.js"></script>
	
	<!--script for this page only-->
	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
      //come From salry  stmt link
      var employeeId='<?php echo isset($_REQUEST['employeeId'])?$_REQUEST['employeeId']:"";?>';
      var requestedDate='<?php echo isset($_REQUEST['date'])?$_REQUEST['date']:"";?>';
      <?php if(!isset($_SESSION['partialRun']))
      	    		$_SESSION['partialRun']=0;
      	?>
           var date="<?php echo ($_SESSION['partialRun']==1)?date('m Y', strtotime("+0 months",strtotime($_SESSION['payrollYear'].'-'.$_SESSION['monthNo'].'-01'))):date('m Y', strtotime("-1 months",strtotime($_SESSION['payrollYear'].'-'.$_SESSION['monthNo'].'-01')));?>";
		   var employee_id="0";
           var search_for="date";
        </script>
	<script type="text/javascript">
           $(document).ready(function () {
               $('#emailsendForm')[0].reset();
               $('#viewRunned').val('');
        	   if(employeeId){
            	   //For Salry smt internal link
        		   $('#container').addClass('sidebar-closed');
             	   $('#main-content').css('margin-left','0px');
             	   $('#sidebar').css('margin-left','-210px');
        	  		$("#datepicker").val(requestedDate);
        	  		$('#advance_form,#section_hide').hide();
        	  		design_fill(employeeId);
        	  		 $('#back').hide();        	  		
        	  	}else{
            	  	 $("#datepicker").val(date);
            	  	 $('#payoutDate').val(date);
        	  		 if (date !== '') {
            	  		  date_search('',date);
                         }
        	  	   $("#advance_form").toggle('hide');
                   $('#back').hide();
        	   }
         });



           

           $('#datepicker').datetimepicker({
        	    viewMode: 'years',
        	    format: 'MM YYYY'
            });
           
              
           $(document).on('click', '#back', function (e) {
               $('#pdf_div,#back').hide();
               $('#run_pay_emp,.emp_list1,#section_hide').show();
          });

           $(document).on('click', '#addendanceSubmit', function (e) {
               if($("#employeeSelected").chosen().val()!='' && $("#employeeSelected").chosen().val()!=null)
                   empValues=$("#employeeSelected").chosen().val();
               else
            	   empValues='Nil';    

         	  date_search(empValues,$("#datepicker").val());
           });
           
           $(document).on('click', '#go', function (e) {
        	   date_search('',$("#datepicker").val());
           });
          

           $(document).on('click', ".view1", function () {
               var epm_id = $(this).find("input").val();
               $(this).find("p").addClass('loader');
               design_fill(epm_id);
               get_leave(epm_id);
               $('#back').show();
               $('#advance_form,#section_hide').hide();
           });

         

           function date_search(empId,date) {
              if(empId!=null && empId!='' && empId!='Nil')
            	  empId=empId.join(); 

              if(empId!='Nil'){
        	   $('#payoutDate').val(date);// Payout Statment
        	   var inActive = <?php echo isset($_REQUEST['inActive'])?$_REQUEST['inActive']:0;?>;
               $.ajax({
                   dataType: 'html',
                   type: "POST",
                   url: "php/payslipDesign.handle.php",
                   cache: false,
                   data: {act: '<?php echo base64_encode($_SESSION['company_id']."!view");?>',affectedIds:empId,monthYear: date,inActive:inActive },
                   beforeSend:function(){
                       	$('#go').button('loading'); 
                       	$('#run_pay_empLoader').loading(true);
                        },
                     success: function (data) {
                    	//allways Scrol in top  
                    	 window.scrollTo(0, 0);
                       var json_obj = $.parseJSON(data); //parse JSON
                       if(json_obj[0]=='success' && json_obj[02].length>0){
                    	   $('#pdf_div').hide();
                           $('#run_pay_emp,.emp_list1').show();
                           $('#run_pay_emp').empty();
                           var emplo = [];
                           for (var i = 0; i < json_obj[02].length; i++) {
                               emplo.push(json_obj[02][i].employee_id);
                               if(json_obj[02][i].enabled==1){
                               	  $('#run_pay_emp').append('<div class="col-md-3 col-sm-3 col-xs-6 view1"  style="cursor:pointer;"><p style="width: 90%;"></p><div class="panel"><div class="get_emp_id panel-body ">      <div class="media"><div class="empImageDiv">      <input type="hidden" value=' + json_obj[02][i].employee_id + '>      </div><div><div class="media-body"><h4 style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:12px;" title=" ' + json_obj[02][i].employee_name + ' [ ' + json_obj[02][i].employee_id + '" >' + json_obj[02][i].employee_name + ' [ ' + json_obj[02][i].employee_id + ' ]</h4><br>     <strong>Gross</strong> : <em>' + json_obj[02][i].gross_salary + '</em><br>      <strong>Total Deductions </strong> : <em>' + json_obj[02][i].total_deduction + '</em><br>     <strong>Net Salary</strong> : <em>' + json_obj[02][i].net_salary + '</em><br></div></div></div></div></div></div>');
                               } else {
                              	  $('#run_pay_emp').append('<div class="col-md-3 col-sm-3 col-xs-6 view1"  style="cursor:pointer;"><p style="width: 90%;"></p><div class="panel"><div class="get_emp_id panel-body "> <div class="media"><label name="inActive" style=" background-color: transparent; color: #FCB322;border: 1px solid #FCB322;padding: 0px 2px 0px 2px; display: inline-block;font-size: 12px;position: absolute; right: 30px; margin-top: 2px;">Inactive</label><div class="empImageDiv"><input type="hidden" value=' + json_obj[02][i].employee_id + '>      </div><div><div class="media-body"><h4 style="text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:12px;" title=" ' + json_obj[02][i].employee_name + ' [ ' + json_obj[02][i].employee_id + '">' + json_obj[02][i].employee_name + ' [ ' + json_obj[02][i].employee_id + ' ]</h4><br>     <strong>Gross</strong> : <em>' + json_obj[02][i].gross_salary + '</em><br>      <strong>Total Deductions </strong> : <em>' + json_obj[02][i].total_deduction + '</em><br>     <strong>Net Salary</strong> : <em>' + json_obj[02][i].net_salary + '</em><br></div></div></div></div></div></div>');
      								}     
                           }
                           $('#wholemailSent').show();    
                           $('#empIDS').val(emplo);
                            $('#go').button('reset');   
                            $('#EmailsentAllemp').popover({ 
             				   html: true,
             				   placement: "left",
             			       title: function () {
             				        return $(this).parent().parent().parent().find('.head').html();
             				    },
             				    content: function () {
             				        return $(this).parent().parent().parent().find('.content').html();
             				    }
             				});
             				$('#goButtonDiv').hide();
             			 }else if (json_obj[0]=='success'){
             				   $('#goButtonDiv').hide();
                        	   $('#wholemailSent').hide();  
                               $('#run_pay_emp').empty();
                               $('#go').button('reset');
                          	 $('#run_pay_emp').append('<div class="col-md-12 col-sm-12"><div class="panel"> <div class="panel-body> <div class="media"> <span class="help-block"><span class="pull-left empty-message">No Records Found</span></span></div></div></div></div>');
                           }else{
                        	   $('#wholemailSent').hide();  
                               $('#run_pay_emp').empty();
                               $('#go').button('reset'); 
                          	 BootstrapDialog.alert(json_obj[1]);   
                              }
                       
                  	 $('#filterCancel').click();
                     $('#run_pay_empLoader').loading(false);
                     }
               });
              }else{
            	  $('#wholemailSent').hide();  
                  $('#run_pay_emp').empty();
                  $('#go').button('reset');
             	 $('#run_pay_emp').append('<div class="col-md-12 col-sm-12"><div class="panel"> <div class="panel-body> <div class="media"> <span class="help-block"><span class="pull-left empty-message">No Records Found</span></span></div></div></div></div>');
             	 $('#filterCancel').click();
              	}
              
           }

           function design_fill(employee_id) {
        	  $.ajax({
                   dataType: 'html',
                   type: "POST",
                   url: "php/payslipDesign.handle.php",
                   cache: false,
                   data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getEmployeePayrollData");?>', 'employee_id': employee_id , 'monthYear': $("#datepicker").val()},
                   beforeSend:function(){
                   	$('.loader').loading(true);
                    },
                  success: function (data) {
                	   var json_obj = $.parseJSON(data); //parse JSON
                	    if(json_obj[0]=='success' && json_obj[02].length>0){
                       $('#run_pay_emp,.emp_list1').hide();
                       $('#id_set,#emailResent').empty();
                       var emp = "'" + json_obj[2][0].employee_id + "'";
                       $('#rmailIdText').val(json_obj[02][0].employee_email);
                        $('#payslip_name').html(json_obj[02][0].employee_name+' ');
                        $('#emailResent').append('<a href="#branch_edit" title="Edit"  data-toggle="modal"><button  class="btn btn-sm btn-default" data-id="'+json_obj[02][0].employee_id+'" id="empForEmailsent"  type="button" title="Email send For '+json_obj[02][0].employee_name +' " ><i class="fa fa-envelope-o" aria-hidden="true"></i>  Email </button></a>');
                        $('#id_set').append('<form  action="php/payroll.handle.php" method="post"><input name="act" type="hidden" value="<?php echo base64_encode($_SESSION['company_id']."!monthlyPayslipPdf");?>"><input type="hidden" name="employee_id" value="(' + emp + ')"> <button  class="btn btn-sm btn-default" type="submit" title="Payslip Download For ' + json_obj[02][0].employee_name + '" > <i class="fa fa-file-pdf-o"></i> PDF    </button></form>');
					
                          $.each(json_obj[02][0], function (k, v) {
                           //display the key and value pair
                           if(v != null && v !="" ){
                           	$('._' + k).html(v.replace(/_/g, " "));
                           }else{
                        	   $('._' + k).addClass('text-center').html("-");
                               }
                        });
                  
                  var monthNames = ["January", "February", "March", "April", "May", "June",
                              		"July", "August", "September", "October", "November", "December"
                            		];
                   var objDate = new Date(json_obj[02][0].month_year);
                   //locale = "en-us",
                 //  month = objDate.toLocaleString(locale, { month: "long" });
                   var month=  monthNames [objDate.getUTCMonth()];
                    var str = $.trim($("#datepicker").val());
                	var res = str.split(" "); 
                    $('#monthPayrollval').html(month.toUpperCase()+" "+res[1]);
                    var iv = parseFloat(json_obj[02][1].net);
                    var il = parseFloat(json_obj[02][1].net)*12;
                    
                   $('.net').html(reFormateByNumber(iv.toFixed(2)));
                   $('.1net').html(reFormateByNumber(il.toFixed(2)));
                       $.each(json_obj[02][0], function (k, v) {
                           //display the key and value pair
                            var i = (v);
                           $('.2_' + k).html(i);
                       });
                      $('.words').html(json_obj[02][1].words);

                       $('#pdf_div').show();
                       $('.loader').loading(false);
                	    }else if (json_obj[0]=='success'){
                       	 $('#run_pay_emp').append('<div class="col-md-12 col-sm-12"><div class="panel"> <div class="panel-body> <div class="media"> <span class="help-block"><span class="pull-left empty-message">No Records Found</span></span></div></div></div></div>');
                        }else{
                       	 BootstrapDialog.alert(json_obj[1]);   
                           }
                   }
               });
           }


           $(document).on('click', "#resentEmail", function (e) {
               e.preventDefault();
               $('.help-block').empty();
              
               empId=$('#empForEmailsent').data('id');
               var mailId=($('input[name="emailId"]:checked').val()==0)?$('#rmailIdText').val():$('#mailIdText').val();

                	   if($('input[name="emailId"]:checked').val()==0){
                		   if(mailId!=null && mailId.indexOf('@') >= 0 &&  mailId.indexOf('.') >= 0){
                			   mailSend(empId,mailId,0);
                		   }else{
                			   $('.close').click();
                               BootstrapDialog.alert("<h5 style='color:#ff6c60'> <i class='fa fa-times' aria-hidden='true'></i>  Email Address is not valid  ");
                              }     
                    	   }else if($('input[name="emailId"]:checked').val()==1){
                    		   if(mailId!=null && mailId.indexOf('@') >= 0 &&  mailId.indexOf('.') >= 0){
                    			   mailSend(empId,mailId,0);
                    		   }else{
                    			   $('#mailIdText').after('<label class="help-block text-danger">Enter valid EmailID</label>');
                               }     
                      }
             });
           
           $(document).on('click', "#empForEmailsent", function (e) {
               e.preventDefault();
               $('#emailsendForm')[0].reset();
               $('#alternateDiv').hide();
           });
           
           $(document).on('change', "input[name='emailId']", function (e) {
				  e.preventDefault();
				  $('#alternateDiv').hide();
				  var selectedOpt = $(this).val()=='1'?'alternateDiv':'';
				  $('#'+selectedOpt).show();
          });

			function mailSend(empId,mailId,IgnoreEmpresend){
				$.ajax({
	                   dataType: 'html',
	                   type: "POST",
	                   url: "php/payroll.handle.php",
	                   cache: false,
	                   data: { act: '<?php echo base64_encode($_SESSION['company_id']."!sendEmail");?>',empFormat:empId,monthYear:$('#datepicker').val(),mailId:mailId,IgnoreEmpresend:IgnoreEmpresend },
	                   beforeSend:function(){
	                      	$('.employeemailflag').show();
	                       },
	                   success: function (data) {
		                	 var json_obj  = JSON.parse(data);
		                	 if(json_obj[0]!='success'){
	                          $('.close').click();
	                          BootstrapDialog.alert("<h5 style='color:#ff6c60'> <i class='fa fa-times' aria-hidden='true'></i> Email Address is not valid </h5>" +json_obj[2]);
	                         }else{
	                              $('.close').click();
	                      	      BootstrapDialog.alert("<h5  style='color: Green;'> <i class='fa fa-check' aria-hidden='true'></i>  Email Sent Successfully</h5>");
	                        }
		                   $('.employeemailflag').hide();
	                    }
	               });

	               }

			
			   $(document).on('click', "#igoremailSubmit", function (e) {
	               e.preventDefault();
	               mailSend($('#empIDS').val(),null,(($('input[type="checkbox"]:checked').val())?1:0));
	               $('#EmailsentAllemp').popover('destroy');
			   });

			  

		  function get_leave(employee_id) {
			   $('#popover1').unbind("click");
			   $('#popover1').on('click',function(e){
			       var check = $('#popover1').text();
			       e.preventDefault();
			   	   if(check > 0 ){
				       $.ajax({
			                 dataType: 'html',
			                 type: "POST",
			                 url: "php/payroll.handle.php",
			                 cache: false,
			                 data: { act: '<?php echo base64_encode($_SESSION['company_id']."!getLopDetails");?>','employee_id': employee_id},
			              
			                 success: function (data) {
				                 data = JSON.parse(data);
				                 var leaveacc = data[2];
				                 var html='';
				                 if(data[2] !=""){
					                html +='<table class="table table-bordered" id=Leave_acc><tbody>';
					                html +='<tr><td><b>Date</b></td><td><b>Leave </b></td></tr>';
									for(i=0;i<leaveacc.length;i++){
					  				   html +='<tr>';
					  				   $.each(leaveacc[i],function(k,v){
					  				    	html +='<td>'+v+'</td>';
					  					});
					  					html +='</tr>';
					  				  }
					                html+='</tbody></table>';
					                $("#popover1").popover(
					     					   {html:true,content:html,trigger:"focus"});
					                $("#popover1").popover("show");
			                   }
			                 }	
			                });
			   			}else{
			   				$('#popover1').popover('hide');
			   				$('#popover1').popover('destroy');
			   			}					 					 
		             });
			}

	
      </script>

</body>
</html>
