'use strict'
$(function () {
	var btnConfirmBulk = $('#confirm-bulk');
	form = $('#confirm-bulk-btn');

	btnConfirmBulk.on('click', function(e) {
		e.preventDefault()
		btnText = $(this).text();
		formInputTrim(form);
		validateForms(form);

		if(form.valid()) {
			insertFormInput(true)
			$(this).html(loader);
			ceo_cook = getCookieValue();
			cypherPass = CryptoJS.AES.encrypt(inputPass.val(), ceo_cook, { format: CryptoJSAesJson }).toString();
			data = {
				bulkTicked: $('#bulkTicked').val(),
				pass: btoa(JSON.stringify({
					passWord: cypherPass,
					plot: btoa(ceo_cook)
				}))
			}
			verb = 'POST'; who = 'Bulk'; where = 'ConfirmBulk';
			callNovoCore(verb, who, where, data, function(response) {
				btnConfirmBulk.html(btnText);
				insertFormInput(false);
			});
		}
	});

});
