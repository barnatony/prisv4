<?php
function _lookup_emp(){
	
		$wherewhat =array();
		$bindings = array();
		
		//check login and list the employee under them
		if($_SESSION['authprivilage']=='employee'){
			$login_id = $_SESSION["employee_id"];
			$wherewhat="  employee_id IN (SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id
                                  FROM    (
                                          SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
                                          FROM    (
                                                  SELECT  @start_with := '{$login_id}',
                                                          @id := @start_with,
                                                          @level := 0
                                                  ) vars, employee_work_details
                                          WHERE   @id IS NOT NULL AND enabled =1
                                          ) ho
                                  JOIN employee_work_details hi
                                  ON hi.employee_id = ho.employee_id) ";
		}
		
	
		if(isset($_REQUEST["s"]) && $_REQUEST["s"] !=""){
			if($_SESSION["authprivilage"]=="employee")
				$wherewhat .=" AND employee_name LIKE ? OR employee_id = ?";
			else 
				$wherewhat ="employee_name LIKE ? OR employee_id = ?";
			array_push($bindings, "%{$_REQUEST["s"]}%");
			array_push($bindings, $_REQUEST["s"]);
		}
		
		$employee=new Employee();
		$employee=$employee->select('employee_id id,CONCAT(employee_name," ",IFNULL(employee_lastname,"")) employee_name ',$wherewhat,$bindings);
	
		echo json_encode($employee);
	
}


