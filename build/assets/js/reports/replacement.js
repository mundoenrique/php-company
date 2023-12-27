'use strict';
var reportsResults;
$(function () {
  insertFormInput(false);
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');

  $('#resultsAccount').DataTable({
    ordering: false,
    responsive: true,
    pagingType: 'full_numbers',
    language: dataTableLang,
  });

  $('#initialDate, #finalDate').datepicker({
    beforeShow: function (input, inst) {
      inst.dpDiv.removeClass('ui-datepicker-month-year');
    },
    onSelect: function (selectedDate) {
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
          $('#finalDate').datepicker('option', 'maxDate', currentDate);
        }
      }
    },
  });

  $('#radio-form').on('change', function () {
    $('#initialDate, #finalDate').removeClass('has-error').siblings('.help-block').text('');

    if ($('#quarterly').is(':checked') || $('#biannual').is(':checked')) {
      $('#initialDate, #finalDate').addClass('ignore').attr('disabled', true);
      $('#initialDate').val('');
      $('#finalDate').val('');
    }

    if ($('#range').is(':checked')) {
      $('#initialDate, #finalDate').removeClass('ignore').attr('disabled', false);
    }
  });
});
