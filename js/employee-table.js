var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function (allowData) {

            var nCloneTh = document.createElement('th');
            var nCloneTd = document.createElement('td');
            nCloneTd.innerHTML = '<img class="openClose" src="../assets/advanced-datatable/examples/examples_support/details_open.png">';
            nCloneTd.className = "center";
            $('#employee_table thead tr').each(function () {
                this.insertBefore(nCloneTh, this.childNodes[0]);
            });

            $('#employee_table tbody tr').each(function () {
                this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
            });

            /*
            * Initialse DataTables, with no sorting on the 'details' column
            */
            var oTable = $('#employee_table').dataTable({
                "aoColumnDefs": [
                  { "bSortable": false, "aTargets": [0] }
              ],
                "aaSorting": [[1, 'asc']],
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],

                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-12'T><'col-lg-6'p>>",

                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../php/employee-view.php",
                "fnServerParams": function (aoData) {
                    aoData.push({ "name": "columns", "value": allowData });
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
                "aoColumns": [                           //Row control
                        {"bSortable": false },
                        { "sName": "w.employee_id" },
                        { "sName": "w.employee_name" },
						{ "sName": "des.designation_name" },
						{ "sName": "dep.department_name" },
                        { "sName": "br.branch_name" },
						{ "sName": "w.status_id" },
						{ "sName": "w.employee_doj" }

                    ],
                "oColVis": {
                    "aiExclude": [0]
                },
                "oTableTools": {
                    "aButtons": [
                {
                    "sExtends": "pdf",
                    "mColumns": [1, 2, 3, 4, 5, 6],
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Employee Details"
                },
                {
                    "sExtends": "xls",
                    "mColumns": [1, 2, 3, 4, 5, 6]
                }
             ],
                    "sSwfPath": "../assets/data-tables/copy_csv_xls_pdf.swf"

                }
            });
            $('#employee_table_wrapper .dataTables_filter').html('<div class="input-group">\
                                              <input class="form-control medium" id="searchInput" type="text">\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchFilter" type="button">Search</button>\
                                              </span>\
                                              <span class="input-group-btn">\
                                                <button class="btn btn-white" id="searchClear" type="button">Clear</button>\
                                              </span>\
                                          </div>');
            $('#employee_table_processing').css('text-align', 'center');
            //$('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            $('#employee_table_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown
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

            /* Add event listener for opening and closing details
            * Note that the indicator for showing which row is open is not controlled by DataTables,
            * rather it is done here
            */
            $(document).on("click", "#employee_table tbody td img.openClose", function (e) {
                //$("#employee_table tbody td img.openClose").click(function (e) { 
                e.preventDefault();
                var nTr = $(this).parents('tr')[0];
                if (oTable.fnIsOpen(nTr)) {

                    /* This row is already open - close it */
                    this.src = "../assets/advanced-datatable/examples/examples_support/details_open.png";
                    this.class = 'openClose';
                    oTable.fnClose(nTr);
                }
                else {
                    /* Open this row */
                    this.src = "../assets/advanced-datatable/examples/examples_support/details_close.png";
                    this.class = 'openClose';
                    var tt = oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');

                }
            });



            function fnFormatDetails(oTable, nTr) {

                var aData = oTable.fnGetData(nTr);
                var sOut = '<div class="col-lg-12"> <div class="col-lg-2"><div class="fileupload-new thumbnail" style="width: 133px; height: 170px;margin-bottom: 5px"><img id="preview_image" src="../CompanyData/' + aData[12] + '" alt="Employee Image"> </div></div> <div class="col-lg-10"><table width="100%" height="17%" border="0" cellspacing="0" cellpadding="5">';
                sOut += '<tr valign="top"><td width="19%"><b>Probation Period </b></td><td>' + aData[28] + '</td><td width="19%"><b>Confirmation Date </b><td>' + aData[32] + '</td><td width="19%"><b>Reporting Person</b><td>' + aData[49] + '</td></tr>';
                sOut += '<tr valign="top"><td width="19%"><b>Gross Salary</b></td><td>' + aData[17] + '</td><td width="19%"><b>Slap Name</b><td>' + aData[42] + '</td><td width="19%"><b>Payment Mode</b><td>' + aData[40] + '</td></tr>';
                sOut += '<tr valign="top"><td width="19%"><b>EPF</b></td><td>' + aData[34] + '</td><td width="19%"><b>UAN</b><td>' + aData[35] + '</td><td width="19%"><b>ESI </b><td>' + aData[36] + '</td></tr>';
                sOut += '<tr valign="top"><td width="19%"><b>Exit Date</b></td><td>' + aData[41] + '</td><td width="19%"><b></b></td><td></td><td></td><td><a href="#edit_employee" class="edit" data-toggle="modal"><button type="button" class="btn btn-success"><i class="fa fa-pencil-square-o"></i></button></a>&nbsp;<a href="#edit_employee" class="edit" data-toggle="modal"><button type="button" class="btn btn-warning"><i class="fa fa-trash-o"></i></button></a></td></tr>';
                sOut += '</table></div></div>';

                return sOut;
            }

            $(document).on("click", "#employee_table a.edit", function (e) {
                e.preventDefault();
                var nTr = $(this).parents('.details').parent().prev()[0];
                var aData = oTable.fnGetData(nTr);
                $.ajax({
                    type: "POST",
                    url: "../php/employee-fill.php",
                    cache: false,
                    data: { 'employee_id': aData[01], 'allowColumns': allowData },
                    success: function (data) {
                        var json_obj = $.parseJSON(data); //parse JSON
                        if (json_obj[0].slab_id == "Nil") {
                            $("input[name=salary_based_on][value='Nil']").click();
                            $("input[name=salary_based_on][value='Nil']").prop('checked', true);
                        } else {
                            if (json_obj[0].slab_type == 'basic' || json_obj[0].slab_type == 'gross') {

                                $("input[name=salary_based_on][value=" + json_obj[0].slab_type + "]").click();

                            }
                        }
                        $('#employee_id_update').val(json_obj[0].employee_id);
                        $('#employee_name_update').val(json_obj[0].employee_name);
                        $('#dob').val(json_obj[0].employee_dob);
                        $('#fname').val(json_obj[0].employee_father_name);
                        $("input[name=gender][value=" + json_obj[0].employee_gender + "]").prop('checked', true);
                        $("input[name=marital_status][value=" + json_obj[0].employee_marital_status + "]").prop('checked', true);
                        $('#edit_preview_image').attr('src', '../CompanyData/' + json_obj[0].employee_image);
                        $('#phone_no').val(json_obj[0].employee_phone);
                        $('#mobile_no').val(json_obj[0].employee_mobile);
                        $('#email_id').val(json_obj[0].employee_email);
                        $('#building_name').val(json_obj[0].employee_build_name);
                        $('#area').val(json_obj[0].employee_area);
                        $('#street').val(json_obj[0].employee_street);
                        $('#state').val(json_obj[0].employee_state);
                        $('#city').val(json_obj[0].employee_city);
                        $('#pin_code').val(json_obj[0].employee_pin_code);
                        $('#pan_no').val(json_obj[0].employee_pan_no);
                        $('#aadhaar_id').val(json_obj[0].employee_aadhaar_id);
                        $('#bank_name').val(json_obj[0].employee_bank_name);
                        $('#bank_ac').val(json_obj[0].employee_acc_no);
                        $('#bank_ifsc').val(json_obj[0].employee_bank_ifsc);
                        $('#bank_branch').val(json_obj[0].employee_bank_branch);
                        $('#probation_period').val(json_obj[0].employee_probation_period);
                        $('#notice_period').val(json_obj[0].notice_period);
                        $('#rep_man').val(json_obj[0].reporting_manager);
                        $('#doj').val(json_obj[0].employee_doj);
                        $('#confirmation_date').val(json_obj[0].employee_confirmation_date);
                        $("#branch_loc option[value=" + json_obj[0].branch_id + "]").attr("selected", "selected")
                        $("#branch_loc").trigger("chosen:updated");
                      
                        $("#desig option[value=" + json_obj[0].designation_id + "]").attr("selected", "selected")
                        $("#desig").trigger("chosen:updated");
                        $("#dept option[value=" + json_obj[0].department_id + "]").attr("selected", "selected")
                        $("#dept").trigger("chosen:updated");
                        $("#job_status option[value=" + json_obj[0].status_id + "]").attr("selected", "selected")
                        $("#job_status").trigger("chosen:updated");
                        $('#pf_no').val(json_obj[0].employee_emp_pf_no);
                        $('#uan_no').val(json_obj[0].employee_emp_uan_no);
                        $('#esi_no').val(json_obj[0].employee_emp_esi_no);
                        //$('#slab_opt').append('<option value="' + json_obj[0].slab_id + '" selected="true">' + json_obj[0].slab_name + '<option>');
                        function slabSelect() {
                            $('#slab_opt option[value="' + json_obj[0].slab_id + '"]').prop('selected', true);
                        }
                        setTimeout(slabSelect, 2000)
                        //$('#slab_opt').text(json_obj[0].slab_name);
                        if (json_obj[0].pf_limit == 1) {
                            //for PF checked or not 
                            $('#pf_limit').prop('checked', true);
                        } else {
                            $('#pf_limit').prop('checked', false);
                        }

                        $("#payment_mode_chosen option[value=" + json_obj[0].payment_mode_id + "]").attr("selected", "selected");
                        $('#1grossSalary').val(json_obj[0].employee_salary_amount);
                          $.each(json_obj[0], function (k, v) {
                            //display the key and value pair
                            $('#' + k).val(v);
                        });
                          $.each(json_obj[0], function (k, v) {
                            //display the key and value pair
                            $('#1' + k).val(v);
                        });


                    }

                });




            });

            $('#edit_employee_form').on('submit', function (e) {
                   $('#edit_employee_form').find('input[type="text"]').each(function () {
                      if ($(this).attr('data-type') == 'rupee') {
                          $(this).val(deFormate($(this).val()));
                         
                      }  });
                e.preventDefault();
                $.ajax({
                    processData: false,
                    contentType: false,
                    type: "POST",
                    url:"../php/employee-update.php",
                    cache: false,
                    data: new FormData(this),
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if (data1[0] == "success") {
                            $('.close').click();
                           alert(data1[1]);
                             $('#edit_employee_form')[0].reset();
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