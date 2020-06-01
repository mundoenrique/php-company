'use strict'
var table;
var access;
var params;
var balance;
var cardsData;
$(function () {
	var action;
	var inputModal;
	var getAmount;
	var modalReq;
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var masterAccountBtn = $('#masterAccountBtn');
	insertFormInput(false);

	masterAccountBtn.on('click', function (e) {
		e.preventDefault();
		form = $('#masterAccountForm');
		btnText = $(this).text().trim()
		validateForms(form);

		if (form.valid()) {
			var dataForm = getDataForm(form)
			insertFormInput(true)
			$('.hide-table').addClass('hide')
			$('#pre-loader-table').removeClass('hide')
			dataTableBuild(dataForm)
		}
	})

	$('#tableServicesMaster').on('click', '.toggle-all', function () {
		$(this).closest("tr").toggleClass("selected");
		if ($(this).closest("tr").hasClass("selected")) {
			table.rows().select();
		} else {
			table.rows().deselect();
		}
	})

	$('#tableServicesMaster').on('click', 'button', function(e) {
		var event = $(e.currentTarget);
		action = event.attr('title');
		getAmount = event.attr('amount');
		$('#tableServicesMaster').find('tr').removeClass('selected')
		$(this).closest('tr').addClass('select');
		$('#accept').addClass('send-request');

		if (amountValidate(getAmount, '.select')) {
			data = {
				btn1: {
					text: lang.GEN_BTN_SEND,
					action: 'none'
				},
				btn2: {
					text: lang.GEN_BTN_CANCEL,
					action: 'close'
				}
			}

			inputModal = 	'<form id="password-modal">';
			inputModal+= 		'<div class="form-group col-auto">';
			inputModal+= 			'<div class="input-group">';
			inputModal+= 				'<input class="form-control pwd-input" type="password" name="password" autocomplete="off"';
			inputModal+=			 		 'placeholder="'+lang.GEN_PLACE_PASSWORD+'">';
			inputModal+= 				'<div class="input-group-append">';
			inputModal+= 					'<span class="input-group-text pwd-action" title="'+lang.GEN_SHOW_PASS+'"><i class="icon-view mr-0"></i></span>';
			inputModal+= 				'</div>';
			inputModal+= 			'</div>';
			inputModal+= 			'<div class="help-block"></div>';
			inputModal+= 		'</div>';

			if (action == lang.GEN_CARD_ASSIGNMENT) {
				inputModal+= 		'<div class="form-group col-auto">';
				inputModal+= 			'<div class="input-group">';
				inputModal+= 				'<input class="form-control" type="text" name="cardNumber" autocomplete="off"';
				inputModal+=			 		 'placeholder="'+lang.GEN_TABLE_CARD_NUMBER+'" req="yes">';
				inputModal+= 			'</div>';
				inputModal+= 			'<div class="help-block"></div>';
				inputModal+= 		'</div>';
			}

			inputModal+=	'</form>';

			lang.CONF_MODAL_WIDTH = 200;
			notiSystem(action, inputModal, lang.GEN_ICON_INFO, data);
			lang.CONF_MODAL_WIDTH = 370;
		}
	})

	$('#system-info').on('click', '.send-request', function() {
		form = $('#password-modal')
		modalReq = true
		btnText = $(this).text().trim();
		sendRequest(action, modalReq, $(this))
	})

	$('#consulta, #abono, #cargo').on('click', function(e) {
		e.preventDefault()
		$('#tableServicesMaster').find('tr').removeClass('select');
		action = $(this).attr('id');
		getAmount = $(this).attr('amount');

		if (amountValidate(getAmount, '.selected')) {
			form = $('#password-table');
			modalReq = false;
			btnText = $(this).text().trim();
			sendRequest(action, modalReq, $(this))
		}
	});

	$('#system-info').on('click', '#cancel', function() {
		$('#tableServicesMaster').find('tr').removeClass('select');
		$('#tableServicesMaster').find('tr').removeClass('selected');
	})

	$("#tableServicesMaster").on({
		"focus": function(event) {
			$(event.target).select();
		},
		"keyup": function(event) {
			$(event.target).val(function(index, value) {
				return value.replace(/\D/g, "")
					.replace(/([0-9])([0-9]{2})$/, '$1'+lang.GEN_DECIMAL+'$2')
					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, lang.GEN_THOUSANDS);
			}, 'input');
		}
	});
})

