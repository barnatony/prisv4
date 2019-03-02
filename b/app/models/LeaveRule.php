<?php
class LeaveRule extends Model {

	function __construct($id='') {
		parent::__construct('id','company_leave_rules'); //primary key = post_id; tablename = blog
		$this->rs['id'] = '';
		$this->rs['leave_rule_id'] = '';
		$this->rs['rule_name'] = '';
		$this->rs['alias_name'] = '';
		$this->rs['allot_from'] = ' ';
		$this->rs['type'] = '';
		$this->rs['days_count'] = '';
		$this->rs['max_combinable'] = '';
		$this->rs['pro_rata_basis'] = '';
		$this->rs['allot_on'] = '';
		$this->rs['round_off'] = '';
		$this->rs['calculation_on'] = '';
		$this->rs['carry_forward'] = '';
		$this->rs['max_cf_days'] = '';
		$this->rs['remain_cf'] = '';
		$this->rs['is_encashable'] = '';
		$this->rs['max_enc_days'] = '';
		$this->rs['encashable_on'] = '';
		$this->rs['enc_salary'] = '';
		$this->rs['leave_in_middle'] = '';
		$this->rs['leave_in_preceeding'] = '';
		$this->rs['leave_in_succeeding'] = '';
		$this->rs['club_with'] = '';
		$this->rs['applicable_to'] = '';
		$this->rs['effects_from'] = '';
		$this->rs['enabled'] = '';
		$this->rs['updated_by'] = '';
		$this->rs['updated_on'] = '';
		
		if ($id)
			$this->retrieve($id);
	}
	
	function create() {
		$this->rs['updated_on']=date('Y-m-d H:i:s');
		$this->rs['updated_by']=$_SESSION['login_id'];
		return parent::create();
	}
	
	function ruleDataForEmployee($employee_id,$rule_id,$year){
		$queryText = "";
	}
	
	public function updateLeavesInPayroll($startDate,$endDate,$employeeId=null ) {
		
		
		
		$caseStmt="";$columStmt="";
		$leaveRules = $this->retrieve_many('enabled=1');
		foreach ($leaveRules as $key => $leaveRule){
			$caseStmt .=" MAX(CASE WHEN leave_rule_type = '".$leaveRule->get('leave_rule_id')."' THEN ASD END)".$leaveRule->get('leave_rule_id').",";
			$columStmt .="pt.".$leaveRule->get('leave_rule_id')." = IFNULL(a.".$leaveRule->get('leave_rule_id').",0),";
		}
		$noticePeriodCondition="";
		if($employeeId){
			//if employee ID is specified update in payroll for the employee
			
			
			$query="SELECT employee_id FROM emp_notice_period 
					WHERE last_working_date BETWEEN '".$startDate."' AND '".$endDate."' AND employee_id = '$employeeId'";
			$dbh = $this->getdbh();
			$stmt=$dbh->query($query);
			$row =$stmt->fetchAll(PDO::FETCH_ASSOC);
			$noticePeriodCondition = $row?" AND a.absent_date <= n.last_working_date ":"";
			
			$query="UPDATE payroll_preview_temp pt,(SELECT";
			$query.=$caseStmt ."MAX(CASE WHEN leave_rule_type = 'lop' THEN ASD ELSE 0 END)lop,
					SUM(CASE WHEN leave_rule_type IN('od','wfh','otr') THEN ASD ELSE 0 END)others
					FROM (
					SELECT leave_rule_type,SUM(day_count) ASD 
					FROM emp_absences a LEFT JOIN emp_notice_period n
					ON a.employee_id = n.employee_id 
					WHERE  absent_date BETWEEN '".$startDate."'
				    AND '".$endDate."' $noticePeriodCondition  AND a.employee_id = '$employeeId' 
		            GROUP BY leave_rule_type) s)a SET ".$columStmt." pt.lop = IFNULL(a.lop,0), pt.other_leave = IFNULL(a.others,0)  WHERE pt.employee_id = '$employeeId'";
			
			
			
		}else{
			$query="UPDATE payroll_preview_temp pt,(SELECT employee_id,";
			$query.= $caseStmt ."MAX(CASE WHEN leave_rule_type = 'lop' THEN ASD ELSE 0 END)lop,
			SUM(CASE WHEN leave_rule_type IN('od','wfh','otr') THEN ASD ELSE 0 END)others
			FROM (
					SELECT e.employee_id,e.leave_rule_type,SUM(e.day_count) ASD
					FROM employee_work_details w
					INNER JOIN  emp_absences e
					ON w.employee_id = e.employee_id AND w.enabled=1
					WHERE e.absent_date BETWEEN '".$startDate."' AND '".$endDate."'
					GROUP BY e.employee_id,e.leave_rule_type
					) s GROUP BY employee_id) a
					SET  ".$columStmt." pt.lop = IFNULL(a.lop,0), pt.other_leave = IFNULL(a.others,0)  WHERE pt.employee_id = a.employee_id ;";
		}
		
		
			$stmt=$dbh->query($query);
			if(!$stmt){ //if query not executed throws an error
				print_r($dbh->errorInfo());
			}else{
				$stmt->execute();
			}
	}

}