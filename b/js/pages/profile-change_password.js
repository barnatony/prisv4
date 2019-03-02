 /*
 *  Document   :profile-change-password.js
 *  Author     :vishnu
 *  Description: user profile edit (user profile edit general page js)
 *
 */
//Initialize when page loads

$(document).ready(function () {
	//Click event to scroll to back
	  $("#profile-secure-new").validate({
		   ignore: [],
	      errorClass: 'help-block text-right animated fadeInDown',
	      errorElement: 'div',
	      errorPlacement: function(error, e) {
	          jQuery(e).parents('.form-group >div').append(error);
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

	       		$(".err-notification").html('<div class="alert alert-success push-30"><p class="font-s18">your password changed</p></div>').fadeOut(3000,function(){
	       		 window.location.href = App.myUrl("main/login");
	       		})
	       		$('#profile-secure-new')[0].reset();
	       	}
	       	else if(data[0]==false){
	       		$(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
	                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
	                       <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
	                   </div>').show().fadeOut(3000);
	           }
	         }
	       });
	           return false;
	   },
	   rules: {
		   user_profile_new_password:{
	     	   required:true,
	     	   minlength:5,
	        },
	        user_profile_confirm_password: {
	             required: true,
	             equalTo:"#user-profile-new-password"
	          },
	    },
	   messages:{
		      user_profile_new_password:{
		    	   required: 'Please provide a password',
                   minlength: 'Your password must be at least 5 characters long'
	         },
	         user_profile_confirm_password: {
	        	 required: 'Please provide a password',
                 minlength: 'Your password must be at least 5 characters long',
                 equalTo: 'Please enter the same password as above'
	              
	            }
	       }
	});  
	
	});
//password visibilty//
$(".pwd-visibility").click(function(){
	var el = $(this).parent().parent().find('.password');
    if (el.attr("type")=='password')
    	
    	el.attr("type","text");
    else
    	el.attr("type", "password");
});