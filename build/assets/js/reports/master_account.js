'use strict'
var table;
var modalReq = {};

$(function () {
	$('#blockMasterAccountResults').addClass("hide");
	$('#titleResults').addClass('hide');
	$('#files-btn').addClass('hide');
	$('.btn').removeAttr('disabled');
	$("#credit").val('');
	$("#debit").val('');
	$("#debit").val('');

	if ($("input[name='results']:checked").val() != 0) {
		$("#initialDate ").attr('required', 'required');
		$("#finalDate ").attr('required', 'required');
	}

	table = $('#balancesClosing').DataTable({
	"ordering": false,
	"pagingType": "full_numbers",
	"language": dataTableLang
}
);

	$('#range').attr('checked', true);
	var datePicker = $('.date-picker');
	$("#credit").val('C');
	$("#debit").val('D');
	$("#trimester").val('3');
	$("#semester").val('6');
	$("#range").val('0');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$('#finalDate').removeClass('has-error');
	$('#initialDate').removeClass('has-error');
	$('#concenAccount').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

		datePicker.datepicker({
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
			dateSelected = new Date(dateSelected);
			var inputDate = $(this).attr('id');

			if (inputDate == 'initialDate') {
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.CONF_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#finalDate').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	$("#radio-form").on('change', function(){
		$('#finalDate').removeClass('has-error');
		$('#initialDate').removeClass('has-error');
		$(".help-block").text("");
		if ($("input[name='results']:checked").val() != 0) {
			$("#initialDate ").datepicker('setDate', null);
			$("#finalDate").datepicker('setDate', null);
			$("#initialDate ").attr('disabled', 'disabled');
			$("#finalDate").attr('disabled', 'disabled');
		} else if ($("input[name='results']:checked").val() == 0 ){
			$("#initialDate ").removeAttr('disabled');
			$("#finalDate ").removeAttr('disabled');
		}
});

	$("#export_excel").click(function(e){
		exportFile(e);
	});
	$("#export_pdf").click(function(e){
		exportFile(e);
	});
	$("#export_pdfCons").click(function(e){
		dialog(e);
	});
	$("#export_excelCons").click(function(e){
		dialog(e);
	});
});

function dialog(e){
	e.preventDefault();
	var event = $(e.currentTarget);
	var action = event.attr('title');
	var submitForm = false;
	$(this).closest('tr').addClass('select');

	switch (action) {

		case 'Exportar a EXCEL consolidado':
			var titleModalExcel = 'Exportar a EXCEL de consolidado';
			var oldID = $('#accept').attr('id');
			modalReq['table'] = $(this).closest('table');
			$('#accept').attr('id', 'download-consolid');
			modalBtn = {
				btn1: {
					text: lang.GEN_BTN_DOWNLOAD,
					action: 'close'
				},
				btn2: {
					text: lang.GEN_BTN_CANCEL,
					action: 'close'
				}
			}

			inputModal = 	'<form id="excel-user-form" class="form-group">';
			inputModal+= 		'<div class="input-group">';
			inputModal+= 			'<select name="anio-consolid"class="date-picker-year select-box custom-select ml-1 h6" id="anio-consolid"><option selected value="0">Seleccione año</option></select>';
			inputModal+= 		'</div>';
			inputModal+= 		'<div class="help-block"></div>';
			inputModal+=	'</form>';
			appMessages(titleModalExcel, inputModal, lang.CONF_ICON_INFO, modalBtn);

			var i=0;
			var anioB;
			do {
				var dateGetYear = new Date();
				var date = dateGetYear.getFullYear();
				anioB= parseInt(date)-i;
				$(".date-picker-year").append('<option value="'+anioB.toString()+'">'+anioB.toString()+'</option>');
				i=i+1;
			} while (i!=20);
			downloadConsolid(oldID);
			break;

			case 'Exportar a PDF consolidado':
				var titleModalPdf = 'Exportar a PDF consolidado';
				var oldID = $('#accept').attr('id');
				modalReq['table'] = $(this).closest('table');
				$('#accept').attr('id', 'download-consolidpdf');
				modalBtn = {
					btn1: {
						text: lang.GEN_BTN_DOWNLOAD,
						action: 'close'
						},
					btn2: {
						text: lang.GEN_BTN_CANCEL,
						action: 'close'
						}
					}
				inputModal = 	'<form id="pdf-user-form" class="form-group">';
				inputModal+= 		'<div class="input-group">';
				inputModal+= 			'<select name="anio-consolid"class="date-picker-year select-box custom-select ml-1 h6" id="anio-consolid"><option selected value="0">Seleccione año</option></select>';
				inputModal+= 		'</div>';
				inputModal+= 		'<div class="help-block"></div>';
				inputModal+=	'</form>';
				appMessages(titleModalPdf, inputModal, lang.CONF_ICON_INFO, modalBtn);

				var i=0;
				var anioB;
				do {
					var dateGetYear = new Date();
					var date = dateGetYear.getFullYear();
					anioB= parseInt(date)-i;
					$(".date-picker-year").append('<option value="'+anioB.toString()+'">'+anioB.toString()+'</option>');
					i=i+1;
				}while(i!=20);
				break;
	}
	if(action == titleModalPdf ){
		var type = 'pdf';
		downloadConsolid(type, oldID);
	}else{
		var type = 'excel';
		downloadConsolid(type, oldID);
	}

	$('#cancel').on('click', function(){
	$('.cover-spin').removeAttr("style");
	modalReq['active'] = false;
	})
}

function downloadConsolid(type, oldID){
	if ( type == "excel" ) {
		var button = $('#download-consolid');
		var form = $('#excel-user-form');
	} else {
		var button = $('#download-consolidpdf');
		var form = $('#pdf-user-form');
	}
	button.on('click', function() {
		validateForms(form);
		if (form.valid()) {
			button.off('click').html(loader).attr('id', oldID);
			button.attr('disabled', 'disabled');
			insertFormInput(true, form);
			var passData = {
				modalReq: true,
				year: $('#anio-consolid').find('option:selected').val(),
				idExtEmp: $('#enterprise-report').find('option:selected').attr('acrif'),
				dateStart: $("#initialDate").val(),
				dateEnd: $("#finalDate").val(),
				dateFilter: $("input[name='results']:checked").val(),
				nameEnterprise: $('#enterprise-report').find('option:selected').attr('nomOf'),
				actualPage: "1",
				pageSize: $("#tamP").val()
			};
			if (type == "excel") {
				exportToExcelConsolid(passData);
			} else {
				exportToPDFConsolid(passData);
			}
		}
	})
};


function exportFile(e){
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		var form = $('#masterAcForm');

  	var passData = {
			idExtEmp: $('#enterprise-report').find('option:selected').attr('acrif'),
			dateStart: $("#initialDate").val(),
			dateEnd: $("#finalDate").val(),
			dateFilter: $("input[name='results']:checked").val(),
			nameEnterprise: $('#enterprise-report').find('option:selected').attr('nomOf'),
			actualPage: "1",
			pageSize: $("#tamP").val()
		};

		switch(action) {
			case 'Exportar a EXCEL':
				validateForms(form);
				if (form.valid()) {
					exportToExcel(passData);
				}
				break;
			case 'Exportar a PDF':
				validateForms(form);
				if (form.valid()) {
					exportToPDF(passData);
				}
				break;
		}
};

function exportToExcel(passData) {
	who = 'Reports';
	where = 'exportToExcelMasterAccount';
	data = passData;

	callNovoCore(who, where, data, function(response) {
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
}

function exportToExcelConsolid(passData, textBtn) {
	who = 'Reports';
	where = 'exportToExcelMasterAccountConsolid';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		insertFormInput(false, form);
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
			if ($("input[name='results']:checked").val() != 0) {
				$("#initialDate").attr("disabled", "disabled");
				$("#finalDate").attr("disabled", "disabled");
			}
			$('.cover-spin').removeAttr("style");
		} else if (code == 4) {
			$('.cover-spin').removeAttr("style");
		}
  })
}

