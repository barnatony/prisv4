<?php
function _dt_regularization($for="me"){
	
	$privilage=$_SESSION["authprivilage"];
	if((isset($_REQUEST['min']) && isset($_REQUEST['max']) && $_REQUEST['min']!="" && $_REQUEST['max']!="")){
		$min=str_replace(',', '-', $_REQUEST['min']);
		$min=$yesterday=date('Y-m-d',strtotime($min));
		$max=str_replace(',', '-', $_REQUEST['max']);
		$max=$yesterday=date('Y-m-d',strtotime($max));
	
	}
	$privilage=$_SESSION["authprivilage"];
	//if employee is logged in display their leave request
	if($_SESSION['authprivilage']=="employee"){
		$login_id = $_SESSION["employee_id"];
		
		    $wherewhat="WHERE r.employee_id='{$_SESSION["employee_id"]}'";
	}elseif($_SESSION["authprivilage"]=="hr"){
		$login_id = $_SESSION["login_id"];
		//if Admin logged in display all leave requests
		if(($_REQUEST["status"]!="") && $_REQUEST["regularization"]=="" && $_REQUEST["reason_type"]=="")
			$wherewhat="WHERE status='{$_REQUEST["status"]}'" ;
		else if(($_REQUEST["status"]=="") && $_REQUEST["regularization"]!="" && $_REQUEST["reason_type"]=="")
			$wherewhat="WHERE regularize_type='{$_REQUEST["regularization"]}'" ;
		else if(($_REQUEST["status"]=="") && $_REQUEST["regularization"]=="" && $_REQUEST["reason_type"]!="")
			$wherewhat="WHERE reason_type='{$_REQUEST["reason_type"]}'" ;
		else if(($_REQUEST["status"]!="") && $_REQUEST["regularization"]!="")
			$wherewhat="WHERE status='{$_REQUEST["status"]}' AND regularize_type='{$_REQUEST["regularization"]}'";
		else if(($_REQUEST["status"]!="") && $_REQUEST["reason_type"]!="")
			$wherewhat="WHERE status='{$_REQUEST["status"]}' AND reason_type='{$_REQUEST["reason_type"]}'";
		else if(($_REQUEST["regularization"]!="") && $_REQUEST["reason_type"]!="")
			$wherewhat="WHERE regularize_type='{$_REQUEST["regularization"]}' AND reason_type='{$_REQUEST["reason_type"]}'";
		else if(($_REQUEST["regularization"]!="") && $_REQUEST["reason_type"]!="" && $_REQUEST["status"]!="")
			$wherewhat="WHERE regularize_type='{$_REQUEST["regularization"]}' AND reason_type='{$_REQUEST["reason_type"]}' AND status='{$_REQUEST['status']}'";
		else if(($_REQUEST["min"]!="") && ($_REQUEST["max"])!="")
				$wherewhat="WHERE day BETWEEN '$min' AND '$max'" ;
		else if($_REQUEST['global_search']!="")
			  $wherewhat="WHERE employee_name LIKE '%{$_REQUEST['global_search']}%'";
		else
			$wherewhat="";
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
	
	//echo $wherewhat;
	if($for=="myteam") {
		
		$privilage="team_lead";
		//if team leader logged in display their team
		//check if login person has team members
		$employees=new Employee();
	
		if($_SESSION["authprivilage"]=="employee")
			$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$login_id);
		elseif($_SESSION["authprivilage"]=="hr")
			$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$login_id);
		
			
	
			//if exists then show team members leave requests
			if($teamleads){
			$wherewhat="WHERE r.employee_id IN(

					SELECT  REPLACE(CONCAT(REPEAT('    ', level - 1), CAST(hi.employee_id AS CHAR)),' ','') AS employee_id-- , employee_reporting_person, level,employee_name
					FROM    (
					        SELECT  hierarchy_connect_by_parent_eq_prior_empid(employee_id) AS employee_id, @level AS level
					        FROM    (
					                SELECT  @start_with := '{$login_id}',
					                        @id := @start_with,
					                        @level := 0
					                ) vars, employee_work_details
					        WHERE   @id IS NOT NULL AND enabled !=0
					        ) ho
					JOIN employee_work_details hi
					ON hi.employee_id = ho.employee_id) AND r.regularize_type='Incorrectpunches'";
			
			}

	}
	
	
	
	
	
	
	
	
	
	
	$table="(SELECT r.id,r.employee_id,p.employee_name,DATE_FORMAT(r.day,'%d %b,%Y') day,r.regularize_type,r.reason_type,r.reason,r.status,r.admin_reason,r.raised_on,
					r.raised_by,r.approved_on,r.approved_by,r.updated_on,r.updated_by,'$privilage' privilage
FROM attendance_regularization r 
LEFT JOIN employee_work_details p ON p.employee_id=r.employee_id 
{$wherewhat}
ORDER BY r.status='RQ' DESC,r.raised_on DESC 
			) attendance";
	//print_r($table);
	
	// Table's primary key
	$primaryKey = 'id';
	
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
					array( 'db' => 'day', 'dt' =>"day" ),
					array( 'db' => 'regularize_type',  'dt' =>"regularize_type" ),
					array( 'db' => 'reason_type',  'dt' =>"reason_type" ),
					array( 'db' => 'reason',  'dt' =>"reason" ),
					array( 'db' => 'status',  'dt' =>"status" ),
					array( 'db' => 'admin_reason',  'dt' =>"admin_reason" ),
					array( 'db' => 'id',  'dt' =>"id" ),
					array( 'db' => 'raised_on',  'dt' =>"raised_on" ),
					array( 'db' => 'raised_by',  'dt' =>"raised_by" ),
					array( 'db' => 'privilage',  'dt' =>"privilage")
					);
	
	echo json_encode(
			ssp::simple( $_GET, getdbh(), $table, $primaryKey, $columns )
			);
	
}