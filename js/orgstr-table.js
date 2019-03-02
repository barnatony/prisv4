var branch_EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {

            var oTable = $('#branch_editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../php/branch-view.php",
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
                        {"sName": "b.branch_id" },
                        { "sName": "b.branch_name" },
                        { "sName": "b.branch_addr" },
						{ "sName": "b.city_pin" },
						{ "sName": "p.districtname" },
						{ "sName": "p.statename" },
						{ "sName": "b.pt_city_id" },
						{ "bSortable": false },
						{ "bSortable": false },
						{ "bSortable": false },
                        { "bSortable": false },
                        { "sName": "b.enabled" },


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
                    "sSwfPath": "../assets/data-tables/copy_csv_xls_pdf.swf"

                }

            });

            $('#branch_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="branch_searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="branch_searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="branch_searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#branch_editable-sample_processing').css('text-align', 'center');
            //jQuery('#branch_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            jQuery('#branch_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            $('#branch_searchInput').on('keyup', function (e) {
                if (e.keyCode == 13) {
                    oTable.fnFilter($(this).val());
                } else if (e.keyCode == 27) {
                    $(this).parent().parent().find('input').val("");
                    oTable.fnFilter("");
                }
            });

            $(document).on('click', "#branch_searchFilter", function () {
                oTable.fnFilter($(this).parent().parent().find('input').val());
            });
            $(document).on('click', "#branch_searchClear", function () {
                $(this).parent().parent().find('input').val("");
                oTable.fnFilter("");
            });
            var nEditing = null;

            // for adding new branch
            $(document).on('click', "#branch_add", function (e) {
                e.preventDefault();
                if ($("#bname").val() == '' || $("#bloc").val() == '' || $("#bcity").val() == '' || $("#bstate").val() == '' || $("#bpin").val() == '' || $("#bpf").val() == '' || $("#besi").val() == '' || $("#bpt").val() == '' || $("#btan").val() == '') {
                    $('.model_msg0').click();

                }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/branch-add.php",
                        cache: false,
                        data: $('#branchAddForm').serialize(),
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                jQuery('#add-branch').toggle('hide');
                                $('#branchAddForm')[0].reset();
                                $('.model_msg1').click();
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



            $(document).on('click', "#branch_editable-sample a.branch_disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var branch_id = data[0];
                var branch_name = data[1];
                $('.model_msg3').click();
                $('#branch_id').val(branch_id);
                $('#branch_name').html(branch_name);
            });

            $(document).on('click', ".branch_disable_form", function (e) {
                e.preventDefault();
                branch_id = $('#branch_id').val();
                $('.close').click();
                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "../php/branch-action.php",
                    cache: false,
                    data: { action: 'disable', branchId: branch_id },
                    success: function (data) {
                        if (data == "success") {
                            $('.model_msg6').click();
                            oTable.fnDraw();
                        }
                        else
                            alert("Error on query");
                    }
                });

            });

            $(document).on('click', "#branch_editable-sample a.branch_enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var branch_id = data[0];
                var branch_name = data[1];
                $('.model_msg4').click();
                $('#e_branch_id').val(branch_id);
                $('#e_branch_name').html(branch_name);
            });


            $(document).on('click', ".branch_enable_form", function (e) {
                e.preventDefault();
                e_branch_id = $('#e_branch_id').val();
                console.log(e_branch_id);
                $('.close').click();
                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "../php/branch-action.php",
                    cache: false,
                    data: { action: 'enable', branchId: e_branch_id },
                    success: function (data) {
                        if (data == "success") {
                              $('.model_msg5').click();
                              oTable.fnDraw();
                        }
                        else
                            alert("Error on query");
                    }
                });

            });


            $(document).on('click', "#branch_editable-sample a.delete", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Delete branch " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/branch-delete.php",
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

            $(document).on('click', "#branch_editable-sample a.branch_edit", function (e) {
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
                if ($("#edit_bname").val() == '' || $("#edit_bloc").val() == '' || $("#edit_bcity").val() == '' || $("#edit_bstate").val() == '' || $("#edit_bpin").val() == '' || $("#edit_bpf").val() == '' || $("#edit_besi").val() == '' || $("#edit_bpt").val() == '' || $("#edit_btan").val() == '') {
                    $('.model_msg0').click();

                }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/branch-update.php",
                        cache: false,
                        data: $(this).serialize(),
                        success: function (data) {

                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                $('.close').click();
                                $('#edit_branch').modal('hide');
                                $('#editBranchForm')[0].reset();
                                $('.model_msg2').click();
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

        }

    };

} ();

var payment_EditableTable = function () {

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
                jqTds[2].innerHTML = '<select class="form-control  small" ><option value="' + aData[2] + '">' + aData[2] + '</option><option value="Current">Current</option><option value="Cash Credit">Cash Credit</option><option value="Cash">Cash</option></select>';
                jqTds[3].innerHTML = '<input type="text" class="form-control small" required value="' + aData[3] + '">';
                jqTds[4].innerHTML = '<input type="text" class="form-control small" required value="' + aData[4] + '">';
                jqTds[5].innerHTML = '<input type="text" class="form-control small" required value="' + aData[5] + '">';
                jqTds[6].innerHTML = '<input type="text" class="form-control small" required value="' + aData[6] + '">';
                jqTds[7].innerHTML = '<a class="pay_edit" href="" data-actions="Save"  title="Save" ><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>&nbsp;<a class="pay_cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs" ><i class="fa fa-times"></i></button></a>';
               }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input,select', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[5].value, nRow, 6, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 3, false);
                oTable.fnUpdate(jqInputs[3].value, nRow, 4, false);
                oTable.fnUpdate(jqInputs[4].value, nRow, 5, false);
                //  oTable.fnUpdate(jqInputs[6].value, nRow, 7, false);

                oTable.fnUpdate('<a class="pay_edit" href="" data-actions="Save"  title="Save"><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>', nRow, 7, false);
            }




            var oTable = $('#payment_editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../php/paymentModes-view.php",
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
                        { "sName": 'payment_mode_id' },
                        { "sName": 'payment_mode_name' },
                         { "sName": 'account_type' },
						{ "sName": 'bank_name' },
                        { "sName": 'bank_ac_no' },
                        { "sName": 'bank_branch' },
                        { "sName": 'bank_ifsc' },
                        { "bSortable": false }
                    ],
                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Payment Mode Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
                }
             ],
                    "sSwfPath": "../assets/data-tables/copy_csv_xls_pdf.swf"

                }

            });


            $('#payment_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="payment_searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="payment_searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="payment_searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#payment_editable-sample_processing').css('text-align', 'center');
            //jQuery('#payment_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            $('#payment_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            $('#payment_searchInput').on('keyup', function (e) {
                if (e.keyCode == 13) {
                    oTable.fnFilter($(this).val());
                } else if (e.keyCode == 27) {
                    $(this).parent().parent().find('input').val("");
                    oTable.fnFilter("");
                }
            });

            $(document).on('click', "#payment_searchFilter", function () {
                oTable.fnFilter($(this).parent().parent().find('input').val());
            });
            $(document).on('click', "#payment_searchClear", function () {
                $(this).parent().parent().find('input').val("");
                oTable.fnFilter("");
            });

            var nEditing = null;

            // for adding new designation
            $(document).on('click', "#payment_mobe_add", function (e) {
                e.preventDefault();
                if ($("#name").val() == '')
                { 
                $('.p_model_msg0').click();

                }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/paymentMode-add.php",
                        cache: false,
                        data: $('#paymentModeAddForm').serialize(),
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                $('.close').click();
                                jQuery('#add-paymentMode').toggle('hide');
                                $('#paymentModeAddForm')[0].reset();
                                 $('.p_model_msg1').click();
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


            $(document).on('click', "#payment_editable-sample a.pay_delete", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Delete Designation " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/designation-delete.php",
                        cache: false,
                        data: { designationId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Designation Deleted");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });

            $(document).on('click', "#payment_editable-sample a.pay_cancel", function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });


            $(document).on('click', "#payment_editable-sample a.pay_enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var e_pay_id = data[0];
                var e_pay_name = data[1];
                $('.p_model_msg4').click();
                $('#e_pay_id').val(e_pay_id);
                $('#e_pay_name').html(e_pay_name);
            });


            $(document).on('click', ".pay_enable_form", function (e) {
                e.preventDefault();
                e_pay_id = $('#e_pay_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/paymentMode-action.php",
                        cache: false,
                        data: { action: 'enable', modeId: e_pay_id},
                        success: function (data) {
                            if (data == "success") {
                                $('.p_model_msg5').click();
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
              
            });


          $(document).on('click', "#payment_editable-sample a.pay_disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var pay_id = data[0];
                var pay_name = data[1];
                $('.p_model_msg3').click();
                $('#pay_id').val(pay_id);
                $('#pay_name').html(pay_name);
            });

            $(document).on('click', ".pay_disable_form", function (e) {
                e.preventDefault();
                pay_id = $('#pay_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/paymentMode-action.php",
                        cache: false,
                        data: { action: 'disable', modeId: pay_id },
                        success: function (data) {
                            if (data == "success") {
                               $('.p_model_msg6').click();
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
               });

            $(document).on('click', "#payment_editable-sample a.pay_edit", function (e) {
                e.preventDefault();
                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && $(this).data('actions')== "Save") {

                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                    var data = oTable.fnGetData(nRow);
                    if (data[2] == '' || data[5] ==''|| data[4] ==''|| data[3] ==''|| data[6] ==''|| data[1] =='' ) {
                        alert("Enter Required Fields");
                        editRow(oTable, nRow);
                        nEditing = nRow;
                    }
                    else {
                        $.ajax({
                            dataType: 'html',
                            type: "POST",
                            url: "../php/paymentMode-update.php",
                            cache: false,
                            data: { pId: data[0], payment_mode_name: data[1], bank_name: data[3], bank_ac_no: data[4],
                                bank_branch: data[5], bank_ifsc: data[6], account_type: data[2]
                            },
                            success: function (data) {
                                data1 = JSON.parse(data);
                                if (data1[0] == "success") {
                                    $('.p_model_msg2').click();
                                    oTable.fnDraw();

                                }
                                else
                                    if (data1[0] == "error") {
                                        alert(data1[1]);
                                    }


                            }

                        });
                    }

                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

} ();

var job_EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }
                //oTable.fnDraw();
            }

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                jqTds[1].innerHTML = '<input type="text" class="form-control small" required value="' + aData[1] + '">';
                jqTds[2].innerHTML = '<textarea type="text" class="form-control small" required>' + aData[2] + '</textarea>';
                jqTds[3].innerHTML = '<a class="job_edit" href=""data-actions="Save"  title="Save"><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>&nbsp;<a class="job_cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs" ><i class="fa fa-times"></i></button></a>';

            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input,textarea', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
                oTable.fnUpdate('<a class="job_edit"  href="" data-actions="Save" title="Save" ><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>', nRow, 3, false);
            }



            var oTable = $('#job_status_editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../php/jobStatus-view.php",
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
                        { "sName": "status_id" },
                        { "sName": "status_name" },
						{ "sName": "status_comments" },
                        { "bSortable": false }
                    ],
                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [0, 1, 2],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Job Status Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [0, 1, 2]
                }
             ],
                    "sSwfPath": "../assets/data-tables/copy_csv_xls_pdf.swf"

                }

            });


            $('#job_status_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="job_searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="job_searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="job_searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#job_status_editable-sample_processing').css('text-align', 'center');
            //jQuery('#job_status_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            $('#job_status_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            $('#job_searchInput').on('keyup', function (e) {
                if (e.keyCode == 13) {
                    oTable.fnFilter($(this).val());
                } else if (e.keyCode == 27) {
                    $(this).parent().parent().find('input').val("");
                    oTable.fnFilter("");
                }
            });

            $(document).on('click', "#job_searchFilter", function () {
                oTable.fnFilter($(this).parent().parent().find('input').val());
            });
            $(document).on('click', "#job_searchClear", function () {
                $(this).parent().parent().find('input').val("");
                oTable.fnFilter("");
            });

            var nEditing = null;

            // for adding new designation
            $(document).on('click', "#job_status_add", function (e) {
                e.preventDefault();
                if ($("#sname").val() == '' && $("#scmts").val() == '')
                { $('.js_model_msg0').click(); }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/jobStatus-add.php",
                        cache: false,
                        data: $('#statusAddForm').serialize(),
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                jQuery('#add-status').toggle('hide');
                                $('#statusAddForm')[0].reset();
                                $('.js_model_msg1').click();
                                oTable.fnDraw();

                            }
                            else {
                                if (data1[0] == "error") {
                                    alert(data1[1]);
                                }


                            }

                        }

                    });
                }
            });



            $(document).on('click', "#job_status_editable-sample a.job_disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var js_id = data[0];
                var js_name = data[1];
                $('.js_model_msg3').click();
                $('#js_id').val(js_id);
                $('#js_name').html(js_name);
            });

            $(document).on('click', ".job_disable_form", function (e) {
                e.preventDefault();
                js_id = $('#js_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/jobStatus-action.php",
                        cache: false,
                        data: { action: 'disable', statusId: js_id },
                        success: function (data) {
                            if (data == "success") {
                              $('.js_model_msg6').click();
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
              });

                $(document).on('click', "#job_status_editable-sample a.job_enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var e_js_id = data[0];
                var e_js_name = data[1];
                $('.js_model_msg4').click();
                $('#e_js_id').val(e_js_id);
                $('#e_js_name').html(e_js_name);
            });


            $(document).on('click', ".job_enable_form", function (e) {
                e.preventDefault();
                e_js_id = $('#e_js_id').val();
                $('.close').click();
               $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/jobStatus-action.php",
                        cache: false,
                        data: { action: 'enable', statusId: e_js_id },
                        success: function (data) {
                            if (data == "success") {
                                 $('.js_model_msg5').click();
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
               });

            $(document).on('click', "#job_status_editable-sample a.job_delete", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Delete Status " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/jobStatus-delete.php",
                        cache: false,
                        data: { statusId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Status Deleted");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });


            $(document).on('click', "#job_status_editable-sample a.job_cancel", function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });


            $(document).on('click', "#job_status_editable-sample a.job_edit", function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && $(this).data('actions') == "Save") {

                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                    var data = oTable.fnGetData(nRow);
                    if (data[1] == '' || data[2] == '') {
                        alert("Enter Required Fields");
                        editRow(oTable, nRow);
                        nEditing = nRow;
                    }
                    else {
                        $.ajax({
                            dataType: 'html',
                            type: "POST",
                            url: "../php/jobStatus-update.php",
                            cache: false,
                            data: { statusId: data[0], sname: data[1], scmts: data[2] },
                            success: function (data) {
                                data1 = JSON.parse(data);
                                if (data1[0] == "success") {
                                    $('.js_model_msg2').click();
                                    oTable.fnDraw();

                                }
                                else {
                                    if (data1[0] == "error") {
                                        alert(data1[1]);
                                    }


                                }
                            }

                        });
                    }

                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

} ();



var desig_EditableTable = function () {

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
                jqTds[3].innerHTML = '<a class="designation_edit" href=""  title="Save" data-actions="Save" ><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>&nbsp;<a class="designation_cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs" ><i class="fa fa-times"></i></button></a>';
               }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
                oTable.fnUpdate('<a class="designation_edit" href="" title="Save"><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>', nRow, 3, false);
                    }


            var oTable = $('#designation_editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../php/designation-view.php",
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
                        { "sName": "designation_id" },
                        { "sName": "designation_name" },
						{ "sName": "designation_hierarchy" },
                        { "bSortable": false }
                    ],
                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [0, 1, 2],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Designation Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [0, 1, 2]
                }
             ],
                    "sSwfPath": "../assets/data-tables/copy_csv_xls_pdf.swf"

                }

            });


            $('#designation_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="desi_searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="desi_searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="desi_searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#designation_editable-sample_processing').css('text-align', 'center');
            //jQuery('#designation_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            $('#designation_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            $('#desi_searchInput').on('keyup', function (e) {
                if (e.keyCode == 13) {
                    oTable.fnFilter($(this).val());
                } else if (e.keyCode == 27) {
                    $(this).parent().parent().find('input').val("");
                    oTable.fnFilter("");
                }
            });
            $(document).on('click', "#desi_searchFilter", function () {
                oTable.fnFilter($(this).parent().parent().find('input').val());
            });
            $(document).on('click', "#desi_searchClear", function () {
                $(this).parent().parent().find('input').val("");
                oTable.fnFilter("");
            });

            var nEditing = null;

            // for adding new designation
            $(document).on('click', "#designation_add", function (e) {
                e.preventDefault();
                if ($("#dname").val() == '' && $("#dhie").val() == '')
                {
                      $('.f_model_msg0').click();
                     
               }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/designation-add.php",
                        cache: false,
                        data: $('#designationAddForm').serialize(),
                        success: function (data) {


                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                jQuery('#add-designation').toggle('hide');
                                $('#designationAddForm')[0].reset();
                               $('.f_model_msg1').click();
                                oTable.fnDraw();

                            }
                            else {
                                if (data1[0] == "error") {
                                    alert(data1[1]);
                                }
                            }

                        }

                    });
                }
            });
            $(document).on('click', "#designation_editable-sample a.designation_disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var design_id = data[0];
                var design_name = data[1];
                $('.f_model_msg3').click();
                $('#design_id').val(design_id);
                $('#design_name').html(design_name);
            });



            $(document).on('click', ".designation_disable_form", function (e) {
                e.preventDefault();
                design_id = $('#design_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/designation-action.php",
                        cache: false,
                        data: { action: 'disable', desigId:design_id },
                        success: function (data) {
                            if (data == "success") {
                                 $('.f_model_msg6').click();
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
              
               });

                $(document).on('click', "#designation_editable-sample a.designation_enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var e_design_id = data[0];
                var e_design_name = data[1];
                $('.f_model_msg4').click();
                $('#e_design_id').val(e_design_id);
                $('#e_design_name').html(e_design_name);
            });


            $(document).on('click', ".designation_enable_form", function (e) {
                e.preventDefault();
                e_design_id = $('#e_design_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/designation-action.php",
                        cache: false,
                        data: { action: 'enable', desigId:e_design_id },
                        success: function (data) {
                            if (data == "success") {
                                $('.f_model_msg5').click();
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
               });

            $(document).on('click', "#designation_editable-sample a.designation_delete", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Delete Designation " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/designation-delete.php",
                        cache: false,
                        data: { designationId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Designation Deleted");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });

            $(document).on('click', "#designation_editable-sample a.designation_cancel", function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });


            $(document).on('click', "#designation_editable-sample a.designation_edit", function (e) {
                e.preventDefault();
                 /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && $(this).data('actions')== "Save") {

                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                    var data = oTable.fnGetData(nRow);
                    if (data[1] == '' || data[2] == '') {
                        $('.f_model_msg0').click();
                        editRow(oTable, nRow);
                        nEditing = nRow;
                    }
                    else {
                        $.ajax({
                            dataType: 'html',
                            type: "POST",
                            url: "../php/designation-update.php",
                            cache: false,
                            data: { designationId: data[0], dname: data[1], dhie: data[2] },
                            success: function (data) {
                                data1 = JSON.parse(data);
                                if (data1[0] == "success") {

                                   $('.f_model_msg2').click();
                                    oTable.fnDraw();

                                }
                                else {
                                    if (data1[0] == "error") {
                                        alert(data1[1]);
                                    }
                                }
                            }
                        });
                    }

                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

} ();


var depart_EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(depart_oTable, nRow) {
                var aData = depart_oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    depart_oTable.fnUpdate(aData[i], nRow, i, false);
                }
                depart_oTable.fnDraw();
            }

            function editRow(depart_oTable, nRow) {
                var aData = depart_oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                jqTds[1].innerHTML = '<input type="text" class="form-control small" required value="' + aData[1] + '">';
                jqTds[2].innerHTML = '<a class="department_edit" href="" title="Save" data-actions="Save" ><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>&nbsp;<a class="department_cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></a>';

            }

            function saveRow(depart_oTable, nRow) {
                var jqInputs = $('input', nRow);
                depart_oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
                depart_oTable.fnUpdate('<a class="department_edit" title="Save" href=""><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>', nRow, 2, false);
                //depart_oTable.fnUpdate('<a class="department_delete" href="">Delete</a>', nRow, 3, false);
            }

            //$('#department_editable-sample').remove();
            var depart_oTable = $('#department_editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../php/department-view.php",
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
                        { "sName": "department_id" },
                        { "sName": "department_name" },
                        { "sName": "enabled" },

                    ],
                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [0, 1, 2],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Designation Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [0, 1, 2]
                }
             ],
                    "sSwfPath": "../assets/data-tables/copy_csv_xls_pdf.swf"

                }

            });

            $('#department_editable-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="depart_searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="depart_searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="depart_searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#department_editable-sample_processing').css('text-align', 'center');
            //jQuery('#department_editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            $('#department_editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            $('#depart_searchInput').on('keyup', function (e) {
                if (e.keyCode == 13) {
                    depart_oTable.fnFilter($(this).val());
                } else if (e.keyCode == 27) {
                    $(this).parent().parent().find('input').val("");
                    depart_oTable.fnFilter("");
                }
            });

            $(document).on('click', "#depart_searchFilter", function () {
                depart_oTable.fnFilter($(this).parent().parent().find('input').val());
            });

            $(document).on('click', "#depart_searchClear", function () {
                $(this).parent().parent().find('input').val("");
                depart_oTable.fnFilter("");
            });

            var nEditing = null;

            // for adding new department

            $(document).on('click', "#department_add", function (e) {
                e.preventDefault();
                if ($("#dname_sub").val() == '')
                {  
                  $('.d_model_msg0').click();
                }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/department-add.php",
                        cache: false,
                        data: $('#departmentAddForm').serialize(),
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                jQuery('#add-department').toggle('hide');
                                $('#departmentAddForm')[0].reset();
                               $('.d_model_msg1').click();
                                depart_oTable.fnDraw();

                            }
                            else {
                                if (data1[0] == "error") {
                                    alert(data1[1]);
                                }


                            }
                        }

                    });
                }
            });


            $(document).on('click', "#department_editable-sample a.department_disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = depart_oTable.fnGetData(nRow);
                var depart_id = data[0];
                var depart_name = data[1];
                $('.d_model_msg3').click();
                $('#depart_id').val(depart_id);
                $('#depart_name').html(depart_name);
            });  


            $(document).on('click', ".department_disable_form", function (e) {
                e.preventDefault();
                depart_id = $('#depart_id').val();
                $('.close').click();
                  $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/department-action.php",
                        cache: false,
                        data: { action: 'disable', deptId: depart_id },
                        success: function (data) {
                            if (data == "success") {
                                $('.d_model_msg6').click();
                                depart_oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
               
            });

              $(document).on('click', "#department_editable-sample a.department_enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = depart_oTable.fnGetData(nRow);
                var e_depart_id = data[0];
                var e_depart_name = data[1];
                $('.d_model_msg4').click();
                $('#e_depart_id').val(e_depart_id);
                $('#e_depart_name').html(e_depart_name);
            });


            $(document).on('click', ".department_enable_form", function (e) {
                e.preventDefault();
                e_depart_id = $('#e_depart_id').val();
                $('.close').click();
                $.ajax({

                        dataType: 'html',
                        type: "POST",
                        url: "../php/department-action.php",
                        cache: false,
                        data: { action: 'enable', deptId: e_depart_id },
                        success: function (data) {
                            if (data == "success") {
                               $('.d_model_msg5').click();
                                depart_oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                });


            $(document).on('click', "#department_editable-sample a.department_delete", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = depart_oTable.fnGetData(nRow);
                if (confirm("Delete Department " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "../php/department-delete.php",
                        cache: false,
                        data: { departmentId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Department Deleted");
                                depart_oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }

            });


            $(document).on('click', "#department_editable-sample a.department_cancel", function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    depart_oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(depart_oTable, nEditing);
                    nEditing = null;
                }
            });


            $(document).on('click', "#department_editable-sample a.department_edit", function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(depart_oTable, nEditing);
                    editRow(depart_oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && $(this).data('actions') == "Save") {
                  
                    /* Editing this row and want to save it */
                    saveRow(depart_oTable, nEditing);
                    nEditing = null;
                    var data = depart_oTable.fnGetData(nRow);
                      if (data[1] == '') {
                        alert("Enter Department Name");
                        editRow(depart_oTable, nRow);
                        nEditing = nRow;
                    }
                    else {
                          console.log($(this).data('actions'));
                        $.ajax({
                            dataType: 'html',
                            type: "POST",
                            url: "../php/department-update.php",
                            cache: false,
                            data: { departmentId: data[0], dname: data[1] },
                            success: function (data) {
                                if (data == "success") {
                                   $('.d_model_msg2').click();
                                    depart_oTable.fnDraw();
                                }
                                else
                                    alert("Error on query");
                            }

                        });
                    }

                } else {
                    /* No edit in progress - let's start one */
                    editRow(depart_oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

} ();


