'use strict'
var rechargeParam;
var checkType;
$(function () {
	rechargeParam = params;
	if (params && code == 0) {
		$("#pay").prop("checked", true);
		checkType = $("input:radio[name=transferType]:checked").val();

		$("input[name=transferType]").click(function() {
			 checkType = $("input:radio[name=transferType]:checked").val();
		})

		$('#transferAmount').mask('#' + lang.CONF_THOUSANDS + '##0' + lang.CONF_DECIMAL + '00', { reverse: true });
		$('#transferAmount').on('keyup', function() {
			$(this).val(function(_index, value) {

				if (value.indexOf('0') != -1 && value.indexOf('0') == 0) {
					value = value.replace(0, '');
				}

				if (value.length == 1 && /^[0-9,.]+$/.test(value)) {
					value = '00' + lang.CONF_DECIMAL + value
				}

				return value
			})
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

				rechargeAccount();
			}
		});
	}
	$('#masterAccountRechargeBtn').on('click', function(e) {
		e.preventDefault();
		form = $('#masterAccountRechargeForm');
		btnText = $(this).text();
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			data.transferAmount = normalizeAmount(data.transferAmount);
			data.transferType = checkType;
			$(this).html(loader);
			insertFormInput(true);
			if (lang.CONF_INPUT_PASS == 'OFF') {
				getTokenRecharge();
			} else {
				rechargeAccount();
			}
		}
	});

	$('#system-info').on('click', '.get-otp', function() {
		$(this)
			.html(loader)
			.prop('disabled', true)
			.removeClass('get-otp');
		getTokenRecharge();
	});
});

function getTokenRecharge() {
	who = 'Services';
	where = 'RechargeAuthorization';

	callNovoCore(who, where, data, function (response) {
		$('#masterAccountRechargeBtn').html(btnText);
		response.modalBtn.posAt = 'center top';
		response.modalBtn.posMy = 'center top+260';
		switch (response.code) {
			case 0:
				generateModalOTP(response)
			break;
		}
		insertFormInput(false);
	});
}

function rechargeAccount() {
	who = 'Services';
	where = 'masterAccountTransfer';

	if (lang.CONF_INPUT_PASS == 'OFF') {
		data.passwordTranfer = $('#otpCode').val();
	} else {
		data.passwordTranfer = cryptoPass(data.passwordTranfer);
	}

	callNovoCore(who, where, data, function (response) {
		if (lang.CONF_INPUT_PASS == 'OFF') {
			switch (response.code) {
				case 1:
					generateModalOTP(response)
					break;
				case 2:
					$('#accept').addClass('get-otp');
					$('#accept').addClass('btn-modal-large');
					appMessages(response.title, response.msg, response.icon, response.modalBtn);
					break;
			}
			insertFormInput(false);
		} else {
			$('#masterAccountRechargeBtn').html(btnText);
			insertFormInput(false);
		}
	});
}
