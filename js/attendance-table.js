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
		   
         $('#form_attendance_add').live('submit',function (e){
		 e.preventDefault();
		 
			$.ajax( {
                    type: "POST",
                    url: $(this).attr("action"),
                    cache: false,
                    data: $(this).serialize(),
					dataType: 'json',
					beforeSend: function(  ) {
					$('#loader').toggle('show');
  },
					success: function(data) {
						if(data.status == "success")
						{
						jQuery('#add-attendance').toggle('hide');
						jQuery('#table').show();
						jQuery('#loader').toggle('hide');
						var month_year = $("#month_year").val();
						$("#form_attendance_add")[0].reset();
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
					"mColumns": [0, 1,2,3,4,5,6,7,8],
					"sPdfOrientation": "landscape",
					"sPdfMessage": "Slab Details"
				},
                {
					"sExtends": "xls",
					"mColumns": [0, 1,2,3,4,5,6,7,8],
				}
             ],
			"sSwfPath": "assets/data-tables/copy_csv_xls_pdf.swf"
		
		}
				
            });
			
			jQuery('#attendance_table_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
           jQuery('#attendance_table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
						}
						else 	
						alert("Attendance Cannot be Added");
						}
                    
                } );
			});


            

            
            
			
}

    };

}();