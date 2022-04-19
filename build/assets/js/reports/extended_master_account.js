'use strict'
var table;
var modalReq = {};

$(function () {
	$('#blockMasterAccountResults').addClass("hide");
	$('#range').prop("checked",true);
	$('#allProcedures').prop("checked",true);
	$('#titleResults').addClass('hide');
	$('#files-btn').addClass('hide');
	$('#finalDate').val('');
	$('#initialDate').val('');
	$("#credit").val('C');
	$("#debit").val('D');
	$("#trimester").val('3');
	$("#semester").val('6');
	$("#range").val('0');
	$('#extMasterAccountFormXls')[0].reset();
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	var datePicker = $('.date-picker');
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

	$("#radio-form").on('change', function(){
		$('#finalDate').datepicker('setDate', null).removeClass('has-error');
		$('#initialDate').datepicker('setDate', null).removeClass('has-error');
		$(".help-block").text("");
		if ($("input[name='results']:checked").val() != 0) {
			$("#initialDate ").attr('disabled', 'disabled');
			$("#finalDate").attr('disabled', 'disabled');
		} else if ($("input[name='results']:checked").val() == 0 ){
			$("#initialDate ").removeAttr('disabled');
			$("#finalDate ").removeAttr('disabled');
		}
	});

	$("#btnMasterAccount").on('click', function(e){
		e.preventDefault();
		form = $('#extMasterAcForm');
		validateForms(form)
		if (form.valid()) {
			insertFormInput(true, form);
			$('#blockMasterAccountResults').addClass("hide");
			$('#spinnerBlock').removeClass("hide");
			$('#titleResults').addClass('hide');
			$('#files-btn').addClass("hide");
			detailData();
		}
	});

	function detailData(){
		var resultadoCheck = '';

		if ($("#debit").is(":checked") == true && $("#credit").is(":checked") == true) {
			resultadoCheck = '';
		}else if ($("#debit").is(":checked") == false && $("#credit").is(":checked") == false) {
			resultadoCheck = '';
		}else if ($("#debit").is(":checked") == true && $("#credit").is(":checked") == false) {
			resultadoCheck = $("#debit").val();
		}else if ($("#debit").is(":checked") == false && $("#credit").is(":checked") == true) {
			resultadoCheck = $("#credit").val();
		}

		var passData = {
			idExtEmp: $('#enterprise-report').find('option:selected').attr('acrif'),
			initialDate: $("#initialDate").val(),
			finalDate:  $("#finalDate").val(),
			typeNote: resultadoCheck,
			filterDate: $("input[name='results']:checked").val()
		};
		extendedMasterAccount(passData);
}

	function extendedMasterAccount(dataForm) {
		$('#extMasterAccount').DataTable().destroy();
		$('#extMasterAccount').DataTable({
			drawCallback: function (d) {
				$('#spinnerBlock').addClass("hide");
				$('#tbody-datos-general').removeClass('hide');
				$('#titleResults').removeClass('hide');
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
					data.idExtEmp = dataForm.idExtEmp,
					data.initialDate= dataForm.initialDate,
					data.finalDate = dataForm.finalDate,
					data.typeNote= dataForm.typeNote,
					data.filterDate= dataForm.filterDate
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
					var responseTableEnd = JSON.parse(
						CryptoJS.AES.decrypt(responseTable.code, responseTable.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
					);

					$('#extMasterAccountFormXls')[0].reset();

					if (responseTableEnd.code == 0) {
						$.each(responseTableEnd.data,function(posLista,itemLista){
							if (itemLista.tipoNota == 'D') {
								itemLista.montoDeposito = '$ ' + itemLista.montoDeposito;
								itemLista.tipoNota = '';
							} else if (itemLista.tipoNota == 'C') {
								itemLista.tipoNota = '$ ' + itemLista.montoDeposito;
								itemLista.montoDeposito = ''
							}
							itemLista.saldoDisponible = '$ ' +itemLista.saldoDisponible;
						});

						$('#idExtEmpXls').val(responseTableEnd.idExtEmp);
						$('#initialDateXls').val(responseTableEnd.initialDate);
						$('#finalDateXls').val(responseTableEnd.finalDate);
						$('#filterDateXls').val(responseTableEnd.filterDate);
						$('#nameEnterpriseXls').val(responseTableEnd.nameEnterprise);

						$('#files-btn').removeClass("hide");
					}

					insertFormInput(false, form)
					if ($("input[name='results']:checked").val() != 0){
						$("#initialDate ").attr('disabled', 'disabled');
						$("#finalDate ").attr('disabled', 'disabled');
					} else if($("input[name='results']:checked").val() == 0){
						$("#initialDate ").removeAttr('disabled');
						$("#finalDate ").removeAttr('disabled');
					}

					$('#blockMasterAccountResults').removeClass("hide");
					return JSON.stringify(responseTableEnd);
				}
			}
		});
	}

	$(".btn-file").on('click', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('id');
		var form = $('#extMasterAccountFormXls');
		var dataForm = getDataForm(form)

		var passData = {
			idExtEmp: dataForm.idExtEmpXls,
			dateStart: dataForm.initialDateXls,
			dateEnd: dataForm.finalDateXls,
			dateFilter: dataForm.filterDateXls,
			nameEnterprise: dataForm.nameEnterpriseXls,
		};

		validateForms(form);
		if (form.valid()) {
			switch(action) {
				case 'export_excel':
					extendedDownloadFiles(dataForm);
				break;
				case 'export_pdf':
					exportToPDF(passData);
				break;
				case 'export_excelCons':
				case 'export_pdfCons':
					ModalConsolid(action);
				break;
			}
		}
	});
});


