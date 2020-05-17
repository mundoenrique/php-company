'use strict'
//app
var currenTime;
var screenSize;
var verb, who, where, dataResponse, ceo_cook, btnText, form, cypherPass;
var loader = $('#loader').html();
var validatePass = /^[\w!@\*\-\?¡¿+\/.,#]+$/;
var searchEnterprise = $('#sb-search');
var inputPass = $('#password');
var dataTableLang;
var validator;

$(function () {
	$('input[type=text], input[type=password], input[type=email]').attr('autocomplete', 'off');

	$('body').on('click', '.pwd-action', function () {
		var pwdInput = $(this).closest('div.input-group').find('.pwd-input')
		var inputType = pwdInput.attr('type');

		if (pwdInput.val() != '') {
			if (inputType === 'password') {
				pwdInput.attr('type', 'text');
				$(this).attr('title', lang.GEN_HIDE_PASS);
			} else {
				pwdInput.attr('type', 'password');
				$(this).attr('title', lang.GEN_SHOW_PASS);
			}
		}
	});

	if (code > 2) {
		notiSystem(title, msg, icon, data)
	}

	$('.big-modal').on('click', function () {
		$('.cover-spin').show(0)
	});
	//dataTale lang
	dataTableLang = {
		"sLengthMenu": lang.GEN_TABLE_SLENGTHMENU,
		"sZeroRecords": lang.GEN_TABLE_SZERORECORDS,
		"sEmptyTable": lang.GEN_TABLE_SEMPTYTABLE,
		"sInfo": lang.GEN_TABLE_SINFO,
		"sInfoEmpty": lang.GEN_TABLE_SINFOEMPTY,
		"sInfoFiltered": lang.GEN_TABLE_SINFOFILTERED,
		"sInfoPostFix": lang.GEN_TABLE_SINFOPOSTFIX,
		"slengthMenu": lang.GEN_TABLE_SLENGTHMENU,
		"sSearch": lang.GEN_TABLE_SSEARCH,
		"sSearchPlaceholder": lang.GEN_TABLE_SSEARCHPLACEHOLDER,
		"sUrl": lang.GEN_TABLE_SSEARCH,
		"sInfoThousands": lang.GEN_TABLE_SINFOTHOUSANDS,
		"sProcessing": lang.GEN_TABLE_SPROCESSING,
		"sloadingrecords": lang.SLOADINGRECORDS,
		"oPaginate": {
			"sFirst": lang.GEN_TABLE_SFIRST,
			"sLast": lang.GEN_TABLE_SLAST,
			"sNext": lang.GEN_TABLE_SNEXT,
			"sPrevious": lang.GEN_TABLE_SPREVIOUS
		},
		"oAria": {
			"sSortAscending": lang.GEN_TABLE_SSORTASCENDING,
			"sSortDescending": lang.GEN_TABLE_SSORTDESCENDING
		},
		"select": {
			"rows": {
				_: lang.GEN_TABLE_ROWS_SELECTED,
				0: lang.GEN_TABLE_ROWS_NO_SELECTED,
				1: lang.GEN_TABLE_ROW_SELECTED
			}
		}
	}
});
/**
 * @info Llama al core del servidor
 * @author J. Enrique Peñaloza Piñero
 * @date 15/04/2019
 */
function callNovoCore(verb, who, where, request, _response_) {
	request.screenSize = screen.width;
	var dataRequest = JSON.stringify({
		who: who,
		where: where,
		data: request
	});
	var codeResp = parseInt(lang.RESP_DEFAULT_CODE);
	var formData = new FormData();

	dataRequest = cryptoPass(dataRequest, true);

	if (request.file) {
		formData.append('file', request.file);
		delete request.file;
	}
	formData.append('request', dataRequest);
	formData.append('ceo_name', ceo_cook);
	formData.append('plot', btoa(ceo_cook));

	if (logged) {
		clearTimeout(resetTimesession);
		clearTimeout(setTimesession);
		sessionExpire();
	}

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

		if (request.modalReq) {
			$('#accept').prop('disabled', false)
			$('#system-info').dialog('destroy');
		}

		response = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))

		if (response.code === codeResp) {
			notiSystem(response.title, response.msg, response.icon, response.data);
		}

		_response_(response);

	}).fail(function (jqXHR, textStatus, errorThrown) {

		if (request.modalReq) {
			$('#accept').prop('disabled', false)
			$('#system-info').dialog('destroy');
		}

		var response = {
			code: codeResp,
			title: lang.GEN_SYSTEM_NAME,
			icon: lang.GEN_ICON_DANGER,
			data: {
				btn1: {
					link: lang.GEN_ENTERPRISE_LIST,
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
		minWidth: lang.CONF_MODAL_WIDTH,
		maxHeight: 350,
		dialogClass: "border-none",
		classes: {
			"ui-dialog-titlebar": "border-none",
		},
		open: function (event, ui) {
			$('.ui-dialog-titlebar-close').hide();
			var classIcon = $('#system-icon').attr('class').split(' ');
			classIcon = classIcon.pop();

			if (classIcon != 'mt-0') {
				$('#system-icon').removeClass(classIcon);
			}

			$('#system-icon').addClass(icon);
			$('#system-msg').html(message);
			$('#accept, #cancel').removeClass("ui-button ui-corner-all ui-widget");
			createButton(dialogMoldal, btnAccept, btn1);

			if (!btn2) {
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
			if ($(this).attr('id') == 'cancel') {
				$(this).children('span')
					.removeClass('secondary')
					.addClass('primary');
			}
			$(location).attr('href', baseURL+valuesButton.link);
		}
		if (valuesButton.action === 'close') {
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
function insertFormInput(disabled, form) {
	form = form == undefined ? false : form;
	var notDisabled = '#product-select, #enterprise-widget-btn'

	if (disabled) {
		notDisabled = false;
	}

	$('form button, form select, form input:not([type=hidden]), button')
		.not(notDisabled)
		.not('.btn-modal')
		.prop('disabled', disabled);

	if (form) {
		ceo_cook = getCookieValue();
		screenSize = screen.width;
		form.append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '"></input>');
		form.append('<input type="hidden" name="screenSize" value="' + screenSize + '"></input>');
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
	form.find('input, select').each(function () {
		var thisValInput = $(this).val();
		if(thisValInput == null) {
			return;
		}
		var trimVal = thisValInput.trim()
		$(this).val(trimVal)
	});
}
/**
 * @info Cifra la contraseña del usuario
 * @author J. Enrique Peñaloza Piñero
 * @date December 27th, 2019
 */
function cryptoPass(jsonObject, req) {
	req = req == undefined ? false : req;
	ceo_cook = getCookieValue();
	var cipherObject = CryptoJS.AES.encrypt(jsonObject, ceo_cook, { format: CryptoJSAesJson }).toString();

	if(!req) {
		cipherObject = btoa(JSON.stringify({
			password: cipherObject,
			plot: btoa(ceo_cook)
		}));
	}

	return cipherObject;
}
/**
 * @info Obtiene datos para el request
 * @author J. Enrique Peñaloza Piñero
 * @date April 25th, 2020
 */
function getDataForm(form) {
	var dataForm = {};
	form.find('input, select').each(function (index, element) {
		dataForm[$(element).attr('id')] = $(element).val().trim()
	})

	return dataForm
}
