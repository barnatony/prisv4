<?php
/*
 * ----------------------------------------------------------
* Filename : event.handle.php
* Classname: event.class.php
* Author : Rajasundari
* Database : HoildayEvent
* Oper : HoildayEvent Actions
*
* ----------------------------------------------------------
*/
class ItDeclaration {
	/* Member variables */
	function __construct() {
		//set all pay params.
		Session::newInstance ()->_setMiscPayParams ();
		$this->miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
		Session::newInstance ()->_setGeneralPayParams ();
		$this->allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
	}
	function __destruct() {
		//mysqli_close ( $this->conn );
	}

	function taxSummaryPdf($employeeId, $benefitsPaid , $partBContent , $empName, $panNum,$year){
		$date = date('d-m-Y');
		$htmlStyle = '<style>
					table, th, td {
					   border-collapse: collapse;
					}
					body,pre { font-family: Open Sans, sans-serif; font-size: 9pt; }
					td {
					    padding: 4px;
					     border:0;
					}

					table#t011 {border:1px solid #000;}
					table#t011 tr {border-left:1px solid #000;}
					table#t011 td {border-left:1px solid #000;}
                  </style>';
		$html = $htmlStyle;
		$period = ($year!=$_SESSION['financialYear'])?"01 Apr ".substr ($year, 0, 4 ) ."- 31 Mar ".(substr ( $year, 0, 4 ) + 1)."":"01 Apr ".substr ( $_SESSION['financialYear'], 0, 4 ) ." - 31 Mar ".(substr ( $_SESSION['financialYear'], 0, 4 ) + 1)."";
		$assyear =($year!=$_SESSION['financialYear'])?"".(substr($year,0,4)+1)." - ".(substr($year,0,4)+2)."":"".(substr($_SESSION['financialYear'],0,4)+1)."-".(substr($_SESSION['financialYear'],0,4)+2)."";
		$headhtml = '<table style="width:100%;border:1px solid #000;" border="1">
		<thead><tr><th colspan="4" style="text-align:center">Form 16 - PART B ( Annexure )</th></tr></thead><tbody>
		<tr><td>Employee</td><td>'.$empName.'</td><td>Period</td>
				<td>'.$period.'</td></tr>
		<tr><td>Emp.PAN</td><td>'.$panNum.'</td><td>Assessment Year</td><td colspan="1">'.$assyear.'</td></tr></tbody></table>';
		$html .=$headhtml;
		$tillNowHeader = ($year!=$_SESSION['financialYear'])?"Apr -Mar (INR)":"Actuals ".((date("M",strtotime($_SESSION['current_payroll_month'])) != 'Apr')?"Apr - ".date("M",strtotime('-1 day',strtotime($_SESSION['current_payroll_month'])))." (INR)":"");
		$projectionheader = ($year!=$_SESSION['financialYear'])?"Projected(INR)":"Projected ".((date("M",strtotime($_SESSION['current_payroll_month'])) != 'Mar')?"".date("M",strtotime($_SESSION['current_payroll_month']))." - Mar (INR)":"Mar");
	    
		$html .='<h3>Details of Salary paid and any other Income</h3>
		<table class="table"  id="t011" style="width:100%;" border="1"><thead><tr><th>Particulars (i) Benifits Paid</th><th style="text-align:center;">'.$tillNowHeader.'</th><th style="text-align:center;">'.$projectionheader.'</th><th style="text-align:center;"><i class=""fa fa-rupee></i>Total</i></th></tr></thead><tbody>' . $benefitsPaid . '<br> </tbody></table><pagebreak /><br>';
		$html .=$headhtml;
		$html .='<h3>Details of Tax deducted<h3><table class="table"  id="t011" style="width:100%;" border="1"><thead><tr><th>Details of all deducted</th><th style="text-align:center;"><i class=""fa fa-rupee></i></th><th style="text-align:center;"><i class=""fa fa-rupee></i></th><th style="text-align:center;"><i class=""fa fa-rupee></i></th></tr></thead><tbody>' . $partBContent . '</table>';
		$html .='<pre style="text-align: right;">Generated on : ' .$date.'</pre>';
		require_once (dirname(dirname ( dirname ( __FILE__ ) ) ). "/js/mpdf/mpdf.php");
		$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
		$mpdf->WriteHTML ( $html );
		$content = $mpdf->Output ( 'TaxSummaryFor_' . $employeeId. '.pdf', 'D' ); // S
		exit ();
	}

