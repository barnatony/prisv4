<?php
function _corn(){

	$ip="103.92.200.3";
	$portt="1504";
		$fp = @fsockopen($ip, $portt, $errno, $errstr, 0.1);
		if (!$fp) {
			echo 'not running';
			return false;
		} else {
			echo "running";
			$Url="http://pris.xyz/hr/tcpListenerService.php/?ip=103.92.200.3&port=1504";
			return true;
		}
		$Url = "http://vdart.pris.xyz/b/redirect//";
		
		
}