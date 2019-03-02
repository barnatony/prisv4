<?php
include_once (dirname ( __DIR__ ) . "/include/config.php");
require_once (dirname ( dirname ( (__FILE__) ) ) . "/include/lib/message.class.php");
$message = new Message ();
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
<title>Message</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/chosen.css" rel="stylesheet">
<link href="../css/chosen-bootstrap.css" rel="stylesheet">
<style>
.modal-dialog {
	width: 50%;
	z-index: 0;
}

.form-control, #changebutton {
	margin-bottom: 10px;
}

.mail-box {
	height: 570px;
}

#fa-caret {
	vertical-align: top;
}

.alert {
	padding-top: 7px !important;
	padding-bottom: 9px !important;
	margin-bottom: 0px;
}
</style>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
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
			<section class="wrapper">
				<!--mail inbox start-->
				<div class="mail-box">

					<aside class="lg-side">
						<div class="inbox-head">
							<i class="fa fa-inbox"><h4 class="in-inbox inbox_head_header">Inbox</h4></i>
							| <i class="fa fa-envelope-o fa-1x"><h4
									class="in-send inbox_head_header">Sent</h4></i> | <i
								class="fa fa-pencil fa-1x"><h4 id="compose"
									class="inbox_head_header">Compose</h4></i>
							<form class="pull-right position" action="#"
								style="display: none;">
								<div class="input-append">
									<input type="text" placeholder="Search Mail" class="sr-input">
									<button type="button" class="btn sr-btn">
										<i class="fa fa-search"></i>
									</button>
								</div>
							</form>


						</div>
						<div class="inbox-body top-nav-all">
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
											<li><a class="unreadmessage" style="cursor: pointer;"> Unread</a></li>
										</ul>
									</div>
								</div>
								<div class="btn-group" style="display: none" id="inboxmessages">
									<a class="btn mini blue" id="back" data-toggle="dropdown"
										data-placement="top" data-original-title="Back"> <i
										class="fa fa-arrow-left"></i>
									</a>
								</div>
								<div class="btn-group" style="display: none" id="inboxmessages1">
									<a class="btn mini blue backs" data-toggle="dropdown"
										data-placement="top" data-original-title="Back"> <i
										class="fa fa-arrow-left"></i>
									</a>
								</div>
								<div class="btn-group hidden-phone inbox_more">
									<a class="btn mini blue" href="#" data-toggle="dropdown"> More
										<i class="fa fa-caret-down"> </i>
									</a>

									<ul class="dropdown-menu">
										<li><a id="markasread" style="cursor: pointer;"><i
												class="fa fa-pencil"></i> Mark as Read</a></li>
										<li><a id="markasunread" style="cursor: pointer;"><i
												class="fa fa-pencil"></i>Mark as Unread</a></li>

									</ul>
								</div>
								<div class="btn-group" id="delete">
									<a class="btn mini tooltips" id="delete" data-toggle="dropdown"
										data-placement="top" data-original-title="Delete"> <i
										class="fa fa-trash-o"></i>
									</a>
								</div>
								<div class="btn-group" id="deletes" style="display: none">
									<a class="btn mini tooltips" id="deletes"
										data-toggle="dropdown" data-placement="top"
										data-original-title="Delete"> <i class="fa fa-trash-o"></i>
									</a>
								</div>
								<div class="btn-group" id="refresh">
									<a class="btn mini tooltips" id="refresh"
										data-toggle="dropdown" data-placement="top"
										data-original-title="Refresh"> <i class=" fa fa-refresh"></i>
									</a>
								</div>
								<img src="../img/ajax-loader.gif" style="display: none"
									id="ajax_loader_refresh">

								<div class="btn-group">
									<div id="send" class="alert alert-info fade in"
										style="display: none;">
										<button data-dismiss="" class="close close-sm" type="button">
											<i class="fa fa-times"></i>
										</button>
										<span class="composed_reply"></span>
									</div>
								</div>
								<ul class="unstyled inbox-pagination">
                             <?php
																													$result = $message->getTotalCount ( $_SESSION ['login_id'] );
																													foreach ( $result as $row ) {
																														?>
                                 <li><span><span class="524">0</span>-<span
											class="523">10</span> of <?php echo $row['msg_id']?></span></li>
									<input type="hidden" name="mjk" id="mjk"
										value="<?php echo $row['msg_id']?>">
                                 <?php
																													}
																													?>
                                 <li><a class="np-btn left_btn"
										id="left_btn" data-value="20" data-send="10"><i
											class="fa fa-angle-left  pagination-left"></i></a></li>
									<li><a class="np-btn right_btn" id="right_btn" data-value="10"
										data-send="0"><i class="fa fa-angle-right pagination-right"></i></a>
									</li>
									<input type='hidden' id='current_page' />
									<input type='hidden' id='show_per_page' />
									<div id='page_navigation'></div>
								</ul>
							</div>

							<img src="../img/ajax-loader.gif" style="display: none"
								id="ajax_loader_getmessages">

							<table class="table table-inbox table-hover inboxs">
								<tbody class="getmessages">
								</tbody>

							</table>

							<div class="send" style="display: none; padding-top: 0px">
								<table class="table table-inbox table-hover send">
									<tbody class="sendmessages">


									</tbody>
								</table>
							</div>
						</div>


						<div class="composed" id="composed" style="display: none;">
							<div class="modal-dialog" style="width: 100%;">
								<div class="modal-content">

									<div class="modal-body">
										<form class="messagesystem" role="form">
											<input type="hidden" name="act" id="act"
												value="<?php echo base64_encode($_SESSION['company_id']."!sendMessage") ?>" />
											<div class="form-group">
												<label class="col-lg-2 control-label">To</label>
												<div class="col-lg-10">
													<select class="form-control to_message" id="to_message"
														name="recivers[]" multiple>
                                                        <?php
																																																								$result = $message->toUsers ( $_SESSION ['login_id'] );
																																																								foreach ( $result as $row ) {
																																																									echo "<option value='" . $row ['user_id'] . "'>" . $row ['user_name'] . "[" . $row ['user_id'] . "]</option>";
																																																								}
																																																								?>
                                                     </select>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-2 control-label">Subject</label>
												<div class="col-lg-10">
													<input type="text" class="form-control" id="subject"
														name="subject" placeholder="">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">Message</label>
												<div class="col-lg-10">
													<textarea name="body" id="body" class="form-control"
														cols="30" rows="10"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">Prority</label>
												<div class="col-lg-10">
													<select class="form-control" name="label" id="label"
														placeholder="">
														<option>Normal</option>
														<option>Important</option>
														<option>Urgent</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="col-lg-offset-2 col-lg-10" id="send_bottom">
													<button type="submit" id="changebutton"
														class="btn btn-send">Send</button>
													<button type="button" id="cancel_compose"
														class="btn btn-danger" style="margin-bottom: 10px;">Cancel</button>

												</div>

											</div>
										</form>
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->
						<input type="hidden" id="sender_ids"
							value="<?php echo $_SESSION['login_id']?>">
                          <?php
																										
																										if (isset ( $_REQUEST ['message_id'] )) {
																											?>
                          
                   <div class="inbox-body replys"
							style="display: none; padding-top: 0px;">
							<div class="getmessagebodyheaders"></div>
							<div class="getmessagebodys"></div>

							<div class="compose-btn pull-left" id="compose-btn"
								style="margin-bottom: 10px;">
								<a class="btn btn-sm btn-primary replys1" id="fa-reply"><i
									class="fa fa-reply"></i> Reply</a>
							</div>
							<div class="compose-reply1"
								style="display: none; margin-top: 15px;">
								<div class="modal-dialog" style="width: 100%;">
									<div class="modal-content">

										<div class="modal-body">
											<form class="replysystem" role="form">
												<input type="hidden" name="act"
													value="<?php echo base64_encode($_SESSION['company_id']."!replyMessage") ?>">
												<div class="form-group">
													<label class="col-lg-2 control-label">To</label>
													<div class="col-lg-10">
														<input type="text" class="form-control sendernames"
															name="sendernames" placeholder="" readonly> <input
															type="hidden" class="form-control sendernames1"
															name="sendernames1"
															value="<?php echo $_SESSION['login_id']?>">
													</div>
												</div>
												<input type="hidden" name="recivers[]" class="receiver1"
													value=""> <input type="hidden" name="subject"
													class="subject1" value=""> <input type="hidden"
													name="parentMsgId" class="parentMsgId" value=""> <input
													type="hidden" name="replylabel" class="label1" value="">
												<div class="form-group">
													<label class="col-lg-2 control-label">Message</label>
													<div class="col-lg-10">
														<textarea name="body" id="message_bodys"
															class="form-control message_body" cols="30" rows="10"></textarea>
													</div>
												</div>

												<div class="form-group">
													<div class="col-lg-offset-2 col-lg-10">
														<button type="submit" id="changereplybuttons"
															class="btn btn-send">Send</button>
														<button type="button" class="btn btn-danger cancel">Cancel</button>
													</div>
												</div>
											</form>

										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
							<!-- /.modal -->
						</div>
                          
                          	
                          	<?php
																										}
																										?>
                               
                         
                               <div class="inbox-body reply"
							style="display: none; padding-top: 0px;">
							<div class="getmessagebodyheader"></div>
							<div class="getmessagebody"></div>

							<div class="compose-btn pull-left" style="margin-bottom: 10px;">
								<a class="btn btn-sm btn-primary replys2" id="fa-reply"><i
									class="fa fa-reply"></i> Reply</a>
							</div>
							<div class="compose-reply2"
								style="display: none; margin-top: 15px;">
								<div class="modal-dialog" style="width: 100%;">
									<div class="modal-content">

										<div class="modal-body">
											<form id="replysystem" role="form">
												<input type="hidden" name="act"
													value="<?php echo base64_encode($_SESSION['company_id']."!replyMessage") ?>">
												<div class="form-group">
													<label class="col-lg-2 control-label">To</label>
													<div class="col-lg-10">
														<input type="text" class="form-control" name="sendernames"
															id="sendernames" placeholder="" readonly> <input
															type="hidden" class="form-control sendernames1"
															id="sendernames1" name="sendernames1"
															value="<?php echo $_SESSION['login_id']?>">

													</div>
												</div>
												<input type="hidden" name="recivers[]" id="receiver1"
													value=""> <input type="hidden" name="subject" id="subject1"
													value=""> <input type="hidden" name="parentMsgId"
													id="parentMsgId" value=""> <input type="hidden"
													name="replylabel" id="label1" value="">
												<div class="form-group">
													<label class="col-lg-2 control-label">Message</label>
													<div class="col-lg-10">
														<textarea name="body" id="message_body"
															class="form-control" cols="30" rows="10"></textarea>
													</div>
												</div>

												<div class="form-group">
													<div class="col-lg-offset-2 col-lg-10">
														<button type="submit" id="changereplybutton"
															class="btn btn-send">Send</button>
														<button type="button" class="btn btn-danger cancels">Cancel</button>
													</div>
												</div>
											</form>

										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
							<!-- /.modal -->
						</div>
				
				</div>
				</aside>
				</div>
				<!--mail inbox end-->
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

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<!--script for this page only-->
	<script src="../js/bootstrap-dialog.js"></script>
	<script src="../js/chosen.jquery.min.js"></script>

	<script>
 var messageId='<?php echo isset($_GET['message_id'])?$_GET['message_id']:0;?>';//jojo798
 var messageIdDecode='<?php echo isset($_GET['message_id'])?base64_decode($_GET['message_id']):0;?>';//jojo798
 var recipentIdDecode='<?php echo isset($_GET['message_id'])?base64_decode($_GET['recipient_id']):0;?>';//jojo798
	
 if(messageId!=0){
$('.composed').hide();
$('.trash').hide();		
$('.send').hide();
$('.inboxs').hide();
$(document).ready(function () {
var message_id = messageIdDecode;
var recipient_id = recipentIdDecode;
$('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
$('#compose,.in-send').addClass('inbox_head_header');
$('.in-inbox').addClass('inbox_menu_change');
	$('#inboxmessages').show();
	$('#inboxclose,#refresh,.chk-all').hide();
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {"recipient_id" :recipient_id, "message_id":message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!getMessage");?>'},	
        success: function (data) {
        	datajson = JSON.parse(data);
            if (datajson[0] == "success") {
            	$('.replys').fadeIn("fast");  
			var htmlsd;
			htmlsd = "";
			$('.subject').html(datajson[2].subject);
			$('.getmessagebodyheaders').html("");
			 for(var j=0;j<1;j++){
				 	$('.subject1').attr('value',datajson[2][j].subject);
					htmlsd +='<div class="getmessageids" style="padding:0px;"><div class="heading-inbox row"><div class="col-md-12 pull-left"><h4 class="subject_inbox">'+datajson[2][j].subject+'</h4></div></div>';
			
				$('.getmessagebodyheaders').append(htmlsd);
			 }
			 $('.getmessagebodys').html("");
				 for(var i=0;i<datajson[2].length;i++){	
					 var html;
						html="";
						if($('#sender_ids').val() == datajson[2][i].senderId)
						{
							var send = "me";
						}
						else
						{
							var send = datajson[2][i].senderName;
						}
						if($('#sender_ids').val() == datajson[2][i].recieverId)
						{
							var recieve = "me";
						}
						else
						{
							var recieve = datajson[2][i].receiverName;
						}
							
				if(datajson[2][i].parentMessageId==null){
				html +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+send+'</strong>to<strong class="me">'+recieve+'</strong><p class="date pull-right date_inbox">'+datajson[2][i].sentOn+'</p></div></div></div><div class="view-mail"><p class="body_inbox">'+datajson[2][i].body+'</p>';
			 }
				else if(datajson[2][i].parentMessageId!=null)
				{
					html +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+send+'</strong>to<strong class="me">'+recieve+'</strong><p class="date pull-right date_inbox">'+datajson[2][i].sentOn+'</p></div></div></div><div class="view-mail"><p class="body_inbox">'+datajson[2][i].body+'</p>';
				}
				else
				{
					html +='<div class="panel panel-default"><div class="panel-heading"><h5 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><strong class="sender_inbox">Me</strong>to<strong class="me">'+datajson[2][i].senderName+'</strong></a></h5></div><div id="collapseOne" class="panel-collapse collapse in" style="height: auto;"><div class="panel-body">'+datajson[2][i].body+'</div></div></div>';
				}
				if($('#sender_ids').val() == datajson[2][i].senderId)
				{
					var replysend = datajson[2][i].recieverId;
				}
				else
				{
					var replysend = datajson[2][i].senderId;
				}
				var str = datajson[2][i].senderId;
				var arr = replysend;
				$('.sendernames').attr('value',datajson[2][i].senderId);
				$('.receiver1').attr('value',arr);
				$('.label1').attr('value',datajson[2][i].label);
			
				if(datajson[2][i].parentMessageId!='')
				{
				$('.parentMsgId').attr('value',datajson[2][i].parentMessageId);
				}
				else
				{
					$('.parentMsgId').attr('value',datajson[2][i].messageId);
				}
				
				$('.getmessagebodys').append(html);  
			
					}
			 }else if (datajson[0] == "error") {
               }
        }
    });
});
 }if(window.location.href.indexOf("?") > -1)
 {
		bf_flag = 0;
 }
	else
	{
	
		$('#deletes').hide();
		$('#delete').show();
		$('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
		$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
		$('#compose,.in-send').addClass('inbox_head_header');
		$('.in-inbox').addClass('inbox_menu_change');
		bf_flag = 0;
		bf_flags = 1;
		$('#ajax_loader_getmessages').show();

		getMessages(0,10);
	    
	
	}
 
$(".replysystem").on('submit',function(e){
	 e.preventDefault();
	 if($('#message_bodys').val()=='')
		{
		}
	 else
	 {
 	 $.ajax({
             dataType: 'html',
             type: "POST",
             url: "../common/message.handle.php",
             cache: false,
             data: $('.replysystem').serialize(),
             beforeSend:function(){
                	$('#changereplybuttons').button('loading'); 
                 },
                 complete:function(){
                	 $('#changereplybuttons').button('reset');
                 },
             success: function (data) {
             	data = JSON.parse(data);
             	if (data[0] == "success") {
             		var phtml="";
             		phtml +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+$('.sendernames1').val()+'</strong>to<strong class="me">'+$('.receiver1').val()+'</strong><p class="date pull-right date_inbox"></p></div></div></div><div class="view-mail"><p class="body_inbox">'+$('#message_bodys').val()+'</p>';
             		$('.getmessagebodys').append(phtml);
             		$(".replysystem")[0].reset();
             		$('.compose-reply1').fadeToggle("slow");
                   }
             }
         });
	 }
});
$('#cancel_compose').click(function(e){
	e.preventDefault();
	$('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
	$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
	$('#compose,.in-send').addClass('inbox_head_header');
	$('.in-inbox').addClass('inbox_menu_change');
	$('.send,.composed,.reply,.getmessageid,.replys,#inboxmessages').hide();
	$('.inboxs,.top-nav-all,.chk-all').fadeIn("FAST");	
});
$('.in-inbox').click(function(e){
	e.preventDefault();
$('#deletes').hide();
$('#delete').show();
$('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
$('#compose,.in-send').addClass('inbox_head_header');
$('.in-inbox').addClass('inbox_menu_change');
if(window.location.href.indexOf("?") > -1)
{
	document.location.href = "messages.php";
}
else
{
$('.send,.composed,.reply,.getmessageid,.replys,#inboxmessages,#inboxmessages1').hide();
$('.inboxs,.top-nav-all,.chk-all,.inbox_more,#refresh').fadeIn("slow");
}
});
$('.in-send').click(function(e){
	e.preventDefault();
$('#deletes').show();
$('#delete').hide();
$('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
$('#compose,.in-inbox').addClass('inbox_head_header');
$('.in-send').addClass('inbox_menu_change');
$('.inboxs,.composed,.reply,.getmessageids,.replys,#inboxmessages,.inbox_more,#refresh').fadeOut(10);
$('.send,.top-nav-all,.chk-all').fadeIn("FAST");

});
$('#compose').click(function(e){
	e.preventDefault();
	$('#deletes').hide();
	$('#delete').show();
	$('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
	$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
	$('.in-send,.in-inbox').addClass('inbox_head_header');
	$('#compose').addClass('inbox_menu_change');
$('.send,.inboxs,.reply,.getmessageid,.replys,.top-nav-all,#inboxmessages,#inboxmessages1').fadeOut(10);
$('.composed').fadeIn("fast");	
});
$('.replys1').click(function (e){
	e.preventDefault();
$('.compose-reply1').fadeToggle("slow");
});
$('.replys2').click(function (e){
	e.preventDefault();
$('.compose-reply2').fadeToggle("slow");
});
$('.cancel').click(function (e){
	e.preventDefault();
$('.compose-reply1').fadeToggle("slow");
});
$('.cancels').click(function (e){
	e.preventDefault();
	$('.compose-reply2').fadeToggle("slow");
});

$('.backs').click(function(e){
	e.preventDefault();
	$('#inboxmessages1,.reply,.replys,.inbox_more,.chk-all,#refresh').hide();
	$('#inboxclose,.send').show();
	});
$(".mail-group-checkbox").change(function (e) {
	e.preventDefault();
$(".mail-checkbox").prop('checked', $(this).prop("checked"));
});
$("#checkds").click(function (e) {
	e.preventDefault();
$(".mail-checkbox").prop('checked', $(".mail-checkbox").prop("checked"));
});
$('.none').click(function (e) {
	e.preventDefault();
$('.mail-checkbox').prop('checked', false);
});
$(document).on('click', '.dont-show' ,function(){
	
	var i = $(this).closest('tr').addClass('unread');
    var message_id = $(this).data('value');
    var recipient_id = $(this).data('recepient');
    $('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
	$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
	$('#compose,.in-send').addClass('inbox_head_header');
	$('.in-inbox').addClass('inbox_menu_change');
	$('#inboxmessages').show();
	$('#inboxclose,#refresh,.chk-all').hide();
	$('#ajax_loader_getmessages').show();
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {"recipient_id" :recipient_id, "message_id":message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!getMessage");?>'},	
        success: function (data) {
        	datajson = JSON.parse(data);
            if (datajson[0] == "success") {
            	$('#ajax_loader_getmessages').hide();
            	$('.inboxs').fadeOut(10);
            	$('.send').fadeOut(10);
            	$('.reply').fadeIn("fast");  
			var htmlsd;
			htmlsd = "";
			$('#subject').html(datajson[2].subject);
			$('.getmessagebodyheader').html("");
			 for(var j=0;j<1;j++){
				 $('#subject1').attr('value',datajson[2][j].subject);
					htmlsd +='<div class="getmessageids" style="padding:0px;"><div class="heading-inbox row"><div class="col-md-12 pull-left"><h4 class="subject_inbox">'+datajson[2][j].subject+'</h4></div></div>';
				$('.getmessagebodyheader').append(htmlsd);
			 }
			 $('.getmessagebody').html("");
				 for(var i=0;i<datajson[2].length;i++){	
					 var html;
						html="";	
						if($('#sender_ids').val() == datajson[2][i].senderId)
						{
							var send = "me";
						}
						else
						{
							var send = datajson[2][i].senderName;
						}
						if($('#sender_ids').val() == datajson[2][i].recieverId)
						{
							var recieve = "me";
						}
						else
						{
							var recieve = datajson[2][i].receiverName;
						}
				if(datajson[2][i].parentMessageId==null){
				html +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+send+'</strong>to<strong class="me">'+recieve+'</strong><p class="date pull-right date_inbox">'+datajson[2][i].sentOn+'</p></div></div></div><div class="view-mail"><p class="body_inbox">'+datajson[2][i].body+'</p>';
			 }
				else if(datajson[2][i].parentMessageId!=null)
				{
					html +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+send+'</strong>to<strong class="me">'+recieve+'</strong><p class="date pull-right date_inbox">'+datajson[2][i].sentOn+'</p></div></div></div><div class="view-mail"><p class="body_inbox">'+datajson[2][i].body+'</p>';
				}
				else
				{
					html +='<div class="panel panel-default"><div class="panel-heading"><h5 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><strong class="sender_inbox">Me</strong>to<strong class="me">'+datajson[2][i].senderName+'</strong></a></h5></div><div id="collapseOne" class="panel-collapse collapse in" style="height: auto;"><div class="panel-body">'+datajson[2][i].body+'</div></div></div>';
				}
				if($('#sender_ids').val() == datajson[2][i].senderId)
				{
					var replysend = datajson[2][i].recieverId;
				}
				else
				{
					var replysend = datajson[2][i].senderId;
				}
				var str = datajson[2][i].senderId;
				var arr = replysend;
				$('#sendernames').attr('value',replysend);
				$('#receiver1').attr('value',arr);
				
				$('#label1').attr('value',datajson[2][i].label);
			
				if(datajson[2][i].parentMessageId!='')
				{
				$('#parentMsgId').attr('value',datajson[2][i].parentMessageId);
				}
				else
				{
					$('#parentMsgId').attr('value',datajson[2][i].messageId);
				}
				
				$('.getmessagebody').append(html);  
			
					}
			 
              
            }else if (datajson[0] == "error") {
                   
               }
        }
    });
});
$(document).on('click', '.dont-show1' ,function(){
	
	var i = $(this).closest('tr').addClass('unread');
    var message_id = $(this).data('value');
    var recipient_id = $(this).data('recepient');
	$('#compose,.in-send,.in-inbox').removeClass('inbox_head_header');
	$('#compose,.in-send,.in-inbox').removeClass('inbox_menu_change');
	$('#compose,.in-inbox').addClass('inbox_head_header');
	$('.in-send').addClass('inbox_menu_change');
	$('#inboxmessages1').show();
	$('#inboxclose,#refresh,.chk-all,#inboxmessages').hide();
	$('#ajax_loader_getmessages').show();
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {"recipient_id" :recipient_id, "message_id":message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!getMessage");?>'},	
        success: function (data) {
        	datajson = JSON.parse(data);
            if (datajson[0] == "success") {
            	$('#ajax_loader_getmessages').hide();
            	$('.inboxs').fadeOut(10);
            	$('.send').fadeOut(10);
            	$('.reply').fadeIn("fast");  
			var htmlsd;
			htmlsd = "";
			$('#subject').html(datajson[2].subject);
			$('.getmessagebodyheader').html("");
			 for(var j=0;j<1;j++){
				 $('#subject1').attr('value',datajson[2][j].subject);
					htmlsd +='<div class="getmessageids" style="padding:0px;"><div class="heading-inbox row"><div class="col-md-12 pull-left"><h4 class="subject_inbox">'+datajson[2][j].subject+'</h4></div></div>';
				$('.getmessagebodyheader').append(htmlsd);
			 }
			 $('.getmessagebody').html("");
				 for(var i=0;i<datajson[2].length;i++){	
					 var html;
						html="";
						if($('#sender_ids').val() == datajson[2][i].senderId)
						{
							var send = "me";
						}
						else
						{
							var send = datajson[2][i].senderName;
						}
						if($('#sender_ids').val() == datajson[2][i].recieverId)
						{
							var recieve = "me";
						}
						else
						{
							var recieve = datajson[2][i].receiverName;
						}	
				if(datajson[2][i].parentMessageId==null){
				html +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+send+'</strong>to<strong class="me">'+recieve+'</strong><p class="date pull-right date_inbox">'+datajson[2][i].sentOn+'</p></div></div></div><div class="view-mail"><p class="body_inbox">'+datajson[2][i].body+'</p>';
			 }
				else if(datajson[2][i].parentMessageId!=null)
				{
					html +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+send+'</strong>to<strong class="me">'+recieve+'</strong><p class="date pull-right date_inbox">'+datajson[2][i].sentOn+'</p></div></div></div><div class="view-mail"><p class="body_inbox">'+datajson[2][i].body+'</p>';
				}
				else
				{
					html +='<div class="panel panel-default"><div class="panel-heading"><h5 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><strong class="sender_inbox">Me</strong>to<strong class="me">'+datajson[2][i].senderName+'</strong></a></h5></div><div id="collapseOne" class="panel-collapse collapse in" style="height: auto;"><div class="panel-body">'+datajson[2][i].body+'</div></div></div>';
				}
				if($('#sender_ids').val() == datajson[2][i].senderId)
				{
					var replysend = datajson[2][i].recieverId;
				}
				else
				{
					var replysend = datajson[2][i].senderId;
				}
				var str = datajson[2][i].senderId;
				var arr = replysend;
				$('#sendernames').attr('value',datajson[2][i].senderName);
				$('#receiver1').attr('value',arr);
				
				$('#label1').attr('value',datajson[2][i].label);
			
				if(datajson[2][i].parentMessageId!='')
				{
				$('#parentMsgId').attr('value',datajson[2][i].parentMessageId);
				}
				else
				{
					$('#parentMsgId').attr('value',datajson[2][i].messageId);
				}
				
				$('.getmessagebody').append(html);  
			
					}
			 
              
            }else if (datajson[0] == "error") {
                   
               }
        }
    });
});
$(".read").click(function (e) {
	e.preventDefault();
$('.mail_read').prop('checked', true);
});
$(".unreadmessage").click(function (e) {
	e.preventDefault();
$('.mail_unread').prop('checked', true);
});
$('#to_message').chosen();

$("#replysystem").on('submit',function(e){
	
	e.preventDefault();

	if($('#message_body').val()=='')
	{
	}
	else
	{
	 
  	 $.ajax({
              dataType: 'html',
              type: "POST",
              url: "../common/message.handle.php",
              cache: false,
              data: $('#replysystem').serialize(),
              beforeSend:function(){
                 	$('#changereplybutton').button('loading'); 
                  },
                  complete:function(){
                 	 $('#changereplybutton').button('reset');
                  },
              success: function (data) {
              	data = JSON.parse(data);
              	if (data[0] == "success") {
              		var html="";
              		html +='<div class="sender-info"><div class="row"><div class="col-md-12"><strong class="sender_inbox">'+$('#sendernames1').val()+'</strong>to<strong class="me">'+$('#receiver1').val()+'</strong><p class="date pull-right date_inbox"></p></div></div></div><div class="view-mail"><p class="body_inbox">'+$('#message_body').val()+'</p>';
              		$('.getmessagebody').append(html);
              		$("#replysystem")[0].reset();
              		$('.compose-reply2').fadeToggle("slow");
                    }
              }
          });
	}
});

$('.is_read').click(function (e)
		{
	e.preventDefault();
	var message_id = $(this).data('value');
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!readMessage");?>'},	
        success: function (data) {
            data = JSON.parse(data);
            if (data[0] == "success") {
                var array = message_id.toString().split(",") ;
                $.each(array,function(i){
            	$('#'+array[i]).addClass('unread');
            	$('#'+array[i]).removeClass('unreads');
                });
                }else if (data[0] == "error") {
                   
               }
        }
    });
		});
$('.read_on').hover(function (e){
	e.preventDefault();
var data = $(this).data('value');
$(this).attr('data-tooltip',data);
	
});

function getMessages(startlimit,endlimit)
{

	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {startlimit,endlimit,act: '<?php echo base64_encode($_SESSION['company_id']."!getMessageNotification");?>'},	
        success: function (data) {
            data = JSON.parse(data);
            $('#ajax_loader_getmessages').hide();
            $('#ajax_loader_refresh').hide();
            if (data[0] == "success") {
            	
            	$('.getmessages').html("");
            	var count = 0;
            	for(var i=0;i<data[2].length;i++){
    				var html;
    				html="";
    				
                  
    				if(data[2][i].parentMessageId!='' && data[2][i].parentMessageId!=null)
    				{
        				var msg = data[2][i].parentMessageId;
        				
    				}
    				else
    				{
        				var msg = data[2][i].messageId;
    				}
    				if(data[2][i].label=='Important')
                	{
                		var label = "danger";
                	}
    				else if(data[2][i].label=='Urgent')
                	{
                		var label = "danger";
                	}
                	else if(data[2][i].label=='Normal')
                	{
                		var label = "success ";
                	}
                	if(data[2][i].isRead==1)
                	{
                    	html +='<tr class="unread" id="'+data[2][i].messageId+'" ><td class="inbox-small-cells"><input type="checkbox" name="mailcheckbox[]" class="mail-checkbox mail_read" value="'+data[2][i].messageId+'"></td><td class="view-message dont-show"  data-value="'+msg+'" data-recepient="'+data[2][i].senderId+'" style="font-weight: 400">'+data[2][i].senderName+'<span class="label label-'+label+' pull-right">'+data[2][i].label+'</span></td><td class="view-message dont-show" data-value="'+msg+'" data-recepient="'+data[2][i].senderId+'">'+data[2][i].subject+'.-<p style="color:#777;display:inline-block;font-weight: 200;">'+data[2][i].body.substr(0,70)+'</p></td><td class="view-message  text-right dont-show" data-value="'+msg+'" data-recepient="'+data[2][i].senderId+'">'+data[2][i].sentOn+'</td></tr>';
                	}
                	else
                	{
                		html +='<tr class="unreads" id="'+data[2][i].messageId+'"><td class="inbox-small-cells"><input type="checkbox" name="mailcheckbox[]" class="mail-checkbox mail_unread" value="'+data[2][i].messageId+'"></td><td class="view-message dont-show"  data-value="'+msg+'" data-recepient="'+data[2][i].senderId+'" style="font-weight: 400">'+data[2][i].senderName+'<span class="label label-'+label+' pull-right">'+data[2][i].label+'</span></td><td class="view-message dont-show" data-value="'+msg+'" data-recepient="'+data[2][i].senderId+'">'+data[2][i].subject+'.-<p style="color:#777;display:inline-block;font-weight: 200;">'+data[2][i].body.substr(0,70)+'</p></td><td class="view-message  text-right dont-show" data-value="'+msg+'" data-recepient="'+data[2][i].senderId+'">'+data[2][i].sentOn+'</td></tr>';
                	}
                	
                	$('.getmessages').append(html);
    				count++;
            	}
            	
                }else if (data[0] == "error") {
                   var html;
                   html ="";
                   html +='<tr><td colspan="7">No Message Found</td></tr>';
                   $('.getmessages').append(html);
               }
        }
    });
}

