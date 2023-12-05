import { apiConnection } from './api_connection.js';
import { CryptoJS } from '../third_party/aes-3.1.2.min.js';

export const apiResponse = function (response) {
  let responseData = response;

  if (activeSafety) {
    let cipher = JSON.parse(atob(responseData));
    responseData = JSON.parse(
      CryptoJS.AES.decrypt(cipher.code, cipher.plot, { format: apiConnection }).toString(CryptoJS.enc.Utf8)
    );
  }

  return responseData;
};
