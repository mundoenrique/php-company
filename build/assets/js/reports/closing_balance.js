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
	$('#amanecidos-bnt').on('click', function(e){
		$('#export_excel').removeClass("hide");
		e.preventDefault();
		form = $('#amanecidosForm');
		validateForms(form)

		if (form.valid()) {
			searchBudgets();
		}
	})


	$("#export_excel").click(function(){
		$('#export_excel').addClass("hide");
		empresa = $('#enterprise-report').find('option:selected').attr('acrif');
		cedula =  $("#Nit").val().replace(/ /g, '');
		producto = $("#products-select").val();
		nomEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
		descProd = $("#products-select").find('option:selected').attr('value');
		paginaActual = 1;
		paginar = true;
		tamPg = 1000;

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
		$('#hid2').removeClass("hide");
		exportToExcel(passData)
		});
});

function selectionBussine(passData) {
	verb = "POST";
	who = 'Business';
	where = 'getProducts';
	data = passData;
	callNovoCore(verb, who, where, data, function(response) {
			dataResponse = response.data
			code = response.code
			var info = dataResponse;
			for (var index = 0; index < info.length; index++) {
				$('#products-select').append("<option value=" + info[index].id + " brand=" + info[index].brand + ">" + info[index].desc + "</option>");
			}
			if(code == 3){
				$('#products-select').append("<option><?= lang('ERROR_(-138)'); ?></option>");
			}
})
}

function exportToExcel(passData) {
	verb = "POST";
	who = 'Reports';
	where = 'exportToExcel';
	data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		$('#hid2').addClass("hide");
			dataResponse = response.data
			code = response.code
			var info = dataResponse;
			if(code == 0){
			var File = new Int8Array(info.archivo);
			byteArrayFile([File], 'SaldoAlCierre.xls');
		}
		$('#export_excel').removeClass("hide");
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
		cedula =  $("#Nit").val().replace(/ /g, '');
		producto = $("#products-select").val();
		nomEmpresa = $('#enterprise-report').find('option:selected').attr('nomOf');
		descProd = $("#products-select").attr("des");
		paginaActual = 1;
		paginar = true;
		tamPg = 20;

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
		$('#hid').removeClass("hide");
		closingBudgets(passData);

}

function closingBudgets(passData) {
	verb = "POST";
	who = 'Reports';
	where = 'closingBudgets';
	data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		$('#hid').addClass("hide");
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
			})
			} else{
				$('#balancesClosing').DataTable({
					"responsive": true,
					"ordering": false,
					"pagingType": "full_numbers",
					"language": dataTableLang,
				}).clear().draw();
			}
})
}







