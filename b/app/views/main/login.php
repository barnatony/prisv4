
<?php 
if($next)
	$nextURL = "/?next=".$next;
if(!isset($popup)){?>
<!-- Block1 start Here -->
<section class="content content-full">
<div class="modal-dialog box-shaddow push-100-t">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Login</h3>
                </div>
        <div class="block-content content-boxed">
                <h1 class="h4">Peopleauth</h1>
                <p>Welcome,Please login</p>
                 <div class="register-section"></div>
         <!--Login form start Content -->
         <form class="js-validation-login  form-horizontal push-30-t" id="user-login" action="<?php echo myUrl('auth/login');?>" method="POST">
                       <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input  class="form-control" id="login-username" name="username" type="text" required autofocus>
                                    <label for="login-username">Username</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="login-password" name="password" type="Password" required>
                                    <label for="login-password">Password</label>
                                </div>
                            </div>
                        </div>
                   <div class="form-group">
                            <div class="col-xs-12">
                                <div class="font-s13 text-right">
                                    <a href="<?php echo myUrl('main/forgotPassword');?>">Forgot Password?</a>
                                </div>
                            </div>
                        </div> 
                        <input type="hidden" name="next" value="<?php echo $next;?>">
                         <div class="form-group">
                         <div class="err-notification"></div>
                         <div class="col-xs-12">
                              <button class="btn  btn-primary " type="submit">Login<i class="si si-login push-20-l"></i></button>
                          </div>
                       </div>
           </form>
      <!--Login form END  Content -->
        </div>
          </div>
      </div>
    </div>
</section>

	<?php }else{?>
                <h1 class="h4">Startup Clinic</h1>
                <p>Welcome,Please login&nbsp;or&nbsp;<a href="<?php echo myUrl('main/register'.$nextURL);?>">Create account.</a></p>
                 <div class="register-section"></div>
         <!--Login form start Content -->
         <form class="js-validation-login  form-horizontal push-30-t" id="user-login" action="<?php echo myUrl('auth/login');?>" method="POST">
                       <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input  class="form-control" id="login-username" name="username" type="text" required autofocus>
                                    <label for="login-username">Username</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="login-password" name="password" type="Password" required>
                                    <label for="login-password">Password</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="next" value="<?php echo $next;?>">
                         <div class="form-group">
                         <div class="err-notification"></div>
                         <div class="col-xs-12">
                              <button class="btn  btn-primary " type="submit">Login</button>
                              <a  class="font-s14 pull-right" data-dismiss="modal">skip</a>
                          </div>
                       </div>
           </form>
      <!--Login form END  Content -->
	<?php }?>