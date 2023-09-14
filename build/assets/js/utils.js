'use strict';
$(function () {
	$('input[type=text], input[type=password], input[type=email]').attr('autocomplete', 'off');
	traslate = $('#traslate').val() === '1' ? true : false;
	assetsClient = cryptography.decrypt(assetsClient.response);

	$.each(assetsClient, function (item, value) {
		window[item] = value;
	});

	loader = $('#loader').html();
});
