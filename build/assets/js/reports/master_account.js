'use strict'
var table;
$(function () {

	var fecha = new Date();
	fecha = fecha.getFullYear();
	var i=0;
	var anio;
	do{
		anio= parseInt(fecha)-i;
		$(".date-picker-year").append('<option value="'+anio.toString()+'">'+anio.toString()+'</option>');
		i=i+1;
	}while(i!=20);
	$('#tbody-datos-general').addClass('hide');
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
	$("#export_pdfCons").click(function(){
		dialogPdf();
	});
	$("#export_excelCons").click(function(){
		dialogExcel();
	});

});

function dialogExcel(){
	$( "#dialogEx" ).dialog({
		modal:true,
		resizable: true,
		title:"Selección de Año",
		width:"250px",
		maxheight:"200px",
});
}

function dialogPdf(){
	$( "#dialogPd" ).dialog({
		modal:true,
		resizable: true,
		title:"Selección de Año",
		width:"250px",
		maxheight:"200px",
});
}

$('#excelConsolidBtn').on('click', function(){
	excelExportConsolid();
})
$('#pdfConsolidBtn').on('click', function(){
	pdfExportConsolid();
})

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

	function excelExportConsolid(){
		$('#dialogEx').dialog('close');
		var anio = $('#anio-consolid-excel').find('option:selected').val();
		var	idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif');
		var fechaIni = $("#initialDate").val();
		var fechaFin = $("#finalDate").val();
		var	filtroFecha = $("input[name='results']:checked").val();
		var	nombreEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
		var	tamanoPagina = $("#tamP").val();
		var	paginaActual = "1";

			var passData = {
				idExtEmp: idExtEmp,
				anio: anio,
				fechaIni: fechaIni,
				fechaFin: fechaFin,
				filtroFecha: filtroFecha,
				nombreEmpresa: nombreEmpresa,
				paginaActual: paginaActual,
				tamanoPagina: tamanoPagina
			};

			exportToExcelConsolid(passData)
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

	function pdfExportConsolid(){
			$('#dialogPd').dialog('close');
			var anio = $('#anio-consolid-pdf').find('option:selected').val();
			var	idExtEmp = $('#enterprise-report').find('option:selected').attr('acrif');
			var fechaIni = $("#initialDate").val();
			var fechaFin = $("#finalDate").val();
			var	filtroFecha = $("input[name='results']:checked").val();
			var	nombreEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
			var	tamanoPagina = $("#tamP").val();
			var	paginaActual = "1";

				var passData = {
					idExtEmp: idExtEmp,
					anio: anio,
					fechaIni: fechaIni,
					fechaFin: fechaFin,
					filtroFecha: filtroFecha,
					nombreEmpresa: nombreEmpresa,
					paginaActual: paginaActual,
					tamanoPagina: tamanoPagina
				};

				exportToPDFConsolid(passData)
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
function exportToExcelConsolid(passData) {
	verb = "POST"; who = 'Reports'; where = 'exportToExcelMasterAccountConsolid'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
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
		}
		else {
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
		$('#spinnerBlockMasterAccount').addClass("hide");
		$('#tbody-datos-general').removeClass('hide');
		var table = $('#concenAccount').DataTable();

	  table.destroy();
			dataResponse = response.data
			code = response.code
			if( code == 0){
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

				$('#concenAccount').DataTable({
					"responsive": true,
					"ordering": false,
					"pagingType": "full_numbers",
					"language": dataTableLang,
				}).clear().draw();
			}

			$('#blockMasterAccountResults').removeClass("hide");
			$('#files-btn').removeClass("hide");
})
}
