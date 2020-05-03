'use strict'
$(function() {
	var changePassBtn = $('#btn-change-pass');
	var new_Pass = $('#new-pass');

	$.balloon.defaults.css = null;
	new_Pass
	.on('focus', function() {
		$(this).showBalloon({
			html: true,
			classname: 'pass-config',
			position: "right",
			contents: $('#psw_info').html()
		});
	})
	.on('keyup focus', function() {
		var pswd = $(this).val();

		passWordStrength(pswd);

	})
	.on('blur', function() {
		new_Pass.hideBalloon();
	});

	changePassBtn.on('click', function(e) {
		e.preventDefault();
		changeBtn = $(this);
		form = $('#form-change-pass');
		validateForms(form, { handleMsg: true });
		if(form.valid()) {
			var userType = $('#user-type').val();
			var currentPass = $('#current-pass').val();
			var newPass = new_Pass.val();
			var confirmPass = $('#confirm-pass').val();
			btnText = changeBtn.text().trim();

			if(userType == '1') {
				currentPass = currentPass.toUpperCase();
			}

			var passData = {
				currentPass: cryptoPass(currentPass),
				newPass: cryptoPass(newPass),
				confirmPass: cryptoPass(confirmPass)
			}

			insertFormInput(true, form);
			changeBtn.html(loader);
			changePassword(passData, btnText);
		}
	});
});

function passWordStrength(pswd) {
	var valid;

	if ( pswd.length < 8 || pswd.length > 15 ) {
		$('.pass-config #length').removeClass('valid').addClass('invalid');
		valid = false;
	} else {
		$('.pass-config #length').removeClass('invalid').addClass('valid');
		valid = true;
	}

	if (pswd.match(/[a-z]/) ) {
		$('.pass-config #letter').removeClass('invalid').addClass('valid');
		valid = !valid ?  valid : true;
	} else {
		$('.pass-config #letter').removeClass('valid').addClass('invalid');
		valid = false;
	}

	if (pswd.match(/[A-Z]/) ) {
		$('.pass-config #capital').removeClass('invalid').addClass('valid');
		valid = !valid ?  valid : true;
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
		valid = !valid ?  valid : true;
	} else {
		$('.pass-config #consecutivo').removeClass('valid').addClass('invalid');
		valid = false;
	}

	if (pswd.match(/([!@\*\-\?¡¿+\/.,_#])/)) {
		$('.pass-config #especial').removeClass('invalid').addClass('valid');
		valid = !valid ?  valid : true;
	} else {
		$('.pass-config #especial').removeClass('valid').addClass('invalid');
		valid = false;
	}

	return valid;
}

function changePassword(passData, textBtn) {
	verb = "POST"; who = 'User'; where = 'ChangePassword'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data
		switch(response.code) {
			case 0:
			case 1:
				notiSystem(response.title, response.msg, response.icon, response.data)
				break;
		}
		$('#form-change-pass')[0].reset();
		$('#form-change-pass input, #form-change-pass button').attr('disabled', false);
		$('#btn-change-pass').html(textBtn)
	})
}
