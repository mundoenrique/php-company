'use strict'
$(function() {
	var userCred, forWho, forWhere;
	var userLogin = $('#user_login');
	var userPass = $('#user_pass');
	$.balloon.defaults.css = null;
	insertFormInput(false);
	inputDisabled(false);

	$('#login-btn').on('click', function(e) {
		e.preventDefault();
		$(".general-form-msg").html('');
		var captcha = lang.GEN_ACTIVE_RECAPTCHA;
		form = $('#login-form');
		userCred = getCredentialsUser();
		btnText = $(this).text();
		formInputTrim(form);
		validateForms(form, {handleMsg: false});

		if(form.valid()) {
			insertFormInput(true);
			inputDisabled(true);
			$(this).html(loader);
			if(captcha) {
				grecaptcha.ready(function() {
					grecaptcha
					.execute('6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf', {action: 'login'})
					.then(function(token) {
						if(token) {
							validateLogin(token);
						}
					}, function(token) {
						if(!token) {
							icon = lan.GEN_ICON_WARNING;
							data = {
								btn1: {
									link: baseURL+'inicio',
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
		if(country == 'bp') {
			userLogin.val('');
		}
		setTimeout(function() {
			$("#user_login").hideBalloon();
		}, 2000);
	};

	function getCredentialsUser(){
		ceo_cook = getCookieValue();
		cypherPass = CryptoJS.AES.encrypt(userPass.val(), ceo_cook, { format: CryptoJSAesJson }).toString();

		return {
			user: userLogin.val(),
			pass: btoa(JSON.stringify({
				passWord: cypherPass,
				plot: btoa(ceo_cook)
			})),
			active: ''
		}
	};

	function validateLogin(token) {
		verb = 'POST'; who = forWho || 'User'; where = forWhere || lang.GEN_LOGIN_URI;
		data = {
			user: userCred.user,
			pass: userCred.pass,
			active: userCred.active,
			token: token || ''
		}
		callNovoCore(verb, who, where, data, function(response) {
			responseCodeLogin[response.code](response);
		})
		forWho = null; forWhere = null
	}

	const responseCodeLogin = {
		0: function(response) {
			if(response.data) {
				$(location).attr('href', response.data)
			} else {
				$('#system-info').dialog('close');
				$('#accept')
				.html(response.msg)
				.attr('disabled', false);
				restartFormLogin();
			}
		},
		1: function(response, textBtn){
			userLogin.showBalloon({
				html: true,
				classname: response.className,
				position: "left",
				contents: response.msg
			});
			restartFormLogin();
		},
		2: function() {
			userCred.active = 1; forWhere = lang.GEN_LOGIN;
			validateLogin();
		},
		3: function(response) {
			var btn = response.data.btn1;
			if(btn.action == 'logout') {
				var oldID = $('#accept').attr('id');
				$('#accept').attr('id', 'closed-btn');
			} else {
				restartFormLogin();
			}
			notiSystem(response.title, response.msg, response.icon, response.data);
			if(btn.action == 'logout') {
				$('#closed-btn').on('click', function() {
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
		4: function() {
			$('#login-btn').html(btnText);
		}
	}

	$('#user_login, #user_pass').on('focus keypress', function() {
		$(this).removeClass('validate-error');
		verifyPassValidate();
	});

	function verifyPassValidate() {
		if(userPass.val() != '' && validatePass.test(userPass.val())) {
			userPass.removeClass('has-error');
		}
	}
})

function inputDisabled(disable) {
	$('#login-form input').attr('disabled', disable);
}
