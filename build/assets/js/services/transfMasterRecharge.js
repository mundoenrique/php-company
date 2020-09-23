'use strict'
$(function () {
	if (code == 0) {
		var parameters = JSON.parse(atob(params))
		parameters = JSON.parse(CryptoJS.AES.decrypt(parameters.code, parameters.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8));
		$('#amount').mask('#' + lang.GEN_THOUSANDS + '##0' + lang.GEN_DECIMAL + '00', { reverse: true });
		$('#amount').on('keyup', function() {
			$(this).val(function(index, value) {

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
			formInputTrim(form);
			validateForms(form);

			if (form.valid()) {
				$(this).html(loader);
				insertFormInput(true);
			}
		});
	}
});

function validamount() {

}
