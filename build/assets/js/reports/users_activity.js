'use strict'
var datePicker = $('.date-picker');
var usersActivityData;
var lastActionsList = [];
var enabledFunctionsList = [];
var usersActivityTable = $('#usersActivity');
var usersActivityMainTable;
var userActivityOptions = '';
userActivityOptions += '<tr>';
userActivityOptions += 	'<td class="flex justify-center items-center">';
userActivityOptions += 		'<button id="lastActions" class="btn px-0 details-user" title="Ultimas acciones" data-toggle="tooltip">';
userActivityOptions += 			'<i class="icon icon-user mr-1" aria-hidden="true"></i>';
userActivityOptions += 		'</button>';
userActivityOptions += 		'<button id="enabledFunctions" class="btn px-0 details-user" title="Funciones habilitadas" data-toggle="tooltip">';
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
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if (inputDate == 'initialDate') {
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + 3);

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
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			insertFormInput(true);
			$('#spinnerBlock').removeClass("hide");
			$('#titleResults').addClass("hide");
			$('#blockResultsUser').addClass("hide");
			usersActivityTable.dataTable().fnClearTable();
			usersActivityTable.dataTable().fnDestroy();
			verb = "POST"; who = 'Reports'; where = 'usersActivity'; data;

			callNovoCore(verb, who, where, data, function(response) {

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

	//Tabla de Ultimas acciones realizadas
	$('#usersActivityOptions').delegate('#lastActions', 'click', function () {
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

	//Tabla de Funciones habilitadas
	$('#usersActivityOptions').delegate('#enabledFunctions', 'click', function () {
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

	//Exportar a Excel
	$('#exportExcel').on('click', function(e){
		e.preventDefault();
		form = $('#userActivityForm');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			verb = "POST"; who = 'Reports'; where = 'exportExcelUsersActivity'; data;

			callNovoCore(verb, who, where, data, function(response) {

				if (response.code == 0) {
					downLoadfiles (response.data);
				}
				$('.cover-spin').hide();
			});
		}
	});
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
		body+= 		'<td>'+ user[key].fechaAccion +'</td>';
		body+= 		'<td>'+ user[key].modulo +'</td>';
		body+= 		'<td>'+ user[key].funcion +'</td>';
		body+= 		'<td>'+ user[key].resultado +'</td>';
		body+= 		'<td>'+ user[key].observacion +'</td>';
		body+=	'</tr>';
	});

	table= 	'<table class="h6 cell-border primary semibold responsive w-100">';
	table+= 	'<tbody>';
	table+= 		'<tr class="bold" style="margin-left: 0px;">';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_DATE_ACTION + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_MODULE + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_FUNCTION + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_RESULT + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_OBSERVATION + '</td>';
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
		body+= 		'<td>'+ user[key].modulo +'</td>';
		body+= 		'<td>'+ user[key].funcion +'</td>';
		body+= 		'<td>'+ user[key].estado +'</td>';
		body+= 		'<td>'+ user[key].fechaUltimaAct +'</td>';
		body+=	'</tr>';
	});

	table= 	'<table class="h6 cell-border primary semibold responsive w-100">';
	table+= 	'<tbody>';
	table+= 		'<tr class="bold" style="margin-left: 0px;">';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_MODULE + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_FUNCTION + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_STATE + '</td>';
	table+= 			'<td>' + lang.REPORTS_USERS_ACT_DATE_ACTIVITY + '</td>';
	table+= 		'</tr>';
	table+= 		body;
	table+= 	'</tbody>';
	table+= '</table>';

	return table;
};
