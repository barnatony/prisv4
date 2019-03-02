<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Basstechs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Ticket</title>
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

<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
<style>
.alert {
	padding-top: 2px !important;
	padding-bottom: 1px !important;
	margin-bottom: 0px;
}

.form-control {
	margin-bottom: 10px;
}

#ajax_loader_ticket {
	display: block;
	margin: 0 auto;
}

.bio-graph-info {
	background: #FAFAFA;
}

.bio-graph-heading {
	background: #42A5F5;
}
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
				<!-- page start-->
				<section class="panel">
					<header class="panel-heading">
						<span class="ticket_list" style="cursor: pointer;"> Tickets</span>
						<div class="btn-group">
							<div id="send" class="alert alert-info fade in"
								style="display: none;">
								<button data-dismiss="" class="close close-sm" type="button">
									<i class="fa fa-times"></i>
								</button>
								<span class="composed_reply">Ticket</span>
							</div>
						</div>
						<?php 
						$stmt = "SELECT IF(SUBSTRING_INDEX(avg_time,'.',1)!=0,SUBSTRING_INDEX(avg_time,'.',1),avg_time) avg_time FROM (
								SELECT SUBSTRING_INDEX(SEC_TO_TIME( SUM( TIME_TO_SEC( time_taken ) ) ),':',1)/(SELECT COUNT(ticket_id) FROM "  . MASTER_DB_NAME . ".tickets WHERE status='CLOSED' AND company_id='" . $_SESSION ['company_id'] . "') avg_time FROM (
								SELECT t.status,TIMEDIFF(t.updated_on,t.created_on) time_taken
								FROM "  . MASTER_DB_NAME . ".tickets t
								INNER JOIN company_details cd ON t.company_id = cd.company_id AND info_flag='A'
								WHERE t.company_id = '" . $_SESSION ['company_id'] . "' AND t.creator_id = '" . $_SESSION ['login_id'] . "' AND status='CLOSED' )z)q";
						
						$stmt1= mysqli_query( $conn,$stmt);
						$result = mysqli_fetch_array ( $stmt1, MYSQLI_ASSOC );
						$avg_time = $result['avg_time'];
						?>
						<span class="pull-right"> 
						<a  class="btn btn-xs btn-danger hide" type="button" id="back-botton" >
								Back</a>
						<a id="raiseTicket"
							class=" btn btn-success btn-xs">Raise Ticket</a>
						</span>
						<span id=avgTime>
							<span class="pull-right" style="margin-right: 10px;font-size: 12px;margin-top: 5px;">(Avg response)</span>
							<span class="pull-right" style="margin-right: 5px;"><?php echo $avg_time ?> hrs</span>
						</span>
					</header>

					<table class="table table-hover p-table" id="ticket_table">
						<thead>
							<tr>
								<th>TicketId</th>
								<th>Category</th>
								<th>Subject</th>
								<th>Priority</th>
								<th>Status</th>
								<th>Created-On</th>
								<th>Updated-On</th>
								<th>Response Time</th>
								<th>To Do</th>
							</tr>
						</thead>
						<tbody class="view_ticket_table">

						</tbody>
						<img src="../img/ajax-loader.gif" style="display: none"
							id="ajax_loader_ticket" />
					</table>

					<div class="ticket_system_details" style="display: none">
						<div class="row">
							<div class="col-md-12">
								<section class="panel">
									<div class="bio-graph-heading project-heading">
										<strong class="ticket_subject"></strong>
									</div>
									<div class="panel-body bio-graph-info">
										<!--<h1>New Dashboard BS3 </h1>-->
										<div class="row p-details">
											<div class="bio-row">
												<p>
													<span class="bold">Created by </span>: <span
														class="ticket_createdby"></span>
												</p>
											</div>
											<div class="bio-row">
												<p>
													<span class="bold">Status </span>: <span
														class="ticket_status"></span>
												</p>
											</div>
											<div class="bio-row">
												<p>
													<span class="bold">Created </span>: <span
														class="ticket_created"></span>
												</p>
											</div>
											<div class="bio-row">
												<p>
													<span class="bold">Last Updated</span>: <span
														class="ticket_updated"></span>
												</p>
											</div>
											<!-- div class="bio-row">
												<p>
													<span class="bold">Company </span>: <a
														class="ticket_company">Bassbiz</a>
												</p>
											</div -->
											<div class="bio-row">
												<p>
													<span class="bold">Priority </span>: <span
														class="ticket_priority"></span>
												</p>
											</div>
											<div class="col-lg-12">
												<p style="text-align: justify">
													<span class="bold"><b>Description:</b></span>&nbsp;<span
														class="ticket_desc"></span>
												</p>
												<p style="text-align: justify">
													<span class="bold"><b>Attachments:</b></span>
												</p>
												<ul class="list-unstyled p-files attachment_ticket">

												</ul>
											</div>
										</div>

									</div>

								</section>

								<section class="panel">
									<header class="panel-heading">
										Actions
										<div class="pull-right" style="margin-bottom: 10px;">
											<div class="reply_fg"></div>
											<div class="reopen_fg hide" id="reopen">
												<a data-toggle="modal" href="#myModal" class=" btn btn-warning btn-xs">
													<i class="fa fa-external-link"></i> &nbsp;Re-open</a>
											</div>
										</div>
									</header>

									<div class="panel-body">
										<table class="table table-hover p-table">
											<thead>
												<tr>
													<th>Ticket Id</th>
													<th>Created By</th>
													<th>Subject</th>
													<th>Description</th>
													<th>Updated On</th>
												</tr>
											</thead>
											<tbody class="ticket_view_all">

											</tbody>
										</table>

									</div>

								</section>

							</div>
						</div>
					</div>
					<div class="raise_ticket" style="display: none">
						<div class="panel-body">

							<form class="raiseticketsystem" role="form">
								<input type="hidden" name="act" id="act"
									value="<?php echo base64_encode($_SESSION['company_id']."!raiseTicket") ?>" />
								<div class="form-group">
									<label class="col-lg-2 control-label">Category</label>
									<div class="col-lg-10">
										<select class="form-control" name="category" id="category">
											<option value="General">General</option>
											<option value="Technical">Technical</option>
											<option value="Functional">Functional</option>
											<option value="Design">Design</option>
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
									<label class="col-lg-2 control-label">Description</label>
									<div class="col-lg-10">
										<textarea name="description" id="description"
											class="form-control" cols="30" rows="10"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-2 control-label">Attachment</label>
									<div class="row fileupload-buttonbar">
										<div class="col-lg-6">
											<span class="btn btn-success btn-sm  fileinput-button attach">
												<i class="glyphicon glyphicon-plus"></i> <span>Attachmnet</span>
												<input type="file" class="form-control" id="attachment"
												name="attachment">

											</span>
										</div>
									</div>

								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Priority</label>
									<div class="col-lg-10">
										<select class="form-control" name="priority" id="priority">
											<option value="NORMAL">Normal</option>
											<option value="HIGH">High</option>
											<option value="LOW">Low</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10" id="send_bottom">
										<button type="submit" id="changebutton" class="btn btn-send">Send</button>
										<button type="button" id="cancel_ticket"
											class="btn btn-danger" style="margin: 10px;">Cancel</button>

									</div>

								</div>
							</form>
						</div>
					</div>
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
						aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog" style="width: 50%;">
							<div class="modal-content">

								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"
										aria-hidden="true">&times;</button>
									<h4 class="modal-title">Reply</h4>
								</div>

								<div class="modal-body">
									<form class="reply_ticket_system" role="form">
										<input type="hidden" name="act"
											value="<?php echo base64_encode($_SESSION['company_id']."!replyTicket") ?>">
										<input type="hidden" name="ticketId" id="ticketId" value=""> <input
											type="hidden" name="receiverId" id="receiverId" value=""> <input
											type="hidden" name="status" id="status" value=""> <input
											type="hidden" name="subject_ticket" id="subject_ticket"
											value=""> <input type="hidden" name="creator_ticket"
											id="creator_ticket" value="">
										<div class="form-group">
											<label class="col-lg-2 control-label">Description</label>
											<div class="col-lg-10">
												<textarea name="description" id="description_ticketd"
													class="form-control" cols="30" rows="10"></textarea>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-2 control-label">Attachment</label>
											<div class="row fileupload-buttonbar">
												<div class="col-lg-6">
													<span
														class="btn btn-success btn-sm  fileinput-button attach"> <i
														class="glyphicon glyphicon-plus"></i> <span>Attachmnet</span>
														<input type="file" class="form-control" id="attachment"
														name="attachment" placeholder="">
													</span>
												</div>
											</div>

										</div>

										<button type="submit" id="changebuttons" class="btn btn-send">Send</button>
								
								</div>
								</form>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
				</section>
				</div>
				</div>
				</div>

			</section>
			<!-- page end-->
		</section>
	</section>
	<!--main content end-->


	<!--footer start-->
