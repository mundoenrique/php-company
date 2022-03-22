'use strict'
var currenTime;
var screenSize;
var inputModal;
var who;
var where;
var dataResponse;
var ceo_cook;
var btnText;
var form;
var cypherPass;
var data;
var loader = $('#loader').html();
var validatePass = /^[\w!@\*\-\?¡¿+\/.,#ñÑ]+$/;
var searchEnterprise = $('#sb-search');
var inputPass = $('#password');
var dataTableLang;
var validator;
var currentDate;
var btnRemote = $('#btn-auth');
var remoteFunction;
var remoteAuthArgs = {}
var classWidget;
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

	$('#change-lang').on('click', function () {
		who = 'User';
		where = 'changeLanguage';
		data = {
			lang: $(this).find('span.text').text()
		};

		callNovoCore(who, where, data, function (response) {
			if (response.code === 0) {
				var url = $(location).attr('href').split("/");
				var currentCodLan = url[url.length - 1];

				if (currentCodLan == lang.GEN_BEFORE_COD_LANG) {
					var module = url[url.length - 2];
					$(location).attr('href', baseURL + module + '/' + lang.GEN_AFTER_COD_LANG);
				} else {
					location.reload();
				}
			}
		});
	});

	if (code > 2) {
		appMessages(title, msg, icon, modalBtn)
	}

	$('.big-modal').on('click', function () {
		$('.cover-spin').show(0);
	});

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
			"sNext": lang.CONF_TABLE_SNEXT,
			"sPrevious": lang.CONF_TABLE_SPREVIOUS
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

	currentDate = new Date();
	$.datepicker.regional['es'] = {
		changeMonth: lang.CONF_DATEPICKER_CHANGEMONTH,
		changeYear: lang.CONF_DATEPICKER_CHANGEYEAR,
		dateFormat: lang.CONF_DATEPICKER_DATEFORMAT,
		firstDay: lang.CONF_DATEPICKER_FIRSTDATE,
		isRTL: lang.CONF_DATEPICKER_ISRLT,
		maxDate: currentDate,
		minDate: lang.CONF_DATEPICKER_MINDATE,
		showAnim: lang.CONF_DATEPICKER_SHOWANIM,
		showMonthAfterYear: lang.CONF_DATEPICKER_SHOWMONTHAFTERYEAR,
		yearRange: lang.CONF_DATEPICKER_YEARRANGE + currentDate.getFullYear(),
		yearSuffix: lang.CONF_DATEPICKER_YEARSUFFIX,
		closeText: lang.GEN_DATEPICKER_CLOSETEXT,
		currentText: lang.GEN_DATEPICKER_CURRENTTEXT,
		dayNames: lang.GEN_DATEPICKER_DAYNAMES,
		dayNamesMin: lang.GEN_DATEPICKER_DAYNAMESMIN,
		dayNamesShort: lang.GEN_DATEPICKER_DAYNAMESSHORT,
		monthNames: lang.GEN_DATEPICKER_MONTHNAMES,
		monthNamesShort: lang.GEN_DATEPICKER_MONTHNAMESSHORT,
		nextText: lang.GEN_DATEPICKER_NEXTTEXT,
		prevText: lang.GEN_DATEPICKER_PREVTEXT,
		weekHeader: lang.GEN_DATEPICKER_WEEKHEADER,
	}
	$.datepicker.setDefaults($.datepicker.regional['es']);

	$(".widget-menu").click(function (e) {
		e.stopPropagation();
		classWidget = $("#widget-menu").hasClass("none")
		if (classWidget) {
			$('#widget-menu').removeClass('none');
			$("#widget-menu").addClass("show");
		} else {
			removeWidgetMenu()
		}
	});

	$('body,html').click(function (e) {
		removeWidgetMenu()
	});
});

