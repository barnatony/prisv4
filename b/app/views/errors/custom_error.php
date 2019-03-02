<html>
<head>
 <meta charset="utf-8">
<title>Fatal Error!</title>
       
</head>
<body>
<div class="content bg-white text-center pulldown overflow-hidden">
  <div class="row">
   <div class="col-sm-6 col-sm-offset-3">
    <!-- Error Titles -->
    <h1 class="font-s128 font-w300 text-smooth"><?php echo isset($errorNo)?$errorNo:503;?></h1>
    <h2 class="h3 font-w300 push-50"><?php echo isset($errorMessage)?$errorMessage:"We are sorry but our service is currently not available.";?></h2>
    <!-- END Error Titles -->
   </div>
 </div>
</div>
<div class="content pulldown text-muted text-center push-30-t err_para">
    <p>Please go <a href="javascript: history.back(1)">back</a> and try again.</p>
</div>
</body>
</html>
<?php die();?>
