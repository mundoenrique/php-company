'use strict'
var resultServiceOrders;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var serviceOrdersBtn = $('#service-orders-btn');
	var firstDate;
	var lastdate;

	resultServiceOrders = $('#resultServiceOrders').DataTable({
		drawCallback: function (d) {
			$('#loader-table').remove();
			$('.hide-table').removeClass('hide');
		},
		"ordering": false,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

	$('#resultServiceOrders tbody').on('click', 'button.details-control', function () {
		var oldTr = $(this).closest('tbody').find('tr.shown');
		var oldRow = resultServiceOrders.row(oldTr);
		var tr = $(this).closest('tr');
		var row = resultServiceOrders.row(tr);
		var bulk = $(this).closest('tr').attr('bulk')

		if (!tr.hasClass('shown')) {
			oldRow.child.hide();
			oldTr.removeClass('shown');
		}

		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
		} else {
			row.child(format(bulk)).show();
			tr.addClass('shown');
		}
	});

	$('#resultServiceOrders tbody').on('click', 'a.this-bulk', function() {
		var bulkdetail = $(this).siblings('form');
		insertFormInput(true, bulkdetail)
		$('.cover-spin').show(0);
		bulkdetail.submit();
	});

	$('.date-picker').datepicker({
		onSelect: function (selectedDate) {
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if (inputDate == 'datepicker_start') {
				$('#datepicker_end').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + 3);

				if (currentDate > maxTime) {
					$('#datepicker_end').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#datepicker_end').datepicker('option', 'maxDate', currentDate);
				}
			}

			if ($('#datepicker_start').val() != '' || $('#datepicker_end').val() != '') {
				$('input:radio').prop('checked', false);
				firstDate = $('#datepicker_start').val();
				lastdate = $('#datepicker_end').val();
			}
		}
	});

	$(":radio").on("change", function () {
		$('#datepicker_start, #datepicker_end').datepicker('setDate', null);
		form = $('#service-orders-form');
		form.find('.form-control').removeClass('has-error')
		$('.help-block').text('');
		var timeBefore = parseInt($(this).val());
		var initDate = new Date();
		var finalDate = new Date();
		initDate.setDate(initDate.getDate() - timeBefore);
		initDate = initDate.getDate() + '/' + (initDate.getMonth() + 1) + '/' + initDate.getFullYear();
		finalDate = finalDate.getDate() + '/' + (finalDate.getMonth() + 1) + '/' + finalDate.getFullYear();
		firstDate = initDate;
		lastdate = finalDate;
	});

	serviceOrdersBtn.on('click', function (e) {
		e.preventDefault();
		var btnAction = $(this);
		var statusOrder = $('#status-order');
		btnText = btnAction.text().trim();
		form = $('#service-orders-form');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			btnAction.html(loader);
			data = {
				initialDate: firstDate,
				finalDate: lastdate,
				status: statusOrder.val(),
				statusText: statusOrder.find('option:selected').text().trim()
			}

			insertFormInput(true);
			verb = 'POST'; who = 'Inquiries'; where = 'GetServiceOrders';
			callNovoCore(verb, who, where, data, function (response) {
				if (response.code == 0) {
					$(location).attr('href', response.data);
				} else {
					btnAction.html(btnText);
					insertFormInput(false);
					$('#resultServiceOrders').dataTable().fnClearTable();
    			$('#resultServiceOrders').dataTable().fnDestroy();
					$('.hide-table').addClass('hide');
				}
			});
		}

	});

	$('#resultServiceOrders').on('click', 'button', function (e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		form = $(this).parent().find('form');

		switch (action) {
			case lang.GEN_BTN_DOWN_PDF:
				insertFormInput(true, form);
				form.submit()
				setTimeout(function () {
					$('.cover-spin').hide();
				}, lang.GEN_TIME_DOWNLOAD_FILE);
				insertFormInput(false);
				break;
			case lang.GEN_BTN_CANCEL_ORDER:
				var oldID = $('#accept').attr('id');
				var inputModal;
				var inputSelected = form.find('input[name="OrderNumber"]').val();
				$(this).closest('tr').addClass('select');
				$('#accept').attr('id', 'delete-bulk-btn');
				data = {
					btn1: {
						text: lang.GEN_BTN_CANCEL_ORDER,
						action: 'none'
					},
					btn2: {
						text: lang.GEN_BTN_CANCEL,
						action: 'close'
					}
				}
				inputModal =	'<form id="delete-bulk-form" name="delete-bulk-form" class="form-group" onsubmit="return false;">';
				inputModal+= 		'<span class="regular">'+lang.GEN_BULK_DELETE_SO+': '+inputSelected+'</span>';
				inputModal+=		'<div class="input-group">';
				inputModal+= 			'<input id="password" class="form-control pwd-input" name="password" type="password" autocomplete="off"';
				inputModal+=				'placeholder="'+lang.GEN_PLACE_PASSWORD+'">';
				inputModal+=			'<div class="input-group-append">';
				inputModal+=				'<span class="input-group-text pwd-action" title="'+lang.GEN_SHOW_PASS+'"><i class="icon-view mr-0"></i></span>';
				inputModal+=			'</div>';
				inputModal+=		'</div>';
				inputModal+= 		'<div class="help-block"></div>';
				inputModal+= 	'</form>';
				notiSystem('Anular orden de servicio', inputModal, lang.GEN_ICON_INFO, data);
				deleteBulk(oldID, inputSelected);

				$('#cancel').on('click', function (e) {
					e.preventDefault();
					$('#pending-bulk').find('tr').removeClass('select');
					$('#delete-bulk-btn').attr('id', oldID);
				});
				break;
		}
	});
})

