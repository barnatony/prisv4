<?php
function _get($request_id=null){
	//for leave_request view
	$result = array(false,"rowCount"=>"","info"=>'');
	
	
	if(!$request_id){
		//throw error object
		$result['info']="Leve request_id required";
		echo json_encode($result);
		die();
	}
	
	$leave =new LeaveRequest();
	$leave=$leave->select('request_id,employee_id,from_date,from_half,to_date,to_half,duration,
							leave_type,reason,status,admin_reason
							approved_on,approved_by,updated_by,updated_on','request_id =?',$request_id);
	
	
	
	
	echo json_encode($leave );
		
}