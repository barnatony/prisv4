<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bassechs">
<title>User Create</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<style>
</style>
</head>
<body>
	<section id="container">
		<!--header start-->
     <?php include("header.php"); ?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading" ><span id="headerSet">User Create</span>
					<div class="btn-group pull-right">
							<button id="showhide" type="button" class="btn btn-sm btn-info whenEditHideThis" style="margin-top: -5px;">
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</header>
					<div id="loader"></div>
					<form class="form-horizontal" id="createLoginForm" method="post" action="" enctype="multipart/form-data">
						<div class="panel-body">
						<div id="divShowHide" class="displayHide">
						<input type="hidden" id="master_id" name="masterId" value="Nil">
						<input type="hidden" name="act"  id="act" value="<?php echo base64_encode("!insert");?>" />
						<div class="col-lg-6">
                               <div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
										Name</label>
									<div class="col-lg-7">
										<input class="form-control" name="name"type="text" id="master_name">
									</div>
								</div>
								 <div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									User Name</label>
									<div class="col-lg-7">
										<input class="form-control" name="userName" type="text" id="master_username">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-5 control-label">Gender</label>
									<div class="col-lg-7 whenViewShow displayHide">
									<input class="form-control" type="text" id="genderText">
                                  	</div>
									<div class="col-lg-7 whenViewHide">
                                  	<label for="maleLabel" class="col-lg-6 control-label">
                                  	<input name="gender" id="maleLabel" type="radio" checked=""  value="Male"> Male</label>
									<label for="femaleLabel" class="col-lg-6 control-label">
									<input name="gender" id="femaleLabel" type="radio" value="Female"> Female</label>
									</div>
								</div>
								 <div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									Email</label>
									<div class="col-lg-7">
										<input class="form-control" name="emailId" type="text" id="master_email">
									</div>
								</div>
								<div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									Password</label>
									<div class="col-lg-7">
										<input class="form-control" name="password" type="text" id="master_password">
									</div>
								</div>
								<div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									Confirm Password</label>
									<div class="col-lg-7">
										<input class="form-control" name="confirmPass" type="text" id="confirmPass">
									</div>
								</div>
								<div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									Mobile</label>
									<div class="col-lg-7">
										<input class="form-control" name="mobile" type="text" id="master_mobile">
									</div>
								</div>
								 <div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									Address</label>
									<div class="col-lg-7">
										<input class="form-control" name="address" type="text" id="master_address">
									</div>
								</div>
								<div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									City</label>
									<div class="col-lg-7">
										<input class="form-control" name="city" type="text" id="master_city">
									</div>
								</div>
								 <div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									State</label>
									<div class="col-lg-7">
										<input class="form-control" name="state" type="text" id="master_state">
									</div>
								</div>
								
							</div>
								<div class="col-lg-6">
								<div class="form-group">
									<label  class="col-lg-5 col-sm-5 control-label">
									Image</label>
										<div class="col-lg-7">
															<div class="fileupload-new thumbnail" style="width: 123px; height: 160px;margin-bottom: 5px;">
																<img id="master_image" src="http://www.placehold.it/123x160/EFEFEF/AAAAAA&amp;text=no+image" alt="Company Image">
															</div>
															<input type="hidden" id="flagImageSet" name="isImage" value="0">
															<span class="btn btn-success btn-sm fileinput-button" id="span_image" style="width: 123px;"> 
															<i class="fa fa-upload"></i> <span>Upload Image</span> <input name="uLogo" id="masterConimage" accept="image/jpeg,image/png" type="file">
															</span> <em class="help-block well errorC" id="well">Provide
																123 x 160 For Company Logo</em>
														</div>
								</div>
                               
								<div class="form-group <?php  echo ($_SESSION['loginIn']!='Master')?'displayHide':''?>" >
									<label  class="col-lg-5 col-sm-5 control-label">
									Role</label>
									<div class="col-lg-7 whenViewShow displayHide">
									<input class="form-control" type="text" id="roleText">
                                  	</div>
									<div class="col-lg-7  whenViewHide">
									<select class="form-control" name="role" id="master_role">
									    <?php 
										if($_SESSION['loginIn']=='Master'){
										?>
										<option value="">Select Role</option>
										<option value="Consultant">Consultant</option>
										<option value="Partner">Partner</option>
										<option value="Master">Master</option>
										<?php }else{?>
										<option value="Consultant">Consultant</option>
										<?php }?>
									</select>
									</div>
								</div>
								<div class="panel-body pull-right whenEditHideThis">
								<span class="errorMsg"></span>
										<button type="submit" class="btn btn-sm btn-success" id="createLogin">Create</button>
											<button type="button" class="btn btn-sm btn-danger cancelLogin">Cancel</button>
								</div>
								<div class="panel-body pull-right whenEditShowThis displayHide">
								<span class="errorMsg"></span>
											<button type="submit" class="btn btn-sm btn-success" data-id="editForm" id="loginFormEdit">Update</button>
											<button type="button" class="btn btn-sm btn-danger cancelLogin">Cancel</button>
								</div>
									
							</div>
						</div>
						</div>
					</form>
					<div class="panel-body whenEditHideThis">
					<div id="tableContent"></div>
					</div>
				</section>

			</section>
		</section>
		<!--main content end-->
		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	
		<script>
		$(document).ready(function () {
           selectAllcompanies();
		});
    	  $("#masterConimage").change(function (e) {
                  if (this.disabled) return alert('File upload not supported!');
                  var F = this.files;
                  if (F && F[0]) for (var i = 0; i < F.length; i++) readImage(F[i]);
              });

              function readImage(file) {

                  var reader = new FileReader();
                  var image = new Image();

                  reader.readAsDataURL(file);
                  reader.onload = function (_file) {
                      image.src = _file.target.result;              // url.createObjectURL(file);
                      image.onload = function () {
                          var w = this.width,
                                    h = this.height,
                                    s = ~ ~(file.size / 1024);
                          if (w <= 123 && h <= 160 && s < 10000) {
                              $('#master_image').attr('src', this.src);
                              $('.errorC').html("Provide 123 x 160 Image");
                        	  $(".errorC").css("color", "black"); 	
                        	  $('#flagImageSet').val(1);
                         } else {
                        	  $('.errorC').html("Provide 123 x 160 Image");
                        	  $(".errorC").css("color", "red");
                        	  $('#flagImageSet').val(0); 
                         }

                      };
                      image.onerror = function () {
                          alert('Invalid file type: ' + file.type);
                      };
                  };
               };
              
              function selectAllcompanies(){
                  $.ajax({
                      dataType: 'html',
                      type: "POST",
                      url: "php/loginCreate.handle.php",
                      cache: false,
                      data: { act: '<?php echo base64_encode("!select");?>',isAllColumsSelect:0 },
		             beforeSend:function(){
                       	$('#loader').loading(true); 
                        },
                     success: function (data) {
                          jsondata = JSON.parse(data);
                          if (jsondata[0] == "success") {
                              tableViewForMaster(jsondata[2]);
                         }

                      	$('#loader').loading(false); 
                      }

                  });
              }
              

              $(document).on('click', "#loginUserTable a.edit", function (e) {
	             e.preventDefault();
                 $('.whenEditHideThis,.whenViewShow').hide();
                 $('#headerSet').html('User Edit');
                 var masterName = $(this).closest('tr').children(':eq(1)').text();
	             var masterId = $(this).closest('tr').children(':eq(0)').text();
	             $('#divShowHide').toggle();
	             $('.whenEditShowThis,.whenViewHide').show();
	             getValues(masterId);
	             actval='<?php echo base64_encode("!update");?>';
	             $('#act').val(actval);
	           });

              $(document).on('click', "#loginUserTable a.view", function (e) {
 	             e.preventDefault();
                  $('.whenEditHideThis,.fileinput-button,.errorC,.whenViewHide').hide();
                  $('#headerSet').html('<span id="back"  style="color:#EA6229;cursor:pointer">User </span> << View');
                  var masterName = $(this).closest('tr').children(':eq(1)').text();
 	             var masterId = $(this).closest('tr').children(':eq(0)').text();
 	             $('#divShowHide').toggle();
 	             $('.whenEditShowThis,.whenViewShow').show();
 	             getValues(masterId);
 	            $("#createLoginForm").find(":input").each(function () {
                    $(this).css({ 'background-color': '#FFF', 'border': '0px' });
                    $(this).attr('disabled', true);
                    });
 	          });
              
	            function getValues(masterId){
	            	 $.ajax({
		                    dataType: 'html',
		                    type: "POST",
		                    url: "php/loginCreate.handle.php",
		                    cache: false,
		                    data: { act: '<?php echo base64_encode("!select");?>', masterId:masterId,isAllColumsSelect:1},
		                    success: function (data) {
		                    	jsonData = JSON.parse(data);
		                    	jsonData[2][1].forEach(function(idVal,index) {
		                    		$("#master_role option[value='']").attr('selected', true);
			                    	if(idVal=='master_image'){
				                    	console.log(jsonData[2][0][0][index].substring(3));
			                    		$('#'+idVal).attr('src',jsonData[2][0][0][index]);
			                    	}
			                    	else if(idVal=='master_role'){
				                    	$("select[name='role'] option[value="+jsonData[2][0][0][index]+"]").attr("selected","selected");
			                    	    $('#roleText').val(jsonData[2][0][0][index]);
			                    	}
			                    	else if(idVal=='master_gender'){
			                    		$("input[name=gender][value="+jsonData[2][0][0][index]+"]").prop("checked",true);
			                    		$('#genderText').val(jsonData[2][0][0][index]);
			                    	}
			                    	else if(idVal=='master_password')
			                    	    $("#confirmPass,#master_password").val(jsonData[2][0][0][index]);
			                    	else
		                    		$('#'+idVal).val(jsonData[2][0][0][index]);
		                    	 });
		                   }
		                });
	            	 }
                

	               $(document).on('click', "#loginUserTable a.enable", function (e) {
	               e.preventDefault();
	               var masterName = $(this).closest('tr').children(':eq(1)').text();
	               var masterId = $(this).closest('tr').children(':eq(0)').text();
	                BootstrapDialog.show({
		                title:'Confirmation',
	                    message: 'Are Sure you want to Disable <strong>'+ masterName +'<strong>',
	                    buttons: [{
	                        label: 'Disable',
	                        cssClass: 'btn-sm btn-danger',
	                        autospin: true,
	                        action: function(dialogRef){
	                        	 $.ajax({
      		                    dataType: 'html',
      		                    type: "POST",
      		                   url: "php/loginCreate.handle.php",
      		                    cache: false,
      		                    data: { act: '<?php echo base64_encode("!disable");?>', masterId:masterId},
      		                      complete:function(){
      		                    	 dialogRef.close();
      		                      },
      		                    success: function (data) {
      		                    	jsonData = JSON.parse(data);
      		                    	if (jsonData[0] == "success") {
      		                    	   tableViewForMaster(jsonData[2]);
      		                           BootstrapDialog.alert(jsonData[1]);  
      		                        }else if (jsonData[0] == "error") {
      		                          BootstrapDialog.alert(jsonData[1]); 
		                                }
      		                    }
      		                });
	                        }
	                    }, {
	                        label: 'Close',
	                        cssClass: 'btn-sm btn-default',
	                        action: function(dialogRef){
	                            dialogRef.close();
	                        }
	                    }]
	                });
	               
	            });

               $(document).on('click', "#loginUserTable a.disable", function (e) {
	                e.preventDefault();
	                var masterName = $(this).closest('tr').children(':eq(1)').text();
	               var masterId = $(this).closest('tr').children(':eq(0)').text();
	                BootstrapDialog.show({
		                title:'Confirmation',
	                    message: 'Are Sure you want to Enable <strong>'+ masterName +'<strong>',
	                    buttons: [{
	                        label: 'Enable',
	                        cssClass: 'btn-sm btn-danger',
	                        autospin: true,
	                        action: function(dialogRef){
	                        	 $.ajax({
       		                    dataType: 'html',
       		                    type: "POST",
       		                   url: "php/loginCreate.handle.php",
       		                    cache: false,
       		                    data: { act: '<?php echo base64_encode("!enable");?>', masterId:masterId },
       		                      complete:function(){
       		                    	 dialogRef.close();
       		                      },
       		                    success: function (data) {
       		                    	jsonData = JSON.parse(data);
       		                    	if (jsonData[0] == "success") {
       		                    	   tableViewForMaster(jsonData[2]);
       		                           BootstrapDialog.alert(jsonData[1]);  
       		                        }else if (jsonData[0] == "error") {
       		                          BootstrapDialog.alert(jsonData[1]); 
		                                }
       		                    }
       		                });
	                        }
	                    }, {
	                        label: 'Close',
	                        cssClass: 'btn-sm btn-default',
	                        action: function(dialogRef){
	                            dialogRef.close();
	                        }
	                    }]
	                });
	               
	            });
	            
              $('#createLoginForm').on('submit', function (e) {
                e.preventDefault();
                if($('#master_name').val() && $('#master_username').val() && 
                   $('#master_email').val() && $('#master_password').val() && $('#master_mobile').val()
                   && $('#master_address').val()&& $('#master_city').val()
                   && $('#master_state').val()&& $("#master_role option:selected").val()){
                    if($('#master_password').val()==$('#confirmPass').val()){
                    	$('.errorMsg').html("");
             	$.ajax({
                       dataType: 'html',
                       type: "POST",
                       url: "php/loginCreate.handle.php",
                       cache: false,
                       data: new FormData(this),
                       processData: false,
                       contentType: false,
                       beforeSend:function(){
                        	$('#createLogin').button('loading'); 
                         },
                      success: function (data) {
                           jsondata = JSON.parse(data);
                           if (jsondata[0] == "success") {
                              tableViewForMaster(jsondata[2]);
                              $('#showhide').click();
                          }
                           else
                               if (data1[0] == "error") {
                                   alert(data1[1]);
                               }
                           $('#createLogin').button('reset'); 
                       }

                   });}else{
                	   $('.errorMsg').html("Password Doesn't Match");
                       }
                }else{
                    $('.errorMsg').html("Enter All Details");
                }
               });
              
              $(document).on('click', '#back', function (e) {
                  e.preventDefault();
                 $('#showhide').click();
              });

              $(document).on('click', '#showhide,.cancelLogin', function (e) {
                  e.preventDefault();
                  $('#createLoginForm')[0].reset()
                  $('.whenEditHideThis,.whenViewHide,.fileinput-button,.errorC').show();
                  $('#master_image').attr('src','http://www.placehold.it/123x160/EFEFEF/AAAAAA&amp;text=no+image');                  
                  $('#headerSet').html('User Add');
                  $('#divShowHide').toggle();
	  	          $('.whenEditShowThis,.whenViewShow').hide();
	  	          $("#createLoginForm").find(":input").each(function () {
                  $(this).css({ 'background-color': '', 'border': '1px solid #e2e2e4' });
                  $(this).attr('disabled', false);
                   });
		  	      $("#master_role option[value='']").attr('selected', true);
		  	      actval='<?php echo base64_encode("!insert");?>';
		          $('#act').val(actval);
		          $('#master_id').val('Nil');
	            }); 
              
            //Master Table View Common
            				 function tableViewForMaster(data){
            					 if(data=='No Data Found'){
            						 html="<table class='table table-striped table-hover table-bordered dataTable' id='loginUserTable'><thead><tr><th>MasterID</th><th>MasterName</th><th>Mobile</th><th>Email</th><th>Role</th><th>	Active</th></tr></table>"
            					 }else{
            	                  htmlHeader="<table class='table table-striped table-hover table-bordered dataTable' id='loginUserTable'><thead><tr>";
            	                  data[1].forEach(function(columName) {
            	                	 htmlHeader+="<th>"+columName+"</th>";
            	                	});
            	                  htmlHeader+="</tr></thead>";
            	                  htmlBody="<tbody>";
            	                  data[0].forEach(function(trrows) {
            	                	  htmlBody+="<tr>";
            	                	  len=trrows.length;
            	                      trrows.forEach(function(td,index) {
            	                         if(index == len - 1){
            	                		  htmlBody+="<td><a class='view'><button class='btn btn-success btn-xs'><i class='fa fa-eye'></i></button></a>&nbsp;";
            	                		  htmlBody+=(td==1)?"<a class='edit'><button class='btn btn-danger btn-xs'><i class='fa fa-pencil'></i></button></a>&nbsp;<a class='enable' title='disable' ><button class='btn btn-success btn-xs'><i class='fa fa-unlock'></i></button></a></td>":"<a class='disable' title='disable'><button class='btn btn-danger btn-xs'><i class='fa fa-lock'></i></button></a></td>"; 
            	                		  }else
            	                		  htmlBody+="<td>"+td+"</td>"; 
            	                 	  });
            	                	  htmlBody+="</tr>";
            	                 	});
            	                  htmlBody+="</tbody></table>";
            	                  html=htmlHeader+htmlBody;
            					 }
            	                  $('#tableContent').html('').html(html);
            	                 var oTable=$('#loginUserTable').dataTable({
            	                	"iDisplayLength": 5,
            	  	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
            	  	            });
            	                $('#loginUserTable_wrapper .dataTables_filter').html('<div class="input-group">\
            	                          <input class="form-control medium" id="searchInput" type="text">\
            	                          <span class="input-group-btn">\
            	                            <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
            	                          </span>\
            	                          <span class="input-group-btn">\
            	                            <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
            	                          </span>\
            	                      </div>');
            						$('#loginUserTable_processing').css('text-align', 'center');
            						jQuery('#loginUserTable_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            						$('#searchInput').on('keyup', function (e) {
            						if (e.keyCode == 13) {
            						oTable.fnFilter($(this).val());
            						} else if (e.keyCode == 27) {
            						$(this).parent().parent().find('input').val("");
            						oTable.fnFilter("");
            						}
            						});
            						$('#searchFilter').on('click', function () {
            						oTable.fnFilter($(this).parent().parent().find('input').val());
            						});
            						$('#searchClear').on('click', function () {
            						$(this).parent().parent().find('input').val("");
            						oTable.fnFilter("");
            						});
            	  	           }
             
</script>
</body>  
</html>
