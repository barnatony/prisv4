<?php 
$leaverules="";
if($leaveAccounts){?>
	<div class="bg-gray-lighters col-md-12" style="padding-top: 20px;">
	<div class="content_act  border-b row">
    <div class="items-push text-uppercase leave-req-t">
    
    <?php foreach ($leaveAccounts as $leaveAccount){?>
	
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
<section class="content content-boxed content-full">


		<!-- Striped Table -->
		
		<?php if($_SESSION["authprivilage"]=="employee"){?>
		
		<div class="block">
			<a class="btn btn-primary push-5-r push-20 pull-right" data-toggle="tooltip" title="" data-original-title="Request for a Leave" 	style="border-radius: 20px;" 	href="<?php echo myUrl('leaveRequests/raise')?>"><i class="fa fa-plus"></i></a>
        </div>
        <?php }?>
		<div class="push-50-t bg-white">
			<div class="block-header">
				<h4>Leave Requests</h4>
				
				<div class="notification"></div>
				
			</div>
			<?php if($_SESSION["authprivilage"]!="employee"){?>
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
			<div class="block-content">
		
				<table class="table table-striped table-condensed myteam-dataTable no-footer display compact " id="myteam-datatable" style="width:100%" data-pagelength=10 data-processing=true data-serverside=true data-ajax="<?php echo myUrl('leaveRequests/dt_leave_requests/me')?>">
				<!-- data-ajax="<?php echo myUrl('leaveRequests/dt_leave_requests/me')?>" -->
				
                   <thead>
						<tr>
							<th></th>							
							<th>Employee</th>
							<th>EmployeeID</th>
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
					<?php if($_SESSION["authprivilage"]!="employee"){?>	
			<?php if(!$custom_filters_leave || !$custom_filters_status){
			}else{
				?>
				<tr id="filter">
				<th class="details-control" rowspan="1" colspan="1">
				</th><th rowspan="1" colspan="1"></th><th rowspan="1" colspan="1">
				</th><th rowspan="1" colspan="1"></th>
				<th rowspan="1" colspan="1"></th>
				<th rowspan="1" colspan="1"></th>
				<th rowspan="1" colspan="1"></th>
				<th rowspan="1" colspan="1"></th>
				<th class="leave_type" rowspan="1" colspan="1">
				       <select id="filter_leave"><option value=""></option>
				       <?php foreach($custom_filters_leave as $custom_filters_leav) {?>
				       <option value="<?php echo $custom_filters_leav['leave_type']?>"><?php echo $custom_filters_leav['leave_type']?></option>
				       <?php }?>
				       </select>
				       </th>
			<th class="status" rowspan="1" colspan="1">
					<select id="filter_status">
					<option value=""></option>
					<?php foreach($custom_filters_status as $custom_filters_stat){?>
							<option value="<?php echo $custom_filters_stat['status']?>">
								<?php if ($custom_filters_stat['status']=="A")
										$status="Approved";
									if ($custom_filters_stat['status']=="W")
										$status="Withdrawed";
									if ($custom_filters_stat['status']=="R")
										$status="Rejected";
									if ($custom_filters_stat['status']=="C")
										$status="Cancelled";
									if ($custom_filters_stat['status']=="RQ")
										$status="Pending";
									
									?>
								<?php echo $status ?>
							</option>
					<?php }?>		
					</select></th>
			<th rowspan="1" colspan="1"></th></tr>
			
			
				<?php }?>
				<?php }?>
				
				</tfoot>
				</table>
			</div>
		</div>
		<!-- END Striped Table -->
	</div>
</section>

<!-- <div class="modal in" id="approveReject-modal" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="block">
				<div class="block-header">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button">
								<i class="si si-close"></i>
							</button>
						</li>
					</ul>
					<h3 class="block-title text-center">Specify a Reason</h3>
					<div class="block-content">
						<form class="form-horizontal push-10-t push-10"  action="<?php echo myUrl('leaveRequests/update')?>" id="approveRejectForm" method="POST">
							<div class="form-group">
								<div class="col-xs-12">
									<div class="form-material form-material-primary floating">
										<input name="id" id="request_id"  type="hidden">
										<input name="value" id="status_value"  type="hidden"> 
										<textarea rows="2" cols="" placeholder="Specify a reason here" name="remarks"  class="form-control"></textarea> 
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 text-center">
									<button class="btn btn-sm"
										type="submit" id="button-text"></button>
									<button class="btn btn-sm btn-primary" type="button"
										data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</form>
					</div>
					
				</div>

			</div>

		</div>
	</div>
</div> -->
<div class="modal in" id="approveReject-modal" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
	<div class="block-content" id="respond_form" style="padding: 0px 20px 1px 0px;">
</div>
</div>
</div>