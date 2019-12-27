'use strict'
$(function () {
	var sign = getPropertyOfElement('sign', '#sign-bulk');
	var auth = getPropertyOfElement('auth', '#authorize-bulk');
	var signBulk = $('#sign-bulk').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      {
        "targets": 0,
        "className": "select-checkbox",
				"checkboxes": {"selectRow": true}
      },
      {
        "targets": 2,
        "visible": false
      },
      {
        "targets": 5,
        "visible": false
      }
    ],
    "select": {
      "style": lang.GEN_TABLE_SELECT_SIGN,
      "info": false,
      selector: ':not(.no-select-checkbox, td:nth-child(-n+7))'
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
        "sFirst": "Primera",
        "sLast": "Última",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      "select": {
        "rows": "%d Lote seleccionado"
      }
    }
	});

  $('#sign-bulk').on('click', '.toggle-all', function () {
    $(this).closest("tr").toggleClass("selected");
    if ($(this).closest("tr").hasClass("selected")) {
      signBulk.rows().select();
    } else {
      signBulk.rows().deselect();
    }
  });

  var authorizeBulk = $('#authorize-bulk').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
			{
				"targets": 0,
				"className": "select-checkbox",
				"checkboxes": {"selectRow": true},
				'visible': auth != false,
      	render: function (data, type, row) {
					var content = '';
					if(data != '') {
						content = '<button class="btn px-0" title="En espera de autorización" data-toggle="tooltip">';
						content+= 	'<i class="icon icon-user" aria-hidden="true"></i>';
						content+= '</button>';
					}
					return content;
        }
      },
			{
        "targets": 2,
        "visible": false
      },
      {
        "targets": 5,
        "visible": false
      }
		],
		"select": {
      "style": lang.GEN_TABLE_SELECT_AUTH,
      "info": false,
      selector: ':not(.no-select-checkbox, td:nth-child(-n+7))'
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
        "sFirst": "Primera",
        "sLast": "Última",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
	});

	$('#authorize-bulk').on('click', '.toggle-all', function () {
    $(this).closest("tr").toggleClass("selected");
    if ($(this).closest("tr").hasClass("selected")) {
      authorizeBulk.rows(':not(.no-select-checkbox)').select();
    } else {
      authorizeBulk.rows().deselect();
    }
  });
});
