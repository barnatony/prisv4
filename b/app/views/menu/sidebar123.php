  <nav id="sidebar">
                <!-- Sidebar Scroll Container -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
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
                                <li>
									<a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-rocket"></i><span class="sidebar-mini-hide">My Room</span></a>
								    <ul>
                                        <li><a class="active" href="<?php echo myUrl('attendance')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Attendance</span></a></li>
										<li><a href="<?php echo myUrl('attendance/regularization')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Regularization</span></a></li>
										<li><a href="<?php echo myUrl('attendance/leaveRequests')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Leave Account</span></a></li>
										<li><a href="<?php echo myUrl('attendance/tax')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Tax Planner</span></a></li>
                                    </ul>
								</li>
								<li>
									<a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-cup"></i><span class="sidebar-mini-hide">My Team</span></a>
								    <ul>
								    	<li><a href="<?php echo myUrl('myteam/dashboard')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Dashboard</span></a></li>
                                        <li><a href="<?php echo myUrl('myteam')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Members</span></a></li>
										<li><a href="<?php echo myUrl('myteam/leaveRequests')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Leave Requests</span></a></li>
										<li><a href="<?php echo myUrl('myteam/regularization')?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Regularization Requests</span></a></li>
                                    </ul>
								</li>
								<li>
									<a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-cup"></i><span class="sidebar-mini-hide">Reports</span></a>
								    <ul>
                                        <li><a href="#"><i class="si si-badge"></i><span class="sidebar-mini-hide">Pay Slips</span></a></li>
										<li><a href="#"><i class="si si-badge"></i><span class="sidebar-mini-hide">Statements</span></a></li>
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