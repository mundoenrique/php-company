'use strict';
var table;
var tableContact;
var geo;

$(function () {
  var ulOptions = $('.nav-item-config');
  $('#existingContactButton').addClass('hidden');

  $.each(ulOptions, function (pos, liOption) {
    $('#' + liOption.id).on('click', function (e) {
      var liOptionId = e.currentTarget.id;
      $(ulOptions).removeClass('active');
      $('.option-service').hide();
      $('#tableContacts_wrapper').hide();
      $(this).addClass('active');
      $('#' + liOptionId + 'View').fadeIn(700, 'linear');
    });
  });

  $('.slide-slow').on('click', function () {
    $('.section').slideToggle('slow');
  });

  $('ul.nav-config-box, .slide-slow').on('click', function (e) {
    var event = $(e.currentTarget);

    if (!event.hasClass('slide-slow')) {
      $('.section').hide();
    }

    $('input, select').removeClass('has-error');
    $('.help-block').text('');
  });

  $('.nav-item-config:first-child').addClass('active');
  var firstActive = $('.nav-config-box > li:first-child').attr('id');
  $('#' + firstActive + 'View').show();

  $('#userDataBtn').on('click', function (e) {
    e.preventDefault();
    form = $('#userDataForm');
    btnText = $(this).text().trim();
    validateForms(form);

    if (form.valid()) {
      who = 'Tools';
      where = 'changeEmail';
      data = getDataForm(form);
      data.email = $('#currentEmail').val().toLowerCase();
      $(this).html(loader);
      insertFormInput(true);

      callNovoCore(who, where, data, function (response) {
        dataResponse = response.data;
        insertFormInput(false);
        $('#userDataBtn').html(btnText);
      });
    }
  });

  $.each(lang.TOOLS_FILES_DOWNLOAD, function (index, header) {
    $.each(header, function (index2, detail) {
      if (detail[3] == 'request') {
        $('a.' + detail[0]).on('click', function () {
          if ($(this).attr('title') == '') {
            who = 'Tools';
            where = 'GetFileIni';
            data = {};
            callNovoCore(who, where, data, function (response) {
              if (response.code == 0) {
                downLoadfiles(response.data);
              }

              $('.cover-spin').hide();
            });
          }
        });
      }
    });
  });
});

function validInputFile() {
  form = $('#txtBranchesForm');
  validateForms(form);

  if ($('#file-branch').valid()) {
    $('.js-label-file').removeClass('has-error');
  } else {
    $('.js-label-file').addClass('has-error');
  }
}
