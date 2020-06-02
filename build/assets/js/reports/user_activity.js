'use strict'
var reportsResults;
var modalReq = {};
$(function () {
	var datePicker = $('.date-picker');
	$('#blockResultsUser').addClass("hide");
	$('#titleResults').addClass('hide');

	datePicker.datepicker({
	onSelect: function (selectedDate) {
		var dateSelected = selectedDate.split('/');
		dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
		var inputDate = $(this).attr('id');
		var maxTime = new Date(dateSelected);

		if (inputDate == 'initialDateAct') {
			$('#finalDateAct').datepicker('option', 'minDate', selectedDate);
			maxTime.setDate(maxTime.getDate() - 1);
			maxTime.setMonth(maxTime.getMonth() + 3);

			if (currentDate > maxTime) {
				$('#finalDateAct').datepicker('option', 'maxDate', maxTime);
			}
		}
			if (inputDate == 'finalDateAct') {
			$('#initialDateAct').datepicker('option', 'maxDate', selectedDate);
			}
		}
	});

	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	var concenAccount = $('#concenAccount').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

	$('#concenAccount tbody').on('click', 'button.details-user', function (e) {
    var tr = $(this).closest('tr');
    var row = concenAccount.row(tr);

    if (row.child.isShown()) {
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    } else {
      // Open this row
      row.child(format(row.data())).show();
      tr.addClass('shown');
    }
  });
});

$("#export_excel").click(function(){
  ExcelPassData()
});
$("#export_pdf").click(function(){
  PDFPassData()
});

$("#userActivity-Btn").on('click', function(e){
  $('#blockResultsUser').addClass("hide");
  form = $('#userActivityForm');
  validateForms(form)
  if (form.valid()) {
    info(e);
  }
})

function info(e){
	$('#spinnerBlockMasterAccount').removeClass("hide");
  var acCodCia = $('#enterprise-report').find('option:selected').attr('code');
  var fechaIni =  $("#initialDateAct").val();
  var fechaFin = $("#finalDateAct").val();

  var passData = {
    acCodCia: acCodCia,
    fechaIni: fechaIni,
    fechaFin: fechaFin,
  };

  userActivity(passData);
}

function userActivity(passData) {
  verb = "POST"; who = 'Reports'; where = 'userActivity'; data = passData;
  callNovoCore(verb, who, where, data, function(response) {
    $('#spinnerBlockMasterAccount').addClass("hide");
		$('#tbody-datos-general').removeClass('hide');
		$('#blockResultsUser').removeClass("hide");
		$('#titleResults').removeClass("hide");
		$('#files-btn').removeClass("hide");
    var table = $('#concenAccount').DataTable();
    table.destroy();
      dataResponse = response.data
      code = response.code
      // if( code == 0){
        var info = response.data.lista;
        var info1 = [];
        var info2 = {};
        var info3 = [];
				var info4 = [];
        var info5 = [];

        $.each(info, function(key, val){
          info1[key] = val.funciones.lista;
          info5[key] = val.actividades.lista;
       })
       $.each(info1, function(key, val){
        info2[key] = info1[key];
        $.each(info2, function(key, val){
          info3[key] = info2[key];
       })
		 })

		var i = 0;
		table = $('#concenAccount').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"language": dataTableLang,
		"data": info,
		"createdRow": function( row, data, dataIndex ) {
		 $(row).attr( 'numRuf', dataIndex );
		},
    "columns": [
      { data: 'userName' },
      { data: 'conectado' },
      { data: 'fechaUltimaConexion' },
        {
          "className":    'TableButtons',
					"orderable":    false,
          render: function (data, type, full, meta) {
            return'<tr><td class="flex justify-center items-center"><button id="seeActivity" class="btn px-0 details-user" title="Ver actividades" data-toggle="tooltip"><i class="icon icon-find mr-1" aria-hidden="true"></i></button><button  id="seeActivity2" class="activity btn px-1"  title="funciones"><i class="icon novoglyphs icon-info" aria-hidden="true"></i></button></td></tr>'
					}
				},
    ]
})

$('#tbody-datos-general').delegate('#seeActivity','click',function() {
	var filaDeLaTabla = $(this).closest('tr');
  var filaComplementaria = table.row(filaDeLaTabla);
  var celdaDeIcono = $(this).closest('#seeActivity');
  var data;

	if (filaComplementaria.child.isShown() ) {
    filaComplementaria.child.hide();
	} else {
		filaComplementaria.child(format(info5[$(this).parents('tr').attr('numRuf')])).show();
	}
})

$('#tableAtivity').DataTable({
  "responsive": true,
  "ordering": false,
  "pagingType": "full_numbers",
  "language": dataTableLang,
  "data": info3,
  "columns": [
    { data: 'accodusuario' },
    { data: 'acnomfuncion' },
    { data: 'accodfuncion' },
	]
})

$('#tbody-datos-general').delegate('.activity','click',function(e) {
	dialogE(e);
	$('#activityTable').DataTable({
		"paging":   false,
		"bFilter": false,
		"info":     false,
		"order": false,
		"bDestroy": true,
		"data": info3[$(this).parents('tr').attr('numRuf')],
		"columns": [
			{ data: 'acnomfuncion' },
		]
	})
	$("#activityTable thead").remove();
		})
	})
}

