'use strict'
$(function () {
	if (params && code == 0) {
		/* var parameters = JSON.parse(atob(params))
		parameters = JSON.parse(CryptoJS.AES.decrypt(parameters.code, parameters.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)); */
		$('#transferAmount').mask('#' + lang.GEN_THOUSANDS + '##0' + lang.GEN_DECIMAL + '00', { reverse: true });
		$('#transferAmount').on('keyup', function() {
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
			validateForms(form);

			if (form.valid()) {
				data = getDataForm(form);
				$(this).html(loader);
				insertFormInput(true);
			}
		});
	}
});

function validamount() {

}
