'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	var concenAccount = $('#concenAccount').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

	$('#concenAccount tbody').on('click', 'button.details-user', function (e) {
    var tr = $(this).closest('tr');
    var row = concenAccount.row(tr);

    if (row.child.isShown()) {
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    } else {
      // Open this row
      row.child(format(row.data())).show();
      tr.addClass('shown');
    }
  });
});

function format(e) {
  // `d` is the original data object for the row
  return '<table class="detailLot cell-border h6 semibold tertiary" style="width:100%">' +
    '<tbody>' +
    '<tr class="bold" style="margin-left: 0px;">' +
    '<td>Modulo</td>' +
    '<td>Función</td>' +
    '<td>Fecha</td>' +
    '</tr>' +
    '<tr>' +
    '<td>Usuario</td>' +
    '<td>RESET CLAVE</td>' +
    '<td>20/01/2020 15:56:28</td>' +
    '</tr>' +
    '</tbody>' +
    '</table>'
}
