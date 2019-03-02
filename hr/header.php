<?php
//require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/devices.class.php");
// session has NOT been started
include_once (dirname ( __DIR__ ) . "/include/config.php");
$company_logo = $_SESSION ['company_logo'];
$company_name = $_SESSION ['company_name'];

if (empty ( $_SESSION ['master_db_name'] ) && empty ( $_SESSION ['company_db'] ) && empty ( $_SESSION ['login_id'] ) && empty ( $_SESSION ['display_name'] )) {
	echo "<script>window.location.href = '../'</script>";
}
?>
<style>
.imgHeader {
	width: 18%;
	margin-top: -3px;
}

@media only screen and (min-width: 320px) and (max-width:480px ) {
	.imgHeader {
		width: 18%;
		margin-top: -89px;
		margin-left: 7%;
	}
}

a.logo {
	margin-bottom: -25px;
}

.btn-xs {
	font-size: 9px;
	line-height: 2.5;
	border-radius: 3px;
}

h3, .h3 {
	font-size: 17px;
}
}
</style>
<header class="header white-bg">
	<div class="sidebar-toggle-box">
		<div data-original-title="Toggle Navigation" data-placement="right" class="fa fa-bars tooltips"></div>
	</div>
	<!--logo start-->
	<a href="index.php" class="logo"> <img alt="<?php echo $company_name; ?>"
		src="<?php echo $company_logo;?>" class="imgHeader"></a>
	<!--logo end-->



	<div class="top-nav nav" id="top_menu">

		<ul class="nav pull-right top-menu">
			<li>
				<button class="btn btn-primary btn-xs" style="margin-top: 8px;"
					type="button">
              <?php echo $_SESSION['fywithMonth'];?></button>
			</li>
			<li><a class="btn btn-default btn-xs device_sync" id="device_sync" title="Device Sync"  data-ip="182.71.224.91:28" data-deviceiid="14" style="padding-top:0px;padding-bottom:0px;">
                    <?php         						
                    									$devices=mysqli_query($conn,"SELECT IP FROM devices");
                    									
                    									while($row = mysqli_fetch_array($devices,MYSQLI_ASSOC))
                    									{
                    										foreach ($row as $key => $value) {
                    											echo "<input class='device_ip_syn' type='hidden' value='" . $value. "'/>";
                    										}
                    									}
                    									
                    									//return $devices;
														?> 	 	
                     	<i class="fa fa-refresh"></i></a>
            </li>
			<li id="header_inbox_bar" class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">
                     <?php
																					require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/message.class.php");
																					$message = new Message ();
																					$result = $message->getMessageNotification ( $_SESSION ['login_id'], 0, 5 );
																					$unreadCount = 0;
																					foreach ( $result as $row ) {
																						if ($row ["isRead"] == 0) {
																							$unreadCount ++;
																						}
																					}
																					
																					?>
                      <i class="fa fa-envelope-o"></i> <span
					class="badge bg-important"><?php echo $unreadCount!=0?$unreadCount:""?></span>
			</a>
				<ul class="dropdown-menu extended inbox">
					<div class="notify-arrow notify-arrow-red"></div>
					<li>
						<p class="red"> <?php echo $unreadCount!=0?"You have {$unreadCount} new messages":"No new messages"?> </p>
					</li>
                   <?php
																			foreach ( $result as $row ) {
																				if ($row ['parentMessageId'] != '' && $row ['parentMessageId'] != null) {
																					$msg = $row ['parentMessageId'];
																				} else {
																					$msg = $row ['messageId'];
																				}
																				$sender_id = $row ['senderId'];
																				if ($row ["isRead"] == '0') {
																					?>
                      <li class="readMessage">
                    
<?php
																				} else {
																					?>

					
					<li>
<?php
																				}
																				?>
                          <a
						href="messages.php?<?php echo "message_id=".base64_encode($msg)."&recipient_id=".base64_encode($sender_id)?>"
						data-value="<?php echo $row['messageId']?>"
						style="cursor: pointer; white-space: normal !important;"> <span
							class="subject"> <span class="from"><?php echo $row['senderName']?></span>
								<span class="time"><?php echo $row['sentOn']?></span>
						</span> <span class="message">
                                        <?php echo substr($row['body'],0,40)?>
                                    </span>
					</a>

					</li>
                         <?php
																			}
																			?>
                      <li><a href="messages.php">See all messages</a></li>
				</ul></li>

			<!-- inbox dropdown end -->
			<!-- notification dropdown start-->
              <?php
														require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/notification.class.php");
														$notification = new Notification ();
														$result = $notification->getAdminNotifications ( $_SESSION ['login_id'], 0, 5 );
														$unreadCount = 0;
														foreach ( $result as $row ) {
															if ($row ["isRead"] == 0) {
																$unreadCount ++;
															}
														}
														?>
              <li id="header_notification_bar" class="dropdown"><a
				data-toggle="dropdown" class="dropdown-toggle" href="#"> <i
					class="fa fa-bell-o"></i> <span class="badge bg-warning"><?php echo $unreadCount!=0?$unreadCount:""?></span>

			</a>

				<ul class="dropdown-menu extended notification">
					<div class="notify-arrow notify-arrow-yellow"></div>
					<li>
						<p class="yellow"> <?php echo $unreadCount!=0?"You have {$unreadCount} new Notification":"No new Notification"?> </p>
					</li>
                      <?php
																						foreach ( $result as $row ) {
																							if ($row ["isRead"] == '0') {
																								?>
                      <li class="readMessage">
                    
<?php
																								$show_url = "true";
																							} else {
																								$show_url = "false";
																								?>

					
					<li>
<?php
																							}
																							?>

                          <a class="read-notification"
						data-url="<?php echo $show_url?>"
						data-action="<?php echo $row['action'] ?>"
						data-value="<?php echo $row['notification_id']?>"
						style="cursor: pointer; white-space: normal !important;"> <span
							class="subject">
								<div class="message">
                                    <?php echo $row['nText']?>
                                    </div>
						</span>
							<div class="time" style="float: right"><?php echo $row['createDate']?></div>


					</a>

					</li>
                         <?php
																						}
																						?>
                            <li><a href="notifications.php">See all
							Notifications</a></li>
				</ul></li>

			<li class="dropdown pull-right"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#"> <span class="username"><?php echo $_SESSION['display_name']; ?></span>
					<b class="caret"></b>
			</a>
				<ul class="dropdown-menu extended logout">
					<li><a href="profileSetup.php"><i class="fa fa-key"></i> Profile
							Settings</a></li>
				    <li><a href="masterSetup.php"><i class="fa fa-gears"></i> Company
							Settings</a></li>
					<li><a href="logout.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
				</ul></li>


			<!-- user login dropdown end -->
		</ul>
	</div>
