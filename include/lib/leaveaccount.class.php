<?php
/*
 * ----------------------------------------------------------
 * Filename : designation.class.php
 * Author : Rufus Jackson
 * Database : company_designations
 * Oper : Designation Actions
 *
 * ----------------------------------------------------------
 */
class leave_account {
	/* Member variables */
	var $updated_by;
	var $conn;
	var $leaveRules;
	var $from_date;
	var $leaveSucceeding;
	var $leavePreceeding;
	var $leaveRequested;
	var $status;
	var $admin_reason;
	var $lopData;
	var $leaveRuleId;
	var $dayCount;
	var $date;
	var $dayType;
	var $attSearchIds;
	VAR $compoff_id;
	var $startDate;
	var $endDate;
	var $attendance_start_date; //date only format:dd
	var $reason;
	function __construct($conn) {
		$this->conn = $conn;
		date_default_timezone_set(DEFAULT_TIMEZONE);
		$stmt = "SELECT salary_days,attendance_period_sdate attendance_dt FROM company_details WHERE info_flag='A' AND company_id='".$_SESSION['company_id']."'";
		$result = mysqli_query ( $this->conn, $stmt );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) or die(mysqli_error($this->conn));
		$this->attendance_start_date=$row['attendance_dt'];
		if($row['attendance_dt'] !=1){
			if($_SESSION['monthNo']!=01){
				$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo']-1)."-".$row['attendance_dt'];
				$this->startDate = date('Y-m-d',strtotime($this->startDate));
				$this->endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($row['attendance_dt']-1);
			}else{
				$this->startDate = ($_SESSION['payrollYear']-1)."-12-".$row['attendance_dt'];
				$this->endDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($row['attendance_dt']-1);
			}
		}else{
			$this->startDate = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-01";
			$this->endDate = date('Y-m-t',strtotime($this->startDate));
		}
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New Designation */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_designations
										(designation_id,designation_name,designation_hierarchy,updated_by) 
										VALUES (?,?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssis', $this->designation_id, $this->designation_name, $this->designation_hierarchy, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update Branch Using Designation ID */
	function update() {
		if (! empty ( $_REQUEST ['leave_value'] )) {
			$element = explode ( ",", $_REQUEST ['leave_value'] );
			foreach ( $element as $i => $key ) {
				$leave_split = explode ( '_', $key );
				$preview_temp = 'update `payroll_preview_temp` set status_flag="A",' . $leave_split [0] . '=' . '\'' . $_REQUEST ['values_' . $leave_split [0]] . '\',lop=\'' . $this->lop . '\' where employee_id=\'' . $this->employee_id . '\' ';
				$result = mysqli_query ( $this->conn, $preview_temp ) ? TRUE : mysqli_error ( $this->conn );
				$stmt = mysqli_prepare ( $this->conn, "INSERT  INTO emp_montly_attendance (remarks,employee_id,month_year,year,updated_by,$leave_split[0]) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE $leave_split[0] =?,compoff=?,remarks=? " );
				mysqli_stmt_bind_param ( $stmt, 'sssssssss', $this->remarks,$this->employee_id, $_SESSION ['current_payroll_month'], $this->current_year, $this->updated_by, $_REQUEST ['keyup' . $leave_split [0]], $_REQUEST ['keyup' . $leave_split [0]], $this->compoff, $this->remarks);
				$result = mysqli_stmt_execute ( $stmt );
			}
		} else {
			mysqli_query ( $this->conn, "INSERT  INTO emp_montly_attendance (remarks,employee_id,month_year,year,updated_by,compoff) values ('$this->remarks','$this->employee_id',
			'" . $_SESSION ['current_payroll_month'] . "','" . $this->current_year . "','$this->updated_by','$this->compoff') ON DUPLICATE KEY UPDATE compoff='$this->compoff',remarks='$this->remarks'" );
			$preview_temp = "update `payroll_preview_temp` set  lop='$this->lop',status_flag='A' where employee_id='$this->employee_id' ";
			$result = mysqli_query ( $this->conn, $preview_temp ) ? TRUE : mysqli_error ( $this->conn );
		}
		return $result;
	}
	/* Enable/Disable Designation */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_designations SET enabled = ?,updated_by = ? WHERE designation_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->designation_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Delete Designation */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM company_designations WHERE designation_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $this->designation_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function lr_select($val) {
		$a_json = array ();
		$stmt = "SELECT leave_rule_id FROM company_leave_rules where enabled=1 and applicable_to LIKE ('$val')";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function select($employee_id, $year, $leave_rules) {
		$ajson = array ();
		$row = array ();
		$leaveRule = array();
		$preview_leave = $caseStmt = "";
		
		$query= "SELECT l.leave_rule_id,l.alias_name,c.leave_based_on FROM company_leave_rules l JOIN company_details c  WHERE l.enabled=1 AND c.info_flag='A';" ;
		$leaveQuery = mysqli_query ( $this->conn, $query);
		while ( $row1 = mysqli_fetch_array ( $leaveQuery, MYSQLI_ASSOC ) ) {
				array_push ( $leaveRule, $row1 );
			}
		if($leaveRule[0] ['leave_based_on']== 'calYear')
			$year = substr($year,0,4);
		foreach ( $leaveRule as $key => $val ){
			$preview_leave .= "pp.". $val ['leave_rule_id'] ." temp_". $val ['leave_rule_id'] .",";
			$caseStmt .= "WHEN '". $val ['leave_rule_id'] ."' THEN allotted_total-IFNULL(temp_". $val ['leave_rule_id'] .",0) ";
		}
		
		$stmt = "SELECT remarks,lop,compoff, employee_id,opening_bal,allotted,alias_name,
        			   (CASE leave_rule_id $caseStmt END) allotted_total,leave_rule_id,max_combinable,employee_name,leave_in_succeeding,leave_in_preceeding,leave_in_middle
 				FROM (
        	    SELECT la.remarks,pp.lop,la.compoff,$leave_rules l.employee_id,l.opening_bal,l.allotted,cl.alias_name,(opening_bal+allotted)-(availed+encashed+lapsed+adjusted)  as allotted_total,l.leave_rule_id,$preview_leave
		        cl.max_combinable,w.employee_name,leave_in_succeeding,leave_in_preceeding,leave_in_middle
		         FROM  emp_leave_account l INNER JOIN company_leave_rules cl ON l.leave_rule_id = cl.leave_rule_id AND cl.enabled = 1
				 LEFT JOIN employee_work_details w ON l.employee_id = w.employee_id
	   			 INNER JOIN payroll_preview_temp pp ON pp.employee_id = w.employee_id 
				 LEFT JOIN emp_montly_attendance la ON la.employee_id = w.employee_id AND l.year = la.year AND la.month_year='" . $_SESSION ['current_payroll_month'] . "'
				 WHERE l.employee_id='$employee_id'  AND l.year='$year')z";
		
		$result=mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $ajson, $row );
		}
		/*return ((empty( $ajson) )?False:$ajson);
		}else
			return false;*/
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$ajson:mysqli_error ( $this->conn )));
	}
	function getLeaveAccount($employee_id, $current_month_year) {
		$json = array ();
		$row = array ();
		$leaveRule = array();
		$preview_leave = $payStmt = $caseStmt = "";
		$stmt=("SELECT l.leave_rule_id,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
		$query = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
			array_push ( $leaveRule, $row );
		}
		foreach ( $leaveRule as $key => $val ){
			$payStmt .="SUM(p.". $val ['leave_rule_id'] ."),";
			$preview_leave .= "p.". $val ['leave_rule_id'] .",";
			$caseStmt .= "WHEN '". $val ['leave_rule_id'] ."' THEN availed+". $val ['leave_rule_id'] ." ";
		}
		$payStmt = rtrim($payStmt,",");
		$preview_leave= rtrim($preview_leave,",");
		$query = "SELECT employee,rule_name,allotted,(CASE leave_rule_id $caseStmt END) availed,lapsed
				  FROM (                                    
				  SELECT CONCAT(w.employee_name,' ',w.employee_lastname) AS employee, lr.rule_name ,l.leave_rule_id,l.allotted+l.opening_bal allotted,l.availed,l.lapsed,$preview_leave
				  FROM emp_leave_account l
				  INNER JOIN payroll_preview_temp p ON l.employee_id = p.employee_id
				  INNER JOIN company_leave_rules lr	ON l.leave_rule_id = lr.leave_rule_id
				  INNER JOIN employee_work_details w ON l.employee_id = w.employee_id
				  WHERE l.employee_id=? AND l.year = ? ) z;";
		
		$stmt = mysqli_prepare ( $this->conn, $query );
		mysqli_stmt_bind_param ( $stmt, "ss", $employee_id, $current_month_year );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $employee, $rule_name, $alloted, $availed, $lapsed );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			
			$row ['employee'] = $employee;
			$row ['rule_name'] = $rule_name;
			$row ['allotted'] = $alloted;
			$row ['availed'] = $availed;
			$row ['lapsed'] = $lapsed;
			array_push ( $json, $row );
		}
		//return $json;
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
	}
	function getLeaveDetails($leaveRules, $employee_id) {
		$json = array ();
		$querystmt = '';
		foreach ( $leaveRules as $comd ) {
			$querystmt .= 'ppt.' . $comd ['leave_rule_id'] . ',';
		}
		if ($_SESSION ['current_payroll_month']) {
			$stmt = "SELECT " . $querystmt . " ppt.lop,ppt.employee_id FROM payroll_preview_temp as ppt WHERE ppt.employee_id = '$employee_id' ";
			$result = mysqli_query ( $this->conn, $stmt );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $json, $row );
			}
		} else {
			$stmt = "SELECT " . $querystmt . " ppt.lop,ppt.employee_id,CONCAT(DATE_FORMAT(ppt.month_year,'%M'),'-',DATE_FORMAT(ppt.month_year,'%Y')) as monthYear FROM payroll as ppt WHERE ppt.employee_id = '$employee_id' ";
			$result = mysqli_query ( $this->conn, $stmt );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $json, $row );
			}
		}
		return $json;
	}
	function getYear() {
		$json = array ();
		$year = ($_SESSION ['creditLeaveBased'] == 'finYear') ? 'ela.year' : 'DATE_FORMAT(ela.month_year,"%Y")';
		$stmt = "SELECT distinct($year) as years FROM payroll as ela" ;
		$result = mysqli_query ( $this->conn, $stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
	function getSelectedLeaveDetails($leaveRules, $employee_id, $requestedYear) {
		$json = array ();
		$querystmt = '';
		foreach ( $leaveRules as $comd ) {
			$querystmt .= 'ppt.' . $comd ['leave_rule_id'] . ',';
		}
		$year_filter = "";
		$yrLength = strlen ( $requestedYear );
		$year_filter = ($yrLength > 4) ? "year =" : "DATE_FORMAT(ppt.month_year,'%Y') =";
		$stmt = "SELECT CONCAT(DATE_FORMAT(ppt.month_year,'%M'),'-',DATE_FORMAT(ppt.month_year,'%Y')) as monthYear," . substr ( $querystmt, 0, - 1 ) . ",ppt.lop  FROM payroll as ppt WHERE ppt.employee_id = '$employee_id' AND $year_filter'$requestedYear'";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//return $json;
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
	}
	function getAdminLeaveDetails($leaveRules, $employee_id, $requestedYear) {
		$json = array ();
		$querystmt = '';
		foreach ( $leaveRules as $comd ) {
			$querystmt .= 'ppt.' . $comd ['leave_rule_id'] . ',';
		}
		$year_filter = "";
		$yrLength = strlen ( $requestedYear );
		if ($yrLength > 4) {
			// fin year
			$year_filter = "ppt.year =";
		} else {
			$year_filter = "DATE_FORMAT(ppt.month_year,'%Y') =";
		}
		$stmt = "SELECT CONCAT(er.employee_name,' ',er.employee_lastname),CONCAT(DATE_FORMAT(ppt.month_year,'%M'),'-',DATE_FORMAT(ppt.month_year,'%Y')) as monthYear," . substr ( $querystmt, 0, - 1 ) . ",ppt.lop  FROM payroll as ppt INNER JOIN employee_work_details er ON ppt.employee_id = er.employee_id   WHERE ppt.employee_id = '$employee_id' and $year_filter'$requestedYear'";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//return $json;
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
	}
	function getLeaveAccountYear() {
		$json = array ();
		$stmt =  "SELECT distinct(ela.year) as years FROM emp_leave_account as ela" ;
		$result = mysqli_query ( $this->conn,$stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
	
	function leaveRequested() {
		$json = array();
		$date= str_replace('/', '-', $this->from_date);
		$todate= str_replace('/', '-', $this->to_date);
		$date = date('Y-m-d', strtotime($date));
		$todate = date('Y-m-d', strtotime($todate));
		$fromDate= date('Y-m-01', strtotime($date));
		$check = ($fromDate == $date)?"(from_date ='$date' AND":"(from_date BETWEEN '$date' AND '$todate' OR";
		
		$query = "SELECT employee_id,request_id,from_date,to_date,reason,leave_type
		FROM leave_requests
		WHERE employee_id ='$this->employee_id' AND $check to_date BETWEEN '$date' AND '$todate') AND status !='R';";
		
		$result = mysqli_query ( $this->conn,$query);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		if($json !=[]){
			self::getLeaveRequest($this->employee_id) ;
			//return false;
			return array(false,"Leave Request Failed.","You have already applied leave for the same day..!");
		}else{
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO leave_requests
                               (request_id,employee_id, from_date, from_half, to_date, to_half, duration, leave_type, reason, status,updated_by) 
								VALUES (?,?, STR_TO_DATE(?,'%d/%m/%Y'),?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,'RQ',?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssss',$this->request_id,$this->employee_id,$this->from_date, $this->from_half, 
				$this->to_date, $this->to_half, $this->duration, $this->leave_type, $this->reason,$this->updated_by);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return self::getLeaveRequest($this->employee_id) ;
		}
	}
	
	function getPreviousLeave($employeeId){
		$json = array();
		$stmt = "SELECT CONCAT(DATE_FORMAT(from_date,'%d-%b-%Y'),'  to  ',DATE_FORMAT(to_date,'%d-%b-%Y')) date,UPPER(leave_type) leave_type,reason
				 FROM leave_requests
				 WHERE employee_id='$employeeId' AND status='A' ORDER BY from_date DESC LIMIT 0,5;";
		
		$result = mysqli_query ( $this->conn,$stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//return $json;
		return array ((($result)?TRUE:FALSE),(($result)?"Leave Request successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
	}
	
	function getLeaveRequest($employee_id=null,$request_id=null,$monthYear=null) {
		if($employee_id)
			if(strpos($employee_id,",")===false)
				if(strpos($employee_id,"'")===false)
	             	$employee_id = "'$employee_id'";
		$whereCondn= $monthCond="";
		if($employee_id && $request_id)
			$whereCondn .="WHERE l.request_id ='$request_id' AND l.employee_id IN('$employee_id') ";
		if($employee_id && !$request_id)
			$whereCondn .="WHERE l.employee_id IN ($employee_id)";
		if(!$employee_id && !$request_id)
			$whereCondn .= "WHERE l.status IN ('RQ','A')";
		if($monthYear)
			$monthCond = "AND (DATE_FORMAT(l.from_date,'%m%Y') = '".$monthYear."' OR  DATE_FORMAT(l.to_date,'%m%Y') = '".$monthYear."') AND l.status IN ('RQ')";
		
		$json = array ();
		$query = "SELECT  l.to_half,l.from_half ,l.request_id,l.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname)  employee_name,DATE_FORMAT(l.from_date,'%d/%m/%Y') from_date, DATE_FORMAT(l.to_date,'%d/%m/%Y') to_date,
												l.duration, UPPER(l.leave_type) leave_type, l.reason, l.admin_reason,l.status
												FROM leave_requests l 
												INNER JOIN employee_work_details w 
								                ON  l.employee_id = w.employee_id
												LEFT JOIN company_leave_rules lr ON  l.leave_type = lr.leave_rule_id $whereCondn  $monthCond ORDER BY l.status ASC,l.from_date DESC";
		
		$result = mysqli_query ( $this->conn,  $query) or die (mysqli_error($this->conn));
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//return $json;
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
				
	}
	
	function weekOfMonth($date) {
		//Get the first day of the month.
		$firstOfMonth = date('Y-m-01', strtotime($date));
		//Apply above formula.
		return intval(date("W",  strtotime($date))) - intval(date("W",  strtotime($firstOfMonth))) + 1;
	}
	
	function getWeekendRule($shiftID) {
		$json=array();
		$i=1;
		$stmt = "SELECT ms.monday,ms.tuesday,ms.wednesday,ms.thursday,ms.friday,ms.saturday,ms.sunday FROM weekend ms WHERE ms.shift_id = '$shiftID' ORDER BY weeks;";
		$result = mysqli_query ( $this->conn, $stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$json[$i]=$row;
			$i++;
		}
	 return $json;
	}
	
	public function getholiday($fromDate,$endDate){
		$json=array();
		$stmt = "SELECT start_date,title FROM holidays_event e WHERE e.start_date >= '$fromDate' AND e.end_date <= '$endDate' AND e.category='HOLIDAY'";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
		$json[$row['start_date']]['title']=$row['title'];
		}
		return $json;
	}
	
	function getWeekoff($employee_id,$request_id,$current_year,$leaveRuleId,$flag) {
		$json=array();
		$stmt = " SELECT w.employee_id,w.shift_id,l.request_id,l.from_date,l.from_half,l.to_date,l.to_half,lr.leave_in_succeeding,lr.leave_in_middle,
				    	 lr.leave_in_preceeding,lr.max_combinable,(opening_bal+allotted)-(availed+encashed+lapsed+adjusted) allottedTotal,duration
				  FROM employee_work_details w
				  INNER JOIN  leave_requests l
				  ON w.employee_id = l.employee_id
				  INNER JOIN emp_leave_account la
				  ON w.employee_id = la.employee_id
                  INNER JOIN  company_leave_rules lr
				  WHERE w.employee_id = '$employee_id' AND year=$current_year AND l.request_id = '$request_id' AND la.leave_rule_id='$leaveRuleId' AND lr.leave_rule_id='$leaveRuleId' AND l.status IN ('A','RQ');";
		
		$result = mysqli_query ( $this->conn, $stmt );
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		$date = $row['from_date'];
		$end_date = $row['to_date'];
		$json[0]=self::totalLeaveCount($date,$end_date,$employee_id,$row['shift_id'],$row['from_half'],$row['to_half'],
				$row['leave_in_preceeding'],$row['leave_in_succeeding'],$row['leave_in_middle'],$row['max_combinable'],$row['allottedTotal'],$row['duration']);
		if($flag==1){
			$json[1]=self::select ($employee_id, $current_year,'');
		}
		//return $json;
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
	}
	
	function getComoffLeave($employeeId,$flag) {
		$json=array();
		$a_json=array();
		$condition=($flag==1)?"OR is_processed ='-1'":'';
		$columSelect=($flag==1)?"crq.is_processed,":'';
		$stmt = "SELECT crq.employee_id,crq.compoff_id,crq.day_count, $columSelect DATE_FORMAT(crq.date,'%d/%m/%Y') date,crq.working_for  FROM compensation_requests crq
				 WHERE employee_id='$employeeId' AND status='CO'
				 AND (is_processed='0' OR is_processed IS NULL  $condition) ";
		$result = mysqli_query ( $this->conn,$stmt);
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		$json[0]=$a_json;
		if($flag==0){
			$json[1]=self::select ($employeeId, $this->current_year,'');
		}
		//return $json;
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
	}
	
	
	function totalLeaveCount($startDate,$endDate,$employee_id,$shiftID,$fromHalf,
		$toHalf,$leavePreceeding,$leaveSucceeding,$leaveMiddle,$maxCombinable,$allotedTotal,$duration){
		$leave=0;
		$days=0;
		$dates=array();
		$weekendRules = self::getWeekendRule(($shiftID!='Nil' && $shiftID!='')?$shiftID:'SH00001'); //weekend Rules
		$endDate=$endDate;//here for when starttime ++ startDatr date was changed so store it in other variable
		$start_date=$startDate;//here for when starttime ++ startDatr date was changed so store it in other variable
		
		if($fromHalf!='SH'){//Fieat  day is not fulllday Leave means  ommitted Leave Precceding option 
		if($leavePreceeding!="N"){
			$weekend=array();
			$holiday=array();
			$iDate = date ("Y-m-d", strtotime("-1 day", strtotime($start_date)));
			$dayName=strtolower(date("l",strtotime($iDate)));
			$weekendDayType=isset($weekendRules[self::weekOfMonth($iDate)][$dayName])?$weekendRules[self::weekOfMonth($iDate)][$dayName]:'';
			$weekendDaycount =($weekendDayType =='FD')?1:(($weekendDayType=='FH' || $weekendDayType=='SH')?0.5:null);
			$lrPreceedingholiday = self::getholiday($iDate,$iDate);
		
			//get workingday  Array Push
			if($weekendDaycount!=null)
				$weekend[$iDate]=array(true,$weekendDayType,$weekendDaycount); //out put of is weekend ["01-05-2016" :[true,"FD",1]]
		
			
				//Holiday Array Push
				if(isset($lrPreceedingholiday[$iDate]))
					$holiday[$iDate]=array(true,'FD',1,$lrPreceedingholiday[$iDate]['title']);
		
						
					//Remove Weekend if its there in holiday
					if(isset($weekend[$iDate]) == isset($holiday[$iDate])){
						$weekend[$iDate]=null;
					}
		
					while(isset($weekend[$iDate])!=null || isset($holiday[$iDate]) !=null){
						//echo $lop;
						if((isset($weekend[$iDate]) && $leavePreceeding=='W') ||(isset($weekend[$iDate]) && $leavePreceeding=='B' )){
							//if the day is weekend
							$dates[$iDate]['isWeekend']= 1; //push in dates Array
							$dayCount = isset($weekend[$iDate][2])?$weekend[$iDate][2]:0;
							if($leavePreceeding=='W'||$leavePreceeding=='B')
								//$leaveCount =$weekend[$iDate][1]=='FD'?1:$weekend[$iDate][2];
								$dayCount = $leaveCount =1;
								else
									//$leaveCount=$weekend[$iDate][1]=='FD'?0:$weekend[$iDate][2];
									$leaveCount =0;
					 	}elseif((isset($holiday[$iDate]) && $leavePreceeding=='H' )||(isset($holiday[$iDate]) && $leavePreceeding=='B' )){
							//if the day is a Holiday
							$dates[$iDate]['isHoliday']=1; //push in Dates Array
							$dayCount = isset($holiday[$iDate][2])?$holiday[$iDate][2]:0;
							if($leavePreceeding=='H'||$leavePreceeding=='B')
								//$leaveCount =  $weekend[$iDate][1]=='FD'?1:$holiday[$iDate][2];
								$leaveCount = 1;
								else
									$leaveCount=0;
						}else{
							//$dayCount = $leaveCount = 0;
							break;
						}
							
		
						
								if(isset($lop)){
									$lop+=$leaveCount;
									$dates[$iDate]['lop']= $leaveCount;
								}
								else{
									$leave+=$leaveCount;
									$dates[$iDate]['lop']= 0;
								}
									
								if($leave==$maxCombinable && !isset($lop))
									$lop=0;
										
									//push the date in to dates Array
									$dates[$iDate]['dayCount'] = $dayCount;
									$dates[$iDate]['dataType']= (isset($weekend[$startDate][1]))?$weekend[$startDate][1]:'FD';
									//$dates[$iDate]['lop']= $leaveCount;
									 
									$iDate = date ("Y-m-d", strtotime("-1 day", strtotime($iDate)));
									$dayName=strtolower(date("l",strtotime($iDate)));
									$weekendDayType=isset($weekendRules[self::weekOfMonth($iDate)][$dayName])?$weekendRules[self::weekOfMonth($iDate)][$dayName]:'';
									$weekendDaycount =($weekendDayType =='FD')?1:(($weekendDayType=='FH' || $weekendDayType=='SH')?0.5:null);
									$holiday = self::getholiday($iDate,$iDate);
		
								if($weekendDaycount!=null)
			  					$weekend[$iDate]=array(true,$weekendDayType,$weekendDaycount); //out put of is weekend ["01-05-2016" :[true,"FD",1]]
		
			  					
			  					//Holiday Array Push
			  					if(isset($lrPreceedingholiday[$iDate]))
			  					$holiday[$iDate]=array(true,'FD',1,$lrPreceedingholiday[$iDate]['title']);
			  					
			  					
			  					//Remove Weekend if its there in holiday
			  					if(isset($weekend[$iDate]) == isset($holiday[$iDate])){
			  						$weekend[$iDate]=null;
			  					}
		
					}
					ksort($dates);
		}
		}
		
		            $totalHolidays = self::getholiday($startDate,$endDate); //holidays with title
					$dateStart=$startDate;
					$dateEnd=$endDate;
					$weekends=array();
					//prepare the dates array
					while(strtotime($startDate) <= strtotime($endDate)){ //key is the Date
						$leaveCount=0;
						//Weekend Array Push
						$dayName=strtolower(date("l",strtotime($startDate)));
						$weekendDayType=isset($weekendRules[self::weekOfMonth($startDate)][$dayName])?$weekendRules[self::weekOfMonth($startDate)][$dayName]:'';
						$weekendDaycount =($weekendDayType =='FD')?1:(($weekendDayType=='FH' || $weekendDayType=='SH')?0.5:null);
						//get workingday  Array Push
						if($weekendDaycount!=null)
							$weekends[$startDate]= array(true,$weekendDayType,$weekendDaycount);
						
						//Holiday Array Push
						if(isset($totalHolidays[$startDate]))
							$holidays[$startDate]=array(true,'FD',1,$totalHolidays[$startDate]['title']);
						
						//remove weekend if day falls in a holiday
						if(isset($weekends[$startDate])&&isset($holidays[$startDate]))
							unset($weekends[$startDate]);
							
						if(isset($weekends[$startDate])){
							//comes here if it is a weekend
							//prepare Dates Array
							$dates[$startDate]['isWeekend']= 1;
							$dayCount =isset($weekends[$startDate][2])?$weekends[$startDate][2]:0;
							if($leaveMiddle=='W' || $leaveMiddle=='B')
								$dayCount = $leaveCount =($weekendDayType=='SH')?1:$weekendDaycount;
								//if its half day check whether its not a start day of leave and set the leave as 1 else 
								else
							     $dayCount = $leaveCount =($weekendDayType=='SH')?0.5:0;
						}elseif(isset($holidays[$startDate])){
							//comes here if it is a holiday
							$dates[$startDate]['isHoliday']=1;
							$dayCount = isset($holidays[$startDate][2])?$holidays[$startDate][2]:0;
							if($leaveMiddle=='H' || $leaveMiddle=='B')
								$leaveCount =1;
								//$leaveCount =1;
							else
								$leaveCount=0;
						}else if( (!isset($weekends[$startDate]))&& (!isset($holidays[$startDate])) ){
							//comes here for a working day
							$dates[$startDate]['isWorkingDay']=1;
							//Follwing Condition start and end date if half day take day count as 0.5
							if($fromHalf=='SH' && $dateStart==$startDate){
								$dayCount = $leaveCount = 0.5;
							}else if($toHalf=='FH' && $dateEnd==$startDate){
								$dayCount = $leaveCount = 0.5;
							}else{
							$dayCount = $leaveCount = 1;
							}
						}
						
						//dates Array Data
						if(isset($lop)){
							$lop+=$leaveCount;
							$dates[$startDate]['lop']= $leaveCount;
						}else{
							$leave+=$leaveCount;
							$dates[$startDate]['lop']= 0;
						}
						
						$whole=floor($leave);
						$fraction=($leave)-$whole;
						if($maxCombinable<=$leave && !isset($lop)){
						    $lop=$fraction;
						    $leave=$leave-$fraction;
						}
						
							$dates[$startDate]['dayCount'] = $dayCount;
							$dates[$startDate]['dataType']= (isset($weekends[$startDate][1]))?$weekends[$startDate][1]:'FD';
						
						//add a day for looping
						$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
					}
			
					if($toHalf!='FH'){//last day is not fulllday Leave means  ommitted Leave succeeding option 
				if($leaveSucceeding!="N"){
					$weekend=array();
				    $holiday=array();
					$iDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
					$dayName=strtolower(date("l",strtotime($iDate)));
					$weekendDayType=isset($weekendRules[self::weekOfMonth($iDate)][$dayName])?$weekendRules[self::weekOfMonth($iDate)][$dayName]:'';
					$weekendDaycount =($weekendDayType =='FD')?1:(($weekendDayType=='FH' || $weekendDayType=='SH')?0.5:null);
					$lrSucceedingHoliday = self::getholiday($iDate,$iDate);
					
					//get workingday  Array Push
					if($weekendDaycount!=null)
					$weekend[$iDate]=array(true,$weekendDayType,$weekendDaycount); //out put of is weekend ["01-05-2016" :[true,"FD",1]]
					
					//Holiday Array Push
					if(isset($lrSucceedingHoliday[$iDate]))
						$holiday[$iDate]=array(true,'FD',1,$lrSucceedingHoliday[$iDate]['title']);
					
					//Remove Weekend if its there in holiday
					if(isset($weekend[$iDate]) == isset($holiday[$iDate])){
						$weekend[$iDate]=null;
					}
							
						
					while(isset($weekend[$iDate]) !=null || isset($holiday[$iDate]) !=null){
					 if((isset($weekend[$iDate]) && $leaveSucceeding=='W') ||(isset($weekend[$iDate]) && $leaveSucceeding=='B' )){
							//if the day is weekend
							$dates[$iDate]['isWeekend']= 1; //push in dates Array
							$dayCount = isset($weekend[$iDate][2])?$weekend[$iDate][2]:0;
							if($leaveSucceeding=='W'||$leaveSucceeding=='B')
								//$leaveCount =$weekend[$iDate][1]=='FD'?1:$weekend[$iDate][2];
								$dayCount = $leaveCount =1;
								else
								//$leaveCount=$weekend[$iDate][1]=='FD'?0:$weekend[$iDate][2];
								$leaveCount =0;
                   }elseif((isset($holiday[$iDate]) && $leaveSucceeding=='H' )||(isset($holiday[$iDate]) && $leaveSucceeding=='B' )){
							//if the day is a Holiday
							$dates[$iDate]['isHoliday']=1; //push in Dates Array
							    $dayCount = isset($holiday[$iDate][2])?$holiday[$iDate][2]:0;
								if($leaveSucceeding=='H'||$leaveSucceeding=='B')
								//$leaveCount =  $weekend[$iDate][1]=='FD'?1:$holiday[$iDate][2];
								$leaveCount = 1;
								else
								$leaveCount=0;
						}else{
							break;
						}
							

						if(isset($lop)){
							$lop+=$leaveCount;
							$dates[$iDate]['lop']= $leaveCount;
						}
						else{
							$leave+=$leaveCount;
							$dates[$iDate]['lop']=0;
						}
						
					                     
                     if($leave==$maxCombinable && !isset($lop))
					   $lop=0;
										

									//push the date in to dates Array
									$dates[$iDate]['dayCount'] = $dayCount;
									$dates[$iDate]['dataType']= isset($weekend[$iDate][1])?$weekend[$iDate][1]:'FD';
									
									
									$iDate = date ("Y-m-d", strtotime("+1 day", strtotime($iDate)));
									$dayName=strtolower(date("l",strtotime($iDate)));
									$weekendDayType=isset($weekendRules[self::weekOfMonth($iDate)][$dayName])?$weekendRules[self::weekOfMonth($iDate)][$dayName]:'';
									$weekendDaycount =($weekendDayType =='FD')?1:(($weekendDayType=='FH' || $weekendDayType=='SH')?0.5:null);
									$lrSucceedingHoliday = self::getholiday($iDate,$iDate);
				
									  if($weekendDaycount!=null)
									  $weekend[$iDate]=array(true,$weekendDayType,$weekendDaycount); //out put of is weekend ["01-05-2016" :[true,"FD",1]]
				
										//Holiday Array Push
										if(isset($lrSucceedingHoliday[$iDate]))
											$holiday[$iDate]=array(true,'FD',1,$lrSucceedingHoliday[$iDate]['title']);
												
											
										//Remove Weekend if its there in holiday
										if(isset($weekend[$iDate]) == isset($holiday[$iDate])){
											$weekend[$iDate]=null;
										}
				
					}
						
				}
		 }
		$dates['lrPre']=$leavePreceeding;
		$dates['lrSuc']=$leaveSucceeding;
		$dates['lrMid']=$leaveMiddle;
		$dates['duration']=$duration;
		$dates['maxCom']=$maxCombinable;
		if($allotedTotal==0){//If Alloted Leave is zero then Take all leave as lop
			$dates['lop']=(isset($lop)?$lop:0)+$leave;
			$dates['leave']=0;
		}else{
		$dates['leave']=$leave;
		$dates['lop']=(isset($lop)?$lop:0);
		}
		//echo(json_encode($dates));
		return $dates;
	}
	
	function updateAttendance() {
		//$this->date refers the date to be attendance to be updated format : dd
		if($this->attendance_start_date !=1){
			//IF $this->att_st_dt!=1 then attendance period involves two months i.e$this->startDate=2017-06-16 and $this->endDate=2017-07-15), so subtract one month from $this_>date.
			$pastMonthDate = strtotime('-1 months', strtotime($this->date));
			if($pastMonthDate >= strtotime($this->startDate) && $pastMonthDate <= strtotime($this->endDate))
				$this->date = date('Y-m-d',$pastMonthDate);
		}else{
			$this->date = $this->date;
			//$this->date = $_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".$this->date;
		}
		if($this->leaveRuleId=='P')
		{
			$result=mysqli_query ( $this->conn,"DELETE FROM emp_absences WHERE employee_id= '$this->employee_id' AND absent_date='$this->date'");
		}else{
			
			$stmt = mysqli_prepare ( $this->conn, "INSERT INTO `emp_absences`
	    	(`employee_id`,`absent_date`,`leave_rule_type`,`day_type`,`day_count`,`reason`,`updated_by`) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE leave_rule_type =?,day_type =?,day_count =?,reason =? ,updated_by=?" );
			mysqli_stmt_bind_param ( $stmt, 'ssssssssssss', $this->employee_id,$this->date, $this->leaveRuleId, $this->dayType, $this->dayCount,$this->reason,$this->updated_by,$this->leaveRuleId, $this->dayType, $this->dayCount, $this->reason, $this->updated_by );
			
			$result = mysqli_stmt_execute ( $stmt )? TRUE : false;
		}
		if($result){
			self::creditlrPreviewPayroll($this->employee_id,$this->leaves);
			return array($result,self::getAttendanceReport($this->attSearchIds,''));
		}else{
			return array($result,mysqli_error ( $this->conn ));
		}
	}
	
	public function creditlrPreviewPayroll($employeeId,$leaveRules) {
		$employeeId = str_ireplace(",","','",str_ireplace("\'","",$employeeId));
		$caseStmt="";$columStmt="";
		
		$noticeQuery = "SELECT employee_id FROM emp_notice_period WHERE last_working_date BETWEEN '".$this->startDate."' AND '".$this->endDate."' AND employee_id = '$employeeId';";
		$result = mysqli_query ( $this->conn,$noticeQuery);
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		$noticeConn = ($row!='')?"AND a.absent_date <= n.last_working_date":"";
		
		foreach ($leaveRules as $key => $lr){
			$caseStmt .=" MAX(CASE WHEN leave_rule_type = '".$lr['leave_rule_id']."' THEN ASD END)".$lr['leave_rule_id'].",";
			$columStmt .="pt.".$lr['leave_rule_id']." = IFNULL(a.".$lr['leave_rule_id'].",0),";
		}
		if($employeeId!=null){
		$query="UPDATE payroll_preview_temp pt,(SELECT employee_id,";
		$query.=$caseStmt ." MAX(CASE WHEN leave_rule_type = 'lop' THEN ASD ELSE 0 END)lop,
					SUM(CASE WHEN leave_rule_type IN('co','od','wfh','otr') THEN ASD ELSE 0 END)others
					FROM (
					SELECT a.employee_id,leave_rule_type,SUM(day_count) ASD 
					FROM emp_absences a LEFT JOIN emp_notice_period n
					ON a.employee_id = n.employee_id 
					WHERE  absent_date BETWEEN '".$this->startDate."'
				    AND '".$this->endDate."' $noticeConn  AND a.employee_id IN ('$employeeId')
		            GROUP BY a.employee_id,leave_rule_type) s GROUP BY employee_id)a SET ".$columStmt." pt.lop = IFNULL(a.lop,0), pt.other_leave = IFNULL(a.others,0)  
					WHERE pt.employee_id = a.employee_id;";
		}else{
		$query="UPDATE payroll_preview_temp pt,(SELECT employee_id,";
		$query.= $caseStmt ." MAX(CASE WHEN leave_rule_type = 'lop' THEN ASD ELSE 0 END)lop,
							SUM(CASE WHEN leave_rule_type IN('co','od','wfh','otr') THEN ASD ELSE 0 END)others
							FROM (
								SELECT e.employee_id,e.leave_rule_type,SUM(e.day_count) ASD
								FROM employee_work_details w
								INNER JOIN  emp_absences e
								ON w.employee_id = e.employee_id AND w.enabled=1
								WHERE e.absent_date BETWEEN '".$this->startDate."' AND '".$this->endDate."'
								GROUP BY e.employee_id,e.leave_rule_type
								) s GROUP BY employee_id) a
								SET  ".$columStmt." pt.lop = IFNULL(a.lop,0), pt.other_leave = IFNULL(a.others,0)  WHERE pt.employee_id = a.employee_id ;";
		}
		//echo $query; 
		$stmt = mysqli_query ( $this->conn,$query);
	 }
	
	function updatelrRequestStatus($employeeId,$requestId,$compoff) {
		if($this->lopData && $this->status=='A'){
	  		foreach(explode('#',$this->lopData) as $key){
			$value="";
			$value.=($key!=null)?
				"INSERT INTO `emp_absences`
				(`employee_id`,`request_id`,`absent_date`,`leave_rule_type`,`day_type`,`day_count`,`updated_by`) VALUES ('$employeeId','$requestId','".explode('_',$key)[0]."','".strtoupper(explode('_',$key)[1])."','".explode('_',$key)[2]."','".explode('_',$key)[3]."','$this->updated_by') 
				ON DUPLICATE KEY UPDATE leave_rule_type ='".strtoupper(explode('_',$key)[1])."',day_type ='".explode('_',$key)[2]."',day_count ='".explode('_',$key)[3]."',updated_by= '$this->updated_by';":'';
			($value!=null)?(mysqli_query ($this->conn,$value)):'';
			}
	  }
	 
	  if($compoff!='' && $compoff!=null ){
	  	mysqli_query ($this->conn,"UPDATE compensation_requests SET is_processed='1',approved_on=NOW() WHERE compoff_id IN (".str_replace('_',',',substr($compoff,0,-1)).")");
	  }
	  
	 	$stmt = mysqli_prepare ( $this->conn, "UPDATE leave_requests SET leave_type=?,status =?,admin_reason=?,approved_on=NOW(),approved_by=?  WHERE employee_id = ? AND request_id=?" );
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $this->leaveRuleId,$this->status,$this->admin_reason,$this->updated_by,$employeeId,$requestId);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		self::creditlrPreviewPayroll($employeeId,$this->leaves);
		return self::getLeaveRequest('','');
	}
	
	function getAttendanceReport($attSearchIds,$month) {
		$json=$weekCheck=array();
		$query="";
		$weekQuery = "SELECT DISTINCT shift_id,weeks FROM weekend;";
		$stmt1= mysqli_query ( $this->conn,$weekQuery);
		while ( $row =mysqli_fetch_array ( $stmt1, MYSQLI_ASSOC ) ) {
			array_push($weekCheck,$row);
		}
		if($month =='' || $month==0){
		    if($this->attendance_start_date=='1'){
		        $this->startDate = $startDate = date('Y-m')."-01";
		        $this->endDate = date("Y-m-t", strtotime($startDate));
		    }else{
		        $this->startDate = $startDate = date("Y-m", strtotime("-1 months"))."-".$this->attendance_start_date;
		        $this->endDate = date('Y-m')."-".($this->attendance_start_date-1);
		    }
		}else{
		    if($this->attendance_start_date=='1'){
		        $year = substr($month,0,4); $month = (substr($month,4,6)>9)?substr($month,4,6):substr($month,5,6);
		        $this->startDate = $startDate = $year."-".$month."-01";
		        $this->endDate = date("Y-m-t", strtotime($startDate));
		    }else{
		        $year = substr($month,0,4); $month = (substr($month,4,6)>9)?substr($month,4,6):substr($month,5,6);
		        $startDate = date($year."-".($month-1)."-".$this->attendance_start_date,strtotime("-1 months"));
		        $this->startDate = $startDate = date("Y-m-d", strtotime($startDate));
		        $this->endDate = $year."-".$month."-".($this->attendance_start_date-1);
		        $this->endDate= date("Y-m-d", strtotime($this->endDate));
		    }
		}
		
		$startDate = $this->startDate = date("Y-m-d", strtotime($this->startDate));
		$queryData ="SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME,";
		$queryData1 = $queryData2= "SELECT employee_id EMPID,CONCAT(employee_name,' ',employee_lastname) NAME,";
		$queryData3 = "SELECT employee_id EMPID,CONCAT(employee_name,' ',employee_lastname) NAME,device_status DEVICE,";
		
		while (strtotime($startDate) <= strtotime($this->endDate)) {
			// without Weekends
			$queryData.="MIN(CASE WHEN a.absent_date = '".$startDate."' THEN CONCAT(a.day_count,'_',a.leave_rule_type,'_',a.day_type,'_',IF(a.request_id!='',IFNULL(l.reason,'Nil'),IFNULL(a.reason,'Nil')),'_',IF(a.request_id !='',1,0))   WHEN n.last_working_date < '".$startDate."' OR w.employee_doj > '".$startDate."' THEN '-' ELSE 'Z' END) `A_".$startDate."`,";
			// For LMS with Weekends
			$queryData1.="MAX(CASE WHEN (weeks = IF((WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)>5,(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01'))),(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)))
                    	  				THEN (CASE WHEN (DAYNAME('".$startDate."')='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),CONCAT('WE','-',sunday),sunday) WHEN (DAYNAME('".$startDate."')='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),CONCAT('WE','-',monday),monday) WHEN (DAYNAME('".$startDate."')='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),CONCAT('WE','-',tuesday),tuesday) WHEN (DAYNAME('".$startDate."')='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),CONCAT('WE','-',wednesday),wednesday) WHEN (DAYNAME('".$startDate."')='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),CONCAT('WE','-',thursday),thursday) WHEN (DAYNAME('".$startDate."')='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),CONCAT('WE','-',friday),friday) WHEN (DAYNAME('".$startDate."')='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),CONCAT('WE','-',saturday),saturday) END)
					        	   WHEN absent_date = '".$startDate."' THEN CONCAT(day_count,'_',UPPER(leave_rule_type),'_',day_type)
					         	   WHEN last_working_date < '".$startDate."' OR employee_doj > '".$startDate."' THEN '-'
					               WHEN ((start_date BETWEEN '".$startDate."' AND '".$startDate."') OR (end_date BETWEEN '".$startDate."' AND '".$startDate."')) THEN (CASE WHEN (category = 'OPTIONAL' AND holBranch = empBranch) THEN 'RH-FD' WHEN (category = 'HOLIDAY' AND holBranch = 'NA') THEN 'GH-FD' END)
			              ELSE 'D' END)  `A_".$startDate."`,";
			// For Biometric with Weekends
			$queryData2.= "MAX(CASE WHEN (weeks = IF((WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)>5,(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01'))),(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)))
                                          THEN (CASE WHEN (DAYNAME('".$startDate."')='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),CONCAT('WE','-',sunday),sunday) WHEN (DAYNAME('".$startDate."')='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),CONCAT('WE','-',monday),monday) WHEN (DAYNAME('".$startDate."')='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),CONCAT('WE','-',tuesday),tuesday) WHEN (DAYNAME('".$startDate."')='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),CONCAT('WE','-',wednesday),wednesday) WHEN (DAYNAME('".$startDate."')='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),CONCAT('WE','-',thursday),thursday) WHEN (DAYNAME('".$startDate."')='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),CONCAT('WE','-',friday),friday) WHEN (DAYNAME('".$startDate."')='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),CONCAT('WE','-',saturday),saturday) END)
                            WHEN ((start_date BETWEEN '".$startDate."' AND '".$startDate."') OR (end_date BETWEEN '".$startDate."' AND '".$startDate."')) THEN (CASE WHEN (category = 'OPTIONAL' AND holBranch = empBranch) THEN 'RH-FD' WHEN (category = 'HOLIDAY' AND holBranch = 'NA') THEN 'GH-FD' END)
    						WHEN DATE_FORMAT(check_in,'%Y-%m-%d') ='".$startDate."' AND DATE_FORMAT(check_out,'%Y-%m-%d') ='".$startDate."' THEN 'Z'
                            WHEN DATE_FORMAT(check_in,'%Y-%m-%d') ='".$startDate."' OR DATE_FORMAT(check_out,'%Y-%m-%d') ='".$startDate."' THEN 'D'
                            WHEN DATE_FORMAT(absent_date,'%Y-%m-%d') = '".$startDate."' THEN leave_details
				            WHEN DATE_FORMAT(last_working_date,'%Y-%m-%d') < '".$startDate."' OR employee_doj > '".$startDate."' THEN '-'
                            ELSE '*' END) `A_".$startDate."`,";
			// For Biometric with Miss punch Highlight
			$queryData3.= "MAX(CASE WHEN (weeks = IF((WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)>5,(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01'))),(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)))
                                          THEN (CASE WHEN (DAYNAME('".$startDate."')='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),CONCAT('WE','-',sunday),sunday) WHEN (DAYNAME('".$startDate."')='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),CONCAT('WE','-',monday),monday) WHEN (DAYNAME('".$startDate."')='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),CONCAT('WE','-',tuesday),tuesday) WHEN (DAYNAME('".$startDate."')='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),CONCAT('WE','-',wednesday),wednesday) WHEN (DAYNAME('".$startDate."')='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),CONCAT('WE','-',thursday),thursday) WHEN (DAYNAME('".$startDate."')='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),CONCAT('WE','-',friday),friday) WHEN (DAYNAME('".$startDate."')='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),CONCAT('WE','-',saturday),saturday) END)
					                WHEN (DATE_FORMAT(check_in,'%Y-%m-%d') ='".$startDate."' AND DATE_FORMAT(check_out,'%Y-%m-%d') ='".$startDate."' AND DATE_FORMAT(absent_date,'%Y-%m-%d') = '".$startDate."') OR DATE_FORMAT(absent_date,'%Y-%m-%d') = '".$startDate."' THEN leave_details
									WHEN DATE_FORMAT(check_in,'%Y-%m-%d') ='".$startDate."' AND dt = '".$startDate."' THEN IF(cnt%2=0,'Z','Y')					                		
							        WHEN ((start_date BETWEEN '".$startDate."' AND '".$startDate."') OR (end_date BETWEEN '".$startDate."' AND '".$startDate."')) THEN (CASE WHEN (category = 'OPTIONAL' AND holBranch = empBranch) THEN 'RH-FD' WHEN (category = 'HOLIDAY' AND holBranch = 'NA') THEN 'GH-FD' END)
		                            WHEN DATE_FORMAT(last_working_date,'%Y-%m-%d') < '".$startDate."' OR employee_doj > '".$startDate."' THEN '-'
		                            WHEN device_status = 0 THEN '' ELSE '*' END) `A_".$startDate."`,";
			$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
		}
		// without Weekends
		$query.=substr($queryData,0,-1)."FROM employee_work_details w
		INNER JOIN company_designations ds
		ON w.designation_id = ds.designation_id
		INNER JOIN company_branch cb
		ON w.branch_id = cb.branch_id
		INNER JOIN company_departments dp
		ON w.department_id = dp.department_id
		LEFT JOIN emp_absences a
		ON w.employee_id = a.employee_id
		LEFT JOIN leave_requests l
		ON w.employee_id = l.employee_id AND (l.from_date BETWEEN '$this->startDate' AND '$this->endDate') AND (l.to_date BETWEEN '$this->startDate' AND '$this->endDate')
		LEFT JOIN emp_notice_period n
		ON w.employee_id = n.employee_id AND n.status='A' WHERE  w.enabled=1 $attSearchIds
		GROUP BY w.employee_id,w.employee_name
		ORDER BY w.employee_id";
		
		// For LMS with Weekends
		$query1 =substr($queryData1,0,-1)."FROM ( SELECT IF(w.shift_id = 'Nil' OR w.shift_id = '','SH00001',w.shift_id) shift_id,we.weeks,we.sunday,we.monday,we.tuesday,
				we.wednesday,we.thursday, we.friday,we.saturday,w.employee_id, w.employee_name,w.employee_lastname,w.branch_id empBranch,
				ea.absent_date,ea.leave_rule_type,ea.day_count,ea.day_type,w.employee_doj,h.start_date,h.end_date,h.branch_id holBranch,h.category,n.last_working_date
				FROM employee_work_details w
				LEFT join weekend we ON IF(w.shift_id = 'Nil' OR w.shift_id = '','SH00001',w.shift_id) = we.shift_id
				LEFT JOIN emp_notice_period n
				ON w.employee_id = n.employee_id AND n.status='A'
				LEFT JOIN emp_absences ea ON w.employee_id = ea.employee_id AND ea.absent_date BETWEEN '$this->startDate' AND '$this->endDate'
				JOIN company_branch b, holidays_event h
				WHERE w.enabled = 1 $attSearchIds AND (h.category = 'HOLIDAY' OR h.category = 'DUE DAY' OR h.category = 'EVENT' )
				) a GROUP BY employee_id;";

		// For Biometric with Weekends
		$query2 =substr($queryData2,0,-1)."FROM (
					SELECT employee_id,(CASE WHEN is_day=1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN early_start AND start_time THEN MIN(date_time)
					                         WHEN is_day=0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN early_start AND start_time THEN work_day END) 'check_in',
					                   (CASE WHEN is_day=1 AND DATE_FORMAT(MAX(date_time),'%H:%i') BETWEEN SUBSTRING_INDEX(end_time,' ',1) AND late_end  THEN MAX(date_time)
					                         WHEN is_day=0 AND DATE_FORMAT(MIN(work_day),'%H:%i') BETWEEN SUBSTRING_INDEX(end_time,' ',1) AND late_end THEN MIN(work_day) ELSE '' END) 'check_out',(WEEK(date_time) - WEEK(DATE_FORMAT(date_time , '%Y-%m-01')))+1 day_week,DAYNAME(date_time) day_name,
					        employee_doj,last_working_date,absent_date,leave_details,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,
					        employee_name,employee_lastname,start_date,end_date,holBranch,empBranch,category
					FROM (
					SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
								IF(from_date<'$this->startDate','$this->startDate',from_date) from_date,
								IF(to_date='0000-00-00' OR to_date IS NULL ,'$this->endDate',to_date) to_date,status device_status
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  w.enabled = 1 $attSearchIds 
						AND ((NOT (from_date > '$this->endDate' OR to_date < '$this->startDate' )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$this->endDate' )) ORDER BY w.employee_id ) z
					LEFT JOIN company_shifts s
					ON z.shift_id = s.shift_id
					LEFT JOIN emp_notice_period n 
					ON z.employee_id = n.employee_id AND n.status='A'
					INNER JOIN employee_biometric b
					ON z.employee_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
					LEFT JOIN leave_requests l
					ON z.employee_id = l.employee_id  AND (l.from_date BETWEEN '$this->startDate' AND '$this->endDate') AND (l.to_date BETWEEN '$this->startDate' AND '$this->endDate') 
					LEFT JOIN emp_absences a
					ON z.employee_id = a.employee_id AND a.absent_date BETWEEN '$this->startDate' AND '$this->endDate'
					LEFT join weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					JOIN company_branch cb
					LEFT JOIN holidays_event h ON h.start_date BETWEEN '$this->startDate' AND '$this->endDate'  
					WHERE s.is_day IS NOT NULL 
					ORDER BY z.employee_id,date_time )q
					GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d'),absent_date,weeks,start_date ORDER BY employee_id ) w
					GROUP BY employee_id;";
		
		// For Biometric with Miss punch Highlight
		$query3 =substr($queryData3,0,-1)."FROM (
					SELECT * FROM 
					(SELECT employee_id,(CASE WHEN is_day=1 THEN MIN(date_time)
					                         WHEN is_day=0 THEN work_day END) 'check_in',
					                   (CASE WHEN is_day=1 THEN MAX(date_time)
					                         WHEN is_day=0 THEN DATE_SUB(MAX(date_time),INTERVAL 1 DAY) ELSE '' END) 'check_out',
					        employee_doj,last_working_date,absent_date,CONCAT('_',leave_details) leave_details,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,
					        employee_name,employee_lastname,start_date,end_date,holBranch,empBranch,category,device_status
					FROM (
					SELECT z.employee_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
					      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
					       ELSE date_time END) work_day,
					       z.employee_doj,n.last_working_date,a.absent_date,CONCAT(a.day_count,'_',a.leave_rule_type,'_',a.day_type,'_',IF(a.request_id!='',IFNULL(l.reason,'Nil'),IFNULL(a.reason,'Nil')),'_',IF(a.request_id !='',1,0),'_',device_status) leave_details,
					       weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,employee_name,employee_lastname,start_date,end_date,h.branch_id holBranch,empBranch,h.category,device_status
					FROM (
					SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
								IF(from_date<'$this->startDate','$this->startDate',from_date) from_date,
								IF(to_date='0000-00-00' OR to_date IS NULL ,'$this->endDate',to_date) to_date,IFNULL(status,0) device_status
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  w.enabled = 1 $attSearchIds 
						AND ((NOT (from_date > '$this->endDate' OR to_date < '$this->startDate' )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$this->endDate' )) ORDER BY w.employee_id  ) z
					LEFT JOIN company_shifts s
					ON z.shift_id = s.shift_id 
					LEFT JOIN emp_notice_period n 
					ON z.employee_id = n.employee_id AND n.status='A'
					LEFT JOIN device_users du
					ON z.employee_id = du.employee_id
					LEFT JOIN employee_biometric b
					ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
					LEFT JOIN leave_requests l
					ON z.employee_id = l.employee_id AND l.status='A' AND (l.from_date BETWEEN '$this->startDate' AND '$this->endDate') AND (l.to_date BETWEEN '$this->startDate' AND '$this->endDate') 
					LEFT JOIN emp_absences a
					ON z.employee_id = a.employee_id AND a.absent_date BETWEEN '$this->startDate' AND '$this->endDate'
					LEFT join weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					LEFT JOIN holidays_event h ON (h.start_date BETWEEN '$this->startDate' AND '$this->endDate') 
					WHERE s.is_day IS NOT NULL 
					ORDER BY z.employee_id,date_time )q
					GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d'),weeks,absent_date,start_date ORDER BY employee_id) t1
					LEFT JOIN 
					( SELECT employee_id emp_id,DATE_FORMAT(date_time,'%Y-%m-%d') dt,COUNT(work_day) cnt FROM (
					SELECT z.employee_id,
						  (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
					       ELSE date_time END) date_time,
					      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
					       ELSE date_time END) work_day
					FROM (
					SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
								IF(from_date<'$this->startDate','$this->startDate',from_date) from_date,
								IF(to_date='0000-00-00' OR to_date IS NULL ,'$this->endDate',to_date) to_date,IFNULL(status,0) device_status
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  w.enabled = 1 $attSearchIds 
						AND ((NOT (from_date > '$this->endDate' OR to_date < '$this->startDate' )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$this->endDate' )) ORDER BY w.employee_id  ) z
					LEFT JOIN company_shifts s
					ON z.shift_id = s.shift_id
					LEFT JOIN device_users du
					ON z.employee_id = du.employee_id
					LEFT JOIN employee_biometric b
					ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
					WHERE s.is_day IS NOT NULL  
					ORDER BY z.employee_id,work_day) g
					GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')) t2
					ON t1.employee_id = t2.emp_id
					GROUP BY t1.employee_id,t1.check_in,t2.dt,t1.absent_date,weeks,start_date) r
					GROUP BY employee_id;";
		
		//echo $query3; die();
		$stmt = mysqli_query ( $this->conn,$query3);
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//return $json;
		return array ((($stmt)?TRUE:FALSE),(($stmt)?" successfull":" Failed"),(($stmt)?$json:mysqli_error ( $this->conn )));
		
	}

	function setelapsedIt() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE compensation_requests SET is_processed = ?,approved_by = ? WHERE compoff_id = ? AND employee_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->is_processed, $this->approved_by, $this->compoff_id,$this->employee_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return self::getComoffLeave($this->employee_id,0)[0];
	}
	
	function getLopDetails($from_dt,$emp_id){
		$json=array();
		$to_dt= date('Y-m-d', strtotime("+1 month", strtotime($from_dt)));
		/*$stmt = "SELECT IF(lr.from_date=lr.to_date,CONCAT(DATE_FORMAT(lr.from_date,'%b %d,%Y'),' (1d)'),CONCAT(DATE_FORMAT(lr.from_date,'%b %d,%Y'),'-',DATE_FORMAT(lr.to_date,'%b %d,%Y'),' (',SUBSTRING_INDEX(lr.duration,'.',1),'d)')) date
				,IF(SUBSTRING_INDEX(lr.duration,'.',1)=COUNT(lr.leave_type),UPPER(lr.leave_type),CONCAT(UPPER(lr.leave_type),'(',COUNT(lr.leave_type),') Lop(',(SUBSTRING_INDEX(lr.duration,'.',1)-COUNT(lr.leave_type)),')')) Leave_cnt,lr.reason
				FROM leave_requests lr
				LEFT JOIN emp_absences a ON lr.employee_id = a.employee_id AND lr.request_id = a.request_id
				WHERE lr.employee_id='$emp_id' AND lr.from_date BETWEEN '$from_dt' AND '$to_dt' 
				AND lr.to_date BETWEEN '$from_dt' AND '$to_dt' 
				AND a.absent_date BETWEEN '$from_dt' AND '$to_dt' GROUP BY leave_type ORDER BY lr.from_date;";*/
		$stmt = "SELECT DISTINCT DATE_FORMAT(a.absent_date,'%b %d,%Y') abs_date,UPPER(a.leave_rule_type) leave_rule
				 FROM emp_absences a
				 WHERE a.employee_id='$emp_id' AND a.leave_rule_type = 'lop'
				 AND a.absent_date BETWEEN '$from_dt' AND DATE_SUB('$to_dt',INTERVAL 1 DAY);";
		
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		//return $json;
		return array (
				'result' => (($result)?TRUE:FALSE),
				'data' =>(($result)?$json:mysqli_error ( $this->conn ))
		);
	}

	function getTimeDetails($emp_id,$date){
		$json=array();
		$WorkDetailsQuery ="SELECT employee_id,shift_name,DATE_FORMAT(check_in,'%H:%i') check_in,check_out,SUBSTRING_INDEX(work_hours,':',2) work_hours,is_day,
				         -- IF((is_day !=0 OR (is_day=0 AND check_in NOT BETWEEN '00:00' AND end_time))AND check_in > start_time,SUBSTRING_INDEX(TIMEDIFF(check_in,start_time),'.',1),
          					-- IF(is_day=0 AND check_in BETWEEN '00:00' AND end_time,SUBSTRING_INDEX(TIMEDIFF(DATE_FORMAT(date_time,'%Y-%m-%d %T'),CONCAT(DATE_FORMAT(DATE_SUB(date_time,INTERVAL 1 DAY),'%Y-%m-%d'),' ',start_time)),'.',1),0)) late,
          				 IF(check_in > CONCAT(DATE_FORMAT(date_time,'%Y-%m-%d'),' ',start_time,':00'),SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,CONCAT(DATE_FORMAT(date_time,'%Y-%m-%d'),' ',start_time,':00')))),':',2),'0') late,
				         IF(work_hours < shift_hrs,SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(shift_hrs,work_hours),'.',1),':',2),0) less_work_hrs,
				         IF(work_hours > shift_hrs,IF(SUBSTRING_INDEX(TIMEDIFF(work_hours,shift_hrs),'.',1)>= min_hrs_ot,SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(work_hours,shift_hrs),'.',1),':',2),0),0) OT
				FROM (
				SELECT employee_id,shift_name,MIN(date_time) check_in,date_time,
					   IF(is_day =1,IF((COUNT(date_time)%2)=0,DATE_FORMAT(MAX(date_time),'%H:%i'),''),IF((COUNT(date_time)%2)=0,DATE_FORMAT(MAX(date_time),'%H:%i'),'')) check_out,
				       IF(is_day=1,IF((COUNT(date_time)%2)=0,TIMEDIFF(MAX(date_time),MIN(date_time)),''),IF((COUNT(date_time)%2)=0,TIMEDIFF(MAX(date_time),MIN(date_time)),'')) work_hours,
				       start_time,end_time,early_start,late_end,min_hrs_half_day,shift_hrs,min_hrs_ot,is_day
				FROM (
				SELECT b.employee_id,b.date_time,s.shift_name
				       ,(CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
				      ELSE date_time END) work_day,s.is_day,SUBSTRING_INDEX(s.start_time,' ',1) start_time,SUBSTRING_INDEX(s.end_time,' ',1) end_time,
				      s.early_start,s.late_end,s.min_hrs_half_day,s.shift_hrs,CONCAT(s.min_hrs_ot,':00') min_hrs_ot
				FROM employee_biometric b
				LEFT JOIN device_users du
				ON b.employee_id = du.ref_id 
				LEFT JOIN employee_work_details w
				ON du.employee_id = w.employee_id
				LEFT JOIN shift_roaster r
				ON du.employee_id = r.employee_id AND ((r.from_date <= '$date' AND r.to_date >= '$date') OR r.from_date IS NULL OR r.to_date IS NULL)
				INNER JOIN company_shifts s
				ON IFNULL(r.shift_id,IF(w.shift_id = 'Nil' OR w.shift_id = '','SH00001',w.shift_id)) = s.shift_id
				WHERE w.employee_id = '$emp_id' AND IF(s.is_day = 1,DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date',DATE_FORMAT(b.date_time,'%Y-%m-%d') BETWEEN '$date' AND DATE_ADD('$date',INTERVAL 1 DAY))) a
				WHERE DATE_FORMAT(work_day,'%Y-%m-%d')='$date') z;";
	//echo $WorkDetailsQuery;
		/*$PunchesQuery2 ="SELECT b.id,b.employee_id,DATE_FORMAT(date_time,'%Y-%m-%d') punch_date,DATE_FORMAT(date_time,'%H:%i') punches,b.date_time,
								IFNULL(r.shift_id,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id)) shift_id,IFNULL(REPLACE(b.reason,' ','-'),'') reason,b.is_manual,IFNULL(b.updated_by,'') updated_by
				       			,(CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
				     			 ELSE date_time END) work_day
						FROM employee_biometric b
						LEFT JOIN device_users du
						ON b.employee_id = du.ref_id
						LEFT JOIN employee_work_details w
						ON du.employee_id = w.employee_id
						LEFT JOIN shift_roaster r
						ON w.employee_id = r.employee_id AND ((r.from_date <= '$date' AND r.to_date >= '$date') OR r.from_date IS NULL OR r.to_date IS NULL)
						INNER JOIN company_shifts s  
						ON IFNULL(r.shift_id,IF(w.shift_id = 'Nil' OR w.shift_id = '','SH00001',w.shift_id)) = s.shift_id
						WHERE w.employee_id = '$emp_id' AND IF(s.is_day = 1,DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date',DATE_FORMAT(b.date_time,'%Y-%m-%d') BETWEEN '$date' AND DATE_ADD('$date',INTERVAL 1 DAY));";
		*/
		$PunchesQuery2 ="SELECT id,employee_id,DATE_FORMAT(date_time,'%H:%i') punches,shift_id,reason,is_manual,updated_by,payroll_status
						FROM (
						SELECT b.id,b.employee_id,b.date_time,(CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
										ELSE date_time END) work_day,
						IFNULL(r.shift_id,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id)) shift_id,
						IFNULL(REPLACE(b.reason,' ','-'),'') reason,b.is_manual,IFNULL(b.updated_by,'') updated_by,IF(month_year IS NOT NULL,1,0) payroll_status
						FROM employee_biometric b
						LEFT JOIN device_users d
						ON b.employee_id = d.ref_id
						INNER JOIN employee_work_details w
						ON d.employee_id = w.employee_id
						LEFT JOIN shift_roaster r
						ON w.employee_id = r.employee_id AND ((r.from_date <= '$date' AND r.to_date >= '$date') OR r.from_date IS NULL OR r.to_date IS NULL)
						INNER JOIN company_shifts s
						ON IFNULL(r.shift_id,IF(w.shift_id = 'Nil' OR w.shift_id = '','SH00001',w.shift_id)) = s.shift_id
						LEFT JOIN payroll p
						ON d.employee_id = p.employee_id AND DATE_FORMAT(p.month_year,'%Y-%m-%d') BETWEEN '$this->startDate' AND '$this->endDate'
						AND DATE_FORMAT('$date','%Y-%m-%d') BETWEEN '$this->startDate' AND '$this->endDate'
						-- DATE_FORMAT(p.month_year,'%Y-%m-%d') =DATE_FORMAT('$date','%Y-%m-01') 
						WHERE w.employee_id='$emp_id' AND
						IF(is_day !=0,DATE_FORMAT(b.date_time,'%Y-%m-%d')='$date',DATE_FORMAT(b.date_time,'%Y-%m-%d')
						BETWEEN '$date' AND DATE_ADD('$date',INTERVAL 1 DAY)))t
						WHERE DATE_FORMAT(work_day,'%Y-%m-%d')='$date' ORDER BY date_time;";
		//echo $PunchesQuery2;
		$result = mysqli_query ( $this->conn, $WorkDetailsQuery);
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		array_push ( $json, $row );
	
		$no_punches = mysqli_query ( $this->conn, $PunchesQuery2);
		while ( $row1 = mysqli_fetch_array ( $no_punches, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row1 );
		}
		$emp_id = $date.'_'.$emp_id;
		
		return array ((($result)?TRUE:FALSE),(($result)?" successfull $emp_id":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
		
	}
	
	function updateTimeDetails($employee_id,$date,$dayVal,$checkin,$checkout,$changeReason,$edit,$old_checkin,$old_checkout,$updated_by){
	    
	    $stmt = "SELECT ref_id FROM device_users WHERE employee_id='$employee_id'";
	    $result= mysqli_query ( $this->conn, $stmt );
	    $row= mysqli_fetch_array ( $result, MYSQLI_ASSOC );
	    $emp_id = implode($row);
	    $punch_time = array();
	    
	    if($old_checkin == '' && $old_checkout ==''){
	        $punch_time [] = date('H:i:s', strtotime($checkin));
	        $punch_time [] = date('H:i:s', strtotime($checkout));
	    }else if($old_checkin == ''){
	        $punch_time [] = date('H:i:s', strtotime($checkin));
	    }else if($old_checkout == ''){
	        $punch_time [] = date('H:i:s', strtotime($checkout));
	    }else if((date('H:i:s', strtotime($old_checkin)))!= (date('H:i:s', strtotime($checkin)))){
	        $punch_time [] = date('H:i:s', strtotime($checkin));
	    }else if((date('H:i:s', strtotime($old_checkout)))!= (date('H:i:s', strtotime($checkout)))){
	        $punch_time [] = date('H:i:s', strtotime($checkout));
	    }else if(((date('H:i:s', strtotime($old_checkin)))!= (date('H:i:s', strtotime($checkin))))&&((date('H:i:s', strtotime($old_checkout)))!= (date('H:i:s', strtotime($checkout))))){
	        $punch_time [] = date('H:i:s', strtotime($checkin));
	        $punch_time [] = date('H:i:s', strtotime($checkout));
	    }
	    foreach($punch_time as $check){
	    	if($dayVal==0 && date('H:i:s', strtotime($check)) > date('H:i:s',strtotime('00:00:00')) && date('H:i:s', strtotime($check)) <= date('H:i:s',strtotime('06:00:00')))
	    		$date = date('Y-m-d', strtotime($date. ' +1 day'));
	    		    	
	        $dateTime= date('Y-m-d', strtotime($date))." ".date('H:i:s', strtotime($check));
	        
	        $stmt = mysqli_prepare ( $this->conn, "INSERT INTO employee_biometric
          									(date_time,employee_id,reason,is_manual,updated_by)
          									VALUES (?,?,?,?,?)" );
	        
	        mysqli_stmt_bind_param ( $stmt, 'sssss',$dateTime, $emp_id,$changeReason,$edit,$updated_by );
	        $result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
			
			$summary_insert = "CALL ATTENDANCE_SUMMARY_INSERT($emp_id,'{$dateTime}')";
			$query=mysqli_query($this->conn,$summary_insert);
	    }
	    return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$date.'_'.$employee_id:mysqli_error ( $this->conn )));
	}
	
	function punchesDelete ($id){
		//echo $id; die();
		$stmt = "SELECT CONCAT(DATE_FORMAT(date_time,'%Y-%m-%d'),'_',du.employee_id) empId_dt FROM employee_biometric b INNER JOIN device_users du
					ON b.employee_id = du.ref_id WHERE b.id='$id';";
		$result= mysqli_query ( $this->conn, $stmt );
		$row= mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		$emp_id = implode($row);
	
		$deleteStmt = "DELETE FROM employee_biometric WHERE id='$id';";
		$result= mysqli_query ( $this->conn, $deleteStmt);
	
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$emp_id:mysqli_error ( $this->conn )));
	}
	
