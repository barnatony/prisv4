<?php
/*
 * ----------------------------------------------------------
 * Filename : filter.class.php
 * Author : Rufus Jackson
 * Database : company_filter
 * Oper : filter Actions
 *
 * ----------------------------------------------------------
 */
class Filter {
	/* Member variables */
	var $pt;
	var $esi;
	var $pf;
	var $tan;
	var $resp_person_emp_id;
	var $enabled;
	var $updated_by;
	var $conn;
	var $viewscreen;
	function __construct() {
		
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	function createFilterForScreen($screen){
		$this->viewscreen = $screen;
	
		if($screen=='attendances'){//attendances.php
		//array must start with Zero For checked defaulty
		$for=Array(0=>["for"=>'D',"isMultiple"=>0],
				   1=>["for"=>'F',"isMultiple"=>1],
				   2=>["for"=>'B',"isMultiple"=>1],
				   3=>["for"=>'S',"isMultiple"=>0],
				   4=>["for"=>'T',"isMultiple"=>1],
				   5=>["for"=>'E',"isMultiple"=>1]);
		return $this->generateFilterHTML($for,0,0,$this->viewscreen);
		}else if($screen=='attendance'){//attendance.php
			$for=Array(0=>["for"=>'D',"isMultiple"=>0],
					1=>["for"=>'F',"isMultiple"=>1],
					2=>["for"=>'B',"isMultiple"=>1],
					3=>["for"=>'S',"isMultiple"=>0],
					4=>["for"=>'T',"isMultiple"=>1],
					5=>["for"=>'E',"isMultiple"=>1]);
			return $this->generateFilterHTML($for,1,0,$this->viewscreen);
		}else if($screen=='previewPayroll'){//previewPayroll.php
			$for=Array(0=>["for"=>'D',"isMultiple"=>0],
					1=>["for"=>'F',"isMultiple"=>1],
					2=>["for"=>'B',"isMultiple"=>1],
					3=>["for"=>'S',"isMultiple"=>0],
					4=>["for"=>'T',"isMultiple"=>1],
					5=>["for"=>'E',"isMultiple"=>1]);
			return $this->generateFilterHTML($for,0,0,$this->viewscreen);
		}else if($screen=='miscellaneous'){//miscellaneous.php
			$for=Array(0=>["for"=>'D',"isMultiple"=>0],
					1=>["for"=>'F',"isMultiple"=>1],
					2=>["for"=>'B',"isMultiple"=>1],
					3=>["for"=>'S',"isMultiple"=>0],
					4=>["for"=>'T',"isMultiple"=>1],
					5=>["for"=>'E',"isMultiple"=>1]);
			return $this->generateFilterHTML($for,0,0,$this->viewscreen);
		}else if($screen=='viewPayroll'){//miscellaneous.php
			$for=Array(0=>["for"=>'D',"isMultiple"=>0],
					1=>["for"=>'F',"isMultiple"=>1],
					2=>["for"=>'B',"isMultiple"=>1],
					3=>["for"=>'S',"isMultiple"=>0],
					4=>["for"=>'T',"isMultiple"=>1],
					5=>["for"=>'E',"isMultiple"=>1]);
			return $this->generateFilterHTML($for,0,0,$this->viewscreen);
		}else if($screen=='lopupdate'){//lopupdate.php
			$for=Array(0=>["for"=>'D',"isMultiple"=>0],
					1=>["for"=>'F',"isMultiple"=>1],
					2=>["for"=>'B',"isMultiple"=>1],
					3=>["for"=>'S',"isMultiple"=>0],
					4=>["for"=>'T',"isMultiple"=>1],
					5=>["for"=>'E',"isMultiple"=>1]);
			
			return $this->generateFilterHTML($for,0,0,$this->viewscreen);
		}
	}
	
	function generateFilterHTML($filtersFor,$loadDisabled,$loadprocessedEmp,$screen=null) {
		ob_start();
		require ('filterHtml.php');
		$filterHtml = ob_get_clean( );
		return $filterHtml;
	}
	
	function getEmployeesbyFilter($filterKey,$filterValue=null,$loadDisabled=null,$loadprocessedEmp=null){
		$ajson = array ();
		$values="";
		//avoid employee Values Its default
		if($filterKey!='E')
		$values="('".implode("','",(array)str_replace(",","','", $filterValue))."')";
		
		$whereCondition=($filterKey=='D')?"AND department_id IN":
				        (($filterKey=='F')?"AND designation_id IN":
						(($filterKey=='B')?"AND branch_id IN":
						(($filterKey=='S')?"AND shift_id IN":
						(($filterKey=='T')?"AND team_id IN":(($filterKey='E')?"":'')))));
		
	  $payrollCondition = ($this->viewscreen!='previewPayroll')?"":"AND w.employee_id NOT IN (SELECT DISTINCT w.employee_id FROM employee_work_details w
                  			LEFT JOIN payroll p
                  			ON w.employee_id = p.employee_id
                  			LEFT JOIN emp_notice_period n
                  		    ON w.employee_id = n.employee_id
                 		    WHERE w.enabled IN (1) AND (p.month_year = '". $_SESSION ['current_payroll_month']."' OR DATE_FORMAT(n.last_working_date,'%m%Y') = DATE_FORMAT('". $_SESSION ['current_payroll_month']."','%m%Y')))";
			
		
	 $stmt="SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,
			   w.enabled FROM employee_work_details w".
			   (($loadprocessedEmp==1)?" INNER JOIN payroll_preview_temp pp
               ON pp.employee_id = w.employee_id AND pp.is_processed!=1 ":"")."
			   WHERE w.enabled IN (".(($loadDisabled==0)?'1':'0,1').") $whereCondition $values $payrollCondition ";
		     		if($result=mysqli_query ( $this->conn, $stmt )){
					while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
							array_push ( $ajson, $row );
				    }
				    return ((empty( $ajson) )?array("result"=>true,"data"=>'No Employees Found'):array("result"=>true,"data"=>$ajson));
				    } else {
				    return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
				    }
	  }
}
?>