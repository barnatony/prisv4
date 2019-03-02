<section class="push-30-t">
 <div id="widgets">
 </div>
</section>

<script type="text/x-template" id="widget-tpl">
<div class="row">
<div class="col-lg-12">
  <div class="col-xs-12 col-sm-6 col-lg-3">
                <a class="block block-bordered block-link-hover3" href="javascript:void(0)">
                <table class="block-table text-center">
                    <tbody>
                        <tr>
                            <td style="width: 50%;" class="bg-city border-r">
                                   
                                <div class="h6 text-muted push-5-t text-white">Total headcounts</div>
{#total}
                            <div class="h1 font-w700 text-white">{total}</div>
{/total}
                            </td>
                            
                        <td style="width: 50%;">
<div class="push-30 push-30-t">
                                    <i class="si si-user fa-3x text-black-op"></i>
                                </div>                          
</td></tr>
                    </tbody>
                </table>
            </a>
            </div>
<div class="col-xs-12 col-sm-6 col-lg-3">
                <a class="block block-bordered block-link-hover3 joined" href="javascript:void(0)" data-content="" onclick="initPopoverJoin(this)" >
                <table class="block-table text-center">
                    <tbody>
                        <tr>
                            <td style="width: 50%;" class="bg-modern text-white border-r">
                                   
                                <div class="h6 text-muted push-5-t text-white">Joined this month</div>
{#joined_count}
                            <div class="h1 font-w700">{joined}</div>
{/joined_count}
                            </td>
                            
                        <td style="width: 50%;">
<div class="push-30 push-30-t">
                                    <i class="si si-user-follow fa-3x text-black-op"></i>
                                </div>                          
</td></tr>
                    </tbody>
                </table>
            </a>
            </div>
<div class="col-xs-12 col-sm-6 col-lg-3">
                <a class="block block-bordered block-link-hover3" href="javascript:void(0)" onclick="initPopoverExit(this)">
                <table class="block-table text-center">
                    <tbody>
                        <tr>
                            <td style="width: 50%;" class="bg-default text-white border-r">
                                   
                                <div class="h6 text-muted push-5-t text-white">Exits this month</div>
{#exits_count}
                            <div class="h1 font-w700">{exits}</div>
{/exits_count}
                            </td>
                            
                        <td style="width: 50%;">
<div class="push-30 push-30-t">
                                    <i class="si si-user-unfollow fa-3x text-black-op"></i>
                                </div>                          
</td></tr>
                    </tbody>
                </table>
            </a>
            </div>
<div class="col-xs-12 col-sm-6 col-lg-3">
                <a class="block block-bordered block-link-hover3" href="javascript:void(0)" onclick="initPopoverAbsent(this)">
                <table class="block-table text-center">
                    <tbody>
                        <tr>
                            <td style="width: 50%;" class="  bg-info text-white border-r">
                                   
                                <div class="h6 text-muted push-5-t text-white">Long Absent</div>
{#absent_employees}
                            <div class="h1 font-w700">{abs_count}</div>
{/absent_employees}
                            </td>
                            
                        <td style="width: 50%;">
<div class="push-30 push-30-t">
                                    <i class="si si-users fa-3x text-black-op"></i>
                                </div>                          
</td></tr>
                    </tbody>
                </table>
            </a>
            </div>
  
  </div>
  
  
      </div>
</script>
  
        <div class="col-lg-6 col-sm-6 ">

 <div class="block block-themed" id="branch_view">
 <div class="block-header bg-info">
                    <ul class="block-options">
                        <li>
                            <button type="button" id="branch" data-value="branch_count"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Branch Headcount</h3>
                </div>
<div class="block-content">    
<div class="chartjs-wrapper">
<div style="height: 330px;">
<canvas id="branch-gender-ratios" class="js-chartjs-gender"></canvas>
</div>
</div></div>
</div>

</div>
   
<div class="col-lg-6 col-sm-6 ">
<div class="block block-themed" id="imageSize">
 <div class="block-header bg-info">
                    <ul class="block-options">
                        <li>
                            <button type="button" id="imagePercentage" ><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Gender Ratios</h3>
                    
                </div>
                <div id="images"></div>

</div>
<script type="text/x-template" id="image-tpl">

<div class="block-content" style="height: 350px;">    
<div class="col-lg-12 col-sm-6 ">
<div class="row">
{#Percentage}

{@select key=gender}
{@eq value="Female"}

<div class="col-xs-6 col-sm-6 "><img class="female_image  " src="<?php echo str_replace('/b/',"/b/img/rsz_avatar_woman_profile.png" , myUrl('',true));?>" alt=""style="width: 259px;height: 262px" data-val="female" onmouseover="initPopoverCount(this)">
<div style="color: #ff6c9d;margin-top: 16px !important;" class="h4 font-w600  text-center" >{total} </div>
</div>

{:else}
<div class="col-xs-6 col-sm-6"><img class="male_image " src="<?php echo str_replace('/b/',"/b/img/male.png" , myUrl('',true));?>" data-val="male" style="width:290px;height: 279px;" onmouseover="initPopoverCount(this) "
" alt="">
<div style="color: #70b9eb" class="h4 font-w600 push-10-l text-center ">{total} </div>
{/eq}
{/select}
{/Percentage}
</div>
</div>
</div>

</div></div>

</script>
</div>


<div class=" col-lg-12 " style="padding: 0px !important">
<div class="col-lg-6 col-sm-6 ">

 <div class="block block-themed" id="employee_span">
 <div class="block-header bg-info">
                    <ul class="block-options">
                        <li>
                            <button type="button" id="emp_span" data-value="emp_count"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Time with company</h3>
                </div>

<div class="block-content bg-white">    
<div class="chartjs-wrapper">
<div style="height: 330px;">
<canvas id="duration" class="js-chartjs"></canvas>
</div>
</div></div>

</div>

</div>
<div class="col-lg-6 col-sm-6">
<div class="block block-themed" id="empAge_view">
 <div class="block-header bg-info">
                    <ul class="block-options">
                        <li>
                            <button type="button" id="emp_age" data-value="empAge"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Employee Age Group</h3>
                </div>
<div id="">
<div class="block-content bg-white">    
<div class="chartjs-wrapper">
<div style="height: 330px;">
<canvas id="emp-age" class="js-chartjs"></canvas>
</div>

</div></div>
</div>
</div>

</div>


</div>

<!--  hired vs left starts here -->
<div class=" col-lg-12  " style="padding: 0px !important">
<div class="col-lg-6 col-sm-6 ">

 <div class="block block-themed" id="hired_view">
 <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button type="button" id="hired" data-value=""><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Hired Vs Left</h3>
                </div>

<div class="block-content">    
<div class="chartjs-wrapper">
<div style="height: 330px;">
<canvas id="hired-left" class="js-chartjs"></canvas>
</div>
</div></div>

</div>

</div>
<div class="col-lg-6 col-sm-6">
<div class="block block-themed" id="employee_count_view">
 <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button type="button" id="emp_view" data-value=""><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Employees</h3>
                </div>
<div id="">
<div class="block-content bg-white">    
<div class="chartjs-wrapper">
<div style="height: 330px;">
<canvas id="employees" class="js-chartjs"></canvas>
</div>

</div></div>
</div>
</div>

</div>


</div>

<!--  hired vs left ends here -->
    
    
    
<!-- time with company starts here -->

<div class=" col-lg-12">

<div class="block block-themed" id="team_view">
 <div class="block-header bg-info">
                    <ul class="block-options">
                        <li>
                            <button type="button" id="team" data-value=""><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Team Headcount</h3>
                </div>
<div class="block-content">    
<div class="chartjs-wrapper">
<div style=" position: relative;
overflow: auto;
overflow-x: auto;
max-height: 250px;
width: 100%;">
<canvas id="gender-ratios" class="js-chartjs-gender"></canvas>
</div>
</div>
</div>

</div>
</div>    
    