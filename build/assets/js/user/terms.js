'use strict'
$(function() {
	$('#terms').on('click', function() {
		title = lang.GEN_SYSTEM_NAME;
		msg = 'Al presionar "Aceptar" declaras que has leído y aceptado los términos de uso de nuestra plataforma..';
		icon = lang.GEN_ICON_INFO;
		data = {
			btn1: {
				text: 'Aceptar',
				link: 'cambiar-clave',
				action: 'redirect'
			},
			btn2: {
				text: 'Cancelar',
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
