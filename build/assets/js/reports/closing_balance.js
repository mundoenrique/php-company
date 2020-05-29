'use strict'
var reportsResults;
	var enterpriseCode;
	var enterpriseGroup;
	var idFiscal;
	var enterpriseName;
	var empresa;
	var cedula;
	var producto;
	var nomEmpresa;
	var descProd;
	var paginaActual;
	var paginar;
	var tamPg;
	var table;

$(function () {
	$('#blockBudgetResults').addClass('hide');
	$('#titleResults').addClass('hide');
	$('#Nit').attr('maxlength', 10);
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	table = $('#balancesClosing').DataTable({
		"ordering": false,
		"pagingType": "full_numbers",
    "language": dataTableLang
	}
	);

	$('#enterprise-report').on('change', function(){
		$('#closingBudgetsBtn').removeAttr('disabled');
		$('#productCode').attr('disabled', 'disabled');
		$('#products-select').empty();
		enterpriseCode =  $('#enterprise-report').find('option:selected').attr('code');
		enterpriseGroup =  $('#enterprise-report').find('option:selected').attr('group');
		idFiscal =  $('#enterprise-report').find('option:selected').attr('acrif');
		enterpriseName = $('#enterprise-report').find('option:selected').text();
		var passData = {
			enterpriseCode: enterpriseCode,
			enterpriseGroup: enterpriseGroup,
			idFiscal: idFiscal,
			enterpriseName: enterpriseName,
			select: true
		};
		selectionBussine(passData)

	})

	$('#Nit').keyup(function (){
		this.value = (this.value + '').replace(/[^0-9]+$/i, '');
	 });

	$('#export_excel').addClass("hide");
	$('#closingBudgetsBtn').on('click', function(e){
		$('#spinnerBlockBudget').removeClass("hide");
		$('#blockBudgetResults').addClass("hide");
		$('#export_excel').removeClass("hide");
		e.preventDefault();
		form = $('#closingBudgetForm');
		validateForms(form)

		if (form.valid()) {
			searchBudgets();
		}
	})


	$("#export_excel").click(function(){


		empresa = $('#enterprise-report').find('option:selected').attr('acrif');
		cedula =  '';
		producto = $("#productCode").val();
		nomEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
		descProd = $("#productCode").find('option:selected').attr('value');
		paginaActual = 1;
		paginar = true;
		tamPg = $("#tamP").val();

		var passData = {
			empresa: empresa,
			cedula: cedula,
			producto: producto,
			nomEmpresa: nomEmpresa,
			descProd: descProd,
			paginaActual: paginaActual,
			paginar: paginar,
			tamPg: tamPg
		};

		exportToExcel(passData)
		});
});

function selectionBussine(passData) {
	verb = "POST"; who = 'Business'; where = 'getProducts'; data = passData;
	$("#productCode").html("");
	callNovoCore(verb, who, where, data, function(response) {
			dataResponse = response.data
			code = response.code

			var info = dataResponse;
			if(code == 3){
				$('#productCode').append("<option>"+		$("#errProd").val() +"</option>");
				$('#closingBudgetsBtn').attr('disabled', 'disabled');
			}
			$('#productCode').removeAttr('disabled');
			for (var index = 0; index < info.length; index++) {
				$('#productCode').append("<option value=" + info[index].id + " brand=" + info[index].brand + ">" + info[index].desc + "</option>");
			}
})

}

function exportToExcel(passData) {
	verb = "POST"; who = 'Reports'; where = 'exportToExcel'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
			dataResponse = response.data;
			code = response.code
			var info = dataResponse;
			if(code == 0){
			var File = new Int8Array(info.archivo);
			byteArrayFile([File], 'SaldoAlCierre.xls');
			$('.cover-spin').removeAttr("style");
		}
})
}

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

function searchBudgets(){
	$('#enterprise-report').find('option:selected').attr('acrif');
		empresa = $('#enterprise-report').find('option:selected').attr('acrif');
		cedula =  '';
		producto = $("#productCode").val();
		nomEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
		descProd = $("#productCode").attr("des");
		paginaActual = 1;
		paginar = false;
		tamPg = $("#tamP").val();

		var passData = {
			empresa: empresa,
			cedula: cedula,
			producto: producto,
			nomEmpresa: nomEmpresa,
			descProd: descProd,
			paginaActual: paginaActual,
			paginar: paginar,
			tamPg: tamPg
		};

		closingBudgets(passData);

}

function closingBudgets(passData) {
	verb = "POST"; who = 'Reports'; where = 'closingBudgets'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		$('#spinnerBlockBudget').addClass("hide");
		$('#tbody-datos-general').removeClass('hide');
		var table = $('#balancesClosing').DataTable();
	  table.destroy();
			dataResponse = response.data
			code = response.code
			if( code == 0){
			var info = dataResponse.saldo.lista;


			table = $('#balancesClosing').DataTable({
				"responsive": true,
				"ordering": false,
				"pagingType": "full_numbers",
				"language": dataTableLang,
				"data": info,
				"columns": [
						{ data: 'nombre' },
						{ data: 'idExtPer' },
						{ data: 'tarjeta' },
						{ data: 'saldo' },
						{ data: 'fechaUltAct' }
				]
			})} else{

				$('#balancesClosing').DataTable({
					"responsive": true,
					"ordering": false,
					"pagingType": "full_numbers",
					"language": dataTableLang,
				}).clear().draw();
			}
			$('#titleResults').removeClass('hide');
			$('#blockBudgetResults').removeClass("hide");
})
}







