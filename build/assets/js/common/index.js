import { spinerLoader } from '../utils.js';
import { changeLanguage } from './change_language.js';
import { languageTenant } from './language.js';

$(function () {
	languageTenant();

	$('.spiner-loader').on('click', function () {
		spinerLoader(true);
	});

	$('#change-lang').on('click', function () {
		changeLanguage();
	});
});
