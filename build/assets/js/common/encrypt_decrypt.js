import { CryptoJS } from '../third_party/aes-3.1.2.min.js';
import { CryptoJSAesJson } from './common.js';

export const cryptography = {
  encrypt: function (request, novoValue) {
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
  decrypt: function (response) {
    let decryptData = response;

    if (activeSafety) {
      let cipher = JSON.parse(atob(decryptData));
      decryptData = JSON.parse(
        CryptoJS.AES.decrypt(cipher.code, cipher.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
      );
    }

    return decryptData;
  },
};
