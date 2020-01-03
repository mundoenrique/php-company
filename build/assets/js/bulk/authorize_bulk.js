'use strict'
var signBulk;
var authorizeBulk;
$(function () {
	var sign = getPropertyOfElement('sign', '#sign-bulk');
	var auth = getPropertyOfElement('auth', '#authorize-bulk');
	var modalReq = {};
	var signBulkDtn = $('#sign-bulk-btn');
	var delSignBulkDtn = $('#del-sign-bulk-btn');

	signBulk = $('#sign-bulk').DataTable({
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
		"autoWidth": false,
    "select": {
      "style": lang.GEN_TABLE_SELECT_SIGN,
			selector: ':not(.no-select-checkbox, td:nth-child(-n+65))'
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
    $(this).closest('tr').toggleClass('selected');
    if ($(this).closest('tr').hasClass('selected')) {
      signBulk.rows().select();
    } else {
      signBulk.rows().deselect();
    }
  });

  authorizeBulk = $('#authorize-bulk').DataTable({
		drawCallback: function(d) {
			/* $('#pre-loader').remove();
			$('.visible').removeClass('visible'); */
		},
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
		"autoWidth": false,
		"select": {
      "style": lang.GEN_TABLE_SELECT_AUTH,
      "info": false,
      selector: ':not(.no-select-checkbox, td:nth-child(-n+65))'
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

	$('#authorize-bulk').on('click', '.toggle-all', function () {
    $(this).closest('tr').toggleClass('selected');
    if ($(this).closest('tr').hasClass('selected')) {
      authorizeBulk.rows(':not(.no-select-checkbox)').select();
    } else {
      authorizeBulk.rows().deselect();
    }
	});

	$('#sign-bulk-btn, #del-sign-bulk-btn, #auth-bulk-btn, #del-auth-bulk-btn').on('click', function(e) {
		e.preventDefault()
		var action = $(this).text().trim();
		var thisId = $(this).attr('id');
		var passwordSignAuht = $(this).closest('form').find('input[type=password]');
		form = $(this).closest('form');
		modalReq['active'] = false;
		SignDeleteBulk(form, action, thisId, passwordSignAuht, modalReq)
	});

	$('#sign-bulk, #authorize-bulk').on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		$(this).closest('tr').addClass('selected');

		switch(action) {
			case lang.GEN_BTN_SEE:
				/* form.attr('action', baseURL+'detalle-lote');
				form.append('<input type="hidden" name="bulkView" value="detail">'); */
				break;
			case lang.GEN_BTN_CONFIRM:
				/* form.attr('action', baseURL+'confirmar-lote');
				form.append('<input type="hidden" name="bulkView" value="confirm">'); */
				break;
			case lang.GEN_BTN_DELETE:
				var oldId = $('#accept').attr('id');
				var currentIdBtn = 'delete-bulk-btn';
				var cancelDelete = $('#cancel');
				$('#accept').attr('id', currentIdBtn);
				var bulkNum = $(this).closest('tr').find('td:nth-child(2)').text();
				var inputModal;
				modalReq['table'] = $(this).closest('table');
				data = {
					btn1: {
						text: lang.GEN_BTN_DELETE,
						action: 'close'
					},
					btn2: {
						action: 'close'
					}
				}
				inputModal = '<form id="delete-bulk-form" class="form-group">';
				inputModal+= '<span class="regular"> '+lang.BULK_DELETE+': '+bulkNum+'</span>';
				inputModal+= 		'<input id="password" class="form-control mt-2 h6 col-9" name="password" type="password" ';
				inputModal+=		'autocomplete="off" placeholder="'+lang.GEN_PLACE_PASSWORD+'">';
				inputModal+= 		'<div class="help-block"></div>';
				inputModal+= '</form>';
				notiSystem(lang.BULK_DELETE_TITLE, inputModal, lang.GEN_ICON_INFO, data);
				$('#'+currentIdBtn).on('click', function(e) {
					e.preventDefault();
					form = $('#delete-bulk-form');
					var passwordDelete = $('#password');
					modalReq['active'] = true;
					modalReq['oldId'] = oldId;
					SignDeleteBulk(form, action, currentIdBtn, passwordDelete, modalReq);
				});
				cancelDelete.on('click', function(e){
					$('#'+currentIdBtn)
					.off('click')
					.attr('id', oldId);
				});
				break;
		}

		/* if(action != lang.GEN_BTN_DELETE) {
			form.submit();
		}*/

	});
});
/**
 * @info Firma o elimina un lote
 * @author J. Enrique Peñaloza Piñero
 * @date December 27th, 2019
 */
function SignDeleteBulk(currentForm, action, btnId, passwordInput, modalReq) {
	formInputTrim(currentForm);
	validateForms(currentForm);
	var bulkData;
	var tableSelected
	switch(currentForm.attr('id')) {
		case 'sign-bulk-form':
			tableSelected = signBulk;
			break;
		case 'auth-bulk-form':
			tableSelected = authorizeBulk;
			break;
		default:
			if(modalReq.table.attr('id') == 'sign-bulk') {
				tableSelected = signBulk;
			}

			if(modalReq.table.attr('id') == 'authorize-bulk') {
				tableSelected = authorizeBulk;
			}
	}

	bulkData = tableSelected.rows({ selected: true }).data();

	if(bulkData.length == 0 ) {
		currentForm.validate().resetForm();
		currentForm.find('.bulk-select').text(lang.BULK_SELECT);
	}

	if(bulkData.length > 0 && form.valid()) {
		var bulkInfo = [];
		var btnAction = $('#'+btnId);
		btnText = btnAction.text().trim();
		btnAction.html(loader);

		for(var i = 0; i < bulkData.length; i++) {
			var info = {};
			info['bulkNumber'] = bulkData[i][1];
			info['bulkId'] = bulkData[i][2];
			info['bulkIdType'] = bulkData[i][5];
			bulkInfo.push(JSON.stringify(info));
		}

		inputPass = crytoPass(passwordInput.val());
		data = {
			modalReq: modalReq.active,
			bulk: bulkInfo,
			pass: inputPass
		}

		if(currentForm.attr('id') == 'auth-bulk-form') {
			data['typeOrder'] = $('#type-order').val()
		}

		if(modalReq.active) {
			btnAction
			.off('click')
			.prop('disabled', true)
			.attr('id', modalReq.oldId);
		}

		passwordInput.val('');
		tableSelected.rows().deselect();
		insertFormInput(true);

		switch(action) {
			case lang.GEN_BTN_SIGN:
				where = 'SignBulkList';
				break;
			case lang.GEN_BTN_AUTHORIZE:
				where = 'AuthorizeBulk';
				break;
			case lang.GEN_BTN_DELETE:
				where = 'DeleteConfirmBulk';
				break;
		}

		verb = 'POST'; who = 'Bulk';
		callNovoCore(verb, who, where, data, function(response) {
			btnAction.html(btnText);
			insertFormInput(false);
			notiSystem(response.title, response.msg, response.icon, response.data);
		});
	}
}
