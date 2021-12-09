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
	who = 'Reports';
	where = 'searchStatusAccount';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		insertFormInput(false);
		$('#spinnerBlock').addClass('hide');

		if (code == 0) {
			$('#export_excel').removeClass('hidden');
			$('#blockResults').removeClass('hidden');
			var table= $("#globalTable").DataTable();
			var personalizeRowsInfo = []
			table.destroy();
			$.each(dataResponse.accounts, function (key, value, index) {
				var table, body = '';
				table= '<div class=""><div class="flex ml-4 py-3 flex-auto">'
				table+=	'<p class="mr-5 h5 semibold tertiary">'+ lang.GEN_TABLE_DNI + ': <span class="light text">'+ dataResponse.accounts[key].id +'</span></p><p class="mr-5 h5 semibold tertiary">Tarjeta: <span class="light text">'+ dataResponse.accounts[key].account +'</span></p><p class="mr-5 h5 semibold tertiary">Nombre: <span class="light text">'+ dataResponse.accounts[key].client +'</span></p></div>'
				table+= 	'<table id="resultsAccount'+  key + '" class="cell-border h6 display responsive w-100">';
				table+= 	'<thead class="bg-primary secondary regular">';
				table+= 		'<tr class="" style="margin-left: 0px;">';
				table+= 			'<td>Fecha</td>';
				table+= 			'<td>Fid</td>';
				table+= 			'<td>Terminal</td>';
				table+= 			'<td>Secuencia</td>';
				table+= 			'<td>Referencia</td>';
				table+= 			'<td>Descripción</td>';
				table+= 			'<td>Abono</td>';
				table+= 			'<td>Cargo</td>';
				table+= 		'</tr>';
				table+= 	'</thead>';
				table+= 		'<tbody></tbody>'
				table+= '</table>';

				personalizeRowsInfo[key] = table;
			})

			$("#globalTable").html(personalizeRowsInfo);
			$.each(dataResponse.users, function (key, value, index) {
				var name = '#resultsAccount';
				createTable(name, dataResponse.users, key);
			})
		}else if(code == 1){
			$('#blockResults').removeClass('hidden');
			$('#export_excel').addClass('hidden');
			var principalTable= $("#globalTable").DataTable();
			principalTable.destroy();
			var data = [];
			var name = '#globalTable';
			var table, key = '';
			table= '<div class="">'
			table+= 	'<table id="resultsAccount" class="cell-border h6 display responsive w-100">';
			table+= 	'<thead class="bg-primary secondary regular">';
			table+= 		'<tr class="" style="margin-left: 0px;">';
			table+= 			'<td>Fecha</td>';
			table+= 			'<td>Fid</td>';
			table+= 			'<td>Terminal</td>';
			table+= 			'<td>Secuencia</td>';
			table+= 			'<td>Referencia</td>';
			table+= 			'<td>Descripción</td>';
			table+= 			'<td>Abono</td>';
			table+= 			'<td>Cargo</td>';
			table+= 		'</tr>';
			table+= 	'</thead>';
			table+= 		'<tbody></tbody>'
			table+= '</table>';
			$("#globalTable").html(table);
			createTable(name, data, key);
		}

	});
};

function createTable(name, data, index){
	$(name + index).DataTable({
		"ordering": false,
		"responsive": true,
		"lengthChange": false,
		"pagingType": "full_numbers",
		"data": data[index],
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
		"language": dataTableLang
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
