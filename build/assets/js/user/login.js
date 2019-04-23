'use strict'
$(function() {
	$.balloon.defaults.css = null;

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
			var loader = $('#loader').html();
			var text = $(this).text()
			user = {
				user: user.val(),
				pass: $.md5(pass.val()),
				active: ''
			}
			$('#login-form input, #login-form button').attr('disabled', true);
			$(this).html(loader);
			$(this).children(0).css({'height': '34px'})
			ingresar(user, text);
		}
	});
	$('#user_login, #user_pass').on('focus keypress', function() {
		$(this).removeClass('validate-error');
	});
})

function ingresar(user, text) {
	verb = "GET"; who = 'User'; where = 'Login'; data = user;
	callNovoCore(verb, who, where, data, function(response) {
		var dataResponse = response.data
		switch(response.code) {
			case 0:
				dataResponse.indexOf('dashboard') != -1 ? dataResponse = dataResponse.replace(country, pais) : '';
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
				notiSystem(response.title, response.msg, response.type, response.data);
				var btn = response.data.btn1;
				if(btn.action == 'logout') {
					$('#accept').on('click', function(){
						verb = 'POST'; who = url.who; where = url.where; data = user;
						callNovoCore (verb, who, where, data)
					});
				}
				break;
			default:

		}
		if(response.code !== 2) {
			setTimeout(function() {
				$("#user_login").hideBalloon();
				$('#login-form input, #login-form button').attr('disabled', false);
				$('#login-btn').html(text);
				$('#user_pass').val('');
			}, 2000);
		}
	})
}
