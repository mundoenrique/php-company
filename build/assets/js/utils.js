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

const getLoader = function () {
	return $('#loader').html();
};

const takeFormData = function (form) {
	let dataForm = {};
	form.find('input, select, textarea').each(function (index, element) {
		dataForm[$(element).attr('name')] = $(element).val();
	});

	return dataForm;
};
