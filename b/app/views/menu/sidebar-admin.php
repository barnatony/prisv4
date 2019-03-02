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
                            <li><a href="<?php echo str_replace('/b/',"/hr/index.php" , myUrl('',true));?>"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a></li>
							<li><a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-rocket"></i><span class="sidebar-mini-hide">Employee</span></a>
								<ul>
								    <li><a class="" href="<?php echo str_replace('/b/',"/hr/index.php" , myUrl('',true));?>"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">List</span></a></li>
                                    <li><a href="<?php echo str_replace('/b/',"/hr/promotions.php" , myUrl('',true));?>"><i class="fa fa-bar-chart"></i><span class="sidebar-mini-hide">Evaluate</span></a></li>
									<li><a href="<?php echo str_replace('/b/',"/hr/noticePeriod.php" , myUrl('',true));?>"><i class="fa fa-pencil-square-o"></i><span class="sidebar-mini-hide">Notice Period</span></a></li>
									<?php if($features['claim']['displayUrl']!='empty'){?>
									<li><a href="<?php echo myUrl('claims')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Claims</span></a></li><?php }?>
									<?php if($features['assetRequest']['displayUrl']!='empty'){?>
									<li><a href="<?php echo myUrl('assetsRequest')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Asset Request</span></a></li><?php }?>
                                </ul>
							</li>
							<li><a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-users"></i><span class="sidebar-mini-hide">LMS</span></a>
								<ul>
									<li><a href="<?php echo str_replace('/b/',"/hr/attendances.php" , myUrl('',true));?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Attendance</span></a></li><?php }?>
									<?php if($features['leaveManagement']['displayUrl']!='empty'){?>
									<li><a href="<?php echo myUrl('attendance/regularization')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Regularization</span></a></li><?php }?>
									<li><a href="<?php echo myUrl('leaveRequests/specialLeaves')?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Special Leaves</span></a></li>
									<li><a href="<?php echo myUrl('leaveRequests')?>"><i class="fa fa-pencil-square-o"></i><span class="sidebar-mini-hide">Leave Requests</span></a></li>
                                </ul>
							</li>
							<li><a class="nav-submenu" data-toggle="nav-submenu"><i class="si si-users"></i><span class="sidebar-mini-hide">Transactions</span></a>
								<ul>
									<li><a href="<?php echo str_replace('/b/',"/hr/miscellaneous.php" , myUrl('',true));?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Miscellaneous</span></a></li><?php }?>
									<li><a href="<?php echo str_replace('/b/',"/hr/shiftAllocation.php" , myUrl('',true));?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">Shift Allocation</span></a></li>
									<li><a href="<?php echo str_replace('/b/',"/hr/itDeclaration.php" , myUrl('',true));?>"><i class="si si-graph"></i><span class="sidebar-mini-hide">IT Declaration</span></a></li>
                                </ul>
							</li>
							<li><a href="<?php echo str_replace('/b/',"/hr/previewPayroll.php" , myUrl('',true));?>"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Preview</span></a></li>
							<li><a class="nav-submenu" data-toggle="nav-submenu"><i class="fa fa-line-chart"></i><span class="sidebar-mini-hide">MIS</span></a>
								<ul>
                                    <li><a href="#"><i class="fa fa-file-pdf-o"></i><span class="sidebar-mini-hide">Pay Slips</span></a></li>
									<li><a href="#"><i class="fa fa-file-text-o"></i><span class="sidebar-mini-hide">Statements</span></a></li>
									<li><a href="#"><i class="fa fa-file-text-o"></i><span class="sidebar-mini-hide">Statutory Report</span></a>
										<ul class="sub">
										 <?php
												Session::newInstance ()->_setGeneralPayParams ();
												$allowDeducArray = Session::newInstance ()->_get ( "generalPayParams" );
												foreach ( $allowDeducArray ['D'] as $dedu ) {
													if ($dedu ['pay_structure_id'] == 'c_esi') {
														echo '<li><a  href="esiReport.php">ESI Report</a></li>';
													} else if ($dedu ['pay_structure_id'] == 'c_epf') {
														echo '<li><a  href="epfReport.php">EPF Report</a></li>';
														echo '<li><a  href="epfReportV2.php">EPF Report V2</a></li>';
													} else if ($dedu ['pay_structure_id'] == 'c_it') {
														echo '<li><a  href="tdsReport.php">TDS Report</a></li>';
													} else if ($dedu ['pay_structure_id'] == 'c_pt') {
														echo '<li><a  href="ptReport.php">PT Report</a></li>';
													}
												}
												?>
										</ul>
									</li>
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