App.initHelpers(['datetimepicker']);
$( document ).ready(function() {
	//Calender Function //
	function calenderInt(){
		  $.ajax({
	         datatype: "html",
	         type: "POST",
	         url:App.myUrl('calendar/get'),
	        cache: false,
	        success: function (data) {
	         	dataSet = JSON.parse(data);
	         	var value=Array();
	         	for(i=0;i<dataSet.length;i++){
	         	 var title=dataSet[i]['title'];
	         	 var start=dataSet[i]['start'];
	             var end=dataSet[i]['end'];
	         	 var category=dataSet[i]['category'];
	         	 var enabled=dataSet[i]['enabled'];
	         	 var className=dataSet[i]['className'];
	         	 var backgroundColor =dataSet[i]['backgroundColor'];
	         	 var id =dataSet[i]['id'];
	         	 var branches=dataSet[i]['branches'];
	         	 var holiday_id=dataSet[i]['holiday_id'];
	           value.push({
	         		   title:title,
	         		   start:start,
	         		   end:end,
	         		   category:category,
	         		   enabled:enabled,
	         		   className:className,
	         		   backgroundColor:backgroundColor,
	         		   borderColor:backgroundColor,
	         		   borderRadius: '0px',
	         		   id:id,
	         		   holiday_id:holiday_id,
	         		   branches:branches,
	         		  });
	         	}
	     	  
	        $('.event-calender').fullCalendar({
			date: 01,
	        header: {
	        left: 'prev,next today',
	        center: 'title',
	        right: 'month,basicWeek,basicDay'
	     },
	   events:value,
	   eventRender: function (event, element, view) {
	
		if(element.find("div.fc-content").parent().hasClass("enabled"))
	    element.find("div.fc-content").prepend("<i class='si si-trash pull-right push-5-r Delete' data-id='"+event.holiday_id+"' title='Delete' style='cursor:pointer;'></i><i class='si  si-pencil  pull-right  push-5-r' data-toggle='modal' data-target='#modal-normal'  title='Edit' data-id='"+event.holiday_id+"' style='cursor:pointer;'></i>");
		else{
		element.find("div.fc-content").prepend("<i class='si si-check pull-right push-5-r Enable' data-id='"+event.holiday_id+"' data-val='"+event.enabled+"' title='Enable' style='cursor:pointer;'></i>");
		}
	   },
    });
	} 
   });
  }
	//initailize the function here//
	  calenderInt();
});
//form field hide and show//
$(function () {
    $('input[name=branches]').change(function(){
      var val=$(this).val();
      var el=$('#add-event');
    	if(val =="all"){   	
    		el.find('#branch').attr('disabled','disabled');
    		el.find('.branch').addClass('hide');
    	}
    	else{
    		el.find('#branch').removeAttr('disabled');
    		el.find('.branch').removeClass('hide');
    	}
      });
})
//Enable Option//

