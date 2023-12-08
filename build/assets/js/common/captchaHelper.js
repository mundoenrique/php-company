import { uiModalMessage } from '../modal/ui_modal.js';
import { lang, redirect } from './useful_data.js';

export const getToken = function (module, _function_) {
  if (lang.SETT_ACTIVE_RECAPTCHA) {
    grecaptcha.ready(function () {
      grecaptcha.execute(lang.SETT_KEY_RECAPTCHA, { action: module }).then(
        function (token) {
          if (token) {
            token;
            _function_(token);
          }
        },
        function (token) {
          if (!token) {
            const failCaptcha = {
              icon: lang.SETT_ICON_WARNING,
              title: lang.GEN_SYSTEM_NAME,
              msg: lang.GEN_SYSTEM_MESSAGE,
              modalBtn: {
                btn1: {
                  link: redirect.link,
                  action: 'redirect',
                },
              },
            };

            uiModalMessage(failCaptcha);
          }
        }
      );
    });
  } else {
    _function_('');
  }
};
