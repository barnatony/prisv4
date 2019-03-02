$(document).ready(function () {
	useCurrent:false;
	$( ".daterange1,.daterange2" ).datetimepicker({  
		format:'Do MMM,YYYY',
		useCurrent:true,
			
	});
	
	App.initHelpers(['datetimepicker']);
	 
});

$('.search_activity').click(function(e) {
	$myteam_dt.api().draw();
});

var datatables = function() {
	
	// Subscribers datatable
    var initAddressDataTable = function() {
    	$myteam_dt = jQuery('#myteam-datatable').dataTable({
            pageLength: 10,
            lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]],
            "processing": true,
            "serverSide": true,
            "sAjaxSource":App.myUrl("leaveRequests/dt_leave_requests/myteam/"),
            "fnServerData": function ( sSource, aoData, fnCallback ) {
            	
            		aoData.push( { "name": "min", "value": $('.daterange1').val() } );
            		aoData.push( { "name": "max", "value": $('.daterange2').val() } );
            		aoData.push( { "name": "status", "value": $( "#filter_status option:selected" ).val() } );
            		aoData.push( { "name": "leave", "value": $( "#filter_leave option:selected" ).val() } );
            		aoData.push( { "name": "start", "value": aoData[3].value } );
            		aoData.push( { "name": "length", "value": aoData[4].value } );
            		aoData.push( { "name": "global_search", "value": $('.dataTables_filter input').val() } );
            	$.ajax( {
            	"dataType": 'json',
            	"type": "GET",
            	"url": App.myUrl("leaveRequests/dt_leave_requests/myteam/"),
            	"data": aoData,
            	"success": fnCallback
            	} );
            	

            },
            "aaSorting": [],
            "autoWidth": false,
            "dom": 'lfrtip<"clear"><"push-10 text-right" B>',
            "columns": [
                        {
                            "class":          "details-control",
                            "orderable":      false,
                            "data":           null,
                            "defaultContent": ""
                        },
                        { "data": "employee_name" },
                        { "data": "employee_id" },
                        { "data": "from_date" },
                        { "data": "from_half" },
                        { "data": "to_date" },
                        { "data": "to_half" },
                        { "data": "duration" },
                        { "data": "leave_type" },
                        { "data": "status" },
                        {
                            "orderable":      false,
                            "data":           null,
                            "defaultContent": ""
                        }
                        
                    ],
            "buttons": [
			             
			             {
			                 extend: 'excel',
			                 title :'LeaveRequests Raised',
			                 text: '<i class="fa  fa-file-excel-o"> Export</i>',
			                 className:'btn-sm',
			                 exportOptions: {
			                     columns: [1,2,3,4,5,6,7,8,9] // indexes of the columns that should be printed,
			                 } 
			             },
			         ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                    	if(data=='A')
                    		return '<a tabindex="0" class="label label-success approved" role="button" data-toggle="popover" data-trigger="focus"   title="Remarks" data-placement="left" data-content="'+row["admin_reason"]+'" data-original-title="Remarks">Approved</a>';
                    	else if(data=='RQ')
                    		return '<span class="label label-warning">Pending</span>';
                    	else if(data=='W')
                    		return '<span class="label label-danger">Withdrawed</span>';
                    	else if(data=='C')
                    		return '<span class="label label-danger">Cancelled</span>';
                    	else 
                    		return '<a tabindex="0" class="label label-danger approved" data-toggle="popover" data-trigger="focus" role="button" title="Remarks" data-placement="left" data-content="'+row["admin_reason"]+'"  data-original-title="Remarks">Rejected</a>'
                    },
                    "targets":'status'
                },
                {
                	"render": function ( data, type, row ) {
                		var compare_date=Date.parse(moment(row["from_date"]).format("YYYY-MM-DD"));
                		var atten_st_date=Date.parse(moment(row["attendance_st_date"]).format("YYYY-MM-DD"));
                		
                		
                    	if(row["status"]=='A')
                    		return '<div class="text-center push-50-r">-</div>';
                    	else if(row["status"]=='RQ' && compare_date>=atten_st_date)
                    		return '<button class="btn btn-xs btn-info  btn-success approve" data-value="A" data-reqid="'+row["request_id"]+'" data-id="'+row["id"]+'" type="button" title="Approve/Reject Requests" class="js-tooltip" data-placement="top"  data-original-title="Approve/Reject Request" data-toggle="modal" data-target="#approveReject-modal"><i class="si si-action-redo"></i> Respond</button>';
                    	else if(row["status"]=='W')
                    		return '<div class="text-center push-50-r">-</div>';
                    	else if(row["status"]=='C')
                    		return '<div class="text-center push-50-r">-</div>';
                    	else 
                    		return '<div class="text-center push-50-r">-</div>'
                    },
                    "targets":'actions'
                },
                {
                    "render": function ( data, type, row ) {
                    		return '<span class="label label-info">'+data+'</span>';
                    },
                    "targets":'leave_type'
                }
                
                ],
                "drawCallback": function( settings ) {
                	$(".approved").popover({
                		content:$(this).data("content"),
                		trigger:'hover'
                	});
                }
    	
        });
    	
    	// Array to track the ids of the details displayed rows
 var detailRows = [];
 $('tfoot').on( 'change', 'tr th.status,th.leave_type', function () {
 	$myteam_dt.api().draw();
   
 }); 
 
         
         $('#myteam-datatable tbody').on( 'click', 'tr td.details-control', function () {
             var tr = $(this).closest('tr');
             var row = $myteam_dt.api().row( tr );
             var idx = $.inArray( tr.attr('id'), detailRows );
      
             if ( row.child.isShown() ) {
                 tr.removeClass( 'details' );
                 row.child.hide();
      
                 // Remove from the 'open' array
                 detailRows.splice( idx, 1 );
             }
             else {
                 tr.addClass( 'details' );
                 row.child( format( row.data() ) ).show();
      
                 // Add to the 'open' array
                 if ( idx === -1 ) {
                     detailRows.push( tr.attr('id') );
                 }
             }
         } );
     
         // On each draw, loop over the `detailRows` array and show any child rows
    	 $myteam_dt.on( 'draw', function () {
    	        $.each( detailRows, function ( i, id ) {
    	        	$('#'+id+' td.details-control').trigger( 'click' );
    	        } );
    	    } );
    };

    //formatting for hidden row details (reason)
    function format ( d ) {
    	 return '  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reason: &nbsp;&nbsp'+d.reason+'<br>';
    }
   
    // DataTables Bootstrap integration
    var bsDataTables = function() {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend( true, $DataTable.defaults, {
            dom:
                "<'row'<'col-sm-6'l><'col-sm-6 form-group' f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            renderer: 'bootstrap',
            oLanguage: {
                sLengthMenu: "_MENU_",
                sInfo: "Showing <strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
                oPaginate: {
                    sPrevious: '<i class="fa fa-angle-left"></i>',
                    sNext: '<i class="fa fa-angle-right"></i>'
                }
            }
        });

        // Default class modification
        jQuery.extend($DataTable.ext.classes, {
            sWrapper: "dataTables_wrapper form-inline dt-bootstrap",
            sFilterInput: "form-control",
            sLengthSelect: "form-control"
        });

        // Bootstrap paging button renderer
        $DataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
            var api     = new $DataTable.Api(settings);
            var classes = settings.oClasses;
            var lang    = settings.oLanguage.oPaginate;
            var btnDisplay, btnClass;

            var attach = function (container, buttons) {
                var i, ien, node, button;
                var clickHandler = function (e) {
                    e.preventDefault();
                    if (!jQuery(e.currentTarget).hasClass('disabled')) {
                        api.page(e.data.action).draw(false);
                    }
                };

                for (i = 0, ien = buttons.length; i < ien; i++) {
                    button = buttons[i];

                    if (jQuery.isArray(button)) {
                        attach(container, button);
                    }
                    else {
                        btnDisplay = '';
                        btnClass = '';

                        switch (button) {
                            case 'ellipsis':
                                btnDisplay = '&hellip;';
                                btnClass = 'disabled';
                                break;

                            case 'first':
                                btnDisplay = lang.sFirst;
                                btnClass = button + (page > 0 ? '' : ' disabled');
                                break;

                            case 'previous':
                                btnDisplay = lang.sPrevious;
                                btnClass = button + (page > 0 ? '' : ' disabled');
                                break;

                            case 'next':
                                btnDisplay = lang.sNext;
                                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                break;

                            case 'last':
                                btnDisplay = lang.sLast;
                                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                break;

                            default:
                                btnDisplay = button + 1;
                                btnClass = page === button ?
                                        'active' : '';
                                break;
                        }

                        if (btnDisplay) {
                            node = jQuery('<li>', {
                                'class': classes.sPageButton + ' ' + btnClass,
                                'aria-controls': settings.sTableId,
                                'tabindex': settings.iTabIndex,
                                'id': idx === 0 && typeof button === 'string' ?
                                        settings.sTableId + '_' + button :
                                        null
                            })
                            .append(jQuery('<a>', {
                                    'href': '#'
                                })
                                .html(btnDisplay)
                            )
                            .appendTo(container);

                            settings.oApi._fnBindAction(
                                node, {action: button}, clickHandler
                            );
                        }
                    }
                }
            };

            attach(
                jQuery(host).empty().html('<ul class="pagination"/>').children('ul'),
                buttons
            );
        };

        // TableTools Bootstrap compatibility - Required TableTools 2.1+
        if ($DataTable.TableTools) {
            // Set the classes that TableTools uses to something suitable for Bootstrap
            jQuery.extend(true, $DataTable.TableTools.classes, {
                "container": "DTTT btn-group",
                "buttons": {
                    "normal": "btn btn-default",
                    "disabled": "disabled"
                },
                "collection": {
                    "container": "DTTT_dropdown dropdown-menu",
                    "buttons": {
                        "normal": "",
                        "disabled": "disabled"
                    }
                },
                "print": {
                    "info": "DTTT_print_info"
                },
                "select": {
                    "row": "active"
                }
            });

            // Have the collection use a bootstrap compatible drop down
            jQuery.extend(true, $DataTable.TableTools.DEFAULTS.oTags, {
                "collection": {
                    "container": "ul",
                    "button": "li",
                    "liner": "a"
                }
            });
        }
    };

    return {
        init: function() {
            // Init Datatables
            bsDataTables();
            initAddressDataTable();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ datatables.init(); });