</header>
<script src="../js/jquery-2.1.4.min.js"></script>
<script>
$('.read-notification').click(function(e){
	e.preventDefault();
	
	var notification_id = $(this).data('value');
	var url = $(this).data('action');
	var check = $(this).data('url');
	console.log(check);
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

$('#device_sync').click(function(e){
	e.preventDefault();
	var target = $(this);
	var ip = [];
	ip = $('#device_sync :input'); 
	
	 var ip1 = [];
	 $.each(ip,function(index,value){    
		 ip1 = value.value;
		 $.ajax({
		        dataType: 'html',
		        type: "POST",
		        url: "php/devices.handle.php",
		        cache: false,
		        data:{act: '<?php echo base64_encode($_SESSION['company_id']."!deviceSync");?>',deviceIp:ip1},
		        beforeSend: function () {
	                  target.button('loading');
	              },
	              complete: function () {
	                  target.button('reset');
	               },
			      success: function (data) {
		        	jsonobject = JSON.parse(data);
	                   if(jsonobject[0]=='error' && jsonobject[2]=='Synced')
	                	   BootstrapDialog.alert('Device is Already Synced Uptodate ');
	                   else if(jsonobject[0]=='error')	   
	                	  BootstrapDialog.alert(jsonobject[1]);
	                   else if(jsonobject[0]!='error')
						   BootstrapDialog.alert(jsonobject[1]);
		        }
		 	 	});
		 });
});

/*For Date Time Picker Sesssion Storage*/
current_payroll_month='<?php echo $_SESSION['current_payroll_month']?>';
localStorage.setItem("current_payroll_month",current_payroll_month );
</script>