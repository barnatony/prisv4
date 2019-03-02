<?php
class LeaveRequest extends Model {

	function __construct($id='') {
		parent::__construct('id','leave_requests'); //primary key = post_id; tablename = blog
		$this->rs['id'] = '';
		$this->rs['request_id'] = '';
		$this->rs['employee_id'] = '';
		$this->rs['from_date'] = '';
		$this->rs['from_half'] = ' ';
		$this->rs['to_date'] = '';
		$this->rs['to_half'] = '';
		$this->rs['duration'] = '';
		$this->rs['leave_type'] = '';
		$this->rs['reason'] = '';
		$this->rs['status'] = ''; //RQ- Requested(Pending for Approval), R - Rejected, C - Cancelled (cancelled approved leave by employee), W - withdrawed ()
		$this->rs['from_date'] = '';
		$this->rs['to_date'] = '';
		$this->rs['admin_reason'] = '';
		$this->rs['approved_on'] = '';
		$this->rs['approved_by'] = '';
		$this->rs['updated_on']='';
		$this->rs['updated_by'] = '';
		$this->rs['req_token'] = '';
		$this->rs['req_token_expiry'] = '';
		
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
		//to insert into employee absentees
		$startDate =$day= $this->get('from_date');
		$endDate = $this->get('to_date');
		$lrId = $this->get("request_id");
		$count=1;
		$leaveCount=0;
		if($this->get('status')=='A'){
			while(strtotime($day) <= strtotime($endDate)){
				$dayType ="FD";
				$dayCount=1;
				if($day == $startDate)
					if($this->get("from_half")=="SH"){
						$dayCount=0.5;
						$dayType= "SH";
				}
				if($day==$endDate)
					if($this->get("to_half")=="FH"){
						$dayCount=0.5;
						$dayType= "FH";
				}
					
				$query="INSERT INTO emp_absences (`employee_id`,`request_id`,`absent_date`,`leave_rule_type`,`day_type`,`day_count`,`reason`,`updated_by`)
				VALUES(:employee_id,:request_id,:day,:leave_type,:dayType,:dayCount,:reason,:approved_by)";
				$stmt = $this->getdbh()->prepare($query);
				$employee_id = $this->get('employee_id');
				$request_id = $this->get('request_id');
				$leave_type =$this->get('leave_type');
				$reason =$this->get('reason');
				$approved_by = $this->get('approved_by');
				$stmt->bindParam('employee_id', $employee_id);
				$stmt->bindParam('request_id', $request_id);
				$stmt->bindParam('day', $day);
				$stmt->bindParam('leave_type',$leave_type);
				$stmt->bindParam('dayType', $dayType);
				$stmt->bindParam('dayCount', $dayCount);
				$stmt->bindParam('reason', $reason);
				$stmt->bindParam('approved_by',$approved_by);
				$stmt->execute();
				if(!$stmt){ //if query not executed throws an error
					$this->result = array(false,"rowCount"=>0,"info"=>$dbh->errorInfo()[2]);
					return $this;
				}
				$leaveCount+=$dayCount;
				$day = date ("Y-m-d", strtotime("+1 day", strtotime($day)));
			}
		}else if($this->get("status")=="C"){
			$query="DELETE FROM emp_absences WHERE request_id ='{$this->get('request_id')}'";
			$dbh = getdbh();
			$stmt=$dbh->query($query);
			if(!$stmt){ //if query not executed throws an error
				return array(false,"rowCount"=>0,"info"=>$dbh->errorInfo()[2]);
			}
		}
		
		if($this->get("status") == "A" || $this->get("status") == "C"){
			//Update the leave details in payroll preview temp
			$leaveRule = new LeaveRule();
			
			$dbh = getdbh();
			//$stmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
	  			//				FROM company_details WHERE info_flag="A" AND company_id=:company_id');
	  			
			$stmt=$dbh->prepare("SELECT DATE_FORMAT(mc.current_payroll_month,'%Y') payrollYear,DATE_FORMAT(mc.current_payroll_month,'%m') monthNo,
									mc.leave_based_on creditLeaveBased,cd.attendance_period_sdate attendance_dt,IF(mc.leave_based_on='finYear',IF((DATE_FORMAT(mc.current_payroll_month,'%m')IN ('01','02','03')),CONCAT((DATE_FORMAT(mc.current_payroll_month,'%Y')-1),SUBSTRING(DATE_FORMAT(mc.current_payroll_month,'%Y'),3,4)),CONCAT(DATE_FORMAT(mc.current_payroll_month,'%Y'),(SUBSTRING(DATE_FORMAT(mc.current_payroll_month,'%Y'),3,4)+1))),DATE_FORMAT(mc.current_payroll_month,'%Y')) financialYear
									FROM company_master_db.company_details mc
									INNER JOIN company_details cd
									ON mc.company_id = cd.company_id
									WHERE mc.company_id=:company_id AND mc.info_flag='A';");
			
			$company_id=isset($_REQUEST['cid'])?$_REQUEST['cid']:$_SESSION['company_id'];
			$stmt->bindParam('company_id',$company_id);
			$stmt->execute();
			$companyProp = $stmt->fetch();
			
			
			if($companyProp['attendance_dt'] !=1){
				$startDate = $companyProp['payrollYear']."-".($companyProp['monthNo'])."-".$companyProp['attendance_dt'];
				$startDate = date("Y-m-d",strtotime("{$startDate} -1 months"));
				$endDate = $companyProp['payrollYear']."-".($companyProp['monthNo'])."-".($companyProp['attendance_dt']-1);
			}else{
				$startDate = $companyProp['payrollYear']."-".($companyProp['monthNo'])."-01";
				$endDate = date('Y-m-t',strtotime($startDate));
			}
			$year = $companyProp ['creditLeaveBased'] == 'finYear' ? $companyProp ['financialYear'] : $companyProp ['payrollYear'];
			
			
			$leaveRule->updateLeavesInPayroll($startDate, $endDate,$this->get("employee_id"));
		}
		return parent::update();
	}
	
	
	function gen_leaveRequest($emp_name,$emp_id,$to){
		
		
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
									WHERE tplname = 'leaveApply' ");
		if(!$stmt->execute())
			die($stmt->errorInfo());
			while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$tpl=$rs;
			}
			
			$subject = new emailTemplate($tpl['subject']);
			$start= date('M,d', strtotime($this->get('from_date')));
			$end=date('M,d', strtotime($this->get('to_date')));
			
			if($this->get('to_date')==$this->get('from_date'))
				$durationwithoutyr=date('d M ', strtotime($this->get('from_date')));
			else
				$durationwithoutyr=date('d M', strtotime($this->get('from_date')))."-".date('d M ', strtotime($this->get('to_date')));
			
			
			if($this->get('to_date')==$this->get('from_date'))
				$durationwithyr=date('d M,Y', strtotime($this->get('from_date')));
			else
				$durationwithyr=date('d M,Y', strtotime($this->get('from_date')))."-".date('d M,Y', strtotime($this->get('to_date')));
			
			
			//to get employeeDetails
			$employee=$this->joined_select('l.request_id,l.employee_id,w.employee_name,l.from_date sdate,
												l.to_date edate,l.duration,l.leave_type,l.reason,l.status,wd.employee_id rep_id,
												l.raised_on,l.admin_reason,wd.employee_name reporting_person_name,pd.employee_email,p.employee_email reporting_email,l.approved_by,l.approved_on,l.updated_by,l.updated_on,
												l.req_token,l.req_token_expiry',
											'leave_requests l 
												LEFT JOIN employee_work_details w ON l.employee_id = w.employee_id
												LEFT JOIN employee_work_details wd ON w.employee_reporting_person =wd.employee_id
												LEFT JOIN employee_personal_details pd ON l.employee_id = pd.employee_id
												LEFT JOIN employee_personal_details p ON wd.employee_id=p.employee_id',
											'l.employee_id=? AND l.request_id = ?',array($_SESSION['employee_id'],$this->get('request_id')) )[0];
			
			
			//to get company email 
			$query="SELECT user_name,password,emp_id,login_privilage,email,last_login,send_email FROM company_login_details WHERE send_email=1";
			$dbh = getdbh();
			$stmt=$dbh->query($query);
			if(!$stmt)
				die($stmt->errorInfo()[1]);
			$company_logins =$stmt->fetchAll(PDO::FETCH_ASSOC);
			
			$subject->set('emp_name',$emp_name);
			$subject->set('type',strtoupper($this->get('leave_type')));
			$subject->set('durationwithoutyr',$durationwithoutyr);
			
			
			
			$subj = $subject->output();
			$body = new emailTemplate($tpl['message']);
			$body->set('company_logo',$config['company_logo']);
			$body->set('company_short_name',$config['company_short_name']);
			$body->set('emp_name', $emp_name);
			$body->set('empId',$emp_id);
			$body->set('durationwithyr',$durationwithyr);
			$body->set('days',$this->get('duration'));
			$body->set('type',strtoupper($this->get('leave_type')));
			$body->set('reason',$this->get('reason'));
			$body->set('company_short_name',$config['company_short_name']);
			$body->set('footer_text',$config['footer_text']);
			$reqId=base64_encode($this->get("request_id"));
			$id=base64_encode($this->get("id"));
			$company_id=base64_encode($_SESSION['company_id']);
			$rep_id=base64_encode($to['id']);
			
			$url = myUrl('leaveRequests/respond/?cid='.$company_id.'&req='.$reqId.'&id='.$id.'&token='.$this->get("req_token").'&rep_id='.$rep_id,true);
			$body->set('respondLink',$url);
			
			$body_o = $body->output();
			
			$sendTo=array();
			$sendTo[]=$to;
			$gen["to"]=$sendTo;
			$gen['subject'] = $subj;
			$gen['message'] = $body_o;
			
			
			return $gen;
			
			
			
	}
	
	
	
