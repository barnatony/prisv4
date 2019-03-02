<?php
class File extends Model {

	function __construct($file_id='') {
		parent::__construct('file_id','files'); //primary key = uid; tablename = users
		$this->rs['file_id'] = '';
		$this->rs['file_name'] = '';
		$this->rs['file_desc'] = '';
		$this->rs['file_size'] = '';
		$this->rs['file_url'] = '';
		$this->rs['updated_on'] = '';
		$this->rs['updated_by'] = '';
		if ($file_id)
			$this->retrieve($file_id);
	}

	function create($restrictions=null) {

		$this->rs['updated_on']=date('Y-m-d H:i:s');


		//upload the file in the files>2016>08 Folder with the same file name the user uploaded
		/*
		 * IF the Year and Month Folder Exists Upload the file directly else create folder
		 * After the successful creation Insert the file data into db
		 *
		 * */

		$folderYear =  'files/'.date('Y').'/';
		$folderMonth = 'files/'.date('Y').'/'.date('m').'/';

		/* Make Folder If not exist */

	
		if(!file_exists($folderYear)){
			mkdir($folderYear);
			//create index file here
			$file= file_get_contents(VIEW_PATH.'errors/error.php');
			$pagename = 'index';
			$newFileName = './'.$folderYear.'/'.$pagename.".php";
			$php_file=file_put_contents($newFileName, $file);
		
		}
		if(!file_exists($folderMonth)){
			mkdir($folderMonth);
			//create index file here
			$file= file_get_contents(VIEW_PATH.'errors/error.php');
			$pagename = 'index';
			$newFileName = './'.$folderMonth.'/'.$pagename.".php";
			$php_file=file_put_contents($newFileName, $file); 
		}
				

				/*   Image Uploading  */

				$name = md5($this->rs['file_name']['name']).time();
				$tmp_name = $this->rs['file_name']['tmp_name'];

				$file_ext = pathinfo($this->rs['file_name']['name'], PATHINFO_EXTENSION);

				$filename = "files/".date('Y')."/".date('m')."/".$name.'.'.$file_ext;
				move_uploaded_file($tmp_name,$filename);
				//compressing images
				$imageExtns=array("jpg","png","jpeg");
				if(in_array($file_ext,$imageExtns)) {
					$image = new zebra_Image();
					$image->source_path = $image->target_path =  $filename;
					$image->png_compression = 8;
					//resize if width gt 1200 or ht gt 800
					$width = 1200;
					$height = 480;
					
					if($restrictions && $restrictions->image){
						$width = $restrictions->image->width?$restrictions->image->width:$width;
						$height = $restrictions->image->height?$restrictions->image->height:$height;
					}
					$size = getimagesize($filename);
					
					if(($size[0]>=$width && $size[1]<$height) ||( $size[1]==$height && $size[0] > $width)){ //width is gte and height is lt
						$width = $width;$height=0;
					}elseif(($size[1]>$height && $size[0]<$width )|| ($size[0]==$width && $size[1] > $height)){ //height is gte and width is lt
						$height = $height;$width=0;
					}elseif($size[1]>$height && $size[0]>$width){
						$hdiff = $size[1]-$height;
						$wdiff=$size[0] -$width;
						if($hdiff <= $wdiff){
							$width = $width;$height=0;
						}else{ 
							$height = $height;$width=0;
						}
					}elseif($size[1]<=$height && $size[0]<=$width){
						$height = 0;$width=0;
					}
					
					$image->resize($width,$height);
					
									
				}
				$this->set("file_size", $this->rs['file_name']['size']);
				$this->rs['file_name'] = $this->rs['file_name']['name'];
				$this->rs['file_url'] = date('Y')."/".date('m')."/".$name.'.'.$file_ext;
				return parent::create();

	}

function delete(){
		$file_url = $_SERVER['DOCUMENT_ROOT'].myUrl("files/".$this->get("file_url"));
		if(file_exists($file_url)){
			if(unlink($file_url)){
				parent::delete();
				return $this;	
			}else
					return false;
		}else{
			echo false;
		}
	}
	
}
?>