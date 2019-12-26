'use strict'
$(function () {
	var table = $('#tableAuth').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      {
        "targets": 0,
        "className": "select-checkbox",
        "checkboxes": {"selectRow": true},
      },
      // {"targets": 0, "width": "10px"},
		  // {"targets": 1, "width": "80px"},
      // {"targets": 2, "width": "85px"},
      {"targets": 3,
      // "width": "130px",
      render: function ( data, type, row ) {
			  return data.length > 20 ?
				  data.substr( 0, 20 ) +'…' :
				  data;
        }
      },
      // {"targets": 4, "width": "100px"},
      // {"targets": 5, "width": "80px"},
      // {"targets": 6, "width": "70px"},
      // {"targets": 7, "width": "80px"},
    ],
		"table-layout": "fixed",
    "select": {
      "style": "multi",
      "info": false,
      selector: ':not(td:nth-child(-n+7))'
    },
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
      },
      select: {
        "rows": "%d Lote seleccionado"
      }
    }
  });
  $('#tableAuth').on('click', '.toggle-all', function () {
    $(this).closest("tr").toggleClass("selected");
    if ($(this).closest("tr").hasClass("selected")) {
      table.rows().select();
    } else {
      table.rows().deselect();
    }
  });

  $('#tableFirm').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      // {"targets": 0, "width": "10px"},
		  // {"targets": 1, "width": "80px"},
      // {"targets": 2, "width": "85px"},
      {"targets": 3,
      // "width": "130px",
      render: function ( data, type, row ) {
        return data.length > 20 ?
				  data.substr( 0, 20 ) +'…' :
				  data;
        }
      },
      // {"targets": 4, "width": "95px"},
      // {"targets": 5, "width": "80px"},
      // {"targets": 6, "width": "70px"},
      // {"targets": 7, "width": "70px"},
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
});
