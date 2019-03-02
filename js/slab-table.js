var EditableTable = function () {
    return {

        //main function to initiate the module
        init: function (allowData) {

            var oTable = $('#slab-table').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "php/slab-view.php",
                "fnServerParams": function (aoData) {
                    aoData.push({ "name": "columns", "value": allowData['columns'] });
                },
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
                "aoColumns": allowData['aoColumns'],
                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Allowance Slab Details"
                },
                {
                    "sExtends": "xls"
                }
             ],
                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                }

            });


            $('#slab-table_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#slab-table_processing').css('text-align', 'center');
            //jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            $('#slab-table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
            $('#searchInput').on('keyup', function (e) {
                if (e.keyCode == 13) {
                    oTable.fnFilter($(this).val());
                } else if (e.keyCode == 27) {
                    $(this).parent().parent().find('input').val("");
                    oTable.fnFilter("");
                }
            });
            $(document).on("click", "#searchFilter", function () {
                oTable.fnFilter($(this).parent().parent().find('input').val());
            });
            $(document).on("click", "#searchClear", function () {
                $(this).parent().parent().find('input').val("");
                oTable.fnFilter("");
            });

            var nEditing = null;




            $(document).on('click', "#slab-table a.disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var s_id = data[0];
                var s_name = data[1];
                $('.model_msg2').click();
                $('#s_id').val(s_id);
                $('#s_name').html(s_name);
            });

            $(document).on('click', ".slab_disable_form", function (e) {
                e.preventDefault();
                s_id = $('#s_id').val();
                $('.close').click();
                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/slab-action.php",
                    cache: false,
                    data: { action: 'disable', slabId: s_id },
                    success: function (data) {
                        if (data == "success") {
                            $('.model_msg0').click();
                            $('#slab_msg').html('Allowance Slab Disabled Successfully');
                            oTable.fnDraw();
                        }
                        else
                            alert("Error on query");
                    }
                });

            });


            $(document).on('click', "#slab-table a.enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var e_s_id = data[0];
                var e_s_name = data[1];
                $('.model_msg1').click();
                $('#e_s_id').val(e_s_id);
                $('#e_s_name').html(e_s_name);
            });

            $(document).on('click', ".slab_enable_form", function (e) {
                e.preventDefault();
                e_s_id = $('#e_s_id').val();
                $('.close').click();
                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/slab-action.php",
                    cache: false,
                    data: { action: 'enable', slabId: e_s_id },
                    success: function (data) {
                        if (data == "success") {
                            $('.model_msg0').click();
                            $('#slab_msg').html('Allowance Slab Enabled Successfully');
                            oTable.fnDraw();
                        }
                        else
                            alert("Error on query");
                    }
                });

            });


            $('#slabAddForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "php/slab-add.php",
                    cache: false,
                    data: slabFunctions.getFormDataForSlab($(this).serializeArray()),
                    success: function (data) {
                        if (data == "success") {
                            $('#slabAddForm')[0].reset();
                            $('#add-slab').toggle('hide');
                            oTable.fnDraw();
                            $('#percentage_total').html('');
                            $('#slabAddForm-back-1').click();
                            $('.model_msg0').click();
                            $('#slab_msg').html('Slab Added Successfully');
                            $('#previewOutput').html('');
                        } else {
                            alert("Error On Query");
                        }
                    }

                });
            });
        }

    };

} ();