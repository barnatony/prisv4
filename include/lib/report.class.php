<?php

require_once (__DIR__ . "/component.class.php");
class Report {
	/* Member variables */
	var $title;
	var $report;
	var $components = array ();
	var $name;
	protected $generatedBy;
	protected $generatedOn;
	protected $reportParams = array ();
	var $conn;
	function __construct() {
		ini_set ( 'memory_limit', MEMORY_LIMIT );
		ini_set('max_execution_time', MAX_EXECUTION_TIME);
		date_default_timezone_set(DEFAULT_TIMEZONE);
		$this->reportParams ['type'] = "";
		$this->reportParams ['reportFor'] = "";
		$this->reportParams ['yearType'] = "";
		$this->reportParams ['from_period'] = "";
		$this->reportParams ['to_period'] = "";
		$this->reportParams ['from_date'] = "";
		$this->reportParams ['to_date'] = "";
		$this->reportParams ['filter_key'] = "";
		$this->reportParams ['filter_value'] = "";
		$this->reportParams ['isConsolidate'] = "";
		$this->reportParams ['isTable'] = "";
		$this->reportParams ['year'] = "";
		$this->reportParams ['reportType'] = "";
		$this->reportParams ['isCustom'] = "";
		$this->reportParams ['monthFor'] = "";
		$this->reportParams ['monYear'] = "";
		$this->reportParams ['year'] = "";
		$this->reportParams ['branchOnly'] = "";
		$this->reportParams ['teamOnly'] = "";
		$this->reportParams ['employeeServiceStatus'] = "";
		$this->reportParams ['empMasterDetail'] = "";
		$this->reportParams ['empPersonalDetail'] = "";
		$this->reportParams ['employeeStatus'] = "";
	}
	/*
	 * function __destruct() {
	 * mysqli_close ( $this->conn );
	 * }/*
	 * /* Member functions
	 */
	public function create($report) {
		$this->report = $report;
		if (method_exists ( $this, $report )) {
			return $this->$report ();
		} else {
			return FALSE;
		}
	}
	 function SS001() {
	 	$this->name = 'SALARY STATEMENT';
		$fromPeriod = explode ( '*', $this->reportParams ['from_period'] ) [1];
	 	$toPeriod = explode ( '*', $this->reportParams ['to_period'] ) [1];
	 
		$curenttitle = ($fromPeriod == $toPeriod) ? $fromPeriod : $fromPeriod . " TO " . $toPeriod;
		$subTitle = ($this->reportParams ['reportFor'] == 'M') ? "MONTH OF" : (($this->reportParams ['reportFor'] == 'Q') ? "QUARTER OF" : (($this->reportParams ['reportFor'] == 'HY') ? "HALF YEAR OF" : (($this->reportParams ['reportFor'] == 'Y') ? "YEAR OF" : '')));
		
		if ($this->reportParams ['isConsolidate'] == 0) {
			$this->title = 'SALARY STATEMENT FOR ' . $subTitle . ' ' . $curenttitle;
		
		} else if ($this->reportParams ['isConsolidate'] == 1) {
			$this->title = 'CONSOLIDATED SALARY STATEMENT FOR THE' . $subTitle . ' ' . $curenttitle;
		}
		
		$this->reportParams ['from_period'] = explode ( '*', $this->reportParams ['from_period'] ) [0];
		$this->reportParams ['to_period'] = explode ( '*', $this->reportParams ['to_period'] ) [0];
		
		Session::newInstance ()->_setRetirementParams ();
		$retirementArray = Session::newInstance ()->_get ( "retirementParams" );
		// prepare the Query for the table component
		$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
		$customFields = $this->reportParams ['isCustom'];
		$customFields= ",".str_replace("^"," ",$customFields);
		
		if ($this->reportParams ['isConsolidate'] == 0) {
			$allow = "";
			$miscallow = "";
			$dedu = "";
			$miscdedu = "";
			$retireAllow = "";
			$retireDedu = "";
			foreach ( $this->allowDeducArray ['A'] as $key => $val ) {
				$allow .= ",SUM(" . $val ['pay_structure_id'] . ") " . $val ['alias_name'];
			}
		
			if(count($retirementArray["retirementAllowString"])>0)
				$retireAllow .= str_replace ( ',', '+', substr ( $retirementArray["retirementAllowString"], 0, - 1 ) ) ;
			if(count($retirementArray["retirementAllowString"])>0)
				$retireAllow = rtrim($retireAllow,"+");
			if(count($retirementArray["RA"])>0)
				$retireAllow = "+IFNULL(".$retireAllow.",0)";
			if(count($this->miscallowDeducArray ["MP"])>0)
				//$allow .= ",SUM(" . str_replace ( ',', '+', substr ( $this->miscallowDeducArray ["miscAllowString"], 0, - 1 ) ) . ") OTHERS";
				$miscallow .= str_replace ( ',', '+', substr ( $this->miscallowDeducArray ["miscAllowString"], 0, - 1 ) ) ;
			$miscallow .=  $retireAllow;
			
			if(count($this->miscallowDeducArray ["MP"]) == 0)
				$miscallow = substr($miscallow,1);
			if((count($this->miscallowDeducArray ["MP"])>0) || count($retirementArray["RA"])>0){
				$miscallow = ",SUM(" .$miscallow.  ") OTHERS";
			}else{
				$miscallow = "";
			}
			$allow .= $miscallow;
			
			$allow .=",SUM(inc_arrear) INCARREAR,SUM(gross_salary) GROSS";
			
			if(count($this->allowDeducArray ['D'])>0){
				foreach ( $this->allowDeducArray ['D'] as $key => $val ) {
					$dedu .= ",SUM(" . $val ['pay_structure_id'] . ") " . $val ['alias_name'];
				}
			}
		if(count($retirementArray["retirementDeduString"])>0)
			$retireDedu .= str_replace ( ',', '+', substr ( $retirementArray["retirementDeduString"], 0, - 1 ) ) ;
		if(count($retirementArray["retirementDeduString"])>0)
			$retireDedu = rtrim($retireDedu,"+");
		if(count($retirementArray["RD"])>0)
			$retireDedu = "+IFNULL(".$retireDedu.",0)";
		if(count($this->miscallowDeducArray ["MD"])>0)
			//$dedu .= ",SUM(" . str_replace ( ',', '+', substr ( $this->miscallowDeducArray ["miscDeduString"], 0, - 1 ) ) . ") OTHERSDED";
			$miscdedu .= str_replace ( ',', '+', substr ( $this->miscallowDeducArray ["miscDeduString"], 0, - 1 ) ) ;
					
		$miscdedu .= $retireDedu;
		
		if(count($this->miscallowDeducArray ["MD"]) == 0)
			$miscdedu = substr($miscdedu,1);
		if((count($this->miscallowDeducArray ["MD"])>0) || count($retirementArray["RD"])>0){
			$miscdedu = ",SUM(" .$miscdedu.  ") OTHERSDED";
		}else{
			$miscdedu = "";
		}
		$headArr = array("name"=>"SUM");
		$dedu .= $miscdedu;
		$dedu .=",SUM(total_deduction) DEDUCTION,SUM(net_salary) NET";
		
		$extraColums = $allow . $dedu;
		} else {
		$extraColums = "";
		}
		$period_from = $this->reportParams ['from_period'];
		$caseStmt = ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? ",(CASE " : '';
		$i = ($this->reportParams ['reportFor'] != 'M' && $this->reportParams ['reportFor'] != 'Y') ? (($this->reportParams ['reportFor'] == 'HY') ? $fromPeriod [2] : $fromPeriod [1]) : 1;
		while ( strtotime ( $period_from ) <= strtotime ( $this->reportParams ['to_period'] ) ) {
			if ($this->reportParams ['isConsolidate'] == 1) {
				if ($this->reportParams ['reportFor'] == 'M') { // without dynamic Colums Only Gross
					$caseStmt .= ",IFNULL(SUM(CASE WHEN p.month_year = '$period_from' THEN p.gross_salary END),0) `" . date ( 'F y', strtotime ( str_replace ( '-', '', $period_from ) ) ) . "`"; // For Month
				} else if ($this->reportParams ['reportFor'] == 'Q') {
					$caseStmt .= ",IFNULL(SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+2 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END),0) `Q" . $i . "`"; // For Month
					$i ++;
				} else if ($this->reportParams ['reportFor'] == 'HY') {
					$caseStmt .= ",IFNULL(SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+5 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END),0) `HY" . $i . "`"; // For Month
					$i ++;
				} else if ($this->reportParams ['reportFor'] == 'Y') {
					$caseStmt .= ",IFNULL(SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+11 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END),0)`" . (($this->reportParams ['yearType'] == 'FY') ? explode ( '-', $period_from ) [0] . "-" . substr ( (explode ( '-', $period_from ) [0] + 1), - 2 ) : explode ( '-', $period_from ) [0]) . "`"; // For Month
				}
			} else {
				if ($this->reportParams ['reportFor'] == 'M') { // without dynamic Colums Only Gross
					$extraColums = str_replace ( 'SUM', '', $extraColums ); // For Month
					$caseStmt = "";
				} else if ($this->reportParams ['reportFor'] == 'Q') {
					$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+2 months", strtotime ( $period_from ) ) ) . "' THEN 'Q" . $i .'  '.(($this->reportParams ['yearType'] == 'FY')?(date ( "Y",strtotime( $this->reportParams ['from_period'])).'-'.substr(date ( "Y",strtotime( $this->reportParams ['from_period']))+1,2)):(date ( "Y",strtotime($period_from)))). "'";
					$i ++;
				} else if ($this->reportParams ['reportFor'] == 'HY') {
					$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+5 months", strtotime ( $period_from ) ) ) . "'  THEN 'HY" . $i .'  '.(($this->reportParams ['yearType'] == 'FY')?(date ( "Y",strtotime( $this->reportParams ['from_period'])).'-'.substr(date ( "Y",strtotime( $this->reportParams ['from_period']))+1,2)):(date ( "Y",strtotime($period_from)))). "'";
					$i ++;
				} else if ($this->reportParams ['reportFor'] == 'Y') {
					$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+11 months", strtotime ( $period_from ) ) ) . "' THEN  '".(($this->reportParams ['yearType'] == 'FY')?(date ( "Y",strtotime($period_from)).'-'.substr(date ( "Y", strtotime ( "+11 months", strtotime ( $period_from ) ) ),2)):(date ( "Y",strtotime($period_from)))). "'"; // For Month
					$i ++;
				}
			}
			
			$period_from = ($this->reportParams ['reportFor'] == 'M') ? date ( "Y-m-d", strtotime ( "+1 months", strtotime ( $period_from ) ) ) : // For Month
(($this->reportParams ['reportFor'] == 'Q') ? date ( "Y-m-d", strtotime ( "+3 months", strtotime ( $period_from ) ) ) : (($this->reportParams ['reportFor'] == 'HY') ? date ( "Y-m-d", strtotime ( "+6 months", strtotime ( $period_from ) ) ) : date ( "Y-m-d", strtotime ( "+12 months", strtotime ( $period_from ) ) )));
		}
		
		$caseStmt .= ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "END) Period " : '';
	
		if ($this->reportParams ['isConsolidate'] != 0) {
			$groupcondition = " GROUP BY p.employee_id order by p.month_year,p.employee_id";
			$montyYearCase = "";
		} else {
			if ($this->reportParams ['reportFor'] == 'M') {
				$customFields= str_replace("SUM","",$customFields);
				$groupcondition = " GROUP BY p.employee_id,Period order by p.month_year,p.employee_id";
				$montyYearCase = ",DATE_FORMAT(p.month_year,'%M %Y') PERIOD ";
			} else {
				$groupcondition = " GROUP BY p.employee_id,Period order by p.month_year,p.employee_id";
				$montyYearCase = "";
			}
		}
		
		$leaveRule = array(); $leaveStm="";
		$stmt="SELECT l.leave_rule_id,UPPER(l.alias_name) alias_name FROM company_leave_rules l WHERE enabled=1;" ;
		$query = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
			array_push ( $leaveRule, $row );
		}
		foreach ( $leaveRule as $key => $val ){
			$leaveStm.="SUM( p.".$val['leave_rule_id'].") ".$val ['alias_name'].",";
		}
		$columns = ($this->reportParams ['isCustom']!='')?$customFields.$extraColums:$extraColums;
		if($this->reportParams ['isCustom']=='')
			$tableQuery = "SELECT p.employee_id ID,p.employee_name NAME,IFNULL(cal_days,'') CALENDAR_DAYS,IFNULL(worked_days,'') WORKED_DAYS,$leaveStm (p.lop+p.alop) LOP,p.late_lop LLOP";
		else 
			$tableQuery = "SELECT p.employee_id ID,p.employee_name NAME,IFNULL(cal_days,'') CALENDAR_DAYS,IFNULL(worked_days,'') WORKED_DAYS";
		
