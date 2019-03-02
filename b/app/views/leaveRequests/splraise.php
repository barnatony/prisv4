<?php 
$leaverules="";
if($leaveAccounts){?>
<div class="bg-gray-lighters col-md-12" style="padding-top: 20px;">
<div class="content_act  border-b row">
    <div class="items-push text-uppercase leave-req-t">
    
    <?php 
    
    
    	foreach ($leaveAccounts as $leaveAccount){?>
    <div class="col-xs-12 col-sm-4 col-md-4">
<table class="block-table text-center box-shaddow">
                                <tbody>
                                    <tr>
                                        <td class="bg-white" style="width: 40%;">
                                            <div class="push-30 push-30-t">
                                                <h2 class="font-w300"><?php echo strtoupper($leaveAccount["leave_rule_id"])?></h2>
                                            </div>
                                        </td>
                                        <td class="bg-gray-lighter text-left" style="width: 60%;">
                                        <h3 class="font-w300 text-primary"><?php echo strtoupper($leaveAccount["this_month"])?></h3>
                                        <div class="text-muted animated fadeIn" title="Monthly Balance"><small><i class="si si-calendar req"></i> This Month</small></div>
                                        <h3 class="font-w300 text-primary"><?php echo strtoupper($leaveAccount["available"])?></h3>
                                        <div class="text-muted animated fadeIn" title="Yearly Balance"><small><i class="si si-calendar req"></i> This Year</small></div>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            <?php 
if($leaveAccount["this_month"]>0){
	$leaverules .= "<option data-available='".$leaveAccount["this_month"]."' value='".$leaveAccount["leave_rule_id"]."'>".strtoupper($leaveAccount["leave_rule_id"])."</option>";
	$leaveBalance = true;
}
}
    
    ?>
                           
                            	
                            </div>
                            </div>
     </div>
     <?php }
     $leaverules .= "<option value='lop'>LOP</option>";
     $leaverules .= "<option value='od'>On Duty</option>";
     $leaverules .= "<option value='wfh'>Work From Home</option>";
     $leaverules .= "<option value='Otr'>On Trip/Tour</option>";
     ?>
