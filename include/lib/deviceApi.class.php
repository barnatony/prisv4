<?php
class deviceApi {
	var $conn; // connection var
	var $company_db_name;
	var $listeners = array("ip"=>"103.92.200.3","port"=>"23456");
	
function __construct($conn){
	$this->conn = $conn;
}

function setdevicename($ip,$devicename,$username,$password){

	$URL="http://$ip/device.cgi/device-basic-config?action=set&name=$devicename";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_exec($ch);
	if(curl_errno($ch)!=0){
		$errorinfo='Device connection Failed.'.curl_errno($ch);
	}
	curl_close($ch);

	//to call the TCP listener
	$url="http://$ip/device.cgi/tcp-events?action=getevent&ipaddress={$this->listeners["ip"]}&port={$this->listeners["port"]}&trigger=1&keep-live-events=1";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_exec($ch);
	curl_close($ch);
}

function insertIntoDevice($conn,$emp_id,$branch_location,$employee_name) {
	
		$resultSet = array(true,"");
		//get the devices of the branch if no device found return true
			$devices=mysqli_query($this->conn,"SELECT device_id,IP,mac_address,username,password FROM devices WHERE branch_id='$branch_location' ORDER BY enrollment DESC");
			if(mysqli_num_rows($devices)<1)
				return $resultSet;
			
		//insert into device users table
			$rand = mt_rand ( 100000, 999999 );
			$pin = $rand;
			$sql = ( "INSERT INTO device_users (employee_id,pin) VALUES (?,?)" );
			$stmt = mysqli_prepare($this->conn, $sql) or die(mysqli_error($this->conn));
			mysqli_stmt_bind_param ($stmt, 'ss', $emp_id,$pin);
			$res = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
			
		
		//get the reference ID from device_users table
			$ref_id=mysqli_query($this->conn,"SELECT ref_id,employee_id,status,pin FROM device_users WHERE employee_id='$emp_id' LIMIT 0,1");
			$employee=mysqli_fetch_assoc($ref_id);
			$ref_id=$employee["ref_id"];
			$pin=$employee["pin"];
			
			
		//loop through it and create users in all the devices
			while ( $row = mysqli_fetch_array ( $devices, MYSQLI_ASSOC ) ){
				
				$employee_name=str_replace(" ", "%20", substr($employee_name, 0,15));
				$username=trim($row['username']);
				$password=trim($row['password']);
				
				$URL="http://{$row['IP']}/device.cgi/users?action=set&user-id=$emp_id&ref-user-id=$ref_id&name=$employee_name&user-active=1&user-pin=$pin&format=xml";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$URL);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 20 seconds
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
				$code=curl_exec($ch);
				
				
				
				//$error = array();
				//return the error message (which is not boolean) if fails
				if(curl_errno($ch)==0){
					$xml_res=simplexml_load_string($code);
					if(!isset($xml_res->{'Response-Code'})){
						$resultSet [0] = false;
						$resultSet[1] .="Error with the Parameters or Call on Device {}";
					}elseif(isset($xml_res->{'Response-Code'}) && $xml_res->{'Response-Code'} !=0){
						//error occurred from device with error code
						$resultSet [0] = false;
						$resultSet[1] .="Code:$xml_res->{'Response-Code'}";
					}else{
						$resultSet [0] = true;
						$resultSet[1] .="Inserted in Device";
					}
				}
					
				curl_close($ch);
			}	
			
			
				return $resultSet;
	}

	function enrollOnDevice($employee_id,$finger_index,$save){
		 
		//to get the branch_id of the employee
		//get the devices of the branch of the employee & get the default enrolment device too.
		$query=mysqli_query($this->conn, "SELECT w.employee_id,w.employee_name,w.branch_id,d.enrollment,d.username,d.password,d.IP
				FROM employee_work_details w
				INNER JOIN devices d ON w.branch_id = d.branch_id
				WHERE w.employee_id='$employee_id' ORDER BY d.enrollment DESC");
		 
		$devices=array();
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC))
			$devices[] = $row;
			 
			 
			//if nodevice found return true
			if(!$devices)
				return true;
				 
				 
				//get the user from device_user table if no record found then create user
				$user=mysqli_query($this->conn,"SELECT * FROM device_users WHERE employee_id ='$employee_id'");
				$userdetail=mysqli_fetch_assoc($user);
				if($userdetail==NULL){
					$res=$this->insertIntoDevice($this->conn, $employee_id, $devices[0]['branch_id'],$devices[0]['employee_name']);
					if(!$res[0]){
						return $res;
					}
				}
				 
				$status=$userdetail['status'];
				//if user found on device_users then  enroll
				$def_ip = $devices[0]["IP"];
				$def_username=$devices[0]['username'];
				$def_password=$devices[0]['password'];
				 
				//for enrolling
				if(!$save){
					 
					//if user found get his status if "1" its enroll again & if "0" its enrol
					if($status==1){ //status is "1" so enroll again
						foreach ($devices as $device){
							 
							$username=$device['username'];
							$password=$device['password'];
							 
							//delete user_credentials from all the devices
							$URL="http://{$device['IP']}/device.cgi/credential?action=delete&user-id=$employee_id&type=1";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL,$URL);
							curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 10 seconds
							curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
							curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
							curl_exec($ch);
							if(curl_errno($ch)!=0)
								return array(false,'Error failed to delete employee credential from default device.'. curl_error($ch));
								curl_close ($ch);
						}
						//update device_users table with status 0
						$update = ("UPDATE device_users SET status=0,fp1=null,fp2=null,fp3=null,fp4=null,fp5=null WHERE employee_id ='$employee_id' ");
						$stmt = mysqli_prepare($this->conn, $update) or die(mysqli_error($this->conn));
						$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ($this->conn);
						//again call this function
						return self::enrollOnDevice($employee_id,$finger_index,$save);
						 
					}else{ //else it is enroll
						 
						//mulitple finger print recording
						//make an enrol call on the default device
						$finger_count=($finger_index-1);
						$URL="http://{$def_ip}/device.cgi/enrolluser?action=enroll&type=2&user-id=$employee_id&finger-count=$finger_count";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL,$URL);
						curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
						curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
						curl_setopt($ch, CURLOPT_USERPWD, "$def_username:$def_password");
						curl_exec($ch);
						if(curl_errno($ch)!=0)
							return array(false,'Error enroll employee failed.'. curl_error($ch));
							curl_close ($ch);
							return true;
					}
				}else{
					 
					$fingerprintPresent = false;
					$resultSet = array(true);
					$errors =array();
					$err=false;
	
	
					//getting from default enrollment device & Storing from Database
					$updateQuery = "UPDATE device_users SET " ;
					$status = 0;
					$fingerprints =array();
					for($i=1;$i<=$finger_index;$i++){
						 
						$updateQuery .=" fp{$i} = ?, ";
						
						//get from the device
						$URL="http://{$def_ip}/device.cgi/credential?action=get&user-id=$employee_id&type=1&finger-index=$i";
						$get = curl_init();
						curl_setopt($get, CURLOPT_URL,$URL);
						curl_setopt($get, CURLOPT_TIMEOUT, 10); //timeout after 30 seconds
						curl_setopt($get, CURLOPT_RETURNTRANSFER,1);
						curl_setopt($get, CURLOPT_USERPWD, "$def_username:$def_password");
						$fingerprint=curl_exec($get);
						if($errno = curl_errno($get))
							return array(false,'Error communicating Device('.$def_ip.') '. curl_strerror($errno));
						curl_close ($get);
							 
							//if finger print not found write as null else write that finger print on db
							$fingerprint=(preg_match('/[\'^£$%&*()}{@#~?><>,|_+¬]/', $fingerprint))?$fingerprint:null;
	
							if($fingerprint){ //if finger print present here it will come here
								$status =1;
								$fingerprints[] = $fingerprint;
							}else{
								$fingerprints[]=null;
							}
	
					}
	
					$updateQuery .= " status={$status} WHERE employee_id ='$employee_id'";
					//connecting to database
					$dbname = isset ( $_SESSION ['cmpDtSrc'] ) ? $_SESSION ['cmpDtSrc'] : "";
					$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $dbname );
					$this->conn = $conn;
					$stmt = mysqli_prepare($this->conn, $updateQuery) or die(mysqli_error($this->conn));
					$type='ss';
					$params = array_merge(array($type), $fingerprints);
					
					foreach( $params as $key => $value ) {
						$params[$key] = &$params[$key];
					}
					call_user_func_array(array($stmt, "bind_param"), $params);
					$result =  mysqli_stmt_execute ( $stmt ) ? TRUE :mysqli_stmt_error ($stmt);
	
	
					//inserting in all the other devices
	
					//update the fp on other devices if multiple devices found
					if(count($devices)>1)//handling multiple devices
						for($j = 1; $j<count($devices) ; $j++){
						
							$device_ip =$devices[$j]['IP'];
							$username=$devices[$j]['username'];
							$password=$devices[$j]['password'];
							
							//deleting existing credentials
							
							$URL="http://{$device_ip}/device.cgi/credential?action=delete&type=1&user-id=$employee_id&format=xml";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL,$URL);
							curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 10 seconds
							curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
							curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
							curl_exec($ch);
							if($errno = curl_errno($ch)){
								$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}.Communication Error (Delete): ".curl_strerror($errno)." URL: {$URL}";
								continue;
							}
							//loop the fingerprints
							foreach ($fingerprints as $key => $fingerprint) {
								$fp_index = $key+1;
								if(!$fingerprint)
									continue;
								//store the fingerprint in file
								$stream = fopen('php://temp','rw+');
								fwrite($stream, $fingerprint );
								$dataLength = ftell($stream);
								rewind($stream);
								
								//write in the device
								$URL="http://{$device_ip}/device.cgi/credential?action=set&type=1&user-id=$employee_id&format=xml";
								$ch = curl_init();
								curl_setopt($ch,  CURLOPT_CUSTOMREQUEST , 'POST');
								curl_setopt($ch,  CURLOPT_URL ,$URL);
								curl_setopt($ch, CURLOPT_HTTPHEADER , array('Content-Type: text/plain'));
								curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true );
								curl_setopt($ch,CURLOPT_TIMEOUT ,10);
								curl_setopt($ch, CURLOPT_USERPWD ,"$username:$password");
								curl_setopt($ch, CURLOPT_INFILE ,$stream );
								curl_setopt($ch, CURLOPT_INFILESIZE ,$dataLength );
								curl_setopt($ch, CURLOPT_UPLOAD ,1);
								$response = curl_exec($ch);
								
								//handle the errors
								if(curl_errno($ch)!=0)
									$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}.Communication Error : ".curl_error($ch)." URL: {$URL}";
								else{
									//response error handling
									$xml_res=simplexml_load_string($response);
									if($xml_res===false)
										$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}.Call Error Response : {$response}";
									else{
									if(isset($xml_res->{'Response-Code'}) && $xml_res->{'Response-Code'} !=0) //error occurred from device with error code
										$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}. Response Error Code : ".$xml_res->{'Response-Code'};
									}
								}
								curl_close ($ch);
							}
						
						} 
						
					if($status){
						if($errors !=[])
							return  array(false,$errors);
						else 
							return array(true,"Save Successfully.");
					}else
						return array(false,'No fingerprint found for the user');
	
				}
	
	}
	
	
	function importFromDevices($devices,$ref_id_start,$ref_id_end,$finger_index,$source_device=null){
		
		//Get All User Information (From DB)
		//ref ID condition
		if($ref_id_start  && $ref_id_end){
			$condn="WHERE d.ref_id BETWEEN $ref_id_start AND $ref_id_end";
		}elseif($ref_id_start && !$ref_id_end){
			$condn="WHERE d.ref_id >= $ref_id_start";
		}elseif($ref_id_end && !$ref_id_start){
			$condn="WHERE d.ref_id <='$ref_id_end'";
		}else{
			$condn="";
		}
		$users=mysqli_query($this->conn,"SELECT d.ref_id,d.employee_id,w.employee_name,w.employee_lastname,d.fp1,d.fp2,d.fp3,d.fp4,d.fp5,d.status,d.pin
				FROM device_users d
				INNER JOIN employee_work_details w ON w.employee_id=d.employee_id AND w.enabled =1 AND d.status IN (0,1)
				$condn");
	
		$device_users=array();
		while($row = mysqli_fetch_array($users,MYSQLI_ASSOC))
			$device_users[] = $row;
		if(!$device_users)
			return array(false,"No user found on device");
				 
		//Get All Destination Devices Information (From DB)
		$dquery ="SELECT device_id,IP,mac_address,username,password FROM devices WHERE device_id IN (".implode(",", $devices).")";
		$devicesquery=mysqli_query($this->conn,$dquery) or die(mysqli_error($this->conn));
		$devices=array();
		while($dev = mysqli_fetch_array($devicesquery,MYSQLI_ASSOC))
			$devices[] = $dev;
		if(!$devices)
			return array(false,"No Device found on the given ID");
	
		if($source_device){
			//get the source device configuration
			$dquery ="SELECT device_id,IP,mac_address,username,password FROM devices WHERE device_id = '{$source_device}' LIMIT 0,1";
			$result=mysqli_query($this->conn,$dquery) or die(mysqli_error($this->conn));
			$source_device = mysqli_fetch_array($result,MYSQLI_ASSOC);
			if(!$source_device)
				return array(false,"No Source Device found on the given ID");
		}
		$errors =array();
						
		foreach ($device_users as $key => $device_user) {	
			extract($device_user);
			$employee_name =str_replace(" ", "%20", substr($employee_name, 0,15));
			if($source_device){	
				//getting from default enrollment device & Storing to Database
				$updateQuery = "UPDATE device_users SET " ;
				$status = 0;
				$fingerprints=array();	
				for($i=1;$i<=$finger_index;$i++){
	
					$updateQuery .=" fp{$i} = ?, ";
					$def_username = $source_device["username"];
					$def_password = $source_device["password"];
					//get from the device
					 $URL="http://{$source_device['IP']}/device.cgi/credential?action=get&user-id=$employee_id&type=1&finger-index=$i";
					$get = curl_init();
					curl_setopt($get, CURLOPT_URL,$URL);
					curl_setopt($get, CURLOPT_TIMEOUT, 10); //timeout after 30 seconds
					curl_setopt($get, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($get, CURLOPT_USERPWD, "$def_username:$def_password");
					$fingerprint=curl_exec($get);
					
					if($errno = curl_errno($get))
						$error[] = '1.Error communicating Device('.$source_device['IP'].') For '. $employee_id .'Error'. curl_strerror($errno);
					curl_close($get);
						
	
						//if finger print not found write as null else write that finger print on db
						$fingerprint=(preg_match('/[\'^£$%&*()}{@#~?><>,|_+¬]/', $fingerprint))?$fingerprint:null;
	
						if($fingerprint){ //if finger print present here it will come here
							$status =1;
							$fingerprints[] = $fingerprint;
						}else{
							$fingerprints[]=null;
						}
				}
				//update the fingerprints in database
				 $updateQuery .= " status={$status} WHERE employee_id ='$employee_id'";
				
				//connecting to database for every user transaction
				$dbname = isset ( $_SESSION ['cmpDtSrc'] ) ? $_SESSION ['cmpDtSrc'] : "";
				$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $dbname );
				$this->conn = $conn;
	
				$stmt = mysqli_prepare($this->conn, $updateQuery) or die(mysqli_error($this->conn));
				
				
				$type='ss';
				$params = array_merge(array($type), $fingerprints);
				
				foreach( $params as $key => $value ) {
					$params[$key] = &$params[$key];
				}
				call_user_func_array(array($stmt, "bind_param"), $params);
				/*foreach ($fingerprints as $k=>$fp){
					echo "bind:".$k;
					mysqli_stmt_bind_param ($stmt,'s',$fp);
				}*/
				$result =  mysqli_stmt_execute ( $stmt ) ? TRUE :mysqli_stmt_error ($stmt);
				//var_dump($result);
			}else 
				$fingerprints=array($fp1,$fp2,$fp3,$fp4,$fp5);
				
				
					
				//inserting credential in all the destination devices
				for($j = 0; $j<count($devices) ; $j++){

					$device_ip =$devices[$j]['IP'];
					$username=$devices[$j]['username'];
					$password=$devices[$j]['password'];

					//deleting existing credentials

					$URL="http://{$device_ip}/device.cgi/credential?action=delete&type=1&user-id=$employee_id&format=xml";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,$URL);
					curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 10 seconds
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
					$response = curl_exec($ch);
					if($errno = curl_errno($ch)){
						$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}.Communication Error (Delete): ".curl_strerror($errno)." URL: {$URL}";
						continue;
					}else{
						//response error handling
						$xml_res=simplexml_load_string($response);
						if($xml_res===false){
							$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}.Call Error, Response : {$response}";
							continue;
						}else
							if(isset($xml_res->{'Response-Code'}) && $xml_res->{'Response-Code'} ==13){ //USER ID NOT FOUND FROM Device then Create the User in the Device
								//create user into given devices
								$URL="http://{$device_ip}/device.cgi/users?action=set&user-id={$device_user['employee_id']}&ref-user-id={$device_user['ref_id']}&name={$employee_name}&user-active=1&user-pin={$device_user['pin']}&format=xml";
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL,$URL);
								curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 20 seconds
								curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
								curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
								$response=curl_exec($ch);
								//request error handling
								if($errno = curl_errno($ch)!=0){
									$errors[] = "{$device_ip}:Device Communication Failed. Error:". curl_strerror($errno);
									curl_close ($ch);
									continue;
								}
								//response error handling
								$insert_res=simplexml_load_string($response);
								if($insert_res===false){
									$errors [] = "Failed to Create User in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}.Call Error, Response : {$response}";
									curl_close ($insert);
									continue;
								}else
									if(isset($insert_res->{'Response-Code'}) && $insert_res->{'Response-Code'} !=0){
										$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}. Response Error Code : ".$insert_res->{'Response-Code'};
										curl_close ($insert);
										continue;
									}
	
							}
						 
					}
					
					//if no fingerprints found on the device continue to next user
					if($source_device && $status==0){
						$errors[] = "No Fingerprints found for the user Ref ID {{$ref_id}}, Employee ID: {{$employee_id}}, Name: {{$employee_name}}";
						continue;
					}
					
					//loop the fingerprints
					foreach ($fingerprints as $key => $fingerprint) {
						
						if(!$fingerprint)
							continue;
							//store the fingerprint in file
							$status=1;
							$stream = fopen('php://temp','rw+');
							fwrite($stream, $fingerprint );
							$dataLength = ftell($stream);
							rewind($stream);

							//write in the device
							$URL="http://{$device_ip}/device.cgi/credential?action=set&type=1&user-id=$employee_id&format=xml";
							$ch = curl_init();
							curl_setopt($ch,  CURLOPT_CUSTOMREQUEST , 'POST');
							curl_setopt($ch,  CURLOPT_URL ,$URL);
							curl_setopt($ch, CURLOPT_HTTPHEADER , array('Content-Type: text/plain'));
							curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true );
							curl_setopt($ch,CURLOPT_TIMEOUT ,10);
							curl_setopt($ch, CURLOPT_USERPWD ,"$username:$password");
							curl_setopt($ch, CURLOPT_INFILE ,$stream );
							curl_setopt($ch, CURLOPT_INFILESIZE ,$dataLength );
							curl_setopt($ch, CURLOPT_UPLOAD ,1);
							$response = curl_exec($ch);

							//handle the errors
							if(curl_errno($ch)!=0)
								$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}.Communication Error : ".curl_error($ch)." URL: {$URL}";
								else{
									//response error handling
									$xml_res=simplexml_load_string($response);
									if($xml_res===false)
										$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}.Call Error Response : {$response}";
										else{
											if(isset($xml_res->{'Response-Code'}) && $xml_res->{'Response-Code'} !=0) //error occurred from device with error code
												$errors [] = "Not Saved in Device ({$device_ip}). Employee: {$employee_id}. Finger Print : {$fp_index}. Response Error Code : ".$xml_res->{'Response-Code'};
										}
								}
								curl_close ($ch);
					}
				} //devices LoopEND
		}//device users LoopEND
						 
						
							if($errors !=[])
								return  array(false,$errors);
								else
									return array(true,"Save Successfully.");
						
	}
	
