<section class="block-content block-content-narrow content-boxed">
<!-- Section Content -->
<div class="row items-push push-100-t push-20 bg-gray-lighter box-shaddow col-sm-12 ">

<div class=" row block-header bg-primary-dark">
                    <h3 class="block-title text-white">create user</h3>
                </div>

<!-- Event register form start Content -->
<div class="block-content block-content-full">
 <!--  Register Form Strat Here -->
          <form novalidate="novalidate" class="js-validation-login  form-horizontal push-30-t" id="add-user" action="<?php echo myUrl('users/ops_update');?>" method="POST">
                   
                         <div class="form-group"> 
                         <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="name" name="name" type="text"  autofocus required>
                                    <label for="user-signup-name">Name</label>
                                </div>
                            </div>
                        </div>       
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="username" name="username" type="text"  required>
                                    <label for="user-signup-username">Username</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="email" name="email" type="email" required>
                                    <label for="user-signup-email">Email</label>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="password" name="password" type="password"  required>
                                    <label for="password">Password</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="c-password"  name="c_password" type="password"  required>
                                    <label for="c-password">Confirm Password</label>
                                </div>
                            </div>
                        </div>
                       <div class="form-group">
                        <label class="col-xs-12" for="secure">Roles:</label>
                            <div class="col-xs-12">
                            <div class="form-material  floating form-material-primary ">
                                    <select class="form-control" id="privilage" name="privilage" required aria-required="true">
                                      <option value="">Select one</option>
                                      <?php foreach($roles as $role){?>  
                                    <option value="<?php echo $role->get('roles')?>"><?php echo $role->get('roles')?></option>
                                    <?php }?>    
                                    </select>
                                </div>
                            
                            </div>
                            </div>
                       <div class="form-group">
                          <div class="err-notification"></div>
                             <div class="col-xs-12">
                               <button class="btn btn-sm btn-primary " type="submit">AddUser<i class="fa fa-plus push-20-l"></i></button>
                            </div>
                       </div>
                    </form>
                     <!--  Register Form End Here -->
</div>
<!-- Event register form  Content end-->
</div>
<!-- END Section Content -->
</section>