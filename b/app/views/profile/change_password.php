<!-- block1 start here --> 

<div class="block-content content-boxed  block-content-narrow bg-gray-lighter push-100-t">
    <div class="row items-push">
        <div class="col-sm-12">
        <?php if(isset($_SESSION["authuid"])){?>
                <a class="fa fa-angle-left fa-2x text-left" href="<?php echo myUrl("profile/edit")?>">&nbsp;<span class="h3" style="display: inherit;">
                BACK</span></a>
                <?php }else{?>
                <div class="text-center">
                <span class="h3" class="">Hello, &nbsp;
                <?php echo $user->get("name");?>
                </span>
                </div>
                <?php }?>
        </div>
    </div>
</div>

<section class="block-content content-boxed  block-content-narrow">
<div class="row items-push push-20 ">
<!-- start past events content -->
    <div class="push-30-t col-sm-12">
        <p>Choose a strong password and don't reuse it for other accounts.<!-- <a href="javascript:void(0)">Learnmore.</a> --><br>
        Changing your  password will sign you  out of all your devices.including your phone you will need to enter your new password on all your devices. </p>
         </div>	
     <div class="col-sm-12 col-xs-12 box-shaddow push-20 bg-white">
            <div class="form-horizontal col-xs-12 col-sm-12">
            <?php 
            if(isset($_SESSION["authuid"]))
            	$url=myUrl('users/changePassword');
            else 
            	$url=myUrl('users/changePassword/'.$user->get("p_reset_token"));
            	?>
                 <form  class="form-horizontal push-50-t" action="<?php echo $url;?>" id="profile-secure-new" method="POST">
                   <div class="form-group">
                            <div class="col-sm-12 col-md-8">
                                <div class="form-material input-group  form-material-primary ">
                                    <input  class="form-control password" id="user-profile-new-password" name="user_profile_new_password"  required type="password">
                                    <label for="user-profile-new-password">New Password<span> *</span></label>
                                    <span class="input-group-addon"><i  class="fa fa-eye-slash pwd-visibility"></i></span>
                               </div>
                            
                             <div class="help-block">
                                    <!-- -<span class="block-title inline font-s12">password length:</span>
                                    <span class="help-block inline font-s13">Too short</span> -->
                                   <p>  Use atleast 5 characters.Don't use a password from anothersite,or something too obviouse like your pet's name.<!-- <a  href="javascript:void(0)">why?</a> --></p>
                                      </div>
                            </div>
                        </div> 
                        <div class="form-group">
                            <div class="col-sm-12 col-md-8">
                                <div class="form-material input-group form-material-primary">
                                    <input  class="form-control password" id="user-profile-confirm-password"  name="user_profile_confirm_password"  required type="password">
                                    <label for="user-profile-confirm-password">Confirm Password<span> *</span></label>
                                      <span class="input-group-addon"><i class="fa fa-eye-slash pwd-visibility"></i></span>
                                </div>
                            </div>
                        </div>   
                          <div class="push-10">
                            <div class="err-notification"></div>
                            <button class="btn btn-primary push-5 text-uppercase" type="submit">change password</button>
	                     </div>
                   </form>
                  </div>
               </div>
      <!-- end past event contents --> 	
   </div>
</section>
    