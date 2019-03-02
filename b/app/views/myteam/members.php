<section class="content content-full">
            <!-- Section Content start here-->
            
            <div class="row">
                <div class="col-sm-12 team_members_view" id="team_members_view">
                    <!-- Team Members -->
                        
                  
                            
                        
                  
                                        </div>
            </div>
        </section>
        
        
        <script type="text/x-template team-members" id="team-members">
<div>
<div class="col-sm-12">
           <a class="fa fa-angle-left fa-2x text-left" href="<?php echo myUrl('myteam//')?>">&nbsp;<span class="h3" style="display: inherit;">
                Manage Team</span></a>
                
        </div>

{@eq key=employees.length value=0}
 <p class="h4 text-center push-20-t"> No Members Found</p>
{:else}
    <div class="row items-push text-center">
            <h3 class="font-w300">
                Members ({employees.length})
            </h3>

    </div>
<hr>
{/eq}
</div>
{#employees}
                        <div class="col-sm-6  col-lg-4">
                            <div class="block  box-shaddow" id="">
    <table class="block-table text-center">
                    <tbody>
                        <tr>
                            <td class="bg-primary" style="width: 40%;">
                                <div class="push-30 push-30-t">
                                    <div>
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
  <div class="h5 push-15-t push-5">{employee_name}</div>
<div class="">{employee_id}</div>
                                </div>
                            </td>
                            <td style="width: 60%;">
                                
                            <div style="" class="text-left">
                            <div class=" push-10">
                                        <i class="si si-badge  push-5-r"></i>{designation}</div>
    <div class=" push-10">
                                        <i class="si si-users push-5-r"></i>{team_name}</div>
                                    	<div class=" push-10">
                                        <i class="si si-users push-5-r"></i>{department}</div>
                                        <div class="push-10"><i class="fa fa-building font-s17 push-5-r"></i><span class="">{branch}</span></div>
                                        <div class="push-10 text-left">
                                            <div class="h5 font-w300 " title="{doj}">
                                            <i class="si si-calendar" title="{doj}"></i>&nbsp;<em class="font-s17"> {experience}</em>
                                            
                                            </div>

                                        </div>                                                                         
<div class="push-10"><i class="si si-screen-smartphone"></i>&nbsp;
                        <a class="text-normal" href="tel:{employee_mobile}" target="_top">
                            {employee_mobile}</a>
                    </div>
<div class="push-10"><i class="si si-envelope"></i>&nbsp;
                        <a class="text-normal" href="mailto:{employee_email}">
{employee_email}</a>
                    </div>
                                                                                        
                                </div>
<!-- <div class="" style="position: relative;bottom: -11px;">
   <span style="color:#5ce19b;border: 1px solid #5ce19b;" class="label ct pull-left">Open</span>
   <ul class=" block-options">
                                            <li>
                                               <input name="lead_id" id="lead_id" value="182" type="hidden">
                                               <button type="button" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Delete Lead" id="remove" data-id="182"><i class="fa fa-trash-o"></i></button>
                                            </li>
                                            <li>
                                                <button type="button" class="js-tooltip" data-placement="top" data-original-title="Assign Manager" data-toggle="modal" data-target="#modal-master" data-id="182"><i class="fa fa-user-plus"></i></button>
                                            </li>
                                           
                                        </ul>
<div> -->
</div></div></td>
                        </tr>
                    </tbody>
                </table>
                                
                              
                                

                    
                    


                        
                            </div>
                        </div>
                  
                            {/employees}
                        </script>