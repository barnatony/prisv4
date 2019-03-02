<?php
function myUrl($url='',$fullurl=false) {
  $s=$fullurl ? WEB_DOMAIN : '';
  $s.=WEB_FOLDER.$url;
  //echo $s;
  return $s;
}

function rootUrl($url=""){
	$s =$_SERVER["DOCUMENT_ROOT"].WEB_FOLDER.$url;
	return $s;
}

function redirect($url,$alertmsg='') {
if ($alertmsg)
    addjAlert($alertmsg,$url);
  header('Location: '.myUrl($url));
  exit;
}

function require_login($privilage=null,$next=null) {
	if($privilage)
		if($privilage=='Admin'){
			if (!isset($_SESSION['authuid']) || $_SESSION['authprivilage']!='Admin')
				if ($next)
					redirect('main/login/?next='.$next);
					else
						redirect('main/login');
	}elseif($privilage=='Author'){
		if (!isset($_SESSION['authuid']) || $_SESSION['authprivilage']!='Author')
			if ($next)
				redirect('main/login/?next='.$next);
				else
					redirect('main/login');
	}else{
		if (!isset($_SESSION['authuid']))
			if ($next)
				redirect('main/login/?next='.$next);
				else
					redirect('main/login');
	}
	else
		if (!isset($_SESSION['authuid']))
			if ($next)
				redirect('main/login/?next='.$next);
				else
					redirect('main/login');
}

function isLogged($privilage){
	if($privilage=='Admin')
		if (isset($_SESSION['authuid']) && $_SESSION['authprivilage']=='Admin')
			return true;
			if($privilage=='Author')
				if (isset($_SESSION['authuid']) && $_SESSION['authprivilage']=='Author')
					return true;
					if($privilage=='User')
						if (isset($_SESSION['authuid']))
							return true;
							return false;
}
//session must have started
//$uri indicates which uri will activate the alert (substring check)
function addjAlert($msg,$uri='') {
  if ($msg) {
    $s="alert(\"$msg\");";
    $_SESSION['jAlert'][]=array($uri,$s);
  }
}

function getjAlert() {
  if (!isset($_SESSION['jAlert']) || !$_SESSION['jAlert'])
    return '';
  $pageuri=$_SERVER['REQUEST_URI'];
  $current=null;
  $remainder=null;
  foreach ($_SESSION['jAlert'] as $x) {
    $uri=$x[0];
    if (!$uri || strpos($pageuri,$uri)!==false)
      $current[]=$x[1];
    else
      $remainder[]=$x;
  }
  if ($current) {
    if ($remainder)
      $_SESSION['jAlert']=$remainder;
    else
      unset($_SESSION['jAlert']);
    return '<script type="text/javascript">'."\n".implode("\n",$current)."\n</script>\n";
  }
  return '';
}
function printMenu($menu,$isRoot=true) {
	//$activeClass = $menu['name']=$activeMenu?"class='active'":'';
	if(!is_null($menu) && count($menu) > 0) {
		$data = ($isRoot)?'<ul class="nav navbar-nav">':'<ul class="dropdown-menu">';
		foreach($menu as $node) {
			if (strpos($node['url'], 'http://') !== false) {
				$url=$node['url'];
			}else{
				$url=myUrl($node['url']);
			}
			$liClass = "scroll";
			$liClass .= ($node['children'])? " dropdown":"";
			$liData = "<li class='{$liClass}'>";
			if($node['children']){
				$hrefData = '<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$node['name'].'</a>';
			}else{
				$hrefData ="<a href='{$url}'>{$node['name']}</a>";
			}
			$childData = printMenu($node['children'],false);
			$data .="{$liData}{$hrefData}{$childData}</li>";
		}
			$data .="</ul>";
		return $data;
	}
}

function str_to_url($str,$appurl=false,$fullurl=false) {
    $str = strtolower($str); // lowercase
    $str = preg_replace("/^\s+|\s+$/", '', $str); // remove leading and trailing whitespaces
    $str = preg_replace("/[^a-z ]/", '', $str); // remove everything that is not [a-z] or whitespace
    $str = preg_replace("/\s+/", '-', $str);
    if($appurl || $fullurl)
    	$str = myUrl($str,$fullurl);
    return $str;
}

function structure_url($dbUrl,$dbIdentifier,$appurl=false,$fullurl=false){
	$dbUrl .= "-".$dbIdentifier;
	if($appurl || $fullurl)
		$dbUrl = myUrl($dbUrl,$fullurl);
	return $dbUrl;
	
}

function crypto_rand_secure($min, $max) {
	$range = $max - $min;
	if ($range < 0) return $min; // not so random...
	$log = log($range, 2);
	$bytes = (int) ($log / 8) + 1; // length in bytes
	$bits = (int) $log + 1; // length in bits
	$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
	do {
		$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
		$rnd = $rnd & $filter; // discard irrelevant bits
	} while ($rnd >= $range);
	return $min + $rnd;
}

function getToken($length=32){
	$token = "";
	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet.= "0123456789";
	for($i=0;$i<$length;$i++){
		$token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
	}
	return $token;
}

function getRequestURI(){
	$requri = $_SERVER['REQUEST_URI'];
	if (strpos($requri,"?"))
		return substr($requri,strpos($requri,"?"));
	return "";
}
function parseLocation($location){ //chennai -> Chennai, adyar,chennai -> Adyar,Chennai,  west-tambaram->West Tambaram
	if(!$location)
		return false;
		$location = strtolower(str_replace("-"," ",$location));
		$seprators=array('-',' ',',');
		foreach ($seprators as $seprator)
			$location =implode($seprator, array_map('ucfirst', explode($seprator, $location)));
	  return $location;
}
function getURLLocation($location){ //Chennai->chennai, Adyar,Chennai->adyar,chennai, West Tambaram-> west-tambaram
	if(!$location)
		return false;
		$location = strtolower(str_replace(" ","-",$location));
		$sperators=array('-',' ',',');
		foreach ($sperators as $sperator)
			$location =implode($sperator, array_map('lcfirst', explode($sperator, $location)));
			return $location;
}
function getLocationIDbyUser($userID=null){
	if(!$userID)
		return false;
		//if loggged is manager get location_id from manager table

		$userLoc = new UserLocation();

		$userLoc = $userLoc->retrieve_one('user_id=?',array($userID));
		return $userLoc->get('location_id');
}
function ellipsis($content,$charLength){
	if(strlen($content)>$charLength)
		return substr($content,0,$charLength-3)."...";
	else 
		return $content;
}