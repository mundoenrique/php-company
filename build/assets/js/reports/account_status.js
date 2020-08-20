'use strict'
var reportsResults;
$(function () {
	var datePicker = $('.date-picker');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$("#allResults").prop( "checked", true );
	$('#blockResults').addClass('hidden');

	datePicker.datepicker({
		onSelect: function (selectedDate) {
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[0] + '/' + dateSelected[2];
		},
		changeMonth: true,
		changeYear: true,
		dateFormat: 'mm/yy',
	});

	$(":radio").on("change", function (e) {
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
	verb = 'POST'; who = 'Reports'; where = 'searchStatusAccount'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code

		insertFormInput(false);

		$('#spinnerBlock').addClass('hide');
		if (code == 0) {
			$('#blockResults').removeClass('hidden');
			var table= $("#globalTable").DataTable();
			var personalizeRowsInfo = []
			table.destroy();
			$.each(dataResponse.accounts, function (key, value, index) {
				var table, body = '';
				table= '<div class=""><div class="flex ml-4 py-3 flex-auto">'
				table+=	'<p class="mr-5 h5 semibold tertiary">Nombre: <span class="light text">'+dataResponse.accounts[key].cliente+'</span></p><p class="mr-5 h5 semibold tertiary">Tarjeta: <span class="light text">'+(dataResponse.user[key])[0].tarjeta+'</span></p><p class="mr-5 h5 semibold tertiary">Cédula: <span class="light text">'+dataResponse.accounts[key].idExtPer+'</span></p></div>'
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
			$.each(dataResponse.user, function (key1, value, index) {
				$.each(dataResponse.user[key1], function (key2, value, index) {
				if((dataResponse.user[key1])[key2].tipoTransaccion == '-'){
					(dataResponse.user[key1])[key2].abono = (dataResponse.user[key1])[key2].monto;
					(dataResponse.user[key1])[key2].cargo = '0.00';
				}else{
					(dataResponse.user[key1])[key2].abono = '0.00';
					(dataResponse.user[key1])[key2].cargo = (dataResponse.user[key1])[key2].monto;
					}
				})
			})

			$.each(dataResponse.user, function (key, value, index) {
				createTable(dataResponse.user, key);
			})
		}
	});
};

function createTable(data, index){
	$('#resultsAccount'+ index).DataTable({
		"ordering": false,
		"responsive": true,
		"lengthChange": false,
		"pagingType": "full_numbers",
		"data": data[index],
		"columns": [
			{ data: 'fecha' },
			{ data: 'fid' },
			{ data: 'terminalTransaccion' },
			{ data: 'secuencia' },
			{ data: 'referencia' },
			{ data: 'descripcion' },
			{ data: 'abono' },
			{ data: 'cargo' },
		],
		"columnDefs": [
			{
				"targets": 0,
				"className": "fecha",
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
				"className": "secuencia",
				"visible": lang.CONF_SECUENCE_COLUMN == "ON"
			},
			{
				"targets": 4,
				"className": "referencia",
				"visible": lang.CONF_REFERENCE_COLUMN == "ON"
			},
			{
				"targets": 5,
				"className": "descripcion",
				"visible": lang.CONF_DESCRIPTION_COLUMN == "ON"
			},
			{
				"targets": 6,
				"className": "abono",
				"visible": lang.CONF_DEBIT_COLUMN == "ON"
			},
			{
				"targets": 7,
				"className": "cargo",
				"visible": lang.CONF_CREDIT_COLUMN == "ON"
			}
		],
		"language": dataTableLang
	});
}

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
	verb = "POST"; who = 'Reports'; where = 'statusAccountExcelFile'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if (info.formatoArchivo == 'excel') {
			info.formatoArchivo = '.xls'
		}
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
	verb = "POST"; who = 'Reports'; where = 'statusAccountPdfFile'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if (info.formatoArchivo == 'PDF') {
			info.formatoArchivo = '.pdf'
		}
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
