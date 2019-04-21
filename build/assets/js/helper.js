'use strict'
var baseURL = $('body').attr('base-url'),
baseAssets = $('body').attr('asset-url'),
country = $('body').attr('country'),
pais = $('body').attr('pais'),
verb = 'POST',
who, where, data;

console.log(baseURL, baseAssets, country)
//iconos
var iconSuccess;
var iconInfo;
var iconWarning;
var iconDanger;

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
	console.log('Model: ', who, 'Method: ', where, 'Request: ', data);
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
		console.log('done', response, status);
		switch(response.code) {
			case 302:

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