function callNovoCore(who, where, request, _response_) {
	request.screenSize = screen.width;
	var dataRequest = JSON.stringify({
		who: who,
		where: where,
		data: request
	});
	var codeResp = parseInt(lang.CONF_DEFAULT_CODE);
	var formData = new FormData();
	dataRequest = cryptoPass(dataRequest, true);

	if (request.file) {
		formData.append('file', request.file);
		delete request.file;
	}

	formData.append('request', dataRequest);

	if (lang.CONF_CYPHER_DATA == 'ON') {
		formData.append('ceo_name', ceo_cook);
		formData.append('plot', btoa(ceo_cook));
	}

	if (logged) {
		clearTimeout(resetTimesession);
		clearTimeout(setTimesession);
		sessionExpire();
	}

	var uri = data.route || 'async-call'

	$.ajax({
		method: 'POST',
		url: baseURL + uri,
		data: formData,
		context: document.body,
		cache: false,
		contentType: false,
		processData: false,
		dataType: 'json'
	}).done(function (response, status, jqXHR) {

		if (lang.CONF_CYPHER_DATA == 'ON') {
			response = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
		}

		var modalClose = response.modal ? false : true;

		if ($('#system-info').parents('.ui-dialog').length && modalClose) {
			$('#accept')
				.prop('disabled', false)
				.text(lang.GEN_BTN_ACCEPT);
			$('#system-info').dialog('destroy');
		}

		if (response.code === codeResp) {
			appMessages(response.title, response.msg, response.icon, response.modalBtn);
		}

		_response_(response);

	}).fail(function (jqXHR, textStatus, errorThrown) {

		if ($('#system-info').parents('.ui-dialog').length) {
			$('#accept')
				.prop('disabled', false)
				.text(lang.GEN_BTN_ACCEPT)
			$('#system-info').dialog('destroy');
		}

		var response = {
			code: codeResp,
			title: lang.GEN_SYSTEM_NAME,
			msg: lang.GEN_SYSTEM_MESSAGE,
			icon: lang.CONF_ICON_DANGER,
			modalBtn: {
				btn1: {
					text: lang.GEN_BTN_ACCEPT,
					link: lang.CONF_LINK_ENTERPRISES,
					action: 'redirect'
				}
			}
		};
		appMessages(response.title, response.msg, response.icon, response.modalBtn);
		_response_(response);
	});
}

function getCookieValue() {
	return decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
}

function appMessages(title, message, icon, modalBtn) {
	var btn1 = modalBtn.btn1;
	var btn2 = modalBtn.btn2;
	var maxHeight = modalBtn.maxHeight || 350;

	$('#system-info').dialog({
		title: title || lang.GEN_SYSTEM_NAME,
		closeText: '',
		modal: 'true',
		position: { my: modalBtn.posMy || 'center', at: modalBtn.posAt || 'center' },
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		focus: false,
		width: modalBtn.width || lang.CONF_MODAL_WIDTH,
		minWidth: modalBtn.minWidth || lang.CONF_MODAL_WIDTH,
		minHeight: modalBtn.minHeight || 100,
		maxHeight: maxHeight !== 'none' ? maxHeight : false,
		dialogClass: "border-none",
		classes: {
			"ui-dialog-titlebar": "border-none",
		},
		open: function (event, ui) {
			if (!modalBtn.close) {
				$('.ui-dialog-titlebar-close').hide();
			}

			if (icon != '') {
				$('#system-icon').addClass(lang.CONF_ICON + ' ' + icon);
			} else {
				$('#system-icon').removeAttr('class');
			}

			$('#system-msg').html(message);

			if (!btn1) {
				$('#accept').hide();
			} else {
				createButton($('#accept'), btn1);
			}

			if (!btn2) {
				$('#cancel').hide();
			} else {
				createButton($('#cancel'), btn2);
			}
		}
	});

	$('.ui-dialog-titlebar-close').on('click', function (e) {
		$('#system-msg').removeClass('w-100 vh-100');
		$('#system-msg').html('');
		$('#system-info').dialog('destroy');
	});
}

function createButton(elementButton, valuesButton) {
	elementButton.text(valuesButton.text);
	elementButton.show();
	elementButton.on('click', function (e) {
		switch (valuesButton.action) {
			case 'redirect':
				$(this)
					.html(loader)
					.prop('disabled', true);
				$(this).children('span').addClass('spinner-border-sm');
				if ($(this).attr('id') == 'cancel') {
					$(this).children('span')
						.removeClass('secondary')
						.addClass('primary');
				}
				$(location).attr('href', baseURL + valuesButton.link);
				break;
			case 'close':
			case 'destroy':
				$('#system-info').dialog('destroy');
				break;
		}

		$(this).off('click');
	})
}

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
		form.append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
		form.append('<input type="hidden" name="screenSize" value="' + screenSize + '">');
	}
}

function getPropertyOfElement(property, element) {
	var element = element || 'body';
	return $(element).attr(property);
}

function formInputTrim(form) {
	form.find('input:not([type=file]), select, textarea').each(function () {
		var thisValInput = $(this).val();

		if (thisValInput == null) {
			return;
		}

		var trimVal = thisValInput.trim();
		$(this).val(trimVal);
	});
}

