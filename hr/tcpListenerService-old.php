<?php
require_once (dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
require_once ( LIBRARY_PATH. "/SocketServer.class.php"); // Include the File
date_default_timezone_set('Asia/Kolkata');


if(!isset($_GET["ip"])||!isset($_GET['port']))
		die("Enter Valid IP & POrt");

		$ip = trim($_GET["ip"]);
		$port=trim($_GET['port']);
		$close = null;
		
if(isset($_GET["close"]))
	$close=1;
$server = new SocketServer($ip,$port,$close); // Create a Server binding to the given ip address and listen to port 31337 for connections
//$server = new SocketServer("192.168.100.110",1500);
$server->max_clients = 10; // Allow no more than 10 people to connect at a time
$server->hook("CONNECT","handle_connect"); // Run handle_connect every time someone connects
$server->hook("INPUT","handle_input"); // Run handle_input whenever text is sent to the server
$server->infinite_loop(); // Run Server Code Until Process is terminated.



function handle_connect($server,$client,$input){
	
	SocketServer::socket_write_smart($client->socket,"String? ","");
	
}


function handle_input($server,$client,$input){  
 	
	// You probably want to sanitize your inputs here
	$trim = trim($input); // Trim the input, Remove Line Endings and Extra Whitespace.

	$result = explode("&", $trim);
	
	
	
	
	//for date and time conversions
	$dt = $result[3];
	$year=substr($dt,4);
	$month=substr($dt,2,2);
	$date=substr($dt,0,2);
	$ty=$result[4];
	$hr=substr($ty,0,2);
	$min=substr($ty,2,2);
	$sec= substr($ty,4,5);
	
	
	$date_time = date("Y-m-d", strtotime("$date-$month-$year")) . ' ' . date('H:i:s', strtotime("$hr:$min:$sec"));
	//echo $date_time;
	//get the device IP
	
	$device_ip=$client->{"ip"};
	
	
	try {
		// open the connection to the database - $host, $user, $password, $database should already be set
		//connect to the master DB
		$masterconn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
	
	
		// did it work?
		if (mysqli_connect_errno()) {
			throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
		}
	
		
		//get the appropriate company mapped for the device IP
		$query=mysqli_query($masterconn,"SELECT c.company_id,d.company_db_name
				FROM company_biometric_devices_ip c
				INNER JOIN company_details d ON c.company_id = d.company_id
				WHERE device_ip ='$device_ip'");
		
		$devices=array();
		//get the multiple companies for single device
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC))
			$devices[] = $row;
		
		// did it has values?
		if (!$devices) {
			throw new Exception("No Company Found for the device [{$device_ip}]");
		}
		
		//loop and connect it to the company database
		foreach($devices as $device){
			$company_id=$device["company_id"];
			$company_name=DB_PREFIX.$device['company_db_name'];
		
		
		//connect with the company DB Insert the data into the biometrics table
		$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD,$company_name);
		if (mysqli_connect_errno()) {
			throw new Exception("Failed to connect to Company Db [ $company_name]: " . mysqli_connect_error());
		}
		
		
		$stmt=mysqli_prepare($conn, "INSERT INTO employee_biometric (date_time,employee_id,ip,event_seq_no,event_id) 
				(SELECT '$date_time',$result[6],'$device_ip','$result[1]','$result[5]' FROM device_users WHERE ref_id='$result[6]');");
		
		//$stmt = mysqli_prepare ($conn,"INSERT INTO employee_biometric (employee_id,date_time,ip,company_id) VALUES (?,?,?,?)" );
		//mysqli_stmt_bind_param ($stmt, 'ssss',$result[6],$date_time,$client->{"ip"},$company_id);
		
		if (!$res = mysqli_stmt_execute ( $stmt ))
			throw new Exception("MySQL Error: " . mysqli_error ($conn));
		if($res)
			$summary_insert = "CALL ATTENDANCE_SUMMARY_INSERT($result[6],'{$date_time}')";
			$query=mysqli_query($conn,$summary_insert);
			SocketServer::socket_write_smart($client->socket,"[ACK_EVT&{$result[1]}&]");// Send the Client back the String
			SocketServer::socket_write_smart($client->socket,"String? ",""); // Request Another String
		if(mysqli_stmt_affected_rows($stmt)>0)
			break;
	}
	}catch (Exception $e) {
		SocketServer::log($e->getMessage()."String: $trim"." IP: $device_ip",6);
	}
	
	
}

