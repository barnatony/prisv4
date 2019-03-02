<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Bass Techs">
<link rel="shortcut icon" href="img/favicon.png">

<title>Allowance Slabs</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-reset.css" rel="stylesheet">
<!--external css-->
<link href="../css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style-responsive.css" rel="stylesheet" />
<link href="../css/TableTools.css" rel="stylesheet" />
<link href="../css/table-responsive.css" rel="stylesheet" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
</head>
<style>
@media ( min-width : 992px) {
	.modal-lg {
		width: 900px;
	}
}

.back {
    margin-top: 5px;
    margin-left: 7px;
    margin-right: 10px;
    }
</style>
<body>

	<section id="container">
		<!--header start-->
        <?php
								
include_once (__DIR__ . "/header.php");
								?>
      <!--header end-->
		<!--sidebar start-->
		<aside>
         <?php include_once (__DIR__."/sideMenu.php");?>
         </aside>
		<!--sidebar end-->
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<!-- page start-->
				<div class="pull-right back">
							<a href=masterSetup.php class="btn btn-sm btn-danger" type="button" id="back-botton">
								<i class=" fa fa-arrow-left"></i> All Settings</a>
				</div>
				<section class="panel">
					<header class="panel-heading">
						Allowance Slabs
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-sm btn-info" id="showhide"
								style="margin-top: -5px;">
								<i class="fa fa-plus"></i> Add New
							</button>
						</div>
					</header>

					<div class="panel-body">
						<div class="col-lg-12">
							<div class="col-lg-12" id="add-slab" style="display: none">
								<div class="stepy-tab">
									<ul id="default-titles" class="stepy-titles clearfix">
										<li id="default-title-0" class="current-step">
											<div>Step 1</div>
										</li>
										<li id="default-title-1" class="">
											<div>Slab Preview</div>
										</li>
									</ul>
								</div>
								<form class="form-horizontal" role="form" method="post"
									id="slabAddForm" name="slabAddForm">
									<input type="hidden" name="act"
										value="<?php echo base64_encode($_SESSION['company_id']."!insert");?>" />

									<fieldset title="Slab Details" class="step" id="default-step-0">
										<legend></legend>
										<div class="form-group col-lg-6">
											<label for="slab_name"
												class="col-lg-5 col-sm-5 control-label">Slab Name</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" id="slab_name"
													name="sName" autocomplete="off" required>
											</div>
										</div>
										<div class="form-group col-lg-6">
											<label for="slab_name"
												class="col-lg-5 col-sm-5 control-label">Based on</label>
											<div class="col-lg-7"> 
											 <label for="based_on1" class="col-lg-6 control-label hide"><input
													name="sType" id="based_on1" value="basic" type="radio" disabled> Basic</label>	 
											 <label for="based_on2"	class="col-lg-6 control-label"><input name="sType"
													id="based_on2" value="gross" type="radio" checked> Gross</label>
											</div>
										</div>
										<input type="hidden" name="totalAllowances"
											id="totalAllowances"> <input type="hidden" name="minSalary"
											id="minimum_salary_amt">
										<div class="clearfix"></div>
										<div id="allowances_list"></div>
										<div class="form-group col-lg-6" id="percentage_total_div"
											style="display: none">
											<label class="col-lg-5 col-sm-5 control-label">Percentage
												Total</label>
											<div class="col-lg-7">
												<p class="col-lg-4 col-sm-4 control-label"
													id="percentage_total"></p>
											</div>
										</div>
										<div class="form-group col-lg-6" id="minimum_salary_div"
											style="display: none">
											<label class="col-lg-5 col-sm-5 control-label">Minimum Salary</label>
											<div class="col-lg-7">
												<p class="col-lg-4 col-sm-4 control-label"
													id="minimum_salary"></p>
											</div>
										</div>
									</fieldset>
									<fieldset title="Preview" class="step" id="default-step-1">
										<legend> </legend>
										<div class="form-group col-lg-6">
											<label for="" id="previewLabel"
												class="col-lg-5 col-sm-5 control-label">Gross</label>
											<div class="col-lg-7">
												<input type="text" class="form-control" id="previewInput"
													name="previewInput" autocomplete="off">
											</div>
										</div>
										<div class="form-group col-lg-6" id="previewOutput"></div>
										<div class="clearfix"></div>
									</fieldset>
									<input type="submit" class="finish btn btn-danger"
										id="confirm-btn" value="Confirm Slab" />

								</form>
								<br></br>

							</div>
						</div>
						<div class="space15"></div>
						<div class="adv-table editable-table">
							<section id="flip-scroll">
								<table class="table table-striped table-hover table-bordered cf"
									id="slab-table" style="display: none">
									<thead class="cf">
										<tr id="slab-table-head">
											<th>Slab Id</th>
											<th>Slab Name</th>
											<th>Based On</th>
										</tr>
									</thead>
									<tbody>
										<tr>
										</tr>
									</tbody>
								</table>
							</section>
						</div>
					</div>
				</section>
				<!-- page end-->
			</section>
		</section>
         
   <?php include_once (__DIR__."/footer.php");?>
   
      <!--footer end-->
	</section>

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="../js/jquery-2.1.4.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script class="include" type="text/javascript"
		src="../js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="../js/jquery.scrollTo.min.js"></script>
	<script src="../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/DT_bootstrap.js"></script>
	<script src="../js/respond.min.js"></script>

	<!--common script for all pages-->
	<script src="../js/common-scripts.js"></script>

	<!--script for this page only-->
	<script src="../js/ZeroClipboard.js"></script>
	<script src="../js/jquery.stepy.js"></script>
	<script src="../js/TableTools.js"></script>
	<!-- END JAVASCRIPTS -->
	<script src="../js/bootstrap-dialog.js"></script>
	<script type="text/javascript">
      $(document).ready(function () {
          $('#slabAddForm')[0].reset();
          loadAllowances(function (output) {
              EditableTable.init(output);
              $('#slab-table').show();

          });
          $(function () {
              $('#slabAddForm').stepy({
                  backLabel: 'Previous',
                  block: true,
                  nextLabel: 'Next',
                  titleClick: false,
                  titleTarget: '.stepy-tab'
              });
              //   $('#slabAddForm-next-0').prop('disabled', true);
              $('#slabAddForm-next-0').hide();
          });
      });

      //function to load allowances text box
     function loadAllowances(callBack) {
              $.ajax({
                  type: "POST",
                  url: "php/getPayStructure.php",
                  cache: false,
                  data:{type1:'A',type2:'D',},
                  success: function (data) {
                      data = JSON.parse(data);
                      var totalAllowances = 0;
                      var dataTableColumns = {};
                      var aoColumns = [{ 'sName': 'slab_id' }, { 'sName': 'slab_name' }, { 'sName': 'slab_type'}];
                      var allowancesArr = ['slab_id', 'slab_name', 'slab_type'];
                      $('#allowances_list').empty();
                      for (lc = 0; lc < data['type1'].length; lc++) {
                          if (data['type1'][lc]['display_flag'] == 1) {
                              totalAllowances = totalAllowances + 1;
                              aoColumns.push({ 'sName': data['type1'][lc]["pay_structure_id"] });
                              allowancesArr.push(data['type1'][lc]["pay_structure_id"]);
                              //table headers
                              $('#slab-table-head').append('<th>' + data['type1'][lc]["display_name"] + '</th>');
                              //text boxes
                              $('#allowances_list').append('<div class="form-group col-lg-6">\
                                                              <label for="' + data['type1'][lc]["pay_structure_id"] + '" class="col-lg-5 col-sm-5 control-label">' + data['type1'][lc]["display_name"] + '</label>\
                                                              <div class="input-group col-lg-7">\
                                                                          <span class="input-group-addon">\
                                                                          <input type="checkbox" data-totalAllowances=""  data-allowancesId="' + eval(data['type1'][lc]['sort_order'] + 1) + '" class="valueCheckbox" name="' + data['type1'][lc]["pay_structure_id"] + '_checkBox" value="A">\
                                                                          </span>\
                                                                        <input type="text" class="form-control inputBox" maxlength="5" id="' + data['type1'][lc]["pay_structure_id"] + '" name="' + data['type1'][lc]["pay_structure_id"] + '" value="0" placeholder="Enter Percentage" autocomplete="off" required>\
                                                                     <span class="txt input-group-addon">\
                                                                          %\
                                                                          </span>\
                                                                     </div>\
                                                                </div>');
                          }
                      }
                      $('#slab-table-head').append('<th>MinSalary</th>');
                      $('#slab-table-head').append('<th>Actions</th>');
                      allowancesArr.push('min_salary_amount', 'enabled');
                      aoColumns.push({ 'sName': 'min_salary_amount' });
                      aoColumns.push({ 'sName': 'enabled', 'bSortable': false });
                      eventsForBoxes(true);
                      $('.valueCheckbox').attr('data-totalAllowances', totalAllowances);
                      $('#totalAllowances').val(totalAllowances);
                      dataTableColumns['columns'] = allowancesArr;
                      dataTableColumns['aoColumns'] = aoColumns;
                      callBack(dataTableColumns);
                  }
              });
          }
          $('#showhide').on('click', function (event) {
              $('#add-slab').toggle('show');
          });
          //if based on basic is clicked then hide the basic
          $('input[name =sType]').change('click', function () {
              if ($(this).val() == 'basic') {
                  $('#previewLabel').html("Basic");
                  $('#allowances_list').children().find('.valueCheckbox').removeAttr('checked');
                  $('#allowances_list').children(":last").find('.inputBox').removeAttr('readonly').val(0).prop('maxlength', '5');
                  $('#allowances_list').children(":last").find('.valueCheckbox').removeAttr('checked');
                  $('#basic').parent().parent().remove();

                  eventsForBoxes(false);
                  eventsForBasicBoxes(true);
              } else {
            	  $('#allowances_list').children().find('.valueCheckbox').removeAttr('checked');
                  $('#previewLabel').html("Gross Salary");
                  $('#allowances_list').prepend('<div class="form-group col-lg-6">\
                                                              <label for="basic" class="col-lg-5 col-sm-5 control-label">Basic</label>\
                                                              <div class="input-group col-lg-7">\
                                                                          <span class="input-group-addon">\
                                                                          <input type="checkbox" data-totalAllowances=""  data-allowancesId="1" class="valueCheckbox" name="basic_checkBox" value="A">\
                                                                          </span>\
                                                                        <input type="text" class="form-control inputBox" maxlength="5" id="basic" name="basic" value="0" placeholder="Enter Percentage" required>\
                                                                     <span class="txt input-group-addon">\
                                                                          %\
                                                                          </span>\
                                                                     </div>\
                                                                </div>');
                  eventsForBoxes(false);
                  eventsForBoxes(true);
              }
          });
          function eventsForBasicBoxes(bindFlag) {
              if (bindFlag == true) {
                  $('#minimum_salary_div').hide();
                  $('#minimum_salary_amt').val("0");
                  $('#percentage_total_div').hide();
                  //$('#slabAddForm-next-0').removeAttr('disabled');
                  $('#slabAddForm-next-0').show();
                  $(".valueCheckbox").bind('click', function () {
                      if (this.checked) {
                          $(this).parent().parent().find('.inputBox').removeAttr('maxlength').prop('placeholder', 'Enter Amount').val(0);
                          $(this).parent().parent().find('span.txt').html('₹');
                      } else {
                          $(this).parent().parent().find('.inputBox').prop('maxlength', '5').prop('placeholder', 'Enter Percentage').val(0);
                          $(this).parent().parent().find('span.txt').html('%');
                      }
                  });
              } else {
                  $(".valueCheckbox").unbind('click');
                  $(".inputBox").unbind('blur');
              }

          }
          function eventsForBoxes(bindFlag) {
              if (bindFlag == true) {
                  $(".valueCheckbox").change('click', function () {
                  if($("input[type='radio'][name='sType']:checked").val()=='gross'){
                      if (this.checked) {
                          $(this).parent().parent().parent().parent().children(":last").find('.valueCheckbox').prop('checked', true).val('R');
                          $(this).parent().parent().find('span.txt').html('₹');
                          $(this).parent().parent().parent().parent().children(":last").find('.inputBox').prop('readonly', true).prop('placeholder', 'Remaining Amount').val("");
                          $(this).parent().parent().find('.inputBox').removeAttr('maxlength').prop('placeholder', 'Enter Amount').val(0);
                          $(this).parent().parent().parent().parent().children(":last").find("span.txt").html('₹');
                          if ($(this).attr('data-allowancesId') == $(this).attr('data-totalAllowances')) {
                              $(this).parent().parent().find('.inputBox').prop('readonly', true).prop('placeholder', 'Remaining Amount').val("");
                              $(this).parent().parent().parent().parent().children(":last").find('span.txt').html('₹');
                          }
                      } else{
                          $(this).parent().parent().parent().parent().children(":last").find('.valueCheckbox').removeAttr('disabled').removeAttr('checked');
                          $(this).parent().parent().find('span.txt').html('%');
                          $(this).parent().parent().parent().parent().children(":last").find("span.txt").html('%');
                          $(this).parent().parent().parent().parent().children(":last").find('.inputBox').removeAttr('readonly').prop('placeholder', 'Enter Percentage').val(0);
                          $(this).parent().parent().find('.inputBox').prop('maxlength', '5').prop('placeholder', 'Enter Percentage').val(0);
                          $(this).parent().parent().parent().parent().children().each(function () {
                              if ($(this).find('.valueCheckbox').prop('checked')) {
                                  $(this).parent().children(":last").find('.valueCheckbox').prop('checked', true).val('R');
                                  $(this).parent().children(":last").find('.inputBox').prop('readonly', true).prop('placeholder', 'Remaining Amount').val("");
                                  $(this).parent().parent().parent().parent().children(":last").find('span.txt').html('%');
                              }
                          });

                      }
                  }
                  });


                  $(".inputBox").bind('blur', function () {
                      var totalValues = getTotalValues($(this));
                      if (totalValues['value'] == 0) {
                          $('#minimum_salary_div').hide();
                          $('#percentage_total').text(totalValues['percentage']);
                          $('#percentage_total_div').show();
                          if (totalValues['percentage'] == 100) {
                              // $('#slabAddForm-next-0').removeAttr('disabled');
                              $('#slabAddForm-next-0').show();
                          } else {
                              //$('#slabAddForm-next-0').prop('disabled', true);
                              $('#slabAddForm-next-0').hide();
                          }
                      } else {
                          $('#percentage_total_div').hide();
                          // $('#slabAddForm-next-0').removeAttr('disabled');
                          $('#slabAddForm-next-0').show();
                          var minSalary = commonFunctions.calcMinimumSalaryForSlab(totalValues['percentage'], totalValues['value'] + "");
                          $('#minimum_salary_amt').val(minSalary);
                          $('#minimum_salary').html("<i class='fa fa-rupee'></i> " + reFormateByNumber(minSalary));
                          $('#minimum_salary_div').show();
                      }
                  });
              } else {
                  $(".valueCheckbox").unbind('click');
                  $(".inputBox").unbind('blur');
              }

          }
          function getTotalValues(element) {
              var val = 0;
              var percentage = 0;
              element.parent().parent().parent().children().each(function () {
                  if ($(this).find('.valueCheckbox').prop('checked')) {
                      var temp = $(this).find('.inputBox').val() == "" ? 0 : $(this).find('.inputBox').val();
                      val = parseFloat(val) + parseFloat(temp);
                  } else {
                      percentage = parseFloat(percentage) + parseFloat($(this).find('.inputBox').val());
                  }
              });
              op = { 'percentage': percentage, 'value': val };
              return op;
          }
          var slabFunctions = {
              getFormDataForSlab: function (formData) { //0 - slab name 1 - based on 2 - no of allow 3 - minimum sal amt
            	  var totalAllow = formData[3]['value'];
                  if (formData[2]['value'] == 'basic') {
                      totalAllow = formData[3]["value"] - 1;
                  }
                  var obj = {};
                  var allowancesColumn = [];
                  var allowancesDisplayName = [];
                  obj[formData[0]["name"]] = formData[0]["value"];
                  obj[formData[1]["name"]] = formData[1]["value"];
                  obj[formData[2]["name"]] = formData[2]["value"];
                  obj[formData[3]["name"]] = totalAllow;
                  obj[formData[4]["name"]] = formData[4]["value"];
                  var obj2 = {}
                  for (i = 5; i < formData.length; i++) {
                      //iterate the allowances
                      //check whether allowance has amount check box selected
                      var result = $.grep(formData, function (e) { return e.name == "" + formData[i]['name'] + "_checkBox"; });
                      if (result.length == 0) {
                          //no check box
                          if ((formData[i]['name'].match(/_checkBox/g) || []).length > 0 || (formData[i]['name'].match(/previewInput/g) || []).length > 0) {
                              //skip loop for checkBox 
                              continue;
                          }
                          obj2[formData[i]["name"]] = "" + formData[i]['value'] + "|P";
                      } else if (result.length == 1) {
                          //if the allowances has check boxes
                          obj2[formData[i]["name"]] = "" + formData[i]['value'] != "" ? "" + formData[i]['value'] + "|A" : "R|A" + "";
                      }
                      allowancesColumn.push(formData[i]['name']);
                      $.extend(obj, obj2);
                  }
                  obj['allowancesColumn'] = allowancesColumn;
                  return obj;
              }
          }

          $('#previewInput').on('keyup keydown', function () {
              $('#previewOutput').empty();
              var label = [];
              $(this).parent().parent().parent().parent().children().find("label").each(function () {
                  if ($(this).html() !== "Slab Name" && $(this).html() !== "Based on" && $(this).html() !== "Percentage Total" && $(this).html() !== "Minimum Salary" && $(this).html() !== "Gross")
                      label.push($(this).html());
              });
              var result = commonFunctions.calculateAmountForSlabs(slabFunctions.getFormDataForSlab($('#slabAddForm').serializeArray()), $(this).val());
              var value = JSON.stringify(result);
              var res = value.replace(/[{""}]/g, '');
              var splited_c = res.split(",");
              var tr;
              var j = 2;
              for (var i = 0; i < splited_c.length; i++) {
                  tr = "";
                  var splited_value = splited_c[i].split(":");
                  tr += "<div class='form-group  clg-lg-12'><label class='col-lg-5 col-sm-5 control-label'>" + label[j] + "</label><div class='col-lg-7'><input class='form-control col-lg-5 col-sm-5' value=" + splited_value[1] + " type='text' readonly/><br></div></div>";
                  $('#previewOutput').append(tr);
                  j++;

              }
              var tr = "</table>";


          });
          var EditableTable = function () {
        	    return {

        	        //main function to initiate the module
        	        init: function (allowData) {

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
       	                  jqTds[1].innerHTML = '<input type="text" class="form-control small" required  maxlength="20" value="' + aData[1] + '">';
       	                  jqTds[jqTds.length-1].innerHTML = '<a class="edit" href=""  title="Save" data-actions="Save" ><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>&nbsp;<a class="cancel" href="" title="Cancel"><button class="btn btn-danger btn-xs" ><i class="fa fa-times"></i></button></a>';
       	                 }

       	              function saveRow(oTable, nRow) {
       	                  var jqInputs = $('input', nRow);
       	                  var jqTds = $('>td', nRow);
    	                 oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
       	                   oTable.fnUpdate('<a class="edit" href="" title="Save"><button class="btn btn-success btn-xs" ><i class="fa fa-check"></i></button></a>', nRow, jqTds.length-1, false);
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

        	            $(document).on('click', "#slab-table a.edit", function (e) {
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
      	                      if(data[1] == '')
      	                      {
      	                      editRow(oTable, nRow);
   	                          nEditing = nRow;
   	                          }else{
      	                    $.ajax({
                   		                    dataType: 'html',
                   		                    type: "POST",
                   		                    url: "php/allowanceSlab.handle.php",
                   		                    cache: false,
                   		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!update");?>', s_id: data[0] , sName: data[1] },
                   		                    success: function (data) {
                   		                    	data = JSON.parse(data);
                   		                        if (data[0] == "success") {
                   		                        	oTable.fnDraw();
                   		                        	BootstrapDialog.alert(data[1]);
                   		                         }else if (data[0] == "error") {
              		                                    alert(data[1]);
              		                                }
                   		                    }
                   		                });
                          }
      	                      }else {
        	                      /* No edit in progress - let's start one */
        	                      editRow(oTable, nRow);
        	                      nEditing = nRow;
        	                  }
  	                       });
	                       
        	            $(document).on('click', "#slab-table  a.cancel", function (e) {
      	                  e.preventDefault();
      	                  if ($(this).attr("data-mode") == "new") {
      	                      var nRow = $(this).parents('tr')[0];
      	                      oTable.fnDeleteRow(nRow);
      	                  } else {
      	                      restoreRow(oTable, nEditing);
      	                      nEditing = null;
      	                  }
      	              });
        	              
        	              
        	            $(document).on('click', "#slab-table a.enable", function (e) {
        	                e.preventDefault();
        	                var nRow = $(this).parents('tr')[0];
        	                var data = oTable.fnGetData(nRow);
        	                var s_id = data[0];
        	                var s_name = data[1];
        	                BootstrapDialog.show({
              	                title:'Confirmation',
                                  message: 'Are Sure you want to Enable <strong>'+ s_name +'</strong>',
                                  buttons: [{
                                      label: 'Enable',
                                      cssClass: 'btn-primary',
                                      autospin: true,
                                      action: function(dialogRef){
                                      	 $.ajax({
                   		                    dataType: 'html',
                   		                    type: "POST",
                   		                    url: "php/allowanceSlab.handle.php",
                   		                    cache: false,
                   		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!enable");?>', s_id: s_id },
                   		                   complete:function(){
                 		                    	 dialogRef.close();
                 		                      },
                   		                    success: function (data) {
                   		                    	data = JSON.parse(data);
                   		                        if (data[0] == "success") {
                   		                        	oTable.fnDraw();
                   		                        	BootstrapDialog.alert(data[1]);
                   		                            
                   		                        }else if (data[0] == "error") {
              		                                    alert(data[1]);
              		                                }
                   		                    }
                   		                });
                                              		                            
                                      }
                                  }, {
                                      label: 'Close',
                                      action: function(dialogRef){
                                          dialogRef.close();
                                      }
                                  }]
                              });
        	            });


        	            $(document).on('click', "#slab-table a.disable", function (e) {
        	                e.preventDefault();
        	                var nRow = $(this).parents('tr')[0];
        	                var data = oTable.fnGetData(nRow);
        	                var e_s_id = data[0];
        	                var e_s_name = data[1];
        	                BootstrapDialog.show({
              	                title:'Confirmation',
                                  message: 'Are Sure you want to Disable <strong>'+ e_s_name +'</strong>',
                                  buttons: [{
                                      label: 'Disable',
                                      cssClass: 'btn-primary',
                                      autospin: true,
                                      action: function(dialogRef){
                                      	 $.ajax({
                   		                    dataType: 'html',
                   		                    type: "POST",
                   		                    url: "php/allowanceSlab.handle.php",
                   		                    cache: false,
                   		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!disable");?>', s_id: e_s_id },
                   		                   complete:function(){
                 		                    	 dialogRef.close();
                 		                      },
                   		                    success: function (data) {
                   		                    	data = JSON.parse(data);
                   		                        if (data[0] == "success") {
                   		                        	oTable.fnDraw();
                   		                        	BootstrapDialog.alert(data[1]);
                   		                            
                   		                        }else if (data[0] == "error") {
              		                                    alert(data[1]);
              		                                }
                   		                    }
                   		                });
                                              		                            
                                      }
                                  }, {
                                      label: 'Close',
                                      action: function(dialogRef){
                                          dialogRef.close();
                                      }
                                  }]
                              });
        	            });


        	              $(document).on('click', "#slab-table a.delete", function (e) {
        	                e.preventDefault();
        	                var nRow = $(this).parents('tr')[0];
        	                var data = oTable.fnGetData(nRow);
        	                var d_id = data[0];
        	                var d_name = data[1];
        	                BootstrapDialog.show({
              	                title:'Confirmation',
                                  message: 'Are Sure you want to Delete <strong>'+ d_name +'</strong>',
                                  buttons: [{
                                      label: 'Delete',
                                      cssClass: 'btn-primary',
                                      autospin: true,
                                      action: function(dialogRef){
                                      	 $.ajax({
                   		                    dataType: 'html',
                   		                    type: "POST",
                   		                    url: "php/allowanceSlab.handle.php",
                   		                    cache: false,
                   		                    data: { act: '<?php echo base64_encode($_SESSION['company_id']."!delete");?>', s_id: d_id },
                   		                   complete:function(){
                 		                    	 dialogRef.close();
                 		                      },
                   		                    success: function (data) {
                   		                    	data = JSON.parse(data);
                   		                    	if (data[3] == "1") {
                   		                        	oTable.fnDraw();
                   		                        	BootstrapDialog.alert(data[1]);
                   		                            
                   		                        }else {
                   		                        	BootstrapDialog.alert("Oops ! Cant Be Deleted Its Mapped with Employee Profile");
              		                                }
                   		                    }
                   		                });
                                              		                            
                                      }
                                  }, {
                                      label: 'Close',
                                      action: function(dialogRef){
                                          dialogRef.close();
                                      }
                                  }]
                              });
        	            });
        	            
        	            
        	            $('#slabAddForm').on('submit', function (e) {
        	                e.preventDefault();
        	                $.ajax({
        	                	 dataType: 'html',
        	                     type: "POST",
        	                     url: "php/allowanceSlab.handle.php",
        	                     cache: false,
        	                     data: slabFunctions.getFormDataForSlab($(this).serializeArray()),
        	                     beforeSend:function(){
        	                     	$('#confirm-btn').button('loading'); 
        	                      },
        	                      complete:function(){
        	                     	 $('#confirm-btn').button('reset');
        	                      },
        	                    success: function (data) {
        	                    	data= JSON.parse(data);
        	                    	
        	                        if (data[0]== "success") {
            	                        oTable.fnDraw();
        	                            $('#slabAddForm')[0].reset();
        	                            $('#add-slab').toggle('hide');
        	                            $('#percentage_total').html('');
        	                            $('#slabAddForm-back-1').click();
        	                           $('#previewOutput').html('');
        	                           BootstrapDialog.alert(data[1]);
        	                           $('#based_on1').click();
        	                           
        	                        } 
        	                    }

        	                });
        	            });
        	        }

        	    };

        	} ();

	</script>


</body>
</html>
