
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

$( document ).ready(function() {
	
	getInfos();
	
});



function getInfos(){
	
    
    
	var infoDashboardTpl = dust.compile($("#info-dashboard").html(),"infoDashboardTpl");
	dust.loadSource(infoDashboardTpl);
	
        $.ajax({
            type:"POST",
            cache: false,
    		url: App.myUrl("dashboard/get/infos"),
    		
    		//data:data,
            
    		beforeSend:function(jqXHR){
            	App.blocks("#tab-info", 'state_loading');
            },
            complete:function(jqXHR,textStatus){
            	App.blocks("#tab-info", 'state_normal');
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
    		
    		$("#info").html(template(data,"infoDashboardTpl"));
    		
    		}
            });

    	
}