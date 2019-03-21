var baseURL = $('body').attr('data-app-base');
var isoPais = $('body').attr('data-country');
var baseCDN = $('body').attr('data-app-base-cdn');
var api = "api/v1/";

function formatterDate(date) {
	var dateArray = date.split('/'),
		dateStr = dateArray[1] + '/' + dateArray[0] + '/' + dateArray[2];

	return new Date(dateStr);
}


function picker(){
	$('input[type=text]').attr('autocomplete','off');
}
picker();
