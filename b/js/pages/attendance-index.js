$(document).ready(function () {
	//minViewMode: "months"
	App.initHelpers(['datetimepicker']);
	$('#calender').datetimepicker({
	    format: 'YYYY-MM-DD',    
	    calendarWeeks:true
	   
	});
	var todayDate=$('.today_date').val();
	//initializeAttendances();
	initializeAttendancesStatus(todayDate);
	  
});

//on refresh 
$(document).on('click','#refresh',function(e){
	e.preventDefault();
	//initializeAttendances();
	
});

$(document).on('click','#Srefresh',function(e){
	e.preventDefault();
	var todayDate=$('.today_date').val();
	initializeAttendancesStatus(todayDate);
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
	
	//ajax call for daily timeline//
	function initializeAttendances(mnth){
		var empID=$('input[name=empId]').val();
		var empName=$('input[name=empName]').val();
		var fdata=$('input[name=for]').val();
		data={"empId":empID,"month":mnth,"dateStatus":"daily"};
		        $.ajax({
		        	dataType: 'html',
		        	type:"POST",
		            cache: false,
		    		url: App.myUrl("attendance/view/"+fdata+'/'+mnth),
		    		data:data,
		            
		    		beforeSend:function(jqXHR){
		            	App.blocks("#attendance-template", 'state_loading');
		            },
		            complete:function(jqXHR,textStatus){
		            	App.blocks("#attendance-template", 'state_normal');
		            },
		            success: function(data){
			            var date = mnth;
			            var	data_1 = JSON.parse(data);
			            $('#attendances').fullCalendar({
					           header :{
					            	  	left:false,
					            		right:'prev title next ,month,agendaWeek,agendaDay',
					            		center: false
					                },
					          buttonText :{
					                	
					                	    month: 'Monthly'
					                  
					                },
			            });
			            $('.fc-agendaDay-view').addClass('hide');
			            $('.attendance_daily').removeClass('hide');
			           var events_daily=[];
			        	var html ='<section id="flip-scroll"><table class="table table-striped table-hover table-bordered" id="attendance_1"><thead><tr class="bg-primary" rowspan="12"><td>Daily Time Sheet</td></tr></thead><tbody>';
			        	for(var i=0;i<data_1.daily.length;i++){
		            		 var empName=data_1.daily[i].employee_name;
		            		 var empID=data_1.daily[i].employee_id;
		            		 $('#empID').val(empID);
		            		 $('#empName').val(empName);
		            		  var punches=data_1.daily[i].all_punches;
		            		  var date = data_1.daily[i].dates;
		            		  var date_daily = new Date(date);
		            		  var newDate = date_daily.toString('dd-MM-yyyy');
		            		  var dateAr = date.split('-');
		            		  var newDate = dateAr[1] + '-' + dateAr[2] + '-' + dateAr[0];
		            		  $('#daily_date').val(newDate);
		            		  
		            		  var newArray = punches.split(',');
		            		  for (var i = 0; i < newArray.length; i++) {
		            			  if(i%2=='0'){
		            				var punch_IN = newArray[i];
		            				html +='<tr class="" rowspan="12"><td class=""><span class="pull-left " id="in_punch"><i class="fa fa-sign-in push-10-r"></i>'+punch_IN+'</span><span class="pull-right push-10-l"><i class="fa fa-close" title="Close"></i></span><span class="attend_edit pull-right" id="attend_edit"><i class="fa fa-pencil" title="Edit"></i></span>&nbsp;&nbsp;</td></tr>';
		            			  }else{
		            				  var punch_OUT = newArray[i];
		            				 html +='<tr class="info" rowspan="12"><td class="info"><span class="pull-left " id="out_punch"><i class="fa fa-sign-out push-10-r"></i>'+punch_OUT+'</span><span class="pull-right push-10-l"><i class="fa fa-close" title="Close"></i></span><span class="attend_edit pull-right" id="attend_edit"><i class="fa fa-pencil" title="Edit"></i></span></td></tr>';	
		            			  } 
		            		    }
		            	 }
			        	html +='</tbody></table></section>';
			        	var oTable=$('#attendance_1').dataTable({
			         		 "aLengthMenu": [
			        	                    [5, 15, 20, -1],
			        	                    [5, 15, 20, "All"] // change per page values here
			        	                ],

			        	                // set the initial value
			        	                "iDisplayLength": 5,
			        	                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
			        	               "oTableTools": {
			       	                    "aButtons": [
			       	                {
			       	                    "sExtends": "pdf",
			       	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
			       	                    "sPdfOrientation": "landscape",
			       	                    "sPdfMessage": "Branch Details"
			       	                },
			       	                {
			       	                    "sExtends": "xls",
			       	                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
			       	                }
			       	             ],
			       	                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

			       	                }
			        	         });
			        	$('.attendance_daily').html(html);
			            
			            }
		            });
	}
	
$(document).on("click",'#attend_edit',function(e){
	e.preventDefault();
	$('.employee_punch_report').addClass('modal');
	$('.employee_punch_report').modal('show');
	var punch_check = $(this).parent().find('span').eq(0).text();
	$('#update_calender').val($('#daily_date').val());
    $('#empl_name,#empl_id').css('color','#ddd');
    $('#empl_id').html($('#empID').val());
    $('#empl_name').html($('#empName').val());
	$('#punch_status').val(punch_check);
	$('#update_calender').datetimepicker({
	    format: 'DD-MM-YYYY',   
	});
});



	/*	$(document).on("dp.change",'#calender', function(e) {
		e.preventDefault();
		var date=$(this).val();
		  initializeAttendancesStatus(date);
	 });*/
	
	$("#at_calender").on("dp.change", function(e) {
		var mnth=$(".datetimepickert").find("input").val();
		//initializeAttendancesStatus(mnth);
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
		// initializeAttendances();
	 });


function initializeAttendancesStatus(mnth){
			var month=$(".datetimepickert").find("input").val();
			var Emonth=$(".datetimepicker").find("input").val();
			Emonth=(moment().month(Emonth).format('MMM-YYYY'));
			var empID=$('input[name=empId]').val();
			var empName=$('input[name=empName]').val();
			var fdata=$('input[name=for]').val();
			
				var message=empName + ',' + Emonth;
			if(typeof mnth == 'undefined'){
				mnth=month;
			}
			
			data={"empId":empID,"mnth":mnth,"dateStatus":"monthly"};
			     $.ajax({
			        	dataType: 'html',
			        	type:"POST",
			            cache: false,
			    		url: App.myUrl("attendance/view/"+fdata+'/'+mnth),
			    		data:data,
			    		beforeSend:function(jqXHR){
			            	App.blocks("#PresentData", 'state_loading');
			            },
			            complete:function(jqXHR,textStatus){
			            	App.blocks("#PresentData", 'state_normal');
			            },
			            success: function(data){
			            var date = mnth;
			            var	data_1 = JSON.parse(data);
			            $('#attendances').fullCalendar('removeEvents');
			            var events_1 = [];
			            	 for(var i=0;i<data_1.daily.length;i++){
			            		 events_1.push({
			                             title:data_1.daily[i].tot_worked,
			                             start: data_1.daily[i].dates
			                         });
			            		 var date_for_cal = data_1.daily[0].dates;
			            	 }
			           
			            eventData = events_1 ;
			            $('#attendances').fullCalendar('addEventSource', eventData);
			            $('#attendances').fullCalendar('rerenderEvents' );
			            $('#attendances').fullCalendar({
			             //timeFormat: 'HH',
			              header :{
			            	  	left: false,
			            		right:'prev title next ,month,agendaWeek,agendaDay',
			            		center: false
			                },
			           buttonText :{
			                	
			                	    month: 'Monthly'
			                 
			                },
			               events : eventData,
			               dayClick: function(date) {
			            	   var mnth =date.format();
			            		  $('#attendances').fullCalendar('changeView', 'agendaDay');
			            		  $('#attendances').fullCalendar('gotoDate', mnth);
			            		  initializeAttendances(mnth);
			            	    },
			            	    axisFormat: 'H:mm',
			            	    timeFormat: {
			            	          agenda: 'H:mm'
			            	     },
			            	    // eventTextColor :'#ff0000',
			            	    	 
			               });
			           
			            var $fcButtons = $('[class*="fc-month-button"]').addClass('btn-default btn'),
			            $oldTable = $('.fc-header-right > table');
			            $('<div>').appendTo('.fc-header-right').append($fcButtons);
			            $oldTable.remove();
			            $("#attendances").fullCalendar('gotoDate', date);
			            }
			            });
					}
	 
	 

$(document).on('click','.fc-prev-button',function(e){
	e.preventDefault();
	 $('.atten-daily').css('margin-left','');
	 $('#attendances').fullCalendar('removeEvents');
	var moment = $('#attendances').fullCalendar('getDate'); 
	var mnth = moment.format();
	//alert(mnth);
	initializeAttendancesStatus(mnth);
});


$(document).on('click','.fc-next-button',function(e){
	e.preventDefault();
	 $('.atten-daily').css('margin-left','');
	 $('#attendances').fullCalendar('removeEvents');
	var moment = $('#attendances').fullCalendar('getDate'); 
	var mnth = moment.format();
	initializeAttendancesStatus(mnth);
});

/* $(document).on('click','.fc-month-button',function(e){
	e.preventDefault();
	 $('.atten-daily').css('margin-left','');
	 $('.attendance_daily').addClass('hide');
	 $('#attendances').fullCalendar('removeEvents');
	var moment = $('#attendances').fullCalendar('getDate'); 
	var mnth = moment.format();
	initializeAttendancesStatus(mnth);
});


$(document).on('click','.fc-agendaWeek-button',function(e){
	e.preventDefault();
	$('.atten-daily').css('margin-left','');
	 $('.attendance_daily').addClass('hide');
	 $('#attendances').fullCalendar('removeEvents');
	var moment = $('#attendances').fullCalendar('getDate'); 
	var mnth = moment.format();
	initializeAttendancesStatus(mnth);
});

$(document).on('click','.fc-agendaDay-button',function(e){
	e.preventDefault();
	$('.atten-daily').css('margin-left','');
	 $('#attendances').fullCalendar('removeEvents');
	var moment = $('#attendances').fullCalendar('getDate'); 
	var mnth = moment.format();
	initializeAttendances(mnth);
}); */
