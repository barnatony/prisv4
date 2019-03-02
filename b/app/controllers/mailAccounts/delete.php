<?php
function _delete($id=null){
	require_login('Admin');
	$result = array(false,"rowCount"=>0,"data"=>'');
	if(!$id){
		$result["info"] = "Provide All Required Fields";
		echo json_encode($result);
		die();
	}
		
	$mail=new Mail();
	$mail->retrieve_one('active',1);
	
	if($mail->get('id')!=$id){
		$mail->delete();
		$result[0] = true;
		$result['info'] = 'Mail Deleted';
	}else{
		$result['info'] = "Email not deleted Mail is active";
	}
	
	echo json_encode($result);
	
}