/*$("ul.pagination > li a[href]").click(function(e){
	e.preventDefault(); 
    var value=$(this).attr("href");
	var arr=value.split('/');
	var last=arr.reverse()[0];
    data={n:last};
$.ajax({
      type:"POST",
      cache: false,
	  url: App.myUrl('users/ajx_load'),
      data:data,
      beforeSend:function(jqXHR,settings){
      	App.blocks('#my-block', 'state_loading');
      	$('#my-block').toggleClass('inactive');
      },
      complete:function(jqXHR,textStatus){
      	App.blocks('#my-block', 'state_normal');
      	$('#my-block').toggleClass('inactive');
      },
      success: function(data){
    	  if(isJson(data)==false)
    		$(".err-notification").html("Can't be Loaded");
    	  else
    		  data = JSON.parse(data);
    	if(data[0]==true){
    	
    		var users = data["data"];
    		userTiles="";
    		for(i=0;i<users.length;i++){
               user=users[i];
      //password reset//
    			var passwordReset="";
    			if(!(user["p_reset_token"])){
    				passwordReset ='<button class="text-danger resetpassword" type="button" data-toggle="tooltip" data-trigger="hover" data-original-title="Send Reset Password Link" data-value="'+user["user_id"]+'"><i class="fa fa-undo"></i></button>';
    			}
    			else{
    				passwordReset='<button class="text-success" type="button" data-toggle="tooltip" data-trigger="hover" data-original-title="Password Reset link Sent" data-value="'+user["user_id"]+'"><i class="fa fa-check"></i></button>';
    			}
    	//last login//	
    			var lastLogin="";
    			
    			if(user["date"] !=null){
    				lastLogin ='<div class="h5 font-w300 text-muted" data-toggle="tooltip" data-trigger="hover" data-original-title="Last Login on&nbsp; '+user["date"]+'">'+user["date"]+'</div>';
    			}else
    				{lastLogin ='<div class="h5 font-w300 text-muted">&nbsp;</div>';}
    			$(function(){ $(".tool").tooltip(); });
    			//admin identifier//		
    			var adminContent="";
    			if(user["privilage"]=="Admin")
    				adminContent = '<i class="fa fa-user push-5-r text-success user-type"></i>';
    			
    			userTiles += '<div class="col-sm-6 col-lg-3">\
    			   <div class="block box-shaddow" id="253">\
    			      <div class="block-header">\
    			          <ul class="block-options">\
    			             <li>'+
    			             passwordReset
    				         +'</li>\
    			          </ul>'+
    			         lastLogin
    			           +'</div>\
    			 <div class="block-content text-center">\
    			   <div class="">\
    			       <img class="img-avatar img-avatar96" src='+App.myUrl(user["image"])+' alt="">'+
    			       adminContent
    			           +'</div>\
    			       <div class="push-15-t push-10">\
    			          <h2 class="h3 push-5-t">'+user["name"]+'</h2>\
    			           <div class="text-center">&nbsp;'+user["username"]+'</div>\
    			             <div class="text-center"><i class="fa fa-envelope-o"></i>&nbsp;'+user["email"]+'</div>\
    			            </div>\
    			        </div>\
    			   </div>\
    			</div>';
    		}
    		  $('#my-block').children("div").html(userTiles);
    	}
    	else if(data[0]==false){
         $(".err-notification").html("Can't be Loaded");
        }
      }
  });
});*/
//reset token sene option//
$( ".resetpassword" ).click(function() {
	var user_id= $(this).data("value");
	var data={"user-id":user_id};
	$.ajax({
         type:"POST",
         cache: false,
 		 url: App.myUrl('users/password_reset_request'),
         data:data,
         beforeSend:function(jqXHR,settings){
        	 App.blocks("#"+user_id, 'state_loading');
         },
         complete:function(jqXHR,textStatus){
        	 App.blocks("#"+user_id, 'state_normal');
         },
         success: function(data){
       	  if(isJson(data)==false)
       		$(".err-notification").html("can't subscribe");
       	  else
       		  data = JSON.parse(data);
       	if(data[0]==true){
       			$("#"+user_id+" .resetpassword").removeAttr("data-original-title").attr("data-original-title","Password Reset Link Sent").removeClass("text-danger text-primary").addClass("text-success").html('<i class="fa fa-check"></i>');
       			}
       	else if(data[0]==false){
               $(".err-notification").html("can't subscribe");
           }
         }
     });
});

/*Disable User*/
$(document).on('click', '.enable', function (e) {
    e.preventDefault();
	var user_id= $(this).data("value");
	var status_id= $(this).data("status");
	var data={"user_id":user_id,"status_id":status_id};
    // Show the user a swal confirmation window
	if(status_id == "0"){
		var title = "<h4> Do you want to disable this user ?</h4>";
		var text='This will prevent the user from login.';
	}else{
		var title = "<h4>Do you want to enable this user ?</h4>";
		var text='This will make the user active again.';
	}
		swal({
            title: title,
            text: text,
            type: 'warning',
            confirmButtonColor: '#14adc4',
            confirmButtonText: 'Yes,Im Sure!',
            showCancelButton: true,
            closeOnConfirm: false,
            html:true
        },
function() {
    $.ajax({
        type: "POST",
        
        url: App.myUrl("users/disable"),
        data: data,
        beforeSend:function(jqXHR,settings){
       	 App.blocks("#"+user_id, 'state_loading');
        },
        complete:function(jqXHR,textStatus){
       	 App.blocks("#"+user_id, 'state_normal');
        },
        success: function(data){
      	  if(isJson(data)==false){
      		swal({title:'Error', text:'Error Occurred,Please Try Again', type:'error'});
      		}
      	  else
      		  data = JSON.parse(data);
      	if(data[0]==true){
      		if(status_id == "0")
      		swal({title:'Disabled!', text:'User Disabled succesfully...', type:'success'},function() {
      			$("#"+user_id+" .enable").data("status",1).removeAttr("data-original-title").attr("data-original-title","Enable User").removeClass("label-success").addClass("label-danger").html('InActive');
           });
      		else
      			swal({title:'Enabled!', text:'User Enabled succesfully...', type:'success'},function() {
          			$("#"+user_id+" .enable").data("status",0).removeAttr("data-original-title").attr("data-original-title","Disable User").removeClass("label-danger").addClass("label-success").html('Active');
               });
      			
         }else if(data[0]==false){
        	 swal({title:'Error', text:data["info"], type:'error'});
                  }}
 
            }); 
            });
		});
