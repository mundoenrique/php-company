'use strict'
var datePicker = $('.date-picker');
var usersActivityData;
var lastActionsList = [];
var enabledFunctionsList = [];
var usersActivityTable = $('#usersActivity');
var usersActivityMainTable;
var downloadFormat;
var userActivityOptions = '';
userActivityOptions += '<tr>';
userActivityOptions += 	'<td class="flex justify-center items-center">';
userActivityOptions += 		'<button class="btn px-0 details-user lastActionsBtn" title="Ultimas acciones" data-toggle="tooltip">';
userActivityOptions += 			'<i class="icon icon-user mr-1" aria-hidden="true"></i>';
userActivityOptions += 		'</button>';
userActivityOptions += 		'<button class="btn px-0 details-user enabledFunctionsBtn" title="Funciones habilitadas" data-toggle="tooltip">';
userActivityOptions += 			'<i class="icon icon-user-config mr-1" aria-hidden="true"></i>';
userActivityOptions += 		'</button>';
userActivityOptions += 	'</td>';
userActivityOptions += '</tr>';

$(function () {
	$('#titleResults').addClass('hide');
	$('#blockResultsUser').addClass("hide");
	$('#preLoader').remove();
	$('.hide-out').removeClass('hide');
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
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.SETT_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#finalDate').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	$('#userActivityBtn').on('click', function(e) {
		e.preventDefault();
		form = $('#userActivityForm');
		validateForms(form);

		if (form.valid()) {
			$('#spinnerBlock').removeClass("hide");
			$('#titleResults').addClass("hide");
			$('#blockResultsUser').addClass("hide");
			usersActivityTable.dataTable().fnClearTable();
			usersActivityTable.dataTable().fnDestroy();
			who = 'Reports';
			where = 'userActivity';
			data = getDataForm(form);
			insertFormInput(true);

			callNovoCore(who, where, data, function(response) {

				if (response.code == 0) {
					$('#spinnerBlock').addClass("hide");
					$('#titleResults').removeClass("hide");
					$('#blockResultsUser').removeClass("hide");
					usersActivityData = response.data.usersActivity;

					$.each(usersActivityData, function(key, val){
						lastActionsList[key] = val.lastActions;
						enabledFunctionsList[key] = val.enabledFunctions;
					});

					createTable(usersActivityData);
					insertFormInput(false);
					$('#blockResultsUser').removeClass("hide");
					$('#spinnerBlock').addClass("hide");
				} else {
					$('#spinnerBlock').addClass("hide");
					insertFormInput(false);
				}
			});
		}
	});

	//Tabla de ultimas acciones realizadas
	$('#usersActivityOptions').delegate('.lastActionsBtn', 'click', function () {
		var oldTr = $(this).closest('tbody').find('tr.shown');
		var oldRow = usersActivityMainTable.row(oldTr);
		var tr = $(this).closest('tr');
		var row = usersActivityMainTable.row( tr );
		var user = lastActionsList[$(this).parents('tr').attr('userId')];

		if (!tr.hasClass('shown')) {
			oldRow.child.hide();
			oldTr.removeClass('shown');
		}

		if (row.child.isShown() && tr.hasClass('enabledFunctions')) {
			tr.removeClass('shown enabledFunctions');
			row.child(lastActions(user)).show();
			tr.addClass('shown lastActions');
		} else {

			if (row.child.isShown()) {
				row.child.hide();
				tr.removeClass('shown lastActions');
			} else {
				row.child(lastActions(user)).show();
				tr.addClass('shown lastActions');
			}
		}
	});

	//Tabla de funciones habilitadas
	$('#usersActivityOptions').delegate('.enabledFunctionsBtn', 'click', function () {
		var oldTr = $(this).closest('tbody').find('tr.shown');
		var oldRow = usersActivityMainTable.row(oldTr);
		var tr = $(this).closest('tr');
		var row = usersActivityMainTable.row( tr );
		var user = enabledFunctionsList[$(this).parents('tr').attr('userId')];

		if (!tr.hasClass('shown')) {
			oldRow.child.hide();
			oldTr.removeClass('shown');
		}

		if (row.child.isShown() && tr.hasClass('lastActions')) {
			tr.removeClass('shown lastActions');
			row.child(enabledFunctions(user)).show();
			tr.addClass('shown enabledFunctions');
		} else {

			if ( row.child.isShown() ) {
				row.child.hide();
				tr.removeClass('shown enabledFunctions');
			} else {
				row.child(enabledFunctions(user)).show();
				tr.addClass('shown enabledFunctions');
			}
		}

	});

	//Descargar reporte en formato Excel o PDF
  $('.downloadReport').on('click', function (e) {

    var action = $(this).attr('format');

    if (lang.SETT_DOWNLOAD_SERVER === 'ON') {
      switch (action) {
        case 'Excel':
          ActivityUserDownloadFiles('generarArchivoXlsActividadesXUsuario','Xls')
          break;
        case 'PDF':
          ActivityUserDownloadFiles('generarPdfActividadesXUsuario','Pdf')
          break;
        case 'Txt':
          ActivityUserDownloadFiles('generarTxtActividadesXUsuario','Txt')
          break;
      }
    } else {
      downLoadReport(e)
    }
  });

  function downLoadReport(e) {
    e.preventDefault();
      form = $('#userActivityForm');
      validateForms(form);

      if (form.valid()) {
        $('.cover-spin').show();
        who = 'Reports';
        where = 'exportReportUserActivity';
        data = getDataForm(form);
        data.rifEnterprise = $('#enterpriseCode').find('option:selected').attr('acrif');
        data.downloadFormat = $(this).attr('format');

        callNovoCore(who, where, data, function(response) {

          if (response.code == 0) {
            downLoadfiles (response.data);
          }

          $('.cover-spin').hide();
        });
      }
  }

  function ActivityUserDownloadFiles(operation, type) {
    var form = $('#userActivityForm');
    var data = getDataForm(form)
    insertFormInput(true);
    who = 'Reports';
    where = `exportToActivityUser`;
    data.rifEnterprise = $('#enterpriseCode').find('option:selected').attr('acrif');
    data.operation = operation
    data.type = type
    callNovoCore(who, where, data, function (response) {
      if (response.code == 0) {
        $('#download-file').attr('href', response.data.file);
        document.getElementById('download-file').click();
        who = 'DownloadFiles';
        where = 'DeleteFile';
        data.fileName = response.data.name
        callNovoCore(who, where, data, function (response) {})
      }
      insertFormInput(false);
      $('.cover-spin').hide();
    })
  }
});




