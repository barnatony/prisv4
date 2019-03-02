<?php
function _index(){
	
	$data['pagename']='Dashboard';
	$view = new View();
	$data['view'] = $view;
	
if($_SESSION['authprivilage']=="hr"){
	
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>';
	$data['foot'][]='<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-core.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-full.min.js"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/dust/dust-helpers.js').'"></script>';
	
	$data['foot'][]='<script src="'.myUrl('js/pages/hr-index.js').'"></script>';
	
	$data['body'][]=View::do_fetch(VIEW_PATH.'dashboard/hr.php',$data);
	
	
}else{
	$data['for']="me";
	
	
	$company_id=$_SESSION['company_id'];
	$employeeId=$_SESSION['employee_id'];
	$dbh = getdbh();
	
	
	$Qstmt = $dbh->prepare('SELECT salary_days,attendance_period_sdate attendance_dt
  							FROM company_details WHERE info_flag="A" AND company_id=:company_id');
	$Qstmt->bindParam('company_id',$company_id);
	$Qstmt->execute();
	$companyP = $Qstmt->fetch();
	
	//to get currentDate
	$today= date("Y-m-d");
	$monthend=$_SESSION['payrollYear']."-".($_SESSION['monthNo'])."-".($companyP['attendance_dt']);
	if($companyP['attendance_dt'] !=1 && $today >= $monthend){
		$data["selectedMonth"]=$_SESSION['payrollYear']."-".($_SESSION['monthNo']+1);
	}else{
		$data["selectedMonth"]=$_SESSION['payrollYear']."-".($_SESSION['monthNo']);
	}
	
	//to get financial year on leaveBalance and myself widget
	$stmt=("SELECT leave_based_on FROM company_details WHERE company_id=:companyId AND info_flag='A'");
	$stmt=$dbh->prepare($stmt);
	$stmt->bindParam('companyId',$company_id);
	$stmt->execute();
	$companyProp = $stmt->fetch(PDO::FETCH_ASSOC);
	$data['year'] = $companyProp ['leave_based_on'] == 'finYear' ? $_SESSION['financialYear'] : $_SESSION['payrollYear'];
	
	//to check whether the employee is paying tax and to get tax years on select box
	$taxes = $dbh->prepare("SELECT year FROM employee_income_tax WHERE employee_id=:empId AND tax>0 ORDER BY year DESC");
	$taxes->bindParam('empId',$employeeId);
	$taxes->execute();
	
	$taxYears = $taxes->fetchAll(PDO::FETCH_ASSOC);
	if(!$taxYears)
		$data['taxYears']=null;
	else	
		$data['taxYears']=$taxYears;
	
	
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/datatables/jquery.dataTables.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css').'">';
	$data['head'][]='<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">';
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/jquery-auto-complete/jquery.auto-complete.min.css').'">';
	$data['foot'][]='<script src="'.myUrl('js/plugins/datatables/jquery.dataTables.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>';
	$data['foot'][]='<script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>';
	
	$data['foot'][]='<script src="'.myUrl('js/plugins/jquery-auto-complete/jquery.auto-complete.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/moment.min.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-core.min.js"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-full.min.js"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/dust/dust-helpers.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/plugins/easy-pie-chart/jquery.easypiechart.min.js').'"></script>';
	$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>';
	$data['foot'][]='<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>';
	//$data['foot'][]='<script src="'.myUrl('js/plugins/chart/chart.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/dashboard-index.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/attendance-index.js').'"></script>';
	$data['foot'][]='<script src="'.myUrl('js/pages/holiday-anniversaries.js').'"></script>';
	
	//holiday-anniversaries
	$data['body'][]=View::do_fetch(VIEW_PATH.'dashboard/index.php',$data);
}
	View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
}