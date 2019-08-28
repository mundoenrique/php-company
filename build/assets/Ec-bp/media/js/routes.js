var baseURL = $('body').attr('data-app-base');
var isoPais = $('body').attr('data-country');
var baseCDN = $('body').attr('data-app-base-cdn');
var api = "api/v1/";

function formatterDate(date) {
	var dateArray = date.split('/'),
		dateStr = dateArray[1] + '/' + dateArray[0] + '/' + dateArray[2];

	return new Date(dateStr);
}

$('input[type=text], input[type=password], input[type=textarea]').attr('autocomplete','off');
$("select").change(function(){
	$(this).attr('style','');
	$('#mensajeError').css('display','none');
});
$("input, p").focus(function(){
	$(this).attr('style','');
	$('#mensajeError').css('display','none');
});
function showErrMsg(errMsg) {
	var errElem = $("#mensajeError");
	if (errMsg)
		errElem.html(errMsg);
	errElem.fadeIn("fast");
	errElem.css("float","none");
	errElem.css("display","block");
}
