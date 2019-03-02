<?php
function _update($id) {
	$result = array(false,"rowCount"=>0,"errorInfo"=>'');
	
	//Validations
	/*try{
	 		if(!isset($_REQUEST['category']) || $_REQUEST['category']=="")
	 			throw new Exception("Select a valid category");
	 		if(!isset($_REQUEST['title']) || $_REQUEST['title']=="")
	 			throw new Exception("Enter the title");
	 		if(!isset($_REQUEST['sDate']) || $_REQUEST['sDate']=="")
	 			throw new Exception("Select the StartDate");
	 		if(!isset($_REQUEST['eDate']) || $_REQUEST['eDate']=="")
	 			throw new Exception("Select the EndDate");
	 	 }catch (Exception $e) {
	 			$result[0]=false;
	 			$result["info"]=$e->getMessage();
	 			die(json_encode($result));
	 	}*/
	 		
	//if id not found throws an error
	if(!$id){
		$result['errorInfo']="id not found";
		echo json_encode($result);
		die();
	}
	
	
	
	$event=new Event($id);
	
	//if event is not exists in db throws an error
	if(!$event->exists()){
		$result['errorInfo']="Event not found";
		die(json_encode($result));
	}else{//update 
		$event->merge(array(
			"category"=>$_REQUEST['category'],
			"holiday_id"=>$_REQUEST['holiday_id'],
			"branch_id"=>$_REQUEST['branch'],
			"title"=>$_REQUEST['title'],
			"start_date"=>$_REQUEST['sDate'],
			"end_date"=>$_REQUEST['eDate'],
	));
	
	switch($result = $event->update()->result){
		case ($result[0]===true):
			$result['info'] .= 'Event Updated!';
			break;
		case ($result[0]===false):
			$result['info'] .= 'Event Not Updated!';
			break;
	}
	}
	
	echo json_encode($result);
}