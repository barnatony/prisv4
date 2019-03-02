<?php
/*
 * ----------------------------------------------------------
* Filename : tdsReport.class.php
* Author : Rufus Jackson
* Database : payroll,..
* Oper :
*
* ----------------------------------------------------------
*/
class TdsReport {
	/* Member variables */
	var $caption;
	var $duration;
	var $tableHeaders = array ();
	var $tableData = array ();
	protected $conn;
	function __construct($conn) {
		$this->conn = $conn;
		$columns = "Sl No, Employee Code, Name, PAN, Gross, Tax, EduChess, Hr.EduChess,Total";
		$this->tableHeaders = explode ( ",", $columns );
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	/* Member functions */
	// tds ,monthyear
	function generateTdsData($tdsColumn, $month_year, $tdsNo) {
		$extraCondition = ($_SESSION ['current_payroll_month'] == $month_year) ? 'AND (SELECT COUNT(*) FROM payroll_preview_temp where is_processed=0) = 0 ' : "";

		$queryStmt = "SELECT  @a:=@a+1 sno,p.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,
		per.employee_pan_no,p.gross_salary,(p.{$tdsColumn} - ROUND(p.{$tdsColumn}*(3/103),2)) as income_tax,
		ROUND(p.{$tdsColumn}*(2/103),2) as edu_chess,ROUND(p.{$tdsColumn}*(1/103),2) as hr_edu_chess,
		p.{$tdsColumn} as total FROM payroll p
		INNER JOIN employee_personal_details per
		ON p.employee_id = per.employee_id
		INNER JOIN employee_work_details w
		ON per.employee_id = w.employee_id
		INNER JOIN company_branch cb
        ON w.branch_id = cb.branch_id
		,(SELECT @a:= 0) AS a
		WHERE cb.tan_no = '$tdsNo' AND DATE_FORMAT(p.month_year,'%Y-%m-%d') = '{$month_year}'
		AND p.{$tdsColumn} >0 $extraCondition";
		//echo $queryStmt;
		//die();

		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $this->tableData, $row );
		}
	}
}
?>