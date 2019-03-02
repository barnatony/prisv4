$(document).ready(function () {
	//minViewMode: "months"
	App.initHelpers(['datetimepicker']);
	//initializeAttendancesStatus();
});



//on refresh 
$(document).on('click','#refresh',function(e){
	e.preventDefault();
	initializeAttendances();
	
});

$(document).on('click','#Srefresh',function(e){
	e.preventDefault();
	initializeAttendancesStatus();
});
//emp-search

var helpers = {
		"moment":function(chunk, context, bodies, params) {
			if(params.datetime)
				var dateTime = moment(params.datetime);
			
			if(params.format)
				chunk.write(moment(params.datetime).format(params.format));
			else if(params.fromnow)
				chunk.write(moment(params.datetime).fromNow());
		}	 
	 };

	for(var key in helpers) {
		  dust.helpers[key] = helpers[key];
		}
	var month=$(".datetimepicker").find("input").val();
	
	if(!month){
		initializeAttendances();
		initializeAttendancesStatus();
	}
	//ajax call for timeline//
	function initializeAttendances(){
	
		
		var month=$(".datetimepicker").find("input").val();
		month=(moment().month(month).format("MMM-YYYY"));
		var Emonth=$(".datetimepicker").find("input").val();
		Emonth=(moment().month(Emonth).format('MMM-YYYY'));
		var empID=$('input[name=empId]').val();
		var empName=$('input[name=empName]').val();
		var fdata=$('input[name=for]').val();
		
			var message=empName + ',' + Emonth;
		
		data={"empId":empID};
		var attendancesTpl = dust.compile($("#attendance-view").html(),"attendancesTpl");
			dust.loadSource(attendancesTpl);
			
		        $.ajax({
		            type:"POST",
		            cache: false,
		    		url: App.myUrl("attendance/view/"+fdata+'/'+month),
		    		data:data,
		            
		    		beforeSend:function(jqXHR){
		            	App.blocks("#attendance-template", 'state_loading');
		            	//$(e.target).button({loadingText: 'Deleting...'}).button('loading');
		            },
		            complete:function(jqXHR,textStatus){
		            	App.blocks("#attendance-template", 'state_normal');
		            },
		            
		            success: function(data){
		          	  if(isJson(data)==false)
		          		$(".err-notification").html("Data Cannot be found");
		          	  else
		          		  data = JSON.parse(data);
		          	if(data[0]==false)
		          		$(".err-notification").html("Data Cannot be found");
		             
		          	var template = function(data,tplvar) {
		          		var result;
		    			dust.render(tplvar, data, function(err, res) {
		    				result = res;
		    			});
		    			
		    			return result;
		    		};
		    		
		    		$("#attendances").html(template(data,"attendancesTpl"));
		    		
		    		
		    		var table= $('#view').DataTable({
		    			 "searching": false,
		    			 "ordering": false, 
		    			 "info": false, 
				         "scrollY":  250,
				         "scrollCollapse": true,
				         "paging":  false,
				         "scrollX": false,   
				         "responsive": true,
				         //"sScrollXInner": "400%",
				         "dom": "Bfrtip",
				         "responsive": true,
				         "scroller":       true,
				         "buttons": [
				             
				             {
				                 extend: 'excel',
				                 title :'Attendance Details',
				                 messageTop: message,
				                 text: '<i class="fa  fa-file-excel-o"> Export</i>',
				                 className:'btn-sm'
				             },
				         ]
		    		
		    		 }); 
		    		//to hide excel import button when table is empty
		    		if(!table.data().count()){
		    			$('.buttons-excel').wrap('<div id="hide" style="display:none"/>');
		    		};		    		
		            }
		            });
		       
		        
	
	
	}
	

	$("#calender").on("dp.change", function(e) {
		var date=$("#calender").find("input").val();
		initializeAttendances();
	 });
	$("#at_calender").on("dp.change", function(e) {
		var mnth=$(".datetimepickert").find("input").val();
		initializeAttendancesStatus(mnth);
	 });

	 
	
	//auto-complete employee
	 jQuery('.employee-search').each(function(){
		 
	       var $input = jQuery(this);
	       
	    // Init autocomplete functionality
		    var cache = {};
		    $input.autoComplete({
		        minChars: 1,
		        source: function( request, response ) {
		            request = {"s":request};
		            $.getJSON( App.myUrl("attendance/lookup_emp/"), request, function( data, status, xhr ) {
		            	response(data);
		              
		            });
		          },
		          
		          focus: function (event, ui) {
		        	  $(this).val(ui.name);
					  return false;
		          },
		          onSelect: function (event, term, item) {
		        	  
		        	$input.parent().find("input[name='empId']").val($(item).data('id'));
		        	$input.parent().find("input[name='empName']").val($(item).data('val'));
		        	return false;
		          },
		          renderItem: function (item, search){
		        	  
		        	  search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
		        	   var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
			        	  var li = ' <div class="autocomplete-suggestion" data-val="' + item.employee_name+ ' ['  +item.id+ ']'+  '"data-id="' + item.id+ '" "data-id="' + item.employee_name+ '">\
			        	  <ul class="list-group" style="margin-bottom: 0px;">\
		                  <li>\
		                      <div class="push-10-l clearfix">\
			        	  <p>' + item.employee_name+ ' ['  +item.id+ ']'+  '</p>\
		                      </div>\
		                  </li>\
		                  </ul>\
			        	  </div>';
			        	   return li;
		          }

		    });
	       
		 });
	 
	 $('#search').on("change",function(){
		 initializeAttendances();
	 });


