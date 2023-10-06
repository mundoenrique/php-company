import { calledCoreApp } from '../connection/core_app.js';

export const changeLanguage = function () {
  const module = 'User';
  const section = 'changeLanguage';
  const data = {
    lang: lang.GEN_AFTER_COD_LANG,
    path: location.pathname,
  };

  calledCoreApp(module, section, data, function (response) {
    if (response.code === 0) {
      location.assign(response.data.link);
    }
  });
};
