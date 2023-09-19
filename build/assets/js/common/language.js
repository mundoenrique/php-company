import { cryptography } from './encrypt_decrypt.js';

export const languageTenant = function () {
	let assetsTenant = cryptography.decrypt(assetsClient.response);

	$.each(assetsTenant, function (item, value) {
		window[item] = value;
	});

	delete assetsClient.response;
};
