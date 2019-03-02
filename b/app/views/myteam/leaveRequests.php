<section class="content content-boxed content-full">
	<!-- Section Content start here-->
	<div class="content  content-boxed">
		<!-- Striped Table -->
		<div class="push-50-t bg-white">
			<div class="block-header">
				<h4>Leave Requests</h4>
				<div class="notification"></div>
			</div>
				
			<div class="block-content">
				<table class="table table-striped table-condensed myteam-dataTable no-footer" id="myteam-datatable" data-pagelength=5 data-processing=true data-serverside=true 	data-ajax="<?php echo myUrl('leaveRequests/dt_leave_requests/myteam/')?>">
                   <thead>
						<tr>
							<th></th>							
							<th>Employee_name</th>
							<th>Employee_id</th>
							<th>FromDate</th>
							<th>FromHalf</th>
							<th>ToDate</th>
							<th>ToHalf</th>
							<th>Duration</th>
							<th class="leave_type">Leave</th>
							<th class="status">Status</th>
							<th class="actions">Actions</th>
						</tr>
					</thead>
						<tfoot>
				
				<!-- <tr id="filter">
							<th></th>							
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="leave_type"></th>
							<th class="status">Status</th>
							<th></th>
						</tr>-->
						
			
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