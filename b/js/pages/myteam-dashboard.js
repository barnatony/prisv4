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
	getMyteamleaveRequests();
	getMyteamRegularizations();
	getMyteamlateComers();
});	

function getMyteamleaveRequests(){
	
	
	var teamLeavesTpl = dust.compile($("#team-leaveRequests").html(),"teamLeavesTpl");
	dust.loadSource(teamLeavesTpl);


	    $.ajax({
	        type:"POST",
	        cache: false,
			 url: App.myUrl('dashboard/myteam/leaveRequests'),
	        beforeSend:function(jqXHR){
	        	App.blocks("#leave-template", 'state_loading');
	        },
	        complete:function(jqXHR,textStatus){
	        	 App.blocks("#leave-template", 'state_normal');
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
			
	      	$("#team_leaveRequests_view").html(template(data,"teamLeavesTpl"));
			
	      	
	        }
	        
	        });
	
}

function getMyteamRegularizations(){
	
	
	var teamRegTpl = dust.compile($("#team-Regularization").html(),"teamRegTpl");
	dust.loadSource(teamRegTpl);


	    $.ajax({
	        type:"POST",
	        cache: false,
			 url: App.myUrl('dashboard/myteam/Regularization'),
	        beforeSend:function(jqXHR){
	        	App.blocks("#reg-template", 'state_loading');
	        },
	        complete:function(jqXHR,textStatus){
	        	App.blocks("#reg-template", 'state_normal');
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
			
	      	$("#team_Reg_view").html(template(data,"teamRegTpl"));
			
	      	
	        }
	        
	        });
	
}
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

function getPresent(){
	
	
	var teamLateTpl = dust.compile($("#team-late").html(),"teamLateTpl");
	dust.loadSource(teamLateTpl);


	    $.ajax({
	        type:"POST",
	        cache: false,
			 url: App.myUrl('dashboard/myteam/Present'),
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
function getActive(){
	
	
	var teamLateTpl = dust.compile($("#team-late").html(),"teamLateTpl");
	dust.loadSource(teamLateTpl);


	    $.ajax({
	        type:"POST",
	        cache: false,
			 url: App.myUrl('dashboard/myteam/active'),
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
function getAbsent(){
	
	
	var teamLateTpl = dust.compile($("#team-late").html(),"teamLateTpl");
	dust.loadSource(teamLateTpl);


	    $.ajax({
	        type:"POST",
	        cache: false,
			 url: App.myUrl('dashboard/myteam/absent'),
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
	var val='Late Comers';
	$("#title_change").html(val);
	getMyteamlateComers();
});
$('.tpl-leave').on("click",function(){
	getMyteamleaveRequests();
	
});
$('.tpl-reg').on("click",function(){
	getMyteamRegularizations();
});


$('#Present').on("click",function(){
	var val='Present Employees';
	$("#title_change").html(val);
	getPresent();
});
$('#Late').on("click",function(){
	var val='Late Comers';
	$("#title_change").html(val);
	getMyteamlateComers();
});
$('#Active').on("click",function(){
	var val='Active Employees';
	$("#title_change").html(val);
	getActive();
});

$('#Absent').on("click",function(){
	var val='Absent Employees';
	$("#title_change").html(val);
	getAbsent();
});



//Active

