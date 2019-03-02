<?php
header("HTTP/1.0 500 Internal Server Error: Uncaught Exception");
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
<title>Exception Occurred</title>
 <link rel="stylesheet" href="<?php echo myUrl('css/bootstrap.min.css'); ?>">
 <link rel="stylesheet" id="css-main" href="<?php echo myUrl('css/oneui.css');?>">
 <link rel="stylesheet" id="css-theme" href="<?php echo myUrl('css/themes/sc.min.css');?>">
</head>
<body>
<div class="content bg-white text-center pulldown overflow-hidden">
  <div class="row">
   <div class="col-sm-6 col-sm-offset-3">
    <!-- Error Titles -->
    <h1 class="font-s128 font-w300 text-smooth">500</h1>
    <h2 class="h3 font-w300 push-50">We are sorry but our service is currently not available.</h2>
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