//Edit Option//
$(document).on("click",".Enable",function() {
        // Show the user a swal confirmation window
	  var id=$(this).data('id');
	  var val=$(this).data('val');
         swal({
                 title: "Are you sure!",
                 text: 'Do You Want Enable this Event?',
                 type: 'warning',
                 confirmButtonColor: '#14adc4',
                 confirmButtonText: 'Yes,Im Sure!',
                 showCancelButton: true,
                 closeOnConfirm: false
             },
             function() {
                 $.ajax({
                     type: "POST",
                     url: App.myUrl("calendar/enable"),
                     data: {id:id,value:val},
                     success: function(data){
                         if(isJson(data)==false){
                           swal({title:'Error', text:'Error Occurred,Please Try Again', type:'error'});
                           }
                         else
                             data = JSON.parse(data);
                       if(data[0]==true){
                    	  
                        swal({title:'Checked!', text:'Event Enabled succesfully...', type:'success'},function() {
                               window.location.href = App.myUrl("calendar/edit");
                        });
                      }else if(data[0]==false){
                          swal({title:'Error', text:data["info"], type:'error'});
                       }}
      }); });
});
//Edit Option//
$(document).on("click",".Delete",function() {
        // Show the user a swal confirmation window
	  var id=$(this).data('id');
         swal({
                 title: "Are you sure!",
                 text: 'Do You Want Delete this Event?',
                 type: 'warning',
                 confirmButtonColor: '#14adc4',
                 confirmButtonText: 'Yes,Im Sure!',
                 showCancelButton: true,
                 closeOnConfirm: false
             },
             function() {
                 $.ajax({
                     type: "POST",
                     url: App.myUrl("calendar/delete"),
                     data: {id:id},
                     success: function(data){
                         if(isJson(data)==false){
                           swal({title:'Error', text:'Error Occurred,Please Try Again', type:'error'});
                           }
                         else
                             data = JSON.parse(data);
                       if(data[0]==true){
                        swal({title:'Checked!', text:'Event Deleted succesfully...', type:'success'},function() {
                               window.location.href = App.myUrl("calendar/edit");
                        });
                      }else if(data[0]==false){
                          swal({title:'Error', text:data["info"], type:'error'});
                       }}
      }); });
});
//Form Validation//
$(document).ready(function () {
      //validation rules//
	$('#category').chosen();
	$('#branch').chosen();
    $.validator.setDefaults({ ignore: ":hidden:not(select)" })
    $("#add-event").validate({
    	  ignore: [],
           errorClass: 'help-block text-right animated fadeInDown',
           errorElement: 'div',
           errorPlacement: function(error, e) {
               jQuery(e).parents('.form-group > div').append(error);
           },
           highlight: function(e) {
               var elem = jQuery(e);

               elem.closest('.form-group').removeClass('has-error').addClass('has-error');
               elem.closest('.help-block').remove();
           },
           success: function(e) {
               var elem = jQuery(e);

               elem.closest('.form-group').removeClass('has-error');
               elem.closest('.help-block').remove();
         },
         //ajax call//
         submitHandler:function(element){
             $.ajax({
                type:"POST",
               cache: false,
               url: $(element).attr('action'),
               data:$(element).serialize(),
               beforeSend:function(jqXHR,settings){
            	   //   $(element).find("button[type=submit]").button({loadingText: 'Submitting...'}).button('loading');
               },
               complete:function(jqXHR,textStatus){
            	   //  $(element).find("button[type=submit]").button('reset');
               },
               error:function(jqXHR,textStatus,errorThrown ){
            	   $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
            			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                           <p class="push-15">Can\'t Subscribe. &nbsp;&nbsp;&nbsp;&nbsp;Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                       </div>').show().fadeOut(3000);
               },
               success: function(data){
             	  if(isJson(data)==false)
             		  $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
             				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                              <p class="push-15">Can\'t Subscribe. &nbsp;&nbsp;&nbsp;&nbsp;Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                          </div>').show().fadeOut(3000);
             	  else
             		  data = JSON.parse(data);
             	if(data[0]==true){
             		$(".err-notification").empty().html('<div class="alert alert-dismissable alert-success">\
             				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
              				<p class="font-s18"><p class="font-s18 ">Event Added Successfully!</p>\
              		</div>').show().fadeOut(5000,function(){
            			window.location.reload(true);
             		});
             		$('#add-event')[0].reset();
                 }else if(data[0]==false){
                	 $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
                			 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                             <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                         </div>').show().fadeOut(3000);
                 }
               }
             });
                 return false;
         },
        //place all errors in a <div id="errors"> element 
        rules: {
        	category: {
        		 required: true,
            }, 
            title: {
       		 required: true,
           },
           branches: {
            	required: true,
            },
            branch: {
          	  required:true,
            	minlength: 1
               },
            start: {
             	  required:true,
                 },
            end:{
               	  required:true,
                },
        },
        messages: {
        	category: {
       		 required: 'Select The Category..!'
           },  
           title: {
         		 required: 'Enter Your Title..!'
             },
           start: {
           	required: 'Please Select Start Date!'
           },
          end: {
              	required: 'Please Select End Date!'
              },
          branches: {
        	  required:'Please select one Option..!',
             },
             branch:{
           	  required:'Select the Branches..!',
                },
        }
    });
});