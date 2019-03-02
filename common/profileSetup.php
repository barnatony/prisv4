<?php
/*
 * ----------------------------------------------------------
 * Filename : profileSetup.php.php
 * Author : Rajasundari
 * Database : company_login_details
 * Oper : profile setup Actions
 *
 * ----------------------------------------------------------
 */
function passwordChange($AdmEmp, $aIdeId, $oldPass, $curPass, $newpass) {
	if(password_verify($curPass,$oldPass) == 0){
		return 0;
	}else{	
	$newpass = password_hash($newpass,PASSWORD_BCRYPT);
		include ("../include/config.php");
		$quertStmt0 = 'employee_personal_details SET employee_password=? where employee_id=?';
		$quertStmt1 = 'company_login_details SET password = ? WHERE user_name = ?';
		$queryStmt = ($AdmEmp == 'admin') ? $quertStmt1 : $quertStmt0;
		
		$stmt = mysqli_prepare ( $conn, "Update " . $queryStmt );
		mysqli_stmt_bind_param ( $stmt, 'ss', $newpass, $aIdeId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $conn );
		return $result;
	}
}

$AdmEmp = $_REQUEST ['changeFor'];
$aIdeId = $_REQUEST ['loginId'];
$curPass = $_REQUEST ['currentPass'];
$newpass = $_REQUEST ['newPass'];
$oldPass = $_REQUEST ['oldPass'];

echo passwordChange ( $AdmEmp, $aIdeId, $oldPass, $curPass, $newpass );

?>