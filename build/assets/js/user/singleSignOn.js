import { calledCoreApp, calledCoreAppForm } from '../connection/core_app.js';
import { takeFormData, toggleDisableActions } from '../utils.js';

$(function () {
  const form = $('#single-signin-form');
  const submit = $('#single-signin-form').attr('submit');
  let dataSignin;

  if (submit === '1') {
    toggleDisableActions(true);
    dataSignin = takeFormData(form);
    dataSignin.uri = 'ingress';
    calledCoreAppForm(dataSignin);
  } else {
    const module = 'User';
    const section = 'singleSignOn';
    dataSignin = takeFormData(form);
    dataSignin.route = 'single';
    dataSignin.currentTime = new Date().getHours();

    calledCoreApp(module, section, dataSignin, function (coreAppResp) {
      $(location).attr('href', coreAppResp.data.link);
    });
  }
});
