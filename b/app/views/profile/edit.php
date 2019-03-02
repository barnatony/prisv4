<!-- block1 end here -->
    <div class="cardheader bg-img-no-repeat push-50" style="background-image:url('<?php echo myUrl('img/background/profile-background.jpg');?>');min-height:300px">
      <section class="content content-boxed">
		<!-- Section Content -->
		<div class="push-100-t  text-center">
	     <h1 class="h2 text-white">Hello,<?php echo ucfirst(strtolower(explode(" ",$user->get("name"))[0]));?>&nbsp;<?php echo $user->get("lastname");?></h1>
	       <a class="user-img user-attribute img-avatar-thumb text-center" data-toggle="modal" data-target="#modal-small" >
            <div class="user-dp" title="Profile" style="background-image:url('<?php echo file_exists($_SERVER["DOCUMENT_ROOT"].myUrl($user->get("image")))?myUrl($user->get("image")):myUrl("img/avatar.jpg")?>');"></div>
            <span class="change hide">Change</span></a>
		</div>
		<!-- END Section Content -->
	</section>
    </div>
   
 <section class="block-content block-content-narrow content-boxed">
 <div class="row items-push push-30-t push-20 ">
        <!-- Section Content -->
        <div class="block col-md-12 box-shaddow">
            <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs">
                       <li class="active">
                        <a class="text-uppercase" href="#personal">Personal</a>
                      </li>
                      <li class="">
                        <a class="text-uppercase" href="#general">General</a>
                      </li>
                      <li>
                        <a class="text-uppercase" href="#privacy">Privacy</a>
                    </li>
                </ul>
          </div>
<!--start tab contents --> 
<div class="tab-content">
<!-- start upcoming content -->   
<div class="tab-pane active" id="personal">
    <div class="col-sm-12 col-xs-12 box-shaddow bg-white push-20">
		      <div class=" col-sm-12">
                 <form  class="form-horizontal" action="<?php echo myUrl('profile/ops_update')?>" id="profile-edit" method="POST">
                 
                 <?php if($user->get("username")){?>
                 <div class="col-sm-12">
                   <div class="form-group text-center  table-bordered push-30-t">
                       <div class="col-sm-6" style="padding:0px">
                           <div class="bg-primary-dark-op user-padding"><span class="h5">User Name</span></div>
                           <div class="bg-white user-padding"><span><?php echo $user->get("username");?></span>
                         </div>
                       </div>   
                      <div class="col-sm-6"  style="padding:0px">
                         <div class="bg-primary-dark-op user-padding"><span class="h5">Profile URL</span></div>
                         <div class="bg-white user-padding"><span> http://jokernet.in/<?php echo $user->get("username");?></span></div>
                       </div> 
                   </div>
                   </div>
                   <?php }else{?>
                     <div class="form-group push-30-t">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating open">
                                    <input class="form-control valid" id="username" name="username" required autofocus type="text">
                                    <label for="username">Username</label>
                                </div>
                                </div>
                          </div>
                   <?php }?>
                   
                      <div class="form-group">
                         <div class="col-sm-6 push-20">
                                <div class="form-material  floating form-material-primary">
                                    <input  class="form-control" id="profile-first-name" name="profile_first_name" value="<?php echo $user->get("name");?>" required type="text" autofocus>
                                    <label for="profile-first-name">First Name<span>*</span></label>
                                </div>
                         </div>
                         <div class="col-sm-6 push-20">
                                <div class="form-material  floating form-material-primary">
                                    <input  class="form-control" id="profile-last-name" name="profile_last_name" value="<?php echo $user->get("lastname");?>"  type="text">
                                    <label for="profile-last-name">Last Name<span>*</span></label>
                                </div>
                         </div>
                       </div>
                      <div class="form-group">
                          <div class="col-sm-6 push-20">
                                <div class="form-material  form-material-primary js-datetimepicker input-group date">
                                 <input class="form-control" id="user-dob" name="user_dob" data-date-format="DD/MM/YYYY" type="text"  autocomplete="off" value="<?php if($user->get("dob") && $user->get("dob")!=0) echo date("dd/mm/Y",strtotime($user->get("dob")));?>" >
                                  <label for="user-dob">DOB<span>*</span></label>
                                   <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                             <div class="col-sm-6 push-20">
                               <label for="user_signup_gender">Gender<span>:</span></label>
                               <div class="form-group">
                                       <label class="css-input css-radio css-radio-primary push-10-r"> 
                                    	<input name="user_signup_gender" type="radio" value="Female"  <?php echo ($user->get("gender")=='Female')? 'checked':''  ;?> ><span></span> Female
                                    </label>
                                    
                                    <label class="css-input css-radio css-radio-primary">      
                                        <input name="user_signup_gender" type="radio" value="Male"  <?php echo ($user->get("gender")=='Male')? 'checked':''  ;?>><span></span> Male
                                    </label>
                                    <label class="css-input css-radio css-radio-primary">
                                        <input name="user_signup_gender" type="radio" value="Others"  <?php echo ($user->get("gender")=='Others')? 'checked':''  ;?>><span></span> I'm Special
                                    </label>
                                    
                               </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material floating  form-material-primary">
                                    <input  class="form-control" id="user-profile-email" value="<?php echo $user->get("email")?>" name="user_profile_email" required type="email">
                                    <label for="user-profile-email">Primary Email<span> *</span></label>
                                </div>
                            </div>
                        </div> 
                       <div class="form-group">
                          <div class="text-center">
                              <div class="notifications"></div>
                            <button class="btn btn-sm btn-primary  push-5" id="submit" type="submit">Save</button>
                            <button  class="btn btn-sm btn-primary push-5" type="reset" >Cancel</button>
	                     </div>
                      </div>
                   </form>
               </div>
           </div>
       </div>
 <!-- end upcoming contents -->  	
 
