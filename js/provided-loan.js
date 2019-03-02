var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {

            var oTable = $('#editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "php/provideloan-view.php",
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
                "aoColumns": [                           //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
                        {"sName": "employee_id" },
                        { "sName": "loan_name" },
                        { "sName": "loan_amount" },
						{ "sName": "no_installment" },
						{ "sName": "emi_amount" },
						{ "sName": "issued_on" },
                        { "sName": "starts_from" },
						{ "sName": "closing_balance" },
						{ "sName": "closing_date" },
                       { "sName": "remaining_installments" },
                        { "sName": "company_loan_mapping.enabled" },
						{ "bSortable": false }
                    ],
                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Branch Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
             ],
                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

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
            $('#editable-sample_processing').css('text-align', 'center');
            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
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

            // for adding new branch

            $('#providedloan-add').on('click', function (e) {
                e.preventDefault();
                if ($("#employee_id").val() == '' || $("#loan_id").val() == '' || $("#loan_amount").val() == '' || $("#issued_on").val() == '' || $("#starts_from").val() == '' || $("#no_installment").val() == '' || $("#emi_amount").val() == '' || $("#closing_date").val() == '')
                { alert("Enter all the required Fields"); }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/provideloan-add.php",
                        cache: false,
                        data: $('#provideformAddForm').serialize(),
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                jQuery('#add-provideloan-toggle').toggle('hide');
                                $('#provideformAddForm')[0].reset();
                                alert(data1[1]);
                                oTable.fnDraw();

                            }
                            else
                                if (data1[0] == "error") {
                                    alert(data1[1]);
                                }
                        }

                    });
                }
            });

            $(document).on('click', "#editable-sample a.disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Disable Branch " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/branch-action.php",
                        cache: false,
                        data: { action: 'disable', branchId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Branch Disabled");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });


            $(document).on('click', "#editable-sample a.enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Enable Branch " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/branch-action.php",
                        cache: false,
                        data: { action: 'enable', branchId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Branch Enabled");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });


            $(document).on('click', "#editable-sample a.delete", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Delete branch " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/branch-delete.php",
                        cache: false,
                        data: { branchId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Branch Deleted");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }
    });

            $(document).on('click', "#editable-sample a.edit", function (e) {
                alert("still in proces");
                e.preventDefault();
                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                $('#branchId').val(data[0]);
                $('#edit_bname').val(data[1]);
                $('#edit_bloc').val(data[2]);
                $('#edit_bpin').val(data[3]);
                $('#edit_bcity').val(data[4]);
                $('#edit_bstate').val(data[5]);
                $('#edit_pt_slab').val(data[6]);
                $('#edit_bpf').val(data[9]);
                $('#edit_besi').val(data[8]);
                $('#edit_bpt').val(data[7]);
                $('#edit_btan').val(data[10]);

            });

            $('#editBranchForm').on('submit', function (e) {
                e.preventDefault();
                if ($("#edit_bname").val() == '' || $("#edit_bloc").val() == '' || $("#edit_bcity").val() == '' || $("#edit_bstate").val() == '' || $("#edit_bpin").val() == '' || $("#edit_bpf").val() == '' || $("#edit_besi").val() == '' || $("#edit_bpt").val() == '' || $("#edit_btan").val() == '')
                { alert("Enter all the required Fields"); }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/branch-update.php",
                        cache: false,
                        data: $(this).serialize(),
                        success: function (data) {
                            if (data == "success") {
                                $('#edit_branch').modal('hide');
                                $('#editBranchForm')[0].reset();
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }

                    });
                }
            });

        }

    };

} ();