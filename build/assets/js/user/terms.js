'use strict'
$(function() {
	$('#terms').on('click', function() {
		title = lang.GEN_SYSTEM_NAME;
		msg = lang.GEN_ACCEPT_TERMS;
		icon = lang.GEN_ICON_INFO;
		data = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				link: 'cambiar-clave',
				action: 'redirect'
			},
			btn2: {
				text: lang.GEN_BTN_CANCEL,
				link: 'cerrar-sesion/inicio',
				action: 'redirect'
			}
		};
		$('#cancel').on('click', function(e) {
			$('#terms').prop('checked', false);
		})
		notiSystem(title, msg, icon, data);
	});
});
