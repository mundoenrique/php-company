'use strict'
$(function() {

	$.balloon.defaults.css = null;
	$('#new-pass')
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

		passStrength(pswd);

	})
	.on('blur', function() {
		$("#new-pass").hideBalloon();
	});

	$('#btn-change-pass').on('click', function(e) {
		e.preventDefault();
		var ChangeBtn = $(this);
		form = $('#form-change-pass');
		validateForms(form, { handleMsg: true });
		if(form.valid()) {
			var userType = $('#user-type').val();
			var currentPass = $('#current-pass').val();
			var newPass = $('#new-pass').val();
			var confirmPass = $('#confirm-pass').val();
			var textBtn = ChangeBtn.text();

			if(userType == '1') {
				currentPass = currentPass.toUpperCase();
			}

			var passData = {
				currentPass: cryptoPass(currentPass),
				newPass: cryptoPass(newPass),
				confirmPass: cryptoPass(confirmPass)
			}

			$('#form-change-pass input, #form-change-pass button').attr('disabled', true);
			ChangeBtn.html(loader);
			changePassword(passData, textBtn);
		}
	});
});

function passStrength(pswd) {
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
