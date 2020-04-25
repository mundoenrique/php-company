'use strict'
$(function () {
	var userCred, forWho, forWhere;
	var userLogin = $('#user_login');
	var userPass = $('#user_pass');
	var btnTrigger,loginIpMsg,formcodeOTP,btn;
	$.balloon.defaults.css = null;
	insertFormInput(false);
	inputDisabled(false);

	$('#login-btn').on('click', function (e) {
		e.preventDefault();
		$(".general-form-msg").html('');
		var captcha = lang.GEN_ACTIVE_RECAPTCHA;
		form = $('#login-form');
		userCred = getCredentialsUser();
		btnText = $(this).html();
		formInputTrim(form);
		validateForms(form, { handleMsg: false });

		if (form.valid()) {
			insertFormInput(true);
			inputDisabled(true);
			$(this).html(loader);
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
								icon = lan.GEN_ICON_WARNING;
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
		} else {
			if (userLogin.val() == '' || userPass.val() == '') {
				$(".general-form-msg").html('Todos los campos son requeridos');
			} else {
				$(".general-form-msg").html('Combinación incorrecta de usuario y contraseña');
			}
			verifyPassValidate()
		}
	});

	function restartFormLogin() {

		insertFormInput(false);
		inputDisabled(false);
		$('#login-btn').html(btnText);
		userPass.val('');
		if (country == 'bp') {
			userLogin.val('');
		}
		setTimeout(function () {
			$("#user_login").hideBalloon();
		}, 2000);
	};

	function getCredentialsUser() {
		cypherPass = cryptoPass(userPass.val());

		return {
			user: userLogin.val(),
			pass: cypherPass,
			active: ''
		}
	};

	function validateLogin(token) {
		verb = 'POST'; who = forWho || 'User'; where = forWhere || lang.GEN_LOGIN;
		data = {
			user: userCred.user,
			pass: userCred.pass,
			active: userCred.active,
			currentTime: new Date().getHours(),
			token: token || ''
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
				$('#system-info').dialog('close');
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
				position: "left",
				contents: response.msg
			});
			restartFormLogin();
		},
		2: function (response) {

			if(response.ipInvalid){
				btn = response.data.btn1;

				loginIpMsg ='<form id="formVerificationOTP" class="mr-2" method="post">';
				loginIpMsg+='<p>'+response.msg+'</p>';
				loginIpMsg+='<div class="row">';
				loginIpMsg+=	'<div class="form-group col-7">';
				loginIpMsg+=	'<label for="codeOTP">'+response.labelInput+'<span class="danger">*</span></label>';
				loginIpMsg+=	'<input id="codeOTP" class="form-control" type="text" name="codeOTP">';
				loginIpMsg+=    '<div id="msgErrorCodeOTP" class="help-block"></div>';
				loginIpMsg+=	'</div>';
				loginIpMsg+='</div>';
				loginIpMsg+='<div class="form-group custom-control custom-switch my-3">';
				loginIpMsg+=	'<input id="acceptAssert" class="custom-control-input" type="checkbox" name="acceptAssert">';
				loginIpMsg+=	'<label class="custom-control-label" for="acceptAssert">'+response.assert+'</label>';
				loginIpMsg+=	'<div class="help-block"></div>';
				loginIpMsg+='</div>';
				loginIpMsg+='</form>';
				
				notiSystem(response.title, loginIpMsg, response.icon,response.data);
				
				if(btn.action == 'wait') {

					formcodeOTP = $('#formVerificationOTP');
					btnTrigger = document.getElementById('accept');

					btnTrigger.addEventListener('click', function (e) {
						
						formInputTrim(formcodeOTP);
						validateForms(formcodeOTP);

						if(formcodeOTP.valid()){

							data.codeOTP =$('#codeOTP').val();
							data.saveIp = $('#acceptAssert').prop('checked');

							callNovoCore(verb, who, where, data, function (response) {
								responseCodeLogin[response.code](response);
							})
							$("#system-info").dialog('close');
						}
					});
				  }
			} else if(response.codeOtpInvalid){
				data = {
					btn1: {
						text: lang.GEN_BTN_ACCEPT,
						link: 'inicio',
						action: 'redirect'
					}
				}
			notiSystem(false, response.msg, response.icon,data);

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

			$('#login-btn').html(btnText);

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
})

function inputDisabled(disable) {
	$('#login-form input').attr('disabled', disable);
}
