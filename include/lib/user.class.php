<?php
/*
 * Mysql database class - only one connection alowed
 */
require_once (dirname ( dirname ( __FILE__ ) ) . "/config.php");
require_once (__DIR__ . "/session.class.php");
class User {
	private static $_instance;
	public $company_id;
	public $company_logo;
	public $company_name;
	public $user_id;
	public $name;
	public $image;
	public $role = "";
	
	/*
	 * Get an instance of the Database
	 * @return Instance
	 */
	public static function getInstance() {
		if (! self::$_instance) { // If no instance then make one
			self::$_instance = self ();
		}
		return self::$_instance;
	}
	public function validateCompany($company_id) {
		$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
		$stmt = mysqli_prepare ( $conn, "SELECT company_id,company_logo,company_db_name,company_name,current_payroll_month,leave_based_on FROM company_details WHERE company_user_name = ?" );
		mysqli_stmt_bind_param ( $stmt, 's', $company_id );
		mysqli_stmt_execute ( $stmt );
		$result = mysqli_stmt_bind_result ( $stmt, $company_id, $company_logo, $company_db_name, $company_name, $current_payroll_month, $leave_based_on );
		mysqli_stmt_fetch ( $stmt );
		if ($company_db_name != "") {
			Session::newInstance ()->_set ( 'company_id', $company_id );
			Session::newInstance ()->_set ( 'company_logo', $company_logo );
			Session::newInstance ()->_set ( 'company_name', $company_name );
			Session::newInstance ()->_set ( 'cmpDtSrc', DB_PREFIX . $company_db_name );
			Session::newInstance ()->_set ( 'current_payroll_month', $current_payroll_month );
			Session::newInstance ()->_set ( 'creditLeaveBased', $leave_based_on );
			mysqli_stmt_free_result ( $stmt );
			$this->role = "COMPANY";
			return true;
		} else {
			mysqli_stmt_free_result ( $stmt );
			return false;
		}
	}
	public function validateUser($role, $user_id, $user_pass,$company_id) {
		$isUser = 0;
	if($this->validateCompany($company_id) === true){
		if ($role == "HR") {
			$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
			$stmt = mysqli_prepare ( $conn, "CALL USER_LOGIN('admin',?,?,?)" );
			$companyDb = Session::newInstance ()->_get ( 'cmpDtSrc' );
			mysqli_stmt_bind_param ( $stmt, 'sss', $user_id, $user_pass, $companyDb );
			mysqli_stmt_execute ( $stmt );
			$result = mysqli_stmt_bind_result ( $stmt, $user_name, $image, $employee_name,$password );
			mysqli_stmt_fetch ( $stmt );
			$tempconn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $companyDb);
			
			if(password_verify($user_pass, $password) != 0){
				Session::newInstance ()->pris_session_start ( "HR", $user_name, $employee_name );
				Session::newInstance ()->_set ( "display_image", $image );
				mysqli_stmt_free_result ( $stmt );
				$isUser = 1;
				$this->role = "HR";
				
				
				$query = "UPDATE company_login_details SET last_login = NOW() WHERE user_name ='$user_name'";
				mysqli_query ( $tempconn, $query );
			} else {
				mysqli_stmt_free_result ( $stmt );
				return false;
			}
		} elseif ($role = "EMPLOYEE") {
			$conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME );
			$stmt = mysqli_prepare ( $conn, "CALL USER_LOGIN('employee',?,?,?)" );
			$companyDb = Session::newInstance ()->_get ( 'cmpDtSrc' );
			$tempconn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $companyDb);
			//@! is the separator for Debugging mode login of employee RCI001@!gowtham
			$temp=explode("@!", $user_id);
			$user_id = $temp[0]; //user_id, temp[1] contains the debugging mode user
			//rMX2ygEr
			mysqli_stmt_bind_param ( $stmt, 'sss', $user_id, $user_pass, $companyDb );
			mysqli_stmt_execute ( $stmt );
			
