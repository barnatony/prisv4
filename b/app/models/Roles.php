<?php
class Roles extends Model {

	function __construct($role_id='') {
		parent::__construct('role_id','user_roles'); //primary key = role_id; tablename = user_roles
		$this->rs['role_id'] = '';
		$this->rs['roles'] = '';
		
		if ($role_id)
			$this->retrieve($role_id);
	}
}