$('#approveReject-modal').on('shown.bs.modal', function (e) {
	
	$(e.target).find("input[name=id]").val($(e.relatedTarget).data("id"));
	var value = $(e.relatedTarget).data("value");
	var req=$(e.relatedTarget).data("reqid");
	var id=$(e.relatedTarget).data("id");
	$(e.target).find("input[name=value]").val($(e.relatedTarget).data("value"));
	$.ajax({
        type:"POST",
        cache: false,
		url: App.myUrl('leaveRequests/respond/p'),
        data:{value:value,req:req,id:id},
        beforeSend:function(jqXHR,settings){
        	App.blocks(".approve", 'state_loading');
        	
        	
        },
        complete:function(jqXHR,textStatus){
        	App.blocks(".approve", 'state_normal');
        },
        success: function(data){
        	$("#respond_form").html(data);
        	$("#remarks").focus();
        	respondFormValidate();
        	App.init();
} });
});






$("#approveRejectForm").validate({
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
          url: App.myUrl('leaveRequests/update/'),
          data:data,
          beforeSend:function(jqXHR,settings){
         	  $(e.target).button({loadingText: 'Loading...'}).button('loading');
          },
          complete:function(jqXHR,textStatus){
        	  $(e.target).button('reset');
          },
          error:function(jqXHR,textStatus,errorThrown ){
        	  $(".notifications").empty().html('<div class="alert alert-danger alert-dismissable">\
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>\
                      <p class="push-15">Can\'t Subscribe &nbsp;&nbsp;&nbsp;&nbsp;Error <a class="alert-link" href="javascript:void(0)">occured</a>!</p>\
                  </div>').show().fadeOut(3000);
          },
         success: function(data){
        	  if(isJson(data)==false)
        		swal({title:'Error', text:'Error Occurred,Please Try Again', type:'error'});
        	  else
        		  data = JSON.parse(data);
        	if(data[0]==true){
        		swal({title:'Success', text:successText, type:'success'},function() {
        			window.location.href = location.href;
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



/*withdraw, Cancell leave
$(document).on('click', '.approve,.reject', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    var value = $(this).data("value");
	  if(value=="A"){
		  confirmText = 'Do you want to withdraw this Leave?';
		  successText = 'Leave withdrawed successfully.';
		  remarks = "withdrawed by applicant";
	  }else{
		  confirmText = 'Do you want to Cancel this Leave?';
		  successText = 'Leave Cancelled successfully.';
		  remarks = "cancelled by applicant";
	  }
    var data={"id":id,"value":value,"remarks":"withdrawed"};
    // Show the user a swal confirmation window
    swal({
            title: "Are you sure!",
            text: confirmText,
            type: "input", 
            inputType:"text", 
            inputValue:"",
            inputClass:"reason",
            inputPlaceholder:"specify a reason",
            confirmButtonColor: '#14adc4',
            confirmButtonText: 'Approve',
            showCancelButton: true,
            closeOnConfirm: false
        },
        function() {
           $.ajax({
                type: "POST",
                url: App.myUrl('leaveRequests/update/'),
                data: data,
                beforeSend:function(jqXHR,settings){
   	        	 //App.blocks("#"+user_id, 'state_loading');
   	         },
   	         complete:function(jqXHR,textStatus){
   	        	 //App.blocks("#"+user_id, 'state_normal');
   	         },
                success: function(data){
              	  if(isJson(data)==false){
              		swal({title:'Error', text:'Error Occurred,Please Try Again', type:'error'});
              		}
              	  else
              		  data = JSON.parse(data);
              	if(data[0]==true){
              		swal({title:'Checked!', text:successText, type:'success'},function() {
             			 location.reload();
                   });
                 }else if(data[0]==false){
                	 swal({title:'Error', text:data["info"], type:'error'});
                  }}
 }); 
            });});*/