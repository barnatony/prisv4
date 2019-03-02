<?php
//get the leave account (balance) of the employee for Admin,TL & employee
function _get($year="current"){
	$wherewhat =array();
	$bindings = array();
	//if employee ID is set take leave accounts for the employee ID
	if(isset($_REQUEST["eID"]) && $_REQUEST["eID"] !=""){
		if($_SESSION["authprivilage"]=="employee")
			$employee_id=$_SESSION["employee_id"];
		else
			$employee_id = $_REQUEST["eID"];
		$wherewhat =" employee_id = ?";
		array_push($bindings, "%{$_REQUEST["s"]}%");
	}
	//if year is set to current take the current year account
	if($year="current")
		$year= ($_SESSION ['creditLeaveBased'] == 'finYear') ? $_SESSION ['financialYear'] : $_SESSION ['payrollYear'];
	
	$wherewhat =" year = ?";
	array_push($bindings, $year);
	
	
	$leaveAccount=new LeaveAccount();
	//$leaveAccount->select('DISTINCT (alias_name) alias,leave_rule_id,rule_name ',$wherewhat,$bindings);
	
	echo json_encode($leave);
	
}