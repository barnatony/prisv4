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

<title>Payslip</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
	href="../css/bootstrap-datetimepicker.min.css" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<style>
.bold_ {
	font-weight: bold;
	font-size: 12px;
}

span.previous {
	float: left;
}

.date {
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

.line {
	border-top: 1px solid #000;
}

.line_1 {
	border-bottom: 1px solid #000;
}

.line_2 {
	border-right: 1px solid #000;
}

.line_3 {
	border-right: 1px solid #000;
}

.align_text {
	text-align: right;
}

table#t002 td {
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
}

th {
	text-align: center;
}

.font_bold {
	font-weight: bold;
}

.line {
	border-top: 1px solid #000;
}

.line_1 {
	border-bottom: 1px solid #000;
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
     <?php
					
include ("header.php");
					$logo_flag = $_SESSION ['logo_id_flag'];
					?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->


		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->

				<section class="panel" id="section_hide">
					<header class="panel-heading">
						<div class="panel-body" style="padding: 1px;">
							<form class="form-horizontal" role="form" method="post"
								id="view_payroll">
								<div class="col-lg-6">
									<label class="col-lg-3 control-label">View Payslip</label>
									<div class="col-lg-5 input-group">
										<span class="input-group-addon" style="cursor: pointer"><i
											class="fa fa-calendar"></i></span>
										<div class="iconic-input right">
											<input class="form-control" name="attendance_month"
												id="datepicker" required="" type="text"> <input
												class="form-control" id="payslipidInput" autocomplete="off"
												type="hidden">
										</div>
									</div>
									<div class="col-lg-2" style="text-align: right;" id="go_hide">
										<input class="btn btn-success" id="go" value="Go"
											type="button">
									</div>


								</div>

							</form>
						</div>
					</header>
					<div id="visibleIfnodata" class="hide">
						<h6 class="container" style="width: 50%; text-align: center;">
							<strong>No Available Data To generate Paysilp</strong>
						</h6>
						<br>
					</div>

					<div class="panel-body hide" id="dataLoad">
						<div class="container " style="width: 75%;">
							<div class="col-lg-2 pull-right" id="id_set"
								style="margin-top: -1%; margin-bottom: 1%; margin-right: -7%;">
							</div>
						</div>
						<div class="container "
							style="width: 75%; box-shadow: 1px 0px 10px 4px rgb(136, 136, 136)">
							<section class="error-wrapper"
								style="margin-top: 2%; text-align: left">
 
       <?php
							
							$html = "";
							$html .= '<br>
         		
            <table><th colspan="4">' . strtoupper ( "Payslip For The  Month  of " ) . " " . '<span id="monthPayrollval"></span></th>';
							
							$stmt0 = "SELECT clo_left,clo_right,logo,is_mastersalary  FROM " . $_SESSION ['cmpDtSrc'] . ".payslip_design WHERE set_active = 1; ";
							$result0 = mysqli_query ( $conn, $stmt0 );
							$row0 = mysqli_fetch_array ( $result0, MYSQLI_ASSOC );
						
							$html_s = explode ( ',', $row0 ['clo_left'] );
							$html_r = explode ( ',', $row0 ['clo_right'] );
							$c = array_combine ( $html_s, $html_r );
							
							foreach ( $c as $k => $v ) {
								// dynamcicloums of value and label set
								$words = explode ( "#", $k );
								$words[1] = strtoupper($words[1]);
								$k_label = substr ( $words [1], 0 );
								$k_value = trim(substr ( $words [0], 0 ));
								
								$words_v = explode ( "#", $v );
								$words_v[1] = strtoupper($words_v[1]);
								$v_label = substr ( $words_v [1], 0 );
								$v_value = trim(substr (  $words_v [0], 0 ));
								
								$html .= ' <tr><td><div class="col-lg-12">
                  <div class="col-lg-6 col-sm-6">                      
        <tr> <td width="22%"> <span class="bold_ previous"> ' . $k_label . '</span> </td><td width="25%"><span class="date"> : 
        <span  class=' . $k_value . '></span></span></td><td width="22%">  <span class="bold_ previous">' . $v_label . ' </span>  
        </td><td width="25%"><span class="date"> :  <span  class=' . $v_value . '></span></span></td></tr></div>
             </td></tr>                   
                  ';
							}
							
							if($row0 ['is_mastersalary']==0){
								$html .= '</table><br><table class="table table-striped table-hover"  id="id"><tr class="line_1 font_bold">
		                        <td  style="text-align:center;">Payheads</td><td  style="text-align:right;width: 198px;">Monthly ( &#8377; )</td>
		                        <td  style="text-align:center;" colspan="2" >Deductions</td><td  style="text-align:right;">Monthly (  &#8377; )</td></tr>';
							}else if($row0 ['is_mastersalary']==2){
								$html .= '</table><br><table class="table table-striped table-hover" id="id"><tr style="border-bottom: 1px solid #000;" class="line_1 font_bold">
			                        <td style="text-align:right;">Earnings</td><td style="text-align:right;">Amount ( ₹ )</td><td style="text-align:right;margin-left: 67px;">YTD ( ₹ )</td>
			                        <td colspan="1" style="text-align:center;">Deductions</td><td style="text-align:center;width: 152px;">Amount ( ₹ )</td><td style="text-align:center;width: 172px;">YTD ( ₹ )</td></tr>';
							}else{
								$html .= '</table><br><table class="table table-striped table-hover" id="id"><tr style="border-bottom: 1px solid #000;" class="line_1 font_bold">
			                        <td  style="text-align:center;">Payheads</td><td  style="text-align:left;width: 198px;" colsapn="1">Actuals ( &#8377; )</td><td  style="text-align:left;width: 198px;" >Current ( &#8377; )</td>
			                        <td  style="text-align:left;" >Deductions</td><td  style="text-align:right;">Current (  &#8377; )</td></tr>';
							}
							
							$allowNameId = array ();
							$deduNameId = array ();
							
							Session::newInstance ()->_setGeneralPayParams ();
							$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
							
							foreach ( $allowDeducArray ['A'] as $allow ) {
								$allowNameId [] = $allow ['display_name'] . "," . $allow ['pay_structure_id'];
								$masterArray [] = $allow ['pay_structure_id'] . "1,";
							}
							
							foreach ( $allowDeducArray ['D'] as $dedu ) {
								$deduNameId [] = $dedu ['display_name'] . "," . $dedu ['pay_structure_id'];
							}
							
							// miscAllowances and miscDeduction
							Session::newInstance ()->_setMiscPayParams ();
							$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
							
							foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
								$allowNameId [] = $miscAllow ['display_name'] . "," . $miscAllow ['pay_structure_id'];
							}
							array_push ( $allowNameId, "Arrear,inc_arrear" );
							
							foreach ( $miscallowDeducArray ['MD'] as $miscDedu ) {
								$deduNameId [] = $miscDedu ['display_name'] . "," . $miscDedu ['pay_structure_id'];
							}
							
							if(count($allowNameId)>count($deduNameId))
								$rowIterateCount = count($allowNameId);
							else
								$rowIterateCount = count($deduNameId);
							
								
							//Pay Parameters Left & Right
							$leftParams = $rightParams =array();
							for($i=0;$i<$rowIterateCount;$i++){//loop
								//dumping allowance values
								if(isset($allowNameId[$i])){
									$a_label = explode(",",$allowNameId[$i])[0]; //allowance label i.e Basic
									$a_id = explode(",",$allowNameId[$i])[1]; //allowance ID i.e c_basic
									//if($allAllowNameId[$a_id] >0 && $allAllowNameId[$a_id]!='')
									if($row0 ['is_mastersalary']==0){
										$leftParams[] = '<td>'. (isset($a_label)?$a_label:'') . '</td>
	      	 	 	 	 				<td style="width: 198px;" class="align_text" colspan="2"><span class=2_' . (isset($a_id)?$a_id:''). '></span></td>';
									}else if($row0 ['is_mastersalary']==2){
										$leftParams[] = '<td>'. (isset($a_label)?$a_label:'') . '</td>
	      	 	 	 	 				<td style="width: 198px;" class="align_text"><span class=2_' . (isset($a_id)?$a_id:''). '></span></td><td style="width: 198px;" class="align_text"><span class=2_' . (isset($a_id)?$a_id:''). '2></span></td>';
									}else{
										$leftParams[] = '<td>' . (isset($a_label)?$a_label:'') . '</td>
	      	 	 	 	 				<td style="width: 198px;text-align:right;" class=""><span class=2_' . (isset($a_id)?$a_id:''). '1></span></td><td style="width: 198px;text-align:right;" class=""><span class=2_' . (isset($a_id)?$a_id:''). '></span></td>';
									}
								}
								
								//dumping deduction values
								if(isset($deduNameId[$i])){
									$d_label = explode(",",$deduNameId[$i])[0];
									$d_id = explode(",",$deduNameId[$i])[1];
									//if($allDeducNameId[$d_id] >0 && $allDeducNameId[$d_id]!='' || $d_id == 'c_it') //IT must be printed always
									if($row0 ['is_mastersalary']==1){
										$rightParams[] = '<td style="width: 198px;">' . (isset($d_label)?$d_label:''). '</td>
      	 	 	 	 			    	 <td style="width: 198px;" colspan="2" class="align_text" ><span style="width: 198px;" class=2_' . (isset($d_id)?$d_id:''). '></span></td>';
									}else if($row0 ['is_mastersalary']==2){
										$rightParams[] = '<td style="width: 198px;padding-left:61px;">' . (isset($d_label)?$d_label:''). '</td>
      	 	 	 	 			    	 <td style="width: 198px;text-align:right" colspan="1" class="" ><span style="width: 198px;text-align:right" class=2_' . (isset($d_id)?$d_id:''). '></span><td style="width: 198px;text-align:right" colspan="1" class="" ><span style="width: 198px;text-align:right" class=2_' . (isset($d_id1)?$d_id1:''). '></span></td>';
									}else{
										$rightParams[] = '<td>' . (isset($d_label)?$d_label:''). '</td>
      	 	 	 	 			    	 <td  class="align_text"colspan="2" ><span class=2_' . (isset($d_id)?$d_id:''). '></span></td>';
									}
								}
							}
							//print_r($leftParams);
							if(count($leftParams)>count($rightParams))
								$rowCount =count($leftParams);
							else
								$rowCount=count($rightParams);
									
							$payParams = "";
									
							for($i=0;$i<$rowCount;$i++){
										
								if(!isset($leftParams[$i]))
									$leftParams[$i] = '<td  style="width:25%" ></td><td class="align_text" style="width:25%" ></td>';
								if(!isset($rightParams[$i]))
									$rightParams[$i] = '<td  style="width:25%" ></td><td class="align_text" style="width:25%" ></td>';
													
								$payParams.="<tr>".$leftParams[$i].$rightParams[$i]."</tr>";
							}
							
							if (count ( $allowNameId ) != count ( $deduNameId )) {
								foreach ( $allowNameId as $key => $value ) :
									if (! isset ( $deduNameId [$key] ))
										$deduNameId [$key] = NULL;
								endforeach
								;
								
							}
							$values = array_combine ( $allowNameId, $deduNameId );
							
							if (! $values) {
								if (count ( $deduNameId ) != count ( $allowNameId )) {
									foreach ( $deduNameId as $key => $value ) :
										if (! isset ( $allowNameId [$key] ))
											$allowNameId [$key] = NULL;
									endforeach
									;
								}
							}
							
							$values = array_combine ( $allowNameId, $deduNameId );
							// key contain allowances values
							// values contain deduction values
							
							foreach ( $values as $key => $value ) {
								$allowanc = explode ( ",", $key );
								$deduction = explode ( ",", $value );
								if($row0 ['is_mastersalary']==0){
									$html .= '<tr><td>' .(isset($allowanc [0])?$allowanc [0]:($allowanc [0]=null) ) . '</td><td style="width: 198px;" class="align_text" colspan="1"><span class=' .(isset($allowanc [1])?$allowanc [1]:($allowanc [1]=null) ). '></span></td>
	                                <td colspan="2" >' .(isset($deduction [0])?$deduction [0]:($deduction [0]=null) ). '</td><td colspan="2" class="align_text"><span class=' . (isset($deduction [1])?$deduction [1]:($deduction [1]=null)) . '></span></td></tr>';
								}else if($row0 ['is_mastersalary']==2){
									$html .= '<tr><td>' .(isset($allowanc [0])?$allowanc [0]:($allowanc [0]=null) ) . '</td><td style="width: 198px;text-align:right" class="" colspan="1"><span class=' .(isset($allowanc [1])?$allowanc [1]:($allowanc [1]=null) ). '></span><td style="width: 198px;text-align:right" class=""><span class=' .(isset($allowanc [1])?$allowanc [1]:($allowanc [1]=null) ). '2></span></td>
	                                <td colspan="1" >' .(isset($deduction [0])?$deduction [0]:($deduction [0]=null) ). '</td><td style="width: 198px;text-align:right" colspan="0" class=""><span class=' . (isset($deduction [1])?$deduction [1]:($deduction [1]=null)) . '></span><td style="width: 198px;text-align:right" class=""><span class=' . (isset($deduction [1])?$deduction [1]:($deduction [1]=null)) . '2></span></td></tr>';
								}else{
									$html .= '<tr><td>' .(isset($allowanc [0])?$allowanc [0]:($allowanc [0]=null) ) . '</td><td style="width: 198px;" class="align_right" colspan="0"><span class=' .(isset($allowanc [1])?$allowanc [1]:($allowanc [1]=null) ). '1></span><td style="width: 198px;" class="align_right"><span class=' .(isset($allowanc [1])?$allowanc [1]:($allowanc [1]=null) ). '></span></td>
	                                <td colspan="1" >' .(isset($deduction [0])?$deduction [0]:($deduction [0]=null) ). '</td><td colspan="0" class="align_text"><span class=' . (isset($deduction [1])?$deduction [1]:($deduction [1]=null)) . '></span></td></tr>';
								}
							}
							if($row0 ['is_mastersalary']==0){
								$html .= '<tr><td class="font_bold align_text" >
	                                 Gross</td><td colspan="2"  class="font_bold  align_text"><span class="gross_salary"></span></td >
	                                 <td class="font_bold align_text ">Net</td><td  class="font_bold  align_text"><span class="net_salary"></span></td></tr>';
							}
							else if($row0 ['is_mastersalary']==2){
								$html .= '<tr><td class="font_bold align_left" >
                                          Gross Earnings</td><td colspan="1"  class="font_bold  align_text"><span class="gross_salary"></span></td ><td colspan="1"  class="font_bold  align_text"><span class="gross_salary2"></span></td >
                                          <td class="font_bold" style="padding-left:61px;">Gross Deductions</td><td  colspan="1" class="font_bold  align_text"><span class="total_deduction"></span></td><td  colspan="1" class="font_bold  align_text"><span class="total_deduction2"></span></td></tr>
										 <td colspan="4" class="font_bold align_left ">Net</td><td  colspan="4" class="font_bold  align_text"><span class="net_salary"></span></td>';
							}
							else{
								$html .= '<tr><td class="font_bold align_left" >
                                 Gross</td><td colspan="0"  class="font_bold  align_left"><span class="gross_salary1"></span></td ><td colspan="1"  class="font_bold  align_left"><span class="gross_salary"></span></td >
                                 <td class="font_bold align_left ">Net</td><td  class="font_bold  align_text"><span class="net_salary"></span></td></tr>';
							}
							
							$html .= '<tr><td colspan="2">
                                Amount in words</td><td colspan="6"><span class="words"></span></td></tr></table>
                                <br><br>
                                  <table style="width:100%" id="t03" >
                                  <tr><td colspan="1" ></td><td colspan="8" style="text-align: justify">
                                  <em class="bold_">Note:</em><em class="note"></em><br>

</td><td colspan="2" ></td></tr></table>';
							
							echo $html;
							 ?> 
           </section>


						</div>
					</div>

				</section>

				<section></section>
				<!-- page end-->
			</section>
		</section>

		<!-- page end-->

		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
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
	<script src="../js/respond.min.js"></script>

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/moment.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<!--script for this page only-->
<script src="../js/bootstrap-dialog.js"></script>




	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">
           $('#view_payroll')[0].reset();
           var url = document.location.toString();
	       var monthYear=url.split('=')[1]
            if(monthYear){
                if(monthYear.length==6){
        	   var date=monthYear.substr(0, 2) + ' ' + monthYear.substr(2);
        	   $('#datepicker').val(date);
        	   design_fill(date);
               }}

           $("#datepicker").datetimepicker({
            	  format: 'MM YYYY',
             });
       
           $('#go').on('click', function (e) {
               var date = $('#datepicker').val();
               design_fill(date);
           });


           function design_fill(monthYear) {
if($('#payslipidInput').val()!=monthYear){
	$('#payslipidInput').val(monthYear)   
	var employee_id='<?php echo $_SESSION ['employee_id'];?>';
         	  $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/employee.handle.php",
                    cache: false,
                     data: {act: '<?php echo base64_encode($_SESSION['company_id']."!getEmployeePayrollData");?>', 'employee_id': employee_id , 'monthYear':monthYear},
                     beforeSend:function(){
                    	$('.loader').loading(true);
                     },
                     complete:function(){
                       $('.loader').loading(false);
                     },
                    success: function (data) {
                 	   var json_obj = $.parseJSON(data); //parse JSON
                 	  if(json_obj[0]=='success' && json_obj[02].length>0){       		
	var str = $("#datepicker").val();
	                   $('#dataLoad,#visibleIfnodata').removeClass('show hide');
                 	   $('#dataLoad').addClass('show');
                 	   $('#run_pay_emp,.emp_list1').hide();
                        $('#id_set').empty();
                        $('#whole_id').empty();
                       $('#visibleIfnodata').addClass('hide');
                        var emp = "'" + json_obj[2][0].employee_id + "'";
                        var name = json_obj[2][0].employee_name + "PDF";
                        $('#payslip_name').html(json_obj[2][0].employee_name);
                        $('#id_set').append('<form action="../hr/php/payroll.handle.php" method="post"><input name="act" type="hidden" value="<?php echo base64_encode($_SESSION['company_id']."!monthlyPayslipPdf");?>"><input type="hidden" name="employee_id" value="(' + emp + ')"> <button  class="btn btn-info" type="submit" title=' + name + ' > <i class="fa fa-file-pdf-o"></i> PDF    </button></form>');
						
                        //locale = "en-us",
                      //month = new Date(json_obj[2][0].month_year).toLocaleString(locale, { month: "long" });
                      //$('#monthPayrollval').html(month+" "+str.split(" ")[01]);
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
                    $.each(json_obj[2][0], function (k, v) {
                            $('.' + k).html((typeof v=='number')?reFormateByNumber(v.toFixed(2)):v);
                          });
                    $('.net_salary').html(reFormateByNumber(Number(json_obj[2][0].net_salary).toFixed(2)));
                     $('.words').html(firstToUpperCase(json_obj[2][1].words));
                    $('#pdf_div').show();
                    }
                 	 else if (json_obj[0]=='success'){
                     	  $('#dataLoad,#visibleIfnodata').removeClass('show hide');
                     	   $('#dataLoad').addClass('hide');
                     	   $('#visibleIfnodata').addClass('show');
                     	   $('#id_set').empty();
                   }else{
                    	BootstrapDialog.alert(json_obj[1]);   
                        }
                  }
                });
}
            }
      </script>

</body>
</html>
