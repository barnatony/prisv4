<?php

class payslipDesign {
	/* Member variables */
	var $conn;
	var $allowDeduString;
	var $monthYear;
	/* Member functions */
	/* Update Payslib Customise Using Branch ID */
	function update($leftCustomise, $rightCustomise, $totalColumName, $leaveInfo, $headerFooter,  $colorCode ,$border, $headerColor,$itSummary, $addColumn, $payslip_id) {
		$stmt1="UPDATE payslip_design SET
				clo_left = ?,clo_right = ?,basic_info = ?,leave_info = ?,logo = ?,color_code = ? ,payslip_border=?, color_header=?, is_ItSummary=?, is_mastersalary=? WHERE payslip_id = ?";
		$stmt = mysqli_prepare ( $this->conn, $stmt1);
		mysqli_stmt_bind_param ( $stmt, 'sssssssssss', $leftCustomise, $rightCustomise, $totalColumName, $leaveInfo, $headerFooter,  $colorCode,  $border , $headerColor, $itSummary, $addColumn, $payslip_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function passwordUpdate($payslip_ids, $protect_password) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE payslip_design SET
				protect_password = ? WHERE payslip_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $protect_password, $payslip_ids );
		mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		$result = self::select ( $payslip_ids );
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$result [0] ['protect_password']:mysqli_error ( $this->conn ))
		);
	}

	function select($payslip_id=null) {
		$a_json = array ();
		$str="";
		if($payslip_id)
			$str = "payslip_id ='$payslip_id'";
			else
				$str = "set_active=1";
				$stmt = "SELECT c.company_email,ps.logo,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,c.company_mobile,
				                               c.company_pin_code,clo_left,clo_right,basic_info,leave_info,logo,protect_password,is_mastersalary,is_ItSummary,logo,payslip_name,color_code,color_header,is_ItSummary, is_mastersalary,payslip_border,set_active,note
											   FROM payslip_design  ps INNER JOIN company_details c ON c.company_id = '" .$_SESSION['company_id'] . "' WHERE (".$str.") AND c.info_flag='A'";
				
				$result = mysqli_query ( $this->conn, $stmt );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
					array_push ( $a_json, $row );
				}
				return $a_json;
	}
	
	function selectThemes($payslip_id){
		$json = array ();
		$stmt = "SELECT color_code,payslip_border,color_header,is_mastersalary,is_ItSummary 
		FROM payslip_design WHERE payslip_id='$payslip_id'";
	
		$result = mysqli_query ( $this->conn,$stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$json:mysqli_error ( $this->conn ))
		);
		
	}
	function view($monthYear,$extraCondition,$inActive){
		
		$json = array ();
		$enabled_cond = ($inActive == 0)? "w.enabled = 1":"w.enabled = 0";
		$stmt = "SELECT s.total_deduction,ROUND(s.net_salary,2) net_salary,ROUND(s.gross_salary,2) gross_salary,
		s.employee_id, CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,w.enabled
		FROM employee_work_details w
		INNER JOIN payroll s ON w.employee_id = s.employee_id
		$extraCondition
		AND s.month_year='$monthYear' order by employee_name;";
		
		$result = mysqli_query ( $this->conn,$stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$json:mysqli_error ( $this->conn ))
		);
	}

	function viewPayslips(){

		$json = array();
		$result = mysqli_query($this->conn,"SELECT * FROM payslip_design;");

		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$json:mysqli_error ( $this->conn ))
		);

	}

	function getEmployeePayrollData($employeeId,$monthYear){
		$_SESSION ['payrollDate']=$monthYear;
		$json = array ();

		$masterstring = "";
		if($this->masterString!= ""){
			$masterArray = explode(',',"sal.".str_replace(',',',sal.', substr($this->masterString,0,-1)));
			$aliasArray = explode(',',(str_replace(',','1,',substr($this->masterString,0,-1))).'1');
			$temp = array_combine($masterArray,$aliasArray);

			foreach($temp as $key=>$val) // Loop though one array
				$masterstring.= $key.' ' .$val.','; // combine 'em
		}
		
		$payrollstring = 's.'.str_replace(',',',s.',substr($this->allowDeduString,0,-1));
		
		$salaryHistoryQuery = "SELECT employee_id FROM employee_salary_details_history 
							   WHERE employee_id='$employeeId' AND '$monthYear' BETWEEN effects_from AND effects_upto;";
		$result = mysqli_query ( $this->conn, $salaryHistoryQuery);
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		
		$histCond = $whereCond = "";
		if($row){
			$histCond ="_history";
			$whereCond = "WHERE '$monthYear' BETWEEN effects_from AND effects_upto";
		}else{
			$salEffectsCheck = "SELECT w.employee_doj,sal.effects_from,his.effects_from hist_effectsFrom
								   FROM employee_work_details w
								   INNER JOIN employee_salary_details sal
								   ON w.employee_id = sal.employee_id
								   LEFT JOIN employee_salary_details_history his
								   ON w.employee_id = his.employee_id
								   WHERE w.employee_id='$employeeId' ORDER BY his.effects_from LIMIT 0,1;";
			$result = mysqli_query ( $this->conn, $salEffectsCheck);
			$row1 = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		}
		//print($row1['effects_from']); die();
		$masterstring1 =''; $cond = 'sal.employee_salary_amount';
		if(!$row){
			if($row1['employee_doj'] != $row1['effects_from'] && $row1['employee_doj'] != $row1['hist_effectsFrom']){
				
				foreach($aliasArray as $val) // Loop though one array
					$masterstring1.= "'-' " .$val.",";
			
			$masterstring = $masterstring1;
			$cond = "'-' ";
			}
		}
		
		$array = $this->allowDeduString."gross_salary,total_deduction,net_salary";
		$alw_array = explode(',',$array);
		$YTDstring = $currentmonth= "";
		if(date('m', strtotime($monthYear))<'04'){
			$st_limit = date('Y-04-d', strtotime($monthYear));
			$st_limit = date("Y-m-d", strtotime("-1 year", strtotime($st_limit)));
		}else 
			$st_limit = date('Y-04-d', strtotime($monthYear));
		
		foreach($alw_array as $key){ // Loop though one array
			$YTDstring.= "SUM(CASE WHEN month_year BETWEEN '".$st_limit."' AND '".$monthYear."' THEN ".$key. " END) " .$key."2,";
			$currentmonth.="SUM(CASE WHEN month_year ='".$monthYear."' THEN ".$key. " END) " .$key.",";
		}
		$currentmonth.= "'".$monthYear."' month_year,SUM(CASE WHEN month_year ='".$monthYear."' THEN lop+alop END) lop,SUM(CASE WHEN month_year ='".$monthYear."' THEN late_lop END) late_lop,SUM(CASE WHEN month_year ='".$monthYear."' THEN worked_days END) worked_days,SUM(CASE WHEN month_year ='".$monthYear."' THEN inc_arrear END) inc_arrear,";
		
		$query ="SELECT s.inc_arrear,s.month_year, w.employee_id ,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,$cond gross_salary1,s.gross_salary gross_salary,s.total_deduction,s.net_salary net_salary,dep.department_name,
						 		 p.employee_bank_branch,DATE_FORMAT(w.employee_doj, '%d/%m/%Y') as employee_doj,js.status_name,des.designation_name,
							     DATE_FORMAT(p.employee_dob, '%d/%m/%Y') as employee_dob,p.employee_phone , p.employee_mobile ,p.employee_email,
     		                     p.employee_pan_no ,p.employee_aadhaar_id,p.employee_bank_name ,p.employee_acc_no ,p.employee_bank_ifsc ,br.branch_name,
     		                     w.employee_emp_pf_no,w.employee_emp_uan_no,w.employee_emp_esi_no ,".$masterstring.$payrollstring. ",ROUND(s.lop,1) lop,s.late_lop,s.worked_days,ps.note
     		                     FROM
     		                     employee_work_details w
     		                     INNER JOIN employee_personal_details p
     		                     ON w.employee_id = p.employee_id
     		                     INNER JOIN employee_salary_details$histCond sal
     		                     ON p.employee_id = sal.employee_id
     		                     INNER JOIN payroll s
     		                     ON w.employee_id = s.employee_id
     		                     INNER JOIN company_designations des
     		                     ON w.designation_id = des.designation_id
     		                     INNER JOIN company_departments dep
     		                     ON w.department_id = dep.department_id
     		                     INNER JOIN company_branch br
     		                     ON w.branch_id = br.branch_id
     		                     LEFT JOIN company_payment_modes pm
     		                     ON w.payment_mode_id = pm.payment_mode_id
     		                     INNER JOIN company_job_statuses js
     		                     ON w.status_id = js.status_id
     		                     AND w.employee_id =s.employee_id
     		                     LEFT JOIN employee_work_details man ON w.employee_reporting_person= man.employee_id
     		                     INNER JOIN payslip_design  ps ON ps.set_active = 1
     		                     INNER JOIN company_details c
     		                     ON c.company_id ='$this->company_id' and s.month_year='$monthYear' AND s.employee_id='$employeeId' 
								 $whereCond;";
	
		$query2 = "SELECT employee_id,employee_name,department_name,employee_bank_branch,employee_doj,status_name,designation_name,
						  employee_dob,employee_phone,employee_mobile,employee_email,employee_pan_no,employee_aadhaar_id,employee_bank_name,
						  employee_acc_no,employee_bank_ifsc,branch_name,employee_emp_pf_no,employee_emp_uan_no,employee_emp_esi_no,"
						  .$currentmonth.str_replace(',','1, ',$this->masterString).$YTDstring." gross_salary1,inc_arrear inc_arrear2,note
				 FROM (
						SELECT s.inc_arrear,s.month_year, w.employee_id ,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,$cond gross_salary1,s.gross_salary gross_salary,s.total_deduction,s.net_salary net_salary,dep.department_name,
						 		 p.employee_bank_branch,DATE_FORMAT(w.employee_doj, '%d/%m/%Y') as employee_doj,js.status_name,des.designation_name,
							     DATE_FORMAT(p.employee_dob, '%d/%m/%Y') as employee_dob,p.employee_phone , p.employee_mobile ,p.employee_email,
     		                     p.employee_pan_no ,p.employee_aadhaar_id,p.employee_bank_name ,p.employee_acc_no ,p.employee_bank_ifsc ,br.branch_name,
     		                     w.employee_emp_pf_no,w.employee_emp_uan_no,w.employee_emp_esi_no ,".$masterstring.$payrollstring. ",ROUND(s.lop,1) lop,alop,s.late_lop,s.worked_days,ps.note
     		            FROM employee_work_details w
     		            INNER JOIN employee_personal_details p
     		            ON w.employee_id = p.employee_id
     		            INNER JOIN employee_salary_details$histCond sal
     		            ON p.employee_id = sal.employee_id
     		            INNER JOIN payroll s
     		            ON w.employee_id = s.employee_id
     		            INNER JOIN company_designations des
     		            ON w.designation_id = des.designation_id
     		            INNER JOIN company_departments dep
     		            ON w.department_id = dep.department_id
     		            INNER JOIN company_branch br
     		            ON w.branch_id = br.branch_id
     		            LEFT JOIN company_payment_modes pm
     		            ON w.payment_mode_id = pm.payment_mode_id
     		            INNER JOIN company_job_statuses js
     		            ON w.status_id = js.status_id
     		            AND w.employee_id =s.employee_id
     		            LEFT JOIN employee_work_details man ON w.employee_reporting_person= man.employee_id
     		            INNER JOIN payslip_design  ps ON ps.set_active = 1
     		            INNER JOIN company_details c
     		            ON c.company_id ='$this->company_id' AND s.month_year BETWEEN '$st_limit' AND '$monthYear' AND s.employee_id='$employeeId' 
     		            $whereCond ORDER BY s.month_year DESC)t;";
		//echo $query2; die();
		$result = mysqli_query ( $this->conn, $query2);
		while($row = mysqli_fetch_array ( $result, MYSQLI_ASSOC )){
			$queue = array (
					"words" => ucfirst ( Session::newInstance ()->convert_number_to_words ( $row ['net_salary'] ) . " Only " ),
					"net" => $row ['net_salary']
			);
			array_push ( $json, $row );
			array_push ( $json, $queue );
		}
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$json:mysqli_error ( $this->conn ))
		);
	}

	function setActive($payslip_id,$setActive){
		$stmt1 = "UPDATE payslip_design SET set_active = 0;";
		$result1 = mysqli_query( $this->conn,$stmt1);

		$stmt = mysqli_prepare ( $this->conn, "UPDATE payslip_design SET set_active = ?  WHERE payslip_id =  ?" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $setActive,$payslip_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;

	}


}
?>