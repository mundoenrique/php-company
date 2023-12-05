import { apiResponse } from '../connection/api_response.js';

const usefulData = (function () {
  const assetsTenant = apiResponse(assetsClient.payload);
  const assets = {};

  $.each(assetsTenant, function (item, value) {
    assets[item] = value;
  });

  delete assetsClient.payload;

  return assets;
})();

export const response = usefulData.response;
export const dataTableLang = usefulData.dataTableLang;
export const datePickerLang = usefulData.datePickerLang;
export const baseURL = usefulData.baseURL;
export const assetUrl = usefulData.assetUrl;
export const logged = usefulData.logged;
export const userId = usefulData.userId;
export const customerUri = usefulData.customerUri;
export const customer = usefulData.customer;
export const callServer = usefulData.callServer;
export const lang = usefulData.lang;
export const novoName = usefulData.novoName || '';
export const novo = {
  value: usefulData.novoValue || '',
};
export const redirect = {
  link: usefulData.redirectLink,
};
