<?php
header("HTTP/1.0 404 Not Found");
?>
<html class="no-focus" lang="en">
    	<head>
    	 <meta charset="utf-8">
    	<title>404 - File not found</title>
         <link rel="stylesheet" href="<?php echo $view->assets_folder; ?>/css/bootstrap.min.css">
	    <link rel="stylesheet" id="css-main" href="<?php echo $view->assets_folder; ?>/css/oneui.css">
	    <link rel="stylesheet" id="css-theme" href="<?php echo $view->assets_folder; ?>/css/themes/<?php echo $view->theme; ?>.min.css">
    	</head>
    	<body>
    		<div class="content bg-white text-center pulldown overflow-hidden">
<div class="row">
   <div class="col-sm-6 col-sm-offset-3">
    <!-- Error Titles -->
     <h1 class="font-s128 font-w300 text-city">404</h1>
     <h2 class="h3 font-w300 push-50">The requested URL was not found on this server.</h2>
    <!-- END Error Titles -->
    </div>
  </div>
</div>
<div class="content pulldown text-muted text-center push-30-t err_para">
    <p>Please go <a href="javascript: history.back(1)">back</a> and try again.</p>
    <p>Powered By: <a href="http://basstechs.com/">Bass Techs</a></p>
</div>
</body>
</html>
<?php die();?>