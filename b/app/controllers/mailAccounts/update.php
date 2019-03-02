<?php
function _update(){
	require_login('Admin');
	$result = array(false,"rowCount"=>0,"info"=>'',"data"=>'');
	$mail = new Mail();
	$id = max(0,intval($_REQUEST['tId']));
	$mail = new Mail();
	$mail->retrieve($id);
	
	if($mail->exists()){
		//update
	$mail->merge(array("host"=>$_REQUEST["host"],
			"username"=>$_REQUEST["username"],
			"port"=>$_REQUEST["port"],
			"secure"=>$_REQUEST["secure"],
			"password"=>$_REQUEST["password"]
			));
	
	switch($result = $mail->update()->result){
		case ($result[0]===true):
			$result['info'] .= 'Mail Updated!';
			break;
		case ($result[0]===false):
			$result['info'] .= '- Mail Not Updated!';
			break;
	}
	
	
	}else{
		//create
		$mail->merge(array("host"=>$_REQUEST["host"],
				"username"=>$_REQUEST["username"],
				"port"=>$_REQUEST["port"],
				"secure"=>$_REQUEST["secure"],
				"password"=>$_REQUEST["password"]
		));

		switch($result = $mail->create()->result){
			case ($result[0]===true):
				$result['info'] .= 'Mail Created!';
				break;
			case ($result[0]===false):
				$result['info'] .= '- Mail Not Created!';
				break;
		}
	}
	
	echo json_encode($result);
}
?>