<?php
require_once ( dirname ( dirname ( __FILE__ ) ) . "/include/config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="BassTechs">

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
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
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

#ajax_loader_ticket, #ajax_loader {
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
						<span class="ticket_list" style="cursor: pointer"> Tickets</span>
						<span class="ticket_list1"></span>
						<div class="pull-right">
							<a  class="btn btn-sm btn-danger hide" type="button" id="back-botton" style="margin-right:5px;">
									Back</a>
	                        <a  class="btn btn-sm btn-default navbar-btn" href="../master/ticketsreport.php" style="float:right;margin-top:-1px;">Generate</a>
						</div>
					</header>
					<div class="panel-body" id="editable-table" style="display: none">
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover  cf"
									id="ticket_table">

									<thead>
										<tr>
											<th>TicketId</th>
											<th>Company Name</th>
											<th>Category</th>
											<th>Subject</th>
											<th>Priority</th>
											<th>Status</th>
											<th>Created-On</th>
											<th>Updated-On</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody class="view_ticket_table">
										<tr>
											<td colspan="8"><img src="../img/ajax-loader.gif"
												style="display: none" id="ajax_loader_ticket"></td>
										</tr>
									</tbody>

								</table>
							</section>
						</div>
					</div>
					<img src="../img/ajax-loader.gif" style="display: none"
						id="ajax_loader">
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
											<div class="bio-row">
												<p>
													<span class="bold">Company </span>: <a
														class="ticket_company"></a>
												</p>
											</div>
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
										</div>
									</header>

									<div class="panel-body">
										<table class="table table-hover p-table"
											style="display: none;">
											<thead>
												<tr>
													<th>Ticket Id</th>
													<th>Created By</th>
													<th>Subject</th>
													<th>Description</th>
													<th>Updated On</th>
													<th>Status</th>
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
										<input type="hidden" name="act" value="replyTicket"> <input
											type="hidden" name="ticketId" id="ticketId" value=""> <input
											type="hidden" name="receiverId" id="receiverId" value=""> <input
											type="hidden" name="subject_ticket" id="subject_ticket"
											value=""> <input type="hidden" name="creator_ticket"
											id="creator_ticket" value="support@hrlabz.in">
										<div class="form-group">
											<label class="col-lg-2 control-label">Description</label>
											<div class="col-lg-10">
												<textarea name="description" id="description_ticketd"
													class="form-control" cols="30" rows="10" maxlength="500"></textarea>
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
														name="attachment">
													</span>
												</div>
											</div>


										</div>
										<div class="form-group">
											<label class="col-lg-2 control-label">Status</label>
											<div class="col-lg-10">
												<select class="form-control" name="status" id="status">
													<option value="OPEN">OPEN</option>
													<option value="CLOSED">CLOSED</option>
												</select>
											</div>
										</div>
										<button type="submit" id="changebuttons" class="btn btn-send">Send</button>


									</form>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
				</section>
			</section>
			<!-- page end-->
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
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/TableTools.js"></script>
	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>
	<script src="../js/jquery.validate.min.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>
	<script>
$(document.body).on('click', '.view_ticket' ,function(){
	$('#back-botton').removeClass('hide');
	$('#ticket_table,#editable-table').hide();
	$('#ajax_loader').show();
	var ticket_Id = $(this).data('value');
	viewSystem(ticket_Id);
	});
var fnTable = function () {

    return {

        //main function to initiate the module
        init: function () {
	var oTable = $('#ticket_table').dataTable( {
     	 "aLengthMenu": [
                          [5, 15, 20, -1],
                          [5, 15, 20, "All"] // change per page values here
                      ],
          "iDisplayLength": 5,
          "aaSorting": [],
          "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
          "bProcessing": true,
          "bServerSide": true,
          "sAjaxSource": "php/ticket-view.php",
          "sServerMethod": "POST",

          "sPaginationType": "bootstrap",
          "oLanguage": {
              "sLengthMenu": "_MENU_ records per page",
              "oPaginate": {
                  "sPrevious": "Prev",
                  "sNext": "Next"
              }
          },
          "oColReorder": {
              "iFixedColumns": 1
          },
          "aoColumns": [
                  { "sName": "ticket_id" },
                  { "sName": "company_id" },
					{ "sName": "category" },
					{ "sName": "subject" },
					{ "sName": "priority" },
					{ "sName": "status" },
					{ "sName": "created_on","bSearchable":false },
					{ "sName": "updated_on","bSearchable":false },
					{ "sName": "action","bSortable": false}                 
              ], "aoColumnDefs": [{
         	      // `data` refers to the data for the cell (defined by `mData`, which
         	      // defaults to the column being worked with, in this case is the first
         	      // Using `row[0]` is equivalent.
         	    
             	  "bSearchable": false,"aTargets": [6,7,8]},
         	      {"mRender": function (data, type, row) {
             	      
             	    if (row[5] == 'CLOSED') {
         	        	 return '<span class="label label-default">'+data+'</span>';
         	          } else {
         	              return '<span class="label label-primary">'+data+'</span>';
         	          }
         	      },
             	   "aTargets": [5]
             	      },{"mRender": function (data, type, row) {
             	     
               	    if(row[8]){
  						return '<a  class="btn btn-primary btn-xs view_ticket" data-value="'+row[8]+'"><i class="fa fa-eye"></i> View </a>';
           	          }
         	          },
             	     "aTargets": [8]
         	      
         	  }], 

              "oTableTools": {
                  "aButtons": [
              {
                  "sExtends": "pdf",
                  "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                  "sPdfOrientation": "landscape",
                  "sPdfMessage": "Branch Details"
              },
              {
                  "sExtends": "xls",
                  "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
              }
           ],
                  "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

              }

          });
	
		 $('#ticket_table_wrapper .dataTables_filter').html('<div class="input-group">\
                 <input class="form-control medium" id="searchInputs" type="text">\
                 <span class="input-group-btn">\
                   <button class="btn btn-white" id="searchFilters" type="button">Search</button>\
                 </span>\
                 <span class="input-group-btn">\
                   <button class="btn btn-white" id="searchClears" type="button">Clear</button>\
                 </span>\
             </div>');
		 $('#ticket_table_processing').css('text-align', 'center');
		 jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
		 $('#searchInputs').on('keyup', function (e) {
			 if (e.keyCode == 13) {
                 oTable.fnFilter($(this).val());
             } else if (e.keyCode == 27) {
                 $(this).parent().parent().find('input').val("");
                 oTable.fnFilter("");
             }
			 });
		 $('#searchFilters').on('click', function () {
             oTable.fnFilter($(this).parent().parent().find('input').val());
         });
         $('#searchClears').on('click', function () {
             $(this).parent().parent().find('input').val("");
             oTable.fnFilter("");
         });
	 var nEditing = null;
        }

    };

} ();
function viewSystem(ticket_Id){
	$.ajax({
		dataType: 'html',
		type: "POST",
	    url: "php/ticket.handle.php",
	    cache: false,
	    data: {ticket_Id,act: 'viewTicket'},	
	    success: function (data) {
		     data = JSON.parse(data);
	        $('#ajax_loader').hide();
	        $('.ticket_system_details').show();
	        if (data[0] == "success") {
		    	$('.reply_fg').html("");
		     for(var j=0;j<1;j++){
				 $('.ticket_subject').html(data[2][j].subject);
				 $('.ticket_createdby').html(data[2][j].creatorId);
				 $('#ticketId').attr('value',data[2][j].ticketId);
				 $('#receiverId').attr('value',data[2][j].receiver);
				 $('#subject_ticket').attr('value',data[2][j].subject);
				 $('.ticket_company').html(data[2][j].companyName);
				
				 if(data[2][j].status == "OPEN")
				 {
				 $('.ticket_status').html('<span class="label label-primary">'+data[2][j].status+'</span>');
				 var jsp = '<a  data-toggle="modal" href="#myModal" class=" btn btn-success btn-xs"><i class="fa fa-reply-all"></i> &nbsp;Reply</a> ';
					 $('.reply_fg').append(jsp);
				 }
				 else if(data[2][j].status == "CLOSED")
				 {
					 $('.ticket_status').html('<span class="label label-default">'+data[2][j].status+'</span>');
					 $('.reply_fg').html("");
					 console.log('here');
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
				 $('.ticket_list1').html(' > '+data[2][j].subject);
			 }
	       	$('.ticket_view_all').html("");
	       	$('.attachment_ticket').html("");

       		 	for(var i=0;i<data[2].length ;i++){	
	       		$('.p-table').show();
	       		if(data[2][i].lastUpdated == "0000-00-00 00:00:00")
	           	{
	           		$('.ticket_updated').html("Not Updated<br/>");
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
	          
	       		html +='<tr><td>'+data[2][i].ticketId+'</td><td>'+data[2][i].creatorId+'</td><td>'+data[2][i].subject+'</td><td>'+data[2][i].desc+'</td><td><a>'+updatedons+'</a></td><td><span class="label '+label+'">'+data[2][i].status+'</span></td></tr>';
	       		$('.ticket_view_all').append(html);
	       		if(data[2][i].attach != 'Nil' && data[2][i].attach != null)
	       		{
	            phtml +='<li><i class="fa fa-picture-o"></i>&nbsp;<a href="'+encodeURI(data[2][i].attach)+'" target="foo">Attachment</a></li>';
	       		}

	       		$('.attachment_ticket').append(phtml);
	          
	       	}
	        }
	    else if (data[0] == "error") {
	  }
	    }
	});
	
}
$('.ticket_list,#back-botton').click(function(e){
	$('.ticket_system_details,.raise_ticket').hide();
	$('#ticket_table,#editable-table').show();	
	$('#back-botton').addClass('hide');
	$('.ticket_list1').html("");
});



$(document).ready(function (e) {
	var ticketId = '<?php echo isset($_GET['tId'])?$_GET['tId']:0;?>';
	if(ticketId!=0){
	$('#ticket_table').hide();
	$('#ajax_loader_ticket').show();
	$('.ticket_list').html('<a href="tickets.php" title="Back">Tickets</a>');
	$('.ticket_list').removeClass('ticket_list');
	viewSystem(ticketId);
	}else{
	$('#ticket_table,#editable-table').show();
	fnTable.init();
	}
});
function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
        var second = '0'+second;
    }   
    var dateTime = year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second;   
     return dateTime;
}
$('.reply_ticket_system').on('submit',function(e){
	e.preventDefault();
	if($('#description_ticketd').val() == ''){
	}
	else{
	 $.ajax({
		 processData: false,
        contentType: false,
        type: "POST",
        url: "php/ticket.handle.php",
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
        
       	 $('#changebuttons').button('reset');
        	if (data[0] == "success") {
           	$('.close').trigger('click');
            var html = "";
            if($("#status").val() == "OPEN"){
                label = "label-primary";
            }
            else if($("#status").val() == "CLOSED"){
                label = "label-default";
            } 
            $('.ticket_status').html('<span class="label '+label+'">'+$("#status").val()+'');
         
    		    html +='<tr><td>'+$("#ticketId").val()+'</td><td>'+$('#creator_ticket').val()+'</td><td>'+$('#subject_ticket').val()+'</td><td>'+$("#description_ticketd").val()+'</td><td>'+getDateTime()+'</td><td><span class="label '+label+'">'+$("#status").val()+'</span></td></tr>'
    		    if($('.ticket_view_all tr').is(':last-child')){
    		    	$('.ticket_view_all tr:last').after(html);
    		    }else{
        		    $('.p-table').show();
    		   $('.ticket_view_all').html(html);
    		    }
    		    $('.reply_ticket_system')[0].reset();
    		    $('#ticket_table').dataTable().fnDestroy();
    		    fnTable.init();
              }
        }
    });
	}
});
</script>


</body>
</html>
