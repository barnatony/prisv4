<?php
class Employee extends Model {

	function __construct($emp_id='') {
		parent::__construct('employee_id','employee_work_details'); //primary key = employee_id; tablename = employee_work_details
		
		//if($table_name=="employee_work_details"){
		$this->rs['id'] = '';
		$this->rs['employee_id'] = '';
		$this->rs['employee_name'] = '';
		$this->rs['employee_lastname'] = '';
		$this->rs['employee_doj'] = '';
		$this->rs['employee_confirmation_date'] = '';
		$this->rs['employee_probation_period'] = '';
		$this->rs['status_id'] = '';
		$this->rs['branch_id'] = '';
		$this->rs['department_id'] = '';
		$this->rs['transfer_id'] = '';
		$this->rs['promotion_id'] = '';
		$this->rs['designation_id'] = '';
		$this->rs['design_effects_from'] = '';
		$this->rs['branch_effects_from'] = '';
		$this->rs['employee_reporting_person'] = '';
		$this->rs['employee_emp_pf_no'] = '';
		$this->rs['employee_emp_uan_no'] = '';
		$this->rs['employee_emp_esi_no'] = '';
		$this->rs['payment_mode_id'] = '';
		$this->rs['notice_period'] = '';
		$this->rs['notice_date'] = '';
		$this->rs['dateofexit'] = '';
		$this->rs['off_ltr_proof'] = '';
		$this->rs['confirm_ltr_proof'] = '';
		$this->rs['contract_ltr_proof'] = '';
		$this->rs['shift_id'] = '';
		$this->rs['weekend_id'] = '';
		$this->rs['confirm_ltr_issue_dt'] = '';
		$this->rs['contract_ltr_issue_dt'] = '';
		$this->rs['off_ltr_issue_dt'] = '';
		$this->rs['enabled'] = '';
		$this->rs['team_id'] = '';
		$this->rs['payroll_enabled'] = '';
		$this->rs['updated_on'] = '';
		$this->rs['updated_by'] = '';
		$this->rs['depart_effects_from'] = '';
		
		//}//elseif()
		
		//if ($emp_id)
			//$this->retrieve($emp_id);
	}
	