$('#refresh').click(function(e){
	e.preventDefault();
	$('#ajax_loader_refresh').show();
	getMessages(0,10);
	
});
function getSend()
{
	
	var startlimit = 0;
	var endlimit = 50;
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {startlimit,endlimit,act: '<?php echo base64_encode($_SESSION['company_id']."!getSentMails");?>'},	
        success: function (data) {
            data = JSON.parse(data);
            $('#ajax_loader_getmessages').hide();
            if (data[0] == "success") {
            	
              $('.sendmessages').html("");
              var count = 0;
            	for(var i=0;i<data[2].length;i++){ 
            
        				var msg = data[2][i].messageId;
    				
                	var html;
    				html="";
    				if(data[2][i].label=='Important')
                	{
                		var label = "danger";
                	}
    				else if(data[2][i].label=='Urgent')
                	{
                		var label = "danger";
                	}
                	else if(data[2][i].label=='Normal')
                	{
                		var label = "success";
                	}
                	if(data[2][i].isRead=1)
                	{
                    	html +='<tr class="unread" id="'+data[2][i].messageId+'" ><td class="inbox-small-cells"><input type="checkbox" name="mailcheckbox[]" class="mail-checkbox mail_read" value="'+data[2][i].messageId+'"></td><td class="view-message dont-show1" data-value="'+msg+'" data-recepient="'+data[2][i].recieverId+'" style="font-weight: 400">To: '+data[2][i].recieverId+'<span class="label label-'+label+' pull-right">'+data[2][i].label+'</span></td><td class="view-message dont-show1" data-value="'+msg+'" data-recepient="'+data[2][i].recieverId+'">'+data[2][i].subject+'.-<p style="color:#777;display:inline-block;font-weight: 200;">'+data[2][i].body.substr(0,70)+'</p></td><td class="view-message  text-right dont-show1" data-value="'+msg+'" data-recepient="'+data[2][i].recieverId+'">'+data[2][i].sentOn+'</td></tr>';
                	}
                	else
                	{
                		html +='<tr class="unreads" id="'+data[2][i].messageId+'" ><td class="inbox-small-cells"><input type="checkbox" name="mailcheckbox[]" class="mail-checkbox mail_unread" value="'+data[2][i].messageId+'"></td><td class="view-message dont-show1" data-value="'+msg+'" data-recepient="'+data[2][i].recieverId+'" style="font-weight: 400">To:'+data[2][i].recieverId+'<span class="label label-'+label+' pull-right">'+data[2][i].label+'</span></td><td class="view-message dont-show1" data-value="'+msg+'" data-recepient="'+data[2][i].recieverId+'">'+data[2][i].subject+'.-<p style="color:#777;display:inline-block;font-weight: 200;">'+data[2][i].body.substr(0,70)+'</p></td><td class="view-message  text-right dont-show1" data-value="'+msg+'" data-recepient="'+data[2][i].recieverId+'">'+data[2][i].sentOn+'</td></tr>';
                	}
                	
                	$('.sendmessages').append(html);
                	
    				count++;
            	}
            	$('#total').html(count);
                }else if (data[0] == "error") {
                	var html;
                    html ="";
                    html +='<tr><td colspan="7">No Message Found</td></tr>';
                    $('.sendmessages').append(html);
               }
        }
    });
    
}
$('.in-send').click(function (e){
	e.preventDefault();
	$('#delete').hide();
	$('#deletes').show();
	$('.chk-all').show();
	if(bf_flag == 0)
	{
	$('#ajax_loader_getmessages').show();
	getSend();
	
	}
	bf_flag = 1;
});
$(".messagesystem").on('submit',function(e){
	 e.preventDefault();
  	 $.ajax({
              dataType: 'html',
              type: "POST",
              url: "../common/message.handle.php",
              cache: false,
              data: $('.messagesystem').serialize(),
              beforeSend:function(){
                 	$('#changebutton').button('loading'); 
                  },
                  complete:function(){
                 	 $('#changebutton').button('reset');
                  },
              success: function (data) {
              	data = JSON.parse(data);
              	if (data[0] == "success") {
              	
              	$('.messagesystem')[0].reset();
              	$('#autoship_option').val('').trigger('chosen:updated');
              $('.composed').fadeOut("fast");
              	$('.inboxs,.top-nav-all,.inbox_more,#refresh,.chk-all').fadeIn('fast');
              	$('#send').fadeIn(300);
         	  $('.composed_reply').html(' Message Send Successfully');
         	 $('#send').fadeOut(5000);
         	  $('.close').click(function(e){
         		  $('#send').fadeOut(300);
         	  });  
         	
            	getSend();
         	 
                  }
              }
          });
 
});
$('#markasread').click(function(e){
	e.preventDefault();
	var message_id = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value;
	}).join(',');
	if(message_id=='')
	{
	}
	else
	{
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!readMessage");?>'},	
        success: function (data) {
            data = JSON.parse(data);
            if (data[0] == "success") {
            	$('.mail_unread').addClass('mail_read');
            	$('.mail_unread').removeClass('mail_unread');
            	$('.composed_reply').html("");
            	var array = message_id.toString().split(",") ;
            	var count = 0;
            	  $.each(array,function(i){
                  	$('#'+array[i]).addClass('unread');
                  	$('#'+array[i]).removeClass('unreads');
                  	count++;
                      });
                  $('#send').fadeIn(300);
            	  $('.composed_reply').html(count+' Message Has been Marked as Read Successfully');
            	  $('#send').fadeOut(5000);
            	  $('.close').click(function(e){
            		  $('#send').fadeOut(300);
            	  });
            }else if (data[0] == "error") {
                   

            }
        }
    });
	}
});	
$('#markasunread').click(function(e){
	e.preventDefault();
	var message_id = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value;
	}).join(',');
	if(message_id=='')
	{
	}
	else
	{
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!unreadMessage");?>'},	
        success: function (data) {
            data = JSON.parse(data);
            if (data[0] == "success") {
            	$('.mail_read').addClass('mail_unread');
            	$('.mail_read').removeClass('mail_read');
            	var array = message_id.toString().split(",") ;
            	var count = 0;
            	  $.each(array,function(i){
                  	$('#'+array[i]).addClass('unreads');
                  	$('#'+array[i]).removeClass('unread');
                  	count++;
                      });
            	  $('#send').fadeIn(300);
            	  $('.composed_reply').html(count+' Message Has been Marked as Unread Successfully');
            	  $('#send').fadeOut(5000);
            	  $('.close').click(function(e){
            		  $('#send').fadeOut(300);
            	  });
            	
            }else if (data[0] == "error") {
                   

            }
        }
    });
	}
});
$('#delete').click(function(e){
	e.preventDefault();
	var message_id = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value;
	}).join(',');
	$('#ajax_loader_refresh').show();
	if(message_id=='')
	{
		$('#ajax_loader_refresh').hide();
	}
	else
	{
 BootstrapDialog.show({
 title:'Confirmation',
	        message: 'Are Sure you want Delete this Message</strong>',
	        buttons: [{
	            label: 'Yes',
	            cssClass: 'btn-sm btn-success',
	            autospin: true,
	            action: function(dialogRef){
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!deleteMessage");?>'},	
        complete:function(){
            dialogRef.close();
         },
        success: function (data) {
            data = JSON.parse(data);
           
            if (data[0] == "success") {
            	  BootstrapDialog.alert(data[1]);
            	  $('#ajax_loader_refresh').hide();
            	var array = message_id.toString().split(",") ;
            	var count = 0;
            	  $.each(array,function(i){
                  	$('#'+array[i]).remove();
                  	count++;
                      });
            	  $('#send').fadeIn(300);
            	  $('.composed_reply').html(count+' Message Has been Deleted Successfully');
            	  $('#send').fadeOut(5000);
            	  $('.close').click(function(e){
            		  $('#send').fadeOut(300);
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
$('#deletes').click(function(e){
	e.preventDefault();
	var message_id = jQuery.map($(':checkbox[name=mailcheckbox\\[\\]]:checked'), function (n, i) {
	    return n.value;
	}).join(',');
	$('#ajax_loader_refresh').show();
	if(message_id=='')
	{
		$('#ajax_loader_refresh').hide();
	}
	else
	{
 BootstrapDialog.show({
 title:'Confirmation',
	        message: 'Are Sure you want Delete this Message</strong>',
	        buttons: [{
	            label: 'Yes',
	            cssClass: 'btn-sm btn-success',
	            autospin: true,
	            action: function(dialogRef){
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/message.handle.php",
        cache: false,
        data: {message_id,act: '<?php echo base64_encode($_SESSION['company_id']."!deleteSendMessage");?>'},	
        complete:function(){
            dialogRef.close();
         },
        success: function (data) {
            data = JSON.parse(data);
           
            if (data[0] == "success") {
            	  BootstrapDialog.alert(data[1]);
            	  $('#ajax_loader_refresh').hide();
            	var array = message_id.toString().split(",") ;
            	var count = 0;
            	  $.each(array,function(i){
                  	$('#'+array[i]).remove();
                  	count++;
                      });
            	  $('#send').fadeIn(300);
            	  $('.composed_reply').html(count+' Message Has been Deleted Successfully');
            	  $('#send').fadeOut(5000);
            	  $('.close').click(function(e){
            		  $('#send').fadeOut(300);
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
$('#back').click(function(e){
	e.preventDefault();
	if(window.location.href.indexOf("?") > -1)
	{
	document.location.href = "messages.php";
	}
	else
	{
$('#inboxmessages,.reply,.replys').hide();
$('#inboxclose,#refresh,.chk-all,.inboxs').show();
	}
});
$('.left_btn').click(function(e){
	e.preventDefault();
	 if(bf_flags == '1')
     {
			
			
     }
	 else
	 {

		   $(".right_btn").attr("disabled",false);
			
	
	$('.right_btn').show();
	var counts = $(this).data('value');
	var sends = $(this).data('send');
	var adds = counts - 10;
	var limits = sends - 10;
	
	
		$('#ajax_loader_getmessages').show();
	if(adds == '10' && limits == '0')
	{
		limitsd = '0';
		endlimits = '10';	 
        bf_flags = '1';	
	}
	else
	{
		endlimits = adds;
		  
	}
	sendlimit = limitsd;
    endlimit = endlimits;
	getMessages(sendlimit,endlimit);
	var car =	$(this).data('value',adds);
	var cars =	$(this).data('send',limits);
	$('.524').html(limitsd);
	$('.523').html(endlimits);
	$('#right_btn').data('value',10);
	$('#right_btn').data('send',0);
	 }
	});
$('.right_btn').click(function(e){
	e.preventDefault();
	   if($("#right_btn").attr("disabled")=="disabled")
       {
		   
		   
       }  
	   else
	   {
		   
	$('#ajax_loader_getmessages').show();
	var count = $(this).data('value');
	var send = $(this).data('send');
	var add = 10+count;
	if($('#mjk').val() < 10)
	{
		$('#ajax_loader_getmessages').hide();
		
	}
	else
	{
	if($('#mjk').val() < add)
	{
		endlimits = $('#mjk').val();
		$("#right_btn").attr("disabled","disabled");
		bf_flags = 0;
	        
	}
	else
	{
		endlimits = add;
		$("#right_btn").prop("disabled", false);
		bf_flags = 0;
		
	}
	var limit = 10+send;
	sendlimit = limit;
   endlimit = endlimits;

   	getMessages(sendlimit,endlimit);
   	var car =	$(this).data('value',add);
	var cars =	$(this).data('send',limit);	
	$('#left_btn').data('value',20);
	$('#left_btn').data('send',10);
	$('.523,.524').html("");
	 $('.524').html(limit);
	   $('.523').html(endlimits);
	   }
	   }
 	});

    </script>
</body>
</html>
