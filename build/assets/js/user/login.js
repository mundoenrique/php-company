'use strict'
'use strict'
$(function () {
	$.balloon.defaults.css = null;
	insertFormInput(false);

	$('#userPass').on('keyup', function () {
		$(this).attr('type', 'password')
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
		formInputTrim(form);
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
		formInputTrim(form);
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
	verb = 'POST'; who = 'User'; where = forWhere;
	callNovoCore(verb, who, where, data, function (response) {
		switch (response.code) {
			case 0:
				if (forWhere == 'SignIn') {
					$(location).attr('href', response.data);
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
				inputModal += '<p class="pt-0 p-0">' + response.msg + '</p>';
				inputModal += '<div class="row">';
				inputModal += '<div class="form-group col-8">';
				inputModal += '<label for="otpCode">' + response.labelInput + '</label>'
				inputModal += '<input id="otpCode" class="form-control" type="text" name="otpCode" autocomplete="off" maxlength="10">';
				inputModal += '<div class="help-block"></div>'
				inputModal += '</div">';
				inputModal += '</div>';
				inputModal += '<div class="form-group custom-control custom-switch mb-0">'
				inputModal += '<input id="acceptAssert" class="custom-control-input" type="checkbox" name="acceptAssert">'
				inputModal += '<label class="custom-control-label" for="acceptAssert">' + response.assert + '</label>'
				inputModal += '</div">'
				inputModal += '</form>';

				windowsStyle();
				appMessages(response.title, inputModal, '', response.modalBtn);
				break;
			default:
				if (response.data == 'session-close') {
					$('#accept').addClass(response.data);
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
	if (lang.MODAL_OTP == 'ON') {
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
























/* $(function () {
	var userCred, forWho, forWhere;
	var userLogin = $('#userName');
	var userPass = $('#userName');
	var loginIpMsg, formcodeOTP, btnTextOtp;
	var captcha = lang.GEN_ACTIVE_RECAPTCHA;

	$('#userName').on('keyup', function() {
		$(this).attr('type', 'password')
	})
	$.balloon.defaults.css = null;
	insertFormInput(false);
	inputDisabled(false);

	$('#login-btn').on('click', function (e) {
		e.preventDefault();
		$(".general-form-msg").html('');
		form = $('#login-form');
		userCred = getCredentialsUser();
		btnText = $(this).html();
		formInputTrim(form);
		validateForms(form, { handleMsg: false });
			if (form.valid()) {
				insertFormInput(true);
				inputDisabled(true);
				$(this).html(loader);
				recaptcha();
			} else {
				if (userLogin.val() == '' || userPass.val() == '') {
					$(".general-form-msg").html('Todos los campos son requeridos');
				} else {
					$(".general-form-msg").html('Combinación incorrecta de usuario y contraseña');
				}
				verifyPassValidate()
			}
	});

	function recaptcha() {
		if (captcha) {
			grecaptcha.ready(function () {
				grecaptcha
					.execute('6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf', { action: 'login' })
					.then(function (token) {
						if (token) {
							validateLogin(token);
						}
					}, function (token) {
						if (!token) {
							icon = lang.CONF_ICON_WARNING;
							data = {
								btn1: {
									link: 'inicio',
									action: 'redirect'
								}
							};
							appMessages(false, false, icon, data);
							restartFormLogin();
						}
					});
			});
		} else {
			validateLogin();
		}
	}

	function restartFormLogin() {
		insertFormInput(false);
		inputDisabled(false);
		$('#login-btn').html(btnText);
		userPass.val('');

		if (lang.CONF_RESTAR_USERNAME == 'ON') {
			userLogin.val('');
		}

		setTimeout(function () {
			$("#userName").hideBalloon();
		}, 2000);
	};

	function getCredentialsUser() {
		return {
			userName: userLogin.val(),
			userPass: cryptoPass(userPass.val()),
			active: '',
			codeotp: $('#codeOTP').val() ? $('#codeOTP').val() : '',
			saveIP : $('#acceptAssert').prop('checked') ? $('#acceptAssert').prop('checked') : ''
		}
	};

	function validateLogin(token) {
		verb = 'POST'; who = forWho || 'User'; where = forWhere || lang.GEN_LOGIN;
		data = {
			user: userCred.user,
			pass: userCred.pass,
			active: userCred.active,
			currentTime: new Date().getHours(),
			token: token || '',
			codeOTP: userCred.codeotp,
			saveIP: userCred.saveip,
			modalReq :userCred.modalreq
		}
		callNovoCore(verb, who, where, data, function (response) {
			responseCodeLogin[response.code](response);
		})
		forWho = null; forWhere = null
	}

	const responseCodeLogin = {
		0: function (response) {
			if (response.data) {
				$(location).attr('href', response.data)
			} else {
				$('#accept')
					.html(response.msg)
					.attr('disabled', false);
				restartFormLogin();
			}
		},
		1: function (response) {
			userLogin.showBalloon({
				html: true,
				classname: response.className,
				position: response.position,
				contents: response.msg
			});
			restartFormLogin();
		},
		2: function (response) {
			restartFormLogin();
			if(response.ipInvalid){
				var oldID = $('#accept').attr('id');
				var optionsData = response.data;
				$('#accept').attr('id', 'send-otp-btn');

				loginIpMsg ='<form id="formVerificationOTP" name="formVerificationOTP" class="mr-2" method="post" onsubmit="return false;">';
				loginIpMsg+='<p class="pt-0 p-0">'+response.msg+'</p>';
				loginIpMsg+='<div class="row">';
				loginIpMsg+=	'<div class="form-group col-6">';
				loginIpMsg+=	'<label id="label_codeOTP" for="codeOTP">'+response.labelInput+'</label>';
				loginIpMsg+=	'<input id="codeOTP" class="form-control" type="text" name="codeOTP" autocomplete="off">';
				loginIpMsg+=    '<div id="msgErrorCodeOTP" class="help-block"></div>';
				loginIpMsg+=	'</div>';
				loginIpMsg+='</div>';
				loginIpMsg+='<div class="form-group custom-control custom-switch mb-0">';
				loginIpMsg+=	'<input id="acceptAssert" class="custom-control-input" type="checkbox" name="acceptAssert"> ';
				loginIpMsg+=	'<label class="custom-control-label" for="acceptAssert">'+response.assert+'</label>';
				loginIpMsg+='</div>';
				loginIpMsg+='</form>';

				$('#formVerificationOTP input').attr('disabled', false);

				optionsData.minWidth = lang.MIN_WIDTH_OTP;
				optionsData.maxHeight = 'none';
				optionsData.posAt = "center top";
				optionsData.posMy = lang.POSTMY_OTP;

				appMessages(response.title, loginIpMsg, response.icon, optionsData);
				windowsStyle();

				formcodeOTP = $('#formVerificationOTP');

				$('#send-otp-btn').on('click', function(e) {
					e.preventDefault();
					e.stopImmediatePropagation();
					btnTextOtp = $('#send-otp-btn').html();
					formInputTrim(formcodeOTP);
					validateForms(formcodeOTP,{handleMsg: true, modal:true});
					if(formcodeOTP.valid()){
						$('#formVerificationOTP input').attr('disabled', true);
						$(this)
						.off('click')
						.html(loader)
						.attr('id', oldID);
						userCred = getCredentialsUser();
						recaptcha();
					}
				});
				$('#cancel').on('click', function() {
					restartFormLogin();
				});
				$('#send-otp-btn').html(btnTextOtp);
			} else{
				userCred.active = 1; forWhere = lang.GEN_LOGIN;
				validateLogin();
			}
		},
		3: function (response) {
			var btn = response.data.btn1;
			if (btn.action == 'logout') {
				var oldID = $('#accept').attr('id');
				$('#accept').attr('id', 'closed-btn');
			} else {
				restartFormLogin();
			}
			appMessages(response.title, response.msg, response.icon, response.data);
			if (btn.action == 'logout') {
				$('#closed-btn').on('click', function () {
					$(this)
						.html(loader)
						.attr('disabled', true)
						.attr('id', oldID);
					forWho = btn.link.who; forWhere = btn.link.where;
					validateLogin();
				});
				$('#login-btn').html(btnText);
			}
		},
		4: function () {
			restartFormLogin();
		}
	}

	$('#userName, #userName').on('focus keypress', function () {
		$(this).removeClass('validate-error');
		verifyPassValidate();
	});

	function verifyPassValidate() {
		if (userPass.val() != '' && validatePass.test(userPass.val())) {
			userPass.removeClass('has-error');
		}
	}

	function inputDisabled(disable) {
		$('#login-form input').attr('disabled', disable);
	}

	function windowsStyle() {
		$('#system-msg').css( "width", "auto" );
		if (lang.MODAL_OTP=='ON') {
			var styles = {
				float : "none",
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
})
 */
