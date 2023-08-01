'use strict'
$(function() {
	$('#terms').on('click', function() {
		title = lang.GEN_SYSTEM_NAME;
		msg = lang.GEN_ACCEPT_TERMS;
		icon = lang.SETT_ICON_INFO;
		modalBtn = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				link: lang.SETT_LINK_CHANGE_PASS,
				action: 'redirect'
			},
			btn2: {
				text: lang.GEN_BTN_CANCEL,
				link: lang.SETT_LINK_SIGNOUT + lang.SETT_LINK_SIGNOUT_START,
				action: 'redirect'
			}
		};
		$('#cancel').on('click', function(e) {
			$('#terms').prop('checked', false);
		})
		appMessages(title, msg, icon, modalBtn);
	});
});