function initializeAttendancesStatus(mnth){
			
			
			var month=$(".datetimepickert").find("input").val();
			//console.log(month)
			//console.log(mnth)
			//month=(moment().month(month).format("MMM-YYYY"));
			var Emonth=$(".datetimepicker").find("input").val();
			Emonth=(moment().month(Emonth).format('MMM-YYYY'));
			var empID=$('input[name=empId]').val();
			var empName=$('input[name=empName]').val();
			var fdata=$('input[name=for]').val();
			
				var message=empName + ',' + Emonth;
			if(typeof mnth == 'undefined'){
				mnth=month;
			}
			data={"empId":empID};
			var attendancesStatusTpl = dust.compile($("#attendance-Present").html(),"attendancesStatusTpl");
				dust.loadSource(attendancesStatusTpl);
				
			        $.ajax({
			            type:"POST",
			            cache: false,
			    		url: App.myUrl("attendance/view/"+fdata+'/'+mnth),
			    		data:data,
			            
			    		beforeSend:function(jqXHR){
			            	App.blocks("#PresentData", 'state_loading');
			            	//$(e.target).button({loadingText: 'Deleting...'}).button('loading');
			            },
			            complete:function(jqXHR,textStatus){
			            	App.blocks("#PresentData", 'state_normal');
			            },
			            
			            success: function(data){
			            	//console.log(data);
			            		
			            	
			            if(isJson(data)==false)
			          		$(".err-notification").html("Data Cannot be found");
			          	  else
			          		  data = JSON.parse(data);
			            
			          	if(data[0]==false)
			          		$(".err-notification").html("Data Cannot be found");
			          	
			          	var template = function(data,tplvar) {
			          		var result;
			    			dust.render(tplvar, data, function(err, res) {
			    				//console.log(data)
			    				result = res;
			    			});
			    			
			    			return result;
			    		};
			    		
			    		$("#Status").html(template(data,"attendancesStatusTpl"));
			    		//console.log($("#Status").html());
			    		
			    		var table= $('#Statusview').DataTable({
			    			 "searching": false,
			    			 "ordering": false, 
			    			 "info": false, 
					         ///"scrollY":  250,
					        // "scrollCollapse": true,
					         "paging":  false,
					         "scrollX": true, 
					         "scrollX":        '250',
					         "responsive": true,
					         "sScrollXInner": "100%",
					         "dom": "Bfrtip",
					         "responsive": true,
					         "scroller":       true,
					         "autoWidth": false,
					         "buttons": [
					             
					             {
					                 extend: 'excel',
					                 title :'Attendance Details',
					                 messageTop: message,
					                 text: '<i class="fa  fa-file-excel-o"> Export</i>',
					                 className:'btn-sm'
					             },
					         ],
			    		"drawCallback": function( settings ) {
		                	$(".display_date").popover({
		                		content:$(this).data("content"),
		                		
		                		trigger:'hover',
		                		placement:'top',
		                		animation:true,
		                		html:true,
		                	});
		                	$(".hoverD").popover({  
		                		html:true,
		                		trigger:'hover',
		                		placement:'top',
		                		container: 'body',
		                		content:$(this).parent().find('.hoverD').data('content'),
		                	
		                		//content:$(this).data("content"),
		                		
		                		
		                		
		                		//animation:true,
		                	
		                		//	container: 'th'
		                	});
		                	
		                }, 
			    		 }); 
			    		//to hide excel import button when table is empty
			    		
			    			//$('.buttons-excel').wrap('<div id="hide" style="display:none"/>');
			    				    		
			            }
			            });
			       
			        
		
		
		}
		