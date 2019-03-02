<?php
if (session_id () == '' || ! isset ( $_SESSION )) {
	// session isn't started
	session_start ();
}
?>
  <!-- Sidebar -->
		<nav id="sidebar">
            <!-- Sidebar Scroll Container -->
            <div id="sidebar-scroll">
                <!-- Sidebar Content -->
                <div class="sidebar-content">
                    <!-- Side Header -->
					<div class="side-header side-content bg-white-op">
                      	<button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                            <i class="fa fa-times"></i>
                        </button>
                        <a class="h5 text-white" href="index.php">
                                <img src="<?php echo myUrl('img/logo/basspris-icon.png')?>" width="30"><img class="push-10-l" src="<?php echo myUrl('img/logo/pris-logo.png')?>" width="70">
                        </a>
                    </div>
                    <!-- END Side Header -->
					<!-- Side Content -->
					<div class="side-content side-content-full">
							<ul class="nav-main">
								<li><a href="<?php echo myUrl('dashboard')?>"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a></li>
								<li><a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-rocket"></i><span class="sidebar-mini-hide">My Room</span></a>
									<ul>
										<li><a class="" href="<?php echo myUrl('mydashboard')?>"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">My Dashboard</span></a></li>
										<li><a href="<?php echo myUrl('attendance')?>"><i class="fa fa-bar-chart"></i><span class="sidebar-mini-hide">Attendance</span></a></li>
										<?php if($features['leaveManagement']['displayUrl']!='empty'){?>
										<li><a href="<?php echo myUrl('attendance/regularization')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Regularization</span></a></li><?php }?>
										
										<li><a href="<?php echo myUrl('leaveRequests/speciallr')?>"><i class="fa fa-pencil-square-o"></i><span class="sidebar-mini-hide">Special Leaves</span></a></li>
										<li><a href="<?php echo myUrl('leaveRequests')?>"><i class="fa fa-pencil-square-o"></i><span class="sidebar-mini-hide">Leave Requests</span></a></li>
										
										<?php if($features['leaveManagement']['displayUrl']!='empty'){?>
										<li><a href="<?php echo myUrl('attendance/regularization')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Regularization</span></a></li><?php }?>
										<li><a href="<?php echo myUrl('taxPlanner')?>"><i class="fa fa-money"></i><span class="sidebar-mini-hide">Tax Planner</span></a></li>
										<?php if($features['claim']['displayUrl']!='empty'){?>
										<li><a href="<?php echo myUrl('claims')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Claims</span></a></li><?php }?>
										<?php if($features['assetRequest']['displayUrl']!='empty'){?>
										<li><a href="<?php echo myUrl('assetsRequest')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Asset Request</span></a></li><?php }?>
									</ul>
								</li>
								<?php if($teamleads){?>
								<li><a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-users"></i><span class="sidebar-mini-hide">My Team</span></a>
									<ul>
										<li><a href="<?php echo myUrl('myteam/dashboard')?>"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a></li>
										<li><a href="<?php echo myUrl('myteam/members')?>"><i class="si si-user-follow"></i><span class="sidebar-mini-hide">Members</span></a></li>
										<li><a href="<?php echo myUrl('myteam/leaveRequests')?>"><i class="glyphicon glyphicon-equalizer"></i><span class="sidebar-mini-hide">Leave Requests</span></a></li>
										<li><a href="<?php echo myUrl('myteam/regularization')?>"><i class="glyphicon glyphicon-indent-left"></i><span class="sidebar-mini-hide">Regularization</span></a></li>
										<?php if($features['claim']['displayUrl']!='empty'){?>
										<li><a href="<?php echo myUrl('teamClaims')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Claims</span></a></li><?php }?>
										<?php if($features['assetRequest']['displayUrl']!='empty'){?>
										<li><a href="<?php echo myUrl('teamAR')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Asset Request</span></a></li><?php }?>
									</ul>
								</li>
								<?php }?>
								<li><a class="nav-submenu" data-toggle="nav-submenu"><i class="fa fa-line-chart"></i><span class="sidebar-mini-hide">Reports</span></a>
									<ul>
										<li><a href="#"><i class="fa fa-file-pdf-o"></i><span class="sidebar-mini-hide">Pay Slips</span></a></li>
										<li><a href="#"><i class="fa fa-file-text-o"></i><span class="sidebar-mini-hide">Statements</span></a></li>
										<li><a href="#"><i class="fa fa-file-text-o"></i><span class="sidebar-mini-hide">Attendance</span></a></li>
										<li><a href="#"><i class="fa fa-sliders"></i><span class="sidebar-mini-hide">Team Report</span></a></li>
									</ul>
								</li>
							</ul>
                    </div>
                        
                    <!-- END Side Content -->
                </div>
                <!-- Sidebar Content -->
            </div>
            <!-- END Sidebar Scroll Container -->
        </nav>
	<!-- END Sidebar -->