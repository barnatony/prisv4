<?php
CLASS Mail extends Model{
	function __construct($id='') {
		parent::__construct('id','emailconfig'); //primary key = uid; tablename = users
	$this->rs['id'] = '';
	$this->rs['host'] = '';
	$this->rs['username'] = '';
	$this->rs['password'] = '';
	$this->rs['port'] = '';
	$this->rs['secure'] = '';
	$this->rs['active'] = '0';
	if ($id)
		$this->retrieve($id);
	}
	
	function getAllDetails(){
		$dbh = getdbh();
		$config = array();
		$stmt = $dbh->prepare('SELECT host,username,password,secure FROM emailconfig WHERE active=1');
		$stmt->execute();
		while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$config[]=$rs;
		}
		return $config;
		
	}

	function sendMail($sdata){
		
		$res = $this->getAllDetails();
		foreach ($res as $mailfun){}
		//require_once 'phpmailer/PHPMailerAutoload.php';
		
		//$mail = new PHPMailer();
		$mail = new phpMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Host = $mailfun['host'];
		
		$mail->IsSMTP();
		
		$mail->SMTPAuth = true;
		$mail->Username = $mailfun['username'];
		$mail->Password = $mailfun['password'];
		$mail->SMTPSecure = $mailfun['secure'];
		
		$mail->From = $mailfun['username'];
		$mail->FromName = $sdata['company_name'];
		$mail->addAddress($_REQUEST['email']);
		$mail->addCC($sdata['company_email']);
		$mail->isHTML(true);
		
		$mail->Subject ='Enquiry on '.$sdata['company_name'];
		$mail->Body = '<h4 style="border-top:7px solid #a5597f;padding:20px 20px;font-family:Open Sans,sans-serif;line-height:29px;text-align:left;margin-bottom:20px;font-size:22px">Dear '.$_REQUEST['email'].',<br>
</h4>
<h4 style="text-align:left;font-size:16px;margin-bottom:20px;padding:0px 20px;font-family:Open Sans,sans-serif;">Thanks For Contacting Our Site!..</h4>
<p style="text-align:left;font-size:16px;margin-bottom:20px;padding:0px 20px;font-family:Open Sans,sans-serif;">
	Name: '.$_REQUEST['email'].'   <br><br>
	Email: '.$_REQUEST['email'].'  <br>	<br>
	Subject: Test Mail    <br>	<br>
	Message: This is test message   <br>	<br>
		
</p><br>
<div style="text-align:left;font-family:Open Sans,sans-serif;background:white;padding:0px 20px;padding-bottom:20px;line-height:11px;font-weight:100;font-size:16px">
        <p>Regards</p>
        <p style="">'.$sdata['company_name'].' Team</p>
        <p>Contact: '.$sdata['contact_no'].'</p>
        <p>Email: <a href="'.$sdata['company_email'].'" target="_blank">'.$sdata['company_email'].'</a></p>
      </div>';
		print_r($mail);
		if(!$mail->send()) {
			$result = array(false,"info"=>$mail->ErrorInfo);
		}
		else{
			$result = array(true,"info"=>'Successfully Mail Sent');
		}
		return $result;
	}
	function setActiveEmail(){
		try{
				$this->getdbh()->beginTransaction();
				$result = getdbh()->query('UPDATE emailconfig SET active=0');
				if(!$result->execute()){
					$result = array(true,"rowCount"=>$stmt->rowCount(),"info"=>$stmt->errorInfo()[2]); 
				}
				$result = self::update();
		if(!$this->result[0])
					throw new Exception('error');
				$this->getdbh()->commit();
			}catch (Exception $e){
				$this->getdbh()->rollBack();
			}
			return $this;
	}
}