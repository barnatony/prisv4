var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {

            var oTable = $('#shift-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "php/shift-view.php",
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
                       { "sName": "shift_id" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
                        {"sName": "shift_name" },
                        { "sName": "start_time" },
                        { "sName": "end_time" },
                        { "sName": "grace_inTime" },
						{ "sName": "grace_outTime" },
						{ "sName": "early_start" },
						{ "sName": "late_end" },
                        { "sName": "min_hrs_ot" },
                        { "sName": "min_hrs_half_day" },
                        { "sName": "min_hrs_full_day" },
                        { "sName": "enabled", "bSortable": false },
                     	  ], "aoColumnDefs": [{
                     	      // `data` refers to the data for the cell (defined by `mData`, which
                     	      // defaults to the column being worked with, in this case is the first
                     	      // Using `row[0]` is equivalent.
                     	      "mRender": function (data, type, row) {
                     	          if (data == data) {
                     	              return data + ' Min';
                     	          } else {
                     	              return data;
                     	          }
                     	      },
                     	      "aTargets": [4, 5]
                     	  }],




                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Branch Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
             ],
                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                }

            });

            $('#shift-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#shift-sample_processing').css('text-align', 'center');
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

            //Enable coding
             $(document).on('click', "#shift-sample a.disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var s_id = data[0];
                var s_name = data[1];
                $('.model_msg2').click();
                $('#s_id').val(s_id);
                $('#s_name').html(s_name);
            });

            $(document).on('click', ".shift_disable_form", function (e) {
                e.preventDefault();
                s_id = $('#s_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/shift-action.php",
                        cache: false,
                        data: { action: 'disable', shift_id: s_id },
                        success: function (data) {
                            if (data == "success") {
                                $('.model_msg0').click();
                                $('#shift_msg').html('Shift Disabled Successfully');
                                oTable.fnDraw();
                            }
                             else
                                alert("Error on query");
                        }
                    });
              
            });

            //Disable coding

            $(document).on('click', "#shift-sample a.enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var s_id = data[0];
                var s_name = data[1];
                $('.model_msg1').click();
                $('#e_s_id').val(s_id);
                $('#e_s_name').html(s_name);
            });

            $(document).on('click', ".shift_enable_form", function (e) {
                e.preventDefault();
                s_id = $('#e_s_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/shift-action.php",
                        cache: false,
                        data: { action: 'enable', shift_id: s_id },
                        success: function (data) {
                            if (data == "success") {
                                 $('.model_msg0').click();
                                $('#shift_msg').html('Shift Enabled Successfully');
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
               
            });



            //Add LEave Rules
            $(document).on('click', "#shift-add", function (e) {
                e.preventDefault();
                if ($("#shift_name").val() == '' || $("#start_time").val() == '' || $("#end_time").val() == '' || $("#days_count").val() == '' || $("#attendance_dependant").val() == '' || $("#carry_forward").val() == '' || $("#max_cf_days").val() == '' || $("#is_encashable").val() == '' || $("#max_enc_days").val() == '' || $("#encashable_on").val() == '' || $("#encahable_timesOf_pay").val() == '' || $("#pro_rata_basis").val() == '' || $("#is_applicable_for_prob").val() == '' || $("#leave_in_middle").val() == '' || $("#leave_in_preceeding").val() == ''
                || $("#leave_in_succeeding").val() == '' ||
                $("#club_with").val() == '' || $("#applicable_to").val() == '' || $("#leave_in_succeeding").val() == '') {
                    $('.model_msg0').click();
                    $('#shift_msg').html('Enter All Requirement');
                }
                else {
                    $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/shift-add.php",
                        cache: false,
                        data: $('#shiftForm').serialize(),
                        beforeSend:function(){
                         	$('#shift-add').button('loading'); 
                          },
                          complete:function(){
                         	 $('#shift-add').button('reset');
                          },
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                jQuery('#add-shift-toggle').toggle('hide');
                                $('#shiftForm')[0].reset();
                                 $('.model_msg0').click();
                                $('#shift_msg').html(data1[1]);
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

            $(document).on('click', "#shift-sample a.edit", function (e) {
                e.preventDefault();
                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);

                $('#e_shift_id').val(data[0]);
                $('#e_shift_name').val(data[1]);
                $('#e_start_time').val(data[2]);
                $('#e_end_time').val(data[3]);
                $('#e_grace_inTime').val(data[4]);
                $('#e_grace_outTime').val(data[5]);
                $('#e_early_start').val(data[6]);
                $('#e_late_end').val(data[7]);
                $('#e_min_hrs_ot').val(data[8]);
                $('#e_min_hrs_half_day').val(data[9]);
                $('#e_min_hrs_full_day').val(data[10]);

            });




            //Edit Submit Form

            $('#editshiftform').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/shift-update.php",
                    cache: false,
                    data: $('#editshiftform').serialize(),
                    beforeSend:function(){
                     	$('.btn').button('loading'); 
                      },
                      complete:function(){
                     	 $('.btn').button('reset');
                      },
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if (data1[0] == "success") {
                            $('.close').click();
                            $('#editshiftform')[0].reset();
                           $('.model_msg0').click();
                           $('#shift_msg').html('Shift Updated Successfully');
                            oTable.fnDraw();

                        }
                        else
                            if (data1[0] == "error") {
                                alert(data1[1]);
                            }
                    }

                });

            });

        }

    };

} ();