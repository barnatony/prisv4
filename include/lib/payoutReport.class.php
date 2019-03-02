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
class payoutReport {
	/* Member variables */
	var $caption;
	var $duration;
	var $tableHeaders = array ();
	var $tableData = array ();
	protected $conn;
	function __construct($conn) {
		$this->conn = $conn;
		$columns = "Bank Name,
					Branch Name,Account NO, IFSC Code,TotEmp ,Net Salary";
		$this->tableHeaders = explode ( ",", $columns );
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	/* Member functions */
	// tds ,monthyear
	function generatePayoutData($month_year) {
		$extraCondition = ($_SESSION ['current_payroll_month'] == $month_year) ? 'AND
		 		(SELECT COUNT(*) FROM payroll_preview_temp where is_processed=0) = 0' : "";
		$queryStmt = "SELECT c.bank_name comBankName,
					c.bank_branch compBranch,c.bank_ac_no compAccNO, c.bank_ifsc comIfsc,count(w.employee_id) countEmp ,SUM(p.net_salary) net
					FROM employee_personal_details e INNER JOIN employee_work_details w ON e.employee_id = w.employee_id
					LEFT JOIN payroll p ON w.employee_id = p.employee_id 
					LEFT JOIN company_payment_modes c ON w.payment_mode_id = c.payment_mode_id 
					WHERE p.month_year = '$month_year' AND w.enabled=1  $extraCondition GROUP BY c.bank_name ORDER BY bank_name ASC; ";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $this->tableData, $row );
		}
	}
}
?>