<!-- start past events content -->	
 <div class="tab-pane" id="general">
     <div class="col-sm-12 col-xs-12 box-shaddow bg-white push-20">
            <div class="col-sm-8 col-sm-offset-2">
               <form  class="form-horizontal push-30-t" action="<?php echo myUrl('auth/login')?>" id="profile-secure" method="POST" >
                   <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material  form-material-primary">
                                     <input type="hidden" name="next"  value="<?php echo myUrl('profile/change_password')?>">
                                    <input  class="form-control" id="user-profile-password" name="password" placeholder="Enter your profile password"required type="password">
                                    <label for="user-profile-password">Your profile Password<span> *</span></label>
                                  </div>
                            </div>
                        </div> 
                          <div class="push-20 text-center">
                           <div class="err-notification"></div>
                            <button class="btn btn-sm btn-primary push-5" type="submit">Go</button>
	                      </div>
                    </form>
                 </div>
               </div>
           </div>
           <div class="tab-pane" id="privacy">
           	 <div class="col-sm-12 col-xs-12 box-shaddow bg-white push-20">
               <div class="col-sm-8 col-sm-offset-2 push-30-t push-30">
               <div class="notification"></div>
                   <div class="col-sm-12 push-20" style="border-bottom: 1px solid #0000001a;">
                      <div class="font-s15 font-w600 push-10">Notifications</div>
                   </div>
                    <?php if(!$notifications){?>
                      <p class="font-w400 font-s17 push-5-t push-5 text-center">No Notifications Found</p>
                   <?php }foreach($notifications as $notification){?>
                   <div class="row push-10-t push-10">
                      <div class="form-group">
                         <div class="col-xs-8">
                             <div class="font-s13 font-w600"><?php echo $notification['name']; ?></div>
                             <div class="font-s13 font-w400 text-muted"><?php echo  $notification['description']?></div>
                           </div>
                            <div class="col-xs-4 text-center">
                               <label class="css-input switch switch-sm switch-primary push-10-t">
                               <?php if($notification['status']=="1"){?>
                                  <a data-type='<?php echo  $notification['notification_type']?>' class="enable" data-value=1 title="Notifications Off"> <input checked type="checkbox"><span></span></a>
                               <?php }else{?>
                                 <a  data-type='<?php echo  $notification['notification_type']?>' class="enable" data-value=0 title="Notifications Show"> <input type="checkbox"><span></span></a> 
                              <?php }?>
                                </label>
                              </div>
                       </div>
                       </div>
                       <?php }?>
                 </div>
            </div>     
	   </div>
   </div>
</div>

      <!-- end past event contents --> 	
     <!-- END tab content -->
</section>
 <!-- end section here --> 
 <div style="display: none; padding-right: 17px;" class="modal in " id="modal-small" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content col-md-12 col-xs-12">
            <div class="block block-themed block-transparent remove-margin-b">
                    <ul class="block-options" style="margin:5px;">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                     <div class="block-content block-content-full col-md-12 col-xs-12">
                      <div id="sysfrm_ajaxrender">
                       <form role="form" id="image-update" name="uc" method="POST" action="<?php echo myUrl('users/update_img')?>">
                        <div class="form-group text-center">
                        <button type="button" id="cropContainerHeaderButton" class="btn btn-sm btn-info"><i class="fa fa-plus push-5-r"></i>Upload Photo</button>
                        <div id="croppic" class="push-30-t push-30 img-responsive"></div>
                        <input type="hidden" name="image_url" id="image_url">
                        <div class="err-notification"></div>
                        <button type="submit" id="opt_gravatar" class="btn btn-sm btn-primary push-5"><i class="fa fa-check push-5-r"></i>Save</button>
                        <button type="button" id="cancel" class="btn btn-sm btn-primary push-5" ><i class="fa fa-close push-5-r"></i>Cancel</button>
                        <button type="button" id="no_image" class="btn btn-sm btn-primary push-5" type="reset">No Image</button>
                        </div>
                       </form>
                     </div>
                  </div>
                 </div>
               </div>
           </div>
        </div>
 
