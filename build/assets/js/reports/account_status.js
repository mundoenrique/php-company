'use strict'
var reportsResults;
$(function () {

	var datePicker = $('.date-picker');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$("#allResults").prop( "checked", true );
	$('#blockResults').addClass('hidden');

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
		$('#blockMessage').val('');
		$('#resultByNITInput').val('');
		if ($("input[name='results']:checked").val() == 'all') {
			$('#resultByNITInput').addClass('visible');
		} else {
			$('#resultByNITInput').removeClass('visible');
		}
	});

	$("#searchButton").on("click", function (e){
		e.preventDefault();
		var form = $('#statusAccountForm');
		var data = getDataForm(form);
		$('#spinnerBlock').addClass('hide');
		$('#blockResults').addClass('hidden');
		if($('#resultByNITInput').val() != ''){
			data.resultByNIT = data.resultByNITInput;
		}else {
			data.resultByNIT = data.allResults;
		}
		delete data.allResults;
		delete data.resultByNITInput;

		validateForms(form);

		if (form.valid()) {
			$('#spinnerBlock').removeClass('hide');
			insertFormInput(true, form);
			searchStatusAccount(data);
		}
	});

	$("#export_excel").click(function(e){
		exportFile(e);
	});

	$("#export_pdf").click(function(e){
		exportFile(e);
	});
});

function searchStatusAccount(passData){
	$('#account-status-table').empty();
	$('#export_excel').addClass('hidden');
	$('#blockResults').removeClass('hidden');
	who = 'Reports';
	where = 'searchStatusAccount';
	data = passData;
	insertFormInput(true);

	callNovoCore(who, where, data, function (response) {
		dataResponse = response.data.listStatesAccounts;
		$('#spinnerBlock').addClass('hide');
		var table = '';

		if (dataResponse != '') {
			$('#export_excel').removeClass('hidden');
		}

		$.each(dataResponse, function (index, value) {
			if (value) {
			  table += '<div class=""><div class="flex ml-4 py-3 flex-auto">';
				table +=	'<p class="mr-5 h5 semibold tertiary">'+ lang.GEN_TABLE_DNI + ': <span class="light text">'+ dataResponse[index].id +'</span></p><p class="mr-5 h5 semibold tertiary">Tarjeta: <span class="light text">'+ dataResponse[index].account +'</span></p><p class="mr-5 h5 semibold tertiary">Nombre: <span class="light text">'+ dataResponse[index].client +'</span></p></div>';
			}
				table += '<table class="result-account-status cell-border h6 display responsive w-100">';
				table += '<thead class="bg-primary secondary regular">';
				table += '<tr>';
				table += '<th>'+ lang.REPORTS_TABLE_DATE +'</th>';
				table += '<th>'+ lang.REPORTS_ACCOUNT_FID +'</th>';
				table += '<th>'+ lang.REPORTS_ACCOUNT_TERMINAL +'</th>';
				table += '<th>'+ lang.REPORTS_ACCOUNT_SECUENCE +'</th>';
				table += '<th>'+ lang.REPORTS_ACCOUNT_REFERENCE +'</th>';
				table += '<th>'+ lang.REPORTS_ACCOUNT_DESCRIPTION +'</th>';
				table += '<th>'+ lang.REPORTS_ACCOUNT_DEBIT +'</th>';
				table += '<th>'+ lang.REPORTS_ACCOUNT_CREDIT +'</th>';
				table += '</tr>';
				table += '</thead>';
				table += '<tbody>';

			if (value) {
				$.each(dataResponse[index].listMovements, function (index2, value2) {
					table += '<tr>';
					table += '<td>' + value2.date + '</td>';
					table += '<td>' + value2.fid + '</td>';
					table += '<td>' + value2.terminal + '</td>';
					table += '<td>' + value2.secuence + '</td>';
					table += '<td>' + value2.reference + '</td>';
					table += '<td>' + value2.description + '</td>';
					table += '<td>' + value2.debit + '</td>';
					table += '<td>' + value2.credit + '</td>';
					table += '</tr>';
				});
			}
				table += '</tbody>';
				table += '</table>';
		});

		$('#account-status-table').append(table);
		$('.result-account-status').DataTable({
			"ordering": false,
			"responsive": true,
			"lengthChange": false,
			"pagingType": "full_numbers",
			"columns": [
				{ data: 'date' },
				{ data: 'fid' },
				{ data: 'terminal' },
				{ data: 'secuence' },
				{ data: 'reference' },
				{ data: 'description' },
				{ data: 'debit' },
				{ data: 'credit' },
			],
			"columnDefs": [
				{
					"targets": 0,
					"className": "date",
					"visible": lang.CONF_DATE_COLUMN == "ON"
				},
				{
					"targets": 1,
					"className": "fid",
					"visible": lang.CONF_DNI_COLUMN == "ON"
				},
				{
					"targets": 2,
					"className": "terminal",
					"visible": lang.CONF_TERMINAL_COLUMN == "ON"
				},
				{
					"targets": 3,
					"className": "secuence",
					"visible": lang.CONF_SECUENCE_COLUMN == "ON"
				},
				{
					"targets": 4,
					"className": "reference",
					"visible": lang.CONF_REFERENCE_COLUMN == "ON"
				},
				{
					"targets": 5,
					"className": "description",
					"visible": lang.CONF_DESCRIPTION_COLUMN == "ON"
				},
				{
					"targets": 6,
					"className": "debit",
					"visible": lang.CONF_DEBIT_COLUMN == "ON"
				},
				{
					"targets": 7,
					"className": "credit",
					"visible": lang.CONF_CREDIT_COLUMN == "ON"
				}
			],
			"language": dataTableLang,
		});

		$('.result-account-status').removeClass('hidden');
		insertFormInput(false);
	});
};

function exportFile(e){
	e.preventDefault();
	var event = $(e.currentTarget);
	var action = event.attr('title');
	var form = $('#statusAccountForm');
	var data = getDataForm(form);
	data.enterpriseName = $('#enterpriseCode').find('option:selected').attr('name');
	data.descProduct= $('#productCode').find('option:selected').attr('doc');
	data.resultByNIT = $('#resultByNITInput').val();
	delete data.allResults;
	delete data.resultByNITInput;

	switch(action) {
		case 'Exportar a EXCEL':
			validateForms(form);
			if (form.valid()) {
				exportToExcel(data);
			}
			break;
		case 'Exportar a PDF':
			validateForms(form);
			if (form.valid()) {
				exportToPDF(data);
			}
			break;
	}
};

function exportToExcel(passData) {
	who = 'Reports';
	where = 'statusAccountExcelFile';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if (info.formatoArchivo == 'excel') {
			info.formatoArchivo = '.xls'
		}
		$('#spinnerBlock').addClass('hide');
		if (code == 0) {
			data = {
				"name": info.nombre.replace(/ /g, "")+info.formatoArchivo,
				"ext": info.formatoArchivo,
				"file": info.archivo
			}
			downLoadfiles (data);
		  $('.cover-spin').removeAttr("style");
	  }
  })
};

function exportToPDF(passData) {
	who = 'Reports';
	where = 'statusAccountPdfFile';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if (info.formatoArchivo == 'PDF') {
			info.formatoArchivo = '.pdf'
		}
		$('#spinnerBlock').addClass('hide');
		if (code == 0) {
			data = {
				"name": info.nombre.replace(/ /g, "")+info.formatoArchivo,
				"ext": info.formatoArchivo,
				"file": info.archivo
			}
			downLoadfiles (data);
			$('.cover-spin').removeAttr("style");
		}
  })
};
