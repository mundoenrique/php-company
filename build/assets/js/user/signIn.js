'use strict';
$(function () {
	$.balloon.defaults.css = null;
	toggleDisableActions(false);
	let btnSignin;
	let dataSignin;
	let formSignin;
	let loaderSignin = getLoader();

	$('#signInBtn').on('click', function (e) {
		e.preventDefault();
		formSignin = $('#signInForm');
		formValidation(formSignin);

		if (formSignin.valid()) {
			btnSignin = $(this).html();
			dataSignin = takeFormData(formSignin);
			dataSignin.userPass = cryptography.encrypt(dataSignin.userPass);
			dataSignin.active = '';
			$(this).html(loaderSignin);
			formSignin.validate().resetForm();
			toggleDisableActions(true);
			getRecaptchaToken('SignIn', function (recaptchaToken) {
				dataSignin.token = recaptchaToken;
				getSignIn('SignIn', dataSignin, btnSignin);
			});
		}
	});

	$('#system-info').on('click', '.session-close', function () {
		$(this).html(loaderSignin).prop('disabled', true).removeClass('session-close');

		getSignIn('FinishSession');
	});

	$('#system-info').on('click', '.send-otp', function () {
		formSignin = $('#formVerificationOTP');
		validateForms(formSignin);

		if (formSignin.valid()) {
			$(this).html(loader).prop('disabled', true).removeClass('send-otp');
			toggleDisableActions(true);

			getRecaptchaToken('verifyIP', function (recaptchaToken) {
				dataSignin.token = recaptchaToken;
				dataSignin.otpCode = $('#otpCode').val();
				dataSignin.saveIP = $('#acceptAssert').is(':checked') ? true : false;
				getSignIn('SignIn');
			});
		}
	});

	const getSignIn = function (whereSignin, dataRequest, btnHtml) {
		const whoSignin = 'User';

		calledCoreApp(whoSignin, whereSignin, dataRequest, function (response) {
			switch (response.code) {
				case 0:
					if (forWhere === 'SignIn') {
						$(location).attr('href', response.data);
					}
					break;
				case 1:
					$('#userName').showBalloon({
						html: true,
						classname: response.className,
						position: response.position,
						contents: response.msg,
					});
					break;
				case 2:
					$('#accept').addClass('send-otp');
					response.modalBtn.minWidth = 480;
					response.modalBtn.maxHeight = 'none';
					response.modalBtn.posAt = 'center top';
					response.modalBtn.posMy = 'center top+160';

					inputModal =
						'<form id="formVerificationOTP" name="formVerificationOTP" class="mr-2" method="post" onsubmit="return false;">';
					inputModal += '<p class="pt-0 p-0">' + response.msg + '</p>';
					inputModal += '<div class="row">';
					inputModal += '<div class="form-group col-8">';
					inputModal += '<label for="otpCode">' + response.labelInput + '</label>';
					inputModal +=
						'<input id="otpCode" class="form-control" type="text" name="otpCode" autocomplete="off" maxlength="10">';
					inputModal += '<div class="help-block"></div>';
					inputModal += '</div">';
					inputModal += '</div>';
					inputModal += '<div class="form-group custom-control custom-switch mb-0">';
					inputModal += '<input id="acceptAssert" class="custom-control-input" type="checkbox" name="acceptAssert">';
					inputModal += '<label class="custom-control-label" for="acceptAssert">' + response.assert + '</label>';
					inputModal += '</div">';
					inputModal += '</form>';

					appMessages(response.title, inputModal, response.icon, response.modalBtn);
					break;
				case 3:
					response.modalBtn.minWidth = 480;
					response.modalBtn.maxHeight = 'none';
					response.modalBtn.posAt = 'center top';
					response.modalBtn.posMy = 'center top+160';
					inputModal = response.msg;
					appMessages(response.title, inputModal, response.icon, response.modalBtn);
					break;
				default:
					if (response.data === 'session-close') {
						$('#accept').addClass(response.data);
					}
			}

			if (response.code !== 0) {
				$('#userPass').val('');
				$('#signInBtn').html(btnHtml);
				toggleDisableActions(false);

				if (lang.SETT_RESTAR_USERNAME === 'ON') {
					$('#userName').val('');
				}

				setTimeout(function () {
					$('#userName').hideBalloon();
				}, 2000);
			}
		});
	};
});
