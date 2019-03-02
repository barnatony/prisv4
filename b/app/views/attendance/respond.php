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
<h3 class="text-center block-title">Approve/Reject Regularization Request</h3>
</div>
<div class="block-content">
         <div class=" col-sm-12 ">
		      <form class="form-horizontal" action="<?php echo myUrl("attendance/reg_update")?>" id="respondForm" method="POST">
                    <div class="form-group">
                          	<input type="hidden" name="for" value=<?php echo isset($for)?$for:null?>>
                            <input type="hidden" name="id" value="<?php echo $request->get("id")?>">
                            <input type="hidden" name="rep_id" value=<?php echo isset($_REQUEST['rep_id'])?base64_decode($_REQUEST['rep_id']):""?>>
                            <?php if($for!="p"){?>
                            <input type="hidden" name="req_token" value="<?php echo $request->get("req_token")?>">
                            <input type="hidden" name="cid" value="<?php echo isset($_REQUEST['cid'])?base64_decode($_REQUEST['cid']):$_SESSION['company_id'] ?>">
                            <?php }?>
                            <input type="hidden" name="day" value="<?php echo $request->get("day")?>">
                            
                            <table class="table remove-margin-b font-s13 table-striped">
                                    <tbody>
                                    
                                        <tr>
                                         
                                            <td class="font-w600">
                                                <h5 class="font-w300 text-primary">
                                                <?php 
                                                	echo date('d M, Y', strtotime($request->get("day")));?>
                                                </h5>
                                                <span class="label label-info"><?php echo strtoupper($request->get("regularize_type"));?></span>
                                                <span class="label label-warning"><?php echo strtoupper($request->get("reason_type"));?></span>
                                                <div class="text-muted word-wrap-txt"><b><?php echo isset($notes)?$notes:null?></b></div>
                                                <div class="text-muted"><small><?php echo $request->get("reason");?></small></div>
                                                
                                            </td>
                               
                    <td>
                    
                     <?php if($request->get("status")=="RQ")
                     	echo "<span class='badge badge-warning'>Pending</span>";
                     elseif($request->get("status")=="A")
                     echo "<span class='badge badge-success'>Approved</span>";
                     elseif($request->get("status")=="R")
                     	echo "<span class='badge badge-danger'>Rejected</span>";
                     elseif($request->get("status")=="W")
                     	echo "<span class='badge badge-danger'>Withdrawed </span>";
                     ?>
                    
                   
                    </td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
                            <?php if($request->get("status")=="RQ"){?>
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