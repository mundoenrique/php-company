'use strict'
$(function() {
	var userCred, forWho, forWhere, btnText;
	$.balloon.defaults.css = null;
	disabledInputsform(false);

	$('#login-btn').on('click', function(e) {
		e.preventDefault();

		$(".general-form-msg").html('');
		var form = $('#login-form');
		var captcha = getPropertyOfElement('recaptcha', '#system-info');
		userCred = getCredentialsUser();
		btnText = $(this).text();

		validateForms(form, {handleMsg: false});
		if(form.valid()) {

			disabledInputsform(true);
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
							title = prefixCountry + strCountry;
							icon = iconWarning;
							data = {
								btn1: {
									link: baseURL+'inicio',
									action: 'redirect'
								}
							};
							notiSystem(title, msg, icon, data);
							restartFormLogin();
						}
					});
				});
			} else {
				validateLogin();
			}
		} else {
			if (userCred.user == '' || userCred.pass=='d41d8cd98f00b204e9800998ecf8427e') {
				$(".general-form-msg").html('Todos los campos son requeridos');
			} else {
				$(".general-form-msg").html('Combinación incorrecta de usuario y contraseña');
			}
		}
	});

	function disabledInputsform(disable){
		$('#login-form input, #login-form button').attr('disabled', disable);
	}

	function restartFormLogin() {

		disabledInputsform(false);
		$('#login-btn').html(btnText);
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

	function validateLogin(token) {
		verb = 'POST'; who = forWho || 'User'; where = forWhere || getPropertyOfElement('login-uri', '#widget-signin');
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
			$('#user_login').showBalloon({
				html: true,
				classname: response.className,
				position: "left",
				contents: response.msg
			});
			restartFormLogin();
		},
		2: function() {
			userCred.active = 1; forWhere = 'Login';
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
	})

})
