<section class="content content-boxed content-full">
	<!-- Section Content start here-->
	<div class="content  content-boxed">
		<!-- Striped Table -->
		
		
		<div class="push-50-t bg-white">
			<div class="block-header">
				<h4>Myteam Regularization Requests</h4>
				<div class="notification"></div>
			</div>
		
			<div class="block-content">
			<input type="hidden" id="privilage" name="privilage" value="<?php echo $privilage?>" >
				<table class="table table-striped table-condensed regularization-dataTable no-footer" id="myteam-regularization-dataTable" data-pagelength=5 data-processing=true data-serverside=true data-ajax="<?php echo myUrl('attendance/dt_regularization/myteam/')?>">
                   <thead>
						<tr>
							<th>employee</th>
                            <th>employee id</th>							
							<th>Day</th>
							<th class="regularization_type">Regularization Type</th>
							<th class="reason_type">Reason Type</th>
							<th class="status">Status</th>
							<th class="actions">Actions</th>
							
						</tr>
					</thead>
					<!-- <tfoot>
					<tr id="filter">
					<th class="details-control" rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1">
						<select id="filter_reason"><option value=""></option>
						<option value="Missed In Punch">Missed In Punch</option>
						<option value="Missed Out Punch">Missed Out Punch</option>
						<option value="Shift Mismatch">Shift Mismatch</option>
						</select></th>
						<th class="status" rowspan="1" colspan="1">
						<select id="filter_status"><option value=""></option>
						<option value="A">A</option>
						<option value="R">R</option>
						<option value="RQ">RQ</option>
						<option value="W">W</option>
						</select></th><th rowspan="1" colspan="1"></th></tr>
							</tfoot> -->
							<tfoot>
							<tr id="filter">
							<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					<th rowspan="1" colspan="1"></th>
					</tr>
							</tfoot>
				</table>
			</div>
		</div>
		<!-- END Striped Table -->
	</div>
</section>
<div class="modal in" id="approveReject-modal" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
	<div class="block-content" id="respond_form" style="padding: 0px 20px 1px 0px;">
</div>
</div>
</div>