/*function enrollOnDevice1($employee_id,$finger_index,$save){
	
		//to get the branch_id of the employee
		//get the devices of the branch of the employee & get the default enrolment device too.
		$query=mysqli_query($this->conn, "SELECT w.employee_id,w.employee_name,w.branch_id,d.enrollment,d.username,d.password,d.IP 
											FROM employee_work_details w
                      						INNER JOIN devices d ON w.branch_id = d.branch_id
                      						WHERE w.employee_id='$employee_id' ORDER BY d.enrollment DESC");
		
		$devices=array();
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC))
			$devices[] = $row;
	
		
		//if nodevice found return true
		if(!$devices)
			return true;
		
		
		//get the user from device_user table if no record found then create user 
		$user=mysqli_query($this->conn,"SELECT * FROM device_users WHERE employee_id ='$employee_id'");
		$userdetail=mysqli_fetch_assoc($user);
		if($userdetail==NULL){
			$res=$this->insertIntoDevice($this->conn, $employee_id, $devices[0]['branch_id'],$devices[0]['employee_name']);
			if(!$res[0]){
				return $res;
			}
		}
		
			$status=$userdetail['status'];
		//if user found on device_users then  enroll
			$def_username=$devices[0]['username'];
			$def_password=$devices[0]['password'];
		
		//for enrolling 
		if(!$save){
			
			//if user found get his status if "1" its enroll again & if "0" its enrol
			if($status==1){ //status is "1" so enroll again
				foreach ($devices as $device){
				
					$username=$device['username'];
					$password=$device['password'];
				
					//delete user_credentials from all the devices
					$URL="http://{$device['IP']}/device.cgi/credential?action=delete&user-id=$employee_id&type=1";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,$URL);
					curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 10 seconds
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
					curl_exec($ch);
					if(curl_errno($ch)!=0)
						return array(false,'Error failed to delete employee credential from default device.'. curl_error($ch));
						curl_close ($ch);
				}
				//update device_users table with status 0
				$update = ("UPDATE device_users SET status=0,fp1=null,fp2=null,fp3=null,fp4=null,fp5=null WHERE employee_id ='$employee_id' ");
				$stmt = mysqli_prepare($this->conn, $update) or die(mysqli_error($this->conn));
				$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ($this->conn);
				//again call this function
				return self::enrollOnDevice($employee_id,$finger_index,$save);
					
				}else{ //else it is enroll
				
				//mulitple finger print recording 
				//make an enrol call on the default device
				 $finger_count=($finger_index-1);
				 $URL="http://{$devices[0]['IP']}/device.cgi/enrolluser?action=enroll&type=2&user-id=$employee_id&finger-count=$finger_count";
				 $ch = curl_init();
				 curl_setopt($ch, CURLOPT_URL,$URL);
				 curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				 curl_setopt($ch, CURLOPT_USERPWD, "$def_username:$def_password");
				 curl_exec($ch);
				 if(curl_errno($ch)!=0)
				 	return array(false,'Error enroll employee failed.'. curl_error($ch));
				 curl_close ($ch);
				 return true;
				}		 
		}else{
		//Set timelimit to 120sec
		
		set_time_limit ( 180);
		
		$fingerprintPresent = false;
		$resultSet = array(true);
		$errors =array();
		$err=false;
		//looping the finger-index	
		for($i=1;$i<=$finger_index;$i++){
			$fp='fp'.$i;
			//make a getcredential call to default device
			
			$URL="http://{$devices[0]['IP']}/device.cgi/credential?action=get&user-id=$employee_id&type=1&finger-index=$i";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$URL);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 30 seconds
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_USERPWD, "$def_username:$def_password");
			$fingerprint=curl_exec($ch);
			curl_close ($ch);
			
			//if finger print not found write as null else write that finger print on db
				$fingerprint=(preg_match('/[\'^£$%&*()}{@#~?><>,|_+¬]/', $fingerprint))?$fingerprint:null;
			
				
				
			
			//write that finger print data and update status to 1 on device_users
				if($fingerprint){
						$fingerprintPresent =true;
						
						//update the fp on db
							$sql = "UPDATE device_users SET status=1,$fp= ? WHERE employee_id ='$employee_id'";
							
							$dbname = isset ( $_SESSION ['cmpDtSrc'] ) ? $_SESSION ['cmpDtSrc'] : "";
							$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $dbname );
							$this->conn = $conn;
							$stmt = mysqli_prepare($this->conn, $sql) or die(mysqli_error($this->conn));
							mysqli_stmt_bind_param ($stmt,'s',$fingerprint);
							$result =  mysqli_stmt_execute ( $stmt ) ? TRUE :mysqli_stmt_error ($stmt);;
							
							//update the fp on other devices if multiple devices found
							if(count($devices)>1){//handling multiple devices
						
						
							//enroll a user in device by setting user-credential
							$stream = fopen('php://temp','rw+');
							fwrite($stream, $fingerprint );
							$dataLength = ftell($stream);
							rewind($stream);
							
							
							for($j = 1; $j<count($devices) ; $j++){
							
								$username=$devices[$j]['username'];
								$password=$devices[$j]['password'];
								
								$URL="http://{$devices[$j]['IP']}/device.cgi/credential?action=set&type=1&user-id=$employee_id&format=xml";
								$ch = curl_init();
								curl_setopt($ch,  CURLOPT_CUSTOMREQUEST , 'POST');
								curl_setopt($ch,  CURLOPT_URL ,$URL);
								curl_setopt($ch, CURLOPT_HTTPHEADER , array('Content-Type: text/plain'));
								curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true );
								curl_setopt($ch,CURLOPT_TIMEOUT ,10);
								curl_setopt($ch, CURLOPT_USERPWD ,"$username:$password");
								curl_setopt($ch, CURLOPT_INFILE ,$stream );
								curl_setopt($ch, CURLOPT_INFILESIZE ,$dataLength );
								curl_setopt($ch, CURLOPT_UPLOAD ,1);
								$response = curl_exec($ch);
							
								if(curl_errno($ch)!=0)
									$errors [] = 'Error not able to set Finger print '.$i.' on device.URL:'.$URL." cURLError:". curl_error($ch)." Response:".$response;
								else{
									//response error handling
									$xml_res=simplexml_load_string($response);
									if(!isset($xml_res->{'Response-Code'})){
										$errors[] = "{$devices[$j]['IP']}:Error with the Parameters or Call on Device Response:".$response;
									}elseif(isset($xml_res->{'Response-Code'}) && $xml_res->{'Response-Code'} !=0){
										//error occurred from device with error code
										$errors[] ="{$devices[$j]['IP']}:Code: ".$xml_res->{'Response-Code'}." for user {$device_user['employee_id']}";
									}
								}
									
								curl_close ($ch);
							}
						}	
					
				}
			}
		
			if($fingerprintPresent){
				$resultSet[]=$errors;
				return $resultSet;
			}else 
				return array(false,'No fingerprint found for the user');



		
		}
			
} */
		
	
	
