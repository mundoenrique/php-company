'use strict';
$(function () {
  insertFormInput(false);
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');
  let cateExpenseTable = $('#cateExpenseTable');
  let cateExpenseForm = $('#cateExpenseForm');
  let dataExpense;

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

  $('#radio-type').on('change', function () {
    $('#idDocument, #cardNumber').removeClass('has-error').siblings('.help-block').text('');

    if ($('#dni').is(':checked')) {
      $('#idDocument').removeClass('ignore');
      $('#cardNumber').addClass('ignore');
      $('.card-number').addClass('hide');
      $('.dni').removeClass('hide');
      $('#cardNumber').val('');
    }

    if ($('#card').is(':checked')) {
      $('#cardNumber').removeClass('ignore');
      $('#idDocument').addClass('ignore');
      $('.dni').addClass('hide');
      $('.card-number').removeClass('hide');
      $('#idDocument').val('');
    }
  });

  $('#radio-date').on('change', function () {
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
      dataExpense = getDataForm(cateExpenseForm);
      dataExpense.annual = $('#annual').is(':checked');
      dataExpense.idDocument = $('#dni').is(':checked') ? dataExpense.idDocument : '';
      dataExpense.cardNumber = $('#card').is(':checked') ? dataExpense.cardNumber : '';
      dataExpense.type = 'list';
      cateExpenseTable.dataTable().fnClearTable();
      delete dataExpense.range;
      delete dataExpense.dni;
      delete dataExpense.card;
      insertFormInput(true);
      $('#blockResults').addClass('hide');
      $('#spinnerBlock').removeClass('hide');
      $('#queryType').text(dataExpense.annual ? 'Anual' : 'Rango');

      cateExpenseRequest();
    }
  });

  $('#buttonFiles').on('click', 'button', function (e) {
    e.preventDefault();
    let type = $(e.target).attr('type');
    dataExpense.type = type;
    insertFormInput(true);

    cateExpenseRequest();
  });

  const cateExpenseRequest = function () {
    who = 'Reports';
    where = 'categoryExpense';

    callNovoCore(who, where, dataExpense, function (response) {
      if (response.code !== 0) {
        $('#buttonFiles').addClass('hide');
      }

      if (dataExpense.type === 'list') {
        $.each(response.data.tableData, function (date, expense) {
          let row = [date];

          $.each(expense, function (index, value) {
            row.push(value);
          });

          tableExpense.row.add(row).draw();
        });

        $('#spinnerBlock').addClass('hide');
        $('#blockResults, #titleResults').removeClass('hide');
      } else if (response.code === 0) {
        downLoadfiles(response.data);
      }

      insertFormInput(false);
    });
  };
});
