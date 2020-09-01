'use strict'
var table;
var action;
var inputModal;
var dataRquest;
var cardsData;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false);

	$('#tableCardInquiry').on('click', '.toggle-all', function () {
		$(this).closest("tr").toggleClass("selected");
		if ($(this).closest("tr").hasClass("selected")) {
			table.rows().select();
		} else {
			table.rows().deselect();
		}
	})

	$('#searchCardsBtn').on('click', function (e) {
		e.preventDefault();
		$('.help-block').text('');
		$('input, select').removeClass('has-error');
		$('#tableCardInquiry').find('thead > tr').removeClass('selected');
		$('#documentType').find('option:first').val('');
		form = $('#searchCardsForm');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			getCardList(data);
		}
	})

	$('.download-icons').on('click', 'button', function(e) {
		var event = $(e.currentTarget);
		action = event.attr('title').trim();
		fileDownload(action);
	})

	$('#tableCardInquiry').on('click', 'button', function(e) {
		var event = $(e.currentTarget);
		var title = event.attr('title').trim();
		action = event.attr('action');
		table.rows().deselect();
		$('.help-block').text('');
		$('input, select').removeClass('has-error')
		$('#tableCardInquiry').find('thead > tr').removeClass('selected');
		$('#accept').addClass('send-request');
		data = {
			btn1: {
				text: lang.GEN_BTN_SEND,
				action: 'none'
			},
			btn2: {
				text: lang.GEN_BTN_CANCEL,
				action: 'destroy'
			}
		}
		inputModal =	'<form id="modalCardsInquiryForm" name="modalCardsInquiryForm" class="w-100" onsubmit="return false;">';

		if (action == 'UPDATE_DATA') {
			data.maxHeight = 520
			$(this).closest('tr').addClass('update');
			cardsData = table.rows('.update').data();

			inputModal +=		'<div class="form-group col-auto">';
			inputModal += 		'<label>Nombre(s)</label>';
			inputModal += 		'<div class="input-group">';
			inputModal += 			'<input class="form-control" type="text" id="firstName" name="firstName" autocomplete="off" ';
			inputModal +=					'value="'+cardsData[0].names+'">';
			inputModal += 		'</div>';
			inputModal += 		'<div class="help-block"></div>';
			inputModal += 	'</div>';
			inputModal +=		'<div class="form-group col-auto">';
			inputModal += 		'<label>Apellido(s)</label>';
			inputModal += 		'<div class="input-group">';
			inputModal += 			'<input class="form-control" type="text" id="lastName" name="lastName" autocomplete="off" ';
			inputModal += 				'value="'+cardsData[0].lastName+'">';
			inputModal += 		'</div>';
			inputModal += 		'<div class="help-block"></div>';
			inputModal += 	'</div>';
			inputModal +=		'<div class="form-group col-auto">';
			inputModal += 		'<label>Correo</label>';
			inputModal += 		'<div class="input-group">';
			inputModal += 			'<input class="form-control" type="text" id="email" name="email" autocomplete="off" value="'+cardsData[0].email+'">';
			inputModal += 		'</div>';
			inputModal += 		'<div class="help-block"></div>';
			inputModal += 	'</div>';
			inputModal +=		'<div class="form-group col-auto">';
			inputModal += 		'<label>Número movil</label>';
			inputModal += 		'<div class="input-group">';
			inputModal += 			'<input class="form-control" type="text" id="movil" name="movil" autocomplete="off" value="'+cardsData[0].celPhone+'">';
			inputModal += 		'</div>';
			inputModal += 		'<div class="help-block"></div>';
			inputModal += 	'</div>';
		}

		inputModal +=		'<div class="form-group col-auto">';
		inputModal += 		'<div class="input-group">';
		inputModal += 			'<input class="form-control pwd-input pwd" type="password" name="password" autocomplete="off"';
		inputModal += 				'placeholder="' + lang.GEN_PLACE_PASSWORD + '">';
		inputModal += 			'<div class="input-group-append">';
		inputModal += 				'<span class="input-group-text pwd-action" title="' + lang.GEN_SHOW_PASS + '"><i class="icon-view mr-0"></i></span>';
		inputModal += 			'</div>';
		inputModal += 		'</div>';
		inputModal += 		'<div class="help-block"></div>';
		inputModal += 	'</div>';
		inputModal += '</form>';

		notiSystem(title, inputModal, lang.GEN_ICON_INFO, data);
	})

	$('#system-info').on('click', '.send-request', function () {
		form = $('#modalCardsInquiryForm')
		btnText = $(this).text().trim();
		applyActions(action, form, $(this));
	})

	$('#system-info').on('click', '.reload-req', function () {
		form = $('#searchCardsForm')
		$('#accept').removeClass('reload-req');
		getCardList(dataRquest);
	})

	$('#cardsInquiryBtn').on('click', function (e) {
		e.preventDefault();
		action = $('#masiveOptions').val();
		form = $('#cardsInquiryForm');
		btnText = $(this).text().trim();
		$('.help-block').text('');
		$('input, select').removeClass('has-error');
		applyActions(action, form, $(this));
	})
})

