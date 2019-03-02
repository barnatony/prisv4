<?php
function _get(){
	$wherewhat =array();
	$bindings = array();
	if(isset($_REQUEST["s"]) && $_REQUEST["s"] !=""){
		$wherewhat =" alias_name LIKE ?";
		array_push($bindings, "%{$_REQUEST["s"]}%");
	}else{
		$wherewhat =" alias_name IS NOT NULL";
		array_push($bindings, " ");
	}
	$leave=new LeaveRule();
	$leave=$leave->select('DISTINCT (alias_name) alias,leave_rule_id,rule_name ',$wherewhat,$bindings);
	
	echo json_encode($leave);
	
}