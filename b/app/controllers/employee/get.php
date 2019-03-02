<?php
function _get($action=null,$emp_id=null){
	
	$table_name="employee_work_details";
	//start and end limit
	$start=!isset($_REQUEST['start'])?0:$_REQUEST['start'];
	$limit=!isset($_REQUEST['limit'])?10:$_REQUEST['limit'];
	$employees=new Employee($table_name);
	print_r($employees);
	die();
	
	
	switch($action){
	
		case "":
			
			$wherewhat=array();
			$bindings=array();
			//search
			if(isset($_REQUEST["employee_id"]) && $_REQUEST["employee_id"] !=""){
				$wherewhat[] =" w.employee_id LIKE  ?";
				array_push($bindings, "%{$_REQUEST["employee_id"]}%");
			}
			
			if(isset($_REQUEST["employee_name"]) && $_REQUEST["employee_name"] !=""){
				$wherewhat[] =" w.employee_name LIKE  ? OR w.employee_lastname = ?";
				array_push($bindings, "%{$_REQUEST["employee_name"]}%");
				array_push($bindings, "{$_REQUEST["employee_name"]}");
			}
			
			$wherewhat=implode(" AND ",$wherewhat);
			//if all given displays  all employees 		 
			$limitStr =$limit=="all"?"":"$start,$limit";
			//to display whole employees
			$employees=$employees-> _getEmployeeView($wherewhat,$bindings,$limitStr);
			
			break;
		case "PersonelDetails":
			//to get personelDetails of the employee
			$employees=$employees->_getEmployeePersonelDetails($emp_id);
			
			
			break;
			
		case "WorkDetails":
			//to get WorkDetails of the employee
			$employees=$employees->_getWorkDetails($emp_id);
			
			break;
			
		case "Letters":
			//to get letters
			$employees=$employees->_letters($emp_id);
			
			
		break;
		
		
		case "Salary":
			//to get salary Details
			$employees=$employees->_letters($emp_id);
				
		
			break;
	}
	
	
	
	echo json_encode($employees);
	
}