	//to get employee whole view
	function _getEmployeeView($wherewhat,$bindings,$limitStr){
		
		$employees=$this->joined_select("w.employee_id,p.employee_image,p.employee_email,p.employee_mobile,s.employee_salary_amount,
										 CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,p.employee_gender,DATE_FORMAT(w.employee_doj,'%b-%d,%Y') employee_doj,
										 des.designation_name,b.branch_name,d.department_name,w.enabled,IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Yrs'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Yr'))) experience",
										 "employee_work_details  w
											INNER JOIN employee_personal_details p
											ON w.employee_id = p.employee_id
											INNER JOIN employee_salary_details s
											ON w.employee_id = s.employee_id
											LEFT JOIN company_designations des
											ON w.designation_id = des.designation_id
											LEFT JOIN company_departments d
											ON w.department_id=d.department_id
											LEFT JOIN company_branch b
										ON w.branch_id=b.branch_id",$wherewhat,$bindings,"","w.employee_id ASC ",$limitStr);
			
		return $employees;
	}
	
	
	function _getEmployeePersonelDetails($employee_id){
		//to get specific employee Personal Details
		$employee=$this->joined_select("w.employee_id , w.employee_name,w.employee_lastname,p.employee_gender,p.employee_mobile,
				DATE_FORMAT(p.employee_dob,'%d/%m/%Y') employee_dob,
				CASE WHEN (    DATE_FORMAT(p.father_dob,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE
				DATE_FORMAT(p.father_dob,'%d/%m/%Y') END  AS father_dob,
				p.employee_email,p.employee_father_name,p.emp_mother_name
				,p.employee_image,p.employee_marital_status,
				(select id from payroll where employee_id='$employee_id' limit 0,1)  id,
				CASE WHEN (    DATE_FORMAT(p.employee_marriagedate,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE
				DATE_FORMAT(p.employee_marriagedate,'%d/%m/%Y') END   employee_marriagedate,
				CASE WHEN (    DATE_FORMAT(w.notice_date,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE
				DATE_FORMAT(w.notice_date,'%d/%m/%Y') END  AS notice_date,
				CASE WHEN (    DATE_FORMAT(w.dateofexit,'%d/%m/%Y')= '00/00/0000') THEN NULL ELSE
				DATE_FORMAT(w.dateofexit,'%d/%m/%Y') END  AS dateofexit,
				p.employee_nationality,p.employee_international,p.employee_blood_group,p.employee_pc,p.employee_pan_proof,p.
				employee_pan_no,p.employee_id_proof_type,p.employee_aadhaar_proof,p.
				employee_aadhaar_id,p.employee_id_proof_no,p.employee_id_proof_expiry,p.
				employee_bank_name,p.employee_acc_no,p.employee_bank_ifsc,p.employee_bank_proof,p.
				employee_bank_branch,p.employee_id_proof,p.employee_build_name,p.employee_area,p.
				employee_pin_code,p.employee_city,p.employee_street,p.employee_state,p.employee_phone,p.
				employee_password,p.permanent_emp_bulidname,p.permanent_emp_area,p.permanent_emp_city,p.
				permanent_emp_pincode,p.permanent_emp_state,p.permanent_emp_country,p.emp_country,p.
				emp_sslc_school,p.emp_sslc_board,p.emp_ug_institute_name,p.emp_ug_university,p.employee_ug_proof,p.
				employee_sslc_proof,p.emp_pg_institute_name,p.emp_pg_university,p.employee_pg_proof,p.
				emp_sslc_marks,p.emp_hsc_school,p.emp_hsc_board,p.emp_hsc_marks,p.emp_hsc_year,p.emp_pg_marks,p.
				emp_pg_year_passing,p.emp_ug_year_passing,p.emp_sslc_year,p.employee_hsc_proof,p.employee_pt_adddress,p.
				emp_ug_marks,w.enabled","employee_work_details w
				INNER JOIN employee_personal_details p
				ON w.employee_id = p.employee_id","w.employee_id = ?",$employee_id);
		
		return $employee;
			
	}
	
	function _getWorkDetails($employee_id){
		//to get specific employee work details
		$employee=$this->joined_select("cs.shift_name,w.shift_id,GROUP_CONCAT(wh.company_name SEPARATOR ',') company_name,
		GROUP_CONCAT(wh.`from` SEPARATOR ',') `from`,
		GROUP_CONCAT(wh.`to` SEPARATOR ',') `to`,
		GROUP_CONCAT(wh.`designation` SEPARATOR ',') `designation`,
		GROUP_CONCAT(wh.`ctc` SEPARATOR ',') `ctc`,(select id from payroll where employee_id='" . $employee_id . "' limit 0
				,1
				) as id,w.employee_id,w.employee_name,w.employee_lastname,w.employee_reporting_person,CONCAT (man.employee_name,' ',man.employee_lastname) as reporting_manager,
				DATE_FORMAT(w.employee_confirmation_date,'%d/%m/%Y') employee_confirmation_date,w.employee_probation_period,w.notice_period, br.branch_name,w.employee_emp_pf_no ,w.employee_emp_uan_no ,
				w.employee_emp_esi_no ,slab.slab_type,w.enabled, s.slab_id ,w.off_ltr_proof, slab.slab_name,pm.payment_mode_name,
				CONCAT(DATE_FORMAT(w.employee_doj,'%d/%m/%Y'),' (',IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1)>1,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 365),1),' Years'),IF(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0)<12,CONCAT(ROUND((DATEDIFF(CURDATE(),w.employee_doj) / 30),0),' Months'),CONCAT('1.0 Year'))),')') employee_doj,
				w.branch_id,w.designation_id,des.designation_name,w.shift_id,w.weekend_id,w.off_ltr_issue_dt,w.confirm_ltr_issue_dt,w.contract_ltr_issue_dt,js.status_name, w.department_id,des.designation_name,dep.department_name,w.payment_mode_id ,w.status_id,s.pf_limit,
				DATE_FORMAT(man.design_effects_from,'%d/%m/%Y') design_effects_from,DATE_FORMAT( man.branch_effects_from,'%d/%m/%Y') branch_effects_from,DATE_FORMAT( man.depart_effects_from ,'%d/%m/%Y') depart_effects_from","
				employee_work_details w
				INNER JOIN employee_salary_details s
				ON s.employee_id = w.employee_id
				LEFT JOIN company_designations des
				ON w.designation_id = des.designation_id
				LEFT JOIN company_departments dep
				ON w.department_id = dep.department_id
				LEFT JOIN company_branch br
				ON w.branch_id = br.branch_id
				LEFT JOIN company_allowance_slabs slab
				ON s.slab_id = slab.slab_id
				LEFT JOIN employee_work_details man ON
				w.employee_reporting_person = man.employee_id
				LEFT JOIN company_payment_modes pm
				ON w.payment_mode_id = pm.payment_mode_id
				LEFT JOIN company_job_statuses js
				ON w.status_id = js.status_id
				LEFT JOIN company_shifts cs
				ON w.shift_id = cs.shift_id
				LEFT JOIN emp_work_history wh on w.employee_id=wh.employee_id","w.employee_id = ?",$employee_id);
		
		return $employee;
	}
	function _letters($employee_id){
		
			$query =" SELECT trans.action_id action_id,w.employee_name,trans.affected_ids,trans.transferred_branch_id,DATE_FORMAT(trans.action_effects_from,'%d/%m/%Y') action_effects_from,'Evaluation:Transfer' letter
		FROM employee_work_details w
		LEFT JOIN comp_transfers trans
		ON w.employee_id = trans.affected_ids OR trans.action_id = w.transfer_id AND (w.branch_effects_from = trans.action_effects_from)
		WHERE w.employee_id = '$employee_id' AND action_id IS NOT NULL
		UNION
		SELECT pro.action_id action_id,w.employee_name,pro.incremented_amount,pro.promoted_desig_id,DATE_FORMAT(pro.action_effects_from,'%d/%m/%Y') action_effects_from,IF(s.increment_id=w.promotion_id && pro.incremented_amount!='0|A','Evaluation:Promotion come Increment',IF(pro.incremented_amount='0|A','Evaluation:Promotion','Evaluation:Increment')) letter
		FROM employee_work_details w
		LEFT JOIN comp_promotions_increments pro
		ON w.employee_id = pro.affected_ids 
    	INNER JOIN employee_salary_details s
    	ON w.employee_id = s.employee_id AND s.increment_id = pro.action_id
		WHERE w.employee_id = '$employee_id' AND action_id IS NOT NULL 
		UNION
		SELECT action_id,employee_name,IF((gross!=his_gross && his_gross !=0),his_gross,gross) employee_salary_amount,IF((desig!=his_desig && his_desig!=''),his_desig,desig) designation_name,employee_doj,letter
		FROM (
		SELECT w.employee_id action_id,w.employee_name,s.employee_salary_amount gross,IFNULL(sh.employee_salary_amount,0) his_gross,desig.designation_name desig,IFNULL(cdh.designation_name,'') his_desig,DATE_FORMAT(w.employee_doj,'%d/%m/%Y') employee_doj ,'Offer:Confirmation' letter
				FROM employee_work_details w
				INNER JOIN company_designations desig
				ON w.designation_id = desig.designation_id
				INNER JOIN employee_salary_details s
				ON w.employee_id = s.employee_id
			    LEFT JOIN employee_salary_details_history sh
			    ON w.employee_id = sh.employee_id
			    LEFT JOIN emp_designation_history dh
			    ON w.employee_id = dh.employee_id AND dh.promotion_id = 'CREATION'
			    LEFT JOIN company_designations cdh
			    ON dh.designation_id = cdh.designation_id
				WHERE w.employee_id = '$employee_id' ORDER BY sh.effects_from ASC LIMIT 0,1) z;";
			$dbh=getdbh();

			$stmt=$dbh->query($query);
			$employee=$stmt->fetchAll(PDO::FETCH_ASSOC);
		
			return $employee;
	}
}