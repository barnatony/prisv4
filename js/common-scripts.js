//$.browser hack 
var matched, browser;

jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}

//allways Scrol in top  
window.scrollTo(0, 0);
   	                 
/*---LEFT BAR ACCORDION----*/
$(function() {
    $('#nav-accordion').dcAccordion({
        eventType: 'click',
        autoClose: true,
        saveState: true,
        disableLink: true,
        speed: 'slow',
        showCount: false,
        autoExpand: true,
//        cookie: 'dcjq-accordion-1',
        classExpand: 'dcjq-current-parent'
    });
});


   

function GetFormattedDate(currDate) {
	var fromDate = new Date(currDate);
	var curr_date1 = fromDate.getDate();
    var curr_month = fromDate.getMonth()+1;
    var currMonth=curr_month < 10 ? '0' + curr_month : '' + curr_month;
    var curr_date=curr_date1 < 10 ? '0' + curr_date1 : '' + curr_date1;
    var curr_year = fromDate.getFullYear();
	return curr_date + "/" + currMonth + "/" + curr_year;
}


var Script = function () {

//    sidebar dropdown menu auto scrolling
    jQuery('#sidebar .sub-menu > a').click(function () {
        var o = ($(this).offset());
        diff = 250 - o.top;
        if(diff>0)
            $("#sidebar").scrollTo("-="+Math.abs(diff),500);
        else
            $("#sidebar").scrollTo("+="+Math.abs(diff),500);
    });

//    sidebar toggle

    $(function() {
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);
    });

    $('.fa-bars').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-210px'
            });
            $('#sidebar > ul').hide();
           $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '210px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
      
    });
   
// custom scrollbar
    $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});

    var url = window.location.pathname;
    filename = url.substring(url.lastIndexOf('/') + 1);
    if(filename!='report.php')
    $("html").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});

// widget tools

    jQuery('.panel .tools .fa-chevron-down').click(function () {
        var el = jQuery(this).parents(".panel").children(".panel-body");
        if (jQuery(this).hasClass("fa-chevron-down")) {
            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200);
        }
    });

    jQuery('.panel .tools .fa-times').click(function () {
        jQuery(this).parents(".panel").parent().remove();
    });


//    tool tips

    $('.tooltips').tooltip();

//    popovers

    $('.popovers').popover();



// custom bar chart

    if ($(".custom-bar-chart")) {
        $(".bar").each(function () {
            var i = $(this).find(".value").html();
            $(this).find(".value").html("");
            $(this).find(".value").animate({
                height: i
            }, 2000)
        })
    }


}();


var url = window.location.pathname;
              var filename = url.substring(url.lastIndexOf('/') + 1);
               $('#sidebar a').each(function () {
                  if ($(this).attr('href')== "index.php") {
                  }else if(filename == $(this).attr('href') && $(this).attr('href') != "dashboard.php") {
                	      $(this).parent().parent().parent().find("a").addClass('dcjq-parent active');
                          $(this).css('color', '#FF6C60');
                          if(filename=='viewPayroll.php'){
                        	//c//onsole.log($(this).parent().parent().find('a '));
                          }
                          if(filename=='tdsReport.php'){
                        	$(this).parent().parent().parent().parent().parent().find("a").addClass('dcjq-parent active');
                         	 var element=$(this).parent().parent().parent().parent().parent().find('ul:last li:nth-child(3)');
                         	 var thisElem=element.find('a').addClass('dcjq-parent active');
                         	thisElem.css('color', '#FF6C60');
         			              }
                          if(filename =='epfReport.php'){
                        	  $(this).parent().parent().parent().parent().parent().find("a").addClass('dcjq-parent active');
                          	 var element=$(this).parent().parent().parent().parent().parent().find('ul:last li:nth-child(2)');
                          	 var thisElem=element.find('a').addClass('dcjq-parent active');
                          	thisElem.css('color', '#FF6C60');
         			              }
                          if(filename =='esiReport.php'){
                        	  $(this).parent().parent().parent().parent().parent().find("a").addClass('dcjq-parent active');
                          	 var element=$(this).parent().parent().parent().parent().parent().find('ul:last li:nth-child(1)');
                          	 var thisElem=element.find('a').addClass('dcjq-parent active');
                          	thisElem.css('color', '#FF6C60');
         			              }
                          if(filename =='ptReport.php'){
                        	  $(this).parent().parent().parent().parent().parent().find("a").addClass('dcjq-parent active');
                          	 var element=$(this).parent().parent().parent().parent().parent().find('ul:last li:nth-child(4)');
                          	 var thisElem=element.find('a').addClass('dcjq-parent active');
                          	thisElem.css('color', '#FF6C60');
         			              }
                  }else if(($(this).attr('href')== "dashboard.php")){
                 }
                  
              });

//for find total  ..Tabel colum  IN reports
                 function calculateColumn(index,tablName)
	              {
		                for(var i=0;i<index;i++){
		            	   var total = 0;
		               $('#'+tablName+' tr').each(function()
	                  { 
		            	   if($('td', this).eq(i).html()!=null){
			              var value =parseFloat(deFormate($('td', this).eq(i).html()));	
			              if (!isNaN(value))
	                      {
			            	  total += value;
	                      }
			              
	                  }
	                  });
		            $('#'+tablName+' td.total').eq(i).html(reFormateByNumber(total.toFixed(2)));
	               }
	              }
