var baseURL = $('body').attr('data-app-base');
var isoPais = $('body').attr('data-country');
var baseCDN = $('body').attr('data-app-base-cdn');
var api = "api/v1/";


//Descarga de reportes
function downReports(way, modelo, data, file) {
	var dataRequest = JSON.stringify(data);
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);

	$.ajax({
		url: baseURL + isoPais + '/trayectos/modelo',
		type: 'POST',
		data: { way: way, modelo: modelo, data: dataRequest, 	ceo_name: ceo_cook },
		datatype: 'json',
	}).done(function (response) {
		var code = response.code, title = response.title, msg = response.msg;
		switch (code) {
			case 0:
				dataRequest = way + ',' + file;
				$('form#formulario').empty();
				$('form#formulario').attr('action', baseURL + isoPais + '/trayectos/modelo');
				$('form#formulario').append('<input type="hidden" name="way" value="downloadFile" />');
				$('form#formulario').append('<input type="hidden" name="modelo" value="' + modelo + '" />');
				$('form#formulario').append('<input type="hidden" name="data" value="' + dataRequest + '" />');
				$('form#formulario').submit();
				break;
			default:
				downloadFail(title, msg);

		}

	}).fail(function (error) {
		console.log(error);
	});
}

function downloadFail(title, msg) {
	$('#msg-info').empty();
	$('#msg-system').dialog({
		title: title,
		modal: 'true',
		width: '210px',
		draggable: false,
		rezise: false,
		open: function (event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#close-info').text(lang.TAG_ACCEPT);
			$('#msg-info').append('<p>' + msg + '</p>');
		}
	});
	$('#close-info').on('click', function (e) {
		e.preventDefault();
		$('#msg-info').empty();
		$('#msg-system').dialog('close');
	});
}
