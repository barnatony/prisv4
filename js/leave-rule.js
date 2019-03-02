var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {

            var oTable = $('#leave-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6' f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "php/leave-view.php",
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
                       { "sName": "rule_id" },                   //Row control , , ,, ,, , b.esi_no, b.pf_no, b.tan_no 
                        {"sName": "rule_name" },
                        { "sName": "frequency" },
                        { "sName": "days_count" },
                        { "sName": "carry_forward" },
						{ "sName": "is_encashable" },
						{ "sName": "is_applicable_for_prob" },
						{ "sName": "applicable_to" },
                        { "sName": "enabled", "bSortable": false },
                          ], "aoColumnDefs": [{
                        // `data` refers to the data for the cell (defined by `mData`, which
                        // defaults to the column being worked with, in this case is the first
                        // Using `row[0]` is equivalent.
                        "mRender": function (data, type, row) {
                            if (data == 0) {
                                return 'No';
                            } else {
                                if (data == 1) {
                                    return 'Yes';
                                } else {
                                    if (data == 'Y') {
                                        return 'Year';
                                    } else {
                                        if (data == 'M') {
                                            return 'Month';
                                        } else {
                                            if (data == 'Female') {
                                                return 'Female';
                                            } else {
                                                if (data == 'Male') {
                                                    return 'Male'
                                                } else {

                                                    if (data == 'T') {
                                                        return 'Trans';
                                                    } else {
                                                        if (data == 'A') {
                                                            return 'All';
                                                        } else {
                                                            return data;
                                                        }
                                                    }
                                                }

                                            }

                                        }
                                    }
                                }
                            }
                        },
                        "aTargets": [2, 4, 5, 6, 7]
                    }],


                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Branch Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
                }
             ],
                    "sSwfPath": "../swf/copy_csv_xls_pdf.swf"

                }

            });

            $('#leave-sample_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#leave-sample_processing').css('text-align', 'center');
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
             $(document).on('click', "#leave-sample a.disable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var l_id = data[0];
                var l_name = data[1];
                $('.model_msg2').click();
                $('#l_id').val(l_id);
                $('#l_name').html(l_name);
            });

            $(document).on('click', ".leave_disable_form", function (e) {
                e.preventDefault();
                l_id = $('#l_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/leave-action.php",
                        cache: false,
                        data: { action: 'disable', rule_id: l_id },
                        success: function (data) {
                            if (data == "success") {
                                $('.model_msg0').click();
                                $('#leave_msg').html('Rule Disabled Successfully');
                                oTable.fnDraw();
                            }

                            else
                                alert("Error on query");
                        }
                    });
                

            });

            //Disable coding
            $(document).on('click', "#leave-sample a.enable", function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);
                var e_l_id = data[0];
                var e_l_name = data[1];
                $('.model_msg1').click();
                $('#e_l_id').val(e_l_id);
                $('#e_l_name').html(e_l_name);
            });

            $(document).on('click', ".leave_enable_form", function (e) {
                e.preventDefault();
                e_l_id = $('#e_l_id').val();
                $('.close').click();
                $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/leave-action.php",
                        cache: false,
                        data: { action: 'enable', rule_id: e_l_id },
                        success: function (data) {
                            if (data == "success") {
                                $('.model_msg0').click();
                                $('#leave_msg').html('Rule Enabled Successfully');
                                oTable.fnDraw();
                            }
                            else
                                alert("Error on query");
                        }
                    });
                
            });



            //Add LEave Rules
             $(document).on('click',"#leave-add",function(e) {
                e.preventDefault();
                if ($("#leave_name").val() == '' || $("#alias_name").val() == '' || $("#frequency").val() == '' || $("#days_count").val() == '' || $("#attendance_dependant").val() == '' || $("#carry_forward").val() == '' || $("#max_cf_days").val() == '' || $("#is_encashable").val() == '' || $("#max_enc_days").val() == '' || $("#encashable_on").val() == '' || $("#encahable_timesOf_pay").val() == '' || $("#pro_rata_basis").val() == '' || $("#is_applicable_for_prob").val() == '' || $("#leave_in_middle").val() == '' || $("#leave_in_preceeding").val() == ''
                 || $("#leave_in_succeeding").val() == '' ||
                  $("#club_with").val() == '' || $("#applicable_to").val() == '' || $("#leave_in_succeeding").val() == '')
                {
                    $('.model_msg0').click();
                    $('#leave_msg').html('Enter All Requirement');
                }
                else {
                      $.ajax({
                        dataType: 'html',
                        type: "POST",
                        url: "php/leave-add.php",
                        cache: false,
                        data: $('#leaveAddForm').serialize(),
                        beforeSend:function(){
                         	$('#leave-add').button('loading'); 
                          },
                          complete:function(){
                         	 $('#leave-add').button('reset');
                          },
                        success: function (data) {
                            data1 = JSON.parse(data);
                            if (data1[0] == "success") {
                                jQuery('#add-leave-toggle').toggle('hide');
                                $('#leaveAddForm')[0].reset();
                                $('.model_msg0').click();
                                $('#leave_msg').html('Leave Rule Added Successfully ');
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

            //Add LEave Rules
             $(document).on('click',"#leave-sample a.edit",function(e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var data = oTable.fnGetData(nRow);

                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/leave-fill.php",
                    cache: false,
                    data: { rule_id: data[0] },
                    success: function (data) {
                        var json_obj = $.parseJSON(data); //parse JSON

                        $('#e_max_combinable').val(json_obj[0].max_combinable);
                        $('#rule_id').val(json_obj[0].rule_id);
                        $('#edit_leave_name').val(json_obj[0].rule_name);
                        $('#edit_alias_name').val(json_obj[0].alias_name);

                        if (json_obj[0].pro_rata_basis !== '0') {
                            $('#e_pro_rate').parents().removeClass("switch-off switch-animate");
                            $('#e_pro_rate').parents().addClass("switch-on switch-animate");

                        } else {

                            $('#e_pro_rate').parents().removeClass("switch-on switch-animate");
                            $('#e_pro_rate').parents().addClass("switch-off switch-animate");

                        }

                        if (json_obj[0].is_applicable_for_prob !== '0') {
                            $('#e_leave_probation').val("1");
                            $('#e_leave_probation').parents().removeClass("switch-off switch-animate");
                            $('#e_leave_probation').parents().addClass("switch-on switch-animate");
                        } else {
                            $('#e_leave_probation').val("0");
                            $('#e_leave_probation').parents().removeClass("switch-on switch-animate");
                            $('#e_leave_probation').parents().addClass("switch-off switch-animate");
                        }


                        if (json_obj[0].frequency !== 'Y') {
                            $('#e_frequency').prop('checked', true);
                            $('#e_frequency').parents().removeClass("switch-off switch-animate");
                            $('#e_frequency').parents().addClass("switch-on switch-animate");
                        } else {
                            $('#e_frequency').prop('checked', false);
                            $('#e_frequency').parents().removeClass("switch-on switch-animate");
                            $('#e_frequency').parents().addClass("switch-off switch-animate");
                        }



                        $('#e_days_count').val(json_obj[0].days_count);

                        if (json_obj[0].attendance_dependant !== '0') {
                            $('#e_attendance_dependant').prop('checked', true);
                            $('#e_attendance_dependant').parents().removeClass("switch-off switch-animate");
                            $('#e_attendance_dependant').parents().addClass("switch-on switch-animate");
                        } else {
                            $('#e_attendance_dependant').prop('checked', false);
                            $('#e_attendance_dependant').parents().removeClass("switch-on switch-animate");
                            $('#e_attendance_dependant').parents().addClass("switch-off switch-animate");
                        }

                        if (json_obj[0].carry_forward !== '0') {

                            $('#e_carry_forward').prop('checked', true);
                            $('#e_carry_forward').parents().removeClass("switch-off switch-animate");
                            $('#e_carry_forward').parents().addClass("switch-on switch-animate");
                            $('#e_m_carry_forward').val(json_obj[0].max_cf_days);
                            $("#e_max_cf").show();
                        } else {
                            $('#e_carry_forward').prop('checked', false);

                            $('#e_carry_forward').parents().removeClass("switch-on switch-animate");
                            $('#e_carry_forward').parents().addClass("switch-off switch-animate");
                            $("#e_max_cf").hide();
                        }

                        if (json_obj[0].is_encashable !== '0') {
                            $('#e_encashable').prop('checked', true);
                            $('#e_encashable').parents().removeClass("switch-off switch-animate");
                            $('#e_encashable').parents().addClass("switch-on switch-animate");
                            $("#e_max_encash,#e_encash_on,#e_encash").show();
                            $('#max_encash_e').val(json_obj[0].max_enc_days);
                            $('#e_encahable_timesOf_pay').val(json_obj[0].encahable_timesOf_pay);
                            if (json_obj[0].encashable_on !== 'R') {
                                $('#e_encashable_on').prop('checked', true);
                                $('#e_encashable_on').parents().removeClass("switch-off switch-animate");
                                $('#e_encashable_on').parents().addClass("switch-on switch-animate");
                            } else {
                                $('#e_encashable_on').prop('checked', false);
                                $('#e_encashable_on').parents().removeClass("switch-on switch-animate");
                                $('#e_encashable_on').parents().addClass("switch-off switch-animate");
                            }

                        } else {
                            $('#e_encashable').prop('checked', false);
                            $('#e_encashable').parents().removeClass("switch-on switch-animate");
                            $('#e_encashable').parents().addClass("switch-off switch-animate");
                            $("#e_max_encash,#e_encash_on,#e_encash").hide();
                        }


                        //leave midle
                        if (json_obj[0].leave_in_middle == 'W') {
                           
                            $('#weekoff').prop('checked', true);

                        } else {
                            if (json_obj[0].leave_in_middle == 'H') {
                                  
                                $('#holiday').prop('checked', true);

                            } else {

                                if (json_obj[0].leave_in_middle == 'B') {
                                     
                                    $('#holiday').prop('checked', true);
                                    $('#weekoff').prop('checked', true);


                                } else {
                                     
                                    $('#holiday').prop('checked', false);
                                    $('#weekoff').prop('checked', false);


                                }

                            }
                        }

                        //leave Preceeding
                        if (json_obj[0].leave_in_preceeding == 'W') {

                            $('#p_weekoff').prop('checked', true);

                        } else {
                            if (json_obj[0].leave_in_preceeding == 'H') {
                                $('#p_holidays').prop('checked', true);
  } else {

                                if (json_obj[0].leave_in_preceeding == 'B') {
                                    $('#p_weekoff').prop('checked', true);
                                    $('#p_holidays').prop('checked', true);


                                } else {
                                    $('#p_weekoff').prop('checked', false);
                                    $('#p_holidays').prop('checked', false);


                                }

                            }
                        }


                        //leave Succeding
                        if (json_obj[0].leave_in_succeeding == 'W') {
                            $('#s_weekoff').prop('checked', true);

                        } else {
                            if (json_obj[0].leave_in_succeeding == 'H') {
                                $('#s_holidays').prop('checked', true);

                            } else {

                                if (json_obj[0].leave_in_succeeding == 'B') {
                                    $('#s_holidays').prop('checked', true);
                                    $('#s_weekoff').prop('checked', true);


                                } else {
                                    $('#s_holidays').prop('checked', false);
                                    $('#s_weekoff').prop('checked', false);


                                }

                            }
                        }


                        //leave Succeding
                        if (json_obj[0].applicable_to == 'Male') {

                            $('#male').prop('checked', true);
                             $('#trans').prop('checked', false);
                            $('#female').prop('checked', false);

                        } else {
                            if (json_obj[0].applicable_to == 'Female') {
                                $('#female').prop('checked', true);
                                  $('#male').prop('checked', false);
                             $('#trans').prop('checked', false);

                            } else {

                                if (json_obj[0].applicable_to == 'All') {
                                    $('#trans').prop('checked', true);
                                        $('#male').prop('checked', true);
                                        $('#female').prop('checked', true);

                                } else {
                                    if (json_obj[0].applicable_to == 'All') {
                                        $('#trans').prop('checked', true);
                                        $('#male').prop('checked', true);
                                        $('#female').prop('checked', true);

                                    } 
                                }
                            }
                        }



                        $.each(json_obj[0].club_with.split(","), function (i, e) {
                            $("#e_club_with option[value='" + e + "']").prop("selected", true);
                        });

                        $("#e_club_with").trigger("chosen:updated"); //for drop down







                    }

                });



            });



            //Edit Submit Form
            $('#editleaveform').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/leave-update.php",
                    cache: false,
                    data: $('#editleaveform').serialize(),
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if (data1[0] == "success") {
                             $('.close').click();                            
                             $('.model_msg0').click();
                             $('#leave_msg').html('Leave Rule Updated Successfully ');
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