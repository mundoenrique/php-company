'use strict'
$(function () {
	var file = $('#file-bulk');
	var inputFile = file.next('.js-label-file').html().trim();
	var SelectTypeBulk = $('#type-bulk');
	form = $('#upload-file-form');

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

	$('#upload-file-btn').on('click', function(e) {
		e.preventDefault();
		var btnAction = $(this);
		btnText = btnAction.text().trim();
		validateForms(form);

		if(form.valid()) {
			btnAction.html(loader);
			verb = 'POST'; who = 'Bulk'; where = 'LoadBulk';
			data = {
				file: file[0].files[0],
				typeBulk: SelectTypeBulk.val(),
				formatBulk: $('#type-bulk option:selected').attr('format'),
				typeBulkText: $('#type-bulk option:selected').text()
			}
			insertFormInput(true);
			callNovoCore(verb, who, where, data, function(response) {
				btnAction.html(btnText);
				insertFormInput(false);
				file.val('');
				file.next('.js-label-file').html(inputFile);
				SelectTypeBulk.prop('selectedIndex', 0);
				respLoadBulk[response.code](response);
			});
		}
	});

	const respLoadBulk = {
		2: function(response) {
			notiSystem(response.title, response.msg, response.icon, response.data);
		},
		3: function(response) {
			var msgModal = '';

			$.each(response.msg, function(item, content) {
				if(item == 'header') {
					$.each(content, function(index, value) {
						msgModal+= '<h5>'+value+'</h5>';
					});
				}

				if(item == 'fields') {
					$.each(content, function(index, value) {
						msgModal+= '<h5>'+index+'</h5>';
						$.each(value, function(pos, val) {
							msgModal+= '<h6 class="light mr-1">'+val+'</h6>';
						})
					});
				}
			});

			notiSystem(response.title, msgModal, response.icon, response.data);
		}
	}

  $('#pending-bulk').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('#content-datatable').removeClass('hide');
		},
    "ordering": false,
		"pagingType": "full_numbers",
		"columnDefs": [{
			"targets": 1,
			render: function ( data, type, row ) {
				return data.length > 20 ?
        	data.substr( 0, 20 ) +'…' :
        	data;
      }
		}],
		"table-layout": "fixed",
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

	$('#pending-bulk').on('click', 'button', function(e){
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		form = $(this).parent().find('form')
		insertFormInput(true, form);
		switch(action) {
			case 'Ver':
				form.attr('action', baseURL+'detalle-lote');
				break;
			case 'Confirmar':
				form.attr('action', baseURL+'confirmar-lote');
				break;
			case 'Eliminar':
				var bulkInfo = {
					'bulkId': form.find('input[name="bulkId"]').val(),
					'bulkTicked': form.find('input[name="bulkTicked"]').val(),
					'bulkStatus': form.find('input[name="bulkStatus"]').val()
				}
				console.log(bulkInfo)
				break;
		}
		if(action != 'Eliminar') {
			form.submit();
		}
		insertFormInput(false);
	});
});
