/**
 * AES JSON formatter for CryptoJS
 *
 * @author BrainFooLong (bfldev.com)
 * @link https://github.com/brainfoolong/cryptojs-aes-php
 */

import { CryptoJS } from '../third_party/aes-3.1.2.min.js';

export const apiConnection = {
  stringify: function (cipherParams) {
    let j = { req: cipherParams.ciphertext.toString(CryptoJS.enc.Base64) };
    if (cipherParams.iv) j.env = cipherParams.iv.toString();
    if (cipherParams.salt) j.str = cipherParams.salt.toString();

    return encodeURIComponent(btoa(JSON.stringify(j).replace(/\s/g, '')));
  },

  parse: function (jsonStr) {
    let j = JSON.parse(atob(decodeURIComponent(jsonStr)));
    let cipherParams = CryptoJS.lib.CipherParams.create({ ciphertext: CryptoJS.enc.Base64.parse(j.res) });
    if (j.str) cipherParams.iv = CryptoJS.enc.Hex.parse(j.str);
    if (j.env) cipherParams.salt = CryptoJS.enc.Hex.parse(j.env);

    return cipherParams;
  },
};
