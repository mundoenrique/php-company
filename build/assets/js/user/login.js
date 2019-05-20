'use strict'
$(function() {
	$.balloon.defaults.css = null;

	$('#login-btn').on('click', function(e) {
		e.preventDefault();
		var
		user = $('#user_login'),
		pass = $('#user_pass'),
		loginBtn = $(this);

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
			var text = loginBtn.text()
			user = {
				user: user.val(),
				pass: $.md5(pass.val()),
				active: ''
			}
			$('#login-form input, #login-form button').attr('disabled', true);
			loginBtn.html(loader);
			
			grecaptcha.ready(function() {
                grecaptcha.execute('6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf', {action: 'login'}).then(function(token) {
                    validateCaptcha(token,user,text);        
                });;
            });
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
				$('#login-form input, #login-form button').attr('disabled', false);
				$('#login-btn').html(text);
                break;
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
		if(response.code !== 2) {
			$('#login-form input, #login-form button').attr('disabled', false);
			$('#login-btn').html(text);
			$('#user_pass').val('');

			setTimeout(function() {
				$("#user_login").hideBalloon();
			}, 2000);
		}
	})
}
