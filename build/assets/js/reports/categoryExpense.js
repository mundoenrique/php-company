'use strict';
$(function () {
  insertFormInput(false);
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');
  let cateExpenseTable = $('#cateExpenseTable');
  let cateExpenseForm = $('#cateExpenseForm');

  const tableExpense = cateExpenseTable.DataTable({
    info: false,
    language: dataTableLang,
    ordering: false,
    paging: false,
    pagingType: 'full_numbers',
    responsive: true,
    searching: false,
    select: false,
  });

  $('#yearDate').datepicker({
    changeMonth: false,
    showButtonPanel: true,
    dateFormat: 'yy',
    beforeShow: function (input, inst) {
      inst.dpDiv.addClass('ui-datepicker-month-year');
    },
    onSelect() {
      $(this).focus().blur();
    },
    onClose: function (dateText, inst) {
      let year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
      $(this).datepicker('setDate', new Date(year, 1));
    },
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
    $('#initialDate, #finalDate, #yearDate').removeClass('has-error').siblings('.help-block').text('');

    if ($('#annual').is(':checked')) {
      $('#yearDate').removeClass('ignore');
      $('#initialDate, #finalDate').addClass('ignore');
      $('.year').removeClass('hide');
      $('.range').addClass('hide');
      $('.search-bnt').addClass('col-6');
      $('#initialDate').val('');
      $('#finalDate').val('');
    }

    if ($('#range').is(':checked')) {
      $('#initialDate, #finalDate').removeClass('ignore');
      $('#yearDate').addClass('ignore');
      $('.range').removeClass('hide');
      $('.year').addClass('hide');
      $('.search-bnt').removeClass('col-6');
      $('#yearDate').val('');
    }
  });

  $('#searchButton').on('click', function (e) {
    e.preventDefault();
    $('#cardNumber').attr('req', 'yes');
    $('#idDocument').attr('req', 'yes');
    cateExpenseForm = $('#cateExpenseForm');
    validateForms(cateExpenseForm);

    if (cateExpenseForm.valid()) {
      let data = getDataForm(cateExpenseForm);
      data.annual = $('#annual').is(':checked');
      cateExpenseTable.dataTable().fnClearTable();
      delete data.range;
      insertFormInput(true);
      $('#blockResults, #titleResults').addClass('hide');
      $('#spinnerBlock').removeClass('hide');
      $('#queryType').text(data.annual ? 'Anual' : 'Rango');

      who = 'Reports';
      where = 'categoryExpense';
      callNovoCore(who, where, data, function (response) {
        if (response.code !== 0) {
          $('#buttonFiles').addClass('hide');
        }

        $.each(response.data, function (day, expense) {
          let row = [day];

          $.each(expense, function (index, value) {
            row.push(value);
          });

          tableExpense.row.add(row).draw();
        });

        $('#spinnerBlock').addClass('hide');
        $('#blockResults, #titleResults').removeClass('hide');
        insertFormInput(false);
      });
    }
  });
});
