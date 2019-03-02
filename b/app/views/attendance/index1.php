<section class="content content-boxed content-full push-20-t">
<div class="row items-push push-20 ">
	<div class="col-lg-12">
		<div class="block" id="PresentData">
	
	<div class="block-header bg-gray-lighter">
	 <span><b>Attendance Details</b></span>
	  <ul class="block-options push-5 md-margin">
                    <li>
                   
							</li>
                          <li>
                          <form class="md-push-5">
                          <input class="for" type="hidden" name="for" id="for"  value='<?php if(!$teamleads && isset($for)) echo $for?>'>
                          <?php if($_SESSION['authprivilage']=="employee"){?>
                            	<input type="hidden" name="empName" id="employeeName"  value="<?php echo $_SESSION['employee_name']?>" >
                           <?php }else{?> 
                           		<input type="hidden" name="empName" id="employeeName"  value="<?php echo $_SESSION['login_id']?>" >
                           <?php }?>
                               <div class="datetimepickert input-group input-group date">
                                    <input class="form-control js-datetimepicker" data-format="MMM-YYYY" id="at_calender" name="at_calender"  placeholder="Select month to view.."  value="<?php if(!$teamleads && isset($for)) echo date('M-Y',strtotime($selectedMonth))?>" type="text">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                            </div>
                            
                       </form>
                        </li>
                        <li>
                        
                        <li class="pull-right push-5-l" style="margin-top:7px !important">
                            <button id="Srefresh" type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo" class=""><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                   
              </div> 
<div class="block-content block-content-full text-center ">
    
    
     <div id="Status">
     </div>
     
		      <script type="text/x-template" id="attendance-Present">
     <table class="table table-condensed myattendance-dataTable no-footer cell-border table-hover responsive table-bordered " style="width: 2775px;