		//$tableQuery = "SELECT p.employee_id ID,p.employee_name NAME,$leaveStm p.lop LOP";
		$columns = str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns);
		$tableQuery .= $montyYearCase.$columns;
		$tableQuery .= "$caseStmt
		FROM payroll p
		INNER JOIN employee_personal_details pd
		ON p.employee_id = pd.employee_id 
		INNER JOIN employee_work_details w
		ON p.employee_id = w.employee_id 
		INNER JOIN company_designations ds
		ON w.designation_id = ds.designation_id
		INNER JOIN company_branch cb
		ON w.branch_id = cb.branch_id
		INNER JOIN company_departments dp
		ON w.department_id = dp.department_id
		LEFT JOIN company_team ct
		ON w.team_id = ct.team_id
		LEFT JOIN employee_work_details repo
        ON w.employee_reporting_person = repo.employee_id
		LEFT JOIN company_shifts cs
		ON w.shift_id = cs.shift_id
		LEFT JOIN settlements s
    	ON p.employee_id = s.employee_id AND s.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
		WHERE p.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
		$whereCondition $groupcondition ";
		
	//print_r($tableQuery); die();
		// component 1 Query Preparation End
		// Prepare the Query and Run it Handle Error here if Error as Report Cannot Load (for this component only).
		$queryData = $this->getComponentsResult ( $tableQuery);
		$headersArr= array();
		
		foreach($queryData[0] as $header)
			array_push($headersArr,array("name"=>$header));
			
		$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'SALARY STATEMENT FOR ' . $curenttitle, 'top', array (
				"status" => "success",
				"headers" => $headersArr,
				"queryData" => $queryData [1],
				"fixedColumns" => 2 ,
				"isRemoveIndices" =>true,
				"isTotal" =>true
		
		
		) ) : array (
				"type" => 'table',
				"title" => 'SALARY COMPONENTS',
				"position" => 'top',
				"status" => "error",
				"data" => $queryData [1] 
		);
		
		/*
		 * $this->components[]=($queryData[0]!='error')?
		 * new Component('piechart', 'Salary Components', 'bottom-left',array("status"=>"success","headers"=>$queryData[0],"queryData"=>$queryData[1],"fixedColumns"=>2)):
		 * array("type"=>'piechart', "title"=>'Salary Components',"position"=>'bottom-left',"status"=>"error","data"=>$queryData[1]);
		 */
		
		/* Component 3 Query Preparation Start */
		// prepare the Query for the component here
		if ($this->reportParams ['isTable'] == 0) {
			if ($this->reportParams ['isConsolidate'] != 1) {
				$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
				
				if ($this->reportParams ['isConsolidate'] == 0) {
					$allow = "";
					$dedu = "";
					foreach ( $this->allowDeducArray ['A'] as $key => $val ) {
						$allow .= ",SUM(" . $val ['pay_structure_id'] . ") " . $val ['alias_name'];
					}
					if(count($this->miscallowDeducArray ["MP"])>0)
					$allow .= ",SUM(" . str_replace ( ',', '+', substr ( $this->miscallowDeducArray ["miscAllowString"], 0, - 1 ) ) . ") OTHERS";
					
					$extraColums = $allow;
				} else {
					$extraColums = "";
				}
				$period_from = $this->reportParams ['from_period'];
				$caseStmt = ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "(CASE " : '';
				$i = ($this->reportParams ['reportFor'] != 'M' && $this->reportParams ['reportFor'] != 'Y') ? (($this->reportParams ['reportFor'] == 'HY') ? $fromPeriod [2] : $fromPeriod [1]) : 1;
				while ( strtotime ( $period_from ) <= strtotime ( $this->reportParams ['to_period'] ) ) {
					if ($this->reportParams ['isConsolidate'] == 1) {
						if ($this->reportParams ['reportFor'] == 'M') { // without dynamic Colums Only Gross
							$caseStmt .= ",SUM(CASE WHEN p.month_year = '$period_from' THEN p.gross_salary END) `" . date ( 'F y', strtotime ( str_replace ( '-', '', $period_from ) ) ) . "`"; // For Month
						} else if ($this->reportParams ['reportFor'] == 'Q') {
							$caseStmt .= ",SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+2 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END) `Q" . $i . "`"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'HY') {
							$caseStmt .= ",SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+5 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END) `HY" . $i . "`"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'Y') {
							$caseStmt .= ",SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+11 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END) `" . (($this->reportParams ['yearType'] == 'FY') ? explode ( '-', $period_from ) [0] . "-" . substr ( (explode ( '-', $period_from ) [0] + 1), - 2 ) : explode ( '-', $period_from ) [0]) . "`"; // For Month
						}
					} else {
						if ($this->reportParams ['reportFor'] == 'M') { // without dynamic Colums Only Gross
						                                           // $extraColums=str_replace('SUM', '', $extraColums);//For Month
							$caseStmt = "";
						} else if ($this->reportParams ['reportFor'] == 'Q') {
							$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+2 months", strtotime ( $period_from ) ) ) . "' THEN 'Q" . $i . "'"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'HY') {
							$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+5 months", strtotime ( $period_from ) ) ) . "'  THEN 'HY" . $i . "'"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'Y') {
							$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+11 months", strtotime ( $period_from ) ) ) . "' THEN  '".(($this->reportParams ['yearType'] == 'FY') ? explode ( '-', $period_from ) [0] . "-" . substr ( (explode ( '-', $period_from ) [0] + 1), - 2 ) : explode ( '-', $period_from ) [0]) . "'"; // For Month
							$i ++;
						}
					}
					
					$period_from = ($this->reportParams ['reportFor'] == 'M') ? date ( "Y-m-d", strtotime ( "+1 months", strtotime ( $period_from ) ) ) : // For Month
(($this->reportParams ['reportFor'] == 'Q') ? date ( "Y-m-d", strtotime ( "+3 months", strtotime ( $period_from ) ) ) : (($this->reportParams ['reportFor'] == 'HY') ? date ( "Y-m-d", strtotime ( "+6 months", strtotime ( $period_from ) ) ) : date ( "Y-m-d", strtotime ( "+12 months", strtotime ( $period_from ) ) )));
				}
				
				
				$caseStmt .= ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "END) Period " : '';
				$tableQuery = "SELECT " . (($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "" : "DATE_FORMAT(p.month_year,'%M %Y') Period") . "$caseStmt  $extraColums
						FROM payroll p
						INNER JOIN employee_work_details w
						ON p.employee_id = w.employee_id  
						INNER JOIN company_designations ds
						ON w.designation_id = ds.designation_id
						INNER JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						INNER JOIN company_departments dp
						ON w.department_id = dp.department_id
						LEFT JOIN company_team ct
						ON w.team_id = ct.team_id
						LEFT JOIN employee_work_details repo
        				ON w.employee_reporting_person = repo.employee_id
						LEFT JOIN company_shifts cs
						ON w.shift_id = cs.shift_id
						LEFT JOIN settlements s
    					ON p.employee_id = s.employee_id AND s.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
						WHERE p.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
						$whereCondition " . (($this->reportParams ['isConsolidate'] != 1) ? (($this->reportParams ['reportFor'] != 'M') ? "GROUP BY Period" : 'GROUP BY p.month_year') : "");
				/* Component 3 Query Preparation End */
				//echo $tableQuery; die();
				$queryData = $this->getComponentsResult ( $tableQuery );
				$this->components [] = ($queryData [0] != 'error') ? new Component ( 'linechart', 'COMPONENT WISE REPORT FOR ' . $curenttitle, 'bottom', array (
						"status" => "success",
						"xLabels" => $queryData [0],
						"data" => $queryData [1],
						"lineColumn" => 0 ,
						"isRemoveIndices" =>false,
						"isTotal" =>true
						
						
				) ) : array (
						"type" => 'table',
						"title" => 'COMPONENT WISE REPORT ',
						"position" => 'bottom-right',
						"status" => "error",
						"data" => $queryData [1] 
				);
				// Component Class will be called with parms componentType,Title,position,theReportsParms as a array (exclusive for the report)
			} else {
				$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
				
				if ($this->reportParams ['isConsolidate'] == 0) {
					$allow = "";
					$dedu = "";
					foreach ( $this->allowDeducArray ['A'] as $key => $val ) {
						$allow .= ",SUM(" . $val ['pay_structure_id'] . ") " . $val ['alias_name'];
					}
					if(count($this->miscallowDeducArray ["MP"])>0)
					$allow .= ",SUM(" . str_replace ( ',', '+', substr ( $this->miscallowDeducArray ["miscAllowString"], 0, - 1 ) ) . ") OTHERS";
					
					$extraColums = $allow;
				} else {
					$extraColums = "";
				}
				$period_from = $this->reportParams ['from_period'];
				$caseStmt = ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "(CASE " : '';
				$i = ($this->reportParams ['reportFor'] != 'M' && $this->reportParams ['reportFor'] != 'Y') ? (($this->reportParams ['reportFor'] == 'HY') ? $fromPeriod [2] : $fromPeriod [1]) : 1;
				while ( strtotime ( $period_from ) <= strtotime ( $this->reportParams ['to_period'] ) ) {
					if ($this->reportParams ['isConsolidate'] == 1) {
						if ($this->reportParams ['reportFor'] == 'M') { // without dynamic Colums Only Gross
							$caseStmt .= ",SUM(CASE WHEN p.month_year = '$period_from' THEN p.gross_salary END) `" . date ( 'F y', strtotime ( str_replace ( '-', '', $period_from ) ) ) . "`"; // For Month
						} else if ($this->reportParams ['reportFor'] == 'Q') {
							$caseStmt .= ",SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+2 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END) `Q" . $i . "`"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'HY') {
							$caseStmt .= ",SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+5 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END) `HY" . $i . "`"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'Y') {
							$caseStmt .= ",SUM(CASE WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+11 months", strtotime ( $period_from ) ) ) . "' THEN p.gross_salary END) `" . (($this->reportParams ['yearType'] == 'FY') ? explode ( '-', $period_from ) [0] . "-" . substr ( (explode ( '-', $period_from ) [0] + 1), - 2 ) : explode ( '-', $period_from ) [0]) . "`"; // For Month
						}
					} else {
						if ($this->reportParams ['reportFor'] == 'M') { // without dynamic Colums Only Gross
						                                           // $extraColums=str_replace('SUM', '', $extraColums);//For Month
							$caseStmt = "";
						} else if ($this->reportParams ['reportFor'] == 'Q') {
							$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+2 months", strtotime ( $period_from ) ) ) . "' THEN 'Q" . $i . "'"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'HY') {
							$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+5 months", strtotime ( $period_from ) ) ) . "'  THEN 'HY" . $i . "'"; // For Month
							$i ++;
						} else if ($this->reportParams ['reportFor'] == 'Y') {
							$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+11 months", strtotime ( $period_from ) ) ) . "' THEN '". (($this->reportParams ['yearType'] == 'FY') ? explode ( '-', $period_from ) [0] . "-" . substr ( (explode ( '-', $period_from ) [0] + 1), - 2 ) : explode ( '-', $period_from ) [0]) . "'"; // For Month
							$i ++;
						}
					}
					
					$period_from = ($this->reportParams ['reportFor'] == 'M') ? date ( "Y-m-d", strtotime ( "+1 months", strtotime ( $period_from ) ) ) : // For Month
(($this->reportParams ['reportFor'] == 'Q') ? date ( "Y-m-d", strtotime ( "+3 months", strtotime ( $period_from ) ) ) : (($this->reportParams ['reportFor'] == 'HY') ? date ( "Y-m-d", strtotime ( "+6 months", strtotime ( $period_from ) ) ) : date ( "Y-m-d", strtotime ( "+12 months", strtotime ( $period_from ) ) )));
				}
				
				$caseStmt .= ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "END) Period " : '';
				// if null for repeatates april2016 so null value set
				$tableQuery = "SELECT * FROM (SELECT " . (($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "" : "IF(p.month_year!='','Value',null) Period ") . "$caseStmt $extraColums
							FROM payroll p
							INNER JOIN employee_work_details w
							ON p.employee_id = w.employee_id  
							INNER JOIN company_designations ds
							ON w.designation_id = ds.designation_id
							INNER JOIN company_branch cb
							ON w.branch_id = cb.branch_id
							INNER JOIN company_departments dp
							ON w.department_id = dp.department_id
							LEFT JOIN company_team ct
							ON w.team_id = ct.team_id
							LEFT JOIN employee_work_details repo
        					ON w.employee_reporting_person = repo.employee_id
							LEFT JOIN company_shifts cs
							ON w.shift_id = cs.shift_id
							LEFT JOIN settlements s
    						ON p.employee_id = s.employee_id AND s.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
							WHERE p.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
							$whereCondition )t WHERE Period IS NOT NULL";
				$queryData = $this->getComponentsResult ( $tableQuery );
				$this->components [] = ($queryData [0] != 'error') ? new Component ( 'barchart', 'CUMULATIVE SALARY FOR ' . $curenttitle, 'bottom', array (
						"status" => "success",
						"xLabels" => $queryData [0],
						"data" => $queryData [1],
						"lineColumn" => 0 
				) ) : array (
						"type" => 'table',
						"title" => 'CUMULATIVE SALARY',
						"position" => 'bottom',
						"status" => "error",
						"data" => $queryData [1] 
				);
				// Component Class will be called with parms componentType,Title,position,theReportsParms as a array (exclusive for the report)
			}
		}
		return $this->getJsonData ( $this );
	}
	function getComponentsResult($query) {
		try {
			$result = mysqli_query ( $this->conn, $query );
			if ($result === FALSE)
				// throw new Exception(mysqli_error ( $this->conn ));
				throw new Exception ( "Failed To Load result" );
			
			$rowcount = mysqli_num_rows ( $result );
			if ($rowcount > 0) {
				$queryData = array (
						array () 
				);
				$queryData [0] = array_keys ( mysqli_fetch_assoc ( $result ) );
				mysqli_data_seek ( $result, 0 ); // pointer reset into zero for again fetch same data in mysql using mysqli_data_seek
				while ( $row = mysqli_fetch_array ( $result, MYSQLI_NUM ) ) {
					$queryData [1] [] = $row;
				}
			} else {
				throw new Exception ( "No data found" );
			}
		} catch ( Exception $e ) {
			$queryData [0] = "error";
			$queryData [1] = $message = $e->getMessage ();
		}
		return $queryData;
	}
	function merge($arr) {
		if (! is_array ( $arr ))
			return $this;
		foreach ( $arr as $key => $val )
			if (isset ( $this->reportParams [$key] ))
				$this->reportParams [$key] = $val;
		return $this;
	}
	function getJsonData() {
		$var = get_object_vars ( $this );
		unset ( $var ['conn'] );
		unset ( $var ['allowDeducArray'] );
		unset ( $var ['miscallowDeducArray'] );
		//print_r($var);
		foreach ( $var as $key => &$value ) {
			if (is_object ( $value ) && method_exists ( $value, 'getJsonData' )) {
				$value = $value->getJsonData ();
			}
		}
		return $var;
	}
	
	function getPayrollYears($type) {
		$json = array ();
		$condition = ($type == 'FY') ? " CONCAT(SUBSTRING(year,1,4),'-',SUBSTRING(year,5,2),'#',SUBSTRING(year,1,4),'-04-01#',(SUBSTRING(year,1,4)+1),'-03-01') resultSet" : "CONCAT(DATE_FORMAT(month_year,'%Y'),'#',DATE_FORMAT(month_year,'%Y'),'-01-01','#',DATE_FORMAT(month_year,'%Y'),'-12-01')  resultSet";
		
		$stmt = mysqli_query ( $this->conn, "SELECT DISTINCT $condition FROM payroll ORDER BY resultSet DESC;" );
		
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		
		return $json;
	}
	function getHeaderNames($headers){
		$headerNames= array();
		foreach($headers as $header){
			if(isset($header["children"])){
				$headerNames= array_merge($headerNames,$this->getHeaderNames($header["children"]));
			
			}else{
				$headerNames[] = array("name"=>$header["name"]);
			}
		}
		return $headerNames;
	}
	
	function getPeriodOf($reportFor, $reportYear, $reportType) {
		$json = array ();
		if ($reportType == 'FY') {
			$condition = "'" . explode ( '-', $reportYear ) [0] . "-04-01' AND '" . (explode ( '-', $reportYear ) [0] + 1) . "-03-01'";
		} else if ($reportType == 'CY') {
			$condition = "'" . explode ( '-', $reportYear ) [0] . "-01-01' AND '" . explode ( '-', $reportYear ) [0] . "-12-01'";
		}
		
		if ($reportFor == 'M') {
			$query = "SELECT DISTINCT CONCAT(DATE_FORMAT(month_year,'%b %Y'),'#',month_year,'#',month_year) resultSet FROM payroll WHERE month_year BETWEEN $condition";
		} else if ($reportFor == 'Q') {
			if ($reportType == 'FY') {
				$query = "SELECT DISTINCT (CASE WHEN DATE_FORMAT(month_year,'%m') IN ('04','05','06') THEN 'Q1 " . explode ( '-', $reportYear ) [0] . "-" . substr ( (explode ( '-', $reportYear ) [0] + 1), - 2 ) . "#" . explode ( '-', $reportYear ) [0] . "-04-01#" . explode ( '-', $reportYear ) [0] . "-06-01'
		            WHEN DATE_FORMAT(month_year,'%m') IN ('07','08','09') THEN 'Q2 " . explode ( '-', $reportYear ) [0] . "-" . substr ( (explode ( '-', $reportYear ) [0] + 1), - 2 ) . "#" . explode ( '-', $reportYear ) [0] . "-07-01#" . explode ( '-', $reportYear ) [0] . "-09-01'
		            WHEN DATE_FORMAT(month_year,'%m') IN ('10','11','12') THEN 'Q3 " . explode ( '-', $reportYear ) [0] . "-" . substr ( (explode ( '-', $reportYear ) [0] + 1), - 2 ) . "#" . explode ( '-', $reportYear ) [0] . "-10-01#" . explode ( '-', $reportYear ) [0] . "-12-01'
		            WHEN DATE_FORMAT(month_year,'%m') IN ('01','02','03') THEN 'Q4 " . explode ( '-', $reportYear ) [0] . "-" . substr ( (explode ( '-', $reportYear ) [0] + 1), - 2 ) . "#" . (explode ( '-', $reportYear ) [0] + 1) . "-01-01#" . (explode ( '-', $reportYear ) [0] + 1) . "-03-01' END) resultSet
			            FROM payroll WHERE month_year BETWEEN $condition";
			} else if ($reportType == 'CY') {
				$query = "SELECT DISTINCT (CASE WHEN DATE_FORMAT(month_year,'%m') IN ('01','02','03') THEN 'Q1 " . explode ( '-', $reportYear ) [0] . "#" . explode ( '-', $reportYear ) [0] . "-01-01#" . explode ( '-', $reportYear ) [0] . "-03-01'
					WHEN DATE_FORMAT(month_year,'%m') IN ('04','05','06') THEN 'Q2 " . explode ( '-', $reportYear ) [0] . "#" . explode ( '-', $reportYear ) [0] . "-04-01#" . explode ( '-', $reportYear ) [0] . "-06-01'
		            WHEN DATE_FORMAT(month_year,'%m') IN ('07','08','09') THEN 'Q3 " . explode ( '-', $reportYear ) [0] . "#" . explode ( '-', $reportYear ) [0] . "-07-01#" . explode ( '-', $reportYear ) [0] . "-09-01'
		            WHEN DATE_FORMAT(month_year,'%m') IN ('10','11','12') THEN 'Q4 " . explode ( '-', $reportYear ) [0] . "#" . explode ( '-', $reportYear ) [0] . "-10-01#" . explode ( '-', $reportYear ) [0] . "-12-01' END) resultSet
			            FROM payroll WHERE month_year BETWEEN  $condition";
			}
		} else if ($reportFor == 'HY') {
			if ($reportType == 'FY') {
				$query = "SELECT DISTINCT (CASE WHEN DATE_FORMAT(month_year,'%m') IN ('04','05','06','07','08','09') THEN 'HY1 " . explode ( '-', $reportYear ) [0] . "-" . substr ( ((explode ( '-', $reportYear ) [0] + 1)), - 2 ) . "#" . explode ( '-', $reportYear ) [0] . "-04-01#" . explode ( '-', $reportYear ) [0] . "-09-01'
			            WHEN DATE_FORMAT(month_year,'%m') IN ('10','11','12','01','02','03') THEN 'HY2 " . explode ( '-', $reportYear ) [0] . "-" . substr ( (explode ( '-', $reportYear ) [0] + 1), - 2 ) . "#" . explode ( '-', $reportYear ) [0] . "-10-01#" . (explode ( '-', $reportYear ) [0] + 1) . "-03-01' END) resultSet
				            FROM payroll WHERE month_year BETWEEN $condition";
			} else if ($reportType == 'CY') {
				$query = "SELECT DISTINCT (CASE WHEN DATE_FORMAT(month_year,'%m') IN ('01','02','03','04','05','06') THEN 'HY1 " . explode ( '-', $reportYear ) [0] . "#" . explode ( '-', $reportYear ) [0] . "-01-01#" . explode ( '-', $reportYear ) [0] . "-06-01'
			                    WHEN DATE_FORMAT(month_year,'%m') IN ('07','08','09','10','11','12') THEN 'HY2 " . explode ( '-', $reportYear ) [0] . "#" . explode ( '-', $reportYear ) [0] . "-07-01#" . explode ( '-', $reportYear ) [0] . "-12-01' END) resultSet
				                    FROM payroll WHERE month_year BETWEEN  $condition";
			}
		}
		
		if ($reportFor == 'Y') {
			$json = self::getPayrollYears ( $reportType );
		} else {
			$stmt = mysqli_query ( $this->conn, $query );
			while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
				array_push ( $json, $row );
			}
		}
		return $json;
	}
	function tableData($returnType=null) {
		//JSON to Table for PDF and EXCEL Export - filename - name of the file to download,title - title of the report,periodfrom - start period,$data - header and rowdata and position , isConsolidated, $report_for - Monthly,Quarterly,.. - $year Type - FY,CY 
		//data -> $this->components 
		/*
		 fixedColumns
		 tableHeaders
		 tableData
		 status
		
		 ---
		 $title;
		 var $name;
		 $generatedBy;
		 $generatedOn;
		 $reportParams = array ();
		 $this->reportParams ['type'] = "";
		 $this->reportParams ['reportFor'] = "";
		 $this->reportParams ['yearType'] = "";
		 $this->reportParams ['from_period'] = "";
		 $this->reportParams ['to_period'] = "";
		 $this->reportParams ['filter_key'] = "";
		 $this->reportParams ['filter_value'] = "";
		 $this->reportParams ['isConsolidate'] = "";
		 $this->reportParams ['isTable'] = "";*/
		$periodIndex = null;
		$consolidated = $this->reportParams ['isConsolidate'];
		$reportFor = $this->reportParams ['reportFor'] ;
		
		foreach ($this->components as $component){
			$tableId =  $component->position . "0-" . "table_excel";
		$allowedComponents = array("table","html");
		
		if(!in_array($component->type, $allowedComponents))
				continue;
		
				//title Preparation
		if($component->type == "table"){		
		$colspan=count($component->data['tableHeaders']);
		$title = "<table style='width:100%'><tr><th  class='".$tableId."_title' colspan=".$colspan." style='font-weight: bold;text-align:center;'>";
				//prepare the cusomized title for the non-consolidated report,//Report Title for the Page if Non-consolidated
		$reportForHeaderTitle ="";
		if($consolidated ==0){//non-consolidated
			if($reportFor == 'Y')
				$reportForHeaderTitle = 'YEAR OF $period ';
			else if($reportFor =='M')
				$reportForHeaderTitle = 'MONTH OF $period';
			else if($reportFor=='Q')
				$reportForHeaderTitle = 'QUARTER OF $period';
			else if($reportFor=='HY')
				$reportForHeaderTitle = 'HALF YEAR OF $period';
		  $title .= $this->name." FOR THE ".$reportForHeaderTitle;
		}else{
			$title .=$this->title;
		}
		$title.='</th></tr></table>';
		
		$tableHtml ='$getTitle';//Initalise Random title
		
		$tableHtml .="<br><div class='reportTable'><table class='" . $tableId . "'>";
		//tableHeaders
		//Table Headers - If non consolidated do not show period
		
		$tableHeaders = $component->data['tableHeaders'];
		
		
		$tableHeaderCount = sizeof($tableHeaders);
		
		$tableHeaderHtml="<thead><tr style='text-align:center'>";
		$depth=$component->data['headerDepth'];
		$rowSpan=false;
		foreach($tableHeaders as $key=>$header){
			$header["name"]=str_ireplace("_"," ",$header["name"]);
			if($header["name"] == 'PERIOD'){
				$periodIndex = $key;
				continue;
			}
			if(isset($header["children"])){
				$rowSpan=true;
				$tableHeaderHtml .= "<th colspan=".count($header["children"])." style='text-align:center;font-size:11px;'>".$header["name"]."</th>";
			}else{
				$tableHeaderHtml .= "<th rowspan=".$depth." style='text-align:center;font-size:11px;'>".$header["name"]."</th>";
			}
		}
		$tableHeaderHtml .="</tr>";
			if($rowSpan){
				$tableHeaderHtml .= "<tr style='text-align:center'>";
		
				foreach($tableHeaders as $key=>$header) {
					if(isset($header["children"])){
						for($i=0;$i<sizeof($header["children"]);$i++)
							$tableHeaderHtml .= "<th style='text-align:center;font-size:11px;'>".str_ireplace("_", " ", $header["children"][$i]["name"])."</th>";
					}
				}
				$tableHeaderHtml .= "</tr>";
			}
			$tableHeaderHtml .="</thead>";
			
		$tableRows = $component->data['tableData'];
		
		$tableFooters = isset($component->data['tableFooters'])?$component->data['tableFooters']:null;
		
		//$theadHtml ="<thead repeat_header='1'><tr>";
		
		
		//Table Body
		$tbodyHtml =$tableHeaderHtml."<tbody>";
		$sumColumnData = array ();
		
		$oldPeriod = "";
		$tableRowCount = sizeof ( $tableRows);
		
			foreach($tableRows as $key=> $tableRow){
				//each row will come here
				$period=$periodIndex?$tableRow[$periodIndex]:""; //get the row period
				
			   if($periodIndex && $key==0){
				eval("\$getTitle = \"$title\";");
				$oldPeriod = $period; // Initalise Period For avoid firset page break
				eval("\$tableHtml = \"$tableHtml\";");
			   }else if($consolidated==1||($consolidated==0&&!$periodIndex)){
				if($consolidated==0)
					$title = "<table style='width:100%'><tr><th  class='".$tableId."_title' colspan=".$colspan." style='font-weight: bold;text-align:center;'>".$this->title."</th></tr></table>";
				eval("\$getTitle = \"$title\";");
				eval("\$tableHtml = \"$tableHtml\";");//Title print using Eval
			   }
			   
			  if($periodIndex!='' && $period != $oldPeriod){ //if period is there and it changed to a new period
					$tbodyHtml .="</tbody>";
					
					$tfootHtml = "<tfoot><tr class=\"border_bottom\">";
					foreach ( $tableRow as $j => $value ) {
						if($periodIndex!==$j){//skip period in footer side
							if (is_numeric ( $value ))
								$tfootHtml .= '<td style="text-align:right;font-size:10px;font-weight: bold;" >' . inr_format ( $sumColumnData [$j] ) . '</td>';
							else
								$tfootHtml .= '<td style="text-align:center;font-size:10px;" > - </td>';
						}
					}
				   
					$tfootHtml .= '</tr></tfoot>';
					
					$tableHtml .= $tbodyHtml.$tfootHtml.'</table></div>';
					$tableHtml .= '<pagebreak>';
					$tableHtml .=$getTitle."<br><div class='reportTable'><table class='" . $tableId . "' >";
					$tbodyHtml =$tableHeaderHtml."<tbody>";
					$sumColumnData =array();
					$oldPeriod=$period;
					//new page Title
					//End the tbody
					//print the sum footer,empty the sum array
					//end the table
					//page break
					//print new page title
					//start the table
					//print the header
					//start the tBody
				}
				
				$tRowHtml ='<tr class=\"border_bottom\">';
				
				$excludeNumberFormatting = array("S.NO","PERSONAL_NO","PERSONAL_NUMBER","ALTERNATE_MOBILE","EXP_IN_MONTHS","BANK_ACC_NO","PF_NUMBER","UAN_NUMBER","ESI_NUMBER","EMPLOYEE_MOBILE","EMPLOYEE_PERSONAL_MOBILE");  //add columns which doesnt need number formatting
					
				if($this->report =="SS001") { 
					//print_r($tableHeaders);
 					   $table_headers_without_span = $tableHeaders;
 					 
				}else{
					$table_headers_without_span = $this->getHeaderNames($tableHeaders);
					
				}
				
				foreach($tableRow as $i=>$value){
					//each column in a row will come here
					
					if($i === $periodIndex) //do not consider period data
						continue 1;
				if (is_numeric( $value ) && !in_array($table_headers_without_span[$i]["name"],$excludeNumberFormatting)){
				//if (is_numeric( $value)){
					$tRowHtml .= '<td style="text-align:right;font-size:10px;" >' . inr_format ( $value ) . '</td>';
						if (isset ( $sumColumnData [$i] ))
							$sumColumnData [$i] += $value;
						else
							$sumColumnData [$i] = $value;
					}else  {
						$tRowHtml .= '<td style="font-size:10px;" >'.$value.'</td>';
						
					}
					
				}
				
				$tRowHtml .='</tr>';
				$tbodyHtml .= $tRowHtml;
				//Table Footer
				if($key == $tableRowCount -1){
					//comes here when printing last element
					$tbodyHtml .="</tbody>";
					$tfootHtml ="";
					if($component->componentParams['isTotal']){
						$tfootHtml = "<tfoot><tr class=\"border_bottom\">";
						foreach ( $tableRow as $j => $value ) {
							if($periodIndex!==$j){//skip period in footer side 
								if (is_numeric($value) && !in_array($table_headers_without_span[$j]["name"],$excludeNumberFormatting))
									 $tfootHtml .= '<td style="text-align:right;font-size:10px;font-weight: bold;" >' . inr_format ( $sumColumnData [$j] ) . '</td>';
								else
									$tfootHtml .= '<td style="text-align:center;font-size:10px;" > - </td>';
							}
						}
						$tfootHtml .= '</tr></tfoot>';
					}
			 $tableHtml .= $tbodyHtml.$tfootHtml.'</table></div>';
					
				}
				
			}
		}else if($component->type == "html"){
			$component->data["data"];
			$tableHtml = $component->data["data"];
			$this->title = $component->title;
			$tableId = "";
		}
		if(!isset($returnType))
			return $this;
		else
			return array (
						"tableId" => $tableId,
						"data" => $tableHtml,
						"filename" => (str_replace(' ', '',str_replace('of','-',str_replace('For','',$this->title)))) 
					
				);
		}
	}
	function getReportPDF($data) {
		$Filename=str_replace(' ', '',str_replace('of','-',str_replace('For','',$this->title)));
		
		$a_json = array ();
		// this is for logo purpose
		$compdetails = array ();
		$stmt ="SELECT c.company_email,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,
                c.company_pin_code FROM company_details c WHERE c.company_id = '" . $_SESSION ['company_id'] . "' AND c.info_flag='A'";
		$result = mysqli_query ( $this->conn,$stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $compdetails, $row );
		}
		// style Purpose
		$htmlstyle = '<style>' . file_get_contents ( dirname ( dirname ( __DIR__ ) ) . "/css/reportTable.css" ) . '</style>';
		$header = '';
		$company_id = $_SESSION ['company_id'];
		$date = $_SESSION ['current_payroll_month'];
		
		$header .= '<table  style="border-bottom:1pt solid black;border-collapse: collapse;width:100%"> <tr>
  		<td style="width:20% "><img src=../' . $compdetails [0] ['company_logo'] . ' style="width:15%"></td>
  		<td style="font-weight: bold;font-size:15px;text-align:left; ">' . ucwords ( $compdetails [0] ['company_name'] ) . '<br>' . ucwords ( $compdetails [0] ['company_build_name'] ) . ',' . ucwords ( $compdetails [0] ['company_street'] ) . ',' . ucwords ( $compdetails [0] ['company_area'] ) . ',<br>' . ucwords ( $compdetails [0] ['company_city'] ) . ',' . $compdetails [0] ['company_pin_code'] . '</td></tr></table>';

		$html = $data;
		
	
		$html .= $htmlstyle;
		
		include_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
	    //$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
	    $mpdf = new mPDF ( 'utf-8', 'A4-L', '', '', '15', '15', '30', '30' ,'9','9','L');
		//$mpdf = new mPDF('','', 0, '', 15, 15, 16, 16, 9, 9, 'L');
		$footer = '<table style="width:100%;vertical-align: bottom;"><tr style="background-color: #1957A2" ><td colspan="5" style="text-align:center;color:#FFF">&copy; Powered by  <a style="color:#FFF" href="http://basspris.com"> BASSPRIS </a> -Online Payroll System</td></tr><tr><td colspan="5" style="text-align:right">Page {PAGENO} | {nb}</td></tr></table>';
		$mpdf->setAutoTopMargin='stretch';
		$mpdf->setHTMLHeader ( $header );
		//$mpdf->setHTMLFooter ( $footer );
		$mpdf->WriteHTML ( $html );
		$mpdf->Output ( $Filename . '.pdf', 'D' );
		exit ();
	}
	
	 function AT001(){
	 //	$table->print_table($header, $data,true,false);
		$this->name = 'leave report';
		
		$fromPeriod = explode ( '*', $this->reportParams ['from_period'] ) [1];
		$toPeriod = explode ( '*', $this->reportParams ['to_period'] ) [1];
		$curenttitle = $subTitle = ($fromPeriod == $toPeriod) ? $fromPeriod : $fromPeriod . " TO " . $toPeriod;
		$curenttitle;
		
		$this->reportParams ['from_period'] = explode ( '*', $this->reportParams ['from_period'] ) [0];
		$this->reportParams ['to_period'] = explode ( '*', $this->reportParams ['to_period'] ) [0];
		//if the condition is false 
		//$this->reportParams ['isConsolidate'] = 1;
		
		if ($this->reportParams ['isConsolidate'] == 0)
			$this->title = 'ATTENDANCE REPORT FOR THE ' . $subTitle . ' ' . $curenttitle;
		else if ($this->reportParams ['isConsolidate'] == 1) 
			$this->title = 'CONSOLIDATED ATTENDANCE REPORT FOR THE ' . $subTitle . ' ' . $curenttitle;
		
			$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
			$customFields = $this->reportParams ['isCustom'];
			$customFields= ",".str_replace("^"," ",$customFields);
			$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
			$colfields = preg_replace('/,/','', $columns,1);
			$headercol = str_replace(',',' ',$colfields);
			
			$columns = str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns);
			if($headercol!=''){
			$fields=explode(" ",$headercol);
			}else{
				$fields = '';
			}
			
		$stmt = mysqli_query ( $this->conn, "SELECT IF(leave_based_on = 'finYear','FY','CY') leave_based_on FROM company_details;" );
		$result = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
		$leave_based = $result['leave_based_on'];
		
		$leaveRule = array();
		$stmt=("SELECT l.leave_rule_id,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
		$query = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
			array_push ( $leaveRule, $row );
		}
		
		/*$header = array(array
				("name"=>"EMPLOYEE_ID"),
				array("name"=>"EMPLOYEE_NAME"),
				array('name'=>"EMPLOYEE_DOJ"),
				array("name"=>"ALLOTTED","children"=> array(array("name"=>"CL"),array("name"=>"PL"))),
		        array("name"=>"AVAILED","children"=> array(array("name"=>"CL"),array("name"=>"PL"))),
				array("name"=>"BALANCE","children"=> array(array("name"=>"CL"),array("name"=>"PL"))));*/
		
		$headersArr=array(
				array("name"=>"ID"),
				array("name"=>"NAME"),
				array('name'=>"DOJ"),
		);
		
		if(!empty($fields)){
		foreach ($fields as $column){
			array_push($headersArr,array("name"=>$column));
		}
		}
		
		if($colfields!=''){
		$caseStmt ="SELECT employee_id EMPLOYEE_ID,employee_name EMPLOYEE_NAME,employee_doj EMPLOYEE_DOJ,$colfields ,";
		}else{
			$caseStmt ="SELECT employee_id EMPLOYEE_ID,employee_name EMPLOYEE_NAME,employee_doj EMPLOYEE_DOJ,";
		}
		$payStmt="";
		$tableQuery="";
		if ($this->reportParams ['isConsolidate'] == 1) {
			$allotedStmt = $availedStmt = $balanceStmt = $consolidateStmt= "";
			foreach ( $leaveRule as $key => $val ){
				$allotedStmt .="ALLOTED_". $val ['alias_name'].",";
				$availedStmt .="AVAILED_". $val ['alias_name'].",";
				$balanceStmt .="BALANCE_". $val ['alias_name'].",";
			}
			$consolidateStmt .= "SELECT EMPLOYEE_ID,EMPLOYEE_NAME,EMPLOYEE_DOJ,".$allotedStmt.$availedStmt.$balanceStmt."ROUND(((".str_replace(",","+",rtrim($availedStmt,",")).")/(".str_replace(",","+",rtrim($allotedStmt,","))."))*100,2) Percentage FROM (";
		}
		//$headersArr["ALLOTTED"]=array();
		$headArr = array("name"=>"ALLOTTED","children"=>array());
		
		foreach ( $leaveRule as $key => $val ){
			array_push($headArr["children"],array("name"=>$val ['alias_name']));
			$caseStmt .= "MAX(CASE WHEN leave_rule_id = '" . $val ['leave_rule_id'] . "' THEN total ELSE 0 END) 'ALLOTED_". $val ['alias_name']."',";
		}
		array_push($headersArr, $headArr);
		
		$period_from = $this->reportParams ['from_period'];
		if ($this->reportParams ['isConsolidate'] == 0) {
			 while ( strtotime ( $period_from ) <= strtotime ( $this->reportParams ['to_period'] ) ) {
				$monthName = date("M",strtotime ( $period_from ));
				
				$headArr = array("name"=>$monthName,"children"=>array());
				foreach ( $leaveRule as $key => $val ){  
					array_push($headArr["children"],array("name"=>$val ['alias_name']));
					$caseStmt .= "IFNULL(MAX(CASE WHEN month_year = '$period_from' AND leave_rule_id = '" . $val ['leave_rule_id'] . "' THEN ".$val ['leave_rule_id']." END),'-')'".date('M' ,strtotime ( $period_from))."-". $val ['alias_name']."',";
					
				}
				$caseStmt .= "IFNULL(MAX(CASE WHEN month_year = '$period_from' THEN lop END),'-')'".date('M' ,strtotime ( $period_from))."- Lop',";
				array_push($headArr["children"],array("name"=>"LOP"));
				array_push($headersArr, $headArr);
				$period_from = date ( "Y-m-d", strtotime ( "+1 months", strtotime ( $period_from ) ) );
		}
		}
		
		$headArr = array("name"=>"AVAILED","children"=>array());
		foreach ( $leaveRule as $key => $val ){
			array_push($headArr["children"],array("name"=>$val ['alias_name']));
			$caseStmt .= "SUM(CASE WHEN month_year BETWEEN '". $this->reportParams ['from_period'] ."' AND '" . $this->reportParams ['to_period'] . "' AND leave_rule_id = '" . $val ['leave_rule_id'] . "' THEN (".$val ['leave_rule_id'].") END)'AVAILED_". $val ['alias_name']."',";
		}
		array_push($headersArr, $headArr);

		$headArr = array("name"=>"BALANCE","children"=>array());
		foreach ( $leaveRule as $key => $val ){
			array_push($headArr["children"],array("name"=>$val ['alias_name']));
			$caseStmt .= "(MAX(CASE WHEN leave_rule_id = '" . $val ['leave_rule_id'] . "'  THEN total END)-(SUM(CASE WHEN month_year BETWEEN '". $this->reportParams ['from_period'] ."' AND '" . $this->reportParams ['to_period'] . "' AND leave_rule_id = '" . $val ['leave_rule_id'] . "' THEN (".$val ['leave_rule_id'].") END))) 'BALANCE_". $val ['alias_name']."',";
		}
		array_push($headersArr, $headArr);
		
		if($this->reportParams ['isConsolidate'] == 1)
			array_push($headersArr,array("name"=>"%"));
		
		$caseStmt = rtrim($caseStmt,",");
		foreach ( $leaveRule as $key => $val ){
			$payStmt .="p.". $val ['leave_rule_id'] .",";
		}
		$payStmt = rtrim($payStmt,",");
	
		
		if($leave_based=='FY'){
			$year=date('Y',strtotime($this->reportParams ['from_period']));
			$year1 =substr($year,2)+1;
			$year =$year.$year1;
			}else {
				$year=date('Y',strtotime($this->reportParams ['from_period']));
			}
	//print_r($this->reportParams ['isConsolidate'] );
		$tableQuery = ($this->reportParams ['isConsolidate'] == 0)?$caseStmt:$consolidateStmt.$caseStmt;
		
		$groupConn = ($this->reportParams ['isConsolidate'] == 0)?'GROUP BY employee_id ORDER BY employee_id':'GROUP BY employee_id ORDER BY employee_id)z';
		$tableQuery .= " FROM ( SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,w.employee_doj,l.leave_rule_id,l.opening_bal,l.allotted,(l.opening_bal+l.allotted) total,p.month_year,p.lop,";
	    $tableQuery .= "$payStmt $columns
		FROM emp_leave_account l
		INNER JOIN employee_work_details w
		ON l.employee_id = w.employee_id
		INNER JOIN employee_personal_details pd
		ON l.employee_id = pd.employee_id
		INNER JOIN company_departments dp
		ON w.department_id = dp.department_id
		INNER JOIN company_designations ds
		ON w.designation_id = ds.designation_id
		INNER JOIN company_branch cb
		ON w.branch_id = cb.branch_id
		LEFT JOIN company_team ct
		ON w.team_id = ct.team_id
		RIGHT JOIN payroll p
		ON l.employee_id = p.employee_id 
		LEFT JOIN employee_work_details repo
        ON w.employee_reporting_person = repo.employee_id
		LEFT JOIN company_shifts cs
		ON w.shift_id = cs.shift_id
		WHERE l.year = '$year' AND p.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'$whereCondition) a
		$groupConn;";
	   
		$queryData = $this->getComponentsResult ( $tableQuery );
		$this->title = 'LEAVE REPORT FOR ' . $curenttitle;
	
		$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'LEAVE REPORT FOR ' . $curenttitle, 'top', array (
				"status" => "success",
				"headers" => $headersArr,
				"queryData" => $queryData [1],
				"fixedColumns" => 2,
				"isRemoveIndices" =>false,
				"headerDepth"=>2,
				"isTotal" =>false
		) ) : array (
				"type" => 'table',
				"title" => 'LEAVE REPORT FOR',
				"position" => 'top',
				"status" => "error",
				"data" => $queryData [1]
		);
		
		return $this->getJsonData ( $this );
	
	}
	function AT002(){
		//In this function we are going to generate employee wise attendance report 
		$this->name = 'EMPLOYEE WISE LEAVE REPORT';
		$fromPeriod = explode ( '*', $this->reportParams ['from_period'] ) [1];
		$toPeriod = explode ( '*', $this->reportParams ['to_period'] ) [1];
		$curenttitle = $subTitle = ($fromPeriod == $toPeriod) ? $fromPeriod : $fromPeriod . " TO " . $toPeriod;
		$this->reportParams ['from_period'] = explode ( '*', $this->reportParams ['from_period'] ) [0];
		$this->reportParams ['to_period'] = explode ( '*', $this->reportParams ['to_period'] ) [0];
		$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));

		//if the condition is false
		if ($this->reportParams ['isConsolidate'] == 0)
			$this->title = 'EMPLOYEE WISE LEAVE REPORT ' . $subTitle . ' ' . $curenttitle;
			else if ($this->reportParams ['isConsolidate'] == 1)
				$this->title = 'CONSOLIDATED ATTENDANCE REPORT FOR THE ' . $subTitle . ' ' . $curenttitle;
				$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : '')))));
				
				$stmt = mysqli_query ( $this->conn, "SELECT IF(leave_based_on = 'finYear','FY','CY') leave_based_on FROM company_details;" );
				$result = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
				$leave_based = $result['leave_based_on'];
				
				$leaveRule = array();
				$stmt=("SELECT l.leave_rule_id,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
				$query = mysqli_query ( $this->conn, $stmt );
				while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
					array_push ( $leaveRule, $row );
				}
	
	 
	
	  	$caseStmt ="SELECT employee_id ID,employee_name NAME,employee_doj DOJ,department_name Department,designation_name Designation ,leave_rule_id,Allotted_leave,";
	
	  	$availedStmt = $balanceStmt = $percentageStmt= "";
	  $case=$case1=$case2=$payStmt=$paidStm="";
	    
	  foreach ( $leaveRule as $key => $val ){
	  	$case .="WHEN leave_rule_id= '". $val ['leave_rule_id'] ."' THEN ".$val ['leave_rule_id']." ";
	  	$case1 .="WHEN leave_rule_id= '". $val ['leave_rule_id'] ."' THEN Allotted_leave - ".$val ['leave_rule_id']." ";
	  	$case2 .="WHEN leave_rule_id= '". $val ['leave_rule_id'] ."' THEN (".$val ['leave_rule_id']."/Allotted_leave)*100 ";
	  	//$caseStmt .= "MAX(CASE WHEN leave_rule_id = '" . $val ['leave_rule_id'] . "' THEN total ELSE 0 END) 'ALLOTED_". $val ['alias_name']."',";
	  }
	 
	  $availedStmt.="(CASE $case ELSE '' END) Availed,";
	  $balanceStmt .="(CASE $case1 ELSE ''END) Balance,";
	  $percentageStmt .="SUM(ROUND((CASE $case2 ELSE 0 END),2))Percentage,lop Availed_LOP";
	  
	  $caseStmt.=$availedStmt.$balanceStmt.$percentageStmt;
	  if($leave_based=='FY'){
	  	$year=date('Y',strtotime($this->reportParams ['from_period']));
	  	$year1 =substr($year,2)+1;
	  	$year =$year.$year1;
	  }else {
	  	$year=date('Y',strtotime($this->reportParams ['from_period']));
	  }
	  foreach ( $leaveRule as $key => $val ){
	   $paidStm.="SUM( p.".$val['leave_rule_id'].") ".$val ['leave_rule_id'].",";
	  }
	  
	 $paidStm=rtrim($paidStm,",");
	 $tableQuery ="";
	 
	 $tableQuery .=" $caseStmt FROM (SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,dp.department_name,ds.designation_name,w.employee_doj,l.leave_rule_id,
	 				l.opening_bal,l.allotted,(l.opening_bal+l.allotted) Allotted_leave,$paidStm,SUM(p.lop) lop 
	 FROM emp_leave_account l
	 INNER JOIN employee_work_details w
	 ON l.employee_id = w.employee_id
	 INNER JOIN employee_personal_details pd
   	 ON l.employee_id = pd.employee_id
	 INNER JOIN company_departments dp
	 ON w.department_id = dp.department_id
	 INNER JOIN company_designations ds
	 ON w.designation_id = ds.designation_id
	 INNER JOIN company_branch cb
	 ON w.branch_id = cb.branch_id
	 LEFT JOIN company_team ct
	 ON w.team_id = ct.team_id
	 RIGHT JOIN payroll p
	 ON l.employee_id = p.employee_id
	 WHERE l.year = '$year' $whereCondition AND p.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
	 GROUP BY leave_rule_id,employee_id) z
	 GROUP BY employee_id,leave_rule_id;";
	 $queryData = $this->getComponentsResult ( $tableQuery );
	
	 //$this->title = 'Employee Wise Leave Report ' . $curenttitle;
	 $htmltable ="";
	 $prevEmployee = "";
	 $employeeDetailsTable=$leaveDetailsTable=$tabletitle="";
     $rightalign=$leftalign="";
	 $htmltable='<div id="Main Table" style="width:100%;text-align:center">' .$this->title = 'EMPLOYEE WISE LEAVE REPORT ' .  $curenttitle;
	 $breakCount =0;
	 //print_r($queryData[1]);
	 foreach ($queryData[1] as $employee){
	 	if($prevEmployee != $employee[0]){
	 	
	 	if($prevEmployee){
	 		$leaveDetailsTable .="<tr><td style='text-align:left'><b>LOP</b></td><td style='text-align:right'>-</td><td style='text-align:right'>{$employee[10]}</td><td style='text-align:right'>-</td><td style='text-align:right'>-</td></tr>";
	 	/*$leaveDetailsTable.="<tr><td style='text-align:left'><b>Total</b></td>";
	 	$leaveDetailsTable.="<td style='text-align:right'><b>".$allotted. "</b></td>";
	 	$leaveDetailsTable.="<td style='text-align:right'><b>".$availed."</b></td>";
	 	$leaveDetailsTable.="<td style='text-align:right'><b>".$bal."</b></td>";
	 	if($per>50){
	 	$leaveDetailsTable.="<td bgcolor='#f97575' style='text-align:right'><b>".$per."</b></td></tr>";
	 	}else{
	 	$leaveDetailsTable.="<td style='text-align:right'><b>".$per."</b></td></tr>";
	 	}*/
	 	$leaveDetailsTable.='</table>';
	 	$leaveDetailsTable.='</div>';
	 	
	 	if($breakCount==3){ //compare the breakcount when looping the 4th employee
	 		$leaveDetailsTable.='<pagebreak></pagebreak>';
	 		$breakCount =0;
	 	}
	 	     
	 	}
	 	
	 
	 	$allotted=$availed=$bal=$per=$i=0;
	 	$htmltable .= $employeeDetailsTable.$leaveDetailsTable;
	 	
	 	//$tabletitle='<div style="text-align:center">' . $this->title = 'Attendance Employee Wise Report ' .  $curenttitle; '</div>';
	 	
	 	//details table
	 	 $employeeDetailsTable ='<div class="reportTable" style="border:none">';
		 $employeeDetailsTable .='<br><table id="First Table" border=1 style="width:80%;margin:auto;text-align:left;border-collapse:collapse;">';
		 $employeeDetailsTable.='<tr>
					             <th bgcolor="#41cac0" style="text-align:right;color:#FFFFFF;font-size: 14px;font-weight: bold;">EMPLOYEE NAME:</th>
					             <td style="text-align:left">'.$employee[1].'('.$employee[0].')</td>
					             <th bgcolor="#41cac0" style="text-align:right;color:#FFFFFF;font-size: 14px;font-weight: bold;">DEPARTMENT:</th>
					             <td style="text-align:left">'.$employee[3].'</td>
					             </tr>
					             <tr>
					             <th bgcolor="#41cac0" style="text-align:right;color:#FFFFFF;font-size: 14px;font-weight: bold;">DESIGNATION:</th>
					             <td style="text-align:left">'.$employee[4].'</td>
					             <th bgcolor="#41cac0" style="text-align:right;color:#FFFFFF;font-size: 14px;font-weight: bold;">DOJ:</th>
					             <td style="text-align:left">'.$employee[2].'</td></tr><tr>';
		
		 $employeeDetailsTable .='</tr></table>';
		 $employeeDetailsTable .='</div>';
		 $leaveDetailsTable  ='<div class="reportTable" style="border:none;">';
		 $leaveDetailsTable .= "<br><table id='Second Table' border=1 style='width:80%;margin:auto;border-collapse: collapse;'><thead><tr><th style='text-align:center'>LEAVE</th><th style='text-align:center'>ALLOTTED</th><th style='text-align:center'>AVAILED</th><th style='text-align:center'>BALANCE</th><th style='text-align:center'>%</th></tr></thead>";
		 $breakCount ++; //increment for each new employee
	 }
	 
	 $leaveDetailsTable .="<tr><td style='text-align:left'><b>".strtoupper($employee[5])."</b></td><td style='text-align:right'>{$employee[6]}</td><td style='text-align:right'>{$employee[7]}</td><td style='text-align:right'>{$employee[8]}</td><td style='text-align:right'>{$employee[9]}</td></tr>";
	 
	 $allotted +=$employee[6];
	 $availed +=$employee[7];
	 $bal +=$employee[8];
	 $per =round((($availed)/($allotted))*100,2);
	 $prevEmployee = $employee[0];
	 $lop = $employee[10];
	
	 }
	 // To include the leave details of the Last Employee
	 $leaveDetailsTable .="<tr><td style='text-align:left'><b>LOP</b></td><td style='text-align:right'>-</td><td style='text-align:right'>{$employee[10]}</td><td style='text-align:right'>-</td><td style='text-align:right'>-</td></tr></table></div>";
	 $htmltable .= $employeeDetailsTable.$leaveDetailsTable;
	 $htmltable.='</div>';
	 
	 $this->components [] = ($queryData [0] != 'error') ? new Component ( 'html', '', 'top', array (
	 		"status" => "success",
	 		"data" => $htmltable
	 ) ) : array (
	 		"type" => 'table',
	 		"title" => 'ATTENDANCE REPORT FOR',
	 		"position" => 'top',
	 		"status" => "error",
	 		"data" => $htmltable
	 );
	
	 
	 return $this->getJsonData ( $this );
    
	 
	}
	function MS001(){
		//In this function we are going to generate employee wise attendance report
		$this->name = 'MASTER SALARY REPORT';
		$allow = $Sumallow = $tableQuery ="";
		$curenttitle = "";
		foreach ( $this->allowDeducArray ['A'] as $key => $val ) {
			$allow .= ",(s." . $val ['pay_structure_id'] . ") " . $val ['alias_name'];
			$Sumallow .= ",SUM(s." . $val ['pay_structure_id'] . ") " . $val ['alias_name'];
		}
		$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
		$customFields = $this->reportParams ['isCustom'];
		$customFields= ",".str_replace("^"," ",$customFields);
		$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
		
		$tableQuery ="SELECT s.employee_id ID,w.employee_name NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns)." $allow ,s.employee_salary_amount GROSS
					  FROM employee_salary_details s
					  INNER JOIN employee_work_details w
					  ON s.employee_id = w.employee_id
					  INNER JOIN employee_personal_details pd
					  ON s.employee_id = pd.employee_id
					  INNER JOIN company_designations ds
					  ON w.designation_id = ds.designation_id
					  INNER JOIN company_branch cb
					  ON w.branch_id = cb.branch_id
					  INNER JOIN company_departments dp
					  ON w.department_id = dp.department_id
					  LEFT JOIN company_team ct
					  ON w.team_id = ct.team_id
					  LEFT JOIN employee_work_details repo
          			  ON w.employee_reporting_person = repo.employee_id
				      LEFT JOIN company_shifts cs
				 	  ON w.shift_id = cs.shift_id
					  WHERE w.enabled = 1 $whereCondition;";
		
		$queryData = $this->getComponentsResult ( $tableQuery );
		$this->title = 'MASTER SALARY REPORT' . $curenttitle;
		
		$headersArr= array();
		
		
		foreach($queryData[0] as $header)
			array_push($headersArr,array("name"=>$header));
			
		$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'MASTER SALARY REPORT ' . $curenttitle, 'top', array (
			"status" => "success",
			"headers" => $headersArr,
			"queryData" => $queryData [1],
			"fixedColumns" => 2 ,
			"isRemoveIndices" =>true,
			"isTotal" =>true
					
					
		) ) : array (
			"type" => 'table',
			"title" => 'MASTER SALARY REPORT',
			"position" => 'top',
			"status" => "error",
			"data" => $queryData [1]
		);
		
		$grossIndex=array_search("GROSS",$queryData[0]);
		$dataArr=array("value");
		$headarr =array("ID");
		foreach($queryData[1] as $key=>$value){
			array_push($dataArr,$value[$grossIndex]);
			array_push($headarr,$value[0]);
			//array_push($headarr,$value[1].' ['.$value[0].']');
		}
		$queryData = $this->getComponentsResult ( $tableQuery );
		$this->title = 'Master Salary Report' . $curenttitle;
		$this->components [] = ($queryData [0] != 'error') ? new Component ( 'barchart', 'Master Salary Report (Info Graphical)' . $curenttitle, 'bottom', array (
				"status" => "success",
				"xLabels" => $headarr,
				"data" => array($dataArr),
				"lineColumn" => 0
		) ) : array (
				"type" => 'table',
				"title" => 'Cumulative Master Salary',
				"position" => 'bottom',
				"status" => "error",
				"data" => $queryData [1]
		);
		
		return $this->getJsonData ( $this );
	}
	
	function AT003(){
		//In this function we are going to generate Employee day attendance report
		$this->name = 'DAY WISE ATTENDANCE REPORT FOR';
		$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:''); 
		$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
		
		$curenttitle = " ".date("j M Y", strtotime($from_date))." TO ".date("j M Y", strtotime($end_date));
		
		$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
		$customFields = $this->reportParams ['isCustom'];
		$customFields= ",".str_replace("^"," ",$customFields);
		//$colfields = preg_replace('/,/',' ', $customFields,1);
		//$colfields = $colfields.',';
		$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
		
		$caseData ="SELECT employee_id ID,CONCAT(employee_name,' ',employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",";
		while (strtotime($from_date) <= strtotime($end_date) && $from_date!='') {
			$day = date("j", strtotime($from_date)).'-'.substr(date("D", strtotime($from_date)),0, -1);
			// Check with Biometric check_in and check_out
			$caseData.="MAX(CASE WHEN (weeks = IF((WEEK('$from_date') - WEEK(DATE_FORMAT('$from_date' , '%Y-%m-01')) + 1)>5,(WEEK('$from_date') - WEEK(DATE_FORMAT('$from_date' , '%Y-%m-01')) - 1),WEEK('$from_date') - WEEK(DATE_FORMAT('$from_date' , '%Y-%m-01')) + 1)) 
                                  		THEN (CASE WHEN (DAYNAME('$from_date')='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),IF((sunday = 'FH' OR sunday = 'SH'),CONCAT('WE','-',sunday),'WE'),sunday) WHEN (DAYNAME('$from_date')='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),IF((monday = 'FH' OR monday = 'SH'),CONCAT('WE','-',monday),'WE'),monday) WHEN (DAYNAME('$from_date')='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),IF((Tuesday = 'FH' OR Tuesday = 'SH'),CONCAT('WE','-',Tuesday),'WE'),Tuesday) WHEN (DAYNAME('$from_date')='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),IF((wednesday = 'FH' OR wednesday = 'SH'),CONCAT('WE','-',wednesday),'WE'),wednesday) WHEN (DAYNAME('$from_date')='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),IF((thursday = 'FH' OR thursday = 'SH'),CONCAT('WE','-',thursday),'WE'),thursday) WHEN (DAYNAME('$from_date')='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),IF((friday = 'FH' OR friday = 'SH'),CONCAT('WE','-',friday),'WE'),friday) WHEN (DAYNAME('$from_date')='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),IF((saturday = 'FH' OR saturday = 'SH'),CONCAT('WE','-',saturday),'WE'),saturday) ELSE '' END)
					               WHEN ((start_date BETWEEN '$from_date' AND '$from_date') OR (end_date BETWEEN '$from_date' AND '$from_date')) THEN (CASE WHEN (category = 'OPTIONAL' AND holBranch = empBranch) THEN 'RH' WHEN (category = 'HOLIDAY' AND holBranch = 'NA') THEN 'GH' ELSE '' END)
					               WHEN DATE_FORMAT(check_in,'%Y-%m-%d') ='$from_date' AND DATE_FORMAT(check_out,'%Y-%m-%d') ='$from_date'  THEN 'P'
					               WHEN DATE_FORMAT(absent_date,'%Y-%m-%d') = '$from_date' THEN leave_details
						           WHEN DATE_FORMAT(last_working_date,'%Y-%m-%d') < '$from_date' OR employee_doj > '$from_date' THEN '-'
					               WHEN device_status = 0 THEN 'P'  ELSE 'A' END) `$day`,";

			// Not Check with Biometric check_in and check_out
			/*$caseData.="MAX(CASE WHEN (weeks = IF((WEEK('$from_date') - WEEK(DATE_FORMAT('$from_date' , '%Y-%m-01')) + 1)>5,(WEEK('$from_date') - WEEK(DATE_FORMAT('$from_date' , '%Y-%m-01')) - 1),WEEK('$from_date') - WEEK(DATE_FORMAT('$from_date' , '%Y-%m-01')) + 1))
									  THEN (CASE WHEN (DAYNAME('$from_date')='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),IF((sunday = 'FH' OR sunday = 'SH'),CONCAT('WE','-',sunday),'WE'),sunday) WHEN (DAYNAME('$from_date')='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),IF((monday = 'FH' OR monday = 'SH'),CONCAT('WE','-',monday),'WE'),monday) WHEN (DAYNAME('$from_date')='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),IF((Tuesday = 'FH' OR Tuesday = 'SH'),CONCAT('WE','-',Tuesday),'WE'),Tuesday) WHEN (DAYNAME('$from_date')='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),IF((wednesday = 'FH' OR wednesday = 'SH'),CONCAT('WE','-',wednesday),'WE'),wednesday) WHEN (DAYNAME('$from_date')='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),IF((thursday = 'FH' OR thursday = 'SH'),CONCAT('WE','-',thursday),'WE'),thursday) WHEN (DAYNAME('$from_date')='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),IF((friday = 'FH' OR friday = 'SH'),CONCAT('WE','-',friday),'WE'),friday) WHEN (DAYNAME('$from_date')='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),IF((saturday = 'FH' OR saturday = 'SH'),CONCAT('WE','-',saturday),'WE'),saturday) ELSE '' END)
								 WHEN ((start_date BETWEEN '$from_date' AND '$from_date') OR (end_date BETWEEN '$from_date' AND '$from_date')) THEN (CASE WHEN (category = 'OPTIONAL' AND holBranch = empBranch) THEN 'RH' WHEN (category = 'HOLIDAY' AND holBranch = 'NA') THEN 'GH' ELSE '' END)
								 WHEN DATE_FORMAT(absent_date,'%Y-%m-%d') = '$from_date' THEN leave_details
								 WHEN DATE_FORMAT(last_working_date,'%Y-%m-%d') < '$from_date' OR employee_doj > '$from_date' THEN '-'
								 WHEN device_status IN (0,1) THEN 'P'  ELSE 'A' END) `$day`,";*/
			$from_date= date ("Y-m-d", strtotime("+1 day", strtotime($from_date)));
		}
		// Check with Biometric check_in and check_out
		$tableQuery =substr($caseData,0,-1)."FROM (
						SELECT * FROM 
						(SELECT employee_id ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)." ,(CASE WHEN is_day=1 AND DATE_FORMAT(date_time,'%H:%i') THEN MIN(date_time)
						                         WHEN is_day=0 AND DATE_FORMAT(date_time,'%H:%i') THEN work_day END) 'check_in',
						                   (CASE WHEN is_day=1 AND DATE_FORMAT(MAX(date_time),'%H:%i') THEN MAX(date_time)
						                         WHEN is_day=0 AND DATE_FORMAT(MIN(work_day),'%H:%i') THEN MIN(work_day) ELSE '' END) 'check_out',
						        employee_doj,last_working_date,absent_date,leave_details,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,
						        employee_name,employee_lastname,start_date,end_date,holBranch,empBranch,shift_id,device_status,category
						FROM (
						SELECT z.employee_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",str_ireplace("SHIFT_NAME","z.SHIFT_NAME",$columns))." ,
						     (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
						       ELSE date_time END) work_day,
						       z.employee_doj,n.last_working_date,a.absent_date,IF(a.day_type!='FD',CONCAT(UPPER(a.leave_rule_type),'-',a.day_type),UPPER(a.leave_rule_type)) leave_details,
						       device_status,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,employee_name,employee_lastname,start_date,end_date,h.branch_id holBranch,empBranch,category
						FROM (
						SELECT w.employee_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,IF(from_date<'$start_date','$start_date',from_date) from_date,IF(to_date='0000-00-00' OR to_date IS NULL ,'$end_date',to_date) to_date
						       ,w.employee_doj,w.employee_name,w.employee_lastname,w.branch_id empBranch $columns, IFNULL(d.status,0) device_status
						FROM employee_work_details w
						INNER JOIN employee_personal_details pd
						ON w.employee_id = pd.employee_id
						LEFT JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						LEFT JOIN company_departments dp
						ON w.department_id = dp.department_id
						LEFT JOIN company_designations ds
						ON w.designation_id = ds.designation_id
						LEFT JOIN company_team ct
						ON w.team_id = ct.team_id 
						LEFT JOIN device_users d
						ON w.employee_id = d.employee_id 
						LEFT JOIN employee_work_details repo
          				ON w.employee_reporting_person = repo.employee_id
						LEFT JOIN company_shifts cs
						ON w.shift_id = cs.shift_id
						LEFT JOIN  shift_roaster r
						ON r.employee_id = w.employee_id AND ((NOT (from_date > '$end_date' OR to_date < '$start_date' )) OR ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$end_date' ))
						WHERE w.enabled = 1 $whereCondition ORDER BY w.employee_id ) z
						LEFT JOIN company_shifts s
						ON z.shift_id = s.shift_id
						LEFT JOIN emp_notice_period n 
						ON z.employee_id = n.employee_id AND n.status='A'
						LEFT JOIN device_users du
						ON z.employee_id = du.employee_id
						LEFT JOIN employee_biometric b
						ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
						LEFT JOIN leave_requests l
						ON z.employee_id = l.employee_id  AND (l.from_date BETWEEN '$start_date' AND '$end_date') AND (l.to_date BETWEEN '$start_date' AND '$end_date') 
						LEFT JOIN emp_absences a
						ON z.employee_id = a.employee_id AND a.absent_date BETWEEN '$start_date' AND '$end_date'
						LEFT join weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
						LEFT JOIN holidays_event h ON (h.start_date BETWEEN '$start_date' AND '$end_date') 
						WHERE s.is_day IS NOT NULL 
						ORDER BY z.employee_id,date_time )q
						GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d'),absent_date,weeks,start_date ORDER BY employee_id) t1
						)w GROUP BY employee_id;";
		// Not Check with Biometric check_in and check_out
		/*$tableQuery =substr($caseData,0,-1)."FROM (
						SELECT * FROM
						(SELECT employee_id,employee_doj,last_working_date,absent_date,leave_details,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,
								employee_name,employee_lastname,start_date,end_date,holBranch,empBranch,shift_id,device_status,category
						FROM (
						SELECT z.employee_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,
						z.employee_doj,n.last_working_date,a.absent_date,IF(a.day_type!='FD',CONCAT(UPPER(a.leave_rule_type),'-',a.day_type),UPPER(a.leave_rule_type)) leave_details,
						device_status,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,employee_name,employee_lastname,start_date,end_date,h.branch_id holBranch,empBranch,category
						FROM (
						SELECT w.employee_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,IF(r.shift_id IS NULL,'$start_date',IF(r.to_date IS NULL AND r.from_date <'$start_date'  OR r.from_date < '$start_date','$start_date',DATE_FORMAT(r.from_date,'%Y-%m-%d'))) from_date,IFNULL(r.to_date,'$end_date') to_date
								,w.employee_doj,w.employee_name,w.employee_lastname,w.branch_id empBranch,IFNULL(d.status,0) device_status
						FROM employee_work_details w
						LEFT JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						LEFT JOIN company_departments dp
						ON w.department_id = dp.department_id
						LEFT JOIN company_designations ds
						ON w.designation_id = ds.designation_id
						LEFT JOIN company_team ct
						ON w.team_id = ct.team_id
						LEFT JOIN device_users d
						ON w.employee_id = d.employee_id
						LEFT JOIN  shift_roaster r
						ON r.employee_id = w.employee_id AND (r.from_date BETWEEN '$start_date' AND '$end_date' OR r.from_date < '$start_date') AND (r.to_date BETWEEN '$start_date' AND '$end_date' OR r.to_date IS NULL)
						WHERE w.enabled = 1 $whereCondition ORDER BY w.employee_id ) z
						LEFT JOIN company_shifts s
						ON z.shift_id = s.shift_id
						LEFT JOIN emp_notice_period n
						ON z.employee_id = n.employee_id AND n.status='A'
						LEFT JOIN device_users du
						ON z.employee_id = du.employee_id
						LEFT JOIN leave_requests l
						ON z.employee_id = l.employee_id  AND (l.from_date BETWEEN '$start_date' AND '$end_date') AND (l.to_date BETWEEN '$start_date' AND '$end_date')
						LEFT JOIN emp_absences a
						ON z.employee_id = a.employee_id AND a.absent_date BETWEEN '$start_date' AND '$end_date'
						LEFT join weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
						LEFT JOIN holidays_event h ON (h.start_date BETWEEN '$start_date' AND '$end_date')
						WHERE s.is_day IS NOT NULL
						ORDER BY z.employee_id )q
						GROUP BY employee_id,absent_date,weeks,start_date ORDER BY employee_id) t1
						)w GROUP BY employee_id;";*/
		
		//echo $tableQuery; die();
		$queryData = $this->getComponentsResult ( $tableQuery );
		$this->title = 'ATTENDANCE REPORT FOR ' . $curenttitle;
		if($queryData[0]!='error'){
		$headersArr= array();
		foreach($queryData[0] as $header)
			array_push($headersArr,array("name"=>$header));
			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'ATTENDANCE REPORT FOR ' . $curenttitle, 'top', array (
					"status" => "success",
					"headers" => $headersArr,
					"queryData" => $queryData [1],
					"fixedColumns" => 2 ,
					"isRemoveIndices" =>true,
					"isTotal" =>true
					
					
			) ) : array (
					"type" => 'table',
					"title" => 'ATTENDANCE REPORT',
					"position" => 'top',
					"status" => "error",
					"data" => $queryData [1]
			);
		
			
			return $this->getJsonData ( $this );
	}else{
		return  false;
	}
  }
  
  function BT001(){
  	//In this function we are going to generate Employee day attendance report
  	$fromPeriod = $this->reportParams ['from_period'] !=''?explode ( '*', $this->reportParams ['from_period'] ) [0]:'';
  	$toPeriod =  $this->reportParams ['to_period'] !=''?explode ( '*', $this->reportParams ['to_period'] ) [0]:'';
  	$startPeriod = $fromPeriod; 
  	$reportType = $this->reportParams ['reportType']; //OT or Late or Early out Report
  	$this->name = 'Biometric ' .ucfirst($reportType). ' Report For';
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	if($this->reportParams ['from_date']==''&& $this->reportParams ['to_date']==''){
  		$start_date = $fromPeriod;
  		$end_date =$toPeriod = date("Y-m-t", strtotime($toPeriod));
  	}else{
  		$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  		$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	}
  	if($this->reportParams ['from_date']==''&& $this->reportParams ['to_date']=='')
  		$curenttitle = " ".date("j M Y", strtotime($fromPeriod))." to ".date("j M Y", strtotime($toPeriod));
    else 
  		$curenttitle = " ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	
  		$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  		$caseData ="SELECT employee_id ID,CONCAT(employee_name,' ',employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",";
  	if($this->reportParams ['from_date']==''&& $this->reportParams ['to_date']==''){
  		while (strtotime($startPeriod) <= strtotime($toPeriod) && $startPeriod!='') {
  			$month = date("mY", strtotime($startPeriod)); $day = date("M y", strtotime($startPeriod));
  			$caseData.="IFNULL(SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN DATE_FORMAT(work_day,'%m%Y')='$month' THEN $reportType ELSE '-' END))),'-') `$day`,";
  			$startPeriod= date ("Y-m-d", strtotime("+1 month", strtotime($startPeriod)));
  		}
  	}else{
  		while (strtotime($from_date) <= strtotime($end_date) && $from_date!='') {
  			$day = date("D j,M", strtotime($from_date));
  			$caseData.="MAX(CASE WHEN work_day='$from_date' THEN SUBSTRING_INDEX($reportType,':',2) ELSE '-' END) `$day`,";
  			$from_date= date ("Y-m-d", strtotime("+1 day", strtotime($from_date)));
  		}
  	}
  	
  	$reportFor = "SELECT employee_id,employee_name,employee_lastname ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",from_date,to_date,work_day,shift_hrs,shift_id,SUBSTRING_INDEX(ot,'|',1) late_in,SUBSTRING_INDEX(SUBSTRING_INDEX(ot,'|',2),'|',-1) early_out,IF(SUBSTRING_INDEX(ot,'|',-1)>=min_hrs_ot AND SUBSTRING_INDEX(ot,'|',-1) !='00:00:00',(SUBSTRING_INDEX(ot,'|',-1)),'-') ot";
  		
  	$tableQuery =substr($caseData,0,-1)." FROM ( $reportFor
					FROM (
					SELECT employee_id ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",ref_id,from_date,to_date,work_day,shift_id,check_in,check_out,TIMEDIFF(check_out,check_in) hrs_worked,shift_hrs,
					       Calculate_OT(work_day,SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),check_in,check_out,start_time,end_time,shift_hrs,min_hrs_ot,late_end,is_day) ot,employee_name,employee_lastname,min_hrs_ot
					       
					FROM ( 
					SELECT employee_id ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",ref_id,shift_id,from_date,to_date,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,
					                          (CASE WHEN is_day=1 THEN MIN(date_time) 
					                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END)  'check_in',
					                          (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out',
					                        employee_doj,is_day,shift_hrs,start_time,end_time,late_end,min_hrs_ot,employee_name,employee_lastname
					FROM ( 
					      SELECT z.employee_id ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",str_ireplace("SHIFT_NAME","z.SHIFT_NAME",$columns)).",z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
					      s.shift_hrs,s.min_hrs_half_day,s.min_hrs_ot,
					            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
					                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj,z.employee_name,z.employee_lastname 
					      FROM (
					      SELECT w.employee_id $columns,u.ref_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,
					            IF(from_date<'$start_date','$start_date',from_date) from_date,IF(to_date='0000-00-00' OR to_date IS NULL ,'$end_date',to_date) to_date,
					            w.employee_doj,w.employee_name,w.employee_lastname,w.branch_id empBranch 
					      FROM employee_work_details w 
					      INNER JOIN employee_personal_details pd
						  ON w.employee_id = pd.employee_id
						  LEFT JOIN company_branch cb
						  ON w.branch_id = cb.branch_id
						  LEFT JOIN company_departments dp
						  ON w.department_id = dp.department_id
						  LEFT JOIN company_designations ds
						  ON w.designation_id = ds.designation_id 
						  LEFT JOIN company_team ct
						  ON w.team_id = ct.team_id
					      LEFT JOIN device_users u
					      ON w.employee_id = u.employee_id
						  LEFT JOIN employee_work_details repo
          				  ON w.employee_reporting_person = repo.employee_id
						  LEFT JOIN company_shifts cs
						  ON w.shift_id = cs.shift_id
					      LEFT JOIN  shift_roaster r 
					      ON w.employee_id = r.employee_id 
					      AND ((NOT (from_date > '$end_date' OR to_date < '$start_date' )) OR ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$end_date' ))
					      WHERE w.enabled = 1 $whereCondition ORDER BY w.employee_id ) z
					      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
					      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
					      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date 
					      WHERE s.is_day IS NOT NULL ORDER BY z.employee_id,date_time )q 
					      GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')
					       ) w WHERE check_in IS NOT NULL )t
					      )w GROUP BY employee_id ORDER BY employee_id;";
  	
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'BIOMETRIC ' .ucfirst($reportType). ' REPORT FOR' . $curenttitle;
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'BIOMETRIC ' .ucfirst($reportType). ' REPORT FOR' . $curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => 'BIOMETRIC ' .ucfirst($reportType). ' REPORT',
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
 
  }
  
  function BT002(){
  	$this->name = 'BIOMETRIC PUNCHES REPORT FOR';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " ".date("j M Y", strtotime($from_date))." TO ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$caseData ="SELECT employee_id ID,CONCAT(employee_name,' ',employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",";
  	
  	while (strtotime($from_date) <= strtotime($end_date) && $from_date!='') {
  		$day = date("D j,M", strtotime($from_date));
  		$caseData.="MAX(CASE WHEN work_day='$from_date' THEN biopunch ELSE '-' END) `$day`,";
  		$from_date= date ("Y-m-d", strtotime("+1 day", strtotime($from_date)));
  	}
  	
  	$tableQuery =substr($caseData,0,-1)." FROM (
						SELECT employee_id,employee_name,employee_lastname ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",work_day,GROUP_CONCAT(punches ORDER BY date_time) biopunch FROM (
						SELECT employee_id,employee_name,employee_lastname ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",is_day,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,DATE_FORMAT(work_day,'%H:%i') punches 
						FROM (
						SELECT z.employee_id ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",str_ireplace("SHIFT_NAME","z.SHIFT_NAME",$columns)).",z.shift_id,s.is_day,b.date_time,z.from_date,z.to_date,
						      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
						       ELSE date_time END) work_day,
						       z.employee_doj,device_status,employee_name,employee_lastname
						FROM (
						SELECT w.employee_id $columns,d.ref_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,IF(from_date<'$start_date','$start_date',from_date) from_date,IF(to_date='0000-00-00' OR to_date IS NULL ,'$end_date',to_date) to_date
						       ,w.employee_doj,w.employee_name,w.employee_lastname ,w.branch_id empBranch,IFNULL(d.status,0) device_status
						FROM employee_work_details w
						INNER JOIN employee_personal_details pd
						ON w.employee_id = pd.employee_id
						LEFT JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						LEFT JOIN company_departments dp
						ON w.department_id = dp.department_id
						LEFT JOIN company_designations ds
						ON w.designation_id = ds.designation_id 
						LEFT JOIN company_team ct
						ON w.team_id = ct.team_id
						LEFT JOIN device_users d
						ON w.employee_id = d.employee_id
						LEFT JOIN employee_work_details repo
          				ON w.employee_reporting_person = repo.employee_id
						LEFT JOIN company_shifts cs
						ON w.shift_id = cs.shift_id 
						LEFT JOIN  shift_roaster r
						ON r.employee_id = w.employee_id AND ((NOT (from_date > '$end_date' OR to_date < '$start_date' )) OR ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$end_date' ))
						WHERE w.enabled = 1 $whereCondition ORDER BY w.employee_id ) z
						LEFT JOIN company_shifts s
						ON z.shift_id = s.shift_id
						INNER JOIN employee_biometric b
						ON z.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN '$start_date' AND DATE_ADD('$end_date',INTERVAL 1 DAY)
						WHERE s.is_day IS NOT NULL 
						ORDER BY z.employee_id,date_time,DATE_FORMAT(work_day,'%H:%i')) q 
						WHERE DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
						) w GROUP BY employee_id,work_day
						)w GROUP BY employee_id ORDER BY employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'BIOMETRIC PUNCHES REPORT FOR' . $curenttitle;
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'BIOMETRIC PUNCHES REPORT FOR' . $curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => 'BIOMETRIC PUNCHES REPORT',
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function SS002(){
  	$this->name = 'MISC PAY/DEDU REPORT FOR ';
  	$fromPeriod = explode ( '*', $this->reportParams ['from_period'] ) [1];
  	$toPeriod = explode ( '*', $this->reportParams ['to_period'] ) [1];
  	$curenttitle = ($fromPeriod == $toPeriod) ? $fromPeriod : $fromPeriod . " TO " . $toPeriod;
  	$subTitle = ($this->reportParams ['reportFor'] == 'M') ? "Month of" : (($this->reportParams ['reportFor'] == 'Q') ? "Quarter of" : (($this->reportParams ['reportFor'] == 'HY') ? "Half Year of" : (($this->reportParams ['reportFor'] == 'Y') ? "Year of" : '')));
  	
  	
  	$this->reportParams ['from_period'] = explode ( '*', $this->reportParams ['from_period'] ) [0];
  	$this->reportParams ['to_period'] = explode ( '*', $this->reportParams ['to_period'] ) [0];
  	
  	// prepare the Query for the table component
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$colfields = preg_replace('/,/','', $customFields,1);
  	$hedfields = str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$colfields);
  	$colfield = $colfields.',';
  	$headcol = str_replace(',',' ',$hedfields);
  	$headcol = str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$headcol);
  	
  	if($headcol!=''){ 
  		$fields=explode(" ",$headcol);
  	}else{
  		$fields='';
  	}
  	$columns = ($this->reportParams ['isCustom']!='')?$colfield:'';
  	$columns = str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","IFNULL(repo.EMPLOYEE_NAME,'') REPORTING_MANAGER",str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns));
  	
  	$miscallow = $miscdedu = $miscallowCond = $miscdeduCond ="";
  	$montyYearCase ="";
  	
  	$headersArr=array(
  			array("name"=>"ID"),
  			array("name"=>"NAME"),
  			array('name'=>"PERIOD"),
  	);
  	
  	
  	if(!empty($fields)){
  	foreach ($fields as $column){
  		array_push($headersArr,array("name"=>$column));
  		}
  	}
 
  	$headArr = array("name"=>"MISC_PAYMENT","children"=>array());
  	if(count($this->miscallowDeducArray ["MP"])>0) {
  		foreach ( $this->miscallowDeducArray ["MP"] as $key => $val ) {
  			array_push($headArr["children"],array("name"=>$val ['display_name']));
  			$miscallow.= "SUM(" . $val ['pay_structure_id'] . ") " . $val ['type']."_".str_replace(" ","_",$val ['display_name']).",";
  			$miscallowCond.=  $val ['pay_structure_id'] . " >0 OR ";
  		}
  	}
  	array_push($headersArr, $headArr);
  	
  	$headArr = array("name"=>"MISC_DEDUCTION","children"=>array());
  	if(count($this->miscallowDeducArray ["MD"])>0) {
  		foreach ( $this->miscallowDeducArray ["MD"] as $key => $val ) {
  			array_push($headArr["children"],array("name"=>$val ['display_name']));
  			$miscdedu.= "SUM(" . $val ['pay_structure_id'] . ") " . $val ['type']."_".str_replace(" ","",$val ['display_name']).",";
  			$miscdeduCond.= $val ['pay_structure_id'] . " >0 OR ";
  		}
  	}
  	$cond = rtrim($miscallowCond.$miscdeduCond,'OR ');
  	
  	array_push($headersArr, $headArr);
  	
  	if ($this->reportParams ['reportFor'] == 'M') {
  		$miscallow = str_replace("SUM","",$miscallow);
  		$miscdedu = str_replace("SUM","",$miscdedu);
  		$montyYearCase = ",DATE_FORMAT(p.month_year,'%M %Y') Period,";
  	}
  	
  	
  	$period_from = $this->reportParams ['from_period'];
  	$caseStmt = ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? ",(CASE " : '';
  	$i = ($this->reportParams ['reportFor'] != 'M' && $this->reportParams ['reportFor'] != 'Y') ? (($this->reportParams ['reportFor'] == 'HY') ? $fromPeriod [2] : $fromPeriod [1]) : 1;
  	while ( strtotime ( $period_from ) <= strtotime ( $this->reportParams ['to_period'] ) ) {
  			if ($this->reportParams ['reportFor'] == 'M') { // without dynamic Colums Only Gross
  				$caseStmt = "";
  			} else if ($this->reportParams ['reportFor'] == 'Q') {
  				$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+2 months", strtotime ( $period_from ) ) ) . "' THEN 'Q" . $i .'  '.(($this->reportParams ['yearType'] == 'FY')?(date ( "Y",strtotime( $this->reportParams ['from_period'])).'-'.substr(date ( "Y",strtotime( $this->reportParams ['from_period']))+1,2)):(date ( "Y",strtotime($period_from)))). "'";
  				$i ++;
  			} else if ($this->reportParams ['reportFor'] == 'HY') {
  				$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+5 months", strtotime ( $period_from ) ) ) . "'  THEN 'HY" . $i .'  '.(($this->reportParams ['yearType'] == 'FY')?(date ( "Y",strtotime( $this->reportParams ['from_period'])).'-'.substr(date ( "Y",strtotime( $this->reportParams ['from_period']))+1,2)):(date ( "Y",strtotime($period_from)))). "'";
  				$i ++;
  			} else if ($this->reportParams ['reportFor'] == 'Y') {
  				$caseStmt .= "WHEN p.month_year BETWEEN '$period_from' AND '" . date ( "Y-m-d", strtotime ( "+11 months", strtotime ( $period_from ) ) ) . "' THEN  '".(($this->reportParams ['yearType'] == 'FY')?(date ( "Y",strtotime($period_from)).'-'.substr(date ( "Y", strtotime ( "+11 months", strtotime ( $period_from ) ) ),2)):(date ( "Y",strtotime($period_from)))). "'"; // For Month
  				$i ++;
  			}
  		$period_from = ($this->reportParams ['reportFor'] == 'M') ? date ( "Y-m-d", strtotime ( "+1 months", strtotime ( $period_from ) ) ) : // For Month
  			(($this->reportParams ['reportFor'] == 'Q') ? date ( "Y-m-d", strtotime ( "+3 months", strtotime ( $period_from ) ) ) : (($this->reportParams ['reportFor'] == 'HY') ? date ( "Y-m-d", strtotime ( "+6 months", strtotime ( $period_from ) ) ) : date ( "Y-m-d", strtotime ( "+12 months", strtotime ( $period_from ) ) )));
  		}
  	$caseStmt .= ($this->reportParams ['isConsolidate'] != 1 && $this->reportParams ['reportFor'] != 'M') ? "END) Period," : '';
  	$groupcondition = " GROUP BY p.employee_id,Period order by p.month_year,p.employee_id";
  		
  	$tableQuery = "SELECT p.employee_id ID,p.employee_name NAME";
  		
  	$tableQuery .= $montyYearCase.$columns;
  	$tableQuery .= $caseStmt. $miscallow .substr($miscdedu,0,-1).
			  		" FROM payroll p
			  		INNER JOIN employee_work_details w
			  		ON p.employee_id = w.employee_id
			  		INNER JOIN employee_personal_details pd
			  		ON p.employee_id = pd.employee_id
			  		INNER JOIN company_designations ds
			  		ON w.designation_id = ds.designation_id
			  		INNER JOIN company_branch cb
			  		ON w.branch_id = cb.branch_id
			  		INNER JOIN company_departments dp
			  		ON w.department_id = dp.department_id
					LEFT JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
			  		WHERE p.month_year BETWEEN '" . $this->reportParams ['from_period'] . "' AND '" . $this->reportParams ['to_period'] . "'
					AND w.enabled=1 AND ($cond) $whereCondition $groupcondition";
  	//echo $tableQuery; die();
	$queryData = $this->getComponentsResult ( $tableQuery );
	$this->title = 'MISC PAY/DEDU REPORT ' . $curenttitle;
	if($queryData[0]!='error'){
	//$headersArr= array();
		//foreach($queryData[0] as $header)
		//array_push($headersArr,array("name"=>$header));
	    $this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'MISC PAY/DEDU REPORT FOR ' . $curenttitle, 'top', array (
					"status" => "success",
					"headers" => $headersArr,
					"queryData" => $queryData [1],
					"fixedColumns" => 2 ,
					"isRemoveIndices" =>false,
	    			"headerDepth"=>2,
	    			"isTotal" =>true
										
										
					) ) : array (
					"type" => 'table',
					"title" => 'MISC PAY/DEDU REPORT',
					"position" => 'top',
					"status" => "error",
					"data" => $queryData [1]
					);
								
	  return $this->getJsonData ( $this );
	}else{
		return  false;
	}
  }
  
  function BT003(){
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	
  	$curenttitle = " ".date("j M Y", strtotime($from_date))." TO ".date("j M Y", strtotime($end_date));
  	$reportType = $this->reportParams ['reportType'];
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	if($this->reportParams ['from_date']==''&& $this->reportParams ['to_date']==''){
  		$start_date = $fromPeriod;
  		$end_date =$toPeriod = date("Y-m-t", strtotime($toPeriod));
  	}else{
  		$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  		$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	}
  	if($this->reportParams ['from_date']==''&& $this->reportParams ['to_date']=='')
  		$curenttitle = " ".date("j M Y", strtotime($fromPeriod))." to ".date("j M Y", strtotime($toPeriod));
  	else
  		$curenttitle = " ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	
    if($reportType=='late'){
  		$cond = "IF(check_in>CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00'),SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00')))),':',2),'') TOTAL_LATE_HRS";
  		$whereCond = "check_in > CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_st_time,':00') AND work_day BETWEEN '$start_date' AND '$end_date'";
  		$this->name = 'LATE COMING REPORT FOR';
  		$this->title = 'LATE COMING REPORT FOR' . $curenttitle;
    }elseif($reportType=='early'){
  		//$cond = "IF(check_out<CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00'),SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))),':',2),'') TOTAL_EARLY_OUT_HRS";
  		$cond = "IF(check_out<CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00'),SUBSTRING_INDEX(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(CONCAT(DATE_FORMAT(check_out,'%Y-%m-%d'),' ',shift_end_time,':00'),check_out))),':',2),SUBSTRING_INDEX(shift_hrs,':',2)) TOTAL_EARLY_OUT_HRS";
  		$whereCond = "IF(shift_end_time NOT BETWEEN '00:00' AND '10:00',CONCAT(DATE_FORMAT(work_day,'%Y-%m-%d'),' ',shift_end_time,':00'),CONCAT(DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d'),' ',shift_end_time,':00')) > ADDTIME(check_out,'0:01:00') ";
  		$this->name = 'EARLY EXIT REPORT FOR';
  		$this->title = 'EARLY EXIT REPORT FOR' . $curenttitle;
  	}
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  
  	$tableQuery ="SELECT employee_id ID,employee_name NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",DATE_FORMAT(work_day,'%d-%b-%Y') DATE,SUBSTRING_INDEX(DATE_FORMAT(check_in,'%T'),':',2) CHECK_IN_TIME,SUBSTRING_INDEX(DATE_FORMAT(check_out,'%T'),':',2) CHECK_OUT_TIME,
			      		SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),':',2) TOTAL_PRESENT_HRS,$cond,
						(CASE WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),':',2) >= min_hrs_full_day THEN 'Present-Full Day'
		                      WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),':',2) BETWEEN min_hrs_half_day AND min_hrs_full_day THEN 'Half a day Present'
		                 ELSE '' END) DAY_STATUS
				  FROM (
				  SELECT employee_id ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",ref_id,shift_id,from_date,to_date,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,
					                          (CASE WHEN is_day=1 THEN MIN(date_time) 
					                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END)  'check_in', 
					                          (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out', 
					                        employee_doj,is_day,shift_hrs,shift_name,start_time shift_st_time,end_time shift_end_time,early_start,min_hrs_half_day,min_hrs_full_day,min_hrs_ot,CONCAT(employee_name,' ',employee_lastname) employee_name
				 FROM ( 
					      SELECT z.employee_id ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",str_ireplace("SHIFT_NAME","z.SHIFT_NAME",$columns)).",z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,
                 			     s.shift_name,s.start_time,s.end_time,b.date_time,s.shift_hrs,s.min_hrs_half_day,s.min_hrs_ot,s.min_hrs_full_day,
					            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
			                                THEN DATE_SUB(date_time, INTERVAL 1 DAY) 
			                          WHEN (s.is_day =1 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end)
			                                THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj,z.employee_name,z.employee_lastname
					      FROM (
					      SELECT w.employee_id $columns,u.ref_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,
			                     IF(from_date<'$start_date','$start_date',from_date) from_date,IF(to_date='0000-00-00' OR to_date IS NULL ,'$end_date',to_date) to_date,
					             w.employee_doj,w.employee_name,w.employee_lastname,w.branch_id empBranch
					      FROM employee_work_details w 
					      INNER JOIN employee_personal_details pd
						  ON w.employee_id = pd.employee_id
						  LEFT JOIN company_branch cb
						  ON w.branch_id = cb.branch_id
						  LEFT JOIN company_departments dp
						  ON w.department_id = dp.department_id
						  LEFT JOIN company_designations ds
						  ON w.designation_id = ds.designation_id 
			              LEFT JOIN company_team ct
			              ON w.team_id = ct.team_id
					      LEFT JOIN device_users u
					      ON w.employee_id = u.employee_id
					      LEFT JOIN  shift_roaster r 
					      ON w.employee_id = r.employee_id AND ((NOT (from_date > '$end_date' OR to_date < '$start_date' )) OR
	 						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$end_date' ))
					      WHERE w.enabled = 1 $whereCondition ORDER BY w.employee_id ) z
					      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
					      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
					      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY) -- IF(is_day=0,DATE_ADD(to_date,INTERVAL 1 DAY),to_date)
					      WHERE s.is_day IS NOT NULL ORDER BY z.employee_id,date_time)q
			              GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')) a
			              WHERE $whereCond AND DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date';";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name. $curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) :array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  	
  }
  
  function BT004(){
  	$this->name = 'DEVIATION REPORT FOR';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	$reportType = $this->reportParams ['reportType'];
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?str_ireplace("SHIFT_NAME","a.SHIFT_NAME",$customFields):'';
  	 
  	
  	if($this->reportParams ['from_date']==''&& $this->reportParams ['to_date']==''){
  		$start_date = $fromPeriod;
  		$end_date =$toPeriod = date("Y-m-t", strtotime($toPeriod));
  	}else{
  		$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  		$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	}
  	if($this->reportParams ['from_date']==''&& $this->reportParams ['to_date']=='')
  		$curenttitle = " ".date("j M Y", strtotime($fromPeriod))." to ".date("j M Y", strtotime($toPeriod));
  	else
  		$curenttitle = " ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
	/*
	$tableQuery ="SELECT employee_id EMPID,employee_name NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",DATE_FORMAT(work_day,'%d-%b-%Y') DATE,SHIFT_NAME,SHIFT_START_TIME,SHIFT_END_TIME,
						 SUBSTRING_INDEX(DATE_FORMAT(check_in,'%T'),':',2) ACTUAL_IN_TIME,SUBSTRING_INDEX(DATE_FORMAT(check_out,'%T'),':',2) ACTUAL_OUT_TIME,
      					 IF(TIME(TIMEDIFF(check_out,check_in))<TIME(shift_hrs),SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(shift_hrs,TIMEDIFF(check_out,check_in)),'.',1),':',2),'') SHORTAGE_HOURS,
      					 IF(TIME(TIMEDIFF(check_out,check_in))>TIME(shift_hrs),SUBSTRING_INDEX(SUBSTRING_INDEX(TIMEDIFF(TIMEDIFF(check_out,check_in),shift_hrs),'.',1),':',2),'') EXCESS_HOURS
      			 FROM (
				 SELECT employee_id $columns,ref_id,shift_id,from_date,to_date,DATE_FORMAT(work_day,'%Y-%m-%d') work_day,
					                          (CASE WHEN is_day=1 THEN MIN(date_time) 
					                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_ADD(work_day,INTERVAL 1 DAY),work_day) END) 'check_in',
					                          (CASE WHEN is_day=1 OR is_day=0 THEN MAX(date_time) END) 'check_out', 
					                          is_day,shift_hrs,shift_name SHIFT_NAME,start_time SHIFT_START_TIME,end_time SHIFT_END_TIME,min_hrs_ot,CONCAT(employee_name,' ',employee_lastname) employee_name,employee_doj
					FROM ( 
					      SELECT z.employee_id $columns,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,
                      s.shift_name,s.start_time,s.end_time,b.date_time,s.shift_hrs,s.min_hrs_half_day,s.min_hrs_ot,
					            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
					                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj,z.employee_name,z.employee_lastname 
					      FROM (
					      SELECT w.employee_id $columns,u.ref_id,IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',w.shift_id),r.shift_id) shift_id,
					            IF(from_date<'$start_date','$start_date',from_date) from_date,IF(to_date='0000-00-00' OR to_date IS NULL ,'$end_date',to_date) to_date,
					            w.employee_doj,w.employee_name,w.employee_lastname,w.branch_id empBranch 
					      FROM employee_work_details w 
					      INNER JOIN employee_personal_details pd
						  ON w.employee_id = pd.employee_id
						  LEFT JOIN company_branch cb
						  ON w.branch_id = cb.branch_id
						  LEFT JOIN company_departments dp
						  ON w.department_id = dp.department_id
						  LEFT JOIN company_designations ds
						  ON w.designation_id = ds.designation_id
			              LEFT JOIN company_team ct
			              ON w.team_id = ct.team_id
					      LEFT JOIN device_users u
					      ON w.employee_id = u.employee_id
					      LEFT JOIN  shift_roaster r 
					      ON w.employee_id = r.employee_id 
					      AND ((NOT (from_date > '$end_date' OR to_date < '$start_date' )) OR ((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$end_date' )) 
					      WHERE w.enabled = 1 $whereCondition ORDER BY w.employee_id ) z
					      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
					      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
					      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date 
					      WHERE s.is_day IS NOT NULL ORDER BY z.employee_id,date_time)q
                          GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d')) t
						  WHERE check_in IS NOT NULL;";
	*/
	$tableQuery = "SELECT a.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",days DATE,c.shift_name SHIFT,shift_st_time SHIFT_START_TIME,
					       a.shift_end_time SHIFT_END_TIME,IFNULL(DATE_FORMAT(checkIn,'%H:%i'),'') ACTUAL_IN_TIME,IFNULL(DATE_FORMAT(checkOut,'%H:%i'),'')  ACTUAL_OUT_TIME,
					       IFNULL(SUBSTRING_INDEX(IF(lateIn !='-' AND earlyOut !='-',ADDTIME(a.lateIn,a.earlyOut),IF(lateIn !='-',lateIn,IF(earlyOut !='-',earlyOut,''))),':',2),'') SHORTAGE_HOURS,
		                   IF(ot='-','',IF(SUBSTRING_INDEX(ot,':',2)!='00:00',SUBSTRING_INDEX(ot,':',2),'')) EXCESS_HOURS,
		                   IFNULL((CASE WHEN a.day_type IN (1,0.5) AND work_hrs ='' THEN 'Weekly Off'
		          						WHEN a.day_type IN ('GH','RH') THEN IF(a.day_type='GH','General Holiday','Reserved Holiday')
		          						WHEN work_hrs !='' AND work_hrs < c.min_hrs_half_day THEN 'Worked less than minimum work hrs'
		                                WHEN work_hrs>= c.min_hrs_half_day AND work_hrs< c.shift_hrs THEN 'Half a day Present'
		          						WHEN work_hrs>= c.min_hrs_full_day THEN 'Present-Full Day'
		                           ELSE 'Absent' END),'') DAY_STATUS
					FROM attendance_summary a
					INNER JOIN employee_work_details w
					ON a.employee_id = w.employee_id
					INNER JOIN employee_personal_details pd
					ON a.employee_id = pd.employee_id
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					INNER JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					LEFT JOIN company_team ct
					ON w.team_id = ct.team_id
					INNER JOIN company_shifts c
					ON a.shift_id = c.shift_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					WHERE days BETWEEN '$start_date' AND '$end_date' AND ((lateIn !='' AND lateIn!='-') OR (earlyOut !='' AND earlyOut!='-' )) 
					$whereCondition
					ORDER BY a.employee_id,days;";
	//echo $tableQuery; die();
	$queryData = $this->getComponentsResult ( $tableQuery );
	$this->title = 'DEVIATION REPORT ' . $curenttitle;
	if($queryData[0]!='error'){
		$headersArr= array();
		foreach($queryData[0] as $header)
			array_push($headersArr,array("name"=>$header));
			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name. $curenttitle, 'top', array (
					"status" => "success",
					"headers" => $headersArr,
					"queryData" => $queryData [1],
					"fixedColumns" => 2 ,
					"isRemoveIndices" =>true,
					"isTotal" =>false
					
					
			) ) : array (
					"type" => 'table',
					"title" => $this->name,
					"position" => 'top',
					"status" => "error",
					"data" => $queryData [1]
			);
			
			return $this->getJsonData ( $this );
	}else{
		return  false;
	}
	
  }
  
  function ER001(){
  	$this->name = 'EMPLOYEE BIRTHDAY REPORT';
  	$month = $this->reportParams ['monthFor']; 
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  //	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  //	$colfields = preg_replace('/,/',' ', $columns,1);
  //	$fields=explode(",",$colfields);
  	
  	$monCond = ($this->reportParams ['monthFor'] != 'all')?"AND DATE_FORMAT(p.employee_dob,'%m')= '$month'":"";
  	
  	$tableQuery ="SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME,p.employee_gender GENDER,DATE_FORMAT(p.employee_dob,'%d-%m-%Y') DOB,
				         IFNULL(p.employee_personal_mobile,employee_mobile) PERSONAL_NO,p.employee_email EMAIL " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns)."
				FROM employee_work_details w
				INNER JOIN employee_personal_details p
				ON w.employee_id = p.employee_id
				LEFT JOIN company_branch cb
				ON w.branch_id = cb.branch_id
				LEFT JOIN company_departments dp
				ON w.department_id = dp.department_id
				LEFT JOIN company_designations ds
				ON w.designation_id = ds.designation_id
			    LEFT JOIN company_team ct
			    ON w.team_id = ct.team_id 
				LEFT JOIN employee_work_details repo
          		ON w.employee_reporting_person = repo.employee_id
				LEFT JOIN company_shifts cs
				ON w.shift_id = cs.shift_id
			    WHERE w.enabled=1 $whereCondition $monCond ORDER BY w.employee_id";
	//echo $query; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'EMPLOYEE BIRTHDAY REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function ER002(){
  	$this->name = 'EMPLOYEE ANNIVERSARY REPORT';
  	$month = $this->reportParams ['monthFor'];
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	$monCond = ($this->reportParams ['monthFor'] != 'all')?"AND DATE_FORMAT(w.employee_doj,'%m')= '$month'":"";
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	$tableQuery ="SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME,p.employee_gender GENDER,DATE_FORMAT(w.employee_doj,'%d-%m-%Y') DOJ,
      					TIMESTAMPDIFF(MONTH,w.employee_doj,NOW()) EXP_IN_MONTHS,IFNULL(p.employee_personal_mobile,employee_mobile) PERSONAL_NO,p.employee_email EMAIL " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns)."
				  FROM employee_work_details w
				  INNER JOIN employee_personal_details p
				  ON w.employee_id = p.employee_id
				  LEFT JOIN company_branch cb
				  ON w.branch_id = cb.branch_id
				  LEFT JOIN company_departments dp
				  ON w.department_id = dp.department_id
				  LEFT JOIN company_designations ds
				  ON w.designation_id = ds.designation_id
			      LEFT JOIN company_team ct
			      ON w.team_id = ct.team_id
				  LEFT JOIN employee_work_details repo
          		  ON w.employee_reporting_person = repo.employee_id
				  LEFT JOIN company_shifts cs
				  ON w.shift_id = cs.shift_id 
			      WHERE w.enabled=1 $whereCondition $monCond ORDER BY w.employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'EMPLOYEE ANNIVERSARY REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function AT004(){
  	$this->name = 'LEAVE APPLY REPORT';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " From ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	$leaveRule = array();
  	$stmt=("SELECT l.leave_rule_id,l.rule_name FROM company_leave_rules l WHERE enabled=1;" );
  	$query = mysqli_query ( $this->conn, $stmt );
  	while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
  		array_push ( $leaveRule, $row );
  	}
  	$case = $leaveTypestmt="";
  	foreach ( $leaveRule as $key => $val ){
  		$case .="WHEN lq.leave_type= '". $val ['leave_rule_id'] ."' THEN '".$val ['rule_name']."' ";
  	}
  	$leaveTypestmt = "(CASE ".$case." WHEN lq.leave_type='wfh' THEN 'Work from Home'
									  WHEN lq.leave_type='otr' THEN 'On Trip'
									  WHEN lq.leave_type='od' THEN 'On Duty' END) LEAVE_TYPE,";
  	$tableQuery = "SELECT lq.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME $columns,DATE_FORMAT(lq.from_date,'%d/%m/%Y') FROM_DATE,DATE_FORMAT(lq.to_date,'%d/%m/%Y') TO_DATE,$leaveTypestmt
						  (CASE WHEN lq.duration ='0.5' AND lq.from_half='FH' THEN 'First Half'
					            WHEN lq.duration ='0.5' AND lq.from_half='SH' THEN 'Second Half'
					            WHEN lq.duration !='0.5' THEN 'Full Day' END) Leave_Session,
					       lq.duration No_of_Days,lq.reason REASON_FOR_LEAVE,
					       (CASE WHEN lq.status ='A' THEN 'Approved'
					             WHEN lq.status ='RQ' THEN 'Requested'
								 WHEN lq.status ='C' THEN 'Cancelled'
								 WHEN lq.status ='W' THEN 'Withdrawn' END) APPROVAL_STATUS 
					FROM leave_requests lq
					INNER JOIN employee_work_details w
					ON w.employee_id = lq.employee_id
					INNER JOIN employee_personal_details pd
					ON w.employee_id = pd.employee_id
					LEFT JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					LEFT JOIN company_departments dp
					ON w.department_id = dp.department_id
					LEFT JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
					WHERE w.enabled=1 AND lq.status IN ('A','RQ','C','W') $whereCondition AND lq.from_date BETWEEN '$start_date' AND '$end_date' 
					AND lq.to_date BETWEEN '$start_date' AND '$end_date';";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'LEAVE APPLY REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function HR001(){
	  	$this->name = 'EMPLOYEE ADDITION REPORT';
	  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
	  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
	  	$curenttitle = " From ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
	  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
	  	$customFields = $this->reportParams ['isCustom'];
	  	$customFields= ",".str_replace("^"," ",$customFields);
	  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
	  	$serialno = "SET @a=0";
	  	$result = mysqli_query ( $this->conn, $serialno);
	  	$tableQuery = "SELECT @a:=@a+1 'S.NO',EMPID,NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",LOCATION,DOJ,FIXED_SALARY
					   FROM (
						SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME,cb.branch_name LOCATION $columns,
						      DATE_FORMAT(w.employee_doj,'%d %b %Y') DOJ,s.employee_salary_amount FIXED_SALARY
						FROM employee_work_details w
						LEFT JOIN employee_salary_details s
						ON w.employee_id = s.employee_id
						INNER JOIN employee_personal_details p
            			ON w.employee_id = p.employee_id
						LEFT JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						LEFT JOIN company_designations ds
						ON w.designation_id = ds.designation_id
						LEFT JOIN company_departments dept
						ON w.department_id = dept.department_id
						LEFT JOIN company_team ct
						ON w.team_id = ct.team_id
						LEFT JOIN employee_work_details repo
          				ON w.employee_reporting_person = repo.employee_id
						LEFT JOIN company_shifts cs
						ON w.shift_id = cs.shift_id
						WHERE w.enabled=1 $whereCondition AND w.employee_doj BETWEEN '$start_date' AND '$end_date'
						ORDER BY w.employee_id)z;";
	  	//echo $tableQuery; die();
	  	$queryData = $this->getComponentsResult ( $tableQuery );
	  	$this->title = 'EMPLOYEE ADDITION REPORT';
	  	
	  	$headersArr= array();
	  	if($queryData[0]!='error'){
	  		$headersArr= array();
	  		foreach($queryData[0] as $header)
	  			array_push($headersArr,array("name"=>$header));
	  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
	  					"status" => "success",
	  					"headers" => $headersArr,
	  					"queryData" => $queryData [1],
	  					"fixedColumns" => 2 ,
	  					"isRemoveIndices" =>true,
	  					"isTotal" =>true
	  					
	  					
	  			) ) : array (
	  					"type" => 'table',
	  					"title" => $this->name,
	  					"position" => 'top',
	  					"status" => "error",
	  					"data" => $queryData [1]
	  			);
	  			
	  			return $this->getJsonData ( $this );
	  	}else{
	  		return  false;
	  	}
  }
  	
  function ER004(){
  	$this->name = 'EMPLOYEE INFORMATION REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$serialno = "SET @a=0";
  	$result = mysqli_query ( $this->conn, $serialno);
  	$tableQuery = "SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME,p.employee_gender GENDER " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",DATE_FORMAT(w.employee_doj,'%d-%m-%Y') DOJ,
					    ds.designation_name DESIGNATION,DATE_FORMAT(p.employee_dob,'%d-%m-%Y') DOB,IFNULL(p.employee_pan_no,'-') PAN,p.employee_father_name FATHER_NAME,
					    IF(w.enabled=1,'On Roll','Resigned') STATUS,p.employee_email OFFICIAL_EMAIL,IFNULL(IFNULL(emp_pg_degree,emp_ug_degree),'') EDUCATION,IFNULL(p.employee_blood_group,'') BLOOD_GROUP,IFNULL(p.employee_personal_mobile,'') PERSONAL_NUMBER,
					    IFNULL(p.employee_mobile,'') ALTERNATE_MOBILE,IFNULL(IFNULL(father_mobile,mother_mobile),'') `FATHER/MOTHER_MOBILE`,CONCAT(p.employee_build_name,',',p.employee_street,',',p.employee_area,',',p.employee_city,',',p.employee_pin_code) ADDRESS,
					    IFNULL(p.employee_personal_email,p.employee_email) PERSONAL_EMAIL,pm.payment_mode_name PAYMENT_MODE,p.employee_bank_name BANK_NAME,p.employee_acc_no BANK_ACC_NO,
					    p.employee_bank_ifsc BANK_IFSC,w.employee_emp_pf_no PF_NUMBER,w.employee_emp_uan_no UAN_NUMBER,w.employee_emp_esi_no ESI_NUMBER,
					    IFNULL(np.last_working_date,'') DOR,IFNULL(res.reason_code,'') RELIEVING_REASON 
					FROM employee_work_details w
					INNER JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					LEFT JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					LEFT JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					LEFT JOIN company_departments dept
					ON w.department_id = dept.department_id
					LEFT JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN company_payment_modes pm
					ON w.payment_mode_id = pm.payment_mode_id
					LEFT JOIN emp_notice_period np
					ON w.employee_id = np.employee_id AND np.status!='P'
					LEFT JOIN exit_reasons res
          			ON np.reason = res.exit_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
					WHERE w.enabled=1 $whereCondition
					ORDER BY w.employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'EMPLOYEE INFORMATION REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function AT005(){
  	$this->name = 'LEAVE BALANCE REPORT';
  	
  	$monyear = explode(' ',($this->reportParams ['monYear']));
  	$monthYear = $monyear[1]."-".$monyear[0]."-01";
  	
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  
  	
  	$stmt = mysqli_query ( $this->conn, "SELECT IF(leave_based_on = 'finYear','FY','CY') leave_based_on FROM company_details;" );
  	$result = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
  	$leave_based = $result['leave_based_on'];
  	
  	if($leave_based=='CY'){
  		$monthstart = $monyear[1]."-01-01";
  		$year = $monyear[1];
  	}else{ 
  		$monthstart = $monyear[1]."-04-01";
  		$year1 =substr($monyear[1],2)+1;
  		$year =$monyear[1].$year1;
  	}
  	
  	$leaveRule = array();
  	$stmt=("SELECT l.leave_rule_id,l.rule_name,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
  	$query = mysqli_query ( $this->conn, $stmt );
  	while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
  		array_push ( $leaveRule, $row );
  	}
  	
  	$leaverulestmt = $openingstmt = $availedstmt = $balancestmt = $wrkdDays = $totstmt= $thismonthstmt = $totAvailedstmt ="";
  	foreach ( $leaveRule as $key => $val ){
  		$leaverulestmt .="IFNULL(p.". $val ['leave_rule_id'] .",0)". $val ['leave_rule_id'] .",";
  		$openingstmt .="total_". $val ['leave_rule_id'] ."-tot_availed_". $val ['leave_rule_id'] . " Opening_Bal_". $val ['alias_name'] .",";
  		$availedstmt .="this_month_". $val ['leave_rule_id'] . " Availed_". $val ['alias_name'] . ",";
  		$balancestmt .="FORMAT((total_". $val ['leave_rule_id'] ."-tot_availed_". $val ['leave_rule_id'] .")-(this_month_". $val ['leave_rule_id'] ."),2) Balance_". $val ['alias_name'] .",";
  		$wrkdDays .="this_month_". $val ['leave_rule_id'] ."-";
  		
  		$totstmt .="MAX(CASE WHEN leave_rule_id = '". $val ['leave_rule_id'] ."' THEN total ELSE 0 END) total_". $val ['leave_rule_id'] .",";
  		$thismonthstmt .="IFNULL(MAX(CASE WHEN month_year = '$monthYear' AND leave_rule_id = '". $val ['leave_rule_id'] ."' THEN ". $val ['leave_rule_id'] ." ELSE 0 END),'0') this_month_". $val ['leave_rule_id'] .",";
  		$totAvailedstmt .="SUM(CASE WHEN month_year BETWEEN '$monthstart' AND DATE_SUB('$monthYear',INTERVAL 1 MONTH)  AND leave_rule_id = '". $val ['leave_rule_id'] ."' THEN ". $val ['leave_rule_id'] ." ELSE 0 END) tot_availed_". $val ['leave_rule_id'] .",";
  	}
  	
  	$wrkdDaysstmt ="(Worked_days-".rtrim($wrkdDays,'-').") Worked_Days,(".str_replace('-','+',rtrim($wrkdDays,'-')).") LEAVE_DAYS";
  	$lopstmt = "IFNULL(MAX(CASE WHEN month_year = '$monthYear' THEN lop END),0) LOP,";
  	$workedstmt = "IFNULL(MAX(CASE WHEN month_year = '$monthYear' THEN worked_days END),0) WORKED_DAYS";
  	
  	$casestmt ="SELECT EMPID,NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns))." , MONTH , $openingstmt $availedstmt $balancestmt LOP,$wrkdDaysstmt FROM (";
  	$casestmt1 = "SELECT employee_id EMPID,employee_name NAME,DATE_FORMAT('$monthYear','%b %Y') MONTH ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).", $totstmt $thismonthstmt $totAvailedstmt $lopstmt $workedstmt";
  	$tableQuery ="$casestmt $casestmt1 FROM ( 
				 SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,w.employee_doj $columns, l.leave_rule_id
				        ,l.opening_bal,l.allotted,(l.opening_bal+l.allotted) total,IFNULL(p.month_year,DATE_FORMAT($monthYear,'%b')) month_year,IFNULL(p.lop,0) lop,$leaverulestmt IFNULL(p.worked_days,0) worked_days 
						FROM emp_leave_account l
						INNER JOIN employee_personal_details pd
						ON l.employee_id = pd.employee_id
						INNER JOIN employee_work_details w
						ON l.employee_id = w.employee_id
						INNER JOIN company_departments dp
						ON w.department_id = dp.department_id
						INNER JOIN company_designations ds
						ON w.designation_id = ds.designation_id
						INNER JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						LEFT JOIN company_team ct
						ON w.team_id = ct.team_id
						LEFT JOIN employee_work_details repo
          				ON w.employee_reporting_person = repo.employee_id
						LEFT JOIN company_shifts cs
						ON w.shift_id = cs.shift_id
						LEFT JOIN payroll p
						ON l.employee_id = p.employee_id AND p.month_year BETWEEN '$monthstart' AND '$monthYear'
						WHERE w.enabled=1 AND l.year = '$year' $whereCondition ) a
						GROUP BY employee_id ORDER BY employee_id)t;";
  	  //echo $tableQuery; die();
	  $queryData = $this->getComponentsResult ( $tableQuery );
	  $this->title = 'LEAVE BALANCE REPORT';
	  
	  $headersArr= array();
	  if($queryData[0]!='error'){
	  	$headersArr= array();
	  	foreach($queryData[0] as $header)
	  		array_push($headersArr,array("name"=>$header));
	  		$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
	  				"status" => "success",
	  				"headers" => $headersArr,
	  				"queryData" => $queryData [1],
	  				"fixedColumns" => 2 ,
	  				"isRemoveIndices" =>false,
	  				"isTotal" =>false
	  				
	  				
	  		) ) : array (
	  				"type" => 'table',
	  				"title" => $this->name,
	  				"position" => 'top',
	  				"status" => "error",
	  				"data" => $queryData [1]
	  		);
	  		
	  		return $this->getJsonData ( $this );
	  }else{
	  	return  false;
	  }
  }
  
  function BT005(){
  	$this->name = 'ATTENDANCE PROCESS DATA';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " FROM ".date("j M Y", strtotime($from_date))." TO ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?str_ireplace("SHIFT_NAME","a.SHIFT_NAME",$customFields):'';
  	
  	$tableQuery = "SELECT a.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",DATE_FORMAT(days,'%d/%m/%Y') DATE,
					  	IFNULL(DATE_FORMAT(checkIn,'%H:%i'),'') IN_TIME,IFNULL(DATE_FORMAT(checkOut,'%H:%i'),'') OUT_TIME,a.shift_name SHIFT,a.shift_st_time SHIFT_START_TIME,
					  	shift_end_time SHIFT_END_TIME,SUBSTRING_INDEX(work_hrs,':',2) WORK_HOURS,IF(lateIn='-','',SUBSTRING_INDEX(lateIn,':',2)) LATE_HOURS,IF(earlyOut='-','',SUBSTRING_INDEX(earlyOut,':',2)) EARLY_EXIT_HOURS,
					  	IF(ot='-','',SUBSTRING_INDEX(ot,':',2)) EXCESS_HOURS,
					  	IFNULL((CASE WHEN ab.absent_date = days THEN UPPER(leave_rule_type) END),'') LEAVE_TYPE,
					  	IFNULL((CASE WHEN ab.absent_date = days THEN (CASE WHEN ab.day_type='FH' THEN 'First Half'
								  	 WHEN ab.day_type='SH' THEN 'Second Half'
								  	 WHEN ab.day_type='FD' THEN 'Full Day' END)END),'') LEAVE_SESSION,
					  	IFNULL((CASE WHEN a.day_type IN (1,'FH-0.5','SH-0.5') AND work_hrs ='' THEN 'Weekly Off' 
								  	 WHEN a.day_type IN ('GH','RH') THEN IF(a.day_type='GH','General Holiday','Reserved Holiday')
								  	 WHEN work_hrs>= min_hrs_half_day AND work_hrs< min_hrs_full_day THEN 'Present-Half Day'
								  	 WHEN work_hrs>= min_hrs_full_day THEN 'Present-Full Day'
                     				 WHEN ab.absent_date = days AND ab.day_type='FD' THEN 'Leave' END),'') DAY_STATUS,
                     	IF(SUBSTRING_INDEX(a.day_type,'-',-1) IN('W',0.5),IF(a.day_type='W' AND pay_day<1,1-pay_day,IF(a.day_type!='W' AND SUBSTRING_INDEX(a.day_type,'-',-1)>pay_day,SUBSTRING_INDEX(a.day_type,'-',-1)-pay_day,'')),'') LOST_SALARY
				  	FROM attendance_summary a
				  	INNER JOIN employee_work_details w
				  	ON a.employee_id = w.employee_id
				  	INNER JOIN employee_personal_details pd
				  	ON a.employee_id = pd.employee_id
				  	INNER JOIN company_branch cb
				  	ON w.branch_id = cb.branch_id
				  	INNER JOIN company_designations ds
				  	ON w.designation_id = ds.designation_id
				  	INNER JOIN company_departments dp
				  	ON w.department_id = dp.department_id
				  	LEFT JOIN company_team ct
				  	ON w.team_id = ct.team_id
				  	INNER JOIN company_shifts s
				  	ON a.shift_id = s.shift_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN emp_absences ab
				  	ON a.employee_id = ab.employee_id AND a.days = ab.absent_date -- absent_date BETWEEN '$start_date' AND '$end_date'
				  	WHERE days BETWEEN '$start_date' AND '$end_date' $whereCondition
				  	ORDER BY a.employee_id,days;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'ATTENDANCE PROCESS DATA' .$curenttitle;
  	 
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  						
  						
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  				
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function BT006(){
  	$this->name = 'ATTENDANCE SUMMARY REPORT';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " FROM ".date("j M Y", strtotime($from_date))." TO ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$tableQuery = "SELECT EMPID,NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",MONTH_DAYS,WEEKLY_OFF,HOLIDAYS,(MONTH_DAYS-WEEKLY_OFF-HOLIDAYS) WORKING_DAYS,PRESENT_DAYS,ABSENT_DAYS+LEAVE_DAYS LEAVE_DAYS,
  						  TOTAL_WORKED_HOURS,`WORKED_ON_HOLIDAYS/WOFF`,LATE_HOURS,EARLY_EXIT_HOURS,IFNULL(ROUND((TOTAL_WORKED_HOURS)/PRESENT_DAYS,2),'') /*ROUND(TIME_TO_SEC(TOTAL_WORKED_HOURS)/TIME_TO_SEC(shift_hrs),2)*/ AVG_WORKED_HRS_PER_DAY
				  	FROM (
				  	SELECT EMPID,NAME " .str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",DATEDIFF('$end_date','$start_date')+1 MONTH_DAYS,HOLIDAYS,
				  		SUM(is_weekoff) WEEKLY_OFF,SUM(check_In) PRESENT_DAYS,SUM(absent_days) ABSENT_DAYS,SUM(IFNULL(leave_days,'')) LEAVE_DAYS,
				  		IFNULL(SUBSTRING_INDEX(SEC_TO_TIME( SUM( TIME_TO_SEC(work_hrs) ) ),':',2),'') TOTAL_WORKED_HOURS,
				  		IFNULL(SUBSTRING_INDEX(SEC_TO_TIME( SUM( TIME_TO_SEC(holiday_hrs) ) ),':',2),'') `WORKED_ON_HOLIDAYS/WOFF`,
				  		IFNULL(SUBSTRING_INDEX(SEC_TO_TIME( SUM( TIME_TO_SEC(lateIn) ) ),':',2),'') LATE_HOURS,
				  		IFNULL(SUBSTRING_INDEX(SEC_TO_TIME( SUM( TIME_TO_SEC(earlyOut) ) ),':',2),'') EARLY_EXIT_HOURS,shift_hrs
				  	FROM (
				  	SELECT a.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME ".str_ireplace("SHIFT_NAME","a.SHIFT_NAME",$customFields).",
				  			IFNULL((SELECT SUM(DATEDIFF(end_date,start_date)+1) duration FROM holidays_event WHERE start_date BETWEEN '2017-12-20' AND '2018-01-19'),0) HOLIDAYS,
				  			IF(SUBSTRING_INDEX(a.day_type,'-',1) IN(1,'FH','SH'),SUBSTRING_INDEX(a.day_type,'-',-1),'') is_weekoff,days,
                			IF(checkIn='',0,IF(SUBSTRING_INDEX(a.day_type,'-',1) NOT IN ('FH','SH'),'1','0.5')) check_In,
                			IF(checkOut!='',1,0) checkOut,IF(a.day_type='W' AND checkIn='',1,0) absent_days,work_hrs,IF(late_approved=0,lateIn,'') lateIn,
				  			IF(early_approved=0,earlyOut,'') earlyOut,s.shift_hrs,IF(a.day_type!='W' AND a.day_type!='0.5' AND FIND_IN_SET('-',CONCAT(''',REPLACE(a.day_type,'-','',-,''),'''))=0,work_hrs,'') holiday_hrs
                			,ab.day_count leave_days
				  	FROM attendance_summary a
				  	INNER JOIN employee_work_details w
				  	ON a.employee_id = w.employee_id
				  	INNER JOIN employee_personal_details pd
				  	ON a.employee_id = pd.employee_id
				  	INNER JOIN company_branch cb
				  	ON w.branch_id = cb.branch_id
				  	INNER JOIN company_designations ds
				  	ON w.designation_id = ds.designation_id
				  	INNER JOIN company_departments dp
				  	ON w.department_id = dp.department_id
				  	LEFT JOIN company_team ct
				  	ON w.team_id = ct.team_id
				  	INNER JOIN company_shifts s
				  	ON a.shift_id = s.shift_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN emp_absences ab
				  	ON a.employee_id = ab.employee_id AND a.days = ab.absent_date -- absent_date BETWEEN '$start_date' AND '$end_date'
				  	WHERE days BETWEEN '$start_date' AND '$end_date' $whereCondition
				  	ORDER BY a.employee_id,days)z
				  	GROUP BY EMPID ORDER BY EMPID)q;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'ATTENDANCE SUMMARY REPORT' .$curenttitle;
  	 
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  						
  						
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  				
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function BT007(){
  	$this->name = 'NO PUNCH REPORT';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " FROM ".date("j M Y", strtotime($from_date))." TO ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields = $this->reportParams ['isCustom'];
  	$customFields= ",".str_replace("^"," ",$customFields);
  	$columns = ($this->reportParams ['isCustom']!='')?str_ireplace("SHIFT_NAME","a.SHIFT_NAME",$customFields):'';
  	
  	$tableQuery = "SELECT a.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",DATE_FORMAT(days,'%d-%b-%Y') DATE,
  						 (CASE WHEN a.day_type IN (1,0.5) THEN 'Weekly Off' 
					           WHEN a.day_type IN ('GH','RH') THEN IF(a.day_type='GH','General Holiday','Reserved Holiday') 
					           WHEN SUBSTRING_INDEX(a.day_type,'-',1)='W' OR a.day_type ='' THEN 'No Punch'
            				   WHEN SUBSTRING_INDEX(a.day_type,'-',-1) IS NOT NULL THEN CONCAT('Leave-',UPPER(SUBSTRING_INDEX(a.day_type,'-',1))) END) DAY_STATUS 
			  	FROM attendance_summary a
			  	INNER JOIN employee_work_details w
			  	ON a.employee_id = w.employee_id
			  	INNER JOIN employee_personal_details pd
			  	ON a.employee_id = pd.employee_id
			  	INNER JOIN company_branch cb
			  	ON w.branch_id = cb.branch_id
			  	INNER JOIN company_designations ds
			  	ON w.designation_id = ds.designation_id
			  	INNER JOIN company_departments dp
			  	ON w.department_id = dp.department_id
			  	LEFT JOIN company_team ct
			  	ON w.team_id = ct.team_id
			  	INNER JOIN company_shifts s
			  	ON a.shift_id = s.shift_id
				LEFT JOIN employee_work_details repo
          		ON w.employee_reporting_person = repo.employee_id
				-- LEFT JOIN emp_absences ab
			  	-- ON a.employee_id = ab.employee_id AND absent_date BETWEEN '$start_date' AND '$end_date'
			  	WHERE days BETWEEN '$start_date' AND '$end_date' AND checkIn='' AND checkOut='' $whereCondition
			  	ORDER BY a.employee_id,days;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'NO PUNCH REPORT' .$curenttitle;
  	 
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  						
  						
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  				
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function ER005(){
  	//In this function we are going to generate employee wise attendance report
  	$this->name = 'APPROVAL ROUTING DETAIL REPORT';
  	$curenttitle = "";
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$tableQuery ="SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",DATE_FORMAT(w.employee_doj,'%d-%b-%Y') DOJ,IFNULL(CONCAT(wd.employee_name,' ',wd.employee_lastname),'') APPROVER_1,
  						 IF(w.employee_reporting_person='NA','',w.employee_reporting_person) APPROVER_1_EMPCODE
			  	FROM employee_work_details w
			  	LEFT JOIN employee_work_details wd
			  	ON w.employee_reporting_person=wd.employee_id
			  	INNER JOIN employee_personal_details pd
			  	ON w.employee_id=pd.employee_id
			  	INNER JOIN company_branch cb
			  	ON w.branch_id = cb.branch_id
			  	INNER JOIN company_designations ds
			  	ON w.designation_id = ds.designation_id
			  	INNER JOIN company_departments dp
			  	ON w.department_id = dp.department_id
			  	LEFT JOIN company_team ct
			  	ON w.team_id = ct.team_id
				LEFT JOIN employee_work_details repo
          		ON w.employee_reporting_person = repo.employee_id
				LEFT JOIN company_shifts cs
				ON w.shift_id = cs.shift_id
			  	WHERE w.enabled=1 $whereCondition
			  	ORDER BY w.employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'APPROVAL ROUTING DETAIL REPORT';
  	 
  	$headersArr= array();
  	foreach($queryData[0] as $header)
  		array_push($headersArr,array("name"=>$header));
  		$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', 'APPROVAL ROUTING DETAIL REPORT ', 'top', array (
  				"status" => "success",
  				"headers" => $headersArr,
  				"queryData" => $queryData [1],
  				"fixedColumns" => 2 ,
  				"isRemoveIndices" =>true,
  				"isTotal" =>false
  
  
  		) ) : array (
  				"type" => 'table',
  				"title" => 'APPROVAL ROUTING DETAIL REPORT',
  				"position" => 'top',
  				"status" => "error",
  				"data" => $queryData [1]
  		);
  		return $this->getJsonData ( $this );
  }
  
  function EL004(){
  	$this->name = 'SEPERATION REPORT';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " From ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));

  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$tableQuery = "SELECT DISTINCT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",ds.designation_name DESIGNATION,DATE_FORMAT(w.employee_doj,'%d/%m/%Y') DOJ,
					      DATE_FORMAT(np.last_working_date,'%d/%m/%Y') DOR,DATE_FORMAT(np.notice_date,'%d/%m/%Y') SEPERATION_NOTICE_DATE,DATE_FORMAT(np.last_working_date,'%d/%m/%Y') ACTUAL_RELIEVING_DATE,er.reason_code SEPERATION_TYPE,
					  	  TIMESTAMPDIFF(MONTH,w.employee_doj,np.last_working_date) EXPERIENCE_IN_MONTHS,s.employee_salary_amount SALARY,np.remark SEPERATION_REASON,
					  	  (CASE WHEN np.process_type='S' THEN 'Processed' ELSE 'Not Processed' END) SETTLEMENT_STATUS,
					  	  (CASE WHEN np.status='A' THEN 'In Notice' WHEN np.status='S' THEN 'Relieved' END) SEPERATION_STATUS
			  	FROM employee_work_details w
			  	INNER JOIN employee_personal_details p
			  	ON w.employee_id = p.employee_id
			  	INNER JOIN employee_salary_details s
			  	ON w.employee_id = s.employee_id
			  	INNER JOIN company_branch cb
			  	ON w.branch_id = cb.branch_id
			  	INNER JOIN company_designations ds
			  	ON w.designation_id = ds.designation_id
			  	INNER JOIN company_departments dp
			  	ON w.department_id = dp.department_id
			  	LEFT JOIN company_team ct
			  	ON w.team_id = ct.team_id
				LEFT JOIN employee_work_details repo
          		ON w.employee_reporting_person = repo.employee_id
				LEFT JOIN company_shifts cs
				ON w.shift_id = cs.shift_id
			  	INNER JOIN emp_notice_period np
			  	ON w.employee_id = np.employee_id
			  	LEFT JOIN exit_reasons er
			  	ON np.reason = er.exit_id
			  	WHERE np.status='S' AND np.last_working_date BETWEEN '$start_date' AND '$end_date' $whereCondition
			  	ORDER BY np.last_working_date";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'SEPERATION REPORT' .$curenttitle;
  	 
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  						
  						
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  				
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function AT006(){
  	$this->name = 'LEAVE SUMMARY REPORT';
  
  	$monyear = explode(' ',($this->reportParams ['monYear']));
  	$monthYear = $monyear[1]."-".$monyear[0]."-01";
  	 
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$stmt = mysqli_query ( $this->conn, "SELECT IF(leave_based_on = 'finYear','FY','CY') leave_based_on,attendance_period_sdate FROM company_details WHERE info_flag='A';" );
  	$result = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
  	$leave_based = $result['leave_based_on'];
  	$attn_stDate = $result['attendance_period_sdate'];
  	 
  	if($leave_based=='CY'){
  		$monthstart = $monyear[1]."-01-01";
  		$year = $monyear[1];
  	}else{
  		$monthstart = $monyear[1]."-04-01";
  		$year1 =substr($monyear[1],2)+1;
  		$year =$monyear[1].$year1;
  	}
  	 
  	if($attn_stDate !=1){
  		$from_dt = $monyear[1]."-".(($monyear[0])-1)."-".$attn_stDate;
  		$end_dt = $monyear[1]."-".$monyear[0]."-".($attn_stDate-1);
  	}else{
  		$from_dt = $monyear[1]."-".$monyear[0]."-".$attn_stDate;
  		$end_dt = date('Y-m-t',strtotime($from_dt));
  	}
  	 
  	$leaveRule = array();
  	$stmt=("SELECT l.leave_rule_id,l.rule_name,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
  	$query = mysqli_query ( $this->conn, $stmt );
  	while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
  		array_push ( $leaveRule, $row );
  	}
  	 
  	$leaverulestmt = $openingstmt = $availedstmt = $balancestmt = $wrkdDays = $totstmt= $thismonthstmt = $totAvailedstmt ="";
  	foreach ( $leaveRule as $key => $val ){
  		$leaverulestmt .="IFNULL(p.". $val ['leave_rule_id'] .",0)". $val ['leave_rule_id'] .",";
  		$openingstmt .="total_". $val ['leave_rule_id'] ."-tot_availed_". $val ['leave_rule_id'] . " Opening_Bal_". $val ['alias_name'] .",";
  		$availedstmt .="this_month_". $val ['leave_rule_id'] . " Availed_". $val ['alias_name'] . ",";
  		$balancestmt .="FORMAT((total_". $val ['leave_rule_id'] ."-tot_availed_". $val ['leave_rule_id'] .")-(this_month_". $val ['leave_rule_id'] ."),2) Balance_". $val ['alias_name'] .",";
  		$wrkdDays .="this_month_". $val ['leave_rule_id'] ."-";
  
  		$totstmt .="MAX(CASE WHEN leave_rule_id = '". $val ['leave_rule_id'] ."' THEN total ELSE 0 END) total_". $val ['leave_rule_id'] .",";
  		$thismonthstmt .="IFNULL(MAX(CASE WHEN month_year = '$monthYear' AND leave_rule_id = '". $val ['leave_rule_id'] ."' THEN ". $val ['leave_rule_id'] ." ELSE 0 END),'0') this_month_". $val ['leave_rule_id'] .",";
  		$totAvailedstmt .="SUM(CASE WHEN month_year BETWEEN '$monthstart' AND DATE_SUB('$monthYear',INTERVAL 1 MONTH)  AND leave_rule_id = '". $val ['leave_rule_id'] ."' THEN ". $val ['leave_rule_id'] ." ELSE 0 END) tot_availed_". $val ['leave_rule_id'] .",";
  	}
  	 
  	$wrkdDaysstmt ="(Worked_days-".rtrim($wrkdDays,'-').") Worked_Days,(".str_replace('-','+',rtrim($wrkdDays,'-')).") LEAVE_DAYS";
  	$lopstmt = "IFNULL(MAX(CASE WHEN month_year = '$monthYear' THEN lop END),0) LOP,";
  	$workedstmt = "IFNULL(MAX(CASE WHEN month_year = '$monthYear' THEN worked_days END),0) WORKED_DAYS";
  	 
  	$casestmt ="SELECT EMPID,NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",MONTH,$openingstmt $availedstmt $balancestmt LOP,
  	((DATEDIFF('$end_dt','$from_dt')+1)-LOP) EARNED_DAYS,IFNULL(LATE_HOURS,'') LATE_MIN,IFNULL(EARLY_EXIT_HOURS,'') EARLY_EXIT_MIN,
  	IFNULL(ADDTIME(LATE_HOURS,EARLY_EXIT_HOURS),'') `TOTAL_LATE & EARLY_EXIT`,IFNULL(TOTAL_WORKED_HOURS,'') TOTAL_WORKED_HOURS FROM (";
  	 
  	$casestmt1 = "SELECT employee_id EMPID,employee_name NAME ".str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns).",DATE_FORMAT('$monthYear','%b %Y') MONTH,$totstmt $thismonthstmt $totAvailedstmt $lopstmt $workedstmt";
  	 
  	$tableQuery ="$casestmt $casestmt1 FROM (
  	SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name $columns,w.employee_doj,l.leave_rule_id
  	,l.opening_bal,l.allotted,(l.opening_bal+l.allotted) total,IFNULL(p.month_year,DATE_FORMAT($monthYear,'%b')) month_year,IFNULL(p.lop,0) lop,$leaverulestmt IFNULL(p.worked_days,0) worked_days
  	FROM emp_leave_account l
  	INNER JOIN employee_work_details w
  	ON l.employee_id = w.employee_id
  	INNER JOIN employee_personal_details pd
  	ON l.employee_id = pd.employee_id
  	INNER JOIN company_departments dp
  	ON w.department_id = dp.department_id
  	INNER JOIN company_designations ds
  	ON w.designation_id = ds.designation_id
  	INNER JOIN company_branch cb
  	ON w.branch_id = cb.branch_id
  	LEFT JOIN company_team ct
  	ON w.team_id = ct.team_id
	LEFT JOIN company_shifts s
	ON w.shift_id = s.shift_id
	LEFT JOIN employee_work_details repo
    ON w.employee_reporting_person = repo.employee_id
  	LEFT JOIN payroll p
  	ON l.employee_id = p.employee_id AND p.month_year BETWEEN '$monthstart' AND '$monthYear'
  	WHERE w.enabled=1 AND l.year = '$year' $whereCondition ) a
  	GROUP BY employee_id ORDER BY employee_id)t
  	LEFT JOIN
  		(SELECT EMPID EMP_ID,NAME EMP_NAME,MONTH_DAYS,WEEKLY_OFF,HOLIDAYS,(MONTH_DAYS-WEEKLY_OFF-HOLIDAYS) WORKING_DAYS,PRESENT_DAYS,((MONTH_DAYS-WEEKLY_OFF-HOLIDAYS)-PRESENT_DAYS) LEAVE_DAYS,
  			    TOTAL_WORKED_HOURS,`WORKED_ON_HOLIDAYS/WOFF`,LATE_HOURS,EARLY_EXIT_HOURS,ROUND(TIME_TO_SEC(TOTAL_WORKED_HOURS)/TIME_TO_SEC(shift_hrs),2) AVG_WORKED_HRS_PER_DAY
  	    FROM (
  		SELECT EMPID,NAME,DATEDIFF('$end_dt','$from_dt')+1 MONTH_DAYS,HOLIDAYS,
  			   SUM(is_weekoff) WEEKLY_OFF,SUM(checkIn) PRESENT_DAYS,
  			   IFNULL(SEC_TO_TIME( SUM( TIME_TO_SEC(work_hrs) ) ),'') TOTAL_WORKED_HOURS,
  	           IFNULL(SEC_TO_TIME( SUM( TIME_TO_SEC(holiday_hrs) ) ),'') `WORKED_ON_HOLIDAYS/WOFF`,
  	           IFNULL(SEC_TO_TIME( SUM( TIME_TO_SEC(lateIn) ) ),'') LATE_HOURS,
  	           IFNULL(SEC_TO_TIME( SUM( TIME_TO_SEC(earlyOut) ) ),'') EARLY_EXIT_HOURS,shift_hrs
  		FROM (
  		SELECT a.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME,
  			   IFNULL((SELECT (DATEDIFF(end_date,start_date)+1) duration FROM holidays_event WHERE start_date BETWEEN '$from_dt' AND '$end_dt'),0) HOLIDAYS,
  			   IF(a.day_type IN(1,0.5),a.day_type,'') is_weekoff,days,IF(checkIn!='',1,0) checkIn,IF(checkOut!='',1,0) checkOut,work_hrs,IF(late_approved=0,lateIn,'') lateIn,
  	           IF(early_approved=0,earlyOut,'') earlyOut,s.shift_hrs,IF(a.day_type IN(1,0.5,'GH','RH'),work_hrs,'') holiday_hrs
	  	FROM attendance_summary a
	  	INNER JOIN employee_work_details w
	  	ON a.employee_id = w.employee_id
	  	INNER JOIN company_branch cb
	  	ON w.branch_id = cb.branch_id
	  	INNER JOIN company_designations ds
	  	ON w.designation_id = ds.designation_id
	  	INNER JOIN company_departments dp
	  	ON w.department_id = dp.department_id
	  	LEFT JOIN company_team ct
	  	ON w.team_id = ct.team_id
	  	INNER JOIN company_shifts s
	  	ON a.shift_id = s.shift_id
		LEFT JOIN employee_work_details repo
        ON w.employee_reporting_person = repo.employee_id
		LEFT JOIN emp_absences ab
	  	ON a.employee_id = ab.employee_id AND absent_date BETWEEN '$from_dt' AND '$end_dt'
	  	WHERE days BETWEEN '$from_dt' AND '$end_dt' $whereCondition
	  	ORDER BY a.employee_id,days)z
	  	GROUP BY EMPID ORDER BY EMPID)q )r
	  	ON t.EMPID = r.EMP_ID;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'LEAVE SUMMARY REPORT ';
  	 
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>false,
  					"isTotal" =>false
  						
  						
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  				
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }

  function HR003(){
  	$reportType = $this->reportParams ['reportType'];
  	$year = $this->reportParams ['year'];
  	
  	if($reportType == 'count')
  		$this->name = $this->title = 'TEAM WISE HEADCOUNT REPORT';
  	else
  		$this->name = $this->title = 'TEAM MEMBERS DETAILS';
  		//$curenttitle = " From ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));

    $teams = array();
  	$stmt=("SELECT team_id,REPLACE(team_name,' ','_') team_name FROM company_team WHERE enabled=1;" );
  	$query = mysqli_query ( $this->conn, $stmt );
  	while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
  		array_push ( $teams, $row );
  	}
  	//print_r($teams[0]);
  	$st_dt = $mon_st = $year."-01-01";
  	$loop_end = $year."-12-01";
  	$loopstmt ="";
  			 
  	foreach ( $teams as $key => $val ){
  		$begin = new DateTime( $st_dt);
  		$end   = new DateTime( $loop_end);
  		$loopstmt .= "IFNULL(MAX(CASE ";
  			for($i = $begin; $i <= $end; $i->modify('+1 month')){
  				if($reportType == 'count')
  					$loopstmt .="WHEN month_year='".$i->format("Y-m-d")."' AND team_id='". $val ['team_id'] ."' THEN emp_count ";
  				else
  					$loopstmt .="WHEN month_year='".$i->format("Y-m-d")."' AND team_id='". $val ['team_id'] ."' THEN team_members ";
  			}
  			$loopstmt .= "END),0) '" . $val ['team_name'] ."',";
  		}
  			 
  	if($reportType == 'count'){
  		$cond = "COUNT(p.team_id) emp_count";
  		$groupCond = "GROUP BY p.team_id,month_year ORDER BY ct.team_name,month_year";
  	}else{
  		//$cond = "GROUP_CONCAT(w.employee_name ORDER BY p.employee_id) team_members";
  		$cond = "REPLACE(GROUP_CONCAT(w.employee_name,'<br>' ORDER BY p.employee_id),',','') team_members";
  		$groupCond = "GROUP BY p.team_id,month_year ORDER BY p.team_id,p.month_year";
  	}
  	$tableQuery = "SELECT DATE_FORMAT(month_year,'%b-%y') `TEAM_NAME/MONTH`,".substr ($loopstmt, 0, - 1 ).
  			"FROM (
  			SELECT p.month_year,p.team_id,ct.team_name,$cond
  			FROM payroll p
  			INNER JOIN company_team ct
  			ON p.team_id = ct.team_id
  			INNER JOIN employee_work_details w
  			ON p.employee_id = w.employee_id
  			INNER JOIN company_branch cb
  			ON w.branch_id = cb.branch_id
  			INNER JOIN company_designations ds
  			ON w.designation_id = ds.designation_id
  			INNER JOIN company_departments dp
  			ON w.department_id = dp.department_id
			LEFT JOIN employee_work_details repo
          	ON w.employee_reporting_person = repo.employee_id
			LEFT JOIN company_shifts cs
			ON w.shift_id = cs.shift_id
  			WHERE w.enabled=1 AND DATE_FORMAT(p.month_year ,'%Y')='$year' $whereCondition
  			$groupCond
  			)t GROUP BY month_year;";
 	$queryData = $this->getComponentsResult ( $tableQuery );
  			 
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  			"status" => "success",
  			"headers" => $headersArr,
  			"queryData" => $queryData [1],
  			"fixedColumns" => 1 ,
  			"isRemoveIndices" =>true,
  			"isTotal" =>false
  							 
  							 
  			) ) : array (
  			"type" => 'table',
  			"title" => $this->name,
  			"position" => 'top',
  			"status" => "error",
  			"data" => $queryData [1]
  			);
  					 
  	return $this->getJsonData ( $this );
  	}else{
  				return  false;
  	}
  }
  
  function ER008(){
  	$this->name = 'PREVIOUS EMPLOYMENT REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	
  	$tableQuery = "SELECT wh.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME,company_name EMPLOYER_NAME,IFNULL(location,'') LOCATION,IFNULL(designation,'') DESIGNATION,
					      DATE_FORMAT(STR_TO_DATE(`from`,'%d/%m/%Y'),'%d-%b-%Y') DOJ,DATE_FORMAT(STR_TO_DATE(`to`,'%d/%m/%Y'),'%d-%b-%Y') LEAVING_DATE,
					      CONCAT(DATE_FORMAT(STR_TO_DATE(`from`,'%d/%m/%Y'),'%Y'),' - ',DATE_FORMAT(STR_TO_DATE(`to`,'%d/%m/%Y'),'%Y')) DURATION,
						  IFNULL(ctc,'') SALARY,IFNULL(contact_email,'') EMPLOYER_MAIL,IFNULL(prev_reporting_manager,'') REPORTING_MANAGER
					FROM emp_work_history wh
					INNER JOIN employee_work_details w
					ON wh.employee_id = w.employee_id
					INNER JOIN company_branch cb
		  			ON w.branch_id = cb.branch_id
		  			INNER JOIN company_designations ds
		  			ON w.designation_id = ds.designation_id
		  			INNER JOIN company_departments dp
		  			ON w.department_id = dp.department_id
					INNER JOIN company_team ct
  					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
		  			WHERE w.enabled=1 $whereCondition
					ORDER BY wh.employee_id,`from` DESC;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'PREVIOUS EMPLOYMENT REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function HR004(){
  	$this->name = 'VARIANCE IN SALARY REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$pastmonth_miscpay = $currentmonth_miscpay = $pastmonth_miscded = $currentmonth_miscded = $miscpaystmt = $miscdedstmt = "";
  	if(count($this->miscallowDeducArray ["MP"])>0) {
  		foreach ( $this->miscallowDeducArray ["MP"] as $key => $val ) {
  			$pastmonth_miscpay.= "p." . $val ['pay_structure_id']."+";
  			$currentmonth_miscpay.= "pt." . $val ['pay_structure_id']."+";
  		}
  	 $miscpaystmt = ",(".substr($pastmonth_miscpay,0,-1).") past_misc_pay ,(".substr($currentmonth_miscpay,0,-1).") current_misc_pay";
  	}
  	
  	if(count($this->miscallowDeducArray ["MD"])>0) {
  		foreach ( $this->miscallowDeducArray ["MD"] as $key => $val ) {
  			$pastmonth_miscded.= "p." . $val ['pay_structure_id']."+";
  			$currentmonth_miscded.= "pt." . $val ['pay_structure_id']."+";
  		}
  	 $miscdedstmt = ",(".substr($pastmonth_miscded,0,-1).") past_misc_ded ,(".substr($currentmonth_miscded,0,-1).") current_misc_ded";
  	}
  	
  	
  	$tableQuery = "SELECT EMPID,NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",str_ireplace("IFNULL(repo.EMPLOYEE_NAME,'')REPORTING_MANAGER","REPORTING_MANAGER",$columns)).",PAST_GROSS,CURRENT_GROSS,GROSS_VARIANCE,
					      (CASE WHEN effects_from = '".$_SESSION ['current_payroll_month']."' THEN 'Increment'
					            WHEN (past_lop !=0 OR current_lop !=0) AND (past_llop !=0 OR current_llop !=0) AND (past_misc_pay !=0 OR current_misc_pay !=0 ) AND (past_misc_ded !=0 OR current_misc_ded !=0) THEN 'LOP,Late LOP,Misc Pay,Misc Ded'
					            WHEN (past_lop !=0 OR current_lop !=0) AND (past_llop !=0 OR current_llop !=0) AND (past_misc_pay !=0 OR current_misc_pay !=0 ) THEN 'LOP,Late LOP,Misc Pay'
					            WHEN (past_lop !=0 OR current_lop !=0) AND (past_misc_pay !=0 OR current_misc_pay !=0 ) AND (past_misc_ded !=0 OR current_misc_ded !=0 ) THEN 'LOP,Misc Pay,Misc Ded'
					            WHEN (past_llop !=0 OR current_llop !=0) AND (past_misc_pay !=0 OR current_misc_pay !=0 ) AND (past_misc_ded !=0 OR current_misc_ded !=0 ) THEN 'Late LOP,Misc Pay,Misc Ded'
					            WHEN (past_lop !=0 OR current_lop !=0) AND (past_llop !=0 OR current_llop !=0) AND (past_misc_ded !=0 OR current_misc_ded !=0) THEN 'LOP,Late LOP,Misc Ded'
					            WHEN (past_lop !=0 OR current_lop !=0) AND (past_llop !=0 OR current_llop !=0) THEN 'LOP,Late LOP'
					            WHEN (past_lop !=0 OR current_lop !=0) AND (past_misc_pay !=0 OR current_misc_pay !=0) THEN 'LOP,Misc Pay'
					            WHEN (past_lop !=0 OR current_lop !=0) AND (past_misc_ded !=0 OR current_misc_ded !=0) THEN 'LOP,Misc Ded'
					            WHEN (past_llop !=0 OR current_llop !=0) AND (past_misc_pay !=0 OR current_misc_pay !=0 ) THEN 'Late LOP,Misc Pay'
					            WHEN (past_llop !=0 OR current_llop !=0) AND (past_misc_ded !=0 OR current_misc_ded !=0 ) THEN 'Late LOP,Misc Ded'
					            WHEN (past_misc_pay !=0 OR current_misc_pay !=0) AND (past_misc_ded !=0 OR current_misc_ded !=0 ) THEN 'Misc Pay,Misc Ded'
					            WHEN past_lop !=0 OR current_lop !=0 THEN 'LOP'
					            WHEN past_llop !=0 OR current_llop !=0 THEN 'Late LOP'
					            WHEN past_misc_pay !=0 OR current_misc_pay !=0 THEN 'Misc Pay'
					            WHEN past_misc_ded !=0 OR current_misc_ded !=0 THEN 'Misc Ded'
					      ELSE '' END) REASON_FOR_VARIANCE
					FROM (
					SELECT DISTINCT w.employee_id EMPID,w.employee_name NAME,p.gross_salary PAST_GROSS,pt.gross_salary CURRENT_GROSS,
					       IF(p.gross_salary>pt.gross_salary,p.gross_salary - pt.gross_salary,pt.gross_salary - p.gross_salary) GROSS_VARIANCE,
					       p.lop past_lop,pt.lop current_lop,p.late_lop past_llop,pt.late_lop current_llop,sal.effects_from
					       $columns $miscpaystmt $miscdedstmt 
					FROM payroll p
					INNER JOIN payroll_preview_temp pt
					ON p.employee_id = pt.employee_id
					INNER JOIN employee_work_details w
					ON p.employee_id = w.employee_id
					INNER JOIN employee_personal_details pd
				  	ON w.employee_id = pd.employee_id
					INNER JOIN employee_salary_details sal
					ON w.employee_id = sal.employee_id
					LEFT JOIN employee_salary_details_history sh
					ON sal.employee_id = sh.employee_id AND sh.effects_upto = DATE_SUB(sal.effects_from,INTERVAL 1 DAY)
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					INNER JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					INNER JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
					WHERE w.enabled=1 $whereCondition
					AND p.month_year=DATE_SUB('".$_SESSION ['current_payroll_month']."',INTERVAL 1 MONTH) AND p.gross_salary != pt.gross_salary
					AND pt.gross_salary !=0 ) t
					WHERE gross_variance > 0 ;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'VARIANCE IN SALARY REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  function EL001(){
  	$this->name = 'INCREMENT REPORT';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " From ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$tableQuery = "SELECT DISTINCT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",'Increment' MOVEMENT_TYPE,DATE_FORMAT(action_effects_from,'%d-%m-%Y') PROMOTION_EFFECTIVE_DATE,ROUND(salHis.employee_salary_amount) FROM_SALARY,
       					  ROUND(salHis.employee_salary_amount+salHis.employee_salary_amount*SUBSTRING_INDEX(pro.incremented_amount,'|P',1)/100) TO_SALARY
				  	FROM comp_promotions_increments pro
					INNER JOIN employee_work_details w
					ON pro.affected_ids = w.employee_id
					INNER JOIN employee_personal_details p
			  		ON w.employee_id = p.employee_id
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					INNER JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					INNER JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
					LEFT JOIN employee_salary_details s
					ON pro.affected_ids = s.employee_id
					LEFT JOIN employee_salary_details_history salHis
					ON pro.affected_ids = salHis.employee_id AND pro.action_effects_from = DATE_ADD(salHis.effects_upto,INTERVAL 1 DAY)
					WHERE promoted_desig_id ='NA' AND pro.incremented_amount !='0|A' AND pro.action_effects_from BETWEEN '$start_date' AND '$end_date' $whereCondition
					ORDER BY pro.action_effects_from,pro.affected_ids;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'INCREMENT REPORT' .$curenttitle;
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  function EL002(){
  	$this->name = 'PROMOTION REPORT';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " From ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$tableQuery = "SELECT DISTINCT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",'Promotion' MOVEMENT_TYPE,DATE_FORMAT(action_effects_from,'%d-%m-%Y') PROMOTION_EFFECTIVE_DATE,
       					  des.designation_name FROM_DESIGNATION,ds.designation_name TO_DESIGNATION
					FROM comp_promotions_increments pro
					INNER JOIN employee_work_details w
					ON pro.affected_ids = w.employee_id
					INNER JOIN employee_personal_details p
			  		ON w.employee_id = p.employee_id
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					INNER JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
					INNER JOIN company_designations ds
					ON pro.promoted_desig_id = ds.designation_id
					LEFT JOIN emp_designation_history desHis
					ON pro.action_effects_from = DATE_ADD(desHis.effects_upto,INTERVAL 1 DAY)
					LEFT JOIN company_designations des
					ON desHis.designation_id = des.designation_id
				  	WHERE promoted_desig_id !='NA' AND pro.action_effects_from BETWEEN '$start_date' AND '$end_date' $whereCondition
				  	ORDER BY pro.action_effects_from;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'PROMOTION REPORT' .$curenttitle;
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  function EL003(){
  	$this->name = 'TRANSFER REPORT';
  	$start_date = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	$curenttitle = " From ".date("j M Y", strtotime($from_date))." to ".date("j M Y", strtotime($end_date));
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	$columns = str_ireplace("TEAM_NAME","ct.TEAM_NAME",$columns);
  	
  	$tableQuery = "SELECT DISTINCT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME " .str_ireplace("EMPLOYEE_DOB","DATE_FORMAT(EMPLOYEE_DOB,'%d-%b-%Y') EMPLOYEE_DOB",$columns).",'Transfer' MOVEMENT_TYPE,DATE_FORMAT(tr.action_effects_from,'%d-%m-%Y') TRANSFER_EFFECTIVE_DATE,
       					  IFNULL(IF(tr.is_teamTrans=1,oldt.team_name,oldbr.branch_name),'') TRANSFER_FROM,IF(tr.is_teamTrans=1,ct.team_name,newbr.branch_name) TRANSFERED_TO
  					FROM comp_transfers tr
					INNER JOIN employee_work_details w
					ON tr.affected_ids = w.employee_id
					INNER JOIN employee_personal_details p
			  		ON w.employee_id = p.employee_id
					INNER JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					LEFT JOIN company_team ct
					ON tr.transferred_branch_id = ct.team_id
					LEFT JOIN emp_team_history th
					ON tr.affected_ids = th.employee_id AND tr.action_effects_from = DATE_ADD(th.effects_upto,INTERVAL 1 DAY)
					LEFT JOIN company_team oldt
					ON th.team_id = oldt.team_id
					LEFT JOIN company_branch newbr
					ON tr.transferred_branch_id = newbr.branch_id
					LEFT JOIN emp_branch_history brHis
					ON w.employee_id = brHis.employee_id AND tr.action_effects_from = DATE_ADD(brHis.effects_upto,INTERVAL 1 DAY) 
					LEFT JOIN company_branch oldbr
					ON brHis.branch_id = oldbr.branch_id
					LEFT JOIN employee_work_details repo
          			ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id
  					WHERE tr.action_effects_from BETWEEN '$start_date' AND '$end_date' $whereCondition
  					ORDER BY tr.action_effects_from;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'TRANSFER REPORT' .$curenttitle;
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name.$curenttitle, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  function ER009(){
  	$this->name = 'EMPLOYEE EDUCATION REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?$customFields:'';
  	
  	$tableQuery = "SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME".$columns .",IF(p.emp_sslc_school!='Nil',p.emp_sslc_school,'') SSLC_SCHOOL,IF(p.emp_sslc_board!='Nil',p.emp_sslc_board,'') SSLC_BOARD,
				       IF(p.emp_hsc_school!='Nil',p.emp_hsc_school,'') HSC_SCHOOL,IF(p.emp_hsc_board!='Nil',p.emp_hsc_board,'') HSC_BOARD,CONCAT(IF(p.emp_ug_degree!='Nil',p.emp_ug_degree,''),IFNULL(CONCAT('-',emp_ug_major_subject),'')) UG_DEGREE,
				       IF(p.emp_ug_institute_name!='Nil',p.emp_ug_institute_name,'') UG_INSTITUTE,CONCAT(IFNULL(CONCAT('-',emp_pg_degree),''),' ',IFNULL(emp_pg_major_subject,'')) PG_DEGREE,IF(p.emp_pg_institute_name!='Nil',p.emp_pg_institute_name,'') PG_INSTITUTE
				FROM employee_work_details w 
				INNER JOIN employee_personal_details p
				ON w.employee_id = p.employee_id
				INNER JOIN company_designations ds
				ON w.designation_id = ds.designation_id
				INNER JOIN company_departments dp
				ON w.department_id = dp.department_id
				INNER JOIN company_branch cb
				ON w.branch_id = cb.branch_id
				LEFT JOIN company_team ct
				ON w.team_id = ct.team_id
				LEFT JOIN employee_work_details repo
				ON w.employee_reporting_person = repo.employee_id
				LEFT JOIN company_shifts cs
				ON w.shift_id = cs.shift_id          
				WHERE w.enabled=1 $whereCondition
				ORDER BY w.employee_id;";
 	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'EMPLOYEE EDUCATION REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  function HR002(){
  	$this->name = 'ATTRITION ANALYSIS REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?str_ireplace("SHIFT_NAME","IFNULL(SHIFT_NAME,'') SHIFT_NAME",$customFields):'';
  	
  	$tableQuery = "SELECT w.employee_id EMPID,CONCAT(w.employee_name,' ',w.employee_lastname) NAME".$columns.",ds.designation_name DESIGNATION,employee_salary_amount SALARY,
					      (CASE WHEN w.enabled=1 THEN 'Onboard' WHEN w.enabled =0 AND np.status IS NULL THEN 'Resigned' WHEN w.enabled=0 AND np.status='S' THEN re.reason_code ELSE '' END) STATUS,DATE_FORMAT(w.employee_doj,'%d-%b-%y') DATE_OF_JOINING,
					      DATE_FORMAT(w.employee_doj,'%b') MONTH_OF_JOINING,DATE_FORMAT(w.employee_doj,'%Y') YEAR_OF_JOINIG,DATE_FORMAT(w.employee_doj,'%d') DOJ_MONTH_IN_NOS,
					      -- IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Years'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Year'))) YEARS_OF_EXP,
					      IF(w.enabled=1,ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),ROUND((DATEDIFF(w.dateofexit,w.employee_doj)/365),1)) YEAR_EXP,
					      IF(w.enabled=1,TIMESTAMPDIFF(MONTH,w.employee_doj,NOW()),TIMESTAMPDIFF(MONTH,w.employee_doj,w.dateofexit)) EXP_IN_MONTHS,IFNULL(DATE_FORMAT(w.dateofexit,'%d-%b-%Y'),'') SEPERATION_DATE,IFNULL(DATE_FORMAT(w.dateofexit,'%b'),'') MONTH_OF_SEPERATION,
					      IFNULL(DATE_FORMAT(IF(w.dateofexit!='0000-00-00',w.dateofexit,''),'%Y'),'') YEAR_OF_SEPERATION,IF(p.employee_gender='Male','M','F') GENDER,
					      IFNULL(p.emp_pg_degree,IFNULL(p.emp_ug_degree,'')) ACADEMIC_QUALIFICATION,IFNULL(p.emp_pg_major_subject,IFNULL(p.emp_ug_major_subject,'')) ACADEMIC_SPECIFICATION
					FROM employee_work_details w
					INNER JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					INNER JOIN employee_salary_details s
					ON w.employee_id = s.employee_id
					INNER JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					INNER JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
					ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id    
					LEFT JOIN emp_notice_period np
					ON w.employee_id = np.employee_id
					LEFT JOIN exit_reasons re
					ON np.reason = re.exit_id -- reason_code
					WHERE w.enabled = 0 $whereCondition
					ORDER BY w.employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'ATTRITION ANALYSIS REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  function FR001(){
  	$this->name = 'FORM-S REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?str_ireplace("SHIFT_NAME","IFNULL(SHIFT_NAME,'') SHIFT_NAME",$customFields):'';
  	
  	$tableQuery = "SELECT CONCAT(w.employee_name,' ',w.employee_lastname) NAME_OF_THE_THE_PERSON_EMPLOYED,IF(p.employee_gender='Male','M','F') SEX,IF(p.employee_gender='Male',IF(p.employee_father_name='Nil','',p.employee_father_name),IFNULL(p.spouse_name,p.employee_father_name)) `FATHER/HUSBAND'S_NEME`,
					      ds.designation_name DESIGNATION,w.employee_id EMPLOYEE_NUMBER,DATE_FORMAT(w.employee_doj,'%d-%b-%y') DATE_OF_ENTRY_INTO_SERVICES,'ADULT' `ADULTS/ADOLESSENT/CHILD`,s.shift_name SHIFT_NO,s.start_time TIME_OF_COMMENCEMENT_OF_WORK,'' REST_INTERVAL,
					      s.end_time TIME_AT_WHICH_WORK_ENDS,'Sat & Sunday' WEEKLY_HOLIDAY,'' CLASS_OF_WORKER,'' MAX_RATE_OF_WAGES,'' MIN_RATE_OF_WAGES
					FROM employee_work_details w
					INNER JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					INNER JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					INNER JOIN company_shifts s
					ON w.shift_id = s.shift_id
					WHERE w.enabled=1 $whereCondition
					ORDER BY w.employee_id";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	
  	$this->title = 'FORM-S REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function FR002(){
  	$this->name = 'FORM Q REPORT';
  	
  	$monyear = explode(' ',($this->reportParams ['monYear']));
  	$monthYear = $monyear[1]."-".$monyear[0]."-01";
  	
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$stmt = mysqli_query ( $this->conn, "SELECT IF(leave_based_on = 'finYear','FY','CY') leave_based_on FROM company_details;" );
  	$result = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
  	$leave_based = $result['leave_based_on'];
  	
  	if($leave_based=='CY'){
  		$monthstart = $monyear[1]."-01-01";
  		$year = $monyear[1];
  	}else{
  		$monthstart = $monyear[1]."-04-01";
  		$year1 =substr($monyear[1],2)+1;
  		$year =$monyear[1].$year1;
  	}
  	
  	$leaveRule = array();
  	$stmt=("SELECT l.leave_rule_id,l.rule_name,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
  	$query = mysqli_query ( $this->conn, $stmt );
  	while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
  		array_push ( $leaveRule, $row );
  	}
  	
  	$leaverulestmt = $openingstmt = $availedstmt = $balancestmt = $wrkdDays = $totstmt= $thismonthstmt = $totAvailedstmt ="";
  	foreach ( $leaveRule as $key => $val ){
  		$leaverulestmt .="IFNULL(p.". $val ['leave_rule_id'] .",0)". $val ['leave_rule_id'] .",";
  		$openingstmt .="total_". $val ['leave_rule_id'] ."-tot_availed_". $val ['leave_rule_id'] . " Opening_Bal_". $val ['alias_name'] .",";
  		$availedstmt .="this_month_". $val ['leave_rule_id'] . " Availed_". $val ['alias_name'] . ",";
  		$balancestmt .="FORMAT((total_". $val ['leave_rule_id'] ."-tot_availed_". $val ['leave_rule_id'] .")-(this_month_". $val ['leave_rule_id'] ."),2) Balance_". $val ['alias_name'] .",";
  		$wrkdDays .="this_month_". $val ['leave_rule_id'] ."-";
  		
  		$totstmt .="MAX(CASE WHEN leave_rule_id = '". $val ['leave_rule_id'] ."' THEN total ELSE 0 END) total_". $val ['leave_rule_id'] .",";
  		$thismonthstmt .="IFNULL(MAX(CASE WHEN month_year = '$monthYear' AND leave_rule_id = '". $val ['leave_rule_id'] ."' THEN ". $val ['leave_rule_id'] ." ELSE 0 END),'0') this_month_". $val ['leave_rule_id'] .",";
  		$totAvailedstmt .="SUM(CASE WHEN month_year BETWEEN '$monthstart' AND DATE_SUB('$monthYear',INTERVAL 1 MONTH)  AND leave_rule_id = '". $val ['leave_rule_id'] ."' THEN ". $val ['leave_rule_id'] ." ELSE 0 END) tot_availed_". $val ['leave_rule_id'] .",";
  	}
  	
  	$wrkdDaysstmt ="(Worked_days-".rtrim($wrkdDays,'-').") Worked_Days,(".str_replace('-','+',rtrim($wrkdDays,'-')).") LEAVE_DAYS";
  	$lopstmt = "IFNULL(MAX(CASE WHEN month_year = '$monthYear' THEN lop END),0) LOP,";
  	$workedstmt = "IFNULL(MAX(CASE WHEN month_year = '$monthYear' THEN worked_days END),0) WORKED_DAYS";
  	
  	$casestmt ="SELECT NAME `Name of the Person Employed`,DATE_FORMAT(employee_doj,'%d-%b-%Y') `Date of entry into services`,DATE_FORMAT(employee_dob,'%d-%b-%Y') `Age / Date of Birth`,designation_name Designation, $openingstmt '' `Holiday with Wages`,$availedstmt '' `Holiday with Wages1`,$balancestmt '' `Holiday with Wages2`,
  					IFNULL(`1`,'') `01`,IFNULL(`2`,'') `02`,IFNULL(`3`,'') `03`,IFNULL(`4`,'') `04`,IFNULL(`5`,'') `05`,IFNULL(`6`,'') `06`,IFNULL(`7`,'') `07`,IFNULL(`8`,'') `08`,IFNULL(`9`,'') `09`,IFNULL(`10`,'') `010`,
					IFNULL(`11`,'') `011`,IFNULL(`12`,'') `012`,IFNULL(`13`,'') `013`,IFNULL(`14`,'') `014`,IFNULL(`15`,'') `015`,IFNULL(`16`,'') `016`,IFNULL(`17`,'') `017`,IFNULL(`18`,'') `018`,IFNULL(`19`,'') `019`,IFNULL(`20`,'') `020`,
				  	IFNULL(`21`,'') `021`,IFNULL(`22`,'') `022`,IFNULL(`23`,'') `023`,IFNULL(`24`,'') `024`,IFNULL(`25`,'') `025`,IFNULL(`26`,'') `026`,IFNULL(`27`,'') `027`,IFNULL(`28`,'') `028`,IFNULL(`29`,'') `029`,IFNULL(`30`,'') `030`,IFNULL(`31`,'') `031`,
				  	'' `Total Hours of Overtime worked`,IFNULL(work_hrs_total,'') `Total hours of work done during the month`,'' `Total Number of maternity leave availed` FROM (";
  	
  	$casestmt1 = "SELECT employee_id EMPID,employee_name NAME,employee_doj,employee_dob,designation_name,DATE_FORMAT('$monthYear','%b %Y') MONTH, $totstmt $thismonthstmt $totAvailedstmt $lopstmt $workedstmt";
  	
  	$tableQuery ="$casestmt $casestmt1 FROM (
				  	SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,w.employee_doj ,pd.employee_dob,ds.designation_name,l.leave_rule_id
				  			,l.opening_bal,l.allotted,(l.opening_bal+l.allotted) total,IFNULL(p.month_year,DATE_FORMAT($monthYear,'%b')) month_year,IFNULL(p.lop,0) lop,$leaverulestmt IFNULL(p.worked_days,0) worked_days
				  	FROM emp_leave_account l
				  	INNER JOIN employee_personal_details pd
				  	ON l.employee_id = pd.employee_id
				  	INNER JOIN employee_work_details w
				  	ON l.employee_id = w.employee_id
				  	INNER JOIN company_departments dp
				  	ON w.department_id = dp.department_id
				  	INNER JOIN company_designations ds
				  	ON w.designation_id = ds.designation_id
				  	INNER JOIN company_branch cb
				  	ON w.branch_id = cb.branch_id
				  	LEFT JOIN company_team ct
				  	ON w.team_id = ct.team_id
				  	LEFT JOIN employee_work_details repo
				  	ON w.employee_reporting_person = repo.employee_id
				  	LEFT JOIN company_shifts cs
				  	ON w.shift_id = cs.shift_id
				  	LEFT JOIN payroll p
				  	ON l.employee_id = p.employee_id AND p.month_year BETWEEN '$monthstart' AND '2018-01-31'
				  	WHERE w.enabled=1 AND l.year = '$year' $whereCondition ) a
				  	GROUP BY employee_id ORDER BY employee_id)t
					LEFT JOIN (SELECT employee_id,MAX(CASE WHEN dt=01 THEN work_hrs END) `1`,MAX(CASE WHEN dt=02 THEN work_hrs ELSE '' END) `2`,MAX(CASE WHEN dt=03 THEN work_hrs ELSE '' END) `3`,
								      MAX(CASE WHEN dt=04 THEN work_hrs ELSE '' END) `4`,MAX(CASE WHEN dt=05 THEN work_hrs ELSE '' END) `5`,MAX(CASE WHEN dt=06 THEN work_hrs ELSE '' END) `6`,
								      MAX(CASE WHEN dt=07 THEN work_hrs ELSE '' END) `7`,MAX(CASE WHEN dt=08 THEN work_hrs ELSE '' END) `8`,MAX(CASE WHEN dt=09 THEN work_hrs ELSE '' END) `9`,
								      MAX(CASE WHEN dt=10 THEN work_hrs ELSE '' END) `10`,MAX(CASE WHEN dt=11 THEN work_hrs ELSE '' END) `11`,MAX(CASE WHEN dt=12 THEN work_hrs ELSE '' END) `12`,
								      MAX(CASE WHEN dt=13 THEN work_hrs ELSE '' END) `13`,MAX(CASE WHEN dt=14 THEN work_hrs ELSE '' END) `14`,MAX(CASE WHEN dt=15 THEN work_hrs ELSE '' END) `15`,
								      MAX(CASE WHEN dt=16 THEN work_hrs ELSE '' END) `16`,MAX(CASE WHEN dt=17 THEN work_hrs ELSE '' END) `17`,MAX(CASE WHEN dt=18 THEN work_hrs ELSE '' END) `18`,
								      MAX(CASE WHEN dt=19 THEN work_hrs ELSE '' END) `19`,MAX(CASE WHEN dt=20 THEN work_hrs ELSE '' END) `20`,MAX(CASE WHEN dt=21 THEN work_hrs ELSE '' END) `21`,
								      MAX(CASE WHEN dt=22 THEN work_hrs ELSE '' END) `22`,MAX(CASE WHEN dt=23 THEN work_hrs ELSE '' END) `23`,MAX(CASE WHEN dt=24 THEN work_hrs ELSE '' END) `24`,
								      MAX(CASE WHEN dt=25 THEN work_hrs ELSE '' END) `25`,MAX(CASE WHEN dt=26 THEN work_hrs ELSE '' END) `26`,MAX(CASE WHEN dt=27 THEN work_hrs ELSE '' END) `27`,
								      MAX(CASE WHEN dt=28 THEN work_hrs ELSE '' END) `28`,MAX(CASE WHEN dt=29 THEN work_hrs ELSE '' END) `29`,MAX(CASE WHEN dt=30 THEN work_hrs ELSE '' END) `30`,
								      MAX(CASE WHEN dt=31 THEN work_hrs ELSE '' END) `31`,REPLACE(ROUND(SUM(TIME_TO_SEC(work_hrs))/3600,2),'.',':') work_hrs_total
								FROM (           
								SELECT a.employee_id,days,DATE_FORMAT(days,'%d') dt,IFNULL(DATE_FORMAT(TIME(work_hrs),'%H:%i'),'') work_hrs,IFNULL(tot_worked,'') tot_worked 
								FROM attendance_summary a
								INNER JOIN employee_work_details w
								ON a.employee_id = w.employee_id
								INNER JOIN company_departments dp
								ON w.department_id = dp.department_id
								INNER JOIN company_designations ds
								ON w.designation_id = ds.designation_id
								INNER JOIN company_branch cb
								ON w.branch_id = cb.branch_id
								WHERE days BETWEEN '$monthYear' AND LAST_DAY('$monthYear') $whereCondition)p GROUP BY employee_id)c 
								ON EMPID = c.employee_id;";
  	//echo $tableQuery; die(); 
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'FORM Q REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 1,
  					"isRemoveIndices" =>false,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function FR003(){
  	$this->name = 'FORM-P REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$tableQuery = "SELECT CONCAT(w.employee_name,' ',w.employee_lastname) `Name of the Person Employed`,IF(p.employee_gender='Male','M','F') Sex,ds.designation_name `Designation_/_Nature_of_work`,
						'' `Daily rated/Piece-rated/Monthly_rated`,'' `Wages Period - Weekly/Fortnight/ Month`,'' `Total No. of days worked during the Week/Fortnight/Month`,'' `Units of work done/Number of days worked`,
						'' `Daily rate of wages / Piece rate`,'' `Overtime Rate`,'' `Basic Wages`,'' `Dearness Allowance`,'' `Other Allowances/Cash PaymentNature to be specified`,'' `Overtime earned`,
						'' `Leave wages including cash in lieu of kinds`,'' `Gross Wages`,'' `Provident Fund`,'' `Employees' State Insurance`,'' `Other Deduction (indicateNature)*`,'' `Fine (if any)*`,
						'' `Net Wages`,'' `Signature with Date or Thump Impression / Cheque No. and Date in case of payment through Bank/Advice of the Bank to be appended`,'' `Total unpaid amounts accumulated` 
				FROM employee_work_details w
				INNER JOIN employee_personal_details p
				ON w.employee_id = p.employee_id
				INNER JOIN company_designations ds
				ON w.designation_id = ds.designation_id
				INNER JOIN company_departments dp
				ON w.department_id = dp.department_id
				INNER JOIN company_branch cb
				ON w.branch_id = cb.branch_id
				INNER JOIN company_shifts s
				ON w.shift_id = s.shift_id
				WHERE w.enabled=1 $whereCondition
				ORDER BY w.employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'FORM-P REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function HR005(){
  	$this->name = 'ATTRITION BY GENDER REPORT';
  	$monyear = str_ireplace(' ','',$this->reportParams ['monYear']);
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$tableQuery = "SELECT employee_gender Experience,MAX(CASE WHEN reason_code='Resigned' THEN emp_count ELSE 0 END) Resigned,
					    MAX(CASE WHEN reason_code='Termination' THEN emp_count ELSE 0 END) Termination,
					    MAX(CASE WHEN reason_code='Abscond' THEN emp_count ELSE 0 END) Abscond
					FROM (
					  SELECT COUNT(employee_id) emp_count,reason_code,employee_gender
					  FROM (
					    SELECT n.employee_id,er.reason_code,-- w.employee_doj,n.last_working_date,n.process_type, 
					      ROUND((DATEDIFF(n.last_working_date,w.employee_doj)/365),1) year_exp,p.employee_gender
					    FROM emp_notice_period n
					    INNER JOIN employee_work_details w
					    ON n.employee_id = w.employee_id
					    INNER JOIN employee_personal_details p
					    ON n.employee_id = p.employee_id
					    LEFT JOIN exit_reasons er
					    ON n.reason = er.exit_id
					    WHERE n.status='S' $whereCondition AND DATE_FORMAT(n.last_working_date,'%m%Y')='$monyear'
					  )t GROUP BY reason_code,employee_gender)a 
					GROUP BY employee_gender;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'ATTRITION BY GENDER REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 1 ,
  					"isRemoveIndices" =>false,
  					"isTotal" =>true
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function HR006(){
  	$this->name = 'ATTRITION BY EXPERIENCE REPORT';
  	$monyear = str_ireplace(' ','',$this->reportParams ['monYear']);
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$tableQuery = "SELECT exp_range Experience,MAX(CASE WHEN reason_code='Resigned' THEN emp_count ELSE 0 END) Resigned,
					    MAX(CASE WHEN reason_code='Termination' THEN emp_count ELSE 0 END) Termination,
					    MAX(CASE WHEN reason_code='Abscond' THEN emp_count ELSE 0 END) Abscond
					FROM (
					SELECT COUNT(employee_id) emp_count,reason_code,exp_range
					FROM (
					SELECT n.employee_id,er.reason_code,-- w.employee_doj,n.last_working_date,n.process_type, 
					  ROUND((DATEDIFF(n.last_working_date,w.employee_doj)/365),1) year_exp,
					  (CASE
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 0.1 and 0.6 THEN '0-0.6 Year'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 0.6 and 1.0 THEN '0.6-1 Year'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 1 and 1.6 THEN '1-1.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 1.6 and 2 THEN '1.6-2 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 2 and 2.6 THEN '2-2.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 2.6 and 3 THEN '2.6-3 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 3 and 3.6 THEN '3-3.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 3.6 and 4 THEN '3.6-4 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 4 and 4.6 THEN '4-4.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 4.6 and 5 THEN '4.6-5 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) >5 THEN 'Above 5 Years' END) as exp_range
					FROM emp_notice_period n
					INNER JOIN employee_work_details w
					ON n.employee_id = w.employee_id
					LEFT JOIN exit_reasons er
					ON n.reason = er.exit_id
					WHERE n.status='S' $whereCondition AND DATE_FORMAT(n.last_working_date,'%m%Y')='$monyear'
					)t
					GROUP BY reason_code,exp_range)a GROUP BY exp_range;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'ATTRITION BY EXPERIENCE REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 1 ,
  					"isRemoveIndices" =>false,
  					"isTotal" =>true
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function HR007(){
  	$this->name = 'ATTRITION BY EXPERIENCE & GENDER REPORT';
  	$monyear = str_ireplace(' ','',$this->reportParams ['monYear']);
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	
  	$tableQuery = "SELECT employee_gender Gender,MAX(CASE WHEN exp_range='0-0.6 Year' THEN emp_count ELSE 0 END) '0 - 0.6 Year',
					    MAX(CASE WHEN exp_range='0.6-1 Year' THEN emp_count ELSE 0 END) '0.6 - 1 Year',
					    MAX(CASE WHEN exp_range='1-1.6 Years' THEN emp_count ELSE 0 END) '1 - 1.6 Years',
					    MAX(CASE WHEN exp_range='1.6-2 Years' THEN emp_count ELSE 0 END) '1.6 - 2 Years',
					    MAX(CASE WHEN exp_range='2-2.6 Years' THEN emp_count ELSE 0 END) '2 - 2.6 Years',
					    MAX(CASE WHEN exp_range='2.6-3 Years' THEN emp_count ELSE 0 END) '2.6 - 3 Years',
					    MAX(CASE WHEN exp_range='3-3.6 Years' THEN emp_count ELSE 0 END) '3 - 3.6 Years',
					    MAX(CASE WHEN exp_range='3.6-4 Years' THEN emp_count ELSE 0 END) '3.6 - 4 Years',
					    MAX(CASE WHEN exp_range='4-4.6 Years' THEN emp_count ELSE 0 END) '4 - 4.6 Years',
					    MAX(CASE WHEN exp_range='4.6-5 Years' THEN emp_count ELSE 0 END) '4.6 - 5 Years',
					    MAX(CASE WHEN exp_range='Above 5 Years' THEN emp_count ELSE 0 END) 'Above 5 Years'
					FROM (   
					SELECT COUNT(employee_id) emp_count,employee_gender,exp_range 
					FROM (
					SELECT n.employee_id,er.reason_code,-- w.employee_doj,n.last_working_date,n.process_type, 
					  ROUND((DATEDIFF(n.last_working_date,w.employee_doj)/365),1) year_exp,p.employee_gender,
					  (CASE
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 0.1 and 0.6 THEN '0-0.6 Year'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 0.6 and 1.0 THEN '0.6-1 Year'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 1 and 1.6 THEN '1-1.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 1.6 and 2 THEN '1.6-2 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 2 and 2.6 THEN '2-2.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 2.6 and 3 THEN '2.6-3 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 3 and 3.6 THEN '3-3.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 3.6 and 4 THEN '3.6-4 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 4 and 4.6 THEN '4-4.6 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) BETWEEN 4.6 and 5 THEN '4.6-5 Years'
					        WHEN ROUND((DATEDIFF(n.last_working_date,employee_doj)/365),2) >5 THEN 'Above 5 Years' END) as exp_range
					FROM emp_notice_period n
					INNER JOIN employee_work_details w
					ON n.employee_id = w.employee_id
					INNER JOIN employee_personal_details p
					ON n.employee_id = p.employee_id
					LEFT JOIN exit_reasons er
					ON n.reason = er.exit_id
					WHERE n.status='S' $whereCondition AND DATE_FORMAT(n.last_working_date,'%m%Y')='$monyear'
					)t
					GROUP BY employee_gender,exp_range)a GROUP BY employee_gender;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'ATTRITION BY EXPERIENCE & GENDER REPORT';
  	
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 1,
  					"isRemoveIndices" =>false,
  					"isTotal" =>true
  					
  					
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  			
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function HR008(){
 	$this->name = 'HR ANALYTICS';
 	 $branchid = $this->reportParams ['branchOnly'];
 	 $teamid =str_ireplace("," ,"','" , $this->reportParams ['teamOnly']) ; 
 	 $service_status = $this->reportParams ['employeeServiceStatus'] ;
 	 $personal_detail = $this->reportParams ['empPersonalDetail'];
 	 $masterDetail = $this->reportParams ['empMasterDetail'];
 	 $employeeStatus = $this->reportParams ['employeeStatus'];
 	 $employeeStatus_1 = $employeeStatus=='1'?"AND w.enabled='1'" : "AND  er.exit_id= '$employeeStatus'" ;
  	 $tableQuery ="SELECT w.employee_id EMPID,w.employee_name NAME,$personal_detail,$masterDetail,IFNULL(er.reason_code,'Active') Employee_Status,
					cj.status_name Status_name FROM employee_work_details w 
					LEFT JOIN company_branch cb ON w.branch_id = cb.branch_id
					LEFT JOIN company_designations ds ON w.designation_id = ds.designation_id
					LEFT JOIN company_departments dp ON w.department_id = dp.department_id
					LEFT JOIN company_team ct ON w.team_id = ct.team_id
					LEFT JOIN company_job_statuses cj ON w.status_id = cj.status_id
					LEFT JOIN employee_personal_details p ON w.employee_id = p.employee_id
					LEFT JOIN emp_notice_period np ON w.employee_id = np.employee_id
					LEFT JOIN exit_reasons er ON er.exit_id = np.reason
					WHERE cb.branch_id ='$branchid' AND ct.team_id IN('$teamid') 
  	 				AND cj.status_id='$service_status' $employeeStatus_1
  	 				ORDER BY w.employee_id";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'HR ANALYTICS REPORT';
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData[1],
  					"fixedColumns" => 2,
  					"isRemoveIndices" =>false,
  					"isTotal" =>false
  						
  						
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  				
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function ER010(){
  	$this->name = 'DOCUMENT TRACKER REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  	 
  	
  	$customFields= ",".str_replace("^"," ",$this->reportParams ['isCustom']);
  	$columns = ($this->reportParams ['isCustom']!='')?str_ireplace("SHIFT_NAME","IFNULL(SHIFT_NAME,'') SHIFT_NAME",$customFields):'';
  	 
  	$tableQuery = "SELECT w.employee_id EMPID,w.employee_name NAME" .$columns.",IF(IFNULL(p.employee_pan_proof,'No')='Nil','No','Yes') PAN, IFNULL(IF(p.employee_aadhaar_proof='Nil','No','Yes'),'No') AADHAAR, 
					  IFNULL(IF(p.employee_id_proof='Nil','No','Yes'),'No') ID,IFNULL(IF(p.employee_bank_proof='Nil','No','Yes'),'No') BANK,
					  IF(p.employee_sslc_proof IS NULL ,'No','Yes') SSLC, IF(p.employee_hsc_proof IS NULL ,'No','Yes') HSC,
					  IF(p.employee_ug_proof IS NULL ,'No','Yes') UG,IF(p.employee_pg_proof IS NULL ,IF(p.emp_pg_degree IS NULL OR p.emp_pg_degree='','Not Applicable','No'),'Yes') PG
					FROM employee_work_details w
					INNER JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					INNER JOIN employee_salary_details s
					ON w.employee_id = s.employee_id
					INNER JOIN company_designations ds
					ON w.designation_id = ds.designation_id
					INNER JOIN company_departments dp
					ON w.department_id = dp.department_id
					INNER JOIN company_branch cb
					ON w.branch_id = cb.branch_id
					INNER JOIN company_team ct
					ON w.team_id = ct.team_id
					LEFT JOIN employee_work_details repo
					ON w.employee_reporting_person = repo.employee_id
					LEFT JOIN company_shifts cs
					ON w.shift_id = cs.shift_id 
					WHERE w.enabled=1 $whereCondition ORDER BY w.employee_id";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'DOCUMENT TRACKER REPORT';
  	 
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  						
  						
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  				
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
  function ER011(){
  	$this->name = 'SHIFT MONTHLY REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  
  	$start_date = $startDate = $from_date= ($this->reportParams ['from_date']!=''?$this->reportParams ['from_date']:'');
  	$end_date= ($this->reportParams ['to_date']!=''?$this->reportParams ['to_date']:'');
  	 
  	$curenttitle = " ".date("j M Y", strtotime($from_date))." TO ".date("j M Y", strtotime($end_date));
  	
  	$query ="SELECT employee_id EMPID,CONCAT(employee_name,' ',employee_lastname) NAME,";
  	
  	while (strtotime($startDate) <= strtotime($end_date)) {
  		$query.= "MAX(CASE WHEN (weeks = IF((WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)>5,(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01'))),(WEEK('".$startDate."') - WEEK(DATE_FORMAT('".$startDate."' , '%Y-%m-01')) + 1)))
	                                          THEN (CASE WHEN (DAYNAME('".$startDate."')='sunday') THEN IF((sunday = 'FH' OR sunday = 'SH' OR sunday = 'FD' ),CONCAT('WE','-',sunday),sunday) WHEN (DAYNAME('".$startDate."')='Monday') THEN IF((monday = 'FH' OR monday = 'SH' OR monday = 'FD' ),CONCAT('WE','-',monday),monday) WHEN (DAYNAME('".$startDate."')='Tuesday') THEN IF((tuesday = 'FH' OR tuesday = 'SH' OR tuesday = 'FD' ),CONCAT('WE','-',tuesday),tuesday) WHEN (DAYNAME('".$startDate."')='Wednesday') THEN IF((wednesday = 'FH' OR wednesday = 'SH' OR wednesday = 'FD' ),CONCAT('WE','-',wednesday),wednesday) WHEN (DAYNAME('".$startDate."')='Thursday') THEN IF((thursday = 'FH' OR thursday = 'SH' OR thursday = 'FD' ),CONCAT('WE','-',thursday),thursday) WHEN (DAYNAME('".$startDate."')='Friday') THEN IF((friday = 'FH' OR friday = 'SH' OR friday = 'FD' ),CONCAT('WE','-',friday),friday) WHEN (DAYNAME('".$startDate."')='Saturday') THEN IF((saturday = 'FH' OR saturday = 'SH' OR saturday = 'FD' ),CONCAT('WE','-',saturday,IF(saturday!= 'FD' AND DATE_FORMAT(check_in,'%Y-%m-%d') = '".$startDate."',CONCAT(' - ',In_Out,' - ',shift_name),'')),saturday) END)
								WHEN (DATE_FORMAT(check_in,'%Y-%m-%d') ='".$startDate."' AND DATE_FORMAT(check_out,'%Y-%m-%d') ='".$startDate."' AND DATE_FORMAT(absent_date,'%Y-%m-%d') = '".$startDate."') OR DATE_FORMAT(absent_date,'%Y-%m-%d') = '".$startDate."' THEN CONCAT(leave_details,IF(DATE_FORMAT(check_in,'%Y-%m-%d') = '".$startDate."',CONCAT(' - ',In_Out,' - ',shift_name),''))
								WHEN DATE_FORMAT(check_in,'%Y-%m-%d') ='".$startDate."' THEN CONCAT('P',' - ',In_Out,' - ',shift_name)
								WHEN ((start_date BETWEEN '".$startDate."' AND '".$startDate."') OR (end_date BETWEEN '".$startDate."' AND '".$startDate."')) THEN (CASE WHEN (category = 'OPTIONAL' AND holBranch = empBranch) THEN 'RH-FD' WHEN (category = 'HOLIDAY' AND holBranch = 'NA') THEN 'GH-FD' END)
	                            WHEN DATE_FORMAT(last_working_date,'%Y-%m-%d') < '".$startDate."' OR employee_doj > '".$startDate."' THEN '-'
	                            WHEN device_status = 0 THEN '' ELSE 'A' END) `".$startDate."`,";
	  	$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
  	}
  	$tableQuery= substr($query,0,-1)."FROM (
					SELECT *,CONCAT(DATE_FORMAT(check_in,'%H:%i'),'/',DATE_FORMAT(check_out,'%H:%i')) In_Out FROM 
					(SELECT employee_id,(CASE WHEN is_day=1 THEN MIN(date_time)
					                          WHEN is_day=0 THEN work_day END) 'check_in',
					                    (CASE WHEN is_day=1 THEN MAX(date_time)
					                          WHEN is_day=0 THEN DATE_SUB(MAX(date_time),INTERVAL 1 DAY) ELSE '' END) 'check_out',
					        employee_doj,last_working_date,absent_date,leave_details,weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,
					        employee_name,employee_lastname,start_date,end_date,holBranch,empBranch,category,device_status,shift_name
					FROM (
					SELECT z.employee_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
					      (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
					       ELSE date_time END) work_day,
					       z.employee_doj,n.last_working_date,a.absent_date,CONCAT(UPPER(a.leave_rule_type),'-',a.day_type) leave_details,-- CONCAT(a.day_count,'_',a.leave_rule_type,'_',a.day_type,'_',IF(a.request_id!='',IFNULL(l.reason,'Nil'),IFNULL(a.reason,'Nil')),'_',IF(a.request_id !='',1,0),'_',device_status) leave_details,
					       weeks,sunday,monday,tuesday,wednesday,thursday, friday,saturday,employee_name,employee_lastname,start_date,end_date,h.branch_id holBranch,empBranch,h.category,device_status,shift_name
					FROM (
						SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
								IF(from_date<'$start_date','$start_date',from_date) from_date,
								IF(to_date='0000-00-00' OR to_date IS NULL ,'$end_date',to_date) to_date,IFNULL(status,0) device_status
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						INNER JOIN company_designations ds
						ON w.designation_id = ds.designation_id
						INNER JOIN company_departments dp
						ON w.department_id = dp.department_id
						INNER JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						INNER JOIN company_team ct
						ON w.team_id = ct.team_id
						WHERE  w.enabled = 1 $whereCondition  
						AND ((NOT (from_date > '$end_date' OR to_date < '$start_date' )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > '$end_date' )) ORDER BY w.employee_id ) z
					LEFT JOIN company_shifts s
					ON z.shift_id = s.shift_id 
					LEFT JOIN emp_notice_period n 
					ON z.employee_id = n.employee_id AND n.status='A'
					LEFT JOIN device_users du
					ON z.employee_id = du.employee_id
					LEFT JOIN employee_biometric b
					ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND to_date
					LEFT JOIN leave_requests l
					ON z.employee_id = l.employee_id AND l.status='A' AND (l.from_date BETWEEN '$start_date' AND '$end_date') AND (l.to_date BETWEEN '$start_date' AND '$end_date') 
					LEFT JOIN emp_absences a
					ON z.employee_id = a.employee_id AND a.absent_date BETWEEN '$start_date' AND '$end_date'
					LEFT join weekend we ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = we.shift_id
					LEFT JOIN holidays_event h ON (h.start_date BETWEEN '$start_date' AND '$end_date') 
					WHERE s.is_day IS NOT NULL 
					ORDER BY z.employee_id,date_time )q
					GROUP BY employee_id,DATE_FORMAT(work_day,'%Y-%m-%d'),absent_date,weeks,start_date ORDER BY employee_id) t1
					GROUP BY t1.employee_id,t1.check_in,t1.absent_date,weeks,start_date) r
					GROUP BY employee_id ORDER BY employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'SHIFT MONTHLY REPORT';
  
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>true,
  					"isTotal" =>false
  
  
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  function ER012(){
  	$this->name = 'APPRAISAL MONTHLY REPORT';
  	$whereCondition = ($this->reportParams ['filter_key'] == 'E') ? "AND w.employee_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'D') ? "AND ds.designation_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'F') ? "AND  dp.department_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'B') ? "AND  cb.branch_id IN " . $this->reportParams ['filter_value'] : (($this->reportParams ['filter_key'] == 'T') ? "AND  ct.team_id IN " . $this->reportParams ['filter_value'] : ''))));
  
  	$caseStmt = "";
  	$last_month = $_SESSION ['current_payroll_month'];
  	$start =$start_month= date ("Y-m-d", strtotime("-6 month", strtotime($last_month)));
  	
  	$leaverules ="";
  	$leaveRule = array();
  	
  	$stmt=("SELECT l.leave_rule_id,l.rule_name,l.alias_name FROM company_leave_rules l WHERE enabled=1;" );
  	$query = mysqli_query ( $this->conn, $stmt );
  	while ( $row = mysqli_fetch_array ( $query, MYSQLI_ASSOC ) ) {
  		array_push ( $leaveRule, $row );
  	}
  	
  	foreach ( $leaveRule as $key => $val )
  		$leaverules .="py.". $val ['leave_rule_id'] ."+";
  	
  	$leavestmt = "(".$leaverules."py.lop) tot_leave";
  	while (strtotime($start) < strtotime($last_month)) {
  		$month_head = date ("M'y", strtotime($start));
  		$caseStmt.= "IFNULL(MAX(CASE WHEN month_year='$start' THEN tot_leave END),0) `$month_head`,";
  		$start = date ("Y-m-d", strtotime("+1 month", strtotime($start)));
  	}
  	
  	$tableQuery = "SELECT employee_id EMPID,employee_name NAME,team_name TEAM_NAME,".$caseStmt."SUM(tot_leave) TOTAL
					FROM (
						SELECT py.employee_id,py.employee_name,ct.team_name,month_year,".$leavestmt." 
						FROM payroll py
						INNER JOIN employee_work_details w
						ON py.employee_id = w.employee_id
						INNER JOIN employee_personal_details p
						ON w.employee_id = p.employee_id
						INNER JOIN employee_salary_details s
						ON w.employee_id = s.employee_id
						INNER JOIN company_designations ds
						ON w.designation_id = ds.designation_id
						INNER JOIN company_departments dp
						ON w.department_id = dp.department_id
						INNER JOIN company_branch cb
						ON w.branch_id = cb.branch_id
						INNER JOIN company_team ct
						ON w.team_id = ct.team_id
						LEFT JOIN employee_work_details repo
						ON w.employee_reporting_person = repo.employee_id
						LEFT JOIN company_shifts cs
						ON w.shift_id = cs.shift_id 
						WHERE py.month_year BETWEEN '$start_month' AND '$last_month' $whereCondition
					)q GROUP BY employee_id ORDER BY employee_id;";
  	//echo $tableQuery; die();
  	$queryData = $this->getComponentsResult ( $tableQuery );
  	$this->title = 'APPRAISAL MONTHLY REPORT FROM '.$start_month.' TO '.$last_month;
  
  	$headersArr= array();
  	if($queryData[0]!='error'){
  		$headersArr= array();
  		foreach($queryData[0] as $header)
  			array_push($headersArr,array("name"=>$header));
  			$this->components [] = ($queryData [0] != 'error') ? new Component ( 'table', $this->name, 'top', array (
  					"status" => "success",
  					"headers" => $headersArr,
  					"queryData" => $queryData [1],
  					"fixedColumns" => 2 ,
  					"isRemoveIndices" =>false,
  					"isTotal" =>false
  
  
  			) ) : array (
  					"type" => 'table',
  					"title" => $this->name,
  					"position" => 'top',
  					"status" => "error",
  					"data" => $queryData [1]
  			);
  
  			return $this->getJsonData ( $this );
  	}else{
  		return  false;
  	}
  }
  
}
  	

?>