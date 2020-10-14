'use strict'
var setTimesession;
var resetTimesession;
$(function() {
	clearTimeout(resetTimesession);
	clearTimeout(setTimesession);
	sessionExpire();
});

function sessionExpire() {
	if(sessionTime > 0) {
		setTimesession = setTimeout(function() {
			finishSession()
		}, (sessionTime - callModal));
	}
}

function finishSession() {
	$('#accept')
		.removeClass('send-request')
		.removeClass('get-auth-key');

	if ($('#system-info').parents('.ui-dialog').length) {
		$('#system-info').dialog('destroy');
	}

	$('#accept').addClass('btn-large-xl')
	data = {
		btn1: {
			text: lang.GEN_BTN_KEEP_SESSION,
			action: 'close'
		}
	}
	appMessages(lang.GEN_SYSTEM_NAME, lang.GEN_FINISH_TEXT, lang.CONF_ICON_INFO, data);
	$('#accept').addClass('keep-session');
	resetTimesession = setTimeout(function() {
		$('#accept')
			.html(loader)
			.prop('disabled', true);
		$(location).attr('href', baseURL+'cerrar-sesion/fin');
	}, callServer);

	$('#system-info').on('click', '.keep-session', function() {
		$(this).off('click');
		verb = 'POST'; who= 'User'; where = 'KeepSession';
		data = {}
		callNovoCore(verb, who, where, data, function(response) {
			$('#accept')
				.text(lang.GEN_BTN_ACCEPT)
				.removeClass('keep-session')
				.removeClass('btn-large-xl');
		})
	})
}
