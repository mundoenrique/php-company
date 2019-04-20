'use strict'
$(function() {
	$('#login-btn').on('click', function(e) {
		e.preventDefault();
		var
		user = $('#user_login'),
		pass = $('#user_pass');

		if(!user.val() && !pass.val()) {
			$('#user_login, #user_pass')
			.addClass('validate-error')
			.attr('placeholder', 'Campo obligatorio');
		} else if(!user.val()) {
			user
			.addClass('validate-error')
			.attr('placeholder', 'Campo obligatorio');
		} else if(!pass.val()) {
			pass
			.addClass('validate-error')
			.attr('placeholder', 'Campo obligatorio');
		} else {
			user = {
				user: user.val(),
				pass: $.md5(pass.val())
			}
			ingresar(user);
		}
	});
	$('#user_login, #user_pass').on('focus keypress', function() {
		$(this).removeClass('validate-error');
	});
})

function ingresar(user) {
	verb = "GET"; who = 'User'; where = 'Login'; data = user;
	callNovoCore (verb, who, where, data, function(response) {
		switch(response.code) {
			case 200:
				//$(location).attr('href', baseUrl+'notificaciones/')
				break;
			case 206:
				//$(location).attr('href', baseUrl+'cambiodeclave')
				break;
			default:
				title = response.title;
				msg = response.msg;
				messagesUser(title, iconWarning, ClassWarning, msg, dirCenter);
		}
	})
}
