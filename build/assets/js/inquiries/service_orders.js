'use strict'
var resultServiceOrders;
$(function() {
	resultServiceOrders = $('#resultServiceOrders').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      {"targets": 3,
      render: function ( data, type, row ) {
        return data.length > 20 ?
				  data.substr( 0, 20 ) +'…' :
				  data;
        }
      }
    ],
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
	});
	$('#resultServiceOrders tbody').on('click', 'button.details-control', function(){
    var tr = $(this).closest('tr');
		var row = resultServiceOrders.row( tr );
		var bulk = $(this).closest('tr').attr('bulk')

    if(row.child.isShown()){
			row.child.hide();
			tr.removeClass('shown');
    } else {
			row.child(format(bulk)).show();
			tr.addClass('shown');
    }
	});
})

function format (bulk) {
	var table, body = '';
	bulk = JSON.parse(bulk)
	$.each(bulk, function(key, value){
		body+= '<tr>';
		body+= 	'<td>'+value.bulkNumber+'</td>';
		body+= 	'<td>'+value.bulkLoadDate+'</td>';
		body+= 	'<td>'+value.bulkLoadType+'</td>';
		body+= 	'<td>'+value.bulkRecords+'</td>';
		body+= 	'<td>'+value.bulkStatus+'</td>';
		body+= 	'<td>'+value.bulkAmount+'</td>';
		body+= 	'<td>'+value.bulkCommisAmount+'</td>';
		body+= 	'<td>'+value.bulkTotalAmount+'</td>';
		body+= '</tr>';
	})
	table = '<table class="detailLot h6 cell-border primary semibold" style="width:100%">';
	table+= 	'<tbody>';
	table+= 		'<tr class="bold" style="margin-left: 0px;">';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_NUMBER+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_DATE+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_TYPE+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_RECORDS+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_STATUS+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_AMOUNT+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_COMMISSION+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_DEPOSIT_AMOUNT+'</td>';
	table+= 		'</tr>';
	table+= 		body;
	table+= 	'</tbody>';
	table+= '</table>';

	return table;
}
