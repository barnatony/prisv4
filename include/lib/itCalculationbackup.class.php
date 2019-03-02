<?php
/*
 * ----------------------------------------------------------
 * Filename : Itdeclaration.class.php
 * Classname: Itcalculation
 * Author : Rufus Jackson
 * Database : employee_income_tax
 * Oper : Find TDS
 *
 * -----------------------------------------------------------
 */
require_once (__DIR__ . "/database.class.php");
class ItCalculation {
	/* Member variables */
	var $fin_year;
	var $financial_year_start;
	var $financial_year_end;
	var $current_year;
	var $remaining_months;
	var $payroll_month_year;
	var $updated_by;
	var $_connection;
	
	/* Common for All Employees */
	var $current_month;
	private $it_slabs = array (
			"G" => array (),
			"A" => array (),
			"S" => array () 
	);
	private $epf_properties;
	private $mappedTaxExemptions;
	private $exemptionText;
	private $epf_deductable_amount = 0; // deductable amount from salaryy details
	private $epf_limit;
	private $hraMetroCities = array (
			"Chennai",
			"CHENNAI",
			"Delhi",
			"DELHI",
			"Mumbai",
			"MUMBAI",
			"Kolkatta",
			"KOLKATTA" 
	);
	private $limit80c = 150000;
	private $rebate_amount = 5000;
	private $rebate_limit = 500000;
	private $employee_remaining_months;
	private $employee_id;
	private $employee_age;
	private $employee_father_age;
	private $employee_city;
	private $current_month_gross = 0;
	private $master_gross_salary = 0;
	private $yearly_epf = 0;
	private $yearly_gross = 0;
	private $yearly_pt = 0;
	private $yearly_basic = 0;
	private $yearly_hra = 0;
	protected $tax_paid = 0;
	protected $gross_total_income = 0;
	protected $other_income = 0;
	protected $total_inc = 0;
	protected $house_prop_inc = 0;
	private $salaried_income = 0; // check this to prevent negative taxation <250000
	
	/* 10 A Exemptions */
	protected $exe_medical = 0;
	protected $exe_travel_con = 0;
	protected $exe_lta = 0;
	protected $exe_other_10a = 0;
	protected $exe_80c = 0;
	protected $exe_80d = 0;
	protected $exe_80g = 0;
	protected $exe_80e = 0;
	protected $exe_deductions_other = 0; // other deductions - 80TTA+80U+80U - interst on saving acc + personal disability + personal severe disability
	protected $exe_hra = 0;
	protected $relief = 0;
	protected $taxable_inc = 0;
	protected $tax = 0;
	protected $ec = 0;
	protected $shec = 0;
	protected $surcharge = 0;
	protected $tax_payable = 0;
	protected $tds = 0;
	
