<?php

class perquisites {
	/* Member variables */
var $conn;
	function __construct($conn) {
		$this->conn = $conn;
	}
	function __destruct() {
		//mysqli_close ( $this->conn );
	}
	/* Member functions */
	/* Insert New Perquisite */
	function insert($perq_name,$usageTrans,$typesPerq,$condition,$ownRent,$value,$capacity,$updated_by) {
		$rand = mt_rand ( 10000, 99999 );
		$perq_id = "PQ" . $rand;
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO perqs( perqs_id,name, usage_transfer, perqs_type, new_old, own_rent, value, capacity, updated_by)  VALUES (?,?,?,?,?,?,?,?,?);" );
	//echo INSERT INTO perqs( perqs_id,name, usage_transfer, perqs_type, new_old, own_rent, value, capacity, updated_by)  VALUES (?,?,?,?,?,?,?,?,?);
		mysqli_stmt_bind_param ( $stmt, 'sssssssss',$perq_id, $perq_name,$usageTrans,$typesPerq,$condition,$ownRent,$value,$capacity,$updated_by);
	//	echo $perq_id, $perq_name,$usageTrans,$typesPerq,$condition,$ownRent,$value,$capacity,$updated_by;
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
			else
				return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	function getperquisites(){
		$a_json = array ();
		$query = "SELECT p.perqs_id,p.name,p.usage_transfer,p.perqs_type,p.new_old,p.own_rent,p.value,p.capacity,CONCAT(w.employee_name,' [',pm.employee_id,']') mapped_to
					  FROM perqs p
					  LEFT JOIN perqs_mapping pm
					  ON p.perqs_id = pm.perqs_id
					  LEFT JOIN employee_work_details w
				      ON pm.employee_id = w.employee_id;";
		if($result = mysqli_query ( $this->conn, $query )){
		   	while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $a_json, $row );
			}
		return ((empty( $a_json) )?array("result"=>true,"data"=>'No Data Found'):array("result"=>true,"data"=>$a_json));
		}else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	
		return $a_json;
	}
	function getAvailablePerqs($employee_id){
		
		$a_json = array();
		$query ="SELECT p.perqs_id pid,IF(pm.employee_id IS NULL,0,1) checked,p.name,pm.dedu_amount value,pm.dedu_type
						FROM perqs p
						LEFT JOIN perqs_mapping pm
						ON p.perqs_id = pm.perqs_id
						WHERE pm.employee_id ='$employee_id' 
						UNION 
						SELECT p.perqs_id pid,IF(pm.employee_id IS NULL,0,1) checked,p.name,p.value,'' as dedu_type
						FROM perqs p
						LEFT JOIN perqs_mapping pm
						ON p.perqs_id = pm.perqs_id
						WHERE pm.employee_id IS NULL;"; 
		//echo $query;die();
		if($result = mysqli_query ( $this->conn, $query )){
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $a_json, $row );
			}
			return ((empty( $a_json) )?array("result"=>true,"data"=>'No Data Found'):array("result"=>true,"data"=>$a_json));
		}else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	return $a_json;
	}
	
	function perquisiteMapping($employeeId,$preqs,$ded_amount,$deduc_type,$updated_by){
		$mapped_from = date('Y-m-d');
		if($preqs!=''){
			for($i=0;$i<sizeof($preqs);$i++){
				$perqs_id = $preqs[$i]; $value = $ded_amount[$i]; $type = $deduc_type[$i];
				$stmt = mysqli_prepare ( $this->conn,"INSERT INTO perqs_mapping (employee_id,perqs_id,dedu_amount,dedu_type,mapped_from,updated_by) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE dedu_amount =?,dedu_type =?;");
				mysqli_stmt_bind_param ( $stmt,'ssssssss',$employeeId,$perqs_id,$value,$type,$mapped_from,$updated_by,$value,$type);
				//echo "INSERT INTO perqs_mapping (employee_id,perqs_id,deduction_amount,dedu_type,mapped_from,updated_by) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE dedu_amount =?,dedu_type =?;";
				//echo $employeeId,$perqs_id,$value,$type,$mapped_from,$updated_by; die();
				$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
			}
		}else{
			$result = TRUE;
		}
		if($result===true && (isset($result['result'])?$result['result']:true)===true)
			return array("result"=>true,"data"=>mysqli_error ( $this->conn ));
		else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	} 

}