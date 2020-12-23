'use strict'
var rechargeParam;
$(function () {
	if (params && code == 0) {
		rechargeParam = params;
		console.log(rechargeParam)
		$('#transferAmount').mask('#' + lang.GEN_THOUSANDS + '##0' + lang.GEN_DECIMAL + '00', { reverse: true });
		$('#transferAmount').on('keyup', function() {
			$(this).val(function(_index, value) {

				if (value.indexOf('0') != -1 && value.indexOf('0') == 0) {
					value = value.replace(0, '');
				}

				if (value.length == 1 && /^[0-9,.]+$/.test(value)) {
					value = '00' + lang.GEN_DECIMAL + value
				}

				return value
			})
		});

		$('#masterAccountRechargeBtn').on('click', function(e) {
			e.preventDefault();
			form = $('#masterAccountRechargeForm');
			btnText = $(this).text();
			validateForms(form);

			if (form.valid()) {
				data = getDataForm(form);
				data.transferAmount = normalizeAmount(data.transferAmount);
				$(this).html(loader);
				insertFormInput(true);

				if (lang.CONF_INPUT_PASS == 'OFF') {
					getTokenRecharge();
				} else {
					rechargeAccount();
				}
			}
		});
	}
});

function getTokenRecharge() {

}

function rechargeAccount() {

	if (lang.CONF_INPUT_PASS == 'OFF') {
		data.passwordTranfer = $('#tokenCode').val();
	} else {
		data.passwordTranfer = cryptoPass(data.passwordTranfer);
	}

	verb = 'POST'; who = 'Services'; where = 'masterAccountTransfer';

	callNovoCore(verb, who, where, data, function (response) {
		$('#masterAccountRechargeBtn').html(btnText);
		insertFormInput(false);
	});
}