	function taxSummaryData($employeeId,$summaryYear){
		//get projection salary data - part B content
		//get form16Data
		//returns JSON RESULT retrun Json Rsult
		
		$year= $summaryYear;
		$a_json = array ();
		$year1=substr($year,0,4);
		$incomequery = "SELECT id,employee_id,year,prev_earnings_app,perqs,house_prop_inc,CAST(ROUND(other_income) AS DECIMAL(15,2)) other_income,CAST(ROUND(total_inc) AS DECIMAL(15,2)) total_inc,CAST(ROUND(exe_hra) AS DECIMAL(15,2)) exe_hra,exe_lta,CAST(ROUND(exe_oth) AS DECIMAL(15,2)) exe_oth,
							   yearly_gross,gti,ded_80c,ded_80d,ded_80e,ded_80g,ded_other,CAST(ROUND(taxable_inc) AS DECIMAL(15,2)) taxable_inc,taxon_employment,epf_employee,epf_employer,CAST(CEIL(tax) AS DECIMAL(15,2)) tax,CAST(CEIL(ec) AS DECIMAL(15,2)) ec,CAST(CEIL(shec) AS DECIMAL(15,2)) shec,
							   prev_tax_app,surcharge,CAST(CEIL(tax_paid) AS DECIMAL(15,2)) tax_paid,relief,rebate,CAST(CEIL(old_tax_paid) AS DECIMAL(15,2)) old_tax_paid,CAST(CEIL(tax_payable) AS DECIMAL(15,2)) tax_payable,tds,updated_on,updated_by
						FROM  employee_income_tax WHERE employee_id ='" . $employeeId . "'  AND  year ='$year'  ";
		
		$payrollSal = $payrollSaltot = "";
		$previewSal = "";
		$masterSal = "";
		$total = "";
		$isNotice = 1;
		$next_month = date ( "Y-m-d", strtotime ( "+1 months", strtotime ( $_SESSION ['current_payroll_month'] ) ) );
		$noticeQuery = "SELECT  n.employee_id,n.status
						FROM employee_work_details w
						LEFT JOIN emp_notice_period n
						ON w.employee_id = n.employee_id
						WHERE n.status !='R' AND n.employee_id = '$employeeId'"; //AND DATE_FORMAT(n.notice_date,'%m%Y') = DATE_FORMAT('". $_SESSION ['current_payroll_month']."','%m%Y')
		
		$noticeResult = mysqli_query (  $this->conn , $noticeQuery );
		$row = mysqli_fetch_array ( $noticeResult, MYSQLI_ASSOC );
		
		if($row['employee_id'] ==""){ //notice period check for salary Projection
			$remainigMOnth = "(TIMESTAMPDIFF(MONTH,'" . $next_month . "', DATE_FORMAT(STR_TO_DATE('" . $_SESSION ['nextyear_date'] . "','%d/%m/%Y'),'%Y-%m-%d'))";
		}else{
			$remainigMOnth = 0;
		}
		foreach ( $this->allowDeducArray['A'] as $allow ) {
			$payrollSal .= "IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0) " . $allow ['pay_structure_id'] . ",";
			$payrollSaltot .= "'0.00' as " . $allow ['pay_structure_id'] . "1,IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0) " . $allow ['pay_structure_id'] . "2,"; // for past year total allowance payments
			if($row['status']=="" || $row['status'] !='A'){
				$masterSal .= "(s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0))" . $allow ['pay_structure_id'] . "1,";
			}else{
				$masterSal .= "((s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0))" . $allow ['pay_structure_id'] . "1,";
			}
			if($row['status']=="" || $row['status'] !='A'){
				$total .= "(IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0)+s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0)) " . $allow ['pay_structure_id'] . "2,";
			}else{
				$total .= "((IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0)+s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0)) " . $allow ['pay_structure_id'] . "2,";
			}
		}
		
		// miscAllowances and miscDeduction
		$miscAllowpayroll = "";
		$miscAllowPerview = "";
		$miscAllowothers = $miscAllowotherstot = "";
		$miscAllowothers1 ="";
		$miscAllowothers2 ="";
		$others = $others1 = $others2 = $gross = $otherstot = "";
		foreach ( $this->miscallowDeducArray['MP'] as $miscAllow ) {
			
			if ($miscAllow ['pay_structure_id'] == 'c_incentive' || $miscAllow ['pay_structure_id'] == 'c_bonus') {
				$miscAllowothers .= "IFNULL(SUM(p." . $miscAllow ['pay_structure_id'] . "),0) " . $miscAllow ['pay_structure_id'] . ",";
				$miscAllowotherstot .= "'0.00' as " . $miscAllow ['pay_structure_id'] . "1,IFNULL(SUM(p." . $miscAllow ['pay_structure_id'] . "),0) " . $miscAllow ['pay_structure_id'] . "2,";  // for past year total misc payments
				$miscAllowothers1 .= "IFNULL((pt." . $miscAllow ['pay_structure_id'] . "),0) " . $miscAllow ['pay_structure_id'] . "1,";
				$miscAllowothers2 .= "IFNULL((IFNULL(SUM(p." . $miscAllow ['pay_structure_id'] . "),0)+IFNULL((pt." . $miscAllow ['pay_structure_id'] . "),0)),0)" . $miscAllow ['pay_structure_id'] . "2,";
			}
			if ($miscAllow ['pay_structure_id'] != 'c_incentive' && $miscAllow ['pay_structure_id'] != 'c_bonus') {
				$miscAllowpayroll .= "p." . $miscAllow ['pay_structure_id'] . "+";
				$miscAllowPerview .= "pt." . $miscAllow ['pay_structure_id'] . "+";
			}
		}//0.00 as". $miscAllow ['pay_structure_id'] ."1,
		if ($miscAllowpayroll != null || $miscAllowPerview != null) {
			$others = "IFNULL(SUM(" . substr ( $miscAllowpayroll, 0, - 1 ) . "),0) c_others,";
			$otherstot = "'0.00' as c_others1,IFNULL(SUM(" . substr ( $miscAllowpayroll, 0, - 1 ) . "),0) c_others2,"; // for past year total misc payments
			$others1 = "IFNULL(SUM(" . substr ( $miscAllowPerview, 0, - 1 ) . "),0) c_others1,";
			$others2 = "IFNULL((IFNULL(SUM(" . substr ( $miscAllowpayroll, 0, - 1 ) . "),0)+IFNULL(SUM(" . substr ( $miscAllowPerview, 0, - 1 ) . "),0)),0) c_others2,";
		}else{
			$otherstot = "'0.00' as c_others1,";
		}
		if($row['status']=="" || $row['status'] !='A'){
			$gross .= "(IFNULL(SUM(p.gross_salary),0)+s.employee_salary_amount*$remainigMOnth)+IFNULL(pt.gross_salary,0))  gross";
		}else{
			$gross .= "((IFNULL(SUM(p.gross_salary),0)+s.employee_salary_amount*$remainigMOnth)+IFNULL(pt.gross_salary,0))  gross";
		}
		
		if($row['status'] =='S'){
			$miscAllowothers1 = $others1 =""; $masterSal ="";
			$total = $payrollSaltot;
			$miscAllowothers2= $miscAllowotherstot;
			$others2= $otherstot;
			$gross = "IFNULL(SUM(p.gross_salary),0)  gross";
		}
		
		//$payroll_condition = ($_SESSION ['curYear'] == $year1)?substr ( $payrollSal, 0, - 1 ) . "," . substr ( $masterSal, 0, - 1 ) . "," . substr ( $total, 0, - 1 ) . ", " . $miscAllowothers . $others . $miscAllowothers1 . $others1 . $miscAllowothers2 . $others2 . $gross : substr ( $payrollSal, 0, - 1 ) . "," . $miscAllowothers . $others ." SUM(p.gross_salary) gross," .substr ( $payrollSaltot, 0, - 1 ) . "," . $miscAllowotherstot . $otherstot ." SUM(p.gross_salary) gross2" ;
		$payroll_condition = ($_SESSION ['curYear'] == $year1)?$payrollSal . $masterSal. substr ( $total, 0, - 1 ) . ", " . $miscAllowothers . $others . $miscAllowothers1 . $others1 . $miscAllowothers2 . $others2 . $gross : substr ( $payrollSal, 0, - 1 ) . "," . $miscAllowothers . $others ." SUM(p.gross_salary) gross," .substr ( $payrollSaltot, 0, - 1 ) . "," . $miscAllowotherstot . $otherstot ." SUM(p.gross_salary) gross2" ;
		$year_condition = ($_SESSION ['curYear'] == $year1)?"'" . $_SESSION ['curYear'] . "-04-01' AND DATE_FORMAT(STR_TO_DATE('" . $_SESSION ['nextyear_date'] . "','%d/%m/%Y'),'%Y-%m-%d')":'"'.$year1.'-04-01" AND DATE_SUB(DATE_ADD("'.$year1 .'-04-01",INTERVAL 1 YEAR),INTERVAL 1 MONTH)';
		$table_condition = ($_SESSION ['curYear'] == $year1 && $row['status'] !='S')?"INNER JOIN 	payroll_preview_temp  pt on	pt.employee_id=p.employee_id INNER JOIN employee_salary_details  s on pt.employee_id=s.employee_id":"";
				
		$salaryquery = "SELECT w.employee_name,per.employee_pan_no, " . $payroll_condition ." FROM payroll p
		INNER JOIN employee_work_details w
		ON p.employee_id = w.employee_id
		INNER JOIN employee_personal_details per
		ON w.employee_id = per.employee_id  $table_condition
		WHERE p.month_year BETWEEN   $year_condition
		AND p.employee_id='" . $employeeId . "' ";
		
		$itquery = "SELECT 80c_fd_app,80c_tution_app,80c_nsc_app,80c_sukanya_app,80c_inf_app,80c_vpf_app,
		    80c_repa_app,80c_lic_app,80c_sip_app,80c_mut_app,80c_nps_app,80c_elss_app,80c_bonds_app,80ccd1b_nps_app,80ccg_rgess_app,
		    80d_medself_app,80d_medpar_app,80d_prev_app,80dd_med_app,80dd_medsev_app,80ddb_med1_app,80e_edu_app,80g_don1_app,80g_don2_app,80g_don3_app,
		    80g_don4_app,80g_don5_app,80g_don6_app,80tta_sav_app,80u_dis1_app,80u_dis2_app,prev_earnings_app,if(year='".$_SESSION ['financialYear']."',1,0)years
		    FROM  employee_it_declaration WHERE employee_id ='" . $employeeId . "' AND year ='$year' ";
		
		$result = mysqli_query (  $this->conn , $incomequery );
		$result2 = mysqli_query (  $this->conn , $salaryquery );
		$result3 = mysqli_query (  $this->conn , $itquery );
		
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$a_json ['employee_income_tax'] = array ($row);
		}
		
