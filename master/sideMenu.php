<?php
if (session_id () == '' || ! isset ( $_SESSION )) {
	// session isn't started
	session_start ();
}
?>
<div id="sidebar" class="nav-collapse ">
	<!-- sidebar menu start-->
	<ul class="sidebar-menu" id="nav-accordion">

		<li><a href="dashboard.php"> <i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
		<?php  if($_SESSION['loginIn']=='Consultant'){//only for consultant?>
		<li class="sub-menu">
				<a href="javascript:;"> <i class="fa fa-gears"></i>	<span>Manage PRIS</span></a>
				<ul class="sub">
		<li id="masterProfile"><a href="listView.php"><i class="fa fa-plus"></i> Add Company</a></li>
		 <li id="invoice"><a href="invoice.php"><i class="fa fa-info"></i> Invoice</a></li>
		</ul>
		</li>
			<?php }else{
				?>
				<li class="sub-menu">
				<a href="javascript:;"> <i class="fa fa-gears"></i>	<span>Manage PRIS</span></a>
		<ul class="sub">
				<li id="masterProfile"><a href="listView.php"><i class="fa fa-plus"></i>Companies</a></li>
				<li><a href="payrollRollback.php">Payroll Rollback</a></li>
				<li><a href="wipeEmployee.php">Employee Wipe</a></li>
				<li id="user"><a href="user.php"><i class="fa fa-user"></i> User</a></li>
	    </ul>
				</li>
				
				<li class="sub-menu">
				<a href="javascript:;"> <i class="fa fa-gears"></i><span>Utilities</span></a>
				<ul class="sub">
				<li id="tickets"><a href="tickets.php">Tickets</a></li>
				<li id="services"><a href="services.php">Services</a></li>
				<li id="invoice"><a href="invoice.php"><i class="fa fa-info"></i> Invoice</a></li>
				<li id="companyFeatures"><a href="companyFeatures.php">Features </a></li>
				</ul>
				</li>
				<?php } ?>
	 </ul>
	<!-- sidebar menu end-->
</div>
<?php  
if($_SESSION['loginIn']=='Consultant'){
	if((basename($_SERVER['PHP_SELF'])!='listView.php') &&  (basename($_SERVER['PHP_SELF'])!='invoice.php') && (basename($_SERVER['PHP_SELF'])!='dashboard.php') ){
		echo "<script>window.location.href = 'dashboard.php'</script>";
		die();
	}
}
?>