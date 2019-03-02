<div class="bg-white push-150-t">
    <div class="content content-boxed ">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <div class="push-30-t push-20">
                    <!-- Reminder Title -->
                    <div class="text-center">
                       <a class="" href="<?php echo myUrl('main/');?>">
                        <img class="bg-img-no-repeat icf" src="<?php echo myUrl('img/favicon/temp_sc-favicon.png');?>" alt="">
                        </a>
                        <p class="text-muted push-15-t err-notification">Don't worry, we'll send a reset link to you.</p>
                    </div>
                    <!-- END Reminder Title -->

                    <!-- Reminder Form -->
                    <!-- jQuery Validation (.js-validation-reminder class is initialized in js/pages/base_pages_reminder.js) -->
                    <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                    <form  class="form-horizontal push-30-t" id="js-validation-reminder" action="<?php echo myUrl('users/password_reset_request');?>" method="post">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="reminder-email" name="email" type="email" required>
                                    <label for="reminder-email">Enter Your Email</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 text-center">
                                <button class="btn btn-sm  btn-primary" type="submit">Send Reset Link</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Reminder Form -->
                     <div class="text-center push-20-t push-20 ">
                        <a href="<?php echo myUrl('main/login');?>">Login?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