var commonFunctions = {
    //state city details by pincode
    getCityByPinCode: function (element,pincode, callback) {
        if(pincode == ""){return false;}
             $.ajax({
            type: "POST",
            url: "../common/getStateCityByPincode.php",
            cache: false,
            data: { pincode: pincode },
            beforeSend: function () {
            	element.addClass('spinner');
            },
            complete: function () {
            	element.removeClass('spinner');
            },
            success: function (data) {
            	
            	  data = JSON.parse(data);
            	  if (data.length > 0) {
            		  callback(data);
            	  }else{
            		 
            	  }
            	  
            
               
            }
        });
    },
    //get company details
    getCompanyDetails: function getCompanyDetails(callback) {
        $.ajax({
            type: "POST",
            url: "php/getCompanyDetails.php",
            cache: false,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (data) {
                data = JSON.parse(data);
                callback(data);
            }
        });
    },
    //get job Status
    getJobStatuses: function getJobStatuses(callback) {
        $.ajax({
            type: "POST",
            url: "php/getJobStatuses.php",
            cache: false,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (data) {
                data = JSON.parse(data);
                callback(data);
            }
        });
    },
    //get designations
    getDesignations: function getDesignations(callback) {
        $.ajax({
            type: "POST",
            url: "php/getDesignations.php",
            cache: false,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (data) {
                data = JSON.parse(data);
                callback(data);
            }
        });
    },
    //get departments
    getDepartments: function getDepartments(callback) {
        $.ajax({
            type: "POST",
            url: "php/getDepartments.php",
            cache: false,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (data) {
                data = JSON.parse(data);
                callback(data);
            }
        });
    },
    //get branches
    getBranchDetails: function getBranchDetails(callback) {
        $.ajax({
            type: "POST",
            url: "php/getBranchDetails.php",
            cache: false,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (data) {
                data = JSON.parse(data);
                callback(data);
            }
        });
    },
    //get slab by type
    getSlabByType: function getSlabByType(element,type, callback) {
        $.ajax({
            type: "POST",
            url: "php/getSlabByType.php",
            data: { 'slab_type': type },
            cache: false,
            beforeSend: function () {
                element.loading(true);
            },
            complete: function () {
                element.loading(false);
            },
            success: function (data) {
                data = JSON.parse(data);
                callback(data);
            }
        });
    },
    //calculate minimum salary for the slab
    calcMinimumSalaryForSlab: function (percentage, value) {
        var salary;
        var remainingPer = 100 - parseFloat(percentage);
        salary = (parseFloat(value) / parseFloat(remainingPer)) * 100;
        return salary.toFixed(2);
    },
    //calc amounts for slabs
    calculateAmountForSlabs: function (slabData, amount) {
    	var allow;
        var temp, totalSal = 0;
        var slabAmounts = {};
        if (parseFloat(amount) < parseFloat(slabData['minimum_salary_amt']) && slabData['minimum_salary_amt'] != 'Nil') {
            //if provided amount doesn't gt the minimum salary
            return false;
        }
        for (i = 0; i < slabData.allowancesColumn.length; i++) {
        	   //get the name of the allowances and get the allowance value for it
            allow = slabData[slabData.allowancesColumn[i]].split('|');
            if (allow[1] == 'P') {
                //for percentage calc
                temp = parseFloat(amount) * (parseFloat(allow[0]) / 100);
                slabAmounts[slabData.allowancesColumn[i]] = parseFloat(temp).toFixed(2);
                totalSal = parseFloat(totalSal) + parseFloat(temp);
            } else if (allow[1] == 'A' && allow[0] != 'R') {
                //for amount calc
                slabAmounts[slabData.allowancesColumn[i]] = parseFloat(allow[0]).toFixed(2);
                totalSal = parseFloat(totalSal) + parseFloat(allow[0]);
            }
           
        }
        for (i = 0; i < slabData.allowancesColumn.length; i++) {
        	allow = slabData[slabData.allowancesColumn[i]].split('|');
        	 if (allow[0] == 'R') {
                 //remaining amount
             	 slabAmounts[slabData.allowancesColumn[i]] = (parseFloat(amount) - parseFloat(totalSal)).toFixed(2);
             	 var grossVal= parseFloat(amount) - parseFloat(totalSal);
               }
        }
        if (slabData['based_on'] == 'basic') {
        	 var grossSal = parseFloat(totalSal) + parseFloat(amount)+((grossVal)?grossVal:0);
            slabAmounts['grossSalary'] = grossSal.toFixed(2);
        }
         return slabAmounts;
    }
};