<?php include 'footer.php'?>
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
	<script type="text/javascript">

$(document).ready(function () {
	var avgtime = <?php echo (isset($avg_time)?$avg_time:0)?>;
	if(avgtime<=0)
		$('#avgTime').hide();	 
	
});

$(document).on('click', '.view_ticket' ,function(e){
	 e.preventDefault();
	$('#raiseTicket,#avgTime').hide();	 
	$('#back-botton').removeClass('hide');
	$('#ticket_table').hide();
	var ticket_Id = $(this).data('value');
	$('#ajax_loader_ticket').show();
	
$.ajax({
	dataType: 'html',
	type: "POST",
    url: "../common/ticket.handle.php",
    cache: false,
    data: {ticket_Id,act: '<?php echo base64_encode($_SESSION['company_id']."!viewTicket");?>'},	
    success: function (data) {
        data = JSON.parse(data);
        $('#ajax_loader_ticket').hide();
        $('.ticket_system_details').show();
        
        if (data[0] == "success") {
            $('.reply_fg').html("");
       	 for(var j=0;j<1;j++){
			 $('.ticket_subject').html(data[2][j].subject);
			 $('.ticket_createdby').html(data[2][j].creatorId);
			 $('#ticketId').attr('value',data[2][j].ticketId);
			 $('#receiverId').attr('value',data[2][j].receiver);
			 $('#status').attr('value',data[2][j].status);
			 $('#subject_ticket').attr('value',data[2][j].subject);
			 $('#creator_ticket').attr('value',data[2][j].creatorId);
			 if(data[2][j].status == "OPEN")
			 {
			 $('.ticket_status').html('<span class="label label-primary">'+data[2][j].status+'</span>');
			 var jsp = '<a  data-toggle="modal" href="#myModal" class=" btn btn-success btn-xs"><i class="fa fa-reply-all"></i> &nbsp;Reply</a> ';
				 $('.reply_fg').append(jsp);
				 //$('#reopen').removeClass('show');
			 }
			 else if(data[2][j].status == "CLOSED")
			 {
				 $('.ticket_status').html('<span class="label label-default">'+data[2][j].status+'</span>');
				 $('.reply_fg').html('');
				 //$('#reopen').addClass('show');
						 }
			 $('.ticket_created').html(data[2][j].createdOn);
			 
			 if(data[2][j].priority == "NORMAL")
			 {
				 priority = "text-success";
			 }
			 else if(data[2][j].priority == "HIGH")
			 {
				 priority = "text-danger";
			 }
			 else
			 {
				 priority = "text-info"; 
			 }
			 $('.ticket_priority').html('<i class=" fa fa-circle '+priority+'"></i> '+data[2][j].priority+'');
			 $('.ticket_desc').html(data[2][j].desc);
			 $('.ticket_list').html('Tickets > '+data[2][j].subject);
		 }
       	$('.ticket_view_all').html("");
       	$('.attachment_ticket').html("");
       	
       	for(var i=0;i<(data[2].length-1);i++){	

           	if(data[2][i].lastUpdated == "0000-00-00 00:00:00")
           	{
           		$('.ticket_updated').html("<br>Not Updated<br/>");
           		$('.ticket_updated').css('margin-top','18px');
           		var updatedons = "Not Updated";
           	}
           	else
           	{
       		$('.ticket_updated').html(data[2][i].lastUpdated);
       		$('.ticket_updated').css('margin-top','0px');
       		var updatedons = data[2][i].lastUpdated;
           	}
       		var html = "";
       		var phtml = "";
       		if(data[2][i].status == "OPEN"){
                label = "label-primary";
       var jsp = '<a  data-toggle="modal" href="#myModal" class=" btn btn-success btn-xs"><i class="fa fa-reply-all"></i> &nbsp;Reply</a> ';
       $('.reply_fg').html(jsp);
      
            }
            else if(data[2][i].status == "CLOSED"){
                label = "label-default";
                $('.reply_fg').html("");
            }  
       		html +='<tr><td>'+data[2][i].ticketId+'</td><td>'+data[2][i].creatorId+'</td><td>'+data[2][i].subject+'</td><td>'+data[2][i].desc+'</td><td><a>'+updatedons+'</a></td></tr>';
       		$('.ticket_view_all').append(html);
       		if(data[2][i].attach != 'Nil' && data[2][i].attach != null)
       		{
            phtml +='<li><i class="fa fa-picture-o"></i>&nbsp;<a href="'+data[2][i].attach+'" target="foo">Attachment</a></li>';
       		}
       		
       	           		
       		$('.attachment_ticket').append(phtml);
       	}
       	if(data[2][i] !=0){
       		$('#reopen').addClass('show');
       	}else{
           	$('#reopen').removeClass('show');
       	}
     }
    else if (data[0] == "error") {
  }
    }
});
});
$('.ticket_list,#back-botton').click(function(e){
	 e.preventDefault();
	$('.ticket_list').html('Tickets');
	$('.ticket_system_details,.raise_ticket').hide();
	$('#back-botton').addClass('hide');
	$('#ticket_table,#avgTime,#raiseTicket').show();
	$('#reopen').removeClass('show');
});
$('#cancel_ticket').click(function(e){
	 e.preventDefault();
	$('.ticket_list').html('Tickets');
	$('.raise_ticket').hide();
	$('#ticket_table').show();	
});
$('#raiseTicket').click(function(e){
	 e.preventDefault();
$('.ticket_list').html('Tickets > Raise Ticket');
$('#ticket_table,.ticket_system_details').hide();
$('.raise_ticket').show();	
$('#avgTime').hide();
});

