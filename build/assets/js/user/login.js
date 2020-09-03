'use strict'
$(function () {
	var userCred, forWho, forWhere;
	var userLogin = $('#user_login');
	var userPass = $('#user_pass');
	var loginIpMsg, formcodeOTP, btnTextOtp;
	var captcha = lang.GEN_ACTIVE_RECAPTCHA;

	$('#user_pass').on('keyup', function() {
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
							icon = lang.GEN_ICON_WARNING;
							data = {
								btn1: {
									link: 'inicio',
									action: 'redirect'
								}
							};
							notiSystem(false, false, icon, data);
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
		if (lang.RESTART_LOGIN=='ON') {
			userLogin.val('');
		}
		setTimeout(function () {
			$("#user_login").hideBalloon();
		}, 2000);
	};

	function getCredentialsUser() {
		cypherPass = (userPass.val() === '' && userCred) ? userCred.pass : cryptoPass(userPass.val());
		return {
			user: userLogin.val(),
			pass: cypherPass,
			active: '',
			codeotp: $('#codeOTP').val() ? $('#codeOTP').val() : '',
			saveip : $('#acceptAssert').prop('checked') ? $('#acceptAssert').prop('checked') : '',
			modalreq : $('#codeOTP').val() ? true : ''
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
				btn = response.data.btn1;

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

				notiSystem(response.title, loginIpMsg, response.icon, optionsData);
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
			notiSystem(response.title, response.msg, response.icon, response.data);
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

	$('#user_login, #user_pass').on('focus keypress', function () {
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
