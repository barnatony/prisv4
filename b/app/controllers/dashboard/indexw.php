<section class="content">
<div class="row">

<div id="msg">

</div>



          <!--               <p><i class="fa fa-gift"></i> &nbsp;Happy birthday Naresh, &nbsp;<a class="alert-link" href="javascript:void(0)">VDart Group wishes you to have a great year ahead..!! <i class="si  si-emoticon-smile text-warning"></i></a></p>-->
          
          <div id="wish"></div>
           
                 
</div>


</section>
 
 
<script type="text/x-template" id="msg-tpl">
{#notes}
<h3 class="h4 push-10">
{text} 
<h3>
{/notes}

 </script>


<!--  myself and whats happening section starts here  -->

<section>
	<div class="row">
	
     <div class="col-lg-6 ">
     <div class="col-lg-12 col-sm-7 ">
     <div id="empdashboard">
     </div>
     </div>
     
     <div class="row">
    <div class="col-lg-12 ">
    <!-- myself starts here -->
    <div class="col-lg-6 col-sm-6 ">
    <div class="block block-themed" id="pay-template" style="height:308px !important">
                <div class="block-header bg-modern">
                    <ul class="block-options">
                    	<li>
                    		<a href="javascript:void(0)" class="text-white net" data-value="mypay" data-val="net_salary">
                    		<span style="color: #fff;"><small>Net</small> </span> </a>
                    	</li>
						<li class="text-white" style="color: #fff !important;">|</li>
						<li>
							<a href="javascript:void(0)" class="text-white gross" data-value="mypay"  data-val="gross_salary"><small>Gross</small></a>
						</li>
<li><button class="mypay" data-value="mypay" type="button"><i class="si si-refresh"></i></button></li></ul>
                    <h4 class="block-title">My Pay</h4>
                </div>
    <div id="mypay">
     </div>
     </div>
    </div>
    <div class="col-lg-6 col-sm-6 ">
    <div class="block block-themed" id="policies-template" style="height:308px !important">
     <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button class="mypay" type="button"  data-value="policies"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h4 class="block-title">Policies</h4>
                </div>
    <div id="policies"></div></div>
    </div>
    </div>
    </div>
     </div>

<div class="col-lg-6">
	<div class="col-lg-12 ">
	<div class="block block-themed"  id="today_view">
                <div class="block-header bg-default">
                
                
                 <ul class="block-options push-5 md-margin">
                    
                          <!-- <li>
                          <form>
                          
                               <div class="col-md-12 dashboard-date input-group input-group date " >
                                    <input class="form-control dashboard-datetimepicker" data-format="DD-MMM-YYYY" id="dashboard-calender" name="dashboard_calender"  placeholder="Select date to view.."  value="" type="text">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                            </div>
                       
                           
                        </form>
                        </li>-->
                        <li>
                          <form class="md-push-5">
                          
                               
<div class="input-group col-xs-6 dashboard-date input-group input-group date " style="width: 240px;">
                                    <input class="form-control dashboard-datetimepicker" data-format="DD-MMM-YYYY" id="dashboard-calender" name="dashboard_calender" placeholder="Select date to view.." value="" style="" type="text">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                            </div>

                       
                           
                        </form>
                        </li>
                        <li class="pull-right push-5-l" style="margin-top: 7px !important;">
                            <button id="whats_happening" type="button"  class=""><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    
                    <h4 class="block-title">What's Happening</h4>
	                     
                   
                
   
                   
                </div>
	<div id="today">
	</div>
	</div>
</div>
</div>
</div>
</section>

<script type="text/x-template" id="wish-tpl">
{#greetings}
{@select key=wishes}
{@ne value="" type="boolean"}
<div class="alert alert-info alert-dismissable animated fadeInDownBig" value="greetings.length">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
<p><i class="fa fa-gift"></i>&nbsp;
{wishes} <i class="si  si-emoticon-smile text-warning"></i></p>
</div>
{/ne}
{/select}

{/greetings}
</script>

 
<script type="text/x-template" id="today-dashboard">
        
                
<div class="block-content" style="height: 385px !important;">
 
 
                        <!-- Today Status -->
{@eq key=today.length value=0 }
<div class="pull-t pull-r-l"><div class=""> <div class="block-content block-content-full text-center push-100-t"><p>No Data Found. Either you are absent for the day or the data might be removed</p></div></div><table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>       </div>
{/eq}
<ul class="list list-timeline parent pull-t" >
{#today}
                        <li>
                            
                        
 
                    <div class="list-timeline-icon parent bg-default">
                                 <span class="font-s14" style="width: 18px;font-size: 12px;">{day_num}</span>
                                <hr>
                                <span class="font-s14" style="font-size: 12px;">{month}</span>
                             </div>
                    
                            <div class="list-timeline-content">
<p class="font-w600">{full_date}</p>
<a class="label label-info" href="javascript:void(0)" data-content="{start_time}-{end_time}"  onmouseover="initPopover(this)" title="" data-original-title="Shift-timings">{shift_name}</a>
 
 
  						<ul class="list list-timeline child pull-t" style="margin-top: -13px !important;">
                    		{#transactions}    
                       			{notes|s}
                        	{/transactions}
                    	</ul>
                            </div>
 {/today}
                        </li>
  


 
              
</ul>
</div>
 
 
</div>
</script>

<!-- myself and whats happening section ends heres  -->

<script type="text/x-template" id="emp-dashboard">
<a class="block block-rounded block-link-hover3" id="emp-template" href="<?php echo str_replace('/b/',"/emp/empProfileView.php" , myUrl('',true));?>">
                <div class="block-content block-content-full clearfix">
{#myself}
                <div class="row">
	    			<div class="col-lg-8">
	                    <div class="pull-left push-5-r">
				{@select key=employee_image}
				{@eq value="Nil"}
				{@select key=employee_gender}
					{@eq value="Female"}
	                    <img class="img-avatar" src="<?php echo str_replace('/b/',"/img/default-avatar_women.png" , myUrl('',true));?>" alt="">
					{:else}
						<img class="img-avatar" src="<?php echo str_replace('/b/',"/img/default-avatar_men.png" , myUrl('',true));?>" alt="">
					{/eq}
				{/select}
				{:else}
					<img class="img-avatar" src="<?php echo str_replace('/b/',"{employee_image}" , myUrl('',true));?>" alt="">
				{/eq}
				{/select}
	                    </div>
	                    <div class="">
	                        <div class="font-w600 push-5">{employee_name} - {employee_id}</div>
	                        <div><i class="si si-badge">&nbsp;</i><span class="text-muted">{designation}, {team_name} </span></div>
	                        <div><i class="si si-pointer">&nbsp;</i><span class="text-muted">{branch} </span></div>
	                    </div>
	                </div>
	                
					<div class="col-lg-4 text-right">
				<div class="h3 push-5">{experience|s}</div>
                            <div class="h5 font-w300 text-muted">Joined on {doj}</div>
		   		</div>
				</div>
{/myself}
</div>
            </a>
</script>



	
<script type="text/x-template" id="my-pay">                
               
{#mypay}
{#salary}	
{@select key=total_pay}
{@ne value="" type="boolean"}
 <div class="block-content bg-gray-lighter">
                    <div class="row items-push"><div class="col-xs-4"><div class="text-muted"><small><i class="si si-calendar"></i> This Year</small></div><div class="font-w600">{fyear}</div></div><div class="col-xs-8">
  					

					<div class="h3 font-w300 text-right">
  							<i class="fa fa-inr">&nbsp;</i>{total_pay}
  						</div>
					

  					<div class="text-muted h6 font-w300 text-right">{val}</div></div>
					</div>

                </div>
{/ne}
{/select}
{/salary}
                
                <div class="pull-t pull-r-l">
                    <table class="table remove-margin-b font-s13">
                                    <tbody>

{@eq key=payslips.length value=0}
<div class=""> <div class="block-content block-content-full text-center push-50-t">
              <p >No Data Found</p>

                    
                </div><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>
{:else}

{#payslips}
                                        <tr>
                                            <td class="font-w600">
                                                <a href="javascript:void(0)" tabindex="-1">{particular}</a>
                                            </td>
                                            <td class="font-w600 text-success text-right"><i class="fa fa-inr">&nbsp;</i>{netSal}</td>
                                            <td class="hidden-xs text-muted text-center">
												<a href="<?php echo str_replace('/b/',"/emp/{url}" , myUrl('',true));?>" tabindex="-1"><i class="si si-eye"></i></a>
                                            </td>

                                        </tr>
{/payslips}

  
                                    </tbody>
                                </table>
                                </div>
                 </div>

                 <div class="block-content block-content-full block-content-mini bg-gray-lighter text-white text-center dashboard-sticky-bottom">
  <a href="<?php echo str_replace('/b/',"/emp/paySlips.php" , myUrl('',true));?>" target="_block">  View All <i class="fa fa-arrow-right text-black-op"></i></a>
                </div>
{/eq}

                          
{/mypay}       
</script>
            

                
 
	
 <script type="text/x-template" id="emp-policy">              
                <div class="block-content ">
                
                <div class="pull-t pull-r-l">
                    <table class="table remove-margin-b font-s13 ">
                                    <tbody>
{@eq key=policies.length value=0}
<div class=""> <div class="block-content block-content-full text-center push-50-t">
              <p >No Data Found</p>
                    
                </div><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>
{:else}
                                {#policies}       
										 <tr>
                                            <td class="font-w600">
                                                <a href="javascript:void(0)" tabindex="-1">{title}</a>
                                            </td>
                                            <td class="hidden-xs text-muted text-center">
                                            	<a href="javascript:void(0)" tabindex="-1"><i class="si si-eye"></i></a>
                                            </td>
                                            
                                        </tr>
                                        
{/policies}        
                                 

                                    </tbody>
                                </table>
                                </div>
                               
                </div>
                 <div class="block-content block-content-full block-content-mini bg-gray-lighter text-white text-center dashboard-sticky-bottom">
  <a href="#" target="_block">  View All <i class="fa fa-arrow-right text-black-op"></i></a>
                </div>
{/eq}    
            
</script>   


<section>

<div class="col-lg-12">
		<div class="block block-themed" id="attendance-template">
	
	<div class="block-header bg-default">
	
                
                 <ul class="block-options push-5 md-margin">
                    
                          <li>
                          <form class="md-push-5">
                          
                              <input class="for" type="hidden" name="for" id="for"  value='<?php if(isset($for)) echo $for?>'>
                          <?php if($_SESSION['authprivilage']=="employee"){?>
                            	<input type="hidden" name="empName" id="employeeName"  value="<?php echo $_SESSION['employee_name']?>" >
                           <?php }else{?> 
                           		<input type="hidden" name="empName" id="employeeName"  value="<?php echo $_SESSION['login_id']?>" >
                           <?php }?>
                               <div class="input-group col-xs-6 datetimepicker input-group input-group date "  style="width: 240px;">
                                    <input class="form-control js-datetimepicker" data-format="MMM-YYYY" id="calender" name="calender"  placeholder="Select month to view.."  value="<?php if(isset($for)) echo date('M-Y',strtotime($selectedMonth))?>" type="text">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                            </div>
                       
                       
                           
                        </form>
                        </li>
                        <li class="pull-right push-5-l" style="margin-top: 7px !important;">
                            <button id="refresh" type="button"  class=""><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    
                    <h4 class="block-title">Attendance Details</h4>
	
	 
                   
                   
                </div>
<div class="block-content text-center">
     <div id="attendances">
	     </div>
</div>
</div>
</div>
</section>


                
<!-- leave section starts here -->

<section>
<div class="row ">
<div class="col-lg-6 col-sm-6 ">
<div class="col-sm-12">
 <div class="block block-themed" id="status-template" style="height: 330px !important">
 <div class="block-header bg-info">
                    <ul class="block-options">
                        <li>
                            <button type="button" class="status" data-value="status" ><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Leave Status</h3>
                </div>
<div id="lstatus">
</div>
</div>
</div>
</div>

<div class="col-lg-6 col-sm-6 ">
<div class="col-sm-12">
 <div class="block block-themed" id="balance-template" style="height: 330px !important">
 <div class="block-header bg-info">
 
                    <ul class="block-options">
                    
                        <li>
                            <button type="button" class="balance" data-value="balance"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Leave Balance -<?php echo $year?></h3>
                </div>
<div id="lbalance">
</div>
</div>
</div>

</div>
</div>
</section>
<!-- leave section ends here -->

  
  
  <script type="text/x-template" id="leave-status">

    
        
                
            <div class="block-content">
{@eq key=leaveStatus.length value=0}
<div class="pull-t pull-r-l"><div class=""> <div class="block-content block-content-full text-center push-50-t"><p>No Data Found</p></div></div><table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>       </div>
</div>
{:else}
            <div class="pull-t pull-r-l">
    <table class="table remove-margin-b font-s13 table-striped">
                                    <tbody>
{#leaveStatus}
                                                                            <tr>
                                            <td class="font-w600">
										{@eq key=start value=end}
											<h5 class="font-w300 text-primary">{start} {@eq key=print_half value="1"}{from_half}{/eq}</h5>
										{:else}
											<h5 class="font-w300 text-primary">
													{start} 
													{@eq key=print_half value="1"}
														{from_half}
													{/eq} - 
													{end} 
													{@eq key=print_half value="1"}
														{to_half}
													{/eq}
											</h5>
										{/eq}
										

                                                <span class="label label-info">{leave_type}</span>
                                                <div class="text-muted"><small><?php echo substr('{reason}',0,54);?></small></div>
                                            </td>
                                            <td class="hidden-xs text-muted text-right" style="width: 70px;"><div style="" class="col-sm-5 col-md-2 text-left">
                        <span class="h5">{duration}</span>
                        <small>Day(s)</small>
                    </div></td>

                    <td>

                    {@select key=status}
							{@eq value="W"}	
								<span class="label label-danger">Withdrawed</span>
								{:else}
								{@eq value="C"}
								<span class="label label-danger">Cancelled</span>
								{:else}
								{@eq value="RQ"}
								<span class="label label-warning" >Pending</span>
								{:else}
								{@eq value="R"}
								<a tabindex="0" class="label label-danger approved" data-toggle="popover" data-trigger="focus" role="button" title="Remarks" data-placement="left" data-content={admin_remarks}  data-original-title="Remarks">Rejected</a>
								{:else}	
								{@eq value="A"}
								<a tabindex="0" class="label label-success approved" role="button" data-toggle="popover" data-trigger="focus" title="" data-placement="left" data-content="Approved" data-original-title="Remarks">Approved</a>
								{:else}
								<span class="text-normal">{earlyout}</span>
								{/eq}	
								{/eq}
								
								{/eq}
								{/eq}
								{/eq}
								{/select}
                    </td>
                                        </tr>
{/leaveStatus}
                                                                           </tbody>
                                </table>    </div>
                    
                    <div class="block-content block-content-full block-content-mini  text-white text-center dashboard-sticky-bottom">
  <a href="<?php echo myUrl('leaveRequests')?>" target="_block">  View All Requests <i class="fa fa-arrow-right text-black-op"></i></a>
                </div>
        {/eq}    
                
</script>  

 
          <script type="text/x-template" id="leave-balance">      
              <div class="block-content">  
{@eq key=leaveBalance.length value=0}
<div class="pull-t pull-r-l"><div class=""> <div class="block-content block-content-full text-center push-50-t"><p>No Data Found</p></div></div><table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>       </div>
</div>
{:else}
</div>

{:else}
           
{#leaveBalance}
<div class="col-lg-4">
            <div class="block block-themed block-bordered">

                <div class="block-content block-content-full text-center">

                    <div class="h3 push-5">{leave_rule_id}</div>
                    
                </div>
                <div class="block-content border-t">
                    <div class="row items-push text-center">
                        <div class="col-xs-6 border-r">
                            <div class="push-5">{availed}</div>
                            <div class="h5 font-w300 text-muted">Availed</div>
                        </div>
                        <div class="col-xs-6">
                            <div class="push-5">{balance}</div>
                            <div class="h5 font-w300 text-muted">Balance</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
{/leaveBalance}
		

		
                    </div>

                    <div class="block-content block-content-full block-content-mini  text-white text-center dashboard-sticky-bottom">
  <a href="<?php echo myUrl('leaveRequests/raise')?>" target="_block">  Apply a Leave <i class="fa fa-plus text-black-op"></i></a>
                </div>
{/eq}                
  

  
</script>
<!-- leave section ends here -->
<input type="hidden" id="checkTax" value="<?php echo $taxYears?>"  >
<?php if($taxYears){?>
<section>
<!-- income tax starts here--> 
<div class="col-lg-12">
<div class="block block-themed" id="income-tax">
		<div class="block-header bg-info">
	 <span class="block-title">Income Tax</span>
	 
                    <ul class="block-options push-5 md-margin">
                    <li>
                          <form>
<input class="financialYear" type="hidden" value='<?php echo $_SESSION['financialYear']?>'>
                          <div class="col-sm-12">
<select class="form-control input-group input-group date taxwidget-year">

									<?php foreach ($taxYears as $taxyear){?>
                                    <option value="<?php echo $taxyear['year']?>"><?php echo $taxyear['year']?></option>
                                    <?php }?>
                                </select>
						</div>
                        </form>
                        </li>
                       <li class="pull-right push-5-l" style="margin-top:7px !important">
                            <button id="tax_data" type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo" ><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                </div>
                  <div class="block-content" id="tax">
                   </div>
</div> 
</div>
</section>
<?php }?>
 <script type="text/x-template" id="tax-view">
<div class="row push-10">
<div class="col-lg-6 col-sm-6  border-r">
<div class="h5 text-uppercase text-center push-5-t push-10 font-w700">Tax Saving Insights</div>
{#tax}
{@lte key=$idx value=2}
<div class="col-xs-6 col-sm-4 text-center" value={$idx}>
<div class="chart pie-chart push-50-t" data-percent="{value3}" data-line-width="3" data-size="100" data-bar-color="#faad7d" data-track-color="#eeeeee" data-scale-color="#dddddd"><span>{value3}%</span></div>

<div class="push-10-t">
<span><i class="fa fa-inr"></i>{value2} <br><small class="text-muted">/<i class="fa fa-inr"></i>{value1}</small></span>
<div class="font-w600 push-5">{ded}</div>
</div>
</div>
{/lte}
{/tax}

</div>
<div class="col-lg-6 col-sm-6 ">
<div class="h5 text-uppercase text-center push-5-t push-10 font-w700">Tax Payable vs Tax Paid</div>
<div class="chartjs-wrapper">
<div style="height: 330px;">
<canvas id="tax-donut" class="js-chartjs-donut"></canvas>
</div>
</div>
<!-- <div class="row items-push" >
{#tax}
{@lte key=$idx value=0}
                        <div class="col-xs-3" value={$idx}>
                            <div class="font-w300 h4">2017-18</div><div class="text-muted"><small><i class="si si-calendar"></i> This Year</small></div>
                            
                        </div>
<div class="col-xs-3">
                            <div class="font-w300 h4"><i class="fa fa-inr">&nbsp;</i>1,66,199</div><div class="text-muted"><small> Total Tax</small></div>
                            
                        </div>
<div class="col-xs-3">
                            <div class="font-w300 h4 text-success"><i class="fa fa-inr">&nbsp;</i>66,000</div><div class="text-muted"><small> Paid</small></div>
                            
                        </div>
<div class="col-xs-3">
                            <div class="font-w300 h4 text-danger"><i class="fa fa-inr">&nbsp;</i>1,00,199</div><div class="text-muted"><small> Yet to Pay</small></div>
                            
                        </div>
{/lte}                        
{/tax}                        
                    </div>-->
</div>

</div>
</script>    

<!-- income tax ends here -->
		      <script type="text/x-template" id="attendance-view">
 
                   <table class="table table-condensed myattendance-dataTable no-footer cell-border table-hover responsive " id="view" style=";" >

                        <thead id="attendance-head">
                            
                                
                               <tr>
                                
                                
                                <th class="text-left" style="width: 200px;">Date</th>
								<th class="text-left" style="width: 150px;">Shift</th>
                                <th class="text-left" style="width: 150px;">Team Name</th>
                                <th class="text-left" style="width: 100px;">Checkin</th>
                                
<th class="text-left" style="width: 100px;">Checkout</th>
<th class="text-left word-wrap" style="word-wrap:break-word">All Punches</th>
<th class="text-left" style="width: 100px;">Late</th>
<th class="text-left" style="width: 140px;">Early Out</th>
<th class="text-left" style="width: 100px;">Total</th>

                            </tr>
                            

                        </thead>
                        <tbody>
{#total}   
<div class="pull-left col-xs-12 col-md-4"><div class="row items-push">
<div class="col-xs-12 text-left" style="padding-left: 0px;">
{@select key=total_time}
{@ne value=Null type="boolean"}
<div class="h4 font-w300"><i class="si si-clock h4">&nbsp;</i>{total_time} hrs
</div>
<div class="text-muted h5 font-w300 text-danger">Total late for this payroll month(Late+Early Out)
</div>
{/ne}
{/select}
</div>
</div>
</div>
{/total}

<div class="pull-right"><div class="row items-push"><div class="text-left">
<a class="text-muted h5 font-w300 text-danger" href="<?php echo myUrl('attendance')?>" target="_blank">
<i class="fa fa-arrow-right text-black-op"></i>&nbsp;Click here to View your day wise attendance 
</a></div></div></div>   
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
<td class="text-left total" id="total">{total_time}</td>
								</td>
								

                            </tr>
	
           
					{/attendances}                            
                                                      
                                                        
                                                    </tbody>

                  
                                      




</table>

<div class="row" style="margin-top: 20px;"><div class="pull-left col-lg-4 col-xs-12 col-md-4"><div style="padding-left: 0px;" class="col-xs-12  text-left"><span class="h5 font-w300"><i class="fa fa-circle text-success">&nbsp;</i>In Punch</span>&nbsp;&nbsp;<span class="h5 font-w300"><i class="fa fa-circle text-danger">&nbsp;</i>Out Punch</span>&nbsp;&nbsp;<span class="h5 font-w300"><i class="fa fa-check text-success">&nbsp;</i>Allowed Late/EarlyOut</span></div></div>

<div class="pull-right col-lg-7 col-xs-12 col-md-4">
<p class="h5 font-w300 text-right">If days are missing in the above you can select the date in the whats happening widget (found above) to see the day status for the same</p>
</div>


</script>
<!-- birthday widgets start -->
     
     <section>


  <div class="row push-10">
<div class="col-lg-12">

<div class="block-content" style="padding-top:0px">
<div class="block" id="tab-info">
                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                    <li class="active">
                        <a href="" data-target="#birthdays"><i class="fa fa-gift"></i> Birthdays</a>
                    </li>
                    <li class="">
                        <a href="" data-target="#anniversary"><i class="fa  fa-flag-checkered "></i> Anniversary</a>
                    </li>
                    <li class="">
                        <a href="" data-target="#holidays"><i class="fa fa-calendar"></i> Holidays</a>
                    </li>
                </ul>
                <div id="info">
     </div>
<script type="text/x-template" id="info-dashboard">                

                <div class="block-content tab-content">
					<div class="tab-pane active" id="birthdays">
{@eq key=birthdays.length value=0}
<p class="text-center"> No Birthdays found</p>
{/eq}
					{#birthdays}
                        <div class="col-sm-6 col-md-4 col-lg-3">
{@select key=employee_email}
				{@eq value="Nil"}
		<a class="block block-rounded block-link-hover3 block-bordered" href="javascript:void(0)">
{:else}
<a class="block block-rounded block-link-hover3 block-bordered" href="mailto:{employee_email}">
{/eq}
{/select}

                				<div style="padding:8px" class="block-content-full clearfix">
                    				<div class="pull-left push-5-r">
  										{@select key=employee_image}
				{@eq value="Nil"}
				{@select key=employee_gender}
					{@eq value="Female"}
	                    <img class="img-avatar" src="<?php echo str_replace('/b/',"/img/default-avatar_women.png" , myUrl('',true));?>" alt="">
					{:else}
						<img class="img-avatar" src="<?php echo str_replace('/b/',"/img/default-avatar_men.png" , myUrl('',true));?>" alt="">
					{/eq}
				{/select}
				{:else}
					<img class="img-avatar" src="<?php echo str_replace('/b/',"{employee_image}" , myUrl('',true));?>" alt="">
				{/eq}
				{/select}
                    				</div>
                    				<div class="pull-left">
                        				<div class="font-w600 push-5">{employee_name}</div>
                        				<div class="text-muted">{employee_dob}</div>
                        				<div class="font-w600 text-info"><i class="fa fa-envelope "></i></div>
                    				</div>
                				</div>
             				</a>
						</div>
					{/birthdays} 
        			</div>
                    <div class="tab-pane" id="anniversary" data=anniversary.length>
{@eq key=anniversary.length value=0}
<p class="text-center"> No Anniversary found</p>
{/eq}
{#anniversary}
                        <div class="col-sm-6 col-md-4 col-lg-3" >
            <a class="block block-rounded block-link-hover3 block-bordered" href="mailto:{employee_email}">
            
                <div style="padding:8px" class="block-content-full clearfix">
                    <div class="pull-left push-5-r">
  
                        {@select key=employee_image}
				{@eq value="Nil"}
				{@select key=employee_gender}
					{@eq value="Female"}
	                    <img class="img-avatar" src="<?php echo str_replace('/b/',"/img/default-avatar_women.png" , myUrl('',true));?>" alt="">
					{:else}
						<img class="img-avatar" src="<?php echo str_replace('/b/',"/img/default-avatar_men.png" , myUrl('',true));?>" alt="">
					{/eq}
				{/select}
				{:else}
					<img class="img-avatar" src="<?php echo str_replace('/b/',"{employee_image}" , myUrl('',true));?>" alt="">
				{/eq}
				{/select}
  
  
                        
                    </div>
                    <div class="pull-left">
                        <div class="font-w600 push-5">{employee_name}</div>
                        <div class="text-muted">{doj}-{years} Yr(s)</div>
                        <div class="font-w600 text-info"><i class="fa fa-envelope "></i></div>
                    </div>
                </div>
            
            </a>
</div>
{/anniversary}
</div>
                    <div class="tab-pane" id="holidays">
{@eq key=holidays.length value=0}
<p class="text-center"> No Holidays found</p>
{:else}
                        <div class="block-content"> 
                        <div class="col-lg-6"> 

            <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-left" style="width: 50px;">Holidays</th>
                                <th class="text-left" style="width: 100px;">Date</th>
                              

                            </tr>
                        </thead>
                        <tbody>

{#holidays}
                           <tr>
                               
                                <td class="text-left">{title}</td>
                               <td class="text-left">
                        {sdate}      </td>

                            </tr>
{/holidays}                      
                     </tbody>
                    </table>
{/eq}

                    </div>
                    </div>
                    </div>
                </div>

  </script>          
  </div>
</div>

</div>
</div>

</section>
<!-- birthday widgets end -->

     
