'use strict';
var table;
var access;
var costs;
var balance;
var balanceConcentratingAccount;
var cardsData;
var cardHolderInf;
$(function () {
  var getAmount;
  var title;
  var action;
  insertFormInput(false);
  remoteFunction = 'sendRequest';
  form = $('#masterAccountForm');
  var dataForm = getDataForm(form);
  dataTableLang.emptyTable = lang.SERVICES_TABLE_NO_REGISTRY_MASTERACCOUNT;

  table = $('#tableServicesMaster').DataTable({
    drawCallback: function (d) {
      $('#balance-aviable').text(balance);

      if (lang.SETT_BALANCE_ACC_CONCENTRATOR == 'ON') {
        $('#balance-acc-concentrator').text(balanceConcentratingAccount);
      }

      $('#cost-trans').text(costs.costoComisionTrans);
      $('#cost-inquiry').text(costs.costoComisionCons);
      insertFormInput(false);
      verifyOperations();
      $('#pre-loader-table').addClass('hide');
      $('.hide-table').removeClass('hide');
      $('#pre-loader').remove();
      $('.disabled-recharge').prop('disabled', false);
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
    ajax: {
      url: baseURL + 'async-call',
      method: 'POST',
      dataType: 'json',
      cache: false,
      data: function (req) {
        data = req;
        data.idNumber = dataForm.idNumber;
        data.cardNumber = dataForm.cardNumber;
        data.screenSize = screen.width;
        var dataRequest = JSON.stringify({
          who: 'Services',
          where: 'TransfMasterAccount',
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
        $('#tableServicesMaster').find('thead > tr').removeClass('selected');
        var responseTable = jQuery.parseJSON(resp);
        responseTable = JSON.parse(
          CryptoJS.AES.decrypt(responseTable.code, responseTable.plot, {
            format: CryptoJSAesJson,
          }).toString(CryptoJS.enc.Utf8)
        );
        var codeDefaul = parseInt(lang.SETT_DEFAULT_CODE);

        if (responseTable.code === codeDefaul) {
          appMessages(responseTable.title, responseTable.msg, responseTable.icon, responseTable.dataResp);
        }

        access = responseTable.access;
        balance = responseTable.balance;
        $('#balance-aviable').addClass(responseTable.cssNegativeBalance);
        balanceConcentratingAccount = responseTable.balanceConcentratingAccount;
        costs = responseTable.params;
        return JSON.stringify(responseTable);
      },
    },
    columnDefs: [
      {
        targets: 0,
        className: 'select-checkbox',
        checkboxes: {
          selectRow: true,
        },
      },
      {
        targets: 1,
        className: 'card-number',
        width: '200px',
      },
      {
        targets: 2,
        width: '200px',
      },
      {
        targets: 3,
        className: 'user-id',
        width: 'auto',
      },
      {
        targets: 4,
        className: 'balance',
        width: '120px',
      },
      {
        targets: 5,
        className: 'amount-cc',
        width: '150px',
      },
      {
        targets: 6,
        width: '200px',
      },
    ],
    columns: [
      {
        data: function (data) {
          return '';
        },
      },
      {
        data: 'cardNumber',
      },
      {
        data: 'name',
      },
      {
        data: 'idNumber',
      },
      {
        data: function (data) {
          return '--';
        },
      },
      {
        data: function (data) {
          var ammount;
          var disabeldInput = data.status == '' ? '' : 'disabled';
          ammount = '<form>';
          ammount += '<input class="form-control h6 text-right" type="text" placeholder="';
          ammount += data.amount + '" ' + disabeldInput + '">';
          ammount += '</form>';

          return ammount;
        },
      },
      {
        data: function (data) {
          var options = '';

          if (access.TRASAL && data.status == '') {
            options +=
              '<button class="btn mx-1 px-0" title="' + lang.GEN_CHECK_BALANCE + '" data-toggle="tooltip" amount="0" ';
            options += 'action="CHECK_BALANCE">';
            options += '<i class="icon icon-envelope-open" aria-hidden="true"></i>';
            options += '</button>';
          }

          if (access.TRACAR && data.status == '') {
            options +=
              '<button class="btn mx-1 px-0" title="' + lang.GEN_CREDIT_TO_CARD + '" data-toggle="tooltip" amount="1" ';
            options += 'action="CREDIT_TO_CARD">';
            options += '<i class="icon icon-credit-card" aria-hidden="true"></i>';
            options += '</button>';
          }

          if (access.TRAABO && data.status == '') {
            options +=
              '<button class="btn mx-1 px-0" title="' + lang.GEN_DEBIT_TO_CARD + '" data-toggle="tooltip" amount="1" ';
            options += 'action="DEBIT_TO_CARD">';
            options += '<i class="icon icon-card-fee" aria-hidden="true"></i>';
            options += '</button>';
          }

          if (access.TRABLQ && data.status == '') {
            options +=
              '<button class="btn mx-1 px-0" title="' + lang.GEN_LOCK_TYPES + '" data-toggle="tooltip" amount="0" ';
            options += 'action="LOCK_TYPES">';
            options += '<i class="icon icon-lock" aria-hidden="true"></i>';
            options += '</button>';
          }

          if (access.TRADBL && data.status == 'pb') {
            options +=
              '<button class="btn mx-1 px-0" title="' +
              lang.GEN_TEMPORARY_UNLOCK +
              '" data-toggle="tooltip" amount="0" ';
            options += 'action="TEMPORARY_UNLOCK">';
            options += '<i class="icon icon-unlock" aria-hidden="true"></i>';
            options += '</button>';
          }

          if (access.TRAASG) {
            options +=
              '<button class="btn mx-1 px-0" title="' +
              lang.GEN_CARD_ASSIGNMENT +
              '" data-toggle="tooltip" amount="0" ';
            options += 'action="CARD_ASSIGNMENT">';
            options += '<i class="icon icon-deliver-card" aria-hidden="true"></i>';
            options += '</button>';
          }

          return options;
        },
      },
    ],
  });

  $('#masterAccountBtn').on('click', function (e) {
    e.preventDefault();
    $('#tableServicesMaster').find('thead > tr').removeClass('selected');
    $('.help-block').text('');
    $('input, select').removeClass('has-error');
    form = $('#masterAccountForm');
    validateForms(form);

    if (form.valid()) {
      dataForm = getDataForm(form);
      insertFormInput(true);
      dataTableReload(true);
    }
  });

  $('#tableServicesMaster').on('click', '.toggle-all', function () {
    $(this).closest('tr').toggleClass('selected');
    if ($(this).closest('tr').hasClass('selected')) {
      table.rows().select();
    } else {
      table.rows().deselect();
    }
  });

  $('#tableServicesMaster').on('click', 'button', function (e) {
    var event = $(e.currentTarget);
    action = event.attr('action');
    title = event.attr('title');
    getAmount = event.attr('amount');
    table.rows().deselect();
    $('#tableServicesMaster').find('thead > tr').removeClass('selected');
    $('#tableServicesMaster').find('tbody > tr').removeClass('selected');
    $(this).closest('tr').addClass('selected');

    if (amountValidate(getAmount, title)) {
      MasterAccBuildFormActions(action, title, $(this));
    }
  });

  $('#tableServicesMaster').on('click', 'tbody td.amount-cc', function (e) {
    $(this).find('input').removeClass('has-error');
  });

  $('#system-info').on('click', '.send-request', function () {
    form = $('#password-modal');
    btnText = $(this).text().trim();
    sendRequest(action, title, $(this));
  });

  $('#system-info').on('click', '.get-auth-key', function () {
    form = $('#password-modal');
    btnText = $(this).text().trim();

    if (formValidateTrim(form, title)) {
      btnRemote = $(this);
      remoteAuthArgs.action = action;
      remoteAuthArgs.title = title;
      getauhtKey();
    }
  });

  $('#Consulta, #Abono, #Cargo').on('click', function (e) {
    e.preventDefault();
    title = $(this).attr('id');
    action = $(this).attr('action');
    getAmount = $(this).attr('amount');

    if (amountValidate(getAmount, title)) {
      form = $('#password-table');
      btnText = $(this).text().trim();

      if (
        lang.SETT_REMOTE_AUTH == 'OFF' ||
        (lang.SETT_REMOTE_AUTH == 'ON' && $.inArray(action, lang.SETT_AUTH_LIST) == -1)
      ) {
        if (formValidateTrim(form, title) && lang.SETT_REFERENCE == 'ON' && action == 'CREDIT_TO_CARD') {
          modalReference(action, title, $(this));
        } else {
          sendRequest(action, title, $(this));
        }
      } else if (formValidateTrim(form, title)) {
        if (lang.SETT_REFERENCE == 'ON' && action == 'CREDIT_TO_CARD') {
          modalReference(action, title, $(this));
        } else {
          btnRemote = $(this);
          remoteAuthArgs.action = action;
          remoteAuthArgs.title = title;
          getauhtKey();
        }
      }
    }
  });

  $('#system-info').on('click', '#cancel', function () {
    $('#tableServicesMaster').find('tr').removeClass('selected');
  });

  $('#tableServicesMaster').on({
    focus: function (event) {
      $(event.target).select();
    },
    keyup: function (event) {
      $(event.target).val(function (index, value) {
        if (value.indexOf('0') != -1 && value.indexOf('0') == 0) {
          value = value.replace(0, '');
        }

        if (value.length == 1 && /^[0-9,.]+$/.test(value)) {
          value = '00' + value;
        }

        value = value
          .replace(/\D/g, '')
          .replace(/([0-9])([0-9]{2})$/, '$1' + lang.SETT_DECIMAL + '$2')
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, lang.SETT_THOUSANDS);

        return value;
      }, 'input');
    },
  });
});

function MasterAccBuildFormActions(currentAction, currentTitle, currentBtn) {
  modalBtn = {
    btn1: {
      text: lang.GEN_BTN_ACCEPT,
      action: 'none',
    },
    btn2: {
      text: lang.GEN_BTN_CANCEL,
      action: 'destroy',
    },
  };
  inputModal = '<form id="password-modal" name="password-modal" class="row col-auto" onsubmit="return false;">';

  if (currentAction == 'CREDIT_TO_CARD' && lang.SETT_REFERENCE == 'ON') {
    inputModal += '<div class="form-group col-12 pl-0 w-160-ie">';
    inputModal += '<div class="input-group">';
    inputModal += '<input class="form-control" type="text" id="reference" name="reference" autocomplete="off"';
    inputModal += 'placeholder="' + lang.SERVICES_REFERENCE + '">';
    inputModal += '</div>';
    inputModal += '<div class="help-block"></div>';
    inputModal += '</div>';
  }

  if (currentAction == 'CARD_ASSIGNMENT') {
    inputModal += '<div class="form-group col-12 pl-0 w-160-ie">';
    inputModal += '<div class="input-group">';
    inputModal += '<input class="form-control" type="text" id="cardNumber" name="cardNumber" autocomplete="off"';
    inputModal += 'placeholder="' + lang.GEN_TABLE_CARD_NUMBER + '" req="yes">';
    inputModal += '</div>';
    inputModal += '<div class="help-block"></div>';
    inputModal += '</div>';
  }

  if (currentAction == 'LOCK_TYPES') {
    inputModal += '<div class="form-group col-12 pl-0 w-160-ie">';
    inputModal += '<label>' + lang.SERVICES_REASON_REQUEST + '</label>';
    inputModal += '<select class="custom-select form-control" name="lockType" id="lockType">';
    inputModal += '<option value="" selected>' + lang.SERVICES_REASON_LOCK_TYPES + '</option>';

    $.each(lang.SERVICES_LOCK_TYPES_BLOCK, function (key, element) {
      inputModal += '<option value="' + key + '">' + element + '</option>';
    });

    inputModal += '</select>';
    inputModal += '<div class="help-block"></div>';
    inputModal += '</div>';
  }

  if (lang.SETT_REMOTE_AUTH == 'OFF') {
    $('#accept').addClass('send-request');

    inputModal += '<div class="form-group col-12 pl-0 w-160-ie">';
    inputModal += '<div class="input-group">';
    inputModal +=
      '<input class="form-control pwd-input pwd" type="password" id="password" name="password" autocomplete="off" ';
    inputModal += 'placeholder="' + lang.GEN_PLACE_PASSWORD + '">';
    inputModal += '<div class="input-group-append">';
    inputModal +=
      '<span class="input-group-text pwd-action" title="' +
      lang.GEN_SHOW_PASS +
      '"><i class="icon-view mr-0"></i></span>';
    inputModal += '</div>';
    inputModal += '</div>';
    inputModal += '<div class="help-block"></div>';
    inputModal += '</div>';
  } else {
    $('#accept').addClass('get-auth-key');
    currentBtn = btnRemote;
    form = $('#nonForm');

    if ($.inArray(currentAction, lang.SETT_AUTH_VALIDATE) == -1) {
      $('.cover-spin').show(0);
    }
  }

  inputModal += '</form>';

  if (
    lang.SETT_REMOTE_AUTH == 'OFF' ||
    (lang.SETT_REMOTE_AUTH == 'ON' && $.inArray(currentAction, lang.SETT_AUTH_VALIDATE) != -1)
  ) {
    appMessages(currentTitle, inputModal, lang.SETT_ICON_INFO, modalBtn);
  } else if ($.inArray(currentAction, lang.SETT_AUTH_LIST) != -1) {
    remoteAuthArgs.action = currentAction;
    remoteAuthArgs.title = currentTitle;
    getauhtKey();
  } else {
    sendRequest(currentAction, currentTitle, currentBtn);
  }
}

function amountValidate(getAmount, currentTitle) {
  var valid = true;
  cardsData = table.rows('.selected').data();

  if (getAmount == '1') {
    var currentamount;
    for (var i = 0; i < cardsData.length; i++) {
      $('#tableServicesMaster')
        .find('tbody > tr.selected')
        .each(function (index, element) {
          currentamount = $(element).find('td.amount-cc input').val();
          var amountArr = currentamount.split(lang.SETT_DECIMAL);
          amountArr[0] = amountArr[0].replace(/[,.]/g, '');
          currentamount = amountArr[0] + '.' + amountArr[1];
          currentamount = parseFloat(currentamount).toFixed(2);

          if (currentamount == 'NaN' || currentamount <= 0) {
            $(element).find('td.amount-cc input').addClass('has-error');
            valid = false;
          }

          if (cardsData[i].idNumber == $(element).find('td.user-id').text()) {
            cardsData[i].amount = currentamount;
          }
        });
    }
  }

  if (!valid) {
    modalBtn = {
      btn1: {
        text: lang.GEN_BTN_ACCEPT,
        action: 'destroy',
      },
    };

    appMessages(currentTitle, lang.GEN_VALID_AMOUNT, lang.SETT_ICON_WARNING, modalBtn);
    $('#tableServicesMaster').find('thead > tr').removeClass('selected');
    table.rows().deselect();
  }

  return valid;
}

function sendRequest(currentAction, currentTitle, currentBtn) {
  if (formValidateTrim(form, currentTitle)) {
    $('#accept').removeClass('send-request');
    $('#accept').removeClass('get-auth-key');
    var cardsInfo = [];

    for (var i = 0; i < cardsData.length; i++) {
      var info = {};
      info['Cardnumber'] = cardsData[i].cardNumber;
      info['idNumber'] = cardsData[i].idNumber;
      info['amount'] = cardsData[i].amount;

      if (currentAction == 'CARD_ASSIGNMENT') {
        info['cardNumberAs'] = cardHolderInf.cardNumber;
      }

      if (currentAction == 'LOCK_TYPES') {
        info['lockType'] = cardHolderInf.lockType;
      }

      cardsInfo.push(JSON.stringify(info));
    }

    currentBtn.html(loader).prop('disabled', true);
    insertFormInput(true);
    data = {
      cards: cardsInfo,
      action: currentAction,
    };

    if (currentAction == 'CREDIT_TO_CARD' && lang.SETT_REFERENCE == 'ON') {
      data.reference = cardHolderInf.reference;
    }

    if (lang.SETT_REMOTE_AUTH == 'OFF') {
      data.pass = cardHolderInf.password ? cryptoPass(cardHolderInf.password) : cryptoPass(cardHolderInf.passAction);
    }

    who = 'Services';
    where = 'ActionMasterAccount';

    callNovoCore(who, where, data, function (response) {
      $('#tableServicesMaster').find('thead > tr').removeClass('selected');
      table.rows().deselect();

      if (currentAction == 'CHECK_BALANCE' || currentAction == 'DEBIT_TO_CARD' || currentAction == 'CREDIT_TO_CARD') {
        currentBtn.html(btnText);
      }

      $('#tableServicesMaster').find('tbody > tr input').val('');

      if (response.data.balance) {
        $('#balance-aviable, #master-balance-aviable').text(response.data.balance);
      }

      if (response.data.balanceConcentratingAccount) {
        $('#balance-acc-concentrator').text(response.data.balanceConcentratingAccount);
      }

      if (currentAction == 'CHECK_BALANCE') {
        cardCheckBalance(response, currentTitle);
      }

      if (currentAction == 'LOCK_TYPES' || currentAction == 'TEMPORARY_UNLOCK' || currentAction == 'CARD_ASSIGNMENT') {
        cardBlockUnblock(response);
      }

      if (currentAction == 'CREDIT_TO_CARD' || currentAction == 'DEBIT_TO_CARD') {
        buildList(response, currentTitle);
      }

      currentBtn.prop('disabled', false);
      insertFormInput(false);
      form.find('input.pwd').val('');
      $('.cover-spin').hide();
    });
  }
}

function cardCheckBalance(response, currentTitle) {
  $.each(response.data.listResponse, function (key, value) {
    $('#tableServicesMaster')
      .find('tbody > tr')
      .each(function (index, element) {
        if (value.cardNumber == $(element).find('td.card-number').text()) {
          $(element).find('td.balance').text(value.balance);

          if (value.balance != '--') {
            $(element).find('td.balance').addClass('text-right');
          } else {
            $(element).find('td.balance').removeClass('text-right');
          }
        }
      });
  });

  if (response.code == 2) {
    modalBtn = {
      btn1: {
        text: lang.GEN_BTN_ACCEPT,
        action: 'destroy',
      },
    };

    inputModal = '<h5 class="regular mr-1">' + response.msg + '</h5>';
    $.each(response.data.listFail, function (index, value) {
      inputModal += '<h6 class="light mr-1">' + value + '</h6>';
    });

    appMessages(currentTitle, inputModal, lang.SETT_ICON_INFO, modalBtn);
  }
}

function cardBlockUnblock(response) {
  if (response.update) {
    $('#accept').addClass('update');
    $('.update').on('click', function () {
      $('#accept').removeClass('update');
      dataTableReload(false);
    });
  }
}

function dataTableReload(resetPaging) {
  $('.hide-table').addClass('hide');
  $('#pre-loader-table').removeClass('hide');
  $('#tableServicesMaster').DataTable().clear();
  $('#tableServicesMaster').DataTable().ajax.reload(null, resetPaging);
}

function buildList(response, currentTitle) {
  if (response.code == 2) {
    modalBtn = {
      btn1: {
        text: lang.GEN_BTN_ACCEPT,
        action: 'destroy',
      },
    };

    inputModal = '<h5 class="regular mr-1">' + response.msg + '</h5>';
    $.each(response.data.listResponse, function (index, value) {
      inputModal += '<div class= "mb-1">';
      inputModal += '<h6 class="light mr-1 mb-0">Tarjeta: ' + value.cardNumber + ' Monto: ' + value.amount + '</h6>';
      inputModal += '<span class=' + (value.codelist != 0 ? 'danger' : '') + '>(' + value.msglist + ')</span>';
      inputModal += '</div>';
    });

    appMessages(currentTitle, inputModal, lang.SETT_ICON_INFO, modalBtn);
    $('#accept').addClass('update');
    $('.update').on('click', function () {
      $('#accept').removeClass('update');
      dataTableReload(false);
    });
  }
}

function verifyOperations() {
  var column;

  if (!access.TRASAL) {
    column = table.column('4');
    column.visible(false);
  }

  if (!access.TRACAR && !access.TRAABO) {
    column = table.column('5');
    column.visible(false);
  }

  if (!access.TRASAL && !access.TRACAR && !access.TRAABO && !access.TRABLQ && !access.TRAASG && !access.TRADBL) {
    $('#password-table').addClass('hide');
    column = table.column('0');
    column.visible(false);
  } else {
    $('#password-table').removeClass('hide');
    column = table.column('0');
    column.visible(true);
  }
}

function formValidateTrim(currentForm, currentTitle) {
  validateForms(currentForm);

  if (cardsData.length == 0) {
    currentForm.validate().resetForm();
    modalBtn = {
      btn1: {
        text: lang.GEN_BTN_ACCEPT,
        action: 'destroy',
      },
    };

    appMessages(currentTitle, lang.VALIDATE_SELECT, lang.SETT_ICON_WARNING, modalBtn);
  }

  if (form.valid()) {
    cardHolderInf = getDataForm(currentForm);
  }

  return cardsData.length > 0 && form.valid();
}

function modalReference(currentAction, currentTitle, currentBtn) {
  var formPassAction = $('#password-table');
  var dataPassAction = getDataForm(formPassAction);
  var password =
    dataPassAction.passAction != undefined && dataPassAction.passAction != null ? dataPassAction.passAction : '';

  modalBtn = {
    btn1: {
      text: lang.GEN_BTN_ACCEPT,
      action: 'none',
    },
    btn2: {
      text: lang.GEN_BTN_CANCEL,
      action: 'destroy',
    },
  };
  inputModal = '<form id="password-modal" name="password-modal" class="row col-auto" onsubmit="return false;">';
  inputModal += '<div class="form-group col-12 pl-0 w-160-ie">';
  inputModal += '<div class="input-group">';
  inputModal += '<input class="form-control" type="text" id="reference" name="reference" autocomplete="off"';
  inputModal += 'placeholder="' + lang.SERVICES_REFERENCE + '">';
  inputModal += '</div>';
  if (lang.SETT_REMOTE_AUTH == 'OFF') {
    inputModal += '<div class="form-group col-12 pl-0 w-160-ie">';
    inputModal += '<div class="input-group">';
    inputModal +=
      '<input class="form-control pwd-input pwd" type="hidden" id="password" name="password" value = ' + password + '>';
    inputModal += '</div>';
  }
  inputModal += '<div class="help-block"></div>';
  inputModal += '</div>';

  if (lang.SETT_REMOTE_AUTH == 'OFF') {
    $('#accept').addClass('send-request');
  } else {
    $('#accept').addClass('get-auth-key');
    currentBtn = btnRemote;
    form = $('#nonForm');
  }

  inputModal += '</form>';
  appMessages(currentTitle, inputModal, lang.SETT_ICON_INFO, modalBtn);
}
