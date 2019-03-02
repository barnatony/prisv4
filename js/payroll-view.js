var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
				for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }
				oTable.fnDraw();
            }

            
          
		
		
			

           

           var nEditing = null;
		   
		   // for adding new department
		   
         $('#month_yr_ip').live('submit',function (e){
		 e.preventDefault();
		 
						jQuery('#table').show();
						var month_year = $("#month_year").val();
						$('#month_yr').val(month_year);
						
		var oTable =$('#attendance_table').dataTable({
		"aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
				
                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
				
				"bProcessing": true,
		"bServerSide": true,
		"bDestroy": true,
		"sAjaxSource": "script/attendance-view.php",
		"sServerMethod": "POST",
		"fnServerParams": function ( aoData ) {
      aoData.push( { "name": "month_year", "value": month_year } );
    },		
			
		"aoColumns": [
            { "sName": "emp_id" },
            { "sName": "emp_name" },
            { "sName": "leave_availed" },
			{ "sName": "lop" },
			{ "sName": "ot" },
			{ "sName": "holiday_wages" },
			{ "sName": "incentives" },
			{ "sName": "other_deduc" },
			
            {
                "mData": null,
                "sClass": "center",
                "sDefaultContent": '<a href="#edit_attendance" data-toggle="modal" class="edit" href="">Edit</a>'
            }
        ],	
         "sPaginationType": "bootstrap",
         "oLanguage": {
         "sLengthMenu": "_MENU_ records per page",
          "oPaginate": {
          "sPrevious": "Prev",
          "sNext": "Next"
                    }
                },
				"oColReorder": {
                        "iFixedColumns": 1
                    },
				
					"oTableTools": {
            "aButtons": [
                {
					"sExtends": "pdf",
					"mColumns": [0, 1,2,3,4,5,6,7],
					"sPdfOrientation": "landscape",
					"sPdfMessage": "Attendance Details"
				},
                {
					"sExtends": "xls",
					"mColumns": [0, 1,2,3,4,5,6,7],
				}
             ],
			"sSwfPath": "assets/data-tables/copy_csv_xls_pdf.swf"
		
		}
				
            });
			
			jQuery('#attendance_table_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
           jQuery('#attendance_table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
			

			
			$('#attendance_table a.edit').live('click', function (e) {
                e.preventDefault();
				$  
                var nTr = $(this).parents('tr')[0];
				var aData = oTable.fnGetData( nTr );
                /* Get the row as a parent of the link that was clicked on */
               var jqTds = $('>td', nTr);
			   //alert(jqTds[0].innerText);
			   $('#emp_id').val(jqTds[0].innerText);
			   $('#emp_name').val(jqTds[1].innerText);
			   $('#leave').val(jqTds[2].innerText);
			   $('#lop').val(jqTds[3].innerText);
			   $('#over_time').val(jqTds[4].innerText);
			   $('#holiday_wages').val(jqTds[5].innerText);
			   $('#incentives').val(jqTds[6].innerText);
			   $('#other_deduc').val(jqTds[7].innerText);
            });
			
		$('#form_attendance_edit').on('submit',function(e) {
		e.preventDefault();
		jQuery.ajax( {
                    type: "POST",
                    url: $(this).attr("action"),
                    cache: false,
                    data: $(this).serialize(),
					success: function(data) {
						if(data == "success")
						{$('.close').click()
						alert("Updated");
						oTable.fnDraw();
						}
						else 	
						alert("Duplicate Entry for Attendance");
						}
                    
                } );	
			
		});	
			
			
			});



         
			
			
			
 }

    };

}();