'use strict'
$(function () {
	$.balloon.defaults.css = null;
	insertFormInput(false);

	$('#userPass').on('keyup', function () {
		$(this).attr('type', 'password');

		if ($(this).val() == '') {
			$(this).attr('type', 'text');
		}
	});

	$('#userName, #userPass').on('keyup', function () {
		$(this).removeClass('validate-error');
		if ($('#userName').val() != '' && $('#userPass').val() != '') {
			$(".general-form-msg").html('');
		}
	});

	$('#system-info').on('keyup', '#otpCode', function () {
		$(this).removeClass('validate-error');
		if ($('#otpCode').val() != '') {
			$(".help-block").html('');
		}
	});

	$('#signInBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#signInForm');
		validateForms(form, { handleMsg: false })

		if (form.valid()) {
			btnText = $(this).html();
			data = getDataForm(form);
			data.userPass = cryptoPass(data.userPass);
			data.active = '';
			data.currentTime = new Date().getHours();
			$(this).html(loader);
			insertFormInput(true);

			getRecaptchaToken('SignIn', function (recaptchaToken) {
				data.token = recaptchaToken;
				getSignIn('SignIn');
			});
		} else if ($('#userName').val() == '' || $('#userPass').val() == '') {
			$(".general-form-msg").html('Todos los campos son requeridos');
		} else {
			$(".general-form-msg").html('Combinación incorrecta de usuario y contraseña');
		}
	});

	$('#system-info').on('click', '.session-close', function () {
		$(this)
			.html(loader)
			.prop('disabled', true)
			.removeClass('session-close');

		getSignIn('FinishSession');
	});

	$('#system-info').on('click', '.send-otp', function () {
		form = $('#formVerificationOTP');
		validateForms(form, { handleMsg: false })

		if (form.valid()) {
			$(this)
				.html(loader)
				.prop('disabled', true)
				.removeClass('send-otp');
			insertFormInput(true);

			getRecaptchaToken('verifyIP', function (recaptchaToken) {
				data.token = recaptchaToken;
				data.otpCode = $('#otpCode').val();
				data.saveIP = $('#acceptAssert').is(':checked') ? true : false;
				getSignIn('SignIn');
			});
		} else {
			$(".help-block").html(lang.VALIDATE_OTP_CODE);
		}
	});
});

function getSignIn(forWhere) {
	who = 'User';
	where = forWhere;

	callNovoCore(who, where, data, function (response) {
		switch (response.code) {
			case 0:
				if (forWhere == 'SignIn') {
					var link = response.data;

					if (link.indexOf('dashboard') != -1) {
						link = link.replace('/' + customerUri + '/', '/' + oldCustomerUri + '/');
					}

					$(location).attr('href', link);
				}
			break;
			case 1:
				$('#userName').showBalloon({
					html: true,
					classname: response.className,
					position: response.position,
					contents: response.msg
				});
			break;
			case 2:
				$('#accept').addClass('send-otp');
				response.modalBtn.minWidth = 480;
				response.modalBtn.maxHeight = 'none';
				response.modalBtn.posAt = 'center top';
				response.modalBtn.posMy = 'center top+160';

				inputModal = '<form id="formVerificationOTP" name="formVerificationOTP" class="mr-2" method="post" onsubmit="return false;">';
				inputModal += 	'<p class="pt-0 p-0">' + response.msg + '</p>';
				inputModal += 	'<div class="row">';
				inputModal += 		'<div class="form-group col-8">';
				inputModal += 			'<label for="otpCode">' + response.labelInput + '</label>'
				inputModal += 			'<input id="otpCode" class="form-control" type="text" name="otpCode" autocomplete="off" maxlength="10">';
				inputModal += 			'<div class="help-block"></div>'
				inputModal += 		'</div">';
				inputModal += 	'</div>';
				inputModal += 	'<div class="form-group custom-control custom-switch mb-0">'
				inputModal += 		'<input id="acceptAssert" class="custom-control-input" type="checkbox" name="acceptAssert">'
				inputModal += 		'<label class="custom-control-label" for="acceptAssert">' + response.assert + '</label>'
				inputModal += 	'</div">'
				inputModal += '</form>';

				windowsStyle();
				appMessages(response.title, inputModal, '', response.modalBtn);
			break;
			case 3:
				response.modalBtn.minWidth = 480;
				response.modalBtn.maxHeight = 'none';
				response.modalBtn.posAt = 'center top';
				response.modalBtn.posMy = 'center top+160';
				inputModal = response.msg
				windowsStyle();
				appMessages(response.title, inputModal, response.icon, response.modalBtn);
			break;
			default:
				if (response.data == 'session-close') {
					$('#accept').addClass(response.data);
					windowsStyle();
				}
		}

		if (response.code != 0) {
			$('#userPass').val('');
			$('#signInBtn').html(btnText);
			insertFormInput(false);

			if (lang.CONF_RESTAR_USERNAME == 'ON') {
				$('#userPass').val('');
			}

			setTimeout(function () {
				$("#userName").hideBalloon();
			}, 2000);
		}
	});
}

function windowsStyle() {
	$('#system-msg').css("width", "auto");
	if (customerUri == 'bpi') {
		var styles = {
			float: "none",
			margin: "auto"
		};
		$("#system-info .ui-dialog-buttonpane").css(styles).removeClass("modal-buttonset");
		$("#system-info .ui-dialog-buttonset").removeClass("modal-buttonset");
		$("#system-info .btn-modal").removeClass("modal-btn-primary");
	} else {
		$("#label_codeOTP").addClass("line-field");
		$("#codeOTP").addClass("input-field");
	}
}
