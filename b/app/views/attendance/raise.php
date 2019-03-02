<?php if(isset($_SESSION['authprivilage']) && $_SESSION['authprivilage']!="hr"){?>

<section class="content content-boxed content-full sidebar-l sidebar-o side-scroll sidebar-mini header-navbar-fixed">
<div class="content content-boxed">
	<!-- <div class="block row box-shaddow" id="respondFormBlock">
	
	</div> -->
<div class="bg-white">
<div class="block-header bg-gray-lighter">
				<span><b>Raise Regularization Request</b></span>
				<ul class="block-options push-5 md-margin">
                    <li><a href="<?php echo myUrl("attendance/regularization")?>" data-toggle="tooltip" title="" data-original-title="Back to Attendance Regularization"><i class="si si-arrow-left fa-2x"></i></a>
                    </li>
                    </ul>
			</div>
	<div class="alert alert-info alert-dismissable" style="color: #3a87ad;background-color: #d9edf7;margin:0px;">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
             <p><i class="fa fa-caret-right"></i>&nbsp; You can regulate your attendance by submiting the requests.</p>
			 <p><i class="fa fa-caret-right"></i>&nbsp; If you've found attendance probelms due to "Shift Mismatch" raise a single request on it and wait for the Approval of HR/Admin before raising other.</p>
			 <p class=""><i class="fa fa-caret-right"></i>&nbsp;  For an approved regularatization request the salary will not get affected.</p>
			 <p><i class="fa fa-caret-right"></i>&nbsp; Personal/Other reasons are  not encouraged and will considered as Late/Early Hours.</p>
			<p><i class="fa fa-caret-right"></i>&nbsp; Permission, Cab Late, Shift mismatch will be processed by HR. You can contact the respective HR for Approval for the same.</p>
    </div>
    <div class="col-lg-4 col-sm-4 push-10-t" style="padding:10px 0px 0px;">
       <select id="regul_type" class="form-control">
           <option value="" selected>Choose type here...</option>
           <option value="In Punch">In Punch</option>
           <option value="Out Punch">Out Punch</option>
           <option value="Shift Mismatch">Shift Mismatch</option>	
           <option value="Cab Late">Cab Late</option>
           <option value="Personal">Personal</option>
           <option value="Permission">Permission</option>
           <option value="Others">Others</option>
       </select>
    </div>
    <div class="col-lg-4 col-sm-4 push-10-t" style="padding:10px 10px 0px;">
      <div class="current-month-date input-group input-group date " id="current-month-date">
                                    <input class="form-control dashboard-datetimepicker" data-format="DD-MMM-YYYY" id="dashboard-calender" name="dashboard_calender"  placeholder="Select date to view.."  value="" type="text">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
      </div>
    </div>
	<div class="regularization-scroll push-20 border side-scroll">
		<div class="col-sm-12 push-20-t push-50 table-responsive slimScrollDiv" id="regularization_view" style="padding: 0px;height:700px;overflow-y:scroll;">
		</div>
	</div>
</div>
</div>
</section>	      
<?php }?>

<script type="text/x-template" id="attendance-reg">
<!--<select id="regul_type" class="form-control">
  {#type}
    <option value="{type}"{@eq key=type value=type}  selected="true"{/eq} >
     {value}
    </option>
  {/type}
</select>-->

<table class="table regularization-view dataTable remove-margin-b font-s13 table-striped table-condensed side-scroll"  style="overflow:scroll;min-height: 50vh;">
<tbody>
	{@eq key=regularization.length value=0}
 		<p class="h4 text-center bg-white "> No Record Found</p>
	<hr>
{/eq}
{#regularization}             
           <tr>
		        <td  key={regularization.length}>
                           		<h5 class="font-w300 text-primary">
                                              {Date_Formatted}  
                           		</h5>

										{@select key=Type}
                                            {@eq value="Late"}<span class="label label-primary">{Type}</span>
										{:else}
											 {@eq value="Incorrect"}<span class="label label-info">{Type}</span>
										{:else}
											{@eq value="EarlyOut"}<span class="label label-warning">{Type}</span>{/eq}	
										{/eq}
										{/eq}
										{/select}
                                                <div class="text-muted word-wrap-txt"><b>{notes}</b></div>
                                            </td>
{@select key=res}
{@eq value="Applicable"}
                                            <td class="text-muted text-right" style="width: 200px;vertical-align: middle;">
<div style="" class="text-left">
  <input name="day" id="day" type="hidden" value={dates}>
  <input name="type" id="type" type="hidden" value={Type}>

                        {@select key=Type}
<div class="form-group">
                            <div class="">
<div class="form-material floating">
                                    <select class="form-control" id="reg_type" name="reg_type" size="1">
                        <option value="">Select an Option</option>
						{@eq value="Incorrect"}
                        <option value="In Punch">In Punch</option>
                        <option value="Out Punch">Out Punch</option>
						<option value="Shift Mismatch">Shift Mismatch</option>
						{:else}
 						{@eq value="Late"}
                        <option value="Cab Late">Cab Late</option>
                        <option value="Personal">Personal</option>
						<option value="Permission">Permission</option>
						<option value="Shift Mismatch">Shift Mismatch</option>
						{:else}
						{@eq value="EarlyOut"}
                        <option value="Permission">Permission</option>
						<option value="Others">Others</option>
						<option value="Shift Mismatch">Shift Mismatch</option>
                        {/eq}
						{/eq}
						{/eq}
                       </select>
                                </div>
</div></div>
						{/select}
                    </div>
</td>
                    <td style="vertical-align: middle;">
<div class="form-group">
<div class="col-sm-12">
<div class="form-material floating  form-material-primary">
					 {@select key=Type}
                    {@eq value="Incorrect"}<textarea rows="2" id="reason" name="reason" cols="" class="form-control" maxlength="60" required placeholder="Specify a Reason like 'I forgot to keep outpunch @ 21:30 '"></textarea>
					{:else}
					{@eq value="Late"}<textarea rows="2" id="reason" name="reason" class="form-control" maxlength="60" cols="" required placeholder="Specify a Reason like 'Late due to traffic '"></textarea>
					{:else}
					{@eq value="EarlyOut"}<textarea rows="2" class="form-control" id="reason" name="reason" maxlength="60" cols="" required placeholder="Specify a Reason like 'Had an appointment'"></textarea>
					{/eq}
					{/eq}
					{/eq}	
				{/select}
</div></div></div>
                    </td>
                                            <td class="text-success text-right" style="width: 70px;vertical-align: middle;">
                                            <button class="btn btn-rounded btn-sm btn-success-outline btn-success push-5-r push-10 apply"  type="button" title="Raise Regularization Request"><i class="fa fa-check"></i></button>
                                            </td>
</tr>
{:else}
<td colspan="4">
<div class="">
<h5 class="font-w300 text-primary">Expired</h5>
<div class="text-muted">
Requests of a week will automatically expire after Tuesday of next week.
</div>
</div>
</td>
{/eq}
{/select}
{/regularization}
</tbody>
</table>
</script>

          
 