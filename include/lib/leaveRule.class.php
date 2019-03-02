<?php
/*
 * ----------------------------------------------------------
 * Filename : leaveRule.class.php
 * Author : Rufus Jackson
 * Database : company_LeaveRule
 * Oper : LeaveRule Actions
 *
 * ----------------------------------------------------------
 */
class LeaveRule {
	/* Member variables */
	var $leave_rule_id;
	var $rule_name;
	var $alias_name;
	var $effects_from;
	var $allot_from;
	var $type;
	var $days_count;
	var $compoff;
	var $max_combinable;
	var $pro_rata_basis;
	var $allot_on;
	var $round_off;
	var $calculation_on;
	var $carry_forward;
	var $max_cf_days;
	var $remain_cf;
	var $is_encashable;
	var $max_enc_days;
	var $remain_enc;
	var $encashable_on;
	var $enc_salary;
	var $leave_in_middle;
	var $leave_in_preceeding;
	var $leave_in_succeeding;
	var $club_with;
	var $applicable_to;
	var $enabled;
	var $updated_by;
	var $current_year;
	var $next_year;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New LeaveRule */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "insert into company_leave_rules(leave_rule_id,rule_name,alias_name,effects_from,allot_from,type,days_count,
  		max_combinable,pro_rata_basis,allot_on,round_off,calculation_on,carry_forward,max_cf_days,remain_cf,
  		is_encashable,max_enc_days,remain_enc,encashable_on,enc_salary,leave_in_middle,leave_in_preceeding,leave_in_succeeding,
  		club_with,applicable_to,updated_by) values (?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssssssssssssssssss', $this->leave_rule_id, $this->rule_name, $this->alias_name, $this->effects_from, $this->allot_from, $this->type, $this->days_count, $this->max_combinable, $this->pro_rata_basis, $this->allot_on, $this->round_off, $this->calculation_on, $this->carry_forward, $this->max_cf_days, $this->remain_cf, $this->is_encashable, $this->max_enc_days, $this->remain_enc, $this->encashable_on, $this->enc_salary, $this->leave_in_middle, $this->leave_in_preceeding, $this->leave_in_succeeding, $this->club_with, $this->applicable_to, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		if ($result) {
			$elements = array ();
			
			foreach ( $this->club as $name ) {
				// employees converted into string using foreach and implode
				$elements [] = "\'" . $name . "\'";
			}
			
			$applicable_to = implode ( ',', $elements );
			
			$stmt1 = mysqli_query ( $this->conn, "ALTER TABLE payroll_preview_temp
			 ADD    $this->leave_rule_id    DECIMAL(10,2) DEFAULT '0.00'  COMMENT 'leaverules' AFTER employee_id;" );
			
			$stmt1 = mysqli_query ( $this->conn, "ALTER TABLE emp_montly_attendance
  	         ADD    $this->leave_rule_id    DECIMAL(10,2) DEFAULT '0.00'  COMMENT 'leaverules' AFTER month_year;" );
			
			$stmt1 = mysqli_query ( $this->conn, "ALTER TABLE payroll
			ADD $this->leave_rule_id  DECIMAL(10,2) DEFAULT '0.00'  COMMENT 'leaverules' AFTER year;" );
			$qurt = "call CREDITLEAVE_ONLEAVEADD('" . $_SESSION ['current_payroll_month'] . "','$this->current_year',STR_TO_DATE('$this->next_year','%d/%m/%Y'),'$this->leave_rule_id',STR_TO_DATE('$this->effects_from','%d/%m/%Y'),
            '$this->allot_from','$this->type','$this->days_count','$this->round_off','$this->pro_rata_basis','$applicable_to','$this->updated_by')";
			$result = mysqli_query ( $this->conn, $qurt ) ? TRUE : mysqli_error ( $this->conn );
			
			Session::newInstance ()->_drop ( "leaveRules" );
			Session::newInstance ()->_setLeaveRules ();
			$arry = Session::newInstance ()->_get ( "leaveRules" );
		}
		return $result;
	}
	
	/* Update LeaveRule Using LeaveRule ID */
	function update() {
		$elements = array ();
		foreach ( $this->club as $name ) {
			// employees converted into string using foreach and implode
			$elements [] = "\'" . $name . "\'";
		}
		$applicable_to = implode ( ',', $elements );
		
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_leave_rules SET rule_name=?,effects_from=STR_TO_DATE(?,'%d/%m/%Y'),allot_from=?,type=?,days_count=?,
  		max_combinable=?,pro_rata_basis=?,allot_on=?,round_off=?,calculation_on=?,carry_forward=?,max_cf_days=?,remain_cf=?,
  		is_encashable=?,max_enc_days=?,remain_enc=?,encashable_on=?,enc_salary=?,leave_in_middle=?,leave_in_preceeding=?,leave_in_succeeding=?,
  		club_with=?,applicable_to=?,updated_by=? WHERE leave_rule_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssssssssssssssssssss', $this->rule_name, $this->effects_from, $this->allot_from, $this->type, $this->days_count, $this->max_combinable, $this->pro_rata_basis, $this->allot_on, $this->round_off, $this->calculation_on, $this->carry_forward, $this->max_cf_days, $this->remain_cf, $this->is_encashable, $this->max_enc_days, $this->remain_enc, $this->encashable_on, $this->enc_salary, $this->leave_in_middle, $this->leave_in_preceeding, $this->leave_in_succeeding, $this->club_with, $this->applicable_to, $this->updated_by, $this->leave_rule_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		$qurt = "call CREDITLEAVE_ONLEAVEADD('" . $_SESSION ['current_payroll_month'] . "','$this->current_year',STR_TO_DATE('$this->next_year','%d/%m/%Y'),'$this->leave_rule_id',STR_TO_DATE('$this->effects_from','%d/%m/%Y'),
		'$this->allot_from','$this->type','$this->days_count','$this->round_off','$this->pro_rata_basis','$applicable_to','$this->updated_by')";
		$result = mysqli_query ( $this->conn, $qurt ) ? TRUE : mysqli_error ( $this->conn );
		
		Session::newInstance ()->_drop ( "leaveRules" );
		Session::newInstance ()->_setLeaveRules ();
		$arry = Session::newInstance ()->_get ( "leaveRules" );
		return $result;
	}
	
	/* Enable/Disable LeaveRule */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_leave_rules SET enabled =?,updated_by = ?  WHERE leave_rule_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->leave_rule_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Delete LeaveRule */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_leave_rules WHERE leave_rule_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->leave_rule_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function select($leave_rule_id) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT DATE_FORMAT(effects_from,'%d/%m/%Y') effects_from, leave_rule_id, rule_name, alias_name, allot_from, type, days_count, 
				                              max_combinable, pro_rata_basis, allot_on, round_off, calculation_on, carry_forward, max_cf_days,
				                              remain_cf, is_encashable, max_enc_days, remain_enc, encashable_on, enc_salary,
				                              leave_in_middle, leave_in_preceeding, leave_in_succeeding, club_with, applicable_to, enabled
				                              FROM company_leave_rules  WHERE enabled=1 AND leave_rule_id='$leave_rule_id'" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function view($alias_name) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, " SELECT DATE_FORMAT(effects_from,'%d/%m/%Y') effects_from, leave_rule_id, rule_name, alias_name, allot_from, type, days_count, 
												max_combinable, pro_rata_basis, allot_on, round_off, calculation_on, carry_forward, 
												max_cf_days, remain_cf, is_encashable, max_enc_days, remain_enc, encashable_on,
												enc_salary, leave_in_middle, leave_in_preceeding, leave_in_succeeding, club_with,
												applicable_to  FROM company_leave_rules  WHERE enabled=1 AND alias_name='$alias_name'" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function header_select() {
		$a_json = array ();
		$_SESSION ['leaveRuleslabel'] = "";
		$result = mysqli_query ( $this->conn, "SELECT leave_rule_id,alias_name,enabled,type FROM company_leave_rules WHERE enabled=1" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		$_SESSION ['leaveRuleslabel'] = substr ( $_SESSION ['leaveRuleslabel'], 0, - 1 );
		return $a_json;
	}
}
?>