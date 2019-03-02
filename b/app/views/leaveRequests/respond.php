<section class="block-content block-content-narrow">
<div class="block row box-shaddow" id="respondFormBlock">
<?php if($for=="p"){?>
<ul class="block-options push-10-t push-10-r">
						<li>
							<button data-dismiss="modal" type="button">
								<i class="si si-close"></i>
							</button>
						</li>
					</ul>
<?php }?>					

                <div class="block-header">
                    <h3 class="text-center block-title">Approve/Reject Leave Request</h3>
                </div>
                <div class="block-content">
                    <div class=" col-sm-12 ">
		      
		      <form class="form-horizontal" action="<?php echo myUrl("leaveRequests/respond_update")?>" id="respondForm" method="POST">
                 
                            <div class="form-group" >
                            <input type="hidden" name="for" value=<?php echo isset($for)?$for:null?>>
                            <input type="hidden" name="id" value="<?php echo $request->get("id")?>">
                            <input type="hidden" name="req_id" value="<?php echo $request->get("request_id")?>">
                            <?php if($for!="p"){?>
                            <input type="hidden" name="rep_id" value=<?php echo isset($_REQUEST['rep_id'])?base64_decode($_REQUEST['rep_id']):""?>>
                            <input type="hidden" name="req_token" value="<?php echo $request->get("req_token")?>">
                            <?php }?>
                            <input type="hidden" name="cid" value="<?php echo isset($_REQUEST['cid'])?base64_decode($_REQUEST['cid']):$_SESSION['company_id'] ?>">
                            <input type="hidden" name="durationStart" value="<?php echo $request->get("from_date")?>">
                            <input type="hidden" name="durationEnd" value="<?php echo $request->get("to_date")?>">
                             
                            <table class="table remove-margin-b font-s13 table-striped">
                                    <tbody>
                                    
                                        <tr>
                                         
                                            <td class="font-w600">
                                                <h5 class="font-w300 text-primary">
                                                <?php 
                                                if($request->get("from_date")==$request->get("to_date"))
                                                	if(($request->get('from_half')==$request->get('to_half')) ||($request->get('from_half')=='SH' AND $request->get('to_half')=='FH'))
                                                		echo date('d M, Y', strtotime($request->get("from_date"))),'&nbsp;'.$request->get('from_half');
                                                	
                                                	else
                                                		echo date('d M, Y', strtotime($request->get("from_date")));
                                                else 
                                                	if(($request->get('from_half')==$request->get('to_half')) ||($request->get('from_half')=='SH' AND $request->get('to_half')=='FH'))
                                                		echo date('d M, Y', strtotime($request->get("from_date"))).'&nbsp;'.$request->get('from_half')." - ".date('d M, Y', strtotime($request->get("to_date"))).'&nbsp;'.$request->get('to_half');
                                                	else
                                                		echo date('d M, Y', strtotime($request->get("from_date")))." - ".date('d M, Y', strtotime($request->get("to_date")));
                                                		
                                                		
                                                ?>
                                                </h5>
                                                
                                                	
                                                
                                                	
                                                	
                                                	
                                                
                                                <span class="label label-info"><?php echo strtoupper($request->get("leave_type"));?></span>
                                                <div class="text-muted"><small><?php echo $request->get("reason");?></small></div>
                                            </td>
                                            <td class="hidden-xs text-muted text-right" style="width: 70px;"><div style="" class="col-sm-5 col-md-2 text-left">
                        <span class="h5"><?php echo $request->get("duration");?></span>
                        <small>Day(s)</small>
                    </div></td>
                    <td>
                    
                     <?php if($request->get("status")=="RQ")
                     	echo "<span class='badge badge-warning'>Pending</span>";
                     elseif($request->get("status")=="A")
                     echo "<span class='badge badge-success'>Approved</span>";
                     elseif($request->get("status")=="R")
                     	echo "<span class='badge badge-danger'>Rejected</span>";
                     elseif($request->get("status")=="W")
                     	echo "<span class='badge badge-danger'>Withdrawed </span>";
                     	else
                     		echo "<span class='badge badge-danger'>Cancelled </span>";
                     	
                     	?>
                    
                   
                    </td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if(strtoupper($request->get("status"))=="RQ"){?>
                             <?php if(strtoupper($request->get("leave_type"))=='LOP'){?>
                              
                      <div class="form-group">
                      <label class="col-lg-12" for="leave">Leave Type:</label>
						 <div class="col-lg-12">
						 
                             <div class="form-material form-material-primary">
                             <select class="form-control" id="leave_rule" name="leave_rule">
                      <?php foreach($leaverules as $leaverule){?>
							<option value=<?php echo $leaverule?>><?php echo strtoupper($leaverule)?></option>
					<?php }?>
						
                      
						</select>
						</div>
						</div>
						</div>
						
						<?php }?>
                            <div class="form-group">
                            <div class="col-lg-12">
                                <div class="form-material form-material-primary">
                                    <textarea class=" form-control" id="remarks" name="remarks" rows="3" maxlength="250" data-always-show="true" placeholder="Reason.."></textarea>
                                    <label for="example-material-maxlength7">Reason</label>
                                </div>
                            </div>
                        </div>
                        <div class="err-notification"></div>
                       
                        <div class="form-group">
                          <div class="text-center">
                              <div class="notifications"></div>
                              <input type="hidden" name="value" id="status_value" value="">
                            <button class="btn btn-sm btn-success push-5 response_btn" data-status="A" type="button">Approve</button>
                            <button class="btn btn-sm btn-danger push-5 response_btn" data-status="R" type="button">Reject</button>
	                     </div>
                      </div>
                      <?php }?>
                     
                     
</form>
</div>
</div>
</div>
</section>