function exportToPDF(passData) {
	who = 'Reports';
	where = 'exportToPDFMasterAccount';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if (info.formatoArchivo == 'PDF') {
			info.formatoArchivo = '.pdf'
		}
		if (code == 0) {
			data = {
				"name": 'cuentaMaestra'+info.formatoArchivo,
				"ext": info.formatoArchivo,
				"file": info.archivo
			}
			downLoadfiles (data);
			$('.cover-spin').removeAttr("style");
		}
  })
}

function exportToPDFConsolid(passData) {
	who = 'Reports';
	where = 'exportToPDFMasterAccountConsolid';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		insertFormInput(false, form);
	  dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if (info.formatoArchivo == 'PDF') {
			info.formatoArchivo = '.pdf'
		}
		if (code == 0) {
			data = {
				"name": 'cuentaMaestraConsolidado'+info.formatoArchivo,
				"ext": info.formatoArchivo,
				"file": info.archivo
			}
		downLoadfiles (data);
		if ($("input[name='results']:checked").val() != 0) {
			$("#initialDate").attr("disabled", "disabled");
			$("#finalDate").attr("disabled", "disabled");
		}
		$('.cover-spin').removeAttr("style");
		}  else if (code == 4) {
			$('.cover-spin').removeAttr("style");
		}
	})
}

