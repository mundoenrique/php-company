'use strict'
var table;
var modalReq = {};
var fecha = new Date();
fecha = fecha.getFullYear();

$(function () {

	$('#blockMasterAccountResults').addClass("hide");
	$('#titleResults').addClass('hide');
	$('#files-btn').addClass('hide');
	$("#credit").val('');
	$("#debit").val('');
	$("#debit").val('');

	if($("input[name='results']:checked").val() != 0){
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
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if (inputDate == 'initialDate') {
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + 3);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				}
			}

			if (inputDate == 'finalDate') {
				$('#initialDate').datepicker('option', 'maxDate', selectedDate);
			}
		}
	});

	$("#radio-form").on('change', function(){
		$('#finalDate').removeClass('has-error');
		$('#initialDate').removeClass('has-error');
		$(".help-block").text("");

		if($("input[name='results']:checked").val() != 0){
			$("#initialDate ").attr('disabled', 'disabled');
			$("#finalDate ").attr('disabled', 'disabled');
		} else if($("input[name='results']:checked").val() == 0){

			$("#initialDate ").removeAttr('disabled');
			$("#finalDate ").removeAttr('disabled');
		}
});

	$("#export_excel").click(function(){

		excelExport();
	});
	$("#export_pdf").click(function(){
		pdfExport();
	});
	$("#export_pdfCons").click(function(e){

		dialogPdf(e);
	});
	$("#export_excelCons").click(function(e){

		dialogExcel(e);
	});
});

function dialogExcel(e){

	e.preventDefault();
	var event = $(e.currentTarget);
	var action = event.attr('title');
	var submitForm = false;
	$(this).closest('tr').addClass('select');

	switch(action) {

		case 'Exportar a EXCEL consolidado':
			lang.CONF_MODAL_WIDTH = 200;
			var titleModal = 'Exportar a EXCEL de consolidado';
			var inputModal;
			modalReq['table'] = $(this).closest('table');
			data = {
				btn1: {
					text: lang.GEN_BTN_DOWNLOAD,
					action: 'none'
				},
				btn2: {
					action: 'close'
				}
			}

			inputModal = 	'<form id="excel-user-form" class="form-group">';
			inputModal+= 		'<div class="input-group">';
			inputModal+= 			'<select name="anio-consolid"class="date-picker-year select-box custom-select ml-1 h6" id="anio-consolid-excel"><option selected disabled >"Selecccione año"</option></select>';
			inputModal+= 		'</div>';
			inputModal+= 		'<div class="help-block"></div>';
			inputModal+=	'</form>';
			notiSystem(titleModal, inputModal, lang.GEN_ICON_INFO, data);

			var i=0;
			var anioB;
			do{
				anioB= parseInt(fecha)-i;
				$(".date-picker-year").append('<option value="'+anioB.toString()+'">'+anioB.toString()+'</option>');
				i=i+1;
			}while(i!=20);
			break;
	}

	$('#accept').addClass('big-modal');
	$('#cancel').on('click', function(){

		$('.cover-spin').removeAttr("style");
		modalReq['active'] = false;
	})
	$('#accept').on('click', function(){
		var form = $('#excel-user-form');
		var anio = $('#anio-consolid-excel').find('option:selected').val();
		var	idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif');
		var fechaIni = $("#initialDate").val();
		var fechaFin = $("#finalDate").val();
		var	filtroFecha = $("input[name='results']:checked").val();
		var	nombreEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
		var	tamanoPagina = $("#tamP").val();
		var	paginaActual = "1";
			var passData = {
				modalReq: true,
				idExtEmp: idExtEmp,
				anio: anio,
				fechaIni: fechaIni,
				fechaFin: fechaFin,
				filtroFecha: filtroFecha,
				nombreEmpresa: nombreEmpresa,
				paginaActual: paginaActual,
				tamanoPagina: tamanoPagina
			};
			validateForms(form);

			if (form.valid()) {
			exportToExcelConsolid(passData)
			}
		})

	};

