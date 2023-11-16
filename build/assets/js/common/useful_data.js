import { cryptography } from './encrypt_decrypt.js';

const usefulData = function () {
  const assetsTenant = cryptography.decrypt(assetsClient.payload);
  const assets = {};

  $.each(assetsTenant, function (item, value) {
    assets[item] = value;
  });

  delete assetsClient.payload;

  return assets;
};

const assetsTenant = usefulData();
export const response = assetsTenant.response;
export const dataTableLang = assetsTenant.dataTableLang;
export const datePickerLang = assetsTenant.datePickerLang;
export const baseURL = assetsTenant.baseURL;
export const assetUrl = assetsTenant.assetUrl;
export const logged = assetsTenant.logged;
export const userId = assetsTenant.userId;
export const customerUri = assetsTenant.customerUri;
export const customer = assetsTenant.customer;
export const callServer = assetsTenant.callServer;
export const lang = assetsTenant.lang;
export const novoName = assetsTenant.novoName || '';
export const novo = {
  value: assetsTenant.novoValue || '',
};
export const redirect = {
  link: assetsTenant.redirectLink,
};
