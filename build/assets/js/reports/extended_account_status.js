'use strict'

$(function () {

	var form = $('#extStatusAccountForm');
	var formXls = $('#extStatusAccountFormXls');
	$('#resultByNITInput').val('');

	var datePicker = $('.date-picker');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$("#allResults").prop( "checked", true );
	$('#blockResults').addClass('hidden');
	$('#resultByNITInput').addClass('ignore');

	datePicker.datepicker({
		dateFormat: 'mm/yy',
		showButtonPanel: true,
		onSelect: function(selectDate){
			$(this)
				.focus()
				.blur();
		},
		onClose: function (dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
		},
		beforeShow: function (input, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			inst.dpDiv.addClass("ui-datepicker-month-year");
			$(this).datepicker('setDate', new Date(year, month, 1));
		}
	});

	$(":radio").on("change", function (e) {
		$('#resultByNITInput').val('');
		if (($('#allResults:checked').val() == 'on')) {
			$('#resultByNITInput').addClass('visible').removeClass('has-error').removeAttr('aria-describedby').addClass('ignore');
			$("#blockMessage").text('');
		}
		else if (($('#resultByNIT:checked').val() == 'on')) {
			$('#resultByNITInput').removeClass('visible').removeClass('has-error').removeAttr('aria-describedby').removeClass('ignore').attr("maxlength",lang.VALIDATE_MAXLENGTH_IDEXTPER);
			$("#blockMessage").text('');
		}
	});

	$("#searchButton").on("click", function (e){
		e.preventDefault();
		var data = getDataForm(form);
		$('#extAccountStatusTable').DataTable().destroy();
		$('#blockResults').addClass('hidden');
		$('#export_excel').addClass('hidden');

		if(($('#allResults:checked').val() == 'on')){
			data.resultSearch = 0;
		}
		else if(($('#resultByNIT:checked').val() == 'on')){
			data.resultSearch = 1;
		}

		delete data.allResults;
		delete data.resultByNIT;

	  $('#enterpriseNameXls').val($('#enterpriseCode').find('option:selected').attr('name'));
		$('#descProductXls').val($('#productCode').find('option:selected').attr('doc'));
		$('#resultByNITXls').val(data.resultByNITInput);
		$('#enterpriseCodeXls').val(data.enterpriseCode);
		$('#productCodeXls').val(data.productCode);
		$('#initialDateActXls').val(data.initialDateAct);
		$('#resultSearchXls').val(data.resultSearch);

		validateForms(form);
		if (form.valid()) {
			$('#spinnerBlock').removeClass('hide');
			insertFormInput(true, form);
			searchExtAccountStatus(data);
		}
	});

	$("#export_excel").click(function(){
		validateForms(formXls);
		if (formXls.valid()) {
			var dataXls = getDataForm(formXls);
			extendedDownloadFiles(dataXls);
		}
	});
});

function searchExtAccountStatus(dataForm) {
	$('#extAccountStatusTable').DataTable({
		drawCallback: function (d) {
			insertFormInput(false)
			$('#blockResults').removeClass('hidden');
			$('#spinnerBlock').addClass('hide');
		},
		"autoWidth": false,
		"ordering": false,
		"searching": false,
		"lengthChange": false,
		"pagelength": 10,
		"pagingType": "full_numbers",
		"table-layout": "fixed",
		"language": dataTableLang,
		"processing": true,
		"serverSide": true,
		"columns": [
      { "data": 'fecha' },
      { "data": 'tarjeta' },
      { "data": 'cliente' },
      { "data": 'idExtPer' },
      { "data": 'referencia' },
      { "data": 'descripcion'},
      { "data": 'tipoTransaccion' },
      { "data": 'monto' }
    ],
		"columnDefs": [
			{"targets": 0, "className": "fecha"},
			{"targets": 1, "className": "tarjeta"},
			{"targets": 2, "className": "cliente", "width": "160px",},
			{"targets": 3, "className": "idExtPer"},
			{"targets": 4, "className": "referencia"},
			{"targets": 5, "className": "descripcion", "width": "260px",},
			{"targets": 6, "className": "tipoTransaccion", "width": "110px"},
			{"targets": 7, "className": "monto"}
		],
		"ajax": {
			url: baseURL + 'async-call',
			method: 'POST',
			dataType: 'json',
			cache: false,
			data: function (req) {
				data = req
				data.enterpriseCode = dataForm.enterpriseCode;
				data.productCode = dataForm.productCode;
				data.initialDateAct = dataForm.initialDateAct;
				data.resultByNITInput = dataForm.resultByNITInput;
				data.resultSearch = dataForm.resultSearch;
				data.screenSize = screen.width;
				data.paginar = true;
				var dataRequest = JSON.stringify({
					who: 'Reports',
					where: 'searchExtendedAccountStatus',
					data: data
				});
				dataRequest = cryptoPass(dataRequest, true);
				var request = {
					request: dataRequest,
					ceo_name: ceo_cook,
					plot: btoa(ceo_cook)
				}
				return request
			},
			dataFilter: function (resp) {
				var responseTable = jQuery.parseJSON(resp);
				responseTable = JSON.parse(
					CryptoJS.AES.decrypt(responseTable.code, responseTable.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
				);
				if (responseTable.data.length > 0) {
					$('#export_excel').removeClass('hidden');
				}
				return JSON.stringify(responseTable);
			}
		}
	});
}

function extendedDownloadFiles(data) {
	insertFormInput(true);
	who = 'Reports';
	where = 'exportToExcelExtendedAccountStatus';
	callNovoCore(who, where, data, function (response) {

		if (response.code == 0) {
			$('#download-file').attr('href', response.data.file);
			document.getElementById('download-file').click();
			who = 'DownloadFiles';
			where = 'DeleteFile';
			data.fileName = response.data.name
			callNovoCore(who, where, data, function (response) { })
		}

		insertFormInput(false);
		$('.cover-spin').hide();
	})
}
