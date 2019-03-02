<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PRIS HRMS by HRLabz">
    <meta name="author" content="HRLabz">
    <meta name="keyword" content="PRIS, HRMS, HRLabz, Payroll Management, Human Resource Management, Employee Management, HR Management">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>BASS PRIS - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <style>
    .login-form .btn.active{
    	border-bottom: 2px solid rgb(87, 200, 242);
		color: rgb(87, 200, 242);
		border-radius:0px;
    }
    </style>
</head>
   <?php 
   require_once ("include/lib/user.class.php");
   $user = new User();
   $company=false;
   ?>
  <body class="login-body">

    <div class="container">

    <div class="form-signin">  
        
        <?php 
        if(isset($_GET['companyId']))
        	$company= $user->validateCompany($_GET['companyId']);
        
        if( $company== false){        
        ?>
        <h2 class="form-signin-heading"><span class="pull-left"><i class="fa fa-arrow-left" style="display:none;cursor:pointer" id="goBack"></i></span>sign in now</h2>
   <div class="login-wrap" id="companyLogin">
				<form id="companyLoginForm" action="auth/login.handle.php"
					method="POST">
					<input type="hidden" name="act" value="company" /> <input
						type="text" class="form-control" name="companyUserName" id="companyUserName"
						placeholder="Enter Company ID" autofocus="true" autocomplete="off">
					<button class="btn btn-lg btn-login btn-block" id="nextBtn"
						type="submit">Next</button>
				</form>

			</div>
		<div class="login-wrap" id = "userLogin" style="display: none;">
		<?php 
        }else{
        	echo '<h2 class="form-signin-heading"><span class="pull-left"></span>sign in now</h2>';
        	echo '<div class="login-wrap" id = "userLogin">';
        }
		?>
			<form id="userLoginForm" class="login-form" action="auth/login.handle.php">
			<!-- ?php 
			/**check whether the listener is running or not**/
				$ip="103.92.200.3";
				$portt="23456";
				$fp = @fsockopen($ip, $portt, $errno, $errstr, 0.1);
				if (!$fp) {
					echo '<input type="hidden" name="checkListener" id="check_listener" value="not running"/>';
				}else{
					echo '<input type="hidden" name="checkListener" id="check_listener" value="running"/>';
					}
			?>-->
			<input type="hidden" name="act" value="user"/>
                <div class="col-lg-12" style="text-align: center; padding-bottom: 10px;">
                <div class="btn-group" data-toggle="buttons">
                    
                    <label class="btn">
                    <input type="radio" name="type"  id="type_0" value="HR"/><i class="fa fa-lock"></i> Admin</label>
                    <label class="btn active">
                    <input type="radio" name="type" id="type_1" value="EMPLOYEE" checked/><i class="fa fa-user"></i> Employee</label>
                 </div>
                   
                </div>
               <input type="hidden" name="companyId" id="companyId" value="<?php echo $_GET['companyId']?>">
				<input type="hidden" name="dataSourceName" id="dataSourceName">
				<input type="text" class="form-control" placeholder="User ID" name="username" id="username" autocomplete="off">
				<input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off">
				<label class="checkbox">
					<span class="pull-right">
						<a data-toggle="modal" id="forgot_pwd" href="#myModal"> Forgot Password?</a>
                    </span>
				</label>
				<button class="btn btn-lg btn-login btn-block" id="signinBtn" type="submit">Sign in</button>
			</form>
        </div>
        

          <!-- Modal -->
          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">Forgot Password ?</h4>
                      </div>
                      <div class="modal-body">
                          <p class="email_text">Enter your e-mail address below to reset your password.</p>
                          <p class="enter_email hide"></p>
                          <input type="text" id="email" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
							<input type="hidden" class="comp_id" />
							<input type="hidden" class="user_type" />
                      </div>
                      <div class="modal-footer">
                          <button data-dismiss="modal" class="btn btn-default" id="forgotcancel" type="button">Cancel</button>
                          <button class="btn btn-success"  id="forgotsubmit" type="button">Submit</button>
                      </div>
                  </div>
              </div>
          </div>
          <!-- modal -->
	</div>
      

    </div>



    <!-- js placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script>
            
                  
     $(document).ready(function () {
         $('#companyLoginForm').on('submit', function (e) {
             e.preventDefault();
             
             $.ajax({
                 type: "POST",
                 url: $(this).attr('action'),
                 cache: false,
                 data: $(this).serialize(),
                 beforeSend:function(){
                	$('#nextBtn').button('loading'); 
                 },
                 complete:function(){
                	 $('#nextBtn').button('reset');
                 },
                 success: function (data) {
                	 data = JSON.parse(data);
                      if (data[0] == 'error' ) {
                         $('#companyLoginForm, #userLoginForm')[0].reset();
                         alert(data[1]);
                     } else {
                         $('#dataSourceName').val(data['data']);
                         $('#companyLogin').hide();
                         $('#userLogin').toggle('show');
                         $('#goBack').show();
                         $('#username').focus();
                         $('#companyId').val($('#companyUserName').val());
                         $('.comp_id').val($('#companyUserName').val());
                     }
                 }
             });
         });
         $('#userLoginForm').on('submit', function (e) {
             e.preventDefault();
			/** if($('#check_listener').val()=='not running'){
             $.post('/hr/tcpListenerService.php/?ip=103.92.200.3&port=23456', function() {
      	      	 window.location ='/hr/index.php';
      	   		});
             }else{
				 
			 }**/
      	  
            $.ajax({
                 type: "POST",
                 url: $(this).attr('action'),
                 cache: false,
                 data: $(this).serialize(),
                 beforeSend:function(){
                 	$('#signinBtn').button('loading'); 
                  },
                  complete:function(){
                 	 $('#signinBtn').button('reset');
                  },
                 success: function (data) {
                	 data = JSON.parse(data);
                     if (data[0] == 'error' ) {
                    	 
                         $('#username').val("");
                         $('#password').val("");
                        alert(data[1]);
                    } else {
                    	window.location = "" + data[2] + "/index.php";
                    }
                 }
             });
         });

         $("input[name=type]").on("change",function(){
				$('.user_type').val($(this).val());
             });
         $('#forgot_pwd').on('click', function () {
        	 $('#email,.email_text').removeClass("hide");
        	 $('.enter_email').addClass('hide');
        	 $('#forgotsubmit,#forgotcancel').show();
        	 $('#email').val('');
          });
         $('#goBack').on('click', function () {
             $('#userLogin').hide();
             $('#companyLoginForm')[0].reset();
             $('#userLoginForm')[0].reset();
             $('#companyLogin').toggle('show');
             $('#goBack').hide();
         });
     });

     $('#forgotsubmit').on('click', function() {
         var email = $('#email').val();
         var compName = $('.comp_id').val();
         var usertype = $('.user_type').val();
         $.ajax({
             dataType: 'html',
             type: "POST",
             url: "auth/login.handle.php",
             cache: false,
             data: {act: '<?php echo "resetPassword";?>',type:usertype,companyId:compName,email:email },
             beforeSend:function(){
              	$('#forgotsubmit').button('loading'); 
               },
               complete:function(){
              	 $('#forgotsubmit').button('reset');
               },
             success: function (data) {
            	   data = JSON.parse(data);
                   if (data[0] == 'success' ) {
                	   $('.enter_email').removeClass('hide');
                       $('.enter_email').html('Your Password Reset Link has been sent to your registered email successfully.');
                       $('#forgotsubmit,#forgotcancel').hide();
                       $('#email,.email_text').addClass("hide");
                   }else{
                	   $('.enter_email').removeClass('hide');
                       $('.enter_email').html('No Users Found.');
                       $('#forgotsubmit,#forgotcancel').hide();
                       $('#email,.email_text').addClass("hide");
                   }
               }
    	 });

     });
	</script>


  </body>
</html>
