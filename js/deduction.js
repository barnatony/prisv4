var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            $('#deductions_type').on('change', function (event) {
                $("#deductions_type_page").hide();
                var selected_id = $('#deductions_type :selected').val();
                var selected_id_i = $("#deductions_type option:selected").text();
                $.ajax({
                    type: "POST",
                    url: "php/allowance_deduction_fill.php",
                    cache: false,
                    data: { 'selected_id': selected_id },
                    success: function (data) {
                        var json_obj = $.parseJSON(data); //parse JSON
                        if (json_obj[0].display_flag == '1' && json_obj[0].pay_structure_id !== 'labour_welfare_fund'
                        && json_obj[0].pay_structure_id !== 'pt' && json_obj[0].pay_structure_id !== 'it') {
                            $('.D').show();
                            $('.cancel').hide();
                            $('.A,.DD').hide();
                            $('.Dd').show();

                            var id = json_obj[0].pay_structure_id;

                            $.ajax({
                                type: "POST",
                                url: "php/allowance_deduction_fill.php",
                                cache: false,
                                data: { 'deduction_id': id },
                                success: function (data) {
                                    $("#deductions_type_page").show();
                                    var json_obj = $.parseJSON(data); //parse JSON
                                    console.log(json_obj);
                                    $('#deduction_id').val(json_obj[0].deduction_id);
                                    $('#deduction_name').val(selected_id_i);
                                    $('#alias_name').val(json_obj[0].alias_name);
                                    $("input[name=is_both_contribution][value=" + json_obj[0].is_both_contribution + "]").prop('checked', true);
                                    $("input[name=is_admin_charges][value=" + json_obj[0].is_admin_charges + "]").prop('checked', true);
                                    var str = json_obj[0].employee_share;
                                    var myString = str.substr(str.indexOf("|") + 1)
                                    if (myString !== 'P') {
                                        $("#Lumsum").click();
                                        $("input[name=cal][value='1']").prop('checked', true);

                                    } else {
                                        $("#Percentage").click();
                                        $("input[name=cal][value='0']").prop('checked', true);

                                    }




                                    var ee_employee_share = json_obj[0].employee_share.substr(0, json_obj[0].employee_share.indexOf('|'));
                                    $('#employee_share').val(ee_employee_share);

                                    $('#max_employee_share').val(json_obj[0].max_employee_share);
                                    $('#max_employer_share').val(json_obj[0].max_employer_share);
                                    $('#cal_exemption').val(json_obj[0].cal_exemption);


                                    var ee_employer_share = json_obj[0].employer_share.substr(0, json_obj[0].employer_share.indexOf('|'));
                                    $('#employer_share').val(ee_employer_share);

                                    var e_admin_charges = json_obj[0].admin_charges.substr(0, json_obj[0].admin_charges.indexOf('|'));
                                    $('#admin_charges').val(e_admin_charges);




                                    $('#deduce_in').val('');
                                    $.each(json_obj[0].deduce_in.split(","), function (i, e) {
                                        $("#deduce_in option[value='" + e + "']").prop("selected", true);
                                    });
                                    $("#deduce_in").trigger("chosen:updated"); //for drop down

                                    var str = json_obj[0].due_in;
                                    var due_in = str.substr(str.indexOf("|") + 1)
                                    if (due_in == 'C') {
                                        $("input[name=due_in_sub]").prop('checked', false);
                                        $("input[name=due_in_sub][value='Current Month']").prop('checked', true);
                                    } else {
                                        $("input[name=due_in_sub]").prop('checked', false);
                                        $("input[name=due_in_sub][value='Next Month']").prop('checked', true);
                                    }

                                    $('#payment_to').val(json_obj[0].payment_to);
                                    if (json_obj[0].frequency == 'M') {

                                        $('#frequency option[value="Month"]').prop('selected', true);
                                    } else {
                                        if (json_obj[0].frequency == 'Y') {
                                            $('#frequency option[value="Yearly"]').prop('selected', true);
                                        } else {
                                            if (json_obj[0].frequency == 'HY') {
                                                $('#frequency option[value="Half-yearly"]').prop('selected', true);
                                            } else {
                                                $('#frequency option[value="Quarter"]').prop('selected', true);

                                            }
                                        }
                                    }

                                    var e_due_in = json_obj[0].due_in.substr(0, json_obj[0].due_in.indexOf('|'));
                                    $('#due_in').val(e_due_in);




                                }


                            });
                        }
                        else {
                            if (json_obj[0].display_flag == '0' && json_obj[0].pay_structure_id !== 'labour_welfare_fund'
                        && json_obj[0].pay_structure_id !== 'pt' && json_obj[0].pay_structure_id !== 'it') {
                                var id = json_obj[0].pay_structure_id;
                                $('.D,.DD').hide();
                                $('.cancel').hide();
                                $('.A').show();
                                $('.Dd').hide();
                                $.ajax({
                                    type: "POST",
                                    url: "php/allowance_deduction_fill.php",
                                    cache: false,
                                    data: { 'deduction_id': id },
                                    success: function (data) {
                                        $("#deductions_type_page").show();
                                        var json_obj = $.parseJSON(data); //parse JSON
                                        $('#deduction_id').val(json_obj[0].deduction_id);
                                        $('#deduction_name').val(selected_id_i);
                                        $('#alias_name').val(json_obj[0].alias_name);
                                        $("input[name=is_both_contribution][value=" + json_obj[0].is_both_contribution + "]").prop('checked', true);
                                        $("input[name=is_admin_charges][value=" + json_obj[0].is_admin_charges + "]").prop('checked', true);
                                        var str = json_obj[0].employee_share;
                                        var myString = str.substr(str.indexOf("|") + 1)
                                        if (myString !== 'P') {
                                            $("#Lumsum").click();
                                            $("input[name=cal][value='1']").prop('checked', true);

                                        } else {
                                            $("#Percentage").click();
                                            $("input[name=cal][value='0']").prop('checked', true);

                                        }

                                        var e_employee_share = json_obj[0].employee_share.substr(0, json_obj[0].employee_share.indexOf('|'));
                                        $('#employee_share').val(e_employee_share);

                                        var e_employer_share = json_obj[0].employer_share.substr(0, json_obj[0].employer_share.indexOf('|'));
                                        $('#employer_share').val(e_employer_share);

                                        var e_admin_charges = json_obj[0].admin_charges.substr(0, json_obj[0].admin_charges.indexOf('|'));
                                        $('#admin_charges').val(e_admin_charges);

                                        $('#max_employee_share').val(json_obj[0].max_employee_share);
                                        $('#max_employer_share').val(json_obj[0].max_employer_share);
                                        $('#cal_exemption').val(json_obj[0].cal_exemption);

                                        $('#deduce_in').val('');
                                        $.each(json_obj[0].deduce_in.split(","), function (i, e) {
                                            $("#deduce_in option[value='" + e + "']").prop("selected", true);
                                        });
                                        $("#deduce_in").trigger("chosen:updated"); //for drop down

                                        var str = json_obj[0].due_in;
                                        var due_in = str.substr(str.indexOf("|") + 1)
                                        if (due_in == 'C') {
                                            $("input[name=due_in_sub]").prop('checked', false);
                                            $("input[name=due_in_sub][value='Current Month']").prop('checked', true);
                                        } else {
                                            $("input[name=due_in_sub]").prop('checked', false);
                                            $("input[name=due_in_sub][value='Next Month']").prop('checked', true);
                                        }

                                        $('#payment_to').val(json_obj[0].payment_to);
                                        if (json_obj[0].frequency == 'M') {

                                            $('#frequency option[value="Month"]').prop('selected', true);
                                        } else {
                                            if (json_obj[0].frequency == 'Y') {
                                                $('#frequency option[value="Yearly"]').prop('selected', true);
                                            } else {
                                                if (json_obj[0].frequency == 'HY') {
                                                    $('#frequency option[value="Half-yearly"]').prop('selected', true);
                                                } else {
                                                    $('#frequency option[value="Quarter"]').prop('selected', true);

                                                }
                                            }
                                        }

                                        var e_due_in = json_obj[0].due_in.substr(0, json_obj[0].due_in.indexOf('|'));
                                        $('#due_in').val(e_due_in);
                                    }


                                });
                            } else {
                                $("#deductions_type_page").hide();

                            }



                        }
                    }

                });








            });














            /*
            $('#deduction-add').live('click', function (e) {
            e.preventDefault();
            if ($("#deduction_name").val() == '' || $("#alias_name").val() == '' || $("#is_both_contribution").val() == '' || $("#is_admin_charges").val() == '' || $("#employee_share").val() == '' || $("#employer_share").val() == '' || $("#admin_charges").val() == '' || $("#deduce_in").val() == '' || $("#payment_to").val() == '' || $("#frequency").val() == '' || $("#due_in").val() == '')
            { alert("Enter all the required Fields"); }
            else {
            $.ajax({
            dataType: 'html',
            type: "POST",
            url: "php/deduction-add.php",
            cache: false,
            data: $('#deductionAddForm').serialize(),
            success: function (data) {
            data1 = JSON.parse(data);
            if (data1[0] == "success") {
            jQuery('#add-deduction-toggle').toggle('hide');
            $('#deductionAddForm')[0].reset();
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

            */


            //Edit Submit Form
            $('#deductionAddForm').on('submit', function (e) {
                e.preventDefault();
                $('.close').click();
                $.ajax({
                    dataType: 'html',
                    type: "POST",
                    url: "php/deduction-update.php",
                    cache: false,
                    data: $('#deductionAddForm').serialize(),
                    beforeSend:function(){
                     	$('.DD').button('loading'); 
                      },
                      complete:function(){
                     	 $('.DD').button('reset');
                      },
                    success: function (data) {
                        data1 = JSON.parse(data);
                        if (data1[0] == "success") {
                           $('.rr').click();
                            $('.DD').hide();
                            $('.D').show();
                            $('.cancel').hide();
                            $('.Dd').show();
                            $('#alias_name').prop('readonly', true);
                            $("input[type=cal]").attr('disabled', true);
                            $("input[type=due_in_sub]").attr('disabled', true);
                            $("input[type=radio]").attr('disabled', true);
                            $('#payment_to').prop('readonly', true);
                            $('#employee_share').prop('readonly', true);
                            $('#max_employee_share').prop('readonly', true);
                            $('#max_employer_share').prop('readonly', true);
                            $('#cal_exemption').prop('readonly', true);
                            $('#employer_share').prop('readonly', true);
                            $('#admin_charges').prop('readonly', true);
                            $("#frequency").prop("disabled", true);
                            $('#due_in').prop('readonly', true);
                            $('#deduce_in').prop('disabled', true).trigger("chosen:updated");
                        }
                        else
                            if (data1[0] == "error") {
                                alert(data1[1]);
                                $("#deduce_in").prop("disabled", true);
                            }
                    }

                });

            });

        }

    };

} ();