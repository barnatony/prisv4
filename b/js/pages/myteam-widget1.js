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

for(var key in helpers) {
	  dust.helpers[key] = helpers[key];
	}

$(document).ready(function () {
	getMyteamlateComers();
});	





function getMyteamlateComers(){
	
	
	var teamLateTpl = dust.compile($("#team-late").html(),"teamLateTpl");
	dust.loadSource(teamLateTpl);


	    $.ajax({
	        type:"POST",
	        cache: false,
			 url: App.myUrl('dashboard/myteam/LateComers'),
	        beforeSend:function(jqXHR){
	        	App.blocks("#late-template", 'state_loading');
	        },
	        complete:function(jqXHR,textStatus){
	        	App.blocks("#late-template", 'state_normal');
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
			
	      	$("#team_late_view").html(template(data,"teamLateTpl"));
			
	      	
	        }
	        
	        });
	
}
$('.tpl-late').on("click",function(){
	getMyteamlateComers();
});
$('.tpl-leave').on("click",function(){
	getMyteamleaveRequests();
	
});