/*function dialog(e){
	e.preventDefault();
	var event = $(e.currentTarget);
	var action = event.attr('title');
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
}*/

/*function downloadConsolid(type, oldID){
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
}*/


function extendedDownloadFiles(data) {
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
		$('.cover-spin').hide();
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

	function extendedDownloadFilesConsolid(data){
		$('.cover-spin').show();
		who = 'Reports';
		where = 'extendedDownloadMasterAccountCon';

		callNovoCore(who, where, data, function(response) {
			if (response.code == 0) {
				downLoadfiles (response.data);
			}
			$('.cover-spin').hide();
		});
	}

	function ModalConsolid(param) {
		var titleModalPdf = 'Exportar a PDF consolidado';

		modalBtn = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'none'
			},
			btn2: {
				text: lang.GEN_BTN_CANCEL,
				action: 'destroy'
			}
		}

		inputModal = '<form id="reportYearModal" name="reportYearModal" class="row col-auto" onsubmit="return false;">';
		inputModal += '<div class="form-group col-4 col-xl-3">';
		inputModal += '<label for="yearReport">Inicio</label>';
		inputModal += '<input id="yearReport" name="selected-year-modal" class="form-control " type="text" placeholder="MM/AAAA" readonly="" autocomplete="off">';
		inputModal += '<input id="formatReport" type="hidden">';
		inputModal += '<div class="help-block"></div>';
		inputModal += '</div>';
		inputModal += '</form>';

		appMessages(titleModalPdf, inputModal, lang.CONF_ICON_INFO, modalBtn);

		$("#formatReport").val('');
		$('#accept').addClass('extended');

		if(param == 'export_excelCons'){
			$("#formatReport").val('Excel');
		}else{
			$("#formatReport").val('Pdf');
		}
	}

	$('#system-info').on('click', '.extended', function () {
		var formXls = $('#extMasterAccountFormXls');
	  var form = $('#reportYearModal');
		var dataFormXls = getDataForm(formXls);
		var dataFormModal = getDataForm(form);

		data = dataFormXls;
		data.year = '2022';
		data.downloadFormat = dataFormModal.formatReport;

		console.log(data);

		extendedDownloadFilesConsolid(data);
	});

/*function exportToExcelConsolid(passData, textBtn) {
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
}*/
