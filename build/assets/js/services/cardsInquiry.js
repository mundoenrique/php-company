'use strict'
var table;
var dataRquest;
var cardsData;
var userInfo;
$(function () {
	var action;
	var title
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false);
	remoteFunction = 'applyActions';

	$('#tableCardInquiry').on('click', '.toggle-all', function () {
		$(this).closest("tr").toggleClass("selected");

		if ($(this).closest("tr").hasClass("selected")) {
			table.rows().select();
		} else {
			table.rows().deselect();
		}
	});

	$('#searchCardsBtn').on('click', function (e) {
		e.preventDefault();
		$('.help-block').text('');
		$('input, select').removeClass('has-error');
		$('#tableCardInquiry').find('thead > tr').removeClass('selected');
		$('#documentType').find('option:first').val('');
		form = $('#searchCardsForm');
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			getCardList(data);
		}
	});

	$('.download-icons').on('click', 'button', function(e) {
		var event = $(e.currentTarget);
		action = event.attr('title').trim();
		fileDownload(action);
	});

	$('#tableCardInquiry').on('click', 'button', function(e) {
		var event = $(e.currentTarget);
		title = event.attr('title').trim();
		action = event.attr('action');
		$('.help-block').text('');
		$('input, select').removeClass('has-error');
		table.rows().deselect();
		$('#tableServicesMaster').find('thead > tr').removeClass("selected");
		$('#tableServicesMaster').find('tbody > tr').removeClass("selected");
		$(this).closest('tr').addClass('selected');

		InqBuildFormActions(action, title, $(this));
	});

	$('#system-info').on('click', '.send-request', function () {
		form = $('#modalCardsInquiryForm')
		btnText = $(this).text().trim();
		applyActions(action, form, $(this));
	});

	$('#system-info').on('click', '.get-auth-key', function () {
		form = $('#modalCardsInquiryForm');
		btnText = $(this).text().trim();

		if (InqValidateActions(action, form)) {
			btnRemote = $(this);
			remoteAuthArgs.action = action;
			remoteAuthArgs.form = form;
			remoteAuthArgs.title = title;
			getauhtKey();
		}
	});

	$('#system-info').on('click', '.reload-req', function () {
		form = $('#searchCardsForm')
		$('#accept').removeClass('reload-req');
		getCardList(dataRquest);
	});

	$('#cardsInquiryBtn').on('click', function (e) {
		e.preventDefault();
		action = $('#masiveOptions').val();
		form = $('#cardsInquiryForm');
		btnText = $(this).text().trim();
		$('.help-block').text('');
		$('input, select').removeClass('has-error');
		applyActions(action, form, $(this));
	});
});

