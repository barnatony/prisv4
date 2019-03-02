<?php
class FeaturesUpgrade{
	var $features; 
	var $conn;
	function upgradeSendEmail($features){
		if($features=='leaveManagement' || $features=='attendances' || $features=='compensation')
			$features='Leave Management System';
		else 
			$features=$features;
		
		$mail=new NotifyEmail();
		$mail->conn = $this->conn;
		$subject="Raise Request to updagrade ".ucwords($features).' Done By $name';
		$result=$mail->_send(null,"sundari@basstechs.com", 'UpgradeRequest',$subject);
		return ($result==1)?true:false;
	}
}
?>