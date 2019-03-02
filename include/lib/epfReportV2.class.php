<?php
/*
 * ----------------------------------------------------------
 * Filename : epfReport.class.php
 * Author : Rufus Jackson
 * Database : payroll,..
 * Oper :
 *
 * ----------------------------------------------------------
 */
error_reporting ( 0 );
class EpfReport {
	/* Member variables */
	var $caption;
	var $duration;
	var $tableHeaders = array ();
	var $tableData = array ();
	protected $conn;
	function __construct($conn) {
		$this->conn = $conn;
		$columns = "Member ID,Member Name,GROSS Wages,EPF Wages,EPS Wages,EDLI Wages,EPF CONTRI REMITTED,EPS CONTRI REMITTED,EPF EPS DIFF REMITTED,NCP Days,Refund of Advances";
        /* PMRPY,PMPRPY,Posting_loc_of_member,Father's /Husbend's Name,Relationship with the Member,Date Of Birth,Gender,Date of Joining EPF,Date of Joining EPS,Date of Exit from EPF,Date of Exit from EPS,Reason for leaving"; */
		$this->tableHeaders = explode ( ",", $columns );
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	/* Member functions */
	protected function getDeductionHeads() {
		$queryStmt = "SELECT deduce_in,is_both_contribution,max_employee_share FROM company_deductions WHERE deduction_id='c_epf'";
		$result = mysqli_query ( $this->conn, $queryStmt );
		
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		
		$arraySet [] = $row ['deduce_in'];
		$arraySet [] = $row ['is_both_contribution'];
		$arraySet [] = $row ['max_employee_share'];
		return $arraySet;
	}
	function generateEpfReportChallan($monthYear,$pf_no) {
		$a_json = array ();
		$deducArray = $this->getDeductionHeads ();
		
		$payHeads = current ( $deducArray );
		$both_contrib = next ( $deducArray );
		$max_employee_share = end ( $deducArray );
		$payHeadsArray = explode ( ',', $payHeads );
		$pColumns = "";
		$sColumns = "";
		foreach ( $payHeadsArray as $val ) {
			$pColumns .= "p.{$val} +";
			$aColumns .= "a.{$val} +";
		}
		$pColumns = rtrim ( $pColumns, "+" );
		$aColumns = rtrim ( $aColumns, "+" );
		$max_employee_share = end ( $deducArray );
		$extraCondition = (date ( "mY", strtotime ( $_SESSION ['current_payroll_month'] ) ) == $monthYear) ? 'AND
		(SELECT COUNT(*) FROM payroll_preview_temp where is_processed=0) = 0' : " ";
		//if ($both_contrib == 0) {
		$stmt = "SELECT COUNT(UAN) emp_count,employee_name,SUM(GROSS),SUM(epf_wages) EPF,SUM(IF(epf_wages>15000,15000,epf_wages)) EPS,SUM(IF(epf_wages>15000,15000,epf_wages)) EDLI,
					SUM(EE) EE_Remitted,SUM(ROUND((IF(epf_wages>15000,15000,epf_wages))*.0833)) EPS_Remitted,SUM((EE - ROUND((IF(epf_wages>15000,15000,epf_wages))*.0833))) ER_Remitted
				FROM (
					 SELECT wd.employee_emp_uan_no UAN, CONCAT(p.employee_name,' ',wd.employee_lastname) employee_name,
					 ROUND(p.gross_salary) GROSS,FLOOR(IF(s.pf_limit=1 && $pColumns >15000,15000,($pColumns))) epf_wages,ROUND(b.c_epf_emp_share) EE
					 FROM payroll p
					 INNER JOIN payroll_deduction_breakup b
					 ON p.employee_id = b.employee_id
					 INNER JOIN employee_work_details wd
					 ON p.employee_id = wd.employee_id
					 INNER JOIN company_branch cb
        			 ON wd.branch_id = cb.branch_id
					 INNER JOIN employee_personal_details per
					 ON p.employee_id = per.employee_id
					 INNER JOIN employee_salary_details s
					 ON p.employee_id = s.employee_id
					 INNER JOIN company_deductions cd
					 ON cd.deduction_id = 'c_epf'
					 WHERE DATE_FORMAT(b.month_year, '%m%Y') = '{$monthYear}' AND DATE_FORMAT(p.month_year, '%m%Y') = '{$monthYear}' 
					 AND cb.pf_no = '$pf_no' AND s.pf_limit !=-1 ) z WHERE EE != 0;          ";
		// echo $stmt;
		 
		 $result = mysqli_query ( $this->conn, $stmt);
		
		 
		/*} elseif ($both_contrib == 1) {
		    $stmt = "SELECT SUM(CASE WHEN (({$pColumns} )+IFNULL(({$aColumns}),0))>15000 AND s.pf_limit= 1 THEN 15000 
	                          WHEN s.pf_limit = -1 THEN 0 
	                          ELSE ROUND(({$pColumns} )+IFNULL(({$aColumns}),0)) END)AS epf_wage,
				     SUM(CASE WHEN (({$pColumns} )+IFNULL(({$aColumns}),0))>15000 AND s.pf_limit= 1 THEN 15000 
	                          WHEN s.pf_limit = -1 THEN 0 
	                          ELSE ROUND(({$pColumns} )+IFNULL(({$aColumns}),0)) END) AS eps_wage, 
				     ROUND(SUM(p.c_epf/2)) as epf_contribution_due,
				     ROUND(SUM(p.c_epf/2)) as epf_contribution_remitted, 
	                 SUM(CASE WHEN (({$pColumns} )+IFNULL({$aColumns} ,0))>15000 AND s.pf_limit= 1 THEN ROUND(15000*0.0833)
	                          WHEN s.pf_limit = -1 THEN 0 
	                          ELSE ROUND((({$pColumns} )+IFNULL(({$aColumns}) ,0))*0.0833) END) AS eps_contribution_due,
	                 SUM(CASE WHEN (({$pColumns} )+IFNULL(({$aColumns}),0))>15000 AND s.pf_limit= 1 THEN ROUND(15000*0.08333)
	                          WHEN s.pf_limit = -1 THEN 0 
	                          ELSE ROUND(({$pColumns} )+IFNULL(({$aColumns}),0)*0.0833) END) AS  eps_contribution_remitted, 
			         SUM(CASE WHEN (({$pColumns} )+IFNULL(({$aColumns} ),0))>15000 AND s.pf_limit= 1 THEN ROUND((p.c_epf/2) - (15000*0.0833))
	                          WHEN s.pf_limit = -1 THEN 0 
	                          ELSE (ROUND(p.c_epf/2) - ROUND((({$pColumns} )+IFNULL(({$aColumns}),0))*0.0833)) END) AS eps_epf_diff_due ,
	                 SUM(CASE WHEN (({$pColumns} )+IFNULL(({$aColumns}),0))>15000 AND s.pf_limit= 1 THEN ROUND((p.c_epf/2) - (15000*0.0833))
	                          WHEN s.pf_limit = -1 THEN 0 
	                          ELSE (ROUND(p.c_epf/2) - ROUND((({$pColumns})+IFNULL(({$aColumns}) ,0))*0.0833)) END) AS eps_epf_diff_remitted
	  			FROM payroll p
	  			INNER JOIN employee_salary_details s
	  			ON p.employee_id = s.employee_id
				INNER JOIN employee_personal_details per
				ON p.employee_id = per.employee_id
				INNER JOIN employee_work_details w
				ON p.employee_id = w.employee_id
				LEFT JOIN arrears a
				ON p.employee_id = a.employee_id
				WHERE DATE_FORMAT(p.month_year, '%m%Y') = '{$monthYear}' AND s.pf_limit !=-1 $extraCondition;";
		    
			$result = mysqli_query ( $this->conn, $stmt );
		}*/
		
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	function generateEpfReportData($monthYear,$pf_no) {
		$deducArray = $this->getDeductionHeads ();
		
		$payHeads = current ( $deducArray );
		$both_contrib = next ( $deducArray );
		$max_employee_share = end ( $deducArray );
		$payHeadsArray = explode ( ',', $payHeads );
		$pColumns = "";
		foreach ( $payHeadsArray as $val ) {
			$pColumns .= "p.{$val} +";
		}
		$pColumns = rtrim ( $pColumns, "+" );
		
	
	$queryStmt = "SELECT CONCAT(UAN,'_',employee_id) UAN,employee_name,GROSS,epf_wages EPF,IF(epf_wages>15000,15000,epf_wages) EPS,IF(epf_wages>15000,15000,epf_wages) EDLI,EE EE_Remitted,
				       ROUND((IF(epf_wages>15000,15000,epf_wages))*.0833) EPS_Remitted,(EE-ROUND((IF(epf_wages>15000,15000,epf_wages))*.0833)) ER_Remitted,ncp_days,refund_advances
				 FROM (
				 SELECT wd.employee_emp_uan_no UAN,wd.employee_id,IF(wd.employee_lastname!='',CONCAT(p.employee_name,' ',wd.employee_lastname),p.employee_name) employee_name,
				 ROUND(p.gross_salary) GROSS,FLOOR(IF(s.pf_limit=1 && $pColumns >15000,15000,($pColumns))) epf_wages,
          			  ROUND(b.c_epf_emp_share) EE,(p.lop+p.alop) as ncp_days,0 as refund_advances,
					'-' as PMRPY,'-' as PMPRPY,'NA' Posting_loc_of_member,
					 (CASE WHEN DATE_FORMAT(wd.employee_doj, '%m%Y') = '{$monthYear}' THEN per.employee_father_name
					      ELSE '' END)as father_name,
					 (CASE WHEN DATE_FORMAT(wd.employee_doj, '%m%Y') = '{$monthYear}' THEN 'F'
					      ELSE '' END) as relationship,
					 (CASE WHEN DATE_FORMAT(wd.employee_doj, '%m%Y') = '{$monthYear}' THEN DATE_FORMAT(per.employee_dob,'%d/%m/%Y')
					      ELSE '' END) as dob,
					 (CASE WHEN DATE_FORMAT(wd.employee_doj, '%m%Y') = '{$monthYear}' THEN (CASE WHEN per.employee_gender = 'Male' THEN 'M'
					       WHEN per.employee_gender = 'Female' THEN 'F'
					       ELSE 'T' END) ELSE '' END) as gender,
					  (CASE WHEN DATE_FORMAT(wd.employee_doj, '%m%Y') = '{$monthYear}' THEN DATE_FORMAT(wd.employee_doj,'%d/%m/%Y')
					      ELSE '' END) as date_of_join_eps,
					  (CASE WHEN DATE_FORMAT(wd.employee_doj, '%m%Y') = '{$monthYear}' THEN DATE_FORMAT(wd.employee_doj,'%d/%m/%Y')
					      ELSE '' END) as date_of_join_epf,
					  (CASE WHEN DATE_FORMAT(wd.dateofexit, '%m%Y') = '{$monthYear}' THEN DATE_FORMAT(wd.dateofexit ,'%d/%m/%Y')
					      ELSE '' END) as date_of_exit_epf,
					  (CASE WHEN DATE_FORMAT(wd.dateofexit, '%m%Y') = '{$monthYear}' THEN DATE_FORMAT(wd.dateofexit ,'%d/%m/%Y')
					      ELSE '' END) as date_of_exit_eps,
					  (CASE WHEN DATE_FORMAT(wd.dateofexit, '%m%Y') = '{$monthYear}' THEN 'C'
					      ELSE '' END)  as reason_for_leaving
				FROM payroll p
		        INNER JOIN payroll_deduction_breakup b
		        ON p.employee_id = b.employee_id
				INNER JOIN employee_work_details wd
				ON p.employee_id = wd.employee_id
				INNER JOIN company_branch cb
        		ON wd.branch_id = cb.branch_id
				INNER JOIN employee_personal_details per
				ON p.employee_id = per.employee_id
				INNER JOIN employee_salary_details s
				ON p.employee_id = s.employee_id
				INNER JOIN company_deductions cd
				ON cd.deduction_id = 'c_epf'
				WHERE DATE_FORMAT(b.month_year, '%m%Y') = '{$monthYear}' AND DATE_FORMAT(p.month_year, '%m%Y') = '{$monthYear}' 
				AND cb.pf_no = '$pf_no' AND s.pf_limit !=-1 ) z  ORDER BY employee_name;";
		//echo $queryStmt;
	
		$result = mysqli_query ( $this->conn, $queryStmt );
		/*if ($flag == 0) {
			
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_BOTH ) ) {
				array_push ( $this->tableData, $row );
			}
		} else  { */
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $this->tableData, $row );
			}
		//}
	}
}
?>