function attnSummaryInsert($employee){
		$from_dt = $this->startDate;
		$from_dt=date("Y-m-d",strtotime("{$from_dt}"));
		$to_dt = $this->endDate;
		$employeeID = "'".str_replace(",","','",$employee)."'";
		
		$deleteStmt = "DELETE FROM attendance_summary WHERE days BETWEEN '$from_dt' AND '$to_dt' AND employee_id IN ($employeeID);";
		$result= mysqli_query ( $this->conn, $deleteStmt);
		/*
		$query ="INSERT INTO attendance_summary (employee_id,shift_id,shift_name,shift_st_time,shift_end_time,shift_hrs,days,checkIn,checkOut,work_hrs,pay_day,lateIn,earlyOut,ot,punches)  (     
				SELECT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,worked_hrs,
				       IF(worked_hrs >=min_hrs_full_day,1,IF((worked_hrs BETWEEN min_hrs_half_day AND min_hrs_full_day),'0.5','0')) pay_day,SUBSTRING_INDEX(min_xtra_hrs,'|',1) late,
				       SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',2),'|',-1) early_out,SUBSTRING_INDEX(min_xtra_hrs,'|',-1) ot,all_punches
				FROM (
				SELECT employee_id,ref_id,shift_id,dates,check_in,check_out,shift_hrs,
				       -- IF((is_day!=0 OR (is_day=0 AND DATE_FORMAT(check_in,'%H:%i') BETWEEN '00:00' AND end_time)),SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),SUBSTRING_INDEX(TIMEDIFF(DATE_ADD(check_out,INTERVAL 1 DAY),check_in),'.',1)) worked_hrs,
					   SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1) worked_hrs,
				       	Calculate_OT(dates,SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),check_in,check_out,start_time,end_time,shift_hrs,min_hrs_ot,is_day) min_xtra_hrs,start_time,end_time,shift_name,min_hrs_half_day,min_hrs_full_day,all_punches
				FROM ( 
				
				SELECT employee_id,ref_id,shift_id,(CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d') 
				                                WHEN is_day=0  THEN DATE_FORMAT(work_day,'%Y-%m-%d') END) 'dates',
				                          (CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d %T') 
				                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d %T'),DATE_FORMAT(work_day,'%Y-%m-%d %T'))  
				                                 END) 'check_in',
				                          (CASE WHEN is_day=1 OR is_day=0 THEN DATE_FORMAT(MAX(date_time),'%Y-%m-%d %T') 
				                                END) 'check_out',
				                        employee_doj,is_day,shift_hrs,start_time,end_time,min_hrs_ot,shift_name,min_hrs_half_day,min_hrs_full_day
				FROM ( 
				      SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
							(CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
				                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj,s.shift_hrs,s.min_hrs_half_day,s.min_hrs_full_day,s.min_hrs_ot,s.shift_name
				      FROM (
				      SELECT w.employee_id,u.ref_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,
				            IF(r.shift_id IS NULL,'$from_dt',IF(r.to_date IS NULL AND r.from_date <'$from_dt' OR r.from_date < '$from_dt',
				            '$from_dt',DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IFNULL(IF(r.to_date>'$to_dt','$to_dt',r.to_date),'$to_dt') to_date,
				            w.employee_doj,w.employee_name,w.employee_lastname,w.branch_id empBranch 
				      FROM employee_work_details w 
				      INNER JOIN device_users u
				      ON w.employee_id = u.employee_id
				      LEFT JOIN  shift_roaster r 
				      ON w.employee_id = r.employee_id AND (r.from_date BETWEEN '$from_dt' AND '$to_dt'  OR r.from_date <= '$from_dt' )
				      AND (r.to_date BETWEEN '$from_dt' AND '$to_dt' OR r.to_date IS NULL)
				      WHERE w.enabled = 1 ORDER BY w.employee_id) z
				      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
				      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date 
				      WHERE s.is_day IS NOT NULL ORDER BY employee_id,date_time )q 
				      GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d'))c
					  INNER JOIN (SELECT employee_id EMPID,Name,date,DATE_FORMAT(date,'%d %b,%Y') Date_Formatted,GROUP_CONCAT(punch ORDER BY date_time) all_punches 
                      FROM (
                      SELECT employee_id,Name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') date,DATE_FORMAT(work_day,'%H:%i') punch
                      FROM (
                           SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,
                                 (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
                                  ELSE date_time END) work_day
                           FROM (
                           SELECT w.employee_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,IF(r.shift_id IS NULL,'2017-11-02',IF(r.to_date IS NULL AND r.from_date <'2017-11-02','2017-11-02',DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IFNULL(r.to_date,'2017-11-28') to_date
                                  ,w.employee_doj,w.employee_name,w.employee_lastname,w.branch_id empBranch
                           FROM employee_work_details w
                           LEFT JOIN  shift_roaster r
                           ON w.employee_id = r.employee_id AND (r.from_date BETWEEN '$from_dt' AND '$to_dt'  OR r.from_date <= '$from_dt' )
                                AND (r.to_date BETWEEN '$from_dt' AND '$to_dt' OR r.to_date IS NULL)
                           WHERE w.enabled = 1 ORDER BY w.employee_id ) z
                           LEFT JOIN company_shifts s
                           ON z.shift_id = s.shift_id
                           LEFT JOIN device_users du
                           ON z.employee_id = du.employee_id
                           LEFT JOIN employee_biometric b
                           ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
                           WHERE s.is_day IS NOT NULL
                           ORDER BY z.employee_id,work_day) q
                      GROUP BY employee_id,work_day
                      )j GROUP BY employee_id,date) t
                      ON c.employee_id = t.EMPID AND dates = t.date
				)w);";
		
		*/
		$employees = explode(',',$employee);
		
		foreach ($employees as $emp_id) {
		$query ="INSERT INTO attendance_summary(employee_id,shift_id,shift_name,shift_st_time,shift_end_time,shift_hrs
				,days,checkIn,checkOut,tot_worked,work_hrs,pay_day,lateIn,earlyOut,ot,punches)  (     
						SELECT DISTINCT q.employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,tot_worked,worked_hours,
		              pay_day,IF(day_type='FH','-',late) late,IF(day_type='SH','-',early_out) early_out,ot,all_punches
		        FROM (
		        SELECT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,tot_worked,day_type,
				       IF(SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) >=min_hrs_full_day,1,IF((SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) BETWEEN min_hrs_half_day AND min_hrs_full_day),'0.5','0')) pay_day,SUBSTRING_INDEX(min_xtra_hrs,'|',1) late,
				       SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',2),'|',-1) early_out,SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) worked_hours,SUBSTRING_INDEX(min_xtra_hrs,'|',-1) ot,all_punches
				FROM (
				SELECT employee_id,ref_id,shift_id,dates,check_in,check_out,shift_hrs,day_type,
				      SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1) tot_worked,late_end,
				      Calculate_OT(dates,SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),check_in,check_out,start_time,end_time,shift_hrs,min_hrs_ot,late_end,is_day) min_xtra_hrs,start_time,end_time,shift_name,min_hrs_half_day,min_hrs_full_day,all_punches
				FROM ( 
				SELECT * FROM (
								SELECT employee_id,ref_id,shift_id,(CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d') 
				                                WHEN is_day=0  THEN DATE_FORMAT(work_day,'%Y-%m-%d') END) 'dates',
				                          (CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d %T') 
				                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_FORMAT(DATE_ADD(MIN(work_day),INTERVAL 1 DAY),'%Y-%m-%d %T'),DATE_FORMAT(MIN(work_day),'%Y-%m-%d %T'))  
				                                 END) 'check_in',
				                          (CASE WHEN is_day=1 OR is_day=0 THEN DATE_FORMAT(MAX(date_time),'%Y-%m-%d %T') 
				                                END) 'check_out',from_date,to_date,
				                        employee_doj,is_day,shift_hrs,start_time,end_time,min_hrs_ot,shift_name,min_hrs_half_day,min_hrs_full_day,late_end
				FROM ( SELECT * FROM (
				      SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
				      s.shift_hrs,s.min_hrs_half_day,s.min_hrs_full_day,s.min_hrs_ot,s.shift_name,
				            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
				                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj
				      FROM (
					      SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      		IF(from_date<'$from_dt','$from_dt',from_date) from_date,
					      		IF(to_date='0000-00-00' OR to_date IS NULL ,'$to_dt',to_date) to_date
							FROM shift_roaster r
							INNER JOIN employee_work_details w
							ON r.employee_id = w.employee_id
							LEFT JOIN device_users u
							ON w.employee_id = u.employee_id
							WHERE  r.employee_id='$emp_id'
							AND ((NOT (from_date > '$to_dt' OR to_date < '$from_dt' )) OR
							((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$to_dt' )) ) z
				      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
				      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
				      WHERE s.is_day IS NOT NULL ORDER BY employee_id,date_time  )q WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN from_date AND to_date )t
				      GROUP BY employee_id,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d'))w WHERE dates BETWEEN from_date AND to_date)c
              		  INNER JOIN (SELECT employee_id EMPID,Name,date,DATE_FORMAT(date,'%d %b,%Y') Date_Formatted,GROUP_CONCAT(punch ORDER BY date_time) all_punches 
                      FROM (
                      SELECT employee_id,Name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') date,DATE_FORMAT(work_day,'%H:%i') punch
                      FROM (
                           SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,
                                 (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
                                  ELSE date_time END) work_day
                           FROM (
	                            SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
							        IF(from_date<'$from_dt','$from_dt',from_date) from_date,
							        IF(to_date='0000-00-00' OR to_date IS NULL ,'$to_dt',to_date) to_date
								FROM shift_roaster r
								INNER JOIN employee_work_details w
								ON r.employee_id = w.employee_id
								LEFT JOIN device_users u
								ON w.employee_id = u.employee_id
								WHERE  r.employee_id='$emp_id'
								AND ((NOT (from_date > '$to_dt' OR to_date < '$from_dt' )) OR
								((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$to_dt' )) ) z
		                           LEFT JOIN company_shifts s
                           ON z.shift_id = s.shift_id
                           LEFT JOIN device_users du
                           ON z.employee_id = du.employee_id
                           LEFT JOIN employee_biometric b
                           ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
                           WHERE s.is_day IS NOT NULL
                           ORDER BY z.employee_id,work_day) q
                      GROUP BY employee_id,work_day
                      )w GROUP BY employee_id,date) t
                    ON c.employee_id = t.EMPID AND dates = t.date
                    LEFT JOIN (SELECT ab.employee_id absent_id,ab.absent_date,ab.day_type
					FROM emp_absences ab
					WHERE ab.day_type!='FD' AND ab.absent_date BETWEEN '$from_dt' AND '$to_dt') p
					ON c.employee_id = p.absent_id AND dates = p.absent_date 
                    WHERE dates BETWEEN '$from_dt' AND '$to_dt')w
			      UNION 
			      SELECT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,'' tot_worked,worked_hrs,day_type,pay_day,late,early_out,ot,all_punches
            FROM (
			      SELECT employee_id,from_date,to_date,z.shift_id,shift_name,start_time,end_time,'' shift_hrs,dates,'' check_in,'' check_out,'' worked_hrs,'' day_type,'' pay_day,'' late,'' early_out,'' ot,'' all_punches
			FROM (
			SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<'$from_dt','$from_dt',from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,'$to_dt',to_date) to_date
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  r.employee_id='$emp_id'
						AND ((NOT (from_date > '$to_dt' OR to_date < '$from_dt' )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$to_dt' )))z
	  		LEFT JOIN company_shifts s ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = s.shift_id
	  		JOIN (SELECT date dates FROM 
              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) date FROM
               (SELECT 0 t0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
               (SELECT 0 t1 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
               (SELECT 0 t2 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
               (SELECT 0 t3 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
               (SELECT 0 t4 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
              WHERE date BETWEEN '$from_dt' AND '$to_dt') t
  			  WHERE dates NOT IN (SELECT dates FROM (
  			  					SELECT * FROM (
								SELECT employee_id,ref_id,shift_id,(CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d') 
				                                WHEN is_day=0  THEN DATE_FORMAT(work_day,'%Y-%m-%d') END) 'dates',
				                          (CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d %T') 
				                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d %T'),DATE_FORMAT(work_day,'%Y-%m-%d %T'))  
				                                 END) 'check_in',
				                          (CASE WHEN is_day=1 OR is_day=0 THEN DATE_FORMAT(MAX(date_time),'%Y-%m-%d %T') 
				                                END) 'check_out',from_date,to_date,
				                        employee_doj,is_day,shift_hrs,start_time,end_time,min_hrs_ot,shift_name,min_hrs_half_day,min_hrs_full_day,late_end
				FROM ( 
				      SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
				      s.shift_hrs,s.min_hrs_half_day,s.min_hrs_full_day,s.min_hrs_ot,s.shift_name,
				            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
				                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj
				      FROM (
				      SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<'$from_dt','$from_dt',from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,'$to_dt',to_date) to_date
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  r.employee_id='$emp_id'
						AND ((NOT (from_date > '$to_dt' OR to_date < '$from_dt' )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$to_dt' )) ) z
				      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
				      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
				      WHERE s.is_day IS NOT NULL ORDER BY employee_id,date_time)q
              GROUP BY employee_id,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d'))w )c)) p
			WHERE dates BETWEEN from_date AND to_date 
			ORDER BY employee_id,dates)q
		LEFT JOIN payroll_preview_temp pt
        ON q.employee_id=pt.employee_id
        WHERE pt.attn_lock !=1);";
		//echo $query; die();
		$stmt = mysqli_prepare ( $this->conn,$query);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		
			$updateWeekoff= "UPDATE attendance_summary s
							JOIN (
						             SELECT employee_id,shift_id,days,is_weekday,is_holiday,is_leave,IF(is_weekday IS NOT NULL AND is_leave='' AND is_holiday='',is_weekday,IF(is_holiday!='',is_holiday,IF(is_leave!='',is_leave,'W'))) type
						              FROM (
						    				SELECT employee_id,shift_id,days,IF(is_weekday='WE',1,IF(is_weekday='WE-FH' OR is_weekday='WE-SH',CONCAT(SUBSTRING_INDEX(is_weekday,'-',-1),'-0.5'),NULL)) is_weekday,is_holiday,is_leave
						                  FROM (
						    						  SELECT s.employee_id,s.shift_id,days,
						    						        (CASE WHEN (weeks = IF((WEEK(days) - WEEK(DATE_FORMAT(days , '%Y-%m-01')) + 1)>5,(WEEK(days) - WEEK(DATE_FORMAT(days , '%Y-%m-01')) - 1),WEEK(days) - WEEK(DATE_FORMAT(days , '%Y-%m-01')) + 1)) 
						    						                                    		THEN (CASE WHEN (DAYNAME(days)='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),IF((sunday = 'FH' OR sunday = 'SH'),CONCAT('WE','-',sunday),'WE'),sunday) WHEN (DAYNAME(days)='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),IF((monday = 'FH' OR monday = 'SH'),CONCAT('WE','-',monday),'WE'),monday) WHEN (DAYNAME(days)='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),IF((Tuesday = 'FH' OR Tuesday = 'SH'),CONCAT('WE','-',Tuesday),'WE'),Tuesday) WHEN (DAYNAME(days)='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),IF((wednesday = 'FH' OR wednesday = 'SH'),CONCAT('WE','-',wednesday),'WE'),wednesday) WHEN (DAYNAME(days)='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),IF((thursday = 'FH' OR thursday = 'SH'),CONCAT('WE','-',thursday),'WE'),thursday) WHEN (DAYNAME(days)='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),IF((friday = 'FH' OR friday = 'SH'),CONCAT('WE','-',friday),'WE'),friday) WHEN (DAYNAME(days)='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),IF((saturday = 'FH' OR saturday = 'SH'),CONCAT('WE','-',saturday),'WE'),saturday) ELSE '' END)
						    						              END) is_weekday,
						    						        IFNULL((CASE WHEN ((start_date BETWEEN days AND days) 
						    						                  OR (end_date BETWEEN days AND days)) 
						    						                  THEN (CASE WHEN (category = 'OPTIONAL' AND h.branch_id = wd.branch_id ) THEN 'RH' 
						    						                             WHEN (category = 'HOLIDAY' AND h.branch_id = 'NA' ) THEN 'GH' END) END),'') is_holiday,
						                        IFNULL(CONCAT(leave_rule_type,'-',day_count),'') is_leave
						    						  FROM attendance_summary s
						    						  INNER JOIN employee_work_details wd
						    						  ON s.employee_id = wd.employee_id
						    						  LEFT JOIN weekend w
						    						  ON s.shift_id = w.shift_id
						    						  LEFT JOIN holidays_event h ON (h.start_date BETWEEN '$from_dt' AND '$to_dt') 
						    						  AND s.days BETWEEN h.start_date AND h.end_date
						                  LEFT JOIN emp_absences a ON a.employee_id = '$emp_id' 
						                  AND a.absent_date = s.days AND a.absent_date BETWEEN '$from_dt' AND '$to_dt'
						    			  WHERE s.employee_id = '$emp_id' AND days BETWEEN '$from_dt' AND '$to_dt' )z
						    			  WHERE is_weekday IS NOT NULL
						    			  ORDER BY employee_id,days)z
						              WHERE is_weekday IS NOT NULL OR is_holiday IS NOT NULL OR is_leave IS NOT NULL  )t
								ON s.employee_id = t.employee_id AND s.days = t.days
								SET s.day_type = t.type;";
		
			$result= mysqli_query ( $this->conn, $updateWeekoff);
		
			$updteRegularaization = "UPDATE attendance_summary ar
								INNER JOIN attendance_regularization re
								ON ar.employee_id = re.employee_id AND re.day = ar.days
								SET late_approved = (CASE WHEN re.regularize_type='Late' AND re.status = 'A' AND re.day = ar.days THEN 1 ELSE 0 END),
									early_approved = (CASE WHEN re.regularize_type='EarlyOut' AND re.status = 'A' AND re.day = ar.days  THEN 1 ELSE 0 END)
								WHERE ar.employee_id='$emp_id' AND re.day BETWEEN '$from_dt' AND '$to_dt'
								AND ar.days BETWEEN '$from_dt' AND '$to_dt';";
			$result= mysqli_query ( $this->conn, $updteRegularaization);
		
			$UpdateLateLop ="UPDATE payroll_preview_temp p
							INNER JOIN employee_work_details w
							ON p.employee_id= w.employee_id
							SET  
							late_lop =IFNULL((SELECT IF(lop_value='',0,lop_value) llop FROM latelop_slab WHERE (SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(late_early_time))) FROM (SELECT days,IF((pay_day IN(0,0.5) AND day_type='W') OR (SUBSTRING_INDEX(day_type,'-',-1)=1),'',lateIn) late_early_time ,SEC_TO_TIME((TIME_TO_SEC(lateIn)))FROM attendance_summary s INNER JOIN employee_work_details w ON s.employee_id = w.employee_id WHERE s.employee_id='$emp_id' AND s.shift_id!='SH63590' AND w.exempt_attn !=1 AND lateIn!='-' AND (day_type ='W' OR SUBSTRING_INDEX(day_type,'-',1) NOT IN(1,'FH')) AND day_type!=1 AND late_approved ='0' AND days BETWEEN '$from_dt' AND '$to_dt' 
									   UNION
									   SELECT days,IF((pay_day IN(0,0.5) AND day_type='W') OR (SUBSTRING_INDEX(day_type,'-',-1)=1),'',earlyOut) late_early_time,SEC_TO_TIME((TIME_TO_SEC(earlyOut))) FROM attendance_summary s INNER JOIN employee_work_details w ON s.employee_id = w.employee_id WHERE s.employee_id='$emp_id' AND s.shift_id!='SH63590' AND w.exempt_attn !=1 AND earlyOut!='-' AND (day_type ='W' OR SUBSTRING_INDEX(day_type,'-',1) NOT IN(1,'SH')) AND day_type!=1 AND early_approved ='0' AND days BETWEEN '$from_dt' AND '$to_dt')t) BETWEEN from_time AND to_time),0),
							alop =IFNULL((SELECT IFNULL(SUM(IF(day_type='W' AND pay_day<1,1-pay_day,IF(day_type!='W' AND SUBSTRING_INDEX(day_type,'-',-1)>pay_day,SUBSTRING_INDEX(day_type,'-',-1)-pay_day,''))),0) alop
									FROM attendance_summary WHERE employee_id='$emp_id' AND SUBSTRING_INDEX(day_type,'-',-1) IN('W',0.5) AND days BETWEEN '$from_dt' AND '$to_dt'),0)
						 WHERE p.employee_id='$emp_id' AND w.exempt_attn!=1;";
			$result= mysqli_query ( $this->conn, $UpdateLateLop);
		}
		return $result;
	}
	function getlateLOP($empID){
		$json=array();
		$emp_id='';
		foreach ($empID as $empID) {
			$emp_id.= "'".$empID."',";
		}
		$emp_id = rtrim($emp_id,',');
		$stmt = "SELECT w.employee_id, w.employee_name,(p.lop+p.alop) alop,p.late_lop
				FROM payroll_preview_temp p
				INNER JOIN employee_work_details w
				ON p.employee_id = w.employee_id
				WHERE w.enabled =1 AND w.employee_id IN ($emp_id);";
		//echo $stmt; die();
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
		//return $json;
		
	}
	
	function UpdateLateLOP($lopdata){
		$json=array();
		$data = explode(',',$lopdata);
		//print_r($data);
		foreach ($data as $empdata){
			//alop=SUBSTRING_INDEX(SUBSTRING_INDEX('$empdata','|',2),'|',-1),
			$query ="UPDATE payroll_preview_temp SET late_lop=SUBSTRING_INDEX('$empdata','|',-1), attn_lock=1 WHERE employee_id=SUBSTRING_INDEX('$empdata','|',1);";
			$result = mysqli_query ( $this->conn, $query );
		}
		return array ((($result)?TRUE:FALSE),(($result)?" successfull":" Failed"),(($result)?$json:mysqli_error ( $this->conn )));
		
	}
}

?>