function setinactive($employee_id){
	
	//get the devices of the branch of the employee from devices table 
	$devicesquery=mysqli_query($this->conn,"SELECT du.employee_id,w.employee_name,du.ref_id, w.branch_id, d.IP device_ip, d.username, d.password, d.enrollment
											FROM device_users du
											INNER JOIN employee_work_details w	
											ON du.employee_id = w.employee_id
											INNER JOIN devices d 
											ON w.branch_id = d.branch_id
											WHERE du.employee_id ='$employee_id'
											ORDER BY d.enrollment DESC;");
	$devices=array();
	while($row = mysqli_fetch_array($devicesquery,MYSQLI_ASSOC))
		$devices[] = $row;

	//no device found for the user	
	if(!$devices)
		return true;
		
	foreach ($devices as $device){
		$username=$device['username'];
		$password=$device['password'];
		
		//make user in-active on device
		$URL="http://{$device['device_ip']}/device.cgi/users?action=set&user-id={$employee_id}&ref-user-id={$device['ref_id']}&name={$device['employee_name']}&user-active=0";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_exec($ch);
		curl_close ($ch);
		
		
	}
	
	
	
	
	//update device_users with status -1
	$query = ("UPDATE device_users SET status=-1 WHERE employee_id = '{$employee_id}'");
	$stmt = mysqli_prepare($this->conn, $query) or die(mysqli_error($this->conn));
	$res = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );	
	return $res;
}


