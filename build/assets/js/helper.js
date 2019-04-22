'use strict'
var
baseURL = $('body').attr('base-url'),
baseAssets = $('body').attr('asset-url'),
country = $('body').attr('country'),
pais = $('body').attr('pais'),
verb = 'POST',
who, where, data;
//iconos
var iconSuccess = 'ui-icon-circle-check';
var iconInfo = 'ui-icon-info';
var iconWarning = 'ui-icon-alert';
var iconDanger = 'ui-icon-closethick';

//color de fondo
var ClassSuccess;
var ClassinFo;
var ClassWarning;
var ClassDanger;

//posición
var dirLeft;
var dirCenter;
var dirRight;

//fallos del sistema
var generalTitle ;
var generalMsg ;
function callNovoCore (verb, who, where, data, _response_) {
	var title = generalTitle;
	var msg = generalMsg;
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
				$(location).attr('href', response.data)
				break;
			case 500:

				break;
			default:
				_response_(response);
		}

	}).fail(function(xrh, status, response) {
		console.log('fail', response, status, xrh);

	});
}

$('input[type=text]').attr('autocomplete','off');

function formatterDate(date) {
	var
	dateArray = date.split('/'),
	dateStr = dateArray[1] + '/' + dateArray[0] + '/' + dateArray[2];

	return new Date(dateStr);
}

function notiSystem(title, message, type, data) {
	$('#system-info').dialog({
		title: title,
		modal: 'true',
		minHeight: 100,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		open: function(event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#system-type').addClass(type)
			$('#system-msg').html(message);
			$('#accept').text(data.btn1.text)
		}
	});
}
