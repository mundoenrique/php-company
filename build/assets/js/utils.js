'use strict';
$(function () {
	$('form, input[type=text], input[type=password], input[type=email]').attr('autocomplete', 'off');
	let assetsTenant = cryptography.decrypt(assetsClient.response);

	$.each(assetsTenant, function (item, value) {
		window[item] = value;
	});

	delete assetsClient.response;
	loader = $('#loader').html();
});

const toggleDisableActions = function (disable) {
	$('button, select,textarea, input:not([type=hidden])').not('[ignore-el]').prop('disabled', disable);
};

const takeFormData = function (form) {
	let dataForm = {};
	form.find('input, select, textarea').each(function (index, element) {
		dataForm[$(element).attr('name')] = $(element).val();
	});

	return dataForm;
};

const uiMdalClose = function (close) {
	if ($('#system-info').parents('.ui-dialog').length && close) {
		$('#system-info').dialog('destroy');
		$('#accept')
			.prop('disabled', false)
			.html(lang.GEN_BTN_ACCEPT)
			.removeClass()
			.addClass(lang.SETT_MODAL_BTN_CLASS['accept'])
			.off('click');
		$('#cancel')
			.prop('disabled', false)
			.removeClass()
			.addClass(lang.SETT_MODAL_BTN_CLASS['cancel'])
			.html(lang.GEN_BTN_CANCEL)
			.off('click');
	}
};

const calledCoreApp = function (who, where, request, _response_) {
	request.currentTime = new Date().getHours();
	const formData = new FormData();
	let dataRequest = {
		who: who,
		where: where,
		data: request,
	};

	dataRequest = cryptography.encrypt(dataRequest);
	formData.append('payload', dataRequest);

	if (activeSafety) {
		formData.append(novoName, novoValue);
	}

	if (request.files) {
		request.files.forEach(function (element) {
			formData.append(element.name, element.file);
		});

		delete request.files;
	}

	if (logged || userId) {
		sessionControl();
	}

	const uri = data.route || 'novo-async-call';

	$.ajax({
		method: 'POST',
		url: baseURL + uri,
		data: formData,
		context: document.body,
		cache: false,
		contentType: false,
		processData: false,
		dataType: 'json',
	})
		.done(function (data, status, jqXHR) {
			response = cryptography.decrypt(data.response);

			var modalClose = response.modal ? false : true;
			uiMdalClose(modalClose);

			if (response.code === defaultCode) {
				appMessages(response.title, response.msg, response.icon, response.modalBtn);
			}

			if (_response_) {
				_response_(response);
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			uiMdalClose(true);

			var response = {
				code: defaultCode,
				modalBtn: {
					btn1: {
						link: redirectLink,
						action: 'redirect',
					},
				},
			};

			appMessages(lang.GEN_SYSTEM_NAME, lang.GEN_SYSTEM_MESSAGE, lang.SETT_ICON_DANGER, response.modalBtn);

			if (_response_) {
				_response_(response);
			}
		});
};
