<?php
/*
 * ----------------------------------------------------------
 * Filename : master compnay.class.php
 * Author : Rufus Jackson
 * Database : company_master compnay
 * Oper : master compnay Actions
 *
 * ----------------------------------------------------------
 */
class consultant_company {
	/* Member variables */
	var $company_id;
	var $company_name;
	var $company_user_name;
	var $company_logo;
	var $company_type;
	var $company_doi;
	var $company_cin_no;
	var $company_build_name;
	var $company_area;
	var $company_pin_code;
	var $company_city;
	var $company_state;
	var $company_phone;
	var $company_mobile;
	var $company_email;
	var $company_website;
	var $company_resp1_name;
	var $company_pan;
	var $company_resp1_desgn;
	var $company_resp1_phone;
	var $company_resp1_email;
	var $company_resp2_name;
	var $company_tan;
	var $company_resp2_desgn;
	var $company_resp2_phone;
	var $company_resp2_email;
	var $date_of_signUp;
	var $enabled;
	var $company_db_name;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New master compnay */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO " . $_SESSION ['master_db_name'] . ".
				consultant_login_details (company_id,company_name,company_logo,company_user_name,company_type,
				company_doi,company_cin_no,company_build_name,company_street,company_area,company_pin_code,company_city,
				company_state,company_phone,company_email,company_website,company_resp1_name,company_resp1_desgn,
				company_resp1_phone,company_resp1_email,company_resp2_name,
				company_resp2_desgn,company_resp2_phone,company_resp2_email,company_mobile,enabled,
				company_pan,company_tan)
			    values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssssssssssssssssssss', $this->company_id, $this->company_name, $this->company_logo, $this->company_user_name, $this->company_type, $this->company_doi, $this->company_cin_no, $this->company_build_name, $this->company_street, $this->company_area, $this->company_pin_code, $this->company_city, $this->company_state, $this->company_phone, $this->company_email, $this->company_website, $this->company_resp1_name, $this->company_resp1_desgn, $this->company_resp1_phone, $this->company_resp1_email, $this->company_resp2_name, $this->company_resp2_desgn, $this->company_resp2_phone, $this->company_resp2_email, $this->company_mobile, $this->enabled, $this->company_pan, $this->company_tan );
		
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Update master compnay Using master compnay ID */
	function update() {
		// this affected on master table
		$stmt = mysqli_prepare ( $this->conn, "UPDATE " . $_SESSION ['master_db_name'] . ".consultant_login_details 
				SET company_name=?,company_logo=?,company_user_name=?,company_type=?,
company_doi=?,company_cin_no=?,company_build_name=?,company_street=?,company_area=?,company_pin_code=?,company_city=?,company_state=?,company_phone=?,
company_email=?,company_website=?,company_resp1_name=?,company_resp1_desgn=?,company_resp1_phone=?,company_resp1_email=?,
				company_resp2_name=?,
company_resp2_desgn=?,company_resp2_phone=?,company_resp2_email=?,company_mobile=?,company_pan=?,company_tan=? WHERE company_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssssssssssssssssssssss', $this->company_name, $this->company_logo, $this->company_user_name, $this->company_type, $this->company_doi, $this->company_cin_no, $this->company_build_name, $this->company_street, $this->company_area, $this->company_pin_code, $this->company_city, $this->company_state, $this->company_phone, $this->company_email, $this->company_website, $this->company_resp1_name, $this->company_resp1_desgn, $this->company_resp1_phone, $this->company_resp1_email, $this->company_resp2_name, $this->company_resp2_desgn, $this->company_resp2_phone, $this->company_resp2_email, $this->company_mobile, $this->company_pan, $this->company_tan, $this->company_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	/* Enable/Disable master compnay */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE " . $_SESSION ['master_db_name'] . ".consultant_login_details  SET enabled =? WHERE company_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'is', $val, $this->company_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function select($company_id) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT * FROM  " . $_SESSION ['master_db_name'] . ".consultant_login_details WHERE company_id ='$company_id'" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
}
?>