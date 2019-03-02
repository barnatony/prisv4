<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="description" content=""><meta name="author" content="Mosaddek"><meta name="keyword"	content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina"><link rel="shortcut icon" href="img/favicon.png"><title>Employee Hierarchy Tree</title><!-- Bootstrap core CSS --><link href="../css/bootstrap.min.css" rel="stylesheet"><link href="../css/bootstrap-reset.css" rel="stylesheet"><!--external css--><link href="../css/font-awesome.min.css" rel="stylesheet" /><!-- Custom styles for this template --><link href="../css/style.css" rel="stylesheet"><link href="../css/style-responsive.css" rel="stylesheet" /><link href="../css/ui.fancytree.css" rel="stylesheet"><style>span:hover {	cursor: pointer;}hr {	margin-top: 10px;	margin-bottom: 10px;}.fancytree-treefocus {	border: 0px !important;	outline: 0px !important;}#treeParent {	position: fixed;	top: 201px;	bottom: 10px;	border: 1px solid #ccc;	overflow: auto;	width: 50%;}#serachWidth {	position: fixed;	top: 134px;	width: 50%;}.outer {	height: 900px;}@media only screen and (min-width: 320px) and (max-width:640px ) {	#treeParent {		position: fixed;		bottom: 10px;		border: 1px solid #ccc;		overflow: auto;		width: 74%;	}	#hiddenCon {		display: none;	}	#serachWidth {		position: fixed;		top: 134px;		width: 74%;	}}@media only screen and (min-width: 768px) and (max-width:1280px ) {	#treeParent {		position: fixed;		bottom: 10px;		border: 1px solid #ccc;		overflow: auto;		width: 66%;	}	#hiddenCon {		display: none;	}	#serachWidth {		position: fixed;		top: 134px;		width: 66%;	}}.inner {	width: 50px;	border: 1px solid white;	position: fixed;}.site-min-height {	min-height: 100%;	position: fixed;	overflow-y: scroll;	overflow-x: hidden;}/* enable absolute positioning */.inner-addon {	position: relative;}/* style icon */.inner-addon .iconSet {	position: absolute;	padding: 10px;}h5 .small {	font-size: 100%;}/* align icon */.right-addon .iconSet {	right: 9px;}</style><!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries --><!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]--></head><body>	<section id="container" class="">		<!--header start-->
     <?php include("header.php"); ?>
      <!--header end-->		<!--sidebar start-->		<aside>
            <?php include_once("sideMenu.php");?>
         </aside>		<!--sidebar end-->		<!--main content start-->		<section id="main-content">			<section class="wrapper site-min-height">				<!-- page start-->				<section class="panel">					<header class="panel-heading"> Employee Hierarchy Tree </header>					<div class="panel-body outer">						<div class="row col-sm-12">							<div class="col-sm-6">								<div class="form-group" id="serachWidth"									style="background-color: #f5f5f5; border: 1px solid rgb(204, 204, 204);">									<br>									<div class="col-lg-5  pull-right">										<div class="inner-addon right-addon">											<a id="value" href="#"> <i class="iconSet fa fa-search"></i></a>											<input class="form-control" id="empSearch" name="empSearch"												autocomplete="off" placeholder="Employee Name" type="text">										</div>									</div>									<br>									<br>									<hr>								</div>								<div id="treeParent">									<div id="employeeTree"></div>								</div>							</div>							<div class="col-sm-6" style="margin-left: 57%;" id="hiddenCon">								<div id="well" class="well hide col-lg-6"></div>							</div>						</div>					</div>				</section>			</section>		</section>		<!--main content end-->		<!--footer start-->
		<?php include("footer.php"); ?>
      <!--footer end-->	</section>	<!-- js placed at the end of the document so the pages load faster -->	<script src="../js/jquery-2.1.4.min.js"></script>	<script src="../js/jquery-ui.js"></script>	<script src="../js/bootstrap.min.js"></script>	<script class="include" type="text/javascript"		src="../js/jquery.dcjqaccordion.2.7.js"></script>	<script src="../js/jquery.scrollTo.min.js"></script>	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>	<script src="../js/respond.min.js"></script>	<!-- END JAVASCRIPTS -->	<!--common script for all pages-->	<script src="../js/common-scripts.js"></script>	<script src="../js/jquery.fancytree-all.js"></script>	<script>
    $(document).ready(function () {
      
        $("#employeeTree").fancytree({
        	extensions: ["filter","childcounter"], 
            quicksearch: true,
            imagePath:"../img/",
            filter: {
                mode: "hide",
                autoApply: true
            },
            source: {
                url: "php/employee.handle.php",
            	data:{act:'<?php echo base64_encode($_SESSION['company_id'].'!getEmployeeTree');?>'}
                },
            cache: false,
            childcounter: {
                deep: true,
                hideZeros: false,
                hideExpanded: false
              },

            activate: function (event, data) {
                $('#well').removeClass('hide show');
                $('#well').addClass('show');
                $('#well').empty();
                var html ='';
                html+='<h5>'+data.node.data.employee_name +' - <span class="text-muted small">'+data.node.data.employee_id+ ' </span> </h5><hr><h5>Designation : <span class="text-muted small">'+data.node.data.designation+ ' </span> </h5><h5>Department : <span class="text-muted small">'+data.node.data.department+ ' </span> </h5><h5>Reporting Person : <span class="text-muted small">'+data.node.data.reportingPerson+' - '+ data.node.data.reportId +' </span> </h5>';
                $('#well').append(html)
            	             
            }
        });


 	     var employeeTree = $("#employeeTree").fancytree("getTree");
        $("#empSearch").keyup(function (e){
                 var n;
		        match = $(this).val();
		        if (e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === ""){
		            employeeTree.clearFilter();
		            $("#empSearch").val("");
		            $('#value').html('<i class="iconSet fa fa-search"></i>');
		            return;
		        }
		        n = employeeTree.filterNodes(match, false);
		        
		        $('#value').html('<i class="iconSet fa fa-times"></i>');
		    });   

      });

    $('#value').on('click',function(){
    	var employeeTree = $("#employeeTree").fancytree("getTree");
		 $("#empSearch").val("");
	     employeeTree.clearFilter();
		$('#value').html('<i class="iconSet fa fa-search"></i>');
        });
    

    
</script></body></html>