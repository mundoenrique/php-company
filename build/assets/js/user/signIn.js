import { getToken } from '../common/captchaHelper.js';
import { customer, customerUri, lang } from '../common/useful_data.js';
import { apiRequest } from '../connection/api_request.js';
import { calledCoreApp } from '../connection/core_app.js';
import { uiModalMessage } from '../modal/ui_modal.js';
import { appLoader, takeFormData, toggleDisableActions } from '../utils.js';
import { formValidation } from '../validation/form_validation.js';

$(function () {
  $.balloon.defaults.css = null;
  toggleDisableActions(false);
  let btnCalled;
  let btnSignin;
  let dataSignin;
  let formSignin;

  $('#signInBtn').on('click', function (e) {
    e.preventDefault();
    formSignin = $('#signInForm');
    formValidation(formSignin);

    if (formSignin.valid()) {
      btnCalled = $(this);
      btnSignin = $(this).html();
      dataSignin = takeFormData(formSignin);
      dataSignin.userPass = apiRequest(dataSignin.userPass);
      $(this).html(appLoader);
      toggleDisableActions(true);
      getToken('SignIn', function (recaptchaToken) {
        dataSignin.token = recaptchaToken;
        signIn('SignIn');
      });
    }
  });

  $('#system-info').on('click', '.session-close', function () {
    dataSignin = {
      userName: dataSignin.userName,
    };

    toggleDisableActions(true);
    $(this).html(appLoader);
    signIn('FinishSession');
  });

  $('#system-info').on('click', '.send-otp', function (e) {
    e.preventDefault();
    const formOtp = $('#otpForm');
    formValidation(formOtp);

    if (formOtp.valid()) {
      const dataOtp = takeFormData(formOtp);
      dataSignin.otpCode = $('#otpCode').val();
      dataSignin.saveIP = $('#saveIp').is(':checked') ? true : false;
      $(this).html(appLoader);
      toggleDisableActions(true);
      getToken('verifyIP', function (recaptchaToken) {
        dataSignin.token = recaptchaToken;
        signIn('SignIn');
      });
    }
  });

  const signIn = function (section) {
    const module = 'User';
    dataSignin.active = '';
    dataSignin.currentTime = new Date().getHours();

    calledCoreApp(module, section, dataSignin, function (coreAppResp) {
      if (coreAppResp.code !== 0 || (coreAppResp.code === 0 && !coreAppResp.data.link)) {
        $('#userPass').val('');

        if (lang.SETT_RESTAR_USERNAME === 'ON') {
          $('#userName').val('');
        }

        toggleDisableActions(false);
        btnCalled.html(btnSignin);
        formSignin.validate().resetForm();
      }

      handleSignInResponse[coreAppResp.code](coreAppResp);
    });
  };

  const handleSignInResponse = {
    0: function (coreAppResp) {
      if (coreAppResp.data.link) {
        let link = coreAppResp.data.link;

        if (coreAppResp.data.link.indexOf('dashboard') !== -1) {
          link = link.replace(customerUri, customer);
        }

        $(location).attr('href', link);
      }
    },
    1: function (coreAppResp) {
      $('#userName').showBalloon({
        html: true,
        classname: coreAppResp.data.className,
        position: coreAppResp.data.position,
        contents: coreAppResp.msg,
      });
      setTimeout(function () {
        $('#userName').hideBalloon();
      }, 2500);
    },
    2: function (coreAppResp) {
      $('#accept').addClass('send-otp');
      coreAppResp.minWidth = 470;
      coreAppResp.maxHeight = 'none';
      coreAppResp.posAt = 'center top';
      coreAppResp.posMy = 'center top+60';

      uiModalMessage(coreAppResp);
    },
    3: function (coreAppResp) {
      coreAppResp.minWidth = 470;
      coreAppResp.maxHeight = 'none';
      coreAppResp.posAt = 'center top';
      coreAppResp.posMy = 'center top+60';

      uiModalMessage(coreAppResp);
    },
    4: function (coreAppResp) {
      if (coreAppResp.data.action) {
        $('#accept').addClass(coreAppResp.data.action);
      }
    },
  };
});
