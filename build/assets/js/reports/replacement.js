'use strict';
$(function () {
  insertFormInput(false);
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');
  let replacementTable = $('#replacementTable');
  let replacementForm = $('#replacementForm');
  let dataReplacement;

  const tablRreplacement = replacementTable.DataTable({
    language: dataTableLang,
    ordering: false,
    pagingType: 'full_numbers',
    responsive: true,
    select: false,
  });

  $('#initialDate, #finalDate').datepicker({
    onSelect: function (selectedDate) {
      $('#range').prop('checked', true);
      $(this).focus().blur();
      let dateSelected = selectedDate.split('/');
      dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
      dateSelected = new Date(dateSelected);
      let inputDate = $(this).attr('id');

      if (inputDate == 'initialDate') {
        $('#finalDate').datepicker('option', 'minDate', selectedDate);
        let maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + 1, dateSelected.getDate() - 1);

        if (currentDate > maxTime) {
          $('#finalDate').datepicker('option', 'maxDate', maxTime);
        } else {
          $('#finalDate').datepicker('option', 'maxDate', 'today');
        }
      }
    },
  });

  $('#radio-form').on('change', function () {
    $('#initialDate, #finalDate').removeClass('has-error').siblings('.help-block').text('');
    $('#finalDate').datepicker('option', 'maxDate', 'today');
    $('#finalDate').datepicker('setDate', 'today');

    if ($('#biannual').is(':checked')) {
      $('#initialDate').datepicker('setDate', '-6m');
    }

    if ($('#quarterly').is(':checked')) {
      $('#initialDate').datepicker('setDate', '-3m');
    }

    if ($('#range').is(':checked')) {
      $('#initialDate').val('');
      $('#finalDate').val('');
    }
  });
});
