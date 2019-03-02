<?php
function _delete(){
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	$file_id = isset($_POST['id'])?$_POST['id']:null;
	if($file_id){
		$file = new File($file_id);
		if(!$file->delete()){
			$result[0]=false;
		}else 
			$result[0]=true;
			$result["data"]=$file_id;
	}
	echo json_encode($result);
}