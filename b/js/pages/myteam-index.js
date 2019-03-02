var helpers = {
	"moment":function(chunk, context, bodies, params) {
		if(params.datetime)
			var dateTime = moment(params.datetime);
		
		if(params.format)
			chunk.write(moment(params.datetime).format(params.format));
		else if(params.fromnow)
			chunk.write(moment(params.datetime).fromNow());
	}	 
 };
$(document).ready(function () {
	getMyteam();
});
for(var key in helpers) {
	  dust.helpers[key] = helpers[key];
	}
//ajax call for timeline//
function getMyteam(){
//(function($) {
	 var teamMembersTpl = dust.compile($("#team-members").html(),"teamMembersTpl");
		dust.loadSource(teamMembersTpl);
		
  
	        $.ajax({
	            type:"POST",
	            cache: false,
	    		 url: App.myUrl('myteam/get'),
	            beforeSend:function(jqXHR){
	            	//App.blocks("#dashboard-mytimeline", 'state_loading');
	            },
	            complete:function(jqXHR,textStatus){
	            	 //App.blocks("#dashboard-mytimeline", 'state_normal');
	            },
	            success: function(data){
	          	  if(isJson(data)==false)
	          		$(".err-notification").html("Data Cannot be found");
	          	  else
	          		  data = JSON.parse(data);
	          	if(data[0]==false)
	                $(".err-notification").html("Data Cannot be found");
	             
	          	var template = function(data,tplvar) {
	          		
	    			var result;
	    			dust.render(tplvar, data, function(err, res) {
	    				result = res;
	    			});
	    			return result;
	    		};
	    		
	          	$("#team_members_view").html(template(data,"teamMembersTpl"));
				
	          	
	            }
	            

	            
	            });
}
//});
$('.tpl-team').on("click",function(){
	getMyteam();
});
      