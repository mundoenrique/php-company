'use strict';
import { cryptography } from './encrypt_decrypt.js';

$(function () {
	$('form, input[type=text], input[type=password], input[type=email]').attr('autocomplete', 'off');
	getAssetsTenant();
});
export const appLoader = $('#loader').html();

export const toggleDisableActions = function (disable) {
	$('button, select,textarea, input:not([type=hidden])').not('[ignore-el]').prop('disabled', disable);
};

export const getLoader = function () {
	return $('#loader').html();
};

export const takeFormData = function (form) {
	let dataForm = {};
	form.find('input, select, textarea').each(function (index, element) {
		dataForm[$(element).attr('name')] = $(element).val();
	});

	return dataForm;
};

export const getAssetsTenant = function () {
	let assetsTenant = cryptography.decrypt(assetsClient.response);
	// const responseData = {};

	$.each(assetsTenant, function (item, value) {
		window[item] = value;
		// responseData[item] = value;
	});

	// return responseData;
};
