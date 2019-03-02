<?php
class Setting extends Model {

	function __construct($id='') {
		parent::__construct('id','appconfig_setting'); //primary key = uid; tablename = users
		$this->rs['id'] = '';
		$this->rs['setting'] = '';
		$this->rs['value'] = '';
		
		if ($id)
			$this->retrieve($id);
	}
	
	
	function update($settings=null){
		$dbh =getdbh();
		$s1="";
		/* Company Logo Update */
		foreach ($settings as $setting => $value){
			$s1 .="WHEN setting='{$setting}' THEN ? ";
		}
		$sql="UPDATE appconfig_setting SET value=(CASE {$s1} ELSE value END)";
		$stmt = $dbh->prepare($sql);
		$i=0;
		foreach ($settings as $setting => $value){
			$stmt->bindValue(++$i,is_scalar($value) ? $value : ($this->COMPRESS_ARRAY ? gzdeflate(serialize($value)) : serialize($value)));
		}
		if(!$stmt->execute())
			$result = array(false,"rowCount"=>0,"info"=>$stmt->errorInfo()[2]);
		else
			$result = array(true,"rowCount"=>$stmt->rowCount(),"info"=>'Setting Updated Successfully');
	return $result;
	}
	function uploadLogo($logo=null){
		$dbh = getdbh();
		
		if($logo['size'] != 0){
			$name = 'logo';
		
			$tmp_name = $logo['tmp_name'];
		
			$file_ext = pathinfo($logo['name'], PATHINFO_EXTENSION);
		
			$filename_image = "img/".$name.'.'.$file_ext;
			
			if(move_uploaded_file($tmp_name,$filename_image)){
				
				$image = new zebra_Image();
				
				$image->source_path = $filename_image;
				
				$image->target_path = $filename_image;
				
				$images = $image->resize('272','73',ZEBRA_IMAGE_NOT_BOXED,'-1');
				
			
				
		$stmt = $dbh->prepare("UPDATE appconfig_setting SET value = :company_logo where setting='company_logo'");
				$stmt->bindParam('company_logo', $filename_image);
				if(!$stmt->execute())
					$result = array(false,"rowCount"=>0,"info"=>$stmt->errorInfo()[2]);
				
					$result = array(true,"rowCount"=>$stmt->rowCount(),"info"=>'Logo Uploaded Successfully');
					return $result;
			
					
			}
		}
	}
	function emailConfig(){
		$dbh = getdbh();
		$emailConfig = array();
		$stmt = $dbh->query('SELECT id,host,username,password,port,secure,active FROM emailconfig');
		while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$emailConfig[] = $rs;
		};
		return $emailConfig;
		
	}
	
	
}