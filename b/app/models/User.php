<?php
class User extends Model {

	function __construct($user_id='') {
		parent::__construct('user_id','users'); //primary key = uid; tablename = users
		$this->rs['user_id'] = '';
		$this->rs['username'] = '';
		$this->rs['password'] = '';
		$this->rs['name'] = '';
		$this->rs['lastname'] = '';
		$this->rs['dob'] = '0000-00-00';
		$this->rs['image'] = '';
		$this->rs['gender'] = '';
		$this->rs['email'] = '';
		$this->rs['privilage'] = 'User';
		$this->rs['p_reset_token'] = '';
		$this->rs['p_reset_expiry'] = '0000-00-00 00:00:00';
		$this->rs['last_login'] = '0000-00-00 00:00:00';
		$this->rs['created_dt'] = '';
		$this->rs['isActive'] = '';
		if ($user_id)
			$this->retrieve($user_id);
	}

	function create() {
		$this->rs['created_dt']=date('Y-m-d H:i:s');
		$this->rs["image"]="img/avatar.jpg";
		return parent::create();
	}

	function gen_email($for,$params=null){
		$settings = new Setting();
		$settings = $settings->retrieve_many();
		foreach($settings as $setting){
			$config[$setting->get('setting')]=$setting->get('value');
		}
		//result gen
		$gen = array("to"=>array(),"cc"=>array(),"bcc"=>array(),"attachments"=>array(),"subject"=>"","message"=>"");
		//mail
		$dbh = getdbh();
		switch ($for){
			case "Password Reset":
				$stmt = $dbh->prepare(" SELECT *
									FROM email_templates
									WHERE tplname = 'Password Reset' ");
				if(!$stmt->execute())
					die($stmt->errorInfo());
					while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$tpl=$rs;
					}
					$subject = new emailTemplate($tpl['subject']);
					$subject->set('user_name', $this->get("name"));
					$subj = $subject->output();
					$body = new emailTemplate($tpl['message']);
					$resetUrl= myUrl('profile/resetPassword/'.$this->get('p_reset_token').'/',true);
					$body->set('password_reset_url', $resetUrl);
					$body->set('expiry_date', $this->get('p_reset_expiry'));
					$body->set('company_email', $config['email']);
					$body->set('company_name', $config['company_name']);
					$body->set('address_inline', str_replace(array("<br>","<p>","</p>"), "", $config['company_address']));
					$body->set('contact_no', $config['contact_no']);
					$body_o = $body->output();
					if(!$this->get('name'))
					$gen["to"] = $this->get('email');
					else
					$gen["to"][] = array("email"=>$this->get('email'),"name"=>$this->get('name'));
					$gen['subject'] = $subj;
			     	$gen['message'] = $body_o;
					break;
			   case "Sign Up":
				$stmt = $dbh->prepare(" SELECT *
									FROM email_templates
									WHERE tplname = 'Sign Up' ");
				if(!$stmt->execute())
					die($stmt->errorInfo());
					while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$tpl=$rs;
					}
					$subject = new emailTemplate($tpl['subject']);
					$subject->set('name', $this->get("name"));
					$subject->set('company_name', $config["company_name"]);
					$subj = $subject->output();
					$body = new emailTemplate($tpl['message']);
					$body->set('name', $this->get("name"));
					$body->set('company_email', $config['email']);
                     
					if($params && $params=="autoCreate")
						$login_url= myUrl('profile/resetPassword/'.$this->get('p_reset_token').'/',true);
						else
							$login_url = myUrl('main/login',true);
							$body->set("login_url", $login_url);
							$body->set('company_name', $config['company_name']);
							$body->set('address_inline', str_replace(array("<br>","<p>","</p>"), "", $config['company_address']));
							$body->set('contact_no', $config['contact_no']);
							$body_o = $body->output();
							if(!$this->get('name'))
							$gen["to"] = $this->get('email');
							else
							$gen["to"][] = array("email"=>$this->get('email'),"name"=>$this->get('name'));
							$gen['subject'] = $subj;
							$gen['message'] = $body_o;
							break;
		}
		 
		return $gen;

	}
}