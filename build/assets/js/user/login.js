'use strict'
$(function() {

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
	}

	const responseCodeLogin = {
		0: function(response){
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
		2: function(){
			user.active = 1;
			verb = "POST"; who = 'User'; where = 'Login'; data = getCredentialsUser();
			callNovoCore(verb, who, where, data, function(response) {
				validateResponseLogin(response);
			})
		},
		3: function(response, textBtn){
			notiSystem(response.title, response.msg, response.icon, response.data);
			var btn = response.data.btn1;
			if(btn.action == 'logout') {
				$('#accept').on('click', function() {
					verb = 'POST'; who = btn.link.who; where = btn.link.where; data = getCredentialsUser();
					callNovoCore (verb, who, where, data);
				});
			}
			restartFormLogin(textBtn);
		}
	}

	function disabledInputsform(disable){
		$('#login-form input, #login-form button').attr('disabled', disable);
	}

	function getCredentialsUser(){
		return {
							user: $('#user_login').val(),
							pass: $.md5($('#user_pass').val()),
							active: ''
						}
	};

	function validateLogin(token,user,text){
		data = {
			user: user.user,
			token: token,
			dataLogin: [user, text]
		}
		verb = "POST"; who = 'User'; where = 'validateCaptcha';
		// verb = "POST"; who = 'User'; where = 'Login'; data = user; // llama al login
		callNovoCore(verb, who, where, data, function(response) {

			if (response.code !== 0 && response.owner === 'captcha'){

				notiSystem(response.title, response.msg, response.icon, response.data);
				disabledInputsform(false);
				$('#login-btn').html(text);

				setTimeout(function() {
					$("#user_login").hideBalloon();
				}, 2000);
			} else {
				validateResponseLogin(response, text);
			}
		})
	}

	function validateResponseLogin(response, textBtn) {

		responseCodeLogin[response.code](response, textBtn);
	}

	$.balloon.defaults.css = null;
	disabledInputsform(false);

	$('#login-btn').on('click', function(e) {
		e.preventDefault();

		$(".general-form-msg").html('');
		var form = $('#login-form');
		validateForms(form, {handleMsg: false});

		var text = $(this).text();
		var user = getCredentialsUser();

		if(form.valid()) {
			disabledInputsform(true);

			$(this).html(loader);
			grecaptcha.ready(function() {
				grecaptcha
				.execute('6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf', {action: 'login'})
				.then(function(token) {
					validateLogin(token, user, text);
				}, function(token) {
					if(!token) {
						title = prefixCountry + strCountry;
						msg = 'No fue posible procesar tu solicitud, por favor vuelve a intentar';
						icon = iconWarning;
						data = {
							btn1: {
								text: 'Aceptar',
								link: false,
								action: 'close'
							}
						};
						notiSystem(title, msg, icon, data);
						disabledInputsform(false);
						$('#login-btn').html(text);
					}
				});
			});
		} else {

			if (user.user.trim() || user.pass.trim()){
				$(".general-form-msg").html('Todos los campos son requeridos');
			} else {
				$(".general-form-msg").html('Combinación incorrecta de usuario y contraseña');
			}
		}
	});

	$('#user_login, #user_pass').on('focus keypress', function() {
		$(this).removeClass('validate-error');
	});

})

