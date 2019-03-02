<?php
//this function bridges the old app and new app
/*
 * It checks for the session
 * if the session exits it check whether the user is employee or hr
 * get the users*/
function _page(){
	$url = isset($_REQUEST["url"])?$_REQUEST["url"]:myUrl('');
	
	if(!isset($_SESSION["role"]))
		redirect(myUrl('main/login'));
	else{
		//if the session is set 
		if(!isset($_SESSION["authuid"])){
			//if the new app authuid is not set call the login method of new app
			$user = new User();
			if($_SESSION["role"]=="HR"){
				$dbh = getdbh();
				$stmt= $dbh->query("SELECT cl.*,IFNULL(u.username,0) username FROM company_login_details cl LEFT JOIN users u
						ON u.username = cl.user_name
						WHERE cl.user_name='{$_SESSION["login_id"]}'");
				$details=$stmt->fetch(PDO::FETCH_ASSOC);
				if($details["username"] == 0){
					//insert in users table
					$user->merge(array(
							"name"=>$details["user_name"],
							"username"=>$details["user_name"],
							"email"=>"",
							"password"=>$details["password"],
							"privilage"=> "hr"
					));
					$result=$user->create()->result;
				}
				
			}else if($_SESSION["role"]="EMPLOYEE"){
				$dbh = getdbh();
				
				$stmt= $dbh->query("SELECT e.employee_id,w.employee_name,e.employee_password,e.employee_email,IFNULL(u.username,0) username 
						FROM employee_personal_details e
						INNER JOIN employee_work_details w ON e.employee_id = w.employee_id
						LEFT JOIN users u ON e.employee_id = u.username 
						WHERE e.employee_id='{$_SESSION["employee_id"]}'");
				
				if(!$stmt)
				print_r($dbh->errorInfo());
				$details=$stmt->fetch(PDO::FETCH_ASSOC);
				
				
				if($details["username"] == 0){
					//insert in users table
					$user->merge(array(
							"name"=> $details["employee_name"],
							"username"=>$details["employee_id"],
							"email"=>$details["employee_email"],
							"password"=>$details["employee_password"],
							"privilage"=> "employee"
					));
					$result=$user->create()->result;
				}
			}
			
			redirect('auth/login/'.base64_encode($user->get("username")).'/?next='.$url);
			
			
		}else{
			redirect($url);
		}
	}
}