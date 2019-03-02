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

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				jqTds[1].innerHTML = '<input type="text" class="form-control small" required value="' + aData[1] + '">';
                jqTds[2].innerHTML = '<input type="text" class="form-control small" required value="' + aData[2] + '">';
                jqTds[3].innerHTML = '<select class="form-control small"><option value="' + aData[3] + '">' + aData[3] + '</option><option value="Housing">Housing</option><option value="Personal">Personal</option><option value="Car">Car</option><option value="Two Wheeler">Two Wheeler</option><option value="Education Loan">Education Loan</option></select>';
                jqTds[4].innerHTML = '<input type="text" class="form-control small" required value="' + aData[4] + '">';
                jqTds[5].innerHTML = '<input type="text" class="form-control small" required value="' + aData[5] + '">';
              
                jqTds[6].innerHTML = '<a class="edit" href="">Save</a>';
				jqTds[7].innerHTML = '<a class="cancel" href="">Cancel</a>';
	     }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input,select', nRow);
				oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
				oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 3, false);
               oTable.fnUpdate(jqInputs[3].value, nRow, 4, false);
               oTable.fnUpdate(jqInputs[4].value, nRow, 5, false);
               oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 7, false);
			}

            

          
		var oTable =$('#editable-sample').dataTable({
		"aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
				
                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
				
				"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "../php/loan-view.php",
		"sServerMethod": "POST",
	  
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
				"aoColumns": [           
                        { "sName": 'loan_id'},
                        { "sName": 'loan_name' },
						{ "sName": 'alias_name' },
                        { "sName": 'loan_category' },
                        { "sName": 'interest_rate' },
                         { "sName": 'sbi_interest_rate' },
                        { "sName": 'enabled',"bSortable": false },
                        { "bSortable": false }
                    ],
					"oTableTools": {
            "aButtons": [
                {
					"sExtends": "pdf",
					"mColumns": [0,1,2,3,4,5,6,7],
					"sPdfOrientation": "landscape",
					"sPdfMessage": "Payment Mode Details"
				},
                {
					"sExtends": "xls",
					"mColumns": [0,1,2,3,4,5,6,7]
				}
             ],
			"sSwfPath": "../assets/data-tables/copy_csv_xls_pdf.swf"
		
		}
				
            });
			

           $('#editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#editable-sample_processing').css('text-align','center');
            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            $('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            $('#searchInput').on('keyup', function (e) {
                if (e.keyCode == 13) {
                    oTable.fnFilter($(this).val());
                } else if (e.keyCode == 27) {
                    $(this).parent().parent().find('input').val("");
                    oTable.fnFilter("");
                }
            });
            $('#searchFilter').on('click', function () {
                oTable.fnFilter($(this).parent().parent().find('input').val());
            });
            $('#searchClear').on('click', function () {
                $(this).parent().parent().find('input').val("");
                oTable.fnFilter("");
            });

           var nEditing = null;
		   
		   // for adding new designation
	 
         $('#add').on('click',function (e){
		 e.preventDefault();
			if($("#loan_name").val() == '' || $("#alias_name").val() == '' || $("#loan_category").val() == '' ||
            $("#interest_rate").val() == '' ||$("#sbi_interest_rate").val() == '' )
			{ alert("Enter All Required Fields");}
			else{
				$.ajax( {
						dataType: 'html',
						type: "POST",
						url: "../php/loan-add.php",
						cache: false,
						data: $('#loanAddForm').serialize(),
						success: function(data) {
                        data1 = JSON.parse(data);
                        if (data1[0] == "success") {
                            $('.close').click();
                           jQuery('#add-loan').toggle('hide');
							$('#loanAddForm')[0].reset();
                             alert(data1[1]);
							oTable.fnDraw();
                }
                        else
                            if (data1[0] == "error") {
                                alert(data1[1]);
                            }
							}
						
					} );
			}
			});


            $('#editable-sample a.delete').on('click', function (e) {
                e.preventDefault();
				var nRow = $(this).parents('tr')[0];
				var data = oTable.fnGetData( nRow );
                if (confirm("Delete Designation "+data[1]+" ?") == false) {
                    return;
                }else{
					$.ajax( {
						dataType: 'html',
						type: "POST",
						url: "../php/designation-delete.php",
						cache: false,
						data: {designationId:data[0]},
						success: function(data) {
							if(data == "success")
							{
							alert("Designation Deleted");
							oTable.fnDraw();
							}
							else 	
							alert("Error on query");
							}
					} );
				}
				
				
            });

            $('#editable-sample a.cancel').on('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });
            $('#editable-sample a.enable').on('click', function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Enable Loan " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/loan-action.php",
                        cache: false,
                        data: { action:'enable',loanId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Loan Disabled");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });

            $('#editable-sample a.disable').on('click', function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Disable Loan " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/loan-action.php",
                        cache: false,
                        data: { action:'disable',loanId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Loan Enabled");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });
            $('#editable-sample a.edit').on('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Save") {
					
                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
					nEditing = null;
					var data = oTable.fnGetData( nRow );
					if(data[1] == ''||data[2] == ''||data[3] == ''||data[4] == '' ||data[5] == '')
						{ alert("Enter Required Fields");
							editRow(oTable, nRow);
							nEditing = nRow;
						}
						else{
							$.ajax( {
								dataType: 'html',
								type: "POST",
								url: "../php/loan-update.php",
								cache: false,
								data: {lId:data[0],loan_name:data[1],alias_name:data[2],loan_category:data[3],
                                interest_rate:data[4],sbi_interest_rate:data[5]},
								success: function(data) {
                                      data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                alert(data1[1]);
                                oTable.fnDraw();

                            }
                            else
                                if (data1[0] == "error") {
                                    alert(data1[1]);
                                }

									
								}
								
							} );
						}
				
				} else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
 }

    };

}();