<?php
/*
 * ----------------------------------------------------------
 * Filename : letter.class.php
 * Classname: letter.class.php
 * Author : Rufus Jackson
 * Database : letters
 * Oper : letter Templates handling and Download
 *
 * ----------------------------------------------------------
 */
require_once (__DIR__ . "/letterTemplate.class.php");
require_once (dirname(dirname ( dirname ( __FILE__ ) ))  ."/js/phpWord/PHPWord.php");
require_once (dirname (dirname ( dirname ( __FILE__ ) ))  ."/js/phpWord/simplehtmldom/simple_html_dom.php");
require_once (dirname (dirname ( dirname ( __FILE__ ) ))  ."/js/phpWord/htmltodocx_converter/h2d_htmlconverter.php");
require_once (dirname (dirname ( dirname ( __FILE__ ) )) ."/js/phpWord/PHPWord/styles.inc");

class Letter {
	/* Member variables */
	var $letterName;
	var $letterTitle;
	var $letterContent;
	var $updated_by;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		// /mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Update Letter  */
		function update() {
		     $letterContents = str_replace("&#39;", "'",$this->letterContent);
			 $stmt = mysqli_prepare ( $this->conn, "UPDATE letter_templates SET letter_content = ?
														WHERE letter_name = ?" );
				$result=mysqli_stmt_bind_param ( $stmt, 'ss', $letterContents,$this->letterName);
				$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
				
				if($result===true)
					return array("result"=>true,"data"=>"Updated Successfully");
				else 
					return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
			}
	
	function getHtml(){
		$ajson = array ();
		if($result = mysqli_query ( $this->conn, "SELECT *
													FROM letter_templates lt
													WHERE lt.letter_name = '{$this->letterName}'" )){
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $ajson, $row );
				$this->letterName = $row['letter_name'];
				$this->letterContent=$row['letter_content'];
				$this->letterTitle = $row['letter_title'];
			}
			return ((empty( $ajson) )?array("result"=>true,"data"=>'Letter Can\'t be Loaded' ):array("result"=>true,"data"=>$ajson));
		
		}else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	function select(){
		$ajson = array ();
		if($result = mysqli_query ( $this->conn, "SELECT lt.letter_name,lt.letter_title
												FROM letter_templates lt
												WHERE lt.enabled = 1 ORDER BY lt.letter_title ASC" )){
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
					array_push ( $ajson, $row );
			}
				return ((empty( $ajson) )?array("result"=>true,"data"=>'No Letters Found'):array("result"=>true,"data"=>$ajson));
		
		}else
			return array("result"=>false,"data"=>mysqli_error ( $this->conn ));
	}
	
	function download($id, $letterName) {
		$a_json = array ();

			if(!$letterName)
				die(false);
			
			$this->getHtml();
			
			switch($letterName){
				case "Welcome:Letter":
					$query = " SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,w.employee_name,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name,employee_doj
				    FROM employee_work_details w
				    LEFT JOIN companies c
				    ON w.company_id = c.company_id
				    LEFT JOIN company_details cmd
				    ON w.company_id = cmd.company_id
				    LEFT JOIN employee_personal_details p
				    ON w.employee_id = p.employee_id
				    WHERE w.employee_id = '$id'";
					
				break;
				case "Offer:Letter":
					$query ="SELECT employee_id,employee_name,employee_doj,IF((new_designation!=old_designation && old_designation !=''),old_designation,new_designation)designation,department,IF((new_sal!=old_sal && old_sal!=0),FORMAT(old_sal,0,'en_IN'),FORMAT(new_sal,0,'en_IN')) salary,number_to_words(IF((new_sal!=old_sal && old_sal!=0),old_sal,new_sal)) new_salary_words,days,company_name 
							FROM (SELECT w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,DATE_FORMAT(w.employee_doj,'%d-%m-%Y') employee_doj,des.designation_name new_designation,IFNULL(cdh.designation_name,'') old_designation, dep.department_name department,
							DATE_FORMAT(s.effects_from,'%d-%m-%Y') effects_from,s.employee_salary_amount new_sal,
							IFNULL(sh.employee_salary_amount,0) old_sal,w.employee_probation_period days,
							IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name 
							FROM employee_work_details w 
							INNER JOIN employee_salary_details s 
							ON w.employee_id = s.employee_id 
							INNER JOIN company_designations des 
							ON w.designation_id = des.designation_id 
							INNER JOIN company_departments dep 
							ON w.department_id = dep.department_id
							INNER JOIN company_branch b 
							ON w.branch_id = b.branch_id 
							LEFT JOIN employee_personal_details p 
							ON w.employee_id = p.employee_id
							LEFT JOIN employee_salary_details_history sh 
							ON w.employee_id = sh.employee_id 
							LEFT JOIN emp_designation_history dh 
							ON w.employee_id = dh.employee_id AND dh.promotion_id = 'CREATION' 
							LEFT JOIN company_designations cdh 
							ON dh.designation_id = cdh.designation_id 
							LEFT JOIN companies c 
							ON w.company_id = c.company_id 
							JOIN company_details cmd 
							WHERE w.employee_id = '$id') z LIMIT 0,1;";
				
					break;
				case "Joining:Letter":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,cd.designation_name designation,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name,IF(c.company_area IS NULL,cmd.company_area,c.company_area) company_area,DATE_FORMAT(employee_doj,'%d-%m-%Y')employee_doj,
					off_ltr_issue_dt,DATE_FORMAT(CURDATE(),'%b %d, %Y') date
				    FROM employee_work_details w
				    LEFT JOIN employee_personal_details p
				    ON w.employee_id = p.employee_id
				    LEFT JOIN companies c
				    ON w.company_id = c.company_id
				    JOIN company_details cmd
				    LEFT JOIN company_designations cd
				    ON w.designation_id = cd.designation_id
				    WHERE w.employee_id = '$id'";
					break;
				case "Bank Account Opening Letter":
					$query =" SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,w.employee_name,p.employee_bank_name,p.employee_bank_branch,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name,
					CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',')),CONCAT(c.company_build_name ,',')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',')),CONCAT(c.company_street ,',')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',')),CONCAT(c.company_area ,',')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',')),CONCAT(c.company_city ,',')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_single_line, 
					CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',<br>')),CONCAT(c.company_build_name ,',<br>')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',<br>')),CONCAT(c.company_street ,',<br>')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',<br>')),CONCAT(c.company_area ,',<br>')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',<br>')),CONCAT(c.company_city ,',<br>')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_multi_line,
					DATE_FORMAT(w.employee_doj,'%d-%m-%Y') employee_doj,DATE_FORMAT(CURDATE(),'%b %d, %Y') date,desig.designation_name designation
				    FROM employee_work_details w
				    LEFT JOIN employee_personal_details p
				    ON w.employee_id = p.employee_id
				    LEFT JOIN companies c
				    ON w.company_id = c.company_id
				    LEFT JOIN company_designations desig
				    ON w.designation_id = desig.designation_id
				    JOIN company_details cmd
				    WHERE w.employee_id = '$id'";
					break;
				case "Bonafide":
					$query =" SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id, 
					CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,', ')), IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,', ')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,', ')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,', ')),IF(p.employee_city IS NULL,'',CONCAT(p.employee_city,', ')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code))employee_single_line_address,
                    CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,',<br>')),IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,',<br>')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,',<br>')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,',<br>')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code)) multi_line_address,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name,
					CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',')),CONCAT(c.company_build_name ,',')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',')),CONCAT(c.company_street ,',')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',')),CONCAT(c.company_area ,',')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',')),CONCAT(c.company_city ,',')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_single_line,
					CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',<br>')),CONCAT(c.company_build_name ,',<br>')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',<br>')),CONCAT(c.company_street ,',<br>')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',<br>')),CONCAT(c.company_area ,',<br>')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',<br>')),CONCAT(c.company_city ,',<br>')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_multi_line,
					w.employee_name,cd.designation_name,DATE_FORMAT(w.employee_doj,'%d-%m-%Y')employee_doj,DATE_FORMAT(CURDATE(),'%b %d, %Y') date
				    FROM employee_work_details w
				    LEFT JOIN employee_personal_details p
				    ON w.employee_id = p.employee_id
				    LEFT JOIN companies c
				    ON w.company_id = c.company_id
				    JOIN company_details cmd
				    LEFT JOIN company_designations cd
					ON w.designation_id = cd.designation_id
				    WHERE w.employee_id = '$id'";
					break;
				case "Employee Information Sheet":
					$query =" SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id employee_id,w.employee_name,
					CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,',')),IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,',')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,',')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,',')),IF(p.employee_city IS NULL,'',CONCAT(p.employee_city,',')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code))employee_single_line_address,
                    CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,',<br>')),IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,',<br>')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,',<br>')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,',<br>')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code)) multi_line_address,
                    CONCAT(IF(p.permanent_emp_bulidname IS NULL,'',CONCAT(p.permanent_emp_bulidname,',')),IF(p.permanent_emp_area IS NULL,'',CONCAT(p.permanent_emp_area,',')),IF(p.permanent_emp_dist IS NULL,'',CONCAT(p.permanent_emp_dist,',')),IF(p.permanent_emp_pincode IS NULL,'',p.permanent_emp_pincode))emp_single_line_Permaddress,
                    CONCAT(IF(p.permanent_emp_bulidname IS NULL,'',CONCAT(p.permanent_emp_bulidname,',<br>')),IF(p.permanent_emp_area IS NULL,'',CONCAT(p.permanent_emp_area,',<br>')),IF(p.permanent_emp_dist IS NULL,'',CONCAT(p.permanent_emp_dist,',<br>')),IF(p.permanent_emp_pincode IS NULL,'',p.permanent_emp_pincode)) emp_multi_line_Permaddress,
                    DATE_FORMAT(w.employee_doj,'%d-%m-%Y') employee_doj,DATE_FORMAT(p.employee_dob,'%d-%m-%Y') employee_dob,p.employee_gender,p.employee_pan_no,p.employee_personal_email,cd.designation_name,p.employee_build_name,IF(p.employee_street,'NA','')employee_street,
					p.employee_area,p.employee_district,p.employee_pin_code,p.employee_personal_mobile,p.employee_marital_status,p.employee_marriagedate,
					p.employee_personal_email,p.employee_blood_group,p.employee_pan_no,p.permanent_emp_bulidname,p.permanent_emp_area,p.permanent_emp_dist,p.permanent_emp_pincode
					FROM employee_work_details w
					LEFT JOIN employee_personal_details p
				  	ON w.employee_id = p.employee_id
          			LEFT JOIN company_designations cd
					ON w.designation_id = cd.designation_id
					WHERE w.employee_id = '$id'";
					break;
				case "New Joinee Checklist":
					$query =" SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,w.employee_name,CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,',')),IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,',')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,',')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,',')),IF(p.employee_city IS NULL,'',CONCAT(p.employee_city,',')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code))employee_single_line_address,
                    CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,',<br>')),IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,',<br>')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,',<br>')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,',<br>')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code)) multi_line_address,
                    DATE_FORMAT(w.employee_doj,'%d-%m-%Y') employee_doj,DATE_FORMAT(p.employee_dob,'%d-%m-%Y') employee_dob,p.employee_personal_email,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name,
                    p.employee_personal_mobile,p.employee_mobile,p.employee_marital_status,p.employee_marriagedate,
					p.employee_email,p.employee_blood_group,p.employee_pan_no,p.permanent_emp_bulidname,p.permanent_emp_area,p.permanent_emp_dist,p.permanent_emp_pincode
					FROM employee_work_details w
					LEFT JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					LEFT JOIN companies c
				    ON w.company_id = c.company_id
				    JOIN company_details cmd
				    WHERE w.employee_id = '$id'";
					break;
				case "Relieving Letter":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,w.employee_name,DATE_FORMAT(n.last_working_date,'%d %b, %Y') last_working_date,DATE_FORMAT(CURDATE(),'%b %d, %Y')date,CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',')),CONCAT(c.company_build_name ,',')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',')),CONCAT(c.company_street ,',')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',')),CONCAT(c.company_area ,',')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',')),CONCAT(c.company_city ,',')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_single_line, 
					CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',<br>')),CONCAT(c.company_build_name ,',<br>')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',<br>')),CONCAT(c.company_street ,',<br>')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',<br>')),CONCAT(c.company_area ,',<br>')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',<br>')),CONCAT(c.company_city ,',<br>')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_multi_line,
					IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name
					FROM employee_work_details w
					LEFT JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					LEFT JOIN emp_notice_period n
					ON w.employee_id = n.employee_id
					LEFT JOIN companies c
				    ON w.company_id = c.company_id
				    JOIN company_details cmd
				    WHERE w.employee_id = '$id'";
					break;
				case "Experience Letter":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,w.employee_name,cd.designation_name,DATE_FORMAT(w.employee_doj,'%d-%b-%Y') employee_doj,DATE_FORMAT(n.last_working_date,'%d-%b-%Y') last_working_date,
					DATE_FORMAT(CURDATE(),'%b %d, %Y')date,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name
					FROM employee_work_details w
					LEFT JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					LEFT JOIN emp_notice_period n
					ON w.employee_id = n.employee_id
					LEFT JOIN companies c
					ON w.company_id = c.company_id
					JOIN company_details cmd
				    LEFT JOIN company_designations cd
					ON w.designation_id = cd.designation_id
					WHERE w.employee_id = '$id'";
					break;
				case "Termination Letter":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,w.employee_name,
					CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,',')),IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,',')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,',')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,',')),IF(p.employee_city IS NULL,'',CONCAT(p.employee_city,',')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code))employee_single_line_address,
                    CONCAT(IF(p.employee_build_name IS NULL,'',CONCAT(p.employee_build_name,',<br>')),IF(p.employee_street IS NULL,'',CONCAT(p.employee_street,',<br>')),IF(p.employee_area IS NULL,'',CONCAT(p.employee_area,',<br>')),IF(p.employee_district IS NULL,'',CONCAT(p.employee_district,',<br>')),IF(p.employee_pin_code IS NULL,'',p.employee_pin_code)) multi_line_address,CONCAT(IF(p.permanent_emp_bulidname IS NULL,'',CONCAT(p.permanent_emp_bulidname,',')),IF(p.permanent_emp_area IS NULL,'',CONCAT(p.permanent_emp_area,',')),IF(p.permanent_emp_dist IS NULL,'',CONCAT(p.permanent_emp_dist,',')),IF(p.permanent_emp_pincode IS NULL,'',p.permanent_emp_pincode))emp_single_line_Permaddress,
                    CONCAT(IF(p.permanent_emp_bulidname IS NULL,'',CONCAT(p.permanent_emp_bulidname,',<br>')),IF(p.permanent_emp_area IS NULL,'',CONCAT(p.permanent_emp_area,',<br>')),IF(p.permanent_emp_dist IS NULL,'',CONCAT(p.permanent_emp_dist,',<br>')),IF(p.permanent_emp_pincode IS NULL,'',p.permanent_emp_pincode)) emp_multi_line_Permaddress,CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',')),CONCAT(c.company_build_name ,',')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',')),CONCAT(c.company_street ,',')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',')),CONCAT(c.company_area ,',')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',')),CONCAT(c.company_city ,',')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_single_line, 
                    CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',<br>')),CONCAT(c.company_build_name ,',<br>')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',<br>')),CONCAT(c.company_street ,',<br>')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',<br>')),CONCAT(c.company_area ,',<br>')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',<br>')),CONCAT(c.company_city ,',<br>')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_multi_line,
                    IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name,
					cd.designation_name,DATE_FORMAT(w.employee_doj,'%d-%m-%Y') employee_doj,
					DATE_FORMAT(n.last_working_date,'%d-%m-%Y') last_working_date,DATE_FORMAT(CURDATE(),'%d/%m/%Y')date
					FROM employee_work_details w
					LEFT JOIN emp_notice_period n
					ON w.employee_id = n.employee_id
					LEFT JOIN  companies c
					ON w.company_id = c.company_id
					JOIN company_details cmd
					LEFT JOIN company_designations cd
					ON w.designation_id = cd.designation_id
					LEFT JOIN employee_personal_details p
				    ON w.employee_id = p.employee_id
					WHERE w.employee_id = '$id'";
					break;
				case "Employee Exit Checklist":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,w.employee_id,w.employee_name,t.team_name,p.employee_personal_mobile,
					cd.designation_name,DATE_FORMAT(w.employee_doj,'%d-%m-%Y') employee_doj,DATE_FORMAT(n.last_working_date,'%d-%m-%Y') last_working_date,DATE_FORMAT(CURDATE(),'%b %d, %Y')date,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name
					FROM employee_work_details w
					LEFT JOIN emp_notice_period n
					ON w.employee_id = n.employee_id
					LEFT JOIN companies c
					ON w.company_id = c.company_id
					JOIN company_details cmd
					LEFT JOIN company_designations cd
					ON w.designation_id = cd.designation_id
					LEFT JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					Left JOIN company_team t
					ON w.team_id =t.team_id
					WHERE w.employee_id = '$id'";
					break;
				case "Exit Interview Letter":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name, w.employee_id,w.employee_name,t.team_name,p.employee_personal_mobile,
					cd.designation_name,DATE_FORMAT(w.employee_doj,'%d-%m-%Y') employee_doj,DATE_FORMAT(n.last_working_date,'%d-%m-%Y') last_working_date,DATE_FORMAT(CURDATE(),'%b %d, %Y')date,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name
					FROM employee_work_details w
					LEFT JOIN emp_notice_period n
					ON w.employee_id = n.employee_id
					LEFT JOIN companies c
					ON w.company_id = c.company_id
					LEFT JOIN company_details cmd
					ON w.company_id = cmd.company_id
					LEFT JOIN company_designations cd
					ON w.designation_id = cd.designation_id
					LEFT JOIN employee_personal_details p
					ON w.employee_id = p.employee_id
					Left JOIN company_team t
					ON w.team_id =t.team_id
					WHERE w.employee_id = '$id'";
					break;
				case "Evaluation:Transfer":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),wrk.employee_name,' ',wrk.employee_lastname) employee_full_name,CONCAT(wrk.employee_name,' ',wrk.employee_lastname) employee_name,
					br.branch_name branch_name,oldBr.branch_name old_branch,cde.department_name department_name,cd.designation_name designation_name,wrk.employee_id,DATE_FORMAT(ct.action_effects_from,'%d-%m-%Y') doj,ct.affected_ids affectedIds
					FROM employee_work_details wrk
					INNER JOIN employee_personal_details p
					ON wrk.employee_id =p.employee_id
					INNER JOIN company_branch br
					ON wrk.branch_id = br.branch_id
					INNER JOIN emp_branch_history brHist
					ON wrk.employee_id = brHist.employee_id AND brHist.effects_upto = DATE_SUB( wrk.branch_effects_from , INTERVAL 1 DAY)
					INNER JOIN company_branch oldBr
					ON brHist.branch_id = oldBr.branch_id
					LEFT JOIN company_designations cd
					ON wrk.designation_id = cd.designation_id
					LEFT JOIN company_departments cde
					ON wrk.department_id = cde.department_id
					INNER JOIN comp_transfers ct
					ON wrk.transfer_id = ct.action_id
					WHERE wrk.transfer_id = '$id' AND wrk.enabled=1 ";
					break;
				case "Evaluation:Promotion":
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),wd.employee_name,' ',wd.employee_lastname) employee_full_name,wd.employee_name,desg.designation_name designation,DATE_FORMAT(CURDATE(),'%b %d, %Y') date,
					oldesg.designation_name old_designation,cb.branch_name,cd.department_name,IF(c.company_area IS NULL,cmd.company_area,c.company_area) company_area,
					DATE_FORMAT(pro.action_effects_from,'%d %b %Y') effects_from,wd.employee_id,pro.affected_ids affectedIds,IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name
					FROM employee_work_details wd 
					INNER JOIN employee_personal_details p
					ON wd.employee_id =p.employee_id
					INNER JOIN company_designations desg 
					ON wd.designation_id = desg.designation_id 
					INNER JOIN company_branch cb 
					ON wd.branch_id = cb.branch_id 
					INNER JOIN company_departments cd 
					ON wd.department_id = cd.department_id
					LEFT JOIN companies c
					ON wd.company_id = c.company_id
					JOIN company_details cmd
					INNER JOIN comp_promotions_increments pro 
					ON wd.promotion_id = pro.action_id 
					INNER JOIN emp_designation_history desHis 
					ON wd.employee_id = desHis.employee_id AND desHis.effects_upto = DATE_SUB(wd.design_effects_from,INTERVAL 1 DAY) 
					INNER JOIN company_designations oldesg ON oldesg.designation_id = desHis.designation_id 
					WHERE wd.promotion_id='$id' AND wd.enabled=1";
					break;
				case "Evaluation:Promotion come Increment":
					
					//To check wheather given increment_id is old increment or recent increment
					$oldCheckQuery = "SELECT promotion_id FROM employee_work_details
									  WHERE promotion_id='$id' AND promotion_id !='CREATION';";
					//echo $oldCheckQuery; die();
					$result = mysqli_query ( $this->conn, $oldCheckQuery) or die(mysqli_error($this->conn));
					$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
					
					if($row['promotion_id'] !=''){
					$query = "SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),wd.employee_name,' ',wd.employee_lastname) employee_full_name, wd.employee_name,desg.designation_name designation,
									 oldesg.designation_name old_designation,(s.employee_salary_amount-sh.employee_salary_amount) increment_percentage,sh.employee_salary_amount old_salary,s.employee_salary_amount new_salary,cb.branch_name,cd.department_name,
									 DATE_FORMAT(pro.action_effects_from,'%d-%m-%Y') effects_from,wd.employee_id,pro.affected_ids affectedIds 
								FROM employee_work_details wd 
								INNER JOIN employee_personal_details p
								ON wd.employee_id =p.employee_id 
								INNER JOIN company_designations desg 
								ON wd.designation_id = desg.designation_id 
								INNER JOIN company_branch cb 
								ON wd.branch_id = cb.branch_id 
								INNER JOIN company_departments cd 
								ON wd.department_id = cd.department_id
								INNER JOIN employee_salary_details s
								ON wd.employee_id = s.employee_id
								INNER JOIN employee_salary_details_history sh
								ON wd.employee_id = sh.employee_id AND sh.effects_upto = DATE_SUB(s.effects_from,INTERVAL 1 DAY)
								INNER JOIN comp_promotions_increments pro 
								ON wd.promotion_id = pro.action_id 
								INNER JOIN emp_designation_history desHis 
								ON wd.employee_id = desHis.employee_id AND desHis.effects_upto = DATE_SUB(wd.design_effects_from,INTERVAL 1 DAY) 
								INNER JOIN company_designations oldesg ON oldesg.designation_id = desHis.designation_id 
								WHERE wd.promotion_id='$id' AND wd.enabled=1 ";
					}else{
					/*$query = "SELECT CONCAT(wd.employee_name,' ',wd.employee_lastname) employee_name,desg.designation_name designation, olddesg.designation_name old_designation,
							    (sh.employee_salary_amount - s.employee_salary_amount) increment_percentage,sh.employee_salary_amount new_salary,
							    s.employee_salary_amount old_salary,cb.branch_name,cd.department_name, DATE_FORMAT(pro.action_effects_from,'%d-%m-%Y') effects_from,
							    wd.employee_id,pro.affected_ids affectedIds 
							FROM employee_work_details wd 
							INNER JOIN company_designations desg ON wd.designation_id = desg.designation_id
							INNER JOIN company_branch cb ON wd.branch_id = cb.branch_id 
							INNER JOIN company_departments cd ON wd.department_id = cd.department_id 
							LEFT JOIN employee_salary_details_history sh ON -- /*wd.employee_id = sh.employee_id  -- AND sh.increment_id = '$id'
							LEFT JOIN employee_salary_details_history s ON -- wd.employee_id = s.employee_id AND s.increment_id != '$id'
							AND s.effects_upto = DATE_SUB(sh.effects_from,INTERVAL 1 DAY)
							INNER JOIN emp_designation_history desHis ON wd.employee_id = desHis.employee_id AND desHis.effects_upto = sh.effects_upto
							LEFT JOIN comp_promotions_increments pro ON pro.action_id = '$id'
							-- AND desHis.effects_upto = DATE_SUB(wd.design_effects_from,INTERVAL 1 DAY)
							INNER JOIN emp_designation_history olddesHis ON wd.employee_id = olddesHis.employee_id
							INNER JOIN company_designations olddesg ON olddesHis.designation_id = olddesg.designation_id AND olddesHis.promotion_id!='$id'
							WHERE wd.enabled=1;";
					*/
					$query ="SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),wd.employee_name,' ',wd.employee_lastname) employee_full_name,CONCAT(wd.employee_name,' ',wd.employee_lastname) employee_name,desg.designation_name designation, olddesg.designation_name old_designation,
							    (sh.employee_salary_amount - s.employee_salary_amount) increment_percentage,sh.employee_salary_amount new_salary,
							    s.employee_salary_amount old_salary,cb.branch_name,cd.department_name, DATE_FORMAT(pro.action_effects_from,'%d-%m-%Y') effects_from,
							    wd.employee_id,pro.affected_ids affectedIds 
							FROM employee_work_details wd 
							INNER JOIN employee_personal_details p
							ON wd.employee_id =p.employee_id 
							INNER JOIN company_branch cb ON wd.branch_id = cb.branch_id 
							INNER JOIN company_departments cd ON wd.department_id = cd.department_id 
							LEFT JOIN employee_salary_details_history sh ON /*wd.employee_id = sh.employee_id  -- AND */sh.increment_id = 'PM88515'
							LEFT JOIN employee_salary_details_history s ON /*wd.employee_id = s.employee_id AND*/ s.increment_id != '$id'
							AND s.effects_upto = DATE_SUB(sh.effects_from,INTERVAL 1 DAY)
							INNER JOIN emp_designation_history desHis ON wd.employee_id = desHis.employee_id AND desHis.effects_upto = sh.effects_upto 
							LEFT JOIN comp_promotions_increments pro ON pro.action_id = '$id'
							INNER JOIN emp_designation_history olddesHis ON wd.employee_id = olddesHis.employee_id
							INNER JOIN company_designations olddesg ON olddesHis.designation_id = olddesg.designation_id AND olddesHis.promotion_id!='$id'
							INNER JOIN company_designations desg ON desg.designation_id = pro.promoted_desig_id 
							WHERE  wd.enabled=1;";
					}
					break;
				case "Evaluation:Increment":
				  // To check wheather given increment_id is old increment or recent increment 
				  $oldCheckQuery = "SELECT increment_id FROM employee_salary_details
									WHERE increment_id='$id' AND increment_id !='CREATION';";
				  $result = mysqli_query ( $this->conn, $oldCheckQuery) or die(mysqli_error($this->conn));
				  $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ); 
				  
				  if($row['increment_id'] !=''){
				  	$cond = "(s.employee_salary_amount-sh.employee_salary_amount) increment_percentage,FORMAT(sh.employee_salary_amount,0,'en_IN') old_salary,FORMAT(s.employee_salary_amount,0,'en_IN') new_salary,CAP_FIRST(number_to_words(sh.employee_salary_amount)) old_salary_words,CAP_FIRST(number_to_words(s.employee_salary_amount)) new_salary_words,";
				  	$join = " s ON w.employee_id = s.employee_id AND sh.effects_upto = DATE_SUB(s.effects_from, INTERVAL 1 DAY)";
				  	$idcond= "s.increment_id";
				  }else{
				  	$cond = "(sh.employee_salary_amount-s.employee_salary_amount) increment_percentage,FORMAT(sh.employee_salary_amount,0,'en_IN') new_salary,FORMAT(s.employee_salary_amount ,0,'en_IN') old_salary,CAP_FIRST(number_to_words(s.employee_salary_amount)) old_salary_words,CAP_FIRST(number_to_words(sh.employee_salary_amount)) new_salary_words,";
				  	$join = "_history s ON w.employee_id = s.employee_id AND s.increment_id != '$id' AND s.effects_upto = DATE_SUB(sh.effects_from, INTERVAL 1 DAY)";
				  	$idcond = "sh.increment_id";
				  }
				  
				  $query = "SELECT CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',')),CONCAT(c.company_build_name ,',')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',')),CONCAT(c.company_street ,',')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',')),CONCAT(c.company_area ,',')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_single_line, 
				  		    CONCAT(IF(c.company_build_name IS NULL,IF(cmd.company_build_name IS NULL,'',CONCAT(cmd.company_build_name,',<br>')),CONCAT(c.company_build_name ,',<br>')),IF(c.company_street IS NULL,IF(cmd.company_street IS NULL,'',CONCAT(cmd.company_street,',<br>')),CONCAT(c.company_street ,',<br>')),IF(c.company_area IS NULL,IF(cmd.company_area IS NULL,'',CONCAT(cmd.company_area,',<br>')),CONCAT(c.company_area ,',<br>')),IF(c.company_city IS NULL,IF(cmd.company_city IS NULL,'',CONCAT(cmd.company_city,',<br>')),CONCAT(c.company_city ,',<br>')),IF(c.company_pin_code IS NULL,IF(cmd.company_pin_code IS NULL,'',cmd.company_pin_code),c.company_pin_code))company_address_multi_line,
				  		    IF(c.company_name IS NULL,cmd.company_name,c.company_name) company_name,IF(c.company_area IS NULL,cmd.company_area,c.company_area) company_area,
                    	    CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,desg.designation_name designation,DATE_FORMAT(CURDATE(),'%b %d, %Y') date,$cond 
						    cb.branch_name,cd.department_name,w.employee_id,pro.affected_ids affectedIds,DATE_FORMAT(pro.action_effects_from,'%d %b %Y') effects_from,DATE_FORMAT(w.employee_doj,'%d %b %Y') employee_doj
							FROM employee_work_details w
							INNER JOIN employee_salary_details_history sh
							ON w.employee_id = sh.employee_id  /*AND sh.effects_upto = DATE_SUB(s.effects_from, INTERVAL 1 DAY)*/
							INNER JOIN employee_salary_details$join
							INNER JOIN employee_personal_details p
							ON w.employee_id =p.employee_id 
							INNER JOIN company_designations desg 
							ON w.designation_id = desg.designation_id 
							INNER JOIN company_branch cb 
							ON w.branch_id = cb.branch_id 
							INNER JOIN company_departments cd 
							ON w.department_id = cd.department_id 
							JOIN company_details cmd
							LEFT JOIN companies c
							ON w.company_id = c.company_id
							INNER JOIN comp_promotions_increments pro 
							ON $idcond = pro.action_id 
							WHERE $idcond= '$id' AND w.enabled = 1;";
				  
				 break;
					
				case "Offer:Confirmation":
					$query ="SELECT CONCAT(IF(employee_gender='Male','Mr. ',IF(employee_marital_status='Single','Ms. ','Mrs. ')),w.employee_name,' ',w.employee_lastname) employee_full_name,employee_name,des.designation_name designation,DATE_FORMAT(w.employee_confirmation_date,'%d, %b %Y') confirmation_date
							FROM employee_work_details w
							INNER JOIN employee_personal_details p
							ON w.employee_id =p.employee_id 
							INNER JOIN company_designations des
							ON w.designation_id = des.designation_id
							WHERE w.employee_id = '$id'";
			   break;
					
											
				default:
					die("No Letter Found");
					
			}
			$result = mysqli_query ( $this->conn, $query ) or die(mysqli_error($this->conn));
			
				
			$letter = new letterTemplate($this->letterContent);
			$filename = str_replace ( ' ', '', $letterName );
			$PHPWord = new PhpWord ();
			
			$count =0;
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				
				$affectedIds = $row ['affectedIds'];
				foreach ($row as $key=>$field){
					$letter->set($key, $field);
				}
				$section = $PHPWord->createSection();
				
				
				if ($count >= 0)
					$letterContnt = "{$letter->output()}" . $section->addPageBreak ();
				
				else
						$letterContnt = $letter->output();
						$html_dom = new simple_html_dom ();
						$html_dom->load ( '<html><body>' . $letterContnt . '</body></html>' );
					    $html_dom_array = $html_dom->find ( 'html', 0 )->children ();
					   
					    $initial_state = array (
								// Required parameters:
								'phpword_object' => &$PHPWord,
								'base_root' => "http://".$_SERVER['HTTP_HOST'],
								'base_path' => $_SERVER['REQUEST_URI'],
								// Optional parameters - showing the defaults if you don't set anything:
								'parents' => array(0 => 'body'), // Our parent is body.
								'list_depth' => 0, // This is the current depth of any current list.
								'context' => 'section', // Possible values - section, footer or header.
								'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
								'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
								'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
								'pseudo_list_indicator_character' => 'l ', // Gives a circle bullet point with wingdings.
								'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
								'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
								
								// Optional - no default:
								'style_sheet' => htmltodocx_styles_example(), // This is an array (the "style sheet") - returned by htmltodocx_styles_example() here (in styles.inc) - see this function for an example of how to construct this array.
								
						); // Must be passed by reference.
					    
						
						htmltodocx_insert_html ( $section, $html_dom_array [0]->nodes, $initial_state );
						//$section = str_replace("<br>", " ",$initial_state);
						$count++;
			}
			
			
			if($count==0)
				die("No Data Found. Unable to Generate Letter.");
				$letterName = preg_replace ( '/\s+/', '', $letterName );
			
				$fileName = str_replace ( 'LTR', '', $letterName ) . '_' . $affectedIds . '.docx';
			    $h2d_file_uri = tempnam ( '', 'htd' );
				$h2d_file_uri = $_SERVER['DOCUMENT_ROOT'].'/docs/some_file_name.docx';
	
				$objWriter = PHPWord_IOFactory::createWriter ( $PHPWord, 'Word2007' );
				$objWriter->save ( $h2d_file_uri );
				
				// Download the file:
				header ( 'Content-Description: File Transfer' );
				header ( 'Content-Type: application/octet-stream' );
				header ( 'Content-Disposition: attachment; filename=' . $fileName . '' );
				header ( 'Content-Transfer-Encoding: binary' );
				header ( 'Content-Length: ' . filesize ( $h2d_file_uri ) );
				ob_clean ();
				flush ();
				$status = readfile ( $h2d_file_uri );
				unlink ( $h2d_file_uri );
				exit ();
				// Save File
			}
			
			
			
			}
			
			?>