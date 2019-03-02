<?php
function _get(){
	//Employee ID, Full Name, Shift Name, Department Name, Designation Name, Official Contact Number, Personal Contact Number, Official/Personal Emails,
	
	$joins="LEFT JOIN company_shifts s ON s.shift_id=w.shift_id
				LEFT JOIN company_departments dept ON w.department_id = dept.department_id
				LEFT JOIN company_designations des ON des.designation_id=w.designation_id
				LEFT JOIN company_branch b ON w.branch_id = b.branch_id
				LEFT JOIN company_team ct ON ct.team_id=w.team_id 
				LEFT JOIN employee_personal_details pd
				ON pd.employee_id=w.employee_id
			WHERE w.enabled=1";
	

	
	if($_SESSION['authprivilage']=="employee"){
		$login_id=$_SESSION['employee_id'];
		$cond="CONCAT(REPEAT('    ', level - 1), CAST(w.employee_id AS CHAR)) AS employee_id, employee_reporting_person, level,employee_name";
		$wherewhat="    (
		        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
		        FROM    (
		                SELECT  @start_with := '{$login_id}',
		                        @id := @start_with,
		                        @level := 0
		                ) vars, employee_work_details
		        WHERE   @id IS NOT NULL AND enabled=1
		        ) ho
			JOIN employee_work_details w
			ON  w.employee_id = ho.employee_id 
			{$joins}";
		

	}elseif($_SESSION['authprivilage']=="hr"){
		$cond='w.employee_id,w.employee_name';
		$wherewhat="employee_work_details w {$joins}";
	}
	
	
	$employees=new Employee();
	$employees=$employees->joined_select("{$cond},
      IFNULL(s.shift_name,'-') shift_name,
			dept.department_name department,des.designation_name designation,
			IFNULL(IF(pd.employee_mobile='Nil','-',pd.employee_mobile),'-') employee_mobile,
			IFNULL(IF(pd.employee_email='Nil','-',pd.employee_email),'-') employee_email,
			IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Yrs'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Mnths'),CONCAT('1.0 Yr'))) experience,b.branch_name branch,
			REPLACE(pd.employee_image,'..','') employee_image,pd.employee_gender,
			DATE_FORMAT(w.employee_doj,'%d %b, %Y') doj
			,ct.team_id,ct.team_name",$wherewhat);
	
	
	$myteamData=array(
			"count"=>count($employees),
			"employees"=>$employees
	);
	
	
	echo json_encode($myteamData);	

}
