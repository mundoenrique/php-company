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
	$('#extMasterAccount').DataTable({
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
			lang.CONF_MODAL_WIDTH = 200;
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
		var form = $('#extMasterAcForm');

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
				if (true/*form.valid()*/) {
					//exportToExcel(passData);
					extendedDownloadFiles(passData);
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

function extendedDownloadFiles(data) {
	insertFormInput(true);
	who = 'Reports';
	where = 'exportToExcelExtendedMasterAccount';
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
	form = $('#extMasterAcForm');
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
	var form= $('#extMasterAcForm');
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

	extendedMasterAccount(passData);

}

function extendedMasterAccount(dataForm) {
	$('#extMasterAccount').DataTable().destroy();
	$('#extMasterAccount').DataTable({
		drawCallback: function (d) {
			insertFormInput(false)
			$('#spinnerBlock').addClass("hide");
			$('#tbody-datos-general').removeClass('hide');
			$('#titleResults').removeClass('hide');
			$('#files-btn').removeClass("hide");
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
			{ "data": 'fechaRegDep' },
			{ "data": 'idPersona' },
			{ "data": 'nombrePersona' },
			{ "data": 'descripcion' },
			{ "data": 'referencia' },
			{ "data": 'montoDeposito' },
			{ "data": 'tipoNota' },
			{ "data": 'saldoDisponible' }
		],
		"columnDefs": [
			{"targets": 0, "className": "fechaRegDep", "width": "100px"},
			{"targets": 1, "className": "idPersona",  "width": "150px"},
			{"targets": 2, "className": "nombrePersona", "width": "100px"},
			{"targets": 3, "className": "descripcion", "width": "150px"},
			{"targets": 4, "className": "referencia"},
			{"targets": 5, "className": "montoDeposito"},
			{"targets": 6, "className": "tipoNota"},
			{"targets": 7, "className": "saldoDisponible"}
		],
		"ajax": {
			url: baseURL + 'async-call',
			method: 'POST',
			dataType: 'json',
			cache: false,
			data: function (req) {
				data = req,
				data.idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif'),
				data.dateStart= $("#initialDate").val(),
				data.dateEnd = $("#finalDate").val(),
				data.typeNote= "",
				data.dateFilter=$("input[name='results']:checked").val()
				var dataRequest = JSON.stringify({
					who: 'Reports',
					where: 'extendedMasterAccount',
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
				$('#blockMasterAccountResults').removeClass("hide");
				return JSON.stringify(responseTable);
			}
		}
	});
}