margin: 0px !important" id="Statusview"  >

 {#status}      

                             
                              {status|s}

 

 {/status}      

</table>
   </script>
     </div>
   

<div class="row" style="margin: ;margin: 0px !important;"><div class="pull-left col-lg-4 col-xs-12 col-md-4 hide"><div style="padding-left: 0px;" class="col-xs-12  text-left"><span class="h5 font-w300"><span style="color:#d26a5c;text-decoration: underline;">P</span></span>
<span class="text-danger"><i class="fa fa-caret-right"></i></span>  Worked Less than Working hours&nbsp;&nbsp;&nbsp;&nbsp;</div></div></div>




</div>


</div>

</div>
<div class="row items-push push-20 ">
	<div class="col-lg-12">
		<div class="block" id="attendance-template">
	
	<div class="block-header bg-gray-lighter">
	 <span><b>Attendance Details</b></span>
	   <ul class="block-options push-5 md-margin">
                    <li>
                   
							</li>
							
                          <li>
                          <form>
                          <input class="for" type="hidden" name="for" id="for"  value='<?php if(!$teamleads && isset($for)) echo $for?>'>
                          <?php if($_SESSION['authprivilage']=="employee"){?>
                            	<input type="hidden" name="empName" id="employeeName"  value="<?php echo $_SESSION['employee_name']?>" >
                           <?php }else{?> 
                           		<input type="hidden" name="empName" id="employeeName"  value="<?php echo $_SESSION['login_id']?>" >
                           <?php }?>
                               <div class="datetimepicker input-group input-group date">
                                    <input class="form-control js-datetimepicker" data-format="MMM-YYYY" id="calender" name="calender"  placeholder="Select month to view.."  value="<?php if(!$teamleads && isset($for)) echo date('M-Y',strtotime($selectedMonth))?>" type="text">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                            </div>
                            
                       </form>
                        </li>
                        <li>
                        
                        <li class="pull-right push-5-l" style="margin-top:7px !important">
                            <button id="refresh" type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo" class=""><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                   
                </div>
<div class="block-content block-content-full text-center">
    
    
     <div id="attendances">
     </div>
		      <script type="text/x-template" id="attendance-view">
 
                   <table class="table table-condensed myattendance-dataTable no-footer cell-border table-hover responsive " id="view"  >

                        <thead id="attendance-head">
                            
                                
                               <tr>
                                
                                
                                <th class="text-left">Date</th>
                                <th class="text-left">Shift</th>
								<th class="text-left">Team Name</th>
                                <th class="text-left">Checkin</th>
                                
<th class="text-left" >Checkout</th>
<th class="text-left word-wrap" style="display: block;
width: 215px;word-wrap: break-word">All Punches</th>
<th class="text-left"">Late</th>
<th class="text-left">Early Out</th>
<th class="text-left">Total</th>

                            </tr>
                            

                        </thead>
                        <tbody>
 {#total}
<div class="pull-left col-xs-12 col-md-4">
<div class="row items-push"><div class="col-xs-7 text-left">
<div class="h4 font-w300">
<i class="si si-clock h4">&nbsp;</i>{total_time} hrs</div>
<div class="text-muted h5 font-w300 text-danger">Late 
</div>
</div>
</div>
</div>
{/total}
{#total}   
<div class="pull-left col-xs-12 col-md-4"><div class="row items-push">
<div class="col-xs-12 text-left" style="padding-left: 0px;">
{@select key=total_time}
{@ne value=Null type="boolean"}
<div class="h4 font-w300"><i class="si si-clock h4">&nbsp;</i>{total_time} hrs
</div>
<div class="text-muted h5 font-w300 text-danger">Total late for this payroll month(Late+Early Out)
</div>
{:else}
<div class="h4 font-w300"><i class="si si-clock h4">&nbsp;</i>0 hrs
</div>
<div class="text-muted h5 font-w300 text-danger">Total late for this payroll month(Late+Early Out)
</div>
{/ne}
{/select}
</div>
</div>
</div>
{/total} 
 

                                {#attendances}

                       <tr>
                                
                                <td class="text-left date">{DATE}</td>
                                <td class="text-left"> {shift_name}</td>
								<td class="text-left"> {team_name}</td>
                                <td class="text-left">{check_in}</td>
                                <td class="text-left">{check_out}</td>
                                <td class="text-left word-wrap" style="word-wrap:break-word">{all_punches|s}</td>
								<td class="text-left">
								{@select key=late_status}
								{@eq value="W"}	
								<a  href="javascript:void(0)" title="Withdrawn" class="text-danger"><span class="text-normal">{late}</span>&nbsp;<i class="fa fa-times"></i></a>
								{:else}
								{@eq value="RQ"}
								<a  href="javascript:void(0)" title="Requested" class="text-primary"><span class="text-normal">{late}</span>&nbsp;<i class="fa fa-spinner"></i></a>
								{:else}
								{@eq value="R"}
								<a href="javascript:void(0)" title="Rejected" class="text-danger"><span class="text-normal">{late}</span>&nbsp;<i class="fa fa-times"></i></a>
								{:else}
								{@eq value="NA"}
								<a  href="<?php echo myUrl('attendance/regularization')?>" title="Raise Request" class="text-success"><span class="text-normal">{late}</span>&nbsp;<i class="fa fa-caret-right"></i></a>
								{:else}	
								{@eq value="A"}
								<a  href="javascript:void(0)" title="Approved" class="text-success"><span class="text-normal">{late}</span>&nbsp;<i class="fa fa-check"></i></a>
								{:else}
								<span class="text-normal">{late}</span>
								{/eq}	
								{/eq}
								{/eq}
								{/eq}
								{/eq}
								{/select}
								</td>
								<td class="text-left">
								{@select key=early_status}
								{@eq value="W"}	
								<a  href="javascript:void(0)" title="Withdrawn" class="text-danger"><span class="text-normal">{earlyout}</span>&nbsp;<i class="fa fa-sign-out"></i></a>
								{:else}
								{@eq value="RQ"}
								<a  href="javascript:void(0)" title="Requested" class="text-primary"><span class="text-normal">{earlyout}</span>&nbsp;<i class="fa fa-spinner"></i></a>
								{:else}
								{@eq value="R"}
								<a href="javascript:void(0)" title="Rejected" class="text-danger"><span class="text-normal">{earlyout}</span>&nbsp;<i class="fa fa-times"></i></a>
								{:else}
								{@eq value="NA"}
								<a  href="<?php echo myUrl('attendance/regularization')?>" title="Raise Request" class="text-success"><span class="text-normal">{earlyout}</span>&nbsp;<i class="fa fa-caret-right"></i></a>
								{:else}	
								{@eq value="A"}
								<a  href="javascript:void(0)" title="Approved" class="text-success"><span class="text-normal">{earlyout}</span>&nbsp;<i class="fa fa-check"></i></a>
								{:else}
								<span class="text-normal">{earlyout}</span>
								{/eq}	
								{/eq}
								
								{/eq}
								{/eq}
								{/eq}
								{/select}
								</td>
								<td class="text-left total" id="total">{total_time}</td>
                            </tr>
	
           
					{/attendances}                            
                                                        
                                                        
                                                        
                                                    </tbody>

 

</div>


</table>


<div class="col-sm-12 text-left	 push-100-t">
<div class="alert alert-info alert-dismissable" style="color: #3a87ad;
background-color: #d9edf7">
            				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
<p><i class="fa fa-caret-right"></i>&nbsp;   <span class="h5 font-w300"><span style="color:#d26a5c;text-decoration: underline;">P </span></span>
&nbsp;Worked Less than Working hours&nbsp;&nbsp;&nbsp;&nbsp;</p>
                              <p><i class="fa fa-caret-right"></i>&nbsp <i class="text-success fa fa-check"></i> &nbspThis will not considered in monthly late as it is regularized.

</p>
<p class=""><i class="fa fa-caret-right"></i>&nbsp You're allowed to maximum 4hrs flexibility in a month. Exceeding this will affect the salary</p>
<p> <i class="fa fa-caret-right"></i> &nbspIf you came late/went early/had an incorrect punch you can raise a regularatization request via raising a request. 
<a href=<?php echo myUrl('attendance/regularization')?> >
Raise a request here</a></p>
	</div>
</div>

</script>


 

</div>



</div>


</div>

</div>
<!-- new table starts here -->

</section>