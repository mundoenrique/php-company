'use strict'
$(function() {
	$('#newPass').on('keyup focus', function() {
		passStrength($(this).val());
	})

	$('#passwordChangeBtn').on('click', function(e) {
		e.preventDefault();
		form = $('#passwordChangeForm');
		btnText = $(this).text().trim();
		validateForms(form)

		if(form.valid()) {
			data = getDataForm(form)

			if(data.userType == '1') {
				data.currentPass = data.currentPass.toUpperCase();
			}

			data.currentPass = cryptoPass(data.currentPass);
			data.newPass = cryptoPass(data.newPass);
			data.confirmPass = cryptoPass(data.confirmPass);
			insertFormInput(true);
			$(this).html(loader);
			changePassword();
		}
	})
})
