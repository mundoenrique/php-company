'use strict';
var serviceOrder;
$(function () {
  serviceOrder = $('#resultServiceOrders').DataTable({
    drawCallback: function (d) {
      $('#pre-loader').remove();
      $('.hide-out').removeClass('hide');
    },
    ordering: false,
    pagingType: 'full_numbers',
    columnDefs: [
      {
        targets: 3,
        render: function (data, type, row) {
          return data.length > 20 ? data.substr(0, 20) + '…' : data;
        },
      },
    ],
    language: dataTableLang,
  });

  $('#resultNonBillable').DataTable({
    drawCallback: function (d) {
      $('#pre-loader').remove();
      $('.hide-out').removeClass('hide');
    },
    ordering: false,
    pagingType: 'full_numbers',
    language: dataTableLang,
  });

  $('#resultServiceOrders tbody').on('click', 'button.details-control', function () {
    var oldTr = $(this).closest('tbody').find('tr.shown');
    var oldRow = serviceOrder.row(oldTr);
    var tr = $(this).closest('tr');
    var row = serviceOrder.row(tr);
    var bulk = $(this).closest('tr').attr('bulk');

    if (!tr.hasClass('shown')) {
      oldRow.child.hide();
      oldTr.removeClass('shown');
    }

    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('shown');
    } else {
      row.child(format(bulk)).show();
      tr.addClass('shown');
    }
  });

  $('#auth-bulk-btn').on('click', function (e) {
    e.preventDefault();
    where = 'ServiceOrder';
    var btnAction = $(this);
    btnText = $(this).text();

    form = $('#auth-bulk-form');
    validateForms(form);

    if (form.valid()) {
      $(this).html(loader);
      getServiceREsponse(btnAction);
    }
  });

  $('#cancel-bulk-btn').on('click', function (e) {
    e.preventDefault();
    where = 'CancelServiceOrder';
    form = $('#auth-bulk-form');
    getServiceREsponse();
  });
});

function getServiceREsponse(btn) {
  data = getDataForm(form);
  insertFormInput(true);
  who = 'Bulk';

  callNovoCore(who, where, data, function (response) {
    if (response.code == 0) {
      $(location).attr('href', response.data);
    } else {
      appMessages(response.title, response.msg, response.icon, response.modalBtn);
      btn.html(btnText);
      insertFormInput(false);
      $('#otpCode').val('');
      $('.cover-spin').hide();
    }
  });
}

function format(bulk) {
  var table,
    body = '';
  bulk = JSON.parse(bulk);
  $.each(bulk, function (key, value) {
    body += '<tr>';
    body += '<td>' + value.bulkNumber + '</td>';
    body += '<td>' + value.bulkLoadDate + '</td>';
    body += '<td>' + value.bulkLoadType + '</td>';
    body += '<td>' + value.bulkRecords + '</td>';
    body += '<td>' + value.bulkStatus + '</td>';
    body += '<td>' + value.bulkAmount + '</td>';
    body += '<td>' + value.bulkCommisAmount + '</td>';
    body += '<td>' + value.bulkTotalAmount + '</td>';
    body += '</tr>';
  });
  table = '<table class="detail-lot h6 cell-border primary semibold" style="width:100%">';
  table += '<tbody>';
  table += '<tr class="bold" style="margin-left: 0px;">';
  table += '<td>' + lang.GEN_TABLE_BULK_NUMBER + '</td>';
  table += '<td>' + lang.GEN_TABLE_BULK_DATE + '</td>';
  table += '<td>' + lang.GEN_TABLE_TYPE + '</td>';
  table += '<td>' + lang.GEN_TABLE_RECORDS + '</td>';
  table += '<td>' + lang.GEN_TABLE_STATUS + '</td>';
  table += '<td>' + lang.GEN_TABLE_AMOUNT + '</td>';
  table += '<td>' + lang.GEN_TABLE_COMMISSION_GMF + '</td>';
  table += '<td>' + lang.GEN_TABLE_DEPOSIT_AMOUNT + '</td>';
  table += '</tr>';
  table += body;
  table += '</tbody>';
  table += '</table>';

  return table;
}
