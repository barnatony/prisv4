 /*
 *  Document   :profile-edit.js
 *  Author     :vishnu
 *  Description: user profile edit (user profile edit personel page js)
 *
 */

//Initialize when page loads
$(document).ready(function () {
	//personel page form//
  var currentdate = new Date();
  App.initHelpers(['datetimepicker','maxlength']);
  $.validator.addMethod("regx", function(value, element, regexpr) {          
		return regexpr.test(value);
		}, "Letters only");
  $("#profile-edit").validate({
	   ignore: [],
       errorClass: 'help-block text-right animated fadeInDown',
       errorElement: 'div',
       errorPlacement: function(error, e) {
           jQuery(e).parents('.form-material,.form-radio').append(error);
       },
       highlight: function(e) {
           var elem = jQuery(e);

           elem.closest('.form-material,.form-radio').removeClass('has-error').addClass('has-error');
           elem.closest('.help-block').remove();
       },
       success: function(e) {
           var elem = jQuery(e);

           elem.closest('.form-material,.form-radio').removeClass('has-error');
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
        	  $(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                      <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                  </div>').show().fadeOut(3000);
          },
          success: function(data){
        	  if(isJson(data)==false)
        		  $(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                          <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                      </div>').show().fadeOut(3000);
        	  else
        		  data = JSON.parse(data);
        	if(data[0]==true){
        		$(".notifications").html('<div class="alert alert-success push-30"><p>'+data["info"]+'</p></div>').show().fadeOut(3000,function(){
        			window.location.reload(true);
         		});
        		
        	}
        	else if(data[0]==false){
        		$(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                        <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                    </div>').show().fadeOut(3000);
            }
          }
        });
            return false;
    },
    rules: {
    	
        profile_first_name:{
    	   required:true,
           minlength:5,
       },
      user_profile_email: {
    	   required: true,
           regx:/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
           email: true,
          },
     user_signup_gender:{
    	   required:true,
    	  
       },
     },
    messages:{
         
         profile_first_name:{
      	     required:"Please Enter first name",
             minlength:"Enter minimum 5 chars",
         },
        user_profile_email: {
         	 required:"Please enter your email..!",
          	regx:"Enter only valid mail address..!",
             },
         user_signup_gender:{
        	   required:"This field should not be empty",
        	  
           },
        }
}); 
  //img crop js//
 
var croppicHeaderOptions = {

      uploadUrl:App.myUrl('profile/user_imgcrop/save'),
      cropData:{
          "email":1,
          "rnd":"rnd"
      },
      cropUrl: App.myUrl('profile/user_imgcrop/crop'),
      outputUrlId:'image_url',
      customUploadButtonId:'cropContainerHeaderButton',
      modal:false,
      loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
      onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
      onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
      onImgDrag: function(){ console.log('onImgDrag') },
      onImgZoom: function(){ console.log('onImgZoom') },
      onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
      onAfterImgCrop:function(){console.log('onAfterImgCrop')}
  }
  croppic = new Croppic('croppic', croppicHeaderOptions);

 //general page //
  $("#profile-secure").validate({
	   ignore: [],
      errorClass: 'help-block text-right animated fadeInDown',
      errorElement: 'div',
      errorPlacement: function(error, e) {
          jQuery(e).parents('.form-material,.form-radio').append(error);
      },
      highlight: function(e) {
          var elem = jQuery(e);

          elem.closest('.form-material,.form-radio').removeClass('has-error').addClass('has-error');
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
                 </div>').fadeOut(5000);
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
           }
         }
       });
           return false;
   },
   rules: {
	   password: {
         required: true,
         minlength:5,
       },
    },
   messages:{
	   password:{
		   required: 'Please provide a password',
           minlength: 'Your password must be at least 5 characters long'
         },
       }
});
  
  //image Update form//
  $("#image-update").on("submit",function(e){
		e.preventDefault();
		element=this;
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
	                </div>').fadeOut(3000);
	        },
	        success: function(data){
	      	  if(isJson(data)==false)
	      		  $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
	                        <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
	                    </div>').fadeOut(3000);
	      	  else
	      		  data = JSON.parse(data);
		       	if(data[0]==true){
		       		
		       		$(".err-notification").html('<div class="alert alert-success push-30"><p class="font-s18">You picture updated successfully.</p></div>').fadeOut(3000,function(){
		       		 window.location.href = App.myUrl("profile/edit");
		       		})
		       	 
		       	 }
	      	else if(data[0]==false){
	      		$(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
	                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
	                      <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
	                  </div>').fadeOut(3000);
	          }
	        }
	      });
	});
//image reset and set default image//  
  $("#no_image").on("click",function(){
	  croppic.reset();
	  $("#croppic").css('background-image','url("'+App.myUrl('img/avatar.jpg',true)+'")');
	  $("#image_url").val("");
	  //$("#croppic").attr('style','background-image:url("'+App.myUrl('img/avatar.jpg',true)+'")');
  });
  //image reset //
  $("#cancel").on("click",function(){
	  croppic.reset();
	  window.location.href = App.myUrl("profile/edit");
  });
  
});

//profile pic hover function//
$('.user-img').hover(function () {
	$('.change').removeClass('hide');
});
$('.user-img').on( "mouseleave", function() {
 $('.change').addClass('hide');
});

//user name avalibility check// 
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

/* Email Preferences Off/On*/
$(".enable").change(function(e) {
	e.preventDefault();
	var type= $(this).data("type");
	var active = $(this).data("value");
	 var target = $(this);
    var data={"notification_type":type,"enable":active};
    //Ajax Call//
	$.ajax({
        type:"POST",
        cache: false,
		url: App.myUrl('notification/mute'),
        data:data,
        success: function(data){
        	 if(isJson(data)==false)
            		$(".notification").html('<div class="alert alert-danger alert-dismissable">\
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                            <p class="push-15">Error Occurred<p>\
                        </div>').show().fadeOut(3000);
            	  else
            		  data = JSON.parse(data);
        		if(data[0]==true){
        			if(active=="1"){
        				target.data("value",0).removeAttr("title").attr("title","Notifications Show");
        				$(".notification").html('<div class="alert alert-success alert-dismissable">\
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                            <p class="push-15">Notifications Disabled<p>\
                        </div>').show().fadeOut(3000);
        			}else{
        				target.data("value",1).removeAttr("title").attr("title","Notifications Off");
        				$(".notification").html('<div class="alert alert-success alert-dismissable">\
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                                <p class="push-15">Notifications Enabled<p>\
                            </div>').show().fadeOut(3000);
                   	}	
         }
    	     else if(data[0]==false){
    	      		$(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
    	                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
    	                      <p class="push-15">Error Occurred<p>\
    	                  </div>').fadeOut(3000);
    	          }
        		
        }
    });
});
  
