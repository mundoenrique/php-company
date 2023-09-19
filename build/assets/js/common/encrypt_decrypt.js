export const cryptography = {
	encrypt: function (request) {
		let requestData = typeof request === 'string' ? request : JSON.stringify(request);

		if (activeSafety) {
			let cipher = CryptoJS.AES.encrypt(requestData, novoValue, { format: CryptoJSAesJson }).toString();
			requestData = btoa(
				JSON.stringify({
					data: cipher,
					plot: btoa(novoValue),
				})
			);
		}

		return requestData;
	},
	decrypt: function (objec) {
		let decryptData = objec;
		if (activeSafety) {
			let cipher = JSON.parse(atob(decryptData));
			decryptData = JSON.parse(
				CryptoJS.AES.decrypt(cipher.code, cipher.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
			);
		}
		return decryptData;
	},
};