function format(bulk) {
	var table, body = '';
	bulk = JSON.parse(bulk)
	$.each(bulk, function (key, value) {
		body+=	'<tr>';
		body+= 		'<td>';
		body+=			'<a class="btn-link big-modal this-bulk">'+value.bulkNumber+'</a>';
		body+= 			'<form class="form-group" action="'+baseURL+'consulta-lote" method="post">';
		body+= 				'<input type="hidden" name="bulkId" value='+value.bulkId+'>';
		body+= 				'<input type="hidden" name="bulkfunction" value="Consulta de orden de servicio">';
		body+=			'</form>';
		body+=		'</td>';
		body+= 		'<td>'+value.bulkLoadDate+'</td>';
		body+= 		'<td>'+value.bulkLoadType+'</td>';
		body+= 		'<td>'+value.bulkRecords+'</td>';
		body+= 		'<td>'+value.bulkStatus+'</td>';
		body+= 		'<td>'+value.bulkAmount+'</td>';
		body+= 		'<td>'+value.bulkCommisAmount+'</td>';
		body+= 		'<td>'+value.bulkTotalAmount+'</td>';
		body+=	'</tr>';

	})
	table= 	'<table class="detail-lot h6 cell-border primary semibold" style="width:100%">';
	table+= 	'<tbody>';
	table+= 		'<tr class="bold" style="margin-left: 0px;">';
	table+= 			'<td>' + lang.GEN_TABLE_BULK_NUMBER + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_BULK_DATE + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_TYPE + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_RECORDS + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_STATUS + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_AMOUNT + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_COMMISSION + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_DEPOSIT_AMOUNT + '</td>';
	table+= 		'</tr>';
	table+= 		body;
	table+= 	'</tbody>';
	table+= '</table>';

	return table;
}
/**
 * @info Elimina un lote
 * @author J. Enrique Peñaloza Piñero
 * @date December 18th, 2019
 */
function deleteBulk(oldID, inputSelected) {
	var deleteBulkBtn = $('#delete-bulk-btn')
	var formDeleteBulk = $('#delete-bulk-form');
	deleteBulkBtn.on('click', function () {
		formInputTrim(formDeleteBulk);
		validateForms(formDeleteBulk);

		if (formDeleteBulk.valid()) {
			insertFormInput(true);
			$(this)
			.off('click')
			.html(loader)
			.prop('disabled', true)
			.attr('id', oldID);
			inputPass = cryptoPass($('#password').val());
			data = {
				modalReq: true,
				OrderNumber: inputSelected,
				pass: inputPass
			}
			verb = 'POST'; who = 'Inquiries'; where = 'ClearServiceOrders';
			callNovoCore(verb, who, where, data, function (response) {

				if (response.cod == 0) {
					resultServiceOrders.row('.select').remove().draw(false);
				}

				insertFormInput(false);
			});
		}
	});
}
