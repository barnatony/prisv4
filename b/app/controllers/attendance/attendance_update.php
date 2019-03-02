<?php
	function _attendance_update(){
		$result = array(false,"rowCount"=>0,"info"=>'');
		
		 $employee_id = $_REQUEST["empID"];
		 $date_1= $_REQUEST["Updatecalender"];
		 $date = DateTime::createFromFormat('d-m-Y', $date_1);
		 $date = $date->format('Y-m-d'); 
		 $punch_status = $_REQUEST["punch"];
		 $reason = $_REQUEST["reason"];
		 
		 $attendance_update=new Regularization($employee_id);
		 
		 if($attendance_update->exists()){
		 	$result["info"] = "Attendance Updation Failed";
		 	echo json_encode($result);
		 	die();
		 }
		
		 //$wherewhat = $id ;
		 
		 $dbh = getdbh();
		 $query=$dbh->prepare("SELECT ref_id FROM device_users  where employee_id=:employee_id");
		 $query->bindParam('employee_id', $employee_id);
		 $query->execute();
		 $empRefId = $query->fetch();
		 
		// $this->$attendance_update->update($data);
		 
		 switch($result = $attendance_update->update()->result){
		 	case ($result[0]===true):
		 		$result['info'] = 'Regularization Updated!';
		 		break;
		 	case ($result[0]===false):
		 		$result['info'] .= '- Regularization Update Failed!';
		 		break;
		 }
	// $regReq = $reg->_select("r.employee_id","employee_biometric eb INNER JOIN device_users d ON d.employee_id=eb.employee_id",$wherewhat,$bindings);
		 
		// $result= $attendance_update->insert("employee_biometric",array("employee_id","category","title","start_date","end_date","updated_on","updated_by"),$branches)->result;
		 
		 //print_r("fdfdfS");
		 echo json_encode($result);
	}
	?>
	
	
