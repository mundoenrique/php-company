'use strict'
var resultServiceOrders;

$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
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
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
			dateSelected = new Date(dateSelected);
			var inputDate = $(this).attr('id');

			if (inputDate == 'datepicker_start') {
				$('#datepicker_end').datepicker('option', 'minDate', dateSelected);
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.SETT_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

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

	$('#service-orders-btn').on('click', function (e) {
		e.preventDefault();
		var btnAction = $(this);
		var statusOrder = $('#status-order');
		btnText = btnAction.text().trim();
		form = $('#service-orders-form');
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
			who = 'Inquiries';
			where = 'GetServiceOrders';

			callNovoCore(who, where, data, function (response) {
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
				$('.cover-spin').show();

				who = 'Inquiries';
				where = 'ExportFiles';
				data = {
					OrderNumber: form.find('input[name="OrderNumber"]').val()
				}

				callNovoCore(who, where, data, function (response) {

					switch (response.code) {
						case 0:
							downLoadfiles(response.data);
							break;
						case 1:
							appMessages(response.title, response.msg, response.icon, response.modalBtn);
							$('.cover-spin').hide();
							break;
						default:
							location.reload(true);
							break;
					}
				});
				break;
			case lang.GEN_BTN_CANCEL_ORDER:
				var oldID = $('#accept').attr('id');
				var inputSelected = form.find('input[name="OrderNumber"]').val();
				$(this).closest('tr').addClass('select');
				$('#accept').attr('id', 'delete-bulk-btn');
				modalBtn = {
					btn1: {
						text: lang.GEN_BTN_CANCEL_ORDER,
						action: 'none'
					},
					btn2: {
						text: lang.GEN_BTN_CANCEL,
						action: 'destroy'
					}
				}
				inputModal =	'<form id="delete-bulk-form" name="delete-bulk-form" class="form-group" onsubmit="return false;">';
				inputModal+= 		'<span class="regular">'+lang.GEN_BULK_DELETE_SO+': '+inputSelected+'</span>';

				if (lang.SETT_REMOTE_AUTH == 'OFF') {
					inputModal+=		'<div class="input-group">';
					inputModal+=			'<input id="password" class="form-control pwd-input pwd" name="password" type="password" ';
					inputModal+= 				'autocomplete="off" placeholder="'+lang.GEN_PLACE_PASSWORD+'">';
					inputModal+=			'<div class="input-group-append">';
					inputModal+=				'<span class="input-group-text pwd-action" title="'+lang.GEN_SHOW_PASS+'"><i class="icon-view mr-0"></i></span>';
					inputModal+=			'</div>';
					inputModal+=		'</div>';
					inputModal+= 		'<div class="help-block"></div>';
				}

				inputModal+= 	'</form>';
				appMessages('Anular orden de servicio', inputModal, lang.SETT_ICON_INFO, modalBtn);
				deleteBulk(oldID, inputSelected);

				$('#cancel').on('click', function (e) {
					e.preventDefault();
					$('#pending-bulk').find('tr').removeClass('select');
					$('#delete-bulk-btn').attr('id', oldID);
				});
				break;
			case lang.PAG_OS_TITLE:
				var idOS = form.find('input[name="OrderNumber"]').val();
				var ordenService = $(this).closest('tr').attr('pagoOS');
				ordenService = JSON.parse(ordenService);
				$('#idOS').val(idOS);
				$('#totalAmount').val(ordenService.total);
				$('#noFactura').val(ordenService.factura);
				$('.cover-spin').show();
				getOtpService();
				break;
		}
	});

	$('#system-info').on('click', '.send-otp', function() {
		form = $('#formVerificationOTP');
		validateForms(form);

		if (form.valid()) {
			$(this)
				.html(loader)
				.prop('disabled', true)
				.removeClass('send-otp');
			insertFormInput(true);
			payOrderService();
		}
	});

	$('#system-info').on('click', '.get-otp', function() {

		$(this)
			.html(loader)
			.prop('disabled', true)
			.removeClass('get-otp');
		getOtpService()
	});

	$('#system-info').on('click', '.viewOS', function () {
		form = $('#pagoOrden');
		validateForms(form);

		if (form.valid()) {
			$(this)
				.html(loader)
				.prop('disabled', true)
				.removeClass('viewOS');
			insertFormInput(true);
			$('.cover-spin').show();

			who = 'Inquiries';
			where = 'GetServiceOrders';

			var initDate = new Date();
			var dataFormOS = {
				initialDate : initDate.getDate() + '/' + (initDate.getMonth() + 1) + '/' + initDate.getFullYear(),
				finalDate: initDate.getDate() + '/' + (initDate.getMonth() + 1) + '/' + initDate.getFullYear(),
				status: 3,
				statusText: 'En Proceso'
			}

			callNovoCore(who, where, dataFormOS, function (response) {
				if (response.code == 0) {
					$(location).attr('href', response.data);
				} else {
					insertFormInput(false);
				}
			});
		}
	});

})

