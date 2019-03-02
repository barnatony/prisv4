
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
class Company {
	/* Member variables */
	var $company_id;
	var $company_name;
	var $master_id;
	var $company_user_name;
	var $company_logo;
	var $company_type;
	var $company_doi;
	var $company_cin_no;
	var $company_emp_id_prefix;
	var $company_emp_id_suffix;
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
	var $hr_1username;
	var $company_resp1_desgn;
	var $company_resp1_phone;
	var $company_resp1_email;
	var $company_resp2_name;
	var $hr_2username;
	var $company_resp2_desgn;
	var $company_resp2_phone;
	var $company_resp2_email;
	var $date_of_signUp;
	var $enabled;
	var $company_db_name;
	var $feature_id;
	var $conn;
	var $masterConn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New master company */
	function create() {
		
		
		// create database for company
		//$queryStmt = "CREATE DATABASE " . DB_PREFIX . $this->company_user_name;
	     require("xmlapi.php");
		$cpanelusr = 'prisxyz';
		$cpanelpass = 'Vgrow@1206';
		$xmlapi2 = new xmlapi('pris.xyz');
		$xmlapi2->set_port( 2083 );
		$xmlapi2->password_auth($cpanelusr,$cpanelpass);
		$xmlapi2->set_debug(0); //output actions in the error log 1 for true and 0 false
		$databasename = $this->company_user_name;
		$databaseuser = 'c1pris'; //be careful this can only have a maximum of 7 characters
		$databasepass = 'Bass@1987';
		//$createdb = $xmlapi2->api1_query($cpanelusr, "Mysql", "adddb", array($databasename));
		//$usr = $xmlapi2->api1_query($cpanelusr, "Mysql", "adduser", array($databaseuser, $databasepass));
		/*
		if($addusr = $xmlapi2->api1_query($cpanelusr, "Mysql", "adduserdb", array("".$cpanelusr."_".$databasename."", "".$cpanelusr."_".$databaseuser."", 'all'))){
		//if (mysqli_query ( $this->conn, $queryStmt )) {
			*/
			$target_dir =COMPANY_PATH . "/" . $this->company_id;
			
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0755);
				$ltrFolder =COMPANY_PATH . "/" . $this->company_id."/LTR/";
			}
			
			if(!empty($_FILES["cLogo"]["name"]))
			{
				if($this->logo_allotted==1){
				$newfilename = "logo" . '.jpg';
				move_uploaded_file ( $_FILES ["cLogo"] ["tmp_name"], COMPANY_PATH . "/" . $this->company_id . "/" . $newfilename );
				$this->company_logo = "../compDat/$this->company_id/logo.jpg";
				}else{
					$this->company_logo = "../compDat/$this->company_id/logo.jpg";
				}
			}else{
				$this->company_logo = isset ( $_REQUEST ['cLogo_'] ) ? $_REQUEST ['cLogo_'] : "../compDat/$this->company_id/logo.jpg";
			}
			
			
				$stmt = mysqli_prepare ($this->conn, "INSERT INTO company_details (company_id,
				company_name,company_logo,company_user_name,company_type,
				company_doi,company_cin_no,company_build_name,company_street,company_area,company_pin_code,company_city,
				company_state,company_phone,company_email,company_website,company_resp1_name,company_resp1_desgn,company_resp1_phone,company_resp1_email,company_resp2_name,
				company_resp2_desgn,company_resp2_phone,company_resp2_email,company_mobile,company_emp_id_suffix,
				company_emp_id_prefix,company_db_name,enabled,
				hr_1username,hr_2username,date_of_signUp,current_payroll_month,created_by,leave_based_on)
			    values (?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?)" );
			mysqli_stmt_bind_param ( $stmt, 'sssssssssssssssssssssssssssssssssss', $this->company_id, $this->company_name, $this->company_logo, $this->company_user_name, $this->company_type, $this->company_doi, $this->company_cin_no, $this->company_build_name, $this->company_street, $this->company_area, $this->company_pin_code, $this->company_city, $this->company_state, $this->company_phone, $this->company_email, $this->company_website, $this->company_resp1_name, $this->company_resp1_desgn, $this->company_resp1_phone, $this->company_resp1_email, $this->company_resp2_name, $this->company_resp2_desgn, $this->company_resp2_phone, $this->company_resp2_email, $this->company_mobile, $this->company_emp_id_suffix, $this->company_emp_id_prefix, $this->company_db_name, $this->enabled, $this->hr_1username, $this->hr_2username, $this->date_of_signUp, $this->starts_from, $this->created_by,$this->leave_based_on )or die(mysqli_stmt_error($stmt));
			$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_stmt_error ( $stmt );
			
			//$dbstmt = "CREATE DATABASE c1pris".$this->company_user_name.";";
			//$result = mysqli_query ( $this->conn, $dbstmt );
			/*
			if($result){
				$username = 'antony';
				$password = 'Infratel@029';
				$soap_location = 'https://www.infratel.xyz:8080/index.php';
				$soap_uri = 'https://www.infratel.xyz:8080/';
				$client = new SoapClient(null, array('location' => $soap_location,
													 'uri'      => $soap_uri));
				try {
					if($session_id = $client->login($username,$password)) {
									echo "Logged:".$session_id."<br />\n";
				}
				$database_type = 'mysql'; //Only mysql type avaliable more types coming soon.
				$database_name = $this->company_name;
				$database_username = 'c1pris';
				$database_password = 'Bass@1987';
				$database_charset = 'utf8'; // blank = db default, latin1 or utf8
				$database_remoteips = ''; //remote ip´s separated by commas
				$params = array(
						  'server_id' => 1,
								'type' => $database_type,
								'database_name' => $database_name,
								'database_user' => $database_username,
								'database_password' => $database_password,
								'database_charset' =>  $database_charset,
								'remote_access' => 'n', // n disabled - y enabled
								'active' => 'y', // n disabled - y enabled
								'remote_ips' => $database_remoteips
								);
				$client_id = 1;
				$database_id = $client->sites_database_add($session_id, $client_id, $params);
				if($client->logout($session_id)) {
					echo "Logout.<br />\n";
				}
				} catch (SoapFault $e) {
						die('Error: '.$e->getMessage());
				}
			}

			*/
			 // connect to the created database
			$companyConn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, DB_PREFIX . $this->company_user_name );
			
			
			
			if ($companyConn) {
				
				
				
				//load structures on to the db
				$stuctQueries = file_get_contents ( dirname ( dirname ( __FILE__ ) ) . '/struct.sql' );
				if (mysqli_multi_query ( $companyConn, $stuctQueries )) {
					do {
						if ($result = mysqli_store_result ( $companyConn )) {
							mysqli_free_result ( $result );
						}
					} while ( mysqli_more_results ( $companyConn ) && mysqli_next_result ( $companyConn ) );
				$logo = "../".$this->company_logo;
				$stmt = mysqli_prepare ( $companyConn, "INSERT INTO company_details (company_id,company_name,company_logo,company_user_name,
							company_type,
                company_doi,company_cin_no,company_build_name,company_street,company_area,company_pin_code,company_city,
                company_state,company_phone,company_email,company_website,company_resp1_name,company_resp1_desgn,company_resp1_phone,
							company_resp1_email,company_resp2_name,
                company_resp2_desgn,company_resp2_phone,company_resp2_email,company_mobile,company_emp_id_suffix,
                company_emp_id_prefix,hr_1username,hr_2username,current_payroll_month,leave_based_on,updated_by)
                values (?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,'ADMIN')" );
					mysqli_stmt_bind_param ( $stmt, 'sssssssssssssssssssssssssssssss', $this->company_id, $this->company_name, $logo, 
							$this->company_user_name, $this->company_type, $this->company_doi, $this->company_cin_no, $this->company_build_name,
							$this->company_street, $this->company_area, $this->company_pin_code, $this->company_city, $this->company_state, 
							$this->company_phone, $this->company_email, $this->company_website, $this->company_resp1_name, $this->company_resp1_desgn,
							$this->company_resp1_phone, $this->company_resp1_email, $this->company_resp2_name, $this->company_resp2_desgn, 
							$this->company_resp2_phone, $this->company_resp2_email, $this->company_mobile, $this->company_emp_id_suffix,
							$this->company_emp_id_prefix, $this->hr_1username, $this->hr_2username, $this->starts_from ,
					$this->leave_based_on);
					$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_stmt_error ( $stmt );

					if ($result == TRUE) {
						$password_hash = password_hash('pris123', PASSWORD_BCRYPT);
						mysqli_query ( $companyConn, "INSERT INTO `company_login_details` VALUES (1,'$this->hr_1username','$password_hash','Nil','admin','$this->company_resp1_email','0000-00-00',0),
								(2,'$this->hr_2username','$password_hash','Nil','admin','$this->company_resp2_email','0000-00-00',0)" );
						$defaultData = file_get_contents ( dirname ( dirname ( __FILE__ ) ) . '/default_data.sql' );
						if (mysqli_multi_query ( $companyConn, $defaultData )) {
							do {
								if ($result = mysqli_store_result ( $companyConn )) {
									mysqli_free_result ( $result );
								}
							} while ( mysqli_more_results ( $companyConn ) && mysqli_next_result ( $companyConn ) );
						}
						
						$filename=dirname ( dirname ( __FILE__ ) ) . '/function.sql';
						$lines = file($filename);
						// Loop through each line
						$templine='';
						foreach ($lines as $line)
						{
							if (substr(trim($line), -1, 1) !== '#'){
								$templine .= $line;
							}
							if (substr(trim($line), -1, 1) == '#')
							{
								$templine;
								if (mysqli_multi_query ( $companyConn, $templine )) {
									do {
										if ($result = mysqli_store_result ( $companyConn )) {
											mysqli_free_result ( $result );
										}
									} while ( mysqli_more_results ( $companyConn ) && mysqli_next_result ( $companyConn ) );
						
									// Reset temp variable to empty
									$templine = '';
								}
							}
						}
						
						$result = true;
					} else {
						return mysqli_error ( $this->conn );
					}
				}else 
				{
					return mysqli_error ( $this->conn );
				}
				
			
			} else {
				return mysqli_error ( $this->conn );
			}
		/*} else {
			return mysqli_error ( $this->conn );
		}
		*/
		// 
		
		// create table structures,procedures,triggers
		// dump default data set
		
		
		
		// var_dump($stmt );
		if($result){
			if($result===true){
				if(self::sendLoginMail()){
					return array('result'=>true,'data'=>'Mail sent');
				}else{
					return array('result'=>true,'data'=>'Mail Cannot Be sent'); 
				}
				//return array('result'=>true,'data'=>'true');
			}else{
				return array('result'=>false,'data'=>$result);
			}
		}else {
			return $result;
		}
		
	}
	protected function sendLoginMail(){
		$admin = $this->company_resp1_email;
		$header .= "From:write@basspris.com\r\n";
		$header .= "bcc: sundari@basstechs.com\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/html\r\n";
		$subject = 'Login Details For Your Company';
		$body = '<html>
				<body>
				<table width="100%" cellpadding="0" cellspacing="0"  align="center"
				style="max-width:600px;border-left:solid 1px #e6e6e6;border-right:solid 1px #e6e6e6;background-color:rgb(119, 177, 248);">
				  <tbody>
				   	 <tr>
					 <td> &nbsp; &nbsp;&nbsp;</td><td>
				<br>
				Dear ' . $this->company_name . ' User,<br>
				<br>Greeting from Basspris..!Thank you for signing up with us. Here is Your Login Details <br><br><br>
					  <b>Company ID:</b> ' . $this->company_user_name . ' <br><br>
				                                   <b>User Name:</b> ' . $this->hr_1username . ' <br>   <br>
				                                   <b>Password:</b> pris123<br><br>
				                                   	 <b></b> <a href="http://pris.xyz/">Click Here</a> to Login.<br><br>	<br>
				<b>Note: You are advised to change the password at your first login.<br>
				         This is an administrator login where you can create and manage employees and your company.</b>
					<br><br>
				<br>Thank You,
				Basspris<br> <br><br>
				  </td>
						
				    </tr>
				  </tbody>
				</table>
						
						
				</body>
				</html>';
						$body1 = '<html>
				<body>
				<table width="100%" cellpadding="0" cellspacing="0"  align="center"
				style="max-width:600px;border-left:solid 1px #e6e6e6;border-right:solid 1px #e6e6e6;background-color:rgb(119, 177, 248);">
				  <tbody>
				   	 <tr>
					 <td> &nbsp; &nbsp;&nbsp;</td><td>
				<br>
				Dear ' . $this->company_name . ' Teams,<br>
				<br>Greeting from Basspris..!Thank you for signing up with us. Here is Your Login Details <br><br><br>
					  <b>Company ID:</b> ' . $this->company_user_name . ' <br><br>
				                                   <b>UserID:</b> ' . $this->hr_2username . ' <br>   <br>
				                                   <b>Password:</b> pris123<br><br>
				                                   		 <b>Link:</b> <a href="http://pris.xyz/">Click Here</a><br><br>	<br>
				<b>Note: You are advised to change the password at your first login.<br>
				         This is an administrator login where you can create and manage employees and your company.</b>
					<br><br>
				<br>Thank You,
				Basspris<br> <br><br>
				  </td>
						
				    </tr>
				  </tbody>
				</table>
						
						
				</body>
				</html>';
						$retval = mail ( $admin, $subject, $body, $header );
						$admin1 = $this->company_resp2_email;
						$retval = mail ( $admin1, $subject, $body1, $header );
			if($retval){
				return true;
			}else{
				return false;
			}
	}
	/* Update master company Using master company ID */
	function update() {
	
		// this affected on master table
	$stmt1 = mysqli_prepare ( $this->masterConn, "UPDATE company_details SET company_name=?,company_logo=?,company_user_name=?,company_type=?,
company_doi=STR_TO_DATE(?,'%d/%m/%Y'),company_cin_no=?,
company_build_name=?,company_street=?,company_area=?,company_pin_code=?,company_city=?,company_state=?,company_phone=?,
company_email=?,company_website=?,company_resp1_name=?,company_resp1_desgn=?,company_resp1_phone=?,company_resp1_email=?,company_resp2_name=?,
company_resp2_desgn=?,company_resp2_phone=?,company_resp2_email=?,company_mobile=?,
company_emp_id_suffix=?,company_emp_id_prefix=?,current_payroll_month=STR_TO_DATE(?,'%d/%m/%Y'),leave_based_on=? WHERE company_id = ?" );
	
		mysqli_stmt_bind_param ( $stmt, 'sssssssssssssssssssssssssssss', $this->company_name, $this->company_logo, $this->company_user_name,
				$this->company_type, $this->company_doi, $this->company_cin_no, $this->company_build_name, $this->company_street,
				$this->company_area, $this->company_pin_code, $this->company_city, $this->company_state, $this->company_phone, $this->company_email,
				$this->company_website, $this->company_resp1_name, $this->company_resp1_desgn, $this->company_resp1_phone, $this->company_resp1_email, 
				$this->company_resp2_name, $this->company_resp2_desgn, $this->company_resp2_phone, $this->company_resp2_email, $this->company_mobile, 
				$this->company_emp_id_suffix, $this->company_emp_id_prefix, $this->starts_from,$this->leave_based_on, $this->company_id);
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
	
	$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
		// this affected based on company db , its on admin
	 $query = "UPDATE company_details SET
				company_name=?,company_logo=?,company_user_name=?,company_type=?,
				company_doi=STR_TO_DATE(?,'%d/%m/%Y'),company_cin_no=?,company_area=?,company_build_name=?,
				company_cin_no=?,company_city=?,company_doi=STR_TO_DATE(?,'%d/%m/%Y'),company_email=?,company_mobile=?,company_name=?,company_phone=?,company_pin_code=?,
				company_resp1_desgn=?,company_resp1_email=?,company_resp1_name=?,company_resp1_phone=?,company_resp2_desgn=?
				,company_resp2_email=?,company_resp2_name=?,company_resp2_phone=?,company_state=?
				,company_street=?,company_type=?,company_website=?,company_emp_id_suffix=?,company_emp_id_prefix=?,leave_based_on=? WHERE company_id=? and info_flag='A' ";
		$stmt2 = mysqli_prepare ( $this->conn,  $query);
		mysqli_stmt_bind_param ( $stmt2, 'ssssssssssssssssssssssssssssssss', $this->company_name, $this->company_logo, $this->company_user_name, $this->company_type, $this->company_doi,
				$this->company_cin_no, $this->company_area, $this->company_build_name, $this->company_cin_no, 
				$this->company_city, $this->company_doi, $this->company_email, $this->company_mobile, $this->company_name, $this->company_phone, $this->company_pin_code, $this->company_resp1_desgn, $this->company_resp1_email, 
				$this->company_resp1_name, $this->company_resp1_phone, $this->company_resp2_desgn, $this->company_resp2_email,
				$this->company_resp2_name, $this->company_resp2_phone, $this->company_state, $this->company_street, 
				$this->company_type, $this->company_website, $this->company_emp_id_suffix, $this->company_emp_id_prefix, $this->leave_based_on,
				$this->company_id );$result = mysqli_stmt_execute ( $stmt2 ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
		
	
	}
	/* Enable/Disable master compnay */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->masterConn, "UPDATE company_details  SET enabled =? WHERE company_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'is', $val, $this->company_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
	if($result===true){
			return array('result'=>true,'data'=>self::filterCompany(0)['data']);
		}else{
			return array('result'=>false,'data'=>$result);
		}
	}
	
	function approve() {
		
		
		$query = "SELECT company_db_name FROM company_details WHERE company_id='{$this->company_id}'";
		$stmt = mysqli_prepare ( $this->masterConn, $query) OR die(mysqli_error( $this->conn));
		$result = mysqli_stmt_execute ( $stmt )or die(mysqli_stmt_error($stmt));
		mysqli_stmt_bind_result ( $stmt, $company_db);
		mysqli_stmt_fetch ( $stmt );
		mysqli_stmt_free_result($stmt);
		$this->company_db_name = $company_db;
		$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
		
		$query="DELETE FROM company_details WHERE info_flag='A';";
		$stmts = mysqli_query( $this->conn, $query) or die(mysqli_error( $this->conn));
		$info_flag = "A";
		
		// this affected on master table
	    $query2 =" UPDATE company_details SET company_build_name=?,company_street=?,company_area=?,company_pin_code=?,
			company_city=?,company_state=?,company_phone=?,company_mobile=?,company_email=?,company_resp1_name=?,hr_1username=?,company_resp1_desgn=?,company_resp1_phone=?,company_resp1_email=?,
			company_resp2_name=?,hr_2username=?,company_resp2_desgn=?,company_resp2_phone=?,company_resp2_email=?,info_flag=? WHERE company_id =?";
		$stmt2 = mysqli_prepare ( $this->masterConn, $query2) OR die(mysqli_error( $this->conn));
		
		mysqli_stmt_bind_param ( $stmt2,'sssssssssssssssssssss', $this->company_build_name, $this->company_street, $this->company_area, $this->company_pin_code,
				$this->company_city, $this->company_state, $this->company_phone,$this->company_mobile, $this->company_email, $this->company_resp1_name, $this->hr_1username, $this->company_resp1_desgn, $this->company_resp1_phone, $this->company_resp1_email,
				$this->company_resp2_name, $this->hr_2username, $this->company_resp2_desgn, $this->company_resp2_phone, $this->company_resp2_email,$info_flag,$this->company_id);
		$result = mysqli_stmt_execute ( $stmt2) ? TRUE : mysqli_error ( $this->conn );
		
		
		
		//this affected on company db table
       $query2=" UPDATE company_details SET company_build_name=?,company_street=?,company_area=?,company_pin_code=?,
			company_city=?,company_state=?,company_phone=?,company_mobile=?,company_email=?,company_resp1_name=?,hr_1username=?,company_resp1_desgn=?,company_resp1_phone=?,company_resp1_email=?,
			company_resp2_name=?,hr_2username=?,company_resp2_desgn=?,company_resp2_phone=?,company_resp2_email=?,info_flag=? WHERE company_id =?";
       $stmt2 = mysqli_prepare ( $this->conn, $query2) or die(mysqli_error( $this->conn));
       mysqli_stmt_bind_param ( $stmt2,'sssssssssssssssssssss', $this->company_build_name, $this->company_street, $this->company_area, $this->company_pin_code,
				$this->company_city, $this->company_state, $this->company_phone,$this->company_mobile, $this->company_email, $this->company_resp1_name, $this->hr_1username, $this->company_resp1_desgn, $this->company_resp1_phone, $this->company_resp1_email,
				$this->company_resp2_name, $this->hr_2username, $this->company_resp2_desgn, $this->company_resp2_phone, $this->company_resp2_email,$info_flag, $this->company_id);
		$result = mysqli_stmt_execute ( $stmt2) ? TRUE : mysqli_error ( $this->conn );
		return $result;
		
		/*$query1 = "SELECT COUNT(info_flag) count_flag FROM company_details WHERE info_flag='A'";
		$stmt3 = mysqli_query ($conn,  $query1) OR die(mysqli_error($conn));
		if(count_flag > 1){
			$query2="DELETE FROM company_details ORDER BY updated_on DESC LIMIT 1";
			$stmt4 = mysqli_query ($conn,  $query2) OR die(mysqli_error($conn));
		}*/
	}
	/*  */
	function reject() {
		
		$query = "SELECT company_db_name FROM " . MASTER_DB_NAME .".company_details WHERE company_id='$this->company_id'";
		$stmt = mysqli_prepare ( $this->masterConn, $query) OR die(mysqli_error( $this->conn));
		$result = mysqli_stmt_execute ( $stmt )or die(mysqli_stmt_error($stmt));
		mysqli_stmt_bind_result ( $stmt, $company_db);
		mysqli_stmt_fetch ( $stmt );
		mysqli_stmt_free_result($stmt);
		$this->company_db_name = $company_db;
		
		$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
		
		$query="DELETE FROM company_details WHERE info_flag='P';";
		$stmts = mysqli_query( $this->conn, $query) or die(mysqli_error( $this->conn));
		
	    $query ="SET @disable_triggers = 1;
		UPDATE $company_db.company_details cd 
		INNER JOIN $company_db.company_details_shadow cs 
		ON cd.company_id = cs.company_id
		SET cd.company_build_name=cs.company_build_name,cd.company_street=cs.company_street,cd.company_area=cs.company_area,
		cd.company_city=cs.company_city,cd.company_pin_code=cs.company_pin_code,cd.company_state=cs.company_state,
		cd.company_phone=cs.company_phone,cd.company_mobile=cs.company_mobile,cd.company_email=cs.company_email,
		cd.hr_1username=cs.hr_1username,cd.company_resp1_phone=cs.company_resp1_phone,cd.company_resp1_phone=cs.company_resp1_phone,
		cd.company_resp1_email=cs.company_resp1_email,cd.company_resp1_desgn=cs.company_resp1_desgn,cd.company_resp2_name=cs.company_resp2_name,
		cd.company_resp2_desgn=cs.company_resp2_desgn,cd.hr_2username=cs.hr_2username,cd.company_resp2_phone=cs.company_resp2_phone,
		cd.company_resp2_email=cs.company_resp2_email,cd.info_flag=cs.info_flag
		WHERE cs.info_flag = 'A' AND cs.updated_on=(SELECT MAX(updated_on) FROM $company_db.company_details_shadow WHERE company_id='CP11122' AND info_flag='A');
		SET @disable_triggers = NULL;";
		
		if (mysqli_multi_query ( $this->conn,$query)) {
			do {
				if ($result = mysqli_store_result ( $this->conn )) {
					mysqli_free_result ( $result );
				}
			} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
		}
		
		
		$stmt1 = mysqli_prepare ( $this->masterConn, "UPDATE company_details SET info_flag='A' WHERE company_id = ?") or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt1,'s', $this->company_id);
		$result = mysqli_stmt_execute ( $stmt1 ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function select_profile_differ($company_id) {
		$a_json = array ();
		$query = "SELECT * FROM company_details WHERE company_id ='$company_id'";
		
		$result = mysqli_query ( $this->masterConn,  $query) OR die(mysqli_error($this->masterConn));
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ;
		
		$this->company_db_name = $rr = DB_PREFIX.$row ['company_db_name'];
		
		$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
		
		$query = "SELECT * FROM company_details_shadow WHERE company_id ='$company_id' AND  info_flag='A' AND updated_on=(SELECT MAX(updated_on) FROM $rr.company_details_shadow WHERE company_id='$company_id' AND info_flag='A')";
		$result = mysqli_query ( $this->conn,  $query) OR die(mysqli_error($this->conn));
		$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ;
		$a_json ["M_company_details"] = array (
				$row
		);
		$query = "SELECT * FROM  company_details where  company_id='$this->company_id' AND  info_flag='P'";
		$stmt1 = mysqli_query ( $this->conn,  $query) OR die(mysqli_error($this->conn));
		$row1 = mysqli_fetch_array ( $stmt1, MYSQLI_ASSOC ) ;
		$a_json ["p_company_details"] = array (
				$row1 
		);
		return $a_json;
	}
	function select($company_id) {
		$a_json = array ();
		$result = mysqli_query ( $this->masterConn, "SELECT *,DATE_FORMAT(current_payroll_month,'%d/%m/%Y') AS payroll_month ,DATE_FORMAT(company_doi,'%d/%m/%Y') AS doi  FROM  company_details WHERE company_id ='$company_id'" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
		
	function getCompanyDetails($currPayrollMonth) {
		
		$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
		
		$ajson = array ();
		
		if($result = mysqli_query ($this->conn, "SELECT DATE_SUB('".$currPayrollMonth."',INTERVAL 1 MONTH) lastMonth,
		DATE_FORMAT('".$currPayrollMonth."','%M %Y') currentMonth,  DATE_FORMAT(month_year,'%M %Y') month_year,
        IF(IF((SELECT COUNT(p.employee_id) FROM payroll_preview_temp p
         WHERE  is_processed=1 )=0,1,0)=0,
        (SELECT CONCAT('1#Partial Runned ',COUNT(p.employee_id),' / ',(SELECT COUNT(p.employee_id) FROM payroll_preview_temp p
        INNER JOIN employee_work_details w 
        ON w.employee_id = p.employee_id 
        WHERE w.enabled=1 ) ) FROM payroll_preview_temp p
        WHERE is_processed=1),
        CONCAT(COUNT(pr.employee_id),' / ',(SELECT COUNT(p.employee_id) 
        FROM payroll_preview_temp p
        )))
        runnedEmp
        FROM payroll pr
        WHERE month_year =DATE_SUB('".$currPayrollMonth."',INTERVAL 1 MONTH)" )){
        while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $ajson, $row );
		}
		if(count($ajson)>0){
			return array('result'=>true,'data'=>$ajson);
		}else{
			return array('result'=>true,'data'=>"No Data Found");
		}
		}else{
			return array('result'=>false,'data'=>$result);
		}
		
	}
	
	function payrollRollBack($companyId,$currentPayrollMonth,$payrollRollbackMonth,$leaveBasedOn,$ispartial) {
		$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
		//leave Set i201718
$stmtPre="";
		$stmtPre="UPDATE emp_leave_account acc 
			INNER JOIN payroll p
			ON acc.employee_id = p.employee_id AND p.month_year = '$payrollRollbackMonth' AND acc.`year` = '$leaveBasedOn'
            SET acc.availed = acc.availed - ( CASE";  
        $result=mysqli_query ($this->conn, "SELECT leave_rule_id FROM company_leave_rules lr WHERE enabled=1");
		while ($row = mysqli_fetch_array ($result,MYSQLI_ASSOC)) {
			$stmtPre.=" WHEN leave_rule_id ='".$row['leave_rule_id']."' THEN p.".$row['leave_rule_id'];
		}
		$stmtPre.=" END );";
		$sql="";
		$rowcount=mysqli_num_rows($result);
		$dataVal=($rowcount>0)?$stmtPre:'';
	    $sql.="$dataVal UPDATE payroll_preview_temp SET is_processed=0, status_flag='A';
	    DELETE FROM payroll WHERE month_year= '$payrollRollbackMonth';DELETE FROM emp_montly_attendance WHERE month_year= '$payrollRollbackMonth' AND `year`='$leaveBasedOn';";
		if ($result=mysqli_multi_query($this->conn, $sql)) {
	    		do {
	    			if ($result = mysqli_store_result ( $this->conn)) {
	    				mysqli_free_result($result);
	    			}
	    		} while ( mysqli_more_results ( $this->conn) && mysqli_next_result ( $this->conn ));
	    		
	    		
	    		if($ispartial==0){
	    			$stmt1 = mysqli_query( $this->masterConn, "UPDATE company_details SET current_payroll_month = '$payrollRollbackMonth' WHERE company_id = '$companyId';") or die(mysqli_error($this->masterConn));
	    			$result = mysqli_query ($this->conn, "UPDATE company_details SET current_payroll_month = '$payrollRollbackMonth' WHERE company_id = '$companyId';");
	    		}
	    		$result=true;
	    		 
	    }else{
	    	$result= mysqli_error ( $this->conn );
	    }
	    
	   return $result;
  	}
		
  	function getEmployeeDetails($companyId,$employeeId) {
  		$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
  		   $ajson = array ();
  		    if($result = mysqli_query ($this->conn,"SELECT  w.employee_id ,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,
  				                                dep.department_name,des.designation_name,br.branch_name, DATE_FORMAT(w.employee_doj, '%d/%m/%Y') employee_doj,np.last_working_date
  		   		                                ,IF(np.last_working_date IS NULL ,'Active','Inactive') status ,np.last_working_date
			  				                    FROM
			                                    employee_work_details w 
			                                    INNER JOIN employee_personal_details p 
			                                    ON w.employee_id = p.employee_id 
			                                    INNER JOIN company_designations des
			                                    ON w.designation_id = des.designation_id
			                                    INNER JOIN company_departments dep
			                                    ON w.department_id = dep.department_id
			                                    INNER JOIN company_branch br
			                                    ON w.branch_id = br.branch_id
  		   		                                LEFT JOIN emp_notice_period np
			                                    ON w.employee_id = np.employee_id
			                                    INNER JOIN company_details c 
			                                    ON c.company_id ='$companyId' and c.info_flag='A' AND w.employee_id='$employeeId' ")){
  		  while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
  			array_push ( $ajson, $row );
  		  }
  	if(count($ajson)>0){
			return array('result'=>true,'data'=>$ajson);
		}else{
			return array('result'=>true,'data'=>"No Data Found");
		}
		}else{
			return array('result'=>false,'data'=>$result);
		}
  	}
  	
  	function wipeEmployee($compId,$employeeId,$deleteTablDetails) {
  	$this->conn = mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, $this->company_db_name) or die(mysqli_conn_error());
  	$deleteRowArray=array("emp"=>array("DELETE FROM  comp_promotions_increments WHERE affected_ids='{$employeeId}';
	DELETE FROM  comp_transfers WHERE affected_ids='{$employeeId}';
	DELETE FROM  emp_work_history WHERE employee_id='{$employeeId}';
	DELETE FROM  employee_salary_details_history WHERE employee_id='{$employeeId}';
	DELETE FROM  employee_salary_details_shadow WHERE employee_id='{$employeeId}';
	DELETE FROM  emp_branch_history WHERE employee_id='{$employeeId}';
	DELETE FROM  emp_designation_history WHERE employee_id='{$employeeId}';
	DELETE FROM  arrears WHERE employee_id='{$employeeId}';
	DELETE FROM  payroll_preview_temp WHERE employee_id='{$employeeId}';
	DELETE FROM  employee_personal_details WHERE employee_id='{$employeeId}';
	DELETE FROM  employee_salary_details WHERE employee_id='{$employeeId}';
	DELETE FROM  employee_work_details WHERE employee_id='{$employeeId}';"),
	"att"=>array("DELETE FROM emp_leave_account WHERE employee_id='{$employeeId}';DELETE FROM emp_montly_attendance WHERE employee_id='$employeeId';"),
	"tax"=>array("DELETE FROM  employee_it_declaration WHERE employee_id='{$employeeId}';DELETE FROM  employee_income_tax WHERE employee_id='{$employeeId}';"),
	"asset"=>array("DELETE FROM  asset_requests WHERE employee_id='{$employeeId}';"),
	"claims"=>array("DELETE FROM  claims WHERE employee_id='{$employeeId}';DELETE FROM  claim_mapping WHERE applicable_id='{$employeeId}';"),
	"notifi"=>array("DELETE FROM  notifications WHERE sender_id='{$employeeId}';"),
	"msg"=>array("DELETE FROM  message WHERE creator_id='{$employeeId}';DELETE FROM message_recipient WHERE recipient_id='{$employeeId}';"),
	"exit"=>array("DELETE FROM  emp_notice_period WHERE employee_id='{$employeeId}';"));
  		
  	$data=explode(',',$deleteTablDetails);	
  	$stmt="";
  	foreach($data as $k =>$v){
  	   if($v=='emp'){
  			$otherStmt =$deleteRowArray[$v][0];
  	   }else{
  			$stmt.=$deleteRowArray[$v][0];
  		}
  	}
  	$emplyeeAll=isset($otherStmt)?$otherStmt:"";
  	if ($result=mysqli_multi_query($this->conn, $stmt.$emplyeeAll)) {
  	 	do {
  	 		if ($result = mysqli_store_result ( $this->conn)) {
  	 			mysqli_free_result($result);
  	 		}
  	 	} while ( mysqli_more_results ( $this->conn) && mysqli_next_result ( $this->conn ));
  	}
  	return true;
  	}
  	
  	
  	function getcompanyFeatures($company_id) {
  		$json = array ();
  		$result = mysqli_query ( $this->conn, "SELECT f.feature_name,f.feature_id ft_id,IFNULL(cf.feature_id,'NA') feature_id
												FROM features f
												LEFT JOIN  company_features cf
												ON cf.feature_id = f.feature_id
												AND cf.company_id = '$company_id';" );
  		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
  			array_push ( $json, $row );
  		}
  		return $json;
  		}
  		
  		function addFeatures($company_id) {
  			$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_features (company_id,feature_id)
                values (?,?)" );
					mysqli_stmt_bind_param ( $stmt, 'ss', $this->company_id, $this->feature_id);
					$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_stmt_error ( $stmt );
			return self::getcompanyFeatures($company_id);
  		}
  		
  		function deleteFeatures($company_id) {
  			$stmt = mysqli_query($this->conn,"DELETE FROM company_features WHERE company_id='$company_id' AND  feature_id='$this->feature_id' ");
  			return self::getcompanyFeatures($company_id);
  		}
  		
  		function filterCompany($isAllColum) {
  		$ajson = array (array());
  		$colum=($isAllColum==1)?"*":"cd.company_id ComID, cd.company_name ComName,cd.company_city City,cd.company_state State, 
								cd.company_mobile Mobile, cd.company_email Email,cd.date_of_signUp Signup, ml.master_name CreatedBy, CONCAT(cd.info_flag,'#',cd.enabled) Staus";
	    if($_SESSION['loginIn']!='Master'){
	    	$condition=($_SESSION['loginIn']!='Consultant')?"WHERE cd.created_by IN ( SELECT master_id FROM company_master_login
			WHERE created_by = '".$_SESSION['master_id']."' OR master_id = '".$_SESSION['master_id']."') ":"WHERE cd.created_by = '".$_SESSION['master_id']."';";
	    }else
	    	$condition = "";
	    $query ="SELECT $colum
								FROM ".MASTER_DB_NAME .".company_details cd
				  				INNER JOIN company_master_login ml
								ON cd.created_by = ml.master_id 
								$condition";
	    if($result = mysqli_query ( $this->conn, $query )){
	    	if($isAllColum==1){
	    		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
	    			array_push ( $ajson[0], $row );
	    		}
	    	}else{
	    		while ( $row = mysqli_fetch_array ( $result, MYSQLI_NUM ) ) {
	    			array_push ( $ajson[0], $row );
	    		}
	    	}
  		
  		if(count($ajson[0])>0){
  			mysqli_data_seek ( $result, 0 ); // pointer reset into zero for again fetch same data in mysql using mysqli_data_seek
  			$isheader=array_keys ( mysqli_fetch_assoc ( $result ) );
  			array_push($ajson,$isheader);
  			return array('result'=>true,'data'=>$ajson);
  		}else{
  			return array('result'=>true,'data'=>"No Data Found");
  		}
  		return $json;
  		}else{
  			return array('result'=>false,'data'=>$result);
  		}
  		
  		}
  		function getPendingApprovals($masterId){
  			$query = "SELECT cd.company_id,cd.company_name,cd.info_flag FROM company_details cd WHERE cd.info_flag = 'P' AND cd.created_by = '$masterId'";
  			$result = mysqli_query($this->masterConn,$query) or die(mysqli_error($this->masterConn));
  			$json = array();
  			while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
  				array_push($json,$row);
  			}
  			return $json;
  		}
}
?>
