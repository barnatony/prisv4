<?php
$authuid=isset($_SESSION['authuid']) ? $_SESSION['authuid'] : 0;
$view->title =isset($pagename) ? $pagename ." - ".$view->title : $view->title;
// Frontend Included Files


// Frontend Header Options
$view->l_header_transparent         = true;     // True: Transparent header (if also fixed, it will get a solid dark background color on scrolling), False: White solid header
$view->inc_header = VIEW_PATH.'menu/admin_header_navigation.php'; 
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title><?php echo $view->title; ?></title>

        <meta name="description" content="<?php echo $view->description; ?>">
        <meta name="author" content="<?php echo $view->author; ?>">
        <meta name="robots" content="<?php echo $view->robots; ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?php echo $view->assets_folder; ?>/img/favicon/sc.png">

       
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
        
		
		<!-- Bootstrap and OneUI CSS framework -->
	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	    <link rel="stylesheet" id="css-main" href="<?php echo $view->assets_folder; ?>css/oneui.css">
	    <!-- <link rel="stylesheet" href="<?php echo $view->assets_folder; ?>js/plugins/sweetalert/sweetalert.min.css">-->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.0/sweetalert.min.css">
	    <link rel="stylesheet" href="<?php echo $view->assets_folder; ?>js/plugins/select2/select2.min.css">
	    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
	    <link rel="stylesheet" href="<?php echo $view->assets_folder; ?>js/plugins/jquery-auto-complete/jquery.auto-complete.min.css">
	
	    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
	    <?php if ($view->theme) { ?>
	    <link rel="stylesheet" id="css-theme" href="<?php echo $view->assets_folder; ?>css/themes/<?php echo $view->theme; ?>.min.css">
	    <?php } ?>
	    
	    <!-- Exclusive Styles For  Page START-->
		<?php echo (isset($head) && is_array($head)) ? implode("\n",$head) : ''?>
		<!-- Exclusive Styles For  Page END -->
		
		<link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css' rel='stylesheet' type='text/css'>
	    <!-- END Stylesheets -->
	</head>
	<body<?php if ($view->body_bg) { echo ' class="bg-image" style="background-image: url(\'' . $view->body_bg . '\');"'; } ?>>
	
	<!-- Page Container -->
<!--
    Available Classes:

    'enable-cookies'             Remembers active color theme between pages (when set through color theme list)

    'sidebar-l'                  Left Sidebar and right Side Overlay
    'sidebar-r'                  Right Sidebar and left Side Overlay
    'sidebar-mini'               Mini hoverable Sidebar (> 991px)
    'sidebar-o'                  Visible Sidebar by default (> 991px)
    'sidebar-o-xs'               Visible Sidebar by default (< 992px)

    'side-overlay-hover'         Hoverable Side Overlay (> 991px)
    'side-overlay-o'             Visible Side Overlay by default (> 991px)

    'side-scroll'                Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (> 991px)

    'header-navbar-fixed'        Enables fixed header
    'header-navbar-transparent'  Enables a transparent header (if also fixed, it will get a solid dark background color on scrolling)
-->
<div id="page-container"<?php $view->page_classes(); ?>>

	<?php if(isset($view->inc_side_overlay) && $view->inc_side_overlay) { include($view->inc_side_overlay); } ?>
    <?php if(isset($view->inc_sidebar) && $view->inc_sidebar) { include($view->inc_sidebar); } ?>
    <?php if(isset($view->inc_header) && $view->inc_header) { include($view->inc_header); } ?> 
    
<!-- Main Container -->
    <main id="main-container">
    
    <?php echo (isset($body) && is_array($body)) ? implode("\n",$body) : ''?>
     
    </main>
    <!-- END Main Container -->

    
     <!-- Footer -->
    <footer id="page-footer" class="bg-gray-lighter">
        <div class="content-boxed">
            
<!-- Copyright Info -->
            <div class="font-s12 push-20 clearfix">
                <hr class="remove-margin-t">
                <div class="pull-right">
                    Designed with <i class="fa fa-heart text-city"></i> by <a class="font-w600" href="http://basstechs?cn=sc&cs=website&cm=site" target="_blank">Bass Techs</a>
                </div>
                <div class="pull-left">
                    <a class="font-s14" href="<?php echo myUrl();?>" target="_blank"><?php echo $view->name . ' ' . $view->version; ?></a> &copy; <span class="js-year-copy"></span>
                </div>
            </div>
            <!-- END Copyright Info -->
            <a href="#" class="scrollToTop fa fa-arrow-up fa-2x"><span class="h6" style="display: inherit;">TOP</span></a>
        </div>
    </footer>
    <!-- END Footer -->
    
</div>

<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<!-- <script src="<?php echo $view->assets_folder;// ?>js/core/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- <script src="<?php echo $view->assets_folder; ?>js/core/bootstrap.min.js"></script>-->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!-- <script src="<?php echo $view->assets_folder; ?>js/core/jquery.slimscroll.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<script src="<?php echo $view->assets_folder; ?>js/core/jquery.scrollLock.min.js"></script>
<script src="<?php echo $view->assets_folder; ?>js/core/jquery.appear.min.js"></script>
<!--<script src="<?php echo $view->assets_folder; ?>js/core/jquery.placeholder.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-placeholder/2.3.1/jquery.placeholder.min.js"></script>
<!--<script src="<?php echo $view->assets_folder; ?>js/plugins/jquery-validation/jquery.validate.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<!-- <script src="<?php echo $view->assets_folder; ?>js/plugins/jquery-validation/additional-methods.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>
<!-- <script src="<?php echo $view->assets_folder; ?>js/plugins/sweetalert/sweetalert.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.0/sweetalert.min.js"></script>
<script src="<?php echo $view->assets_folder; ?>js/plugins/ckeditor/ckeditor.js"></script> 
<script src="<?php echo $view->assets_folder; ?>js/plugins/select2/select2.min.js"></script> 
<script src="<?php echo $view->assets_folder; ?>js/plugins/jquery-auto-complete/jquery.auto-complete.min.js"></script> 
<script src="<?php echo $view->assets_folder; ?>js/app.js"></script>
<script type="text/javascript">
WEB_DOMAIN = '<?php echo WEB_DOMAIN?>';	//Do Not Remove
WEB_FOLDER ='<?php echo WEB_FOLDER?>';  //Do Not Remove
jQuery(function(){
    // Init page helpers (Appear + CountTo plugins)
    App.initHelpers(['appear', 'appear-countTo']);
	var url = document.location.toString();
	if (url.match('#')) {
	    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').click().focus();
	} 
	// Change hash for page-reload //history.pushState( null, null, $(this).attr('href') );
	$('.nav-tabs a').on('shown.bs.tab', function (e) {
		//e.preventDefault();
		 //$(window).scrollTop();
		 history.pushState( null, null, url + $(this).attr('href') );
	    //window.location.hash = e.target.hash;
	})
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    });
    
    //Click event to scroll to top
    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;});
    
});
</script>
<!-- Exclusive Scripts for the Page START -->
<?php echo (isset($foot) && is_array($foot)) ? implode("\n",$foot) : ''?>
<!-- Exclusive Scripts for the Page END -->


    </body>
</html>