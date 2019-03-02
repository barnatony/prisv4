<?php
include_once (dirname ( __DIR__ ) . "/include/config.php");
/*
  if(empty($_SESSION['employee_id']) || empty($_SESSION['employee_name'])){
  echo "<script>window.location.href = '../'</script>";
  }
*/
$company_name = $_SESSION ['company_name'];
$company_logo = $_SESSION ['company_logo'];
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
		<div data-original-title="Toggle Navigation" data-placement="right"
			class="fa fa-bars tooltips"></div>
	</div>
	<!--logo start-->
	<a href="index.php" class="logo"> <img alt="<?php echo $company_name; ?>"
		src="<?php echo $company_logo;?>" class="imgHeader"></a>
	<!--logo end-->
	<div class="top-nav ">
		<ul class="nav pull-right top-menu">
			<li>
				<button class="btn btn-primary btn-xs" style="margin-top: 8px;"
					type="button">
              <?php echo $_SESSION['fywithMonth'];?></button>
			</li>
			<li id="header_inbox_bar" class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">
                     <?php
																					require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/message.class.php");
																					$message = new Message ();
																					$result = $message->getMessageNotification ( $_SESSION ['employee_id'], 0, 5 );
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
						style="cursor: pointer;"> <span class="subject"> <span
								class="from"><?php echo $row['senderName']?></span> <span
								class="time"><?php echo $row['sentOn']?></span>
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
                        <?php
																								require_once (dirname ( dirname ( __FILE__ ) ) . "/include/lib/notification.class.php");
																								$notification = new Notification ();
																								$result = $notification->getEmployeeNotifications ( $_SESSION ['employee_id'], 0, 5 );
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
			<!-- inbox dropdown end -->
			<!-- user login dropdown start-->
			<li class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">
                      <?php
																						if ($_SESSION ['employee_image'] == 'Nil') {
																							if ($_SESSION ['employee_gender'] == 'Male') {
																								$imaName = '../img/default-avatar_men.png';
																							} elseif ($_SESSION ['employee_gender'] == 'Female') {
																								$imaName = '../img/default-avatar_women.png';
																							} else {
																								$imaName = '../img/default-avatar_women.png';
																							}
																						} else {
																							$imaName = $_SESSION ['employee_image'];
																						}
																						?>
                      
                       <img alt="employee_image"
					src="<?php echo $imaName?>" style="height: 25px;"> <span
					class="username"><?php echo $_SESSION['employee_name']; ?></span> <b
					class="caret"></b>
			</a>
				<ul class="dropdown-menu extended logout">
					<div class="log-arrow-up"></div>
					<li><a href="empProfileView.php"> <i class=" fa fa-user"></i>
							Profile
					</a></li>
					<li><a href="empProfileSetup.php"><i class="fa fa-cog"></i>
							Settings</a></li>
					
					<li><a href="logout.php"><i class="fa fa-key"></i> Log Out</a></li>
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

/*FOr date Time picker javascript Session Storage*/
	        current_payroll_month='<?php echo $_SESSION['current_payroll_month']?>';
	     	localStorage.setItem("current_payroll_month",current_payroll_month );
</script>