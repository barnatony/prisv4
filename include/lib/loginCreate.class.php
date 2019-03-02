<?php
/*
 * ----------------------------------------------------------
 * Filename : loginCreate.class.php
 * Author : Rajasundari
 * Database : company_master_login
 * Oper : General Company  Actions
 *
 * ----------------------------------------------------------
 */
class loginCreate {
	var $master_name;
	var $master_username;
	var $master_email;
	var $master_password;
	var $master_image;
	var $master_mobile;
	var $master_address;
	var $master_city;
	var $master_state;
	var $master_gender;
	var $master_role;
	var $master_id;
	var $created_by;
	var $whologIN;
	var $conn; // connection var
	
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_master_login
				(master_id, master_name, master_username, master_email, master_password, master_image, 
                master_mobile, master_address, master_city, master_state, master_gender, master_role, created_by)
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssssssss',$this->master_id,$this-> master_name,$this-> master_username,$this-> master_email,
				$this->master_password,$this-> master_image,$this->master_mobile, $this->master_address, 
				$this->master_city, $this->master_state,$this-> master_gender, $this->master_role, $this->created_by);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true){
			return array('result'=>true,'data'=>self::select(0)['data']);
		}else{
			return array('result'=>false,'data'=>$result);
		}
	}

	
	function select($isAllColumsSelect) {
	   $ajson = array (array());
	   $columSet=($isAllColumsSelect==1)?'*':'master_id MasterID,master_name MasterName ,master_mobile Mobile,master_email Email,master_role Role,enabled Active';
	   $condition=($isAllColumsSelect==1)?"AND  master_id='$this->master_id'":"";
	   $subCondition=($this->loginrole!='Master')?"WHERE created_by IN('$this->created_by') $condition":"WHERE created_by!='Master'";
	    
	   if($result = mysqli_query ( $this->conn, "SELECT  $columSet  FROM  " . MASTER_DB_NAME . ".company_master_login $subCondition" )){
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_NUM ) ) {
			array_push ( $ajson[0], $row );
		}
		if(count($ajson[0])>0){
			mysqli_data_seek ( $result, 0 ); // pointer reset into zero for again fetch same data in mysql using mysqli_data_seek
				$isheader=array_keys ( mysqli_fetch_assoc ( $result ) );
				array_push($ajson,$isheader);
			return array('result'=>true,'data'=>$ajson);
		}else{
			return array('result'=>true,'data'=>"No Data Found");
		}
		}else{
			return array('result'=>false,'data'=>$result);
		}
	}
	
	function update(){
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_master_login SET
				master_name=?, master_username=?, master_email=?, master_password=?, master_image=?,
                master_mobile=?, master_address=?, master_city=?, master_state=?, master_gender=?, master_role=?
				WHERE master_id=?");
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssss',$this-> master_name,$this-> master_username,$this-> master_email,
				$this->master_password,$this-> master_image,$this->master_mobile, $this->master_address,
				$this->master_city, $this->master_state,$this-> master_gender, $this->master_role,$this->master_id);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true){
			return array('result'=>true,'data'=>self::select(0)['data']);
		}else{
			return array('result'=>false,'data'=>$result);
		}
	}
	
	/* Enable/Disable Branch */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_master_login SET enabled = ? WHERE master_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'is', $val,$this->master_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		if($result===true){
			return array('result'=>true,'data'=>self::select(0)['data']);
		}else{
			return array('result'=>false,'data'=>$result);
		}
	}
}
?>