	/* Member functions */
	function __construct($employees, $fin_year, $payroll_month_year, $updated_by, $isNoUpdate, $conn, $againRunFlag) {
		$this->_connection = $conn;
		$this->financial_year_start = substr ( $fin_year, 0, 4 ) . "-04-01";
		$this->financial_year_end = (substr ( $fin_year, 0, 4 ) + 1) . "-03-31";
		$month_start = substr ( $payroll_month_year, 2, 4 ) . "-" . substr ( $payroll_month_year, 0, 2 ) . "-01";
		$this->remaining_months = self::monthsFromDate ( $month_start, $this->financial_year_end, false );
		$this->prev_month_end = substr ( $payroll_month_year, 2, 4 ) . "-" . (substr ( $payroll_month_year, 0, 2 ) - 1) . "-31";
		$this->current_month = substr ( $payroll_month_year, 0, 2 );
		$this->fin_year = $fin_year;
		$this->current_year = substr ( $payroll_month_year, 2, 4 );
		$this->payroll_month_year = $payroll_month_year;
		$this->updated_by = $updated_by;
		$this->loadItSlabs ( $fin_year );
		$this->loadEpfProperties ();
		$this->loadMappedExemptions ();
		$empCount = 0;
		foreach ( $employees as $employee ) {
			
			if ($employee ['status_flag'] != 'P' || $againRunFlag == 1) {
				$empCount ++;
				$stmt = "SELECT DATE_FORMAT(np.last_working_date,'%m') last_working_month,np.last_working_date,p.father_dob,p.employee_dob,w.employee_doj,it.*,tax.old_tax_paid
					FROM employee_personal_details p
					INNER JOIN  employee_work_details w
					ON p.employee_id=w.employee_id
					INNER JOIN  employee_it_declaration it
					ON p.employee_id=it.employee_id AND it.year='" . $this->fin_year . "'
					INNER JOIN  employee_income_tax tax
					ON it.employee_id=tax.employee_id AND tax.year='" . $this->fin_year . "'
					LEFT JOIN emp_notice_period np
					ON w.employee_id=np.employee_id  AND np.status='A'
					WHERE w.employee_id ='" . $employee ['employee_id'] . "'";
				$result = mysqli_query ( $this->_connection, $stmt );
				if ($result) {
					$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
					$itRow = $row;
					mysqli_free_result ( $result );
					$tds = self::calculateEmployeeIt ( $employee ['employee_id'], $employee ['gross_salary'], $itRow ); // GET TDS VALUE
					
					if ($isNoUpdate == 1) {
						$this->updateITtable ();
						if ($tds != null) {
							$TotalDeductionValue = $tds + $employee ['total_deduction'];
							$netSalary = $employee ['gross_salary'] - ($tds + $employee ['total_deduction']);
							$stmt = mysqli_prepare ( $this->_connection, "UPDATE payroll_preview_temp pp SET pp.c_it=? ,pp.total_deduction=? , pp.net_salary=?, updated_by=?,status_flag='P' WHERE employee_id=? " );
							mysqli_stmt_bind_param ( $stmt, 'sssss', $tds, $TotalDeductionValue, $netSalary, $this->updated_by, $employee ['employee_id'] );
							$result = mysqli_stmt_execute ( $stmt );
						} else {
							$TotalDeductionValue = $employee ['total_deduction'];
							$netSalary = $employee ['gross_salary'] - ($employee ['total_deduction']);
							$stmt = mysqli_prepare ( $this->_connection, "UPDATE payroll_preview_temp pp SET pp.total_deduction=? , pp.net_salary=?, updated_by=?,status_flag='P' WHERE employee_id=? " );
							mysqli_stmt_bind_param ( $stmt, 'ssss', $TotalDeductionValue, $netSalary, $this->updated_by, $employee ['employee_id'] );
							$result = mysqli_stmt_execute ( $stmt );
						}
					} else {
						return $tds;
					}
				} else {
					die ( mysqli_error ( $this->_connection ) );
				}
			} else {
				// load and select value from the table
			}
		}
	}
	public function calculateEmployeeIt($employee_id, $currentGross, $itRow) {
		$this->current_month_gross = $currentGross;
		$this->employee_id = $employee_id;
		$this->loadYearlyValues ( $employee_id, $this->financial_year_start, $this->prev_month_end, $itRow );
		$this->other_mapped_exe_10a ( $employee_id );
		if ($this->salariedIncome ( $itRow ) > 250000) {
			$this->gross_total_income = $this->grossTotalIncome ( $itRow );
			$this->taxPayable ( $itRow );
		} else {
			$this->gross_total_income = $this->grossTotalIncome ( $itRow );
			// no tax for people
			$this->other_income = 0;
			$this->exe_lta = 0;
			$this->exeOther10a = 0;
			$this->total_inc = 0;
			$this->house_prop_inc = 0;
			$this->exe_80e = 0;
			$this->exe_80c = 0;
			$this->gross_total_income = 0;
			$this->exe_80d = 0;
			$this->exe_80g = 0;
			$this->exe_deductions_other = 0;
			$this->taxable_inc = 0;
			$this->relief = 0;
			$this->tax = 0;
			$this->ec = 0;
			$this->shec = 0;
			$this->salaried_income = 0;
			$this->surcharge = 0;
			$this->tax_payable = 0;
			$this->tds = 0;
			$this->tax_paid = 0;
			$this->tds = 0;
		}
		return $this->tds;
	}
	private function loadItSlabs($fin_year) {
		$stmt = "SELECT  slab.from_value, slab.to_value, slab.rate,slab.age_group FROM it_slab slab WHERE slab.fin_year={$fin_year}";
		$result = mysqli_query ( $this->_connection, $stmt ); // Procedure Gives who ever employees affected
		$i = 0;
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			if ($row ['age_group'] == "G") {
				$this->it_slabs ["G"] [] = $row;
			} elseif ($row ['age_group'] == "A") {
				$this->it_slabs ["A"] [] = $row;
			} elseif ($row ['age_group'] == "S") {
				$this->it_slabs ["S"] [] = $row;
			}
		}
		mysqli_free_result ( $result );
		
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
	}
	private function loadEpfProperties() {
		$stmt = "SELECT cd.max_employee_share,cd.employer_share,cd.max_employer_share,cd.is_both_contribution,  
				 cd.employee_share,cd.is_admin_charges,cd.admin_charges ,cd.deduce_in
				 FROM company_deductions cd INNER JOIN  company_pay_structure ps
				 ON cd.deduction_id=ps.pay_structure_id
				 AND ps.display_flag=1 AND cd.deduction_id='c_epf'";
		$result = mysqli_query ( $this->_connection, $stmt ); // Procedure Gives who ever employees affected
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		$this->epf_properties = $row;
		mysqli_free_result ( $result );
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
	}
	private function loadMappedExemptions() {
		$id = "";
		$sumId = "";
		$leaseId = "";
		$caseText = "";
		$anotherSumText = "";
		$selectText = "";
		$remainigMonth = "";
		$ifnulltext = "";
		$arrersText = "";
		$pastcaseText = "";
		$presentcaseText = "";
		$projeacetedcaseText = "";
		$arrerscaseText = "";
		$stmt = "SELECT  pay_structure_id FROM company_pay_structure WHERE exemption_id!=''";
		$result = mysqli_query ( $this->_connection, $stmt ); // Procedure Gives who ever employees affected
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$id .= 'tot_' . $row ['pay_structure_id'] . '+';
			$sumId .= 'SUM(tot_' . $row ['pay_structure_id'] . ') tot_' . $row ['pay_structure_id'] . ',';
			$leaseId .= 'LEAST(e_' . $row ['pay_structure_id'] . ',' . $row ['pay_structure_id'] . ') tot_' . $row ['pay_structure_id'] . ',';
			$pastcaseText .= 'SUM(CASE WHEN ps.pay_structure_id = "' . $row ['pay_structure_id'] . '" THEN LEAST(pe.exemption_amount,py.' . $row ['pay_structure_id'] . ') END) e_' . $row ['pay_structure_id'] . ',';
			$presentcaseText .= 'SUM(CASE WHEN ps.pay_structure_id = "' . $row ['pay_structure_id'] . '" THEN LEAST(pe.exemption_amount,pp.' . $row ['pay_structure_id'] . ') END) e_' . $row ['pay_structure_id'] . ',';
			$projeacetedcaseText .= 'SUM(CASE WHEN ps.pay_structure_id = "' . $row ['pay_structure_id'] . '" THEN LEAST( pe.exemption_amount,s.' . $row ['pay_structure_id'] . ')*' . $this->remaining_months . ' END) e_' . $row ['pay_structure_id'] . ',';
			$arrerscaseText .= 'SUM(CASE WHEN ps.pay_structure_id = "' . $row ['pay_structure_id'] . '" THEN LEAST( pe.exemption_amount,a.' . $row ['pay_structure_id'] . ')*' . $this->remaining_months . ' END) e_' . $row ['pay_structure_id'] . ',';
			$anotherSumText .= 'IFNULL(SUM(py.' . $row ['pay_structure_id'] . '),0)/3 ' . $row ['pay_structure_id'] . ',';
			$selectText .= '(pp.' . $row ['pay_structure_id'] . '),';
			$remainigMonth .= '(s.' . $row ['pay_structure_id'] . '*' . $this->remaining_months . ') ' . $row ['pay_structure_id'] . ',';
			$arrersText .= '(a.' . $row ['pay_structure_id'] . '*' . $this->remaining_months . ') ' . $row ['pay_structure_id'] . ',';
			$ifnulltext .= 'IFNULL(SUM(a.' . $row ['pay_structure_id'] . '),0)' . $row ['pay_structure_id'] . ',';
		}
		echo $query = "SELECT ROUND((" . substr ( $id, 0, - 1 ) . "),2)exmpt_value FROM (
				SELECT employee_id," . substr ( $sumId, 0, - 1 ) . " FROM ( SELECT employee_id," . substr ( $leaseId, 0, - 1 ) . "
				FROM (SELECT py.employee_id," . $pastcaseText . $anotherSumText . " 'Past' duration
                FROM company_pay_structure ps
                INNER JOIN company_pay_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN payroll py
				ON py.month_year BETWEEN ? AND ?
				INNER JOIN employee_personal_details pd
				ON py.employee_id = pd.employee_id
				WHERE py.employee_id =? )z
                UNION ALL
                SELECT employee_id," . substr ( $leaseId, 0, - 1 ) . " FROM (
                SELECT pp.employee_id," . $presentcaseText . $selectText . " 'Present'
                FROM company_pay_structure ps
				INNER JOIN company_pay_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN payroll_preview_temp pp
				ON pp.employee_id = ?)a
				UNION ALL
                SELECT employee_id," . substr ( $leaseId, 0, - 1 ) . " FROM ( SELECT s.employee_id," . $projeacetedcaseText . $remainigMonth . " 'Projected'
				FROM company_pay_structure ps
				INNER JOIN company_pay_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN employee_salary_details s
				ON s.employee_id= ?)a
				UNION ALL
                SELECT employee_id," . substr ( $leaseId, 0, - 1 ) . "
				FROM (
                SELECT a.employee_id," . $arrerscaseText . $arrersText . " 'arrears'
				FROM company_pay_structure ps
				INNER JOIN company_pay_exemptions pe
				ON ps.exemption_id = pe.exemption_id AND ps.exemption_id !=''
				LEFT JOIN arrears a
				ON a.month_year BETWEEN ? AND ?
				WHERE a.employee_id= ? )q ) e)s;";
		$this->exemptionText = $query;
	}
	public function loadYearlyValues($employee_id, $fin_year_start, $prev_month_end, $itRow) {
		if ($itRow ['last_working_month'] == $this->current_month) {
			// $remaining_months = 0;
			$remaining_months = 0;
		} else {
			$remaining_months = $this->remaining_months;
		}
		$query = "SELECT t.employee_id,t.employee_city,SUM(t.basic) basic ,SUM(t.hra) hra,SUM(t.gross) gross ,it FROM (
		SELECT p.employee_id,p.employee_city,IFNULL(SUM(py.basic),0) basic,
		IFNULL(SUM(py.c_hra),0) hra,
		IFNULL(SUM(py.gross_salary),0) gross,
		IFNULL(SUM(py.c_it),0) it,
		'Past' duration FROM employee_personal_details p
    LEFT JOIN  payroll py 
		ON p.employee_id=py.employee_id 
    AND py.month_year BETWEEN ? AND ?
		WHERE p.employee_id =?
		UNION ALL
		SELECT pp.employee_id,NULL,SUM(pp.basic),SUM(pp.c_hra),SUM(pp.gross_salary) gross ,0,'Current' FROM
        payroll_preview_temp pp
		WHERE pp.employee_id =?
		UNION ALL
	    SELECT a.employee_id,NULL,IFNULL(SUM(a.basic),0),IFNULL(SUM(a.c_hra),0),0,0,'arrears' FROM arrears a
	    WHERE  a.month_year BETWEEN ? AND ? AND a.employee_id =?
        UNION ALL
		SELECT s.employee_id,NULL,s.basic*{$remaining_months},s.c_hra*{$remaining_months},SUM(s.employee_salary_amount)*{$remaining_months} ,0,'Projected'
		FROM employee_salary_details s WHERE s.employee_id = ?) t";
		$stmt = mysqli_prepare ( $this->_connection, $query );
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $fin_year_start, $prev_month_end, $employee_id, $employee_id, $fin_year_start, $prev_month_end, $employee_id, $employee_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $employee_id, $employee_city, $basic, $hra, $gross, $it );
		mysqli_stmt_fetch ( $stmt );
		$this->employee_city = $employee_city;
		$this->yearly_basic = $basic;
		$this->yearly_hra = $hra;
		$this->yearly_gross = $gross;
		$this->tax_paid = $it;
		mysqli_stmt_free_result ( $stmt );
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		
		if (is_array ( $this->epf_properties )) {
			$summ = str_replace ( ",", "+", $this->epf_properties ['deduce_in'] ); // sum all the deduce in columns in salary details
			$query = "SELECT ($summ) as deductableAmt,pf_limit,employee_salary_amount FROM employee_salary_details WHERE employee_id = '{$employee_id}'";
			if ($result = mysqli_query ( $this->_connection, $query )) {
				$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
				$this->epf_deductable_amount = $row ['deductableAmt'];
				$this->epf_limit = $row ['pf_limit'];
				$this->master_gross_salary = $row ['employee_salary_amount'];
				mysqli_free_result ( $result );
			} else {
				die ( mysqli_error ( $this->_connection ) );
			}
		} else {
			$query = "SELECT employee_salary_amount FROM employee_salary_details WHERE employee_id = '{$employee_id}'";
			if ($result = mysqli_query ( $this->_connection, $query )) {
				$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
				$this->master_gross_salary = $row ['employee_salary_amount'];
				mysqli_free_result ( $result );
			} else {
				die ( mysqli_error ( $this->_connection ) );
			}
		}
		
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		if ($remaining_months == 0) {
			$this->epf_deductable_amount = 0;
		}
		
		$query = "CALL CALC_PT('$employee_id','$this->payroll_month_year','$this->fin_year','$this->current_month_gross','$this->master_gross_salary','IT','$remaining_months',@ptValue)";
		if (mysqli_multi_query ( $this->_connection, $query )) {
			if ($result1 = mysqli_use_result ( $this->_connection )) {
				while ( $row = mysqli_fetch_array ( $result1, MYSQLI_ASSOC ) ) {
					$this->yearly_pt = isset ( $row ['pt_rate'] ) ? $row ['pt_rate'] : 0;
					// array_push($json, $row['']);
				}
				
				do {
					if ($result = mysqli_store_result ( $this->_connection )) {
						mysqli_free_result ( $result );
					}
				} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
			}
		}
		
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		return array (
				"city" => $employee_city,
				"yearlyBasic" => $basic,
				"yearlyHra" => $hra,
				"yearlyGross" => $gross,
				"taxPaid" => $it 
		);
	}
	private function loadYearlyEpf() {
		mysqli_stmt_init ( $this->_connection );
		$max_employee_share = $this->epf_properties ['max_employee_share'];
		$max_employer_share = $this->epf_properties ['max_employer_share'];
		$is_both_contribution = $this->epf_properties ['is_both_contribution'];
		$employee_share = $this->epf_properties ['employee_share'];
		$employer_share = $this->epf_properties ['employer_share'];
		$epf_limit = $this->epf_limit;
		$is_admin_charges = $this->epf_properties ['is_admin_charges'];
		$admin_charges = $this->epf_properties ['admin_charges'];
		$query = "CALL CALC_EPF('$this->epf_deductable_amount','$employee_share','$max_employee_share',
		                     '$is_both_contribution','$employer_share','$max_employer_share','$is_admin_charges','$admin_charges',
		                     '$epf_limit','$this->employee_id','$this->payroll_month_year',
		                     '$this->fin_year','$this->remaining_months','IT',@epfValue);";
		if (mysqli_multi_query ( $this->_connection, $query )) {
			if ($resultRow = mysqli_use_result ( $this->_connection )) {
				$row = mysqli_fetch_array ( $resultRow, MYSQLI_ASSOC );
				$this->yearly_epf = $row ['epfValue'];
				// $this->yearly_epf =$row['epfValue']; corection
				do {
					if ($result = mysqli_store_result ( $this->_connection )) {
						mysqli_free_result ( $result );
					}
				} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
			}
		}
		return $this->yearly_epf;
	}
	private function otherIncome($itRow) {
		$other_income = $itRow ['int_inc_app'] + $itRow ['lottery_app'] + $itRow ['letting_app'] + $itRow ['land_app'] + $itRow ['other_app'];
		$this->other_income = $other_income;
		return $other_income;
	}
	private function housePropertyIncome($itRow) {
		$inter_app = min ( $itRow ['inter_app'], 200000 );
		$annRent_app = $itRow ['annRent_app'] - ($itRow ['munic_app'] + $itRow ['unreal_app']);
		$annRent = ($annRent_app * (70 / 100)) - $itRow ['housing_app'];
		$house_loan = $annRent + $inter_app;
		$this->house_prop_inc = $house_loan;
		return $house_loan;
	}
	private function salariedIncome($row) {
		$this->salaried_income = $this->yearly_gross - $this->exe_hra ( $row ) - $this->exe_10a ( $row ) - $this->mappedTaxExemptions - $this->yearly_pt;
		return $this->salaried_income;
	}
	private function other_mapped_exe_10a($employee_id) {
		$stmt = mysqli_prepare ( $this->_connection, $this->exemptionText );
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $this->financial_year_start, $this->prev_month_end, $employee_id, $employee_id, $employee_id, $this->financial_year_start, $this->prev_month_end, $employee_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_bind_result ( $stmt, $mappedTaxExemptions );
		mysqli_stmt_fetch ( $stmt );
		return $this->mappedTaxExemptions = $mappedTaxExemptions;
	}
	private function grossTotalIncome($itRow) {
		// other income + total income +house loan income etc..
		$this->gross_total_income = $this->salaried_income + $this->otherIncome ( $itRow ) + $this->housePropertyIncome ( $itRow );
		return $this->gross_total_income;
	}
	private function exe_10a($row) {
		$this->exe_medical = min ( $row ['medical_app'], 15000 );
		
		$this->exe_lta = $row ['lta_app'];
		$months_worked = $this->monthsFromDate ( $row ['employee_doj'], $this->financial_year_end, true );
		$trc_app_month = min ( ($months_worked), 12 ) * 1600;
		$this->exe_travel_con = min ( $row ['trc_app'], $trc_app_month );
		$this->exe_other_10a = $row ['others_app'];
		$this->exeOther10a = ($this->exe_medical + $this->exe_lta + $this->exe_travel_con + $this->exe_other_10a + $this->mappedTaxExemptions);
		return $this->exeOther10a;
	}
	private function exe_hra($row) {
		// hra past present future value calc - > hra, basic yearly
		$hra_factor = in_array ( $this->employee_city, $this->hraMetroCities ) ? 0.5 : 0.4;
		$basicWitFactor = $this->yearly_basic * $hra_factor;
		$r_paid = ($row ['jan_app'] + $row ['feb_app'] + $row ['mar_app'] + $row ['apr_app'] + $row ['may_app'] + $row ['jun_app'] + $row ['jul_app'] + $row ['aug_app'] + $row ['sep_app'] + $row ['oct_app'] + $row ['nov_app'] + $row ['dec_app']);
		$tenPercentBasic = (10 / 100) * ($this->yearly_basic);
		// rent_paid
		$rent_paid = MAX ( 0, ($r_paid - $tenPercentBasic) );
		return $this->exe_hra = min ( $this->yearly_hra, $rent_paid, $basicWitFactor );
	}
	private function exe_80c($row) {
		$qproducts = $row ['80c_fd_app'] + $row ['80c_tution_app'] + $row ['80c_nsc_app'] + $row ['80c_sukanya_app'] + $row ['80c_inf_app'] + $row ['80c_vpf_app'] + $row ['80c_repa_app'] + $row ['80c_lic_app'] + $row ['80c_sip_app'] + $row ['80c_mut_app'] + $row ['80c_nps_app'] + $row ['80c_elss_app'] + $row ['80c_bonds_app'] + $row ['prev_pf_app'] + $this->loadYearlyEpf ();
		$ded_80c_sub = min ( $qproducts, 150000 );
		$ccd1b_nps_app = min ( $row ['80ccd1b_nps_app'], 50000 );
		if ($this->yearly_gross > 1200000) {
			$rgess_app = min ( ($row ['80ccg_rgess_app'] / 2), 25000 );
		} else {
			$rgess_app = "0";
		}
		$ded_80c = $ded_80c_sub + $ccd1b_nps_app + $rgess_app;
		$this->exe_80c = $ded_80c;
		return $ded_80c;
	}
	private function exe_80e($row) {
		$edu_app = $row ['80e_edu_app'];
		$this->exe_80e = $edu_app;
		return $edu_app;
	}
	private function exe_80d($row) {
		$employee_age = $this->current_year - $row ['employee_dob'];
		$father_age = $this->current_year - $row ['father_dob'];
		if ($employee_age >= 60) {
			$medself = $row ['80d_medself_app'] + $row ['80d_prev_app'];
			$medself_app = min ( $medself, 30000 );
		} else {
			$medself = $row ['80d_medself_app'] + $row ['80d_prev_app'];
			$medself_app = min ( $medself, 25000 );
		}
		if ($father_age >= 60) {
			$min_parent = min ( $row ['80d_medpar_app'], 30000 );
		} else {
			$min_parent = min ( $row ['80d_medpar_app'], 25000 );
		}
		if ($row ['80dd_med_app'] > 0) {
			$med_app = min ( $row ['80dd_med_app'], 75000 );
		} else {
			$med_app = "0";
		}
		
		if ($row ['80dd_medsev_app'] > 0) {
			$medsev_app = min ( $row ['80dd_medsev_app'], 100000 );
		} else {
			$medsev_app = "0";
		}
		
		if ($employee_age >= 60) {
			$med1_app = min ( $row ['80ddb_med1_app'], 80000 );
		} else {
			$med1_app = min ( $row ['80ddb_med1_app'], 40000 );
		}
		$ded_80d = $medself_app + $min_parent + $med_app + $medsev_app + $med1_app;
		$this->exe_80d = $ded_80d;
		return $ded_80d;
	}
	private function exe_80g($row) {
		$national_app = $row ['80g_don1_app'];
		$trust_app = ($row ['80g_don2_app']) * (50 / 100);
		$this->exe_80e = $row ['80e_edu_app'];
		$income = $this->gross_total_income - ($this->exe_80c - $this->exe_80d - $row ['80e_edu_app']);
		$family_sub = ($income) * (10 / 100);
		
		$donation = $row ['80g_don3_app'] + $row ['80g_don4_app'] / 2;
		$donation_cal = min ( $donation, $family_sub );
		$political = $row ['80g_don6_app'];
		$ded_80g = $national_app + $trust_app + $donation_cal + $political + $row ['80g_don5_app'];
		$sav_app = min ( $row ['80tta_sav_app'], 10000 );
		$dis1_app = min ( $row ['80u_dis1_app'], 75000 );
		$dis2_app = min ( $row ['80u_dis2_app'], 100000 );
		$this->exe_deductions_other = $sav_app + $dis1_app + $dis2_app;
		$this->exe_80g = $ded_80g;
		return $ded_80g;
	}
	private function yearlyTax($row) {
		$exemptions = $this->exe_80c ( $row ) + $this->exe_80d ( $row ) + $this->exe_80e ( $row ) + $this->exe_80g ( $row ) + $this->exe_deductions_other + $row ['prev_pt_app'];
		
		$taxable_income = $this->gross_total_income - $exemptions; // 303600
		$this->taxable_inc = $taxable_income;
		$this->gross_total_income;
		$tax = 0;
		$slabs = null;
		if ($this->employee_age < 60) {
			// G
			$slabs = $this->it_slabs ["G"];
		} elseif ($this->employee_age >= 60 && $this->employee_age < 80) {
			// A
			$slabs = $this->it_slabs ["A"];
		} elseif ($this->employee_age >= 80) {
			// S
			$slabs = $this->it_slabs ["S"];
		}
		foreach ( $slabs as $slab ) {
			// array("from_value":,"to_value":,"rate")
			/*
			 * 0 - 2,50,000 0%
			 * 2,50,001 - 5,00,000 10%
			 * 5,00,001 - 10,00,000 20%
			 * 10,00,001 - 30%
			 *
			 */
			
			$diff_from_to = max ( $slab ['to_value'] - $slab ['from_value'], 0 );
			if ($taxable_income > $diff_from_to && $diff_from_to != 0) {
				$tax += $diff_from_to * ($slab ['rate'] / 100);
				$taxable_income = $taxable_income - $diff_from_to;
			} else {
				$tax += $taxable_income * ($slab ['rate'] / 100);
				$taxable_income = 0;
				continue;
			}
		}
		$sur_charge = $this->yearly_gross > 10000000 ? $tax * 0.10 : 0;
		$tax += $sur_charge;
		$this->relief = $this->taxable_inc < $this->rebate_limit ? $this->rebate_amount : 0;
		$tax = $tax - $this->relief;
		$edu_chess = $tax * 0.02;
		$higher_edu_chess = $tax * 0.01;
		$this->tax = MAX ( 0, $tax );
		$tax = $tax + $edu_chess + $higher_edu_chess;
		$this->ec = MAX ( 0, $edu_chess );
		$this->shec = MAX ( 0, $higher_edu_chess );
		$this->surcharge = MAX ( 0, $sur_charge );
		return $tax;
	}
	private function taxPayable($itRow) {
		$this->tax_payable = max ( 0, $this->yearlyTax ( $itRow ) - $itRow ['prev_tax_app'] - $itRow ['old_tax_paid'] - $this->tax_paid );
		$tds = $this->tax_payable / ($this->remaining_months + 1);
		$this->tds = ROUND ( max ( $tds, 0 ) );
		return $this->tds;
	}
	private function updateITtable() {
		do {
			if ($result = mysqli_store_result ( $this->_connection )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->_connection ) && mysqli_next_result ( $this->_connection ) );
		$stmt = mysqli_prepare ( $this->_connection, "UPDATE employee_income_tax SET other_income=?,exe_hra=?,exe_lta=?,exe_oth=?,total_inc=?,
		house_prop_inc=?,ded_80e=?,ded_80c=?,gti=?,ded_80d=?,ded_80g=?,ded_other=?,taxable_inc=?,relief=?,tax=?,ec=?,shec=?,surcharge=?,
		tax_payable=?,tds=?,tax_paid=?,updated_by=?,taxon_employment=? WHERE employee_id =? AND year=? " );
		mysqli_stmt_bind_param ( $stmt, 'sssssssssssssssssssssssss', $this->other_income, $this->exe_hra, $this->exe_lta, $this->exeOther10a, $this->salaried_income, $this->house_prop_inc, $this->exe_80e, $this->exe_80c, $this->gross_total_income, $this->exe_80d, $this->exe_80g, $this->exe_deductions_other, $this->taxable_inc, $this->relief, $this->tax, $this->ec, $this->shec, $this->surcharge, $this->tax_payable, $this->tds, $this->tax_paid, $this->updated_by, $this->yearly_pt, $this->employee_id, $this->fin_year );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->_connection );
		mysqli_stmt_free_result ( $stmt );
	}
	public function monthsFromDate($start_date, $end_date, $includeCurrentMonth) {
		// calculate how many months between dates
		$curr_month = $includeCurrentMonth ? 1 : 0;
		$datetime1 = date_create ( $start_date );
		$datetime2 = date_create ( $end_date );
		$interval = date_diff ( $datetime1, $datetime2 );
		return (($interval->format ( '%y' ) * 12) + $interval->format ( '%m' )) + $curr_month;
	}
}
?>