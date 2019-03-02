  <?php
	require_once ("include/lib/user.class.php");
	$user = new User();
	$company=false;
	?>
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