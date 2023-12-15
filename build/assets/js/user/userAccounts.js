'use strict';
$(function () {
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');
  insertFormInput(false);
  $('#enableSectionBtn').addClass('hidden');

  for (var i = 0; i <= 28; i++) {
    var checkboxValue = $('input[name=checkbox' + i + ']');
    if (checkboxValue.val() == 'off') {
      checkboxValue.prop('checked', false);
    } else if (checkboxValue.val() == 'on') {
      checkboxValue.prop('checked', true);
    }
  }

  $('#allAccounts').change(function () {
    if ($('#allAccounts').is(':checked')) {
      $('.accounts').prop('checked', true);
      $('.accounts').val('on');
      $('#removeAllAccounts').prop('checked', false);
    }
  });

  $('#removeAllAccounts').change(function () {
    if ($('#removeAllAccounts').is(':checked')) {
      $('.accounts').prop('checked', false);
      $('.accounts').val('off');
      $('#allAccounts').prop('checked', false);
    }
  });

  $('.accounts').change(function () {
    if ($(this).is(':checked')) {
      $(this).val('on');
      $('#removeAllAccounts').prop('checked', false);
    } else {
      $(this).val('off');
    }
    $('#allAccounts').prop('checked', false);
  });

  $('#enableUserBtn').on('click', function () {
    $('#sectionAccounts').fadeIn(700, 'linear');
    $('#enableSectionBtn').remove();
  });

  $('#updateUserBtn').on('click', function (e) {
    var changeBtn = $(this);
    var btnText = changeBtn.text().trim();
    var form = $('#checkFormAccounts');
    var passData = getDataForm(form);
    $('#spinnerBlock').addClass('hide');
    passData.idUsuario = $('#idUser').text();
    passData.fullName = $('#fullName').text();
    passData.email = $('#email').text();
    passData.typeUser = $('#typeUser').text();

    validateForms(form);

    if (form.valid()) {
      insertFormInput(true, form);
      changeBtn.html(loader);
      updateAccounts(passData, btnText);
    }
  });
});

function updateAccounts(passData, btnText) {
  who = 'User';
  where = 'updateAccounts';
  data = passData;

  callNovoCore(who, where, data, function (response) {
    dataResponse = response.data;
    $('#updateUserBtn').html(btnText);
    insertFormInput(false);
  });
}
