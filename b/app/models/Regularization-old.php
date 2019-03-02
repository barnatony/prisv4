<?php
class Regularization extends Model {

	function __construct($id='') {
		parent::__construct('id','attendance_regularization'); //primary key = id; tablename = attendance_regularization
		$this->rs['id'] = '';
		$this->rs['employee_id'] = '';
		$this->rs['day'] = '';
		$this->rs['regularize_type'] = '';
		$this->rs['reason_type'] = '';
		$this->rs['reason'] = '';
		$this->rs['status'] = '';
		$this->rs['admin_reason'] = '';
		$this->rs['raised_on'] = '';
		$this->rs['req_token'] = '';
		$this->rs['req_token_expiry'] = '';
		$this->rs['raised_by'] = '';
		$this->rs['approved_on'] = '';
		$this->rs['approved_by'] = '';
		$this->rs['updated_on'] = '';
		$this->rs['updated_by'] = '';
		
		if ($id)
			$this->retrieve($id);
	}

function create() {
	
		$this->rs['raised_on']=date('Y-m-d H:i:s');
		$expiry=date('Y-m-d H:i:s', strtotime("+1 week", strtotime(date('Y-m-d h:i:sa'))));
		$this->merge(array("req_token"=>getToken(),"req_token_expiry"=>$expiry));
		$this->rs['updated_on']=date('Y-m-d H:i:s');
		return parent::create();
}

function update(){
	if($this->get('regularize_type')=='Incorrectpunches' && $this->get('status')=='A')
		$this->InsertIntoBiometric($this->get('employee_id'),$this->get('day'),$this->get('reason_type'),$this->get('reason'));
	return parent::update();
}

function InsertIntoBiometric($empId,$day,$type,$reason){
	
	//insert if regularization is raised for incorrect punches
	$dbh=getdbh();
	if($type=='Missed In Punch')
		$cond="CONCAT(:day,' ',start_time,':00')";
	else 
		$cond = "IF(is_day=0 AND end_time BETWEEN '00:00' AND '10:00',CONCAT(DATE_ADD(:day,INTERVAL 1 DAY),' ',end_time,':00'),CONCAT(:day,' ',end_time,':00'))";
	
	
	$query=$dbh->prepare("INSERT INTO employee_biometric (employee_id,date_time,is_manual,reason) (SELECT DISTINCT d.ref_id,{$cond},'1',:reason
								FROM company_shifts s
								LEFT JOIN device_users d ON d.employee_id=:empId
								WHERE ref_id IS NOT NULL
								AND s.shift_id = (SELECT IF(r.shift_id IS NULL,IF(w.shift_id='Nil' OR w.shift_id = '','SH00001',
								w.shift_id),r.shift_id) shift_id
				                FROM employee_work_details w
				                INNER JOIN device_users u
				                ON w.employee_id = u.employee_id
				                LEFT JOIN  shift_roaster r
				                ON w.employee_id = r.employee_id
				                AND :day BETWEEN r.from_date AND r.to_date
					WHERE w.enabled = 1 AND w.employee_id=:empId)); ");
	
	$query->bindParam('empId', $empId);
	$query->bindParam('day', $day);
	$query->bindParam('updated_by', $empId);
	$query->bindParam('reason', $reason);
	$query->execute();
	
	if(!$query){ //if query not executed throws an error
		print_r($dbh->errorInfo());
	}
}

function gen_regRequest($emp_name,$emp_id,$to){

	
	$settings = new Setting();
	$settings = $settings->retrieve_many();
	foreach($settings as $setting){
		$config[$setting->get('setting')]=$setting->get('value');
	}


	//result gen
	$dbh = getdbh();
	$gen = array("to"=>array(),"subject"=>"","message"=>"","cc"=>array(),"bcc"=>array(),"attachments"=>array());
	$stmt = $dbh->prepare(" SELECT *
									FROM email_templates
									WHERE tplname = 'regRequest' ");
	if(!$stmt->execute())
		die($stmt->errorInfo());
		while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tpl=$rs;
		}
			
		$subject = new emailTemplate($tpl['subject']);
		
			
			
		
			$durationwithoutyr=date('d M ', strtotime($this->get("day")));
			$day= date('d M,Y', strtotime($this->get('day')));
			$subject->set('emp_name',$emp_name);
			$subject->set('type',strtoupper($this->get('reason_type')));
			$subject->set('durationwithoutyr',$durationwithoutyr);
			
				
				
			$subj = $subject->output();
			$body = new emailTemplate($tpl['message']);
			$body->set('company_logo',$config['company_logo']);
			$body->set('company_short_name',$config['company_short_name']);
			$body->set('emp_name', $emp_name);
			$body->set('empId',$emp_id);
			$body->set('days',$day);
			$body->set('type',strtoupper($this->get('regularize_type')));
			$body->set('reason_type',strtoupper($this->get('reason_type')));
			$body->set('reason',$this->get('reason'));
			$body->set('company_short_name',$config['company_short_name']);
			$body->set('footer_text',$config['footer_text']);
			$id=base64_encode($this->get("id"));
			$company_id=base64_encode($_SESSION['company_id']);
			
			$url = myUrl('attendance/respond/?cid='.$company_id.'&id='.$id.'&token='.$this->get("req_token").'&rep_id='.base64_encode($to['id']),true);
			$body->set('respondLink',$url);
			
			$body_o = $body->output();

			$sendTo=array();
			$sendTo[]=$to;
			$gen["to"]=$sendTo;
			$gen['subject'] = $subj;
			$gen['message'] = $body_o;
			
			
			return $gen;
}
function gen_regResponse(){
	$settings = new Setting();
	$settings = $settings->retrieve_many();
	foreach($settings as $setting){
		$config[$setting->get('setting')]=$setting->get('value');
	}
	
	//result gen
	$dbh = getdbh();
	$gen = array("to"=>array(),"subject"=>"","message"=>"","cc"=>array(),"bcc"=>array(),"attachments"=>array());
	$stmt = $dbh->prepare(" SELECT *
									FROM email_templates
									WHERE tplname = 'regResponse' ");
	if(!$stmt->execute())
		die($stmt->errorInfo());
		while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tpl=$rs;
		}
	
		$subject = new emailTemplate($tpl['subject']);
		$employee=$this->joined_select('r.id,r.employee_id,w.employee_name,p.employee_email,r.day,r.regularize_type,r.reason_type,r.req_token,r.req_token_expiry,r.reason,r.status,r.admin_reason,r.raised_by,r.approved_by,r.approved_on ',
									'attendance_regularization r 
										 LEFT JOIN employee_work_details w ON r.employee_id=w.employee_id
										 LEFT JOIN employee_personal_details p ON r.employee_id=p.employee_id','r.id=? ',$this->get('id'))[0];
		
		$leavtype=strtoupper($employee['regularize_type']);
			
		if($this->get('status')=='A'){
			$status='Approved';
		}elseif($this->get('status')=='R'){
			$status='Rejected';
		}
		
		
		$day= date('d M,Y', strtotime($employee['day']));
		$durationwithoutyr=date('d M ', strtotime($employee["day"]));
		$subject->set('durationwithoutyr',$durationwithoutyr);
		$subject->set('status',$status);
		
		$subj = $subject->output();
		$body = new emailTemplate($tpl['message']);
		$body->set('company_logo',$config['company_logo']);
		$body->set('company_short_name',$config['company_short_name']);
		$body->set('status', $status);
			
			
		$body->set('employee_name',$employee['employee_name']);
		$body->set('day',$day);
		$body->set('reason_type',strtoupper($employee['reason_type']));
		$body->set('reason',$employee['reason']);
		$body->set('admin_reason',$this->get('admin_reason'));
		$body->set('company_short_name',$config['company_short_name']);
		$body->set('company_short_address',$config['company_short_address']);
		$body->set('footer_text',$config['footer_text']);
		$reqId=$this->get('id');
		$company_id=$_SESSION['company_id'];
		$url = $config['app_login_url']."/?next=".myUrl('redirect/page/?url=attendance/regularization');
		$body->set('app_login_url',$url);
			
		$body_o = $body->output();
		$gen["to"][] = array("email"=>$employee['employee_email'],"name"=>$employee['employee_name']);
			
		$gen['subject'] = $subj;
		$gen['message'] = $body_o;
		
		
		return $gen;
}


}