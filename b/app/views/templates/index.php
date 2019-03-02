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
                    <h3 class="block-title">Add Template</h3>
                </div>
                <div class="block-content">
                  <form novalidate="novalidate" class="js-validation-login  form-horizontal push-30-t" id="add-template" action="<?php echo myUrl('templates/update')?>" method="POST">
                  <input type="hidden" name="tId" value="<?php echo $template->get("id");?>"/>
                       <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material form-material-primary floating ">
                                    <input class="form-control" id="template-name" name="template_name" value="<?php echo $template->get("tplname");?>" required autofocus type="text">
                                    <label for="template-name">Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="template-subject" name="template_subject" value="<?php echo $template->get("subject");?>" required type="text">
                                    <label for="template-subject">subject</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                        <div class="col-sm-12">
                                <div class="form-material form-material-primary floating ">
                                    <input class="form-control" id="notification_type" name="notification_type" value="" required autofocus aria-required="true" type="text">
                                    <label for="notification_type">Notification type</label>
                                </div>
                            </div>
                            </div>
           <div class="form-group">
              <div class="block-content block-content-full">
            <!-- Summernote Container -->
               <label for="message">Message<span>*</span></label>
               <div class="form-material  floating form-material-primary">
            	<textarea class="js-summernote" id="message"  name="message"><?php echo $template->get("message");?></textarea>
               </div>
              </div>
           </div>
  
                    <!-- -      <div class="form-group">
                             <div class="block-content block-content-full">
                            <!-- Summernote Container -->
                            <!--     <label for="message">Message<span>*</span></label>
                             <div class="form-material  floating form-material-primary">
            	                 <textarea id="js-ckeditor"  name="message"><?php echo $template->get("message");?></textarea>
                            </div>
                         </div>
                      </div> -->
                      <div class="form-group">
                         <div class="col-sm-12">
                            <div class="col-xs-6">
                            <label class="css-input css-checkbox css-checkbox-primary">
                             SEND <input <?php echo $template->get("send")=="Yes"?"checked":$template->get("send")==""?"checked":"";?> class="" type="checkbox" name="send" value="Yes"><span></span> 
                            </label>
                        </div>
                        <div class="col-xs-6">
                            <label class="css-input css-checkbox css-checkbox-primary">
                            CORE <input <?php echo $template->get("core")=="Yes"?"checked":$template->get("core")==""?"checked":"";;?> class="" type="checkbox" name="core" value="Yes"><span></span> 
                            </label>
                        </div>
                        </div>
                        </div>
                      <div class="form-group">
                          
                         <div class="col-xs-12">
                         <div class="err-notification"></div>
                         
                         	  <?php if($template->get("id")){?>
                         	  	<button class="btn btn-sm btn-primary pull-right" type="submit">UPDATE<i class="fa fa-edit push-10-l"></i></button>
                         	  	<a class="btn btn-sm btn-primary pull-right push-10-r" href="<?php echo myUrl('templates');?>">ADD NEW<i class="fa fa-plus push-10-l"></i></a>
                              	
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
                    <h3 class="block-title">templates</h3>
                </div>
                <div class="block-content">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 30px;">#</th>
                                <th>Name</th>
                                <th class="hidden-xs">Access</th>
                                <th class="text-center" style="width: 80px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($templates as $key=>$template){?>
                            <tr>
                                <td class="text-center"><?php echo ++$key;?></td>
                                <td><?php echo $template->get("tplname");?></td>
                                <td class="hidden-xs">
                                     <span class="label label-warning push-5-r" title="Send"><?php echo $template->get("send");?></span><span class="label label-info" title="Core"><?php echo $template->get("core");?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a data-original-title="Edit Template" class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Template" href="<?php echo myUrl('templates/index/'.$template->get("id"));?>"><i class="fa fa-pencil"></i></a>
                                        <button data-original-title="Remove Template" class="btn btn-xs btn-default delete" data-url="<?php echo myUrl('templates/delete/'.$template->get("id"));?>" type="button" data-toggle="tooltip" ><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php }?>
                         </tbody>
                    </table>
                </div>
            </div>
            <!-- END Striped Table -->
        </div>
  
     </div>
  </section>
</div>