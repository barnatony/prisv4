<?php 
function _setActive(){
	require_login('Admin');
	$result = array(false,"rowCount"=>0,"info"=>'',"data"=>'');
    $id = max(0,intval($_POST['id']));
	$mail = new Mail();
	$active=$_POST['active'];
	$mail->retrieve($id);
	$mail->merge($_POST);
		if (!$mail->exists()){
			$result['info']='Mail not found!';
		}else{
 	
			switch($result = $mail->setActiveEmail()->result){
				case ($result[0]===true):
					$result['info'] .= 'Set Active';
					break;
				case ($result[0]===false):
					$result['info'] .= 'set active failed!';
					break;
			}
    }
	echo json_encode($result);
}
?>