$(document).ready(function () {
	bf_flag = 0;
	var startlimit = 0;
	var endlimit = 50;
	$('#ajax_loader_ticket').show();
	$.ajax({
		dataType: 'html',
		type: "POST",
        url: "../common/ticket.handle.php",
        cache: false,
        data: {startlimit,endlimit,act: '<?php echo base64_encode($_SESSION['company_id']."!viewTickets");?>'},	
        success: function (data) {
            data = JSON.parse(data);
            
            $('#ajax_loader_ticket').hide();
            if (data[0] == "success") {
            	$('.view_ticket_table').html("");
            	for(var i=0;i<data[2].length;i++){
                if(data[2][i].status == "OPEN")
                {
                    label = "label-primary";
                }
                else if(data[2][i].status == "CLOSED")
                {
                    label = "label-default";
                }    
                if(data[2][i].lastUpdated == "0000-00-00 00:00:00")
               	{
                	lastUpdated = "Not Updated";
               	}
                else
                {
                	lastUpdated =	data[2][i].lastUpdated;
                }
               	                
            	var html = "";
            	html +='<tr><td class="p-name"><a>'+data[2][i].ticketId+'</a></td><td class="p-team"><p>'+data[2][i].category+'</p></td><td class="p-name"><a >'+data[2][i].subject+'</a></td><td><a>'+data[2][i].priority+'</a></td><td><span class="label '+label+'">'+data[2][i].status+'</span></td><td><a>'+data[2][i].createdOn+'</a></td><td><a>'+lastUpdated+'</a></td><td class="p-team" style="text-align:center"><p>'+data[2][i].time_taken+'</p></td><td><a  class="btn btn-primary btn-xs view_ticket" data-value="'+data[2][i].ticketId+'"><i class="fa fa-eye"></i> View </a></td></tr>';
				$('.view_ticket_table').append(html);
            	}
            	
                }else if (data[0] == "error") {
                	var html = "";
                	html += '<tr><td colspan="7">No Record Found</td></tr>' ;
                		$('.view_ticket_table').append(html);
               }
        }
    });
    
});

