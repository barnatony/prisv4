<?php
/*
 * ----------------------------------------------------------
 * Filename : miscAllowances.class.php
 * Author : RajaSundari
 * Database : miscDeduction
 * Oper : General Deduction Actions
 *
 * ----------------------------------------------------------
 */
class MiscAllowances {
	var $miscPay;
	var $name; // (normal Attribute)
	var $alias_name; // (normal Attribute)
	var $type; // (normal Attribute)
	var $sort_order = 99; // (normal Attribute)
	var $enabled = 0; // (normal Attribute)
	var $updated_by; // (normal Attribute)
	var $payment_id;
	var $payment_name;
	var $payment_for;
	var $pay_category;
	var $pay_affected_ids;
	var $payment_amount;
	var $payments_in;
	var $effects_from;
	var $pay_id;
	var $conn; // connection var
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	function add() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_pay_structure 
		( pay_structure_id,display_name,alias_name,type,sort_order,display_flag,updated_by)VALUES  (?,?,?,'MP',0,0,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->miscPay, $this->display_name, $this->alias_name, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function createMiscpay($data) {
		$sortOrder = 0;
		foreach ( $data as $allow ) {
			$stmt = mysqli_prepare ( $this->conn, "UPDATE company_pay_structure SET display_flag = 1 , sort_order = ? , updated_by = ? WHERE pay_structure_id = ?" );
			mysqli_stmt_bind_param ( $stmt, 'iss', $sortOrder, $this->updated_by, $allow ['id'] );
			$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
			if ($result) {
				mysqli_query ( $this->conn, "ALTER TABLE employee_salary_details_shadow ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER ctc_fixed_component;" );
				mysqli_query ( $this->conn, "ALTER TABLE employee_salary_details ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER ctc_fixed_component;" );
				mysqli_query ( $this->conn, "ALTER TABLE employee_salary_details_history ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER ctc_fixed_component;" );
				mysqli_query ( $this->conn, "ALTER TABLE payroll_preview_temp ADD  " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER lop" );
				mysqli_query ( $this->conn, "ALTER TABLE payroll ADD " . $allow ['id'] . " DECIMAL(10,2) DEFAULT '0.00' AFTER lop" );
			}
			$sortOrder ++;
		}
		Session::newInstance ()->_drop ( "miscPayParams" );
		Session::newInstance ()->_setMiscPayParams ();
		$miscAlloDeduArray = Session::newInstance ()->_get ( "miscPayParams" );
		Session::newInstance ()->_drop ( "generalPayParams" );
		Session::newInstance ()->_setGeneralPayParams ();
		$alloDeduArray = Session::newInstance ()->_get ( "generalPayParams" );
		$triggerColumnStatement = "";
		foreach ( array_merge ( $alloDeduArray ['A'], $miscAlloDeduArray ['MP'] ) as $allow ) {
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
		mysqli_query ( $this->conn, "UPDATE payroll_preview_temp SET status_flag='MA',updated_by='$this->updated_by'" );
		return $result;
	}
	function insert() {
		$emp_id = array();
		$is_group = is_numeric(strpos($this->pay_affected_ids,","));
		if($is_group !=1)
			$is_group = 0;
		$emp_id = explode(',',$this->pay_affected_ids);
		$date = '01/'.$this->effects_from;
		foreach( $emp_id as $val){
			$stmt = mysqli_prepare ( $this->conn, "INSERT INTO misc_payments
				(payment_id,remarks,payment_for,pay_category,pay_affected_ids,payment_amount,payments_in,effects_from,repetition_count,
				effects_upto,updated_by,is_group)VALUES(?,?,?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,DATE_ADD(STR_TO_DATE(?,'%d/%m/%Y'),INTERVAL ?-1 MONTH),?,?)" );
			mysqli_stmt_bind_param ( $stmt, 'sssssssssssss', $this->payment_id, $this->remarks, $this->payment_for, $this->pay_category, $val, $this->payment_amount, $this->payments_in, $date, $this->repetition_count, $date, $this->repetition_count, $this->updated_by,$is_group );
			$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		}
		$extraCondition = "SET pt.status_flag = 'MP' WHERE w.enabled = 1 AND pt.employee_id IN ( '" . str_replace ( ",", "','", $this->pay_affected_ids ) . "')";
		if($result===true){
			mysqli_query ( $this->conn, " UPDATE payroll_preview_temp pt
					INNER JOIN employee_work_details w
					ON pt.employee_id = w.employee_id
					$extraCondition" );
			return array('result'=>true,'data'=>'');
		}else
			return array('result'=>false,'data'=>$result);
		
	}
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE misc_payments SET payment_amount=?,payments_in=?,remarks=?,updated_by=? WHERE payment_id =?" );
		mysqli_stmt_bind_param ( $stmt, 'sssss', $this->payment_amount, $this->payments_in, $this->remarks, $this->updated_by, $this->payment_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		if($result===true){
		mysqli_query ( $this->conn, "UPDATE payroll_preview_temp pt
									 INNER JOIN employee_work_details w
									 ON pt.employee_id = w.employee_id
									 INNER JOIN misc_payments mp 
									 ON pt.employee_id = mp.pay_affected_ids
									 SET status_flag = 'MP' WHERE w.enabled = 1 AND mp.payment_id = '$this->payment_id';" );
		return array('result'=>true,'data'=>'');
		}else
			return array('result'=>false,'data'=>$result);
	}
	
	/* Enable/Disable Misc payments */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE misc_payments SET enabled =?,updated_by = ?  WHERE payment_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->payment_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/*Get grouped EmployeeIds whose PaymentId is same*/
	function getgroupIds(){
		$a_json=array();
		$query = "SELECT CONCAT(w.employee_name,' [',mp.pay_affected_ids,']') employee
				 FROM misc_payments mp
				 INNER JOIN employee_work_details w
				 ON mp.pay_affected_ids = w.employee_id
				 WHERE mp.payment_id = '$this->payment_id'";
		$result=mysqli_query($this->conn, $query) or die(mysqli_error($this->conn));
		while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			array_push($a_json,$row);
		}
		return $a_json;
	
	}
} // class : end

?>
