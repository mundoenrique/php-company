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
								itemLista.montoDeposito = lang.CONF_CURRENCY + ' ' + itemLista.montoDeposito;
								itemLista.tipoNota = '';
							} else if (itemLista.tipoNota == 'C') {
								itemLista.tipoNota = lang.CONF_CURRENCY + ' ' + itemLista.montoDeposito;
								itemLista.montoDeposito = ''
							}
							itemLista.saldoDisponible = lang.CONF_CURRENCY + ' ' +itemLista.saldoDisponible;
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
				case 'export_txt':
					extendedDownloadFiles(dataForm, 'exportToTxtExtendedMasterAccount');
     		break;
				case 'export_excel':
					extendedDownloadFiles(dataForm, 'exportToExcelExtendedMasterAccount');
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

function extendedDownloadFiles(data, service) {
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

function ModalConsolid(param) {
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

	var titleModal = '';
	var year;

	if(param == 'export_excelCons'){
		titleModal = lang.GEN_TITLE_EXPORT_XLS_CON;
	}else{
		titleModal = lang.GEN_TITLE_EXPORT_PDF_CON;
	}

	inputModal = '<form id="reportYearModal" name="reportYearModal" onsubmit="return false;">';
	inputModal += '<div class="form-group">';
	inputModal += 	'<select id="yearReport" name="yearReport" class="select-box custom-select form-control date-picker-year">';
	inputModal += 		'<option selected disabled>Seleccione año</option>';
		for (var i = 0; i < lang.CONF_YEAR; i++) {
			var dateGetYear = new Date();
			var date = dateGetYear.getFullYear();
			year = (parseInt(date)-i).toString();
			inputModal += '<option value="'+year+'">'+year+'</option>'
		}
	inputModal += 	'</select>';
	inputModal += 	'<input id="formatReport" type="hidden" name="formatReport">';
	inputModal += 	'<div class="help-block"></div>';
	inputModal += '</div>';
	inputModal += '</form>';

	$("#formatReport").val('');
	$('#accept').addClass('extended');

	appMessages(titleModal, inputModal, lang.CONF_ICON_INFO, modalBtn);

	if(param == 'export_excelCons'){
		$("#formatReport").val('Excel');
		titleModal = lang.GEN_TITLE_EXPORT_XLS_CON;
	}else{
		$("#formatReport").val('Pdf');
		titleModal = lang.GEN_TITLE_EXPORT_PDF_CON;
	}
}

$('#system-info').on('click', '.extended', function (e) {
	e.preventDefault();
	var formXls = $('#extMasterAccountFormXls');
	var form = $('#reportYearModal');
	var dataFormXls = getDataForm(formXls);
	var dataFormModal = getDataForm(form);
	data = dataFormXls;
	data.year = dataFormModal.yearReport;
	data.downloadFormat = dataFormModal.formatReport;
	validateForms(form)
	if (form.valid()) {
		extendedDownloadFilesConsolid(data, $(this));
	}
});

function extendedDownloadFilesConsolid(data, currentBtn){
	currentBtn.html(loader).prop('disabled', true);
	insertFormInput(true);
	who = 'Reports';
	where = 'extendedDownloadMasterAccountCon';

	callNovoCore(who, where, data, function(response) {
		if (response.code == 0) {
			downLoadfiles (response.data);
		}
		currentBtn.prop('disabled', false);
			insertFormInput(false);
			$('.cover-spin').hide();
	});
}
