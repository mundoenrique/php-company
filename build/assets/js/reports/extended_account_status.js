'use strict'

$(function () {

	var form = $('#extStatusAccountForm');
	var formFileDownload = $('#extStatusAccountFormFileDownload');
	$('#resultByNITInput').val('');
	$('#resultByNameInput').val('');

	var datePicker = $('.date-picker');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$("#allResults").prop( "checked", true );
	$('#blockResults').addClass('hidden');
	$('#resultByNITInput').addClass('ignore');
	$('#resultByNameInput').addClass('ignore');

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
		$('#resultByNameInput').val('');
		if (($('#allResults:checked').val() == 'on')) {
			$('#resultByNITInput').addClass('visible').removeClass('has-error').removeAttr('aria-describedby').addClass('ignore');
			$('#resultByNameInput').addClass('visible').removeClass('has-error').removeAttr('aria-describedby').addClass('ignore');
			$("#blockMessage").text('');
		}
		else if (($('#resultByNIT:checked').val() == 'on')) {
			$('#resultByNITInput').removeClass('visible').removeClass('has-error').removeAttr('aria-describedby').removeClass('ignore').css("display", "block").attr("maxlength",lang.VALIDATE_MAXLENGTH_IDEXTPER);
			$('#resultByNameInput').addClass('visible').removeClass('has-error').removeAttr('aria-describedby').addClass('ignore').css("display", "none");
			$("#blockMessage").text('');
		}
		else if (($('#resultByName:checked').val() == 'on')) {
			$('#resultByNITInput').addClass('visible').removeClass('has-error').removeAttr('aria-describedby').addClass('ignore').css("display", "none");
			$('#resultByNameInput').removeClass('visible').removeClass('has-error').removeAttr('aria-describedby').removeClass('ignore').css("display", "block").attr("maxlength",lang.VALIDATE_MAXLENGTH);
			$("#blockMessage").text('');
		}
	});

	$("#searchButton").on("click", function (e){
		e.preventDefault();
		var data = getDataForm(form);
		$('#extAccountStatusTable').DataTable().destroy();
		$('#blockResults').addClass('hidden');
		$('#export_excel').addClass('hidden');
		$('#export_txt').addClass('hidden');

		if(($('#allResults:checked').val() == 'on')){
			data.resultSearch = 0;
		}
		else if(($('#resultByNIT:checked').val() == 'on')){
			data.resultSearch = 1;
		}
		else if(($('#resultByName:checked').val() == 'on')){
			data.resultSearch = 3;
		}

		delete data.allResults;
		delete data.resultByNIT;
		delete data.resultByName;

	  $('#enterpriseNameFileDownload').val($('#enterpriseCode').find('option:selected').attr('name'));
		$('#descProductFileDownload').val($('#productCode').find('option:selected').attr('doc'));
		$('#resultByNITFileDownload').val(data.resultByNITInput);
		$('#resultByNameFileDownload').val(data.resultByNameInput);
		$('#enterpriseCodeFileDownload').val(data.enterpriseCode);
		$('#productCodeFileDownload').val(data.productCode);
		$('#initialDateActFileDownload').val(data.initialDateAct);
		$('#resultSearchFileDownload').val(data.resultSearch);


		validateForms(form);
		if (form.valid()) {
			$('#spinnerBlock').removeClass('hide');
			insertFormInput(true, form);
			searchExtAccountStatus(data);
		}
	});

	$("#export_excel").click(function(){
		validateForms(formFileDownload);
		if (formFileDownload.valid()) {
			var dataXls = getDataForm(formFileDownload)
			extendedDownloadFiles(dataXls, 'exportToExcelExtendedAccountStatus');
		}
	});

	$("#export_txt").click(function(){
		validateForms(formFileDownload);
		if (formFileDownload.valid()) {
			var dataXls = getDataForm(formFileDownload)
			extendedDownloadFiles(dataXls, 'exportToTxtExtendedAccountStatus');
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
      { "data": 'monto' },
      { "data": 'status' }
    ],
		"columnDefs": [
			{"targets": 0, "className": "fecha"},
			{"targets": 1, "className": "tarjeta"},
			{"targets": 2, "className": "cliente", "width": "160px",},
			{"targets": 3, "className": "idExtPer"},
			{"targets": 4, "className": "referencia"},
			{"targets": 5, "className": "descripcion", "width": "260px",},
			{"targets": 6, "className": "tipoTransaccion", "width": "110px"},
			{"targets": 7, "className": "monto"},
			{"targets": 8, "className": "status", "visible": lang.CONF_STATUS_MOVEMENT == "ON"}
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
				data.resultByNameInput = dataForm.resultByNameInput;
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
					$('#export_txt').removeClass('hidden');
				}
				return JSON.stringify(responseTable);
			}
		}
	});
}

function extendedDownloadFiles(data, service) {
	insertFormInput(true);
	who = 'Reports';
	where = service;
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
