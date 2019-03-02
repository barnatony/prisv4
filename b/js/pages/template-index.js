 /*
 *  Document   :admin-template.js
 *  Author     :vishnu
 *  Description:Admin Template (admin template page)
 *
 */

	jQuery('.js-summernote').summernote({
        height: 350,
        minHeight: null,
        maxHeight: null
    });
$("#add-template").validate({
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
       		$(".err-notification").empty().html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p class="font-s18 "> Template Inserted</p></div>').show().fadeOut(3000);
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
template_name: {
         required: true
     },  
     template_subject:{
     	required:true,
     	minlength:5,
     },
     message:{
     	required:true,	
     	minlength:10,
     }
     
 },
 messages: {
 	template_name: {
    	 required:'Enter Name of the template',
    },
    template_subject: {
 	   required:'Please Enter the subject',
 	   minlength:"Enter minimum 10 chars",
    },
    message:{
 	   required:'Please Enter the message',
 	  minlength:"Enter minimum 10 chars",
    }
    
 }
 
});


/* Delete Template */
$(".delete").on("click",function(e){
	e.preventDefault();
		swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this Template!',
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
              		swal('Deleted!', 'Template Deleted succesfully.', 'success');
              		
              	}else if(data[0]==false){
              		$(".err-notification").empty().html('<div class="alert alert-danger alert-dismissable">\
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                              <p class="push-15">'+data["info"]+'&nbsp;&nbsp;&nbsp;&nbsp;<p>\
                          </div>').show().fadeOut(3000);
                  }
                }
              });
            
        });
    
	
});

/* Delete Template End */
