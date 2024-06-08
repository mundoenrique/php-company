import { lang } from '../common/useful_data.js';
import { uiModalMessage } from '../modal/ui_modal.js';

$(function () {
  $('#terms').on('click', function () {
    const modalMsg = {
      icon: lang.SETT_ICON_INFO,
      title: lang.GEN_SYSTEM_NAME,
      msg: lang.GEN_ACCEPT_TERMS,
      modalBtn: {
        btn1: {
          text: lang.GEN_BTN_ACCEPT,
          link: lang.SETT_LINK_CHANGE_PASS,
          action: 'redirect',
        },
        btn2: {
          text: lang.GEN_BTN_CANCEL,
          action: 'destroy',
        },
      },
    };

    uiModalMessage(modalMsg);
  });

  $('#cancel').on('click', function (e) {
    $('#terms').prop('checked', false);
  });
});
