<script type="text/x-template" id="attendance-reg">
<!--<select id="regul_type" class="form-control">
  {#type}
    <option value="{type}"{@eq key=type value=type}  selected="true"{/eq} >
     {value}
    </option>
  {/type}
</select>-->

<table class="table regularization-view dataTable remove-margin-b font-s13 table-striped table-condensed">
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
					{@eq value="Incorrectpunches"}<span class="label label-info">{Type}</span>
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
							{@eq value="Incorrectpunches"}
							<option value="Missed In Punch">Missed In Punch</option>
							<option value="Missed Out Punch">Missed Out Punch</option>
							<option value="Shift Mismatch">Shift Mismatch</option>{:else}{@eq value="Late"}
							<option value="Cab Late">Cab Late</option>
							<option value="Personal Reasons">Personal Reasons</option>
							<option value="Permission">Permission</option>
							<option value="Shift Mismatch">Shift Mismatch</option>{:else}{@eq value="EarlyOut"}
							<option value="Permission">Permission</option>
							<option value="Others">Others</option>
							<option value="Shift Mismatch">Shift Mismatch</option>
							{/eq}
							{/eq}
							{/eq}
							</select>
                        </div>
					</div>
				</div>
				{/select}
                </div>
			</td>
            <td style="vertical-align: middle;">
				<div class="form-group">
					<div class="col-sm-12">
						<div class="form-material floating  form-material-primary">
						{@select key=Type}
						{@eq value="Incorrectpunches"}<textarea rows="2" id="reason" name="reason" cols="" class="form-control" maxlength="60" required placeholder="Specify a Reason like 'I forgot to keep outpunch @ 21:30 '"></textarea>
						{:else}
						{@eq value="Late"}<textarea rows="2" id="reason" name="reason" class="form-control" maxlength="60" cols="" required placeholder="Specify a Reason like 'Late due to traffic '"></textarea>
						{:else}
						{@eq value="EarlyOut"}<textarea rows="2" class="form-control" id="reason" name="reason" maxlength="60" cols="" required placeholder="Specify a Reason like 'Had an appointment'"></textarea>
						{/eq}
						{/eq}
						{/eq}	
						{/select}
						</div>
					</div>
				</div>
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

        


<section class="content content-boxed content-full">
	<!-- Section Content start here-->
	<div class="content  content-boxed">
		<!-- Striped Table -->
		<div class="bg-white">
			<div class="block-header bg-gray-lighter">
				<span><b>Attendance Regularization Requests</b></span>
				<ul class="block-options push-5 md-margin">
					<li>
						<div class="notification"></div>
						<a data-toggle="tooltip" title="" data-original-title="Regularize Your Attendance" href="<?php echo myUrl('attendance/raise')?>"> <button class="btn btn-default plustwo push-5-r pull-right"><i class="fa fa-plus"></i></button></a>
					</li>
				</ul>
			</div>
			
			<?php if(isset($_SESSION['authprivilage']) && $_SESSION['authprivilage']!="employee"){?>
			<div class="col-sm-12 pull-right"><div class="col-sm-12 col-md-5 pull-right">
                               <form>
                                
                        <div class="input-group col-xs-12">
                        	<div class="input-group-btn search-panel">
                        	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        	
                                <span class="search_concept" data-filter="date">Date</span>
                             </button>
                            </div>
                            
                            <input class="form-control value" value="" placeholder="Search term..." required style="display: none;" type="text">
                            <div class="daterange input-group" style="display: table;">
                                    <input class="form-control daterange1" id="daterange1" name="daterange1" value="" placeholder="From Date" type="text">
                                    <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                    <input class="form-control daterange2" id="daterange2" name="daterange2" placeholder="To Date" type="text">
                                </div>
                              
                            <span class="input-group-btn">
                              <button class="btn btn-default search_activity" type="submit"><span class="fa fa-search"></span></button>
                            </span>
                        </div>
                    </form>
                    </div>
</div>
<?php }?>
			<div class="block-content table-responsive">
				<table class="table  table-striped table-condensed regularization-dataTable no-footer display compact"  style="width:100%;"  data-pagelength=5 data-processing=true data-serverside=true >
                   <thead class="filters">
						<tr>
							<th></th>							
							<th>Employee</th>
							<th>Employee ID</th>
							<th>Day</th>
							<th class="regularization_type">Regularization Type</th>
							<th class="reason_type">Reason Type</th>
							<th class="status">Status</th>
							<th class="actions">Actions</th>
							
						</tr>
					</thead>
						<tfoot>
				<?php if(isset($_SESSION['authprivilage']) && $_SESSION['authprivilage']!="employee"){?>
					<tr id="filter">
					<th class="details-control" rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1">
					<select id="filter_regularization"><option value=""></option>
						<option value="EarlyOut">EarlyOut</option>
						<option value="Incorrectpunches">Incorrectpunches</option>
						<option value="Late">Late</option>
					</select>
					</th>
						<th rowspan="1" colspan="1">
						<select id="filter_reason"><option value=""></option>
						<option value="Cab Late">Cab Late</option>
						<option value="Missed In Punch">Missed In Punch</option>
						<option value="Missed Out Punch">Missed Out Punch</option>
						<option value="Others">Others</option>
						<option value="Permission">Permission</option>
						<option value="Personal Reasons">Personal Reasons</option>
						<option value="Shift Mismatch">Shift Mismatch</option>
						</select></th>
						<th class="status" rowspan="1" colspan="1">
						<select id="filter_status"><option value=""></option>
						<option value="A">Appproved</option>
						<option value="R">Rejected</option>
						<option value="RQ">Pending</option>
						<option value="W">Withdrawed</option>
						</select></th><th rowspan="1" colspan="1"></th></tr>
							</tfoot>
							<?php }?>
				</table>
				



			

		<!-- END Striped Table -->
	
		</div>
	</div>
</section>
<div class="modal in" id="approveReject-modal" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
	<div class="block-content" id="respond_form" style="padding: 0px 20px 1px 0px;">
</div>
</div>
</div>

