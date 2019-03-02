<?php
class LeaveAccount extends Model {

	function __construct($id='') {
		parent::__construct('id','emp_leave_account'); //primary key = post_id; tablename = blog
		$this->rs['id'] = '';
		$this->rs['employee_id'] = '';
		$this->rs['year'] = '';
		$this->rs['leave_rule_id'] = '';
		$this->rs['opening_bal'] = ' ';
		$this->rs['allotted'] = '';
		$this->rs['availed'] = '';
		$this->rs['encashed'] = '';
		$this->rs['adjusted'] = '';
		$this->rs['lapsed'] = '';
		$this->rs['is_leavecredited'] = '';
		$this->rs['updated_on'] = '';
		$this->rs['updated_by'] = '';
		if ($id)
			$this->retrieve($id);
	}
	
	function create() {
		$this->rs['updated_on']=date('Y-m-d H:i:s');
		$this->rs['updated_by']='headhr';
		return parent::create();
	}

}