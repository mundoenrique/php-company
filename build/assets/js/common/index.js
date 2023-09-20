import { spinerLoader } from '../utils.js';
import { languageTenant } from './language.js';

$(function () {
	languageTenant();

	$('.spiner-loader').on('click', function () {
		spinerLoader(true);
	});
});
