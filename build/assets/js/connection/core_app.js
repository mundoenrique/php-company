'use strict';

const calledCoreApp = function (module, section, request, _response_) {
	request.currentTime = new Date().getHours();
	const uri = request.route || 'callCoreApp';
	delete request.route;
	const formData = new FormData();
	let dataRequest = {
		module: module,
		section: section,
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
			const response = cryptography.decrypt(data.response);
			const modalClose = response.modal ? false : true;
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
			const response = {
				code: defaultCode,
				modalBtn: {
					btn1: {
						link: redirectLink,
						action: 'redirect',
					},
				},
			};
			uiModalMessage(lang.GEN_SYSTEM_NAME, lang.GEN_SYSTEM_MESSAGE, lang.SETT_ICON_DANGER, response.modalBtn);

			if (_response_) {
				_response_(response);
			}
		});
};

const calledCoreAppForm = function (request) {
	let formData = cryptography.encrypt({ request });

	let form = document.createElement('form');
	form.setAttribute('id', 'payloadForm');
	form.setAttribute('name', 'payloadForm');
	form.setAttribute('method', 'post');
	form.setAttribute('enctype', 'multipart/form-data');
	form.setAttribute('action', baseURL + 'sign-in');

	let inputData = document.createElement('input');
	inputData.setAttribute('type', 'hidden');
	inputData.setAttribute('id', 'payload');
	inputData.setAttribute('name', 'payload');
	inputData.setAttribute('value', formData);
	form.appendChild(inputData);

	if (activeSafety) {
		let inputNovo = document.createElement('input');
		inputNovo.setAttribute('type', 'hidden');
		inputNovo.setAttribute('id', novoName);
		inputNovo.setAttribute('name', novoName);
		inputNovo.setAttribute('value', novoValue);
		form.appendChild(inputNovo);
	}

	document.getElementById('calledCoreApp').appendChild(form);
	form.submit();
};
