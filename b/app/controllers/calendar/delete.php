<?php
function _delete() {
	$result = array(false,"rowCount"=>0,"info"=>'');
    $id=$_REQUEST['id'];
	//if not id found throws an error
	if(!$id){
		$result['info']="Id not found";
		echo json_encode($result);
		die();
	}
	$event = new Event($id);
	if($event->exists()){//delete
		$result['rowCount']=count($event->delete());
		$result[0] = true;
		$result['info'] = 'Events Deleted';
	}else{//if event not found throws an error
		$result['info'] = 'Failed to delete the event';
	}
	
	echo json_encode($result);
	
}
?>