function format(bulk) {
	var table, body = '';
	var orderList = cryptoPass($('#resultServiceOrders').attr('orderList'));
	bulk = JSON.parse(bulk)
	$.each(bulk, function (key, value) {
		body+=	'<tr>';
		body+= 		'<td>';
		body+=			'<a class="btn-link big-modal this-bulk">'+value.bulkNumber+'</a>';
		body+= 			'<form class="form-group" action="'+ baseURL + lang.SETT_LINK_INQUIRY_BULK_DETAIL +'" method="post">';
		body+= 				'<input type="hidden" name="bulkId" value="'+value.bulkId+'">';
		body+= 				'<input type="hidden" name="orderList" value="'+orderList+'">';
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
		body+=		'</tr>';
		if (lang.SETT_SERVICEORDERS_ICON == 'ON' && value.bulkObservation != '') {
			body+=		'<tr>';
			body+=			'<td colspan="8">';
			body+=				'<span>'+value.bulkObservation+'</span>';
			body+=			'</td>';
			body+=		'</tr>';
		}
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
	table+= 			'<td>' + lang.GEN_TABLE_COMMISSION_GMF + '</td>';
	table+= 			'<td>' + lang.GEN_TABLE_DEPOSIT_AMOUNT + '</td>';
	table+= 		'</tr>';
	table+= 		body;
	table+= 	'</tbody>';
	table+= '</table>';

	return table;
}

function deleteBulk(oldID, inputSelected) {
	var deleteBulkBtn = $('#delete-bulk-btn')
	var formDeleteBulk = $('#delete-bulk-form');
	deleteBulkBtn.on('click', function () {
		validateForms(formDeleteBulk);

		if (formDeleteBulk.valid()) {
			insertFormInput(true);
			$(this)
				.off('click')
				.html(loader)
				.prop('disabled', true)
				.attr('id', oldID);

			data = {
				OrderNumber: inputSelected
			}

			if (lang.SETT_REMOTE_AUTH == 'OFF') {
				data.pass = cryptoPass($('.pwd').val());
			}

			who = 'Inquiries';
			where = 'ClearServiceOrders';

			callNovoCore(who, where, data, function (response) {
				if (response.cod == 0) {
					resultServiceOrders.row('.select').remove().draw(false);
				}

				insertFormInput(false);
			});
		}
	});
}

function getOtpService() {

	var formGetOtp = $('#pagoOrden');
	validateForms(formGetOtp);
	if (formGetOtp.valid()) {

		data = getDataForm(formGetOtp)
		who = 'Inquiries';
		where = 'pagoOs';

		callNovoCore(who, where, data, function (response) {
			$('.cover-spin').hide()
			switch (response.code) {
				case 0:
					generateModalOTP(response)
					break;
				default:
					appMessages(response.title, response.msg, response.icon, response.modalBtn);
					break;
			}
		});
	}
}

function payOrderService() {

	var formGetOtp = $('#pagoOrden');
	validateForms(formGetOtp);
	if (formGetOtp.valid()) {

		insertFormInput(true);
		data.codeToken = $('#otpCode').val();

		who = 'Inquiries';
		where = 'PagarOS';

		callNovoCore(who, where, data, function (response) {

			switch (response.code) {
				case 0:
					$('#accept').addClass('viewOS');
					appMessages(lang.PAG_OS_TITLE, response.msg, response.icon, response.modalBtn);
					break;
				case 1:
					generateModalOTP(response);
					break;
				case 2:
					$('#accept').addClass('get-otp');
					$('#accept').addClass('btn-modal-large');
					appMessages(lang.PAG_OS_TITLE, response.msg, response.icon, response.modalBtn);
					break;
			}
			insertFormInput(false);
		});
	}
}