<section class="content">
<div class="content  content-boxed col-md-12">
<div class="push-20-t bg-white">
<div class="block-header bg-gray-lighter">
			<span><b>Leave Requests</b></span>
				<ul class="block-options push-5 md-margin">
                    <li>
                   <div class="notification"></div>
                  <a data-toggle="tooltip" title="" data-original-title="Leave Requests" href="<?php echo myUrl("leaveRequests")?>"><i class="si si-arrow-left fa-2x"></i></a>
							</li>
							</ul>
				
			</div>
		      <div class=" block-content table-responsive">
		      <?php 
		      if($restrictions["pendingRequests"]==false ||($restrictions["pendingRequests"] && !$leaveRequests)){?>
		      <div style="color: #3a87ad;background-color: #d9edf7;margin: 0px 0px 25px 0px;" class="alert alert-info alert-dismissable">
            				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                              <p><i class="fa fa-caret-right"></i> You can't Apply Leaves for the past week after Tuesday of the current week.</p>
							<p><i class="fa fa-caret-right"></i> OD, WFH, On Trip will be processed only by HR. You can contact the respective HR for Approval</p>
			</div>
		      
                 <form class="form-horizontal" action="<?php echo myUrl("leaveRequests/add")?>" id="leave-request" method="POST" novalidate="novalidate">
                 
                      <div class="form-group">
                          <div class="col-sm-6 ">
                                <div class="form-material  form-material-primary js-datetimepicker input-group date">
                                 <input class="form-control js-datetimepicker" placeholder="Select From Date" id="duration-from" name="duration_from" data-min-date="<?php echo $Rdate ?>" data-date-format="DD/MM/YYYY" autocomplete="off" value="" required type="text">
                                  <label for="duration">Duration<span>*</span></label>
                                   <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                             <div class="col-sm-6 ">
                               <label for="duration"><span></span></label>
                               <div class="form-group">
                                       <label class="css-input css-radio css-radio-primary push-10-r"> 
                                    	<input name="from_half" value="FH" type="radio" checked><span></span>First Half
                                    </label>
                                    
                                    <label class="css-input css-radio css-radio-primary">      
                                        <input name="from_half" value="SH" type="radio"><span></span> Second Half
                                    </label>
                               </div>
                            </div>
                        </div>
                        
                       <div class="form-group">
                         <div class="col-sm-6">
                                <div class="form-material  form-material-primary js-datetimepicker input-group date">
                                 <input class="form-control js-datetimepicker" placeholder="Select To Date" data-use-current='false' id="duration-to" data-min-date="<?php echo $Rdate ?>" name="duration_to" data-date-format="DD/MM/YYYY" autocomplete="off" value="" required type="text">
                                   <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                         </div>
                         <div class="col-sm-6">
                         <label for="user_signup_gender"><span></span></label>
                                 <div class="form-group">
                                       <label class="css-input css-radio css-radio-primary push-10-r"> 
                                    	<input name="to_half" value="FH" type="radio" ><span></span>First Half
                                    </label>
                                    
                                    <label class="css-input css-radio css-radio-primary">      
                                        <input name="to_half" value="SH" type="radio" checked><span></span> Second Half
                                    </label>
                               </div>
                               
                               
                         </div>
                         
                         
                       </div>
                       
                       <div class="form-group hide " id="duration-div">
<div style="" class="col-sm-5 col-md-2 text-left">
  <input name="duration" id="duration" type="hidden">
  <input name="employee_id" id="employee_id" type="hidden" value="<?php echo isset($_SESSION["employee_id"])?$_SESSION["employee_id"]:""?>">
                        <span class="h5" id="day-text"></span>
                        <div class="h4">Day(s)</div>
                    </div>
                    <div class="col-sm-7 col-md-10 has-warning  hide" id="holiday-found">
                    <div class="help-block">You have holiday(s) or week-off(s) falling in between the specified duration. This will lead to the whole duration to be considered as a LOP.</div>
                    </div>
</div>

                       <div class="form-group">
                       <input name="considerLop" id="considerLop" type="hidden" value="0">
                        <label class="col-xs-12" for="leave">Leave Type:</label>
                            <div class="col-xs-12">
                            <div class="form-material form-material-primary">
                          		 <select class="js-select2 form-control" id="leave_rule" required name="leave_rule" style="width: 100%;" data-placeholder="Choose a Leave Type" required>
                          		 <option value=""></option>
 										  <?php echo $leaverules;?>                      
                             </select>
                          </div>
                            </div>
                            </div>
                            
                            
                            <div class="form-group">
                            <div class="col-lg-12">
                                <div class="form-material form-material-primary">
                                    <textarea class=" form-control" id="reason" name="reason" rows="3" maxlength="100" data-always-show="true" placeholder="Reason.."></textarea>
                                    <label for="example-material-maxlength7">Reason</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <div class="text-center">
                            <button class="btn btn-sm btn-primary  push-5" id="submit" type="submit">Submit</button>
                            <button class="btn btn-sm btn-primary push-5" type="reset">Cancel</button>
	                     </div>
                      </div>
                   </form>
                   <div class="notifications"></div>
                   <?php }else{?>
                   
                    <!-- Warning Alert -->
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h3 class="font-w300 push-15">Hey,</h3>
                        <p>You have <b><?php echo count($leaveRequests)?></b> pending Leave Requests, You can't raise one untill you clear them..!</p>
                    </div>
                    <!-- END Warning Alert -->
                    
                    
                   <!-- Pending Leave Requests Block -->
                   <div class="block block-bordered">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Pending Leave Requests</h3>
                </div>
                <div class="block-content bg-gray-lighter hide">
                    <div class="row items-push">
                        <div class="col-xs-4">
                            <div class="text-muted"><small><i class="si si-calendar"></i> 24 hrs</small></div>
                            <div class="font-w600">18 Sales</div>
                        </div>
                        <div class="col-xs-4">
                            <div class="text-muted"><small><i class="si si-calendar"></i> 7 days</small></div>
                            <div class="font-w600">78 Sales</div>
                        </div>
                        <div class="col-xs-4 h1 font-w300 text-right">$769</div>
                    </div>
                </div>
                <div class="block-content">
                    <div class="pull-t pull-r-l">
                    <div class="" data-toggle="slimscroll"  data-height="300px" >
                    <table class="table remove-margin-b font-s13 table-striped">
                                    <tbody>
                                    <?php foreach ($leaveRequests as $key=>$request){?>
                                        <tr>
                                            <td class="font-w600">
                                                <h5 class="font-w300 text-primary">
                                                <?php 
                                                if($request->get("from_date")==$request->get("to_date"))
                                                	echo date('d M, Y', strtotime($request->get("from_date")));
                                                else 
                                                	echo date('d M, Y', strtotime($request->get("from_date")))." - ".date('d M, Y', strtotime($request->get("to_date")));?>
                                                </h5>
                                                <span class="label label-info"><?php echo strtoupper($request->get("leave_type"));?></span>
                                                <div class="text-muted"><small><?php echo $request->get("reason");?></small></div>
                                            </td>
                                            <td class="hidden-xs text-muted text-right" style="width: 70px;"><div style="" class="col-sm-5 col-md-2 text-left">
                        <span class="h5"><?php echo $request->get("duration");?></span>
                        <small>Day(s)</small>
                    </div></td>
                    <td>
                    <span class="badge badge-warning">Pending</span>
                    </td>
                                            <td class="font-w600 text-success text-right" style="width: 70px;">
                                            <button class="btn btn-rounded btn-sm btn-danger-outline btn-danger push-5-r push-10 withdraw" data-value="W" data-id="<?php echo $request->get("id");?>" type="button" title="withdraw leave"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                       <?php }?>
                                    </tbody>
                                </table>
                    </div>
                                
                            
                            
                        </div>
                        <!-- END Slick slider -->
                    </div>
                </div>
            </div>
                   <!-- END Pending Leave Requests -->
                   <?php }?>
               </div>
          
 
   </div>

 
</section>
</div>