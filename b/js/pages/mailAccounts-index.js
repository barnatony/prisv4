$.validator.addMethod("regx", function(value, element, regexpr) {          
		return regexpr.test(value);
		}, "Letters only");
$("#add-account").validate({
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
         cache: false,
 		processData: false,
 		contentType: false,
         url: $(element).attr('action'),
         data: new FormData($(element)[0]),
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
       	  if(isJson(data)==false){
       		  $(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                       <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                   </div>').show().fadeOut(3000);
       	  }else
       		  data = JSON.parse(data);
       	if(data[0]==true){
       		$(".err-notification").empty().html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p class="font-s18 ">'+data['info']+'</div>').show().fadeOut(3000);
       		window.location.reload();
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
  host: {
         required: true
     },  
     username:{
    	 required: true,
         regx:/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
         email: true,
     },
     password: {
           required:true,
   	       minlength:5,
      },
     port:{
     	required:true,	
     	 regx:/^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/
     },
     secure:{
    	 required:true,	
     }
     
 },
 messages: {
 	host: {
    	 required:'Enter the Host Name',
    },
    usename: {
    	 required:"Please enter your email..!",
      	regx:"Enter only valid mail address..!",
    },
    password: {
    	 required: 'Please provide a password',
         minlength: 'Your password must be at least 5 characters long'
     },
    
    port:{
 	   required:'Please Enter the Port Number',
 	   regx:"Enter only Numeric values",
    },
    secure:{
   	 required:"Please Select One Secure Method",	
    }
 }
 
});
$(".pwd-visibility").click(function(){
	var el = $(this).parent().parent().find('.password');
    if (el.attr("type")=='password')
    	
    	el.attr("type","text");
    else
    	el.attr("type", "password");
});

$(document).on('change','.active',function(e){
    e.preventDefault();
    var element =$(this);
    var id = $(this).data("id");
	var active = $(this).data("value");
    var data={"id":id,"active":active};
    $.ajax({
        type:"POST",
        url: App.myUrl('mailAccounts/setActive'),
        data:data,
        cache: false,
        success: function(data){
            data = JSON.parse(data);
            if(data[0] == false){
        		$(".notification").html('<div class="alert alert-danger alert-dismissable">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ãƒâ€”</button>\
                        <p class="push-15">Error Occurred<p>\
                    </div>').show().fadeOut(3000);
            }else{
                if(active==1){
                     element.data('value',0);
                     $(".notification").html('<div class="alert alert-success alert-dismissable">\
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                             <p class="push-15">Set Active&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                         </div>').show().fadeOut(2000);
                    
                }else{
                     element.data('value',1);
                     $(".notification").html('<div class="alert alert-danger alert-dismissable">\
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                             <p class="push-15">Set InActive&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                         </div>').show().fadeOut(2000);
                }}
        
        }
    });
        
});

$('#test-mail').on('shown.bs.modal', function (e) {
	$(e.target).find("input[name=id]").val($(e.relatedTarget).data("value"));
});

$.validator.addMethod("regx", function(value, element, regexpr) {          
	return regexpr.test(value);
	}, "Letters only");

$("#test-email").validate({
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
	var email = $("#email").val();
	var val= $("#id").val();
	var data={"email":email,"id":val};
	
   $.ajax({
     type:"POST",
     cache: false,
     url:  App.myUrl('mailAccounts/test'),
     data:data,
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
   	  if(isJson(data)==false){
   		  $(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                   <p class="push-15">Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
               </div>').show().fadeOut(3000);
   	  }else
   		  data = JSON.parse(data);
   	if(data[0]==true){
   		$(".notifications").empty().html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p class="font-s18 ">Mail Sent Successfully</p></div>').show().fadeOut(3000);
   		
       }else if(data[0]==false){
     	  $(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
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
email: {
     required: true
 },  
 
},
messages: {
	email: {
	 required:'Enter the Email Id',
},
}
});


/* Delete Account */
$(".delete").on("click",function(e){
	e.preventDefault();
		swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this Account!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d26a5c',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            html: false
        }, function () {
        	$.ajax({
        		type:"POST",
                cache: false,
                processData: false,
        		contentType: false,
                url: $(e.target).data('url'),
                beforeSend:function(jqXHR,settings){
               	  $(e.target).button({loadingText: 'Deleting...'}).button('loading');
                },
                complete:function(jqXHR,textStatus){
              	  $(e.target).button('reset');
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
              		$(e.target).parents("tr").remove();
              		swal('Deleted!', 'Account Deleted succesfully.', 'success');
              		
              	}else if(data[0]==false){
              		swal('Not Deleted!', data['info'], 'error');
                  }
                }
              });
            
        });
    
	
});

/* Delete Template End */