function getCardList(request) {
	insertFormInput(true);
	$('#loader-table').removeClass('hide');
	$('.hide-table').addClass('hide');
	$('#tableCardInquiry').dataTable().fnClearTable();
	$('#tableCardInquiry').dataTable().fnDestroy();
	verb = 'POST'; who = 'services', where = 'CardsInquiry'
	callNovoCore(verb, who, where, request, function (response) {
		dataRquest = request;
		insertFormInput(false);

		if (response.data.cardsList.length == 0) {
			$('.download-icons').addClass('hide')
		} else {
			$('.download-icons').removeClass('hide')
		}

		table = $('#tableCardInquiry').DataTable({
			"ordering": false,
			"pagingType": "full_numbers",
			data: response.data.cardsList,
			"columnDefs": [
				{
					"targets": 0,
					"className": "select-checkbox",
					"checkboxes": {
						"selectRow": true
					},
					"visible": Object.keys(response.data.massiveOptions).length > 0
				},
				{
					"targets": 1,
					"visible": false
				},
				{
					"targets": 2,
					"visible": false
				},
				{
					"targets": 3,
					"visible": false
				},
				{
					"targets": 4,
					"visible": false
				},
				{
					"targets": 5,
					"visible": false
				},
				{
					"targets": 6,
					"className": 'card-number'
				},
				{
					"targets": 9,
					"visible": lang.CONF_CARDS_INQUIRY_ISSUE_STATUS == 'ON'
				},
				{
					"targets": 13,
					"className": 'balance',
					"visible": response.data.operList.INQUIRY_BALANCE
				}
			],
			"columns": [
				{
					data: function (data) {
						return ''
					}
				},
				{ data: 'email' },
				{ data: 'celPhone' },
				{ data: 'names' },
				{ data: 'lastName' },
				{ data: 'idNumberSend' },
				{ data: 'cardNumber' },
				{ data: 'orderNumber' },
				{ data: 'bulkNumber' },
				{ data: 'issueStatus' },
				{ data: 'cardStatus' },
				{ data: 'name' },
				{ data: 'idNumber' },
				{
					data: function (data) {
						return '--'
					}
				},
				{
					data: function (data) {
						var options = '<div class="flex justify-center items-center">';
						if (data.options.UPDATE_DATA) {
							options += '<button class="btn mx-1 px-0 update" title="'+lang.SERVICES_INQUIRY_UPDATE_DATA+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.UPDATE_DATA+'">';
							options += 	'<i class="icon icon-user-edit" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.INQUIRY_BALANCE) {
							options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_INQUIRY_BALANCE+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.INQUIRY_BALANCE+'">';
							options += 	'<i class="icon icon-envelope-open" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.LOCK_CARD) {
							options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_LOCK_CARD+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.LOCK_CARD+'">';
							options += 	'<i class="icon icon-lock" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.UNLOCK_CARD) {
							options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_UNLOCK_CARD+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.UNLOCK_CARD+'">';
							options += 	'<i class="icon icon-unlock" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.DELIVER_TO_CARDHOLDER) {
							options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_DELIVER_TO_CARDHOLDER+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.DELIVER_TO_CARDHOLDER+'">';
							options += 	'<i class="icon icon-deliver-card" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.SEND_TO_ENTERPRISE) {
							options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_SEND_TO_ENTERPRISE+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.SEND_TO_ENTERPRISE+'">';
							options += 	'<i class="icon icon-shipping" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.RECEIVE_IN_ENTERPRISE) {
							options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_RECEIVE_IN_ENTERPRISE+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.RECEIVE_IN_ENTERPRISE+'">';
							options += 	'<i class="icon icon-building" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.RECEIVE_IN_BANK) {
							options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_RECEIVE_IN_BANK+'" data-toggle="tooltip" ';
							options += 'action="'+data.options.RECEIVE_IN_BANK+'">';
							options += 	'<i class="icon icon-user-building" aria-hidden="true"></i>';
							options += '</button>';
						}

						if (data.options.NO_OPER) {
							options += '<span class="btn mx-1 px-0" data-toggle="tooltip">';
							options += 	data.options.NO_OPER;
							options += '</span>';
						}

						options += '</div>';

						return options;
					},
				}
			],
			"table-layout": "fixed",
			"select": {
				"style": "multi",
				"info": false
			},
			"language": dataTableLang
		})
		verifymassiveOptions(response.data.massiveOptions)
	})
}

