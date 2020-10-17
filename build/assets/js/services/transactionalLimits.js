'use strict'
var cardnumber
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false);
	$('#blockResults').addClass('hidden');

	$('.slide-slow').click(function() {
		$(this).next(".section").slideToggle("slow");
		$(".help-block").text("");
	});


	$('#card-holder-btn').on('click', function (e) {
		e.preventDefault();
		$('#passwordAuth').val('');
		$('.section').css("display", "none");
		$('.money').removeClass("has-error");
		$('#blockResults').addClass('hidden');
		form = $('#limitsForm');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			$('#spinnerBlock').removeClass("hide");
			insertFormInput(true);
			getForm();
		}
	});

	$('#sign-btn').on('click', function(e) {
		btnText = $(this).text().trim();
		form = $('#limitsUpdateForm');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			$(this).html(loader);
			insertFormInput(true);

			if (lang.CONF_REMOTE_CONNECT == 'ON') {
				remoteFunction = 'updateLimits';
				btnRemote = $(this);
				remoteAuthArgs.action = lang.GEN_TRANSACTIONAL_LIMITS_TITTLE;
				getauhtKey();
			} else {
				updateLimits();
			}
		}
	});
});

function getForm(){
	verb = "POST"; who = 'Services'; where = 'transactionalLimits';
	data = getDataForm(form);

	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		cardnumber = data.cardNumber

		if (code == 0) {
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
			$('#blockResults').removeClass('hidden');

			$.each (dataResponse.dataLimits, function(key, val) {
				$('#' + key).text(val);
			});

			$.each (dataResponse.limits, function(key, val) {
				$('#' + key).val(val);
			});

		} else {
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
		}
	});
};

function updateLimits() {
	data = getDataForm(form);
	data.cardNumber = cardnumber;
	verb = "POST"; who = 'Services'; where = 'updateTransactionalLimits';

	if (lang.CONF_REMOTE_CONNECT == 'OFF') {
		data.passwordAuth = cryptoPass(form.find('#passwordAuth').val().trim());
	}

	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code;
		insertFormInput(false);
		$('input[type=password]').val('');
		$('#sign-btn').html(btnText);

		if (response.success) {
			$('#accept').on('click', function(){
				$('#card-holder-btn').trigger('click');
			});
		}

		$('.cover-spin').hide();
	})
};
