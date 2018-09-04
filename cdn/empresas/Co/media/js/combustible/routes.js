var path = window.location.href.split( '/' );
var baseURL = path[0]+ "//" +path[2]+'/'+path[3];
var isoPais = path[4];
var cdn = path[2].replace('online', 'cdn');
var baseCDN = path[0]+ "//" +cdn+'/'+path[3]+'/'+path[4];
var api = "/api/v1/";

//Descarga de reportes
function downReports(way, modelo, data, file)
{
	var dataRequest = JSON.stringify(data);
	$.ajax({
		url: baseURL + '/' + isoPais + '/trayectos/modelo',
		type: 'POST',
		data: { way: way, modelo: modelo, data: dataRequest },
		datatype: 'json',
	}).done(function (response) {
		var code = response.code, title = response.title, msg = response.msg;
		switch (code) {
			case 0:
				dataRequest = way + ',' + file;
				$('form#formulario').empty();
				$('form#formulario').attr('action', baseURL + '/' + isoPais + '/trayectos/modelo');
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
