<?php
function _dt_leave_requests($for="me"){
	
	
	
	$privilage=$_SESSION["authprivilage"];
	
	
	if(($_REQUEST['min']!="" && $_REQUEST['max']!="")){
		$min=str_replace(',', '-', $_REQUEST['min']);
		$min=$yesterday=date('Y-m-d',strtotime($min));
		$max=str_replace(',', '-', $_REQUEST['max']);
		$max=$yesterday=date('Y-m-d',strtotime($max));
		
	}
	//if employee is logged in display their leave request
	if($_SESSION['authprivilage']=="employee"){
		$login_id = $_SESSION["employee_id"];
		$wherewhat="WHERE l.employee_id='{$_SESSION["employee_id"]}'";
	}elseif($_SESSION["authprivilage"]=="hr"){
		$login_id = $_SESSION["login_id"];
		
		$from_date = $_SESSION['payrollYear']."-".$_SESSION['monthNo']."-01";
		$to_date = $_SESSION['payrollYear']."-".$_SESSION['monthNo']."-31";
		//$to_date= date("Y-m-d",strtotime("{$to_date} +1 months"));
		//if Admin logged in display all leave requests
		if(($_REQUEST["min"]!="") && ($_REQUEST["max"])!="")
			$wherewhat="WHERE l.from_date BETWEEN '$min' AND '$max'";
		else if( $_REQUEST['leave']!="" && $_REQUEST['status']=="")
			$wherewhat="WHERE leave_type='{$_REQUEST["leave"]}'";
		else if( $_REQUEST['leave']=="" && $_REQUEST['status']!="")
			$wherewhat="WHERE status='{$_REQUEST["status"]}'";
		else if( $_REQUEST['leave']!="" && $_REQUEST['status']!="")
			$wherewhat="WHERE status='{$_REQUEST["status"]}' AND leave_type='{$_REQUEST["leave"]}'";
		else if($_REQUEST['global_search']!="")
			$wherewhat="WHERE employee_name LIKE '%{$_REQUEST['global_search']}%'";
		else
			$wherewhat="WHERE l.from_date BETWEEN '$from_date' AND '$to_date'";
	}
	
	//get attedance start date from company details
	$today_date= date("d");
	$dbh = getdbh();
	$stmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
  							FROM company_details WHERE info_flag="A" AND company_id=:company_id');
	$stmt->bindParam('company_id', $_SESSION['company_id']);
	$stmt->execute();
	$companyProp = $stmt->fetch();
	
	if($companyProp['attendance_dt'] !=1){
		if($today_date >= $companyProp['attendance_dt']){
			$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']);
			$eDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
			$endDate = date("Y-m-d",strtotime("{$eDate} +1 months"));
		}else{
			$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']);
			$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
			$endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyProp['attendance_dt']-1);
		}
	}else{
		$startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
		$endDate = date('Y-m-t',strtotime($startDate));
	}

	if($for=="myteam") {
		
		//if team leader logged in display their team
		//check if login person has team members
		$employees=new Employee();
		if($_SESSION["authprivilage"]=="employee")
			$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$login_id);
		elseif($_SESSION["authprivilage"]=="hr")
			$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$login_id);
		
		//if exists then show team members leave requests
		if($teamleads){
			$privilage="team_lead"; //DATE_FORMAT(l.from_date,'%Y-%m-%d') >= '{$startDate}' AND 
			$wherewhat="WHERE l.status='RQ' AND w.employee_id IN (
                                        SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id
                                        FROM    (
                                                SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
                                                FROM    (
                                                        SELECT  @start_with := '{$login_id}',
                                                                @id := @start_with,
                                                                @level := 0
                                                        ) vars, employee_work_details
                                                WHERE   @id IS NOT NULL AND enabled !=0
                                                ) ho
                                        JOIN    employee_work_details hi
                                        ON      hi.employee_id = ho.employee_id) OR w.employee_reporting_person IS NULL";
		}
			
			
		

	}
	
	
	
	
	
	$table="(SELECT	w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,s.shift_name shift,dept.department_name department,des.designation_name designation,pd.employee_mobile mobile,pd.employee_email email,IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Yrs'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Yr'))) experience,b.branch_name branch,
	      l.id,l.request_id,l.from_date,l.from_half,l.to_date,l.to_half,l.duration,UPPER(l.leave_type) leave_type,l.reason,l.status,l.admin_reason,l.approved_on,l.approved_by,l.updated_by,l.updated_on,'$privilage' privilage,'$startDate' attendance_st_date
FROM leave_requests l
INNER JOIN employee_work_details w
ON l.employee_id=w.employee_id
LEFT JOIN company_shifts s
ON s.shift_id=w.shift_id
LEFT JOIN company_departments dept
ON w.department_id = dept.department_id
LEFT JOIN company_designations des
ON des.designation_id=w.designation_id
LEFT JOIN employee_personal_details pd
ON pd.employee_id=w.employee_id
LEFT JOIN company_branch b
ON w.branch_id = b.branch_id
{$wherewhat}
AND l.request_id IS NOT NULL ORDER BY l.status='RQ' DESC,l.raised_on DESC) myteam";

//print_r($table);

	// Table's primary key
	$primaryKey = 'employee_id';
	
	$columns = array(
			array(
					'db' => 'employee_id',
					'dt' => 'DT_RowId',
					'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
			// a string. This can also be useful if you have multiple tables
			// to ensure that the id is unique with a different prefix
			return 'row_'.$d;
					}
					),
					array( 'db' => 'employee_name', 'dt' => "employee_name" ),
					array( 'db' => 'employee_id', 'dt' => "employee_id" ),
					array( 'db' => 'from_date',  'dt' =>"from_date",'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
			// a string. This can also be useful if you have multiple tables
			// to ensure that the id is unique with a different prefix
			return date('d M, Y', strtotime($d));
					} ),
					array( 'db' => 'from_half',  'dt' =>"from_half" ),
					array( 'db' => 'to_date',  'dt' =>"to_date",'formatter' => function( $d, $row ) {
						// Technically a DOM id cannot start with an integer, so we prefix
						// a string. This can also be useful if you have multiple tables
						// to ensure that the id is unique with a different prefix
						return date('d M, Y', strtotime($d));
					} ),
					array( 'db' => 'to_half',  'dt' =>"to_half"),
					array( 'db' => 'duration',  'dt' =>"duration" ),
					array( 'db' => 'leave_type',  'dt'=>"leave_type"),
					array( 'db' => 'reason',  'dt' =>"reason"),
					array( 'db' => 'status',  'dt' =>"status"),
					array( 'db' => 'id',  'dt' =>"id"),
					array( 'db' => 'admin_reason',  'dt' =>"admin_reason"),
					array( 'db' => 'request_id',  'dt' =>"request_id"),
					array( 'db' => 'privilage',  'dt' =>"privilage"),
					array( 'db' => 'attendance_st_date',  'dt' =>"attendance_st_date")
					
					);
	
	
	
	
	echo json_encode(ssp::simple( $_GET, getdbh(), $table, $primaryKey, $columns)); 
	//print_r(ssp::simple( $_GET, getdbh(), $table, $primaryKey, $columns));
}