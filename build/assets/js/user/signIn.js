import { getToken } from '../common/captchaHelper.js';
import { cryptography } from '../common/encrypt_decrypt.js';
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
      dataSignin.userPass = cryptography.encrypt(dataSignin.userPass);
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

    calledCoreApp(module, section, dataSignin, function (response) {
      if (response.code !== 0 || (response.code === 0 && !response.data.link)) {
        $('#userPass').val('');

        if (lang.SETT_RESTAR_USERNAME === 'ON') {
          $('#userName').val('');
        }

        toggleDisableActions(false);
        btnCalled.html(btnSignin);
        formSignin.validate().resetForm();
      }

      handleSignInResponse[response.code](response);
    });
  };

  const handleSignInResponse = {
    0: function (response) {
      if (response.data.link) {
        let link = response.data.link;

        if (response.data.link.indexOf('dashboard') !== -1) {
          link = link.replace(customerUri, customer);
        }

        $(location).attr('href', link);
      }
    },
    1: function (response) {
      $('#userName').showBalloon({
        html: true,
        classname: response.data.className,
        position: response.data.position,
        contents: response.msg,
      });
      setTimeout(function () {
        $('#userName').hideBalloon();
      }, 2500);
    },
    2: function (response) {
      $('#accept').addClass('send-otp');
      response.minWidth = 470;
      response.maxHeight = 'none';
      response.posAt = 'center top';
      response.posMy = 'center top+60';

      uiModalMessage(response);
    },
    3: function (response) {
      response.minWidth = 470;
      response.maxHeight = 'none';
      response.posAt = 'center top';
      response.posMy = 'center top+60';

      uiModalMessage(response);
    },
    4: function (response) {
      if (response.data.action) {
        $('#accept').addClass(response.data.action);
      }
    },
  };
});
