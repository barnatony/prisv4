<?php
class Event extends Model {

	function __construct($id='') {
		parent::__construct('id','holidays_event'); //primary key = id; tablename = holidays_event
		$this->rs['id'] = '';
		$this->rs['holiday_id'] = '';
		$this->rs['category'] = '';
		$this->rs['branch_id'] = '';
		$this->rs['title'] = '';
		$this->rs['start_date'] = '';
		$this->rs['end_date'] = '';
		$this->rs['enabled'] = '0';
		$this->rs['updated_on'] = '';
		$this->rs['updated_by'] = '';
		
		if ($id)
			$this->retrieve($id);
	}
	
}