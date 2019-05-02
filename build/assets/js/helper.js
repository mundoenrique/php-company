'use strict'
//icons
var iconSuccess = 'ui-icon-circle-check';
var iconInfo = 'ui-icon-info';
var iconWarning = 'ui-icon-alert';
var iconDanger = 'ui-icon-closethick';
//app
var baseURL = $('body').attr('base-url');
var baseAssets = $('body').attr('asset-url');
var country = $('body').attr('country');
var pais = $('body').attr('pais');
var loader = $('#loader').html();
var verb;
var who;
var where;
var data;
var title;
var msg;
var icon;
var data;
var dataResponse;
var strCountry;
var prefixCountry = 'Empresas Online ';
switch(country) {
	case 'bp':
		strCountry = 'Conexión Empresas';
		prefixCountry = '';
		break;
	case 'co':
		strCountry = 'Colombia';
		break;
	case 'pe':
	case 'us':
		strCountry = 'Perú';
		break;
	case 've':
		strCountry = 'Venezuela';
		break;
}

$('input[type=text]').attr('autocomplete','off');

function callNovoCore (verb, who, where, data, _response_) {
	console.log('Model:', who, 'Method:', where, 'Request:', data);
	var dataRequest = JSON.stringify({
		who: who,
		where: where,
		data: data
	});
	$.ajax({
		method: verb,
		url: baseURL + 'async-call',
		data: {request: btoa(dataRequest)},
		context: document.body,
		dataType: 'json',
	}).done(function(response, status) {
		switch(response.code) {
			case 303:
				notiSystem(response.title, response.msg, response.icon, response.data);
				response.code = 'unanswered';
				_response_(response);
				break;
			default:
				if(response.data !== 'finishSession') {
					_response_(response);
				}
		}

	}).fail(function(xrh, status, response) {
		console.log('fail', response, status, xrh);
		title = prefixCountry + strCountry;
		msg = 'En estos momentos no podemos atender su solicitud, por favor intente en unos minutos';
		icon = iconWarning;
		data = {
			btn1: {
				text: 'Aceptar',
				link: false,
				action: 'close'
			}
		};
		notiSystem(title, msg, icon, data);
		var resp = {
			code: 'unanswered'
		}
		_response_(resp);
	});
}

function formatterDate(date) {
	var	dateArray = date.split('/');
	var dateStr = dateArray[1] + '/' + dateArray[0] + '/' + dateArray[2];

	return new Date(dateStr);
}

function notiSystem(title, message, icon, data) {
	var btn1 = data.btn1;
	var btn2 = data.btn2;
	var dialogMoldal = $('#system-info');
	dialogMoldal.dialog({
		title: title,
		modal: 'true',
		minHeight: 100,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		open: function(event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#system-type').addClass(icon);
			$('#system-msg').html(message);
			$('#accept')
			.text(btn1.text)
			.on('click', function(e) {
				dialogMoldal.dialog('close');
				if(btn1.action === 'redirect') {
					$(location).attr('href', btn1.link);
				}
				$(this).off('click');
			});
			$('#cancel').hide();
			if (btn2) {
				$('#cancel')
				.text(btn2.text)
				.on('click', function(e) {
					dialogMoldal.dialog('close');
					if(btn2.action === 'redirect') {
						$(location).attr('href', btn2.link);
					}
					$(this).off('click');
				})
				.show();
			}
		}
	});
}

$('#signup').on('click', function() {
	verb = 'POST'; who = 'User'; where = 'finishSession'; data = '';
		callNovoCore (verb, who, where, data);
});
