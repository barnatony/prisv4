<script type="text/x-template" id="msg-tpl">
	{#notes}
		<h3 class="h4 push-10">
		{text} 
		</h3>
	{/notes}
</script>

<!--  myself and whats happening section starts here  -->

<section style="padding-top: 19px;">
    <div class="col-lg-12">
		<div id="empdashboard"></div>
	</div>
	<section class="content">
		<div class="row">
			<!-- <p><i class="fa fa-gift"></i> &nbsp;Happy birthday Naresh, &nbsp;<a class="alert-link" href="javascript:void(0)">VDart Group wishes you to have a great year ahead..!! <i class="si  si-emoticon-smile text-warning"></i></a></p> -->
			<div id="wish"></div>
		</div>
	</section>
	
	<div class="col-lg-12 push-15-t">
		<div class="row">
			<div class="col-lg-6 col-sm-6 a1">  
				<div class="row">
				<!-- myself starts here -->
					<div class="col-lg-6 col-sm-6 col-xs-12 a1">
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
								</ul>
							<h4 class="block-title">My Pay</h4>
							</div>
							<div id="mypay"></div>
						</div>
					</div>
					<div class="col-lg-6 col-sm-6 col-xs-12 a1">
						<div class="block block-themed" id="policies-template" style="height:308px !important">
							<div class="block-header bg-modern">
								<h4 class="block-title">Policies</h4>
							</div>
							<div id="policies"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-sm-6 col-xs-12">
				<div class="block block-themed"  id="today_view">
					<div class="block-header bg-default">
						<div class="col-md-6 col-xs-12 rects">
							<h4 class="block-title rect_h">What's Happening</h4>
						</div>
						<div class="col-md-6 col-xs-12 rect_h1">
							<ul class="block-options push-5 md-margin">
										<li>
											<form class="md-push-5">
												<div class="input-group col-xs-6 dashboard-date input-group input-group date " style="width: 100%;">
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
						</div>
					</div>
					<div id="today"></div>
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
	<div class="block-content" style="height: 253px !important;">
		<!-- Today Status -->
		{@eq key=today.length value=0 }
		<div class="pull-t pull-r-l">
			<div class="">
				<div class="block-content block-content-full text-center push-100-t"><p>No Data Found. Either you are absent for the day or the data might be removed</p></div>
			</div>
			<table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody></tbody></table>
		</div>
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
            </li>
		{/today}
		</ul>
	</div>
</script>

<!-- myself and whats happening section ends heres  -->

<script type="text/x-template" id="emp-dashboard">
<div class="content_head bg-default" style="padding: 10px;">                   
   <div class="clearfix">
       <div class=" col-lg-6 opt" >
			<div class="block block-rounded animated fadeIn" style="padding: 13px;margin: 10px 0px;" id="emp-template" href="<?php echo str_replace('/b/',"{employee_image}" , myUrl('',true));?>">
				<div class="block-content block-content-full clearfix">
				{#myself}
					<div class="col-lg-12 paper">
						<div class="row">
							<div class="pull-left col-md-2 col-sm-12 col-xs-12 paper">
								{@select key=employee_image}
									{@eq value="Nil"}
									{@select key=employee_gender}
										{@eq value="Female"}
											<img class="img-avatar img-avatar96" src="<?php echo str_replace('/b/',"/img/default-avatar_women.png" , myUrl('',true));?>" alt="Girl">
										{:else}
											<img class="img-avatar img-avatar96" src="<?php echo str_replace('/b/',"/img/default-avatar_men.png" , myUrl('',true));?>" alt="Male">
										{/eq}
									{/select}
									{:else}
										<img class="img-avatar img-avatar96" src="<?php echo str_replace('/b/',"{employee_image}" , myUrl('',true));?>" alt="Me">
									{/eq}
								{/select}
							</div>
							<div class="col-md-7 col-sm-12 col-xs-12 paper">
								<div class="h3 font-w600 push-5"><a class="block block-rounded block-link-hover3" id="emp-template" href="<?php echo str_replace('/b/',"/emp/empProfileView.php" , myUrl('',true));?>">{employee_name} - {employee_id}</a></div>
								<div style="padding: 5px 0px;"><i class="si si-badge badge1">&nbsp;</i><span class="text-muted">{designation}, {team_name} </span></div>
								<div><i class="si si-pointer badge1">&nbsp;</i><span class="text-muted">{branch} </span></div>
							</div>
							<div class="col-md-3 text-right paper paper0 opt">
								<div class="h3 push-5">{experience|s}</div>
								<div class="h5 font-w300 text-muted p0">Joined on {doj}</div>
							</div>
						</div>
					</div>
				
				{/myself}
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-12 col-xs-12 opt" style="margin: 10px 0px;">
			<div class="block block-rounded animated fadeIn" style="padding: 35px;">
	           	<div id="payout-widget" class="carousel slide auto panel-body">
					<!-- 	<ol class="carousel-indicators out" >
					<li class="" data-slide-to="0" data-target="#payout-widget"></li>
					<li class="active" data-slide-to="1" data-target="#payout-widget"></li>
					</ol> -->
					<div class="carousel-inner">
						<div class="item  text-center active">
							<h3 class="text-muted "><i class="fa fa-inr">&nbsp;24000</i></h3>
							<h4 class="text-gray ">GrossPay(Dec)</h4>
						</div>
						<!-- payout values-->
						<div class="item text-center">
							<h3 class="text-muted "><i class="fa fa-inr">&nbsp;23545</i></h3>
							<h4 class="text-gray ">NetPay(Dec)</h4>
						</div>
					</div>
				</div>
	        </div>	
        </div>
        <div class="col-lg-3 col-sm-12 col-xs-12 opt" style="margin: 8px 0px;">
			<div class="block block-rounded animated fadeIn rect0">
	            <div class="row">
                    <div class="dayto">
	                  	<i class="si si-clock text-muted o1 h2"></i> <a class="text-muted  animated zoomIn h3" id="work_hrs">EXPIRED</a>
              			<h2 class="h5 text-muted  animated zoomIn" id="date">Wed 04 th April</h2>
               			<p class="h5 push-5-t o1 text-muted  animated zoomIn" id="start-hrs">Today Starts at 10.37</p>
							<div class="rect col-md-12" style="padding-top: 5px;">
								<div class="col-md-4 col-xs-6 col-xm-12 rect1">
									<a class="btn btn-default btn-xs" href="javascript:void(0)" style="padding:7px;">
										<i class="fa fa-coffee push-5-r"></i> Coffee
                                    </a>
                                </div>
                                <div class="col-md-4 col-xs-6 col-xm-12 rect1">
                                    <a class="btn btn-default btn-xs" href="javascript:void(0)" style="padding:7px;">
                                        <i class="fa fa-cutlery push-5-r"></i> Lunch
                                    </a>
                                </div>
								<div class="col-md-4 col-xs-6 col-xm-12 rect1">
									<a class="btn btn-default btn-xs" href="javascript:void(0)" style="padding:7px;">
										<i class="fa fa-sign-out push-5-r"></i> Clock out
                                    </a>
                                </div>
                            </div>
                    </div>
                </div>
	        </div>	
        </div>
	</div>
</div>
</script>
	
<script type="text/x-template" id="my-pay">                
{#mypay}
	{#salary}	
	{@select key=total_pay}
	{@ne value="" type="boolean"}
		<div class="block-content bg-gray-lighter">
			<div class="row items-push">
				<div class="col-xs-4">
				<div class="text-muted"><small><i class="si si-calendar"></i> Year</small></div>
				<div class="font-w600">{fyear}</div>
				</div>
				<div class="col-xs-8">
					<div class="h3 font-w300 text-right">
						<i class="fa fa-inr">&nbsp;</i>{total_pay}
					</div>
					<div class="text-muted h6 font-w300 text-right">{val}</div>
				</div>
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
				</div>
				<table class="table remove-margin-b font-s13"><tbody></tbody></table>
				{:else}
				{#payslips}
					<tr>
						<td class="font-w600"><a href="javascript:void(0)" tabindex="-1">{particular}</a></td>
						<td class="font-w600 text-success text-right"><i class="fa fa-inr">&nbsp;</i>{netSal}</td>
						<td class="hidden-xs text-muted text-center"><a href="<?php echo str_replace('/b/',"/emp/{url}" , myUrl('',true));?>" tabindex="-1"><i class="si si-eye"></i></a></td>
					</tr>
				{/payslips}
			    </div>
				{/eq}
            </tbody>
        </table>
    </div>
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
                </div>
				<table class="table remove-margin-b font-s13"><tbody>       </tbody></table>
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
				{/eq}
				</div>
				</tbody>
            </table>
        </div>
    </div>                
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
	<div class="row">
		<div class="col-lg-12 col-sm-12 ">
			<div class="col-lg-6 col-sm-6 ">
				<div class="block block-themed " id="status-template" style="height: 282px !important">
					<div class="block-header bg-info">
						<ul class="block-options">
							<li></li>
						</ul>
						<h3 class="block-title">Leave Status</h3>
					</div>
					<div id="lstatus">
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-sm-6 ">
				<div class="">
					<div class="block block-themed" id="balance-template" style="height: auto !important">
						<div class="block-header bg-info">
							<ul class="block-options">
							</ul>
							<h3 class="block-title">Leave Balance -<?php echo $year?></h3>
						</div>
						<div id="lbalance">
						</div>
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
		<div class="pull-t pull-r-l"><div class=""> <div class="block-content block-content-full text-center push-50-t"><p>No Data Found</p></div></div>
			<table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody></tbody></table>
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
						<div class="text-muted"><small><?php echo substr('{reason}',0,54);?></small></div>
					</td>
					<td>
						<span class="label label-info">{leave_type}</span>
					</td>
					<td class="hidden-xs text-muted text-right" style="width: 70px;">
						<div style="" class="col-sm-5 col-md-2 text-left">
							<span class="h5">{duration}</span>
							<small>Day(s)</small>
						</div>
					</td>
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
		</table>
	</div>
	<div class="block-content block-content-full block-content-mini  text-white text-center dashboard-sticky-bottom">
	  <a href="<?php echo myUrl('leaveRequests')?>" target="_block"> View All Requests <i class="fa fa-arrow-right text-black-op"></i></a>
	</div>
	{/eq}
	</div>	
</script>  

 
<script type="text/x-template" id="leave-balance">      
<div class="block-content">  
	{@eq key=leaveBalance.length value=0}
		<div class="pull-t pull-r-l"><div class=""><div class="block-content block-content-full text-center push-50-t"><p>No Data Found</p></div></div>
			<table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody></tbody></table>
		</div>
		{:else}
		<div>
			{#leaveBalance}
				<div class="col-lg-4 col-xs-12">
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
</div>
</script>
<!-- leave section ends here -->

<!-- income tax starts here-->
<input type="hidden" id="checkTax" value="<?php echo $taxYears?>">
<?php if($taxYears){?>
<section>
	<div class="row">
		<div class="col-lg-6 col-sm-12 col-xs-12">
			<div class="block block-themed pull-t" id="income-tax">
				<div class="block-header bg-info">
					<span class="block-title">Income Tax</span>
						<ul class="block-options push-5 md-margin">
							<li>
								<form>
									<input class="financialYear" type="hidden" value="<?php echo $_SESSION['financialYear']?>">
									<div class="col-lg-12 col-sm-12">
										<select class="form-control input-group input-group date taxwidget-year">
										<?php foreach ($taxYears as $taxyear){?>
										<option value="<?php echo $taxyear['year']?>"><?php echo $taxyear['year']?></option>
										<?php }?>
										</select>
									</div>
								</form>
							</li>
						</ul>
				</div>
				<div class="block-content" id="tax"></div>
			</div>
		</div>
		<div class="col-lg-6 col-sm-12 col-xs-12">
			<div class="block block-themed float-l-10 pull-t">
				<div class="block-header bg-info">
					<span class="block-title">Tax Deductable</span>
				</div>
				<div id="tax-donut" style=" width: 100%;height: 300px;text-align: center;"></div>
			</div>
		</div> 
	</div>
</section>
<?php }?>
<script type="text/x-template" id="tax-view">
	<div class="row push-10">
		<div class="col-lg-12 col-sm-12">
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
	</div>
</script>
<!-- income tax ends here -->

<script type="text/x-template" id="attendance-view">
	<table class="table table-bordered table-striped js-dataTable-full dataTable no-footer responsive " id="view" style=";" >
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
			<div class="pull-right"><div class="row items-push"><div class="text-left">
				<a class="text-muted h5 font-w300 text-danger" href="<?php echo myUrl('attendance')?>" target="_blank">
					<i class="fa fa-arrow-right text-black-op"></i>&nbsp;Click here to View your day wise attendance 
				</a></div></div>
			</div>   
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
					{/select}
				<td class="text-left total" id="total">{total_time}</td>
				</td>
            </tr>
			{/attendances}                     
        </tbody>
	</table>
	<div class="row" style="margin-top: 20px;">
		<div class="pull-left col-lg-4 col-xs-12 col-md-4">
			<div style="padding-left: 0px;" class="col-xs-12  text-left">
				<span class="h5 font-w300"><i class="fa fa-circle text-success">&nbsp;</i>In Punch</span>&nbsp;&nbsp;
				<span class="h5 font-w300"><i class="fa fa-circle text-danger">&nbsp;</i>Out Punch</span>&nbsp;&nbsp;
				<span class="h5 font-w300"><i class="fa fa-check text-success">&nbsp;</i>Allowed Late/EarlyOut</span>
			</div>
		</div>
		<div class="pull-right col-lg-7 col-xs-12 col-md-4">
			<p class="h5 font-w300 text-right"> If days are missing in the above you can select the date in the whats happening widget (found above) to see the day status for the same</p>
		</div>
	</div>
</script>