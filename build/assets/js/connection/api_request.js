import { apiConnection } from './api_connection.js';
import { novo } from '../common/useful_data.js';
import { CryptoJS } from '../third_party/aes-3.1.2.min.js';

export const apiRequest = function (request) {
  let requestData = typeof request === 'string' ? request : JSON.stringify(request);

  if (activeSafety) {
    let cipher = CryptoJS.AES.encrypt(requestData, novo.value, { format: apiConnection }).toString();
    requestData = btoa(
      JSON.stringify({
        data: cipher,
        plot: btoa(novo.value),
      })
    );
  }

  return requestData;
};