$("#btnMasterAccount").on('click', function(e){
	e.preventDefault();
	form = $('#masterAcForm');
	validateForms(form)
	if (form.valid()) {
		insertFormInput(true, form);
	  $('#blockMasterAccountResults').addClass("hide");
	  $('#spinnerBlock').removeClass("hide");
		$('#titleResults').addClass('hide');
		info();

	}
})

function info(){
	var resultadoCheck;
	var checkDebit;
	var checkCredit;
	if ($("#debit").is(":checked")) {
		resultadoCheck = $("#debit").val();
		if ($("#credit").is(":checked")) {
			resultadoCheck = $("#credit").val();
			} else {
				checkCredit = false;
			}
	} else {
		checkDebit = false;
			if ($("#credit").is(":checked")) {
				resultadoCheck = $("#credit").val();
				} else {
				checkCredit = false;
				}
		}
		if (checkDebit == false && checkCredit == false) {
			resultadoCheck = '';
	}
	if (checkDebit != false) {
		if (checkCredit != false) {
			resultadoCheck = '';
		}
	}
	var form= $('#masterAcForm');
	insertFormInput(true, form);

	var passData = {
		idExtEmp: $('#enterprise-report').find('option:selected').attr('acrif'),
		dateStart: $("#initialDate").val(),
		dateEnd:  $("#finalDate").val(),
		typeNote: resultadoCheck,
		dateFilter: $("input[name='results']:checked").val(),
		actualPage: 1,
		pageSize: $("#tamP").val()
	};

	masterAccount(passData);

}

function masterAccount(passData) {
	who = 'Reports';
	where = 'masterAccount';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		insertFormInput(false);
		$('#spinnerBlock').addClass("hide");
		$('#tbody-datos-general').removeClass('hide');
		$('#titleResults').removeClass('hide');
		var table = $('#concenAccount').DataTable();
		table.destroy();
			dataResponse = response.data
			code = response.code
			if ( code == 0) {
				$('#files-btn').removeClass("hide");
			var info = dataResponse.depositoGMO.lista;
			$.each(info,function(posLista,itemLista){
				if (itemLista.tipoNota == 'D') {
					itemLista.montoDeposito = '-' + itemLista.montoDeposito;
					itemLista.tipoNota = '';
				} else if (itemLista.tipoNota == 'C') {
					itemLista.tipoNota = '+' + itemLista.montoDeposito;
					itemLista.montoDeposito = ''
				}
			});
			table = $('#concenAccount').DataTable({
				"responsive": true,
				"ordering": false,
				"pagingType": "full_numbers",
				"language": dataTableLang,
				"data": info,
				"columns": [
						{ data: 'fechaRegDep' },
						{ data: 'descripcion' },
						{ data: 'referencia' },
						{ data: 'montoDeposito' },
						{ data: 'tipoNota' },
						{ data: 'saldoDisponible' }
				]
			})} else {
				$('#files-btn').addClass('hide');
				$('#concenAccount').DataTable({
					"responsive": true,
					"ordering": false,
					"pagingType": "full_numbers",
					"language": dataTableLang,
				}).clear().draw();
			}
			if ($("input[name='results']:checked").val() != 0){
				$("#initialDate ").attr('disabled', 'disabled');
				$("#finalDate ").attr('disabled', 'disabled');
			} else if($("input[name='results']:checked").val() == 0){

				$("#initialDate ").removeAttr('disabled');
				$("#finalDate ").removeAttr('disabled');
			}
			$('#blockMasterAccountResults').removeClass("hide");

  })
}