function dialogPdf(e){

	e.preventDefault();
	var event = $(e.currentTarget);
	var action = event.attr('title');
	var submitForm = false;
	$(this).closest('tr').addClass('select');

	switch(action) {

	case 'Exportar a PDF consolidado':
	var titleModal = 'Exportar a PDF de consolidado';
	var inputModal;
	modalReq['table'] = $(this).closest('table');
	data = {
		btn1: {
			text: lang.GEN_BTN_DOWNLOAD,
		  },
		btn2: {
			action: 'close'
			}
		}
	inputModal = 	'<form id="pdf-user-form" class="form-group">';
	inputModal+= 	'<div class="input-group">';
	inputModal+= 	'<select id="anio-consolid-pdf" name="anio-consolid"class="date-picker-year select-box custom-select ml-1 h6" ><option selected disabled >"Selecccione año"</option></select>';
	inputModal+= 	'</div>';
	inputModal+= 	'<div class="help-block"></div>';
	inputModal+=	'</form>';
	notiSystem(titleModal, inputModal, lang.GEN_ICON_INFO, data);

	var i=0;
	var anioB;
	do{
		anioB= parseInt(fecha)-i;
		$(".date-picker-year").append('<option value="'+anioB.toString()+'">'+anioB.toString()+'</option>');
		i=i+1;
	}while(i!=20);
	  break;
	}

	if(submitForm) {
		form.submit();
	}
	$('#accept').addClass('big-modal');
	$('#cancel').on('click', function(){
		$('.cover-spin').removeAttr("style");
  })

  $('#accept').on('click', function(){
	  var form = $('#pdf-user-form');
	  var anio = $('#anio-consolid-pdf').find('option:selected').val();
	  var	idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif');
	  var fechaIni = $("#initialDate").val();
	  var fechaFin = $("#finalDate").val();
	  var	filtroFecha = $("input[name='results']:checked").val();
	  var	nombreEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
	  var	tamanoPagina = $("#tamP").val();
	  var	paginaActual = "1";
		var passData = {
			modalReq: true,
			idExtEmp: idExtEmp,
			anio: anio,
			fechaIni: fechaIni,
			fechaFin: fechaFin,
			filtroFecha: filtroFecha,
			nombreEmpresa: nombreEmpresa,
			paginaActual: paginaActual,
			tamanoPagina: tamanoPagina
		};

		validateForms(form);
		insertFormInput(false, form);
		if (form.valid()) {

		exportToPDFConsolid(passData)
		}
  })
};

function excelExport(){
	var	idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif');
	var fechaIni = $("#initialDate").val();
	var fechaFin = $("#finalDate").val();
	var	filtroFecha = $("input[name='results']:checked").val();
	var	nombreEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
	var	tamanoPagina = $("#tamP").val();
	var	paginaActual = "1";

  var passData = {
		idExtEmp: idExtEmp,
		fechaIni: fechaIni,
		fechaFin: fechaFin,
		filtroFecha: filtroFecha,
		nombreEmpresa: nombreEmpresa,
		paginaActual: paginaActual,
		tamanoPagina: tamanoPagina
  };
  exportToExcel(passData)
};

function pdfExport(){
	var	idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif');
	var fechaIni = $("#initialDate").val();
	var fechaFin = $("#finalDate").val();
	var	filtroFecha = $("input[name='results']:checked").val();
	var	nombreEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
	var	tamanoPagina = $("#tamP").val();
	var	paginaActual = "1";
	var passData = {
		idExtEmp: idExtEmp,
		fechaIni: fechaIni,
		fechaFin: fechaFin,
		filtroFecha: filtroFecha,
		nombreEmpresa: nombreEmpresa,
		paginaActual: paginaActual,
		tamanoPagina: tamanoPagina
	};

	exportToPDF(passData)
};

var byteArrayFile = (function () {
	var a = document.createElement("a");
	document.body.appendChild(a);
	a.style = "display: none";
	return function (data, name) {
		var blob = new Blob(data, {type: "application/xls"}),
		url = window.URL.createObjectURL(blob);
		a.href = url;
		a.download = name;
		a.click();
		window.URL.revokeObjectURL(url);
	};
}());

var byteArrayPDFFile = (function () {
	var a = document.createElement("a");
	document.body.appendChild(a);
	a.style = "display: none";
	return function (data, name) {
		var blob = new Blob(data, {type: "application/pdf"}),
		url = window.URL.createObjectURL(blob);
		a.href = url;
		a.download = name;
		a.click();
		window.URL.revokeObjectURL(url);
	};
}());

function exportToExcel(passData) {
	verb = "POST"; who = 'Reports'; where = 'exportToExcelMasterAccount'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if(code == 0){
		  var File = new Int8Array(info.archivo);
			byteArrayFile([File], 'cuentaMaestra.xls');
		  $('.cover-spin').removeAttr("style");
	  }
  })
}

