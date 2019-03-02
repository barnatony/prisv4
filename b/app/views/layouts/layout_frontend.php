<?php

$employees=new Employee();
$login_id = $_SESSION["employee_id"];
//to check team exist for the login employee
$teamleads=$employees->retrieve_one("employee_reporting_person = ?",$login_id);


$authuid=isset($_SESSION['authuid']) ? $_SESSION['authuid'] : 0;
$view->title =isset($pagename) ? $pagename ." - ".$view->title : $view->title;
// Frontend Included Files
$view->robots="";

// Frontend Header Options
$view->l_header_transparent         = false;     // True: Transparent header (if also fixed, it will get a solid dark background color on scrolling), False: White solid header


/*$view->main_nav =Menu::get_menu_items();
$settings = new Setting();
$settings = $settings->retrieve_many();
foreach($settings as $setting){
	$data[$setting->get('setting')]=$mdata[$setting->get('setting')]=$setting->get('value');
}
*/
?>

<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

       	
        <title><?php echo $view->title; ?></title>
		
    	
        <meta name="robots" content="<?php echo $view->robots; ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?php echo $view->assets_folder; ?>/img/favicon/sc.png">

       
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
        
		
		<!-- Bootstrap and PRISUI CSS framework -->
	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	    <link rel="stylesheet" id="css-main" href="<?php echo $view->assets_folder; ?>css/prisui.css">
	    

	    <link rel="stylesheet" href="<?php echo $view->assets_folder; ?>js/plugins/select2/select2.min.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.0/sweetalert.min.css">
	
	    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.css">
        <!-- <link rel="stylesheet" href="<?php echo $view->assets_folder; ?>js/plugins/jquery-auto-complete/jquery.auto-complete.min.css">-->

		
	    <!-- END Stylesheets -->
	</head>
	<body<?php if ($view->body_bg) { echo ' class="bg-image" style="background-image: url(\'' . $view->body_bg . '\');"'; } ?>>
	
	<!-- Page Container -->
<div id="page-container" <?php $view->page_classes(); ?>>

		    <!-- Exclusive Styles For  Page START-->
		<?php echo (isset($head) && is_array($head)) ? implode("\n",$head) : ''?>
		<!-- Exclusive Styles For  Page END -->
	<?php echo (isset($sidebar) && is_array($sidebar)) ? implode("\n",$sidebar) : ''?>

	<?php if(isset($view->inc_side_overlay) && $view->inc_side_overlay) { include($view->inc_side_overlay); } ?>
    <?php if(isset($view->inc_sidebar) && $view->inc_sidebar) { include($view->inc_sidebar); } ?>
    <?php if(isset($view->inc_header) && $view->inc_header) { include($view->inc_header); } ?>
    
<!-- Main Container -->
    <main id="main-container">
    
    <?php echo (isset($body) && is_array($body)) ? implode("\n",$body) : ''?>
     
    </main>
    <!-- END Main Container -->

    
    <?php if(isset($view->inc_footer) && $view->inc_footer) { include($view->inc_footer); } ?>
    
</div>

<!-- PRISUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.js"></script>
<script src="<?php echo $view->assets_folder; ?>js/core/jquery.scrollLock.min.js"></script>
<script src="<?php echo $view->assets_folder; ?>js/core/jquery.appear.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-countto/1.2.0/jquery.countTo.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-placeholder/2.3.1/jquery.placeholder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.2/js.cookie.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.0/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js"></script>


<script src="<?php echo $view->assets_folder; ?>js/plugins/select2/select2.min.js"></script> 
<script src="<?php echo $view->assets_folder; ?>js/app.js"></script>

<script src="<?php echo $view->assets_folder; ?>js/plugins/slimScroll/scroll_js.js" type="text/javascript"></script>

<script type="text/javascript">
WEB_DOMAIN = '<?php echo WEB_DOMAIN?>';	//Do Not Remove
WEB_FOLDER ='<?php echo WEB_FOLDER?>';  //Do Not Remove
jQuery(function(){
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
            return false;
        });
	// Init page helpers (Appear + CountTo plugins)

    App.initHelpers(['appear', 'appear-countTo']);
    (function() {
        var temp_log = [];
        function log() {
            if (console && console.log) {
                for (var i = 0; i < temp_log.length; i++) {
                    console.log.call(window, temp_log[i]);
                }
                console.log.call(window, arguments);
            } else {
                temp_log.push(arguments);
            }
        }
    })();
});
</script>

<!-- Exclusive Scripts for the Page START -->
<?php echo (isset($foot) && is_array($foot)) ? implode("\n",$foot) : ''?>
<!-- Exclusive Scripts for the Page END -->


    </body>
</html>