<?php
function _get($emp_id=null){
	
	//to get branch of the employees
	$emp_id= isset($_SESSION ['employee_id'])?$_SESSION['employee_id']:null;
	$wherewhat=array();
	$bindings=array();
	
	$colors = array(
			'Enabled'=>'#6883a3',
			'Due Date'=>'#f00',
			'Event'=>'#00b050',
			'Disabled'=>'#abb3bf'
	);
	$event=new Event();
	$dbh = getdbh();
	
	if(!$emp_id){
	//to get all events
	$event=$event->joined_select("e.title,e.start_date start,e.end_date end,e.category,e.enabled,e.id holiday_id,
								(CASE
  									WHEN e.enabled ='1' THEN 'enabled' 
  									WHEN e.enabled ='0' THEN 'disabled'
  									ELSE 'disabled'
  								END) className,
								(CASE
  									WHEN e.category ='Holiday' and e.enabled='1' THEN '#6883a3'
  									WHEN e.category ='Holiday' and e.enabled='0' THEN '#abb3bf' 
  									WHEN e.category ='Due Date' THEN '#f00'
  									WHEN e.category ='Event' THEN '#00b050'
  									ELSE '#abb3bf'
  								END) backgroundColor,
								GROUP_CONCAT(DISTINCT e.branch_id) branch_id,
								GROUP_CONCAT(DISTINCT br.branch_name) branches","holidays_event e
								LEFT JOIN company_branch br ON br.branch_id = e.branch_id
								GROUP BY e.title");
	}else{//to get events of the branch 
		$event=$event->joined_select("e.title,e.start_date start,e.end_date end,e.enabled,e.category,e.id holiday_id,
									(CASE
  								WHEN e.enabled ='1' THEN 'enabled' 
  								WHEN e.enabled ='0' THEN 'disabled'
  								ELSE 'disabled'
  							END) className,(CASE
  								WHEN e.category ='Holiday' and e.enabled='1' THEN '{$colors['Enabled']}'
  								WHEN e.category ='Holiday' and e.enabled='0' THEN '{$colors['Disabled']}' 
  								WHEN e.category ='Due Date' THEN '{$colors['Due Date']}'
  								WHEN e.category ='Event' THEN '{$colors['Event']}'
  								ELSE '{$colors['Disabled']}'
  							END) backgroundColor","holidays_event e 
										INNER JOIN employee_work_details wrk ON e.branch_id = wrk.branch_id
										WHERE wrk.employee_id='{$emp_id}'");
	}
	
	echo json_encode($event);
	
}