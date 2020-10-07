'use strict'
var inputModal;
$(function () {
	$('.hide-out').removeClass('hide');
	$('#blockResults').addClass('hidden');
	insertFormInput(false);

	$('#card-holder-btn').on('click', function (e) {
		e.preventDefault();
		var form = $('#formTwirls');
		validateForms(form);

		if (form.valid()) {
			$('#blockResults').addClass('hidden');
			$('#spinnerBlock').removeClass('hide');
			insertFormInput(true);
			getSwitchTwirls(getDataForm(form));
		}
	});

	$('input[type=checkbox]').on('change', function(){
		var password = $('#passwordAuth').val();
		if( $(this).is(':checked') == false ){
			$( this).val(0);
			$( '#passwordAuth').val(password);
		}else{
			$( this).val(1);
		}
	});

	$('#sign-btn').on('click', function(e){
		var changeBtn = $(this);
		var btnText = changeBtn.text().trim();
		var form = $('#check-form');
		var passData = getDataForm(form);

		$('#spinnerBlock').addClass('hide');
		delete passData.passwordAuth;

		if (lang.CONF_SHOW_INPUT_PASS == 'ON') {
			passData.passwordAuth = cryptoPass(form.find('input.pwd').val().trim());
		}

		passData.cardNumber = $('#cardNumber').val();
		validateForms(form)

		if (form.valid()) {
			insertFormInput(true, form);
			changeBtn.html(loader);
			updateTwirlsCard(passData, btnText);
		}
	});
});

function getSwitchTwirls(passData) {
	verb = 'POST'; who = 'Services'; where = 'commercialTwirls'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		if (code == 0) {
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
			$('#blockResults').removeClass('hidden');

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

function updateTwirlsCard(passData, btnText) {
	verb = 'POST'; who = 'Services'; where = 'updateCommercialTwirls'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code;
		msg = response.msg;
		title = response.title
		buildList(code, dataResponse, msg, title);
		insertFormInput(false);
		$('#sign-btn').html(btnText);
		$('#passwordAuth').val('');
	});
};

function buildList(code, dataResponse, msg, title) {
	if (code == 2) {
		data = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'close'
			}
		}
		inputModal = '<h5 class="regular mr-1">' + msg + '</h5>'
		$.each(dataResponse, function (key) {
			inputModal += '<h6 class="light mr-1">' + key + '</h6>';
		})

		appMessages(title, inputModal, lang.CONF_ICON_WARNING, data);
	}
};