		while ( $row2 = mysqli_fetch_array ( $result2, MYSQLI_ASSOC ) ) {
			$a_json ['employee_salary_details'] = array ($row2);
		}
		
		while ( $row3 = mysqli_fetch_array ( $result3, MYSQLI_ASSOC ) ) {
			$a_json ['employee_it_declaration'] = array ($row3);
		}
		
		return $a_json;
		
	}
	
	function downloadTaxSummary($taxData,$summaryYear,$returnType=null){
		$a_json = array ();
		$a_json = $taxData;
		
		if($returnType)
			$htmlStyle = '<style>
					table,  td {
					   border-collapse: collapse;
					}
					body,pre { font-family: Open Sans, sans-serif; font-size: 9pt; }
					td {
					    padding: 4px;
					     border:0;
					}
					
					table#t011 {border:1px solid #000;}
					table#t011 tr {border-left:1px solid #000;}
					table#t011 td {border-left:1px solid #000;}
                  </style>';
			$allowancesHtml="";
			$html = $htmlStyle;
			$period = ($summaryYear!=$_SESSION['financialYear'])?"01 Apr ".substr ($summaryYear, 0, 4 ) ."- 31 Mar ".(substr ( $summaryYear, 0, 4 ) + 1)."":"01 Apr ".substr ( $_SESSION['financialYear'], 0, 4 ) ." - 31 Mar ".(substr ( $_SESSION['financialYear'], 0, 4 ) + 1)."";
			$assyear =($summaryYear!=$_SESSION['financialYear'])?"".(substr($summaryYear,0,4)+1)." - ".(substr($summaryYear,0,4)+2)."":"".(substr($_SESSION['financialYear'],0,4)+1)."-".(substr($_SESSION['financialYear'],0,4)+2)."";
			$headhtml = "<table style='width:100%;border:1px solid #000;' border='1'>
		<thead><tr><th colspan='4' style='text-align:center'>Form 16 - PART B ( Annexure )</th></tr></thead><tbody>
		<tr><td>Employee</td><td>" . $a_json ['employee_salary_details'][0]['employee_name'] . "</td><td>Period</td>
				<td>".$period."</td></tr>
		<tr><td>Emp.PAN</td><td>" . $a_json ['employee_salary_details'][0]['employee_pan_no'] . "</td><td>Assessment Year</td><td colspan='1'>".$assyear."</td></tr></tbody></table>";
			$html .=$headhtml;
			$tillNowHeader = ($summaryYear!=$_SESSION['financialYear'])?"Apr -Mar (INR)":((date("M",strtotime($_SESSION['current_payroll_month'])) != 'Apr')?"Apr - ".date("M",strtotime('-1 day',strtotime($_SESSION['current_payroll_month'])))." (INR)":"-");
			$projectionheader = ($summaryYear!=$_SESSION['financialYear'])?"":((date("M",strtotime($_SESSION['current_payroll_month'])) != 'Mar')?"".date("M",strtotime($_SESSION['current_payroll_month']))." - Mar (INR)":"Mar (INR)");
			$stmt = "SELECT * FROM  company_pay_structure WHERE type ='A' && display_flag ='1' ";
			$result = mysqli_query (  $this->conn, $stmt );
			//echo json_encode($a_json);
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				$allowancesHtml.= "<tr><td>" . $row ['display_name'] . "</td>
								   <td style='text-align: right;'  class=''>" . $a_json ['employee_salary_details'][0][$row['pay_structure_id']] . "</td>
								   <td  class='' style='text-align: right;'>" . $a_json ['employee_salary_details'][0][$row['pay_structure_id']."1"] . "</td>
								   <td style='text-align: right;' >" . $a_json ['employee_salary_details'][0][$row['pay_structure_id']."2"] . "</td></tr>";
			}
			
			$html .="<h3>Details of Salary paid and any other Income</h3>";
		    $html .="<table class='table'  id='t011' style='width:100%;' border='1'><thead><tr><th>Particulars (i) Benifits Paid</th><th style='text-align:center;'>".$tillNowHeader."</th><th style='text-align:center;'>".$projectionheader."</th><th style='text-align:center;'>Total</th></tr></thead><tbody>";
			$html .=$allowancesHtml;
			
			$html.= "<tr><td>Incentive</td><td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_incentive'] . "</td>
					 <td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_incentive1'] . "</td>
                     <td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_incentive2'] . "</td></tr>";
			$html .="<tr><td>Bonus</td><td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_bonus'] . "</td>
					 <td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_bonus1'] . "</td>
 					 <td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_bonus2'] . "</td></tr>";
			$html .="<tr><td>Others</td><td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_other'] . "</td>
     				 <td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_other1'] . "</td>
					 <td style='text-align: right;'>" . $a_json ['employee_salary_details'][0]['c_other2'] . "</td></tr>";
			$html .="<tr><td>Perquisites</td><td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['perqs'] . "</td><td></td><td></td></tr>";
			$html .="<tr><td>Previous Employer Salary</td><td></td><td></td><td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['prev_earnings_app'] . "</td></tr>";
			$html .="<tr><td style='text-align: right;'>Gross Salary</td><td></td><td></td>
					 <td style='text-align: right;'>" . ROUND($a_json ['employee_salary_details'][0]['gross'].+ $a_json ['employee_income_tax'][0]['perqs'].+$a_json ['employee_income_tax'][0]['prev_earnings_app'],2) ."</td></tr>";
			$html .="<tr><td>( i ) House Property Income</td><td></td>
					 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['house_prop_inc'] . "</td><td></td></tr>
				 <tr><td>( ii ) Income form other Sources</td><td></td><td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['other_income'] . "</td><td></td></tr>";
			$html .="<tr><td style='text-align: right;'>Total Income</td><td></td><td></td>
					 <td style='text-align: right;'>".($a_json ['employee_salary_details'][0]['gross']+ $a_json ['employee_income_tax'][0]['perqs']+$a_json ['employee_income_tax'][0]['prev_earnings_app']+ $a_json ['employee_income_tax'][0]['house_prop_inc']+ $a_json ['employee_income_tax'][0]['other_income'] )."</td></tr>";
			$html .="</tbody></table><pagebreak /><br><br><br><br><br>";
			
			$html .= $headhtml;
			$gross_amt = $a_json ['employee_salary_details'][0]['gross'].+ $a_json ['employee_income_tax'][0]['perqs'].+$a_json ['employee_income_tax'][0]['prev_earnings_app'];
			$gross_amt = sprintf("%.2f",$gross_amt);
			
			$html .="<h3>Details of Tax deducted</h3><table class='table'  id='t011' style='width:100%;' border='1'><thead><tr><th style='text-align: center;'>Details of all deducted</th><th></th><th></th><th></th></tr></thead>";
			$html .="<tbody><tr><td>1 ) Gross Salary</td><td></td><td></td><td></td></tr>
							<tr><td style='padding-left: 45px;'>(a) Salary as per provisions contained in sec 17 (1)</td>
								<td style='text-align: right;'>$gross_amt</td><td></td><td></td></tr>
							<tr><td style='padding-left: 45px;'>(b) Value of perquisites u/s 17(2) ( as per Form No.12BA, whereverapplicable )</td>
								<td style='text-align: right;'>0.00</td><td></td><td></td></tr>
							<tr><td style='padding-left: 45px;'>(c) Profits in lieu of salary under section 17(3) ( as per Form No.12BA,wherever applicable )</td>
								<td style='text-align: right;'>0.00</td><td></td><td></td></tr>";
			
			$sec1_total = $a_json ['employee_salary_details'][0]['gross'].+ $a_json ['employee_income_tax'][0]['perqs'].+$a_json ['employee_income_tax'][0]['prev_earnings_app'];
			$sec1_total = sprintf("%.2f", $sec1_total);
			$html .="<tr><td style='text-align: right;'>Total</td><td></td>
						 <td style='text-align: right;'>$sec1_total</td><td></td></tr>";
			$html .="<tr><td>2 ) Less Allowance to the extent exempt u/s 10</td><td></td><td></td><td></td></tr>
			     	 <tr><td style='padding-left: 45px;'>HRA</td>
					 	 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['exe_hra'] . "</td><td></td><td></td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>LTA</td>
					     <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['exe_lta'] . "</td><td></td><td></td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>Others</td>
					 	 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['exe_oth'] . "</td><td></td><td></td></tr>";
			$sec2_total = $a_json ['employee_income_tax'][0]['exe_hra']+$a_json ['employee_income_tax'][0]['exe_lta']+$a_json ['employee_income_tax'][0]['exe_oth'];
			$sec2_total = sprintf("%.2f", $sec2_total);
			$html .="<tr><td style='text-align: right;'>Total</td><td></td>
						 <td style='text-align: right;'>$sec2_total</td><td></td></tr>";
			$sec3_total = $sec1_total - $sec2_total;
			$sec3_total = sprintf("%.2f", $sec3_total);
			$html .="<tr><td>3 ) Balance (1-2)</td><td></td><td style='text-align: right;'>$sec3_total</td><td></td></tr>";
			$html .="<tr><td>4 ) Deductions</td><td></td><td></td><td></td></tr>
				 	 <tr><td style='padding-left: 45px;'>(a) Entertainment allowance</td>
					 	 <td style='text-align: right;'>0.00</td><td></td><td></td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>(b) Tax on Employment</td>
					 	 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['taxon_employment'] . "</td><td></td><td></td></tr>";
			$sec4_total = $a_json ['employee_income_tax'][0]['taxon_employment'];
			$sec5_total = sprintf("%.2f", $sec3_total - $sec4_total);
			$html .="<tr><td style='text-align: right;'>Total</td><td></td>
						 <td style='text-align: right;'>$sec4_total</td><td></td></tr>";
			$html .="<tr><td>5 ) Income chargable under the head 'salaries' (3-4)</td><td></td><td></td>
						 <td style='text-align: right;'>$sec5_total</td></tr>";
			$any_oth_income =  $a_json ['employee_income_tax'][0]['house_prop_inc']+ $a_json ['employee_income_tax'][0]['other_income'];
			$any_oth_income = sprintf("%.2f",$any_oth_income);
			$html .="<tr><td>6 ) Add any other income reported by the employee</td><td></td>
			 			 <td style='text-align: right;'>$any_oth_income</td><td></td></tr>";
			$sec6_gross_total = sprintf("%.2f",$sec5_total + $any_oth_income);
			$html .="<tr><td>7 ) Gross total income (5+6)</td><td></td><td></td>
						 <td style='text-align: right;'>$sec6_gross_total</td></tr>";
			$html .="<tr><td>8 ) Deduction under Chapter VI-A</td><td>Gross Amount</td><td>Deductible Amount</td><td></td></tr>";
			
			$c80 = $d80 = $e80 = $g80 = $oth ="0.00";
			foreach($a_json ['employee_it_declaration'][0] as $key=>$value){
				if(substr($key,0,3) == '80c'){
					$c80 +=  $value;
				}else{
					if(substr($key,0,3) == '80d'){
						$d80 += $value;
					}else{
						if(substr($key,0,3) == '80e'){
							$e80 += $value;
						}else{
							if(substr($key,0,3) == '80g'){
								$g80 += $value;
							}else{
								if($key !='prev_earnings_app'&& $key !='years'){
									$oth += $value;
								}
							}
						}
					}
				}
			}
			$c80 = sprintf("%.2f",$c80); $d80 = sprintf("%.2f",$d80); $e80 = sprintf("%.2f",$e80); $g80 = sprintf("%.2f",$g80); $oth = sprintf("%.2f",$oth);
			
			$html .="<tr><td style='padding-left: 45px;'>a) section 80C</td>
						 <td style='text-align: right;'>$c80</td>
						 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['ded_80c'] . "</td><td></td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>b) section 80D</td>
						 <td style='text-align: right;'>$d80</td>
						 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['ded_80d'] . "</td><td></td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>c) section 80E</td>
						 <td style='text-align: right;'>$e80</td>
						 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['ded_80e'] . "</td><td></td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>d) section 80G</td>
						 <td style='text-align: right;'>$g80</td>
						 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['ded_80g'] . "</td><td></td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>e)Other Deduction</td>
						 <td style='text-align: right;'>$oth</td>
						 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['ded_other'] . "</td><td></td></tr>";
			$deductable_total = $a_json ['employee_income_tax'][0]['ded_80c']+$a_json ['employee_income_tax'][0]['ded_80d']+$a_json ['employee_income_tax'][0]['ded_80e']+$a_json ['employee_income_tax'][0]['ded_80g']+$a_json ['employee_income_tax'][0]['ded_other'];
			$deductable_total = sprintf("%.2f",$deductable_total);
			$html .="<tr><td>9 ) Aggregate of deductible amount under Chapter VI-A</td><td></td><td></td>
						 <td style='text-align: right;'>$deductable_total</td></tr>";
			$total_income = sprintf("%.2f",$sec6_gross_total - $deductable_total);
			$html .="<tr><td>10 ) Total Income (7-9)</td><td></td><td></td>
						 <td style='text-align: right;'>$total_income</td></tr>";
			$html .="<tr><td>11) Income Tax</td><td></td><td></td><td></td></tr>
				     <tr><td style='padding-left: 45px;'>a ) Tax on total income</td><td></td><td></td>
						 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['tax'] . "</td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>b ) Surcharge @ 10 % (on tax computed at S.No.11)	</td><td></td><td></td>
				 		 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['surcharge'] . "</td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>c ) EC @ 3% (on tax computed at S.No.11)</td><td></td><td></td>
					 	 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['ec'] . "</td></tr>";
			$html .="<tr><td style='padding-left: 45px;'>d ) HC @ 1% (on tax computed at S.No.11)</td><td></td><td></td>
					 	 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['shec'] . "</td></tr>";
			$total_tax = $a_json ['employee_income_tax'][0]['tax']+$a_json ['employee_income_tax'][0]['surcharge']+$a_json ['employee_income_tax'][0]['ec']+$a_json ['employee_income_tax'][0]['shec'];
			$total_tax = sprintf("%.2f", $total_tax);
			$html .="<tr><td>12 ) Tax payable (Aggregate of amount from S.No.11)</td><td></td><td></td>
					 	 <td style='text-align: right;'>$total_tax</td></tr>
					 <tr><td>13 ) Less:Relief under section 89</td><td></td><td></td>
					 	 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['relief'] . "</td></tr>";
			$html .="<tr><td>14 ) Tax Paid</td><td></td><td></td>
					 	 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['tax_paid'] . "</td></tr>
				     <tr><td>15 ) Net tax payable</td><td></td><td></td>
						 <td style='text-align: right;'>" . $a_json ['employee_income_tax'][0]['tax_payable'] . "</td></tr></tbody></table>";
			
			return $html;
		//download PDF	
	}
	
	function ak_img_resize($target, $newcopy, $w, $h, $ext) {
		list ( $w_orig, $h_orig ) = getimagesize ( $target );
		$scale_ratio = $w_orig / $h_orig;
		if (($w / $h) > $scale_ratio) {
			$w = $h * $scale_ratio;
		} else {
			$h = $w / $scale_ratio;
		}
		$img = "";
		$ext = strtolower ( $ext );
		if ($ext == "gif") {
			$img = imagecreatefromgif ( $target );
		} else if ($ext == "png") {
			$img = imagecreatefrompng ( $target );
		} else {
			$img = imagecreatefromjpeg ( $target );
		}
		$tci = imagecreatetruecolor ( $w, $h );
		imagecopyresampled ( $tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig );
		imagejpeg ( $tci, $newcopy, 80 );
	}

	function insertImage($tmpFile, $rename,$employeeId) {
		$fileName = $tmpFile ["name"];
		$fileTmpLoc = $tmpFile ["tmp_name"];
		$fileType = $tmpFile ["type"];
		$fileSize = $tmpFile ["size"];
		$fileExt = substr ( $fileName, strripos ( $fileName, '.' ) );
		$fileErrorMsg=0;

		$moveResult = move_uploaded_file ( $fileTmpLoc, "../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employeeId . "/" . $_SESSION ['financialYear'] . $rename . $fileExt );
		if ($moveResult != true) {
			unlink ( $fileTmpLoc );
			exit ();
		}
		unlink ( file_exists($fileTmpLoc) );
		$target_file = "../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employeeId . "/" . $_SESSION ['financialYear'] . $rename . $fileExt;
		$resized_file = "../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $employeeId . "/" . $_SESSION ['financialYear'] . $rename . $fileExt;
		$wmax = 672;
		$hmax = 1024;
		$this->ak_img_resize ( $target_file, $resized_file, $wmax, $hmax, $fileExt );
		return array ("columname" => $rename,"fileName" => $resized_file);
	}


	function getItempView($inActive,$year) {
		$a_json = array ();
		//$enabled_cond = ($inActive == 0)? "w.enabled = 1":"w.enabled  IN (0,1)";
		$query = "SELECT p.employee_pan_no ,w.employee_id,it.status_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,
			de.department_name,d.designation_name,income.tax_paid,income.tax_payable,(income.tax+income.ec+income.shec) tax, w.enabled,if(it.year='".$_SESSION ['financialYear']."',1,0)years,it.year view_year
			FROM employee_work_details  w
			INNER JOIN employee_it_declaration it on it.employee_id = w.employee_id
			INNER JOIN company_designations d ON
			d.designation_id = w.designation_id
			INNER JOIN employee_income_tax income
			ON it.employee_id =income.employee_id
			INNER JOIN employee_personal_details p ON
			p.employee_id = w.employee_id
			INNER JOIN company_departments de
			ON de.department_id = w.department_id
			WHERE w.enabled!='-1' AND it.year='".$year."' AND income.year= '".$year."' ";
		//echo $query; die();
		$stmt = mysqli_query ( $this->conn, $query) or die(mysqli_error($this->conn));
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}

	function getItDeclarationData($employeeId,$viewyear) {
		$a_json = array ();
		$query = "SELECT it.*,w.employee_name,p.employee_pan_no
		FROM  employee_it_declaration it
		INNER JOIN employee_personal_details  p
		ON p.employee_id=it.employee_id
		INNER JOIN employee_work_details  w
		ON it.employee_id=w.employee_id
		WHERE it.employee_id=w.employee_id AND w.employee_id ='$employeeId' AND it.year='" . $viewyear. "'";
		
		$stmt = mysqli_query ( $this->conn, $query );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}

	/*function getItsummaryData($employeeId,$allowDeducArray,$miscallowDeducArray,$summaryYear) {
		$year= $summaryYear;
		$a_json = array ();
		$year1=substr($year,0,4);
		$incomequery = "SELECT id,employee_id,year,prev_earnings_app,perqs,house_prop_inc,CAST(ROUND(other_income) AS DECIMAL(15,2)) other_income,CAST(ROUND(total_inc) AS DECIMAL(15,2)) total_inc,CAST(ROUND(exe_hra) AS DECIMAL(15,2)) exe_hra,exe_lta,CAST(ROUND(exe_oth) AS DECIMAL(15,2)) exe_oth,
							   yearly_gross,gti,ded_80c,ded_80d,ded_80e,ded_80g,ded_other,CAST(ROUND(taxable_inc) AS DECIMAL(15,2)) taxable_inc,taxon_employment,epf_employee,epf_employer,CAST(CEIL(tax) AS DECIMAL(15,2)) tax,CAST(CEIL(ec) AS DECIMAL(15,2)) ec,CAST(CEIL(shec) AS DECIMAL(15,2)) shec,
							   prev_tax_app,surcharge,CAST(CEIL(tax_paid) AS DECIMAL(15,2)) tax_paid,relief,rebate,CAST(CEIL(old_tax_paid) AS DECIMAL(15,2)) old_tax_paid,CAST(CEIL(tax_payable) AS DECIMAL(15,2)) tax_payable,tds,updated_on,updated_by
						FROM  employee_income_tax WHERE employee_id ='" . $employeeId . "'  AND  year ='$year'  ";
		$payrollSal = $payrollSaltot = "";
		$previewSal = "";
		$masterSal = "";
		$total = "";
		$isNotice = 1;
		$next_month = date ( "Y-m-d", strtotime ( "+1 months", strtotime ( $_SESSION ['current_payroll_month'] ) ) );
		$noticeQuery = "SELECT  n.employee_id
						FROM employee_work_details w
						LEFT JOIN emp_notice_period n
						ON w.employee_id = n.employee_id
						WHERE DATE_FORMAT(n.notice_date,'%m%Y') = DATE_FORMAT('". $_SESSION ['current_payroll_month']."','%m%Y') AND n.employee_id = '$employeeId'";

		$noticeResult = mysqli_query (  $this->conn , $noticeQuery );
		$row = mysqli_fetch_array ( $noticeResult, MYSQLI_ASSOC );
		if($row ==""){
			$isNotice = 0;
		}
		if($isNotice == 0){
			$remainigMOnth = "(TIMESTAMPDIFF(MONTH,'" . $next_month . "', DATE_FORMAT(STR_TO_DATE('" . $_SESSION ['nextyear_date'] . "','%d/%m/%Y'),'%Y-%m-%d'))";
		}else{
			$remainigMOnth = 0;
		}
		foreach ( $allowDeducArray ['A'] as $allow ) {
			$payrollSal .= "IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0) " . $allow ['pay_structure_id'] . ",";
			$payrollSaltot .= "IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0) " . $allow ['pay_structure_id'] . "2,"; // for past year total allowance payments
			if($isNotice == 0){
				$masterSal .= "(s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0))" . $allow ['pay_structure_id'] . "1,";
			}else{
				$masterSal .= "((s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0))" . $allow ['pay_structure_id'] . "1,";
			}
			if($isNotice == 0){
				$total .= "(IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0)+s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0)) " . $allow ['pay_structure_id'] . "2,";
			}else{
				$total .= "((IFNULL(SUM(p." . $allow ['pay_structure_id'] . "),0)+s." . $allow ['pay_structure_id'] . "* $remainigMOnth )+IFNULL(pt.". $allow ['pay_structure_id'] .",0)) " . $allow ['pay_structure_id'] . "2,";
			}
		}

		// miscAllowances and miscDeduction
		$miscAllowpayroll = "";
		$miscAllowPerview = "";
		$miscAllowothers = $miscAllowotherstot = "";
		$miscAllowothers1 ="";
		$miscAllowothers2 ="";
		$others = $others1 = $others2 = $gross = $otherstot = "";
		foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
			if ($miscAllow ['pay_structure_id'] == 'c_incentive' || $miscAllow ['pay_structure_id'] == 'c_bonus') {
				$miscAllowothers .= "IFNULL(SUM(p." . $miscAllow ['pay_structure_id'] . "),0) " . $miscAllow ['pay_structure_id'] . ",";
				$miscAllowotherstot . "IFNULL(SUM(p." . $miscAllow ['pay_structure_id'] . "),0) " . $miscAllow ['pay_structure_id'] . "2,";  // for past year total misc payments
				$miscAllowothers1 .= "IFNULL((pt." . $miscAllow ['pay_structure_id'] . "),0) " . $miscAllow ['pay_structure_id'] . "1,";
				$miscAllowothers2 .= "IFNULL((IFNULL(SUM(p." . $miscAllow ['pay_structure_id'] . "),0)+IFNULL((pt." . $miscAllow ['pay_structure_id'] . "),0)),0)" . $miscAllow ['pay_structure_id'] . "2,";
			}
			if ($miscAllow ['pay_structure_id'] != 'c_incentive' && $miscAllow ['pay_structure_id'] != 'c_bonus') {
				$miscAllowpayroll .= "p." . $miscAllow ['pay_structure_id'] . "+";
				$miscAllowPerview .= "pt." . $miscAllow ['pay_structure_id'] . "+";
			}
		}
		if ($miscAllowpayroll || $miscAllowPerview != null) {
			$others = "IFNULL(SUM(" . substr ( $miscAllowpayroll, 0, - 1 ) . "),0) c_other,";
			$otherstot = "IFNULL(SUM(" . substr ( $miscAllowpayroll, 0, - 1 ) . "),0) c_other2,"; // for past year total misc payments
			$others1 = "IFNULL(SUM(" . substr ( $miscAllowPerview, 0, - 1 ) . "),0) c_other1,";
			$others2 = "IFNULL((IFNULL(SUM(" . substr ( $miscAllowpayroll, 0, - 1 ) . "),0)+IFNULL(SUM(" . substr ( $miscAllowPerview, 0, - 1 ) . "),0)),0) c_other2,";
		}
		if($isNotice == 0){
			$gross .= "(IFNULL(SUM(p.gross_salary),0)+s.employee_salary_amount*$remainigMOnth)+IFNULL(pt.gross_salary,0))  gross";
		}else{
			$gross .= "((IFNULL(SUM(p.gross_salary),0)+s.employee_salary_amount*$remainigMOnth)+IFNULL(pt.gross_salary,0))  gross";
		}
	  
		$payroll_condition = ($_SESSION ['curYear'] == $year1)?substr ( $payrollSal, 0, - 1 ) . "," . substr ( $masterSal, 0, - 1 ) . "," . substr ( $total, 0, - 1 ) . ", " . $miscAllowothers . $others . $miscAllowothers1 . $others1 . $miscAllowothers2 . $others2 . $gross : substr ( $payrollSal, 0, - 1 ) . "," . $miscAllowothers . $others ." SUM(p.gross_salary) gross," .substr ( $payrollSaltot, 0, - 1 ) . "," . $miscAllowotherstot . $otherstot ." SUM(p.gross_salary) gross2" ;
		$year_condition = ($_SESSION ['curYear'] == $year1)?"'" . $_SESSION ['curYear'] . "-04-01' AND DATE_FORMAT(STR_TO_DATE('" . $_SESSION ['nextyear_date'] . "','%d/%m/%Y'),'%Y-%m-%d')":'"'.$year1.'-04-01" AND DATE_SUB(DATE_ADD("'.$year1 .'-04-01",INTERVAL 1 YEAR),INTERVAL 1 MONTH)';
		$table_condition = ($_SESSION ['curYear'] == $year1)?"INNER JOIN 	payroll_preview_temp  pt on	pt.employee_id=p.employee_id INNER JOIN employee_salary_details  s on pt.employee_id=s.employee_id":"";

		$salaryquery = "SELECT w.employee_name, " . $payroll_condition ." FROM payroll p
		INNER JOIN employee_work_details w
		ON p.employee_id = w.employee_id  $table_condition
		WHERE p.month_year BETWEEN   $year_condition
		AND p.employee_id='" . $employeeId . "' ";
		//echo $salaryquery; die();
		$itquery = "SELECT 80c_fd_app,80c_tution_app,80c_nsc_app,80c_sukanya_app,80c_inf_app,80c_vpf_app,
		    80c_repa_app,80c_lic_app,80c_sip_app,80c_mut_app,80c_nps_app,80c_elss_app,80c_bonds_app,80ccd1b_nps_app,80ccg_rgess_app,
		    80d_medself_app,80d_medpar_app,80d_prev_app,80dd_med_app,80dd_medsev_app,80ddb_med1_app,80e_edu_app,80g_don1_app,80g_don2_app,80g_don3_app,
		    80g_don4_app,80g_don5_app,80g_don6_app,80tta_sav_app,80u_dis1_app,80u_dis2_app,prev_earnings_app,if(year='".$_SESSION ['financialYear']."',1,0)years
		    FROM  employee_it_declaration WHERE employee_id ='" . $employeeId . "' AND year ='$year' ";

		$result = mysqli_query (  $this->conn , $incomequery );
		$result2 = mysqli_query (  $this->conn , $salaryquery );
		$result3 = mysqli_query (  $this->conn , $itquery );

		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$a_json ['employee_income_tax'] = array ($row);
		}

		while ( $row2 = mysqli_fetch_array ( $result2, MYSQLI_ASSOC ) ) {
			$a_json ['employee_salary_details'] = array ($row2);
		}

		while ( $row3 = mysqli_fetch_array ( $result3, MYSQLI_ASSOC ) ) {
			$a_json ['employee_it_declaration'] = array ($row3);
		}
		return $a_json;
	} */

	function updateItdeclaration($employeeId,$files,$post,$flag) {
		$queryFile="";$stmt="";
		foreach ( $files as $k => $v ) {
			if (! empty ( $files [$k] ["name"] )) {
				if (is_array ( $v )) {
					$dataSet=$this->insertImage ( $files [$k], $k,$employeeId );
					$queryFile.=$dataSet['columname']."='".$dataSet['fileName']."',";
				}
			}
		}
		foreach ($post as $key => $value ) {
			if($key!='act' && $key!='employee_id' && $key!='FromHrSide'){
				$stmt .= $key . "='" . $value . "',";
			}
		}
		$extraColum=($flag==1)?"status_id='A'":"status_id='P'";
		$previewupdate=($flag==1)?"status_flag='A',":"";
		$queryStmt="UPDATE employee_it_declaration it INNER JOIN payroll_preview_temp p
		ON it.employee_id=p.employee_id SET $previewupdate $queryFile $stmt $extraColum
		WHERE it.employee_id = '$employeeId' AND
		it. year='".$_SESSION ['financialYear']."'";
		return $result =(mysqli_query ( $this->conn, $queryStmt ))?true:explode(':', mysqli_error($this->conn))[0];
	}

}
?>