'use strict'
var inputModal;
var cardnumber;
$(function () {
	$('.hide-out').removeClass('hide');
	$('#blockResults').addClass('hidden');
	insertFormInput(false);

	$('#card-holder-btn').on('click', function (e) {
		e.preventDefault();
		form = $('#formTwirls');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			$('#blockResults').addClass('hidden');
			$('#spinnerBlock').removeClass('hide');
			insertFormInput(true);
			getSwitchTwirls();
		}
	});

	$('input[type=checkbox]').on('change', function() {
		var password = $('#passwordAuth').val();

		if ($(this).is(':checked') == false ) {
			$(this).val(0);
			$('#passwordAuth').val(password);
		} else {
			$(this).val(1);
		}
	});

	$('#sign-btn').on('click', function(e) {
		btnText = $(this).text().trim();
		form = $('#check-form');

		$('#spinnerBlock').addClass('hide');
		formInputTrim(form);
		validateForms(form)

		if (form.valid()) {
			insertFormInput(true);
			$(this).html(loader);
			if (lang.CONF_REMOTE_AUTH == 'ON') {
				remoteFunction = 'updateTwirlsCard';
				btnRemote = $(this);
				remoteAuthArgs.action = lang.GEN_COMMERCIAL_TWIRLS_TITTLE;
				getauhtKey();
			} else {
				updateTwirlsCard();
			}
		}
	});
});

function getSwitchTwirls(passData) {
	data = getDataForm(form);
	verb = 'POST'; who = 'Services'; where = 'commercialTwirls';

	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code

		if (code == 0) {
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
			$('#blockResults').removeClass('hidden');
			cardnumber = data.cardNumber

			$.each (dataResponse.dataTwirls, function(key, val) {
				$('#' + key).text(val);
			});

			$.each (dataResponse.shops, function(key, val, i) {
				$('#' + key).val(val);
				var markCheck = $('#' + key).val() == 1 ? true : false;
				$('#' + key).prop('checked', markCheck);
			});
		} else {
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
		}
	});
};

function updateTwirlsCard() {
	data = getDataForm(form);
	data.cardNumber = cardnumber;

	if (lang.CONF_REMOTE_AUTH == 'OFF') {
		passData.passwordAuth = cryptoPass(passData.passwordAuth);
	}

	verb = 'POST'; who = 'Services'; where = 'updateCommercialTwirls';

	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code;
		msg = response.msg;
		title = response.title
		buildList(code, dataResponse, msg, title);
		insertFormInput(false);
		$('#sign-btn').html(btnText);
		$('#passwordAuth').val('');
		$('.cover-spin').hide();
	});
};

function buildList(code, dataResponse, msg, title) {
	if (code == 2) {
		data = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'destroy'
			}
		}
		inputModal = '<h5 class="regular mr-1">' + msg + '</h5>';

		$.each(dataResponse, function (key) {
			inputModal += '<h6 class="light mr-1">' + key + '</h6>';
		})

		appMessages(title, inputModal, lang.CONF_ICON_WARNING, data);
	}
};
