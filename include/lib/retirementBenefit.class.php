<?php
/*
 * ----------------------------------------------------------
 * Filename : retirementBenefit.class.php
 * Author : Rufus Jackson
 * Database : pay_structure
 * Oper :
 *
 * ----------------------------------------------------------
 */
class RetirementBenefit {
	var $id;
	var $name;
	var $enabled;
	var $updated_by;
	var $salary_heads;
	var $salary_heads_it_exempted;
	var $salary_average_months;
	var $salary_days;
	var $round_service_years;
	var $maximum_amount;
	var $conn; // connection var
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	function setEnable($val) {
		$query = "UPDATE company_pay_structure ps SET ps.display_flag = ? WHERE ps.pay_structure_id = ? AND ps.type='RA'";
		$stmt = mysqli_prepare ( $this->conn, $query );
		mysqli_stmt_bind_param ( $stmt, 'ss', $val, $this->id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_stmt_error ( $this->conn );
		Session::newInstance ()->_drop ( "retirementParams" );
		Session::newInstance ()->_setRetirementParams ();
		Session::newInstance ()->_get ( "retirementParams" );
		return $result;
	}
	public function getBenefits() {
		$a_json = array ();
		$row = array ();
		$query = "SELECT ps.pay_structure_id , ps.display_flag  FROM company_pay_structure ps WHERE ps.type='RA'";
		$stmt = mysqli_prepare ( $this->conn, $query );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_stmt_error ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $payment_structure, $display_flag );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['pay_structure_id'] = $payment_structure;
			$row ['display_flag'] = $display_flag;
			array_push ( $a_json, $row );
		}
		
