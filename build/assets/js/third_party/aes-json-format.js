/**
* AES JSON formatter for CryptoJS
*
* @author BrainFooLong (bfldev.com)
* @link https://github.com/brainfoolong/cryptojs-aes-php
*/

var CryptoJSAesJson = {
	stringify: function (cipherParams) {
		var j = {req: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
		if (cipherParams.iv) j.env = cipherParams.iv.toString();
		if (cipherParams.salt) j.str = cipherParams.salt.toString();
		return encodeURIComponent(btoa(JSON.stringify(j).replace(/\s/g, '')));
	},
	parse: function (jsonStr) {
		var j = JSON.parse(atob(decodeURIComponent(jsonStr)));
		var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.res)});
		if (j.str) cipherParams.iv = CryptoJS.enc.Hex.parse(j.str);
		if (j.env) cipherParams.salt = CryptoJS.enc.Hex.parse(j.env);
		return cipherParams;
	}
}