//Format Number
function reFormate(id) {
	var str =  id.value;
	id.value = addCommas(str.replace(/,/g,''));
}
function reFormateByNumber(number) {
    return addCommas(number);
}
function addCommas(nStr){
     nStr += '';
     x = nStr.split('.');
     x1 = x[0];
     x2 = x.length > 1 ? '.' + x[1] : '';
     var rgx = /(\d+)(\d{3})/;
     var z = 0;
     var len = String(x1).length;
     var num = parseInt((len/2)-1);
 
      while (rgx.test(x1))
      {
        if(z > 0)
        {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        else
        {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
          rgx = /(\d+)(\d{2})/;
        }
        z++;
        num--;
        if(num == 0)
        {
          break;
        }
      }
     return x1 + x2;
}
function deFormate(value) {
	
	/*if(value.indexOf(',') > -1){
		deValue = value.replace(/,/g,'');
	}else{
		deValue = value;
	}*/
	if(value != undefined)
		deValue = value.replace(/,/g,'');
	return deValue;
}
(function ( $ ) {
 
    $.fn.loading = function(flag) {
        if(flag){
            this.children().each(function(){
                $(this).hide();
            });
            this.addClass('loader loading');
        }else{
            this.children().each(function(){
                $(this).show();
            });
            this.removeClass('loader loading');
        }
        return this;
    };
 
}( jQuery ));

///image crop Funtiocn
// convert bytes into friendly format
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

// check for selected crop region
function checkForm() {
    if (parseInt($('#w').val())) return true;
    $('.error').html('Please select a crop region and then press Upload').show();
    return false;
};

// update info by cropping (onChange and onSelect events handler)
function updateInfo(e) {
	 if(parseInt(e.w) > 0) {
	        // Show image preview
	        var imageObj = $("#preview")[0];
	        var canvas = $("#preview_image")[0];
	        var context = canvas.getContext("2d");
	        context.drawImage(imageObj, e.x, e.y, e.w, e.h, 0, 0, canvas.width, canvas.height);
	        }
    $('#x1').val(e.x);
    $('#y1').val(e.y);
    $('#x2').val(e.x2);
    $('#y2').val(e.y2);
    $('#w').val(e.w);
    $('#h').val(e.h);
};

// clear info by cropping (onRelease event handler)
function clearInfo() {
    $('.info #w').val('');
    $('.info #h').val('');
};

// Create variables (in this scope) to hold the Jcrop API and image size
var jcrop_api, boundx, boundy;

function fileSelectHandler() {

    // get selected file
    var oFile = $('#image_file')[0].files[0];

    // hide all errors
    $('.error').hide();
    $('#file_error').addClass('hide');
    // check for image type (jpg and png are allowed)
    var rFilter = /^(image\/jpeg|image\/png)$/i;
    if (! rFilter.test(oFile.type)) {
        $('.error').html('Please select a valid image file (jpg and png are allowed)').show();
        return;
    }

    // check for file size
    if (oFile.size > 1048576) {
        $('.error').html('Image size should be less than 1MB').show();
        return;
    }

    // preview element
    var oImage = document.getElementById('preview');

    // prepare HTML5 FileReader
    var oReader = new FileReader();
        oReader.onload = function(e) {

        // e.target.result contains the DataURL which we can use as a source of the image
        oImage.src = e.target.result;
        oImage.onload = function () { // onload event handler

            // display step 2
            $('.step2').fadeIn(500);

            // display some basic image info
            var sResultFileSize = bytesToSize(oFile.size);
          /*  $('#filesize').val(sResultFileSize);
            $('#filetype').val(oFile.type);
            $('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);*/

            // destroy Jcrop if it is existed
            if (typeof jcrop_api != 'undefined') {
                jcrop_api.destroy();
                jcrop_api = null;
                $('#preview').width(oImage.naturalWidth);
                $('#preview').height(oImage.naturalHeight);
            }

           
                // initialize Jcrop
                $('#preview').Jcrop({
              	  aspectRatio: 130 / 170, // 0.7647
                    minSize: [130, 170],
                    setSelect: [260, 340, 0, 0], // keep aspect ratio 1:1
                    boxWidth: 650,   //Maximum width you want for your bigger images
                    boxHeight: 400,  //Maximum Height for your bigger images
                    bgFade: true, // use fade effect
                    bgOpacity: .3, // fade opacity
                    onChange: updateInfo,
                    onSelect: updateInfo,
                    onRelease: clearInfo
                }, function(){

                    // use the Jcrop API to get the real image size
                    var bounds = this.getBounds();
                    boundx = bounds[0];
                    boundy = bounds[1];

                    // Store the Jcrop API in the jcrop_api variable
                    jcrop_api = this;
                });
           

        };
    };

    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
    
}

//For number Tect in Javascript

function inWords( n ) {

	var junkVal=n.toString();
    var obStr=new String(junkVal);
    numReversed=obStr.split("");
    actnumber=numReversed.reverse();
    if(Number(junkVal) >=0){
        //do nothing
    }
    else{
        alert('wrong Number cannot be converted');
        return false;
    }
    if(Number(junkVal)==0){
        junkVal.push(obStr+''+'Rupees Zero Only');
        return false;
    }
    var iWords=["Zero", " One", " Two", " Three", " Four", " Five", " Six", " Seven", " Eight", " Nine"];
    var ePlace=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
    var tensPlace=['dummy', ' Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];
    var iWordsLength=numReversed.length;
    var totalWords="";
    var inWords=new Array();
    var finalWord="";
    j=0;
    for(i=0; i<iWordsLength; i++){
        switch(i)
        {
        case 0:
            if(actnumber[i]==0 || actnumber[i+1]==1 ) {
                inWords[j]='';
            }
            else {																
                inWords[j]=iWords[actnumber[i]];
            }
            inWords[j]=inWords[j]+' Only';
            break;
        case 1:
            tens_complication();
            break;
        case 2:
            if(actnumber[i]==0) {
                inWords[j]='';
            }
            else if(actnumber[i-1]!=0 && actnumber[i-2]!=0) {
                inWords[j]=iWords[actnumber[i]]+' Hundred and';
            }
            else {
                inWords[j]=iWords[actnumber[i]]+' Hundred';
            }
            break;
        case 3:
            if(actnumber[i]==0 || actnumber[i+1]==1) {
                inWords[j]='';
            }
            else {
                inWords[j]=iWords[actnumber[i]];
            }
            if(actnumber[i+1] != 0 || actnumber[i] > 0){
                inWords[j]=inWords[j]+" Thousand";
            }
            break;
        case 4:
            tens_complication();
            break;
        case 5:
            if(actnumber[i]==0 || actnumber[i+1]==1) {
                inWords[j]='';
            }
            else {
                inWords[j]=iWords[actnumber[i]];
            }
            if(actnumber[i+1] != 0 || actnumber[i] > 0){
                inWords[j]=inWords[j]+" Lakhs";
            }
            break;
        case 6:
            tens_complication();
            break;
        case 7:
            if(actnumber[i]==0 || actnumber[i+1]==1 ){
                inWords[j]='';
            }
            else {
                inWords[j]=iWords[actnumber[i]];
            }
            inWords[j]=inWords[j]+" Crore";
            break;
        case 8:
            tens_complication();
            break;
        default:
            break;
        }
        j++;
    }
    function tens_complication() {
        if(actnumber[i]==0) {
            inWords[j]='';
        }
        else if(actnumber[i]==1) {
            inWords[j]=ePlace[actnumber[i-1]];
        }
        else {
            inWords[j]=tensPlace[actnumber[i]];
        }
    }
    inWords.reverse();
    for(i=0; i<inWords.length; i++) {
        finalWord+=inWords[i];
    }
    return finalWord;

}
function firstToUpperCase( str ) {
    return str.substr(0, 1).toUpperCase() + str.substr(1);
}

//json Concat Array Value   
(function($){$.concat||$.extend({concat:function(b,c){var a=[];for(var x in arguments)if(typeof a=='object')a=a.concat(arguments[x]);return a}});})(jQuery);



//Add employee And View



 $('input[name =salary_type]').on('change', function () {
 $("input[type=radio][name=salary_based_on]").prop("checked", false);
 $('#getCTCcontent,#getTablecontent').html('');
 $('#submitForm,#employeeAddForm-next-2').show();
 });
 


 function loadSlabOptions(slabFor,callback) {
     var columns = [];
    commonFunctions.getSlabByType($('#slab'), slabFor, function (result) {
    	 $('#slab_opt').empty();
         $('#slab_opt').append('<option value="">Select Slab</option>');
         for (i = 0; i < result.length; i++) {
             if($("input[type=radio][name=salary_type]:checked").val()=='ctc'){
                 if(Math.round(Number(result[i]['min_salary_amount']))==0){
                	 $('#slab_opt').append('<option id="' + result[i]['slab_id'] + '" value="' + result[i]['slab_id'] + '">' + result[i]['slab_name'] + '</option>');
                	
                	 for (j = 0; j < columns.length; j++) {
                         $("#" + result[i]['slab_id']).attr("data-" + columns[j] + "", result[i][columns[j]]);
                     }
                     $("#" + result[i]['slab_id']).attr("data-min_salary_amount", result[i]['min_salary_amount']);
                     }
             }else{
             $('#slab_opt').append('<option id="' + result[i]['slab_id'] + '" value="' + result[i]['slab_id'] + '">' + result[i]['slab_name'] + '</option>');
             for (j = 0; j < columns.length; j++) {
                 $("#" + result[i]['slab_id']).attr("data-" + columns[j] + "", result[i][columns[j]]);
             }
             $("#" + result[i]['slab_id']).attr("data-min_salary_amount", result[i]['min_salary_amount']);

         }
        }
         $('#slab_opt').trigger('chosen:updated');
         
		  });
     callback('success');
 }

   

  	 $(document).on('keyup blur',".miscAlowDeduCtc,#Subctc,#ctc", function (e) {
		 e.preventDefault();
         var total = 0;
         $(".miscAlowDeduCtc").each(function(){
             total += parseFloat(Number(deFormate($(this).val())));
         });
         $('#ctc').val(reFormateByNumber((Number(deFormate($('#Subctc').val()))-total).toFixed(2)));
     });
    
 function eventForNoSlab(){
	 $('#getCTCcontent').find('input[type="text"]').bind('keyup blur', function () {
          var total = 0;
          $('#gross').val(0);
          $('#getCTCcontent').find('input[type="text"]').not(".miscAlowDeduCtc,#Subctc,#ctc").each(function () {
             total += parseFloat(Number(deFormate($(this).val())));
          });
          $('#gross').val(reFormateByNumber(total.toFixed(2)));
     });
 }

 
 function setData(data){
	 var html = '<tbody>';
	    $.each( data['allowances'], function (k, v) {
        	if(v!=null){
        		
			if(v.rate !=null && v.rate.split('|')[0]){
		    		rate=(v.rate.split('|')[1]=='P')?v.rate.split('|')[0]+' %':v.rate.split('|')[0];
    		    }else{
    		    	rate=v.rate;
    		    }
        	}
		 var allowDataVal=(Number(v.amount)).toFixed(2);
		 html += '<tr><td class="emptyDiv">'+v.label+'</td><td style="text-align:center;">'+rate+'</td><td style="text-align:right;"><input type="hidden" name="allowances['+v.name+']" class="salaryAmount" value="'+allowDataVal+'">'+allowDataVal+'</td><td style="text-align:right;">'+(Number(v.amount)*12).toFixed(2)+'</td></tr>';
	  });
	 
	  var grossVal=(Number(data.gross)).toFixed(2);
	  html += '<tr class="borderSet"><th style="font-weight: bold;text-align: right;" class="emptyDiv">Gross</th><th style="text-align:center;"></th><th style="text-align:right;"><input type="hidden" name="grossSalary" class="salaryAmount"  value="'+grossVal+'" >'+grossVal+'</th><th style="text-align:right;">'+Math.round((Number(data.gross)*12)).toFixed(2)+'</th></tr>';
      $.each( data['deductions'], function (k, v) {
    	  html += '<tr><td class="emptyDiv">'+v.label+'</td><td style="text-align:center;">'+v.rate +' % </td><td style="text-align:right;">'+(Number(v.amount)).toFixed(2)+'</td><td style="text-align:right;">'+(Number(v.amount)*12).toFixed(2)+'</td></tr>';
	  });
      html += '<tr  class="borderSet"><th style="font-weight: bold;text-align: right;" class="emptyDiv">Deduction</th><th style="text-align:center;"></th><th style="text-align:right;">'+(Number(data.totalDeductions)).toFixed(2)+'</th><th style="text-align:right;">'+(Number(data.totalDeductions)*12).toFixed(2)+'</th></tr>';
      html += '<tr  class="borderSet"><th style="font-weight: bold;text-align: right;" class="emptyDiv">CTC</th><th style="text-align:center;"></th><th style="text-align:right;">'+(Number(data.ctc)).toFixed(2)+'</th><th style="text-align:right;">'+Math.round((Number(data.ctc)*12)).toFixed(2)+'</th></tr>';
      html +='</tbody></table></div>';
      if(Math.round(data.difference)!=0 && data.isCtc =='1'){
     	 $('#submitForm,#employeeAddForm-next-2,.emp_up7,#incSubmit,#proSubmit').hide();
     	 html+='<br><div id="well" class="well" style="background-color: #fff;color: rgb(236, 74, 23);"><strong> Difference (Yearly) : <em>'+(data.difference*12).toFixed(2)+'</em></strong></div>';
          }else{
     	 $('#submitForm,#employeeAddForm-next-2,.emp_up7,#incSubmit,#proSubmit').show();
     	  }
     return html;
     }
 
 function validateForSalary(){
	  var inputs = $(".salaryValidate");
	  var j=0;
	  $('.text').remove();
	  (inputs.length==0)?$('#employeeAddForm-title-2').click():'';
	  if($('#slab_opt :selected').val()=='' || $('#slabinEmp').val()==''){
     $('#slab_opt_chosen').after('<label class="help-block text-danger text">Enter Slab Name </label>');
     }else{
     $('#slab_opt_chosen').next().remove();
     $('#slabinEmp').next().remove();
     j++;}
	  
       for(var i = 0; i < inputs.length; i++){
   	 if($(inputs[i]).val()!=''){
   		 j++;
   		 $(inputs[i]).next().remove();
       	 }else{
       		 labelVal=$(inputs[i]).parent().parent().parent().find('label').html();
           	 $(inputs[i]).next().remove();
       		 $(inputs[i]).after('<label class="help-block text-danger text">Enter '+labelVal+'</label>');
       		 }
   }
       ($("input[type=radio][name=salary_based_on]:checked").val()=='noslab')?j++:'';
      if(inputs.length<j){
   	 $('.text').remove();
        return true;
    }else{
    	 $('#employeeAddForm-title-2').click();
    	 }
}
 

	function setTimeslabIntialize(data){
	 $.each(data, function (k, v) {
		 if(k!="salary_type")
			 $('#' + k).val(v);
       });
 
	(data.slab_id == "Nil")?$('#slabdata').val('No Slab'):$('#slabdata').val(data.slab_name);
    (data.slab_id == "Nil")?$("#noSlabCaulation").click():'';  
     $('.hiddenSpan,#ctcSalaryCalc,#noSlabCaulation').hide();
     $('#slab_opt').prop('disabled', true).trigger("chosen:updated");
    $("#emp_ctc_form").find(":input").not(".emp_edit7,.back_emp,input[type=radio][name=salary_based_on]").each(function () {
         $(this).css({ 'background-color': '#FFF', 'border': '0px' });
          $(this).attr('disabled', true);
        });   
    $('.showSpan').show(); 
    setTimeout(function(){  
    $('.emp_up7').hide();
    $('.ctc_detail_loader,.ul_loader,.ctc_detail_loader_sub').loading(false);
    }, 100);
   }
	//sidemenu 
	//$('#sidebar').getNiceScroll().hide();
	
	//side Menu HIde
	// $('#container').addClass('sidebar-closed');
	//$('#main-content').css('margin-left','0px');
	//$('#sidebar').css('margin-left','-221px');
	
		
	 $('.ITtabNav').on('click', function () {
	        var menu = $(this).attr('href');
	        menu = menu.split('#');
	        $('#click_' + menu[1]).parent().siblings().each(function () {
	            $(this).removeClass('active');
	        });
	        $('#click_' + menu[1]).parent().addClass('active');
	    });
	    
	
	 
	    	$(document).on('click', 'a.itImgView', function (e) {
	    	e.preventDefault();
	    	var imag = $(this).find("input").val();
	    	if(imag!='Nil' &&  imag!='' ){
	        $(this).prop( "disabled", false);
	        $('#preview_image').attr('src', imag);
	        }
	    	});
	    
	    	$(document).on('change', '.itImagechange', function (e) {
		    	e.preventDefault();
		    	 var rFilter = /^(image\/jpeg|image\/png)$/i;
		    	 var element=$(this)[0].files[0];
		    	 var idVal=($(this).attr('name'))?$(this).attr('name'):$(this).data('id');
		    	
		    	 var data=this;
		     	    if (! rFilter.test(element.type)) {
		      	    	$('#text1,#text').html('Please select a valid image file (jpg and png are allowed)').show();
		      	    	$(this).replaceWith($(this).val('').clone(true));
		      	    	//console.log($(this).replaceWith($(this).val('').clone(true)));
		      	    	return;
		      	    }else if(rFilter.test(element.type)){
		      	      ImageTools.resize(element, {
		  	            width: 672, // maximum width
		  	            height: 1024 // maximum height
		  	        },
		  	      function (blob, didItResize) {
		  	         $('#text1,#text').html('');
		  	         $('#'+ idVal+'_currentImage').val(window.URL.createObjectURL(blob));
		  	         $('#'+idVal).val(window.URL.createObjectURL(blob));
		  	      });
		      	    }
		     });
	    	
	    
	    	/*Income tax and Summery  Script*/ 
	    	function it_summary(employee_id) {
	    		//var year=$('#years').val();
	    		var year=$('#year').val();
	    		 $.ajax({
	    	       dataType: 'html',
	    	       type: "POST",
	    	       url: "../common/itDeclaration.handle.php",
	    	       cache: false,
	    	        data: {act:localStorage.getItem("itSummeryActData"),current_payroll_month:current_payroll_month,employee_id: employee_id,year:year},
	    	       success: function (data) {
	    	           var json_obj = $.parseJSON(data); //parse JSON
	    	           $('#it_declare_summery').show();
	    	           $('#it_declare_tab').hide();
	    	           
	    	           var s = 0;
	    	          prev_earnings_app=json_obj[2].employee_it_declaration[0].prev_earnings_app;
	       	       	  $('.name_emp').html(json_obj[2].employee_salary_details[0].employee_name + "  's IT Summary ");
	    	           
	    	           //table header setting
	       	         var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
	    	        	   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
	    	        	 ];
	       	          var  formattedDate =new Date(current_payroll_month);
	       	          var m =  monthNames [formattedDate.getMonth()];
	       	       var pre = formattedDate.getMonth()!=0?monthNames [formattedDate.getMonth()-1]:"Dec";
	       	        
	       	          if((json_obj[2].employee_it_declaration[0].years)==0){
	    	        	 $('#previousMonthsHead').html('<p>Apr - Mar(<i class="fa fa-rupee"></i>)</p>');
	    	        	 $('#previousMonthsHead1').html('<p>Projected(<i class="fa fa-rupee"></i>)</p>');
	    	          }else{
	    	        	  $("#previousMonthsHead").html('<p>Apr - '+pre+'(<i class="fa fa-rupee"></i>)</p>');
	    	        	  $('#previousMonthsHead1').html('<p>'+m+' - Mar(<i class="fa fa-rupee"></i>)</p>');
	    	        	  }
	    	          
	    	           $.each(json_obj[2].employee_salary_details[0], function (k, v) {
	    	        	   if(k=='gross')
	    	        		   v=parseFloat(v)+parseFloat(prev_earnings_app);
	    	        	   if(v!='-'){
	    	        		   $('.'+k).html(reFormateByNumber(Number(v).toFixed(2)));
	    	        	   }else{
	    	        		   $('.'+k).html(v);
	    	        	   }
	    	         });
	    	           $.each(json_obj[2].employee_income_tax[0], function (k, v) {
	    	               //display the key and value pair
	    	               $('.' + k).html(reFormateByNumber(Number(v).toFixed(2)));
	    	          });
	    	           $('.c_incentive').html(json_obj[2].employee_salary_details[0].c_incentive);
	    	           
	    	           $('.c_bonus').html(json_obj[2].employee_salary_details[0].c_bonus);
	    	           perq = '0.00';
	    	           $('.perq').html(perq);
	    	           $('.prev_earnings_app').html(prev_earnings_app);
	    	           $('.othersGross').html((Number(deFormate($('.prev_earnings_app').html()))+Number(deFormate($('.perq').html()))+Number(deFormate($('.c_other').html()))+Number(deFormate($('.c_incentive').html()))+Number(deFormate($('.c_bonus').html()))).toFixed(2));
	    	           grosVal=Number(deFormate($('.gross').html()))+Number(deFormate($('.house_prop_inc').html()))+Number(deFormate($('.other_income').html()));
	    	           $('.Tot_income').html(reFormateByNumber(grosVal.toFixed(2)));     
	    	           $('.salary_month').html($('.gross').html());
	    	           income = Number(deFormate($('.house_prop_inc').html()) )+Number(deFormate($('.other_income').html()));
	    	   flag=1;
	    	           //IT DECALRATION CAL
	    	           var _80g = 0;
	    	           var _80c = 0;
	    	           var _80d = 0;
	    	           var _80e = 0;
	    	           var other = 0;
	    	           
	    	           $.each(json_obj[2].employee_it_declaration[0], function (k, v) {
	    	               //display the key and value pair
	    	               if (k[2] == "g") {
	    	                   _80g += parseFloat(v, 10);
	    	               } else {
	    	                   if (k[2] == "c") {
	    	                       _80c += parseFloat(v, 10);
	    	                   } else {
	    	                       if (k[2] == "d") {
	    	                           _80d += parseFloat(v, 10);
	    	                       } else {
	    	                           if (k[2] == "e" & k !='prev_earnings_app') {
	    	                               _80e += parseFloat(v, 10);
	    	                           } else {
	    	                        	   if(k !='prev_earnings_app' && k !='years')
	    	                        		   other += parseFloat(v, 10);
	    	                           }
	    	                       }
	    	                   }
	    	               }
	    	               $('.'+k).html(reFormateByNumber(Number(v).toFixed(2)));

	    	           });
	    	           
	    	           $('._80g').html(reFormateByNumber(_80g.toFixed(2))); //3
	    	           $('._80c').html(reFormateByNumber((_80c+Number(json_obj[2].employee_income_tax[0].epf_employee)).toFixed(2))); //3
	    	           $('._80d').html(reFormateByNumber(_80d.toFixed(2))); //3
	    	           $('._80e').html(reFormateByNumber(_80e.toFixed(2))); //3
	    	            $('.other').html(reFormateByNumber(other.toFixed(2))); //3
	    	            

	    	            val_perq = "0.00";
	    	           $('.val_perq').html(val_perq);
	    	           lieu = "0.00";
	    	           $('.lieu').html(lieu);
	    	          tot_perq = parseFloat(Number(deFormate($('.gross').html())), 10) + parseFloat(val_perq, 10) + parseFloat(lieu, 10);
	    	           $('.tot_perq').html(reFormateByNumber(tot_perq.toFixed(2)));

	    	            exemption = parseFloat(json_obj[2].employee_income_tax[0].exe_hra, 10) + parseFloat(json_obj[2].employee_income_tax[0].exe_lta, 10) +
	    	                parseFloat(json_obj[2].employee_income_tax[0].exe_oth, 10);
	    	           $('.exemption').html(reFormateByNumber(exemption.toFixed(2)));
	    	           $('.balance').html(reFormateByNumber((tot_perq - exemption).toFixed(2))); //3

	    	            $('.taxon_employment').html(parseFloat(json_obj[2].employee_income_tax[0].taxon_employment, 10).toFixed(2));
	    	           entertain = "0.00";
	    	           $('.entertain').html(reFormateByNumber(entertain)); //5
	    	           aggregate = parseFloat(entertain, 10) + parseFloat(json_obj[2].employee_income_tax[0].taxon_employment, 10);
	    	           $('.aggregate').html(reFormateByNumber(aggregate.toFixed(2))); //4()a+4(b)
	    	           income_charable = (tot_perq - exemption) - aggregate;
	    	           $('.income_charable').html(reFormateByNumber(income_charable.toFixed(2)));
	    	           $('.any_income').html(reFormateByNumber(income.toFixed(2)));
	    	           $('.gross_s').html(reFormateByNumber((income + income_charable).toFixed(2)));

	    	           aggregate_dec = parseFloat(json_obj[2].employee_income_tax[0].ded_80c, 10) + parseFloat(json_obj[2].employee_income_tax[0].ded_80d, 10) +
	    	                parseFloat(json_obj[2].employee_income_tax[0].ded_80e, 10) + parseFloat(json_obj[2].employee_income_tax[0].ded_80g, 10) +
	    	                parseFloat(json_obj[2].employee_income_tax[0].ded_other, 10);

	    	           $('.aggregate_dec').html(reFormateByNumber(aggregate_dec.toFixed(2)));
	    	           tot_dec = ((income + income_charable) - aggregate_dec);
	    	           $('.tot_dec').html(reFormateByNumber(tot_dec.toFixed(2)));
	    	           tax_tot = parseFloat(json_obj[2].employee_income_tax[0].tax, 10) +
                            parseFloat(json_obj[2].employee_income_tax[0].surcharge, 10)+
	    	                parseFloat(json_obj[2].employee_income_tax[0].ec, 10) +
	    	                parseFloat(json_obj[2].employee_income_tax[0].shec, 10);
	    	           payable_tax = tax_tot-parseFloat(json_obj[2].employee_income_tax[0].tax_paid, 10);
	    	           $('.tax_payable').html(reFormateByNumber(tax_tot.toFixed(2)));
	    	           //$('.net_tax_payable').html(reFormateByNumber(parseFloat(json_obj[2].employee_income_tax[0].tax_payable, 10)));
	    	           $('.net_tax_payable').html(reFormateByNumber(payable_tax.toFixed(2)));
	    	           tax_payable_tot =Math.max(0,(Number(deFormate($('.tax_payable').html()))- parseFloat(json_obj[2].employee_income_tax[0].relief, 10)));
	    	           $('.tax_payable_tot').html(reFormateByNumber(tax_payable_tot.toFixed(2)));
	    	           
	    	           var element=$('#hiddenpartBText').html($('#partBContent').clone());
	    	           element.find(".hiddentrPdf").each(function () {
	    	              $(this).remove();
	    	           });
	    	           var beneEelement=$('#hiddenBeneifitsText').html($('#benefitsPaidContent').clone());
	    	           beneEelement.find(".hiddentrPdf").each(function () {
	    	              $(this).remove();
	    	           });
	    	          $("#partBContentId").val($('#partBContent').html());
	    	          $('#benefitsPaidId').val($('#benefitsPaidContent').html());
	    	          /*
	    	          $perqs = json_obj[2].employee_perquisites;
	    	          $perqsTable = "";
	    	          if($perqs != ""){
	    	        	  $perqsTable = '<header class="panel-heading"> Statment of perqs </header><table class="table table-bordered table-striped table-condensed"><thead><tr><th class="">Perquisites</th><th>Value</th><th>Recovered</th><th>Taxable</th></tr></thead><tbody>';
	    	          
	    	        	  for (var i = 0; i < $perqs.length; ++i) {
		    	        	  $perqsTable += '<tr><td>'+$perqs[i].perqs_type+'</td><td>'+$perqs[i].value+'</td><td>'+$perqs[i].dedu_amount+'</td><td></td>';
		    	          }
	    	        	  $perqsTable +='</tbody></table>';
	    	          }else{
	    	        	  $perqsTable += '<div style ="margin-left:25px"><br> No Perquisites Mapped for this Employee</div>';
	    	          }
	    	          
	    	          $("#form12ba").html($perqsTable);
	    	          */
	    	       }

	    	   });
	    	}
	    
	      	function it_view(employee_id,year) {
	       	$('.showDiv').show();
	       	 $('.emptyHtml').html('');
	           $.ajax({
	               dataType: 'html',
	               type: "POST",
	               url: "../common/itDeclaration.handle.php",
	       	    cache: false,
	       	    data: {act:localStorage.getItem("itDeclarationActData"),employee_id: employee_id,year:year},
	       	    success: function (data) {
	                   var json_obj = $.parseJSON(data); //parse JSON
	                   $('#privious_id').val(json_obj[2][0].employee_id);
	                   $('#hra_id').val(json_obj[2][0].employee_id);
	                   $('#exemption_id').val(json_obj[2][0].employee_id);
	                   $('#80c_id').val(json_obj[2][0].employee_id);
	                   $('#80d_id').val(json_obj[2][0].employee_id);
	                   $('#other_deduc_id').val(json_obj[2][0].employee_id);
	                   $('#house_id').val(json_obj[2][0].employee_id);
	                   $('#income_id').val(json_obj[2][0].employee_id);
	                   $('#it_declare_summery').hide();
	                   $('#it_declare_tab').show();
	                   $('.name_emp1').html(json_obj[2][0].employee_name + "  's IT DECLARATION ");
	                //   $('.name_emp').html(json_obj[2][0].employee_name + "  's IT SUMMARY ");
	                   $('#empPan').val(json_obj[2][0].employee_pan_no);
	                   $.each(json_obj[2][0], function (k, v) {
	                   	  var lastSlash = k.lastIndexOf("_");
	                       if(k.substring(lastSlash+1)=='proof')
	                       {
	                       	 $('#' + k).val(v);
	                       	 $('#' + k).attr('data-id',v);
	                            if($('#'+k).val()!=='Nil' && $('#'+k).val()!==''){
	                          	   	  $('#'+k).parent().removeClass('btn-danger');
	                          	   	 $('#'+k).parent().addClass('btn-primary');
	                          	   	 $('#'+k).parent().find('.fa').removeClass("fa-paperclip").addClass("fa-eye");
	                          	     }else{
	                          	   	$('#'+k).parent().removeClass('btn-danger btn-primary').addClass('btn-danger').prop( "disabled", true)
	                               	   	 }
	                       }else{               
	                       //display the key and value pair
	                    	if(k!='year')   
	                    		(Number(v) === parseInt(Number(v), 10))?$('#' + k).val(reFormateByNumber(v)):$('#' + k).val(v);
	                       $('#' + k).attr('data-id',v);
	                      }
	                   });
	            }

	           });
	       }
	    /*END OF Income tax and Summery  Script*/
	      	
	      	//leve.php from 
	      	function daydiff(first, second) {
				return (second-first)/(1000*60*60*24)
				}
				function parseDate(str) {
				    var mdy = str.split('/');   
				    return  new Date(mdy[2], mdy[1] - 1, mdy[0]);
				}	
				

				function humanTiming(date, ref_date, date_formats, time_units) {
				  //Date Formats must be be ordered smallest -> largest and must end in a format with ceiling of null
				  date_formats = date_formats || {
				    past: [
				      { ceiling: 60, text: "$seconds seconds ago" },
				      { ceiling: 3600, text: "$minutes minutes ago" },
				      { ceiling: 86400, text: "$hours hours ago" },
				      { ceiling: 2629744, text: "$days days ago" },
				      { ceiling: 31556926, text: "$months months ago" },
				      { ceiling: null, text: "$years years ago" }      
				    ],
				    future: [
				      { ceiling: 60, text: "in $seconds seconds" },
				      { ceiling: 3600, text: "in $minutes minutes" },
				      { ceiling: 86400, text: "in $hours hours" },
				      { ceiling: 2629744, text: "in $days days" },
				      { ceiling: 31556926, text: "in $months months" },
				      { ceiling: null, text: "in $years years" }
				    ]
				  };
				  //Time units must be be ordered largest -> smallest
				  time_units = time_units || [
				    [31556926, 'years'],
				    [2629744, 'months'],
				    [86400, 'days'],
				    [3600, 'hours'],
				    [60, 'minutes'],
				    [1, 'seconds']
				  ];
				  
				  date = new Date(date);
				  ref_date = ref_date ? new Date(ref_date) : new Date();
				  var seconds_difference = (ref_date - date) / 1000;
				  
				  var tense = 'past';
				  if (seconds_difference < 0) {
				    tense = 'future';
				    seconds_difference = 0-seconds_difference;
				  }
				  
				  function get_format() {
				    for (var i=0; i<date_formats[tense].length; i++) {
				      if (date_formats[tense][i].ceiling == null || seconds_difference <= date_formats[tense][i].ceiling) {
				        return date_formats[tense][i];
				      }
				    }
				    return null;
				  }
				  
				  function get_time_breakdown() {
				    var seconds = seconds_difference;
				    var breakdown = {};
				    for(var i=0; i<time_units.length; i++) {
				      var occurences_of_unit = Math.floor(seconds / time_units[i][0]);
				      seconds = seconds - (time_units[i][0] * occurences_of_unit);
				      breakdown[time_units[i][1]] = occurences_of_unit;
				    }
				    return breakdown;
				  }

				  function render_date(date_format) {
				    var breakdown = get_time_breakdown();
				    var time_ago_text = date_format.text.replace(/\$(\w+)/g, function() {
				      return breakdown[arguments[1]];
				    });
				    return depluralize_time_ago_text(time_ago_text, breakdown);
				  }
				  
				  function depluralize_time_ago_text(time_ago_text, breakdown) {
				    for(var i in breakdown) {
				      if (breakdown[i] == 1) {
				        var regexp = new RegExp("\\b"+i+"\\b");
				        time_ago_text = time_ago_text.replace(regexp, function() {
				          return arguments[0].replace(/s\b/g, '');
				        });
				      }
				    }
				    return time_ago_text;
				  }
				          
				  return render_date(get_format());
				}
				
				//remove after # in url Function
				function removeHash(){
	        		   window.location.replace("#");
	        	       // slice off the remaining '#' in HTML5:    
	        			if (typeof window.history.replaceState == 'function') {
	        			  history.replaceState({}, '', window.location.href.slice(0, -1));
	        			}
	        	   }
				
				/*Fliter Cutomise*/
				if(filename=='viewPayroll.php' || filename=='miscellaneous.php' || filename=='attendance.php' || filename=='attendances.php' || filename=='previewPayroll.php' || filename=='lopupdate.php'){
					//Chosen initialize 
					$('#filter_Shift_chosenId,#filter_Department_chosenId,#filter_Designation_chosenId,#filter_Branch_chosenId,#filter_Team_chosenId')
		              .chosen();
		              element=$("input:radio[name='filterfor']:checked").attr('id');
		              commonActioninFilter();
		              
					//Chnange radio Button 
					$(document).on('change', "input[name='filterfor']", function (e) {
			        	  $('#employeeSelected').html('').trigger("chosen:updated");
			             $('#filter_Designation_Id,#filter_Department_Id,#filter_Branch_Id,#filter_Shift_Id,#filter_Team_Id').hide();
			             $('#filter_'+$(this).attr('id')+'_Id').show();
			            if($(this).val()=='E')
			               getOptionValue('E');
			            else
			               getOptionValue(null);
			          });

					//Change Chosen 
			          $(document).on('change', "#filter_Shift_chosenId,#filter_Department_chosenId,#filter_Designation_chosenId,#filter_Branch_chosenId,#filter_Team_chosenId", function (e) {
			        	  getOptionValue(null);
			          });

			          //Get Employees
			          function getOptionValue(filterValue){
			        	  if(filterValue!='E'){
					              element=$("input:radio[name='filterfor']:checked").attr('id');
					              filterValue=$('#filter_'+element+'_chosenId').val();
			        	  }
			        	  filterKey= $("input:radio[name='filterfor']:checked").val();
			        	  $('.showedEmp').hide();
			        	  if((filterValue!=null || filterValue=='E') && filterValue!='Select Branch'
			            	   && filterValue!='Select Designation' && filterValue!='Select Shift' && filterValue!='Select Department' && filterValue!='Select Team'
			               ){
			        	$.ajax({
			              	dataType: 'html',
			                  type: "POST",
			                  url: "php/filter.handle.php",
			                  cache: false,
			                  data: {screen:$("#screenName").val(),act:$('#act').val(),filterKey:filterKey,filterValue:filterValue,
			                	    loadDisabled:$('#loadDisabled').val(),loadprocessedEmp:$('#loadprocessedEmp').val()},
			                  beforeSend:function(){
			                     	$('#filterLoader').loading(true); 
			                      },
			                 success: function (data) {
			                      jsonData = JSON.parse(data);
			                      $('#employeeSelected').html();
			                      optionValues="";
			                      $('.showedEmp').show();
			                      if(jsonData[2]=="No Employees Found"){
			                    	  jsonData[2]=[];
			                    	  $('#employeeSelected').attr('data-placeholder',"No Employees Found");
			                    	  $('#employeeSelected').chosen('destroy');
			                    	  $('#employeeSelected').chosen().html('').trigger("chosen:updated");
			                    }
			                      if(jsonData[0]=='success' &&  jsonData[2].length>0){
			                    	  $('#employeeSelected').attr('data-placeholder',"Select Employee(s)");
			                    	  $('#employeeSelected').chosen('destroy');
			                    	  $('#employeeSelected').chosen();
			                          chosenEnaabledEmp=[];
						                       for(i=0;i<jsonData[2].length;i++){
						                    	(jsonData[2][i].enabled==0)?chosenEnaabledEmp[i]="<span style='float:right;color: red'>foo</span>":'';
						                    	    optionValues+='<option data-enabled="'+jsonData[2][i].enabled+'"value='+jsonData[2][i].employee_id+'>'+jsonData[2][i].employee_name+' [ '+jsonData[2][i].employee_id+' ] </option>';
						                       }
						                       $('#employeeSelected').html(optionValues);
			                                   $("#employeeSelected").trigger("chosen:updated"); //for drop down
			                       }
			                  }   
			                       });
			              }
				      	   $('#filterLoader').loading(false); 
			           	 }     

                      //Employee Chosen Disable Display using shwing_dropDown event 
			          $(function () {
			        	  var $select = $('#employeeSelected');
			        	  $select.chosen();
			        	  $select.on("chosen:showing_dropdown", function() {
			        		 if(chosenEnaabledEmp.length>0){
			            	     chosenEnaabledEmp.forEach(function(k,v) {
			                 	   vale=$("#employeeSelected_chosen .chosen-results li[data-option-array-index="+v+"]").
			                 	   prepend("<span class='badge bg-important' style='float:right;font-size:10px;'>Disabled</span> ")
			                 	 });
			        		 }
			              });
			        	});
			    	  
					
				//Filter Cutomise Select Function in js
				 $(document).on('click', "#filterSelect", function () {
					 $('#loaderForEmployees').loading(true);
				     $('#employeeSelected option').prop("selected", true);
				     $('#loaderForEmployees').loading(false);
				     $('#employeeSelected').trigger('chosen:updated');
	             });
				 
				//Filter Cutomise Deselect Function in js
				$(document).on('click', "#filterDeselect", function () {
					$('#loaderForEmployees').loading(true);
	            	  $('#employeeSelected option').removeAttr("selected");
	            	  $('#loaderForEmployees').loading(false);
	            	  $('#employeeSelected').trigger('chosen:updated');
		          });
				
				//Filter canel Button 
				 $(document).on('click', "#filterCancel", function (e) {
	                  e.preventDefault();
	                  $("#filterHideShowDiv,.showedEmp").hide();
	                  commonActioninFilter();
	              });
				//Filter Shoe/hidediv 
				 $(document).on('click', "#showhideButton", function (e) {
	                  e.preventDefault();
	                  $('.showedEmp').hide();
	                  $("#filterHideShowDiv").toggle();
	                  $('#Lumsum').click();
	                  commonActioninFilter();
					 });
				 //For Remove Selected items in employee,designation,departments
				 function commonActioninFilter(){
					 element=$("input:radio[name='filterfor']:checked").attr('id');
					 $('#filter_'+element+'_Id option').removeAttr("selected").trigger('chosen:updated');
				     if(element=='Employee'){
		            	 $('.showedEmp').show();
		            	 $('#filterDeselect').click();
				     }else{
		              $('#filter_'+element+'_Id').show();
		              $('.showedEmp').hide();
		             }
				     $('#paymentAddForm').toggle();
				     $('#goButtonDiv').toggle();
				 }
				}
				
				
				 $(document).keyup(function (event) {
		                if (event.which === 27) {
		                   $('.popover').hide();
		                }
		            });	
				 
		         

		 