function deleteonDevice($employeeId){
	$query = "SELECT du.employee_id ,du.ref_id, w.branch_id, d.IP device_ip, d.username, d.password, d.enrollment
											FROM device_users du
											INNER JOIN employee_work_details w
											ON du.employee_id = w.employee_id
											INNER JOIN devices d 
											ON w.branch_id = d.branch_id
											WHERE du.employee_id ='{$employeeId}'
										ORDER BY d.enrollment DESC";
	$devicesquery=mysqli_query($this->conn,$query);
	$devices=array();
	while($row = mysqli_fetch_array($devicesquery,MYSQLI_ASSOC))
		$devices[] = $row;
	if(!$devices){
		return true;
	}
		foreach ($devices as $device){
			$username=$device['username'];
			$password=$device['password'];
			$ip=$device['device_ip'];
	
			
	//make a delete call to the device
		$URL="http://$ip/device.cgi/users?action=delete&user-id={$employeeId}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 10 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_exec($ch);
		
		curl_close ($ch);
		}
	
	//delete user from device_users
	$query="DELETE FROM  device_users WHERE employee_id='{$employeeId}'";
	$stmt = mysqli_prepare($this->conn, $query) or die(mysqli_error($this->conn));
	$res = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
	
	return $res;
}


function getdeviceinfo($ip){
	
	//get the devices of the branch
	$query=mysqli_query($this->conn, "SELECT * FROM devices WHERE IP='$ip'");
	$device=mysqli_fetch_assoc($query);



	$username=$device['username'];
	$password=$device['password'];

	//to get total number of users
	$URL="http://{$device['IP']}/device.cgi/command?action=getusercount&format=xml";
	echo $URL; die();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	$users=curl_exec($ch);
	

	if(curl_errno($ch)==0){
		$total= simplexml_load_string($users);
		$total_users=$total->{'Enrolled-User-Count'};
	}else{
		$total_users='Device Connection Failed.'. curl_error($ch);
	}


	curl_close($ch);



	//to get total number of punches
	$URL="http://{$device['IP']}/device.cgi/command?action=geteventcount&format=xml";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	$code=curl_exec($ch);
	if(curl_errno($ch)==0){
		$total = simplexml_load_string($code);
		$total_punches=$total->{'Seq-Number'};
	}else{
		
		$total_punches =  'Device Connection Failed.'. curl_error($ch);
	}

	curl_close($ch);

	$count=$total_users.','.$total_punches;
	return $count;
}

