'use strict'
var currentDate
$(function() {
	currentDate = new Date();

  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
		showMonthAfterYear: false,
		yearRange: '-20:' + currentDate.getFullYear(),
		maxDate: currentDate,
		changeMonth: true,
    changeYear: true,
    showAnim: "slideDown",
    yearSuffix: ''
  };
	$.datepicker.setDefaults($.datepicker.regional['es']);

	sessionExpire();
});

function sessionExpire() {
	if(sessionTime > 0) {
		setTimeout(function() {
			finishSession()
		}, (sessionTime - callModal));
	}
}

function finishSession() {
	$('#accept').addClass('btn-large-xl')
	data = {
		btn1: {
			text: 'Mantener sesión',
			action: 'close'
		}
	}
	notiSystem(lang.GEN_SYSTEM_NAME, 'No se ha detectado actividad en la página, tu sesión está próxima a expirar.', lang.GEN_ICON_INFO, data);

	var oldID = $('#accept').attr('id');

	$('#accept').attr('id', 'keep-session');
	var setTimesession = setTimeout(function() {
		$(location).attr('href', baseURL+'cerrar-sesion/fin');
	}, callServer);

	$('#keep-session').on('click', function() {
		$(this)
		.off('click')
		.attr('id', oldID);
		verb = 'POST'; who= 'User'; where = 'KeepSession';
		data = {
			modalReq: true,
		}
		callNovoCore(verb, who, where, data, function(response) {
			$('#accept').removeClass('btn-large-xl')
			clearTimeout(setTimesession);
			sessionExpire();
		})


	});
}