function dataTableBuild(dataForm) {
	$('#tableServicesMaster').dataTable().fnClearTable();
	$('#tableServicesMaster').dataTable().fnDestroy();
	verb = 'POST'; who = "Services"; where = 'TransfMasterAccount';
	table = $('#tableServicesMaster').DataTable({
		drawCallback: function(d) {
			$('#balance-aviable').text(lang.GEN_CURRENCY+' '+balance)
			$('#cost-trans').text(lang.GEN_CURRENCY+' '+params.costoComisionTrans)
			$('#cost-inquiry').text(lang.GEN_CURRENCY+' '+params.costoComisionCons)
			insertFormInput(false)
			$('#pre-loader-table').addClass('hide')
			$('.hide-table').removeClass('hide')
		},
		"ordering": false,
		"searching": false,
		"lengthChange": false,
		"pagelength": 10,
		"pagingType": "full_numbers",
		"table-layout": "fixed",
		"select": {
			"style": "multi",
			"info": false
		},
		"language": dataTableLang,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: baseURL + 'async-call',
			method: 'POST',
			dataType: 'json',
			cache: false,
			data: function (req) {
				data = req
				data.idNumber = dataForm.idNumber;
				data.cardNumber = dataForm.cardNumber;
				data.screenSize = screen.width;
				var dataRequest = JSON.stringify({
					who: who,
					where: where,
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
				access = responseTable.access;
				balance = responseTable.balance;
				params = responseTable.params;
				return JSON.stringify(responseTable);
			}
		},
		"columnDefs": [
			{
				"targets": 0,
				"className": "select-checkbox",
				"checkboxes": {
					"selectRow": true
				},
			},
			{
				"targets": 1,
				"className": "card-number",
				"width": "200px"
			},
			{
				"targets": 2,
				"width": "200px",
			},
			{
				"targets": 3,
				"className": "user-id",
				"width": "auto"
			},
			{
				"targets": 4,
				"className": "balance",
				"width": "120px"
			},
			{
				"targets": 5,
				"width": "90px"
			},
			{
				"targets": 6,
				"width": "200px"
			}
		],
		"columns": [
			{
				data: function (data) {
					return ''
				}
			},
			{ data: 'cardNumber' },
			{ data: 'name' },
			{ data: 'idNumber' },
			{
				 data: function (data) {
					 return '--'
				 }
			},
			{
				data: function (data) {
						var ammount;
						ammount = '<form>';
						ammount+=		'<input class="form-control h6 text-right" type="text" placeholder="'+data.amount+'"></input>';
						ammount+= '</form>';
					return ammount
				}
		 },
			{
				data: function (data) {
					var options = '';

					if(access.TRASAL && data.status == '') {
						options+=		'<button class="btn mx-1 px-0" title="'+lang.GEN_CHECK_BALANCE+'" data-toggle="tooltip" amount="0">';
						options+=			'<i class="icon novoglyphs icon-balance" aria-hidden="true"></i>';
						options+= 	'</button>';
					}

					if (access.TRACAR && data.status == '') {
						options+=		'<button class="btn mx-1 px-0" title="'+lang.GEN_CREDIT_TO_CARD+'" data-toggle="tooltip" amount="1">';
						options+=			'<i class="icon novoglyphs icon-credit-card" aria-hidden="true"></i>';
						options+=		'</button>';

					}

					if (access.TRAABO && data.status == '') {
						options+=		'<button class="btn mx-1 px-0" title="'+lang.GEN_DEBIT_TO_CARD+'" data-toggle="tooltip" amount="1">';
						options+=			'<i class="icon novoglyphs icon-card-fee" aria-hidden="true"></i>';
						options+=		'</button>';
					}

					if (access.TRABLQ && data.status == '') {
						options+=		'<button class="btn mx-1 px-0" title="'+lang.GEN_TEMPORARY_LOCK+'" data-toggle="tooltip" amount="0">';
						options+=			'<i class="icon novoglyphs icon-lock" aria-hidden="true"></i>';
						options+=		'</button>';
					}

					if (access.TRADBL && data.status == 'pb') {
						options+=		'<button class="btn mx-1 px-0" title="'+lang.GEN_UNLOCK_CARD+'" data-toggle="tooltip" amount="0">';
						options+=			'<i class="icon novoglyphs icon-unlock" aria-hidden="true"></i>';
						options+=		'</button>';
					}

					if (access.TRAASG) {
						options+=		'<button class="btn mx-1 px-0" title="'+lang.GEN_CARD_ASSIGNMENT+'" data-toggle="tooltip" amount="0">';
						options+=			'<i class="icon novoglyphs icon-arrow-left" aria-hidden="true"></i>';
						options+=		'</button>';
					}

					return options;
				}
		 }
		]
	});
}

function sendRequest(action, modalReq, btn) {
	formInputTrim(form)
	validateForms(form)
	if(cardsData.length == 0) {
		form.validate().resetForm();
		form.find('.bulk-select').text(lang.VALIDATE_SELECT);
	}

	if (cardsData.length > 0 && form.valid()) {
		var cardsInfo = [];

		for(var i = 0; i < cardsData.length; i++) {
			var info = {};
			info['Cardnumber'] = cardsData[i].cardNumber;
			info['idNumber'] = cardsData[i].idNumber;
			info['amount'] = cardsData[i].amount;

			if (action == lang.GEN_CARD_ASSIGNMENT) {
				info['cardNumberAs'] = form.find('input[name=cardNumber]').val()
			}

			cardsInfo.push(JSON.stringify(info));
		}

		btn
		.html(loader)
		.prop('disabled', true);
		insertFormInput(true)
		data = {
			modalReq: modalReq,
			cards: cardsInfo,
			action: action,
			pass: cryptoPass(form.find('input[type=password]').val())
		}

		verb = 'POST'; who = 'Services'; where = 'ActionMasterAccount';

		callNovoCore(verb, who, where, data, function(response) {
			$('#tableServicesMaster').find('tr').removeClass('select');
			$('#tableServicesMaster').find('tr').removeClass('selected');
			$('#accept').removeClass('send-request');
			btn
			.html(btnText)
			.prop('disabled', false);
			insertFormInput(false);

			if (action == lang.GEN_CARD_ASSIGNMENT || 'consulta') {
				$.each(response.data.listResponse, function(key, value) {
					$('#tableServicesMaster').find('tbody > tr').each(function(index, element) {
						if(value.cardNumber == $(element).find('td.card-number').text()) {
							$(element).find('td.balance').text(value.balance)
							if (value.balance != '--') {
								$(element).find('td.balance').addClass('text-right')
							} else {
								$(element).find('td.balance').removeClass('text-right')
							}
						}
					})
				})
			}
		})
	}
}

function amountValidate(getAmount, classSelect) {
	var valid = true
	cardsData = table.rows(classSelect).data();

	if (getAmount == '1') {
		for(var i = 0; i < cardsData.length; i++) {
			console.log(cardsData[i].amount)
			var ammountText = parseFloat(cardsData[i].amount)
		}
	}

	if (!valid) {
		console.log('alto')
	}

	return valid;
}