		return $a_json;
	}
	public function updateRetirement($benefit) {
		$query = "UPDATE retirement_benefits
	SET
   salary_heads = ? ,salary_heads_it_exempted = ?
  ,salary_average_months = ?
  ,salary_days = ?
  ,round_service_years = ?
  ,maximum_amount = ?
  ,updated_by = ?
WHERE r_benefit_id = ?";
		$stmt = mysqli_prepare ( $this->conn, $query );
		mysqli_stmt_bind_param ( $stmt, 'ssiissss', $this->salary_heads, $this->salary_heads_it_exempted, $this->salary_average_months, $this->salary_days, $this->round_service_years, $this->maximum_amount, $this->updated_by, $benefit );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_stmt_error ( $this->conn );
		mysqli_query ( $this->conn, "  UPDATE settlements s
								INNER JOIN payroll_preview_temp pt
								ON s.employee_id = pt.employee_id
								SET s.$benefit =NULL " );
		return $result;
	}
	public function selectRetirement($benefit) {
		$a_json = array ();
		$query = mysqli_query ( $this->conn, "SELECT salary_heads ,salary_heads_it_exempted,salary_average_months,salary_days,round_service_years,maximum_amount,updated_by FROM retirement_benefits WHERE r_benefit_id = '$benefit'" );
		while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function calculateBenefit($benefit, $emp_id, $doj, $doe) {
		$query = "SELECT r.r_benefit_id, r.salary_heads,r.salary_heads_it_exempted, r.salary_average_months, r.salary_days,
	(CASE WHEN r.round_service_years = 'UP' THEN CEIL(PERIOD_DIFF( DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(?, '%d/%m/%Y'), '%Y-%m-%d'),'%Y%m'),
			DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(?, '%d/%m/%Y'), '%Y-%m-%d'),'%Y%m'))/12)
			WHEN r.round_service_years = 'DOWN' THEN FLOOR(PERIOD_DIFF( DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(?, '%d/%m/%Y'), '%Y-%m-%d'),'%Y%m'), 
			DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(?, '%d/%m/%Y'), '%Y-%m-%d'),'%Y%m'))/12)
			END) serv_years, r.maximum_amount
			FROM retirement_benefits r 
			INNER JOIN company_pay_structure ps
            ON r.r_benefit_id=ps.pay_structure_id
            AND ps.type='RA' AND  ps.display_flag=1 AND r.r_benefit_id = ?";
		$stmt = mysqli_prepare ( $this->conn, $query );
		mysqli_stmt_bind_param ( $stmt, 'sssss', $doe, $doj, $doe, $doj, $benefit );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $benefit_id, $salary_heads, $salary_heads_it_exempted, $avg_months, $sal_days, $serv_years, $max_amount );
		mysqli_stmt_fetch ( $stmt );
		mysqli_stmt_close ( $stmt );
		/*
		 * do{
		 * if ($result=mysqli_store_result($this->conn)) {
		 * mysqli_free_result($result);
		 * }
		 * }while (mysqli_next_result($this->conn));
		 */
		if ($serv_years >= 5) {
			$salayHeads = str_replace ( ",", "+s.", $salary_heads );
			$salaryHeadsItExempted = str_replace ( ",", "+s.", $salary_heads_it_exempted );
			
			$query = "SELECT ROUND((SUM(salHead*TIMESTAMPDIFF(MONTH,effects_from,effects_upto))/$avg_months),2) payable,
	       ROUND((SUM(exemptedSalHead*TIMESTAMPDIFF(MONTH,effects_from,effects_upto))/$avg_months),2) exempted
	       FROM (
		   SELECT SUM($salayHeads) as salHead,
		   SUM($salaryHeadsItExempted) as exemptedSalHead,
		   (CASE WHEN s.effects_from < DATE_SUB(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH),INTERVAL $avg_months  MONTH)
		   THEN DATE_SUB(DATE_FORMAT(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH),'%Y-%m-01'),INTERVAL $avg_months  MONTH)
		   ELSE DATE_FORMAT(s.effects_from,'%Y-%m-01')
		   END) effects_from,
		   DATE_FORMAT(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH),'%Y-%m-01') effects_upto
		   FROM employee_salary_details s WHERE s.employee_id = ?
	       UNION ALL
	       SELECT SUM($salayHeads) as salHead,
	       SUM($salaryHeadsItExempted) as exemptedSalHead,
	       (CASE WHEN s.effects_from < DATE_SUB(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH),INTERVAL $avg_months  MONTH)
	       THEN DATE_SUB(DATE_FORMAT(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH),'%Y-%m-01'),INTERVAL $avg_months  MONTH)
	       ELSE DATE_FORMAT(s.effects_from,'%Y-%m-01')
	       END) effects_from,
	       DATE_FORMAT(s.effects_upto,'%Y-%m-01') effects_upto
		   FROM employee_salary_details_history s
		   WHERE s.effects_from
		   BETWEEN DATE_SUB(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH),INTERVAL $avg_months  MONTH) AND DATE_FORMAT(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH), '%Y-%m-%d')
		   OR s.effects_upto
		   BETWEEN DATE_SUB(DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH),INTERVAL $avg_months  MONTH) AND DATE_ADD('" . $_SESSION ['current_payroll_month'] . "',INTERVAL 1 MONTH)
		   AND s.employee_id=  ? ) s;";
			
			$stmt = mysqli_prepare ( $this->conn, $query );
			if (! $stmt) {
				die ( mysqli_error ( $this->conn ) );
			}
			
			if (! mysqli_stmt_bind_param ( $stmt, 'ss', $emp_id, $emp_id )) {
				die ( mysqli_stmt_error ( $this->conn ) );
			}
			if (! mysqli_stmt_execute ( $stmt )) {
				die ( mysqli_error ( $this->conn ) );
			}
			mysqli_stmt_bind_result ( $stmt, $salary_amt, $sal_amt_exempted );
			mysqli_stmt_fetch ( $stmt );
			mysqli_stmt_close ( $stmt );
			$benefit_amount = self::calculateBenefits ( $sal_amt_exempted, $salary_amt, $sal_days, $serv_years, $max_amount );
		} else {
			$benefit_amount = 0;
		}
		
		return $benefit_amount;
	}
	public static function calculateBenefits($basicNda, $salary_amt = null, $sal_days = null, $ser_years, $max_amount) {
		$grat_it_exempted = (15 / 26) * ($basicNda) * $ser_years;
		if ($salary_amt != null) {
			$grat = ($sal_days / 30) * $salary_amt * $ser_years;
		} else {
			$grat = $grat_it_exempted;
		}
		$r_gratuity = ROUND ( min ( $max_amount, $grat ), 2 );
		$r_gratuity = max($r_gratuity,0);
		return array (
				'amount' => $r_gratuity,
				'amount_it_exempted' => ROUND ( $grat_it_exempted, 2 ) 
		);
	}
}
?>
