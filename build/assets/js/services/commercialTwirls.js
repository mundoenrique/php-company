'use strict'
var cardnumber;
$(function () {
	$('.hide-out').removeClass('hide');
	$('#blockResults').addClass('hidden');
	insertFormInput(false);

	$('#card-holder-btn').on('click', function (e) {
		e.preventDefault();
		form = $('#formTwirls');
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
		validateForms(form)

		if (form.valid()) {
			insertFormInput(true);
			$(this).html(loader);

			if (lang.SETT_REMOTE_AUTH == 'ON') {
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
	who = 'Services';
	where = 'commercialTwirls';
	data = getDataForm(form);

	callNovoCore(who, where, data, function(response) {
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

	if (lang.SETT_REMOTE_AUTH == 'OFF') {
		data.passwordAuth = cryptoPass(data.passwordAuth);
	}

	who = 'Services';
	where = 'updateCommercialTwirls';

	callNovoCore(who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code;
		msg = response.msg;
		title = response.title
		buildList(code, dataResponse, msg, title);
		insertFormInput(false);
		$('#sign-btn').html(btnText);
		$('#passwordAuth').val('');
		if (response.success) {
			$('#accept').on('click', function(){
				$('#card-holder-btn').trigger('click');
			});
		}
		$('.cover-spin').hide();
	});
};

function buildList(code, dataResponse, msg, title) {
	if (code == 2) {
		modalBtn = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'destroy'
			}
		}
		inputModal = '<h5 class="regular mr-1">' + msg + '</h5>';

		$.each(dataResponse, function (key) {
			inputModal += '<h6 class="light mr-1">' + key + '</h6>';
		})

		appMessages(title, inputModal, lang.SETT_ICON_WARNING, modalBtn);
	}
};
