'use strict';
$(function () {
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');
  form = $('#confirm-bulk-btn');

  $('#confirm-bulk').on('click', function (e) {
    e.preventDefault();
    btnText = $(this).text();
    validateForms(form);

    if (form.valid()) {
      insertFormInput(true);
      $(this).html(loader);
      data = {
        bulkTicked: $('#bulkTicked').val(),
      };

      if (lang.SETT_REMOTE_AUTH === 'OFF') {
        data.pass = cryptoPass(inputPass.val());
      }

      who = 'Bulk';
      where = 'ConfirmBulk';

      callNovoCore(who, where, data, function (response) {
        $('#confirm-bulk').html(btnText);
        insertFormInput(false);
        inputPass.val('');
        respConfirmBulk[response.code](response);
      });
    }
  });

  const respConfirmBulk = {
    0: function (response) {
      appMessages(response.title, response.msg, response.icon, response.modalBtn);
    },
  };
});
