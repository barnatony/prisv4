<?php

Class NotifyEmail extends Model
{


	protected $contents;
	protected $values = array();

	public static function _init($config_id=null)
	{
		
		$settings = new Setting();
		$settings = $settings->retrieve_many();
		foreach($settings as $setting){
			$config[$setting->get('setting')]=$mdata[$setting->get('setting')]=$setting->get('value');
		}
		$sysEmail = $config['company_support_email'];
		$sysCompany = $config['company_name'];
		$mail = new phpMailer();
		$mail->CharSet = 'UTF-8';
		//check for smtp
		$smtpUsernameSetFor = array("smtp.zoho.com","smtpout.secureserver.net");
		$smtpNoSecure = array("smtpout.secureserver.net");
		$mail->SetFrom($sysEmail, $sysCompany);
		$emailConfigs = new Mail();
		if($config_id)
			$emailConfigs->retrieve_one("id=?",$config_id);
		else 
			$emailConfigs->retrieve_one("active=?",1);
		if(!$emailConfigs->exists())
			return false;
		if($config['email_method'] == 'smtp'){
			$mail->isSMTP();
			//$mail->SMTPDebug = 2;
			$mail->Host =$emailConfigs->get('host');
			$mail->SMTPAuth = true;
			$mail->Username = $emailConfigs->get('username');
			$mail->Password = $emailConfigs->get('password');
			$mail->SMTPSecure = $emailConfigs->get('secure');
			$mail->Port = $emailConfigs->get('port');
			if(in_array($mail->Host,$smtpUsernameSetFor))
				$mail->SetFrom($emailConfigs->get('username'), $sysCompany);
			if(in_array($mail->Host,$smtpNoSecure))
				$mail->SMTPSecure = false;
		}
		
		
		$mail->AddReplyTo($sysEmail, $sysCompany);
		
		return $mail;
	}


	public static function _log($userid, $email, $subject, $message, $iid='0')
	{
		$date = date('Y-m-d H:i:s');
		$d = ORM::for_table('sys_email_logs')->create();
		$d->userid = $userid;
		$d->sender = '';
		$d->email = $email;
		$d->subject = $subject;
		$d->message = $message;
		$d->date = $date;
		$d->iid = $iid;
		$d->save();
		$id = $d->id();
		return $id;

	}


	public static function _test($config_id,$to){
		$mail = self::_init($config_id);
		$email = $to;
		$mail_subject = 'Test Email';
		$name = $to;
		$body = 'Hello this is test email body';
		$mail->AddAddress($email, $name);
		$mail->Subject = $mail_subject;
		$mail->MsgHTML($body);
		if ($mail->Send())
			return true;
		 else 
			return $mail->ErrorInfo;
	}

	public static function _otp($otp,$name,$email)
	{
		$mail = self::_init();
		global $config;

		$sysCompany = $config['CompanyName'];
		$mail_subject = $sysCompany . ' OTP (One Time Password)';

		$body = 'Your '.$sysCompany.' password has been verified and OTP is required to proceed further. Your current session OTP is '.$otp.' and only valid for this session. If you didn\'t login, please contact us immediately.';
		$mail->AddAddress($email, $name);
		$mail->Subject = $mail_subject;
		$mail->MsgHTML($body);
		$mail->Send();

	}
	//multi send for email
	
	public static function _send($to,$subject,$message,$cc=null,$bcc=null,$attachments=null){
		$mail = self::_init();
		
		if(!$mail)
			return false;
		//to,cc,bcc mails for array -> array(array("email"=>johndoe@example.com,"name"=>"John Doe"))
		//to,cc,bcc mails for not array -> johndoe@example.com
	
		//to
		//notification exclusions will come here
		/* if($to){
		 $dbh = getdbh();
		 $query = "SELECT u.user_id,u.email,e.notification_type
		 FROM users u
		 INNER JOIN notification_exclusions e
		 ON u.user_id = e.user_id
		 WHERE
		 u.email = :to_email
		 AND
		 e.notification_type = :notification_type";
		 $stmt = $dbh->prepare($query);
		 $stmt->bindParam("to_email", $to);
		 $stmt->bindParam("notification_type", $notification_type);
		 } */
	
		if(is_array($to))
			foreach($to as $toMail)
				$mail->AddAddress($toMail["email"],$toMail["name"]);
				else
					$mail->AddAddress($to,$to);
	
	
					//cc
					if($cc){
						if(is_array($cc))
							foreach($cc as $ccMail)
								$mail->AddCC($ccMail["email"],$ccMail["name"]);
								else
									$mail->AddCC($cc,$cc);
					}
	
					//bcc
					if($bcc){
						if(is_array($bcc))
							foreach($bcc as $bccMail)
								$mail->AddBCC($bccMail["email"],$bccMail["name"]);
								else
									$mail->AddBCC($bcc,$bcc);
					}
	
					//content
					$mail->Subject = $subject;
					$mail->MsgHTML($message);
	
					//attachments
					//multipe attachments array -> array(array("file"=>'file/path/goes/here.extension',"name"=>'Sample File',"type"=>'string'))
					//single attachment without names "file/path/goes/here.extension"
					if($attachments){
						if(is_array($attachments))
							foreach($attachments as $key => $attachment){
								if(isset($attachment["type"]) && $attachment['type'] == 'string')
									$mail->AddStringAttachment($attachment["file"], $attachment["name"], 'base64', 'application/octet-stream');
									else
										$mail->AddAttachment($attachment["file"], $attachment["name"], 'base64', 'application/octet-stream');
						}
						else
							$mail->AddAttachment($attachments, substr($attachments,(strrpos($attachments,"/")+1)), 'base64', 'application/octet-stream');
					}
	
					if ($mail->Send()) {
						$result = 1;
					} else {
						//echo $mail->ErrorInfo;
						$result = 0;
					}
					return $result;
	}
	
	}
	?>
