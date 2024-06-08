import { calledCoreApp } from '../connection/core_app.js';
import { lang } from './useful_data.js';

export const changeLanguage = function () {
  const module = 'User';
  const section = 'changeLanguage';
  const dataLanguage = {
    lang: lang.GEN_AFTER_COD_LANG,
    path: location.pathname,
  };

  calledCoreApp(module, section, dataLanguage, function (coreAppResp) {
    if (coreAppResp.code === 0) {
      location.assign(coreAppResp.data.link);
    }
  });
};
