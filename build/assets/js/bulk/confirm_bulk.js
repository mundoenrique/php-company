'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
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
			var pwd = cryptoPass(inputPass.val());
			data = {
				bulkTicked: $('#bulkTicked').val(),
				pass: pwd
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
			appMessages(response.title, response.msg, response.icon, response.data);
		}
	}
});
