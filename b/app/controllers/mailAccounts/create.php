<?php 
function _create(){
	require_login('Admin');
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	$mail = new Mail();
	$mail->merge($_POST);
	switch($result = $mail->create()->result){
    	case ($result[0]===true):
			$result['info'] .= 'Mail Created!'; 
    	break;
    	case ($result[0]===false):
    		$result['info'] .= '- Mail Not Created!';
    	break;
    }
	echo json_encode($result);
}
?>