<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Mosaddek">
<meta name="keyword"
	content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
<link rel="shortcut icon" href="img/favicon.png">

<title>Profile Setup</title>

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

	<section id="container" class="">
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
				<section class="panel">
					<!-- page start-->
					<header class="panel-heading tab-bg-dark-navy-blue">
						<ul class="nav nav-tabs  ">
							<li class="active"><a href="#change_password" data-toggle="tab"
								id="changePassword"> Change Password </a></li>
							<!-- <li class="">
                                              <a href="#increment" data-id="i" data-toggle="tab" id="incClick">
                                                  Increment
                                              </a>
                                          </li>
                                          <li class="">
                                              <a href="#transfer" data-id="t" data-toggle="tab" id="transferClick">
                                                  Transfer
                                              </a>
                                          </li>
                                       -->
						</ul>
					</header>
					<form id="profilesetup" method="POST" class="form-horizontal"
						role="form">
						<div class="panel-body">
							<div class="tab-content tasi-tab">
								<div class="tab-pane active" id="changePassword">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="col-lg-3 col-sm-3 control-label">Current
												Password</label>
											<div class="col-lg-4">
		                                        <?php
																																										$result = mysqli_query ( $conn, "SELECT password FROM company_login_details WHERE user_name='" . $_SESSION ['display_name'] . "' " );
																																										$row = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
																																										echo '<input class="form-control"  id="oldPass" name="oldPass" value="' . $row ['password'] . '"  type="hidden">';
																																										?>
		                                     <input class="form-control"
													name="changeFor" value="admin" type="hidden"> <input
													class="form-control" name="loginId"
													value='<?php echo $_SESSION['display_name'];?>'
													type="hidden"> <input class="form-control"
													name="currentPass" id="currentPass" maxlength="10"
													type="password" value=''>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 col-sm-3 control-label">New Password</label>
											<div class="col-lg-4">
												<input class="form-control" name="newPass" id="newPass"
													maxlength="10" type="password">
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 col-sm-3 control-label">Confirm
												Password</label>
											<div class="col-lg-4">
												<input class="form-control" id="confirmPass"
													name="confirmPass" maxlength="10" type="password">
											</div>
										</div>
										<div class="form-group">
											<div class="col-lg-offset-4 col-lg-4">
												<button type="submit" class="btn btn-success"
													id="changePassSub">save</button>
												<button type="button" class="btn btn-danger cancel">Cancel</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

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
	<!-- END JAVASCRIPTS -->
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<script>
    $("#profilesetup").validate({
        
        // Specify the validation rules
        rules: {
        	currentPass: "required",
        	newPass: "required",
        	confirmPass: "required",
        	confirmPass: {
                equalTo: "#newPass"
              }
                  	
           },
      // Specify the validation error messages
        messages: {
        	currentPass: "Please Enter Current Password",
        	confirmPass: "Must Be Equal To New password",
            },
        
        submitHandler: function(form) {
        	 $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "../common/profileSetup.php",
                    cache: false,
                    data: $('#profilesetup').serialize(),
                    beforeSend:function(){
                       	$('#changePassSub').button('loading'); 
                        },
                        complete:function(){
                       	 $('#changePassSub').button('reset');
                        },
                    success: function (data) {
                    	data = JSON.parse(data);
                    	if (data == 1) {
                    	$('#profilesetup')[0].reset();
                        BootstrapDialog.alert("Password has been Changed Successfully");
                        setTimeout(function(){ 
                        	$('.close').click();
                     	   location.assign("logout.php"); 
                             }, 2000);
                        }else if (data == 0) {
                            	$('#profilesetup')[0].reset();
                                BootstrapDialog.alert("Password has Not Been Changed");
                                }
                    }
                });
        }
    });
    

    $(document).on('click', ".cancel", function (e) {
    	  e.preventDefault();
      
      window.location.assign("index.php");
    	  
        });
    
    </script>


</body>
</html>
