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
	.on('keyup', function() {
		var pswd = $(this).val();

		passStrength(pswd);

	})
	.on('blur', function() {
		$("#new-pass").hideBalloon();
	});

	$('#btn-change-pass').on('click', function(e) {
		e.preventDefault();
		var form = $('#form-change-pass');
		validateForms(form);
		if(form.valid()) {
			var userType = $('#user-type').val();
			var currentPass = $('#current-pass').val();
			var newPass = $('#new-pass').val();
			var confirmPass = $('#confirm-pass').val();

			if(userType == '1') {
				currentPass = currentPass.toUpperCase();
			}

			var passData = {
				currentPass: $.md5(currentPass),
				newPass: $.md5(newPass),
				confirmPass: $.md5(confirmPass)
			}
			changePassword(passData);
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

	if (!pswd.match(/((\w|[!@#$%])*\d(\w|[!@#$%])*\d(\w|[!@#$%])*\d(\w|[!@#\$%])*\d(\w|[!@#$%])*(\d)*)/)
			&& pswd.match(/\d{1}/) ) {
		$('.pass-config #number').removeClass('invalid').addClass('valid');
		valid = !valid ?  valid : true;
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

function changePassword(passData) {
	verb = "POST"; who = 'User'; where = 'ChangePassword'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		var dataResponse = response.data
		switch(response.code) {
			case 0:
			case 1:
				notiSystem(response.title, response.msg, response.icon, response.data)
				break;
		}

	})
}