function verifymassiveOptions(massiveOptions) {
	$('#masiveOptions').children().remove();

	if (Object.keys(massiveOptions).length == 0) {
		$('#cardsInquiryForm').addClass('hide')
	} else {
		$('#cardsInquiryForm').removeClass('hide')
		$('#masiveOptions').append('<option value="" selected disabled>Seleccionar</option>')
		$.each(massiveOptions, function(key, value) {
			$('#masiveOptions').append('<option value="'+key+'">'+value+'</option>')
		})
	}

	$('#loader-table').addClass('hide');
	$('.hide-table').removeClass('hide');
}

function applyActions(currentAction, currentForm, currentBtn) {
	cardsData = table.rows('.selected').data();
	formInputTrim(currentForm);
	validateForms(currentForm);

	if (cardsData.length == 0) {
		currentForm.find('.item-select').text(lang.VALIDATE_SELECT);
	}

	if (cardsData.length > 0 && currentForm.valid()) {
		var cardsInfo = [];
		$('#accept').removeClass('send-request');
		$.each(cardsData, function(key, data) {
			var info = {};

			$.each(data, function(pos, value) {
				if (pos == 'options') {
					return
				}
				info[pos] = value
			})

			if (action == 'UPDATE_DATA') {
				info['names'] = $('#firstName').val();
				info['lastName'] = $('#lastName').val();
				info['email'] = $('#email').val();
				info['celPhone'] = $('#movil').val();
			}

			cardsInfo.push(JSON.stringify(info));
		})

		data = {
			cards: cardsInfo,
			action: currentAction,
			pass: cryptoPass(currentForm.find('input.pwd').val().trim())
		}
		currentBtn
			.html(loader)
			.prop('disabled', true)
		insertFormInput(true)
		verb = 'POST'; who = 'Services'; where = 'InquiriesActions'
		callNovoCore(verb, who, where, data, function(response) {
			if (response.success) {
				$('#accept').addClass('reload-req');
			}

			table.rows().deselect();
			$('#tableCardInquiry').find('thead > tr').removeClass('selected');
			evalResult(response, currentAction);
			currentBtn
				.text(btnText)
				.prop('disabled', false);
			insertFormInput(false);
			currentForm.find('input.pwd').val('');
		})
	}
}

function fileDownload(currentAction) {
	verb = 'POST'; who = 'Services'; where = 'CardsInquiry'
	data = dataRquest;
	data.action = currentAction
	$('.cover-spin').show(0)
	callNovoCore(verb, who, where, data, function(response) {
		delete (data.action);
		if (response.code == 0) {
			delete (response.data.btn1)
			downLoadfiles(response.data)
		}
	})
}

function evalResult(response, currentAction) {
	if (currentAction == 'INQUIRY_BALANCE') {
		$.each(response.data.balanceList, function(key, value) {
			$('#tableCardInquiry').find('tbody > tr').each(function(index, element) {
				var cardnumber = $(element).find('td.card-number').text().trim().replace(/[*]/g, '');
				if (value.cardNumber == cardnumber) {
					$(element).find('td.balance').text(value.balance);
					if (value.balance == '--') {
						$(element).find('td.balance').removeClass('text-right')
					} else {
						$(element).find('td.balance').addClass('text-right')
					}
				}
			})
		})
	}

	if (response.data.failList.length > 0) {
		data = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'close'
			}
		}
		inputModal = '<h5 class="regular mr-1">' + response.msg + '</h5>';

		$.each(response.data.failList, function (index, value) {
			inputModal += '<h6 class="light mr-1">' + value + '</h6>';
		})

		notiSystem(response.title, inputModal, lang.GEN_ICON_INFO, data);
	}
}
