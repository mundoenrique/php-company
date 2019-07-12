'use strict'
$(function() {
	$('#login-form input, #login-form button').attr('disabled', false);
	$.balloon.defaults.css = null;

	$('#login-btn').on('click', function(e) {
		e.preventDefault();
		$(".general-form-msg").html('');
		var form = $('#login-form');
		var loginBtn = $(this);
		validateForms(form, {handleMsg: false});
		if(form.valid()) {
			var text = loginBtn.text();
			var user = {
				user: $('#user_login').val(),
				pass: $.md5($('#user_pass').val()),
				active: ''
			};
			$('#login-form input, #login-form button').attr('disabled', true);
			loginBtn.html(loader);
			grecaptcha.ready(function() {
				grecaptcha
				.execute('6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf', {action: 'login'})
				.then(function(token) {
					validateCaptcha(token, user, text);
				}, function(token) {
					if(!token) {
						title = prefixCountry + strCountry;
						msg = 'No fue posible procesar tu solicitud, por favor vuelva a intentar';
						icon = iconWarning;
						data = {
							btn1: {
								text: 'Aceptar',
								link: false,
								action: 'close'
							}
						};
						notiSystem(title, msg, icon, data);
						$('#login-form input, #login-form button').attr('disabled', false);
						$('#login-btn').html(text);
					}
				});
			});
		} else {
			var username = $('#user_login');
			var passValue = $('#user_pass').val();
			var empty = false;
			if (username.val().trim()==='') {
				empty = true;
				username.val('');
			}
			if (passValue==='')
				empty = true;
			if (empty)
				$(".general-form-msg").html('Todos los campos son requeridos');
			else
				$(".general-form-msg").html('Combinación incorrecta de usuario y contraseña');
		}
	});

	$('#user_login, #user_pass').on('focus keypress', function() {
		$(this).removeClass('validate-error');
	});
})

function validateCaptcha(token,user,text)
{
	data = {
		user: user.user,
		token: token
	}
	verb = "POST"; who = 'User'; where = 'validateCaptcha';
	callNovoCore(verb, who, where, data, function(response) {
		switch(response.code) {
			case 0:
				ingresar(user,text);
				break;
			case 1:
				notiSystem(response.title, response.msg, response.icon, response.data);
				break;
		}
		if(response.code !== 0) {
			$('#login-form input, #login-form button').attr('disabled', false);
			$('#login-btn').html(text);

			setTimeout(function() {
				$("#user_login").hideBalloon();
			}, 2000);
		}

	})
}

function ingresar(user, text) {
	verb = "POST"; who = 'User'; where = 'Login'; data = user;
	callNovoCore(verb, who, where, data, function(response) {
		var dataResponse = response.data
		switch(response.code) {
			case 0:
				dataResponse.indexOf('dashboard') != -1 ? dataResponse = dataResponse.replace(country+'/', pais+'/') : '';
				$(location).attr('href', dataResponse)
				break;
			case 1:
				$('#user_login').showBalloon({
					html: true,
					classname: response.className,
					position: "left",
					contents: response.msg
				});
				break;
			case 2:
				user.active = 1;
				ingresar(user, text);
				break;
			case 3:
				notiSystem(response.title, response.msg, response.icon, response.data);
				var btn = response.data.btn1;
				if(btn.action == 'logout') {
					$('#accept').on('click', function() {
						verb = 'POST'; who = btn.link.who; where = btn.link.where; data = user;
						callNovoCore (verb, who, where, data);
					});
				}
				break;
		}
		if(response.code !== 2 && response.code !== 0) {
			$('#login-form input, #login-form button').attr('disabled', false);
			$('#login-btn').html(text);
			$('#user_pass').val('');
			if(country == 'bp') {
				$('#user_login').val('');
			}

			setTimeout(function() {
				$("#user_login").hideBalloon();
			}, 2000);
		}
	})
}
