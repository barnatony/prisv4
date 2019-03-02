<?php
require_once (__DIR__ . "/database.class.php");
require_once (__DIR__ . "/phpMailer.php");

Class NotifyEmail extends Database
{
    protected $contents;
    var $conn;
    protected $values = array();
    function __construct(){
    	parent::__construct();
    }
  
    public function _init()
    {
    	$result = mysqli_query ( $this->_connection, "SELECT company_email,company_name,email_method FROM company_details cd WHERE cd.company_id = '" . $_SESSION ['company_id'] . "' AND cd.info_flag='A'" );
    	//echo "SELECT company_email,company_name,email_method FROM company_details cd WHERE cd.company_id = '" . $_SESSION ['company_id'] . "' AND cd.info_flag='A'" ;
    	$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
    	$sysEmail = $row['company_email'];
    	//For Yahoo Only Set their Original Yahoo ids,if secondary email acepted means use Write@hrlabz.in
    	//IN yahoo if ges error like this means MAIL FROM command failed,553,,From address not verified,,,,set secondarry email for setFrom() function..
    	//set  zoho and Yahoo  Set secondary mail for SetFrom() ;
    	//yahoo received email is very slow compare to zoho and gmail
        $sysCompany = $row['company_name'];
        $mail = new phpMailer();
        $mail->CharSet = 'UTF-8';
        //check for smtp
        if($row['email_method'] == 'smtp'){

        	$result = mysqli_query ($this->_connection, "SELECT host,username,password,secure,port FROM emailconfig ec WHERE ec.active=1" );
    	    $emailConfigs = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
        	$mail->isSMTP();
        	$mail->Host = $emailConfigs['host'];
        	$mail->SMTPAuth = true;
        	$mail->Username = $emailConfigs['username'];
        	$mail->Password = $emailConfigs['password'];
        	$mail->SMTPSecure = $emailConfigs['secure'];
        	$mail->Port = $emailConfigs['port'];
        }
        $mail->SetFrom($sysEmail, $sysCompany);//setFrom Function

        $mail->AddReplyTo($sysEmail, $sysCompany);

        return $mail;
    }

    public function _send($name,$to,$subject,$message,$attachments=null,$attachmentNames=null,$ccs=null,$bccs=null,$attachmentType="string"){
    	 $mail = self::_init();
    	 $name=($name==null)?$mail->FromName:$name;
    	 $mail->AddAddress($to,$name);
    	 //eval ( "\$message = \"$message\";" ); //for companyName Get Eval did
    	 $mail->Subject = $subject;
   
    	 $mail->MsgHTML($message);
    	 if($ccs)
    	 	foreach ($ccs as $cc)
    	 		$mail->AddCC($cc["email"],$cc["name"]);
		 if($bccs)
    	 	foreach ($bccs as $bcc)
    	 		$mail->AddBCC($bcc["email"],$bcc["name"]);
        if($attachments!=null){
        	foreach($attachments as $key => $attachment){
        		if($attachmentType == "string")
        			$mail->AddStringAttachment($attachment, $attachmentNames[$key]);
        		else
        			$mail->AddAttachment($attachment, $attachmentNames[$key]);
	        }
        }
        if ($mail->Send()) {

            $result = 1;
         } else {
       	echo $mail->ErrorInfo;
        	$result = 0;
        }
        return $result;
        }

    public static function _test()
    {
        $mail = self::_init();
        $email = 'donotreply@bdinfosys.com';
        $mail_subject = 'Test Email';
        $name = 'Razib';
        $body = 'Hello this is test email body';
        $mail->AddAddress($email, $name);
        $mail->Subject = $mail_subject;
        $mail->MsgHTML($body);
        $mail->Send();

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

   

   }
