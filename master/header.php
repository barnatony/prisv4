<?php
require_once ( dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
if (empty ( $_SESSION ['master_name'] )) {
	echo "<script>window.location.href = '../master.html'</script>";
}
?>
<style>.imgHeader {
	width: 18%;
	margin-top: -3px;
}

@media only screen and (min-width: 320px) and (max-width:480px ) {
	.imgHeader {
		width: 18%;
		margin-top: -89px;
		margin-left: 7%;
	}
}

a.logo {
	margin-bottom: -25px;
}

.btn-xs {
	font-size: 9px;
	line-height: 2.5;
	border-radius: 3px;
}

h3, .h3 {
	font-size: 17px;
}
}</style>
<header class="header white-bg">
	<div class="sidebar-toggle-box">
		<div data-original-title="Toggle Navigation" data-placement="right"
			class="fa fa-bars tooltips"></div>
	</div>
	<!--logo start-->
	<a href="dashboard.php" class="logo"> <img alt="Master Image"
		src="<?php echo $_SESSION['master_image']; ?>" class="imgHeader"></a>
	<!--logo end-->

	<div class="top-nav ">
		<ul class="nav pull-right top-menu">
		<li>
				<button class="btn btn-primary btn-xs" style="margin-top: 6px;" type="button">
             <?php echo $_SESSION['loginIn']; ?> </button>
			</li>
			
			<li id="header_notification_bar" class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"> <i class="fa fa-bell-o"></i>

			</a>

				<ul class="dropdown-menu extended notification">
					<div class="notify-arrow notify-arrow-yellow"></div>
					<?php require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/company.class.php");
					$company = new Company();
					$company->masterConn=mysqli_connect ( MASTER_DB_HOST, MASTER_DB_USER, MASTER_DB_PASSWORD, MASTER_DB_NAME);
					$companies = $company->getPendingApprovals($_SESSION["master_id"]);
					foreach($companies as $comp){
					?>
					<li>

                          <a class="read-notification" data-url="false" href="masterApproval.php?id=<?php echo $comp["company_id"];?>" data-value="NT2123" style="cursor: pointer; white-space: normal !important;"> <span class="subject">
								<div class="message">
                                    <b><?php echo $comp["company_name"]?></b>  has modified their profile.</div>
						</span>


					</a>

					</li>
					<?php }?>
                         
					

                                                   
				</ul></li>
				
			<!-- user login dropdown start-->
			<li class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">
				 <span	class="username"><?php echo $_SESSION['master_name']; ?></span> <b	class="caret"></b>
			</a>
				<ul class="dropdown-menu extended logout">
					<div class="log-arrow-up"></div>
					<li><a href="masterProfile.php"><i class=" fa fa-user"></i>Profile</a></li>
					<li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
					
					<li><a href="logout.php"><i class="fa fa-key"></i> Log Out</a></li>
				</ul></li>
			<!-- user login dropdown end -->
		</ul>
	</div>
</header>