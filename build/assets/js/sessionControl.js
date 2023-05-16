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
	$('#system-msg').removeClass('w-100 vh-100');

	if ($('#system-info').parents('.ui-dialog').length) {
		$('#system-info').dialog('destroy');
	}

	$('#accept').addClass('btn-large-xl')
	modalBtn = {
		btn1: {
			text: lang.GEN_BTN_KEEP_SESSION,
			action: 'destroy'
		}
	}
	appMessages(lang.GEN_SYSTEM_NAME, lang.GEN_FINISH_TEXT, lang.SETT_ICON_INFO, modalBtn);
	$('#accept').addClass('keep-session');
	resetTimesession = setTimeout(function() {
		$('#accept')
			.html(loader)
			.prop('disabled', true);
		$(location).attr('href', baseURL + lang.SETT_LINK_SIGNOUT + lang.SETT_LINK_SIGNOUT_END);
	}, callServer);

	$('#system-info').on('click', '.keep-session', function() {
		$(this).off('click');
		who = 'User';
		where = 'KeepSession';
		data = {}

		callNovoCore(who, where, data, function(response) {
			$('#accept')
				.text(lang.GEN_BTN_ACCEPT)
				.removeClass('keep-session')
				.removeClass('btn-large-xl');
		})
	})
}
