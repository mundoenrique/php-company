'use strict'
$(function() {
	var data;
	$.balloon.defaults.css = null;
	disabledInputsform(false);

	$('#login-btn').on('click', function(e) {
		e.preventDefault();

		$(".general-form-msg").html('');
		var text = $(this).text();
		var form = $('#login-form');
		var user = getCredentialsUser();
		var captcha = getPropertyOfElement('recaptcha');
		where = getPropertyOfElement('login-uri', '#widget-signin');
		console.log('captcha', captcha)

		validateForms(form, {handleMsg: false});
		if(form.valid()) {

			disabledInputsform(true);
			$(this).html(loader);

			if(captcha) {
				grecaptcha.ready(function() {
					grecaptcha
					.execute('6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf', {action: 'login'})
					.then(function(token) {
						validateLogin({token: token, user: user, where: where});
					}, function(token) {
						if(!token) {
							title = prefixCountry + strCountry;
							icon = iconWarning;
							data = {
								btn1: {
									link: baseURL+'inicio',
									action: 'redirect'
								}
							};
							notiSystem(title, msg, icon, data);
							restartFormLogin(text);
						}
					});
				});
			} else {
				validateLogin({token: 'token', user: user, text: text, where: where});
			}
		} else {
			if (user.user=='' || user.pass=='d41d8cd98f00b204e9800998ecf8427e') {
				$(".general-form-msg").html('Todos los campos son requeridos');
			} else {
				$(".general-form-msg").html('Combinación incorrecta de usuario y contraseña');
			}
		}
	});

	function disabledInputsform(disable){
		$('#login-form input, #login-form button').attr('disabled', disable);
	}

	function restartFormLogin(textBtn) {

		disabledInputsform(false);
		$('#login-btn').html(textBtn);
		$('#user_pass').val('');
		if(country == 'bp') {
			$('#user_login').val('');
		}
		setTimeout(function() {
			$("#user_login").hideBalloon();
		}, 2000);
	};

	function getCredentialsUser(){
		return {
			user: $('#user_login').val(),
			pass: $.md5($('#user_pass').val()),
			active: ''
		}
	};

	function validateLogin(dataValidateLogin){
		data = {
			user: dataValidateLogin.user.user,
			token: dataValidateLogin.token,
			dataLogin: [dataValidateLogin.user, dataValidateLogin.text]
		}
		verb = "POST"; who = 'User'; where = dataValidateLogin.where;
		callNovoCore(verb, who, where, data, function(response) {

			if (response.code !== 0 && response.owner === 'captcha') {

				notiSystem(response.title, response.msg, response.icon, response.data);
				restartFormLogin(dataValidateLogin.text);

			} else {
				validateResponseLogin(response, dataValidateLogin.text);
			}
		})
	}

	function validateResponseLogin(response, textBtn) {
		responseCodeLogin[response.code](response, textBtn);
	}

	const responseCodeLogin = {
		0: function(response) {
			$(location).attr('href', response.data)
		},
		1: function(response, textBtn){
			$('#user_login').showBalloon({
				html: true,
				classname: response.className,
				position: "left",
				contents: response.msg
			});
			restartFormLogin(textBtn);
		},
		2: function() {
			user.active = 1;
			verb = "POST"; who = 'User'; where = 'Login'; data = getCredentialsUser();
			callNovoCore(verb, who, where, data, function(response) {
				validateResponseLogin(response);
			})
		},
		3: function(response, textBtn){
			var dataLogin = getCredentialsUser();
			notiSystem(response.title, response.msg, response.icon, response.data);
			var btn = response.data.btn1;
			if(btn.action == 'logout') {
				$('#accept').on('click', function() {
					verb = 'POST'; who = btn.link.who; where = btn.link.where; data = dataLogin;
					callNovoCore (verb, who, where, data);
				});
			}
			restartFormLogin(textBtn);
		},
		4: function(response, textBtn) {
			console.log('aqui');
			restartFormLogin(textBtn);
		}
	}

	$('#user_login, #user_pass').on('focus keypress', function() {
		$(this).removeClass('validate-error');
	})

})
