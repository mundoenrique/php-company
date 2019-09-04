'use strict'

/**
 * lee una propiedad especifica de un elemento html
 * de no indicarse el elemento se toma por defecto el body
 *
 * @param {*} element  elemento al cual quiero extraer su propiedad
 * @param {*} property  propiedad a leer
 * @author pedro torres
 * @date 27/08/2019
 */
function getPropertyOfElement(property, element) {
	var element = element || 'body';
	return $(element).attr(property);
}

//icons
var iconSuccess = 'ui-icon-circle-check';
var iconInfo = 'ui-icon-info';
var iconWarning = 'ui-icon-alert';
var iconDanger = 'ui-icon-closethick';
//app
var baseURL = getPropertyOfElement('base-url');
var baseAssets = getPropertyOfElement('asset-url');
var country = getPropertyOfElement('country');
var pais = getPropertyOfElement('pais');
var isoPais = pais;
var loader = $('#loader').html();
var prefixCountry = country !== 'bp' ? 'Empresas Online ' : '';
var settingsCountry = { bp: 'Conexión Empresas', co: 'Colombia', pe: 'Perú', us: 'Perú', ve: 'Venezuela' };
var strCountry = settingsCountry[country];

var verb, who, where, data, title, msg, icon, data, dataResponse;

$('input[type=text], input[type=password], input[type=textarea]').attr('autocomplete', 'off');

function callNovoCore(verb, who, where, data, _response_) {
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	var dataRequest = JSON.stringify({
		who: who,
		where: where,
		data: data
	});

	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
	$.ajax({
		method: verb,
		url: baseURL + 'async-call',
		data: { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) },
		context: document.body,
		dataType: 'json'
	}).done(function (response) {
		response = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))

		if(response.code === 303){
			notiSystem(response.title, response.msg, response.icon, response.data);
			response.code = 'unanswered';
		}

		if (response.data !== 'finishSession') {
			_response_(response);
		}
	}).fail(function () {
		title = prefixCountry + strCountry;
		msg = 'En estos momentos no podemos atender tu solicitud, por favor intenta en unos minutos';
		icon = iconWarning;
		data = {
			btn1: {
				text: 'Aceptar',
				class: 'novo-btn-primary-modal',
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
	var dateArray = date.split('/');
	var dateStr = dateArray[1] + '/' + dateArray[0] + '/' + dateArray[2];

	return new Date(dateStr);
}

function notiSystem(title, message, icon, data) {
	var btn1 = data.btn1;
	var btn2 = data.btn2;
	if (!btn2) {
		$('#accept').css('margin', '0')
		$('.novo-dialog-buttonset').css('width', '80px')
	}
	var dialogMoldal = $('#system-info');
	dialogMoldal.dialog({
		title: title,
		modal: 'true',
		minHeight: 100,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		open: function (event, ui) {
			$('#cancel').hide();

			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#system-type').addClass(icon);
			$('#system-msg').html(message);

			$('#accept')
				.text(btn1.text)
				.on('click', function (e) {
					dialogMoldal.dialog('close');
					if (btn1.action === 'redirect') {
						$(location).attr('href', btn1.link);
					}
					$(this).off('click');
				});

			if (btn2) {
				$('#cancel')
					.text(btn2.text)
					.on('click', function (e) {
						dialogMoldal.dialog('close');
						if (btn2.action === 'redirect') {
							$(location).attr('href', btn2.link);
						}
						$(this).off('click');
					})
					.show();
			}
		}
	});
}
