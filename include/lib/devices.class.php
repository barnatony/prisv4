<?php
require_once ( LIBRARY_PATH. "/deviceApi.class.php"); // Include the File
class devices{
	/* Member variables */
	var $conn;
	function __construct($conn) {
		$this->conn = $conn;
	}
	function __destruct() {
		//mysqli_close ( $this->conn );
	}
	/* Member functions */
	/* Insert device data */
	function insert($deviceName,$deviceIp,$deviceMac,$device_loc,$username,$password,$enableEnroll) {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO devices( device_name,IP, mac_address, branch_id,username,password,enrollment)  VALUES (?,?,?,?,?,?,?);" );
		mysqli_stmt_bind_param ( $stmt, 'sssssss', $deviceName,$deviceIp,$deviceMac, $device_loc,$username,$password,$enableEnroll);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		    if($result===true && (isset($result['result'])?$result['result']:true)===true)
			      return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
			  else
				  return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	function getDevicedata(){ 
		$a_json = array();
		$query ="SELECT d.device_id,cb.branch_name,d.device_name,d.IP,d.enrollment,d.branch_id
				 FROM devices d
				 LEFT JOIN company_branch cb
				 ON d.branch_id =cb.branch_id ";
		if($result = mysqli_query ( $this->conn, $query )){
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $a_json, $row );
			   }
			    return ((empty( $a_json) )?array("result"=>true,"data"=>'No Data Found'):array("result"=>true,"data"=>$a_json));
		}else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
			return $a_json;
	}
	
	function insertListener($deviceIp,$listenerIp,$listenerPort,$reg_on,$reg_by) {
		$deviceApi = new deviceApi($this->conn); //pass the connection inside
		$results = $deviceApi->tcpListenerSet ($deviceIp,$listenerIp,$listenerPort);
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO device_tcp_listener( device_ip, listener_ip, listener_port, registered_on, registered_by)  VALUES (?,?,?,?,?);" );
		mysqli_stmt_bind_param ( $stmt, 'sssss', $deviceIp,$listenerIp,$listenerPort,$reg_on,$reg_by);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
			else
				return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	function getListenerinfo($deviceIp){
		$a_json = array();
		$query ="SELECT * FROM device_tcp_listener WHERE device_ip='$deviceIp' 
                  ORDER BY registered_on DESC LIMIT 0,1  ";
		if($result = mysqli_query ( $this->conn, $query )){
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $a_json, $row );
			}
			return ((empty( $a_json) )?array("result"=>true,"data"=>'No Data Found'):array("result"=>true,"data"=>$a_json));
		}else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
			return $a_json;
		
	}
	
	function changeEnrolment($deviceId){
		$json = array();
		$query = "UPDATE devices SET enrollment=0;UPDATE devices SET enrollment=1 WHERE device_id='$deviceId'";
		$result = mysqli_multi_query ( $this->conn, $query);
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		array_push ( $json, $row );
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
	}
}
	

?>
