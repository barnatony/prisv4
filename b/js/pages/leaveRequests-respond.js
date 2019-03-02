$(document).ready(function(){
    respondFormValidate();
});

$(document).on("click",".response_btn",function(){
	$("#status_value").val($(this).data("status"));
	$("#respondForm").submit();
});

function respondFormValidate(){
	$("#respondForm").validate({
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
	var value=$("#status_value").val();
	
	if(value=="A"){
		  confirmText = 'Do you want to Approve this Leave?';
		  successText = 'Leave Approved successfully.';
	}else{
		confirmText = 'Do you want to Reject this Leave?';
		successText = 'Leave Rejected successfully.';
	}
  	var data = new FormData($(element)[0]);
  	var e=element;
  	$.ajax({
          type:"POST",
          cache: false,
          processData: false,
  		contentType: false,
          url: App.myUrl('leaveRequests/respond_update/'),
          data:data,
          beforeSend:function(jqXHR,settings){
         	 $(e.target).button({loadingText: 'Loading...'}).button('loading');
         	 App.blocks('#respondFormBlock', 'state_loading');
          },
          complete:function(jqXHR,textStatus){
        	  $(e.target).button('reset');
        	  App.blocks('#respondFormBlock', 'state_normal');
          },
          error:function(jqXHR,textStatus,errorThrown ){
        	  $(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>\
                      <p class="push-15">&nbsp;Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                  </div>').show().fadeOut(3000);
          },
         success: function(data){
        	  if(isJson(data)==false)
        		swal({title:'Error', text:'Error Occurred,Please Try Again', type:'error'});
        	  else
        		  data = JSON.parse(data);
        	if(data[0]==true){
        		swal({title:'Success', text:successText, type:'success'},function() {
        			if(data['from']=='app'){
        				var table = $('.myteam-dataTable').DataTable();
        				table.draw(false);
        				$('#approveReject-modal').modal('hide');
        			}else{
        				window.location.href = location.href;
        			}
        	});
           }else if(data[0]==false){
        	   swal({title:'Error', text:'Error Occurred :'+data[2], type:'error'});
            }
          }
        });
      
  
      return false;
},
rules: {
	   remarks:{
	   required:true,
 },
  
},
messages:{
	  
remarks:{
	   required:"Please Specify a Reason..!!",
},
  }
});


}
