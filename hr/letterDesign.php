<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Letters</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/DT_bootstrap.css" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<!-- Custom styles for this template -->
</head>
<style>
.external-event {
	cursor: move;
	display: inline-block !important;
	margin-bottom: 6px !important;
	margin-right: 6px !important;
	padding: 8px;
}

.label {
	cursor: pointer;
	display: inline;
	padding: .2em .6em .3em;
	font-size: 75%;
	font-weight: 700;
	line-height: 2;
	color: #fff;
	text-align: center;
	white-space: nowrap;
	vertical-align: baseline;
	border-radius: .25em;
}

.back{
	margin-top: 7px;
    margin-right: 7px;
}
</style>
<body>
	<section id="container" class="">
		<!--header start-->
       <?php
							
include_once (__DIR__ . "/header.php");
							?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
         <?php include_once (__DIR__."/sideMenu.php");?>
         <?php require_once (LIBRARY_PATH . "/letter.class.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
			<!-- page start-->
			<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
							<i class=" fa fa-arrow-left"></i> All Settings</a>
				</div>
				<section class="panel">
					<header class="panel-heading text-bold" id="titleName"> Letter Templates </header>

					<div class="panel-body">
						<div class="col-lg-12" id="hide_from">
							<form class="form-horizontal" role="form" method="post"
								id="notice_add">
								<input type="hidden" name="act"
									value="<?php echo base64_encode($_SESSION['company_id']."!txtGenerate");?>" />
								<div class="col-lg-12" id="add-department">
									<div class="panel-body">
										<div class="row">
											<aside class="col-lg-3">
												<input type="hidden" id="letterName" name="letterName">
												<h4 class="drg-event-title" id="titleName">Letters</h4>
												<div id="external-events">
												<?php $letter = new Letter();
												$letter->conn=$conn; 
												$letters = $letter->select()['data'];
												if(is_array($letters)){
												foreach ($letters as $letter_o){
												?>
													<div
														class='letterSelect external-event label label-primary'
														id='1' data-lettername="<?php echo $letter_o['letter_name']?>"><?php echo $letter_o['letter_title']?></div>
													<br>
													
												
												<?php }}else echo "<div><p> No Letters Found</p></div>";?>
												<h4 class="border-top drp-rmv">Click a Button to See a Content!</h4>
											</aside>
                                            <div class="display-hide"  style="display:none">
											<aside class="col-lg-9 " >
											<div>
											<h4 class="letter-title"></h4>
											</div>
												<textarea class="form-control ckeditor" name="editor1"
													rows="4"></textarea>
												<input type="hidden" name="letterContent" id="letter"/>
											</aside>

											<div class="col-lg-2 pull-right" style="margin-top: 25px;margin-right: -50px;">
												<button type="submit" class="btn btn-sm btn-success"
													id="submit">Update</button>
												<button type="submit" class="btn btn-sm btn-danger"
													data-id="" id="cancel">Cancel</button>
											</div>
											</div>
										</div>

									</div>
								</div>
							</form>
						</div>
					</div>
				</section>
				<!-- page  end-->
			</section>

		</section>
                 <?php include_once (__DIR__."/footer.php");?>
                </section>
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../js/respond.min.js"></script>


	<!--script for this page only-->
	<script type="text/javascript" src="../js/ckeditor.js"></script>
	<script src="../js/common-scripts.js"></script>
	<script src="../js/bootstrap-dialog.js"></script>

	<!-- END JAVASCRIPTS -->
	<script type="text/javascript">

	  $('#cancel').on('click', function (e) {
          e.preventDefault();
          $('.letterSelect[data-lettername="'+$(this).data('lettername')+'"]').trigger('click');
      });


	    
      $('#submit').on('click', function (e) {
          e.preventDefault();
          var data = CKEDITOR.instances.editor1.getData();
      	$('#letter').val(data);
          $.ajax({
               datatype: "html",
               type: "POST",
               url: "php/letter.handle.php",
               cache: false,
               data: {act:"<?php echo base64_encode($_SESSION['company_id']."!update");?>",letterName:$("#letterName").val(),letterContent:CKEDITOR.instances.editor1.getData()},
              /* beforeSend:function(){
                	$('#'+idVal).button('loading'); 
                 },
                 complete:function(){
                	 $('#'+idVal).button('reset');
                 },*/
               success: function (data) {
                   data1 = JSON.parse(data);
                   if (data1[0] == "success") {
                       BootstrapDialog.alert(data1[1]);
                       }
                   else
                       if (data1[0] == "error") {
                           alert(data1[1]);
                       }

               }

           });
          
      });
      var company_id='<?php echo $_SESSION['company_id'];?>';
      $('.letterSelect ').on('click', function (e) {
   $(".letter-title").html($(this).html());
   $("#cancel").data('lettername',$(this).data('lettername'));
   $(".display-hide").show();
         $.ajax({
        	   datatype: "html",
               type: "POST",
               url: "php/letter.handle.php",
               data:{act:"<?php echo base64_encode($_SESSION['company_id']."!getHtml");?>",letterName:$(this).data('lettername')},
               beforeSend:function(){
                   $(".panel-body").loading(true);
               },
               complete:function(){
            	   $(".panel-body").loading(false);
                   },
               cache: false,
               success: function(data) {
                   data=JSON.parse(data);
                   if(data[0]=="success")
                       letters= data[2];
                   		if(letters!=[]){
                       		$("#letterName").val(letters[0]['letter_name']);
               				CKEDITOR.instances.editor1.editable().setHtml( letters[0]['letter_content']);
                   		}
        	},
        	error: function() {
        		CKEDITOR.instances.editor1.editable().setHtml( 'Create Design for '+element);
        	}
        	});
       	 
       });
      
      
      $('#cancel').on('click', function (e) {
          e.preventDefault();
          var ele=$(this).data('id');
          $('#'+ele).click();
      });
     </script>
</body>
</html>