function exportToExcelConsolid(passData, textBtn) {
	verb = "POST"; who = 'Reports'; where = 'exportToExcelMasterAccountConsolid'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		insertFormInput(false, form);
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if(code == 0){
		  var File = new Int8Array(info.archivo);
		  byteArrayFile([File], 'cuentaMaestraConsolidado.xls');
			$('.cover-spin').removeAttr("style");
		}
  })
}

function exportToPDF(passData) {
	verb = "POST"; who = 'Reports'; where = 'exportToPDFMasterAccount'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if(code == 0){
			var File = new Int8Array(info.archivo);
			byteArrayPDFFile([File], 'cuentaMaestra.pdf');
			$('.cover-spin').removeAttr("style");
		}
  })
}

function exportToPDFConsolid(passData) {
	verb = "POST"; who = 'Reports'; where = 'exportToPDFMasterAccountConsolid'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		insertFormInput(false, form);
	  dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if(code == 0){
			var File = new Int8Array(info.archivo);
			byteArrayPDFFile([File], 'cuentaMaestraConsolidado.pdf');
			$('.cover-spin').removeAttr("style");
		}
  })
}

$("#masterAc-btn").on('click', function(e){
	e.preventDefault();
	form = $('#masterAcForm');
	validateForms(form)
	if (form.valid()) {
		insertFormInput(true, form);
	  $('#blockMasterAccountResults').addClass("hide");
	  $('#spinnerBlockMasterAccount').removeClass("hide");
		info();

	}
})

function info(){
	var resultadoCheck;
	var checkDebit;
	var checkCredit;
	if($("#debit").is(":checked")) {
		resultadoCheck = $("#debit").val();
		if($("#credit").is(":checked")) {
			resultadoCheck = $("#credit").val();
			} else {
				checkCredit = false;
			}
	}else {
		checkDebit = false;
			if($("#credit").is(":checked")) {
				resultadoCheck = $("#credit").val();
				} else {
				checkCredit = false;
				}
		}
		if(checkDebit == false && checkCredit == false){
			resultadoCheck = '';
	}
	if(checkDebit != false){
		if(checkCredit != false){
			resultadoCheck = '';
		}
	}

	var idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif');
	var fechaIni =  $("#initialDate").val();
	var fechaFin = $("#finalDate").val();
	var tipoNota = resultadoCheck;
	var filtroFecha = $("input[name='results']:checked").val();
	var paginaActual = 1;
	var tamPg = $("#tamP").val();
	var passData = {
		idExtEmp: idExtEmp,
		fechaIni: fechaIni,
		fechaFin: fechaFin,
		tipoNota: tipoNota,
		filtroFecha: filtroFecha,
		paginaActual: paginaActual,
		tamanoPagina: tamPg
	};

	masterAccount(passData);

}

function masterAccount(passData) {
	verb = "POST"; who = 'Reports'; where = 'masterAccount'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		insertFormInput(false);
		$('#spinnerBlockMasterAccount').addClass("hide");
		$('#tbody-datos-general').removeClass('hide');
		$('#titleResults').removeClass('hide');
		var table = $('#concenAccount').DataTable();
		table.destroy();
			dataResponse = response.data
			code = response.code
			if( code == 0){
				$('#files-btn').removeClass('hide');
			var info = dataResponse.depositoGMO.lista;
			$.each(info,function(posLista,itemLista){
				if(itemLista.tipoNota == 'D'){
					itemLista.montoDeposito = '+' + itemLista.montoDeposito;
					itemLista.tipoNota = '';
				}else if(itemLista.tipoNota == 'C'){
					itemLista.tipoNota = '-' + itemLista.montoDeposito;
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
			})} else{
				$('#files-btn').addClass('hide');
				$('#concenAccount').DataTable({
					"responsive": true,
					"ordering": false,
					"pagingType": "full_numbers",
					"language": dataTableLang,
				}).clear().draw();
			}
			if($("input[name='results']:checked").val() != 0){
				$("#initialDate ").attr('disabled', 'disabled');
				$("#finalDate ").attr('disabled', 'disabled');
			} else if($("input[name='results']:checked").val() == 0){

				$("#initialDate ").removeAttr('disabled');
				$("#finalDate ").removeAttr('disabled');
			}
			$('#blockMasterAccountResults').removeClass("hide");

  })
}

