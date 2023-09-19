import { getToken } from '../common/captchaHelper.js';
import { cryptography } from '../common/encrypt_decrypt.js';
import { calledCoreApp } from '../connection/core_app.js';
import { appLoader, takeFormData, toggleDisableActions } from '../utils.js';
import { formValidation } from '../validation/form_validation.js';

$(function () {
	$.balloon.defaults.css = null;
	toggleDisableActions(false);
	let btnCalled;
	let btnSignin;
	let dataSignin;
	let formSignin;

	$('#signInBtn').on('click', function (e) {
		e.preventDefault();
		formSignin = $('#signInForm');
		formValidation(formSignin);

		if (formSignin.valid()) {
			btnCalled = $(this);
			btnSignin = $(this).html();
			dataSignin = takeFormData(formSignin);
			dataSignin.userPass = cryptography.encrypt(dataSignin.userPass);
			dataSignin.active = '';
			dataSignin.currentTime = new Date().getHours();
			$(this).html(appLoader);
			toggleDisableActions(true);
			getToken('SignIn', function (recaptchaToken) {
				dataSignin.token = recaptchaToken;
				signIn('SignIn');
			});
		}
	});

	const signIn = function (section) {
		const module = 'user';

		calledCoreApp(module, section, dataSignin, function (response) {
			handleSignInResponse[response.code](response);
		});
	};

	const handleSignInResponse = {
		0: function (response) {
			if (response.data.link) {
				$(location).attr('href', response.data.link);
			} else {
				toggleDisableActions(false);
				btnCalled.html(btnSignin);
				formSignin.validate().resetForm();
			}
		},
		1: function (response) {
			$('#userName').showBalloon({
				html: true,
				classname: response.data.className,
				position: response.data.position,
				contents: response.msg,
			});
			setTimeout(function () {
				$('#userName').hideBalloon();
			}, 2500);
			toggleDisableActions(false);
			btnCalled.html(btnSignin);
			formSignin.validate().resetForm();
		},
		2: function (response) {},
		3: function (response) {},
		4: function (response) {},
	};
});
/*
$('#system-info').on('click', '.session-close', function () {
	toggleDisableActions(true);
	getSignIn('FinishSession');
});
 */
/*
$('#system-info').on('click', '.send-otp', function () {
	formSignin = $('#formVerificationOTP');
	validateForms(formSignin);

	if (formSignin.valid()) {
		toggleDisableActions(true);
		getRecaptchaToken('verifyIP', function (recaptchaToken) {
			dataSignin.token = recaptchaToken;
			dataSignin.otpCode = $('#otpCode').val();
			dataSignin.saveIP = $('#acceptAssert').is(':checked') ? true : false;
			getSignIn('SignIn');
		});
	}
});
 */
/*
const getSignIn = function (section) {
	const module = 'User';

	calledCoreApp(module, section, dataSignin, function (response) {
		handleResponse[response.code](response);
		switch (response.code) {
			case 0:
				response.data.link && $(location).attr('href', response.data.link);
				break;
			case 1:
				$('#userName').showBalloon({
					html: true,
					classname: response.data.className,
					position: response.data.position,
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
				if (response.data.action === 'session-close') {
					$('#accept').addClass(response.data.action);
				}
		}

		if (response.code !== 0) {
			$('#userPass').val('');
			$('#signInBtn').html(btnSignin);
			toggleDisableActions(false);
			formSignin.validate().resetForm();

			if (lang.SETT_RESTAR_USERNAME === 'ON') {
				$('#userName').val('');
			}

			setTimeout(function () {
				$('#userName').hideBalloon();
			}, 3000);
		}
	});
};
 */
/* const handleResponse = {
	0: function (response) {
		if (response.data.link) {
			$(location).attr('href', response.data.link);
		} else {
			toggleDisableActions(false);
			btnCalled.html(btnSignin);
			formSignin.validate().resetForm();
		}
	},
	1: function (response) {
		$('#userName').showBalloon({
			html: true,
			classname: response.data.className,
			position: response.data.position,
			contents: response.msg,
		});
		formSignin.validate().resetForm();
		toggleDisableActions(false);
		btnCalled.html(btnSignin);

		setTimeout(function () {
			$('#userName').hideBalloon();
		}, 3000);
	},
	4: function (response) {
		toggleDisableActions(false);
		btnCalled.html(btnSignin);
		if (response.data.action && response.data.action === 'session-close') {
			$('#accept').addClass(response.data.action);
		}
	},
};
 */