function cryptoPass(jsonObject, req) {
	req = req == undefined ? false : req;
	ceo_cook = getCookieValue();
	var cipherObject = jsonObject;

	if (lang.CONF_CYPHER_DATA == 'ON') {
		cipherObject = CryptoJS.AES.encrypt(jsonObject, ceo_cook, { format: CryptoJSAesJson }).toString();

		if (!req) {
			cipherObject = btoa(JSON.stringify({
				password: cipherObject,
				plot: btoa(ceo_cook)
			}));
		}
	}


	return cipherObject;
}

function getDataForm(form) {
	var dataForm = {};
	form.find('input, select, textarea').each(function (index, element) {
		dataForm[$(element).attr('id')] = $(element).val();
	});

	return dataForm
}

function downLoadfiles(data) {
	var File = new Int8Array(data.file);
	var blob = new Blob([File], { type: "application/" + data.ext });

	if (window.navigator.msSaveOrOpenBlob) {
		window.navigator.msSaveBlob(blob, data.name);
	} else {
		var url = window.URL.createObjectURL(blob);
		$('#download-file').attr('href', url);
		$('#download-file').attr('download', data.name);
		document.getElementById('download-file').click();
		window.URL.revokeObjectURL(url);
		$('#download-file').attr('href', lang.CONF_NO_LINK);
		$('#download-file').attr('download', '');
	}

	$('.cover-spin').hide();
}

function getauhtKey() {
	// NO BORRAR DEJAR PARA PRUEBAS INTERNAS
	 /*if (lang.CONF_REMOTE_AUTH == 'ON') {
		$('#accept').addClass('sender');
		$('#system-info').on('click', '.sender', function () {
			$('#accept')
				.prop('disabled', false)
				.removeClass('sender');
			getResponse('true', 'EXITOSO')
		});
	}*/
	//HASTA AQUI
	$('#accept').removeClass('get-auth-key');
	$('#accept').removeClass('send-request');
	data = {
		action: remoteAuthArgs.title || remoteAuthArgs.action
	}
	btnRemote
		.html(loader)
		.prop('disabled', true);
	insertFormInput(true);
	who = 'Services';
	where = 'AuthorizationKey';

	callNovoCore(who, where, data, function (response) {
		$('.cover-spin').hide();
		if (response.code == 0) {
			data = {
			// NO BORRAR DEJAR PARA PRUEBAS INTERNAS
				/* btn1: {
					text: lang.GEN_BTN_ACCEPT,
					action: 'none'
				},*/
				// HASTA AQUI
				minHeight: 650,
				width: 1000,
				posMy: 'top',
				posAt: 'top',
				close: true
			}
			$('#system-msg').addClass('w-100 vh-100');
			appMessages(remoteAuthArgs.title || remoteAuthArgs.action, '', '', data);
			AutorizacionCanales(response.data.authKey, 'system-msg', response.data.urlApp, response.data.urlLoad, 'getResponse');
		}

		insertFormInput(false);
		btnRemote
			.prop('disabled', false)
			.html(btnText);
	});
}

function getResponse(Exitoso, MensajeError) {
	$('#system-info').dialog('destroy');
	$('#system-msg').removeClass('w-100 vh-100');
	Exitoso = (Exitoso === 'true' || Exitoso === true);

	if (Exitoso) {
		switch (remoteFunction) {
			case 'sendRequest':
				sendRequest(remoteAuthArgs.action, remoteAuthArgs.title, btnRemote, remoteAuthArgs.selectBlockCard);
				break;
			case 'SignDeleteBulk':
				SignDeleteBulk(remoteAuthArgs.form, remoteAuthArgs.action, remoteAuthArgs.thisId, remoteAuthArgs.passwordSignAuht, remoteAuthArgs.modalReq);
				break;
			case 'updateLimits':
				updateLimits();
				break;
			case 'updateTwirlsCard':
				updateTwirlsCard();
				break;
			case 'applyActions':
				applyActions(remoteAuthArgs.action, remoteAuthArgs.form, btnRemote);
				break;
		}

		$('.cover-spin').show(0);
	} else {
		data = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'destroy'
			},
		}

		appMessages(remoteAuthArgs.title || remoteAuthArgs.action, MensajeError, lang.CONF_ICON_WARNING, data);
	}
}

function normalizeAmount(amount) {
	var valueAttr = amount.split(lang.CONF_DECIMAL);
	amount = valueAttr[0].replace(/[,.]/g, '') + '.' + valueAttr[1];
	amount = parseFloat(amount);

	return amount;
}

function removeWidgetMenu () {
	$('#widget-menu').removeClass('show');
	setTimeout(function(){
		$("#widget-menu").addClass("none");
	}, 1000);
}
