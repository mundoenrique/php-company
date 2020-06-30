'use strict'
var reportsResults;
var enterpriseCode;
var enterpriseGroup;
var idFis;
var nameEnterprise;
var enterprise;
var nit;
var product;
var enterpriseName;
var descProd;
var actualPage;
var paging;
var tamPg;
var table;
var access;

$(function () {
	$('#blockBudgetResults').addClass('hide');
	$('#titleResults').addClass('hide');
	$('#Nit').attr('maxlength', 10);
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('#enterpriseReport').on('change', function(){
		$('#closingBudgetsBtn').removeAttr('disabled');
		$('#productCode').attr('disabled', 'disabled');
		$('#products-select').empty();
		enterpriseCode =  $('#enterpriseReport').find('option:selected').attr('code');
		enterpriseGroup =  $('#enterpriseReport').find('option:selected').attr('group');
		idFis =  $('#enterpriseReport').find('option:selected').attr('acrif');
		enterpriseName = $('#enterpriseReport').find('option:selected').text();
		var passData = {
			enterpriseCode: enterpriseCode,
			enterpriseGroup: enterpriseGroup,
			idFiscal: idFis,
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
			insertFormInput(false);
			form = $('#closingBudgetForm');
			var dataForm = getDataForm(form)
			searchBudgets(dataForm);
		}
	})


	$("#export_excel").click(function(){
		enterprise = $('#enterpriseReport').find('option:selected').attr('acrif');
		nit =  $('#enterpriseReport').find('option:selected').attr('acrif');
		product = $("#productCode").val();
		nameEnterprise = $('#enterpriseReport').find('option:selected').attr('nomOf');
		descProd = $("#productCode").find('option:selected').attr('value');
		actualPage = 1;
		paging = true;
		tamPg = $("#tamP").val();

		var passData = {
			empresa: enterprise,
			cedula: nit,
			producto: product,
			nomEmpresa: nameEnterprise,
			descProd: descProd,
			paginaActual: actualPage,
			paginar: paging,
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
			if(info.formatoArchivo == 'excel'){
				info.formatoArchivo = '.xls'
			}
			if(code == 0){
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

function searchBudgets(dataForm){
		enterprise = $('#enterpriseReport').find('option:selected').attr('acrif');
		nit =  '';
		product = $("#productCode").val();
		nameEnterprise = $('#enterpriseReport').find('option:selected').attr('nomOf');
		descProd = $("#productCode").attr("des");
		actualPage = 1;
		paging = false;
		tamPg = $("#tamP").val();

		var passData = {
			empresa: enterprise,
			cedula: nit,
			producto: product,
			nomEmpresa: nameEnterprise,
			descProd: descProd,
			paginaActual: actualPage,
			paginar: paging,
			tamPg: tamPg
		};

		closingBudgets(dataForm);
}

function closingBudgets(dataForm) {
var URLactual = window.location.pathname.substr(1);

if(URLactual.substring(0, URLactual.length - 16) == 'bnt'){
	var table = $('#balancesClosing').DataTable();
	table.destroy();
	table = $('#balancesClosing').DataTable({
		drawCallback: function (d) {
			insertFormInput(false)
			$('#spinnerBlockBudget').addClass("hide");
			$('#tbody-datos-general').removeClass('hide');
			$('#titleResults').removeClass('hide');
			$('#blockBudgetResults').removeClass("hide");
			$('#pre-loader-table').addClass('hide')
			$('.hide-table').removeClass('hide')
			$('.hide-out').removeClass('hide');
		},
		"ordering": false,
		"searching": false,
		"lengthChange": false,
		"length": 10,
		"pagingType": "full_numbers",
		"table-layout": "fixed",
		"select": {
			"style": "multi",
			"selector": ':not(td:nth-child(-n+6))',
			"info": false
		},
		"language": dataTableLang,
		"processing": true,
		"serverSide": true,
		"columns": [
			{ data: 'nombre' },
			{ data: 'idExtPer' },
			{ data: 'tarjeta' },
			{ data: 'saldo' }
	],
	"columnDefs": [
		{
			"targets": 0,
			"className": "nombre",
		},
		{
			"targets": 1,
			"className": "idExtPer",
		},
		{
			"targets": 2,
			"className": "tarjeta",
		},
		{
			"targets": 3,
			"className": "saldo",
		}
	],

		"ajax": {
			url: baseURL + 'async-call',
			method: 'POST',
			dataType: 'json',
			cache: false,
			data: function (req) {
				data = req
				data.idExtPer = "";
				data.producto = $("#productCode").val();
				data.idExtEmp = $('#enterpriseReport').find('option:selected').attr('acrif');
				data.screenSize = screen.width;
				data.paginar = true;
				var dataRequest = JSON.stringify({
					who: 'Reports',
					where: 'closingBudgets',
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
				var responseTable = jQuery.parseJSON(resp)

				responseTable = JSON.parse(
					CryptoJS.AES.decrypt(responseTable.code, responseTable.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
				);
				var codeDefaul = parseInt(lang.RESP_DEFAULT_CODE);

				if (responseTable.code === codeDefaul) {
					notiSystem(responseTable.title, responseTable.msg, responseTable.icon, responseTable.dataResp);
				}

				access = responseTable.access;
				return JSON.stringify(responseTable);
			}
		}
	})
	}else{
		var table = $('#balancesClosing').DataTable();
		table.destroy();

		table = $('#balancesClosing').DataTable({
			drawCallback: function (d) {
				insertFormInput(false)
				$('#spinnerBlockBudget').addClass("hide");
				$('#tbody-datos-general').removeClass('hide');
				$('#titleResults').removeClass('hide');
				$('#blockBudgetResults').removeClass("hide");
				$('#pre-loader-table').addClass('hide')
				$('.hide-table').removeClass('hide')
				$('.hide-out').removeClass('hide');
			},
			"ordering": false,
			"searching": false,
			"lengthChange": false,
			"length": 10,
			"pagingType": "full_numbers",
			"table-layout": "fixed",
			"select": {
				"style": "multi",
				"selector": ':not(td:nth-child(-n+6))',
				"info": false
			},
			"language": dataTableLang,
			"processing": true,
			"serverSide": true,
			"columns": [
				{ data: 'nombre' },
				{ data: 'idExtPer' },
				{ data: 'tarjeta' },
				{ data: 'saldo' },
				{ data: 'fechaUltAct' }
			],
			"columnDefs": [
				{
					"targets": 0,
					"className": "nombre",
				},
				{
					"targets": 1,
					"className": "idExtPer",
				},
				{
					"targets": 2,
					"className": "tarjeta",
				},
				{
					"targets": 3,
					"className": "saldo",
				},
				{
					"targets": 4,
					"className": "fechaUltAct",
				}
			],
			"ajax": {
				url: baseURL + 'async-call',
				method: 'POST',
				dataType: 'json',
				cache: false,
				data: function (req) {
					data = req
					data.idExtPer = "";
					data.producto = $("#productCode").val();
					data.idExtEmp = $('#enterpriseReport').find('option:selected').attr('acrif');
					data.tamanoPagina = 10;
					data.paginar = true;
					data.paginaActual = data.draw;
					data.screenSize = screen.width;

					var dataRequest = JSON.stringify({
						who: 'Reports',
						where: 'closingBudgets',
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

					var responseTable = jQuery.parseJSON(resp)

					responseTable = JSON.parse(
					CryptoJS.AES.decrypt(responseTable.code, responseTable.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
				);

				var codeDefaul = parseInt(lang.RESP_DEFAULT_CODE);

				if (responseTable.code === codeDefaul) {
					notiSystem(responseTable.title, responseTable.msg, responseTable.icon, responseTable.dataResp);
				}

				access = responseTable.access;
				return JSON.stringify(responseTable);
				}
			}
		})
	}
}
