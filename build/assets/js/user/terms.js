'use strict'
$(function() {
	$('#terms').on('click', function() {
		title = prefixCountry + strCountry;
		msg = 'Al presionar "Aceptar" declara que ha leído y aceptado los términos de uso de nuestra plataforma';
		icon = iconInfo;
		data = {
			btn1: {
				text: 'Aceptar',
				link: baseURL + 'cambiar-clave',
				action: 'redirect'
			},
			btn2: {
				text: 'Cancelar',
				link: false,
				action: 'close'
			}
		};
		$('#cancel').on('click', function(e) {
			$('#terms').prop('checked', false);
		})
		notiSystem(title, msg, icon, data);

	});
})
