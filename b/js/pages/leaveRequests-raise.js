$(document).ready(function () {
	
  var currentdate = new Date();
  //keepInvalid:false;
  //3.allowInputToggle:true
  //duration_from
  App.initHelpers(['maxlength','slimscroll','select2','datetimepicker']);
  
  
  
  

	

	
	
 /*     $('#leave_rule').on("change",function(){
    	  var availableLeave = $(this).find(':selected').attr('data-available');
    	  var durationSelected = parseFloat($("#duration").val());
    	  if(durationSelected > availableLeave)
    		  $(this).parent().parent().parent().addClass("has-error").append('<div class="help-block text-right">You don\'t have balance in this leave type for the duration you specified.<br>You can choose LOP </div>');
    	  else
    		  $(this).parent().parent().parent().removeClass("has-error").children('.help-block').remove();
      }); */
      
      
  
  $(".withdraw").on("click",function(){
    	  var id= $(this).data("id");
    		var data={"id":id,"value":"W","remarks":"withdrawed"};
    		$.ajax({
    	         type:"POST",
    	         cache: false,
    	 		 url: App.myUrl('leaveRequests/update/'),
    	         data:data,
    	         beforeSend:function(jqXHR,settings){
    	        	 //App.blocks("#"+user_id, 'state_loading');
    	         },
    	         complete:function(jqXHR,textStatus){
    	        	 //App.blocks("#"+user_id, 'state_normal');
    	         },
    	         success: function(data){
    	       	  if(isJson(data)==false)
    	       		swal({title:'Error Occured!', text:'Leave Can\'t be withdrawed.', type:'danger',showConfirmButton:false});
    	       	  else
    	       		  data = JSON.parse(data);
    	       	if(data[0]==true){
    	       			//$("#"+user_id+" .resetpassword").removeAttr("data-original-title").attr("data-original-title","Password Reset Link Sent").removeClass("text-danger text-primary").addClass("text-success").html('<i class="fa fa-check"></i>').unbind("click");
    	       			swal({title:'Information', text:'Leave withdrawed successfully', type:'success'},function() {
    	       			 window.location.href = App.myUrl("leaveRequests/raise");
    	       			});
    	       			}
    	       	else if(data[0]==false){
    	       		swal({title:'Error Occured!', text:'Leave Can\'t be withdrawed.', type:'danger',showConfirmButton:false});
    	       			//$("#"+user_id+" .resetpassword").removeAttr("data-original-title").attr("data-original-title","Can't Reset. Try Again Later").removeClass("text-danger text-primary").addClass("text-danger").html('<i class="fa fa-exclamation"></i>').unbind("click");
    	           }
    	         }
    	     });;
      });
  /*

  $('#leave-rule').select2({
  		ajax: {
  		    url: App.myUrl('leaveRules/get'),
  		    delay: 350,
  		    data: function (params) {
  		        var query = {
  		          s: params.term
  		        }
  		        return query;
  		      },
  		    processResults: function (data) {
  		    	data= JSON.parse(data);
  		    	options =[];
  		    	$.each(data, function (i, option) {
  		    		temp ={};
  		            temp["text"] = option.alias;
  		            temp["id"] = option.leave_rule_id;
  		            options.push(temp);
  		        }); 
  		    	console.log(options);
  		      return {
  		    	 
  		        results: options
  		      };
  		    }
  		  }		
  });
  */
//Initialize when page loads

//form validation//
  $.validator.addMethod("regx", function(value, element, regexpr) {          
		return regexpr.test(value);
		}, "Letters only");
  $.validator.addMethod("lesserThanDuration", function(value, element) {
	  element = $(element);
	  var selected = element.find(':selected').val();
	  var availableLeave = element.find(':selected').attr('data-available');
	  var durationSelected = parseFloat($("#duration").val());
	  if(selected == "lop" || selected =="od" || selected =="wfh" || selected =="Otr")
		  return true;
	  if(durationSelected > availableLeave )
		  return false;
	  else
		  return true;
	   // return this.optional(element) || (parseFloat(value) > 0);
	}, "You don't have sufficient balance in the leave type selected.You can raise the Leave as LOP");
  
  var $registerValidate = $("#leave-request").validate({
	  	ignore: [],
       errorClass: 'help-block text-right animated fadeInDown',
       errorElement: 'div',
       errorPlacement: function(error, e) {
           jQuery(e).parents('.form-material').append(error);
       },
       highlight: function(e) {
           var elem = jQuery(e);
           
	           elem.closest('.form-material').removeClass('has-error').addClass('has-error');
	           elem.closest('.help-block').remove();
           
       },
       success: function(e) {
    	   var elem = jQuery(e);

           elem.closest('.form-material').removeClass('has-error');
           elem.closest('.help-block').remove();
     },
     //ajax call//
    submitHandler:function(element){
       $.ajax({
          type:"POST",
          url: $(element).attr('action'),
          data: $(element).serialize(),
          beforeSend:function(jqXHR,settings){
         	  $(element).find("button[type=submit]").button({loadingText: 'Submitting...'}).button('loading');
          },
          complete:function(jqXHR,textStatus){
        	  $(element).find("button[type=submit]").button('reset');
          },
          error:function(jqXHR,textStatus,errorThrown ){
        	  $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                      <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                  </div>').show().fadeOut(3000);
          },
          success: function(data){
        	  if(isJson(data)==false)
        		  $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                          <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                      </div>').show().fadeOut(3000);
        	  else
        		  data = JSON.parse(data);
        	 
        	  if(data[0]==true){
        		$("#leave-request").hide();  
        		$(".notifications").empty().html('<div class="alert alert-success alert-dismissable">\
                        <p class="h3 push-15"><i class="fa fa-check fa-1x"></i>  Leave Request Raised Successfully.&nbsp;&nbsp;&nbsp;&nbsp;<p>\
        			</div>\
        			<div class="push-10-l">\
        			<p>View All Leave Requests<a title="LeaveRequest View" style="border-radius: 20px;" href='+App.myUrl('leaveRequests')+'> here</a>\
        				  or <a title="LeaveRequest Raise" style="border-radius: 20px;" href='+App.myUrl('leaveRequests/raise')+'>raise one.</a></p></div>').show();
        	}
        	else if(data[0]==false){
        		$(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                        <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                    </div>').show();
            }
          }
        });
            return false;
    },
    rules: {
     reason: {
          required:true,
          minlength:5
        },
        leave_rule:{ required:true,lesserThanDuration : true }
       
         
       
       
   },
    messages:{
    	reason: {
            required:"Enter any reason",
            minlength:"Name atleast 5 characters"
          }
         
        }
});
 

});


function checkAvailability(e){
	var from = $("#duration-from").val();
	var to = $("#duration-to").val();
	var leave_rule = $("#leave_rule").val();
	var employee_id =$("#employee_id").val();
	var duration = $("#duration").val();
	if(from && to && leave_rule && duration && leave_rule!='lop' && leave_rule!='od' && leave_rule!='wfh'&& leave_rule!='Otr'){
		$.ajax({
	          type:"POST",
	          url: App.myUrl('leaveRequests/check/'),
	          data: {employee_id:employee_id,leave_rule:leave_rule,from:from,to:to,duration:duration},
	          beforeSend:function(jqXHR,settings){
	         	  //$(element).find("button[type=submit]").button({loadingText: 'Submitting...'}).button('loading');
	          },
	          complete:function(jqXHR,textStatus){
	        	  //$(element).find("button[type=submit]").button('reset');
	          },
	          error:function(jqXHR,textStatus,errorThrown ){
	        	  $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
	                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
	                      <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
	                  </div>').show().fadeOut(3000);
	          },
	          success: function(data){
	        	  if(isJson(data)==false)
	        		  $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
	                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
	                          <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
	                      </div>').show().fadeOut(3000);
	        	  else
	        		  data = JSON.parse(data);
	        	 
	        	  if(data[0]==true){
	        		  
	        		  if(data["rowCount"]>0 && leave_rule!='lop' && leave_rule!='od' && leave_rule!='wfh'&& leave_rule!='Otr' ){ //leave type != lop
	        			  if($("#holiday-found").hasClass("hide")){
		        				$("#holiday-found").removeClass("hide")
		        				$("#holiday-found").show();
		        			}
		        		  $("#considerLop").val(1);
	        		  }else{
		        		$("#holiday-found").hide();
		        		$("#considerLop").val(0);
	        		  }
	        	}
	        	else if(data[0]==false){
	        		$(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
	                        <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
	                    </div>').show().fadeOut(3000);
	            }
	          }
	        });
	}
}
$("#leave_rule,input[name=from_half],input[name=to_half]").on("change",function(){
	calculateDuration();
	checkAvailability($(this));
});

$("#duration-from").on("dp.change", function (e) {
	$('#duration-to').data("DateTimePicker").minDate(e.date);
    calculateDuration();
	checkAvailability($(this));
});
$("#duration-to").on("dp.change", function (e) {
    $('#duration-from').data("DateTimePicker").maxDate(e.date);
    calculateDuration();
	checkAvailability($(this));
});

function calculateDuration(){
		var from = $('#duration-from').val();
		var to = $('#duration-to').val();
		if(from && to){
		var mdy = daydiff(parseDate(from), parseDate(to));
		
		mdy = (parseFloat(mdy)-1)+
			parseFloat(($("input[name='from_half']:checked").val()=='FH')?1:0.5)+
			parseFloat(($("input[name='to_half']:checked").val()=='SH')?1:0.5);
		mdy=mdy.toFixed(1);
		$("#duration").val(mdy);
		$("#day-text").text(mdy);
		if($("#duration-div").hasClass("hide")){
			$("#duration-div").removeClass("hide")
			$("#duration-div").toggle("show");
		} 
			
		}else{
			$("#duration-div").hide();
			$("#duration").val();
			$("#day-text").text("");
		}
}
function daydiff(first, second) {
	return (second-first)/(1000*60*60*24)
	}
	function parseDate(str) {
	    var mdy = str.split('/');   
	    return  new Date(mdy[2], mdy[1] - 1, mdy[0]);
	}	