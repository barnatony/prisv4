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
		$columns = "Member ID,Member Name,EPF Wages,EPS Wages,EPF Contribution (EE Share) due,EPF Contribution (EE Share) being remitted,EPS Contribution due,EPS Contribution being remitted,Diff EPF and EPS Contribution (ER Share) due,Diff EPF and EPS Contribution (ER Share) being Remitted,NCP Days,Refund of Advances,Arrear EPF Wages,Arrear EPF EE Share,Arrear EPF ER Share,Arrear EPS Share,Father's /Husbend's Name,Relationship with the Member,Date Of Birth,Gender,Date of Joining EPF,Date of Joining EPS,Date of Exit from EPF,Date of Exit from EPS,Reason for leaving";
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
		if ($both_contrib == 0) {
			$stmt = "SELECT SUM(CASE WHEN (({$pColumns})+IFNULL({$aColumns},0))>15000 AND s.pf_limit= 1 THEN 15000 WHEN s.pf_limit = -1 THEN 0
			ELSE ROUND(({$pColumns})+IFNULL({$aColumns},0)) END)AS epf_wage,
			SUM(CASE WHEN (({$pColumns})+IFNULL({$aColumns},0))>15000 AND s.pf_limit= 1 THEN 15000 WHEN s.pf_limit = -1 THEN 0
			ELSE ROUND(({$pColumns})+IFNULL({$aColumns},0)) END) AS eps_wage,
			ROUND(SUM(p.c_epf)) as epf_contribution_due,
			ROUND(SUM(p.c_epf)) as epf_contribution_remitted,
			SUM(CASE WHEN (({$pColumns})+IFNULL({$aColumns},0))>15000 AND s.pf_limit= 1 THEN ROUND(15000*0.0833)
			WHEN s.pf_limit = -1 THEN 0 ELSE ROUND((({$pColumns})+IFNULL({$aColumns},0))*0.0833) END) AS eps_contribution_due,
			SUM(CASE WHEN (({$pColumns})+IFNULL({$aColumns},0))>15000 AND s.pf_limit= 1 THEN ROUND(15000*0.0833)
			WHEN s.pf_limit = -1 THEN 0 ELSE ROUND((({$pColumns})+IFNULL({$aColumns},0))*0.0833) END) AS  eps_contribution_remitted,
			SUM(CASE WHEN (({$pColumns})+IFNULL({$aColumns},0))>15000 AND s.pf_limit= 1 THEN ROUND((p.c_epf) - ROUND(15000*0.0833))
			WHEN s.pf_limit = -1 THEN 0 ELSE (ROUND(p.c_epf) - ROUND((({$pColumns})+IFNULL({$aColumns},0))*0.0833)) END) AS eps_epf_diff_due ,
			SUM(CASE WHEN (({$pColumns})+IFNULL({$aColumns},0))>15000 AND s.pf_limit= 1 THEN ROUND((p.c_epf) - ROUND(15000*0.0833))
			WHEN s.pf_limit = -1 THEN 0 ELSE (ROUND(p.c_epf) - ROUND((({$pColumns})+IFNULL({$aColumns},0))*0.0833)) END) AS eps_epf_diff_remitted
			FROM payroll p
			INNER JOIN employee_salary_details s
			ON p.employee_id = s.employee_id
			INNER JOIN employee_personal_details per
			ON p.employee_id = per.employee_id
			INNER JOIN employee_work_details w
			ON p.employee_id = w.employee_id
			INNER JOIN company_branch cb
      		ON w.branch_id = cb.branch_id
			LEFT JOIN  arrears a
			ON p.employee_id = a.employee_id
			WHERE cb.pf_no = '$pf_no' AND DATE_FORMAT(p.month_year, '%m%Y') = '{$monthYear}' AND s.pf_limit !=-1 $extraCondition;";
			$result = mysqli_query ( $this->conn, $stmt);
		} elseif ($both_contrib == 1) {
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
			INNER JOIN company_branch cb
      		ON w.branch_id = cb.branch_id
			LEFT JOIN arrears a
			ON p.employee_id = a.employee_id
			WHERE  cb.pf_no = '$pf_no' AND DATE_FORMAT(p.month_year, '%m%Y') = '{$monthYear}' AND s.pf_limit !=-1 $extraCondition;";
			$result = mysqli_query ( $this->conn, $stmt );
		}
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}

	function generateEpfReportData($monthYear,$pf_no) {
		$queryStmt = "SELECT employee_emp_pf_no,employee_name,epf_wages,epf_wages AS eps_wages,ROUND(epf_wages*employee_share/100) AS epf_contribution_due,
		ROUND(epf_wages*employee_share/100) AS epf_contribution_remitted,ROUND(epf_wages*0.0833) AS eps_contribution_due,
		ROUND(epf_wages*0.0833) AS eps_contribution_remitted,(ROUND(epf_wages*employee_share/100)-ROUND(epf_wages*0.0833)) AS eps_epf_diff_due,
		(ROUND(epf_wages*employee_share/100)-ROUND(epf_wages*0.0833)) AS eps_epf_diff_remitted,ncp_days,refund_advances,arrear_epf_wage,arrear_epf_ee_share,
		arrear_epf_er_share,arrear_eps_share,father_name,relationship,dob,gender,date_of_join_eps,date_of_join_epf,
		date_of_exit_epf,date_of_exit_eps,reason_for_leaving
		FROM (
		SELECT  wd.employee_emp_pf_no, CONCAT(p.employee_name,' ',wd.employee_lastname) employee_name,SUBSTRING_INDEX(cd.employee_share,'|',1) employee_share,
		SUBSTRING_INDEX(cd.employer_share,'|',1) employer_share,SUBSTRING_INDEX(cd.admin_charges,'|',1) admin_charges,
		(CASE WHEN cd.is_both_contribution = 1 AND cd.is_admin_charges = 0 THEN ROUND(((cd.employee_share/(cd.employee_share+cd.employer_share))*p.c_epf)*(100/cd.employee_share))
		WHEN cd.is_both_contribution = 1 AND cd.is_admin_charges = 1 THEN ROUND(((cd.employee_share/(cd.employee_share+cd.employer_share+cd.admin_charges ))*p.c_epf)*(100/cd.employee_share))
		WHEN cd.is_both_contribution = 0 AND cd.is_admin_charges = 1 THEN ROUND(((cd.employee_share/(cd.employee_share+cd.admin_charges ))*p.c_epf)*(100/cd.employee_share))
		ELSE ROUND(p.c_epf*(100/cd.employee_share))
		END) epf_wages,0 as ncp_days,'' as refund_advances,
		IFNULL(a.c_epf,'') AS arrear_epf_wage,
		IFNULL(((a.c_epf)*cd.employee_share/100),'') as arrear_epf_ee_share,
		IFNULL((a.c_epf*cd.employer_share/100)*(86/100),'') as arrear_epf_er_share,
		IFNULL((a.c_epf*cd.employer_share/100)*(14/100),'') as arrear_eps_share,
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
		INNER JOIN employee_work_details wd
		ON p.employee_id = wd.employee_id
		INNER JOIN employee_personal_details per
		ON p.employee_id = per.employee_id
		INNER JOIN employee_salary_details s
		ON p.employee_id = s.employee_id
		INNER JOIN company_branch cb
        ON wd.branch_id = cb.branch_id
		LEFT JOIN arrears a
		ON p.employee_id = a.employee_id
		INNER JOIN company_deductions cd
		ON cd.deduction_id = 'c_epf'
		WHERE  cb.pf_no = '$pf_no' AND DATE_FORMAT(p.month_year, '%m%Y') = '{$monthYear}' AND s.pf_limit !=-1 AND p.c_epf !=0 )t ORDER BY employee_emp_pf_no;";
		$result = mysqli_query ( $this->conn, $queryStmt );
		if ($flag == 0) {
				
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_BOTH ) ) {
				array_push ( $this->tableData, $row );
			}
		} else {
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $this->tableData, $row );
			}
		}
	}
}
?>