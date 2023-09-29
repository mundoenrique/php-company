import { spinerLoader } from '../utils.js';
import { changeLanguage } from './change_language.js';
import { languageTenant } from './language.js';

$(function () {
  languageTenant();

  $('body').on('click', '.pwd-action', function () {
    const passInput = $('body').find('.pwd-input');
    const inputType = passInput.attr('type');

    if (passInput.val() !== '') {
      if (inputType === 'password') {
        passInput.attr('type', 'text');
        $(this).attr('title', lang.GEN_HIDE_PASS);
      } else {
        passInput.attr('type', 'password');
        $(this).attr('title', lang.GEN_SHOW_PASS);
      }
    }
  });

  $('body').on('keydown', '.pwd-input', function () {
    ($(this).attr('type') === 'text') & $(this).attr('type', 'password');
  });

  $('.spiner-loader').on('click', function () {
    spinerLoader(true);

    setTimeout(() => {
      spinerLoader(false);
    }, 3000);
  });

  $('#change-lang').on('click', function () {
    changeLanguage();
  });

  $('.widget-menu').on('click', function (e) {
    e.stopPropagation();
    if ($('#widget-menu').hasClass('none')) {
      $('#widget-menu').removeClass('none');
      $('#widget-menu').addClass('show');
    } else {
      hideWidgetMenu();
    }
  });

  $('html, body').on('click', function () {
    hideWidgetMenu();
  });

  const hideWidgetMenu = function () {
    $('#widget-menu').removeClass('show');
    setTimeout(function () {
      $('#widget-menu').addClass('none');
    }, 1000);
  };
});
