'use strict';
var access;

$(function () {
  $('#blockBudgetResults').addClass('hide');
  $('#titleResults').addClass('hide');
  $('#Nit').attr('maxlength', 10);
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');
  $('#export_excel').addClass('hide');

  $('#enterpriseReport').on('change', function () {
    var form = $('#closingBudgetForm');
    $('#closingBudgetsBtn').removeAttr('disabled');
    $('#productCode').attr('disabled', 'disabled');
    $('#products-select').empty();
    var data = {
      enterpriseCode: $('#enterpriseReport').find('option:selected').attr('code'),
      enterpriseGroup: $('#enterpriseReport').find('option:selected').attr('group'),
      idFiscal: $('#enterpriseReport').find('option:selected').attr('acrif'),
      enterpriseName: $('#enterpriseReport').find('option:selected').text(),
      select: true,
    };
    if (form.valid()) {
      insertFormInput(true, form);
      selectionBussine(data);
    }
  });

  $('#closingBudgetsBtn').on('click', function (e) {
    $('#blockBudgetResults').addClass('hide');
    $('#export_excel').removeClass('hide');
    e.preventDefault();
    form = $('#closingBudgetForm');
    validateForms(form);

    if (form.valid()) {
      $('#spinnerBlock').removeClass('hide');
      insertFormInput(true, form);
      form = $('#closingBudgetForm');
      var dataForm = getDataForm(form);
      closingBudgets(dataForm);
    }
  });

  $('#export_excel').click(function () {
    var form = $('#closingBudgetForm');
    var data = {
      identificationCard: $('#enterpriseReport').find('option:selected').attr('acrif'),
      product: $('#productCode').val(),
      descProd: $('#productCode').find('option:selected').attr('value'),
      actualPage: 1,
      paged: true,
      pageLenght: $('#tamP').val(),
    };
    validateForms(form);
    if (form.valid()) {
      exportToExcel(data);
    }
  });
});

function selectionBussine(passData) {
  who = 'Business';
  where = 'getProducts';
  data = passData;
  $('#productCode').html('');

  callNovoCore(who, where, data, function (response) {
    dataResponse = response.data;
    code = response.code;
    var info = dataResponse;
    if (code == 3) {
      $('#productCode').append('<option>' + $('#errProd').val() + '</option>');
      $('#closingBudgetsBtn').attr('disabled', 'disabled');
    } else if (code == 0) {
      insertFormInput(false);
    }
    $('#productCode').removeAttr('disabled');
    for (var index = 0; index < info.length; index++) {
      $('#productCode').append(
        '<option value=' + info[index].id + ' brand=' + info[index].brand + '>' + info[index].desc + '</option>'
      );
    }
  });
}

function exportToExcel(passData) {
  who = 'Reports';
  where = 'exportToExcel';
  data = passData;
  if (lang.SETT_NIT_INPUT_BOOL == 'ON') {
    data.idExtPer = $('#document').val();
  } else {
    data.idExtPer = '';
  }

  callNovoCore(who, where, data, function (response) {
    dataResponse = response.data;
    code = response.code;
    var info = dataResponse;
    if (info.formatoArchivo == 'excel') {
      info.formatoArchivo = '.xls';
    }
    if (code == 0) {
      data = {
        name: info.nombre.replace(/ /g, '') + info.formatoArchivo,
        ext: info.formatoArchivo,
        file: info.archivo,
      };
      downLoadfiles(data);
      $('.cover-spin').removeAttr('style');
    }
  });
}

function closingBudgets(dataForm) {
  var table = $('#balancesClosing').DataTable();
  table.destroy();
  table = $('#balancesClosing').DataTable({
    drawCallback: function (d) {
      insertFormInput(false);
      $('#spinnerBlock').addClass('hide');
      $('#tbody-datos-general').removeClass('hide');
      $('#titleResults').removeClass('hide');
      $('#blockBudgetResults').removeClass('hide');
      $('#pre-loader-table').addClass('hide');
      $('.hide-table').removeClass('hide');
      $('.hide-out').removeClass('hide');
    },
    autoWidth: false,
    ordering: false,
    searching: false,
    lengthChange: false,
    pagelength: 10,
    pagingType: 'full_numbers',
    'table-layout': 'fixed',
    select: {
      style: 'multi',
      info: false,
    },
    language: dataTableLang,
    processing: true,
    serverSide: true,
    columns: [
      { data: 'tarjeta' },
      { data: 'nombre' },
      { data: 'idExtPer' },
      { data: 'saldo' },
      { data: 'fechaUltAct' },
    ],
    columnDefs: [
      {
        targets: 0,
        className: 'tarjeta',
        visible: lang.SETT_CARD_COLUMN == 'ON',
      },
      {
        targets: 1,
        className: 'nombre',
        visible: lang.SETT_NAME_COLUMN == 'ON',
      },
      {
        targets: 2,
        className: 'idExtPer',
        visible: lang.SETT_ID_COLUMN == 'ON',
      },
      {
        targets: 3,
        className: 'saldo',
        visible: lang.SETT_BALANCE_COLUMN == 'ON',
      },
      {
        targets: 4,
        className: 'fechaUltAct',
        visible: lang.SETT_LAST_UPDATE_COLUMN == 'ON',
      },
    ],
    ajax: {
      url: baseURL + 'async-call',
      method: 'POST',
      dataType: 'json',
      cache: false,
      data: function (req) {
        data = req;
        if (lang.SETT_NIT_INPUT_BOOL == 'ON') {
          data.idExtPer = $('#document').val();
        } else {
          data.idExtPer = '';
        }
        data.product = $('#productCode').val();
        data.idExt = $('#enterpriseReport').find('option:selected').attr('acrif');
        data.screenSize = screen.width;
        data.paginar = true;
        var dataRequest = JSON.stringify({
          who: 'Reports',
          where: 'closingBudgets',
          data: data,
        });

        dataRequest = cryptoPass(dataRequest, true);
        var request = {
          request: dataRequest,
          ceo_name: ceo_cook,
          plot: btoa(ceo_cook),
        };
        return request;
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
          appMessages(responseTable.title, responseTable.msg, responseTable.icon, responseTable.dataResp);
        }
        access = responseTable.access;
        return JSON.stringify(responseTable);
      },
    },
  });
}
