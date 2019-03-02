<?php
//error_reporting(0);
require_once (__DIR__ . "/functions.php");
defined ( "LIBRARY_PATH" ) or define ( "LIBRARY_PATH", realpath ( dirname ( __FILE__ ) . '/lib' ) );

defined ( "COMPANY_PATH" ) or define ( "COMPANY_PATH", realpath ( dirname ( dirname ( __FILE__ ) ) . '/compDat' ) );


 defined("MASTER_DB_NAME")
 //or define('MASTER_DB_NAME', 'c1prismaster');
 or defined ( "MASTER_DB_NAME" ) or define ( 'MASTER_DB_NAME', 'c1prismaster' );
 
 defined("MASTER_DB_USER")
 //or define('MASTER_DB_USER', 'c1pris');
 or defined ( "MASTER_DB_USER" ) or define ( 'MASTER_DB_USER', 'root' );
 
 defined("MASTER_DB_PASSWORD")
 //or define('MASTER_DB_PASSWORD', 'Bass@1987');
 or defined ( "MASTER_DB_PASSWORD" ) or define ( 'MASTER_DB_PASSWORD', 'hrlabz' );
 
 defined("MASTER_DB_HOST")
 //or define('MASTER_DB_HOST', '103.92.200.3');
 or define('MASTER_DB_HOST', 'localhost');
 
 defined("DB_PREFIX")
//or define('DB_PREFIX', 'c1pris');
or define('DB_PREFIX', 'c1pris');

defined("MEMORY_LIMIT")
or define('MEMORY_LIMIT', '512M');
defined("MAX_EXECUTION_TIME")
or define('MAX_EXECUTION_TIME', '120');
defined("DEFAULT_TIMEZONE")
or define('DEFAULT_TIMEZONE', 'GMT');

if (session_id () == "") {

	session_start ();
}

$dbname = isset ( $_SESSION ['cmpDtSrc'] ) ? $_SESSION ['cmpDtSrc'] : "";
$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $dbname );

?>