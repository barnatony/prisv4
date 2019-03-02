<?php
/*
 * ----------------------------------------------------------
 * Filename : generalDeduction.class.php
 * Author : Rufus Jackson
 * Database : company_deductions
 * Oper : General Deduction Actions
 *
 * ----------------------------------------------------------
 */
class Allowance {
	var $allowance_id;
	var $name; // (normal Attribute)
	var $alias_name; // (normal Attribute)
	var $type; // (normal Attribute)
	var $sort_order = 99; // (normal Attribute)
	var $enabled = 0; // (normal Attribute)
	var $updated_by; // (normal Attribute)
	var $mappedwithPid;
	var $exemption_id;
	var $conn; // connection var
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	function mapAllowanceToExcemption() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_pay_structure SET exemption_id=?,updated_by = ?,exemption_mapped_on=now() WHERE pay_structure_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sss', $this->exemption_id, $this->updated_by, $this->mappedwithPid );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_pay_structure SET exemption_id='',updated_by = ?,exemption_mapped_on=now() WHERE pay_structure_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $this->updated_by, $this->mappedwithPid );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function getMappedExemptions() {
		$a_json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT display_name,exemption_id,exemption_id as exe,pay_structure_id FROM company_pay_structure WHERE  type='A' AND display_flag =1 AND exemption_id!=''" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function addAllowance() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_pay_structure 
		( pay_structure_id,display_name,alias_name,type,sort_order,display_flag,updated_by)VALUES  (?,?,?,'A',0,0,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->allowance_id, $this->display_name, $this->alias_name, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function createAllowance($data) {
		$sortOrder = 0;
		$triggerColumnStatement = "";
		foreach ( $data as $allow ) {
			$addColumnStmt = "";
			$stmt = mysqli_prepare ( $this->conn, "UPDATE company_pay_structure SET display_flag = 1 , sort_order = ? , updated_by = ? WHERE pay_structure_id = ?" );
			mysqli_stmt_bind_param ( $stmt, 'iss', $sortOrder, $this->updated_by, $allow ['id'] );
			$result0 = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
			mysqli_query ( $this->conn, "ALTER TABLE company_allowance_slabs ADD " . $allow ['id'] . " varchar(30) DEFAULT '0|P'  AFTER basic;" );
			mysqli_query ( $this->conn, "ALTER TABLE employee_salary_details ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER basic;" );
			mysqli_query ( $this->conn, "ALTER TABLE employee_salary_details_shadow ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER basic;" );
			mysqli_query ( $this->conn, "ALTER TABLE employee_salary_details_history ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER basic;" );
			mysqli_query ( $this->conn, "ALTER TABLE payroll_preview_temp ADD  " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER basic;" );
			mysqli_query ( $this->conn, "ALTER TABLE payroll ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER basic;" );
			mysqli_query ( $this->conn, "ALTER TABLE arrears ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER basic;" );
			$triggerColumnStatement .= "{$allow['id']} = OLD.{$allow['id']},";
			$sortOrder ++;
		}
		Session::newInstance ()->_drop ( "miscPayParams" );
		Session::newInstance ()->_setMiscPayParams ();
		$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
		foreach ( $miscAlloDeduArray ['MP'] as $allow ) {
			$triggerColumnStatement .= "{$allow['pay_structure_id']} = OLD.{$allow['pay_structure_id']},";
		}
		// for salry history delete
		$triggerStatement = "DROP TRIGGER IF EXISTS   `employee_salary_details_history_before_delete` ; ";
		$triggerStatement .= "CREATE TRIGGER `employee_salary_details_history_before_delete` BEFORE DELETE ON
	employee_salary_details_history FOR EACH ROW
	BEGIN	
	SET @disable_triggers = 1;
	UPDATE employee_salary_details
	SET employee_id = OLD.employee_id,
	slab_id = OLD.slab_id,
	employee_salary_amount = OLD.employee_salary_amount,
	pf_limit = OLD.pf_limit,
	salary_type = OLD.salary_type,ctc = OLD.ctc,ctc_fixed_component = OLD.ctc_fixed_component,
	increment_id = OLD.increment_id,
	{$triggerColumnStatement}
	effects_from = OLD.effects_from
	WHERE employee_id = OLD.employee_id;
	SET @disable_triggers = NULL;
	END;";
		
		$triggerStatement .= "DROP TRIGGER IF EXISTS  `employee_salary_details_after_update`;";
		$triggerStatement .= "CREATE TRIGGER `employee_salary_details_after_update` AFTER UPDATE ON employee_salary_details FOR EACH ROW
	BEGIN IF @disable_triggers IS NULL  THEN
	INSERT INTO employee_salary_details_shadow
	SET employee_id = OLD.employee_id,
	slab_id = OLD.slab_id,
	employee_salary_amount = OLD.employee_salary_amount,
	pf_limit = OLD.pf_limit,
	increment_id = OLD.increment_id,
	salary_type = OLD.salary_type,ctc = OLD.ctc,ctc_fixed_component = OLD.ctc_fixed_component,
	{$triggerColumnStatement}
	effects_from = OLD.effects_from,
	updated_by = OLD.updated_by;
	IF OLD.increment_id != NEW.increment_id OR OLD.slab_id !=NEW.slab_id THEN
	INSERT INTO employee_salary_details_history
	SET employee_id = OLD.employee_id,
	slab_id = OLD.slab_id,
	employee_salary_amount = OLD.employee_salary_amount,
	pf_limit = OLD.pf_limit,
	increment_id = OLD.increment_id,
	salary_type = OLD.salary_type,ctc = OLD.ctc,ctc_fixed_component = OLD.ctc_fixed_component,
	{$triggerColumnStatement}
	effects_from = OLD.effects_from,
	effects_upto = DATE_SUB(NEW.effects_from, INTERVAL 1 DAY),
	updated_on = OLD.updated_on,
	updated_by = OLD.updated_by;
	ELSEIF OLD.increment_id = NEW.increment_id AND OLD.effects_from != NEW.effects_from AND OLD.slab_id = NEW.slab_id THEN
	
	UPDATE employee_salary_details_history
	SET effects_upto = DATE_SUB(NEW.effects_from, INTERVAL 1 DAY),
	updated_on = OLD.updated_on,
	updated_by = OLD.updated_by
	WHERE employee_id = OLD.employee_id AND effects_upto =DATE_SUB(OLD.effects_from, INTERVAL 1 DAY);
	END IF;
	END IF;
	
	END;";
		mysqli_multi_query ( $this->conn, $triggerStatement );
		
		Session::newInstance ()->_drop ( "generalPayParams" );
		Session::newInstance ()->_setGeneralPayParams ();
		Session::newInstance ()->_get ( "generalPayParams" );
		mysqli_query ( $this->conn, "UPDATE payroll_preview_temp SET status_flag='A',updated_by='$this->updated_by'" );
		$result = true; // to skip the duplicate column error
		return $result;
	}
	
	// class : end
	public function renameAllowance($payStuctId, $newName, $aliasOrFull) {
		if ($aliasOrFull == 'A') {
			$changeName = "alias_name";
		} elseif ($aliasOrFull == 'F') {
			$changeName = "display_name";
		} else {
			$changeName = "";
		}
		$query = "UPDATE company_pay_structure SET {$changeName} = ? WHERE pay_structure_id = ?";
		$stmt = mysqli_prepare ( $this->conn, $query );
		mysqli_stmt_bind_param ( $stmt, 'ss', $newName, $payStuctId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		mysqli_stmt_free_result ( $stmt );
		return $result;
	}
}
?>
