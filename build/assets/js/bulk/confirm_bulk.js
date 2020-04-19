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
			inputPass = cryptoPass(inputPass.val());
			data = {
				bulkTicked: $('#bulkTicked').val(),
				pass: inputPass
			}
			verb = 'POST'; who = 'Bulk'; where = 'ConfirmBulk';
			callNovoCore(verb, who, where, data, function(response) {
				btnConfirmBulk.html(btnText);
				insertFormInput(false);
				inputPass.val('');
				respConfirmBulk[response.code](response);
			});
		}
	});

	const respConfirmBulk = {
		0: function(response) {
			notiSystem(response.title, response.msg, response.icon, response.data);
		}
	}
});
