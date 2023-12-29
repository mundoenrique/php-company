'use strict';
$(function () {
  insertFormInput(false);
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');
  let replacementTable = $('#replacementTable');
  let replacementForm = $('#replacementForm');
  let dataReplacement;

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

  $('#searchButton').on('click', function (e) {
    e.preventDefault();
    replacementForm = $('#replacementForm');
    validateForms(replacementForm);

    if (replacementForm.valid()) {
      dataReplacement = getDataForm(replacementForm);
      delete dataReplacement.biannual;
      delete dataReplacement.quarterly;
      delete dataReplacement.range;
      // replacementTable.dataTable().fnClearTable();
      // replacementTable.dataTable().fnDestroy();

      insertFormInput(true);
      $('#blockResults, #titleResults').addClass('hide');
      $('#spinnerBlock').removeClass('hide');
      replacementRenderTable();
    }
  });

  const replacementRenderTable = function () {
    replacementTable.DataTable({
      drawCallback: function (d) {
        insertFormInput(false);
        $('#spinnerBlock').addClass('hide');
        $('#blockResults, #titleResults').removeClass('hide');
      },
      language: dataTableLang,
      ordering: false,
      pagingType: 'full_numbers',
      processing: true,
      responsive: true,
      searching: false,
      select: false,
      serverSide: true,
      columns: [
        { data: 'cardNumber' },
        { data: 'cardholder' },
        { data: 'documentId' },
        { data: 'issueDate' },
        { data: 'bulkId' },
        { data: 'servOrder' },
        { data: 'invNumber' },
      ],
      columnDefs: [
        {
          targets: 0,
          className: 'cardNumber',
          visible: lang.SETT_REPLACE_CARD_NUMBER === 'ON',
        },
        {
          targets: 1,
          className: 'cardholder',
          visible: lang.SETT_REPLACE_CARD_HOLDER === 'ON',
        },
        {
          targets: 2,
          className: 'documentId',
          visible: lang.SETT_REPLACE_DOCUMENT_ID === 'ON',
        },
        {
          targets: 3,
          className: 'issueDate',
          visible: lang.SETT_REPLACE_ISSUE_DATE === 'ON',
        },
        {
          targets: 4,
          className: 'bulkId',
          visible: lang.SETT_REPLACE_BULK_ID === 'ON',
        },
        {
          targets: 5,
          className: 'servOrder',
          visible: lang.SETT_REPLACE_SERV_ORDER === 'ON',
        },
        {
          targets: 6,
          className: 'invNumber',
          visible: lang.SETT_REPLACE_INV_NUMBER === 'ON',
        },
      ],
      ajax: {
        url: baseURL + 'async-call',
        method: 'POST',
        dataType: 'json',
        cache: false,
        data: function (req) {
          let dataRequest = JSON.stringify({
            who: 'Reports',
            where: 'replacement',
            data: {
              ...dataReplacement,
              ...req,
            },
          });
          dataRequest = {
            request: cryptoPass(dataRequest, true),
            ceo_name: ceo_cook,
            plot: btoa(ceo_cook),
          };

          return dataRequest;
        },
        dataFilter: function (resp) {
          var responseTable = jQuery.parseJSON(resp);

          responseTable = JSON.parse(
            CryptoJS.AES.decrypt(responseTable.code, responseTable.plot, { format: CryptoJSAesJson }).toString(
              CryptoJS.enc.Utf8
            )
          );
          var codeDefaul = parseInt(lang.SETT_DEFAULT_CODE);
          if (responseTable.code === codeDefaul) {
            appMessages(responseTable.title, responseTable.msg, responseTable.icon, responseTable.modalBtn);
          }
          console.log(responseTable);
          return JSON.stringify(responseTable);
        },
      },
    });
  };
});
