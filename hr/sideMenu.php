<?php
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
	$features[$key]['fileName']=$val.'_'.$row['feature_id'].'_'.(($row['feature_id']=='NA')?"featuresUpgrade.php?feature=".$key:$val);
	$features[$key]['displayUrl']=($row['feature_id']=='NA')?"featuresUpgrade.php?feature=".$key:$val;
	}
  }
  
 $deviceQuery = "SELECT COUNT(device_id) cnt FROM devices;";
 $stmt = mysqli_query ( $conn, $deviceQuery );
 $devicerow = mysqli_fetch_array( $stmt, MYSQLI_ASSOC );
  
 ?>
<div id="sidebar" class="nav-collapse ">
	<!-- sidebar menu start-->
	<ul class="sidebar-menu" id="nav-accordion">
		<li><a href="index.php"> <i class="fa fa-dashboard"></i> <span>Dashboard</span>
		</a></li>
<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-user"></i>
				<span>Employee</span>
		</a>
			<ul class="sub">
				<!-- <li id="createEmployee"><a href="createEmployee.php">Add</a></li> -->
				<li id="employee"><a href="employees.php">List</a></li>
				<li id="promotion"><a href="promotions.php">Evaluate</a></li>
				<li id="noticePeriod"><a href="noticePeriod.php">Notice Period</a></li>
				<li id="claim"><a href="<?php echo $features['claim']['displayUrl']; ?>">Assign Claims</a></li>
				<li id="assetRequest"><a href="<?php echo $features['assetRequest']['displayUrl']; ?>">Asset Request</a></li> 
				</ul></li>
				
	<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-calendar"></i>
				<span>LMS</span>
				
		</a>
			<ul class="sub">
				<!-- <li id="leaveManagement"><a href="<?php echo $features['leaveManagement']['displayUrl']; ?>">Leave Accounts</a></li>  -->
				<?php if($features['leaveManagement']['displayUrl']!='leave.php'){ ?>
				<li id="attendance"><a href="attendance.php">Attendance</a></li>
				<?php }else{ ?> <li id="attendances"><a href="<?php echo $features['attendances']['displayUrl'];?>">Attendance</a></li> <?php } ?>
				<li id="compensation"><a href="<?php echo $features['compensation']['displayUrl']; ?>">Compensation</a></li>
				<li id="leaveManagement"><a href="../b/redirect/page/?url=leaveRequests">Leave Requests</a></li>
				<?php if($devicerow['cnt']>0) {?> <li id="compensation"><a href="../b/redirect/page/?url=attendance/regularization">Regularization Requests</a></li> <?php }?>
			</ul></li>			
		<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-exchange"></i> <span>Transactions</span>
		</a>
			<ul class="sub">
			
				<li id="miscPayment"><a href="miscellaneous.php">Miscellaneous</a></li>
				<li id="shiftAlloc"><a href="shiftAllocation.php">Shift Allocation</a></li>
			<!-- 	<li id="processClaims"><a href="processClaims.php">Process Claims</a></li> -->
				<li id="itDeclaration"><a href="itDeclaration.php">IT Declaration</a></li>
			</ul></li>
		<li><a href="previewPayroll.php"><i class="fa fa-inr"></i><span>Payroll Preview</span></a></li>
		<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-line-chart"></i> <span>MIS</span>
		</a>
			<ul class="sub">
				<li id="viewPayroll"><a href="viewPayroll.php">View Pay Slips</a></li>
				<li id="reportFilter"><a href="reportFilter.php">Other Reports</a></li>
				<li class="sub-menu"><a href="javascript:;">Statutory Reports</a>
					<ul class="sub">
					 <?php
							Session::newInstance ()->_setGeneralPayParams ();
							$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
							foreach ( $allowDeducArray ['D'] as $dedu ) {
								if ($dedu ['pay_structure_id'] == 'c_esi') {
									echo '<li><a  href="esiReport.php">ESI Report</a></li>';
								} else if ($dedu ['pay_structure_id'] == 'c_epf') {
									//echo '<li><a  href="epfReport.php">EPF Report</a></li>';
									echo '<li><a  href="epfReportV2.php">EPF Report</a></li>';
								} else if ($dedu ['pay_structure_id'] == 'c_it') {
									echo '<li><a  href="tdsReport.php">TDS Report</a></li>';
								} else if ($dedu ['pay_structure_id'] == 'c_pt') {
									echo '<li><a  href="ptReport.php">PT Report</a></li>';
								}
							}
							?>
					</ul>
				</li>
				<li id="customReport"><a href="payoutReports.php">Bank Payout
						Reports</a></li>
			</ul> <!-- 
                          <li id="payOrder"><a href="payOrder.php">Pay Order</a></li>
                          <li id="salaryStatement"><a href="salaryStatement.php">Salary Stat</a></li>
                          <li id="paymodeWisereport"><a href="paymodeWiseReport.php">Pay Orders</a></li>
                          <li id="leaveReport"><a href="leaveReport.php">Leave Stat</a></li>
                          <li id="customReports"><a href="customReports.php">Custom Reports</a></li>
                          <li id="statutoryReports"><a href="statutoryReports.php">Statutory Reports</a></li>
                           --></li>
		
		<li class="sub-menu"><a href="javascript:;"> <i class="fa fa-suitcase"></i>
				<span>Utilities</span>
		</a>
			<ul class="sub">
			    <li id="project"><a href="<?php echo $features['project']['displayUrl']; ?>">Project</a></li>
				<li id="ticket"><a href="tickets.php">Tickets</a></li>
				<li id="notification"><a href="notifications.php">Notifications</a></li>
				<li id="message"><a href="<?php echo $features['message']['displayUrl']; ?>">Messages</a></li>
				<li id="invoice"><a href="invoice.php">Invoice</a></li>
			</ul></li>

	</ul>
	<!-- sidebar menu end-->
</div>
<?php

if(explode('_',$features['leaveManagement']['fileName'])[1]!='NA' && basename($_SERVER['PHP_SELF'])=='attendance.php'){
	echo "<script>window.location.href =  'index.php'</script>";
	die();
}
foreach($features as $row) {
	 if(basename($_SERVER['PHP_SELF'])==explode('_',$row['fileName'])[0] && explode('_',$row['fileName'])[1]=='NA'){
	 	echo "<script>window.location.href = '".explode('_',$row['fileName'])[2]."'</script>";
		die();
	}
}
?>