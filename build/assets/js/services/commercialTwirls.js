'use strict'
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
		$('#spinnerBlock').addClass('hide');
		var form = $('#check-form');
		var passData = getDataForm(form);
		delete passData.passwordAuth;
		passData.passwordAuth = cryptoPass(form.find('input.pwd').val().trim());
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
		var info = dataResponse;
		if (code == 0) {
			var obj = (info).cards[0];
			var properties = obj.mccItems;
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
			$('#blockResults').removeClass('hidden');
			$('#cardNumberP').text(obj.numberCard);
			$('#dateTimeAct').text(obj.datetimeLastUpdate);
			$('#personalId').text(obj.personId);
			$('#nameUser').text(obj.personName);

			$.each (lang.SERVICES_NAMES_PROPERTIES, function(key){
				properties[lang.SERVICES_NAMES_PROPERTIES[key]]= properties[key];
				delete properties[key];
			})

			$.each (properties, function(key, val, i) {
				$('#' + key).val(val);
				if ($('#' + key).val() == 1){
					$('#' + key).prop('checked', true);
				}else if ($('#' + key).val() == 0) {
					$('#' + key).prop('checked', false);
				}
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
			if (code == 0) {
				$('#passwordAuth').val('');
				insertFormInput(false);
				$('#sign-btn').html(btnText);
		} else {
			insertFormInput(false);
			$('#sign-btn').html(btnText);
			$('#passwordAuth').val('');
		}
	});
};
