<?php
session_start ();
if (isset ( $_SERVER ['HTTP_COOKIE'] )) {
	$cookies = explode ( ';', $_SERVER ['HTTP_COOKIE'] );
	foreach ( $cookies as $cookie ) {
		$parts = explode ( '=', $cookie );
		$name = trim ( $parts [0] );
		setcookie ( $name, '', time () - 1000 );
		setcookie ( $name, '', time () - 1000, '/' );
	}
}
foreach ( $_SESSION as $key ) {
	echo $key = '';
}

$_SESSION = array ();
session_unset ();
session_destroy ();

header ( "Location: ../" );

die ();
?>

