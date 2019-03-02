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
class PtReport {
	/* Member variables */
	var $caption;
	var $duration;
	var $tableHeaders = array ();
	var $tableData = array ();
	protected $conn;
	function __construct($conn) {
		$this->conn = $conn;
		$columns = "Sl No, Employee Code, Name,Branch Name,PT City, Gross Salary, Professional Tax";
		$this->tableHeaders = explode ( ",", $columns );
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	/* Member functions */
	// tds ,monthyear
	function generatePtData($ptColumn, $month_year) {
		$extraCondition = ($_SESSION ['current_payroll_month'] == $month_year) ? 'AND
		 		(SELECT COUNT(*) FROM payroll_preview_temp where is_processed=0) = 0' : "";
		$queryStmt = "SELECT  @a:=@a+1 sno,p.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,
	                b.branch_name,b.pt_city_id,p.gross_salary, p.{$ptColumn} as pt FROM payroll p
					INNER JOIN employee_personal_details per
					ON p.employee_id = per.employee_id
					INNER JOIN employee_work_details w
					ON per.employee_id = w.employee_id
					INNER JOIN company_branch b
					ON b.branch_id = w.branch_id
					,(SELECT @a:= 0) AS a
					WHERE p.month_year = '{$month_year}' $extraCondition
					AND p.{$ptColumn} >0 ";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $this->tableData, $row );
		}
	}
}
?>