function getCardList(request) {
	insertFormInput(true);
	$('#loader-table').removeClass('hide');
	$('.hide-table').addClass('hide');
	$('#tableCardInquiry').dataTable().fnClearTable();
	$('#tableCardInquiry').dataTable().fnDestroy();
	who = 'services', where = 'CardsInquiry';

	callNovoCore(who, where, request, function (response) {
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
					"visible": lang.SETT_CARDS_INQUIRY_ISSUE_STATUS == 'ON'
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

						if ( !data.options.NO_OPER ) {
							$.each(data.options, function(key,val){
								var title = "SERVICES_INQUIRY_".concat(key);
								if ( data.options[key] ) {
									options += '<button class="btn mx-1 px-0" title="'+lang[title] + '" data-toggle="tooltip" ';
									options += 'action="' + key + '">';
									options += 	'<i class="icon icon-' + data.options[key] + '" aria-hidden="true"></i>';
									options += '</button>';
								}
							})
						} else {
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

function InqBuildFormActions(currentAction, currentTitle, currentBtn) {

	modalBtn = {
		btn1: {
			text: lang.GEN_BTN_ACCEPT,
			action: 'none'
		},
		btn2: {
			text: lang.GEN_BTN_CANCEL,
			action: 'destroy'
		}
	}
	inputModal = '<form id="modalCardsInquiryForm" name="modalCardsInquiryForm" class="row col-auto p-0 w-335-ie" onsubmit="return false;">';

	if (currentAction == 'UPDATE_DATA') {
		modalBtn.maxHeight = 520;
		$('#tableCardInquiry').find('tbody > tr').removeClass('update');
		currentBtn.closest('tr').addClass('update');
		cardsData = table.rows('.update').data();

		inputModal += 	'<div class="form-group col-12">';
		inputModal += 		'<label>Nombre(s)</label>';
		inputModal += 		'<div class="input-group">';
		inputModal += 			'<input class="form-control" type="text" id="firstName" name="firstName" autocomplete="off" ';
		inputModal += 				'value="' + cardsData[0].names + '">';
		inputModal += 			'</div>';
		inputModal += 		'<div class="help-block"></div>';
		inputModal += 	'</div>';
		inputModal += 	'<div class="form-group col-12">';
		inputModal += 		'<label>Apellido(s)</label>';
		inputModal += 		'<div class="input-group">';
		inputModal += 			'<input class="form-control" type="text" id="lastName" name="lastName" autocomplete="off" ';
		inputModal += 				'value="' + cardsData[0].lastName + '">';
		inputModal += 		'</div>';
		inputModal += 		'<div class="help-block"></div>';
		inputModal += 	'</div>';
		inputModal += 	'<div class="form-group col-12">';
		inputModal += 		'<label>Correo</label>';
		inputModal += 		'<div class="input-group">';
		inputModal += 			'<input class="form-control" type="text" id="email" name="email" autocomplete="off" value="' + cardsData[0].email + '">';
		inputModal += 		'</div>';
		inputModal += 		'<div class="help-block"></div>';
		inputModal += 	'</div>';
		inputModal += 	'<div class="form-group col-12">';
		inputModal += 		'<label>Número movil</label>';
		inputModal += 		'<div class="input-group">';
		inputModal += 			'<input class="form-control" type="text" id="movil" name="movil" autocomplete="off" value="' + cardsData[0].celPhone + '">';
		inputModal += 		'</div>';
		inputModal += 		'<div class="help-block"></div>';
		inputModal += 	'</div>';
	}

	if (currentAction == 'CARD_CANCELLATION') {
		inputModal += '<div class="form-group col-12 w-335-ie">';
		inputModal += '<p class="mb-0">' + lang.SERVICES_RESPONSE_CARD_CANCELED + '</p>';
		inputModal += '</div>';
	}

	if (lang.SETT_REMOTE_AUTH == 'OFF') {
		$('#accept').addClass('send-request');

		inputModal += 	'<div class="form-group col-12">';
		inputModal += 		'<div class="input-group">';
		inputModal += 			'<input class="form-control pwd-input pwd" id="password" name="password" type="password" ';
		inputModal += 				'autocomplete="off" placeholder="' + lang.GEN_PLACE_PASSWORD + '">';
		inputModal += 			'<div class="input-group-append">';
		inputModal += 				'<span class="input-group-text pwd-action" title="' + lang.GEN_SHOW_PASS + '"><i class="icon-view mr-0"></i></span>';
		inputModal += 			'</div>';
		inputModal += 		'</div>';
		inputModal += 		'<div class="help-block"></div>';
		inputModal += 	'</div>';
	} else {
		$('#accept').addClass('get-auth-key');
		currentBtn = btnRemote;
		form = $('#nonForm');

		if ($.inArray(currentAction, lang.SETT_AUTH_VALIDATE) == -1) {
			$('.cover-spin').show(0);
		}
	}

	inputModal += '</form>';

	if (lang.SETT_REMOTE_AUTH == 'OFF' || (lang.SETT_REMOTE_AUTH == 'ON' && $.inArray(currentAction, lang.SETT_AUTH_VALIDATE) != -1)) {
		appMessages(currentTitle, inputModal, lang.SETT_ICON_INFO, modalBtn);
	} else if ($.inArray(currentAction, lang.SETT_AUTH_LIST) != -1) {
		remoteAuthArgs.title = currentTitle;
		remoteAuthArgs.action = currentAction;
		remoteAuthArgs.form = form;
		getauhtKey();
	} else {
		applyActions(currentAction, form, currentBtn);
	}
}

function applyActions(currentAction, currentForm, currentBtn) {
	if (InqValidateActions(currentAction, currentForm)) {
		var cardsInfo = [];
		$('#accept').removeClass('send-request');
		$('#accept').removeClass('get-auth-key');
		$.each(cardsData, function(key, data) {
			var info = {};

			$.each(data, function(pos, value) {
				if (pos == 'options') {
					return
				}
				info[pos] = value
			})

			if (currentAction == 'UPDATE_DATA') {
				info['names'] = userInfo.firstName;
				info['lastName'] = userInfo.lastName;
				info['email'] = userInfo.email
				info['celPhone'] = userInfo.movil;
			}

			cardsInfo.push(JSON.stringify(info));
		})

		data = {
			cards: cardsInfo,
			action: currentAction
		}

		if (lang.SETT_REMOTE_AUTH == 'OFF') {
			data.password = userInfo.password ? cryptoPass(userInfo.password) : cryptoPass(userInfo.passAction);
		}

		currentBtn
			.html(loader)
			.prop('disabled', true)
		insertFormInput(true)
		who = 'Services';
		where = 'InquiriesActions'

		callNovoCore(who, where, data, function(response) {
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
			$('.cover-spin').hide();
		});
	}
}

function InqValidateActions(currentAction, currentForm) {
	cardsData = table.rows('.selected').data();
	validateForms(currentForm);

	if (cardsData.length == 0) {
		currentForm.validate().resetForm();
		modalBtn = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'destroy'
			}
		}

		appMessages(currentAction, lang.VALIDATE_SELECT, lang.SETT_ICON_WARNING, modalBtn);
	}

	if (currentForm.valid()) {
		userInfo = getDataForm(currentForm);
	}

	return cardsData.length > 0 && currentForm.valid();
}

function fileDownload(currentAction) {
	who = 'Services';
	where = 'CardsInquiry'
	data = dataRquest;
	data.action = currentAction
	$('.cover-spin').show(0);

	callNovoCore(who, where, data, function(response) {
		delete (data.action);

		if (response.code == 0) {
			delete (response.data.btn1);
			downLoadfiles(response.data);
		}

		$('.cover-spin').hide();
	});
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
			});
		});
	}

	if (response.data.failList.length > 0) {
		modalBtn = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'destroy'
			}
		}
		inputModal = '<h5 class="regular mr-1">' + response.msg + '</h5>';

		$.each(response.data.failList, function (index, value) {
			inputModal += '<h6 class="light mr-1">' + value + '</h6>';
		})

		appMessages(response.title, inputModal, lang.SETT_ICON_INFO, modalBtn);
	}
}
