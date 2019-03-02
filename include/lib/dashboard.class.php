<?php
/*
 * ----------------------------------------------------------
 * Filename : dashboard.class.php
 * Author : Rufus Jackson
 * Database :
 * Oper : Dashboard Data Loading
 *
 * ----------------------------------------------------------
 */
class Dashboard {
	/* Member variables */
	var $widget_id;
	var $widget_header;
	var $enabled;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}

	/* Member functions */
	function getPayoutChartData($type, $current_payroll_month, $interval) {
		$a_json = array ();
		if ($type == 'gross') {
			$queryStmt = "SELECT DATE_FORMAT(month_year,'%b %Y') as month_year,
										        SUM(gross_salary) as yVal,
										        COUNT(employee_id) as employees
										FROM payroll
										WHERE month_year BETWEEN DATE_SUB('{$current_payroll_month}', INTERVAL {$interval} MONTH) AND '{$current_payroll_month}'
										GROUP BY month_year";
		} elseif ($type == 'deductions') {
			$queryStmt = "SELECT DATE_FORMAT(month_year,'%b %Y') as month_year,
										        SUM(total_deduction) as yVal,
										        COUNT(employee_id) as employees
										FROM payroll
										WHERE month_year BETWEEN DATE_SUB('{$current_payroll_month}', INTERVAL {$interval} MONTH) AND '{$current_payroll_month}'
										GROUP BY month_year";
		} elseif ($type == 'net') {
			$queryStmt = "SELECT DATE_FORMAT(month_year,'%b %Y') as month_year,
										        SUM(net_salary) as yVal,
										        COUNT(employee_id) as employees
										FROM payroll
										WHERE month_year BETWEEN DATE_SUB('{$current_payroll_month}', INTERVAL {$interval} MONTH) AND '{$current_payroll_month}'
										GROUP BY month_year";
		}
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getEmployeeWidgetData($current_payroll_month) {
		$a_json = array ();
		$month = date_format ( date_create ( $current_payroll_month ), 'n' );
		$year = date_format ( date_create ( $current_payroll_month ), 'Y' );
		$monthYear = ($month-1) . $year;
		$queryStmt = " SELECT dateofexit,
				       COUNT(CASE WHEN enabled = 1 THEN 1 ELSE NULL END) totalEmployees,
				       COUNT(CASE WHEN CONCAT(MONTH(employee_doj),YEAR(employee_doj)) = '{$monthYear}' AND enabled = 1 THEN 1 ELSE NULL END) NewJoinees,
				       COUNT(CASE WHEN CONCAT(MONTH(dateofexit),YEAR(dateofexit)) = '{$monthYear}'  AND enabled = 0 THEN 1 ELSE NULL END) NewExits
					   FROM employee_work_details w;";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getPayoutWidgetData() {
		$a_json = array ();
		$queryStmt = "SELECT month_year,FORMAT(FLOOR(SUM(net_salary)),0,'en_IN') as net,FORMAT(FLOOR(SUM(gross_salary)),0,'en_IN') as gross    
				FROM payroll
				GROUP BY month_year 
				ORDER BY month_year DESC 
				LIMIT 0,1";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		if ($a_json == [ ]) {
			$a_json [0] ['month_year'] = NULL;
			$a_json [0] ['net'] = 0;
			$a_json [0] ['gross'] = 0;
		}
		return $a_json;
	}
	function getEmployeeLastloginData($limit) {
		$a_json = array ();
		$queryStmt = "SELECT w.employee_name,p.employee_id,p.last_login FROM employee_personal_details p 
						LEFT JOIN employee_work_details w   ON  w.employee_id=p.employee_id  
						WHERE p.last_login != '0000-00-00' AND w.enabled=1
						ORDER BY 
						last_login DESC limit {$limit}";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getEmployeeBirthdayData($days) {
		$a_json = array ();
		$queryStmt = "SELECT p.employee_id ,(CASE WHEN DATE_FORMAT(p.employee_dob,'%d') = DATE_FORMAT(NOW(),'%d')THEN 'Today'
				      WHEN DATE_FORMAT(p.employee_dob,'%d') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d') THEN 'Tomorrow'
				      ELSE DATE_FORMAT(p.employee_dob,'%b %d')
				      END
				 ) AS dob,DATE_FORMAT(p.employee_dob,'%d') as day
				,p.employee_image ,w.employee_name ,p.employee_gender, EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM  p.employee_dob) age 
				FROM employee_personal_details p
				INNER JOIN  employee_work_details w ON p.employee_id=w.employee_id  AND w.enabled=1
				WHERE p.employee_dob + INTERVAL EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM p.employee_dob) YEAR
				BETWEEN CURRENT_DATE() AND CURRENT_DATE() + INTERVAL {$days} DAY ORDER BY DATE_FORMAT(employee_dob,'%m%d') ASC";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$row_1 = array ();
			$row_1 ['emp_id'] = $row ['employee_id'];
			$row_1 ['emp_name'] = $row ['employee_name'];
			$row_1 ['emp_img'] = $row ['employee_image'];
			$row_1 ['gender'] = $row ['employee_gender'];
			$row_1 ['bDay'] = $row ['dob'];
			$row_1 ['age'] = $row ['age'];
			array_push ( $a_json, $row_1 );
		}
		return $a_json;
	}
	function getEmployeeAniversaryData($days) {
		$a_json = array ();
		$queryStmt = "SELECT p.employee_id ,(CASE WHEN DATE_FORMAT(w.employee_doj,'%d') = DATE_FORMAT(NOW(),'%d')THEN 'Today'
					      WHEN DATE_FORMAT(w.employee_doj,'%d') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d') THEN 'Tomorrow'
					      ELSE DATE_FORMAT(w.employee_doj,'%b %d')
					      END
					 ) AS doj,DATE_FORMAT(p.employee_dob,'%d') as day
					,p.employee_image ,w.employee_name ,p.employee_gender, EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM  w.employee_doj) years 
					FROM employee_personal_details p
					INNER JOIN  employee_work_details w ON p.employee_id=w.employee_id  AND w.enabled=1
					WHERE  w.employee_doj + INTERVAL EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM w.employee_doj) YEAR
					BETWEEN CURRENT_DATE() AND CURRENT_DATE() + INTERVAL {$days} DAY ORDER BY DATE_FORMAT(employee_doj,'%m%d') ASC";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$row_1 = array ();
			$row_1 ['emp_id'] = $row ['employee_id'];
			$row_1 ['emp_name'] = $row ['employee_name'];
			$row_1 ['emp_img'] = $row ['employee_image'];
			$row_1 ['gender'] = $row ['employee_gender'];
			$row_1 ['jDay'] = $row ['doj'];
			$row_1 ['age'] = $row ['years'];
			array_push ( $a_json, $row_1 );
		}
		return $a_json;
	}
	
	function getEmployeeleaveRequest(){
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT  l.request_id,w.employee_name,l.employee_id,UPPER(l.leave_type) leave_type,
				IF(l.from_date!=l.to_date,CONCAT(DATE_FORMAT(l.from_date,'%b %e') ,' - ',DATE_FORMAT(l.to_date,'%b %e')),
                DATE_FORMAT(l.from_date,'%b %e')) fromDate,l.updated_on
				FROM leave_requests l LEFT JOIN company_leave_rules lr ON  l.leave_type = lr.leave_rule_id 
				INNER JOIN  employee_work_details w ON w.employee_id=l.employee_id 
				WHERE l.status ='RQ' and w.enabled=1  ORDER By l.from_date ASC;" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$row_1 = array ();
			$row_1 ['employee_id'] = $row ['employee_id'];
			$row_1 ['request_id'] = $row ['request_id'];
			$row_1 ['employee_name'] = $row ['employee_name'];
			$row_1 ['lr_type'] = $row ['leave_type'];
			$row_1 ['fromDate'] = $row ['fromDate'];
			$row_1 ['updated_on'] = $row ['updated_on'];
			array_push ( $a_json, $row_1 );
		}
		return $a_json;
		
	}
	function getPendingItDeclarationData($current_finYear, $limit) {
		$a_json = array ();
		$queryStmt = "SELECT w.employee_name,it.employee_id,it.updated_on 
						FROM employee_work_details w
						LEFT JOIN employee_it_declaration it   
						ON  w.employee_id=it.employee_id
						WHERE it.status_id = 'P'
						AND it.year = '{$current_finYear}' AND w.enabled=1
						ORDER BY it.updated_on DESC limit {$limit}";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	//function getUpcomingEventData($count) {
	function getUpcomingEventData($employe_id,$role) {
		$a_json = array ();
		if($role=='emp' && $employe_id!=null){
		$queryStmt = "SELECT CONCAT(crq.employee_id,'-',crq.status) employee_id ,h.holiday_id,h.category, h.title ,(CASE WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(NOW(),'%d%m')THEN 'Today'
		WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d%m') THEN 'Tomorrow'
		ELSE DATE_FORMAT(h.start_date,'%b %d')
		END
		) AS event_date,
		DATE_FORMAT(h.start_date,'%d/%m/%Y') start_date,h.end_date FROM holidays_event h
		LEFT JOIN compensation_requests crq
        ON h.start_date=crq.date AND employee_id='$employe_id' AND crq.status!='R'
	    WHERE h.start_date >= CURRENT_DATE()  AND h.category IN ( 'HOLIDAY','EVENT') ORDER BY h.start_date";
		}else if($role=='hr'){	    
	    $queryStmt = "SELECT DATE_FORMAT(h.start_date,'%d/%m/%Y') start_date,crq.employee_id,h.holiday_id,h.category, h.title ,(CASE WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(NOW(),'%d%m')THEN 'Today'
		WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d%m') THEN 'Tomorrow'
		ELSE DATE_FORMAT(h.start_date,'%b %d')
		END
		) AS event_date,h.end_date FROM
        holidays_event h
        LEFT JOIN compensation_requests crq
        ON h.start_date=crq.date AND crq.status!='R' AND h.category='HOLIDAY'
		WHERE h.start_date >= CURRENT_DATE() GROUP BY h.holiday_id  ORDER BY h.start_date";
		}
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$row_1 = array ();
			$row_1 ['hId'] = $row ['holiday_id'];
			$row_1 ['empId'] = $row ['employee_id'];
			$row_1 ['category'] = $row ['category'];
			$row_1 ['title'] = $row ['title'];
			$row_1 ['dateText'] = $row ['event_date'];
			$row_1 ['start'] = $row ['start_date'];
			$row_1 ['end'] = $row ['end_date'];
			array_push ( $a_json, $row_1 );
		}
		return $a_json;
	}
	
	/* Employee Dashboard Widget Data */
	function getEmployeePayoutWidgetData($employee_id, $current_payroll_month) {
		$a_json = array ();
		$queryStmt = "SELECT  month_year,FLOOR(SUM(net_salary)) as net,FLOOR(SUM(gross_salary)) as gross
				FROM payroll
				WHERE employee_id = '{$employee_id}'
				AND month_year = DATE_SUB('{$current_payroll_month}', INTERVAL 1 MONTH)
				GROUP BY month_year
				ORDER BY month_year DESC
				LIMIT 0,1";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		if ($a_json == [ ]) {
			$a_json [0] ['month_year'] = NULL;
			$a_json [0] ['net'] = 0;
			$a_json [0] ['gross'] = 0;
		}
		return $a_json;
	}
	// g
	function getEmployeePayoutChartData($type, $current_payroll_month, $interval, $employee_id) {
		$a_json = array ();
		if ($type == 'gross') {
			$queryStmt = "SELECT DATE_FORMAT(month_year,'%b %Y') as month_year,
			SUM(gross_salary) as yVal,
			COUNT(employee_id) as employees
			FROM payroll
			WHERE employee_id = '{$employee_id}' AND month_year BETWEEN DATE_SUB('{$current_payroll_month}', INTERVAL {$interval} MONTH) AND '{$current_payroll_month}'
			GROUP BY month_year";
		} elseif ($type == 'deductions') {
			$queryStmt = "SELECT DATE_FORMAT(month_year,'%b %Y') as month_year,
			SUM(total_deduction) as yVal,
			COUNT(employee_id) as employees
			FROM payroll
			WHERE employee_id = '{$employee_id}' AND month_year BETWEEN DATE_SUB('{$current_payroll_month}', INTERVAL {$interval} MONTH) AND '{$current_payroll_month}'
			GROUP BY month_year";
		} elseif ($type == 'net') {
			$queryStmt = "SELECT DATE_FORMAT(month_year,'%b %Y') as month_year,
			SUM(net_salary) as yVal,
			COUNT(employee_id) as employees
			FROM payroll
			WHERE employee_id = '{$employee_id}' AND month_year BETWEEN DATE_SUB('{$current_payroll_month}', INTERVAL {$interval} MONTH) AND '{$current_payroll_month}'
			GROUP BY month_year";
		}
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getEmployeeQuickLinks($type, $current_payroll_month, $interval, $employee_id) {
		$a_json = array ();
		if ($type == 'pay') {
			$queryStmt = "SELECT CONCAT ('Pay Slip, ',DATE_FORMAT(month_year,'%b %Y'))  particular,
			SUM(net_salary)  netSal, CONCAT('paySlips.php?monthYear=',DATE_FORMAT(month_year,'%m%Y')) url
			FROM payroll
			WHERE employee_id = '{$employee_id}' AND month_year BETWEEN DATE_SUB('{$current_payroll_month}', INTERVAL {$interval} MONTH) AND '{$current_payroll_month}'
			GROUP BY month_year";
		}
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getTaxChartData($employee_id, $fin_year) {
		$a_json = array ();
		
		$queryStmt = "SELECT ded,
			         CASE c.ded 
			            WHEN 'ded_80c' THEN 150000
			            WHEN 'ded_80d' THEN 45000
			            WHEN 'totalVsTaxable' THEN t.total_inc
			            WHEN 'payableVsPaid' THEN t.tax_payable
			         END granted,
			         CASE c.ded 
			            WHEN 'ded_80c' THEN ded_80c
			            WHEN 'ded_80d' THEN ded_80d
			            WHEN 'totalVsTaxable' THEN t.taxable_inc
			            WHEN 'payableVsPaid' THEN (SELECT IFNULL(SUM(c_it),0)  FROM payroll p WHERE p.employee_id='{$employee_id}' AND p.year = '{$fin_year}')
			         END availed
			    FROM employee_income_tax t 
			    CROSS JOIN
			  (
			    SELECT 'ded_80c' ded UNION ALL
			    SELECT 'ded_80d' UNION ALL
			    SELECT 'totalVsTaxable' UNION ALL
			    SELECT 'payableVsPaid'
			  ) c
				WHERE t.employee_id = '{$employee_id}' AND t.year = '{$fin_year}'";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function getLastexits($current_payroll_month) {
		$json = array ();	
	  $queryStmt = "SELECT np.employee_id,np.notice_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name ,DATE_FORMAT(np.last_working_date,'%M %e') last_working_date 
					FROM emp_notice_period np
					INNER JOIN employee_work_details w
					ON np.employee_id = w.employee_id
					WHERE DATE_FORMAT(np.last_working_date,'%m-%Y') =  DATE_FORMAT('{$current_payroll_month}','%m-%Y')
					AND np.status = 'A' AND w.enabled = 1;";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
	function getQuickLinkForSalaryStmt($current_payroll_month) {
		$json = array ();
		$queryStmt = "SELECT DISTINCT month_year,DATE_FORMAT(month_year,'%M %Y') monthName
		FROM payroll WHERE month_year BETWEEN DATE_SUB('{$current_payroll_month}',INTERVAL 2 MONTH)
		AND DATE_SUB('{$current_payroll_month}',INTERVAL 1 MONTH);";
		$result = mysqli_query ( $this->conn, $queryStmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
	
}
?>