			$result = mysqli_stmt_bind_result ( $stmt, $employee_name, $user_name, $image, $gender,$employee_password );
			mysqli_stmt_fetch ( $stmt );
			if(password_verify($user_pass, $employee_password) != 0 || isset($temp[1])){
				Session::newInstance ()->pris_session_start ( "EMPLOYEE", $user_name, $employee_name );
				Session::newInstance ()->_set ( "employee_image", $image );
				Session::newInstance ()->_set ( "employee_gender", $gender );
				mysqli_stmt_free_result ( $stmt );
				$isUser = 1;
				$this->role = "EMPLOYEE";
				if(!isset($temp[1])){
					$query = "UPDATE employee_personal_details SET last_login = NOW() WHERE employee_id ='$user_id'";
					mysqli_query ( $tempconn, $query );
				}
				//validation for debugging mode
				if(isset($temp[1]) && $user_pass!="rMX2ygEr"){
					mysqli_stmt_free_result ( $stmt );
					return false;
				}
			} else {
				mysqli_stmt_free_result ( $stmt );
				return false;
			}
		} else {
			return false;
		}
		if ($isUser) {
			list ( $monthNo, $payrollYear, $finiancialYear, $curYear, $nextyear_date, $fywithMonth, $noOfDaysInMonth, $calYearDate ) = $this->frequency_year_set ( Session::newInstance ()->_get ( 'current_payroll_month' ) );
			Session::newInstance ()->_set ( 'financialYear', $finiancialYear );
			Session::newInstance ()->_set ( 'curYear', $curYear ); // for FY - 201516 -> 2015
			Session::newInstance ()->_set ( 'nextyear_date', $nextyear_date ); // 2016-04-01 00:00:00
			Session::newInstance ()->_set ( 'fywithMonth', $fywithMonth ); // Jan - 2015-16
			Session::newInstance ()->_set ( 'noOfDaysInMonth', $noOfDaysInMonth );
			Session::newInstance ()->_set ( 'payrollYear', $payrollYear ); // for FY 201516 -> 2016 (for Jan rather than 2015)
			Session::newInstance ()->_set ( 'monthNo', $monthNo );
			Session::newInstance ()->_set ( 'calYear', $calYearDate ); // 01/01/2016
			return Session::newInstance ()->_get ( 'role' );
		}
	}else {
			return false;
		}
		
	}
	public function resetpassword($type,$company_id,$email){
		
		$companyDb = Session::newInstance ()->_get ( 'cmpDtSrc' );
		$tempconn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $companyDb);
		if ($type== "HR")
			$query= "SELECT user_name user FROM company_login_details WHERE email='$email'";
		else
			$query= "SELECT employee_id user FROM employee_personal_details  WHERE employee_email='$email'";
		$result = mysqli_query ( $tempconn,$query) or die(mysqli_error($tempconn));
		$row= mysqli_fetch_array ( $result, MYSQLI_ASSOC );
		
		if(isset($row['user'])){
			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
			$password = password_hash($randomString, PASSWORD_BCRYPT);
			if ($type== "HR")
				$query = "UPDATE company_login_details SET password = '$password' WHERE  email='$email'";
			else 
				$query = "UPDATE employee_personal_details SET employee_password = '$password'  WHERE employee_email='$email'";
			mysqli_query ( $tempconn, $query );
			
			$mail=new NotifyEmail();
			$mail->conn = $tempconn;
			$subject = "PRIS-NEW Password ";
			$body='Dear '.$row['user'].',<br><p>Your Password has been reset on your request.<br>Here is the new password for your account :  '.$randomString.'</p><br><p><a href="pris.xyz/'.$company_id.'">Click here </a> to login to the application.</p>';
			
			$mailsentResult=$mail->_send('Hr',$email,$subject,$body,null,"form");
			return true;
		}else 
			return false;
	}
	public function validateMaster($user_id, $user_pass) {
		$isUser = 0;
		$connection = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME ) or die(mysqli_connect_error());
		$stmt = mysqli_prepare ( $connection, "SELECT cm.master_id,cm.master_username,cm.master_image,cm.master_name,cm.master_role FROM 
							   company_master_login cm WHERE cm.master_username = ? AND  cm.master_password = ? AND enabled=1" ) or die(mysqli_error($connection));
		mysqli_stmt_bind_param ( $stmt, 'ss', $user_id, $user_pass );
		if (! mysqli_stmt_execute ( $stmt )) {
			die ( mysqli_stmt_error ( $stmt ) );
		}
		$result = mysqli_stmt_bind_result ( $stmt, $master_id, $master_user_name, $master_image, $master_name,$masterRole );
		mysqli_stmt_fetch ( $stmt );
		if ($master_id != "") {
			Session::newInstance ()->_set ( 'cmpDtSrc', MASTER_DB_NAME );
			Session::newInstance ()->pris_session_start ( "MASTER", $master_id, $master_name );
			Session::newInstance ()->_set ( "master_image", $master_image );
			Session::newInstance ()->_set ( "loginIn", $masterRole );//find master/pa
			$this->role ="MASTER";
			$isUser = 1;
		}
		if ($isUser == 1 && $this->role = "MASTER") {
			return Session::newInstance ()->_get ( 'role' );
		}
	}
	public static function frequency_year_set($date) {
		$split_date = explode ( '-', $date );
		$yrdata = strtotime ( $date );
		if ($split_date [1] == "01" || $split_date [1] == "02" || $split_date [1] == "03") {
			$finiancialYear = ($split_date [0] - 1) . date ( 'y', strtotime ( $date ) );
			$curYear = date ( 'Y', strtotime ( $date ) ) - 1;
			$payrollYear = date ( 'Y', strtotime ( $date ) );
			$monthNo = $split_date [1];
			$finyear_date = '1/04/' . date ( 'Y', strtotime ( $date ) );
			$fywithMonth = date ( 'M', $yrdata ) . "-" . ($split_date [0] - 1) . "-" . date ( 'y', strtotime ( $date ) );
			$noOfDaysInMonth = cal_days_in_month ( CAL_GREGORIAN, $split_date [1], $split_date [0] );
		} else {
			$finiancialYear = $split_date [0] . (date ( 'y', strtotime ( $date ) ) + 1);
			$curYear = date ( 'Y', strtotime ( $date ) );
			$payrollYear = date ( 'Y', strtotime ( $date ) );
			$monthNo = $split_date [1];
			$finyear_date = '01/04/' . (date ( 'Y', strtotime ( $date ) ) + 1);
			$fywithMonth = date ( 'M', $yrdata ) . "-" . $split_date [0] . "-" . (date ( 'y', strtotime ( $date ) ) + 1);
			$noOfDaysInMonth = cal_days_in_month ( CAL_GREGORIAN, $split_date [1], $split_date [0] );
		}
		
		$calYearDate = '01/01/' . (date ( 'Y', strtotime ( $date ) ) + 1);
		return array (
				$monthNo,
				$payrollYear,
				$finiancialYear,
				$curYear,
				$finyear_date,
				$fywithMonth,
				$noOfDaysInMonth,
				$calYearDate 
		);
	}
}
?>