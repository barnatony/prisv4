<?php
class Shift extends Model {

	function __construct($id='') {
		parent::__construct('id','company_shifts'); //primary key = id; tablename = company_shifts
		$this->rs['id'] = '';
		$this->rs['shift_id'] = '';
		$this->rs['shift_name'] = '';
		$this->rs['start_time'] = '';
		$this->rs['end_time'] = '';
		$this->rs['grace_inTime'] = '';
		$this->rs['grace_outTime'] = '';
		$this->rs['early_start'] = '';
		$this->rs['late_end'] = '';
		$this->rs['min_hrs_ot'] = '';
		$this->rs['min_hrs_half_day'] = '';
		$this->rs['min_hrs_full_day'] = '';
		$this->rs['shift_hrs'] = '';
		$this->rs['is_day'] = '';
		$this->rs['enabled'] = '';
		$this->rs['updated_by'] = '';
		$this->rs['updated_on'] = '';
		
		if ($id)
			$this->retrieve($id);
	}
	
}