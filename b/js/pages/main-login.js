 /*
 *  Document   :main-login.js
 *  Author     :vishnu
 *  Description: login Functionality (main login page js)
 *
 */
//Initialize when page loads
    $(window).load(function(){
        $('#modal-register').modal('show') ;
    });
$.validator.addMethod("regx", function(value, element, regexpr) {          
		return regexpr.test(value);
		}, "Letters only");

  $("#user-login").validate({
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
       username = $("#login-username").val();
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
        		window.location.href = data['data'];
        	}
        	else if(data[0]==false){
        		$(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                        <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                    </div>').show().fadeOut(3000);
        		$('#user-login')[0].reset();
            }
          }
        });
            return false;
    },
    rules: {
  	    username:{
    	   required:true,
       },
       password:{
      	 required:true
      }
        
    },
    messages:{
  	  
    username:{
  	   required:"Please enter your name!",
     },
    password:{
    	 required:"Please enter your password!",
    }
        }
});