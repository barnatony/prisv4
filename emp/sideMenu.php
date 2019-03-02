<?php
if (session_id () == '' || ! isset ( $_SESSION )) {
	// session isn't started
	session_start ();
}
$query="SELECT f.feature_menuid,f.screen_url,f.feature_id ft_id,IFNULL(cf.feature_id,'NA') feature_id
												FROM " .MASTER_DB_NAME. ".features f
												LEFT JOIN  " .MASTER_DB_NAME. ".company_features cf
												ON cf.feature_id = f.feature_id
												AND cf.company_id = '".$_SESSION['company_id']."'";
$stmt = mysqli_query ( $conn, $query );
$features = array ();
while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
	$feature_menuid=explode(",",$row['feature_menuid']);
	$screen_url=explode(",",$row['screen_url']);
	$arrayFeatures=array_combine($feature_menuid,$screen_url);
	foreach($arrayFeatures as $key=>$val){
		$features[$key]['fileName']=$val.'_'.$row['feature_id'].'_'.(($row['feature_id']=='NA')?'index.php':$val);
		$features[$key]['displayUrl']=($row['feature_id']=='NA')?"empty":$val;
	}
}
?>
<div id="sidebar" class="nav-collapse ">
	<!-- sidebar menu start-->
	<ul class="sidebar-menu" id="nav-accordion">
		<li><a href="index.php"> <i class="fa fa-dashboard"></i> <span>Dashboard</span>
		</a></li>

		<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-gears"></i>
				<span>My Room</span>
		</a>
			<ul class="sub">
				<!-- <li id="employeeProfile"><a href="empProfileView.php">Profile</a></li> -->
				<!-- <li id="holidays"><a href="empHolidays.php">Calenders</a></li> -->
				<!-- <li id="roomPerqs"><a  href="roomPerqs.php">Perqs</a></li> -->
				<li id="attendance"><a href="../b/redirect/page/?url=attendance">Attendance</a></li>
				<li id="taxPlanner"><a href="taxPlanner.php">Tax Planner</a></li>
				<li id="attendanceRegularization"><a href="../b/redirect/page/?url=attendance/regularization">Regularization</a></li>
				<?php if($features['leaveManagement']['displayUrl']!='empty'){?>
					<li id="leaveManagement"><a href="../b/redirect/page/?url=leaveRequests">Leave Requests</a></li>
				<?php }?>
				<?php echo ($features['leaveManagement']['displayUrl']!='empty')?'<li id="compensation">
				<a href="compensation.php">Compensation</a></li>':'';?>
				<li id="noticePeriod"><a href="noticePeriod.php">Raise a Notice Period</a></li>
				
			</ul></li>
			
		<!--
		echo '<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-sign-out"></i>
				<span>LMS</span>
		</a>

			<ul class="sub">
			 <!-- <li id="leaveManagement">
		<a href="'.$features['leaveManagement']['displayUrl'].'">Leave Accounts</a></li> 
		<li id="leaveManagement"><a href="../b/redirect/page/?url=leaveRequests">Leave Requests</a></li>

			</ul></li>';-->
                          
		
		<!-- <li class="sub-menu"><a href="javascript:;"> <i class="fa fa-calendar"></i>
				<span>Transactions</span>
		</a>
			<ul class="sub"> -->
			<!-- <?php echo ($features['claim']['displayUrl']!='empty')?'<li id="claim">
		<a href="'.$features['claim']['displayUrl'].'">Claims</a></li>':'';?>-->
		
		<!--<?php echo ($features['assetRequest']['displayUrl']!='empty')?'<li id="assetRequest">
		<a href="'.$features['assetRequest']['displayUrl'].'">Asset Request</a></li>':'';?>-->
				
			
				<!--  <li id="leaves"><a  href="leave.php">Leaves</a></li>
                          <li id="attendence"><a  href="attendence.php">Attendence</a></li> -->
				<!-- <li id="taxPlanner"><a href="taxPlanner.php">Tax planner</a></li>
			</ul></li> -->
		<li class="sub-menu"><a href="../b/redirect/page/?url=myteam//"> <i class="fa fa-users"></i>
				<span>My Team</span></a></li>
				
				<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-sitemap"></i>
				<span>Reports</span>
		</a>
			<ul class="sub">
				<li id="paySlip"><a href="paySlips.php">Pay Slips</a></li>
				<!--  <li id="pfBalance"><a href="pfBalance.php">PF-Balance</a></li>
                          <li id="loanSummary"><a href="loanSummary.php">Loan summary</a></li>
                          <li id="reportPerqs"><a href="perqsSummary.php">Perqs summary</a></li>
                          <li id="leaveSummary"><a href="leaveSummary.php">Leave Summary</a></li> -->
				<li id="annualSalary"><a href="annualSalaryStat.php">Annual Salary
						Statement</a></li>
				<!--  <li id="itPapers"><a href="itPapers.php">IT Papers</a></li> -->
			</ul></li>
		<!--
		<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-suitcase"></i>
				<span>Utilities</span>
		</a>
			<ul class="sub">

			
		<?php echo ($features['message']['displayUrl']!='empty')?'<li id="message">
		<a href="'.$features['message']['displayUrl'].'">Messages</a></li>':'';?>
		<li id="notification"><a href="notifications.php">Notifications</a></li>
		
		<?php echo ($features['project']['displayUrl']!='empty')?'<li id="project">
		<a href="'.$features['project']['displayUrl'].'">Project</a></li>':'';?>
		
		<?php echo ($features['timesheet']['displayUrl']!='empty')?'<li id="timesheet">
		<a href="'.$features['timesheet']['displayUrl'].'">Timesheet</a></li>':'';?>
		
		</ul></li>
		-->
		 
		
		
	</ul>
	<!-- sidebar menu end-->
</div>
<?php 

foreach($features as $row) {
	 if(basename($_SERVER['PHP_SELF'])==explode('_',$row['fileName'])[0] && explode('_',$row['fileName'])[1]=='NA'){
		echo "<script>window.location.href = '".explode('_',$row['fileName'])[2]."'</script>";
		die();
	}
}
?>