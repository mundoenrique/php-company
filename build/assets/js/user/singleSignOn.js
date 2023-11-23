import { calledCoreApp, calledCoreAppForm } from '../connection/core_app.js';
import { takeFormData, toggleDisableActions } from '../utils.js';

$(function () {
  const form = $('#single-signin-form');
  const send = $('#single-signin-form').attr('send');
  let dataSignin;

  if (send) {
    toggleDisableActions(true);
    dataSignin = takeFormData(form);
    dataSignin.uri = 'ingresar';
    calledCoreAppForm(dataSignin);
  } else {
    const module = 'User';
    const section = 'singleSignOn';
    dataSignin = takeFormData(form);
    dataSignin.route = 'single';
    dataSignin.currentTime = new Date().getHours();

    calledCoreApp(module, section, dataSignin, function (response) {
      $(location).attr('href', response.data.link);
    });
  }
});
