'use strict'
var reportsResults;
var modalReq = {};
$(function () {
	var datePicker = $('.date-picker');
	$('#blockResultsUser').addClass("hide");
	$('#titleResults').addClass('hide');

	datePicker.datepicker({
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
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
				} else {
					$('#finalDateAct').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	var concenAccount = $('#concenAccount').DataTable({
		"ordering": false,
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
	$('#spinnerBlock').removeClass("hide");
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
    $('#spinnerBlock').addClass("hide");
		$('#tbody-datos-general').removeClass('hide');
		$('#blockResultsUser').removeClass("hide");
		$('#titleResults').removeClass("hide");
		$('#files-btn').removeClass("hide");
    var table = $('#concenAccount').DataTable();
    table.destroy();
		dataResponse = response.data
    code = response.code
    if( code == 0){
			$('#buttonFiles').removeClass('hidden');
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

			table = $('#concenAccount').DataTable({
				"ordering": false,
				"pagingType": "full_numbers",
				"data": info,
				"language": dataTableLang,
				"createdRow": function( row, data, dataIndex ) {
		 			$(row).attr( 'numRuf', dataIndex );
				},
    		"columns": [
      		{ data: 'userName' },
      		{ data: 'estatus' },
      		{ data: 'fechaUltimaConexion' },
        	{
          	"className":    'TableButtons',
						"orderable":    false,
          	render: function (data, type, full, meta) {
            	return'<tr><td class="flex justify-center items-center"><button id="seeActivity" class="btn px-0 details-user" title="Ver actividades" data-toggle="tooltip"><i class="icon icon-find mr-1" aria-hidden="true"></i></button><button  id="seeActivity2" class="activity btn px-1"  title="funciones"><i class="icon icon-info" aria-hidden="true"></i></button></td></tr>'
						}
					},
				]
			})

			$('#tbody-datos-general').delegate('#seeActivity','click',function() {
			var rowTable = $(this).closest('tr');
			var complementaryRow = table.row(rowTable);

			if (complementaryRow.child.isShown() ) {
				$('.complementary').parents('tr').remove();
				complementaryRow.child(format(info5[$(this).parents('tr').attr('numRuf')])).hide();
			}
			if($('.complementary').parents('tr')){
				$('.complementary').parents('tr').remove();
			}
			complementaryRow.child(format(info5[$(this).parents('tr').attr('numRuf')])).show();
			})

			$('#tableAtivity').DataTable({
  			"responsive": true,
  			"ordering": false,
				"pagingType": "full_numbers",
				"oLanguage": {
				"sEmptyTable": "No hay registros disponibles"
				},
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
					"oLanguage": {
						"sEmptyTable": "No hay Funciones disponibles"
					},
					"data": info3[$(this).parents('tr').attr('numRuf')],
					"columns": [
						{ data: 'acnomfuncion' },
					]
				})
				$("#activityTable thead").remove();
			})
		} else if(code == 1){
			$('#buttonFiles').addClass('hidden');
			$('#concenAccount').DataTable({
				"ordering": false,
				"pagingType": "full_numbers",
				"data": [],
				"language": dataTableLang,
    		"columns": [
      		{ data: 'userName' },
      		{ data: 'estatus' },
					{ data: 'fechaUltimaConexion' },
					{ data: 'options' },
				]
			})
		}
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
			if(info.formatoArchivo == 'PDF'){
				info.formatoArchivo = '.pdf'
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

	appMessages(titleModal, inputModal, lang.GEN_ICON_INFO, data);
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
		table= 	'<table class="complementary h6 cell-border primary semibold" style="width:100%">';
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
