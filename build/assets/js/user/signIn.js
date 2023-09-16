'use strict';
$(function () {
	$.balloon.defaults.css = null;
	toggleDisableActions(false);

	if (lang.SETT_MAINT_NOTIF === 'ON') {
		var mesgNotif = lang.GEN_MSG_MAINT_NOTIF.replace('%s', assetUrl + 'images/ve/maint_notif3.png');
		modalBtn = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'destroy',
			},
			maxHeight: 'none',
			minWidth: 480,
			posAt: 'center top',
			posMy: 'center top+100',
		};

		appMessages(lang.GEN_SYSTEM_NAME, mesgNotif, '', modalBtn);
	}

	$('#userPass').on('keyup', function () {
		$(this).attr('type', 'password');

		if ($(this).val() === '') {
			$(this).attr('type', 'text');
		}
	});

	$('#signInBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#signInForm');
		formValidation(form);

		if (form.valid()) {
			btnContent = $(this).html();
			data = takeFormData(form);
			data.userPass = cryptography.encrypt(data.userPass);
			data.active = '';
			$(this).html(loader);
			form.validate().resetForm();
			toggleDisableActions(true);
			getRecaptchaToken('SignIn', function (recaptchaToken) {
				data.token = recaptchaToken;
				getSignIn('SignIn');
			});

			// btnContent = $(this).html();
			// data = takeFormData(form);
			// data.userPass = cryptography.encrypt(data.userPass);
			// data.active = '';
			// $(this).html(loader);
			// form.validate().resetForm();
			// toggleDisableActions(true);
			// getRecaptchaToken('SignIn', function (recaptchaToken) {
			// 	data.token = recaptchaToken;
			// 	data = cryptography.encrypt({ data });
			// 	let form = document.createElement('form');
			// 	form.setAttribute('id', 'payloadForm');
			// 	form.setAttribute('name', 'payloadForm');
			// 	form.setAttribute('method', 'post');
			// 	form.setAttribute('enctype', 'multipart/form-data');
			// 	form.setAttribute('action', baseURL + 'sign-in');

			// 	let inputData = document.createElement('input');
			// 	inputData.setAttribute('type', 'hidden');
			// 	inputData.setAttribute('id', 'payload');
			// 	inputData.setAttribute('name', 'payload');
			// 	inputData.setAttribute('value', data);
			// 	form.appendChild(inputData);

			// 	if (activeSafety) {
			// 		let inputNovo = document.createElement('input');
			// 		inputNovo.setAttribute('type', 'hidden');
			// 		inputNovo.setAttribute('id', novoName);
			// 		inputNovo.setAttribute('name', novoName);
			// 		inputNovo.setAttribute('value', novoValue);
			// 		form.appendChild(inputNovo);
			// 	}

			// 	document.getElementById('calledCoreApp').appendChild(form);
			// 	form.submit();
			// });
		}
	});

	$('#system-info').on('click', '.session-close', function () {
		$(this).html(loader).prop('disabled', true).removeClass('session-close');

		getSignIn('FinishSession');
	});

	$('#system-info').on('click', '.send-otp', function () {
		form = $('#formVerificationOTP');
		validateForms(form);

		if (form.valid()) {
			$(this).html(loader).prop('disabled', true).removeClass('send-otp');
			toggleDisableActions(true);

			getRecaptchaToken('verifyIP', function (recaptchaToken) {
				data.token = recaptchaToken;
				data.otpCode = $('#otpCode').val();
				data.saveIP = $('#acceptAssert').is(':checked') ? true : false;
				getSignIn('SignIn');
			});
		}
	});
});

function getSignIn(forWhere) {
	who = 'User';
	where = forWhere;

	calledCoreApp(who, where, data, function (response) {
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
			$('#signInBtn').html(btnText);
			toggleDisableActions(false);

			if (lang.SETT_RESTAR_USERNAME === 'ON') {
				$('#userName').val('');
			}

			setTimeout(function () {
				$('#userName').hideBalloon();
			}, 2000);
		}
	});
}
