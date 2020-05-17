'use strict'
$(function() {
	var changePassBtn = $('#change-pass-btn');
	var newPass = $('#newPass');

	newPass.on('keyup focus', function() {
		var pswd = $(this).val();
		passStrength(pswd);
	});

	changePassBtn.on('click', function(e) {
		e.preventDefault();
		var changeBtn = $(this);
		form = $('#change-pass-form');
		btnText = changeBtn.text().trim();
		validateForms(form)

		if(form.valid()) {
			data = getDataForm(form)

			if(data.userType == '1') {
				data.currentPass = data.currentPass.toUpperCase();
			}

			data.currentPass = cryptoPass(data.currentPass);
			data.newPass = cryptoPass(data.newPass);
			data.confirmPass = cryptoPass(data.confirmPass);
			insertFormInput(true, form);
			changeBtn.html(loader);
			changePassword(data, btnText);
		}
	});
});