$('.raiseticketsystem').on('submit',function(e){
	e.preventDefault();
	if($('#subject,#description').val() == '')
	{
	}
	else
	{
	 $.ajax({
		 processData: false,
         contentType: false,
         type: "POST",
         url: "../common/ticket.handle.php",
         cache: false,
         data: new FormData(this),
         beforeSend:function(){
            	$('#changebutton').button('loading'); 
             },
             complete:function(){
            	 $('#changebutton').button('reset');
            	 
             },
         success: function (data) {
         	data = JSON.parse(data);
         	if (data[0] == "success") {
             	
         		$('.raiseticketsystem')[0].reset();
         		$('.raise_ticket').hide();
         		$('#ticket_table').show();
         		$('.ticket_list').html('Tickets');
               	$('#send').fadeIn(300);
          	  $('.composed_reply').html('Ticket Has been Raised Successfully');
          	$('#send').fadeOut(5000);
          	  $('.close').click(function(e){
          		  $('#send').fadeOut(300);
          	  }); 
          	var startlimit = 0;
        	var endlimit = 50;
        	$('#ajax_loader_ticket').show();
        	$.ajax({
        		dataType: 'html',
        		type: "POST",
                url: "../common/ticket.handle.php",
                cache: false,
                data: {startlimit,endlimit,act: '<?php echo base64_encode($_SESSION['company_id']."!viewTickets");?>'},	
                success: function (data) {
                    data = JSON.parse(data);
                    $('#ajax_loader_ticket').hide();
                    if (data[0] == "success") {
                    	$('.view_ticket_table').html("");
                    	for(var i=0;i<data[2].length;i++){
                        if(data[2][i].status == "OPEN")
                        {
                            label = "label-primary";
                        }
                        else if(data[2][i].status == "CLOSED")
                        {
                            label = "label-default";
                        }    
                        if(data[2][i].lastUpdated == "0000-00-00 00:00:00")
                       	{
                        	lastUpdated = "Not Updated";
                       	}
                        else
                        {
                        	lastUpdated =	data[2][i].lastUpdated;
                        }
                       	                
                    	var html = "";
                    	html +='<tr><td class="p-name"><a>'+data[2][i].ticketId+'</a></td><td class="p-team"><p>'+data[2][i].category+'</p></td><td class="p-name"><a >'+data[2][i].subject+'</a></td><td><a>'+data[2][i].priority+'</a></td><td><span class="label '+label+'">'+data[2][i].status+'</span></td><td><a>'+data[2][i].createdOn+'</a></td><td><a>'+lastUpdated+'</a></td><td><a  class="btn btn-primary btn-xs view_ticket" data-value="'+data[2][i].ticketId+'"><i class="fa fa-eye"></i> View </a></td></tr>';
        				$('.view_ticket_table').append(html);
                    	}
                    	
                        }else if (data[0] == "error") {
                           
                       }
                }
            });
          
               }
         }
     });
	}
});
$('.reply_ticket_system').on('submit',function(e){
	e.preventDefault();
	if($('#description_ticketd').val() == '')
	{
	}
	else
	{
	 $.ajax({
		 processData: false,
        contentType: false,
        type: "POST",
        url: "../common/ticket.handle.php",
        cache: false,
        data: new FormData(this),
        beforeSend:function(){
           	$('#changebuttons').button('loading'); 
            },
            complete:function(){
           	 $('#changebuttons').button('reset');
           	
            },
        success: function (data) {
        	data = JSON.parse(data);
        	
        	if (data[0] == "success") {
            	$('.close').trigger('click');
            var html = "";
            var displayLastUpdatedTime=data[2][1];
           $(".ticket_updated").html(displayLastUpdatedTime);
           
           
            if($("#status").val() == "OPEN")
            {
                label = "label-primary";
            }
            else if($("#status").val() == "CLOSED")
            {
                label = "label-default";
            } 
    		    html +='<tr><td>'+$("#ticketId").val()+'</td><td>'+$('#creator_ticket').val()+'</td><td>'+$('#subject_ticket').val()+'</td><td>'+$("#description_ticketd").val()+'</td><td>'+displayLastUpdatedTime+'</td><td><span class="label '+label+'">'+$("#status").val()+'</span></td></tr>'
        		$('.ticket_view_all tr:last').after(html);
    		    $('.reply_ticket_system')[0].reset();
              }
        }
    });
	}
});
</script>


</body>
</html>