function ExcelPassData(){
  var acCodCia = $('#enterprise-report').find('option:selected').attr('code');
  var fechaIni =  $("#initialDateAct").val();
  var fechaFin = $("#finalDateAct").val();
	var rifEmpresa = $('#enterprise-report').find('option:selected').attr('acrif');

  var passData = {
    modalReq: true,
    acCodCia: acCodCia,
    fechaIni: fechaIni,
    fechaFin: fechaFin,
    rifEmpresa : rifEmpresa
  };

  exportToExcel(passData);
}

function exportToExcel(passData) {
  verb = "POST"; who = 'Reports'; where = 'exportToExcelUserActivity'; data = passData;
  callNovoCore(verb, who, where, data, function(response) {
      dataResponse = response.data;
      code = response.code
      var info = dataResponse;
      if(code == 0){
      var File = new Int8Array(info.archivo);
      byteArrayFile([File], 'actividadUsuario.xls');
      $('.cover-spin').removeAttr("style");
    }
})
}

function PDFPassData(){
  var acCodCia = $('#enterprise-report').find('option:selected').attr('code');
  var fechaIni =  $("#initialDateAct").val();
  var fechaFin = $("#finalDateAct").val();
  var rifEmpresa = $('#enterprise-report').find('option:selected').attr('acrif');

  var passData = {
    acCodCia: acCodCia,
    fechaIni: fechaIni,
    fechaFin: fechaFin,
    rifEmpresa : rifEmpresa
  };

  exportToPDF(passData);
}

function exportToPDF(passData) {
  verb = "POST"; who = 'Reports'; where = 'exportToPDFUserActivity'; data = passData;
  callNovoCore(verb, who, where, data, function(response) {
      dataResponse = response.data;
      code = response.code
      var info = dataResponse;
      if(code == 0){
      var File = new Int8Array(info.archivo);
      byteArrayFile([File], 'actividadUsuario.pdf');
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


function dialogE(e){

  e.preventDefault();
  var event = $(e.currentTarget);
  var action = event.attr('title');
  var submitForm = false;
  $(this).closest('tr').addClass('select');
  var titleModal = 'Funciones';
  var inputModal;
  modalReq['table'] = $(this).closest('table');
  data = {
    btn1: {
    text: 'Salir',
    action: 'close'
  	}
  }
	inputModal =  '<form  class="form-group">';
  inputModal+=    '<div id="functionsBlock" class="input-group">';
  inputModal+= '<table id=activityTable></table>'
  inputModal+=    '</div>';
  inputModal+=  '</form>';

	notiSystem(titleModal, inputModal, lang.GEN_ICON_INFO, data);
	};

function format(user) {
	var table, body = '';
	$.each(user, function (key, value) {
		body+=	'<tr>';
		body+= 		'<td>'+ user[key].modulo +'</td>';
		body+= 		'<td>'+ user[key].funcion +'</td>';
		body+= 		'<td>'+ user[key].dttimesstamp +'</td>';
		body+=	'</tr>';
	})
		table= 	'<table class="detail-lot h6 cell-border primary semibold" style="width:100%">';
		table+= 	'<tbody>';
		table+= 		'<tr class="bold" style="margin-left: 0px;">';
		table+= 			'<td>' + lang.GEN_TABLE_USERACT_MODULE + '</td>';
		table+= 			'<td>' + lang.GEN_TABLE_USERACT_FUNCTION + '</td>';
		table+= 			'<td>' + lang.GEN_TABLE_USERACT_DATE + '</td>';
		table+= 		'</tr>';
		table+= 		body;
		table+= 	'</tbody>';
		table+= '</table>';
		return table;
}
