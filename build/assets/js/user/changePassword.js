'use strict'
$(function () {
	insertFormInput(false);
	$.balloon.defaults.css = null;
	$('#new-pass')
		.on('focus', function () {
			$(this).showBalloon({
				html: true,
				classname: 'pass-config',
				position: "right",
				contents: $('#psw_info').html()
			});
		})
		.on('keyup focus', function () {
			var pswd = $(this).val();
			passWordStrength(pswd);

		})
		.on('blur', function () {
			$('#new-pass').hideBalloon();
		});

	$('#passwordChangeBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#form-change-pass');
		btnText = $(this).text().trim();
		validateForms(form, { handleMsg: true });
		if (form.valid()) {
			var userType = $('#user-type').val();
			var currentPass = $('#current-pass').val();
			var newPass = $('#new-pass').val();
			var confirmPass = $('#confirm-pass').val();
			$(this).text().trim();

			if (userType == '1') {
				currentPass = currentPass.toUpperCase();
			}

			data = {
				currentPass: cryptoPass(currentPass),
				newPass: cryptoPass(newPass),
				confirmPass: cryptoPass(confirmPass)
			}

			insertFormInput(true, form);
			$(this).html(loader);
			changePassword();
		}
	})
})

function passWordStrength(pswd) {
	var valid;

	if (pswd.length < 8 || pswd.length > 15) {
		$('.pass-config #length').removeClass('valid').addClass('invalid');
		valid = false;
	} else {
		$('.pass-config #length').removeClass('invalid').addClass('valid');
		valid = true;
	}

	if (pswd.match(/[a-z]/)) {
		$('.pass-config #letter').removeClass('invalid').addClass('valid');
		valid = !valid ? valid : true;
	} else {
		$('.pass-config #letter').removeClass('valid').addClass('invalid');
		valid = false;
	}

	if (pswd.match(/[A-Z]/)) {
		$('.pass-config #capital').removeClass('invalid').addClass('valid');
		valid = !valid ? valid : true;
	} else {
		$('.pass-config #capital').removeClass('valid').addClass('invalid');
		valid = false;
	}

	if (pswd.split(/[0-9]/).length - 1 >= 1 && pswd.split(/[0-9]/).length - 1 <= 3) {
		$('.pass-config #number').removeClass('invalid').addClass('valid');
		valid = !valid ? valid : true;
	} else {
		$('.pass-config #number').removeClass('valid').addClass('invalid');
		valid = false;
	}

	if ((pswd.length > 0) && !pswd.match(/(.)\1{2,}/)) {
		$('.pass-config #consecutivo').removeClass('invalid').addClass('valid');
		valid = !valid ? valid : true;
	} else {
		$('.pass-config #consecutivo').removeClass('valid').addClass('invalid');
		valid = false;
	}

	if (pswd.match(/([!@\*\-\?¡¿+\/.,_#])/)) {
		$('.pass-config #especial').removeClass('invalid').addClass('valid');
		valid = !valid ? valid : true;
	} else {
		$('.pass-config #especial').removeClass('valid').addClass('invalid');
		valid = false;
	}

	return valid;
}
