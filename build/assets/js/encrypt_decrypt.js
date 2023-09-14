'use strict';
$(function () {
	cryptography = {
		encrypt: function (request) {
			var requestDAta = typeof request === 'string' ? request : JSON.stringify(request);
			cpo_cook = getCookieValue();

			if (traslate) {
				var cipher = CryptoJS.AES.encrypt(requestDAta, cpo_cook, { format: CryptoJSAesJson }).toString();
				requestDAta = btoa(
					JSON.stringify({
						data: cipher,
						plot: btoa(cpo_cook),
					})
				);
			}

			return requestDAta;
		},

		decrypt: function (objec) {
			var decryptData = objec;

			if (traslate) {
				var cipher = JSON.parse(atob(decryptData));
				decryptData = JSON.parse(
					CryptoJS.AES.decrypt(cipher.code, cipher.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
				);
			}

			return decryptData;
		},
	};
});
