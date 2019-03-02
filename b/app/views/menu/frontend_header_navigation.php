<!-- Header -->
<?php if(isset($_SESSION["authuid"])){?>
<header id="header-navbar" class="content-mini content-mini-full">
    <div class="content-boxed">
		<div class="pull-left push-10-t">
			<i class="hidden-md hidden-lg">
				<!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
				<button class="btn btn-default " data-toggle="layout" data-action="sidebar_toggle" type="button">
					<i class="fa fa-navicon"></i>
				</button>
			</i>
		</div>
		<!-- Header Navigation Left -->
		<ul class="nav-header pull-left">
            <li class="header-content hidden-xs hidden-sm">
                <a class="logo"">
                    <?php if(isset($_SESSION["authuid"])){?>
                    <img alt="<?php echo $_SESSION["company_name"];?>" src="<?php echo str_replace('/b/',str_replace("..", "", $_SESSION["company_logo"]) , myUrl('',true));?>" class="imgHeader">
                    <?php }?>
                </a>
            </li>
             <li class="header-content visible-xs visible-sm">
                <a class="h5" href="<?php echo myUrl(''); ?>">
                    <h4 class="text-white"><i class="ic-f ic-sc-f"></i></h4>
                </a>
            </li>
        </ul>
		<!-- END Header Navigation Left -->
        
		<!-- Main Header Navigation -->
		<ul class=" js-nav-main-header nav-main-header pull-right ">
			<li class="text-right hidden-md hidden-lg">
                <!-- Toggle class helper (for main header navigation in small screens), functionality initialized in App() -> uiToggleClass() -->
                <button class="btn btn-link text-white" data-toggle="class-toggle" data-target=".js-nav-main-header" data-class="nav-main-header-o" type="button">
                    <i class="fa fa-times"></i>
                </button>
			</li>
			<?php $view->build_nav(); ?>
            <?php if(isset($_SESSION["authuid"])){ 
						$user = new User($_SESSION['authuid']);?>  
	        <?php }else{?>
	        	<li>
				<a href="<?php echo myUrl('main/login');?>">Login</a>
				</li>
	        <?php }?>
        </ul>
        <!-- END Main Header Navigation -->

       
       
        <ul class="nav-header pull-right">
         			<li id="header_inbox_bar" class="dropdown"><a data-toggle="dropdown"
						class="dropdown-toggle remove-padding-r" href="#">
                 		<button class="btn btn-default"><i class="fa fa-envelope-o"></i> 
						</button></a>
					</li>
					<li id="header_notification_bar" class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle remove-padding-r remove-padding-l" href="#">
						<button class="btn btn-default"> <i class="fa fa-bell-o"></i>
          				</button></a></li>
	     
		<li>
			<?php if($_SESSION["authprivilage"]=="hr"){?>
            <a class="btn btn-sm btn-info text-white" href="<?php echo str_replace('/b/',"/hr/index.php" , myUrl('',true));?>">Back to PRIS</a>
            <?php }?>
		</li>
        <li>
			<button class="btn btn-primary push-5-r fin_month" >
            <?php echo $_SESSION['fywithMonth'];?></button>
		</li>	 
	    <li>
	       <div class="btn-group">
                            <button class="btn btn-default btn-image dropdown-toggle img-push-t" data-toggle="dropdown" type="button" aria-expanded="true">
                                <img src="<?php echo myUrl($user->get("image"))?>" alt="<?php echo $_SESSION['employee_name'];?>">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-header">Profile</li>
                                <li>
                                    <a tabindex="-1" href="<?php echo myUrl('profile');?>">
                                        <i class="si si-user pull-right"></i>
                                        <span class="badge badge-success pull-right">1</span>Profile
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="<?php echo myUrl('profilesettings');?>">
                                        <i class="si si-settings pull-right"></i>Settings
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="<?php echo myUrl('auth/logout');?>">
                                        <i class="si si-logout pull-right"></i>Log out
                                    </a>
                                </li>
                            </ul>
                            
                        </div>
        </li>
                        
           <!--  <li class="hidden-md hidden-lg">
                <!-- Toggle class helper (for main header navigation below in small screens), functionality initialized in App() -> uiToggleClass() -->
            <!--    <button class="btn btn-link text-white pull-right" data-toggle="class-toggle" data-target=".js-nav-main-header" data-class="nav-main-header-o" type="button">
                    <i class="fa fa-navicon"></i>
                </button>
            </li> -->
        </ul>
    </div>
</header>
<!-- END Header -->
<?php }?>
<script type="text/javascript">
$(document).ready(function () {
	  $('.dropdown-menu').mouseleave(function () {
		  console.log("haiii");
	    $(".dropdown-toggle").dropdown('toggle');
	  });
	});
</script>
 
       