'use strict'
$(function () {
	$('.input-file').each(function () {
    var $input = $(this),
      $label = $input.next('.js-label-file'),
      labelVal = $label.html();

    $input.on('change', function (element) {
      var fileName = '';
      if (element.target.value) fileName = element.target.value.split('\\').pop();
      fileName ? $label.addClass('has-file').find('.js-file-name').html(fileName) : $label.removeClass('has-file').html(labelVal);
    });
	});

	$('#upload-file').on('click', function(e) {
		e.preventDefault();
		ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		verb = 'POST'; who = 'Bulk'; where = 'LoadBulk';
		data = {
			file: $('#file')[0].files[0],
			typeBulk: $('#type-bulk').val(),
			formatBulk: $('#type-bulk option:selected').attr('format')
		}

		callNovoCore(verb, who, where, data, function(response) {
			console.log(response)
		});
	});

  $('#pendingLots').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
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