//Tabla principal
function createTable(usersActivityData){
	usersActivityMainTable = usersActivityTable.DataTable({
		"ordering": true,
		"responsive": true,
		"pagingType": "full_numbers",
		"data": usersActivityData,
		"createdRow": function( row, data, dataIndex ) {
			$(row).attr( 'userId', dataIndex );
		},
		"language": dataTableLang,
		"columns": [
			{ data: 'user' },
			{ data: 'userStatus' },
			{ data: 'lastConnectionDate' },
			{
				render: function() {
					return userActivityOptions;
				}
			},
		],
	});
};

function lastActions(user) {
	var table, body = '';

	$.each(user, function (key, value) {
		body+=	'<tr>';
		body+= 		'<td>'+ user[key].modulo +'</td>';
		body+= 		'<td>'+ user[key].funcion +'</td>';
		body+= 		'<td>'+ user[key].dttimesstamp +'</td>';
		body+=	'</tr>';
	});

	table= 	'<table class="h6 cell-border primary semibold responsive w-100">';
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
};

function enabledFunctions(user) {
	var table, body = '';

	$.each(user, function (key, value) {
		body+=	'<tr>';
		body+= 		'<td>'+ user[key].accodfuncion +'</td>';
		body+= 		'<td>'+ user[key].acnomfuncion +'</td>';
		body+= 		'<td>'+ user[key].status +'</td>';
		body+=	'</tr>';
	});

	table= 	'<table class="h6 cell-border primary semibold responsive w-100">';
	table+= 	'<tbody>';
	table+= 		'<tr class="bold" style="margin-left: 0px;">';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_MODULE + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_FUNCTION + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_STATUS + '</td>';
	table+= 		'</tr>';
	table+= 		body;
	table+= 	'</tbody>';
	table+= '</table>';

	return table;
};
