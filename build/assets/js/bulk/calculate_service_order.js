'use strict'
var serviceOrder;
$(function() {
	serviceOrder = $('#resultServiceOrders').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      {"targets": 3,
      render: function ( data, type, row ) {
        return data.length > 20 ?
				  data.substr( 0, 20 ) +'…' :
				  data;
        }
      },
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
		var row = serviceOrder.row( tr );
		var bulk = $(this).closest('tr').attr('bulk')

    if(row.child.isShown()){
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
				// Open this row
        row.child(format(bulk)).show();
        tr.addClass('shown');
    }
	});

	$('#auth-bulk-btn, #cancel-bulk-btn').on('click', function(e) {
		e.preventDefault();

		if($(this).attr('id') == 'auth-bulk-btn') {
			where = 'ServiceOrder'
			var btnAction = $(this);
			btnText = $(this).text();
			$(this).html(loader);
			insertFormInput(true)
		}

		if($(this).attr('id') == 'cancel-bulk-btn') {
			where = 'CancelServiceOrder'
		}

		data = {
			tempOrders: $('#temp-orders').val(),
			bulkNoBill: $('#bulk-no-bil').val()
		}

		verb = 'POST'; who = 'Bulk';
		callNovoCore(verb, who, where, data, function(response) {

			notiSystem(response.title, response.msg, response.icon, response.data);
			btnAction.html(btnText);
			insertFormInput(false);

		});
	});
});

function format (bulk) {
	// `d` is the original data object for the row

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
	table+= 			'<td>Lote nro.</td>';
	table+= 			'<td>Fecha</td>';
	table+= 			'<td>Tipo</td>';
	table+= 			'<td>Cantidad</td>';
	table+= 			'<td>Estado</td>';
	table+= 			'<td>Monto de recarga</td>';
	table+= 			'<td>Monto de comisión</td>';
	table+= 			'<td>Monto de depósito</td>';
	table+= 		'</tr>';
	//table+= 	'</thead>';
	//table+= 	'<tbody>';
	table+= 		body;
	table+= 	'</tbody>';
	table+= '</table>';

	return table;
}
