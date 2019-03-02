<div id="filterLoader" style="height:25%;width: 96%;"> </div>
<input type="hidden" id="act" value="<?php echo base64_encode($_SESSION['company_id']."!getEmployeesbyFilter");?>">
<input type="hidden" id="screenName" value="<?php echo isset($screen)?$screen:"";?>">
<input type="hidden" id="loadprocessedEmp" value="<?php echo $loadprocessedEmp;?>">
<!--  Set Emplouee Enable/disabled-->
<input type="hidden" id="loadDisabled" value="<?php echo $loadDisabled;?>">
<div class="col-lg-12 panel-body">
<div class="col-lg-6">
<div class="form-group">
		<label class="col-lg-2 control-label">For</label>
		<div class="col-lg-10 filterCustomise">
		 <div class="btn-group" data-toggle="buttons">
<?php
include_once (dirname ( __DIR__ ) . "/include/config.php");
     foreach($filtersFor as $key => $filterFor){
     	$isEmployeeMutiple=(($filterFor['for']=='E' && $filterFor['isMultiple']==1)?$filterFor['isMultiple']:'');
     		$labelId=($filterFor['for']=='D')?'Department':(($filterFor['for']=='F')?'Designation':
     				(($filterFor['for']=='B')?'Branch':(($filterFor['for']=='E')?'Employee':(($filterFor['for']=='S')?'Shift':(($filterFor['for']=='T')?'Team':'')))));
		echo '<label for="'.$labelId.'" class="btn btn-default '.(($key==0)?'active':'').'"> <input name="filterfor" id="'.$labelId.'"  '.(($key==0)?'Checked':'').' value="'.$filterFor['for'].'" type="radio"> '.$labelId.'</label>';
		}?>
		</div>
		</div>
	</div>	
		<!-- DepartMent Code -->
		<?php 
		foreach($filtersFor as $key => $filterFor){
			if($filterFor['for']=='D'){
				?>
				<div id="filter_Department_Id" class="displayHide form-group">
				    <label class="col-lg-2 control-label">Department</label>
					<div class="col-lg-10">
					<select class="form-control" id="filter_Department_chosenId"	data-placeholder="Select Department" name="filter_Department_selected[]" <?php echo (($filterFor['isMultiple']==1)?'multiple':'');?>>
                      <?php $stmt = mysqli_prepare ( $this->conn, "SELECT distinct c.department_name,c.department_id
                      											   FROM company_departments c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.department_id= c.department_id".
                             (($loadprocessedEmp==1)?" INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1":"")
                      		                                      ." WHERE c.enabled=1");
					   $result = mysqli_stmt_execute ( $stmt );
					   mysqli_stmt_bind_result ( $stmt, $department_name, $department_id );
					  echo (($filterFor['isMultiple']==0)?'<option>Select Department</option>':'');
					   while ( mysqli_stmt_fetch ( $stmt ) ) {
							echo "<option value='" . $department_id . "'>" . $department_name . " [ " . $department_id . " ] <br>" . "</option>";
						}?> 
					</select></div></div>
			<?php } ?>
			<!--  Designation -->
			<?php if($filterFor['for']=='F'){
				?>
				<div id="filter_Designation_Id"  class="displayHide form-group">
				    <label class="col-lg-2 control-label">Designation</label>
					<div class="col-lg-10">
					<select class="form-control" id="filter_Designation_chosenId" data-placeholder="Select Designation"  name="filter_Designation_selected[]" <?php echo (($filterFor['isMultiple']==1)?'multiple':'');?>>
	                 <?php  $stmt = mysqli_prepare ( $this->conn, "SELECT distinct c.designation_name,c.designation_id
                      											   FROM company_designations c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.designation_id= c.designation_id".
                             (($loadprocessedEmp==1)?" INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1":"")
                      		                                      ." WHERE c.enabled=1" );
					$result = mysqli_stmt_execute ( $stmt );
					echo (($filterFor['isMultiple']==0)?'<option>Select Designation</option>':'');
					mysqli_stmt_bind_result ( $stmt, $designation_name, $designation_id );
					while ( mysqli_stmt_fetch ( $stmt ) ) {
						echo  "<option value='" . $designation_id . "'>" . $designation_name . " [ " . $designation_id . " ] <br>" . "</option>";
					}?>
					</select></div></div>
			<?php } ?>
			<!--  Branch -->
			<?php if($filterFor['for']=='B'){
				?>
				<div id="filter_Branch_Id"  class="displayHide form-group">
				    <label class="col-lg-2 control-label">Branch</label>
					<div class="col-lg-10">
					<select class="form-control" id="filter_Branch_chosenId"  data-placeholder="Select Branch" name="filter_Branch_selected[]" <?php echo (($filterFor['isMultiple']==1)?'multiple':'');?>>
	               <?php
	                $stmt = mysqli_prepare ( $this->conn, "SELECT distinct c.branch_name,c.branch_id
                      											   FROM company_branch c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.branch_id= c.branch_id".
                             (($loadprocessedEmp==1)?" INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1":"")
                      		                                      ." WHERE c.enabled=1" );
					$result = mysqli_stmt_execute ( $stmt );
					mysqli_stmt_bind_result ( $stmt, $branch_name, $branch_id );
					echo (($filterFor['isMultiple']==0)?'<option>Select Branch</option>':'');
					while ( mysqli_stmt_fetch ( $stmt ) ) {
						echo "<option value='" . $branch_id . "'>" . $branch_name . " [ " . $branch_id . " ] <br>" . "</option>";
					}
					?>
				    </select></div></div>
			<?php } ?>
			<!-- Team -->
		<?php 
		  if($filterFor['for']=='T'){
				?>
				<div id="filter_Team_Id" class="displayHide form-group">
				    <label class="col-lg-2 control-label">Team</label>
					<div class="col-lg-10">
					<select class="form-control" id="filter_Team_chosenId"	data-placeholder="Select Team" name="filter_Team_selected[]" <?php echo (($filterFor['isMultiple']==1)?'multiple':'');?>>
                      <?php $stmt = mysqli_prepare ( $this->conn, "SELECT distinct t.team_name,t.team_id
                      											   FROM company_team t
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.team_id= t.team_id".
                             (($loadprocessedEmp==1)?" INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1":"")
                      		                                      ." WHERE t.enabled=1");
					   $result = mysqli_stmt_execute ( $stmt );
					   mysqli_stmt_bind_result ( $stmt, $team_name, $team_id);
					  echo (($filterFor['isMultiple']==0)?'<option>Select Team</option>':'');
					   while ( mysqli_stmt_fetch ( $stmt ) ) {
					   	echo "<option value='" . $team_id. "'>" . $team_name. " [ " . $team_id. " ] <br>" . "</option>";
						}?> 
					</select></div></div>
			<?php } ?>
			<!--  Shift -->
			<?php if($filterFor['for']=='S'){
				?>
				<div id="filter_Shift_Id"  class="displayHide form-group">
				    <label class="col-lg-2 control-label">Shift</label>
					<div class="col-lg-10">
					<select class="form-control" id="filter_Shift_chosenId"	 data-placeholder="Select Shift" name="filter_Shift_selected[]" <?php echo (($filterFor['isMultiple']==1)?'multiple':'');?>>
	               <?php  $stmt = mysqli_prepare ( $this->conn, "SELECT distinct c.shift_id,c.shift_name
                      											   FROM company_shifts c
                      		                                       INNER JOIN employee_work_details w
	                                                               ON w.shift_id= c.shift_id".
                             (($loadprocessedEmp==1)?" INNER JOIN payroll_preview_temp pp
                                                                   ON pp.employee_id = w.employee_id AND pp.is_processed!=1":"")
                      		                                      ." WHERE c.enabled=1" );
				    $result = mysqli_stmt_execute ( $stmt );
					mysqli_stmt_bind_result ( $stmt,  $shift_id,$shift_name);
					echo (($filterFor['isMultiple']==0 )?'<option>Select Shift</option>':'');
					while ( mysqli_stmt_fetch ( $stmt ) ) {
						$shiftValue=($shift_id=='SH00001')?'SH00001,Nil,':$shift_id;
						echo "<option value='" . $shiftValue . "'>" . $shift_name . " [ " . $shift_id . " ] <br>" . "</option>";
					}
					?>
			     </select></div></div>
			<?php } ?>
	<?php }	?>
		
		   </div>
		  
		    <div class="col-lg-6 displayHide showedEmp" style="background-color:rgb(252, 252, 252);padding-bottom: 12px;border: 1px solid #CCC;border-radius: 4px;">
		    <div id="loaderForEmployees" style="margin-left: -4%"></div>
		    <div class="panel-heading row" style="/*border:1px solid #ccc;*/margin-left:0px;margin-right:0px;">
		    <div  style="padding-right: 0px;padding-left: 0px;">
		    Employees
		    <?php if($isEmployeeMutiple==1){?>
		   <button class="btn btn-xs btn-danger pull-right" style="margin-left:1%" type="button" id="filterDeselect">Deselect</button>
		    <button type="button" id="filterSelect" class="btn btn-xs btn-success pull-right">Select All</button>
		     <?php }?>
		     </div></div>
			    <select class="form-control" id="employeeSelected" data-placeholder="Employees Listed Here" name="affectedIds[]"  <?php echo (($isEmployeeMutiple==1)?'multiple':'');?>>
				</select>
			</div>
	    </div>
	