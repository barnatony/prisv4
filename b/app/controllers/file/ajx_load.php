 <?php
function _ajx_load(){
$files = $_REQUEST["files"];
$fileIDs = "'".implode("','", $files)."'";

$file = new File();
$files = $file->retrieve_many("file_id IN ({$fileIDs})");
$ajx_response=array("error"=>"","errorkeys"=>array(),"initialPreview"=>array(),"initialPreviewConfig"=>array());
foreach ($files as $key=>$file){
	$url = myUrl("files/".$file->get('file_url'));
	$name = $file->get('file_name');
	$ajx_response["initialPreview"][]="<img src='$url' class='file-preview-image' alt='{$name}' title='{$name}' style='width:100%' >";
	$ajx_response["initialPreviewConfig"][]=array("caption"=>$name,
													"key"=>$key,
													"extra"=>array("id"=>$file->get("file_id")),
													"size"=>$file->get("file_size")
													);
}
	
echo json_encode($ajx_response);
	
		
}