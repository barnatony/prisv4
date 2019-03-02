<?php 
function _upload() {
if(isset($_GET["CKEditor"])){
	
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	$funcNum = $_GET['CKEditorFuncNum'] ;
	$file_ip = array("name"=>$_FILES['upload']['name'],
			"type"=>$_FILES['upload']['type'],
			"tmp_name"=>$_FILES['upload']['tmp_name'],
			"error"=>$_FILES['upload']['error'],
			"size"=>$_FILES['upload']['size']);
	
	
	//Create File for each file
	
	$file = new File();
	$file->merge(array("file_name"=>$file_ip,"updated_by"=>$_SESSION["authname"]));
	if($file->create()->result[0]){
		$url=myUrl('files/'.$file->get('file_url'));
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum,'$url');</script>";
	}else{
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum,'$url','{$file->result["info"]}');</script>";
	}
	
}else{
	$result = array(false,"rowCount"=>0,"errorInfo"=>'',"data"=>'');
	//foreach ($_FILES['files'] as $file)
	$files = array();
		//prepare file array as like $_FILES
	foreach($_FILES['files_ip']['name'] as $key=>$file)
		$files[]=array("name"=>$_FILES['files_ip']['name'][$key],
				"type"=>$_FILES['files_ip']['type'][$key],
				"tmp_name"=>$_FILES['files_ip']['tmp_name'][$key],
				"error"=>$_FILES['files_ip']['error'][$key],
				"size"=>$_FILES['files_ip']['size'][$key],
				
				
		);
		//Create File for each file
		//file_upload restrictions
		$restrictions =null;
		if(isset($_POST["restrictions"]))
			$restrictions = json_decode($_POST["restrictions"]);
	
			$result=array();
			foreach($files as $key=>$sfile){
				$file = new File();
				$file->merge(array("file_name"=>$sfile,"updated_by"=>$_SESSION["authname"]));
				$result[] = $file->create($restrictions);
			}
			$ajx_response=array("error"=>"","errorkeys"=>array(),"initialPreview"=>array(),"initialPreviewConfig"=>array());
			foreach ($result as $key=>$file){
				if(!$file->result[0])
					$ajx_response["errorkeys"][]=$key;
					$url = myUrl("files/".$file->get('file_url'));
					$name = $file->get('file_name');
					$ajx_response["initialPreview"][]="<img src='$url' class='file-preview-image' alt='{$name}' title='{$name}' style='width:100%' >";
					$ajx_response["initialPreviewConfig"][]=array("caption"=>$name,
							"key"=>$key,
							"extra"=>array("id"=>$file->get("file_id"),"url"=>$file->get("file_url")),
							"size"=>$file->get("file_size")
					);
	
			}
	
			echo json_encode($ajx_response);	
}

}
?>