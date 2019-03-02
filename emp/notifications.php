<?php
include_once (dirname ( __DIR__ ) . "/include/config.php");
require_once (dirname ( dirname ( (__FILE__) ) ) . "/include/lib/notification.class.php");
$notification = new Notification ();
?>
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

<title>Notification</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet"
	href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link href="../css/tasks.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
<style>
.list-primary {
	padding: 15px 0 15px 35px !important;
	position: relative;
	background: #f5f6f8 !important;
	margin-bottom: 2px;
	border-bottom: 1px dashed #eaeaea;
	background-color: white;
}

.list-task {
	padding: 15px 0 15px 35px !important;
	position: relative;
	margin-bottom: 2px;
	border-bottom: 1px dashed #eaeaea;
	background-color: white;
}

.ajax_loader {
	display: block;
	margin: 0px auto;
}

.show_more {
	cursor: pointer;
}

.mail-option {
	padding: 13px 0 11px 23px !important;
}

.read-notification {
	cursor: pointer;
}
</style>



</head>

<body>

	<section id="container" class="">
		<!--header start-->
     <?php include("header.php")?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
     <?php include_once("sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper">
				<!-- page start-->
				<div class="row">
					<div class="col-md-12">
						<section class="panel tasks-widget">
							<header class="panel-heading"> Notifications </header>
							<div class="panel-body">

								<div class="task-content">
									<div class="mail-option">
										<div class="chk-all">
											<input type="checkbox"
												class="mail-checkbox mail-group-checkbox">
											<div class="btn-group" id="inboxclose">
												<a class="btn mini all" id="checkds" href="#"
													data-toggle="dropdown"> <i class="fa fa-caret-down"
													id="fa-caret"></i>
												</a>
												<ul class="dropdown-menu">
													<li><a class="none" style="cursor: pointer;"> None</a></li>
													<li><a class="read" style="cursor: pointer;"> Read</a></li>
													<li><a class="unreadmessage" style="cursor: pointer;">
															Unread</a></li>
												</ul>
											</div>
										</div>

										<div class="btn-group" id="delete">
											<a class="btn mini tooltips" id="delete"
												data-toggle="dropdown" data-placement="top"
												data-original-title="Delete"> <i class="fa fa-trash-o"></i>
											</a>
										</div>
										<div class="btn-group">
											<div id="send" class="alert alert-info fade in"
												style="display: none;">
												<button data-dismiss="" class="close close-sm" type="button">
													<i class="fa fa-times"></i>
												</button>
												<span class="composed_reply"></span>
											</div>
										</div>




									</div>
									<ul class="task-list">
										<li><img src="../img/ajax-loader.gif" class="ajax_loader"></li>

									</ul>
									<div class="text-center show_more">
										<a data-sum='1' data-end='10' id="show_more"
											style="display: none">Show More</a>
									</div>
									<img src="../img/input-spinner.gif" class="ajax_loader"
										style="display: none">
								</div>
                                     <?php
																																					$result = $notification->countNotificationEmployee ( $_SESSION ['employee_id'] );
																																					foreach ( $result as $row ) {
																																						?>
                                <input type="hidden" name="mjk" id="mjk"
									value="<?php echo $row['countEmp']?>">
                                 <?php
																																					}
																																					?>
                                     
                          </div>
						</section>
					</div>
				</div>


				<!-- page end-->
			</section>
		</section>
		<!--main content end-->
		<!--footer start include 'footer.php' -->

		<!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>


	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/tasks.js" type="text/javascript"></script>
	<script src="../js/bootstrap-dialog.js"></script>


	<script>
     $(document).ready(function(e){
    	
    		var start_limit = 0;
    		var end_limit = 10;
    		$.ajax({
    	        dataType: 'html',
    	        type: "POST",
    	        url: "php/notification.handle.php",
    	        cache: false,
    	        data:{act: '<?php echo base64_encode($_SESSION['company_id']."!getEmployeeNotifications");?>',start_limit:start_limit,end_limit:end_limit},
    	        success: function (data) {
    	        data = JSON.parse(data);
    	        $('#show_more').show();
    	        $('.ajax_loader').hide();
    	        if(data[0] == 'success'){
    	        var html = "";
    	        $('.task-list').html("");    	        
    	        for(var i=0;i<data[2].length;i++){        	    
				if(data[2][i].isRead == 1){
					var dataClass = 'list-primary';
					var showUrl = false;
				}else{
					var dataClass = 'list-task';
					var showUrl = true;
				}
				html +='<li class='+dataClass+' id='+data[2][i].notification_id+'><div class="task-checkbox"><input name="mailcheckbox[]" type="checkbox" class="list-child '+dataClass+'" value="'+data[2][i].notification_id+'"></div><div class="task-title"><span class="task-title-sp"><a class="read-notification" data-url="'+showUrl+'" data-action="'+data[2][i].action+'" data-value="'+data[2][i].notification_id+'" >'+data[2][i].nText+'</a></span><span class="pull-right">'+data[2][i].createDate+'</span></div></li>';
    	        }
    	        $('.task-list').append(html);
    	        }else if(data[0] == 'error'){
        	        $('#show_more').hide();
        	        var html = "";
    	        	html +='<li class="list-task"><div class="task-title"><span class="task-title-sp">No Notification Found</span></div></li>';
    	        	$('.task-list').append(html);
    	        }	
    	        },
    	        error:function(jqXHR, textStatus, errorThrown) {
    	        	
    	      },
    	    });

     });
$('#show_more').click(function(e){
	$('.ajax_loader').show();
	var start_limit = $(this).data('sum');
	var end_limit = $(this).data('end');
	var add = 10+start_limit;
	var end = end_limit;
	if($('#mjk').val() <= add+end){
		add =add;
		$(this).hide();
	}else{
		add = add;
	}
	$(this).data('sum',add);
	$.ajax({
        dataType: 'html',
        type: "POST",
        url: "php/notification.handle.php",
        cache: false,
        data:{act: '<?php echo base64_encode($_SESSION['company_id']."!getEmployeeNotifications");?>',start_limit:add,end_limit:end},
        success: function (data) {
        data = JSON.parse(data);
        $('.ajax_loader').hide();
        if(data[0] == 'success'){
        var html = "";
        var count = 0;
        
        for(var i=0;i<data[2].length;i++){
        	if(data[2][i].isRead == 1){
				var dataClass = 'list-primary';
				var showUrl = false;
			}else{
				var dataClass = 'list-task';
				var showUrl = true;
			}
			html +='<li class='+dataClass+' id='+data[2][i].notification_id+'><div class="task-checkbox"><input name="mailcheckbox[]" type="checkbox" class="list-child '+dataClass+'" value="'+data[2][i].notification_id+'"></div><div class="task-title"><span class="task-title-sp"><a class="read-notification" data-url="'+showUrl+'" data-action="'+data[2][i].action+'" data-value="'+data[2][i].notification_id+'" >'+data[2][i].nText+'</a></span><span class="pull-right">'+data[2][i].createDate+'</span></div></li>';
	        }
        $('.task-list').append(html);
        }else if(data[0] == 'error'){
	        var html = "";
        	html +='<li class="list-task"><div class="task-title"><span class="task-title-sp">No Notification Found</span></div></li>';
        	$('.task-list').insertAfter(html);
        }	
        },
        error:function(jqXHR, textStatus, errorThrown) {
        	
      },
    });

});
$(".mail-group-checkbox").change(function (e) {
	e.preventDefault();
$(".list-child").prop('checked', $(this).prop("checked"));
});
$('.none').click(function (e) {
	e.preventDefault();
$('.list-child,.mail-group-checkbox').prop('checked', false);
});
$(".read").click(function (e) {
	e.preventDefault();
$('.list-primary').prop('checked', true);
$('.list-task').prop('checked', false);
});
$(".unreadmessage").click(function (e) {
	e.preventDefault();
	$('.list-primary').prop('checked', false);	
$('.list-task').prop('checked', true);
});
$('#delete').click(function(e){
	e.preventDefault();
	var notification_id = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value;
	}).join(',');
	console.log(notification_id);
	if(notification_id == ''){
	}else{
BootstrapDialog.show({
 title:'Confirmation',
	        message: 'Are Sure you want Delete this Notification</strong>',
	        buttons: [{
	            label: 'Yes',
	            cssClass: 'btn-sm btn-success',
	            autospin: true,
	            action: function(dialogRef){
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "php/notification.handle.php",
        cache: false,
        data: {notification_id:notification_id,act: '<?php echo base64_encode($_SESSION['company_id']."!deleteNotification");?>'},	
        complete:function(){
            dialogRef.close();
         },
        success: function (data) {
            data = JSON.parse(data);
           
            if (data[0] == "success") {
            	  BootstrapDialog.alert(data[1]);
            	  
            	var array = notification_id.toString().split(",") ;
            	var count = 0;
            	  $.each(array,function(i){
                  	$('#'+array[i]).remove();
                  	count++;
                      });
            	  $('#send').show();
            	  $('.composed_reply').html(count+' Notification Has been Deleted Successfully');
            	  $('#send').fadeOut(5000);
            	  $('.close-sm').click(function(e){
            		  $('#send').hide();
            	  });
            	
            }else if (data[0] == "error") {
            	 alert(data[1]);    
            	 
            }
        }
    });
	            }
            }, {
                label: 'No',
                cssClass: 'btn-sm btn-danger',
                action: function(dialogRef){
                    dialogRef.close();
                    $('#ajax_loader_refresh').hide();
                }
            }]
	});
	}
});
$(document).on('click','.read-notification',function(e){
	e.preventDefault();
	var notification_id = $(this).data('value');
	var url = $(this).data('action');
	var check = $(this).data('url');
	if(check == true){
	$.ajax({
        dataType: 'html',
        type: "POST",
        url: "php/notification.handle.php",
        cache: false,
        data:{act: '<?php echo base64_encode($_SESSION['company_id']."!readNotification");?>',notif_id:notification_id},
        success: function (data) {
        data = JSON.parse(data);
        if(data[0] == 'success'){
        	window.location.href = url;
        }else if(data[0] == 'error'){
            
        }	
        },
        error:function(jqXHR, textStatus, errorThrown) {
        	
      },
    });
	}else{
		window.location.href=url;
	}
});
    </script>

</body>
</html>
