<?php

$ip="103.92.200.13";
	$portt="23456";
		$fp = @fsockopen($ip, $portt, $errno, $errstr, 0.1);
		if (!$fp) {
			die("<script>location.href = 'http://pris.xyz/hr/tcpListenerService.php/?ip=103.92.200.13&port=23456'</script>");
			exit;
		} else {
			exit;
		}
/*
function _corn($count=0){

	$ip="103.92.200.3";
	$portt="1504";
		$fp = @fsockopen($ip, $portt, $errno, $errstr, 0.1);
		if (!$fp) {
			echo 'not running';
			return false;
		} else {
			echo "running";
			//redirect("http://pris.xyz/b/redirect/?$count/");
			redirect('redirect//');
			return true;
		}
*/		
		
		
?>