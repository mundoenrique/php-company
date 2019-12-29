'use strict'
//app
var currenTime;
var screenSize;
var verb, who, where, dataResponse, ceo_cook, btnText, form, cypherPass;
var loader = $('#loader').html();
var validatePass = /^[\w!@\*\-\?¡¿+\/.,#]+$/;
var searchEnterprise = $('#sb-search');
var inputPass = $('#password');

$(function () {
	$('input[type=text], input[type=password], input[type=email]').attr('autocomplete', 'off');
	/**
 * @info Maneja llamado del modal para petiociones submit
 * @author J. Enrique Peñaloza Piñero
 * @date November 22nd, 2019
 */
	if(code > 2) {
		notiSystem(title, msg, icon, data)
	}

	$('.big-modal').on('click', function(){
		$('.cover-spin').show(0)
	});
});
/**
 * @info Llama al core del servidor
 * @author J. Enrique Peñaloza Piñero
 * @date 15/04/2019
 */
function callNovoCore(verb, who, where, request, _response_) {
	ceo_cook = getCookieValue();
	request.currenTime = new Date();
	request.screenSize = screen.width;
	var dataRequest = JSON.stringify({
		who: who,
		where: where,
		data: request
	});
	var codeResp = parseInt(lang.RESP_DEFAULT_CODE);
	var formData = new FormData();
	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
	if(request.file) {
		formData.append('file', request.file);
		delete request.file;
	}
	formData.append('request', dataRequest);
	formData.append('ceo_name', ceo_cook);
	formData.append('plot', btoa(ceo_cook));

	$.ajax({
		method: verb,
		url: baseURL + 'async-call',
		data: formData,
		context: document.body,
		cache: false,
		contentType: false,
		processData: false,
		dataType: 'json'
	}).done(function (response, status, jqXHR) {

		if(request.modalReq) {
			$('#accept').prop('disabled', false)
			$('#system-info').dialog('destroy');
		}

		response = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))

		if(response.code === codeResp) {
			notiSystem(response.title, response.msg, response.icon, response.data);
		}

		_response_(response);

	}).fail(function (jqXHR, textStatus, errorThrown ) {

		if(request.modalReq) {
			$('#accept').prop('disabled', false)
			$('#system-info').dialog('destroy');
		}

		var response = {
			code: codeResp,
			title: lang.GEN_SYSTEM_NAME,
			icon: lang.GEN_ICON_DANGER,
			data: {
				btn1: {
					link: baseURL+lang.GEN_ENTERPRISE_LIST,
					action: 'redirect'
				}
			}
		};
		notiSystem(response.title, response.msg, response.icon, response.data);
		_response_(response);
	});
}
/**
 * @info Obtiene valor de cookie
 * @author J. Enrique Peñaloza Piñero
 * @date December 18th, 2019
 */
function getCookieValue() {
	return decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
}
/**
 * @info Uso del modal informativo
 * @author J. Enrique Peñaloza Piñero
 * @date 05/03/2019
 */
function notiSystem(title, message, icon, data) {
	var btnAccept = $('#accept');
	var btnCancel = $('#cancel');
	var dialogMoldal = $('#system-info');
	var btn1 = data.btn1;
	var btn2 = data.btn2;

	dialogMoldal.dialog({
		title: title || lang.GEN_SYSTEM_NAME,
		modal: 'true',
		minHeight: 100,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		minWidth: lang.GEN_MODAL_WIDTH,
		maxHeight: 350,
		dialogClass: "border-none",
    classes: {
      "ui-dialog-titlebar": "border-none",
    },
		open: function (event, ui) {
			$('.ui-dialog-titlebar-close').hide();
			$('#system-icon').addClass(icon);
			$('#system-msg').html(message);
			$('#accept, #cancel').removeClass("ui-button ui-corner-all ui-widget");

			createButton(dialogMoldal, btnAccept, btn1);
			if(!btn2) {
				btnCancel.hide();
				btnAccept.addClass('modal-btn-primary');
				$('.novo-dialog-buttonset').addClass('modal-buttonset');
			} else {
				createButton(dialogMoldal, btnCancel, btn2);
			}
		}
	});
}
/**
 * @info Crea botones para modal informativo
 * @author Pedro Torres
 * @date 16/09/2019
 */
function createButton(dialogMoldal, elementButton, valuesButton) {
	valuesButton.text && elementButton.text(valuesButton.text);
	elementButton.show();
	elementButton.on('click', function (e) {
		if (valuesButton.action === 'redirect') {
			$(this).html(loader);
			$(this).children('span').addClass('spinner-border-sm');
			if($(this).attr('id') == 'cancel') {
				$(this).children('span')
				.removeClass('secondary')
				.addClass('primary');
			}
			$(location).attr('href', valuesButton.link);
		}
		if (valuesButton.action !== 'redirect') {
			dialogMoldal.dialog('close');
		}
		$(this).off('click');
	});
}
/**
 * @info Incorpora inputs a formularios
 * @author J. Enrique Peñaloza
 * @date November 18th, 2019
 */
function insertFormInput(disabled, form = false,) {
	var notDisabled = '#product-select, #enterprise-widget-btn'

	if(disabled) {
		notDisabled = false;
	}

	$('form button, form select, form input[type=file], .flex button')
	.not(notDisabled)
	.prop('disabled', disabled);

	if(form) {
		ceo_cook = getCookieValue();
		currenTime = new Date();
		screenSize = screen.width;
		form.append(`<input type="hidden" name="ceo_name" value="${ceo_cook}"></input>`);
		form.append(`<input type="hidden" name="currenTime" value="${currenTime}"></input>`);
		form.append(`<input type="hidden" name="screenSize" value="${screenSize}"></input>`);
	}
}
/**
 * @info lee una propiedad especifica de un elemento html,
 * de no indicarse el elemento se toma por defecto el body
 * @author Pedro Torres
 * @date 27/08/2019
 */
function getPropertyOfElement(property, element) {
	var element = element || 'body';
	return $(element).attr(property);
}
/**
 * @info quita espacios en blanco de los campos input
 * @author J. Enrique Peñaloza Piñero
 * @date November 18th, 2019
 */
function formInputTrim(form) {
	form.find('input').each(function() {
		var trimVal = $(this).val().trim()
		$(this).val(trimVal)
	});
}
/**
 * @info Cifra la contraseña del usuario
 * @author J. Enrique Peñaloza Piñero
 * @date December 27th, 2019
 */
function crytoPass(password) {
	ceo_cook = getCookieValue();
	cypherPass = CryptoJS.AES.encrypt(password, ceo_cook, { format: CryptoJSAesJson }).toString();
	password = btoa(JSON.stringify({
		password: cypherPass,
		plot: btoa(ceo_cook)
	}));

	return password;
}
