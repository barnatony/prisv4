<?php
function _index(){

	$ip="103.92.200.13";
	$portt="23456";
		$fp = @fsockopen($ip, $portt, $errno, $errstr, 0.1);
		if (!$fp) {
			echo 'not running';
			return false;
		} else {
			echo "running";
			return true;
		}
		$Url = "http://pris.xyz/b/redirect//";
		
}