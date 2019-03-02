$(document).ready(function () {
	useCurrent:false;
	$( ".dashboard-datetimepicker" ).datetimepicker({  
		maxDate: new Date,format:'Do MMM,YYYY',
		useCurrent:false,
			
	});
	
	App.initHelpers(['datetimepicker']);
	
});

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
	
	initilaizeEmp();
	getleaveStatus();
	//getInfos();
	getToday();
	var checkTaxPay=$('#checkTax').val();
	if(checkTaxPay)
		getTaxchartData();
});


		  
		  


$('.net').on("click",function(){
	var widget=$(this).data("value");
	var values=$(this).data("val");
	initilaizeEmp(widget,values)
});

$('.gross').on("click",function(){
	
	var widget=$(this).data("value");
	var values=$(this).data("val");
	initilaizeEmp(widget,values)
});

$('.mypay').on("click",function(){
	var widget=$(this).data("value");
	var values=$(this).data("val");
	initilaizeEmp(widget,values)
})


$("#dashboard-calender").on("dp.change", function(e) {
	var date=$(".dashboard-date").find("input").val();
	getToday(date);

});

$('.status').on("click",function(){
	var widget=$(this).data("value");
	getleaveStatus(widget);
});

$('.balance').on("click",function(){
	var widget=$(this).data("value");
	getleaveStatus(widget);
});

$('#tax_data').on("click",function(){
	getTaxchartData();
});


$('.taxwidget-year').on("change",function(){
	getTaxchartData();
});


$('#whats_happening').on("click",function(){
	var date=$(".dashboard-date").find("input").val();
	
	if(!date)
		getToday();
	else
		getToday(date);
});


function initilaizeEmp(widget,values){//widget,employeeID, (mypay,[type=gross])
	
	//to get system time and change message accordingly
	  var now = new Date();
  	  var hrs = now.getHours();
  	  var msg = "";
  	  
  	  if (hrs >  0) msg = "Morning' Sunshine!"; // REALLY early
  	  if (hrs >  6) msg = "Good Morning!..";      // After 6am
  	  if (hrs > 12) msg = "Good Afternoon!..";    // After 12pm
  	  if (hrs > 17) msg = "Good Evening!..";      // After 5pm
  	  if (hrs > 22) msg = "Good Night!..";        // After 10pm
	
  	  
	data={"payval":values,"widget":widget,"wishes" : msg};
	
	var empDashboardTpl = dust.compile($("#emp-dashboard").html(),"empDashboardTpl");
	dust.loadSource(empDashboardTpl);
	
	var payDashboardTpl = dust.compile($("#my-pay").html(),"payDashboardTpl");
	dust.loadSource(payDashboardTpl);

	var policiesDashboardTpl = dust.compile($("#emp-policy").html(),"policiesDashboardTpl");
	dust.loadSource(policiesDashboardTpl);
	
	var wishDashboardTpl = dust.compile($("#wish-tpl").html(),"wishDashboardTpl");
	dust.loadSource(wishDashboardTpl);
	
	var greetingsDashboardTpl = dust.compile($("#msg-tpl").html(),"greetingsDashboardTpl");
	dust.loadSource(greetingsDashboardTpl);
	
	template = function(data,tplvar) {
  		var result;
		dust.render(tplvar, data, function(err, res) {
			result = res;
		});
		
		return result;
	};
	
        $.ajax({
            type:"POST",
            cache: false,
    		url: App.myUrl("dashboard/get/employeeDetails"),
    		data:data,
            
    		beforeSend:function(jqXHR){
            	if(widget ===undefined || widget=="emp")
            		App.blocks("#emp-template", 'state_loading');
            	if(widget ===undefined || widget=="mypay")
            		App.blocks("#pay-template", 'state_loading');
            	if(widget ===undefined || widget=="policies")
            		App.blocks("#policies-template", 'state_loading');
            },
            complete:function(jqXHR,textStatus){
            	//App.blocks("#emp-template", 'state_normal');
            	//App.blocks("#mypay", 'state_normal');
            	//App.blocks("#policies", 'state_normal');
            },
            
            success: function(data){
          	  if(isJson(data)==false)
          		$(".err-notification").html("Error Loading Data.");
          	  else
          		  data = JSON.parse(data);
          	if(data[0]==false)
          		$(".err-notification").html("Error Loading Data.");
          	
          	
    		
          	
          	//loop the widgets data
          	
          	$.each(data,function(key,widget){
          		
          		if(key=="myself"){
          			//render the myself widget
          				$("#empdashboard").html(template(data,"empDashboardTpl"));
          			//hide the loader of myself widget
          			 App.blocks("#emp-template", 'state_normal');
          		}else if(key=="mypay"){
          			$("#mypay").html(template(data,"payDashboardTpl"));
          			App.blocks("#pay-template", 'state_normal');
          		}else if(key =="policies"){
          			$("#policies").html(template(data,"policiesDashboardTpl"))
          			App.blocks("#policies-template", 'state_normal');
          		}
          		
          		$("#wish").html(template(data,"wishDashboardTpl"))
          		$("#msg").html(template(data,"greetingsDashboardTpl"))
          	});
          	

    		
    		}
            
            });

    	
}


