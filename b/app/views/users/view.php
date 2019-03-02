<div class=" bg-gray-lighter">
<section class="content content-full">
<!-- Section Content start here-->
<div class="content  content-boxed">
<div class="row push-50-t">
<div class="col-sm-12  bg-white push-20 box-shaddow">
<!-- sub section block1 start here -->
<div class="block-header">
           <div class="col-sm-6 col-md-8 col-xs-3">
              <h3 class="font-w400 text-black push-5">Users</h3>
		   </div>
		   <div class="col-sm-3 col-md-2 col-xs-5 text-right h5 font-w600" style="border-right: 1px solid #f16829;">
		   	<div><span><?php if(isset($searched_count) && $searched_count)echo $searched_count."/";echo $total?></span></div>  
			  <div><span> Users</span></div>
		   </div>
          <div class="col-sm-3 col-md-2 col-xs-4 text-right">
               <a class="h5 font-w600" href="<?php echo myUrl('users/add');?>" ><i class="fa fa-plus"></i>
                          <div>ADD USER</div>
              </a>
         </div>
</div>
</div>

              

<div class="row push-10-t push-10">
<!-- sub section block1 start here -->

<?php if($users){
	foreach ($users as $user){?>
    <div class="col-sm-6 col-lg-3">
      <div class="block box-shaddow" id="<?php echo $user->get('user_id');?>">
          <div class="block-header">
                    <ul class="block-options">
                     <?php if($user->get('user_id')!=$_SESSION["authuid"]){?>
                     <li>
                       <?php if($user->get('isActive')){?>
                      <button  type="button"  class=" enable" data-toggle="tooltip" data-trigger="hover" data-original-title="Disable User" data-status=0 data-value="<?php echo $user->get("user_id");?>">
                      <span class="label label-success">Active</span> 
                     </button>
                     <?php }else{?>
                         <button  type="button"  class=" enable" data-toggle="tooltip" data-trigger="hover" data-original-title="Enable User" data-status=1 data-value="<?php echo $user->get("user_id");?>">
                         <span class="label label-danger" >InActive</span>
                        </button>
                         <?php }?>
                        </li>
                        <?php }?>
                        
                        <li>
                        <?php if(!$user->get('p_reset_token')){?>
	                      <button class="text-danger resetpassword" type="button" data-toggle="tooltip" data-trigger="hover" data-original-title="Send Reset Password Link" data-value="<?php echo $user->get("user_id");?>"><i class="fa fa-undo"></i></button>
	                   <?php }else{?>
	                        <button class="text-success" type="button" data-toggle="tooltip" data-trigger="hover" data-original-title="Password Reset link Sent" data-value="<?php echo $user->get("user_id");?>"><i class="fa fa-check"></i></button>
	                   <?php }?>
                        </li>
                    </ul>
                    <?php if($user->get("last_login") !='0000-00-00 00:00:00'){?>
                    <div class="h5 font-w300 text-muted" data-toggle="tooltip" data-trigger="hover" data-original-title="Last Login on <?php echo date('d-m-Y h:i:s a',strtotime($user->get("last_login")));?>"><?php echo date('M d, h:i:s a',strtotime($user->get("last_login")));?></div>
                    <?php }else{?>
                    <div class="h5 font-w300 text-muted">&nbsp;</div>
                    <?php }?>
                </div>
  
<div class="block-content text-center">
<div class="">
<?php if($user->get("image")){?>
<img class="img-avatar img-avatar96" src="<?php echo myUrl($user->get("image"))?>" alt="">
<?php }else{?>
<img class="img-avatar img-avatar96" src="<?php echo myUrl("img/avatar.jpg")?>" alt="">
<?php }?>
<?php if($user->get("privilage")=="Admin"){?>
		<i class="fa fa-user push-5-r text-success user-type"></i>
	<?php }?>
</div>
<div class="push-15-t push-10">

<h2 class="h3 push-5-t"><?php echo ucfirst(strtolower(explode(" ",$user->get('name'))[0]))?></h2>
<?php if($user->get('username')){?>
<div class="text-center"><?php echo $user->get('username')?></div>
<?php }else{?>
	<div class="text-center">-</div>
<?php }?>
<div class="text-center"><i class="fa fa-envelope-o"></i>&nbsp;<?php echo $user->get('email')?></div>
</div>
</div>
</div>
</div>
<?php }}else{?>
<p class="font-w400 font-s17 push-5-t push-5 text-center">No User found</p>
<?php }?>
</div>
</div>
</div>
<?php echo isset($pagination)?$pagination:"";?>      
</section>
</div>