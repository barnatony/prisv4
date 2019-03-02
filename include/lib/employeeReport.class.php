<?php
/*
 * ----------------------------------------------------------
 * Filename : employeeReport.handle.php
 * Classname: employeeReport.class.php
 * Author : Raja Sundari
 * Database :
 * Oper :
 *
 * ----------------------------------------------------------
 */

class Employeereports {
	/* Member variables */
	var $updated_by;
	var $conn;
	var $title;
	var $name;
	var $title1;
	/* Member functions */
	
	
	function getEmployeesAnnualSalary($empId, $startDate, $endDate) {
		
		Session::newInstance ()->_setGeneralPayParams ();
		$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
		$allowName = "";
		$miscAllowName = "";
		$deduName = "";
		$miscDeddu = "";
		$allowNameOther = "";
		$miscAllowNameOther = "";
		$miscDedduOther = "";
		$deduNameOther = "";
		// $allowFiled="";$miscAllowfield="";$dedufield="";$miscDeddufield="";
		foreach ( $allowDeducArray ['A'] as $allow ) {
			$allowName .= " WHEN 'A_" . $allow ['display_name'] . "' THEN " . $allow ['pay_structure_id'];
			$allowNameOther .= " SELECT 'A_" . $allow ['display_name'] . "' SALARYHEADS UNION ALL";
			
			// $allowFiled.="'".$allow['pay_structure_id']."',";
		}
		
		foreach ( $allowDeducArray ['D'] as $dedu ) {
			$deduName .= " WHEN 'D_" . $dedu ['display_name'] . "' THEN " . $dedu ['pay_structure_id'];
			$deduNameOther .= " SELECT 'D_" . $dedu ['display_name'] . "' UNION ALL";
			// $dedufield.="'".$dedu['pay_structure_id']."',";
		}
		
		// miscAllowances and miscDeduction
		Session::newInstance ()->_setMiscPayParams ();
		$miscallowDeducArray = Session::newInstance ()->_get ( "miscPayParams" );
		
		foreach ( $miscallowDeducArray ['MP'] as $miscAllow ) {
			$miscAllowName .= " WHEN 'B_" . $miscAllow ['display_name'] . "' THEN " . $miscAllow ['pay_structure_id'];
			$miscAllowNameOther .= " SELECT 'B_" . $miscAllow ['display_name'] . "' UNION ALL";
			// $miscAllowfield.="'".$miscAllow['pay_structure_id']."',";
		}
		
		foreach ( $miscallowDeducArray ['MD'] as $miscDedu ) {
			$miscDeddu .= " WHEN 'E_" . $miscDedu ['display_name'] . "' THEN " . $miscDedu ['pay_structure_id'];
			$miscDedduOther .= " SELECT 'E_" . $miscDedu ['display_name'] . "'  UNION ALL";
			// $miscDeddufield.="'".$miscDedu['pay_structure_id']."',";
		}
		
		$a_json = array ();
		$begin = new DateTime( $startDate );
		$end = new DateTime( $endDate );
		$startEnd = "";
		for($i = $begin; $begin <= $end; $i->modify ( '+1 month' )) {
			$startEnd .= "IFNULL(MAX(CASE WHEN month_year = '" . $i->format ( 'Y-m-d' ) . "' THEN value END),'-') `" . strtoupper ( date ( 'M', strtotime ( $i->format ( 'Y-m-d' ) ) ) ) . "`,";
		}
		$stmt= "SELECT SALARYHEADS," . substr ( $startEnd, 0, - 1 ) . "		
			             FROM
						 ( SELECT employee_id,month_year, SALARYHEADS,
		                   CASE SALARYHEADS
				           WHEN 'A_A' THEN IF(lop='-','','') 
						   $allowName
						   $miscAllowName
						   WHEN  'C_Gross' THEN gross_salary
				           WHEN 'D_D' THEN IF(id='-','','') 
						   $deduName
						   $miscDeddu
						   WHEN  'F_TotalDedu' THEN total_deduction
						   WHEN  'G_Net Salary' THEN net_salary
					       END value
						FROM payroll t CROSS JOIN
						(  
				           SELECT 'A_A' SALARYHEADS UNION ALL
				           $allowNameOther
						   $miscAllowNameOther
						   SELECT 'C_Gross'  UNION ALL
				           SELECT 'D_D'  UNION ALL
						   $deduNameOther
						   $miscDedduOther
						   SELECT 'F_TotalDedu'  UNION ALL
						   SELECT 'G_Net Salary' 
		               ) c
						)q
						WHERE  employee_id='$empId' AND month_year BETWEEN '$startDate' AND '$endDate'
								GROUP BY SALARYHEADS
								" ;
		
		/*
		 * ORDER BY FIELD(Payslip,".substr($allowFiled,0,-1).",".substr($miscAllowfield,0,-1).",
		 * 'Gross',".substr($dedufield,0,-1).",".substr($miscDeddufield,0,-1).",'TotalDedu','Net')
		 */
		
		$result = mysqli_query ( $this->conn,$stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
	    return $a_json;
	 
	}
	function getannualSalarystmtpdf($tableData){
		/*what this function does */
		//loop the table Data prepare the html code
		//download it using mpdf
		//company header
		
		if($_REQUEST ['yearBasedon']=='finYear'){
			$year=date('Y',strtotime($this->startDate));
			$year1 =substr($year,2)+1;
			$year =$year.$year1;
			$title.="Annual Salary Statement for the Financial Year of ".$year;
		}else {
			$year=date('Y',strtotime($this->startDate));
			$title.="Annual Salary Statement for the Calender Year of ".$year;
		}
		//$title.="Annual Salary Statement for the " .$_REQUEST ['yearBasedon']." of ".$year;
		$title1.='<table><tr><th style="width:35%;"><td>'.$title.'</td></th></tr></table>';
		$compdetails = array ();
		$stmt ="SELECT c.company_email,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,
                c.company_pin_code FROM company_details c WHERE c.company_id = '" . $_SESSION ['company_id'] . "' AND c.info_flag='A'";
		$result = mysqli_query ( $this->conn,$stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $compdetails, $row );
		}
		
		$html.=$title1;
		$html .= '<div class="reportTable"><table id="annualTable" style="width:100%;" ><thead><tr>';
	
		
		foreach($tableData[2] as $k=>$v) {
			    if($v!='-'  )
				$html .= '<th style="text-align:center;">' .$k. '</th>';
				
		}
		
		  
			$html .= '<th style="text-align:center;">TOTAL</th></tr></thead><tbody>';
		    
			for ($i = 0, $len = count($tableData); $i < $len; ++$i) {
				$header="";
				if($i % 2 == 0){
					$html .= '<tr class="alt">';
				}else{
					$html .= '<tr>';
				}
		       
			   $total=0;
			   
				foreach($tableData[$i] as $k1=>$v1) {
					if($k1=='SALARYHEADS' ){
						if(explode("_",$v1)[1]=='A' ){
							$html .= '<td style="text-align:center;font-weight: bold;">ALLOWANCES</td>';
					        
						}else if(explode("_", $v1)[1]=='D') {
							$html .= '<td style="text-align:center;font-weight: bold;">DEDUCTIONS</td>';
							
						}else if(explode("_", $v1)[1]=='Gross'){
							$html .= '<td style="font-weight:bold;text-align: right;">'.explode("_", $v1)[1].'</td>';
							// header= '<tr><td colspan="13">Deduction</td></tr>';
							
						}else if(explode("_", $v1)[1]=='Net Salary'){
							$html .= '<td style="font-weight: bold;text-align: right;">'.explode("_", $v1)[1].'</td>';
						}else if(explode("_", $v1)[1]=='TotalDedu'){
							$html .= '<td style="font-weight: bold;text-align: right;">'.explode("_", $v1)[1].'</td>';
							
						} else{
							$html .= '<td style="text-align: left;">'.explode("_", $v1)[1].'</td>' .$header;
							
						}
						
					}
					
					else if($v1!='-'  ){
						if($v1 && $v1!=0)
							$html .= '<td style="text-align:right;">' . inr_format($v1) . '</td>';
						else 
							$html .= '<td style="text-align:right;"> - </td>';
						$total+=floatval($v1);
						
					}
		           
				}
				    if($total)
						$html .= "<td style='text-align:right;'>" . inr_format($total) .'</td></tr>';
					else
						$html .= '<td style="text-align:right;"> - </td>';
					
			}
		
			
			 
			$html .= '</tbody></table></div>';
			
			$footer = '<table style="width:100%;"><tr style="background-color: #1957A2" ><td colspan="5" style="text-align:center;color:#FFF">&copy; Powered by  <a style="color:#FFF" href="http://basspris.com"> BASSPRIS </a> -Online Payroll System</td></tr><tr><td colspan="5" style="text-align:right">Page {PAGENO} | {nb}</td></tr></table>';
			include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
			$mpdf = new mPDF ( 'en-GB-x', 'A4-L', '', '', 10, 10, 10, 10, 6, 3,'L' ); // if remove note isset into 20
			$styleSheet = file_get_contents ( dirname ( dirname ( __DIR__ ) ) . "/css/reportTable.css" );
			$header1 .= '<table> <tr>
  		<th style="width:20% "><img src=../' . $compdetails [0] ['company_logo'] . ' style="width:15%"></th>
  		<td style="font-weight: bold;font-size:15px;text-align:left; ">' . ucwords ( $compdetails [0] ['company_name'] ) . '<br>' . ucwords ( $compdetails [0] ['company_build_name'] ) . ',' . ucwords ( $compdetails [0] ['company_street'] ) . ',' . ucwords ( $compdetails [0] ['company_area'] ) . ',<br>' . ucwords ( $compdetails [0] ['company_city'] ) . ',' . $compdetails [0] ['company_pin_code'] . '</td></tr></table>';
			$mpdf->WriteHTML ( $styleSheet, 1 );
			$mpdf->setAutoTopMargin='stretch';// Writing style to pdf
			$mpdf->setHeader ( $header1 );
			$mpdf->setHTMLFooter ( $footer );
			$mpdf->WriteHTML ( $html, 2 );
			$mpdf->Output ( 'Annual Salary_Statement' . $year . '.pdf', D );
			exit ();
			
			
}
        
}
     
?>