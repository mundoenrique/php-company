import { spinerLoader } from '../utils.js';
import { changeLanguage } from './change_language.js';
import { languageTenant } from './language.js';

$(function () {
  languageTenant();

  $('.spiner-loader').on('click', function () {
    spinerLoader(true);
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
