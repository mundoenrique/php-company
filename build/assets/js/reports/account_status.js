'use strict'
var reportsResults;
$(function () {

	var datePicker = $('.date-picker');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$("#allResults").prop( "checked", true );
	$('#blockResults').addClass('hidden');
	$('#resultByNITInput').addClass('ignore');
	$('#resultByCardInput').addClass('ignore');

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
		$('#resultByCardInput').val('');
		if (($('#allResults:checked').val() == 'on')) {
			$("#resultByCardInput").attr("id","resultByNITInput").attr("name","radioDni").attr("maxlength",lang.VALIDATE_MAXLENGTH_IDEXTPER);
			$('#resultByNITInput').addClass('visible').removeClass('has-error').removeAttr('aria-describedby').addClass('ignore');
			$("#blockMessage2").attr("id","blockMessage");
			$("#blockMessage").text('');
		}
		else if (($('#resultByNIT:checked').val() == 'on')) {
			$("#resultByCardInput").attr("id","resultByNITInput").attr("name","radioDni");
			$('#resultByNITInput').removeClass('visible').removeClass('has-error').removeAttr('aria-describedby').removeClass('ignore').attr("maxlength",lang.VALIDATE_MAXLENGTH_IDEXTPER);
			$("#blockMessage2").attr("id","blockMessage");
			$("#blockMessage").text('');
		}
		else if (($('#resultByCard:checked').val() == 'on')) {
			$("#resultByNITInput").attr("id","resultByCardInput").attr("name","radioCard");
			$('#resultByCardInput').removeClass('visible').removeClass('has-error').removeAttr('aria-describedby').removeClass('ignore').attr("maxlength",lang.VALIDATE_MAXLENGTH_CARD);;
   		$("#blockMessage").attr("id","blockMessage2");
			$("#blockMessage2").text('');
		}
	});

	$("#searchButton").on("click", function (e){
		e.preventDefault();
		var form = $('#statusAccountForm');
		var data = getDataForm(form);
		$('#spinnerBlock').addClass('hide');
		$('#blockResults').addClass('hidden');

		if(($('#allResults:checked').val() == 'on')){
			data.resultSearch = 0;
		}
		else if(($('#resultByNIT:checked').val() == 'on')){
			data.resultSearch = 1;
		}
		else if(($('#resultByCard:checked').val() == 'on')){
			data.resultSearch = 2;
		}
		delete data.allResults;
		delete data.resultByNIT;
		delete data.resultByCard;

		validateForms(form);

		if (form.valid()) {
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
	$('#spinnerBlock').removeClass('hide');
	$('#account-status-table').empty();
	$('#export_excel').addClass('hidden');
	who = 'Reports';
	where = 'searchStatusAccount';
	data = passData;
	insertFormInput(true);

	callNovoCore(who, where, data, function (response) {
		dataResponse = response.data.listStatesAccountsNew;
		$('#spinnerBlock').addClass('hide');

		if (dataResponse != '') {
			$('#export_excel').removeClass('hidden');
		}

		if (response.code == 0){
			$('#blockResults').removeClass('hidden');

			paintTable(dataResponse);
			if (dataResponse[0].length >= lang.SETT_DATATABLE_ARRAY_CHUNK)
				$('#spinnerResults').removeClass('hide');

		};
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
	data.resultByNIT = ($('#resultByNITInput').val() === undefined) ? '' : $('#resultByNITInput').val()
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
		$('.cover-spin').hide();
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

function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

async function paintTable(dataResponse) {
	for (let i = 0; i < dataResponse.length; i++) {
		$.each(dataResponse[i], function (index, value) {
		var table = '';
				table = '<div class="hide-table'+ index +' hide">';
			if (value) {
				table += '<div class="">';
				table +=		'<div class="flex ml-4 py-3 flex-auto">';
				table +=			'<p class="mr-5 h5 semibold tertiary">'+ lang.GEN_TABLE_DNI +':';
				table +=				'<span class="light text">'+ dataResponse[i][index].id +'</span>';
				table +=			'</p>';
				table +=			'<p class="mr-5 h5 semibold tertiary">'+ lang.GEN_TABLE_COUNT +':';
				table +=				'<span class="light text">'+ dataResponse[i][index].account +'</span>';
				table +=			'</p>';
				table +=			'<p class="mr-5 h5 semibold tertiary">'+ lang.GEN_TABLE_NAME_CLIENT +':';
				table +=				'<span class="light text">'+ dataResponse[i][index].client +'</span>';
				table +=			'</p>';
				table +=		'</div>';
			}
				table += 		'<table class="result-account-status'+ index +' cell-border h6 display responsive w-100">';
				table += 			'<thead class="bg-primary secondary regular">';
				table += 				'<tr>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_CARD +'</th>';
				table += 					'<th>'+ lang.REPORTS_TABLE_DATE +'</th>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_FID +'</th>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_TERMINAL +'</th>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_SECUENCE +'</th>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_REFERENCE +'</th>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_DESCRIPTION +'</th>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_OPERATION +'</th>';
				table += 					'<th>'+ lang.REPORTS_ACCOUNT_AMOUNT +'</th>';
				table += 				'</tr>';
				table += 			'</thead>';
				table += 		'<tbody>';

			if (value) {
				$.each(dataResponse[i][index].listMovements, function (index2, value2) {
				table += 			'<tr>';
				table += 				'<td>' + value2.card + '</td>';
				table += 				'<td>' + value2.date + '</td>';
				table += 				'<td>' + value2.fid + '</td>';
				table += 				'<td>' + value2.terminal + '</td>';
				table += 				'<td>' + value2.secuence + '</td>';
				table += 				'<td>' + value2.reference + '</td>';
				table += 				'<td>' + value2.description + '</td>';
				table += 				'<td>' + value2.typeTransaction + '</td>';
				table += 				'<td>' + value2.amount + '</td>';
				table += 			'</tr>';
				});
			}
				table += 		'</tbody>';
				table += 	'</table>';
				table += '</div>';

			$('#account-status-table').append(table);
			$('.result-account-status'+ index).DataTable().destroy();
			$('.result-account-status'+ index).DataTable({
				drawCallback: function (d) {
					$('.hide-table'+index).removeClass('hide');
				},
				"ordering": false,
				"lengthChange": false,
				"pagingType": "full_numbers",
				"columns": [
					{ data: 'Tarjeta' },
					{ data: 'date' },
					{ data: 'fid' },
					{ data: 'terminal' },
					{ data: 'secuence' },
					{ data: 'reference' },
					{ data: 'description' },
					{ data: 'typeTransaction' },
					{ data: 'amount' },
				],
				"columnDefs": [
					{
						"targets": 0,
						"className": "Tarjeta",
						"visible": lang.SETT_CARD_STATUS_COLUMN == "ON"
					},
					{
						"targets": 1,
						"className": "date",
						"visible": lang.SETT_DATE_COLUMN == "ON"
					},
					{
						"targets": 1,
						"className": "date",
						"visible": lang.SETT_DATE_COLUMN == "ON"
					},
					{
						"targets": 2,
						"className": "fid",
						"visible": lang.SETT_DNI_COLUMN == "ON"
					},
					{
						"targets": 3,
						"className": "terminal",
						"visible": lang.SETT_TERMINAL_COLUMN == "ON"
					},
					{
						"targets": 4,
						"className": "secuence",
						"visible": lang.SETT_SECUENCE_COLUMN == "ON"
					},
					{
						"targets": 5,
						"className": "reference",
						"visible": lang.SETT_REFERENCE_COLUMN == "ON"
					},
					{
						"targets": 6,
						"className": "description",
						"visible": lang.SETT_DESCRIPTION_COLUMN == "ON"
					},
					{
						"targets": 7,
						"className": "typeTransaction",
						"visible": lang.SETT_OPERATION_COLUMN == "ON",
						"width": "15%",
					},
					{
						"targets": 8,
						"className": "amount",
						"visible": lang.SETT_AMOUNT_COLUMN == "ON"
					}
				],
				"language": dataTableLang,
			});
		});
		await sleep(lang.SETT_DATATABLE_SLEEP);
	};
	$('#spinnerResults').addClass('hide');
};
