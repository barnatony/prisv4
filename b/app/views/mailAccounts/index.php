<div class="">
  <section class="content">
     <div class="row push-50-t">
     <div class="col-lg-6">
            <!-- Default Table -->
            <div class="block">
                <div class="block-header">
                   <ul class="block-options">
                        <li>
                            <!-- To toggle fullscreen a block, just add the following properties to your button: data-toggle="block-option" data-action="fullscreen_toggle" -->
                            <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Add Account</h3>
                </div>
                <div class="block-content">
                  <form novalidate="novalidate" class="js-validation-login  form-horizontal push-30-t" id="add-account"  action="<?php echo myUrl('mailAccounts/update')?>" method="POST">
                  <input type="hidden" name="tId" value="<?php echo $id?>"/>
                       <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material form-material-primary floating ">
                                    <input class="form-control" id="host" name="host" value="<?php echo $mail->get('host')?>" required autofocus type="text">
                                    <label for="host">Host</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="username" name="username" value="<?php echo $mail->get('username')?>" required type="email">
                                    <label for="username">UserName(Email)</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material form-material-primary input-group floating">
                                    <input class="form-control password" id="password" name="password" value="<?php echo $mail->get('password')?>" required type="password">
                                    <label for="password">Password</label>
                                    <span class="input-group-addon"><i class="fa fa-eye-slash pwd-visibility"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="port" name="port" value="<?php echo $mail->get('port')?>" required type="text">
                                    <label for="port">Port</label>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                        <label class="col-sm-12" for="secure">Secure:</label>
                            <div class="col-sm-12">
                                <div class="form-material  floating form-material-primary ">
                                    <select class="form-control" id="secure" name="secure" required>
                                      <option value="">Select one</option>  
                                    <option value="tls" <?php echo ($mail->get('secure')=="tls"? 'selected':"") ?> >TLS</option>
                                    <option value="ssl" <?php echo ($mail->get('secure')=="ssl"? 'selected':"") ?>>SSL</option>
                                    </select>
                                </div>
                            </div>
                            </div>
                      <div class="form-group">
                      <div class="col-sm-12">
                         <div class="err-notification"></div>
                         <?php if($mail->get("id")){?>
                         <button  class="btn btn-sm btn-primary pull-right" type="submit">UPDATE<i class="fa fa-edit push-10-l"></i></button>
                              <?php }else{?>
                         <button class="btn btn-sm btn-primary pull-right" type="submit">ADD<i class="fa fa-plus push-10-l"></i></button>
                              <?php }?>
                          </div>
                       </div>
                </form>
                </div>
            </div>
            <!-- END Default Table -->
        </div>
        <div class="col-lg-6">
            <!-- Striped Table -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Accounts</h3>
                    <div class="notification"></div>
                </div>
                <div class="block-content">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 30px;">#</th>
                                <th>Host</th>
                                <th class="hidden-xs">UserName</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php foreach($mails as $key=>$mail) {?>
                            <tr>
                                <td class="text-center"><?php echo ++$key;?></td>
                                <td><?php echo $mail->get("host")?></td>
                                <td class="hidden-xs">
                                     <span><?php echo $mail->get("username")?></span>
                                </td>
                                <td class="text-center">
                               
                                      <label class="css-input switch switch-small switch-primary">
                                      <?php if($mail->get('active')){?>
                                     <a data-id='<?php echo $mail->get('id')?>' class="active" data-value=0 title="set inactive"  ><input checked type="checkbox" ><span></span></a> 
                                     <?php }else{?>
                                      <a data-id='<?php echo $mail->get('id')?>' class="active" data-value=1 title="set active"  ><input  type="checkbox" ><span></span></a>
                                     <?php }?>
                                     </label>
                                       <a data-original-title="Edit mailAccounts" class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit mailAccounts" href="<?php echo myUrl('mailAccounts/index/'.$mail->get("id"));?>"><i class="fa fa-pencil"></i></a>
                                       <a class="btn btn-xs btn-default test" title="Test Mail"id="test" data-value="<?php echo $mail->get("id")?>" data-toggle="modal" data-target="#test-mail" type="button">
                                       <i data-original-title="Test Mail" data-toggle="tooltip" class="fa fa-gear"></i></a>
                                       <a class="btn btn-xs btn-default delete" title="Delete Mail" data-url="<?php echo myUrl('mailAccounts/delete/').$mail->get("id")?>" data-toggle="modal" data-target="#delete-mail" type="button">
                                       <i data-original-title="Delete Mail" data-toggle="tooltip" class="fa fa-trash-o"></i></a>                   
								
						
                
                    <?php }?>
                
                
            
                                </td>
                            </tr>
                              
                         </tbody>
                    </table>
                    
                </div>
               
            </div>
          
            <!-- END Striped Table -->
             
            
  <!-- Modal starts here -->
            
  <div class="modal in" id="test-mail" tabindex="-1" role="dialog" aria-hidden="true" style="display: hide;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button class="text-white" data-dismiss="modal" type="button"><i class="si si-close text-white"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Enter mail ID here</h3>
                    
                </div>
                
      <form  id="test-email" novalidate="novalidate" class="test-mail" action="<?php echo myUrl('mailAccounts/test')?>" method="POST">
				<div class="form-group">
				<div class="col-xs-offset-1 col-xs-10 push-10">
				<div class="notifications"></div>
				<input type="hidden" name="id" id="id">
				
				<div class="form-material floating form-material-primary">
				<input class="form-control" id="email" name="email"   required type="email">
				<label for="email">Email</label>
				
				
				</div>
				</div>
				</div>
			
		<button class="btn btn-sm btn-primary push-30" name="submit" type="submit" style="margin-left: 99px;">Submit</button>
		</form>
            </div>
        </div>
    </div>
</div>
            
        </div>
        
  
     </div>
      
     
  </section>
  
</div>

