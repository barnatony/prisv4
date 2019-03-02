$(document).ready(function() {

			$("#employee_id").on('blur', function() {
			if ($('#emp_id_prefix').val() != undefined) {
					$('#e_id').text($('#emp_id_prefix').val() + $(this).val());
				} else if ($('#emp_id_suffix').val() != undefined) {
					$('#e_id').text($(this).val() + $('#emp_id_suffix').val());
				}

			});

			$("#employee_name").on('blur', function() {
				$('#ename').text($(this).val());

			});

			$("#gender1,#gender2,#gender3").on('blur', function() {
				$('#d_gender').text($(this).val());
			});

			$("#email_id").on('blur', function() {
				$('#d_mail_id').text($(this).val());
			});
			$("#dob").on('change', function() {
				$('#d_dob').text($(this).val());
			});
			$("#doj").on('change', function() {
				$('#d_doj').text($(this).val());
			});

			$("#mobile_no").on('blur', function() {
				$('#d_mob_no').text($(this).val());
			});

			$("#building_name").on('blur keyup', function() {
				$('#d_addr').text($(this).val());
			});
			$("#street").on('blur keyup', function() {
				$('#d_addr1').text($(this).val());
			});
			$("#area").on('blur keyup', function() {
				$('#d_addr2').text($(this).val());
			});
			$("#pf_number").on('blur', function() {
				$('#d_pf_number').text($(this).val());
			});
			$("#uan_number").on('blur', function() {
				$('#d_uan_no').text($(this).val());
			});
			$("#ef_number").on('blur', function() {
				$('#d_esi_no').text($(this).val());
			});

			$("#desig").change(function() {
				$('#d_desig').text($("#desig option:selected").text());
			});

			$("#dept").change(function() {
				$('#d_dept').text($("#dept option:selected").text());
			});

			$("#bank_ac").on('blur', function() {
				$('#d_bank_ac').text($(this).val());
			});
			$("#bank_ifsc").on('blur', function() {
				$('#d_ifcs').text($(this).val());
			});
		   $("#salary_based_on1,#salary_based_on2,#salary_based_on3").on(
					'change',
					function() {
						// $("#gross").on('blur', function ()
						$(document).on('click', '#employeeAddForm-next-2',
								function() {
									$('#d_sal').text($('#gross').val());
									$('#d_ctc').text($('#Subctc').val());

							});
					});
		   $(document).on('click', '#employeeAddForm-next-2',
					function() {
						$('#d_sal').text($('#gross').val());
						$('#d_ctc').text($('#Subctc').val());
					});
			$("#payment_mode").change(
					function() {
						$('#d_payment_mode').text(
								$("#payment_mode option:selected").text());
					});

			/*
			 * function addr() { var
			 * addr=document.getElementById('building_name') + <br>; var
			 * city=document.getElementById('city') + <br>; var
			 * state=document.getElementById('state') + <br>; var
			 * pincode=document.getElementById('pin_code') + <br>; var
			 * address=addr + city + state + pincode;
			 * $("#d_addr").append(address).text($(this).val()); }
			 */
		});