$(document).ready(function () {
	getInfos();
	getBranchCount();
	timeWitCompany();
	employeeAge();	
	hiredleft();
	employees();
	teamCount();
	imagePercentage();
	
 
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

function getBranchCount(widget){	
	 $.ajax({
         type:"POST",
         cache: false,
         url: App.myUrl("dashboard/getChartData/branchCount"),
 		
 		beforeSend:function(jqXHR){
         	App.blocks("#branch_view", 'state_loading');
         },
         complete:function(jqXHR,textStatus){
         	App.blocks("#branch_view", 'state_normal');
         },
         
         success: function(data){
       	  if(isJson(data)==false)
       		$(".err-notification").html("Data Cannot be found");
       	  else
       		  data = JSON.parse(data);
       	  
       	  var branches=[];
       	  var branchMalePer=[];
       	  var branchFemPer=[];
       	  var branchMaleTot=[];
     	  var branchFemTot=[];
       	  
 		$(function() {
 			
 			for(var i=0;i<data.brachCount.length;i++){
 				
 				
 				branchMaleTot.push(data.brachCount[i].maleTotal)
 				branches.push(data.brachCount[i].branch_name)
 				branchFemTot.push(data.brachCount[i].femTotal)
 				branchMalePer.push(data.brachCount[i].malePercentage)
 				branchFemPer.push(data.brachCount[i].femPercentage)
 				
 			}
 			
 			var $horizontalChartData ={
 	        	    datasets: [{
 	        	    	
 	        	    	label: 'Male',
 	                    data:  branchMaleTot,
 	                    labels:branchMalePer,
 	        			backgroundColor: "#70b9eb",
 	        			hoverBorderWidth: 2,
 	        			hoverBorderColor: 'lightgrey',
 	        			
 	        				
 	        	        
 	        	    },{
 	        	    	label: 'Female',
 	                    data:  branchFemTot,
 	                    labels:branchFemPer,
 	        			backgroundColor: "#ff6c9d",
 	        			hoverBorderWidth: 2,
 	        			hoverBorderColor: 'lightgrey'
 	        	    	
 	        	    },
 	        	    
 	        	    ],

 	        	   labels: branches,
 	        	  

 	        	};
 	   
 		      var $chartGenderCon  = jQuery('#branch-gender-ratios')[0].getContext('2d');
 		      
 		         // Set Chart and Chart Data variables
 		         var $chartDonut;


 		      var $chartGenderCon = new Chart($chartGenderCon, {
 		       type: 'horizontalBar',
 		       data: $horizontalChartData,
 		       options: {animation:{animateScale:true},legend:{display:true}, 
 		    	  tooltips: {
 		             mode: 'label',
 		           callbacks: {
 		                label: function(tooltipItem, data) {
 		                	if(typeof data.datasets[tooltipItem.datasetIndex].labels[tooltipItem.index] != "undefined")
 		                		return  data.datasets[tooltipItem.datasetIndex].label + ":" + data.datasets[tooltipItem.datasetIndex].labels[tooltipItem.index];
 		                	//return data.datasets[tooltipItem.datasetIndex].data + ": " + (tooltipItem.datasetIndex);
 		                }
 		           }
 		    	  },
 		    	   scales: {
 	 	          
 		    	   xAxes: [{
 	 	            display: false,
 		    	   stacked: true,
 	 	           gridLines: { display: false },
 	 	           //ticks: {min: 0, max:100}
 	 	           
 	 	          }],
 	 	         yAxes: [{
 	  	           stacked: true,
 	  	           maxBarThickness:50,
 	  	           gridLines: { display: false },
 	  	          }],
 	  	       	  	       
 		       	}, // scales
 		       layout: {
		       	    padding: {
		       	      left: 0,
		       	      right: 50,
		       	     // top: 0,
		       	     // bottom: 50
		       	    }},
 		       plugins: {
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
 		       },
 		       
 		       },
 		   });
 		      
 		      //horizontal chart for branch gender ratios ends here
 		      
 		});
 		
 		
 		}
         });
	
	
}
	 
	 
	 
function timeWitCompany(){
	 $.ajax({
         type:"POST",
         cache: false,
         //data:{'tax-year':value},
 		url: App.myUrl("dashboard/getChartData/employeeSpan"),
 		
 		beforeSend:function(jqXHR){
         	App.blocks("#employee_span", 'state_loading');
         },
         complete:function(jqXHR,textStatus){
         	App.blocks("#employee_span", 'state_normal');
         },
         
         success: function(data){
       	  if(isJson(data)==false)
       		$(".err-notification").html("Data Cannot be found");
       	  else
       		  data = JSON.parse(data);
       	
       	  var range=[];
       	  var men=[];
       	  var women=[];
       	  var menPer=[];
       	  var womenPer=[];
       	 
       	  
 		$(function() {
 			for(var i=0;i<data.employeeSpan.length;i++){
 				
 				
 					range.push(data.employeeSpan[i].exp_range)
 					women.push(data.employeeSpan[i].range_female)
 					men.push(data.employeeSpan[i].range_male)
 					menPer.push(data.employeeSpan[i].per_male)
 					womenPer.push(data.employeeSpan[i].per_female)
 					
 					
 				
 			}
 			
 			
 			
 		      
 		});
 		//time with companystarts here
 		$(function() {
 			//men,women
 			
 	      var $donutData ={
 	    		  datasets: [{
 	        	    	
 	        	    	label: 'Men',
 	        	    	data: men,
 	        	    	labels:menPer,
 	        			backgroundColor: "#70b9eb",
 	        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
 	        			hoverBorderWidth: 2,
 	        			hoverBorderColor: 'lightgrey'
 	        	        
 	        	    },{
 	        	    	label: 'Women',
 	                    data: women,
 	                    labels:womenPer,
 	        			backgroundColor: "#ff6c9d",
 	        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
 	        			hoverBorderWidth: 2,
 	        			hoverBorderColor: 'lightgrey'
 	        	    	
 	        	    }],

 	     
 	        	    // These labels appear in the legend and in the tooltips when hovering different arcs
 	        	    labels: range,

 	        	};
 	   
 		      var $chartDonutCon  = jQuery('#duration')[0].getContext('2d');
 		      
 		         // Set Chart and Chart Data variables
 		         var $chartDonut;


 		      var $chartDonut = new Chart($chartDonutCon, {
 		       type: 'horizontalBar',
 		       data: $donutData,
 		       options: {animation:{animateScale:true},legend:{display:true},
 		    	  tooltips: {
  		             mode: 'label',
  		           callbacks: {
  		                label: function(tooltipItem, data) {
  		                	if(typeof data.datasets[tooltipItem.datasetIndex].labels[tooltipItem.index] != "undefined")
  		                		return  data.datasets[tooltipItem.datasetIndex].label + ":" + data.datasets[tooltipItem.datasetIndex].labels[tooltipItem.index];
  		                	//return data.datasets[tooltipItem.datasetIndex].data + ": " + (tooltipItem.datasetIndex);
  		                }
  		           }
  		    	  },
 		    	   scales: {
 		  	          xAxes: [{
 		  	            display: false,
 		  	            stacked: true,
 		  	          gridLines: { display: false },
 		  	          }],
 		  	         yAxes: [{
 		  	  	           stacked: true,
 		  	  	           gridLines: { display: false },
 		  	  	           maxBarThickness:50,
 		  	  	          }],
 		  	  	          
 		 	       	},
 		 	      plugins: {
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
 	 		       },
 	 		       // scales// scales
 		       
 		       }
 		   });
 		    //hired vs left ends here
 		});
 		
 		
 		}
         })
}	 
	 
	 
function employeeAge(){
	 $.ajax({
         type:"POST",
         cache: false,
         //data:{'tax-year':value},
 		url: App.myUrl("dashboard/getChartData/AgeCount"),
 		
 		beforeSend:function(jqXHR){
         	App.blocks("#empAge_view", 'state_loading');
         },
         complete:function(jqXHR,textStatus){
         	App.blocks("#empAge_view", 'state_normal');
         },
         
         success: function(data){
       	  if(isJson(data)==false)
       		$(".err-notification").html("Data Cannot be found");
       	  else
       		  data = JSON.parse(data);
       	  var AgeCount=[];
       	  var AgeM=[];
       	  var AgeF=[];
       	  var RangeM=[];
     	  var RangeF=[];
       	  
 		$(function() {
 			for(var i=0;i<data.AgeCount.length;i++){
 				
 				if(data.AgeCount[i].gender=="Male"){
 					AgeCount.push(data.AgeCount[i].age_range)
 					AgeM.push(data.AgeCount[i].percentage)
 					RangeM.push(data.AgeCount[i].range_count)
 				}else{
 					AgeF.push(data.AgeCount[i].percentage)
 					RangeF.push(data.AgeCount[i].range_count)
 				}
 					
 				
 				
 			}
 			
 			

 			$(function() {
 				//men,women
 				
 				 
 		      var $donutData ={
 		    		  datasets: [{
 		        	    	
 		        	    	label: 'Men',
 		                    data: RangeM,
 		                    labels:AgeM,
 		        			backgroundColor: "#70b9eb",
 		        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
 		        			hoverBorderWidth: 2,
 		        			hoverBorderColor: 'lightgrey'
 		        	        
 		        	    },{
 		        	    	label: 'Women',
 		                    data:RangeF,
 		                    labels:AgeF,
 		        			backgroundColor: "#ff6c9d",
 		        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
 		        			hoverBorderWidth: 2,
 		        			hoverBorderColor: 'lightgrey'
 		        	    	
 		        	    }],

 		     
 		        	    // These labels appear in the legend and in the tooltips when hovering different arcs
 		        	    labels:AgeCount

 		        	};
 		   
 			      var $chartDonutCon  = jQuery('#emp-age')[0].getContext('2d');
 			      
 			         // Set Chart and Chart Data variables
 			         var $chartDonut;


 			      var $chartDonut = new Chart($chartDonutCon, {
 			       type: 'bar',
 			       data: $donutData,
 			       options: {animation:{animateScale:true},legend:{display:true},
 			    	  tooltips: {
 	 		             mode: 'label',
 	 		           callbacks: {
 	 		                label: function(tooltipItem, data) {
 	 		                	//console.log(tooltipItem)
 	 		                	//console.log(data.datasets[tooltipItem.datasetIndex].data)
 	 		                	return  data.datasets[tooltipItem.datasetIndex].label + ":" + data.datasets[tooltipItem.datasetIndex].labels[tooltipItem.index];
 	 		                	//return data.datasets[tooltipItem.datasetIndex].data + ": " + (tooltipItem.datasetIndex);
 	 		                }
 	 		           }
 	 		    	  },
 			    	   scales: {
 			  	          xAxes: [{
 			  	           
 			  	           stacked: true,
 			  	          gridLines: { display: false },
 			  	          maxBarThickness:50,
 			  	          }],
 			  	         yAxes: [{
 			  	        	display: false,  
 			  	        	 stacked: true,
 			  	        	 //ticks: {min: 0, max:18},
 			  	  	           gridLines: { display: false },
 			  	  	          }],
 			 	       	},
 			 	      
 			 	      plugins: {
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
 		 		       },// scales
 			       
 			       }
 			   });
 			    
 			});
 	
 		      
 		});
 		
 		
 		}
         });
}	 
	 
	 
function hiredleft(){
	$.ajax({
        type:"POST",
        cache: false,
        //data:{'tax-year':value},
		url: App.myUrl("dashboard/getChartData/HiredLeft"),
		
		beforeSend:function(jqXHR){
        	App.blocks("#hired_view", 'state_loading');
        },
        complete:function(jqXHR,textStatus){
        	App.blocks("#hired_view", 'state_normal');
        },
        
        success: function(data){
      	  if(isJson(data)==false)
      		$(".err-notification").html("Data Cannot be found");
      	  else
      		  data = JSON.parse(data);
      	  
      	  var hired=[];
      	  var left=[];
      	  var monLabel=[];
      	 
      	  
		$(function() {
			
			for(var i=0;i<=data.HiredLeft.length-1;i++){
				
				hired.push(data.HiredLeft[i].join_count)
				left.push(data.HiredLeft[i].left_actual_count)
				monLabel.push(data.HiredLeft[i].doj_month)
				
			}
			
			
			
			//hired vs left starts here
			$(function() {
			
				var $donutData ={
		    		  datasets: [{
		        	    	
		        	    	label: 'Hired',
		        	    	data: hired,
		        			backgroundColor: "#23c6c8",
		        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
		        			hoverBorderWidth: 2,
		        			hoverBorderColor: 'lightgrey'
		        	        
		        	    },{
		        	    	label: 'Left',
		                    data: left,
		        			backgroundColor: "#ff6b6b",
		        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
		        			hoverBorderWidth: 2,
		        			hoverBorderColor: 'lightgrey'
		        	    	
		        	    }],

		     
		        	    // These labels appear in the legend and in the tooltips when hovering different arcs
		        	    labels:monLabel

		        	};
		   
			      var $chartDonutCon  = jQuery('#hired-left')[0].getContext('2d');
			      
			         // Set Chart and Chart Data variables
			         var $chartDonut;


			      var $chartDonut = new Chart($chartDonutCon, {
			       type: 'bar',
			       data: $donutData,
			       options: {animation:{animateScale:true},legend:{display:true}, scaleBeginAtZero: false,
	 		    	    responsive: true,
			    	   scales: {
			  	          
			    		   xAxes: [{
			  	         //   display: false,
			  	            stacked: true,
			  	          gridLines: { display: false },
			  	          maxBarThickness : 50,
			  	          }],
			  	         yAxes: [{
			  	  	           stacked: true,
			  	  	          // ticks:{max:100,min:-40},
			  	  	           gridLines: { display: true },
			  	  	       display: false,
			  	  	          }],
			 	       	}, // scales
			 	       plugins: {
	 		 		          datalabels: {
	 		 		             display: true,
	 		 		             align: 'center',
	 		 		             anchor:'center',
	 		 		             color:'white',	 
	 		 		             formatter: function(value, context) {
	 		 		        	 //  console.log(context.dataset.data[context.dataIndex])
	 		 		        	   //return context.dataset.labels[context.dataIndex] +'%';
	 		 		        	  //return context.dataset.data[context.dataIndex] +'%';
	 		 		            	return value ;
	 		 		           }
	 		 		          }
	 		 		       },// scales
			       
			       }
			   });
			    //hired vs left ends here
			});
	
		      
		});
		
		
		}
        })
	
}	 
	 
	 
function employees(){
	$.ajax({
        type:"POST",
        cache: false,
        //data:{'tax-year':value},
		url: App.myUrl("dashboard/getChartData/currentEmployees"),
		
		beforeSend:function(jqXHR){
        	App.blocks("#employee_count_view", 'state_loading');
        },
        complete:function(jqXHR,textStatus){
        	App.blocks("#employee_count_view", 'state_normal');
        },
        
        success: function(data){
      	  if(isJson(data)==false)
      		$(".err-notification").html("Data Cannot be found");
      	  else
      		  data = JSON.parse(data);
      	
      	  var Male =[];
     	  var Female=[];
     	  var Label=[];
     	 
     	  
      	  
	$(function() {
		
		for(var i=0;i<data.currentEmployees.length;i++){
			if(data.currentEmployees[i].months==data.currentEmployees[i].months)
				
				Label.push(data.currentEmployees[i].month)
	 			Male.push(data.currentEmployees[i].maleCount)
	 			Female.push(data.currentEmployees[i].femaleCount)
			
			
			}
		
      var $donutData ={
    		  datasets: [{
        	    	
        	    	label: 'Men',
        	    	data: Male,
        			backgroundColor: "#ff6b6b",
        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
        			hoverBorderWidth: 2,
        			hoverBorderColor: 'lightgrey'
        	        
        	    },{
        	    	label: 'Women',
                    data: Female,
        			backgroundColor: "#23c6c8",
        			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
        			hoverBorderWidth: 2,
        			hoverBorderColor: 'lightgrey'
        	    	
        	    }],

     
        	    // These labels appear in the legend and in the tooltips when hovering different arcs
        	    labels: Label

        	};
   
	      var $chartDonutCon  = jQuery('#employees')[0].getContext('2d');
	      
	         // Set Chart and Chart Data variables
	         var $chartDonut;


	      var $chartDonut = new Chart($chartDonutCon, {
	       type: 'bar',
	       data: $donutData,
	       options: {animation:{animateScale:true},legend:{display:true},
	    	   scales: {
	  	          xAxes: [{
	  	          //  display: false,
	  	            stacked: true,
	  	          gridLines: { display: false },
	  	        maxBarThickness:50,
	  	          }],
	  	         yAxes: [{
	  	  	           stacked: true,
	  	  	       display: false,
	  	  	           gridLines: { display: false },
	  	  	           maxBarThickness:50,
	  	  	          }],
	 	       	}, // scales
	 	       plugins: {
	 		          datalabels: {
	 		             display: true,
	 		             align: 'center',
	 		             anchor:'center',
	 		             color:'white',	 
	 		             formatter: function(value, context) {
	 		        	 //  console.log(context.dataset.data[context.dataIndex])
	 		        	   //return context.dataset.labels[context.dataIndex] +'%';
	 		        	  //return context.dataset.data[context.dataIndex] +'%';
	 		            	return value +'';
	 		           }
	 		          }
	 		       },// scales
	       
	       
	       }
	   });
	    //hired vs left ends here
	});


}
})
}	 
	 
	 
	 
function teamCount(){

	$.ajax({
        type:"POST",
        cache: false,
        //data:{'tax-year':value},
		url: App.myUrl("dashboard/getChartData/teamCount"),
		
		beforeSend:function(jqXHR){
        	App.blocks("#team_view", 'state_loading');
        },
        complete:function(jqXHR,textStatus){
        	App.blocks("#team_view", 'state_normal');
        },
        
        success: function(data){
      	  if(isJson(data)==false)
      		$(".err-notification").html("Data Cannot be found");
      	  else
      		  data = JSON.parse(data);
      	  
      	  var teamName=[];
      	  var teamMalePercentage=[];
      	  var teamFemalePercentage=[];
      	  var teamMaleCount=[];
      	  var teamFemaleCount=[];
      	  
		$(function() {
			for(var i=0;i<data.teamCount.length;i++){
				if(data.teamCount[i].team_name===data.teamCount[i].team_name){
					
	 				teamName.push(data.teamCount[i].team_name)
	 				teamMalePercentage.push(data.teamCount[i].malePercentage)
					teamMaleCount.push(data.teamCount[i].maleTotal)
					teamFemalePercentage.push(data.teamCount[i].femPercentage)
					teamFemaleCount.push(data.teamCount[i].femTotal)
				
				}
				
				
			}
			
			var $horizontalChartData ={
	    		  datasets: [{
	      	    	
	      	    	label: 'Men',
	                data: teamMaleCount,
	                labels:teamMalePercentage,
	      			backgroundColor: "#70b9eb",
	      			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
	      			hoverBorderWidth: 2,
	      			hoverBorderColor: 'lightgrey',
	      			 
	      	        
	      	    },{
	      	    	label: 'Women',
	                  data: teamFemaleCount,
	                  labels:teamFemalePercentage,
	      			backgroundColor: "#ff6c9d",
	      			//hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
	      			hoverBorderWidth: 2,
	      			hoverBorderColor: 'lightgrey'
	      	    	
	      	    }],


	     
	        	    // These labels appear in the legend and in the tooltips when hovering different arcs
	        	    labels: teamName

	        	};
	   
		      var $chartGenderCon  = jQuery('#gender-ratios')[0].getContext('2d');
		      
		         // Set Chart and Chart Data variables
		         var $chartDonut;


		      var $chartGenderCon = new Chart($chartGenderCon, {
		       type: 'horizontalBar',
		       data: $horizontalChartData,
		       options: {animation:{animateScale:true},legend:{display:true},maintainAspectRatio: true,
	
		    	   tooltips: {
	 		             mode: 'label',
	 		           callbacks: {
	 		                label: function(tooltipItem, data) {
	 		                	if(typeof data.datasets[tooltipItem.datasetIndex].labels[tooltipItem.index] != "undefined")
	 		                		return  data.datasets[tooltipItem.datasetIndex].label+ ":" + data.datasets[tooltipItem.datasetIndex].labels[tooltipItem.index];
	 		                }
	 		           }
	 		    	  },
		         responsive: true,
		    	   scales: {
	 	          xAxes: [{
	 	           display: false,
	 	        	//ticks: {min: 0, max:100},
	 	        	stacked: true,
	 	       	gridLines: { display: false },
	 	        	
	 	          }],
	 	         yAxes: [{
	 	        	ticks: {
	 	                beginAtZero:true,
	 	                fontSize:11
	 	            },
	 	        	 stacked: true,
	 	        	 maxBarThickness:50,
	 	        	barPercentage: 0.2,
	 	            categoryPercentage: 0.8,
	 	        	gridLines: { display: false },
	 	  	        barThickness : 15,
	 	        	//maxBarThickness: 100,
	 	  	       
	 	  	          }],
	 	  	          
	 	  	      
		       	}, // scales
		       	layout: {
		       	    padding: {
		       	      left: 50,
		       	      right: 200,
		       	      top: 0,
		       	      bottom: 50
		       	    }},
		      plugins: {
		          datalabels: {
		             display: true,
		             align: 'center',
		             anchor: 'center',
		             color:'white',	 
		            formatter: function(value, context) {
		            	return value;
		           }
		          }
		       },
		       }
		      
		     
		   }
		      
		      
		      );
		      
		      
		     

	
		      
		});
		
		
		}
        });
}

$('#branch').on("click",function(){
	var widget=$(this).data("value");
	getBranchCount(widget);
});

$('#emp_span').on("click",function(){
	var widget=$(this).data("value");	 
    timeWitCompany(widget);
});

$('#emp_age').on("click",function(){
	var widget=$(this).data("value");	 
	employeeAge(widget);
});
$('#hired').on("click",function(){
	var widget=$(this).data("value");	 
	hiredleft(widget);
});
$('#emp_view').on("click",function(){
	employees();
});
$('#team').on("click",function(){
	teamCount();
});
$('#imagePercentage').on("click",function(){
	imagePercentage();
});


function getInfos(){

	
    
	var DashboardTpl = dust.compile($("#widget-tpl").html(),"DashboardTpl");
	dust.loadSource(DashboardTpl);
	
	
        $.ajax({
            type:"POST",
            cache: false,
    		url: App.myUrl("dashboard/getChartData/infos"),
    		//data:data,
            
    		beforeSend:function(jqXHR){
            	//App.blocks("#today_view", 'state_loading');
            },
            complete:function(jqXHR,textStatus){
            	//App.blocks("#today_view", 'state_normal');
            },
            
            success: function(data){
          	  if(isJson(data)==false)
          		$(".err-notification").html("Data Cannot be found");
          	  else
          		  data = JSON.parse(data);
          	//console.log(data);
          	if(data[0]==false)
          		$(".err-notification").html("Data Cannot be found");
             
          	
          	var template = function(data,tplvar) {
          		var result;
    			dust.render(tplvar, data, function(err, res) {
    				result = res;
    			});
    			
    			return result;
    		};
    		
    		$("#widgets").html(template(data,"DashboardTpl"));
    		
            }
            });

    	
}
function imagePercentage(){

	
    
	var imageTpl = dust.compile($("#image-tpl").html(),"imageTpl");
	dust.loadSource(imageTpl);
	
	
        $.ajax({
            type:"POST",
            cache: false,
    		url: App.myUrl("dashboard/getChartData/imagePercentage"),
    		//data:data,
            
    		beforeSend:function(jqXHR){
            	App.blocks("#imageSize", 'state_loading');
            },
            complete:function(jqXHR,textStatus){
            	App.blocks("#imageSize", 'state_normal');
            },
            
            success: function(data){
          	//  if(isJson(data)==false)
          	//	$(".err-notification").html("Data Cannot be found");
          	//  else
          		  data = JSON.parse(data);
          	 
          	 
          	
          //	if(data[0]==false)
          	//	$(".err-notification").html("Data Cannot be found");
             
          	
          	var template = function(data,tplvar) {
          		
          		var result;
    			dust.render(tplvar, data, function(err, res) {
    				result = res;
    			});
    			
    			return result;
    		};
    		
    		$("#images").html(template(data,"imageTpl"));
    		 var female_percent = data.Percentage[0].percentage;
         	 var male_percent = data.Percentage[1].percentage;
         	if(female_percent>male_percent){
         		$('.male_image').addClass('resizeImage');
         	}else{
         		$('.female_image').addClass('resizeImage');
         	}
            }
            });

    	
}

function initPopoverJoin(element) {
    $.ajax({
        type:"POST",
        cache: false,
		url: App.myUrl("dashboard/getChartData/HoverData"),
		//data:data,
        
		beforeSend:function(jqXHR){
        	//App.blocks("#imageSize", 'state_loading');
        },
        complete:function(jqXHR,textStatus){
        	//App.blocks("#imageSize", 'state_normal');
        },
        
        success: function(data){
          	//  if(isJson(data)==false)
          	//	$(".err-notification").html("Data Cannot be found");
          	//  else
          		  data = JSON.parse(data);
          		var empName=[];  
          		
          		if(data.JoinedEmp.length>=1){
          			var html ="<ul class='fa-ul' style='width:250px'>";
          		for(var i=0;i<data.JoinedEmp.length;i++){
          			
          			if(data.JoinedEmp[i].employee_gender=="Male")
          				html +="<li><i class='si si-user fa-li'style='color:#70b9eb' ></i>";	
          			else
          				html +="<li><i class='si si-user-female fa-li' style='color:#ff4e89 '></i>";
          				
          			html+="<b>"+data.JoinedEmp[i].employee_name+"</b>"+'('+data.JoinedEmp[i].employee_id+')';
          			html+="</li>";
          			
          		}
          		html +='</ul>';
          		}else{
          			html ="<p> No Employee Joined In this Month</p>";
          		}
          		
          		
          		
          		$(element).popover({
          			
          			animation:true,
          			html:true,
          			content: html,
          			placement:'bottom',
          			trigger:'hover'
          		});
          		
          		
        }
    });
	};
	
	
function initPopoverExit(element) {
	    $.ajax({
	        type:"POST",
	        cache: false,
			url: App.myUrl("dashboard/getChartData/HoverData"),
			//data:data,
	        
			beforeSend:function(jqXHR){
	        	//App.blocks("#imageSize", 'state_loading');
	        },
	        complete:function(jqXHR,textStatus){
	        	//App.blocks("#imageSize", 'state_normal');
	        },
	        
	        success: function(data){
	          	//  if(isJson(data)==false)
	          	//	$(".err-notification").html("Data Cannot be found");
	          	//  else
	          		  data = JSON.parse(data);
	          		//console.log(data);
	          		
	          		var empName=[];  
	          		if(data.ExitEmp.length>=1){
	          		var html ="<ul class='fa-ul' style='width:250px'>";
	          		for(var i=0;i<data.ExitEmp.length;i++){
	          			
	          			if(data.ExitEmp[i].employee_gender=="Male")
	          				html +="<li><i class='si si-user fa-li' style='color:#70b9eb'></i>";	
	          			else
	          				html +="<li><i class='si si-user-female fa-li' style='color:#ff4e89 '></i>";
	          				
	          			html+="<b>"+data.ExitEmp[i].employee_name+"</b>"+'('+data.ExitEmp[i].employee_id+')';
	          			html+="</li>";
	          			
	          		}
	          		html +='</ul>';
	          		}else{
	          			html ="<p> No Employee Exited In this Month</p>";
	          		}
	          		$(element).popover({
	          			
	          			animation:true,
	          			html:true,
	          			content: html,
	          			placement:'bottom',
	          			trigger:'hover'
	          		});
	          		
	        }
	    });
		};
		
		
		function initPopoverCount(element) {
		    $.ajax({
		        type:"POST",
		        cache: false,
				url: App.myUrl("dashboard/getChartData/HoverData"),
				//data:data,
		        
				beforeSend:function(jqXHR){
		        	//App.blocks("#imageSize", 'state_loading');
		        },
		        complete:function(jqXHR,textStatus){
		        	//App.blocks("#imageSize", 'state_normal');
		        },
		        
		        success: function(data){
		          	//  if(isJson(data)==false)
		          	//	$(".err-notification").html("Data Cannot be found");
		          	//  else
		          		  data = JSON.parse(data);
		          		//console.log(data);
		          		var empName=[];  
		          		
		          		var html ="";
		          		
		          		
		          				if($(element).data("val")=='male'){
		          					html +="<p>Total Percentage of males : <b>"+data.Empcount[1].percentage+"</b>";	
		          					
		          				}else{
		          					html +="<p>Total Percentage of Females : <b>"+data.Empcount[0].percentage+"</b>";
		          					
		          				}
		          			
		          				
		          			
		          				
		          			
		          			html+="</p>";
		          			
		          		
		          		html +='</ul>';
		          		
		          		$(element).popover({
		          			
		          			animation:true,
		          			html:true,
		          			content: html,
		          			placement:'left',
		          			trigger:'hover'
		          				
		          		});
		          		
		        }
		    });
			};


