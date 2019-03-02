//Initialize when page loads

//form validation//
  $.validator.addMethod("regx", function(value, element, regexpr) {          
		return regexpr.test(value);
		}, "Letters only");
  var $registerValidate = $("#add-user").validate({
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
       username = $("#username").val();
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
        		$(".err-notification").empty().html('<div class="alert alert-success alert-dismissable">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                        <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                    </div>').show().fadeOut(3000);
                    $("#add-user")[0].reset();
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
     name: {
          required:true,
          minlength:5
        },
      username:{
    	   required:true,
           minlength:5,
       },
      email: {
    	   required: true,
           regx:/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
           email: true,
          },
       password:{
      	   required:true,
      	  minlength:5,
         },
         c_password:{
        	   required:true,
        	   equalTo:"#password"
           }, 
       
   },
    messages:{
    	name: {
            required:"Enter your name",
            minlength:"Name atleast 5 characters"
          },
         username:{
        	  required:"Please enter your name.",
               minlength:"Minimum length is 5."
         },
         email: {
        	 required:"Please enter your email.",
         	regx:"Enter only valid mail address.",
            },
         password:{
        	 required: 'Please provide a password',
             minlength: 'Your password must be at least 5 characters long'
           },
           c_password:{
        	   required: 'Please provide a password',
               minlength: 'Your password must be at least 5 characters long',
               equalTo: 'Please enter the same password as above'
           },
         
        }
});
 //user name avilaiblity//
  $("#username").on('blur',function(){
		$.ajax({
	        type:"POST",
	        url: App.myUrl('users/availability/un/'),
	        data: {"term":$(this).val()},
	        
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
	        	data=JSON.parse(data);
	        	
	        	if(data[0]="true"){
	        		if(data["rowCount"]>0)
	        			errors = { username: data["data"] };
	        		else
	        			errors = { username: data["data"] };
	        	}else{
	        		errors = { username: "Error Occurred. "+data["data"] };
	        	}
	             /* Show errors on the form */
	        	 $registerValidate.showErrors(errors); 
	        }
	        });
	  });
