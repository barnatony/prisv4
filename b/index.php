<?php
//===============================================
// Debug
//===============================================
ini_set('display_errors','On');
error_reporting(E_ALL);
//phpinfo();
//===============================================
// mod_rewrite
//===============================================
//Please configure via .htaccess or httpd.conf

//===============================================
// Madatory KISSMVC Settings (please configure)
//===============================================
//if ( !defined('ABSPATH') )
	//define('ABSPATH', dirname(__FILE__) . '/');

define('APP_PATH','app/'); //with trailing slash pls
define('WEB_FOLDER','/prisv4/b/'); //with trailing slash pls

//===============================================
// Other Settings
//===============================================
define('WEB_DOMAIN','localhost'); //with http:// and NO trailing slash pls
define('VIEW_PATH','app/views/'); //with trailing slash pls

//===============================================
// Includes
//===============================================
require('bassmvc.php');
require(APP_PATH.'inc/functions.php');

//===============================================
// Session
//===============================================
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
date_default_timezone_set ( "Asia/Kolkata" );
//===============================================
// Globals
//===============================================
$GLOBALS['sitename']='Test PRISv4';

//===============================================
// Uncaught Exception Handling
//===============================================s
set_exception_handler('uncaught_exception_handler');

function uncaught_exception_handler($e) {
	ob_end_clean(); //dump out remaining buffered text
	$vars['message']=$e;
	die(View::do_fetch(APP_PATH.'errors/exception_uncaught.php',$vars));
}

function custom_error($errorNo="",$msg='') {
	$vars['msg']=$msg;
	die(View::do_fetch(VIEW_PATH.'errors/custom_error.php',$vars));
}

//===============================================
// Database
//===============================================
function getdbh() {
	//local
	
	$host="localhost";
	$dbname = "c1prismaster";
	$username ="root";
	$password = "hrlabz";
	
	// production & test
/*	$host="108.170.32.123";
	$dbname = "prisxyz_master";
	$username ="prisxyz_pris";
	$password = "Bassbiz@12";*/
	
	if (!isset($GLOBALS['dbh']))
		try {
			$mdbh = $GLOBALS['mdbh'] = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
			$stmt = $mdbh->prepare('SELECT company_db_name,company_host,company_db_user,company_db_password FROM company_details WHERE company_id = :company_id');
			if(!isset($_SESSION['company_id']) && $_REQUEST['cid'])
				$_SESSION["company_id"] = base64_decode($_REQUEST["cid"]);
			$stmt->bindParam('company_id', $_SESSION['company_id']);
			$stmt->execute();
			$dbprop = $stmt->fetch();
			
			if($dbprop["company_host"] && $dbprop["company_db_user"] && $dbprop["company_db_password"]){
				$host=$dbprop["company_host"];
				$username = $dbprop["company_db_user"];
				$password = $dbprop["company_db_password"];
				//production
				$dbname = "c1pris".$dbprop['company_db_name'];
				//local
				//$dbname =$dbprop['company_db_name'];
			}else 
				$dbname = $dbprop['company_db_name'];
			$GLOBALS['dbh'] = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
			
			
	} catch (PDOException $e) {
		die('Connection failed: '.$e->getMessage());
	}
	return $GLOBALS['dbh'];
}

//===============================================
// Autoloading for Business Classes
//===============================================
// Assumes Model Classes start with capital letters and Helpers start with lower case letters

function app_autoload($classname ){
	$a=$classname[0];
	if ($a >= 'A' && $a <='Z')
		$classFilePath = APP_PATH.'models/'.$classname.'.php';
		else
			$classFilePath = APP_PATH.'helpers/'.$classname.'.php';
			if ((file_exists($classFilePath) === FALSE) || (is_readable($classFilePath) === FALSE))//    Can't load
				return FALSE;

				require_once($classFilePath);
}
spl_autoload_register("app_autoload");
//===============================================
// Start the controller
//===============================================
$controller = new Controller(APP_PATH.'controllers/',WEB_FOLDER,'main','index');