function getToday(date){

	
    data={"date":date};
	var todayDashboardTpl = dust.compile($("#today-dashboard").html(),"todayDashboardTpl");
	dust.loadSource(todayDashboardTpl);
	
	
        $.ajax({
            type:"POST",
            cache: false,
    		url: App.myUrl("dashboard/get/whats-happening"),
    		data:data,
            
    		beforeSend:function(jqXHR){
            	App.blocks("#today_view", 'state_loading');
            },
            complete:function(jqXHR,textStatus){
            	App.blocks("#today_view", 'state_normal');
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
    		
    		$("#today").html(template(data,"todayDashboardTpl"));
    		
            }
            });

    	
}


function getleaveStatus(widget){
	  
  	var template = function(data,tplvar) {
  		var result;
		dust.render(tplvar, data, function(err, res) {
			result = res;
		});
		
		return result;
	};
    
    
	var leaveStatusTpl = dust.compile($("#leave-status").html(),"leaveStatusTpl");
	dust.loadSource(leaveStatusTpl);
	var leaveBalanceTpl = dust.compile($("#leave-balance").html(),"leaveBalanceTpl");
	dust.loadSource(leaveBalanceTpl);
	
        $.ajax({
            type:"POST",
            cache: false,
    		url: App.myUrl("dashboard/get/leaveStatus"),
    		
    		data:{"widget":widget},
            
    		beforeSend:function(jqXHR){
    			if(widget ===undefined || widget=="balance"){
    				App.blocks("#balance-template", 'state_loading');
    			}if(widget ===undefined || widget=="status"){
    				App.blocks("#status-template", 'state_loading');
    			}
            },
            complete:function(jqXHR,textStatus){
            	//App.blocks("#attendance-template", 'state_normal');
            },
            
            success: function(data){
          	  if(isJson(data)==false)
          		$(".err-notification").html("Data Cannot be found");
          	  else
          		  data = JSON.parse(data);
          	if(data[0]==false)
          		$(".err-notification").html("Data Cannot be found");
           
          	$.each(data,function(key,widget){
              	
          		if(key=="leaveBalance"){
          			//render the myswlf widget
          			$("#lbalance").html(template(data,"leaveBalanceTpl"));
      				//hide the loader of myself widget
      					App.blocks("#balance-template", 'state_normal');
          		}else if(key=="leaveStatus"){
          			$("#lstatus").html(template(data,"leaveStatusTpl"));		
          			App.blocks("#status-template", 'state_normal');
          			
          		}
        	});
        	
            }
            });

    	
}


/*function getInfos(){
	
    
    
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

    	
}*/



function getTaxchartData(){
	
    
	//taxwidget-year
	var value=$('.taxwidget-year').val();
	if(!value)
		var value=$('.financialYear').val();
	
	var taxDashboardTpl = dust.compile($("#tax-view").html(),"taxDashboardTpl");
	dust.loadSource(taxDashboardTpl);
	
        $.ajax({
            type:"POST",
            cache: false,
            data:{'tax-year':value},
    		url: App.myUrl("dashboard/get/tax"),
    		
    		beforeSend:function(jqXHR){
            	App.blocks("#income-tax", 'state_loading');
            },
            complete:function(jqXHR,textStatus){
            	App.blocks("#income-tax", 'state_normal');
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
    		
    		$("#tax").html(template(data,"taxDashboardTpl"));
    		chartData = data["tax"];
    		$(function() {
    			$('.chart').easyPieChart({});
    		if(chartData.length>0){
    	      var $donutData ={
    	        	    datasets: [{
    	        	        data: [App.deFormate(chartData[3]["value1"]),App.deFormate(chartData[3]["value2"])],
    	        	        backgroundColor:["#d9534f","#46c37b"]
    	        	    }],

    	        	    // These labels appear in the legend and in the tooltips when hovering different arcs
    	        	    labels: [
    	        	        chartData[3]["value1_legend"],
    	        	        chartData[3]["value2_legend"]
    	        	    ]
    	        	};
	    	      var $chartDonutCon  = jQuery('#tax-donut')[0].getContext('2d');
	
	    	         // Set Chart and Chart Data variables
	    	         var $chartDonut;
	    	         
	
	    	      var $chartDonut = new Chart($chartDonutCon, {
	    	       type: 'pie',
	    	       data: $donutData,
	    	       options: {animation:{animateScale:true},legend:false,
	    	    	   tooltips: {
		    	    	    enabled: false
		    	    	},
	    	       },
	    	       
	    	     /* plugins: {
	 		          datalabels: {
	 		             display: true,
	 		             align: 'center',
	 		             anchor:'center',
	 		             color:'white',	 
	 		           formatter: function(value, context) {
	 		        	 //  console.log(context.dataset.data[context.dataIndex])
	 		        	   //return context.dataset.labels[context.dataIndex] +'%';
	 		        	  //return context.dataset.data[context.dataIndex] +'%';
	 		            	return value;
	 		           }
	 		          }
	 		       },*/
	    	   });
    			}else
    				 var $donutData =[];
   	        
    	    });
    		
    		
    		}
            });

    	
}


/* View More Pop Over*/

function initPopover(element) {
	$(element).popover({
	animation:true,
	html:true,
	content:$(element).data("content"),
	placement:'right',
	trigger:'hover'
});};
