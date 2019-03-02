<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="BassTechs">
<link rel="shortcut icon" href="img/favicon.png">
<title>Report</title>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
<link href="../css/morris.css" rel="stylesheet" />
<style type="text/css" title="currentStyle">
@import "../css/reportTable.css";
.compenentStyle{
padding: 12px;
margin: 7px 0px 9px 0px;
border: 1px solid #ccc;
}
.compenentRightepadding{
padding-right: 12px;
}
.compenentLeftepadding{
padding-left: 0px;
}

#top0-content{
width:100% !important;
margin-left:0px !important;
}

/*.dataTable{
width:1086px !important;
}*/

</style>
<body>
	<section id="container" class="">
		<!--header start-->
     <?php include_once (__DIR__ . "/header.php");	?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
            <?php include_once (__DIR__."/sideMenu.php");
            if(isset($_REQUEST['categoryNames'])){
            $categoryNames="(";
             foreach ( $_REQUEST['categoryNames'] as $row ) {
             	$categoryNames.="'".$row."',";
             }
            }else{
            	$categoryNames='';
            }
            if(isset($_REQUEST['customfields'])){
            	foreach ( $_REQUEST['customfields'] as $row ) {
            		$customfields.=$row.",";
            	}
            }else{
            	$customfields='';
            }
        
                       
           if(isset($_REQUEST['empPersonalDetails'])){
            	foreach ( $_REQUEST['empPersonalDetails'] as $row ) {
            		$empPersonalDetails.=$row.",";
            	}
            }else{
            	$empPersonalDetails='';
            }
            
            $empMasterDetail='';
            if(isset($_REQUEST['empMasterDetail'])){
            	foreach ( $_REQUEST['empMasterDetail'] as $row ) {
            		$empMasterDetail.=$row.",";
            	}
            }else{
            	$empMasterDetail='';
            }
            
            $teamCat='';
            if(isset($_REQUEST['teamCat'])){
            	foreach ( $_REQUEST['teamCat'] as $row ) {
            		$teamCat.=$row.",";
            	}
            }else{
            	$teamCat='';
            }
            
             ?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content" style="margin-top: -30px;">
			<section class="wrapper">
				<!-- page start-->
				<!-- Excel Table formed Id -->
				<div class="displayHide" id="exceltableContent"></div>
				<div class="displayHide" id="exceltableContent1"></div>
				<section class="panel">
					<!-- <header class="panel-heading"> Reports </header> -->
					<div class="panel-body">
					<div class="wrapper" style="border:1px solid #CCC;margin-top:12px;padding:0px;">
					<div id="loader" style="width:97%;height:100%;"></div>
					<div  id="report" class="col-lg-12" style="padding:0px;">
							
							</div>
							</div>
					</div>
				</section>
			</section>

		</section>

		<!--main content end-->
		<!--footer start-->
	   <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript" src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	
	<script src="../js/respond.min.js"></script>
		<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/TableTools.js"></script>
	<script src="../js/ZeroClipboard.js"></script>
	<script type="text/javascript" charset="utf-8" src="../js/FixedColumns.min.js"></script>
 	<script src="../js/jquery-migrate-1.0.0.js"></script> 
	<!--common script for all pages-->
	 
	<script src="../js/morris.min.js" type="text/javascript"></script>
	<script src="../js/jquery.table2excel.js"></script>
	<script src="../js/raphael-min.js" type="text/javascript"></script>
	<script src="../js/common-scripts.js"></script>
	<script type="text/javascript">
     $(document).ready(function () {
    	 $('#container').addClass('sidebar-closed');
    	 var currentAct='<?php echo base64_encode($_SESSION['company_id']."!reportHTML");?>';
    	 intializeReport(currentAct,0);
    	 stmtReport=$('#salaryStmt :selected').val();
    	 if(stmtReport=='AT002'){
        	 $('#export-to-excel').hide();
    	 }
 	 });
  

     $(document).on('click', "#export-to-excel", function (e) {
    	  e.preventDefault();
    	  var currentAct='<?php echo base64_encode($_SESSION['company_id']."!reportExcel");?>';
   	  intializeReport(currentAct,1);
     });

    
    function intializeReport(currentAct,isOnlyTable){ 
	   var report='<?php echo $_REQUEST['salaryStmt'];?>';
	   report = report.split("|"); 
	   var salaryStmt = report[0];
	   var reportType = report[1];
	   //if(salaryStmt=='SS001')
		   var customfield = "<?php echo substr($customfields,0,-1);?>";
	  // else
		   //var customfield = "";
	   var stmtFor='<?php echo isset($_REQUEST['stmtFor'])?$_REQUEST['stmtFor']:'';?>';
	   var typeOfyear='<?php echo isset($_REQUEST['typeOfyear'])?$_REQUEST['typeOfyear']:'';?>';
	   var periodFrom='<?php echo isset($_REQUEST['periodFrom'])?$_REQUEST['periodFrom']:'';?>';
	   var periodTo='<?php echo isset($_REQUEST['periodTo'])?$_REQUEST['periodTo']:'';?>';
	   var fromDt='<?php echo isset($_REQUEST['fromDate'])?substr($_REQUEST['fromDate'],6,9).substr($_REQUEST['fromDate'],2,4).substr($_REQUEST['fromDate'],0,2):'';?>';
	   var toDt='<?php echo isset($_REQUEST['toDate'])?(substr($_REQUEST['toDate'],6,9).substr($_REQUEST['toDate'],2,4).substr($_REQUEST['toDate'],0,2)):'';?>';
	   var monthFor='<?php echo isset($_REQUEST['monthFor'])?$_REQUEST['monthFor']:'';?>';
	   var monYear='<?php echo isset($_REQUEST['attendance_month'])?$_REQUEST['attendance_month']:'';?>';
	   var reportCategory='<?php echo isset($_REQUEST['reportCategory'])?$_REQUEST['reportCategory']:null;?>';
	   var categoryNames="<?php echo substr($categoryNames,0,-1).")";?>";
	   var consolidate='<?php echo isset($_REQUEST['consolidate'])?$_REQUEST['consolidate']:0;?>';
	   var year = '<?php echo isset($_REQUEST['choose_year'])?$_REQUEST['choose_year']:'';?>';
	   var branchOnly = '<?php echo isset($_REQUEST['branchCat'])?$_REQUEST['branchCat']:'';?>';
	   var teamOnly = '<?php echo substr($teamCat,0,-1);?>';
	   var employeeServiceStatus = '<?php echo isset($_REQUEST['job_status1'])?$_REQUEST['job_status1']:'';?>';
	   var employeeStatus = '<?php echo isset($_REQUEST['employeeStatus'])?$_REQUEST['employeeStatus']:'';?>';
	   var empPersonalDetail="<?php echo substr($empPersonalDetails,0,-1);?>";
	   var empMasterDetail = "<?php echo substr($empMasterDetail,0,-1);?>";
          
	    $.ajax({
        	 dataType: 'html',
             type: "POST",
             url: "php/report.handle.php",
             cache: false,
             data: { act:currentAct, type:salaryStmt,reportType:reportType,isCustom:customfield,monthFor:monthFor,monYear:monYear,
            	 reportFor:stmtFor,yearType:typeOfyear,from_period:periodFrom,to_period:periodTo,from_date:fromDt,to_date:toDt,
            	 filter_key:reportCategory,filter_value:categoryNames,isConsolidate:consolidate,isTable:isOnlyTable,year:year,branchOnly:branchOnly,
            	 teamOnly:teamOnly,employeeServiceStatus:employeeServiceStatus,empMasterDetail:empMasterDetail,employeeStatus:employeeStatus,
            	 empPersonalDetail:empPersonalDetail},
            	 
             beforeSend:function(){
            	 $('#loader').loading(true);  
              },
            success: function (data) { 
               jsonData = JSON.parse(data);
               if(jsonData[2]!=false){
                if(isOnlyTable==0){
                	reportDataPreparation(jsonData[2],categoryNames,customfield);
                }else{
                  $('#exceltableContent').html('').html(jsonData[2].data);
                  
                  
                  
                  
                  filename=jsonData[2].filename;
               
                  setTablesName=[];
               	  workSheetName=[];
               	  //getTableId=jsonData[2].tableId;
               	  str=$('#titleHeader').html();
                  var rowCount = $('#exceltableContent').find("pagebreak").length;;
              	 
                 if(rowCount>0){ 
               	 $('#exceltableContent').prepend('<tr><td style="font-weight: bold" colspan=27>'+str+'</td></tr>');
                 }
                	/* $('#exceltableContent').find('.'+getTableId).each(function (k) {
               	 	 $(this).attr('id',getTableId+k);
               	 	 
               	  	setTablesName[k]=getTableId+k;
               	  	
              	 	 element=$('.'+getTableId+'_title')[k];
              	 	
              	 	 findTdlenght=$(element).parent().parent().parent().parent().find('.reportTable tbody tr:first td').length;
              	 	 console.log(findTdlenght);
              	 	 $(element).parent().parent().parent().parent().find('.reportTable #'+getTableId+k+' thead').prepend('<tr><td colSpan='+findTdlenght+'><b>'+$(element).html()+'</b></td></tr>');
              	     
              	 	 var spaceTrim=$(element).html().split('of')[1];
              	 	 if(spaceTrim==undefined){
              	 		var spaceTrim=$(element).html().split('of')[0];
              	 	 }
                     workSheetName[k]=spaceTrim.trim();
                     
                    });*/
                    
               	    $("#exceltableContent").table2excel({
               	    	
			          //  exclude: ".excludeThisClass",
 						name:filename ,
			            fileext:".xls",
			            filename: str,
			        });
                    
               	 // tablesToExcel(setTablesName, workSheetName, filename+'.xls', 'Excel');
               	  
                }
             $('#loader').loading(false);  
           }else{
        	   $('#report').html('<div id="components" style="padding: 15px; margin-left: 574px;">No data Found</div>');
        	   $('#loader').loading(false);  
             }
          }
        });
     }
     
   function reportDataPreparation(reportArrayResult,categoryNames,customfields){
	   
	   var stmt='<?php echo $_REQUEST['salaryStmt'];?>';
	   stmt = stmt.split("|");
	   var salaryStmt = stmt[0];       
	   if($.isPlainObject(reportArrayResult)==true){
           report = reportArrayResult;    
           var idval=html="";
           $('#report').empty();
           //append the header into container
           $('#report').append('<form action="php/report.handle.php" method="post"><input type="hidden"name="act" value="<?php echo base64_encode($_SESSION['company_id']."!reportPdf");?>"><input type="hidden" name="type" value="<?php echo $_REQUEST['salaryStmt'];?>"><input type="hidden" name="reportFor" value="<?php echo isset($_REQUEST['stmtFor'])?$_REQUEST['stmtFor']:'';?>">	<input type="hidden" name="year" value="<?php echo isset($_REQUEST['choose_year'])?$_REQUEST['choose_year']:'';?>">   <input type="hidden" name="yearType" value="<?php echo isset($_REQUEST['typeOfyear'])?$_REQUEST['typeOfyear']:'';?>">	   <input type="hidden" name="from_period" value="<?php echo isset($_REQUEST['periodFrom'])?$_REQUEST['periodFrom']:'';?>">	   <input type="hidden" name="to_period" value="<?php echo isset($_REQUEST['periodTo'])?$_REQUEST['periodTo']:'';?>">	<input type="hidden" name="from_date" value="<?php echo isset($_REQUEST['fromDate'])?$_REQUEST['fromDate']:'';?>"> <input type="hidden" name="to_date" value="<?php echo isset($_REQUEST['toDate'])?$_REQUEST['toDate']:'';?>"> <input type="hidden" name="monthFor" value="<?php echo isset($_REQUEST['monthFor'])?$_REQUEST['monthFor']:'';?>">  <input type="hidden" name="monYear" value="<?php echo isset($_REQUEST['attendance_month'])?$_REQUEST['attendance_month']:'';?>"> <input type="hidden" name="filter_key" value="<?php echo isset($_REQUEST['reportCategory'])?$_REQUEST['reportCategory']:null;?>">	   <input type="hidden" name="filter_value" id="filterValue">	<input type="hidden" name="isCustom" id="isCustom">   <input type="hidden" name="isConsolidate" value="<?php echo isset($_REQUEST['consolidate'])?$_REQUEST['consolidate']:0;?>">	   <input type="hidden" name="isTable" value="1"><h5 class="container" style="text-align: center;width: 100%;"><strong>'+report.title+'</strong><button class="btn btn-sm btn-default pull-right" style="margin-left:1%" type="submit" id="exportIntoPdf"><i class="fa fa-file-pdf-o"></i> PDF</button><button class="btn btn-sm btn-default pull-right" type="button" id="export-to-excel" data-forData="'+report.reportParams.reportFor+'"><i class="fa fa-file-excel-o"></i> Excel</button></h5></form>');
			//append a div for components 
            $('#filterValue').val(categoryNames);
            $('#isCustom').val(customfields);
	        $('#report').append('<div id="components"></div>');
           //appending components
           $('#reportTitle').html(report.title);
           j=0;
           var height=(report.components.length==1)? ($(window).height()+127):($(window).height()/2)-127; 
		   
           for(i=0;i<report.components.length;i++){
           	var component = report.components[i];
           	// when there is no data we remove the title 
            if(component.data=='No data found'){
                component.title="";   
            }
        
            if(component.position == 'top' || component.position == 'bottom' ){
				//$('#components').append('<div class="col-lg-12 component" id="'+component.position+i+'"><div class="compenentStyle" style= "height:'+((report.components.length==1)?height+50:height+30)+'px;"><div class="component-title"><h6 style="text-align: center;margin-top: 1px;"><strong id="titleHeader">'+component.title+'</strong></h6></div><div class="container"  style="width:100%;"><div id="'+component.position+i+'-content"></div></div></div></div>');
				$('#components').append('<div class="col-lg-12 component" id="'+component.position+i+'"><div class="compenentStyle" style= "100%"><div class="component-title"><h6 style="text-align: center;margin-top: 1px;"><strong id="titleHeader">'+component.title+'</strong></h6></div><div class="container"  style="width:100%;"><div id="'+component.position+i+'-content"></div></div></div></div>');
           
			}else{
              	$('#components').append('<div class="col-lg-6 component '+((j%2==0)?'compenentLeftepadding':'compenentRightepadding')+'" id="'+component.position+i+'"><div class="compenentStyle"  style="height:'+((report.components.length==1)?height-60:height+30)+'px;"><div class="component-title"><h6 style="text-align: center;margin-top: 1px;"><strong>'+component.title+'</strong></h6></div><div id="'+component.position+i+'-content"></div></div></div>');
           		j++;
           	}
			contentElement = component.position+i;
			if(component.status!="error"){ 
				if(component.type == 'table'){
				   var isConsolidate=report.reportParams.isConsolidate;
				
				   initializeTable(contentElement,component.data,height,isConsolidate,report.title,report.reportParams.reportFor);
				   if(salaryStmt!='BT001' && salaryStmt!='BT002' && salaryStmt!='BT003' && salaryStmt!='ER007' && salaryStmt!='BT004' && salaryStmt!='BT005' && salaryStmt!='BT006'  && salaryStmt!='EL001' && salaryStmt!='EL002' && salaryStmt!='EL003' && salaryStmt!='EL004'&& salaryStmt!='FR002' && salaryStmt!='HR008' && salaryStmt!='ER011') 
				      $('#exportIntoPdf,#export-to-excel').show();  
				   else{
					   $('#export-to-excel').show();
					   $('#exportIntoPdf').hide();
				   }
				   
			    }else if (component.type == 'piechart'){
				   $('#'+component.position+i+'-content').html('<canvas id="myChart"></canvas>'); 
				   initializePiechart(height);
        	    }else if(component.type =='linechart'){
					initializeLinechart(contentElement,component.data,height);
				}else if(component.type =='barchart'){
					initializeBarchart(contentElement,component.data,height);
				}else if(component.type =='html'){
						initializehtmlContent(contentElement,component.data,height);
				}
            }else{
               $('#exportIntoPdf,#export-to-excel').hide();
               $('#'+component.position+i+'-content').html('<h5 class="text-center" style="padding: 123px;">'+component.data+'</h5>');
            }
         }
	  }
	}
										
   function initializeTable(element,data,height,isConsolidate,title,reportFor){
	  var isConsolidate=report.reportParams.isConsolidate;
	  var tableId = element+"-"+"table";
	  var table ="";
	  table += "<div class='reportTable' style='border: 1px solid #41CAC0;'><table id='"+tableId+"' style='width:100%;'>" ;
	  var theaders="<thead><tr style='text-align:center'>";
	  depth=data.headerDepth;
	  var rowSpan=false;
	  
	  $.each( data.tableHeaders,function (k, v) {
		     v.name = v.name.replace(/_/g," ");
			  var str1 = v.name ;
          	 var columnCusto = str1.replace('_',' ');
		      if(v.children)
			  	theaders += "<th colspan="+v.children.length+" style='text-align:center'>"+columnCusto+"</th>";
			  else
	          	theaders += "<th rowspan="+depth+" style='text-align:center'>"+columnCusto+"</th>";
		    });
		theaders +="</tr>";
		
	  if(depth>1){
	    	theaders += "<tr style='text-align:center'>";
	    	$.each( data.tableHeaders, function (k, v) {
	      	if(v.children){
		      	$.each( v.children, function (key, val) {
		      		val.name = val.name.replace(/_/g," ");
	          		theaders += "<th style='text-align:center;'>"+val.name+"</th>";
	      		});
	        }
	      });
	      theaders += "</tr>";
	      }
	      theaders +="</thead>";
	      table+=theaders;
         
	  table+='<tbody>';
	 
	  
	  var columnsCount = Object.keys(data.tableHeaders).length;
	  var dataLength = data.tableData.length; //(data.tableData.length>500)?500:data.tableData.length;
	  for(j=0;j<dataLength;j++){
		  if(j % 2 == 0){
			  table += '<tr class="alt">';
     		    }else{
     		    	table += '<tr>';
     		    }
			var tableHeaders = data.tableHeaders;
    	  $.each( data.tableData[j], function (k, v) {
        	  if(isConsolidate==0){
        		
            	  if(isNaN(v)==0 && k!=2 || v=="-" )
            		  if(tableHeaders[k] != "Personal_No") //column names doesnt need reformating to number
    				  	table+='<td style="text-align:right;">'+v+'</td>'; 
    				  else
    					  table+='<td style="text-align:right;">'+reFormateByNumber(v)+'</td>';
    			  else
    				  table+='<td>'+v.split('  ')[0]+'</td>';
        	  }else if(isConsolidate==1){
    			  if(isNaN(v)==0 || v=="-"  )
    				  table+='<td style="text-align:right;">'+reFormateByNumber(v)+'</td>'; 
    			  else 
    				  table+='<td >'+(v)+'</td>';
             }
     	 });
    	  table+='</tr>';
      }
    table+='</tbody>';
   
    if(data.tableFooters!= null){
	    table += '<tfoot><tr>';
	    $.each( data.tableFooters, function (k, v) {
				  if(isNaN(v)==0)
					    table+='<td style="text-align:right;">'+reFormateByNumber(v)+'</td>';
				  else
					  	table+='<td>'+(v)+'</td>';
	          });
	    table +="</tr></tfoot>";
   }
    table +='</table></div>';
		//pdfDataCreate(element,data,isConsolidate,title,reportFor);
		$('#'+element+'-content').append(table);
		
      if(isConsolidate==1 && data.tableHeaders.length<8){
			$('#'+element+'-content').css({'width':'50%','margin-left':'27%'});
		}else if(isConsolidate==0 && data.tableHeaders.length<8){
			$('#'+element+'-content').css({'width':'50%','margin-left':'27%'});
		}
		oTable = $('#'+tableId).dataTable({
			 //"sScrollY": height-85,
			 "sScrollX": (isConsolidate==1)?'120%':'100%',
			 "bScrollCollapse": true,
			 "aLengthMenu": [
 	                    [20, 50, 75, 100],
 	                    [20, 50, 75, 100] // change per page values here
 	                ],

 	                // set the initial value
 	                "iDisplayLength": 20,
					"sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
					"oTableTools": {
					"aButtons": []
						}, 	               
			});
		
	   	  new FixedColumns( oTable, {
					"iLeftColumns": data.fixedColumns,
			});
   }

   function initializePiechart(height){
	   var context = document.getElementById("myChart");
	   var data = {
			    labels: [
			        "Red",
			        "Blue",
			        "Yellow"
			    ],
			    datasets: [
			        {
			            data: [300, 50, 100],
			            backgroundColor: [
			                "#FF6384",
			                "#36A2EB",
			                "#FFCE56"
			            ],
			            hoverBackgroundColor: [
			                "#FF6384",
			                "#36A2EB",
			                "#FFCE56"
			            ]
			        }]
			};
	     var myPieChart = new Chart(context,{
		    type: 'pie',
		    data: data,
		    options: {
		    	legend: {
		            position:"right",
		            labels: {
		            	padding:5,
		            	top:5,
			            boxWidth:10,
		                fontColor: 'rgb(255, 99, 132)'
		            }
		        }
		    }
	    	 //   options: options
		});
	}
   function initializeLinechart(element,data,height){
	   chartElement = element+'-barchart';
	   $('#'+element+'-content').append('<div id="'+chartElement+'" style="height:inherit"><div>');
	   $('#'+element+'-content').height(height);
	   var chartData =data['data'];
	   var ykeys = data['ykeys'];
	   var labels = data['labels'];
	   Morris.Line({
		   element: chartElement,
		   data: chartData,
		   xkey: 'y',
		   ykeys: ykeys,
		   labels: labels,
		   xLabelAngle:45,
		   grid:true,
		     parseTime:false,
		     smooth:false,
		     hidehover:true
		  
		  
		 });
	   }
   function initializeBarchart(element,data,height){
	   chartElement = element+'-linechart';
	   $('#'+element+'-content').append('<div id="'+chartElement+'" style="height:inherit"><div>');
	   $('#'+element+'-content').height(height);
	   var chartData =data['data'];
	   var ykeys = data['ykeys'];
	   var labels = data['labels'];
	   Morris.Bar({
		   element: chartElement,
		   data: chartData,
		   xkey: 'y',
		   ykeys: ykeys,
		   labels: labels,
		   xLabelAngle:45, barSizeRatio:0.1,
		   grid:true,
		     parseTime:false,
		     smooth:false,
		     hidehover:true
		});
	   }
   function initializehtmlContent(element,data,height){
	   data =data["data"];
	   $('#'+element+'-content').html(data);
	   $('#export-to-excel').hide();
	   $('#'+element+'-content').css("overflow-y","auto");
	   $('#'+element+'-content').css("height",height+"px");
   }
   
  /* function pdfDataCreate(element,data,isConsolidate,title,reportFor){
	   var periodFrom='<?php echo $_REQUEST['periodFrom'];?>';
	   var tableId = element+"-"+"table_excel";
	   var table ="";
	  nameHeader=title.substring(0,title.indexOf("of"));
	  headerTitle= "<table style='width:100%'><tr><td  class='"+tableId+"_title' style='font-weight: bold;text-align:center;'>"
		+((isConsolidate==0)?((reportFor!='M' && reportFor!='Y')?nameHeader+'( '+data.tableData[0][2]+' - '+ periodFrom.split(' ')[1]+' )':
			((reportFor=='Y')?"Salary Statement For the Year of "+periodFrom.split('*')[1]+'( '+data.tableData[0][2]+' )':"Salary Statement For the Month of "+data.tableData[0][2])):
			((reportFor!='M' && reportFor!='Y' )?title:((reportFor=='Y')?"Salary Statement For the Year of "+periodFrom.split('*')[1]:
			 title)))+'</td> </tr></table>';
		  headerTable="<br><div class='reportTable'><table class="+tableId+"><thead repeat_header='1'><tr>" ;
		  $.each( data.tableHeaders, function (k, v) {
			  if(isConsolidate==0 && k==2)
			  headerTable+='';
			  else 
			  headerTable+='<th>'+v+'</th>';
	 	  });
		  headerTable+='</th></thead><tbody>';
		  oldPeriod=period='';
		  var i=0;		  
		  if(isConsolidate==1)
			   table=headerTitle+headerTable;
		  for(j=0;j<data.tableData.length;j++){
			if(isConsolidate!=1){
				 if(j==0){ 
			 oldPeriod=period=data.tableData[j][2];
			 table=headerTitle+headerTable;
			 }else{
			 period=data.tableData[j][2];
			 }
			if(oldPeriod!=period){  
				  oldPeriod=period;
				  table+='</tr></tbody></table></div><pagebreak>';
				  table +="<table style='width:100%'><tr><td class='"+tableId+"_title'  style='font-weight: bold;text-align:center;'>"+((reportFor!='M' && reportFor!='Y' )?
						  nameHeader+'( '+data.tableData[j][2]+' - '+ periodFrom.split(' ')[1]+' ) ':
						  ((reportFor=='Y')?"Salary Statement For the Year of "+periodFrom.split('*')[1]+'( '+data.tableData[j][2]+' )':
							  "Salary Statement For the Month of "+data.tableData[j][2]))+"</td> </tr></table>"+headerTable;
			}else{
				  table+='</tr>';
				  period=data.tableData[j][2];
			}
			}
		  if(j % 2 == 0){
			  table += '<tr class="alt border_bottom">';
     		    }else{
     		    	table += '<tr class="border_bottom">';
     		    }
		   $.each( data.tableData[j], function (k, v) {
			   if(k==2){
				 if(isConsolidate!=1)
				 table+='';
				 else
				 table+='<td style="text-align:right;width:9%">'+v+'</td>'; 
				 
				}else if(k==0 || k==1){
					 table+='<td style="text-align:left;width:7%;font-size:12px;">'+v+'</td>';
				}else{
				if(isConsolidate!=1)
			     table+='<td style="text-align:right">'+v+'</td>';
				 else
					 table+='<td style="text-align:right;width:9%">'+v+'</td>';
			}
			});
		   if(isConsolidate==1)
			   table+='</tr>';
		  }
		  table+='</tbody></table></div>';
		  $('#export-to-excel').data('id',tableId);
		  }*/
		 function hideIfColumsAllZero(tableId,startFrom,headerLength,status){
			 for(var i=startFrom;i<headerLength;i++){
				  var total = 0;
	              $('#'+tableId+' tr').each(function(){
	                if($('td', this).eq(i).html()!=null){
	 	             var value = parseFloat(deFormate($('td', this).eq(i).html()));
		 	              if (!isNaN(value)){
		                     if (total>0)
		                		return false;
		  	                 else
			  	               total+= value;
		                 }
	 	            }
	 			    
		         });
			      if(total==0){
	                  elementTable=$('#'+tableId+' td,th').filter(':nth-child(' + (i + 1) + ')');
				      $(elementTable).remove();
			      }
	             
		        }
 		 }

</script>
</body>
</html>

