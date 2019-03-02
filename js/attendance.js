var EditableTable = function () {
    return {

        //main function to initiate the module
        init: function (allowData, id, selected_radio, date) {
            var aoColumns = allowData;
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
                var length = jqTds.length - 1;
                for (var i = 2, iLen = length; i < iLen; i++) {
                    jqTds[i].innerHTML = '<input type="text" class="form-control small" required value="' + aData[i] + '">';
                }
                jqTds[length].innerHTML = '<a class="a_edit" href="">Save</a>&nbsp;&nbsp;<a class="cancel" href="">Cancel</a>';
                // jqTds[6].innerHTML = '<a class="edit" href="">Cancel</a>';
            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                var data_length = jqInputs.length;
                var edit_pos = data_length + 2;
                var j = 2;
                info = [];
                for (var i = 0, iLen = data_length; i < iLen; i++) {
                    oTable.fnUpdate(jqInputs[i].value, nRow, j, false);
                    var colum_name = oTable.find('th').eq([j]).html();
                    info[i] = [colum_name, jqInputs[i].value];
                    j++;
                }
                oTable.fnUpdate('<a class="a_edit" href="">Edit</a>', nRow, edit_pos, false);
            }

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
                "sAjaxSource": "php/attendance_view.php",
                "fnServerParams": function (aoData) {
                    aoData.push({ "name": "columns", "value": allowData['columns'] }, { "name": "attFor", "value": selected_radio },
                     { "name": "affected_ids", "value": id }, { "name": "date", "value": date });
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
            var d = new Date();
              var n = eval(d.getMonth() + 1);
              var y = d.getFullYear();
              var date_s = n + "" + y;
            if (date!==date_s) {
                oTable.fnSetColumnVis(7, false);
            }


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
            $('#searchFilter').on('click', function () {
                oTable.fnFilter($(this).parent().parent().find('input').val());
            });
            $('#searchClear').on('click', function () {
                $(this).parent().parent().find('input').val("");
                oTable.fnFilter("");
            });

            var nEditing = null;


            $('#slab-table a.disable').on('click', function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                if (confirm("Disable Mode " + data[1] + " ?") == false) {
                    return;
                } else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/slab-action.php",
                        cache: false,
                        data: { action: 'disable', slabId: data[0] },
                        success: function (data) {
                            if (data == "success") {
                                alert("Mode Disabled");
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                }


            });

            $(document).on('click', "#slab-table a.cancel", function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });


            $(document).on('click', "#slab-table a.a_edit", function (e) {
                e.preventDefault();
                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/attendance_fill.php",
                    cache: false,
                    data: { employee_id: data[0], employee_name: data[1] },
                    success: function (data) {
                        var json_obj = $.parseJSON(data); //parse JSON
                        $('#e_employee_name').val(json_obj.employee_name[0]);
                        $('#e_employee_id').val(json_obj.employee_leave_account[0].employee_id);
                        $('#e_employee_name_id').val(json_obj.employee_name[0] + " [ " + json_obj.employee_leave_account[0].employee_id + " ]");
                        $('#yearly_lop').val(json_obj.employee_leave_account[0].lop);
                        $('#yearly_lop_s').html(json_obj.employee_leave_account[0].lop);
                        $.each(json_obj.employee_leave_account[0], function (k, v) {
                            //display the key and value pair
                            if (k[0] == "R") {
                                $('#' + k).html(v);
                            }
                        });

                        $.each(json_obj.company_leave_rules[0], function (k, v) {
                            //display the key and value pair

                            $('#max' + v.alias_name).html(v.max_combinable);

                        });

                    }

                });

            });




            $('#editshiftform').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/attendance_update.php",
                    cache: false,
                    data: $('#editshiftform').serialize(),
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if (data1[0] == "success") {
                            $('.close').click();
                            $('#editshiftform')[0].reset();
                            oTable.fnDraw();
                            $('.model_msg0').click();
                            $('#att_msg').html('Attendance Updated Successfully');

                        }
                        else
                            if (data1[0] == "error") {
                                alert(data1[1]);
                            }
                    }

                });

            });
            /*
            $(document).on('click',"#slab-table a.edit",function(e) {
            e.preventDefault();

            Get the row as a parent of the link that was clicked on 
            var nRow = $(this).parents('tr')[0];



            if (nEditing !== null && nEditing != nRow) {

            Currently editing - but not this row - restore the old before continuing to edit mode 
            restoreRow(oTable, nEditing);

            editRow(oTable, nRow);

            nEditing = nRow;
            } else if (nEditing == nRow && this.innerHTML == "Save") {

            Editing this row and want to save it 
            saveRow(oTable, nEditing);
            nEditing = null;
            var data = oTable.fnGetData(nRow);

            if (data[1] == '' || data[2] == '') {
            alert("Enter Required Fields");
            var data_length = editRow(oTable, nRow);
            console.log(data_length);
            nEditing = nRow;
            }
            else {


            $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/attendance_update.php",
            cache: false,
            data: { employee_id: data[0], info: info },
            success: function (data) {
            data1 = JSON.parse(data);
            if (data1[0] == "success") {
            alert(data1[1]);
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
            No edit in progress - let's start one 
            editRow(oTable, nRow);
            nEditing = nRow;
            }
            });
            /*
            $('#slab-table a.edit').on('click', function (e) {
            e.preventDefault();
            var nRow = $(this).parents('tr')[0];
            var data = oTable.fnGetData(nRow);
            if (confirm("Enable Mode " + data[1] + " ?") == false) {
            return;
            } else {
            $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/slab-action.php",
            cache: false,
            data: { action:'enable',slabId: data[0] },
            success: function (data) {
            if (data == "success") {
            alert("Mode enabled");
            oTable.fnDraw();
            }
            else
            alert("Error on query");
            }
            });
            }


            });

            */
            $('#attendance_get_form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "php/attendance_add.php",
                    cache: false,
                    data: $(this).serializeArray(),
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if (data1[0] == "success") {
                            alert(data1[1]);
                            $('#attendance_get_form')[0].reset();

                        }
                        else
                            if (data1[0] == "") {
                                alert(data1[1]);
                            }
                    }

                });
            });
        }

    };

} ();