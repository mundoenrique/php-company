import { languageTenant } from './language.js';

$(function () {
	$('form, input[type=text], input[type=password], input[type=email]').attr('autocomplete', 'off');

	languageTenant();
});
