<?php if(!$teamleads){?>
<section class="content content-full">
            <!-- Section Content start here-->
            
            <div class="row">
                <div class="col-sm-12 "><div><div class="col-sm-12"></div><p class="h4 text-center push-20-t"> No Members Found</p></div></div>
            </div>
        </section>

<?php }else{?>
<section class="push-30-t">
<div id="widgets"><div class="row">
<div class="col-lg-12">


<div class="col-xs-12 col-sm-6 col-lg-3 ">
<a class="block block-bordered block-link-hover3 Present" id="Present" href="javascript:void(0)">
<table class="block-table text-center"><tbody>
<tr><td style="width: 50%;" class="bg-city border-r"><div class="h6 text-muted push-5-t text-white">Present Today</div>

<div class="h1 font-w700 text-white"><?php echo $Present['tot_count']?></div>

</td><td style="width: 50%;"><div class="push-30 push-30-t">
<i class="si si-user fa-3x text-black-op"></i></div>                          
</td></tr>
</tbody></table></a>
</div>
<div class="col-xs-12 col-sm-6 col-lg-3">
<a class="block block-bordered block-link-hover3" id="Absent" href="javascript:void(0)" ">
<table class="block-table text-center"><tbody><tr><td style="width: 50%;" class="bg-default text-white border-r">
<div class="h6 text-muted push-5-t text-white">Absent Today</div>

<div class="h1 font-w700"><?php echo $Absent['tot_count']?></div>

</td>
<td style="width: 50%;"><div class="push-30 push-30-t">
<i class="si si-user-unfollow fa-3x text-black-op"></i></div>                          </td></tr></tbody></table></a>
</div>
<div class="col-xs-12 col-sm-6 col-lg-3">
<a class="block block-bordered block-link-hover3 joined" id="Active" href="javascript:void(0)" data-content="" >
<table class="block-table text-center"><tbody><tr><td style="width: 50%;" class="bg-modern text-white border-r">
<div class="h6 text-muted push-5-t text-white">Active Users</div>

<div class="h1 font-w700"><?php echo $Active['tot_count']?></div>

</td><td style="width: 50%;">
<div class="push-30 push-30-t"><i class="si si-user-follow fa-3x text-black-op"></i></div>                        
 </td></tr></tbody></table></a>
 </div>
<div class="col-xs-12 col-sm-6 col-lg-3"><a class="block block-bordered block-link-hover3"  id="Late" href="javascript:void(0)">
<table class="block-table text-center"><tbody>
<tr><td style="width: 50%;" class="  bg-info text-white border-r"><div class="h6 text-muted push-5-t text-white">Late Comers</div>


<div class="h1 font-w700"><?php echo $Late['tot_count']?></div>
</td><td style="width: 50%;"><div class="push-30 push-30-t"><i class="si si-users fa-3x text-black-op"></i></div>                       
</td></tr></tbody></table></a>

</div>



</div></div></div>
</section>



<section>
<div class="row ">
<div class="col-lg-6 col-sm-6">
<div class="col-sm-12" >
 <div class="block block-themed" id="team" style="height: 330px !important">
 <div class="block-header  bg-primary">
                    <ul class="block-options">
                        <li>
                            <button type="button" class="tpl-team" data-value="status"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">MYteam</h3>
                </div>
                
                <div class="team_members_view" id="team_members_view"></div>

</div>
</div>
</div>

<script type="text/x-template" class="team-members" id="team-members">
<div id="lstatus" style="position: relative; overflow-y: auto; overflow-x :hidden; max-height: 250px; width: 100%;">
<div class="block-content">
<div class="pull-t pull-r-l">
<table class="table remove-margin-b font-s13 table-striped">
<tbody>

{#employees}
<tr>
<td>{employee_name}
<div class="text-primary">
<i class="si si-badge">&nbsp;</i>
<span class="text-muted">{designation}</span>
</div>
</td>

<td class="font-w600"><div class="text-info">
<i class="fa fa-envelope ">&nbsp;</i>
<span class="text-muted">{employee_email}</span></div>
<div class="text-muted"><small>Email</small>
</div></td>
<td class="font-w600"><div class="text-success">
<i class="fa fa-phone-square ">&nbsp;</i>
<span class="text-muted">{employee_mobile}</span></div><div class="text-muted">
<small>Mobile</small></div></td>
<td class="font-w600">
<h5 class="font-w400 h5">{experience}</h5>
</td>
</tr>
{/employees}
</tbody></table></div>   

 </div></div>


</script>



<div class="col-lg-6 col-sm-6 ">
<div class="col-sm-12">
 <div class="block block-themed" id="late-template" style="height: 330px !important">
 <div class="block-header " style="background-color: #00ccb3;">
 
                    
                    <h3 class="block-title" id='title_change'>Late Comers</h3>
                </div>
                <div id="team_late_view"></div>

</div>
</div>

</div>
<script  type="text/x-template"  id="team-late">
<div id="lbalance" style="position: relative; overflow-y: auto; overflow-x :hidden; max-height: 250px; width: 100%;">      <div class="block-content">  
  <div class="pull-t pull-r-l">
{@eq key=lateComers.length value=0 }
<div class="pull-t pull-r-l"><div class=""> <div class="block-content block-content-full text-center push-100-t"><p>No Data Found.</p></div></div><table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>       </div>
{:else}
<table class="table remove-margin-b font-s13 table-striped table-bordered"><tbody>


                            
                                


<tr>
{@iterate key=lateComers[0]}
<th style="width: 50px;text-transform: capitalize;color: black" class="text-left" value=lateComers[0]> {$key}</th>
{/iterate}
</tr>
  
{#lateComers}
<tr><td class="font-w600 text-left">{employee}</td>
<td class="">{branch}</td><td style="width: 70px;" class="hidden-xs text-muted text-left">
{shift}
{^checkIn}
{^late}
<td><h5 class="font-w300 text-primary">{absentCount}</h5></td>
{/late}
{/checkIn}

{^absentCount}
{^late}
<td><h5 class="font-w300">{checkIn}</h5></td>
<td><h5 class="font-w300 text-primary">{lastCheckIn}</h5></td>
{:else}
<td><h5 class="font-w300 text-primary">{late}</h5></td>
<td><h5 class="font-w300">{lastCheckIn}</h5></td>
{/late}
{/absentCount}

</tr>
{/lateComers}
{/eq}
</tbody></table>    </div>
</div>                </div>
</script>
</div>
</section>


<section>
<div class="row ">
<div class="col-lg-6 col-sm-6 ">
<div class="col-sm-12">
 <div class="block block-themed" id="leave-template" style="height: 330px !important">
 <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button type="button" class="tpl-leave" data-value="status"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Team Leave Requests</h3>
                </div>
<div id="team_leaveRequests_view"></div>
</div>
</div>
</div>


<script type="text/x-template" id="team-leaveRequests">

<div id="lstatus">
<div class="block-content">
<div class="pull-t pull-r-l">
{@eq key=leaveRequests.length value=0 }
<div class="pull-t pull-r-l"><div class=""> <div class="block-content block-content-full text-center push-100-t"><p>No Data Found.</p></div></div><table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>       </div>
{/eq}
<table class="table remove-margin-b font-s13 table-striped">
<tbody>
{#leaveRequests}
<tr><td>{employee_name}</td>
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
										

<span class="label label-info">{leave_type}</span><div class="text-muted"><small>{reason}</small></div>
</td>
<td class="hidden-xs text-muted text-right" style="width: 70px;">
<div style="" class="col-sm-5 col-md-2 text-left"><span class="h5">{duration}</span><small>Day(s)</small></div>
</td>
{@select key=status}
<td>
								{@eq value="W"}	
								<span class="label label-danger">Withdrawed</span>
								{:else}
								{@eq value="RQ"}
								<span class="label label-warning">Pending</span>
								{:else}
								{@eq value="R"}
								<span class="label label-primary">Rejected</span>
								{:else}	
								{@eq value="A"}
								<span class="label label-success">Approved</span>
								{/eq}
								{/eq}
								{/eq}
								{/eq}
								{/select}
</td>
</tr>
{/leaveRequests}

</tbody></table>    </div><div class="block-content block-content-full block-content-mini  text-white text-center dashboard-sticky-bottom">
<a href="<?php echo myUrl('myteam/leaveRequests')?>" target="_block"> 
View All Requests <i class="fa fa-arrow-right text-black-op"></i></a></div>    </div></div>
</script>
<div class="col-lg-6 col-sm-6 ">
<div class="col-sm-12">
 <div class="block block-themed" id="reg-template" style="height: 330px !important">
 <div class="block-header bg-amethyst">
 
                    <ul class="block-options">
                    
                        <li>
                            <button type="button" class="tpl-reg" data-value="balance"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Attendance Regularization</h3>
                </div>
<div id="team_Reg_view"></div>
</div>
</div>

</div>

<script type="text/x-template" id="team-Regularization">
<div id="lbalance">      <div class="block-content">  
<div class="pull-t pull-r-l">
{@eq key=Regularization.length value=0 }
<div class="pull-t pull-r-l"><div class=""> <div class="block-content block-content-full text-center push-100-t"><p>No Data Found</p></div></div><table class="table remove-margin-b font-s13"><tbody></tbody></table><table class="table remove-margin-b font-s13"><tbody>       </tbody></table>       </div>
{/eq}

<table class="table remove-margin-b font-s13 table-striped"><tbody>
{#Regularization}
<tr><td>{employee_name}</td><td class="font-w600"><h5 class="font-w300 text-primary">{day} </h5>
<div class="text-muted"><small>{reason} </small></div>
</td>
<td class="hidden-xs text-muted text-right" style="width: 70px;">
<div style="" class="col-sm-5 col-md-2 text-left">
<span class="label label-info">{reason_type}</span></div>
</td>
{@select key=status}
<td>
								{@eq value="W"}	
								<span class="label label-danger">Withdrawed</span>
								{:else}
								{@eq value="RQ"}
								<span class="label label-warning">Pending</span>
								{:else}
								{@eq value="R"}
								<span class="label label-primary">Rejected</span>
								{:else}	
								{@eq value="A"}
								<span class="label label-success">Approved</span>
								{/eq}
								{/eq}
								{/eq}
								{/eq}
								{/select}
</td>
</tr>
{/Regularization}


</tbody></table>
</div>
</div><div class="block-content block-content-full block-content-mini  text-white text-center dashboard-sticky-bottom">
<a href="<?php echo myUrl('myteam/regularization')?>" target="_block">View All Regularization Requests <i class="fa fa-plus text-black-op"></i></a>
</div>                </div>

</script>
</div>
</section>
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

  </script>          </div>
</div>

</div>
</div>

</section>
<?php }?>