	function gen_LeaveResponse(){
		
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
									WHERE tplname = 'leaveResponse' ");
		if(!$stmt->execute())
			die($stmt->errorInfo());
			while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$tpl=$rs;
			}
				
			$subject = new emailTemplate($tpl['subject']);
			$leave=$this->joined_select('l.id,l.request_id,l.employee_id,w.employee_name,pd.employee_email,l.from_date,l.to_date,l.duration,l.leave_type,l.reason,l.status,l.raised_on','leave_requests l
										 	LEFT JOIN employee_work_details w ON w.employee_id=l.employee_id
											LEFT JOIN employee_personal_details pd ON pd.employee_id=l.employee_id','l.id=? AND l.employee_id = ? AND l.request_id=?',array($this->get('id'),$this->get('employee_id'),$this->get('request_id')))[0];
			
			$leavtype=strtoupper($leave['leave_type']);
			
			if($_REQUEST['value']=='A'){
				$status='Approved';
			}elseif($_REQUEST['value']=='R'){
				$status='Rejected';
			}
			
			$subject->set('leave_type',$leavtype);
			$subject->set('leaveRequest_status',$status);
			
			if($leave['duration']!='1')
				$durationwithyr=date('d M,Y', strtotime($this->get('from_date')))."-".date('d M,Y', strtotime($this->get('to_date')));
			else
				$durationwithyr=date('d M,Y', strtotime($this->get('from_date')));
						
			$subj = $subject->output();
			$body = new emailTemplate($tpl['message']);
			$body->set('company_logo',$config['company_logo']);
			$body->set('company_short_name',$config['company_short_name']);
			$body->set('leaveRequest_status', $status);
			
			
			$body->set('employee_name',$leave['employee_name']);
			$body->set('durationwithyr',$durationwithyr);
			$body->set('leave_days',$leave['duration']);
			$body->set('leave_type',strtoupper($leave['leave_type']));
			$body->set('leave_reason',$leave['reason']);
			$body->set('approver_remarks',$this->get('admin_reason'));
			$body->set('company_short_name',$config['company_short_name']);
			$body->set('company_short_address',$config['company_short_address']);
			$body->set('footer_text',$config['footer_text']);
			$reqId=$this->get('id');
			$company_id=$_SESSION['company_id'];
			$url = $config['app_login_url']."/?next=".myUrl('redirect/page/?url=leaveRequests');
			$body->set('app_login_url',$url);
			
			$body_o = $body->output();
			$gen["to"][] = array("email"=>$leave['employee_email'],"name"=>$leave['employee_name']);
			
			$gen['subject'] = $subj;
			$gen['message'] = $body_o;
			
			
			return $gen;
	}

}