function tcpListenerSet($ip,$listenerip,$listenerport){
	
	//get the username password of that device
	$query=mysqli_query($this->conn, "SELECT * FROM devices WHERE IP='$ip'");
	$device=mysqli_fetch_assoc($query);
	$username=$device['username'];
	$password=$device['password'];

	//to call the TCP listener
	$url="http://$ip/device.cgi/tcp-events?action=getevent&ipaddress=$listenerip&port=$listenerport&trigger=1&keep-live-events=1";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_exec($ch);
	if(curl_errno($ch)!=0){
		return false;
	}
	curl_close($ch);

	return true;

}

function importIntoDevices($devices,$ref_id_start,$ref_id_end,$finger_count){
	
	$resultSet = array(true);
	$errors=array();
	
	//based on given start and end get the data from device_users
	if($ref_id_start  && $ref_id_end){
		$condn="WHERE d.ref_id BETWEEN $ref_id_start AND $ref_id_end";
	}elseif($ref_id_start && !$ref_id_end){
		$condn="WHERE d.ref_id >= $ref_id_start";
	}elseif($ref_id_end && !$ref_id_start){
		$condn="WHERE d.ref_id <='$ref_id_end'";
	}else{
		$condn="";
	}
	
	
	//to get the device_users 
	$users=mysqli_query($this->conn,"SELECT d.ref_id,d.employee_id,w.employee_name,w.employee_lastname,d.fp1,d.fp2,d.fp3,d.fp4,d.fp5,d.status,d.pin
			FROM device_users d
			LEFT JOIN employee_work_details w ON w.employee_id=d.employee_id
			$condn");
	
	
	$device_users=array();
	while($row = mysqli_fetch_array($users,MYSQLI_ASSOC))
		$device_users[] = $row;
		if(!$device_users)
			return array(false,"No user found on device");
				
			$dquery ="SELECT device_id,IP,mac_address,username,password FROM devices WHERE device_id IN (".implode(",", $devices).")";
			$devicesquery=mysqli_query($this->conn,$dquery) or die(mysqli_error($this->conn));
			$bio_devices=array();
			while($dev = mysqli_fetch_array($devicesquery,MYSQLI_ASSOC))
			$bio_devices[] = $dev;
			if(!$bio_devices)
				return array(false,"No Device found on the given ID");
			
				
	//for device insert 	
	foreach($bio_devices as $device){
		
		foreach($device_users as $device_user){
			$employee_name=str_replace(" ", "%20", substr($device_user['employee_name'], 0,15));
			$username=trim($device['username']);
			$password=trim($device['password']);
			
			//create user into given devices
		    $URL="http://{$device['IP']}/device.cgi/users?action=set&user-id={$device_user['employee_id']}&ref-user-id={$device_user['ref_id']}&name={$employee_name}&user-active=1&user-pin={$device_user['pin']}&format=xml";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$URL);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 20 seconds
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			$response=curl_exec($ch);
			//request error handling
			if(curl_errno($ch)!=0){
				$errors[] = "{$device["IP"]}:Device Communication Failed. Error:". curl_error($ch);
				curl_close ($ch);
				continue;
			}
			//response error handling
			$xml_res=simplexml_load_string($response);
			if(!isset($xml_res->{'Response-Code'})){
				$errors[] = "{$device["IP"]}:Error with the Parameters or Call on Device";
				curl_close ($ch);
				continue;
			}elseif(isset($xml_res->{'Response-Code'}) && $xml_res->{'Response-Code'} !=0){
				//error occurred from device with error code
				$errors[] ="{$device["IP"]}:Code:".$xml_res->{'Response-Code'}." for user {$device_user['employee_id']}";
				curl_close ($ch);
				continue;
			}
			curl_close ($ch);
				
		
		for($i=1;$i<=$finger_count;$i++){
				$fp='fp'.$i;
				$fingerprint=$device_user[$fp];
			
				$stream = fopen('php://temp','rw+');
				fwrite($stream, $fingerprint );
				$dataLength = ftell($stream);
				rewind($stream);
		
		 	//set the credential into given devices
				 $URL="http://{$device['IP']}/device.cgi/credential?action=set&type=1&user-id={$device_user['employee_id']}&format=xml";
				 $ch = curl_init();
				 curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');
				 curl_setopt($ch,CURLOPT_URL ,$URL);
				 curl_setopt($ch,CURLOPT_HTTPHEADER , array('Content-Type: text/plain'));
				 curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true );
				 curl_setopt($ch,CURLOPT_TIMEOUT ,30);
				 curl_setopt($ch,CURLOPT_USERPWD ,"$username:$password");
				 curl_setopt($ch,CURLOPT_INFILE ,$stream );
				 curl_setopt($ch,CURLOPT_INFILESIZE ,$dataLength );
				 curl_setopt($ch,CURLOPT_UPLOAD ,1);
				 $response = curl_exec($ch);
				 //request error handling
				 if(curl_errno($ch)!=0){
					$errors[] = "{$device["IP"]}:Device Communication Failed. Error:". curl_error($ch);
					curl_close ($ch);
					continue;
				}
				

				//response error handling
				$xml_res=simplexml_load_string($response);
				if(!isset($xml_res->{'Response-Code'})){
					$errors[] = "{$device["IP"]}:Error with the Parameters or Call on Device";
					curl_close ($ch);
					continue;
				}elseif(isset($xml_res->{'Response-Code'}) && $xml_res->{'Response-Code'} !=0){
					//error occurred from device with error code
					$errors[] ="{$device["IP"]}:Code: $xml_res->{'Response-Code'} for user {$device_user['employee_id']}";
					curl_close ($ch);
					continue;
				}
				curl_close ($ch);
				
				 
			}
		}
	}
	
	$resultSet[]=$errors;
	
	return $resultSet;
}

function device_sync($device_capacity,$device_id){
	
	//case 1 = 0,1,5,1,2 starts from 0,3,0,4 omit (0,1,0,2)
	//case 2 = 0,1,5,2,2 starts from 1,3,1,4 omit (0,1,0,2,0,3,0,4,0,5,1,1,1,2)
	//case 3 = 1,1,5,1,2 starts from 1,1,1,2 directly starts from (1,1,1,2)
	
	//to get device data from devices
	$dQuery=mysqli_query($this->conn, "SELECT * FROM devices WHERE IP ='{$device_id}' LIMIT 0,1");
	$devices=mysqli_fetch_assoc($dQuery);
	$device_ip=$devices['IP'];
	$username=$devices['username'];
	$password=$devices['password'];
	
	//no device found
	if(!$devices)
		return true;
	
		$errors =array();
	
		//check from database device_sync if no data found give the roll_over and seq_num as 0 and 1 else get that from database
		$query=mysqli_query($this->conn, "SELECT device_id,sync_dt,sync_upto,roll_over_count,sync_by FROM device_sync WHERE device_id ='{$device_id}' ORDER BY sync_dt DESC LIMIT 0,1");
		$devices_sync=mysqli_fetch_assoc($query);
	
		if(!$devices_sync){
			$sync_roll_over=0;
			$sync_seq_num=1;
		}else{
			$sync_seq_num=$devices_sync['sync_upto'];
			$sync_roll_over=$devices_sync['roll_over_count'];
		}
	
		
			//to get last sequence number and roll-over-count count from device
	
			$URL="http://{$device_ip}/device.cgi/command?action=geteventcount&format=xml";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$URL);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 30 seconds
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			$code=curl_exec($ch);
			if(curl_errno($ch)==0){
				$total = simplexml_load_string($code);
				$d_seq_num=$total->{'Seq-Number'};
				$d_roll_over=$total->{'Roll-Over-Count'};
					
			}else{ //errror handling
				$d_seq_num =  'Device Connection Failed.'. curl_error($ch);
			}
			curl_close($ch);
	
			if(isset($sync_seq_num) && isset($sync_roll_over)){
				
			while($sync_roll_over <= $d_roll_over){
	
				if($sync_roll_over >= $d_roll_over && $sync_seq_num >= $d_seq_num) //no punches there it exits
					break;
				
				if($sync_seq_num == $device_capacity){
						$sync_seq_num = 1;
						$sync_roll_over ++;
					}else
						$sync_seq_num++;
							
						//set maximum execution time
						set_time_limit (180);
	
				 		$url="http://{$device_ip}/device.cgi/events?action=getevent&roll-over-count=$sync_roll_over&seq-number=$sync_seq_num&no-of-events=1&format=xml";
						//echo $url.'</br>';
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL,$url);
						curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 30 seconds
						curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
						curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
						$sync_datas=curl_exec($ch);
						if(curl_errno($ch)==0){
							$sync_datas = simplexml_load_string($sync_datas);
							$seq_num=$sync_datas->Events->{'seq-No'};
							$date=$sync_datas->Events->{'date'};
							$time=$sync_datas->Events->{'time'};
							$event_id=$sync_datas->Events->{'event-id'};
							$ref_id=$sync_datas->Events->{'detail-1'};
	
						}else{ //error handling
							$errors[] = "{$device_ip}:Device Communication Failed. Error:". curl_strerror($errno);
						}
						curl_close($ch);
							
						if(!isset($sync_datas->{'Response-Code'})=='10' && $ref_id != 0){
							$date=str_replace('/','-', $date);
							$date_time=date('Y-m-d',strtotime($date)).' '.$time;
								
							$masterconn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
							
							//to get datas from employee_biometric table for comparison
							//compare by event_id and user_id and date_time and seq_no
							
							//if not exists in employee_biometric table then insert it on table
							
								//echo 'helo'."<br>";
								
								
								$arr = explode(":", $device_ip);
								$trim_ip = $arr[0];
								//get the appropriate company mapped for the device IP
								$query=mysqli_query($masterconn,"SELECT c.company_id,d.company_db_name
										FROM company_biometric_devices_ip c
										INNER JOIN company_details d ON c.company_id = d.company_id
										WHERE c.device_ip ='$trim_ip'");
								$mdevices=array();
								//get the multiple companies for single device
								while($row = mysqli_fetch_array($query,MYSQLI_ASSOC))
									$mdevices[] = $row;
									// did it has values?
									if (!$mdevices) {
										$errors[] = "{$device_ip}:No Company Found for the device ";
									}
									
									foreach($mdevices as $mdevice){
										$company_id=$mdevice["company_id"];
										$company_name=DB_PREFIX.$mdevice['company_db_name'];
									
										//echo $company_name."<br>";
										//connect with the company DB Insert the data into the biometrics table
										$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD,$company_name);
										if (mysqli_connect_errno()) {
											$errors[] = "Failed to connect to Company Db [ $company_name]: " . mysqli_connect_error();
											
										}
										
										$Equery=mysqli_query($conn, "SELECT * FROM employee_biometric WHERE event_seq_no='{$seq_num}' AND date_time='{$date_time}' AND employee_id='{$ref_id}' AND event_id={$event_id}");
										$empData=mysqli_fetch_assoc($Equery);
											 
										//then insert into employee biometric table
										//echo 'inserted'."</br>";
										if(!$empData){
										$sql=mysqli_prepare($conn, "INSERT INTO employee_biometric (date_time,employee_id,ip,event_seq_no)
												(SELECT '$date_time','$ref_id','$device_ip','$seq_num' FROM device_users WHERE ref_id='$ref_id');");
										//$sql=mysqli_prepare($this->conn, "INSERT INTO employee_biometric (date_time,employee_id,ip,event_seq_no) VALUES ('$date_time','$ref_id','$device_ip','$seq_num')");
										//print_r("INSERT INTO employee_biometric (date_time,employee_id,ip,event_seq_no) VALUES ('$date_time','$ref_id','$device_ip','$seq_num')");
										$res = mysqli_stmt_execute ( $sql ) ? TRUE : mysqli_error ($sql );
										
										$summary_insert = "CALL ATTENDANCE_SUMMARY_INSERT($ref_id,'{$date_time}')";
										$query=mysqli_query($conn,$summary_insert);
										}
									
									}
							
	
						}
						
			}}
			
			if($devices_sync['device_id']==$device_id && $devices_sync['roll_over_count']==$d_roll_over&& $devices_sync['sync_upto']==$d_seq_num){
				$errors[]="Device :"  .($device_ip). " is Already Synced Uptodate";
				return  array(false,'Synced');
				exit;
			}
			foreach($mdevices as $mdevice){
				$company_id=$mdevice["company_id"];
				$company_name=DB_PREFIX.$mdevice['company_db_name'];
					
				//echo $company_name."<br>";
				//connect with the company DB Insert the data into the biometrics table
				$connT = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD,$company_name);
		
		//Insert the device-sequence number (last_sync_value) into device_sync table 
		$today=date("Y-m-d H:i:s");
		//echo 'last inserted';
		//echo $d_seq_num;
		//echo $d_roll_over;
		$dsql =("INSERT INTO device_sync (device_id,sync_dt,sync_upto,roll_over_count) VALUES (?,?,?,?)");
		$Stmt = mysqli_prepare($connT, $dsql) or die(mysqli_error($connT));
		mysqli_stmt_bind_param ($Stmt, 'ssss', $device_id,$today,$d_seq_num,$d_roll_over);
		$result = mysqli_stmt_execute ( $Stmt ) ? TRUE : mysqli_error ( $connT );
			}
		
		//For handling Errors
		if($errors !=[])
			return  array(false,$errors);
		else
			return array(true,"Synced Successfully.");
	
}

}



